<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('deduction_setting_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('claim_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('month', 7); // YYYY-MM
            $table->decimal('gross_salary', 12, 2);
            $table->string('deduction_type', 20); // percentage or fixed
            $table->decimal('deduction_value', 8, 2);
            $table->decimal('deduction_amount', 12, 2);
            $table->string('description');
            $table->string('status', 20)->default('pending'); // pending, collected
            $table->timestamp('collected_at')->nullable();
            $table->foreignId('collected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('generated_by')->constrained('users');
            $table->timestamps();

            $table->index(['month', 'status']);
            $table->index('faculty_profile_id');
            // Prevent duplicate entries for same member + setting + month
            $table->unique(['faculty_profile_id', 'deduction_setting_id', 'month'], 'monthly_ded_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_deductions');
    }
};
