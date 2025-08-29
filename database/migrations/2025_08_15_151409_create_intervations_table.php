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
        Schema::create('interventions', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Relations (au moins une des deux requise)
            $table->uuid('culte_id')->nullable()->comment('Culte concerné');
            $table->uuid('reunion_id')->nullable()->comment('Réunion concernée');
            $table->uuid('intervenant_id')->comment('Personne qui intervient');

            // Informations essentielles
            $table->string('titre', 200)->comment('Titre de l\'intervention');
            $table->enum('type_intervention', [
                'predication',
                'temoignage',
                'priere',
                'louange',
                'lecture',
                'annonce',
                'offrande',
                'accueil',
                'benediction',
                'presentation',
                'animation',
                'autre'
            ])->comment('Type d\'intervention');

            // Timing simplifié
            $table->time('heure_debut')->nullable()->comment('Heure de début');
            $table->integer('duree_minutes')->default(15)->comment('Durée en minutes');
            $table->integer('ordre_passage')->nullable()->comment('Ordre dans le programme');

            // Contenu minimal
            $table->text('description')->nullable()->comment('Description de l\'intervention');
            $table->string('passage_biblique', 300)->nullable()->comment('Passage biblique de référence');

            // Statut simplifié
            $table->enum('statut', [
                'prevue',
                'terminee',
                'annulee'
            ])->default('prevue')->comment('Statut de l\'intervention');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key
            $table->foreign('reunion_id')->references('id')->on('reunions')->onDelete('cascade');
            $table->foreign('intervenant_id')->references('id')->on('users')->onDelete('cascade');

            // Index essentiels uniquement
            $table->index(['culte_id', 'ordre_passage']);
            $table->index(['reunion_id', 'ordre_passage']);
            $table->index(['intervenant_id', 'type_intervention']);
            $table->index('statut');
        });

        // Contrainte : au moins culte_id ou reunion_id doit être fourni
        DB::statement("
            ALTER TABLE interventions
            ADD CONSTRAINT check_interventions_evenement
            CHECK (culte_id IS NOT NULL OR reunion_id IS NOT NULL)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
