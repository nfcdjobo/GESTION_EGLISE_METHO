@extends('layouts.private.main')
@section('title', 'Analytics Avancées')

@section('content')
<div class="space-y-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Analyse prédictive et insights approfondis </h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.fonds.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-receipt mr-2"></i>
                        Fonds
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Analyse prédictive et insights approfondis </span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Filtres et sélecteurs -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="flex flex-col p-6 border-b border-slate-200 sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-sliders-h text-blue-600 mr-2"></i>
                Paramètres d'Analyse
            </h2>
            <div class="flex flex-wrap gap-2">
                @can('fonds.create')
                    <a href="{{ route('private.fonds.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Nouvelle Transaction
                    </a>
                @endcan
                @can('fonds.dashboard')
                <a href="{{ route('private.fonds.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-tachometer-alt mr-2"></i> Tableau de Bord
                </a>
                @endcan
                @can('fonds.statistics')
                <a href="{{ route('private.fonds.statistics') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-chart-bar mr-2"></i> Statistiques
                </a>
                @endcan
                @can('fonds.analytics')
                <a href="{{ route('private.fonds.analytics') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-chart-line mr-2"></i> Analytics
                </a>
                @endcan
                @can('fonds.export')
                <a href="{{ route('private.fonds.export') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i> Exporter
                </a>
                @endcan
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type d'analyse</label>
                    <select id="typeAnalyse" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="generale" {{ $typeAnalyse == 'generale' ? 'selected' : '' }}>Analyse générale</option>
                        <option value="donateur" {{ $typeAnalyse == 'donateur' ? 'selected' : '' }}>Par donateur</option>
                        <option value="culte" {{ $typeAnalyse == 'culte' ? 'selected' : '' }}>Par culte</option>
                        <option value="tendance" {{ $typeAnalyse == 'tendance' ? 'selected' : '' }}>Tendances</option>
                        <option value="predictive" {{ $typeAnalyse == 'predictive' ? 'selected' : '' }}>Analyse prédictive</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Année</label>
                    <select id="anneeAnalyse" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @for($year = date('Y'); $year >= date('Y') - 3; $year--)
                            <option value="{{ $year }}" {{ $annee == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Granularité</label>
                    <select id="granularite" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="mois">Par mois</option>
                        <option value="trimestre">Par trimestre</option>
                        <option value="semestre">Par semestre</option>
                        <option value="annee">Par année</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button onclick="refreshAnalytics()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i> Analyser
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if($typeAnalyse == 'predictive')
        <!-- Section Analyse Prédictive -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Projections -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-crystal-ball text-purple-600 mr-2"></i>
                        Projections Financières
                    </h2>
                </div>
                <div class="p-6">
                    @if(isset($data['projections_mensuelles']) && !empty($data['projections_mensuelles']['projections']))
                        <div class="mb-6">
                            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl p-4 mb-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-sm font-medium text-purple-700">Projection annuelle</div>
                                        <div class="text-2xl font-bold text-purple-900">
                                            {{ number_format($data['projections_mensuelles']['total_projete_annee'] ?? 0, 0, ',', ' ') }} XOF
                                        </div>
                                    </div>
                                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-chart-line text-white"></i>
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-purple-600">
                                    Confiance: {{ ucfirst($data['projections_mensuelles']['confiance'] ?? 'moyenne') }}
                                </div>
                            </div>

                            <div class="space-y-3 max-h-64 overflow-y-auto">
                                @foreach($data['projections_mensuelles']['projections'] ?? [] as $mois => $projection)
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                        <div>
                                            <div class="font-medium text-slate-900">{{ $projection['nom_mois'] ?? "Mois $mois" }}</div>
                                            <div class="text-sm text-slate-500">
                                                Tendance: {{ isset($projection['tendance']) ? ($projection['tendance'] > 0 ? '+' : '') . number_format($projection['tendance'], 1) . '%' : 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-slate-900">{{ number_format($projection['montant_projete'] ?? 0, 0, ',', ' ') }}</div>
                                            <div class="text-sm text-slate-500">{{ number_format($projection['montant_historique_moyen'] ?? 0, 0, ',', ' ') }} hist.</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500">
                            <i class="fas fa-crystal-ball text-4xl mb-4"></i>
                            <p>Données insuffisantes pour les projections</p>
                            <p class="text-sm">Au moins 12 mois d'historique requis</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tendances Prédictives -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-trending-up text-green-600 mr-2"></i>
                        Tendances Prédictives
                    </h2>
                </div>
                <div class="p-6">
                    @if(isset($data['tendances_predictives']))
                        <div class="space-y-6">
                            <div class="bg-green-50 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-medium text-green-800">Croissance Annuelle</div>
                                    <div class="text-2xl font-bold text-green-900">
                                        {{ number_format($data['tendances_predictives']['croissance_annuelle'] ?? 0, 1) }}%
                                    </div>
                                </div>
                                <div class="text-sm text-green-600">
                                    Tendance sur les 3 dernières années
                                </div>
                            </div>

                            @if(isset($data['tendances_predictives']['prediction_annee_suivante']))
                                <div class="bg-blue-50 rounded-xl p-4">
                                    <div class="font-medium text-blue-800 mb-2">Prédiction {{ date('Y') + 1 }}</div>
                                    <div class="text-2xl font-bold text-blue-900 mb-2">
                                        {{ number_format($data['tendances_predictives']['prediction_annee_suivante']['montant_estime'], 0, ',', ' ') }} XOF
                                    </div>
                                    <div class="text-sm text-blue-600">
                                        Fourchette:
                                        {{ number_format($data['tendances_predictives']['prediction_annee_suivante']['intervalle_confiance']['min'], 0, ',', ' ') }} -
                                        {{ number_format($data['tendances_predictives']['prediction_annee_suivante']['intervalle_confiance']['max'], 0, ',', ' ') }} XOF
                                    </div>
                                </div>
                            @endif

                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-3 bg-slate-50 rounded-lg">
                                    <div class="font-bold text-slate-900">{{ number_format($data['tendances_predictives']['saisonnalite'] ?? 0, 1) }}%</div>
                                    <div class="text-sm text-slate-500">Variation saisonnière</div>
                                </div>
                                <div class="text-center p-3 bg-slate-50 rounded-lg">
                                    <div class="font-bold text-slate-900">{{ number_format($data['tendances_predictives']['volatilite'] ?? 0, 1) }}%</div>
                                    <div class="text-sm text-slate-500">Volatilité</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Alertes Prédictives -->
            @if(isset($data['alertes_predictives']) && !empty($data['alertes_predictives']))
                <div class="lg:col-span-2 bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-exclamation-triangle text-amber-600 mr-2"></i>
                            Alertes Prédictives
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($data['alertes_predictives'] as $alerte)
                                <div class="border-l-4
                                    @if($alerte['severity'] == 'high') border-red-500 bg-red-50
                                    @elseif($alerte['severity'] == 'medium') border-orange-500 bg-orange-50
                                    @else border-yellow-500 bg-yellow-50
                                    @endif
                                    p-4 rounded-r-lg">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <i class="fas fa-
                                                @if($alerte['severity'] == 'high') exclamation-circle text-red-500
                                                @elseif($alerte['severity'] == 'medium') exclamation-triangle text-orange-500
                                                @else info-circle text-yellow-500
                                                @endif"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-medium
                                                @if($alerte['severity'] == 'high') text-red-800
                                                @elseif($alerte['severity'] == 'medium') text-orange-800
                                                @else text-yellow-800
                                                @endif">
                                                {{ $alerte['message'] }}
                                            </h3>
                                            @if(isset($alerte['recommandation']))
                                                <p class="mt-1 text-sm
                                                    @if($alerte['severity'] == 'high') text-red-700
                                                    @elseif($alerte['severity'] == 'medium') text-orange-700
                                                    @else text-yellow-700
                                                    @endif">
                                                    {{ $alerte['recommandation'] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recommandations -->
            @if(isset($data['recommandations']) && !empty($data['recommandations']))
                <div class="lg:col-span-2 bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                            Recommandations Stratégiques
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($data['recommandations'] as $recommandation)
                                <div class="bg-slate-50 rounded-xl p-4">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="w-10 h-10 bg-
                                                @if($recommandation['priorite'] == 'haute') red-500
                                                @elseif($recommandation['priorite'] == 'moyenne') orange-500
                                                @else green-500
                                                @endif
                                                rounded-lg flex items-center justify-center text-white font-bold">
                                                @if($recommandation['priorite'] == 'haute') !
                                                @elseif($recommandation['priorite'] == 'moyenne') ⚡
                                                @else ✓
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-bold text-slate-900 mb-2">{{ $recommandation['titre'] }}</h3>
                                            <p class="text-slate-700 mb-3">{{ $recommandation['description'] }}</p>
                                            @if(isset($recommandation['actions']) && is_array($recommandation['actions']))
                                                <div class="space-y-2">
                                                    <div class="font-medium text-slate-800">Actions recommandées:</div>
                                                    <ul class="text-sm text-slate-600 space-y-1">
                                                        @foreach($recommandation['actions'] as $action)
                                                            <li class="flex items-start">
                                                                <i class="fas fa-chevron-right text-blue-500 text-xs mt-1 mr-2"></i>
                                                                {{ $action }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

    @elseif($typeAnalyse == 'donateur')
        <!-- Section Analyse par Donateur -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Segmentation des donateurs -->
            <div class="lg:col-span-2 bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Segmentation des Donateurs {{ $annee }}
                    </h2>
                </div>
                <div class="p-6">
                    @if(isset($data['segmentation']))
                        <div class="mb-6">
                            <canvas id="segmentationChart" width="400" height="200"></canvas>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($data['segmentation'] as $segment => $stats)
                                <div class="text-center p-4 bg-slate-50 rounded-xl">
                                    <div class="w-12 h-12 mx-auto mb-2 bg-gradient-to-r
                                        @if($segment == 'champions') from-yellow-400 to-yellow-600
                                        @elseif($segment == 'reguliers') from-blue-400 to-blue-600
                                        @elseif($segment == 'occasionnels') from-purple-400 to-purple-600
                                        @else from-gray-400 to-gray-600
                                        @endif
                                        rounded-full flex items-center justify-center text-white font-bold">
                                        {{ $stats['count'] }}
                                    </div>
                                    <div class="font-medium text-slate-900 capitalize">{{ str_replace('_', ' ', $segment) }}</div>
                                    <div class="text-sm text-slate-500">{{ number_format($stats['pourcentage'], 1) }}% des donateurs</div>
                                    <div class="text-xs text-slate-400">{{ number_format($stats['contribution_totale'], 0, ',', ' ') }} XOF</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques générales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                        Statistiques
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    @if(isset($data['statistiques']))
                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold text-lg">{{ $data['total_donateurs'] ?? 0 }}</span>
                            </div>
                            <div class="font-medium text-slate-900">Total Donateurs</div>
                            <div class="text-sm text-slate-500">Année {{ $annee }}</div>
                        </div>

                        <div class="space-y-4">
                            <div class="bg-green-50 rounded-lg p-3">
                                <div class="font-medium text-green-800">Don Moyen</div>
                                <div class="text-2xl font-bold text-green-900">{{ number_format($data['statistiques']['don_moyen'] ?? 0, 0, ',', ' ') }} XOF</div>
                            </div>

                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="font-medium text-blue-800">Fréquence Moyenne</div>
                                <div class="text-2xl font-bold text-blue-900">{{ number_format($data['statistiques']['frequence_moyenne'] ?? 0, 1) }} dons</div>
                            </div>

                            <div class="bg-purple-50 rounded-lg p-3">
                                <div class="font-medium text-purple-800">Contribution Moyenne</div>
                                <div class="text-2xl font-bold text-purple-900">{{ number_format($data['statistiques']['contribution_moyenne'] ?? 0, 0, ',', ' ') }} XOF</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Préférences des donateurs -->
            @if(isset($data['preferences']))
                <div class="lg:col-span-3 bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-heart text-red-600 mr-2"></i>
                            Préférences de Don
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Types populaires -->
                            <div>
                                <h3 class="font-semibold text-slate-800 mb-4">Types de Dons Populaires</h3>
                                <div class="space-y-3">
                                    @foreach($data['preferences']['types_populaires']->take(5) as $type)
                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                            <div>
                                                <div class="font-medium text-slate-900">{{ ucfirst(str_replace('_', ' ', $type->type_transaction)) }}</div>
                                                <div class="text-sm text-slate-500">{{ $type->count }} dons</div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-slate-900">{{ number_format($type->moyenne, 0, ',', ' ') }}</div>
                                                <div class="text-xs text-slate-500">XOF moyen</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Modes de paiement populaires -->
                            <div>
                                <h3 class="font-semibold text-slate-800 mb-4">Modes de Paiement</h3>
                                <div class="space-y-3">
                                    @foreach($data['preferences']['modes_populaires']->take(5) as $mode)
                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                            <div class="font-medium text-slate-900">{{ ucfirst(str_replace('_', ' ', $mode->mode_paiement)) }}</div>
                                            <div class="flex items-center">
                                                <div class="font-bold text-slate-900 mr-2">{{ $mode->count }}</div>
                                                <div class="text-xs text-slate-500">utilisations</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    @elseif($typeAnalyse == 'culte')
        <!-- Section Analyse par Culte -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Analyse des cultes -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-church text-purple-600 mr-2"></i>
                        Analyse des Cultes {{ $annee }}
                    </h2>
                </div>
                <div class="p-6">
                    @if(isset($data) && !$data->isEmpty())
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            @foreach($data->take(10) as $culte)
                                <div class="p-4 bg-slate-50 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="font-medium text-slate-900">{{ $culte->titre_culte ?? 'Culte sans titre' }}</div>
                                        <div class="text-sm text-slate-500">{{ isset($culte->date_culte) ? \Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y') : 'N/A' }}</div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <div class="text-xs text-slate-500">Montant total</div>
                                            <div class="font-bold text-slate-900">{{ number_format($culte->total_montant ?? 0, 0, ',', ' ') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-slate-500">Transactions</div>
                                            <div class="font-bold text-slate-900">{{ $culte->nombre_transactions ?? 0 }}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs text-slate-500">Moyenne</div>
                                            <div class="font-bold text-slate-900">{{ number_format($culte->montant_moyen ?? 0, 0, ',', ' ') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500">
                            <i class="fas fa-church text-4xl mb-4"></i>
                            <p>Aucune donnée de culte disponible</p>
                            <p class="text-sm">pour l'année {{ $annee }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Graphique évolution -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-line text-green-600 mr-2"></i>
                        Évolution Mensuelle
                    </h2>
                </div>
                <div class="p-6">
                    <div class="mb-6" style="height: 300px;">
                        <canvas id="evolutionCulteChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

    @elseif($typeAnalyse == 'tendance')
        <!-- Section Analyse des Tendances -->
        <div class="space-y-8">
            <!-- Graphique des tendances principales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                        Analyse des Tendances {{ $annee }}
                    </h2>
                </div>
                <div class="p-6">
                    <div class="mb-6" style="height: 400px;">
                        <canvas id="tendancesAvanceesChart"></canvas>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if(isset($data['tendance_generale']))
                            <div class="text-center p-4 bg-blue-50 rounded-xl">
                                <div class="text-2xl font-bold text-blue-900">{{ ucfirst($data['tendance_generale']['direction'] ?? 'stable') }}</div>
                                <div class="text-sm text-blue-600">Tendance générale</div>
                                <div class="text-xs text-blue-500">{{ number_format($data['tendance_generale']['variation'] ?? 0, 1) }}%</div>
                            </div>
                        @endif

                        @if(isset($data['volatilite']))
                            <div class="text-center p-4 bg-green-50 rounded-xl">
                                <div class="text-2xl font-bold text-green-900">{{ number_format($data['volatilite'], 1) }}%</div>
                                <div class="text-sm text-green-600">Volatilité</div>
                            </div>
                        @endif

                        @if(isset($data['anomalies']))
                            <div class="text-center p-4 bg-amber-50 rounded-xl">
                                <div class="text-2xl font-bold text-amber-900">{{ $data['anomalies']->count() }}</div>
                                <div class="text-sm text-amber-600">Anomalies détectées</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Détail des tendances -->
            @if(isset($data['evolution_mensuelle']) && !$data['evolution_mensuelle']->isEmpty())
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-calendar-alt text-cyan-600 mr-2"></i>
                                Évolution Mensuelle
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3 max-h-64 overflow-y-auto">
                                @foreach($data['evolution_mensuelle'] as $mois)
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                        <div>
                                            <div class="font-medium text-slate-900">{{ $mois->nom_mois ?? "Mois {$mois->mois}" }}</div>
                                            <div class="text-sm text-slate-500">{{ $mois->nombre ?? 0 }} transactions</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-slate-900">{{ number_format($mois->total ?? 0, 0, ',', ' ') }}</div>
                                            <div class="text-sm text-slate-500">{{ number_format($mois->moyenne ?? 0, 0, ',', ' ') }} moy.</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Comparaison avec année précédente -->
                    @if(isset($data['comparaison_annee_precedente']))
                        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-balance-scale text-indigo-600 mr-2"></i>
                                    Comparaison {{ $annee - 1 }} vs {{ $annee }}
                                </h2>
                            </div>
                            <div class="p-6">
                                <div class="space-y-3 max-h-64 overflow-y-auto">
                                    @foreach($data['comparaison_annee_precedente']->take(12) as $comparaison)
                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                            <div>
                                                <div class="font-medium text-slate-900">{{ $comparaison['nom_mois'] ?? "Mois {$comparaison['mois']}" }}</div>
                                                <div class="text-sm
                                                    @if($comparaison['tendance'] == 'hausse') text-green-600
                                                    @elseif($comparaison['tendance'] == 'baisse') text-red-600
                                                    @else text-slate-500
                                                    @endif">
                                                    {{ ucfirst($comparaison['tendance']) }} {{ number_format(abs($comparaison['variation']), 1) }}%
                                                </div>
                                            </div>
                                            <div class="text-right text-sm">
                                                <div class="font-bold">{{ number_format($comparaison['actuel'], 0, ',', ' ') }}</div>
                                                <div class="text-slate-500">vs {{ number_format($comparaison['precedent'], 0, ',', ' ') }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

    @else
        <!-- Section Analyse Générale -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Vue d'ensemble -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-area text-blue-600 mr-2"></i>
                        Évolution Générale {{ $annee }}
                    </h2>
                </div>
                <div class="p-6">
                    <div class="mb-6" style="height: 300px;">
                        <canvas id="evolutionGeneraleChart"></canvas>
                    </div>

                    @if(isset($data['evolution_mensuelle']) && !$data['evolution_mensuelle']->isEmpty())
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="font-bold text-blue-900">{{ number_format($data['evolution_mensuelle']->sum('total'), 0, ',', ' ') }}</div>
                                <div class="text-sm text-blue-600">Total {{ $annee }}</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="font-bold text-green-900">{{ number_format($data['evolution_mensuelle']->avg('total'), 0, ',', ' ') }}</div>
                                <div class="text-sm text-green-600">Moyenne mensuelle</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Répartition détaillée -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                        Performance Globale
                    </h2>
                </div>
                <div class="p-6">
                    @if(isset($data['performance_globale']))
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full flex items-center justify-center">
                                <span class="text-white font-bold">{{ number_format($data['performance_globale']['score_performance'] ?? 0, 0) }}</span>
                            </div>
                            <div class="font-bold text-slate-900">Score de Performance</div>
                            <div class="text-sm text-slate-500">{{ ucfirst($data['performance_globale']['evaluation'] ?? 'moyenne') }}</div>
                        </div>

                        @if(isset($data['performance_globale']['stats_principales']))
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-3 bg-slate-50 rounded-lg">
                                    <div class="font-bold text-slate-900">{{ number_format($data['performance_globale']['stats_principales']->total_transactions ?? 0) }}</div>
                                    <div class="text-sm text-slate-500">Transactions</div>
                                </div>
                                <div class="text-center p-3 bg-slate-50 rounded-lg">
                                    <div class="font-bold text-slate-900">{{ number_format($data['performance_globale']['stats_principales']->donateurs_uniques ?? 0) }}</div>
                                    <div class="text-sm text-slate-500">Donateurs</div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Saisonnalité -->
        @if(isset($data['saisonnalite']))
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-snowflake text-cyan-600 mr-2"></i>
                        Analyse Saisonnière
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-cyan-50 rounded-xl">
                            <div class="text-2xl font-bold text-cyan-900">{{ ucfirst($data['saisonnalite']['pattern']) }}</div>
                            <div class="text-sm text-cyan-600">Pattern détecté</div>
                        </div>

                        @if(isset($data['saisonnalite']['mois_le_plus_fort']))
                            <div class="text-center p-4 bg-green-50 rounded-xl">
                                <div class="text-2xl font-bold text-green-900">{{ $data['saisonnalite']['mois_le_plus_fort']->nom_mois ?? "Mois {$data['saisonnalite']['mois_le_plus_fort']->mois}" }}</div>
                                <div class="text-sm text-green-600">Meilleur mois</div>
                                <div class="text-xs text-green-500">{{ number_format($data['saisonnalite']['mois_le_plus_fort']->total ?? 0, 0, ',', ' ') }} XOF</div>
                            </div>
                        @endif

                        @if(isset($data['saisonnalite']['mois_le_plus_faible']))
                            <div class="text-center p-4 bg-red-50 rounded-xl">
                                <div class="text-2xl font-bold text-red-900">{{ $data['saisonnalite']['mois_le_plus_faible']->nom_mois ?? "Mois {$data['saisonnalite']['mois_le_plus_faible']->mois}" }}</div>
                                <div class="text-sm text-red-600">Plus faible mois</div>
                                <div class="text-xs text-red-500">{{ number_format($data['saisonnalite']['mois_le_plus_faible']->total ?? 0, 0, ',', ' ') }} XOF</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Section Confiance des Prédictions (pour tous les types d'analyse) -->
    @if(isset($data['confiance_predictions']))
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                    Fiabilité des Analyses
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-3 bg-green-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold">{{ number_format($data['confiance_predictions']['score_global'] ?? 0, 0) }}</span>
                        </div>
                        <div class="font-medium text-slate-900">Score Global</div>
                        <div class="text-sm text-slate-500">{{ ucfirst($data['confiance_predictions']['niveau'] ?? 'moyenne') }}</div>
                    </div>

                    @foreach($data['confiance_predictions']['facteurs_detailles'] ?? [] as $facteur => $score)
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center
                                @if($score >= 80) bg-green-500
                                @elseif($score >= 60) bg-yellow-500
                                @else bg-red-500
                                @endif">
                                <span class="text-white font-bold text-sm">{{ number_format($score, 0) }}</span>
                            </div>
                            <div class="font-medium text-slate-900 text-sm capitalize">{{ str_replace('_', ' ', $facteur) }}</div>
                            <div class="text-xs text-slate-500">
                                @if($score >= 80) Excellent
                                @elseif($score >= 60) Bon
                                @else À améliorer
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(isset($data['confiance_predictions']['recommandations']) && !empty($data['confiance_predictions']['recommandations']))
                    <div class="mt-6 p-4 bg-blue-50 rounded-xl">
                        <div class="font-medium text-blue-800 mb-2">Recommandations pour améliorer la fiabilité :</div>
                        <ul class="text-sm text-blue-700 space-y-1">
                            @foreach($data['confiance_predictions']['recommandations'] as $recommandation)
                                <li class="flex items-start">
                                    <i class="fas fa-arrow-right text-blue-500 text-xs mt-1 mr-2"></i>
                                    {{ $recommandation }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
// Variables globales pour les graphiques
let analyticsCharts = {};

// Fonction pour rafraîchir les analytics
function refreshAnalytics() {
    const typeAnalyse = document.getElementById('typeAnalyse').value;
    const annee = document.getElementById('anneeAnalyse').value;

    const url = new URL(window.location.href);
    url.searchParams.set('type', typeAnalyse);
    url.searchParams.set('annee', annee);
    window.location.href = url.toString();
}

// Initialisation des graphiques selon le type d'analyse
document.addEventListener('DOMContentLoaded', function() {
    const typeAnalyse = '{{ $typeAnalyse }}';

    switch(typeAnalyse) {
        case 'donateur':
            initSegmentationChart();
            break;
        case 'culte':
            initEvolutionCulteChart();
            break;
        case 'tendance':
            initTendancesAvanceesChart();
            break;
        default:
            initEvolutionGeneraleChart();
    }
});

// Graphique de segmentation des donateurs
function initSegmentationChart() {
    @if(isset($data['segmentation']))
        const ctx = document.getElementById('segmentationChart').getContext('2d');
        const segmentationData = @json($data['segmentation']);

        analyticsCharts.segmentation = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(segmentationData).map(key => key.charAt(0).toUpperCase() + key.slice(1).replace('_', ' ')),
                datasets: [{
                    data: Object.values(segmentationData).map(item => item.count),
                    backgroundColor: [
                        '#F59E0B', '#10B981', '#3B82F6', '#8B5CF6', '#6B7280'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    @endif
}

// Graphique évolution culte
function initEvolutionCulteChart() {
    const ctx = document.getElementById('evolutionCulteChart');
    if (!ctx) return;

    // Données de base pour les cultes
    analyticsCharts.evolutionCulte = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: @json(range(1, 12)),
            datasets: [{
                label: 'Collecte mensuelle',
                data: @json(array_fill(0, 12, 0)),
                borderColor: 'rgb(139, 92, 246)',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Graphique des tendances avancées
function initTendancesAvanceesChart() {
    @if(isset($data['evolution_mensuelle']))
        const ctx = document.getElementById('tendancesAvanceesChart').getContext('2d');
        const evolutionData = @json($data['evolution_mensuelle']);

        analyticsCharts.tendancesAvancees = new Chart(ctx, {
            type: 'line',
            data: {
                labels: evolutionData.map(item => item.nom_mois || `Mois ${item.mois}`),
                datasets: [{
                    label: 'Montant (XOF)',
                    data: evolutionData.map(item => item.total),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value) + ' XOF';
                            }
                        }
                    }
                }
            }
        });
    @endif
}

// Graphique évolution générale
function initEvolutionGeneraleChart() {
    @if(isset($data['evolution_mensuelle']))
        const ctx = document.getElementById('evolutionGeneraleChart').getContext('2d');
        const evolutionData = @json($data['evolution_mensuelle']);

        analyticsCharts.evolutionGenerale = new Chart(ctx, {
            type: 'line',
            data: {
                labels: evolutionData.map(item => item.nom_mois || `Mois ${item.mois}`),
                datasets: [{
                    label: 'Montant (XOF)',
                    data: evolutionData.map(item => item.total),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR').format(value) + ' XOF';
                            }
                        }
                    }
                }
            }
        });
    @endif
}

// Nettoyage des graphiques
window.addEventListener('beforeunload', function() {
    Object.values(analyticsCharts).forEach(chart => {
        if (chart && typeof chart.destroy === 'function') {
            chart.destroy();
        }
    });
});
</script>
@endpush
@endsection
