<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Helpers\CKEditorHelper;

class CKEditorCacheService
{
    private $cacheEnabled;
    private $cacheTtl;
    private $keyPrefix;

    public function __construct()
    {
        $this->cacheEnabled = config('ckeditor.cache.enabled', true);
        $this->cacheTtl = config('ckeditor.cache.ttl', 3600);
        $this->keyPrefix = config('ckeditor.cache.key_prefix', 'ckeditor_');
    }

    /**
     * Met en cache le contenu formaté
     */
    public function cacheFormattedContent(string $key, string $content, array $options = []): string
    {
        if (!$this->cacheEnabled) {
            return CKEditorHelper::formatForDisplay($content, $options);
        }

        $cacheKey = $this->generateCacheKey('formatted', $key, $options);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($content, $options) {
            return CKEditorHelper::formatForDisplay($content, $options);
        });
    }

    /**
     * Met en cache le texte brut
     */
    public function cachePlainText(string $key, string $content, ?int $maxLength = null): string
    {
        if (!$this->cacheEnabled) {
            return CKEditorHelper::toPlainText($content, $maxLength);
        }

        $cacheKey = $this->generateCacheKey('plain', $key, ['max_length' => $maxLength]);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($content, $maxLength) {
            return CKEditorHelper::toPlainText($content, $maxLength);
        });
    }

    /**
     * Met en cache les métadonnées du contenu
     */
    public function cacheContentMeta(string $key, string $content): array
    {
        if (!$this->cacheEnabled) {
            return CKEditorHelper::toApiFormat($content);
        }

        $cacheKey = $this->generateCacheKey('meta', $key);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($content) {
            return CKEditorHelper::toApiFormat($content);
        });
    }

    /**
     * Invalide le cache pour une clé donnée
     */
    public function invalidateCache(string $key): void
    {
        $patterns = ['formatted_', 'plain_', 'meta_'];

        foreach ($patterns as $pattern) {
            $cacheKey = $this->keyPrefix . $pattern . $key;
            Cache::forget($cacheKey);
        }
    }

    /**
     * Nettoie tout le cache CKEditor
     */
    public function clearAllCache(): void
    {
        if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
            Cache::tags(['ckeditor'])->flush();
        } else {
            // Fallback pour les stores non-taggables
            Log::warning('Cache store does not support tags, manual cache cleanup may be required');
        }
    }

    /**
     * Génère une clé de cache
     */
    private function generateCacheKey(string $type, string $key, array $options = []): string
    {
        $optionsHash = empty($options) ? '' : '_' . md5(serialize($options));
        return $this->keyPrefix . $type . '_' . $key . $optionsHash;
    }

    /**
     * Statistiques du cache
     */
    public function getCacheStats(): array
    {
        // Cette méthode dépend du driver de cache utilisé
        // Implémentation basique pour Redis/Memcached
        return [
            'enabled' => $this->cacheEnabled,
            'ttl' => $this->cacheTtl,
            'prefix' => $this->keyPrefix,
            'driver' => config('cache.default'),
        ];
    }
}
