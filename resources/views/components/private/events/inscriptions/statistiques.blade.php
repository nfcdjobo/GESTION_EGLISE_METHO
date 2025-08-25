@extends('layouts.private.main')
@section('title', 'Statistiques des Inscriptions - ' . $event->titre)

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Inscriptions</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.events.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Événements
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <a href="{{ route('private.events.show', $event) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                    {{ Str::limit($event->titre, 20) }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <a href="{{ route('private.events.inscriptions', $event) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                    Inscriptions
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">Statistiques</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <p class="text-slate-600 mt-1">Analyse détaillée des inscriptions - {{ $event->titre }}</p>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('private.events.inscriptions', $event) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-users mr-2"></i> Gérer les inscriptions
                </a>

                <a href="{{ route('private.events.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à l'événement
                </a>

                <button onclick="exportStatistics()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i> Exporter
                </button>

                <button onclick="printStatistics()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>
            </div>
        </div>
    </div>

    <!-- Métriques principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-plus text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-3xl font-bold text-slate-800">{{ $statistiques['totaux']['total_inscriptions'] }}</p>
                    <p class="text-sm text-slate-500">Total inscriptions</p>
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
                    <p class="text-3xl font-bold text-slate-800">{{ $statistiques['totaux']['inscriptions_actives'] }}</p>
                    <p class="text-sm text-slate-500">Actives</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-times-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-3xl font-bold text-slate-800">{{ $statistiques['totaux']['inscriptions_annulees'] }}</p>
                    <p class="text-sm text-slate-500">Annulées</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-gray-500 to-slate-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-trash text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-3xl font-bold text-slate-800">{{ $statistiques['totaux']['inscriptions_supprimees'] }}</p>
                    <p class="text-sm text-slate-500">Supprimées</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Informations sur la capacité -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                    Capacité et Remplissage
                </h2>
            </div>
            <div class="p-6">
                @if($statistiques['capacite']['capacite_totale'])
                    <div class="space-y-6">
                        <!-- Graphique en anneau -->
                        <div class="relative">
                            <div class="flex items-center justify-center">
                                <div class="relative w-40 h-40">
                                    <svg class="w-40 h-40 transform -rotate-90" viewBox="0 0 144 144">
                                        <!-- Cercle de fond -->
                                        <circle cx="72" cy="72" r="60" fill="none" stroke="rgb(226, 232, 240)" stroke-width="12"></circle>
                                        <!-- Cercle de progression -->
                                        <circle cx="72" cy="72" r="60" fill="none" stroke="rgb(59, 130, 246)" stroke-width="12"
                                                stroke-dasharray="{{ 2 * pi() * 60 }}"
                                                stroke-dashoffset="{{ 2 * pi() * 60 * (1 - ($statistiques['capacite']['taux_remplissage'] ?? 0) / 100) }}"
                                                stroke-linecap="round"></circle>
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <div class="text-3xl font-bold text-slate-800">{{ $statistiques['capacite']['taux_remplissage'] ?? 0 }}%</div>
                                        <div class="text-sm text-slate-500">Remplissage</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Détails capacité -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-blue-50 rounded-xl">
                                <div class="text-2xl font-bold text-blue-600">{{ $statistiques['capacite']['capacite_totale'] }}</div>
                                <div class="text-sm text-blue-800">Capacité totale</div>
                            </div>
                            <div class="text-center p-4 bg-green-50 rounded-xl">
                                <div class="text-2xl font-bold text-green-600">{{ $statistiques['capacite']['places_occupees'] }}</div>
                                <div class="text-sm text-green-800">Places occupées</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-orange-50 rounded-xl">
                                <div class="text-2xl font-bold text-orange-600">{{ $statistiques['capacite']['places_restantes'] }}</div>
                                <div class="text-sm text-orange-800">Places restantes</div>
                            </div>
                            <div class="text-center p-4 bg-purple-50 rounded-xl">
                                <div class="text-2xl font-bold text-purple-600">{{ $statistiques['capacite']['liste_attente_activee'] ? 'Oui' : 'Non' }}</div>
                                <div class="text-sm text-purple-800">Liste d'attente</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-infinity text-2xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-slate-900 mb-2">Capacité illimitée</h3>
                        <p class="text-slate-500">Cet événement n'a pas de limite de capacité définie</p>
                        <div class="mt-4 p-4 bg-green-50 rounded-xl">
                            <div class="text-2xl font-bold text-green-600">{{ $statistiques['capacite']['places_occupees'] }}</div>
                            <div class="text-sm text-green-800">Inscriptions actives</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tendances temporelles -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Tendances Temporelles
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Inscriptions récentes -->
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Activité récente</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-cyan-50 rounded-xl">
                            <div class="text-2xl font-bold text-cyan-600">{{ $statistiques['temporel']['inscriptions_derniere_semaine'] }}</div>
                            <div class="text-sm text-cyan-800">Cette semaine</div>
                        </div>
                        <div class="text-center p-4 bg-indigo-50 rounded-xl">
                            <div class="text-2xl font-bold text-indigo-600">{{ $statistiques['temporel']['inscriptions_derniere_24h'] }}</div>
                            <div class="text-sm text-indigo-800">Dernières 24h</div>
                        </div>
                    </div>
                </div>

                <!-- Annulations récentes -->
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Annulations récentes</h3>
                    <div class="text-center p-4 bg-red-50 rounded-xl">
                        <div class="text-2xl font-bold text-red-600">{{ $statistiques['temporel']['annulations_derniere_semaine'] }}</div>
                        <div class="text-sm text-red-800">Cette semaine</div>
                    </div>
                </div>

                <!-- Taux d'annulation -->
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Taux d'annulation</h3>
                    @php
                        $tauxAnnulation = $statistiques['totaux']['total_inscriptions'] > 0
                            ? round(($statistiques['totaux']['inscriptions_annulees'] / $statistiques['totaux']['total_inscriptions']) * 100, 1)
                            : 0;
                    @endphp
                    <div class="relative">
                        <div class="w-full bg-gray-200 rounded-full h-4">
                            <div class="bg-red-500 h-4 rounded-full" style="width: {{ $tauxAnnulation }}%"></div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-sm text-slate-600">0%</span>
                            <span class="text-sm font-semibold text-red-600">{{ $tauxAnnulation }}%</span>
                            <span class="text-sm text-slate-600">100%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques détaillés -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Répartition des statuts -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-donut text-amber-600 mr-2"></i>
                    Répartition des Statuts
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @php
                        $total = $statistiques['totaux']['total_inscriptions'];
                    @endphp

                    <!-- Actives -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                            <span class="text-slate-700">Inscriptions actives</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-slate-900">{{ $statistiques['totaux']['inscriptions_actives'] }}</span>
                            <span class="text-sm text-slate-500">({{ $total > 0 ? round(($statistiques['totaux']['inscriptions_actives'] / $total) * 100, 1) : 0 }}%)</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ $total > 0 ? ($statistiques['totaux']['inscriptions_actives'] / $total) * 100 : 0 }}%"></div>
                    </div>

                    <!-- Annulées -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                            <span class="text-slate-700">Inscriptions annulées</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-slate-900">{{ $statistiques['totaux']['inscriptions_annulees'] }}</span>
                            <span class="text-sm text-slate-500">({{ $total > 0 ? round(($statistiques['totaux']['inscriptions_annulees'] / $total) * 100, 1) : 0 }}%)</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $total > 0 ? ($statistiques['totaux']['inscriptions_annulees'] / $total) * 100 : 0 }}%"></div>
                    </div>

                    <!-- Supprimées -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-gray-500 rounded-full"></div>
                            <span class="text-slate-700">Inscriptions supprimées</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-slate-900">{{ $statistiques['totaux']['inscriptions_supprimees'] }}</span>
                            <span class="text-sm text-slate-500">({{ $total > 0 ? round(($statistiques['totaux']['inscriptions_supprimees'] / $total) * 100, 1) : 0 }}%)</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-gray-500 h-2 rounded-full" style="width: {{ $total > 0 ? ($statistiques['totaux']['inscriptions_supprimees'] / $total) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicateurs de performance -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-tachometer-alt text-teal-600 mr-2"></i>
                    Indicateurs de Performance
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Taux de conversion -->
                @php
                    $tauxConversion = $statistiques['totaux']['total_inscriptions'] > 0
                        ? round(($statistiques['totaux']['inscriptions_actives'] / $statistiques['totaux']['total_inscriptions']) * 100, 1)
                        : 0;
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Taux de conversion</span>
                        <span class="text-sm font-bold text-green-600">{{ $tauxConversion }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full" style="width: {{ $tauxConversion }}%"></div>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Pourcentage d'inscriptions actives par rapport au total</p>
                </div>

                <!-- Fidélité -->
                @php
                    $tauxFidelite = $statistiques['totaux']['inscriptions_actives'] > 0
                        ? round((($statistiques['totaux']['inscriptions_actives'] - $statistiques['temporel']['annulations_derniere_semaine']) / $statistiques['totaux']['inscriptions_actives']) * 100, 1)
                        : 100;
                    $tauxFidelite = max(0, $tauxFidelite);
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Taux de fidélité</span>
                        <span class="text-sm font-bold text-blue-600">{{ $tauxFidelite }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full" style="width: {{ $tauxFidelite }}%"></div>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Inscriptions maintenues sans annulation récente</p>
                </div>

                <!-- Croissance -->
                @php
                    $croissance = $statistiques['temporel']['inscriptions_derniere_semaine'] > 0 ? '+' : '';
                    $croissance .= $statistiques['temporel']['inscriptions_derniere_semaine'];
                @endphp
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Croissance hebdomadaire</span>
                        <span class="text-sm font-bold {{ $statistiques['temporel']['inscriptions_derniere_semaine'] > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $croissance }}</span>
                    </div>
                    <div class="text-xs text-slate-500">Nouvelles inscriptions cette semaine</div>
                </div>

                <!-- Score global -->
                @php
                    $scoreGlobal = round(($tauxConversion * 0.4) + ($tauxFidelite * 0.4) + (min(100, max(0, $statistiques['temporel']['inscriptions_derniere_semaine'] * 10)) * 0.2), 1);
                @endphp
                <div class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $scoreGlobal }}/100</div>
                        <div class="text-sm text-indigo-800">Score global des inscriptions</div>
                        <div class="w-full bg-indigo-200 rounded-full h-2 mt-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $scoreGlobal }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé et recommandations -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                Résumé et Recommandations
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Résumé -->
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Résumé de Performance</h3>
                    <div class="space-y-3">
                        @if($statistiques['totaux']['total_inscriptions'] > 0)
                            <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-info-circle text-blue-600 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-blue-800"><strong>{{ $statistiques['totaux']['total_inscriptions'] }} inscriptions</strong> au total ont été enregistrées</p>
                                </div>
                            </div>

                            @if($tauxConversion >= 80)
                                <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg">
                                    <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm text-green-800">Excellent taux de conversion de <strong>{{ $tauxConversion }}%</strong></p>
                                    </div>
                                </div>
                            @elseif($tauxConversion >= 60)
                                <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm text-yellow-800">Taux de conversion moyen de <strong>{{ $tauxConversion }}%</strong></p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-start space-x-3 p-3 bg-red-50 rounded-lg">
                                    <i class="fas fa-times-circle text-red-600 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm text-red-800">Taux de conversion faible de <strong>{{ $tauxConversion }}%</strong></p>
                                    </div>
                                </div>
                            @endif

                            @if($statistiques['temporel']['annulations_derniere_semaine'] > 0)
                                <div class="flex items-start space-x-3 p-3 bg-orange-50 rounded-lg">
                                    <i class="fas fa-exclamation-circle text-orange-600 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm text-orange-800"><strong>{{ $statistiques['temporel']['annulations_derniere_semaine'] }} annulations</strong> cette semaine</p>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <i class="fas fa-info-circle text-gray-600 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-gray-800">Aucune inscription enregistrée pour cet événement</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recommandations -->
                <div>
                    <h3 class="font-semibold text-slate-900 mb-4">Recommandations</h3>
                    <div class="space-y-3">
                        @if($statistiques['totaux']['total_inscriptions'] == 0)
                            <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-bullhorn text-blue-600 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-blue-800">Lancez une campagne de promotion pour attirer les premières inscriptions</p>
                                </div>
                            </div>
                        @endif

                        @if($statistiques['capacite']['capacite_totale'] && $statistiques['capacite']['taux_remplissage'] < 50)
                            <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg">
                                <i class="fas fa-chart-line text-yellow-600 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-yellow-800">Remplissage à {{ $statistiques['capacite']['taux_remplissage'] }}% - Intensifiez la communication</p>
                                </div>
                            </div>
                        @endif

                        @if($tauxAnnulation > 20)
                            <div class="flex items-start space-x-3 p-3 bg-red-50 rounded-lg">
                                <i class="fas fa-exclamation-triangle text-red-600 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-red-800">Taux d'annulation élevé - Analysez les causes et améliorez l'expérience</p>
                                </div>
                            </div>
                        @endif

                        @if($statistiques['temporel']['inscriptions_derniere_24h'] > 0)
                            <div class="flex items-start space-x-3 p-3 bg-green-50 rounded-lg">
                                <i class="fas fa-trending-up text-green-600 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-green-800">Dynamique positive avec {{ $statistiques['temporel']['inscriptions_derniere_24h'] }} nouvelles inscriptions récentes</p>
                                </div>
                            </div>
                        @endif

                        @if($statistiques['capacite']['capacite_totale'] && $statistiques['capacite']['taux_remplissage'] > 90)
                            <div class="flex items-start space-x-3 p-3 bg-purple-50 rounded-lg">
                                <i class="fas fa-users text-purple-600 mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-purple-800">Événement presque complet - Préparez la logistique finale</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction d'export
function exportStatistics() {
    // Implémentation de l'export des statistiques
    const data = @json($statistiques);
    console.log('Export des statistiques:', data);
    alert('Fonctionnalité d\'export à implémenter');
}

// Fonction d'impression
function printStatistics() {
    window.print();
}

// Auto-actualisation toutes les 5 minutes
setInterval(function() {
    // Optionnel: recharger les statistiques
    // location.reload();
}, 300000);
</script>

@endsection
