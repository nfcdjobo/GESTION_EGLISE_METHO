<?php
// app/Models/Fimeco.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Fimeco extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'responsable_id',
        'nom',
        'description',
        'debut',
        'fin',
        'cible',
        'statut'
    ];

    protected $casts = [
        'debut' => 'date',
        'fin' => 'date',
        'cible' => 'decimal:2'
    ];

    protected $attributes = [
        'statut' => 'active'
    ];

    // Relations
    public function responsable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function subscriptionsActives(): HasMany
    {
        return $this->hasMany(Subscription::class)
                    ->whereIn('statut', ['active', 'partiellement_payee']);
    }

    public function subscriptionsCompletes(): HasMany
    {
        return $this->hasMany(Subscription::class)
                    ->where('statut', 'completement_payee');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }

    public function scopeEnCours($query)
    {
        return $query->where('debut', '<=', now())
                    ->where('fin', '>=', now())
                    ->where('statut', 'active');
    }

    public function scopeAVenir($query)
    {
        return $query->where('debut', '>', now());
    }

    public function scopeTerminee($query)
    {
        return $query->where('fin', '<', now())
                    ->orWhere('statut', 'cloturee');
    }

    // Accessors & Mutators
    public function getEstEnCoursAttribute(): bool
    {
        return $this->debut <= now() &&
               $this->fin >= now() &&
               $this->statut === 'active';
    }

    public function getEstTermineeAttribute(): bool
    {
        return $this->fin < now() || $this->statut === 'cloturee';
    }

    public function getTotalSouscriptionsAttribute(): string
    {
        return $this->subscriptions()
                   ->sum('montant_souscrit');
    }

    public function getTotalPayeAttribute(): string
    {
        return $this->subscriptions()
                   ->sum('montant_paye');
    }

    public function getPourcentageRealisationAttribute(): float
    {
        if ($this->cible == 0) return 0;

        return round(($this->total_paye / $this->cible) * 100, 2);
    }

    public function getNombreMembresSouscripteursAttribute(): int
    {
        return $this->subscriptions()->distinct('souscripteur_id')->count();
    }

    // Methods
    public function peutEtreSouscrite(): bool
    {
        return $this->est_en_cours && $this->statut === 'active';
    }

    public function cloturer(): bool
    {
        $this->statut = 'cloturee';
        return $this->save();
    }

    public function calculerStatistiques(): array
    {
        return [
            'total_souscriptions' => $this->total_souscriptions,
            'total_paye' => $this->total_paye,
            'reste_a_collecter' => $this->cible - $this->total_paye,
            'pourcentage_realisation' => $this->pourcentage_realisation,
            'nombre_souscripteurs' => $this->nombre_membres_souscripteurs,
            'montant_moyen_souscription' => $this->nombre_membres_souscripteurs > 0 ?
                $this->total_souscriptions / $this->nombre_membres_souscripteurs : 0
        ];
    }
}






