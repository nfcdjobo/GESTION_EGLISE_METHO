@extends('layouts.private.main')
@section('title', 'Statistiques des Programmes')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Programmes</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.programmes.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Programmes
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
                <p class="text-slate-500 mt-1">Tableau de bord et métriques des programmes d'église</p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('private.programmes.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-list mr-2"></i> Liste Programmes
                </a>
                <a href="{{ route('private.programmes.planning') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-calendar mr-2"></i> Planning
                </a>
                <button type="button" onclick="exporterStatistiques()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i> Exporter
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['total'] }}</p>
                    <p class="text-sm text-slate-500">Total programmes</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-play text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['actifs'] }}</p>
                    <p class="text-sm text-slate-500">Programmes actifs</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['planifies'] }}</p>
                    <p class="text-sm text-slate-500">En planification</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['termines'] }}</p>
                    <p class="text-sm text-slate-500">Terminés</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Répartition par type -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                    Répartition par Type
                </h2>
            </div>
            <div class="p-6">
                @if($parType->count() > 0)
                    <div class="space-y-4">
                        @php
                            $totalProgrammes = $parType->sum();
                            $couleurs = [
                                'culte_regulier' => ['bg-blue-500', 'bg-blue-100', 'text-blue-800'],
                                'formation' => ['bg-green-500', 'bg-green-100', 'text-green-800'],
                                'evangelisation' => ['bg-purple-500', 'bg-purple-100', 'text-purple-800'],
                                'jeunesse' => ['bg-pink-500', 'bg-pink-100', 'text-pink-800'],
                                'enfants' => ['bg-yellow-500', 'bg-yellow-100', 'text-yellow-800'],
                                'femmes' => ['bg-rose-500', 'bg-rose-100', 'text-rose-800'],
                                'hommes' => ['bg-cyan-500', 'bg-cyan-100', 'text-cyan-800'],
                                'conference' => ['bg-indigo-500', 'bg-indigo-100', 'text-indigo-800'],
                                'special' => ['bg-amber-500', 'bg-amber-100', 'text-amber-800'],
                                'autre' => ['bg-slate-500', 'bg-slate-100', 'text-slate-800']
                            ];
                        @endphp
                        @foreach($parType->sortDesc() as $type => $nombre)
                            @php
                                $pourcentage = $totalProgrammes > 0 ? round(($nombre / $totalProgrammes) * 100, 1) : 0;
                                $couleur = $couleurs[$type] ?? ['bg-gray-500', 'bg-gray-100', 'text-gray-800'];
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 {{ $couleur[0] }} rounded-full"></div>
                                    <span class="font-medium text-slate-900">
                                        {{ \App\Models\Programme::TYPES_PROGRAMME[$type] ?? ucfirst($type) }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-24 bg-slate-200 rounded-full h-2">
                                        <div class="h-2 {{ $couleur[0] }} rounded-full" style="width: {{ $pourcentage }}%"></div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $couleur[1] }} {{ $couleur[2] }}">
                                        {{ $nombre }} ({{ $pourcentage }}%)
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-pie text-slate-400 text-xl"></i>
                        </div>
                        <p class="text-slate-500">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Répartition par audience -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-users text-green-600 mr-2"></i>
                    Répartition par Audience
                </h2>
            </div>
            <div class="p-6">
                @if($parAudience->count() > 0)
                    <div class="space-y-4">
                        @php
                            $totalAudiences = $parAudience->sum();
                            $couleursAudience = [
                                'tous' => ['bg-blue-500', 'bg-blue-100', 'text-blue-800'],
                                'membres' => ['bg-green-500', 'bg-green-100', 'text-green-800'],
                                'jeunes' => ['bg-purple-500', 'bg-purple-100', 'text-purple-800'],
                                'adultes' => ['bg-indigo-500', 'bg-indigo-100', 'text-indigo-800'],
                                'enfants' => ['bg-yellow-500', 'bg-yellow-100', 'text-yellow-800'],
                                'femmes' => ['bg-pink-500', 'bg-pink-100', 'text-pink-800'],
                                'hommes' => ['bg-cyan-500', 'bg-cyan-100', 'text-cyan-800'],
                                'visiteurs' => ['bg-orange-500', 'bg-orange-100', 'text-orange-800']
                            ];
                        @endphp
                        @foreach($parAudience->sortDesc() as $audience => $nombre)
                            @php
                                $pourcentage = $totalAudiences > 0 ? round(($nombre / $totalAudiences) * 100, 1) : 0;
                                $couleur = $couleursAudience[$audience] ?? ['bg-gray-500', 'bg-gray-100', 'text-gray-800'];
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 {{ $couleur[0] }} rounded-full"></div>
                                    <span class="font-medium text-slate-900">
                                        {{ \App\Models\Programme::AUDIENCES[$audience] ?? ucfirst($audience) }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-24 bg-slate-200 rounded-full h-2">
                                        <div class="h-2 {{ $couleur[0] }} rounded-full" style="width: {{ $pourcentage }}%"></div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $couleur[1] }} {{ $couleur[2] }}">
                                        {{ $nombre }} ({{ $pourcentage }}%)
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-slate-400 text-xl"></i>
                        </div>
                        <p class="text-slate-500">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Répartition par statut et évolution -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                Répartition des Statuts
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                @php
                    $statutsStats = [
                        'planifie' => ['count' => $stats['planifies'], 'color' => 'yellow', 'icon' => 'clock'],
                        'actif' => ['count' => $stats['actifs'], 'color' => 'green', 'icon' => 'play'],
                        'suspendu' => ['count' => $stats['suspendus'], 'color' => 'orange', 'icon' => 'pause'],
                        'termine' => ['count' => $stats['termines'], 'color' => 'purple', 'icon' => 'check'],
                        'annule' => ['count' => $stats['total'] - ($stats['planifies'] + $stats['actifs'] + $stats['suspendus'] + $stats['termines']), 'color' => 'red', 'icon' => 'times']
                    ];
                @endphp
                @foreach($statutsStats as $statut => $data)
                    <div class="text-center">
                        <div class="w-16 h-16 bg-{{ $data['color'] }}-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-{{ $data['icon'] }} text-{{ $data['color'] }}-600 text-xl"></i>
                        </div>
                        <div class="text-2xl font-bold text-slate-800">{{ $data['count'] }}</div>
                        <div class="text-sm text-slate-500 capitalize">{{ \App\Models\Programme::STATUTS[$statut] ?? $statut }}</div>
                        @php $pourcentage = $stats['total'] > 0 ? round(($data['count'] / $stats['total']) * 100, 1) : 0; @endphp
                        <div class="text-xs text-{{ $data['color'] }}-600 font-medium mt-1">{{ $pourcentage }}%</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Métriques détaillées -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Programmes par fréquence -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar-week text-amber-600 mr-2"></i>
                    Par Fréquence
                </h2>
            </div>
            <div class="p-6">
                @php
                    $frequences = \App\Models\Programme::select('frequence', \DB::raw('count(*) as total'))
                        ->groupBy('frequence')
                        ->pluck('total', 'frequence');
                @endphp
                <div class="space-y-3">
                    @foreach(\App\Models\Programme::FREQUENCES as $key => $label)
                        @php $count = $frequences[$key] ?? 0; @endphp
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                            <span class="font-medium text-slate-900">{{ $label }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                {{ $count }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Responsables les plus actifs -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-user-tie text-cyan-600 mr-2"></i>
                    Responsables Actifs
                </h2>
            </div>
            <div class="p-6">
                @php
                    $responsables = \App\Models\Programme::whereNotNull('responsable_principal_id')
                        ->with('responsablePrincipal')
                        ->get()
                        ->groupBy('responsable_principal_id')
                        ->map(function($programmes) {
                            return [
                                'responsable' => $programmes->first()->responsablePrincipal,
                                'count' => $programmes->count(),
                                'actifs' => $programmes->where('statut', 'actif')->count()
                            ];
                        })
                        ->sortByDesc('count')
                        ->take(5);
                @endphp
                @if($responsables->count() > 0)
                    <div class="space-y-4">
                        @foreach($responsables as $data)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">
                                            {{ $data['responsable']->prenom }} {{ $data['responsable']->nom }}
                                        </div>
                                        <div class="text-sm text-slate-600">{{ $data['actifs'] }} actifs sur {{ $data['count'] }}</div>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                    {{ $data['count'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-tie text-slate-400 text-xl"></i>
                        </div>
                        <p class="text-slate-500">Aucun responsable assigné</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Résumé et recommandations -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                Résumé et Recommandations
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Santé des programmes -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-heartbeat text-green-600 mr-2"></i>
                        <h3 class="font-semibold text-green-800">Santé des Programmes</h3>
                    </div>
                    @php
                        $tauxActivite = $stats['total'] > 0 ? round(($stats['actifs'] / $stats['total']) * 100) : 0;
                        $couleurSante = $tauxActivite >= 70 ? 'green' : ($tauxActivite >= 50 ? 'yellow' : 'red');
                    @endphp
                    <div class="text-2xl font-bold text-{{ $couleurSante }}-700 mb-1">{{ $tauxActivite }}%</div>
                    <p class="text-sm text-green-700">de programmes actifs</p>
                </div>

                <!-- Diversité -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-th text-blue-600 mr-2"></i>
                        <h3 class="font-semibold text-blue-800">Diversité</h3>
                    </div>
                    <div class="text-2xl font-bold text-blue-700 mb-1">{{ $parType->count() }}</div>
                    <p class="text-sm text-blue-700">types de programmes différents</p>
                </div>

                <!-- Couverture -->
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-globe text-purple-600 mr-2"></i>
                        <h3 class="font-semibold text-purple-800">Couverture</h3>
                    </div>
                    <div class="text-2xl font-bold text-purple-700 mb-1">{{ $parAudience->count() }}</div>
                    <p class="text-sm text-purple-700">audiences ciblées</p>
                </div>
            </div>

            @php
                $recommandations = [];
                if($tauxActivite < 50) {
                    $recommandations[] = "Considérer l'activation de programmes en planification";
                }
                if($stats['planifies'] > $stats['actifs']) {
                    $recommandations[] = "Beaucoup de programmes en attente - prioriser les lancements";
                }
                if($parType->count() < 3) {
                    $recommandations[] = "Diversifier les types de programmes proposés";
                }
                if($responsables->count() < 3) {
                    $recommandations[] = "Recruter plus de responsables de programmes";
                }
            @endphp

            @if(count($recommandations) > 0)
                <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <h4 class="font-semibold text-amber-800 mb-2 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Recommandations
                    </h4>
                    <ul class="space-y-1">
                        @foreach($recommandations as $recommandation)
                            <li class="text-sm text-amber-700 flex items-start">
                                <i class="fas fa-arrow-right mr-2 mt-1 text-xs"></i>
                                {{ $recommandation }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <h4 class="font-semibold text-green-800 mb-2 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Excellent !
                    </h4>
                    <p class="text-sm text-green-700">Vos programmes sont bien équilibrés et diversifiés.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Fonction d'export
function exporterStatistiques() {
    // Simuler un export - dans la vraie vie, vous pourriez générer un PDF ou Excel
    const stats = {
        total: {{ $stats['total'] }},
        actifs: {{ $stats['actifs'] }},
        planifies: {{ $stats['planifies'] }},
        termines: {{ $stats['termines'] }},
        suspendus: {{ $stats['suspendus'] }},
        parType: @json($parType),
        parAudience: @json($parAudience)
    };

    // Créer un blob avec les données
    const dataStr = JSON.stringify(stats, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});

    // Créer un lien de téléchargement
    const link = document.createElement('a');
    link.href = URL.createObjectURL(dataBlob);
    link.download = `statistiques-programmes-${new Date().toISOString().split('T')[0]}.json`;
    link.click();
}

// Animation des barres de progression au chargement
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('[style*="width:"]');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = width;
        }, 100);
    });
});

// Interactions hover pour les cartes statistiques
const statCards = document.querySelectorAll('.hover\\:-translate-y-1');
statCards.forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-4px)';
    });

    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});
</script>

@endsection
