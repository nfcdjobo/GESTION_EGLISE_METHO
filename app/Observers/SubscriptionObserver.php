<?php

// =================================================================
// app/Observers/SubscriptionObserver.php

namespace App\Observers;

use App\Models\Subscription;
use App\Models\SubscriptionPaymentLog;

class SubscriptionObserver
{
    public function created(Subscription $subscription): void
    {
        // Log automatique de création
        SubscriptionPaymentLog::creerLog([
            'subscription_id' => $subscription->id,
            'action' => 'souscription_creee',
            'donnees_apres' => $subscription->toArray(),
            'commentaire' => 'Nouvelle souscription créée'
        ]);
    }

    public function updated(Subscription $subscription): void
    {
        // Log des changements importants
        $changes = $subscription->getDirty();

        if (isset($changes['statut']) || isset($changes['montant_paye'])) {
            SubscriptionPaymentLog::creerLog([
                'subscription_id' => $subscription->id,
                'action' => 'souscription_modifiee',
                'donnees_avant' => $subscription->getOriginal(),
                'donnees_apres' => $subscription->getAttributes(),
                'ancien_montant_paye' => $subscription->getOriginal('montant_paye'),
                'nouveau_montant_paye' => $subscription->montant_paye,
                'commentaire' => 'Souscription mise à jour automatiquement'
            ]);
        }
    }
}
