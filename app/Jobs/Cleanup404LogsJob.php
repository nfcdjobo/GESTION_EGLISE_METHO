<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Models\Error404Log;
use Carbon\Carbon;

class Cleanup404LogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Le temps maximum d'exécution du job (en secondes).
     *
     * @var int
     */
    public int $timeout = 300;

    /**
     * Nombre de jours de logs à conserver
     */
    protected int $daysToKeep;

    /**
     * Nettoyer aussi les caches
     */
    protected bool $cleanCache;

    /**
     * Nettoyer les fichiers de logs
     */
    protected bool $cleanLogFiles;

    /**
     * Générer un rapport de nettoyage
     */
    protected bool $generateReport;

    /**
     * Create a new job instance.
     */
    public function __construct(
        int $daysToKeep = 90,
        bool $cleanCache = true,
        bool $cleanLogFiles = false,
        bool $generateReport = true
    ) {
        $this->daysToKeep = $daysToKeep;
        $this->cleanCache = $cleanCache;
        $this->cleanLogFiles = $cleanLogFiles;
        $this->generateReport = $generateReport;

        // Configuration de la queue
        $this->onQueue('maintenance');
        // $this->timeout = 300; // 5 minutes
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $startTime = microtime(true);
        $report = [
            'start_time' => now()->toDateTimeString(),
            'days_to_keep' => $this->daysToKeep,
            'database_cleanup' => null,
            'cache_cleanup' => null,
            'log_files_cleanup' => null,
            'total_freed_space' => 0,
            'duration_seconds' => 0,
            'errors' => []
        ];

        Log::info('🧹 Début du nettoyage des logs 404', [
            'days_to_keep' => $this->daysToKeep,
            'clean_cache' => $this->cleanCache,
            'clean_log_files' => $this->cleanLogFiles
        ]);

        try {
            // 1. Nettoyage de la base de données
            $report['database_cleanup'] = $this->cleanupDatabase();

            // 2. Nettoyage du cache si demandé
            if ($this->cleanCache) {
                $report['cache_cleanup'] = $this->cleanupCache();
            }

            // 3. Nettoyage des fichiers de logs si demandé
            if ($this->cleanLogFiles) {
                $report['log_files_cleanup'] = $this->cleanupLogFiles();
            }

            // 4. Calcul des métriques finales
            $report['duration_seconds'] = round(microtime(true) - $startTime, 2);
            $report['end_time'] = now()->toDateTimeString();

            // 5. Génération du rapport si demandé
            if ($this->generateReport) {
                $this->generateCleanupReport($report);
            }

            Log::info('✅ Nettoyage des logs 404 terminé avec succès', $report);

        } catch (\Exception $e) {
            $report['errors'][] = [
                'type' => 'general_error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];

            Log::error('❌ Erreur lors du nettoyage des logs 404', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'report' => $report
            ]);

            throw $e;
        }
    }

    /**
     * Nettoie les anciens enregistrements de la base de données
     */
    protected function cleanupDatabase(): array
    {
        $result = [
            'attempted' => true,
            'deleted_count' => 0,
            'freed_space_estimate' => 0,
            'oldest_remaining' => null,
            'errors' => []
        ];

        try {
            $cutoffDate = now()->subDays($this->daysToKeep);

            // Compter les enregistrements à supprimer
            $toDeleteCount = Error404Log::where('created_at', '<', $cutoffDate)->count();

            if ($toDeleteCount === 0) {
                Log::info('Aucun log 404 à supprimer de la base de données');
                $result['deleted_count'] = 0;
                return $result;
            }

            // Estimation de l'espace libéré (approximation)
            $avgRecordSize = 2048; // bytes
            $result['freed_space_estimate'] = $toDeleteCount * $avgRecordSize;

            // Suppression par lots pour éviter les timeouts
            $batchSize = 1000;
            $totalDeleted = 0;

            do {
                $deleted = Error404Log::where('created_at', '<', $cutoffDate)
                    ->limit($batchSize)
                    ->delete();

                $totalDeleted += $deleted;

                // Petit délai pour éviter la surcharge
                if ($deleted === $batchSize) {
                    usleep(100000); // 0.1 seconde
                }

            } while ($deleted === $batchSize);

            $result['deleted_count'] = $totalDeleted;

            // Trouver l'enregistrement le plus ancien restant
            $oldest = Error404Log::orderBy('created_at')->first();
            $result['oldest_remaining'] = $oldest ? $oldest->created_at->toDateTimeString() : null;

            // Optimiser la table après suppression massive
            $this->optimizeTable();

            Log::info("🗑️ Supprimé {$totalDeleted} logs 404 de la base de données", [
                'cutoff_date' => $cutoffDate->toDateString(),
                'freed_space_estimate' => $this->formatBytes($result['freed_space_estimate']),
                'oldest_remaining' => $result['oldest_remaining']
            ]);

        } catch (\Exception $e) {
            $result['errors'][] = [
                'type' => 'database_error',
                'message' => $e->getMessage()
            ];

            Log::error('Erreur lors du nettoyage de la base de données', [
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    /**
     * Nettoie les entrées du cache liées aux 404
     */
    protected function cleanupCache(): array
    {
        $result = [
            'attempted' => true,
            'cleaned_keys' => [],
            'total_keys_cleaned' => 0,
            'errors' => []
        ];

        try {
            $keysToClean = [
                // Compteurs journaliers anciens
                'daily_404_counts' => $this->getOldDailyCountKeys(),

                // Compteurs par IP anciens
                'ip_404_counts' => $this->getOldIpCountKeys(),

                // Compteurs par User Agent anciens
                'ua_404_counts' => $this->getOldUserAgentKeys(),

                // URLs 404 peu fréquentes
                'low_frequency_urls' => $this->getLowFrequencyUrlKeys(),

                // Cache de statistiques expiré
                'expired_stats' => $this->getExpiredStatsKeys()
            ];

            foreach ($keysToClean as $category => $keys) {
                $cleaned = 0;
                foreach ($keys as $key) {
                    if (Cache::forget($key)) {
                        $cleaned++;
                    }
                }

                $result['cleaned_keys'][$category] = $cleaned;
                $result['total_keys_cleaned'] += $cleaned;
            }

            // Nettoyage spécial du top des URLs (garder seulement les 50 plus fréquentes)
            $this->cleanupTopUrlsCache();

            Log::info('🧽 Cache 404 nettoyé', [
                'total_keys' => $result['total_keys_cleaned'],
                'by_category' => $result['cleaned_keys']
            ]);

        } catch (\Exception $e) {
            $result['errors'][] = [
                'type' => 'cache_error',
                'message' => $e->getMessage()
            ];

            Log::error('Erreur lors du nettoyage du cache', [
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    /**
     * Nettoie les anciens fichiers de logs
     */
    protected function cleanupLogFiles(): array
    {
        $result = [
            'attempted' => true,
            'files_deleted' => [],
            'total_files_deleted' => 0,
            'freed_space' => 0,
            'errors' => []
        ];

        try {
            $logPath = storage_path('logs');
            $cutoffDate = now()->subDays($this->daysToKeep);

            $logFiles = [
                '404*.log',
                '404-security*.log',
                'user-behavior*.log',
                '404-bots*.log',
                '404-metrics*.log',
                '404-redirects*.log',
                '404-alerts*.log'
            ];

            foreach ($logFiles as $pattern) {
                $files = glob($logPath . '/' . $pattern);

                foreach ($files as $file) {
                    $fileDate = $this->extractDateFromLogFile($file);

                    if ($fileDate && Carbon::parse($fileDate)->lt($cutoffDate)) {
                        $fileSize = filesize($file);

                        if (unlink($file)) {
                            $result['files_deleted'][] = [
                                'file' => basename($file),
                                'size' => $fileSize,
                                'date' => $fileDate
                            ];

                            $result['freed_space'] += $fileSize;
                            $result['total_files_deleted']++;
                        }
                    }
                }
            }

            Log::info('📁 Fichiers de logs nettoyés', [
                'files_deleted' => $result['total_files_deleted'],
                'freed_space' => $this->formatBytes($result['freed_space'])
            ]);

        } catch (\Exception $e) {
            $result['errors'][] = [
                'type' => 'log_files_error',
                'message' => $e->getMessage()
            ];

            Log::error('Erreur lors du nettoyage des fichiers de logs', [
                'error' => $e->getMessage()
            ]);
        }

        return $result;
    }

    /**
     * Génère un rapport détaillé du nettoyage
     */
    protected function generateCleanupReport(array $report): void
    {
        try {
            $reportContent = $this->formatCleanupReport($report);

            // Sauvegarder le rapport
            $filename = 'cleanup_404_report_' . now()->format('Y-m-d_H-i-s') . '.log';
            Storage::disk('local')->put('reports/' . $filename, $reportContent);

            // Logger le rapport dans le channel dédié
            Log::channel('404_metrics')->info('Rapport de nettoyage 404 généré', [
                'report_file' => $filename,
                'summary' => [
                    'database_deleted' => $report['database_cleanup']['deleted_count'] ?? 0,
                    'cache_keys_cleaned' => $report['cache_cleanup']['total_keys_cleaned'] ?? 0,
                    'log_files_deleted' => $report['log_files_cleanup']['total_files_deleted'] ?? 0,
                    'total_duration' => $report['duration_seconds'],
                    'freed_space' => $report['total_freed_space']
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du rapport de nettoyage', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Formate le rapport de nettoyage
     */
    protected function formatCleanupReport(array $report): string
    {
        $content = "=== RAPPORT DE NETTOYAGE DES LOGS 404 ===\n\n";
        $content .= "Date: {$report['start_time']}\n";
        $content .= "Durée: {$report['duration_seconds']} secondes\n";
        $content .= "Conservation: {$report['days_to_keep']} derniers jours\n\n";

        // Base de données
        if ($report['database_cleanup']) {
            $db = $report['database_cleanup'];
            $content .= "--- BASE DE DONNÉES ---\n";
            $content .= "Enregistrements supprimés: {$db['deleted_count']}\n";
            $content .= "Espace libéré (estimation): " . $this->formatBytes($db['freed_space_estimate']) . "\n";
            $content .= "Plus ancien restant: {$db['oldest_remaining']}\n\n";
        }

        // Cache
        if ($report['cache_cleanup']) {
            $cache = $report['cache_cleanup'];
            $content .= "--- CACHE ---\n";
            $content .= "Clés nettoyées: {$cache['total_keys_cleaned']}\n";
            foreach ($cache['cleaned_keys'] as $category => $count) {
                $content .= "  - {$category}: {$count}\n";
            }
            $content .= "\n";
        }

        // Fichiers
        if ($report['log_files_cleanup']) {
            $files = $report['log_files_cleanup'];
            $content .= "--- FICHIERS DE LOGS ---\n";
            $content .= "Fichiers supprimés: {$files['total_files_deleted']}\n";
            $content .= "Espace libéré: " . $this->formatBytes($files['freed_space']) . "\n\n";
        }

        // Erreurs
        if (!empty($report['errors'])) {
            $content .= "--- ERREURS ---\n";
            foreach ($report['errors'] as $error) {
                $content .= "- {$error['type']}: {$error['message']}\n";
            }
            $content .= "\n";
        }

        $content .= "=== FIN DU RAPPORT ===\n";

        return $content;
    }

    // Méthodes utilitaires privées

    protected function getOldDailyCountKeys(): array
    {
        $keys = [];
        $cutoffDate = now()->subDays($this->daysToKeep);

        for ($i = $this->daysToKeep; $i <= $this->daysToKeep + 30; $i++) {
            $date = now()->subDays($i);
            $keys[] = '404_count_' . $date->format('Y-m-d');
        }

        return $keys;
    }

    protected function getOldIpCountKeys(): array
    {
        // Cette méthode nécessiterait une approche plus sophistiquée
        // pour énumérer toutes les clés IP existantes
        return [];
    }

    protected function getOldUserAgentKeys(): array
    {
        // Similaire aux IP keys
        return [];
    }

    protected function getLowFrequencyUrlKeys(): array
    {
        $topUrls = Cache::get('top_404_urls', []);
        $keysToClean = [];

        // Supprimer les URLs avec moins de 3 occurrences
        foreach ($topUrls as $url => $count) {
            if ($count < 3) {
                $keysToClean[] = "404_url_count_{$url}";
            }
        }

        return $keysToClean;
    }

    protected function getExpiredStatsKeys(): array
    {
        return [
            'most_searched_terms',
            '404_count_today',
            '404_count_week'
        ];
    }

    protected function cleanupTopUrlsCache(): void
    {
        $topUrls = Cache::get('top_404_urls', []);

        if (count($topUrls) > 50) {
            // Garder seulement les 50 URLs les plus fréquentes
            arsort($topUrls);
            $topUrls = array_slice($topUrls, 0, 50, true);
            Cache::put('top_404_urls', $topUrls, now()->addDays(30));
        }
    }

    protected function extractDateFromLogFile(string $file): ?string
    {
        // Extraction de la date depuis le nom de fichier (format Laravel: file-2024-01-01.log)
        if (preg_match('/(\d{4}-\d{2}-\d{2})\.log$/', $file, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function optimizeTable(): void
    {
        try {
            // Optimisation spécifique à MySQL
            if (config('database.default') === 'mysql') {
                \DB::statement('OPTIMIZE TABLE error_404_logs');
            }
        } catch (\Exception $e) {
            Log::warning('Impossible d\'optimiser la table error_404_logs', [
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes === 0) return '0 B';

        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor(log($bytes, 1024));

        return sprintf('%.2f %s', $bytes / pow(1024, $factor), $units[$factor]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('❌ Échec du job de nettoyage 404', [
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'days_to_keep' => $this->daysToKeep
        ]);
    }
}
