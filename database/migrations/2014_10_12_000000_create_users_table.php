<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Identifiant unique UUID
            $table->uuid('id')->primary();
            $table->uuid('classe_id')->nullable();

            // Informations personnelles de base
            $table->string('prenom', 100);
            $table->string('nom', 100);
            $table->date('date_naissance')->nullable();
            $table->enum('sexe', ['masculin', 'feminin']);

            // Informations de contact
            $table->string('telephone_1', 20);
            $table->string('telephone_2', 20)->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();

            // Adresse
            $table->string('adresse_ligne_1', 200)->nullable();
            $table->string('adresse_ligne_2', 200)->nullable();
            $table->string('ville', 100)->nullable();
            $table->string('code_postal', 20)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('pays', 5)->default('CI'); // Côte d'Ivoire par défaut

            // Informations familiales
            $table->enum('statut_matrimonial', ['celibataire', 'marie', 'divorce', 'veuf'])->default('celibataire');
            $table->integer('nombre_enfants')->default(0)->unsigned();

            // Informations professionnelles
            $table->string('profession', 150)->nullable();
            $table->string('employeur', 150)->nullable();

            // Informations d'église
            $table->date('date_adhesion')->nullable(); // Date d'adhésion
            $table->enum('statut_membre', ['actif', 'inactif', 'visiteur', 'nouveau_converti'])->default('visiteur');
            $table->enum('statut_bapteme', ['non_baptise', 'baptise', 'confirme'])->default('non_baptise');
            $table->date('date_bapteme')->nullable();
            $table->string('eglise_precedente', 150)->nullable(); // Église précédente

            // Contact d'urgence
            $table->string('contact_urgence_nom', 100)->nullable();
            $table->string('contact_urgence_telephone', 20)->nullable();
            $table->string('contact_urgence_relation', 50)->nullable();

            // Informations spirituelles
            $table->text('temoignage')->nullable(); // Témoignage de conversion
            $table->text('dons_spirituels')->nullable(); // Dons spirituels
            $table->text('demandes_priere')->nullable(); // Demandes de prière

            // Informations système
            $table->string('password')->nullable();
            $table->boolean('actif')->default(true);
            $table->rememberToken();

            // Photo de profil
            $table->string('photo_profil', 500)->nullable();

            // Notes administratives
            $table->text('notes_admin')->nullable();

            // Timestamps et soft delete
            $table->timestamps();
            $table->softDeletes(); // Pour le soft delete

            // Index pour optimiser les recherches (pas de foreign keys pour l'instant)
            $table->index(['statut_membre', 'actif'], 'idx_users_statut_actif');
            $table->index(['prenom', 'nom'], 'idx_users_nom_complet');
            $table->index('date_adhesion', 'idx_users_adhesion');
            $table->index('classe_id', 'idx_users_classe');
            $table->index('email', 'idx_users_email');
            $table->index('telephone_1', 'idx_users_telephone');
            $table->index(['date_naissance', 'sexe'], 'idx_users_demographie');
        });

        // Commentaire sur la table
        DB::statement("COMMENT ON TABLE users IS 'Gestion des membres et membres de l''église';");

        // Ajouter les contraintes de sécurité
        $this->addSecurityConstraints();

        // Créer les vues utilitaires
        $this->createUtilityViews();
    }

    /**
     * Ajouter les contraintes de sécurité
     */
    private function addSecurityConstraints(): void
    {
        // Contrainte de validation des noms (non vides)
        DB::statement("
            ALTER TABLE users ADD CONSTRAINT chk_noms_non_vides
            CHECK (
                LENGTH(TRIM(prenom)) > 0 AND
                LENGTH(TRIM(nom)) > 0
            )
        ");

        // Contrainte de validation de la date de naissance
        DB::statement("
            ALTER TABLE users ADD CONSTRAINT chk_date_naissance_valide
            CHECK (
                date_naissance IS NULL OR
                (date_naissance >= '1900-01-01' AND date_naissance <= CURRENT_DATE)
            )
        ");

        // Contrainte de validation du nombre d'enfants
        DB::statement("
            ALTER TABLE users ADD CONSTRAINT chk_nombre_enfants_positif
            CHECK (nombre_enfants >= 0 AND nombre_enfants <= 20)
        ");

        // Contrainte de validation de l'email
        DB::statement("
            ALTER TABLE users ADD CONSTRAINT chk_email_valide
            CHECK (
                email ~ '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$'
            )
        ");

        // Contrainte de validation des téléphones
        DB::statement("
            ALTER TABLE users ADD CONSTRAINT chk_telephone_valide
            CHECK (
                LENGTH(TRIM(telephone_1)) >= 8 AND
                (telephone_2 IS NULL OR LENGTH(TRIM(telephone_2)) >= 8)
            )
        ");

        // Contrainte de cohérence des dates de baptême
        DB::statement("
            ALTER TABLE users ADD CONSTRAINT chk_dates_bapteme_coherentes
            CHECK (
                (statut_bapteme = 'non_baptise' AND date_bapteme IS NULL) OR
                (statut_bapteme IN ('baptise', 'confirme') AND date_bapteme IS NOT NULL) OR
                (date_bapteme IS NULL)
            )
        ");

        // Contrainte de validation de l'URL de la photo
        DB::statement("
            ALTER TABLE users ADD CONSTRAINT chk_photo_url_valide
            CHECK (
                photo_profil IS NULL OR
                photo_profil ~ '^https?://.*' OR
                photo_profil ~ '^/.*' OR
                LENGTH(photo_profil) > 0
            )
        ");

        // Contrainte de validation du code postal
        DB::statement("
            ALTER TABLE users ADD CONSTRAINT chk_code_postal_valide
            CHECK (
                code_postal IS NULL OR
                LENGTH(TRIM(code_postal)) >= 2
            )
        ");

        // Contrainte de cohérence date d'adhésion
        DB::statement("
            ALTER TABLE users ADD CONSTRAINT chk_date_adhesion_coherente
            CHECK (
                (statut_membre = 'visiteur' AND date_adhesion IS NULL) OR
                (statut_membre != 'visiteur' AND date_adhesion IS NOT NULL) OR
                (date_adhesion IS NULL OR date_adhesion <= CURRENT_DATE)
            )
        ");







    }

    /**
     * Créer les vues utilitaires
     */
    private function createUtilityViews(): void
    {
        // Vue des membres actifs (version de base sans jointures)
        DB::statement("
            CREATE OR REPLACE VIEW membres_actifs AS
            SELECT
                u.id,
                u.prenom,
                u.nom,
                (u.prenom || ' ' || u.nom) AS nom_complet,
                u.email,
                u.telephone_1,
                u.telephone_2,
                u.sexe,
                u.date_naissance,
                EXTRACT(YEAR FROM AGE(COALESCE(u.date_naissance, CURRENT_DATE))) AS age,
                u.statut_membre,
                u.statut_bapteme,
                u.date_adhesion,
                u.classe_id,
                u.profession,
                u.ville,
                u.pays,
                u.actif,
                u.created_at,
                u.updated_at
            FROM users u
            WHERE u.deleted_at IS NULL
              AND u.actif = true
              AND u.statut_membre IN ('actif', 'nouveau_converti')
            ORDER BY u.nom, u.prenom
        ");

        // Vue statistiques des membres
        DB::statement("
            CREATE OR REPLACE VIEW statistiques_membres AS
            SELECT
                COUNT(*) AS total_membres,
                COUNT(CASE WHEN statut_membre = 'actif' THEN 1 END) AS membres_actifs,
                COUNT(CASE WHEN statut_membre = 'inactif' THEN 1 END) AS membres_inactifs,
                COUNT(CASE WHEN statut_membre = 'visiteur' THEN 1 END) AS visiteurs,
                COUNT(CASE WHEN statut_membre = 'nouveau_converti' THEN 1 END) AS nouveaux_convertis,
                COUNT(CASE WHEN statut_bapteme = 'baptise' THEN 1 END) AS baptises,
                COUNT(CASE WHEN statut_bapteme = 'confirme' THEN 1 END) AS confirmes,
                COUNT(CASE WHEN sexe = 'masculin' THEN 1 END) AS hommes,
                COUNT(CASE WHEN sexe = 'feminin' THEN 1 END) AS femmes,
                ROUND(AVG(EXTRACT(YEAR FROM AGE(COALESCE(date_naissance, CURRENT_DATE)))), 1) AS age_moyen,
                COUNT(CASE WHEN actif = true THEN 1 END) AS comptes_actifs
            FROM users
            WHERE deleted_at IS NULL
        ");

        // Vue anniversaires du mois
        DB::statement("
            CREATE OR REPLACE VIEW anniversaires_du_mois AS
            SELECT
                u.id,
                u.prenom,
                u.nom,
                u.date_naissance,
                u.email,
                u.telephone_1,
                EXTRACT(DAY FROM u.date_naissance) AS jour_anniversaire,
                EXTRACT(YEAR FROM AGE(u.date_naissance)) AS age_actuel
            FROM users u
            WHERE u.deleted_at IS NULL
              AND u.actif = true
              AND u.date_naissance IS NOT NULL
              AND EXTRACT(MONTH FROM u.date_naissance) = EXTRACT(MONTH FROM CURRENT_DATE)
            ORDER BY EXTRACT(DAY FROM u.date_naissance)
        ");
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS anniversaires_du_mois");
        DB::statement("DROP VIEW IF EXISTS statistiques_membres");
        DB::statement("DROP VIEW IF EXISTS membres_actifs");

        // Suppression de la table
        Schema::dropIfExists('users');
    }
};
