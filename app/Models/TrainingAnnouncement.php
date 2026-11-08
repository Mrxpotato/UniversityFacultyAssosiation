<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingAnnouncement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'training_number',
        'title',
        'content',
        'training_type',
        'speaker_facilitator',
        'speaker_bio',
        'date_start',
        'date_end',
        'venue',
        'mode',
        'max_participants',
        'registration_deadline',
        'registration_fee',
        'target_participants',
        'learning_objectives',
        'requirements',
        'contact_person',
        'contact_details',
        'attachments',
        'status',
        'published_at',
        'created_by',
        'views_count',
        'registered_count',
    ];

    protected function casts(): array
    {
        return [
            'date_start' => 'datetime',
            'date_end' => 'datetime',
            'registration_deadline' => 'date',
            'registration_fee' => 'decimal:2',
            'attachments' => 'array',
            'learning_objectives' => 'array',
            'published_at' => 'datetime',
        ];
    }

    const TRAINING_TYPES = [
        'Workshop' => 'Workshop',
        'Seminar' => 'Seminar',
        'Conference' => 'Conference',
        'Webinar' => 'Webinar',
        'Training Course' => 'Training Course',
    ];

    const MODES = [
        'Face-to-face' => 'Face-to-face',
        'Online' => 'Online',
        'Hybrid' => 'Hybrid',
    ];

    const STATUSES = [
        'Draft' => 'Draft',
        'Open for Registration' => 'Open for Registration',
        'Closed' => 'Closed',
        'Ongoing' => 'Ongoing',
        'Completed' => 'Completed',
        'Cancelled' => 'Cancelled',
    ];

    const TRAINING_TYPE_ICONS = [
        'Workshop' => 'build',
        'Seminar' => 'school',
        'Conference' => 'groups',
        'Webinar' => 'videocam',
        'Training Course' => 'menu_book',
    ];

    const MODE_ICONS = [
        'Face-to-face' => 'people',
        'Online' => 'computer',
        'Hybrid' => 'devices',
    ];

    const STATUS_COLORS = [
        'Draft' => 'gray',
        'Open for Registration' => 'green',
        'Closed' => 'yellow',
        'Ongoing' => 'blue',
        'Completed' => 'indigo',
        'Cancelled' => 'red',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->training_number)) {
                $model->training_number = self::generateTrainingNumber();
            }
        });
    }

    /**
     * Generate training number
     */
    public static function generateTrainingNumber(): string
    {
        $year = date('Y');
        $lastTraining = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastTraining) {
            $lastNumber = (int) substr($lastTraining->training_number, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'TRN-'.$year.'-'.str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get the author
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get formatted registration fee
     */
    public function getFormattedFeeAttribute(): string
    {
        if ($this->registration_fee == 0) {
            return 'Free';
        }

        return '₱'.number_format($this->registration_fee, 2);
    }

    /**
     * Get duration in hours
     */
    public function getDurationHoursAttribute(): int
    {
        return $this->date_start->diffInHours($this->date_end);
    }

    /**
     * Get slots remaining
     */
    public function getSlotsRemainingAttribute(): ?int
    {
        if (! $this->max_participants) {
            return null;
        }

        return max(0, $this->max_participants - $this->registered_count);
    }

    /**
     * Check if registration is still open
     */
    public function getIsRegistrationOpenAttribute(): bool
    {
        if ($this->status !== 'Open for Registration') {
            return false;
        }
        if ($this->registration_deadline && $this->registration_deadline->isPast()) {
            return false;
        }
        if ($this->max_participants && $this->registered_count >= $this->max_participants) {
            return false;
        }

        return true;
    }

    /**
     * Scope for published trainings
     */
    public function scopePublished($query)
    {
        return $query->whereNotIn('status', ['Draft']);
    }

    /**
     * Scope for open registration
     */
    public function scopeOpenForRegistration($query)
    {
        return $query->where('status', 'Open for Registration')
            ->where(function ($q) {
                $q->whereNull('registration_deadline')
                    ->orWhere('registration_deadline', '>=', now());
            });
    }

    /**
     * Scope by training type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('training_type', $type);
    }

    /**
     * Scope upcoming trainings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('date_start', '>', now());
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
