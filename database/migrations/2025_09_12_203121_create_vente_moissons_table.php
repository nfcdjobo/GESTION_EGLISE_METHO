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
        Schema::create('vente_moissons', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('moisson_id');

            // Utiliser Laravel's enum() method
            $table->enum('categorie', ['aliments', 'arbres_vie', 'americaine'])
                  ->comment("Type de vente pour la moisson");

            // Montants avec contraintes
            $table->decimal('cible', 15, 2)->comment("C'est l'objectif à atteindre mais on peut aller au-delà");
            $table->decimal('montant_solde', 15, 2)->default(0)->comment("L'ensemble des montants déjà collectés");
            $table->decimal('reste', 15, 2)->default(0)->comment("Reste à solder");
            $table->decimal('montant_supplementaire', 15, 2)->default(0)->comment("Existe lorsque montant_solde > cible");

            // Informations de collecte
            $table->uuid('collecter_par');
            $table->timestamp('collecte_le')->useCurrent();
            $table->uuid('creer_par');

            $table->text('description')->nullable()->comment("Description détaillée de la vente");

            $table->jsonb('editeurs')->nullable()->comment("Historique des modifications en JSONB");
            $table->boolean('status')->default(false);

            $table->timestamps();
            $table->softDeletes();



            // Index PostgreSQL optimisés
            $table->index('moisson_id');
            $table->index('categorie');
            $table->index('collecter_par');
            $table->index('creer_par');
            $table->index('status');
            $table->index('collecte_le');

            // Index GIN pour JSONB
            $table->index(['editeurs'], null, 'gin');

            // Index pour recherche textuelle
            $table->rawIndex("to_tsvector('french', description)", 'vente_moissons_description_index', 'gin');

            // Index composites
            $table->index(['moisson_id', 'categorie']);
            $table->index(['moisson_id', 'status']);

            // Contrainte unique pour éviter les doublons
            $table->unique(['moisson_id', 'categorie'], 'unique_vente_moisson');

            // Contraintes de clés étrangères
            $table->foreign('moisson_id')->references('id')->on('moissons')->onDelete('cascade');
            $table->foreign('collecter_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('creer_par')->references('id')->on('users')->onDelete('set null');
        });

        // Contraintes CHECK PostgreSQL
        DB::statement('ALTER TABLE vente_moissons ADD CONSTRAINT chk_vente_amounts_positive CHECK (cible >= 0 AND montant_solde >= 0 AND reste >= 0 AND montant_supplementaire >= 0)');
        DB::statement('ALTER TABLE vente_moissons ADD CONSTRAINT chk_vente_logic CHECK ((montant_solde + reste = cible AND montant_supplementaire = 0) OR (montant_solde = cible + montant_supplementaire AND reste = 0))');


        // Index pour recherche textuelle avec classe d'opérateurs appropriée
        DB::statement('CREATE INDEX idx_vente_moissons_description_search ON vente_moissons USING gin(to_tsvector(\'french\', description))');
        // Alternative : index B-tree pour les recherches LIKE
        // DB::statement('CREATE INDEX idx_vente_moissons_description_btree ON vente_moissons (description)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vente_moissons');
    }
};
