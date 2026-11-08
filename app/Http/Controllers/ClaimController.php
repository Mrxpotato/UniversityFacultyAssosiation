<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClaimRequest;
use App\Http\Requests\UpdateClaimRequest;
use App\Models\Beneficiary;
use App\Models\Claim;
use App\Models\ClaimType;
use App\Models\FacultyProfile;
use App\Services\ClaimService;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    protected ClaimService $claimService;

    public function __construct(ClaimService $claimService)
    {
        $this->claimService = $claimService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $filters = $request->only(['search', 'status', 'claim_type_id', 'campus_id', 'date_from', 'date_to', 'sort', 'direction']);

        if (! $user->isOfficer()) {
            // Faculty without a profile must not see other members' claims (0 matches nothing)
            $filters['faculty_profile_id'] = $user->facultyProfile?->id ?? 0;
        } elseif ($request->has('my_claims') && $user->facultyProfile) {
            // Officer viewing their own claims
            $filters['faculty_profile_id'] = $user->facultyProfile->id;
        } elseif ($user->isCampusPresident() && $user->campus_id) {
            // Campus presidents only see claims from their campus
            $filters['campus_id'] = $user->campus_id;
        }

        $claims = $this->claimService->getPaginated($filters);
        $claimTypes = ClaimType::active()->get();

        return view('claims.index', compact('claims', 'claimTypes', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = auth()->user();

        if ($user->isOfficer()) {
            $query = FacultyProfile::with('user')->active()->orderBy('surname');

            // Campus presidents can only file claims for their campus members
            if ($user->isCampusPresident() && $user->campus_id) {
                $query->where('campus_id', $user->campus_id);
            }

            $facultyProfiles = $query->get();
            $selectedProfileId = $request->faculty_profile_id;
        } else {
            $profile = $user->facultyProfile;
            if (! $profile) {
                return redirect()->route('dashboard')->with('error', 'You need a faculty profile to file a claim.');
            }
            $profile->load(['campus', 'college']);

            // Check profile completeness — required fields must be filled before filing a claim
            $requiredFields = ['birth_date', 'home_address', 'phone_number', 'date_of_service', 'academic_rank_id', 'college_id'];
            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (empty($profile->$field)) {
                    $missingFields[] = str_replace('_', ' ', str_replace('_id', '', $field));
                }
            }
            if (! empty($missingFields)) {
                return redirect()->route('my-profile.edit')
                    ->with('error', 'Please complete the following required fields before filing a claim: '.implode(', ', $missingFields).'.');
            }

            $facultyProfiles = collect([$profile]);
            $selectedProfileId = $profile->id;
        }

        $claimTypes = ClaimType::active()->get();

        // Get beneficiaries for the selected profile
        $beneficiaries = collect();
        if ($selectedProfileId) {
            $beneficiaries = Beneficiary::where('faculty_profile_id', $selectedProfileId)
                ->active()
                ->get();
        }

        return view('claims.create', compact('facultyProfiles', 'selectedProfileId', 'claimTypes', 'beneficiaries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClaimRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        // If not an officer, use their own profile
        if (! $user->isOfficer()) {
            $profile = $user->facultyProfile;
            if (! $profile) {
                return redirect()->route('dashboard')->with('error', 'Profile not found.');
            }
            $data['faculty_profile_id'] = $profile->id;
        } elseif ($user->isCampusPresident() && $user->campus_id) {
            // Campus presidents can only file claims for their own campus members
            $profile = \App\Models\FacultyProfile::findOrFail($data['faculty_profile_id']);
            if ($profile->campus_id !== $user->campus_id) {
                abort(403, 'You can only file claims for members of your campus.');
            }
        }

        $claim = $this->claimService->create($data);

        return redirect()
            ->route('claims.show', $claim)
            ->with('success', 'Claim filed successfully. Your claim number is '.$claim->claim_number);
    }

    /**
     * Display the specified resource.
     */
    public function show(Claim $claim)
    {
        $this->authorize('view', $claim);

        $claim->load([
            'facultyProfile.user',
            'facultyProfile.campus',
            'claimType',
            'beneficiary',
            'approvedByUser',
            'releasedByUser',
        ]);

        return view('claims.show', compact('claim'));
    }

    /**
     * Display the specified resource in admin context (from Claims Monitoring).
     */
    public function adminShow(Claim $claim)
    {
        $this->authorize('view', $claim);

        $claim->load([
            'facultyProfile.user',
            'facultyProfile.campus',
            'facultyProfile.college',
            'facultyProfile.academicRank',
            'claimType',
            'beneficiary',
            'approvedByUser',
            'releasedByUser',
            'reviewedByUser',
        ]);

        return view('claims.admin-show', compact('claim'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Claim $claim)
    {
        $this->authorize('update', $claim);

        if (! $claim->canBeEdited()) {
            return redirect()
                ->route('claims.show', $claim)
                ->with('error', 'This claim cannot be edited.');
        }

        $claimTypes = ClaimType::active()->get();
        $beneficiaries = Beneficiary::where('faculty_profile_id', $claim->faculty_profile_id)
            ->active()
            ->get();

        return view('claims.edit', compact('claim', 'claimTypes', 'beneficiaries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClaimRequest $request, Claim $claim)
    {
        $this->authorize('update', $claim);

        $this->claimService->update($claim, $request->validated());

        return redirect()
            ->route('claims.show', $claim)
            ->with('success', 'Claim updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Claim $claim)
    {
        $this->authorize('delete', $claim);

        $claim->delete();

        return redirect()
            ->route('claims.index')
            ->with('success', 'Claim deleted successfully.');
    }

    /**
     * Start review of a claim.
     */
    public function startReview(Claim $claim)
    {
        $this->authorize('startReview', $claim);

        $this->claimService->startReview($claim, auth()->user());

        return redirect()
            ->route('admin.claims.show', $claim)
            ->with('success', 'Claim is now under review.');
    }

    /**
     * Approve a claim.
     */
    public function approve(Claim $claim)
    {
        $this->authorize('approve', $claim);

        $this->claimService->approve($claim, auth()->user());

        return redirect()
            ->route('admin.claims.show', $claim)
            ->with('success', 'Claim approved successfully.');
    }

    /**
     * Release a claim.
     */
    public function release(Claim $claim)
    {
        $this->authorize('release', $claim);

        $this->claimService->release($claim, auth()->user());

        return redirect()
            ->route('admin.claims.show', $claim)
            ->with('success', 'Claim released successfully.');
    }

    /**
     * Move claim to Billing & Collection (Step 4).
     */
    public function prepareBilling(Claim $claim)
    {
        $this->authorize('prepareBilling', $claim);

        $this->claimService->prepareBilling($claim, auth()->user());

        return redirect()
            ->route('admin.claims.show', $claim)
            ->with('success', 'Claim moved to Billing & Collection.');
    }

    /**
     * Move claim to Collection Monitoring (Step 5).
     */
    public function startCollecting(Claim $claim)
    {
        $this->authorize('startCollecting', $claim);

        $this->claimService->startCollecting($claim, auth()->user());

        return redirect()
            ->route('admin.claims.show', $claim)
            ->with('success', 'Claim moved to Collection Monitoring.');
    }

    /**
     * Move claim to Check Prepared (Step 6).
     */
    public function prepareCheck(Claim $claim)
    {
        $this->authorize('prepareCheck', $claim);

        $this->claimService->prepareCheck($claim, auth()->user());

        return redirect()
            ->route('admin.claims.show', $claim)
            ->with('success', 'Check prepared. Ready for release to recipient.');
    }

    /**
     * Reject a claim.
     */
    public function reject(Request $request, Claim $claim)
    {
        $this->authorize('reject', $claim);

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $this->claimService->reject($claim, $request->rejection_reason);

        return redirect()
            ->route('admin.claims.show', $claim)
            ->with('success', 'Claim rejected.');
    }

    /**
     * Remand a claim.
     */
    public function remand(Request $request, Claim $claim)
    {
        $this->authorize('remand', $claim);

        $request->validate([
            'remand_reason' => 'required|string|max:1000',
        ]);

        $this->claimService->remand($claim, $request->remand_reason);

        return redirect()
            ->route('admin.claims.show', $claim)
            ->with('success', 'Claim remanded for revision.');
    }

    /**
     * Get beneficiaries for a faculty profile (AJAX).
     */
    public function getBeneficiaries(FacultyProfile $facultyProfile)
    {
        $user = auth()->user();

        // Officers can view any profile's beneficiaries; faculty only their own
        if (! $user->isOfficer() && $user->facultyProfile?->id !== $facultyProfile->id) {
            abort(403);
        }

        $beneficiaries = $facultyProfile->beneficiaries()->active()->get();

        return response()->json($beneficiaries);
    }
}
