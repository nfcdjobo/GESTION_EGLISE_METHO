<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projets', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Informations de base du projet
            $table->string('nom_projet', 200)->comment('Nom du projet');
            $table->string('code_projet', 50)->unique()->comment('Code unique du projet');
            $table->text('description')->nullable()->comment('Description détaillée du projet');
            $table->text('objectif')->nullable()->comment('Objectifs du projet');
            $table->text('contexte')->nullable()->comment('Contexte et justification');

            // Type et catégorie
            $table->enum('type_projet', [
                'construction',        // Projet de construction
                'renovation',          // Rénovation
                'social',             // Projet social
                'evangelisation',     // Évangélisation
                'formation',          // Formation
                'mission',            // Mission
                'equipement',         // Achat d'équipement
                'technologie',        // Projet technologique
                'communautaire',      // Projet communautaire
                'humanitaire',        // Action humanitaire
                'education',          // Projet éducatif
                'sante',              // Projet de santé
                'autre'               // Autre type
            ])->comment('Type de projet');

            $table->enum('categorie', [
                'infrastructure',     // Infrastructure
                'spirituel',          // Spirituel
                'social',             // Social
                'educatif',           // Éducatif
                'technique',          // Technique
                'administratif'       // Administratif
            ])->comment('Catégorie du projet');

            // Budget et finances - CORRIGÉ : budget_collecte retiré car calculé automatiquement
            $table->decimal('budget_prevu', 15, 2)->nullable()->comment('Budget prévisionnel total');
            $table->decimal('budget_depense', 15, 2)->default(0)->comment('Montant déjà dépensé');
            $table->decimal('budget_minimum', 15, 2)->nullable()->comment('Budget minimum pour démarrer');
            $table->string('devise', 3)->default('XOF')->comment('Devise du budget');
            $table->json('detail_budget')->nullable()->comment('Détail du budget par poste (JSON)');
            $table->json('sources_financement')->nullable()->comment('Sources de financement (JSON)');

            // Planification temporelle
            $table->date('date_creation')->nullable()->comment('Date de création du projet');
            $table->date('date_debut')->nullable()->comment('Date de début du projet');
            $table->date('date_fin_prevue')->nullable()->comment('Date de fin prévue');
            $table->date('date_fin_reelle')->nullable()->comment('Date de fin réelle');
            $table->integer('duree_prevue_jours')->nullable()->comment('Durée prévue en jours');
            $table->integer('duree_reelle_jours')->nullable()->comment('Durée réelle en jours');

            // Responsables et équipe
            $table->uuid('responsable_id')->nullable()->comment('Responsable principal du projet');
            $table->uuid('coordinateur_id')->nullable()->comment('Coordinateur du projet');
            $table->uuid('chef_projet_id')->nullable()->comment('Chef de projet');
            $table->json('equipe_projet')->nullable()->comment('Équipe du projet (JSON)');
            $table->json('partenaires')->nullable()->comment('Partenaires du projet (JSON)');
            $table->json('beneficiaires')->nullable()->comment('Bénéficiaires du projet (JSON)');

            // Localisation et géographie
            $table->string('localisation', 200)->nullable()->comment('Localisation du projet');
            $table->text('adresse_complete')->nullable()->comment('Adresse complète');
            $table->string('ville', 100)->nullable()->comment('Ville');
            $table->string('region', 100)->nullable()->comment('Région');
            $table->string('pays', 100)->default('CI')->comment('Pays');
            $table->decimal('latitude', 10, 8)->nullable()->comment('Latitude GPS');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Longitude GPS');

            // Statut et progression - AJOUTÉ : statut_precedent pour l'historique
            $table->enum('statut', [
                'conception',         // En conception
                'planification',      // En planification
                'recherche_financement', // Recherche de financement
                'en_attente',        // En attente de démarrage
                'en_cours',          // En cours d'exécution
                'suspendu',          // Suspendu
                'termine',           // Terminé
                'annule',            // Annulé
                'archive'            // Archivé
            ])->default('conception')->comment('Statut du projet');

            $table->enum('statut_precedent', [
                'conception',
                'planification',
                'recherche_financement',
                'en_attente',
                'en_cours',
                'suspendu',
                'termine',
                'annule',
                'archive'
            ])->nullable()->comment('Statut précédent (historique)');

            $table->enum('priorite', [
                'faible',
                'normale',
                'haute',
                'urgente',
                'critique'
            ])->default('normale')->comment('Niveau de priorité');

            $table->decimal('pourcentage_completion', 5, 2)->default(0)->comment('Pourcentage d\'avancement');
            $table->text('derniere_activite')->nullable()->comment('Dernière activité enregistrée');
            $table->date('derniere_mise_a_jour')->nullable()->comment('Date de dernière mise à jour');

            // Approbation et validation
            $table->uuid('approuve_par')->nullable()->comment('Qui a approuvé le projet');
            $table->timestamp('approuve_le')->nullable()->comment('Date d\'approbation');
            $table->text('commentaires_approbation')->nullable()->comment('Commentaires d\'approbation');
            $table->boolean('necessite_approbation')->default(true)->comment('Nécessite une approbation');

            // Objectifs et métriques
            $table->json('objectifs_mesurables')->nullable()->comment('Objectifs mesurables (JSON)');
            $table->json('indicateurs_succes')->nullable()->comment('Indicateurs de succès (JSON)');
            $table->json('risques_identifies')->nullable()->comment('Risques identifiés (JSON)');
            $table->json('mesures_mitigation')->nullable()->comment('Mesures de mitigation (JSON)');

            // Documentation et ressources
            $table->json('documents_joints')->nullable()->comment('Documents joints (JSON)');
            $table->json('photos_projet')->nullable()->comment('Photos du projet (JSON)');
            $table->string('site_web')->nullable()->comment('Site web du projet');
            $table->json('liens_utiles')->nullable()->comment('Liens utiles (JSON)');
            $table->text('manuel_procedure')->nullable()->comment('Manuel de procédure');

            // Communication et visibilité
            $table->boolean('visible_public')->default(false)->comment('Visible au public');
            $table->boolean('ouvert_aux_dons')->default(true)->comment('Ouvert aux dons');
            $table->text('message_promotion')->nullable()->comment('Message promotionnel');
            $table->string('image_principale')->nullable()->comment('Image principale du projet');
            $table->json('canaux_communication')->nullable()->comment('Canaux de communication (JSON)');

            // Évaluation et résultats
            $table->text('resultats_obtenus')->nullable()->comment('Résultats obtenus');
            $table->text('impact_communaute')->nullable()->comment('Impact sur la communauté');
            $table->text('lecons_apprises')->nullable()->comment('Leçons apprises');
            $table->text('recommandations')->nullable()->comment('Recommandations');
            $table->decimal('note_satisfaction', 3, 1)->nullable()->comment('Note de satisfaction (1-10)');
            $table->text('feedback_beneficiaires')->nullable()->comment('Feedback des bénéficiaires');

            // Suivi post-projet
            $table->boolean('necessite_suivi')->default(false)->comment('Nécessite un suivi post-projet');
            $table->date('prochaine_evaluation')->nullable()->comment('Date de prochaine évaluation');
            $table->text('plan_suivi')->nullable()->comment('Plan de suivi');
            $table->json('projet_lie')->nullable()->comment('Projets liés (JSON)');

            // Conformité et audit
            $table->boolean('conforme_reglementation')->default(true)->comment('Conforme à la réglementation');
            $table->text('autorisations_requises')->nullable()->comment('Autorisations requises');
            $table->boolean('audit_requis')->default(false)->comment('Audit requis');
            $table->text('observations_audit')->nullable()->comment('Observations d\'audit');

            // Fréquence et récurrence
            $table->boolean('projet_recurrent')->default(false)->comment('Projet récurrent');
            $table->enum('frequence_recurrence', [
                'annuelle',
                'semestrielle',
                'trimestrielle',
                'mensuelle',
                'ponctuelle'
            ])->nullable()->comment('Fréquence de récurrence');
            $table->uuid('projet_parent_id')->nullable()->comment('Projet parent si récurrent');

            // Métadonnées techniques
            $table->json('metadonnees')->nullable()->comment('Métadonnées supplémentaires (JSON)');
            $table->string('reference_externe')->nullable()->comment('Référence externe');
            $table->text('integration_systemes')->nullable()->comment('Intégration avec d\'autres systèmes');

            // Notes et commentaires
            $table->text('notes_responsable')->nullable()->comment('Notes du responsable');
            $table->text('notes_admin')->nullable()->comment('Notes administratives');
            $table->text('historique_modifications')->nullable()->comment('Historique des modifications');

            // Audit et traçabilité
            $table->uuid('cree_par')->nullable()->comment('Membre qui a créé le projet');
            $table->uuid('modifie_par')->nullable()->comment('Dernier membre ayant modifié');
            $table->timestamp('derniere_activite_date')->nullable()->comment('Date de dernière activité');
            $table->uuid('derniere_activite_par')->nullable()->comment('Auteur de la dernière activité');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Index pour les performances
            $table->index(['statut', 'priorite'], 'idx_projets_statut_priorite');
            $table->index(['type_projet', 'statut'], 'idx_projets_type_statut');
            $table->index(['responsable_id', 'statut'], 'idx_projets_responsable_statut');
            $table->index(['date_debut', 'date_fin_prevue'], 'idx_projets_periode');
            $table->index(['categorie', 'statut'], 'idx_projets_categorie_statut');
            $table->index(['ouvert_aux_dons', 'visible_public'], 'idx_projets_public_dons');
            $table->index(['pourcentage_completion', 'statut'], 'idx_projets_completion');
            $table->index(['ville', 'region'], 'idx_projets_localisation');
            $table->index(['budget_prevu'], 'idx_projets_budget');
            $table->index(['necessite_approbation', 'approuve_le'], 'idx_projets_approbation');
            $table->index(['statut', 'statut_precedent'], 'idx_projets_statut_historique');

            // Index pour les recherches
            $table->index('nom_projet', 'idx_projets_nom');
            $table->index('code_projet', 'idx_projets_code');
            $table->index(['latitude', 'longitude'], 'idx_projets_geo');

            // Index composé pour les requêtes complexes
            $table->index([
                'statut',
                'type_projet',
                'priorite',
                'date_debut'
            ], 'idx_projets_recherche');
        });

        // Ajouter les contraintes de clés étrangères après la création de la table
        Schema::table('projets', function (Blueprint $table) {
            $table->foreign('responsable_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('coordinateur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('chef_projet_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approuve_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('projet_parent_id')->references('id')->on('projets')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('derniere_activite_par')->references('id')->on('users')->onDelete('set null');
        });

        // Nettoyer les objets existants avant création
        DB::statement("DROP TRIGGER IF EXISTS trigger_validate_projet_transition ON projets");
        DB::statement("DROP FUNCTION IF EXISTS validate_projet_transition()");
        DB::statement("DROP VIEW IF EXISTS projets_action_requise");
        DB::statement("DROP VIEW IF EXISTS statistiques_projets");
        DB::statement("DROP VIEW IF EXISTS projets_dons_ouverts");
        DB::statement("DROP VIEW IF EXISTS projets_actifs");

        // Vues CORRIGÉES
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

        // Fonction et trigger pour les validations automatiques
        DB::statement('
            CREATE OR REPLACE FUNCTION validate_projet_transition()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Validation : ne peut pas passer en cours sans être approuvé
                IF NEW.statut = \'en_cours\' AND NEW.necessite_approbation = true AND NEW.approuve_par IS NULL THEN
                    RAISE EXCEPTION \'Impossible de démarrer un projet non approuvé\';
                END IF;

                -- Validation : projet terminé doit avoir 100% de completion
                IF NEW.statut = \'termine\' AND NEW.pourcentage_completion < 100 THEN
                    NEW.pourcentage_completion = 100;
                END IF;

                -- Validation : projet en recherche de financement doit être ouvert aux dons
                IF NEW.statut = \'recherche_financement\' AND NEW.ouvert_aux_dons = false THEN
                    NEW.ouvert_aux_dons = true;
                END IF;

                -- Auto-complétion de la date de fin si projet terminé
                IF NEW.statut = \'termine\' AND (OLD.statut IS NULL OR OLD.statut != \'termine\') AND NEW.date_fin_reelle IS NULL THEN
                    NEW.date_fin_reelle = CURRENT_DATE;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::statement('
            CREATE TRIGGER trigger_validate_projet_transition
            BEFORE UPDATE ON projets
            FOR EACH ROW
            EXECUTE FUNCTION validate_projet_transition();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression du trigger et de la fonction
        DB::statement("DROP TRIGGER IF EXISTS trigger_validate_projet_transition ON projets");
        DB::statement("DROP FUNCTION IF EXISTS validate_projet_transition()");

        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS projets_action_requise");
        DB::statement("DROP VIEW IF EXISTS statistiques_projets");
        DB::statement("DROP VIEW IF EXISTS projets_dons_ouverts");
        DB::statement("DROP VIEW IF EXISTS projets_actifs");

        // Suppression de la table
        Schema::dropIfExists('projets');
    }
};
