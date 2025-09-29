@extends('layouts.private.main')
@section('title', 'Statistiques des Dons')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
     <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
            Statistiques des Dons
        </h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.dons.index') }}"
                        class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-dove mr-2"></i>
                        Donations
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Analyse détaillée des donations - {{ \Carbon\Carbon::now()->format('l d F Y') }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>


    <!-- Filtres de période -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                Sélection de la période
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.dons.statistiques') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Période</label>
                    <select name="periode" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" onchange="toggleCustomDates()">
                        <option value="ce_mois" {{ $periode == 'ce_mois' ? 'selected' : '' }}>Ce mois</option>
                        <option value="cette_semaine" {{ $periode == 'cette_semaine' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="aujourd_hui" {{ $periode == 'aujourd_hui' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="cette_annee" {{ $periode == 'cette_annee' ? 'selected' : '' }}>Cette année</option>
                        <option value="personnalisee" {{ $periode == 'personnalisee' ? 'selected' : '' }}>Période personnalisée</option>
                    </select>
                </div>
                <div id="date-debut-container" class="{{ $periode != 'personnalisee' ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div id="date-fin-container" class="{{ $periode != 'personnalisee' ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-chart-bar mr-2"></i> Analyser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('private.dons.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                @can('dons.export')
                    <a href="{{ route('private.dons.exporter', array_merge(['periode' => $periode], request()->only(['date_debut', 'date_fin']))) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i> Exporter période
                    </a>
                @endcan
                @can('dons.report')
                    <button onclick="generateReport()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-file-pdf mr-2"></i> Rapport PDF
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <!-- Résumé principal -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                Résumé de la période
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($statistiques['resume']['total_dons']) }}</div>
                    <div class="text-sm text-slate-500">Total dons</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600 mb-2">{{ number_format($statistiques['resume']['montant_total'], 0, ',', ' ') }}</div>
                    <div class="text-sm text-slate-500">Montant total</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600 mb-2">{{ number_format($statistiques['resume']['montant_moyen'], 0, ',', ' ') }}</div>
                    <div class="text-sm text-slate-500">Montant moyen</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-orange-600 mb-2">{{ number_format($statistiques['resume']['don_maximum'], 0, ',', ' ') }}</div>
                    <div class="text-sm text-slate-500">Don maximum</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-cyan-600 mb-2">{{ number_format($statistiques['resume']['don_minimum'], 0, ',', ' ') }}</div>
                    <div class="text-sm text-slate-500">Don minimum</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analyses détaillées -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Par devise -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-coins text-yellow-600 mr-2"></i>
                    Répartition par devise
                </h2>
            </div>
            <div class="p-6">
                @if($statistiques['par_devise']->count() > 0)
                    <div class="space-y-4">
                        @foreach($statistiques['par_devise'] as $devise)
                            @php
                                $pourcentage = $statistiques['resume']['montant_total'] > 0 ?
                                    round(($devise->total / $statistiques['resume']['montant_total']) * 100, 1) : 0;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @switch($devise->devise)
                                                @case('XOF') bg-orange-100 text-orange-800 @break
                                                @case('EUR') bg-blue-100 text-blue-800 @break
                                                @case('USD') bg-green-100 text-green-800 @break
                                                @default bg-gray-100 text-gray-800 @break
                                            @endswitch">
                                            {{ $devise->devise }}
                                        </span>
                                        <span class="text-sm text-slate-700">{{ $devise->nombre }} don(s)</span>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-900">{{ $pourcentage }}%</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-medium text-slate-900">{{ number_format($devise->total, 0, ',', ' ') }}</span>
                                    <span class="text-slate-500">Moy: {{ number_format($devise->moyenne, 0, ',', ' ') }}</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2 mt-2">
                                    <div class="h-2 rounded-full transition-all duration-300
                                        @switch($devise->devise)
                                            @case('XOF') bg-gradient-to-r from-orange-400 to-orange-500 @break
                                            @case('EUR') bg-gradient-to-r from-blue-400 to-blue-500 @break
                                            @case('USD') bg-gradient-to-r from-green-400 to-green-500 @break
                                            @default bg-gradient-to-r from-gray-400 to-gray-500 @break
                                        @endswitch"
                                        style="width: {{ $pourcentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-coins text-3xl text-slate-400 mb-3"></i>
                        <p class="text-slate-500">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Par opérateur -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-building text-indigo-600 mr-2"></i>
                    Répartition par opérateur
                </h2>
            </div>
            <div class="p-6">
                @if($statistiques['par_operateur']->count() > 0)
                    <div class="space-y-4">
                        @foreach($statistiques['par_operateur'] as $operateur)
                            @php
                                $pourcentage = $statistiques['resume']['total_dons'] > 0 ?
                                    round(($operateur->nombre / $statistiques['resume']['total_dons']) * 100, 1) : 0;
                            @endphp
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <div>
                                    <div class="font-medium text-slate-900">{{ $operateur->operateur }}</div>
                                    <div class="text-sm text-slate-500">{{ $operateur->nombre }} don(s) - {{ $pourcentage }}%</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-green-600">{{ number_format($operateur->total, 0, ',', ' ') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-building text-3xl text-slate-400 mb-3"></i>
                        <p class="text-slate-500">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Par type de paiement -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-credit-card text-purple-600 mr-2"></i>
                    Répartition par type de paiement
                </h2>
            </div>
            <div class="p-6">
                @if($statistiques['par_type_paiement']->count() > 0)
                    <div class="space-y-4">
                        @foreach($statistiques['par_type_paiement'] as $type)
                            @php
                                $pourcentage = $statistiques['resume']['total_dons'] > 0 ?
                                    round(($type->nombre / $statistiques['resume']['total_dons']) * 100, 1) : 0;
                            @endphp
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <div>
                                    <div class="font-medium text-slate-900">{{ ucfirst($type->type) }}</div>
                                    <div class="text-sm text-slate-500">{{ $type->nombre }} don(s) - {{ $pourcentage }}%</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-green-600">{{ number_format($type->total, 0, ',', ' ') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-credit-card text-3xl text-slate-400 mb-3"></i>
                        <p class="text-slate-500">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top donateurs -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-trophy text-amber-600 mr-2"></i>
                    Top donateurs
                </h2>
            </div>
            <div class="p-6">
                @if($statistiques['top_donateurs']->count() > 0)
                    <div class="space-y-4">
                        @foreach($statistiques['top_donateurs'] as $index => $donateur)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm
                                        @if($index === 0) bg-yellow-100 text-yellow-800
                                        @elseif($index === 1) bg-gray-100 text-gray-800
                                        @elseif($index === 2) bg-orange-100 text-orange-800
                                        @else bg-slate-100 text-slate-800
                                        @endif">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $donateur->nom_complet }}</div>
                                        <div class="text-sm text-slate-500">{{ $donateur->telephone_1 }} • {{ $donateur->nombre_dons }} don(s)</div>
                                        <div class="text-xs text-slate-400">Dernier: {{ \Carbon\Carbon::parse($donateur->dernier_don)->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-green-600">{{ number_format($donateur->total_donne, 0, ',', ' ') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-trophy text-3xl text-slate-400 mb-3"></i>
                        <p class="text-slate-500">Aucun donateur trouvé</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Évolution mensuelle -->
    @if($statistiques['evolution_mensuelle']->count() > 0)
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                Évolution mensuelle (12 derniers mois)
            </h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Période</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Nombre de dons</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Montant total</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Evolution</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($statistiques['evolution_mensuelle'] as $index => $mois)
                            @php
                                $precedent = $statistiques['evolution_mensuelle']->get($index + 1);
                                $evolution = null;
                                if ($precedent) {
                                    $evolution = $precedent->total > 0 ?
                                        round((($mois->total - $precedent->total) / $precedent->total) * 100, 1) : 0;
                                }
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                    {{ \Carbon\Carbon::create($mois->annee, $mois->mois, 1)->format('F Y') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-900">{{ $mois->nombre }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-green-600">{{ number_format($mois->total, 0, ',', ' ') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($evolution !== null)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $evolution >= 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            <i class="fas fa-{{ $evolution >= 0 ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                                            {{ abs($evolution) }}%
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
function toggleCustomDates() {
    const periode = document.querySelector('select[name="periode"]').value;
    const dateDebutContainer = document.getElementById('date-debut-container');
    const dateFinContainer = document.getElementById('date-fin-container');

    if (periode === 'personnalisee') {
        dateDebutContainer.classList.remove('hidden');
        dateFinContainer.classList.remove('hidden');
    } else {
        dateDebutContainer.classList.add('hidden');
        dateFinContainer.classList.add('hidden');
    }
}

function generateReport() {
    // Générer un rapport PDF (implémentation selon vos besoins)
    const params = new URLSearchParams(window.location.search);
    const reportUrl = `{{ route('private.dons.rapportPersonnalise') }}?${params.toString()}&format=pdf`;
    window.open(reportUrl, '_blank');
}

// Animation des barres de progression
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('[style*="width"]');
    progressBars.forEach(function(bar) {
        const targetWidth = bar.style.width;
        bar.style.width = '0%';
        setTimeout(function() {
            bar.style.width = targetWidth;
        }, 300);
    });
});
</script>

@endsection
