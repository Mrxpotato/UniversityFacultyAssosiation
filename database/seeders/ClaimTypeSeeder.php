<?php

namespace Database\Seeders;

use App\Models\ClaimType;
use Illuminate\Database\Seeder;

class ClaimTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $claimTypes = [
            [
                'name' => 'Death Aid Claim',
                'code' => 'DEATH_AID',
                'description' => 'Financial assistance provided to the family or beneficiary upon the death of a member or their registered beneficiary (Namatayan).',
                'required_documents' => ['Death Certificate (certified true copy)'],
                'max_amount' => 30000.00,
                'requires_beneficiary' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Medical Claim',
                'code' => 'MEDICAL',
                'description' => 'Financial assistance for hospitalization, medical procedures, and health-related expenses for members and their registered beneficiaries.',
                'required_documents' => ['Medical Abstract / Hospital Abstract'],
                'max_amount' => 25000.00,
                'requires_beneficiary' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Nuptial Claim',
                'code' => 'NUPTIAL',
                'description' => 'Financial assistance provided to members upon their marriage or wedding (Kasal).',
                'required_documents' => ['Marriage Certificate (certified true copy)'],
                'max_amount' => 10000.00,
                'requires_beneficiary' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Retirement Claim',
                'code' => 'RETIREMENT',
                'description' => 'Retirement cash benefit provided to members upon their retirement from the university. Requires submission of retirement papers approved/signed by the CSU President.',
                'required_documents' => ['Copy of Retirement (approved/signed by CSU President)'],
                'max_amount' => 30000.00,
                'requires_beneficiary' => false,
                'is_active' => true,
            ],
        ];

        // Deactivate old claim types that are no longer used
        $activeCodes = collect($claimTypes)->pluck('code')->toArray();
        \App\Models\ClaimType::whereNotIn('code', $activeCodes)->update(['is_active' => false]);

        foreach ($claimTypes as $type) {
            ClaimType::updateOrCreate(
                ['code' => $type['code']],
                $type
            );
        }
    }
}
