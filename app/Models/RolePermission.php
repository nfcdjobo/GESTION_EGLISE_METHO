<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class RolePermission extends Pivot
{
    use SoftDeletes, HasUuids;

    /**
     * Le nom de la table
     */
    protected $table = 'role_permissions';

    /**
     * Indique si les timestamps sont gérés automatiquement
     */
    public $timestamps = true;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'role_id',
        'permission_id',
        'attribue_par',
        'attribue_le',
        'expire_le',
        'actif',
        'conditions',
        'notes',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'attribue_le' => 'datetime',
        'expire_le' => 'datetime',
        'actif' => 'boolean',
        'conditions' => 'array',
    ];

    /**
     * Relation avec le rôle
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relation avec la permission
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    /**
     * Relation avec l'membres qui a attribué la permission
     */
    public function attribuePar()
    {
        return $this->belongsTo(User::class, 'attribue_par');
    }

    /**
     * Scope pour les attributions actives
     */
    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Scope pour les attributions expirées
     */
    public function scopeExpirees($query)
    {
        return $query->whereNotNull('expire_le')
                     ->where('expire_le', '<', now());
    }

    /**
     * Scope pour les attributions non expirées
     */
    public function scopeNonExpirees($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expire_le')
              ->orWhere('expire_le', '>', now());
        });
    }

    /**
     * Scope pour les attributions valides (actives et non expirées)
     */
    public function scopeValides($query)
    {
        return $query->actives()->nonExpirees();
    }

    /**
     * Scope pour filtrer par rôle
     */
    public function scopeParRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    /**
     * Scope pour filtrer par permission
     */
    public function scopeParPermission($query, $permissionId)
    {
        return $query->where('permission_id', $permissionId);
    }

    /**
     * Scope pour les permissions qui expirent bientôt
     */
    public function scopeExpirentBientot($query, $jours = 7)
    {
        return $query->where('actif', true)
                     ->whereNotNull('expire_le')
                     ->whereBetween('expire_le', [now(), now()->addDays($jours)]);
    }

    /**
     * Vérifier si l'attribution est expirée
     */
    public function isExpiree()
    {
        return $this->expire_le && $this->expire_le < now();
    }

    /**
     * Vérifier si l'attribution est valide
     */
    public function isValide()
    {
        return $this->actif && !$this->isExpiree();
    }

    /**
     * Vérifier si l'attribution expire bientôt
     */
    public function expireBientot($jours = 7)
    {
        return $this->expire_le &&
               $this->expire_le <= now()->addDays($jours) &&
               !$this->isExpiree();
    }

    /**
     * Activer l'attribution
     */
    public function activer()
    {
        $this->update(['actif' => true]);
    }

    /**
     * Désactiver l'attribution
     */
    public function desactiver()
    {
        $this->update(['actif' => false]);
    }

    /**
     * Prolonger l'attribution
     */
    public function prolonger($nouvelleDateExpiration, $notes = null)
    {
        $updateData = ['expire_le' => $nouvelleDateExpiration];

        if ($notes) {
            $updateData['notes'] = $notes;
        }

        $this->update($updateData);
    }

    /**
     * Rendre l'attribution permanente
     */
    public function rendrePermanente($notes = null)
    {
        $updateData = ['expire_le' => null];

        if ($notes) {
            $updateData['notes'] = $notes;
        }

        $this->update($updateData);
    }

    /**
     * Ajouter une condition
     */
    public function ajouterCondition($cle, $valeur)
    {
        $conditions = $this->conditions ?? [];
        $conditions[$cle] = $valeur;
        $this->update(['conditions' => $conditions]);
    }

    /**
     * Supprimer une condition
     */
    public function supprimerCondition($cle)
    {
        $conditions = $this->conditions ?? [];
        unset($conditions[$cle]);
        $this->update(['conditions' => $conditions]);
    }

    /**
     * Vérifier une condition
     */
    public function verifierCondition($cle, $valeur = null)
    {
        $conditions = $this->conditions ?? [];

        if (!array_key_exists($cle, $conditions)) {
            return false;
        }

        if ($valeur === null) {
            return true; // Juste vérifier l'existence de la clé
        }

        return $conditions[$cle] === $valeur;
    }

    /**
     * Obtenir la durée de l'attribution
     */
    public function getDureeAttribute()
    {
        if (!$this->attribue_le) {
            return null;
        }

        $fin = $this->expire_le ?? now();
        return $this->attribue_le->diffInDays($fin);
    }

    /**
     * Obtenir le nombre de jours restants
     */
    public function getJoursRestantsAttribute()
    {
        if (!$this->expire_le) {
            return null; // Attribution permanente
        }

        $jours = now()->diffInDays($this->expire_le, false);
        return $jours > 0 ? $jours : 0;
    }

    /**
     * Obtenir le statut de l'attribution
     */
    public function getStatutAttribute()
    {
        if (!$this->actif) {
            return 'inactive';
        }

        if ($this->isExpiree()) {
            return 'expiree';
        }

        if ($this->expireBientot()) {
            return 'expire_bientot';
        }

        return 'active';
    }

    /**
     * Obtenir le nom de la permission
     */
    public function getNomPermissionAttribute()
    {
        return $this->permission ? $this->permission->name : null;
    }

    /**
     * Obtenir le nom du rôle
     */
    public function getNomRoleAttribute()
    {
        return $this->role ? $this->role->name : null;
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($rolePermission) {
            if (!$rolePermission->attribue_le) {
                $rolePermission->attribue_le = now();
            }
        });
    }

    /**
     * Obtenir les permissions d'un rôle avec leurs conditions
     */
    public static function getPermissionsAvecConditions($roleId)
    {
        return static::with(['permission'])
            ->where('role_id', $roleId)
            ->valides()
            ->get()
            ->map(function ($rolePermission) {
                return [
                    'permission' => $rolePermission->permission,
                    'conditions' => $rolePermission->conditions,
                    'notes' => $rolePermission->notes,
                    'expire_le' => $rolePermission->expire_le,
                    'jours_restants' => $rolePermission->jours_restants,
                ];
            });
    }

    /**
     * Obtenir les rôles ayant une permission spécifique
     */
    public static function getRolesAvecPermission($permissionId)
    {
        return static::with(['role'])
            ->where('permission_id', $permissionId)
            ->valides()
            ->get()
            ->map(function ($rolePermission) {
                return [
                    'role' => $rolePermission->role,
                    'conditions' => $rolePermission->conditions,
                    'notes' => $rolePermission->notes,
                    'attribue_le' => $rolePermission->attribue_le,
                    'attribue_par' => $rolePermission->attribuePar,
                ];
            });
    }

    /**
     * Copier les permissions d'un rôle vers un autre
     */
    public static function copierPermissions($roleSourceId, $roleDestinationId, $attribueParId = null)
    {
        $permissions = static::where('role_id', $roleSourceId)
            ->valides()
            ->get();

        foreach ($permissions as $permission) {
            static::create([
                'role_id' => $roleDestinationId,
                'permission_id' => $permission->permission_id,
                'attribue_par' => $attribueParId,
                'attribue_le' => now(),
                'actif' => true,
                'conditions' => $permission->conditions,
                'notes' => 'Copié depuis le rôle ' . $permission->role->name,
            ]);
        }
    }
}
