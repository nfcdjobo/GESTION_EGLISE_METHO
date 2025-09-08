@extends('layouts.private.main')
@section('title', 'Statistiques FIMECO')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques FIMECO</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.fimecos.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-coins mr-2"></i>
                        FIMECO
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
        <p class="text-slate-500 mt-1">Analyse détaillée des performances - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div>

    <!-- Métriques principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-hand-holding-usd text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($statistiques['total_paye'] ?? 0, 0, ',', ' ') }}</p>
                    <p class="text-sm text-slate-500">Total collecté (FCFA)</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-percentage text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['pourcentage_realisation'] ?? 0 }}%</p>
                    <p class="text-sm text-slate-500">Taux de réalisation</p>
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
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['nombre_souscripteurs'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Souscripteurs</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calculator text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($statistiques['montant_moyen_souscription'] ?? 0, 0, ',', ' ') }}</p>
                    <p class="text-sm text-slate-500">Moyenne par souscription</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Progression détaillée -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                    Progression Détaillée
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Objectif vs Réalisé -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Objectif fixé</span>
                        <span class="text-lg font-bold text-blue-600">{{ number_format($statistiques['cible'] ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Total souscriptions</span>
                        <span class="text-lg font-bold text-purple-600">{{ number_format($statistiques['total_souscriptions'] ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Total collecté</span>
                        <span class="text-lg font-bold text-green-600">{{ number_format($statistiques['total_paye'] ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Reste à collecter</span>
                        <span class="text-lg font-bold text-orange-600">{{ number_format($statistiques['reste_a_collecter'] ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>

                <!-- Barre de progression -->
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>Progression globale</span>
                        <span class="font-semibold">{{ $statistiques['pourcentage_realisation'] ?? 0 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-green-500 h-4 rounded-full transition-all duration-500"
                             style="width: {{ min($statistiques['pourcentage_realisation'] ?? 0, 100) }}%"></div>
                    </div>
                </div>

                <!-- Ratio souscriptions vs paiements -->
                @if(($statistiques['total_souscriptions'] ?? 0) > 0)
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Taux de paiement des souscriptions</span>
                            <span class="font-semibold">{{ round((($statistiques['total_paye'] ?? 0) / ($statistiques['total_souscriptions'] ?? 1)) * 100, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-yellow-400 to-green-500 h-2 rounded-full"
                                 style="width: {{ min(round((($statistiques['total_paye'] ?? 0) / ($statistiques['total_souscriptions'] ?? 1)) * 100, 1), 100) }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Analyse par souscripteur -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-users-cog text-purple-600 mr-2"></i>
                    Analyse des Souscripteurs
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-xl">
                        <div class="text-2xl font-bold text-blue-600">{{ $statistiques['nombre_souscripteurs'] ?? 0 }}</div>
                        <div class="text-sm text-blue-700">Souscripteurs actifs</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-xl">
                        <div class="text-2xl font-bold text-green-600">{{ number_format($statistiques['montant_moyen_souscription'] ?? 0, 0, ',', ' ') }}</div>
                        <div class="text-sm text-green-700">Montant moyen</div>
                    </div>
                </div>

                <!-- Top contributeurs (si disponible) -->
                @if(isset($statistiques['top_contributeurs']))
                    <div class="space-y-3">
                        <h3 class="font-semibold text-slate-900">Top Contributeurs</h3>
                        @foreach($statistiques['top_contributeurs'] as $contributeur)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                <div>
                                    <div class="font-medium text-slate-900">{{ $contributeur['nom'] }}</div>
                                    <div class="text-sm text-slate-600">{{ number_format($contributeur['montant_paye'], 0, ',', ' ') }} FCFA payés</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-green-600">
                                        {{ round(($contributeur['montant_paye'] / ($statistiques['total_paye'] ?: 1)) * 100, 1) }}%
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Répartition des statuts -->
                @if(isset($statistiques['repartition_statuts']))
                    <div class="space-y-3">
                        <h3 class="font-semibold text-slate-900">Répartition par Statut</h3>
                        @foreach($statistiques['repartition_statuts'] as $statut => $nombre)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-700 capitalize">{{ str_replace('_', ' ', $statut) }}</span>
                                <span class="text-sm font-semibold text-slate-900">{{ $nombre }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Évolution temporelle -->
    @if(isset($statistiques['evolution_paiements']) && count($statistiques['evolution_paiements']) > 0)
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-area text-green-600 mr-2"></i>
                    Évolution des Paiements
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($statistiques['evolution_paiements'] as $evolution)
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-blue-50 rounded-lg">
                            <div>
                                <div class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($evolution['date'])->format('d/m/Y') }}</div>
                                <div class="text-sm text-slate-600">{{ $evolution['nombre'] }} paiement(s)</div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-green-600">{{ number_format($evolution['total'], 0, ',', ' ') }} FCFA</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Comparaisons et insights -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-lightbulb text-amber-600 mr-2"></i>
                Analyses et Recommandations
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Performance actuelle -->
                <div class="space-y-4">
                    <h3 class="font-semibold text-slate-900 flex items-center">
                        <i class="fas fa-tachometer-alt text-blue-600 mr-2"></i>
                        Performance Actuelle
                    </h3>

                    @php
                        $pourcentage = $statistiques['pourcentage_realisation'] ?? 0;
                        $performance_class = '';
                        $performance_text = '';

                        if ($pourcentage >= 90) {
                            $performance_class = 'text-green-600';
                            $performance_text = 'Excellente performance !';
                        } elseif ($pourcentage >= 70) {
                            $performance_class = 'text-blue-600';
                            $performance_text = 'Bonne progression';
                        } elseif ($pourcentage >= 50) {
                            $performance_class = 'text-yellow-600';
                            $performance_text = 'Progression modérée';
                        } else {
                            $performance_class = 'text-red-600';
                            $performance_text = 'Effort supplémentaire requis';
                        }
                    @endphp

                    <div class="p-4 bg-slate-50 rounded-lg">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-info-circle {{ $performance_class }}"></i>
                            <span class="font-medium {{ $performance_class }}">{{ $performance_text }}</span>
                        </div>
                        <p class="text-sm text-slate-600 mt-2">
                            Avec {{ $pourcentage }}% de l'objectif atteint,
                            @if($pourcentage >= 70)
                                vous êtes sur la bonne voie pour atteindre votre objectif.
                            @else
                                il reste des efforts à fournir pour atteindre l'objectif fixé.
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Recommandations -->
                <div class="space-y-4">
                    <h3 class="font-semibold text-slate-900 flex items-center">
                        <i class="fas fa-bullhorn text-purple-600 mr-2"></i>
                        Recommandations
                    </h3>

                    <div class="space-y-3">
                        @if(($statistiques['pourcentage_realisation'] ?? 0) < 50)
                            <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                <div class="text-sm font-medium text-red-800">Action urgente requise</div>
                                <div class="text-sm text-red-600">Organiser une campagne de sensibilisation</div>
                            </div>
                        @endif

                        @if(($statistiques['nombre_souscripteurs'] ?? 0) < 10)
                            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="text-sm font-medium text-yellow-800">Élargir la base</div>
                                <div class="text-sm text-yellow-600">Recruter plus de souscripteurs</div>
                            </div>
                        @endif

                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="text-sm font-medium text-blue-800">Communication</div>
                            <div class="text-sm text-blue-600">Partager régulièrement les progrès</div>
                        </div>

                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div class="text-sm font-medium text-green-800">Suivi</div>
                            <div class="text-sm text-green-600">Relancer les paiements en retard</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('private.fimecos.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
                <button onclick="window.print()" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i> Imprimer le rapport
                </button>
                <a href="#" onclick="exporterStatistiques()" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i> Exporter en CSV
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exporterStatistiques() {
    // Simuler l'export des statistiques
    const statistiques = @json($statistiques);

    let csvContent = "Métrique,Valeur\n";
    csvContent += `Total payé,${statistiques.total_paye || 0}\n`;
    csvContent += `Pourcentage réalisation,${statistiques.pourcentage_realisation || 0}%\n`;
    csvContent += `Nombre souscripteurs,${statistiques.nombre_souscripteurs || 0}\n`;
    csvContent += `Montant moyen souscription,${statistiques.montant_moyen_souscription || 0}\n`;

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", `statistiques_fimeco_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endpush
@endsection
