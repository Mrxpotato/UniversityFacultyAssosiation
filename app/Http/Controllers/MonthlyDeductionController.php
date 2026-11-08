<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\DeductionSetting;
use App\Models\FacultyProfile;
use App\Models\MonthlyDeduction;
use App\Services\FundService;
use Illuminate\Http\Request;

class MonthlyDeductionController extends Controller
{
    /**
     * Display monthly deductions page.
     */
    public function index(Request $request)
    {
        $this->authorize('manage-dues');

        $month = $request->get('month', now()->format('Y-m'));

        $query = MonthlyDeduction::with(['facultyProfile.user', 'facultyProfile.campus', 'claimType'])
            ->forMonth($month);

        if ($request->filled('campus_id')) {
            $query->forCampus($request->campus_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('facultyProfile', function ($q) use ($search) {
                $q->whereHas('user', function ($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                })->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $deductions = $query->latest()->paginate(20)->withQueryString();

        // Statistics for selected month
        $statsQuery = MonthlyDeduction::forMonth($month);
        if ($request->filled('campus_id')) {
            $statsQuery->forCampus($request->campus_id);
        }

        $statistics = [
            'total_amount' => (clone $statsQuery)->sum('deduction_amount'),
            'collected_amount' => (clone $statsQuery)->collected()->sum('deduction_amount'),
            'pending_amount' => (clone $statsQuery)->pending()->sum('deduction_amount'),
            'collected_count' => (clone $statsQuery)->collected()->count(),
            'pending_count' => (clone $statsQuery)->pending()->count(),
            'total_count' => (clone $statsQuery)->count(),
        ];

        // Available months that have been generated
        $availableMonths = MonthlyDeduction::selectRaw('DISTINCT month')
            ->orderByDesc('month')
            ->pluck('month');

        $campuses = Campus::active()->get();

        return view('admin.monthly-deductions.index', compact(
            'deductions', 'statistics', 'campuses', 'month', 'availableMonths'
        ));
    }

    /**
     * Generate monthly deductions for a given month.
     */
    public function generate(Request $request)
    {
        $this->authorize('manage-dues');

        $request->validate([
            'month' => 'required|date_format:Y-m',
        ]);

        $month = $request->month;
        $actor = auth()->user();

        // Get all active faculty with academic ranks
        $profiles = FacultyProfile::active()
            ->whereNotNull('academic_rank_id')
            ->with('academicRank')
            ->get();

        // Get all active deduction settings
        $settings = DeductionSetting::active()
            ->where('deduction_value', '>', 0)
            ->with('claimType')
            ->get()
            ->groupBy('academic_rank_id');

        $created = 0;
        $skipped = 0;

        foreach ($profiles as $profile) {
            $rankId = $profile->academic_rank_id;
            $salaryGrade = $profile->academicRank?->salary_grade;
            $grossSalary = $salaryGrade ? (float) config("salary_grades.amounts.{$salaryGrade}", 0) : 0;

            if ($grossSalary <= 0) {
                continue;
            }

            $rankSettings = $settings->get($rankId, collect());

            foreach ($rankSettings as $setting) {
                // Check if already generated for this member + setting + month
                $exists = MonthlyDeduction::where('faculty_profile_id', $profile->id)
                    ->where('deduction_setting_id', $setting->id)
                    ->where('month', $month)
                    ->exists();

                if ($exists) {
                    $skipped++;

                    continue;
                }

                $amount = $setting->deduction_type === 'percentage'
                    ? round($grossSalary * ($setting->deduction_value / 100), 2)
                    : (float) $setting->deduction_value;

                if ($amount <= 0) {
                    continue;
                }

                $label = $setting->claimType
                    ? $setting->claimType->name.' Deduction'
                    : 'General Deduction';

                MonthlyDeduction::create([
                    'faculty_profile_id' => $profile->id,
                    'deduction_setting_id' => $setting->id,
                    'claim_type_id' => $setting->claim_type_id,
                    'month' => $month,
                    'gross_salary' => $grossSalary,
                    'deduction_type' => $setting->deduction_type,
                    'deduction_value' => $setting->deduction_value,
                    'deduction_amount' => $amount,
                    'description' => $label,
                    'generated_by' => $actor->id,
                ]);

                $created++;
            }
        }

        $message = "Generated {$created} monthly deductions for {$month}.";
        if ($skipped > 0) {
            $message .= " ({$skipped} already existed, skipped)";
        }

        return redirect()
            ->route('admin.monthly-deductions.index', ['month' => $month])
            ->with('success', $message);
    }

    /**
     * Mark individual monthly deduction as collected.
     */
    public function markCollected(MonthlyDeduction $monthlyDeduction)
    {
        $this->authorize('manage-dues');

        $monthlyDeduction->markCollected(auth()->id());

        // Record fund inflow
        app(FundService::class)->recordMonthlyDeductionCollected($monthlyDeduction, auth()->user());

        return redirect()->back()->with('success', 'Monthly deduction marked as collected.');
    }

    /**
     * Bulk mark all pending deductions for a campus + month as collected.
     */
    public function markCampusCollected(Request $request)
    {
        $this->authorize('manage-dues');

        $request->validate([
            'campus_id' => 'required|exists:campuses,id',
            'month' => 'required|date_format:Y-m',
        ]);

        // Fetch before bulk update for fund transactions
        $deductionsToMark = MonthlyDeduction::pending()
            ->forMonth($request->month)
            ->forCampus($request->campus_id)
            ->get();

        $count = MonthlyDeduction::pending()
            ->forMonth($request->month)
            ->forCampus($request->campus_id)
            ->update([
                'status' => 'collected',
                'collected_at' => now(),
                'collected_by' => auth()->id(),
            ]);

        // Record fund inflows
        if ($deductionsToMark->isNotEmpty()) {
            app(FundService::class)->recordBulkMonthlyDeductionCollected($deductionsToMark, auth()->user());
        }

        $campus = Campus::find($request->campus_id);

        return redirect()
            ->back()
            ->with('success', "Marked {$count} deductions as collected for {$campus->name}.");
    }

    /**
     * Export monthly deductions to CSV.
     */
    public function export(Request $request)
    {
        $this->authorize('manage-dues');

        $month = $request->get('month', now()->format('Y-m'));

        $query = MonthlyDeduction::with(['facultyProfile.user', 'facultyProfile.campus', 'claimType'])
            ->forMonth($month);

        if ($request->filled('campus_id')) {
            $query->forCampus($request->campus_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deductions = $query->latest()->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="monthly-deductions-'.$month.'.csv"',
        ];

        $callback = function () use ($deductions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Employee ID', 'Name', 'Campus', 'Deduction Type', 'Description',
                'Gross Salary', 'Rate/Amount', 'Deduction Amount', 'Status', 'Collected Date',
            ]);

            foreach ($deductions as $ded) {
                fputcsv($file, [
                    $ded->facultyProfile->employee_id ?? '',
                    $ded->facultyProfile->user->name ?? '',
                    $ded->facultyProfile->campus->name ?? '',
                    ucfirst($ded->deduction_type),
                    $ded->description,
                    $ded->gross_salary,
                    $ded->deduction_value,
                    $ded->deduction_amount,
                    ucfirst($ded->status),
                    $ded->collected_at?->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
