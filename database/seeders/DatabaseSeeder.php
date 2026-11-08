<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CampusSeeder::class,
            AcademicRankSeeder::class,
            ClaimTypeSeeder::class,
            CollegeSeeder::class,
            AdminUserSeeder::class,
            TransparencyWallSeeder::class,
            BeneficiarySeeder::class,
            ClaimSeeder::class,
        ]);
    }
}
