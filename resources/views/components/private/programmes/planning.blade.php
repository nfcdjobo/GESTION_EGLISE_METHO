@extends('layouts.private.main')
@section('title', 'Planning Hebdomadaire')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Planning Hebdomadaire</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.programmes.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Programmes
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">Planning</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('private.programmes.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i> Nouveau Programme
                </a>
                <a href="{{ route('private.programmes.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-list mr-2"></i> Liste Complète
                </a>
                <button type="button" onclick="imprimerPlanning()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>
            </div>
        </div>
    </div>

    <!-- Résumé du planning -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Résumé de la Semaine
            </h2>
            <p class="text-slate-500 mt-1">Planning des programmes réguliers (quotidiens et hebdomadaires)</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $totalProgrammes = collect($planning)->sum(function($jour) {
                        return $jour['programmes']->count();
                    });
                    $programmesActifs = collect($planning)->sum(function($jour) {
                        return $jour['programmes']->where('statut', 'actif')->count();
                    });
                    $joursAvecProgrammes = collect($planning)->filter(function($jour) {
                        return $jour['programmes']->count() > 0;
                    })->count();
                    $typesUniques = collect($planning)->flatMap(function($jour) {
                        return $jour['programmes']->pluck('type_programme');
                    })->unique()->count();
                @endphp

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                        <i class="fas fa-calendar-week text-white text-2xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-slate-800">{{ $totalProgrammes }}</div>
                    <div class="text-sm text-slate-500">Total programmes</div>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                        <i class="fas fa-play text-white text-2xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-slate-800">{{ $programmesActifs }}</div>
                    <div class="text-sm text-slate-500">Programmes actifs</div>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                        <i class="fas fa-calendar-day text-white text-2xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-slate-800">{{ $joursAvecProgrammes }}</div>
                    <div class="text-sm text-slate-500">Jours programmés</div>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                        <i class="fas fa-tags text-white text-2xl"></i>
                    </div>
                    <div class="text-2xl font-bold text-slate-800">{{ $typesUniques }}</div>
                    <div class="text-sm text-slate-500">Types différents</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Planning par jour -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($planning as $numeroJour => $jour)
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            @php
                                $iconsJours = [
                                    1 => 'calendar-day',
                                    2 => 'calendar-day',
                                    3 => 'calendar-day',
                                    4 => 'calendar-day',
                                    5 => 'calendar-day',
                                    6 => 'calendar-day',
                                    7 => 'calendar-week'
                                ];
                                $couleursJours = [
                                    1 => 'text-blue-600',
                                    2 => 'text-green-600',
                                    3 => 'text-purple-600',
                                    4 => 'text-amber-600',
                                    5 => 'text-pink-600',
                                    6 => 'text-cyan-600',
                                    7 => 'text-red-600'
                                ];
                            @endphp
                            <i class="fas fa-{{ $iconsJours[$numeroJour] }} {{ $couleursJours[$numeroJour] }} mr-2"></i>
                            {{ $jour['nom'] }}
                        </h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($jour['programmes']->count() > 0) bg-green-100 text-green-800 @else bg-slate-100 text-slate-800 @endif">
                            {{ $jour['programmes']->count() }}
                            {{ $jour['programmes']->count() > 1 ? 'programmes' : 'programme' }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    @if($jour['programmes']->count() > 0)
                        <div class="space-y-4">
                            @foreach($jour['programmes']->sortBy('heure_debut') as $programme)
                                <div class="border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-2 mb-2">
                                                <h4 class="font-semibold text-slate-900">{{ $programme->nom_programme }}</h4>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $programme->statut_badge }}-100 text-{{ $programme->statut_badge }}-800">
                                                    {{ \App\Models\Programme::STATUTS[$programme->statut] ?? $programme->statut }}
                                                </span>
                                            </div>

                                            @if($programme->heure_debut && $programme->heure_fin)
                                                <div class="flex items-center text-sm text-slate-600 mb-1">
                                                    <i class="fas fa-clock mr-2"></i>
                                                    {{ $programme->horaires }}
                                                </div>
                                            @endif

                                            <div class="flex items-center text-sm text-slate-600 mb-1">
                                                <i class="fas fa-tag mr-2"></i>
                                                {{ \App\Models\Programme::TYPES_PROGRAMME[$programme->type_programme] ?? $programme->type_programme }}
                                            </div>

                                            @if($programme->lieu_principal)
                                                <div class="flex items-center text-sm text-slate-600 mb-1">
                                                    <i class="fas fa-map-marker-alt mr-2"></i>
                                                    {{ $programme->lieu_principal }}
                                                </div>
                                            @endif

                                            @if($programme->responsablePrincipal)
                                                <div class="flex items-center text-sm text-slate-600">
                                                    <i class="fas fa-user mr-2"></i>
                                                    {{ $programme->responsablePrincipal->prenom }} {{ $programme->responsablePrincipal->nom }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex items-center space-x-1 ml-4">
                                            <a href="{{ route('private.programmes.show', $programme) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>

                                            @if($programme->peutEtreModifie())
                                                <a href="{{ route('private.programmes.edit', $programme) }}" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-times text-slate-400 text-xl"></i>
                            </div>
                            <p class="text-slate-500 text-sm">Aucun programme ce jour</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Vue d'ensemble des créneaux horaires -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-clock text-green-600 mr-2"></i>
                Vue Horaire
            </h2>
            <p class="text-slate-500 mt-1">Répartition des programmes par créneaux horaires</p>
        </div>
        <div class="p-6">
            @php
                $creneauxHoraires = collect($planning)->flatMap(function($jour) {
                    return $jour['programmes'];
                })->filter(function($programme) {
                    return $programme->heure_debut && $programme->heure_fin;
                })->groupBy(function($programme) {
                    return $programme->heure_debut->format('H:i');
                })->sortKeys();
            @endphp

            @if($creneauxHoraires->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Heure</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Programmes</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Jours</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($creneauxHoraires as $heure => $programmes)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <div class="font-semibold text-slate-900">{{ $heure }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="space-y-2">
                                            @foreach($programmes as $programme)
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-medium text-slate-900">{{ $programme->nom_programme }}</span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ \App\Models\Programme::TYPES_PROGRAMME[$programme->type_programme] ?? $programme->type_programme }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @php
                                                $joursUniques = $programmes->flatMap(function($programme) {
                                                    return $programme->jours_semaine ?? [];
                                                })->unique()->sort();
                                                $nomsJours = [1 => 'L', 2 => 'M', 3 => 'M', 4 => 'J', 5 => 'V', 6 => 'S', 7 => 'D'];
                                            @endphp
                                            @foreach($joursUniques as $jour)
                                                <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-medium bg-slate-100 text-slate-800 rounded-full">
                                                    {{ $nomsJours[$jour] ?? $jour }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-1">
                                            @if($programmes->count() == 1)
                                                @php $programme = $programmes->first(); @endphp
                                                <a href="{{ route('private.programmes.show', $programme) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                            @else
                                                <span class="text-sm text-slate-600">{{ $programmes->count() }} programmes</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun horaire défini</h3>
                    <p class="text-slate-500">Les programmes n'ont pas d'horaires spécifiés.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Fonction d'impression
function imprimerPlanning() {
    window.print();
}

// Gestion responsive pour mobile
document.addEventListener('DOMContentLoaded', function() {
    // Amélioration de l'affichage mobile
    const cards = document.querySelectorAll('.bg-white\\/80');
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Si on clique sur la carte mais pas sur un bouton, on peut ajouter des interactions
            if (!e.target.closest('a') && !e.target.closest('button')) {
                // Interactions supplémentaires si nécessaire
            }
        });
    });
});

// Style d'impression
const printStyles = `
<style media="print">
    @page {
        margin: 1cm;
        size: landscape;
    }
    .no-print {
        display: none !important;
    }
    body {
        font-size: 12pt;
        line-height: 1.4;
    }
    .bg-gradient-to-r {
        background: #000 !important;
        color: #fff !important;
        -webkit-print-color-adjust: exact;
    }
    .shadow-lg, .shadow-xl {
        box-shadow: none !important;
    }
    .bg-white\\/80 {
        background: #fff !important;
        border: 1px solid #ccc !important;
    }
</style>`;

// Ajouter les styles d'impression
document.head.insertAdjacentHTML('beforeend', printStyles);
</script>

@endsection
