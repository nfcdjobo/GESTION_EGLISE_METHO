<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionSpirituelle extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * Le nom de la table
     */
    protected $table = 'transactions_spirituelles';

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'culte_id',
        'donateur_id',
        'collecteur_id',
        'validateur_id',
        'numero_transaction',
        'date_transaction',
        'heure_transaction',
        'montant',
        'devise',
        'type_transaction',
        'categorie',
        'nom_donateur_anonyme',
        'telephone_donateur',
        'email_donateur',
        'est_anonyme',
        'est_membre',
        'mode_paiement',
        'reference_paiement',
        'numero_cheque',
        'banque_emettrice',
        'details_paiement',
        'description_don_nature',
        'valeur_estimee',
        'inventaire_items',
        'destination',
        'projet_id',
        'ministere_beneficiaire',
        'est_flechee',
        'instructions_donateur',
        'statut',
        'validee_le',
        'motif_annulation',
        'notes_validation',
        'numero_recu',
        'recu_demande',
        'recu_emis',
        'date_emission_recu',
        'lien_recu_pdf',
        'est_recurrente',
        'frequence_recurrence',
        'prochaine_echeance',
        'transaction_parent_id',
        'occasion_speciale',
        'temoignage_donateur',
        'demande_priere_associee',
        'niveau_urgence',
        'latitude',
        'longitude',
        'lieu_collecte',
        'cree_par',
        'modifie_par',
        'derniere_verification',
        'verifie_par',
        'notes_comptable',
        'deductible_impots',
        'taux_deduction',
        'code_fiscal',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'date_transaction' => 'date',
        'heure_transaction' => 'datetime:H:i',
        'montant' => 'decimal:2',
        'valeur_estimee' => 'decimal:2',
        'taux_deduction' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'est_anonyme' => 'boolean',
        'est_membre' => 'boolean',
        'est_flechee' => 'boolean',
        'recu_demande' => 'boolean',
        'recu_emis' => 'boolean',
        'est_recurrente' => 'boolean',
        'deductible_impots' => 'boolean',
        'validee_le' => 'datetime',
        'date_emission_recu' => 'date',
        'prochaine_echeance' => 'date',
        'derniere_verification' => 'datetime',
        'details_paiement' => 'array',
        'inventaire_items' => 'array',
    ];

    /**
     * Relation avec le culte
     */
    public function culte()
    {
        return $this->belongsTo(Culte::class, 'culte_id');
    }

    /**
     * Relation avec le donateur
     */
    public function donateur()
    {
        return $this->belongsTo(User::class, 'donateur_id');
    }

    /**
     * Relation avec le collecteur
     */
    public function collecteur()
    {
        return $this->belongsTo(User::class, 'collecteur_id');
    }

    /**
     * Relation avec le validateur
     */
    public function validateur()
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

    /**
     * Relation avec le projet (il faudra créer ce modèle)
     */
    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }

    /**
     * Relation avec la transaction parent
     */
    public function transactionParent()
    {
        return $this->belongsTo(TransactionSpirituelle::class, 'transaction_parent_id');
    }

    /**
     * Relation avec les transactions enfants
     */
    public function transactionsEnfants()
    {
        return $this->hasMany(TransactionSpirituelle::class, 'transaction_parent_id');
    }

    /**
     * Utilisateur qui a créé la transaction
     */
    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Dernier utilisateur qui a modifié la transaction
     */
    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    /**
     * Utilisateur qui a vérifié la transaction
     */
    public function verificateur()
    {
        return $this->belongsTo(User::class, 'verifie_par');
    }

    /**
     * Scope pour les transactions validées
     */
    public function scopeValidees($query)
    {
        return $query->where('statut', 'validee');
    }

    /**
     * Scope pour les transactions en attente
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour filtrer par type de transaction
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_transaction', $type);
    }

    /**
     * Scope pour filtrer par date
     */
    public function scopeParDate($query, $date)
    {
        return $query->whereDate('date_transaction', $date);
    }

    /**
     * Scope pour filtrer par période
     */
    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_transaction', [$dateDebut, $dateFin]);
    }

    /**
     * Scope pour les dîmes
     */
    public function scopeDimes($query)
    {
        return $query->where('type_transaction', 'dime');
    }

    /**
     * Scope pour les offrandes
     */
    public function scopeOffrandes($query)
    {
        return $query->whereIn('type_transaction', [
            'offrande_libre',
            'offrande_speciale',
            'offrande_ordinaire',
            'offrande_mission',
            'offrande_construction'
        ]);
    }

    /**
     * Scope pour les transactions récurrentes
     */
    public function scopeRecurrentes($query)
    {
        return $query->where('est_recurrente', true);
    }

    /**
     * Générer un numéro de transaction unique
     */
    public static function genererNumeroTransaction()
    {
        $prefix = 'TR';
        $date = now()->format('Ymd');
        $compteur = static::whereDate('created_at', now())->count() + 1;

        return $prefix . $date . str_pad($compteur, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Valider la transaction
     */
    public function valider($validateurId = null)
    {
        $this->update([
            'statut' => 'validee',
            'validateur_id' => $validateurId,
            'validee_le' => now(),
        ]);
    }

    /**
     * Annuler la transaction
     */
    public function annuler($motif = null)
    {
        $this->update([
            'statut' => 'annulee',
            'motif_annulation' => $motif,
        ]);
    }

    /**
     * Vérifier si la transaction peut être modifiée
     */
    public function canBeModified()
    {
        return in_array($this->statut, ['en_attente', 'disputee']);
    }

    /**
     * Vérifier si la transaction peut être supprimée
     */
    public function canBeDeleted()
    {
        return $this->statut === 'en_attente';
    }

    /**
     * Accesseur pour le nom du donateur
     */
    public function getNomDonateurAttribute()
    {
        if ($this->est_anonyme) {
            return 'Anonyme';
        }

        if ($this->donateur) {
            return $this->donateur->nom_complet;
        }

        return $this->nom_donateur_anonyme ?? 'Inconnu';
    }
}
