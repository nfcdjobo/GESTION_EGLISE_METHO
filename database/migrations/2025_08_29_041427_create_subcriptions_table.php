<?php

// =================================================================
// 2025_08_29_041427_create_subscriptions_table.php (CORRIGÉ POSTGRESQL)
// =================================================================

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('souscripteur_id')->nullable();
            $table->foreign('souscripteur_id', 'fk_subscriptions_souscripteur')
                  ->references('id')->on('users')->onDelete('set null');

            $table->uuid('fimeco_id')->nullable();
            $table->foreign('fimeco_id', 'fk_subscriptions_fimeco')
                  ->references('id')->on('fimecos')->onDelete('cascade');

            // Montants principaux
            $table->decimal('montant_souscrit', 15, 2);
            $table->decimal('montant_paye', 15, 2)->default(0);
            $table->decimal('reste_a_payer', 15, 2)->default(0);

            // Champs calculés pour éviter jointures
            $table->decimal('cible', 15, 2)->default(0)
                  ->comment("Copie de la cible du FIMECO pour éviter les jointures coûteuses");
            $table->decimal('montant_solde', 15, 2)->default(0)
                  ->comment("Le montant soldé: c'est l'ensemble de tous les paiements déjà effectué par les souscripteurs et ce montant vient de la table paiement_souscriptions. Mise à jour automatique");
            $table->decimal('reste', 15, 2)->default(0)
                  ->comment("C'est l'ensemble des montants non soldés. Mise à jour automatique");
            $table->decimal('montant_supplementaire', 15, 2)->default(0)
                  ->comment("Le montant supplémentaire existe si cible inférieur au cible. Mise à jour automatique");
            $table->decimal('progression', 5, 2)->default(0)
                  ->comment("Progression ou évolution de montant soldé en %");

            $table->enum('statut_global', ['objectif_atteint', 'presque_atteint', 'en_cours', 'tres_faible'])
                  ->default('tres_faible')
                  ->comment("Mise à jour automatique: tres_faible <=25%, 25%<en_cours<=75%, 75%<presque_atteint<=99,99% et objectif_atteint >=100%");

            $table->enum('statut', ['inactive', 'partiellement_payee', 'completement_payee'])
                  ->default('inactive')
                  ->comment("Mise à jour automatique en fonction du reste à payer");

            $table->date('date_souscription');
            $table->date('date_echeance')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Contrainte d'unicité
            $table->unique(['souscripteur_id', 'fimeco_id'], 'unique_souscription_par_fimeco');
        });

        // ===== FONCTION IMMUTABLE POUR INDEX PARTIELS =====

        // Fonction immutable pour déterminer si une date d'échéance est dépassée
        DB::unprepared('
            CREATE OR REPLACE FUNCTION is_subscription_overdue(echeance_date DATE, subscription_status TEXT)
            RETURNS BOOLEAN AS $$
            BEGIN
                RETURN echeance_date IS NOT NULL
                   AND echeance_date < CURRENT_DATE
                   AND subscription_status != \'completement_payee\';
            END;
            $$ LANGUAGE plpgsql IMMUTABLE;
        ');

        // ===== INDEX POSTGRESQL HAUTE PERFORMANCE =====

        // Index de base avec conditions
        DB::statement('CREATE INDEX idx_subscriptions_souscripteur ON subscriptions(souscripteur_id) WHERE souscripteur_id IS NOT NULL AND deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_subscriptions_fimeco ON subscriptions(fimeco_id) WHERE fimeco_id IS NOT NULL AND deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_subscriptions_statut ON subscriptions(statut) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_subscriptions_statut_global ON subscriptions(statut_global) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_subscriptions_date_souscription ON subscriptions(date_souscription) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_subscriptions_date_echeance ON subscriptions(date_echeance) WHERE date_echeance IS NOT NULL AND deleted_at IS NULL');

        // Index composés pour requêtes business
        DB::statement('CREATE INDEX idx_subscriptions_fimeco_dashboard ON subscriptions(fimeco_id, statut, progression) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_subscriptions_fimeco_calculs ON subscriptions(fimeco_id, statut, montant_paye, montant_souscrit) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_subscriptions_souscripteur_actives ON subscriptions(souscripteur_id, statut, date_souscription) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_subscriptions_echeances ON subscriptions(date_echeance, statut, souscripteur_id) WHERE date_echeance IS NOT NULL AND deleted_at IS NULL');

        // Index partiels pour performance maximale (CORRIGÉS)
        DB::statement('CREATE INDEX idx_subscriptions_actives_montants ON subscriptions(fimeco_id, montant_paye, montant_souscrit) WHERE statut != \'inactive\' AND deleted_at IS NULL');

        // Index pour souscriptions en retard avec fonction immutable
        DB::statement('CREATE INDEX idx_subscriptions_en_retard ON subscriptions(date_echeance, souscripteur_id, reste_a_payer) WHERE is_subscription_overdue(date_echeance, statut) AND deleted_at IS NULL');

        DB::statement('CREATE INDEX idx_subscriptions_completes ON subscriptions(fimeco_id, date_souscription) WHERE statut = \'completement_payee\' AND deleted_at IS NULL');

        // Index avec plage de dates fixes (alternative plus performante)
        DB::statement('CREATE INDEX idx_subscriptions_echeances_recentes ON subscriptions(date_echeance, souscripteur_id, statut) WHERE date_echeance >= \'2024-01-01\' AND date_echeance <= \'2030-12-31\' AND deleted_at IS NULL');

        // ===== CONTRAINTES POSTGRESQL =====

        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_montant_souscrit_positif CHECK (montant_souscrit > 0)');
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_montants_positifs CHECK (montant_paye >= 0 AND reste_a_payer >= 0)');
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_coherence_montants CHECK (montant_paye + reste_a_payer = montant_souscrit)');
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_progression_valide CHECK (progression >= 0 AND progression <= 100)');
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_dates_coherentes CHECK (date_echeance IS NULL OR date_echeance >= date_souscription)');

        // ===== TRIGGERS POSTGRESQL POUR AUTO-CALCULS =====

        DB::unprepared('
            CREATE OR REPLACE FUNCTION auto_update_subscription_fields()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Auto-calcul du reste à payer
                NEW.reste_a_payer := GREATEST(0, NEW.montant_souscrit - NEW.montant_paye);

                -- Auto-calcul de la progression
                NEW.progression := CASE
                    WHEN NEW.montant_souscrit > 0 THEN ROUND((NEW.montant_paye / NEW.montant_souscrit) * 100, 2)
                    ELSE 0
                END;

                -- Auto-calcul du statut
                NEW.statut := CASE
                    WHEN NEW.montant_paye = 0 THEN \'inactive\'
                    WHEN NEW.montant_paye >= NEW.montant_souscrit THEN \'completement_payee\'
                    ELSE \'partiellement_payee\'
                END;

                -- Auto-calcul du statut global
                NEW.statut_global := CASE
                    WHEN NEW.progression >= 100 THEN \'objectif_atteint\'
                    WHEN NEW.progression > 75 THEN \'presque_atteint\'
                    WHEN NEW.progression > 25 THEN \'en_cours\'
                    ELSE \'tres_faible\'
                END;

                -- Copie de la cible du FIMECO pour éviter les jointures
                IF NEW.fimeco_id IS NOT NULL AND (TG_OP = \'INSERT\' OR OLD.fimeco_id != NEW.fimeco_id) THEN
                    SELECT cible INTO NEW.cible FROM fimecos WHERE id = NEW.fimeco_id;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_auto_update_subscription_fields
                BEFORE INSERT OR UPDATE ON subscriptions
                FOR EACH ROW EXECUTE FUNCTION auto_update_subscription_fields();
        ');

        // Trigger pour mettre à jour les totaux FIMECO
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_fimeco_totals_from_subscription()
            RETURNS TRIGGER AS $$
            DECLARE
                target_fimeco_id UUID;
                old_fimeco_id UUID;
            BEGIN
                -- Déterminer les FIMECOs à mettre à jour
                target_fimeco_id := COALESCE(NEW.fimeco_id, OLD.fimeco_id);
                old_fimeco_id := CASE WHEN TG_OP = \'UPDATE\' AND OLD.fimeco_id != NEW.fimeco_id THEN OLD.fimeco_id ELSE NULL END;

                -- Mise à jour du FIMECO cible
                IF target_fimeco_id IS NOT NULL THEN
                    UPDATE fimecos SET
                        montant_solde = (
                            SELECT COALESCE(SUM(montant_paye), 0)
                            FROM subscriptions
                            WHERE fimeco_id = target_fimeco_id AND deleted_at IS NULL
                        ),
                        updated_at = NOW()
                    WHERE id = target_fimeco_id;
                END IF;

                -- Mise à jour de l\'ancien FIMECO si changement
                IF old_fimeco_id IS NOT NULL THEN
                    UPDATE fimecos SET
                        montant_solde = (
                            SELECT COALESCE(SUM(montant_paye), 0)
                            FROM subscriptions
                            WHERE fimeco_id = old_fimeco_id AND deleted_at IS NULL
                        ),
                        updated_at = NOW()
                    WHERE id = old_fimeco_id;
                END IF;

                RETURN COALESCE(NEW, OLD);
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_update_fimeco_totals_from_subscription
                AFTER INSERT OR UPDATE OR DELETE ON subscriptions
                FOR EACH ROW EXECUTE FUNCTION update_fimeco_totals_from_subscription();
        ');

        // ===== FONCTION UTILITAIRE POUR REQUÊTES =====

        // Fonction pour obtenir les souscriptions en retard
        DB::unprepared('
            CREATE OR REPLACE FUNCTION get_overdue_subscriptions(limit_count INTEGER DEFAULT 100)
            RETURNS TABLE(
                subscription_id UUID,
                souscripteur_id UUID,
                fimeco_id UUID,
                montant_souscrit DECIMAL(15,2),
                montant_paye DECIMAL(15,2),
                reste_a_payer DECIMAL(15,2),
                date_echeance DATE,
                jours_retard INTEGER
            ) AS $$
            BEGIN
                RETURN QUERY
                SELECT
                    s.id,
                    s.souscripteur_id,
                    s.fimeco_id,
                    s.montant_souscrit,
                    s.montant_paye,
                    s.reste_a_payer,
                    s.date_echeance,
                    (CURRENT_DATE - s.date_echeance)::INTEGER
                FROM subscriptions s
                WHERE s.date_echeance IS NOT NULL
                  AND s.date_echeance < CURRENT_DATE
                  AND s.statut != \'completement_payee\'
                  AND s.deleted_at IS NULL
                ORDER BY s.date_echeance ASC, s.reste_a_payer DESC
                LIMIT limit_count;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');
    }

    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS get_overdue_subscriptions(INTEGER)');
        DB::statement('DROP TRIGGER IF EXISTS trigger_update_fimeco_totals_from_subscription ON subscriptions');
        DB::statement('DROP FUNCTION IF EXISTS update_fimeco_totals_from_subscription()');
        DB::statement('DROP TRIGGER IF EXISTS trigger_auto_update_subscription_fields ON subscriptions');
        DB::statement('DROP FUNCTION IF EXISTS auto_update_subscription_fields()');
        DB::statement('DROP FUNCTION IF EXISTS is_subscription_overdue(DATE, TEXT)');
        Schema::dropIfExists('subscriptions');
    }
};
