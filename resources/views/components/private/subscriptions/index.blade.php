@extends('layouts.private.main')
@section('title', 'Liste des Souscriptions')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Liste des Souscriptions</h1>
            <p class="text-slate-500 mt-1">Gérez vos souscriptions FIMECO - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Actions
                </h2>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('private.subscriptions.mesStatistiques') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Mes Statistiques
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <!-- Formulaire de recherche avancée -->
            <form method="GET" action="{{ route('private.subscriptions.index') }}" class="space-y-6">
                <!-- Recherche générale -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Recherche générale</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Nom du souscripteur, ID de souscription..."
                                   class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">FIMECO</label>
                        <select name="fimeco_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Toutes les FIMECO</option>
                            <!-- Les options seront remplies dynamiquement -->
                        </select>
                    </div>
                </div>

                <!-- Filtres avancés -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                        <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('statut') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="partiellement_payee" {{ request('statut') == 'partiellement_payee' ? 'selected' : '' }}>Partiellement payée</option>
                            <option value="completement_payee" {{ request('statut') == 'completement_payee' ? 'selected' : '' }}>Complètement payée</option>
                            <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Montant min (FCFA)</label>
                        <input type="number" name="montant_min" value="{{ request('montant_min') }}"
                               placeholder="0" min="0" step="1000"
                               class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Montant max (FCFA)</label>
                        <input type="number" name="montant_max" value="{{ request('montant_max') }}"
                               placeholder="Illimité" min="0" step="1000"
                               class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date de souscription</label>
                        <input type="date" name="date_souscription" value="{{ request('date_souscription') }}"
                               class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-wrap gap-2 pt-4 border-t border-slate-200">
                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="{{ route('private.subscriptions.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                    <button type="button" id="toggleAdvanced" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-300 transition-colors">
                        <i class="fas fa-cog mr-2"></i> Options avancées
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des souscriptions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Souscriptions ({{ $meta['total'] }})
                </h2>
                <!-- Toggle vue grille/liste -->
                <div class="flex items-center bg-gray-100 rounded-lg p-1">
                    <button id="gridView" class="view-toggle px-3 py-1 rounded-md transition-all duration-200 text-sm font-medium bg-blue-600 text-white">
                        <i class="fas fa-th mr-1"></i> Grille
                    </button>
                    <button id="listView" class="view-toggle px-3 py-1 rounded-md transition-all duration-200 text-sm font-medium text-gray-600 hover:text-gray-800">
                        <i class="fas fa-list mr-1"></i> Liste
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if(count($subscriptions) > 0)
                <!-- Vue Grille (par défaut) -->
                <div id="gridContainer" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($subscriptions as $subscription)
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $subscription['fimeco']['nom'] }}</h3>
                                        <p class="text-xs text-slate-400 mb-1">ID: {{ substr($subscription['id'], 0, 8) }}...</p>
                                        <p class="text-sm text-slate-600">Souscrit le {{ \Carbon\Carbon::parse($subscription['date_souscription'])->format('d/m/Y') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($subscription['statut'] === 'completement_payee') bg-green-100 text-green-800
                                        @elseif($subscription['statut'] === 'partiellement_payee') bg-yellow-100 text-yellow-800
                                        @elseif($subscription['statut'] === 'active') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $subscription['statut'])) }}
                                    </span>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <a href="{{route('private.users.show', $subscription->souscripteur->id)}}" class="text-slate-600">Souscripteur:</a>
                                        <a href="{{route('private.users.show', $subscription->souscripteur->id)}}" class="font-medium text-blue-600 hover:text-blue-800 transition-colors">{{ $subscription->souscripteur->nom. ' '. $subscription->souscripteur->prenom }}</a>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-600">Montant souscrit:</span>
                                        <span class="font-medium text-blue-600">{{ number_format($subscription['montant_souscrit'], 0, ',', ' ') }} FCFA</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-600">Montant payé:</span>
                                        <span class="font-medium text-green-600">{{ number_format($subscription['montant_paye'], 0, ',', ' ') }} FCFA</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-slate-600">Reste à payer:</span>
                                        <span class="font-medium text-orange-600">{{ number_format($subscription['reste_a_payer'], 0, ',', ' ') }} FCFA</span>
                                    </div>

                                    <!-- Barre de progression -->
                                    @php
                                        $pourcentagePaye = ($subscription['montant_souscrit'] > 0) ? round(($subscription['montant_paye'] / $subscription['montant_souscrit']) * 100, 1) : 0;
                                    @endphp
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full" style="width:  {{ $pourcentagePaye > 100 ? 100 : $pourcentagePaye }}%"></div>
                                    </div>
                                    <div class="text-center text-sm font-medium text-slate-700">
                                        {{ $pourcentagePaye }}% payé
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 mt-6 pt-4 border-t border-slate-200">
                                    <a href="{{ route('private.subscriptions.show', $subscription['id']) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors">
                                        <i class="fas fa-eye mr-1 text-sm"></i> Voir
                                    </a>
                                    @if(in_array($subscription['statut'], ['active', 'partiellement_payee']))
                                        <a href="{{ route('private.subscriptions.edit', $subscription['id']) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors">
                                            <i class="fas fa-edit mr-1 text-sm"></i> Modifier
                                        </a>
                                        @if($subscription['reste_a_payer'] > 0)
                                            <a href="{{ route('private.paiements.create', $subscription['id']) }}" class="flex-1 inline-flex items-center justify-center px-3 py-2 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors">
                                                <i class="fas fa-plus mr-1 text-sm"></i> Solder
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Vue Liste -->
                <div id="listContainer" class="space-y-4 hidden">
                    @foreach($subscriptions as $subscription)
                        <div class="bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-300">
                            <div class="p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4 flex-1">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h3 class="text-lg font-bold text-slate-900 truncate">{{ $subscription['fimeco']['nom'] }}</h3>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    @if($subscription['statut'] === 'completement_payee') bg-green-100 text-green-800
                                                    @elseif($subscription['statut'] === 'partiellement_payee') bg-yellow-100 text-yellow-800
                                                    @elseif($subscription['statut'] === 'active') bg-blue-100 text-blue-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $subscription['statut'])) }}
                                                </span>
                                            </div>

                                            @php
                                                    $pourcentagePaye = ($subscription['montant_souscrit'] > 0) ? round(($subscription['montant_paye'] / $subscription['montant_souscrit']) * 100, 1) : 0;
                                                @endphp

                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                <div>
                                                    <span class="text-slate-600">Souscripteur:</span>
                                                    <a href="{{route('private.users.show', $subscription->souscripteur->id)}}" class="block font-medium text-blue-600 hover:text-blue-800 transition-colors">{{ $subscription->souscripteur->nom. ' '. $subscription->souscripteur->prenom }}</a>
                                                </div>
                                                <div>
                                                    <span class="text-slate-600">Souscrit:</span>
                                                    <div class="font-medium text-blue-600">{{ number_format($subscription['montant_souscrit'], 0, ',', ' ') }} FCFA</div>
                                                </div>
                                                <div>
                                                    <span class="text-slate-600">Payé:</span>
                                                    <div class="font-medium text-green-600">{{ number_format($subscription['montant_paye'], 0, ',', ' ') }} FCFA</div>
                                                </div>
                                                <div>
                                                    <span class="text-slate-600">Reste:</span>
                                                    <div class="font-medium text-orange-600">{{ number_format($subscription['reste_a_payer'], 0, ',', ' ') }} FCFA</div>
                                                </div>
                                            </div>
                                            <div class="mt-3">

                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full" style="width:  {{ $pourcentagePaye > 100 ? 100 : $pourcentagePaye }}%"></div>
                                                </div>
                                                <div class="text-xs text-slate-600 mt-1">{{ $pourcentagePaye }}% payé - Souscrit le {{ \Carbon\Carbon::parse($subscription['date_souscription'])->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('private.subscriptions.show', $subscription['id']) }}" class="inline-flex items-center px-3 py-2 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors">
                                                <i class="fas fa-eye mr-1 text-sm"></i> Voir
                                            </a>
                                            @if(in_array($subscription['statut'], ['active', 'partiellement_payee']))
                                                <a href="{{ route('private.subscriptions.edit', $subscription['id']) }}" class="inline-flex items-center px-3 py-2 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors">
                                                    <i class="fas fa-edit mr-1 text-sm"></i> Modifier
                                                </a>
                                                @if($subscription['reste_a_payer'] > 0)
                                                    <a href="{{ route('private.paiements.create', $subscription['id']) }}" class="inline-flex items-center px-3 py-2 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors">
                                                        <i class="fas fa-plus mr-1 text-sm"></i> Solder
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-8 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium">{{ ($meta['current_page'] - 1) * $meta['per_page'] + 1 }}</span> à <span class="font-medium">{{ min($meta['current_page'] * $meta['per_page'], $meta['total']) }}</span>
                        sur <span class="font-medium">{{ $meta['total'] }}</span> résultats
                    </div>
                    <div class="flex items-center gap-2">
                        @if($meta['current_page'] > 1)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $meta['current_page'] - 1]) }}" class="px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Précédent</a>
                        @endif
                        @if($meta['current_page'] < $meta['last_page'])
                            <a href="{{ request()->fullUrlWithQuery(['page' => $meta['current_page'] + 1]) }}" class="px-3 py-2 text-sm bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Suivant</a>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-holding-usd text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune souscription trouvée</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['search', 'fimeco_id', 'statut', 'montant_min', 'montant_max', 'date_souscription']))
                            Aucune souscription ne correspond à vos critères de recherche.
                        @else
                            Vous n'avez pas encore de souscription. Commencez par souscrire à une FIMECO.
                        @endif
                    </p>
                    <a href="{{ route('private.subscriptions.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Nouvelle Souscription
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Résumé rapide -->
    @if(count($subscriptions) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $totalSouscrit = collect($subscriptions)->sum('montant_souscrit');
                $totalPaye = collect($subscriptions)->sum('montant_paye');
                $totalReste = collect($subscriptions)->sum('reste_a_payer');
                $pourcentageGlobal = $totalSouscrit > 0 ? round(($totalPaye / $totalSouscrit) * 100, 1) : 0;
            @endphp

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-hand-holding-usd text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($subscriptions->sum('montant_souscrit'), 0, ',', ' ') }}</p>
                        <p class="text-sm text-slate-500">Total souscrit (FCFA)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($subscriptions->sum('montant_paye'), 0, ',', ' ') }}</p>
                        <p class="text-sm text-slate-500">Total payé (FCFA)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($subscriptions->sum('reste_a_payer'), 0, ',', ' ') }}</p>
                        <p class="text-sm text-slate-500">Reste à payer (FCFA)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-percentage text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $subscriptions->sum('montant_souscrit') > 0 ? round(($subscriptions->sum('montant_paye') / $subscriptions->sum('montant_souscrit')) * 100, 1) : 0 }}%</p>
                        <p class="text-sm text-slate-500">Taux de paiement</p> {{-- Pourcentage global des montants payés par rapport aux montants souscrits --}}
                        
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle entre vue grille et liste
    const gridViewBtn = document.getElementById('gridView');
    const listViewBtn = document.getElementById('listView');
    const gridContainer = document.getElementById('gridContainer');
    const listContainer = document.getElementById('listContainer');

    // Récupérer la vue sauvegardée ou utiliser 'grid' par défaut
    const savedView = localStorage.getItem('subscriptionView') || 'grid';

    function showGridView() {
        gridContainer.classList.remove('hidden');
        listContainer.classList.add('hidden');
        gridViewBtn.classList.add('bg-blue-600', 'text-white');
        gridViewBtn.classList.remove('text-gray-600', 'hover:text-gray-800');
        listViewBtn.classList.remove('bg-blue-600', 'text-white');
        listViewBtn.classList.add('text-gray-600', 'hover:text-gray-800');
        localStorage.setItem('subscriptionView', 'grid');
    }

    function showListView() {
        gridContainer.classList.add('hidden');
        listContainer.classList.remove('hidden');
        listViewBtn.classList.add('bg-blue-600', 'text-white');
        listViewBtn.classList.remove('text-gray-600', 'hover:text-gray-800');
        gridViewBtn.classList.remove('bg-blue-600', 'text-white');
        gridViewBtn.classList.add('text-gray-600', 'hover:text-gray-800');
        localStorage.setItem('subscriptionView', 'list');
    }

    // Appliquer la vue sauvegardée au chargement
    if (savedView === 'list') {
        showListView();
    } else {
        showGridView();
    }

    // Event listeners pour les boutons de vue
    gridViewBtn.addEventListener('click', showGridView);
    listViewBtn.addEventListener('click', showListView);

    // Toggle options avancées (si nécessaire)
    const toggleAdvanced = document.getElementById('toggleAdvanced');
    if (toggleAdvanced) {
        toggleAdvanced.addEventListener('click', function() {
            // Logique pour afficher/masquer des options avancées supplémentaires
            console.log('Options avancées clicked');
        });
    }

    // Animation au survol des cartes
    const cards = document.querySelectorAll('[class*="hover:shadow"]');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>

<style>
/* Animations personnalisées pour les transitions */
.view-toggle {
    transition: all 0.2s ease-in-out;
}

.view-toggle:hover {
    transform: translateY(-1px);
}

/* Améliorations responsive pour la vue liste */
@media (max-width: 768px) {
    #listContainer .grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

/* Animation de fade pour le changement de vue */
#gridContainer, #listContainer {
    transition: opacity 0.3s ease-in-out;
}

/* Amélioration de l'accessibilité */
button:focus, a:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}
</style>
@endsection
