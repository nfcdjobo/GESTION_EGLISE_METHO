<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'subscriptions';

    protected $fillable = [
        'souscripteur_id',
        'fimeco_id',
        'montant_souscrit',
        'date_souscription',
        'date_echeance',
    ];

    protected $casts = [
        'montant_souscrit' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'reste_a_payer' => 'decimal:2',
        'cible' => 'decimal:2',
        'montant_solde' => 'decimal:2',
        'reste' => 'decimal:2',
        'montant_supplementaire' => 'decimal:2',
        'progression' => 'decimal:2',
        'date_souscription' => 'date',
        'date_echeance' => 'date',
        'statut_global' => 'string',
        'statut' => 'string',
    ];

    protected $attributes = [
        'montant_paye' => 0,
        'reste_a_payer' => 0,
        'cible' => 0,
        'montant_solde' => 0,
        'reste' => 0,
        'montant_supplementaire' => 0,
        'progression' => 0,
        'statut_global' => 'tres_faible',
        'statut' => 'inactive',
    ];

    // Relations

    /**
     * Relation avec le souscripteur (utilisateur)
     */
    public function souscripteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'souscripteur_id');
    }

    /**
     * Relation avec le FIMECO
     */
    public function fimeco(): BelongsTo
    {
        return $this->belongsTo(Fimeco::class, 'fimeco_id');
    }

    /**
     * Relation avec les paiements
     */
    public function payments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class, 'subscription_id');
    }

    /**
     * Relation avec les paiements validés uniquement
     */
    public function paymentsValides(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class, 'subscription_id')
                    ->where('statut', 'valide');
    }

    /**
     * Relation avec les paiements en attente
     */
    public function paymentsEnAttente(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class, 'subscription_id')
                    ->where('statut', 'en_attente');
    }

    // Scopes

    /**
     * Scope pour les souscriptions actives
     */
    public function scopeActives($query)
    {
        return $query->where('statut', '!=', 'inactive');
    }

    /**
     * Scope pour les souscriptions complètement payées
     */
    public function scopeCompletes($query)
    {
        return $query->where('statut', 'completement_payee');
    }

    /**
     * Scope pour les souscriptions partiellement payées
     */
    public function scopePartielles($query)
    {
        return $query->where('statut', 'partiellement_payee');
    }

    /**
     * Scope pour les souscriptions en retard
     */
    public function scopeEnRetard($query)
    {
        return $query->where('date_echeance', '<', now())
                    ->where('statut', '!=', 'completement_payee');
    }

    /**
     * Scope pour filtrer par FIMECO
     */
    public function scopePourFimeco($query, $fimecoId)
    {
        return $query->where('fimeco_id', $fimecoId);
    }

    /**
     * Scope pour filtrer par souscripteur
     */
    public function scopePourSouscripteur($query, $souscripteurId)
    {
        return $query->where('souscripteur_id', $souscripteurId);
    }

    /**
     * Scope pour filtrer par période de souscription
     */
    public function scopePeriodeSouscription($query, $debut = null, $fin = null)
    {
        if ($debut) {
            $query->where('date_souscription', '>=', $debut);
        }
        if ($fin) {
            $query->where('date_souscription', '<=', $fin);
        }
        return $query;
    }

    /**
     * Scope pour filtrer par échéance
     */
    public function scopeEcheanceProche($query, $jours = 30)
    {
        return $query->whereBetween('date_echeance', [now(), now()->addDays($jours)])
                    ->where('statut', '!=', 'completement_payee');
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
     * Vérifie si la souscription est complètement payée
     */
    public function getEstCompleteAttribute(): bool
    {
        return $this->statut === 'completement_payee';
    }

    /**
     * Vérifie si la souscription est en retard
     */
    public function getEnRetardAttribute(): bool
    {
        return $this->date_echeance &&
               $this->date_echeance < now() &&
               $this->statut !== 'completement_payee';
    }

    /**
     * Retourne le nombre de jours de retard
     */
    public function getJoursRetardAttribute(): int
    {
        if (!$this->en_retard) {
            return 0;
        }
        return now()->diffInDays($this->date_echeance);
    }

    /**
     * Retourne le nombre de jours restants avant échéance
     */
    public function getJoursRestantsAttribute(): int
    {
        if (!$this->date_echeance || $this->en_retard) {
            return 0;
        }
        return now()->diffInDays($this->date_echeance, false);
    }

    /**
     * Retourne le montant total des paiements validés
     */
    public function getMontantTotalPayeAttribute(): float
    {
        return $this->paymentsValides()->sum('montant');
    }

    /**
     * Retourne le nombre de paiements
     */
    public function getNombrePaiementsAttribute(): int
    {
        return $this->payments()->count();
    }

    /**
     * Retourne le dernier paiement
     */
    public function getDernierPaiementAttribute(): ?SubscriptionPayment
    {
        return $this->payments()->latest('date_paiement')->first();
    }

    // Méthodes métier

    /**
     * Vérifie si un nouveau paiement peut être ajouté
     */
    public function peutAccepterPaiement(float $montant): bool
    {
        return $this->statut !== 'completement_payee' &&
               $montant > 0 &&
               $montant <= $this->reste_a_payer;
    }

    /**
     * Calcule le montant maximum qu'on peut encore payer
     */
    public function getMontantMaximumPayable(): float
    {
        return max(0, $this->reste_a_payer);
    }

    /**
     * Retourne les statistiques de paiement
     */
    public function getStatistiquesPaiements(): array
    {
        $payments = $this->payments()->get();
        $paymentsValides = $payments->where('statut', 'valide');

        return [
            'nb_paiements_total' => $payments->count(),
            'nb_paiements_valides' => $paymentsValides->count(),
            'nb_paiements_en_attente' => $payments->where('statut', 'en_attente')->count(),
            'nb_paiements_rejetes' => $payments->where('statut', 'rejete')->count(),
            'montant_total_paye' => $paymentsValides->sum('montant'),
            'montant_moyen_paiement' => $paymentsValides->avg('montant') ?? 0,
            'premier_paiement' => $paymentsValides->min('date_paiement'),
            'dernier_paiement' => $paymentsValides->max('date_paiement'),
        ];
    }

    /**
     * Retourne l'historique des paiements formaté
     */
    public function getHistoriquePaiements()
    {
        return $this->payments()
                    ->with('validateur')
                    ->orderBy('date_paiement', 'desc')
                    ->get();
    }

    /**
     * Vérifie si la souscription nécessite une attention particulière
     */
    public function necessiteAttention(): bool
    {
        return $this->en_retard ||
               $this->paymentsEnAttente()->exists() ||
               ($this->date_echeance && $this->jours_restants <= 7 && $this->statut !== 'completement_payee');
    }

    /**
     * Retourne le statut en français
     */
    public function getStatutLibelle(): string
    {
        return match($this->statut) {
            'inactive' => 'Inactive',
            'partiellement_payee' => 'Partiellement payée',
            'completement_payee' => 'Complètement payée',
            default => 'Statut inconnu'
        };
    }

    /**
     * Retourne le statut global en français
     */
    public function getStatutGlobalLibelle(): string
    {
        return match($this->statut_global) {
            'tres_faible' => 'Très faible',
            'en_cours' => 'En cours',
            'presque_atteint' => 'Presque atteint',
            'objectif_atteint' => 'Objectif atteint',
            default => 'Statut inconnu'
        };
    }

    // Événements du modèle

    protected static function booted()
    {
        // Validation avant sauvegarde
        static::saving(function ($subscription) {
            if ($subscription->montant_souscrit <= 0) {
                throw new \InvalidArgumentException('Le montant souscrit doit être supérieur à zéro');
            }
            if ($subscription->date_echeance && $subscription->date_echeance < $subscription->date_souscription) {
                throw new \InvalidArgumentException('La date d\'échéance ne peut pas être antérieure à la date de souscription');
            }
        });

        // Mise à jour automatique après sauvegarde
        static::saved(function ($subscription) {
            // Les triggers PostgreSQL s'occupent des calculs automatiques
            // Mais on peut ajouter des actions supplémentaires ici si nécessaire
        });
    }
}
