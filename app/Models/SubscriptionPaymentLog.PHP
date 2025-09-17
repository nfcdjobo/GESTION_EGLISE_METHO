<?php

// =================================================================
// app/Models/SubscriptionPaymentLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPaymentLog extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'subscription_id',
        'payment_id',
        'user_id',
        'action',
        'donnees_avant',
        'donnees_apres',
        'ancien_montant_paye',
        'nouveau_montant_paye',
        'commentaire',
        'ip_address'
    ];

    protected $casts = [
        'donnees_avant' => 'array',
        'donnees_apres' => 'array',
        'ancien_montant_paye' => 'decimal:2',
        'nouveau_montant_paye' => 'decimal:2'
    ];

    // Relations
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPayment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeParAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeParMembres($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('created_at', [$dateDebut, $dateFin]);
    }

    // Accessors
    public function getAEuImpactFinancierAttribute(): bool
    {
        return in_array($this->action, [
            'paiement_valide',
            'paiement_annule'
        ]);
    }

    public function getDifferenceMonetaireAttribute(): ?float
    {
        if (!$this->a_eu_impact_financier) {
            return null;
        }

        return $this->nouveau_montant_paye - $this->ancien_montant_paye;
    }

    // Methods
    public static function creerLog(array $data): self
    {
        return self::create(array_merge($data, [
            'ip_address' => request()->ip(),
            'user_id' => $data['user_id'] ?? auth()->id()
        ]));
    }

    // Events
    protected static function booted()
    {
        static::creating(function ($log) {
            if (!$log->ip_address) {
                $log->ip_address = request()->ip();
            }
        });
    }
}
