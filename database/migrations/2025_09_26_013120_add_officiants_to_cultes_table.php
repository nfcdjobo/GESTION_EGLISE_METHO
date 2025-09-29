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
        // Étape 1: Ajouter la nouvelle colonne officiants
        Schema::table('cultes', function (Blueprint $table) {
            $table->json('officiants')->nullable()->comment('Liste des officiants du culte (JSON: [{user_id, titre, provenance}, ...])')->after('capacite_prevue');
        });

        // Étape 2: Créer les fonctions de validation pour les officiants
        $this->createOfficiationsFunctions();

        // Étape 3: Migrer les données existantes vers la nouvelle structure
        $this->migrateExistingData();

        // Étape 4: Ajouter les contraintes de validation
        $this->addOfficiationsConstraints();

        // Étape 5: Mettre à jour les vues existantes
        $this->updateExistingViews();

        // Étape 6: Pas d'index pour le moment (on peut en ajouter un plus tard si nécessaire)
        // Les colonnes JSON peuvent être interrogées sans index pour des tables de taille moyenne

        // Étape 7: Supprimer les anciennes colonnes (optionnel - voir commentaire)
        // $this->dropOldColumns();
    }

    /**
     * Migrer les données existantes vers la nouvelle structure
     */
    private function migrateExistingData(): void
    {
        // Récupérer tous les cultes avec des responsables
        $cultes = DB::table('cultes')
            ->whereNotNull('pasteur_principal_id')
            ->orWhereNotNull('predicateur_id')
            ->orWhereNotNull('responsable_culte_id')
            ->orWhereNotNull('dirigeant_louange_id')
            ->get();

        foreach ($cultes as $culte) {
            $officiants = [];

            // Migrer le pasteur principal
            if ($culte->pasteur_principal_id) {
                $officiants[] = [
                    'user_id' => $culte->pasteur_principal_id,
                    'titre' => 'Pasteur Principal',
                    'provenance' => 'Église Locale'
                ];
            }

            // Migrer le prédicateur
            if ($culte->predicateur_id && $culte->predicateur_id !== $culte->pasteur_principal_id) {
                $officiants[] = [
                    'user_id' => $culte->predicateur_id,
                    'titre' => 'Prédicateur',
                    'provenance' => 'Église Locale'
                ];
            }

            // Migrer le responsable du culte
            if ($culte->responsable_culte_id &&
                $culte->responsable_culte_id !== $culte->pasteur_principal_id &&
                $culte->responsable_culte_id !== $culte->predicateur_id) {
                $officiants[] = [
                    'user_id' => $culte->responsable_culte_id,
                    'titre' => 'Responsable Organisation',
                    'provenance' => 'Église Locale'
                ];
            }

            // Migrer le dirigeant de louange
            if ($culte->dirigeant_louange_id &&
                !collect($officiants)->pluck('user_id')->contains($culte->dirigeant_louange_id)) {
                $officiants[] = [
                    'user_id' => $culte->dirigeant_louange_id,
                    'titre' => 'Dirigeant de Louange',
                    'provenance' => 'Église Locale'
                ];
            }

            // Migrer l'équipe du culte si elle existe
            if ($culte->equipe_culte) {
                try {
                    $equipe = json_decode($culte->equipe_culte, true);
                    if (is_array($equipe)) {
                        foreach ($equipe as $membre) {
                            // Vérifier la structure et éviter les doublons
                            if (isset($membre['user_id']) && isset($membre['role'])) {
                                $userExists = collect($officiants)->pluck('user_id')->contains($membre['user_id']);
                                if (!$userExists) {
                                    $officiants[] = [
                                        'user_id' => $membre['user_id'],
                                        'titre' => $membre['role'] ?? 'Membre Équipe',
                                        'provenance' => $membre['provenance'] ?? 'Église Locale'
                                    ];
                                }
                            }
                        }
                    }
                } catch (Exception $e) {
                    // Ignorer les erreurs de parsing JSON
                }
            }

            // Mettre à jour le culte avec les nouveaux officiants
            if (!empty($officiants)) {
                DB::table('cultes')
                    ->where('id', $culte->id)
                    ->update(['officiants' => json_encode($officiants)]);
            }
        }
    }

    /**
     * Créer les fonctions pour la validation des officiants
     */
    private function createOfficiationsFunctions(): void
    {
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
    }

    /**
     * Ajouter les contraintes pour les officiants
     */
    private function addOfficiationsConstraints(): void
    {
        DB::statement("
            ALTER TABLE cultes ADD CONSTRAINT chk_officiants_valide
            CHECK (
                officiants IS NULL OR
                validate_officiants_json(officiants::jsonb)
            )
        ");
    }

    /**
     * Mettre à jour les vues existantes
     */
    private function updateExistingViews(): void
    {
        // Supprimer la vue existante d'abord
        DB::statement("DROP VIEW IF EXISTS cultes_a_venir");

        // Recréer la vue avec la nouvelle structure
        DB::statement("
            CREATE VIEW cultes_a_venir AS
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
                -- Conserver la compatibilité avec les anciennes colonnes
                COALESCE(pp.prenom || ' ' || pp.nom, 'Non assigné') AS nom_pasteur_principal,
                COALESCE(pred.prenom || ' ' || pred.nom, 'Non assigné') AS nom_predicateur,
                COALESCE(resp.prenom || ' ' || resp.nom, 'Non assigné') AS nom_responsable,
                COALESCE(dl.prenom || ' ' || dl.nom, 'Non assigné') AS nom_dirigeant_louange,
                (c.date_culte - CURRENT_DATE) AS jours_restants,
                c.created_at,
                c.updated_at
            FROM cultes c
            LEFT JOIN users pp ON c.pasteur_principal_id = pp.id AND pp.deleted_at IS NULL
            LEFT JOIN users pred ON c.predicateur_id = pred.id AND pred.deleted_at IS NULL
            LEFT JOIN users resp ON c.responsable_culte_id = resp.id AND resp.deleted_at IS NULL
            LEFT JOIN users dl ON c.dirigeant_louange_id = dl.id AND dl.deleted_at IS NULL
            WHERE c.date_culte >= CURRENT_DATE
              AND c.statut IN ('planifie', 'en_preparation')
              AND c.deleted_at IS NULL
            ORDER BY c.date_culte ASC, c.heure_debut ASC
        ");
    }

    /**
     * Supprimer les anciennes colonnes (à décommenter après vérification)
     * ATTENTION: Ne pas utiliser immédiatement - garder les colonnes pendant la transition
     */
    private function dropOldColumns(): void
    {
        // À décommenter SEULEMENT après avoir vérifié que tout fonctionne bien
        /*
        Schema::table('cultes', function (Blueprint $table) {
            // Supprimer les foreign keys d'abord
            $table->dropForeign(['pasteur_principal_id']);
            $table->dropForeign(['predicateur_id']);
            $table->dropForeign(['responsable_culte_id']);
            $table->dropForeign(['dirigeant_louange_id']);

            // Supprimer les index
            $table->dropIndex(['pasteur_principal_id', 'date_culte']);
            $table->dropIndex(['predicateur_id', 'date_culte']);

            // Supprimer les colonnes
            $table->dropColumn([
                'pasteur_principal_id',
                'predicateur_id',
                'responsable_culte_id',
                'dirigeant_louange_id',
                'equipe_culte'
            ]);
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les contraintes
        DB::statement("ALTER TABLE cultes DROP CONSTRAINT IF EXISTS chk_officiants_valide");

        // Supprimer les fonctions
        DB::statement("DROP FUNCTION IF EXISTS validate_officiants_json(JSONB)");
        DB::statement("DROP FUNCTION IF EXISTS get_officiants_summary(JSONB)");

        // Remettre l'ancienne vue
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
                COALESCE(pp.prenom || ' ' || pp.nom, 'Non assigné') AS nom_pasteur_principal,
                COALESCE(pred.prenom || ' ' || pred.nom, 'Non assigné') AS nom_predicateur,
                COALESCE(resp.prenom || ' ' || resp.nom, 'Non assigné') AS nom_responsable,
                COALESCE(dl.prenom || ' ' || dl.nom, 'Non assigné') AS nom_dirigeant_louange,
                (c.date_culte - CURRENT_DATE) AS jours_restants,
                c.created_at,
                c.updated_at
            FROM cultes c
            LEFT JOIN users pp ON c.pasteur_principal_id = pp.id AND pp.deleted_at IS NULL
            LEFT JOIN users pred ON c.predicateur_id = pred.id AND pred.deleted_at IS NULL
            LEFT JOIN users resp ON c.responsable_culte_id = resp.id AND resp.deleted_at IS NULL
            LEFT JOIN users dl ON c.dirigeant_louange_id = dl.id AND dl.deleted_at IS NULL
            WHERE c.date_culte >= CURRENT_DATE
              AND c.statut IN ('planifie', 'en_preparation')
              AND c.deleted_at IS NULL
            ORDER BY c.date_culte ASC, c.heure_debut ASC
        ");

        // Supprimer la colonne officiants
        Schema::table('cultes', function (Blueprint $table) {
            $table->dropColumn('officiants');
        });
    }
};
