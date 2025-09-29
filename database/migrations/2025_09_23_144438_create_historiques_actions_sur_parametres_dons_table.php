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
        Schema::create('historiques_actions_sur_parametres_dons', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('parametre_don_id')->nullable(); // Rendu nullable pour la contrainte
            $table->foreign('parametre_don_id')->references('id')->on('parametres_dons')->onDelete('set null');

            $table->enum('action', ['ajout', 'mise_a_jour', 'suppression', 'publication'])
                  ->default('ajout')
                  ->comment('Type d\'action effectuée');

            $table->uuid('effectuer_par')->nullable(); // Rendu nullable pour la contrainte
            $table->foreign('effectuer_par')->references('id')->on('users')->onDelete('set null');

            $table->json('infos')->nullable()->comment('Les informations enregistrées lors de l\'action');

            $table->timestamps();

            // Index pour améliorer les performances
            $table->index('parametre_don_id');
            $table->index('action');
            $table->index('effectuer_par');
            $table->index('created_at');

            // Index composé pour les requêtes fréquentes
            $table->index(['parametre_don_id', 'action']);
            $table->index(['effectuer_par', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historiques_actions_sur_parametres_dons');
    }
};
