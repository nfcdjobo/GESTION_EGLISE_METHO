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
        Schema::create('participant_cultes', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Clés étrangères
            $table->uuid('participant_id')->comment('ID de l\'membres participant');
            $table->uuid('culte_id')->comment('ID du culte');

            // Informations de participation
            $table->enum('statut_presence', [
                'present',
                'present_partiel',
                'en_retard',
                'parti_tot'
            ])->default('present')->comment('Type de présence du participant');

            $table->enum('type_participation', [
                'physique',
                'en_ligne',
                'hybride'
            ])->default('physique')->comment('Mode de participation');

            // Horaires réels de participation
            $table->time('heure_arrivee')->nullable()->comment('Heure réelle d\'arrivée');
            $table->time('heure_depart')->nullable()->comment('Heure réelle de départ');

            // Rôle spécifique dans ce culte
            $table->enum('role_culte', [
                'participant',
                'equipe_technique',
                'equipe_louange',
                'equipe_accueil',
                'orateur',
                'dirigeant',
                'diacre_service',
                'collecteur_offrande',
                'invite_special',
                'nouveau_visiteur'
            ])->default('participant')->comment('Rôle du participant dans ce culte');

            // Informations de confirmation
            $table->boolean('presence_confirmee')->default(true)->comment('Présence confirmée par un responsable');
            $table->uuid('confirme_par')->nullable()->comment('ID de la personne qui a confirmé la présence');
            $table->timestamp('confirme_le')->nullable()->comment('Date et heure de confirmation');

            // Informations contextuelles importantes
            $table->boolean('premiere_visite')->default(false)->comment('Est-ce la première visite de cette personne?');
            $table->uuid('accompagne_par')->nullable()->comment('ID du membre accompagnateur');

            // Suivi pastoral essentiel
            $table->boolean('demande_contact_pastoral')->default(false)->comment('Demande un contact pastoral');
            $table->boolean('interesse_bapteme')->default(false)->comment('Intéressé par le baptême');
            $table->boolean('souhaite_devenir_membre')->default(false)->comment('Souhaite devenir membre');

            // Notes et observations
            $table->text('notes_responsable')->nullable()->comment('Notes du responsable sur la participation');
            $table->text('commentaires_participant')->nullable()->comment('Commentaires du participant');

            // Informations d'enregistrement
            $table->uuid('enregistre_par')->nullable()->comment('ID de la personne qui a enregistré');
            $table->timestamp('enregistre_le')->nullable()->comment('Date et heure d\'enregistrement');

            // Timestamps et soft delete
            $table->timestamps();
            $table->softDeletes();

            // INDEX UNIQUE au lieu d'une deuxième clé primaire
            $table->unique(['participant_id', 'culte_id'], 'unique_participant_culte');

            // Contraintes foreign key
            $table->foreign('participant_id', 'fk_participant_cultes_user')
                  ->references('id')->on('users')->onDelete('cascade');
            $table->foreign('culte_id', 'fk_participant_cultes_culte')
                  ->references('id')->on('cultes')->onDelete('cascade');
            $table->foreign('confirme_par', 'fk_participant_cultes_confirmateur')
                  ->references('id')->on('users')->onDelete('set null');
            $table->foreign('enregistre_par', 'fk_participant_cultes_enregistreur')
                  ->references('id')->on('users')->onDelete('set null');
            $table->foreign('accompagne_par', 'fk_participant_cultes_accompagnateur')
                  ->references('id')->on('users')->onDelete('set null');

            // Index pour optimiser les performances
            $table->index(['culte_id', 'statut_presence'], 'idx_participant_cultes_statut');
            $table->index(['participant_id', 'created_at'], 'idx_participant_historique');
            $table->index(['presence_confirmee', 'statut_presence'], 'idx_participant_confirmation');
            $table->index(['role_culte', 'culte_id'], 'idx_participant_role');
            $table->index(['premiere_visite', 'culte_id'], 'idx_participant_premiere_visite');
            $table->index(['demande_contact_pastoral', 'culte_id'], 'idx_participant_suivi_pastoral');

            // Index composé pour les statistiques
            $table->index([
                'culte_id',
                'statut_presence',
                'type_participation'
            ], 'idx_participant_statistiques');
        });

        // Commentaire sur la table
        DB::statement("COMMENT ON TABLE participant_cultes IS 'Gestion de la participation des membres aux cultes';");

        // Contraintes de sécurité
        $this->addSecurityConstraints();

        // Vues utilitaires
        $this->createUtilityViews();
    }

    /**
     * Ajouter les contraintes de sécurité
     */
    private function addSecurityConstraints(): void
    {
        // Contrainte de cohérence des horaires
        DB::statement("
            ALTER TABLE participant_cultes ADD CONSTRAINT chk_horaires_coherents
            CHECK (
                heure_depart IS NULL OR heure_arrivee IS NULL OR heure_depart >= heure_arrivee
            )
        ");

        // Contrainte de cohérence entre statut et horaires
        DB::statement("
            ALTER TABLE participant_cultes ADD CONSTRAINT chk_statut_horaires_coherent
            CHECK (
                (statut_presence = 'present' OR heure_arrivee IS NOT NULL)
            )
        ");

        // Contrainte de cohérence pour les premières visites
        DB::statement("
            ALTER TABLE participant_cultes ADD CONSTRAINT chk_premiere_visite_coherente
            CHECK (
                NOT (premiere_visite = true AND role_culte IN ('dirigeant', 'diacre_service'))
            )
        ");

        // Contrainte pour éviter l'auto-accompagnement
        DB::statement("
            ALTER TABLE participant_cultes ADD CONSTRAINT chk_pas_auto_accompagnement
            CHECK (
                accompagne_par IS NULL OR accompagne_par != participant_id
            )
        ");
    }

    /**
     * Créer les vues utilitaires
     */
    private function createUtilityViews(): void
    {
        // Vue des présences par culte
        DB::statement("
            CREATE OR REPLACE VIEW presences_par_culte AS
            SELECT
                c.id AS culte_id,
                c.titre AS titre_culte,
                c.date_culte,
                c.type_culte,
                COUNT(pc.participant_id) AS total_presents,
                COUNT(CASE WHEN pc.statut_presence = 'present' THEN 1 END) AS presents_complets,
                COUNT(CASE WHEN pc.statut_presence != 'present' THEN 1 END) AS presents_partiels,
                COUNT(CASE WHEN pc.premiere_visite = true THEN 1 END) AS nouveaux_visiteurs,
                COUNT(CASE WHEN pc.type_participation = 'en_ligne' THEN 1 END) AS participants_en_ligne,
                COUNT(CASE WHEN pc.type_participation = 'physique' THEN 1 END) AS participants_physiques
            FROM cultes c
            LEFT JOIN participant_cultes pc ON c.id = pc.culte_id AND pc.deleted_at IS NULL
            WHERE c.deleted_at IS NULL
            GROUP BY c.id, c.titre, c.date_culte, c.type_culte
            ORDER BY c.date_culte DESC
        ");

        // Vue des statistiques de participation par membre
        DB::statement("
            CREATE OR REPLACE VIEW statistiques_participation_membres AS
            SELECT
                u.id AS user_id,
                u.prenom,
                u.nom,
                u.email,
                COUNT(pc.culte_id) AS total_participations,
                COUNT(CASE WHEN pc.statut_presence = 'present' THEN 1 END) AS presences_completes,
                COUNT(CASE WHEN pc.statut_presence != 'present' THEN 1 END) AS presences_partielles,
                MAX(pc.created_at) AS derniere_participation,
                COUNT(CASE WHEN pc.role_culte != 'participant' THEN 1 END) AS participations_avec_role,
                COUNT(CASE WHEN pc.demande_contact_pastoral = true THEN 1 END) AS demandes_contact_pastoral
            FROM users u
            LEFT JOIN participant_cultes pc ON u.id = pc.participant_id AND pc.deleted_at IS NULL
            WHERE u.deleted_at IS NULL AND u.actif = true
            GROUP BY u.id, u.prenom, u.nom, u.email
            HAVING COUNT(pc.culte_id) > 0
            ORDER BY presences_completes DESC, total_participations DESC
        ");

        // Vue des nouveaux visiteurs nécessitant un suivi
        DB::statement("
            CREATE OR REPLACE VIEW nouveaux_visiteurs_suivi AS
            SELECT
                u.id,
                u.prenom,
                u.nom,
                u.email,
                u.telephone_1,
                c.titre AS titre_culte,
                c.date_culte,
                pc.statut_presence,
                pc.demande_contact_pastoral,
                pc.interesse_bapteme,
                pc.souhaite_devenir_membre,
                pc.commentaires_participant,
                COALESCE(accompagnateur.prenom || ' ' || accompagnateur.nom, 'Aucun') AS nom_accompagnateur,
                pc.created_at AS date_inscription
            FROM participant_cultes pc
            JOIN users u ON pc.participant_id = u.id
            JOIN cultes c ON pc.culte_id = c.id
            LEFT JOIN users accompagnateur ON pc.accompagne_par = accompagnateur.id
            WHERE pc.premiere_visite = true
              AND pc.deleted_at IS NULL
              AND u.deleted_at IS NULL
              AND c.deleted_at IS NULL
              AND c.date_culte >= CURRENT_DATE - INTERVAL '30 days'
            ORDER BY c.date_culte DESC, pc.created_at DESC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS nouveaux_visiteurs_suivi");
        DB::statement("DROP VIEW IF EXISTS statistiques_participation_membres");
        DB::statement("DROP VIEW IF EXISTS presences_par_culte");

        // Suppression de la table
        Schema::dropIfExists('participant_cultes');
    }
};
