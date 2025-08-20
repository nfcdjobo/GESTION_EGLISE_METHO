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
        Schema::create('annonces', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Informations de base
            $table->string('titre', 200)->comment('Titre de l\'annonce');
            $table->text('contenu')->comment('Contenu principal de l\'annonce');
            $table->text('resume_court')->nullable()->comment('Résumé court pour aperçu');
            $table->string('sous_titre', 200)->nullable()->comment('Sous-titre optionnel');

            // Type et catégorie
            $table->enum('type_annonce', [
                'evenement',            // Annonce d'événement
                'administrative',       // Annonce administrative
                'pastorale',           // Message pastoral
                'urgence',             // Annonce d'urgence
                'deces',               // Annonce de décès
                'mariage',             // Annonce de mariage
                'naissance',           // Annonce de naissance
                'formation',           // Annonce de formation
                'ministerielle',       // Annonce ministérielle
                'financiere',          // Annonce financière
                'sociale',             // Événement social
                'mission',             // Annonce mission
                'priere',              // Demande de prière
                'felicitation',        // Félicitations
                'remerciement',        // Remerciements
                'information'          // Information générale
            ])->comment('Type d\'annonce');

            $table->enum('categorie', [
                'spirituel',           // Contenu spirituel
                'administratif',       // Administratif
                'social',              // Social/communautaire
                'formation',           // Formation/enseignement
                'ministeriel',         // Activités ministérielles
                'finance',             // Financier
                'technique',           // Technique/logistique
                'personnel'            // Personnel/RH
            ])->comment('Catégorie de l\'annonce');

            // Priorité et importance
            $table->enum('niveau_priorite', [
                'info',                // Information simple
                'important',           // Important
                'urgent',              // Urgent
                'critique'             // Critique/d'urgence
            ])->default('info')->comment('Niveau de priorité');

            $table->enum('niveau_importance', [
                'faible',              // Importance faible
                'normale',             // Importance normale
                'haute',               // Haute importance
                'critique'             // Importance critique
            ])->default('normale')->comment('Niveau d\'importance');

            // Audience et visibilité
            $table->enum('audience_cible', [
                'tous',                // Tous les membres
                'membres_actifs',      // Membres actifs uniquement
                'leadership',          // Leadership seulement
                'ministeres',          // Responsables ministères
                'jeunes',              // Jeunes
                'adultes',             // Adultes
                'enfants_parents',     // Enfants et parents
                'femmes',              // Femmes
                'hommes',              // Hommes
                'nouveaux_membres',    // Nouveaux membres
                'visiteurs',           // Visiteurs
                'conseil',             // Conseil d'église
                'personnel'            // Personnel église
            ])->default('tous')->comment('Audience ciblée');

            $table->json('groupes_specifiques')->nullable()->comment('Groupes spécifiques ciblés (JSON)');
            $table->json('ministeres_cibles')->nullable()->comment('Ministères ciblés (JSON)');
            $table->json('classes_cibles')->nullable()->comment('Classes ciblées (JSON)');

            // Planification temporelle
            $table->timestamp('publie_le')->nullable()->comment('Date/heure de publication');
            $table->timestamp('expire_le')->nullable()->comment('Date/heure d\'expiration');
            $table->date('date_evenement')->nullable()->comment('Date de l\'événement annoncé');
            $table->time('heure_evenement')->nullable()->comment('Heure de l\'événement');
            $table->boolean('publication_programmee')->default(false)->comment('Publication programmée');
            $table->boolean('rappel_active')->default(false)->comment('Rappels activés');

            // Canaux de diffusion
            $table->boolean('afficher_site_web')->default(true)->comment('Afficher sur le site web');
            $table->boolean('afficher_ecrans')->default(false)->comment('Afficher sur les écrans');
            $table->boolean('envoyer_email')->default(false)->comment('Envoyer par email');
            $table->boolean('envoyer_sms')->default(false)->comment('Envoyer par SMS');
            $table->boolean('publier_reseaux_sociaux')->default(false)->comment('Publier sur réseaux sociaux');
            $table->boolean('annoncer_culte')->default(false)->comment('Annoncer pendant le culte');
            $table->boolean('afficher_app_mobile')->default(true)->comment('Afficher sur l\'app mobile');

            // Médias et ressources
            $table->string('image_principale')->nullable()->comment('Image principale de l\'annonce');
            $table->json('images_annexes')->nullable()->comment('Images supplémentaires (JSON)');
            $table->string('video_url')->nullable()->comment('URL de vidéo associée');
            $table->json('documents_joints')->nullable()->comment('Documents joints (JSON)');
            $table->string('lien_externe')->nullable()->comment('Lien externe');
            $table->string('lien_inscription')->nullable()->comment('Lien d\'inscription');
            $table->text('call_to_action')->nullable()->comment('Appel à l\'action');

            // Contact et informations pratiques
            $table->uuid('contact_principal_id')->nullable()->comment('Contact principal pour cette annonce');
            $table->string('telephone_contact', 20)->nullable()->comment('Téléphone de contact');
            $table->string('email_contact')->nullable()->comment('Email de contact');
            $table->text('informations_pratiques')->nullable()->comment('Informations pratiques');
            $table->text('instructions_speciales')->nullable()->comment('Instructions spéciales');

            // Lieu (si applicable)
            $table->string('lieu_evenement')->nullable()->comment('Lieu de l\'événement');
            $table->text('adresse_complete')->nullable()->comment('Adresse complète');
            $table->decimal('latitude', 10, 8)->nullable()->comment('Latitude GPS');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Longitude GPS');

            // Statut et workflow
            $table->enum('statut', [
                'brouillon',           // En cours de rédaction
                'en_attente',          // En attente de validation
                'approuvee',           // Approuvée
                'publiee',             // Publiée
                'expiree',             // Expirée
                'annulee',             // Annulée
                'archivee'             // Archivée
            ])->default('brouillon')->comment('Statut de l\'annonce');

            $table->uuid('approuvee_par')->nullable()->comment('Qui a approuvé l\'annonce');
            $table->timestamp('approuvee_le')->nullable()->comment('Date d\'approbation');
            $table->text('commentaires_approbation')->nullable()->comment('Commentaires d\'approbation');
            $table->text('motif_refus')->nullable()->comment('Motif de refus si applicable');

            // Récurrence et répétition (sans contrainte FK pour l'instant)
            $table->boolean('est_recurrente')->default(false)->comment('Annonce récurrente');
            $table->enum('frequence_recurrence', [
                'quotidienne',
                'hebdomadaire',
                'bimensuelle',
                'mensuelle',
                'trimestrielle',
                'semestrielle',
                'annuelle'
            ])->nullable()->comment('Fréquence de récurrence');
            $table->date('fin_recurrence')->nullable()->comment('Date de fin de récurrence');
            $table->uuid('annonce_parent_id')->nullable()->comment('Annonce parent si récurrente');

            // Interaction et engagement
            $table->integer('nombre_vues')->default(0)->comment('Nombre de vues');
            $table->integer('nombre_clics')->default(0)->comment('Nombre de clics');
            $table->integer('nombre_partages')->default(0)->comment('Nombre de partages');
            $table->integer('nombre_inscriptions')->default(0)->comment('Nombre d\'inscriptions');
            $table->json('statistiques_interaction')->nullable()->comment('Statistiques détaillées (JSON)');
            $table->decimal('taux_engagement', 5, 2)->nullable()->comment('Taux d\'engagement en %');

            // Notification et rappels
            $table->json('rappels_programmes')->nullable()->comment('Rappels programmés (JSON)');
            $table->timestamp('dernier_rappel_envoye')->nullable()->comment('Dernier rappel envoyé');
            $table->integer('nombre_rappels_envoyes')->default(0)->comment('Nombre de rappels envoyés');
            $table->boolean('notification_admin_envoyee')->default(false)->comment('Notification admin envoyée');

            // Feedback et réactions
            $table->integer('nombre_likes')->default(0)->comment('Nombre de likes');
            $table->integer('nombre_commentaires')->default(0)->comment('Nombre de commentaires');
            $table->json('reactions')->nullable()->comment('Réactions diverses (JSON)');
            $table->text('feedback_recu')->nullable()->comment('Feedback reçu');
            $table->decimal('note_moyenne', 3, 1)->nullable()->comment('Note moyenne (1-10)');

            // Ciblage avancé
            $table->integer('age_min')->nullable()->comment('Âge minimum ciblé');
            $table->integer('age_max')->nullable()->comment('Âge maximum ciblé');
            $table->json('criteres_ciblage')->nullable()->comment('Critères de ciblage avancés (JSON)');
            $table->boolean('membres_uniquement')->default(false)->comment('Réservé aux membres');
            $table->boolean('necessite_inscription')->default(false)->comment('Nécessite une inscription');

            // Modération et contrôle
            $table->boolean('necessite_approbation')->default(true)->comment('Nécessite une approbation');
            $table->boolean('contenu_sensible')->default(false)->comment('Contenu sensible');
            $table->text('tags_moderation')->nullable()->comment('Tags de modération');
            $table->text('notes_moderateur')->nullable()->comment('Notes du modérateur');

            // SEO et référencement
            $table->string('meta_title', 150)->nullable()->comment('Titre SEO');
            $table->text('meta_description')->nullable()->comment('Description SEO');
            $table->string('slug', 200)->nullable()->unique()->comment('URL slug');
            $table->json('mots_cles')->nullable()->comment('Mots clés pour recherche (JSON)');

            // Analytics et suivi
            $table->json('canaux_diffusion_utilises')->nullable()->comment('Canaux utilisés (JSON)');
            $table->timestamp('derniere_interaction')->nullable()->comment('Dernière interaction');
            $table->json('performances_canal')->nullable()->comment('Performance par canal (JSON)');
            $table->decimal('cout_diffusion', 10, 2)->nullable()->comment('Coût de diffusion');

            // Archivage et historique
            $table->date('date_archivage')->nullable()->comment('Date d\'archivage');
            $table->text('raison_archivage')->nullable()->comment('Raison d\'archivage');
            $table->json('historique_modifications')->nullable()->comment('Historique des modifications (JSON)');
            $table->boolean('conservee_historique')->default(true)->comment('Conservée dans l\'historique');

            // Audit et traçabilité
            $table->uuid('cree_par')->nullable()->comment('Utilisateur qui a créé l\'annonce');
            $table->uuid('modifie_par')->nullable()->comment('Dernier utilisateur ayant modifié');
            $table->text('commentaires_auteur')->nullable()->comment('Commentaires de l\'auteur');
            $table->text('notes_admin')->nullable()->comment('Notes administratives');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key (sauf les auto-référentielles)
            $table->foreign('contact_principal_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approuvee_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances
            $table->index(['statut', 'publie_le'], 'idx_annonces_statut_publie');
            $table->index(['type_annonce', 'audience_cible'], 'idx_annonces_type_audience');
            $table->index(['niveau_priorite', 'publie_le'], 'idx_annonces_priorite_date');
            $table->index(['expire_le', 'statut'], 'idx_annonces_expiration');
            $table->index(['date_evenement', 'type_annonce'], 'idx_annonces_evenement');
            $table->index(['categorie', 'statut'], 'idx_annonces_categorie_statut');
            $table->index(['est_recurrente', 'fin_recurrence'], 'idx_annonces_recurrence');
            $table->index(['necessite_approbation', 'statut'], 'idx_annonces_approbation');
            $table->index(['audience_cible', 'publie_le'], 'idx_annonces_audience_date');
            $table->index(['cree_par', 'statut'], 'idx_annonces_auteur_statut');

            // Index pour l'affichage public
            $table->index(['afficher_site_web', 'statut', 'publie_le'], 'idx_annonces_site_web');
            $table->index(['afficher_ecrans', 'niveau_priorite'], 'idx_annonces_ecrans');
            $table->index(['annoncer_culte', 'date_evenement'], 'idx_annonces_culte');

            // Index pour les recherches
            $table->index('titre', 'idx_annonces_titre');
            $table->index('slug', 'idx_annonces_slug');
            $table->index(['nombre_vues', 'nombre_clics'], 'idx_annonces_engagement');

            // Index composé pour les requêtes complexes
            $table->index([
                'statut',
                'audience_cible',
                'niveau_priorite',
                'publie_le'
            ], 'idx_annonces_diffusion');
        });

        // Ajouter les contraintes auto-référentielles après la création de la table
        Schema::table('annonces', function (Blueprint $table) {
            $table->foreign('annonce_parent_id')->references('id')->on('annonces')->onDelete('set null');
        });

        // Commentaire sur la table (PostgreSQL syntax)
        DB::statement("COMMENT ON TABLE annonces IS 'Système complet de gestion des annonces de l''église avec workflow et multi-canal'");

        // Vue pour les annonces actives (PostgreSQL syntax)
        DB::statement("
            CREATE VIEW annonces_actives AS
            SELECT
                a.*,
                CONCAT(contact.prenom, ' ', contact.nom) as nom_contact,
                CONCAT(auteur.prenom, ' ', auteur.nom) as nom_auteur,
                CASE
                    WHEN a.date_evenement IS NOT NULL THEN (a.date_evenement - CURRENT_DATE)
                    ELSE NULL
                END as jours_avant_evenement,
                CASE
                    WHEN a.expire_le IS NOT NULL THEN EXTRACT(DAY FROM (a.expire_le - NOW()))
                    ELSE NULL
                END as jours_avant_expiration
            FROM annonces a
            LEFT JOIN users contact ON a.contact_principal_id = contact.id
            LEFT JOIN users auteur ON a.cree_par = auteur.id
            WHERE a.statut = 'publiee'
                AND a.deleted_at IS NULL
                AND (a.expire_le IS NULL OR a.expire_le > NOW())
            ORDER BY a.niveau_priorite DESC, a.publie_le DESC
        ");

        // Vue pour les annonces par audience
        DB::statement("
            CREATE VIEW annonces_par_audience AS
            SELECT
                a.audience_cible,
                a.type_annonce,
                COUNT(*) as nombre_annonces,
                AVG(a.nombre_vues) as vues_moyennes,
                AVG(a.taux_engagement) as engagement_moyen,
                MAX(a.publie_le) as derniere_annonce
            FROM annonces a
            WHERE a.statut = 'publiee'
                AND a.deleted_at IS NULL
            GROUP BY a.audience_cible, a.type_annonce
            ORDER BY nombre_annonces DESC
        ");

        // Vue pour le tableau de bord des annonces
        DB::statement("
            CREATE VIEW tableau_bord_annonces AS
            SELECT
                a.cree_par,
                CONCAT(u.prenom, ' ', u.nom) as nom_auteur,
                COUNT(*) as total_annonces,
                COUNT(CASE WHEN a.statut = 'brouillon' THEN 1 END) as brouillons,
                COUNT(CASE WHEN a.statut = 'en_attente' THEN 1 END) as en_attente,
                COUNT(CASE WHEN a.statut = 'publiee' THEN 1 END) as publiees,
                AVG(a.nombre_vues) as vues_moyennes,
                AVG(a.taux_engagement) as engagement_moyen,
                MAX(a.created_at) as derniere_creation
            FROM annonces a
            LEFT JOIN users u ON a.cree_par = u.id
            WHERE a.deleted_at IS NULL
                AND a.cree_par IS NOT NULL
            GROUP BY a.cree_par, u.prenom, u.nom
            ORDER BY total_annonces DESC
        ");

        // Vue pour les annonces urgentes (PostgreSQL syntax)
        DB::statement("
            CREATE VIEW annonces_urgentes AS
            SELECT
                a.*,
                CONCAT(contact.prenom, ' ', contact.nom) as nom_contact,
                CASE
                    WHEN a.niveau_priorite = 'critique' THEN 'CRITIQUE'
                    WHEN a.niveau_priorite = 'urgent' THEN 'URGENT'
                    WHEN a.date_evenement IS NOT NULL AND a.date_evenement <= (CURRENT_DATE + INTERVAL '2 days') THEN 'ÉVÉNEMENT IMMINENT'
                    WHEN a.expire_le IS NOT NULL AND a.expire_le <= (NOW() + INTERVAL '24 hours') THEN 'EXPIRE BIENTÔT'
                    ELSE 'NORMAL'
                END as niveau_urgence
            FROM annonces a
            LEFT JOIN users contact ON a.contact_principal_id = contact.id
            WHERE a.statut IN ('publiee', 'approuvee')
                AND a.deleted_at IS NULL
                AND (
                    a.niveau_priorite IN ('urgent', 'critique')
                    OR (a.date_evenement IS NOT NULL AND a.date_evenement <= (CURRENT_DATE + INTERVAL '2 days'))
                    OR (a.expire_le IS NOT NULL AND a.expire_le <= (NOW() + INTERVAL '24 hours'))
                )
            ORDER BY
                CASE a.niveau_priorite
                    WHEN 'critique' THEN 1
                    WHEN 'urgent' THEN 2
                    ELSE 3
                END,
                a.date_evenement ASC,
                a.expire_le ASC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS annonces_urgentes");
        DB::statement("DROP VIEW IF EXISTS tableau_bord_annonces");
        DB::statement("DROP VIEW IF EXISTS annonces_par_audience");
        DB::statement("DROP VIEW IF EXISTS annonces_actives");

        // Suppression de la table
        Schema::dropIfExists('annonces');
    }
};
