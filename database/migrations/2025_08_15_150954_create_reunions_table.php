php<?php

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
        Schema::create('reunions', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Relation avec le type de réunion (template)
            $table->uuid('type_reunion_id')->comment('Type/modèle de réunion utilisé');

            // Informations de base
            $table->string('titre', 200)->comment('Titre spécifique de cette réunion');
            $table->text('description')->nullable()->comment('Description spécifique');
            $table->text('objectifs')->nullable()->comment('Objectifs de cette réunion');

            // Planification temporelle
            $table->date('date_reunion')->comment('Date de la réunion');
            $table->time('heure_debut_prevue')->comment('Heure de début prévue');
            $table->time('heure_fin_prevue')->nullable()->comment('Heure de fin prévue');
            $table->time('heure_debut_reelle')->nullable()->comment('Heure de début réelle');
            $table->time('heure_fin_reelle')->nullable()->comment('Heure de fin réelle');
            $table->time('duree_prevue')->nullable()->comment('Durée prévue');
            $table->time('duree_reelle')->nullable()->comment('Durée réelle');

            // Lieu et logistique
            $table->string('lieu', 200)->comment('Lieu de la réunion');
            $table->text('adresse_complete')->nullable()->comment('Adresse complète du lieu');
            $table->string('salle', 100)->nullable()->comment('Salle spécifique');
            $table->integer('capacite_salle')->nullable()->comment('Capacité de la salle');
            $table->decimal('latitude', 10, 8)->nullable()->comment('Latitude GPS');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Longitude GPS');

            // Responsables et équipe
            $table->uuid('organisateur_principal_id')->nullable()->comment('Organisateur principal');
            $table->uuid('animateur_id')->nullable()->comment('Animateur/facilitateur principal');
            $table->uuid('responsable_technique_id')->nullable()->comment('Responsable technique');
            $table->uuid('responsable_accueil_id')->nullable()->comment('Responsable accueil');
            $table->json('equipe_organisation')->nullable()->comment('Équipe d\'organisation (JSON)');
            $table->json('intervenants')->nullable()->comment('Liste des intervenants (JSON)');

            // Participants et inscription
            $table->integer('nombre_places_disponibles')->nullable()->comment('Places disponibles');
            $table->integer('nombre_inscrits')->default(0)->comment('Nombre d\'inscrits');
            $table->integer('nombre_participants_reel')->nullable()->comment('Nombre réel de participants');
            $table->integer('nombre_adultes')->nullable()->comment('Nombre d\'adultes présents');
            $table->integer('nombre_enfants')->nullable()->comment('Nombre d\'enfants présents');
            $table->integer('nombre_nouveaux')->nullable()->comment('Nombre de nouveaux participants');
            $table->date('limite_inscription')->nullable()->comment('Date limite d\'inscription');
            $table->boolean('liste_attente_activee')->default(false)->comment('Liste d\'attente activée');

            // Programme et contenu
            $table->json('ordre_du_jour')->nullable()->comment('Ordre du jour détaillé (JSON)');
            $table->text('message_principal')->nullable()->comment('Message ou enseignement principal');
            $table->string('passage_biblique', 500)->nullable()->comment('Passage biblique de référence');
            $table->json('documents_annexes')->nullable()->comment('Documents fournis (JSON)');
            $table->text('materiel_fourni')->nullable()->comment('Matériel fourni aux participants');
            $table->text('materiel_apporter')->nullable()->comment('Matériel à apporter');

            // État et statut
            $table->enum('statut', [
                'planifiee',          // Réunion planifiée
                'confirmee',          // Réunion confirmée
                'planifie',     // En cours de préparation
                'en_cours',          // Réunion en cours
                'terminee',          // Réunion terminée
                'annulee',           // Réunion annulée
                'reportee',          // Réunion reportée
                'suspendue'          // Réunion suspendue
            ])->default('planifiee')->comment('Statut de la réunion');

            $table->enum('niveau_priorite', [
                'faible',
                'normale',
                'haute',
                'urgente',
                'critique'
            ])->default('normale')->comment('Niveau de priorité');

            // Informations financières
            $table->decimal('frais_inscription', 10, 2)->nullable()->comment('Frais d\'inscription');
            $table->decimal('budget_prevu', 15, 2)->nullable()->comment('Budget prévu');
            $table->decimal('cout_reel', 15, 2)->nullable()->comment('Coût réel');
            $table->json('detail_couts')->nullable()->comment('Détail des coûts (JSON)');
            $table->decimal('recettes_totales', 15, 2)->nullable()->comment('Recettes totales');

            // Communication et médias
            $table->boolean('diffusion_en_ligne')->default(false)->comment('Diffusion en ligne');
            $table->string('lien_diffusion')->nullable()->comment('Lien de diffusion');
            $table->boolean('enregistrement_autorise')->default(false)->comment('Enregistrement autorisé');
            $table->string('lien_enregistrement')->nullable()->comment('Lien vers l\'enregistrement');
            $table->json('photos_reunion')->nullable()->comment('Photos de la réunion (JSON)');
            $table->text('notes_communication')->nullable()->comment('Notes de communication');

            // Préparation et suivi
            $table->text('preparation_necessaire')->nullable()->comment('Préparation nécessaire');
            $table->json('checklist_preparation')->nullable()->comment('Checklist de préparation (JSON)');
            $table->boolean('preparation_terminee')->default(false)->comment('Préparation terminée');
            $table->text('instructions_participants')->nullable()->comment('Instructions pour les participants');

            // Évaluation et feedback
            $table->decimal('note_globale', 3, 1)->nullable()->comment('Note globale (1-10)');
            $table->decimal('note_contenu', 3, 1)->nullable()->comment('Note du contenu');
            $table->decimal('note_organisation', 3, 1)->nullable()->comment('Note de l\'organisation');
            $table->decimal('note_lieu', 3, 1)->nullable()->comment('Note du lieu');
            $table->decimal('taux_satisfaction', 5, 2)->nullable()->comment('Taux de satisfaction en %');
            $table->text('points_positifs')->nullable()->comment('Points positifs relevés');
            $table->text('points_amelioration')->nullable()->comment('Points à améliorer');
            $table->text('feedback_participants')->nullable()->comment('Feedback des participants');

            // Résultats spirituels (si applicable)
            $table->integer('nombre_decisions')->default(0)->comment('Nombre de décisions spirituelles');
            $table->integer('nombre_recommitments')->default(0)->comment('Nombre de re-engagements');
            $table->integer('nombre_guerisons')->default(0)->comment('Nombre de guérisons rapportées');
            $table->text('temoignages_recueillis')->nullable()->comment('Témoignages recueillis');
            $table->text('demandes_priere')->nullable()->comment('Demandes de prière');

            // Météo et contexte
            $table->string('conditions_meteo')->nullable()->comment('Conditions météorologiques');
            $table->text('contexte_particulier')->nullable()->comment('Contexte particulier');
            $table->text('defis_rencontres')->nullable()->comment('Défis rencontrés');
            $table->text('solutions_apportees')->nullable()->comment('Solutions apportées');

            // Suivi et récurrence (sans contraintes FK pour l'instant)
            $table->uuid('reunion_parent_id')->nullable()->comment('Réunion parent si récurrente');
            $table->boolean('est_recurrente')->default(false)->comment('Fait partie d\'une série récurrente');
            $table->date('prochaine_occurrence')->nullable()->comment('Prochaine occurrence si récurrente');
            $table->uuid('reunion_suivante_id')->nullable()->comment('Réunion suivante prévue');

            // Informations d'annulation/report
            $table->uuid('annulee_par')->nullable()->comment('Qui a annulé la réunion');
            $table->timestamp('annulee_le')->nullable()->comment('Date d\'annulation');
            $table->text('motif_annulation')->nullable()->comment('Motif d\'annulation');
            $table->date('nouvelle_date')->nullable()->comment('Nouvelle date si reportée');
            $table->text('message_participants')->nullable()->comment('Message envoyé aux participants');

            // Notifications et rappels
            $table->boolean('rappel_1_jour_envoye')->default(false)->comment('Rappel J-1 envoyé');
            $table->boolean('rappel_1_semaine_envoye')->default(false)->comment('Rappel J-7 envoyé');
            $table->timestamp('dernier_rappel_envoye')->nullable()->comment('Dernier rappel envoyé');
            $table->integer('nombre_rappels_envoyes')->default(0)->comment('Nombre de rappels envoyés');

            // Audit et traçabilité
            $table->uuid('cree_par')->nullable()->comment('Utilisateur qui a créé la réunion');
            $table->uuid('modifie_par')->nullable()->comment('Dernier utilisateur ayant modifié');
            $table->uuid('validee_par')->nullable()->comment('Qui a validé la réunion');
            $table->timestamp('validee_le')->nullable()->comment('Date de validation');
            $table->text('notes_organisateur')->nullable()->comment('Notes privées de l\'organisateur');
            $table->text('notes_admin')->nullable()->comment('Notes administratives');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key (sauf les auto-référentielles)
            $table->foreign('type_reunion_id')->references('id')->on('type_reunions')->onDelete('restrict');
            $table->foreign('organisateur_principal_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('animateur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('responsable_technique_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('responsable_accueil_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('annulee_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validee_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances
            $table->index(['date_reunion', 'statut'], 'idx_reunions_date_statut');
            $table->index(['type_reunion_id', 'date_reunion'], 'idx_reunions_type_date');
            $table->index(['organisateur_principal_id', 'date_reunion'], 'idx_reunions_org_date');
            $table->index(['statut', 'date_reunion'], 'idx_reunions_statut_date');
            $table->index(['lieu', 'date_reunion'], 'idx_reunions_lieu_date');
            $table->index(['limite_inscription', 'statut'], 'idx_reunions_inscription');
            $table->index(['diffusion_en_ligne', 'date_reunion'], 'idx_reunions_diffusion');
            $table->index(['est_recurrente', 'prochaine_occurrence'], 'idx_reunions_recurrence');
            $table->index(['niveau_priorite', 'date_reunion'], 'idx_reunions_priorite');
            $table->index(['validee_le', 'statut'], 'idx_reunions_validation');

            // Index pour les recherches
            $table->index('titre', 'idx_reunions_titre');
            $table->index(['date_reunion', 'heure_debut_prevue'], 'idx_reunions_planning');

            // Index composé pour les requêtes complexes
            $table->index([
                'date_reunion',
                'type_reunion_id',
                'statut',
                'nombre_participants_reel'
            ], 'idx_reunions_rapport');
        });

        // Ajouter les contraintes auto-référentielles après la création de la table
        Schema::table('reunions', function (Blueprint $table) {
            $table->foreign('reunion_parent_id')->references('id')->on('reunions')->onDelete('set null');
            $table->foreign('reunion_suivante_id')->references('id')->on('reunions')->onDelete('set null');
        });

        // Commentaire sur la table
        DB::statement("COMMENT ON TABLE reunions IS 'Instances spécifiques de réunions basées sur les types configurés'");

        // Vue pour les réunions à venir (PostgreSQL syntax)
        DB::statement("
            CREATE VIEW reunions_a_venir AS
            SELECT
                r.*,
                tr.nom as nom_type_reunion,
                tr.categorie,
                tr.couleur as couleur_type,
                CONCAT(org.prenom, ' ', org.nom) as nom_organisateur,
                CONCAT(anim.prenom, ' ', anim.nom) as nom_animateur,
                (r.date_reunion - CURRENT_DATE) as jours_restants,
                CASE
                    WHEN r.limite_inscription IS NOT NULL AND r.limite_inscription < CURRENT_DATE THEN 'Inscriptions fermées'
                    WHEN r.nombre_places_disponibles IS NOT NULL AND r.nombre_inscrits >= r.nombre_places_disponibles THEN 'Complet'
                    WHEN r.liste_attente_activee = true AND r.nombre_inscrits >= r.nombre_places_disponibles THEN 'Liste d''attente'
                    ELSE 'Inscriptions ouvertes'
                END as statut_inscription
            FROM reunions r
            INNER JOIN type_reunions tr ON r.type_reunion_id = tr.id
            LEFT JOIN users org ON r.organisateur_principal_id = org.id
            LEFT JOIN users anim ON r.animateur_id = anim.id
            WHERE r.date_reunion >= CURRENT_DATE
                AND r.statut IN ('planifiee', 'confirmee', 'planifie')
                AND r.deleted_at IS NULL
                AND tr.deleted_at IS NULL
            ORDER BY r.date_reunion ASC, r.heure_debut_prevue ASC
        ");

        // Vue simplifiée pour les réunions du jour (sans calcul de temps complexe)
        DB::statement("
            CREATE VIEW reunions_du_jour AS
            SELECT
                r.*,
                tr.nom as nom_type_reunion,
                tr.categorie,
                CONCAT(org.prenom, ' ', org.nom) as nom_organisateur,
                CONCAT(anim.prenom, ' ', anim.nom) as nom_animateur,
                EXTRACT(HOUR FROM r.heure_debut_prevue) as heure_debut,
                EXTRACT(MINUTE FROM r.heure_debut_prevue) as minute_debut
            FROM reunions r
            INNER JOIN type_reunions tr ON r.type_reunion_id = tr.id
            LEFT JOIN users org ON r.organisateur_principal_id = org.id
            LEFT JOIN users anim ON r.animateur_id = anim.id
            WHERE r.date_reunion = CURRENT_DATE
                AND r.statut IN ('confirmee', 'planifie', 'en_cours')
                AND r.deleted_at IS NULL
            ORDER BY r.heure_debut_prevue ASC
        ");

        // Vue pour les statistiques de participation
        DB::statement("
            CREATE VIEW statistiques_participation_reunions AS
            SELECT
                r.type_reunion_id,
                tr.nom as nom_type_reunion,
                tr.categorie,
                COUNT(*) as nombre_reunions,
                AVG(r.nombre_participants_reel) as moyenne_participants,
                SUM(r.nombre_participants_reel) as total_participants,
                MAX(r.nombre_participants_reel) as max_participants,
                AVG(r.taux_satisfaction) as satisfaction_moyenne,
                AVG(r.note_globale) as note_moyenne,
                COUNT(CASE WHEN r.statut = 'annulee' THEN 1 END) as nombre_annulations
            FROM reunions r
            INNER JOIN type_reunions tr ON r.type_reunion_id = tr.id
            WHERE r.statut = 'terminee'
                AND r.deleted_at IS NULL
            GROUP BY r.type_reunion_id, tr.nom, tr.categorie
            ORDER BY moyenne_participants DESC
        ");

        // Vue simplifiée pour les réunions nécessitant une action
        DB::statement("
            CREATE VIEW reunions_action_requise AS
            SELECT
                r.id,
                r.titre,
                r.date_reunion,
                r.statut,
                r.preparation_terminee,
                r.rappel_1_semaine_envoye,
                r.rappel_1_jour_envoye,
                r.note_globale,
                r.limite_inscription,
                tr.nom as nom_type_reunion,
                CASE
                    WHEN r.statut = 'planifiee' AND r.date_reunion <= (CURRENT_DATE + INTERVAL '7 days') THEN 'Confirmation requise'
                    WHEN r.limite_inscription IS NOT NULL AND r.limite_inscription <= (CURRENT_DATE + INTERVAL '3 days') AND r.statut = 'confirmee' THEN 'Clôture inscriptions proche'
                    WHEN r.preparation_terminee = false AND r.date_reunion <= (CURRENT_DATE + INTERVAL '3 days') THEN 'Préparation à terminer'
                    WHEN r.rappel_1_semaine_envoye = false AND r.date_reunion <= (CURRENT_DATE + INTERVAL '7 days') THEN 'Rappel à envoyer'
                    WHEN r.rappel_1_jour_envoye = false AND r.date_reunion <= (CURRENT_DATE + INTERVAL '1 day') THEN 'Rappel urgent'
                    WHEN r.statut = 'terminee' AND r.note_globale IS NULL AND r.date_reunion >= (CURRENT_DATE - INTERVAL '7 days') THEN 'Évaluation en attente'
                    ELSE NULL
                END as action_requise
            FROM reunions r
            INNER JOIN type_reunions tr ON r.type_reunion_id = tr.id
            WHERE r.date_reunion >= (CURRENT_DATE - INTERVAL '7 days')
                AND r.date_reunion <= (CURRENT_DATE + INTERVAL '30 days')
                AND r.deleted_at IS NULL
        ");

        // Vue filtrée pour les actions requises (exclut les NULL)
        DB::statement("
            CREATE VIEW reunions_actions_a_traiter AS
            SELECT * FROM reunions_action_requise
            WHERE action_requise IS NOT NULL
            ORDER BY date_reunion ASC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS reunions_actions_a_traiter");
        DB::statement("DROP VIEW IF EXISTS reunions_action_requise");
        DB::statement("DROP VIEW IF EXISTS statistiques_participation_reunions");
        DB::statement("DROP VIEW IF EXISTS reunions_du_jour");
        DB::statement("DROP VIEW IF EXISTS reunions_a_venir");

        // Suppression de la table
        Schema::dropIfExists('reunions');
    }
};
