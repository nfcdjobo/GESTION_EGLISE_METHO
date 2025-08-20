<?php

namespace App\Jobs;

use App\Services\PermissionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CleanupExpiredPermissions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(PermissionService $permissionService): void
    {
        try {
            $result = $permissionService->cleanupExpiredPermissions();

            Log::info("Nettoyage des permissions expirées terminé", [
                'direct_permissions' => $result['direct_permissions'],
                'roles' => $result['roles'],
                'role_permissions' => $result['role_permissions'],
            ]);

            // Si beaucoup de permissions ont expiré, envoyer une alerte
            $totalExpired = $result['direct_permissions'] + $result['roles'] + $result['role_permissions'];
            if ($totalExpired > 100) {
                Log::warning("Nombre élevé de permissions expirées", [
                    'total' => $totalExpired,
                    'details' => $result,
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Erreur dans CleanupExpiredPermissions", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
