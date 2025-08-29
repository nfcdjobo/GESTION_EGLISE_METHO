<?php

// =================================================================
// app/Policies/SubscriptionPaymentPolicy.php

namespace App\Policies;

use App\Models\SubscriptionPayment;
use App\Models\User;

class SubscriptionPaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'tresorier', 'responsable_fimeco']);
    }

    public function view(User $user, SubscriptionPayment $payment): bool
    {
        return $user->hasRole(['admin', 'tresorier', 'responsable_fimeco']) ||
               $user->id === $payment->subscription->souscripteur_id;
    }

    public function validate(User $user, SubscriptionPayment $payment): bool
    {
        return $user->hasRole(['admin', 'tresorier']) &&
               $payment->statut === 'en_attente';
    }

    public function cancel(User $user, SubscriptionPayment $payment): bool
    {
        return $user->hasRole(['admin', 'tresorier']) ||
               ($user->id === $payment->subscription->souscripteur_id &&
                $payment->statut === 'en_attente');
    }

    public function validateMultiple(User $user): bool
    {
        return $user->hasRole(['admin', 'tresorier']);
    }
}
