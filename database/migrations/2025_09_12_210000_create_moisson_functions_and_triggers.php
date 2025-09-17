<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fonction pour calculer les totaux d'une moisson avec gestion d'erreur
        DB::unprepared("
            CREATE OR REPLACE FUNCTION calculate_moisson_totals(moisson_uuid UUID)
            RETURNS VOID AS $$
            DECLARE
                total_passages DECIMAL(15,2) := 0;
                total_ventes DECIMAL(15,2) := 0;
                total_engagements DECIMAL(15,2) := 0;
                total_general DECIMAL(15,2) := 0;
                target_amount DECIMAL(15,2) := 0;
                remaining DECIMAL(15,2) := 0;
                supplement DECIMAL(15,2) := 0;
                moisson_exists BOOLEAN := FALSE;
            BEGIN
                -- Vérifier si la moisson existe
                SELECT EXISTS(SELECT 1 FROM moissons WHERE id = moisson_uuid AND deleted_at IS NULL) INTO moisson_exists;

                IF NOT moisson_exists THEN
                    RAISE EXCEPTION 'Moisson avec ID % n''existe pas', moisson_uuid;
                END IF;

                -- Calculer le total des passages
                SELECT COALESCE(SUM(montant_solde), 0) INTO total_passages
                FROM passage_moissons
                WHERE moisson_id = moisson_uuid AND deleted_at IS NULL AND status = true;

                -- Calculer le total des ventes
                SELECT COALESCE(SUM(montant_solde), 0) INTO total_ventes
                FROM vente_moissons
                WHERE moisson_id = moisson_uuid AND deleted_at IS NULL AND status = true;

                -- Calculer le total des engagements
                SELECT COALESCE(SUM(montant_solde), 0) INTO total_engagements
                FROM engagement_moissons
                WHERE moisson_id = moisson_uuid AND deleted_at IS NULL AND status = true;

                -- Total général
                total_general := total_passages + total_ventes + total_engagements;

                -- Récupérer la cible
                SELECT cible INTO target_amount FROM moissons WHERE id = moisson_uuid;

                -- Calculer le reste et le supplément
                IF total_general >= target_amount THEN
                    remaining := 0;
                    supplement := total_general - target_amount;
                ELSE
                    remaining := target_amount - total_general;
                    supplement := 0;
                END IF;

                -- Mettre à jour la table moissons
                UPDATE moissons
                SET
                    montant_solde = total_general,
                    reste = remaining,
                    montant_supplementaire = supplement,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = moisson_uuid;

                -- Log pour debug (optionnel)
                RAISE NOTICE 'Totaux mis à jour pour moisson %: passages=%, ventes=%, engagements=%, total=%',
                    moisson_uuid, total_passages, total_ventes, total_engagements, total_general;

            EXCEPTION
                WHEN OTHERS THEN
                    RAISE EXCEPTION 'Erreur lors du calcul des totaux pour moisson %: %', moisson_uuid, SQLERRM;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Fonction pour recalculer tous les sous-totaux d\'une ligne
        DB::unprepared("
            CREATE OR REPLACE FUNCTION calculate_line_totals()
            RETURNS TRIGGER AS $$
            DECLARE
                new_reste DECIMAL(15,2);
                new_supplement DECIMAL(15,2);
            BEGIN
                -- Pour INSERT et UPDATE
                IF TG_OP IN ('INSERT', 'UPDATE') THEN
                    -- Calculer le reste et le supplément
                    IF NEW.montant_solde >= NEW.cible THEN
                        new_reste := 0;
                        new_supplement := NEW.montant_solde - NEW.cible;
                    ELSE
                        new_reste := NEW.cible - NEW.montant_solde;
                        new_supplement := 0;
                    END IF;

                    -- Mettre à jour les valeurs calculées
                    NEW.reste := new_reste;
                    NEW.montant_supplementaire := new_supplement;
                    NEW.updated_at := CURRENT_TIMESTAMP;

                    RETURN NEW;
                END IF;

                RETURN NULL;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Fonction trigger pour mise à jour des totaux de moisson
        DB::unprepared("
            CREATE OR REPLACE FUNCTION trigger_update_moisson_totals()
            RETURNS TRIGGER AS $$
            DECLARE
                moisson_id_to_update UUID;
            BEGIN
                -- Déterminer quelle moisson mettre à jour
                IF TG_OP = 'DELETE' THEN
                    moisson_id_to_update := OLD.moisson_id;
                ELSE
                    moisson_id_to_update := NEW.moisson_id;

                    -- Si UPDATE et changement de moisson_id, mettre à jour les deux
                    IF TG_OP = 'UPDATE' AND OLD.moisson_id != NEW.moisson_id THEN
                        PERFORM calculate_moisson_totals(OLD.moisson_id);
                    END IF;
                END IF;

                -- Mettre à jour la moisson concernée
                PERFORM calculate_moisson_totals(moisson_id_to_update);

                IF TG_OP = 'DELETE' THEN
                    RETURN OLD;
                ELSE
                    RETURN NEW;
                END IF;

            EXCEPTION
                WHEN OTHERS THEN
                    RAISE WARNING 'Erreur lors de la mise à jour des totaux de moisson: %', SQLERRM;
                    -- Continue l\'opération même en cas d\'erreur
                    IF TG_OP = 'DELETE' THEN
                        RETURN OLD;
                    ELSE
                        RETURN NEW;
                    END IF;
            END;
            $$ LANGUAGE plpgsql;
        ");

        // Appliquer les triggers de calcul automatique des sous-totaux
        DB::unprepared('
            CREATE TRIGGER trigger_passage_moissons_line_totals
            BEFORE INSERT OR UPDATE ON passage_moissons
            FOR EACH ROW EXECUTE FUNCTION calculate_line_totals();
        ');

        DB::unprepared('
            CREATE TRIGGER trigger_vente_moissons_line_totals
            BEFORE INSERT OR UPDATE ON vente_moissons
            FOR EACH ROW EXECUTE FUNCTION calculate_line_totals();
        ');

        DB::unprepared('
            CREATE TRIGGER trigger_engagement_moissons_line_totals
            BEFORE INSERT OR UPDATE ON engagement_moissons
            FOR EACH ROW EXECUTE FUNCTION calculate_line_totals();
        ');

        // Appliquer les triggers de mise à jour des totaux généraux
        DB::unprepared('
            CREATE TRIGGER trigger_passage_moissons_update_totals
            AFTER INSERT OR UPDATE OR DELETE ON passage_moissons
            FOR EACH ROW EXECUTE FUNCTION trigger_update_moisson_totals();
        ');

        DB::unprepared('
            CREATE TRIGGER trigger_vente_moissons_update_totals
            AFTER INSERT OR UPDATE OR DELETE ON vente_moissons
            FOR EACH ROW EXECUTE FUNCTION trigger_update_moisson_totals();
        ');

        DB::unprepared('
            CREATE TRIGGER trigger_engagement_moissons_update_totals
            AFTER INSERT OR UPDATE OR DELETE ON engagement_moissons
            FOR EACH ROW EXECUTE FUNCTION trigger_update_moisson_totals();
        ');

        // Fonction utilitaire pour recalculer tous les totaux (maintenance)
        DB::unprepared('
            CREATE OR REPLACE FUNCTION recalculate_all_moisson_totals()
            RETURNS INTEGER AS $$
            DECLARE
                moisson_record RECORD;
                count_updated INTEGER := 0;
            BEGIN
                FOR moisson_record IN SELECT id FROM moissons WHERE deleted_at IS NULL LOOP
                    PERFORM calculate_moisson_totals(moisson_record.id);
                    count_updated := count_updated + 1;
                END LOOP;

                RETURN count_updated;
            END;
            $$ LANGUAGE plpgsql;
        ');

        // Vue matérialisée pour les statistiques de moisson (performance)
        DB::unprepared("
            CREATE MATERIALIZED VIEW moisson_statistics AS
            SELECT
                m.id,
                m.theme,
                m.date,
                m.cible,
                m.montant_solde,
                m.reste,
                m.montant_supplementaire,
                ROUND((m.montant_solde * 100.0 / NULLIF(m.cible, 0)), 2) AS pourcentage_realise,
                (SELECT COUNT(*) FROM passage_moissons pm WHERE pm.moisson_id = m.id AND pm.deleted_at IS NULL) AS nb_passages,
                (SELECT COUNT(*) FROM vente_moissons vm WHERE vm.moisson_id = m.id AND vm.deleted_at IS NULL) AS nb_ventes,
                (SELECT COUNT(*) FROM engagement_moissons em WHERE em.moisson_id = m.id AND em.deleted_at IS NULL) AS nb_engagements,
                CASE
                    WHEN m.montant_solde >= m.cible THEN 'Objectif atteint'
                    WHEN m.montant_solde >= (m.cible * 0.8) THEN 'Proche de l''objectif'
                    WHEN m.montant_solde >= (m.cible * 0.5) THEN 'En cours'
                    ELSE 'Début'
                END AS statut_progression
            FROM moissons m
            WHERE m.deleted_at IS NULL;
        ");

        // Index sur la vue matérialisée
        DB::unprepared('CREATE UNIQUE INDEX idx_moisson_statistics_id ON moisson_statistics (id);');
        DB::unprepared('CREATE INDEX idx_moisson_statistics_date ON moisson_statistics (date);');
        DB::unprepared('CREATE INDEX idx_moisson_statistics_statut ON moisson_statistics (statut_progression);');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer les fonctions
        DB::unprepared('DROP FUNCTION IF EXISTS trigger_update_moisson_totals();');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_line_totals();');
        DB::unprepared('DROP FUNCTION IF EXISTS calculate_moisson_totals(UUID);');
        DB::unprepared('DROP FUNCTION IF EXISTS recalculate_all_moisson_totals();');
    }
};
