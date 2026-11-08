<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')->nullable()->unique()->after('name');
        });

        // Populate employee_id for existing users from their faculty profiles
        DB::statement('
            UPDATE users 
            SET employee_id = (
                SELECT faculty_profiles.employee_id 
                FROM faculty_profiles 
                WHERE faculty_profiles.user_id = users.id 
                LIMIT 1
            )
            WHERE EXISTS (
                SELECT 1 FROM faculty_profiles WHERE faculty_profiles.user_id = users.id AND faculty_profiles.employee_id IS NOT NULL
            )
        ');

        // For any users without a faculty profile employee_id, generate a dummy one
        $usersWithoutEmployeeId = DB::table('users')->whereNull('employee_id')->get();
        foreach ($usersWithoutEmployeeId as $user) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['employee_id' => 'EMP-'.str_pad($user->id, 5, '0', STR_PAD_LEFT)]);
        }

        // Now make it non-nullable
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_id')->nullable(false)->change();
        });

        // Make email nullable since we no longer use it for auth
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
    }
};
