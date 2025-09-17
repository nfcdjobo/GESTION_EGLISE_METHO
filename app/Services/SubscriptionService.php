<?php

// =================================================================
// app/Services/SubscriptionService.php

namespace App\Services;

use App\Models\User;
use App\Models\Fimeco;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\SubscriptionException;
use App\Exceptions\ConcurrentUpdateException;
use App\Exceptions\InvalidPaymentAmountException;

class SubscriptionService
{
    public function souscrire(array $data): Subscription
    {
        return DB::transaction(function () use ($data) {
            $fimeco = Fimeco::findOrFail($data['fimeco_id']);
            $user = User::findOrFail($data['souscripteur_id']);

            // Validations métier
            if (!$fimeco->peutEtreSouscrite()) {
                throw SubscriptionException::fimecoInactive();
            }

            // Vérifier si l'membres n'a pas déjà souscrit
            $existingSubscription = Subscription::where('fimeco_id', $data['fimeco_id'])
                ->where('souscripteur_id', $data['souscripteur_id'])
                ->first();

            if ($existingSubscription) {
                throw SubscriptionException::alreadyExists();
            }

            // Montant minimum (configurable)
            $montantMinimum = config('fimeco.montant_minimum_souscription', 10);
            if ($data['montant_souscrit'] < $montantMinimum) {
                throw SubscriptionException::amountTooLow($montantMinimum);
            }

            $subscription = Subscription::create([
                'souscripteur_id' => $data['souscripteur_id'],
                'fimeco_id' => $data['fimeco_id'],
                'montant_souscrit' => $data['montant_souscrit'],
                'date_souscription' => $data['date_souscription'] ?? now(),
                'date_echeance' => $data['date_echeance'] ?? null
            ]);
// dd($subscription);
            return $subscription;
        });
    }

    public function modifierMontantSouscription(
        string $subscriptionId,
        float $nouveauMontant,
        ?int $versionAttendue = null
    ): Subscription {
        return DB::transaction(function () use ($subscriptionId, $nouveauMontant, $versionAttendue) {
            $subscription = Subscription::lockForUpdate()->findOrFail($subscriptionId);

            // Vérification de concurrence optimiste
            if ($versionAttendue && $subscription->version !== $versionAttendue) {
                throw new ConcurrentUpdateException();
            }

            // Validation métier
            if ($subscription->montant_paye > $nouveauMontant) {
                throw new InvalidPaymentAmountException(
                    'Le nouveau montant ne peut pas être inférieur au montant déjà payé'
                );
            }

            $ancienMontant = $subscription->montant_souscrit;

            $subscription->montant_souscrit = $nouveauMontant;
            $subscription->reste_a_payer = $nouveauMontant - $subscription->montant_paye;
            $subscription->version++;
            $subscription->save();

            // Log de la modification
            $subscription->logs()->create([
                'action' => 'souscription_modifiee',
                'donnees_avant' => ['montant_souscrit' => $ancienMontant],
                'donnees_apres' => ['montant_souscrit' => $nouveauMontant],
                'commentaire' => "Montant modifié de {$ancienMontant} à {$nouveauMontant}",
                'user_id' => auth()->id()
            ]);

            return $subscription;
        });
    }

    public function obtenirSouscriptionsMembres(string $userId, ?string $fimecoId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Subscription::where('souscripteur_id', $userId)
            ->with(['fimeco', 'paymentsValides']);

        if ($fimecoId) {
            $query->where('fimeco_id', $fimecoId);
        }

        return $query->orderByDesc('date_souscription')->get();
    }

    public function obtenirSouscriptionsEnRetard(?int $joursRetard = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Subscription::enRetard()
            ->with(['souscripteur', 'fimeco']);

        if ($joursRetard) {
            $dateLimit = now()->subDays($joursRetard);
            $query->where('date_echeance', '<', $dateLimit);
        }

        return $query->orderBy('date_echeance')->get();
    }

    public function calculerStatistiquesMembres(string $userId): array
    {
        $subscriptions = $this->obtenirSouscriptionsMembres($userId);

        return [
            'total_subscriptions' => $subscriptions->count(),
            'montant_total_souscrit' => $subscriptions->sum('montant_souscrit'),
            'montant_total_paye' => $subscriptions->sum('montant_paye'),
            'reste_total_a_payer' => $subscriptions->sum('reste_a_payer'),
            'subscriptions_completes' => $subscriptions->where('statut', 'completement_payee')->count(),
            'subscriptions_en_cours' => $subscriptions->whereIn('statut', ['active', 'partiellement_payee'])->count(),
            'subscriptions_en_retard' => $subscriptions->where('est_en_retard', true)->count()
        ];
    }








    /**
     * Annuler une souscription
     *
     * @param string $subscriptionId
     * @param string $raison
     * @return Subscription
     * @throws \Exception
     */
    public function annulerSouscription(string $subscriptionId, string $raison): Subscription
    {
        return DB::transaction(function () use ($subscriptionId, $raison) {
            $subscription = Subscription::with(['payments'])
                ->where('souscripteur_id', auth()->id())
                ->findOrFail($subscriptionId);

            // Vérifier que la souscription peut être annulée
            if ($subscription->statut === 'annulee') {
                throw new \InvalidArgumentException('Cette souscription est déjà annulée');
            }

            if ($subscription->statut === 'completement_payee') {
                throw new \InvalidArgumentException('Une souscription entièrement payée ne peut pas être annulée');
            }

            // Vérifier qu'il n'y a pas de paiements validés
            $paiementsValides = $subscription->payments()->where('statut', 'valide')->count();
            if ($paiementsValides > 0) {
                throw new \InvalidArgumentException(
                    'Impossible d\'annuler une souscription avec des paiements validés'
                );
            }

            // Annuler tous les paiements en attente
            $subscription->payments()
                ->where('statut', 'en_attente')
                ->update([
                    'statut' => 'annule',
                    'commentaire' => DB::raw("CONCAT(COALESCE(commentaire, ''), ' | Annulation souscription: {$raison}')")
                ]);

            // Mettre à jour le statut de la souscription
            $subscription->update([
                'statut' => 'annulee'
            ]);

            return $subscription->fresh(['fimeco', 'payments']);
        });
    }

    /**
     * Récupérer les FIMECO disponibles pour souscription pour un membres
     *
     * @param string $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenirFimecosDisponibles(string $userId)
    {
        return Fimeco::enCours()
            ->whereDoesntHave('subscriptions', function($query) use ($userId) {
                $query->where('souscripteur_id', $userId);
            })
            ->orderBy('nom')
            ->get();
    }

    /**
     * Vérifier si un membres peut souscrire à une FIMECO
     *
     * @param string $userId
     * @param string $fimecoId
     * @return bool
     */
    public function peutSouscrire(string $userId, string $fimecoId): bool
    {
        $fimeco = Fimeco::find($fimecoId);

        if (!$fimeco || $fimeco->statut !== 'active') {
            return false;
        }

        // Vérifier que l'membres n'a pas déjà souscrit
        $souscriptionExistante = Subscription::where('souscripteur_id', $userId)
            ->where('fimeco_id', $fimecoId)
            ->whereNotIn('statut', ['annulee'])
            ->exists();

        return !$souscriptionExistante;
    }

    /**
     * Suspendre temporairement une souscription
     *
     * @param string $subscriptionId
     * @param string $raison
     * @return Subscription
     * @throws \Exception
     */
    public function suspendreSouscription(string $subscriptionId, string $raison): Subscription
    {
        return DB::transaction(function () use ($subscriptionId, $raison) {
            $subscription = Subscription::where('souscripteur_id', auth()->id())
                ->findOrFail($subscriptionId);

            if (!in_array($subscription->statut, ['active', 'partiellement_payee'])) {
                throw new \InvalidArgumentException('Cette souscription ne peut pas être suspendue');
            }

            $subscription->update([
                'statut' => 'suspendue'
            ]);

            // Log de la suspension
            Log::info("Souscription suspendue", [
                'subscription_id' => $subscription->id,
                'raison' => $raison,
                'user_id' => auth()->id()
            ]);

            return $subscription->fresh();
        });
    }
}

