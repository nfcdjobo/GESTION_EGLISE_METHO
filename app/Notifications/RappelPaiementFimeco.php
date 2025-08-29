<?php
// app/Notifications/RappelPaiementFimeco.php

namespace App\Notifications;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RappelPaiementFimeco extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private Subscription $subscription,
        private int $joursRestants
    ) {
        $this->queue = 'notifications';
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $montantRestant = number_format($this->subscription->reste_a_payer, 2);

        $message = (new MailMessage)
            ->subject("Rappel de paiement FIMECO - {$this->subscription->fimeco->nom}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Ceci est un rappel concernant votre souscription FIMECO pour \"{$this->subscription->fimeco->nom}\".")
            ->line("**Détails de votre souscription :**")
            ->line("- Montant souscrit : " . number_format($this->subscription->montant_souscrit, 2) . " €")
            ->line("- Montant déjà payé : " . number_format($this->subscription->montant_paye, 2) . " €")
            ->line("- **Reste à payer : {$montantRestant} €**");

        if ($this->joursRestants > 0) {
            $message->line("⏰ Échéance dans **{$this->joursRestants} jour(s)** ({$this->subscription->date_echeance->format('d/m/Y')})");
        } else {
            $message->line("⚠️ Votre paiement est **en retard** depuis le {$this->subscription->date_echeance->format('d/m/Y')}");
        }

        return $message
            ->action('Effectuer un paiement', $this->getPaymentUrl())
            ->line('Merci de votre fidélité et de votre engagement pour notre église.')
            ->salutation('Que Dieu vous bénisse,');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'rappel_paiement',
            'subscription_id' => $this->subscription->id,
            'fimeco_id' => $this->subscription->fimeco_id,
            'fimeco_nom' => $this->subscription->fimeco->nom,
            'montant_reste' => $this->subscription->reste_a_payer,
            'jours_restants' => $this->joursRestants,
            'date_echeance' => $this->subscription->date_echeance,
            'message' => $this->joursRestants > 0
                ? "Échéance dans {$this->joursRestants} jour(s)"
                : "Paiement en retard"
        ];
    }

    private function getPaymentUrl(): string
    {
        return route('subscriptions.show', $this->subscription->id);
    }
}
