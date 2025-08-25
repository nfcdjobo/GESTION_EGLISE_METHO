@extends('layouts.private.main')
@section('title', 'Statistiques des Projets')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Projets</h1>
            <p class="text-slate-500 mt-1">Analyse et suivi de la performance des projets</p>
        </div>
        <div class="flex gap-2 mt-4 sm:mt-0">
            <a href="{{ route('private.projets.statistiques', ['periode' => 'mois']) }}" class="inline-flex items-center px-4 py-2 {{ request('periode') == 'mois' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700' }} text-sm font-medium rounded-xl hover:bg-blue-700 hover:text-white transition-colors border border-slate-200">
                Ce mois
            </a>
            <a href="{{ route('private.projets.statistiques', ['periode' => 'trimestre']) }}" class="inline-flex items-center px-4 py-2 {{ request('periode') == 'trimestre' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700' }} text-sm font-medium rounded-xl hover:bg-blue-700 hover:text-white transition-colors border border-slate-200">
                Ce trimestre
            </a>
            <a href="{{ route('private.projets.statistiques', ['periode' => 'annee']) }}" class="inline-flex items-center px-4 py-2 {{ request('periode') == 'annee' ? 'bg-blue-600 text-white' : 'bg-white text-slate-700' }} text-sm font-medium rounded-xl hover:bg-blue-700 hover:text-white transition-colors border border-slate-200">
                Cette année
            </a>
            <a href="{{ route('private.projets.statistiques') }}" class="inline-flex items-center px-4 py-2 {{ !request('periode') ? 'bg-blue-600 text-white' : 'bg-white text-slate-700' }} text-sm font-medium rounded-xl hover:bg-blue-700 hover:text-white transition-colors border border-slate-200">
                Tout
            </a>
        </div>
    </div>

    <!-- KPIs principaux -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total des projets -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-project-diagram text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['par_statut']->sum('nombre') }}</p>
                    <p class="text-sm text-slate-500">Total projets</p>
                </div>
            </div>
        </div>

        <!-- Projets en cours -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-play text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['par_statut']->where('statut', 'en_cours')->sum('nombre') }}</p>
                    <p class="text-sm text-slate-500">En cours</p>
                </div>
            </div>
        </div>

        <!-- Projets en retard -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['projets_en_retard'] }}</p>
                    <p class="text-sm text-slate-500">En retard</p>
                </div>
            </div>
        </div>

        <!-- Actions requises -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-bell text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['projets_necessitant_action'] }}</p>
                    <p class="text-sm text-slate-500">Actions requises</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Métriques budgétaires -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-wallet text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Budget Prévu</h3>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($statistiques['budget']['total_prevu'], 0, ',', ' ') }}</p>
                <p class="text-sm text-slate-500 mt-1">XOF</p>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-donate text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Budget Collecté</h3>
                <p class="text-3xl font-bold text-green-600">{{ number_format($statistiques['budget']['total_collecte'], 0, ',', ' ') }}</p>
                <p class="text-sm text-slate-500 mt-1">XOF</p>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Taux de Financement</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $statistiques['budget']['pourcentage_financement_global'] }}%</p>
                <div class="w-full bg-slate-200 rounded-full h-2 mt-3">
                    <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-2 rounded-full" style="width: {{ min($statistiques['budget']['pourcentage_financement_global'], 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Répartition par statut -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                    Répartition par Statut
                </h2>
            </div>
            <div class="p-6">
                <div class="w-full h-64">
                    <canvas id="statutChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($statistiques['par_statut'] as $stat)
                        @php
                            $statutColors = [
                                'conception' => 'bg-gray-500',
                                'planification' => 'bg-blue-500',
                                'recherche_financement' => 'bg-yellow-500',
                                'en_attente' => 'bg-orange-500',
                                'en_cours' => 'bg-green-500',
                                'suspendu' => 'bg-red-500',
                                'termine' => 'bg-emerald-500',
                                'annule' => 'bg-red-600',
                                'archive' => 'bg-slate-500'
                            ];
                        @endphp
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full {{ $statutColors[$stat->statut] ?? 'bg-gray-500' }} mr-2"></div>
                                <span class="text-slate-700 capitalize">{{ str_replace('_', ' ', $stat->statut) }}</span>
                            </div>
                            <span class="font-semibold text-slate-900">{{ $stat->nombre }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Répartition par type -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                    Répartition par Type
                </h2>
            </div>
            <div class="p-6">
                <div class="w-full h-64">
                    <canvas id="typeChart"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-2 text-sm">
                    @foreach($statistiques['par_type']->take(6) as $stat)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-700 capitalize">{{ str_replace('_', ' ', $stat->type_projet) }}</span>
                            <span class="font-semibold text-slate-900">{{ $stat->nombre }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques détaillées par période -->
    @if(isset($statistiques['periode']))
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                    Statistiques de la Période
                    <span class="ml-2 text-sm font-normal text-slate-500">
                        ({{ \Carbon\Carbon::parse($statistiques['periode']['debut'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($statistiques['periode']['fin'])->format('d/m/Y') }})
                    </span>
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-xl">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <p class="text-2xl font-bold text-blue-600">{{ $statistiques['periode']['projets_crees'] }}</p>
                        <p class="text-sm text-blue-800">Projets créés</p>
                    </div>

                    <div class="text-center p-4 bg-green-50 rounded-xl">
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-play text-white"></i>
                        </div>
                        <p class="text-2xl font-bold text-green-600">{{ $statistiques['periode']['projets_demarres'] }}</p>
                        <p class="text-sm text-green-800">Projets démarrés</p>
                    </div>

                    <div class="text-center p-4 bg-emerald-50 rounded-xl">
                        <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <p class="text-2xl font-bold text-emerald-600">{{ $statistiques['periode']['projets_termines'] }}</p>
                        <p class="text-sm text-emerald-800">Projets terminés</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Tableau détaillé par type -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-table text-cyan-600 mr-2"></i>
                Détail par Type de Projet
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Budget Prévu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Budget Collecté</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Completion Moyenne</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Satisfaction</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @foreach($statistiques['par_type'] as $stat)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @php
                                        $typeIcons = [
                                            'construction' => 'fas fa-building',
                                            'renovation' => 'fas fa-tools',
                                            'social' => 'fas fa-users',
                                            'evangelisation' => 'fas fa-cross',
                                            'formation' => 'fas fa-chalkboard-teacher',
                                            'mission' => 'fas fa-globe-americas',
                                            'equipement' => 'fas fa-cogs',
                                            'technologie' => 'fas fa-laptop',
                                            'communautaire' => 'fas fa-hands-helping',
                                            'humanitaire' => 'fas fa-heart',
                                            'education' => 'fas fa-graduation-cap',
                                            'sante' => 'fas fa-heartbeat',
                                        ];
                                    @endphp
                                    <i class="{{ $typeIcons[$stat->type_projet] ?? 'fas fa-project-diagram' }} text-slate-500 mr-3"></i>
                                    <span class="text-sm font-medium text-slate-900 capitalize">{{ str_replace('_', ' ', $stat->type_projet) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $stat->nombre }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                {{ number_format($stat->budget_total_prevu ?? 0, 0, ',', ' ') }} XOF
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">
                                {{ number_format($stat->budget_total_collecte ?? 0, 0, ',', ' ') }} XOF
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-1 bg-slate-200 rounded-full h-2 mr-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stat->completion_moyenne ?? 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-slate-900">{{ round($stat->completion_moyenne ?? 0) }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($stat->satisfaction_moyenne)
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= ($stat->satisfaction_moyenne / 2) ? 'text-yellow-400' : 'text-slate-300' }} mr-1"></i>
                                        @endfor
                                        <span class="ml-2 text-sm text-slate-600">{{ round($stat->satisfaction_moyenne, 1) }}/10</span>
                                    </div>
                                @else
                                    <span class="text-sm text-slate-400">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg text-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold mb-2">Actions Recommandées</h3>
                <div class="space-y-2 text-blue-100">
                    @if($statistiques['projets_en_retard'] > 0)
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span>{{ $statistiques['projets_en_retard'] }} projet(s) en retard nécessitent une attention</span>
                        </div>
                    @endif
                    @if($statistiques['projets_necessitant_action'] > 0)
                        <div class="flex items-center">
                            <i class="fas fa-bell mr-2"></i>
                            <span>{{ $statistiques['projets_necessitant_action'] }} projet(s) nécessitent une action</span>
                        </div>
                    @endif
                    @if($statistiques['budget']['pourcentage_financement_global'] < 50)
                        <div class="flex items-center">
                            <i class="fas fa-coins mr-2"></i>
                            <span>Financement global faible ({{ $statistiques['budget']['pourcentage_financement_global'] }}%)</span>
                        </div>
                    @endif
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('private.projets.index', ['en_retard' => 1]) }}" class="inline-flex items-center px-4 py-2 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Projets en retard
                </a>
                <a href="{{ route('private.projets.index', ['necessite_approbation' => 1]) }}" class="inline-flex items-center px-4 py-2 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                    <i class="fas fa-bell mr-2"></i>Actions requises
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration des couleurs
    const colors = {
        conception: '#6B7280',
        planification: '#3B82F6',
        recherche_financement: '#EAB308',
        en_attente: '#F97316',
        en_cours: '#10B981',
        suspendu: '#EF4444',
        termine: '#059669',
        annule: '#DC2626',
        archive: '#6B7280'
    };

    // Données pour le graphique par statut
    const statutData = @json($statistiques['par_statut']->pluck('nombre', 'statut'));
    const statutLabels = Object.keys(statutData).map(key => key.replace('_', ' '));
    const statutColors = Object.keys(statutData).map(key => colors[key] || '#6B7280');

    // Graphique par statut (Pie Chart)
    const statutCtx = document.getElementById('statutChart').getContext('2d');
    new Chart(statutCtx, {
        type: 'doughnut',
        data: {
            labels: statutLabels,
            datasets: [{
                data: Object.values(statutData),
                backgroundColor: statutColors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Données pour le graphique par type
    const typeData = @json($statistiques['par_type']->pluck('nombre', 'type_projet'));
    const typeLabels = Object.keys(typeData).map(key => key.replace('_', ' '));

    // Graphique par type (Bar Chart)
    const typeCtx = document.getElementById('typeChart').getContext('2d');
    new Chart(typeCtx, {
        type: 'bar',
        data: {
            labels: typeLabels,
            datasets: [{
                label: 'Nombre de projets',
                data: Object.values(typeData),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.parsed.y} projet(s)`;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
