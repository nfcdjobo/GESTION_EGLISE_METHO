<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Collection;

trait HasPermissions
{
    /**
     * Cache des permissions de l'utilisateur
     */
    protected ?Collection $permissionsCache = null;

    /**
     * Vérifier si l'utilisateur a une ou plusieurs permissions
     */
    public function hasPermission($permissions): bool
    {
        if (is_array($permissions)) {
            return $this->hasAnyPermission($permissions);
        }

        $permissions = $this->getAllPermissions();

        if (is_string($permissions)) {
            return $permissions->contains('slug', $permissions);
        }

        return $permissions->contains($permissions);
    }

    /**
     * Vérifier si l'utilisateur a au moins une des permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifier si l'utilisateur a toutes les permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Vérifier si l'utilisateur a une permission sur une ressource spécifique
     */
    public function hasResourcePermission(string $resource, string $action): bool
    {
        $permissions = $this->getAllPermissions();

        return $permissions->contains(function ($permission) use ($resource, $action) {
            return $permission->resource === $resource &&
                   ($permission->action === $action || $permission->action === 'manage');
        });
    }

    /**
     * Obtenir toutes les permissions de l'utilisateur (directes + via rôles)
     */
    public function getAllPermissions(): Collection
    {
        if ($this->permissionsCache !== null) {
            return $this->permissionsCache;
        }

        $permissions = collect();

        // Permissions directes actives
        $directPermissions = $this->permissions()
            ->wherePivot('is_granted', true)
            ->where(function ($query) {
                $query->whereNull('user_permissions.expires_at')
                      ->orWhere('user_permissions.expires_at', '>', now());
            })
            ->where('permissions.is_active', true)
            ->get();

        $permissions = $permissions->merge($directPermissions);

        // Permissions via les rôles actifs
        $rolesActifs = $this->roles()
            ->wherePivot('actif', true)
            ->where(function ($query) {
                $query->whereNull('user_roles.expire_le')
                      ->orWhere('user_roles.expire_le', '>', now());
            })
            ->with(['permissions' => function ($query) {
                $query->wherePivot('actif', true)
                      ->where(function ($q) {
                          $q->whereNull('role_permissions.expire_le')
                            ->orWhere('role_permissions.expire_le', '>', now());
                      })
                      ->where('permissions.is_active', true);
            }])
            ->get();

        foreach ($rolesActifs as $role) {
            $permissions = $permissions->merge($role->permissions);
        }

        // Enlever les doublons et mettre en cache
        $this->permissionsCache = $permissions->unique('id');

        return $this->permissionsCache;
    }

    /**
     * Obtenir toutes les permissions groupées par catégorie
     */
    public function getPermissionsByCategory(): Collection
    {
        return $this->getAllPermissions()->groupBy('category');
    }

    /**
     * Obtenir toutes les permissions pour une ressource
     */
    public function getPermissionsForResource(string $resource): Collection
    {
        return $this->getAllPermissions()->where('resource', $resource);
    }

    /**
     * Vérifier si l'utilisateur a un rôle
     */
    public function hasRole($roles): bool
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles);
        }

        $userRoles = $this->roles()
            ->wherePivot('actif', true)
            ->where(function ($query) {
                $query->whereNull('user_roles.expire_le')
                      ->orWhere('user_roles.expire_le', '>', now());
            })
            ->get();

        if (is_string($roles)) {
            return $userRoles->contains('slug', $roles);
        }

        return $userRoles->contains($roles);
    }

    /**
     * Vérifier si l'utilisateur a au moins un des rôles
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Vérifier si l'utilisateur a tous les rôles
     */
    public function hasAllRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if (!$this->hasRole($role)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Attribuer un rôle à l'utilisateur
     */
    public function assignRole($role, $assignedBy = null, $expiresAt = null): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        if (!$this->hasRole($role)) {
            $this->roles()->attach($role->id, [
                'attribue_par' => $assignedBy,
                'attribue_le' => now(),
                'expire_le' => $expiresAt,
                'actif' => true,
            ]);
            $this->clearPermissionsCache();
        }
    }

    /**
     * Retirer un rôle de l'utilisateur
     */
    public function removeRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $this->roles()->updateExistingPivot($role->id, [
            'actif' => false,
        ]);

        $this->clearPermissionsCache();
    }

    /**
     * Synchroniser les rôles de l'utilisateur
     */
    public function syncRoles(array $roles, $assignedBy = null): void
    {
        $roleData = [];

        foreach ($roles as $role) {
            if (is_string($role)) {
                $roleModel = Role::where('slug', $role)->orWhere('id', $role)->first();
                if ($roleModel) {
                    $roleData[$roleModel->id] = [
                        'attribue_par' => $assignedBy,
                        'attribue_le' => now(),
                        'actif' => true,
                    ];
                }
            } elseif (is_object($role)) {
                $roleData[$role->id] = [
                    'attribue_par' => $assignedBy,
                    'attribue_le' => now(),
                    'actif' => true,
                ];
            }
        }

        $this->roles()->sync($roleData);
        $this->clearPermissionsCache();
    }

    /**
     * Accorder une permission directe à l'utilisateur
     */
    public function grantPermission($permission, $grantedBy = null, $expiresAt = null, $reason = null): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }

        // Vérifier si la permission existe déjà
        $existingPermission = UserPermission::where('user_id', $this->id)
            ->where('permission_id', $permission->id)
            ->first();

        if ($existingPermission) {
            // Mettre à jour la permission existante
            $existingPermission->update([
                'is_granted' => true,
                'granted_by' => $grantedBy,
                'granted_at' => now(),
                'expires_at' => $expiresAt,
                'reason' => $reason,
                'revoked_by' => null,
                'revoked_at' => null,
                'revocation_reason' => null,
            ]);
        } else {
            // Créer une nouvelle permission
            UserPermission::create([
                'user_id' => $this->id,
                'permission_id' => $permission->id,
                'is_granted' => true,
                'granted_by' => $grantedBy,
                'granted_at' => now(),
                'expires_at' => $expiresAt,
                'reason' => $reason,
            ]);
        }

        $this->clearPermissionsCache();
    }

    /**
     * Révoquer une permission directe
     */
    public function revokePermission($permission, $revokedBy = null, $reason = null): void
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->firstOrFail();
        }

        $userPermission = UserPermission::where('user_id', $this->id)
            ->where('permission_id', $permission->id)
            ->first();

        if ($userPermission) {
            $userPermission->revoke($revokedBy, $reason);
        }

        $this->clearPermissionsCache();
    }

    /**
     * Vérifier si l'utilisateur est super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('secretaire');
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['pasteur', 'president-laique']);
    }

    /**
     * Obtenir le niveau hiérarchique le plus élevé de l'utilisateur
     */
    public function getHighestRoleLevel(): ?int
    {
        $roles = $this->roles()
            ->wherePivot('actif', true)
            ->where(function ($query) {
                $query->whereNull('user_roles.expire_le')
                      ->orWhere('user_roles.expire_le', '>', now());
            })
            ->get();

        if ($roles->isEmpty()) {
            return null;
        }

        return $roles->max('level');
    }

    /**
     * Vérifier si l'utilisateur peut gérer un autre utilisateur
     */
    public function canManageUser(User $targetUser): bool
    {
        // Super admin peut tout gérer
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Comparer les niveaux hiérarchiques
        $myLevel = $this->getHighestRoleLevel();
        $targetLevel = $targetUser->getHighestRoleLevel();

        if ($myLevel === null || $targetLevel === null) {
            return false;
        }

        // Un utilisateur peut gérer ceux de niveau inférieur
        return $myLevel > $targetLevel;
    }

    /**
     * Effacer le cache des permissions
     */
    public function clearPermissionsCache(): void
    {
        $this->permissionsCache = null;
    }

    /**
     * Obtenir les permissions qui expirent bientôt
     */
    public function getExpiringPermissions(int $days = 7): Collection
    {
        $directPermissions = UserPermission::where('user_id', $this->id)
            ->where('is_granted', true)
            ->whereNotNull('expires_at')
            ->whereBetween('expires_at', [now(), now()->addDays($days)])
            ->with('permission')
            ->get();

        return $directPermissions;
    }

    /**
     * Obtenir les rôles qui expirent bientôt
     */
    public function getExpiringRoles(int $days = 7): Collection
    {
        return $this->roles()
            ->wherePivot('actif', true)
            ->whereNotNull('user_roles.expire_le')
            ->whereBetween('user_roles.expire_le', [now(), now()->addDays($days)])
            ->get();
    }
}
