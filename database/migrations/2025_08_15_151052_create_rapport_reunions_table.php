<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rapport_reunions', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Relation avec la réunion
            $table->uuid('reunion_id')->comment('Réunion concernée par ce rapport');

            // Informations de base du rapport
            $table->string('titre_rapport', 200)->comment('Titre du rapport');
            $table->enum('type_rapport', [
                'proces_verbal',
                'compte_rendu',
                'rapport_activite',
                'rapport_financier'
            ])->comment('Type de rapport');

            // Responsables
            $table->uuid('redacteur_id')->nullable()->comment('Rédacteur du rapport');
            $table->uuid('validateur_id')->nullable()->comment('Personne qui valide le rapport');
            $table->uuid('cree_par')->nullable()->comment('Personne qui valide le rapport');
            $table->uuid('modifie_par')->nullable()->comment('Personne qui valide le rapport');

            // Statut
            $table->enum('statut', [
                'brouillon',
                'en_revision',
                'valide',
                'publie'
            ])->default('brouillon')->comment('Statut du rapport');

            $table->timestamp('valide_le')->nullable()->comment('Date de validation');
            $table->timestamp('publie_le')->nullable()->comment('Date de publication');

            // Contenu principal
            $table->text('resume')->nullable()->comment('Résumé du rapport');
            $table->json('points_traites')->nullable()->comment('Points traités (JSON)');
            $table->text('decisions_prises')->nullable()->comment('Décisions prises');
            $table->text('actions_decidees')->nullable()->comment('Actions décidées');

            // Présences
            $table->json('presences')->nullable()->comment('Liste des présences (JSON)');
            $table->integer('nombre_presents')->default(0)->comment('Nombre de présents');

            // Aspects financiers (si applicable)
            $table->decimal('montant_collecte', 10, 2)->nullable()->comment('Montant collecté');

            // Suivi
            $table->json('actions_suivre')->nullable()->comment('Actions à suivre (JSON)');
            $table->text('recommandations')->nullable()->comment('Recommandations');

            // Évaluation simple
            $table->integer('note_satisfaction')->nullable()->comment('Note de satisfaction (1-5)');
            $table->text('commentaires')->nullable()->comment('Commentaires généraux');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('reunion_id')->references('id')->on('reunions')->onDelete('cascade');
            $table->foreign('redacteur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validateur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');

            // Index principaux
            $table->index(['reunion_id', 'type_rapport']);
            $table->index(['statut', 'valide_le']);
            $table->index('redacteur_id');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement('DROP VIEW IF EXISTS rapports_violations_integrite CASCADE');

        // Suppression des triggers
        DB::statement('DROP TRIGGER IF EXISTS trigger_rapport_workflow_dates ON rapport_reunions CASCADE');
        DB::statement('DROP FUNCTION IF EXISTS update_rapport_workflow_dates() CASCADE');

        // Suppression de la table (les contraintes CHECK sont supprimées automatiquement)
        Schema::dropIfExists('rapport_reunions');
    }
};
