<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reunion extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'type_reunion_id',
        'titre',
        'description',
        'objectifs',
        'date_reunion',
        'heure_debut_prevue',
        'heure_fin_prevue',
        'heure_debut_reelle',
        'heure_fin_reelle',
        'duree_prevue',
        'duree_reelle',
        'lieu',
        'adresse_complete',
        'salle',
        'capacite_salle',
        'latitude',
        'longitude',
        'organisateur_principal_id',
        'animateur_id',
        'responsable_technique_id',
        'responsable_accueil_id',
        'equipe_organisation',
        'intervenants',
        'nombre_places_disponibles',
        'nombre_inscrits',
        'nombre_participants_reel',
        'nombre_adultes',
        'nombre_enfants',
        'nombre_nouveaux',
        'limite_inscription',
        'liste_attente_activee',
        'ordre_du_jour',
        'message_principal',
        'passage_biblique',
        'documents_annexes',
        'materiel_fourni',
        'materiel_apporter',
        'statut',
        'niveau_priorite',
        'frais_inscription',
        'budget_prevu',
        'cout_reel',
        'detail_couts',
        'recettes_totales',
        'diffusion_en_ligne',
        'lien_diffusion',
        'enregistrement_autorise',
        'lien_enregistrement',
        'photos_reunion',
        'notes_communication',
        'preparation_necessaire',
        'checklist_preparation',
        'preparation_terminee',
        'instructions_participants',
        'note_globale',
        'note_contenu',
        'note_organisation',
        'note_lieu',
        'taux_satisfaction',
        'points_positifs',
        'points_amelioration',
        'feedback_participants',
        'nombre_decisions',
        'nombre_recommitments',
        'nombre_guerisons',
        'temoignages_recueillis',
        'demandes_priere',
        'conditions_meteo',
        'contexte_particulier',
        'defis_rencontres',
        'solutions_apportees',
        'reunion_parent_id',
        'est_recurrente',
        'prochaine_occurrence',
        'reunion_suivante_id',
        'annulee_par',
        'annulee_le',
        'motif_annulation',
        'nouvelle_date',
        'message_participants',
        'rappel_1_jour_envoye',
        'rappel_1_semaine_envoye',
        'dernier_rappel_envoye',
        'nombre_rappels_envoyes',
        'cree_par',
        'modifie_par',
        'validee_par',
        'validee_le',
        'notes_organisateur',
        'notes_admin',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'date_reunion' => 'date',
        'heure_debut_prevue' => 'datetime:H:i',
        'heure_fin_prevue' => 'datetime:H:i',
        'heure_debut_reelle' => 'datetime:H:i',
        'heure_fin_reelle' => 'datetime:H:i',
        'duree_prevue' => 'datetime:H:i',
        'duree_reelle' => 'datetime:H:i',
        'limite_inscription' => 'date',
        'prochaine_occurrence' => 'date',
        'nouvelle_date' => 'date',
        'annulee_le' => 'datetime',
        'validee_le' => 'datetime',
        'dernier_rappel_envoye' => 'datetime',
        'capacite_salle' => 'integer',
        'nombre_places_disponibles' => 'integer',
        'nombre_inscrits' => 'integer',
        'nombre_participants_reel' => 'integer',
        'nombre_adultes' => 'integer',
        'nombre_enfants' => 'integer',
        'nombre_nouveaux' => 'integer',
        'nombre_decisions' => 'integer',
        'nombre_recommitments' => 'integer',
        'nombre_guerisons' => 'integer',
        'nombre_rappels_envoyes' => 'integer',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'frais_inscription' => 'decimal:2',
        'budget_prevu' => 'decimal:2',
        'cout_reel' => 'decimal:2',
        'recettes_totales' => 'decimal:2',
        'note_globale' => 'decimal:1',
        'note_contenu' => 'decimal:1',
        'note_organisation' => 'decimal:1',
        'note_lieu' => 'decimal:1',
        'taux_satisfaction' => 'decimal:2',
        'liste_attente_activee' => 'boolean',
        'diffusion_en_ligne' => 'boolean',
        'enregistrement_autorise' => 'boolean',
        'preparation_terminee' => 'boolean',
        'est_recurrente' => 'boolean',
        'rappel_1_jour_envoye' => 'boolean',
        'rappel_1_semaine_envoye' => 'boolean',
        'equipe_organisation' => 'array',
        'intervenants' => 'array',
        'ordre_du_jour' => 'array',
        'documents_annexes' => 'array',
        'detail_couts' => 'array',
        'photos_reunion' => 'array',
        'checklist_preparation' => 'array',
    ];

    /**
     * Relation avec le type de réunion
     */
    public function typeReunion()
    {
        return $this->belongsTo(TypeReunion::class, 'type_reunion_id');
    }

    /**
     * Relation avec l'organisateur principal
     */
    public function organisateurPrincipal()
    {
        return $this->belongsTo(User::class, 'organisateur_principal_id');
    }

    /**
     * Relation avec l'animateur
     */
    public function animateur()
    {
        return $this->belongsTo(User::class, 'animateur_id');
    }

    /**
     * Relation avec le responsable technique
     */
    public function responsableTechnique()
    {
        return $this->belongsTo(User::class, 'responsable_technique_id');
    }

    /**
     * Relation avec le responsable accueil
     */
    public function responsableAccueil()
    {
        return $this->belongsTo(User::class, 'responsable_accueil_id');
    }

    /**
     * Relation avec la réunion parent
     */
    public function reunionParent()
    {
        return $this->belongsTo(Reunion::class, 'reunion_parent_id');
    }

    /**
     * Relation avec les réunions enfants
     */
    public function reunionsEnfants()
    {
        return $this->hasMany(Reunion::class, 'reunion_parent_id');
    }

    /**
     * Relation avec la réunion suivante
     */
    public function reunionSuivante()
    {
        return $this->belongsTo(Reunion::class, 'reunion_suivante_id');
    }

    /**
     * Relation avec les interventions
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'reunion_id');
    }

    /**
     * Relation avec les rapports
     */
    public function rapports()
    {
        return $this->hasMany(RapportReunion::class, 'reunion_id');
    }

    /**
     * Utilisateur qui a annulé la réunion
     */
    public function annuleePar()
    {
        return $this->belongsTo(User::class, 'annulee_par');
    }

    /**
     * Utilisateur qui a créé la réunion
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Dernier utilisateur qui a modifié la réunion
     */
    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    /**
     * Utilisateur qui a validé la réunion
     */
    public function validateur()
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    /**
     * Scope pour les réunions à venir
     */
    public function scopeAVenir($query)
    {
        return $query->where('date_reunion', '>=', now()->toDateString())
                     ->whereIn('statut', ['planifiee', 'confirmee', 'planifie']);
    }

    /**
     * Scope pour les réunions terminées
     */
    public function scopeTerminees($query)
    {
        return $query->where('statut', 'terminee');
    }

    /**
     * Scope pour les réunions du jour
     */
    public function scopeDuJour($query)
    {
        return $query->whereDate('date_reunion', now()->toDateString());
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour filtrer par type
     */
    public function scopeParType($query, $typeId)
    {
        return $query->where('type_reunion_id', $typeId);
    }

    /**
     * Scope pour filtrer par organisateur
     */
    public function scopeParOrganisateur($query, $organisateurId)
    {
        return $query->where('organisateur_principal_id', $organisateurId);
    }

    /**
     * Scope pour les réunions récurrentes
     */
    public function scopeRecurrentes($query)
    {
        return $query->where('est_recurrente', true);
    }

    /**
     * Vérifier si la réunion peut être modifiée
     */
    public function canBeModified()
    {
        return in_array($this->statut, ['planifiee', 'confirmee', 'planifie']);
    }

    /**
     * Vérifier si la réunion peut être annulée
     */
    public function canBeCancelled()
    {
        return in_array($this->statut, ['planifiee', 'confirmee', 'planifie']);
    }

    /**
     * Commencer la réunion
     */
    public function commencer()
    {
        $this->update([
            'statut' => 'en_cours',
            'heure_debut_reelle' => now()->format('H:i'),
        ]);
    }

    /**
     * Terminer la réunion
     */
    public function terminer()
    {
        $this->update([
            'statut' => 'terminee',
            'heure_fin_reelle' => now()->format('H:i'),
        ]);
    }

    /**
     * Annuler la réunion
     */
    public function annuler($motif = null, $userId = null)
    {
        $this->update([
            'statut' => 'annulee',
            'motif_annulation' => $motif,
            'annulee_par' => $userId,
            'annulee_le' => now(),
        ]);
    }

    /**
     * Reporter la réunion
     */
    public function reporter($nouvelleDate, $motif = null)
    {
        $this->update([
            'statut' => 'reportee',
            'nouvelle_date' => $nouvelleDate,
            'motif_annulation' => $motif,
        ]);
    }

    /**
     * Accesseur pour la durée réelle en minutes
     */
    public function getDureeReelleMinutesAttribute()
    {
        if ($this->heure_debut_reelle && $this->heure_fin_reelle) {
            return $this->heure_debut_reelle->diffInMinutes($this->heure_fin_reelle);
        }

        return null;
    }

    /**
     * Accesseur pour le taux de participation
     */
    public function getTauxParticipationAttribute()
    {
        if ($this->nombre_places_disponibles && $this->nombre_participants_reel) {
            return ($this->nombre_participants_reel / $this->nombre_places_disponibles) * 100;
        }

        return null;
    }

    /**
     * Vérifier si les inscriptions sont ouvertes
     */
    public function areInscriptionsOuvertes()
    {
        if (!$this->typeReunion->necessite_inscription) {
            return false;
        }

        if ($this->limite_inscription && $this->limite_inscription < now()) {
            return false;
        }

        if ($this->nombre_places_disponibles && $this->nombre_inscrits >= $this->nombre_places_disponibles) {
            return $this->liste_attente_activee;
        }

        return true;
    }

    /**
     * Obtenir le statut des inscriptions
     */
    public function getStatutInscriptions()
    {
        if (!$this->typeReunion->necessite_inscription) {
            return 'non_requise';
        }

        if ($this->limite_inscription && $this->limite_inscription < now()) {
            return 'fermees';
        }

        if ($this->nombre_places_disponibles && $this->nombre_inscrits >= $this->nombre_places_disponibles) {
            return $this->liste_attente_activee ? 'liste_attente' : 'complet';
        }

        return 'ouvertes';
    }
}
