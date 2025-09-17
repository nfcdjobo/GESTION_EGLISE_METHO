@extends('errors.generic', [
    'statusCode' => 503,
    'title' => 'Service Indisponible',
    'color' => 'orange',
    'icon' => 'fa-wrench',
    'message' => 'Maintenance en Cours',
    'description' => 'Le service est temporairement indisponible pour maintenance.'
])

@section('content')
    @parent

    {{-- Section maintenance spécialisée --}}
    @push('specific_sections')
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 mb-8 border border-amber-200">
            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                <i class="fas fa-hard-hat text-amber-500 mr-2"></i>
                Maintenance Programmée
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-semibold text-slate-800 mb-2">Que faisons-nous ?</h4>
                    <ul class="space-y-1 text-slate-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2 text-sm"></i>
                            Mise à jour de sécurité
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2 text-sm"></i>
                            Optimisation des performances
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2 text-sm"></i>
                            Amélioration de la stabilité
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-800 mb-2">Informations</h4>
                    <div class="space-y-2 text-slate-600">
                        <p class="flex items-center">
                            <i class="fas fa-clock text-blue-500 mr-2"></i>
                            <span class="text-sm">Durée estimée: 15-30 minutes</span>
                        </p>
                        <p class="flex items-center">
                            <i class="fas fa-sync text-green-500 mr-2"></i>
                            <span class="text-sm">Retour automatique</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endpush
@endsection
