<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'level',
        'is_system_role',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'level' => 'integer',
        'is_system_role' => 'boolean',
    ];

    /**
     * Relation avec les membres (many-to-many)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles')
                    ->withPivot('attribue_par', 'attribue_le', 'expire_le', 'actif')
                    ->withTimestamps();
    }

    /**
     * Relation avec les permissions (many-to-many)
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions')
                    ->withPivot('attribue_par', 'attribue_le', 'expire_le', 'actif', 'conditions', 'notes')
                    ->withTimestamps();
    }

    /**
     * Scope pour les rôles système
     */
    public function scopeSysteme($query)
    {
        return $query->where('is_system_role', true);
    }

    /**
     * Scope pour les rôles personnalisés
     */
    public function scopePersonnalises($query)
    {
        return $query->where('is_system_role', false);
    }

    /**
     * Scope pour filtrer par niveau hiérarchique
     */
    public function scopeParNiveau($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope pour les rôles de niveau supérieur ou égal
     */
    public function scopeNiveauSuperieurOuEgal($query, $level)
    {
        return $query->where('level', '>=', $level);
    }

    /**
     * Scope pour les rôles de niveau inférieur
     */
    public function scopeNiveauInferieur($query, $level)
    {
        return $query->where('level', '<', $level);
    }

    /**
     * Vérifier si le rôle a une permission spécifique
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('slug', $permission);
        }

        return $this->permissions->contains($permission);
    }

    /**
     * Assigner une permission au rôle
     */
    public function assignPermission($permission, $assignedBy = null)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }

        if (!$permission) {
            throw new \InvalidArgumentException('Permission not found');
        }

        return $this->permissions()->attach($permission->id, [
            'attribue_par' => $assignedBy,
            'attribue_le' => now(),
            'actif' => true,
        ]);
    }

    /**
     * Retirer une permission du rôle
     */
    public function removePermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('slug', $permission)->first();
        }

        if (!$permission) {
            return false;
        }

        return $this->permissions()->detach($permission->id);
    }

    /**
     * Synchroniser les permissions du rôle
     */
    public function syncPermissions(array $permissions, $assignedBy = null)
    {
        $permissionData = [];

        foreach ($permissions as $permission) {
            if (is_string($permission)) {
                $permissionModel = Permission::where('slug', $permission)->orWhere('id', $permission)->first();
                if ($permissionModel) {
                    $permissionData[$permissionModel->id] = [
                        'attribue_par' => $assignedBy,
                        'attribue_le' => now(),
                        'actif' => true,
                    ];
                }
            } elseif (is_object($permission)) {
                $permissionData[$permission->id] = [
                    'attribue_par' => $assignedBy,
                    'attribue_le' => now(),
                    'actif' => true,
                ];
            }
        }

        return $this->permissions()->sync($permissionData);
    }

    /**
     * Obtenir le nombre d'membres ayant ce rôle
     */
    public function getNombreMembressAttribute()
    {
        return $this->users()->wherePivot('actif', true)->count();
    }

    /**
     * Vérifier si le rôle peut être supprimé
     */
    public function canBeDeleted()
    {
        return !$this->is_system_role && $this->users()->count() === 0;
    }
}
