<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Culte extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'titre',
        'description',
        'date_culte',
        'heure_debut',
        'heure_fin',
        'heure_debut_reelle',
        'heure_fin_reelle',
        'type_culte',
        'categorie',
        'lieu',
        'adresse_lieu',
        'capacite_prevue',
        'pasteur_principal_id',
        'predicateur_id',
        'responsable_culte_id',
        'dirigeant_louange_id',
        'equipe_culte',
        'titre_message',
        'resume_message',
        'passage_biblique',
        'versets_cles',
        'plan_message',
        'ordre_service',
        'cantiques_chantes',
        'duree_louange',
        'duree_message',
        'duree_priere',
        'nombre_participants',
        'nombre_adultes',
        'nombre_enfants',
        'nombre_jeunes',
        'nombre_nouveaux',
        'nombre_conversions',
        'nombre_baptemes',
        'offrande_totale',
        'dime_totale',
        'detail_offrandes',
        'responsable_finances',
        'est_enregistre',
        'lien_enregistrement_audio',
        'lien_enregistrement_video',
        'lien_diffusion_live',
        'photos_culte',
        'diffusion_en_ligne',
        'statut',
        'est_public',
        'necessite_invitation',
        'meteo',
        'atmosphere',
        'notes_pasteur',
        'notes_organisateur',
        'temoignages',
        'points_forts',
        'points_amelioration',
        'demandes_priere',
        'note_globale',
        'note_louange',
        'note_message',
        'note_organisation',
        'cree_par',
        'modifie_par',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'date_culte' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'heure_debut_reelle' => 'datetime:H:i',
        'heure_fin_reelle' => 'datetime:H:i',
        'duree_louange' => 'datetime:H:i',
        'duree_message' => 'datetime:H:i',
        'duree_priere' => 'datetime:H:i',
        'capacite_prevue' => 'integer',
        'nombre_participants' => 'integer',
        'nombre_adultes' => 'integer',
        'nombre_enfants' => 'integer',
        'nombre_jeunes' => 'integer',
        'nombre_nouveaux' => 'integer',
        'nombre_conversions' => 'integer',
        'nombre_baptemes' => 'integer',
        'offrande_totale' => 'decimal:2',
        'dime_totale' => 'decimal:2',
        'note_globale' => 'decimal:1',
        'note_louange' => 'decimal:1',
        'note_message' => 'decimal:1',
        'note_organisation' => 'decimal:1',
        'est_enregistre' => 'boolean',
        'diffusion_en_ligne' => 'boolean',
        'est_public' => 'boolean',
        'necessite_invitation' => 'boolean',
        'equipe_culte' => 'array',
        'versets_cles' => 'array',
        'ordre_service' => 'array',
        'cantiques_chantes' => 'array',
        'detail_offrandes' => 'array',
        'photos_culte' => 'array',
    ];

    /**
     * Relation avec le pasteur principal
     */
    public function pasteurPrincipal()
    {
        return $this->belongsTo(User::class, 'pasteur_principal_id');
    }

    /**
     * Relation avec le prédicateur
     */
    public function predicateur()
    {
        return $this->belongsTo(User::class, 'predicateur_id');
    }

    /**
     * Relation avec le responsable du culte
     */
    public function responsableCulte()
    {
        return $this->belongsTo(User::class, 'responsable_culte_id');
    }

    /**
     * Relation avec le dirigeant de louange
     */
    public function dirigeantLouange()
    {
        return $this->belongsTo(User::class, 'dirigeant_louange_id');
    }

    /**
     * Relation avec les transactions spirituelles
     */
    public function transactionsSpirituelless()
    {
        return $this->hasMany(TransactionSpirituelle::class, 'culte_id');
    }

    /**
     * Relation avec les interventions
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'culte_id');
    }

    /**
     * Utilisateur qui a créé le culte
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Dernier utilisateur qui a modifié le culte
     */
    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    /**
     * Scope pour les cultes à venir
     */
    public function scopeAVenir($query)
    {
        return $query->where('date_culte', '>=', now()->toDateString())
                     ->whereIn('statut', ['planifie', 'planifie']);
    }

    /**
     * Scope pour les cultes terminés
     */
    public function scopeTermines($query)
    {
        return $query->where('statut', 'termine');
    }

    /**
     * Scope pour les cultes publics
     */
    public function scopePublics($query)
    {
        return $query->where('est_public', true);
    }

    /**
     * Scope pour filtrer par type de culte
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_culte', $type);
    }

    /**
     * Scope pour filtrer par date
     */
    public function scopeParDate($query, $date)
    {
        return $query->whereDate('date_culte', $date);
    }

    /**
     * Scope pour les cultes du dimanche
     */
    public function scopeDimanche($query)
    {
        return $query->whereIn('type_culte', ['dimanche_matin', 'dimanche_soir']);
    }

    /**
     * Accesseur pour la durée totale du culte
     */
    public function getDureeTotaleAttribute()
    {
        if ($this->heure_debut_reelle && $this->heure_fin_reelle) {
            return $this->heure_debut_reelle->diffInMinutes($this->heure_fin_reelle);
        }

        if ($this->heure_debut && $this->heure_fin) {
            return $this->heure_debut->diffInMinutes($this->heure_fin);
        }

        return null;
    }

    /**
     * Accesseur pour le total des offrandes et dîmes
     */
    public function getTotalFinancesAttribute()
    {
        return ($this->offrande_totale ?? 0) + ($this->dime_totale ?? 0);
    }

    /**
     * Vérifier si le culte est en cours
     */
    public function isEnCours()
    {
        return $this->statut === 'en_cours';
    }

    /**
     * Vérifier si le culte est terminé
     */
    public function isTermine()
    {
        return $this->statut === 'termine';
    }

    /**
     * Marquer le culte comme commencé
     */
    public function commencer()
    {
        $this->update([
            'statut' => 'en_cours',
            'heure_debut_reelle' => now()->format('H:i'),
        ]);
    }

    /**
     * Marquer le culte comme terminé
     */
    public function terminer()
    {
        $this->update([
            'statut' => 'termine',
            'heure_fin_reelle' => now()->format('H:i'),
        ]);
    }
}
