<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use App\Models\SubscriptionPayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
    $montantPaiement = number_format($this->payment->montant, 2, ',', ' ');
    $resteAPayer = number_format($subscription->reste_a_payer, 2, ',', ' ');

    // Convertir le logo en base64
    $logoPath = public_path('images/logo/image.png');
    $logoBase64 = null;

    if (file_exists($logoPath)) {
        try {
            $imageSize = getimagesize($logoPath);
            $mimeType = $imageSize['mime'] ?? 'image/png';
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($logoData);
        } catch (\Exception $e) {
            Log::warning('Impossible de convertir le logo en base64', [
                'error' => $e->getMessage(),
                'logo_path' => $logoPath
            ]);
        }
    }

    return (new MailMessage)
        ->subject("Confirmation de paiement FIMECO - {$subscription->fimeco->nom}")
        ->view('emails.payment-confirmation', [
            'subscription' => $subscription,
            'payment' => $this->payment,
            'notifiable' => $notifiable,
            'montantPaiement' => $montantPaiement,
            'resteAPayer' => $resteAPayer,
            'logoBase64' => $logoBase64,
            'logoUrl' => asset('images/logo/image.png'), // Fallback
        ]);
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
            'message' => 'Votre paiement de ' . number_format($this->payment->montant, 2) . ' FCFA a été validé'
        ];
    }
}

