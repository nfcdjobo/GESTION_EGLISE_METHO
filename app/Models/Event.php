<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'events';

    protected $fillable = [
        'titre',
        'sous_titre',
        'description',
        'resume_court',
        'slug',
        'type_evenement',
        'categorie',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'evenement_multi_jours',
        'horaires_detailles',
        'fuseau_horaire',
        'lieu_nom',
        'lieu_adresse',
        'lieu_ville',
        'lieu_pays',
        'instructions_acces',
        'transport_organise',
        'capacite_totale',
        'places_reservees',
        'places_disponibles',
        'nombre_inscrits',
        'nombre_participants',
        'liste_attente',
        'audience_cible',
        'age_minimum',
        'age_maximum',
        'ouvert_public',
        'necessite_invitation',
        'inscription_requise',
        'date_ouverture_inscription',
        'date_fermeture_inscription',
        'inscription_payante',
        'prix_inscription',
        'tarifs_categories',
        'conditions_inscription',
        'organisateur_principal_id',
        'coordinateur_id',
        'responsable_logistique_id',
        'responsable_communication_id',
        'equipe_organisation',
        'partenaires',
        'sponsors',
        'programme_detaille',
        'intervenants',
        'objectifs',
        'programme_enfants',
        'activites_annexes',
        'statut',
        'priorite',
        'annule_par',
        'annule_le',
        'motif_annulation',
        'nouvelle_date',
        'message_promotion',
        'hashtag_officiel',
        'canaux_communication',
        'publication_site_web',
        'publication_reseaux_sociaux',
        'envoi_newsletter',
        'image_principale',
        'galerie_images',
        'video_presentation',
        'documents_joints',
        'site_web_evenement',
        'diffusion_en_ligne',
        'lien_diffusion',
        'enregistrement_autorise',
        'lien_enregistrement',
        'photos_autorisees',
        'budget_prevu',
        'cout_realise',
        'recettes_inscriptions',
        'recettes_sponsors',
        'detail_budget',
        'responsable_finances',
        'note_globale',
        'note_organisation',
        'note_contenu',
        'note_lieu',
        'taux_satisfaction',
        'feedback_participants',
        'points_positifs',
        'points_amelioration',
        'evenement_recurrent',
        'frequence_recurrence',
        'prochaine_occurrence',
        'cree_par',
        'modifie_par',
        'derniere_activite',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'evenement_multi_jours' => 'boolean',
        'horaires_detailles' => 'array',
        'places_reservees' => 'integer',
        'capacite_totale' => 'integer',
        'places_disponibles' => 'integer',
        'nombre_inscrits' => 'integer',
        'nombre_participants' => 'integer',
        'liste_attente' => 'boolean',
        'age_minimum' => 'integer',
        'age_maximum' => 'integer',
        'ouvert_public' => 'boolean',
        'necessite_invitation' => 'boolean',
        'inscription_requise' => 'boolean',
        'date_ouverture_inscription' => 'date',
        'date_fermeture_inscription' => 'date',
        'inscription_payante' => 'boolean',
        'prix_inscription' => 'decimal:2',
        'tarifs_categories' => 'array',
        'equipe_organisation' => 'array',
        'partenaires' => 'array',
        'sponsors' => 'array',
        'programme_detaille' => 'array',
        'intervenants' => 'array',
        'activites_annexes' => 'array',
        'annule_le' => 'datetime',
        'nouvelle_date' => 'date',
        'canaux_communication' => 'array',
        'publication_site_web' => 'boolean',
        'publication_reseaux_sociaux' => 'boolean',
        'envoi_newsletter' => 'boolean',
        'galerie_images' => 'array',
        'documents_joints' => 'array',
        'diffusion_en_ligne' => 'boolean',
        'enregistrement_autorise' => 'boolean',
        'photos_autorisees' => 'boolean',
        'budget_prevu' => 'decimal:2',
        'cout_realise' => 'decimal:2',
        'recettes_inscriptions' => 'decimal:2',
        'recettes_sponsors' => 'decimal:2',
        'detail_budget' => 'array',
        'note_globale' => 'decimal:1',
        'note_organisation' => 'decimal:1',
        'note_contenu' => 'decimal:1',
        'note_lieu' => 'decimal:1',
        'taux_satisfaction' => 'decimal:2',
        'evenement_recurrent' => 'boolean',
        'prochaine_occurrence' => 'date',
        'derniere_activite' => 'datetime',
    ];


    protected $attributes = [
        'fuseau_horaire' => 'Africa/Abidjan',
        'lieu_pays' => 'Côte d\'Ivoire',
        'audience_cible' => 'tous',
        'ouvert_public' => true,
        'necessite_invitation' => false,
        'inscription_requise' => false,
        'inscription_payante' => false,
        'places_reservees' => 0,
        'nombre_inscrits' => 0,
        
        'liste_attente' => false,
        'statut' => 'brouillon',
        'priorite' => 'normale',
        'publication_site_web' => true,
        'publication_reseaux_sociaux' => false,
        'envoi_newsletter' => false,
        'diffusion_en_ligne' => false,
        'enregistrement_autorise' => false,
        'photos_autorisees' => true,
        'evenement_recurrent' => false,
    ];

    // Relations
    public function organisateurPrincipal()
    {
        return $this->belongsTo(User::class, 'organisateur_principal_id');
    }

    public function coordinateur()
    {
        return $this->belongsTo(User::class, 'coordinateur_id');
    }

    public function responsableLogistique()
    {
        return $this->belongsTo(User::class, 'responsable_logistique_id');
    }

    public function responsableCommunication()
    {
        return $this->belongsTo(User::class, 'responsable_communication_id');
    }

    public function annulePar()
    {
        return $this->belongsTo(User::class, 'annule_par');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    // Scopes
    public function scopePublics($query)
    {
        return $query->where('ouvert_public', true)
                    ->where('publication_site_web', true);
    }

    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>=', now()->format('Y-m-d'))
                    ->whereNotIn('statut', ['annule', 'archive']);
    }

    public function scopeTermines($query)
    {
        return $query->where('statut', 'termine');
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopePlanifies($query)
    {
        return $query->whereIn('statut', ['brouillon', 'planifie', 'en_promotion', 'ouvert_inscription']);
    }

    public function scopeInscriptionsOuvertes($query)
    {
        return $query->where('inscription_requise', true)
                    ->where('statut', 'ouvert_inscription')
                    ->where(function($q) {
                        $q->whereNull('date_fermeture_inscription')
                          ->orWhere('date_fermeture_inscription', '>', now());
                    });
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_evenement', $type);
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeParLieu($query, $ville)
    {
        return $query->where('lieu_ville', 'ILIKE', "%{$ville}%");
    }

    // Mutateurs
    public function setTitreAttribute($value)
    {
        $this->attributes['titre'] = $value;

        // Générer automatiquement le slug si pas déjà défini
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accesseurs
    public function getJoursRestantsAttribute()
    {
        if (!$this->date_debut) {
            return null;
        }

        return now()->diffInDays($this->date_debut, false);
    }

    public function getStatutInscriptionAttribute()
    {
        if (!$this->inscription_requise) {
            return 'Libre d\'accès';
        }

        if ($this->date_fermeture_inscription && $this->date_fermeture_inscription->isPast()) {
            return 'Inscriptions fermées';
        }

        if ($this->places_disponibles && $this->nombre_inscrits >= $this->places_disponibles) {
            return $this->liste_attente ? 'Liste d\'attente' : 'Complet';
        }

        return 'Inscriptions ouvertes';
    }

    public function getPourcentageRemplissageAttribute()
    {
        if (!$this->places_disponibles || $this->places_disponibles <= 0) {
            return null;
        }

        return round(($this->nombre_inscrits / $this->places_disponibles) * 100, 2);
    }

    public function getDureeAttribute()
    {
        if (!$this->date_fin) {
            return 1; // Événement d'une journée
        }

        return $this->date_debut->diffInDays($this->date_fin) + 1;
    }

    // Méthodes utilitaires
    public function peutEtreModifie(): bool
    {
        return !in_array($this->statut, ['termine', 'archive']);
    }

    public function peutEtreAnnule(): bool
    {
        return !in_array($this->statut, ['termine', 'annule', 'archive']);
    }

    public function estComplet(): bool
    {
        return $this->places_disponibles
               && $this->nombre_inscrits >= $this->places_disponibles;
    }

    public function accepteInscriptions(): bool
    {
        return $this->inscription_requise
               && $this->statut === 'ouvert_inscription'
               && (!$this->date_fermeture_inscription || $this->date_fermeture_inscription->isFuture())
               && (!$this->estComplet() || $this->liste_attente);
    }

    public function estPublic(): bool
    {
        return $this->ouvert_public && $this->publication_site_web;
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            $event->cree_par = auth()->id();
            $event->derniere_activite = now();
        });

        static::updating(function ($event) {
            $event->modifie_par = auth()->id();
            $event->derniere_activite = now();
        });
    }
}
