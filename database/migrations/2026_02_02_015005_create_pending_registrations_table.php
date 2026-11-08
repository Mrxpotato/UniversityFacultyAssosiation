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
        Schema::create('pending_registrations', function (Blueprint $table) {
            $table->id();

            // Personal Information
            $table->string('prefix')->nullable();
            $table->string('surname');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->enum('civil_status', ['single', 'married', 'widowed', 'separated'])->default('single');
            $table->enum('gender', ['male', 'female', 'lgbtqia+'])->default('male');
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->text('home_address')->nullable();
            $table->string('phone_number')->nullable();

            // Campus & Employee Info
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');
            $table->string('employee_id_number');

            // File Uploads
            $table->string('id_picture')->nullable();
            $table->string('employee_id_front')->nullable();
            $table->string('employee_id_back')->nullable();

            // Account Credentials
            $table->string('email');
            $table->string('password');

            // Review Status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pending_registrations');
    }
};
