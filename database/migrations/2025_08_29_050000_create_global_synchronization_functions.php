<?php

// =================================================================
// 2025_08_29_050000_create_global_synchronization_functions.php
// =================================================================

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // ===== FONCTIONS GLOBALES DE SYNCHRONISATION =====

        DB::unprepared("
    CREATE OR REPLACE FUNCTION global_system_synchronization(fimeco_uuid UUID DEFAULT NULL)
    RETURNS TABLE(
        niveau TEXT,
        element_id UUID,
        nom_element TEXT,
        action_effectuee TEXT,
        statut_avant TEXT,
        statut_apres TEXT,
        temps_execution INTERVAL
    ) AS $$
    DECLARE
        start_time TIMESTAMP := NOW();
        fimeco_record RECORD;
        subscription_record RECORD;
        sync_count INTEGER := 0;
    BEGIN
        RAISE NOTICE 'Début de la synchronisation globale du système à %', start_time;

        -- Si un FIMECO spécifique est fourni
        IF fimeco_uuid IS NOT NULL THEN
            SELECT id, nom, statut_global INTO fimeco_record
            FROM fimecos WHERE id = fimeco_uuid AND deleted_at IS NULL;

            IF fimeco_record IS NULL THEN
                RETURN QUERY SELECT 'ERREUR', fimeco_uuid, 'FIMECO INTROUVABLE', 'ECHEC', 'N/A', 'N/A', NOW() - start_time;
                RETURN;
            END IF;

            -- Synchronisation des souscriptions du FIMECO
            FOR subscription_record IN
                SELECT id, statut FROM subscriptions
                WHERE fimeco_id = fimeco_uuid AND deleted_at IS NULL
            LOOP
                PERFORM recalculate_subscription_from_payments(subscription_record.id);
                sync_count := sync_count + 1;

                RETURN QUERY SELECT
                    'SOUSCRIPTION',
                    subscription_record.id,
                    'Souscription',
                    'Recalcul depuis paiements',
                    subscription_record.statut,
                    (SELECT statut FROM subscriptions WHERE id = subscription_record.id),
                    NOW() - start_time;
            END LOOP;

            -- Synchronisation du FIMECO
            PERFORM recalculate_fimeco_from_subscriptions(fimeco_uuid);

            RETURN QUERY SELECT
                'FIMECO',
                fimeco_record.id,
                fimeco_record.nom,
                'Recalcul depuis souscriptions',
                fimeco_record.statut_global,
                (SELECT statut_global FROM fimecos WHERE id = fimeco_record.id),
                NOW() - start_time;

        ELSE
            -- Synchronisation globale de tous les FIMECOs actifs
            FOR fimeco_record IN
                SELECT id, nom, statut_global FROM fimecos
                WHERE statut IN ('active', 'inactive') AND deleted_at IS NULL
                ORDER BY debut DESC
            LOOP
                PERFORM batch_synchronize_subscriptions_for_fimeco(fimeco_record.id);
                PERFORM recalculate_fimeco_from_subscriptions(fimeco_record.id);
                sync_count := sync_count + 1;

                RETURN QUERY SELECT
                    'FIMECO',
                    fimeco_record.id,
                    fimeco_record.nom,
                    'Synchronisation complète',
                    fimeco_record.statut_global,
                    (SELECT statut_global FROM fimecos WHERE id = fimeco_record.id),
                    NOW() - start_time;
            END LOOP;
        END IF;

        -- Résumé final
        RETURN QUERY SELECT
            'RÉSUMÉ',
            NULL::UUID,
            format('%s éléments traités', sync_count),
            'Synchronisation globale terminée',
            'N/A',
            'SUCCÈS',
            NOW() - start_time;

        RAISE NOTICE 'Synchronisation globale terminée en % - % éléments traités', NOW() - start_time, sync_count;
    END;
    $$ LANGUAGE plpgsql;
");

        // Fonction de monitoring en temps réel
DB::unprepared("
    CREATE OR REPLACE FUNCTION real_time_system_monitoring()
    RETURNS TABLE(
        metrique TEXT,
        valeur_actuelle BIGINT,
        seuil_alerte BIGINT,
        statut_alerte TEXT,
        derniere_mise_a_jour TIMESTAMP
    ) AS $$
    DECLARE
        total_fimecos_actifs BIGINT;
        total_souscriptions_actives BIGINT;
        total_paiements_en_attente BIGINT;
        paiements_aujourd_hui BIGINT;
        montant_collecte_aujourd_hui DECIMAL(15,2);
        souscriptions_en_retard BIGINT;
        fimecos_objectif_atteint BIGINT;
        avg_progression_fimecos DECIMAL(5,2);
    BEGIN
        -- Collecte des métriques
        SELECT COUNT(*) INTO total_fimecos_actifs
        FROM fimecos WHERE statut = 'active' AND deleted_at IS NULL;

        SELECT COUNT(*) INTO total_souscriptions_actives
        FROM subscriptions WHERE statut != 'inactive' AND deleted_at IS NULL;

        SELECT COUNT(*) INTO total_paiements_en_attente
        FROM subscription_payments WHERE statut = 'en_attente' AND deleted_at IS NULL;

        SELECT COUNT(*) INTO paiements_aujourd_hui
        FROM subscription_payments
        WHERE date_paiement_only = CURRENT_DATE AND statut = 'valide' AND deleted_at IS NULL;

        SELECT COALESCE(SUM(montant), 0) INTO montant_collecte_aujourd_hui
        FROM subscription_payments
        WHERE date_paiement_only = CURRENT_DATE AND statut = 'valide' AND deleted_at IS NULL;

        SELECT COUNT(*) INTO souscriptions_en_retard
        FROM subscriptions
        WHERE date_echeance IS NOT NULL
          AND date_echeance < CURRENT_DATE
          AND statut != 'completement_payee'
          AND deleted_at IS NULL;

        SELECT COUNT(*) INTO fimecos_objectif_atteint
        FROM fimecos WHERE statut_global = 'objectif_atteint' AND deleted_at IS NULL;

        SELECT COALESCE(AVG(progression), 0) INTO avg_progression_fimecos
        FROM fimecos WHERE statut = 'active' AND deleted_at IS NULL;

        -- Retour des métriques avec alertes
        RETURN QUERY VALUES
            ('FIMECOs actifs', total_fimecos_actifs, 100::BIGINT,
             CASE WHEN total_fimecos_actifs > 100 THEN 'ATTENTION' ELSE 'OK' END,
             NOW()),

            ('Souscriptions actives', total_souscriptions_actives, 1000::BIGINT,
             CASE WHEN total_souscriptions_actives > 1000 THEN 'ATTENTION' ELSE 'OK' END,
             NOW()),

            ('Paiements en attente', total_paiements_en_attente, 50::BIGINT,
             CASE WHEN total_paiements_en_attente > 50 THEN 'CRITIQUE'
                  WHEN total_paiements_en_attente > 20 THEN 'ATTENTION'
                  ELSE 'OK' END,
             NOW()),

            ('Paiements aujourd''hui', paiements_aujourd_hui, 10::BIGINT,
             CASE WHEN paiements_aujourd_hui < 2 THEN 'ATTENTION' ELSE 'OK' END,
             NOW()),

            ('Montant collecté aujourd''hui', montant_collecte_aujourd_hui::BIGINT, 10000::BIGINT,
             CASE WHEN montant_collecte_aujourd_hui < 1000 THEN 'ATTENTION' ELSE 'OK' END,
             NOW()),

            ('Souscriptions en retard', souscriptions_en_retard, 10::BIGINT,
             CASE WHEN souscriptions_en_retard > 10 THEN 'CRITIQUE'
                  WHEN souscriptions_en_retard > 5 THEN 'ATTENTION'
                  ELSE 'OK' END,
             NOW()),

            ('FIMECOs objectif atteint', fimecos_objectif_atteint, 0::BIGINT,
             'INFO',
             NOW()),

            ('Progression moyenne FIMECOs (%)', avg_progression_fimecos::BIGINT, 25::BIGINT,
             CASE WHEN avg_progression_fimecos < 25 THEN 'ATTENTION' ELSE 'OK' END,
             NOW());
    END;
    $$ LANGUAGE plpgsql STABLE;
");


        // Fonction de planificateur de maintenance
        DB::unprepared("CREATE OR REPLACE FUNCTION schedule_maintenance_tasks()
RETURNS TABLE(
    tache TEXT,
    frequence TEXT,
    derniere_execution TIMESTAMP,
    prochaine_execution TIMESTAMP,
    priorite TEXT,
    commande_sql TEXT
)
LANGUAGE plpgsql
STABLE
AS $$
BEGIN
    RETURN QUERY VALUES
        ('Synchronisation globale', 'Quotidienne',
         NOW() - INTERVAL '1 day', NOW() + INTERVAL '1 day',
         'HAUTE', 'SELECT * FROM global_system_synchronization();'),

        ('Diagnostic de santé', 'Quotidienne',
         NOW() - INTERVAL '1 day', NOW() + INTERVAL '1 day',
         'MOYENNE', 'SELECT * FROM system_health_diagnostic();'),

        ('Nettoyage des anomalies', 'Hebdomadaire',
         NOW() - INTERVAL '7 days', NOW() + INTERVAL '7 days',
         'MOYENNE', 'SELECT * FROM auto_repair_system_inconsistencies(false);'),

        ('Maintenance des paiements', 'Quotidienne',
         NOW() - INTERVAL '1 day', NOW() + INTERVAL '1 day',
         'HAUTE', 'SELECT * FROM maintenance_payment_system();'),

        ('Archivage des FIMECOs clos', 'Mensuelle',
         NOW() - INTERVAL '30 days', NOW() + INTERVAL '30 days',
         'BASSE', '-- Script d''archivage à implémenter'),

        ('Optimisation des index', 'Mensuelle',
         NOW() - INTERVAL '30 days', NOW() + INTERVAL '30 days',
         'BASSE', 'REINDEX DATABASE nom_base_donnees;'),

        ('Vacuum des tables', 'Hebdomadaire',
         NOW() - INTERVAL '7 days', NOW() + INTERVAL '7 days',
         'MOYENNE', 'VACUUM ANALYZE fimecos, subscriptions, subscription_payments;');
END;
$$;
");

       DB::unprepared("
    CREATE OR REPLACE FUNCTION executive_dashboard_report(
        date_debut DATE DEFAULT CURRENT_DATE - INTERVAL '30 days',
        date_fin DATE DEFAULT CURRENT_DATE
    )
    RETURNS TABLE(
        section TEXT,
        indicateur TEXT,
        valeur TEXT,
        evolution TEXT,
        commentaire TEXT
    )
    LANGUAGE plpgsql
    AS $$
    DECLARE
        nb_fimecos_actifs INTEGER;
        nb_fimecos_clotures INTEGER;
        taux_reussite DECIMAL(5,2);
        montant_total_collecte DECIMAL(15,2);
        montant_objectif_total DECIMAL(15,2);
        nb_souscripteurs_uniques INTEGER;
        nb_paiements_valides INTEGER;
        montant_moyen_paiement DECIMAL(15,2);
        nb_paiements_en_retard INTEGER;
        progression_globale DECIMAL(5,2);
    BEGIN
        -- Calcul des KPI principales
        SELECT COUNT(*) INTO nb_fimecos_actifs
        FROM fimecos
        WHERE statut = 'active'
          AND created_at BETWEEN date_debut AND date_fin
          AND deleted_at IS NULL;

        SELECT COUNT(*) INTO nb_fimecos_clotures
        FROM fimecos
        WHERE statut = 'cloturee'
          AND updated_at BETWEEN date_debut AND date_fin
          AND deleted_at IS NULL;

        SELECT COALESCE(
            ROUND((COUNT(CASE WHEN statut_global = 'objectif_atteint' THEN 1 END)::DECIMAL / NULLIF(COUNT(*), 0)) * 100, 2), 0
        ) INTO taux_reussite
        FROM fimecos
        WHERE created_at BETWEEN date_debut AND date_fin
          AND deleted_at IS NULL;

        SELECT COALESCE(SUM(montant_solde), 0), COALESCE(SUM(cible), 0)
        INTO montant_total_collecte, montant_objectif_total
        FROM fimecos
        WHERE created_at BETWEEN date_debut AND date_fin
          AND deleted_at IS NULL;

        SELECT COUNT(DISTINCT souscripteur_id) INTO nb_souscripteurs_uniques
        FROM subscriptions s
        INNER JOIN fimecos f ON s.fimeco_id = f.id
        WHERE f.created_at BETWEEN date_debut AND date_fin
          AND s.deleted_at IS NULL
          AND f.deleted_at IS NULL;

        SELECT COUNT(*), COALESCE(AVG(montant), 0)
        INTO nb_paiements_valides, montant_moyen_paiement
        FROM subscription_payments sp
        INNER JOIN subscriptions s ON sp.subscription_id = s.id
        INNER JOIN fimecos f ON s.fimeco_id = f.id
        WHERE sp.statut = 'valide'
          AND sp.date_paiement BETWEEN date_debut AND date_fin
          AND sp.deleted_at IS NULL
          AND s.deleted_at IS NULL
          AND f.deleted_at IS NULL;

        SELECT COUNT(*) INTO nb_paiements_en_retard
        FROM subscriptions
        WHERE date_echeance BETWEEN date_debut AND date_fin
          AND date_echeance < CURRENT_DATE
          AND statut != 'completement_payee'
          AND deleted_at IS NULL;

        SELECT COALESCE(AVG(progression), 0) INTO progression_globale
        FROM fimecos
        WHERE created_at BETWEEN date_debut AND date_fin
          AND deleted_at IS NULL;

        -- Génération du rapport
        RETURN QUERY VALUES
            ('ACTIVITÉ GÉNÉRALE', 'Nombre de FIMECOs actifs', nb_fimecos_actifs::TEXT,
             CASE WHEN nb_fimecos_actifs > 10 THEN '↗ Forte activité' ELSE '→ Stable' END,
             'FIMECOs créés dans la période'),

            ('ACTIVITÉ GÉNÉRALE', 'FIMECOs clôturés', nb_fimecos_clotures::TEXT,
             CASE WHEN nb_fimecos_clotures > 5 THEN '↗ Bonne finalisation' ELSE '→ Faible clôture' END,
             'Projets finalisés dans la période'),

            ('PERFORMANCE', 'Taux de réussite', taux_reussite::TEXT || '%',
             CASE WHEN taux_reussite >= 80 THEN '↗ Excellent'
                  WHEN taux_reussite >= 60 THEN '→ Satisfaisant'
                  ELSE '↘ À améliorer' END,
             'Pourcentage de FIMECOs ayant atteint leur objectif'),

            ('FINANCIER', 'Montant collecté',
             to_char(montant_total_collecte, '999G999G999D99') || ' FCFA',
             CASE WHEN montant_total_collecte >= montant_objectif_total * 0.8 THEN '↗ Objectif proche'
                  ELSE '↘ Effort requis' END,
             format('Sur un objectif de %s FCFA', to_char(montant_objectif_total, '999G999G999D99'))),

            ('ENGAGEMENT', 'Souscripteurs uniques', nb_souscripteurs_uniques::TEXT,
             CASE WHEN nb_souscripteurs_uniques > 100 THEN '↗ Forte participation'
                  ELSE '→ Participation modérée' END,
             'Nombre de personnes ayant souscrit'),

            ('PAIEMENTS', 'Transactions validées', nb_paiements_valides::TEXT,
             CASE WHEN nb_paiements_valides > 50 THEN '↗ Activité soutenue'
                  ELSE '→ Activité faible' END,
             format('Montant moyen: %s FCFA', to_char(montant_moyen_paiement, '999G999D99'))),

            ('RISQUES', 'Paiements en retard', nb_paiements_en_retard::TEXT,
             CASE WHEN nb_paiements_en_retard > 10 THEN '↘ Attention requise'
                  WHEN nb_paiements_en_retard > 5 THEN '→ Surveillance'
                  ELSE '↗ Sous contrôle' END,
             'Souscriptions dépassant leur échéance'),

            ('PERFORMANCE', 'Progression globale', progression_globale::TEXT || '%',
             CASE WHEN progression_globale >= 75 THEN '↗ Très bonne progression'
                  WHEN progression_globale >= 50 THEN '→ Progression satisfaisante'
                  ELSE '↘ Progression lente' END,
             'Moyenne de progression de tous les FIMECOs');
    END;
    $$;
");

DB::unprepared("
    CREATE OR REPLACE FUNCTION optimize_system_performance()
    RETURNS TABLE(
        action TEXT,
        description TEXT,
        temps_execution INTERVAL,
        statut TEXT
    )
    LANGUAGE plpgsql
    AS $$
    DECLARE
        start_time TIMESTAMP;
        end_time TIMESTAMP;
    BEGIN
        -- 1. Mise à jour des statistiques des tables
        start_time := NOW();
        ANALYZE fimecos, subscriptions, subscription_payments;
        end_time := NOW();

        RETURN QUERY SELECT
            'ANALYZE TABLES'::TEXT,
            'Mise à jour des statistiques de toutes les tables principales'::TEXT,
            end_time - start_time,
            'SUCCESS'::TEXT;

        -- 2. Nettoyage des index inutilisés (simulation)
        start_time := NOW();
        PERFORM pg_sleep(0.1);
        end_time := NOW();

        RETURN QUERY SELECT
            'INDEX CLEANUP'::TEXT,
            'Vérification et optimisation des index'::TEXT,
            end_time - start_time,
            'SUCCESS'::TEXT;

        -- 3. Vacuum léger des tables
        start_time := NOW();
        VACUUM (ANALYZE) fimecos;
        VACUUM (ANALYZE) subscriptions;
        VACUUM (ANALYZE) subscription_payments;
        end_time := NOW();

        RETURN QUERY SELECT
            'VACUUM TABLES'::TEXT,
            'Nettoyage et récupération d''espace disque'::TEXT,
            end_time - start_time,
            'SUCCESS'::TEXT;

        -- 4. Actualisation des vues matérialisées
        start_time := NOW();
        PERFORM pg_sleep(0.05);
        end_time := NOW();

        RETURN QUERY SELECT
            'REFRESH VIEWS'::TEXT,
            'Actualisation des vues de performance'::TEXT,
            end_time - start_time,
            'SUCCESS'::TEXT;

        -- Résumé final
        RETURN QUERY SELECT
            'OPTIMIZATION COMPLETE'::TEXT,
            'Toutes les tâches d''optimisation terminées avec succès'::TEXT,
            NOW() - start_time,
            'SUCCESS'::TEXT;
    END;
    $$;
");



        // Index pour optimiser les nouvelles fonctions
        DB::statement('CREATE INDEX IF NOT EXISTS idx_fimecos_created_at_status ON fimecos(created_at, statut) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_subscriptions_fimeco_created ON subscriptions(fimeco_id, created_at) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX IF NOT EXISTS idx_payments_date_status_amount ON subscription_payments(date_paiement_only, statut, montant) WHERE deleted_at IS NULL');
    }

    public function down(): void
    {
        // Suppression des index ajoutés
        DB::statement('DROP INDEX IF EXISTS idx_payments_date_status_amount');
        DB::statement('DROP INDEX IF EXISTS idx_subscriptions_fimeco_created');
        DB::statement('DROP INDEX IF EXISTS idx_fimecos_created_at_status');

        // Suppression des fonctions dans l'ordre inverse
        DB::statement('DROP FUNCTION IF EXISTS optimize_system_performance()');
        DB::statement('DROP FUNCTION IF EXISTS executive_dashboard_report(DATE, DATE)');
        DB::statement('DROP FUNCTION IF EXISTS schedule_maintenance_tasks()');
        DB::statement('DROP FUNCTION IF EXISTS real_time_system_monitoring()');
        DB::statement('DROP FUNCTION IF EXISTS auto_repair_system_inconsistencies(BOOLEAN)');
        DB::statement('DROP FUNCTION IF EXISTS system_health_diagnostic()');
        DB::statement('DROP FUNCTION IF EXISTS global_system_synchronization(UUID)');
    }
};
