<?php

namespace App\Services;

use App\Models\Due;
use App\Models\FacultyProfile;
use Carbon\Carbon;

class DuesService
{
    /**
     * Generate dues for a specific month (batch optimized)
     */
    public function generateMonthlyDues(string $month): int
    {
        // Get IDs of members who already have dues for this month
        $existingProfileIds = Due::where('month', $month)
            ->pluck('faculty_profile_id')
            ->toArray();

        // Get active members who don't yet have dues
        $activeMembers = FacultyProfile::with('academicRank')
            ->where('membership_status', 'active')
            ->whereNotIn('id', $existingProfileIds)
            ->get();

        if ($activeMembers->isEmpty()) {
            return 0;
        }

        $dueDate = Carbon::parse($month)->endOfMonth();
        $now = now();

        // Batch insert all new dues at once
        $newDues = $activeMembers->map(function ($member) use ($month, $dueDate, $now) {
            return [
                'faculty_profile_id' => $member->id,
                'amount' => $member->academicRank ? $member->academicRank->monthly_dues : 100,
                'month' => $month,
                'due_date' => $dueDate,
                'status' => 'pending',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();

        // Insert in chunks of 500 to avoid memory issues
        foreach (array_chunk($newDues, 500) as $chunk) {
            Due::insert($chunk);
        }

        return count($newDues);
    }

    /**
     * Mark due as paid
     */
    public function markAsPaid(Due $due, ?string $reference = null): Due
    {
        $due->markAsPaid($reference);

        return $due->fresh();
    }

    /**
     * Update overdue status for all dues
     */
    public function updateOverdueStatuses(): int
    {
        return Due::where('status', 'pending')
            ->whereDate('due_date', '<', now())
            ->update(['status' => 'overdue']);
    }

    /**
     * Get paginated dues with filters
     */
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = Due::with(['facultyProfile.user', 'facultyProfile.campus']);

        if (! empty($filters['search'])) {
            $query->whereHas('facultyProfile', function ($q) use ($filters) {
                $q->search($filters['search']);
            });
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['month'])) {
            $query->forMonth($filters['month']);
        }

        if (! empty($filters['campus_id'])) {
            $query->forCampus($filters['campus_id']);
        }

        if (! empty($filters['faculty_profile_id'])) {
            $query->where('faculty_profile_id', $filters['faculty_profile_id']);
        }

        $allowedSorts = ['month', 'amount', 'status', 'due_date', 'paid_date', 'created_at'];
        $sortField = in_array($filters['sort'] ?? '', $allowedSorts) ? $filters['sort'] : 'month';
        $sortDirection = ($filters['direction'] ?? '') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get statistics
     */
    public function getStatistics(?int $campusId = null, ?string $month = null): array
    {
        $query = Due::query();

        if ($campusId) {
            $query->forCampus($campusId);
        }

        if ($month) {
            $query->forMonth($month);
        }

        $totalCollected = (clone $query)->paid()->sum('amount');
        $totalPending = (clone $query)->pending()->sum('amount');
        $totalOverdue = (clone $query)->overdue()->sum('amount');

        return [
            'total_dues' => $query->count(),
            'paid_count' => (clone $query)->paid()->count(),
            'pending_count' => (clone $query)->pending()->count(),
            'overdue_count' => (clone $query)->overdue()->count(),
            'total_collected' => $totalCollected,
            'total_pending' => $totalPending,
            'total_overdue' => $totalOverdue,
        ];
    }

    /**
     * Get member's dues summary using aggregate queries (avoids loading all dues into memory)
     */
    public function getMemberDuesSummary(FacultyProfile $member): array
    {
        $stats = $member->dues()
            ->selectRaw("
                COUNT(*) as total_dues,
                SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) as overdue,
                COALESCE(SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END), 0) as total_paid,
                COALESCE(SUM(CASE WHEN status IN ('pending', 'overdue') THEN amount ELSE 0 END), 0) as total_outstanding
            ")
            ->first();

        return [
            'total_dues' => (int) ($stats->total_dues ?? 0),
            'paid' => (int) ($stats->paid ?? 0),
            'pending' => (int) ($stats->pending ?? 0),
            'overdue' => (int) ($stats->overdue ?? 0),
            'total_paid' => (float) ($stats->total_paid ?? 0),
            'total_outstanding' => (float) ($stats->total_outstanding ?? 0),
        ];
    }
}
