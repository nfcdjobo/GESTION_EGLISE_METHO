<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserPermission extends Model
{
    use SoftDeletes, HasUuids;

    /**
     * Le nom de la table
     */
    protected $table = 'user_permissions';

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'user_id',
        'permission_id',
        'is_granted',
        'granted_by',
        'granted_at',
        'expires_at',
        'is_expired',
        'reason',
        'metadata',
        'revoked_by',
        'revoked_at',
        'revocation_reason',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'is_granted' => 'boolean',
        'is_expired' => 'boolean',
        'granted_at' => 'datetime',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Relation avec l'membres
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec la permission
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }

    /**
     * Relation avec l'membres qui a accordé la permission
     */
    public function grantedBy()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    /**
     * Relation avec l'membres qui a révoqué la permission
     */
    public function revokedBy()
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }

    /**
     * Scope pour les permissions accordées
     */
    public function scopeGranted($query)
    {
        return $query->where('is_granted', true);
    }

    /**
     * Scope pour les permissions révoquées
     */
    public function scopeRevoked($query)
    {
        return $query->where('is_granted', false);
    }

    /**
     * Scope pour les permissions expirées
     */
    public function scopeExpired($query)
    {
        return $query->where('is_expired', true)
                     ->orWhere(function($q) {
                         $q->whereNotNull('expires_at')
                           ->where('expires_at', '<', now());
                     });
    }

    /**
     * Scope pour les permissions actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_granted', true)
                     ->where('is_expired', false)
                     ->where(function($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', now());
                     });
    }

    /**
     * Scope pour les permissions qui expirent bientôt
     */
    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->where('is_granted', true)
                     ->whereNotNull('expires_at')
                     ->whereBetween('expires_at', [now(), now()->addDays($days)]);
    }

    /**
     * Vérifier si la permission est expirée
     */
    public function isExpired()
    {
        return $this->is_expired ||
               ($this->expires_at && $this->expires_at < now());
    }

    /**
     * Vérifier si la permission est active
     */
    public function isActive()
    {
        return $this->is_granted && !$this->isExpired();
    }

    /**
     * Vérifier si la permission expire bientôt
     */
    public function isExpiringSoon($days = 7)
    {
        return $this->expires_at &&
               $this->expires_at <= now()->addDays($days) &&
               !$this->isExpired();
    }

    /**
     * Révoquer la permission
     */
    public function revoke($revokedBy = null, $reason = null)
    {
        $this->update([
            'is_granted' => false,
            'revoked_by' => $revokedBy,
            'revoked_at' => now(),
            'revocation_reason' => $reason,
        ]);
    }

    /**
     * Restaurer la permission
     */
    public function restore($grantedBy = null, $reason = null)
    {
        $this->update([
            'is_granted' => true,
            'granted_by' => $grantedBy,
            'granted_at' => now(),
            'reason' => $reason,
            'revoked_by' => null,
            'revoked_at' => null,
            'revocation_reason' => null,
        ]);
    }

    /**
     * Prolonger la permission
     */
    public function extend($newExpirationDate)
    {
        $this->update([
            'expires_at' => $newExpirationDate,
            'is_expired' => false,
        ]);
    }

    /**
     * Rendre la permission permanente
     */
    public function makePermanent()
    {
        $this->update([
            'expires_at' => null,
            'is_expired' => false,
        ]);
    }

    /**
     * Obtenir le nombre de jours restants
     */
    public function getDaysRemainingAttribute()
    {
        if (!$this->expires_at) {
            return null; // Permission permanente
        }

        $days = now()->diffInDays($this->expires_at, false);
        return $days > 0 ? $days : 0;
    }

    /**
     * Obtenir la durée totale de la permission
     */
    public function getTotalDurationAttribute()
    {
        if (!$this->granted_at) {
            return null;
        }

        $end = $this->expires_at ?? now();
        return $this->granted_at->diffInDays($end);
    }

    /**
     * Obtenir le statut de la permission
     */
    public function getStatusAttribute()
    {
        if (!$this->is_granted) {
            return 'revoked';
        }

        if ($this->isExpired()) {
            return 'expired';
        }

        if ($this->isExpiringSoon()) {
            return 'expiring_soon';
        }

        return 'active';
    }

    /**
     * Ajouter des métadonnées
     */
    public function addMetadata($key, $value)
    {
        $metadata = $this->metadata ?? [];
        $metadata[$key] = $value;
        $this->update(['metadata' => $metadata]);
    }

    /**
     * Obtenir une métadonnée
     */
    public function getMetadata($key, $default = null)
    {
        return $this->metadata[$key] ?? $default;
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($userPermission) {
            if (!$userPermission->granted_at) {
                $userPermission->granted_at = now();
            }
        });

        // Mise à jour automatique du flag is_expired
        static::saving(function ($userPermission) {
            if ($userPermission->expires_at && $userPermission->expires_at <= now()) {
                $userPermission->is_expired = true;
            } else {
                $userPermission->is_expired = false;
            }
        });
    }

    /**
     * Obtenir l'historique des changements
     */
    public function getChangeHistory()
    {
        $history = [];

        // Permission accordée
        if ($this->granted_at) {
            $history[] = [
                'action' => 'granted',
                'date' => $this->granted_at,
                'user' => $this->grantedBy,
                'reason' => $this->reason,
            ];
        }

        // Permission révoquée
        if ($this->revoked_at) {
            $history[] = [
                'action' => 'revoked',
                'date' => $this->revoked_at,
                'user' => $this->revokedBy,
                'reason' => $this->revocation_reason,
            ];
        }

        return collect($history)->sortBy('date');
    }
}
