<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFacultyProfileRequest;
use App\Http\Requests\UpdateFacultyProfileRequest;
use App\Models\AcademicRank;
use App\Models\Campus;
use App\Models\FacultyProfile;
use App\Services\FacultyProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FacultyProfileController extends Controller
{
    protected FacultyProfileService $facultyProfileService;

    public function __construct(FacultyProfileService $facultyProfileService)
    {
        $this->facultyProfileService = $facultyProfileService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', FacultyProfile::class);

        $user = auth()->user();
        $filters = $request->only(['search', 'campus_id', 'academic_rank_id', 'membership_status', 'sort', 'direction']);

        // Campus presidents can only see members from their campus
        if ($user->isCampusPresident()) {
            $filters['campus_id'] = $user->campus_id;
        }

        $facultyProfiles = $this->facultyProfileService->getPaginated($filters);

        $campuses = Campus::active()->get();
        $academicRanks = AcademicRank::active()->ordered()->get();

        return view('faculty-profiles.index', compact('facultyProfiles', 'campuses', 'academicRanks', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', FacultyProfile::class);

        $user = auth()->user();

        // Campus presidents can only create members for their campus
        if ($user->isCampusPresident()) {
            $campuses = Campus::active()->where('id', $user->campus_id)->get();
        } else {
            $campuses = Campus::active()->get();
        }

        $academicRanks = AcademicRank::active()->ordered()->get();

        return view('faculty-profiles.create', compact('campuses', 'academicRanks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacultyProfileRequest $request)
    {
        $this->authorize('create', FacultyProfile::class);

        $profile = $this->facultyProfileService->create($request->validated());

        return redirect()
            ->route('admin.faculty-profiles.show', $profile)
            ->with('success', 'Faculty profile created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FacultyProfile $facultyProfile)
    {
        $this->authorize('view', $facultyProfile);

        $facultyProfile->load([
            'user', 'campus', 'academicRank', 'college',
            'beneficiaries' => fn ($q) => $q->active(),
            'claims' => fn ($q) => $q->latest()->take(5),
            'dues' => fn ($q) => $q->latest()->take(6),
        ]);

        return view('faculty-profiles.show', compact('facultyProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FacultyProfile $facultyProfile)
    {
        $this->authorize('update', $facultyProfile);

        $campuses = Campus::active()->get();
        $academicRanks = AcademicRank::active()->ordered()->get();

        return view('faculty-profiles.edit', compact('facultyProfile', 'campuses', 'academicRanks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacultyProfileRequest $request, FacultyProfile $facultyProfile)
    {
        $this->authorize('update', $facultyProfile);

        $this->facultyProfileService->update($facultyProfile, $request->validated());

        return redirect()
            ->route('admin.faculty-profiles.show', $facultyProfile)
            ->with('success', 'Faculty profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FacultyProfile $facultyProfile)
    {
        $this->authorize('delete', $facultyProfile);

        $this->facultyProfileService->delete($facultyProfile);

        return redirect()
            ->route('admin.faculty-profiles.index')
            ->with('success', 'Faculty profile deleted successfully.');
    }

    /**
     * Archive a faculty profile (mark as retired and soft-delete).
     */
    public function archive(FacultyProfile $facultyProfile)
    {
        $this->authorize('delete', $facultyProfile);

        $this->facultyProfileService->archive($facultyProfile);

        return redirect()
            ->route('admin.faculty-profiles.index')
            ->with('success', $facultyProfile->display_name.' has been archived successfully.');
    }

    /**
     * Deactivate a member's account (disable login without deleting anything).
     */
    public function deactivate(FacultyProfile $facultyProfile)
    {
        $this->authorize('update', $facultyProfile);

        $this->facultyProfileService->deactivateAccount($facultyProfile);

        return redirect()
            ->back()
            ->with('success', $facultyProfile->display_name.'\'s account has been deactivated. They can no longer log in.');
    }

    /**
     * Reactivate a previously deactivated member's account.
     */
    public function reactivate(FacultyProfile $facultyProfile)
    {
        $this->authorize('update', $facultyProfile);

        $this->facultyProfileService->reactivateAccount($facultyProfile);

        return redirect()
            ->back()
            ->with('success', $facultyProfile->display_name.'\'s account has been reactivated.');
    }

    /**
     * Display archived (soft-deleted) faculty profiles.
     */
    public function archived(Request $request)
    {
        $this->authorize('viewAny', FacultyProfile::class);

        $user = auth()->user();
        $filters = $request->only(['search', 'campus_id']);

        // Campus presidents can only see archived members from their campus
        if ($user->isCampusPresident()) {
            $filters['campus_id'] = $user->campus_id;
        }

        $archivedProfiles = $this->facultyProfileService->getArchived($filters);
        $campuses = Campus::active()->get();

        return view('faculty-profiles.archived', compact('archivedProfiles', 'campuses', 'filters'));
    }

    /**
     * Restore an archived faculty profile.
     */
    public function restore(int $id)
    {
        $this->authorize('viewAny', FacultyProfile::class);

        $profile = $this->facultyProfileService->restore($id);

        return redirect()
            ->route('admin.faculty-profiles.archived')
            ->with('success', $profile->display_name.' has been restored successfully.');
    }

    /**
     * Display the current user's profile.
     */
    public function myProfile()
    {
        $user = auth()->user();
        $facultyProfile = $user->facultyProfile;

        if (! $facultyProfile) {
            return view('faculty-profiles.no-profile');
        }

        // Load relationships
        $facultyProfile->load(['user', 'campus', 'academicRank', 'college']);

        return view('faculty-profiles.my-profile-show', compact('facultyProfile'));
    }

    /**
     * Show form to edit current user's profile.
     */
    public function editMyProfile()
    {
        $user = auth()->user();
        $facultyProfile = $user->facultyProfile;

        if (! $facultyProfile) {
            return view('faculty-profiles.no-profile');
        }

        $campuses = Campus::active()->get();
        $academicRanks = AcademicRank::active()->ordered()->get();

        return view('faculty-profiles.my-profile-edit', compact('facultyProfile', 'campuses', 'academicRanks'));
    }

    /**
     * Update current user's profile (personal info only).
     */
    public function updateMyProfile(Request $request)
    {
        $user = auth()->user();
        $facultyProfile = $user->facultyProfile;

        if (! $facultyProfile) {
            return redirect()->route('dashboard')->with('error', 'Profile not found.');
        }

        // Validate personal and professional information fields
        $validated = $request->validate([
            'prefix' => ['nullable', 'string', 'in:Dr.,Prof.,Engr.,Atty.,Mr.,Ms.,Mrs.'],
            'surname' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'extension_name' => ['nullable', 'string', 'max:20'],
            'civil_status' => ['required', 'in:single,married,widowed,separated'],
            'gender' => ['required', 'in:male,female,lgbtqia+'],
            'birth_date' => ['required', 'date', 'before:today'],
            'birth_place' => ['required', 'string', 'max:255'],
            'address_province' => ['nullable', 'string', 'max:100'],
            'address_city' => ['nullable', 'string', 'max:100'],
            'address_barangay' => ['nullable', 'string', 'max:100'],
            'address_street' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'id_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            // Professional information fields
            'employee_id' => ['required', 'string', 'max:50', 'unique:faculty_profiles,employee_id,'.$facultyProfile->id, 'unique:users,employee_id,'.$user->id],
            'date_of_service' => ['nullable', 'date'],
            'academic_rank_id' => ['required', 'exists:academic_ranks,id'],
            'campus_id' => ['required', 'exists:campuses,id'],
            'college_id' => ['required', 'exists:colleges,id'],
        ]);

        // Handle ID picture upload
        if ($request->hasFile('id_picture')) {
            // Delete old picture
            if ($facultyProfile->id_picture) {
                \Storage::disk('public')->delete($facultyProfile->id_picture);
            }
            $validated['id_picture'] = $request->file('id_picture')->store('id-pictures', 'public');
        }

        // Build home_address from structured address fields
        $addressParts = array_filter([
            $validated['address_street'] ?? null,
            $validated['address_barangay'] ?? null,
            $validated['address_city'] ?? null,
            $validated['address_province'] ?? null,
        ]);
        $homeAddress = implode(', ', $addressParts) ?: $facultyProfile->home_address;

        // Update faculty profile personal and professional info
        $facultyProfile->update([
            'prefix' => $validated['prefix'] ?? null,
            'surname' => $validated['surname'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'extension_name' => $validated['extension_name'] ?? null,
            'civil_status' => $validated['civil_status'],
            'gender' => $validated['gender'],
            'birth_date' => $validated['birth_date'],
            'birth_place' => $validated['birth_place'],
            'home_address' => $homeAddress,
            'address_province' => $validated['address_province'] ?? $facultyProfile->address_province,
            'address_city' => $validated['address_city'] ?? $facultyProfile->address_city,
            'address_barangay' => $validated['address_barangay'] ?? $facultyProfile->address_barangay,
            'address_street' => $validated['address_street'] ?? $facultyProfile->address_street,
            'phone_number' => $validated['phone_number'],
            'id_picture' => $validated['id_picture'] ?? $facultyProfile->id_picture,
            // Professional information
            'employee_id' => $validated['employee_id'],
            'date_of_service' => $validated['date_of_service'] ?? $facultyProfile->date_of_service,
            'academic_rank_id' => $validated['academic_rank_id'],
            'campus_id' => $validated['campus_id'],
            'college_id' => $validated['college_id'],
        ]);

        // Update user name and employee_id
        $fullName = $validated['first_name'];
        if (! empty($validated['middle_name'])) {
            $fullName .= ' '.substr($validated['middle_name'], 0, 1).'.';
        }
        $fullName .= ' '.$validated['surname'];
        if (! empty($validated['extension_name'])) {
            $fullName .= ' '.$validated['extension_name'];
        }

        $user->fill([
            'name' => $fullName,
            'employee_id' => $validated['employee_id'],
        ]);

        $user->save();

        return redirect()
            ->route('my-profile')
            ->with('success', 'Your profile has been updated successfully.');
    }

    /**
     * Export faculty profiles to CSV.
     */
    public function export(Request $request)
    {
        $this->authorize('viewAny', FacultyProfile::class);

        $user = auth()->user();
        $filters = $request->only(['search', 'campus_id', 'academic_rank_id', 'membership_status']);

        // Campus presidents can only export members from their campus
        if ($user->isCampusPresident()) {
            $filters['campus_id'] = $user->campus_id;
        }

        $profiles = FacultyProfile::with(['user', 'campus', 'academicRank', 'college'])
            ->when($filters['search'] ?? null, fn ($q, $s) => $q->search($s))
            ->when($filters['campus_id'] ?? null, fn ($q, $c) => $q->forCampus($c))
            ->when($filters['academic_rank_id'] ?? null, fn ($q, $r) => $q->forRank($r))
            ->when($filters['membership_status'] ?? null, fn ($q, $s) => $q->where('membership_status', $s))
            ->orderBy('surname')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="faculty-profiles-'.now()->format('Y-m-d').'.csv"',
        ];

        $callback = function () use ($profiles) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Employee ID', 'Surname', 'First Name', 'Middle Name', 'Extension',
                'Email', 'Phone', 'Campus', 'College', 'Academic Rank',
                'Membership Status', 'Date of Service', 'Birth Date', 'Gender', 'Civil Status',
            ]);

            foreach ($profiles as $profile) {
                fputcsv($file, [
                    $profile->employee_id,
                    $profile->surname,
                    $profile->first_name,
                    $profile->middle_name,
                    $profile->extension_name,
                    $profile->user->email ?? '',
                    $profile->phone_number,
                    $profile->campus->name ?? '',
                    $profile->college->name ?? '',
                    $profile->academicRank->name ?? '',
                    $profile->membership_status,
                    $profile->date_of_service?->format('Y-m-d'),
                    $profile->birth_date?->format('Y-m-d'),
                    $profile->gender,
                    $profile->civil_status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
