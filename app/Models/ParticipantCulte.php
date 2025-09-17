<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ParticipantCulte extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $table = 'participant_cultes';

    /**
     * Clé primaire simple UUID
     */
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Les attributs assignables en masse
     */
    protected $fillable = [
        'participant_id',
        'culte_id',
        'statut_presence',
        'type_participation',
        'heure_arrivee',
        'heure_depart',
        'role_culte',
        'presence_confirmee',
        'confirme_par',
        'confirme_le',
        'premiere_visite',
        'accompagne_par',
        'demande_contact_pastoral',
        'interesse_bapteme',
        'souhaite_devenir_membre',
        'notes_responsable',
        'commentaires_participant',
        'enregistre_par',
        'enregistre_le'
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation
     */
    protected $hidden = [];

    /**
     * Les attributs qui doivent être castés
     */
    protected $casts = [
        'presence_confirmee' => 'boolean',
        'confirme_le' => 'datetime',
        'premiere_visite' => 'boolean',
        'demande_contact_pastoral' => 'boolean',
        'interesse_bapteme' => 'boolean',
        'souhaite_devenir_membre' => 'boolean',
        'enregistre_le' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Constantes pour les énumérations
     */
    const STATUT_PRESENCE = [
        'present' => 'Présent',
        'present_partiel' => 'Présent Partiel',
        'en_retard' => 'En Retard',
        'parti_tot' => 'Parti Tôt'
    ];

    const TYPE_PARTICIPATION = [
        'physique' => 'Physique',
        'en_ligne' => 'En Ligne',
        'hybride' => 'Hybride'
    ];

    const ROLE_CULTE = [
        'participant' => 'Participant',
        'equipe_technique' => 'Équipe Technique',
        'equipe_louange' => 'Équipe Louange',
        'equipe_accueil' => 'Équipe Accueil',
        'orateur' => 'Orateur',
        'dirigeant' => 'Dirigeant',
        'diacre_service' => 'Diacre de Service',
        'collecteur_offrande' => 'Collecteur d\'Offrande',
        'invite_special' => 'Invité Spécial',
        'nouveau_visiteur' => 'Nouveau Visiteur'
    ];

    /**
     * Relations
     */

    /**
     * Relation avec l'membres participant
     */
    public function participant(): BelongsTo
    {

        return $this->belongsTo(User::class, 'participant_id');
    }

    /**
     * Relation avec le culte
     */
    public function culte(): BelongsTo
    {
        return $this->belongsTo(Culte::class, 'culte_id');
    }

    /**
     * Relation avec l'membres qui a confirmé la présence
     */
    public function confirmateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirme_par');
    }

    /**
     * Relation avec l'membres qui a enregistré la participation
     */
    public function enregistreur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enregistre_par');
    }

    /**
     * Relation avec l'membres accompagnateur
     */
    public function accompagnateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accompagne_par');
    }

    /**
     * Accesseurs
     */

    /**
     * Accesseur pour le libellé du statut de présence
     */
    public function getStatutPresenceLibelleAttribute(): string
    {
        return self::STATUT_PRESENCE[$this->statut_presence] ?? $this->statut_presence;
    }

    /**
     * Accesseur pour le libellé du type de participation
     */
    public function getTypeParticipationLibelleAttribute(): string
    {
        return self::TYPE_PARTICIPATION[$this->type_participation] ?? $this->type_participation;
    }

    /**
     * Accesseur pour le libellé du rôle dans le culte
     */
    public function getRoleCulteLibelleAttribute(): string
    {
        return self::ROLE_CULTE[$this->role_culte] ?? $this->role_culte;
    }

    /**
     * Calculer la durée de participation
     */
    public function getDureeParticipationAttribute(): ?string
    {
        if ($this->heure_arrivee && $this->heure_depart) {
            $arrivee = Carbon::parse($this->heure_arrivee);
            $depart = Carbon::parse($this->heure_depart);
            return $arrivee->diff($depart)->format('%H:%I:%S');
        }

        return null;
    }

    /**
     * Vérifier si c'est une participation complète
     */
    public function getIsParticipationCompleteAttribute(): bool
    {
        return $this->statut_presence === 'present';
    }

    /**
     * Vérifier si la participation nécessite un suivi
     */
    public function getNecessiteSuiviAttribute(): bool
    {
        return $this->premiere_visite ||
               $this->demande_contact_pastoral ||
               $this->interesse_bapteme ||
               $this->souhaite_devenir_membre;
    }

    /**
     * Obtenir le nom complet du participant
     */
    public function getNomParticipantAttribute(): string
    {
        return $this->participant ? $this->participant->nom_complet : 'Inconnu';
    }

    /**
     * Obtenir le titre du culte
     */
    public function getTitreCulteAttribute(): string
    {
        return $this->culte ? $this->culte->titre : 'Culte inconnu';
    }

    /**
     * Accesseurs pour les heures formatées
     */
    public function getHeureArriveeFormatteeAttribute(): ?string
    {
        return $this->heure_arrivee ? Carbon::parse($this->heure_arrivee)->format('H:i') : null;
    }

    public function getHeureDepartFormatteeAttribute(): ?string
    {
        return $this->heure_depart ? Carbon::parse($this->heure_depart)->format('H:i') : null;
    }

    /**
     * Scopes
     */

    /**
     * Scope pour les présences confirmées
     */
    public function scopeConfirmees(Builder $query): Builder
    {
        return $query->where('presence_confirmee', true);
    }

    /**
     * Scope pour les premières visites
     */
    public function scopePremiereVisite(Builder $query): Builder
    {
        return $query->where('premiere_visite', true);
    }

    /**
     * Scope pour les participants nécessitant un suivi pastoral
     */
    public function scopeNecessitantSuivi(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->where('premiere_visite', true)
              ->orWhere('demande_contact_pastoral', true)
              ->orWhere('interesse_bapteme', true)
              ->orWhere('souhaite_devenir_membre', true);
        });
    }

    /**
     * Scope pour filtrer par type de participation
     */
    public function scopeParType(Builder $query, string $type): Builder
    {
        return $query->where('type_participation', $type);
    }

    /**
     * Scope pour filtrer par rôle
     */
    public function scopeParRole(Builder $query, string $role): Builder
    {
        return $query->where('role_culte', $role);
    }

    /**
     * Scope pour filtrer par statut de présence
     */
    public function scopeParStatut(Builder $query, string $statut): Builder
    {
        return $query->where('statut_presence', $statut);
    }

    /**
     * Scope pour les participations physiques
     */
    public function scopePhysique(Builder $query): Builder
    {
        return $query->where('type_participation', 'physique');
    }

    /**
     * Scope pour les participations en ligne
     */
    public function scopeEnLigne(Builder $query): Builder
    {
        return $query->where('type_participation', 'en_ligne');
    }

    /**
     * Scope pour filtrer par culte
     */
    public function scopeParCulte(Builder $query, string $culteId): Builder
    {
        return $query->where('culte_id', $culteId);
    }

    /**
     * Scope pour filtrer par participant
     */
    public function scopeParParticipant(Builder $query, string $participantId): Builder
    {
        return $query->where('participant_id', $participantId);
    }

    /**
     * Scope pour les participations d'une période donnée
     */
    public function scopeParPeriode(Builder $query, $dateDebut, $dateFin): Builder
    {
        return $query->whereHas('culte', function ($q) use ($dateDebut, $dateFin) {
            $q->whereBetween('date_culte', [$dateDebut, $dateFin]);
        });
    }

    /**
     * Scope pour les participations récentes
     */
    public function scopeRecentes(Builder $query, int $jours = 30): Builder
    {
        return $query->whereHas('culte', function ($q) use ($jours) {
            $q->where('date_culte', '>=', now()->subDays($jours));
        });
    }

    /**
     * Méthodes utilitaires
     */

    /**
     * Marquer la présence comme confirmée
     */
    public function confirmerPresence(string $confirmateurId = null): bool
    {
        $this->presence_confirmee = true;
        $this->confirme_par = $confirmateurId ?? auth()->id();
        $this->confirme_le = now();

        return $this->save();
    }

    /**
     * Vérifier si la participation était en retard
     */
    public function etaitEnRetard(): bool
    {
        if (!$this->heure_arrivee || !$this->culte) {
            return false;
        }

        $heureDebutCulte = Carbon::parse($this->culte->heure_debut);
        $heureArrivee = Carbon::parse($this->heure_arrivee);

        return $heureArrivee->gt($heureDebutCulte);
    }

    /**
     * Vérifier si la participation était partielle
     */
    public function etaitPartielle(): bool
    {
        return in_array($this->statut_presence, ['present_partiel', 'en_retard', 'parti_tot']);
    }

    /**
     * Obtenir les informations de suivi nécessaires
     */
    public function getInfosSuivi(): array
    {
        return [
            'premiere_visite' => $this->premiere_visite,
            'demande_contact_pastoral' => $this->demande_contact_pastoral,
            'interesse_bapteme' => $this->interesse_bapteme,
            'souhaite_devenir_membre' => $this->souhaite_devenir_membre,
            'accompagnateur' => $this->accompagnateur ? $this->accompagnateur->nom_complet : null,
            'commentaires' => $this->commentaires_participant,
            'notes_responsable' => $this->notes_responsable
        ];
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Automatiquement définir les champs lors de la création
        static::creating(function ($model) {
            if (!$model->enregistre_par) {
                $model->enregistre_par = auth()->id();
            }
            if (!$model->enregistre_le) {
                $model->enregistre_le = now();
            }
            // Définir heure_arrivee automatiquement si vide
            if (!$model->heure_arrivee) {
                $model->heure_arrivee = now()->format('H:i:s');
            }
            if (!isset($model->presence_confirmee)) {
                $model->presence_confirmee = true;
            }
        });
    }
}
