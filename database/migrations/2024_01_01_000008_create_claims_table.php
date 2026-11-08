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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number')->unique();
            $table->foreignId('faculty_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('claim_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('beneficiary_id')->nullable()->constrained()->onDelete('set null');

            // Claim Details
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->text('supporting_documents')->nullable(); // JSON array of file paths

            // Dates
            $table->date('date_applied');
            $table->date('date_approved')->nullable();
            $table->date('date_released')->nullable();

            // Status
            $table->enum('status', [
                'pending', 'under_review', 'approved', 'rejected', 'released', 'remanded',
            ])->default('pending');
            $table->boolean('is_remanded')->default(false);
            $table->text('remand_reason')->nullable();
            $table->text('rejection_reason')->nullable();

            // Processing
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('released_by')->nullable()->constrained('users')->onDelete('set null');

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
