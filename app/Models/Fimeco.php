<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fimeco extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'fimecos';

    protected $fillable = [
        'responsable_id',
        'nom',
        'description',
        'debut',
        'fin',
        'cible',
        'statut',
    ];

    protected $casts = [
        'debut' => 'date',
        'fin' => 'date',
        'cible' => 'decimal:2',
        'montant_solde' => 'decimal:2',
        'reste' => 'decimal:2',
        'montant_supplementaire' => 'decimal:2',
        'progression' => 'decimal:2',
        'statut_global' => 'string',
        'statut' => 'string',
    ];

    protected $attributes = [
        'montant_solde' => 0,
        'reste' => 0,
        'montant_supplementaire' => 0,
        'progression' => 0,
        'statut_global' => 'tres_faible',
        'statut' => 'active',
    ];

    // Relations

    /**
     * Relation avec l'utilisateur responsable
     */
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    /**
     * Relation avec les souscriptions
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'fimeco_id');
    }

    /**
     * Relation avec les souscriptions actives uniquement
     */
    public function subscriptionsActives(): HasMany
    {
        return $this->hasMany(Subscription::class, 'fimeco_id')->where('statut', '!=', 'inactive');
    }

    /**
     * Relation avec les souscriptions complètement payées
     */
    public function subscriptionsCompletes(): HasMany
    {
        return $this->hasMany(Subscription::class, 'fimeco_id')->where('statut', 'completement_payee');
    }

    // Scopes

    /**
     * Scope pour les FIMECO actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'active');
    }

    /**
     * Scope pour les FIMECO avec objectif atteint
     */
    public function scopeObjectifAtteint($query)
    {
        return $query->where('statut_global', 'objectif_atteint');
    }

    /**
     * Scope pour les FIMECO en cours
     */
    public function scopeEnCours($query)
    {
        return $query->whereIn('statut_global', ['en_cours', 'presque_atteint']);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopePeriode($query, $debut = null, $fin = null)
    {
        if ($debut) {
            $query->where('debut', '>=', $debut);
        }
        if ($fin) {
            $query->where('fin', '<=', $fin);
        }
        return $query;
    }

    /**
     * Scope pour recherche textuelle
     */
    public function scopeRecherche($query, $terme)
    {
        return $query->where('nom', 'ILIKE', "%{$terme}%")->orWhere('description', 'ILIKE', "%{$terme}%");
    }

    // Accesseurs

    /**
     * Retourne le pourcentage de progression formaté
     */
    public function getProgressionFormatteeAttribute(): string
    {
        return number_format($this->progression, 2) . '%';
    }

    /**
     * Vérifie si l'objectif est atteint
     */
    public function getObjectifAtteintAttribute(): bool
    {
        return $this->statut_global === 'objectif_atteint';
    }

    /**
     * Retourne le nombre de jours restants
     */
    public function getJoursRestantsAttribute(): int
    {
        return max(0, now()->diffInDays($this->fin, false));
    }

    /**
     * Vérifie si le FIMECO est en retard
     */
    public function getEnRetardAttribute(): bool
    {
        return $this->fin < now() && $this->statut_global !== 'objectif_atteint';
    }

    // Méthodes métier

    /**
     * Calcule les statistiques complètes du FIMECO
     */
    public function getStatistiques(): array
    {
        $subscriptions = $this->subscriptions()->get();

        return [
            'nb_souscriptions_total' => $subscriptions->count(),
            'nb_souscriptions_actives' => $subscriptions->where('statut', '!=', 'inactive')->count(),
            'nb_souscriptions_completes' => $subscriptions->where('statut', 'completement_payee')->count(),
            'nb_souscriptions_partielles' => $subscriptions->where('statut', 'partiellement_payee')->count(),
            'nb_souscriptions_inactives' => $subscriptions->where('statut', 'inactive')->count(),
            'montant_total_souscrit' => $subscriptions->sum('montant_souscrit'),
            'montant_total_paye' => $subscriptions->sum('montant_paye'),
            'progression_moyenne_souscriptions' => $subscriptions->avg('progression') ?? 0,
            'nb_souscriptions_en_retard' => $subscriptions->filter(function ($s) {
                return $s->date_echeance && $s->date_echeance < now() && $s->statut !== 'completement_payee';
            })->count(),
        ];
    }

    /**
     * Vérifie si une nouvelle souscription peut être créée
     */
    public function peutAccepterNouvellesSouscriptions(): bool
    {
        return $this->statut === 'active' &&
               $this->fin >= now() &&
               $this->statut_global !== 'objectif_atteint';
    }

    /**
     * Retourne le montant encore disponible pour les souscriptions
     */
    public function getMontantDisponible(): float
    {
        return max(0, $this->cible - $this->subscriptions()->sum('montant_souscrit'));
    }

    /**
     * Retourne les paiements en attente pour ce FIMECO
     */
    public function getPaiementsEnAttente()
    {
        return SubscriptionPayment::whereHas('subscription', function ($query) {
            $query->where('fimeco_id', $this->id);
        })->where('statut', 'en_attente')->get();
    }

    // Événements du modèle

    protected static function booted()
    {
        // Validation avant sauvegarde
        static::saving(function ($fimeco) {
            if ($fimeco->fin < $fimeco->debut) {
                throw new \InvalidArgumentException('La date de fin ne peut pas être antérieure à la date de début');
            }
            if ($fimeco->cible <= 0) {
                throw new \InvalidArgumentException('La cible doit être supérieure à zéro');
            }
        });
    }
}
