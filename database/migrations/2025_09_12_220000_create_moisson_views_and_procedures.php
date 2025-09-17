<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vue pour le tableau de bord des moissons
        DB::unprepared("
            CREATE OR REPLACE VIEW dashboard_moissons AS
            SELECT
                m.id,
                m.theme,
                m.date,
                m.cible,
                m.montant_solde,
                m.reste,
                m.montant_supplementaire,
                m.status,
                ROUND((m.montant_solde * 100.0 / NULLIF(m.cible, 0)), 2) AS pourcentage_realise,

                -- Détails des collectes
                COALESCE(pm_stats.total_passages, 0) AS total_passages,
                COALESCE(pm_stats.nb_passages, 0) AS nb_passages,

                COALESCE(vm_stats.total_ventes, 0) AS total_ventes,
                COALESCE(vm_stats.nb_ventes, 0) AS nb_ventes,

                COALESCE(em_stats.total_engagements, 0) AS total_engagements,
                COALESCE(em_stats.nb_engagements, 0) AS nb_engagements,
                COALESCE(em_stats.nb_engagements_en_attente, 0) AS nb_engagements_en_attente,

                -- Statut de progression
                CASE
                    WHEN m.montant_solde >= m.cible THEN 'Objectif atteint'
                    WHEN m.montant_solde >= (m.cible * 0.9) THEN 'Presque atteint'
                    WHEN m.montant_solde >= (m.cible * 0.7) THEN 'Bonne progression'
                    WHEN m.montant_solde >= (m.cible * 0.5) THEN 'En cours'
                    WHEN m.montant_solde >= (m.cible * 0.3) THEN 'Début'
                    ELSE 'Très faible'
                END AS statut_progression,

                -- Jours depuis la création
                (CURRENT_DATE - m.date) AS jours_depuis_moisson,

                -- Informations temporelles
                m.created_at,
                m.updated_at

            FROM moissons m

            LEFT JOIN (
                SELECT
                    moisson_id,
                    SUM(montant_solde) AS total_passages,
                    COUNT(*) AS nb_passages
                FROM passage_moissons
                WHERE deleted_at IS NULL AND status = true
                GROUP BY moisson_id
            ) pm_stats ON m.id = pm_stats.moisson_id

            LEFT JOIN (
                SELECT
                    moisson_id,
                    SUM(montant_solde) AS total_ventes,
                    COUNT(*) AS nb_ventes
                FROM vente_moissons
                WHERE deleted_at IS NULL AND status = true
                GROUP BY moisson_id
            ) vm_stats ON m.id = vm_stats.moisson_id

            LEFT JOIN (
                SELECT
                    moisson_id,
                    SUM(montant_solde) AS total_engagements,
                    COUNT(*) AS nb_engagements,
                    COUNT(CASE WHEN reste > 0 THEN 1 END) AS nb_engagements_en_attente
                FROM engagement_moissons
                WHERE deleted_at IS NULL AND status = true
                GROUP BY moisson_id
            ) em_stats ON m.id = em_stats.moisson_id

            WHERE m.deleted_at IS NULL
            ORDER BY m.date DESC;
        ");

        // Vue détaillée pour une moisson spécifique
        DB::unprepared("
            CREATE OR REPLACE VIEW detail_moisson AS
            SELECT
                'moisson' AS type_ligne,
                m.id AS moisson_id,
                m.id AS ligne_id,
                m.theme AS description,
                m.date,
                m.cible,
                m.montant_solde,
                m.reste,
                m.montant_supplementaire,
                NULL AS categorie,
                m.status,
                m.created_at,
                m.updated_at
            FROM moissons m
            WHERE m.deleted_at IS NULL

            UNION ALL

            SELECT
                'passage' AS type_ligne,
                pm.moisson_id,
                pm.id AS ligne_id,
                'Passage ' || pm.categorie::text AS description,
                (SELECT date FROM moissons WHERE id = pm.moisson_id) AS date,
                pm.cible,
                pm.montant_solde,
                pm.reste,
                pm.montant_supplementaire,
                pm.categorie::text AS categorie,
                pm.status,
                pm.created_at,
                pm.updated_at
            FROM passage_moissons pm
            WHERE pm.deleted_at IS NULL

            UNION ALL

            SELECT
                'vente' AS type_ligne,
                vm.moisson_id,
                vm.id AS ligne_id,
                'Vente ' || vm.categorie::text AS description,
                (SELECT date FROM moissons WHERE id = vm.moisson_id) AS date,
                vm.cible,
                vm.montant_solde,
                vm.reste,
                vm.montant_supplementaire,
                vm.categorie::text AS categorie,
                vm.status,
                vm.created_at,
                vm.updated_at
            FROM vente_moissons vm
            WHERE vm.deleted_at IS NULL

            UNION ALL

            SELECT
                'engagement' AS type_ligne,
                em.moisson_id,
                em.id AS ligne_id,
                CASE
                    WHEN em.categorie = 'entite_morale' THEN 'Engagement ' || COALESCE(em.nom_entite, 'Entité morale')
                    ELSE 'Engagement personne physique'
                END AS description,
                (SELECT date FROM moissons WHERE id = em.moisson_id) AS date,
                em.cible,
                em.montant_solde,
                em.reste,
                em.montant_supplementaire,
                em.categorie::text AS categorie,
                em.status,
                em.created_at,
                em.updated_at
            FROM engagement_moissons em
            WHERE em.deleted_at IS NULL;
        ");

        // Vue pour les engagements en retard
        DB::unprepared("
            CREATE OR REPLACE VIEW engagements_en_retard AS
            SELECT
                em.id,
                em.moisson_id,
                m.theme AS theme_moisson,
                m.date AS date_moisson,
                em.categorie,
                CASE
                    WHEN em.categorie = 'entite_morale' THEN em.nom_entite
                    ELSE 'Personne physique'
                END AS donateur,
                em.cible,
                em.montant_solde,
                em.reste,
                em.date_echeance,
                em.date_rappel,
                em.telephone,
                em.email,
                CURRENT_DATE - em.date_echeance AS jours_retard,
                CASE
                    WHEN em.date_echeance < CURRENT_DATE - INTERVAL '30 days' THEN 'Critique'
                    WHEN em.date_echeance < CURRENT_DATE - INTERVAL '15 days' THEN 'Important'
                    WHEN em.date_echeance < CURRENT_DATE - INTERVAL '7 days' THEN 'Modéré'
                    ELSE 'Récent'
                END AS niveau_urgence
            FROM engagement_moissons em
            JOIN moissons m ON em.moisson_id = m.id
            WHERE em.deleted_at IS NULL
            AND m.deleted_at IS NULL
            AND em.status = true
            AND em.reste > 0
            AND em.date_echeance < CURRENT_DATE
            ORDER BY em.date_echeance ASC;
        ");

        // Procédure stockée pour obtenir les statistiques globales
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_moisson_global_stats(
                date_debut DATE DEFAULT NULL,
                date_fin DATE DEFAULT NULL
            )
            RETURNS TABLE(
                nombre_moissons INTEGER,
                objectif_total DECIMAL(15,2),
                montant_collecte_total DECIMAL(15,2),
                reste_total DECIMAL(15,2),
                supplement_total DECIMAL(15,2),
                pourcentage_realisation DECIMAL(5,2),
                moissons_objectif_atteint INTEGER,
                nombre_passages_total INTEGER,
                nombre_ventes_total INTEGER,
                nombre_engagements_total INTEGER,
                nombre_engagements_en_retard INTEGER
            )
            LANGUAGE plpgsql
            AS $$
            BEGIN
                RETURN QUERY
                SELECT
                    COUNT(m.id)::INTEGER AS nombre_moissons,
                    COALESCE(SUM(m.cible), 0) AS objectif_total,
                    COALESCE(SUM(m.montant_solde), 0) AS montant_collecte_total,
                    COALESCE(SUM(m.reste), 0) AS reste_total,
                    COALESCE(SUM(m.montant_supplementaire), 0) AS supplement_total,
                    CASE
                        WHEN SUM(m.cible) > 0 THEN ROUND((SUM(m.montant_solde) * 100.0 / SUM(m.cible)), 2)
                        ELSE 0
                    END AS pourcentage_realisation,
                    COUNT(CASE WHEN m.montant_solde >= m.cible THEN 1 END)::INTEGER AS moissons_objectif_atteint,

                    (SELECT COUNT(*) FROM passage_moissons pm
                     JOIN moissons m2 ON pm.moisson_id = m2.id
                     WHERE pm.deleted_at IS NULL AND m2.deleted_at IS NULL
                     AND (date_debut IS NULL OR m2.date >= date_debut)
                     AND (date_fin IS NULL OR m2.date <= date_fin))::INTEGER AS nombre_passages_total,

                    (SELECT COUNT(*) FROM vente_moissons vm
                     JOIN moissons m2 ON vm.moisson_id = m2.id
                     WHERE vm.deleted_at IS NULL AND m2.deleted_at IS NULL
                     AND (date_debut IS NULL OR m2.date >= date_debut)
                     AND (date_fin IS NULL OR m2.date <= date_fin))::INTEGER AS nombre_ventes_total,

                    (SELECT COUNT(*) FROM engagement_moissons em
                     JOIN moissons m2 ON em.moisson_id = m2.id
                     WHERE em.deleted_at IS NULL AND m2.deleted_at IS NULL
                     AND (date_debut IS NULL OR m2.date >= date_debut)
                     AND (date_fin IS NULL OR m2.date <= date_fin))::INTEGER AS nombre_engagements_total,

                    (SELECT COUNT(*) FROM engagements_en_retard)::INTEGER AS nombre_engagements_en_retard

                FROM moissons m
                WHERE m.deleted_at IS NULL
                AND (date_debut IS NULL OR m.date >= date_debut)
                AND (date_fin IS NULL OR m.date <= date_fin);
            END;
            $$;
        ");

        // Procédure pour générer un rapport détaillé d'une moisson
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_rapport_moisson(moisson_uuid UUID)
            RETURNS TABLE(
                moisson_id UUID,
                theme VARCHAR(250),
                date_moisson DATE,
                objectif DECIMAL(15,2),
                total_collecte DECIMAL(15,2),
                reste_a_collecter DECIMAL(15,2),
                supplement DECIMAL(15,2),
                pourcentage_realise DECIMAL(5,2),
                statut_global VARCHAR(50),

                -- Détail passages
                passages_json JSONB,
                total_passages DECIMAL(15,2),

                -- Détail ventes
                ventes_json JSONB,
                total_ventes DECIMAL(15,2),

                -- Détail engagements
                engagements_json JSONB,
                total_engagements DECIMAL(15,2),
                engagements_en_attente INTEGER
            )
            LANGUAGE plpgsql
            AS $$
            DECLARE
                moisson_exists BOOLEAN := FALSE;
            BEGIN
                -- Vérifier l'existence de la moisson
                SELECT EXISTS(SELECT 1 FROM moissons WHERE id = moisson_uuid AND deleted_at IS NULL)
                INTO moisson_exists;

                IF NOT moisson_exists THEN
                    RAISE EXCEPTION 'Moisson avec ID % introuvable', moisson_uuid;
                END IF;

                RETURN QUERY
                SELECT
                    m.id AS moisson_id,
                    m.theme,
                    m.date AS date_moisson,
                    m.cible AS objectif,
                    m.montant_solde AS total_collecte,
                    m.reste AS reste_a_collecter,
                    m.montant_supplementaire AS supplement,
                    ROUND((m.montant_solde * 100.0 / NULLIF(m.cible, 0)), 2) AS pourcentage_realise,
                    CASE
                        WHEN m.montant_solde >= m.cible THEN 'Objectif atteint'
                        WHEN m.montant_solde >= (m.cible * 0.8) THEN 'Presque atteint'
                        ELSE 'En cours'
                    END::VARCHAR(50) AS statut_global,

                    -- Passages en JSON
                    COALESCE((
                        SELECT jsonb_agg(
                            jsonb_build_object(
                                'id', pm.id,
                                'categorie', pm.categorie,
                                'cible', pm.cible,
                                'montant_solde', pm.montant_solde,
                                'reste', pm.reste,
                                'collecte_le', pm.collecte_le,
                                'status', pm.status
                            )
                        )
                        FROM passage_moissons pm
                        WHERE pm.moisson_id = m.id AND pm.deleted_at IS NULL
                    ), '[]'::jsonb) AS passages_json,

                    COALESCE((
                        SELECT SUM(pm.montant_solde)
                        FROM passage_moissons pm
                        WHERE pm.moisson_id = m.id AND pm.deleted_at IS NULL AND pm.status = true
                    ), 0) AS total_passages,

                    -- Ventes en JSON
                    COALESCE((
                        SELECT jsonb_agg(
                            jsonb_build_object(
                                'id', vm.id,
                                'categorie', vm.categorie,
                                'cible', vm.cible,
                                'montant_solde', vm.montant_solde,
                                'reste', vm.reste,
                                'collecte_le', vm.collecte_le,
                                'status', vm.status
                            )
                        )
                        FROM vente_moissons vm
                        WHERE vm.moisson_id = m.id AND vm.deleted_at IS NULL
                    ), '[]'::jsonb) AS ventes_json,

                    COALESCE((
                        SELECT SUM(vm.montant_solde)
                        FROM vente_moissons vm
                        WHERE vm.moisson_id = m.id AND vm.deleted_at IS NULL AND vm.status = true
                    ), 0) AS total_ventes,

                    -- Engagements en JSON
                    COALESCE((
                        SELECT jsonb_agg(
                            jsonb_build_object(
                                'id', em.id,
                                'categorie', em.categorie,
                                'donateur', CASE
                                    WHEN em.categorie = 'entite_morale' THEN em.nom_entite
                                    ELSE 'Personne physique'
                                END,
                                'cible', em.cible,
                                'montant_solde', em.montant_solde,
                                'reste', em.reste,
                                'date_echeance', em.date_echeance,
                                'telephone', em.telephone,
                                'email', em.email,
                                'collecter_le', em.collecter_le,
                                'status', em.status
                            )
                        )
                        FROM engagement_moissons em
                        WHERE em.moisson_id = m.id AND em.deleted_at IS NULL
                    ), '[]'::jsonb) AS engagements_json,

                    COALESCE((
                        SELECT SUM(em.montant_solde)
                        FROM engagement_moissons em
                        WHERE em.moisson_id = m.id AND em.deleted_at IS NULL AND em.status = true
                    ), 0) AS total_engagements,

                    COALESCE((
                        SELECT COUNT(*)
                        FROM engagement_moissons em
                        WHERE em.moisson_id = m.id AND em.deleted_at IS NULL AND em.status = true AND em.reste > 0
                    ), 0)::INTEGER AS engagements_en_attente

                FROM moissons m
                WHERE m.id = moisson_uuid AND m.deleted_at IS NULL;
            END;
            $$;
        ");

        // Procédure pour rafraîchir la vue matérialisée des statistiques
        DB::unprepared('
            CREATE OR REPLACE FUNCTION refresh_moisson_statistics()
            RETURNS VOID AS $$
            BEGIN
                REFRESH MATERIALIZED VIEW CONCURRENTLY moisson_statistics;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Index pour optimiser les vues
        DB::unprepared('CREATE INDEX IF NOT EXISTS idx_moissons_date_status ON moissons (date, status) WHERE deleted_at IS NULL;');
        DB::unprepared('CREATE INDEX IF NOT EXISTS idx_passage_moissons_moisson_status ON passage_moissons (moisson_id, status) WHERE deleted_at IS NULL;');
        DB::unprepared('CREATE INDEX IF NOT EXISTS idx_vente_moissons_moisson_status ON vente_moissons (moisson_id, status) WHERE deleted_at IS NULL;');
        DB::unprepared('CREATE INDEX IF NOT EXISTS idx_engagement_moissons_moisson_status ON engagement_moissons (moisson_id, status) WHERE deleted_at IS NULL;');
        DB::unprepared('CREATE INDEX IF NOT EXISTS idx_engagement_moissons_echeance ON engagement_moissons (date_echeance, reste) WHERE deleted_at IS NULL AND status = true;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les vues
        DB::unprepared('DROP VIEW IF EXISTS dashboard_moissons;');
        DB::unprepared('DROP VIEW IF EXISTS detail_moisson;');
        DB::unprepared('DROP VIEW IF EXISTS engagements_en_retard;');

        // Supprimer les fonctions
        DB::unprepared('DROP FUNCTION IF EXISTS get_moisson_global_stats(DATE, DATE);');
        DB::unprepared('DROP FUNCTION IF EXISTS get_rapport_moisson(UUID);');
        DB::unprepared('DROP FUNCTION IF EXISTS refresh_moisson_statistics();');
    }
};
