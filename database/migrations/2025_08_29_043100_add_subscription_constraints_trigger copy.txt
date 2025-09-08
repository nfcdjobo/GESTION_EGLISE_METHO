<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fonction pour gérer l'INSERT d'un paiement
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
                            WHEN (montant_souscrit - (montant_paye + NEW.montant)) <= 0 THEN \'completement_payee\'
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

        // Fonction pour gérer l'annulation d'un paiement
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
                            WHEN montant_paye - OLD.montant <= 0 THEN \'active\'
                            ELSE \'partiellement_payee\'
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
