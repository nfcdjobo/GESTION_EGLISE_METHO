<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('session_token')->unique();
            // Créer temporairement comme string, on va le modifier après
            $table->string('ip_address_temp')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->softDeletes(); // Pour le soft delete

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'is_active', 'expires_at']);
        });

        // Modifier le type de colonne en INET avec SQL brut
        DB::statement('ALTER TABLE user_sessions DROP COLUMN ip_address_temp');
        DB::statement('ALTER TABLE user_sessions ADD COLUMN ip_address INET');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
