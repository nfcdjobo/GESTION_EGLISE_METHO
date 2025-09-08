@extends('layouts.private.main')
@section('title', 'Dashboard FIMECO')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Dashboard FIMECO</h1>
            <p class="text-slate-500 mt-1">Vue d'ensemble de votre activité FIMECO - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
        </div>
    </div>

    <!-- FIMECO Active -->
    @if($fimeco_active)
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-2xl shadow-lg border border-white/20 text-white overflow-hidden">
            <div class="p-8">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">{{ $fimeco_active['nom'] }}</h2>
                        <div class="flex items-center space-x-4 text-blue-100">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                {{ $fimeco_active['jours_restants'] }} jours restants
                            </span>
                        </div>
                    </div>
                    @can('subscriptions.create')
                    @if($fimeco_active['peut_souscrire'])
                        <a href="{{ route('private.subscriptions.create') }}?fimeco={{$fimeco_active->id}}" class="inline-flex items-center px-6 py-3 bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium rounded-xl transition-all duration-200">
                            <i class="fas fa-hand-holding-usd mr-2"></i> Souscrire
                        </a>
                    @endif
                    @endcan
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-1">{{ number_format($fimeco_active['total_paye'], 0, ',', ' ') }}</div>
                        <div class="text-blue-100">Total collecté (FCFA)</div>
                    </div>

                    <div class="text-center">
                        <div class="text-3xl font-bold mb-1">{{ $fimeco_active['pourcentage_realisation'] }}%</div>
                        <div class="text-blue-100">Réalisé</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="w-full bg-white bg-opacity-20 rounded-full h-3">
                        <div class="bg-white h-3 rounded-full transition-all duration-500" style="width: {{ min($fimeco_active['pourcentage_realisation'], 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-12 text-center">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-coins text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-900 mb-2">Aucune FIMECO active</h3>
                <p class="text-slate-600 mb-6">Il n'y a actuellement aucune campagne FIMECO en cours.</p>
                @can('fimecos.create')
                    <a href="{{ route('private.fimecos.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Créer une FIMECO
                    </a>
                @endcan
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Statistiques utilisateur -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Métriques personnelles -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-hand-holding-usd text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-slate-800">{{ number_format($statistiques_utilisateur['total_souscrit'] ?? 0, 0, ',', ' ') }}</p>
                            <p class="text-sm text-slate-500">Total souscrit (FCFA)</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-credit-card text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-slate-800">{{ number_format($statistiques_utilisateur['total_paye'] ?? 0, 0, ',', ' ') }}</p>
                            <p class="text-sm text-slate-500">Total payé (FCFA)</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-list text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-slate-800">{{ $statistiques_utilisateur['nombre_souscriptions'] ?? 0 }}</p>
                            <p class="text-sm text-slate-500">Souscriptions</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Souscriptions récentes -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-history text-blue-600 mr-2"></i>
                            Mes Souscriptions Récentes
                        </h2>
                        <a href="{{ route('private.subscriptions.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Voir tout <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if(count($souscriptions_recentes) > 0)
                        <div class="space-y-4">
                            @foreach($souscriptions_recentes as $souscription)
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $souscription['fimeco_nom'] }}</div>
                                        <div class="text-sm text-slate-600">Souscrit le {{ $souscription['date_souscription'] }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-green-600">{{ number_format($souscription['montant_paye'], 0, ',', ' ') }} FCFA</div>
                                        <div class="text-sm text-slate-500">/ {{ number_format($souscription['montant_souscrit'], 0, ',', ' ') }} FCFA</div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mt-1
                                            @if($souscription['statut'] === 'completement_payee') bg-green-100 text-green-800
                                            @elseif($souscription['statut'] === 'partiellement_payee') bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $souscription['statut'])) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-3xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500">Aucune souscription récente</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Paiements récents -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                            Paiements Récents
                        </h2>
                        <a href="{{ route('private.paiements.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Voir tout <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if(count($paiements_recents) > 0)
                        <div class="space-y-4">
                            @foreach($paiements_recents as $paiement)
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $paiement['fimeco_nom'] }}</div>
                                        <div class="text-sm text-slate-600">{{ $paiement['date_paiement'] }} - {{ $paiement['type_paiement'] }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-green-600">{{ number_format($paiement['montant'], 0, ',', ' ') }} FCFA</div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Validé
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-3xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500">Aucun paiement récent</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions rapides -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">

                        <a href="{{ route('private.fimecos.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                            <i class="fas fa-hand-holding-usd mr-2"></i> Voir la liste
                        </a>

                    @can('subscriptions.create')
                    @if($fimeco_active && $fimeco_active['peut_souscrire'])
                        <a href="{{ route('private.subscriptions.create') }}?fimero={{$fimeco_active->id}}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                            <i class="fas fa-hand-holding-usd mr-2"></i> Nouvelle Souscription
                        </a>
                    @endif
                    @endcan
                    <a href="{{ route('private.subscriptions.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-list mr-2"></i> Mes Souscriptions
                    </a>
                    <a href="{{ route('private.paiements.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                        <i class="fas fa-credit-card mr-2"></i> Mes Paiements
                    </a>
                    @can('subscriptions.my-statistics')
                    <a href="{{ route('private.subscriptions.mesStatistiques') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i> Mes Statistiques
                    </a>
                    @endcan
                </div>
            </div>

            <!-- Statistiques globales (si admin) -->
            @if($statistiques_globales)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-globe text-cyan-600 mr-2"></i>
                            Vue Globale
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Paiements en attente:</span>
                            <span class="text-sm font-semibold text-orange-600">{{ $statistiques_globales['paiements_en_attente'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Souscriptions en retard:</span>
                            <span class="text-sm font-semibold text-red-600">{{ $statistiques_globales['souscriptions_en_retard'] }}</span>
                        </div>

                        @if($statistiques_globales['fimeco_active'])
                            <div class="pt-4 border-t border-slate-200">
                                <h3 class="font-medium text-slate-900 mb-3">FIMECO Active</h3>
                                <div class="space-y-2">
                                    @foreach($statistiques_globales['fimeco_active'] as $key => $value)
                                        <div class="flex items-center justify-between text-sm">
                                            <span class="text-slate-600 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                            <span class="font-medium text-slate-900">
                                                @if(is_numeric($value))
                                                    @if($key === 'pourcentage_realisation')
                                                        {{ $value }}%
                                                    @elseif(in_array($key, ['total_paye', 'total_souscriptions', 'reste_a_collecter']))
                                                        {{ number_format($value, 0, ',', ' ') }}
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Progression personnelle -->
            @if(($statistiques_utilisateur['total_souscrit'] ?? 0) > 0)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-chart text-indigo-600 mr-2"></i>
                            Ma Progression
                        </h2>
                    </div>
                    <div class="p-6">
                        @php
                            $tauxPaiement = ($statistiques_utilisateur['total_souscrit'] > 0) ?
                                           round(($statistiques_utilisateur['total_paye'] / $statistiques_utilisateur['total_souscrit']) * 100, 1) : 0;
                        @endphp

                        <div class="text-center mb-4">
                            <div class="text-2xl font-bold text-indigo-600 mb-1">{{ $tauxPaiement }}%</div>
                            <div class="text-sm text-slate-600">Taux de paiement personnel</div>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-3 rounded-full transition-all duration-500"
                                 style="width: {{ $tauxPaiement }}%"></div>
                        </div>

                        <div class="text-center text-sm">
                            <span class="text-green-600 font-semibold">{{ number_format($statistiques_utilisateur['total_paye'], 0, ',', ' ') }} FCFA</span>
                            <span class="text-slate-500"> payés sur </span>
                            <span class="text-blue-600 font-semibold">{{ number_format($statistiques_utilisateur['total_souscrit'], 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
