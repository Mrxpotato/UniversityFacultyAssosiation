<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeductionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_rank_id',
        'claim_type_id',
        'deduction_type',
        'deduction_value',
        'is_active',
        'notes',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'deduction_value' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the academic rank for this deduction setting
     */
    public function academicRank(): BelongsTo
    {
        return $this->belongsTo(AcademicRank::class);
    }

    /**
     * Get the claim type for this deduction setting
     */
    public function claimType(): BelongsTo
    {
        return $this->belongsTo(ClaimType::class);
    }

    /**
     * Get the user who last updated this setting
     */
    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Calculate the deduction amount for a given gross salary.
     * Deductions are applied to salary only, NOT to claims.
     */
    public function calculateDeduction(float $grossSalary): float
    {
        if (! $this->is_active) {
            return 0;
        }

        if ($this->deduction_type === 'percentage') {
            return round($grossSalary * ($this->deduction_value / 100), 2);
        }

        // Fixed amount — cannot exceed the gross salary
        return min((float) $this->deduction_value, $grossSalary);
    }

    /**
     * Get the display label for this deduction
     */
    public function getDeductionLabelAttribute(): string
    {
        if ($this->deduction_type === 'percentage') {
            return number_format($this->deduction_value, 2).'%';
        }

        return '₱'.number_format($this->deduction_value, 2);
    }

    /**
     * Scope for active settings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the deduction setting for a specific academic rank and claim type.
     * Falls back to the global (null claim_type_id) setting if no type-specific one exists.
     */
    public static function forRankAndType(int $academicRankId, ?int $claimTypeId = null): ?self
    {
        // First try claim-type-specific setting
        if ($claimTypeId) {
            $setting = static::where('academic_rank_id', $academicRankId)
                ->where('claim_type_id', $claimTypeId)
                ->active()
                ->first();

            if ($setting) {
                return $setting;
            }
        }

        // Fall back to global setting (null claim_type_id)
        return static::where('academic_rank_id', $academicRankId)
            ->whereNull('claim_type_id')
            ->active()
            ->first();
    }

    /**
     * @deprecated Use forRankAndType() instead
     */
    public static function forRank(int $academicRankId): ?self
    {
        return static::forRankAndType($academicRankId);
    }
}
