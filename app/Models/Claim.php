<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Claim extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_number',
        'faculty_profile_id',
        'claim_type_id',
        'beneficiary_id',
        'amount',
        'deduction_amount',
        'net_amount',
        'deduction_type',
        'deduction_value',
        'deduction_status',
        'deduction_collected_at',
        'description',
        'supporting_documents',
        'date_applied',
        'date_reviewed',
        'date_approved',
        'date_billing',
        'date_collecting',
        'date_check_prepared',
        'date_released',
        'status',
        'is_remanded',
        'remand_reason',
        'rejection_reason',
        'reviewed_by',
        'approved_by',
        'released_by',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'deduction_amount' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'deduction_value' => 'decimal:2',
            'supporting_documents' => 'array',
            'date_applied' => 'date',
            'date_reviewed' => 'date',
            'date_approved' => 'date',
            'date_billing' => 'date',
            'date_collecting' => 'date',
            'date_check_prepared' => 'date',
            'date_released' => 'date',
            'is_remanded' => 'boolean',
            'deduction_collected_at' => 'datetime',
        ];
    }

    const STATUSES = [
        'pending' => 'Pending',
        'under_review' => 'Under Review',
        'approved' => 'Approved',
        'billing' => 'Billing & Collection',
        'collecting' => 'Collection Monitoring',
        'check_prepared' => 'Check Prepared',
        'released' => 'Released',
        'rejected' => 'Rejected',
        'remanded' => 'Remanded',
    ];

    const STATUS_COLORS = [
        'pending' => 'yellow',
        'under_review' => 'blue',
        'approved' => 'green',
        'billing' => 'indigo',
        'collecting' => 'cyan',
        'check_prepared' => 'violet',
        'released' => 'purple',
        'rejected' => 'red',
        'remanded' => 'orange',
    ];

    /**
     * @deprecated Deductions are on salary only (MonthlyDeduction), not on claims.
     * Kept for backward compatibility with existing data.
     */
    const DEDUCTION_STATUSES = [
        'pending' => 'Pending',
        'collected' => 'Collected',
    ];

    /**
     * @deprecated Deductions are on salary only (MonthlyDeduction), not on claims.
     */
    const DEDUCTION_STATUS_COLORS = [
        'pending' => 'yellow',
        'collected' => 'green',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->claim_number)) {
                $model->claim_number = 'CLM-'.strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Get the faculty profile
     */
    public function facultyProfile(): BelongsTo
    {
        return $this->belongsTo(FacultyProfile::class);
    }

    /**
     * Get the claim type
     */
    public function claimType(): BelongsTo
    {
        return $this->belongsTo(ClaimType::class);
    }

    /**
     * Get the beneficiary
     */
    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    /**
     * Get the user who approved the claim
     */
    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user who reviewed the claim
     */
    public function reviewedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the user who released the claim
     */
    public function releasedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Get status color
     */
    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    /**
     * Check if claim can be edited
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, ['pending', 'remanded']);
    }

    /**
     * Check if claim can be reviewed (moved to under_review status)
     */
    public function canBeReviewed(): bool
    {
        return in_array($this->status, ['pending', 'remanded']);
    }

    /**
     * Check if claim can be approved
     */
    public function canBeApproved(): bool
    {
        return in_array($this->status, ['pending', 'under_review', 'remanded']);
    }

    /**
     * Check if claim can move to billing
     */
    public function canMoveToBilling(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if claim can move to collecting
     */
    public function canMoveToCollecting(): bool
    {
        return $this->status === 'billing';
    }

    /**
     * Check if claim can move to check_prepared
     */
    public function canMoveToCheckPrepared(): bool
    {
        return $this->status === 'collecting';
    }

    /**
     * Check if claim can be released
     */
    public function canBeReleased(): bool
    {
        return $this->status === 'check_prepared';
    }

    /**
     * Scope for pending claims
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved claims
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for released claims
     */
    public function scopeReleased($query)
    {
        return $query->where('status', 'released');
    }

    /**
     * Scope for a specific status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for a specific campus
     */
    public function scopeForCampus($query, $campusId)
    {
        return $query->whereHas('facultyProfile', function ($q) use ($campusId) {
            $q->where('campus_id', $campusId);
        });
    }

    /**
     * Scope for claims with deductions
     */
    public function scopeHasDeduction($query)
    {
        return $query->where('deduction_amount', '>', 0);
    }

    /**
     * Scope for pending deduction collection
     */
    public function scopeDeductionPending($query)
    {
        return $query->where('deduction_status', 'pending');
    }

    /**
     * Scope for collected deductions
     */
    public function scopeDeductionCollected($query)
    {
        return $query->where('deduction_status', 'collected');
    }

    /**
     * Mark deduction as collected
     */
    public function markDeductionCollected(): void
    {
        $this->update([
            'deduction_status' => 'collected',
            'deduction_collected_at' => now(),
        ]);
    }
}
