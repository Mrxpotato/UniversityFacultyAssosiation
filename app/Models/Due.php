<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Due extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'faculty_profile_id',
        'amount',
        'month',
        'due_date',
        'paid_date',
        'status',
        'payment_reference',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_date' => 'date',
        ];
    }

    const STATUSES = [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'overdue' => 'Overdue',
    ];

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

    /**
     * Check if overdue
     */
    public function isOverdue(): bool
    {
        return $this->status !== 'paid' && $this->due_date->isPast();
    }

    /**
     * Update status to overdue if applicable
     */
    public function updateOverdueStatus(): void
    {
        if ($this->status === 'pending' && $this->isOverdue()) {
            $this->update(['status' => 'overdue']);
        }
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(?string $reference = null): void
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => now(),
            'payment_reference' => $reference,
        ]);
    }

    /**
     * Scope for pending dues
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for paid dues
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for overdue
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope for a specific month
     */
    public function scopeForMonth($query, $month)
    {
        return $query->where('month', $month);
    }

    /**
     * Scope for a specific campus
     */
    public function scopeForCampus($query, $campusId)
    {
        return $query->whereHas('facultyProfile', function ($q) use ($campusId) {
            $q->where('campus_id', $campusId);
        });
    }
}
