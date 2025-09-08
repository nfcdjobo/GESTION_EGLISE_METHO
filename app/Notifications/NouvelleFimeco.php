<?php

// =================================================================
// app/Notifications/NouvelleFimeco.php

namespace App\Notifications;

use App\Models\Fimeco;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NouvelleFimeco extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private Fimeco $fimeco)
    {
        $this->queue = 'notifications';
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $objectif = number_format($this->fimeco->cible, 2);

        return (new MailMessage)
            ->subject("Nouvelle FIMECO - {$this->fimeco->nom}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Une nouvelle FIMECO vient d'être lancée dans notre église !")
            ->line("**{$this->fimeco->nom}**")
            ->when($this->fimeco->description, function ($message) {
                return $message->line($this->fimeco->description);
            })
            ->line("**Détails :**")
            ->line("- Objectif : **{$objectif} FCFA**")
            ->line("- Période : du {$this->fimeco->debut->format('d/m/Y')} au {$this->fimeco->fin->format('d/m/Y')}")
            ->action('Souscrire maintenant', route('fimecos.show', $this->fimeco->id))
            ->line('Nous comptons sur votre générosité pour atteindre cet objectif ensemble.')
            ->salutation('Que Dieu vous bénisse,');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'nouvelle_fimeco',
            'fimeco_id' => $this->fimeco->id,
            'fimeco_nom' => $this->fimeco->nom,
            'objectif' => $this->fimeco->cible,
            'date_debut' => $this->fimeco->debut,
            'date_fin' => $this->fimeco->fin,
            'message' => "Nouvelle FIMECO : {$this->fimeco->nom}"
        ];
    }
}

