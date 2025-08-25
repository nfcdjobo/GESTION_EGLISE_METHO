<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\CKEditorCacheService;
use App\Helpers\CKEditorHelper;

class CKEditorHealthController extends Controller
{
    private $cacheService;

    public function __construct(CKEditorCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Vérifie la santé du système CKEditor
     */
    public function healthCheck()
    {
        $checks = [];
        $overall_status = 'healthy';

        // Test de configuration
        try {
            $config = config('ckeditor');
            $checks['configuration'] = [
                'status' => !empty($config) ? 'ok' : 'error',
                'enabled' => $config['enabled'] ?? false,
            ];
        } catch (\Exception $e) {
            $checks['configuration'] = ['status' => 'error', 'error' => $e->getMessage()];
            $overall_status = 'unhealthy';
        }

        // Test de cache
        try {
            $cacheStats = $this->cacheService->getCacheStats();
            $checks['cache'] = [
                'status' => 'ok',
                'enabled' => $cacheStats['enabled'],
                'driver' => $cacheStats['driver'],
            ];
        } catch (\Exception $e) {
            $checks['cache'] = ['status' => 'error', 'error' => $e->getMessage()];
            $overall_status = 'degraded';
        }

        // Test de processing
        try {
            $testContent = '<p>Test <strong>content</strong></p>';
            $startTime = microtime(true);

            $cleaned = CKEditorHelper::cleanContent($testContent);
            $plainText = CKEditorHelper::toPlainText($testContent);

            $endTime = microtime(true);
            $processingTime = ($endTime - $startTime) * 1000;

            $checks['processing'] = [
                'status' => $processingTime < 100 ? 'ok' : 'slow',
                'processing_time_ms' => round($processingTime, 2),
                'test_passed' => !empty($cleaned) && !empty($plainText),
            ];

            if ($processingTime > 100) {
                $overall_status = 'degraded';
            }
        } catch (\Exception $e) {
            $checks['processing'] = ['status' => 'error', 'error' => $e->getMessage()];
            $overall_status = 'unhealthy';
        }

        // Test de sécurité
        try {
            $maliciousContent = '<script>alert("xss")</script><p>Normal content</p>';
            $cleaned = CKEditorHelper::cleanContent($maliciousContent);

            $checks['security'] = [
                'status' => !str_contains($cleaned, '<script>') ? 'ok' : 'vulnerable',
                'xss_protection' => !str_contains($cleaned, '<script>'),
            ];

            if (str_contains($cleaned, '<script>')) {
                $overall_status = 'unhealthy';
            }
        } catch (\Exception $e) {
            $checks['security'] = ['status' => 'error', 'error' => $e->getMessage()];
            $overall_status = 'unhealthy';
        }

        return response()->json([
            'status' => $overall_status,
            'timestamp' => now()->toISOString(),
            'checks' => $checks,
            'version' => config('ckeditor.cdn.version'),
        ], $overall_status === 'healthy' ? 200 : 503);
    }

    /**
     * Métriques détaillées
     */
    public function metrics()
    {
        return response()->json([
            'cache_stats' => $this->cacheService->getCacheStats(),
            'memory_usage' => [
                'current' => memory_get_usage(true),
                'peak' => memory_get_peak_usage(true),
            ],
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
        ]);
    }
}
