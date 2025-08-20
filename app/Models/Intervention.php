<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intervention extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'culte_id',
        'reunion_id',
        'intervenant_id',
        'titre',
        'type_intervention',
        'heure_debut',
        'heure_fin',
        'duree_minutes',
        'ordre_passage',
        'description',
        'passage_biblique',
        'points_cles',
        'qualite',
        'commentaires',
        'notes_responsable',
        'statut',
        'assignee_par',
        'assignee_le',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'duree_minutes' => 'integer',
        'ordre_passage' => 'integer',
        'assignee_le' => 'datetime',
    ];

    /**
     * Validation pour s'assurer qu'au moins culte_id ou reunion_id est rempli
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($intervention) {
            if (!$intervention->culte_id && !$intervention->reunion_id) {
                throw new \InvalidArgumentException('Une intervention doit être associée soit à un culte soit à une réunion');
            }
        });

        static::updating(function ($intervention) {
            if (!$intervention->culte_id && !$intervention->reunion_id) {
                throw new \InvalidArgumentException('Une intervention doit être associée soit à un culte soit à une réunion');
            }
        });
    }

    /**
     * Relation avec le culte
     */
    public function culte()
    {
        return $this->belongsTo(Culte::class, 'culte_id');
    }

    /**
     * Relation avec la réunion
     */
    public function reunion()
    {
        return $this->belongsTo(Reunion::class, 'reunion_id');
    }

    /**
     * Relation avec l'intervenant
     */
    public function intervenant()
    {
        return $this->belongsTo(User::class, 'intervenant_id');
    }

    /**
     * Relation avec l'utilisateur qui a assigné l'intervention
     */
    public function assignePar()
    {
        return $this->belongsTo(User::class, 'assignee_par');
    }

    /**
     * Scope pour les interventions prévues
     */
    public function scopePrevues($query)
    {
        return $query->where('statut', 'prevue');
    }

    /**
     * Scope pour les interventions terminées
     */
    public function scopeTerminees($query)
    {
        return $query->where('statut', 'terminee');
    }

    /**
     * Scope pour les interventions en cours
     */
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    /**
     * Scope pour filtrer par type d'intervention
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_intervention', $type);
    }

    /**
     * Scope pour filtrer par intervenant
     */
    public function scopeParIntervenant($query, $intervenantId)
    {
        return $query->where('intervenant_id', $intervenantId);
    }

    /**
     * Scope pour ordonner par ordre de passage
     */
    public function scopeParOrdre($query)
    {
        return $query->orderBy('ordre_passage');
    }

    /**
     * Scope pour les interventions d'un culte spécifique
     */
    public function scopePourCulte($query, $culteId)
    {
        return $query->where('culte_id', $culteId);
    }

    /**
     * Scope pour les interventions d'une réunion spécifique
     */
    public function scopePourReunion($query, $reunionId)
    {
        return $query->where('reunion_id', $reunionId);
    }

    /**
     * Scope pour les prédications
     */
    public function scopePredications($query)
    {
        return $query->where('type_intervention', 'predication');
    }

    /**
     * Scope pour les témoignages
     */
    public function scopeTemoignages($query)
    {
        return $query->where('type_intervention', 'temoignage');
    }

    /**
     * Obtenir l'événement associé (culte ou réunion)
     */
    public function getEvenementAttribute()
    {
        return $this->culte ?: $this->reunion;
    }

    /**
     * Obtenir le type d'événement
     */
    public function getTypeEvenementAttribute()
    {
        return $this->culte_id ? 'culte' : 'reunion';
    }

    /**
     * Obtenir le titre de l'événement
     */
    public function getTitreEvenementAttribute()
    {
        $evenement = $this->getEvenementAttribute();
        return $evenement ? $evenement->titre : null;
    }

    /**
     * Obtenir la date de l'événement
     */
    public function getDateEvenementAttribute()
    {
        if ($this->culte) {
            return $this->culte->date_culte;
        }

        if ($this->reunion) {
            return $this->reunion->date_reunion;
        }

        return null;
    }

    /**
     * Calculer la durée de l'intervention
     */
    public function calculerDuree()
    {
        if ($this->heure_debut && $this->heure_fin) {
            $duree = $this->heure_debut->diffInMinutes($this->heure_fin);
            $this->update(['duree_minutes' => $duree]);
            return $duree;
        }

        return $this->duree_minutes;
    }

    /**
     * Commencer l'intervention
     */
    public function commencer()
    {
        $this->update([
            'statut' => 'en_cours',
            'heure_debut' => now()->format('H:i'),
        ]);
    }

    /**
     * Terminer l'intervention
     */
    public function terminer()
    {
        $this->update([
            'statut' => 'terminee',
            'heure_fin' => now()->format('H:i'),
        ]);

        $this->calculerDuree();
    }

    /**
     * Annuler l'intervention
     */
    public function annuler()
    {
        $this->update(['statut' => 'annulee']);
    }

    /**
     * Vérifier si l'intervention peut être modifiée
     */
    public function canBeModified()
    {
        return $this->statut === 'prevue';
    }

    /**
     * Vérifier si l'intervention peut être supprimée
     */
    public function canBeDeleted()
    {
        return in_array($this->statut, ['prevue', 'annulee']);
    }

    /**
     * Obtenir la prochaine intervention dans l'ordre
     */
    public function getSuivanteAttribute()
    {
        $query = static::where('ordre_passage', '>', $this->ordre_passage);

        if ($this->culte_id) {
            $query->where('culte_id', $this->culte_id);
        } else {
            $query->where('reunion_id', $this->reunion_id);
        }

        return $query->orderBy('ordre_passage')->first();
    }

    /**
     * Obtenir l'intervention précédente dans l'ordre
     */
    public function getPrecedenteAttribute()
    {
        $query = static::where('ordre_passage', '<', $this->ordre_passage);

        if ($this->culte_id) {
            $query->where('culte_id', $this->culte_id);
        } else {
            $query->where('reunion_id', $this->reunion_id);
        }

        return $query->orderBy('ordre_passage', 'desc')->first();
    }

    /**
     * Réorganiser l'ordre des interventions
     */
    public static function reorganiserOrdre($evenementId, $typeEvenement, $nouvelOrdre)
    {
        $interventions = static::when($typeEvenement === 'culte', function($query) use ($evenementId) {
                return $query->where('culte_id', $evenementId);
            })
            ->when($typeEvenement === 'reunion', function($query) use ($evenementId) {
                return $query->where('reunion_id', $evenementId);
            })
            ->orderBy('ordre_passage')
            ->get();

        foreach ($nouvelOrdre as $index => $interventionId) {
            $intervention = $interventions->firstWhere('id', $interventionId);
            if ($intervention) {
                $intervention->update(['ordre_passage' => $index + 1]);
            }
        }
    }

    /**
     * Obtenir les statistiques de qualité pour un intervenant
     */
    public static function getStatistiquesQualite($intervenantId)
    {
        $interventions = static::where('intervenant_id', $intervenantId)
            ->where('statut', 'terminee')
            ->whereNotNull('qualite')
            ->get();

        if ($interventions->isEmpty()) {
            return null;
        }

        $qualites = $interventions->pluck('qualite');
        $mapping = [
            'excellente' => 4,
            'bonne' => 3,
            'satisfaisante' => 2,
            'a_ameliorer' => 1
        ];

        $notes = $qualites->map(function($qualite) use ($mapping) {
            return $mapping[$qualite] ?? 0;
        });

        return [
            'total_interventions' => $interventions->count(),
            'note_moyenne' => $notes->avg(),
            'repartition' => $qualites->countBy(),
            'derniere_intervention' => $interventions->max('created_at'),
        ];
    }
}
