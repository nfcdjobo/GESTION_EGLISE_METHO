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
        Schema::create('rapport_reunions', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Relation avec la réunion
            $table->uuid('reunion_id')->comment('Réunion concernée par ce rapport');

            // Informations de base du rapport
            $table->string('titre_rapport', 200)->comment('Titre du rapport');
            $table->enum('type_rapport', [
                'proces_verbal',        // Procès-verbal officiel
                'rapport_activite',     // Rapport d'activité
                'rapport_financier',    // Rapport financier
                'rapport_spirituel',    // Rapport spirituel
                'rapport_ministeriel',  // Rapport ministériel
                'compte_rendu',         // Compte-rendu simple
                'evaluation_detaillee', // Évaluation détaillée
                'rapport_technique',    // Rapport technique
                'synthese_executive',   // Synthèse exécutive
                'rapport_audit'         // Rapport d'audit
            ])->comment('Type de rapport');

            $table->enum('niveau_confidentialite', [
                'public',              // Accessible à tous
                'membres',             // Accessible aux membres
                'leadership',          // Accessible au leadership
                'conseil',             // Accessible au conseil
                'confidentiel'         // Confidentiel
            ])->default('membres')->comment('Niveau de confidentialité');

            // Responsables du rapport
            $table->uuid('redacteur_principal_id')->nullable()->comment('Rédacteur principal du rapport');
            $table->uuid('validateur_id')->nullable()->comment('Personne qui valide le rapport');
            $table->uuid('secretaire_id')->nullable()->comment('Secrétaire de séance');
            $table->json('contributeurs')->nullable()->comment('Liste des contributeurs (JSON)');

            // Statut et workflow
            $table->enum('statut', [
                'brouillon',           // En cours de rédaction
                'en_revision',         // En cours de révision
                'en_attente_validation', // En attente de validation
                'valide',             // Validé
                'publie',             // Publié
                'archive',            // Archivé
                'rejete'              // Rejeté
            ])->default('brouillon')->comment('Statut du rapport');

            $table->date('date_limite_redaction')->nullable()->comment('Date limite de rédaction');
            $table->timestamp('valide_le')->nullable()->comment('Date de validation');
            $table->timestamp('publie_le')->nullable()->comment('Date de publication');

            // Contenu principal du rapport
            $table->text('resume_executif')->nullable()->comment('Résumé exécutif');
            $table->text('introduction')->nullable()->comment('Introduction du rapport');
            $table->text('objectifs_reunion')->nullable()->comment('Objectifs de la réunion');
            $table->text('deroulement_general')->nullable()->comment('Déroulement général');
            $table->text('conclusion')->nullable()->comment('Conclusion du rapport');

            // Sections détaillées
            $table->json('ordre_jour_traite')->nullable()->comment('Points traités de l\'ordre du jour (JSON)');
            $table->text('decisions_prises')->nullable()->comment('Décisions importantes prises');
            $table->text('actions_decidees')->nullable()->comment('Actions décidées');
            $table->json('responsabilites_attribuees')->nullable()->comment('Responsabilités attribuées (JSON)');
            $table->text('points_discussion')->nullable()->comment('Points de discussion majeurs');
            $table->text('defis_rencontres')->nullable()->comment('Défis rencontrés');
            $table->text('solutions_proposees')->nullable()->comment('Solutions proposées');

            // Présences et participation
            $table->json('liste_presences')->nullable()->comment('Liste détaillée des présences (JSON)');
            $table->json('excuses_recues')->nullable()->comment('Excuses reçues (JSON)');
            $table->json('absents_non_excuses')->nullable()->comment('Absents non excusés (JSON)');
            $table->text('analyse_participation')->nullable()->comment('Analyse de la participation');
            $table->decimal('taux_presence', 5, 2)->nullable()->comment('Taux de présence en %');

            // Aspects spirituels
            $table->text('temps_priere')->nullable()->comment('Rapport du temps de prière');
            $table->text('temps_louange')->nullable()->comment('Rapport du temps de louange');
            $table->text('message_partage')->nullable()->comment('Résumé du message partagé');
            $table->text('temoignages')->nullable()->comment('Témoignages partagés');
            $table->text('demandes_priere')->nullable()->comment('Demandes de prière exprimées');
            $table->integer('nombre_conversions')->default(0)->comment('Nombre de conversions');
            $table->integer('nombre_rededications')->default(0)->comment('Nombre de re-dédicaces');
            $table->text('mouvements_esprit')->nullable()->comment('Mouvements de l\'Esprit observés');

            // Aspects financiers
            $table->decimal('offrandes_recoltees', 15, 2)->nullable()->comment('Offrandes récoltées');
            $table->json('detail_finances')->nullable()->comment('Détail des aspects financiers (JSON)');
            $table->text('rapport_tresorier')->nullable()->comment('Rapport du trésorier');
            $table->decimal('depenses_engagees', 15, 2)->nullable()->comment('Dépenses engagées');

            // Évaluation et performance
            $table->decimal('note_organisation', 3, 1)->nullable()->comment('Note organisation (1-10)');
            $table->decimal('note_contenu', 3, 1)->nullable()->comment('Note contenu (1-10)');
            $table->decimal('note_participation', 3, 1)->nullable()->comment('Note participation (1-10)');
            $table->decimal('note_spiritualite', 3, 1)->nullable()->comment('Note spiritualité (1-10)');
            $table->decimal('satisfaction_generale', 5, 2)->nullable()->comment('Satisfaction générale en %');

            // Feedback et amélioration
            $table->text('retours_positifs')->nullable()->comment('Retours positifs');
            $table->text('critiques_constructives')->nullable()->comment('Critiques constructives');
            $table->text('suggestions_amelioration')->nullable()->comment('Suggestions d\'amélioration');
            $table->text('lecons_apprises')->nullable()->comment('Leçons apprises');
            $table->text('bonnes_pratiques')->nullable()->comment('Bonnes pratiques identifiées');

            // Suivi et actions futures
            $table->json('actions_suivre')->nullable()->comment('Actions à suivre (JSON avec responsables et échéances)');
            $table->text('recommandations')->nullable()->comment('Recommandations pour l\'avenir');
            $table->text('preparation_prochaine')->nullable()->comment('Préparation de la prochaine réunion');
            $table->date('prochaine_echeance')->nullable()->comment('Prochaine échéance importante');
            $table->json('suivi_precedent')->nullable()->comment('Suivi des actions précédentes (JSON)');

            // Médias et documentation
            $table->json('documents_annexes')->nullable()->comment('Documents annexes (JSON)');
            $table->json('photos_rapport')->nullable()->comment('Photos pour le rapport (JSON)');
            $table->string('lien_enregistrement_audio')->nullable()->comment('Lien enregistrement audio');
            $table->string('lien_enregistrement_video')->nullable()->comment('Lien enregistrement vidéo');
            $table->json('presentations_utilisees')->nullable()->comment('Présentations utilisées (JSON)');

            // Aspects techniques
            $table->text('problemes_techniques')->nullable()->comment('Problèmes techniques rencontrés');
            $table->text('solutions_techniques')->nullable()->comment('Solutions techniques appliquées');
            $table->text('materiel_utilise')->nullable()->comment('Matériel utilisé');
            $table->text('recommandations_techniques')->nullable()->comment('Recommandations techniques');

            // Conformité et audit
            $table->boolean('conforme_procedures')->default(true)->comment('Conforme aux procédures');
            $table->text('ecarts_procedures')->nullable()->comment('Écarts aux procédures observés');
            $table->text('justification_ecarts')->nullable()->comment('Justification des écarts');
            $table->boolean('audit_requis')->default(false)->comment('Audit requis');
            $table->text('observations_audit')->nullable()->comment('Observations d\'audit');

            // Versions et révisions (sans contrainte FK pour l'instant)
            $table->integer('numero_version')->default(1)->comment('Numéro de version du rapport');
            $table->uuid('version_precedente_id')->nullable()->comment('Version précédente du rapport');
            $table->text('modifications_version')->nullable()->comment('Modifications apportées dans cette version');
            $table->timestamp('derniere_modification')->nullable()->comment('Dernière modification majeure');

            // Distribution et circulation
            $table->json('destinataires')->nullable()->comment('Liste des destinataires (JSON)');
            $table->boolean('envoye_conseil')->default(false)->comment('Envoyé au conseil');
            $table->boolean('envoye_leadership')->default(false)->comment('Envoyé au leadership');
            $table->timestamp('date_diffusion')->nullable()->comment('Date de diffusion');
            $table->text('canal_diffusion')->nullable()->comment('Canaux de diffusion utilisés');

            // Archivage et conservation
            $table->string('reference_archivage', 100)->nullable()->comment('Référence d\'archivage');
            $table->date('date_archivage')->nullable()->comment('Date d\'archivage');
            $table->enum('duree_conservation', [
                '1_an',
                '3_ans',
                '5_ans',
                '10_ans',
                'permanent'
            ])->default('5_ans')->comment('Durée de conservation');

            // Audit et traçabilité
            $table->uuid('cree_par')->nullable()->comment('Utilisateur qui a créé le rapport');
            $table->uuid('modifie_par')->nullable()->comment('Dernier utilisateur ayant modifié');
            $table->text('commentaires_redacteur')->nullable()->comment('Commentaires du rédacteur');
            $table->text('commentaires_validateur')->nullable()->comment('Commentaires du validateur');
            $table->text('historique_modifications')->nullable()->comment('Historique des modifications');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key (sauf les auto-référentielles)
            $table->foreign('reunion_id')->references('id')->on('reunions')->onDelete('cascade');
            $table->foreign('redacteur_principal_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validateur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('secretaire_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances
            $table->index(['reunion_id', 'type_rapport'], 'idx_rapport_reunion_type');
            $table->index(['statut', 'date_limite_redaction'], 'idx_rapport_statut_limite');
            $table->index(['redacteur_principal_id', 'statut'], 'idx_rapport_redacteur_statut');
            $table->index(['niveau_confidentialite', 'statut'], 'idx_rapport_confidentialite');
            $table->index(['type_rapport', 'statut'], 'idx_rapport_type_statut');
            $table->index(['valide_le', 'publie_le'], 'idx_rapport_validation_publication');
            $table->index('reference_archivage', 'idx_rapport_archivage');
            $table->index(['numero_version', 'reunion_id'], 'idx_rapport_version');
            $table->index(['audit_requis', 'statut'], 'idx_rapport_audit');
            $table->index(['duree_conservation', 'date_archivage'], 'idx_rapport_conservation');

            // Index pour les recherches
            $table->index('titre_rapport', 'idx_rapport_titre');
            $table->index(['type_rapport', 'valide_le'], 'idx_rapport_type_date');

            // Contrainte d'unicité pour éviter les doublons de rapport par réunion et type
            $table->unique(['reunion_id', 'type_rapport', 'numero_version'], 'unique_rapport_reunion_type_version');
        });

        // Ajouter les contraintes auto-référentielles après la création de la table
        Schema::table('rapport_reunions', function (Blueprint $table) {
            $table->foreign('version_precedente_id')->references('id')->on('rapport_reunions')->onDelete('set null');
        });

        // Commentaire sur la table (PostgreSQL syntax)
        DB::statement("COMMENT ON TABLE rapport_reunions IS 'Rapports formels et détaillés des réunions avec workflow de validation'");

        // Vue pour les rapports en attente (PostgreSQL syntax)
        DB::statement("
            CREATE VIEW rapports_en_attente AS
            SELECT
                rr.*,
                r.titre as titre_reunion,
                r.date_reunion,
                tr.nom as nom_type_reunion,
                CONCAT(red.prenom, ' ', red.nom) as nom_redacteur,
                CONCAT(val.prenom, ' ', val.nom) as nom_validateur,
                (rr.date_limite_redaction - CURRENT_DATE) as jours_restants
            FROM rapport_reunions rr
            INNER JOIN reunions r ON rr.reunion_id = r.id
            INNER JOIN type_reunions tr ON r.type_reunion_id = tr.id
            LEFT JOIN users red ON rr.redacteur_principal_id = red.id
            LEFT JOIN users val ON rr.validateur_id = val.id
            WHERE rr.statut IN ('brouillon', 'en_revision', 'en_attente_validation')
                AND rr.deleted_at IS NULL
            ORDER BY rr.date_limite_redaction ASC
        ");

        // Vue pour les rapports publiés par type (PostgreSQL syntax)
        DB::statement("
            CREATE VIEW rapports_publies_par_type AS
            SELECT
                rr.type_rapport,
                COUNT(*) as nombre_rapports,
                AVG(rr.satisfaction_generale) as satisfaction_moyenne,
                AVG(rr.taux_presence) as presence_moyenne,
                MAX(rr.publie_le) as dernier_rapport,
                AVG(EXTRACT(EPOCH FROM (rr.valide_le - rr.created_at))/86400) as delai_moyen_validation_jours
            FROM rapport_reunions rr
            WHERE rr.statut = 'publie'
                AND rr.deleted_at IS NULL
            GROUP BY rr.type_rapport
            ORDER BY nombre_rapports DESC
        ");

        // Vue pour le tableau de bord des rapporteurs (PostgreSQL syntax)
        DB::statement("
            CREATE VIEW tableau_bord_rapporteurs AS
            SELECT
                rr.redacteur_principal_id,
                CONCAT(u.prenom, ' ', u.nom) as nom_redacteur,
                COUNT(*) as nombre_rapports_total,
                COUNT(CASE WHEN rr.statut = 'brouillon' THEN 1 END) as rapports_brouillon,
                COUNT(CASE WHEN rr.statut = 'en_revision' THEN 1 END) as rapports_revision,
                COUNT(CASE WHEN rr.statut = 'valide' THEN 1 END) as rapports_valides,
                COUNT(CASE WHEN rr.statut = 'publie' THEN 1 END) as rapports_publies,
                AVG(rr.satisfaction_generale) as satisfaction_moyenne,
                MIN(rr.date_limite_redaction) as prochaine_echeance
            FROM rapport_reunions rr
            LEFT JOIN users u ON rr.redacteur_principal_id = u.id
            WHERE rr.deleted_at IS NULL
                AND rr.redacteur_principal_id IS NOT NULL
            GROUP BY rr.redacteur_principal_id, u.prenom, u.nom
            ORDER BY prochaine_echeance ASC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS tableau_bord_rapporteurs");
        DB::statement("DROP VIEW IF EXISTS rapports_publies_par_type");
        DB::statement("DROP VIEW IF EXISTS rapports_en_attente");

        // Suppression de la table
        Schema::dropIfExists('rapport_reunions');
    }
};
