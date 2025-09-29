<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Don extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'dons';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'parametre_fond_id',
        'nom_donateur',
        'prenom_donateur',
        'telephone_1',
        'telephone_2',
        'montant',
        'devise',
        'preuve',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'montant' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Les devises supportées.
     */
    public const DEVISE_XOF = 'XOF'; // Franc CFA
    public const DEVISE_EUR = 'EUR'; // Euro
    public const DEVISE_USD = 'USD'; // Dollar US

    public const DEVISES = [
        self::DEVISE_XOF => 'Franc CFA (XOF)',
        self::DEVISE_EUR => 'Euro (EUR)',
        self::DEVISE_USD => 'Dollar US (USD)',
    ];

    /**
     * Relation avec le paramètre de don.
     */
    public function parametreDon(): BelongsTo
    {
        return $this->belongsTo(ParametreDon::class, 'parametre_fond_id');
    }

    /**
     * Scope pour filtrer par montant minimum.
     */
    public function scopeMontantMinimum($query, float $montant)
    {
        return $query->where('montant', '>=', $montant);
    }

    /**
     * Scope pour filtrer par montant maximum.
     */
    public function scopeMontantMaximum($query, float $montant)
    {
        return $query->where('montant', '<=', $montant);
    }

    /**
     * Scope pour filtrer entre deux montants.
     */
    public function scopeEntreMontants($query, float $min, float $max)
    {
        return $query->whereBetween('montant', [$min, $max]);
    }

    /**
     * Scope pour filtrer par devise.
     */
    public function scopeParDevise($query, string $devise)
    {
        return $query->where('devise', $devise);
    }

    /**
     * Scope pour filtrer par donateur.
     */
    public function scopeParDonateur($query, string $nom = null, string $prenom = null)
    {
        $query = $query->when($nom, function ($q) use ($nom) {
            return $q->where('nom_donateur', 'like', "%{$nom}%");
        });

        return $query->when($prenom, function ($q) use ($prenom) {
            return $q->where('prenom_donateur', 'like', "%{$prenom}%");
        });
    }

    /**
     * Scope pour les dons récents (derniers 30 jours par défaut).
     */
    public function scopeRecents($query, int $jours = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($jours));
    }

    /**
     * Retourne le nom complet du donateur.
     */
    public function getNomCompletAttribute(): string
    {
        return trim($this->prenom_donateur . ' ' . $this->nom_donateur);
    }

    /**
     * Retourne le montant formaté avec la devise.
     */
    public function getMontantFormateAttribute(): string
    {
        return number_format($this->montant, 2, ',', ' ') . ' ' . $this->devise;
    }

    /**
     * Retourne le libellé de la devise.
     */
    public function getDeviseLibelleAttribute(): string
    {
        return self::DEVISES[$this->devise] ?? $this->devise;
    }

    /**
     * Vérifie si une preuve est fournie.
     */
    public function aUnePreuve(): bool
    {
        return !empty($this->preuve);
    }

    /**
     * Retourne l'URL complète de la preuve.
     */
    public function getUrlPreuveAttribute(): ?string
    {
        if (!$this->aUnePreuve()) {
            return null;
        }

        return asset('storage/' . $this->preuve);
    }
}
