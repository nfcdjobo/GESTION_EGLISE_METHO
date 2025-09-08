{{-- components/private/annonces/dashboard-widget.blade.php --}}
@props([
    'compact' => false,
    'showActions' => true
])

@php
    // Récupérer les données nécessaires
    $statistiques = [
        'total' => \App\Models\Annonce::count(),
        'actives' => \App\Models\Annonce::actives()->count(),
        'brouillons' => \App\Models\Annonce::where('statut', 'brouillon')->count(),
        'urgentes' => \App\Models\Annonce::urgentes()->actives()->count(),
        'expirent_bientot' => \App\Models\Annonce::actives()
            ->whereNotNull('expire_le')
            ->where('expire_le', '<=', now()->addDays(3))
            ->count()
    ];

    $annoncesRecentes = \App\Models\Annonce::with(['auteur'])
        ->orderBy('created_at', 'desc')
        ->take($compact ? 3 : 5)
        ->get();

    $annoncesUrgentes = \App\Models\Annonce::urgentes()
        ->actives()
        ->with(['auteur'])
        ->take(3)
        ->get();
@endphp

<div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
    <!-- Header du widget -->
    <div class="p-6 border-b border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-bullhorn text-blue-600 mr-2"></i>
                    Annonces
                </h3>
                @if(!$compact)
                    <p class="text-slate-500 mt-1">Aperçu de vos communications</p>
                @endif
            </div>

            @if($showActions)
                <div class="flex items-center space-x-2">
                    @can('annonces.create')
                    <a href="{{ route('private.annonces.create') }}"
                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors"
                       title="Nouvelle annonce">
                        <i class="fas fa-plus mr-1"></i>
                        @if(!$compact) Nouveau @endif
                    </a>
                    @endcan
                    <a href="{{ route('private.annonces.index') }}"
                       class="text-slate-400 hover:text-slate-600 transition-colors"
                       title="Voir toutes les annonces">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Contenu du widget -->
    <div class="p-6">
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-2 {{ $compact ? 'lg:grid-cols-4' : 'lg:grid-cols-5' }} gap-4 mb-6">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $statistiques['total'] }}</div>
                <div class="text-xs text-slate-500 uppercase tracking-wide">Total</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $statistiques['actives'] }}</div>
                <div class="text-xs text-slate-500 uppercase tracking-wide">Actives</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $statistiques['brouillons'] }}</div>
                <div class="text-xs text-slate-500 uppercase tracking-wide">Brouillons</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">{{ $statistiques['urgentes'] }}</div>
                <div class="text-xs text-slate-500 uppercase tracking-wide">Urgentes</div>
            </div>
            @if(!$compact)
                <div class="text-center">
                    <div class="text-2xl font-bold text-orange-600">{{ $statistiques['expirent_bientot'] }}</div>
                    <div class="text-xs text-slate-500 uppercase tracking-wide">Expirent</div>
                </div>
            @endif
        </div>

        <!-- Alertes critiques -->
        @if($statistiques['urgentes'] > 0 || $statistiques['expirent_bientot'] > 0)
            <div class="mb-6">
                @if($statistiques['urgentes'] > 0)
                    <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg mb-2">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                        <span class="text-red-800 text-sm font-medium">
                            {{ $statistiques['urgentes'] }} annonce(s) urgente(s) active(s)
                        </span>
                        <a href="{{ route('private.annonces.index', ['niveau_priorite' => 'urgent']) }}"
                           class="ml-auto text-red-600 hover:text-red-800 text-sm">
                            Voir →
                        </a>
                    </div>
                @endif

                @if($statistiques['expirent_bientot'] > 0)
                    <div class="flex items-center p-3 bg-orange-50 border border-orange-200 rounded-lg">
                        <i class="fas fa-clock text-orange-500 mr-2"></i>
                        <span class="text-orange-800 text-sm font-medium">
                            {{ $statistiques['expirent_bientot'] }} annonce(s) expire(nt) bientôt
                        </span>
                        <a href="{{ route('private.annonces.index') }}?expiring_soon=1"
                           class="ml-auto text-orange-600 hover:text-orange-800 text-sm">
                            Voir →
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <!-- Annonces urgentes -->
        @if($annoncesUrgentes->count() > 0)
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-red-800 mb-3 flex items-center">
                    <i class="fas fa-bell text-red-600 mr-2"></i>
                    Annonces Urgentes
                </h4>
                <div class="space-y-3">
                    @foreach($annoncesUrgentes as $annonce)
                        <div class="flex items-start p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-red-900 truncate">{{ $annonce->titre }}</p>
                                <div class="flex items-center text-xs text-red-600 mt-1">
                                    @if($annonce->auteur)
                                        <span>{{ $annonce->auteur->prenom }} {{ $annonce->auteur->nom }}</span>
                                        <span class="mx-1">•</span>
                                    @endif
                                    <span>{{ $annonce->publie_le->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-1 ml-2">
                                <a href="{{ route('private.annonces.show', $annonce) }}"
                                   class="text-red-600 hover:text-red-800 transition-colors"
                                   title="Voir l'annonce">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                @can('annonces.update')
                                    <a href="{{ route('private.annonces.edit', $annonce) }}"
                                       class="text-red-600 hover:text-red-800 transition-colors"
                                       title="Modifier l'annonce">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Annonces récentes -->
        <div>
            <h4 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                <i class="fas fa-history text-slate-600 mr-2"></i>
                Activité Récente
            </h4>

            @if($annoncesRecentes->count() > 0)
                <div class="space-y-3">
                    @foreach($annoncesRecentes as $annonce)
                        <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                            <!-- Indicateur de statut -->
                            <div class="flex-shrink-0 mt-1">
                                @switch($annonce->statut)
                                    @case('publiee')
                                        <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                        @break
                                    @case('brouillon')
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                        @break
                                    @case('expiree')
                                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                        @break
                                    @default
                                        <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                                @endswitch
                            </div>

                            <!-- Contenu -->
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate">
                                    {{ $annonce->titre }}
                                </p>
                                <div class="flex items-center text-xs text-slate-500 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium {{ $annonce->badge_statut }} mr-2">
                                        {{ \App\Models\Annonce::getStatuts()[$annonce->statut] ?? $annonce->statut }}
                                    </span>
                                    @if($annonce->auteur)
                                        <span>{{ $annonce->auteur->prenom }} {{ $annonce->auteur->nom }}</span>
                                        <span class="mx-1">•</span>
                                    @endif
                                    <span>{{ $annonce->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            @can('annonces.read')
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('private.annonces.show', $annonce) }}"
                                   class="text-slate-400 hover:text-slate-600 transition-colors"
                                   title="Voir l'annonce">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                            </div>
                            @endcan
                        </div>
                    @endforeach
                </div>

                @if($annoncesRecentes->count() >= ($compact ? 3 : 5))
                    <div class="text-center mt-4">
                        <a href="{{ route('private.annonces.index') }}"
                           class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Voir toutes les annonces →
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <i class="fas fa-bullhorn text-3xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500 mb-4">Aucune annonce créée</p>
                    @if($showActions)
                        @can('annonces.create')
                        <a href="{{ route('private.annonces.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Créer votre première annonce
                        </a>
                        @endcan
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Footer avec actions rapides -->
    @if($showActions && !$compact)
        <div class="p-4 bg-slate-50 border-t border-slate-200 rounded-b-2xl">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 text-sm text-slate-600">
                    <a href="{{ route('private.annonces.annoncesActives') }}"
                       class="flex items-center hover:text-slate-800 transition-colors">
                        <i class="fas fa-eye mr-1"></i>
                        Vue publique
                    </a>
                    <a href="{{ route('private.annonces.index', ['statut' => 'brouillon']) }}"
                       class="flex items-center hover:text-slate-800 transition-colors">
                        <i class="fas fa-edit mr-1"></i>
                        Brouillons ({{ $statistiques['brouillons'] }})
                    </a>
                    <button onclick="refreshAnnonceWidget()"
                            class="flex items-center hover:text-slate-800 transition-colors">
                        <i class="fas fa-sync-alt mr-1"></i>
                        Actualiser
                    </button>
                </div>

                <div class="text-xs text-slate-400">
                    Mis à jour {{ now()->format('H:i') }}
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function refreshAnnonceWidget() {
    // Recharger le widget via AJAX (optionnel)
    fetch('{{ route("private.annonces.statistiques") }}')
        .then(response => response.json())
        .then(data => {
            // Mettre à jour les statistiques
            console.log('Statistiques mises à jour:', data);
            // Ici vous pouvez mettre à jour les éléments du DOM si nécessaire
        })
        .catch(error => {
            console.error('Erreur lors de la mise à jour:', error);
        });
}

// Auto-refresh du widget toutes les 5 minutes
setInterval(function() {
    if (!document.hidden) {
        refreshAnnonceWidget();
    }
}, 300000); // 5 minutes

// Notification en temps réel pour les nouvelles annonces urgentes
if (typeof Echo !== 'undefined') {
    Echo.channel('annonces-urgentes')
        .listen('NouvelleAnnonceUrgente', (e) => {
            // Ajouter un indicateur visuel
            const widget = document.querySelector('.fas.fa-bullhorn').closest('.bg-white\\/80');
            if (widget) {
                widget.style.boxShadow = '0 0 20px rgba(239, 68, 68, 0.3)';
                setTimeout(() => {
                    widget.style.boxShadow = '';
                }, 3000);
            }

            // Optionnel: rafraîchir le widget
            setTimeout(refreshAnnonceWidget, 1000);
        });
}
</script>
@endpush
