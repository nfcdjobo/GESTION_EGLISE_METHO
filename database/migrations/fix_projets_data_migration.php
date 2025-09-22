<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Script pour migrer les données existantes vers le nouveau modèle corrigé
     */
    public function up(): void
    {
        echo "Début de la migration des données...\n";

        // 1. Ajouter la colonne statut_precedent si elle n'existe pas
        $this->ajouterColonneStatutPrecedent();

        // 2. Migrer budget_collecte vers la table fonds (si pas déjà fait)
        $this->migrerBudgetCollecte();

        // 3. Nettoyer les incohérences de données
        $this->nettoyerDonneesIncoherentes();

        // 4. Valider les transitions de statut
        $this->validerTransitionsStatut();

        // 5. Synchroniser les données calculées
        $this->synchroniserDonneesCalculees();

        echo "Migration des données terminée.\n";
    }

    /**
     * Ajoute la colonne statut_precedent si elle n'existe pas
     */
    private function ajouterColonneStatutPrecedent(): void
    {
        if (!Schema::hasColumn('projets', 'statut_precedent')) {
            echo "Ajout de la colonne statut_precedent...\n";

            Schema::table('projets', function (Blueprint $table) {
                $table->enum('statut_precedent', [
                    'conception',
                    'planification',
                    'recherche_financement',
                    'en_attente',
                    'en_cours',
                    'suspendu',
                    'termine',
                    'annule',
                    'archive'
                ])->nullable()->after('statut')->comment('Statut précédent (historique)');
            });
        } else {
            echo "La colonne statut_precedent existe déjà.\n";
        }
    }

    /**
     * Migre les données budget_collecte vers la relation fonds
     */
    private function migrerBudgetCollecte(): void
    {
        echo "Migration du budget collecté...\n";

        // Vérifier si la colonne budget_collecte existe encore
        if (!Schema::hasColumn('projets', 'budget_collecte')) {
            echo "La colonne budget_collecte n'existe plus, migration ignorée.\n";
            return;
        }

        $projetsAvecBudget = DB::table('projets')
            ->whereNotNull('budget_collecte')
            ->where('budget_collecte', '>', 0)
            ->get();

        $migratedCount = 0;

        foreach ($projetsAvecBudget as $projet) {
            // Vérifier si la table fonds existe
            if (!Schema::hasTable('fonds')) {
                echo "La table 'fonds' n'existe pas. Création d'une entrée factice ignorée.\n";
                continue;
            }

            // Vérifier s'il existe déjà des fonds pour ce projet
            $fondsExistants = DB::table('fonds')
                ->where('projet_id', $projet->id)
                ->sum('montant');

            // Si pas de fonds mais budget_collecte > 0, créer une entrée de migration
            if ($fondsExistants == 0 && $projet->budget_collecte > 0) {
                try {
                    DB::table('fonds')->insert([
                        'id' => \Illuminate\Support\Str::uuid(),
                        'projet_id' => $projet->id,
                        'montant' => $projet->budget_collecte,
                        'type_transaction' => 'don',
                        'statut' => 'validee',
                        'nom_donateur' => 'Migration données',
                        'mode_paiement' => 'autre',
                        'date_transaction' => $projet->created_at ?? now(),
                        'description' => 'Migration automatique du budget collecté',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $migratedCount++;
                    echo "Migré budget_collecte pour projet {$projet->code_projet}: {$projet->budget_collecte}\n";
                } catch (\Exception $e) {
                    echo "Erreur lors de la migration du projet {$projet->code_projet}: " . $e->getMessage() . "\n";
                }
            }
        }

        echo "Migration terminée: {$migratedCount} projets traités.\n";
    }

    /**
     * Nettoie les incohérences dans les données
     */
    private function nettoyerDonneesIncoherentes(): void
    {
        echo "Nettoyage des incohérences...\n";

        // 1. Projets en cours sans pourcentage
        $updated1 = DB::table('projets')
            ->where('statut', 'en_cours')
            ->where('pourcentage_completion', 0)
            ->update(['pourcentage_completion' => 5]); // Valeur minimale

        echo "Corrigé {$updated1} projets en cours sans pourcentage.\n";

        // 2. Projets terminés sans 100%
        $updated2 = DB::table('projets')
            ->where('statut', 'termine')
            ->where('pourcentage_completion', '<', 100)
            ->update(['pourcentage_completion' => 100]);

        echo "Corrigé {$updated2} projets terminés sans 100%.\n";

        // 3. Projets en recherche financement mais fermés aux dons
        $updated3 = DB::table('projets')
            ->where('statut', 'recherche_financement')
            ->where('ouvert_aux_dons', false)
            ->update(['ouvert_aux_dons' => true]);

        echo "Corrigé {$updated3} projets en recherche de financement fermés aux dons.\n";

        // 4. Projets sans budget en recherche de financement
        $updated4 = DB::table('projets')
            ->where('statut', 'recherche_financement')
            ->whereNull('budget_prevu')
            ->update(['statut' => 'conception']);

        echo "Corrigé {$updated4} projets sans budget en recherche de financement.\n";
    }

    /**
     * Valide et corrige les transitions de statut impossibles
     */
    private function validerTransitionsStatut(): void
    {
        echo "Validation des transitions de statut...\n";

        // Projets non approuvés mais en cours
        $projetsNonApprouves = DB::table('projets')
            ->whereIn('statut', ['planification', 'en_cours', 'termine'])
            ->where('necessite_approbation', true)
            ->whereNull('approuve_par')
            ->get();

        $approvedCount = 0;

        foreach ($projetsNonApprouves as $projet) {
            // Auto-approuver avec l'utilisateur système ou le créateur
            $approbateur = $projet->cree_par ?? $this->getUtilisateurSysteme();

            if ($approbateur) {
                DB::table('projets')
                    ->where('id', $projet->id)
                    ->update([
                        'approuve_par' => $approbateur,
                        'approuve_le' => $projet->created_at ?? now(),
                        'commentaires_approbation' => 'Auto-approuvé lors de la migration'
                    ]);

                $approvedCount++;
                echo "Auto-approuvé projet {$projet->code_projet}\n";
            } else {
                echo "Impossible de trouver un approbateur pour le projet {$projet->code_projet}\n";
            }
        }

        echo "Auto-approuvé {$approvedCount} projets.\n";
    }

    /**
     * Synchronise les données calculées
     */
    private function synchroniserDonneesCalculees(): void
    {
        echo "Synchronisation des données calculées...\n";

        // Recalculer les durées réelles pour les projets terminés
        $updated1 = DB::update("
            UPDATE projets
            SET duree_reelle_jours = (date_fin_reelle - date_debut)
            WHERE statut = 'termine'
            AND date_debut IS NOT NULL
            AND date_fin_reelle IS NOT NULL
            AND duree_reelle_jours IS NULL
        ");

        echo "Recalculé la durée pour {$updated1} projets terminés.\n";

        // Mettre à jour la dernière mise à jour
        $updated2 = DB::table('projets')
            ->whereNull('derniere_mise_a_jour')
            ->update(['derniere_mise_a_jour' => DB::raw('DATE(updated_at)')]);

        echo "Mis à jour la date de dernière modification pour {$updated2} projets.\n";
    }

    /**
     * Obtient l'ID d'un utilisateur système
     */
    private function getUtilisateurSysteme(): ?string
    {
        // Essayer de trouver un admin
        $admin = DB::table('users')
            ->where('role', 'admin')
            ->first();

        if ($admin) {
            return $admin->id;
        }

        // Essayer par email contenant 'admin'
        $admin = DB::table('users')
            ->where('email', 'like', '%admin%')
            ->first();

        if ($admin) {
            return $admin->id;
        }

        // Prendre le premier utilisateur
        $firstUser = DB::table('users')->first();

        return $firstUser?->id;
    }

    /**
     * Vérification post-migration
     */
    public function verifierMigration(): array
    {
        $resultats = [];

        // 1. Vérifier les projets sans incohérences
        $incoherences = DB::select("
            SELECT
                COUNT(*) as total,
                SUM(CASE WHEN statut = 'en_cours' AND pourcentage_completion = 0 THEN 1 ELSE 0 END) as en_cours_zero_pct,
                SUM(CASE WHEN statut = 'termine' AND pourcentage_completion < 100 THEN 1 ELSE 0 END) as termine_incomplet,
                SUM(CASE WHEN statut = 'recherche_financement' AND ouvert_aux_dons = false THEN 1 ELSE 0 END) as financement_ferme,
                SUM(CASE WHEN statut IN ('planification', 'en_cours') AND necessite_approbation = true AND approuve_par IS NULL THEN 1 ELSE 0 END) as non_approuve
            FROM projets
            WHERE deleted_at IS NULL
        ");

        $resultats['incoherences'] = $incoherences[0];

        // 2. Vérifier la synchronisation budget collecté (seulement si table fonds existe)
        if (Schema::hasTable('fonds')) {
            $budgetSync = DB::select("
                SELECT
                    COUNT(*) as projets_avec_budget,
                    SUM(CASE WHEN f.budget_collecte IS NULL THEN 1 ELSE 0 END) as sans_fonds
                FROM projets p
                LEFT JOIN (
                    SELECT projet_id, SUM(montant) as budget_collecte
                    FROM fonds
                    WHERE statut = 'validee' AND deleted_at IS NULL
                    GROUP BY projet_id
                ) f ON p.id = f.projet_id
                WHERE p.deleted_at IS NULL
                AND p.budget_prevu > 0
            ");

            $resultats['budget_sync'] = $budgetSync[0];
        } else {
            $resultats['budget_sync'] = 'Table fonds non trouvée';
        }

        return $resultats;
    }

    public function down(): void
    {
        echo "Rollback de la migration des données...\n";

        // Retirer la colonne statut_precedent si nécessaire
        if (Schema::hasColumn('projets', 'statut_precedent')) {
            Schema::table('projets', function (Blueprint $table) {
                $table->dropColumn('statut_precedent');
            });
            echo "Colonne statut_precedent supprimée.\n";
        }
    }
};
