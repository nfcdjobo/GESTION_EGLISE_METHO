<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionAuditLog extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'action',
        'model_type',
        'model_id',
        'user_id',
        'target_user_id',
        'changes',
        'original',
        'ip_address',
        'user_agent',
        'session_id',
        'context',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'changes' => 'array',
        'original' => 'array',
        'context' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Actions possibles
     */
    const ACTIONS = [
        'created' => 'Créé',
        'updated' => 'Mis à jour',
        'deleted' => 'Supprimé',
        'restored' => 'Restauré',
        'permission_granted' => 'Permission accordée',
        'permission_revoked' => 'Permission révoquée',
        'permission_updated' => 'Permission mise à jour',
        'permission_restored' => 'Permission restaurée',
        'permission_removed' => 'Permission retirée',
        'role_assigned' => 'Rôle attribué',
        'role_removed' => 'Rôle retiré',
        'role_updated' => 'Rôle mis à jour',
        'role_reactivated' => 'Rôle réactivé',
        'role_deactivated' => 'Rôle désactivé',
    ];

    /**
     * Relation avec l'membres qui a effectué l'action
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec l'membres cible
     */
    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    /**
     * Obtenir le modèle lié
     */
    public function getModelAttribute()
    {
        if (!$this->model_type || !$this->model_id) {
            return null;
        }

        $modelClass = "App\\Models\\{$this->model_type}";
        if (!class_exists($modelClass)) {
            return null;
        }

        return $modelClass::find($this->model_id);
    }

    /**
     * Obtenir le nom de l'action formaté
     */
    public function getActionNameAttribute()
    {
        return self::ACTIONS[$this->action] ?? $this->action;
    }

    /**
     * Obtenir la description de l'action
     */
    public function getDescriptionAttribute()
    {
        $user = $this->user ? $this->user->nom_complet : 'Système';
        $action = $this->action_name;

        switch ($this->action) {
            case 'permission_granted':
                $permission = Permission::find($this->changes['permission_id'] ?? null);
                $targetUser = $this->targetUser;
                return "{$user} a accordé la permission '{$permission?->name}' à {$targetUser?->nom_complet}";

            case 'permission_revoked':
                $permission = Permission::find($this->changes['permission_id'] ?? null);
                $targetUser = $this->targetUser;
                return "{$user} a révoqué la permission '{$permission?->name}' de {$targetUser?->nom_complet}";

            case 'role_assigned':
                $role = Role::find($this->changes['role_id'] ?? null);
                $targetUser = $this->targetUser;
                return "{$user} a attribué le rôle '{$role?->name}' à {$targetUser?->nom_complet}";

            case 'role_removed':
                $role = Role::find($this->original['role_id'] ?? null);
                $targetUser = $this->targetUser;
                return "{$user} a retiré le rôle '{$role?->name}' de {$targetUser?->nom_complet}";

            default:
                return "{$user} a effectué l'action: {$action} sur {$this->model_type} #{$this->model_id}";
        }
    }

    /**
     * Scope pour filtrer par action
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour filtrer par type de modèle
     */
    public function scopeByModelType($query, $modelType)
    {
        return $query->where('model_type', $modelType);
    }

    /**
     * Scope pour filtrer par membres
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour filtrer par membres cible
     */
    public function scopeByTargetUser($query, $userId)
    {
        return $query->where('target_user_id', $userId);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeInPeriod($query, $startDate, $endDate = null)
    {
        $query->where('created_at', '>=', $startDate);

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        return $query;
    }

    /**
     * Scope pour les actions récentes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Obtenir les changements formatés
     */
    public function getFormattedChangesAttribute()
    {
        if (!$this->changes) {
            return [];
        }

        $formatted = [];
        foreach ($this->changes as $key => $value) {
            $formatted[] = [
                'field' => $key,
                'old_value' => $this->original[$key] ?? null,
                'new_value' => $value,
            ];
        }

        return $formatted;
    }

    /**
     * Créer un log depuis une action
     */
    public static function logAction($action, $model, $changes = null, $context = null)
    {
        return static::create([
            'action' => $action,
            'model_type' => class_basename($model),
            'model_id' => $model->getKey(),
            'user_id' => auth()->id(),
            'changes' => $changes ?? $model->getChanges(),
            'original' => $model->getOriginal(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
            'context' => $context,
        ]);
    }

    /**
     * Obtenir les statistiques des logs
     */
    public static function getStatistics($period = 30)
    {
        $startDate = now()->subDays($period);

        return [
            'total_actions' => static::where('created_at', '>=', $startDate)->count(),
            'by_action' => static::where('created_at', '>=', $startDate)
                ->selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action'),
            'by_model' => static::where('created_at', '>=', $startDate)
                ->selectRaw('model_type, COUNT(*) as count')
                ->groupBy('model_type')
                ->pluck('count', 'model_type'),
            'most_active_users' => static::where('created_at', '>=', $startDate)
                ->whereNotNull('user_id')
                ->selectRaw('user_id, COUNT(*) as count')
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->with('user')
                ->get(),
            'recent_critical_actions' => static::whereIn('action', [
                    'deleted', 'permission_revoked', 'role_removed'
                ])
                ->where('created_at', '>=', $startDate)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->with(['user', 'targetUser'])
                ->get(),
        ];
    }
}
