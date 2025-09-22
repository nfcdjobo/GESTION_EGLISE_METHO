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
        'horaires_cultes',
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
        'horaires_cultes' => 'array',
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
                'nom_eglise' => 'Nom de votre église',
                'telephone_1' => '',
                'email_principal' => 'contact@eglise.com',
                'adresse' => '',
                'ville' => '',
                'pays' => 'France',
                'verset_biblique' => 'Car Dieu a tant aimé le monde qu\'il a donné son Fils unique, afin que quiconque croit en lui ne périsse point, mais qu\'il ait la vie éternelle.',
                'reference_verset' => 'Jean 3:16',
                'devise' => 'EUR',
                'langue' => 'fr',
                'fuseau_horaire' => 'Europe/Paris',
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

        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->images_hero);
    }

    /**
     * Mutator pour s'assurer que les horaires sont au bon format
     */
    public function setHorairesCultesAttribute($value)
    {

        if (is_string($value)) {
            // dd($value);
            $this->attributes['horaires_cultes'] = json_encode($value, true);
        } else {

            $this->attributes['horaires_cultes'] = json_encode($value, true);;
        }


    }

    /**
     * Mutator pour s'assurer que les images hero sont au bon format
     */
    public function setImagesHeroAttribute($value)
    {
dd($value);
        if (is_string($value)) {
            $this->attributes['images_hero'] = json_decode($value, true);
        } else {
            $this->attributes['images_hero'] = $value;
        }

    }

    /**
     * Scope pour récupérer les paramètres actifs
     */
    public function scopeActif($query)
    {
        return $query->where('actif', true);
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
            'horaires' => $this->horaires_cultes,
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
}
