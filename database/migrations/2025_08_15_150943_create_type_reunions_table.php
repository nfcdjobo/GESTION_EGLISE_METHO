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
        Schema::create('type_reunions', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Informations de base
            $table->string('nom', 150)->comment('Nom du type de réunion');
            $table->string('code', 50)->unique()->comment('Code unique du type de réunion');
            $table->text('description')->nullable()->comment('Description détaillée du type de réunion');
            $table->string('icone', 100)->nullable()->comment('Nom de l\'icône (FontAwesome, etc.)');
            $table->string('couleur', 7)->default('#3498db')->comment('Couleur associée (code hex)');

            // Catégorie et classification
            $table->enum('categorie', [
                'spirituel',          // Réunions spirituelles
                'administratif',      // Réunions administratives
                'formation',          // Formation et enseignement
                'social',            // Événements sociaux
                'ministeriel',       // Réunions ministérielles
                'jeunesse',          // Activités jeunesse
                'femmes',            // Réunions femmes
                'hommes',            // Réunions hommes
                'enfants',           // Activités enfants
                'special'            // Événements spéciaux
            ])->comment('Catégorie de la réunion');

            $table->enum('niveau_acces', [
                'public',            // Ouvert à tous
                'membres',           // Réservé aux membres
                'leadership',        // Réservé au leadership
                'invite',           // Sur invitation uniquement
                'prive'             // Privé/fermé
            ])->default('public')->comment('Niveau d\'accès requis');

            // Configuration temporelle
            $table->enum('frequence_type', [
                'unique',            // Événement unique
                'hebdomadaire',      // Chaque semaine
                'bimensuel',         // Toutes les 2 semaines
                'mensuel',           // Chaque mois
                'trimestriel',       // Chaque trimestre
                'semestriel',        // Chaque semestre
                'annuel',            // Chaque année
                'irregulier'         // Fréquence irrégulière
            ])->default('unique')->comment('Fréquence type par défaut');

            $table->time('duree_standard')->nullable()->comment('Durée standard prévue');
            $table->time('duree_min')->nullable()->comment('Durée minimale');
            $table->time('duree_max')->nullable()->comment('Durée maximale');

            // Paramètres de configuration
            $table->boolean('necessite_preparation')->default(false)->comment('Nécessite une préparation spéciale');
            $table->boolean('necessite_inscription')->default(false)->comment('Inscription obligatoire');
            $table->boolean('a_limite_participants')->default(false)->comment('Nombre de participants limité');
            $table->integer('limite_participants')->nullable()->comment('Limite de participants si applicable');
            $table->boolean('permet_enfants')->default(true)->comment('Enfants autorisés');
            $table->integer('age_minimum')->nullable()->comment('Âge minimum requis');

            // Ressources et logistique
            $table->json('equipements_requis')->nullable()->comment('Équipements nécessaires (JSON array)');
            $table->json('roles_requis')->nullable()->comment('Rôles/responsables requis (JSON)');
            $table->text('materiel_necessaire')->nullable()->comment('Matériel nécessaire');
            $table->text('preparation_requise')->nullable()->comment('Préparation requise');

            // Paramètres spirituels
            $table->boolean('inclut_louange')->default(false)->comment('Inclut un temps de louange');
            $table->boolean('inclut_message')->default(false)->comment('Inclut un message/enseignement');
            $table->boolean('inclut_priere')->default(true)->comment('Inclut un temps de prière');
            $table->boolean('inclut_communion')->default(false)->comment('Peut inclure la communion');
            $table->boolean('permet_temoignages')->default(false)->comment('Permet les témoignages');

            // Gestion financière
            $table->boolean('collecte_offrandes')->default(false)->comment('Collecte d\'offrandes');
            $table->boolean('a_frais_participation')->default(false)->comment('Frais de participation');
            $table->decimal('frais_standard', 10, 2)->nullable()->comment('Frais standard si applicable');
            $table->text('details_frais')->nullable()->comment('Détails des frais');

            // Communication et médias
            $table->boolean('permet_enregistrement')->default(true)->comment('Enregistrement autorisé');
            $table->boolean('permet_diffusion_live')->default(false)->comment('Diffusion en direct autorisée');
            $table->boolean('necessite_promotion')->default(false)->comment('Nécessite une promotion/annonce');
            $table->integer('delai_annonce_jours')->nullable()->comment('Délai d\'annonce en jours');

            // Templates et modèles
            $table->json('modele_ordre_service')->nullable()->comment('Modèle d\'ordre de service (JSON)');
            $table->text('instructions_organisateur')->nullable()->comment('Instructions pour l\'organisateur');
            $table->text('modele_invitation')->nullable()->comment('Modèle d\'invitation');
            $table->text('modele_programme')->nullable()->comment('Modèle de programme');

            // Évaluation et suivi
            $table->boolean('necessite_evaluation')->default(false)->comment('Évaluation post-réunion requise');
            $table->boolean('necessite_rapport')->default(false)->comment('Rapport obligatoire');
            $table->json('criteres_evaluation')->nullable()->comment('Critères d\'évaluation (JSON)');
            $table->text('questions_feedback')->nullable()->comment('Questions pour le feedback');

            // Statistiques et métriques
            $table->json('metriques_importantes')->nullable()->comment('Métriques à suivre (JSON)');
            $table->boolean('compte_conversions')->default(false)->comment('Compter les conversions');
            $table->boolean('compte_baptemes')->default(false)->comment('Compter les baptêmes');
            $table->boolean('compte_nouveaux')->default(true)->comment('Compter les nouveaux visiteurs');

            // Paramètres d'affichage
            $table->boolean('afficher_calendrier_public')->default(true)->comment('Afficher sur calendrier public');
            $table->boolean('afficher_site_web')->default(true)->comment('Afficher sur le site web');
            $table->string('nom_affichage_public')->nullable()->comment('Nom pour affichage public');
            $table->text('description_publique')->nullable()->comment('Description pour le public');

            // État et configuration
            $table->boolean('actif')->default(true)->comment('Type de réunion actif');
            $table->boolean('est_archive')->default(false)->comment('Type archivé');
            $table->integer('ordre_affichage')->default(0)->comment('Ordre d\'affichage');
            $table->integer('priorite')->default(5)->comment('Priorité (1-10)');

            // Règles et politiques
            $table->text('regles_annulation')->nullable()->comment('Règles d\'annulation');
            $table->text('politique_remboursement')->nullable()->comment('Politique de remboursement');
            $table->text('conditions_participation')->nullable()->comment('Conditions de participation');
            $table->text('code_vestimentaire')->nullable()->comment('Code vestimentaire');

            // Informations de gestion
            $table->uuid('responsable_type_id')->nullable()->comment('Responsable par défaut de ce type');
            $table->uuid('cree_par')->nullable()->comment('Membres qui a créé le type');
            $table->uuid('modifie_par')->nullable()->comment('Dernier membres ayant modifié');
            $table->timestamp('derniere_utilisation')->nullable()->comment('Dernière utilisation de ce type');
            $table->integer('nombre_utilisations')->default(0)->comment('Nombre d\'utilisations total');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key
            $table->foreign('responsable_type_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances
            $table->index(['categorie', 'actif'], 'idx_type_reunions_cat_actif');
            $table->index(['niveau_acces', 'actif'], 'idx_type_reunions_acces_actif');
            $table->index(['frequence_type', 'actif'], 'idx_type_reunions_freq_actif');
            $table->index(['actif', 'ordre_affichage'], 'idx_type_reunions_affichage');
            $table->index(['afficher_calendrier_public', 'actif'], 'idx_type_reunions_public');
            $table->index(['priorite', 'ordre_affichage'], 'idx_type_reunions_priorite');
            $table->index('code', 'idx_type_reunions_code');
            $table->index('responsable_type_id', 'idx_type_reunions_responsable');
            $table->index(['derniere_utilisation', 'nombre_utilisations'], 'idx_type_reunions_usage');
            $table->index(['necessite_inscription', 'actif'], 'idx_type_reunions_inscription');

            // Index pour les recherches
            $table->index('nom', 'idx_type_reunions_nom');
            $table->index(['categorie', 'niveau_acces', 'actif'], 'idx_type_reunions_recherche');
        });

        // Commentaire sur la table
        DB::statement("COMMENT ON TABLE type_reunions IS 'Types de réunions et événements configurables pour l''église'");

        // Vue pour les types actifs par catégorie
        DB::statement("
            CREATE VIEW types_reunions_actifs AS
            SELECT
                tr.*,
                CONCAT(resp.prenom, ' ', resp.nom) as nom_responsable_type,
                resp.email as email_responsable
            FROM type_reunions tr
            LEFT JOIN users resp ON tr.responsable_type_id = resp.id
            WHERE tr.actif = true
                AND tr.est_archive = false
                AND tr.deleted_at IS NULL
            ORDER BY tr.categorie, tr.ordre_affichage, tr.nom
        ");

        // Vue pour les types publics (pour le site web)
        DB::statement("
            CREATE VIEW types_reunions_publics AS
            SELECT
                tr.id,
                tr.code,
                COALESCE(tr.nom_affichage_public, tr.nom) as nom,
                COALESCE(tr.description_publique, tr.description) as description,
                tr.icone,
                tr.couleur,
                tr.categorie,
                tr.duree_standard,
                tr.permet_enfants,
                tr.age_minimum,
                tr.necessite_inscription,
                tr.a_frais_participation,
                tr.frais_standard,
                tr.ordre_affichage
            FROM type_reunions tr
            WHERE tr.actif = true
                AND tr.est_archive = false
                AND tr.afficher_site_web = true
                AND tr.deleted_at IS NULL
            ORDER BY tr.ordre_affichage, tr.nom
        ");

        // Vue pour statistiques d'utilisation (PostgreSQL syntax)
        DB::statement("
            CREATE VIEW statistiques_types_reunions AS
            SELECT
                tr.id,
                tr.nom,
                tr.code,
                tr.categorie,
                tr.nombre_utilisations,
                tr.derniere_utilisation,
                CASE
                    WHEN tr.derniere_utilisation IS NULL THEN NULL
                    ELSE (NOW()::date - tr.derniere_utilisation::date)
                END as jours_depuis_utilisation,
                CASE
                    WHEN tr.derniere_utilisation IS NULL THEN 'Jamais utilisé'
                    WHEN (NOW()::date - tr.derniere_utilisation::date) <= 30 THEN 'Récent'
                    WHEN (NOW()::date - tr.derniere_utilisation::date) <= 90 THEN 'Modéré'
                    ELSE 'Ancien'
                END as statut_usage
            FROM type_reunions tr
            WHERE tr.deleted_at IS NULL
            ORDER BY tr.nombre_utilisations DESC, tr.derniere_utilisation DESC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS statistiques_types_reunions");
        DB::statement("DROP VIEW IF EXISTS types_reunions_publics");
        DB::statement("DROP VIEW IF EXISTS types_reunions_actifs");

        // Suppression de la table
        Schema::dropIfExists('type_reunions');
    }
};
