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
        Schema::create('transactions_spirituelles', function (Blueprint $table) {
            // Clé primaire
            $table->uuid('id')->primary();

            // Relations
            $table->uuid('culte_id')->nullable()->comment('Culte associé à la transaction');
            $table->uuid('donateur_id')->nullable()->comment('Membre qui a fait le don');
            $table->uuid('collecteur_id')->nullable()->comment('Personne qui a collecté');
            $table->uuid('validateur_id')->nullable()->comment('Personne qui a validé');

            // Informations de base de la transaction
            $table->string('numero_transaction', 50)->unique()->comment('Numéro unique de transaction');
            $table->date('date_transaction')->comment('Date de la transaction');
            $table->time('heure_transaction')->nullable()->comment('Heure de la transaction');
            $table->decimal('montant', 15, 2)->comment('Montant de la transaction');
            $table->string('devise', 3)->default('XOF')->comment('Devise (XOF, EUR, USD, etc.)');

            // Type et catégorie de transaction
            $table->enum('type_transaction', [
                'dime',                 // Dîme
                'offrande_libre',       // Offrande libre
                'offrande_speciale',    // Offrande spéciale
                'offrande_ordinaire',    // Offrande spéciale
                'offrande_mission',     // Offrande pour les missions
                'offrande_construction', // Offrande pour construction
                'don_materiel',         // Don en nature
                'don_special',          // Don spécial
                'collecte_urgence',     // Collecte d'urgence
                'soutien_pasteur',      // Soutien au pasteur
                'frais_ceremonie',      // Frais de cérémonie
                'cotisation',           // Cotisation
                'parrainage',           // Parrainage
                'vente_articles',       // Vente d'articles religieux
                'location_salle',       // Location de salle
                'autres'                // Autres
            ])->comment('Type de transaction spirituelle');

            $table->enum('categorie', [
                'reguliere',            // Transaction régulière
                'exceptionnelle',       // Transaction exceptionnelle
                'urgente',             // Transaction urgente
                'programmee',          // Transaction programmée
                'commemorative'        // Transaction commémorative
            ])->default('reguliere')->comment('Catégorie de transaction');

            // Informations du donateur (si anonyme)
            $table->string('nom_donateur_anonyme')->nullable()->comment('Nom si donateur non membre');
            $table->string('telephone_donateur')->nullable()->comment('Téléphone du donateur');
            $table->string('email_donateur')->nullable()->comment('Email du donateur');
            $table->boolean('est_anonyme')->default(false)->comment('Don anonyme');
            $table->boolean('est_membre')->default(true)->comment('Donateur est membre de l\'église');

            // Méthode de paiement et détails
            $table->enum('mode_paiement', [
                'especes',              // Espèces
                'mobile_money',         // Mobile Money (Orange, MTN, etc.)
                'virement',            // Virement bancaire
                'cheque',              // Chèque
                'carte_bancaire',      // Carte bancaire
                'cryptocurrency',      // Cryptomonnaie
                'nature',              // Don en nature
                'autres'               // Autres modes
            ])->default('especes')->comment('Mode de paiement');

            $table->string('reference_paiement')->nullable()->comment('Référence de paiement (numéro mobile, chèque, etc.)');
            $table->string('numero_cheque')->nullable()->comment('Numéro de chèque');
            $table->string('banque_emettrice')->nullable()->comment('Banque émettrice du chèque');
            $table->json('details_paiement')->nullable()->comment('Détails supplémentaires du paiement (JSON)');

            // Don en nature
            $table->text('description_don_nature')->nullable()->comment('Description du don en nature');
            $table->decimal('valeur_estimee', 15, 2)->nullable()->comment('Valeur estimée du don en nature');
            $table->json('inventaire_items')->nullable()->comment('Liste des items donnés (JSON)');

            // Affectation et utilisation
            $table->string('destination', 200)->nullable()->comment('Destination ou projet bénéficiaire');
            $table->uuid('projet_id')->nullable()->comment('Projet spécifique bénéficiaire');
            $table->string('ministere_beneficiaire')->nullable()->comment('Ministère bénéficiaire');
            $table->boolean('est_flechee')->default(false)->comment('Offrande fléchée pour un usage spécifique');
            $table->text('instructions_donateur')->nullable()->comment('Instructions du donateur');

            // Statut et validation
            $table->enum('statut', [
                'en_attente',          // En attente de validation
                'validee',             // Transaction validée
                'annulee',             // Transaction annulée
                'remboursee',          // Transaction remboursée
                'disputee',            // Transaction en litige
                'reportee'             // Transaction reportée
            ])->default('en_attente')->comment('Statut de la transaction');

            $table->timestamp('validee_le')->nullable()->comment('Date de validation');
            $table->text('motif_annulation')->nullable()->comment('Motif d\'annulation');
            $table->text('notes_validation')->nullable()->comment('Notes de validation');

            // Reçu et documentation
            $table->string('numero_recu', 50)->nullable()->comment('Numéro de reçu fiscal');
            $table->boolean('recu_demande')->default(false)->comment('Reçu fiscal demandé');
            $table->boolean('recu_emis')->default(false)->comment('Reçu fiscal émis');
            $table->date('date_emission_recu')->nullable()->comment('Date d\'émission du reçu');
            $table->string('lien_recu_pdf')->nullable()->comment('Lien vers le reçu PDF');

            // Périodicité et récurrence
            $table->boolean('est_recurrente')->default(false)->comment('Transaction récurrente');
            $table->enum('frequence_recurrence', [
                'hebdomadaire',
                'bimensuelle',
                'mensuelle',
                'trimestrielle',
                'semestrielle',
                'annuelle'
            ])->nullable()->comment('Fréquence de récurrence');
            $table->date('prochaine_echeance')->nullable()->comment('Prochaine échéance pour récurrence');
            $table->uuid('transaction_parent_id')->nullable()->comment('Transaction parent si récurrente');

            // Informations contextuelles
            $table->string('occasion_speciale')->nullable()->comment('Occasion spéciale (Noël, Pâques, etc.)');
            $table->text('temoignage_donateur')->nullable()->comment('Témoignage du donateur');
            $table->text('demande_priere_associee')->nullable()->comment('Demande de prière associée');
            $table->enum('niveau_urgence', [
                'normale',
                'urgente',
                'tres_urgente',
                'critique'
            ])->default('normale')->comment('Niveau d\'urgence');

            // Géolocalisation (optionnel)
            $table->decimal('latitude', 10, 8)->nullable()->comment('Latitude du lieu de don');
            $table->decimal('longitude', 11, 8)->nullable()->comment('Longitude du lieu de don');
            $table->string('lieu_collecte')->nullable()->comment('Lieu de collecte');

            // Audit et traçabilité
            $table->uuid('cree_par')->nullable()->comment('Utilisateur qui a créé la transaction');
            $table->uuid('modifie_par')->nullable()->comment('Dernier utilisateur ayant modifié');
            $table->timestamp('derniere_verification')->nullable()->comment('Dernière vérification comptable');
            $table->uuid('verifie_par')->nullable()->comment('Qui a vérifié');
            $table->text('notes_comptable')->nullable()->comment('Notes du comptable');

            // Informations fiscales
            $table->boolean('deductible_impots')->default(true)->comment('Don déductible des impôts');
            $table->decimal('taux_deduction', 5, 2)->nullable()->comment('Taux de déduction applicable');
            $table->string('code_fiscal')->nullable()->comment('Code fiscal applicable');

            // Timestamps standards
            $table->timestamps();
            $table->softDeletes();

            // Contraintes foreign key
            $table->foreign('culte_id')->references('id')->on('cultes')->onDelete('set null');
            $table->foreign('donateur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('collecteur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validateur_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('projet_id')->references('id')->on('projets')->onDelete('set null');

            $table->foreign('cree_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('modifie_par')->references('id')->on('users')->onDelete('set null');
            $table->foreign('verifie_par')->references('id')->on('users')->onDelete('set null');

            // Index pour les performances
            $table->index(['date_transaction', 'type_transaction'], 'idx_trans_date_type');
            $table->index(['culte_id', 'type_transaction'], 'idx_trans_culte_type');
            $table->index(['donateur_id', 'date_transaction'], 'idx_trans_donateur_date');
            $table->index(['statut', 'date_transaction'], 'idx_trans_statut_date');
            $table->index(['mode_paiement', 'statut'], 'idx_trans_paiement_statut');
            $table->index(['type_transaction', 'montant'], 'idx_trans_type_montant');
            $table->index('numero_transaction', 'idx_trans_numero');
            $table->index('numero_recu', 'idx_trans_recu');
            $table->index(['est_recurrente', 'prochaine_echeance'], 'idx_trans_recurrence');
            $table->index(['validee_le', 'statut'], 'idx_trans_validation');
            $table->index(['devise', 'date_transaction'], 'idx_trans_devise_date');
            $table->index(['categorie', 'type_transaction'], 'idx_trans_cat_type');
            $table->index(['projet_id', 'statut'], 'idx_trans_projet_statut');

            // Index pour les recherches de montants
            $table->index(['montant', 'type_transaction', 'date_transaction'], 'idx_trans_montant_recherche');

            // Index composé pour les rapports financiers
            $table->index([
                'date_transaction',
                'type_transaction',
                'statut',
                'montant',
                'devise'
            ], 'idx_trans_rapport_financier');
        });


        Schema::table('transactions_spirituelles', function (Blueprint $table) {
            $table->foreign('transaction_parent_id')->references('id')->on('transactions_spirituelles')->onDelete('set null');
        });

        // Commentaire sur la table
         DB::statement("COMMENT ON TABLE transactions_spirituelles IS 'Gestion complète des transactions financières de l-église (dîmes, offrandes, dons)';");

        // Vue pour les transactions validées par culte
DB::statement("
    CREATE OR REPLACE VIEW transactions_par_culte AS
    SELECT
        ts.culte_id,
        c.titre AS titre_culte,
        c.date_culte,
        c.type_culte,
        ts.type_transaction,
        COUNT(*) AS nombre_transactions,
        SUM(ts.montant) AS total_montant,
        AVG(ts.montant) AS montant_moyen,
        MIN(ts.montant) AS montant_min,
        MAX(ts.montant) AS montant_max,
        ts.devise
    FROM transactions_spirituelles ts
    INNER JOIN cultes c ON ts.culte_id = c.id
    WHERE ts.statut = 'validee'
      AND ts.deleted_at IS NULL
      AND c.deleted_at IS NULL
    GROUP BY ts.culte_id, c.titre, c.date_culte, c.type_culte, ts.type_transaction, ts.devise
    ORDER BY c.date_culte DESC
");

// Vue pour les statistiques mensuelles financières
DB::statement("
    CREATE OR REPLACE VIEW statistiques_financieres_mensuelles AS
    SELECT
        EXTRACT(YEAR FROM date_transaction)::int AS annee,
        EXTRACT(MONTH FROM date_transaction)::int AS mois,
        type_transaction,
        mode_paiement,
        devise,
        COUNT(*) AS nombre_transactions,
        SUM(montant) AS total_montant,
        AVG(montant) AS montant_moyen,
        COUNT(DISTINCT donateur_id) AS nombre_donateurs_uniques
    FROM transactions_spirituelles
    WHERE statut = 'validee'
      AND deleted_at IS NULL
    GROUP BY annee, mois, type_transaction, mode_paiement, devise
    ORDER BY annee DESC, mois DESC
");

// Vue pour les donateurs réguliers
// Vue pour les donateurs réguliers
DB::statement("
    CREATE OR REPLACE VIEW donateurs_reguliers AS
    SELECT
        ts.donateur_id,
        (u.prenom || ' ' || u.nom) AS nom_donateur,
        u.email,
        u.telephone_1,
        COUNT(*) AS nombre_dons,
        SUM(ts.montant) AS total_dons,
        AVG(ts.montant) AS don_moyen,
        MIN(ts.date_transaction) AS premier_don,
        MAX(ts.date_transaction) AS dernier_don,
        (MAX(ts.date_transaction) - MIN(ts.date_transaction)) AS jours_donateur
    FROM transactions_spirituelles ts
    INNER JOIN users u ON ts.donateur_id = u.id
    WHERE ts.statut = 'validee'
      AND ts.deleted_at IS NULL
      AND ts.donateur_id IS NOT NULL
    GROUP BY ts.donateur_id, u.prenom, u.nom, u.email, u.telephone_1
    HAVING COUNT(*) >= 3
    ORDER BY total_dons DESC
");


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des vues
        DB::statement("DROP VIEW IF EXISTS donateurs_reguliers");
        DB::statement("DROP VIEW IF EXISTS statistiques_financieres_mensuelles");
        DB::statement("DROP VIEW IF EXISTS transactions_par_culte");

        // Suppression de la table
        Schema::dropIfExists('transactions_spirituelles');
    }
};
