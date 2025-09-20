@extends('layouts.private.main')
@section('title', 'Tableau de bord FIMECOs')

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('private.fimecos.index') }}"
                        class="inline-flex items-center justify-center w-10 h-10 bg-white/80 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                        <i class="fas fa-arrow-left text-slate-600"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                            Tableau de bord FIMECOs
                        </h1>
                        <p class="text-slate-500 mt-1">
                            Vue d'ensemble et analyses - {{ \Carbon\Carbon::now()->locale('fr')->format('l d F Y') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="refreshStats()"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-sync mr-2"></i> Actualiser
                    </button>
                    @can('fimecos.export')
                        <button onclick="exporterTableauBord()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Statistiques globales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-piggy-bank text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $statistiques_globales['total_fimecos'] }}</p>
                        <p class="text-sm text-slate-500">Total FIMECOs</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $statistiques_globales['fimecos_actifs'] }}</p>
                        <p class="text-sm text-slate-500">Actifs</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-bullseye text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $statistiques_globales['objectifs_atteints'] }}</p>
                        <p class="text-sm text-slate-500">Objectifs atteints</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-coins text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($statistiques_globales['montant_total_cible'], 0, ',', ' ') }}</p>
                        <p class="text-sm text-slate-500">Cible totale (FCFA)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-money-bill-wave text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($statistiques_globales['montant_total_solde'], 0, ',', ' ') }}</p>
                        <p class="text-sm text-slate-500">Total collecté (FCFA)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-percentage text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($statistiques_globales['progression_globale'], 1) }}%</p>
                        <p class="text-sm text-slate-500">Progression globale</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes et FIMECOs urgents -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Alertes -->
            @if(count($alertes) > 0)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-exclamation-triangle text-orange-600 mr-2"></i>
                            Alertes
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                {{ count($alertes) }}
                            </span>
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        @foreach($alertes as $alerte)
                            <div class="flex items-center p-4 rounded-lg border-l-4
                                @if($alerte['type'] === 'danger') bg-red-50 border-red-400
                                @elseif($alerte['type'] === 'warning') bg-yellow-50 border-yellow-400
                                @else bg-blue-50 border-blue-400 @endif">
                                <div class="flex-shrink-0">
                                    @if($alerte['type'] === 'danger')
                                        <i class="fas fa-exclamation-circle text-red-600"></i>
                                    @elseif($alerte['type'] === 'warning')
                                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                                    @else
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium
                                        @if($alerte['type'] === 'danger') text-red-800
                                        @elseif($alerte['type'] === 'warning') text-yellow-800
                                        @else text-blue-800 @endif">
                                        {{ $alerte['message'] }}
                                    </p>
                                </div>
                                <div class="ml-auto">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($alerte['type'] === 'danger') bg-red-100 text-red-800
                                        @elseif($alerte['type'] === 'warning') bg-yellow-100 text-yellow-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ $alerte['count'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- FIMECOs urgents -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-clock text-red-600 mr-2"></i>
                        FIMECOs urgents
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            {{ count($fimecos_urgents) }}
                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    @if(count($fimecos_urgents) > 0)
                        <div class="space-y-3">
                            @foreach($fimecos_urgents as $fimeco)
                                <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-200">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-exclamation text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">{{ Str::limit($fimeco['nom'], 30) }}</p>
                                            <p class="text-xs text-slate-500">
                                                @if(isset($fimeco['responsable']))
                                                    {{ $fimeco['responsable']['nom'] }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ ucfirst(str_replace('_', ' ', $fimeco['statut_global'])) }}
                                        </span>
                                        <p class="text-xs text-slate-500 mt-1">{{ number_format($fimeco['progression'], 1) }}%</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-3xl text-green-300 mb-3"></i>
                            <p class="text-slate-500">Aucun FIMECO urgent</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Performance mensuelle et Top performeurs -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Performance mensuelle -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                        Performance mensuelle
                    </h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Mois</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Nouveaux</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Objectifs atteints</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach(array_slice($performance_mensuelle, -6) as $mois)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                            {{ \Carbon\Carbon::parse($mois['mois'] . '-01')->format('M Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $mois['nouveaux_fimecos'] }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $mois['objectifs_atteints'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top performeurs -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                        Top performeurs
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ count($top_performeurs) }}
                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    @if(count($top_performeurs) > 0)
                        <div class="space-y-3">
                            @foreach($top_performeurs as $index => $fimeco)
                                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white text-xs font-bold">#{{ $index + 1 }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">{{ Str::limit($fimeco['nom'], 25) }}</p>
                                            <p class="text-xs text-slate-500">
                                                @if(isset($fimeco['responsable']))
                                                    {{ $fimeco['responsable']['nom'] }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="flex items-center">
                                            <i class="fas fa-star text-yellow-500 mr-1"></i>
                                            <span class="text-sm font-bold text-slate-800">{{ number_format($fimeco['progression'], 1) }}%</span>
                                        </div>
                                        <p class="text-xs text-slate-500">{{ number_format($fimeco['montant_solde'], 0, ',', ' ') }} FCFA</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-trophy text-3xl text-yellow-300 mb-3"></i>
                            <p class="text-slate-500">Aucun top performeur pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Graphiques de performance -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-area text-purple-600 mr-2"></i>
                        Analyse des tendances
                    </h2>
                    <div class="flex space-x-2">
                        <button onclick="changeChartPeriod('7d')" class="px-3 py-1 text-xs font-medium bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                            7 jours
                        </button>
                        <button onclick="changeChartPeriod('30d')" class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                            30 jours
                        </button>
                        <button onclick="changeChartPeriod('90d')" class="px-3 py-1 text-xs font-medium bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                            90 jours
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Graphique de progression -->
                    <div class="text-center p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                        <div class="text-3xl font-bold text-blue-600 mb-2">
                            {{ number_format(($statistiques_globales['objectifs_atteints'] / max($statistiques_globales['total_fimecos'], 1)) * 100, 1) }}%
                        </div>
                        <div class="text-sm text-blue-700 font-medium">Taux de réussite</div>
                        <div class="text-xs text-blue-600 mt-1">
                            {{ $statistiques_globales['objectifs_atteints'] }} / {{ $statistiques_globales['total_fimecos'] }} FIMECOs
                        </div>
                    </div>

                    <!-- Collecte moyenne -->
                    <div class="text-center p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                        <div class="text-3xl font-bold text-green-600 mb-2">
                            {{ number_format($statistiques_globales['montant_total_solde'] / max($statistiques_globales['total_fimecos'], 1), 0, ',', ' ') }}
                        </div>
                        <div class="text-sm text-green-700 font-medium">Collecte moyenne (FCFA)</div>
                        <div class="text-xs text-green-600 mt-1">par FIMECO</div>
                    </div>

                    <!-- Progression moyenne -->
                    <div class="text-center p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                        <div class="text-3xl font-bold text-purple-600 mb-2">
                            {{ number_format($statistiques_globales['progression_globale'], 1) }}%
                        </div>
                        <div class="text-sm text-purple-700 font-medium">Progression globale</div>
                        <div class="text-xs text-purple-600 mt-1">tous FIMECOs confondus</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-2xl shadow-lg border border-indigo-200 p-6">
            <h3 class="text-lg font-semibold text-indigo-800 mb-4 flex items-center">
                <i class="fas fa-bolt text-indigo-600 mr-2"></i>
                Actions rapides
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @can('fimecos.create')
                    <a href="{{ route('private.fimecos.create') }}"
                        class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i>
                        Nouveau FIMECO
                    </a>
                @endcan

                @can('fimecos.search')
                    <a href="{{ route('private.fimecos.search') }}"
                        class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-search mr-2"></i>
                        Recherche avancée
                    </a>
                @endcan

                @can('fimecos.export')
                    <button onclick="exporterRapportGlobal()"
                        class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-file-alt mr-2"></i>
                        Rapport global
                    </button>
                @endcan

                <button onclick="window.location.reload()"
                    class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-slate-600 to-gray-600 text-white font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-sync mr-2"></i>
                    Actualiser tout
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function refreshStats() {
                fetch('{{ route("private.fimecos.liveStats") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour les statistiques en temps réel
                        console.log('Statistiques mises à jour', data.data);
                        // Ici vous pouvez mettre à jour les éléments du DOM avec les nouvelles données
                        location.reload(); // Solution simple pour recharger
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de l\'actualisation:', error);
                    alert('Erreur lors de l\'actualisation des statistiques');
                });
            }

            function exporterTableauBord() {
                const format = prompt('Format d\'export (json/excel/pdf):', 'pdf');
                if (format && ['json', 'excel', 'pdf'].includes(format.toLowerCase())) {
                    window.location.href = `{{ route('private.fimecos.export') }}?format=${format.toLowerCase()}&dashboard=1`;
                }
            }

            function exporterRapportGlobal() {
                window.location.href = `{{ route('private.fimecos.export') }}?format=pdf&type=global`;
            }

            function changeChartPeriod(period) {
                // Mettre à jour l'apparence des boutons
                document.querySelectorAll('[onclick^="changeChartPeriod"]').forEach(btn => {
                    btn.className = 'px-3 py-1 text-xs font-medium bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors';
                });
                event.target.className = 'px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors';

                // Ici vous pouvez implémenter la logique pour changer la période des graphiques
                console.log('Période changée vers:', period);
            }

            // Animation des cartes au chargement
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.bg-white\\/80');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });

            // Auto-refresh des statistiques toutes les 5 minutes
            setInterval(function() {
                fetch('{{ route("private.fimecos.liveStats") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mise à jour silencieuse des données
                        console.log('Auto-refresh des statistiques réussi', data.timestamp);
                    }
                })
                .catch(error => {
                    console.log('Auto-refresh échoué:', error);
                });
            }, 300000); // 5 minutes
        </script>
    @endpush
@endsection
