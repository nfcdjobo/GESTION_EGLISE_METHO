<?php

// =================================================================
// app/Models/Subscription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\ConcurrentUpdateException;

class Subscription extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'souscripteur_id',
        'fimeco_id',
        'montant_souscrit',
        'montant_paye',
        'reste_a_payer',
        'statut',
        'date_souscription',
        'date_echeance'
    ];

    protected $casts = [
        'montant_souscrit' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'reste_a_payer' => 'decimal:2',
        'date_souscription' => 'date',
        'date_echeance' => 'date'
    ];

    protected $attributes = [
        'montant_paye' => '0.00',
        'statut' => 'active'
    ];

    // Relations
    public function souscripteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'souscripteur_id');
    }

    public function fimeco(): BelongsTo
    {
        return $this->belongsTo(Fimeco::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function paymentsValides(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class)
                    ->where('statut', 'valide');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SubscriptionPaymentLog::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }

    public function scopePartiellementPayee($query)
    {
        return $query->where('statut', 'partiellement_payee');
    }

    public function scopeCompletementPayee($query)
    {
        return $query->where('statut', 'completement_payee');
    }

    public function scopeSurPayee($query)
    {
        return $query->where('montant_paye', '>', 'montant_souscrit');
    }

    public function scopeEnRetard($query)
    {
        return $query->where('date_echeance', '<', now())
                    ->whereNotIn('statut', ['completement_payee', 'annulee']);
    }

    public function scopePourFimeco($query, $fimecoId)
    {
        return $query->where('fimeco_id', $fimecoId);
    }

    public function scopePourMembres($query, $userId)
    {
        return $query->where('souscripteur_id', $userId);
    }

    // Accessors & Mutators
    public function getEstSoldeeAttribute(): bool
    {
        return $this->montant_paye >= $this->montant_souscrit;
    }

    public function getEstSurPayeeAttribute(): bool
    {
        return $this->montant_paye > $this->montant_souscrit;
    }

    public function getMontantSurPayeAttribute(): string
    {
        $surPaye = $this->montant_paye - $this->montant_souscrit;
        return $surPaye > 0 ? $surPaye : '0.00';
    }

    public function getResteAPayer_RealAttribute(): string
    {
        $reste = $this->montant_souscrit - $this->montant_paye;
        return $reste > 0 ? $reste : '0.00';
    }

    public function getEstEnRetardAttribute(): bool
    {
        return $this->date_echeance &&
               $this->date_echeance < now() &&
               !$this->est_soldee;
    }

    public function getPourcentagePayeAttribute(): float
    {
        if ($this->montant_souscrit == 0) return 0;

        return round(($this->montant_paye / $this->montant_souscrit) * 100, 2);
    }

    public function getProchainMontantMinimumAttribute(): string
    {
        // Si déjà soldé, permettre quand même un paiement minimum (ex: don supplémentaire)
        if ($this->est_soldee) {
            return '10.00'; // Montant minimum pour paiement supplémentaire
        }

        // Sinon, calculer le minimum entre le reste et un seuil
        return min($this->reste_a_payer_real, 50);
    }

    // Mutators pour maintenir la cohérence
    public function setMontantSouscritAttribute($value)
    {
        $this->attributes['montant_souscrit'] = $value;
        $this->calculerReste();
    }

    public function setMontantPayeAttribute($value)
    {
        $this->attributes['montant_paye'] = $value;
        $this->calculerReste();
    }

    private function calculerReste()
    {
        if (isset($this->attributes['montant_souscrit']) &&
            isset($this->attributes['montant_paye'])) {
            // Le reste peut être négatif si sur-paiement
            $this->attributes['reste_a_payer'] =
                $this->attributes['montant_souscrit'] - $this->attributes['montant_paye'];
        }
    }

    // Methods
    public function peutRecevoirPaiement(float $montant = null): bool
    {
        // Empêcher les paiements sur souscriptions annulées uniquement
        if ($this->statut === 'annulee') {
            return false;
        }

        // Permettre les paiements même si déjà soldée (pour les sur-paiements)
        // Pas de limite sur le montant - le souscripteur peut payer autant qu'il veut

        return true;
    }

    public function ajouterPaiement(array $paymentData): SubscriptionPayment
    {
        if (!$this->peutRecevoirPaiement($paymentData['montant'])) {
            throw new \InvalidArgumentException('Paiement non autorisé pour cette souscription');
        }

        $ancienReste = $this->reste_a_payer;
        $nouveauReste = $ancienReste - $paymentData['montant'];

        return $this->payments()->create(array_merge($paymentData, [
            'ancien_reste' => $ancienReste,
            'nouveau_reste' => $nouveauReste,
            'subscription_version_at_payment' => $this->version
        ]));
    }

    public function mettreAJourMontants(): void
    {
        $totalPaye = $this->paymentsValides()->sum('montant');

        $this->montant_paye = $totalPaye;
        $reste = $this->montant_souscrit - $totalPaye;
        $this->reste_a_payer = $reste > 0 ? $reste : 0;

        // Mise à jour du statut selon le montant payé
        if ($totalPaye >= $this->montant_souscrit) {
            $this->statut = 'completement_payee';
        } elseif ($totalPaye > 0) {
            $this->statut = 'partiellement_payee';
        } else {
            $this->statut = 'active';
        }

        $this->version++;
        $this->save();
    }

    public function augmenterSouscription(float $nouveauMontant, string $raison = null): bool
    {
        if ($nouveauMontant <= $this->montant_souscrit) {
            throw new \InvalidArgumentException(
                'Le nouveau montant doit être supérieur au montant actuel'
            );
        }

        $ancienMontant = $this->montant_souscrit;
        $this->montant_souscrit = $nouveauMontant;
        $this->calculerReste();

        // Log de la modification
        $this->logs()->create([
            'action' => 'souscription_modifiee',
            'donnees_avant' => ['montant_souscrit' => $ancienMontant],
            'donnees_apres' => ['montant_souscrit' => $nouveauMontant],
            'commentaire' => $raison ?? "Augmentation de souscription de {$ancienMontant} à {$nouveauMontant}",
            'user_id' => auth()->id()
        ]);

        return $this->save();
    }

    public function obtenirDetailsPaiements(): array
    {
        return [
            'montant_initial_souscrit' => $this->montant_souscrit,
            'montant_total_paye' => $this->montant_paye,
            'reste_a_payer' => $this->reste_a_payer_real,
            'montant_sur_paye' => $this->montant_sur_paye,
            'est_soldee' => $this->est_soldee,
            'est_sur_payee' => $this->est_sur_payee,
            'pourcentage_paye' => $this->pourcentage_paye,
            'nombre_paiements' => $this->paymentsValides()->count(),
            'dernier_paiement' => $this->paymentsValides()->latest('date_paiement')->first()?->date_paiement
        ];
    }

    public function annuler(string $raison = null): bool
    {
        if ($this->montant_paye > 0) {
            throw new \InvalidArgumentException(
                'Impossible d\'annuler une souscription avec des paiements validés'
            );
        }

        $this->statut = 'annulee';

        // Log de l'annulation
        $this->logs()->create([
            'action' => 'souscription_annulee',
            'commentaire' => $raison,
            'user_id' => auth()->id()
        ]);

        return $this->save();
    }

    // Events
    protected static function booted()
    {
        static::creating(function ($subscription) {
            if (!$subscription->date_souscription) {
                $subscription->date_souscription = now();
            }

            $subscription->reste_a_payer = $subscription->montant_souscrit;
        });

        static::created(function ($subscription) {
            $subscription->logs()->create([
                'action' => 'souscription_creee',
                'donnees_apres' => $subscription->toArray(),
                'user_id' => auth()->id()
            ]);
        });
    }
}
