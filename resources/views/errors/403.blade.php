@extends('errors.generic', [
    'statusCode' => 403,
    'title' => 'Accès Non Autorisé',
    'color' => 'red',
    'icon' => 'fa-shield-alt',
    'message' => 'Accès Refusé',
    'description' => 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.',
    'contact_admin' => true
])
