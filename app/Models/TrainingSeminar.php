<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TrainingSeminar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trainings_seminars';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'venue',
        'facilitator',
        'start_datetime',
        'end_datetime',
        'type',
        'campus_id',
        'created_by',
        'max_participants',
        'registration_fee',
        'status',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'start_datetime' => 'datetime',
            'end_datetime' => 'datetime',
            'registration_fee' => 'decimal:2',
            'is_published' => 'boolean',
        ];
    }

    const TYPES = [
        'training' => 'Training',
        'seminar' => 'Seminar',
        'workshop' => 'Workshop',
        'conference' => 'Conference',
        'webinar' => 'Webinar',
        'other' => 'Other',
    ];

    const STATUSES = [
        'upcoming' => 'Upcoming',
        'ongoing' => 'Ongoing',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $slug = Str::slug($model->title);
                $original = $slug;
                $count = 1;

                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $original.'-'.$count++;
                }

                $model->slug = $slug;
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the author
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the campus
     */
    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    /**
     * Get registrations
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(TrainingRegistration::class);
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Alias: start_date → start_datetime
     */
    public function getStartDateAttribute()
    {
        return $this->start_datetime;
    }

    /**
     * Alias: end_date → end_datetime
     */
    public function getEndDateAttribute()
    {
        return $this->end_datetime;
    }

    /**
     * Alias: start_time (formatted from start_datetime)
     */
    public function getStartTimeAttribute(): ?string
    {
        return $this->start_datetime?->format('g:i A');
    }

    /**
     * Alias: fee → registration_fee
     */
    public function getFeeAttribute()
    {
        return $this->registration_fee;
    }

    /**
     * Alias: is_free (true when registration_fee is 0 or null)
     */
    public function getIsFreeAttribute(): bool
    {
        return ! $this->registration_fee || $this->registration_fee <= 0;
    }

    /**
     * Alias: image (no DB column, always null)
     */
    public function getImageAttribute(): ?string
    {
        return null;
    }

    /**
     * Get registered count
     */
    public function getRegisteredCountAttribute(): int
    {
        return $this->registrations()->whereNotIn('status', ['cancelled'])->count();
    }

    /**
     * Check if full
     */
    public function isFull(): bool
    {
        if (! $this->max_participants) {
            return false;
        }

        return $this->registered_count >= $this->max_participants;
    }

    /**
     * Check if registration is open
     */
    public function isRegistrationOpen(): bool
    {
        return $this->status === 'upcoming' &&
               $this->is_published &&
               ! $this->isFull() &&
               $this->start_datetime->isFuture();
    }

    /**
     * Scope for published
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for upcoming
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
            ->where('start_datetime', '>', now());
    }

    /**
     * Scope for a specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for a specific campus
     */
    public function scopeForCampus($query, $campusId)
    {
        return $query->where(function ($q) use ($campusId) {
            $q->where('campus_id', $campusId)
                ->orWhereNull('campus_id');
        });
    }
}
