@extends('layouts.private.main')
@section('title', 'Tableau de Bord - Souscriptions')

@section('content')
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Tableau de Bord des Souscriptions
                </h1>
                <p class="text-slate-500 mt-1">
                    Vue d'ensemble et analyses des souscriptions FIMECO -
                    {{ \Carbon\Carbon::now()->locale('fr')->format('l d F Y') }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('private.subscriptions.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-list mr-2"></i> Liste des souscriptions
                </a>
                @can('subscriptions.create')
                    <a href="{{ route('private.subscriptions.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i> Nouvelle souscription
                    </a>
                @endcan
            </div>
        </div>

        <!-- Alertes importantes -->
        @if(count($alertes) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @foreach($alertes as $alerte)
                    <div
                        class="p-4 rounded-xl border @if($alerte['type'] === 'danger') bg-red-50 border-red-200 @elseif($alerte['type'] === 'warning') bg-yellow-50 border-yellow-200 @else bg-blue-50 border-blue-200 @endif">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($alerte['type'] === 'danger')
                                    <i class="fas fa-exclamation-triangle text-red-400 text-lg"></i>
                                @elseif($alerte['type'] === 'warning')
                                    <i class="fas fa-exclamation-circle text-yellow-400 text-lg"></i>
                                @else
                                    <i class="fas fa-info-circle text-blue-400 text-lg"></i>
                                @endif
                            </div>
                            <div class="ml-3 flex-1">
                                <p
                                    class="text-sm font-medium @if($alerte['type'] === 'danger') text-red-800 @elseif($alerte['type'] === 'warning') text-yellow-800 @else text-blue-800 @endif">
                                    {{ $alerte['message'] }}
                                </p>
                                <div class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium @if($alerte['type'] === 'danger') bg-red-100 text-red-800 @elseif($alerte['type'] === 'warning') bg-yellow-100 text-yellow-800 @else bg-blue-100 text-blue-800 @endif">
                                        {{ $alerte['count'] }} cas
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Statistiques globales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-handshake text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ number_format($statistiques_globales['total_souscriptions'] ?? 0) }}</p>
                        <p class="text-sm text-slate-500">Total Souscriptions</p>
                    </div>

                    <div
                        class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-hourglass-half text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-slate-800">
                                    {{ number_format($statistiques_globales['souscriptions_actives'] ?? 0) }}</p>
                                <p class="text-sm text-slate-500">Actives</p>
                                <p class="text-xs text-orange-600">
                                    {{ number_format($statistiques_globales['progression_moyenne'] ?? 0, 1) }}% progression
                                    moy.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-slate-800">
                                    {{ number_format($statistiques_globales['souscriptions_en_retard'] ?? 0) }}</p>
                                <p class="text-sm text-slate-500">En retard</p>
                                <p class="text-xs text-red-600">Nécessitent une attention</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphiques et analyses -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Performance mensuelle -->
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                                Performance mensuelle
                            </h2>
                            <p class="text-slate-500 text-sm mt-1">
                                Évolution des souscriptions sur 12 mois
                            </p>
                        </div>

                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($performance_mensuelle as $index => $mois)
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span
                                                    class="text-sm font-medium text-slate-700">{{ \Carbon\Carbon::parse($mois['mois'])->locale('fr')->format('F Y') }}</span>
                                                <span class="text-sm text-slate-600">{{ $mois['nouvelles_souscriptions'] }}
                                                    nouvelles</span>
                                            </div>
                                            <div class="w-full bg-slate-200 rounded-full h-2">
                                                <div class="h-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500"
                                                    style="width: {{ $mois['nouvelles_souscriptions'] > 0 ? min(($mois['nouvelles_souscriptions'] / max(array_column($performance_mensuelle, 'nouvelles_souscriptions'))) * 100, 100) : 0 }}%">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4 text-right">
                                            <div class="text-sm font-bold text-green-600">
                                                {{ $mois['souscriptions_completees'] }}</div>
                                            <div class="text-xs text-slate-500">complétées</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Top souscripteurs -->
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                                Top souscripteurs
                            </h2>
                            <p class="text-slate-500 text-sm mt-1">
                                Classement par montant total souscrit
                            </p>
                        </div>

                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($top_souscripteurs as $index => $souscripteur)
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-500' : ($index === 1 ? 'bg-gray-400' : ($index === 2 ? 'bg-amber-600' : 'bg-slate-300')) }} flex items-center justify-center">
                                                <span class="text-white text-sm font-bold">{{ $index + 1 }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="font-medium text-slate-900">{{ $souscripteur['nom'] }}</div>
                                            <div class="text-sm text-slate-500">{{ $souscripteur['nb_souscriptions'] }}
                                                souscription(s)</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-bold text-slate-900">
                                                {{ number_format($souscripteur['montant_total_souscrit'], 0, ',', ' ') }}</div>
                                            <div class="text-xs text-green-600">
                                                {{ number_format($souscripteur['taux_paiement'], 1) }}% payé</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Souscriptions urgentes et évolution -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Souscriptions urgentes -->
                    <div
                        class="lg:col-span-2 bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                                Souscriptions nécessitant une attention
                            </h2>
                            <p class="text-slate-500 text-sm mt-1">
                                En retard ou arrivant à échéance prochainement
                            </p>
                        </div>

                        <div class="p-6">
                            @if(count($souscriptions_urgentes) > 0)
                                <div class="space-y-4">
                                    @foreach($souscriptions_urgentes as $subscription)
                                        <div
                                            class="flex items-center justify-between p-4 bg-red-50 rounded-xl border border-red-200">
                                            <div class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-red-600"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-slate-900">
                                                        {{ $subscription['souscripteur']['nom'] ?? 'N/A' }}</div>
                                                    <div class="text-sm text-slate-600">
                                                        {{ $subscription['fimeco']['nom'] ?? 'N/A' }}</div>
                                                    <div class="text-xs text-red-600 mt-1">
                                                        @if($subscription['en_retard'] ?? false)
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                                            En retard de {{ $subscription['jours_retard'] ?? 0 }} jours
                                                        @else
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Échéance dans {{ $subscription['jours_restants'] ?? 0 }} jours
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-slate-900">
                                                    {{ number_format($subscription['reste_a_payer'] ?? 0, 0, ',', ' ') }} FCFA</div>
                                                <div class="text-sm text-slate-500">reste à payer</div>
                                                <a href="{{ route('private.subscriptions.show', $subscription['id']) }}"
                                                    class="inline-flex items-center px-2 py-1 bg-red-600 text-white text-xs rounded mt-2 hover:bg-red-700 transition-colors">
                                                    <i class="fas fa-eye mr-1"></i> Voir
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div
                                        class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-slate-900 mb-2">Tout va bien !</h3>
                                    <p class="text-slate-500">Aucune souscription ne nécessite d'attention particulière.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Évolution récente -->
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-chart-area text-green-600 mr-2"></i>
                                Évolution récente
                            </h2>
                            <p class="text-slate-500 text-sm mt-1">
                                Tendances des derniers mois
                            </p>
                        </div>

                        <div class="p-6 space-y-4">
                            @foreach($evolution_souscriptions as $evolution)
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span
                                            class="text-sm font-medium text-slate-700">{{ \Carbon\Carbon::parse($evolution['mois'])->locale('fr')->format('M Y') }}</span>
                                        <span class="text-sm text-slate-600">{{ $evolution['nb_souscriptions'] }}</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-green-500 to-emerald-500"
                                            style="width: {{ $evolution['nb_souscriptions'] > 0 ? min(($evolution['nb_souscriptions'] / max(array_column($evolution_souscriptions, 'nb_souscriptions'))) * 100, 100) : 0 }}%">
                                        </div>
                                    </div>
                                    <div class="text-xs text-slate-500 mt-1">
                                        {{ number_format($evolution['montant_total'], 0, ',', ' ') }} FCFA</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Métriques financières -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-coins text-purple-600 mr-2"></i>
                            Métriques financières
                        </h2>
                        <p class="text-slate-500 text-sm mt-1">
                            Vue d'ensemble des montants et performances financières
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div
                                class="text-center p-6 bg-gradient-to-br from-blue-50 to-cyan-50 rounded-xl border border-blue-100">
                                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-hand-holding-usd text-white"></i>
                                </div>
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ number_format($statistiques_globales['montant_total_souscrit'] ?? 0, 0, ',', ' ') }}
                                </div>
                                <div class="text-sm text-slate-600">Total souscrit (FCFA)</div>
                            </div>

                            <div
                                class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl border border-green-100">
                                <div
                                    class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                                <div class="text-2xl font-bold text-green-600">
                                    {{ number_format($statistiques_globales['montant_total_paye'] ?? 0, 0, ',', ' ') }}
                                </div>
                                <div class="text-sm text-slate-600">Total payé (FCFA)</div>
                            </div>

                            <div
                                class="text-center p-6 bg-gradient-to-br from-orange-50 to-yellow-50 rounded-xl border border-orange-100">
                                <div
                                    class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                                <div class="text-2xl font-bold text-orange-600">
                                    {{ number_format(($statistiques_globales['montant_total_souscrit'] ?? 0) - ($statistiques_globales['montant_total_paye'] ?? 0), 0, ',', ' ') }}
                                </div>
                                <div class="text-sm text-slate-600">Reste à collecter (FCFA)</div>
                            </div>

                            <div
                                class="text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                                <div
                                    class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-percentage text-white"></i>
                                </div>
                                <div class="text-2xl font-bold text-purple-600">
                                    {{ number_format($statistiques_globales['taux_completion'] ?? 0, 1) }}%</div>
                                <div class="text-sm text-slate-600">Taux de completion</div>
                            </div>
                        </div>

                        <!-- Barre de progression globale -->
                        <div class="mt-8">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-slate-700">Progression globale des paiements</span>
                                <span class="text-sm font-bold text-slate-900">
                                    {{ $statistiques_globales['montant_total_souscrit'] > 0 ? number_format((($statistiques_globales['montant_total_paye'] ?? 0) / $statistiques_globales['montant_total_souscrit']) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-4">
                                <div class="h-4 rounded-full bg-gradient-to-r from-blue-500 to-purple-500"
                                    style="width: {{ $statistiques_globales['montant_total_souscrit'] > 0 ? min((($statistiques_globales['montant_total_paye'] ?? 0) / $statistiques_globales['montant_total_souscrit']) * 100, 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                            Actions rapides
                        </h2>
                        <p class="text-slate-500 text-sm mt-1">
                            Accès direct aux fonctionnalités principales
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @can('subscriptions.create')
                                <a href="{{ route('private.subscriptions.create') }}"
                                    class="flex flex-col items-center p-4 bg-blue-50 rounded-xl border border-blue-200 hover:bg-blue-100 transition-colors group">
                                    <div
                                        class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-plus text-white"></i>
                                    </div>
                                    <span class="text-sm font-medium text-blue-800">Nouvelle souscription</span>
                                </a>
                            @endcan

                            @can('subscriptions.search')
                                <a href="{{ route('private.subscriptions.index') }}?en_retard=1"
                                    class="flex flex-col items-center p-4 bg-red-50 rounded-xl border border-red-200 hover:bg-red-100 transition-colors group">
                                    <div
                                        class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                    </div>
                                    <span class="text-sm font-medium text-red-800">Souscriptions en retard</span>
                                </a>
                            @endcan

                            @can('subscriptions.export')
                                <button onclick="exporterRapport()"
                                    class="flex flex-col items-center p-4 bg-green-50 rounded-xl border border-green-200 hover:bg-green-100 transition-colors group">
                                    <div
                                        class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-download text-white"></i>
                                    </div>
                                    <span class="text-sm font-medium text-green-800">Exporter les données</span>
                                </button>
                            @endcan

                            @can('payments.index')
                                <a href="{{ route('private.payments.index') }}?statut=en_attente"
                                    class="flex flex-col items-center p-4 bg-yellow-50 rounded-xl border border-yellow-200 hover:bg-yellow-100 transition-colors group">
                                    <div
                                        class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-clock text-white"></i>
                                    </div>
                                    <span class="text-sm font-medium text-yellow-800">Paiements en attente</span>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ number_format($statistiques_globales['souscriptions_completes'] ?? 0) }}</p>
                        <p class="text-sm text-slate-500">Complétées</p>
                        <p class="text-xs text-green-600">
                            {{ number_format($statistiques_globales['taux_completion'] ?? 0, 1) }}% de succès</p>
                    </div>
                </div>
            </div>


        </div>


    </div>


    @push('scripts')
        <script>
            function exporterRapport() {
                const format = prompt('Format du rapport (excel/pdf):', 'excel');
                if (format && ['excel', 'pdf', 'csv'].includes(format.toLowerCase())) {
                    window.location.href = `{{ route('private.subscriptions.export') }}?format=${format}`;
                }
            }

            // Actualisation automatique des statistiques
            function refreshStats() {
                fetch("{{ route('private.subscriptions.live-stats') }}", {
                    headers: {
                        'Accept': 'application/json',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mettre à jour les statistiques en temps réel
                            updateStatsDisplay(data.data);
                        }
                    })
                    .catch(error => {
                        console.log('Erreur lors de l\'actualisation des statistiques:', error);
                    });
            }

            function updateStatsDisplay(stats) {
                // Cette fonction pourrait mettre à jour les statistiques affichées
                // sans recharger la page complète
                console.log('Statistiques mises à jour:', stats);
            }

            // Animation au chargement
            document.addEventListener('DOMContentLoaded', function () {
                // Animation des cartes
                const cards = document.querySelectorAll('.bg-white\\/80');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    // card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        // card.style.transform = 'translateY(0)';
                    }, index * 100);
                });

                // Actualisation périodique (toutes les 5 minutes)
                setInterval(refreshStats, 300000);
            });
        </script>
    @endpush
@endsection



