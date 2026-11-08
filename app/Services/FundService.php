<?php

namespace App\Services;

use App\Models\Claim;
use App\Models\FundTransaction;
use App\Models\MonthlyDeduction;
use App\Models\User;
use Illuminate\Support\Collection;

class FundService
{
    /**
     * @deprecated Deductions are collected via salary (MonthlyDeduction), not from claims.
     * Kept for backward compatibility with existing fund transaction records.
     */
    public function recordDeductionCollected(Claim $claim, User $actor): FundTransaction
    {
        return FundTransaction::create([
            'type' => 'inflow',
            'category' => 'deduction_collected',
            'amount' => $claim->deduction_amount,
            'description' => "Deduction collected from {$claim->claim_number}",
            'notes' => $claim->claimType?->name ?? null,
            'transactable_type' => Claim::class,
            'transactable_id' => $claim->id,
            'recorded_by' => $actor->id,
            'transacted_at' => now(),
        ]);
    }

    /**
     * @deprecated Deductions are collected via salary (MonthlyDeduction), not from claims.
     * Kept for backward compatibility with existing fund transaction records.
     */
    public function recordBulkDeductionCollected(Collection $claims, User $actor): int
    {
        $now = now();
        $records = $claims->map(function ($claim) use ($actor, $now) {
            return [
                'reference_number' => 'TXN-'.strtoupper(\Illuminate\Support\Str::random(8)),
                'type' => 'inflow',
                'category' => 'deduction_collected',
                'amount' => $claim->deduction_amount,
                'description' => "Deduction collected from {$claim->claim_number}",
                'notes' => $claim->claimType?->name ?? null,
                'transactable_type' => Claim::class,
                'transactable_id' => $claim->id,
                'recorded_by' => $actor->id,
                'transacted_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();

        foreach (array_chunk($records, 100) as $chunk) {
            FundTransaction::insert($chunk);
        }

        return count($records);
    }

    /**
     * Record outflow when a claim is released.
     * Claims are paid out at full amount — deductions are collected separately from salary.
     */
    public function recordClaimReleased(Claim $claim, User $actor): FundTransaction
    {
        return FundTransaction::create([
            'type' => 'outflow',
            'category' => 'claim_released',
            'amount' => $claim->amount,
            'description' => "Claim {$claim->claim_number} released",
            'notes' => $claim->claimType?->name ?? null,
            'transactable_type' => Claim::class,
            'transactable_id' => $claim->id,
            'recorded_by' => $actor->id,
            'transacted_at' => now(),
        ]);
    }

    /**
     * Record a manual transaction (adjustment, donation, other).
     */
    public function recordManualTransaction(array $data, User $actor): FundTransaction
    {
        return FundTransaction::create([
            'type' => $data['type'],
            'category' => $data['category'],
            'amount' => $data['amount'],
            'description' => $data['description'],
            'notes' => $data['notes'] ?? null,
            'recorded_by' => $actor->id,
            'transacted_at' => $data['transacted_at'] ?? now(),
        ]);
    }

    /**
     * Record inflow for a single monthly salary deduction collected.
     */
    public function recordMonthlyDeductionCollected(MonthlyDeduction $deduction, User $actor): FundTransaction
    {
        return FundTransaction::create([
            'type' => 'inflow',
            'category' => 'salary_deduction',
            'amount' => $deduction->deduction_amount,
            'description' => "Monthly salary deduction ({$deduction->month}) - {$deduction->description}",
            'notes' => $deduction->facultyProfile?->user?->name ?? null,
            'transactable_type' => MonthlyDeduction::class,
            'transactable_id' => $deduction->id,
            'recorded_by' => $actor->id,
            'transacted_at' => now(),
        ]);
    }

    /**
     * Record inflows for bulk monthly deduction collections.
     */
    public function recordBulkMonthlyDeductionCollected(Collection $deductions, User $actor): int
    {
        $now = now();
        $records = $deductions->map(function ($deduction) use ($actor, $now) {
            return [
                'reference_number' => 'TXN-'.strtoupper(\Illuminate\Support\Str::random(8)),
                'type' => 'inflow',
                'category' => 'salary_deduction',
                'amount' => $deduction->deduction_amount,
                'description' => "Monthly salary deduction ({$deduction->month}) - {$deduction->description}",
                'notes' => $deduction->facultyProfile?->user?->name ?? null,
                'transactable_type' => MonthlyDeduction::class,
                'transactable_id' => $deduction->id,
                'recorded_by' => $actor->id,
                'transacted_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();

        foreach (array_chunk($records, 100) as $chunk) {
            FundTransaction::insert($chunk);
        }

        return count($records);
    }

    /**
     * Get current fund balance.
     */
    public function getCurrentBalance(): float
    {
        return FundTransaction::currentBalance();
    }

    /**
     * Get summary statistics.
     */
    public function getSummary(): array
    {
        return [
            'current_balance' => FundTransaction::currentBalance(),
            'total_inflows' => (float) FundTransaction::inflows()->sum('amount'),
            'total_outflows' => (float) FundTransaction::outflows()->sum('amount'),
            'deductions_collected' => (float) FundTransaction::ofCategory('deduction_collected')->sum('amount'),
            'salary_deductions' => (float) FundTransaction::ofCategory('salary_deduction')->sum('amount'),
            'claims_released' => (float) FundTransaction::ofCategory('claim_released')->sum('amount'),
            'transaction_count' => FundTransaction::count(),
        ];
    }
}
