<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ===== VUES MATÉRIALISÉES POSTGRESQL =====

        // Vue matérialisée pour dashboard FIMECO avec performance maximale
        DB::statement('
            CREATE MATERIALIZED VIEW mv_fimeco_dashboard AS
            SELECT
                f.id,
                f.nom,
                f.responsable_id,
                f.cible,
                f.montant_solde,
                f.reste,
                f.montant_supplementaire,
                f.progression,
                f.statut_global,
                f.statut,
                f.debut,
                f.fin,
                f.created_at,
                f.updated_at,
                COUNT(s.id) as nb_souscriptions_total,
                COUNT(CASE WHEN s.statut = \'completement_payee\' THEN 1 END) as nb_souscriptions_completes,
                COUNT(CASE WHEN s.statut = \'partiellement_payee\' THEN 1 END) as nb_souscriptions_partielles,
                COUNT(CASE WHEN s.statut = \'inactive\' THEN 1 END) as nb_souscriptions_inactives,
                COALESCE(SUM(s.montant_souscrit), 0) as total_montant_souscrit,
                COALESCE(AVG(s.progression), 0) as progression_moyenne_souscriptions,
                COUNT(CASE WHEN s.date_echeance < CURRENT_DATE AND s.statut != \'completement_payee\' THEN 1 END) as nb_souscriptions_en_retard,
                (SELECT COUNT(*) FROM subscription_payments sp
                 JOIN subscriptions s2 ON sp.subscription_id = s2.id
                 WHERE s2.fimeco_id = f.id AND sp.statut = \'en_attente\' AND sp.deleted_at IS NULL) as nb_paiements_en_attente
            FROM fimecos f
            LEFT JOIN subscriptions s ON f.id = s.fimeco_id AND s.deleted_at IS NULL
            WHERE f.deleted_at IS NULL
            GROUP BY f.id, f.nom, f.responsable_id, f.cible, f.montant_solde, f.reste,
                     f.montant_supplementaire, f.progression, f.statut_global, f.statut,
                     f.debut, f.fin, f.created_at, f.updated_at
        ');

        // Index sur la vue matérialisée - séparés en plusieurs statements
        DB::statement('CREATE UNIQUE INDEX idx_mv_fimeco_dashboard_id ON mv_fimeco_dashboard (id)');
        DB::statement('CREATE INDEX idx_mv_fimeco_dashboard_statut ON mv_fimeco_dashboard (statut, statut_global)');
        DB::statement('CREATE INDEX idx_mv_fimeco_dashboard_progression ON mv_fimeco_dashboard (progression DESC)');
        DB::statement('CREATE INDEX idx_mv_fimeco_dashboard_responsable ON mv_fimeco_dashboard (responsable_id)');
        DB::statement('CREATE INDEX idx_mv_fimeco_dashboard_dates ON mv_fimeco_dashboard (debut, fin)');

        // Vue matérialisée pour statistiques de paiements par période
        DB::statement('
            CREATE MATERIALIZED VIEW mv_payment_statistics AS
            SELECT
                f.id as fimeco_id,
                f.nom as fimeco_nom,
                DATE_TRUNC(\'month\', sp.date_paiement) as mois,
                DATE_TRUNC(\'week\', sp.date_paiement) as semaine,
                DATE_TRUNC(\'day\', sp.date_paiement) as jour,
                sp.type_paiement,
                sp.statut as statut_paiement,
                COUNT(sp.id) as nb_paiements,
                SUM(sp.montant) as montant_total,
                AVG(sp.montant) as montant_moyen,
                MIN(sp.montant) as montant_min,
                MAX(sp.montant) as montant_max,
                COUNT(DISTINCT s.souscripteur_id) as nb_souscripteurs_uniques
            FROM fimecos f
            JOIN subscriptions s ON f.id = s.fimeco_id
            JOIN subscription_payments sp ON s.id = sp.subscription_id
            WHERE f.deleted_at IS NULL
              AND s.deleted_at IS NULL
              AND sp.deleted_at IS NULL
            GROUP BY f.id, f.nom, DATE_TRUNC(\'month\', sp.date_paiement),
                     DATE_TRUNC(\'week\', sp.date_paiement), DATE_TRUNC(\'day\', sp.date_paiement),
                     sp.type_paiement, sp.statut
        ');

        // Index sur les statistiques - séparés
        DB::statement('CREATE INDEX idx_mv_payment_statistics_fimeco_mois ON mv_payment_statistics (fimeco_id, mois)');
        DB::statement('CREATE INDEX idx_mv_payment_statistics_type_mois ON mv_payment_statistics (type_paiement, mois)');
        DB::statement('CREATE INDEX idx_mv_payment_statistics_statut ON mv_payment_statistics (statut_paiement, mois)');
        DB::statement('CREATE INDEX idx_mv_payment_statistics_montant ON mv_payment_statistics (mois, montant_total DESC)');

        // Vue matérialisée pour rapports de performance par souscripteur
        DB::statement('
            CREATE MATERIALIZED VIEW mv_souscripteur_performance AS
            SELECT
                u.id as souscripteur_id,
                u.nom as souscripteur_nom,
                u.email as souscripteur_email,
                COUNT(s.id) as nb_souscriptions_total,
                COUNT(CASE WHEN s.statut = \'completement_payee\' THEN 1 END) as nb_souscriptions_completes,
                COUNT(CASE WHEN s.statut = \'partiellement_payee\' THEN 1 END) as nb_souscriptions_partielles,
                COUNT(CASE WHEN s.statut = \'inactive\' THEN 1 END) as nb_souscriptions_inactives,
                SUM(s.montant_souscrit) as montant_total_souscrit,
                SUM(s.montant_paye) as montant_total_paye,
                SUM(s.reste_a_payer) as reste_total_a_payer,
                AVG(s.progression) as progression_moyenne,
                COUNT(CASE WHEN s.date_echeance < CURRENT_DATE AND s.statut != \'completement_payee\' THEN 1 END) as nb_souscriptions_en_retard,
                MIN(s.date_souscription) as premiere_souscription,
                MAX(s.date_souscription) as derniere_souscription,
                COUNT(DISTINCT s.fimeco_id) as nb_fimecos_differents
            FROM users u
            JOIN subscriptions s ON u.id = s.souscripteur_id
            WHERE s.deleted_at IS NULL
                            GROUP BY u.id, u.nom, u.email
        ');

        // Index sur la performance des souscripteurs - séparés
        DB::statement('CREATE UNIQUE INDEX idx_mv_souscripteur_performance_id ON mv_souscripteur_performance (souscripteur_id)');
        DB::statement('CREATE INDEX idx_mv_souscripteur_performance_progression ON mv_souscripteur_performance (progression_moyenne DESC)');
        DB::statement('CREATE INDEX idx_mv_souscripteur_performance_montant ON mv_souscripteur_performance (montant_total_paye DESC)');
        DB::statement('CREATE INDEX idx_mv_souscripteur_performance_retard ON mv_souscripteur_performance (nb_souscriptions_en_retard DESC)');

        // ===== FONCTIONS POSTGRESQL OPTIMISÉES =====

        // Fonction pour obtenir le résumé complet d'un FIMECO
        DB::unprepared('
            CREATE OR REPLACE FUNCTION get_fimeco_complete_summary(fimeco_uuid UUID)
            RETURNS TABLE(
                id UUID,
                nom VARCHAR(100),
                cible DECIMAL(15,2),
                montant_solde DECIMAL(15,2),
                reste DECIMAL(15,2),
                montant_supplementaire DECIMAL(15,2),
                progression DECIMAL(5,2),
                statut_global VARCHAR,
                statut VARCHAR,
                nb_souscriptions_total BIGINT,
                nb_souscriptions_actives BIGINT,
                nb_souscriptions_completes BIGINT,
                montant_total_souscrit DECIMAL(15,2),
                montant_total_paye DECIMAL(15,2),
                progression_moyenne DECIMAL(5,2),
                nb_paiements_en_attente BIGINT,
                dernier_paiement_date TIMESTAMP,
                nb_jours_restants INTEGER
            ) AS $$
            BEGIN
                RETURN QUERY
                SELECT
                    f.id,
                    f.nom,
                    f.cible,
                    f.montant_solde,
                    f.reste,
                    f.montant_supplementaire,
                    f.progression,
                    f.statut_global::VARCHAR,
                    f.statut::VARCHAR,
                    COUNT(s.id)::BIGINT,
                    COUNT(CASE WHEN s.statut != \'inactive\' THEN 1 END)::BIGINT,
                    COUNT(CASE WHEN s.statut = \'completement_payee\' THEN 1 END)::BIGINT,
                    COALESCE(SUM(s.montant_souscrit), 0),
                    COALESCE(SUM(s.montant_paye), 0),
                    COALESCE(AVG(s.progression), 0),
                    (SELECT COUNT(*)
                     FROM subscription_payments sp
                     JOIN subscriptions s2 ON sp.subscription_id = s2.id
                     WHERE s2.fimeco_id = f.id AND sp.statut = \'en_attente\' AND sp.deleted_at IS NULL)::BIGINT,
                    (SELECT MAX(sp.date_paiement)
                     FROM subscription_payments sp
                     JOIN subscriptions s2 ON sp.subscription_id = s2.id
                     WHERE s2.fimeco_id = f.id AND sp.statut = \'valide\' AND sp.deleted_at IS NULL),
                    GREATEST(0, (f.fin - CURRENT_DATE)::INTEGER)
                FROM fimecos f
                LEFT JOIN subscriptions s ON f.id = s.fimeco_id AND s.deleted_at IS NULL
                WHERE f.id = fimeco_uuid AND f.deleted_at IS NULL
                GROUP BY f.id, f.nom, f.cible, f.montant_solde, f.reste, f.montant_supplementaire,
                         f.progression, f.statut_global, f.statut, f.fin;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');

        // Fonction pour calculs rapides de statistiques mensuelles
        DB::unprepared('
            CREATE OR REPLACE FUNCTION get_monthly_payment_stats(
                target_fimeco_id UUID DEFAULT NULL,
                start_date DATE DEFAULT NULL,
                end_date DATE DEFAULT NULL
            )
            RETURNS TABLE(
                mois DATE,
                fimeco_id UUID,
                fimeco_nom VARCHAR(100),
                nb_paiements BIGINT,
                montant_total DECIMAL(15,2),
                nb_souscripteurs BIGINT,
                montant_moyen DECIMAL(15,2),
                type_paiement_principal VARCHAR
            ) AS $$
            BEGIN
                RETURN QUERY
                SELECT
                    DATE_TRUNC(\'month\', sp.date_paiement)::DATE,
                    f.id,
                    f.nom,
                    COUNT(sp.id)::BIGINT,
                    SUM(sp.montant),
                    COUNT(DISTINCT s.souscripteur_id)::BIGINT,
                    AVG(sp.montant),
                    MODE() WITHIN GROUP (ORDER BY sp.type_paiement)::VARCHAR
                FROM subscription_payments sp
                JOIN subscriptions s ON sp.subscription_id = s.id
                JOIN fimecos f ON s.fimeco_id = f.id
                WHERE sp.statut = \'valide\'
                  AND sp.deleted_at IS NULL
                  AND s.deleted_at IS NULL
                  AND f.deleted_at IS NULL
                  AND (target_fimeco_id IS NULL OR f.id = target_fimeco_id)
                  AND (start_date IS NULL OR sp.date_paiement >= start_date)
                  AND (end_date IS NULL OR sp.date_paiement <= end_date)
                GROUP BY DATE_TRUNC(\'month\', sp.date_paiement), f.id, f.nom
                ORDER BY DATE_TRUNC(\'month\', sp.date_paiement) DESC, SUM(sp.montant) DESC;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');

        // Fonction pour détecter les anomalies de paiement
        DB::unprepared('
            CREATE OR REPLACE FUNCTION detect_payment_anomalies()
            RETURNS TABLE(
                type_anomalie VARCHAR,
                subscription_id UUID,
                souscripteur_nom VARCHAR,
                fimeco_nom VARCHAR,
                description TEXT,
                montant_implique DECIMAL(15,2),
                date_detection TIMESTAMP
            ) AS $$
            BEGIN
                -- Retourner les paiements suspects (montants très élevés ou très faibles)
                RETURN QUERY
                WITH payment_stats AS (
                    SELECT
                        s.fimeco_id,
                        AVG(sp.montant) as montant_moyen,
                        STDDEV(sp.montant) as ecart_type
                    FROM subscription_payments sp
                    JOIN subscriptions s ON sp.subscription_id = s.id
                    WHERE sp.statut = \'valide\' AND sp.deleted_at IS NULL
                    GROUP BY s.fimeco_id
                ),
                anomalies AS (
                    SELECT
                        \'MONTANT_SUSPECT\' as type_anomalie,
                        s.id as subscription_id,
                        u.nom as souscripteur_nom,
                        f.nom as fimeco_nom,
                        CASE
                            WHEN sp.montant > (ps.montant_moyen + 3 * ps.ecart_type) THEN
                                \'Paiement anormalement élevé: \' || sp.montant || \' (moyenne: \' || ROUND(ps.montant_moyen, 2) || \')\'
                            WHEN sp.montant < (ps.montant_moyen - 2 * ps.ecart_type) AND sp.montant > 0 THEN
                                \'Paiement anormalement faible: \' || sp.montant || \' (moyenne: \' || ROUND(ps.montant_moyen, 2) || \')\'
                        END as description,
                        sp.montant,
                        sp.created_at
                    FROM subscription_payments sp
                    JOIN subscriptions s ON sp.subscription_id = s.id
                    JOIN users u ON s.souscripteur_id = u.id
                    JOIN fimecos f ON s.fimeco_id = f.id
                    JOIN payment_stats ps ON f.id = ps.fimeco_id
                    WHERE sp.statut = \'valide\'
                      AND sp.deleted_at IS NULL
                      AND ps.ecart_type > 0
                      AND (sp.montant > (ps.montant_moyen + 3 * ps.ecart_type) OR
                           sp.montant < (ps.montant_moyen - 2 * ps.ecart_type))
                      AND sp.created_at >= CURRENT_DATE - INTERVAL \'30 days\'

                    UNION ALL

                    -- Paiements en double potentiels
                    SELECT
                        \'DOUBLON_SUSPECT\' as type_anomalie,
                        s1.id as subscription_id,
                        u.nom as souscripteur_nom,
                        f.nom as fimeco_nom,
                        \'Paiements similaires détectés: \' || sp1.montant || \' le \' || sp1.date_paiement::DATE || \' et \' || sp2.date_paiement::DATE as description,
                        sp1.montant,
                        sp1.created_at
                    FROM subscription_payments sp1
                    JOIN subscription_payments sp2 ON sp1.subscription_id = sp2.subscription_id
                        AND sp1.id != sp2.id
                        AND sp1.montant = sp2.montant
                        AND ABS(EXTRACT(EPOCH FROM (sp1.date_paiement - sp2.date_paiement))) < 86400 -- Moins de 24h
                    JOIN subscriptions s1 ON sp1.subscription_id = s1.id
                    JOIN users u ON s1.souscripteur_id = u.id
                    JOIN fimecos f ON s1.fimeco_id = f.id
                    WHERE sp1.statut = \'valide\' AND sp2.statut = \'valide\'
                      AND sp1.deleted_at IS NULL AND sp2.deleted_at IS NULL
                      AND sp1.created_at >= CURRENT_DATE - INTERVAL \'7 days\'
                )
                SELECT * FROM anomalies
                ORDER BY date_detection DESC;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');

        // ===== PROCÉDURES DE MAINTENANCE POSTGRESQL =====

        // Procédure pour rafraîchir toutes les vues matérialisées
        DB::unprepared('
            CREATE OR REPLACE FUNCTION refresh_all_materialized_views()
            RETURNS void AS $$
            BEGIN
                REFRESH MATERIALIZED VIEW CONCURRENTLY mv_fimeco_dashboard;
                REFRESH MATERIALIZED VIEW CONCURRENTLY mv_payment_statistics;
                REFRESH MATERIALIZED VIEW CONCURRENTLY mv_souscripteur_performance;

                -- Log de la dernière actualisation (optionnel si la table existe)
                BEGIN
                    INSERT INTO system_logs (type, message, created_at)
                    VALUES (\'MAINTENANCE\', \'Vues matérialisées actualisées\', NOW());
                EXCEPTION
                    WHEN undefined_table THEN
                        -- Ignorer si la table system_logs n\'existe pas
                        NULL;
                END;
            EXCEPTION
                WHEN OTHERS THEN
                    -- En cas d\'erreur, rafraîchissement complet (plus lent)
                    REFRESH MATERIALIZED VIEW mv_fimeco_dashboard;
                    REFRESH MATERIALIZED VIEW mv_payment_statistics;
                    REFRESH MATERIALIZED VIEW mv_souscripteur_performance;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Procédure de nettoyage et optimisation
        DB::unprepared('
            CREATE OR REPLACE FUNCTION optimize_fimeco_tables()
            RETURNS TABLE(
                table_name TEXT,
                operation TEXT,
                result TEXT
            ) AS $$
            DECLARE
                table_record RECORD;
                deleted_count INTEGER;
            BEGIN
                -- Nettoyage des données supprimées définitivement (plus de 90 jours)
                DELETE FROM subscription_payments
                WHERE deleted_at IS NOT NULL AND deleted_at < NOW() - INTERVAL \'90 days\';

                GET DIAGNOSTICS deleted_count = ROW_COUNT;

                RETURN QUERY SELECT \'subscription_payments\'::TEXT, \'PURGE_OLD_DELETED\'::TEXT,
                    (deleted_count || \' paiements supprimés depuis plus de 90 jours nettoyés\')::TEXT;

                -- Analyse des statistiques des tables pour l\'optimiseur
                FOR table_record IN
                    SELECT schemaname, tablename
                    FROM pg_tables
                    WHERE tablename IN (\'fimecos\', \'subscriptions\', \'subscription_payments\')
                      AND schemaname = current_schema()
                LOOP
                    EXECUTE \'ANALYZE \' || quote_ident(table_record.schemaname) || \'.\' || quote_ident(table_record.tablename);

                    RETURN QUERY SELECT table_record.tablename::TEXT, \'ANALYZE\'::TEXT, \'Statistiques mises à jour\'::TEXT;
                END LOOP;

                RETURN;
            END;
            $$ LANGUAGE plpgsql;
        ');
    }

    public function down(): void
    {
        // Suppression des procédures
        DB::statement('DROP FUNCTION IF EXISTS optimize_fimeco_tables()');
        DB::statement('DROP FUNCTION IF EXISTS refresh_all_materialized_views()');
        DB::statement('DROP FUNCTION IF EXISTS detect_payment_anomalies()');
        DB::statement('DROP FUNCTION IF EXISTS get_monthly_payment_stats(UUID, DATE, DATE)');
        DB::statement('DROP FUNCTION IF EXISTS get_fimeco_complete_summary(UUID)');

        // Suppression des vues matérialisées
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_souscripteur_performance');
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_payment_statistics');
        DB::statement('DROP MATERIALIZED VIEW IF EXISTS mv_fimeco_dashboard');
    }
};
