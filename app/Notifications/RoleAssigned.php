<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoleAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $role;
    protected $assignedBy;
    protected $expiresAt;

    /**
     * Create a new notification instance.
     */
    public function __construct($role, $assignedBy = null, $expiresAt = null)
    {
        $this->role = $role;
        $this->assignedBy = $assignedBy;
        $this->expiresAt = $expiresAt;
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
            ->subject('🎉 Nouveau rôle attribué')
            ->greeting("Bonjour {$notifiable->prenom},")
            ->line("Le rôle suivant vous a été attribué :")
            ->line("**{$this->role->name}**");

        if ($this->role->description) {
            $message->line("Description : {$this->role->description}");
        }

        $message->line("Niveau hiérarchique : {$this->role->level}");

        if ($this->assignedBy) {
            $message->line("Attribué par : {$this->assignedBy->nom_complet}");
        }

        if ($this->expiresAt) {
            $message->line("⏱️ Ce rôle expirera le : {$this->expiresAt->format('d/m/Y à H:i')}");
        } else {
            $message->line("Ce rôle est permanent.");
        }

        // Lister quelques permissions importantes du rôle
        $permissions = $this->role->permissions()->take(5)->get();
        if ($permissions->isNotEmpty()) {
            $message->line("Ce rôle vous donne accès aux permissions suivantes (entre autres) :");
            foreach ($permissions as $permission) {
                $message->line("• {$permission->name}");
            }
        }

        return $message
            ->action('Voir mon profil', url('/profile'))
            ->line('Félicitations pour votre nouvelle responsabilité!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'type' => 'role_assigned',
            'role' => [
                'id' => $this->role->id,
                'name' => $this->role->name,
                'slug' => $this->role->slug,
                'level' => $this->role->level,
                'description' => $this->role->description,
            ],
            'assigned_by' => $this->assignedBy ? [
                'id' => $this->assignedBy->id,
                'name' => $this->assignedBy->nom_complet,
            ] : null,
            'expires_at' => $this->expiresAt ? $this->expiresAt->toIso8601String() : null,
        ];
    }
}
