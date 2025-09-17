<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ===== FONCTION POUR RECALCULER UNE SOUSCRIPTION COMPLÈTE =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION recalculate_subscription_amounts(target_subscription_id UUID)
            RETURNS void AS $$
            DECLARE
                total_paye DECIMAL(15,2);
                subscription_record RECORD;
            BEGIN
                -- Récupérer les informations de la souscription
                SELECT montant_souscrit, version INTO subscription_record
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

                -- Mettre à jour la souscription
                UPDATE subscriptions
                SET
                    montant_paye = total_paye,
                    reste_a_payer = GREATEST(0, montant_souscrit - total_paye),
                    progression = CASE
                        WHEN montant_souscrit > 0 THEN
                            LEAST(100.0, (total_paye / montant_souscrit * 100))
                        ELSE 0
                    END,
                    statut = CASE
                        WHEN total_paye >= montant_souscrit THEN \'completement_payee\'
                        WHEN total_paye > 0 THEN \'partiellement_payee\'
                        ELSE \'active\'
                    END,
                    version = subscription_record.version + 1,
                    updated_at = NOW()
                WHERE id = target_subscription_id;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // ===== TRIGGER APRÈS INSERTION D\'UN PAIEMENT =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_subscription_after_payment_insert()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Seulement si le paiement est validé
                IF NEW.statut = \'valide\' THEN
                    PERFORM recalculate_subscription_amounts(NEW.subscription_id);
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER trigger_update_subscription_after_payment_insert
            AFTER INSERT ON subscription_payments
            FOR EACH ROW
            EXECUTE FUNCTION update_subscription_after_payment_insert();
        ');

        // ===== TRIGGER APRÈS MODIFICATION D\'UN PAIEMENT =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_subscription_after_payment_update()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Si le statut du paiement a changé ou si le montant a changé
                IF OLD.statut != NEW.statut OR OLD.montant != NEW.montant THEN
                    PERFORM recalculate_subscription_amounts(NEW.subscription_id);

                    -- Si la souscription a changé, recalculer aussi l\'ancienne
                    IF OLD.subscription_id != NEW.subscription_id THEN
                        PERFORM recalculate_subscription_amounts(OLD.subscription_id);
                    END IF;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER trigger_update_subscription_after_payment_update
            AFTER UPDATE ON subscription_payments
            FOR EACH ROW
            EXECUTE FUNCTION update_subscription_after_payment_update();
        ');

        // ===== TRIGGER APRÈS SUPPRESSION D\'UN PAIEMENT =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_subscription_after_payment_delete()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Recalculer la souscription après suppression
                PERFORM recalculate_subscription_amounts(OLD.subscription_id);

                RETURN OLD;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER trigger_update_subscription_after_payment_delete
            AFTER DELETE ON subscription_payments
            FOR EACH ROW
            EXECUTE FUNCTION update_subscription_after_payment_delete();
        ');

        // ===== TRIGGER POUR SOFT DELETE DES PAIEMENTS =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_subscription_after_payment_soft_delete()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Si un paiement passe de non-supprimé à supprimé ou vice-versa
                IF (OLD.deleted_at IS NULL AND NEW.deleted_at IS NOT NULL) OR
                   (OLD.deleted_at IS NOT NULL AND NEW.deleted_at IS NULL) THEN
                    PERFORM recalculate_subscription_amounts(NEW.subscription_id);
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER trigger_update_subscription_after_payment_soft_delete
            AFTER UPDATE ON subscription_payments
            FOR EACH ROW
            WHEN (OLD.deleted_at IS DISTINCT FROM NEW.deleted_at)
            EXECUTE FUNCTION update_subscription_after_payment_soft_delete();
        ');

        // ===== FONCTION DE VALIDATION BUSINESS =====
        DB::unprepared('
            CREATE OR REPLACE FUNCTION validate_payment_business_logic()
            RETURNS TRIGGER AS $$
            DECLARE
                subscription_info RECORD;
                current_total_paye DECIMAL(15,2);
            BEGIN
                -- Récupérer les infos de la souscription
                SELECT montant_souscrit, statut INTO subscription_info
                FROM subscriptions
                WHERE id = NEW.subscription_id AND deleted_at IS NULL;

                IF NOT FOUND THEN
                    RAISE EXCEPTION \'Souscription introuvable ou supprimée: %\', NEW.subscription_id;
                END IF;

                -- Interdire les paiements sur une souscription complètement payée (sauf cas de remboursement)
                IF subscription_info.statut = \'completement_payee\' AND NEW.montant > 0 AND TG_OP = \'INSERT\' THEN
                    RAISE EXCEPTION \'Impossible d\'\'ajouter un paiement sur une souscription complètement payée\';
                END IF;

                -- Calculer le total qui serait payé après ce paiement
                SELECT COALESCE(SUM(montant), 0) INTO current_total_paye
                FROM subscription_payments
                WHERE subscription_id = NEW.subscription_id
                  AND statut = \'valide\'
                  AND deleted_at IS NULL
                  AND (TG_OP = \'INSERT\' OR id != NEW.id); -- Exclure le paiement en cours de modification

                -- Ajouter le nouveau montant si c\'est un paiement validé
                IF NEW.statut = \'valide\' THEN
                    current_total_paye := current_total_paye + NEW.montant;
                END IF;

                -- Permettre les dépassements mais les signaler dans les logs
                IF current_total_paye > subscription_info.montant_souscrit THEN
                    BEGIN
                        INSERT INTO system_logs (type, message, created_at)
                        VALUES (
                            \'WARNING\',
                            \'Paiement entraîne un dépassement - Souscription: \' || NEW.subscription_id ||
                            \', Montant total: \' || current_total_paye ||
                            \', Montant souscrit: \' || subscription_info.montant_souscrit,
                            NOW()
                        );
                    EXCEPTION
                        WHEN OTHERS THEN
                            -- Ignorer les erreurs de log
                            NULL;
                    END;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER trigger_validate_payment_business_logic
            BEFORE INSERT OR UPDATE ON subscription_payments
            FOR EACH ROW
            EXECUTE FUNCTION validate_payment_business_logic();
        ');
    }

    public function down(): void
    {
        // -- Suppression des triggers
        DB::statement('DROP TRIGGER IF EXISTS trigger_validate_payment_business_logic ON subscription_payments');
        DB::statement('DROP TRIGGER IF EXISTS trigger_update_subscription_after_payment_soft_delete ON subscription_payments');
        DB::statement('DROP TRIGGER IF EXISTS trigger_update_subscription_after_payment_delete ON subscription_payments');
        DB::statement('DROP TRIGGER IF EXISTS trigger_update_subscription_after_payment_update ON subscription_payments');
        DB::statement('DROP TRIGGER IF EXISTS trigger_update_subscription_after_payment_insert ON subscription_payments');

        // -- Suppression des fonctions
        DB::statement('DROP FUNCTION IF EXISTS validate_payment_business_logic()');
        DB::statement('DROP FUNCTION IF EXISTS update_subscription_after_payment_soft_delete()');
        DB::statement('DROP FUNCTION IF EXISTS update_subscription_after_payment_delete()');
        DB::statement('DROP FUNCTION IF EXISTS update_subscription_after_payment_update()');
        DB::statement('DROP FUNCTION IF EXISTS update_subscription_after_payment_insert()');
        DB::statement('DROP FUNCTION IF EXISTS recalculate_subscription_amounts(UUID)');

        // -- Suppression des anciens triggers si ils existent
        DB::statement('DROP TRIGGER IF EXISTS update_subscription_reste_after_payment ON subscription_payments');
        DB::statement('DROP FUNCTION IF EXISTS update_subscription_reste_after_payment_fn()');
        DB::statement('DROP TRIGGER IF EXISTS update_subscription_reste_after_payment_cancellation ON subscription_payments');
        DB::statement('DROP FUNCTION IF EXISTS update_subscription_reste_after_payment_cancellation_fn()');
    }
};
