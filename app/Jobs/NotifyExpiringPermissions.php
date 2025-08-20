<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\PermissionService;
use App\Notifications\PermissionExpiringSoon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyExpiringPermissions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $daysBeforeExpiration;

    /**
     * Create a new job instance.
     */
    public function __construct($daysBeforeExpiration = 7)
    {
        $this->daysBeforeExpiration = $daysBeforeExpiration;
    }

    /**
     * Execute the job.
     */
    public function handle(PermissionService $permissionService): void
    {
        try {
            // Obtenir toutes les permissions et rôles qui expirent bientôt
            $expiring = $permissionService->getExpiringPermissions($this->daysBeforeExpiration);

            // Grouper par utilisateur
            $userNotifications = [];

            // Traiter les permissions directes
            foreach ($expiring['direct_permissions'] as $userPermission) {
                $userId = $userPermission->user_id;
                if (!isset($userNotifications[$userId])) {
                    $userNotifications[$userId] = [
                        'permissions' => collect(),
                        'roles' => collect(),
                    ];
                }
                $userNotifications[$userId]['permissions']->push($userPermission);
            }

            // Traiter les rôles
            foreach ($expiring['roles'] as $userRole) {
                $userId = $userRole->user_id;
                if (!isset($userNotifications[$userId])) {
                    $userNotifications[$userId] = [
                        'permissions' => collect(),
                        'roles' => collect(),
                    ];
                }
                $userNotifications[$userId]['roles']->push($userRole->role);
            }

            // Envoyer les notifications
            foreach ($userNotifications as $userId => $data) {
                $user = User::find($userId);
                if ($user && $user->actif) {
                    $user->notify(new PermissionExpiringSoon(
                        $data['permissions'],
                        $data['roles'],
                        $this->daysBeforeExpiration
                    ));

                    Log::info("Notification d'expiration envoyée", [
                        'user_id' => $userId,
                        'permissions_count' => $data['permissions']->count(),
                        'roles_count' => $data['roles']->count(),
                    ]);
                }
            }

            Log::info("Job NotifyExpiringPermissions terminé", [
                'users_notified' => count($userNotifications),
                'days_before_expiration' => $this->daysBeforeExpiration,
            ]);
        } catch (\Exception $e) {
            Log::error("Erreur dans NotifyExpiringPermissions", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}




