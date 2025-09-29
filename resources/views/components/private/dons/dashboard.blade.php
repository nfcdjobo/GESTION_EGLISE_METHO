@extends('layouts.private.main')
@section('title', 'Dashboard des Dons')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->

    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
            Dashboard des Dons
        </h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.dons.index') }}"
                        class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-dove mr-2"></i>
                        Donations
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Dashboard des Dons</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                {{-- @can('dons.create')
                    <a href="{{ route('private.dons.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Nouveau Don
                    </a>
                @endcan --}}
                @can('dons.read')
                    <a href="{{ route('private.dons.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-list mr-2"></i> Voir tous les dons
                    </a>
                @endcan
                @can('dons.statistics')
                    <a href="{{ route('private.dons.statistiques') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-line mr-2"></i> Statistiques avancées
                    </a>
                @endcan
                @can('dons.export')
                    <a href="{{ route('private.dons.exporter') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i> Exporter
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Aujourd'hui -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-day text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['aujourd_hui']['total_dons'] }}</p>
                    <p class="text-sm text-slate-500">Dons aujourd'hui</p>
                    <p class="text-lg font-semibold text-green-600">{{ number_format($statistiques['aujourd_hui']['montant_total'], 0, ',', ' ') }}</p>
                </div>
            </div>
        </div>

        <!-- Cette semaine -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-week text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['cette_semaine']['total_dons'] }}</p>
                    <p class="text-sm text-slate-500">Cette semaine</p>
                    <p class="text-lg font-semibold text-green-600">{{ number_format($statistiques['cette_semaine']['montant_total'], 0, ',', ' ') }}</p>
                </div>
            </div>
        </div>

        <!-- Ce mois -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['ce_mois']['total_dons'] }}</p>
                    <p class="text-sm text-slate-500">Ce mois</p>
                    <p class="text-lg font-semibold text-green-600">{{ number_format($statistiques['ce_mois']['montant_total'], 0, ',', ' ') }}</p>
                </div>
            </div>
        </div>

        <!-- Cette année -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['cette_annee']['total_dons'] }}</p>
                    <p class="text-sm text-slate-500">Cette année</p>
                    <p class="text-lg font-semibold text-green-600">{{ number_format($statistiques['cette_annee']['montant_total'], 0, ',', ' ') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Objectifs et évolution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Objectifs -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-bullseye text-red-600 mr-2"></i>
                    Objectifs
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Objectif mensuel -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Objectif mensuel</span>
                        <span class="text-sm text-slate-500">{{ $objectifs['mensuel']['pourcentage'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-500 to-cyan-500 h-3 rounded-full transition-all duration-300"
                             style="width: {{ min($objectifs['mensuel']['pourcentage'], 100) }}%"></div>
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs text-slate-500">
                        <span>{{ number_format($objectifs['mensuel']['atteint'], 0, ',', ' ') }}</span>
                        <span>{{ number_format($objectifs['mensuel']['objectif'], 0, ',', ' ') }}</span>
                    </div>
                </div>

                <!-- Objectif annuel -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Objectif annuel</span>
                        <span class="text-sm text-slate-500">{{ $objectifs['annuel']['pourcentage'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-3 rounded-full transition-all duration-300"
                             style="width: {{ min($objectifs['annuel']['pourcentage'], 100) }}%"></div>
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs text-slate-500">
                        <span>{{ number_format($objectifs['annuel']['atteint'], 0, ',', ' ') }}</span>
                        <span>{{ number_format($objectifs['annuel']['objectif'], 0, ',', ' ') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Évolution (30 derniers jours) -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-indigo-600 mr-2"></i>
                    Évolution (30 derniers jours)
                </h2>
            </div>
            <div class="p-6">
                @if($evolution->count() > 0)
                    <div class="space-y-3">
                        @foreach($evolution->take(10) as $jour)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <span class="text-sm text-slate-700">{{ \Carbon\Carbon::parse($jour->date)->format('d/m') }}</span>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-slate-900">{{ $jour->total }} don(s)</div>
                                    <div class="text-xs text-green-600">{{ number_format($jour->montant, 0, ',', ' ') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-line text-3xl text-slate-400 mb-3"></i>
                        <p class="text-slate-500">Aucune donnée d'évolution disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Répartition et Top opérateurs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Répartition par devise -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-coins text-yellow-600 mr-2"></i>
                    Répartition par devise
                </h2>
            </div>
            <div class="p-6">
                @if($parDevise->count() > 0)
                    <div class="space-y-4">
                        @foreach($parDevise as $devise)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @switch($devise->devise)
                                            @case('XOF') bg-orange-100 text-orange-800 @break
                                            @case('EUR') bg-blue-100 text-blue-800 @break
                                            @case('USD') bg-green-100 text-green-800 @break
                                            @default bg-gray-100 text-gray-800 @break
                                        @endswitch">
                                        {{ $devise->devise }}
                                    </span>
                                    <span class="text-sm text-slate-700">{{ $devise->nombre }} don(s)</span>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-slate-900">{{ number_format($devise->total, 0, ',', ' ') }}</div>
                                    <div class="text-xs text-slate-500">Moy: {{ number_format($devise->moyenne, 0, ',', ' ') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-coins text-3xl text-slate-400 mb-3"></i>
                        <p class="text-slate-500">Aucune donnée de devise disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top opérateurs -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-trophy text-amber-600 mr-2"></i>
                    Top opérateurs
                </h2>
            </div>
            <div class="p-6">
                @if($topOperateurs->count() > 0)
                    <div class="space-y-4">
                        @foreach($topOperateurs as $index => $operateur)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm
                                        @if($index === 0) bg-yellow-100 text-yellow-800
                                        @elseif($index === 1) bg-gray-100 text-gray-800
                                        @elseif($index === 2) bg-orange-100 text-orange-800
                                        @else bg-slate-100 text-slate-800
                                        @endif">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $operateur->operateur }}</div>
                                        <div class="text-sm text-slate-500">{{ $operateur->nombre }} don(s)</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-green-600">{{ number_format($operateur->total, 0, ',', ' ') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-trophy text-3xl text-slate-400 mb-3"></i>
                        <p class="text-slate-500">Aucun opérateur disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Derniers dons -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-clock text-green-600 mr-2"></i>
                    Derniers dons
                </h2>
                @can('dons.read')
                    <a href="{{ route('private.dons.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Voir tous <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                @endcan
            </div>
        </div>
        <div class="p-6">
            @if($derniersDons->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Donateur</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Montant</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Opérateur</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($derniersDons as $don)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-2">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                                <span class="text-white text-xs font-medium">
                                                    {{ substr($don->prenom_donateur, 0, 1) }}{{ substr($don->nom_donateur, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="font-medium text-slate-900">{{ $don->nom_complet }}</div>
                                                <div class="text-xs text-slate-500">{{ $don->telephone_1 }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-semibold text-green-600">{{ $don->montant_formate }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-slate-900">{{ $don->parametreDon->operateur ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-slate-900">{{ $don->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-slate-500">{{ $don->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center space-x-1">
                                            @can('dons.read')
                                                <a href="{{ route('private.dons.show', $don) }}" class="inline-flex items-center justify-center w-7 h-7 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </a>
                                            @endcan
                                            @can('dons.update')
                                                <a href="{{ route('private.dons.edit', $don) }}" class="inline-flex items-center justify-center w-7 h-7 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                    <i class="fas fa-edit text-xs"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-heart text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun don enregistré</h3>
                    <p class="text-slate-500 mb-4">Commencez par enregistrer votre premier don.</p>
                    @can('dons.create')
                        <a href="{{ route('private.dons.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Enregistrer un don
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Auto-refresh (toutes les 5 minutes) -->
<script>
// Auto-refresh du dashboard toutes les 5 minutes
setTimeout(function() {
    location.reload();
}, 300000); // 5 minutes

// Mise à jour des compteurs en temps réel (si WebSocket disponible)
// TODO: Implémenter WebSocket pour les mises à jour en temps réel

// Animation des barres de progression
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.bg-gradient-to-r');
    progressBars.forEach(function(bar) {
        if (bar.style.width) {
            bar.style.width = '0%';
            setTimeout(function() {
                bar.style.width = bar.dataset.width || bar.getAttribute('data-width');
            }, 100);
        }
    });
});

// Fonction pour formater les nombres
function formatNumber(num) {
    return new Intl.NumberFormat('fr-FR').format(num);
}

// Actualisation partielle via AJAX (optionnel)
function refreshStats() {
    fetch(window.location.href, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour les statistiques sans recharger la page
            updateStatsDisplay(data.data);
        }
    })
    .catch(error => {
        console.log('Erreur lors de la mise à jour:', error);
    });
}

function updateStatsDisplay(stats) {
    // Fonction pour mettre à jour l'affichage des statistiques
    // Implementation selon la structure des données retournées
}
</script>

@endsection
