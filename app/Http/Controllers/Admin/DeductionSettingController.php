<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicRank;
use App\Models\AuditLog;
use App\Models\ClaimType;
use App\Models\DeductionSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DeductionSettingController extends Controller
{
    /**
     * Display deduction settings grouped by claim type
     */
    public function index(Request $request): View
    {
        $this->authorize('manage-dues');

        $claimTypes = ClaimType::where('is_active', true)->orderBy('name')->get();
        $selectedClaimTypeId = $request->query('claim_type_id', $claimTypes->first()?->id);

        $ranks = AcademicRank::active()->ordered()->get();

        // Load deduction settings for the selected claim type
        $settings = DeductionSetting::where('claim_type_id', $selectedClaimTypeId)
            ->get()
            ->keyBy('academic_rank_id');

        return view('admin.deductions.index', compact('claimTypes', 'selectedClaimTypeId', 'ranks', 'settings'));
    }

    /**
     * Update deduction settings for a specific claim type (bulk)
     */
    public function update(Request $request): RedirectResponse
    {
        $this->authorize('manage-dues');

        $validated = $request->validate([
            'claim_type_id' => ['required', 'exists:claim_types,id'],
            'settings' => ['required', 'array'],
            'settings.*.academic_rank_id' => ['required', 'exists:academic_ranks,id'],
            'settings.*.deduction_type' => ['required', 'in:percentage,fixed'],
            'settings.*.deduction_value' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'settings.*.is_active' => ['sometimes', 'boolean'],
            'settings.*.notes' => ['nullable', 'string', 'max:255'],
        ]);

        $userId = auth()->id();
        $claimTypeId = $validated['claim_type_id'];

        foreach ($validated['settings'] as $setting) {
            $deduction = DeductionSetting::updateOrCreate(
                [
                    'academic_rank_id' => $setting['academic_rank_id'],
                    'claim_type_id' => $claimTypeId,
                ],
                [
                    'deduction_type' => $setting['deduction_type'],
                    'deduction_value' => $setting['deduction_value'],
                    'is_active' => isset($setting['is_active']) ? (bool) $setting['is_active'] : false,
                    'notes' => $setting['notes'] ?? null,
                    'updated_by' => $userId,
                ]
            );

            AuditLog::log(
                'deduction_setting_updated',
                DeductionSetting::class,
                $deduction->id,
                null,
                $deduction->toArray(),
                'Deduction setting updated for rank: '.$deduction->academicRank->name
            );
        }

        $claimType = ClaimType::find($claimTypeId);

        return redirect()
            ->route('admin.deductions.index', ['claim_type_id' => $claimTypeId])
            ->with('success', "Deduction settings for \"{$claimType->name}\" updated successfully.");
    }

    /**
     * Update a single deduction setting (AJAX-friendly)
     */
    public function updateSingle(Request $request, AcademicRank $academicRank): RedirectResponse
    {
        $this->authorize('manage-dues');

        $validated = $request->validate([
            'claim_type_id' => ['required', 'exists:claim_types,id'],
            'deduction_type' => ['required', 'in:percentage,fixed'],
            'deduction_value' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'is_active' => ['sometimes', 'boolean'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $deduction = DeductionSetting::updateOrCreate(
            [
                'academic_rank_id' => $academicRank->id,
                'claim_type_id' => $validated['claim_type_id'],
            ],
            [
                'deduction_type' => $validated['deduction_type'],
                'deduction_value' => $validated['deduction_value'],
                'is_active' => $request->boolean('is_active', false),
                'notes' => $validated['notes'] ?? null,
                'updated_by' => auth()->id(),
            ]
        );

        AuditLog::log(
            'deduction_setting_updated',
            DeductionSetting::class,
            $deduction->id,
            null,
            $deduction->toArray(),
            'Deduction setting updated for rank: '.$academicRank->name
        );

        return back()->with('success', "Deduction for {$academicRank->name} updated successfully.");
    }
}
