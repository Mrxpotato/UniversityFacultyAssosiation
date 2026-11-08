<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\College;
use Illuminate\Database\Seeder;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Carig campus (main campus)
        $carigCampus = Campus::where('code', 'CARIG')->first();

        if (! $carigCampus) {
            return;
        }

        $colleges = [
            [
                'campus_id' => $carigCampus->id,
                'name' => 'College of Arts and Sciences',
                'code' => 'CAS',
                'description' => 'Offering programs in humanities, natural sciences, and social sciences.',
                'is_active' => true,
            ],
            [
                'campus_id' => $carigCampus->id,
                'name' => 'College of Engineering',
                'code' => 'COE',
                'description' => 'Programs in civil, mechanical, electrical, and computer engineering.',
                'is_active' => true,
            ],
            [
                'campus_id' => $carigCampus->id,
                'name' => 'College of Business Administration',
                'code' => 'CBA',
                'description' => 'Business, management, accounting, and entrepreneurship programs.',
                'is_active' => true,
            ],
            [
                'campus_id' => $carigCampus->id,
                'name' => 'College of Education',
                'code' => 'COED',
                'description' => 'Teacher education and educational leadership programs.',
                'is_active' => true,
            ],
            [
                'campus_id' => $carigCampus->id,
                'name' => 'College of Information Technology',
                'code' => 'CIT',
                'description' => 'Computer science, information technology, and information systems.',
                'is_active' => true,
            ],
            [
                'campus_id' => $carigCampus->id,
                'name' => 'College of Nursing',
                'code' => 'CON',
                'description' => 'Nursing education and healthcare programs.',
                'is_active' => true,
            ],
            [
                'campus_id' => $carigCampus->id,
                'name' => 'College of Law',
                'code' => 'COL',
                'description' => 'Legal education and jurisprudence programs.',
                'is_active' => true,
            ],
            [
                'campus_id' => $carigCampus->id,
                'name' => 'Graduate School',
                'code' => 'GS',
                'description' => 'Masters and doctoral programs across various disciplines.',
                'is_active' => true,
            ],
        ];

        foreach ($colleges as $college) {
            College::create($college);
        }

        // Add colleges to other campuses
        $aparriCampus = Campus::where('code', 'APARRI')->first();
        if ($aparriCampus) {
            College::create([
                'campus_id' => $aparriCampus->id,
                'name' => 'College of Fisheries and Marine Sciences',
                'code' => 'CFMS',
                'description' => 'Fisheries and marine sciences programs at Aparri Campus.',
                'is_active' => true,
            ]);
        }

        $lalloCampus = Campus::where('code', 'LALLO')->first();
        if ($lalloCampus) {
            College::create([
                'campus_id' => $lalloCampus->id,
                'name' => 'College of Agriculture',
                'code' => 'COA',
                'description' => 'Agriculture programs at Lal-lo Campus.',
                'is_active' => true,
            ]);
        }

        $piatCampus = Campus::where('code', 'PIAT')->first();
        if ($piatCampus) {
            College::create([
                'campus_id' => $piatCampus->id,
                'name' => 'College of Teacher Education',
                'code' => 'CTE',
                'description' => 'Teacher education programs at Piat Campus.',
                'is_active' => true,
            ]);
        }
    }
}
