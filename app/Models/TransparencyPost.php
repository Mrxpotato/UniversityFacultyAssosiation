<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TransparencyPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'featured_image',
        'type',
        'visibility',
        'campus_id',
        'created_by',
        'is_featured',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    const TYPES = [
        'news' => 'News',
        'announcement' => 'Announcement',
        'resolution' => 'Resolution',
        'allegation' => 'Allegation',
        'release' => 'Release',
        'other' => 'Other',
    ];

    const TYPE_ICONS = [
        'news' => 'newspaper',
        'announcement' => 'megaphone',
        'resolution' => 'document-text',
        'allegation' => 'exclamation-circle',
        'release' => 'banknotes',
        'other' => 'information-circle',
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
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }

    /**
     * Get type icon
     */
    public function getTypeIconAttribute(): string
    {
        return self::TYPE_ICONS[$this->type] ?? 'document';
    }

    /**
     * Alias: image → featured_image
     */
    public function getImageAttribute(): ?string
    {
        return $this->featured_image;
    }

    /**
     * Alias: category → type
     */
    public function getCategoryAttribute(): ?string
    {
        return $this->type;
    }

    /**
     * Get excerpt
     */
    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->content), 200);
    }

    /**
     * Scope for published posts
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope for featured posts
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for a specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for public posts
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
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
