<?php

// =============================================================================
// 1. CONFIGURATION D'ENVIRONNEMENT POUR PRODUCTION
// =============================================================================

// config/ckeditor.php - Nouveau fichier de configuration
return [
    /*
    |--------------------------------------------------------------------------
    | CKEditor Configuration
    |--------------------------------------------------------------------------
    */

    'enabled' => env('CKEDITOR_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | CDN Configuration
    |--------------------------------------------------------------------------
    */
    'cdn' => [
        'enabled' => env('CKEDITOR_CDN_ENABLED', true),
        'version' => env('CKEDITOR_VERSION', '40.2.0'),
        'url' => env('CKEDITOR_CDN_URL', 'https://cdn.ckeditor.com/ckeditor5'),
        'integrity' => env('CKEDITOR_INTEGRITY', ''), // SRI pour sécurité
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('CKEDITOR_CACHE_ENABLED', true),
        'ttl' => env('CKEDITOR_CACHE_TTL', 3600), // 1 heure
        'key_prefix' => 'ckeditor_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'security' => [
        'max_content_length' => env('CKEDITOR_MAX_CONTENT_LENGTH', 65000),
        'allowed_tags' => [
            'basic' => ['p', 'br', 'strong', 'em', 'u', 'h1', 'h2', 'h3', 'ul', 'ol', 'li', 'a'],
            'advanced' => [
                'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike',
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'ul', 'ol', 'li', 'a', 'blockquote',
                'table', 'thead', 'tbody', 'tr', 'th', 'td',
                'span', 'div'
            ],
            'simple' => ['p', 'br', 'strong', 'em', 'ul', 'ol', 'li']
        ],
        'forbidden_attributes' => ['onclick', 'onload', 'onerror', 'onmouseover'],
        'csp_enabled' => env('CKEDITOR_CSP_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'lazy_loading' => env('CKEDITOR_LAZY_LOADING', true),
        'minify_output' => env('CKEDITOR_MINIFY_OUTPUT', true),
        'gzip_compression' => env('CKEDITOR_GZIP_COMPRESSION', true),
        'async_processing' => env('CKEDITOR_ASYNC_PROCESSING', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring Configuration
    |--------------------------------------------------------------------------
    */
    'monitoring' => [
        'enabled' => env('CKEDITOR_MONITORING_ENABLED', false),
        'log_level' => env('CKEDITOR_LOG_LEVEL', 'warning'),
        'track_usage' => env('CKEDITOR_TRACK_USAGE', false),
        'performance_threshold' => env('CKEDITOR_PERF_THRESHOLD', 1000), // ms
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Configurations
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'language' => 'fr',
        'height' => [
            'simple' => 120,
            'basic' => 200,
            'advanced' => 300,
        ],
        'placeholder' => 'Saisissez votre texte ici...',
    ],
];
