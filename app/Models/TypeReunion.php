<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class TypeReunion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'type_reunions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nom', 'code', 'description', 'icone', 'couleur',
        'categorie', 'niveau_acces', 'frequence_type',
        'duree_standard', 'duree_min', 'duree_max',
        'necessite_preparation', 'necessite_inscription', 'a_limite_participants',
        'limite_participants', 'permet_enfants', 'age_minimum',
        'equipements_requis', 'roles_requis', 'materiel_necessaire', 'preparation_requise',
        'inclut_louange', 'inclut_message', 'inclut_priere', 'inclut_communion', 'permet_temoignages',
        'collecte_offrandes', 'a_frais_participation', 'frais_standard', 'details_frais',
        'permet_enregistrement', 'permet_diffusion_live', 'necessite_promotion', 'delai_annonce_jours',
        'modele_ordre_service', 'instructions_organisateur', 'modele_invitation', 'modele_programme',
        'necessite_evaluation', 'necessite_rapport', 'criteres_evaluation', 'questions_feedback',
        'metriques_importantes', 'compte_conversions', 'compte_baptemes', 'compte_nouveaux',
        'afficher_calendrier_public', 'afficher_site_web', 'nom_affichage_public', 'description_publique',
        'actif', 'est_archive', 'ordre_affichage', 'priorite',
        'regles_annulation', 'politique_remboursement', 'conditions_participation', 'code_vestimentaire',
        'responsable_type_id', 'cree_par', 'modifie_par', 'derniere_utilisation', 'nombre_utilisations'
    ];

    protected $casts = [
        'equipements_requis' => 'array',
        'roles_requis' => 'array',
        'modele_ordre_service' => 'array',
        'criteres_evaluation' => 'array',
        'metriques_importantes' => 'array',
        'duree_standard' => 'datetime',
        'duree_min' => 'datetime',
        'duree_max' => 'datetime',
        'derniere_utilisation' => 'datetime',
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
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
            if (empty($model->code)) {
                $model->code = Str::slug($model->nom);
            }
        });
    }

    // Relations
    public function responsableType(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_type_id');
    }

    public function createurType(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    public function reunions(): HasMany
    {
        return $this->hasMany(Reunion::class, 'type_reunion_id');
    }

    // Scopes
    public function scopeActif($query)
    {
        return $query->where('actif', true)->where('est_archive', false);
    }

    public function scopePublic($query)
    {
        return $query->where('afficher_site_web', true)->actif();
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeParNiveauAcces($query, $niveau)
    {
        return $query->where('niveau_acces', $niveau);
    }

    // Accessors
    public function getNomAffichageAttribute()
    {
        return $this->nom_affichage_public ?: $this->nom;
    }

    public function getDescriptionAffichageAttribute()
    {
        return $this->description_publique ?: $this->description;
    }

    // Mutators
    public function setCodeAttribute($value)
    {
        $this->attributes['code'] = Str::slug($value);
    }

    // Méthodes utilitaires
    public function marquerUtilise()
    {
        $this->update([
            'derniere_utilisation' => now(),
            'nombre_utilisations' => $this->nombre_utilisations + 1
        ]);
    }

    public function peutEtreUtilisePar($user)
    {
        return match($this->niveau_acces) {
            'public' => true,
            'membres' => $user->isMembre(),
            'leadership' => $user->isLeadership(),
            'invite' => $user->hasInvitation(),
            'prive' => $user->isAdmin(),
            default => false
        };
    }

    public function getStatutUtilisation()
    {
        if (!$this->derniere_utilisation) {
            return 'Jamais utilisé';
        }

        $jours = now()->diffInDays($this->derniere_utilisation);

        return match(true) {
            $jours <= 30 => 'Récent',
            $jours <= 90 => 'Modéré',
            default => 'Ancien'
        };
    }
}
