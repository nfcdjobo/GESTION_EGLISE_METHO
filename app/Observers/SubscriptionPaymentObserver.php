<?php

// =================================================================
// app/Observers/SubscriptionPaymentObserver.php

namespace App\Observers;

use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPaymentLog;
use App\Notifications\PaiementValide;

class SubscriptionPaymentObserver
{
    public function created(SubscriptionPayment $payment): void
    {
        SubscriptionPaymentLog::creerLog([
            'subscription_id' => $payment->subscription_id,
            'payment_id' => $payment->id,
            'action' => 'paiement_ajoute',
            'commentaire' => "Paiement de {$payment->montant}€ ajouté ({$payment->type_paiement})"
        ]);
    }

    public function updated(SubscriptionPayment $payment): void
    {
        $changes = $payment->getDirty();

        if (isset($changes['statut'])) {
            $ancienStatut = $payment->getOriginal('statut');
            $nouveauStatut = $payment->statut;

            if ($ancienStatut === 'en_attente' && $nouveauStatut === 'valide') {
                // Notification au souscripteur
                $payment->subscription->souscripteur->notify(
                    new PaiementValide($payment)
                );

                SubscriptionPaymentLog::creerLog([
                    'subscription_id' => $payment->subscription_id,
                    'payment_id' => $payment->id,
                    'action' => 'paiement_valide',
                    'commentaire' => "Paiement de {$payment->montant}€ validé",
                    'user_id' => $payment->validateur_id
                ]);
            } elseif ($nouveauStatut === 'refuse') {
                SubscriptionPaymentLog::creerLog([
                    'subscription_id' => $payment->subscription_id,
                    'payment_id' => $payment->id,
                    'action' => 'paiement_refuse',
                    'commentaire' => "Paiement de {$payment->montant}€ refusé: " . $payment->commentaire,
                    'user_id' => $payment->validateur_id
                ]);
            }
        }
    }
}
