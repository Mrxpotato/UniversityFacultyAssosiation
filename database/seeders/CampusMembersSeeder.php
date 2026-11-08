<?php

namespace Database\Seeders;

use App\Models\AcademicRank;
use App\Models\Campus;
use App\Models\College;
use App\Models\FacultyProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CampusMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates campus-level officers and members for each campus.
     * The 9 University-level officers are also Campus Presidents in their respective campuses.
     */
    public function run(): void
    {
        // Update existing University officers to also be Campus Presidents
        $universityOfficers = [
            'PIAT' => 'Dr. Ricardo Casaway',
            'APARRI' => 'Dr. Billy Javier',
            'LASAM' => 'Dr. Jehan Gerardo',
            'ANDREWS' => 'Dr. Jaypee Alegado',
            'GONZAGA' => 'Dr. Bert Bermudez',
            'SOLANA' => 'Mr. Stanley Follante',
            'LALLO' => 'Dr. Domingo Dela Vega',
            'SANCHEZ' => 'Dr. Freddic Masuli',
        ];

        foreach ($universityOfficers as $campusCode => $officerName) {
            $campus = Campus::where('code', $campusCode)->first();
            if ($campus) {
                FacultyProfile::where('campus_id', $campus->id)
                    ->whereNotNull('officer_position')
                    ->update(['campus_position' => 'Campus President']);
            }
        }

        // Get academic ranks
        $professorRank = AcademicRank::where('code', 'PROF1')->first();
        $assocProfRank = AcademicRank::where('code', 'APROF')->first();
        $asstProfRank = AcademicRank::where('code', 'ASTPR')->first();
        $instructorRank = AcademicRank::where('code', 'INST1')->first();

        // Campus members data - each campus will have officers and regular members
        $campusMembers = [
            'PIAT' => [
                'officers' => [
                    ['name' => 'Maria Santos', 'position' => 'Campus Vice President', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Pedro Reyes', 'position' => 'Campus Secretary', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Elena Fernandez', 'position' => 'Campus Treasurer', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Roberto Cruz', 'position' => 'Campus Auditor', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Ana Gonzales', 'position' => 'Campus PIO', 'gender' => 'female', 'rank' => 'INST1'],
                ],
                'members' => [
                    ['name' => 'Carlos Magno', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Teresa Villanueva', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Miguel Ramos', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Lucia Mendoza', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Antonio Pascual', 'gender' => 'male', 'rank' => 'APROF'],
                ],
            ],
            'APARRI' => [
                'officers' => [
                    ['name' => 'Rosario Agustin', 'position' => 'Campus Vice President', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Francisco Bautista', 'position' => 'Campus Secretary', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Carmela Dizon', 'position' => 'Campus Treasurer', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Andres Lacson', 'position' => 'Campus Auditor', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Patricia Navarro', 'position' => 'Campus PIO', 'gender' => 'female', 'rank' => 'INST1'],
                ],
                'members' => [
                    ['name' => 'Emmanuel Soriano', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Gloria Magat', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Ramon Taguba', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Josephine Aquino', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Ricardo Baguio', 'gender' => 'male', 'rank' => 'APROF'],
                    ['name' => 'Nelia Cariño', 'gender' => 'female', 'rank' => 'INST1'],
                ],
            ],
            'CARIG' => [
                'officers' => [
                    ['name' => 'Dr. Benjamin Torres', 'position' => 'Campus President', 'gender' => 'male', 'rank' => 'PROF1'],
                    ['name' => 'Amelia Castillo', 'position' => 'Campus Vice President', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Jose Manalo', 'position' => 'Campus Secretary', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Cristina Villar', 'position' => 'Campus Treasurer', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Gabriel Luna', 'position' => 'Campus Auditor', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Marissa Abad', 'position' => 'Campus PIO', 'gender' => 'female', 'rank' => 'INST1'],
                ],
                'members' => [
                    ['name' => 'Daniel Espiritu', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Remedios Valdez', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Ernesto Pagaduan', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Lourdes Ventura', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Rodrigo Batac', 'gender' => 'male', 'rank' => 'APROF'],
                    ['name' => 'Victoria Macaraeg', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Alfredo Dacumos', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Corazon Sabulao', 'gender' => 'female', 'rank' => 'ASTPR'],
                ],
            ],
            'GONZAGA' => [
                'officers' => [
                    ['name' => 'Carmen Velasco', 'position' => 'Campus Vice President', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Reynaldo Daquioag', 'position' => 'Campus Secretary', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Felicidad Marcos', 'position' => 'Campus Treasurer', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Lorenzo Aggabao', 'position' => 'Campus Auditor', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Dolores Pascua', 'position' => 'Campus PIO', 'gender' => 'female', 'rank' => 'INST1'],
                ],
                'members' => [
                    ['name' => 'Herminio Calata', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Teresita Gatan', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Jaime Tumaliuan', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Erlinda Baggao', 'gender' => 'female', 'rank' => 'INST1'],
                ],
            ],
            'LALLO' => [
                'officers' => [
                    ['name' => 'Rosalinda Pagulayan', 'position' => 'Campus Vice President', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Mariano Ramos', 'position' => 'Campus Secretary', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Imelda Guzman', 'position' => 'Campus Treasurer', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Danilo Sabado', 'position' => 'Campus Auditor', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Evangeline Mabborang', 'position' => 'Campus PIO', 'gender' => 'female', 'rank' => 'INST1'],
                ],
                'members' => [
                    ['name' => 'Fernando Cabugao', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Josefina Adduro', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Virgilio Cabanatan', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Zenaida Pattugalan', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Arturo Maguddatu', 'gender' => 'male', 'rank' => 'APROF'],
                ],
            ],
            'LASAM' => [
                'officers' => [
                    ['name' => 'Leticia Bangayan', 'position' => 'Campus Vice President', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Alfredo Cuntapay', 'position' => 'Campus Secretary', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Norma Taggueg', 'position' => 'Campus Treasurer', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Gerardo Balangue', 'position' => 'Campus Auditor', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Milagros Cabaddu', 'position' => 'Campus PIO', 'gender' => 'female', 'rank' => 'INST1'],
                ],
                'members' => [
                    ['name' => 'Renato Tallud', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Susana Cammayo', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Conrado Mabborang', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Beatriz Saludaga', 'gender' => 'female', 'rank' => 'INST1'],
                ],
            ],
            'SANCHEZ' => [
                'officers' => [
                    ['name' => 'Soledad Bangug', 'position' => 'Campus Vice President', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Enrique Gammad', 'position' => 'Campus Secretary', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Purita Lappay', 'position' => 'Campus Treasurer', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Bernardo Pasadilla', 'position' => 'Campus Auditor', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Remedios Cagurangan', 'position' => 'Campus PIO', 'gender' => 'female', 'rank' => 'INST1'],
                ],
                'members' => [
                    ['name' => 'Leopoldo Tumamao', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Adoracion Baccay', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Simeon Tangonan', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Filomena Pacris', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Romulo Cabulisan', 'gender' => 'male', 'rank' => 'APROF'],
                ],
            ],
            'SOLANA' => [
                'officers' => [
                    ['name' => 'Estrella Balisi', 'position' => 'Campus Vice President', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Napoleon Baquiran', 'position' => 'Campus Secretary', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Concepcion Garat', 'position' => 'Campus Treasurer', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Crisanto Dumlao', 'position' => 'Campus Auditor', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Adoracion Palaganas', 'position' => 'Campus PIO', 'gender' => 'female', 'rank' => 'INST1'],
                ],
                'members' => [
                    ['name' => 'Gregorio Sibbaluca', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Olivia Mabborang', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Aurelio Mallillin', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Presentacion Cabading', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Demetrio Guzman', 'gender' => 'male', 'rank' => 'APROF'],
                    ['name' => 'Teofila Bumanglag', 'gender' => 'female', 'rank' => 'INST1'],
                ],
            ],
            'ANDREWS' => [
                'officers' => [
                    ['name' => 'Lucinda Bacani', 'position' => 'Campus Vice President', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Marcelo Paddayuman', 'position' => 'Campus Secretary', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Estelita Guiyab', 'position' => 'Campus Treasurer', 'gender' => 'female', 'rank' => 'APROF'],
                    ['name' => 'Isidro Baquiran', 'position' => 'Campus Auditor', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Leonora Cabading', 'position' => 'Campus PIO', 'gender' => 'female', 'rank' => 'INST1'],
                ],
                'members' => [
                    ['name' => 'Feliciano Tagudina', 'gender' => 'male', 'rank' => 'INST1'],
                    ['name' => 'Rosita Bumanglag', 'gender' => 'female', 'rank' => 'INST1'],
                    ['name' => 'Valeriano Paggao', 'gender' => 'male', 'rank' => 'ASTPR'],
                    ['name' => 'Catalina Pasadilla', 'gender' => 'female', 'rank' => 'INST1'],
                ],
            ],
        ];

        // Academic rank mapping
        $rankMap = [
            'PROF1' => $professorRank,
            'APROF' => $assocProfRank,
            'ASTPR' => $asstProfRank,
            'INST1' => $instructorRank,
        ];

        $counter = 100;

        foreach ($campusMembers as $campusCode => $data) {
            $campus = Campus::where('code', $campusCode)->first();
            if (! $campus) {
                continue;
            }

            // Get a college for this campus
            $college = College::where('campus_id', $campus->id)->first();
            if (! $college) {
                $college = College::first(); // fallback
            }

            // Create officers
            foreach ($data['officers'] as $officer) {
                $counter++;
                $rank = $rankMap[$officer['rank']] ?? $instructorRank;

                // Parse name
                $nameParts = explode(' ', $officer['name']);
                $firstName = $nameParts[0];
                if (str_starts_with($firstName, 'Dr.') || str_starts_with($firstName, 'Mr.') || str_starts_with($firstName, 'Ms.')) {
                    $firstName = $nameParts[1] ?? $firstName;
                    $surname = end($nameParts);
                    $middleName = count($nameParts) > 3 ? $nameParts[2] : '';
                } else {
                    $surname = end($nameParts);
                    $middleName = count($nameParts) > 2 ? $nameParts[1] : '';
                }

                $email = strtolower(str_replace(' ', '.', $officer['name'])).'@csu.edu.ph';
                $email = preg_replace('/dr\.|mr\.|ms\./i', '', $email);
                $email = preg_replace('/\.\.+/', '.', $email);
                $email = ltrim($email, '.');

                $user = User::create([
                    'name' => $officer['name'],
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'role' => 'faculty',
                ]);

                FacultyProfile::create([
                    'user_id' => $user->id,
                    'campus_id' => $campus->id,
                    'college_id' => $college->id,
                    'academic_rank_id' => $rank->id,
                    'employee_id' => 'FAC-'.$campusCode.'-'.$counter,
                    'surname' => $surname,
                    'first_name' => $firstName,
                    'middle_name' => $middleName ?: 'M.',
                    'extension_name' => null,
                    'civil_status' => 'married',
                    'gender' => $officer['gender'],
                    'birth_date' => '1975-'.str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT).'-'.str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT),
                    'birth_place' => $campus->name.', Cagayan',
                    'home_address' => $campus->name.', Cagayan',
                    'phone_number' => '0917'.rand(1000000, 9999999),
                    'id_picture' => null,
                    'date_of_service' => '2012-06-01',
                    'membership_status' => 'active',
                    'membership_date' => '2012-06-01',
                    'campus_position' => $officer['position'],
                ]);
            }

            // Create regular members
            foreach ($data['members'] as $member) {
                $counter++;
                $rank = $rankMap[$member['rank']] ?? $instructorRank;

                // Parse name
                $nameParts = explode(' ', $member['name']);
                $firstName = $nameParts[0];
                $surname = end($nameParts);
                $middleName = count($nameParts) > 2 ? $nameParts[1] : '';

                $email = strtolower(str_replace(' ', '.', $member['name'])).'@csu.edu.ph';

                $user = User::create([
                    'name' => $member['name'],
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'role' => 'faculty',
                ]);

                FacultyProfile::create([
                    'user_id' => $user->id,
                    'campus_id' => $campus->id,
                    'college_id' => $college->id,
                    'academic_rank_id' => $rank->id,
                    'employee_id' => 'FAC-'.$campusCode.'-'.$counter,
                    'surname' => $surname,
                    'first_name' => $firstName,
                    'middle_name' => $middleName ?: 'M.',
                    'extension_name' => null,
                    'civil_status' => rand(0, 1) ? 'married' : 'single',
                    'gender' => $member['gender'],
                    'birth_date' => rand(1970, 1995).'-'.str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT).'-'.str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT),
                    'birth_place' => $campus->name.', Cagayan',
                    'home_address' => $campus->name.', Cagayan',
                    'phone_number' => '0917'.rand(1000000, 9999999),
                    'id_picture' => null,
                    'date_of_service' => rand(2010, 2022).'-06-01',
                    'membership_status' => 'active',
                    'membership_date' => rand(2010, 2022).'-06-01',
                ]);
            }
        }
    }
}
