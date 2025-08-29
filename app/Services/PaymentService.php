<?php

// =================================================================
// app/Services/PaymentService.php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\ConcurrentUpdateException;
use App\Exceptions\InvalidPaymentAmountException;

class PaymentService
{
    public function ajouterPaiement(string $subscriptionId, array $paymentData): SubscriptionPayment
    {
        $lockKey = "subscription_payment_lock_{$subscriptionId}";

        return Cache::lock($lockKey, 30)->block(5, function () use ($subscriptionId, $paymentData) {
            return DB::transaction(function () use ($subscriptionId, $paymentData) {
                $subscription = Subscription::lockForUpdate()->findOrFail($subscriptionId);

                // Vérification de concurrence optimiste
                if (isset($paymentData['expected_version']) &&
                    $subscription->version !== $paymentData['expected_version']) {
                    throw new ConcurrentUpdateException();
                }

                // Validations métier
                if (!$subscription->peutRecevoirPaiement($paymentData['montant'])) {
                    throw new InvalidPaymentAmountException(
                        'Ce paiement ne peut pas être traité pour cette souscription'
                    );
                }

                if ($paymentData['montant'] > $subscription->reste_a_payer) {
                    throw new InvalidPaymentAmountException(
                        'Le montant dépasse le reste à payer'
                    );
                }

                // Création du paiement
                $payment = $subscription->payments()->create([
                    'montant' => $paymentData['montant'],
                    'ancien_reste' => $subscription->reste_a_payer,
                    'nouveau_reste' => $subscription->reste_a_payer - $paymentData['montant'],
                    'type_paiement' => $paymentData['type_paiement'],
                    'reference_paiement' => $paymentData['reference_paiement'] ?? null,
                    'date_paiement' => $paymentData['date_paiement'] ?? now(),
                    'commentaire' => $paymentData['commentaire'] ?? null,
                    'subscription_version_at_payment' => $subscription->version,
                    'statut' => $paymentData['statut'] ?? 'en_attente'
                ]);

                // Auto-validation pour certains types de paiement
                if (in_array($paymentData['type_paiement'], ['especes', 'mobile_money']) &&
                    ($paymentData['auto_validate'] ?? false)) {
                    $payment->valider('Validation automatique');
                }

                return $payment;
            });
        });
    }

    public function validerPaiement(string $paymentId, ?string $commentaire = null): bool
    {
        $payment = SubscriptionPayment::findOrFail($paymentId);
        return $payment->valider($commentaire);
    }

    public function refuserPaiement(string $paymentId, string $raison): bool
    {
        $payment = SubscriptionPayment::findOrFail($paymentId);
        return $payment->refuser($raison);
    }

    public function annulerPaiement(string $paymentId, string $raison): bool
    {
        $payment = SubscriptionPayment::findOrFail($paymentId);
        return $payment->annuler($raison);
    }

    public function obtenirPaiementsEnAttente(?string $fimecoId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = SubscriptionPayment::enAttente()
            ->with(['subscription.souscripteur', 'subscription.fimeco'])
            ->orderBy('date_paiement');

        if ($fimecoId) {
            $query->whereHas('subscription', function ($q) use ($fimecoId) {
                $q->where('fimeco_id', $fimecoId);
            });
        }

        return $query->get();
    }

    public function obtenirRapportPaiements(array $filtres = []): array
    {
        $query = SubscriptionPayment::with(['subscription.fimeco', 'subscription.souscripteur'])
            ->valide();

        // Filtres
        if (isset($filtres['fimeco_id'])) {
            $query->whereHas('subscription', function ($q) use ($filtres) {
                $q->where('fimeco_id', $filtres['fimeco_id']);
            });
        }

        if (isset($filtres['date_debut'])) {
            $query->where('date_paiement', '>=', $filtres['date_debut']);
        }

        if (isset($filtres['date_fin'])) {
            $query->where('date_paiement', '<=', $filtres['date_fin']);
        }

        if (isset($filtres['type_paiement'])) {
            $query->where('type_paiement', $filtres['type_paiement']);
        }

        $payments = $query->orderByDesc('date_paiement')->get();

        return [
            'total_paiements' => $payments->count(),
            'montant_total' => $payments->sum('montant'),
            'repartition_par_type' => $payments->groupBy('type_paiement')
                ->map(function ($group) {
                    return [
                        'nombre' => $group->count(),
                        'montant' => $group->sum('montant')
                    ];
                }),
            'paiements_par_jour' => $payments->groupBy(function ($payment) {
                return $payment->date_paiement->format('Y-m-d');
            })->map(function ($group) {
                return [
                    'nombre' => $group->count(),
                    'montant' => $group->sum('montant')
                ];
            }),
            'details' => $payments
        ];
    }

    public function traiterPaiementsEnLot(array $paymentIds, string $action, ?string $commentaire = null): array
    {
        $resultats = ['succes' => 0, 'echecs' => 0, 'erreurs' => []];

        foreach ($paymentIds as $paymentId) {
            try {
                $payment = SubscriptionPayment::findOrFail($paymentId);

                switch ($action) {
                    case 'valider':
                        $payment->valider($commentaire);
                        break;
                    case 'refuser':
                        $payment->refuser($commentaire ?: 'Refus en lot');
                        break;
                    case 'annuler':
                        $payment->annuler($commentaire ?: 'Annulation en lot');
                        break;
                    default:
                        throw new \InvalidArgumentException("Action non supportée : {$action}");
                }

                $resultats['succes']++;
            } catch (\Exception $e) {
                $resultats['echecs']++;
                $resultats['erreurs'][$paymentId] = $e->getMessage();
            }
        }

        return $resultats;
    }



    /**
     * Modifier un paiement en attente
     *
     * @param string $paymentId
     * @param array $data
     * @return SubscriptionPayment
     * @throws \Exception
     */
    public function modifierPaiement(string $paymentId, array $data): SubscriptionPayment
    {
        return DB::transaction(function () use ($paymentId, $data) {
            $payment = SubscriptionPayment::with(['subscription'])
                ->whereHas('subscription', function($query) {
                    $query->where('souscripteur_id', Auth::id());
                })
                ->findOrFail($paymentId);

            // Vérifier que le paiement peut être modifié
            if ($payment->statut !== 'en_attente') {
                throw new \InvalidArgumentException('Seuls les paiements en attente peuvent être modifiés');
            }

            // Vérifier que la FIMECO est encore active
            if ($payment->subscription->fimeco->statut !== 'active') {
                throw new \InvalidArgumentException('La FIMECO associée n\'est plus active');
            }

            // Vérifier la cohérence du montant avec le reste à payer
            $subscription = $payment->subscription;
            $ancienMontant = $payment->montant;
            $nouveauMontant = $data['montant'];

            // Calculer le nouveau reste à payer
            $nouveauReste = $payment->ancien_reste - $nouveauMontant;

            if ($nouveauReste < 0) {
                throw new \InvalidArgumentException(
                    'Le montant du paiement ne peut pas être supérieur au reste à payer'
                );
            }

            // Mettre à jour le paiement
            $payment->update([
                'montant' => $nouveauMontant,
                'nouveau_reste' => $nouveauReste,
                'type_paiement' => $data['type_paiement'],
                'reference_paiement' => $data['reference_paiement'] ?? null,
                'date_paiement' => $data['date_paiement'],
                'commentaire' => $data['commentaire'] ?? null,
            ]);

            return $payment->fresh(['subscription.fimeco', 'validateur']);
        });
    }


     /**
     * Supprimer un paiement en attente
     *
     * @param string $paymentId
     * @param string|null $raison
     * @return bool
     * @throws \Exception
     */
    public function supprimerPaiement(string $paymentId, ?string $raison = null): bool
    {
        return DB::transaction(function () use ($paymentId, $raison) {
            $payment = SubscriptionPayment::with(['subscription'])
                ->whereHas('subscription', function($query) {
                    $query->where('souscripteur_id', Auth::id());
                })
                ->findOrFail($paymentId);

            // Seuls les paiements en attente peuvent être supprimés
            if ($payment->statut !== 'en_attente') {
                throw new \InvalidArgumentException('Seuls les paiements en attente peuvent être supprimés');
            }

            // Ajouter la raison dans les commentaires si fournie
            if ($raison) {
                $payment->update([
                    'commentaire' => ($payment->commentaire ? $payment->commentaire . ' | ' : '') .
                                   'Suppression: ' . $raison
                ]);
            }

            // Soft delete du paiement
            return $payment->delete();
        });
    }

    /**
     * Récupérer l'historique des paiements d'un utilisateur
     *
     * @param string $userId
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function obtenirHistoriquePaiements(string $userId, array $filters = [])
    {
        $query = SubscriptionPayment::with(['subscription.fimeco', 'validateur'])
            ->whereHas('subscription', function($query) use ($userId) {
                $query->where('souscripteur_id', $userId);
            });

        // Appliquer les filtres
        if (isset($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (isset($filters['fimeco_id'])) {
            $query->whereHas('subscription', function($q) use ($filters) {
                $q->where('fimeco_id', $filters['fimeco_id']);
            });
        }

        if (isset($filters['type_paiement'])) {
            $query->where('type_paiement', $filters['type_paiement']);
        }

        if (isset($filters['date_debut'])) {
            $query->whereDate('date_paiement', '>=', $filters['date_debut']);
        }

        if (isset($filters['date_fin'])) {
            $query->whereDate('date_paiement', '<=', $filters['date_fin']);
        }

        return $query->orderByDesc('date_paiement')->paginate(15);
    }

    /**
     * Calculer les statistiques de paiements d'un utilisateur
     *
     * @param string $userId
     * @return array
     */
    public function calculerStatistiquesPaiements(string $userId): array
    {
        $baseQuery = SubscriptionPayment::whereHas('subscription', function($query) use ($userId) {
            $query->where('souscripteur_id', $userId);
        });

        return [
            'total_paye' => $baseQuery->clone()->where('statut', 'valide')->sum('montant'),
            'nombre_paiements_valides' => $baseQuery->clone()->where('statut', 'valide')->count(),
            'en_attente_validation' => $baseQuery->clone()->where('statut', 'en_attente')->count(),
            'montant_en_attente' => $baseQuery->clone()->where('statut', 'en_attente')->sum('montant'),
            'paiements_refuses' => $baseQuery->clone()->where('statut', 'refuse')->count(),
            'dernier_paiement' => $baseQuery->clone()
                ->where('statut', 'valide')
                ->latest('date_paiement')
                ->first()?->date_paiement,
            'repartition_par_type' => $baseQuery->clone()
                ->where('statut', 'valide')
                ->selectRaw('type_paiement, COUNT(*) as nombre, SUM(montant) as total')
                ->groupBy('type_paiement')
                ->get()
                ->keyBy('type_paiement')
        ];
    }


}
