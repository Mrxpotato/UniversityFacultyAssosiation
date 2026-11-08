<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default processes setting
        DB::table('system_settings')->insert([
            'key' => 'processes_enabled',
            'value' => 'true',
            'type' => 'boolean',
            'group' => 'processes',
            'description' => 'Enable or disable system processes (claims, beneficiaries, etc.)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('system_settings')->where('key', 'processes_enabled')->delete();
    }
};
