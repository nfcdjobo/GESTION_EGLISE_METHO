<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Programme extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom_programme',
        'description',
        'code_programme',
        'type_programme',
        'frequence',
        'date_debut',
        'date_fin',
        'heure_debut',
        'heure_fin',
        'jours_semaine',
        'lieu_principal',
        'responsable_principal_id',
        'audience_cible',
        'statut',
        'notes',
        'cree_par',
        'modifie_par',
    ];

    protected $casts = [
        'id' => 'string',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'jours_semaine' => 'array',
        'responsable_principal_id' => 'string',
        'cree_par' => 'string',
        'modifie_par' => 'string',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
            if (empty($model->code_programme)) {
                $model->code_programme = $model->genererCodeProgramme();
            }
        });

        static::updating(function ($model) {
            $model->modifie_par = auth()->id();
        });
    }

    /**
     * Relations
     */
    public function responsablePrincipal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_principal_id');
    }

    public function createurUtilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function modificateurUtilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    /**
     * Scopes
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_programme', $type);
    }

    public function scopeParAudience($query, $audience)
    {
        return $query->where('audience_cible', $audience);
    }

    public function scopeEnCours($query)
    {
        return $query->where('statut', 'actif')
                    ->where(function ($q) {
                        $q->whereNull('date_fin')
                          ->orWhere('date_fin', '>=', now()->toDateString());
                    });
    }

    public function scopeParFrequence($query, $frequence)
    {
        return $query->where('frequence', $frequence);
    }

    /**
     * Accesseurs
     */
    public function getNomCompletAttribute()
    {
        return $this->nom_programme . ' (' . $this->code_programme . ')';
    }

    public function getJoursSemaineTexteAttribute()
    {
        if (!$this->jours_semaine) {
            return 'Non défini';
        }

        $jours = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];

        return collect($this->jours_semaine)
            ->map(fn($jour) => $jours[$jour] ?? '')
            ->filter()
            ->implode(', ');
    }

    public function getHorairesAttribute()
    {
        if (!$this->heure_debut || !$this->heure_fin) {
            return 'Horaires non définis';
        }

        return $this->heure_debut->format('H:i') . ' - ' . $this->heure_fin->format('H:i');
    }

    public function getStatutBadgeAttribute()
    {
        $badges = [
            'planifie' => 'info',
            'actif' => 'success',
            'suspendu' => 'warning',
            'termine' => 'secondary',
            'annule' => 'danger'
        ];

        return $badges[$this->statut] ?? 'light';
    }

    /**
     * Méthodes utiles
     */
    public function genererCodeProgramme()
    {
        $prefix = strtoupper(substr($this->type_programme, 0, 3));
        $numero = self::where('type_programme', $this->type_programme)->count() + 1;

        return $prefix . '-' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    public function estActif()
    {
        return $this->statut === 'actif';
    }

    public function estTermine()
    {
        return $this->statut === 'termine' ||
               ($this->date_fin && $this->date_fin < now()->toDateString());
    }

    public function estEnCours()
    {
        return $this->estActif() && !$this->estTermine();
    }

    public function peutEtreModifie()
    {
        return in_array($this->statut, ['planifie', 'actif', 'suspendu']);
    }

    public function obtenirProchainOccurrence()
    {
        if (!$this->estEnCours() || !$this->jours_semaine) {
            return null;
        }

        $aujourdhui = now();
        $prochainJour = null;

        // Chercher le prochain jour dans la semaine courante
        for ($i = 0; $i < 7; $i++) {
            $date = $aujourdhui->copy()->addDays($i);
            $jourSemaine = $date->dayOfWeek === 0 ? 7 : $date->dayOfWeek; // Dimanche = 7

            if (in_array($jourSemaine, $this->jours_semaine)) {
                if ($i === 0 && $this->heure_debut && $date->format('H:i') > $this->heure_debut->format('H:i')) {
                    continue; // Aujourd'hui mais heure passée
                }
                $prochainJour = $date;
                break;
            }
        }

        return $prochainJour;
    }

    public function activer()
    {
        $this->update(['statut' => 'actif']);
    }

    public function suspendre()
    {
        $this->update(['statut' => 'suspendu']);
    }

    public function terminer()
    {
        $this->update(['statut' => 'termine']);
    }

    public function annuler()
    {
        $this->update(['statut' => 'annule']);
    }

    /**
     * Constantes pour les énumérations
     */
    public const TYPES_PROGRAMME = [
        'culte_regulier' => 'Culte régulier',
        'formation' => 'Formation',
        'evangelisation' => 'Évangélisation',
        'jeunesse' => 'Jeunesse',
        'enfants' => 'Enfants',
        'femmes' => 'Femmes',
        'hommes' => 'Hommes',
        'conference' => 'Conférence',
        'special' => 'Spécial',
        'autre' => 'Autre'
    ];

    public const FREQUENCES = [
        'quotidien' => 'Quotidien',
        'hebdomadaire' => 'Hebdomadaire',
        'mensuel' => 'Mensuel',
        'annuel' => 'Annuel',
        'ponctuel' => 'Ponctuel'
    ];

    public const AUDIENCES = [
        'tous' => 'Tous',
        'membres' => 'Membres',
        'jeunes' => 'Jeunes',
        'adultes' => 'Adultes',
        'enfants' => 'Enfants',
        'femmes' => 'Femmes',
        'hommes' => 'Hommes',
        'visiteurs' => 'Visiteurs'
    ];

    public const STATUTS = [
        'planifie' => 'Planifié',
        'actif' => 'Actif',
        'suspendu' => 'Suspendu',
        'termine' => 'Terminé',
        'annule' => 'Annulé'
    ];
}
