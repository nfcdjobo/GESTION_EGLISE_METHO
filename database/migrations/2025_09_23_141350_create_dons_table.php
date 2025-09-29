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
        Schema::create('dons', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('parametre_fond_id')->nullable(); // Rendu nullable pour la contrainte
            $table->foreign('parametre_fond_id')->references('id')->on('parametres_dons')->onDelete('set null');

            $table->string('nom_donateur', 100);
            $table->string('prenom_donateur', 100);
            $table->string('telephone_1', 20);
            $table->string('telephone_2', 20)->nullable();
            $table->decimal('montant', 15, 2); // Précision ajoutée pour les décimales
            $table->string('devise', 10); // Limitation appropriée pour les codes devise
            $table->string('preuve', 255)->comment('Chemin vers le fichier de preuve');

            // Note: J'ai supprimé les colonnes dupliquées 'nom_donateur'
            // En gardant seulement la première déclaration

            $table->timestamps();
            $table->softDeletes();

            // Index pour améliorer les performances
            $table->index('parametre_fond_id');
            $table->index(['nom_donateur', 'prenom_donateur']);
            $table->index('montant');
            $table->index('devise');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dons');
    }
};
