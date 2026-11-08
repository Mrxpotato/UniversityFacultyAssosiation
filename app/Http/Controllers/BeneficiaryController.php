<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBeneficiaryRequest;
use App\Http\Requests\UpdateBeneficiaryRequest;
use App\Models\Beneficiary;
use App\Models\FacultyProfile;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->isOfficer()) {
            $query = Beneficiary::with(['facultyProfile.user', 'facultyProfile.campus'])
                ->when($request->search, function ($query, $search) {
                    $query->where('full_name', 'like', "%{$search}%")
                        ->orWhereHas('facultyProfile', fn ($q) => $q->search($search));
                })
                ->when($request->relationship, fn ($q, $r) => $q->where('relationship', $r));

            // Campus presidents only see their campus
            if ($user->isCampusPresident() && $user->campus_id) {
                $query->whereHas('facultyProfile', fn ($q) => $q->where('campus_id', $user->campus_id));
            }

            $beneficiaries = $query->latest()->paginate(15);
        } else {
            $profile = $user->facultyProfile;
            if (! $profile) {
                return redirect()->route('dashboard')->with('error', 'Profile not found.');
            }

            $beneficiaries = $profile->beneficiaries()
                ->when($request->search, fn ($q, $s) => $q->where('full_name', 'like', "%{$s}%"))
                ->latest()
                ->paginate(15);
        }

        return view('beneficiaries.index', compact('beneficiaries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        if ($user->isOfficer()) {
            $query = FacultyProfile::with('user')->active()->orderBy('surname');
            if ($user->isCampusPresident() && $user->campus_id) {
                $query->where('campus_id', $user->campus_id);
            }
            $facultyProfiles = $query->get();
            $selectedProfileId = $request->faculty_profile_id;
        } else {
            $facultyProfiles = collect([$user->facultyProfile]);
            $selectedProfileId = $user->facultyProfile?->id;
        }

        $relationships = Beneficiary::RELATIONSHIPS;

        return view('beneficiaries.create', compact('facultyProfiles', 'selectedProfileId', 'relationships'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBeneficiaryRequest $request)
    {
        $user = auth()->user();
        $profileId = $request->faculty_profile_id;

        // If not an officer, ensure they can only add to their own profile
        if (! $user->isOfficer()) {
            $profileId = $user->facultyProfile?->id;
            if (! $profileId) {
                return redirect()->route('dashboard')->with('error', 'Profile not found.');
            }
        }

        // Check authorization
        $profile = FacultyProfile::findOrFail($profileId);

        // Campus presidents may only manage beneficiaries for members of their own campus
        if ($user->isCampusPresident() && $user->campus_id && (int) $profile->campus_id !== (int) $user->campus_id) {
            abort(403, 'You can only add beneficiaries for members of your campus.');
        }

        $beneficiary = new Beneficiary;
        $beneficiary->faculty_profile_id = $profile->id;
        $this->authorize('create', $beneficiary);

        $data = $request->validated();
        $data['faculty_profile_id'] = $profileId;

        // If marking as primary, unmark others
        if ($data['is_primary'] ?? false) {
            Beneficiary::where('faculty_profile_id', $profileId)
                ->update(['is_primary' => false]);
        }

        Beneficiary::create($data);

        return redirect()
            ->route('beneficiaries.index')
            ->with('success', 'Beneficiary added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Beneficiary $beneficiary)
    {
        $this->authorize('view', $beneficiary);

        $beneficiary->load(['facultyProfile.user', 'claims']);

        return view('beneficiaries.show', compact('beneficiary'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Beneficiary $beneficiary)
    {
        $this->authorize('update', $beneficiary);

        $relationships = Beneficiary::RELATIONSHIPS;

        return view('beneficiaries.edit', compact('beneficiary', 'relationships'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBeneficiaryRequest $request, Beneficiary $beneficiary)
    {
        $this->authorize('update', $beneficiary);

        $data = $request->validated();

        // If marking as primary, unmark others
        if ($data['is_primary'] ?? false) {
            Beneficiary::where('faculty_profile_id', $beneficiary->faculty_profile_id)
                ->where('id', '!=', $beneficiary->id)
                ->update(['is_primary' => false]);
        }

        $beneficiary->update($data);

        return redirect()
            ->route('beneficiaries.index')
            ->with('success', 'Beneficiary updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Beneficiary $beneficiary)
    {
        $this->authorize('delete', $beneficiary);

        $beneficiary->delete();

        return redirect()
            ->route('beneficiaries.index')
            ->with('success', 'Beneficiary removed successfully.');
    }
}
