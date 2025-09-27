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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('workload_hours');
            $table->integer('total_spots');
            $table->boolean('has_cost')->default(false);
            $table->string('pix_key')->nullable();
            $table->string('proof_file_path')->nullable();
            $table->boolean('waiting_list_enabled')->default(true);
            $table->enum('type', ['palestra', 'minicurso', 'workshop', 'painel'])->default('palestra');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
