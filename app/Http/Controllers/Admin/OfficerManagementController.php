<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campus;
use App\Models\FacultyProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OfficerManagementController extends Controller
{
    /**
     * UFA officer positions and their corresponding system roles.
     * 'President' and 'Treasurer' map to special roles with elevated permissions.
     * All others remain as campus_president with their officer_position set.
     */
    const POSITION_ROLE_MAP = [
        'President' => 'university_president',
        'Vice President' => 'campus_president',
        'Secretary' => 'campus_president',
        'Treasurer' => 'university_treasurer',
        'Assistant Treasurer' => 'campus_president',
        'Auditor' => 'campus_president',
        'Business Manager' => 'campus_president',
        'PIO' => 'campus_president',
    ];

    /**
     * Display the officer management page.
     */
    public function index(): View
    {
        $campuses = Campus::active()
            ->with(['facultyProfiles' => fn ($q) => $q->with('user')])
            ->orderBy('name')
            ->get();

        // Current campus presidents (users with campus_president, university_president, or university_treasurer role)
        $campusPresidents = User::whereIn('role', ['campus_president', 'university_president', 'university_treasurer'])
            ->whereNotNull('campus_id')
            ->where('is_active', true)
            ->with(['facultyProfile', 'campus'])
            ->get()
            ->keyBy('campus_id');

        // University-level officers (by officer_position on faculty_profiles)
        $universityOfficers = FacultyProfile::whereNotNull('officer_position')
            ->with(['user', 'campus'])
            ->get()
            ->keyBy('officer_position');

        // All active faculty members (potential candidates for campus president)
        $allFaculty = FacultyProfile::whereHas('user', fn ($q) => $q->where('is_active', true))
            ->with(['user', 'campus'])
            ->orderBy('surname')
            ->get()
            ->groupBy('campus_id');

        $officerPositions = array_keys(self::POSITION_ROLE_MAP);

        return view('admin.officers.index', compact(
            'campuses',
            'campusPresidents',
            'universityOfficers',
            'allFaculty',
            'officerPositions'
        ));
    }

    /**
     * Assign or change the campus president for a campus.
     * Demotes the previous president back to faculty.
     */
    public function assignCampusPresident(Request $request, Campus $campus): RedirectResponse
    {
        $validated = $request->validate([
            'faculty_profile_id' => ['required', 'exists:faculty_profiles,id'],
        ]);

        $newProfile = FacultyProfile::with('user')->findOrFail($validated['faculty_profile_id']);

        // Ensure the faculty member belongs to this campus
        if ($newProfile->campus_id !== $campus->id) {
            return back()->with('error', 'The selected member does not belong to this campus.');
        }

        DB::transaction(function () use ($campus, $newProfile) {
            // Demote the current campus president (if any) back to faculty
            $currentPresident = User::where('campus_id', $campus->id)
                ->whereIn('role', ['campus_president', 'university_president', 'university_treasurer'])
                ->where('id', '!=', $newProfile->user_id)
                ->get();

            foreach ($currentPresident as $user) {
                // Clear their UFA officer position if they have one
                if ($user->facultyProfile) {
                    $user->facultyProfile->update(['officer_position' => null]);
                }
                $user->update(['role' => 'faculty']);
            }

            // Promote the new member to campus president
            $newProfile->user->update([
                'role' => 'campus_president',
                'campus_id' => $campus->id,
            ]);
        });

        return back()->with('success', "{$newProfile->display_name} has been assigned as campus president for {$campus->name}.");
    }

    /**
     * Remove a campus president (demote back to faculty).
     */
    public function removeCampusPresident(Campus $campus): RedirectResponse
    {
        DB::transaction(function () use ($campus) {
            $currentPresident = User::where('campus_id', $campus->id)
                ->whereIn('role', ['campus_president', 'university_president', 'university_treasurer'])
                ->get();

            foreach ($currentPresident as $user) {
                if ($user->facultyProfile) {
                    $user->facultyProfile->update(['officer_position' => null]);
                }
                $user->update(['role' => 'faculty']);
            }
        });

        return back()->with('success', "Campus president for {$campus->name} has been removed.");
    }

    /**
     * Assign a UFA officer position (University President, Treasurer, etc.) to a campus president.
     * This updates both the officer_position on faculty_profiles and the role on users.
     */
    public function assignOfficerPosition(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'string', 'in:'.implode(',', array_keys(self::POSITION_ROLE_MAP))],
            'faculty_profile_id' => ['required', 'exists:faculty_profiles,id'],
        ]);

        $position = $validated['position'];
        $newProfile = FacultyProfile::with('user')->findOrFail($validated['faculty_profile_id']);
        $newRole = self::POSITION_ROLE_MAP[$position];

        // Ensure the selected member is a campus president (or already an officer)
        if (! in_array($newProfile->user->role, ['campus_president', 'university_president', 'university_treasurer'])) {
            return back()->with('error', 'Only campus presidents can be assigned UFA officer positions. Please assign them as campus president first.');
        }

        DB::transaction(function () use ($position, $newProfile, $newRole) {
            // Remove the position from whoever currently holds it
            $currentHolder = FacultyProfile::where('officer_position', $position)->first();
            if ($currentHolder && $currentHolder->id !== $newProfile->id) {
                $currentHolder->update(['officer_position' => null]);

                // Reset their role back to campus_president (they're still a campus president)
                if ($currentHolder->user && in_array($currentHolder->user->role, ['university_president', 'university_treasurer'])) {
                    $currentHolder->user->update(['role' => 'campus_president']);
                }
            }

            // If this person previously held a different officer position, clear the old one's role effects
            if ($newProfile->officer_position && $newProfile->officer_position !== $position) {
                // They're moving to a new position — just clear the old position
                // (The new role will be set below)
            }

            // Assign the new position
            $newProfile->update(['officer_position' => $position]);

            // Update the user's system role based on the position
            $newProfile->user->update(['role' => $newRole]);
        });

        return back()->with('success', "{$newProfile->display_name} has been assigned as UFA {$position}.");
    }

    /**
     * Remove a UFA officer position from a campus president.
     */
    public function removeOfficerPosition(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position' => ['required', 'string', 'in:'.implode(',', array_keys(self::POSITION_ROLE_MAP))],
        ]);

        $position = $validated['position'];

        DB::transaction(function () use ($position) {
            $currentHolder = FacultyProfile::where('officer_position', $position)->with('user')->first();

            if ($currentHolder) {
                $currentHolder->update(['officer_position' => null]);

                // Reset role to campus_president (they remain a campus president)
                if ($currentHolder->user && in_array($currentHolder->user->role, ['university_president', 'university_treasurer'])) {
                    $currentHolder->user->update(['role' => 'campus_president']);
                }
            }
        });

        return back()->with('success', "The {$position} position has been vacated.");
    }
}
