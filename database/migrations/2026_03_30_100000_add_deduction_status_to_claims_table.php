<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('deduction_status', 20)->default('pending')->after('deduction_value');
            $table->timestamp('deduction_collected_at')->nullable()->after('deduction_status');
        });

        // Auto-mark released claims with deductions as collected
        DB::table('claims')
            ->where('deduction_amount', '>', 0)
            ->where('status', 'released')
            ->update([
                'deduction_status' => 'collected',
                'deduction_collected_at' => DB::raw('date_released'),
            ]);
    }

    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['deduction_status', 'deduction_collected_at']);
        });
    }
};
