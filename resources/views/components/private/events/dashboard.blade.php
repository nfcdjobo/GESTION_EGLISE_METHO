@extends('layouts.private.main')
@section('title', 'Tableau de Bord des Événements')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Tableau de Bord des Événements</h1>
            <p class="text-slate-500 mt-1">Vue d'ensemble des activités - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @can('events.create')
                    <a href="{{ route('private.events.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Nouvel Événement
                    </a>
                @endcan
                <a href="{{ route('private.events.planning') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-calendar-alt mr-2"></i> Planning
                </a>
                <a href="{{ route('private.events.statistiques') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-chart-line mr-2"></i> Statistiques
                </a>
                <a href="{{ route('private.events.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-list mr-2"></i> Tous les Événements
                </a>
            </div>
        </div>
    </div>

    <!-- Métriques d'aujourd'hui -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-calendar-day text-orange-600 mr-2"></i>
                Aujourd'hui - {{ \Carbon\Carbon::now()->format('l d F Y') }}
            </h2>
        </div>
        <div class="p-6">
            @if($dashboard['aujourd_hui']['nombre'] > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <div class="text-center p-6 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl">
                            <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-calendar-day text-white text-2xl"></i>
                            </div>
                            <div class="text-3xl font-bold text-orange-600">{{ $dashboard['aujourd_hui']['nombre'] }}</div>
                            <div class="text-orange-800 font-medium">Événement{{ $dashboard['aujourd_hui']['nombre'] > 1 ? 's' : '' }} aujourd'hui</div>
                        </div>
                    </div>
                    <div class="space-y-3">
                        @foreach($dashboard['aujourd_hui']['events'] as $event)
                            <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                <div class="flex-shrink-0">
                                    @if($event->image_principale)
                                        <img src="{{ $event->image_principale }}" alt="{{ $event->titre }}" class="w-12 h-12 object-cover rounded-lg">
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-calendar-alt text-white text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-slate-900 truncate">
                                        <a href="{{ route('private.events.show', $event) }}" class="hover:text-blue-600 transition-colors">{{ $event->titre }}</a>
                                    </h3>
                                    <div class="text-sm text-slate-500 flex items-center space-x-2">
                                        <span><i class="fas fa-clock mr-1"></i>{{ $event->heure_debut ? \Carbon\Carbon::parse($event->heure_debut)->format('H:i') : '--' }}</span>
                                        <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->lieu_nom }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        @if($event->statut == 'planifie') bg-blue-100 text-blue-800
                                        @elseif($event->statut == 'en_cours') bg-green-100 text-green-800
                                        @else bg-slate-100 text-slate-800
                                        @endif">
                                        {{ ucfirst($event->statut) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-day text-slate-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">Aucun événement aujourd'hui</h3>
                    <p class="text-slate-500">Pas d'événement planifié pour aujourd'hui</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Aperçu de la semaine et du mois -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Cette semaine -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar-week text-blue-600 mr-2"></i>
                    Cette Semaine
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded-xl">
                        <div class="text-2xl font-bold text-blue-600">{{ $dashboard['cette_semaine']['events_a_venir'] }}</div>
                        <div class="text-sm text-blue-800">À venir</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-xl">
                        <div class="text-2xl font-bold text-green-600">{{ $dashboard['cette_semaine']['events_termines'] }}</div>
                        <div class="text-sm text-green-800">Terminés</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ce mois -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar text-purple-600 mr-2"></i>
                    Ce Mois
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-purple-50 rounded-xl">
                        <div class="text-2xl font-bold text-purple-600">{{ $dashboard['ce_mois']['total_events'] }}</div>
                        <div class="text-sm text-purple-800">Événements</div>
                    </div>
                    <div class="text-center p-4 bg-indigo-50 rounded-xl">
                        <div class="text-2xl font-bold text-indigo-600">{{ $dashboard['ce_mois']['events_publics'] }}</div>
                        <div class="text-sm text-indigo-800">Publics</div>
                    </div>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-cyan-50 rounded-xl">
                        <div class="text-2xl font-bold text-cyan-600">{{ number_format($dashboard['ce_mois']['total_participants']) }}</div>
                        <div class="text-sm text-cyan-800">Participants</div>
                    </div>
                    <div class="text-center p-4 bg-emerald-50 rounded-xl">
                        <div class="text-2xl font-bold text-emerald-600">{{ number_format($dashboard['ce_mois']['total_recettes']) }}</div>
                        <div class="text-sm text-emerald-800">Recettes (FCFA)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prochains événements -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-arrow-right text-teal-600 mr-2"></i>
                    Prochains Événements
                </h2>
                <a href="{{ route('private.events.planning') }}" class="text-sm text-blue-600 hover:text-blue-800 transition-colors">
                    Voir le planning complet <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($dashboard['prochains_events']->count() > 0)
                <div class="space-y-4">
                    @foreach($dashboard['prochains_events'] as $event)
                        <div class="flex items-center space-x-4 p-4 border border-slate-200 rounded-xl hover:shadow-md transition-all duration-200">
                            <div class="flex-shrink-0">
                                @if($event->image_principale)
                                    <img src="{{ $event->image_principale }}" alt="{{ $event->titre }}" class="w-16 h-12 object-cover rounded-lg">
                                @else
                                    <div class="w-16 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-alt text-white text-sm"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-slate-900 hover:text-blue-600 transition-colors">
                                    <a href="{{ route('private.events.show', $event) }}">{{ $event->titre }}</a>
                                </h3>
                                @if($event->sous_titre)
                                    <p class="text-sm text-slate-600">{{ $event->sous_titre }}</p>
                                @endif
                                <div class="flex items-center space-x-4 mt-1 text-sm text-slate-500">
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $event->date_debut->format('d/m/Y') }}</span>
                                    <span><i class="fas fa-clock mr-1"></i>{{ $event->heure_debut ? \Carbon\Carbon::parse($event->heure_debut)->format('H:i') : '--' }}</span>
                                    <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $event->lieu_nom }}</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                @if($event->inscription_requise)
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-green-600">{{ $event->nombre_inscrits }}</div>
                                        <div class="text-xs text-slate-500">inscrits</div>
                                    </div>
                                @endif
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($event->statut == 'planifie') bg-blue-100 text-blue-800
                                    @elseif($event->statut == 'en_promotion') bg-yellow-100 text-yellow-800
                                    @elseif($event->statut == 'ouvert_inscription') bg-green-100 text-green-800
                                    @else bg-slate-100 text-slate-800
                                    @endif">
                                    @switch($event->statut)
                                        @case('planifie') Planifié @break
                                        @case('en_promotion') En promotion @break
                                        @case('ouvert_inscription') Inscriptions ouvertes @break
                                        @default Brouillon @break
                                    @endswitch
                                </span>
                                @if($event->organisateurPrincipal)
                                    <div class="text-center">
                                        <div class="text-xs text-slate-500">Organisateur</div>
                                        <div class="text-sm font-medium text-slate-700">{{ $event->organisateurPrincipal->prenom }} {{ $event->organisateurPrincipal->nom }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-plus text-slate-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">Aucun événement à venir</h3>
                    <p class="text-slate-500 mb-4">Aucun événement planifié dans les prochains jours</p>
                    @can('events.create')
                        <a href="{{ route('private.events.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Créer un événement
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-chart-bar text-indigo-600 mr-2"></i>
                Statistiques Rapides
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-6 bg-gradient-to-r from-amber-50 to-yellow-50 rounded-xl">
                    <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-yellow-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-star text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-amber-600">{{ $dashboard['statistiques_rapides']['note_moyenne_mois'] }}<span class="text-lg">/10</span></div>
                    <div class="text-sm text-amber-800">Note moyenne ce mois</div>
                </div>

                <div class="text-center p-6 bg-gradient-to-r from-rose-50 to-pink-50 rounded-xl">
                    <div class="w-12 h-12 bg-gradient-to-r from-rose-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-percentage text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-rose-600">{{ $dashboard['statistiques_rapides']['taux_inscription'] }}%</div>
                    <div class="text-sm text-rose-800">Taux d'inscription</div>
                </div>

                <div class="text-center p-6 bg-gradient-to-r from-violet-50 to-purple-50 rounded-xl">
                    <div class="w-12 h-12 bg-gradient-to-r from-violet-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-calendar-check text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-violet-600">{{ $dashboard['cette_semaine']['events_a_venir'] + $dashboard['cette_semaine']['events_termines'] }}</div>
                    <div class="text-sm text-violet-800">Total cette semaine</div>
                </div>

                <div class="text-center p-6 bg-gradient-to-r from-teal-50 to-cyan-50 rounded-xl">
                    <div class="w-12 h-12 bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-trending-up text-white"></i>
                    </div>
                    <div class="text-2xl font-bold text-teal-600">+{{ number_format(($dashboard['ce_mois']['total_events'] / max($dashboard['cette_semaine']['events_a_venir'] + $dashboard['cette_semaine']['events_termines'], 1) - 1) * 100, 0) }}%</div>
                    <div class="text-sm text-teal-800">Croissance mensuelle</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions et raccourcis -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                Actions Rapides
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @can('events.create')
                    <a href="{{ route('private.events.create') }}" class="flex items-center p-4 border border-slate-200 rounded-xl hover:shadow-md hover:border-blue-300 transition-all duration-200 group">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                            <i class="fas fa-plus text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <div class="font-medium text-slate-900">Créer un événement</div>
                            <div class="text-sm text-slate-500">Nouvel événement</div>
                        </div>
                    </a>
                @endcan

                <a href="{{ route('private.events.planning') }}" class="flex items-center p-4 border border-slate-200 rounded-xl hover:shadow-md hover:border-green-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-calendar-alt text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <div class="font-medium text-slate-900">Voir le planning</div>
                        <div class="text-sm text-slate-500">Calendrier complet</div>
                    </div>
                </a>

                <a href="{{ route('private.events.statistiques') }}" class="flex items-center p-4 border border-slate-200 rounded-xl hover:shadow-md hover:border-purple-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-chart-bar text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <div class="font-medium text-slate-900">Statistiques</div>
                        <div class="text-sm text-slate-500">Analyses détaillées</div>
                    </div>
                </a>

                <a href="{{ route('private.events.index', ['statut' => 'ouvert_inscription']) }}" class="flex items-center p-4 border border-slate-200 rounded-xl hover:shadow-md hover:border-cyan-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center group-hover:bg-cyan-200 transition-colors">
                        <i class="fas fa-user-plus text-cyan-600"></i>
                    </div>
                    <div class="ml-4">
                        <div class="font-medium text-slate-900">Inscriptions ouvertes</div>
                        <div class="text-sm text-slate-500">Gérer les inscriptions</div>
                    </div>
                </a>

                <a href="{{ route('private.events.index', ['a_venir' => 1]) }}" class="flex items-center p-4 border border-slate-200 rounded-xl hover:shadow-md hover:border-orange-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                        <i class="fas fa-clock text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <div class="font-medium text-slate-900">Événements à venir</div>
                        <div class="text-sm text-slate-500">Prochains événements</div>
                    </div>
                </a>

                <a href="{{ route('private.events.index', ['termines' => 1]) }}" class="flex items-center p-4 border border-slate-200 rounded-xl hover:shadow-md hover:border-emerald-300 transition-all duration-200 group">
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                    </div>
                    <div class="ml-4">
                        <div class="font-medium text-slate-900">Événements terminés</div>
                        <div class="text-sm text-slate-500">Historique et évaluations</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh du dashboard toutes les 5 minutes
setInterval(function() {
    // Optionnel: recharger automatiquement la page
    // window.location.reload();
}, 300000); // 5 minutes
</script>
@endsection
