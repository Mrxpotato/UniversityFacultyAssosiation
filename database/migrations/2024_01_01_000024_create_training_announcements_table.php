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
        Schema::create('training_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('training_number')->unique(); // Auto-generated: TRN-2026-001
            $table->string('title', 200);
            $table->text('content'); // Full description
            $table->enum('training_type', ['Workshop', 'Seminar', 'Conference', 'Webinar', 'Training Course']);
            $table->string('speaker_facilitator');
            $table->text('speaker_bio')->nullable();
            $table->datetime('date_start');
            $table->datetime('date_end');
            $table->string('venue');
            $table->enum('mode', ['Face-to-face', 'Online', 'Hybrid']);
            $table->integer('max_participants')->nullable();
            $table->date('registration_deadline')->nullable();
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->string('target_participants')->nullable();
            $table->text('learning_objectives')->nullable();
            $table->text('requirements')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_details')->nullable();
            $table->json('attachments')->nullable();
            $table->enum('status', ['Draft', 'Open for Registration', 'Closed', 'Ongoing', 'Completed', 'Cancelled'])->default('Draft');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('views_count')->default(0);
            $table->integer('registered_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_announcements');
    }
};
