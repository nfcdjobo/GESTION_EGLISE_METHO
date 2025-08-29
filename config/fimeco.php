<?php
// config/fimeco.php

return [
    // Montant minimum pour une souscription
    'montant_minimum_souscription' => 10.00,

    // Montant minimum pour un paiement
    'montant_minimum_paiement' => 5.00,

    // Types de paiement autorisés
    'types_paiement_autorises' => [
        'especes' => 'Espèces',
        'cheque' => 'Chèque',
        'virement' => 'Virement bancaire',
        'carte' => 'Carte bancaire',
        'mobile_money' => 'Mobile Money'
    ],

    // Types de paiement à validation automatique
    'paiements_auto_valides' => [
        'especes',
        'mobile_money'
    ],

    // Délai de grâce avant qu'une souscription soit en retard (en jours)
    'delai_grace_retard' => 7,

    // Notifications
    'notifications' => [
        'rappel_paiement_jours' => [7, 3, 1], // Rappels à 7, 3 et 1 jour avant échéance
        'admin_email' => env('FIMECO_ADMIN_EMAIL', 'admin@eglise.com')
    ],

    // Rapports
    'rapports' => [
        'cache_duration' => 3600, // 1 heure
        'export_formats' => ['pdf', 'excel', 'csv']
    ]
];
