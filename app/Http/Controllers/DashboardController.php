<?php

namespace App\Http\Controllers;

use App\Models\FundTransaction;
use App\Models\MonthlyDeduction;
use App\Models\TrainingSeminar;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isUniversityOfficer()) {
            return $this->adminDashboard($request);
        }

        if ($user->isCampusPresident()) {
            return $this->adminDashboard($request, $user->campus_id);
        }

        return $this->facultyDashboard($request);
    }

    /**
     * Admin dashboard (optionally scoped to a campus)
     */
    protected function adminDashboard(Request $request, ?int $forceCampusId = null)
    {
        $campusId = $forceCampusId ?? $request->get('campus_id');
        $cacheKey = 'admin_dashboard_'.($campusId ?? 'all');

        $raw = Cache::remember($cacheKey, 300, function () use ($campusId) {
            return $this->dashboardService->getAdminDashboardData($campusId);
        });

        $data = [
            'stats' => [
                'totalMembers' => $raw['members']['total'] ?? 0,
                'activeMembers' => $raw['members']['active'] ?? 0,
                'inactiveMembers' => $raw['members']['inactive'] ?? 0,
                'retiredMembers' => $raw['members']['retired'] ?? 0,
                'pendingClaims' => $raw['claims']['pending'] ?? 0,
                'totalDeductionsCollected' => (float) FundTransaction::inflows()->sum('amount'),
                'totalClaimsReleased' => $raw['claims']['total_released_amount'] ?? 0,
                'fundBalance' => FundTransaction::currentBalance(),
            ],
            'recentClaims' => $raw['recent_claims'] ?? collect(),
            'membersByCampus' => $raw['members_by_campus'] ?? [],
            'claimsByType' => $raw['claims_by_type'] ?? [],
            'monthlyCollections' => $raw['monthly_collections'] ?? [],
            'upcomingTrainings' => Cache::remember('admin_upcoming_trainings_'.($campusId ?? 'all'), 300, function () {
                return TrainingSeminar::where('start_datetime', '>=', now())
                    ->orderBy('start_datetime')
                    ->take(5)
                    ->get();
            }),
            'pendingDeductions' => MonthlyDeduction::with(['facultyProfile.user', 'facultyProfile.campus', 'claimType'])
                ->pending()
                ->forMonth(now()->format('Y-m'))
                ->when($campusId, fn ($q) => $q->forCampus($campusId))
                ->latest()
                ->take(5)
                ->get(),
            'forcedCampusId' => $forceCampusId,
            'members' => $raw['members'] ?? [],
            'claims' => $raw['claims'] ?? [],
        ];

        return view('dashboard.admin', $data);
    }

    /**
     * Faculty dashboard
     */
    protected function facultyDashboard(Request $request)
    {
        $user = auth()->user();
        $profile = $user->facultyProfile;

        if (! $profile) {
            return view('dashboard.no-profile');
        }

        $data = $this->dashboardService->getFacultyDashboardData($profile);

        // Add upcoming trainings for faculty dashboard
        $data['upcomingTrainings'] = TrainingSeminar::where('start_datetime', '>=', now())
            ->published()
            ->orderBy('start_datetime')
            ->take(5)
            ->get();

        return view('dashboard.faculty', $data);
    }
}
