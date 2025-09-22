<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPayment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'subscription_payments';

    protected $fillable = [
        'subscription_id',
        'montant',
        'ancien_reste',
        'nouveau_reste',
        'type_paiement',
        'reference_paiement',
        'date_paiement',
        'commentaire',
        'subscription_version_at_payment',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'ancien_reste' => 'decimal:2',
        'nouveau_reste' => 'decimal:2',
        'date_paiement' => 'datetime',
        'date_validation' => 'datetime',
        'date_paiement_only' => 'date',
        'subscription_version_at_payment' => 'integer',
        'type_paiement' => 'string',
        'statut' => 'string',
    ];

    protected $attributes = [
        'statut' => 'en_attente',
    ];

    // Relations

    /**
     * Relation avec la souscription
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    /**
     * Relation avec le validateur (utilisateur)
     */
    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

    // Scopes

    /**
     * Scope pour les paiements validés
     */
    public function scopeValides($query)
    {
        return $query->where('statut', 'valide');
    }

    /**
     * Scope pour les paiements en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les paiements rejetés
     */
    public function scopeRejetes($query)
    {
        return $query->where('statut', 'rejete');
    }

    /**
     * Scope pour filtrer par type de paiement
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_paiement', $type);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopePeriode($query, $debut = null, $fin = null)
    {
        if ($debut) {
            $query->where('date_paiement', '>=', $debut);
        }
        if ($fin) {
            $query->where('date_paiement', '<=', $fin);
        }
        return $query;
    }

    /**
     * Scope pour les paiements du jour
     */
    public function scopeAujourdhui($query)
    {
        return $query->whereDate('date_paiement', today());
    }

    /**
     * Scope pour filtrer par validateur
     */
    public function scopeParValidateur($query, $validateurId)
    {
        return $query->where('validateur_id', $validateurId);
    }

    /**
     * Scope pour les paiements avec référence
     */
    public function scopeAvecReference($query)
    {
        return $query->whereNotNull('reference_paiement');
    }

    // Accesseurs

    /**
     * Retourne le montant formaté
     */
    public function getMontantFormatteAttribute(): string
    {
        return number_format($this->montant, 2, ',', ' ') . ' FCFA';
    }

    /**
     * Vérifie si le paiement est validé
     */
    public function getEstValideAttribute(): bool
    {
        return $this->statut === 'valide';
    }

    /**
     * Vérifie si le paiement est en attente
     */
    public function getEstEnAttenteAttribute(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifie si le paiement est rejeté
     */
    public function getEstRejeteAttribute(): bool
    {
        return $this->statut === 'rejete';
    }

    /**
     * Retourne l'âge du paiement en jours
     */
    public function getAgeJoursAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Vérifie si le paiement nécessite une référence
     */
    public function getNecessiteReferenceAttribute(): bool
    {
        return in_array($this->type_paiement, ['cheque', 'virement', 'carte']);
    }

    /**
     * Retourne le délai de validation en heures
     */
    public function getDelaiValidationHeuresAttribute(): ?int
    {
        if (!$this->date_validation) {
            return null;
        }
        return $this->created_at->diffInHours($this->date_validation);
    }

    /**
 * Calcule le délai de validation
 */
public function getDelaiValidation(): ?int
{
    if ($this->statut === 'en_attente') {
        return now()->diffInHours($this->created_at);
    }

    if ($this->date_validation) {
        return $this->created_at->diffInHours($this->date_validation);
    }

    return null;
}


/**
 * Vérifie si le paiement peut être rejeté
 */
public function peutEtreRejete(): bool
{
    return $this->statut === 'en_attente' && !$this->trashed();
}


/**
 * Vérifie si la validation peut être annulée
 */
public function peutAnnulerValidation(): bool
{
    return $this->statut !== 'en_attente' &&
           $this->date_validation &&
           $this->date_validation->diffInHours(now()) <= 24; // Limite de 24h
}


/**
 * Vérifie si le paiement peut être validé
 */
public function peutEtreValide(): bool
{
    return $this->statut === 'en_attente' &&
           $this->subscription &&
           !$this->trashed();
}

    // Méthodes métier

    /**
     * Valide le paiement
     */
    public function valider(User $validateur, string $commentaire = null): bool
    {
        if ($this->statut !== 'en_attente') {
            throw new \InvalidArgumentException('Seuls les paiements en attente peuvent être validés');
        }

        $this->update([
            'statut' => 'valide',
            'validateur_id' => $validateur->id,
            'date_validation' => now(),
            'commentaire' => $commentaire,
        ]);

        return true;
    }

    /**
     * Rejette le paiement
     */
    public function rejeter(User $validateur, string $commentaire): bool
    {
        if ($this->statut !== 'en_attente') {
            throw new \InvalidArgumentException('Seuls les paiements en attente peuvent être rejetés');
        }

        if (empty($commentaire)) {
            throw new \InvalidArgumentException('Un commentaire est obligatoire pour rejeter un paiement');
        }

        $this->update([
            'statut' => 'rejete',
            'validateur_id' => $validateur->id,
            'date_validation' => now(),
            'commentaire' => $commentaire,
        ]);

        return true;
    }

    /**
     * Vérifie si le paiement peut être modifié
     */
    public function peutEtreModifie(): bool
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Vérifie si le paiement peut être supprimé
     */
    public function peutEtreSupprime(): bool
    {
        return $this->statut !== 'valide' || $this->age_jours <= 7;
    }

    /**
     * Retourne le libellé du type de paiement
     */
    public function getTypePaiementLibelle(): string
    {
        return match($this->type_paiement) {
            'especes' => 'Espèces',
            'cheque' => 'Chèque',
            'virement' => 'Virement bancaire',
            'carte' => 'Carte bancaire',
            'mobile_money' => 'Mobile Money',
            default => 'Type inconnu'
        };
    }

    /**
     * Retourne le libellé du statut
     */
    public function getStatutLibelle(): string
    {
        return match($this->statut) {
            'en_attente' => 'En attente de validation',
            'valide' => 'Validé',
            'rejete' => 'Rejeté',
            default => 'Statut inconnu'
        };
    }

    /**
     * Retourne les informations de validation
     */
    public function getInfosValidation(): array
    {
        return [
            'statut' => $this->statut,
            'statut_libelle' => $this->getStatutLibelle(),
            'validateur' => $this->validateur?->nom,
            'date_validation' => $this->date_validation?->format('d/m/Y H:i'),
            'delai_validation_heures' => $this->delai_validation_heures,
            'commentaire' => $this->commentaire,
        ];
    }

    /**
     * Génère un récépissé de paiement
     */
    public function genererRecepisse(): array
    {
        return [
            'id_paiement' => $this->id,
            'reference' => $this->reference_paiement,
            'montant' => $this->montant_formatte,
            'type_paiement' => $this->getTypePaiementLibelle(),
            'date_paiement' => $this->date_paiement->format('d/m/Y H:i'),
            'statut' => $this->getStatutLibelle(),
            'souscripteur' => $this->subscription->souscripteur->nom,
            'fimeco' => $this->subscription->fimeco->nom,
            'ancien_reste' => number_format($this->ancien_reste, 2, ',', ' ') . ' FCFA',
            'nouveau_reste' => number_format($this->nouveau_reste, 2, ',', ' ') . ' FCFA',
        ];
    }

    // Méthodes statiques

    /**
     * Retourne les statistiques globales des paiements
     */
    public static function getStatistiquesGlobales(array $filtres = []): array
    {
        $query = static::query();

        // Application des filtres
        if (isset($filtres['date_debut'])) {
            $query->where('date_paiement', '>=', $filtres['date_debut']);
        }
        if (isset($filtres['date_fin'])) {
            $query->where('date_paiement', '<=', $filtres['date_fin']);
        }
        if (isset($filtres['type_paiement'])) {
            $query->where('type_paiement', $filtres['type_paiement']);
        }
        if (isset($filtres['statut'])) {
            $query->where('statut', $filtres['statut']);
        }

        $payments = $query->get();
        $paymentsValides = $payments->where('statut', 'valide');

        return [
            'nb_paiements_total' => $payments->count(),
            'nb_paiements_valides' => $paymentsValides->count(),
            'nb_paiements_en_attente' => $payments->where('statut', 'en_attente')->count(),
            'nb_paiements_rejetes' => $payments->where('statut', 'rejete')->count(),
            'montant_total' => $paymentsValides->sum('montant'),
            'montant_moyen' => $paymentsValides->avg('montant') ?? 0,
            'montant_min' => $paymentsValides->min('montant') ?? 0,
            'montant_max' => $paymentsValides->max('montant') ?? 0,
            'repartition_par_type' => $paymentsValides->groupBy('type_paiement')
                ->map(fn($group) => $group->count()),
            'repartition_par_statut' => $payments->groupBy('statut')
                ->map(fn($group) => $group->count()),
        ];
    }

    /**
     * Détecte les paiements suspects (doublons potentiels)
     */
    public static function detecterDoublons(): array
    {
        return static::selectRaw('
                subscription_id,
                montant,
                type_paiement,
                DATE(date_paiement) as date_paiement_jour,
                COUNT(*) as nb_paiements
            ')
            ->where('statut', 'valide')
            ->groupBy('subscription_id', 'montant', 'type_paiement', 'date_paiement_jour')
            ->having('nb_paiements', '>', 1)
            ->with(['subscription.souscripteur', 'subscription.fimeco'])
            ->get()
            ->toArray();
    }

    /**
     * Retourne les paiements nécessitant une validation urgente
     */
    public static function getValidationsUrgentes(int $heures = 24): array
    {
        return static::enAttente()
            ->where('created_at', '<=', now()->subHours($heures))
            ->with(['subscription.souscripteur', 'subscription.fimeco'])
            ->orderBy('created_at')
            ->get()
            ->toArray();
    }

    // Événements du modèle

    protected static function booted()
    {
        // Validation avant sauvegarde
        static::saving(function ($payment) {
            if ($payment->montant <= 0) {
                throw new \InvalidArgumentException('Le montant du paiement doit être supérieur à zéro');
            }

            // Vérification de la référence pour certains types de paiement
            if ($payment->necessite_reference && empty($payment->reference_paiement)) {
                throw new \InvalidArgumentException(
                    "Une référence est obligatoire pour les paiements de type {$payment->type_paiement}"
                );
            }

            // Vérification de la cohérence des calculs
            if ($payment->ancien_reste && $payment->nouveau_reste) {
                $difference = $payment->ancien_reste - $payment->nouveau_reste;
                if (abs($difference - $payment->montant) > 0.01) {
                    throw new \InvalidArgumentException('Incohérence dans les calculs de reste');
                }
            }
        });

        // Actions après sauvegarde
        static::saved(function ($payment) {
            // Log de l'activité
            try {
                \Log::info("Paiement {$payment->statut}", [
                    'payment_id' => $payment->id,
                    'subscription_id' => $payment->subscription_id,
                    'montant' => $payment->montant,
                    'type' => $payment->type_paiement,
                    'statut' => $payment->statut,
                ]);
            } catch (\Exception $e) {
                // Ignorer les erreurs de log
            }
        });

        // Validation lors de la suppression
        static::deleting(function ($payment) {
            if (!$payment->peutEtreSupprime()) {
                throw new \InvalidArgumentException(
                    'Ce paiement ne peut pas être supprimé car il est validé depuis plus de 7 jours'
                );
            }
        });
    }
}
