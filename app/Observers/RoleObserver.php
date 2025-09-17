<?php
namespace App\Observers;

use App\Models\Role;
use App\Models\PermissionAuditLog;
use App\Jobs\RefreshPermissionCache;
use Illuminate\Support\Facades\Log;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'created',
            'model_type' => 'Role',
            'model_id' => $role->id,
            'user_id' => auth()->id(),
            'changes' => $role->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache
        dispatch(new RefreshPermissionCache(null, true));

        Log::info("Rôle créé", [
            'role_id' => $role->id,
            'role_slug' => $role->slug,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        $changes = $role->getChanges();
        $original = $role->getOriginal();

        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'updated',
            'model_type' => 'Role',
            'model_id' => $role->id,
            'user_id' => auth()->id(),
            'changes' => $changes,
            'original' => $original,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Si le niveau hiérarchique change, vérifier les implications
        if (isset($changes['level'])) {
            $this->checkHierarchyImplications($role, $original['level']);
        }

        // Rafraîchir le cache des membres affectés
        $this->refreshAffectedUsersCache($role);

        Log::info("Rôle mis à jour", [
            'role_id' => $role->id,
            'changes' => $changes,
            'updated_by' => auth()->id(),
        ]);
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'deleted',
            'model_type' => 'Role',
            'model_id' => $role->id,
            'user_id' => auth()->id(),
            'changes' => ['deleted_at' => now()],
            'original' => $role->getOriginal(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache
        dispatch(new RefreshPermissionCache(null, true));

        Log::warning("Rôle supprimé", [
            'role_id' => $role->id,
            'role_slug' => $role->slug,
            'deleted_by' => auth()->id(),
        ]);
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        // Log de l'audit
        PermissionAuditLog::create([
            'action' => 'restored',
            'model_type' => 'Role',
            'model_id' => $role->id,
            'user_id' => auth()->id(),
            'changes' => ['restored_at' => now()],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Rafraîchir le cache
        dispatch(new RefreshPermissionCache(null, true));

        Log::info("Rôle restauré", [
            'role_id' => $role->id,
            'restored_by' => auth()->id(),
        ]);
    }

    /**
     * Vérifier les implications d'un changement de hiérarchie
     */
    protected function checkHierarchyImplications(Role $role, int $oldLevel): void
    {
        if ($role->level < $oldLevel) {
            // Le rôle a été rétrogradé
            Log::warning("Rôle rétrogradé", [
                'role_id' => $role->id,
                'old_level' => $oldLevel,
                'new_level' => $role->level,
                'affected_users' => $role->users()->count(),
            ]);
        }
    }

    /**
     * Rafraîchir le cache des membres affectés
     */
    protected function refreshAffectedUsersCache(Role $role): void
    {
        $userIds = $role->users()->pluck('users.id');
        foreach ($userIds as $userId) {
            dispatch(new RefreshPermissionCache($userId));
        }
    }
}
