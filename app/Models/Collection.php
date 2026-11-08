<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'report_number',
        'title',
        'content',
        'period_covered',
        'total_collected',
        'breakdown_data',
        'report_type',
        'attachments',
        'status',
        'published_at',
        'created_by',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'total_collected' => 'decimal:2',
            'breakdown_data' => 'array',
            'attachments' => 'array',
            'published_at' => 'datetime',
        ];
    }

    const REPORT_TYPES = [
        'Monthly' => 'Monthly',
        'Quarterly' => 'Quarterly',
        'Annual' => 'Annual',
    ];

    const STATUSES = [
        'Draft' => 'Draft',
        'Published' => 'Published',
        'Archived' => 'Archived',
    ];

    const REPORT_TYPE_ICONS = [
        'Monthly' => 'calendar_month',
        'Quarterly' => 'date_range',
        'Annual' => 'calendar_today',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->report_number)) {
                $model->report_number = self::generateReportNumber($model->report_type, $model->period_covered);
            }
        });
    }

    /**
     * Generate report number
     */
    public static function generateReportNumber(string $reportType, string $periodCovered): string
    {
        $year = date('Y');

        // For monthly reports, use month abbreviation
        if ($reportType === 'Monthly') {
            // Extract month from period_covered like "January 2026"
            $parts = explode(' ', $periodCovered);
            $month = strtoupper(substr($parts[0] ?? date('F'), 0, 3));
            $year = $parts[1] ?? $year;

            return "COL-{$year}-{$month}";
        }

        // For quarterly reports
        if ($reportType === 'Quarterly') {
            // Extract quarter from period_covered like "Q1 2026"
            if (preg_match('/Q(\d)/', $periodCovered, $matches)) {
                $quarter = 'Q'.$matches[1];
                preg_match('/\d{4}/', $periodCovered, $yearMatches);
                $year = $yearMatches[0] ?? $year;

                return "COL-{$year}-{$quarter}";
            }
        }

        // For annual reports
        if ($reportType === 'Annual') {
            preg_match('/\d{4}/', $periodCovered, $yearMatches);
            $year = $yearMatches[0] ?? $year;

            return "COL-{$year}-ANNUAL";
        }

        // Fallback
        $count = self::whereYear('created_at', $year)->count() + 1;

        return "COL-{$year}-".str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Get the author
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get formatted total collected
     */
    public function getFormattedTotalAttribute(): string
    {
        return '₱'.number_format($this->total_collected, 2);
    }

    /**
     * Scope for published collections
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    /**
     * Scope by report type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }
}
