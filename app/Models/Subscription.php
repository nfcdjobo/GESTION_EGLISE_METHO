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

    /**
     * Relation avec les paiements supplémentaires
     */
    public function paymentsSupplementaires(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class, 'subscription_id')
                    ->where('statut', 'valide')
                    ->whereRaw('(
                        SELECT COALESCE(SUM(sp2.montant), 0)
                        FROM subscription_payments sp2
                        WHERE sp2.subscription_id = subscription_payments.subscription_id
                        AND sp2.statut = \'valide\'
                        AND sp2.date_paiement <= subscription_payments.date_paiement
                    ) > (
                        SELECT montant_souscrit
                        FROM subscriptions s
                        WHERE s.id = subscription_payments.subscription_id
                    )');
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

    /**
     * Scope pour les souscriptions avec paiements supplémentaires
     */
    public function scopeAvecPaiementsSupplementaires($query)
    {
        return $query->where('montant_supplementaire', '>', 0);
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
     * Vérifie si la souscription est complètement payée - VERSION MODIFIÉE
     */
    public function getEstCompleteAttribute(): bool
    {
        return $this->montant_paye >= $this->montant_souscrit;
    }

    /**
     * Vérifie si la souscription a des paiements supplémentaires
     */
    public function getAPaiementsSupplementairesAttribute(): bool
    {
        return $this->montant_supplementaire > 0;
    }

    /**
     * Vérifie si la souscription est en retard
     */
    public function getEnRetardAttribute(): bool
    {
        return $this->date_echeance &&
               $this->date_echeance < now() &&
               !$this->est_complete;
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
     * Retourne le montant de base encore à payer (sans dépasser la souscription)
     */
    public function getMontantBaseRestantAttribute(): float
    {
        return max(0, $this->montant_souscrit - $this->montant_paye);
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

    // Méthodes métier - VERSIONS MODIFIÉES

    /**
     * Vérifie si un nouveau paiement peut être ajouté - VERSION MODIFIÉE
     */
    public function peutAccepterPaiement(float $montant): bool
    {
        // On accepte maintenant tous les paiements, même au-delà de la souscription
        return $montant > 0 && $this->fimeco && $this->fimeco->statut === 'active';
    }

    /**
     * Calcule le montant maximum qu'on peut encore payer pour la souscription de base
     */
    public function getMontantMaximumPayableBase(): float
    {
        return max(0, $this->montant_souscrit - $this->montant_paye);
    }

    /**
     * Indique s'il n'y a plus de limite de paiement (pour les supplémentaires)
     */
    public function peutRecevoirPaiementsSupplementaires(): bool
    {
        return $this->fimeco && $this->fimeco->statut === 'active';
    }

    /**
     * Retourne les statistiques de paiement - VERSION ENRICHIE
     */
    public function getStatistiquesPaiements(): array
    {
        $payments = $this->payments()->get();
        $paymentsValides = $payments->where('statut', 'valide');
        $paymentsSupplementaires = $paymentsValides->filter(function ($payment) {
            return $payment->est_paiement_supplementaire;
        });

        return [
            'nb_paiements_total' => $payments->count(),
            'nb_paiements_valides' => $paymentsValides->count(),
            'nb_paiements_en_attente' => $payments->where('statut', 'en_attente')->count(),
            'nb_paiements_rejetes' => $payments->where('statut', 'rejete')->count(),
            'montant_total_paye' => $paymentsValides->sum('montant'),
            'montant_moyen_paiement' => $paymentsValides->avg('montant') ?? 0,
            'premier_paiement' => $paymentsValides->min('date_paiement'),
            'dernier_paiement' => $paymentsValides->max('date_paiement'),
            // Statistiques supplémentaires
            'nb_paiements_supplementaires' => $paymentsSupplementaires->count(),
            'montant_total_supplementaire' => $this->montant_supplementaire,
            'montant_base_paye' => min($this->montant_paye, $this->montant_souscrit),
            'est_au_dela_souscription' => $this->montant_paye > $this->montant_souscrit,
            'taux_depassement' => $this->montant_souscrit > 0 ?
                max(0, (($this->montant_paye - $this->montant_souscrit) / $this->montant_souscrit) * 100) : 0,
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
     * Vérifie si la souscription nécessite une attention particulière - VERSION MODIFIÉE
     */
    public function necessiteAttention(): bool
    {
        return $this->en_retard ||
               $this->paymentsEnAttente()->exists() ||
               ($this->date_echeance && $this->jours_restants <= 7 && !$this->est_complete) ||
               ($this->montant_paye > $this->montant_souscrit * 2); // Alerte si dépassement important
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

    /**
     * Calcule le montant suggéré pour le prochain paiement
     */
    public function getMontantSuggereProchainPaiement(): array
    {
        $suggestions = [];

        // Montant pour compléter la souscription de base
        $resteBase = $this->getMontantBaseRestantAttribute();
        if ($resteBase > 0) {
            $suggestions['completion_base'] = [
                'montant' => $resteBase,
                'description' => 'Pour compléter la souscription de base',
                'type' => 'completion'
            ];
        }

        // Suggestions de montants supplémentaires si la base est complète
        if ($resteBase == 0) {
            $montantsSuggeres = [5000, 10000, 25000, 50000, 100000];
            foreach ($montantsSuggeres as $montant) {
                $suggestions["supplementaire_{$montant}"] = [
                    'montant' => $montant,
                    'description' => "Paiement supplémentaire de " . number_format($montant, 0, ',', ' ') . " FCFA",
                    'type' => 'supplementaire'
                ];
            }
        }

        return $suggestions;
    }

    /**
     * Retourne l'impact d'un potentiel paiement
     */
    public function getImpactPaiement(float $montant): array
    {
        $resteBase = $this->getMontantBaseRestantAttribute();
        $montantBase = min($montant, $resteBase);
        $montantSupplementaire = max(0, $montant - $resteBase);

        return [
            'montant_total' => $montant,
            'montant_pour_base' => $montantBase,
            'montant_supplementaire' => $montantSupplementaire,
            'nouveau_montant_paye' => $this->montant_paye + $montant,
            'nouveau_reste_base' => max(0, $resteBase - $montant),
            'nouveau_montant_supplementaire_total' => $this->montant_supplementaire + $montantSupplementaire,
            'sera_complete' => ($this->montant_paye + $montant) >= $this->montant_souscrit,
            'nouvelle_progression' => $this->montant_souscrit > 0 ?
                min(100, (($this->montant_paye + $montantBase) / $this->montant_souscrit) * 100) : 0,
            'taux_depassement' => $montantSupplementaire > 0 && $this->montant_souscrit > 0 ?
                (($this->montant_supplementaire + $montantSupplementaire) / $this->montant_souscrit) * 100 : 0,
        ];
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
