@extends('errors.generic', [
    'statusCode' => 404,
    'title' => 'Page Non Trouvée',
    'color' => 'purple',
    'icon' => 'fa-search',
    'message' => 'Page Introuvable',
    'description' => 'Il est possible que la page ait été déplacée, supprimée ou que l\'adresse soit incorrecte.'
])
