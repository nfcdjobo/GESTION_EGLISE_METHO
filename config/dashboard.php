<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration du Dashboard de l'Église
    |--------------------------------------------------------------------------
    |
    | Ce fichier contient les paramètres de configuration pour le dashboard
    | de gestion de l'église.
    |
    */

    // Informations générales de l'église
    'church' => [
        'name' => env('CHURCH_NAME', 'Église Évangélique'),
        'address' => env('CHURCH_ADDRESS', 'Abidjan, Côte d\'Ivoire'),
        'phone' => env('CHURCH_PHONE', '+225 XX XX XX XX'),
        'email' => env('CHURCH_EMAIL', 'contact@eglise.ci'),
        'logo' => env('CHURCH_LOGO', '/images/logo.png'),
        'timezone' => env('APP_TIMEZONE', 'Africa/Abidjan'),
    ],

    // Paramètres d'affichage du dashboard
    'display' => [
        'refresh_interval' => 300000, // 5 minutes en millisecondes
        'items_per_widget' => 5,
        'recent_transactions_limit' => 10,
        'upcoming_events_limit' => 5,
        'chart_months' => 6,
        'show_animations' => true,
        'show_tooltips' => true,
    ],

    // Couleurs et thèmes
    'theme' => [
        'primary_color' => '#007bff',
        'secondary_color' => '#6c757d',
        'success_color' => '#28a745',
        'warning_color' => '#ffc107',
        'danger_color' => '#dc3545',
        'info_color' => '#17a2b8',
        'dark_color' => '#343a40',
        'light_color' => '#f8f9fa',
    ],

    // Statistiques à afficher
    'stats' => [
        'show_members' => true,
        'show_events' => true,
        'show_offerings' => true,
        'show_projects' => true,
        'show_classes' => true,
        'show_meetings' => true,
    ],

    // Widgets activés
    'widgets' => [
        'upcoming_events' => true,
        'today_meetings' => true,
        'recent_transactions' => true,
        'active_announcements' => true,
        'active_projects' => true,
        'upcoming_cultes' => true,
        'performance_indicators' => true,
        'ministry_stats' => true,
        'offerings_chart' => true,
    ],

    // Notifications et alertes
    'alerts' => [
        'show_unprepared_meetings' => true,
        'show_delayed_projects' => true,
        'show_expiring_announcements' => true,
        'show_low_attendance' => true,
        'meeting_preparation_days' => 3,
        'announcement_expiry_days' => 2,
        'project_delay_threshold' => 0, // jours de retard
    ],

    // Permissions et accès
    'permissions' => [
        'view_financial_data' => 'view_finances',
        'view_member_data' => 'view_members',
        'view_admin_data' => 'admin_access',
        'manage_dashboard' => 'manage_dashboard',
    ],

    // Formats d'affichage
    'formats' => [
        'date_format' => 'd/m/Y',
        'datetime_format' => 'd/m/Y H:i',
        'time_format' => 'H:i',
        'currency_format' => 'XOF',
        'number_format' => [
            'decimals' => 0,
            'decimal_separator' => ',',
            'thousands_separator' => ' ',
        ],
    ],

    // Graphiques et diagrammes
    'charts' => [
        'offerings' => [
            'type' => 'line',
            'months' => 6,
            'currency' => 'XOF',
            'show_grid' => true,
            'show_legend' => true,
            'animate' => true,
        ],
        'attendance' => [
            'type' => 'bar',
            'days' => 30,
            'show_average' => true,
        ],
        'projects' => [
            'type' => 'doughnut',
            'show_percentages' => true,
        ],
    ],

    // Cache et performance
    'cache' => [
        'enabled' => env('DASHBOARD_CACHE_ENABLED', true),
        'ttl' => env('DASHBOARD_CACHE_TTL', 300), // 5 minutes
        'prefix' => 'dashboard_',
        'stats_cache_key' => 'dashboard_main_stats',
        'ministry_cache_key' => 'dashboard_ministry_stats',
    ],

    // Modules disponibles
    'modules' => [
        'members' => [
            'enabled' => true,
            'icon' => 'fa-users',
            'route' => 'admin.users.index',
            'color' => 'yellow',
        ],
        'cultes' => [
            'enabled' => true,
            'icon' => 'fa-church',
            'route' => 'admin.cultes.index',
            'color' => 'blue',
        ],
        'events' => [
            'enabled' => true,
            'icon' => 'fa-calendar',
            'route' => 'admin.events.index',
            'color' => 'green',
        ],
        'finances' => [
            'enabled' => true,
            'icon' => 'fa-money',
            'route' => 'admin.transactions.index',
            'color' => 'red',
        ],
        'projects' => [
            'enabled' => true,
            'icon' => 'fa-tasks',
            'route' => 'admin.projets.index',
            'color' => 'purple',
        ],
        'classes' => [
            'enabled' => true,
            'icon' => 'fa-graduation-cap',
            'route' => 'admin.classes.index',
            'color' => 'orange',
        ],
    ],

    // Raccourcis rapides
    'quick_actions' => [
        [
            'title' => 'Nouveau Membre',
            'icon' => 'fa-user-plus',
            'route' => 'admin.users.create',
            'color' => 'success',
            'permission' => 'create_members',
        ],
        [
            'title' => 'Nouvel Événement',
            'icon' => 'fa-calendar-plus',
            'route' => 'admin.events.create',
            'color' => 'info',
            'permission' => 'create_events',
        ],
        [
            'title' => 'Transaction',
            'icon' => 'fa-money',
            'route' => 'admin.transactions.create',
            'color' => 'warning',
            'permission' => 'create_transactions',
        ],
        [
            'title' => 'Nouveau Projet',
            'icon' => 'fa-plus',
            'route' => 'admin.projets.create',
            'color' => 'primary',
            'permission' => 'create_projects',
        ],
    ],

    // Configuration des rapports
    'reports' => [
        'auto_generate' => false,
        'schedule' => 'weekly', // daily, weekly, monthly
        'email_recipients' => [], // emails des destinataires
        'include_attachments' => true,
        'format' => 'pdf', // pdf, excel, both
    ],

    // Paramètres de sécurité
    'security' => [
        'session_timeout' => 120, // minutes
        'max_login_attempts' => 5,
        'password_expiry_days' => 90,
        'require_2fa' => false,
        'audit_dashboard_access' => true,
    ],

    // Intégrations externes
    'integrations' => [
        'google_calendar' => [
            'enabled' => false,
            'calendar_id' => env('GOOGLE_CALENDAR_ID'),
        ],
        'email_service' => [
            'enabled' => true,
            'provider' => env('MAIL_MAILER', 'smtp'),
        ],
        'sms_service' => [
            'enabled' => false,
            'provider' => env('SMS_PROVIDER'),
        ],
        'payment_gateway' => [
            'enabled' => false,
            'provider' => env('PAYMENT_PROVIDER'),
        ],
    ],

    // Backup et maintenance
    'maintenance' => [
        'backup_frequency' => 'daily',
        'cleanup_logs_days' => 30,
        'optimize_database' => 'weekly',
        'maintenance_mode_message' => 'Système en maintenance. Retour prévu dans quelques minutes.',
    ],

    // Localisation
    'localization' => [
        'default_locale' => 'fr',
        'available_locales' => ['fr', 'en'],
        'timezone_display' => true,
        'currency_symbol' => 'XOF',
    ],

    // Personnalisation avancée
    'customization' => [
        'custom_css' => '',
        'custom_js' => '',
        'logo_url' => '',
        'favicon_url' => '',
        'footer_text' => 'Plateforme de Gestion d\'Église',
        'show_powered_by' => true,
    ],
];
