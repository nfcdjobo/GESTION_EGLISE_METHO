{{-- components/private/annonces/dashboard.blade.php --}}
@props([
    'statistiques' => [],
    'annoncesRecentes' => collect(),
    'annoncesUrgentes' => collect(),
    'annoncesExpirantBientot' => collect()
])

<div class="space-y-8">
    <!-- Titre du dashboard -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Dashboard Annonces</h2>
            <p class="text-slate-500 mt-1">Vue d'ensemble de la communication</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="text-sm text-slate-500">
                <i class="fas fa-sync-alt mr-1"></i>
                Mis à jour {{ now()->format('H:i') }}
            </div>
            <button onclick="refreshDashboard()" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-refresh mr-2"></i>
                Actualiser
            </button>
        </div>
    </div>

    <!-- Métriques principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total des annonces -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-bullhorn text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['total'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Total annonces</p>
                </div>
            </div>
            @if(isset($statistiques['total_mois_precedent']))
                <div class="mt-4 flex items-center text-sm">
                    @php
                        $variation = ($statistiques['total'] - $statistiques['total_mois_precedent']);
                        $isPositive = $variation >= 0;
                    @endphp
                    <i class="fas fa-arrow-{{ $isPositive ? 'up' : 'down' }} mr-1 text-{{ $isPositive ? 'green' : 'red' }}-500"></i>
                    <span class="text-{{ $isPositive ? 'green' : 'red' }}-600">
                        {{ abs($variation) }} vs mois dernier
                    </span>
                </div>
            @endif
        </div>

        <!-- Annonces actives -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['actives'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Annonces actives</p>
                </div>
            </div>
            <div class="mt-4 text-sm text-slate-600">
                <div class="flex justify-between">
                    <span>Taux de publication:</span>
                    <span class="font-medium">
                        @if($statistiques['total'] > 0)
                            {{ round(($statistiques['actives'] / $statistiques['total']) * 100) }}%
                        @else
                            0%
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Brouillons -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-edit text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['brouillons'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Brouillons</p>
                </div>
            </div>
            @if(($statistiques['brouillons'] ?? 0) > 0)
                <div class="mt-4">
                    <a href="{{ route('private.annonces.index', ['statut' => 'brouillon']) }}" class="text-sm text-blue-600 hover:text-blue-800">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        Voir les brouillons
                    </a>
                </div>
            @endif
        </div>

        <!-- Urgentes -->
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['urgentes'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Urgentes</p>
                </div>
            </div>
            @if(($statistiques['urgentes'] ?? 0) > 0)
                <div class="mt-4">
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                        <i class="fas fa-bell mr-1"></i>
                        Attention requise
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Alertes -->
    @if($annoncesExpirantBientot->count() > 0 || $annoncesUrgentes->count() > 0)
        <div class="space-y-4">
            <!-- Annonces expirant bientôt -->
            @if($annoncesExpirantBientot->count() > 0)
                <div class="bg-orange-50 border border-orange-200 rounded-2xl p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-orange-500 text-2xl"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-orange-800 mb-2">
                                Annonces expirant bientôt ({{ $annoncesExpirantBientot->count() }})
                            </h3>
                            <div class="space-y-2">
                                @foreach($annoncesExpirantBientot->take(3) as $annonce)
                                    <div class="flex items-center justify-between bg-white rounded-lg p-3">
                                        <div class="flex-1">
                                            <p class="font-medium text-slate-800">{{ $annonce->titre }}</p>
                                            <p class="text-sm text-slate-600">
                                                <i class="fas fa-clock mr-1"></i>
                                                @if($annonce->jours_restants > 0)
                                                    Expire dans {{ $annonce->jours_restants }} jour{{ $annonce->jours_restants > 1 ? 's' : '' }}
                                                @else
                                                    Expire aujourd'hui
                                                @endif
                                            </p>
                                        </div>

                                        @can('annonces.edit')
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('private.annonces.edit', $annonce) }}" class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-700 rounded-md hover:bg-orange-200 transition-colors text-xs">
                                                <i class="fas fa-edit mr-1"></i>
                                                Modifier
                                            </a>
                                        </div>
                                        @endcan
                                    </div>
                                @endforeach
                                @if($annoncesExpirantBientot->count() > 3)
                                    <p class="text-sm text-orange-700">
                                        Et {{ $annoncesExpirantBientot->count() - 3 }} autre(s)...
                                        <a href="{{ route('private.annonces.index') }}?expire_soon=1" class="underline">Voir toutes</a>
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Annonces urgentes non traitées -->
            @if($annoncesUrgentes->count() > 0)
                <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-semibold text-red-800 mb-2">
                                Annonces urgentes actives ({{ $annoncesUrgentes->count() }})
                            </h3>
                            <div class="space-y-2">
                                @foreach($annoncesUrgentes->take(3) as $annonce)
                                    <div class="flex items-center justify-between bg-white rounded-lg p-3">
                                        <div class="flex-1">
                                            <p class="font-medium text-slate-800">{{ $annonce->titre }}</p>
                                            <p class="text-sm text-slate-600">
                                                <i class="fas fa-clock mr-1"></i>
                                                Publiée {{ $annonce->publie_le->diffForHumans() }}
                                            </p>
                                        </div>
                                        @can('annonces.read')
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('private.annonces.show', $annonce) }}" class="inline-flex items-center px-2 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition-colors text-xs">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir
                                            </a>
                                        </div>
                                        @endcan
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Graphiques et analyse -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Répartition par type -->
        @if(isset($statistiques['par_type']))
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                        Répartition par Type
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $total = array_sum($statistiques['par_type']->toArray());
                            $colors = [
                                'evenement' => 'bg-blue-500',
                                'administrative' => 'bg-yellow-500',
                                'pastorale' => 'bg-green-500',
                                'urgence' => 'bg-red-500',
                                'information' => 'bg-purple-500'
                            ];
                        @endphp
                        @foreach($statistiques['par_type'] as $type => $count)
                            @if($count > 0)
                                <div class="flex items-center">
                                    <div class="w-4 h-4 rounded-full {{ $colors[$type] ?? 'bg-gray-500' }} mr-3"></div>
                                    <div class="flex-1 flex items-center justify-between">
                                        <span class="text-sm font-medium text-slate-700">
                                            {{ \App\Models\Annonce::getTypesAnnonces()[$type] ?? ucfirst($type) }}
                                        </span>
                                        <div class="flex items-center">
                                            <span class="text-sm text-slate-600 mr-2">{{ $count }}</span>
                                            <div class="w-20 h-2 bg-slate-200 rounded-full overflow-hidden">
                                                <div class="h-full {{ $colors[$type] ?? 'bg-gray-500' }}" style="width: {{ $total > 0 ? ($count / $total) * 100 : 0 }}%"></div>
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

        <!-- Activité récente -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-green-600 mr-2"></i>
                        Activité Récente
                    </h3>
                    <a href="{{ route('private.annonces.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Voir toutes
                    </a>
                </div>
            </div>
            <div class="p-6">
                @if($annoncesRecentes->count() > 0)
                    <div class="space-y-4">
                        @foreach($annoncesRecentes->take(5) as $annonce)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 {{ $annonce->statut === 'publiee' ? 'bg-green-500' : 'bg-yellow-500' }} rounded-full mt-2"></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-800 truncate">
                                        {{ $annonce->titre }}
                                    </p>
                                    <div class="flex items-center space-x-2 text-xs text-slate-500 mt-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium {{ $annonce->badge_statut }}">
                                            {{ \App\Models\Annonce::getStatuts()[$annonce->statut] ?? $annonce->statut }}
                                        </span>
                                        <span>•</span>
                                        <span>{{ $annonce->created_at->diffForHumans() }}</span>
                                        @if($annonce->auteur)
                                            <span>•</span>
                                            <span>{{ $annonce->auteur->prenom }} {{ $annonce->auteur->nom }}</span>
                                        @endif
                                    </div>
                                </div>
                                @can('annonces.read')
                                <div class="flex-shrink-0">
                                    <a href="{{ route('private.annonces.show', $annonce) }}" class="text-slate-400 hover:text-slate-600">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                                @endcan
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6">
                        <i class="fas fa-inbox text-3xl text-slate-300 mb-3"></i>
                        <p class="text-slate-500">Aucune activité récente</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-bolt text-blue-600 mr-2"></i>
                Actions Rapides
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @can('annonces.create')
                <a href="{{ route('private.annonces.create') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-plus text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-800">Nouvelle annonce</p>
                        <p class="text-sm text-slate-600">Créer rapidement</p>
                    </div>
                </a>
                @endcan

                @can('annonces.read')
                <a href="{{ route('private.annonces.index', ['statut' => 'brouillon']) }}" class="flex items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-xl transition-colors">
                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-edit text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-800">Mes brouillons</p>
                        <p class="text-sm text-slate-600">{{ $statistiques['brouillons'] ?? 0 }} en attente</p>
                    </div>
                </a>
                @endcan

                @can('annonces.read')
                <a href="{{ route('private.annonces.annoncesActives') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-xl transition-colors">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-eye text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-800">Vue publique</p>
                        <p class="text-sm text-slate-600">Annonces actives</p>
                    </div>
                </a>
                @endcan

                @can('annonces.read')
                <button onclick="exportAnnonces()" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-xl transition-colors">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-download text-white"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-800">Exporter</p>
                        <p class="text-sm text-slate-600">Données CSV</p>
                    </div>
                </button>
                @endcan
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function refreshDashboard() {
    window.location.reload();
}

@can('annonces.read')
function exportAnnonces() {
    window.open('{{ route("private.annonces.index") }}?export=csv', '_blank');
}
@endcan

// Auto-refresh du dashboard toutes les 10 minutes
@can('annonces.statistics')
setInterval(function() {
    if (!document.hidden) {
        // Mettre à jour uniquement les statistiques via AJAX
        fetch('{{ route("private.annonces.statistiques") }}')
            .then(response => response.json())
            .then(data => {
                // Mettre à jour les compteurs sans recharger la page
                updateCounters(data);
            })
            .catch(error => {
                console.error('Erreur lors de la mise à jour:', error);
            });
    }
}, 600000); // 10 minutes
@endcan

function updateCounters(data) {
    // Mettre à jour les valeurs des compteurs
    document.querySelector('[data-stat="total"]').textContent = data.total || 0;
    document.querySelector('[data-stat="actives"]').textContent = data.actives || 0;
    document.querySelector('[data-stat="brouillons"]').textContent = data.brouillons || 0;
    document.querySelector('[data-stat="urgentes"]').textContent = data.urgentes || 0;
}
</script>
@endpush
