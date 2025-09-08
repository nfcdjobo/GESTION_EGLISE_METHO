<?php

// =================================================================
// app/Observers/SubscriptionPaymentObserver.php

namespace App\Observers;

use App\Models\SubscriptionPayment;
use Illuminate\Support\Facades\Log;
use App\Notifications\PaiementValide;
use App\Models\SubscriptionPaymentLog;

class SubscriptionPaymentObserver
{
    public function created(SubscriptionPayment $payment): void
    {

    }




    public function updated(SubscriptionPayment $payment): void
    {
        try {
            $changes = $payment->getDirty();

            if (isset($changes['statut'])) {
                $ancienStatut = $payment->getOriginal('statut');
                $nouveauStatut = $payment->statut;

                if ($ancienStatut === 'en_attente' && $nouveauStatut === 'valide') {

                    // IMPORTANT: Envoyer la notification APRÈS la réponse HTTP
                    dispatch(function () use ($payment) {
                        try {
                            // Recharger le modèle depuis la base
                            $freshPayment = SubscriptionPayment::with(['subscription.souscripteur'])
                                ->find($payment->id);

                            if ($freshPayment && $freshPayment->subscription && $freshPayment->subscription->souscripteur) {
                                $freshPayment->subscription->souscripteur->notify(
                                    new PaiementValide($freshPayment)
                                );
                                Log::info("Notification envoyée pour payment {$payment->id}");
                            }
                        } catch (\Exception $e) {
                            Log::error("Erreur notification payment {$payment->id}: " . $e->getMessage());
                        }
                    })->afterResponse();

                    // NE PAS créer de log ici car la méthode valider() le fait déjà

                } elseif ($nouveauStatut === 'refuse') {

                    // Créer le log seulement si pas fait par la méthode refuser()
                    if (!$this->isCalledFromMethod('refuser')) {
                        SubscriptionPaymentLog::creerLog([
                            'subscription_id' => $payment->subscription_id,
                            'payment_id' => $payment->id,
                            'action' => 'paiement_refuse',
                            'commentaire' => "Paiement de {$payment->montant} FCFA refusé: " . $payment->commentaire,
                            'user_id' => $payment->validateur_id
                        ]);
                    }
                } elseif ($nouveauStatut === 'annule') {

                    if (!$this->isCalledFromMethod('annuler')) {
                        SubscriptionPaymentLog::creerLog([
                            'subscription_id' => $payment->subscription_id,
                            'payment_id' => $payment->id,
                            'action' => 'paiement_annule',
                            'commentaire' => "Paiement de {$payment->montant}€ annulé: " . $payment->commentaire,
                            'user_id' => $payment->validateur_id
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("Erreur dans SubscriptionPaymentObserver: " . $e->getMessage());
            // Ne pas relancer pour éviter d'interrompre le processus principal
        }
    }


    /**
     * Vérifier si on vient d'une méthode spécifique pour éviter la duplication
     */
    private function isCalledFromMethod(string $method): bool
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 15);

        foreach ($trace as $frame) {
            if (isset($frame['function']) && $frame['function'] === $method &&
                isset($frame['class']) && $frame['class'] === SubscriptionPayment::class) {
                return true;
            }
        }

        return false;
    }
}
