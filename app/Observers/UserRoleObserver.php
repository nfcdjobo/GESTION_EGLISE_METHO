<?php
namespace App\Observers;

use App\Models\UserRole;
use App\Models\PermissionAuditLog;
use App\Jobs\RefreshPermissionCache;
use Illuminate\Support\Facades\Log;

class UserRoleObserver
{
    /**
     * Handle the UserRole "created" event.
     */
    public function created(UserRole $userRole): void
    {
        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'role_assigned',
            'model_type' => 'UserRole',
            'model_id' => "{$userRole->user_id}_{$userRole->role_id}",
            'user_id' => $userRole->attribue_par ?? auth()->id(),
            'target_user_id' => $userRole->user_id,
            'changes' => [
                'role_id' => $userRole->role_id,
                'expire_le' => $userRole->expire_le,
                'actif' => $userRole->actif,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache de l'utilisateur
        dispatch(new RefreshPermissionCache($userRole->user_id));

        Log::info("Rôle attribué à un utilisateur", [
            'user_id' => $userRole->user_id,
            'role_id' => $userRole->role_id,
            'assigned_by' => $userRole->attribue_par,
            'expires_at' => $userRole->expire_le,
        ]);
    }

    /**
     * Handle the UserRole "updated" event.
     */
    public function updated(UserRole $userRole): void
    {
        $changes = $userRole->getChanges();

        // Déterminer l'action
        $action = 'role_updated';
        if (isset($changes['actif'])) {
            $action = $changes['actif'] ? 'role_reactivated' : 'role_deactivated';
        }

        // Log de l'audit
        PermissionAuditLog::create([
            'action' => $action,
            'model_type' => 'UserRole',
            'model_id' => "{$userRole->user_id}_{$userRole->role_id}",
            'user_id' => auth()->id(),
            'target_user_id' => $userRole->user_id,
            'changes' => $changes,
            'original' => $userRole->getOriginal(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache de l'utilisateur
        dispatch(new RefreshPermissionCache($userRole->user_id));

        Log::info("Rôle utilisateur mis à jour", [
            'user_id' => $userRole->user_id,
            'role_id' => $userRole->role_id,
            'action' => $action,
            'changes' => $changes,
        ]);
    }

    /**
     * Handle the UserRole "deleted" event.
     */
    public function deleted(UserRole $userRole): void
    {
        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'role_removed',
            'model_type' => 'UserRole',
            'model_id' => "{$userRole->user_id}_{$userRole->role_id}",
            'user_id' => auth()->id(),
            'target_user_id' => $userRole->user_id,
            'changes' => ['deleted_at' => now()],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache de l'utilisateur
        dispatch(new RefreshPermissionCache($userRole->user_id));

        Log::warning("Rôle retiré d'un utilisateur", [
            'user_id' => $userRole->user_id,
            'role_id' => $userRole->role_id,
            'removed_by' => auth()->id(),
        ]);
    }
}
