<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicRank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'level',
        'salary_grade',
        'monthly_dues',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'monthly_dues' => 'decimal:2',
            'is_active' => 'boolean',
            'salary_grade' => 'integer',
        ];
    }

    /**
     * Get faculty profiles with this academic rank
     */
    public function facultyProfiles(): HasMany
    {
        return $this->hasMany(FacultyProfile::class);
    }

    /**
     * Get all deduction settings for this rank (one per claim type)
     */
    public function deductionSettings(): HasMany
    {
        return $this->hasMany(DeductionSetting::class);
    }

    /**
     * Get active ranks
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get ranks ordered by level
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('level', 'asc');
    }
}
