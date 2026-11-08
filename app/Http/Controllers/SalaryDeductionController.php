<?php

namespace App\Http\Controllers;

use App\Models\DeductionSetting;
use Illuminate\Http\Request;

class SalaryDeductionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $profile = $user->facultyProfile;

        if (! $profile) {
            return redirect()->route('dashboard')
                ->with('error', 'You need a faculty profile to view salary deductions.');
        }

        $profile->load('academicRank');
        $academicRank = $profile->academicRank;

        $salaryGrade = $academicRank?->salary_grade;
        $monthlySalary = $salaryGrade ? config("salary_grades.amounts.{$salaryGrade}", 0) : 0;

        // Build itemized deductions
        $deductions = collect();

        // 1) Deduction Settings configured for this academic rank (per claim type)
        $deductionSettings = collect();
        if ($academicRank) {
            $deductionSettings = DeductionSetting::where('academic_rank_id', $academicRank->id)
                ->where('is_active', true)
                ->where('deduction_value', '>', 0)
                ->with('claimType')
                ->get();

            foreach ($deductionSettings as $setting) {
                $computedAmount = $setting->deduction_type === 'percentage'
                    ? round($monthlySalary * ($setting->deduction_value / 100), 2)
                    : (float) $setting->deduction_value;

                $deductions->push([
                    'label' => $setting->claimType
                        ? $setting->claimType->name.' Deduction'
                        : 'General Deduction',
                    'description' => $setting->deduction_type === 'percentage'
                        ? number_format($setting->deduction_value, 2).'% of ₱'.number_format($monthlySalary, 2)
                        : 'Fixed ₱'.number_format($setting->deduction_value, 2),
                    'type' => $setting->deduction_type === 'percentage' ? 'Percentage' : 'Fixed',
                    'amount' => $computedAmount,
                    'icon' => 'tune',
                    'color' => 'teal',
                    'category' => 'setting',
                    'notes' => $setting->notes,
                ]);
            }
        }

        // Note: Deductions are ONLY from salary (rank-based settings above).
        // Claims are paid out at full amount from the collected fund.

        $totalDeductions = $deductions->sum('amount');
        $netTakeHome = $monthlySalary - $totalDeductions;

        return view('salary-deductions.index', compact(
            'profile',
            'academicRank',
            'salaryGrade',
            'monthlySalary',
            'deductions',
            'deductionSettings',
            'totalDeductions',
            'netTakeHome',
        ));
    }
}
