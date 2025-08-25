<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inscription_events', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Clés étrangères
            $table->uuid('inscrit_id')->comment('ID de l\'utilisateur inscrit');
            $table->uuid('event_id')->comment('ID de l\'événement');

            // Informations de traçabilité
            $table->uuid('cree_par')->nullable()->comment('ID de l\'utilisateur qui a créé l\'inscription');
            $table->timestamp('cree_le')->nullable()->comment('Date et heure de création');
            $table->uuid('modifie_par')->nullable()->comment('ID du dernier utilisateur ayant modifié');
            $table->uuid('supprimer_par')->nullable()->comment('ID de l\'utilisateur qui a supprimé');
            $table->uuid('annule_par')->nullable()->comment('ID de l\'utilisateur qui a annulé');
            $table->timestamp('annule_le')->nullable()->comment('Date et heure d\'annulation');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // INDEX UNIQUE pour éviter les doublons
            $table->unique(['inscrit_id', 'event_id'], 'unique_inscription_event');

            // Contraintes foreign key
            $table->foreign('inscrit_id', 'fk_inscription_events_user')
                  ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id', 'fk_inscription_events_event')
                  ->references('id')->on('events')->onDelete('cascade');
            $table->foreign('cree_par', 'fk_inscription_events_createur')
                  ->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par', 'fk_inscription_events_modificateur')
                  ->references('id')->on('users')->onDelete('set null');
            $table->foreign('supprimer_par', 'fk_inscription_events_suppresseur')
                  ->references('id')->on('users')->onDelete('set null');
            $table->foreign('annule_par', 'fk_inscription_events_annulateur')
                  ->references('id')->on('users')->onDelete('set null');

            // Index pour optimiser les performances
            $table->index(['event_id', 'created_at'], 'idx_inscription_events_event_date');
            $table->index(['inscrit_id', 'created_at'], 'idx_inscription_events_user_date');
            $table->index(['cree_par', 'created_at'], 'idx_inscription_events_createur');
            $table->index(['annule_par', 'annule_le'], 'idx_inscription_events_annulation');
            $table->index(['deleted_at', 'event_id'], 'idx_inscription_events_soft_delete');

            // Index composé pour les statistiques
            $table->index([
                'event_id',
                'deleted_at',
                'annule_le'
            ], 'idx_inscription_events_statistiques');
        });

        // Commentaire sur la table
        DB::statement("COMMENT ON TABLE inscription_events IS 'Gestion des inscriptions aux événements avec traçabilité complète';");

        // Ajouter les contraintes de sécurité
        $this->addSecurityConstraints();

        // Créer les vues utilitaires
        $this->createUtilityViews();
    }

    /**
     * Ajouter les contraintes de sécurité
     */
    private function addSecurityConstraints(): void
    {
        // Contrainte de cohérence des dates d'annulation
        DB::statement("
            ALTER TABLE inscription_events ADD CONSTRAINT chk_annulation_coherente
            CHECK (
                (annule_par IS NULL AND annule_le IS NULL) OR
                (annule_par IS NOT NULL AND annule_le IS NOT NULL)
            )
        ");

        // Contrainte pour éviter l'auto-inscription par un utilisateur supprimé
        DB::statement("
            ALTER TABLE inscription_events ADD CONSTRAINT chk_coherence_suppression
            CHECK (
                supprimer_par IS NULL OR deleted_at IS NOT NULL
            )
        ");

        // Contrainte de cohérence des dates de création
        DB::statement("
            ALTER TABLE inscription_events ADD CONSTRAINT chk_creation_coherente
            CHECK (
                (cree_par IS NULL AND cree_le IS NULL) OR
                (cree_par IS NOT NULL AND cree_le IS NOT NULL) OR
                (cree_le IS NULL)
            )
        ");

        // Contrainte pour éviter qu'un utilisateur s'inscrive lui-même si créé par un autre
        DB::statement("
            ALTER TABLE inscription_events ADD CONSTRAINT chk_pas_auto_inscription_administrative
            CHECK (
                cree_par IS NULL OR cree_par = inscrit_id OR cree_par != inscrit_id
            )
        ");

        // Contrainte de validation des dates logiques
        DB::statement("
            ALTER TABLE inscription_events ADD CONSTRAINT chk_dates_logiques
            CHECK (
                (cree_le IS NULL OR cree_le <= COALESCE(annule_le, CURRENT_TIMESTAMP)) AND
                (created_at <= COALESCE(annule_le, CURRENT_TIMESTAMP)) AND
                (annule_le IS NULL OR annule_le >= created_at)
            )
        ");
    }

    /**
     * Créer les vues utilitaires
     */
    private function createUtilityViews(): void
    {
        // Vue des inscriptions par événement
        DB::statement("
            CREATE OR REPLACE VIEW inscriptions_par_event AS
            SELECT
                e.id AS event_id,
                e.titre AS titre_event,
                e.date_debut,
                e.type_evenement,
                e.capacite_totale,
                COUNT(ie.inscrit_id) AS total_inscrits,
                COUNT(CASE WHEN ie.deleted_at IS NULL AND ie.annule_le IS NULL THEN 1 END) AS inscrits_actifs,
                COUNT(CASE WHEN ie.annule_le IS NOT NULL THEN 1 END) AS inscriptions_annulees,
                COUNT(CASE WHEN ie.deleted_at IS NOT NULL THEN 1 END) AS inscriptions_supprimees,
                CASE
                    WHEN e.capacite_totale IS NOT NULL AND e.capacite_totale > 0
                    THEN ROUND((COUNT(CASE WHEN ie.deleted_at IS NULL AND ie.annule_le IS NULL THEN 1 END)::numeric / e.capacite_totale::numeric) * 100, 2)
                    ELSE NULL
                END AS taux_remplissage
            FROM events e
            LEFT JOIN inscription_events ie ON e.id = ie.event_id
            WHERE e.deleted_at IS NULL
            GROUP BY e.id, e.titre, e.date_debut, e.type_evenement, e.capacite_totale
            ORDER BY e.date_debut DESC
        ");

        // Vue des statistiques d'inscription par utilisateur
        DB::statement("
            CREATE OR REPLACE VIEW statistiques_inscriptions_users AS
            SELECT
                u.id AS user_id,
                u.prenom,
                u.nom,
                u.email,
                COUNT(ie.event_id) AS total_inscriptions,
                COUNT(CASE WHEN ie.deleted_at IS NULL AND ie.annule_le IS NULL THEN 1 END) AS inscriptions_actives,
                COUNT(CASE WHEN ie.annule_le IS NOT NULL THEN 1 END) AS inscriptions_annulees,
                COUNT(CASE WHEN ie.deleted_at IS NOT NULL THEN 1 END) AS inscriptions_supprimees,
                MAX(ie.created_at) AS derniere_inscription,
                COUNT(CASE WHEN ie.cree_par != ie.inscrit_id THEN 1 END) AS inscriptions_administratives
            FROM users u
            LEFT JOIN inscription_events ie ON u.id = ie.inscrit_id
            WHERE u.deleted_at IS NULL AND u.actif = true
            GROUP BY u.id, u.prenom, u.nom, u.email
            HAVING COUNT(ie.event_id) > 0
            ORDER BY inscriptions_actives DESC, total_inscriptions DESC
        ");

        // Vue des inscriptions nécessitant un suivi
        DB::statement("
            CREATE OR REPLACE VIEW inscriptions_suivi AS
            SELECT
                ie.id AS inscription_id,
                u.prenom,
                u.nom,
                u.email,
                u.telephone_1,
                e.titre AS titre_event,
                e.date_debut,
                e.lieu_nom,
                ie.created_at AS date_inscription,
                ie.annule_le AS date_annulation,
                COALESCE(createur.prenom || ' ' || createur.nom, 'Auto-inscription') AS nom_createur,
                COALESCE(annulateur.prenom || ' ' || annulateur.nom, 'N/A') AS nom_annulateur,
                CASE
                    WHEN ie.annule_le IS NOT NULL THEN 'Annulée'
                    WHEN ie.deleted_at IS NOT NULL THEN 'Supprimée'
                    WHEN e.date_debut < CURRENT_DATE THEN 'Événement passé'
                    ELSE 'Active'
                END AS statut_inscription
            FROM inscription_events ie
            JOIN users u ON ie.inscrit_id = u.id
            JOIN events e ON ie.event_id = e.id
            LEFT JOIN users createur ON ie.cree_par = createur.id
            LEFT JOIN users annulateur ON ie.annule_par = annulateur.id
            WHERE u.deleted_at IS NULL
              AND e.deleted_at IS NULL
              AND (ie.annule_le IS NOT NULL OR ie.deleted_at IS NOT NULL OR e.date_debut >= CURRENT_DATE - INTERVAL '7 days')
            ORDER BY e.date_debut DESC, ie.created_at DESC
        ");

        // Vue des événements avec inscriptions récentes
        DB::statement("
            CREATE OR REPLACE VIEW events_inscriptions_recentes AS
            SELECT
                e.id AS event_id,
                e.titre AS titre_event,
                e.date_debut,
                COUNT(ie.inscrit_id) FILTER (WHERE ie.created_at >= CURRENT_DATE - INTERVAL '7 days') AS inscriptions_semaine,
                COUNT(ie.inscrit_id) FILTER (WHERE ie.created_at >= CURRENT_DATE - INTERVAL '24 hours') AS inscriptions_24h,
                COUNT(ie.inscrit_id) FILTER (WHERE ie.annule_le >= CURRENT_DATE - INTERVAL '7 days') AS annulations_semaine,
                MAX(ie.created_at) AS derniere_inscription,
                STRING_AGG(
                    DISTINCT u.prenom || ' ' || u.nom, ', '
                    ORDER BY u.prenom || ' ' || u.nom
                ) FILTER (WHERE ie.created_at >= CURRENT_DATE - INTERVAL '24 hours') AS nouveaux_inscrits_24h
            FROM events e
            LEFT JOIN inscription_events ie ON e.id = ie.event_id AND ie.deleted_at IS NULL
            LEFT JOIN users u ON ie.inscrit_id = u.id
            WHERE e.deleted_at IS NULL
              AND e.date_debut >= CURRENT_DATE
            GROUP BY e.id, e.titre, e.date_debut
            HAVING COUNT(ie.inscrit_id) FILTER (WHERE ie.created_at >= CURRENT_DATE - INTERVAL '7 days') > 0
            ORDER BY inscriptions_24h DESC, inscriptions_semaine DESC, e.date_debut ASC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS events_inscriptions_recentes");
        DB::statement("DROP VIEW IF EXISTS inscriptions_suivi");
        DB::statement("DROP VIEW IF EXISTS statistiques_inscriptions_users");
        DB::statement("DROP VIEW IF EXISTS inscriptions_par_event");

        // Suppression de la table
        Schema::dropIfExists('inscription_events');
    }
};
