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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('activity_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('certificate_type'); // participant, collaborator, instructor, organizer, partner
            $table->decimal('total_hours', 8, 2)->nullable();
            $table->string('validation_code', 32)->unique();
            $table->string('pdf_path')->nullable();
            $table->json('certificate_data')->nullable(); // Store calculated data for regeneration
            $table->timestamp('issued_at');
            $table->timestamps();

            $table->index(['user_id', 'activity_id']);
            $table->index('validation_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
