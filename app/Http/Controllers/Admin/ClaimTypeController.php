<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicRank;
use App\Models\AuditLog;
use App\Models\ClaimType;
use App\Models\DeductionSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClaimTypeController extends Controller
{
    public function index(): View
    {
        $claimTypes = ClaimType::withCount('claims')
            ->orderBy('name')
            ->get();

        $stats = [
            'total' => $claimTypes->count(),
            'active' => $claimTypes->where('is_active', true)->count(),
            'with_deductions' => DeductionSetting::distinct('claim_type_id')->count('claim_type_id'),
        ];

        return view('admin.claim-types.index', compact('claimTypes', 'stats'));
    }

    public function create(): View
    {
        return view('admin.claim-types.form', [
            'claimType' => null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:claim_types,code'],
            'description' => ['nullable', 'string', 'max:1000'],
            'max_amount' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'requires_beneficiary' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'required_documents' => ['nullable', 'array'],
            'required_documents.*' => ['string', 'max:255'],
        ]);

        $validated['requires_beneficiary'] = $request->boolean('requires_beneficiary');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['required_documents'] = array_values(array_filter($validated['required_documents'] ?? []));

        $claimType = ClaimType::create($validated);

        // Auto-create deduction settings for all active academic ranks
        $this->createDeductionSettingsForType($claimType);

        AuditLog::log(
            'claim_type_created',
            ClaimType::class,
            $claimType->id,
            null,
            $claimType->toArray(),
            "Claim type created: {$claimType->name}"
        );

        return redirect()
            ->route('admin.claim-types.index')
            ->with('success', "Claim type \"{$claimType->name}\" created successfully. Deduction settings have been initialized.");
    }

    public function edit(ClaimType $claimType): View
    {
        return view('admin.claim-types.form', compact('claimType'));
    }

    public function update(Request $request, ClaimType $claimType): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('claim_types', 'code')->ignore($claimType->id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'max_amount' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'requires_beneficiary' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'required_documents' => ['nullable', 'array'],
            'required_documents.*' => ['string', 'max:255'],
        ]);

        $oldData = $claimType->toArray();

        $validated['requires_beneficiary'] = $request->boolean('requires_beneficiary');
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['required_documents'] = array_values(array_filter($validated['required_documents'] ?? []));

        $claimType->update($validated);

        // If claim type was just activated and has no deduction settings, create them
        if ($validated['is_active'] && $claimType->deductionSettings()->count() === 0) {
            $this->createDeductionSettingsForType($claimType);
        }

        AuditLog::log(
            'claim_type_updated',
            ClaimType::class,
            $claimType->id,
            $oldData,
            $claimType->fresh()->toArray(),
            "Claim type updated: {$claimType->name}"
        );

        return redirect()
            ->route('admin.claim-types.index')
            ->with('success', "Claim type \"{$claimType->name}\" updated successfully.");
    }

    public function destroy(ClaimType $claimType): RedirectResponse
    {
        if ($claimType->claims()->exists()) {
            return back()->with('error', "Cannot delete \"{$claimType->name}\" because it has existing claims. Deactivate it instead.");
        }

        $name = $claimType->name;

        // Remove associated deduction settings
        $claimType->deductionSettings()->delete();

        AuditLog::log(
            'claim_type_deleted',
            ClaimType::class,
            $claimType->id,
            $claimType->toArray(),
            null,
            "Claim type deleted: {$name}"
        );

        $claimType->delete();

        return redirect()
            ->route('admin.claim-types.index')
            ->with('success', "Claim type \"{$name}\" deleted successfully.");
    }

    /**
     * Create default (inactive) deduction settings for all active academic ranks.
     */
    private function createDeductionSettingsForType(ClaimType $claimType): void
    {
        $ranks = AcademicRank::active()->get();

        foreach ($ranks as $rank) {
            DeductionSetting::firstOrCreate(
                [
                    'academic_rank_id' => $rank->id,
                    'claim_type_id' => $claimType->id,
                ],
                [
                    'deduction_type' => 'percentage',
                    'deduction_value' => 0,
                    'is_active' => false,
                    'updated_by' => auth()->id(),
                ]
            );
        }
    }
}
