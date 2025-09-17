<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'resource',
        'action',
        'guard_name',
        'category',
        'priority',
        'is_active',
        'is_system',
        'conditions',
        'created_by',
        'updated_by',
        'last_used_at',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
        'conditions' => 'array',
        'priority' => 'integer',
        'last_used_at' => 'datetime',
    ];

    /**
     * Relation avec les rôles (many-to-many)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
                    ->withPivot('attribue_par', 'attribue_le', 'expire_le', 'actif', 'conditions', 'notes')
                    ->withTimestamps();
    }

    /**
     * Relation avec les membres (many-to-many)
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_permissions')
                    ->withPivot('is_granted', 'granted_by', 'granted_at', 'expires_at', 'reason')
                    ->withTimestamps();
    }

    /**
     * Membres qui a créé la permission
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Dernier membres qui a modifié la permission
     */
    public function modificateur()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope pour les permissions actives
     */
    public function scopeActives($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les permissions système
     */
    public function scopeSysteme($query)
    {
        return $query->where('is_system', true);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeParCategorie($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope pour filtrer par ressource
     */
    public function scopeParRessource($query, $resource)
    {
        return $query->where('resource', $resource);
    }

    /**
     * Scope pour filtrer par action
     */
    public function scopeParAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour filtrer par guard
     */
    public function scopeParGuard($query, $guard)
    {
        return $query->where('guard_name', $guard);
    }

    /**
     * Accesseur pour obtenir le nom complet de la permission
     */
    public function getNomCompletAttribute()
    {
        return $this->resource . '.' . $this->action;
    }

    /**
     * Vérifier si la permission est expirée (pour un membres spécifique)
     */
    public function isExpiredForUser($userId)
    {
        $userPermission = $this->users()->where('user_id', $userId)->first();

        if (!$userPermission || !$userPermission->pivot->expires_at) {
            return false;
        }

        return $userPermission->pivot->expires_at < now();
    }

    /**
     * Mettre à jour la dernière utilisation
     */
    public function updateLastUsed()
    {
        $this->update(['last_used_at' => now()]);
    }
}
