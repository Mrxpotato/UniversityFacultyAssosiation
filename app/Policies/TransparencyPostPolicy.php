<?php

namespace App\Policies;

use App\Models\TransparencyPost;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransparencyPostPolicy
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
    public function view(?User $user, TransparencyPost $post): bool
    {
        // Published public posts can be viewed by anyone
        if ($post->is_published && $post->visibility === 'public') {
            return true;
        }

        // Must be logged in to view members-only or unpublished posts
        if (! $user) {
            return false;
        }

        // University officers can view any post
        if ($user->isUniversityOfficer()) {
            return true;
        }

        // Members can view published members-only posts
        if ($post->is_published && $post->visibility === 'members_only') {
            return true;
        }

        // Authors can view their own posts
        return $user->id === $post->created_by;
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
    public function update(User $user, TransparencyPost $post): bool
    {
        return $user->isUniversityOfficer();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TransparencyPost $post): bool
    {
        return $user->isUniversityOfficer();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TransparencyPost $post): bool
    {
        return $user->isUniversityOfficer();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TransparencyPost $post): bool
    {
        return $user->isUniversityOfficer();
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, TransparencyPost $post): bool
    {
        return $user->isUniversityOfficer();
    }
}
