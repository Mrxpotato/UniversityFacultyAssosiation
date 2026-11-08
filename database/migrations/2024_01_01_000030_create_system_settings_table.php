<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, boolean, integer, json
            $table->string('group')->default('general'); // general, registration, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default registration setting
        DB::table('system_settings')->insert([
            'key' => 'registration_enabled',
            'value' => 'true',
            'type' => 'boolean',
            'group' => 'registration',
            'description' => 'Enable or disable public member registration',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
