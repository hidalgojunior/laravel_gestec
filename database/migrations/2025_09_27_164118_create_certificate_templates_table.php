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
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('template_image_path');
            $table->enum('type', ['global', 'activity_specific'])->default('global');
            $table->foreignId('activity_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_default')->default(false);
            $table->json('layout_config')->nullable(); // Store positioning data for text overlay
            $table->timestamps();

            $table->index(['type', 'activity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
