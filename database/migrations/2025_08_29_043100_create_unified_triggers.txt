<?php

// =================================================================
// 2025_08_29_043100_create_unified_triggers.php (NOUVEAU - SYSTÈME UNIFIÉ)
// =================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ===== FONCTION CENTRALE POUR RECALCULER UNE SOUSCRIPTION =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION recalculate_subscription_totals(target_subscription_id UUID)
            RETURNS void AS $$
            DECLARE
                total_paye DECIMAL(15,2);
                subscription_data RECORD;
            BEGIN
                -- Récupérer les informations de base de la souscription
                SELECT montant_souscrit, fimeco_id INTO subscription_data
                FROM subscriptions
                WHERE id = target_subscription_id AND deleted_at IS NULL;

                IF NOT FOUND THEN
                    RAISE EXCEPTION \'Souscription introuvable: %\', target_subscription_id;
                END IF;

                -- Calculer le total des paiements validés
                SELECT COALESCE(SUM(montant), 0) INTO total_paye
                FROM subscription_payments
                WHERE subscription_id = target_subscription_id
                  AND statut = \'valide\'
                  AND deleted_at IS NULL;

                -- Mettre à jour la souscription avec tous les calculs automatiques
                UPDATE subscriptions
                SET
                    montant_paye = total_paye,
                    updated_at = NOW()
                WHERE id = target_subscription_id;

                -- Le trigger BEFORE se chargera des autres calculs (reste_a_payer, progression, statut, etc.)

            EXCEPTION
                WHEN OTHERS THEN
                    -- Log l\'erreur mais ne pas faire échouer la transaction
                    RAISE WARNING \'Erreur recalcul souscription %: %\', target_subscription_id, SQLERRM;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // ===== FONCTION POUR RECALCULER UN FIMECO =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION recalculate_fimeco_totals(target_fimeco_id UUID)
            RETURNS void AS $$
            DECLARE
                total_solde DECIMAL(15,2);
            BEGIN
                -- Calculer le montant soldé total depuis les souscriptions
                SELECT COALESCE(SUM(montant_paye), 0) INTO total_solde
                FROM subscriptions
                WHERE fimeco_id = target_fimeco_id AND deleted_at IS NULL;

                -- Mettre à jour le FIMECO
                UPDATE fimecos SET
                    montant_solde = total_solde,
                    updated_at = NOW()
                WHERE id = target_fimeco_id AND deleted_at IS NULL;

                -- Le trigger BEFORE se chargera des autres calculs (reste, progression, statut_global)

            EXCEPTION
                WHEN OTHERS THEN
                    -- Log l\'erreur mais ne pas faire échouer la transaction
                    RAISE WARNING \'Erreur recalcul FIMECO %: %\', target_fimeco_id, SQLERRM;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // ===== TRIGGER UNIFIÉ POUR GÉRER TOUS LES CHANGEMENTS DE PAIEMENTS =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION handle_payment_changes()
            RETURNS TRIGGER AS $$
            DECLARE
                affected_fimeco_id UUID;
                old_affected_fimeco_id UUID;
            BEGIN
                -- INSERT: nouveau paiement
                IF TG_OP = \'INSERT\' THEN
                    -- Recalculer la souscription concernée
                    PERFORM recalculate_subscription_totals(NEW.subscription_id);

                    -- Récupérer et recalculer le FIMECO associé
                    SELECT fimeco_id INTO affected_fimeco_id
                    FROM subscriptions
                    WHERE id = NEW.subscription_id;

                    IF affected_fimeco_id IS NOT NULL THEN
                        PERFORM recalculate_fimeco_totals(affected_fimeco_id);
                    END IF;

                    RETURN NEW;
                END IF;

                -- UPDATE: modification d\'un paiement
                IF TG_OP = \'UPDATE\' THEN
                    -- Si le statut, le montant ou la souscription a changé
                    IF OLD.statut != NEW.statut OR
                       OLD.montant != NEW.montant OR
                       OLD.subscription_id != NEW.subscription_id OR
                       OLD.deleted_at IS DISTINCT FROM NEW.deleted_at THEN

                        -- Recalculer la nouvelle souscription
                        PERFORM recalculate_subscription_totals(NEW.subscription_id);

                        -- Récupérer le FIMECO de la nouvelle souscription
                        SELECT fimeco_id INTO affected_fimeco_id
                        FROM subscriptions
                        WHERE id = NEW.subscription_id;

                        IF affected_fimeco_id IS NOT NULL THEN
                            PERFORM recalculate_fimeco_totals(affected_fimeco_id);
                        END IF;

                        -- Si la souscription a changé, recalculer aussi l\'ancienne
                        IF OLD.subscription_id != NEW.subscription_id THEN
                            PERFORM recalculate_subscription_totals(OLD.subscription_id);

                            SELECT fimeco_id INTO old_affected_fimeco_id
                            FROM subscriptions
                            WHERE id = OLD.subscription_id;

                            IF old_affected_fimeco_id IS NOT NULL THEN
                                PERFORM recalculate_fimeco_totals(old_affected_fimeco_id);
                            END IF;
                        END IF;
                    END IF;

                    RETURN NEW;
                END IF;

                -- DELETE: suppression d\'un paiement
                IF TG_OP = \'DELETE\' THEN
                    PERFORM recalculate_subscription_totals(OLD.subscription_id);

                    SELECT fimeco_id INTO affected_fimeco_id
                    FROM subscriptions
                    WHERE id = OLD.subscription_id;

                    IF affected_fimeco_id IS NOT NULL THEN
                        PERFORM recalculate_fimeco_totals(affected_fimeco_id);
                    END IF;

                    RETURN OLD;
                END IF;

                RETURN NULL;
            END;
            $$ LANGUAGE plpgsql;

            -- Créer le trigger unifié
            CREATE TRIGGER trigger_handle_payment_changes
                AFTER INSERT OR UPDATE OR DELETE ON subscription_payments
                FOR EACH ROW EXECUTE FUNCTION handle_payment_changes();
        ');

        // ===== TRIGGER POUR SYNCHRONISER LES FIMECOS QUAND UNE SOUSCRIPTION CHANGE =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION sync_fimeco_after_subscription_change()
            RETURNS TRIGGER AS $$
            DECLARE
                old_fimeco_id UUID;
                new_fimeco_id UUID;
            BEGIN
                -- UPDATE d\'une souscription
                IF TG_OP = \'UPDATE\' THEN
                    old_fimeco_id := OLD.fimeco_id;
                    new_fimeco_id := NEW.fimeco_id;

                    -- Si le FIMECO a changé ou si le montant payé a changé
                    IF old_fimeco_id != new_fimeco_id OR OLD.montant_paye != NEW.montant_paye THEN
                        -- Recalculer le nouveau FIMECO
                        IF new_fimeco_id IS NOT NULL THEN
                            PERFORM recalculate_fimeco_totals(new_fimeco_id);
                        END IF;

                        -- Recalculer l\'ancien FIMECO si différent
                        IF old_fimeco_id IS NOT NULL AND old_fimeco_id != new_fimeco_id THEN
                            PERFORM recalculate_fimeco_totals(old_fimeco_id);
                        END IF;
                    END IF;

                    RETURN NEW;
                END IF;

                -- INSERT d\'une nouvelle souscription
                IF TG_OP = \'INSERT\' THEN
                    IF NEW.fimeco_id IS NOT NULL THEN
                        PERFORM recalculate_fimeco_totals(NEW.fimeco_id);
                    END IF;

                    RETURN NEW;
                END IF;

                -- DELETE d\'une souscription
                IF TG_OP = \'DELETE\' THEN
                    IF OLD.fimeco_id IS NOT NULL THEN
                        PERFORM recalculate_fimeco_totals(OLD.fimeco_id);
                    END IF;

                    RETURN OLD;
                END IF;

                RETURN NULL;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_sync_fimeco_after_subscription_change
                AFTER INSERT OR UPDATE OR DELETE ON subscriptions
                FOR EACH ROW EXECUTE FUNCTION sync_fimeco_after_subscription_change();
        ');

        // ===== FONCTION DE TEST ET DE VÉRIFICATION =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION test_calculations_coherence(test_subscription_id UUID DEFAULT NULL)
            RETURNS TABLE(
                subscription_id UUID,
                fimeco_id UUID,
                montant_souscrit DECIMAL(15,2),
                montant_paye_bdd DECIMAL(15,2),
                montant_paye_calcule DECIMAL(15,2),
                reste_a_payer_bdd DECIMAL(15,2),
                reste_a_payer_calcule DECIMAL(15,2),
                progression_bdd DECIMAL(5,2),
                progression_calculee DECIMAL(5,2),
                statut_bdd VARCHAR,
                statut_calcule VARCHAR,
                is_coherent BOOLEAN,
                nb_paiements_valides BIGINT
            ) AS $$
            BEGIN
                RETURN QUERY
                SELECT
                    s.id,
                    s.fimeco_id,
                    s.montant_souscrit,
                    s.montant_paye as montant_paye_bdd,
                    COALESCE(p.total_paye, 0) as montant_paye_calcule,
                    s.reste_a_payer as reste_a_payer_bdd,
                    GREATEST(0, s.montant_souscrit - COALESCE(p.total_paye, 0)) as reste_a_payer_calcule,
                    s.progression as progression_bdd,
                    CASE
                        WHEN s.montant_souscrit > 0 THEN
                            ROUND((COALESCE(p.total_paye, 0) / s.montant_souscrit) * 100, 2)
                        ELSE 0
                    END as progression_calculee,
                    s.statut::VARCHAR as statut_bdd,
                    CASE
                        WHEN COALESCE(p.total_paye, 0) = 0 THEN \'inactive\'
                        WHEN COALESCE(p.total_paye, 0) >= s.montant_souscrit THEN \'completement_payee\'
                        ELSE \'partiellement_payee\'
                    END as statut_calcule,
                    (s.montant_paye = COALESCE(p.total_paye, 0) AND
                     s.reste_a_payer = GREATEST(0, s.montant_souscrit - COALESCE(p.total_paye, 0))) as is_coherent,
                    COALESCE(p.nb_paiements, 0) as nb_paiements_valides
                FROM subscriptions s
                LEFT JOIN (
                    SELECT
                        subscription_id,
                        SUM(montant) as total_paye,
                        COUNT(*) as nb_paiements
                    FROM subscription_payments
                    WHERE statut = \'valide\' AND deleted_at IS NULL
                    GROUP BY subscription_id
                ) p ON s.id = p.subscription_id
                WHERE s.deleted_at IS NULL
                  AND (test_subscription_id IS NULL OR s.id = test_subscription_id)
                ORDER BY s.created_at DESC;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');

        // ===== FONCTION POUR RÉPARER TOUTES LES DONNÉES =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION repair_all_calculations()
            RETURNS TABLE(
                operation VARCHAR,
                affected_count BIGINT,
                execution_time INTERVAL
            ) AS $$
            DECLARE
                start_time TIMESTAMP;
                subscription_count BIGINT := 0;
                fimeco_count BIGINT := 0;
                subscription_record RECORD;
                fimeco_record RECORD;
            BEGIN
                start_time := clock_timestamp();

                -- Réparer toutes les souscriptions
                FOR subscription_record IN
                    SELECT id FROM subscriptions WHERE deleted_at IS NULL
                LOOP
                    PERFORM recalculate_subscription_totals(subscription_record.id);
                    subscription_count := subscription_count + 1;
                END LOOP;

                RETURN QUERY SELECT \'SUBSCRIPTIONS_REPAIRED\'::VARCHAR, subscription_count, clock_timestamp() - start_time;

                start_time := clock_timestamp();

                -- Réparer tous les FIMECOs
                FOR fimeco_record IN
                    SELECT id FROM fimecos WHERE deleted_at IS NULL
                LOOP
                    PERFORM recalculate_fimeco_totals(fimeco_record.id);
                    fimeco_count := fimeco_count + 1;
                END LOOP;

                RETURN QUERY SELECT \'FIMECOS_REPAIRED\'::VARCHAR, fimeco_count, clock_timestamp() - start_time;

                RETURN;
            END;
            $$ LANGUAGE plpgsql;
        ');
    }

    public function down(): void
    {
        // Suppression de tous les triggers et fonctions unifiés
        DB::statement('DROP TRIGGER IF EXISTS trigger_sync_fimeco_after_subscription_change ON subscriptions');
        DB::statement('DROP FUNCTION IF EXISTS sync_fimeco_after_subscription_change()');

        DB::statement('DROP TRIGGER IF EXISTS trigger_handle_payment_changes ON subscription_payments');
        DB::statement('DROP FUNCTION IF EXISTS handle_payment_changes()');

        DB::statement('DROP FUNCTION IF EXISTS repair_all_calculations()');
        DB::statement('DROP FUNCTION IF EXISTS test_calculations_coherence(UUID)');
        DB::statement('DROP FUNCTION IF EXISTS recalculate_fimeco_totals(UUID)');
        DB::statement('DROP FUNCTION IF EXISTS recalculate_subscription_totals(UUID)');
    }
};
