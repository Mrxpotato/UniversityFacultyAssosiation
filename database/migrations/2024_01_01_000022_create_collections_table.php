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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique(); // Auto-generated: COL-2026-JAN
            $table->string('title', 200);
            $table->text('content'); // Executive summary
            $table->string('period_covered'); // e.g., "January 2026", "Q1 2026", "Year 2025"
            $table->decimal('total_collected', 12, 2);
            $table->json('breakdown_data')->nullable(); // Detailed breakdown
            $table->enum('report_type', ['Monthly', 'Quarterly', 'Annual']);
            $table->json('attachments')->nullable();
            $table->enum('status', ['Draft', 'Published', 'Archived'])->default('Draft');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
