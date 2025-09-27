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
            $table->enum('theme', ['light', 'dark'])->default('light');
            $table->string('homepage_title')->default('Bem-vindo ao GESTEC');
            $table->string('homepage_subtitle')->nullable();
            $table->text('homepage_description')->nullable();
            $table->json('homepage_features')->nullable();
            $table->string('homepage_cta_text')->default('Começar Agora');
            $table->string('homepage_cta_link')->default('/dashboard');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->dropColumn([
                'theme',
                'homepage_title',
                'homepage_subtitle',
                'homepage_description',
                'homepage_features',
                'homepage_cta_text',
                'homepage_cta_link'
            ]);
        });
    }
};
