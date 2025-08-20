<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermissionRevoked extends Notification implements ShouldQueue
{
    use Queueable;

    protected $permission;
    protected $revokedBy;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct($permission, $revokedBy = null, $reason = null)
    {
        $this->permission = $permission;
        $this->revokedBy = $revokedBy;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('❌ Permission révoquée')
            ->greeting("Bonjour {$notifiable->prenom},")
            ->line("La permission suivante vous a été retirée :")
            ->line("**{$this->permission->name}**");

        if ($this->revokedBy) {
            $message->line("Révoquée par : {$this->revokedBy->nom_complet}");
        }

        if ($this->reason) {
            $message->line("Raison : {$this->reason}");
        }

        return $message
            ->line("Si vous pensez qu'il s'agit d'une erreur, veuillez contacter votre administrateur.")
            ->action('Contacter l\'administrateur', url('/contact'))
            ->line('Merci de votre compréhension.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'permission_revoked',
            'permission' => [
                'id' => $this->permission->id,
                'name' => $this->permission->name,
                'slug' => $this->permission->slug,
            ],
            'revoked_by' => $this->revokedBy ? [
                'id' => $this->revokedBy->id,
                'name' => $this->revokedBy->nom_complet,
            ] : null,
            'reason' => $this->reason,
        ];
    }
}


