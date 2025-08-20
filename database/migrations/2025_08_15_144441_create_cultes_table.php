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
        Schema::create('cultes', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Informations de base du culte
            $table->string('titre', 200)->comment('Titre/Thème du culte');
            $table->text('description')->nullable()->comment('Description détaillée du culte');
            $table->date('date_culte')->comment('Date du culte');
            $table->time('heure_debut')->comment('Heure de début prévue');
            $table->time('heure_fin')->nullable()->comment('Heure de fin prévue');
            $table->time('heure_debut_reelle')->nullable()->comment('Heure de début réelle');
            $table->time('heure_fin_reelle')->nullable()->comment('Heure de fin réelle');

            // Type et catégorie de culte
            $table->enum('type_culte', [
                'dimanche_matin',      // Culte principal du dimanche
                'dimanche_soir',       // Culte du soir
                'mercredi',           // Culte de milieu de semaine
                'vendredi',           // Culte de jeûne et prière
                'samedi_jeunes',      // Culte des jeunes
                'special',            // Culte spécial
                'conference',         // Conférence
                'seminaire',          // Séminaire
                'retraite',           // Retraite spirituelle
                'mariage',            // Mariage
                'funerailles',        // Funérailles
                'bapteme',            // Baptême
                'communion',          // Sainte Cène
                'noel',              // Culte de Noël
                'paques',            // Culte de Pâques
                'nouvel_an'          // Culte de Nouvel An
            ])->comment('Type de culte');

            $table->enum('categorie', [
                'regulier',           // Culte régulier
                'special',            // Événement spécial
                'ceremonial',         // Cérémonie
                'formation',          // Formation/Enseignement
                'evangelisation'      // Évangélisation
            ])->default('regulier')->comment('Catégorie du culte');

            // Lieu et logistique
            $table->string('lieu', 200)->default('Église principale')->comment('Lieu du culte');
            $table->text('adresse_lieu')->nullable()->comment('Adresse complète si lieu externe');
            $table->integer('capacite_prevue')->nullable()->comment('Capacité prévue de participants');

            // Responsables et intervenants
            $table->uuid('pasteur_principal_id')->nullable()->comment('Pasteur principal du culte');
            $table->uuid('predicateur_id')->nullable()->comment('Prédicateur/Orateur principal');
            $table->uuid('responsable_culte_id')->nullable()->comment('Responsable de l\'organisation');
            $table->uuid('dirigeant_louange_id')->nullable()->comment('Dirigeant de louange');
            $table->json('equipe_culte')->nullable()->comment('Équipe du culte (JSON: rôles et personnes)');

            // Message et prédication
            $table->string('titre_message', 300)->nullable()->comment('Titre du message/prédication');
            $table->text('resume_message')->nullable()->comment('Résumé du message');
            $table->string('passage_biblique', 500)->nullable()->comment('Passage biblique principal');
            $table->json('versets_cles')->nullable()->comment('Versets clés (JSON array)');
            $table->text('plan_message')->nullable()->comment('Plan détaillé du message');

            // Programme et ordre de service
            $table->json('ordre_service')->nullable()->comment('Ordre de service détaillé (JSON)');
            $table->json('cantiques_chantes')->nullable()->comment('Liste des cantiques/chants (JSON)');
            $table->time('duree_louange')->nullable()->comment('Durée de la louange');
            $table->time('duree_message')->nullable()->comment('Durée du message');
            $table->time('duree_priere')->nullable()->comment('Durée des prières');

            // Statistiques et données
            $table->integer('nombre_participants')->nullable()->comment('Nombre total de participants');
            $table->integer('nombre_adultes')->nullable()->comment('Nombre d\'adultes');
            $table->integer('nombre_enfants')->nullable()->comment('Nombre d\'enfants');
            $table->integer('nombre_jeunes')->nullable()->comment('Nombre de jeunes');
            $table->integer('nombre_nouveaux')->nullable()->comment('Nombre de nouveaux visiteurs');
            $table->integer('nombre_conversions')->default(0)->comment('Nombre de conversions');
            $table->integer('nombre_baptemes')->default(0)->comment('Nombre de baptêmes');

            // Offrandes et finances
            $table->decimal('offrande_totale', 15, 2)->nullable()->comment('Total des offrandes');
            $table->decimal('dime_totale', 15, 2)->nullable()->comment('Total des dîmes');
            $table->json('detail_offrandes')->nullable()->comment('Détail des offrandes par type (JSON)');
            $table->string('responsable_finances')->nullable()->comment('Responsable du comptage');

            // Médias et enregistrements
            $table->boolean('est_enregistre')->default(false)->comment('Culte enregistré (audio/vidéo)');
            $table->string('lien_enregistrement_audio')->nullable()->comment('Lien vers l\'enregistrement audio');
            $table->string('lien_enregistrement_video')->nullable()->comment('Lien vers l\'enregistrement vidéo');
            $table->string('lien_diffusion_live')->nullable()->comment('Lien de diffusion en direct');
            $table->json('photos_culte')->nullable()->comment('Photos du culte (JSON array de liens)');
            $table->boolean('diffusion_en_ligne')->default(false)->comment('Diffusé en ligne');

            // État et statut
            $table->enum('statut', [
                'planifie',           // Culte planifié
                'planifie',     // En cours de préparation
                'en_cours',          // Culte en cours
                'termine',           // Culte terminé
                'annule',            // Culte annulé
                'reporte'            // Culte reporté
            ])->default('planifie')->comment('Statut du culte');

            $table->boolean('est_public')->default(true)->comment('Culte ouvert au public');
            $table->boolean('necessite_invitation')->default(false)->comment('Culte sur invitation uniquement');

            // Météo et contexte
            $table->string('meteo')->nullable()->comment('Conditions météorologiques');
            $table->enum('atmosphere', [
                'excellent',
                'tres_bon',
                'bon',
                'moyen',
                'difficile'
            ])->nullable()->comment('Atmosphère spirituelle ressentie');

            // Notes et commentaires
            $table->text('notes_pasteur')->nullable()->comment('Notes du pasteur');
            $table->text('notes_organisateur')->nullable()->comment('Notes de l\'organisateur');
            $table->text('temoignages')->nullable()->comment('Témoignages recueillis');
            $table->text('points_forts')->nullable()->comment('Points forts du culte');
            $table->text('points_amelioration')->nullable()->comment('Points à améliorer');
            $table->text('demandes_priere')->nullable()->comment('Demandes de prière exprimées');

            // Suivi et évaluation
            $table->decimal('note_globale', 3, 1)->nullable()->comment('Note globale du culte (1-10)');
            $table->decimal('note_louange', 3, 1)->nullable()->comment('Note de la louange (1-10)');
            $table->decimal('note_message', 3, 1)->nullable()->comment('Note du message (1-10)');
            $table->decimal('note_organisation', 3, 1)->nullable()->comment('Note de l\'organisation (1-10)');

            // Informations de création et modification
            $table->uuid('cree_par')->nullable()->comment('Utilisateur qui a créé l\'enregistrement');
            $table->uuid('modifie_par')->nullable()->comment('Dernier utilisateur ayant modifié');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key
            $table->foreign('pasteur_principal_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('predicateur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('responsable_culte_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('dirigeant_louange_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances
            $table->index(['date_culte', 'type_culte'], 'idx_cultes_date_type');
            $table->index(['statut', 'date_culte'], 'idx_cultes_statut_date');
            $table->index(['pasteur_principal_id', 'date_culte'], 'idx_cultes_pasteur_date');
            $table->index(['predicateur_id', 'date_culte'], 'idx_cultes_predicateur_date');
            $table->index(['type_culte', 'categorie'], 'idx_cultes_type_categorie');
            $table->index('date_culte', 'idx_cultes_date');
            $table->index(['est_public', 'statut'], 'idx_cultes_public_statut');
            $table->index(['diffusion_en_ligne', 'date_culte'], 'idx_cultes_diffusion');
            $table->index(['nombre_participants', 'date_culte'], 'idx_cultes_participants');

            // Index pour les recherches textuelles
            $table->index('titre', 'idx_cultes_titre');
            $table->index('titre_message', 'idx_cultes_message');

            // Index composé pour les statistiques
            $table->index([
                'date_culte',
                'type_culte',
                'nombre_participants',
                'nombre_conversions'
            ], 'idx_cultes_statistiques');
        });

        // Commentaire sur la table
        // DB::statement("ALTER TABLE cultes COMMENT = 'Gestion complète des cultes et services religieux de l\'église'");
        DB::statement("COMMENT ON TABLE cultes IS 'Gestion complète des cultes et services religieux de léglise';");

        // // Vue pour les cultes à venir
        // DB::statement("
        //     CREATE VIEW cultes_a_venir AS
        //     SELECT
        //         c.*,
        //         CONCAT(pp.prenom, ' ', pp.nom) as nom_pasteur_principal,
        //         CONCAT(pred.prenom, ' ', pred.nom) as nom_predicateur,
        //         CONCAT(resp.prenom, ' ', resp.nom) as nom_responsable,
        //         CONCAT(dl.prenom, ' ', dl.nom) as nom_dirigeant_louange,
        //         DATEDIFF(c.date_culte, CURDATE()) as jours_restants
        //     FROM cultes c
        //     LEFT JOIN users pp ON c.pasteur_principal_id = pp.id
        //     LEFT JOIN users pred ON c.predicateur_id = pred.id
        //     LEFT JOIN users resp ON c.responsable_culte_id = resp.id
        //     LEFT JOIN users dl ON c.dirigeant_louange_id = dl.id
        //     WHERE c.date_culte >= CURDATE()
        //         AND c.statut IN ('planifie', 'planifie')
        //         AND c.deleted_at IS NULL
        //     ORDER BY c.date_culte ASC, c.heure_debut ASC
        // ");

        // // Vue pour les statistiques mensuelles
        // DB::statement("
        //     CREATE VIEW statistiques_cultes_mensuelles AS
        //     SELECT
        //         YEAR(date_culte) as annee,
        //         MONTH(date_culte) as mois,
        //         type_culte,
        //         COUNT(*) as nombre_cultes,
        //         AVG(nombre_participants) as moyenne_participants,
        //         SUM(nombre_participants) as total_participants,
        //         SUM(nombre_conversions) as total_conversions,
        //         SUM(nombre_baptemes) as total_baptemes,
        //         SUM(offrande_totale) as total_offrandes,
        //         AVG(note_globale) as note_moyenne
        //     FROM cultes
        //     WHERE statut = 'termine'
        //         AND deleted_at IS NULL
        //     GROUP BY YEAR(date_culte), MONTH(date_culte), type_culte
        //     ORDER BY annee DESC, mois DESC
        // ");

        // Vue pour les cultes à venir
DB::statement("
    CREATE OR REPLACE VIEW cultes_a_venir AS
    SELECT
        c.*,
        (pp.prenom || ' ' || pp.nom) AS nom_pasteur_principal,
        (pred.prenom || ' ' || pred.nom) AS nom_predicateur,
        (resp.prenom || ' ' || resp.nom) AS nom_responsable,
        (dl.prenom || ' ' || dl.nom) AS nom_dirigeant_louange,
        (c.date_culte - CURRENT_DATE) AS jours_restants
    FROM cultes c
    LEFT JOIN users pp ON c.pasteur_principal_id = pp.id
    LEFT JOIN users pred ON c.predicateur_id = pred.id
    LEFT JOIN users resp ON c.responsable_culte_id = resp.id
    LEFT JOIN users dl ON c.dirigeant_louange_id = dl.id
    WHERE c.date_culte >= CURRENT_DATE
      AND c.statut IN ('planifie', 'planifie')
      AND c.deleted_at IS NULL
    ORDER BY c.date_culte ASC, c.heure_debut ASC
");

// Vue pour les statistiques mensuelles
DB::statement("
    CREATE OR REPLACE VIEW statistiques_cultes_mensuelles AS
    SELECT
        EXTRACT(YEAR FROM date_culte) AS annee,
        EXTRACT(MONTH FROM date_culte) AS mois,
        type_culte,
        COUNT(*) AS nombre_cultes,
        AVG(nombre_participants) AS moyenne_participants,
        SUM(nombre_participants) AS total_participants,
        SUM(nombre_conversions) AS total_conversions,
        SUM(nombre_baptemes) AS total_baptemes,
        SUM(offrande_totale) AS total_offrandes,
        AVG(note_globale) AS note_moyenne
    FROM cultes
    WHERE statut = 'termine'
      AND deleted_at IS NULL
    GROUP BY EXTRACT(YEAR FROM date_culte), EXTRACT(MONTH FROM date_culte), type_culte
    ORDER BY annee DESC, mois DESC
");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS statistiques_cultes_mensuelles");
        DB::statement("DROP VIEW IF EXISTS cultes_a_venir");

        // Suppression de la table
        Schema::dropIfExists('cultes');
    }
};
