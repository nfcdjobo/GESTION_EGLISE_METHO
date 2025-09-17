<?php

namespace App\Services;


use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\UserPermission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
// Str

class PermissionService
{
    /**
     * Créer une nouvelle permission
     */
    public function createPermission(array $data): Permission
    {
        return DB::transaction(function () use ($data) {
            $permission = Permission::create([
                'name' => $data['name'],
                'slug' => $data['slug'] ?? Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'resource' => $data['resource'] ?? null,
                'action' => $data['action'] ?? 'read',
                'guard_name' => $data['guard_name'] ?? 'web',
                'category' => $data['category'] ?? null,
                'priority' => $data['priority'] ?? 0,
                'is_active' => $data['is_active'] ?? true,
                'is_system' => $data['is_system'] ?? false,
                'conditions' => $data['conditions'] ?? null,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            $this->clearCache();

            return $permission;
        });
    }

    /**
     * Créer un nouveau rôle
     */
    public function createRole(array $data): Role
    {
        return DB::transaction(function () use ($data) {
            $role = Role::create([
                'name' => $data['name'],
                'slug' => $data['slug'] ?? Str::slug($data['name']),
                'description' => $data['description'] ?? null,
                'level' => $data['level'] ?? 0,
                'is_system_role' => $data['is_system_role'] ?? false,
            ]);

            // Attribuer les permissions initiales si fournies
            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $this->syncRolePermissions($role, $data['permissions']);
            }

            $this->clearCache();

            return $role;
        });
    }

    /**
     * Assigner un rôle à un membres
     */
    public function assignRoleToUser(User $user, $role, ?User $assignedBy = null, ?\DateTime $expiresAt = null): void
    {
        DB::transaction(function () use ($user, $role, $assignedBy, $expiresAt) {
            if (is_string($role)) {
                $role = Role::where('slug', $role)->firstOrFail();
            }

            // Vérifier si l'membres a déjà ce rôle
            $existingRole = UserRole::where('user_id', $user->id)
                ->where('role_id', $role->id)
                ->first();

            if ($existingRole) {
                // Réactiver le rôle s'il était inactif
                $existingRole->update([
                    'actif' => true,
                    'attribue_par' => $assignedBy?->id,
                    'attribue_le' => now(),
                    'expire_le' => $expiresAt,
                ]);
            } else {
                // Créer une nouvelle attribution
                $user->roles()->attach($role->id, [
                    'attribue_par' => $assignedBy?->id,
                    'attribue_le' => now(),
                    'expire_le' => $expiresAt,
                    'actif' => true,
                ]);
            }

            $this->clearUserCache($user);
        });
    }

    /**
     * Retirer un rôle d'un membres
     */
    public function removeRoleFromUser(User $user, $role): void
    {
        DB::transaction(function () use ($user, $role) {
            if (is_string($role)) {
                $role = Role::where('slug', $role)->firstOrFail();
            }

            $user->roles()->updateExistingPivot($role->id, [
                'actif' => false,
            ]);

            $this->clearUserCache($user);
        });
    }

    /**
     * Accorder une permission directe à un membres
     */
    public function grantPermissionToUser(
        User $user,
        $permission,
        ?User $grantedBy = null,
        ?\DateTime $expiresAt = null,
        ?string $reason = null
    ): void {
        DB::transaction(function () use ($user, $permission, $grantedBy, $expiresAt, $reason) {
            if (is_string($permission)) {
                $permission = Permission::where('slug', $permission)->firstOrFail();
            }

            $existingPermission = UserPermission::where('user_id', $user->id)
                ->where('permission_id', $permission->id)
                ->first();

            if ($existingPermission) {
                $existingPermission->update([
                    'is_granted' => true,
                    'granted_by' => $grantedBy?->id,
                    'granted_at' => now(),
                    'expires_at' => $expiresAt,
                    'reason' => $reason,
                    'revoked_by' => null,
                    'revoked_at' => null,
                    'revocation_reason' => null,
                ]);
            } else {
                UserPermission::create([
                    'user_id' => $user->id,
                    'permission_id' => $permission->id,
                    'is_granted' => true,
                    'granted_by' => $grantedBy?->id,
                    'granted_at' => now(),
                    'expires_at' => $expiresAt,
                    'reason' => $reason,
                ]);
            }

            $this->clearUserCache($user);
        });
    }

    /**
     * Révoquer une permission d'un membres
     */
    public function revokePermissionFromUser(
        User $user,
        $permission,
        ?User $revokedBy = null,
        ?string $reason = null
    ): void {
        DB::transaction(function () use ($user, $permission, $revokedBy, $reason) {
            if (is_string($permission)) {
                $permission = Permission::where('slug', $permission)->firstOrFail();
            }

            $userPermission = UserPermission::where('user_id', $user->id)
                ->where('permission_id', $permission->id)
                ->first();

            if ($userPermission) {
                $userPermission->revoke($revokedBy?->id, $reason);
            }

            $this->clearUserCache($user);
        });
    }

    /**
     * Synchroniser les permissions d'un rôle
     */
    public function syncRolePermissions(Role $role, array $permissionIds, ?User $assignedBy = null): void
    {
        DB::transaction(function () use ($role, $permissionIds, $assignedBy) {
            $syncData = [];

            foreach ($permissionIds as $permissionId) {
                $permission = is_string($permissionId)
                    ? Permission::where('slug', $permissionId)->orWhere('id', $permissionId)->first()
                    : Permission::find($permissionId);

                if ($permission) {
                    $syncData[$permission->id] = [
                        'attribue_par' => $assignedBy?->id,
                        'attribue_le' => now(),
                        'actif' => true,
                    ];
                }
            }

            $role->permissions()->sync($syncData);
            $this->clearCache();
        });
    }

    /**
     * Copier les permissions d'un rôle vers un autre
     */
    public function copyRolePermissions(Role $sourceRole, Role $targetRole, ?User $assignedBy = null): void
    {
        DB::transaction(function () use ($sourceRole, $targetRole, $assignedBy) {
            RolePermission::copierPermissions(
                $sourceRole->id,
                $targetRole->id,
                $assignedBy?->id
            );

            $this->clearCache();
        });
    }

    /**
     * Vérifier si un membres a une permission
     */
    public function userHasPermission(User $user, string $permission): bool
    {
        $cacheKey = "user_{$user->id}_has_permission_{$permission}";

        return Cache::remember($cacheKey, 300, function () use ($user, $permission) {
            return $user->hasPermission($permission);
        });
    }

    /**
     * Vérifier si un membres a un rôle
     */
    public function userHasRole(User $user, string $role): bool
    {
        $cacheKey = "user_{$user->id}_has_role_{$role}";

        return Cache::remember($cacheKey, 300, function () use ($user, $role) {
            return $user->hasRole($role);
        });
    }

    /**
     * Obtenir toutes les permissions d'un membres
     */
    public function getUserPermissions(User $user): Collection
    {
        $cacheKey = "user_{$user->id}_all_permissions";

        return Cache::remember($cacheKey, 300, function () use ($user) {
            return $user->getAllPermissions();
        });
    }

    /**
     * Obtenir les membres ayant une permission spécifique
     */
    public function getUsersWithPermission(string $permission): Collection
    {
        $permission = Permission::where('slug', $permission)->firstOrFail();

        // Membress avec permission directe
        $directUsers = User::whereHas('permissions', function ($query) use ($permission) {
            $query->where('permissions.id', $permission->id)
                  ->where('user_permissions.is_granted', true)
                  ->where(function ($q) {
                      $q->whereNull('user_permissions.expires_at')
                        ->orWhere('user_permissions.expires_at', '>', now());
                  });
        })->get();

        // Membress avec permission via rôle
        $roleUsers = User::whereHas('roles.permissions', function ($query) use ($permission) {
            $query->where('permissions.id', $permission->id)
                  ->where('user_roles.actif', true)
                  ->where('role_permissions.actif', true);
        })->get();

        return $directUsers->merge($roleUsers)->unique('id');
    }

    /**
     * Obtenir les membres ayant un rôle spécifique
     */
    public function getUsersWithRole(string $role): Collection
    {
        $role = Role::where('slug', $role)->firstOrFail();

        return User::whereHas('roles', function ($query) use ($role) {
            $query->where('roles.id', $role->id)
                  ->where('user_roles.actif', true)
                  ->where(function ($q) {
                      $q->whereNull('user_roles.expire_le')
                        ->orWhere('user_roles.expire_le', '>', now());
                  });
        })->get();
    }

    /**
     * Nettoyer les permissions expirées
     */
    public function cleanupExpiredPermissions(): array
    {
        return DB::transaction(function () {
            // Permissions directes expirées
            $expiredDirectPermissions = UserPermission::where('expires_at', '<', now())
                ->where('is_granted', true)
                ->update(['is_expired' => true]);

            // Rôles expirés
            $expiredRoles = UserRole::where('expire_le', '<', now())
                ->where('actif', true)
                ->update(['actif' => false]);

            // Permissions de rôles expirées
            $expiredRolePermissions = RolePermission::where('expire_le', '<', now())
                ->where('actif', true)
                ->update(['actif' => false]);

            $this->clearCache();

            return [
                'direct_permissions' => $expiredDirectPermissions,
                'roles' => $expiredRoles,
                'role_permissions' => $expiredRolePermissions,
            ];
        });
    }

    /**
     * Obtenir les permissions qui expirent bientôt
     */
    public function getExpiringPermissions(int $days = 7): array
    {
        $expiringDate = now()->addDays($days);

        return [
            'direct_permissions' => UserPermission::with(['user', 'permission'])
                ->where('is_granted', true)
                ->whereNotNull('expires_at')
                ->whereBetween('expires_at', [now(), $expiringDate])
                ->get(),

            'roles' => UserRole::with(['user', 'role'])
                ->where('actif', true)
                ->whereNotNull('expire_le')
                ->whereBetween('expire_le', [now(), $expiringDate])
                ->get(),

            'role_permissions' => RolePermission::with(['role', 'permission'])
                ->where('actif', true)
                ->whereNotNull('expire_le')
                ->whereBetween('expire_le', [now(), $expiringDate])
                ->get(),
        ];
    }

    /**
     * Audit des permissions d'un membres
     */
    public function auditUserPermissions(User $user): array
    {
        return [
            'user' => [
                'id' => $user->id,
                'name' => $user->nom_complet,
                'email' => $user->email,
            ],
            'roles' => $user->roles()
                ->wherePivot('actif', true)
                ->with('permissions')
                ->get()
                ->map(function ($role) {
                    return [
                        'name' => $role->name,
                        'level' => $role->level,
                        'expires_at' => $role->pivot->expire_le,
                        'permissions_count' => $role->permissions->count(),
                    ];
                }),
            'direct_permissions' => $user->permissions()
                ->wherePivot('is_granted', true)
                ->get()
                ->map(function ($permission) {
                    return [
                        'name' => $permission->name,
                        'resource' => $permission->resource,
                        'action' => $permission->action,
                        'expires_at' => $permission->pivot->expires_at,
                    ];
                }),
            'all_permissions' => $user->getAllPermissions()
                ->map(function ($permission) {
                    return [
                        'name' => $permission->name,
                        'resource' => $permission->resource,
                        'action' => $permission->action,
                    ];
                }),
            'statistics' => [
                'total_roles' => $user->roles()->wherePivot('actif', true)->count(),
                'total_direct_permissions' => $user->permissions()->wherePivot('is_granted', true)->count(),
                'total_all_permissions' => $user->getAllPermissions()->count(),
                'highest_role_level' => $user->getHighestRoleLevel(),
            ],
        ];
    }

    /**
     * Effacer le cache
     */
    public function clearCache(): void
    {
        Cache::tags(['permissions', 'roles'])->flush();
    }

    /**
     * Effacer le cache d'un membres spécifique
     */
    protected function clearUserCache(User $user): void
    {
        Cache::forget("user_{$user->id}_all_permissions");
        Cache::tags(["user_{$user->id}"])->flush();
    }

    /**
     * Obtenir les statistiques du système de permissions
     */
    public function getSystemStatistics(): array
    {
        return Cache::remember('permission_system_stats', 3600, function () {
            return [
                'total_permissions' => Permission::count(),
                'active_permissions' => Permission::where('is_active', true)->count(),
                'total_roles' => Role::count(),
                'system_roles' => Role::where('is_system_role', true)->count(),
                'users_with_roles' => User::has('roles')->count(),
                'users_with_direct_permissions' => User::has('permissions')->count(),
                'expired_permissions' => UserPermission::where('is_expired', true)->count(),
                'expiring_soon' => [
                    'permissions' => UserPermission::expiringSoon()->count(),
                    'roles' => DB::table('user_roles')
                        ->where('actif', true)
                        ->whereNotNull('expire_le')
                        ->whereBetween('expire_le', [now(), now()->addDays(7)])
                        ->count(),
                ],
            ];
        });
    }
}
