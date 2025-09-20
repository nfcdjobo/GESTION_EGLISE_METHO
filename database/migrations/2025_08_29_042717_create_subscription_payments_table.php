<?php

// =================================================================
// 2025_08_29_042717_create_subscription_payments_table.php (CORRIGÉ - VERSION FINALE)
// =================================================================

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

        // Index pour les paiements du jour
        DB::statement('CREATE INDEX idx_payments_date_only ON subscription_payments(date_paiement_only, subscription_id, montant, validateur_id) WHERE deleted_at IS NULL');

        // ===== CONTRAINTES POSTGRESQL =====

        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_montant_positif CHECK (montant > 0)');
        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_restes_positifs CHECK (ancien_reste >= 0 AND nouveau_reste >= 0)');
        // DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_coherence_calcul CHECK (ancien_reste - nouveau_reste = montant)');
        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_coherence_calcul CHECK (
            (montant <= ancien_reste AND ancien_reste - nouveau_reste = montant) OR
            (montant > ancien_reste AND nouveau_reste = 0 AND ancien_reste > 0)
        )');
        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_version_positive CHECK (subscription_version_at_payment > 0)');
        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_reference_obligatoire CHECK (
            (type_paiement IN (\'cheque\', \'virement\', \'carte\') AND reference_paiement IS NOT NULL AND length(trim(reference_paiement)) > 0) OR
            (type_paiement IN (\'especes\', \'mobile_money\'))
        )');
        DB::statement('ALTER TABLE subscription_payments ADD CONSTRAINT chk_payments_dates_validation CHECK (
            (statut = \'valide\' AND date_validation IS NOT NULL AND validateur_id IS NOT NULL) OR
            (statut != \'valide\')
        )');

        // ===== FONCTIONS DE SYNCHRONISATION AUTOMATIQUE =====

        // Fonction centrale de synchronisation cascade
        DB::unprepared('
            CREATE OR REPLACE FUNCTION trigger_payment_cascade_synchronization(payment_uuid UUID, operation_type TEXT)
            RETURNS VOID AS $$
            DECLARE
                payment_data RECORD;
                subscription_uuid UUID;
                fimeco_uuid UUID;
            BEGIN
                -- Récupération des informations du paiement
                SELECT subscription_id, statut INTO payment_data
                FROM subscription_payments
                WHERE id = payment_uuid;

                IF payment_data.subscription_id IS NULL THEN
                    RAISE WARNING \'Aucune souscription associée au paiement %\', payment_uuid;
                    RETURN;
                END IF;

                subscription_uuid := payment_data.subscription_id;

                -- Récupération du FIMECO associé
                SELECT fimeco_id INTO fimeco_uuid
                FROM subscriptions
                WHERE id = subscription_uuid AND deleted_at IS NULL;

                -- Synchronisation en cascade uniquement pour les paiements validés
                IF payment_data.statut = \'valide\' OR operation_type = \'DELETE\' THEN
                    -- 1. Synchronisation de la souscription
                    PERFORM recalculate_subscription_from_payments(subscription_uuid);

                    -- 2. Synchronisation du FIMECO (déjà déclenchée par le trigger de subscription)
                    -- Mais on la fait explicitement pour s\'assurer de la cohérence
                    IF fimeco_uuid IS NOT NULL THEN
                        PERFORM recalculate_fimeco_from_subscriptions(fimeco_uuid);
                    END IF;

                    RAISE NOTICE \'Synchronisation cascade payment % -> subscription % -> fimeco %\',
                        payment_uuid, subscription_uuid, fimeco_uuid;
                END IF;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Fonction de validation avancée des paiements
        DB::unprepared('
            CREATE OR REPLACE FUNCTION validate_payment_with_subscription_lock(payment_uuid UUID)
            RETURNS BOOLEAN AS $$
            DECLARE
                payment_data RECORD;
                subscription_data RECORD;
                current_version BIGINT;
            BEGIN
                -- Récupération des données du paiement
                SELECT subscription_id, montant, ancien_reste, nouveau_reste, subscription_version_at_payment, statut
                INTO payment_data
                FROM subscription_payments
                WHERE id = payment_uuid AND deleted_at IS NULL;

                IF payment_data IS NULL THEN
                    RAISE EXCEPTION \'Paiement introuvable: %\', payment_uuid;
                END IF;

                -- Verrouillage et récupération de la souscription
                SELECT montant_souscrit, montant_paye, reste_a_payer,
                       extract(epoch from updated_at)::bigint as version
                INTO subscription_data
                FROM subscriptions
                WHERE id = payment_data.subscription_id AND deleted_at IS NULL
                FOR UPDATE;

                IF subscription_data IS NULL THEN
                    RAISE EXCEPTION \'Souscription introuvable: %\', payment_data.subscription_id;
                END IF;

                current_version := subscription_data.version;

                -- Vérification de concurrence optimiste
                IF payment_data.subscription_version_at_payment != current_version AND payment_data.statut = \'en_attente\' THEN
                    RAISE NOTICE \'Version de souscription changée: attendue=%, actuelle=%. Paiement % nécessite validation manuelle\',
                        payment_data.subscription_version_at_payment, current_version, payment_uuid;
                    RETURN FALSE;
                END IF;

                -- Validation business : le montant ne doit pas dépasser le reste
                IF payment_data.montant > subscription_data.reste_a_payer THEN
                    RAISE WARNING \'Paiement % (%) dépasse le reste à payer (%) pour la souscription %\',
                        payment_uuid, payment_data.montant, subscription_data.reste_a_payer, payment_data.subscription_id;
                    RETURN FALSE;
                END IF;

                -- Validation cohérence des calculs
                IF payment_data.ancien_reste != subscription_data.reste_a_payer THEN
                    RAISE WARNING \'Ancien reste incohérent pour paiement %: attendu=%, stocké=%\',
                        payment_uuid, subscription_data.reste_a_payer, payment_data.ancien_reste;
                    RETURN FALSE;
                END IF;

                RETURN TRUE;
            END;
            $$ LANGUAGE plpgsql;
        ');

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

        // Trigger pour validation des règles métier avancées
        DB::unprepared('
            CREATE OR REPLACE FUNCTION validate_payment_business_rules()
            RETURNS TRIGGER AS $$
            DECLARE
                subscription_data RECORD;
                current_version BIGINT;
            BEGIN
                -- Récupération des données de souscription avec verrouillage
                SELECT montant_souscrit, montant_paye, reste_a_payer, statut,
                       extract(epoch from updated_at)::bigint as version
                INTO subscription_data
                FROM subscriptions
                WHERE id = NEW.subscription_id AND deleted_at IS NULL
                FOR UPDATE;

                IF NOT FOUND THEN
                    RAISE EXCEPTION \'Souscription introuvable ou supprimée: %\', NEW.subscription_id;
                END IF;

                current_version := subscription_data.version;

                -- Validation: pas de paiement sur souscription complète
                IF subscription_data.statut = \'completement_payee\' AND TG_OP = \'INSERT\' THEN
                    RAISE EXCEPTION \'Impossible d\'\'ajouter un paiement sur une souscription déjà complètement payée\';
                END IF;

                -- Auto-remplissage de la version pour contrôle de concurrence
                IF TG_OP = \'INSERT\' THEN
                    NEW.subscription_version_at_payment := current_version;
                END IF;

                -- Auto-calcul des restes si pas fournis (pour les insertions)
                IF TG_OP = \'INSERT\' THEN
                    NEW.ancien_reste := COALESCE(NEW.ancien_reste, subscription_data.reste_a_payer);
                    NEW.nouveau_reste := COALESCE(NEW.nouveau_reste, NEW.ancien_reste - NEW.montant);
                END IF;

                -- Validation de cohérence des calculs
                IF NEW.ancien_reste - NEW.nouveau_reste != NEW.montant THEN
                    RAISE EXCEPTION \'Incohérence dans les calculs de reste: ancien=%, nouveau=%, montant=%\',
                        NEW.ancien_reste, NEW.nouveau_reste, NEW.montant;
                END IF;

                -- Validation business: montant ne dépasse pas le reste
                IF NEW.montant > subscription_data.reste_a_payer THEN
                    RAISE EXCEPTION \'Le montant du paiement (%) dépasse le reste à payer (%) pour la souscription %\',
                        NEW.montant, subscription_data.reste_a_payer, NEW.subscription_id;
                END IF;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_validate_payment_business_rules
                BEFORE INSERT OR UPDATE ON subscription_payments
                FOR EACH ROW EXECUTE FUNCTION validate_payment_business_rules();
        ');

        // Trigger pour synchronisation cascade après changement de paiement
        DB::unprepared('
            CREATE OR REPLACE FUNCTION trigger_payment_cascade_sync()
            RETURNS TRIGGER AS $$
            DECLARE
                payment_uuid UUID;
                operation_type TEXT;
                should_sync BOOLEAN := FALSE;
            BEGIN
                IF TG_OP = \'DELETE\' THEN
                    payment_uuid := OLD.id;
                    operation_type := \'DELETE\';
                    should_sync := TRUE;
                ELSIF TG_OP = \'UPDATE\' THEN
                    payment_uuid := NEW.id;
                    operation_type := \'UPDATE\';
                    -- Synchroniser si le statut ou le montant change
                    should_sync := (OLD.statut != NEW.statut OR OLD.montant != NEW.montant);
                ELSE -- INSERT
                    payment_uuid := NEW.id;
                    operation_type := \'INSERT\';
                    should_sync := TRUE;
                END IF;

                -- Synchronisation cascade uniquement si nécessaire
                IF should_sync THEN
                    PERFORM trigger_payment_cascade_synchronization(payment_uuid, operation_type);
                END IF;

                IF TG_OP = \'DELETE\' THEN
                    RETURN OLD;
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_payment_cascade_sync
                AFTER INSERT OR UPDATE OR DELETE ON subscription_payments
                FOR EACH ROW EXECUTE FUNCTION trigger_payment_cascade_sync();
        ');

        // ===== FONCTIONS UTILITAIRES POUR WORKFLOW DE PAIEMENT =====

        DB::unprepared('
            CREATE OR REPLACE FUNCTION approve_payment(payment_uuid UUID, validator_uuid UUID, comment_text TEXT DEFAULT NULL)
            RETURNS BOOLEAN AS $$
            DECLARE
                payment_valid BOOLEAN;
            BEGIN
                -- Validation du paiement
                payment_valid := validate_payment_with_subscription_lock(payment_uuid);

                IF NOT payment_valid THEN
                    RAISE EXCEPTION \'Le paiement % ne peut pas être validé - échec de la validation business\', payment_uuid;
                END IF;

                -- Approbation du paiement
                UPDATE subscription_payments SET
                    statut = \'valide\',
                    validateur_id = validator_uuid,
                    date_validation = NOW(),
                    commentaire = COALESCE(comment_text, commentaire),
                    updated_at = NOW()
                WHERE id = payment_uuid
                  AND statut = \'en_attente\'
                  AND deleted_at IS NULL;

                IF NOT FOUND THEN
                    RAISE EXCEPTION \'Paiement % introuvable ou déjà traité\', payment_uuid;
                END IF;

                RAISE NOTICE \'Paiement % approuvé par validateur %\', payment_uuid, validator_uuid;
                RETURN TRUE;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE OR REPLACE FUNCTION reject_payment(payment_uuid UUID, validator_uuid UUID, rejection_reason TEXT)
            RETURNS BOOLEAN AS $$
            BEGIN
                UPDATE subscription_payments SET
                    statut = \'rejete\',
                    validateur_id = validator_uuid,
                    date_validation = NOW(),
                    commentaire = rejection_reason,
                    updated_at = NOW()
                WHERE id = payment_uuid
                  AND statut = \'en_attente\'
                  AND deleted_at IS NULL;

                IF NOT FOUND THEN
                    RAISE EXCEPTION \'Paiement % introuvable ou déjà traité\', payment_uuid;
                END IF;

                RAISE NOTICE \'Paiement % rejeté par validateur %: %\', payment_uuid, validator_uuid, rejection_reason;
                RETURN TRUE;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // ===== SUPPRESSION DES FONCTIONS EXISTANTES AVANT RECRÉATION =====

        // Supprimer les fonctions qui pourraient déjà exister avec des signatures différentes
        DB::statement('DROP FUNCTION IF EXISTS detect_payment_anomalies()');
        DB::statement('DROP FUNCTION IF EXISTS repair_payment_inconsistencies(UUID)');
        DB::statement('DROP FUNCTION IF EXISTS maintenance_payment_system()');

        // ===== FONCTIONS DE MONITORING =====

        DB::unprepared('
            CREATE OR REPLACE FUNCTION get_payment_statistics(date_from DATE DEFAULT NULL, date_to DATE DEFAULT NULL)
            RETURNS TABLE(
                statut_paiement TEXT,
                nombre_paiements BIGINT,
                montant_total DECIMAL(15,2),
                montant_moyen DECIMAL(15,2),
                type_paiement_principal TEXT
            ) AS $$
            BEGIN
                RETURN QUERY
                WITH payment_stats AS (
                    SELECT
                        sp.statut,
                        COUNT(*) as nb,
                        SUM(sp.montant) as total,
                        AVG(sp.montant) as moyenne,
                        MODE() WITHIN GROUP (ORDER BY sp.type_paiement) as type_principal
                    FROM subscription_payments sp
                    WHERE sp.deleted_at IS NULL
                      AND (date_from IS NULL OR sp.date_paiement_only >= date_from)
                      AND (date_to IS NULL OR sp.date_paiement_only <= date_to)
                    GROUP BY sp.statut
                )
                SELECT
                    ps.statut,
                    ps.nb,
                    ps.total,
                    ROUND(ps.moyenne, 2),
                    ps.type_principal
                FROM payment_stats ps
                ORDER BY ps.total DESC;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');

        DB::unprepared('
            CREATE FUNCTION detect_payment_anomalies()
            RETURNS TABLE(
                payment_id UUID,
                subscription_id UUID,
                anomalie TEXT,
                montant DECIMAL(15,2),
                date_paiement TIMESTAMP,
                action_recommandee TEXT
            ) AS $$
            BEGIN
                RETURN QUERY
                SELECT
                    sp.id,
                    sp.subscription_id,
                    CASE
                        WHEN sp.montant > s.montant_souscrit THEN \'Montant supérieur à la souscription totale\'
                        WHEN sp.ancien_reste != (s.montant_souscrit - s.montant_paye + sp.montant) AND sp.statut = \'valide\' THEN \'Ancien reste incohérent\'
                        WHEN sp.nouveau_reste < 0 THEN \'Nouveau reste négatif\'
                        WHEN sp.statut = \'valide\' AND sp.date_validation IS NULL THEN \'Paiement validé sans date de validation\'
                        WHEN sp.statut = \'valide\' AND sp.validateur_id IS NULL THEN \'Paiement validé sans validateur\'
                        WHEN sp.date_paiement > NOW() THEN \'Date de paiement future\'
                        WHEN sp.montant <= 0 THEN \'Montant invalide\'
                        ELSE \'Anomalie détectée\'
                    END,
                    sp.montant,
                    sp.date_paiement,
                    CASE
                        WHEN sp.montant > s.montant_souscrit THEN \'Vérifier le montant et ajuster si nécessaire\'
                        WHEN sp.ancien_reste != (s.montant_souscrit - s.montant_paye + sp.montant) THEN \'Recalculer avec recalculate_subscription_from_payments()\'
                        WHEN sp.nouveau_reste < 0 THEN \'Ajuster le montant du paiement\'
                        WHEN sp.statut = \'valide\' AND (sp.date_validation IS NULL OR sp.validateur_id IS NULL) THEN \'Compléter les informations de validation\'
                        WHEN sp.date_paiement > NOW() THEN \'Corriger la date de paiement\'
                        ELSE \'Analyser en détail\'
                    END
                FROM subscription_payments sp
                INNER JOIN subscriptions s ON sp.subscription_id = s.id
                WHERE sp.deleted_at IS NULL
                  AND s.deleted_at IS NULL
                  AND (
                    sp.montant > s.montant_souscrit OR
                    (sp.ancien_reste != (s.montant_souscrit - s.montant_paye + sp.montant) AND sp.statut = \'valide\') OR
                    sp.nouveau_reste < 0 OR
                    (sp.statut = \'valide\' AND sp.date_validation IS NULL) OR
                    (sp.statut = \'valide\' AND sp.validateur_id IS NULL) OR
                    sp.date_paiement > NOW() OR
                    sp.montant <= 0
                  )
                ORDER BY sp.date_paiement DESC;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');

        // ===== FONCTION DE RÉPARATION AUTOMATIQUE =====

        DB::unprepared('
            CREATE FUNCTION repair_payment_inconsistencies(payment_uuid UUID DEFAULT NULL)
            RETURNS TABLE(
                payment_id UUID,
                action_effectuee TEXT,
                resultat TEXT
            ) AS $$
            DECLARE
                payment_record RECORD;
                subscription_record RECORD;
                repair_count INTEGER := 0;
            BEGIN
                -- Si un paiement spécifique est fourni
                IF payment_uuid IS NOT NULL THEN
                    -- Réparation d\'un paiement spécifique
                    SELECT sp.*, s.montant_souscrit, s.montant_paye, s.reste_a_payer
                    INTO payment_record
                    FROM subscription_payments sp
                    INNER JOIN subscriptions s ON sp.subscription_id = s.id
                    WHERE sp.id = payment_uuid AND sp.deleted_at IS NULL AND s.deleted_at IS NULL;

                    IF payment_record IS NULL THEN
                        RETURN QUERY SELECT payment_uuid, \'Erreur\', \'Paiement introuvable\';
                        RETURN;
                    END IF;

                    -- Recalculer la souscription associée
                    PERFORM recalculate_subscription_from_payments(payment_record.subscription_id);

                    RETURN QUERY SELECT payment_uuid, \'Recalcul souscription\', \'Synchronisation effectuée\';
                    RETURN;
                END IF;

                -- Réparation globale des incohérences
                FOR payment_record IN
                    SELECT DISTINCT sp.id, sp.subscription_id
                    FROM subscription_payments sp
                    INNER JOIN subscriptions s ON sp.subscription_id = s.id
                    WHERE sp.deleted_at IS NULL
                      AND s.deleted_at IS NULL
                      AND (
                        sp.ancien_reste != (s.montant_souscrit - s.montant_paye + sp.montant) OR
                        sp.nouveau_reste < 0 OR
                        (sp.statut = \'valide\' AND sp.date_validation IS NULL)
                      )
                LOOP
                    BEGIN
                        -- Tentative de réparation
                        PERFORM recalculate_subscription_from_payments(payment_record.subscription_id);

                        RETURN QUERY SELECT
                            payment_record.id,
                            \'Réparation automatique\',
                            \'Souscription recalculée avec succès\';

                        repair_count := repair_count + 1;

                    EXCEPTION WHEN OTHERS THEN
                        RETURN QUERY SELECT
                            payment_record.id,
                            \'Échec réparation\',
                            SQLERRM;
                    END;
                END LOOP;

                -- Résumé global
                RETURN QUERY SELECT
                    NULL::UUID,
                    \'Résumé global\',
                    format(\'%s paiements traités\', repair_count);
            END;
            $$ LANGUAGE plpgsql;
        ');

        // ===== VUES MATÉRIALISÉES POUR PERFORMANCE =====

        DB::unprepared("
CREATE OR REPLACE FUNCTION create_payment_performance_views()
RETURNS void AS $$
BEGIN
    -- Vue des statistiques quotidiennes des paiements
    DROP VIEW IF EXISTS daily_payment_stats CASCADE;
    CREATE VIEW daily_payment_stats AS
    SELECT
        date_paiement_only as date_paiement,
        statut,
        type_paiement,
        COUNT(*) as nombre_paiements,
        SUM(montant) as montant_total,
        AVG(montant) as montant_moyen,
        MIN(montant) as montant_min,
        MAX(montant) as montant_max
    FROM subscription_payments
    WHERE deleted_at IS NULL
    GROUP BY date_paiement_only, statut, type_paiement
    ORDER BY date_paiement_only DESC, statut, type_paiement;

    -- Vue des paiements nécessitant une attention
    DROP VIEW IF EXISTS payments_requiring_attention CASCADE;
    CREATE VIEW payments_requiring_attention AS
    SELECT
        sp.id,
        sp.subscription_id,
        sp.montant,
        sp.statut,
        sp.date_paiement,
        sp.date_validation,
        sp.validateur_id,
        s.montant_souscrit,
        s.reste_a_payer,
        CASE
            WHEN sp.statut = 'en_attente' AND sp.date_paiement < NOW() - INTERVAL '24 hours' THEN 'En attente depuis plus de 24h'
            WHEN sp.statut = 'valide' AND sp.date_validation IS NULL THEN 'Validé sans date'
            WHEN sp.montant > s.reste_a_payer THEN 'Montant supérieur au reste dû'
            WHEN sp.nouveau_reste < 0 THEN 'Calcul erroné'
            ELSE 'À vérifier'
        END as raison_attention
    FROM subscription_payments sp
    INNER JOIN subscriptions s ON sp.subscription_id = s.id
    WHERE sp.deleted_at IS NULL
      AND s.deleted_at IS NULL
      AND (
        (sp.statut = 'en_attente' AND sp.date_paiement < NOW() - INTERVAL '24 hours') OR
        (sp.statut = 'valide' AND sp.date_validation IS NULL) OR
        sp.montant > s.reste_a_payer OR
        sp.nouveau_reste < 0
      )
    ORDER BY sp.date_paiement DESC;

    RAISE NOTICE 'Vues de performance des paiements créées avec succès';
END;
$$ LANGUAGE plpgsql;

        ");

        // -- Création des vues
        DB::statement('SELECT create_payment_performance_views()');

        // ===== FONCTION DE MAINTENANCE PERIODIQUE =====

        DB::unprepared('
            CREATE FUNCTION maintenance_payment_system()
            RETURNS TABLE(
                categorie TEXT,
                nombre_elements BIGINT,
                description TEXT
            ) AS $$
            DECLARE
                inconsistent_payments INTEGER;
                pending_old_payments INTEGER;
                duplicate_hashes INTEGER;
            BEGIN
                -- Détection des incohérences
                SELECT COUNT(*) INTO inconsistent_payments
                FROM subscription_payments sp
                INNER JOIN subscriptions s ON sp.subscription_id = s.id
                WHERE sp.deleted_at IS NULL
                  AND s.deleted_at IS NULL
                  AND sp.ancien_reste != (s.montant_souscrit - s.montant_paye + sp.montant);

                -- Paiements en attente depuis trop longtemps
                SELECT COUNT(*) INTO pending_old_payments
                FROM subscription_payments
                WHERE deleted_at IS NULL
                  AND statut = \'en_attente\'
                  AND date_paiement < NOW() - INTERVAL \'7 days\';

                -- Hash en doublon potentiels
                SELECT COUNT(*) INTO duplicate_hashes
                FROM (
                    SELECT payment_hash
                    FROM subscription_payments
                    WHERE deleted_at IS NULL AND payment_hash IS NOT NULL
                    GROUP BY payment_hash
                    HAVING COUNT(*) > 1
                ) duplicates;

                -- Retour des statistiques
                RETURN QUERY VALUES
                    (\'Incohérences détectées\', inconsistent_payments::BIGINT, \'Paiements avec calculs erronés\'),
                    (\'Paiements anciens en attente\', pending_old_payments::BIGINT, \'En attente depuis plus de 7 jours\'),
                    (\'Doublons potentiels\', duplicate_hashes::BIGINT, \'Hash identiques détectés\');

                -- Log de maintenance
                RAISE NOTICE \'Maintenance système paiements: % incohérences, % anciens en attente, % doublons potentiels\',
                    inconsistent_payments, pending_old_payments, duplicate_hashes;
            END;
            $$ LANGUAGE plpgsql;
        ');
    }

    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS maintenance_payment_system()');
        DB::statement('DROP FUNCTION IF EXISTS create_payment_performance_views()');
        DB::statement('DROP VIEW IF EXISTS payments_requiring_attention CASCADE');
        DB::statement('DROP VIEW IF EXISTS daily_payment_stats CASCADE');
        DB::statement('DROP FUNCTION IF EXISTS repair_payment_inconsistencies(UUID)');
        DB::statement('DROP FUNCTION IF EXISTS detect_payment_anomalies()');
        DB::statement('DROP FUNCTION IF EXISTS get_payment_statistics(DATE, DATE)');
        DB::statement('DROP FUNCTION IF EXISTS reject_payment(UUID, UUID, TEXT)');
        DB::statement('DROP FUNCTION IF EXISTS approve_payment(UUID, UUID, TEXT)');
        DB::statement('DROP TRIGGER IF EXISTS trigger_payment_cascade_sync ON subscription_payments');
        DB::statement('DROP FUNCTION IF EXISTS trigger_payment_cascade_sync()');
        DB::statement('DROP FUNCTION IF EXISTS validate_payment_with_subscription_lock(UUID)');
        DB::statement('DROP FUNCTION IF EXISTS trigger_payment_cascade_synchronization(UUID, TEXT)');
        DB::statement('DROP TRIGGER IF EXISTS trigger_validate_payment_business_rules ON subscription_payments');
        DB::statement('DROP FUNCTION IF EXISTS validate_payment_business_rules()');
        DB::statement('DROP TRIGGER IF EXISTS trigger_generate_payment_hash ON subscription_payments');
        DB::statement('DROP FUNCTION IF EXISTS generate_payment_hash()');
        Schema::dropIfExists('subscription_payments');
    }
};
