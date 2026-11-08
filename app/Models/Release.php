<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Release extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'release_number',
        'title',
        'content',
        'release_type',
        'contact_person',
        'contact_email',
        'contact_phone',
        'attachments',
        'status',
        'published_at',
        'created_by',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'published_at' => 'datetime',
        ];
    }

    const RELEASE_TYPES = [
        'Press Release' => 'Press Release',
        'Official Statement' => 'Official Statement',
        'Position Paper' => 'Position Paper',
        'Public Announcement' => 'Public Announcement',
    ];

    const STATUSES = [
        'Draft' => 'Draft',
        'Published' => 'Published',
        'Archived' => 'Archived',
    ];

    const RELEASE_TYPE_ICONS = [
        'Press Release' => 'newspaper',
        'Official Statement' => 'campaign',
        'Position Paper' => 'description',
        'Public Announcement' => 'announcement',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->release_number)) {
                $model->release_number = self::generateReleaseNumber();
            }
        });
    }

    /**
     * Generate release number
     */
    public static function generateReleaseNumber(): string
    {
        return \Illuminate\Support\Facades\DB::transaction(function () {
            $year = date('Y');
            $count = self::withTrashed()
                ->whereYear('created_at', $year)
                ->lockForUpdate()
                ->count();

            return 'PR-'.$year.'-'.str_pad($count + 1, 3, '0', STR_PAD_LEFT);
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
     * Scope for published releases
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    /**
     * Scope by release type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('release_type', $type);
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
