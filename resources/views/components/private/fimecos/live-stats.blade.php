<!-- Fragment de statistiques en temps réel - peut être utilisé via AJAX -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4" id="live-stats-container">
    <!-- FIMECOs actifs -->
    <div class="bg-white/90 rounded-xl shadow-md p-4 border border-white/30 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-lg flex items-center justify-center shadow-md">
                    <i class="fas fa-check-circle text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-xl font-bold text-slate-800" data-stat="fimecos_actifs">{{ $stats['fimecos_actifs'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">FIMECOs actifs</p>
            </div>
        </div>
    </div>

    <!-- Objectifs atteints aujourd'hui -->
    <div class="bg-white/90 rounded-xl shadow-md p-4 border border-white/30 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center shadow-md">
                    <i class="fas fa-bullseye text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-xl font-bold text-slate-800" data-stat="objectifs_atteints_aujourd_hui">{{ $stats['objectifs_atteints_aujourd_hui'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">Objectifs atteints aujourd'hui</p>
            </div>
        </div>
    </div>

    <!-- Paiements aujourd'hui -->
    <div class="bg-white/90 rounded-xl shadow-md p-4 border border-white/30 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center shadow-md">
                    <i class="fas fa-coins text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-xl font-bold text-slate-800" data-stat="paiements_aujourd_hui">
                    {{ number_format($stats['paiements_aujourd_hui'] ?? 0, 0, ',', ' ') }}
                </p>
                <p class="text-xs text-slate-500">Paiements aujourd'hui (FCFA)</p>
            </div>
        </div>
    </div>

    <!-- Nouvelles souscriptions -->
    <div class="bg-white/90 rounded-xl shadow-md p-4 border border-white/30 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center shadow-md">
                    <i class="fas fa-user-plus text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-xl font-bold text-slate-800" data-stat="nouvelles_souscriptions_aujourd_hui">{{ $stats['nouvelles_souscriptions_aujourd_hui'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">Nouvelles souscriptions</p>
            </div>
        </div>
    </div>

    <!-- Paiements en attente -->
    <div class="bg-white/90 rounded-xl shadow-md p-4 border border-white/30 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-lg flex items-center justify-center shadow-md">
                    <i class="fas fa-hourglass-half text-white text-lg"></i>
                </div>
            </div>
            <div class="ml-3">
                <p class="text-xl font-bold text-slate-800" data-stat="paiements_en_attente">{{ $stats['paiements_en_attente'] ?? 0 }}</p>
                <p class="text-xs text-slate-500">Paiements en attente</p>
            </div>
        </div>
    </div>
</div>

<!-- Indicateur de dernière mise à jour -->
<div class="mt-4 text-center">
    <div class="inline-flex items-center px-3 py-1 bg-slate-100 rounded-full">
        <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
        <span class="text-xs text-slate-600" id="last-update">
            Dernière mise à jour : {{ now()->format('H:i:s') }}
        </span>
    </div>
</div>

@if(isset($standalone) && $standalone)
<!-- Si utilisé comme page standalone -->
@extends('layouts.private.main')
@section('title', 'Statistiques temps réel')

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('private.fimecos.dashboard') }}"
                        class="inline-flex items-center justify-center w-10 h-10 bg-white/80 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                        <i class="fas fa-arrow-left text-slate-600"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                            Statistiques en temps réel
                        </h1>
                        <p class="text-slate-500 mt-1">
                            Données actualisées automatiquement - {{ now()->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="refreshLiveStats()"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-sync mr-2"></i> Actualiser
                    </button>
                    <div class="flex items-center px-3 py-2 bg-green-100 text-green-800 rounded-xl">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                        <span class="text-sm font-medium">En direct</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques principales -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                    Vue d'ensemble
                </h2>
            </div>
            <div class="p-6">
                @include('components.private.fimecos.live-stats', ['stats' => $stats])
            </div>
        </div>

        <!-- Graphiques temps réel -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Activité du jour -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-activity text-green-600 mr-2"></i>
                        Activité du jour
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                            <div class="flex items-center">
                                <i class="fas fa-arrow-up text-green-600 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">Nouveaux paiements</p>
                                    <p class="text-xs text-slate-500">Validés aujourd'hui</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-blue-600">{{ $stats['paiements_valides_aujourd_hui'] ?? 0 }}</p>
                                <p class="text-xs text-slate-500">+{{ $stats['evolution_paiements'] ?? 0 }}%</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                            <div class="flex items-center">
                                <i class="fas fa-users text-blue-600 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">Nouvelles souscriptions</p>
                                    <p class="text-xs text-slate-500">Créées aujourd'hui</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-green-600">{{ $stats['nouvelles_souscriptions_aujourd_hui'] ?? 0 }}</p>
                                <p class="text-xs text-slate-500">+{{ $stats['evolution_souscriptions'] ?? 0 }}%</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200">
                            <div class="flex items-center">
                                <i class="fas fa-bullseye text-orange-600 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-slate-800">Objectifs atteints</p>
                                    <p class="text-xs text-slate-500">Depuis hier</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-orange-600">{{ $stats['objectifs_atteints_aujourd_hui'] ?? 0 }}</p>
                                <p class="text-xs text-slate-500">{{ $stats['taux_reussite_jour'] ?? 0 }}% taux</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertes en cours -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        Alertes en cours
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if(($stats['paiements_en_attente'] ?? 0) > 0)
                            <div class="flex items-center p-3 bg-red-50 rounded-lg border border-red-200">
                                <i class="fas fa-hourglass-half text-red-600 mr-3"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-red-800">Paiements en attente</p>
                                    <p class="text-xs text-red-600">{{ $stats['paiements_en_attente'] }} paiements nécessitent une validation</p>
                                </div>
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full">
                                    {{ $stats['paiements_en_attente'] }}
                                </span>
                            </div>
                        @endif

                        @if(($stats['fimecos_en_retard'] ?? 0) > 0)
                            <div class="flex items-center p-3 bg-orange-50 rounded-lg border border-orange-200">
                                <i class="fas fa-clock text-orange-600 mr-3"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-orange-800">FIMECOs en retard</p>
                                    <p class="text-xs text-orange-600">{{ $stats['fimecos_en_retard'] }} FIMECOs ont dépassé leur date limite</p>
                                </div>
                                <span class="px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">
                                    {{ $stats['fimecos_en_retard'] }}
                                </span>
                            </div>
                        @endif

                        @if(($stats['souscriptions_en_retard'] ?? 0) > 0)
                            <div class="flex items-center p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                <i class="fas fa-user-clock text-yellow-600 mr-3"></i>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-yellow-800">Souscriptions en retard</p>
                                    <p class="text-xs text-yellow-600">{{ $stats['souscriptions_en_retard'] }} souscriptions ont des échéances dépassées</p>
                                </div>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                    {{ $stats['souscriptions_en_retard'] }}
                                </span>
                            </div>
                        @endif

                        @if(($stats['paiements_en_attente'] ?? 0) == 0 && ($stats['fimecos_en_retard'] ?? 0) == 0 && ($stats['souscriptions_en_retard'] ?? 0) == 0)
                            <div class="text-center py-8">
                                <i class="fas fa-check-circle text-3xl text-green-300 mb-3"></i>
                                <p class="text-slate-500">Aucune alerte en cours</p>
                                <p class="text-xs text-slate-400 mt-1">Tout fonctionne normalement</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tendances récentes -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-trending-up text-purple-600 mr-2"></i>
                    Tendances des 7 derniers jours
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                        <div class="text-2xl font-bold text-blue-600 mb-1">
                            {{ number_format($stats['moyenne_paiements_semaine'] ?? 0, 0, ',', ' ') }}
                        </div>
                        <div class="text-sm text-blue-700">Paiements/jour</div>
                        <div class="text-xs text-blue-600 mt-1">
                            {{ ($stats['tendance_paiements'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($stats['tendance_paiements'] ?? 0, 1) }}%
                        </div>
                    </div>

                    <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                        <div class="text-2xl font-bold text-green-600 mb-1">
                            {{ number_format($stats['moyenne_montant_semaine'] ?? 0, 0, ',', ' ') }}
                        </div>
                        <div class="text-sm text-green-700">FCFA/jour</div>
                        <div class="text-xs text-green-600 mt-1">
                            {{ ($stats['tendance_montant'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($stats['tendance_montant'] ?? 0, 1) }}%
                        </div>
                    </div>

                    <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                        <div class="text-2xl font-bold text-purple-600 mb-1">
                            {{ number_format($stats['moyenne_souscriptions_semaine'] ?? 0, 1) }}
                        </div>
                        <div class="text-sm text-purple-700">Souscriptions/jour</div>
                        <div class="text-xs text-purple-600 mt-1">
                            {{ ($stats['tendance_souscriptions'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($stats['tendance_souscriptions'] ?? 0, 1) }}%
                        </div>
                    </div>

                    <div class="text-center p-4 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl border border-orange-200">
                        <div class="text-2xl font-bold text-orange-600 mb-1">
                            {{ number_format($stats['progression_moyenne_semaine'] ?? 0, 1) }}%
                        </div>
                        <div class="text-sm text-orange-700">Progression moy.</div>
                        <div class="text-xs text-orange-600 mt-1">
                            {{ ($stats['tendance_progression'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($stats['tendance_progression'] ?? 0, 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Fonction pour actualiser les statistiques
            function refreshLiveStats() {
                const button = document.querySelector('[onclick="refreshLiveStats()"]');
                const originalText = button.innerHTML;

                button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Actualisation...';
                button.disabled = true;

                fetch('{{ route("private.fimecos.liveStats") }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mettre à jour chaque statistique
                        Object.keys(data.data).forEach(key => {
                            const element = document.querySelector(`[data-stat="${key}"]`);
                            if (element) {
                                // Animation de mise à jour
                                element.style.transform = 'scale(1.1)';
                                element.style.transition = 'transform 0.2s ease';

                                setTimeout(() => {
                                    if (key.includes('montant') || key.includes('paiements_aujourd_hui')) {
                                        element.textContent = new Intl.NumberFormat('fr-FR').format(data.data[key]);
                                    } else {
                                        element.textContent = data.data[key];
                                    }
                                    element.style.transform = 'scale(1)';
                                }, 100);
                            }
                        });

                        // Mettre à jour l'heure
                        document.getElementById('last-update').textContent =
                            'Dernière mise à jour : ' + new Date().toLocaleTimeString('fr-FR');

                        // Message de succès
                        showNotification('Statistiques actualisées avec succès', 'success');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de l\'actualisation', 'error');
                })
                .finally(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
            }

            // Fonction pour afficher les notifications
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-xl shadow-lg transition-all duration-300 transform translate-x-full ${
                    type === 'success' ? 'bg-green-500 text-white' :
                    type === 'error' ? 'bg-red-500 text-white' :
                    'bg-blue-500 text-white'
                }`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                // Animation d'entrée
                setTimeout(() => {
                    notification.style.transform = 'translateX(0)';
                }, 100);

                // Suppression automatique
                setTimeout(() => {
                    notification.style.transform = 'translateX(full)';
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 3000);
            }

            // Auto-refresh toutes les 30 secondes
            setInterval(refreshLiveStats, 30000);

            // Animation au chargement
            document.addEventListener('DOMContentLoaded', function() {
                const stats = document.querySelectorAll('[data-stat]');
                stats.forEach((stat, index) => {
                    stat.style.opacity = '0';
                    stat.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        stat.style.transition = 'all 0.5s ease';
                        stat.style.opacity = '1';
                        stat.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
    @endpush
@endsection
@endif
