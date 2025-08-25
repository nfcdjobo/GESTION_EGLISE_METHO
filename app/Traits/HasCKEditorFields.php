<?php

namespace App\Traits;

use App\Helpers\CKEditorHelper;
use Illuminate\Support\Str;

/**
 * Trait pour gérer les champs CKEditor dans les modèles
 */
trait HasCKEditorFields
{
    /**
     * Champs qui contiennent du contenu CKEditor
     * À définir dans le modèle qui utilise ce trait
     *
     * @var array
     */
    protected $ckeditorFields = [];

    /**
     * Nettoie automatiquement les champs CKEditor lors de la sauvegarde
     */
    public static function bootHasCKEditorFields()
    {
        static::saving(function ($model) {
            foreach ($model->getCKEditorFields() as $field) {
                if (isset($model->attributes[$field])) {
                    $model->attributes[$field] = CKEditorHelper::cleanContent($model->attributes[$field]);
                }
            }
        });
    }

    /**
     * Retourne la liste des champs CKEditor
     *
     * @return array
     */
    public function getCKEditorFields(): array
    {
        return $this->ckeditorFields ?? [];
    }

    /**
     * Retourne le contenu formaté pour l'affichage
     *
     * @param string $field
     * @param array $options
     * @return string
     */
    public function getFormattedContent(string $field, array $options = []): string
    {
        return CKEditorHelper::formatForDisplay($this->getAttribute($field), $options);
    }

    /**
     * Retourne le contenu en texte brut
     *
     * @param string $field
     * @param int|null $maxLength
     * @return string
     */
    public function getPlainTextContent(string $field, int $maxLength = null): string
    {
        return CKEditorHelper::toPlainText($this->getAttribute($field), $maxLength);
    }

    /**
     * Retourne un résumé du contenu
     *
     * @param string $field
     * @param int $maxSentences
     * @return string
     */
    public function getContentSummary(string $field, int $maxSentences = 2): string
    {
        return CKEditorHelper::generateSummary($this->getAttribute($field), $maxSentences);
    }

    /**
     * Compte les mots dans un champ
     *
     * @param string $field
     * @return int
     */
    public function getWordCount(string $field): int
    {
        return CKEditorHelper::wordCount($this->getAttribute($field));
    }

    /**
     * Estime le temps de lecture
     *
     * @param string $field
     * @param int $wordsPerMinute
     * @return int
     */
    public function getReadingTime(string $field, int $wordsPerMinute = 200): int
    {
        return CKEditorHelper::estimateReadingTime($this->getAttribute($field), $wordsPerMinute);
    }

    /**
     * Extrait les liens d'un champ
     *
     * @param string $field
     * @return array
     */
    public function getContentLinks(string $field): array
    {
        return CKEditorHelper::extractLinks($this->getAttribute($field));
    }

    /**
     * Valide le contenu d'un champ
     *
     * @param string $field
     * @param array $requiredElements
     * @return array
     */
    public function validateContentField(string $field, array $requiredElements = []): array
    {
        return CKEditorHelper::validateContent($this->getAttribute($field), $requiredElements);
    }

    /**
     * Prépare le contenu pour l'édition
     *
     * @param string $field
     * @return string
     */
    public function prepareForEditing(string $field): string
    {
        return CKEditorHelper::prepareForEditing($this->getAttribute($field));
    }

    /**
     * Convertit tous les champs CKEditor en format API
     *
     * @return array
     */
    public function getCKEditorFieldsAsApi(): array
    {
        $result = [];

        foreach ($this->getCKEditorFields() as $field) {
            $result[$field] = CKEditorHelper::toApiFormat($this->getAttribute($field));
        }

        return $result;
    }

    /**
     * Scope pour rechercher dans les champs CKEditor
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearchInCKEditorFields($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            foreach ($this->getCKEditorFields() as $field) {
                $q->orWhere($field, 'LIKE', "%{$search}%");
            }
        });
    }

    /**
     * Accessor pour obtenir un aperçu de tous les contenus
     *
     * @return array
     */
    public function getContentOverviewAttribute(): array
    {
        $overview = [];

        foreach ($this->getCKEditorFields() as $field) {
            $content = $this->getAttribute($field);
            if (!empty($content)) {
                $overview[$field] = [
                    'word_count' => $this->getWordCount($field),
                    'reading_time' => $this->getReadingTime($field),
                    'summary' => $this->getContentSummary($field, 1),
                    'has_links' => !empty($this->getContentLinks($field)),
                    'plain_text_preview' => Str::limit($this->getPlainTextContent($field), 100)
                ];
            }
        }

        return $overview;
    }
}

