<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermissionGranted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $permission;
    protected $grantedBy;
    protected $expiresAt;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct($permission, $grantedBy = null, $expiresAt = null, $reason = null)
    {
        $this->permission = $permission;
        $this->grantedBy = $grantedBy;
        $this->expiresAt = $expiresAt;
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
            ->subject('✅ Nouvelle permission accordée')
            ->greeting("Bonjour {$notifiable->prenom},")
            ->line("La permission suivante vous a été accordée :")
            ->line("**{$this->permission->name}**");

        if ($this->permission->description) {
            $message->line("Description : {$this->permission->description}");
        }

        if ($this->grantedBy) {
            $message->line("Accordée par : {$this->grantedBy->nom_complet}");
        }

        if ($this->reason) {
            $message->line("Raison : {$this->reason}");
        }

        if ($this->expiresAt) {
            $message->line("⏱️ Cette permission expirera le : {$this->expiresAt->format('d/m/Y à H:i')}");
        } else {
            $message->line("Cette permission est permanente.");
        }

        return $message
            ->action('Voir mes permissions', url('/profile/permissions'))
            ->line('Merci de votre confiance!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'permission_granted',
            'permission' => [
                'id' => $this->permission->id,
                'name' => $this->permission->name,
                'slug' => $this->permission->slug,
                'description' => $this->permission->description,
            ],
            'granted_by' => $this->grantedBy ? [
                'id' => $this->grantedBy->id,
                'name' => $this->grantedBy->nom_complet,
            ] : null,
            'expires_at' => $this->expiresAt ? $this->expiresAt->toIso8601String() : null,
            'reason' => $this->reason,
        ];
    }
}

