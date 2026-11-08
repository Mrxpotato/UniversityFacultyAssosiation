<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // For SQLite: The role column is stored as text, so enum values are
        // enforced at the application level. This migration documents the change.
        // For MySQL: Use ALTER TABLE to modify the enum.
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'faculty', 'president', 'treasurer') DEFAULT 'faculty'");
        }
        // For SQLite, the column is already a text type — new values ('president', 'treasurer')
        // are validated in the User model and form requests. No schema change needed.
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'faculty') DEFAULT 'faculty'");
        }
    }
};
