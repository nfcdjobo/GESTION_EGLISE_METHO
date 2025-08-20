<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserRole extends Pivot
{
    use SoftDeletes, HasUuids;

    /**
     * Le nom de la table
     */
    protected $table = 'user_roles';

    /**
     * Indique si les timestamps sont gérés automatiquement
     */
    public $timestamps = true;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'user_id',
        'role_id',
        'attribue_par',
        'attribue_le',
        'expire_le',
        'actif',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'attribue_le' => 'datetime',
        'expire_le' => 'datetime',
        'actif' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec le rôle
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Relation avec l'utilisateur qui a attribué le rôle
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
    public function prolonger($nouvelleDateExpiration)
    {
        $this->update(['expire_le' => $nouvelleDateExpiration]);
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

        if ($this->expire_le && $this->expire_le <= now()->addDays(7)) {
            return 'expire_bientot';
        }

        return 'active';
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($userRole) {
            if (!$userRole->attribue_le) {
                $userRole->attribue_le = now();
            }
        });
    }
}
