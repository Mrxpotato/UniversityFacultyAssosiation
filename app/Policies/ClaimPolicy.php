<?php

namespace App\Policies;

use App\Models\Claim;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClaimPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Claim $claim): bool
    {
        // University-level officers can view any claim
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Campus presidents can view claims from their campus
        if ($user->isCampusPresident() && $user->campus_id) {
            return $claim->facultyProfile && $claim->facultyProfile->campus_id === $user->campus_id;
        }

        // Faculty can view their own claims
        return $user->facultyProfile && $user->facultyProfile->id === $claim->faculty_profile_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Claim $claim): bool
    {
        // University-level officers can update any claim
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Faculty (including campus presidents viewing as faculty) can update their own claims only if pending or remanded
        if ($user->facultyProfile && $user->facultyProfile->id === $claim->faculty_profile_id) {
            return $claim->canBeEdited();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Claim $claim): bool
    {
        // Super admin can delete any claim
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Faculty can only delete pending claims
        if ($user->facultyProfile && $user->facultyProfile->id === $claim->faculty_profile_id) {
            return $claim->status === 'pending';
        }

        return false;
    }

    /**
     * Determine whether the user can start reviewing the claim.
     * Admin & President can start review.
     */
    public function startReview(User $user, Claim $claim): bool
    {
        return $user->canManageClaims() && $claim->canBeReviewed();
    }

    /**
     * Determine whether the user can approve the claim.
     * Admin & President can approve claims.
     */
    public function approve(User $user, Claim $claim): bool
    {
        return $user->canManageClaims() && $claim->canBeApproved();
    }

    /**
     * Determine whether the user can release the claim.
     * Admin & Treasurer can release claims.
     */
    public function release(User $user, Claim $claim): bool
    {
        return $user->canReleaseClaims() && $claim->canBeReleased();
    }

    /**
     * Determine whether the user can move the claim to billing.
     * Admin & Treasurer can manage billing workflow.
     */
    public function prepareBilling(User $user, Claim $claim): bool
    {
        return $user->canReleaseClaims() && $claim->canMoveToBilling();
    }

    /**
     * Determine whether the user can move the claim to collection monitoring.
     * Admin & Treasurer can manage collection workflow.
     */
    public function startCollecting(User $user, Claim $claim): bool
    {
        return $user->canReleaseClaims() && $claim->canMoveToCollecting();
    }

    /**
     * Determine whether the user can move the claim to check prepared.
     * Admin & Treasurer can prepare checks.
     */
    public function prepareCheck(User $user, Claim $claim): bool
    {
        return $user->canReleaseClaims() && $claim->canMoveToCheckPrepared();
    }

    /**
     * Determine whether the user can remand the claim.
     * Admin & President can remand claims.
     */
    public function remand(User $user, Claim $claim): bool
    {
        return $user->canManageClaims() && in_array($claim->status, ['pending', 'under_review']);
    }

    /**
     * Determine whether the user can reject the claim.
     * Admin & President can reject claims.
     */
    public function reject(User $user, Claim $claim): bool
    {
        return $user->canManageClaims() && in_array($claim->status, ['pending', 'under_review']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Claim $claim): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Claim $claim): bool
    {
        return $user->isSuperAdmin();
    }
}
