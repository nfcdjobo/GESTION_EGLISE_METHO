<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\HasCKEditorFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reunion extends Model
{
    use HasFactory, SoftDeletes, HasCKEditorFields;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'type_reunion_id', 'titre', 'description', 'objectifs',
        'date_reunion', 'heure_debut_prevue', 'heure_fin_prevue',
        'heure_debut_reelle', 'heure_fin_reelle', 'duree_prevue', 'duree_reelle',
        'lieu', 'adresse_complete', 'salle', 'capacite_salle', 'latitude', 'longitude',
        'organisateur_principal_id', 'animateur_id', 'responsable_technique_id', 'responsable_accueil_id',
        'equipe_organisation', 'intervenants',
        'nombre_places_disponibles', 'nombre_inscrits', 'nombre_participants_reel',
        'nombre_adultes', 'nombre_enfants', 'nombre_nouveaux', 'limite_inscription', 'liste_attente_activee',
        'ordre_du_jour', 'message_principal', 'passage_biblique', 'documents_annexes',
        'materiel_fourni', 'materiel_apporter',
        'statut', 'niveau_priorite',
        'frais_inscription', 'budget_prevu', 'cout_reel', 'detail_couts', 'recettes_totales',
        'diffusion_en_ligne', 'lien_diffusion', 'enregistrement_autorise', 'lien_enregistrement',
        'photos_reunion', 'notes_communication',
        'preparation_necessaire', 'checklist_preparation', 'preparation_terminee', 'instructions_participants',
        'note_globale', 'note_contenu', 'note_organisation', 'note_lieu', 'taux_satisfaction',
        'points_positifs', 'points_amelioration', 'feedback_participants',
        'nombre_decisions', 'nombre_recommitments', 'nombre_guerisons', 'temoignages_recueillis', 'demandes_priere',
        'conditions_meteo', 'contexte_particulier', 'defis_rencontres', 'solutions_apportees',
        'reunion_parent_id', 'est_recurrente', 'prochaine_occurrence', 'reunion_suivante_id',
        'annulee_par', 'annulee_le', 'motif_annulation', 'nouvelle_date', 'message_participants',
        'rappel_1_jour_envoye', 'rappel_1_semaine_envoye', 'dernier_rappel_envoye', 'nombre_rappels_envoyes',
        'cree_par', 'modifie_par', 'validee_par', 'validee_le', 'notes_organisateur', 'notes_admin'
    ];

    protected $casts = [
        'date_reunion' => 'date',
        'heure_debut_prevue' => 'datetime',
        'heure_fin_prevue' => 'datetime',
        'heure_debut_reelle' => 'datetime',
        'heure_fin_reelle' => 'datetime',
        'limite_inscription' => 'date',
        'prochaine_occurrence' => 'date',
        'nouvelle_date' => 'date',
        'annulee_le' => 'datetime',
        'dernier_rappel_envoye' => 'datetime',
        'validee_le' => 'datetime',
        'equipe_organisation' => 'array',
        'intervenants' => 'array',
        'ordre_du_jour' => 'array',
        'documents_annexes' => 'array',
        'detail_couts' => 'array',
        'photos_reunion' => 'array',
        'checklist_preparation' => 'array',
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
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    // Relations
    public function typeReunion(): BelongsTo
    {
        return $this->belongsTo(TypeReunion::class, 'type_reunion_id');
    }

    public function organisateurPrincipal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organisateur_principal_id');
    }

    public function animateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'animateur_id');
    }

    public function responsableTechnique(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_technique_id');
    }

    public function responsableAccueil(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_accueil_id');
    }

    public function annuleePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'annulee_par');
    }

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function modificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validee_par');
    }

    public function reunionParent(): BelongsTo
    {
        return $this->belongsTo(Reunion::class, 'reunion_parent_id');
    }

    public function reunionSuivante(): BelongsTo
    {
        return $this->belongsTo(Reunion::class, 'reunion_suivante_id');
    }

    public function reunionsEnfants(): HasMany
    {
        return $this->hasMany(Reunion::class, 'reunion_parent_id');
    }

    public function rapports(): HasMany
    {
        return $this->hasMany(RapportReunion::class, 'reunion_id');
    }

    // Scopes
    public function scopeAVenir($query)
    {
        return $query->where('date_reunion', '>=', now()->toDateString())
                    ->whereIn('statut', ['planifiee', 'confirmee', 'planifiee']);
    }

    public function scopeDuJour($query)
    {
        return $query->where('date_reunion', now()->toDateString())
                    ->whereIn('statut', ['confirmee', 'planifiee', 'en_cours']);
    }

    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    public function scopeParOrganisateur($query, $organisateurId)
    {
        return $query->where('organisateur_principal_id', $organisateurId);
    }

    public function scopeParLieu($query, $lieu)
    {
        return $query->where('lieu', 'ILIKE', "%{$lieu}%");
    }

    public function scopeRecurrentes($query)
    {
        return $query->where('est_recurrente', true);
    }

    public function scopeAvecDiffusionEnLigne($query)
    {
        return $query->where('diffusion_en_ligne', true);
    }

    // Accessors
    public function getDureeReelleEnMinutesAttribute()
    {
        if ($this->heure_debut_reelle && $this->heure_fin_reelle) {
            return $this->heure_debut_reelle->diffInMinutes($this->heure_fin_reelle);
        }
        return null;
    }

    public function getDureePrevueEnMinutesAttribute()
    {
        if ($this->heure_debut_prevue && $this->heure_fin_prevue) {
            return $this->heure_debut_prevue->diffInMinutes($this->heure_fin_prevue);
        }
        return null;
    }

    public function getStatutInscriptionAttribute()
    {
        if ($this->limite_inscription && $this->limite_inscription < now()) {
            return 'Inscriptions fermées';
        }

        if ($this->nombre_places_disponibles && $this->nombre_inscrits >= $this->nombre_places_disponibles) {
            return $this->liste_attente_activee ? 'Liste d\'attente' : 'Complet';
        }

        return 'Inscriptions ouvertes';
    }

    public function getJoursRestantsAttribute()
    {
        return now()->diffInDays($this->date_reunion, false);
    }

    // Méthodes utilitaires
    public function peutEtreAnnulee()
    {
        return in_array($this->statut, ['confirmee', 'planifiee']);
    }

    public function peutEtreReportee()
    {
        return in_array($this->statut, ['planifiee', 'confirmee', 'planifiee']);
    }

    public function peutCommencer()
    {
        return in_array($this->statut, ['confirmee', 'planifiee']);
    }

    public function peutEtreTerminee()
    {
        return $this->statut === 'en_cours';
    }

    public function annuler($motif, $userId = null)
    {
        $this->update([
            'statut' => 'annulee',
            'motif_annulation' => $motif,
            'annulee_par' => $userId,
            'annulee_le' => now()
        ]);
    }

    public function reporter($nouvelleDate, $motif = null, $userId = null)
    {
        $this->update([
            'statut' => 'reportee',
            'nouvelle_date' => $nouvelleDate,
            'motif_annulation' => $motif,
            'annulee_par' => $userId,
            'annulee_le' => now()
        ]);
    }

    public function commencer($userId = null)
    {
        $this->update([
            'statut' => 'en_cours',
            'heure_debut_reelle' => now(),
            'modifie_par' => $userId
        ]);
    }

    public function terminer($userId = null)
    {
        $this->update([
            'statut' => 'terminee',
            'heure_fin_reelle' => now(),
            'modifie_par' => $userId
        ]);

        if ($this->heure_debut_reelle) {
            $this->update([
                'duree_reelle' => $this->heure_debut_reelle->diff(now())->format('%H:%I:%S')
            ]);
        }
    }

    public function marquerPresences($nombreAdultes, $nombreEnfants = 0, $nombreNouveaux = 0)
    {
        $this->update([
            'nombre_adultes' => $nombreAdultes,
            'nombre_enfants' => $nombreEnfants,
            'nombre_nouveaux' => $nombreNouveaux,
            'nombre_participants_reel' => $nombreAdultes + $nombreEnfants
        ]);
    }

    public function ajouterResultatsSpirituel($decisions = 0, $recommitments = 0, $guerisons = 0)
    {
        $this->update([
            'nombre_decisions' => $this->nombre_decisions + $decisions,
            'nombre_recommitments' => $this->nombre_recommitments + $recommitments,
            'nombre_guerisons' => $this->nombre_guerisons + $guerisons
        ]);
    }

    public function necessiteRappel()
    {
        $dateReunion = Carbon::parse($this->date_reunion);
        $maintenant = now();

        if (!$this->rappel_1_semaine_envoye && $dateReunion->diffInDays($maintenant) <= 7) {
            return '1_semaine';
        }

        if (!$this->rappel_1_jour_envoye && $dateReunion->diffInDays($maintenant) <= 1) {
            return '1_jour';
        }

        return null;
    }

    public function marquerRappelEnvoye($type)
    {
        $updates = ['nombre_rappels_envoyes' => $this->nombre_rappels_envoyes + 1];

        if ($type === '1_semaine') {
            $updates['rappel_1_semaine_envoye'] = true;
        } elseif ($type === '1_jour') {
            $updates['rappel_1_jour_envoye'] = true;
        }

        $updates['dernier_rappel_envoye'] = now();

        $this->update($updates);
    }












    // Nouveaux accessors pour CKEditor
    public function getDescriptionFormattedAttribute()
    {
        return $this->getFormattedContent('description');
    }

    public function getResumeMessageFormattedAttribute()
    {
        return $this->getFormattedContent('resume_message');
    }

    public function getPlanMessageFormattedAttribute()
    {
        return $this->getFormattedContent('plan_message');
    }

    public function getNotesFormattedAttribute()
    {
        return [
            'pasteur' => $this->getFormattedContent('notes_pasteur'),
            'organisateur' => $this->getFormattedContent('notes_organisateur')
        ];
    }

    public function getPointsFormattedAttribute()
    {
        return [
            'forts' => $this->getFormattedContent('points_forts'),
            'amelioration' => $this->getFormattedContent('points_amelioration')
        ];
    }

    public function getTemoignagesFormattedAttribute()
    {
        return $this->getFormattedContent('temoignages');
    }

    // Méthodes utilitaires
    public function getMessageWordCount()
    {
        return $this->getWordCount('resume_message') + $this->getWordCount('plan_message');
    }

    public function getMessageReadingTime()
    {
        $totalWords = $this->getWordCount('resume_message') + $this->getWordCount('plan_message');
        return max(1, ceil($totalWords / 200));
    }

    public function hasRichContent()
    {
        foreach ($this->getCKEditorFields() as $field) {
            $content = $this->getAttribute($field);
            if (!empty($content) && $content !== strip_tags($content)) {
                return true;
            }
        }
        return false;
    }

    // Scopes
    public function scopeWithContent($query)
    {
        return $query->where(function ($q) {
            foreach ($this->getCKEditorFields() as $field) {
                $q->orWhereNotNull($field)
                  ->orWhere($field, '!=', '');
            }
        });
    }

    public function scopeSearchContent($query, $search)
    {
        return $this->scopeSearchInCKEditorFields($query, $search);
    }
}
