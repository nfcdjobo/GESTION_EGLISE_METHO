<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     * Cette migration doit être exécutée APRÈS la création de la table 'fonds'
     */
    public function up(): void
    {
        // Vérifier si la table fonds existe avant de recréer les vues
        if (!Schema::hasTable('fonds')) {
            throw new Exception('La table "fonds" doit exister avant d\'exécuter cette migration. Créez d\'abord la table fonds.');
        }

        // Nettoyer les vues existantes
        DB::statement("DROP VIEW IF EXISTS projets_action_requise");
        DB::statement("DROP VIEW IF EXISTS statistiques_projets");
        DB::statement("DROP VIEW IF EXISTS projets_dons_ouverts");
        DB::statement("DROP VIEW IF EXISTS projets_actifs");

        // Recréer les vues avec les références à la table fonds
        DB::statement('
            CREATE VIEW projets_actifs AS
            SELECT
                p.*,
                CONCAT(resp.prenom, \' \', resp.nom) AS nom_responsable,
                CONCAT(coord.prenom, \' \', coord.nom) AS nom_coordinateur,
                CONCAT(chef.prenom, \' \', chef.nom) AS nom_chef_projet,
                COALESCE(f.budget_collecte, 0) AS budget_collecte,
                CASE
                    WHEN p.budget_prevu > 0 AND f.budget_collecte IS NOT NULL
                    THEN ROUND((f.budget_collecte::numeric / p.budget_prevu::numeric) * 100, 2)
                    ELSE 0
                END AS pourcentage_financement,
                CASE
                    WHEN p.budget_prevu > 0 AND f.budget_collecte IS NOT NULL
                    THEN (p.budget_prevu - f.budget_collecte)
                    ELSE p.budget_prevu
                END AS montant_restant,
                (p.date_fin_prevue - CURRENT_DATE) AS jours_restants
            FROM projets p
            LEFT JOIN users resp ON p.responsable_id = resp.id
            LEFT JOIN users coord ON p.coordinateur_id = coord.id
            LEFT JOIN users chef ON p.chef_projet_id = chef.id
            LEFT JOIN (
                SELECT
                    projet_id,
                    SUM(CASE WHEN statut = \'validee\' THEN montant ELSE 0 END) as budget_collecte
                FROM fonds
                WHERE deleted_at IS NULL
                GROUP BY projet_id
            ) f ON p.id = f.projet_id
            WHERE p.statut IN (\'en_cours\', \'planification\', \'en_attente\', \'recherche_financement\')
            AND p.deleted_at IS NULL
            ORDER BY
                CASE p.priorite
                    WHEN \'critique\' THEN 1
                    WHEN \'urgente\' THEN 2
                    WHEN \'haute\' THEN 3
                    WHEN \'normale\' THEN 4
                    WHEN \'faible\' THEN 5
                END,
                p.date_debut ASC NULLS LAST
        ');

        DB::statement('
            CREATE VIEW projets_dons_ouverts AS
            SELECT
                p.id,
                p.nom_projet,
                p.code_projet,
                p.description,
                p.type_projet,
                p.budget_prevu,
                COALESCE(f.budget_collecte, 0) AS budget_collecte,
                p.image_principale,
                p.message_promotion,
                CASE
                    WHEN p.budget_prevu > 0 AND f.budget_collecte IS NOT NULL
                    THEN ROUND((f.budget_collecte::numeric / p.budget_prevu::numeric) * 100, 2)
                    ELSE 0
                END AS pourcentage_financement,
                CASE
                    WHEN p.budget_prevu > 0 AND f.budget_collecte IS NOT NULL
                    THEN (p.budget_prevu - f.budget_collecte)
                    ELSE p.budget_prevu
                END AS montant_restant,
                CONCAT(resp.prenom, \' \', resp.nom) AS nom_responsable,
                p.localisation
            FROM projets p
            LEFT JOIN users resp ON p.responsable_id = resp.id
            LEFT JOIN (
                SELECT
                    projet_id,
                    SUM(CASE WHEN statut = \'validee\' THEN montant ELSE 0 END) as budget_collecte
                FROM fonds
                WHERE deleted_at IS NULL
                GROUP BY projet_id
            ) f ON p.id = f.projet_id
            WHERE p.ouvert_aux_dons = true
            AND p.visible_public = true
            AND p.statut IN (\'en_cours\', \'planification\', \'recherche_financement\')
            AND p.deleted_at IS NULL
            ORDER BY
                CASE p.priorite
                    WHEN \'critique\' THEN 1
                    WHEN \'urgente\' THEN 2
                    WHEN \'haute\' THEN 3
                    WHEN \'normale\' THEN 4
                    WHEN \'faible\' THEN 5
                END,
                p.pourcentage_completion ASC
        ');

        DB::statement('
            CREATE VIEW statistiques_projets AS
            SELECT
                p.type_projet,
                p.statut,
                COUNT(*) AS nombre_projets,
                AVG(p.pourcentage_completion) AS completion_moyenne,
                SUM(p.budget_prevu) AS budget_total_prevu,
                SUM(COALESCE(f.budget_collecte, 0)) AS budget_total_collecte,
                SUM(p.budget_depense) AS budget_total_depense,
                AVG(p.note_satisfaction) AS satisfaction_moyenne,
                COUNT(*) FILTER (WHERE p.statut = \'termine\') AS projets_termines,
                COUNT(*) FILTER (WHERE p.date_fin_prevue < CURRENT_DATE AND p.statut NOT IN (\'termine\', \'annule\', \'archive\')) AS projets_retard
            FROM projets p
            LEFT JOIN (
                SELECT
                    projet_id,
                    SUM(CASE WHEN statut = \'validee\' THEN montant ELSE 0 END) as budget_collecte
                FROM fonds
                WHERE deleted_at IS NULL
                GROUP BY projet_id
            ) f ON p.id = f.projet_id
            WHERE p.deleted_at IS NULL
            GROUP BY p.type_projet, p.statut
            ORDER BY nombre_projets DESC
        ');

        DB::statement('
            CREATE VIEW projets_action_requise AS
            SELECT
                p.id,
                p.nom_projet,
                p.code_projet,
                p.statut,
                p.statut_precedent,
                p.priorite,
                p.date_fin_prevue,
                p.pourcentage_completion,
                CONCAT(resp.prenom, \' \', resp.nom) AS nom_responsable,
                CASE
                    WHEN p.necessite_approbation = true AND p.approuve_par IS NULL THEN \'approbation_requise\'
                    WHEN p.date_fin_prevue < CURRENT_DATE AND p.statut NOT IN (\'termine\', \'annule\', \'archive\') THEN \'en_retard\'
                    WHEN p.prochaine_evaluation <= CURRENT_DATE THEN \'evaluation_requise\'
                    WHEN p.statut = \'planification\' AND p.budget_prevu > 0 AND COALESCE(f.budget_collecte, 0) < COALESCE(p.budget_minimum, p.budget_prevu) THEN \'financement_requis\'
                    WHEN p.statut = \'recherche_financement\' AND COALESCE(f.budget_collecte, 0) >= COALESCE(p.budget_minimum, p.budget_prevu) THEN \'financement_atteint\'
                    ELSE \'suivi_normal\'
                END AS type_action,
                COALESCE(f.budget_collecte, 0) AS budget_collecte
            FROM projets p
            LEFT JOIN users resp ON p.responsable_id = resp.id
            LEFT JOIN (
                SELECT
                    projet_id,
                    SUM(CASE WHEN statut = \'validee\' THEN montant ELSE 0 END) as budget_collecte
                FROM fonds
                WHERE deleted_at IS NULL
                GROUP BY projet_id
            ) f ON p.id = f.projet_id
            WHERE p.deleted_at IS NULL
            AND (
                (p.necessite_approbation = true AND p.approuve_par IS NULL) OR
                (p.date_fin_prevue < CURRENT_DATE AND p.statut NOT IN (\'termine\', \'annule\', \'archive\')) OR
                (p.prochaine_evaluation <= CURRENT_DATE) OR
                (p.statut = \'planification\' AND p.budget_prevu > 0 AND COALESCE(f.budget_collecte, 0) < COALESCE(p.budget_minimum, p.budget_prevu)) OR
                (p.statut = \'recherche_financement\' AND COALESCE(f.budget_collecte, 0) >= COALESCE(p.budget_minimum, p.budget_prevu))
            )
            ORDER BY
                CASE p.priorite
                    WHEN \'critique\' THEN 1
                    WHEN \'urgente\' THEN 2
                    WHEN \'haute\' THEN 3
                    WHEN \'normale\' THEN 4
                    WHEN \'faible\' THEN 5
                END,
                p.date_fin_prevue ASC NULLS LAST
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues avec références à fonds
        DB::statement("DROP VIEW IF EXISTS projets_action_requise");
        DB::statement("DROP VIEW IF EXISTS statistiques_projets");
        DB::statement("DROP VIEW IF EXISTS projets_dons_ouverts");
        DB::statement("DROP VIEW IF EXISTS projets_actifs");

        // Recréer les vues sans références à fonds (versions de base)
        DB::statement('
            CREATE VIEW projets_actifs AS
            SELECT
                p.*,
                CONCAT(resp.prenom, \' \', resp.nom) AS nom_responsable,
                CONCAT(coord.prenom, \' \', coord.nom) AS nom_coordinateur,
                CONCAT(chef.prenom, \' \', chef.nom) AS nom_chef_projet,
                0 AS budget_collecte,
                0 AS pourcentage_financement,
                p.budget_prevu AS montant_restant,
                (p.date_fin_prevue - CURRENT_DATE) AS jours_restants
            FROM projets p
            LEFT JOIN users resp ON p.responsable_id = resp.id
            LEFT JOIN users coord ON p.coordinateur_id = coord.id
            LEFT JOIN users chef ON p.chef_projet_id = chef.id
            WHERE p.statut IN (\'en_cours\', \'planification\', \'en_attente\', \'recherche_financement\')
            AND p.deleted_at IS NULL
            ORDER BY
                CASE p.priorite
                    WHEN \'critique\' THEN 1
                    WHEN \'urgente\' THEN 2
                    WHEN \'haute\' THEN 3
                    WHEN \'normale\' THEN 4
                    WHEN \'faible\' THEN 5
                END,
                p.date_debut ASC NULLS LAST
        ');

        // Ajouter les autres vues de base ici si nécessaire...
    }
};
