<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermissionExpiringSoon extends Notification implements ShouldQueue
{
    use Queueable;

    protected $expiringPermissions;
    protected $expiringRoles;
    protected $daysUntilExpiration;

    /**
     * Create a new notification instance.
     */
    public function __construct($expiringPermissions, $expiringRoles, $daysUntilExpiration = 7)
    {
        $this->expiringPermissions = $expiringPermissions;
        $this->expiringRoles = $expiringRoles;
        $this->daysUntilExpiration = $daysUntilExpiration;
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
            ->subject('⚠️ Permissions et rôles expirant bientôt')
            ->greeting("Bonjour {$notifiable->prenom},");

        if ($this->expiringPermissions->isNotEmpty()) {
            $message->line("Les permissions suivantes vont expirer dans les {$this->daysUntilExpiration} prochains jours :");

            foreach ($this->expiringPermissions as $userPermission) {
                $daysRemaining = $userPermission->days_remaining;
                $message->line("• {$userPermission->permission->name} - Expire dans {$daysRemaining} jour(s)");
            }
        }

        if ($this->expiringRoles->isNotEmpty()) {
            $message->line("Les rôles suivants vont expirer dans les {$this->daysUntilExpiration} prochains jours :");

            foreach ($this->expiringRoles as $role) {
                $daysRemaining = now()->diffInDays($role->pivot->expire_le, false);
                $message->line("• {$role->name} - Expire dans {$daysRemaining} jour(s)");
            }
        }

        return $message
            ->line('Veuillez contacter votre administrateur si vous avez besoin de prolonger ces accès.')
            ->action('Voir mes permissions', url('/profile/permissions'))
            ->line('Merci de votre attention!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'permissions_expiring',
            'expiring_permissions' => $this->expiringPermissions->map(function ($up) {
                return [
                    'id' => $up->id,
                    'name' => $up->permission->name,
                    'expires_at' => $up->expires_at->toIso8601String(),
                    'days_remaining' => $up->days_remaining,
                ];
            })->toArray(),
            'expiring_roles' => $this->expiringRoles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'expires_at' => $role->pivot->expire_le->toIso8601String(),
                    'days_remaining' => now()->diffInDays($role->pivot->expire_le, false),
                ];
            })->toArray(),
            'days_until_expiration' => $this->daysUntilExpiration,
        ];
    }
}

