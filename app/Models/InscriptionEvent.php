<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InscriptionEvent extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'inscription_events';

    protected $fillable = [
        'inscrit_id',
        'event_id',
        'cree_par',
        'cree_le',
        'modifie_par',
        'supprimer_par',
        'annule_par',
        'annule_le',
    ];

    protected $casts = [
        'cree_le' => 'datetime',
        'annule_le' => 'datetime',
    ];

    // Relations
    public function inscrit()
    {
        return $this->belongsTo(User::class, 'inscrit_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function modificateur()
    {
        return $this->belongsTo(User::class, 'modifie_par');
    }

    public function suppresseur()
    {
        return $this->belongsTo(User::class, 'supprimer_par');
    }

    public function annulateur()
    {
        return $this->belongsTo(User::class, 'annule_par');
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->whereNull('annule_le')
                    ->whereNull('deleted_at');
    }

    public function scopeAnnulees($query)
    {
        return $query->whereNotNull('annule_le');
    }

    public function scopeSupprimees($query)
    {
        return $query->onlyTrashed();
    }

    public function scopeParEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeParInscrit($query, $inscritId)
    {
        return $query->where('inscrit_id', $inscritId);
    }

    public function scopeParCreateur($query, $createurId)
    {
        return $query->where('cree_par', $createurId);
    }

    public function scopeRecentes($query, $jours = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($jours));
    }

    public function scopeInscriptionsAdministratives($query)
    {
        return $query->whereColumn('cree_par', '!=', 'inscrit_id')
                    ->whereNotNull('cree_par');
    }

    public function scopeAutoInscriptions($query)
    {
        return $query->whereColumn('cree_par', '=', 'inscrit_id')
                    ->orWhereNull('cree_par');
    }

    // Accesseurs
    public function getStatutAttribute()
    {
        if ($this->trashed()) {
            return 'supprimee';
        }

        if ($this->annule_le) {
            return 'annulee';
        }

        // Vérifier si l'événement est passé
        if ($this->event && $this->event->date_debut < now()) {
            return 'evenement_passe';
        }

        return 'active';
    }

    public function getEstActiveAttribute()
    {
        return $this->statut === 'active';
    }

    public function getEstAnnuleeAttribute()
    {
        return $this->statut === 'annulee';
    }

    public function getEstSupprimeeAttribute()
    {
        return $this->statut === 'supprimee';
    }

    public function getEstInscriptionAdministrativeAttribute()
    {
        return $this->cree_par && $this->cree_par !== $this->inscrit_id;
    }

    public function getEstAutoInscriptionAttribute()
    {
        return !$this->cree_par || $this->cree_par === $this->inscrit_id;
    }

    public function getDateInscriptionAttribute()
    {
        return $this->cree_le ?? $this->created_at;
    }

    public function getTempsDepuisInscriptionAttribute()
    {
        $dateInscription = $this->date_inscription;

        if (!$dateInscription) {
            return null;
        }

        return $dateInscription->diffForHumans();
    }

    public function getTempsDepuisAnnulationAttribute()
    {
        if (!$this->annule_le) {
            return null;
        }

        return $this->annule_le->diffForHumans();
    }

    // Méthodes utilitaires
    public function peutEtreAnnulee(): bool
    {
        return $this->est_active && $this->event && $this->event->date_debut > now();
    }

    public function peutEtreReactivee(): bool
    {
        return $this->est_annulee
               && $this->event
               && $this->event->accepteInscriptions();
    }

    public function annuler($motif = null, $annulePar = null): bool
    {
        if (!$this->peutEtreAnnulee()) {
            return false;
        }

        $this->update([
            'annule_par' => $annulePar ?? auth()->id(),
            'annule_le' => now(),
        ]);

        // Décrémenter le nombre d'inscrits de l'événement
        if ($this->event) {
            $this->event->decrement('nombre_inscrits');
        }

        return true;
    }

    public function reactiver($reactivePar = null): bool
    {
        if (!$this->peutEtreReactivee()) {
            return false;
        }

        $this->update([
            'annule_par' => null,
            'annule_le' => null,
            'modifie_par' => $reactivePar ?? auth()->id(),
        ]);

        // Incrémenter le nombre d'inscrits de l'événement
        if ($this->event) {
            $this->event->increment('nombre_inscrits');
        }

        return true;
    }

    public function marquerSupprimePar($supprimePar = null)
    {
        $this->update([
            'supprimer_par' => $supprimePar ?? auth()->id()
        ]);

        return $this->delete();
    }

    public function estInscritParDefaut(): bool
    {
        return !$this->cree_par;
    }

    public function getInformationsAudit(): array
    {
        return [
            'date_creation' => $this->date_inscription,
            'createur' => $this->createur?->nom_complet ?? 'Auto-inscription',
            'date_modification' => $this->updated_at,
            'modificateur' => $this->modificateur?->nom_complet ?? null,
            'date_annulation' => $this->annule_le,
            'annulateur' => $this->annulateur?->nom_complet ?? null,
            'date_suppression' => $this->deleted_at,
            'suppresseur' => $this->suppresseur?->nom_complet ?? null,
            'statut' => $this->statut,
        ];
    }

    // Événements du modèle
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inscription) {
            // Si pas de créateur spécifié, c'est une auto-inscription
            if (!$inscription->cree_par) {
                $inscription->cree_par = $inscription->inscrit_id;
            }

            // Définir la date de création si pas spécifiée
            if (!$inscription->cree_le) {
                $inscription->cree_le = now();
            }

            // Incrémenter le compteur d'inscrits de l'événement
            if ($inscription->event) {
                $inscription->event->increment('nombre_inscrits');
            }
        });

        static::updating(function ($inscription) {
            $inscription->modifie_par = auth()->id();
        });

        static::deleting(function ($inscription) {
            // Si suppression définitive, décrémenter le compteur
            if ($inscription->isForceDeleting()) {
                if ($inscription->event && $inscription->est_active) {
                    $inscription->event->decrement('nombre_inscrits');
                }
            } else {
                // Soft delete - marquer qui a supprimé si pas déjà fait
                if (!$inscription->supprimer_par) {
                    $inscription->supprimer_par = auth()->id();
                    $inscription->save();
                }

                // Décrémenter si l'inscription était active
                if ($inscription->event && $inscription->est_active) {
                    $inscription->event->decrement('nombre_inscrits');
                }
            }
        });

        static::restoring(function ($inscription) {
            // Lors de la restauration, incrémenter le compteur si l'inscription était active
            if ($inscription->event && $inscription->est_active) {
                $inscription->event->increment('nombre_inscrits');
            }
        });
    }

    // Validation personnalisée
    public function validate(): array
    {
        $errors = [];

        // Vérifier que l'utilisateur n'est pas déjà inscrit à cet événement
        $existingInscription = self::actives()
            ->where('inscrit_id', $this->inscrit_id)
            ->where('event_id', $this->event_id)
            ->where('id', '!=', $this->id)
            ->first();

        if ($existingInscription) {
            $errors[] = "L'utilisateur est déjà inscrit à cet événement.";
        }

        // Vérifier la capacité de l'événement
        if ($this->event && $this->event->capacite_totale) {
            $inscriptionsActives = self::actives()
                ->where('event_id', $this->event_id)
                ->where('id', '!=', $this->id)
                ->count();

            if ($inscriptionsActives >= $this->event->capacite_totale && !$this->event->liste_attente) {
                $errors[] = "L'événement a atteint sa capacité maximale.";
            }
        }

        // Vérifier les dates d'inscription
        if ($this->event) {
            if ($this->event->date_fermeture_inscription && $this->event->date_fermeture_inscription < now()) {
                $errors[] = "Les inscriptions sont fermées pour cet événement.";
            }

            if ($this->event->date_ouverture_inscription && $this->event->date_ouverture_inscription > now()) {
                $errors[] = "Les inscriptions ne sont pas encore ouvertes pour cet événement.";
            }
        }

        return $errors;
    }
}
