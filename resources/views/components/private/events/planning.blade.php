@extends('layouts.private.main')
@section('title', 'Planning des Événements')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Planning des Événements</h1>
            <p class="text-slate-500 mt-1">Vue d'ensemble du planning - {{ ucfirst($periode['vue']) }} du {{ \Carbon\Carbon::parse($periode['debut'])->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($periode['fin'])->format('d/m/Y') }}</p>
        </div>
    </div>

    <!-- Navigation et filtres -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar-week text-blue-600 mr-2"></i>
                    Navigation
                </h2>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('private.events.planning', ['vue' => 'semaine']) }}" class="inline-flex items-center px-4 py-2 {{ request('vue') === 'semaine' ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} text-sm font-medium rounded-xl transition-all duration-200">
                        <i class="fas fa-calendar-week mr-2"></i> Semaine
                    </a>
                    <a href="{{ route('private.events.planning', ['vue' => 'mois']) }}" class="inline-flex items-center px-4 py-2 {{ request('vue', 'mois') === 'mois' ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} text-sm font-medium rounded-xl transition-all duration-200">
                        <i class="fas fa-calendar-alt mr-2"></i> Mois
                    </a>
                    <a href="{{ route('private.events.planning', ['vue' => 'annee']) }}" class="inline-flex items-center px-4 py-2 {{ request('vue') === 'annee' ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} text-sm font-medium rounded-xl transition-all duration-200">
                        <i class="fas fa-calendar mr-2"></i> Année
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.events.planning') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="hidden" name="vue" value="{{ $periode['vue'] }}">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date de début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut', $periode['debut']) }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date de fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin', $periode['fin']) }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Planning des événements -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar-check text-green-600 mr-2"></i>
                    Événements Planifiés ({{ $events->count() }})
                </h2>
                @can('events.create')
                    <a href="{{ route('private.events.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Nouvel Événement
                    </a>
                @endcan
            </div>
        </div>
        <div class="p-6">
            @if($events->count() > 0)
                <!-- Vue calendrier simplifiée -->
                <div class="space-y-6">
                    @php
                        $eventsByDate = $events->groupBy(function($event) {
                            return $event->date_debut->format('Y-m-d');
                        });
                    @endphp

                    @foreach($eventsByDate as $date => $dayEvents)
                        <div class="border border-slate-200 rounded-xl overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 px-6 py-3 border-b border-slate-200">
                                <h3 class="font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-calendar-day text-blue-600 mr-2"></i>
                                    {{ \Carbon\Carbon::parse($date)->format('l d F Y') }}
                                    <span class="ml-2 text-sm font-medium text-blue-600">({{ $dayEvents->count() }} événement{{ $dayEvents->count() > 1 ? 's' : '' }})</span>
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach($dayEvents->sortBy('heure_debut') as $event)
                                        <div class="flex items-start space-x-4 p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
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
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h4 class="font-semibold text-slate-900 hover:text-blue-600 transition-colors">
                                                            <a href="{{ route('private.events.show', $event) }}">{{ $event->titre }}</a>
                                                        </h4>
                                                        @if($event->sous_titre)
                                                            <p class="text-sm text-slate-600 mt-1">{{ $event->sous_titre }}</p>
                                                        @endif
                                                        <div class="flex items-center space-x-4 mt-2 text-sm text-slate-500">
                                                            <span class="flex items-center">
                                                                <i class="fas fa-clock mr-1"></i>
                                                                {{ $event->heure_debut ? \Carbon\Carbon::parse($event->heure_debut)->format('H:i') : '--' }}
                                                                @if($event->heure_fin)
                                                                    - {{ \Carbon\Carbon::parse($event->heure_fin)->format('H:i') }}
                                                                @endif
                                                            </span>
                                                            <span class="flex items-center">
                                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                                {{ $event->lieu_nom }}
                                                            </span>
                                                            @if($event->organisateurPrincipal)
                                                                <span class="flex items-center">
                                                                    <i class="fas fa-user-tie mr-1"></i>
                                                                    {{ $event->organisateurPrincipal->prenom }} {{ $event->organisateurPrincipal->nom }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="flex items-center space-x-2 mt-2">
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                                @if($event->statut == 'planifie') bg-blue-100 text-blue-800
                                                                @elseif($event->statut == 'en_promotion') bg-yellow-100 text-yellow-800
                                                                @elseif($event->statut == 'ouvert_inscription') bg-green-100 text-green-800
                                                                @elseif($event->statut == 'en_cours') bg-purple-100 text-purple-800
                                                                @else bg-slate-100 text-slate-800
                                                                @endif">
                                                                @switch($event->statut)
                                                                    @case('planifie') Planifié @break
                                                                    @case('en_promotion') En promotion @break
                                                                    @case('ouvert_inscription') Inscriptions ouvertes @break
                                                                    @case('en_cours') En cours @break
                                                                    @default Brouillon @break
                                                                @endswitch
                                                            </span>
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                                {{ ucfirst(str_replace('_', ' ', $event->type_evenement)) }}
                                                            </span>
                                                            @if($event->inscription_requise)
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-cyan-100 text-cyan-800">
                                                                    <i class="fas fa-users mr-1"></i>
                                                                    {{ $event->nombre_inscrits }} inscrits
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2 ml-4">
                                                        <a href="{{ route('private.events.show', $event) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Voir">
                                                            <i class="fas fa-eye text-sm"></i>
                                                        </a>
                                                        @can('events.update')
                                                            @if($event->peutEtreModifie())
                                                                <a href="{{ route('private.events.edit', $event) }}" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                                    <i class="fas fa-edit text-sm"></i>
                                                                </a>
                                                            @endif
                                                        @endcan
                                                        @if($event->inscription_requise)
                                                            <a href="{{ route('private.events.inscriptions', $event) }}" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Inscriptions">
                                                                <i class="fas fa-users text-sm"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun événement planifié</h3>
                    <p class="text-slate-500 mb-6">
                        Aucun événement n'est planifié pour cette période.
                    </p>
                    @can('events.create')
                        <a href="{{ route('private.events.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Créer un événement
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    <!-- Résumé rapide -->
    @if($events->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-calendar-check text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $events->count() }}</p>
                        <p class="text-sm text-slate-500">Événements</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-user-plus text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $events->where('inscription_requise', true)->count() }}</p>
                        <p class="text-sm text-slate-500">Avec inscriptions</p>
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
                        <p class="text-2xl font-bold text-slate-800">{{ $events->sum('nombre_inscrits') }}</p>
                        <p class="text-sm text-slate-500">Total inscrits</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-globe text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $events->where('ouvert_public', true)->count() }}</p>
                        <p class="text-sm text-slate-500">Publics</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
