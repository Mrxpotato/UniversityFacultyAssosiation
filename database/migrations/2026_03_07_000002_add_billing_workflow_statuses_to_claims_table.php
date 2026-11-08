<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds three new workflow statuses between 'approved' and 'released':
     *   billing         – UFA prepares list of claims & billing for collection
     *   collecting      – UFA monitors receipt of collected claims via Campus Cashiers
     *   check_prepared  – UFA prepares check for the recipient
     *
     * Also adds date-tracking columns for each new stage.
     */
    public function up(): void
    {
        // 1. Change ENUM → VARCHAR so we can add new statuses freely
        //    (MySQL ENUMs are painful to alter; VARCHAR is safer long-term)
        Schema::table('claims', function (Blueprint $table) {
            $table->string('status', 30)->default('pending')->change();
        });

        // 2. Add date-tracking columns for the new stages
        Schema::table('claims', function (Blueprint $table) {
            $table->date('date_billing')->nullable()->after('date_approved');
            $table->date('date_collecting')->nullable()->after('date_billing');
            $table->date('date_check_prepared')->nullable()->after('date_collecting');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['date_billing', 'date_collecting', 'date_check_prepared']);
        });

        // Revert any rows that have the new statuses back to 'approved'
        DB::table('claims')->whereIn('status', ['billing', 'collecting', 'check_prepared'])->update(['status' => 'approved']);

        Schema::table('claims', function (Blueprint $table) {
            $table->enum('status', [
                'pending', 'under_review', 'approved', 'rejected', 'released', 'remanded',
            ])->default('pending')->change();
        });
    }
};
