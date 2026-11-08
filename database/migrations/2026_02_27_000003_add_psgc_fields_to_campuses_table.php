<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds Philippine Standard Geographic Code (PSGC) fields to the campuses table.
     * PSGC is maintained by the Philippine Statistics Authority (PSA).
     * Format: Region (2 digits) + Province (2 digits) + City/Municipality (2 digits) + Barangay (3 digits)
     */
    public function up(): void
    {
        Schema::table('campuses', function (Blueprint $table) {
            // PSGC code fields
            $table->string('psgc_code', 10)->nullable()->after('code')->comment('Full 9-digit PSGC code');
            $table->string('region_code', 10)->nullable()->after('psgc_code');
            $table->string('province_code', 10)->nullable()->after('region_code');
            $table->string('city_municipality_code', 10)->nullable()->after('province_code');
            $table->string('barangay_code', 10)->nullable()->after('city_municipality_code');

            // Human-readable geographic fields
            $table->string('region')->nullable()->after('barangay_code');
            $table->string('province')->nullable()->after('region');
            $table->string('city_municipality')->nullable()->after('province');
            $table->string('barangay')->nullable()->after('city_municipality');

            // GPS coordinates
            $table->decimal('latitude', 10, 7)->nullable()->after('barangay');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campuses', function (Blueprint $table) {
            $table->dropColumn([
                'psgc_code',
                'region_code',
                'province_code',
                'city_municipality_code',
                'barangay_code',
                'region',
                'province',
                'city_municipality',
                'barangay',
                'latitude',
                'longitude',
            ]);
        });
    }
};
