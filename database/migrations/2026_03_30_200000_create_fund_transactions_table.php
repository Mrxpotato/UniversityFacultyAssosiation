<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fund_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 30)->unique();
            $table->string('type', 10); // inflow, outflow
            $table->string('category', 50); // deduction_collected, claim_released, manual_adjustment, donation, other
            $table->decimal('amount', 12, 2);
            $table->string('description');
            $table->string('notes')->nullable();

            $table->nullableMorphs('transactable');

            $table->foreignId('recorded_by')->constrained('users');
            $table->timestamp('transacted_at');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['type', 'category']);
            $table->index('transacted_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_transactions');
    }
};
