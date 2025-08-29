<?php

// =================================================================
// app/Models/SubscriptionPayment.php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPayment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'subscription_id',
        'montant',
        'ancien_reste',
        'nouveau_reste',
        'type_paiement',
        'reference_paiement',
        'statut',
        'date_paiement',
        'validateur_id',
        'date_validation',
        'commentaire',
        'subscription_version_at_payment'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'ancien_reste' => 'decimal:2',
        'nouveau_reste' => 'decimal:2',
        'date_paiement' => 'datetime',
        'date_validation' => 'datetime'
    ];

    protected $attributes = [
        'statut' => 'en_attente'
    ];

    // Relations
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

    // Scopes
    public function scopeValide($query)
    {
        return $query->where('statut', 'valide');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeRefuse($query)
    {
        return $query->where('statut', 'refuse');
    }

    public function scopeAnnule($query)
    {
        return $query->where('statut', 'annule');
    }

    public function scopeParType($query, string $type)
    {
        return $query->where('type_paiement', $type);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_paiement', [$dateDebut, $dateFin]);
    }

    // Accessors
    public function getEstValideAttribute(): bool
    {
        return $this->statut === 'valide';
    }

    public function getEstEnAttenteAttribute(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function getPeutEtreValideAttribute(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function getPeutEtreAnnuleAttribute(): bool
    {
        return in_array($this->statut, ['en_attente', 'valide']);
    }

    // Methods
    public function valider(?string $commentaire = null): bool
    {
        if (!$this->peut_etre_valide) {
            throw new \InvalidArgumentException('Ce paiement ne peut pas être validé');
        }

        DB::transaction(function () use ($commentaire) {
            $this->statut = 'valide';
            $this->validateur_id = auth()->id();
            $this->date_validation = now();

            if ($commentaire) {
                $this->commentaire = $commentaire;
            }

            $this->save();

            // Log de validation
            $this->subscription->logs()->create([
                'subscription_id' => $this->subscription_id,
                'payment_id' => $this->id,
                'action' => 'paiement_valide',
                'ancien_montant_paye' => $this->subscription->montant_paye,
                'nouveau_montant_paye' => $this->subscription->montant_paye + $this->montant,
                'commentaire' => $commentaire,
                'user_id' => auth()->id()
            ]);

            // Mise à jour de la souscription (si pas fait automatiquement par trigger)
            $this->subscription->mettreAJourMontants();
        });

        return true;
    }

    public function refuser(string $raison): bool
    {
        if (!$this->peut_etre_valide) {
            throw new \InvalidArgumentException('Ce paiement ne peut pas être refusé');
        }

        $this->statut = 'refuse';
        $this->validateur_id = auth()->id();
        $this->date_validation = now();
        $this->commentaire = $raison;

        // Log du refus
        $this->subscription->logs()->create([
            'subscription_id' => $this->subscription_id,
            'payment_id' => $this->id,
            'action' => 'paiement_refuse',
            'commentaire' => $raison,
            'user_id' => auth()->id()
        ]);

        return $this->save();
    }

    public function annuler(string $raison): bool
    {
        if (!$this->peut_etre_annule) {
            throw new \InvalidArgumentException('Ce paiement ne peut pas être annulé');
        }

        $ancienStatut = $this->statut;

        DB::transaction(function () use ($raison, $ancienStatut) {
            $this->statut = 'annule';
            $this->commentaire = $raison;
            $this->save();

            // Si le paiement était validé, mettre à jour la souscription
            if ($ancienStatut === 'valide') {
                $this->subscription->logs()->create([
                    'subscription_id' => $this->subscription_id,
                    'payment_id' => $this->id,
                    'action' => 'paiement_annule',
                    'ancien_montant_paye' => $this->subscription->montant_paye,
                    'nouveau_montant_paye' => $this->subscription->montant_paye - $this->montant,
                    'commentaire' => $raison,
                    'user_id' => auth()->id()
                ]);

                $this->subscription->mettreAJourMontants();
            }
        });

        return true;
    }

    // Events
    protected static function booted()
    {
        static::creating(function ($payment) {
            if (!$payment->date_paiement) {
                $payment->date_paiement = now();
            }
        });

        static::created(function ($payment) {
            $payment->subscription->logs()->create([
                'subscription_id' => $payment->subscription_id,
                'payment_id' => $payment->id,
                'action' => 'paiement_ajoute',
                'commentaire' => 'Nouveau paiement de ' . $payment->montant . ' ajouté',
                'user_id' => auth()->id()
            ]);
        });
    }
}
