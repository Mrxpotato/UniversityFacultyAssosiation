<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_profile_id',
        'deduction_setting_id',
        'claim_type_id',
        'month',
        'gross_salary',
        'deduction_type',
        'deduction_value',
        'deduction_amount',
        'description',
        'status',
        'collected_at',
        'collected_by',
        'generated_by',
    ];

    protected function casts(): array
    {
        return [
            'gross_salary' => 'decimal:2',
            'deduction_value' => 'decimal:2',
            'deduction_amount' => 'decimal:2',
            'collected_at' => 'datetime',
        ];
    }

    public function facultyProfile(): BelongsTo
    {
        return $this->belongsTo(FacultyProfile::class);
    }

    public function deductionSetting(): BelongsTo
    {
        return $this->belongsTo(DeductionSetting::class);
    }

    public function claimType(): BelongsTo
    {
        return $this->belongsTo(ClaimType::class);
    }

    public function collectedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function generatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCollected($query)
    {
        return $query->where('status', 'collected');
    }

    public function scopeForMonth($query, string $month)
    {
        return $query->where('month', $month);
    }

    public function scopeForCampus($query, $campusId)
    {
        return $query->whereHas('facultyProfile', function ($q) use ($campusId) {
            $q->where('campus_id', $campusId);
        });
    }

    public function markCollected(int $userId): void
    {
        $this->update([
            'status' => 'collected',
            'collected_at' => now(),
            'collected_by' => $userId,
        ]);
    }
}
