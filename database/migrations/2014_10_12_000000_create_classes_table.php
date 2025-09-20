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

            // Responsables: délégué, enseignant principal, enseignant secondaire, sous-délégué, etc...
            $table->json('responsables')->nullable(); /**Ici c'est du json avec les champs suivants: id, superieur et responsabilite. L'id vient de la table user, superieur est un booleen (un seul responsable peut être le superieur parmi les responsables et seul lui peut attribuer ou rétirer la responsabitité aux autres membres de la classe) et responsabitité peut être principal, enseignant, ...*/


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
