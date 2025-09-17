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
        Schema::create('engagement_moissons', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->uuid('moisson_id');

            // Utiliser Laravel's enum() method
            $table->enum('categorie', ['entite_morale', 'entite_physique'])
                  ->comment("Type d'engagement");

            $table->uuid('donateur_id')->nullable()->comment("Existe si catégorie est une personne physique");

            // Informations supplémentaires pour entité morale
            $table->string('nom_entite', 255)->nullable()->comment("Nom de l'entité morale si applicable");
            $table->text('description')->nullable()->comment("Description de l'engagement");

            // Coordonnées de contact
            $table->string('telephone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('adresse')->nullable();

            // Montants avec contraintes
            $table->decimal('cible', 15, 2)->comment("C'est l'objectif à atteindre mais on peut aller au-delà");
            $table->decimal('montant_solde', 15, 2)->default(0)->comment("L'ensemble des montants déjà collectés");
            $table->decimal('reste', 15, 2)->default(0)->comment("Reste à solder");
            $table->decimal('montant_supplementaire', 15, 2)->default(0)->comment("Existe lorsque montant_solde > cible");

            // Informations de suivi
            $table->uuid('collecter_par');
            $table->timestamp('collecter_le')->useCurrent();
            $table->uuid('creer_par');

            // Dates d'échéances pour le suivi
            $table->date('date_echeance')->nullable()->comment("Date limite pour honorer l'engagement");
            $table->date('date_rappel')->nullable()->comment("Date de rappel automatique");

            $table->jsonb('editeurs')->nullable()->comment("Historique des modifications en JSONB");
            $table->boolean('status')->default(false);

            $table->timestamps();
            $table->softDeletes();



            // Index PostgreSQL optimisés
            $table->index('moisson_id');
            $table->index('categorie');
            $table->index('donateur_id');
            $table->index('collecter_par');
            $table->index('creer_par');
            $table->index('status');
            $table->index('date_echeance');
            $table->index('date_rappel');

            // Index GIN pour JSONB seulement (pas pour text)
            $table->index(['editeurs'], null, 'gin');


            // Index pour email (recherche rapide)
            $table->index('email');

            // Index composites
            $table->index(['moisson_id', 'categorie']);
            $table->index(['moisson_id', 'status']);
            $table->index(['date_echeance', 'status'], 'idx_engagement_echeance_status');

            // Contraintes de clés étrangères
            $table->foreign('moisson_id')->references('id')->on('moissons')->onDelete('cascade');
            $table->foreign('collecter_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('creer_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('donateur_id')->references('id')->on('users')->onDelete('set null');
        });

        // Index pour recherche textuelle avec classe d'opérateurs appropriée
        DB::statement('CREATE INDEX idx_engagement_moissons_description_search ON engagement_moissons USING gin(to_tsvector(\'french\', description))');

        // Contraintes CHECK PostgreSQL
        DB::statement("ALTER TABLE engagement_moissons ADD CONSTRAINT chk_engagement_amounts_positive CHECK (cible >= 0 AND montant_solde >= 0 AND reste >= 0 AND montant_supplementaire >= 0)");
        DB::statement("ALTER TABLE engagement_moissons ADD CONSTRAINT chk_engagement_logic CHECK ((montant_solde + reste = cible AND montant_supplementaire = 0) OR (montant_solde = cible + montant_supplementaire AND reste = 0))");
        DB::statement("ALTER TABLE engagement_moissons ADD CONSTRAINT chk_engagement_donateur CHECK ((categorie = 'entite_physique' AND donateur_id IS NOT NULL) OR (categorie = 'entite_morale' AND donateur_id IS NULL))");
        DB::statement("ALTER TABLE engagement_moissons ADD CONSTRAINT chk_engagement_entite_morale CHECK ((categorie = 'entite_morale' AND nom_entite IS NOT NULL) OR categorie = 'entite_physique')");
        DB::statement("ALTER TABLE engagement_moissons ADD CONSTRAINT chk_engagement_dates CHECK (date_echeance IS NULL OR date_rappel IS NULL OR date_rappel <= date_echeance)");
        DB::statement("ALTER TABLE engagement_moissons ADD CONSTRAINT chk_engagement_email CHECK (email IS NULL OR email ~* '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engagement_moissons');
    }
};
