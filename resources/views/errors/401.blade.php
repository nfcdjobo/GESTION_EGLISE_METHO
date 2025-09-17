@extends('errors.generic', [
    'statusCode' => 401,
    'title' => 'Non Authentifié',
    'color' => 'orange',
    'icon' => 'fa-user-lock',
    'message' => 'Authentification Requise',
    'description' => 'Vous devez vous connecter pour accéder à cette ressource.'
])

@section('content')
    @parent

    {{-- Bouton de connexion spécialisé pour 401 --}}
    @push('additional_buttons')
        <a href="{{ route('security.login') ?? '/login' }}"
           class="inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold text-lg rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-1">
            <i class="fas fa-sign-in-alt mr-3"></i>
            Se connecter
        </a>
    @endpush
@endsection
