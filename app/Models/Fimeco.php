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
        'statut'
    ];

    protected $casts = [
        'debut' => 'date',
        'fin' => 'date'
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

    public function getNombreMembresSouscripteursAttribute(): int
    {
        return $this->subscriptions()->distinct('souscripteur_id')->count();
    }

    public function getNombreTotalSouscriptionsAttribute(): int
    {
        return $this->subscriptions()->count();
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
        $totalSouscriptions = $this->total_souscriptions;
        $totalPaye = $this->total_paye;
        $nombreSouscripteurs = $this->nombre_membres_souscripteurs;

        return [
            'total_souscriptions' => $totalSouscriptions,
            'total_paye' => $totalPaye,
            'nombre_souscripteurs' => $nombreSouscripteurs,
            'nombre_total_souscriptions' => $this->nombre_total_souscriptions,
            'montant_moyen_souscription' => $nombreSouscripteurs > 0 ?
                ($totalSouscriptions / $nombreSouscripteurs) : 0,
            'taux_realisation_paiements' => $totalSouscriptions > 0 ?
                round(($totalPaye / $totalSouscriptions) * 100, 2) : 0,
        ];
    }

    public function obtenirTopSouscripteurs(int $limite = 10): \Illuminate\Database\Eloquent\Collection
    {
        return $this->subscriptions()
                   ->with('souscripteur')
                   ->orderBy('montant_paye', 'desc')
                   ->limit($limite)
                   ->get();
    }

    public function obtenirStatistiquesParMois(): array
    {
        return $this->subscriptions()
                   ->selectRaw('
                       YEAR(date_souscription) as annee,
                       MONTH(date_souscription) as mois,
                       COUNT(*) as nombre_souscriptions,
                       SUM(montant_souscrit) as total_souscrit,
                       SUM(montant_paye) as total_paye
                   ')
                   ->groupBy('annee', 'mois')
                   ->orderBy('annee', 'desc')
                   ->orderBy('mois', 'desc')
                   ->get()
                   ->toArray();
    }
}
