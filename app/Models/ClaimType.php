<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClaimType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'required_documents',
        'max_amount',
        'requires_beneficiary',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'required_documents' => 'array',
            'max_amount' => 'decimal:2',
            'requires_beneficiary' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get claims with this type
     */
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Get deduction settings for this claim type
     */
    public function deductionSettings(): HasMany
    {
        return $this->hasMany(DeductionSetting::class);
    }

    /**
     * Get active claim types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
