<?php

namespace Database\Seeders;

use App\Models\AcademicRank;
use Illuminate\Database\Seeder;

class AcademicRankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ranks = [
            // Instructor ranks (SG 12-14)
            ['name' => 'Instructor I', 'code' => 'INST1', 'level' => 1, 'salary_grade' => 12, 'monthly_dues' => 100.00],
            ['name' => 'Instructor II', 'code' => 'INST2', 'level' => 2, 'salary_grade' => 13, 'monthly_dues' => 100.00],
            ['name' => 'Instructor III', 'code' => 'INST3', 'level' => 3, 'salary_grade' => 14, 'monthly_dues' => 100.00],

            // Assistant Professor ranks (SG 15-18)
            ['name' => 'Assistant Professor I', 'code' => 'AP1', 'level' => 4, 'salary_grade' => 15, 'monthly_dues' => 150.00],
            ['name' => 'Assistant Professor II', 'code' => 'AP2', 'level' => 5, 'salary_grade' => 16, 'monthly_dues' => 150.00],
            ['name' => 'Assistant Professor III', 'code' => 'AP3', 'level' => 6, 'salary_grade' => 17, 'monthly_dues' => 150.00],
            ['name' => 'Assistant Professor IV', 'code' => 'AP4', 'level' => 7, 'salary_grade' => 18, 'monthly_dues' => 150.00],

            // Associate Professor ranks (SG 19-23)
            ['name' => 'Associate Professor I', 'code' => 'ASSOC1', 'level' => 8, 'salary_grade' => 19, 'monthly_dues' => 200.00],
            ['name' => 'Associate Professor II', 'code' => 'ASSOC2', 'level' => 9, 'salary_grade' => 20, 'monthly_dues' => 200.00],
            ['name' => 'Associate Professor III', 'code' => 'ASSOC3', 'level' => 10, 'salary_grade' => 21, 'monthly_dues' => 200.00],
            ['name' => 'Associate Professor IV', 'code' => 'ASSOC4', 'level' => 11, 'salary_grade' => 22, 'monthly_dues' => 200.00],
            ['name' => 'Associate Professor V', 'code' => 'ASSOC5', 'level' => 12, 'salary_grade' => 23, 'monthly_dues' => 200.00],

            // Professor ranks (SG 24-29)
            ['name' => 'Professor I', 'code' => 'PROF1', 'level' => 13, 'salary_grade' => 24, 'monthly_dues' => 250.00],
            ['name' => 'Professor II', 'code' => 'PROF2', 'level' => 14, 'salary_grade' => 25, 'monthly_dues' => 250.00],
            ['name' => 'Professor III', 'code' => 'PROF3', 'level' => 15, 'salary_grade' => 26, 'monthly_dues' => 250.00],
            ['name' => 'Professor IV', 'code' => 'PROF4', 'level' => 16, 'salary_grade' => 27, 'monthly_dues' => 250.00],
            ['name' => 'Professor V', 'code' => 'PROF5', 'level' => 17, 'salary_grade' => 28, 'monthly_dues' => 250.00],
            ['name' => 'Professor VI', 'code' => 'PROF6', 'level' => 18, 'salary_grade' => 29, 'monthly_dues' => 250.00],

            // University Professor (SG 30)
            ['name' => 'University Professor', 'code' => 'UP', 'level' => 19, 'salary_grade' => 30, 'monthly_dues' => 300.00],
        ];

        foreach ($ranks as $rank) {
            AcademicRank::updateOrCreate(
                ['code' => $rank['code']],
                $rank
            );
        }
    }
}
