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
        'responsables', // Structure JSON mise à jour
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
        'responsables' => 'array', // Cast en array pour JSON
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
     * Relation avec tous les responsables de la classe
     */
    public function responsables()
    {
        if (!$this->responsables) {
            return collect();
        }

        $responsableIds = collect($this->responsables)->pluck('id');
        return User::whereIn('id', $responsableIds)->get()->map(function ($user) {
            $responsableData = collect($this->responsables)->firstWhere('id', $user->id);
            $user->responsabilite = $responsableData['responsabilite'] ?? null;
            $user->superieur = $responsableData['superieur'] ?? false;
            return $user;
        });
    }

    /**
     * Obtenir le responsable supérieur
     */
    public function responsableSuperieur()
    {
        if (!$this->responsables) {
            return null;
        }

        $superieur = collect($this->responsables)->firstWhere('superieur', true);
        return $superieur ? User::find($superieur['id']) : null;
    }

    /**
     * Obtenir les responsables par type de responsabilité
     */
    public function responsablesParType($type)
    {
        if (!$this->responsables) {
            return collect();
        }

        $responsables = collect($this->responsables)->where('responsabilite', $type);
        $ids = $responsables->pluck('id');

        return User::whereIn('id', $ids)->get();
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
     * Scope pour les classes actives (ayant un responsable supérieur)
     */


public function scopeActives($query)
{
    return $query->whereNotNull('responsables')
                 ->whereRaw('jsonb_array_length(responsables::jsonb) > 0');
}

    /**
     * Scope pour filtrer par tranche d'âge
     */
    public function scopeParTrancheAge($query, $tranche)
    {
        return $query->where('tranche_age', $tranche);
    }

    /**
     * Scope pour les classes ayant des places disponibles (supprimé car capacité illimitée)
     * Conservé pour compatibilité mais ne fait aucun filtrage
     */
    public function scopeAvecPlacesDisponibles($query, $capaciteMax = null)
    {
        return $query; // Pas de limitation
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
     * Accesseur pour obtenir le nom complet avec tranche d'âge
     */
    public function getNomCompletAttribute()
    {
        return $this->nom . ($this->tranche_age ? ' (' . $this->tranche_age . ')' : '');
    }

    /**
     * Accesseur pour vérifier si la classe est complète (supprimé car capacité illimitée)
     */
    public function getEstCompleteAttribute()
    {
        return false; // Jamais complète car capacité illimitée
    }

    /**
     * Accesseur pour obtenir le pourcentage de remplissage (supprimé car capacité illimitée)
     */
    public function getPourcentageRemplissageAttribute()
    {
        return 0; // Pas pertinent avec capacité illimitée
    }

    /**
     * Accesseur pour les places disponibles (supprimé car capacité illimitée)
     */
    public function getPlacesDisponiblesAttribute()
    {
        return PHP_INT_MAX; // Capacité illimitée
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
     * Mutateur pour les responsables (validation de la structure)
     */
    public function setResponsablesAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        // Validation de la structure
        if (is_array($value)) {
            $validated = [];
            $hasSuperieur = false;

            foreach ($value as $responsable) {
                if (isset($responsable['id']) && isset($responsable['responsabilite'])) {
                    $isSuperieur = $responsable['superieur'] ?? false;

                    // Un seul supérieur autorisé
                    if ($isSuperieur && $hasSuperieur) {
                        $isSuperieur = false;
                    } elseif ($isSuperieur) {
                        $hasSuperieur = true;
                    }

                    $validated[] = [
                        'id' => $responsable['id'],
                        'superieur' => $isSuperieur,
                        'responsabilite' => $responsable['responsabilite']
                    ];
                }
            }

            $this->attributes['responsables'] = json_encode($validated);
        } else {
            $this->attributes['responsables'] = json_encode([]);
        }
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
     * Ajouter un responsable
     */
    public function ajouterResponsable($userId, $responsabilite, $superieur = false)
    {
        $responsables = $this->responsables ?? [];

        // Vérifier si l'utilisateur n'est pas déjà responsable
        $existe = collect($responsables)->firstWhere('id', $userId);
        if ($existe) {
            return false;
        }

        // Si c'est un supérieur, retirer le statut des autres
        if ($superieur) {
            $responsables = collect($responsables)->map(function ($resp) {
                $resp['superieur'] = false;
                return $resp;
            })->toArray();
        }

        $responsables[] = [
            'id' => $userId,
            'superieur' => $superieur,
            'responsabilite' => $responsabilite
        ];

        $this->responsables = $responsables;
        return $this->save();
    }

    /**
     * Retirer un responsable
     */
    public function retirerResponsable($userId)
    {
        $responsables = collect($this->responsables ?? [])
            ->filter(function ($resp) use ($userId) {
                return $resp['id'] !== $userId;
            })
            ->values()
            ->toArray();

        $this->responsables = $responsables;
        return $this->save();
    }

    /**
     * Vérifier si un utilisateur peut gérer les responsables
     */
    public function peutGererResponsables($userId)
    {
        $responsables = $this->responsables ?? [];
        $responsable = collect($responsables)->firstWhere('id', $userId);

        return $responsable && $responsable['superieur'] === true;
    }

    /**
     * Méthode pour obtenir les statistiques de la classe
     */
    public function getStatistiques()
    {
        return [
            'nombre_inscrits' => $this->nombre_inscrits,
            'a_responsables' => !empty($this->responsables),
            'nombre_responsables' => count($this->responsables ?? []),
            'a_superieur' => $this->responsableSuperieur() !== null,
        ];
    }
}
