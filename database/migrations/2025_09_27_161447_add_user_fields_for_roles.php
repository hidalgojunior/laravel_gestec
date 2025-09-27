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
        // Migration already applied - fields exist in database
        // This migration is kept for reference but does nothing
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['cpf']);
            $table->dropColumn(['role', 'full_name', 'cpf', 'whatsapp', 'additional_contacts', 'person_type', 'signature_image']);
        });
    }
};
