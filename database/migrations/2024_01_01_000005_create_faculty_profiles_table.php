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
        Schema::create('faculty_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Personal Information
            $table->string('surname');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable(); // Jr., Sr., III, etc.
            $table->enum('civil_status', ['single', 'married', 'widowed', 'separated'])->default('single');
            $table->enum('gender', ['male', 'female', 'lgbtqia+'])->default('male');
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->text('home_address')->nullable();
            $table->string('id_picture')->nullable();
            $table->string('phone_number')->nullable();

            // Professional Information
            $table->string('employee_id')->unique();
            $table->date('date_of_service')->nullable();
            $table->foreignId('academic_rank_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('college_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('campus_id')->constrained()->onDelete('cascade');

            // Membership Status
            $table->enum('membership_status', ['active', 'inactive', 'retired', 'deceased'])->default('active');
            $table->date('membership_date')->nullable();

            // Additional fields
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
        Schema::dropIfExists('faculty_profiles');
    }
};
