<?php

namespace Database\Seeders;

use App\Models\AcademicRank;
use App\Models\Campus;
use App\Models\College;
use App\Models\FacultyProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create System Admin (Super Admin)
        $admin = User::create([
            'name' => 'System Administrator',
            'employee_id' => 'ADMIN-001',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        $carigCampus = Campus::where('code', 'CARIG')->first();
        $college = College::where('code', 'CAS')->first();
        $professorRank = AcademicRank::where('code', 'PROF1')->first();

        if ($carigCampus && $college && $professorRank) {
            FacultyProfile::create([
                'user_id' => $admin->id,
                'campus_id' => $carigCampus->id,
                'college_id' => $college->id,
                'academic_rank_id' => $professorRank->id,
                'employee_id' => 'ADMIN-001',
                'surname' => 'Administrator',
                'first_name' => 'System',
                'middle_name' => 'Admin',
                'extension_name' => null,
                'civil_status' => 'single',
                'gender' => 'male',
                'birth_date' => '1975-01-01',
                'birth_place' => 'Tuguegarao City, Cagayan',
                'home_address' => 'CSU Main Office, Carig, Tuguegarao City',
                'phone_number' => '09171234567',
                'id_picture' => null,
                'date_of_service' => '2015-01-01',
                'membership_status' => 'active',
                'membership_date' => '2015-01-01',
            ]);
        }

        // Create Faculty Officers for each campus
        // The 9 university officers also serve as campus presidents in their respective campuses.
        // University President & Treasurer have elevated university-wide roles.
        $facultyOfficers = [
            [
                'campus_code' => 'PIAT',
                'name' => 'Dr. Ricardo Casaway',
                'email' => 'ricardo.casaway@csu.edu.ph',
                'employee_id' => 'PRES-PIAT',
                'surname' => 'Casaway',
                'first_name' => 'Ricardo',
                'middle_name' => '',
                'gender' => 'male',
                'position' => 'President',
                'role' => 'university_president',
            ],
            [
                'campus_code' => 'APARRI',
                'name' => 'Dr. Billy Javier',
                'email' => 'billy.javier@csu.edu.ph',
                'employee_id' => 'VP-APARRI',
                'surname' => 'Javier',
                'first_name' => 'Billy',
                'middle_name' => '',
                'gender' => 'male',
                'position' => 'Vice President',
                'role' => 'campus_president',
            ],
            [
                'campus_code' => 'LASAM',
                'name' => 'Dr. Jehan Gerardo',
                'email' => 'jehan.gerardo@csu.edu.ph',
                'employee_id' => 'SEC-LASAM',
                'surname' => 'Gerardo',
                'first_name' => 'Jehan',
                'middle_name' => '',
                'gender' => 'male',
                'position' => 'Secretary',
                'role' => 'campus_president',
            ],
            [
                'campus_code' => 'ANDREWS',
                'name' => 'Dr. Jaypee Alegado',
                'email' => 'jaypee.alegado@csu.edu.ph',
                'employee_id' => 'TRES-ANDREWS',
                'surname' => 'Alegado',
                'first_name' => 'Jaypee',
                'middle_name' => '',
                'gender' => 'male',
                'position' => 'Treasurer',
                'role' => 'university_treasurer',
            ],
            [
                'campus_code' => 'CARIG',
                'name' => 'Ms. Helen Dangani',
                'email' => 'helen.dangani@csu.edu.ph',
                'employee_id' => 'ATRES-CARIG',
                'surname' => 'Dangani',
                'first_name' => 'Helen',
                'middle_name' => '',
                'gender' => 'female',
                'position' => 'Assistant Treasurer',
                'role' => 'campus_president',
            ],
            [
                'campus_code' => 'GONZAGA',
                'name' => 'Dr. Bert Bermudez',
                'email' => 'bert.bermudez@csu.edu.ph',
                'employee_id' => 'AUD-GONZAGA',
                'surname' => 'Bermudez',
                'first_name' => 'Bert',
                'middle_name' => '',
                'gender' => 'male',
                'position' => 'Auditor',
                'role' => 'campus_president',
            ],
            [
                'campus_code' => 'SOLANA',
                'name' => 'Mr. Stanley Follante',
                'email' => 'stanley.follante@csu.edu.ph',
                'employee_id' => 'BM-SOLANA',
                'surname' => 'Follante',
                'first_name' => 'Stanley',
                'middle_name' => '',
                'gender' => 'male',
                'position' => 'Business Manager',
                'role' => 'campus_president',
            ],
            [
                'campus_code' => 'LALLO',
                'name' => 'Dr. Domingo Dela Vega',
                'email' => 'domingo.delavega@csu.edu.ph',
                'employee_id' => 'BM-LALLO',
                'surname' => 'Dela Vega',
                'first_name' => 'Domingo',
                'middle_name' => '',
                'gender' => 'male',
                'position' => 'Business Manager',
                'role' => 'campus_president',
            ],
            [
                'campus_code' => 'SANCHEZ',
                'name' => 'Dr. Freddic Masuli',
                'email' => 'freddic.masuli@csu.edu.ph',
                'employee_id' => 'PIO-SANCHEZ',
                'surname' => 'Masuli',
                'first_name' => 'Freddic',
                'middle_name' => '',
                'gender' => 'male',
                'position' => 'PIO',
                'role' => 'campus_president',
            ],
        ];

        foreach ($facultyOfficers as $officer) {
            $campus = Campus::where('code', $officer['campus_code'])->first();

            if (! $campus) {
                continue;
            }

            // Get or create a college for this campus
            $campusCollege = College::where('campus_id', $campus->id)->first();
            if (! $campusCollege) {
                $campusCollege = $college; // fallback to CAS
            }

            $user = User::create([
                'name' => $officer['name'],
                'employee_id' => $officer['employee_id'],
                'password' => Hash::make('password'),
                'role' => $officer['role'],
                'campus_id' => $campus->id,
            ]);

            FacultyProfile::create([
                'user_id' => $user->id,
                'campus_id' => $campus->id,
                'college_id' => $campusCollege->id,
                'academic_rank_id' => $professorRank->id,
                'employee_id' => $officer['employee_id'],
                'surname' => $officer['surname'],
                'first_name' => $officer['first_name'],
                'middle_name' => $officer['middle_name'] ?: 'M.',
                'extension_name' => null,
                'civil_status' => 'married',
                'gender' => $officer['gender'],
                'birth_date' => '1970-'.str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT).'-'.str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT),
                'birth_place' => $campus->name.', Cagayan',
                'home_address' => $campus->name.', Cagayan',
                'phone_number' => '0917'.rand(1000000, 9999999),
                'id_picture' => null,
                'date_of_service' => '2010-06-01',
                'membership_status' => 'active',
                'membership_date' => '2010-06-01',
                'officer_position' => $officer['position'],
            ]);
        }

        // Create a sample regular faculty member
        $faculty = User::create([
            'name' => 'Juan Dela Cruz',
            'employee_id' => 'FAC-001',
            'password' => Hash::make('password'),
            'role' => 'faculty',
        ]);

        $instructorRank = AcademicRank::where('code', 'INST1')->first();

        if ($carigCampus && $college && $instructorRank) {
            FacultyProfile::create([
                'user_id' => $faculty->id,
                'campus_id' => $carigCampus->id,
                'college_id' => $college->id,
                'academic_rank_id' => $instructorRank->id,
                'employee_id' => 'FAC-001',
                'surname' => 'Dela Cruz',
                'first_name' => 'Juan',
                'middle_name' => 'Santos',
                'extension_name' => null,
                'civil_status' => 'married',
                'gender' => 'male',
                'birth_date' => '1990-05-15',
                'birth_place' => 'Tuguegarao City, Cagayan',
                'home_address' => '123 Faculty Housing, Tuguegarao City',
                'phone_number' => '09181234567',
                'id_picture' => null,
                'date_of_service' => '2022-06-01',
                'membership_status' => 'active',
                'membership_date' => '2022-06-01',
            ]);
        }
    }
}
