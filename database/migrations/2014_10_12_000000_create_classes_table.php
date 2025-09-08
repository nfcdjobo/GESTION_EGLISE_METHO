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
        Schema::create('classes', function (Blueprint $table) {
            // Identifiant unique UUID
            $table->uuid('id')->primary();

            // Informations de base
            $table->string('nom')->unique();
            $table->text('description')->nullable();

            // Participants et âge
            $table->string('tranche_age')->nullable(); // Ex: "6-12 ans", "Adultes", etc.
            $table->integer('age_minimum')->nullable();
            $table->integer('age_maximum')->nullable();
            $table->integer('nombre_inscrits')->default(0);

            // Enseignants et responsables
            $table->uuid('responsable_id')->nullable();

            // $table->uuid('responsable2_id')->nullable();
            // $table->uuid('responsable3_id')->nullable();
            // $table->uuid('responsable4_id')->nullable();
            // $table->uuid('responsable5_id')->nullable();
            // $table->uuid('responsable6_id')->nullable();

            $table->uuid('enseignant_principal_id')->nullable(); // ← AJOUTÉ

            $table->json('programme')->nullable(); // Programme détaillé

            // Image et documents
            $table->string('image_classe')->nullable();

            // Timestamps et soft delete
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
