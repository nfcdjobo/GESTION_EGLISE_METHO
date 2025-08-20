<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoleRemoved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $role;
    protected $removedBy;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct($role, $removedBy = null, $reason = null)
    {
        $this->role = $role;
        $this->removedBy = $removedBy;
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
            ->subject('🔄 Rôle retiré')
            ->greeting("Bonjour {$notifiable->prenom},")
            ->line("Le rôle suivant vous a été retiré :")
            ->line("**{$this->role->name}**");

        if ($this->removedBy) {
            $message->line("Retiré par : {$this->removedBy->nom_complet}");
        }

        if ($this->reason) {
            $message->line("Raison : {$this->reason}");
        }

        return $message
            ->line("Les permissions associées à ce rôle ne sont plus disponibles.")
            ->line("Si vous avez des questions, veuillez contacter votre administrateur.")
            ->action('Contacter l\'administrateur', url('/contact'))
            ->line('Merci de votre compréhension.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'role_removed',
            'role' => [
                'id' => $this->role->id,
                'name' => $this->role->name,
                'slug' => $this->role->slug,
            ],
            'removed_by' => $this->removedBy ? [
                'id' => $this->removedBy->id,
                'name' => $this->removedBy->nom_complet,
            ] : null,
            'reason' => $this->reason,
        ];
    }
}
