<?php

// =================================================================
// 2025_08_29_041427_create_subscriptions_table.php (CORRIGÉ - VERSION FINALE)
// =================================================================

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
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
                ->comment("Le montant soldé: c'est l'ensemble de tous les paiements déjà effectué par le souscription et ce montant vient de la table paiement_souscriptions. Mise à jour automatique");
            $table->decimal('reste', 15, 2)->default(0)
                ->comment("C'est l'ensemble le montant non soldé. Mise à jour automatique");
            $table->decimal('montant_supplementaire', 15, 2)->default(0)
                ->comment("Le montant supplémentaire existe si cible inférieur au cible. Ce montant est une valeur positive, c'est la différence entre montant_solde et cible lorsque le montant_solde est superieur cible c'est-à-dire le souscripteur va au dela de sa cible prévue. Mise à jour automatique");
            $table->decimal('progression', 5, 2)->default(0)
                ->comment("Progression ou évolution de montant soldé en % et peut aller au dela de 100% puis que le souscripteur peut aller au dela de sa cible");

            $table->enum('statut_global', ['objectif_atteint', 'presque_atteint', 'en_cours', 'tres_faible'])
                ->default('tres_faible')
                ->comment("Mise à jour automatique: tres_faible <=25%, 25% < en_cours <= 75%, 75% < presque_atteint <= 99,99% et objectif_atteint >= 100%");

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
        DB::statement("CREATE INDEX idx_subscriptions_souscripteur ON subscriptions(souscripteur_id) WHERE souscripteur_id IS NOT NULL AND deleted_at IS NULL");
        DB::statement("CREATE INDEX idx_subscriptions_fimeco ON subscriptions(fimeco_id) WHERE fimeco_id IS NOT NULL AND deleted_at IS NULL");
        DB::statement("CREATE INDEX idx_subscriptions_statut ON subscriptions(statut) WHERE deleted_at IS NULL");
        DB::statement("CREATE INDEX idx_subscriptions_statut_global ON subscriptions(statut_global) WHERE deleted_at IS NULL");
        DB::statement("CREATE INDEX idx_subscriptions_date_souscription ON subscriptions(date_souscription) WHERE deleted_at IS NULL");
        DB::statement("CREATE INDEX idx_subscriptions_date_echeance ON subscriptions(date_echeance) WHERE date_echeance IS NOT NULL AND deleted_at IS NULL");

        // Index composés pour requêtes business
        DB::statement("CREATE INDEX idx_subscriptions_fimeco_dashboard ON subscriptions(fimeco_id, statut, progression) WHERE deleted_at IS NULL");
        DB::statement("CREATE INDEX idx_subscriptions_fimeco_calculs ON subscriptions(fimeco_id, statut, montant_paye, montant_souscrit) WHERE deleted_at IS NULL");
        DB::statement("CREATE INDEX idx_subscriptions_souscripteur_actives ON subscriptions(souscripteur_id, statut, date_souscription) WHERE deleted_at IS NULL");
        DB::statement("CREATE INDEX idx_subscriptions_echeances ON subscriptions(date_echeance, statut, souscripteur_id) WHERE date_echeance IS NOT NULL AND deleted_at IS NULL");

        // Index partiels pour performance maximale
        DB::statement("CREATE INDEX idx_subscriptions_actives_montants ON subscriptions(fimeco_id, montant_paye, montant_souscrit) WHERE statut != 'inactive' AND deleted_at IS NULL");

        // Index pour souscriptions en retard avec fonction immutable
        DB::statement("CREATE INDEX idx_subscriptions_en_retard ON subscriptions(date_echeance, souscripteur_id, reste_a_payer) WHERE is_subscription_overdue(date_echeance, statut) AND deleted_at IS NULL");

        DB::statement("CREATE INDEX idx_subscriptions_completes ON subscriptions(fimeco_id, date_souscription) WHERE statut = 'completement_payee' AND deleted_at IS NULL");

        // Index avec plage de dates fixes
        DB::statement("CREATE INDEX idx_subscriptions_echeances_recentes ON subscriptions(date_echeance, souscripteur_id, statut) WHERE date_echeance >= '2024-01-01' AND date_echeance <= '2030-12-31' AND deleted_at IS NULL");

        // ===== CONTRAINTES POSTGRESQL =====

        DB::statement("ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_montant_souscrit_positif CHECK (montant_souscrit > 0)");
        DB::statement("ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_montants_positifs CHECK (montant_paye >= 0 AND reste_a_payer >= 0)");
        DB::statement("ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_coherence_montants CHECK (
    (montant_paye <= montant_souscrit AND montant_paye + reste_a_payer = montant_souscrit) OR
    (montant_paye > montant_souscrit AND reste_a_payer = 0)
)");
        // DB::statement("ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_coherence_montants CHECK (CHECK (
        //     (montant_paye <= montant_souscrit AND montant_paye + reste_a_payer = montant_souscrit) OR
        //     (montant_paye > montant_souscrit AND reste_a_payer = 0)
        // ))");
        DB::statement("ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_progression_valide CHECK (progression >= 0)");
        // DB::statement("ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_progression_valide CHECK (progression >= 0 AND progression <= 100)");
        DB::statement("ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_dates_coherentes CHECK (date_echeance IS NULL OR date_echeance >= date_souscription)");

        // ===== FONCTIONS DE SYNCHRONISATION AVANCÉES =====

        // Fonction de recalcul d'une souscription depuis ses paiements
        DB::unprepared("
 CREATE OR REPLACE FUNCTION recalculate_subscription_from_payments(subscription_uuid UUID)
RETURNS VOID AS $$
DECLARE
    total_paiements DECIMAL(15,2) := 0;
    subscription_data RECORD;
    nouveau_montant_paye DECIMAL(15,2);
    nouveau_reste DECIMAL(15,2);
    nouveau_supplementaire DECIMAL(15,2);
    nouvelle_progression DECIMAL(5,2);
    nouveau_statut TEXT;
    nouveau_statut_global TEXT;
BEGIN
    -- Récupération des données de base de la souscription
    SELECT montant_souscrit, fimeco_id INTO subscription_data
    FROM subscriptions
    WHERE id = subscription_uuid AND deleted_at IS NULL;

    IF subscription_data IS NULL THEN
        RAISE EXCEPTION 'Souscription introuvable: %', subscription_uuid;
    END IF;

    -- Calcul du total des paiements validés
    SELECT COALESCE(SUM(montant), 0) INTO total_paiements
    FROM subscription_payments
    WHERE subscription_id = subscription_uuid
      AND statut = 'valide'
      AND deleted_at IS NULL;

    -- Calculs dérivés avec gestion des surplus
    nouveau_montant_paye := total_paiements;

    -- Gestion du reste à payer et montant supplémentaire
    IF total_paiements >= subscription_data.montant_souscrit THEN
        -- Cas surplus: reste = 0, montant supplémentaire = surplus
        nouveau_reste := 0;
        nouveau_supplementaire := total_paiements - subscription_data.montant_souscrit;
    ELSE
        -- Cas normal: reste = différence, pas de surplus
        nouveau_reste := subscription_data.montant_souscrit - total_paiements;
        nouveau_supplementaire := 0;
    END IF;

    -- Calcul de la progression (peut dépasser 100%)
    nouvelle_progression := CASE
        WHEN subscription_data.montant_souscrit > 0 THEN
            ROUND((nouveau_montant_paye / subscription_data.montant_souscrit) * 100, 2)
        ELSE 0
    END;

    -- Calcul du statut
    nouveau_statut := CASE
        WHEN nouveau_montant_paye = 0 THEN 'inactive'
        WHEN nouveau_montant_paye >= subscription_data.montant_souscrit THEN 'completement_payee'
        ELSE 'partiellement_payee'
    END;

    -- Calcul du statut global (peut être objectif_atteint même au-delà de 100%)
    nouveau_statut_global := CASE
        WHEN nouvelle_progression >= 100 THEN 'objectif_atteint'
        WHEN nouvelle_progression > 75 THEN 'presque_atteint'
        WHEN nouvelle_progression > 25 THEN 'en_cours'
        ELSE 'tres_faible'
    END;

    -- Mise à jour atomique de la souscription
    UPDATE subscriptions SET
        montant_paye = nouveau_montant_paye,
        reste_a_payer = nouveau_reste,
        montant_supplementaire = nouveau_supplementaire,
        progression = nouvelle_progression,
        statut = nouveau_statut,
        statut_global = nouveau_statut_global,
        updated_at = NOW()
    WHERE id = subscription_uuid;

    RAISE NOTICE 'Souscription % synchronisée: payé=%, reste=%, supplémentaire=%, progression=%, statut=%',
        subscription_uuid, nouveau_montant_paye, nouveau_reste, nouveau_supplementaire, nouvelle_progression, nouveau_statut;

    -- Déclencher la synchronisation du FIMECO parent
    IF subscription_data.fimeco_id IS NOT NULL THEN
        PERFORM recalculate_fimeco_from_subscriptions(subscription_data.fimeco_id);
    END IF;
END;
$$ LANGUAGE plpgsql;
        ");

        // Fonction de synchronisation batch pour optimiser les performances
        DB::unprepared('
            CREATE OR REPLACE FUNCTION batch_synchronize_subscriptions_for_fimeco(fimeco_uuid UUID)
            RETURNS INTEGER AS $$
            DECLARE
                subscription_record RECORD;
                updated_count INTEGER := 0;
            BEGIN
                -- Synchronisation de toutes les souscriptions du FIMECO
                FOR subscription_record IN
                    SELECT id FROM subscriptions
                    WHERE fimeco_id = fimeco_uuid AND deleted_at IS NULL
                LOOP
                    PERFORM recalculate_subscription_from_payments(subscription_record.id);
                    updated_count := updated_count + 1;
                END LOOP;

                RAISE NOTICE \'% souscriptions synchronisées pour le FIMECO %\', updated_count, fimeco_uuid;
                RETURN updated_count;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // ===== TRIGGERS POSTGRESQL POUR AUTO-CALCULS =====

        DB::unprepared("
CREATE OR REPLACE FUNCTION auto_update_subscription_fields()
RETURNS TRIGGER AS $$
BEGIN
    -- Auto-calcul du reste à payer et montant supplémentaire
    IF NEW.montant_paye >= NEW.montant_souscrit THEN
        -- Cas surplus
        NEW.reste_a_payer := 0;
        NEW.montant_supplementaire := NEW.montant_paye - NEW.montant_souscrit;
    ELSE
        -- Cas normal
        NEW.reste_a_payer := NEW.montant_souscrit - NEW.montant_paye;
        NEW.montant_supplementaire := 0;
    END IF;

    -- Auto-calcul de la progression (peut dépasser 100%)
    NEW.progression := CASE
        WHEN NEW.montant_souscrit > 0 THEN ROUND((NEW.montant_paye / NEW.montant_souscrit) * 100, 2)
        ELSE 0
    END;

    -- Auto-calcul du statut
    NEW.statut := CASE
        WHEN NEW.montant_paye = 0 THEN 'inactive'
        WHEN NEW.montant_paye >= NEW.montant_souscrit THEN 'completement_payee'
        ELSE 'partiellement_payee'
    END;

    -- Auto-calcul du statut global
    NEW.statut_global := CASE
        WHEN NEW.progression >= 100 THEN 'objectif_atteint'
        WHEN NEW.progression > 75 THEN 'presque_atteint'
        WHEN NEW.progression > 25 THEN 'en_cours'
        ELSE 'tres_faible'
    END;

    -- Copie de la cible du FIMECO pour éviter les jointures
    IF NEW.fimeco_id IS NOT NULL AND (TG_OP = 'INSERT' OR OLD.fimeco_id != NEW.fimeco_id) THEN
        SELECT cible INTO NEW.cible FROM fimecos WHERE id = NEW.fimeco_id;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_auto_update_subscription_fields
                BEFORE INSERT OR UPDATE ON subscriptions
                FOR EACH ROW EXECUTE FUNCTION auto_update_subscription_fields();
        ");

        // Trigger pour synchronisation FIMECO après modification de souscription
        DB::unprepared("
            CREATE OR REPLACE FUNCTION trigger_sync_fimeco_after_subscription_change()
            RETURNS TRIGGER AS $$
            DECLARE
                fimeco_to_sync UUID;
            BEGIN
                -- Déterminer quel FIMECO synchroniser
                IF TG_OP = 'DELETE' THEN
                    fimeco_to_sync := OLD.fimeco_id;
                ELSIF TG_OP = 'UPDATE' THEN
                    -- Si le FIMECO a changé, synchroniser l\'ancien et le nouveau
                    IF OLD.fimeco_id != NEW.fimeco_id THEN
                        IF OLD.fimeco_id IS NOT NULL THEN
                            PERFORM recalculate_fimeco_from_subscriptions(OLD.fimeco_id);
                        END IF;
                    END IF;
                    fimeco_to_sync := NEW.fimeco_id;
                ELSE -- INSERT
                    fimeco_to_sync := NEW.fimeco_id;
                END IF;

                -- Synchronisation du FIMECO concerné
                IF fimeco_to_sync IS NOT NULL THEN
                    PERFORM recalculate_fimeco_from_subscriptions(fimeco_to_sync);
                END IF;

                IF TG_OP = 'DELETE' THEN
                    RETURN OLD;
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_sync_fimeco_after_subscription_change
                AFTER INSERT OR UPDATE OR DELETE ON subscriptions
                FOR EACH ROW EXECUTE FUNCTION trigger_sync_fimeco_after_subscription_change();
        ");

        // ===== FONCTION UTILITAIRE POUR REQUÊTES =====

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

        // ===== FONCTIONS DE MONITORING ET DEBUGGING =====

        DB::unprepared('
            CREATE OR REPLACE FUNCTION get_subscription_synchronization_status(subscription_uuid UUID)
            RETURNS TABLE(
                subscription_id UUID,
                montant_souscrit DECIMAL(15,2),
                montant_paye_stocke DECIMAL(15,2),
                montant_paye_calcule DECIMAL(15,2),
                difference DECIMAL(15,2),
                est_synchrone BOOLEAN,
                statut_actuel TEXT,
                statut_attendu TEXT
            ) AS $$
            DECLARE
                total_paiements DECIMAL(15,2);
                subscription_data RECORD;
                statut_calcule TEXT;
            BEGIN
                -- Récupération des données de la souscription
                SELECT montant_souscrit, montant_paye, statut
                INTO subscription_data
                FROM subscriptions
                WHERE id = subscription_uuid AND deleted_at IS NULL;

                IF subscription_data IS NULL THEN
                    RAISE EXCEPTION \'Souscription introuvable: %\', subscription_uuid;
                END IF;

                -- Calcul du montant réel depuis les paiements
                SELECT COALESCE(SUM(montant), 0) INTO total_paiements
                FROM subscription_payments
                WHERE subscription_id = subscription_uuid
                  AND statut = \'valide\'
                  AND deleted_at IS NULL;

                -- Calcul du statut attendu
                statut_calcule := CASE
                    WHEN total_paiements = 0 THEN \'inactive\'
                    WHEN total_paiements >= subscription_data.montant_souscrit THEN \'completement_payee\'
                    ELSE \'partiellement_payee\'
                END;

                RETURN QUERY
                SELECT
                    subscription_uuid,
                    subscription_data.montant_souscrit,
                    subscription_data.montant_paye,
                    total_paiements,
                    (subscription_data.montant_paye - total_paiements),
                    (subscription_data.montant_paye = total_paiements AND subscription_data.statut = statut_calcule),
                    subscription_data.statut,
                    statut_calcule;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');

        DB::unprepared('
            CREATE OR REPLACE FUNCTION detect_subscription_inconsistencies()
            RETURNS TABLE(
                subscription_id UUID,
                souscripteur_id UUID,
                fimeco_id UUID,
                probleme TEXT,
                montant_stocke DECIMAL(15,2),
                montant_calcule DECIMAL(15,2),
                action_recommandee TEXT
            ) AS $$
            BEGIN
                RETURN QUERY
                WITH payment_totals AS (
                    SELECT
                        sp.subscription_id,
                        SUM(CASE WHEN sp.statut = \'valide\' THEN sp.montant ELSE 0 END) as total_valide,
                        COUNT(*) as nb_paiements
                    FROM subscription_payments sp
                    WHERE sp.deleted_at IS NULL
                    GROUP BY sp.subscription_id
                ),
                subscription_analysis AS (
                    SELECT
                        s.id,
                        s.souscripteur_id,
                        s.fimeco_id,
                        s.montant_souscrit,
                        s.montant_paye,
                        s.reste_a_payer,
                        s.statut,
                        COALESCE(pt.total_valide, 0) as paiements_valides,
                        COALESCE(pt.nb_paiements, 0) as nb_paiements,
                        -- Calculs attendus
                        GREATEST(0, s.montant_souscrit - COALESCE(pt.total_valide, 0)) as reste_attendu,
                        CASE
                            WHEN COALESCE(pt.total_valide, 0) = 0 THEN \'inactive\'
                            WHEN COALESCE(pt.total_valide, 0) >= s.montant_souscrit THEN \'completement_payee\'
                            ELSE \'partiellement_payee\'
                        END as statut_attendu
                    FROM subscriptions s
                    LEFT JOIN payment_totals pt ON s.id = pt.subscription_id
                    WHERE s.deleted_at IS NULL
                )
                SELECT
                    sa.id,
                    sa.souscripteur_id,
                    sa.fimeco_id,
                    CASE
                        WHEN sa.montant_paye != sa.paiements_valides THEN \'Montant payé désynchronisé\'
                        WHEN sa.reste_a_payer != sa.reste_attendu THEN \'Reste à payer incohérent\'
                        WHEN sa.statut != sa.statut_attendu THEN \'Statut incorrect\'
                        WHEN sa.montant_paye + sa.reste_a_payer != sa.montant_souscrit THEN \'Cohérence mathématique\'
                        ELSE \'Incohérence détectée\'
                    END,
                    sa.montant_paye,
                    sa.paiements_valides,
                    CASE
                        WHEN sa.montant_paye != sa.paiements_valides THEN \'Exécuter recalculate_subscription_from_payments()\'
                        WHEN sa.statut != sa.statut_attendu THEN \'Vérifier les triggers de mise à jour\'
                        ELSE \'Analyser en détail\'
                    END
                FROM subscription_analysis sa
                WHERE sa.montant_paye != sa.paiements_valides
                   OR sa.reste_a_payer != sa.reste_attendu
                   OR sa.statut != sa.statut_attendu
                   OR sa.montant_paye + sa.reste_a_payer != sa.montant_souscrit
                ORDER BY ABS(sa.montant_paye - sa.paiements_valides) DESC;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');
    }

    public function down(): void
    {
        DB::statement("DROP FUNCTION IF EXISTS detect_subscription_inconsistencies()");
        DB::statement("DROP FUNCTION IF EXISTS get_subscription_synchronization_status(UUID)");
        DB::statement("DROP TRIGGER IF EXISTS trigger_sync_fimeco_after_subscription_change ON subscriptions");
        DB::statement("DROP FUNCTION IF EXISTS trigger_sync_fimeco_after_subscription_change()");
        DB::statement("DROP FUNCTION IF EXISTS batch_synchronize_subscriptions_for_fimeco(UUID)");
        DB::statement("DROP FUNCTION IF EXISTS recalculate_subscription_from_payments(UUID)");
        DB::statement("DROP FUNCTION IF EXISTS get_overdue_subscriptions(INTEGER)");
        DB::statement("DROP TRIGGER IF EXISTS trigger_auto_update_subscription_fields ON subscriptions");
        DB::statement("DROP FUNCTION IF EXISTS auto_update_subscription_fields()");
        DB::statement("DROP FUNCTION IF EXISTS is_subscription_overdue(DATE, TEXT)");
        Schema::dropIfExists("subscriptions");
    }
};
