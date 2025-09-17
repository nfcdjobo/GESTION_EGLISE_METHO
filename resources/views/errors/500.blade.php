@extends('errors.generic', [
    'statusCode' => 500,
    'title' => 'Erreur Serveur',
    'color' => 'red',
    'icon' => 'fa-server',
    'message' => 'Erreur Interne du Serveur',
    'description' => 'Une erreur technique s\'est produite. Nos équipes ont été notifiées.',
])

@section('content')
    @parent

    {{-- Section spécifique pour 500 --}}
    @push('specific_sections')
        <div class="bg-gradient-to-r from-gray-50 to-slate-50 rounded-xl p-6 mb-8 border border-gray-200">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                <i class="fas fa-tools text-gray-500 mr-2"></i>
                Que s'est-il passé ?
            </h3>
            <div class="space-y-3">
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-times text-red-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-800">Erreur de traitement</p>
                        <p class="text-sm text-slate-600">Le serveur n'a pas pu traiter votre requête</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-bell text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-800">Équipe notifiée</p>
                        <p class="text-sm text-slate-600">Nos développeurs travaillent sur une solution</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                        <i class="fas fa-sync text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-800">Réessayez plus tard</p>
                        <p class="text-sm text-slate-600">Le problème devrait être résolu rapidement</p>
                    </div>
                </div>
            </div>
        </div>
    @endpush
@endsection
