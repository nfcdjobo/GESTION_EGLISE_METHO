<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Moisson extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'moissons';

    protected $fillable = [
        'theme',
        'date',
        'cible',
        'montant_solde',
        'reste',
        'montant_supplementaire',
        'passages_bibliques',
        'culte_id',
        'creer_par',
        'editeurs',
        'status'
    ];

    protected $casts = [
        'id' => 'string',
        'date' => 'date',
        'cible' => 'decimal:2',
        'montant_solde' => 'decimal:2',
        'reste' => 'decimal:2',
        'montant_supplementaire' => 'decimal:2',
        'passages_bibliques' => 'array',
        'editeurs' => 'array',
        'status' => 'boolean',
        'culte_id' => 'string',
        'creer_par' => 'string'
    ];

    protected $attributes = [
        'montant_solde' => 0,
        'reste' => 0,
        'montant_supplementaire' => 0,
        'status' => false
    ];

    // Relations
    public function passageMoissons(): HasMany
    {
        return $this->hasMany(PassageMoisson::class, 'moisson_id');
    }

    public function venteMoissons(): HasMany
    {
        return $this->hasMany(VenteMoisson::class, 'moisson_id');
    }

    public function engagementMoissons(): HasMany
    {
        return $this->hasMany(EngagementMoisson::class, 'moisson_id');
    }

    // Relations avec d'autres modèles (à adapter selon votre structure)
    public function culte(): BelongsTo
    {
        return $this->belongsTo(Culte::class, 'culte_id');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creer_par');
    }

    // Accesseurs et Mutateurs
    protected function pourcentageRealise(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->cible > 0 ? round(($this->montant_solde * 100) / $this->cible, 2) : 0
        );
    }

    // public function scopeParStatutProgression($query, $statut)
    // {
    //     return $query->when($statut, function ($q) use ($statut) {
    //         if ($statut === 'Objectif atteint') {
    //             $q->whereColumn('montant_solde', '>=', 'cible');
    //         } elseif ($statut === 'Presque atteint') {
    //             $q->whereColumn('montant_solde', '>=', DB::raw('cible * 0.9'))
    //             ->whereColumn('montant_solde', '<', 'cible');
    //         } elseif ($statut === 'Bonne progression') {
    //             $q->whereColumn('montant_solde', '>=', DB::raw('cible * 0.7'))
    //             ->whereColumn('montant_solde', '<', DB::raw('cible * 0.9'));
    //         } elseif ($statut === 'En cours') {
    //             $q->whereColumn('montant_solde', '>=', DB::raw('cible * 0.5'))
    //             ->whereColumn('montant_solde', '<', DB::raw('cible * 0.7'));
    //         } elseif ($statut === 'Début') {
    //             $q->whereColumn('montant_solde', '>=', DB::raw('cible * 0.3'))
    //             ->whereColumn('montant_solde', '<', DB::raw('cible * 0.5'));
    //         } elseif ($statut === 'Très faible') {
    //             $q->whereColumn('montant_solde', '<', DB::raw('cible * 0.3'));
    //         }
    //     });
    // }

    public function scopeParStatutProgression($query, $statut)
    {
        return $query->when($statut, function ($q) use ($statut) {
            // Normaliser le statut (gérer les variations de casse et d'accents)
            $statutNormalise = strtolower(trim($statut));

            switch ($statutNormalise) {
                case 'objectif atteint':
                case 'atteint':
                    return $q->whereColumn('montant_solde', '>=', 'cible');

                case 'presque atteint':
                    return $q->whereRaw('montant_solde >= (cible * 0.9) AND montant_solde < cible');

                case 'bonne progression':
                    return $q->whereRaw('montant_solde >= (cible * 0.7) AND montant_solde < (cible * 0.9)');

                case 'en cours':
                    return $q->whereRaw('montant_solde >= (cible * 0.5) AND montant_solde < (cible * 0.7)');

                case 'début':
                case 'debut':
                    return $q->whereRaw('montant_solde >= (cible * 0.3) AND montant_solde < (cible * 0.5)');

                case 'très faible':
                case 'tres faible':
                case 'faible':
                    return $q->whereRaw('montant_solde < (cible * 0.3)');

                default:
                    return $q; // Retourner la query sans modification si statut non reconnu
            }
        });
    }


    protected function statutProgression(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->montant_solde >= $this->cible) return 'Objectif atteint';
                if ($this->montant_solde >= ($this->cible * 0.9)) return 'Presque atteint';
                if ($this->montant_solde >= ($this->cible * 0.7)) return 'Bonne progression';
                if ($this->montant_solde >= ($this->cible * 0.5)) return 'En cours';
                if ($this->montant_solde >= ($this->cible * 0.3)) return 'Début';
                return 'Très faible';
            }
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

    public function scopeParDate($query, $dateDebut = null, $dateFin = null)
    {
        return $query->when($dateDebut, fn($q) => $q->whereDate('date', '>=', $dateDebut))
                    ->when($dateFin, fn($q) => $q->whereDate('date', '<=', $dateFin));
    }

    public function scopeObjectifAtteint($query)
    {
        return $query->whereRaw('montant_solde >= cible');
    }

    public function scopeAvecStatistiques($query)
    {
        return $query->withCount([
            'passageMoissons as nb_passages',
            'venteMoissons as nb_ventes',
            'engagementMoissons as nb_engagements'
        ])->withSum([
            'passageMoissons as total_passages' => fn($q) => $q->where('status', true)
        ], 'montant_solde')
        ->withSum([
            'venteMoissons as total_ventes' => fn($q) => $q->where('status', true)
        ], 'montant_solde')
        ->withSum([
            'engagementMoissons as total_engagements' => fn($q) => $q->where('status', true)
        ], 'montant_solde');
    }

    // Méthodes métier
    public function recalculerTotaux(): bool
    {
        try {
            DB::select('SELECT calculate_moisson_totals(?)', [$this->id]);
            $this->refresh();
            return true;
        } catch (\Exception $e) {
            dd('errors', $e->getMessage());
            \Log::error('Erreur lors du recalcul des totaux de moisson: ' . $e->getMessage());
            return false;
        }
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

    public function estEnRetard(): bool
    {
        return $this->date < now()->subDays(30) && !$this->objectif_atteint;
    }

    // Événements du modèle
    protected static function booted()
    {
        static::saving(function ($moisson) {
            // Validation métier avant sauvegarde
            if ($moisson->cible <= 0) {
                throw new \InvalidArgumentException('La cible doit être supérieure à 0');
            }
        });

        static::saved(function ($moisson) {
            // Log des modifications importantes
            if ($moisson->wasChanged(['cible', 'status'])) {
                \Log::info("Moisson {$moisson->id} modifiée", $moisson->getChanges());
            }
        });
    }


    /**
 * Accessor pour formater les passages bibliques pour l'affichage
 */
public function getPassagesBibliquesFormattedAttribute(): array
{
    if (!$this->passages_bibliques || !is_array($this->passages_bibliques)) {
        return [];
    }

    return collect($this->passages_bibliques)->map(function ($passage) {
        if (is_array($passage)) {
            // Nouveau format structuré
            if (isset($passage['reference'])) {
                return $passage['reference'];
            }

            // Reconstruire la référence
            $reference = $passage['livre'] . ' ' . $passage['chapitre'] . ':' . $passage['verset_debut'];
            if (!empty($passage['verset_fin'])) {
                $reference .= '-' . $passage['verset_fin'];
            }
            return $reference;
        }

        // Ancien format string
        return $passage;
    })->toArray();
}

/**
 * Accessor pour obtenir un passage biblique spécifique formaté
 */
public function getPassageBibliqueFormatted(int $index): ?string
{
    $passages = $this->passages_bibliques_formatted;
    return $passages[$index] ?? null;
}
}
