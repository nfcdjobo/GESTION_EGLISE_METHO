<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeReunion extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Le nom de la table
     */
    protected $table = 'type_reunions';

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'nom',
        'code',
        'description',
        'icone',
        'couleur',
        'categorie',
        'niveau_acces',
        'frequence_type',
        'duree_standard',
        'duree_min',
        'duree_max',
        'necessite_preparation',
        'necessite_inscription',
        'a_limite_participants',
        'limite_participants',
        'permet_enfants',
        'age_minimum',
        'equipements_requis',
        'roles_requis',
        'materiel_necessaire',
        'preparation_requise',
        'inclut_louange',
        'inclut_message',
        'inclut_priere',
        'inclut_communion',
        'permet_temoignages',
        'collecte_offrandes',
        'a_frais_participation',
        'frais_standard',
        'details_frais',
        'permet_enregistrement',
        'permet_diffusion_live',
        'necessite_promotion',
        'delai_annonce_jours',
        'modele_ordre_service',
        'instructions_organisateur',
        'modele_invitation',
        'modele_programme',
        'necessite_evaluation',
        'necessite_rapport',
        'criteres_evaluation',
        'questions_feedback',
        'metriques_importantes',
        'compte_conversions',
        'compte_baptemes',
        'compte_nouveaux',
        'afficher_calendrier_public',
        'afficher_site_web',
        'nom_affichage_public',
        'description_publique',
        'actif',
        'est_archive',
        'ordre_affichage',
        'priorite',
        'regles_annulation',
        'politique_remboursement',
        'conditions_participation',
        'code_vestimentaire',
        'responsable_type_id',
        'cree_par',
        'modifie_par',
        'derniere_utilisation',
        'nombre_utilisations',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'duree_standard' => 'datetime:H:i',
        'duree_min' => 'datetime:H:i',
        'duree_max' => 'datetime:H:i',
        'limite_participants' => 'integer',
        'age_minimum' => 'integer',
        'delai_annonce_jours' => 'integer',
        'ordre_affichage' => 'integer',
        'priorite' => 'integer',
        'nombre_utilisations' => 'integer',
        'frais_standard' => 'decimal:2',
        'necessite_preparation' => 'boolean',
        'necessite_inscription' => 'boolean',
        'a_limite_participants' => 'boolean',
        'permet_enfants' => 'boolean',
        'inclut_louange' => 'boolean',
        'inclut_message' => 'boolean',
        'inclut_priere' => 'boolean',
        'inclut_communion' => 'boolean',
        'permet_temoignages' => 'boolean',
        'collecte_offrandes' => 'boolean',
        'a_frais_participation' => 'boolean',
        'permet_enregistrement' => 'boolean',
        'permet_diffusion_live' => 'boolean',
        'necessite_promotion' => 'boolean',
        'necessite_evaluation' => 'boolean',
        'necessite_rapport' => 'boolean',
        'compte_conversions' => 'boolean',
        'compte_baptemes' => 'boolean',
        'compte_nouveaux' => 'boolean',
        'afficher_calendrier_public' => 'boolean',
        'afficher_site_web' => 'boolean',
        'actif' => 'boolean',
        'est_archive' => 'boolean',
        'derniere_utilisation' => 'datetime',
        'equipements_requis' => 'array',
        'roles_requis' => 'array',
        'modele_ordre_service' => 'array',
        'criteres_evaluation' => 'array',
        'metriques_importantes' => 'array',
    ];

    /**
     * Relation avec le responsable du type
     */
    public function responsableType()
    {
        return $this->belongsTo(User::class, 'responsable_type_id');
    }

    /**
     * Relation avec les réunions de ce type
     */
    public function reunions()
    {
        return $this->hasMany(Reunion::class, 'type_reunion_id');
    }

    /**
     * Utilisateur qui a créé le type
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Dernier utilisateur qui a modifié le type
     */
    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    /**
     * Scope pour les types actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('actif', true)->where('est_archive', false);
    }

    /**
     * Scope pour les types archivés
     */
    public function scopeArchives($query)
    {
        return $query->where('est_archive', true);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    /**
     * Scope pour les types publics
     */
    public function scopePublics($query)
    {
        return $query->where('afficher_site_web', true);
    }

    /**
     * Scope pour les types avec inscription requise
     */
    public function scopeAvecInscription($query)
    {
        return $query->where('necessite_inscription', true);
    }

    /**
     * Scope pour ordonner par priorité
     */
    public function scopeParPriorite($query)
    {
        return $query->orderBy('priorite', 'desc')->orderBy('ordre_affichage');
    }

    /**
     * Marquer comme utilisé
     */
    public function marquerUtilise()
    {
        $this->increment('nombre_utilisations');
        $this->update(['derniere_utilisation' => now()]);
    }

    /**
     * Vérifier si le type peut être supprimé
     */
    public function canBeDeleted()
    {
        return $this->reunions()->count() === 0;
    }

    /**
     * Archiver le type
     */
    public function archiver()
    {
        $this->update([
            'est_archive' => true,
            'actif' => false,
        ]);
    }

    /**
     * Restaurer le type
     */
    public function restaurer()
    {
        $this->update([
            'est_archive' => false,
            'actif' => true,
        ]);
    }

    /**
     * Accesseur pour le nom d'affichage
     */
    public function getNomAffichageAttribute()
    {
        return $this->nom_affichage_public ?: $this->nom;
    }

    /**
     * Accesseur pour la description d'affichage
     */
    public function getDescriptionAffichageAttribute()
    {
        return $this->description_publique ?: $this->description;
    }

    /**
     * Obtenir les statistiques d'utilisation
     */
    public function getStatistiquesUtilisation()
    {
        return [
            'nombre_utilisations' => $this->nombre_utilisations,
            'derniere_utilisation' => $this->derniere_utilisation,
            'jours_depuis_utilisation' => $this->derniere_utilisation
                ? $this->derniere_utilisation->diffInDays(now())
                : null,
            'nombre_reunions_prevues' => $this->reunions()
                ->where('date_reunion', '>=', now())
                ->count(),
        ];
    }
}
