<?php

// =================================================================
// 2025_08_29_042717_create_subscription_payments_table.php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('subscription_id')->nullable();
            $table->foreign('subscription_id', 'fk_payments_subscription')
                  ->references('id')->on('subscriptions')->onDelete('cascade');

            $table->decimal('montant', 15, 2);
            $table->decimal('ancien_reste', 15, 2);
            $table->decimal('nouveau_reste', 15, 2);

            $table->enum('type_paiement', [
                'especes',
                'cheque',
                'virement',
                'carte',
                'mobile_money'
            ]);

            $table->string('reference_paiement', 100)->nullable();

            $table->enum('statut', [
                'en_attente',
                'valide',
                'rejete'
            ])->default('en_attente');

            $table->dateTime('date_paiement');

            $table->uuid('validateur_id')->nullable();
            $table->foreign('validateur_id', 'fk_payments_validateur')
                  ->references('id')->on('users')->onDelete('set null');

            $table->dateTime('date_validation')->nullable();
            $table->text('commentaire')->nullable();

            // Version pour contrôle de concurrence
            $table->bigInteger('subscription_version_at_payment');

            // Hash unique pour éviter les doublons
            $table->string('payment_hash', 64)->nullable();

            // Ajout d'une colonne date pour faciliter les index
            $table->date('date_paiement_only')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // ===== INDEX POSTGRESQL HAUTE PERFORMANCE =====

        // Index de base
        DB::statement('CREATE INDEX idx_payments_subscription ON subscription_payments(subscription_id) WHERE subscription_id IS NOT NULL AND deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_payments_type ON subscription_payments(type_paiement) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_payments_statut ON subscription_payments(statut) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_payments_date ON subscription_payments(date_paiement) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_payments_validateur ON subscription_payments(validateur_id) WHERE validateur_id IS NOT NULL AND deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_payments_date_validation ON subscription_payments(date_validation) WHERE date_validation IS NOT NULL AND deleted_at IS NULL');

        // Index unique sur hash pour éviter doublons
        DB::statement('CREATE UNIQUE INDEX idx_payments_hash_unique ON subscription_payments(payment_hash) WHERE payment_hash IS NOT NULL AND deleted_at IS NULL');

        // Index sur référence avec condition
        DB::statement('CREATE INDEX idx_payments_reference ON subscription_payments(reference_paiement, type_paiement) WHERE reference_paiement IS NOT NULL AND deleted_at IS NULL');

        // Index composés pour requêtes business
        DB::statement('CREATE INDEX idx_payments_subscription_calculs ON subscription_payments(subscription_id, statut, montant, date_paiement) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_payments_workflow ON subscription_payments(statut, date_paiement, validateur_id) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_payments_audit ON subscription_payments(validateur_id, date_validation, statut, montant) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_payments_rapports ON subscription_payments(date_paiement, type_paiement, statut, montant) WHERE deleted_at IS NULL');

        // Index partiels pour workflows
        DB::statement('CREATE INDEX idx_payments_en_attente ON subscription_payments(date_paiement, subscription_id, montant) WHERE statut = \'en_attente\' AND deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_payments_valides ON subscription_payments(subscription_id, montant, date_paiement) WHERE statut = \'valide\' AND deleted_at IS NULL');

        // Index pour les paiements du jour - VERSION CORRIGÉE
        // Utilise la colonne date_paiement_only au lieu de DATE(date_paiement)
        DB::statement('CREATE INDEX idx_payments_date_only ON subscription_payments(date_paiement_only, subscription_id, montant, validateur_id) WHERE deleted_at IS NULL');

        // ===== CONTRAINTES POSTGRESQL =====

        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_montant_positif CHECK (montant > 0)');
        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_restes_positifs CHECK (ancien_reste >= 0 AND nouveau_reste >= 0)');
        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_coherence_calcul CHECK (ancien_reste - nouveau_reste = montant)');
        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_version_positive CHECK (subscription_version_at_payment > 0)');
        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_reference_obligatoire CHECK (
            (type_paiement IN (\'cheque\', \'virement\', \'carte\') AND reference_paiement IS NOT NULL AND length(trim(reference_paiement)) > 0) OR
            (type_paiement IN (\'especes\', \'mobile_money\'))
        )');
        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_dates_validation CHECK (
            (statut = \'valide\' AND date_validation IS NOT NULL AND validateur_id IS NOT NULL) OR
            (statut != \'valide\')
        )');

        // ===== TRIGGERS POSTGRESQL POUR AUTOMATISATION =====

        // Trigger pour génération du hash anti-doublon et mise à jour de date_paiement_only
        DB::unprepared('
            CREATE OR REPLACE FUNCTION generate_payment_hash()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Génération du hash pour détection de doublons
                NEW.payment_hash := encode(
                    digest(
                        CONCAT(
                            COALESCE(NEW.subscription_id::text, \'\'),
                            NEW.montant::text,
                            NEW.type_paiement,
                            COALESCE(NEW.reference_paiement, \'\'),
                            date_trunc(\'minute\', NEW.date_paiement)::text
                        ), \'sha256\'
                    ), \'hex\'
                );

                -- Auto-remplissage de la colonne date_paiement_only
                NEW.date_paiement_only := DATE(NEW.date_paiement);

                -- Auto-remplissage date_validation si statut = valide
                IF NEW.statut = \'valide\' AND (OLD IS NULL OR OLD.statut != \'valide\') THEN
                    NEW.date_validation := COALESCE(NEW.date_validation, NOW());
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_generate_payment_hash
                BEFORE INSERT OR UPDATE ON subscription_payments
                FOR EACH ROW EXECUTE FUNCTION generate_payment_hash();
        ');

        // Trigger pour validation des règles métier
        DB::unprepared('
            CREATE OR REPLACE FUNCTION validate_payment_business_rules()
            RETURNS TRIGGER AS $$
            DECLARE
                subscription_data RECORD;
            BEGIN
                -- Récupération des données de souscription
                SELECT montant_souscrit, montant_paye, reste_a_payer, statut
                INTO subscription_data
                FROM subscriptions
                WHERE id = NEW.subscription_id AND deleted_at IS NULL;

                IF NOT FOUND THEN
                    RAISE EXCEPTION \'Souscription introuvable ou supprimée: %\', NEW.subscription_id;
                END IF;

                -- Validation: pas de paiement sur souscription complète
                IF subscription_data.statut = \'completement_payee\' AND TG_OP = \'INSERT\' THEN
                    RAISE EXCEPTION \'Impossible d\'\'ajouter un paiement sur une souscription déjà complètement payée\';
                END IF;

                -- Validation: le montant ne dépasse pas le reste à payer
                IF NEW.montant > subscription_data.reste_a_payer THEN
                    RAISE EXCEPTION \'Le montant du paiement (%) dépasse le reste à payer (%)\',
                        NEW.montant, subscription_data.reste_a_payer;
                END IF;

                -- Auto-calcul des restes si pas fournis
                NEW.ancien_reste := COALESCE(NEW.ancien_reste, subscription_data.reste_a_payer);
                NEW.nouveau_reste := COALESCE(NEW.nouveau_reste, NEW.ancien_reste - NEW.montant);

                -- Validation de cohérence des calculs
                IF NEW.ancien_reste - NEW.nouveau_reste != NEW.montant THEN
                    RAISE EXCEPTION \'Incohérence dans les calculs de reste: ancien=%, nouveau=%, montant=%\',
                        NEW.ancien_reste, NEW.nouveau_reste, NEW.montant;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_validate_payment_business_rules
                BEFORE INSERT OR UPDATE ON subscription_payments
                FOR EACH ROW EXECUTE FUNCTION validate_payment_business_rules();
        ');

        // Trigger pour mise à jour automatique des souscriptions
        DB::unprepared('
            CREATE OR REPLACE FUNCTION update_subscription_from_payment()
            RETURNS TRIGGER AS $$
            DECLARE
                total_paye DECIMAL(15,2);
            BEGIN
                -- Recalcul du montant payé total (paiements validés seulement)
                SELECT COALESCE(SUM(montant), 0) INTO total_paye
                FROM subscription_payments
                WHERE subscription_id = COALESCE(NEW.subscription_id, OLD.subscription_id)
                  AND statut = \'valide\'
                  AND deleted_at IS NULL;

                -- Mise à jour de la souscription
                UPDATE subscriptions SET
                    montant_paye = total_paye,
                    updated_at = NOW()
                WHERE id = COALESCE(NEW.subscription_id, OLD.subscription_id);

                RETURN COALESCE(NEW, OLD);
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_update_subscription_from_payment
                AFTER INSERT OR UPDATE OR DELETE ON subscription_payments
                FOR EACH ROW EXECUTE FUNCTION update_subscription_from_payment();
        ');
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS trigger_update_subscription_from_payment ON subscription_payments');
        DB::statement('DROP FUNCTION IF EXISTS update_subscription_from_payment()');
        DB::statement('DROP TRIGGER IF EXISTS trigger_validate_payment_business_rules ON subscription_payments');
        DB::statement('DROP FUNCTION IF EXISTS validate_payment_business_rules()');
        DB::statement('DROP TRIGGER IF EXISTS trigger_generate_payment_hash ON subscription_payments');
        DB::statement('DROP FUNCTION IF EXISTS generate_payment_hash()');
        Schema::dropIfExists('subscription_payments');
    }
};
