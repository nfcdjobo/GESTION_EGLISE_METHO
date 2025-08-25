<?php


namespace App\Observers;

use App\Models\Culte;
use App\Services\CKEditorCacheService;
use Illuminate\Support\Facades\Log;

class CulteObserver
{
    private $cacheService;

    public function __construct(CKEditorCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Événement lors de la sauvegarde
     */
    public function saving(Culte $culte)
    {
        // Pre-processing des champs CKEditor
        foreach ($culte->getCKEditorFields() as $field) {
            if ($culte->isDirty($field)) {
                $content = $culte->getAttribute($field);

                if (!empty($content)) {
                    // Valider la taille du contenu
                    $maxLength = config('ckeditor.security.max_content_length', 65000);
                    if (strlen($content) > $maxLength) {
                        throw new \InvalidArgumentException(
                            "Le contenu du champ {$field} dépasse la taille maximale autorisée ({$maxLength} caractères)"
                        );
                    }

                    // Log des contenus suspects
                    if (config('ckeditor.monitoring.enabled')) {
                        $this->checkSuspiciousContent($field, $content, $culte);
                    }
                }
            }
        }
    }

    /**
     * Événement après sauvegarde
     */
    public function saved(Culte $culte)
    {
        // Invalider le cache pour ce culte
        $this->cacheService->invalidateCache("culte_{$culte->id}");

        // Pre-cache du contenu principal si activé
        if (config('ckeditor.performance.async_processing')) {
            $this->preCacheContent($culte);
        }
    }

    /**
     * Événement avant suppression
     */
    public function deleting(Culte $culte)
    {
        // Nettoyer le cache
        $this->cacheService->invalidateCache("culte_{$culte->id}");
    }

    /**
     * Pré-cache le contenu
     */
    private function preCacheContent(Culte $culte)
    {
        dispatch(function () use ($culte) {
            foreach ($culte->getCKEditorFields() as $field) {
                $content = $culte->getAttribute($field);
                if (!empty($content)) {
                    $key = "culte_{$culte->id}_{$field}";
                    $this->cacheService->cacheFormattedContent($key, $content);
                    $this->cacheService->cachePlainText($key, $content);
                    $this->cacheService->cacheContentMeta($key, $content);
                }
            }
        })->afterResponse();
    }

    /**
     * Vérifie le contenu suspect
     */
    private function checkSuspiciousContent(string $field, string $content, Culte $culte)
    {
        $suspiciousPatterns = [
            'javascript:',
            'data:text/html',
            '<script',
            'onclick=',
            'onerror=',
            'eval(',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($content, $pattern) !== false) {
                Log::warning('Suspicious content detected in CKEditor field', [
                    'field' => $field,
                    'pattern' => $pattern,
                    'culte_id' => $culte->id,
                    'user_id' => auth()->id(),
                    'ip' => request()->ip(),
                    'content_preview' => substr($content, 0, 200),
                ]);
                break;
            }
        }
    }
}
