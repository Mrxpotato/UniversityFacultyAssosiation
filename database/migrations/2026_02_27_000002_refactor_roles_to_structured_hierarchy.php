<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refactor the role system to support the university organizational hierarchy:
     *
     * - super_admin         : System administrator (full access)
     * - university_president : UFA President (approve/manage claims, all members)
     * - university_treasurer : UFA Treasurer (release claims, manage dues/reports)
     * - campus_president     : Campus president (manage members within their campus)
     * - faculty              : Regular faculty member
     *
     * Each of the 9 university officers doubles as a campus president for their campus.
     * University President & Treasurer have elevated university-wide permissions.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        // Update existing role values
        // 'admin' → 'super_admin'
        DB::table('users')->where('role', 'admin')->update(['role' => 'super_admin']);

        // For MySQL, update the enum definition
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('super_admin', 'university_president', 'university_treasurer', 'campus_president', 'faculty') NOT NULL DEFAULT 'faculty'");
        }
        // SQLite: role is stored as text, no schema change needed — validated at application level
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        // Revert role values
        DB::table('users')->where('role', 'super_admin')->update(['role' => 'admin']);
        DB::table('users')->where('role', 'university_president')->update(['role' => 'faculty']);
        DB::table('users')->where('role', 'university_treasurer')->update(['role' => 'faculty']);
        DB::table('users')->where('role', 'campus_president')->update(['role' => 'faculty']);

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'faculty') NOT NULL DEFAULT 'faculty'");
        }
    }
};
