<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\HasCKEditorFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Annonce extends Model
{
    use HasFactory, SoftDeletes, HasUuids, HasCKEditorFields;

    protected $fillable = [
        'titre',
        'contenu',
        'type_annonce',
        'niveau_priorite',
        'audience_cible',
        'publie_le',
        'expire_le',
        'date_evenement',
        'afficher_site_web',
        'annoncer_culte',
        'contact_principal_id',
        'lieu_evenement',
        'statut',
        'cree_par',
    ];

    protected $casts = [
        'publie_le' => 'datetime',
        'expire_le' => 'datetime',
        'date_evenement' => 'date',
        'afficher_site_web' => 'boolean',
        'annoncer_culte' => 'boolean',
    ];

    protected $attributes = [
        'statut' => 'brouillon',
        'niveau_priorite' => 'normal',
        'audience_cible' => 'tous',
        'afficher_site_web' => true,
        'annoncer_culte' => false,
    ];

    // Relations
    public function contactPrincipal(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contact_principal_id');
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    // Scopes
    public function scopeActives($query)
    {
        return $query->where('statut', 'publiee')
                    ->where(function($q) {
                        $q->whereNull('expire_le')
                          ->orWhere('expire_le', '>', now());
                    });
    }

    public function scopeParType($query, string $type)
    {
        return $query->where('type_annonce', $type);
    }

    public function scopeParAudience($query, string $audience)
    {
        return $query->where('audience_cible', $audience);
    }

    public function scopeUrgentes($query)
    {
        return $query->where('niveau_priorite', 'urgent');
    }

    public function scopePourCulte($query)
    {
        return $query->where('annoncer_culte', true);
    }

    public function scopePourSiteWeb($query)
    {
        return $query->where('afficher_site_web', true);
    }

    public function scopeTrieesParPriorite($query)
    {
        return $query->orderByRaw("
            CASE niveau_priorite
                WHEN 'urgent' THEN 1
                WHEN 'important' THEN 2
                ELSE 3
            END
        ")->orderBy('publie_le', 'desc');
    }

    // Mutateurs
    public function setTitreAttribute($value)
    {
        $this->attributes['titre'] = ucfirst(trim($value));
    }

    // Accesseurs
    public function getEstActiveAttribute(): bool
    {
        return $this->statut === 'publiee' &&
               ($this->expire_le === null || $this->expire_le->isFuture());
    }

    public function getEstExpireAttribute(): bool
    {
        return $this->expire_le !== null && $this->expire_le->isPast();
    }

    public function getJoursRestantsAttribute(): ?int
    {
        if ($this->expire_le === null) {
            return null;
        }

        return max(0, now()->diffInDays($this->expire_le, false));
    }

    public function getBadgePrioriteAttribute(): string
    {
        return match($this->niveau_priorite) {
            'urgent' => 'bg-red-100 text-red-800',
            'important' => 'bg-yellow-100 text-yellow-800',
            'normal' => 'bg-gray-100 text-gray-800',
        };
    }

    public function getBadgeStatutAttribute(): string
    {
        return match($this->statut) {
            'publiee' => 'bg-green-100 text-green-800',
            'brouillon' => 'bg-gray-100 text-gray-800',
            'expiree' => 'bg-red-100 text-red-800',
        };
    }

    // Méthodes utilitaires
    public function publier(): bool
    {
        if ($this->statut === 'brouillon') {
            $this->update([
                'statut' => 'publiee',
                'publie_le' => now()
            ]);
            return true;
        }
        return false;
    }

    public function archiver(): bool
    {
        if ($this->statut === 'publiee') {
            $this->update(['statut' => 'expiree']);
            return true;
        }
        return false;
    }

    public function dupliquer(): self
    {
        $nouvelleAnnonce = $this->replicate();
        $nouvelleAnnonce->titre = $this->titre . ' (Copie)';
        $nouvelleAnnonce->statut = 'brouillon';
        $nouvelleAnnonce->publie_le = null;
        $nouvelleAnnonce->save();

        return $nouvelleAnnonce;
    }

    public static function getTypesAnnonces(): array
    {
        return [
            'evenement' => 'Événement',
            'administrative' => 'Administrative',
            'pastorale' => 'Pastorale',
            'urgence' => 'Urgence',
            'information' => 'Information'
        ];
    }

    public static function getNiveauxPriorite(): array
    {
        return [
            'normal' => 'Normal',
            'important' => 'Important',
            'urgent' => 'Urgent'
        ];
    }

    public static function getAudiencesCibles(): array
    {
        return [
            'tous' => 'Tous',
            'membres' => 'Membres',
            'leadership' => 'Leadership',
            'jeunes' => 'Jeunes'
        ];
    }

    public static function getStatuts(): array
    {
        return [
            'brouillon' => 'Brouillon',
            'publiee' => 'Publiée',
            'expiree' => 'Expirée'
        ];
    }


}
