<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Parametres extends Model
{
    use HasFactory;

    protected $table = 'parametres';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nom_eglise',
        'telephone_1',
        'telephone_2',
        'email_principal',
        'email_secondaire',
        'adresse',
        'ville',
        'commune',
        'pays',
        'code_postal',
        'logo',
        'images_hero',
        'verset_biblique',
        'reference_verset',
        'mission_statement',
        'vision',
        'description_eglise',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'twitter_url',
        'website_url',
        'programmes', // Remplace horaires_cultes
        'date_fondation',
        'nombre_membres',
        'histoire_eglise',
        'devise',
        'langue',
        'fuseau_horaire',
        'actif',
        'singleton',
    ];

    protected $casts = [
        'images_hero' => 'array',
        'programmes' => 'array', // Remplace horaires_cultes
        'actif' => 'boolean',
        'singleton' => 'boolean',
        'date_fondation' => 'date',
        'nombre_membres' => 'integer',
    ];

    protected $hidden = [
        'singleton', // Cacher la colonne technique
    ];

    /**
     * Configuration du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Générer un UUID si pas défini
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }

            // S'assurer que singleton est toujours true
            $model->singleton = true;

            // Vérifier qu'il n'existe pas déjà un enregistrement
            if (static::count() > 0) {
                throw new \Exception('Il ne peut y avoir qu\'un seul enregistrement de paramètres de l\'église.');
            }
        });

        static::updating(function ($model) {
            // S'assurer que singleton reste toujours true
            $model->singleton = true;
        });
    }

    /**
     * Récupérer l'instance unique des paramètres
     * @return Parametres
     */
    public static function getInstance()
    {
        $parametres = static::first();

        if (!$parametres) {
            // Créer l'enregistrement par défaut s'il n'existe pas
            $parametres = static::create([
                'nom_eglise' => 'CANAAN Belle Ville',
                'telephone_1' => '',
                'email_principal' => 'contact@eglise.com',
                'adresse' => 'Abobo belle ville après...',
                'ville' => 'Abidjan',
                'pays' => 'Côte d\'Ivoire',
                'verset_biblique' => 'Car Dieu a tant aimé le monde qu\'il a donné son Fils unique, afin que quiconque croit en lui ne périsse point, mais qu\'il ait la vie éternelle.',
                'reference_verset' => 'Jean 3:16',
                'devise' => 'FCFA',
                'langue' => 'fr',
                'fuseau_horaire' => 'Afrique/Abidjan',
                'actif' => true,
            ]);
        }

        return $parametres;
    }

    /**
     * Empêcher la suppression de l'enregistrement
     */
    public function delete()
    {
        throw new \Exception('Les paramètres de l\'église ne peuvent pas être supprimés. Vous pouvez seulement les modifier.');
    }

    /**
     * Empêcher la suppression forcée
     */
    public function forceDelete()
    {
        throw new \Exception('Les paramètres de l\'église ne peuvent pas être supprimés. Vous pouvez seulement les modifier.');
    }

    /**
     * Accessor pour le logo avec URL complète
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

/**
     * Accessor pour les images hero avec URLs complètes
     */
    public function getImagesHeroUrlsAttribute()
    {
        if (!$this->images_hero || !is_array($this->images_hero)) {
            return [];
        }

        return collect($this->images_hero)->map(function($image) {
            return [
                'id' => $image['id'],
                'titre' => $image['titre'],
                'url' => asset('storage/' . $image['url']),
                'active' => $image['active'],
                'ordre' => $image['ordre'],
            ];
        })->toArray();
    }

    /**
     * Mutator pour s'assurer que les programmes sont au bon format
     */
    public function setProgrammesAttribute($value)
    {
        if (is_string($value)) {
            $this->attributes['programmes'] = $value;
        } else {
            $this->attributes['programmes'] = json_encode($value, JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Mutateur pour valider et formater images_hero
     */
    public function setImagesHeroAttribute($value)
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }

        if (is_array($value)) {
            $formatted = collect($value)->map(function ($image, $index) {
                return [
                    'id' => $image['id'] ?? Str::uuid()->toString(),
                    'titre' => $image['titre'] ?? '',
                    'url' => $image['url'] ?? '',
                    'active' => $image['active'] ?? true,
                    'ordre' => $image['ordre'] ?? $index + 1,
                ];
            })->sortBy('ordre')->values()->toArray();

            $this->attributes['images_hero'] = json_encode($formatted, JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['images_hero'] = null;
        }
    }


        /**
     * Accesseur pour récupérer images_hero formaté
     */
    public function getImagesHeroAttribute($value)
    {
        if (empty($value)) {
            return [];
        }

        $images = json_decode($value, true);
        return collect($images)->sortBy('ordre')->values()->toArray();
    }


     /**
     * Récupérer uniquement les images actives
     */
    public function getImagesHeroActivesAttribute()
    {
        return collect($this->images_hero)
            ->where('active', true)
            ->sortBy('ordre')
            ->values()
            ->toArray();
    }

    /**
     * Scope pour récupérer les paramètres actifs
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }


        /**
     * Récupérer toutes les images hero
     */
    public function getImagesHero()
    {
        return $this->images_hero ?? [];
    }

    /**
     * Récupérer une image hero par son ID
     */
    public function getImageHeroById($id)
    {
        $images = $this->getImagesHero();
        return collect($images)->firstWhere('id', $id);
    }

    /**
     * Ajouter une nouvelle image hero
     */
    public function ajouterImageHero(array $imageData)
    {
        $images = $this->getImagesHero();

        // Générer un UUID pour la nouvelle image
        $imageData['id'] = Str::uuid()->toString();

        // Définir l'ordre si non spécifié
        if (!isset($imageData['ordre'])) {
            $maxOrdre = collect($images)->max('ordre') ?? 0;
            $imageData['ordre'] = $maxOrdre + 1;
        }

        // Valeurs par défaut
        $imageData = array_merge([
            'active' => true,
        ], $imageData);

        $images[] = $imageData;

        $this->images_hero = $images;
        $this->save();

        return $imageData['id'];
    }

    /**
     * Mettre à jour une image hero existante
     */
    public function mettreAJourImageHero($id, array $updateData)
    {
        $images = $this->getImagesHero();

        $images = collect($images)->map(function ($image) use ($id, $updateData) {
            if ($image['id'] === $id) {
                return array_merge($image, $updateData);
            }
            return $image;
        })->toArray();

        $this->images_hero = $images;
        $this->save();

        return $this->getImageHeroById($id);
    }

    /**
     * Supprimer une image hero
     */
    public function supprimerImageHero($id)
    {
        $images = $this->getImagesHero();

        $images = collect($images)->reject(function ($image) use ($id) {
            return $image['id'] === $id;
        })->values()->toArray();

        $this->images_hero = $images;
        $this->save();

        return true;
    }

    /**
     * Réorganiser l'ordre des images hero
     */
    public function reordonnerImagesHero(array $ordreIds)
    {
        $images = $this->getImagesHero();
        $imagesCollection = collect($images);

        $imagesReordonnees = [];

        foreach ($ordreIds as $index => $id) {
            $image = $imagesCollection->firstWhere('id', $id);
            if ($image) {
                $image['ordre'] = $index + 1;
                $imagesReordonnees[] = $image;
            }
        }

        $this->images_hero = $imagesReordonnees;
        $this->save();

        return $this->getImagesHero();
    }



    /**
     * Méthode pour mettre à jour les paramètres de manière sécurisée
     */
    public static function updateParametres(array $data)
    {
        $parametres = static::getInstance();

        // Filtrer les données pour ne garder que les champs autorisés
        $allowedFields = array_intersect_key($data, array_flip($parametres->getFillable()));

        // Exclure la modification du singleton
        unset($allowedFields['singleton']);

        return $parametres->update($allowedFields);
    }

    /**
     * Récupérer tous les programmes
     */
    public function getProgrammes()
    {
        return $this->programmes ?? [];
    }

    /**
     * Récupérer les programmes publics et actifs
     */
    public function getProgrammesPublics()
    {
        $programmes = $this->getProgrammes();

        return collect($programmes)
            ->filter(function ($programme) {
                return ($programme['est_public'] ?? false) && ($programme['est_actif'] ?? false);
            })
            ->sortBy('ordre')
            ->values()
            ->all();
    }

    /**
     * Récupérer un programme par son UUID
     */
    public function getProgrammeById($id)
    {
        $programmes = $this->getProgrammes();

        return collect($programmes)->firstWhere('id', $id);
    }

    /**
     * Ajouter un nouveau programme
     */
    public function ajouterProgramme(array $programmeData)
    {
        $programmes = $this->getProgrammes();

        // Générer un UUID pour le nouveau programme
        $programmeData['id'] = Str::uuid()->toString();

        // Définir l'ordre si non spécifié
        if (!isset($programmeData['ordre'])) {
            $maxOrdre = collect($programmes)->max('ordre') ?? 0;
            $programmeData['ordre'] = $maxOrdre + 1;
        }

        // Valeurs par défaut
        $programmeData = array_merge([
            'est_public' => true,
            'est_actif' => true,
            'type_horaire' => 'regulier',
        ], $programmeData);

        $programmes[] = $programmeData;

        $this->programmes = $programmes;
        $this->save();

        return $programmeData['id'];
    }

    /**
     * Mettre à jour un programme existant
     */
    public function mettreAJourProgramme($id, array $updateData)
    {
        $programmes = $this->getProgrammes();

        $programmes = collect($programmes)->map(function ($programme) use ($id, $updateData) {
            if ($programme['id'] === $id) {
                return array_merge($programme, $updateData);
            }
            return $programme;
        })->all();

        $this->programmes = $programmes;
        $this->save();

        return $this->getProgrammeById($id);
    }

    /**
     * Supprimer un programme
     */
    public function supprimerProgramme($id)
    {
        $programmes = $this->getProgrammes();

        $programmes = collect($programmes)->reject(function ($programme) use ($id) {
            return $programme['id'] === $id;
        })->values()->all();

        $this->programmes = $programmes;
        $this->save();

        return true;
    }

    /**
     * Réorganiser l'ordre des programmes
     */
    public function reordonnerProgrammes(array $ordreIds)
    {
        $programmes = $this->getProgrammes();
        $programmesCollection = collect($programmes);

        $programmesReordonnes = [];

        foreach ($ordreIds as $index => $id) {
            $programme = $programmesCollection->firstWhere('id', $id);
            if ($programme) {
                $programme['ordre'] = $index + 1;
                $programmesReordonnes[] = $programme;
            }
        }

        $this->programmes = $programmesReordonnes;
        $this->save();

        return $this->getProgrammes();
    }

    /**
     * Méthode pour obtenir les informations complètes de l'église
     */
    public function getInfosCompletes()
    {
        return [
            'identite' => [
                'nom' => $this->nom_eglise,
                'date_fondation' => $this->date_fondation,
                'nombre_membres' => $this->nombre_membres,
                'description' => $this->description_eglise,
                'histoire' => $this->histoire_eglise,
            ],
            'contact' => [
                'telephone_1' => $this->telephone_1,
                'telephone_2' => $this->telephone_2,
                'email_principal' => $this->email_principal,
                'email_secondaire' => $this->email_secondaire,
                'adresse_complete' => $this->getAdresseComplete(),
            ],
            'spirituel' => [
                'verset' => $this->verset_biblique,
                'reference' => $this->reference_verset,
                'mission' => $this->mission_statement,
                'vision' => $this->vision,
            ],
            'reseaux_sociaux' => [
                'facebook' => $this->facebook_url,
                'instagram' => $this->instagram_url,
                'youtube' => $this->youtube_url,
                'twitter' => $this->twitter_url,
                'website' => $this->website_url,
            ],
            'medias' => [
                'logo_url' => $this->logo_url,
                'images_hero_urls' => $this->images_hero_urls,
            ],
            'programmes' => $this->getProgrammesPublics(), // Remplace horaires
            'parametres' => [
                'devise' => $this->devise,
                'langue' => $this->langue,
                'fuseau_horaire' => $this->fuseau_horaire,
            ]
        ];
    }

    /**
     * Méthode pour obtenir l'adresse complète formatée
     */
    public function getAdresseComplete()
    {
        $adresse = $this->adresse;

        if ($this->commune) {
            $adresse .= ', ' . $this->commune;
        }

        $adresse .= ', ' . $this->ville;

        if ($this->code_postal) {
            $adresse .= ' ' . $this->code_postal;
        }

        $adresse .= ', ' . $this->pays;

        return $adresse;
    }











    /**
 * Récupérer les programmes publics avec pagination
 */
public function getProgrammesPublicsPaginated($perPage = 6, $currentPage = 1)
{
    $programmes = $this->getProgrammesPublics();

    if (empty($programmes)) {
        return [
            'data' => [],
            'current_page' => 1,
            'per_page' => $perPage,
            'total' => 0,
            'total_pages' => 0,
            'from' => 0,
            'to' => 0
        ];
    }

    $total = count($programmes);
    $totalPages = ceil($total / $perPage);
    $offset = ($currentPage - 1) * $perPage;
    $data = array_slice($programmes, $offset, $perPage);

    return [
        'data' => $data,
        'current_page' => $currentPage,
        'per_page' => $perPage,
        'total' => $total,
        'total_pages' => $totalPages,
        'from' => $offset + 1,
        'to' => min($offset + $perPage, $total)
    ];
}

/**
 * Récupérer les programmes publics actifs seulement
 */
public function getProgrammesPublicsActifs()
{
    $programmes = $this->getProgrammes();

    if (!$programmes) {
        return [];
    }

    return array_filter($programmes, function($programme) {
        return ($programme['est_public'] ?? false) && ($programme['est_actif'] ?? false);
    });
}

/**
 * Compter les programmes publics
 */
public function countProgrammesPublics()
{
    return count($this->getProgrammesPublics());
}

// /**
//  * Récupérer un programme par son ID
//  */
// public function getProgrammeById($id)
// {
//     $programmes = $this->getProgrammes();

//     if (!$programmes) {
//         return null;
//     }

//     foreach ($programmes as $programme) {
//         if (($programme['id'] ?? null) == $id) {
//             return $programme;
//         }
//     }

//     return null;
// }
}
