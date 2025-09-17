<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PassageMoisson extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'passage_moissons';

    // Constantes pour les catégories
    public const CATEGORIES = [
        'passage_hommes' => 'Passage des hommes',
        'passage_femmes' => 'Passage des femmes',
        'passage_jeunesses' => 'Passage des jeunes',
        'passage_enfants' => 'Passage des enfants',
        'passage_classe_communautaire' => 'Passage de la classe communautaire',
        'passage_predicateurs' => 'Passage des prédicateurs',
        'passage_conseil' => 'Passage du conseil',
        'passage_assemble' => 'Passage de l\'assemblée'
    ];

    protected $fillable = [
        'moisson_id',
        'categorie',
        'classe_id',
        'cible',
        'montant_solde',
        'reste',
        'montant_supplementaire',
        'collecter_par',
        'collecte_le',
        'creer_par',
        'editeurs',
        'status'
    ];

    protected $casts = [
        'id' => 'string',
        'moisson_id' => 'string',
        'classe_id' => 'string',
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

    public function classe(): BelongsTo
    {
        return $this->belongsTo(Classe::class, 'classe_id');
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

    protected function estClasseCommunautaire(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->categorie === 'passage_classe_communautaire'
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

    public function scopeClasseCommunautaire($query)
    {
        return $query->where('categorie', 'passage_classe_communautaire')
                    ->whereNotNull('classe_id');
    }

    public function scopeObjectifAtteint($query)
    {
        return $query->whereRaw('montant_solde >= cible');
    }

    public function scopeEnRetard($query)
    {
        return $query->where('reste', '>', 0)
                    ->where('status', true)
                    ->whereHas('moisson', function($q) {
                        $q->where('date', '<', now()->subDays(7));
                    });
    }

    public function scopeAvecMoisson($query)
    {
        return $query->with(['moisson:id,theme,date,cible,status']);
    }

    public function scopeParPeriode($query, $dateDebut = null, $dateFin = null)
    {
        return $query->when($dateDebut, fn($q) => $q->whereDate('collecte_le', '>=', $dateDebut))
                    ->when($dateFin, fn($q) => $q->whereDate('collecte_le', '<=', $dateFin));
    }

    // Méthodes métier
    public function ajouterMontant(float $montant, string $collecteurId): bool
    {
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

    public function estEnRetard(): bool
    {
        return $this->reste > 0 &&
               $this->status &&
               $this->moisson &&
               $this->moisson->date < now()->subDays(7);
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

    // Événements du modèle
    protected static function booted()
    {
        static::saving(function ($passage) {
            // Validation des contraintes métier

            if ($passage->categorie === 'passage_classe_communautaire' && !$passage->classe_id) {
                throw new \InvalidArgumentException('Une classe doit être spécifiée pour un passage de classe communautaire');
            }

            if ($passage->categorie !== 'passage_classe_communautaire' && $passage->classe_id) {
                $passage->classe_id = null;
            }

            if (isset( $passage->cible) && $passage->cible <= 0) {
                 dd(isset( $passage->cible));
                throw new \InvalidArgumentException('La cible doit être supérieure à 0');
            }
        });

        static::created(function ($passage) {
            $passage->ajouterEditeur($passage->creer_par, 'création');
            $passage->saveQuietly();
        });

        static::updated(function ($passage) {
            if ($passage->wasChanged(['montant_solde', 'cible'])) {
                \Log::info("Passage moisson {$passage->id} modifié", [
                    'moisson_id' => $passage->moisson_id,
                    'categorie' => $passage->categorie,
                    'changes' => $passage->getChanges()
                ]);
            }
        });
    }
}
