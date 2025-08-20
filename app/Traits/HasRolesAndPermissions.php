<?php

// ================================================
// Trait pour faciliter la gestion des permissions
// ================================================

namespace App\Traits;

use App\Models\Permission;
use App\Models\RolePermission;

trait HasRolesAndPermissions
{
    /**
     * Relation many-to-many avec les rôles via UserRole
     */
    public function userRoles()
    {
        return $this->hasMany(\App\Models\UserRole::class)->actifs()->nonExpires();
    }

    /**
     * Obtenir toutes les permissions via les rôles
     */
    public function getAllPermissions()
    {
        return Permission::whereHas('roles', function ($query) {
            $query->whereHas('users', function ($subQuery) {
                $subQuery->where('users.id', $this->id)
                    ->where('user_roles.actif', true)
                    ->where(function ($q) {
                        $q->whereNull('user_roles.expire_le')
                            ->orWhere('user_roles.expire_le', '>', now());
                    });
            });
        })->where('is_active', true)->get();
    }

    /**
     * Vérifier une permission avec gestion des conditions
     */
    public function hasPermissionWithConditions(string $permissionSlug, array $contexte = []): bool
    {
        $rolePermissions = RolePermission::whereHas('role.users', function ($query) {
            $query->where('users.id', $this->id)
                ->where('user_roles.actif', true);
        })->whereHas('permission', function ($query) use ($permissionSlug) {
            $query->where('slug', $permissionSlug)
                ->where('is_active', true);
        })->actifs()->nonExpires()->get();

        foreach ($rolePermissions as $rolePermission) {
            if ($rolePermission->conditionsRemplies($contexte)) {
                return true;
            }
        }

        return false;
    }
}
