<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\HasCKEditorFields;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Multimedia extends Model
{
    use HasFactory, HasUuids, SoftDeletes, HasCKEditorFields;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'culte_id',
        'event_id',
        'intervention_id',
        'reunion_id',
        'titre',
        'description',
        'legende',
        'tags',
        'type_media',
        'categorie',
        'nom_fichier_original',
        'nom_fichier_stockage',
        'chemin_fichier',
        'url_publique',
        'miniature',
        'type_mime',
        'extension',
        'taille_fichier',
        'hash_fichier',
        'metadonnees_exif',
        'largeur',
        'hauteur',
        'orientation',
        'duree_secondes',
        'bitrate',
        'codec',
        'resolution',
        'fps',
        'date_prise',
        'lieu_prise',
        'photographe',
        'appareil',
        'parametres_capture',
        'licence',
        'usage_public',
        'usage_site_web',
        'usage_reseaux_sociaux',
        'usage_commercial',
        'restrictions_usage',
        'statut_moderation',
        'commentaire_moderation',
        'est_visible',
        'est_featured',
        'est_archive',
        'date_publication',
        'date_expiration',
        'niveau_acces',
        'necessite_connexion',
        'groupes_autorises',
        'service_stockage',
        'emplacements_backup',
        'backup_automatique',
        'alt_text',
        'titre_seo',
        'description_seo',
        'slug',
        'statut_traitement',
        'versions_disponibles',
        'generer_miniatures',
        'formats_convertis',
        'qualite',
        'note_qualite',
        'contenu_sensible',
        'avertissement',
        'telecharge_par',
        'cree_par',
        'modifie_par',
        'historique_modifications',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'id' => 'string',
        'culte_id' => 'string',
        'event_id' => 'string',
        'intervention_id' => 'string',
        'reunion_id' => 'string',
        'telecharge_par' => 'string',
        'cree_par' => 'string',
        'modifie_par' => 'string',
        'modere_par' => 'string',
        'tags' => 'array',
        'metadonnees_exif' => 'array',
        'parametres_capture' => 'array',
        'groupes_autorises' => 'array',
        'emplacements_backup' => 'array',
        'versions_disponibles' => 'array',
        'formats_convertis' => 'array',
        'historique_modifications' => 'array',
        'taille_fichier' => 'integer',
        'largeur' => 'integer',
        'hauteur' => 'integer',
        'duree_secondes' => 'integer',
        'bitrate' => 'integer',
        'fps' => 'integer',
        'nombre_vues' => 'integer',
        'nombre_telechargements' => 'integer',
        'nombre_partages' => 'integer',
        'nombre_likes' => 'integer',
        'nombre_commentaires' => 'integer',
        'note_qualite' => 'decimal:1',
        'usage_public' => 'boolean',
        'usage_site_web' => 'boolean',
        'usage_reseaux_sociaux' => 'boolean',
        'usage_commercial' => 'boolean',
        'est_visible' => 'boolean',
        'est_featured' => 'boolean',
        'est_archive' => 'boolean',
        'necessite_connexion' => 'boolean',
        'backup_automatique' => 'boolean',
        'generer_miniatures' => 'boolean',
        'contenu_sensible' => 'boolean',
        'date_prise' => 'datetime',
        'date_publication' => 'date',
        'date_expiration' => 'date',
        'modere_le' => 'datetime',
        'derniere_vue' => 'datetime',
        'derniere_sauvegarde' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Les types de média disponibles
     */
    public const TYPES_MEDIA = [
        'image' => 'Image',
        'video' => 'Vidéo',
        'audio' => 'Audio',
        'document' => 'Document',
        'presentation' => 'Présentation',
        'archive' => 'Archive',
        'livestream' => 'Diffusion en direct',
        'podcast' => 'Podcast'
    ];

    /**
     * Les catégories disponibles
     */
    public const CATEGORIES = [
        'photos_culte' => 'Photos de culte',
        'photos_evenement' => 'Photos d\'événement',
        'enregistrement_audio' => 'Enregistrement audio',
        'enregistrement_video' => 'Enregistrement vidéo',
        'temoignage' => 'Témoignage',
        'predication' => 'Prédication',
        'louange' => 'Louange/musique',
        'formation' => 'Formation/enseignement',
        'ceremonie' => 'Cérémonie',
        'activite_jeunes' => 'Activités des jeunes',
        'activite_enfants' => 'Activités des enfants',
        'evenement_special' => 'Événement spécial',
        'archive_historique' => 'Archive historique',
        'documentaire' => 'Documentaire',
        'interview' => 'Interview',
        'reportage' => 'Reportage',
        'autre' => 'Autre'
    ];

    /**
     * Les niveaux d'accès disponibles
     */
    public const NIVEAUX_ACCES = [
        'public' => 'Public',
        'membres' => 'Membres uniquement',
        'leadership' => 'Leadership uniquement',
        'administrateurs' => 'Administrateurs uniquement',
        'prive' => 'Privé'
    ];

    /**
     * Les statuts de modération
     */
    public const STATUTS_MODERATION = [
        'en_attente' => 'En attente',
        'approuve' => 'Approuvé',
        'rejete' => 'Rejeté',
        'revision_requise' => 'Révision requise',
        'archive' => 'Archivé'
    ];

    /**
     * Les niveaux de qualité
     */
    public const QUALITES = [
        'basse' => 'Basse qualité',
        'standard' => 'Qualité standard',
        'haute' => 'Haute qualité',
        'premium' => 'Qualité premium',
        'raw' => 'Format RAW/original'
    ];

    /**
     * Relation avec le culte
     */
    public function culte(): BelongsTo
    {
        return $this->belongsTo(Culte::class, 'culte_id');
    }

    /**
     * Relation avec l'événement
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Relation avec l'intervention
     */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class, 'intervention_id');
    }

    /**
     * Relation avec la réunion
     */
    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class, 'reunion_id');
    }

    /**
     * Relation avec l'utilisateur qui a téléchargé
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'telecharge_par');
    }

    /**
     * Relation avec le créateur
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    /**
     * Relation avec le modérateur
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'modere_par');
    }

    /**
     * Scope pour les médias visibles
     */
    public function scopeVisible($query)
    {
        return $query->where('est_visible', true);
    }

    /**
     * Scope pour les médias approuvés
     */
    public function scopeApprouve($query)
    {
        return $query->where('statut_moderation', 'approuve');
    }

    /**
     * Scope pour les médias publics
     */
    public function scopePublic($query)
    {
        return $query->where('niveau_acces', 'public')
                    ->where('usage_public', true);
    }

    /**
     * Scope pour les médias en attente de modération
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut_moderation', 'en_attente');
    }

    /**
     * Scope pour les médias featured
     */
    public function scopeFeatured($query)
    {
        return $query->where('est_featured', true);
    }

    /**
     * Scope pour filtrer par type de média
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type_media', $type);
    }

    /**
     * Scope pour filtrer par catégorie
     */
    public function scopeOfCategory($query, $category)
    {
        return $query->where('categorie', $category);
    }

    /**
     * Scope pour les médias récents
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope pour les médias d'un événement spécifique
     */
    public function scopeForEvent($query, $eventType, $eventId)
    {
        $column = $eventType . '_id';
        return $query->where($column, $eventId);
    }

    /**
     * Scope pour ordonner par popularité
     */
    public function scopePopular($query)
    {
        return $query->orderBy('nombre_vues', 'desc')
                    ->orderBy('nombre_likes', 'desc');
    }

    /**
     * Accessor pour obtenir le libellé du type de média
     */
    public function getTypeMediaLabelAttribute()
    {
        return self::TYPES_MEDIA[$this->type_media] ?? $this->type_media;
    }

    /**
     * Accessor pour obtenir le libellé de la catégorie
     */
    public function getCategorieLabelAttribute()
    {
        return self::CATEGORIES[$this->categorie] ?? $this->categorie;
    }

    /**
     * Accessor pour obtenir le libellé du niveau d'accès
     */
    public function getNiveauAccesLabelAttribute()
    {
        return self::NIVEAUX_ACCES[$this->niveau_acces] ?? $this->niveau_acces;
    }

    /**
     * Accessor pour obtenir le libellé du statut de modération
     */
    public function getStatutModerationLabelAttribute()
    {
        return self::STATUTS_MODERATION[$this->statut_moderation] ?? $this->statut_moderation;
    }

    /**
     * Accessor pour obtenir la taille formatée du fichier
     */
    public function getTailleFormateeAttribute()
    {
        return $this->formatFileSize($this->taille_fichier);
    }

    /**
     * Accessor pour obtenir la durée formatée
     */
    public function getDureeFormateeAttribute()
    {
        if (!$this->duree_secondes) {
            return null;
        }

        $hours = floor($this->duree_secondes / 3600);
        $minutes = floor(($this->duree_secondes % 3600) / 60);
        $seconds = $this->duree_secondes % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    /**
     * Accessor pour vérifier si c'est une image
     */
    public function getEstImageAttribute()
    {
        return $this->type_media === 'image';
    }

    /**
     * Accessor pour vérifier si c'est une vidéo
     */
    public function getEstVideoAttribute()
    {
        return $this->type_media === 'video';
    }

    /**
     * Accessor pour vérifier si c'est un audio
     */
    public function getEstAudioAttribute()
    {
        return $this->type_media === 'audio';
    }

    /**
     * Accessor pour obtenir l'URL complète
     */
    public function getUrlCompleteAttribute()
    {
        if ($this->url_publique) {
            return $this->url_publique;
        }

        if ($this->service_stockage === 'local') {
            return Storage::url($this->chemin_fichier);
        }

        return $this->chemin_fichier;
    }

    /**
     * Accessor pour obtenir l'URL de la miniature
     */
    public function getUrlMiniatureAttribute()
    {
        if ($this->miniature) {
            if (Str::startsWith($this->miniature, ['http://', 'https://'])) {
                return $this->miniature;
            }
            return Storage::url($this->miniature);
        }

        return null;
    }

    /**
     * Accessor pour obtenir les dimensions formatées
     */
    public function getDimensionsFormateeAttribute()
    {
        if ($this->largeur && $this->hauteur) {
            return $this->largeur . ' × ' . $this->hauteur . ' px';
        }

        return null;
    }

    /**
     * Accessor pour obtenir l'événement parent
     */
    public function getEvenementParentAttribute()
    {
        return $this->culte ?? $this->event ?? $this->intervention ?? $this->reunion;
    }

    /**
     * Accessor pour obtenir le nom de l'événement parent
     */
    public function getNomEvenementParentAttribute()
    {
        $evenement = $this->evenement_parent;
        return $evenement ? $evenement->titre ?? $evenement->nom : null;
    }

    /**
     * Accessor pour obtenir le type d'événement parent
     */
    public function getTypeEvenementParentAttribute()
    {
        if ($this->culte_id) return 'culte';
        if ($this->event_id) return 'evenement';
        if ($this->intervention_id) return 'intervention';
        if ($this->reunion_id) return 'reunion';
        return null;
    }

    /**
     * Mutator pour générer automatiquement le slug
     */
    public function setTitreAttribute($value)
    {
        $this->attributes['titre'] = $value;

        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = $this->generateUniqueSlug($value);
        }
    }

    /**
     * Mutator pour nettoyer les tags
     */
    public function setTagsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['tags'] = json_encode(array_filter(array_map('trim', $value)));
        } else {
            $this->attributes['tags'] = $value;
        }
    }

    /**
     * Méthode pour vérifier si le média est public
     */
    public function estPublic(): bool
    {
        return $this->niveau_acces === 'public' &&
               $this->usage_public &&
               $this->est_visible &&
               $this->statut_moderation === 'approuve';
    }

    /**
     * Méthode pour vérifier si le média est accessible par un utilisateur
     */
    public function estAccessiblePar(?User $user = null): bool
    {
        
        if (!$this->est_visible || $this->statut_moderation !== 'approuve') {
            return false;
        }

        if ($this->niveau_acces === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        switch ($this->niveau_acces) {
            case 'membres':
                return true; // L'utilisateur est connecté donc membre
            case 'leadership':
                return $user->hasRole(['leadership', 'pasteur', 'administrateur']);
            case 'administrateurs':
                return $user->hasRole('administrateur');
            case 'prive':
                return $user->id === $this->telecharge_par || $user->id === $this->cree_par;
            default:
                return false;
        }
    }

    /**
     * Méthode pour incrémenter les vues
     */
    public function incrementerVues(): void
    {
        DB::statement('SELECT increment_media_stats(?, ?)', [$this->id, 'vue']);
        $this->refresh();
    }

    /**
     * Méthode pour incrémenter les téléchargements
     */
    public function incrementerTelechargements(): void
    {
        DB::statement('SELECT increment_media_stats(?, ?)', [$this->id, 'telechargement']);
        $this->refresh();
    }

    /**
     * Méthode pour incrémenter les likes
     */
    public function incrementerLikes(): void
    {
        DB::statement('SELECT increment_media_stats(?, ?)', [$this->id, 'like']);
        $this->refresh();
    }

    /**
     * Méthode pour approuver le média
     */
    public function approuver(User $moderator, ?string $commentaire = null): void
    {
        $this->update([
            'statut_moderation' => 'approuve',
            'modere_par' => $moderator->id,
            'modere_le' => now(),
            'commentaire_moderation' => $commentaire,
            'est_visible' => true
        ]);
    }

    /**
     * Méthode pour rejeter le média
     */
    public function rejeter(User $moderator, string $raison): void
    {
        $this->update([
            'statut_moderation' => 'rejete',
            'modere_par' => $moderator->id,
            'modere_le' => now(),
            'commentaire_moderation' => $raison,
            'est_visible' => false
        ]);
    }

    /**
     * Méthode pour archiver le média
     */
    public function archiver(): void
    {
        $this->update([
            'est_archive' => true,
            'est_visible' => false
        ]);
    }

    /**
     * Méthode pour mettre en featured
     */
    public function mettreEnFeatured(): void
    {
        $this->update(['est_featured' => true]);
    }

    /**
     * Méthode pour supprimer le fichier physique
     */
    public function supprimerFichier(): bool
    {
        if ($this->service_stockage === 'local') {
            $deleted = Storage::delete($this->chemin_fichier);

            if ($this->miniature) {
                Storage::delete($this->miniature);
            }

            return $deleted;
        }

        return true; // Pour les autres services, on assume que la suppression est gérée ailleurs
    }

    /**
 * Méthode pour générer un slug unique
 */
private function generateUniqueSlug(string $title): string
{
    $slug = Str::slug($title);
    $originalSlug = $slug;
    $counter = 1;

    while (true) {
        $query = self::where('slug', $slug);

        // Exclure l'enregistrement actuel s'il existe
        if ($this->exists) {
            $query->where('id', '!=', $this->id);
        }

        if (!$query->exists()) {
            break;
        }

        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}

    /**
     * Formater la taille du fichier
     */
    private function formatFileSize(int $bytes): string
    {
        $units = ['o', 'Ko', 'Mo', 'Go', 'To'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.2f %s", $bytes / pow(1024, $factor), $units[$factor]);
    }

    /**
     * Boot du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Validation avant sauvegarde
        static::saving(function ($multimedia) {
            // Au moins une relation doit être définie
            if (empty($multimedia->culte_id) &&
                empty($multimedia->event_id) &&
                empty($multimedia->intervention_id) &&
                empty($multimedia->reunion_id)) {
                throw new \InvalidArgumentException('Le média doit être associé à au moins un événement.');
            }

            // Validation du titre
            if (empty(trim($multimedia->titre))) {
                throw new \InvalidArgumentException('Le titre ne peut pas être vide.');
            }

            // Auto-génération de certains champs
            if (empty($multimedia->slug)) {
                $multimedia->slug = Str::slug($multimedia->titre);
            }

            if (empty($multimedia->hash_fichier) && !empty($multimedia->chemin_fichier)) {
                if (Storage::exists($multimedia->chemin_fichier)) {
                    $multimedia->hash_fichier = hash_file('sha256', Storage::path($multimedia->chemin_fichier));
                }
            }
        });

        // Supprimer le fichier physique lors de la suppression définitive
        static::forceDeleted(function ($multimedia) {
            $multimedia->supprimerFichier();
        });
    }
}
