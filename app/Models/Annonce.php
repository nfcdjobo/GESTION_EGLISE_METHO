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


    /**
 * Obtenir le contenu nettoyé pour affichage PDF
 *
 * @return string
 */
public function getContenuForPdf(): string
{
    if (empty($this->contenu)) {
        return '<p style="color: #9ca3af; font-style: italic;">Aucun contenu disponible</p>';
    }

    $content = $this->contenu;

    // Ajouter des styles inline pour les paragraphes
    $content = preg_replace('/<p>/i', '<p style="margin: 8px 0; line-height: 1.6;">', $content);

    // Ajouter des styles inline pour les titres
    $content = preg_replace('/<h1>/i', '<h1 style="font-size: 14px; font-weight: bold; color: #1f2937; margin: 12px 0 8px 0;">', $content);
    $content = preg_replace('/<h2>/i', '<h2 style="font-size: 12px; font-weight: bold; color: #374151; margin: 10px 0 6px 0;">', $content);
    $content = preg_replace('/<h3>/i', '<h3 style="font-size: 11px; font-weight: bold; color: #4b5563; margin: 8px 0 4px 0;">', $content);
    $content = preg_replace('/<h4>/i', '<h4 style="font-size: 10px; font-weight: bold; color: #6b7280; margin: 6px 0 3px 0;">', $content);

    // Ajouter des styles pour le texte en gras
    $content = preg_replace('/<strong>/i', '<strong style="font-weight: bold; color: #1f2937;">', $content);
    $content = preg_replace('/<b>/i', '<b style="font-weight: bold; color: #1f2937;">', $content);

    // Ajouter des styles pour le texte en italique
    $content = preg_replace('/<em>/i', '<em style="font-style: italic;">', $content);
    $content = preg_replace('/<i>/i', '<i style="font-style: italic;">', $content);

    // Gérer les listes avec styles
    $content = preg_replace('/<ul>/i', '<ul style="margin: 8px 0; padding-left: 20px; list-style-type: disc;">', $content);
    $content = preg_replace('/<ol>/i', '<ol style="margin: 8px 0; padding-left: 20px;">', $content);
    $content = preg_replace('/<li>/i', '<li style="margin: 3px 0; line-height: 1.5;">', $content);

    // Gérer les tableaux
    $content = preg_replace('/<table>/i', '<table style="width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 8px;">', $content);
    $content = preg_replace('/<td>/i', '<td style="border: 1px solid #d1d5db; padding: 4px;">', $content);
    $content = preg_replace('/<th>/i', '<th style="border: 1px solid #d1d5db; padding: 4px; background-color: #f3f4f6; font-weight: bold;">', $content);

    // Gérer les citations
    $content = preg_replace('/<blockquote>/i', '<blockquote style="border-left: 3px solid #3b82f6; padding-left: 12px; margin: 10px 0; font-style: italic; color: #4b5563;">', $content);

    // Gérer les liens
    $content = preg_replace('/<a /i', '<a style="color: #3b82f6; text-decoration: underline;" ', $content);

    // Nettoyer les sauts de ligne excessifs
    $content = preg_replace('/(<br\s*\/?>\s*){3,}/i', '<br><br>', $content);

    return $content;
}


/**
 * Obtenir un aperçu court du contenu (pour listes)
 *
 * @param int $limit
 * @return string
 */
public function getContenuPreview(int $limit = 200): string
{
    if (empty($this->contenu)) {
        return 'Aucun contenu';
    }

    // Supprimer toutes les balises HTML
    $content = strip_tags($this->contenu);

    // Décoder les entités HTML
    $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Nettoyer les espaces multiples
    $content = preg_replace('/\s+/', ' ', $content);

    // Trimmer et limiter
    $content = trim($content);

    return \Illuminate\Support\Str::limit($content, $limit);
}

}
