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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');

            // ✅ REMPLACER morphs() par des colonnes manuelles avec UUID
            $table->uuid('notifiable_id');      // Au lieu de unsignedBigInteger
            $table->string('notifiable_type');  // Reste identique

            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Index pour les performances
            $table->index(['notifiable_id', 'notifiable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
