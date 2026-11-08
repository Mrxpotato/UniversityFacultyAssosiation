<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'phone_number',
        'campus_id',
        'employee_id_number',
        'id_picture',
        'employee_id_front',
        'employee_id_back',
        'email',
        'password',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'reviewed_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the campus for this registration
     */
    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    /**
     * Get the user who reviewed this registration
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        $name = ($this->prefix ?? '').
               ($this->prefix ? ' ' : '').
               $this->first_name.' '.
               ($this->middle_name ? substr($this->middle_name, 0, 1).'. ' : '').
               $this->surname.
               ($this->extension_name ? ' '.$this->extension_name : '');

        return trim($name);
    }

    /**
     * Check if registration is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if registration is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if registration is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Scope for pending registrations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for registrations by campus
     */
    public function scopeForCampus($query, $campusId)
    {
        return $query->where('campus_id', $campusId);
    }
}
