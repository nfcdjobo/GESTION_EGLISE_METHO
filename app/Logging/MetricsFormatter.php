<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Formatter\JsonFormatter;
use Monolog\Processor\IntrospectionProcessor;

class MetricsFormatter
{
    /**
     * Customize the given logger instance.
     */
    public function __invoke(Logger $logger): void
    {
        // Formatter JSON pour les métriques structurées
        $formatter = new JsonFormatter(JsonFormatter::BATCH_MODE_NEWLINES, true);

        // Processeur pour ajouter des informations contextuelles
        $processor = new IntrospectionProcessor(Logger::DEBUG, ['Illuminate\\', 'Laravel\\']);

        /** @var \Monolog\Handler\AbstractProcessingHandler $handler */
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter($formatter);
            $handler->pushProcessor($processor);

            // Processeur personnalisé pour les métriques 404
            $handler->pushProcessor([$this, 'processMetrics']);
        }
    }

    /**
     * Processeur personnalisé pour enrichir les logs de métriques
     */
    public function processMetrics(array $record): array
    {
        // Ajouter des métadonnées système
        $record['extra']['system'] = [
            'hostname' => gethostname(),
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'memory_usage' => memory_get_usage(true),
            'memory_peak' => memory_get_peak_usage(true)
        ];

        // Ajouter des informations sur la requête si disponible
        if (app()->bound('request')) {
            $request = app('request');
            $record['extra']['request'] = [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => auth()->id(),
                'session_id' => session()->getId()
            ];
        }

        // Ajouter des métriques de performance
        $record['extra']['performance'] = [
            'timestamp' => microtime(true),
            'execution_time' => defined('LARAVEL_START') ?
                round((microtime(true) - LARAVEL_START) * 1000, 2) : null,
            'queries_count' => \DB::getQueryLog() ? count(\DB::getQueryLog()) : null
        ];

        // Enrichir spécifiquement pour les erreurs 404
        if (isset($record['context']['type']) && $record['context']['type'] === '404') {
            $record['extra']['404_metrics'] = $this->enrich404Metrics($record);
        }

        return $record;
    }

    /**
     * Enrichit les métriques spécifiques aux erreurs 404
     */
    private function enrich404Metrics(array $record): array
    {
        $metrics = [];

        // Analyse du path
        if (isset($record['context']['path'])) {
            $path = $record['context']['path'];
            $metrics['path_analysis'] = [
                'segments_count' => count(explode('/', trim($path, '/'))),
                'has_extension' => pathinfo($path, PATHINFO_EXTENSION) !== '',
                'extension' => pathinfo($path, PATHINFO_EXTENSION) ?: null,
                'contains_numbers' => preg_match('/\d/', $path),
                'contains_special_chars' => preg_match('/[^a-zA-Z0-9\/\-_.]/', $path),
                'length' => strlen($path)
            ];
        }

        // Analyse du user agent
        if (isset($record['context']['user_agent'])) {
            $userAgent = $record['context']['user_agent'];
            $metrics['user_agent_analysis'] = [
                'is_mobile' => $this->isMobile($userAgent),
                'is_bot' => $this->isBot($userAgent),
                'browser' => $this->extractBrowser($userAgent),
                'platform' => $this->extractPlatform($userAgent),
                'length' => strlen($userAgent)
            ];
        }

        // Analyse du référent
        if (isset($record['context']['referrer'])) {
            $referrer = $record['context']['referrer'];
            $metrics['referrer_analysis'] = [
                'is_internal' => $this->isInternalReferrer($referrer),
                'domain' => parse_url($referrer, PHP_URL_HOST),
                'is_search_engine' => $this->isSearchEngineReferrer($referrer),
                'is_social_media' => $this->isSocialMediaReferrer($referrer)
            ];
        }

        // Métadonnées temporelles
        $metrics['temporal'] = [
            'hour_of_day' => (int) date('H'),
            'day_of_week' => (int) date('w'), // 0 = dimanche
            'day_of_month' => (int) date('j'),
            'month' => (int) date('n'),
            'is_weekend' => in_array(date('w'), [0, 6]),
            'is_business_hours' => $this->isBusinessHours()
        ];

        return $metrics;
    }

    /**
     * Détecte si l'user agent correspond à un mobile
     */
    private function isMobile(string $userAgent): bool
    {
        return preg_match('/Mobile|Android|iPhone|iPad|BlackBerry|Windows Phone|Opera Mini|IEMobile/i', $userAgent);
    }

    /**
     * Détecte si l'user agent correspond à un bot
     */
    private function isBot(string $userAgent): bool
    {
        return preg_match('/bot|crawler|spider|scraper|facebook|twitter|google|yahoo|bing|baidu|yandex/i', $userAgent);
    }

    /**
     * Extrait le navigateur du user agent
     */
    private function extractBrowser(string $userAgent): ?string
    {
        $browsers = [
            'Chrome' => '/Chrome\/([\d.]+)/',
            'Firefox' => '/Firefox\/([\d.]+)/',
            'Safari' => '/Safari\/([\d.]+)/',
            'Edge' => '/Edge\/([\d.]+)/',
            'Opera' => '/Opera\/([\d.]+)/',
            'Internet Explorer' => '/MSIE ([\d.]+)/'
        ];

        foreach ($browsers as $browser => $pattern) {
            if (preg_match($pattern, $userAgent, $matches)) {
                return $browser;
            }
        }

        return null;
    }

    /**
     * Extrait la plateforme du user agent
     */
    private function extractPlatform(string $userAgent): ?string
    {
        $platforms = [
            'Windows' => '/Windows NT/',
            'macOS' => '/Mac OS X/',
            'Linux' => '/Linux/',
            'Android' => '/Android/',
            'iOS' => '/iPhone|iPad/'
        ];

        foreach ($platforms as $platform => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return $platform;
            }
        }

        return null;
    }

    /**
     * Vérifie si le référent est interne
     */
    private function isInternalReferrer(?string $referrer): bool
    {
        if (!$referrer) return false;

        $appDomain = parse_url(config('app.url'), PHP_URL_HOST);
        $referrerDomain = parse_url($referrer, PHP_URL_HOST);

        return $appDomain === $referrerDomain;
    }

    /**
     * Vérifie si le référent provient d'un moteur de recherche
     */
    private function isSearchEngineReferrer(?string $referrer): bool
    {
        if (!$referrer) return false;

        $searchEngines = [
            'google.', 'bing.', 'yahoo.', 'duckduckgo.',
            'baidu.', 'yandex.', 'ask.', 'aol.'
        ];

        $domain = parse_url($referrer, PHP_URL_HOST);

        foreach ($searchEngines as $engine) {
            if (strpos($domain, $engine) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifie si le référent provient des réseaux sociaux
     */
    private function isSocialMediaReferrer(?string $referrer): bool
    {
        if (!$referrer) return false;

        $socialMedia = [
            'facebook.', 'twitter.', 'linkedin.', 'instagram.',
            'youtube.', 'tiktok.', 'pinterest.', 'reddit.'
        ];

        $domain = parse_url($referrer, PHP_URL_HOST);

        foreach ($socialMedia as $social) {
            if (strpos($domain, $social) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifie si on est en heures de bureau (9h-17h)
     */
    private function isBusinessHours(): bool
    {
        $hour = (int) date('H');
        $dayOfWeek = (int) date('w');

        // Lundi à Vendredi, 9h à 17h
        return $dayOfWeek >= 1 && $dayOfWeek <= 5 && $hour >= 9 && $hour <= 17;
    }
}
