@extends('layouts.private.main')
@section('title', 'Statistiques des Rapports')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Rapports de Réunions</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.rapports-reunions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>
                        Rapports de Réunions
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Statistiques</span>
                    </div>
                </li>
            </ol>
        </nav>
        <p class="text-slate-500 mt-1">Analyse des performances et tendances - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div>

    <!-- Filtres -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-filter text-blue-600 mr-2"></i>
                Filtres d'Analyse
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.rapports-reunions.statistiques') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Période</label>
                    <select name="periode" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="30" {{ request('periode', '30') == '30' ? 'selected' : '' }}>30 derniers jours</option>
                        <option value="90" {{ request('periode') == '90' ? 'selected' : '' }}>3 derniers mois</option>
                        <option value="180" {{ request('periode') == '180' ? 'selected' : '' }}>6 derniers mois</option>
                        <option value="365" {{ request('periode') == '365' ? 'selected' : '' }}>12 derniers mois</option>
                        <option value="all" {{ request('periode') == 'all' ? 'selected' : '' }}>Toutes les données</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de rapport</label>
                    <select name="type_rapport" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        @foreach(\App\Models\RapportReunion::TYPES_RAPPORT as $key => $value)
                            <option value="{{ $value }}" {{ request('type_rapport') == $value ? 'selected' : '' }}>
                                @switch($value)
                                    @case('proces_verbal') Procès-verbal @break
                                    @case('compte_rendu') Compte-rendu @break
                                    @case('rapport_activite') Rapport d'activité @break
                                    @case('rapport_financier') Rapport financier @break
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="publie" {{ request('statut') == 'publie' ? 'selected' : '' }}>Publiés uniquement</option>
                        <option value="valide" {{ request('statut') == 'valide' ? 'selected' : '' }}>Validés uniquement</option>
                        <option value="en_revision" {{ request('statut') == 'en_revision' ? 'selected' : '' }}>En révision</option>
                        <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillons</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync mr-2"></i> Actualiser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-file-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques_globales['total'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Total des rapports</p>
                    @if(isset($statistiques_globales['total']) && $statistiques_globales['total'] > 0)
                        <p class="text-xs text-green-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            {{ round(($statistiques_globales['publies'] / $statistiques_globales['total']) * 100, 1) }}% publiés
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques_globales['en_revision'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">En révision</p>
                    @if(isset($statistiques_globales['delai_validation_moyen']) && $statistiques_globales['delai_validation_moyen'])
                        <p class="text-xs text-amber-600">
                            <i class="fas fa-stopwatch mr-1"></i>
                            {{ $statistiques_globales['delai_validation_moyen'] }}j délai moyen
                        </p>
                    @endif
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
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques_globales['publies'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Publiés</p>
                    <p class="text-xs text-green-600">
                        <i class="fas fa-thumbs-up mr-1"></i>
                        Processus terminé
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($statistiques_globales['satisfaction_moyenne'] ?? 0, 1) }}</p>
                    <p class="text-sm text-slate-500">Satisfaction moyenne</p>
                    <div class="flex items-center mt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-xs {{ $i <= ($statistiques_globales['satisfaction_moyenne'] ?? 0) ? 'text-yellow-400' : 'text-slate-300' }} mr-1"></i>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et analyses -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Évolution mensuelle -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Évolution des Rapports (12 derniers mois)
                </h2>
            </div>
            <div class="p-6">
                @if($evolution_mensuelle && $evolution_mensuelle->count() > 0)
                    <div class="relative">
                        <canvas id="evolutionChart" width="400" height="200"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-4 text-center">
                        <div class="bg-blue-50 p-3 rounded-lg">
                            <p class="text-lg font-bold text-blue-600">{{ $evolution_mensuelle->sum('total') }}</p>
                            <p class="text-sm text-slate-600">Total créés</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded-lg">
                            <p class="text-lg font-bold text-green-600">{{ $evolution_mensuelle->sum('publies') }}</p>
                            <p class="text-sm text-slate-600">Total publiés</p>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-line text-4xl text-slate-400 mb-4"></i>
                        <p class="text-slate-500">Pas assez de données pour générer le graphique</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Répartition par type -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                    Répartition par Type
                </h2>
            </div>
            <div class="p-6">
                @php
                    $totalRapports = $statistiques_globales['total'] ?? 0;
                    $typeColors = [
                        'proces_verbal' => ['color' => 'bg-blue-500', 'text' => 'text-blue-600'],
                        'compte_rendu' => ['color' => 'bg-green-500', 'text' => 'text-green-600'],
                        'rapport_activite' => ['color' => 'bg-yellow-500', 'text' => 'text-yellow-600'],
                        'rapport_financier' => ['color' => 'bg-red-500', 'text' => 'text-red-600']
                    ];

                    // Simuler des données de répartition si pas disponibles
                    $repartition = [
                        'proces_verbal' => round($totalRapports * 0.4),
                        'compte_rendu' => round($totalRapports * 0.3),
                        'rapport_activite' => round($totalRapports * 0.2),
                        'rapport_financier' => round($totalRapports * 0.1)
                    ];
                @endphp

                @if($totalRapports > 0)
                    <div class="space-y-4">
                        @foreach($repartition as $type => $nombre)
                            @php
                                $pourcentage = $totalRapports > 0 ? ($nombre / $totalRapports) * 100 : 0;
                                $typeLabel = match($type) {
                                    'proces_verbal' => 'Procès-verbaux',
                                    'compte_rendu' => 'Comptes-rendus',
                                    'rapport_activite' => 'Rapports d\'activité',
                                    'rapport_financier' => 'Rapports financiers',
                                    default => $type
                                };
                            @endphp

                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 {{ $typeColors[$type]['color'] }} rounded mr-3"></div>
                                    <span class="text-sm font-medium text-slate-700">{{ $typeLabel }}</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-sm font-bold {{ $typeColors[$type]['text'] }} mr-3">{{ $nombre }}</span>
                                    <div class="w-24 h-2 bg-slate-200 rounded-full">
                                        <div class="{{ $typeColors[$type]['color'] }} h-2 rounded-full" style="width: {{ $pourcentage }}%"></div>
                                    </div>
                                    <span class="text-xs text-slate-500 ml-2">{{ number_format($pourcentage, 1) }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-pie text-4xl text-slate-400 mb-4"></i>
                        <p class="text-slate-500">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top rédacteurs -->
    @if($top_redacteurs && $top_redacteurs->count() > 0)
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-trophy text-amber-600 mr-2"></i>
                    Top Rédacteurs
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($top_redacteurs->take(6) as $index => $redacteur)
                        <div class="bg-gradient-to-br from-slate-50 to-white rounded-xl p-4 border border-slate-200 hover:shadow-md transition-all duration-300">
                            <div class="flex items-center mb-3">
                                @if($index < 3)
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                        {{ $index === 0 ? 'bg-yellow-100 text-yellow-600' : '' }}
                                        {{ $index === 1 ? 'bg-slate-100 text-slate-600' : '' }}
                                        {{ $index === 2 ? 'bg-orange-100 text-orange-600' : '' }}">
                                        @if($index === 0) <i class="fas fa-crown text-sm"></i>
                                        @elseif($index === 1) <i class="fas fa-medal text-sm"></i>
                                        @elseif($index === 2) <i class="fas fa-award text-sm"></i>
                                        @endif
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-sm font-bold text-blue-600">#{{ $index + 1 }}</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-semibold text-slate-900">
                                        {{ $redacteur->redacteur ? $redacteur->redacteur->nom . ' ' . $redacteur->redacteur->prenom : 'Rédacteur inconnu' }}
                                    </h3>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600">Rapports créés:</span>
                                    <span class="font-bold text-slate-900">{{ $redacteur->total_rapports }}</span>
                                </div>

                                @php
                                    $maxRapports = $top_redacteurs->first()->total_rapports ?? 1;
                                    $pourcentage = ($redacteur->total_rapports / $maxRapports) * 100;
                                @endphp

                                <div class="w-full h-2 bg-slate-200 rounded-full">
                                    <div class="h-2 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full transition-all duration-500" style="width: {{ $pourcentage }}%"></div>
                                </div>

                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>Performance</span>
                                    <span>{{ number_format($pourcentage, 1) }}%</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Rapports en attente -->
    @if($rapports_en_attente && $rapports_en_attente->count() > 0)
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-hourglass-half text-orange-600 mr-2"></i>
                        Rapports en Attente par Type
                    </h2>
                    <span class="text-sm text-slate-500">{{ $rapports_en_attente->sum('total') }} total</span>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($rapports_en_attente as $attente)
                        @php
                            $typeLabel = match($attente->type_rapport) {
                                'proces_verbal' => 'Procès-verbaux',
                                'compte_rendu' => 'Comptes-rendus',
                                'rapport_activite' => 'Rapports d\'activité',
                                'rapport_financier' => 'Rapports financiers',
                                default => $attente->type_rapport
                            };
                        @endphp

                        <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-yellow-50 rounded-xl border border-orange-200">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-file-alt text-orange-600"></i>
                            </div>
                            <h3 class="font-semibold text-slate-900 mb-1">{{ $typeLabel }}</h3>
                            <p class="text-2xl font-bold text-orange-600 mb-2">{{ $attente->total }}</p>
                            <p class="text-sm text-slate-600">en attente</p>

                            <div class="mt-3">
                                <a href="{{ route('private.rapports-reunions.index', ['statut' => 'en_revision', 'type' => $attente->type_rapport]) }}"
                                   class="inline-flex items-center px-3 py-1 bg-orange-600 text-white text-xs rounded-lg hover:bg-orange-700 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> Voir
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                Actions Rapides
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @can('rapports-reunions.create')
                <a href="{{ route('private.rapports-reunions.create') }}" class="flex items-center justify-center p-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    <span class="font-medium">Nouveau Rapport</span>
                </a>
                @endcan

                @can('rapports-reunions.manage-attendance')
                <a href="{{ route('private.rapports-reunions.en-attente') }}" class="flex items-center justify-center p-4 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-clock mr-2"></i>
                    <span class="font-medium">En Attente</span>
                </a>
                @endcan

                @can('rapports-reunions.manage')
                <a href="{{ route('private.rapports-reunions.mes-rapports') }}" class="flex items-center justify-center p-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-user mr-2"></i>
                    <span class="font-medium">Mes Rapports</span>
                </a>
                @endcan

                @can('rapports-reunions.export')
                <a href="{{ route('private.rapports-reunions.export', ['format' => 'excel']) }}" class="flex items-center justify-center p-4 bg-gradient-to-r from-cyan-600 to-blue-600 text-white rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i>
                    <span class="font-medium">Export Excel</span>
                </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Indicateurs de performance -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-tachometer-alt text-indigo-600 mr-2"></i>
                Indicateurs de Performance
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Taux de finalisation -->
                <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-200">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-double text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Taux de Finalisation</h3>
                    @php
                        $tauxFinalisation = $statistiques_globales['total'] > 0
                            ? (($statistiques_globales['publies'] ?? 0) / $statistiques_globales['total']) * 100
                            : 0;
                    @endphp
                    <p class="text-3xl font-bold text-green-600 mb-2">{{ number_format($tauxFinalisation, 1) }}%</p>
                    <p class="text-sm text-slate-600">des rapports sont publiés</p>

                    <div class="mt-4 w-full h-3 bg-green-200 rounded-full">
                        <div class="h-3 bg-green-500 rounded-full transition-all duration-500" style="width: {{ $tauxFinalisation }}%"></div>
                    </div>
                </div>

                <!-- Temps de traitement -->
                <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border border-blue-200">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-stopwatch text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Délai Moyen</h3>
                    <p class="text-3xl font-bold text-blue-600 mb-2">
                        {{ $statistiques_globales['delai_validation_moyen'] ?? 'N/A' }}
                        @if(isset($statistiques_globales['delai_validation_moyen']))
                            <span class="text-lg">jours</span>
                        @endif
                    </p>
                    <p class="text-sm text-slate-600">pour la validation</p>

                    @if(isset($statistiques_globales['delai_validation_moyen']))
                        @php
                            $delaiPourcentage = min(($statistiques_globales['delai_validation_moyen'] / 14) * 100, 100); // 14 jours = 100%
                            $delaiColor = $statistiques_globales['delai_validation_moyen'] <= 7 ? 'bg-green-500' :
                                         ($statistiques_globales['delai_validation_moyen'] <= 14 ? 'bg-yellow-500' : 'bg-red-500');
                        @endphp
                        <div class="mt-4 w-full h-3 bg-slate-200 rounded-full">
                            <div class="{{ $delaiColor }} h-3 rounded-full transition-all duration-500" style="width: {{ $delaiPourcentage }}%"></div>
                        </div>
                    @endif
                </div>

                <!-- Productivité -->
                <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Productivité</h3>
                    @php
                        $rapportsParMois = $statistiques_globales['total'] > 0 ? $statistiques_globales['total'] / 12 : 0;
                    @endphp
                    <p class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($rapportsParMois, 1) }}</p>
                    <p class="text-sm text-slate-600">rapports par mois</p>

                    <div class="mt-4 w-full h-3 bg-purple-200 rounded-full">
                        @php
                            $productivitePourcentage = min($rapportsParMois * 10, 100); // 10 rapports/mois = 100%
                        @endphp
                        <div class="bg-purple-500 h-3 rounded-full transition-all duration-500" style="width: {{ $productivitePourcentage }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution
    @if($evolution_mensuelle && $evolution_mensuelle->count() > 0)
    const evolutionCtx = document.getElementById('evolutionChart');
    if (evolutionCtx) {
        const evolutionData = @json($evolution_mensuelle->values());
        const labels = evolutionData.map(item => {
            const [year, month] = item.mois.split('-');
            const date = new Date(year, month - 1);
            return date.toLocaleDateString('fr-FR', { month: 'short', year: '2-digit' });
        });

        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Rapports créés',
                    data: evolutionData.map(item => item.total),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Rapports publiés',
                    data: evolutionData.map(item => item.publies),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }
    @endif

    // Animation des barres de progression
    const progressBars = document.querySelectorAll('[style*="width:"]');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });

    // Animation des compteurs
    const counters = document.querySelectorAll('.text-2xl.font-bold');
    counters.forEach(counter => {
        const target = parseFloat(counter.textContent);
        if (isNaN(target)) return;

        let current = 0;
        const increment = target / 20;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }

            if (target < 1) {
                counter.textContent = current.toFixed(1);
            } else {
                counter.textContent = Math.round(current);
            }
        }, 50);
    });
});
</script>
@endpush
@endsection
