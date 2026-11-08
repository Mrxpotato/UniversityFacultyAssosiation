<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Claim;
use App\Models\User;
use App\Notifications\ClaimStatusNotification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ClaimService
{
    /**
     * Create a new claim
     */
    public function create(array $data): Claim
    {
        return DB::transaction(function () use ($data) {
            // Handle supporting documents upload
            $documents = [];
            if (isset($data['supporting_documents']) && is_array($data['supporting_documents'])) {
                foreach ($data['supporting_documents'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $documents[] = $file->store('claim-documents', 'public');
                    }
                }
            }

            // Claims are paid out at the full amount.
            // Deductions are collected separately from employee salaries (MonthlyDeduction),
            // NOT subtracted from the claim payout.
            $claim = Claim::create([
                'faculty_profile_id' => $data['faculty_profile_id'],
                'claim_type_id' => $data['claim_type_id'],
                'beneficiary_id' => $data['beneficiary_id'] ?? null,
                'amount' => $data['amount'],
                'deduction_amount' => 0,
                'net_amount' => $data['amount'],
                'deduction_type' => null,
                'deduction_value' => null,
                'description' => $data['description'] ?? null,
                'supporting_documents' => $documents,
                'date_applied' => $data['date_applied'],
                'status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            AuditLog::log(
                'claim_created',
                Claim::class,
                $claim->id,
                null,
                $claim->toArray(),
                "Claim {$claim->claim_number} created"
            );

            // Send notification to the faculty member
            $this->notifyClaimOwner($claim, 'created');

            return $claim;
        });
    }

    /**
     * Fields that only officers may set on a claim.
     */
    private const PRIVILEGED_FIELDS = [
        'status', 'date_approved', 'date_billing', 'date_collecting', 'date_check_prepared',
        'date_released', 'approved_by', 'released_by',
        'is_remanded', 'remand_reason', 'rejection_reason',
    ];

    /**
     * Update a claim (strips privileged fields — status changes go through dedicated methods)
     */
    public function update(Claim $claim, array $data): Claim
    {
        return DB::transaction(function () use ($claim, $data) {
            $oldValues = $claim->toArray();

            // Strip privileged fields — status transitions must use approve/release/reject/remand
            $data = array_diff_key($data, array_flip(self::PRIVILEGED_FIELDS));

            // Handle supporting documents upload
            if (isset($data['supporting_documents']) && is_array($data['supporting_documents'])) {
                $documents = $claim->supporting_documents ?? [];
                foreach ($data['supporting_documents'] as $file) {
                    if ($file instanceof UploadedFile) {
                        $documents[] = $file->store('claim-documents', 'public');
                    }
                }
                $data['supporting_documents'] = $documents;
            }

            $claim->update($data);

            AuditLog::log(
                'claim_updated',
                Claim::class,
                $claim->id,
                $oldValues,
                $claim->toArray(),
                "Claim {$claim->claim_number} updated"
            );

            return $claim->fresh();
        });
    }

    /**
     * Start review of a claim (move to under_review status)
     */
    public function startReview(Claim $claim, User $reviewer): Claim
    {
        if (! $claim->canBeReviewed()) {
            throw new \LogicException('Claim cannot be reviewed in its current status.');
        }

        return DB::transaction(function () use ($claim, $reviewer) {
            $oldValues = $claim->toArray();

            $claim->update([
                'status' => 'under_review',
                'reviewed_by' => $reviewer->id,
                'date_reviewed' => now(),
            ]);

            AuditLog::log(
                'claim_under_review',
                Claim::class,
                $claim->id,
                $oldValues,
                $claim->toArray(),
                "Claim {$claim->claim_number} is now under review by {$reviewer->name}"
            );

            // Send notification to the faculty member
            $this->notifyClaimOwner($claim, 'under_review', null, $reviewer->name);

            return $claim->fresh();
        });
    }

    /**
     * Approve a claim
     */
    public function approve(Claim $claim, User $approver): Claim
    {
        if (! $claim->canBeApproved()) {
            throw new \LogicException('Claim cannot be approved in its current status.');
        }

        return DB::transaction(function () use ($claim, $approver) {
            $oldValues = $claim->toArray();

            $claim->update([
                'status' => 'approved',
                'date_approved' => now(),
                'approved_by' => $approver->id,
            ]);

            AuditLog::log(
                'claim_approved',
                Claim::class,
                $claim->id,
                $oldValues,
                $claim->toArray(),
                "Claim {$claim->claim_number} approved by {$approver->name}"
            );

            // Send notification to the faculty member
            $this->notifyClaimOwner($claim, 'approved', null, $approver->name);

            return $claim->fresh();
        });
    }

    /**
     * Release a claim (final step — recipient receives check)
     */
    public function release(Claim $claim, User $releaser): Claim
    {
        if (! $claim->canBeReleased()) {
            throw new \LogicException('Claim cannot be released in its current status.');
        }

        return DB::transaction(function () use ($claim, $releaser) {
            $oldValues = $claim->toArray();

            $claim->update([
                'status' => 'released',
                'date_released' => now(),
                'released_by' => $releaser->id,
            ]);

            AuditLog::log(
                'claim_released',
                Claim::class,
                $claim->id,
                $oldValues,
                $claim->toArray(),
                "Claim {$claim->claim_number} released by {$releaser->name}"
            );

            // Record fund outflow when claim is actually released
            app(FundService::class)->recordClaimReleased($claim, $releaser);

            // Send notification to the faculty member
            $this->notifyClaimOwner($claim, 'released', null, $releaser->name);

            return $claim->fresh();
        });
    }

    /**
     * Move claim to Billing & Collection (Step 4)
     */
    public function prepareBilling(Claim $claim, User $actor): Claim
    {
        if (! $claim->canMoveToBilling()) {
            throw new \LogicException('Claim cannot move to billing in its current status.');
        }

        return DB::transaction(function () use ($claim, $actor) {
            $oldValues = $claim->toArray();

            $claim->update([
                'status' => 'billing',
                'date_billing' => now(),
            ]);

            AuditLog::log(
                'claim_billing',
                Claim::class,
                $claim->id,
                $oldValues,
                $claim->toArray(),
                "Claim {$claim->claim_number} moved to billing by {$actor->name}"
            );

            $this->notifyClaimOwner($claim, 'billing', null, $actor->name);

            // Notify Treasurer(s) that a claim is ready for billing
            $this->notifyTreasurers($claim, 'billing', $actor->name);

            return $claim->fresh();
        });
    }

    /**
     * Move claim to Collection Monitoring (Step 5)
     */
    public function startCollecting(Claim $claim, User $actor): Claim
    {
        if (! $claim->canMoveToCollecting()) {
            throw new \LogicException('Claim cannot move to collecting in its current status.');
        }

        return DB::transaction(function () use ($claim, $actor) {
            $oldValues = $claim->toArray();

            $claim->update([
                'status' => 'collecting',
                'date_collecting' => now(),
            ]);

            AuditLog::log(
                'claim_collecting',
                Claim::class,
                $claim->id,
                $oldValues,
                $claim->toArray(),
                "Claim {$claim->claim_number} moved to collection monitoring by {$actor->name}"
            );

            $this->notifyClaimOwner($claim, 'collecting', null, $actor->name);

            return $claim->fresh();
        });
    }

    /**
     * Move claim to Check Prepared (Step 6)
     */
    public function prepareCheck(Claim $claim, User $actor): Claim
    {
        if (! $claim->canMoveToCheckPrepared()) {
            throw new \LogicException('Claim cannot move to check prepared in its current status.');
        }

        return DB::transaction(function () use ($claim, $actor) {
            $oldValues = $claim->toArray();

            $claim->update([
                'status' => 'check_prepared',
                'date_check_prepared' => now(),
            ]);

            AuditLog::log(
                'claim_check_prepared',
                Claim::class,
                $claim->id,
                $oldValues,
                $claim->toArray(),
                "Claim {$claim->claim_number} check prepared by {$actor->name}"
            );

            $this->notifyClaimOwner($claim, 'check_prepared', null, $actor->name);

            return $claim->fresh();
        });
    }

    /**
     * Reject a claim
     */
    public function reject(Claim $claim, string $reason): Claim
    {
        if (! in_array($claim->status, ['pending', 'under_review'])) {
            throw new \LogicException('Claim cannot be rejected in its current status.');
        }

        return DB::transaction(function () use ($claim, $reason) {
            $oldValues = $claim->toArray();

            $claim->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
            ]);

            AuditLog::log(
                'claim_rejected',
                Claim::class,
                $claim->id,
                $oldValues,
                $claim->toArray(),
                "Claim {$claim->claim_number} rejected"
            );

            // Send notification to the faculty member
            $this->notifyClaimOwner($claim, 'rejected', $reason);

            return $claim->fresh();
        });
    }

    /**
     * Remand a claim
     */
    public function remand(Claim $claim, string $reason): Claim
    {
        if (! in_array($claim->status, ['pending', 'under_review'])) {
            throw new \LogicException('Claim cannot be remanded in its current status.');
        }

        return DB::transaction(function () use ($claim, $reason) {
            $oldValues = $claim->toArray();

            $claim->update([
                'status' => 'remanded',
                'is_remanded' => true,
                'remand_reason' => $reason,
            ]);

            AuditLog::log(
                'claim_remanded',
                Claim::class,
                $claim->id,
                $oldValues,
                $claim->toArray(),
                "Claim {$claim->claim_number} remanded"
            );

            // Send notification to the faculty member
            $this->notifyClaimOwner($claim, 'remanded', $reason);

            return $claim->fresh();
        });
    }

    /**
     * Get paginated claims with filters
     */
    public function getPaginated(array $filters = [], int $perPage = 15)
    {
        $query = Claim::with(['facultyProfile.user', 'claimType', 'beneficiary', 'approvedByUser', 'releasedByUser']);

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('claim_number', 'like', "%{$filters['search']}%")
                    ->orWhereHas('facultyProfile', function ($q) use ($filters) {
                        $q->search($filters['search']);
                    });
            });
        }

        if (! empty($filters['status'])) {
            $query->withStatus($filters['status']);
        }

        if (! empty($filters['claim_type_id'])) {
            $query->where('claim_type_id', $filters['claim_type_id']);
        }

        if (! empty($filters['campus_id'])) {
            $query->forCampus($filters['campus_id']);
        }

        if (isset($filters['faculty_profile_id'])) {
            $query->where('faculty_profile_id', $filters['faculty_profile_id']);
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('date_applied', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('date_applied', '<=', $filters['date_to']);
        }

        $allowedSorts = ['created_at', 'amount', 'date_applied', 'status', 'claim_number'];
        $sortField = in_array($filters['sort'] ?? '', $allowedSorts) ? $filters['sort'] : 'created_at';
        $sortDirection = ($filters['direction'] ?? '') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query->paginate($perPage);
    }

    /**
     * Get statistics
     */
    public function getStatistics(?int $campusId = null): array
    {
        $query = Claim::query();

        if ($campusId) {
            $query->forCampus($campusId);
        }

        $totalAmount = (clone $query)->released()->sum('amount');
        $pendingAmount = (clone $query)->pending()->sum('amount');

        return [
            'total' => $query->count(),
            'pending' => (clone $query)->pending()->count(),
            'approved' => (clone $query)->approved()->count(),
            'released' => (clone $query)->released()->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
            'total_released_amount' => $totalAmount,
            'pending_amount' => $pendingAmount,
        ];
    }

    /**
     * Send notification to the claim owner about status change
     */
    protected function notifyClaimOwner(Claim $claim, string $action, ?string $reason = null, ?string $actorName = null): void
    {
        $claim->load('facultyProfile.user');

        $user = $claim->facultyProfile?->user;

        if ($user) {
            $user->notify(new ClaimStatusNotification($claim, $action, $reason, $actorName));
        }
    }

    /**
     * Notify Treasurer users when a claim enters billing or other relevant stages
     */
    protected function notifyTreasurers(Claim $claim, string $action, ?string $actorName = null): void
    {
        $treasurers = User::where('role', 'university_treasurer')->get();

        foreach ($treasurers as $treasurer) {
            $treasurer->notify(new ClaimStatusNotification($claim, $action.'_treasurer', null, $actorName));
        }
    }
}
