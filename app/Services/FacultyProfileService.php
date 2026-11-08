<?php

namespace App\Services;

use App\Models\FacultyProfile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class FacultyProfileService
{
    /**
     * Create a new faculty profile with user account
     */
    public function create(array $data): FacultyProfile
    {
        return DB::transaction(function () use ($data) {
            // Build full name with prefix
            $fullName = ($data['prefix'] ?? '').
                       ($data['prefix'] ? ' ' : '').
                       $data['first_name'].' '.
                       ($data['middle_name'] ? substr($data['middle_name'], 0, 1).'. ' : '').
                       $data['surname'].
                       ($data['extension_name'] ? ' '.$data['extension_name'] : '');

            // Create user account
            $user = User::create([
                'name' => $fullName,
                'employee_id' => $data['employee_id'],
                'email' => $data['email'] ?? null,
                'password' => Hash::make($data['password']),
                'role' => 'faculty',
                'campus_id' => $data['campus_id'],
                'is_active' => true,
            ]);

            // Handle ID picture upload
            $idPicturePath = null;
            if (isset($data['id_picture']) && $data['id_picture'] instanceof UploadedFile) {
                $idPicturePath = $data['id_picture']->store('id-pictures', 'public');
            }

            // Create faculty profile
            $profile = FacultyProfile::create([
                'user_id' => $user->id,
                'prefix' => $data['prefix'] ?? null,
                'surname' => $data['surname'],
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'extension_name' => $data['extension_name'] ?? null,
                'civil_status' => $data['civil_status'],
                'gender' => $data['gender'],
                'birth_date' => $data['birth_date'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'home_address' => $data['home_address'] ?? null,
                'address_province' => $data['address_province'] ?? null,
                'address_city' => $data['address_city'] ?? null,
                'address_barangay' => $data['address_barangay'] ?? null,
                'address_street' => $data['address_street'] ?? null,
                'id_picture' => $idPicturePath,
                'phone_number' => $data['phone_number'] ?? null,
                'employee_id' => $data['employee_id'],
                'date_of_service' => $data['date_of_service'] ?? null,
                'academic_rank_id' => $data['academic_rank_id'] ?? null,
                'college_id' => $data['college_id'] ?? null,
                'campus_id' => $data['campus_id'],
                'membership_status' => $data['membership_status'] ?? 'active',
                'membership_date' => $data['membership_date'] ?? now(),
                'notes' => $data['notes'] ?? null,
            ]);

            return $profile;
        });
    }

    /**
     * Update a faculty profile
     */
    public function update(FacultyProfile $profile, array $data): FacultyProfile
    {
        return DB::transaction(function () use ($profile, $data) {
            // Handle ID picture upload
            if (isset($data['id_picture']) && $data['id_picture'] instanceof UploadedFile) {
                // Delete old picture
                if ($profile->id_picture) {
                    Storage::disk('public')->delete($profile->id_picture);
                }
                $data['id_picture'] = $data['id_picture']->store('id-pictures', 'public');
            } else {
                unset($data['id_picture']);
            }

            // Update user account if email changed
            if (isset($data['email']) && $data['email'] !== $profile->user->email) {
                $profile->user->update(['email' => $data['email']]);
            }

            // Update password if provided
            if (! empty($data['password'])) {
                $profile->user->update(['password' => Hash::make($data['password'])]);
            }

            // Update user name with prefix
            $fullName = ($data['prefix'] ?? $profile->prefix ?? '').
                       (($data['prefix'] ?? $profile->prefix) ? ' ' : '').
                       $data['first_name'].' '.
                       (($data['middle_name'] ?? $profile->middle_name) ? substr($data['middle_name'] ?? $profile->middle_name, 0, 1).'. ' : '').
                       $data['surname'].
                       (($data['extension_name'] ?? $profile->extension_name) ? ' '.($data['extension_name'] ?? $profile->extension_name) : '');

            $profile->user->update([
                'name' => $fullName,
                'employee_id' => $data['employee_id'] ?? $profile->employee_id,
                'campus_id' => $data['campus_id'] ?? $profile->campus_id,
            ]);

            // Remove user-specific fields
            unset($data['email'], $data['password'], $data['password_confirmation']);

            // Build home_address from structured address fields
            if (isset($data['address_street']) || isset($data['address_barangay']) || isset($data['address_city']) || isset($data['address_province'])) {
                $addressParts = array_filter([
                    $data['address_street'] ?? null,
                    $data['address_barangay'] ?? null,
                    $data['address_city'] ?? null,
                    $data['address_province'] ?? null,
                ]);
                $data['home_address'] = implode(', ', $addressParts) ?: $profile->home_address;
            }

            // Update profile
            $profile->update($data);

            return $profile->fresh();
        });
    }

    /**
     * Delete a faculty profile
     */
    public function delete(FacultyProfile $profile): bool
    {
        return DB::transaction(function () use ($profile) {
            // Delete ID picture
            if ($profile->id_picture) {
                Storage::disk('public')->delete($profile->id_picture);
            }

            // Soft delete profile
            $profile->delete();

            // Deactivate user account
            $profile->user->update(['is_active' => false]);

            return true;
        });
    }

    /**
     * Archive a faculty profile (set membership to retired and soft-delete)
     */
    public function archive(FacultyProfile $profile): bool
    {
        return DB::transaction(function () use ($profile) {
            $profile->update(['membership_status' => 'retired']);
            $profile->delete(); // soft delete
            $profile->user->update(['is_active' => false]);

            return true;
        });
    }

    /**
     * Deactivate a member's account (login disabled, profile data preserved)
     */
    public function deactivateAccount(FacultyProfile $profile): bool
    {
        return DB::transaction(function () use ($profile) {
            $profile->user->update(['is_active' => false]);

            return true;
        });
    }

    /**
     * Reactivate a previously deactivated member's account
     */
    public function reactivateAccount(FacultyProfile $profile): bool
    {
        return DB::transaction(function () use ($profile) {
            $profile->user->update(['is_active' => true]);

            return true;
        });
    }

    /**
     * Restore an archived faculty profile
     */
    public function restore(int $profileId): FacultyProfile
    {
        $profile = FacultyProfile::withTrashed()->findOrFail($profileId);

        return DB::transaction(function () use ($profile) {
            $profile->restore();
            $profile->update(['membership_status' => 'active']);
            $profile->user->update(['is_active' => true]);

            return $profile->fresh();
        });
    }

    /**
     * Get archived (soft-deleted) faculty profiles
     */
    public function getArchived(array $filters = [], int $perPage = 15)
    {
        $query = FacultyProfile::onlyTrashed()->with(['user', 'campus', 'academicRank', 'college']);

        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (! empty($filters['campus_id'])) {
            $query->forCampus($filters['campus_id']);
        }

        return $query->orderBy('deleted_at', 'desc')->paginate($perPage);
    }

    /**
     * Get paginated faculty profiles with filters
     */
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = FacultyProfile::with(['user', 'campus', 'academicRank', 'college']);

        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (! empty($filters['campus_id'])) {
            $query->forCampus($filters['campus_id']);
        }

        if (! empty($filters['academic_rank_id'])) {
            $query->forRank($filters['academic_rank_id']);
        }

        if (! empty($filters['membership_status'])) {
            $query->where('membership_status', $filters['membership_status']);
        }

        $allowedSorts = ['surname', 'first_name', 'employee_id', 'membership_status', 'created_at', 'membership_date'];
        $sortField = in_array($filters['sort'] ?? '', $allowedSorts) ? $filters['sort'] : 'surname';
        $sortDirection = ($filters['direction'] ?? '') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get statistics
     */
    public function getStatistics(?int $campusId = null): array
    {
        $query = FacultyProfile::query();

        if ($campusId) {
            $query->forCampus($campusId);
        }

        return [
            'total' => $query->count(),
            'active' => (clone $query)->where('membership_status', 'active')->count(),
            'inactive' => (clone $query)->where('membership_status', 'inactive')->count(),
            'retired' => (clone $query)->where('membership_status', 'retired')->count(),
            'deceased' => (clone $query)->where('membership_status', 'deceased')->count(),
        ];
    }
}
