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
        DB::unprepared("
        CREATE OR REPLACE FUNCTION validate_multimedia_json_fields(
            tags json,
            metadonnees_exif json,
            parametres_capture json,
            groupes_autorises json,
            emplacements_backup json,
            versions_disponibles json,
            formats_convertis json,
            historique_modifications json
        ) RETURNS boolean AS $$
        BEGIN
            -- Vérification simple
            IF tags IS NOT NULL AND json_typeof(tags) <> 'array' THEN
                RETURN FALSE;
            END IF;
            RETURN TRUE;
        END;
        $$ LANGUAGE plpgsql;
    ");

        // Créer d'abord les fonctions de validation
        $this->createValidationFunctions();

        Schema::create('multimedia', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Relations avec les événements
            $table->uuid('culte_id')->nullable()->comment('Culte associé');
            $table->uuid('event_id')->nullable()->comment('Événement associé');
            $table->uuid('intervention_id')->nullable()->comment('Intervention associée');
            $table->uuid('reunion_id')->nullable()->comment('Réunion associée');

            // Informations de base du média
            $table->string('titre', 200)->comment('Titre du média');
            $table->text('description')->nullable()->comment('Description du média');
            $table->text('legende')->nullable()->comment('Légende/caption du média');
            $table->json('tags')->nullable()->comment('Tags/mots-clés (JSON array)');

            // Type et catégorie de média
            $table->enum('type_media', [
                'image',              // Image
                'video',              // Vidéo
                'audio',              // Audio/enregistrement
                'document',           // Document PDF, Word, etc.
                'presentation',       // Présentation PowerPoint, etc.
                'archive',            // Archive ZIP, RAR
                'livestream',         // Diffusion en direct
                'podcast'             // Podcast
            ])->comment('Type de média');

            $table->enum('categorie', [
                'photos_culte',       // Photos de culte
                'photos_evenement',   // Photos d'événement
                'enregistrement_audio', // Enregistrement audio
                'enregistrement_video', // Enregistrement vidéo
                'temoignage',         // Témoignage
                'predication',        // Prédication
                'louange',            // Louange/musique
                'formation',          // Formation/enseignement
                'ceremonie',          // Cérémonie (baptême, mariage, etc.)
                'activite_jeunes',    // Activités des jeunes
                'activite_enfants',   // Activités des enfants
                'evenement_special',  // Événement spécial
                'archive_historique', // Archive historique
                'documentaire',       // Documentaire
                'interview',          // Interview
                'reportage',          // Reportage
                'autre'               // Autre
            ])->comment('Catégorie du média');

            // Informations techniques du fichier
            $table->string('nom_fichier_original', 255)->comment('Nom original du fichier');
            $table->string('nom_fichier_stockage', 255)->comment('Nom du fichier en stockage');
            $table->string('chemin_fichier', 500)->comment('Chemin complet vers le fichier');
            $table->string('url_publique', 500)->nullable()->comment('URL publique d\'accès');
            $table->string('miniature', 500)->nullable()->comment('Chemin vers la miniature');

            // Métadonnées techniques
            $table->string('type_mime', 100)->comment('Type MIME du fichier');
            $table->string('extension', 10)->comment('Extension du fichier');
            $table->unsignedBigInteger('taille_fichier')->comment('Taille en octets');
            $table->string('hash_fichier', 64)->nullable()->comment('Hash SHA-256 du fichier');
            $table->json('metadonnees_exif')->nullable()->comment('Métadonnées EXIF (pour images)');

            // Métadonnées spécifiques par type
            // Pour images
            $table->integer('largeur')->nullable()->comment('Largeur en pixels');
            $table->integer('hauteur')->nullable()->comment('Hauteur en pixels');
            $table->string('orientation', 20)->nullable()->comment('Orientation de l\'image');

            // Pour vidéos et audios
            $table->integer('duree_secondes')->nullable()->comment('Durée en secondes');
            $table->integer('bitrate')->nullable()->comment('Bitrate en kbps');
            $table->string('codec', 50)->nullable()->comment('Codec utilisé');
            $table->string('resolution', 20)->nullable()->comment('Résolution (ex: 1920x1080)');
            $table->integer('fps')->nullable()->comment('Images par seconde (vidéo)');

            // Informations de capture/création
            $table->timestamp('date_prise')->nullable()->comment('Date de prise/création du média');
            $table->string('lieu_prise', 200)->nullable()->comment('Lieu de prise');
            $table->string('photographe', 100)->nullable()->comment('Photographe/créateur');
            $table->string('appareil', 100)->nullable()->comment('Appareil utilisé');
            $table->json('parametres_capture')->nullable()->comment('Paramètres de capture (JSON)');

            // Gestion des droits et permissions
            $table->enum('licence', [
                'libre_usage',        // Libre usage interne
                'usage_restreint',    // Usage restreint
                'droits_auteur',      // Droits d'auteur
                'creative_commons',   // Creative Commons
                'usage_commercial',   // Usage commercial autorisé
                'prive'              // Privé
            ])->default('libre_usage')->comment('Type de licence');

            $table->boolean('usage_public')->default(true)->comment('Autorisé pour usage public');
            $table->boolean('usage_site_web')->default(true)->comment('Autorisé sur le site web');
            $table->boolean('usage_reseaux_sociaux')->default(false)->comment('Autorisé sur réseaux sociaux');
            $table->boolean('usage_commercial')->default(false)->comment('Usage commercial autorisé');
            $table->text('restrictions_usage')->nullable()->comment('Restrictions d\'usage spécifiques');

            // Processus de validation et modération
            $table->enum('statut_moderation', [
                'en_attente',         // En attente de modération
                'approuve',           // Approuvé
                'rejete',             // Rejeté
                'revision_requise',   // Révision requise
                'archive'             // Archivé
            ])->default('en_attente')->comment('Statut de modération');

            $table->uuid('modere_par')->nullable()->comment('Modérateur');
            $table->timestamp('modere_le')->nullable()->comment('Date de modération');
            $table->text('commentaire_moderation')->nullable()->comment('Commentaire du modérateur');

            // Visibilité et publication
            $table->boolean('est_visible')->default(true)->comment('Média visible');
            $table->boolean('est_featured')->default(false)->comment('Média mis en avant');
            $table->boolean('est_archive')->default(false)->comment('Média archivé');
            $table->date('date_publication')->nullable()->comment('Date de publication');
            $table->date('date_expiration')->nullable()->comment('Date d\'expiration');

            // Audience et accès
            $table->enum('niveau_acces', [
                'public',             // Accessible à tous
                'membres',            // Membres uniquement
                'leadership',         // Leadership uniquement
                'administrateurs',    // Administrateurs uniquement
                'prive'              // Privé (créateur uniquement)
            ])->default('public')->comment('Niveau d\'accès requis');

            $table->boolean('necessite_connexion')->default(false)->comment('Connexion requise');
            $table->json('groupes_autorises')->nullable()->comment('Groupes autorisés (JSON array)');

            // Statistiques et engagement
            $table->unsignedInteger('nombre_vues')->default(0)->comment('Nombre de vues');
            $table->unsignedInteger('nombre_telechargements')->default(0)->comment('Nombre de téléchargements');
            $table->unsignedInteger('nombre_partages')->default(0)->comment('Nombre de partages');
            $table->unsignedInteger('nombre_likes')->default(0)->comment('Nombre de likes');
            $table->unsignedInteger('nombre_commentaires')->default(0)->comment('Nombre de commentaires');
            $table->timestamp('derniere_vue')->nullable()->comment('Dernière vue');

            // Stockage et backup
            $table->string('service_stockage', 50)->default('local')->comment('Service de stockage utilisé');
            $table->json('emplacements_backup')->nullable()->comment('Emplacements de backup (JSON)');
            $table->boolean('backup_automatique')->default(true)->comment('Backup automatique activé');
            $table->timestamp('derniere_sauvegarde')->nullable()->comment('Dernière sauvegarde');

            // SEO et référencement
            $table->string('alt_text', 255)->nullable()->comment('Texte alternatif pour SEO');
            $table->string('titre_seo', 200)->nullable()->comment('Titre SEO');
            $table->text('description_seo')->nullable()->comment('Description SEO');
            $table->string('slug', 255)->nullable()->unique()->comment('Slug pour URL');

            // Workflow et traitement
            $table->enum('statut_traitement', [
                'original',           // Fichier original
                'en_traitement',      // En cours de traitement
                'optimise',           // Optimisé/compressé
                'erreur_traitement'   // Erreur de traitement
            ])->default('original')->comment('Statut de traitement');

            $table->json('versions_disponibles')->nullable()->comment('Versions disponibles (JSON)');
            $table->boolean('generer_miniatures')->default(true)->comment('Générer miniatures automatiquement');
            $table->json('formats_convertis')->nullable()->comment('Formats convertis disponibles');

            // Informations de qualité
            $table->enum('qualite', [
                'basse',              // Basse qualité
                'standard',           // Qualité standard
                'haute',              // Haute qualité
                'premium',            // Qualité premium
                'raw'                // Format RAW/original
            ])->default('standard')->comment('Niveau de qualité');

            $table->decimal('note_qualite', 3, 1)->nullable()->comment('Note de qualité (1-10)');
            $table->boolean('contenu_sensible')->default(false)->comment('Contenu sensible');
            $table->text('avertissement')->nullable()->comment('Avertissement si contenu sensible');

            // Traçabilité et audit
            $table->uuid('telecharge_par')->comment('Utilisateur qui a téléchargé');
            $table->uuid('cree_par')->nullable()->comment('Utilisateur créateur');
            $table->uuid('modifie_par')->nullable()->comment('Dernier utilisateur modificateur');
            $table->json('historique_modifications')->nullable()->comment('Historique des modifications');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key
            $table->foreign('culte_id')->references('id')->on('cultes')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('intervention_id')->references('id')->on('interventions')->onDelete('cascade');
            $table->foreign('reunion_id')->references('id')->on('reunions')->onDelete('cascade');
            $table->foreign('telecharge_par')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modere_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances
            $table->index(['type_media', 'categorie'], 'idx_multimedia_type_cat');
            $table->index(['est_visible', 'statut_moderation'], 'idx_multimedia_visible');
            $table->index(['culte_id', 'type_media'], 'idx_multimedia_culte');
            $table->index(['event_id', 'type_media'], 'idx_multimedia_event');
            $table->index(['date_prise', 'categorie'], 'idx_multimedia_date_cat');
            $table->index(['telecharge_par', 'created_at'], 'idx_multimedia_user');
            $table->index(['niveau_acces', 'est_visible'], 'idx_multimedia_acces');
            $table->index(['statut_moderation', 'created_at'], 'idx_multimedia_moderation');
            $table->index(['est_featured', 'date_publication'], 'idx_multimedia_featured');
            $table->index('hash_fichier', 'idx_multimedia_hash');
            $table->index('slug', 'idx_multimedia_slug');

            // Index pour les statistiques
            $table->index(['nombre_vues', 'created_at'], 'idx_multimedia_stats');
            $table->index(['taille_fichier', 'type_media'], 'idx_multimedia_taille');

            // Index composé pour les requêtes complexes
            $table->index([
                'est_visible',
                'statut_moderation',
                'niveau_acces',
                'date_publication'
            ], 'idx_multimedia_publication');
        });

        // Commentaire sur la table
        DB::statement("COMMENT ON TABLE multimedia IS 'Galerie multimédia complète de la communauté religieuse avec gestion des droits et modération';");

        // Créer les contraintes de sécurité
        $this->addSecurityConstraints();

        // Créer les vues sécurisées
        $this->createSecureViews();

        // Créer les triggers
        $this->createTriggers();
    }

    /**
     * Ajouter les contraintes de sécurité
     */
    private function addSecurityConstraints(): void
    {
        // Au moins une relation doit être définie
        DB::statement("
            ALTER TABLE multimedia ADD CONSTRAINT chk_relation_requise
            CHECK (culte_id IS NOT NULL OR event_id IS NOT NULL OR intervention_id IS NOT NULL OR reunion_id IS NOT NULL)
        ");

        // Validation des tailles de fichier (max 2GB)
        DB::statement("
            ALTER TABLE multimedia ADD CONSTRAINT chk_taille_fichier_valide
            CHECK (taille_fichier > 0 AND taille_fichier <= 2147483648)
        ");

        // Validation des dimensions d'image
        DB::statement("
            ALTER TABLE multimedia ADD CONSTRAINT chk_dimensions_coherentes
            CHECK (
                (type_media != 'image' OR (largeur > 0 AND hauteur > 0)) AND
                (largeur IS NULL OR largeur <= 50000) AND
                (hauteur IS NULL OR hauteur <= 50000)
            )
        ");

        // Validation de la durée pour vidéos/audios
        DB::statement("
            ALTER TABLE multimedia ADD CONSTRAINT chk_duree_coherente
            CHECK (
                (type_media NOT IN ('video', 'audio', 'livestream', 'podcast') OR duree_secondes > 0) AND
                (duree_secondes IS NULL OR duree_secondes <= 86400)
            )
        ");

        // Validation des notes
        DB::statement("
            ALTER TABLE multimedia ADD CONSTRAINT chk_note_qualite_valide
            CHECK (note_qualite IS NULL OR (note_qualite >= 1 AND note_qualite <= 10))
        ");

        // Validation des dates
        DB::statement("
            ALTER TABLE multimedia ADD CONSTRAINT chk_dates_coherentes
            CHECK (
                (date_publication IS NULL OR date_publication >= '2000-01-01') AND
                (date_expiration IS NULL OR date_publication IS NULL OR date_expiration > date_publication) AND
                (date_prise IS NULL OR date_prise >= '1900-01-01' AND date_prise <= CURRENT_TIMESTAMP)
            )
        ");

        // Validation des statistiques
        DB::statement("
            ALTER TABLE multimedia ADD CONSTRAINT chk_statistiques_positives
            CHECK (
                nombre_vues >= 0 AND
                nombre_telechargements >= 0 AND
                nombre_partages >= 0 AND
                nombre_likes >= 0 AND
                nombre_commentaires >= 0
            )
        ");



        // Validation du titre non vide
        DB::statement("
            ALTER TABLE multimedia ADD CONSTRAINT chk_titre_non_vide
            CHECK (LENGTH(TRIM(titre)) > 0)
        ");

        // Cohérence entre statut de modération et visibilité
        DB::statement("
            ALTER TABLE multimedia ADD CONSTRAINT chk_moderation_coherente
            CHECK (
                (statut_moderation = 'approuve' OR est_visible = false) AND
                (statut_moderation != 'rejete' OR est_visible = false)
            )
        ");

        // Validation des métadonnées JSON
        DB::statement("
            ALTER TABLE multimedia ADD CONSTRAINT chk_metadonnees_valides
            CHECK (
                validate_multimedia_json_fields(
                    tags, metadonnees_exif, parametres_capture,
                    groupes_autorises, emplacements_backup,
                    versions_disponibles, formats_convertis, historique_modifications
                )
            )
        ");
    }

    /**
     * Créer les vues sécurisées
     */
    private function createSecureViews(): void
    {
        // Vue pour la galerie publique
        DB::statement("
            CREATE OR REPLACE VIEW galerie_publique AS
            SELECT
                m.id,
                m.titre,
                m.description,
                m.legende,
                m.tags,
                m.type_media,
                m.categorie,
                m.url_publique,
                m.miniature,
                m.largeur,
                m.hauteur,
                m.duree_secondes,
                m.alt_text,
                m.date_prise,
                m.lieu_prise,
                m.photographe,
                m.nombre_vues,
                m.nombre_likes,
                m.est_featured,
                m.date_publication,
                m.created_at,
                COALESCE(c.titre, e.titre, 'Sans titre') AS evenement_titre,
                CASE
                    WHEN m.culte_id IS NOT NULL THEN 'culte'
                    WHEN m.event_id IS NOT NULL THEN 'evenement'
                    WHEN m.intervention_id IS NOT NULL THEN 'intervention'
                    WHEN m.reunion_id IS NOT NULL THEN 'reunion'
                END AS type_evenement
            FROM multimedia m
            LEFT JOIN cultes c ON m.culte_id = c.id AND c.deleted_at IS NULL
            LEFT JOIN events e ON m.event_id = e.id AND e.deleted_at IS NULL
            WHERE m.est_visible = true
              AND m.statut_moderation = 'approuve'
              AND m.niveau_acces = 'public'
              AND m.usage_public = true
              AND (m.date_publication IS NULL OR m.date_publication <= CURRENT_DATE)
              AND (m.date_expiration IS NULL OR m.date_expiration > CURRENT_DATE)
              AND m.deleted_at IS NULL
            ORDER BY m.est_featured DESC, m.date_publication DESC, m.created_at DESC
        ");

        // Vue pour les médias récents
        DB::statement("
            CREATE OR REPLACE VIEW medias_recents AS
            SELECT
                m.*,
                CONCAT(u.prenom, ' ', u.nom) AS nom_createur,
                COALESCE(c.titre, e.titre) AS titre_evenement,
                ROW_NUMBER() OVER (PARTITION BY m.categorie ORDER BY m.created_at DESC) as rang_categorie
            FROM multimedia m
            LEFT JOIN users u ON m.telecharge_par = u.id AND u.deleted_at IS NULL
            LEFT JOIN cultes c ON m.culte_id = c.id AND c.deleted_at IS NULL
            LEFT JOIN events e ON m.event_id = e.id AND e.deleted_at IS NULL
            WHERE m.est_visible = true
              AND m.statut_moderation = 'approuve'
              AND m.created_at >= (CURRENT_DATE - INTERVAL '30 days')
              AND m.deleted_at IS NULL
            ORDER BY m.created_at DESC
        ");

        // Vue pour les statistiques de la galerie
        DB::statement("
            CREATE OR REPLACE VIEW statistiques_galerie AS
            SELECT
                m.type_media,
                m.categorie,
                COUNT(*) AS nombre_total,
                SUM(m.taille_fichier) AS taille_totale,
                AVG(m.nombre_vues) AS vues_moyenne,
                SUM(m.nombre_vues) AS vues_totales,
                AVG(m.note_qualite) AS qualite_moyenne,
                COUNT(*) FILTER (WHERE m.est_featured = true) AS nombre_featured,
                COUNT(*) FILTER (WHERE m.statut_moderation = 'en_attente') AS en_attente_moderation,
                MIN(m.created_at) AS plus_ancien,
                MAX(m.created_at) AS plus_recent
            FROM multimedia m
            WHERE m.deleted_at IS NULL
            GROUP BY m.type_media, m.categorie
            ORDER BY nombre_total DESC
        ");
    }

    /**
     * Créer les fonctions de validation
     */
    private function createValidationFunctions(): void
    {
        // Fonction pour valider les champs JSON
        DB::unprepared("
            CREATE OR REPLACE FUNCTION validate_multimedia_json_fields(
                p_tags JSONB DEFAULT NULL,
                p_exif JSONB DEFAULT NULL,
                p_params JSONB DEFAULT NULL,
                p_groupes JSONB DEFAULT NULL,
                p_backup JSONB DEFAULT NULL,
                p_versions JSONB DEFAULT NULL,
                p_formats JSONB DEFAULT NULL,
                p_historique JSONB DEFAULT NULL
            )
            RETURNS BOOLEAN AS \$function\$
            BEGIN
                -- Validation des tags (doit être un array de strings)
                IF p_tags IS NOT NULL THEN
                    IF jsonb_typeof(p_tags) != 'array' THEN
                        RETURN FALSE;
                    END IF;
                END IF;

                -- Validation des groupes autorisés (array d'UUIDs)
                IF p_groupes IS NOT NULL THEN
                    IF jsonb_typeof(p_groupes) != 'array' THEN
                        RETURN FALSE;
                    END IF;
                END IF;

                -- Validation des emplacements de backup (array)
                IF p_backup IS NOT NULL THEN
                    IF jsonb_typeof(p_backup) != 'array' THEN
                        RETURN FALSE;
                    END IF;
                END IF;

                -- Les autres champs JSON peuvent être des objets ou arrays
                -- Validation de base réussie
                RETURN TRUE;
            END;
            \$function\$ LANGUAGE plpgsql;
        ");

        // Fonction pour mettre à jour les statistiques
        DB::unprepared("
            CREATE OR REPLACE FUNCTION increment_media_stats(
                p_media_id UUID,
                p_stat_type VARCHAR(20)
            )
            RETURNS VOID AS \$function\$
            BEGIN
                CASE p_stat_type
                    WHEN 'vue' THEN
                        UPDATE multimedia
                        SET nombre_vues = nombre_vues + 1,
                            derniere_vue = CURRENT_TIMESTAMP
                        WHERE id = p_media_id;
                    WHEN 'telechargement' THEN
                        UPDATE multimedia
                        SET nombre_telechargements = nombre_telechargements + 1
                        WHERE id = p_media_id;
                    WHEN 'partage' THEN
                        UPDATE multimedia
                        SET nombre_partages = nombre_partages + 1
                        WHERE id = p_media_id;
                    WHEN 'like' THEN
                        UPDATE multimedia
                        SET nombre_likes = nombre_likes + 1
                        WHERE id = p_media_id;
                    WHEN 'commentaire' THEN
                        UPDATE multimedia
                        SET nombre_commentaires = nombre_commentaires + 1
                        WHERE id = p_media_id;
                END CASE;
            END;
            \$function\$ LANGUAGE plpgsql;
        ");
    }

    /**
     * Créer les triggers
     */
    private function createTriggers(): void
    {
        // Fonction trigger pour générer le slug automatiquement
        DB::unprepared("
            CREATE OR REPLACE FUNCTION generate_multimedia_slug()
            RETURNS TRIGGER AS \$trigger\$
            DECLARE
                base_slug VARCHAR(255);
                final_slug VARCHAR(255);
                slug_counter INTEGER := 1;
            BEGIN
                -- Générer le slug de base à partir du titre
                IF NEW.slug IS NULL OR NEW.slug = '' THEN
                    base_slug := LOWER(TRIM(REGEXP_REPLACE(NEW.titre, '[^a-zA-Z0-9]+', '-', 'g'), '-'));
                    final_slug := base_slug;

                    -- Vérifier l'unicité et ajouter un suffixe si nécessaire
                    WHILE EXISTS (SELECT 1 FROM multimedia WHERE slug = final_slug AND id != COALESCE(NEW.id, '00000000-0000-0000-0000-000000000000'::uuid)) LOOP
                        final_slug := base_slug || '-' || slug_counter;
                        slug_counter := slug_counter + 1;
                    END LOOP;

                    NEW.slug := final_slug;
                END IF;

                RETURN NEW;
            END;
            \$trigger\$ LANGUAGE plpgsql;
        ");

        // Créer le trigger
        DB::statement("
            CREATE TRIGGER trg_generate_multimedia_slug
            BEFORE INSERT OR UPDATE ON multimedia
            FOR EACH ROW
            EXECUTE FUNCTION generate_multimedia_slug();
        ");

        // Trigger pour mettre à jour les timestamps de modification
        DB::unprepared("
            CREATE OR REPLACE FUNCTION update_multimedia_modified()
            RETURNS TRIGGER AS \$trigger\$
            BEGIN
                NEW.updated_at := CURRENT_TIMESTAMP;
                RETURN NEW;
            END;
            \$trigger\$ LANGUAGE plpgsql;
        ");

        DB::statement("
            CREATE TRIGGER trg_update_multimedia_modified
            BEFORE UPDATE ON multimedia
            FOR EACH ROW
            EXECUTE FUNCTION update_multimedia_modified();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS statistiques_galerie");
        DB::statement("DROP VIEW IF EXISTS medias_recents");
        DB::statement("DROP VIEW IF EXISTS galerie_publique");

        // Suppression des triggers
        DB::statement("DROP TRIGGER IF EXISTS trg_update_multimedia_modified ON multimedia");
        DB::statement("DROP TRIGGER IF EXISTS trg_generate_multimedia_slug ON multimedia");

        // Suppression des fonctions
        DB::statement("DROP FUNCTION IF EXISTS update_multimedia_modified()");
        DB::statement("DROP FUNCTION IF EXISTS generate_multimedia_slug()");
        DB::statement("DROP FUNCTION IF EXISTS increment_media_stats(UUID, VARCHAR)");
        DB::statement("DROP FUNCTION IF EXISTS validate_multimedia_json_fields(JSONB, JSONB, JSONB, JSONB, JSONB, JSONB, JSONB, JSONB)");

        // Suppression de la table
        Schema::dropIfExists('multimedia');
    }
};
