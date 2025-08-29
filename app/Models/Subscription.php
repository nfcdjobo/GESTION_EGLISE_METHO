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

    public function scopeEnRetard($query)
    {
        return $query->where('date_echeance', '<', now())
                    ->whereNotIn('statut', ['completement_payee', 'annulee']);
    }

    public function scopePourFimeco($query, $fimecoId)
    {
        return $query->where('fimeco_id', $fimecoId);
    }

    public function scopePourUtilisateur($query, $userId)
    {
        return $query->where('souscripteur_id', $userId);
    }

    // Accessors & Mutators
    public function getEstSoldeeAttribute(): bool
    {
        return $this->reste_a_payer <= 0;
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
        // Calcul du montant minimum pour le prochain paiement
        return min($this->reste_a_payer, 50); // Par exemple, minimum 50 ou le reste
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
            $this->attributes['reste_a_payer'] =
                $this->attributes['montant_souscrit'] - $this->attributes['montant_paye'];
        }
    }

    // Methods
    public function peutRecevoirPaiement(float $montant = null): bool
    {
        if ($this->statut === 'completement_payee' ||
            $this->statut === 'annulee') {
            return false;
        }

        if ($montant && $montant > $this->reste_a_payer) {
            return false;
        }

        return true;
    }

    public function ajouterPaiement(array $paymentData): SubscriptionPayment
    {
        if (!$this->peutRecevoirPaiement($paymentData['montant'])) {
            throw new \InvalidArgumentException('Paiement non autorisé pour cette souscription');
        }

        return $this->payments()->create(array_merge($paymentData, [
            'ancien_reste' => $this->reste_a_payer,
            'nouveau_reste' => $this->reste_a_payer - $paymentData['montant'],
            'subscription_version_at_payment' => $this->version
        ]));
    }

    public function mettreAJourMontants(): void
    {
        $totalPaye = $this->paymentsValides()->sum('montant');

        $this->montant_paye = $totalPaye;
        $this->reste_a_payer = $this->montant_souscrit - $totalPaye;

        // Mise à jour du statut
        if ($this->reste_a_payer <= 0) {
            $this->statut = 'completement_payee';
        } elseif ($this->montant_paye > 0) {
            $this->statut = 'partiellement_payee';
        }

        $this->version++;
        $this->save();
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
