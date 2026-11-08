<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacultyProfile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'prefix',
        'surname',
        'first_name',
        'middle_name',
        'extension_name',
        'civil_status',
        'gender',
        'birth_date',
        'birth_place',
        'home_address',
        'address_province',
        'address_city',
        'address_barangay',
        'address_street',
        'id_picture',
        'phone_number',
        'employee_id',
        'date_of_service',
        'academic_rank_id',
        'college_id',
        'campus_id',
        'membership_status',
        'membership_date',
        'officer_position',
        'campus_position',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'date_of_service' => 'date',
            'membership_date' => 'date',
        ];
    }

    /**
     * Get the user that owns the profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the academic rank
     */
    public function academicRank(): BelongsTo
    {
        return $this->belongsTo(AcademicRank::class);
    }

    /**
     * Get the college
     */
    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    /**
     * Get the campus
     */
    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    /**
     * Get beneficiaries
     */
    public function beneficiaries(): HasMany
    {
        return $this->hasMany(Beneficiary::class);
    }

    /**
     * Get claims
     */
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Get dues
     */
    public function dues(): HasMany
    {
        return $this->hasMany(Due::class);
    }

    /**
     * Get training registrations
     */
    public function trainingRegistrations(): HasMany
    {
        return $this->hasMany(TrainingRegistration::class);
    }

    /**
     * Get full name (Surname, First Name Middle Name Extension)
     */
    public function getFullNameAttribute(): string
    {
        $name = "{$this->surname}, {$this->first_name}";

        if ($this->middle_name) {
            $name .= " {$this->middle_name}";
        }

        if ($this->extension_name) {
            $name .= " {$this->extension_name}";
        }

        return $name;
    }

    /**
     * Get display name (with prefix for formal display)
     */
    public function getDisplayNameAttribute(): string
    {
        $name = '';

        if ($this->prefix) {
            $name = $this->prefix.' ';
        }

        $name .= $this->first_name;

        if ($this->middle_name) {
            $name .= ' '.substr($this->middle_name, 0, 1).'.';
        }

        $name .= ' '.$this->surname;

        if ($this->extension_name) {
            $name .= ' '.$this->extension_name;
        }

        return $name;
    }

    /**
     * Get greeting name (with prefix for proper salutation)
     */
    public function getGreetingNameAttribute(): string
    {
        $name = '';

        if ($this->prefix) {
            $name = $this->prefix.' ';
        }

        $name .= $this->first_name;

        if ($this->extension_name) {
            $name .= ' '.$this->extension_name;
        }

        $name .= ' '.$this->surname;

        return $name;
    }

    /**
     * Calculate years of service based on date of service
     */
    public function getYearsOfServiceAttribute(): ?float
    {
        if (! $this->date_of_service) {
            return null;
        }

        return round($this->date_of_service->diffInYears(now()), 1);
    }

    /**
     * Calculate 7th day of service
     */
    public function getSeventhDayOfServiceAttribute(): ?Carbon
    {
        if (! $this->date_of_service) {
            return null;
        }

        return $this->date_of_service->copy()->addDays(6);
    }

    /**
     * Get age
     */
    public function getAgeAttribute(): ?int
    {
        if (! $this->birth_date) {
            return null;
        }

        return $this->birth_date->age;
    }

    /**
     * Scope for active members
     */
    public function scopeActive($query)
    {
        return $query->where('membership_status', 'active');
    }

    /**
     * Scope for a specific campus
     */
    public function scopeForCampus($query, $campusId)
    {
        return $query->where('campus_id', $campusId);
    }

    /**
     * Scope for a specific rank
     */
    public function scopeForRank($query, $rankId)
    {
        return $query->where('academic_rank_id', $rankId);
    }

    /**
     * Search scope
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('surname', 'like', "%{$search}%")
                ->orWhere('first_name', 'like', "%{$search}%")
                ->orWhere('middle_name', 'like', "%{$search}%")
                ->orWhere('employee_id', 'like', "%{$search}%");
        });
    }
}
