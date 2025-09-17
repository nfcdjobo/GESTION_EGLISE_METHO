<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;

class VenteMoisson extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'vente_moissons';

    // Constantes pour les catégories
    public const CATEGORIES = [
        'aliments' => 'Vente d\'aliments',
        'arbres_vie' => 'Vente d\'arbres de vie',
        'americaine' => 'Vente américaine'
    ];

    protected $fillable = [
        'moisson_id',
        'categorie',
        'cible',
        'montant_solde',
        'reste',
        'montant_supplementaire',
        'collecter_par',
        'collecte_le',
        'creer_par',
        'description',
        'editeurs',
        'status'
    ];

    protected $casts = [
        'id' => 'string',
        'moisson_id' => 'string',
        'cible' => 'decimal:2',
        'montant_solde' => 'decimal:2',
        'reste' => 'decimal:2',
        'montant_supplementaire' => 'decimal:2',
        'collecte_le' => 'datetime',
        'collecter_par' => 'string',
        'creer_par' => 'string',
        'editeurs' => 'array',
        'status' => 'boolean'
    ];

    protected $attributes = [
        'montant_solde' => 0,
        'reste' => 0,
        'montant_supplementaire' => 0,
        'status' => false
    ];

    // Relations
    public function moisson(): BelongsTo
    {
        return $this->belongsTo(Moisson::class, 'moisson_id');
    }

    public function collecteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collecter_par');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creer_par');
    }

    // Accesseurs et Mutateurs
    protected function categorieLibelle(): Attribute
    {
        return Attribute::make(
            get: fn() => self::CATEGORIES[$this->categorie] ?? $this->categorie
        );
    }

    protected function pourcentageRealise(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cible > 0 ? round(($this->montant_solde * 100) / $this->cible, 2) : 0
        );
    }

    protected function objectifAtteint(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->montant_solde >= $this->cible
        );
    }







    // Scopes
    public function scopeActif($query)
    {
        return $query->where('status', true);
    }

    public function scopeParCategorie($query, string $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeObjectifAtteint($query)
    {
        return $query->whereRaw('montant_solde >= cible');
    }



    public function scopeRechercheDescription($query, string $terme)
    {
        return $query->whereRaw("to_tsvector('french', description) @@ plainto_tsquery('french', ?)", [$terme]);
    }

    public function scopeParPeriode($query, $dateDebut = null, $dateFin = null)
    {
        return $query->when($dateDebut, fn($q) => $q->whereDate('collecte_le', '>=', $dateDebut))
                    ->when($dateFin, fn($q) => $q->whereDate('collecte_le', '<=', $dateFin));
    }

    public function scopeAvecMoisson($query)
    {
        return $query->with(['moisson:id,theme,date,cible,status']);
    }

    // Méthodes métier
    public function ajouterVente(
        float $montant,
        string $collecteurId
    ): bool {
        if ($montant <= 0) {
            throw new \InvalidArgumentException('Le montant doit être supérieur à 0');
        }



        $this->montant_solde += $montant;
        $this->collecter_par = $collecteurId;
        $this->collecte_le = now();



        return $this->save();
    }

    public function ajouterEditeur(string $userId, string $action = 'modification'): void
    {
        $editeurs = $this->editeurs ?? [];
        $editeurs[] = [
            'user_id' => $userId,
            'action' => $action,
            'date' => now()->toISOString()
        ];
        $this->editeurs = $editeurs;
    }

    public function valider(): bool
    {
        $this->status = true;
        $this->ajouterEditeur(auth()->id(), 'validation');
        return $this->save();
    }

    public function calculerStatsVente(): array
    {
        return [
            'montant_collecte' => $this->montant_solde,
            'objectif' => $this->cible,
            'pourcentage' => $this->pourcentage_realise,
            'reste' => $this->reste,
            'montant_theorique' => $this->montant_theorique,
            'marge_beneficiaire' => $this->benefice_marge,
            'statut' => $this->objectif_atteint ? 'Atteint' : 'En cours'
        ];
    }

    public static function statistiquesParCategorie(string $moissonId = null)
    {
        $query = self::selectRaw('
            categorie,
            COUNT(*) as nombre,
            SUM(cible) as total_cible,
            SUM(montant_solde) as total_collecte,
            SUM(reste) as total_reste,
            ROUND(AVG(montant_solde * 100.0 / NULLIF(cible, 0)), 2) as pourcentage_moyen
        ')
        ->where('status', true);

        if ($moissonId) {
            $query->where('moisson_id', $moissonId);
        }

        return $query->groupBy('categorie')->get();
    }

    public static function topVentes(int $limit = 10, string $periode = null)
    {
        $query = self::where('status', true)
                    ->orderByDesc('montant_solde');

        if ($periode === 'mois') {
            $query->whereMonth('collecte_le', now()->month)
                 ->whereYear('collecte_le', now()->year);
        } elseif ($periode === 'annee') {
            $query->whereYear('collecte_le', now()->year);
        }

        return $query->with(['moisson:id,theme,date'])
                    ->limit($limit)
                    ->get();
    }

    // Événements du modèle
    protected static function booted()
    {
        static::saving(function ($vente) {
            // Validation des contraintes métier
            if ($vente->cible <= 0) {
                throw new \InvalidArgumentException('La cible doit être supérieure à 0');
            }





            
        });

        static::created(function ($vente) {
            $vente->ajouterEditeur($vente->creer_par, 'création');
            $vente->saveQuietly();
        });

        static::updated(function ($vente) {
            if ($vente->wasChanged(['montant_solde', 'cible'])) {
                \Log::info("Vente moisson {$vente->id} modifiée", [
                    'moisson_id' => $vente->moisson_id,
                    'categorie' => $vente->categorie,
                    'changes' => $vente->getChanges()
                ]);
            }
        });
    }
}
