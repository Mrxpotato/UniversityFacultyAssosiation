<?php

namespace App\Services;

use App\Models\Claim;
use App\Models\Due;
use App\Models\FacultyProfile;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected FacultyProfileService $facultyProfileService;

    protected ClaimService $claimService;

    protected DuesService $duesService;

    public function __construct(
        FacultyProfileService $facultyProfileService,
        ClaimService $claimService,
        DuesService $duesService
    ) {
        $this->facultyProfileService = $facultyProfileService;
        $this->claimService = $claimService;
        $this->duesService = $duesService;
    }

    /**
     * Get admin dashboard data
     */
    public function getAdminDashboardData(?int $campusId = null): array
    {
        return [
            'members' => $this->facultyProfileService->getStatistics($campusId),
            'claims' => $this->claimService->getStatistics($campusId),
            'dues' => $this->duesService->getStatistics($campusId, now()->format('Y-m')),
            'recent_claims' => $this->getRecentClaims($campusId),
            'pending_claims' => $this->getPendingClaimsCount($campusId),
            'monthly_collections' => $this->getMonthlyCollections($campusId),
            'members_by_campus' => $this->getMembersByCampus(),
            'claims_by_type' => $this->getClaimsByType($campusId),
        ];
    }

    /**
     * Get faculty dashboard data
     */
    public function getFacultyDashboardData(FacultyProfile $profile): array
    {
        $claimsSummary = $this->getMemberClaimsSummary($profile);

        return [
            'profile' => $profile->load(['campus', 'academicRank', 'college']),
            'facultyProfile' => $profile,
            'claims_summary' => $claimsSummary,
            'stats' => [
                'totalClaims' => $claimsSummary['total'] ?? 0,
                'pendingClaims' => $claimsSummary['pending'] ?? 0,
                'beneficiariesCount' => $profile->beneficiaries()->active()->count(),
            ],
            'recentClaims' => $profile->claims()->with('claimType')->latest()->take(5)->get(),
            'beneficiaries_count' => $profile->beneficiaries()->active()->count(),
        ];
    }

    /**
     * Get recent claims
     */
    protected function getRecentClaims(?int $campusId = null, int $limit = 10)
    {
        $query = Claim::with(['facultyProfile.user', 'claimType'])
            ->latest();

        if ($campusId) {
            $query->forCampus($campusId);
        }

        return $query->take($limit)->get();
    }

    /**
     * Get pending claims count
     */
    protected function getPendingClaimsCount(?int $campusId = null): int
    {
        $query = Claim::pending();

        if ($campusId) {
            $query->forCampus($campusId);
        }

        return $query->count();
    }

    /**
     * Get monthly collections for the last 12 months
     */
    protected function getMonthlyCollections(?int $campusId = null): array
    {
        $driver = config('database.default');

        if ($driver === 'sqlite') {
            $monthSelect = "strftime('%Y-%m', paid_date) as month";
            $monthGroup = "strftime('%Y-%m', paid_date)";
        } else {
            $monthSelect = "DATE_FORMAT(paid_date, '%Y-%m') as month";
            $monthGroup = "DATE_FORMAT(paid_date, '%Y-%m')";
        }

        $query = Due::selectRaw("{$monthSelect}, SUM(amount) as total")
            ->where('status', 'paid')
            ->whereNotNull('paid_date')
            ->whereDate('paid_date', '>=', now()->subMonths(12))
            ->groupBy(DB::raw($monthGroup))
            ->orderBy('month');

        if ($campusId) {
            $query->forCampus($campusId);
        }

        return $query->get()->toArray();
    }

    /**
     * Get members count by campus
     */
    protected function getMembersByCampus(): array
    {
        return FacultyProfile::selectRaw('campus_id, COUNT(*) as count')
            ->where('membership_status', 'active')
            ->groupBy('campus_id')
            ->with('campus:id,name,code')
            ->get()
            ->map(function ($item) {
                return [
                    'campus' => $item->campus ? $item->campus->name : 'Unknown',
                    'code' => $item->campus ? $item->campus->code : 'N/A',
                    'count' => $item->count,
                ];
            })
            ->toArray();
    }

    /**
     * Get claims by type
     */
    protected function getClaimsByType(?int $campusId = null): array
    {
        $query = Claim::selectRaw('claim_type_id, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('claim_type_id')
            ->with('claimType:id,name');

        if ($campusId) {
            $query->forCampus($campusId);
        }

        return $query->get()->map(function ($item) {
            return [
                'type' => $item->claimType ? $item->claimType->name : 'Unknown',
                'count' => $item->count,
                'total' => $item->total,
            ];
        })->toArray();
    }

    /**
     * Get member claims summary using aggregate queries (avoids N+1)
     */
    protected function getMemberClaimsSummary(FacultyProfile $profile): array
    {
        $stats = $profile->claims()
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'released' THEN 1 ELSE 0 END) as released,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                COALESCE(SUM(amount), 0) as total_claimed,
                COALESCE(SUM(CASE WHEN status = 'released' THEN amount ELSE 0 END), 0) as total_released
            ")
            ->first();

        return [
            'total' => (int) ($stats->total ?? 0),
            'pending' => (int) ($stats->pending ?? 0),
            'approved' => (int) ($stats->approved ?? 0),
            'released' => (int) ($stats->released ?? 0),
            'rejected' => (int) ($stats->rejected ?? 0),
            'total_claimed' => (float) ($stats->total_claimed ?? 0),
            'total_released' => (float) ($stats->total_released ?? 0),
        ];
    }
}
