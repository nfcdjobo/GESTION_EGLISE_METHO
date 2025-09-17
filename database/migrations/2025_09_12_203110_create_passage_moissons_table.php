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
        Schema::create('passage_moissons', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('moisson_id');

            // Utiliser Laravel's enum() method
            $table->enum('categorie', [
                'passage_hommes',
                'passage_femmes',
                'passage_jeunesses',
                'passage_enfants',
                'passage_classe_communautaire',
                'passage_predicateurs',
                'passage_conseil',
                'passage_assemble'
            ])->comment("Catégorie de passage");

            $table->uuid('classe_id')->nullable()->comment("Si le type est le passage de la classe communautaire alors classe_id prend l'identifiant de la classe sinon null");

            // Montants avec contraintes
            $table->decimal('cible', 15, 2)->comment("C'est l'objectif à atteindre mais on peut aller au-delà");
            $table->decimal('montant_solde', 15, 2)->default(0)->comment("L'ensemble des montants déjà collectés");
            $table->decimal('reste', 15, 2)->default(0)->comment("Reste à solder");
            $table->decimal('montant_supplementaire', 15, 2)->default(0)->comment("Existe lorsque montant_solde > cible");

            $table->uuid('collecter_par');
            $table->timestamp('collecte_le')->useCurrent();

            $table->uuid('creer_par');
            $table->jsonb('editeurs')->nullable()->comment("Historique des modifications en JSONB");

            $table->boolean('status')->default(false);

            $table->timestamps();
            $table->softDeletes();

            // Index PostgreSQL optimisés
            $table->index('moisson_id');
            $table->index('categorie');
            $table->index('classe_id');
            $table->index('collecter_par');
            $table->index('creer_par');
            $table->index('status');
            $table->index('collecte_le');

            // Index GIN pour JSONB
            $table->index(['editeurs'], null, 'gin');

            // Index composites pour les requêtes fréquentes
            $table->index(['moisson_id', 'categorie']);
            $table->index(['moisson_id', 'status']);

            // Contrainte unique composite
            $table->unique(['moisson_id', 'categorie', 'classe_id'], 'unique_passage_moisson');

            // Contraintes de clés étrangères
            $table->foreign('moisson_id')->references('id')->on('moissons')->onDelete('cascade');
            $table->foreign('classe_id')->references('id')->on('classes')->onDelete('set null');
            $table->foreign('creer_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('collecter_par')->references('id')->on('users')->onDelete('set null');
        });

        // Index partiel pour les passages actifs
        DB::statement('CREATE INDEX idx_passage_moissons_active ON passage_moissons (moisson_id) WHERE status = true AND deleted_at IS NULL');

        // Contraintes CHECK PostgreSQL
        DB::statement("ALTER TABLE passage_moissons ADD CONSTRAINT chk_passage_amounts_positive CHECK (cible >= 0 AND montant_solde >= 0 AND reste >= 0 AND montant_supplementaire >= 0)");
        DB::statement("ALTER TABLE passage_moissons ADD CONSTRAINT chk_passage_logic CHECK ((montant_solde + reste = cible AND montant_supplementaire = 0) OR (montant_solde = cible + montant_supplementaire AND reste = 0))");
        DB::statement("ALTER TABLE passage_moissons ADD CONSTRAINT chk_classe_id_logic CHECK ((categorie = 'passage_classe_communautaire' AND classe_id IS NOT NULL) OR (categorie != 'passage_classe_communautaire' AND classe_id IS NULL))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passage_moissons');
    }
};
