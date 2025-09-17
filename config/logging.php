<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Deprecations Log Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the log channel that should be used to log warnings
    | regarding deprecated PHP and library features. This allows you to get
    | your application ready for upcoming major versions of dependencies.
    |
    */

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
            'replace_placeholders' => true,
        ],




        // ==========================================
        // CHANNELS SPÉCIFIQUES POUR LES ERREURS 404
        // ==========================================

        /**
         * Channel principal pour les erreurs 404
         * Rotation quotidienne avec conservation de 30 jours
         */
        // '404' => [
        //     'driver' => 'daily',
        //     'path' => storage_path('logs/404.log'),
        //     'level' => 'info',
        //     'days' => 30,
        //     'replace_placeholders' => true,
        //     'permission' => 0664,
        // ],

        // Canal spécialisé pour les erreurs 404
        '404' => [
            'driver' => 'daily',
            'path' => storage_path('logs/404.log'),
            'level' => 'info',
            'days' => 30,
            'replace_placeholders' => true,
        ],

        /**
         * Channel pour les erreurs 404 critiques (attaques potentielles)
         * Conservation plus longue pour investigation
         */
        '404_security' => [
            'driver' => 'daily',
            'path' => storage_path('logs/404-security.log'),
            'level' => 'warning',
            'days' => 90,
            'replace_placeholders' => true,
            'permission' => 0664,
        ],

        /**
         * Channel pour l'analyse comportementale des membres
         * Logs des patterns de navigation
         */
        'user_behavior' => [
            'driver' => 'daily',
            'path' => storage_path('logs/user-behavior.log'),
            'level' => 'info',
            'days' => 60,
            'replace_placeholders' => true,
            'permission' => 0664,
        ],

        /**
         * Channel pour les bots et crawlers
         * Séparation des logs humains/bots pour une meilleure analyse
         */
        '404_bots' => [
            'driver' => 'daily',
            'path' => storage_path('logs/404-bots.log'),
            'level' => 'info',
            'days' => 7, // Conservation plus courte pour les bots
            'replace_placeholders' => true,
            'permission' => 0664,
        ],

        /**
         * Channel pour les métriques et statistiques
         * Format structuré pour l'analyse automatique
         */
        '404_metrics' => [
            'driver' => 'daily',
            'path' => storage_path('logs/404-metrics.log'),
            'level' => 'info',
            'days' => 180, // Conservation longue pour les tendances
            'replace_placeholders' => true,
            'permission' => 0664,
            'tap' => [App\Logging\MetricsFormatter::class],
        ],

        /**
         * Channel pour les redirections automatiques
         * Suivi des redirections appliquées
         */
        '404_redirects' => [
            'driver' => 'daily',
            'path' => storage_path('logs/404-redirects.log'),
            'level' => 'info',
            'days' => 30,
            'replace_placeholders' => true,
            'permission' => 0664,
        ],

        /**
         * Channel pour les alertes et notifications
         * Pics de trafic, attaques, etc.
         */
        '404_alerts' => [
            'driver' => 'daily',
            'path' => storage_path('logs/404-alerts.log'),
            'level' => 'warning',
            'days' => 365, // Conservation longue pour les alertes
            'replace_placeholders' => true,
            'permission' => 0664,
        ],

        // ==========================================
        // CHANNELS MULTI-DESTINATIONS
        // ==========================================

        /**
         * Stack pour erreurs 404 avec multiple outputs
         */
        '404_stack' => [
            'driver' => 'stack',
            'channels' => ['404', '404_metrics'],
            'ignore_exceptions' => false,
        ],

        /**
         * Channel pour erreurs 404 avec notification Slack
         * Utile pour les environnements de production
         */
        '404_slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => '404 Monitor',
            'emoji' => ':warning:',
            'level' => 'error',
            'context' => true,
            'include_extra' => true,
        ],

        /**
         * Channel pour erreurs critiques avec email
         */
        '404_mail' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\NativeMailerHandler::class,
            'handler_with' => [
                'to' => env('LOG_MAIL_TO', 'admin@example.com'),
                'subject' => 'Erreurs 404 Critiques',
                'from' => env('LOG_MAIL_FROM', 'noreply@example.com'),
            ],
            'level' => 'critical',
            'processors' => [PsrLogMessageProcessor::class],
        ],









        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
            'replace_placeholders' => true,
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
            'facility' => LOG_USER,
            'replace_placeholders' => true,
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],


    ],

];
