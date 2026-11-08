<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Collection;
use App\Models\FacultyProfile;
use App\Models\Memo;
use App\Models\Release;
use App\Models\Resolution;
use App\Models\TrainingAnnouncement;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Show the home/welcome page.
     */
    public function home(): View
    {
        $campuses = Campus::where('is_active', true)
            ->orderBy('name')
            ->limit(9)
            ->get(['id', 'name', 'code']);

        return view('welcome', compact('campuses'));
    }

    /**
     * Show the organization chart.
     */
    public function organization(): View
    {
        // Get all campuses
        $campuses = \App\Models\Campus::where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get university-level officers (President, VP, etc.)
        $universityOfficers = FacultyProfile::whereNotNull('officer_position')
            ->with(['campus', 'academicRank'])
            ->get()
            ->sortBy(function ($officer) {
                $order = [
                    'President' => 1,
                    'Vice President' => 2,
                    'Secretary' => 3,
                    'Treasurer' => 4,
                    'Assistant Treasurer' => 5,
                    'Auditor' => 6,
                    'Business Manager' => 7,
                    'PIO' => 8,
                ];

                return $order[$officer->officer_position] ?? 99;
            });

        // Get campus-level officers (campus_position field)
        $campusOfficers = FacultyProfile::whereNotNull('campus_position')
            ->with(['campus', 'academicRank', 'college'])
            ->get()
            ->groupBy('campus_id');

        return view('organization', compact('campuses', 'universityOfficers', 'campusOfficers'));
    }

    /**
     * Show the admin transparency wall dashboard.
     */
    public function transparencyDashboard(): View
    {
        return view('admin.transparency.dashboard', [
            'memosCount' => Memo::count(),
            'resolutionsCount' => Resolution::count(),
            'collectionsCount' => Collection::count(),
            'releasesCount' => Release::count(),
            'trainingsCount' => TrainingAnnouncement::count(),
            'publishedMemosCount' => Memo::published()->count(),
            'publishedResolutionsCount' => Resolution::published()->count(),
            'publishedCollectionsCount' => Collection::published()->count(),
            'publishedReleasesCount' => Release::published()->count(),
            'publishedTrainingsCount' => TrainingAnnouncement::published()->count(),
        ]);
    }
}
