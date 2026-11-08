<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Memo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'memo_number',
        'title',
        'content',
        'category',
        'issued_by',
        'effective_date',
        'expiration_date',
        'requires_acknowledgment',
        'priority',
        'attachments',
        'status',
        'published_at',
        'created_by',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'effective_date' => 'date',
            'expiration_date' => 'date',
            'requires_acknowledgment' => 'boolean',
            'attachments' => 'array',
            'published_at' => 'datetime',
        ];
    }

    const CATEGORIES = [
        'Administrative' => 'Administrative',
        'Financial' => 'Financial',
        'Benefits' => 'Benefits',
        'Policy' => 'Policy',
        'Urgent' => 'Urgent',
    ];

    const ISSUED_BY = [
        'President' => 'President',
        'Secretary' => 'Secretary',
        'Board of Directors' => 'Board of Directors',
    ];

    const PRIORITIES = [
        'Normal' => 'Normal',
        'High' => 'High',
        'Urgent' => 'Urgent',
    ];

    const STATUSES = [
        'Draft' => 'Draft',
        'Published' => 'Published',
        'Archived' => 'Archived',
    ];

    const PRIORITY_COLORS = [
        'Normal' => 'gray',
        'High' => 'yellow',
        'Urgent' => 'red',
    ];

    const CATEGORY_ICONS = [
        'Administrative' => 'business',
        'Financial' => 'account_balance',
        'Benefits' => 'card_giftcard',
        'Policy' => 'policy',
        'Urgent' => 'warning',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->memo_number)) {
                $model->memo_number = self::generateMemoNumber();
            }
        });
    }

    /**
     * Generate memo number
     */
    public static function generateMemoNumber(): string
    {
        return \Illuminate\Support\Facades\DB::transaction(function () {
            $year = date('Y');
            $count = self::withTrashed()
                ->whereYear('created_at', $year)
                ->lockForUpdate()
                ->count();

            return $year.'-'.str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Get the author
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get users who acknowledged this memo
     */
    public function acknowledgedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'memo_acknowledgments')
            ->withPivot('acknowledged_at')
            ->withTimestamps();
    }

    /**
     * Check if a user has acknowledged this memo
     */
    public function isAcknowledgedBy(User $user): bool
    {
        return $this->acknowledgedBy()->where('user_id', $user->id)->exists();
    }

    /**
     * Get acknowledgment count
     */
    public function getAcknowledgmentCountAttribute(): int
    {
        return $this->acknowledgedBy()->count();
    }

    /**
     * Scope for published memos
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    /**
     * Scope for active memos (not expired)
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expiration_date')
                ->orWhere('expiration_date', '>=', now());
        });
    }

    /**
     * Scope by category
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope by priority
     */
    public function scopeOfPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Check if memo is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->expiration_date && $this->expiration_date->isPast();
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
