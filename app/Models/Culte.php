<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Traits\HasCKEditorFields;

class Culte extends Model
{
    use HasFactory, SoftDeletes, HasUuids, HasCKEditorFields;

    protected $table = 'cultes';

    /**
     * Les attributs assignables en masse
     */
    protected $fillable = [
        'programme_id',
        'titre',
        'description',
        'date_culte',
        'heure_debut',
        'heure_fin',
        'heure_debut_reelle',
        'heure_fin_reelle',
        'type_culte',
        'categorie',
        'lieu',
        'adresse_lieu',
        'capacite_prevue',
        'pasteur_principal_id',
        'predicateur_id',
        'responsable_culte_id',
        'dirigeant_louange_id',
        'equipe_culte',
        'titre_message',
        'resume_message',
        'passage_biblique',
        'versets_cles',
        'plan_message',
        'ordre_service',
        'cantiques_chantes',
        'duree_louange',
        'duree_message',
        'duree_priere',
        'nombre_participants',
        'nombre_adultes',
        'nombre_enfants',
        'nombre_jeunes',
        'nombre_nouveaux',
        'nombre_conversions',
        'nombre_baptemes',
        'detail_offrandes',
        'offrande_totale',
        'dime_totale',
        'responsable_finances_id',
        'est_enregistre',
        'lien_enregistrement_audio',
        'lien_enregistrement_video',
        'lien_diffusion_live',
        'photos_culte',
        'diffusion_en_ligne',
        'statut',
        'est_public',
        'necessite_invitation',
        'meteo',
        'atmosphere',
        'notes_pasteur',
        'notes_organisateur',
        'temoignages',
        'points_forts',
        'points_amelioration',
        'demandes_priere',
        'note_globale',
        'note_louange',
        'note_message',
        'note_organisation',
        'cree_par',
        'modifie_par'
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation
     */
    protected $hidden = [];

    /**
     * Les attributs qui doivent être castés
     */
    protected $casts = [
        'date_culte' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'heure_debut_reelle' => 'datetime:H:i',
        'heure_fin_reelle' => 'datetime:H:i',
        'duree_louange' => 'datetime:H:i',
        'duree_message' => 'datetime:H:i',
        'duree_priere' => 'datetime:H:i',
        'capacite_prevue' => 'integer',
        'nombre_participants' => 'integer',
        'nombre_adultes' => 'integer',
        'nombre_enfants' => 'integer',
        'nombre_jeunes' => 'integer',
        'nombre_nouveaux' => 'integer',
        'nombre_conversions' => 'integer',
        'nombre_baptemes' => 'integer',
        'equipe_culte' => 'array',
        'versets_cles' => 'array',
        'ordre_service' => 'array',
        'cantiques_chantes' => 'array',
        'detail_offrandes' => 'array',
        'photos_culte' => 'array',
        'offrande_totale' => 'decimal:2',
        'dime_totale' => 'decimal:2',
        'note_globale' => 'decimal:1',
        'note_louange' => 'decimal:1',
        'note_message' => 'decimal:1',
        'note_organisation' => 'decimal:1',
        'est_enregistre' => 'boolean',
        'diffusion_en_ligne' => 'boolean',
        'est_public' => 'boolean',
        'necessite_invitation' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Constantes pour les énumérations
     */
    const TYPE_CULTE = [
        'dimanche_matin' => 'Dimanche Matin',
        'dimanche_soir' => 'Dimanche Soir',
        'mercredi' => 'Mercredi',
        'vendredi' => 'Vendredi',
        'samedi_jeunes' => 'Samedi Jeunes',
        'special' => 'Spécial',
        'conference' => 'Conférence',
        'seminaire' => 'Séminaire',
        'retraite' => 'Retraite',
        'mariage' => 'Mariage',
        'funerailles' => 'Funérailles',
        'bapteme' => 'Baptême',
        'communion' => 'Communion',
        'noel' => 'Noël',
        'paques' => 'Pâques',
        'nouvel_an' => 'Nouvel An'
    ];

    const CATEGORIE = [
        'regulier' => 'Régulier',
        'special' => 'Spécial',
        'ceremonial' => 'Cérémonial',
        'formation' => 'Formation',
        'evangelisation' => 'Évangélisation'
    ];

    const STATUT = [
        'planifie' => 'Planifié',
        'en_preparation' => 'En Préparation',
        'en_cours' => 'En Cours',
        'termine' => 'Terminé',
        'annule' => 'Annulé',
        'reporte' => 'Reporté'
    ];

    const ATMOSPHERE = [
        'excellent' => 'Excellent',
        'tres_bon' => 'Très Bon',
        'bon' => 'Bon',
        'moyen' => 'Moyen',
        'difficile' => 'Difficile'
    ];

    /**
     * Relations
     */

    /**
     * Relation avec le programme
     */
    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'programme_id');
    }

    /**
     * Relation avec le pasteur principal
     */
    public function pasteurPrincipal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pasteur_principal_id');
    }

    /**
     * Relation avec le prédicateur
     */
    public function predicateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'predicateur_id');
    }

    /**
     * Relation avec le responsable du culte
     */
    public function responsableCulte(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_culte_id');
    }

    /**
     * Relation avec le dirigeant de louange
     */
    public function dirigeantLouange(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dirigeant_louange_id');
    }

    /**
     * Relation avec le responsable des finances
     */
    public function responsableFinances(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_finances_id');
    }

    /**
     * Relation avec l'membres créateur
     */
    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Relation avec l'membres modificateur
     */
    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    /**
     * Mutateurs
     */

    /**
     * Mutateur pour le titre (nettoyer les espaces)
     */
    public function setTitreAttribute($value)
    {
        $this->attributes['titre'] = trim($value);
    }

    /**
     * Mutateur pour automatiquement définir modifie_par
     */
    public function setModifieParAttribute($value)
    {
        $this->attributes['modifie_par'] = $value ?? auth()->id();
    }

    /**
     * Accesseurs
     */

    /**
     * Accesseur pour le libellé du type de culte
     */
    public function getTypeCulteLibelleAttribute(): string
    {
        return self::TYPE_CULTE[$this->type_culte] ?? $this->type_culte;
    }

    /**
     * Accesseur pour le libellé de la catégorie
     */
    public function getCategorieLibelleAttribute(): string
    {
        return self::CATEGORIE[$this->categorie] ?? $this->categorie;
    }

    /**
     * Accesseur pour le libellé du statut
     */
    public function getStatutLibelleAttribute(): string
    {
        return self::STATUT[$this->statut] ?? $this->statut;
    }

    /**
     * Accesseur pour le libellé de l'atmosphère
     */
    public function getAtmosphereLibelleAttribute(): ?string
    {
        return $this->atmosphere ? (self::ATMOSPHERE[$this->atmosphere] ?? $this->atmosphere) : null;
    }

    /**
     * Calculer la durée totale du culte
     */
    public function getDureeTotaleAttribute(): ?string
    {
        if ($this->heure_debut_reelle && $this->heure_fin_reelle) {
            $debut = \Carbon\Carbon::parse($this->heure_debut_reelle);
            $fin = \Carbon\Carbon::parse($this->heure_fin_reelle);
            return $debut->diff($fin)->format('%H:%I:%S');
        }

        if ($this->heure_debut && $this->heure_fin) {
            $debut = \Carbon\Carbon::parse($this->heure_debut);
            $fin = \Carbon\Carbon::parse($this->heure_fin);
            return $debut->diff($fin)->format('%H:%I:%S');
        }

        return null;
    }

    /**
     * Vérifier si le culte est terminé
     */
    public function getIsTermineAttribute(): bool
    {
        return $this->statut === 'termine';
    }

    /**
     * Vérifier si le culte est à venir
     */
    public function getIsAVenirAttribute(): bool
    {
        return in_array($this->statut, ['planifie', 'en_preparation']) &&
               $this->date_culte >= now()->toDateString();
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les cultes publics
     */
    public function scopePublic($query)
    {
        return $query->where('est_public', true);
    }

    /**
     * Scope pour les cultes à venir
     */
    public function scopeAVenir($query)
    {
        return $query->where('date_culte', '>=', now()->toDateString())
                    ->whereIn('statut', ['planifie', 'en_preparation']);
    }

    /**
     * Scope pour les cultes terminés
     */
    public function scopeTermines($query)
    {
        return $query->where('statut', 'termine');
    }

    /**
     * Scope pour filtrer par type de culte
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_culte', $type);
    }

    /**
     * Scope pour filtrer par date
     */
    public function scopeParDate($query, $date)
    {
        return $query->whereDate('date_culte', $date);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_culte', [$dateDebut, $dateFin]);
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Automatiquement définir cree_par lors de la création
        static::creating(function ($model) {
            if (!$model->cree_par) {
                $model->cree_par = auth()->id();
            }
        });

        // Automatiquement définir modifie_par lors de la mise à jour
        static::updating(function ($model) {
            $model->modifie_par = auth()->id();
        });
    }



    // Nouveaux accessors pour CKEditor
    public function getDescriptionFormattedAttribute()
    {
        return $this->getFormattedContent('description');
    }

    public function getResumeMessageFormattedAttribute()
    {
        return $this->getFormattedContent('resume_message');
    }

    public function getPlanMessageFormattedAttribute()
    {
        return $this->getFormattedContent('plan_message');
    }

    public function getNotesFormattedAttribute()
    {
        return [
            'pasteur' => $this->getFormattedContent('notes_pasteur'),
            'organisateur' => $this->getFormattedContent('notes_organisateur')
        ];
    }

    public function getPointsFormattedAttribute()
    {
        return [
            'forts' => $this->getFormattedContent('points_forts'),
            'amelioration' => $this->getFormattedContent('points_amelioration')
        ];
    }

    public function getTemoignagesFormattedAttribute()
    {
        return $this->getFormattedContent('temoignages');
    }

    // Méthodes utilitaires
    public function getMessageWordCount()
    {
        return $this->getWordCount('resume_message') + $this->getWordCount('plan_message');
    }

    public function getMessageReadingTime()
    {
        $totalWords = $this->getWordCount('resume_message') + $this->getWordCount('plan_message');
        return max(1, ceil($totalWords / 200));
    }

    public function hasRichContent()
    {
        foreach ($this->getCKEditorFields() as $field) {
            $content = $this->getAttribute($field);
            if (!empty($content) && $content !== strip_tags($content)) {
                return true;
            }
        }
        return false;
    }

    // Scopes
    public function scopeWithContent($query)
    {
        return $query->where(function ($q) {
            foreach ($this->getCKEditorFields() as $field) {
                $q->orWhereNotNull($field)
                  ->orWhere($field, '!=', '');
            }
        });
    }

    public function scopeSearchContent($query, $search)
    {
        return $this->scopeSearchInCKEditorFields($query, $search);
    }
}
