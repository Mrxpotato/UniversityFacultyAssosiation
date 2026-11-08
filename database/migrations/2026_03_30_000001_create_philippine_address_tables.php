<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ph_provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('region_name')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('region_name');
        });

        Schema::create('ph_cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('province_id')->constrained('ph_provinces')->onDelete('cascade');
            $table->string('zip_code')->nullable();
            $table->timestamps();

            $table->index('name');
            $table->index('province_id');
        });

        Schema::create('ph_barangays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('city_id')->constrained('ph_cities')->onDelete('cascade');
            $table->timestamps();

            $table->index('name');
            $table->index('city_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ph_barangays');
        Schema::dropIfExists('ph_cities');
        Schema::dropIfExists('ph_provinces');
    }
};
