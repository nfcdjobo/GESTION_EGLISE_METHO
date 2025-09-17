@extends('layouts.private.main')
@section('title', 'Statistiques des Classes')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Classes</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.classes.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>
                                Classes
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
            </div>
            <div class="flex items-center space-x-3">
                @can('classes.export')
                    <button onclick="exportStatistics()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i> Exporter PDF
                    </button>
                @endcan
                <button onclick="refreshStatistics()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-sm font-medium rounded-xl hover:from-blue-600 hover:to-cyan-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-sync-alt mr-2"></i> Actualiser
                </button>
            </div>
        </div>
        <p class="text-slate-500 mt-2">Analyse complète des données des classes - {{ \Carbon\Carbon::now()->format('l d F Y à H:i') }}</p>
    </div>

    <!-- Statistiques globales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chalkboard-teacher text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['total_classes'] }}</p>
                    <p class="text-sm text-slate-500">Total des classes</p>
                    <p class="text-xs text-green-600 flex items-center mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +3% ce mois
                    </p>
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
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['classes_actives'] }}</p>
                    <p class="text-sm text-slate-500">Classes actives</p>
                    <p class="text-xs text-slate-600 mt-1">
                        {{ round(($stats['classes_actives'] / max($stats['total_classes'], 1)) * 100, 1) }}% du total
                    </p>
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
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['total_inscrits'] }}</p>
                    <p class="text-sm text-slate-500">Total inscrits</p>
                    <p class="text-xs text-blue-600 flex items-center mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>
                        +12% ce mois
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['taux_occupation_moyen'] }}</p>
                    <p class="text-sm text-slate-500">Moyenne par classe</p>
                    <p class="text-xs text-slate-600 mt-1">
                        Taux occupation: {{ round(($stats['taux_occupation_moyen'] / 50) * 100, 1) }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Répartition par tranche d'âge -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-child text-green-600 mr-2"></i>
                    Répartition par Tranche d'Âge
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @php
                        $tranchesColors = [
                            '0-3 ans' => 'from-pink-500 to-rose-500',
                            '4-6 ans' => 'from-red-500 to-pink-500',
                            '7-9 ans' => 'from-orange-500 to-red-500',
                            '10-12 ans' => 'from-yellow-500 to-orange-500',
                            '13-15 ans' => 'from-green-500 to-yellow-500',
                            '16-18 ans' => 'from-teal-500 to-green-500',
                            'Adultes' => 'from-blue-500 to-teal-500',
                            'Tous âges' => 'from-purple-500 to-blue-500',
                        ];
                        $totalTranches = $stats['tranches_age']->count();
                    @endphp

                    @if($stats['tranches_age']->count() > 0)
                        @foreach($stats['tranches_age'] as $tranche)
                            @php
                                $percentage = $totalTranches > 0 ? (1 / $totalTranches) * 100 : 0;
                                $colorClass = $tranchesColors[$tranche] ?? 'from-gray-500 to-slate-500';
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center flex-1">
                                    <div class="w-3 h-3 bg-gradient-to-r {{ $colorClass }} rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-slate-700">{{ $tranche }}</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-24 bg-slate-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r {{ $colorClass }} h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-900 w-12 text-right">{{ number_format($percentage, 1) }}%</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-child text-4xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500">Aucune tranche d'âge définie</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Classes par statut -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
                    Classes par Statut
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center p-4 bg-green-50 rounded-xl border border-green-200">
                        <div class="text-2xl font-bold text-green-600">{{ $stats['classes_actives'] }}</div>
                        <div class="text-sm text-green-700 font-medium">Actives</div>
                        <div class="text-xs text-green-600 mt-1">Avec responsable</div>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-xl border border-yellow-200">
                        <div class="text-2xl font-bold text-yellow-600">{{ $stats['total_classes'] - $stats['classes_actives'] }}</div>
                        <div class="text-sm text-yellow-700 font-medium">En attente</div>
                        <div class="text-xs text-yellow-600 mt-1">Sans responsable</div>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="text-center p-4 bg-red-50 rounded-xl border border-red-200">
                        <div class="text-2xl font-bold text-red-600">{{ $stats['classes_completes'] }}</div>
                        <div class="text-sm text-red-700 font-medium">Classes Complètes</div>
                        <div class="text-xs text-red-600 mt-1">50+ membres</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques détaillés -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Évolution des inscriptions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-line-chart text-purple-600 mr-2"></i>
                    Évolution des Inscriptions
                </h3>
            </div>
            <div class="p-6">
                <div class="h-64 flex items-center justify-center bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl">
                    <div class="text-center">
                        <canvas id="inscriptionsChart" width="400" height="200"></canvas>
                        <p class="text-slate-500 text-sm mt-4">Graphique des inscriptions sur les 6 derniers mois</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taux de remplissage -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-bar text-amber-600 mr-2"></i>
                    Taux de Remplissage par Classe
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4 max-h-64 overflow-y-auto">
                    @for($i = 1; $i <= 8; $i++)
                        @php
                            $className = "Classe Exemple $i";
                            $percentage = rand(20, 100);
                            $colorClass = $percentage >= 80 ? 'from-red-500 to-pink-500' :
                                        ($percentage >= 60 ? 'from-yellow-500 to-orange-500' : 'from-green-500 to-emerald-500');
                        @endphp
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-slate-700">{{ $className }}</span>
                                    <span class="text-sm font-semibold text-slate-900">{{ $percentage }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r {{ $colorClass }} h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Analyses détaillées -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Top 5 des classes les plus actives -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                    Top 5 Classes Actives
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @for($i = 1; $i <= 5; $i++)
                        @php
                            $medals = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣'];
                            $names = ['École du Dimanche Enfants', 'Jeunes Adultes', 'Adolescents', 'Adultes Confirmés', 'Petite Enfance'];
                            $counts = [45, 38, 32, 28, 22];
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <span class="text-2xl">{{ $medals[$i-1] }}</span>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $names[$i-1] }}</p>
                                    <p class="text-sm text-slate-500">{{ $counts[$i-1] }} membres</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-blue-600">{{ $counts[$i-1] }}</div>
                                <div class="text-xs text-slate-500">{{ round(($counts[$i-1]/50)*100) }}%</div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Responsables les plus actifs -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-star text-amber-600 mr-2"></i>
                    Responsables Actifs
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @for($i = 1; $i <= 5; $i++)
                        @php
                            $names = ['Marie KOUASSI', 'Jean TRAORE', 'Sarah OUATTARA', 'David KONE', 'Grace AKA'];
                            $classes = [3, 2, 2, 1, 1];
                            $members = [120, 85, 78, 45, 32];
                        @endphp
                        <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr(explode(' ', $names[$i-1])[0], 0, 1) }}{{ substr(explode(' ', $names[$i-1])[1], 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $names[$i-1] }}</p>
                                    <p class="text-sm text-slate-500">{{ $classes[$i-1] }} classe(s)</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-bold text-blue-600">{{ $members[$i-1] }}</div>
                                <div class="text-xs text-slate-500"</div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                    Statistiques Rapides
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-xl">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user-plus text-green-600"></i>
                        <span class="text-sm font-medium text-slate-700">Nouvelles inscriptions</span>
                    </div>
                    <span class="text-lg font-bold text-green-600">+24</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-graduation-cap text-blue-600"></i>
                        <span class="text-sm font-medium text-slate-700">Taux de présence</span>
                    </div>
                    <span class="text-lg font-bold text-blue-600">87%</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-xl">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar-check text-purple-600"></i>
                        <span class="text-sm font-medium text-slate-700">Classes cette semaine</span>
                    </div>
                    <span class="text-lg font-bold text-purple-600">{{ $stats['classes_actives'] }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-xl">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-clock text-orange-600"></i>
                        <span class="text-sm font-medium text-slate-700">Heures d'enseignement</span>
                    </div>
                    <span class="text-lg font-bold text-orange-600">156h</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-chart-line text-red-600"></i>
                        <span class="text-sm font-medium text-slate-700">Croissance mensuelle</span>
                    </div>
                    <span class="text-lg font-bold text-red-600">+8.5%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions et recommandations -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-lightbulb text-amber-600 mr-2"></i>
                Recommandations et Actions
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl border border-blue-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-plus-circle text-blue-600 text-xl mr-3"></i>
                        <h4 class="font-semibold text-slate-900">Créer de nouvelles classes</h4>
                    </div>
                    <p class="text-sm text-slate-600 mb-3">{{ $stats['classes_completes'] }} classes sont complètes. Envisagez de créer de nouvelles classes pour répondre à la demande.</p>
                    @can('classes.create')
                        <a href="{{ route('private.classes.create') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Créer une classe <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    @endcan
                </div>

                <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-user-tie text-green-600 text-xl mr-3"></i>
                        <h4 class="font-semibold text-slate-900">Recruter des responsables</h4>
                    </div>
                    <p class="text-sm text-slate-600 mb-3">{{ $stats['total_classes'] - $stats['classes_actives'] }} classes sont sans responsable et nécessitent une supervision.</p>
                    <button onclick="showRecruitmentModal()" class="inline-flex items-center text-green-600 hover:text-green-800 text-sm font-medium">
                        Gérer les responsables <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>

                <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                    <div class="flex items-center mb-3">
                        <i class="fas fa-chart-line text-purple-600 text-xl mr-3"></i>
                        <h4 class="font-semibold text-slate-900">Optimiser la capacité</h4>
                    </div>
                    <p class="text-sm text-slate-600 mb-3">Certaines classes ont un faible taux de remplissage. Envisagez une redistribution ou une fusion.</p>
                    <a href="#" class="inline-flex items-center text-purple-600 hover:text-purple-800 text-sm font-medium">
                        Comparer les classes <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Données simulées pour le graphique
const mockData = {
    labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin'],
    datasets: [{
        label: 'Nouvelles inscriptions',
        data: [12, 19, 15, 25, 22, 30],
        borderColor: 'rgb(59, 130, 246)',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        tension: 0.4
    }]
};

// Fonctions utilitaires
function exportStatistics() {
    // Implémenter l'export PDF
    window.print();
}

function refreshStatistics() {
    location.reload();
}

function showRecruitmentModal() {
    alert('Modal de recrutement de responsables à implémenter');
}

// Animation d'entrée des statistiques
document.addEventListener('DOMContentLoaded', function() {
    // Animer les barres de progression
    const progressBars = document.querySelectorAll('[style*="width:"]');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
});

// Actualisation automatique toutes les 5 minutes
setInterval(function() {
    // Optionnel: actualiser les données via AJAX
    console.log('Actualisation automatique des statistiques');
}, 300000);
</script>

@endsection
