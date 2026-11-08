<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'psgc_code',
        'region_code',
        'province_code',
        'city_municipality_code',
        'barangay_code',
        'region',
        'province',
        'city_municipality',
        'barangay',
        'latitude',
        'longitude',
        'address',
        'contact_number',
        'email',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    /**
     * Get full PSGC location string
     */
    public function getPsgcLocationAttribute(): string
    {
        $parts = array_filter([
            $this->barangay,
            $this->city_municipality,
            $this->province,
            $this->region,
        ]);

        return implode(', ', $parts) ?: 'Not set';
    }

    /**
     * Check if campus has GPS coordinates
     */
    public function hasCoordinates(): bool
    {
        return $this->latitude !== null && $this->longitude !== null;
    }

    /**
     * Get users associated with the campus
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get colleges associated with the campus
     */
    public function colleges(): HasMany
    {
        return $this->hasMany(College::class);
    }

    /**
     * Get faculty profiles associated with the campus
     */
    public function facultyProfiles(): HasMany
    {
        return $this->hasMany(FacultyProfile::class);
    }

    /**
     * Get transparency posts associated with the campus
     */
    public function transparencyPosts(): HasMany
    {
        return $this->hasMany(TransparencyPost::class);
    }

    /**
     * Get trainings associated with the campus
     */
    public function trainingSeminars(): HasMany
    {
        return $this->hasMany(TrainingSeminar::class);
    }

    /**
     * Get active campuses
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get total members count
     */
    public function getMembersCountAttribute(): int
    {
        return $this->facultyProfiles()->count();
    }

    /**
     * Get active members count
     */
    public function getActiveMembersCountAttribute(): int
    {
        return $this->facultyProfiles()->where('membership_status', 'active')->count();
    }
}
