<?php

// =================================================================
// app/Notifications/PaiementValide.php

namespace App\Notifications;

use App\Models\SubscriptionPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaiementValide extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private SubscriptionPayment $payment)
    {
        $this->queue = 'notifications';
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $subscription = $this->payment->subscription;
        $montantPaiement = number_format($this->payment->montant, 2);
        $resteAPayer = number_format($subscription->reste_a_payer, 2);

        $message = (new MailMessage)
            ->subject("Confirmation de paiement FIMECO - {$subscription->fimeco->nom}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Nous confirmons la réception de votre paiement pour la FIMECO \"{$subscription->fimeco->nom}\".")
            ->line("**Détails du paiement :**")
            ->line("- Montant reçu : **{$montantPaiement} €**")
            ->line("- Type de paiement : " . config('fimeco.types_paiement_autorises')[$this->payment->type_paiement])
            ->line("- Date de paiement : " . $this->payment->date_paiement->format('d/m/Y à H:i'));

        if ($this->payment->reference_paiement) {
            $message->line("- Référence : {$this->payment->reference_paiement}");
        }

        if ($subscription->reste_a_payer > 0) {
            $message->line("- **Reste à payer : {$resteAPayer} €**")
                   ->action('Effectuer un autre paiement', route('subscriptions.show', $subscription->id));
        } else {
            $message->line("🎉 **Félicitations ! Votre souscription est maintenant entièrement payée.**")
                   ->action('Voir votre souscription', route('subscriptions.show', $subscription->id));
        }

        return $message
            ->line('Merci pour votre générosité et votre engagement.')
            ->salutation('Que Dieu vous bénisse,');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'paiement_valide',
            'payment_id' => $this->payment->id,
            'subscription_id' => $this->payment->subscription->id,
            'fimeco_nom' => $this->payment->subscription->fimeco->nom,
            'montant_paye' => $this->payment->montant,
            'reste_a_payer' => $this->payment->subscription->reste_a_payer,
            'est_complet' => $this->payment->subscription->reste_a_payer <= 0,
            'message' => 'Votre paiement de ' . number_format($this->payment->montant, 2) . ' € a été validé'
        ];
    }
}

