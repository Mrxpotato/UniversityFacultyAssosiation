<?php

namespace App\Policies;

use App\Models\TrainingSeminar;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TrainingSeminarPolicy
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
    public function view(?User $user, TrainingSeminar $training): bool
    {
        // Published trainings can be viewed by anyone
        if ($training->is_published) {
            return true;
        }

        // Must be logged in to view unpublished
        if (! $user) {
            return false;
        }

        // University officers can view any training
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Authors can view their own
        return $user->id === $training->created_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isUniversityOfficer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TrainingSeminar $training): bool
    {
        return $user->isUniversityOfficer();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TrainingSeminar $training): bool
    {
        return $user->isUniversityOfficer();
    }

    /**
     * Determine whether the user can register for the training.
     */
    public function register(User $user, TrainingSeminar $training): bool
    {
        // Must have a faculty profile
        if (! $user->facultyProfile) {
            return false;
        }

        // Must be open for registration
        return $training->isRegistrationOpen();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TrainingSeminar $training): bool
    {
        return $user->isUniversityOfficer();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TrainingSeminar $training): bool
    {
        return $user->isUniversityOfficer();
    }
}
