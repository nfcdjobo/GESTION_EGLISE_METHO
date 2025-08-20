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
        Schema::create('events', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Informations de base
            $table->string('titre', 200)->comment('Titre de lévénement');
            $table->string('sous_titre', 200)->nullable()->comment('Sous-titre de lévénement');
            $table->text('description')->nullable()->comment('Description détaillée');
            $table->text('resume_court', 500)->nullable()->comment('Résumé court pour aperçu');
            $table->string('slug', 250)->unique()->comment('URL slug pour lévénement');

            // Type et catégorie
            $table->enum('type_evenement', [
                'conference',          // Conférence
                'seminaire',          // Séminaire
                'atelier',            // Atelier
                'formation',          // Formation
                'celebration',        // Célébration
                'festival',           // Festival
                'concert',            // Concert
                'spectacle',          // Spectacle
                'exposition',         // Exposition
                'competition',        // Compétition
                'ceremonie',          // Cérémonie
                'rencontre',          // Rencontre
                'sortie',             // Sortie/excursion
                'pelerinage',         // Pèlerinage
                'retraite',           // Retraite spirituelle
                'jeune_priere',       // Jeûne et prière
                'evangelisation',     // Événement d'évangélisation
                'social',             // Événement social
                'caritatif',          // Événement caritatif
                'culturel',           // Événement culturel
                'sportif',            // Événement sportif
                'anniversaire',       // Anniversaire
                'inauguration',       // Inauguration
                'autre'               // Autre type
            ])->comment('Type dévénement');

            $table->enum('categorie', [
                'spirituel',          // Spirituel
                'educatif',           // Éducatif
                'social',             // Social
                'culturel',           // Culturel
                'sportif',            // Sportif
                'caritatif',          // Caritatif
                'administratif',      // Administratif
                'technique',          // Technique
                'formation',          // Formation
                'divertissement'      // Divertissement
            ])->comment('Catégorie de lévénement');

            // Planification temporelle
            $table->date('date_debut')->comment('Date de début');
            $table->date('date_fin')->nullable()->comment('Date de fin');
            $table->time('heure_debut')->comment('Heure de début');
            $table->time('heure_fin')->nullable()->comment('Heure de fin');
            $table->boolean('evenement_multi_jours')->default(false)->comment('Événement sur plusieurs jours');
            $table->json('horaires_detailles')->nullable()->comment('Horaires détaillés (JSON)');
            $table->string('fuseau_horaire', 50)->default('Africa/Abidjan')->comment('Fuseau horaire');

            // Lieu et logistique
            $table->string('lieu_nom', 200)->comment('Nom du lieu');
            $table->text('lieu_adresse')->nullable()->comment('Adresse complète du lieu');
            $table->string('lieu_ville', 100)->nullable()->comment('Ville');
            $table->string('lieu_region', 100)->nullable()->comment('Région');
            $table->string('lieu_pays', 100)->default('Côte dIvoire')->comment('Pays');
            $table->decimal('lieu_latitude', 10, 8)->nullable()->comment('Latitude du lieu');
            $table->decimal('lieu_longitude', 11, 8)->nullable()->comment('Longitude du lieu');
            $table->text('instructions_acces')->nullable()->comment('Instructions daccès');
            $table->text('transport_organise')->nullable()->comment('Transport organisé');

            // Capacité et participation
            $table->integer('capacite_totale')->nullable()->comment('Capacité totale');
            $table->integer('places_reservees')->default(0)->comment('Places réservées');
            $table->integer('places_disponibles')->nullable()->comment('Places disponibles');
            $table->integer('nombre_inscrits')->default(0)->comment('Nombre dinscrits');
            $table->integer('nombre_participants')->nullable()->comment('Nombre réel de participants');
            $table->boolean('liste_attente')->default(false)->comment('Liste dattente activée');

            // Audience et ciblage
            $table->enum('audience_cible', [
                'tous',               // Tous
                'membres',            // Membres de l'église
                'jeunes',             // Jeunes
                'adultes',            // Adultes
                'enfants',            // Enfants
                'familles',           // Familles
                'femmes',             // Femmes
                'hommes',             // Hommes
                'couples',            // Couples
                'celibataires',       // Célibataires
                'nouveaux_membres',   // Nouveaux membres
                'leadership',         // Leadership
                'invite_seulement',   // Sur invitation seulement
                'public_externe'      // Public externe
            ])->default('tous')->comment('Audience ciblée');

            $table->integer('age_minimum')->nullable()->comment('Âge minimum');
            $table->integer('age_maximum')->nullable()->comment('Âge maximum');
            $table->boolean('ouvert_public')->default(true)->comment('Ouvert au public');
            $table->boolean('necessite_invitation')->default(false)->comment('Nécessite une invitation');

            // Inscription et tarification
            $table->boolean('inscription_requise')->default(false)->comment('Inscription obligatoire');
            $table->date('date_ouverture_inscription')->nullable()->comment('Date douverture des inscriptions');
            $table->date('date_fermeture_inscription')->nullable()->comment('Date de fermeture des inscriptions');
            $table->boolean('inscription_payante')->default(false)->comment('Inscription payante');
            $table->decimal('prix_inscription', 10, 2)->nullable()->comment('Prix dinscription');
            $table->json('tarifs_categories')->nullable()->comment('Tarifs par catégorie (JSON)');
            $table->text('conditions_inscription')->nullable()->comment('Conditions dinscription');

            // Responsables et organisation
            $table->uuid('organisateur_principal_id')->nullable()->comment('Organisateur principal');
            $table->uuid('coordinateur_id')->nullable()->comment('Coordinateur');
            $table->uuid('responsable_logistique_id')->nullable()->comment('Responsable logistique');
            $table->uuid('responsable_communication_id')->nullable()->comment('Responsable communication');
            $table->json('equipe_organisation')->nullable()->comment('Équipe dorganisation (JSON)');
            $table->json('partenaires')->nullable()->comment('Partenaires (JSON)');
            $table->json('sponsors')->nullable()->comment('Sponsors (JSON)');

            // Programme et contenu
            $table->json('programme_detaille')->nullable()->comment('Programme détaillé (JSON)');
            $table->json('intervenants')->nullable()->comment('Intervenants (JSON)');
            $table->text('objectifs')->nullable()->comment('Objectifs de lévénement');
            $table->text('programme_enfants')->nullable()->comment('Programme spécial enfants');
            $table->json('activites_annexes')->nullable()->comment('Activités annexes (JSON)');

            // Statut et workflow
            $table->enum('statut', [
                'brouillon',          // En cours de planification
                'planifie',           // Planifié
                'en_promotion',       // En cours de promotion
                'ouvert_inscription', // Inscriptions ouvertes
                'complet',            // Complet
                'en_cours',           // En cours
                'termine',            // Terminé
                'annule',             // Annulé
                'reporte',            // Reporté
                'archive'             // Archivé
            ])->default('brouillon')->comment('Statut de lévénement');

            $table->enum('priorite', [
                'faible',
                'normale',
                'haute',
                'urgente'
            ])->default('normale')->comment('Niveau de priorité');

            // Annulation et report
            $table->uuid('annule_par')->nullable()->comment('Qui a annulé lévénement');
            $table->timestamp('annule_le')->nullable()->comment('Date dannulation');
            $table->text('motif_annulation')->nullable()->comment('Motif dannulation');
            $table->date('nouvelle_date')->nullable()->comment('Nouvelle date si reporté');

            // Communication et promotion
            $table->text('message_promotion')->nullable()->comment('Message promotionnel');
            $table->string('hashtag_officiel', 100)->nullable()->comment('Hashtag officiel');
            $table->json('canaux_communication')->nullable()->comment('Canaux de communication (JSON)');
            $table->boolean('publication_site_web')->default(true)->comment('Publier sur le site web');
            $table->boolean('publication_reseaux_sociaux')->default(false)->comment('Publier sur réseaux sociaux');
            $table->boolean('envoi_newsletter')->default(false)->comment('Envoyer par newsletter');

            // Médias et ressources
            $table->string('image_principale')->nullable()->comment('Image principale');
            $table->json('galerie_images')->nullable()->comment('Galerie dimages (JSON)');
            $table->string('video_presentation')->nullable()->comment('Vidéo de présentation');
            $table->json('documents_joints')->nullable()->comment('Documents joints (JSON)');
            $table->string('site_web_evenement')->nullable()->comment('Site web dédié');

            // Diffusion et enregistrement
            $table->boolean('diffusion_en_ligne')->default(false)->comment('Diffusion en ligne');
            $table->string('lien_diffusion')->nullable()->comment('Lien de diffusion');
            $table->boolean('enregistrement_autorise')->default(false)->comment('Enregistrement autorisé');
            $table->string('lien_enregistrement')->nullable()->comment('Lien vers lenregistrement');
            $table->boolean('photos_autorisees')->default(true)->comment('Photos autorisées');

            // Budget et finances
            $table->decimal('budget_prevu', 15, 2)->nullable()->comment('Budget prévisionnel');
            $table->decimal('cout_realise', 15, 2)->nullable()->comment('Coût réalisé');
            $table->decimal('recettes_inscriptions', 15, 2)->nullable()->comment('Recettes des inscriptions');
            $table->decimal('recettes_sponsors', 15, 2)->nullable()->comment('Recettes des sponsors');
            $table->json('detail_budget')->nullable()->comment('Détail du budget (JSON)');
            $table->string('responsable_finances')->nullable()->comment('Responsable des finances');

            // Évaluation et feedback
            $table->decimal('note_globale', 3, 1)->nullable()->comment('Note globale (1-10)');
            $table->decimal('note_organisation', 3, 1)->nullable()->comment('Note organisation');
            $table->decimal('note_contenu', 3, 1)->nullable()->comment('Note contenu');
            $table->decimal('note_lieu', 3, 1)->nullable()->comment('Note lieu');
            $table->decimal('taux_satisfaction', 5, 2)->nullable()->comment('Taux de satisfaction (%)');
            $table->text('feedback_participants')->nullable()->comment('Feedback des participants');
            $table->text('points_positifs')->nullable()->comment('Points positifs');
            $table->text('points_amelioration')->nullable()->comment('Points à améliorer');

            // Récurrence et série
            $table->boolean('evenement_recurrent')->default(false)->comment('Événement récurrent');
            $table->enum('frequence_recurrence', [
                'hebdomadaire',
                'mensuelle',
                'trimestrielle',
                'semestrielle',
                'annuelle'
            ])->nullable()->comment('Fréquence de récurrence');
            $table->uuid('evenement_parent_id')->nullable()->comment('Événement parent si récurrent');
            $table->date('prochaine_occurrence')->nullable()->comment('Prochaine occurrence');

            // Statistiques et engagement
            $table->integer('nombre_vues')->default(0)->comment('Nombre de vues');
            $table->integer('nombre_partages')->default(0)->comment('Nombre de partages');
            $table->integer('nombre_likes')->default(0)->comment('Nombre de likes');
            $table->json('statistiques_participation')->nullable()->comment('Statistiques de participation (JSON)');

            // Notifications et rappels
            $table->boolean('rappel_1_semaine')->default(false)->comment('Rappel 1 semaine avant');
            $table->boolean('rappel_1_jour')->default(false)->comment('Rappel 1 jour avant');
            $table->boolean('rappel_1_heure')->default(false)->comment('Rappel 1 heure avant');
            $table->timestamp('dernier_rappel_envoye')->nullable()->comment('Dernier rappel envoyé');

            // Conformité et autorisations
            $table->text('autorisations_requises')->nullable()->comment('Autorisations requises');
            $table->boolean('assurance_souscrite')->default(false)->comment('Assurance souscrite');
            $table->text('mesures_securite')->nullable()->comment('Mesures de sécurité');
            $table->text('protocole_sanitaire')->nullable()->comment('Protocole sanitaire');

            // Météo et contexte
            $table->string('previsions_meteo')->nullable()->comment('Prévisions météo');
            $table->text('plan_b_intemperies')->nullable()->comment('Plan B en cas dintempéries');
            $table->text('contexte_particulier')->nullable()->comment('Contexte particulier');

            // Notes et historique
            $table->text('notes_organisateur')->nullable()->comment('Notes de lorganisateur');
            $table->text('notes_admin')->nullable()->comment('Notes administratives');
            $table->text('historique_modifications')->nullable()->comment('Historique des modifications');
            $table->text('retour_experience')->nullable()->comment('Retour dexpérience');

            // Audit et traçabilité
            $table->uuid('cree_par')->nullable()->comment('Utilisateur qui a créé lévénement');
            $table->uuid('modifie_par')->nullable()->comment('Dernier utilisateur ayant modifié');
            $table->timestamp('derniere_activite')->nullable()->comment('Dernière activité');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key
            $table->foreign('organisateur_principal_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('coordinateur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('responsable_logistique_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('responsable_communication_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('annule_par')->references('id')->on('users')->onDelete('set null');

            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances
            $table->index(['date_debut', 'date_fin'], 'idx_events_periode');
            $table->index(['statut', 'date_debut'], 'idx_events_statut_date');
            $table->index(['type_evenement', 'statut'], 'idx_events_type_statut');
            $table->index(['organisateur_principal_id', 'statut'], 'idx_events_org_statut');
            $table->index(['audience_cible', 'ouvert_public'], 'idx_events_audience');
            $table->index(['inscription_requise', 'date_fermeture_inscription'], 'idx_events_inscription');
            $table->index(['lieu_ville', 'lieu_region'], 'idx_events_localisation');
            $table->index(['evenement_recurrent', 'prochaine_occurrence'], 'idx_events_recurrence');
            $table->index(['categorie', 'type_evenement'], 'idx_events_cat_type');
            $table->index(['priorite', 'date_debut'], 'idx_events_priorite_date');

            // Index pour la géolocalisation
            $table->index(['lieu_latitude', 'lieu_longitude'], 'idx_events_geo');

            // Index pour les recherches
            $table->index('titre', 'idx_events_titre');
            $table->index('slug', 'idx_events_slug');
            $table->index(['publication_site_web', 'statut'], 'idx_events_publication');

            // Index composé pour les requêtes complexes
            $table->index([
                'statut',
                'date_debut',
                'type_evenement',
                'ouvert_public'
            ], 'idx_events_recherche_publique');
        });

         // Ajouter la contrainte de clé étrangère après la création de la table
        Schema::table('events', function (Blueprint $table) {
            $table->foreign('evenement_parent_id')->references('id')->on('events')->onDelete('set null');
        });

        // Commentaire sur la table
        // DB::statement("ALTER TABLE events COMMENT = 'Gestion complète des événements de léglise avec inscription et suivi'");
        DB::statement("COMMENT ON TABLE events IS 'Gestion complète des événements de léglise avec inscription et suivi';");

        // // Vue pour les événements à venir
        // DB::statement("
        //     CREATE VIEW events_a_venir AS
        //     SELECT
        //         e.*,
        //         CONCAT(org.prenom, ' ', org.nom) as nom_organisateur,
        //         CONCAT(coord.prenom, ' ', coord.nom) as nom_coordinateur,
        //         DATEDIFF(e.date_debut, CURDATE()) as jours_restants,
        //         CASE
        //             WHEN e.inscription_requise = true AND e.date_fermeture_inscription IS NOT NULL AND e.date_fermeture_inscription < CURDATE() THEN 'Inscriptions fermées'
        //             WHEN e.places_disponibles IS NOT NULL AND e.nombre_inscrits >= e.places_disponibles THEN 'Complet'
        //             WHEN e.liste_attente = true AND e.nombre_inscrits >= e.places_disponibles THEN 'Liste dattente'
        //             WHEN e.inscription_requise = true THEN 'Inscriptions ouvertes'
        //             ELSE 'Libre daccès'
        //         END as statut_inscription,
        //         CASE
        //             WHEN e.places_disponibles IS NOT NULL THEN ROUND((e.nombre_inscrits / e.places_disponibles) * 100, 2)
        //             ELSE NULL
        //         END as pourcentage_remplissage
        //     FROM events e
        //     LEFT JOIN users org ON e.organisateur_principal_id = org.id
        //     LEFT JOIN users coord ON e.coordinateur_id = coord.id
        //     WHERE e.date_debut >= CURDATE()
        //         AND e.statut IN ('planifie', 'en_promotion', 'ouvert_inscription')
        //         AND e.deleted_at IS NULL
        //     ORDER BY e.date_debut ASC, e.heure_debut ASC
        // ");

        // // Vue pour les événements publics
        // DB::statement("
        //     CREATE VIEW events_publics AS
        //     SELECT
        //         e.id,
        //         e.titre,
        //         e.sous_titre,
        //         e.description,
        //         e.resume_court,
        //         e.slug,
        //         e.type_evenement,
        //         e.categorie,
        //         e.date_debut,
        //         e.date_fin,
        //         e.heure_debut,
        //         e.heure_fin,
        //         e.lieu_nom,
        //         e.lieu_adresse,
        //         e.lieu_ville,
        //         e.image_principale,
        //         e.inscription_requise,
        //         e.inscription_payante,
        //         e.prix_inscription,
        //         e.statut_inscription,
        //         e.nombre_inscrits,
        //         e.places_disponibles,
        //         CONCAT(org.prenom, ' ', org.nom) as nom_organisateur
        //     FROM events_a_venir e
        //     LEFT JOIN users org ON e.organisateur_principal_id = org.id
        //     WHERE e.ouvert_public = true
        //         AND e.publication_site_web = true
        //         AND e.statut NOT IN ('annule', 'archive')
        //     ORDER BY e.date_debut ASC
        // ");

        // // Vue pour les statistiques d'événements
        // DB::statement("
        //     CREATE VIEW statistiques_events AS
        //     SELECT
        //         e.type_evenement,
        //         e.categorie,
        //         COUNT(*) as nombre_events,
        //         AVG(e.nombre_participants) as participants_moyenne,
        //         SUM(e.nombre_participants) as participants_total,
        //         AVG(e.taux_satisfaction) as satisfaction_moyenne,
        //         AVG(e.note_globale) as note_moyenne,
        //         SUM(e.recettes_inscriptions) as recettes_totales,
        //         COUNT(CASE WHEN e.statut = 'termine' THEN 1 END) as events_termines,
        //         COUNT(CASE WHEN e.statut = 'annule' THEN 1 END) as events_annules
        //     FROM events e
        //     WHERE e.deleted_at IS NULL
        //         AND e.date_debut >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)
        //     GROUP BY e.type_evenement, e.categorie
        //     ORDER BY nombre_events DESC
        // ");

        // // Vue pour les événements nécessitant une action
        // DB::statement("
        //     CREATE VIEW events_action_requise AS
        //     SELECT
        //         e.*,
        //         CONCAT(org.prenom, ' ', org.nom) as nom_organisateur,
        //         CASE
        //             WHEN e.date_debut <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND e.statut = 'brouillon' THEN 'Finalisation urgente'
        //             WHEN e.inscription_requise = true AND e.date_ouverture_inscription <= CURDATE() AND e.statut != 'ouvert_inscription' THEN 'Ouvrir inscriptions'
        //             WHEN e.date_fermeture_inscription <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) AND e.statut = 'ouvert_inscription' THEN 'Clôture inscriptions proche'
        //             WHEN e.rappel_1_semaine = false AND e.date_debut <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 'Rappel 1 semaine'
        //             WHEN e.rappel_1_jour = false AND e.date_debut <= DATE_ADD(CURDATE(), INTERVAL 1 DAY) THEN 'Rappel urgent'
        //             WHEN e.statut = 'termine' AND e.note_globale IS NULL AND e.date_debut >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 'Évaluation en attente'
        //             ELSE 'Autre'
        //         END as action_requise,
        //         DATEDIFF(e.date_debut, CURDATE()) as jours_avant_event
        //     FROM events e
        //     LEFT JOIN users org ON e.organisateur_principal_id = org.id
        //     WHERE e.date_debut >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        //         AND e.date_debut <= DATE_ADD(CURDATE(), INTERVAL 30 DAY)
        //         AND e.statut NOT IN ('annule', 'archive')
        //         AND e.deleted_at IS NULL
        //     HAVING action_requise != 'Autre'
        //     ORDER BY
        //         CASE action_requise
        //             WHEN 'Finalisation urgente' THEN 1
        //             WHEN 'Rappel urgent' THEN 2
        //             WHEN 'Ouvrir inscriptions' THEN 3
        //             ELSE 4
        //         END,
        //         e.date_debut ASC
        // ");


        // Vue pour les événements à venir
DB::statement("
    CREATE OR REPLACE VIEW events_a_venir AS
    SELECT
        e.*,
        (org.prenom || ' ' || org.nom) AS nom_organisateur,
        (coord.prenom || ' ' || coord.nom) AS nom_coordinateur,
        (e.date_debut - CURRENT_DATE) AS jours_restants,
        CASE
            WHEN e.inscription_requise = true AND e.date_fermeture_inscription IS NOT NULL AND e.date_fermeture_inscription < CURRENT_DATE THEN 'Inscriptions fermées'
            WHEN e.places_disponibles IS NOT NULL AND e.nombre_inscrits >= e.places_disponibles THEN 'Complet'
            WHEN e.liste_attente = true AND e.nombre_inscrits >= e.places_disponibles THEN 'Liste dattente'
            WHEN e.inscription_requise = true THEN 'Inscriptions ouvertes'
            ELSE 'Libre daccès'
        END AS statut_inscription,
        CASE
            WHEN e.places_disponibles IS NOT NULL THEN ROUND((e.nombre_inscrits::numeric / e.places_disponibles::numeric) * 100, 2)
            ELSE NULL
        END AS pourcentage_remplissage
    FROM events e
    LEFT JOIN users org ON e.organisateur_principal_id = org.id
    LEFT JOIN users coord ON e.coordinateur_id = coord.id
    WHERE e.date_debut >= CURRENT_DATE
      AND e.statut IN ('planifie', 'en_promotion', 'ouvert_inscription')
      AND e.deleted_at IS NULL
    ORDER BY e.date_debut ASC, e.heure_debut ASC
");

// Vue pour les événements publics
DB::statement("
    CREATE OR REPLACE VIEW events_publics AS
    SELECT
        e.id,
        e.titre,
        e.sous_titre,
        e.description,
        e.resume_court,
        e.slug,
        e.type_evenement,
        e.categorie,
        e.date_debut,
        e.date_fin,
        e.heure_debut,
        e.heure_fin,
        e.lieu_nom,
        e.lieu_adresse,
        e.lieu_ville,
        e.image_principale,
        e.inscription_requise,
        e.inscription_payante,
        e.prix_inscription,
        e.statut_inscription,
        e.nombre_inscrits,
        e.places_disponibles,
        (org.prenom || ' ' || org.nom) AS nom_organisateur
    FROM events_a_venir e
    LEFT JOIN users org ON e.organisateur_principal_id = org.id
    WHERE e.ouvert_public = true
      AND e.publication_site_web = true
      AND e.statut NOT IN ('annule', 'archive')
    ORDER BY e.date_debut ASC
");

// Vue pour les statistiques d'événements
DB::statement("
    CREATE OR REPLACE VIEW statistiques_events AS
    SELECT
        e.type_evenement,
        e.categorie,
        COUNT(*) AS nombre_events,
        AVG(e.nombre_participants) AS participants_moyenne,
        SUM(e.nombre_participants) AS participants_total,
        AVG(e.taux_satisfaction) AS satisfaction_moyenne,
        AVG(e.note_globale) AS note_moyenne,
        SUM(e.recettes_inscriptions) AS recettes_totales,
        COUNT(*) FILTER (WHERE e.statut = 'termine') AS events_termines,
        COUNT(*) FILTER (WHERE e.statut = 'annule') AS events_annules
    FROM events e
    WHERE e.deleted_at IS NULL
      AND e.date_debut >= (CURRENT_DATE - INTERVAL '1 year')
    GROUP BY e.type_evenement, e.categorie
    ORDER BY nombre_events DESC
");

// // Vue pour les événements nécessitant une action
// DB::statement("
//     CREATE OR REPLACE VIEW events_action_requise AS
//     SELECT
//         e.*,
//         (org.prenom || ' ' || org.nom) AS nom_organisateur,
//         CASE
//             WHEN e.date_debut <= (CURRENT_DATE + INTERVAL '7 days') AND e.statut = 'brouillon' THEN 'Finalisation urgente'
//             WHEN e.inscription_requise = true AND e.date_ouverture_inscription <= CURRENT_DATE AND e.statut != 'ouvert_inscription' THEN 'Ouvrir inscriptions'
//             WHEN e.date_fermeture_inscription <= (CURRENT_DATE + INTERVAL '3 days') AND e.statut = 'ouvert_inscription' THEN 'Clôture inscriptions proche'
//             WHEN e.rappel_1_semaine = false AND e.date_debut <= (CURRENT_DATE + INTERVAL '7 days') THEN 'Rappel 1 semaine'
//             WHEN e.rappel_1_jour = false AND e.date_debut <= (CURRENT_DATE + INTERVAL '1 day') THEN 'Rappel urgent'
//             WHEN e.statut = 'termine' AND e.note_globale IS NULL AND e.date_debut >= (CURRENT_DATE - INTERVAL '7 days') THEN 'Évaluation en attente'
//             ELSE 'Autre'
//         END AS action_requise,
//         (e.date_debut - CURRENT_DATE) AS jours_avant_event
//     FROM events e
//     LEFT JOIN users org ON e.organisateur_principal_id = org.id
//     WHERE e.date_debut >= (CURRENT_DATE - INTERVAL '7 days')
//       AND e.date_debut <= (CURRENT_DATE + INTERVAL '30 days')
//       AND e.statut NOT IN ('annule', 'archive')
//       AND e.deleted_at IS NULL
//     HAVING action_requise != 'Autre'
//     ORDER BY
//         CASE action_requise
//             WHEN 'Finalisation urgente' THEN 1
//             WHEN 'Rappel urgent' THEN 2
//             WHEN 'Ouvrir inscriptions' THEN 3
//             ELSE 4
//         END,
//         e.date_debut ASC
// ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        // DB::statement("DROP VIEW IF EXISTS events_action_requise");
        DB::statement("DROP VIEW IF EXISTS statistiques_events");
        DB::statement("DROP VIEW IF EXISTS events_publics");
        DB::statement("DROP VIEW IF EXISTS events_a_venir");

        // Suppression de la table
        Schema::dropIfExists('events');
    }
};
