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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_profile_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->enum('relationship', [
                'mother', 'father', 'mother-in-law', 'father-in-law',
                'brother', 'sister', 'wife', 'husband', 'son', 'daughter', 'other',
            ]);
            $table->date('date_of_birth')->nullable();
            $table->string('pse')->nullable(); // Primary/Secondary Emergency
            $table->string('contact_number')->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
