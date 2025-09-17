<?php

// =================================================================
// 2025_08_29_040507_create_fimecos_table.php (POSTGRESQL OPTIMISÉ)
// =================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fimecos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Clé étrangère avec index
            $table->uuid('responsable_id')->nullable();
            $table->foreign('responsable_id', 'fk_fimecos_responsable')
                  ->references('id')->on('users')
                  ->onDelete('set null');

            // Champs principaux
            $table->string('nom', 100);
            $table->text('description')->nullable();
            $table->date('debut');
            $table->date('fin');

            // Montants avec précision PostgreSQL
            $table->decimal('cible', 15, 2)
                  ->comment("Le montant cible: c'est un montant prévu. En un mot c'est l'objectif à atteindre et cet objectif doit forcement être atteint avant que d'autre fimeco soit créé");

            $table->decimal('montant_solde', 15, 2)->default(0)
                  ->comment("Le montant soldé: c'est l'ensemble de tous les paiements déjà effectué par les souscripteurs et ce montant vient de la table subscriptions. Mise à jour automatique");

            $table->decimal('reste', 15, 2)->default(0)
                  ->comment("C'est l'ensemble des montants non soldés. Mise à jour automatique");

            $table->decimal('montant_supplementaire', 15, 2)->default(0)
                  ->comment("Le montant supplémentaire existe si montant_solde supérieur à la cible. Mise à jour automatique");

            $table->decimal('progression', 5, 2)->default(0)
                  ->comment("Progression ou évolution de montant soldé en %");

            $table->enum('statut_global', ['objectif_atteint', 'presque_atteint', 'en_cours', 'tres_faible'])
                  ->default('tres_faible')
                  ->comment("Mise à jour automatique: tres_faible <=25%, 25%<en_cours<=75%, 75%<presque_atteint<=99,99% et objectif_atteint >=100%");

            $table->enum('statut', ['active', 'inactive', 'cloturee'])
                  ->default('inactive');

            $table->timestamps();
            $table->softDeletes();
        });

        // ===== INDEX POSTGRESQL HAUTE PERFORMANCE =====

        // Index standards
        DB::statement('CREATE INDEX idx_fimecos_responsable ON fimecos(responsable_id) WHERE responsable_id IS NOT NULL');
        DB::statement('CREATE INDEX idx_fimecos_nom ON fimecos(nom) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_fimecos_statut ON fimecos(statut) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_fimecos_statut_global ON fimecos(statut_global) WHERE deleted_at IS NULL');

        // Index composés pour requêtes fréquentes
        DB::statement('CREATE INDEX idx_fimecos_dashboard ON fimecos(statut, statut_global, debut) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_fimecos_periode ON fimecos(debut, fin, statut) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_fimecos_progression ON fimecos(progression DESC, statut) WHERE deleted_at IS NULL');

        // Index partiels pour performance maximale
        DB::statement('CREATE INDEX idx_fimecos_actifs_progression ON fimecos(progression DESC, debut) WHERE statut = \'active\' AND deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_fimecos_en_cours ON fimecos(debut, fin, montant_solde) WHERE statut = \'active\' AND deleted_at IS NULL');

        // Index de recherche textuelle français
        DB::statement('CREATE INDEX idx_fimecos_nom_search ON fimecos USING gin(to_tsvector(\'french\', nom)) WHERE deleted_at IS NULL');

        // ===== CONTRAINTES POSTGRESQL =====

        DB::statement('ALTER TABLE fimecos ADD CONSTRAINT chk_fimecos_cible_positive CHECK (cible > 0)');
        DB::statement('ALTER TABLE fimecos ADD CONSTRAINT chk_fimecos_montants_positifs CHECK (montant_solde >= 0 AND reste >= 0 AND montant_supplementaire >= 0)');
        DB::statement('ALTER TABLE fimecos ADD CONSTRAINT chk_fimecos_progression_valide CHECK (progression >= 0 AND progression <= 999.99)');
        DB::statement('ALTER TABLE fimecos ADD CONSTRAINT chk_fimecos_dates_coherentes CHECK (fin >= debut)');
        DB::statement('ALTER TABLE fimecos ADD CONSTRAINT chk_fimecos_coherence_cible CHECK (
            (montant_solde <= cible AND reste = cible - montant_solde AND montant_supplementaire = 0) OR
            (montant_solde > cible AND reste = 0 AND montant_supplementaire = montant_solde - cible)
        )');

        // ===== TRIGGERS POSTGRESQL POUR AUTOMATISATION =====

        DB::unprepared('
            CREATE OR REPLACE FUNCTION validate_fimeco_coherence()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Auto-calcul des champs dérivés
                IF NEW.montant_solde > NEW.cible THEN
                    NEW.reste := 0;
                    NEW.montant_supplementaire := NEW.montant_solde - NEW.cible;
                ELSE
                    NEW.reste := NEW.cible - NEW.montant_solde;
                    NEW.montant_supplementaire := 0;
                END IF;

                -- Auto-calcul progression
                NEW.progression := CASE
                    WHEN NEW.cible > 0 THEN ROUND((NEW.montant_solde / NEW.cible) * 100, 2)
                    ELSE 0
                END;

                -- Auto-calcul statut_global
                NEW.statut_global := CASE
                    WHEN NEW.progression >= 100 THEN \'objectif_atteint\'
                    WHEN NEW.progression > 75 THEN \'presque_atteint\'
                    WHEN NEW.progression > 25 THEN \'en_cours\'
                    ELSE \'tres_faible\'
                END;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_validate_fimeco_coherence
                BEFORE INSERT OR UPDATE ON fimecos
                FOR EACH ROW EXECUTE FUNCTION validate_fimeco_coherence();
        ');
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS trigger_validate_fimeco_coherence ON fimecos');
        DB::statement('DROP FUNCTION IF EXISTS validate_fimeco_coherence()');
        Schema::dropIfExists('fimecos');
    }
};
