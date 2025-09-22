@extends('layouts.private.main')
@section('title', 'Tableau de Bord - Moissons')

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-slate-600 mb-4">
                <a href="{{ route('private.moissons.index') }}" class="hover:text-blue-600 transition-colors">
                    <i class="fas fa-seedling mr-1"></i> Moissons
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium">Tableau de bord</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Tableau de Bord des Moissons
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Vue d'ensemble des performances et statistiques - {{ now()->format('l d F Y') }}
                    </p>
                </div>

                <div class="flex gap-2">
                    <button onclick="rafraichirDonnees()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i> Actualiser
                    </button>
                    <button onclick="exporterDashboard()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i> Exporter
                    </button>
                </div>
            </div>
        </div>

        <!-- Filtres de période -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
            <div class="flex flex-wrap items-center gap-4">
                <h3 class="font-medium text-slate-800">Période d'analyse :</h3>
                <div class="flex gap-2">
                    <button onclick="setPeriod('7')" class="period-btn px-3 py-2 text-sm rounded-lg border hover:bg-blue-50">7 jours</button>
                    <button onclick="setPeriod('30')" class="period-btn active px-3 py-2 text-sm rounded-lg border bg-blue-100 text-blue-700">30 jours</button>
                    <button onclick="setPeriod('90')" class="period-btn px-3 py-2 text-sm rounded-lg border hover:bg-blue-50">3 mois</button>
                    <button onclick="setPeriod('365')" class="period-btn px-3 py-2 text-sm rounded-lg border hover:bg-blue-50">1 an</button>
                    <button onclick="setPeriod('all')" class="period-btn px-3 py-2 text-sm rounded-lg border hover:bg-blue-50">Tout</button>
                </div>
                <div class="flex items-center gap-2 ml-auto">
                    <input type="date" id="date-debut" class="px-3 py-2 text-sm border rounded-lg">
                    <span class="text-slate-500">à</span>
                    <input type="date" id="date-fin" class="px-3 py-2 text-sm border rounded-lg">
                    <button onclick="appliquerPeriodePersonnalisee()" class="px-3 py-2 text-sm bg-slate-600 text-white rounded-lg hover:bg-slate-700">Appliquer</button>
                </div>
            </div>
        </div>

        <!-- Métriques principales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-seedling text-white text-xl"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-600">Total moissons</p>
                        <p class="text-2xl font-bold text-slate-900" id="total-moissons">{{ $stats['total_moissons'] ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-xs px-2 py-1 bg-blue-100 text-blue-600 rounded-full">
                        +{{ $stats['nouvelles_moissons'] ?? 0 }} ce mois
                    </span>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-coins text-white text-xl"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-600">Total collecté</p>
                        <p class="text-2xl font-bold text-slate-900" id="total-collecte">{{ number_format($stats['total_collecte'] ?? 0, 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-xs px-2 py-1 bg-green-100 text-green-600 rounded-full">
                        {{ $stats['evolution_collecte'] ?? '+0' }}% vs période précédente
                    </span>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bullseye text-white text-xl"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-600">Objectifs atteints</p>
                        <p class="text-2xl font-bold text-slate-900" id="objectifs-atteints">{{ $stats['objectifs_atteints'] ?? 0 }}</p>
                        <p class="text-xs text-slate-500">sur {{ $stats['total_moissons'] ?? 0 }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-1 bg-slate-200 rounded-full h-2 mr-2">
                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $stats['pourcentage_objectifs'] ?? 0 }}%"></div>
                    </div>
                    <span class="text-xs font-medium">{{ number_format($stats['pourcentage_objectifs'] ?? 0, 1) }}%</span>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-slate-600">Performance moyenne</p>
                        <p class="text-2xl font-bold text-slate-900" id="performance-moyenne">{{ number_format($stats['performance_moyenne'] ?? 0, 1) }}%</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-1 bg-slate-200 rounded-full h-2 mr-2">
                        <div class="bg-orange-600 h-2 rounded-full" style="width: {{ min($stats['performance_moyenne'] ?? 0, 100) }}%"></div>
                    </div>
                    <span class="text-xs font-medium">Réalisation</span>
                </div>
            </div>
        </div>

        <!-- Graphiques principaux -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Évolution des collectes -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-area text-blue-600 mr-2"></i>
                        Évolution des collectes
                    </h3>
                </div>
                <div class="p-6">
                    <canvas id="evolutionChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Répartition par type -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                        Répartition par type
                    </h3>
                </div>
                <div class="p-6">
                    <canvas id="repartitionChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Performers et Alertes -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Top Moissons -->
            <div class="lg:col-span-2 bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                        Top Moissons Performantes
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4" id="top-moissons">
                        @foreach($topMoissons ?? [] as $index => $moisson)
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                                <div class="flex items-center space-x-4">
                                    <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-slate-800">{{ Str::limit($moisson->theme, 30) }}</h4>
                                        <p class="text-sm text-slate-500">{{ $moisson->date->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">{{ number_format($moisson->montant_solde, 0, ',', ' ') }} FCFA</p>
                                    <p class="text-sm text-slate-500">{{ number_format($moisson->pourcentage_realise, 1) }}% réalisé</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Alertes et Rappels -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bell text-red-600 mr-2"></i>
                        Alertes & Rappels
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4" id="alertes">
                        <!-- Engagements en retard -->
                        @if(isset($alertes['engagements_retard']) && count($alertes['engagements_retard']) > 0)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                                    <span class="font-medium text-red-700">Engagements en retard</span>
                                </div>
                                <p class="text-sm text-red-600">{{ count($alertes['engagements_retard']) }} engagement(s) dépassent leur échéance</p>
                            </div>
                        @endif

                        <!-- Rappels du jour -->
                        @if(isset($alertes['rappels_jour']) && count($alertes['rappels_jour']) > 0)
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-calendar-check text-orange-500 mr-2"></i>
                                    <span class="font-medium text-orange-700">Rappels du jour</span>
                                </div>
                                <p class="text-sm text-orange-600">{{ count($alertes['rappels_jour']) }} rappel(s) à effectuer aujourd'hui</p>
                            </div>
                        @endif

                        <!-- Objectifs en danger -->
                        @if(isset($alertes['objectifs_danger']) && count($alertes['objectifs_danger']) > 0)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-warning text-yellow-500 mr-2"></i>
                                    <span class="font-medium text-yellow-700">Objectifs en danger</span>
                                </div>
                                <p class="text-sm text-yellow-600">{{ count($alertes['objectifs_danger']) }} moisson(s) sous les 50%</p>
                            </div>
                        @endif

                        <!-- Pas d'alertes -->
                        @if(empty($alertes) || (empty($alertes['engagements_retard']) && empty($alertes['rappels_jour']) && empty($alertes['objectifs_danger'])))
                            <div class="text-center py-6">
                                <i class="fas fa-check-circle text-green-500 text-3xl mb-2"></i>
                                <p class="text-sm text-slate-500">Aucune alerte pour le moment</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau détaillé des moissons récentes -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-table text-purple-600 mr-2"></i>
                        Moissons Récentes
                    </h3>
                    <a href="{{ route('private.moissons.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 transition-colors">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase">Moisson</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase">Objectif</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase">Collecté</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase">Performance</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-slate-700 uppercase">Statut</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-slate-700 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200" id="moissons-recentes">
                        @foreach($moissonRecentes ?? [] as $moisson)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-slate-900">{{ Str::limit($moisson->theme, 40) }}</div>
                                        <div class="text-sm text-slate-500">{{ $moisson->culte->titre ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    {{ $moisson->date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-700">
                                    {{ number_format($moisson->cible, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-green-600">
                                    {{ number_format($moisson->montant_solde, 0, ',', ' ') }} FCFA
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1 bg-slate-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $moisson->pourcentage_realise >= 100 ? 'bg-green-500' : ($moisson->pourcentage_realise >= 70 ? 'bg-blue-500' : 'bg-orange-500') }}"
                                                 style="width: {{ min($moisson->pourcentage_realise, 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-slate-700 w-12">
                                            {{ number_format($moisson->pourcentage_realise, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $moisson->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $moisson->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('private.moissons.show', $moisson) }}"
                                            class="text-cyan-600 hover:text-cyan-700 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('private.moissons.edit', $moisson) }}"
                                            class="text-yellow-600 hover:text-yellow-700 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Statistiques détaillées -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Analyse par composant -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-layer-group text-indigo-600 mr-2"></i>
                        Analyse par Composant
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Passages -->
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-800">Passages</h4>
                                    <p class="text-sm text-slate-600">{{ $statsComposants['passages']['nombre'] ?? 0 }} enregistrements</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-600">{{ number_format($statsComposants['passages']['total'] ?? 0, 0, ',', ' ') }} FCFA</p>
                                <p class="text-sm text-slate-500">{{ number_format($statsComposants['passages']['pourcentage'] ?? 0, 1) }}% du total</p>
                            </div>
                        </div>

                        <!-- Ventes -->
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-store text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-800">Ventes</h4>
                                    <p class="text-sm text-slate-600">{{ $statsComposants['ventes']['nombre'] ?? 0 }} enregistrements</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">{{ number_format($statsComposants['ventes']['total'] ?? 0, 0, ',', ' ') }} FCFA</p>
                                <p class="text-sm text-slate-500">{{ number_format($statsComposants['ventes']['pourcentage'] ?? 0, 1) }}% du total</p>
                            </div>
                        </div>

                        <!-- Engagements -->
                        <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-handshake text-white"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-800">Engagements</h4>
                                    <p class="text-sm text-slate-600">{{ $statsComposants['engagements']['nombre'] ?? 0 }} enregistrements</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-purple-600">{{ number_format($statsComposants['engagements']['total'] ?? 0, 0, ',', ' ') }} FCFA</p>
                                <p class="text-sm text-slate-500">{{ number_format($statsComposants['engagements']['pourcentage'] ?? 0, 1) }}% du total</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tendances et prédictions -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-trending-up text-green-600 mr-2"></i>
                        Tendances & Insights
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Croissance mensuelle -->
                        <div class="border-l-4 border-green-500 pl-4">
                            <h4 class="font-medium text-slate-800 mb-1">Croissance mensuelle</h4>
                            <p class="text-2xl font-bold text-green-600">{{ $tendances['croissance_mensuelle'] ?? '+0' }}%</p>
                            <p class="text-sm text-slate-600">vs mois précédent</p>
                        </div>

                        <!-- Moyenne par moisson -->
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="font-medium text-slate-800 mb-1">Collecte moyenne par moisson</h4>
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($tendances['moyenne_moisson'] ?? 0, 0, ',', ' ') }}</p>
                            <p class="text-sm text-slate-600">FCFA</p>
                        </div>

                        <!-- Meilleur mois -->
                        <div class="border-l-4 border-purple-500 pl-4">
                            <h4 class="font-medium text-slate-800 mb-1">Meilleur mois</h4>
                            <p class="text-2xl font-bold text-purple-600">{{ $tendances['meilleur_mois'] ?? 'N/A' }}</p>
                            <p class="text-sm text-slate-600">{{ number_format($tendances['montant_meilleur_mois'] ?? 0, 0, ',', ' ') }} FCFA collectés</p>
                        </div>

                        <!-- Prochaine échéance importante -->
                        <div class="border-l-4 border-orange-500 pl-4">
                            <h4 class="font-medium text-slate-800 mb-1">Prochaine échéance</h4>
                            <p class="text-lg font-bold text-orange-600">{{ $tendances['prochaine_echeance'] ?? 'Aucune' }}</p>
                            <p class="text-sm text-slate-600">{{ $tendances['montant_echeance'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Chart.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

 

        <script>
            let evolutionChart, repartitionChart;
            let currentPeriod = '30';

            // Configuration des graphiques
            const chartColors = {
                primary: 'rgba(59, 130, 246, 0.8)',
                secondary: 'rgba(16, 185, 129, 0.8)',
                tertiary: 'rgba(139, 92, 246, 0.8)',
                warning: 'rgba(245, 158, 11, 0.8)',
                danger: 'rgba(239, 68, 68, 0.8)'
            };

            // Initialiser les graphiques
            function initCharts() {
                // Données initiales depuis PHP
                const donneesEvolution = @json($donneesGraphiques['evolution'] ?? ['labels' => [], 'data' => []]);
                const donneesRepartition = @json($donneesGraphiques['repartition'] ?? ['labels' => [], 'data' => []]);

                // Graphique d'évolution
                const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
                evolutionChart = new Chart(evolutionCtx, {
                    type: 'line',
                    data: {
                        labels: donneesEvolution.labels || [],
                        datasets: [{
                            label: 'Montant collecté',
                            data: donneesEvolution.data || [],
                            backgroundColor: chartColors.primary,
                            borderColor: chartColors.primary,
                            borderWidth: 2,
                            fill: false,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return new Intl.NumberFormat('fr-FR').format(value) + ' FCFA';
                                    }
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
                                        return new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                                    }
                                }
                            }
                        }
                    }
                });

                // Graphique de répartition
                const repartitionCtx = document.getElementById('repartitionChart').getContext('2d');
                repartitionChart = new Chart(repartitionCtx, {
                    type: 'doughnut',
                    data: {
                        labels: donneesRepartition.labels || [],
                        datasets: [{
                            data: donneesRepartition.data || [],
                            backgroundColor: [
                                chartColors.primary,
                                chartColors.secondary,
                                chartColors.tertiary,
                                chartColors.warning
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': ' + new Intl.NumberFormat('fr-FR').format(context.parsed) + ' FCFA';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Gestion des périodes
            function setPeriod(period) {
                currentPeriod = period;

                // Mettre à jour l'apparence des boutons
                document.querySelectorAll('.period-btn').forEach(btn => {
                    btn.classList.remove('active', 'bg-blue-100', 'text-blue-700');
                    btn.classList.add('hover:bg-blue-50');
                });

                event.target.classList.add('active', 'bg-blue-100', 'text-blue-700');
                event.target.classList.remove('hover:bg-blue-50');

                // Actualiser les données
                chargerDonneesPeriode(period);
            }

            function appliquerPeriodePersonnalisee() {
                const dateDebut = document.getElementById('date-debut').value;
                const dateFin = document.getElementById('date-fin').value;

                if (!dateDebut || !dateFin) {
                    alert('Veuillez sélectionner les deux dates');
                    return;
                }

                if (new Date(dateDebut) > new Date(dateFin)) {
                    alert('La date de début doit être antérieure à la date de fin');
                    return;
                }

                chargerDonneesPeriode('custom', dateDebut, dateFin);
            }

            function chargerDonneesPeriode(period, dateDebut = null, dateFin = null) {
                // Afficher un indicateur de chargement
                const loader = document.createElement('div');
                loader.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                loader.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Actualisation...';
                document.body.appendChild(loader);

                // Paramètres de la requête
                const params = new URLSearchParams();
                if (period === 'custom' && dateDebut && dateFin) {
                    params.append('date_debut', dateDebut);
                    params.append('date_fin', dateFin);
                } else {
                    params.append('periode', period);
                }

                // Appel AJAX pour récupérer les nouvelles données
                fetch(`{{ route('private.moissons.dashboard') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour les métriques principales
                        mettreAJourMetriques(data.statistiques);

                        // Mettre à jour les graphiques
                        mettreAJourGraphiques(data.donnees_graphiques);

                        // Mettre à jour les listes
                        mettreAJourListes(data.top_performers, data.moissons_recentes);
                    } else {
                        alert('Erreur lors du chargement des données');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors du chargement des données');
                })
                .finally(() => {
                    // Supprimer l'indicateur de chargement
                    document.body.removeChild(loader);
                });
            }

            function mettreAJourMetriques(stats) {
                document.getElementById('total-moissons').textContent = stats.total_moissons || 0;
                document.getElementById('total-collecte').textContent = new Intl.NumberFormat('fr-FR').format(stats.total_collecte || 0);
                document.getElementById('objectifs-atteints').textContent = stats.objectifs_atteints || 0;
                document.getElementById('performance-moyenne').textContent = (stats.performance_moyenne || 0).toFixed(1) + '%';
            }

            function mettreAJourGraphiques(donnees) {
                if (donnees && donnees.evolution) {
                    // Mettre à jour le graphique d'évolution
                    evolutionChart.data.labels = donnees.evolution.labels || [];
                    evolutionChart.data.datasets[0].data = donnees.evolution.data || [];
                    evolutionChart.update();
                }

                if (donnees && donnees.repartition) {
                    // Mettre à jour le graphique de répartition
                    repartitionChart.data.labels = donnees.repartition.labels || [];
                    repartitionChart.data.datasets[0].data = donnees.repartition.data || [];
                    repartitionChart.update();
                }
            }

            function mettreAJourListes(topPerformers, moissonRecentes) {
                // Mettre à jour le top des moissons
                if (topPerformers && topPerformers.length > 0) {
                    const topContainer = document.getElementById('top-moissons');
                    if (topContainer) {
                        topContainer.innerHTML = '';
                        topPerformers.forEach((moisson, index) => {
                            const pourcentage = moisson.cible > 0 ? (moisson.montant_solde * 100 / moisson.cible) : 0;
                            const div = document.createElement('div');
                            div.className = 'flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors';
                            div.innerHTML = `
                                <div class="flex items-center space-x-4">
                                    <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                        ${index + 1}
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-slate-800">${moisson.theme.substring(0, 30)}${moisson.theme.length > 30 ? '...' : ''}</h4>
                                        <p class="text-sm text-slate-500">${new Date(moisson.date).toLocaleDateString('fr-FR')}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">${new Intl.NumberFormat('fr-FR').format(moisson.montant_solde)} FCFA</p>
                                    <p class="text-sm text-slate-500">${pourcentage.toFixed(1)}% réalisé</p>
                                </div>
                            `;
                            topContainer.appendChild(div);
                        });
                    }
                }

                // Mettre à jour le tableau des moissons récentes
                if (moissonRecentes && moissonRecentes.length > 0) {
                    const tableContainer = document.getElementById('moissons-recentes');
                    if (tableContainer) {
                        tableContainer.innerHTML = '';
                        moissonRecentes.forEach(moisson => {
                            const pourcentage = moisson.cible > 0 ? (moisson.montant_solde * 100 / moisson.cible) : 0;
                            const progressColor = pourcentage >= 100 ? 'bg-green-500' : (pourcentage >= 70 ? 'bg-blue-500' : 'bg-orange-500');
                            const statusColor = moisson.status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';

                            const tr = document.createElement('tr');
                            tr.className = 'hover:bg-slate-50 transition-colors';
                            tr.innerHTML = `
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium text-slate-900">${moisson.theme.substring(0, 40)}${moisson.theme.length > 40 ? '...' : ''}</div>
                                        <div class="text-sm text-slate-500">${moisson.culte?.titre || 'N/A'}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-700">
                                    ${new Date(moisson.date).toLocaleDateString('fr-FR')}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-700">
                                    ${new Intl.NumberFormat('fr-FR').format(moisson.cible)} FCFA
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-green-600">
                                    ${new Intl.NumberFormat('fr-FR').format(moisson.montant_solde)} FCFA
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1 bg-slate-200 rounded-full h-2">
                                            <div class="h-2 rounded-full ${progressColor}" style="width: ${Math.min(pourcentage, 100)}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-slate-700 w-12">
                                            ${pourcentage.toFixed(1)}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColor}">
                                        ${moisson.status ? 'Active' : 'Inactive'}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="/private/moissons/${moisson.id}" class="text-cyan-600 hover:text-cyan-700 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/private/moissons/${moisson.id}/edit" class="text-yellow-600 hover:text-yellow-700 transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            `;
                            tableContainer.appendChild(tr);
                        });
                    }
                }
            }

            function rafraichirDonnees() {
                chargerDonneesPeriode(currentPeriod);
            }

            function exporterDashboard() {
                const format = prompt('Format d\'export (excel/pdf):', 'excel');
                if (format && ['excel', 'pdf'].includes(format.toLowerCase())) {
                    window.location.href = `{{ route('private.moissons.dashboard') }}/export?format=${format.toLowerCase()}&periode=${currentPeriod}`;
                }
            }

            // Initialisation au chargement de la page
            document.addEventListener('DOMContentLoaded', function() {
                initCharts();

                // Animation des cartes au chargement
                const cards = document.querySelectorAll('.bg-white\\/80');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    // card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        // card.style.transform = 'translateY(0)';
                    }, index * 50);
                });

                // Définir les dates par défaut
                const today = new Date();
                const thirtyDaysAgo = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);

                document.getElementById('date-fin').value = today.toISOString().split('T')[0];
                document.getElementById('date-debut').value = thirtyDaysAgo.toISOString().split('T')[0];

                // Actualisation automatique toutes les 5 minutes
                setInterval(rafraichirDonnees, 300000);
            });
        </script>
    @endpush
@endsection
