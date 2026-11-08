<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Beneficiary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'faculty_profile_id',
        'full_name',
        'relationship',
        'date_of_birth',
        'pse',
        'contact_number',
        'address',
        'is_primary',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    const RELATIONSHIPS = [
        'mother' => 'Mother',
        'father' => 'Father',
        'mother-in-law' => 'Mother-in-law',
        'father-in-law' => 'Father-in-law',
        'brother' => 'Brother',
        'sister' => 'Sister',
        'wife' => 'Wife',
        'husband' => 'Husband',
        'son' => 'Son',
        'daughter' => 'Daughter',
        'other' => 'Other',
    ];

    /**
     * Get the faculty profile that owns the beneficiary
     */
    public function facultyProfile(): BelongsTo
    {
        return $this->belongsTo(FacultyProfile::class);
    }

    /**
     * Get claims for this beneficiary
     */
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Get age
     */
    public function getAgeAttribute(): ?int
    {
        if (! $this->date_of_birth) {
            return null;
        }

        return $this->date_of_birth->age;
    }

    /**
     * Get relationship label
     */
    public function getRelationshipLabelAttribute(): string
    {
        return self::RELATIONSHIPS[$this->relationship] ?? ucfirst($this->relationship);
    }

    /**
     * Scope for active beneficiaries
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for primary beneficiaries
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
