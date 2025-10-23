<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     * ATTENTION: N'exécutez cette migration qu'après avoir vérifié
     * que la nouvelle structure avec les officiants fonctionne correctement
     */
public function up(): void
{
    // ÉTAPE 1: Supprimer d'abord la vue qui dépend des colonnes
    DB::statement("DROP VIEW IF EXISTS cultes_a_venir");



    // Attendre un peu pour la suppression des contraintes
    sleep(1);


    // ÉTAPE 4: Recréer la vue finale sans les anciennes colonnes
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
            (c.date_culte - CURRENT_DATE) AS jours_restants,
            c.created_at,
            c.updated_at
        FROM cultes c
        WHERE c.date_culte >= CURRENT_DATE
          AND c.statut IN ('planifie', 'en_preparation')
          AND c.deleted_at IS NULL
        ORDER BY c.date_culte ASC, c.heure_debut ASC
    ");

    // ÉTAPE 5: Créer des fonctions utilitaires
    $this->createOfficicantsUtilityFunctions();
}

    /**
     * Créer des fonctions utilitaires pour extraire des informations spécifiques des officiants
     */
    private function createOfficicantsUtilityFunctions(): void
    {
        // Fonction pour obtenir le pasteur principal depuis les officiants
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_pasteur_principal_from_officiants(officiants_json JSONB)
            RETURNS TABLE(user_id UUID, nom_complet TEXT) AS \$get_pasteur\$
            DECLARE
                officiant JSONB;
                pasteur_id TEXT;
                pasteur_nom TEXT;
            BEGIN
                -- Chercher le pasteur principal dans les officiants
                FOR officiant IN SELECT jsonb_array_elements(officiants_json)
                LOOP
                    IF LOWER(officiant->>'titre') LIKE '%pasteur%principal%' OR
                       LOWER(officiant->>'titre') = 'pasteur principal' THEN

                        pasteur_id := officiant->>'user_id';

                        -- Récupérer le nom complet
                        SELECT COALESCE(u.prenom || ' ' || u.nom, u.email) INTO pasteur_nom
                        FROM users u
                        WHERE u.id::text = pasteur_id
                        AND u.deleted_at IS NULL;

                        user_id := pasteur_id::UUID;
                        nom_complet := pasteur_nom;
                        RETURN NEXT;
                        RETURN;
                    END IF;
                END LOOP;

                -- Si pas trouvé, retourner null
                user_id := NULL;
                nom_complet := NULL;
                RETURN NEXT;
            END;
            \$get_pasteur\$ LANGUAGE plpgsql;
        ");

        // Fonction pour obtenir le prédicateur depuis les officiants
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_predicateur_from_officiants(officiants_json JSONB)
            RETURNS TABLE(user_id UUID, nom_complet TEXT) AS \$get_predicateur\$
            DECLARE
                officiant JSONB;
                pred_id TEXT;
                pred_nom TEXT;
            BEGIN
                -- Chercher le prédicateur dans les officiants
                FOR officiant IN SELECT jsonb_array_elements(officiants_json)
                LOOP
                    IF LOWER(officiant->>'titre') LIKE '%prédicateur%' OR
                       LOWER(officiant->>'titre') LIKE '%predicateur%' OR
                       LOWER(officiant->>'titre') = 'prédicateur' OR
                       LOWER(officiant->>'titre') = 'predicateur' THEN

                        pred_id := officiant->>'user_id';

                        -- Récupérer le nom complet
                        SELECT COALESCE(u.prenom || ' ' || u.nom, u.email) INTO pred_nom
                        FROM users u
                        WHERE u.id::text = pred_id
                        AND u.deleted_at IS NULL;

                        user_id := pred_id::UUID;
                        nom_complet := pred_nom;
                        RETURN NEXT;
                        RETURN;
                    END IF;
                END LOOP;

                -- Si pas trouvé, retourner null
                user_id := NULL;
                nom_complet := NULL;
                RETURN NEXT;
            END;
            \$get_predicateur\$ LANGUAGE plpgsql;
        ");

        // -- Fonction pour obtenir les officiants par titre
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_officiants_by_titre(officiants_json JSONB, titre_recherche TEXT)
            RETURNS TABLE(user_id UUID, nom_complet TEXT, provenance TEXT) AS \$get_by_titre\$
            DECLARE
                officiant JSONB;
                off_id TEXT;
                off_nom TEXT;
                off_provenance TEXT;
            BEGIN
                -- Chercher les officiants avec le titre spécifié
                FOR officiant IN SELECT jsonb_array_elements(officiants_json)
                LOOP
                    IF LOWER(officiant->>'titre') LIKE LOWER('%' || titre_recherche || '%') THEN

                        off_id := officiant->>'user_id';
                        off_provenance := officiant->>'provenance';

                        -- Récupérer le nom complet
                        SELECT COALESCE(u.prenom || ' ' || u.nom, u.email) INTO off_nom
                        FROM users u
                        WHERE u.id::text = off_id
                        AND u.deleted_at IS NULL;

                        user_id := off_id::UUID;
                        nom_complet := off_nom;
                        provenance := off_provenance;
                        RETURN NEXT;
                    END IF;
                END LOOP;
            END;
            \$get_by_titre\$ LANGUAGE plpgsql;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les fonctions utilitaires
        DB::statement("DROP FUNCTION IF EXISTS get_pasteur_principal_from_officiants(JSONB)");
        DB::statement("DROP FUNCTION IF EXISTS get_predicateur_from_officiants(JSONB)");
        DB::statement("DROP FUNCTION IF EXISTS get_officiants_by_titre(JSONB, TEXT)");

        // Recréer les colonnes supprimées
        Schema::table('cultes', function (Blueprint $table) {
            // $table->uuid('pasteur_principal_id')->nullable()->comment('Pasteur principal du culte');
            // $table->uuid('predicateur_id')->nullable()->comment('Prédicateur/Orateur principal');
            // $table->uuid('responsable_culte_id')->nullable()->comment('Responsable de l\'organisation');
            // $table->uuid('dirigeant_louange_id')->nullable()->comment('Dirigeant de louange');
            $table->json('equipe_culte')->nullable()->comment('Équipe du culte (JSON: rôles et personnes)');

            // Recréer les foreign keys
            // $table->foreign('pasteur_principal_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('predicateur_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('responsable_culte_id')->references('id')->on('users')->onDelete('set null');
            // $table->foreign('dirigeant_louange_id')->references('id')->on('users')->onDelete('set null');

            // Recréer les index
            // $table->index(['pasteur_principal_id', 'date_culte'], 'idx_cultes_pasteur_date');
            // $table->index(['predicateur_id', 'date_culte'], 'idx_cultes_predicateur_date');
        });

        // Restaurer la vue avec les anciennes colonnes
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
                COALESCE(pp.prenom || ' ' || pp.nom, 'Non assigné') AS nom_pasteur_principal,
                COALESCE(pred.prenom || ' ' || pred.nom, 'Non assigné') AS nom_predicateur,
                COALESCE(resp.prenom || ' ' || resp.nom, 'Non assigné') AS nom_responsable,
                COALESCE(dl.prenom || ' ' || dl.nom, 'Non assigné') AS nom_dirigeant_louange,
                (c.date_culte - CURRENT_DATE) AS jours_restants,
                c.created_at,
                c.updated_at
            FROM cultes c
            WHERE c.date_culte >= CURRENT_DATE
              AND c.statut IN ('planifie', 'en_preparation')
              AND c.deleted_at IS NULL
            ORDER BY c.date_culte ASC, c.heure_debut ASC
        ");
    }
};
