@extends('layouts.private.main')
@section('title', 'Tableau de Bord Financier')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    {{-- <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Tableau de Bord Financier</h1>
        <p class="text-slate-500 mt-1">Vue d'ensemble des transactions financières - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div> --}}
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Tableau de Bord Financier</h1>
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
                        <span class="text-sm font-medium text-slate-500">Financier</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Filtres de période -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Période d'analyse
                </h2>
                <div class="flex flex-wrap gap-2">
                    <button onclick="changePeriod('jour')" class="period-btn px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $periode == 'jour' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Aujourd'hui
                    </button>
                    <button onclick="changePeriod('semaine')" class="period-btn px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $periode == 'semaine' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Cette semaine
                    </button>
                    <button onclick="changePeriod('mois')" class="period-btn px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $periode == 'mois' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Ce mois
                    </button>
                    <button onclick="changePeriod('trimestre')" class="period-btn px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $periode == 'trimestre' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Ce trimestre
                    </button>
                    <button onclick="changePeriod('annee')" class="period-btn px-4 py-2 text-sm font-medium rounded-xl transition-colors {{ $periode == 'annee' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                        Cette année
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs principaux -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-money-bill-wave text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($kpis['total_montant'] ?? 0, 0, ',', ' ') }}</p>
                    <p class="text-sm text-slate-500">Total collecté (XOF)</p>
                    @if(isset($comparaisons['total_montant']))
                        <div class="flex items-center mt-1">
                            @if($comparaisons['total_montant']['tendance'] == 'hausse')
                                <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                                <span class="text-xs text-green-600">+{{ $comparaisons['total_montant']['variation'] }}%</span>
                            @elseif($comparaisons['total_montant']['tendance'] == 'baisse')
                                <i class="fas fa-arrow-down text-red-500 text-xs mr-1"></i>
                                <span class="text-xs text-red-600">{{ $comparaisons['total_montant']['variation'] }}%</span>
                            @else
                                <span class="text-xs text-slate-500">Stable</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-receipt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($kpis['total_transactions'] ?? 0) }}</p>
                    <p class="text-sm text-slate-500">Transactions</p>
                    @if(isset($comparaisons['total_transactions']))
                        <div class="flex items-center mt-1">
                            @if($comparaisons['total_transactions']['tendance'] == 'hausse')
                                <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                                <span class="text-xs text-green-600">+{{ $comparaisons['total_transactions']['variation'] }}%</span>
                            @elseif($comparaisons['total_transactions']['tendance'] == 'baisse')
                                <i class="fas fa-arrow-down text-red-500 text-xs mr-1"></i>
                                <span class="text-xs text-red-600">{{ $comparaisons['total_transactions']['variation'] }}%</span>
                            @else
                                <span class="text-xs text-slate-500">Stable</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($kpis['donateurs_uniques'] ?? 0) }}</p>
                    <p class="text-sm text-slate-500">Donateurs uniques</p>
                    @if(isset($comparaisons['donateurs_uniques']))
                        <div class="flex items-center mt-1">
                            @if($comparaisons['donateurs_uniques']['tendance'] == 'hausse')
                                <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                                <span class="text-xs text-green-600">+{{ $comparaisons['donateurs_uniques']['variation'] }}%</span>
                            @elseif($comparaisons['donateurs_uniques']['tendance'] == 'baisse')
                                <i class="fas fa-arrow-down text-red-500 text-xs mr-1"></i>
                                <span class="text-xs text-red-600">{{ $comparaisons['donateurs_uniques']['variation'] }}%</span>
                            @else
                                <span class="text-xs text-slate-500">Stable</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($kpis['montant_moyen'] ?? 0, 0, ',', ' ') }}</p>
                    <p class="text-sm text-slate-500">Montant moyen (XOF)</p>
                    @if(isset($comparaisons['montant_moyen']))
                        <div class="flex items-center mt-1">
                            @if($comparaisons['montant_moyen']['tendance'] == 'hausse')
                                <i class="fas fa-arrow-up text-green-500 text-xs mr-1"></i>
                                <span class="text-xs text-green-600">+{{ $comparaisons['montant_moyen']['variation'] }}%</span>
                            @elseif($comparaisons['montant_moyen']['tendance'] == 'baisse')
                                <i class="fas fa-arrow-down text-red-500 text-xs mr-1"></i>
                                <span class="text-xs text-red-600">{{ $comparaisons['montant_moyen']['variation'] }}%</span>
                            @else
                                <span class="text-xs text-slate-500">Stable</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs secondaires -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-lg font-bold text-slate-800">{{ number_format($kpis['total_dimes'] ?? 0, 0, ',', ' ') }}</p>
                    <p class="text-sm text-slate-500">Dîmes (XOF)</p>
                </div>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hand-holding-heart text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-lg font-bold text-slate-800">{{ number_format($kpis['total_offrandes'] ?? 0, 0, ',', ' ') }}</p>
                    <p class="text-sm text-slate-500">Offrandes (XOF)</p>
                </div>
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-donate text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-lg font-bold text-slate-800">{{ number_format($kpis['total_dons'] ?? 0, 0, ',', ' ') }}</p>
                    <p class="text-sm text-slate-500">Dons spéciaux (XOF)</p>
                </div>
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-gift text-purple-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-lg font-bold text-slate-800">{{ $kpis['transactions_attente'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">En attente</p>
                </div>
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Graphique d'évolution -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-area text-blue-600 mr-2"></i>
                    Évolution des Transactions
                </h2>
            </div>
            <div class="p-6">
                <canvas id="evolutionChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Répartition par type -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                    Répartition par Type
                </h2>
            </div>
            <div class="p-6">
                <canvas id="repartitionChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Répartition par mode de paiement -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-credit-card text-purple-600 mr-2"></i>
                    Modes de Paiement
                </h2>
            </div>
            <div class="p-6">
                <canvas id="modesChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Top donateurs -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-trophy text-amber-600 mr-2"></i>
                    Top Donateurs
                </h2>
            </div>
            <div class="p-6">
                @if($top_donateurs && $top_donateurs->count() > 0)
                    <div class="space-y-4">
                        @foreach($top_donateurs->take(5) as $index => $donateur)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gradient-to-r
                                        @if($index == 0) from-yellow-400 to-yellow-600
                                        @elseif($index == 1) from-gray-300 to-gray-500
                                        @elseif($index == 2) from-orange-400 to-orange-600
                                        @else from-blue-400 to-blue-600
                                        @endif
                                        rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-medium text-slate-900">
                                            @if($donateur->donateur)
                                                {{ $donateur->donateur->nom }} {{ $donateur->donateur->prenom }}
                                            @else
                                                Donateur inconnu
                                            @endif
                                        </div>
                                        <div class="text-sm text-slate-500">{{ $donateur->nombre }} dons</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-slate-900">{{ number_format($donateur->total, 0, ',', ' ') }} XOF</div>
                                    <div class="text-sm text-slate-500">{{ number_format($donateur->moyenne, 0, ',', ' ') }} moy.</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-500">
                        <i class="fas fa-users text-4xl mb-4"></i>
                        <p>Aucun donateur pour cette période</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Transactions récentes et alertes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Transactions récentes -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-clock text-cyan-600 mr-2"></i>
                        Transactions Récentes
                    </h2>
                    <a href="{{ route('private.fonds.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($transactions_recentes && $transactions_recentes->count() > 0)
                    <div class="space-y-4">
                        @foreach($transactions_recentes as $transaction)
                            <div class="flex items-center justify-between p-3 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($transaction->type_transaction, 0, 2)) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-medium text-slate-900">{{ $transaction->numero_transaction }}</div>
                                        <div class="text-sm text-slate-500">
                                            @if($transaction->donateur)
                                                {{ $transaction->donateur->nom }} {{ $transaction->donateur->prenom }}
                                            @elseif($transaction->est_anonyme)
                                                Anonyme
                                            @else
                                                {{ $transaction->nom_donateur_anonyme }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-slate-900">{{ number_format($transaction->montant, 0, ',', ' ') }} {{ $transaction->devise }}</div>
                                    <div class="text-sm">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                            @if($transaction->statut == 'validee') bg-green-100 text-green-800
                                            @elseif($transaction->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($transaction->statut) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-slate-500">
                        <i class="fas fa-inbox text-4xl mb-4"></i>
                        <p>Aucune transaction récente</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Transactions en attente -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-hourglass-half text-orange-600 mr-2"></i>
                    Transactions en Attente
                    @if($transactions_en_attente && $transactions_en_attente->count() > 0)
                        <span class="ml-2 bg-orange-100 text-orange-800 text-sm font-medium px-2.5 py-0.5 rounded-full">{{ $transactions_en_attente->count() }}</span>
                    @endif
                </h2>
            </div>
            <div class="p-6">
                @if($transactions_en_attente && $transactions_en_attente->count() > 0)
                    <div class="space-y-4">
                        @foreach($transactions_en_attente as $transaction)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                        {{ $transaction->jours_attente ?? 0 }}j
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-medium text-slate-900">{{ $transaction->numero_transaction }}</div>
                                        <div class="text-sm text-slate-600">
                                            @if($transaction->nom_donateur)
                                                {{ $transaction->nom_donateur }}
                                            @else
                                                Donateur non spécifié
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-slate-900">{{ number_format($transaction->montant, 0, ',', ' ') }} {{ $transaction->devise }}</div>
                                    <div class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($transaction->date_transaction)->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @can('valider_fonds')
                        <div class="mt-4 text-center">
                            <button onclick="validateAllPending()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                <i class="fas fa-check-double mr-2"></i> Valider tout
                            </button>
                        </div>
                    @endcan
                @else
                    <div class="text-center py-8 text-slate-500">
                        <i class="fas fa-check-circle text-4xl mb-4 text-green-400"></i>
                        <p>Aucune transaction en attente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($echeances && $echeances->count() > 0)
        <!-- Échéances à traiter -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar-check text-red-600 mr-2"></i>
                    Échéances Récurrentes à Traiter
                    <span class="ml-2 bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded-full">{{ $echeances->count() }}</span>
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($echeances as $echeance)
                        <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-medium text-slate-900">{{ $echeance->numero_transaction }}</div>
                                <div class="text-sm text-red-600">
                                    {{ \Carbon\Carbon::parse($echeance->prochaine_echeance)->diffInDays(now()) }} jours de retard
                                </div>
                            </div>
                            <div class="text-sm text-slate-600 mb-2">
                                @if($echeance->donateur)
                                    {{ $echeance->donateur->nom }} {{ $echeance->donateur->prenom }}
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="font-bold text-slate-900">{{ number_format($echeance->montant, 0, ',', ' ') }} {{ $echeance->devise }}</div>
                                <button onclick="processRecurrence('{{ $echeance->id }}')" class="text-blue-600 hover:text-blue-800 text-sm">
                                    <i class="fas fa-plus mr-1"></i>Traiter
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
// Variables globales pour les graphiques
let evolutionChart, repartitionChart, modesChart;

// Changer la période
function changePeriod(periode) {
    const url = new URL(window.location.href);
    url.searchParams.set('periode', periode);
    window.location.href = url.toString();
}

// Initialiser les graphiques
document.addEventListener('DOMContentLoaded', function() {
    initEvolutionChart();
    initRepartitionChart();
    initModesChart();
});

// Graphique d'évolution
function initEvolutionChart() {
    const ctx = document.getElementById('evolutionChart').getContext('2d');
    const evolutionData = @json($evolution ?? []);

    evolutionChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: evolutionData.map(item => item.periode),
            datasets: [{
                label: 'Montant (XOF)',
                data: evolutionData.map(item => item.total),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }, {
                label: 'Nombre de transactions',
                data: evolutionData.map(item => item.nombre),
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true
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
}

// Graphique de répartition par type
function initRepartitionChart() {
    const ctx = document.getElementById('repartitionChart').getContext('2d');
    const repartitionData = @json($repartition_types ?? []);

    repartitionChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: repartitionData.map(item => {
                return item.type_transaction.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            }),
            datasets: [{
                data: repartitionData.map(item => item.total),
                backgroundColor: [
                    '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                    '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${context.label}: ${new Intl.NumberFormat('fr-FR').format(value)} XOF (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Graphique des modes de paiement
function initModesChart() {
    const ctx = document.getElementById('modesChart').getContext('2d');
    const modesData = @json($repartition_modes ?? []);

    modesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: modesData.map(item => {
                const labels = {
                    'especes': 'Espèces',
                    'mobile_money': 'Mobile Money',
                    'virement': 'Virement',
                    'cheque': 'Chèque',
                    'nature': 'Don en nature'
                };
                return labels[item.mode_paiement] || item.mode_paiement;
            }),
            datasets: [{
                label: 'Nombre de transactions',
                data: modesData.map(item => item.nombre),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 1
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
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Valider toutes les transactions en attente
function validateAllPending() {
    if (confirm('Valider toutes les transactions en attente ?')) {
        // Ici on devrait faire un appel API pour valider en masse
        alert('Fonctionnalité à implémenter');
    }
}

// Traiter une récurrence
function processRecurrence(transactionId) {
    if (confirm('Créer une nouvelle transaction pour cette récurrence ?')) {
        fetch(`{{route('private.fonds.duplicate', ':fond')}}`.replace(':fond', transactionId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Rafraîchir les données toutes les 5 minutes
setInterval(() => {
    location.reload();
}, 5 * 60 * 1000);
</script>
@endpush
@endsection
