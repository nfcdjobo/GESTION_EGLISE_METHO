<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Relations
            $table->uuid('culte_id')->nullable()->comment('Culte concerné');
            $table->uuid('reunion_id')->nullable()->comment('Réunion concernée');
            $table->uuid('intervenant_id')->comment('Personne qui intervient');

            // Informations de base
            $table->string('titre', 200)->comment('Titre de l\'intervention');
            $table->enum('type_intervention', [
                'predication',         // Prédication principale
                'temoignage',         // Témoignage personnel
                'priere',             // Temps de prière
                'louange',            // Direction de louange
                'lecture',            // Lecture biblique
                'annonce',            // Annonce
                'offrande',           // Collecte d'offrande
                'accueil',            // Accueil des visiteurs
                'benediction',        // Bénédiction
                'presentation',       // Présentation/enseignement
                'animation',          // Animation générale
                'autre'               // Autre type
            ])->comment('Type d\'intervention');

            // Timing
            $table->time('heure_debut')->nullable()->comment('Heure de début');
            $table->time('heure_fin')->nullable()->comment('Heure de fin');
            $table->integer('duree_minutes')->nullable()->comment('Durée en minutes');
            $table->integer('ordre_passage')->nullable()->comment('Ordre dans le programme');

            // Contenu
            $table->text('description')->nullable()->comment('Description/résumé de l\'intervention');
            $table->string('passage_biblique', 300)->nullable()->comment('Passage biblique de référence');
            $table->text('points_cles')->nullable()->comment('Points clés abordés');

            // Évaluation simple
            $table->enum('qualite', [
                'excellente',
                'bonne',
                'satisfaisante',
                'a_ameliorer'
            ])->nullable()->comment('Évaluation de la qualité');

            $table->text('commentaires')->nullable()->comment('Commentaires sur l\'intervention');
            $table->text('notes_responsable')->nullable()->comment('Notes du responsable');

            // État
            $table->enum('statut', [
                'prevue',             // Intervention prévue
                'en_cours',           // En cours
                'terminee',           // Terminée
                'annulee'             // Annulée
            ])->default('prevue')->comment('Statut de l\'intervention');

            // Audit simple
            $table->uuid('assignee_par')->nullable()->comment('Qui a assigné cette intervention');
            $table->timestamp('assignee_le')->nullable()->comment('Date d\'assignation');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key (cultes sera ajoutée plus tard si nécessaire)
            $table->foreign('reunion_id')->references('id')->on('reunions')->onDelete('cascade');
            $table->foreign('intervenant_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assignee_par')->references('id')->on('users')->onDelete('set null');

            // Index essentiels
            $table->index(['culte_id', 'ordre_passage'], 'idx_interventions_culte_ordre');
            $table->index(['reunion_id', 'ordre_passage'], 'idx_interventions_reunion_ordre');
            $table->index(['intervenant_id', 'type_intervention'], 'idx_interventions_personne_type');
            $table->index(['statut', 'heure_debut'], 'idx_interventions_statut_heure');
            $table->index('type_intervention', 'idx_interventions_type');

            // Index pour vérifier qu'au moins un événement est lié
            $table->index(['culte_id', 'reunion_id'], 'idx_interventions_evenement');
        });

        // Contrainte CHECK PostgreSQL : au moins un des deux IDs doit être fourni
        DB::statement("
            ALTER TABLE interventions
            ADD CONSTRAINT check_interventions_evenement
            CHECK (culte_id IS NOT NULL OR reunion_id IS NOT NULL)
        ");

        // Commentaire sur la table (PostgreSQL syntax)
        DB::statement("COMMENT ON TABLE interventions IS 'Interventions des personnes lors des cultes et réunions'");

        // Vue simple pour le planning (PostgreSQL syntax)
        DB::statement("
            CREATE VIEW planning_interventions AS
            SELECT
                i.*,
                CONCAT(intervenant.prenom, ' ', intervenant.nom) as nom_intervenant,
                COALESCE(r.titre, 'Culte du ' || TO_CHAR(CURRENT_DATE, 'DD/MM/YYYY')) as titre_evenement,
                COALESCE(r.date_reunion, CURRENT_DATE) as date_evenement,
                CASE
                    WHEN i.culte_id IS NOT NULL THEN 'Culte'
                    ELSE 'Réunion'
                END as type_evenement
            FROM interventions i
            LEFT JOIN users intervenant ON i.intervenant_id = intervenant.id
            LEFT JOIN reunions r ON i.reunion_id = r.id
            WHERE i.deleted_at IS NULL
            ORDER BY
                COALESCE(r.date_reunion, CURRENT_DATE) ASC,
                i.ordre_passage ASC
        ");

        // Vue pour les interventions par type
        DB::statement("
            CREATE VIEW interventions_par_type AS
            SELECT
                i.type_intervention,
                COUNT(*) as nombre_interventions,
                COUNT(DISTINCT i.intervenant_id) as nombre_intervenants,
                AVG(i.duree_minutes) as duree_moyenne,
                COUNT(CASE WHEN i.qualite = 'excellente' THEN 1 END) as excellentes,
                COUNT(CASE WHEN i.qualite = 'bonne' THEN 1 END) as bonnes,
                COUNT(CASE WHEN i.statut = 'terminee' THEN 1 END) as terminees
            FROM interventions i
            WHERE i.deleted_at IS NULL
            GROUP BY i.type_intervention
            ORDER BY nombre_interventions DESC
        ");

        // Vue pour le planning des intervenants
        DB::statement("
            CREATE VIEW planning_intervenants AS
            SELECT
                u.id as intervenant_id,
                CONCAT(u.prenom, ' ', u.nom) as nom_intervenant,
                u.email,
                COUNT(i.id) as nombre_interventions_total,
                COUNT(CASE WHEN i.statut = 'prevue' THEN 1 END) as interventions_prevues,
                COUNT(CASE WHEN i.statut = 'en_cours' THEN 1 END) as interventions_en_cours,
                MAX(COALESCE(r.date_reunion, CURRENT_DATE)) as derniere_intervention,
                STRING_AGG(DISTINCT i.type_intervention, ', ') as types_interventions
            FROM users u
            INNER JOIN interventions i ON u.id = i.intervenant_id
            LEFT JOIN reunions r ON i.reunion_id = r.id
            WHERE i.deleted_at IS NULL
            GROUP BY u.id, u.prenom, u.nom, u.email
            ORDER BY nombre_interventions_total DESC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS planning_intervenants");
        DB::statement("DROP VIEW IF EXISTS interventions_par_type");
        DB::statement("DROP VIEW IF EXISTS planning_interventions");

        // Suppression de la table
        Schema::dropIfExists('interventions');
    }
};
