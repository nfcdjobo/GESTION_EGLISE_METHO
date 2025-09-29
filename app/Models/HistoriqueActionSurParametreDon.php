<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoriqueActionSurParametreDon extends Model
{
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     */
    protected $table = 'historiques_actions_sur_parametres_dons';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parametre_don_id',
        'action',
        'effectuer_par',
        'infos',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'infos' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Les actions disponibles.
     */
    public const ACTION_AJOUT = 'ajout';
    public const ACTION_MISE_A_JOUR = 'mise_a_jour';
    public const ACTION_SUPPRESSION = 'suppression';
    public const ACTION_PUBLICATION = 'publication';

    public const ACTIONS = [
        self::ACTION_AJOUT => 'Ajout',
        self::ACTION_MISE_A_JOUR => 'Mise à jour',
        self::ACTION_SUPPRESSION => 'Suppression',
        self::ACTION_PUBLICATION => 'Publication',
    ];

    /**
     * Relation avec le paramètre de don.
     */
    public function parametreDon(): BelongsTo
    {
        return $this->belongsTo(ParametreDon::class, 'parametre_don_id');
    }

    /**
     * Relation avec l'utilisateur qui a effectué l'action.
     */
    public function effectuerPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'effectuer_par');
    }

    /**
     * Scope pour filtrer par action.
     */
    public function scopeParAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour filtrer par utilisateur.
     */
    public function scopeParUtilisateur($query, string $userId)
    {
        return $query->where('effectuer_par', $userId);
    }

    /**
     * Scope pour filtrer par paramètre de don.
     */
    public function scopeParParametreDon($query, string $parametreDonId)
    {
        return $query->where('parametre_don_id', $parametreDonId);
    }

    /**
     * Scope pour les actions récentes.
     */
    public function scopeRecentes($query, int $jours = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($jours));
    }

    /**
     * Scope pour ordonner par date décroissante.
     */
    public function scopeRecentsEnPremier($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Retourne le libellé de l'action.
     */
    public function getActionLibelleAttribute(): string
    {
        return self::ACTIONS[$this->action] ?? $this->action;
    }

    /**
     * Vérifie si l'action est une création.
     */
    public function estUneCreation(): bool
    {
        return $this->action === self::ACTION_AJOUT;
    }

    /**
     * Vérifie si l'action est une modification.
     */
    public function estUneModification(): bool
    {
        return $this->action === self::ACTION_MISE_A_JOUR;
    }

    /**
     * Vérifie si l'action est une suppression.
     */
    public function estUneSuppression(): bool
    {
        return $this->action === self::ACTION_SUPPRESSION;
    }

    /**
     * Vérifie si l'action est une publication.
     */
    public function estUnePublication(): bool
    {
        return $this->action === self::ACTION_PUBLICATION;
    }

    /**
     * Retourne une description formatée de l'action.
     */
    public function getDescriptionAttribute(): string
    {
        $utilisateur = $this->effectuerPar?->name ?? 'Utilisateur inconnu';
        $action = $this->action_libelle;
        $date = $this->created_at->format('d/m/Y à H:i');

        return "{$utilisateur} a effectué une {$action} le {$date}";
    }

    /**
     * Enregistre une nouvelle action dans l'historique.
     */
    public static function enregistrerAction(
        string $parametreDonId,
        string $action,
        string $effectuerPar,
        array $infos = null
    ): self {
        return self::create([
            'parametre_don_id' => $parametreDonId,
            'action' => $action,
            'effectuer_par' => $effectuerPar,
            'infos' => $infos,
        ]);
    }
}
