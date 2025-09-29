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
        // Créer d'abord les fonctions de validation (sans le trigger)
        $this->createValidationFunctions();

        Schema::create('cultes', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();
            $table->uuid('programme_id');

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
            $table->integer('capacite_prevue')->nullable()->unsigned()->comment('Capacité prévue de participants');

            // Officiants du culte (remplace le bloc responsables et intervenants)
            $table->json('officiants')->nullable()->comment('Liste des officiants du culte (JSON: [{user_id, titre, provenance}, ...])');

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
            $table->integer('nombre_participants')->nullable()->unsigned()->comment('Nombre total de participants');
            $table->integer('nombre_adultes')->nullable()->unsigned()->comment('Nombre d\'adultes');
            $table->integer('nombre_enfants')->nullable()->unsigned()->comment('Nombre d\'enfants');
            $table->integer('nombre_jeunes')->nullable()->unsigned()->comment('Nombre de jeunes');
            $table->integer('nombre_nouveaux')->nullable()->unsigned()->comment('Nombre de nouveaux visiteurs');
            $table->integer('nombre_conversions')->default(0)->unsigned()->comment('Nombre de conversions');
            $table->integer('nombre_baptemes')->default(0)->unsigned()->comment('Nombre de baptêmes');

            // Offrandes et finances
            $table->json('detail_offrandes')->nullable()->comment("Détail des offrandes par type (JSON). Tout types d'offrande et leur montant respectif: offrande ordinaire sont obligatoire cette offrande qui contient les offrandes de chaque classe communautaire et les offrandes de culte d'enfant, les spéciales eux se situent dans un cadre imprévu il peut ne pas avoir d'offrande spéciale et il peut en avoir plusieurs");
            $table->decimal('offrande_totale', 15, 2)->nullable()->unsigned()->comment('Total des offrandes');
            $table->decimal('dime_totale', 15, 2)->nullable()->unsigned()->comment('Total des dîmes');
            $table->uuid('responsable_finances_id')->nullable()->comment('Responsable du comptage');

            // Médias et enregistrements
            $table->boolean('est_enregistre')->default(false)->comment('Culte enregistré (audio/vidéo)');
            $table->string('lien_enregistrement_audio', 2048)->nullable()->comment('Lien vers l\'enregistrement audio');
            $table->string('lien_enregistrement_video', 2048)->nullable()->comment('Lien vers l\'enregistrement vidéo');
            $table->string('lien_diffusion_live', 2048)->nullable()->comment('Lien de diffusion en direct');
            $table->json('photos_culte')->nullable()->comment('Photos du culte (JSON array de liens)');
            $table->boolean('diffusion_en_ligne')->default(false)->comment('Diffusé en ligne');

            // État et statut
            $table->enum('statut', [
                'planifie',           // Culte planifié
                'en_preparation',     // En cours de préparation
                'en_cours',          // Culte en cours
                'termine',           // Culte terminé
                'annule',            // Culte annulé
                'reporte'            // Culte reporté
            ])->default('planifie')->comment('Statut du culte');

            $table->boolean('est_public')->default(true)->comment('Culte ouvert au public');
            $table->boolean('necessite_invitation')->default(false)->comment('Culte sur invitation uniquement');

            // Météo et contexte
            $table->string('meteo', 100)->nullable()->comment('Conditions météorologiques');
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
            $table->uuid('cree_par')->nullable()->comment('Membres qui a créé l\'enregistrement');
            $table->uuid('modifie_par')->nullable()->comment('Dernier membres ayant modifié');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key avec protection CASCADE
            $table->foreign('programme_id')->references('id')->on('programmes')->onDelete('restrict');
            $table->foreign('responsable_finances_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances et sécurité
            $table->index(['date_culte', 'type_culte'], 'idx_cultes_date_type');
            $table->index(['statut', 'date_culte'], 'idx_cultes_statut_date');
            $table->index(['type_culte', 'categorie'], 'idx_cultes_type_categorie');
            $table->index('date_culte', 'idx_cultes_date');
            $table->index(['est_public', 'statut'], 'idx_cultes_public_statut');
            $table->index(['diffusion_en_ligne', 'date_culte'], 'idx_cultes_diffusion');
            $table->index(['nombre_participants', 'date_culte'], 'idx_cultes_participants');
            $table->index('titre', 'idx_cultes_titre');
            $table->index('titre_message', 'idx_cultes_message');
            $table->index('programme_id', 'idx_cultes_programme');

            // Index composé pour les statistiques
            $table->index([
                'date_culte',
                'type_culte',
                'nombre_participants',
                'nombre_conversions'
            ], 'idx_cultes_statistiques');

            // Index pour les officiants (pour les recherches dans le JSON)
            $table->index('officiants', 'idx_cultes_officiants');
        });

        // Commentaire sur la table
        DB::statement("COMMENT ON TABLE cultes IS 'Gestion complète des cultes et services religieux de l''église';");

        // Créer le trigger maintenant que la table existe
        $this->createTrigger();

        // Contraintes de sécurité supplémentaires
        $this->addSecurityConstraints();

        // Vues sécurisées
        $this->createSecureViews();
    }

    /**
     * Ajouter les contraintes de sécurité
     */
    private function addSecurityConstraints(): void
    {
        // Contraintes de validation des dates
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_date_culte_valide
            CHECK (date_culte >= '1900-01-01' AND date_culte <= (CURRENT_DATE + INTERVAL '10 years'))
        ");

        // Contraintes de validation des heures
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_heures_coherentes
            CHECK (
                (heure_fin IS NULL OR heure_fin > heure_debut) AND
                (heure_fin_reelle IS NULL OR heure_debut_reelle IS NULL OR heure_fin_reelle > heure_debut_reelle)
            )
        ");

        // Contraintes de validation des notes (1-10)
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_notes_valides
            CHECK (
                (note_globale IS NULL OR (note_globale >= 1 AND note_globale <= 10)) AND
                (note_louange IS NULL OR (note_louange >= 1 AND note_louange <= 10)) AND
                (note_message IS NULL OR (note_message >= 1 AND note_message <= 10)) AND
                (note_organisation IS NULL OR (note_organisation >= 1 AND note_organisation <= 10))
            )
        ");

        // Contraintes de validation des nombres de participants
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_participants_coherents
            CHECK (
                (capacite_prevue IS NULL OR capacite_prevue > 0) AND
                (nombre_participants IS NULL OR nombre_participants >= 0) AND
                (nombre_adultes IS NULL OR nombre_adultes >= 0) AND
                (nombre_enfants IS NULL OR nombre_enfants >= 0) AND
                (nombre_jeunes IS NULL OR nombre_jeunes >= 0) AND
                (nombre_nouveaux IS NULL OR nombre_nouveaux >= 0) AND
                (nombre_conversions >= 0) AND
                (nombre_baptemes >= 0) AND
                (nombre_participants IS NULL OR nombre_adultes IS NULL OR nombre_enfants IS NULL OR nombre_jeunes IS NULL OR
                 nombre_participants >= COALESCE(nombre_adultes, 0) + COALESCE(nombre_enfants, 0) + COALESCE(nombre_jeunes, 0))
            )
        ");

        // Contraintes de validation des montants financiers
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_montants_positifs
            CHECK (
                (offrande_totale IS NULL OR offrande_totale >= 0) AND
                (dime_totale IS NULL OR dime_totale >= 0)
            )
        ");

        // Contrainte de validation des URLs
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_urls_valides
            CHECK (
                (lien_enregistrement_audio IS NULL OR lien_enregistrement_audio ~ '^https?://.*') AND
                (lien_enregistrement_video IS NULL OR lien_enregistrement_video ~ '^https?://.*') AND
                (lien_diffusion_live IS NULL OR lien_diffusion_live ~ '^https?://.*')
            )
        ");

        // Contrainte de cohérence entre statut et données réelles
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_statut_coherent
            CHECK (
                (statut != 'termine' OR (heure_debut_reelle IS NOT NULL AND nombre_participants IS NOT NULL)) AND
                (statut = 'annule' OR statut = 'reporte' OR date_culte >= CURRENT_DATE - INTERVAL '30 days')
            )
        ");

        // Contrainte de cohérence pour les cultes publics/privés
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_invitation_coherente
            CHECK (
                NOT (est_public = true AND necessite_invitation = true)
            )
        ");

        // Contrainte de validation du titre (non vide si pas null)
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_titre_non_vide
            CHECK (LENGTH(TRIM(titre)) > 0)
        ");

        // Contrainte de validation du JSON des offrandes
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_detail_offrandes_valide
            CHECK (
                detail_offrandes IS NULL OR
                validate_detail_offrandes(detail_offrandes::jsonb)
            )
        ");

        // Contrainte de cohérence entre detail_offrandes et offrande_totale
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_coherence_offrande_totale
            CHECK (
                (detail_offrandes IS NULL AND offrande_totale IS NULL) OR
                (detail_offrandes IS NOT NULL AND
                 ABS(COALESCE(offrande_totale, 0) - calculate_offrande_totale(detail_offrandes::jsonb)) < 0.01)
            )
        ");

        // Contrainte de validation du JSON des officiants
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_officiants_valide
            CHECK (
                officiants IS NULL OR
                validate_officiants_json(officiants::jsonb)
            )
        ");
    }

    /**
     * Créer les vues sécurisées
     */
    private function createSecureViews(): void
    {
        // Vue pour les cultes à venir (sécurisée avec officiants)
        DB::statement("
            CREATE OR REPLACE VIEW cultes_a_venir AS
            SELECT
                c.id,
                c.programme_id,
                c.titre,
                c.description,
                c.date_culte,
                c.heure_debut,
                c.heure_fin,
                c.type_culte,
                c.categorie,
                c.lieu,
                c.capacite_prevue,
                c.est_public,
                c.necessite_invitation,
                c.diffusion_en_ligne,
                c.lien_diffusion_live,
                c.statut,
                c.officiants,
                get_officiants_summary(c.officiants::jsonb) AS resume_officiants,
                (c.date_culte - CURRENT_DATE) AS jours_restants,
                c.created_at,
                c.updated_at
            FROM cultes c
            WHERE c.date_culte >= CURRENT_DATE
              AND c.statut IN ('planifie', 'en_preparation')
              AND c.deleted_at IS NULL
            ORDER BY c.date_culte ASC, c.heure_debut ASC
        ");

        // Vue pour les statistiques mensuelles (sécurisée)
        DB::statement("
            CREATE OR REPLACE VIEW statistiques_cultes_mensuelles AS
            SELECT
                EXTRACT(YEAR FROM date_culte) AS annee,
                EXTRACT(MONTH FROM date_culte) AS mois,
                type_culte,
                COUNT(*) AS nombre_cultes,
                ROUND(AVG(COALESCE(nombre_participants, 0)), 0) AS moyenne_participants,
                SUM(COALESCE(nombre_participants, 0)) AS total_participants,
                SUM(COALESCE(nombre_conversions, 0)) AS total_conversions,
                SUM(COALESCE(nombre_baptemes, 0)) AS total_baptemes,
                ROUND(SUM(COALESCE(offrande_totale, 0)), 2) AS total_offrandes,
                ROUND(AVG(note_globale), 1) AS note_moyenne,
                MIN(date_culte) AS premier_culte,
                MAX(date_culte) AS dernier_culte
            FROM cultes
            WHERE statut = 'termine'
              AND deleted_at IS NULL
              AND date_culte >= CURRENT_DATE - INTERVAL '5 years'  -- Limiter aux 5 dernières années
            GROUP BY EXTRACT(YEAR FROM date_culte), EXTRACT(MONTH FROM date_culte), type_culte
            HAVING COUNT(*) > 0  -- S'assurer qu'il y a au moins un culte
            ORDER BY annee DESC, mois DESC, type_culte
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

        // Suppression des triggers et fonctions
        DB::statement("DROP TRIGGER IF EXISTS trg_calculate_offrande_totale ON cultes");
        DB::statement("DROP FUNCTION IF EXISTS trigger_calculate_offrande_totale()");
        DB::statement("DROP FUNCTION IF EXISTS calculate_offrande_totale(JSONB)");
        DB::statement("DROP FUNCTION IF EXISTS validate_detail_offrandes(JSONB)");
        DB::statement("DROP FUNCTION IF EXISTS validate_officiants_json(JSONB)");
        DB::statement("DROP FUNCTION IF EXISTS get_officiants_summary(JSONB)");

        // Suppression de la table (les contraintes sont supprimées automatiquement)
        Schema::dropIfExists('cultes');
    }

    /**
     * Créer les fonctions de validation des offrandes et des officiants
     */
    private function createValidationFunctions(): void
    {
        // Function simple pour valider la structure JSON des offrandes
        DB::unprepared("
            CREATE OR REPLACE FUNCTION validate_detail_offrandes(detail_json JSONB)
            RETURNS BOOLEAN AS \$function\$
            DECLARE
                classe_uuid TEXT;
                classe_exists BOOLEAN;
                special_offering JSONB;
            BEGIN
                -- Vérifier que le JSON a la structure de base attendue
                IF NOT (detail_json ? 'offrandes_ordinnaires' AND detail_json ? 'offrandes_speciales') THEN
                    RETURN FALSE;
                END IF;

                -- Vérifier que offrandes_ordinnaires est un objet
                IF jsonb_typeof(detail_json->'offrandes_ordinnaires') != 'object' THEN
                    RETURN FALSE;
                END IF;

                -- Vérifier que offrandes_speciales est un array
                IF jsonb_typeof(detail_json->'offrandes_speciales') != 'array' THEN
                    RETURN FALSE;
                END IF;

                -- Vérifier que chaque UUID de classe existe dans la table classes
                FOR classe_uuid IN SELECT jsonb_object_keys(detail_json->'offrandes_ordinnaires')
                LOOP
                    -- Vérification simple de l'UUID (longueur et format de base)
                    IF LENGTH(classe_uuid) != 36 OR POSITION('-' IN classe_uuid) = 0 THEN
                        RETURN FALSE;
                    END IF;

                    -- Vérifier que la classe existe
                    SELECT EXISTS(SELECT 1 FROM classes WHERE id::text = classe_uuid AND deleted_at IS NULL) INTO classe_exists;
                    IF NOT classe_exists THEN
                        RETURN FALSE;
                    END IF;

                    -- Vérifier que le montant est un nombre positif
                    IF NOT (jsonb_typeof(detail_json->'offrandes_ordinnaires'->classe_uuid) = 'number' AND
                           (detail_json->'offrandes_ordinnaires'->classe_uuid)::numeric >= 0) THEN
                        RETURN FALSE;
                    END IF;
                END LOOP;

                -- Vérifier la structure des offrandes spéciales
                FOR special_offering IN SELECT jsonb_array_elements(detail_json->'offrandes_speciales')
                LOOP
                    -- Chaque offrande spéciale doit avoir 'titre' et 'montant'
                    IF NOT (special_offering ? 'titre' AND special_offering ? 'montant') THEN
                        RETURN FALSE;
                    END IF;

                    -- Le titre ne doit pas être vide
                    IF jsonb_typeof(special_offering->'titre') != 'string' OR
                       LENGTH(TRIM(special_offering->>'titre')) = 0 THEN
                        RETURN FALSE;
                    END IF;

                    -- Le montant doit être un nombre positif
                    IF NOT (jsonb_typeof(special_offering->'montant') = 'number' AND
                           (special_offering->'montant')::numeric >= 0) THEN
                        RETURN FALSE;
                    END IF;
                END LOOP;

                RETURN TRUE;
            END;
            \$function\$ LANGUAGE plpgsql;
        ");

        // Function pour valider la structure JSON des officiants
        DB::unprepared("
            CREATE OR REPLACE FUNCTION validate_officiants_json(officiants_json JSONB)
            RETURNS BOOLEAN AS \$validate_officiants\$
            DECLARE
                officiant JSONB;
                user_exists BOOLEAN;
            BEGIN
                -- Si le JSON est null, c'est valide
                IF officiants_json IS NULL THEN
                    RETURN TRUE;
                END IF;

                -- Le JSON doit être un array
                IF jsonb_typeof(officiants_json) != 'array' THEN
                    RETURN FALSE;
                END IF;

                -- Valider chaque officiant
                FOR officiant IN SELECT jsonb_array_elements(officiants_json)
                LOOP
                    -- Chaque officiant doit avoir user_id, titre et provenance
                    IF NOT (officiant ? 'user_id' AND officiant ? 'titre' AND officiant ? 'provenance') THEN
                        RETURN FALSE;
                    END IF;

                    -- Vérifier que user_id est un UUID valide
                    IF jsonb_typeof(officiant->'user_id') != 'string' OR
                       LENGTH(officiant->>'user_id') != 36 OR
                       POSITION('-' IN (officiant->>'user_id')) = 0 THEN
                        RETURN FALSE;
                    END IF;

                    -- Vérifier que l'utilisateur existe
                    SELECT EXISTS(
                        SELECT 1 FROM users
                        WHERE id::text = (officiant->>'user_id')
                        AND deleted_at IS NULL
                    ) INTO user_exists;

                    IF NOT user_exists THEN
                        RETURN FALSE;
                    END IF;

                    -- Vérifier que titre n'est pas vide
                    IF jsonb_typeof(officiant->'titre') != 'string' OR
                       LENGTH(TRIM(officiant->>'titre')) = 0 THEN
                        RETURN FALSE;
                    END IF;

                    -- Vérifier que provenance n'est pas vide
                    IF jsonb_typeof(officiant->'provenance') != 'string' OR
                       LENGTH(TRIM(officiant->>'provenance')) = 0 THEN
                        RETURN FALSE;
                    END IF;
                END LOOP;

                RETURN TRUE;
            END;
            \$validate_officiants\$ LANGUAGE plpgsql;
        ");

        // Function pour obtenir un résumé des officiants
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_officiants_summary(officiants_json JSONB)
            RETURNS TEXT AS \$get_summary\$
            DECLARE
                officiant JSONB;
                user_name TEXT;
                summary_parts TEXT[] := '{}';
                final_summary TEXT;
            BEGIN
                -- Si pas d'officiants, retourner message par défaut
                IF officiants_json IS NULL OR jsonb_array_length(officiants_json) = 0 THEN
                    RETURN 'Aucun officiant assigné';
                END IF;

                -- Construire le résumé
                FOR officiant IN SELECT jsonb_array_elements(officiants_json)
                LOOP
                    -- Récupérer le nom de l'utilisateur
                    SELECT COALESCE(prenom || ' ' || nom, email) INTO user_name
                    FROM users
                    WHERE id::text = (officiant->>'user_id')
                    AND deleted_at IS NULL;

                    -- Ajouter à la liste
                    summary_parts := summary_parts || (
                        (officiant->>'titre') || ': ' ||
                        COALESCE(user_name, 'Utilisateur introuvable') ||
                        ' (' || (officiant->>'provenance') || ')'
                    );
                END LOOP;

                -- Joindre tous les éléments
                final_summary := array_to_string(summary_parts, ', ');

                -- Limiter la longueur si nécessaire
                IF LENGTH(final_summary) > 200 THEN
                    final_summary := LEFT(final_summary, 197) || '...';
                END IF;

                RETURN final_summary;
            END;
            \$get_summary\$ LANGUAGE plpgsql;
        ");

        // Function pour calculer le total des offrandes à partir du JSON
        DB::unprepared("
            CREATE OR REPLACE FUNCTION calculate_offrande_totale(detail_json JSONB)
            RETURNS NUMERIC AS \$calculate\$
            DECLARE
                total_ordinaires NUMERIC := 0;
                total_speciales NUMERIC := 0;
                classe_uuid TEXT;
                special_offering JSONB;
            BEGIN
                -- Si le JSON est null ou invalide, retourner 0
                IF detail_json IS NULL OR NOT validate_detail_offrandes(detail_json) THEN
                    RETURN 0;
                END IF;

                -- Calculer le total des offrandes ordinaires
                FOR classe_uuid IN SELECT jsonb_object_keys(detail_json->'offrandes_ordinnaires')
                LOOP
                    total_ordinaires := total_ordinaires + (detail_json->'offrandes_ordinnaires'->classe_uuid)::numeric;
                END LOOP;

                -- Calculer le total des offrandes spéciales
                FOR special_offering IN SELECT jsonb_array_elements(detail_json->'offrandes_speciales')
                LOOP
                    total_speciales := total_speciales + (special_offering->'montant')::numeric;
                END LOOP;

                RETURN total_ordinaires + total_speciales;
            END;
            \$calculate\$ LANGUAGE plpgsql;
        ");

        // Function trigger (sans créer le trigger lui-même)
        DB::unprepared("
            CREATE OR REPLACE FUNCTION trigger_calculate_offrande_totale()
            RETURNS TRIGGER AS \$trigger\$
            BEGIN
                IF NEW.detail_offrandes IS NOT NULL THEN
                    NEW.offrande_totale := calculate_offrande_totale(NEW.detail_offrandes::jsonb);
                END IF;
                RETURN NEW;
            END;
            \$trigger\$ LANGUAGE plpgsql;
        ");
    }

    /**
     * Créer le trigger maintenant que la table existe
     */
    private function createTrigger(): void
    {
        DB::statement("
            CREATE TRIGGER trg_calculate_offrande_totale
            BEFORE INSERT OR UPDATE ON cultes
            FOR EACH ROW
            EXECUTE FUNCTION trigger_calculate_offrande_totale();
        ");
    }
};
