<?php
namespace App\Observers;

use App\Models\UserPermission;
use App\Models\PermissionAuditLog;
use App\Jobs\RefreshPermissionCache;
use App\Notifications\PermissionGranted;
use App\Notifications\PermissionRevoked;
use Illuminate\Support\Facades\Log;

class UserPermissionObserver
{
    /**
     * Handle the UserPermission "created" event.
     */
    public function created(UserPermission $userPermission): void
    {
        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'permission_granted',
            'model_type' => 'UserPermission',
            'model_id' => $userPermission->id,
            'user_id' => $userPermission->granted_by ?? auth()->id(),
            'target_user_id' => $userPermission->user_id,
            'changes' => [
                'permission_id' => $userPermission->permission_id,
                'is_granted' => $userPermission->is_granted,
                'expires_at' => $userPermission->expires_at,
                'reason' => $userPermission->reason,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache de l'membres
        dispatch(new RefreshPermissionCache($userPermission->user_id));

        // Mettre à jour last_used_at de la permission
        if ($userPermission->permission) {
            $userPermission->permission->updateLastUsed();
        }

        Log::info("Permission accordée à un membres", [
            'user_id' => $userPermission->user_id,
            'permission_id' => $userPermission->permission_id,
            'granted_by' => $userPermission->granted_by,
            'expires_at' => $userPermission->expires_at,
        ]);
    }

    /**
     * Handle the UserPermission "updated" event.
     */
    public function updated(UserPermission $userPermission): void
    {
        $changes = $userPermission->getChanges();

        // Déterminer l'action
        $action = 'permission_updated';
        if (isset($changes['is_granted'])) {
            $action = $changes['is_granted'] ? 'permission_restored' : 'permission_revoked';
        }

        // Log de l'audit
        PermissionAuditLog::create([
            'action' => $action,
            'model_type' => 'UserPermission',
            'model_id' => $userPermission->id,
            'user_id' => auth()->id(),
            'target_user_id' => $userPermission->user_id,
            'changes' => $changes,
            'original' => $userPermission->getOriginal(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache de l'membres
        dispatch(new RefreshPermissionCache($userPermission->user_id));

        Log::info("Permission membres mise à jour", [
            'user_permission_id' => $userPermission->id,
            'action' => $action,
            'changes' => $changes,
        ]);
    }

    /**
     * Handle the UserPermission "deleted" event.
     */
    public function deleted(UserPermission $userPermission): void
    {
        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'permission_removed',
            'model_type' => 'UserPermission',
            'model_id' => $userPermission->id,
            'user_id' => auth()->id(),
            'target_user_id' => $userPermission->user_id,
            'changes' => ['deleted_at' => now()],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache de l'membres
        dispatch(new RefreshPermissionCache($userPermission->user_id));

        Log::warning("Permission retirée d'un membres", [
            'user_id' => $userPermission->user_id,
            'permission_id' => $userPermission->permission_id,
            'deleted_by' => auth()->id(),
        ]);
    }
}
