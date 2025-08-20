<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
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
        'lieu_region',
        'lieu_pays',
        'lieu_latitude',
        'lieu_longitude',
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
        'evenement_parent_id',
        'prochaine_occurrence',
        'nombre_vues',
        'nombre_partages',
        'nombre_likes',
        'statistiques_participation',
        'rappel_1_semaine',
        'rappel_1_jour',
        'rappel_1_heure',
        'dernier_rappel_envoye',
        'autorisations_requises',
        'assurance_souscrite',
        'mesures_securite',
        'protocole_sanitaire',
        'previsions_meteo',
        'plan_b_intemperies',
        'contexte_particulier',
        'notes_organisateur',
        'notes_admin',
        'historique_modifications',
        'retour_experience',
        'cree_par',
        'modifie_par',
        'derniere_activite',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'date_ouverture_inscription' => 'date',
        'date_fermeture_inscription' => 'date',
        'nouvelle_date' => 'date',
        'prochaine_occurrence' => 'date',
        'annule_le' => 'datetime',
        'dernier_rappel_envoye' => 'datetime',
        'derniere_activite' => 'datetime',
        'capacite_totale' => 'integer',
        'places_reservees' => 'integer',
        'places_disponibles' => 'integer',
        'nombre_inscrits' => 'integer',
        'nombre_participants' => 'integer',
        'age_minimum' => 'integer',
        'age_maximum' => 'integer',
        'nombre_vues' => 'integer',
        'nombre_partages' => 'integer',
        'nombre_likes' => 'integer',
        'lieu_latitude' => 'decimal:8',
        'lieu_longitude' => 'decimal:8',
        'prix_inscription' => 'decimal:2',
        'budget_prevu' => 'decimal:2',
        'cout_realise' => 'decimal:2',
        'recettes_inscriptions' => 'decimal:2',
        'recettes_sponsors' => 'decimal:2',
        'note_globale' => 'decimal:1',
        'note_organisation' => 'decimal:1',
        'note_contenu' => 'decimal:1',
        'note_lieu' => 'decimal:1',
        'taux_satisfaction' => 'decimal:2',
        'evenement_multi_jours' => 'boolean',
        'liste_attente' => 'boolean',
        'ouvert_public' => 'boolean',
        'necessite_invitation' => 'boolean',
        'inscription_requise' => 'boolean',
        'inscription_payante' => 'boolean',
        'publication_site_web' => 'boolean',
        'publication_reseaux_sociaux' => 'boolean',
        'envoi_newsletter' => 'boolean',
        'diffusion_en_ligne' => 'boolean',
        'enregistrement_autorise' => 'boolean',
        'photos_autorisees' => 'boolean',
        'evenement_recurrent' => 'boolean',
        'rappel_1_semaine' => 'boolean',
        'rappel_1_jour' => 'boolean',
        'rappel_1_heure' => 'boolean',
        'assurance_souscrite' => 'boolean',
        'horaires_detailles' => 'array',
        'equipe_organisation' => 'array',
        'partenaires' => 'array',
        'sponsors' => 'array',
        'programme_detaille' => 'array',
        'intervenants' => 'array',
        'activites_annexes' => 'array',
        'tarifs_categories' => 'array',
        'canaux_communication' => 'array',
        'galerie_images' => 'array',
        'documents_joints' => 'array',
        'detail_budget' => 'array',
        'statistiques_participation' => 'array',
    ];

    /**
     * Boot du modèle pour générer le slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->titre);
            }
        });

        static::updating(function ($event) {
            if ($event->isDirty('titre') && empty($event->slug)) {
                $event->slug = Str::slug($event->titre);
            }
        });
    }

    /**
     * Relation avec l'organisateur principal
     */
    public function organisateurPrincipal()
    {
        return $this->belongsTo(User::class, 'organisateur_principal_id');
    }

    /**
     * Relation avec le coordinateur
     */
    public function coordinateur()
    {
        return $this->belongsTo(User::class, 'coordinateur_id');
    }

    /**
     * Relation avec le responsable logistique
     */
    public function responsableLogistique()
    {
        return $this->belongsTo(User::class, 'responsable_logistique_id');
    }

    /**
     * Relation avec le responsable communication
     */
    public function responsableCommunication()
    {
        return $this->belongsTo(User::class, 'responsable_communication_id');
    }

    /**
     * Relation avec l'utilisateur qui a annulé
     */
    public function annulePar()
    {
        return $this->belongsTo(User::class, 'annule_par');
    }

    /**
     * Relation avec l'événement parent
     */
    public function evenementParent()
    {
        return $this->belongsTo(Event::class, 'evenement_parent_id');
    }

    /**
     * Relation avec les événements enfants
     */
    public function evenementsEnfants()
    {
        return $this->hasMany(Event::class, 'evenement_parent_id');
    }

    /**
     * Utilisateur qui a créé l'événement
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Dernier utilisateur qui a modifié l'événement
     */
    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    /**
     * Scope pour les événements à venir
     */
    public function scopeAVenir($query)
    {
        return $query->where('date_debut', '>=', now()->toDateString());
    }

    /**
     * Scope pour les événements terminés
     */
    public function scopeTermines($query)
    {
        return $query->where('statut', 'termine');
    }

    /**
     * Scope pour les événements publics
     */
    public function scopePublics($query)
    {
        return $query->where('ouvert_public', true)
                     ->where('publication_site_web', true);
    }

    /**
     * Scope pour les événements actifs
     */
    public function scopeActifs($query)
    {
        return $query->whereNotIn('statut', ['annule', 'archive']);
    }

    /**
     * Scope pour filtrer par type
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_evenement', $type);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour les événements avec inscription ouverte
     */
    public function scopeInscriptionOuverte($query)
    {
        return $query->where('inscription_requise', true)
                     ->where('statut', 'ouvert_inscription')
                     ->where(function($q) {
                         $q->whereNull('date_fermeture_inscription')
                           ->orWhere('date_fermeture_inscription', '>', now());
                     });
    }

    /**
     * Scope pour les événements récurrents
     */
    public function scopeRecurrents($query)
    {
        return $query->where('evenement_recurrent', true);
    }

    /**
     * Scope pour les événements avec places disponibles
     */
    public function scopeAvecPlacesDisponibles($query)
    {
        return $query->where(function($q) {
            $q->whereNull('places_disponibles')
              ->orWhereRaw('nombre_inscrits < places_disponibles');
        });
    }

    /**
     * Vérifier si l'événement peut être modifié
     */
    public function canBeModified()
    {
        return in_array($this->statut, ['brouillon', 'planifie', 'en_promotion']);
    }

    /**
     * Vérifier si l'événement peut être annulé
     */
    public function canBeCancelled()
    {
        return !in_array($this->statut, ['termine', 'annule', 'archive']);
    }

    /**
     * Vérifier si les inscriptions sont ouvertes
     */
    public function areInscriptionsOuvertes()
    {
        if (!$this->inscription_requise) {
            return false;
        }

        if ($this->statut !== 'ouvert_inscription') {
            return false;
        }

        if ($this->date_fermeture_inscription && $this->date_fermeture_inscription < now()) {
            return false;
        }

        if ($this->places_disponibles && $this->nombre_inscrits >= $this->places_disponibles) {
            return $this->liste_attente;
        }

        return true;
    }

    /**
     * Commencer l'événement
     */
    public function commencer()
    {
        $this->update(['statut' => 'en_cours']);
    }

    /**
     * Terminer l'événement
     */
    public function terminer()
    {
        $this->update(['statut' => 'termine']);
    }

    /**
     * Annuler l'événement
     */
    public function annuler($motif = null, $userId = null)
    {
        $this->update([
            'statut' => 'annule',
            'motif_annulation' => $motif,
            'annule_par' => $userId,
            'annule_le' => now(),
        ]);
    }

    /**
     * Reporter l'événement
     */
    public function reporter($nouvelleDate, $motif = null)
    {
        $this->update([
            'statut' => 'reporte',
            'nouvelle_date' => $nouvelleDate,
            'motif_annulation' => $motif,
        ]);
    }

    /**
     * Incrémenter les vues
     */
    public function incrementerVues()
    {
        $this->increment('nombre_vues');
    }

    /**
     * Incrémenter les partages
     */
    public function incrementerPartages()
    {
        $this->increment('nombre_partages');
    }

    /**
     * Incrémenter les likes
     */
    public function incrementerLikes()
    {
        $this->increment('nombre_likes');
    }

    /**
     * Accesseur pour la durée de l'événement
     */
    public function getDureeAttribute()
    {
        if ($this->heure_debut && $this->heure_fin) {
            return $this->heure_debut->diffInMinutes($this->heure_fin);
        }

        return null;
    }

    /**
     * Accesseur pour le taux de remplissage
     */
    public function getTauxRemplissageAttribute()
    {
        if ($this->places_disponibles && $this->places_disponibles > 0) {
            return min(100, ($this->nombre_inscrits / $this->places_disponibles) * 100);
        }

        return null;
    }

    /**
     * Accesseur pour le nombre de places restantes
     */
    public function getPlacesRestantesAttribute()
    {
        if ($this->places_disponibles) {
            return max(0, $this->places_disponibles - $this->nombre_inscrits);
        }

        return null;
    }

    /**
     * Accesseur pour le statut des inscriptions
     */
    public function getStatutInscriptionsAttribute()
    {
        if (!$this->inscription_requise) {
            return 'non_requise';
        }

        if ($this->statut !== 'ouvert_inscription') {
            return 'fermees';
        }

        if ($this->date_fermeture_inscription && $this->date_fermeture_inscription < now()) {
            return 'fermees';
        }

        if ($this->places_disponibles && $this->nombre_inscrits >= $this->places_disponibles) {
            return $this->liste_attente ? 'liste_attente' : 'complet';
        }

        return 'ouvertes';
    }

    /**
     * Vérifier si l'événement est complet
     */
    public function isComplet()
    {
        return $this->places_disponibles &&
               $this->nombre_inscrits >= $this->places_disponibles;
    }

    /**
     * Vérifier si l'événement est aujourd'hui
     */
    public function isAujourdhui()
    {
        return $this->date_debut->isToday();
    }

    /**
     * Vérifier si l'événement est en cours
     */
    public function isEnCours()
    {
        if (!$this->isAujourdhui()) {
            return false;
        }

        $maintenant = now()->format('H:i');
        return $maintenant >= $this->heure_debut->format('H:i') &&
               (!$this->heure_fin || $maintenant <= $this->heure_fin->format('H:i'));
    }

    /**
     * Calculer la prochaine occurrence pour les événements récurrents
     */
    public function calculerProchaineOccurrence()
    {
        if (!$this->evenement_recurrent || !$this->frequence_recurrence) {
            return null;
        }

        $prochaine = $this->date_debut->copy();

        switch ($this->frequence_recurrence) {
            case 'hebdomadaire':
                $prochaine->addWeek();
                break;
            case 'mensuelle':
                $prochaine->addMonth();
                break;
            case 'trimestrielle':
                $prochaine->addMonths(3);
                break;
            case 'semestrielle':
                $prochaine->addMonths(6);
                break;
            case 'annuelle':
                $prochaine->addYear();
                break;
        }

        return $prochaine;
    }

    /**
     * Mettre à jour la prochaine occurrence
     */
    public function mettreAJourProchaineOccurrence()
    {
        $prochaine = $this->calculerProchaineOccurrence();
        $this->update(['prochaine_occurrence' => $prochaine]);
    }

    /**
     * Obtenir l'URL de l'événement
     */
    public function getUrlAttribute()
    {
        return route('events.show', $this->slug);
    }

    /**
     * Obtenir les coordonnées GPS
     */
    public function getCoordonneesAttribute()
    {
        if ($this->lieu_latitude && $this->lieu_longitude) {
            return [
                'latitude' => $this->lieu_latitude,
                'longitude' => $this->lieu_longitude,
            ];
        }

        return null;
    }

    /**
     * Obtenir le bilan financier
     */
    public function getBilanFinancierAttribute()
    {
        return [
            'budget_prevu' => $this->budget_prevu,
            'cout_realise' => $this->cout_realise,
            'recettes_totales' => ($this->recettes_inscriptions ?? 0) + ($this->recettes_sponsors ?? 0),
            'benefice' => (($this->recettes_inscriptions ?? 0) + ($this->recettes_sponsors ?? 0)) - ($this->cout_realise ?? 0),
        ];
    }

    /**
     * Créer une nouvelle occurrence pour un événement récurrent
     */
    public function creerNouvelleOccurrence()
    {
        if (!$this->evenement_recurrent) {
            throw new \Exception('Cet événement n\'est pas récurrent');
        }

        $nouvelleDate = $this->calculerProchaineOccurrence();
        if (!$nouvelleDate) {
            return null;
        }

        $nouvelEvent = $this->replicate();
        $nouvelEvent->evenement_parent_id = $this->id;
        $nouvelEvent->date_debut = $nouvelleDate;
        $nouvelEvent->statut = 'planifie';
        $nouvelEvent->nombre_inscrits = 0;
        $nouvelEvent->nombre_participants = null;
        $nouvelEvent->slug = Str::slug($this->titre . ' ' . $nouvelleDate->format('Y-m-d'));
        $nouvelEvent->save();

        return $nouvelEvent;
    }
}
