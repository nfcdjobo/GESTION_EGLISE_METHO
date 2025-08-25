<?php

namespace App\Helpers;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Str;

/**
 * Helper pour traiter le contenu CKEditor
 */
class CKEditorHelper
{
    /**
     * Nettoie le contenu HTML généré par CKEditor
     *
     * @param string|null $content
     * @param array $allowedTags
     * @return string
     */
    public static function cleanContent(?string $content, array $allowedTags = null): string
    {
        if (empty($content)) {
            return '';
        }

        // Tags autorisés par défaut pour CKEditor
        $defaultAllowedTags = [
            'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike',
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'ul', 'ol', 'li',
            'a', 'blockquote',
            'table', 'thead', 'tbody', 'tr', 'th', 'td',
            'span', 'div'
        ];

        $allowedTags = $allowedTags ?? $defaultAllowedTags;

        // Nettoyer avec strip_tags
        $allowedTagsString = '<' . implode('><', $allowedTags) . '>';
        $cleanContent = strip_tags($content, $allowedTagsString);

        // Nettoyer les attributs dangereux
        $cleanContent = preg_replace('/\son\w+\s*=\s*["\'][^"\']*["\']/i', '', $cleanContent);
        $cleanContent = preg_replace('/javascript:/i', '', $cleanContent);

        return trim($cleanContent);
    }

    /**
     * Convertit le contenu HTML en texte brut
     *
     * @param string|null $content
     * @param int $maxLength
     * @return string
     */
    public static function toPlainText(?string $content, int $maxLength = null): string
    {
        if (empty($content)) {
            return '';
        }

        // Remplacer les balises de paragraphe et de saut de ligne par des espaces
        $text = str_replace(['<p>', '</p>', '<br>', '<br/>', '<br />'], [' ', ' ', ' ', ' ', ' '], $content);

        // Supprimer toutes les balises HTML
        $text = strip_tags($text);

        // Nettoyer les espaces multiples
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // Limiter la longueur si spécifié
        if ($maxLength && strlen($text) > $maxLength) {
            $text = Str::limit($text, $maxLength);
        }

        return $text;
    }

    /**
     * Extrait les liens du contenu CKEditor
     *
     * @param string|null $content
     * @return array
     */
    public static function extractLinks(?string $content): array
    {
        if (empty($content)) {
            return [];
        }

        $links = [];

        // Utiliser DOMDocument pour extraire les liens
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new DOMXPath($dom);
        $linkNodes = $xpath->query('//a[@href]');

        foreach ($linkNodes as $link) {
            $href = $link->getAttribute('href');
            $text = trim($link->textContent);

            if (!empty($href)) {
                $links[] = [
                    'url' => $href,
                    'text' => $text,
                    'is_external' => !str_starts_with($href, config('app.url'))
                ];
            }
        }

        return $links;
    }

    /**
     * Compte les mots dans le contenu
     *
     * @param string|null $content
     * @return int
     */
    public static function wordCount(?string $content): int
    {
        if (empty($content)) {
            return 0;
        }

        $plainText = self::toPlainText($content);
        return str_word_count($plainText);
    }

    /**
     * Estime le temps de lecture
     *
     * @param string|null $content
     * @param int $wordsPerMinute
     * @return int Temps en minutes
     */
    public static function estimateReadingTime(?string $content, int $wordsPerMinute = 200): int
    {
        $wordCount = self::wordCount($content);
        return max(1, ceil($wordCount / $wordsPerMinute));
    }

    /**
     * Génère un résumé du contenu
     *
     * @param string|null $content
     * @param int $maxSentences
     * @return string
     */
    public static function generateSummary(?string $content, int $maxSentences = 2): string
    {
        if (empty($content)) {
            return '';
        }

        $plainText = self::toPlainText($content);

        // Diviser en phrases
        $sentences = preg_split('/[.!?]+/', $plainText, -1, PREG_SPLIT_NO_EMPTY);
        $sentences = array_map('trim', $sentences);

        // Prendre les premières phrases
        $summarySentences = array_slice($sentences, 0, $maxSentences);

        return implode('. ', $summarySentences) . (count($sentences) > $maxSentences ? '...' : '.');
    }

    /**
     * Valide si le contenu contient des éléments spécifiques
     *
     * @param string|null $content
     * @param array $requiredElements
     * @return array
     */
    public static function validateContent(?string $content, array $requiredElements = []): array
    {
        $validation = [
            'is_valid' => true,
            'errors' => [],
            'warnings' => []
        ];

        if (empty($content)) {
            if (in_array('content', $requiredElements)) {
                $validation['is_valid'] = false;
                $validation['errors'][] = 'Le contenu ne peut pas être vide.';
            }
            return $validation;
        }

        // Vérifier la longueur minimale
        $plainText = self::toPlainText($content);
        if (strlen($plainText) < 10) {
            $validation['warnings'][] = 'Le contenu semble très court.';
        }

        // Vérifier les éléments requis
        foreach ($requiredElements as $element) {
            switch ($element) {
                case 'links':
                    if (empty(self::extractLinks($content))) {
                        $validation['warnings'][] = 'Aucun lien trouvé dans le contenu.';
                    }
                    break;
                case 'headings':
                    if (!preg_match('/<h[1-6]/', $content)) {
                        $validation['warnings'][] = 'Aucun titre trouvé dans le contenu.';
                    }
                    break;
                case 'lists':
                    if (!preg_match('/<[ou]l>/', $content)) {
                        $validation['warnings'][] = 'Aucune liste trouvée dans le contenu.';
                    }
                    break;
            }
        }

        return $validation;
    }

    /**
     * Formate le contenu pour l'affichage
     *
     * @param string|null $content
     * @param array $options
     * @return string
     */
    public static function formatForDisplay(?string $content, array $options = []): string
    {
        if (empty($content)) {
            return '';
        }

        $defaultOptions = [
            'add_target_blank' => true,        // Ouvrir les liens externes dans un nouvel onglet
            'add_css_classes' => true,         // Ajouter des classes CSS
            'process_images' => false,         // Traiter les images (si utilisées)
            'add_nofollow' => false           // Ajouter rel="nofollow" aux liens externes
        ];

        $options = array_merge($defaultOptions, $options);

        // Nettoyer le contenu
        $formatted = self::cleanContent($content);

        // Ajouter target="_blank" aux liens externes
        if ($options['add_target_blank']) {
            $formatted = preg_replace_callback(
                '/<a\s+([^>]*?)href=(["\'])([^"\']*?)\2([^>]*?)>/i',
                function ($matches) use ($options) {
                    $href = $matches[3];
                    $attributes = $matches[1] . $matches[4];

                    // Vérifier si c'est un lien externe
                    if (!str_starts_with($href, config('app.url')) &&
                        (str_starts_with($href, 'http://') || str_starts_with($href, 'https://'))) {

                        // Ajouter target="_blank" s'il n'existe pas déjà
                        if (!str_contains($attributes, 'target=')) {
                            $attributes .= ' target="_blank"';
                        }

                        // Ajouter rel="noopener" pour la sécurité
                        if (!str_contains($attributes, 'rel=')) {
                            $rel = 'noopener';
                            if ($options['add_nofollow']) {
                                $rel .= ' nofollow';
                            }
                            $attributes .= ' rel="' . $rel . '"';
                        }
                    }

                    return '<a ' . trim($attributes) . ' href=' . $matches[2] . $href . $matches[2] . '>';
                },
                $formatted
            );
        }

        // Ajouter des classes CSS
        if ($options['add_css_classes']) {
            $formatted = str_replace(
                ['<blockquote>', '<table>', '<ul>', '<ol>'],
                [
                    '<blockquote class="border-l-4 border-blue-500 pl-4 italic text-slate-600">',
                    '<table class="min-w-full divide-y divide-gray-200 border border-gray-300 rounded-lg">',
                    '<ul class="list-disc list-inside space-y-1">',
                    '<ol class="list-decimal list-inside space-y-1">'
                ],
                $formatted
            );
        }

        return $formatted;
    }

    /**
     * Prépare le contenu pour l'édition
     *
     * @param string|null $content
     * @return string
     */
    public static function prepareForEditing(?string $content): string
    {
        if (empty($content)) {
            return '';
        }

        // Décoder les entités HTML
        $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');

        // Nettoyer les espaces en trop
        $content = preg_replace('/\s+/', ' ', $content);

        return trim($content);
    }

    /**
     * Convertit le contenu en JSON pour l'API
     *
     * @param string|null $content
     * @return array
     */
    public static function toApiFormat(?string $content): array
    {
        return [
            'html' => $content,
            'plain_text' => self::toPlainText($content),
            'word_count' => self::wordCount($content),
            'reading_time' => self::estimateReadingTime($content),
            'summary' => self::generateSummary($content),
            'links' => self::extractLinks($content),
            'has_formatting' => $content !== strip_tags($content)
        ];
    }
}
