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
        // Drop the old unique constraint on academic_rank_id alone
        Schema::table('deduction_settings', function (Blueprint $table) {
            $table->dropUnique(['academic_rank_id']);
        });

        Schema::table('deduction_settings', function (Blueprint $table) {
            $table->foreignId('claim_type_id')->nullable()->after('academic_rank_id')
                ->constrained()->onDelete('cascade');

            // New unique: one setting per rank + claim type combo (null claim_type_id = global default)
            $table->unique(['academic_rank_id', 'claim_type_id'], 'deduction_rank_claim_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deduction_settings', function (Blueprint $table) {
            $table->dropUnique('deduction_rank_claim_type_unique');
            $table->dropForeign(['claim_type_id']);
            $table->dropColumn('claim_type_id');
            $table->unique('academic_rank_id');
        });
    }
};
