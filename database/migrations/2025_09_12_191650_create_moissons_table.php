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
        // Activer l'extension UUID si pas déjà fait
        DB::statement('CREATE EXTENSION IF NOT EXISTS "uuid-ossp"');

        Schema::create('moissons', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'))->comment("ID généré automatiquement");

            $table->string('theme', 250)->comment("Le thème de la prédication");
            $table->date('date')->unique()->comment("La date de la célébration de la moisson");

            // Montants avec contraintes PostgreSQL
            $table->decimal('cible', 15, 2)->comment("Le montant cible: c'est un montant prévu.");
            $table->decimal('montant_solde', 15, 2)->default(0)->comment("Le montant soldé: c'est l'ensemble de tous les fonds collectés et ces fonds viennent des tables passages moissons, vente_moissons et engagement_moissons. Le montant total peut aller au-delà du cible, en dessous du cible ou égal au cible. Mise à jour automatique");
            $table->decimal('reste', 15, 2)->default(0)->comment("C'est l'ensemble des montants non soldés. Mise à jour automatique");
            $table->decimal('montant_supplementaire', 15, 2)->default(0)->comment("Le montant supplémentaire existe si cible inférieur au montant_total. Mise à jour automatique");

            // JSON avec validation PostgreSQL
            $table->jsonb('passages_bibliques')->nullable()->comment("Les passages bibliques en JSONB contenant le livre chapitre allant de x à y mais le y est optionnel");

            // Relations avec contraintes de clés étrangères
            $table->uuid('culte_id')->comment("Le culte");
            $table->uuid('creer_par')->comment("Celui qui fait l'enregistrement");

            // JSONB pour l'historique avec schéma validé
            $table->jsonb('editeurs')->nullable()->comment("Historique des modifications en JSONB avec l'identifiant de celui qui a effectué la mise à jour, date de mise à jour");

            $table->boolean('status')->default(false)->comment("Statut de la moisson");

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('culte_id')->references('id')->on('cultes')->onDelete('set null');
            $table->foreign('creer_par')->references('id')->on('users')->onDelete('set null');

            // Index PostgreSQL optimisés
            $table->index('date');
            $table->index('culte_id');
            $table->index('creer_par');
            $table->index('status');
        });

        // Ajouter les index GIN pour JSONB après création de la table
        DB::statement('CREATE INDEX idx_moissons_passages_bibliques_gin ON moissons USING gin (passages_bibliques)');
        DB::statement('CREATE INDEX idx_moissons_editeurs_gin ON moissons USING gin (editeurs)');

        // Index partiel pour les enregistrements actifs
        DB::statement('CREATE INDEX idx_moissons_active_by_date ON moissons (status, date) WHERE status = true AND deleted_at IS NULL');

        // Contraintes CHECK PostgreSQL - Version simplifiée
        DB::statement('ALTER TABLE moissons ADD CONSTRAINT chk_moissons_amounts_positive CHECK (cible >= 0 AND montant_solde >= 0 AND reste >= 0 AND montant_supplementaire >= 0)');
        DB::statement('ALTER TABLE moissons ADD CONSTRAINT chk_moissons_cible_positive CHECK (cible > 0)');

        // Les validations JSONB seront gérées au niveau de l'application (modèles Eloquent)
        // Cela évite les problèmes de syntaxe avec Laravel et reste plus flexible
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moissons');
    }
};
