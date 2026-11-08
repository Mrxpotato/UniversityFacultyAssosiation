<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resolution extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'resolution_number',
        'title',
        'content',
        'resolved_date',
        'votes_for',
        'votes_against',
        'votes_abstain',
        'attachments',
        'status',
        'published_at',
        'created_by',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'resolved_date' => 'date',
            'attachments' => 'array',
            'published_at' => 'datetime',
        ];
    }

    const STATUSES = [
        'Draft' => 'Draft',
        'Published' => 'Published',
        'Archived' => 'Archived',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->resolution_number)) {
                $model->resolution_number = self::generateResolutionNumber();
            }
        });
    }

    /**
     * Generate resolution number
     */
    public static function generateResolutionNumber(): string
    {
        return \Illuminate\Support\Facades\DB::transaction(function () {
            $year = date('Y');
            $count = self::withTrashed()
                ->whereYear('created_at', $year)
                ->lockForUpdate()
                ->count();

            return 'RES-'.$year.'-'.str_pad($count + 1, 3, '0', STR_PAD_LEFT);
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
     * Get total votes
     */
    public function getTotalVotesAttribute(): int
    {
        return $this->votes_for + $this->votes_against + $this->votes_abstain;
    }

    /**
     * Get approval percentage
     */
    public function getApprovalPercentageAttribute(): float
    {
        $total = $this->votes_for + $this->votes_against;
        if ($total === 0) {
            return 0;
        }

        return round(($this->votes_for / $total) * 100, 1);
    }

    /**
     * Scope for published resolutions
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
