<?php

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
        Schema::create('programmes', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Informations de base
            $table->string('nom_programme', 200)->comment('Nom du programme');
            $table->text('description')->nullable()->comment('Description du programme');
            $table->string('code_programme', 50)->unique()->comment('Code unique du programme');

            // Type de programme
            $table->enum('type_programme', [
                'culte_regulier',
                'formation',
                'evangelisation',
                'jeunesse',
                'enfants',
                'femmes',
                'hommes',
                'conference',
                'special',
                'autre'
            ])->comment('Type de programme');

            // Périodicité
            $table->enum('frequence', [
                'quotidien',
                'hebdomadaire',
                'mensuel',
                'annuel',
                'ponctuel'
            ])->comment('Fréquence du programme');

            // Dates et horaires
            $table->date('date_debut')->nullable()->comment('Date de début du programme');
            $table->date('date_fin')->nullable()->comment('Date de fin du programme');
            $table->time('heure_debut')->nullable()->comment('Heure de début');
            $table->time('heure_fin')->nullable()->comment('Heure de fin');
            $table->json('jours_semaine')->nullable()->comment('Jours de la semaine [1,2,3,4,5,6,7]');

            // Lieu
            $table->string('lieu_principal', 200)->nullable()->comment('Lieu principal du programme');

            // Responsable
            $table->uuid('responsable_principal_id')->nullable()->comment('Responsable principal du programme');

            // Audience
            $table->enum('audience_cible', [
                'tous',
                'membres',
                'jeunes',
                'adultes',
                'enfants',
                'femmes',
                'hommes',
                'visiteurs'
            ])->default('tous')->comment('Audience ciblée');

            // Statut
            $table->enum('statut', [
                'planifie',
                'actif',
                'suspendu',
                'termine',
                'annule'
            ])->default('planifie')->comment('Statut du programme');

            // Notes
            $table->text('notes')->nullable()->comment('Notes supplémentaires');

            // Audit
            $table->uuid('cree_par')->nullable()->comment('Membres créateur');
            $table->uuid('modifie_par')->nullable()->comment('Dernier modificateur');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('responsable_principal_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');

            // Index
            $table->index(['type_programme', 'statut']);
            $table->index(['date_debut', 'date_fin']);
            $table->index('code_programme');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
