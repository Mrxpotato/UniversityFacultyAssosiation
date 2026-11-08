<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Claim;
use App\Models\ClaimType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClaimsMonitoringController extends Controller
{
    /**
     * Display the claims monitoring dashboard.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $campusId = null;

        // Campus presidents are scoped to their campus
        if ($user->isCampusPresident() && $user->campus_id) {
            $campusId = $user->campus_id;
        } elseif ($request->filled('campus_id')) {
            $campusId = $request->campus_id;
        }

        $dateFrom = $request->get('date_from', now()->startOfYear()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Base query
        $baseQuery = Claim::query()
            ->when($campusId, fn ($q) => $q->forCampus($campusId))
            ->whereDate('date_applied', '>=', $dateFrom)
            ->whereDate('date_applied', '<=', $dateTo);

        // Status breakdown
        $statusBreakdown = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as count, COALESCE(SUM(amount), 0) as total_amount')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Claims by type
        $claimsByType = (clone $baseQuery)
            ->selectRaw('claim_type_id, COUNT(*) as count, COALESCE(SUM(amount), 0) as total_amount')
            ->groupBy('claim_type_id')
            ->with('claimType:id,name')
            ->get();

        // Claims by campus
        $claimsByCampus = Claim::query()
            ->join('faculty_profiles', 'claims.faculty_profile_id', '=', 'faculty_profiles.id')
            ->join('campuses', 'faculty_profiles.campus_id', '=', 'campuses.id')
            ->when($campusId, fn ($q) => $q->where('faculty_profiles.campus_id', $campusId))
            ->whereDate('claims.date_applied', '>=', $dateFrom)
            ->whereDate('claims.date_applied', '<=', $dateTo)
            ->selectRaw('campuses.name as campus_name, campuses.code as campus_code, COUNT(*) as count, COALESCE(SUM(claims.amount), 0) as total_amount')
            ->groupBy('campuses.name', 'campuses.code')
            ->orderByDesc('count')
            ->get();

        // Monthly trend (last 12 months)
        $driver = config('database.default');
        if ($driver === 'sqlite') {
            $monthSelect = "strftime('%Y-%m', date_applied) as month";
            $monthGroup = "strftime('%Y-%m', date_applied)";
        } else {
            $monthSelect = "DATE_FORMAT(date_applied, '%Y-%m') as month";
            $monthGroup = "DATE_FORMAT(date_applied, '%Y-%m')";
        }

        $monthlyTrend = Claim::query()
            ->when($campusId, fn ($q) => $q->forCampus($campusId))
            ->whereDate('date_applied', '>=', now()->subMonths(12))
            ->selectRaw("{$monthSelect}, status, COUNT(*) as count, COALESCE(SUM(amount), 0) as total_amount")
            ->groupBy(DB::raw($monthGroup), 'status')
            ->orderBy('month')
            ->get()
            ->groupBy('month');

        // Summary totals
        $totalClaims = (clone $baseQuery)->count();
        $totalAmount = (clone $baseQuery)->sum('amount');
        $releasedAmount = (clone $baseQuery)->where('status', 'released')->sum('amount');
        $pendingCount = (clone $baseQuery)->whereIn('status', ['pending', 'under_review'])->count();
        $approvedCount = (clone $baseQuery)->where('status', 'approved')->count();
        $avgProcessingDays = (clone $baseQuery)
            ->whereNotNull('date_approved')
            ->whereNotNull('date_applied')
            ->selectRaw($driver === 'sqlite'
                ? 'AVG(julianday(date_approved) - julianday(date_applied)) as avg_days'
                : 'AVG(DATEDIFF(date_approved, date_applied)) as avg_days')
            ->value('avg_days');

        // Recent activity
        $recentClaims = Claim::with(['facultyProfile.user', 'facultyProfile.campus', 'claimType'])
            ->when($campusId, fn ($q) => $q->forCampus($campusId))
            ->latest()
            ->take(10)
            ->get();

        $campuses = Campus::active()->orderBy('name')->get();
        $claimTypes = ClaimType::active()->get();

        return view('claims.monitoring', compact(
            'statusBreakdown',
            'claimsByType',
            'claimsByCampus',
            'monthlyTrend',
            'totalClaims',
            'totalAmount',
            'releasedAmount',
            'pendingCount',
            'approvedCount',
            'avgProcessingDays',
            'recentClaims',
            'campuses',
            'claimTypes',
            'campusId',
            'dateFrom',
            'dateTo'
        ));
    }
}
