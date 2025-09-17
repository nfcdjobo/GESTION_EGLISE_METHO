<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\DB;

trait HasMoissonCalculs
{
    /**
     * Recalcule automatiquement les totaux lors des événements
     */
    protected static function bootHasMoissonCalculs()
    {
        static::saved(function ($model) {
            if ($model->wasChanged(['montant_solde', 'cible'])) {
                $model->recalculerTotauxLigne();
            }
        });
    }

    /**
     * Recalcule les totaux de la ligne (reste et supplément)
     */
    public function recalculerTotauxLigne(): void
    {
        if ($this->montant_solde >= $this->cible) {
            $this->reste = 0;
            $this->montant_supplementaire = $this->montant_solde - $this->cible;
        } else {
            $this->reste = $this->cible - $this->montant_solde;
            $this->montant_supplementaire = 0;
        }

        $this->saveQuietly();
    }

    /**
     * Valide les montants avant sauvegarde
     */
    public function validerMontants(): void
    {
        if ($this->cible < 0 || $this->montant_solde < 0) {
            throw new \InvalidArgumentException('Les montants ne peuvent pas être négatifs');
        }

        if ($this->cible == 0) {
            throw new \InvalidArgumentException('La cible doit être supérieure à 0');
        }
    }

    /**
     * Obtient le pourcentage de réalisation
     */
    public function getPourcentageRealisation(): float
    {
        return $this->cible > 0 ? round(($this->montant_solde * 100) / $this->cible, 2) : 0;
    }

    /**
     * Vérifie si l'objectif est atteint
     */
    public function isObjectifAtteint(): bool
    {
        return $this->montant_solde >= $this->cible;
    }

    /**
     * Obtient le statut textuel basé sur le pourcentage
     */
    public function getStatutProgression(): string
    {
        $pourcentage = $this->getPourcentageRealisation();

        if ($pourcentage >= 100) return 'Objectif atteint';
        if ($pourcentage >= 90) return 'Presque atteint';
        if ($pourcentage >= 70) return 'Bonne progression';
        if ($pourcentage >= 50) return 'En cours';
        if ($pourcentage >= 30) return 'Début';
        return 'Très faible';
    }

    /**
     * Ajoute un montant de manière sécurisée
     */
    public function ajouterMontant(float $montant, string $userId): bool
    {
        if ($montant <= 0) {
            throw new \InvalidArgumentException('Le montant doit être positif');
        }

        DB::beginTransaction();
        try {
            $ancienMontant = $this->montant_solde;
            $this->montant_solde += $montant;

            // Ajouter à l'historique si la méthode existe
            if (method_exists($this, 'ajouterEditeur')) {
                $this->ajouterEditeur($userId, 'ajout_montant', [
                    'ancien_montant' => $ancienMontant,
                    'montant_ajoute' => $montant,
                    'nouveau_montant' => $this->montant_solde
                ]);
            }

            $saved = $this->save();
            DB::commit();

            return $saved;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Soustrait un montant de manière sécurisée
     */
    public function soustraireMontant(float $montant, string $userId, string $motif = null): bool
    {
        if ($montant <= 0) {
            throw new \InvalidArgumentException('Le montant doit être positif');
        }

        if ($this->montant_solde < $montant) {
            throw new \InvalidArgumentException('Montant insuffisant pour la soustraction');
        }

        DB::beginTransaction();
        try {
            $ancienMontant = $this->montant_solde;
            $this->montant_solde -= $montant;

            if (method_exists($this, 'ajouterEditeur')) {
                $this->ajouterEditeur($userId, 'soustraction_montant', [
                    'ancien_montant' => $ancienMontant,
                    'montant_soustrait' => $montant,
                    'nouveau_montant' => $this->montant_solde,
                    'motif' => $motif
                ]);
            }

            $saved = $this->save();
            DB::commit();

            return $saved;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Définit un nouveau montant cible
     */
    public function definirNouvelleCible(float $nouvelleCible, string $userId, string $motif = null): bool
    {
        if ($nouvelleCible <= 0) {
            throw new \InvalidArgumentException('La nouvelle cible doit être positive');
        }

        $ancienneCible = $this->cible;
        $this->cible = $nouvelleCible;

        if (method_exists($this, 'ajouterEditeur')) {
            $this->ajouterEditeur($userId, 'modification_cible', [
                'ancienne_cible' => $ancienneCible,
                'nouvelle_cible' => $nouvelleCible,
                'motif' => $motif
            ]);
        }

        return $this->save();
    }

    /**
     * Obtient l'historique des modifications de montants
     */
    public function getHistoriqueModifications(): array
    {
        if (!isset($this->editeurs) || !is_array($this->editeurs)) {
            return [];
        }

        return array_filter($this->editeurs, function($edit) {
            return in_array($edit['action'] ?? '', [
                'ajout_montant',
                'soustraction_montant',
                'modification_cible',
                'paiement_partiel',
                'paiement_complet'
            ]);
        });
    }

    /**
     * Calcule la variation depuis la dernière modification
     */
    public function getVariationDepuisCreation(): array
    {
        $historique = $this->getHistoriqueModifications();

        if (empty($historique)) {
            return [
                'montant_initial' => 0,
                'montant_actuel' => $this->montant_solde,
                'variation_absolue' => $this->montant_solde,
                'variation_pourcentage' => 0
            ];
        }

        $premiere = reset($historique);
        $montantInitial = $premiere['ancien_montant'] ?? 0;

        $variationAbsolue = $this->montant_solde - $montantInitial;
        $variationPourcentage = $montantInitial > 0 ?
            round(($variationAbsolue * 100) / $montantInitial, 2) : 0;

        return [
            'montant_initial' => $montantInitial,
            'montant_actuel' => $this->montant_solde,
            'variation_absolue' => $variationAbsolue,
            'variation_pourcentage' => $variationPourcentage
        ];
    }

    /**
     * Scope pour filtrer par statut de progression
     */
    public function scopeParStatutProgression($query, string $statut)
    {
        switch (strtolower($statut)) {
            case 'objectif atteint':
            case 'atteint':
                return $query->whereRaw('montant_solde >= cible');

            case 'presque atteint':
                return $query->whereRaw('montant_solde >= (cible * 0.9) AND montant_solde < cible');

            case 'bonne progression':
                return $query->whereRaw('montant_solde >= (cible * 0.7) AND montant_solde < (cible * 0.9)');

            case 'en cours':
                return $query->whereRaw('montant_solde >= (cible * 0.5) AND montant_solde < (cible * 0.7)');

            case 'début':
            case 'debut':
                return $query->whereRaw('montant_solde >= (cible * 0.3) AND montant_solde < (cible * 0.5)');

            case 'très faible':
            case 'tres faible':
            case 'faible':
                return $query->whereRaw('montant_solde < (cible * 0.3)');

            default:
                return $query;
        }
    }

    /**
     * Scope pour les objectifs dépassés (avec supplément)
     */
    public function scopeObjectifDepasse($query)
    {
        return $query->where('montant_supplementaire', '>', 0);
    }

    /**
     * Scope pour les objectifs non atteints avec un pourcentage minimum
     */
    public function scopeObjectifNonAtteint($query, float $pourcentageMin = 0)
    {
        $query = $query->whereRaw('montant_solde < cible');

        if ($pourcentageMin > 0) {
            $query->whereRaw('(montant_solde * 100.0 / cible) >= ?', [$pourcentageMin]);
        }

        return $query;
    }

    /**
     * Calcule les statistiques de performance
     */
    public function calculerStatsPerformance(): array
    {
        return [
            'cible' => $this->cible,
            'montant_solde' => $this->montant_solde,
            'reste' => $this->reste,
            'supplement' => $this->montant_supplementaire,
            'pourcentage' => $this->getPourcentageRealisation(),
            'statut' => $this->getStatutProgression(),
            'objectif_atteint' => $this->isObjectifAtteint(),
            'variation' => $this->getVariationDepuisCreation(),
            'nb_modifications' => count($this->getHistoriqueModifications())
        ];
    }
}
