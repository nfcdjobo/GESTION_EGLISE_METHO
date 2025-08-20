<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Annonce extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'titre',
        'contenu',
        'resume_court',
        'sous_titre',
        'type_annonce',
        'categorie',
        'niveau_priorite',
        'niveau_importance',
        'audience_cible',
        'groupes_specifiques',
        'ministeres_cibles',
        'classes_cibles',
        'publie_le',
        'expire_le',
        'date_evenement',
        'heure_evenement',
        'publication_programmee',
        'rappel_active',
        'afficher_site_web',
        'afficher_ecrans',
        'envoyer_email',
        'envoyer_sms',
        'publier_reseaux_sociaux',
        'annoncer_culte',
        'afficher_app_mobile',
        'image_principale',
        'images_annexes',
        'video_url',
        'documents_joints',
        'lien_externe',
        'lien_inscription',
        'call_to_action',
        'contact_principal_id',
        'telephone_contact',
        'email_contact',
        'informations_pratiques',
        'instructions_speciales',
        'lieu_evenement',
        'adresse_complete',
        'latitude',
        'longitude',
        'statut',
        'approuvee_par',
        'approuvee_le',
        'commentaires_approbation',
        'motif_refus',
        'est_recurrente',
        'frequence_recurrence',
        'fin_recurrence',
        'annonce_parent_id',
        'nombre_vues',
        'nombre_clics',
        'nombre_partages',
        'nombre_inscriptions',
        'statistiques_interaction',
        'taux_engagement',
        'rappels_programmes',
        'dernier_rappel_envoye',
        'nombre_rappels_envoyes',
        'notification_admin_envoyee',
        'nombre_likes',
        'nombre_commentaires',
        'reactions',
        'feedback_recu',
        'note_moyenne',
        'age_min',
        'age_max',
        'criteres_ciblage',
        'membres_uniquement',
        'necessite_inscription',
        'necessite_approbation',
        'contenu_sensible',
        'tags_moderation',
        'notes_moderateur',
        'meta_title',
        'meta_description',
        'slug',
        'mots_cles',
        'canaux_diffusion_utilises',
        'derniere_interaction',
        'performances_canal',
        'cout_diffusion',
        'date_archivage',
        'raison_archivage',
        'historique_modifications',
        'conservee_historique',
        'cree_par',
        'modifie_par',
        'commentaires_auteur',
        'notes_admin',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'publie_le' => 'datetime',
        'expire_le' => 'datetime',
        'date_evenement' => 'date',
        'heure_evenement' => 'datetime:H:i',
        'approuvee_le' => 'datetime',
        'fin_recurrence' => 'date',
        'dernier_rappel_envoye' => 'datetime',
        'derniere_interaction' => 'datetime',
        'date_archivage' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'nombre_vues' => 'integer',
        'nombre_clics' => 'integer',
        'nombre_partages' => 'integer',
        'nombre_inscriptions' => 'integer',
        'nombre_rappels_envoyes' => 'integer',
        'nombre_likes' => 'integer',
        'nombre_commentaires' => 'integer',
        'age_min' => 'integer',
        'age_max' => 'integer',
        'taux_engagement' => 'decimal:2',
        'note_moyenne' => 'decimal:1',
        'cout_diffusion' => 'decimal:2',
        'publication_programmee' => 'boolean',
        'rappel_active' => 'boolean',
        'afficher_site_web' => 'boolean',
        'afficher_ecrans' => 'boolean',
        'envoyer_email' => 'boolean',
        'envoyer_sms' => 'boolean',
        'publier_reseaux_sociaux' => 'boolean',
        'annoncer_culte' => 'boolean',
        'afficher_app_mobile' => 'boolean',
        'est_recurrente' => 'boolean',
        'notification_admin_envoyee' => 'boolean',
        'membres_uniquement' => 'boolean',
        'necessite_inscription' => 'boolean',
        'necessite_approbation' => 'boolean',
        'contenu_sensible' => 'boolean',
        'conservee_historique' => 'boolean',
        'groupes_specifiques' => 'array',
        'ministeres_cibles' => 'array',
        'classes_cibles' => 'array',
        'images_annexes' => 'array',
        'documents_joints' => 'array',
        'statistiques_interaction' => 'array',
        'rappels_programmes' => 'array',
        'reactions' => 'array',
        'criteres_ciblage' => 'array',
        'mots_cles' => 'array',
        'canaux_diffusion_utilises' => 'array',
        'performances_canal' => 'array',
        'historique_modifications' => 'array',
    ];

    /**
     * Relation avec le contact principal
     */
    public function contactPrincipal()
    {
        return $this->belongsTo(User::class, 'contact_principal_id');
    }

    /**
     * Relation avec l'utilisateur qui a approuvé
     */
    public function approbateur()
    {
        return $this->belongsTo(User::class, 'approuvee_par');
    }

    /**
     * Relation avec l'annonce parent
     */
    public function annonceParent()
    {
        return $this->belongsTo(Annonce::class, 'annonce_parent_id');
    }

    /**
     * Relation avec les annonces enfants
     */
    public function annoncesEnfants()
    {
        return $this->hasMany(Annonce::class, 'annonce_parent_id');
    }

    /**
     * Utilisateur qui a créé l'annonce
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Dernier utilisateur qui a modifié l'annonce
     */
    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    /**
     * Boot du modèle pour générer le slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($annonce) {
            if (empty($annonce->slug)) {
                $annonce->slug = Str::slug($annonce->titre);
            }
        });

        static::updating(function ($annonce) {
            if ($annonce->isDirty('titre') && empty($annonce->slug)) {
                $annonce->slug = Str::slug($annonce->titre);
            }
        });
    }

    /**
     * Scope pour les annonces actives
     */
    public function scopeActives($query)
    {
        return $query->where('statut', 'publiee')
                     ->where(function($q) {
                         $q->whereNull('expire_le')
                           ->orWhere('expire_le', '>', now());
                     });
    }

    /**
     * Scope pour les annonces publiées
     */
    public function scopePubliees($query)
    {
        return $query->where('statut', 'publiee');
    }

    /**
     * Scope pour les annonces en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les annonces urgentes
     */
    public function scopeUrgentes($query)
    {
        return $query->whereIn('niveau_priorite', ['urgent', 'critique']);
    }

    /**
     * Scope pour filtrer par audience
     */
    public function scopeParAudience($query, $audience)
    {
        return $query->where('audience_cible', $audience);
    }

    /**
     * Scope pour filtrer par type
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_annonce', $type);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeParCategorie($query, $categorie)
    {
        return $query->where('categorie', $categorie);
    }

    /**
     * Scope pour les annonces du site web
     */
    public function scopeSiteWeb($query)
    {
        return $query->where('afficher_site_web', true);
    }

    /**
     * Scope pour les annonces récurrentes
     */
    public function scopeRecurrentes($query)
    {
        return $query->where('est_recurrente', true);
    }

    /**
     * Scope pour les annonces avec événement
     */
    public function scopeAvecEvenement($query)
    {
        return $query->whereNotNull('date_evenement');
    }

    /**
     * Vérifier si l'annonce est expirée
     */
    public function isExpiree()
    {
        return $this->expire_le && $this->expire_le < now();
    }

    /**
     * Vérifier si l'annonce peut être modifiée
     */
    public function canBeModified()
    {
        return in_array($this->statut, ['brouillon', 'en_attente']);
    }

    /**
     * Vérifier si l'annonce peut être supprimée
     */
    public function canBeDeleted()
    {
        return in_array($this->statut, ['brouillon', 'en_attente', 'expiree']);
    }

    /**
     * Approuver l'annonce
     */
    public function approuver($approbateurId = null, $commentaires = null)
    {
        $this->update([
            'statut' => 'approuvee',
            'approuvee_par' => $approbateurId,
            'approuvee_le' => now(),
            'commentaires_approbation' => $commentaires,
        ]);
    }

    /**
     * Rejeter l'annonce
     */
    public function rejeter($motif = null)
    {
        $this->update([
            'statut' => 'brouillon',
            'motif_refus' => $motif,
        ]);
    }

    /**
     * Publier l'annonce
     */
    public function publier()
    {
        if ($this->statut !== 'approuvee') {
            throw new \Exception('L\'annonce doit être approuvée pour être publiée');
        }

        $this->update([
            'statut' => 'publiee',
            'publie_le' => now(),
        ]);
    }

    /**
     * Archiver l'annonce
     */
    public function archiver($raison = null)
    {
        $this->update([
            'statut' => 'archivee',
            'date_archivage' => now()->toDateString(),
            'raison_archivage' => $raison,
        ]);
    }

    /**
     * Incrémenter les vues
     */
    public function incrementerVues()
    {
        $this->increment('nombre_vues');
        $this->update(['derniere_interaction' => now()]);
    }

    /**
     * Incrémenter les clics
     */
    public function incrementerClics()
    {
        $this->increment('nombre_clics');
        $this->update(['derniere_interaction' => now()]);
    }

    /**
     * Incrémenter les partages
     */
    public function incrementerPartages()
    {
        $this->increment('nombre_partages');
        $this->update(['derniere_interaction' => now()]);
    }

    /**
     * Calculer le taux d'engagement
     */
    public function calculerTauxEngagement()
    {
        if ($this->nombre_vues > 0) {
            $engagements = $this->nombre_clics + $this->nombre_partages + $this->nombre_likes;
            $taux = ($engagements / $this->nombre_vues) * 100;
            $this->update(['taux_engagement' => $taux]);
            return $taux;
        }

        return 0;
    }

    /**
     * Vérifier si l'annonce cible un utilisateur
     */
    public function cibleUtilisateur(User $user)
    {
        // Vérifier l'audience générale
        if ($this->audience_cible === 'tous') {
            return true;
        }

        // Vérifier les critères spécifiques
        switch ($this->audience_cible) {
            case 'membres':
                return $user->statut_membre === 'actif';
            case 'jeunes':
                $age = $user->date_naissance ? $user->date_naissance->age : null;
                return $age && $age >= 12 && $age <= 35;
            case 'adultes':
                $age = $user->date_naissance ? $user->date_naissance->age : null;
                return $age && $age >= 18;
            case 'enfants':
                $age = $user->date_naissance ? $user->date_naissance->age : null;
                return $age && $age < 12;
            // Ajouter d'autres critères selon les besoins
        }

        return false;
    }

    /**
     * Accesseur pour l'URL de l'annonce
     */
    public function getUrlAttribute()
    {
        return route('annonces.show', $this->slug);
    }

    /**
     * Accesseur pour le statut d'expiration
     */
    public function getStatutExpirationAttribute()
    {
        if (!$this->expire_le) {
            return 'permanent';
        }

        if ($this->expire_le < now()) {
            return 'expiree';
        }

        $joursRestants = now()->diffInDays($this->expire_le);
        if ($joursRestants <= 1) {
            return 'expire_bientot';
        }

        return 'active';
    }
}
