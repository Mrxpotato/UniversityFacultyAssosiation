<?php

namespace Database\Seeders;

use App\Models\Beneficiary;
use App\Models\FacultyProfile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BeneficiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facultyProfiles = FacultyProfile::all();

        if ($facultyProfiles->isEmpty()) {
            $this->command->warn('No faculty profiles found. Please run CampusMembersSeeder first.');

            return;
        }

        // Sample beneficiary data patterns
        $beneficiaryPatterns = [
            ['relationship' => 'wife', 'suffix' => 'Spouse'],
            ['relationship' => 'husband', 'suffix' => 'Spouse'],
            ['relationship' => 'mother', 'suffix' => 'Mother'],
            ['relationship' => 'father', 'suffix' => 'Father'],
            ['relationship' => 'son', 'suffix' => 'Son'],
            ['relationship' => 'daughter', 'suffix' => 'Daughter'],
            ['relationship' => 'brother', 'suffix' => 'Brother'],
            ['relationship' => 'sister', 'suffix' => 'Sister'],
        ];

        $firstNames = [
            'male' => ['Juan', 'Pedro', 'Jose', 'Miguel', 'Carlos', 'Antonio', 'Ricardo', 'Manuel', 'Eduardo', 'Roberto'],
            'female' => ['Maria', 'Ana', 'Rosa', 'Carmen', 'Elena', 'Teresa', 'Gloria', 'Patricia', 'Lucia', 'Isabel'],
        ];

        $addresses = [
            'Brgy. Centro, Tuguegarao City, Cagayan',
            'Brgy. Annafunan, Tuguegarao City, Cagayan',
            'Brgy. Pengue-Ruyu, Tuguegarao City, Cagayan',
            'Brgy. Carig Sur, Tuguegarao City, Cagayan',
            'Brgy. Larion Alto, Tuguegarao City, Cagayan',
            'Brgy. Pallua Norte, Tuguegarao City, Cagayan',
            'Brgy. Caritan Sur, Tuguegarao City, Cagayan',
            'Brgy. Ugac Norte, Tuguegarao City, Cagayan',
            'Brgy. Bagay, Aparri, Cagayan',
            'Brgy. Centro, Solana, Cagayan',
        ];

        foreach ($facultyProfiles as $profile) {
            // Each faculty gets 2-4 beneficiaries
            $beneficiaryCount = rand(2, 4);
            $usedRelationships = [];

            // First beneficiary is always primary (spouse or parent)
            $facultyGender = $profile->gender ?? 'male';
            $primaryRelationship = $facultyGender === 'male' ? 'wife' : 'husband';

            $this->createBeneficiary($profile, $primaryRelationship, $firstNames, $addresses, true);
            $usedRelationships[] = $primaryRelationship;

            // Add additional beneficiaries
            for ($i = 1; $i < $beneficiaryCount; $i++) {
                $availableRelationships = collect($beneficiaryPatterns)
                    ->pluck('relationship')
                    ->filter(fn ($r) => ! in_array($r, $usedRelationships))
                    ->values()
                    ->toArray();

                if (empty($availableRelationships)) {
                    break;
                }

                $relationship = $availableRelationships[array_rand($availableRelationships)];
                $this->createBeneficiary($profile, $relationship, $firstNames, $addresses, false);
                $usedRelationships[] = $relationship;
            }
        }

        $this->command->info('Beneficiaries seeded successfully!');
    }

    private function createBeneficiary(FacultyProfile $profile, string $relationship, array $firstNames, array $addresses, bool $isPrimary): void
    {
        // Determine gender based on relationship
        $beneficiaryGender = match ($relationship) {
            'wife', 'mother', 'mother-in-law', 'sister', 'daughter' => 'female',
            default => 'male',
        };

        // Generate appropriate birth date based on relationship
        $birthDate = match ($relationship) {
            'mother', 'father', 'mother-in-law', 'father-in-law' => Carbon::now()->subYears(rand(55, 75)),
            'wife', 'husband' => Carbon::now()->subYears(rand(30, 55)),
            'son', 'daughter' => Carbon::now()->subYears(rand(5, 25)),
            'brother', 'sister' => Carbon::now()->subYears(rand(25, 50)),
            default => Carbon::now()->subYears(rand(20, 60)),
        };

        // Generate name
        $firstName = $firstNames[$beneficiaryGender][array_rand($firstNames[$beneficiaryGender])];
        $fullName = $firstName.' '.$profile->surname;

        // For in-laws or spouse, use a different surname
        if (in_array($relationship, ['wife', 'husband', 'mother-in-law', 'father-in-law'])) {
            $surnames = ['Santos', 'Reyes', 'Cruz', 'Garcia', 'Torres', 'Flores', 'Rivera', 'Gomez', 'Diaz', 'Ramos'];
            $fullName = $firstName.' '.$surnames[array_rand($surnames)];
        }

        // Generate PSE (Philippine Statistics Authority ID)
        $pse = rand(1000, 9999).'-'.rand(1000, 9999).'-'.rand(1000, 9999);

        Beneficiary::create([
            'faculty_profile_id' => $profile->id,
            'full_name' => $fullName,
            'relationship' => $relationship,
            'date_of_birth' => $birthDate,
            'pse' => $pse,
            'contact_number' => '09'.rand(10, 99).rand(1000000, 9999999),
            'address' => $addresses[array_rand($addresses)],
            'is_primary' => $isPrimary,
            'is_active' => true,
        ]);
    }
}
