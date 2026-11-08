<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class FundTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reference_number',
        'type',
        'category',
        'amount',
        'description',
        'notes',
        'transactable_type',
        'transactable_id',
        'recorded_by',
        'transacted_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transacted_at' => 'datetime',
        ];
    }

    const TYPES = [
        'inflow' => 'Inflow',
        'outflow' => 'Outflow',
    ];

    const CATEGORIES = [
        'deduction_collected' => 'Deduction Collected',
        'salary_deduction' => 'Salary Deduction',
        'claim_released' => 'Claim Released',
        'manual_adjustment' => 'Manual Adjustment',
        'donation' => 'Donation',
        'other' => 'Other',
    ];

    const CATEGORY_ICONS = [
        'deduction_collected' => 'account_balance_wallet',
        'salary_deduction' => 'request_quote',
        'claim_released' => 'payments',
        'manual_adjustment' => 'tune',
        'donation' => 'volunteer_activism',
        'other' => 'more_horiz',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->reference_number)) {
                $model->reference_number = 'TXN-'.strtoupper(Str::random(8));
            }
        });
    }

    public function transactable(): MorphTo
    {
        return $this->morphTo();
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopeInflows($query)
    {
        return $query->where('type', 'inflow');
    }

    public function scopeOutflows($query)
    {
        return $query->where('type', 'outflow');
    }

    public function scopeOfCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeForPeriod($query, $from, $to)
    {
        return $query->whereBetween('transacted_at', [$from, $to]);
    }

    public function getSignedAmountAttribute(): float
    {
        return $this->type === 'inflow' ? (float) $this->amount : -1 * (float) $this->amount;
    }

    public static function currentBalance(): float
    {
        return (float) static::selectRaw(
            "COALESCE(SUM(CASE WHEN type = 'inflow' THEN amount ELSE -amount END), 0) as balance"
        )->value('balance');
    }
}
