<?php

namespace App\Observers;

use App\Models\Permission;
use App\Models\PermissionAuditLog;
use App\Jobs\RefreshPermissionCache;
use Illuminate\Support\Facades\Log;

class PermissionObserver
{
    /**
     * Handle the Permission "created" event.
     */
    public function created(Permission $permission): void
    {
        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'created',
            'model_type' => 'Permission',
            'model_id' => $permission->id,
            'user_id' => auth()->id(),
            'changes' => $permission->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache
        dispatch(new RefreshPermissionCache(null, true));

        Log::info("Permission créée", [
            'permission_id' => $permission->id,
            'permission_slug' => $permission->slug,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Handle the Permission "updated" event.
     */
    public function updated(Permission $permission): void
    {
        $changes = $permission->getChanges();
        $original = $permission->getOriginal();

        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'updated',
            'model_type' => 'Permission',
            'model_id' => $permission->id,
            'user_id' => auth()->id(),
            'changes' => $changes,
            'original' => $original,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Si la permission est désactivée, notifier les utilisateurs affectés
        if (isset($changes['is_active']) && !$changes['is_active']) {
            $this->notifyAffectedUsers($permission);
        }

        // Rafraîchir le cache
        dispatch(new RefreshPermissionCache(null, true));

        Log::info("Permission mise à jour", [
            'permission_id' => $permission->id,
            'changes' => $changes,
            'updated_by' => auth()->id(),
        ]);
    }

    /**
     * Handle the Permission "deleted" event.
     */
    public function deleted(Permission $permission): void
    {
        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'deleted',
            'model_type' => 'Permission',
            'model_id' => $permission->id,
            'user_id' => auth()->id(),
            'changes' => ['deleted_at' => now()],
            'original' => $permission->getOriginal(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache
        dispatch(new RefreshPermissionCache(null, true));

        Log::warning("Permission supprimée", [
            'permission_id' => $permission->id,
            'permission_slug' => $permission->slug,
            'deleted_by' => auth()->id(),
        ]);
    }

    /**
     * Handle the Permission "restored" event.
     */
    public function restored(Permission $permission): void
    {
        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'restored',
            'model_type' => 'Permission',
            'model_id' => $permission->id,
            'user_id' => auth()->id(),
            'changes' => ['restored_at' => now()],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache
        dispatch(new RefreshPermissionCache(null, true));

        Log::info("Permission restaurée", [
            'permission_id' => $permission->id,
            'restored_by' => auth()->id(),
        ]);
    }

    /**
     * Notifier les utilisateurs affectés par un changement
     */
    protected function notifyAffectedUsers(Permission $permission): void
    {
        // Obtenir tous les utilisateurs ayant cette permission
        $affectedUsers = $permission->users()->get()
            ->merge(
                $permission->roles()->with('users')->get()->pluck('users')->flatten()
            )->unique('id');

        // Envoyer des notifications ou effectuer d'autres actions
        foreach ($affectedUsers as $user) {
            // Rafraîchir le cache de l'utilisateur
            dispatch(new RefreshPermissionCache($user->id));
        }
    }
}





