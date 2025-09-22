<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fonds extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'fonds';

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
        'contact_donateur',
        'est_anonyme',
        'est_membre',
        'mode_paiement',
        'reference_paiement',
        'details_paiement',
        'description_don_nature',
        'valeur_estimee',
        'destination',
        'projet_id',
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
        'fichier_recu',
        'est_recurrente',
        'frequence_recurrence',
        'prochaine_echeance',
        'transaction_parent_id',
        'occasion_speciale',
        'lieu_collecte',
        'cree_par',
        'modifie_par',
        'derniere_verification',
        'verifie_par',
        'notes_comptable',
        'deductible_impots',
    ];

    protected $casts = [
        'date_transaction' => 'date',
        'heure_transaction' => 'datetime:H:i',
        'montant' => 'decimal:2',
        'valeur_estimee' => 'decimal:2',
        'est_anonyme' => 'boolean',
        'est_membre' => 'boolean',
        'est_flechee' => 'boolean',
        'recu_demande' => 'boolean',
        'recu_emis' => 'boolean',
        'date_emission_recu' => 'date',
        'est_recurrente' => 'boolean',
        'prochaine_echeance' => 'date',
        'validee_le' => 'datetime',
        'derniere_verification' => 'datetime',
        'deductible_impots' => 'boolean',
        'details_paiement' => 'array',
    ];

    protected $attributes = [
        'devise' => 'XOF',
        'categorie' => 'reguliere',
        'mode_paiement' => 'especes',
        'est_anonyme' => false,
        'est_membre' => true,
        'est_flechee' => false,
        'recu_demande' => false,
        'recu_emis' => false,
        'est_recurrente' => false,
        'statut' => 'en_attente',
        'deductible_impots' => true,
    ];

    // Relations
    public function culte()
    {
        return $this->belongsTo(Culte::class, 'culte_id');
    }

    public function donateur()
    {
        return $this->belongsTo(User::class, 'donateur_id');
    }

    public function collecteur()
    {
        return $this->belongsTo(User::class, 'collecteur_id');
    }

    public function validateur()
    {
        return $this->belongsTo(User::class, 'validateur_id');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    public function verificateur()
    {
        return $this->belongsTo(User::class, 'verifie_par');
    }

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'projet_id');
    }

    public function transactionParent()
    {
        return $this->belongsTo(Fonds::class, 'transaction_parent_id');
    }

    public function transactionsEnfants()
    {
        return $this->hasMany(Fonds::class, 'transaction_parent_id');
    }

    // Scopes
    public function scopeValidees($query)
    {
        return $query->where('statut', 'validee');
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeAnnulees($query)
    {
        return $query->where('statut', 'annulee');
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type_transaction', $type);
    }

    public function scopeParCulte($query, $culteId)
    {
        return $query->where('culte_id', $culteId);
    }

    public function scopeParDonateur($query, $donateurId)
    {
        return $query->where('donateur_id', $donateurId);
    }

    public function scopeParPeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_transaction', [$dateDebut, $dateFin]);
    }

    public function scopeParMois($query, $annee, $mois)
    {
        return $query->whereYear('date_transaction', $annee)
                    ->whereMonth('date_transaction', $mois);
    }

    public function scopeDimes($query)
    {
        return $query->where('type_transaction', 'dime');
    }

    public function scopeOffrandes($query)
    {
        return $query->whereIn('type_transaction', [
            'offrande_libre',
            'offrande_speciale',
            'offrande_mission',
            'offrande_construction'
        ]);
    }

    public function scopeDons($query)
    {
        return $query->whereIn('type_transaction', [
            'don_special',
            'don_materiel',
            'soutien_pasteur'
        ]);
    }

    public function scopeEspeces($query)
    {
        return $query->where('mode_paiement', 'especes');
    }

    public function scopeMobileMoney($query)
    {
        return $query->where('mode_paiement', 'mobile_money');
    }

    public function scopeRecurrentes($query)
    {
        return $query->where('est_recurrente', true);
    }

    public function scopeFlechees($query)
    {
        return $query->where('est_flechee', true);
    }

    public function scopeAnonymes($query)
    {
        return $query->where('est_anonyme', true);
    }

    public function scopeAvecRecu($query)
    {
        return $query->where('recu_demande', true);
    }

    public function scopeRecusEmis($query)
    {
        return $query->where('recu_emis', true);
    }

    // Mutateurs
    public function setNumeroTransactionAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['numero_transaction'] = $this->genererNumeroTransaction();
        } else {
            $this->attributes['numero_transaction'] = strtoupper($value);
        }
    }

    public function setNumeroRecuAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['numero_recu'] = strtoupper($value);
        }
    }

    public function setMontantAttribute($value)
    {
        $this->attributes['montant'] = abs(floatval($value));
    }

    // Accesseurs
    public function getNomDonateurAttribute()
    {
        if ($this->est_anonyme) {
            return 'Donateur anonyme';
        }

        if ($this->donateur) {
            return $this->donateur->prenom . ' ' . $this->donateur->nom;
        }

        return $this->nom_donateur_anonyme ?? 'Donateur inconnu';
    }

    public function getContactDonateurCompletAttribute()
    {
        if ($this->donateur) {
            return $this->donateur->email ?? $this->donateur->telephone_1;
        }

        return $this->contact_donateur;
    }

    public function getMontantFormatAttribute()
    {
        return number_format($this->montant, 0, ',', ' ') . ' ' . $this->devise;
    }

    public function getStatutLibelleAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'validee' => 'Validée',
            'annulee' => 'Annulée',
            'remboursee' => 'Remboursée',
            default => ucfirst($this->statut)
        };
    }

    public function getTypeTransactionLibelleAttribute()
    {
        return match($this->type_transaction) {
            'dime' => 'Dîme',
            'offrande_libre' => 'Offrande libre',
            'offrande_speciale' => 'Offrande spéciale',
            'offrande_mission' => 'Offrande mission',
            'offrande_construction' => 'Offrande construction',
            'don_special' => 'Don spécial',
            'soutien_pasteur' => 'Soutien pasteur',
            'frais_ceremonie' => 'Frais cérémonie',
            'don_materiel' => 'Don matériel',
            default => ucfirst(str_replace('_', ' ', $this->type_transaction))
        };
    }

    public function getModePaiementLibelleAttribute()
    {
        return match($this->mode_paiement) {
            'especes' => 'Espèces',
            'mobile_money' => 'Mobile Money',
            'virement' => 'Virement bancaire',
            'cheque' => 'Chèque',
            'nature' => 'Don en nature',
            default => ucfirst($this->mode_paiement)
        };
    }

    public function getEstDonNatureAttribute()
    {
        return $this->type_transaction === 'don_materiel';
    }

    public function getJoursDepuisTransactionAttribute()
    {
        return $this->date_transaction->diffInDays(now());
    }

    public function getJoursDepuisValidationAttribute()
    {
        if (!$this->validee_le) {
            return null;
        }

        return $this->validee_le->diffInDays(now());
    }

    // Méthodes métier
    public function peutEtreValidee(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function peutEtreAnnulee(): bool
    {
    return in_array($this->statut, ['en_attente' /*, 'validee'*/]);
    }

    public function peutEtreModifiee(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function peutGenererRecu(): bool
    {
        return $this->statut === 'validee' && $this->recu_demande /*&& !$this->recu_emis*/ && $this->deductible_impots;
    }

    public function valider($validateurId = null, $notes = null): bool
    {
        if (!$this->peutEtreValidee()) {
            return false;
        }

        $this->update([
            'statut' => 'validee',
            'validateur_id' => $validateurId ?? auth()->id(),
            'validee_le' => now(),
            'notes_validation' => $notes,
        ]);

        // Générer prochaine échéance si récurrente
        if ($this->est_recurrente) {
            $this->genererProchaineEcheance();
        }

        return true;
    }

    public function annuler($motif, $annuleurId = null): bool
    {
        if (!$this->peutEtreAnnulee()) {
            return false;
        }

        return $this->update([
            'statut' => 'annulee',
            'motif_annulation' => $motif,
            'validateur_id' => $annuleurId ?? auth()->id(),
            'validee_le' => now(),
        ]);
    }

    public function rembourser($motif, $rembourseurId = null): bool
    {
        if ($this->statut !== 'validee') {
            return false;
        }

        return $this->update([
            'statut' => 'remboursee',
            'motif_annulation' => $motif,
            'modifie_par' => $rembourseurId ?? auth()->id(),
        ]);
    }

    public function genererRecu(): string|null
    {
        if (!$this->peutGenererRecu()) {
            return null;
        }

        $numeroRecu = $this->genererNumeroRecu();

        $this->update([
            'numero_recu' => $numeroRecu,
            'recu_emis' => true,
            'date_emission_recu' => now()->toDateString(),
        ]);

        return $numeroRecu;
    }

    public function marquerCommeVerifiee($verificateurId = null, $notes = null): bool
    {
        return $this->update([
            'derniere_verification' => now(),
            'verifie_par' => $verificateurId ?? auth()->id(),
            'notes_comptable' => $notes,
        ]);
    }

    public function dupliquer($nouvelleDateTransaction = null): self
    {
        $nouvelleTransaction = $this->replicate();

        // Réinitialiser certains champs
        $nouvelleTransaction->numero_transaction = null;
        $nouvelleTransaction->date_transaction = $nouvelleDateTransaction ?? now()->toDateString();
        $nouvelleTransaction->statut = 'en_attente';
        $nouvelleTransaction->validateur_id = null;
        $nouvelleTransaction->validee_le = null;
        $nouvelleTransaction->numero_recu = null;
        $nouvelleTransaction->recu_emis = false;
        $nouvelleTransaction->date_emission_recu = null;
        $nouvelleTransaction->fichier_recu = null;
        $nouvelleTransaction->transaction_parent_id = $this->id;

        $nouvelleTransaction->save();

        return $nouvelleTransaction;
    }

    // Méthodes utilitaires privées
    private function genererNumeroTransaction(): string
    {
        $prefix = 'TR';
        $date = now()->format('Ymd');
        $sequence = str_pad(
            (Fonds::whereDate('created_at', now())->count() + 1),
            4,
            '0',
            STR_PAD_LEFT
        );

        return $prefix . $date . $sequence;
    }

    private function genererNumeroRecu(): string
    {
        $prefix = 'REC';
        $annee = now()->year;
        $sequence = str_pad(
            (Fonds::where('recu_emis', true)->whereYear('date_emission_recu', $annee)->count() + 1),
            5,
            '0',
            STR_PAD_LEFT
        );

        return $prefix . $annee . $sequence;
    }

    private function genererProchaineEcheance(): void
    {
        if (!$this->est_recurrente || !$this->frequence_recurrence) {
            return;
        }

        $prochaine = match($this->frequence_recurrence) {
            'hebdomadaire' => $this->date_transaction->addWeek(),
            'mensuelle' => $this->date_transaction->addMonth(),
            'trimestrielle' => $this->date_transaction->addMonths(3),
            'annuelle' => $this->date_transaction->addYear(),
            default => null
        };

        if ($prochaine) {
            $this->update(['prochaine_echeance' => $prochaine]);
        }
    }

    // Événements du modèle
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($fonds) {
            $fonds->cree_par = auth()->id();

            if (empty($fonds->numero_transaction)) {
                $fonds->numero_transaction = $fonds->genererNumeroTransaction();
            }
        });

        static::updating(function ($fonds) {
            $fonds->modifie_par = auth()->id();
        });
    }

    // Validation personnalisée
    public function validate(): array
    {
        $errors = [];

        // Validation montant
        if ($this->montant <= 0) {
            $errors[] = "Le montant doit être positif.";
        }

        // Validation don en nature
        if ($this->type_transaction === 'don_materiel' && empty($this->description_don_nature)) {
            $errors[] = "La description est obligatoire pour un don matériel.";
        }

        // Validation récurrence
        if ($this->est_recurrente && empty($this->frequence_recurrence)) {
            $errors[] = "La fréquence de récurrence est obligatoire.";
        }

        // Validation donateur
        if (!$this->est_anonyme && !$this->donateur_id && empty($this->nom_donateur_anonyme)) {
            $errors[] = "Le donateur doit être identifié.";
        }

        // Validation reçu
        if ($this->recu_emis && (empty($this->numero_recu) || !$this->date_emission_recu)) {
            $errors[] = "Le numéro et la date d'émission sont obligatoires pour un reçu émis.";
        }

        return $errors;
    }

    // Méthodes statiques
    public static function totalParPeriode($dateDebut, $dateFin, $typeTransaction = null): float
    {
        $query = static::validees()->whereBetween('date_transaction', [$dateDebut, $dateFin]);

        if ($typeTransaction) {
            $query->where('type_transaction', $typeTransaction);
        }

        return $query->sum('montant');
    }

    public static function statistiquesParType($dateDebut = null, $dateFin = null): \Illuminate\Support\Collection
    {
        $query = static::validees();

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date_transaction', [$dateDebut, $dateFin]);
        }

        return $query->selectRaw('type_transaction, COUNT(*) as nombre, SUM(montant) as total, AVG(montant) as moyenne')
                    ->groupBy('type_transaction')
                    ->orderBy('total', 'desc')
                    ->get();
    }

    public static function transactionsEcheancesArrivees(): \Illuminate\Database\Eloquent\Collection
    {
        return static::where('est_recurrente', true)
                    ->where('prochaine_echeance', '<=', now())
                    ->validees()
                    ->get();
    }
}
