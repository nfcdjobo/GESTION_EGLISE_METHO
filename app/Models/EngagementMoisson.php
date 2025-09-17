<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class EngagementMoisson extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'engagement_moissons';

    // Constantes pour les catégories
    public const CATEGORIES = [
        'entite_morale' => 'Entité morale',
        'entite_physique' => 'Entité physique (personne)'
    ];

    // Niveaux d'urgence pour les retards
    public const NIVEAUX_URGENCE = [
        'critique' => 'Critique (>30 jours)',
        'important' => 'Important (15-30 jours)',
        'modere' => 'Modéré (7-15 jours)',
        'recent' => 'Récent (<7 jours)'
    ];

    protected $fillable = [
        'moisson_id',
        'categorie',
        'donateur_id',
        'nom_entite',
        'description',
        'telephone',
        'email',
        'adresse',
        'cible',
        'montant_solde',
        'reste',
        'montant_supplementaire',
        'collecter_par',
        'collecter_le',
        'creer_par',
        'date_echeance',
        'date_rappel',
        'editeurs',
        'status'
    ];

    protected $casts = [
        'id' => 'string',
        'moisson_id' => 'string',
        'donateur_id' => 'string',
        'cible' => 'decimal:2',
        'montant_solde' => 'decimal:2',
        'reste' => 'decimal:2',
        'montant_supplementaire' => 'decimal:2',
        'collecter_le' => 'datetime',
        'date_echeance' => 'date',
        'date_rappel' => 'date',
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

    public function donateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donateur_id');
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

    protected function nomDonateur(): Attribute
    {
        return Attribute::make(
            get: function() {
                if ($this->categorie === 'entite_morale') {
                    return $this->nom_entite ?? 'Entité morale non précisée';
                }
                return $this->donateur ? $this->donateur->name : 'Personne physique';
            }
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

    protected function estEnRetard(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->date_echeance &&
                       $this->date_echeance->isPast() &&
                       $this->reste > 0 &&
                       $this->status
        );
    }

    protected function joursRetard(): Attribute
    {
        return Attribute::make(
            get: function() {
                if (!$this->est_en_retard) return 0;
                return now()->diffInDays($this->date_echeance);
            }
        );
    }

    protected function niveauUrgence(): Attribute
    {
        return Attribute::make(
            get: function() {
                if (!$this->est_en_retard) return null;

                $jours = $this->jours_retard;
                if ($jours > 30) return 'critique';
                if ($jours > 15) return 'important';
                if ($jours > 7) return 'modere';
                return 'recent';
            }
        );
    }

    protected function niveauUrgenceLibelle(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->niveau_urgence ? self::NIVEAUX_URGENCE[$this->niveau_urgence] : null
        );
    }

    protected function doitEtreRappele(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->date_rappel &&
                       $this->date_rappel->isToday() &&
                       $this->reste > 0 &&
                       $this->status
        );
    }

    protected function estEntiteMorale(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->categorie === 'entite_morale'
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

    public function scopeEntiteMorale($query)
    {
        return $query->where('categorie', 'entite_morale');
    }

    public function scopeEntitePhysique($query)
    {
        return $query->where('categorie', 'entite_physique')
                    ->whereNotNull('donateur_id');
    }

    public function scopeObjectifAtteint($query)
    {
        return $query->whereRaw('montant_solde >= cible');
    }

    public function scopeEnRetard($query)
    {
        return $query->where('status', true)
                    ->where('reste', '>', 0)
                    ->whereDate('date_echeance', '<', now());
    }

    public function scopeARappeler($query, Carbon $date = null)
    {
        $date = $date ?? now();
        return $query->where('status', true)
                    ->where('reste', '>', 0)
                    ->whereDate('date_rappel', '<=', $date->toDateString());
    }

    public function scopeParNiveauUrgence($query, string $niveau)
    {
        $joursMap = [
            'critique' => ['>', 30],
            'important' => ['between', [15, 30]],
            'modere' => ['between', [7, 15]],
            'recent' => ['<=', 7]
        ];

        if (!isset($joursMap[$niveau])) return $query;

        $query->enRetard();

        [$operator, $value] = $joursMap[$niveau];

        if ($operator === 'between') {
            return $query->whereRaw('CURRENT_DATE - date_echeance BETWEEN ? AND ?', $value);
        }

        return $query->whereRaw("CURRENT_DATE - date_echeance $operator ?", [$value]);
    }

    public function scopeAvecMoisson($query)
    {
        return $query->with(['moisson:id,theme,date,cible,status']);
    }

    public function scopeRechercheTexte($query, string $terme)
    {
        return $query->where(function($q) use ($terme) {
            $q->where('nom_entite', 'ILIKE', "%{$terme}%")
              ->orWhere('email', 'ILIKE', "%{$terme}%")
              ->orWhere('telephone', 'ILIKE', "%{$terme}%")
              ->orWhereRaw("to_tsvector('french', description) @@ plainto_tsquery('french', ?)", [$terme]);
        });
    }

    // Méthodes métier
    public function ajouterPaiement(float $montant, string $collecteurId): bool
    {
        if ($montant <= 0) {
            throw new \InvalidArgumentException('Le montant doit être supérieur à 0');
        }

        $this->montant_solde += $montant;
        $this->collecter_par = $collecteurId;
        $this->collecter_le = now();

        // Si l'engagement est soldé, on peut le marquer comme terminé
        if ($this->reste <= 0) {
            $this->ajouterEditeur($collecteurId, 'paiement_complet');
        } else {
            $this->ajouterEditeur($collecteurId, 'paiement_partiel');
        }

        return $this->save();
    }

    public function ajouterEditeur( $userId, string $action = 'modification'): void
    {
// dd($userId);
        $editeurs = $this->editeurs ?? [];
        $editeurs[] = [
            'user_id' => $userId,
            'action' => $action,
            'date' => now()->toISOString(),
            'montant' => $action === 'paiement_partiel' || $action === 'paiement_complet' ?
                        $this->montant_solde : null
        ];
        $this->editeurs = $editeurs;
    }

    public function valider(): bool
    {
        $this->status = true;
        $this->ajouterEditeur(auth()->id(), 'validation');
        return $this->save();
    }

    public function planifierRappel(Carbon $dateRappel): bool
    {
        if ($dateRappel->isPast()) {
            throw new \InvalidArgumentException('La date de rappel ne peut pas être dans le passé');
        }

        if ($this->date_echeance && $dateRappel->isAfter($this->date_echeance)) {
            throw new \InvalidArgumentException('La date de rappel ne peut pas être après l\'échéance');
        }

        $this->date_rappel = $dateRappel;
        $this->ajouterEditeur(auth()->id(), 'rappel_planifie');

        return $this->save();
    }

    public function marquerRappelEffectue(): bool
    {
        $this->date_rappel = null;
        $this->ajouterEditeur(auth()->id(), 'rappel_effectue');
        return $this->save();
    }

    public function prolongerEcheance(Carbon $nouvelleEcheance, string $motif = null): bool
    {
        if ($nouvelleEcheance->isPast()) {
            throw new \InvalidArgumentException('La nouvelle échéance ne peut pas être dans le passé');
        }

        $ancienneEcheance = $this->date_echeance;
        $this->date_echeance = $nouvelleEcheance;

        $this->ajouterEditeur(auth()->id(), 'prolongation_echeance');

        if ($motif) {
            $editeurs = $this->editeurs;
            $editeurs[count($editeurs) - 1]['motif'] = $motif;
            $editeurs[count($editeurs) - 1]['ancienne_echeance'] = $ancienneEcheance?->toDateString();
            $this->editeurs = $editeurs;
        }

        return $this->save();
    }

    public function calculerStatistiques(): array
    {
        return [
            'montant_collecte' => $this->montant_solde,
            'objectif' => $this->cible,
            'pourcentage' => $this->pourcentage_realise,
            'reste' => $this->reste,
            'statut' => $this->objectif_atteint ? 'Soldé' : 'En cours',
            'en_retard' => $this->est_en_retard,
            'jours_retard' => $this->jours_retard,
            'niveau_urgence' => $this->niveau_urgence_libelle,
            'doit_etre_rappele' => $this->doit_etre_rappele,
            'donateur' => $this->nom_donateur
        ];
    }

    public static function statistiquesGlobales(string $moissonId = null)
    {
        $query = self::where('status', true);

        if ($moissonId) {
            $query->where('moisson_id', $moissonId);
        }

        return [
            'totaux' => $query->selectRaw('
                COUNT(*) as nombre_total,
                SUM(cible) as objectif_total,
                SUM(montant_solde) as collecte_totale,
                SUM(reste) as reste_total,
                COUNT(CASE WHEN montant_solde >= cible THEN 1 END) as nombre_soldes,
                ROUND(AVG(montant_solde * 100.0 / NULLIF(cible, 0)), 2) as pourcentage_moyen
            ')->first(),

            'par_categorie' => $query->selectRaw('
                categorie,
                COUNT(*) as nombre,
                SUM(cible) as objectif,
                SUM(montant_solde) as collecte,
                SUM(reste) as reste
            ')->groupBy('categorie')->get(),

            'retards' => self::enRetard()
                ->when($moissonId, fn($q) => $q->where('moisson_id', $moissonId))
                ->selectRaw('
                    COUNT(*) as total_en_retard,
                    COUNT(CASE WHEN CURRENT_DATE - date_echeance > 30 THEN 1 END) as critique,
                    COUNT(CASE WHEN CURRENT_DATE - date_echeance BETWEEN 15 AND 30 THEN 1 END) as important,
                    COUNT(CASE WHEN CURRENT_DATE - date_echeance BETWEEN 7 AND 15 THEN 1 END) as modere,
                    COUNT(CASE WHEN CURRENT_DATE - date_echeance <= 7 THEN 1 END) as recent
                ')->first()
        ];
    }

    public static function getRappelsDuJour()
    {
        return self::aRappeler(now())
                  ->with(['moisson', 'donateur'])
                  ->orderBy('date_echeance')
                  ->get();
    }

    // Événements du modèle
    protected static function booted()
    {
        static::saving(function ($engagement) {
            // Validations métier
            if ($engagement->cible <= 0) {
                throw new \InvalidArgumentException('La cible doit être supérieure à 0');
            }

            if ($engagement->categorie === 'entite_physique' && !$engagement->donateur_id) {
                throw new \InvalidArgumentException('Un donateur doit être spécifié pour une entité physique');
            }

            if ($engagement->categorie === 'entite_morale') {
                $engagement->donateur_id = null;
                if (!$engagement->nom_entite) {
                    throw new \InvalidArgumentException('Le nom de l\'entité morale doit être spécifié');
                }
            }

            if ($engagement->email && !filter_var($engagement->email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('Format d\'email invalide');
            }

            if ($engagement->date_rappel && $engagement->date_echeance &&
                $engagement->date_rappel->isAfter($engagement->date_echeance)) {
                throw new \InvalidArgumentException('La date de rappel ne peut pas être après l\'échéance');
            }
        });

        static::created(function ($engagement) {
            $engagement->ajouterEditeur($engagement->enregistrer_par, 'création');
            $engagement->saveQuietly();
        });

        static::updated(function ($engagement) {
            if ($engagement->wasChanged(['montant_solde', 'cible', 'date_echeance'])) {
                \Log::info("Engagement moisson {$engagement->id} modifié", [
                    'moisson_id' => $engagement->moisson_id,
                    'categorie' => $engagement->categorie,
                    'donateur' => $engagement->nom_donateur,
                    'changes' => $engagement->getChanges()
                ]);
            }
        });
    }
}
