@extends('layouts.private.main')
@section('title', 'Gestion des Événements')

@section('content')

<div class="space-y-8">
    <!-- Page Title -->

    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Événements</h1>
            <p class="text-slate-500 mt-1">Administration des événements de l'église - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
        </div>
    </div>
    <div id="toast-container" class="fixed top-4 right-4 space-y-2 z-50"></div>

    <!-- Filtres et actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Actions
                </h2>
                <div class="flex flex-wrap gap-2">
                    @can('events.create')
                        <a href="{{ route('private.events.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouvel Événement
                        </a>
                    @endcan
                    <a href="{{ route('private.events.planning') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-calendar-alt mr-2"></i> Planning
                    </a>
                    <a href="{{ route('private.events.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-pie mr-2"></i> Tableau de bord
                    </a>
                    @can('events.statistics')
                    <a href="{{ route('private.events.statistiques') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-line mr-2"></i> Statistiques
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.events.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre, description, lieu..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                        <option value="planifie" {{ request('statut') == 'planifie' ? 'selected' : '' }}>Planifié</option>
                        <option value="en_promotion" {{ request('statut') == 'en_promotion' ? 'selected' : '' }}>En promotion</option>
                        <option value="ouvert_inscription" {{ request('statut') == 'ouvert_inscription' ? 'selected' : '' }}>Inscriptions ouvertes</option>
                        <option value="complet" {{ request('statut') == 'complet' ? 'selected' : '' }}>Complet</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                        <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                        <option value="reporte" {{ request('statut') == 'reporte' ? 'selected' : '' }}>Reporté</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                    <select name="type_evenement" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <option value="conference" {{ request('type_evenement') == 'conference' ? 'selected' : '' }}>Conférence</option>
                        <option value="seminaire" {{ request('type_evenement') == 'seminaire' ? 'selected' : '' }}>Séminaire</option>
                        <option value="atelier" {{ request('type_evenement') == 'atelier' ? 'selected' : '' }}>Atelier</option>
                        <option value="camps" {{ request('type_evenement') == 'camps' ? 'selected' : '' }}>Camps</option>
                        <option value="celebration" {{ request('type_evenement') == 'celebration' ? 'selected' : '' }}>Célébration</option>
                        <option value="concert" {{ request('type_evenement') == 'concert' ? 'selected' : '' }}>Concert</option>
                        <option value="retraite" {{ request('type_evenement') == 'retraite' ? 'selected' : '' }}>Retraite</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Organisateur</label>
                    <select name="organisateur_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les organisateurs</option>
                        @foreach($organisateurs as $organisateur)
                            <option value="{{ $organisateur->id }}" {{ request('organisateur_id') == $organisateur->id ? 'selected' : '' }}>
                                {{ $organisateur->prenom }} {{ $organisateur->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                        <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date fin</label>
                        <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Ville</label>
                        <input type="text" name="lieu_ville" value="{{ request('lieu_ville') }}" placeholder="Ex: Abidjan" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>
                <div class="lg:col-span-6 flex flex-wrap gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="{{ route('private.events.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                    <div class="flex items-center space-x-4 ml-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="a_venir" value="1" {{ request('a_venir') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-slate-700">À venir seulement</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="publics_seulement" value="1" {{ request('publics_seulement') ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-slate-700">Publics seulement</span>
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $events->total() }}</p>
                    <p class="text-sm text-slate-500">Total événements</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $events->where('statut', 'ouvert_inscription')->count() + $events->where('statut', 'planifie')->count() }}</p>
                    <p class="text-sm text-slate-500">À venir</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-plus text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $events->sum('nombre_inscrits') }}</p>
                    <p class="text-sm text-slate-500">Total inscriptions</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $events->where('statut', 'termine')->count() }}</p>
                    <p class="text-sm text-slate-500">Terminés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des événements -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Événements ({{ $events->total() }})
                </h2>
                <div class="flex items-center space-x-2">
                    <select id="bulkAction" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="">Actions en lot</option>
                        <option value="export">Exporter sélection</option>
                        @can('events.duplicate')
                        <option value="duplicate">Dupliquer sélection</option>
                        @endcan
                    </select>
                    <button type="button" onclick="executeBulkAction()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200">
                        <i class="fas fa-play mr-2"></i> Exécuter
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($events->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Événement</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Lieu</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Inscriptions</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Organisateur</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($events as $event)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <input type="checkbox" name="selected_events[]" value="{{ $event->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 event-checkbox">
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-start space-x-3">
                                            @if($event->image_principale)
                                                <img src="{{ $event->image_principale }}" alt="{{ $event->titre }}" class="w-16 h-12 object-cover rounded-lg">
                                            @else
                                                <div class="w-16 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-calendar-alt text-white text-sm"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <div class="font-semibold text-slate-900 text-sm">{{ $event->titre }}</div>
                                                @if($event->sous_titre)
                                                    <div class="text-xs text-slate-500">{{ $event->sous_titre }}</div>
                                                @endif
                                                <div class="flex items-center space-x-2 mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                        @if($event->type_evenement == 'conference') bg-blue-100 text-blue-800
                                                        @elseif($event->type_evenement == 'seminaire') bg-green-100 text-green-800
                                                        @elseif($event->type_evenement == 'celebration') bg-purple-100 text-purple-800
                                                        @elseif($event->type_evenement == 'concert') bg-pink-100 text-pink-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $event->type_evenement)) }}
                                                    </span>
                                                    @if($event->ouvert_public)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-globe mr-1"></i> Public
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm">
                                            <div class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($event->date_debut)->format('d/m/Y') }}</div>
                                            <div class="text-slate-500">{{ \Carbon\Carbon::parse($event->heure_debut)->format('H:i') }}</div>
                                            @if($event->date_fin && $event->date_fin != $event->date_debut)
                                                <div class="text-xs text-slate-400">au {{ \Carbon\Carbon::parse($event->date_fin)->format('d/m/Y') }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm">
                                            <div class="font-medium text-slate-900">{{ $event->lieu_nom }}</div>
                                            @if($event->lieu_ville)
                                                <div class="text-slate-500">{{ $event->lieu_ville }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($event->statut == 'planifie') bg-blue-100 text-blue-800
                                            @elseif($event->statut == 'en_promotion') bg-yellow-100 text-yellow-800
                                            @elseif($event->statut == 'ouvert_inscription') bg-green-100 text-green-800
                                            @elseif($event->statut == 'complet') bg-orange-100 text-orange-800
                                            @elseif($event->statut == 'en_cours') bg-purple-100 text-purple-800
                                            @elseif($event->statut == 'termine') bg-gray-100 text-gray-800
                                            @elseif($event->statut == 'annule') bg-red-100 text-red-800
                                            @elseif($event->statut == 'reporte') bg-yellow-100 text-yellow-800
                                            @else bg-slate-100 text-slate-800
                                            @endif">
                                            @switch($event->statut)
                                                @case('planifie') <i class="fas fa-calendar mr-1"></i> Planifié @break
                                                @case('en_promotion') <i class="fas fa-bullhorn mr-1"></i> En promotion @break
                                                @case('ouvert_inscription') <i class="fas fa-user-plus mr-1"></i> Inscriptions ouvertes @break
                                                @case('complet') <i class="fas fa-users mr-1"></i> Complet @break
                                                @case('en_cours') <i class="fas fa-play mr-1"></i> En cours @break
                                                @case('termine') <i class="fas fa-check mr-1"></i> Terminé @break
                                                @case('annule') <i class="fas fa-times mr-1"></i> Annulé @break
                                                @case('reporte') <i class="fas fa-calendar-alt mr-1"></i> Reporté @break
                                                @default <i class="fas fa-edit mr-1"></i> Brouillon @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($event->inscription_requise)
                                            <div class="text-sm">
                                                <div class="font-medium text-slate-900">{{ $event->nombre_inscrits }}</div>
                                                @if($event->capacite_totale)
                                                    <div class="text-slate-500">/ {{ $event->capacite_totale }}</div>
                                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $event->pourcentage_remplissage }}%"></div>
                                                    </div>
                                                @else
                                                    <div class="text-slate-500">inscrits</div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                Libre accès
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($event->organisateurPrincipal)
                                            <div class="text-sm text-slate-900">{{ $event->organisateurPrincipal->prenom }} {{ $event->organisateurPrincipal->nom }}</div>
                                        @else
                                            <span class="text-sm text-slate-500">Non assigné</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            @can('events.read')
                                                <a href="{{ route('private.events.show', $event) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                            @endcan

                                            @can('events.update')
                                                <button
                                                    onclick="publier('{{ $event->id }}')"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition-colors
                                                        {{ $event->ouvert_public
                                                                ? 'text-red-600 bg-red-100 hover:bg-red-200'
                                                                : 'text-green-600 bg-green-100 hover:bg-green-200' }}"
                                                    title="{{ $event->ouvert_public ? 'Dépublier' : 'Publier' }}">

                                                    @if($event->ouvert_public)
                                                        <i class="fas fa-eye-slash text-sm"></i> {{-- Dépublier --}}
                                                    @else
                                                        <i class="fas fa-eye text-sm"></i> {{-- Publier --}}
                                                    @endif
                                                </button>
                                            @endcan


                                            @can('events.manage-inscriptions')
                                            @if($event->inscription_requise)
                                                <a href="{{ route('private.events.inscriptions', $event) }}" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Inscriptions">
                                                    <i class="fas fa-users text-sm"></i>
                                                </a>
                                            @endif
                                            @endcan

                                            @can('events.update')
                                                @if($event->peutEtreModifie())
                                                    <a href="{{ route('private.events.edit', $event) }}" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                @endif

                                                <button type="button" onclick="showStatusModal('{{ $event->id }}', '{{ $event->statut }}')" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Changer statut">
                                                    <i class="fas fa-exchange-alt text-sm"></i>
                                                </button>
                                            @endcan

                                            @can('events.duplicate')
                                            <button type="button" onclick="duplicateEvent('{{ $event->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" title="Dupliquer">
                                                <i class="fas fa-copy text-sm"></i>
                                            </button>
                                            @endcan

                                            @can('events.delete')
                                                @if($event->statut !== 'en_cours')
                                                    <button type="button" onclick="deleteEvent('{{ $event->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium">{{ $events->firstItem() }}</span> à <span class="font-medium">{{ $events->lastItem() }}</span>
                        sur <span class="font-medium">{{ $events->total() }}</span> résultats
                    </div>
                    <div>
                        {{ $events->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-alt text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun événement trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['search', 'statut', 'type_evenement', 'organisateur_id']))
                            Aucun événement ne correspond à vos critères de recherche.
                        @else
                            Commencez par créer votre premier événement.
                        @endif
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
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3>
            </div>
            <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer cet événement ?</p>
            <p class="text-red-600 font-medium">Cette action est irréversible.</p>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Supprimer
            </button>
        </div>
    </div>
</div>

<!-- Modal changement de statut -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Changer le statut</h3>
            </div>
            <form id="statusForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau statut</label>
                    <select id="newStatus" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="brouillon">Brouillon</option>
                        <option value="planifie">Planifié</option>
                        <option value="en_promotion">En promotion</option>
                        <option value="ouvert_inscription">Inscriptions ouvertes</option>
                        <option value="complet">Complet</option>
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                        <option value="annule">Annulé</option>
                        <option value="reporte">Reporté</option>
                        <option value="archive">Archivé</option>
                    </select>
                </div>
                <div id="reasonField" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Raison de l'annulation/report</label>
                    <textarea id="statusReason" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" placeholder="Précisez la raison..."></textarea>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" id="confirmStatusChange" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Changer
            </button>
        </div>
    </div>
</div>

<script>
let currentEventId = null;

// Sélection multiple
const selectAll = document.getElementById('selectAll');
if(selectAll){
    selectAll.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.event-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
}


// Modal functions
function showDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}










function showToast(message, type = "success") {
        const container = document.getElementById("toast-container");

        // Couleurs selon le type
        const colors = {
            success: "bg-green-100 text-green-800 border border-green-300",
            error: "bg-red-100 text-red-800 border border-red-300",
            warning: "bg-yellow-100 text-yellow-800 border border-yellow-300",
        };

        // Créer le toast
        const toast = document.createElement("div");
        toast.className = `px-4 py-2 rounded-lg shadow-md ${colors[type]}`;
        toast.innerText = message;

        container.appendChild(toast);

        // Supprimer après 4s
        setTimeout(() => {
            toast.remove();
            location.reload();
        }, 4000);
    }

    function publier(eventId) {
        fetch("{{ route('private.events.publier', ':id') }}".replace(':id', eventId), {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Erreur serveur");
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast(data.message || "Évènement mis à jour avec succès ✅", "success");
            } else {
                showToast(data.message || "Une erreur est survenue ❌", "error");
            }
        })
        .catch(error => {
            console.error(error);
            showToast("Impossible de mettre à jour l’évènement ❌", "error");
        });
    }



function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function showStatusModal(eventId, currentStatus) {
    currentEventId = eventId;
    document.getElementById('newStatus').value = currentStatus;
    document.getElementById('statusModal').classList.remove('hidden');

    // Show/hide reason field based on status
    const statusSelect = document.getElementById('newStatus');
    const reasonField = document.getElementById('reasonField');

    if (['annule', 'reporte'].includes(statusSelect.value)) {
        reasonField.classList.remove('hidden');
    } else {
        reasonField.classList.add('hidden');
    }
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    currentEventId = null;
}

// Status change reason field toggle
document.getElementById('newStatus').addEventListener('change', function() {
    const reasonField = document.getElementById('reasonField');
    if (['annule', 'reporte'].includes(this.value)) {
        reasonField.classList.remove('hidden');
    } else {
        reasonField.classList.add('hidden');
    }
});

// Suppression d'un événement
function deleteEvent(eventId) {
    currentEventId = eventId;
    showDeleteModal();
    document.getElementById('confirmDelete').onclick = function() {
        fetch(`{{ route('private.events.destroy', ':event') }}`.replace(':event', eventId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeDeleteModal();
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    };
}

// Changement de statut
document.getElementById('confirmStatusChange').addEventListener('click', function() {
    if (!currentEventId) return;

    const newStatus = document.getElementById('newStatus').value;
    const reason = document.getElementById('statusReason').value;

    const data = { statut: newStatus };
    if (['annule', 'reporte'].includes(newStatus) && reason) {
        data.raison = reason;
    }

    fetch(`{{ route('private.events.index') }}/${currentEventId}/statut`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        closeStatusModal();
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
});

// Duplication d'événement
function duplicateEvent(eventId) {
    const nouvelleDate = prompt('Date du nouvel événement (YYYY-MM-DD):');
    if (!nouvelleDate) return;

    const nouvelleHeure = prompt('Heure du nouvel événement (HH:MM):');
    const nouveauTitre = prompt('Titre du nouvel événement (optionnel):');

    const data = {
        nouvelle_date: nouvelleDate
    };

    if (nouvelleHeure) data.nouvelle_heure = nouvelleHeure;
    if (nouveauTitre) data.nouveau_titre = nouveauTitre;

    fetch(`{{ route('private.events.dupliquer', ':event') }}`.replace(':event', eventId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = `{{ route('private.events.show', ':event') }}`.replace(':event', data.data.id);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Actions en lot
function executeBulkAction() {
    const action = document.getElementById('bulkAction').value;
    const selected = Array.from(document.querySelectorAll('.event-checkbox:checked')).map(cb => cb.value);

    if (!action || selected.length === 0) {
        alert('Veuillez sélectionner une action et au moins un événement');
        return;
    }

    switch (action) {
        case 'export':
            // Implémentation export
            alert('Fonctionnalité d\'export à implémenter');
            break;
        case 'duplicate':
            // Implémentation duplication multiple
            alert('Fonctionnalité de duplication multiple à implémenter');
            break;
    }
}

// Close modals when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatusModal();
});
</script>

@endsection
