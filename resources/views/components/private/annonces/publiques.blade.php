@extends('layouts.private.main')
@section('title', 'Annonces Publiques')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Annonces Publiques</h1>
        <p class="text-slate-500 mt-1">Toutes les annonces actuellement visibles par le public - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.annonces.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Annonces
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Publiques</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Filtres rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres
                </h2>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('private.annonces.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-cog mr-2"></i> Gestion
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type d'annonce</label>
                    <select name="type_annonce" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <option value="evenement" {{ request('type_annonce') == 'evenement' ? 'selected' : '' }}>Événement</option>
                        <option value="administrative" {{ request('type_annonce') == 'administrative' ? 'selected' : '' }}>Administrative</option>
                        <option value="pastorale" {{ request('type_annonce') == 'pastorale' ? 'selected' : '' }}>Pastorale</option>
                        <option value="urgence" {{ request('type_annonce') == 'urgence' ? 'selected' : '' }}>Urgence</option>
                        <option value="information" {{ request('type_annonce') == 'information' ? 'selected' : '' }}>Information</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Audience cible</label>
                    <select name="audience_cible" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes audiences</option>
                        <option value="tous" {{ request('audience_cible') == 'tous' ? 'selected' : '' }}>Tous</option>
                        <option value="membres" {{ request('audience_cible') == 'membres' ? 'selected' : '' }}</option>
                        <option value="leadership" {{ request('audience_cible') == 'leadership' ? 'selected' : '' }}>Leadership</option>
                        <option value="jeunes" {{ request('audience_cible') == 'jeunes' ? 'selected' : '' }}>Jeunes</option>
                    </select>
                </div>
                <div class="md:col-span-2 flex gap-2 items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Filtrer
                    </button>
                    <a href="{{ route('private.annonces.annoncesActives') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Tout afficher
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-eye text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $annonces->count() }}</p>
                    <p class="text-sm text-slate-500">Annonces actives</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $annonces->where('niveau_priorite', 'urgent')->count() }}</p>
                    <p class="text-sm text-slate-500">Urgentes</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $annonces->where('type_annonce', 'evenement')->count() }}</p>
                    <p class="text-sm text-slate-500">Événements</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-globe text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $annonces->where('afficher_site_web', true)->count() }}</p>
                    <p class="text-sm text-slate-500">Site web</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Annonces par priorité -->
    @if($annonces->count() > 0)
        <!-- Annonces urgentes -->
        @php $annoncesUrgentes = $annonces->where('niveau_priorite', 'urgent'); @endphp
        @if($annoncesUrgentes->count() > 0)
            <div class="bg-red-50/80 rounded-2xl shadow-lg border border-red-200/50 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-red-200">
                    <h2 class="text-xl font-bold text-red-800 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        Annonces Urgentes ({{ $annoncesUrgentes->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        @foreach($annoncesUrgentes as $annonce)
                            @include('components.private.annonces.partials.card-publique', ['annonce' => $annonce, 'urgent' => true])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Annonces importantes -->
        @php $annoncesImportantes = $annonces->where('niveau_priorite', 'important'); @endphp
        @if($annoncesImportantes->count() > 0)
            <div class="bg-yellow-50/80 rounded-2xl shadow-lg border border-yellow-200/50 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-yellow-200">
                    <h2 class="text-xl font-bold text-yellow-800 flex items-center">
                        <i class="fas fa-star text-yellow-600 mr-2"></i>
                        Annonces Importantes ({{ $annoncesImportantes->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($annoncesImportantes as $annonce)
                            @include('components.private.annonces.partials.card-publique', ['annonce' => $annonce])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Autres annonces -->
        @php $autresAnnonces = $annonces->where('niveau_priorite', 'normal'); @endphp
        @if($autresAnnonces->count() > 0)
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-list text-slate-600 mr-2"></i>
                        Autres Annonces ({{ $autresAnnonces->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($autresAnnonces as $annonce)
                            @include('components.private.annonces.partials.card-publique', ['annonce' => $annonce])
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-bullhorn text-3xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune annonce active</h3>
            <p class="text-slate-500 mb-6">
                @if(request()->hasAny(['type_annonce', 'audience_cible']))
                    Aucune annonce ne correspond aux critères sélectionnés.
                @else
                    Il n'y a actuellement aucune annonce publiée et active.
                @endif
            </p>
        </div>
    @endif
</div>

@push('styles')
<style>
@media print {
    .no-print { display: none !important; }
    body { background: white !important; }
    .bg-gradient-to-r, .bg-white\/80 { background: white !important; }
    .shadow-lg, .shadow-xl { box-shadow: none !important; }
}
</style>
@endpush

@push('scripts')
<script>
// Fonction d'impression
function printAnnonces() {
    window.print();
}

// Auto-refresh toutes les 5 minutes pour les annonces urgentes
@if($annonces->where('niveau_priorite', 'urgent')->count() > 0)
setInterval(function() {
    // Recharger uniquement si la page est visible
    if (!document.hidden) {
        location.reload();
    }
}, 300000); // 5 minutes
@endif

// Notification pour les nouvelles annonces urgentes (si WebSockets disponibles)
if (typeof Echo !== 'undefined') {
    Echo.channel('annonces-urgentes')
        .listen('NouvelleAnnonceUrgente', (e) => {
            // Afficher une notification
            if (Notification.permission === 'granted') {
                new Notification('Nouvelle annonce urgente', {
                    body: e.annonce.titre,
                    icon: '/favicon.ico'
                });
            }

            // Recharger la page après un délai
            setTimeout(() => {
                location.reload();
            }, 2000);
        });
}
</script>
@endpush
@endsection

{{-- Partial pour les cartes d'annonces publiques --}}
{{-- Cette partial devrait être créée séparément --}}
@php
// Inline partial pour les cartes - idéalement à placer dans un fichier séparé
if (!View::exists('components.private.annonces.partials.card-publique')) {
    $cardPublique = '
<div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 ' . (isset($urgent) && $urgent ? 'border-red-300 bg-red-50' : '') . '">
    <!-- Header -->
    <div class="flex items-start justify-between mb-4">
        <div class="flex-1">
            <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $annonce->titre }}</h3>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $annonce->badge_priorite }}">
                    @switch($annonce->niveau_priorite)
                        @case("urgent")
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Urgent
                            @break
                        @case("important")
                            <i class="fas fa-star mr-1"></i>
                            Important
                            @break
                        @default
                            <i class="fas fa-info-circle mr-1"></i>
                            Normal
                    @endswitch
                </span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                    @switch($annonce->type_annonce)
                        @case("evenement")
                            <i class="fas fa-calendar-alt mr-1"></i>
                            Événement
                            @break
                        @case("administrative")
                            <i class="fas fa-cog mr-1"></i>
                            Administrative
                            @break
                        @case("pastorale")
                            <i class="fas fa-cross mr-1"></i>
                            Pastorale
                            @break
                        @case("urgence")
                            <i class="fas fa-bell mr-1"></i>
                            Urgence
                            @break
                        @default
                            <i class="fas fa-info mr-1"></i>
                            Information
                    @endswitch
                </span>
            </div>
        </div>
    </div>

    <!-- Contenu -->
    <div class="space-y-3 mb-4">
        @if($annonce->date_evenement)
            <div class="flex items-center text-sm text-slate-600">
                <i class="fas fa-calendar-alt w-4 mr-2 text-green-600"></i>
                <span class="font-medium">{{ $annonce->date_evenement->format("l d F Y") }}</span>
            </div>
        @endif

        @if($annonce->lieu_evenement)
            <div class="flex items-center text-sm text-slate-600">
                <i class="fas fa-map-marker-alt w-4 mr-2 text-red-600"></i>
                <span>{{ $annonce->lieu_evenement }}</span>
            </div>
        @endif

        @if($annonce->contactPrincipal)
            <div class="flex items-center text-sm text-slate-600">
                <i class="fas fa-user w-4 mr-2 text-blue-600"></i>
                <span>{{ $annonce->contactPrincipal->nom }} {{ $annonce->contactPrincipal->prenom }}</span>
                @if($annonce->contactPrincipal->email)
                    <a href="mailto:{{ $annonce->contactPrincipal->email }}" class="ml-2 text-blue-600 hover:text-blue-800">
                        <i class="fas fa-envelope text-xs"></i>
                    </a>
                @endif
            </div>
        @endif

        @if($annonce->expire_le && $annonce->jours_restants !== null && $annonce->jours_restants <= 7)
            <div class="flex items-center text-sm">
                <i class="fas fa-clock w-4 mr-2 text-orange-600"></i>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $annonce->jours_restants <= 3 ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                    @if($annonce->jours_restants > 0)
                        Expire dans {{ $annonce->jours_restants }} jour{{ $annonce->jours_restants > 1 ? 's' : '' }}
                    @else
                        Expire aujourd\'hui
                    @endif
                </span>
            </div>
        @endif

        <!-- Aperçu du contenu -->
        <div class="text-sm text-slate-700 mt-3 pt-3 border-t border-slate-100">
            <p class="line-clamp-3">{{ Str::limit(strip_tags($annonce->contenu), 150) }}</p>
        </div>
    </div>

    <!-- Footer avec diffusion -->
    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
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
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                {{ \App\Models\Annonce::getAudiencesCibles()[$annonce->audience_cible] ?? $annonce->audience_cible }}
            </span>
        </div>
        <div class="text-xs text-slate-500">
            @if($annonce->publie_le)
                {{ $annonce->publie_le->diffForHumans() }}
            @endif
        </div>
    </div>
</div>';
}
@endphp
