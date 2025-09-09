@extends('layouts.private.main')
@section('title', 'Statistiques Financières')

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Statistiques Financières</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.fonds.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-receipt mr-2"></i>
                            Fonds
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-medium text-slate-500">Statistiques Financières</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Filtres -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <form method="GET" action="{{ route('private.fonds.statistics') }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Période</label>
                        <select name="periode"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="mois" {{ $periode == 'mois' ? 'selected' : '' }}>Ce mois</option>
                            <option value="trimestre" {{ $periode == 'trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                            <option value="annee" {{ $periode == 'annee' ? 'selected' : '' }}>Cette année</option>
                            <option value="personnalise" {{ $periode == 'personnalise' ? 'selected' : '' }}>Personnalisée
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Année</label>
                        <select name="annee"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                                <option value="{{ $year }}" {{ $annee == $year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Mois</label>
                        <select name="mois"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @for ($month = 1; $month <= 12; $month++)
                                <option value="{{ $month }}" {{ $mois == $month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($month)->locale('fr')->monthName }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i> Analyser
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Résumé global -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-globe text-blue-600 mr-2"></i>
                    Résumé Global
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">
                            {{ number_format($stats['resume_global']['total_collecte'] ?? 0, 0, ',', ' ') }}</div>
                        <div class="text-sm text-slate-500">Total collecté (XOF)</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-600">
                            {{ number_format($stats['resume_global']['nombre_transactions'] ?? 0) }}</div>
                        <div class="text-sm text-slate-500">Transactions totales</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-600">
                            {{ number_format($stats['resume_global']['nombre_donateurs'] ?? 0) }}</div>
                        <div class="text-sm text-slate-500">Donateurs uniques</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-amber-600">
                            {{ number_format($stats['resume_global']['montant_moyen'] ?? 0, 0, ',', ' ') }}</div>
                        <div class="text-sm text-slate-500">Montant moyen (XOF)</div>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <div class="bg-blue-50 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-lg font-bold text-blue-900">
                                    {{ number_format($stats['resume_global']['dimes_total'] ?? 0, 0, ',', ' ') }}</div>
                                <div class="text-sm text-blue-600">Dîmes (XOF)</div>
                            </div>
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-hand-holding-heart text-white"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-lg font-bold text-green-900">
                                    {{ number_format($stats['resume_global']['offrandes_total'] ?? 0, 0, ',', ' ') }}</div>
                                <div class="text-sm text-green-600">Offrandes (XOF)</div>
                            </div>
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-donate text-white"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-purple-50 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-lg font-bold text-purple-900">
                                    {{ number_format($stats['resume_global']['dons_total'] ?? 0, 0, ',', ' ') }}</div>
                                <div class="text-sm text-purple-600">Dons spéciaux (XOF)</div>
                            </div>
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-gift text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Tendances annuelles -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-line text-green-600 mr-2"></i>
                        Tendances Annuelles {{ $annee }}
                    </h2>
                </div>
                <div class="p-6">
                    @if (isset($stats['tendances_annuelles']['donnees_mensuelles']) &&
                            $stats['tendances_annuelles']['donnees_mensuelles']->count() > 0)
                        <div class="mb-6">
                            <canvas id="tendancesChart" width="400" height="200"></canvas>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="bg-green-50 rounded-lg p-3">
                                <div class="font-medium text-green-800">Croissance moyenne</div>
                                <div class="text-lg font-bold text-green-900">
                                    {{ number_format($stats['tendances_annuelles']['croissance_moyenne'] ?? 0, 1) }}%</div>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-3">
                                <div class="font-medium text-blue-800">Meilleur mois</div>
                                <div class="text-lg font-bold text-blue-900">
                                    @if (isset($stats['tendances_annuelles']['meilleur']))
                                        {{ \Carbon\Carbon::create()->month($stats['tendances_annuelles']['meilleur']['mois'])->locale('fr')->monthName }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500">
                            <i class="fas fa-chart-line text-4xl mb-4"></i>
                            <p>Aucune donnée disponible pour cette période</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Comparaison mensuelle -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-balance-scale text-purple-600 mr-2"></i>
                        Comparaison Mensuelle {{ $annee }}
                    </h2>
                </div>
                <div class="p-6">
                    @if (isset($stats['comparaison_mensuelle']) && !empty($stats['comparaison_mensuelle']))
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            @foreach ($stats['comparaison_mensuelle'] as $mois => $data)
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $data['nom_mois'] ?? 'Mois ' . $mois }}
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            {{ number_format($data['actuel'], 0, ',', ' ') }} XOF</div>
                                    </div>
                                    <div class="text-right">
                                        @if ($data['variation'] != 0)
                                            <div class="flex items-center">
                                                @if ($data['tendance'] == 'hausse')
                                                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                                                    <span
                                                        class="text-green-600 font-medium">+{{ number_format($data['variation'], 1) }}%</span>
                                                @elseif($data['tendance'] == 'baisse')
                                                    <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                                                    <span
                                                        class="text-red-600 font-medium">{{ number_format($data['variation'], 1) }}%</span>
                                                @else
                                                    <span class="text-slate-500">Stable</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-500">-</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500">
                            <i class="fas fa-balance-scale text-4xl mb-4"></i>
                            <p>Aucune donnée de comparaison</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Performance des donateurs -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-users text-amber-600 mr-2"></i>
                        Performance des Donateurs
                    </h2>
                </div>
                <div class="p-6">
                    @if (isset($stats['performance_donateurs']['segmentation']))
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            @foreach ($stats['performance_donateurs']['segmentation'] as $segment => $data)
                                <div class="bg-slate-50 rounded-lg p-3">
                                    <div class="text-sm font-medium text-slate-600 capitalize">
                                        {{ str_replace('_', ' ', $segment) }}</div>
                                    <div class="text-lg font-bold text-slate-900">{{ $data['count'] ?? 0 }}</div>
                                    <div class="text-xs text-slate-500">
                                        {{ number_format($data['pourcentage_montant'] ?? 0, 1) }}% du total</div>
                                </div>
                            @endforeach
                        </div>

                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-green-700">Taux de rétention</div>
                                    <div class="text-2xl font-bold text-green-900">
                                        {{ number_format($stats['performance_donateurs']['retention_rate'] ?? 0, 1) }}%
                                    </div>
                                </div>
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user-check text-white"></i>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500">
                            <i class="fas fa-users text-4xl mb-4"></i>
                            <p>Aucune donnée de performance</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Analyse des cultes -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-church text-cyan-600 mr-2"></i>
                        Analyse des Cultes
                    </h2>
                </div>
                <div class="p-6">
                    @if (isset($stats['analyse_cultes']) && $stats['analyse_cultes']->count() > 0)
                        <div class="space-y-3 max-h-64 overflow-y-auto">
                            @foreach ($stats['analyse_cultes']->take(8) as $culte)
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $culte->titre_culte ?? 'Culte' }}</div>
                                        <div class="text-sm text-slate-500">
                                            {{ \Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y') }} -
                                            {{ $culte->nombre_transactions }} transactions
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-slate-900">
                                            {{ number_format($culte->total_montant, 0, ',', ' ') }}</div>
                                        <div class="text-sm text-slate-500">
                                            {{ number_format($culte->montant_moyen, 0, ',', ' ') }} moy.</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500">
                            <i class="fas fa-church text-4xl mb-4"></i>
                            <p>Aucun culte analysé</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ratios financiers -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calculator text-indigo-600 mr-2"></i>
                    Ratios Financiers {{ $annee }}
                </h2>
            </div>
            <div class="p-6">
                @if (isset($stats['ratios_financiers']))
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto mb-4 bg-blue-500 rounded-full flex items-center justify-center">
                                <span
                                    class="text-white font-bold text-lg">{{ number_format($stats['ratios_financiers']['ratio_dimes'] ?? 0, 0) }}%</span>
                            </div>
                            <div class="font-medium text-slate-900">Ratio Dîmes</div>
                            <div class="text-sm text-slate-500">Part des dîmes</div>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto mb-4 bg-green-500 rounded-full flex items-center justify-center">
                                <span
                                    class="text-white font-bold text-lg">{{ number_format($stats['ratios_financiers']['ratio_offrandes'] ?? 0, 0) }}%</span>
                            </div>
                            <div class="font-medium text-slate-900">Ratio Offrandes</div>
                            <div class="text-sm text-slate-500">Part des offrandes</div>
                        </div>

                        <div class="text-center">
                            <div
                                class="w-20 h-20 mx-auto mb-4 bg-purple-500 rounded-full flex items-center justify-center">
                                <span
                                    class="text-white font-bold text-lg">{{ number_format($stats['ratios_financiers']['ratio_dons'] ?? 0, 0) }}%</span>
                            </div>
                            <div class="font-medium text-slate-900">Ratio Dons</div>
                            <div class="text-sm text-slate-500">Part des dons</div>
                        </div>

                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto mb-4 bg-amber-500 rounded-full flex items-center justify-center">
                                <span
                                    class="text-white font-bold text-lg">{{ number_format($stats['ratios_financiers']['diversification_score'] ?? 0, 0) }}</span>
                            </div>
                            <div class="font-medium text-slate-900">Score Diversification</div>
                            <div class="text-sm text-slate-500">Sur 100</div>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-slate-700">Score de Stabilité</div>
                                    <div class="text-2xl font-bold text-slate-900">
                                        {{ number_format($stats['ratios_financiers']['stabilite_score'] ?? 0, 1) }}/100
                                    </div>
                                </div>
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-balance-scale text-white"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-slate-700">Évaluation Globale</div>
                                    <div class="text-2xl font-bold text-slate-900">
                                        @if (($stats['ratios_financiers']['stabilite_score'] ?? 0) >= 80)
                                            Excellente
                                        @elseif(($stats['ratios_financiers']['stabilite_score'] ?? 0) >= 60)
                                            Bonne
                                        @elseif(($stats['ratios_financiers']['stabilite_score'] ?? 0) >= 40)
                                            Moyenne
                                        @else
                                            À améliorer
                                        @endif
                                    </div>
                                </div>
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-star text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if (isset($stats['saisonnalite']))
            <!-- Analyse de saisonnalité -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-snowflake text-cyan-600 mr-2"></i>
                        Analyse de Saisonnalité {{ $annee }}
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @if (isset($stats['saisonnalite']['mois_le_plus_fort']))
                            <div class="text-center p-4 bg-green-50 rounded-xl">
                                <div
                                    class="w-16 h-16 mx-auto mb-3 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-arrow-up text-white text-xl"></i>
                                </div>
                                <div class="font-bold text-green-900">
                                    {{ $stats['saisonnalite']['mois_le_plus_fort']['nom_mois'] ?? 'N/A' }}</div>
                                <div class="text-sm text-green-600">Meilleur mois</div>
                                <div class="text-lg font-semibold text-slate-900 mt-2">
                                    {{ number_format($stats['saisonnalite']['mois_le_plus_fort']['total'] ?? 0, 0, ',', ' ') }}
                                    XOF
                                </div>
                            </div>
                        @endif

                        @if (isset($stats['saisonnalite']['mois_le_plus_faible']))
                            <div class="text-center p-4 bg-red-50 rounded-xl">
                                <div
                                    class="w-16 h-16 mx-auto mb-3 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-arrow-down text-white text-xl"></i>
                                </div>
                                <div class="font-bold text-red-900">
                                    {{ $stats['saisonnalite']['mois_le_plus_faible']['nom_mois'] ?? 'N/A' }}</div>
                                <div class="text-sm text-red-600">Mois le plus faible</div>
                                <div class="text-lg font-semibold text-slate-900 mt-2">
                                    {{ number_format($stats['saisonnalite']['mois_le_plus_faible']['total'] ?? 0, 0, ',', ' ') }}
                                    XOF
                                </div>
                            </div>
                        @endif

                        <div class="text-center p-4 bg-blue-50 rounded-xl">
                            <div class="w-16 h-16 mx-auto mb-3 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-chart-bar text-white text-xl"></i>
                            </div>
                            <div class="font-bold text-blue-900">
                                {{ number_format($stats['saisonnalite']['coefficient_variation'] ?? 0, 1) }}%</div>
                            <div class="text-sm text-blue-600">Coefficient de variation</div>
                            <div class="text-sm text-slate-500 mt-2">
                                @if (($stats['saisonnalite']['coefficient_variation'] ?? 0) < 20)
                                    Stable
                                @elseif(($stats['saisonnalite']['coefficient_variation'] ?? 0) < 40)
                                    Modérément variable
                                @else
                                    Très variable
                                @endif
                            </div>
                        </div>
                    </div>

                    @if (isset($stats['saisonnalite']['pattern']))
                        <div class="mt-6 p-4 bg-slate-50 rounded-xl">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                                <div>
                                    <div class="font-medium text-slate-900">Pattern détecté</div>
                                    <div class="text-sm text-slate-600">
                                        @if ($stats['saisonnalite']['pattern'] == 'religious')
                                            Pattern religieux : pics durant les fêtes (Noël, Pâques)
                                        @elseif($stats['saisonnalite']['pattern'] == 'vacation')
                                            Pattern de vacances : baisse estivale
                                        @else
                                            Pattern irrégulier : pas de saisonnalité claire détectée
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if (isset($stats['alertes']) && !empty($stats['alertes']))
            <!-- Alertes -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        Alertes et Recommandations
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach ($stats['alertes'] as $type => $alerte)
                            @if ($alerte && !empty($alerte))
                                <div class="border-l-4 border-orange-400 bg-orange-50 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-orange-400"></i>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-orange-800 capitalize">
                                                {{ str_replace('_', ' ', $type) }}
                                            </h3>
                                            <div class="mt-2 text-sm text-orange-700">
                                                @if (is_array($alerte) && isset($alerte['message']))
                                                    {{ $alerte['message'] }}
                                                    @if (isset($alerte['recommandation']))
                                                        <br><strong>Recommandation:</strong>
                                                        {{ $alerte['recommandation'] }}
                                                    @endif
                                                @else
                                                    {{-- {{ $alerte }} --}}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @can('fonds.export')
            <!-- Actions d'export -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('private.fonds.export') }}?format=excel&periode={{ $periode }}&annee={{ $annee }}&mois={{ $mois }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-file-excel mr-2"></i> Exporter Excel
                        </a>
                        <a href="{{ route('private.fonds.export') }}?format=pdf&periode={{ $periode }}&annee={{ $annee }}&mois={{ $mois }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-file-pdf mr-2"></i> Exporter PDF
                        </a>
                        <a href="{{ route('private.fonds.analytics') }}?annee={{ $annee }}"
                            class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-chart-line mr-2"></i> Analytics Avancées
                        </a>
                    </div>
                </div>
            </div>
        @endcan
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
        <script>
            // Graphique des tendances annuelles
            document.addEventListener('DOMContentLoaded', function() {
                @if (isset($stats['tendances_annuelles']['donnees_mensuelles']) &&
                        $stats['tendances_annuelles']['donnees_mensuelles']->count() > 0)
                    const ctx = document.getElementById('tendancesChart').getContext('2d');
                    const tendancesData = @json($stats['tendances_annuelles']['donnees_mensuelles']);

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: tendancesData.map(item => {
                                const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun',
                                    'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'
                                ];
                                return months[item.mois - 1];
                            }),
                            datasets: [{
                                label: 'Montant (XOF)',
                                data: tendancesData.map(item => item.total),
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4
                            }, {
                                label: 'Nombre de transactions',
                                data: tendancesData.map(item => item.nombre),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                yAxisID: 'y1'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            },
                            scales: {
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    ticks: {
                                        callback: function(value) {
                                            return new Intl.NumberFormat('fr-FR').format(value) + ' XOF';
                                        }
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    grid: {
                                        drawOnChartArea: false,
                                    },
                                }
                            }
                        }
                    });
                @endif
            });

            // Fonction pour actualiser les données
            function refreshData() {
                location.reload();
            }

            // Actualiser toutes les 10 minutes
            setInterval(refreshData, 10 * 60 * 1000);
        </script>
    @endpush
@endsection
