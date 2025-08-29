<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intervention extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'culte_id',
        'reunion_id',
        'intervenant_id',
        'titre',
        'type_intervention',
        'heure_debut',
        'duree_minutes',
        'ordre_passage',
        'description',
        'passage_biblique',
        'statut',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'id' => 'string',
        'culte_id' => 'string',
        'reunion_id' => 'string',
        'intervenant_id' => 'string',
        'heure_debut' => 'datetime:H:i',
        'duree_minutes' => 'integer',
        'ordre_passage' => 'integer',
        'type_intervention' => 'string',
        'statut' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Les types d'intervention disponibles
     */
    public const TYPES_INTERVENTION = [
        'predication' => 'Prédication',
        'temoignage' => 'Témoignage',
        'priere' => 'Prière',
        'louange' => 'Louange',
        'lecture' => 'Lecture',
        'annonce' => 'Annonce',
        'offrande' => 'Offrande',
        'accueil' => 'Accueil',
        'benediction' => 'Bénédiction',
        'presentation' => 'Présentation',
        'animation' => 'Animation',
        'autre' => 'Autre'
    ];

    /**
     * Les statuts disponibles
     */
    public const STATUTS = [
        'prevue' => 'Prévue',
        'terminee' => 'Terminée',
        'annulee' => 'Annulée'
    ];

    /**
     * Relation avec le culte
     */
    public function culte(): BelongsTo
    {
        return $this->belongsTo(Culte::class, 'culte_id');
    }

    /**
     * Relation avec la réunion
     */
    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class, 'reunion_id');
    }

    /**
     * Relation avec l'intervenant (User)
     */
    public function intervenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'intervenant_id');
    }

    /**
     * Scope pour filtrer par type d'événement
     */
    public function scopePourCulte($query, $culteId)
    {
        return $query->where('culte_id', $culteId);
    }

    /**
     * Scope pour filtrer par réunion
     */
    public function scopePourReunion($query, $reunionId)
    {
        return $query->where('reunion_id', $reunionId);
    }

    /**
     * Scope pour filtrer par intervenant
     */
    public function scopeParIntervenant($query, $intervenantId)
    {
        return $query->where('intervenant_id', $intervenantId);
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour filtrer par type d'intervention
     */
    public function scopeType($query, $type)
    {
        return $query->where('type_intervention', $type);
    }

    /**
     * Scope pour ordonner par ordre de passage
     */
    public function scopeOrdonneesParPassage($query)
    {
        return $query->orderBy('ordre_passage')->orderBy('heure_debut');
    }

    /**
     * Accessor pour obtenir l'heure de fin calculée
     */
    public function getHeureFinAttribute()
    {
        if (!$this->heure_debut) {
            return null;
        }

        return $this->heure_debut->copy()->addMinutes($this->duree_minutes);
    }

    /**
     * Accessor pour obtenir le libellé du type d'intervention
     */
    public function getTypeInterventionLabelAttribute()
    {
        return self::TYPES_INTERVENTION[$this->type_intervention] ?? $this->type_intervention;
    }

    /**
     * Accessor pour obtenir le libellé du statut
     */
    public function getStatutLabelAttribute()
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    /**
     * Mutator pour s'assurer qu'au moins culte_id ou reunion_id est défini
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($intervention) {
            if (empty($intervention->culte_id) && empty($intervention->reunion_id)) {
                throw new \InvalidArgumentException('Une intervention doit être liée soit à un culte, soit à une réunion.');
            }
        });
    }

    /**
     * Méthode pour déterminer le type d'événement parent
     */
    public function getEvenementParent()
    {
        return $this->culte ?? $this->reunion;
    }

    /**
     * Méthode pour obtenir le nom de l'événement parent
     */
    public function getNomEvenementParent()
    {
        $evenement = $this->getEvenementParent();
        return $evenement ? $evenement->nom ?? $evenement->titre : null;
    }

    /**
     * Méthode pour vérifier si l'intervention est terminée
     */
    public function estTerminee(): bool
    {
        return $this->statut === 'terminee';
    }

    /**
     * Méthode pour vérifier si l'intervention est annulée
     */
    public function estAnnulee(): bool
    {
        return $this->statut === 'annulee';
    }

    /**
     * Méthode pour vérifier si l'intervention est prévue
     */
    public function estPrevue(): bool
    {
        return $this->statut === 'prevue';
    }
}
