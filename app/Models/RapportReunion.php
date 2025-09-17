<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\HasCKEditorFields;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RapportReunion extends Model
{
    use HasFactory, SoftDeletes, HasUuids, HasCKEditorFields;

    /**
     * Table associée au modèle
     */
    protected $table = 'rapport_reunions';

    /**
     * La clé primaire du modèle
     */
    protected $primaryKey = 'id';

    /**
     * Le type de la clé primaire
     */
    protected $keyType = 'string';

    /**
     * Indique si les IDs sont auto-incrémentés
     */
    public $incrementing = false;

    /**
     * Les attributs qui peuvent être assignés en masse
     */
    protected $fillable = [
        'reunion_id',
        'titre_rapport',
        'type_rapport',
        'redacteur_id',
        'validateur_id',
        'statut',
        'resume',
        'points_traites',
        'decisions_prises',
        'actions_decidees',
        'presences',
        'nombre_presents',
        'montant_collecte',
        'actions_suivre',
        'recommandations',
        'note_satisfaction',
        'commentaires',
        'cree_par',
        'modifie_par',
    ];

    /**
     * Les attributs qui doivent être cachés pour les tableaux
     */
    protected $hidden = [
        'deleted_at',
    ];

    /**
     * Les attributs qui doivent être castés
     */
    protected $casts = [
        'points_traites' => 'array',
        'presences' => 'array',
        'actions_suivre' => 'array',
        'valide_le' => 'datetime',
        'publie_le' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'nombre_presents' => 'integer',
        'montant_collecte' => 'decimal:2',
        'note_satisfaction' => 'integer',
    ];

    /**
     * Les attributs qui doivent être mutés en dates
     */
    protected $dates = [
        'valide_le',
        'publie_le',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Les valeurs par défaut des attributs
     */
    protected $attributes = [
        'statut' => 'brouillon',
        'nombre_presents' => 0,
    ];

    // ================================
    // CONSTANTES ENUM
    // ================================

    public const TYPES_RAPPORT = [
        'PROCES_VERBAL' => 'proces_verbal',
        'COMPTE_RENDU' => 'compte_rendu',
        'RAPPORT_ACTIVITE' => 'rapport_activite',
        'RAPPORT_FINANCIER' => 'rapport_financier',
    ];

    public const STATUTS = [
        'BROUILLON' => 'brouillon',
        'EN_REVISION' => 'en_revision',
        'VALIDE' => 'valide',
        'PUBLIE' => 'publie',
    ];

    public const WORKFLOW_ORDER = [
        'brouillon' => 1,
        'en_revision' => 2,
        'valide' => 3,
        'publie' => 4,
    ];

    // ================================
    // UTILITAIRES DATABASE
    // ================================

    /**
     * Génère la requête de différence en jours selon le driver de base de données
     */
    protected static function dateDiffQuery(string $laterDate, string $earlierDate): string
    {
        $driver = DB::connection()->getDriverName();

        return match ($driver) {
            'pgsql' => "EXTRACT(DAY FROM ({$laterDate} - {$earlierDate}))",
            'sqlite' => "julianday({$laterDate}) - julianday({$earlierDate})",
            'mysql', 'mariadb' => "DATEDIFF({$laterDate}, {$earlierDate})",
            'sqlsrv' => "DATEDIFF(day, {$earlierDate}, {$laterDate})",
            default => "EXTRACT(DAY FROM ({$laterDate} - {$earlierDate}))" // Fallback PostgreSQL
        };
    }

    // ================================
    // RELATIONS ELOQUENT
    // ================================

    /**
     * Relation avec la réunion concernée
     */
    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class, 'reunion_id');
    }

    /**
     * Relation avec le rédacteur du rapport
     */
    public function redacteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'redacteur_id');
    }

    /**
     * Relation avec le validateur du rapport
     */
    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

    /**
     * Relation avec l'membres créateur
     */
    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Relation avec le dernier modificateur
     */
    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    // ================================
    // SCOPES DE REQUÊTE
    // ================================

    /**
     * Scope pour filtrer par statut
     */
    public function scopeParStatut(Builder $query, string $statut): Builder
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour filtrer par type de rapport
     */
    public function scopeParType(Builder $query, string $type): Builder
    {
        return $query->where('type_rapport', $type);
    }

    /**
     * Scope pour les rapports en brouillon
     */
    public function scopeBrouillons(Builder $query): Builder
    {
        return $query->where('statut', self::STATUTS['BROUILLON']);
    }

    /**
     * Scope pour les rapports en révision
     */
    public function scopeEnRevision(Builder $query): Builder
    {
        return $query->where('statut', self::STATUTS['EN_REVISION']);
    }

    /**
     * Scope pour les rapports validés
     */
    public function scopeValides(Builder $query): Builder
    {
        return $query->where('statut', self::STATUTS['VALIDE']);
    }

    /**
     * Scope pour les rapports publiés
     */
    public function scopePublies(Builder $query): Builder
    {
        return $query->where('statut', self::STATUTS['PUBLIE']);
    }

    /**
     * Scope pour les rapports d'un rédacteur
     */
    public function scopeParRedacteur(Builder $query, string $redacteurId): Builder
    {
        return $query->where('redacteur_id', $redacteurId);
    }

    /**
     * Scope pour les rapports récents
     */
    public function scopeRecents(Builder $query, int $jours = 30): Builder
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($jours));
    }

    /**
     * Scope pour les rapports avec des actions en cours
     */
    public function scopeAvecActionsSuivre(Builder $query): Builder
    {
        return $query->whereNotNull('actions_suivre')
                    ->where('actions_suivre', '!=', '[]');
    }

    /**
     * Scope pour les rapports avec des notes de satisfaction élevées
     */
    public function scopeSatisfactionElevee(Builder $query, int $noteMin = 4): Builder
    {
        return $query->where('note_satisfaction', '>=', $noteMin);
    }

    // ================================
    // MUTATORS & ACCESSORS
    // ================================

    /**
     * Mutator pour le titre du rapport
     */
    public function setTitreRapportAttribute(string $value): void
    {
        $this->attributes['titre_rapport'] = trim($value);
    }

    /**
     * Accessor pour le titre formaté
     */
    public function getTitreFormatAttribute(): string
    {
        return Str::title($this->titre_rapport);
    }

    /**
     * Accessor pour le statut traduit
     */
    public function getStatutTraduitAttribute(): string
    {
        return match($this->statut) {
            'brouillon' => 'Brouillon',
            'en_revision' => 'En révision',
            'valide' => 'Validé',
            'publie' => 'Publié',
            default => $this->statut,
        };
    }

    /**
     * Accessor pour le type traduit
     */
    public function getTypeRapportTraduitAttribute(): string
    {
        return match($this->type_rapport) {
            'proces_verbal' => 'Procès-verbal',
            'compte_rendu' => 'Compte-rendu',
            'rapport_activite' => 'Rapport d\'activité',
            'rapport_financier' => 'Rapport financier',
            default => $this->type_rapport,
        };
    }

    /**
     * Accessor pour le nombre de jours depuis la création
     */
    public function getJoursDepuisCreationAttribute(): int
    {
        return $this->created_at->diffInDays(Carbon::now());
    }

    /**
     * Accessor pour vérifier si le rapport est modifiable
     */
    public function getEstModifiableAttribute(): bool
    {
        return in_array($this->statut, ['brouillon', 'en_revision']);
    }

    /**
     * Accessor pour le pourcentage de completion
     */
    public function getPourcentageCompletionAttribute(): int
    {
        $champsObligatoires = [
            'titre_rapport', 'resume', 'decisions_prises',
            'actions_decidees', 'redacteur_id'
        ];

        $champsRemplis = 0;
        foreach ($champsObligatoires as $champ) {
            if (!empty($this->$champ)) {
                $champsRemplis++;
            }
        }

        return round(($champsRemplis / count($champsObligatoires)) * 100);
    }

    // ================================
    // MÉTHODES MÉTIER
    // ================================

    /**
     * Passer le rapport en révision
     */
    public function passerEnRevision(string $userId = null): bool
    {
        if ($this->statut !== 'brouillon') {
            return false;
        }

        $this->statut = 'en_revision';
        if ($userId) {
            $this->modifie_par = $userId;
        }

        return $this->save();
    }

    /**
     * Valider le rapport
     */
    public function valider(string $validateurId, string $commentaires = null): bool
    {
        if (!in_array($this->statut, ['en_revision', 'brouillon'])) {
            return false;
        }

        $this->statut = 'valide';
        $this->validateur_id = $validateurId;
        $this->valide_le = Carbon::now();
        $this->modifie_par = $validateurId;

        if ($commentaires) {
            $this->commentaires = $commentaires;
        }

        return $this->save();
    }

    /**
     * Publier le rapport
     */
    public function publier(string $userId = null): bool
    {
        if ($this->statut !== 'valide') {
            return false;
        }

        $this->statut = 'publie';
        $this->publie_le = Carbon::now();
        if ($userId) {
            $this->modifie_par = $userId;
        }

        return $this->save();
    }

    /**
     * Rejeter le rapport
     */
    public function rejeter(string $raison, string $userId = null): bool
    {
        if (!in_array($this->statut, ['en_revision', 'valide'])) {
            return false;
        }

        $this->statut = 'brouillon';
        $this->commentaires = "Rejeté: " . $raison;
        $this->valide_le = null;
        $this->publie_le = null;
        if ($userId) {
            $this->modifie_par = $userId;
        }

        return $this->save();
    }

    /**
     * Ajouter une présence
     */
    public function ajouterPresence(array $presence): void
    {
        $presences = $this->presences ?? [];
        $presences[] = $presence;
        $this->presences = $presences;
        $this->nombre_presents = count($presences);
        $this->save();
    }

    /**
     * Supprimer une présence
     */
    public function supprimerPresence(string $userId): void
    {
        $presences = collect($this->presences ?? [])->filter(function ($presence) use ($userId) {
            return $presence['user_id'] !== $userId;
        })->values()->toArray();

        $this->presences = $presences;
        $this->nombre_presents = count($presences);
        $this->save();
    }

    /**
     * Ajouter une action de suivi
     */
    public function ajouterActionSuivi(array $action): void
    {
        $actions = $this->actions_suivre ?? [];
        $action['id'] = Str::uuid()->toString();
        $action['created_at'] = Carbon::now()->toISOString();
        $actions[] = $action;
        $this->actions_suivre = $actions;
        $this->save();
    }

    /**
     * Marquer une action comme terminée
     */
    public function terminerAction(string $actionId): bool
    {
        $actions = collect($this->actions_suivre ?? []);

        $actions = $actions->map(function ($action) use ($actionId) {
            if ($action['id'] === $actionId) {
                $action['terminee'] = true;
                $action['terminee_le'] = Carbon::now()->toISOString();
            }
            return $action;
        });

        $this->actions_suivre = $actions->toArray();
        return $this->save();
    }

    /**
     * Calculer les statistiques du rapport (Compatible PostgreSQL)
     */
    public function getStatistiques(): array
    {
        // Calcul du taux de présence
        $tauxPresence = null;
        if ($this->reunion && $this->reunion->nombre_attendus > 0) {
            $tauxPresence = round(($this->nombre_presents / $this->reunion->nombre_attendus) * 100, 2);
        }

        // Calcul des jours pour validation (utilise Carbon pour la portabilité)
        $joursValidation = null;
        if ($this->valide_le) {
            $joursValidation = $this->created_at->diffInDays(Carbon::parse($this->valide_le));
        }

        // Calcul des jours pour publication
        $joursPublication = null;
        if ($this->publie_le && $this->valide_le) {
            $joursPublication = Carbon::parse($this->valide_le)->diffInDays(Carbon::parse($this->publie_le));
        }

        return [
            'nombre_points_traites' => count($this->points_traites ?? []),
            'nombre_actions_suivre' => count($this->actions_suivre ?? []),
            'actions_terminees' => collect($this->actions_suivre ?? [])
                ->where('terminee', true)->count(),
            'taux_presence' => $tauxPresence,
            'jours_pour_validation' => $joursValidation,
            'jours_pour_publication' => $joursPublication,
        ];
    }

    /**
     * Vérifier si l'membres peut modifier ce rapport
     */
    public function peutEtreModifiePar(User $user): bool
    {
        // Le créateur ou rédacteur peut toujours modifier en brouillon/révision
        if ($this->est_modifiable &&
            ($this->cree_par === $user->id || $this->redacteur_id === $user->id)) {
            return true;
        }

        // Les validateurs peuvent modifier en révision
        if ($this->statut === 'en_revision' && $this->validateur_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Générer un résumé automatique
     */
    public function genererResumeAuto(): string
    {
        $elements = [];

        if ($this->reunion) {
            $elements[] = "Rapport de {$this->type_rapport_traduit} pour la réunion '{$this->reunion->titre}'";
            $elements[] = "tenue le " . $this->reunion->date_reunion->format('d/m/Y');
        }

        if ($this->nombre_presents > 0) {
            $elements[] = "{$this->nombre_presents} participants présents";
        }

        if (!empty($this->points_traites)) {
            $elements[] = count($this->points_traites) . " points traités";
        }

        if (!empty($this->actions_suivre)) {
            $elements[] = count($this->actions_suivre) . " actions de suivi définies";
        }

        return implode(', ', $elements) . '.';
    }

    // ================================
    // ÉVÉNEMENTS DU MODÈLE
    // ================================

    /**
     * Les événements de démarrage du modèle
     */
    protected static function boot(): void
    {
        parent::boot();

        // Génération automatique de l'UUID
        static::creating(function (self $model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }

            // Générer un titre par défaut si vide
            if (empty($model->titre_rapport) && $model->reunion) {
                $model->titre_rapport = "Rapport - " . $model->reunion->titre;
            }
        });

        // Mise à jour du modificateur
        static::updating(function (self $model) {
            if (auth()->check()) {
                $model->modifie_par = auth()->id();
            }
        });

        // Log des changements de statut
        static::updated(function (self $model) {
            if ($model->isDirty('statut')) {
                Log::info("Rapport {$model->id} - Statut changé de {$model->getOriginal('statut')} vers {$model->statut}");
            }
        });
    }

    // ================================
    // MÉTHODES STATIQUES UTILES
    // ================================

    /**
     * Obtenir les rapports en attente de validation
     */
    public static function enAttenteValidation(): Builder
    {
        return static::whereIn('statut', ['en_revision']);
    }

    /**
     * Obtenir les statistiques globales des rapports (Compatible PostgreSQL)
     */
    public static function getStatistiquesGlobales(): array
    {
        // Calcul du délai de validation avec support multi-DB
        $dateDiffSql = static::dateDiffQuery('valide_le', 'created_at');
        $delaiValidationMoyen = static::whereNotNull('valide_le')
            ->selectRaw("AVG({$dateDiffSql}) as delai")
            ->value('delai');

        return [
            'total' => static::count(),
            'brouillons' => static::brouillons()->count(),
            'en_revision' => static::enRevision()->count(),
            'valides' => static::valides()->count(),
            'publies' => static::publies()->count(),
            'satisfaction_moyenne' => static::whereNotNull('note_satisfaction')
                ->avg('note_satisfaction'),
            'delai_validation_moyen' => $delaiValidationMoyen ? round($delaiValidationMoyen, 1) : null,
        ];
    }

    // ================================
    // VALIDATION RULES
    // ================================

    /**
     * Règles de validation pour la création/mise à jour
     */
    public static function validationRules(string $id = null): array
    {
        return [
            'reunion_id' => 'required|uuid|exists:reunions,id',
            'titre_rapport' => 'required|string|max:200|min:5',
            'type_rapport' => 'required|in:' . implode(',', self::TYPES_RAPPORT),
            'redacteur_id' => 'nullable|uuid|exists:users,id',
            'validateur_id' => 'nullable|uuid|exists:users,id',
            // 'statut' => 'required|in:' . implode(',', array: self::STATUTS),
            'resume' => 'nullable|string|max:2000',
            'points_traites' => 'nullable|array',
            'decisions_prises' => 'nullable|string',
            'actions_decidees' => 'nullable|string',
            'presences' => 'nullable|json',
            'nombre_presents' => 'integer|min:0|max:1000',
            'montant_collecte' => 'nullable|numeric|min:0|max:999999.99',
            'actions_suivre' => 'nullable|json',
            'recommandations' => 'nullable|string',
            'note_satisfaction' => 'nullable|integer|between:1,5',
            'commentaires' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Messages de validation personnalisés
     */
    public static function validationMessages(): array
    {
        return [
            'reunion_id.required' => 'La réunion est obligatoire.',
            'reunion_id.exists' => 'Cette réunion n\'existe pas.',
            'titre_rapport.required' => 'Le titre du rapport est obligatoire.',
            'titre_rapport.min' => 'Le titre doit faire au moins 5 caractères.',
            'titre_rapport.max' => 'Le titre ne peut pas dépasser 200 caractères.',
            'type_rapport.in' => 'Le type de rapport n\'est pas valide.',
            'nombre_presents.min' => 'Le nombre de présents ne peut pas être négatif.',
            'montant_collecte.min' => 'Le montant ne peut pas être négatif.',
            'note_satisfaction.between' => 'La note doit être comprise entre 1 et 5.',
        ];
    }
}
