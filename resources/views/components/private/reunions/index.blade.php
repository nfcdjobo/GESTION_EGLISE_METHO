@extends('layouts.private.main')
@section('title', 'Gestion des Réunions')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Réunions</h1>
        <p class="text-slate-500 mt-1">Organisation et suivi des réunions - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Actions
                </h2>
                <div class="flex flex-wrap gap-2">
                    @can('reunions.create')
                        <a href="{{ route('private.reunions.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouvelle Réunion
                        </a>
                    @endcan
                    <a href="{{ route('private.reunions.calendrier') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-calendar mr-2"></i> Calendrier
                    </a>
                    <a href="{{ route('private.reunions.statistiques') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.reunions.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre, lieu, description..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="planifiee" {{ request('statut') == 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                        <option value="confirmee" {{ request('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                        <option value="planifie" {{ request('statut') == 'planifie' ? 'selected' : '' }}>En préparation</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="terminee" {{ request('statut') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                        <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                        <option value="reportee" {{ request('statut') == 'reportee' ? 'selected' : '' }}>Reportée</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de réunion</label>
                    <select name="type_reunion_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        @foreach($typesReunions as $type)
                            <option value="{{ $type->id }}" {{ request('type_reunion_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Organisateur</label>
                    <select name="organisateur_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les organisateurs</option>
                        <!-- Populate avec les organisateurs -->
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                    <select name="niveau_priorite" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes priorités</option>
                        <option value="faible" {{ request('niveau_priorite') == 'faible' ? 'selected' : '' }}>Faible</option>
                        <option value="normale" {{ request('niveau_priorite') == 'normale' ? 'selected' : '' }}>Normale</option>
                        <option value="haute" {{ request('niveau_priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                        <option value="urgente" {{ request('niveau_priorite') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                        <option value="critique" {{ request('niveau_priorite') == 'critique' ? 'selected' : '' }}>Critique</option>
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
                    <div class="flex items-end">
                        <div class="w-full space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="diffusion_en_ligne" value="1" {{ request('diffusion_en_ligne') ? 'checked' : '' }} id="diffusion_en_ligne" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="diffusion_en_ligne" class="ml-2 text-sm text-slate-700">Avec diffusion en ligne</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="recurrentes" value="1" {{ request('recurrentes') ? 'checked' : '' }} id="recurrentes" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="recurrentes" class="ml-2 text-sm text-slate-700">Récurrentes seulement</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="{{ route('private.reunions.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
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
                    <p class="text-2xl font-bold text-slate-800">{{ $reunions->total() }}</p>
                    <p class="text-sm text-slate-500">Total réunions</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $reunions->where('statut', 'confirmee')->count() }}</p>
                    <p class="text-sm text-slate-500">Confirmées</p>
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
                    <p class="text-2xl font-bold text-slate-800">{{ $reunions->sum('nombre_participants_reel') ?: '0' }}</p>
                    <p class="text-sm text-slate-500">Total participants</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($reunions->whereNotNull('note_globale')->avg('note_globale') ?: 0, 1) }}/10</p>
                    <p class="text-sm text-slate-500">Note moyenne</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des réunions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Réunions ({{ $reunions->total() }})
                </h2>
                <div class="flex items-center space-x-2">
                    <select id="sortBy" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="date_reunion" {{ request('sort_by') == 'date_reunion' ? 'selected' : '' }}>Date</option>
                        <option value="titre" {{ request('sort_by') == 'titre' ? 'selected' : '' }}>Titre</option>
                        <option value="statut" {{ request('sort_by') == 'statut' ? 'selected' : '' }}>Statut</option>
                        <option value="niveau_priorite" {{ request('sort_by') == 'niveau_priorite' ? 'selected' : '' }}>Priorité</option>
                        <option value="nombre_participants_reel" {{ request('sort_by') == 'nombre_participants_reel' ? 'selected' : '' }}>Participants</option>
                    </select>
                    <select id="sortOrder" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($reunions->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($reunions as $reunion)
                        <div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $reunion->titre }}</h3>
                                    <p class="text-sm text-slate-600">{{ $reunion->typeReunion->nom ?? 'Non défini' }}</p>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    @php
                                        $statutColors = [
                                            'planifiee' => 'bg-blue-100 text-blue-800',
                                            'confirmee' => 'bg-green-100 text-green-800',
                                            'planifie' => 'bg-yellow-100 text-yellow-800',
                                            'en_cours' => 'bg-orange-100 text-orange-800',
                                            'terminee' => 'bg-emerald-100 text-emerald-800',
                                            'annulee' => 'bg-red-100 text-red-800',
                                            'reportee' => 'bg-purple-100 text-purple-800',
                                            'suspendue' => 'bg-gray-100 text-gray-800'
                                        ];

                                        $prioriteColors = [
                                            'faible' => 'bg-gray-100 text-gray-800',
                                            'normale' => 'bg-blue-100 text-blue-800',
                                            'haute' => 'bg-yellow-100 text-yellow-800',
                                            'urgente' => 'bg-orange-100 text-orange-800',
                                            'critique' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statutColors[$reunion->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($reunion->statut) }}
                                    </span>
                                    @if($reunion->niveau_priorite !== 'normale')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioriteColors[$reunion->niveau_priorite] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($reunion->niveau_priorite) }}
                                        </span>
                                    @endif
                                    @if($reunion->diffusion_en_ligne)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                            <i class="fas fa-video mr-1"></i> Live
                                        </span>
                                    @endif
                                    @if($reunion->est_recurrente)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <i class="fas fa-repeat mr-1"></i> Récurrente
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Détails -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                    <span>{{ \Carbon\Carbon::parse($reunion->date_reunion)->format('d/m/Y') }}</span>
                                    <i class="fas fa-clock w-4 ml-4 mr-2"></i>
                                    <span>{{ \Carbon\Carbon::parse($reunion->heure_debut_prevue)->format('H:i') }}</span>
                                </div>

                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                    <span>{{ $reunion->lieu }}</span>
                                </div>

                                @if($reunion->organisateurPrincipal)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-user-tie w-4 mr-2"></i>
                                        <span>{{ $reunion->organisateurPrincipal->nom }} {{ $reunion->organisateurPrincipal->prenom }}</span>
                                    </div>
                                @endif

                                @if($reunion->nombre_inscrits > 0)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-users w-4 mr-2"></i>
                                        <span>{{ $reunion->nombre_inscrits }} inscrit(s)</span>
                                        @if($reunion->nombre_participants_reel)
                                            <span class="ml-2 text-green-600">({{ $reunion->nombre_participants_reel }} présent(s))</span>
                                        @endif
                                    </div>
                                @endif

                                @if($reunion->note_globale)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-star w-4 mr-2 text-yellow-500"></i>
                                        <span>{{ $reunion->note_globale }}/10</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                <div class="flex items-center space-x-2">
                                    @can('reunions.read')
                                        <a href="{{ route('private.reunions.show', $reunion) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    @endcan

                                    @can('reunions.update')
                                        <a href="{{ route('private.reunions.edit', $reunion) }}" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    @endcan

                                    @if($reunion->peutCommencer())
                                        <button type="button" onclick="changerStatut('{{ $reunion->id }}', 'commencer')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Commencer">
                                            <i class="fas fa-play text-sm"></i>
                                        </button>
                                    @endif

                                    @if($reunion->peutEtreTerminee())
                                        <button type="button" onclick="changerStatut('{{ $reunion->id }}', 'terminer')" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Terminer">
                                            <i class="fas fa-stop text-sm"></i>
                                        </button>
                                    @endif

                                    @can('reunions.create')
                                        <button type="button" onclick="openDuplicateModal('{{ $reunion->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors" title="Dupliquer">
                                            <i class="fas fa-copy text-sm"></i>
                                        </button>
                                    @endcan
                                </div>

                                <div class="flex items-center space-x-2">
                                    @if($reunion->peutEtreAnnulee())
                                        <button type="button" onclick="openAnnulerModal('{{ $reunion->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Annuler">
                                            <i class="fas fa-times text-sm"></i>
                                        </button>
                                    @endif

                                    @can('reunions.delete')
                                        @if(in_array($reunion->statut, ['planifiee', 'confirmee']))
                                            <button type="button" onclick="supprimerReunion('{{ $reunion->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium">{{ $reunions->firstItem() }}</span> à <span class="font-medium">{{ $reunions->lastItem() }}</span>
                        sur <span class="font-medium">{{ $reunions->total() }}</span> résultats
                    </div>
                    <div>
                        {{ $reunions->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-times text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune réunion trouvée</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['search', 'statut', 'type_reunion_id', 'organisateur_id']))
                            Aucune réunion ne correspond à vos critères de recherche.
                        @else
                            Commencez par planifier votre première réunion.
                        @endif
                    </p>
                    @can('reunions.create')
                        <a href="{{ route('private.reunions.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Planifier une réunion
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Duplication -->
<div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Dupliquer la réunion</h3>
            <form id="duplicateForm">
                @csrf
                <input type="hidden" id="duplicate_reunion_id" name="reunion_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle date</label>
                        <input type="date" name="nouvelle_date" id="nouvelle_date" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="copier_participants" id="copier_participants" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="copier_participants" class="ml-2 text-sm text-slate-700">Copier les participants</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDuplicateModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="dupliquerReunion()" class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                Dupliquer
            </button>
        </div>
    </div>
</div>

<!-- Modal Annulation -->
<div id="annulerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Annuler la réunion</h3>
            <form id="annulerForm">
                @csrf
                <input type="hidden" id="annuler_reunion_id" name="reunion_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Motif d'annulation</label>
                        <textarea name="motif_annulation" id="motif_annulation" rows="3" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Raison de l'annulation..."></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Message aux participants</label>
                        <textarea name="message_participants" id="message_participants" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Message à envoyer aux participants..."></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeAnnulerModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="annulerReunion()" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Confirmer l'annulation
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Gestion du tri
document.getElementById('sortBy').addEventListener('change', function() {
    updateSort();
});

document.getElementById('sortOrder').addEventListener('change', function() {
    updateSort();
});

function updateSort() {
    const sortBy = document.getElementById('sortBy').value;
    const sortOrder = document.getElementById('sortOrder').value;
    const url = new URL(window.location.href);
    url.searchParams.set('sort_by', sortBy);
    url.searchParams.set('sort_order', sortOrder);
    window.location.href = url.toString();
}

// Modal duplication
function openDuplicateModal(reunionId) {
    document.getElementById('duplicate_reunion_id').value = reunionId;
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('nouvelle_date').value = tomorrow.toISOString().split('T')[0];
    document.getElementById('duplicateModal').classList.remove('hidden');
}

function closeDuplicateModal() {
    document.getElementById('duplicateModal').classList.add('hidden');
    document.getElementById('duplicateForm').reset();
}

function dupliquerReunion() {
    const form = document.getElementById('duplicateForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('duplicate_reunion_id').value;

    fetch(`{{route('private.reunions.dupliquer', ':reunion')}}`.replace(':reunion', reunionId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.message?.includes('succès')) {
            window.location.href = `/private/reunions/${data.data?.id || reunionId}`;
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Modal annulation
function openAnnulerModal(reunionId) {
    document.getElementById('annuler_reunion_id').value = reunionId;
    document.getElementById('annulerModal').classList.remove('hidden');
}

function closeAnnulerModal() {
    document.getElementById('annulerModal').classList.add('hidden');
    document.getElementById('annulerForm').reset();
}

function annulerReunion() {
    const form = document.getElementById('annulerForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('annuler_reunion_id').value;

    fetch(`{{route('private.reunions.annuler', ':reunion')}}`.replace(':reunion', reunionId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.message?.includes('succès')) {
            location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Changement de statut
function changerStatut(reunionId, action) {
    const actions = {
        'commencer': '/commencer',
        'terminer': '/terminer'
    };

    if (!actions[action]) return;
    const route = `{{route('private.types-reunions.activer', ':reunion')}}`.replace(':reunion', reunionId);
    fetch(route.replace('activer', actions), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success || data.message?.includes('succès')) {
            location.reload();
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Suppression
function supprimerReunion(reunionId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette réunion ?')) {
        fetch(`{{route('private.reunions.destroy', ':reunion')}}`.replace(':reunion', reunionId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || data.message?.includes('succès')) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Fermer les modals en cliquant à l'extérieur
document.getElementById('duplicateModal').addEventListener('click', function(e) {
    if (e.target === this) closeDuplicateModal();
});

document.getElementById('annulerModal').addEventListener('click', function(e) {
    if (e.target === this) closeAnnulerModal();
});
</script>
@endpush
@endsection
