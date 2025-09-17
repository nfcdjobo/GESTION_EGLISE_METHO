<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Error404Log;
use Symfony\Component\HttpFoundation\Response;

class Track404Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Enregistrer le temps de début pour calculer le temps de réponse
        $startTime = microtime(true);

        $response = $next($request);

        // Si c'est une 404, faire le tracking
        if ($response->getStatusCode() === 404) {
            $responseTime = round((microtime(true) - $startTime) * 1000); // en millisecondes
            $this->track404($request, $responseTime);
        }

        return $response;
    }

    /**
     * Effectue le tracking de l'erreur 404
     */
    private function track404(Request $request, int $responseTime): void
    {
        try {
            // Mise à jour des compteurs en cache
            $this->updateCacheCounters($request);

            // Sauvegarder l'URL pour analyse dans la base de données
            $this->save404Url($request, $responseTime);

            // Tracking comportemental membres
            $this->trackUserBehavior($request, $responseTime);

            // Alertes automatiques si nécessaire
            $this->checkForAlerts($request);

        } catch (\Exception $e) {
            // Logger l'erreur mais ne pas faire échouer la requête
            Log::error('Erreur tracking 404', [
                'error' => $e->getMessage(),
                'url' => $request->fullUrl(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Met à jour les compteurs dans le cache
     */
    private function updateCacheCounters(Request $request): void
    {
        // Compteur global journalier
        $dailyKey = '404_count_' . now()->format('Y-m-d');
        Cache::increment($dailyKey, 1);
        Cache::put($dailyKey, Cache::get($dailyKey, 0), now()->endOfDay());

        // Compteur horaire pour les pics de trafic
        $hourlyKey = '404_count_' . now()->format('Y-m-d-H');
        Cache::increment($hourlyKey, 1);
        Cache::put($hourlyKey, Cache::get($hourlyKey, 0), now()->endOfHour());

        // Compteur par IP (pour détecter les attaques)
        $ipKey = '404_ip_' . md5($request->ip()) . '_' . now()->format('Y-m-d');
        Cache::increment($ipKey, 1);
        Cache::put($ipKey, Cache::get($ipKey, 0), now()->endOfDay());

        // Compteur par user agent (pour détecter les bots problématiques)
        if ($request->userAgent()) {
            $userAgentKey = '404_ua_' . md5($request->userAgent()) . '_' . now()->format('Y-m-d');
            Cache::increment($userAgentKey, 1);
            Cache::put($userAgentKey, Cache::get($userAgentKey, 0), now()->endOfDay());
        }
    }

    /**
     * Sauvegarde l'URL 404 pour analytics détaillés
     */
    private function save404Url(Request $request, int $responseTime): void
    {
        // Compteur spécifique par URL
        $url = $request->path();
        $urlCountKey = "404_url_count_{$url}";
        $count = Cache::get($urlCountKey, 0) + 1;
        Cache::put($urlCountKey, $count, now()->addDays(30));

        // Mise à jour du top des URLs 404
        $topUrls = Cache::get('top_404_urls', []);
        $topUrls[$url] = $count;

        // Garder seulement les 100 URLs les plus fréquentes
        arsort($topUrls);
        $topUrls = array_slice($topUrls, 0, 100, true);

        Cache::put('top_404_urls', $topUrls, now()->addDays(30));

        // Sauvegarder les données détaillées avec géolocalisation
        $this->saveDetailedAnalytics($request, $responseTime, $count);
    }

    /**
     * Sauvegarde les analytics détaillés
     */
    private function saveDetailedAnalytics(Request $request, int $responseTime, int $count): void
    {
        // Analyser le user agent
        $userAgent = $request->userAgent() ?? '';
        $isBot = $this->detectBot($userAgent);
        $isMobile = $this->detectMobile($userAgent);

        // Géolocalisation basique (peut être améliorée avec des services externes)
        $location = $this->getLocationFromIp($request->ip());

        try {
            Error404Log::create([
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $userAgent,
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'referrer' => $request->headers->get('referer'),
                'request_data' => $this->sanitizeRequestData($request),
                'headers' => $this->getImportantHeaders($request),
                'locale' => app()->getLocale(),
                'country_code' => $location['country_code'] ?? null,
                'city' => $location['city'] ?? null,
                'response_time' => $responseTime,
                'is_bot' => $isBot,
                'is_mobile' => $isMobile
            ]);
        } catch (\Exception $e) {
            // Fallback vers l'insertion directe si le modèle Eloquent ne fonctionne pas
            $this->fallbackSave($request, $responseTime, $isBot, $isMobile, $location);
        }
    }

    /**
     * Fallback pour sauvegarder sans Eloquent
     */
    private function fallbackSave(Request $request, int $responseTime, bool $isBot, bool $isMobile, array $location): void
    {
        try {
            DB::table('error_404_logs')->insert([
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'referrer' => $request->headers->get('referer'),
                'request_data' => json_encode($this->sanitizeRequestData($request)),
                'headers' => json_encode($this->getImportantHeaders($request)),
                'locale' => app()->getLocale(),
                'country_code' => $location['country_code'] ?? null,
                'city' => $location['city'] ?? null,
                'response_time' => $responseTime,
                'is_bot' => $isBot,
                'is_mobile' => $isMobile,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Fallback save 404 failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Tracking du comportement membres
     */
    private function trackUserBehavior(Request $request, int $responseTime): void
    {
        if (!auth()->check()) {
            return;
        }

        $userId = auth()->id();

        // Compter les 404 consécutives pour cet membres
        $userErrorsKey = "user_404_count_{$userId}_" . now()->format('Y-m-d-H');
        $userErrorCount = Cache::get($userErrorsKey, 0) + 1;
        Cache::put($userErrorsKey, $userErrorCount, now()->endOfHour());

        // Si l'membres fait beaucoup d'erreurs 404, logger pour investigation
        if ($userErrorCount >= 5) {
            Log::warning('Membres avec beaucoup d\'erreurs 404', [
                'user_id' => $userId,
                'error_count' => $userErrorCount,
                'last_url' => $request->fullUrl(),
                'session_id' => session()->getId()
            ]);
        }

        // Analyser les patterns de navigation
        $this->analyzeNavigationPattern($userId, $request);
    }

    /**
     * Analyse les patterns de navigation
     */
    private function analyzeNavigationPattern(string $userId, Request $request): void
    {
        $navigationKey = "user_navigation_{$userId}";
        $navigation = Cache::get($navigationKey, []);

        // Ajouter la nouvelle URL au pattern de navigation
        $navigation[] = [
            'url' => $request->fullUrl(),
            'timestamp' => now()->toISOString(),
            'method' => $request->method(),
            'referrer' => $request->headers->get('referer')
        ];

        // Garder seulement les 20 dernières URLs
        $navigation = array_slice($navigation, -20);

        Cache::put($navigationKey, $navigation, now()->addHours(2));
    }

    /**
     * Vérifie s'il faut déclencher des alertes
     */
    private function checkForAlerts(Request $request): void
    {
        // Alerte si pic de 404 détecté
        $hourlyCount = Cache::get('404_count_' . now()->format('Y-m-d-H'), 0);
        $threshold = config('app.404_alert_threshold', 50);

        if ($hourlyCount >= $threshold) {
            $alertKey = 'alert_404_spike_' . now()->format('Y-m-d-H');

            // Envoyer l'alerte seulement une fois par heure
            if (!Cache::has($alertKey)) {
                $this->sendAlert404Spike($hourlyCount);
                Cache::put($alertKey, true, now()->endOfHour());
            }
        }

        // Alerte pour attaque potentielle (même IP, beaucoup de 404)
        $ipCount = Cache::get('404_ip_' . md5($request->ip()) . '_' . now()->format('Y-m-d'), 0);
        if ($ipCount >= 20) {
            $this->checkPotentialAttack($request->ip(), $ipCount);
        }
    }

    /**
     * Envoie une alerte pour un pic de 404
     */
    private function sendAlert404Spike(int $count): void
    {
        Log::warning('Pic d\'erreurs 404 détecté', [
            'count' => $count,
            'hour' => now()->format('Y-m-d H:i'),
            'threshold_exceeded' => true
        ]);

        // Ici, vous pouvez ajouter l'envoi d'email, notification Slack, etc.
        // Mail::to('admin@example.com')->send(new Alert404Spike($count));
    }

    /**
     * Vérifie une attaque potentielle
     */
    private function checkPotentialAttack(string $ip, int $count): void
    {
        $attackKey = "potential_attack_{$ip}_" . now()->format('Y-m-d');

        if (!Cache::has($attackKey)) {
            Log::alert('Attaque potentielle détectée', [
                'ip' => $ip,
                'count_404' => $count,
                'date' => now()->toDateString()
            ]);

            Cache::put($attackKey, true, now()->endOfDay());

            // Optionnel : bloquer temporairement l'IP
            // $this->temporarilyBlockIp($ip);
        }
    }

    /**
     * Détecte si le user agent correspond à un bot
     */
    private function detectBot(string $userAgent): bool
    {
        $botPatterns = [
            '/bot/i', '/crawler/i', '/spider/i', '/crawling/i',
            '/facebook/i', '/twitter/i', '/linkedin/i',
            '/google/i', '/yahoo/i', '/bing/i', '/baidu/i',
            '/yandex/i', '/duckduckgo/i', '/slurp/i'
        ];

        foreach ($botPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Détecte si la requête provient d'un mobile
     */
    private function detectMobile(string $userAgent): bool
    {
        $mobilePatterns = [
            '/Mobile/i', '/Android/i', '/iPhone/i', '/iPad/i',
            '/BlackBerry/i', '/Windows Phone/i', '/Opera Mini/i',
            '/IEMobile/i', '/webOS/i', '/Kindle/i'
        ];

        foreach ($mobilePatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtient la géolocalisation approximative à partir de l'IP
     */
    private function getLocationFromIp(string $ip): array
    {
        // Implémentation basique - peut être améliorée avec des services comme MaxMind
        if ($ip === '127.0.0.1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return ['country_code' => 'LOCAL', 'city' => 'Local'];
        }

        // Ici vous pouvez intégrer un service de géolocalisation
        // return $this->geolocateWithService($ip);

        return [];
    }

    /**
     * Sanitise les données de requête pour le stockage
     */
    private function sanitizeRequestData(Request $request): array
    {
        $data = [];

        // Paramètres GET
        if ($request->query()) {
            $data['query'] = array_slice($request->query(), 0, 10); // Limiter à 10 paramètres
        }

        // Quelques données POST sans informations sensibles
        if ($request->isMethod('POST')) {
            $postData = $request->only(['_token', 'page', 'search', 'filter']);
            if (!empty($postData)) {
                $data['post'] = $postData;
            }
        }

        return $data;
    }

    /**
     * Récupère les en-têtes importantes pour l'analyse
     */
    private function getImportantHeaders(Request $request): array
    {
        return [
            'accept' => $request->headers->get('accept'),
            'accept_language' => $request->headers->get('accept-language'),
            'accept_encoding' => $request->headers->get('accept-encoding'),
            'connection' => $request->headers->get('connection'),
            'upgrade_insecure_requests' => $request->headers->get('upgrade-insecure-requests'),
            'sec_fetch_dest' => $request->headers->get('sec-fetch-dest'),
            'sec_fetch_mode' => $request->headers->get('sec-fetch-mode'),
            'sec_fetch_site' => $request->headers->get('sec-fetch-site')
        ];
    }
}
