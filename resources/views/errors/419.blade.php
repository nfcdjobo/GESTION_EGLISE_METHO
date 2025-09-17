@extends('errors.generic', [
    'statusCode' => 419,
    'title' => 'Session Expirée',
    'color' => 'blue',
    'icon' => 'fa-clock',
    'message' => 'Session Expirée',
    'description' => 'Votre session a expiré pour des raisons de sécurité.',
    'refresh_required' => true
])
