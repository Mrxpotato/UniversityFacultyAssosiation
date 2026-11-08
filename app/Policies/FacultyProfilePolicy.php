<?php

namespace App\Policies;

use App\Models\FacultyProfile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FacultyProfilePolicy
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
    public function view(User $user, FacultyProfile $facultyProfile): bool
    {
        // University officers can view any profile
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Campus presidents can view profiles from their campus
        if ($user->isCampusPresident() && $user->campus_id === $facultyProfile->campus_id) {
            return true;
        }

        // Faculty can view their own profile
        return $user->id === $facultyProfile->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // University officers and campus presidents can create members
        return $user->isUniversityOfficer() || $user->isCampusPresident();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FacultyProfile $facultyProfile): bool
    {
        // University officers can update any profile
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Campus presidents can update profiles from their campus
        if ($user->isCampusPresident() && $user->campus_id === $facultyProfile->campus_id) {
            return true;
        }

        // Faculty can update their own profile
        return $user->id === $facultyProfile->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FacultyProfile $facultyProfile): bool
    {
        // University officers can delete any profile
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Campus presidents can delete profiles from their campus
        return $user->isCampusPresident() && $user->campus_id === $facultyProfile->campus_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FacultyProfile $facultyProfile): bool
    {
        // University officers can restore any profile
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Campus presidents can restore profiles from their campus
        return $user->isCampusPresident() && $user->campus_id === $facultyProfile->campus_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FacultyProfile $facultyProfile): bool
    {
        // University officers can force delete any profile
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Campus presidents can force delete profiles from their campus
        return $user->isCampusPresident() && $user->campus_id === $facultyProfile->campus_id;
    }
}
