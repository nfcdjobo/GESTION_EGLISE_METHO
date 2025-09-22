<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Traits\HasCKEditorFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Projet extends Model
{
    use HasFactory, HasUuids, SoftDeletes, HasCKEditorFields;

    protected $table = 'projets';

    protected $fillable = [
        'nom_projet',
        'code_projet',
        'description',
        'objectif',
        'contexte',
        'type_projet',
        'categorie',
        'budget_prevu',
        // 'budget_collecte', // RETIRÉ - sera calculé automatiquement
        'budget_depense',
        'budget_minimum',
        'devise',
        'detail_budget',
        'sources_financement',
        'date_creation',
        'date_debut',
        'date_fin_prevue',
        'date_fin_reelle',
        'duree_prevue_jours',
        'duree_reelle_jours',
        'responsable_id',
        'coordinateur_id',
        'chef_projet_id',
        'equipe_projet',
        'partenaires',
        'beneficiaires',
        'localisation',
        'adresse_complete',
        'ville',
        'region',
        'pays',
        'latitude',
        'longitude',
        'statut',
        'statut_precedent', // AJOUTÉ pour l'historique
        'priorite',
        'pourcentage_completion',
        'derniere_activite',
        'derniere_mise_a_jour',
        'approuve_par',
        'approuve_le',
        'commentaires_approbation',
        'necessite_approbation',
        'objectifs_mesurables',
        'indicateurs_succes',
        'risques_identifies',
        'mesures_mitigation',
        'documents_joints',
        'photos_projet',
        'site_web',
        'liens_utiles',
        'manuel_procedure',
        'visible_public',
        'ouvert_aux_dons',
        'message_promotion',
        'image_principale',
        'canaux_communication',
        'resultats_obtenus',
        'impact_communaute',
        'lecons_apprises',
        'recommandations',
        'note_satisfaction',
        'feedback_beneficiaires',
        'necessite_suivi',
        'prochaine_evaluation',
        'plan_suivi',
        'projet_lie',
        'conforme_reglementation',
        'autorisations_requises',
        'audit_requis',
        'observations_audit',
        'projet_recurrent',
        'frequence_recurrence',
        'projet_parent_id',
        'metadonnees',
        'reference_externe',
        'integration_systemes',
        'notes_responsable',
        'notes_admin',
        'historique_modifications',
        'cree_par',
        'modifie_par',
        'derniere_activite_date',
        'derniere_activite_par',
    ];

    protected $casts = [
        'budget_prevu' => 'decimal:2',
        'budget_depense' => 'decimal:2',
        'budget_minimum' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'pourcentage_completion' => 'decimal:2',
        'note_satisfaction' => 'decimal:1',
        'date_creation' => 'date',
        'date_debut' => 'date',
        'date_fin_prevue' => 'date',
        'date_fin_reelle' => 'date',
        'derniere_mise_a_jour' => 'date',
        'prochaine_evaluation' => 'date',
        'approuve_le' => 'datetime',
        'derniere_activite_date' => 'datetime',
        'duree_prevue_jours' => 'integer',
        'duree_reelle_jours' => 'integer',
        'necessite_approbation' => 'boolean',
        'visible_public' => 'boolean',
        'ouvert_aux_dons' => 'boolean',
        'necessite_suivi' => 'boolean',
        'conforme_reglementation' => 'boolean',
        'audit_requis' => 'boolean',
        'projet_recurrent' => 'boolean',
        'detail_budget' => 'array',
        'sources_financement' => 'array',
        'equipe_projet' => 'array',
        'partenaires' => 'array',
        'beneficiaires' => 'array',
        'objectifs_mesurables' => 'array',
        'indicateurs_succes' => 'array',
        'risques_identifies' => 'array',
        'mesures_mitigation' => 'array',
        'documents_joints' => 'array',
        'photos_projet' => 'array',
        'liens_utiles' => 'array',
        'canaux_communication' => 'array',
        'projet_lie' => 'array',
        'metadonnees' => 'array',
    ];

    protected $attributes = [
        'devise' => 'XOF',
        'pays' => 'CI',
        'statut' => 'conception',
        'priorite' => 'normale',
        'pourcentage_completion' => 0,
        'budget_depense' => 0,
        'necessite_approbation' => true,
        'visible_public' => false,
        'ouvert_aux_dons' => true,
        'necessite_suivi' => false,
        'conforme_reglementation' => true,
        'audit_requis' => false,
        'projet_recurrent' => false,
    ];

    // Relations
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }

    public function coordinateur()
    {
        return $this->belongsTo(User::class, 'coordinateur_id');
    }

    public function chefProjet()
    {
        return $this->belongsTo(User::class, 'chef_projet_id');
    }

    public function approbateur()
    {
        return $this->belongsTo(User::class, 'approuve_par');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    public function derniereActivitePar()
    {
        return $this->belongsTo(User::class, 'derniere_activite_par');
    }

    public function projetParent()
    {
        return $this->belongsTo(Projet::class, 'projet_parent_id');
    }

    public function projetsEnfants()
    {
        return $this->hasMany(Projet::class, 'projet_parent_id');
    }

    public function fonds()
    {
        return $this->hasMany(Fonds::class, 'projet_id');
    }

    // Scopes
    public function scopeActifs($query)
    {
        return $query->whereIn('statut', ['en_cours', 'recherche_financement']);
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    public function scopeTermines($query)
    {
        return $query->where('statut', 'termine');
    }

    public function scopeEnPlanification($query)
    {
        return $query->where('statut', 'planification');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAnnules($query)
    {
        return $query->where('statut', 'annule');
    }

    public function scopeSuspendus($query)
    {
        return $query->where('statut', 'suspendu');
    }

    public function scopeEnRechercheFinancement($query)
    {
        return $query->where('statut', 'recherche_financement');
    }

    public function scopeEnAttenteDisponibles($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_projet', $type);
    }

    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    public function scopeParPriorite($query, $priorite)
    {
        return $query->where('priorite', $priorite);
    }

    public function scopeParResponsable($query, $responsableId)
    {
        return $query->where('responsable_id', $responsableId);
    }

    public function scopeParVille($query, $ville)
    {
        return $query->where('ville', $ville);
    }

    public function scopeParRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeVisiblesPublic($query)
    {
        return $query->where('visible_public', true);
    }

    public function scopeOuvertsAuxDons($query)
    {
        return $query->where('ouvert_aux_dons', true);
    }

    public function scopeRecurrents($query)
    {
        return $query->where('projet_recurrent', true);
    }

    public function scopeApprouves($query)
    {
        return $query->whereNotNull('approuve_par');
    }

    public function scopeEnAttenteApprobation($query)
    {
        return $query->where('necessite_approbation', true)
            ->whereNull('approuve_par');
    }

    public function scopeEnRetard($query)
    {
        return $query->where('date_fin_prevue', '<', now())
            ->whereNotIn('statut', ['termine', 'annule', 'archive']);
    }

    public function scopeNecessitantSuivi($query)
    {
        return $query->where('necessite_suivi', true);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_debut', [$dateDebut, $dateFin]);
    }

    // Accesseurs CORRIGÉS
    public function getNomCompletAttribute()
    {
        return $this->nom_projet . ' (' . $this->code_projet . ')';
    }

    // CORRIGÉ : Budget collecté calculé automatiquement
    public function getBudgetCollecteAttribute()
    {
        return $this->fonds()->validees()->sum('montant') ?? 0;
    }

    public function getPourcentageFinancementAttribute()
    {
        if (!$this->budget_prevu || $this->budget_prevu == 0) {
            return 0;
        }
        return round(($this->budget_collecte / $this->budget_prevu) * 100, 2);
    }

    public function getStatutLibelleAttribute()
    {
        return match ($this->statut) {
            'conception' => 'En conception',
            'planification' => 'En planification',
            'recherche_financement' => 'Recherche de financement',
            'en_attente' => 'En attente',
            'en_cours' => 'En cours',
            'suspendu' => 'Suspendu',
            'termine' => 'Terminé',
            'annule' => 'Annulé',
            'archive' => 'Archivé',
            default => ucfirst($this->statut)
        };
    }

    public function getTypeProjetLibelleAttribute()
    {
        return match ($this->type_projet) {
            'construction' => 'Construction',
            'renovation' => 'Rénovation',
            'social' => 'Social',
            'evangelisation' => 'Évangélisation',
            'formation' => 'Formation',
            'mission' => 'Mission',
            'equipement' => 'Équipement',
            'technologie' => 'Technologie',
            'communautaire' => 'Communautaire',
            'humanitaire' => 'Humanitaire',
            'education' => 'Éducation',
            'sante' => 'Santé',
            default => ucfirst($this->type_projet)
        };
    }

    public function getPrioriteLibelleAttribute()
    {
        return match ($this->priorite) {
            'faible' => 'Faible',
            'normale' => 'Normale',
            'haute' => 'Haute',
            'urgente' => 'Urgente',
            'critique' => 'Critique',
            default => ucfirst($this->priorite)
        };
    }

    public function getBudgetFormatAttribute()
    {
        if (!$this->budget_prevu) {
            return 'Non défini';
        }
        return number_format($this->budget_prevu, 0, ',', ' ') . ' ' . $this->devise;
    }

    public function getMontantRestantAttribute()
    {
        if (!$this->budget_prevu) {
            return 0;
        }
        return max(0, $this->budget_prevu - $this->budget_collecte);
    }

    public function getJoursRestantsAttribute()
    {
        if (!$this->date_fin_prevue) {
            return null;
        }
        return $this->date_fin_prevue->diffInDays(now(), false);
    }

    public function getDureeReelleJoursAttribute()
    {
        if ($this->date_debut && $this->date_fin_reelle) {
            return $this->date_debut->diffInDays($this->date_fin_reelle);
        }
        return $this->attributes['duree_reelle_jours'] ?? null;
    }

    public function getEstEnRetardAttribute()
    {
        return $this->date_fin_prevue
            && $this->date_fin_prevue->isPast()
            && !in_array($this->statut, ['termine', 'annule', 'archive']);
    }

    public function getEstApprouveAttribute()
    {
        return !is_null($this->approuve_par);
    }

    public function getEstFinanceAttribute()
    {
        $seuil = $this->getSeuilFinancement();
        return $seuil > 0 && $this->budget_collecte >= $seuil;
    }

    public function getEquipeProjetNomsAttribute()
    {
        if (!$this->equipe_projet) {
            return [];
        }

        $userIds = collect($this->equipe_projet)->pluck('user_id')->filter();
        if ($userIds->isEmpty()) {
            return [];
        }

        return User::whereIn('id', $userIds)->get()->pluck('nom_complet')->toArray();
    }

    // Mutateurs CORRIGÉS (budget_collecte retiré)
    public function setCodeProjetAttribute($value)
    {
        $this->attributes['code_projet'] = strtoupper($value);
    }

    public function setBudgetPrevuAttribute($value)
    {
        $this->attributes['budget_prevu'] = $value ? abs(floatval($value)) : null;
    }

    public function setBudgetDepenseAttribute($value)
    {
        $this->attributes['budget_depense'] = abs(floatval($value));
    }

    public function setPourcentageCompletionAttribute($value)
    {
        $this->attributes['pourcentage_completion'] = max(0, min(100, floatval($value)));
    }

    // MÉTHODES DE WORKFLOW CORRIGÉES

    /**
     * Retourne le seuil de financement requis (budget minimum ou budget prévu)
     */
    private function getSeuilFinancement(): float
    {
        return $this->budget_minimum ?? $this->budget_prevu ?? 0;
    }

    /**
     * Vérifie si le projet peut être mis en recherche de financement
     */
    public function peutEtreEnRechercheFinancement(): bool
    {
        if ($this->statut !== 'planification' || !$this->est_approuve || !$this->budget_prevu) {
            return false;
        }

        $seuilRequis = $this->getSeuilFinancement();
        return $this->budget_collecte < $seuilRequis;
    }

    /**
     * Vérifie si le projet peut passer en attente (prêt à démarrer)
     */
    // public function peutEtreEnAttente(): bool
    // {
    //     // Condition commune : doit être approuvé
    //     if (!$this->est_approuve) {
    //         return false;
    //     }

    //     // Depuis planification : pas de budget OU financement suffisant
    //     if ($this->statut === 'planification') {
    //         // Si pas de budget défini, peut passer en attente
    //         if (!$this->budget_prevu) {
    //             return true;
    //         }

    //         $seuilRequis = $this->getSeuilFinancement();
    //         return $this->budget_collecte >= $seuilRequis;
    //     }

    //     // Depuis recherche_financement : financement atteint
    //     if ($this->statut === 'recherche_financement') {
    //         $seuilRequis = $this->getSeuilFinancement();
    //         return $this->budget_collecte >= $seuilRequis;
    //     }

    //     return false;
    // }

    public function peutEtreEnAttente(): bool
    {
        // Condition commune : doit être approuvé
        if (!$this->est_approuve) {
            return false;
        }

        // Depuis planification : pas de budget OU financement suffisant
        if ($this->statut === 'planification') {
            // Si pas de budget défini, peut passer en attente
            if (!$this->budget_prevu || $this->budget_prevu == 0) {
                return true;
            }

            $seuilRequis = $this->getSeuilFinancement();
            return $this->budget_collecte >= $seuilRequis;
        }

        // CORRECTION : Depuis recherche_financement
        if ($this->statut === 'recherche_financement') {
            // TOUJOURS permettre la transition, même si financement pas complet
            // (avec validation dans le contrôleur)
            return true;
        }

        return false;
    }


        /**
     * NOUVELLE MÉTHODE : Forcer la mise en attente avec validation
     */
    public function forcerMiseEnAttente(string $justification = null): bool
    {
        if ($this->statut !== 'recherche_financement' || !$this->est_approuve) {
            return false;
        }

        return $this->update([
            'statut' => 'en_attente',
            'statut_precedent' => $this->statut,
            'derniere_mise_a_jour' => now()->toDateString(),
            'derniere_activite' => $justification ?? 'Projet forcé en attente malgré financement incomplet',
            'derniere_activite_date' => now(),
            'derniere_activite_par' => auth()->id(),
            'notes_admin' => ($this->notes_admin ?? '') . "\n[" . now() . "] Forcé en attente: " . $justification
        ]);
    }


    /**
     * CORRECTION : Amélioration de la méthode de validation du workflow
     */
    public function getStatutFinancement(): array
    {
        $seuilRequis = $this->getSeuilFinancement();
        $collecte = $this->budget_collecte;

        return [
            'seuil_requis' => $seuilRequis,
            'montant_collecte' => $collecte,
            'pourcentage' => $seuilRequis > 0 ? round(($collecte / $seuilRequis) * 100, 2) : 0,
            'est_finance' => $collecte >= $seuilRequis,
            'montant_manquant' => max(0, $seuilRequis - $collecte)
        ];
    }


    /**
     * Met le projet en recherche de financement
     */
    public function mettreEnRechercheFinancement(): bool
    {
        if (!$this->peutEtreEnRechercheFinancement()) {
            return false;
        }

        return $this->changerStatut('recherche_financement', 'Projet mis en recherche de financement');
    }

    /**
     * Met le projet en attente (prêt à démarrer)
     */
    public function mettreEnAttente(): bool
    {
        if (!$this->peutEtreEnAttente()) {
            return false;
        }

        return $this->changerStatut('en_attente', 'Projet prêt à démarrer');
    }

    /**
     * Vérifie si le projet peut être démarré (en_cours)
     */
    public function peutEtreDemarre(): bool
    {
        return $this->statut === 'en_attente' && $this->est_approuve;
    }

    /**
     * Vérifie si le projet peut être approuvé
     */
    public function peutEtreApprouve(): bool
    {
        return $this->statut === 'conception'
            && $this->necessite_approbation
            && !$this->est_approuve;
    }

    /**
     * Vérifie si le projet peut passer en planification
     */
    public function peutEtrePlanifie(): bool
    {
        return $this->statut === 'conception' && $this->est_approuve;
    }

    public function peutEtreSuspendu(): bool
    {
        return $this->statut === 'en_cours';
    }

    public function peutEtreRepris(): bool
    {
        return $this->statut === 'suspendu';
    }

    public function peutEtreTermine(): bool
    {
        return $this->statut === 'en_cours';
    }

    public function peutEtreAnnule(): bool
    {
        return !in_array($this->statut, ['termine', 'annule', 'archive']);
    }

    /**
     * MÉTHODE UTILITAIRE : Changer le statut avec historique
     */
    private function changerStatut(string $nouveauStatut, string $activite = null): bool
    {
        return $this->update([
            'statut' => $nouveauStatut,
            'statut_precedent' => $this->statut, // AJOUTÉ : historique
            'derniere_mise_a_jour' => now()->toDateString(),
            'derniere_activite' => $activite ?? "Statut changé vers {$nouveauStatut}",
            'derniere_activite_date' => now(),
            'derniere_activite_par' => auth()->id(),
        ]);
    }

    /**
     * Approuve un projet
     */
    public function approuver($approbateurId = null, $commentaires = null): bool
    {
        if (!$this->peutEtreApprouve()) {
            return false;
        }

        return $this->update([
            'approuve_par' => $approbateurId ?? auth()->id(),
            'approuve_le' => now(),
            'commentaires_approbation' => $commentaires,
            'derniere_mise_a_jour' => now()->toDateString(),
            'derniere_activite' => 'Projet approuvé',
            'derniere_activite_date' => now(),
            'derniere_activite_par' => auth()->id(),
        ]);
    }

    /**
     * Fait passer le projet en planification
     */
    public function planifier(): bool
    {
        if (!$this->peutEtrePlanifie()) {
            return false;
        }

        return $this->changerStatut('planification', 'Projet planifié');
    }

    /**
     * Démarre le projet (passe en cours)
     */
    public function demarrer($dateDebut = null): bool
    {
        if (!$this->peutEtreDemarre()) {
            return false;
        }

        return $this->update([
            'statut' => 'en_cours',
            'statut_precedent' => $this->statut,
            'date_debut' => $dateDebut ?? now()->toDateString(),
            'derniere_mise_a_jour' => now()->toDateString(),
            'derniere_activite' => 'Projet démarré',
            'derniere_activite_date' => now(),
            'derniere_activite_par' => auth()->id(),
        ]);
    }

    /**
     * Suspend un projet
     */
    public function suspendre($motif = null): bool
    {
        if (!$this->peutEtreSuspendu()) {
            return false;
        }

        return $this->changerStatut('suspendu', $motif ?? 'Projet suspendu');
    }

    /**
     * Reprend un projet suspendu
     */
    public function reprendre(): bool
    {
        if (!$this->peutEtreRepris()) {
            return false;
        }

        return $this->changerStatut('en_cours', 'Projet repris');
    }

    /**
     * Termine un projet
     */
    public function terminer($dateFin = null, $resultats = null): bool
    {
        if (!$this->peutEtreTermine()) {
            return false;
        }

        $updateData = [
            'statut' => 'termine',
            'statut_precedent' => $this->statut,
            'date_fin_reelle' => $dateFin ?? now()->toDateString(),
            'pourcentage_completion' => 100,
            'derniere_mise_a_jour' => now()->toDateString(),
            'derniere_activite' => 'Projet terminé',
            'derniere_activite_date' => now(),
            'derniere_activite_par' => auth()->id(),
        ];

        if ($resultats) {
            $updateData['resultats_obtenus'] = $resultats;
        }

        return $this->update($updateData);
    }

    /**
     * Annule un projet
     */
    public function annuler($motif = null): bool
    {
        if (!$this->peutEtreAnnule()) {
            return false;
        }

        return $this->changerStatut('annule', $motif ?? 'Projet annulé');
    }

    public function mettreAJourProgression($pourcentage, $activite = null): bool
    {
        if ($this->statut !== 'en_cours') {
            return false;
        }

        $updateData = [
            'pourcentage_completion' => max(0, min(100, $pourcentage)),
            'derniere_mise_a_jour' => now()->toDateString(),
            'derniere_activite_date' => now(),
            'derniere_activite_par' => auth()->id(),
        ];

        if ($activite) {
            $updateData['derniere_activite'] = $activite;
        }

        return $this->update($updateData);
    }

    /**
     * CORRIGÉ : Gestion des fonds sans modification directe du budget_collecte
     */
    public function ajouterFonds($montant, $source = null): void
    {
        // Ne plus incrémenter budget_collecte car c'est calculé automatiquement

        if ($source && $this->sources_financement) {
            $sources = $this->sources_financement;
            $sources[] = [
                'source' => $source,
                'montant' => $montant,
                'date' => now()->toDateString(),
            ];
            $this->update(['sources_financement' => $sources]);
        }
    }

    public function ajouterDepense($montant, $description = null): void
    {
        $this->increment('budget_depense', $montant);

        if ($description && $this->detail_budget) {
            $details = $this->detail_budget;
            $details['depenses'][] = [
                'description' => $description,
                'montant' => $montant,
                'date' => now()->toDateString(),
            ];
            $this->update(['detail_budget' => $details]);
        }
    }

    /**
     * Retourne la prochaine action logique possible
     */
    public function getProchainePossibleAction(): ?string
    {
        if ($this->peutEtreApprouve())
            return 'approuver';
        if ($this->peutEtrePlanifie())
            return 'planifier';
        if ($this->peutEtreEnRechercheFinancement())
            return 'rechercher_financement';
        if ($this->peutEtreEnAttente())
            return 'mettre_en_attente';
        if ($this->peutEtreDemarre())
            return 'demarrer';
        if ($this->peutEtreTermine())
            return 'terminer';
        if ($this->peutEtreSuspendu())
            return 'suspendre';
        if ($this->peutEtreRepris())
            return 'reprendre';

        return null;
    }

    /**
     * Vérifie si le projet nécessite une action
     */
    public function necessiteAction(): bool
    {
        return $this->getProchainePossibleAction() !== null;
    }

    public function dupliquer($nouveauNom = null, $nouveauCode = null): self
    {
        $nouveauProjet = $this->replicate();

        // Réinitialiser certains champs
        $nouveauProjet->nom_projet = $nouveauNom ?? $this->nom_projet . ' (Copie)';
        $nouveauProjet->code_projet = $nouveauCode ?? $this->genererNouveauCode();
        $nouveauProjet->statut = 'conception';
        $nouveauProjet->statut_precedent = null;
        $nouveauProjet->pourcentage_completion = 0;
        $nouveauProjet->budget_depense = 0;
        $nouveauProjet->date_debut = null;
        $nouveauProjet->date_fin_reelle = null;
        $nouveauProjet->duree_reelle_jours = null;
        $nouveauProjet->approuve_par = null;
        $nouveauProjet->approuve_le = null;
        $nouveauProjet->resultats_obtenus = null;
        $nouveauProjet->projet_parent_id = $this->id;

        $nouveauProjet->save();

        return $nouveauProjet;
    }

    // Validation CORRIGÉE
    public function validate(): array
    {
        $errors = [];

        // Validation des dates
        if ($this->date_debut && $this->date_fin_prevue && $this->date_debut > $this->date_fin_prevue) {
            $errors[] = "La date de début ne peut pas être postérieure à la date de fin prévue.";
        }

        if ($this->date_fin_reelle && $this->date_debut && $this->date_fin_reelle < $this->date_debut) {
            $errors[] = "La date de fin réelle ne peut pas être antérieure à la date de début.";
        }

        // Validation du budget
        if ($this->budget_minimum && $this->budget_prevu && $this->budget_minimum > $this->budget_prevu) {
            $errors[] = "Le budget minimum ne peut pas être supérieur au budget prévu.";
        }

        if ($this->budget_depense < 0) {
            $errors[] = "Les montants dépensés ne peuvent pas être négatifs.";
        }

        // Validation du pourcentage
        if ($this->pourcentage_completion < 0 || $this->pourcentage_completion > 100) {
            $errors[] = "Le pourcentage de completion doit être entre 0 et 100.";
        }

        // Validation de la note de satisfaction
        if ($this->note_satisfaction && ($this->note_satisfaction < 1 || $this->note_satisfaction > 10)) {
            $errors[] = "La note de satisfaction doit être entre 1 et 10.";
        }

        // Validation de l'approbation
        if ($this->statut === 'en_cours' && $this->necessite_approbation && !$this->est_approuve) {
            $errors[] = "Le projet doit être approuvé avant d'être démarré.";
        }

        // NOUVELLES VALIDATIONS de cohérence workflow
        if ($this->statut === 'en_cours' && $this->pourcentage_completion === 0) {
            $errors[] = "Un projet en cours doit avoir un pourcentage de completion > 0.";
        }

        if ($this->statut === 'recherche_financement' && !$this->ouvert_aux_dons) {
            $errors[] = "Un projet en recherche de financement doit être ouvert aux dons.";
        }

        if ($this->statut === 'recherche_financement' && !$this->budget_prevu) {
            $errors[] = "Un projet en recherche de financement doit avoir un budget défini.";
        }

        if ($this->statut === 'termine' && $this->pourcentage_completion < 100) {
            $errors[] = "Un projet terminé doit avoir un pourcentage de completion de 100%.";
        }

        return $errors;
    }

    // Méthodes utilitaires privées
    private function genererNouveauCode(): string
    {
        $baseCode = preg_replace('/\d+$/', '', $this->code_projet);
        $counter = 1;

        do {
            $nouveauCode = $baseCode . sprintf('%03d', $counter);
            $counter++;
        } while (static::where('code_projet', $nouveauCode)->exists());

        return $nouveauCode;
    }

    // Événements du modèle CORRIGÉS
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($projet) {
            $projet->cree_par = auth()->id();
            $projet->date_creation = $projet->date_creation ?? now()->toDateString();
            $projet->derniere_mise_a_jour = now()->toDateString();

            if (empty($projet->code_projet)) {
                $projet->code_projet = $projet->genererCodeProjet();
            }
        });

        static::updating(function ($projet) {
            $projet->modifie_par = auth()->id();

            // Historique du statut CORRIGÉ
            if ($projet->isDirty('statut') && !$projet->isDirty('statut_precedent')) {
                $projet->statut_precedent = $projet->getOriginal('statut');
            }

            // Calculer automatiquement la durée réelle si le projet est terminé
            if ($projet->statut === 'termine' && $projet->date_debut && $projet->date_fin_reelle) {
                $projet->duree_reelle_jours = $projet->date_debut->diffInDays($projet->date_fin_reelle);
            }

            // Auto-validation lors des changements critiques
            if ($projet->isDirty(['statut', 'budget_prevu', 'budget_minimum'])) {
                $errors = $projet->validate();
                if (!empty($errors)) {
                    throw new \InvalidArgumentException('Validation échouée: ' . implode(', ', $errors));
                }
            }
        });

        // AJOUTÉ : Observer pour synchroniser les changements de fonds
        static::saved(function ($projet) {
            // Invalider le cache si nécessaire ou déclencher des événements
            if ($projet->wasChanged('statut')) {
                // Optionnel: logger les changements de statut
                \Log::info("Projet {$projet->code_projet} : statut changé vers {$projet->statut}");
            }
        });
    }

    private function genererCodeProjet(): string
    {
        $prefix = strtoupper(substr($this->type_projet ?? 'PROJ', 0, 4));
        $annee = now()->year;
        $sequence = str_pad(
            (static::whereYear('created_at', $annee)->count() + 1),
            3,
            '0',
            STR_PAD_LEFT
        );

        return $prefix . $annee . $sequence;
    }

    // Méthodes statiques AMÉLIORÉES
    public static function statistiquesParStatut(): \Illuminate\Support\Collection
    {
        return static::selectRaw('statut, COUNT(*) as nombre')
            ->groupBy('statut')
            ->orderBy('nombre', 'desc')
            ->get();
    }

    public static function statistiquesParType(): \Illuminate\Support\Collection
    {
        return static::selectRaw('type_projet, COUNT(*) as nombre, SUM(budget_prevu) as budget_total')
            ->groupBy('type_projet')
            ->orderBy('nombre', 'desc')
            ->get();
    }

    public static function projetsEnRetard(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('date_fin_prevue', '<', now())
            ->whereNotIn('statut', ['termine', 'annule', 'archive'])
            ->with(['responsable', 'coordinateur'])
            ->get();
    }

    public static function projetsNecessitantAction(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where(function ($query) {
            $query->where('necessite_approbation', true)
                ->whereNull('approuve_par');
        })
            ->orWhere(function ($query) {
                $query->where('date_fin_prevue', '<', now())
                    ->whereNotIn('statut', ['termine', 'annule', 'archive']);
            })
            ->orWhere(function ($query) {
                $query->where('prochaine_evaluation', '<=', now());
            })
            ->with(['responsable', 'coordinateur'])
            ->get();
    }

    // NOUVELLE MÉTHODE : Obtenir le workflow complet
    // public function getWorkflowPossible(): array
    // {
    //     $workflow = [];

    //     // Depuis conception
    //     if ($this->statut === 'conception') {
    //         if ($this->peutEtreApprouve()) {
    //             $workflow[] = 'approuver';
    //         } elseif ($this->peutEtrePlanifie()) {
    //             $workflow[] = 'planifier';
    //         }
    //     }

    //     // Depuis planification
    //     elseif ($this->statut === 'planification') {
    //         if ($this->peutEtreEnRechercheFinancement()) {
    //             $workflow[] = 'rechercher_financement';
    //         }
    //         if ($this->peutEtreEnAttente()) {
    //             $workflow[] = 'mettre_en_attente';
    //         }
    //     }

    //     // Depuis recherche_financement
    //     elseif ($this->statut === 'recherche_financement') {
    //         if ($this->peutEtreEnAttente()) {
    //             $workflow[] = 'mettre_en_attente';
    //         }
    //     }

    //     // Depuis en_attente
    //     elseif ($this->statut === 'en_attente') {
    //         if ($this->peutEtreDemarre()) {
    //             $workflow[] = 'demarrer';
    //         }
    //     }

    //     // Depuis en_cours
    //     elseif ($this->statut === 'en_cours') {
    //         $workflow[] = 'mettre_a_jour_progression';
    //         if ($this->peutEtreTermine()) {
    //             $workflow[] = 'terminer';
    //         }
    //         if ($this->peutEtreSuspendu()) {
    //             $workflow[] = 'suspendre';
    //         }
    //     }

    //     // Depuis suspendu
    //     elseif ($this->statut === 'suspendu') {
    //         if ($this->peutEtreRepris()) {
    //             $workflow[] = 'reprendre';
    //         }
    //     }

    //     // Actions toujours possibles (si applicable)
    //     if ($this->peutEtreAnnule()) {
    //         $workflow[] = 'annuler';
    //     }

    //     return $workflow;
    // }


    public function getWorkflowPossible(): array
    {
        $workflow = [];

        // Depuis conception
        if ($this->statut === 'conception') {
            if ($this->peutEtreApprouve()) {
                $workflow[] = 'approuver';
            } elseif ($this->peutEtrePlanifie()) {
                $workflow[] = 'planifier';
            }
        }

        // Depuis planification
        elseif ($this->statut === 'planification') {
            // Si pas de budget ou budget déjà financé, peut aller directement en attente
            if (!$this->budget_prevu || $this->est_finance) {
                $workflow[] = 'mettre_en_attente';
            }
            // Sinon, doit passer par recherche de financement
            elseif ($this->peutEtreEnRechercheFinancement()) {
                $workflow[] = 'rechercher_financement';
            }
        }

        // Depuis recherche_financement - C'EST ICI LE PROBLÈME PRINCIPAL
        elseif ($this->statut === 'recherche_financement') {
            // CORRECTION : Toujours permettre la transition vers "en_attente"
            if ($this->peutEtreEnAttente()) {
                $workflow[] = 'mettre_en_attente';
            }
            // Même si pas encore financé, permettre de forcer la mise en attente
            // avec une validation spéciale
            $workflow[] = 'forcer_attente'; // Nouvelle action
        }

        // Depuis en_attente
        elseif ($this->statut === 'en_attente') {
            if ($this->peutEtreDemarre()) {
                $workflow[] = 'demarrer';
            }
        }

        // Depuis en_cours
        elseif ($this->statut === 'en_cours') {
            $workflow[] = 'mettre_a_jour_progression';
            if ($this->peutEtreTermine()) {
                $workflow[] = 'terminer';
            }
            if ($this->peutEtreSuspendu()) {
                $workflow[] = 'suspendre';
            }
        }

        // Depuis suspendu
        elseif ($this->statut === 'suspendu') {
            if ($this->peutEtreRepris()) {
                $workflow[] = 'reprendre';
            }
        }

        // Actions toujours possibles (si applicable)
        if ($this->peutEtreAnnule()) {
            $workflow[] = 'annuler';
        }

        return $workflow;
    }



    /**
     * Retourne les statistiques de financement
     */
    public function getStatistiquesFinancement(): array
    {
        $totalCollecte = $this->budget_collecte;
        $pourcentageFinancement = $this->pourcentage_financement;
        $montantRestant = $this->montant_restant;

        $fonds = $this->fonds()->validees();
        $nombreDonations = $fonds->count();
        $derniereDonation = $fonds->latest('date_transaction')->first();

        return [
            'total_collecte' => $totalCollecte,
            'pourcentage_financement' => $pourcentageFinancement,
            'montant_restant' => $montantRestant,
            'nombre_donations' => $nombreDonations,
            'derniere_donation' => $derniereDonation?->date_transaction?->format('Y-m-d'),
            'est_finance' => $this->est_finance,
            'seuil_financement' => $this->getSeuilFinancement(),
        ];
    }

    /**
     * Vérifie la cohérence des données après modification
     */
    public function verifierCoherence(): array
    {
        $problemes = [];

        // Vérifier cohérence statut/financement
        if ($this->statut === 'recherche_financement' && $this->est_finance) {
            $problemes[] = "Le projet est financé mais reste en recherche de financement";
        }

        // Vérifier cohérence statut/approbation
        if (in_array($this->statut, ['planification', 'en_cours']) && !$this->est_approuve && $this->necessite_approbation) {
            $problemes[] = "Le projet n'est pas approuvé mais a un statut avancé";
        }

        // Vérifier cohérence progression/statut
        if ($this->statut === 'termine' && $this->pourcentage_completion < 100) {
            $problemes[] = "Le projet est terminé mais la progression n'est pas à 100%";
        }

        return $problemes;
    }

    // Accesseurs CKEditor (conservés de l'original)
    public function getDescriptionFormattedAttribute()
    {
        return $this->getFormattedContent('description');
    }

    public function getObjectifFormattedAttribute()
    {
        return $this->getFormattedContent('objectif');
    }

    public function getContexteFormattedAttribute()
    {
        return $this->getFormattedContent('contexte');
    }

    // Méthodes utilitaires pour CKEditor
    public function getMessageWordCount()
    {
        return $this->getWordCount('description') + $this->getWordCount('objectif');
    }

    public function getMessageReadingTime()
    {
        $totalWords = $this->getMessageWordCount();
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

    // Scopes pour CKEditor
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
