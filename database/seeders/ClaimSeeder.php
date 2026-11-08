<?php

namespace Database\Seeders;

use App\Models\Beneficiary;
use App\Models\Claim;
use App\Models\ClaimType;
use App\Models\FacultyProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ClaimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facultyProfiles = FacultyProfile::with('beneficiaries')->get();
        $claimTypes = ClaimType::all();
        $adminUsers = User::where('role', 'super_admin')->get();

        if ($facultyProfiles->isEmpty()) {
            $this->command->warn('No faculty profiles found. Please run CampusMembersSeeder first.');

            return;
        }

        if ($claimTypes->isEmpty()) {
            $this->command->warn('No claim types found. Please run ClaimTypeSeeder first.');

            return;
        }

        $statuses = ['pending', 'under_review', 'approved', 'rejected', 'released', 'remanded'];

        $descriptions = [
            'MEDICAL' => [
                'Hospitalization for pneumonia treatment',
                'Surgery for appendectomy',
                'Medical checkup and laboratory tests',
                'Emergency room admission for chest pain',
                'Hospitalization for dengue fever treatment',
                'Eye surgery (cataract removal)',
                'Physical therapy sessions',
                'Dental extraction and treatment',
            ],
            'DEATH_MEMBER' => [
                'Death aid claim for deceased faculty member',
                'Funeral assistance for late colleague',
            ],
            'DEATH_BENEFICIARY' => [
                'Death of registered spouse',
                'Death of registered parent (mother)',
                'Death of registered parent (father)',
                'Death of registered child',
            ],
            'RETIREMENT' => [
                'Retirement benefit upon completion of service',
                'Early retirement benefit claim',
            ],
            'EMERGENCY' => [
                'Emergency assistance due to house fire',
                'Calamity assistance (flooding)',
                'Emergency aid for typhoon damage',
                'Financial assistance for family emergency',
            ],
            'OTHER' => [
                'Educational assistance for dependent',
                'Special assistance as approved by board',
                'Miscellaneous financial assistance',
            ],
        ];

        $rejectionReasons = [
            'Incomplete documentation submitted',
            'Claim amount exceeds maximum allowable limit',
            'Beneficiary not registered in the system',
            'Claim type not applicable for this case',
            'Membership dues not up to date',
        ];

        $remandReasons = [
            'Additional documents required',
            'Verification of beneficiary relationship needed',
            'Need clarification on claim amount',
            'Hospital records incomplete',
        ];

        // Generate claims for about 60% of faculty members
        $facultyWithClaims = $facultyProfiles->random(intval($facultyProfiles->count() * 0.6));

        foreach ($facultyWithClaims as $profile) {
            // Each selected faculty gets 1-3 claims
            $claimCount = rand(1, 3);

            for ($i = 0; $i < $claimCount; $i++) {
                $claimType = $claimTypes->random();
                $status = $statuses[array_rand($statuses)];

                // Get beneficiary if claim type requires it
                $beneficiary = null;
                if ($claimType->requires_beneficiary && $profile->beneficiaries->isNotEmpty()) {
                    $beneficiary = $profile->beneficiaries->random();
                }

                // Generate appropriate date range
                $dateApplied = Carbon::now()->subDays(rand(1, 180));
                $dateApproved = null;
                $dateReleased = null;
                $approvedBy = null;
                $releasedBy = null;
                $rejectionReason = null;
                $remandReason = null;
                $isRemanded = false;

                // Set dates and other fields based on status
                switch ($status) {
                    case 'approved':
                        $dateApproved = $dateApplied->copy()->addDays(rand(3, 14));
                        $approvedBy = $adminUsers->isNotEmpty() ? $adminUsers->random()->id : null;
                        break;
                    case 'released':
                        $dateApproved = $dateApplied->copy()->addDays(rand(3, 14));
                        $dateReleased = $dateApproved->copy()->addDays(rand(1, 7));
                        $approvedBy = $adminUsers->isNotEmpty() ? $adminUsers->random()->id : null;
                        $releasedBy = $adminUsers->isNotEmpty() ? $adminUsers->random()->id : null;
                        break;
                    case 'rejected':
                        $rejectionReason = $rejectionReasons[array_rand($rejectionReasons)];
                        break;
                    case 'remanded':
                        $isRemanded = true;
                        $remandReason = $remandReasons[array_rand($remandReasons)];
                        break;
                }

                // Generate amount (random percentage of max amount)
                $maxAmount = $claimType->max_amount ?? 10000;
                $amount = round(rand(intval($maxAmount * 0.3), intval($maxAmount)) / 100) * 100;

                // Get description based on claim type
                $typeDescriptions = $descriptions[$claimType->code] ?? $descriptions['OTHER'];
                $description = $typeDescriptions[array_rand($typeDescriptions)];

                // Generate claim number
                $claimNumber = 'CLM-'.strtoupper(Str::random(8));

                Claim::create([
                    'claim_number' => $claimNumber,
                    'faculty_profile_id' => $profile->id,
                    'claim_type_id' => $claimType->id,
                    'beneficiary_id' => $beneficiary?->id,
                    'amount' => $amount,
                    'description' => $description,
                    'supporting_documents' => null,
                    'date_applied' => $dateApplied,
                    'date_approved' => $dateApproved,
                    'date_released' => $dateReleased,
                    'status' => $status,
                    'is_remanded' => $isRemanded,
                    'remand_reason' => $remandReason,
                    'rejection_reason' => $rejectionReason,
                    'approved_by' => $approvedBy,
                    'released_by' => $releasedBy,
                    'notes' => null,
                ]);
            }
        }

        $this->command->info('Claims seeded successfully!');
    }
}
