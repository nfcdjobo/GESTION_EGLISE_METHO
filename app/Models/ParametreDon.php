<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParametreDon extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'parametres_dons';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'operateur',
        'type',
        'numero_compte',
        'logo',
        'qrcode',
        'statut',
        'publier',
        'publier_par',
        'creer_par',
        'modifier_par',
        'supprimer_par',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'statut' => 'boolean',
        'publier' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Les types de paramètres disponibles.
     */
    public const TYPE_VIREMENT_BANCAIRE = 'virement_bancaire';
    public const TYPE_CARTE_BANCAIRE = 'carte_bancaire';
    public const TYPE_MOBILE_MONEY = 'mobile_money';

    public const TYPES = [
        self::TYPE_VIREMENT_BANCAIRE,
        self::TYPE_CARTE_BANCAIRE,
        self::TYPE_MOBILE_MONEY,
    ];

    /**
     * Relation avec l'utilisateur qui a publié.
     */
    public function publierPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'publier_par');
    }

    /**
     * Relation avec l'utilisateur qui a créé.
     */
    public function creerPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creer_par');
    }

    /**
     * Relation avec l'utilisateur qui a modifié.
     */
    public function modifierPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modifier_par');
    }

    /**
     * Relation avec l'utilisateur qui a supprimé.
     */
    public function supprimerPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supprimer_par');
    }

    /**
     * Relation avec les dons.
     */
    public function dons(): HasMany
    {
        return $this->hasMany(Don::class, 'parametre_fond_id');
    }

    /**
     * Relation avec l'historique des actions.
     */
    public function historiques(): HasMany
    {
        return $this->hasMany(HistoriqueActionSurParametreDon::class, 'parametre_don_id');
    }

    /**
     * Scope pour les paramètres actifs.
     */
    public function scopeActif($query)
    {
        return $query->where('statut', true);
    }

    /**
     * Scope pour les paramètres publiés.
     */
    public function scopePublie($query)
    {
        return $query->where('publier', true);
    }

    /**
     * Scope pour filtrer par type.
     */
    public function scopeParType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope pour filtrer par opérateur.
     */
    public function scopeParOperateur($query, string $operateur)
    {
        return $query->where('operateur', $operateur);
    }


    /**
     * Scope pour les paramètres actifs et publiés.
     */
    public function scopeActifEtPublie($query)
    {
        return $query->where('statut', true)->where('publier', true);
    }

    /**
     * Vérifie si le paramètre est actif.
     */
    public function estActif(): bool
    {
        return $this->statut === true;
    }

    /**
     * Vérifie si le paramètre est publié.
     */
    public function estPublie(): bool
    {
        return $this->publier === true;
    }

    /**
     * Retourne le libellé du type.
     */
    public function getTypeLibelleAttribute(): string
    {
        return match($this->type) {
            self::TYPE_VIREMENT_BANCAIRE => 'Virement Bancaire',
            self::TYPE_CARTE_BANCAIRE => 'Carte Bancaire',
            self::TYPE_MOBILE_MONEY => 'Mobile Money',
            default => 'Type inconnu'
        };
    }


    // Dans le modèle
public static function getTypeLibelle(string $type): string
{
    return match($type) {
        self::TYPE_VIREMENT_BANCAIRE => 'Virement Bancaire',
        self::TYPE_CARTE_BANCAIRE => 'Carte Bancaire',
        self::TYPE_MOBILE_MONEY => 'Mobile Money',
        default => 'Type inconnu'
    };
}
}



