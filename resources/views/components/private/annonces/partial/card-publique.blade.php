
<div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 {{ isset($urgent) && $urgent ? 'border-red-300 bg-gradient-to-br from-red-50 to-red-100' : '' }}">
    <!-- Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h3 class="text-lg font-bold text-slate-900 mb-2 {{ isset($urgent) && $urgent ? 'text-red-900' : '' }}">{{ $annonce->titre }}</h3>
            <div class="flex flex-wrap items-center gap-2">
                <!-- Badge priorité -->
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $annonce->badge_priorite }}">
                    @switch($annonce->niveau_priorite)
                        @case('urgent')
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Urgent
                            @break
                        @case('important')
                            <i class="fas fa-star mr-1"></i>
                            Important
                            @break
                        @default
                            <i class="fas fa-info-circle mr-1"></i>
                            Normal
                    @endswitch
                </span>

                <!-- Badge type -->
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                    @switch($annonce->type_annonce)
                        @case('evenement')
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Événement
                            @break
                        @case('administrative')
                            <i class="fas fa-cog mr-1"></i>
                            Administrative
                            @break
                        @case('pastorale')
                            <i class="fas fa-cross mr-1"></i>
                            Pastorale
                            @break
                        @case('urgence')
                            <i class="fas fa-bell mr-1"></i>
                            Urgence
                            @break
                        @default
                            <i class="fas fa-info mr-1"></i>
                            Information
                    @endswitch
                </span>

                <!-- Badge audience -->
                @if($annonce->audience_cible !== 'tous')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        <i class="fas fa-users mr-1"></i>
                        {{ \App\Models\Annonce::getAudiencesCibles()[$annonce->audience_cible] ?? $annonce->audience_cible }}
                    </span>
                @endif
            </div>
        </div>

        <!-- Date de publication -->
        @if($annonce->publie_le)
            <div class="text-right text-xs text-slate-500">
                <i class="fas fa-clock mr-1"></i>
                {{ $annonce->publie_le->diffForHumans() }}
            </div>
        @endif
    </div>

    <!-- Informations de l'événement -->
    @if($annonce->type_annonce === 'evenement' && ($annonce->date_evenement || $annonce->lieu_evenement))
        <div class="bg-green-50 rounded-lg p-4 mb-4 border border-green-200">
            <div class="space-y-2">
                @if($annonce->date_evenement)
                    <div class="flex items-center text-sm">
                        <i class="fas fa-calendar-alt w-5 mr-3 text-green-600"></i>
                        <div>
                            <span class="font-semibold text-green-800">{{ $annonce->date_evenement->format('l d F Y') }}</span>
                            @if($annonce->date_evenement->isToday())
                                <span class="inline-flex items-center px-2 py-0.5 ml-2 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aujourd'hui
                                </span>
                            @elseif($annonce->date_evenement->isTomorrow())
                                <span class="inline-flex items-center px-2 py-0.5 ml-2 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Demain
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                @if($annonce->lieu_evenement)
                    <div class="flex items-center text-sm">
                        <i class="fas fa-map-marker-alt w-5 mr-3 text-red-600"></i>
                        <span class="text-slate-700">{{ $annonce->lieu_evenement }}</span>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Contact principal -->
    @if($annonce->contactPrincipal)
        <div class="flex items-center text-sm text-slate-600 mb-3">
            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold text-xs mr-3">
                {{ substr($annonce->contactPrincipal->prenom, 0, 1) }}{{ substr($annonce->contactPrincipal->nom, 0, 1) }}
            </div>
            <div class="flex-1">
                <span class="font-medium text-slate-700">{{ $annonce->contactPrincipal->nom }} {{ $annonce->contactPrincipal->prenom }}</span>
                @if($annonce->contactPrincipal->email)
                    <a href="mailto:{{ $annonce->contactPrincipal->email }}" class="ml-3 text-blue-600 hover:text-blue-800 transition-colors">
                        <i class="fas fa-envelope"></i>
                    </a>
                @endif
                @if($annonce->contactPrincipal->telephone)
                    <a href="tel:{{ $annonce->contactPrincipal->telephone }}" class="ml-2 text-blue-600 hover:text-blue-800 transition-colors">
                        <i class="fas fa-phone"></i>
                    </a>
                @endif
            </div>
        </div>
    @endif

    <!-- Alerte d'expiration -->
    @if($annonce->expire_le && $annonce->jours_restants !== null && $annonce->jours_restants <= 7)
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mb-4">
            <div class="flex items-center text-sm">
                <i class="fas fa-clock mr-2 text-orange-600"></i>
                <span class="font-medium text-orange-800">
                    @if($annonce->jours_restants > 0)
                        Expire dans {{ $annonce->jours_restants }} jour{{ $annonce->jours_restants > 1 ? 's' : '' }}
                    @elseif($annonce->jours_restants === 0)
                        Expire aujourd'hui
                    @else
                        Expirée
                    @endif
                </span>
            </div>
        </div>
    @endif

    <!-- Contenu de l'annonce -->
    <div class="text-sm text-slate-700 mb-4">
        <div class="prose prose-sm max-w-none">
            @if(strlen($annonce->contenu) > 200)
                <p class="line-clamp-4">{{ Str::limit(strip_tags($annonce->contenu), 200) }}</p>
                <button onclick="toggleContent(this)" class="text-blue-600 hover:text-blue-800 font-medium mt-2 text-xs">
                    <i class="fas fa-chevron-down mr-1"></i>
                    Lire la suite
                </button>
                <div class="hidden full-content">
                    <p class="mt-2">{!! nl2br(e($annonce->contenu)) !!}</p>
                    <button onclick="toggleContent(this)" class="text-blue-600 hover:text-blue-800 font-medium mt-2 text-xs">
                        <i class="fas fa-chevron-up mr-1"></i>
                        Réduire
                    </button>
                </div>
            @else
                <p>{!! nl2br(e($annonce->contenu)) !!}</p>
            @endif
        </div>
    </div>

    <!-- Footer avec options de diffusion -->
    <div class="flex items-center justify-between pt-4 border-t border-slate-200">
        <div class="flex flex-wrap gap-1">
            @if($annonce->afficher_site_web)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-globe mr-1"></i> Site web
                </span>
            @endif
            @if($annonce->annoncer_culte)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    <i class="fas fa-church mr-1"></i> Culte
                </span>
            @endif
            @if($annonce->audience_cible === 'tous')
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <i class="fas fa-users mr-1"></i> Tout public
                </span>
            @endif
        </div>

        <!-- Actions pour les gestionnaires -->
        @auth
            @if(auth()->user()->can('update', $annonce) || auth()->user()->can('view', $annonce))
                <div class="flex items-center space-x-1">
                    @can('view', $annonce)
                        <a href="{{ route('private.annonces.show', $annonce) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir détails">
                            <i class="fas fa-eye text-xs"></i>
                        </a>
                    @endcan
                    @can('update', $annonce)
                        <a href="{{ route('private.annonces.edit', $annonce) }}" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                            <i class="fas fa-edit text-xs"></i>
                        </a>
                    @endcan
                </div>
            @endif
        @endauth
    </div>

    <!-- Indicateur d'urgence pour les annonces urgentes -->
    @if($annonce->niveau_priorite === 'urgent')
        <div class="absolute top-2 right-2 w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
    @endif
</div>

@push('scripts')
<script>
function toggleContent(button) {
    const card = button.closest('.bg-gradient-to-br');
    const summary = card.querySelector('.line-clamp-4');
    const fullContent = card.querySelector('.full-content');

    if (fullContent.classList.contains('hidden')) {
        // Afficher le contenu complet
        summary.style.display = 'none';
        fullContent.classList.remove('hidden');
    } else {
        // Afficher le résumé
        summary.style.display = 'block';
        fullContent.classList.add('hidden');
    }
}
</script>
@endpush

@push('styles')
<style>
.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

@media print {
    .full-content {
        display: block !important;
    }
    .line-clamp-4 {
        display: none !important;
    }
    button {
        display: none !important;
    }
}
</style>
@endpush
