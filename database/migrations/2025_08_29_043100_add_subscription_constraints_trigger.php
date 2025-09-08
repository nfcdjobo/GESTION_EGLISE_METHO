<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fonction pour gérer l'INSERT d'un paiement (version corrigée pour supporter les dépassements)
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_subscription_reste_after_payment_fn()
            RETURNS TRIGGER AS $$
            BEGIN
                IF NEW.statut = \'valide\' THEN
                    UPDATE subscriptions
                    SET
                        montant_paye = montant_paye + NEW.montant,
                        reste_a_payer = montant_souscrit - (montant_paye + NEW.montant),
                        statut = CASE
                            -- Si le montant payé atteint ou dépasse le montant souscrit
                            WHEN (montant_paye + NEW.montant) >= montant_souscrit THEN \'completement_payee\'
                            -- Si il y a eu au moins un paiement mais pas encore soldé
                            WHEN montant_paye + NEW.montant > 0 THEN \'partiellement_payee\'
                            ELSE statut
                        END,
                        version = version + 1,
                        updated_at = NOW()
                    WHERE id = NEW.subscription_id;
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER update_subscription_reste_after_payment
            AFTER INSERT ON subscription_payments
            FOR EACH ROW
            EXECUTE FUNCTION update_subscription_reste_after_payment_fn();
        ');

        // Fonction pour gérer l'annulation d'un paiement (version corrigée)
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_subscription_reste_after_payment_cancellation_fn()
            RETURNS TRIGGER AS $$
            BEGIN
                IF OLD.statut = \'valide\' AND NEW.statut = \'annule\' THEN
                    UPDATE subscriptions
                    SET
                        montant_paye = montant_paye - OLD.montant,
                        reste_a_payer = reste_a_payer + OLD.montant,
                        statut = CASE
                            -- Si après annulation, le montant payé est encore >= au montant souscrit
                            WHEN (montant_paye - OLD.montant) >= montant_souscrit THEN \'completement_payee\'
                            -- Si après annulation, il reste encore des paiements
                            WHEN montant_paye - OLD.montant > 0 THEN \'partiellement_payee\'
                            -- Sinon retour au statut actif
                            ELSE \'active\'
                        END,
                        version = version + 1,
                        updated_at = NOW()
                    WHERE id = OLD.subscription_id;
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER update_subscription_reste_after_payment_cancellation
            AFTER UPDATE ON subscription_payments
            FOR EACH ROW
            EXECUTE FUNCTION update_subscription_reste_after_payment_cancellation_fn();
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_subscription_reste_after_payment ON subscription_payments');
        DB::unprepared('DROP FUNCTION IF EXISTS update_subscription_reste_after_payment_fn');

        DB::unprepared('DROP TRIGGER IF EXISTS update_subscription_reste_after_payment_cancellation ON subscription_payments');
        DB::unprepared('DROP FUNCTION IF EXISTS update_subscription_reste_after_payment_cancellation_fn');
    }
};
