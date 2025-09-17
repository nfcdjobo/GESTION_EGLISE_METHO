<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classe extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Le nom de la table associée au modèle.
     */
    protected $table = 'classes';

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'nom',
        'description',
        'tranche_age',
        'age_minimum',
        'age_maximum',
        'nombre_inscrits',
        'responsable_id',
        'enseignant_principal_id',
        'programme',
        'image_classe',
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation.
     */
    protected $hidden = [];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'age_minimum' => 'integer',
        'age_maximum' => 'integer',
        'nombre_inscrits' => 'integer',
        'programme' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Les attributs qui sont des dates.
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Relation avec le responsable de la classe
     */
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    /**
     * Relation avec l'enseignant principal
     */
    public function enseignantPrincipal()
    {
        return $this->belongsTo(User::class, 'enseignant_principal_id');
    }

    /**
     * Relation avec les membres (membres de la classe)
     */
    public function membres()
    {
        return $this->hasMany(User::class, 'classe_id');
    }

    /**
     * Relation avec les membres actifs de la classe
     */
    public function membresActifs()
    {
        return $this->hasMany(User::class, 'classe_id')->where('actif', true);
    }

    /**
     * Scope pour les classes actives (ayant un responsable)
     */
    public function scopeActives($query)
    {
        return $query->whereNotNull('responsable_id');
    }

    /**
     * Scope pour filtrer par tranche d'âge
     */
    public function scopeParTrancheAge($query, $tranche)
    {
        return $query->where('tranche_age', $tranche);
    }

    /**
     * Scope pour les classes ayant des places disponibles
     */
    public function scopeAvecPlacesDisponibles($query, $capaciteMax = 50)
    {
        return $query->where('nombre_inscrits', '<', $capaciteMax);
    }

    /**
     * Scope pour filtrer par âge minimum
     */
    public function scopeAgeMinimum($query, $age)
    {
        return $query->where('age_minimum', '<=', $age);
    }

    /**
     * Scope pour filtrer par âge maximum
     */
    public function scopeAgeMaximum($query, $age)
    {
        return $query->where('age_maximum', '>=', $age);
    }

    /**
     * Accesseur pour obtenir le nombre de places disponibles
     */
    // public function getPlacesDisponiblesAttribute()
    // {
    //     // Capacité maximale par défaut de 50
    //     $capaciteMax = 50;
    //     return max(0, $capaciteMax - $this->nombre_inscrits);
    // }

    /**
     * Accesseur pour obtenir le nom complet avec tranche d'âge
     */
    public function getNomCompletAttribute()
    {
        return $this->nom . ($this->tranche_age ? ' (' . $this->tranche_age . ')' : '');
    }

    /**
     * Accesseur pour vérifier si la classe est complète
     */
    public function getEstCompleteAttribute()
    {
        $capaciteMax = 50;
        return $this->nombre_inscrits >= $capaciteMax;
    }

    /**
     * Accesseur pour obtenir le pourcentage de remplissage
     */
    public function getPourcentageRemplissageAttribute()
    {
        $capaciteMax = 50;
        return $this->nombre_inscrits > 0 ? round(($this->nombre_inscrits / $capaciteMax) * 100, 2) : 0;
    }

    /**
     * Mutateur pour le programme (s'assurer que c'est un array)
     */
    public function setProgrammeAttribute($value)
    {
        $this->attributes['programme'] = is_array($value) ? json_encode($value) : $value;
    }

    /**
     * Mutateur pour le nom (première lettre en majuscule)
     */
    public function setNomAttribute($value)
    {
        $this->attributes['nom'] = ucfirst(trim($value));
    }

    /**
     * Mutateur pour s'assurer que nombre_inscrits n'est jamais négatif
     */
    public function setNombreInscritsAttribute($value)
    {
        $this->attributes['nombre_inscrits'] = max(0, (int) $value);
    }

    /**
     * Méthode pour incrementer le nombre d'inscrits
     */
    public function incrementerInscrits($nombre = 1)
    {
        $this->increment('nombre_inscrits', $nombre);
        return $this;
    }

    /**
     * Méthode pour décrémenter le nombre d'inscrits
     */
    public function decrementerInscrits($nombre = 1)
    {
        $nouveauNombre = max(0, $this->nombre_inscrits - $nombre);
        $this->update(['nombre_inscrits' => $nouveauNombre]);
        return $this;
    }

    /**
     * Méthode pour vérifier si un âge est compatible avec la classe
     */
    public function ageCompatible($age)
    {
        if (is_null($age)) {
            return true;
        }

        $ageMinOk = is_null($this->age_minimum) || $age >= $this->age_minimum;
        $ageMaxOk = is_null($this->age_maximum) || $age <= $this->age_maximum;

        return $ageMinOk && $ageMaxOk;
    }

    /**
     * Méthode pour obtenir les statistiques de la classe
     */
    public function getStatistiques()
    {

        return [
            'nombre_inscrits' => $this->nombre_inscrits,
            'places_disponibles' => $this->places_disponibles,
            'pourcentage_remplissage' => $this->pourcentage_remplissage,
            'est_complete' => $this->est_complete,
            'a_responsable' => !is_null($this->responsable_id),
            'a_enseignant' => !is_null($this->enseignant_principal_id),
        ];
    }
}
