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
        Schema::table('users', function (Blueprint $table) {
            $table->string('pin', 4)->nullable()->unique();
            $table->boolean('is_disqualified')->default(false);
            $table->text('disqualification_reason')->nullable();
            $table->timestamp('disqualified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pin', 'is_disqualified', 'disqualification_reason', 'disqualified_at']);
        });
    }
};
