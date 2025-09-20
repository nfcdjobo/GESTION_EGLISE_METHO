<?php

// NOUVELLE MIGRATION : 2025_08_29_060000_update_postgresql_triggers_for_supplementary_payments.php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // ===== MODIFICATION DES TRIGGERS POSTGRESQL POUR SUPPORTER LES PAIEMENTS SUPPLÉMENTAIRES =====

        // 1. Modification de la fonction de validation des règles métier des paiements
        DB::unprepared('
            CREATE OR REPLACE FUNCTION validate_payment_business_rules()
            RETURNS TRIGGER AS $$
            DECLARE
                subscription_data RECORD;
                current_version BIGINT;
                montant_deja_paye DECIMAL(15,2);
                reste_souscription DECIMAL(15,2);
            BEGIN
                -- Récupération des données de souscription avec verrouillage
                SELECT montant_souscrit, montant_paye, reste_a_payer, statut,
                       extract(epoch from updated_at)::bigint as version
                INTO subscription_data
                FROM subscriptions
                WHERE id = NEW.subscription_id AND deleted_at IS NULL
                FOR UPDATE;

                IF NOT FOUND THEN
                    RAISE EXCEPTION \'Souscription introuvable ou supprimée: %\', NEW.subscription_id;
                END IF;

                current_version := subscription_data.version;

                -- Auto-remplissage de la version pour contrôle de concurrence
                IF TG_OP = \'INSERT\' THEN
                    NEW.subscription_version_at_payment := current_version;
                END IF;

                -- Calcul du montant déjà payé avec paiements validés
                SELECT COALESCE(SUM(montant), 0) INTO montant_deja_paye
                FROM subscription_payments
                WHERE subscription_id = NEW.subscription_id
                  AND statut = \'valide\'
                  AND deleted_at IS NULL
                  AND (TG_OP = \'INSERT\' OR id != NEW.id);

                -- Calcul du reste de la souscription de base (peut être négatif si dépassement)
                reste_souscription := subscription_data.montant_souscrit - montant_deja_paye;

                -- Auto-calcul des restes pour les insertions
                IF TG_OP = \'INSERT\' THEN
                    -- Ancien reste : ce qui restait à payer de la souscription de base
                    NEW.ancien_reste := GREATEST(0, reste_souscription);

                    -- Nouveau reste : ce qui restera après ce paiement
                    -- Si le paiement dépasse le reste de la souscription, le nouveau reste sera 0
                    NEW.nouveau_reste := GREATEST(0, reste_souscription - NEW.montant);
                END IF;

                -- Validation de cohérence pour les paiements dans la limite de la souscription
                IF reste_souscription > 0 AND NEW.montant <= reste_souscription THEN
                    -- Paiement normal : vérification standard
                    IF NEW.ancien_reste - NEW.nouveau_reste != NEW.montant THEN
                        RAISE EXCEPTION \'Incohérence dans les calculs de reste pour paiement normal: ancien=%, nouveau=%, montant=%\',
                            NEW.ancien_reste, NEW.nouveau_reste, NEW.montant;
                    END IF;
                ELSE
                    -- Paiement supplémentaire : logique différente
                    IF reste_souscription <= 0 THEN
                        -- Souscription déjà complètement payée, tout est supplémentaire
                        NEW.ancien_reste := 0;
                        NEW.nouveau_reste := 0;
                    ELSE
                        -- Paiement mixte : une partie complète la souscription, le reste est supplémentaire
                        NEW.ancien_reste := reste_souscription;
                        NEW.nouveau_reste := 0;
                    END IF;
                END IF;

                -- Validation : montants positifs
                IF NEW.montant <= 0 THEN
                    RAISE EXCEPTION \'Le montant du paiement doit être positif: %\', NEW.montant;
                END IF;

                IF NEW.ancien_reste < 0 OR NEW.nouveau_reste < 0 THEN
                    RAISE EXCEPTION \'Les restes ne peuvent pas être négatifs: ancien=%, nouveau=%\',
                        NEW.ancien_reste, NEW.nouveau_reste;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // 2. Modification de la fonction de recalcul des souscriptions
        DB::unprepared('
            CREATE OR REPLACE FUNCTION recalculate_subscription_from_payments(subscription_uuid UUID)
            RETURNS VOID AS $$
            DECLARE
                total_paiements_valides DECIMAL(15,2) := 0;
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
                    RAISE EXCEPTION \'Souscription introuvable: %\', subscription_uuid;
                END IF;

                -- Calcul du total des paiements validés
                SELECT COALESCE(SUM(montant), 0) INTO total_paiements_valides
                FROM subscription_payments
                WHERE subscription_id = subscription_uuid
                  AND statut = \'valide\'
                  AND deleted_at IS NULL;

                -- Calculs dérivés pour supporter les paiements supplémentaires
                nouveau_montant_paye := total_paiements_valides;

                -- Calcul du reste à payer (ne peut pas être négatif)
                nouveau_reste := GREATEST(0, subscription_data.montant_souscrit - nouveau_montant_paye);

                -- Calcul du montant supplémentaire (au-delà de la souscription)
                nouveau_supplementaire := GREATEST(0, nouveau_montant_paye - subscription_data.montant_souscrit);

                -- Calcul de la progression (basée sur la souscription initiale, peut dépasser 100%)
                nouvelle_progression := CASE
                    WHEN subscription_data.montant_souscrit > 0 THEN
                        ROUND((nouveau_montant_paye / subscription_data.montant_souscrit) * 100, 2)
                    ELSE 0
                END;

                -- Calcul du statut basé sur la souscription de base
                nouveau_statut := CASE
                    WHEN nouveau_montant_paye = 0 THEN \'inactive\'
                    WHEN nouveau_montant_paye >= subscription_data.montant_souscrit THEN \'completement_payee\'
                    ELSE \'partiellement_payee\'
                END;

                -- Calcul du statut global (basé sur la progression de la souscription de base)
                nouveau_statut_global := CASE
                    WHEN nouvelle_progression >= 100 THEN \'objectif_atteint\'
                    WHEN nouvelle_progression > 75 THEN \'presque_atteint\'
                    WHEN nouvelle_progression > 25 THEN \'en_cours\'
                    ELSE \'tres_faible\'
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

                RAISE NOTICE \'Souscription % synchronisée: payé=%, reste=%, supplémentaire=%, progression=%, statut=%\',
                    subscription_uuid, nouveau_montant_paye, nouveau_reste, nouveau_supplementaire,
                    nouvelle_progression, nouveau_statut;

                -- Déclencher la synchronisation du FIMECO parent
                IF subscription_data.fimeco_id IS NOT NULL THEN
                    PERFORM recalculate_fimeco_from_subscriptions(subscription_data.fimeco_id);
                END IF;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // 3. Modification de la fonction de validation de cohérence des FIMECO
        DB::unprepared('
            CREATE OR REPLACE FUNCTION validate_fimeco_coherence()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Auto-calcul des champs dérivés pour supporter les dépassements
                IF NEW.montant_solde > NEW.cible THEN
                    NEW.reste := 0;
                    NEW.montant_supplementaire := NEW.montant_solde - NEW.cible;
                ELSE
                    NEW.reste := NEW.cible - NEW.montant_solde;
                    NEW.montant_supplementaire := 0;
                END IF;

                -- Auto-calcul progression (peut dépasser 100% maintenant)
                NEW.progression := CASE
                    WHEN NEW.cible > 0 THEN ROUND((NEW.montant_solde / NEW.cible) * 100, 2)
                    ELSE 0
                END;

                -- Auto-calcul statut_global (basé sur la cible, pas les suppléments)
                NEW.statut_global := CASE
                    WHEN NEW.progression >= 100 THEN \'objectif_atteint\'
                    WHEN NEW.progression > 75 THEN \'presque_atteint\'
                    WHEN NEW.progression > 25 THEN \'en_cours\'
                    ELSE \'tres_faible\'
                END;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // 4. Modification de la fonction de mise à jour automatique des champs de souscription
        DB::unprepared('
            CREATE OR REPLACE FUNCTION auto_update_subscription_fields()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Auto-calcul du reste à payer (ne peut pas être négatif)
                NEW.reste_a_payer := GREATEST(0, NEW.montant_souscrit - NEW.montant_paye);

                -- Auto-calcul du montant supplémentaire
                NEW.montant_supplementaire := GREATEST(0, NEW.montant_paye - NEW.montant_souscrit);

                -- Auto-calcul de la progression (peut dépasser 100%)
                NEW.progression := CASE
                    WHEN NEW.montant_souscrit > 0 THEN ROUND((NEW.montant_paye / NEW.montant_souscrit) * 100, 2)
                    ELSE 0
                END;

                -- Auto-calcul du statut (basé sur la souscription de base)
                NEW.statut := CASE
                    WHEN NEW.montant_paye = 0 THEN \'inactive\'
                    WHEN NEW.montant_paye >= NEW.montant_souscrit THEN \'completement_payee\'
                    ELSE \'partiellement_payee\'
                END;

                -- Auto-calcul du statut global (basé sur la progression de base)
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
        ');

        // 5. Nouvelle fonction pour détecter et analyser les paiements supplémentaires
        DB::unprepared('
            CREATE OR REPLACE FUNCTION analyze_supplementary_payments(
                fimeco_uuid UUID DEFAULT NULL,
                date_debut DATE DEFAULT NULL,
                date_fin DATE DEFAULT NULL
            )
            RETURNS TABLE(
                subscription_id UUID,
                souscripteur_nom TEXT,
                fimeco_nom TEXT,
                montant_souscrit DECIMAL(15,2),
                montant_total_paye DECIMAL(15,2),
                montant_supplementaire DECIMAL(15,2),
                pourcentage_depassement DECIMAL(5,2),
                nb_paiements_supplementaires BIGINT,
                dernier_paiement_supplementaire DATE
            ) AS $$
            BEGIN
                RETURN QUERY
                WITH subscription_analysis AS (
                    SELECT
                        s.id as sub_id,
                        s.montant_souscrit,
                        s.montant_paye,
                        s.montant_supplementaire,
                        f.nom as fimeco_name,
                        u.nom as user_name,
                        CASE
                            WHEN s.montant_souscrit > 0
                            THEN ROUND((s.montant_supplementaire / s.montant_souscrit) * 100, 2)
                            ELSE 0
                        END as pct_depassement
                    FROM subscriptions s
                    INNER JOIN fimecos f ON s.fimeco_id = f.id
                    INNER JOIN users u ON s.souscripteur_id = u.id
                    WHERE s.montant_supplementaire > 0
                      AND s.deleted_at IS NULL
                      AND f.deleted_at IS NULL
                      AND (fimeco_uuid IS NULL OR f.id = fimeco_uuid)
                ),
                supplementary_payments AS (
                    SELECT
                        sp.subscription_id,
                        COUNT(*) as nb_paiements_supp,
                        MAX(sp.date_paiement)::DATE as dernier_paiement_supp
                    FROM subscription_payments sp
                    INNER JOIN subscriptions s ON sp.subscription_id = s.id
                    WHERE sp.statut = \'valide\'
                      AND sp.deleted_at IS NULL
                      AND s.deleted_at IS NULL
                      AND (date_debut IS NULL OR sp.date_paiement >= date_debut)
                      AND (date_fin IS NULL OR sp.date_paiement <= date_fin)
                      AND (
                          SELECT COALESCE(SUM(sp2.montant), 0)
                          FROM subscription_payments sp2
                          WHERE sp2.subscription_id = sp.subscription_id
                            AND sp2.statut = \'valide\'
                            AND sp2.date_paiement <= sp.date_paiement
                            AND sp2.deleted_at IS NULL
                      ) > s.montant_souscrit
                    GROUP BY sp.subscription_id
                )
                SELECT
                    sa.sub_id,
                    sa.user_name,
                    sa.fimeco_name,
                    sa.montant_souscrit,
                    sa.montant_paye,
                    sa.montant_supplementaire,
                    sa.pct_depassement,
                    COALESCE(sup.nb_paiements_supp, 0),
                    sup.dernier_paiement_supp
                FROM subscription_analysis sa
                LEFT JOIN supplementary_payments sup ON sa.sub_id = sup.subscription_id
                ORDER BY sa.montant_supplementaire DESC;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');

        // 6. Fonction pour obtenir un résumé des paiements supplémentaires par FIMECO
        DB::unprepared('
            CREATE OR REPLACE FUNCTION supplementary_payments_summary_by_fimeco()
            RETURNS TABLE(
                fimeco_id UUID,
                fimeco_nom TEXT,
                nb_souscriptions_avec_supplement BIGINT,
                montant_total_supplementaire DECIMAL(15,2),
                pourcentage_contribution_supplement DECIMAL(5,2),
                montant_moyen_supplement DECIMAL(15,2)
            ) AS $$
            BEGIN
                RETURN QUERY
                SELECT
                    f.id,
                    f.nom,
                    COUNT(s.id) as nb_souscriptions,
                    COALESCE(SUM(s.montant_supplementaire), 0) as total_supplement,
                    CASE
                        WHEN f.cible > 0
                        THEN ROUND((COALESCE(SUM(s.montant_supplementaire), 0) / f.cible) * 100, 2)
                        ELSE 0
                    END as pct_contribution,
                    CASE
                        WHEN COUNT(s.id) > 0
                        THEN ROUND(COALESCE(SUM(s.montant_supplementaire), 0) / COUNT(s.id), 2)
                        ELSE 0
                    END as moyenne_supplement
                FROM fimecos f
                LEFT JOIN subscriptions s ON f.id = s.fimeco_id
                    AND s.montant_supplementaire > 0
                    AND s.deleted_at IS NULL
                WHERE f.deleted_at IS NULL
                GROUP BY f.id, f.nom, f.cible
                HAVING COUNT(s.id) > 0
                ORDER BY total_supplement DESC;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');

        // 7. Mise à jour des contraintes pour supporter les dépassements
        DB::statement('ALTER TABLE subscriptions DROP CONSTRAINT IF EXISTS chk_subscriptions_progression_valide');
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_progression_valide
                      CHECK (progression >= 0 AND progression <= 999.99)');

        DB::statement('ALTER TABLE fimecos DROP CONSTRAINT IF EXISTS chk_fimecos_progression_valide');
        DB::statement('ALTER TABLE fimecos ADD CONSTRAINT chk_fimecos_progression_valide
                      CHECK (progression >= 0 AND progression <= 999.99)');

        // Index pour optimiser les requêtes sur les paiements supplémentaires
        DB::statement('CREATE INDEX IF NOT EXISTS idx_subscriptions_montant_supplementaire
                      ON subscriptions(montant_supplementaire)
                      WHERE montant_supplementaire > 0 AND deleted_at IS NULL');

        DB::statement('CREATE INDEX IF NOT EXISTS idx_payments_supplementaires
                      ON subscription_payments(subscription_id, montant, date_paiement)
                      WHERE statut = \'valide\' AND deleted_at IS NULL');
    }

    public function down(): void
    {
        // Suppression des nouvelles fonctions
        DB::statement('DROP FUNCTION IF EXISTS supplementary_payments_summary_by_fimeco()');
        DB::statement('DROP FUNCTION IF EXISTS analyze_supplementary_payments(UUID, DATE, DATE)');

        // Suppression des index
        DB::statement('DROP INDEX IF EXISTS idx_subscriptions_montant_supplementaire');
        DB::statement('DROP INDEX IF EXISTS idx_payments_supplementaires');

        // Restauration des contraintes originales
        DB::statement('ALTER TABLE subscriptions DROP CONSTRAINT IF EXISTS chk_subscriptions_progression_valide');
        DB::statement('ALTER TABLE subscriptions ADD CONSTRAINT chk_subscriptions_progression_valide
                      CHECK (progression >= 0 AND progression <= 100)');

        DB::statement('ALTER TABLE fimecos DROP CONSTRAINT IF EXISTS chk_fimecos_progression_valide');
        DB::statement('ALTER TABLE fimecos ADD CONSTRAINT chk_fimecos_progression_valide
                      CHECK (progression >= 0 AND progression <= 100)');

        // Restauration des fonctions originales (versions simplifiées)
        DB::unprepared('
            CREATE OR REPLACE FUNCTION validate_payment_business_rules()
            RETURNS TRIGGER AS $$
            DECLARE
                subscription_data RECORD;
                current_version BIGINT;
            BEGIN
                SELECT montant_souscrit, montant_paye, reste_a_payer, statut,
                       extract(epoch from updated_at)::bigint as version
                INTO subscription_data
                FROM subscriptions
                WHERE id = NEW.subscription_id AND deleted_at IS NULL
                FOR UPDATE;

                IF NOT FOUND THEN
                    RAISE EXCEPTION \'Souscription introuvable ou supprimée: %\', NEW.subscription_id;
                END IF;

                current_version := subscription_data.version;

                IF subscription_data.statut = \'completement_payee\' AND TG_OP = \'INSERT\' THEN
                    RAISE EXCEPTION \'Impossible d\'ajouter un paiement sur une souscription déjà complètement payée\';
                END IF;

                IF TG_OP = \'INSERT\' THEN
                    NEW.subscription_version_at_payment := current_version;
                    NEW.ancien_reste := COALESCE(NEW.ancien_reste, subscription_data.reste_a_payer);
                    NEW.nouveau_reste := COALESCE(NEW.nouveau_reste, NEW.ancien_reste - NEW.montant);
                END IF;

                IF NEW.ancien_reste - NEW.nouveau_reste != NEW.montant THEN
                    RAISE EXCEPTION \'Incohérence dans les calculs de reste\';
                END IF;

                IF NEW.montant > subscription_data.reste_a_payer THEN
                    RAISE EXCEPTION \'Le montant du paiement dépasse le reste à payer\';
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');
    }
};
