<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RapportReunion extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Le nom de la table
     */
    protected $table = 'rapport_reunions';

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'reunion_id',
        'titre_rapport',
        'type_rapport',
        'niveau_confidentialite',
        'redacteur_principal_id',
        'validateur_id',
        'secretaire_id',
        'contributeurs',
        'statut',
        'date_limite_redaction',
        'valide_le',
        'publie_le',
        'resume_executif',
        'introduction',
        'objectifs_reunion',
        'deroulement_general',
        'conclusion',
        'ordre_jour_traite',
        'decisions_prises',
        'actions_decidees',
        'responsabilites_attribuees',
        'points_discussion',
        'defis_rencontres',
        'solutions_proposees',
        'liste_presences',
        'excuses_recues',
        'absents_non_excuses',
        'analyse_participation',
        'taux_presence',
        'temps_priere',
        'temps_louange',
        'message_partage',
        'temoignages',
        'demandes_priere',
        'nombre_conversions',
        'nombre_rededications',
        'mouvements_esprit',
        'offrandes_recoltees',
        'detail_finances',
        'rapport_tresorier',
        'depenses_engagees',
        'note_organisation',
        'note_contenu',
        'note_participation',
        'note_spiritualite',
        'satisfaction_generale',
        'retours_positifs',
        'critiques_constructives',
        'suggestions_amelioration',
        'lecons_apprises',
        'bonnes_pratiques',
        'actions_suivre',
        'recommandations',
        'preparation_prochaine',
        'prochaine_echeance',
        'suivi_precedent',
        'documents_annexes',
        'photos_rapport',
        'lien_enregistrement_audio',
        'lien_enregistrement_video',
        'presentations_utilisees',
        'problemes_techniques',
        'solutions_techniques',
        'materiel_utilise',
        'recommandations_techniques',
        'conforme_procedures',
        'ecarts_procedures',
        'justification_ecarts',
        'audit_requis',
        'observations_audit',
        'numero_version',
        'version_precedente_id',
        'modifications_version',
        'derniere_modification',
        'destinataires',
        'envoye_conseil',
        'envoye_leadership',
        'date_diffusion',
        'canal_diffusion',
        'reference_archivage',
        'date_archivage',
        'duree_conservation',
        'cree_par',
        'modifie_par',
        'commentaires_redacteur',
        'commentaires_validateur',
        'historique_modifications',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'date_limite_redaction' => 'date',
        'valide_le' => 'datetime',
        'publie_le' => 'datetime',
        'derniere_modification' => 'datetime',
        'date_diffusion' => 'datetime',
        'date_archivage' => 'date',
        'prochaine_echeance' => 'date',
        'taux_presence' => 'decimal:2',
        'nombre_conversions' => 'integer',
        'nombre_rededications' => 'integer',
        'offrandes_recoltees' => 'decimal:2',
        'depenses_engagees' => 'decimal:2',
        'note_organisation' => 'decimal:1',
        'note_contenu' => 'decimal:1',
        'note_participation' => 'decimal:1',
        'note_spiritualite' => 'decimal:1',
        'satisfaction_generale' => 'decimal:2',
        'numero_version' => 'integer',
        'conforme_procedures' => 'boolean',
        'audit_requis' => 'boolean',
        'envoye_conseil' => 'boolean',
        'envoye_leadership' => 'boolean',
        'contributeurs' => 'array',
        'ordre_jour_traite' => 'array',
        'responsabilites_attribuees' => 'array',
        'liste_presences' => 'array',
        'excuses_recues' => 'array',
        'absents_non_excuses' => 'array',
        'detail_finances' => 'array',
        'actions_suivre' => 'array',
        'suivi_precedent' => 'array',
        'documents_annexes' => 'array',
        'photos_rapport' => 'array',
        'presentations_utilisees' => 'array',
        'destinataires' => 'array',
    ];

    /**
     * Relation avec la réunion
     */
    public function reunion()
    {
        return $this->belongsTo(Reunion::class, 'reunion_id');
    }

    /**
     * Relation avec le rédacteur principal
     */
    public function redacteurPrincipal()
    {
        return $this->belongsTo(User::class, 'redacteur_principal_id');
    }

    /**
     * Relation avec le validateur
     */
    public function validateur()
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

    /**
     * Relation avec le secrétaire
     */
    public function secretaire()
    {
        return $this->belongsTo(User::class, 'secretaire_id');
    }

    /**
     * Relation avec la version précédente
     */
    public function versionPrecedente()
    {
        return $this->belongsTo(RapportReunion::class, 'version_precedente_id');
    }

    /**
     * Relation avec les versions suivantes
     */
    public function versionsSuivantes()
    {
        return $this->hasMany(RapportReunion::class, 'version_precedente_id');
    }

    /**
     * Utilisateur qui a créé le rapport
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Dernier utilisateur qui a modifié le rapport
     */
    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    /**
     * Scope pour les rapports en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->whereIn('statut', ['brouillon', 'en_revision', 'en_attente_validation']);
    }

    /**
     * Scope pour les rapports publiés
     */
    public function scopePublies($query)
    {
        return $query->where('statut', 'publie');
    }

    /**
     * Scope pour les rapports validés
     */
    public function scopeValides($query)
    {
        return $query->where('statut', 'valide');
    }

    /**
     * Scope pour filtrer par type de rapport
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_rapport', $type);
    }

    /**
     * Scope pour filtrer par niveau de confidentialité
     */
    public function scopeParConfidentialite($query, $niveau)
    {
        return $query->where('niveau_confidentialite', $niveau);
    }

    /**
     * Scope pour filtrer par rédacteur
     */
    public function scopeParRedacteur($query, $redacteurId)
    {
        return $query->where('redacteur_principal_id', $redacteurId);
    }

    /**
     * Vérifier si le rapport peut être modifié
     */
    public function canBeModified()
    {
        return in_array($this->statut, ['brouillon', 'en_revision']);
    }

    /**
     * Vérifier si le rapport peut être validé
     */
    public function canBeValidated()
    {
        return $this->statut === 'en_attente_validation';
    }

    /**
     * Vérifier si le rapport peut être publié
     */
    public function canBePublished()
    {
        return $this->statut === 'valide';
    }

    /**
     * Soumettre le rapport pour validation
     */
    public function soumettrePourValidation()
    {
        if ($this->statut !== 'en_revision') {
            throw new \Exception('Le rapport doit être en révision pour être soumis');
        }

        $this->update(['statut' => 'en_attente_validation']);
    }

    /**
     * Valider le rapport
     */
    public function valider($validateurId = null, $commentaires = null)
    {
        $this->update([
            'statut' => 'valide',
            'validateur_id' => $validateurId,
            'valide_le' => now(),
            'commentaires_validateur' => $commentaires,
        ]);
    }

    /**
     * Rejeter le rapport
     */
    public function rejeter($commentaires = null)
    {
        $this->update([
            'statut' => 'rejete',
            'commentaires_validateur' => $commentaires,
        ]);
    }

    /**
     * Publier le rapport
     */
    public function publier()
    {
        if ($this->statut !== 'valide') {
            throw new \Exception('Le rapport doit être validé pour être publié');
        }

        $this->update([
            'statut' => 'publie',
            'publie_le' => now(),
        ]);
    }

    /**
     * Archiver le rapport
     */
    public function archiver()
    {
        $this->update([
            'statut' => 'archive',
            'date_archivage' => now()->toDateString(),
        ]);
    }

    /**
     * Créer une nouvelle version du rapport
     */
    public function creerNouvelleVersion($modifications = null)
    {
        $nouvelleVersion = $this->replicate();
        $nouvelleVersion->version_precedente_id = $this->id;
        $nouvelleVersion->numero_version = $this->numero_version + 1;
        $nouvelleVersion->modifications_version = $modifications;
        $nouvelleVersion->statut = 'brouillon';
        $nouvelleVersion->valide_le = null;
        $nouvelleVersion->publie_le = null;
        $nouvelleVersion->validateur_id = null;
        $nouvelleVersion->save();

        return $nouvelleVersion;
    }

    /**
     * Générer une référence d'archivage
     */
    public function genererReferenceArchivage()
    {
        $prefix = 'RPT';
        $annee = $this->reunion->date_reunion->format('Y');
        $mois = $this->reunion->date_reunion->format('m');
        $compteur = str_pad($this->id, 6, '0', STR_PAD_LEFT);

        return $prefix . $annee . $mois . $compteur;
    }

    /**
     * Accesseur pour la note moyenne
     */
    public function getNoteMoyenneAttribute()
    {
        $notes = array_filter([
            $this->note_organisation,
            $this->note_contenu,
            $this->note_participation,
            $this->note_spiritualite,
        ]);

        return count($notes) > 0 ? array_sum($notes) / count($notes) : null;
    }

    /**
     * Calculer le délai de rédaction
     */
    public function getDelaiRedaction()
    {
        if ($this->valide_le && $this->created_at) {
            return $this->created_at->diffInDays($this->valide_le);
        }

        return null;
    }

    /**
     * Vérifier si le rapport est en retard
     */
    public function isEnRetard()
    {
        return $this->date_limite_redaction &&
               $this->date_limite_redaction < now() &&
               !in_array($this->statut, ['valide', 'publie', 'archive']);
    }
}
