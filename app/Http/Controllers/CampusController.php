<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCampusRequest;
use App\Http\Requests\UpdateCampusRequest;
use App\Models\Campus;
use App\Models\FacultyProfile;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    /**
     * Display a public listing of the resource.
     */
    public function index(Request $request)
    {
        $campuses = Campus::withCount(['facultyProfiles', 'colleges'])
            ->where('is_active', true)
            ->when($request->search, function ($q, $s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('code', 'like', "%{$s}%");
            })
            ->orderBy('name')
            ->paginate(15);

        return view('campuses.index', compact('campuses'));
    }

    /**
     * Display admin listing of the resource.
     */
    public function adminIndex(Request $request)
    {
        $this->authorize('viewAny', Campus::class);

        $campuses = Campus::withCount(['facultyProfiles', 'colleges'])
            ->when($request->search, function ($q, $s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('code', 'like', "%{$s}%");
            })
            ->orderBy('name')
            ->paginate(15);

        return view('admin.campuses.index', compact('campuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Campus::class);

        return view('admin.campuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCampusRequest $request)
    {
        $this->authorize('create', Campus::class);

        Campus::create($request->validated());

        return redirect()
            ->route('admin.campuses.index')
            ->with('success', 'Campus created successfully.');
    }

    /**
     * Display the specified resource (public).
     */
    public function show(Campus $campus)
    {
        if (! $campus->is_active) {
            abort(404);
        }

        $campus->loadCount([
            'facultyProfiles as total_members',
            'facultyProfiles as active_members' => fn ($q) => $q->where('membership_status', 'active'),
        ]);

        // Get campus officers (sorted by position hierarchy)
        $campusOfficers = FacultyProfile::where('campus_id', $campus->id)
            ->whereNotNull('campus_position')
            ->with(['academicRank', 'college'])
            ->get()
            ->sortBy(function ($officer) {
                $order = [
                    'Campus President' => 1,
                    'Campus Vice President' => 2,
                    'Campus Secretary' => 3,
                    'Campus Treasurer' => 4,
                    'Campus Auditor' => 5,
                    'Campus PIO' => 6,
                ];

                return $order[$officer->campus_position] ?? 99;
            });

        // Get regular members (no campus position)
        $members = FacultyProfile::where('campus_id', $campus->id)
            ->whereNull('campus_position')
            ->where('membership_status', 'active')
            ->with(['academicRank', 'college'])
            ->orderBy('surname')
            ->get();

        // Get colleges in this campus
        $colleges = $campus->colleges()->withCount('facultyProfiles')->get();

        return view('campuses.show', compact('campus', 'campusOfficers', 'members', 'colleges'));
    }

    /**
     * Display the specified resource (admin).
     */
    public function adminShow(Campus $campus)
    {
        $this->authorize('view', $campus);

        $campus->loadCount([
            'facultyProfiles as total_members',
            'facultyProfiles as active_members' => fn ($q) => $q->where('membership_status', 'active'),
        ]);

        $recentMembers = FacultyProfile::where('campus_id', $campus->id)
            ->with(['academicRank', 'college'])
            ->latest()
            ->take(10)
            ->get();

        // Statistics by rank
        $membersByRank = FacultyProfile::where('campus_id', $campus->id)
            ->where('membership_status', 'active')
            ->selectRaw('academic_rank_id, COUNT(*) as count')
            ->groupBy('academic_rank_id')
            ->with('academicRank:id,name')
            ->get();

        return view('admin.campuses.show', compact('campus', 'recentMembers', 'membersByRank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Campus $campus)
    {
        $this->authorize('update', $campus);

        return view('admin.campuses.edit', compact('campus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCampusRequest $request, Campus $campus)
    {
        $this->authorize('update', $campus);

        $campus->update($request->validated());

        return redirect()
            ->route('admin.campuses.show', $campus)
            ->with('success', 'Campus updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campus $campus)
    {
        $this->authorize('delete', $campus);

        // Check if campus has members
        if ($campus->facultyProfiles()->exists()) {
            return redirect()
                ->route('admin.campuses.index')
                ->with('error', 'Cannot delete campus with existing members.');
        }

        $campus->delete();

        return redirect()
            ->route('admin.campuses.index')
            ->with('success', 'Campus deleted successfully.');
    }

    /**
     * Get campus statistics.
     */
    public function statistics(Campus $campus)
    {
        $this->authorize('view', $campus);

        $stats = [
            'total_members' => $campus->facultyProfiles()->count(),
            'active_members' => $campus->facultyProfiles()->where('membership_status', 'active')->count(),
            'inactive_members' => $campus->facultyProfiles()->where('membership_status', 'inactive')->count(),
            'retired_members' => $campus->facultyProfiles()->where('membership_status', 'retired')->count(),
            'total_claims' => \App\Models\Claim::whereHas('facultyProfile', fn ($q) => $q->where('campus_id', $campus->id))->count(),
            'pending_claims' => \App\Models\Claim::forCampus($campus->id)->pending()->count(),
        ];

        return response()->json($stats);
    }
}
