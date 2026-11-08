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
        Schema::table('claims', function (Blueprint $table) {
            $table->decimal('deduction_amount', 12, 2)->default(0)->after('amount');
            $table->decimal('net_amount', 12, 2)->nullable()->after('deduction_amount');
            $table->string('deduction_type')->nullable()->after('net_amount');
            $table->decimal('deduction_value', 10, 2)->nullable()->after('deduction_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['deduction_amount', 'net_amount', 'deduction_type', 'deduction_value']);
        });
    }
};
