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
        Schema::table('global_settings', function (Blueprint $table) {
            $table->string('certificate_director_signature')->nullable();
            $table->string('certificate_coordinator_signature')->nullable();
            $table->text('certificate_text_template')->nullable();
            $table->string('validation_base_url')->default('https://pitchdev.com.br/validacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_director_signature',
                'certificate_coordinator_signature',
                'certificate_text_template',
                'validation_base_url'
            ]);
        });
    }
};
