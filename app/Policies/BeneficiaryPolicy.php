<?php

namespace App\Policies;

use App\Models\Beneficiary;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BeneficiaryPolicy
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
    public function view(User $user, Beneficiary $beneficiary): bool
    {
        // University officers can view any beneficiary
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Campus presidents can view beneficiaries in their campus
        if ($user->isCampusPresident() && $user->campus_id) {
            return $beneficiary->facultyProfile && (int) $beneficiary->facultyProfile->campus_id === (int) $user->campus_id;
        }

        // Faculty can view their own beneficiaries
        return $user->facultyProfile && (int) $user->facultyProfile->id === (int) $beneficiary->faculty_profile_id;
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
    public function update(User $user, Beneficiary $beneficiary): bool
    {
        // University officers can update any beneficiary
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Campus presidents can update beneficiaries in their campus
        if ($user->isCampusPresident() && $user->campus_id) {
            return $beneficiary->facultyProfile && (int) $beneficiary->facultyProfile->campus_id === (int) $user->campus_id;
        }

        // Faculty can update their own beneficiaries
        return $user->facultyProfile && (int) $user->facultyProfile->id === (int) $beneficiary->faculty_profile_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Beneficiary $beneficiary): bool
    {
        // University officers can delete any beneficiary
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Campus presidents can delete beneficiaries in their campus
        if ($user->isCampusPresident() && $user->campus_id) {
            return $beneficiary->facultyProfile && (int) $beneficiary->facultyProfile->campus_id === (int) $user->campus_id;
        }

        // Faculty can delete their own beneficiaries
        return $user->facultyProfile && (int) $user->facultyProfile->id === (int) $beneficiary->faculty_profile_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Beneficiary $beneficiary): bool
    {
        return $user->isUniversityOfficer();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Beneficiary $beneficiary): bool
    {
        return $user->isUniversityOfficer();
    }
}
