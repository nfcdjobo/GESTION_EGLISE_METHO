<?php

// =================================================================
// 2025_08_29_040507_create_fimecos_table.php (CORRIGÉ - VERSION FINALE)
// =================================================================

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Activer l'extension pgcrypto si pas déjà fait
        DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto"');

        Schema::create('fimecos', function (Blueprint $table) {
            $table->uuid('id')->primary()->comment("Ici on a l'identifiant de la fimeco. Aussi dans une même période on ne peut créer une seule fimeco. Deux ou plisueur ne doivent pas être enregistrées lorsqu'il y'a une fimeco qui n'est pas encore cloturée");

            // Clé étrangère avec index
            $table->uuid('responsable_id')->nullable();
            $table->foreign('responsable_id', 'fk_fimecos_responsable')
                  ->references('id')->on('users')
                  ->onDelete('set null');

            // Champs principaux
            $table->string('nom', 100)->comment("C'est le nom de la fimeco qui doit respecter cette nommenclature FIMECO-ANNEEFIN-CANAAN-BELLEVILLE  Ici ANNEEFIN est l'année de la date de fin (la colonne fin)");
            $table->text('description')->nullable();
            $table->date('debut')->comment("Date à laquelle la fimeco commence");
            $table->date('fin')->comment("La date à laquelle la fimeco prend fin.");

            // Montants avec précision PostgreSQL
            $table->decimal('cible', 15, 2)
                  ->comment("Le montant cible: c'est un montant prévu. En un mot c'est l'objectif à atteindre et cet objectif doit forcement être atteint avant la cloture de la fimeco. Tnat que le montant_solde n'est as égale ou superieur à la cible dont ne doit pas pouvoir cloturer la fimeco");

            $table->decimal('montant_solde', 15, 2)->default(0)
                  ->comment("Le montant soldé: c'est l'ensemble de tous les paiements déjà effectué par les souscripteurs et ce montant vient de la table subscriptions. Mise à jour automatique");

            $table->decimal('reste', 15, 2)->default(0)
                  ->comment("C'est l'ensemble des montants non soldés. Mise à jour automatique");

            $table->decimal('montant_supplementaire', 15, 2)->default(0)
                  ->comment("Le montant supplémentaire rentre en jeu lorsque montant_solde est supérieur à la cible. Mise à jour automatique");

            $table->decimal('progression', 5, 2)->default(0)
                  ->comment("Progression ou évolution de montant soldé en %. Et ça peut aller au dela des 100% puisque le montant_solde peut etre superieur à la cible");

            $table->enum('statut_global', ['objectif_atteint', 'presque_atteint', 'en_cours', 'tres_faible'])
                  ->default('tres_faible')
                  ->comment("Mise à jour automatique: tres_faible <=25%, 25% < en_cours <= 75%, 75% < presque_atteint <= 99,99% et objectif_atteint >= 100%");

            $table->enum('statut', ['active', 'inactive', 'cloturee'])
                  ->default('inactive')->comment("");

            $table->timestamps();
            $table->softDeletes();
        });

        // ===== INDEX POSTGRESQL HAUTE PERFORMANCE =====

        // Index standards
        DB::statement('CREATE INDEX idx_fimecos_responsable ON fimecos(responsable_id) WHERE responsable_id IS NOT NULL');
        DB::statement('CREATE INDEX idx_fimecos_nom ON fimecos(nom) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_fimecos_statut ON fimecos(statut) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_fimecos_statut_global ON fimecos(statut_global) WHERE deleted_at IS NULL');

        // Index composés pour requêtes fréquentes
        DB::statement('CREATE INDEX idx_fimecos_dashboard ON fimecos(statut, statut_global, debut) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_fimecos_periode ON fimecos(debut, fin, statut) WHERE deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_fimecos_progression ON fimecos(progression DESC, statut) WHERE deleted_at IS NULL');

        // Index partiels pour performance maximale
        DB::statement('CREATE INDEX idx_fimecos_actifs_progression ON fimecos(progression DESC, debut) WHERE statut = \'active\' AND deleted_at IS NULL');
        DB::statement('CREATE INDEX idx_fimecos_en_cours ON fimecos(debut, fin, montant_solde) WHERE statut = \'active\' AND deleted_at IS NULL');

        // Index de recherche textuelle français
        DB::statement('CREATE INDEX idx_fimecos_nom_search ON fimecos USING gin(to_tsvector(\'french\', nom)) WHERE deleted_at IS NULL');

        // ===== CONTRAINTES POSTGRESQL =====

        DB::statement('ALTER TABLE fimecos ADD CONSTRAINT chk_fimecos_cible_positive CHECK (cible > 0)');
        DB::statement('ALTER TABLE fimecos ADD CONSTRAINT chk_fimecos_montants_positifs CHECK (montant_solde >= 0 AND reste >= 0 AND montant_supplementaire >= 0)');
        DB::statement('ALTER TABLE fimecos ADD CONSTRAINT chk_fimecos_progression_valide CHECK (progression >= 0 AND progression <= 999.99)');
        DB::statement('ALTER TABLE fimecos ADD CONSTRAINT chk_fimecos_dates_coherentes CHECK (fin >= debut)');

        // ===== FONCTIONS CENTRALES DE SYNCHRONISATION =====

        // Fonction de recalcul global d'un FIMECO depuis ses souscriptions
        DB::unprepared('
            CREATE OR REPLACE FUNCTION recalculate_fimeco_from_subscriptions(fimeco_uuid UUID)
            RETURNS VOID AS $$
            DECLARE
                total_souscrit DECIMAL(15,2) := 0;
                total_paye DECIMAL(15,2) := 0;
                fimeco_cible DECIMAL(15,2);
                nouveau_montant_solde DECIMAL(15,2);
                nouveau_reste DECIMAL(15,2);
                nouveau_supplementaire DECIMAL(15,2);
                nouvelle_progression DECIMAL(5,2);
                nouveau_statut_global TEXT;
            BEGIN
                -- Récupération de la cible du FIMECO
                SELECT cible INTO fimeco_cible FROM fimecos WHERE id = fimeco_uuid AND deleted_at IS NULL;

                IF fimeco_cible IS NULL THEN
                    RAISE EXCEPTION \'FIMECO introuvable: %\', fimeco_uuid;
                END IF;

                -- Calcul des totaux depuis les paiements validés
                SELECT
                    COALESCE(SUM(s.montant_souscrit), 0),
                    COALESCE(SUM(s.montant_paye), 0)
                INTO total_souscrit, total_paye
                FROM subscriptions s
                WHERE s.fimeco_id = fimeco_uuid
                  AND s.deleted_at IS NULL;

                -- Le montant soldé est basé sur les paiements effectivement validés
                nouveau_montant_solde := total_paye;

                -- Calculs dérivés
                IF nouveau_montant_solde > fimeco_cible THEN
                    nouveau_reste := 0;
                    nouveau_supplementaire := nouveau_montant_solde - fimeco_cible;
                ELSE
                    nouveau_reste := fimeco_cible - nouveau_montant_solde;
                    nouveau_supplementaire := 0;
                END IF;

                -- Calcul progression
                nouvelle_progression := CASE
                    WHEN fimeco_cible > 0 THEN ROUND((nouveau_montant_solde / fimeco_cible) * 100, 2)
                    ELSE 0
                END;

                -- Calcul statut global
                nouveau_statut_global := CASE
                    WHEN nouvelle_progression >= 100 THEN \'objectif_atteint\'
                    WHEN nouvelle_progression > 75 THEN \'presque_atteint\'
                    WHEN nouvelle_progression > 25 THEN \'en_cours\'
                    ELSE \'tres_faible\'
                END;

                -- Mise à jour atomique du FIMECO
                UPDATE fimecos SET
                    montant_solde = nouveau_montant_solde,
                    reste = nouveau_reste,
                    montant_supplementaire = nouveau_supplementaire,
                    progression = nouvelle_progression,
                    statut_global = nouveau_statut_global,
                    updated_at = NOW()
                WHERE id = fimeco_uuid;

                RAISE NOTICE \'FIMECO % synchronisé: soldé=%, reste=%, progression=%, statut=%\',
                    fimeco_uuid, nouveau_montant_solde, nouveau_reste, nouvelle_progression, nouveau_statut_global;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Fonction de synchronisation en cascade depuis un paiement
        DB::unprepared('
            CREATE OR REPLACE FUNCTION synchronize_payment_cascade(subscription_uuid UUID)
            RETURNS VOID AS $$
            DECLARE
                fimeco_uuid UUID;
            BEGIN
                -- Récupération du FIMECO associé
                SELECT fimeco_id INTO fimeco_uuid
                FROM subscriptions
                WHERE id = subscription_uuid AND deleted_at IS NULL;

                IF fimeco_uuid IS NULL THEN
                    RAISE WARNING \'Aucun FIMECO trouvé pour la souscription %\', subscription_uuid;
                    RETURN;
                END IF;

                -- Synchronisation du FIMECO
                PERFORM recalculate_fimeco_from_subscriptions(fimeco_uuid);

                RAISE NOTICE \'Synchronisation cascade terminée pour souscription % -> FIMECO %\',
                    subscription_uuid, fimeco_uuid;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // ===== TRIGGERS POSTGRESQL POUR AUTOMATISATION =====

        DB::unprepared('
            CREATE OR REPLACE FUNCTION validate_fimeco_coherence()
            RETURNS TRIGGER AS $$
            BEGIN
                -- Auto-calcul des champs dérivés
                IF NEW.montant_solde > NEW.cible THEN
                    NEW.reste := 0;
                    NEW.montant_supplementaire := NEW.montant_solde - NEW.cible;
                ELSE
                    NEW.reste := NEW.cible - NEW.montant_solde;
                    NEW.montant_supplementaire := 0;
                END IF;

                -- Auto-calcul progression
                NEW.progression := CASE
                    WHEN NEW.cible > 0 THEN ROUND((NEW.montant_solde / NEW.cible) * 100, 2)
                    ELSE 0
                END;

                -- Auto-calcul statut_global
                NEW.statut_global := CASE
                    WHEN NEW.progression >= 100 THEN \'objectif_atteint\'
                    WHEN NEW.progression > 75 THEN \'presque_atteint\'
                    WHEN NEW.progression > 25 THEN \'en_cours\'
                    ELSE \'tres_faible\'
                END;

                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;

            CREATE TRIGGER trigger_validate_fimeco_coherence
                BEFORE INSERT OR UPDATE ON fimecos
                FOR EACH ROW EXECUTE FUNCTION validate_fimeco_coherence();
        ');

        // ===== FONCTIONS UTILITAIRES POUR MONITORING =====

        DB::unprepared('
            CREATE OR REPLACE FUNCTION get_fimeco_synchronization_status(fimeco_uuid UUID)
            RETURNS TABLE(
                fimeco_id UUID,
                nom VARCHAR,
                cible DECIMAL(15,2),
                montant_calcule DECIMAL(15,2),
                montant_stocke DECIMAL(15,2),
                difference DECIMAL(15,2),
                est_synchrone BOOLEAN
            ) AS $$
            BEGIN
                RETURN QUERY
                SELECT
                    f.id,
                    f.nom,
                    f.cible,
                    COALESCE(SUM(s.montant_paye), 0) as montant_calcule,
                    f.montant_solde as montant_stocke,
                    (f.montant_solde - COALESCE(SUM(s.montant_paye), 0)) as difference,
                    (f.montant_solde = COALESCE(SUM(s.montant_paye), 0)) as est_synchrone
                FROM fimecos f
                LEFT JOIN subscriptions s ON f.id = s.fimeco_id AND s.deleted_at IS NULL
                WHERE f.id = fimeco_uuid AND f.deleted_at IS NULL
                GROUP BY f.id, f.nom, f.cible, f.montant_solde;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');

        DB::unprepared('
            CREATE OR REPLACE FUNCTION detect_fimeco_desynchronization()
            RETURNS TABLE(
                fimeco_id UUID,
                nom VARCHAR,
                montant_stocke DECIMAL(15,2),
                montant_reel DECIMAL(15,2),
                ecart DECIMAL(15,2)
            ) AS $$
            BEGIN
                RETURN QUERY
                SELECT
                    f.id,
                    f.nom,
                    f.montant_solde,
                    COALESCE(SUM(s.montant_paye), 0) as montant_reel,
                    (f.montant_solde - COALESCE(SUM(s.montant_paye), 0)) as ecart
                FROM fimecos f
                LEFT JOIN subscriptions s ON f.id = s.fimeco_id AND s.deleted_at IS NULL
                WHERE f.deleted_at IS NULL
                GROUP BY f.id, f.nom, f.montant_solde
                HAVING f.montant_solde != COALESCE(SUM(s.montant_paye), 0)
                ORDER BY ABS(f.montant_solde - COALESCE(SUM(s.montant_paye), 0)) DESC;
            END;
            $$ LANGUAGE plpgsql STABLE;
        ');
    }

    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS detect_fimeco_desynchronization()');
        DB::statement('DROP FUNCTION IF EXISTS get_fimeco_synchronization_status(UUID)');
        DB::statement('DROP FUNCTION IF EXISTS synchronize_payment_cascade(UUID)');
        DB::statement('DROP FUNCTION IF EXISTS recalculate_fimeco_from_subscriptions(UUID)');
        DB::statement('DROP TRIGGER IF EXISTS trigger_validate_fimeco_coherence ON fimecos');
        DB::statement('DROP FUNCTION IF EXISTS validate_fimeco_coherence()');
        Schema::dropIfExists('fimecos');
    }
};
