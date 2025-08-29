<?php

// =================================================================
// app/Services/FimecoService.php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Fimeco;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\SubscriptionException;

class FimecoService
{
    public function creerFimeco(array $data): Fimeco
    {
        return DB::transaction(function () use ($data) {
            $fimeco = Fimeco::create([
                'responsable_id' => $data['responsable_id'],
                'nom' => $data['nom'],
                'description' => $data['description'] ?? null,
                'debut' => $data['debut'],
                'fin' => $data['fin'],
                'cible' => $data['cible'],
                'statut' => 'active'
            ]);

            return $fimeco;
        });
    }

    public function obtenirFimecoActive(): ?Fimeco
    {
        return Fimeco::enCours()->first();
    }

    public function obtenirStatistiquesFimeco(string $fimecoId): array
    {
        $fimeco = Fimeco::with(['subscriptions.souscripteur'])->findOrFail($fimecoId);

        $stats = $fimeco->calculerStatistiques();

        // Statistiques détaillées
        $subscriptions = $fimeco->subscriptions;

        $stats['repartition_par_statut'] = [
            'active' => $subscriptions->where('statut', 'active')->count(),
            'partiellement_payee' => $subscriptions->where('statut', 'partiellement_payee')->count(),
            'completement_payee' => $subscriptions->where('statut', 'completement_payee')->count(),
            'annulee' => $subscriptions->where('statut', 'annulee')->count()
        ];

        $stats['montants_par_statut'] = [
            'total_souscrit' => $subscriptions->sum('montant_souscrit'),
            'total_paye' => $subscriptions->sum('montant_paye'),
            'total_reste' => $subscriptions->sum('reste_a_payer')
        ];

        $stats['top_contributeurs'] = $subscriptions
            ->sortByDesc('montant_paye')
            ->take(10)
            ->map(function ($subscription) {
                return [
                    'nom' => $subscription->souscripteur->name,
                    'montant_souscrit' => $subscription->montant_souscrit,
                    'montant_paye' => $subscription->montant_paye,
                    'pourcentage' => $subscription->pourcentage_paye
                ];
            });

        return $stats;
    }

    public function cloturerFimeco(string $fimecoId, ?string $commentaire = null): bool
    {
        return DB::transaction(function () use ($fimecoId, $commentaire) {
            $fimeco = Fimeco::findOrFail($fimecoId);

            // Vérifier les souscriptions en attente
            $subscriptionsEnAttente = $fimeco->subscriptions()
                ->whereIn('statut', ['active', 'partiellement_payee'])
                ->count();

            if ($subscriptionsEnAttente > 0) {
                throw new \InvalidArgumentException(
                    "Impossible de clôturer la FIMECO : {$subscriptionsEnAttente} souscriptions sont encore en attente de paiement."
                );
            }

            return $fimeco->cloturer();
        });
    }




    /**
     * Modifier une FIMECO existante
     *
     * @param string $fimecoId
     * @param array $data
     * @return Fimeco
     * @throws \Exception
     */
    public function modifierFimeco(string $fimecoId, array $data): Fimeco
    {
        return DB::transaction(function () use ($fimecoId, $data) {
            $fimeco = Fimeco::findOrFail($fimecoId);

            // Vérifier que la FIMECO peut être modifiée
            if ($fimeco->statut === 'cloturee') {
                throw new \InvalidArgumentException('Une FIMECO clôturée ne peut pas être modifiée');
            }

            // Si on modifie la date de fin, vérifier qu'elle est cohérente
            if (isset($data['fin'])) {
                if ($data['fin'] < now()->toDateString()) {
                    throw new \InvalidArgumentException('La date de fin ne peut pas être dans le passé');
                }

                if (isset($data['debut']) && $data['fin'] < $data['debut']) {
                    throw new \InvalidArgumentException('La date de fin doit être postérieure à la date de début');
                }
            }

            // Si on change le responsable, vérifier qu'il est valide
            if (isset($data['responsable_id']) && $data['responsable_id']) {
                $responsable = User::findOrFail($data['responsable_id']);
                if (!$responsable->hasRole(['admin', 'responsable_fimeco'])) {
                    throw new \InvalidArgumentException('L\'utilisateur sélectionné ne peut pas être responsable de FIMECO');
                }
            }

            $fimeco->update($data);

            return $fimeco->fresh(['responsable']);
        });
    }

    /**
     * Réactiver une FIMECO inactive
     *
     * @param string $fimecoId
     * @return Fimeco
     * @throws \Exception
     */
    public function reactiverFimeco(string $fimecoId): Fimeco
    {
        return DB::transaction(function () use ($fimecoId) {
            $fimeco = Fimeco::findOrFail($fimecoId);

            if ($fimeco->statut === 'cloturee') {
                throw new \InvalidArgumentException('Une FIMECO clôturée ne peut pas être réactivée');
            }

            if ($fimeco->statut === 'active') {
                throw new \InvalidArgumentException('Cette FIMECO est déjà active');
            }

            // Vérifier qu'il n'y a pas déjà une FIMECO active
            $fimecoActive = $this->obtenirFimecoActive();
            if ($fimecoActive && $fimecoActive->id !== $fimeco->id) {
                throw new \InvalidArgumentException('Une autre FIMECO est déjà active');
            }

            $fimeco->update(['statut' => 'active']);

            return $fimeco->fresh();
        });
    }

    /**
     * Désactiver temporairement une FIMECO
     *
     * @param string $fimecoId
     * @param string|null $commentaire
     * @return Fimeco
     * @throws \Exception
     */
    public function desactiverFimeco(string $fimecoId, ?string $commentaire = null): Fimeco
    {
        return DB::transaction(function () use ($fimecoId, $commentaire) {
            $fimeco = Fimeco::findOrFail($fimecoId);

            if ($fimeco->statut !== 'active') {
                throw new \InvalidArgumentException('Seule une FIMECO active peut être désactivée');
            }

            $fimeco->update([
                'statut' => 'inactive'
            ]);

            // Enregistrer le commentaire si fourni (vous pourriez avoir une table d'historique)
            if ($commentaire) {
                // Log de l'action (à adapter selon votre système de logs)
                Log::info("FIMECO {$fimeco->nom} désactivée", [
                    'fimeco_id' => $fimeco->id,
                    'commentaire' => $commentaire,
                    'user_id' => auth()->id()
                ]);
            }

            return $fimeco->fresh();
        });
    }

    /**
     * Récupérer les responsables éligibles pour les FIMECO
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenirResponsablesEligibles()
    {
        return User::where('active', true)
            ->whereHas('roles', function($query) {
                $query->whereIn('name', ['admin', 'responsable_fimeco']);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }
}
