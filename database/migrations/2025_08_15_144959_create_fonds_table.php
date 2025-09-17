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
        Schema::create('fonds', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Relations essentielles
            $table->uuid('culte_id')->nullable()->comment('Culte associé à la transaction');
            $table->uuid('donateur_id')->nullable()->comment('Membre qui a fait le don');
            $table->uuid('collecteur_id')->nullable()->comment('Personne qui a collecté');
            $table->uuid('validateur_id')->nullable()->comment('Personne qui a validé');

            // Informations de base de la transaction
            $table->string('numero_transaction', 30)->unique()->comment('Numéro unique de transaction');
            $table->date('date_transaction')->comment('Date de la transaction');
            $table->time('heure_transaction')->nullable()->comment('Heure de la transaction');
            $table->decimal('montant', 12, 2)->comment('Montant de la transaction');
            $table->string('devise', 3)->default('XOF')->comment('Devise');

            // Type de transaction simplifié
            $table->enum('type_transaction', [
                'dime',                    // Dîme
                'offrande_libre',          // Offrande libre
                'offrande_ordinaire',          // Offrande ordinaire
                'offrande_speciale',       // Offrande spéciale (Noël, Pâques, etc.)
                'offrande_mission',        // Offrande pour les missions
                'offrande_construction',   // Offrande pour travaux/construction
                'don_special',             // Don spécial ponctuel
                'soutien_pasteur',         // Soutien au pasteur
                'frais_ceremonie',         // Frais de cérémonie (mariage, baptême, etc.)
                'don_materiel',            // Don en nature
                'autres'                   // Autres types
            ])->comment('Type de transaction');

            $table->enum('categorie', [
                'reguliere',               // Transaction régulière
                'exceptionnelle',          // Transaction exceptionnelle
                'urgente'                  // Transaction urgente
            ])->default('reguliere')->comment('Catégorie de transaction');

            // Informations du donateur
            $table->string('nom_donateur_anonyme')->nullable()->comment('Nom si donateur non membre');
            $table->string('contact_donateur')->nullable()->comment('Contact du donateur (tél/email)');
            $table->boolean('est_anonyme')->default(false)->comment('Don anonyme');
            $table->boolean('est_membre')->default(true)->comment('Donateur est membre de l église');

            // Méthode de paiement simplifiée
            $table->enum('mode_paiement', [
                'especes',                 // Espèces
                'mobile_money',            // Mobile Money (Orange, MTN, Moov)
                'virement',               // Virement bancaire
                'cheque',                 // Chèque
                'nature'                  // Don en nature
            ])->default('especes')->comment('Mode de paiement');

            $table->string('reference_paiement')->nullable()->comment('Référence de paiement');
            $table->json('details_paiement')->nullable()->comment('Détails supplémentaires (JSON)');

            // Don en nature (simplifié)
            $table->text('description_don_nature')->nullable()->comment('Description du don en nature');
            $table->decimal('valeur_estimee', 10, 2)->nullable()->comment('Valeur estimée du don en nature');

            // Affectation
            $table->string('destination')->nullable()->comment('Destination ou projet bénéficiaire');
            $table->uuid('projet_id')->nullable()->comment('Projet spécifique bénéficiaire');
            $table->boolean('est_flechee')->default(false)->comment('Offrande fléchée pour un usage spécifique');
            $table->text('instructions_donateur')->nullable()->comment('Instructions particulières du donateur');

            // Statut et validation
            $table->enum('statut', [
                'en_attente',             // En attente de validation
                'validee',                // Transaction validée
                'annulee',                // Transaction annulée
                'remboursee'              // Transaction remboursée
            ])->default('en_attente')->comment('Statut de la transaction');

            $table->timestamp('validee_le')->nullable()->comment('Date et heure de validation');
            $table->text('motif_annulation')->nullable()->comment('Motif d annulation/remboursement');
            $table->text('notes_validation')->nullable()->comment('Notes de validation');

            // Reçu fiscal
            $table->string('numero_recu', 30)->nullable()->unique()->comment('Numéro de reçu fiscal');
            $table->boolean('recu_demande')->default(false)->comment('Reçu fiscal demandé');
            $table->boolean('recu_emis')->default(false)->comment('Reçu fiscal émis');
            $table->date('date_emission_recu')->nullable()->comment('Date d émission du reçu');
            $table->string('fichier_recu')->nullable()->comment('Chemin vers le fichier reçu');

            // Périodicité (simplifié)
            $table->boolean('est_recurrente')->default(false)->comment('Transaction récurrente');
            $table->enum('frequence_recurrence', [
                'hebdomadaire',
                'mensuelle',
                'trimestrielle',
                'annuelle'
            ])->nullable()->comment('Fréquence de récurrence');
            $table->date('prochaine_echeance')->nullable()->comment('Prochaine échéance');
            $table->uuid('transaction_parent_id')->nullable()->comment('Transaction parent si récurrente');

            // Contexte (simplifié)
            $table->string('occasion_speciale')->nullable()->comment('Occasion spéciale (Noël, Pâques, etc.)');
            $table->string('lieu_collecte')->nullable()->comment('Lieu de collecte (église principale, annexe, etc.)');

            // Audit et traçabilité
            $table->uuid('cree_par')->nullable()->comment('Membres créateur');
            $table->uuid('modifie_par')->nullable()->comment('Dernier modificateur');
            $table->timestamp('derniere_verification')->nullable()->comment('Dernière vérification comptable');
            $table->uuid('verifie_par')->nullable()->comment('Vérificateur comptable');
            $table->text('notes_comptable')->nullable()->comment('Notes du responsable financier');

            // Informations fiscales (simplifié)
            $table->boolean('deductible_impots')->default(true)->comment('Don déductible des impôts');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key
            $table->foreign('culte_id')->references('id')->on('cultes')->onDelete('set null');
            $table->foreign('donateur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('collecteur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validateur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verifie_par')->references('id')->on('users')->onDelete('set null');

            // Index optimisés pour les performances
            $table->index(['date_transaction', 'type_transaction'], 'idx_fonds_date_type');
            $table->index(['culte_id', 'type_transaction'], 'idx_fonds_culte_type');
            $table->index(['donateur_id', 'date_transaction'], 'idx_fonds_donateur_date');
            $table->index(['statut', 'date_transaction'], 'idx_fonds_statut_date');
            $table->index(['mode_paiement', 'statut'], 'idx_fonds_paiement_statut');
            $table->index(['type_transaction', 'montant'], 'idx_fonds_type_montant');
            $table->index(['validee_le', 'statut'], 'idx_fonds_validation');
            $table->index(['est_recurrente', 'prochaine_echeance'], 'idx_fonds_recurrence');
            $table->index(['projet_id', 'est_flechee'], 'idx_fonds_projet');

            // Index pour les rapports financiers
            $table->index([
                'date_transaction',
                'type_transaction',
                'statut',
                'montant'
            ], 'idx_fonds_rapport_financier');

            // Index pour les recherches fréquentes
            $table->index(['numero_transaction'], 'idx_fonds_numero');
            $table->index(['numero_recu'], 'idx_fonds_recu');
            $table->index(['est_anonyme', 'donateur_id'], 'idx_fonds_anonymat');
        });

        // Contrainte auto-référentielle pour les transactions récurrentes
        Schema::table('fonds', function (Blueprint $table) {
            $table->foreign('transaction_parent_id')->references('id')->on('fonds')->onDelete('set null');
        });

        // Commentaire sur la table
        DB::statement("COMMENT ON TABLE fonds IS 'Gestion des transactions financières de l église (dîmes, offrandes, dons)';");

        // Contraintes de validation métier
        DB::statement("
            ALTER TABLE fonds ADD CONSTRAINT chk_fonds_montant_positif
            CHECK (montant > 0)
        ");

        DB::statement("
            ALTER TABLE fonds ADD CONSTRAINT chk_fonds_coherence_don_nature
            CHECK (
                (type_transaction != 'don_materiel' AND description_don_nature IS NULL AND valeur_estimee IS NULL) OR
                (type_transaction = 'don_materiel' AND description_don_nature IS NOT NULL)
            )
        ");

        DB::statement("
            ALTER TABLE fonds ADD CONSTRAINT chk_fonds_coherence_recurrence
            CHECK (
                (est_recurrente = false AND frequence_recurrence IS NULL AND prochaine_echeance IS NULL) OR
                (est_recurrente = true AND frequence_recurrence IS NOT NULL)
            )
        ");

        DB::statement("
            ALTER TABLE fonds ADD CONSTRAINT chk_fonds_coherence_recu
            CHECK (
                (recu_emis = false OR (recu_emis = true AND numero_recu IS NOT NULL AND date_emission_recu IS NOT NULL))
            )
        ");

        // Vues utilitaires optimisées
        // DB::statement("
        //     CREATE OR REPLACE VIEW transactions_par_culte AS
        //     SELECT
        //         f.culte_id,
        //         c.titre AS titre_culte,
        //         c.date_culte,
        //         f.type_transaction,
        //         COUNT(*) AS nombre_transactions,
        //         SUM(f.montant) AS total_montant,
        //         AVG(f.montant) AS montant_moyen,
        //         f.devise
        //     FROM fonds f
        //     INNER JOIN cultes c ON f.culte_id = c.id
        //     WHERE f.statut = 'validee' AND f.deleted_at IS NULL AND c.deleted_at IS NULL
        //     GROUP BY f.culte_id, c.titre, c.date_culte, f.type_transaction, f.devise
        //     ORDER BY c.date_culte DESC
        // ");

        DB::statement("DROP VIEW IF EXISTS transactions_par_culte CASCADE");

DB::statement("
    CREATE VIEW transactions_par_culte AS
    SELECT
        f.culte_id,
        c.titre AS titre_culte,
        c.date_culte,
        f.type_transaction,
        COUNT(*) AS nombre_transactions,
        SUM(f.montant) AS total_montant,
        AVG(f.montant) AS montant_moyen,
        f.devise
    FROM fonds f
    INNER JOIN cultes c ON f.culte_id = c.id
    WHERE f.statut = 'validee' AND f.deleted_at IS NULL AND c.deleted_at IS NULL
    GROUP BY f.culte_id, c.titre, c.date_culte, f.type_transaction, f.devise
    ORDER BY c.date_culte DESC
");


        // DB::statement("
        //     CREATE OR REPLACE VIEW statistiques_financieres_mensuelles AS
        //     SELECT
        //         EXTRACT(YEAR FROM date_transaction)::int AS annee,
        //         EXTRACT(MONTH FROM date_transaction)::int AS mois,
        //         type_transaction,
        //         mode_paiement,
        //         COUNT(*) AS nombre_transactions,
        //         SUM(montant) AS total_montant,
        //         AVG(montant) AS montant_moyen,
        //         COUNT(DISTINCT donateur_id) AS donateurs_uniques
        //     FROM fonds
        //     WHERE statut = 'validee' AND deleted_at IS NULL
        //     GROUP BY annee, mois, type_transaction, mode_paiement
        //     ORDER BY annee DESC, mois DESC
        // ");

        DB::statement("DROP VIEW IF EXISTS statistiques_financieres_mensuelles CASCADE");

DB::statement("
    CREATE VIEW statistiques_financieres_mensuelles AS
    SELECT
        EXTRACT(YEAR FROM date_transaction)::int AS annee,
        EXTRACT(MONTH FROM date_transaction)::int AS mois,
        type_transaction,
        mode_paiement,
        COUNT(*) AS nombre_transactions,
        SUM(montant) AS total_montant,
        AVG(montant) AS montant_moyen,
        COUNT(DISTINCT donateur_id) AS donateurs_uniques
    FROM fonds
    WHERE statut = 'validee' AND deleted_at IS NULL
    GROUP BY annee, mois, type_transaction, mode_paiement
    ORDER BY annee DESC, mois DESC
");

        DB::statement("
            CREATE OR REPLACE VIEW donateurs_reguliers AS
            SELECT
                f.donateur_id,
                CONCAT(u.prenom, ' ', u.nom) AS nom_donateur,
                u.email,
                u.telephone_1,
                COUNT(*) AS nombre_dons,
                SUM(f.montant) AS total_dons,
                AVG(f.montant) AS don_moyen,
                MIN(f.date_transaction) AS premier_don,
                MAX(f.date_transaction) AS dernier_don,
                (MAX(f.date_transaction) - MIN(f.date_transaction)) AS jours_donateur
            FROM fonds f
            INNER JOIN users u ON f.donateur_id = u.id
            WHERE f.statut = 'validee' AND f.deleted_at IS NULL AND f.donateur_id IS NOT NULL
            GROUP BY f.donateur_id, u.prenom, u.nom, u.email, u.telephone_1
            HAVING COUNT(*) >= 3
            ORDER BY total_dons DESC
        ");

        // Vue pour les transactions en attente de validation
        DB::statement("
            CREATE OR REPLACE VIEW transactions_en_attente AS
            SELECT
                f.id,
                f.numero_transaction,
                f.date_transaction,
                f.montant,
                f.devise,
                f.type_transaction,
                f.mode_paiement,
                CASE
                    WHEN f.donateur_id IS NOT NULL THEN CONCAT(u.prenom, ' ', u.nom)
                    ELSE f.nom_donateur_anonyme
                END AS nom_donateur,
                CONCAT(uc.prenom, ' ', uc.nom) AS nom_collecteur,
                f.created_at,
                (CURRENT_DATE - f.date_transaction) AS jours_attente
            FROM fonds f
            LEFT JOIN users u ON f.donateur_id = u.id
            LEFT JOIN users uc ON f.collecteur_id = uc.id
            WHERE f.statut = 'en_attente' AND f.deleted_at IS NULL
            ORDER BY f.date_transaction ASC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS transactions_en_attente");
        DB::statement("DROP VIEW IF EXISTS donateurs_reguliers");
        DB::statement("DROP VIEW IF EXISTS statistiques_financieres_mensuelles");
        DB::statement("DROP VIEW IF EXISTS transactions_par_culte");

        // Suppression de la table
        Schema::dropIfExists('fonds');
    }
};
