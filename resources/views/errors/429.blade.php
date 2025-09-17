@extends('errors.generic', [
    'statusCode' => 429,
    'title' => 'Trop de Requêtes',
    'color' => 'red',
    'icon' => 'fa-tachometer-alt',
    'message' => 'Limitation de Débit',
    'description' => 'Vous avez effectué trop de requêtes. Veuillez patienter.',
])
