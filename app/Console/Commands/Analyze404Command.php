<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Models\Error404Log;
use Carbon\Carbon;

class Analyze404Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'errors:analyze-404
                           {--days=7 : Nombre de jours à analyser}
                           {--export : Exporter les résultats en CSV}
                           {--detailed : Affichage détaillé avec géolocalisation}
                           {--suggestions : Générer des suggestions de redirections}
                           {--cleanup : Nettoyer les anciens logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyse les erreurs 404 sur une période donnée avec statistiques détaillées';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = (int) $this->option('days');
        $startDate = now()->subDays($days);

        $this->info("🔍 Analyse des erreurs 404 des {$days} derniers jours...");
        $this->info("📅 Période: du " . $startDate->format('d/m/Y') . " au " . now()->format('d/m/Y'));
        $this->newLine();

        // Nettoyage des anciens logs si demandé
        if ($this->option('cleanup')) {
            $this->cleanupOldLogs();
        }

        // Statistiques globales
        $this->displayGlobalStats($days);

        // Top des URLs 404
        $this->displayTopUrls($days);

        // Analyse des tendances
        $this->displayTrends($days);

        // Analyse des user agents (bots vs humains)
        $this->displayUserAgentAnalysis($days);

        // Analyse géographique si demandée
        if ($this->option('detailed')) {
            $this->displayGeographicAnalysis($days);
            $this->displayDetailedTimeline($days);
        }

        // Suggestions de redirections si demandées
        if ($this->option('suggestions')) {
            $this->generateRedirectionSuggestions($days);
        }

        // Export CSV si demandé
        if ($this->option('export')) {
            $this->exportToCsv($days);
        }

        $this->newLine();
        $this->info("✅ Analyse terminée !");
    }

    /**
     * Affiche les statistiques globales
     */
    private function displayGlobalStats(int $days): void
    {
        $this->info("📊 STATISTIQUES GLOBALES");
        $this->line(str_repeat("═", 50));

        try {
            $total = Error404Log::inLastDays($days)->count();
            $totalHumans = Error404Log::inLastDays($days)->notFromBots()->count();
            $totalBots = $total - $totalHumans;
            $uniqueIps = Error404Log::inLastDays($days)->distinct('ip')->count('ip');
            $uniqueUsers = Error404Log::inLastDays($days)->whereNotNull('user_id')->distinct('user_id')->count('user_id');

            $this->table(['Métrique', 'Valeur'], [
                ['Total erreurs 404', number_format($total)],
                ['Erreurs humaines', number_format($totalHumans) . ' (' . round(($totalHumans/$total)*100, 1) . '%)'],
                ['Erreurs de bots', number_format($totalBots) . ' (' . round(($totalBots/$total)*100, 1) . '%)'],
                ['IPs uniques', number_format($uniqueIps)],
                ['Membress uniques', number_format($uniqueUsers)],
                ['Moyenne par jour', number_format($total / max($days, 1), 1)],
                ['Moyenne par heure', number_format($total / max($days * 24, 1), 1)]
            ]);

        } catch (\Exception $e) {
            $this->warn("Impossible d'accéder aux données de la base. Utilisation du cache...");
            $this->displayCacheStats($days);
        }

        $this->newLine();
    }

    /**
     * Affiche les stats depuis le cache si la base n'est pas accessible
     */
    private function displayCacheStats(int $days): void
    {
        $topUrls = Cache::get('top_404_urls', []);
        $totalFromCache = array_sum($topUrls);

        $this->table(['Métrique', 'Valeur'], [
            ['Total erreurs (cache)', number_format($totalFromCache)],
            ['URLs distinctes', count($topUrls)]
        ]);
    }

    /**
     * Affiche le top des URLs 404
     */
    private function displayTopUrls(int $days): void
    {
        $this->info("🔗 TOP DES URLS EN ERREUR 404");
        $this->line(str_repeat("═", 50));

        try {
            $topUrls = Error404Log::select('path', DB::raw('count(*) as count'), DB::raw('count(distinct ip) as unique_ips'))
                ->inLastDays($days)
                ->notFromBots()
                ->groupBy('path')
                ->orderByDesc('count')
                ->limit(15)
                ->get();

            if ($topUrls->isNotEmpty()) {
                $tableData = $topUrls->map(function ($item, $index) {
                    return [
                        $index + 1,
                        $item->path,
                        number_format($item->count),
                        number_format($item->unique_ips),
                        $this->getPathSuggestion($item->path)
                    ];
                })->toArray();

                $this->table(
                    ['#', 'Chemin', 'Occurrences', 'IPs', 'Suggestion'],
                    $tableData
                );
            } else {
                $this->info("Aucune donnée 404 trouvée en base.");
            }

        } catch (\Exception $e) {
            $this->warn("Données depuis le cache:");
            $topUrls = Cache::get('top_404_urls', []);

            if (!empty($topUrls)) {
                $tableData = collect($topUrls)->map(function ($count, $url) {
                    return [$url, number_format($count)];
                })->take(15)->values()->toArray();

                $this->table(['Chemin', 'Occurrences'], $tableData);
            } else {
                $this->info("Aucune donnée 404 trouvée dans le cache.");
            }
        }

        $this->newLine();
    }

    /**
     * Affiche les tendances
     */
    private function displayTrends(int $days): void
    {
        $this->info("📈 TENDANCES ET ÉVOLUTION");
        $this->line(str_repeat("═", 50));

        try {
            $dailyStats = Error404Log::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as total'),
                    DB::raw('sum(case when is_bot = 0 then 1 else 0 end) as humans'),
                    DB::raw('sum(case when is_bot = 1 then 1 else 0 end) as bots'),
                    DB::raw('count(distinct ip) as unique_ips')
                )
                ->inLastDays($days)
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->limit(10)
                ->get();

            if ($dailyStats->isNotEmpty()) {
                $tableData = $dailyStats->map(function ($stat) use ($dailyStats) {
                    return [
                        Carbon::parse($stat->date)->format('d/m/Y'),
                        number_format($stat->total),
                        number_format($stat->humans),
                        number_format($stat->bots),
                        number_format($stat->unique_ips),
                        $this->getTrendIndicator($stat->total, $dailyStats)
                    ];
                })->toArray();

                $this->table(
                    ['Date', 'Total', 'Humains', 'Bots', 'IPs uniques', 'Tendance'],
                    $tableData
                );

                // Calcul de la tendance globale
                $trends = Error404Log::getTrends($days);
                $trendIcon = $trends['trend'] === 'increase' ? '📈' : ($trends['trend'] === 'decrease' ? '📉' : '➡️');

                $this->info($trendIcon . " Évolution: {$trends['change_percentage']}% par rapport à la période précédente");
            }

        } catch (\Exception $e) {
            $this->warn("Impossible de calculer les tendances depuis la base de données.");
        }

        $this->newLine();
    }

    /**
     * Analyse des user agents
     */
    private function displayUserAgentAnalysis(int $days): void
    {
        $this->info("🤖 ANALYSE DES USER AGENTS");
        $this->line(str_repeat("═", 50));

        try {
            $userAgentStats = Error404Log::select(
                    'user_agent',
                    DB::raw('count(*) as count'),
                    'is_bot',
                    'is_mobile'
                )
                ->inLastDays($days)
                ->groupBy('user_agent', 'is_bot', 'is_mobile')
                ->orderByDesc('count')
                ->limit(10)
                ->get();

            if ($userAgentStats->isNotEmpty()) {
                $tableData = $userAgentStats->map(function ($stat) {
                    $type = $stat->is_bot ? '🤖 Bot' : ($stat->is_mobile ? '📱 Mobile' : '💻 Desktop');
                    return [
                        $this->truncate($stat->user_agent, 50),
                        number_format($stat->count),
                        $type
                    ];
                })->toArray();

                $this->table(['User Agent', 'Occurrences', 'Type'], $tableData);

                // Résumé par type
                $summary = Error404Log::select(
                        DB::raw('sum(case when is_bot = 1 then 1 else 0 end) as bots'),
                        DB::raw('sum(case when is_mobile = 1 and is_bot = 0 then 1 else 0 end) as mobile'),
                        DB::raw('sum(case when is_mobile = 0 and is_bot = 0 then 1 else 0 end) as desktop')
                    )
                    ->inLastDays($days)
                    ->first();

                if ($summary) {
                    $this->info("🤖 Bots: " . number_format($summary->bots));
                    $this->info("📱 Mobile: " . number_format($summary->mobile));
                    $this->info("💻 Desktop: " . number_format($summary->desktop));
                }
            }

        } catch (\Exception $e) {
            $this->warn("Impossible d'analyser les user agents.");
        }

        $this->newLine();
    }

    /**
     * Analyse géographique détaillée
     */
    private function displayGeographicAnalysis(int $days): void
    {
        $this->info("🌍 ANALYSE GÉOGRAPHIQUE");
        $this->line(str_repeat("═", 50));

        try {
            $geoStats = Error404Log::select(
                    'country_code',
                    'city',
                    DB::raw('count(*) as count'),
                    DB::raw('count(distinct ip) as unique_ips')
                )
                ->inLastDays($days)
                ->notFromBots()
                ->whereNotNull('country_code')
                ->groupBy('country_code', 'city')
                ->orderByDesc('count')
                ->limit(10)
                ->get();

            if ($geoStats->isNotEmpty()) {
                $tableData = $geoStats->map(function ($stat) {
                    return [
                        $stat->country_code,
                        $stat->city ?? 'Non spécifié',
                        number_format($stat->count),
                        number_format($stat->unique_ips)
                    ];
                })->toArray();

                $this->table(['Pays', 'Ville', 'Erreurs', 'IPs uniques'], $tableData);
            } else {
                $this->info("Pas de données géographiques disponibles.");
            }

        } catch (\Exception $e) {
            $this->warn("Impossible d'analyser les données géographiques.");
        }

        $this->newLine();
    }

    /**
     * Timeline détaillée par heure
     */
    private function displayDetailedTimeline(int $days): void
    {
        $this->info("⏰ TIMELINE DÉTAILLÉE (24h)");
        $this->line(str_repeat("═", 50));

        try {
            $hourlyStats = Error404Log::select(
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('count(*) as count')
                )
                ->inLastDays($days)
                ->notFromBots()
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();

            if ($hourlyStats->isNotEmpty()) {
                $maxCount = $hourlyStats->max('count');

                foreach ($hourlyStats as $stat) {
                    $barLength = (int) (($stat->count / $maxCount) * 30);
                    $bar = str_repeat('█', $barLength) . str_repeat('░', 30 - $barLength);
                    $this->line(sprintf('%02d:00 |%s| %s', $stat->hour, $bar, number_format($stat->count)));
                }

                $peakHour = $hourlyStats->sortByDesc('count')->first();
                $this->info("🔥 Pic d'activité: {$peakHour->hour}:00 avec " . number_format($peakHour->count) . " erreurs");
            }

        } catch (\Exception $e) {
            $this->warn("Impossible de générer la timeline.");
        }

        $this->newLine();
    }

    /**
     * Génère des suggestions de redirections
     */
    private function generateRedirectionSuggestions(int $days): void
    {
        $this->info("💡 SUGGESTIONS DE REDIRECTIONS");
        $this->line(str_repeat("═", 50));

        try {
            $topPaths = Error404Log::getMostFrequentPaths(20, $days);
            $suggestions = [];

            foreach ($topPaths as $pathData) {
                $suggestion = $this->findBestRedirect($pathData->path);
                if ($suggestion) {
                    $suggestions[] = [
                        $pathData->path,
                        $suggestion['target'],
                        $suggestion['reason'],
                        number_format($pathData->count)
                    ];
                }
            }

            if (!empty($suggestions)) {
                $this->table(
                    ['Chemin 404', 'Redirection suggérée', 'Raison', 'Occurrences'],
                    $suggestions
                );

                // Générer le code de redirection
                $this->generateRedirectCode($suggestions);
            } else {
                $this->info("Aucune suggestion de redirection automatique trouvée.");
            }

        } catch (\Exception $e) {
            $this->warn("Impossible de générer les suggestions de redirections.");
        }

        $this->newLine();
    }

    /**
     * Génère le code de redirection
     */
    private function generateRedirectCode(array $suggestions): void
    {
        if ($this->confirm('Voulez-vous générer le code de redirection pour le Handler ?')) {
            $this->info("📝 Code à ajouter dans app/Exceptions/Handler.php:");
            $this->newLine();

            $this->line("// Redirections automatiques générées le " . now()->format('d/m/Y H:i'));
            $this->line('$redirects = [');

            foreach ($suggestions as $suggestion) {
                $this->line("    '{$suggestion[0]}' => '{$suggestion[1]}', // {$suggestion[2]}");
            }

            $this->line('];');
            $this->newLine();
        }
    }

    /**
     * Exporte les résultats en CSV
     */
    private function exportToCsv(int $days): void
    {
        $this->info("📄 EXPORT CSV");
        $this->line(str_repeat("═", 50));

        try {
            $data = Error404Log::select([
                    'created_at', 'path', 'url', 'method', 'ip', 'user_agent',
                    'country_code', 'city', 'is_bot', 'is_mobile', 'response_time'
                ])
                ->inLastDays($days)
                ->orderBy('created_at', 'desc')
                ->get();

            $filename = storage_path("app/404_analysis_" . now()->format('Y-m-d_H-i-s') . ".csv");

            $file = fopen($filename, 'w');

            // En-têtes
            fputcsv($file, [
                'Date', 'Chemin', 'URL', 'Méthode', 'IP', 'User Agent',
                'Pays', 'Ville', 'Bot', 'Mobile', 'Temps (ms)'
            ]);

            // Données
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->created_at,
                    $row->path,
                    $row->url,
                    $row->method,
                    $row->ip,
                    $row->user_agent,
                    $row->country_code,
                    $row->city,
                    $row->is_bot ? 'Oui' : 'Non',
                    $row->is_mobile ? 'Oui' : 'Non',
                    $row->response_time
                ]);
            }

            fclose($file);
            $this->info("✅ Export sauvegardé: {$filename}");
            $this->info("📊 " . number_format($data->count()) . " lignes exportées");

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de l'export: " . $e->getMessage());
        }

        $this->newLine();
    }

    /**
     * Nettoie les anciens logs
     */
    private function cleanupOldLogs(): void
    {
        $this->info("🧹 NETTOYAGE DES ANCIENS LOGS");
        $this->line(str_repeat("═", 50));

        try {
            $daysToKeep = $this->ask('Combien de jours de logs garder ?', '90');
            $deleted = Error404Log::cleanOldLogs((int) $daysToKeep);

            $this->info("✅ {$deleted} anciens logs supprimés");
            $this->info("📅 Logs conservés: " . $daysToKeep . " derniers jours");

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors du nettoyage: " . $e->getMessage());
        }

        $this->newLine();
    }

    /**
     * Trouve la meilleure redirection pour un chemin
     */
    private function findBestRedirect(string $path): ?array
    {
        // Vérifier les routes similaires
        $routes = Route::getRoutes();
        $bestMatch = null;
        $bestScore = 0;

        foreach ($routes as $route) {
            $routeUri = $route->uri();

            if (strpos($routeUri, '{') !== false) {
                continue;
            }

            similar_text($path, $routeUri, $percent);

            if ($percent > $bestScore && $percent >= 60) {
                $bestScore = $percent;
                $bestMatch = [
                    'target' => $routeUri,
                    'reason' => "Similarité {$percent}%",
                    'score' => $percent
                ];
            }
        }

        return $bestMatch;
    }

    /**
     * Obtient une suggestion pour un chemin
     */
    private function getPathSuggestion(string $path): string
    {
        $redirect = $this->findBestRedirect($path);
        return $redirect ? "→ {$redirect['target']}" : "-";
    }

    /**
     * Obtient un indicateur de tendance
     */
    private function getTrendIndicator(int $current, $allStats): string
    {
        $previous = $allStats->skip(1)->first();
        if (!$previous) return "-";

        $change = (($current - $previous->total) / $previous->total) * 100;

        if ($change > 10) return "📈 +" . round($change, 1) . "%";
        if ($change < -10) return "📉 " . round($change, 1) . "%";
        return "➡️ " . round($change, 1) . "%";
    }

    /**
     * Tronque une chaîne
     */
    private function truncate(string $text, int $length): string
    {
        return strlen($text) > $length ? substr($text, 0, $length) . '...' : $text;
    }
}
