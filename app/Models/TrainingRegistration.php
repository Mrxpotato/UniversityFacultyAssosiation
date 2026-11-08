<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_seminar_id',
        'faculty_profile_id',
        'status',
        'notes',
    ];

    const STATUSES = [
        'registered' => 'Registered',
        'confirmed' => 'Confirmed',
        'attended' => 'Attended',
        'cancelled' => 'Cancelled',
    ];

    /**
     * Get the training/seminar
     */
    public function trainingSeminar(): BelongsTo
    {
        return $this->belongsTo(TrainingSeminar::class);
    }

    /**
     * Get the faculty profile
     */
    public function facultyProfile(): BelongsTo
    {
        return $this->belongsTo(FacultyProfile::class);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }
}
