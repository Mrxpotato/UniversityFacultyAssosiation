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
        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->string('memo_number')->unique(); // Auto-generated: 2026-001
            $table->string('title', 200);
            $table->text('content');
            $table->enum('category', ['Administrative', 'Financial', 'Benefits', 'Policy', 'Urgent']);
            $table->enum('issued_by', ['President', 'Secretary', 'Board of Directors']);
            $table->date('effective_date');
            $table->date('expiration_date')->nullable();
            $table->boolean('requires_acknowledgment')->default(false);
            $table->enum('priority', ['Normal', 'High', 'Urgent'])->default('Normal');
            $table->json('attachments')->nullable();
            $table->enum('status', ['Draft', 'Published', 'Archived'])->default('Draft');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Memo acknowledgments table
        Schema::create('memo_acknowledgments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('acknowledged_at');
            $table->timestamps();
            $table->unique(['memo_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo_acknowledgments');
        Schema::dropIfExists('memos');
    }
};
