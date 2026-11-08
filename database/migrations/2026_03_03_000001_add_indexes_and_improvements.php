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
        // Add indexes to claims table for frequently filtered columns
        Schema::table('claims', function (Blueprint $table) {
            $table->index(['faculty_profile_id', 'status']);
            $table->index(['status', 'date_applied']);
            $table->index('claim_type_id');
        });

        // Add indexes and unique constraint to dues table
        Schema::table('dues', function (Blueprint $table) {
            $table->index(['faculty_profile_id', 'month']);
            $table->index(['status', 'due_date']);
            $table->unique(['faculty_profile_id', 'month'], 'dues_profile_month_unique');
            $table->softDeletes();
        });

        // Add indexes to faculty_profiles table
        Schema::table('faculty_profiles', function (Blueprint $table) {
            $table->index(['campus_id', 'membership_status']);
            $table->index('academic_rank_id');
        });

        // Add index to pending_registrations
        Schema::table('pending_registrations', function (Blueprint $table) {
            $table->index(['campus_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropIndex(['faculty_profile_id', 'status']);
            $table->dropIndex(['status', 'date_applied']);
            $table->dropIndex(['claim_type_id']);
        });

        Schema::table('dues', function (Blueprint $table) {
            $table->dropIndex(['faculty_profile_id', 'month']);
            $table->dropIndex(['status', 'due_date']);
            $table->dropUnique('dues_profile_month_unique');
            $table->dropSoftDeletes();
        });

        Schema::table('faculty_profiles', function (Blueprint $table) {
            $table->dropIndex(['campus_id', 'membership_status']);
            $table->dropIndex(['academic_rank_id']);
        });

        Schema::table('pending_registrations', function (Blueprint $table) {
            $table->dropIndex(['campus_id', 'status']);
        });
    }
};
