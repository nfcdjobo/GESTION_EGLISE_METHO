@extends('layouts.private.main')
@section('title', 'Types de Réunions')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Types de Réunions</h1>
        <p class="text-slate-500 mt-1">Gestion des types de réunions configurables - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
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
                    @can('types-reunions.create')
                        <a href="{{ route('private.types-reunions.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouveau Type
                        </a>
                    @endcan
                    <a href="{{ route('private.types-reunions.statistiques') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                    <a href="{{ route('private.types-reunions.categories') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-tags mr-2"></i> Catégories
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.types-reunions.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, code, description..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                    <select name="categorie" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $key => $label)
                            <option value="{{ $key }}" {{ request('categorie') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Niveau d'accès</label>
                    <select name="niveau_acces" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les niveaux</option>
                        @foreach($niveauxAcces as $key => $label)
                            <option value="{{ $key }}" {{ request('niveau_acces') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="actif" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs</option>
                        <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="{{ route('private.types-reunions.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
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
                        <i class="fas fa-calendar-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $types->total() }}</p>
                    <p class="text-sm text-slate-500">Types totaux</p>
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
                    <p class="text-2xl font-bold text-slate-800">{{ $types->where('actif', true)->count() }}</p>
                    <p class="text-sm text-slate-500">Actifs</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-archive text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $types->where('est_archive', true)->count() }}</p>
                    <p class="text-sm text-slate-500">Archivés</p>
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
                    <p class="text-2xl font-bold text-slate-800">{{ $types->sum('nombre_utilisations') }}</p>
                    <p class="text-sm text-slate-500">Utilisations</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des types de réunions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Types de Réunions ({{ $types->total() }})
                </h2>
                <div class="flex items-center space-x-4">
                    <!-- Sélecteur de vue -->
                    <div class="flex items-center bg-slate-100 rounded-xl p-1">
                        <button id="listView" class="view-toggle flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-white text-slate-700 shadow-sm">
                            <i class="fas fa-list mr-2"></i>Liste
                        </button>
                        <button id="gridView" class="view-toggle flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-slate-500 hover:text-slate-700">
                            <i class="fas fa-th-large mr-2"></i>Grille
                        </button>
                    </div>

                    <select id="perPage" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15 par page</option>
                        <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30 par page</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 par page</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($types->count() > 0)
                <!-- Affichage en liste (par défaut) -->
                <div id="listViewContainer" class="space-y-4">
                    @foreach($types as $type)
                        <div class="bg-gradient-to-r from-white to-slate-50 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                            <!-- Barre de couleur -->
                            <div class="h-1 bg-gradient-to-r" style="background: linear-gradient(90deg, {{ $type->couleur ?? '#3498db' }}, {{ adjustBrightness($type->couleur ?? '#3498db', -20) }})"></div>

                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <!-- Informations principales -->
                                    <div class="flex items-center space-x-4 flex-1">
                                        <!-- Icône -->
                                        @if($type->icone)
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-sm flex-shrink-0" style="background-color: {{ $type->couleur ?? '#3498db' }}">
                                                <i class="fas fa-{{ $type->icone }} text-lg"></i>
                                            </div>
                                        @else
                                            <div class="w-12 h-12 bg-gradient-to-br from-slate-400 to-slate-500 rounded-xl flex items-center justify-center text-white shadow-sm flex-shrink-0">
                                                <i class="fas fa-calendar text-lg"></i>
                                            </div>
                                        @endif

                                        <!-- Détails -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h3 class="font-semibold text-slate-800 text-lg truncate">{{ $type->nom }}</h3>
                                                <span class="text-sm text-slate-500 bg-slate-100 px-2 py-1 rounded-lg">{{ $type->code }}</span>

                                                <!-- Statuts -->
                                                @if($type->actif && !$type->est_archive)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check-circle mr-1"></i>Actif
                                                    </span>
                                                @elseif($type->est_archive)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        <i class="fas fa-archive mr-1"></i>Archivé
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-times-circle mr-1"></i>Inactif
                                                    </span>
                                                @endif

                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $categories[$type->categorie] ?? ucfirst($type->categorie) }}
                                                </span>
                                            </div>

                                            @if($type->description)
                                                <p class="text-sm text-slate-600 mb-3 line-clamp-1">{{ Str::limit($type->description, 120) }}</p>
                                            @endif

                                            <!-- Informations détaillées -->
                                            <div class="flex items-center space-x-6 text-sm text-slate-600">
                                                <div class="flex items-center">
                                                    <i class="fas fa-shield-alt mr-1 text-slate-400"></i>
                                                    <span>{{ $niveauxAcces[$type->niveau_acces] ?? ucfirst($type->niveau_acces) }}</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-repeat mr-1 text-slate-400"></i>
                                                    <span>{{ ucfirst(str_replace('_', ' ', $type->frequence_type)) }}</span>
                                                </div>
                                                @if($type->duree_standard)
                                                    <div class="flex items-center">
                                                        <i class="fas fa-clock mr-1 text-slate-400"></i>
                                                        <span>{{ $type->duree_standard->format('H:i') }}</span>
                                                    </div>
                                                @endif
                                                <div class="flex items-center">
                                                    <i class="fas fa-chart-line mr-1 text-slate-400"></i>
                                                    <span>{{ $type->nombre_utilisations }} utilisations</span>
                                                </div>
                                            </div>

                                            <!-- Badges des caractéristiques -->
                                            @if($type->necessite_inscription || $type->inclut_louange || $type->inclut_message || $type->permet_enfants)
                                                <div class="flex flex-wrap gap-2 mt-3">
                                                    @if($type->necessite_inscription)
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                                            <i class="fas fa-user-check mr-1"></i>Inscription
                                                        </span>
                                                    @endif
                                                    @if($type->inclut_louange)
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                            <i class="fas fa-music mr-1"></i>Louange
                                                        </span>
                                                    @endif
                                                    @if($type->inclut_message)
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="fas fa-microphone mr-1"></i>Message
                                                        </span>
                                                    @endif
                                                    @if($type->permet_enfants)
                                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-child mr-1"></i>Enfants
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2 ml-4">
                                        @can('types-reunions.read')
                                            <a href="{{ route('private.types-reunions.show', $type) }}" class="inline-flex items-center justify-center w-9 h-9 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endcan

                                        @can('types-reunions.update')
                                            <a href="{{ route('private.types-reunions.edit', $type) }}" class="inline-flex items-center justify-center w-9 h-9 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('types-reunions.duplicate')
                                            <button type="button" onclick="duplicateType('{{ $type->id }}')" class="inline-flex items-center justify-center w-9 h-9 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Dupliquer">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        @endcan

                                        @if($type->actif && !$type->est_archive)
                                            @can('types-reunions.deactivate')
                                                <button type="button" onclick="toggleStatus('{{ $type->id }}', false)" class="inline-flex items-center justify-center w-9 h-9 text-orange-600 bg-orange-100 rounded-lg hover:bg-orange-200 transition-colors" title="Désactiver">
                                                    <i class="fas fa-pause"></i>
                                                </button>
                                            @endcan
                                        @else
                                            @can('types-reunions.activate')
                                                <button type="button" onclick="toggleStatus('{{ $type->id }}', true)" class="inline-flex items-center justify-center w-9 h-9 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Activer">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            @endcan
                                        @endif

                                        @if(!$type->est_archive)
                                            @can('types-reunions.archive')
                                                <button type="button" onclick="archiveType('{{ $type->id }}', 'archive')" class="inline-flex items-center justify-center w-9 h-9 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" title="Archiver">
                                                    <i class="fas fa-archive"></i>
                                                </button>
                                            @endcan
                                        @else
                                            @can('types-reunions.archive')
                                                <button type="button" onclick="restoreType('{{ $type->id }}')" class="inline-flex items-center justify-center w-9 h-9 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Restaurer">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Affichage en grille -->
                <div id="gridViewContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
                    @foreach($types as $type)
                        <div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                            <!-- En-tête avec couleur -->
                            <div class="h-2 bg-gradient-to-r" style="background: linear-gradient(90deg, {{ $type->couleur ?? '#3498db' }}, {{ adjustBrightness($type->couleur ?? '#3498db', -20) }})"></div>

                            <div class="p-6">
                                <!-- Titre et icône -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center space-x-3">
                                        @if($type->icone)
                                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white shadow-sm" style="background-color: {{ $type->couleur ?? '#3498db' }}">
                                                <i class="fas fa-{{ $type->icone }} text-lg"></i>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-gradient-to-br from-slate-400 to-slate-500 rounded-lg flex items-center justify-center text-white shadow-sm">
                                                <i class="fas fa-calendar text-lg"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-semibold text-slate-800 text-lg">{{ $type->nom }}</h3>
                                            <p class="text-sm text-slate-500">{{ $type->code }}</p>
                                        </div>
                                    </div>

                                    <!-- Statut -->
                                    <div class="flex flex-col items-end space-y-1">
                                        @if($type->actif && !$type->est_archive)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Actif
                                            </span>
                                        @elseif($type->est_archive)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-archive mr-1"></i>Archivé
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Inactif
                                            </span>
                                        @endif

                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $categories[$type->categorie] ?? ucfirst($type->categorie) }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Description -->
                                @if($type->description)
                                    <p class="text-sm text-slate-600 mb-4 line-clamp-2">{{ Str::limit($type->description, 100) }}</p>
                                @endif

                                <!-- Informations clés -->
                                <div class="grid grid-cols-2 gap-4 mb-4 text-xs">
                                    <div>
                                        <span class="text-slate-500">Niveau d'accès:</span>
                                        <div class="font-medium text-slate-700">{{ $niveauxAcces[$type->niveau_acces] ?? ucfirst($type->niveau_acces) }}</div>
                                    </div>
                                    <div>
                                        <span class="text-slate-500">Fréquence:</span>
                                        <div class="font-medium text-slate-700">{{ ucfirst(str_replace('_', ' ', $type->frequence_type)) }}</div>
                                    </div>
                                    @if($type->duree_standard)
                                        <div>
                                            <span class="text-slate-500">Durée:</span>
                                            <div class="font-medium text-slate-700">{{ $type->duree_standard->format('H:i') }}</div>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="text-slate-500">Utilisations:</span>
                                        <div class="font-medium text-slate-700">{{ $type->nombre_utilisations }}</div>
                                    </div>
                                </div>

                                <!-- Badges des caractéristiques -->
                                <div class="flex flex-wrap gap-1 mb-4">
                                    @if($type->necessite_inscription)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800">
                                            <i class="fas fa-user-check mr-1"></i>Inscription
                                        </span>
                                    @endif
                                    @if($type->inclut_louange)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-music mr-1"></i>Louange
                                        </span>
                                    @endif
                                    @if($type->inclut_message)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-microphone mr-1"></i>Message
                                        </span>
                                    @endif
                                    @if($type->permet_enfants)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-child mr-1"></i>Enfants
                                        </span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                    <div class="flex items-center space-x-2">
                                        @can('types-reunions.read')
                                            <a href="{{ route('private.types-reunions.show', $type) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                        @endcan

                                        @can('types-reunions.update')
                                            <a href="{{ route('private.types-reunions.edit', $type) }}" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                        @endcan

                                        @can('types-reunions.duplicate')
                                            <button type="button" onclick="duplicateType('{{ $type->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Dupliquer">
                                                <i class="fas fa-copy text-sm"></i>
                                            </button>
                                        @endcan
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        @if($type->actif && !$type->est_archive)
                                            @can('types-reunions.deactivate')
                                                <button type="button" onclick="toggleStatus('{{ $type->id }}', false)" class="inline-flex items-center justify-center w-8 h-8 text-orange-600 bg-orange-100 rounded-lg hover:bg-orange-200 transition-colors" title="Désactiver">
                                                    <i class="fas fa-pause text-sm"></i>
                                                </button>
                                            @endcan
                                        @else
                                            @can('types-reunions.activate')
                                                <button type="button" onclick="toggleStatus('{{ $type->id }}', true)" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Activer">
                                                    <i class="fas fa-play text-sm"></i>
                                                </button>
                                            @endcan
                                        @endif

                                        @if(!$type->est_archive)
                                            @can('types-reunions.archive')
                                                <button type="button" onclick="archiveType('{{ $type->id }}', 'archive')" class="inline-flex items-center justify-center w-8 h-8 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" title="Archiver">
                                                    <i class="fas fa-archive text-sm"></i>
                                                </button>
                                            @endcan
                                        @else
                                            @can('types-reunions.archive')
                                                <button type="button" onclick="restoreType('{{ $type->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Restaurer">
                                                    <i class="fas fa-undo text-sm"></i>
                                                </button>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium">{{ $types->firstItem() }}</span> à <span class="font-medium">{{ $types->lastItem() }}</span>
                        sur <span class="font-medium">{{ $types->total() }}</span> résultats
                    </div>
                    <div>
                        {{ $types->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-calendar-alt text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun type de réunion trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['search', 'categorie', 'niveau_acces']))
                            Aucun type de réunion ne correspond à vos critères de recherche.
                        @else
                            Commencez par créer votre premier type de réunion.
                        @endif
                    </p>
                    @can('types-reunions.create')
                        <a href="{{ route('private.types-reunions.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Créer un type de réunion
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Gestion des vues (Liste/Grille)
let currentView = 'list'; // Vue par défaut

// Éléments DOM
const listViewBtn = document.getElementById('listView');
const gridViewBtn = document.getElementById('gridView');
const listContainer = document.getElementById('listViewContainer');
const gridContainer = document.getElementById('gridViewContainer');

// Fonction pour basculer entre les vues
function toggleView(view) {
    if (view === currentView) return;

    currentView = view;

    // Mise à jour des boutons
    if (view === 'list') {
        listViewBtn.classList.add('bg-white', 'text-slate-700', 'shadow-sm');
        listViewBtn.classList.remove('text-slate-500', 'hover:text-slate-700');
        gridViewBtn.classList.remove('bg-white', 'text-slate-700', 'shadow-sm');
        gridViewBtn.classList.add('text-slate-500', 'hover:text-slate-700');

        // Affichage des conteneurs
        listContainer.classList.remove('hidden');
        gridContainer.classList.add('hidden');
    } else {
        gridViewBtn.classList.add('bg-white', 'text-slate-700', 'shadow-sm');
        gridViewBtn.classList.remove('text-slate-500', 'hover:text-slate-700');
        listViewBtn.classList.remove('bg-white', 'text-slate-700', 'shadow-sm');
        listViewBtn.classList.add('text-slate-500', 'hover:text-slate-700');

        // Affichage des conteneurs
        gridContainer.classList.remove('hidden');
        listContainer.classList.add('hidden');
    }

    // Sauvegarde de la préférence utilisateur
    localStorage.setItem('typesReunionsView', view);
}

// Écouteurs d'événements pour les boutons
listViewBtn.addEventListener('click', () => toggleView('list'));
gridViewBtn.addEventListener('click', () => toggleView('grid'));

// Restauration de la préférence utilisateur au chargement (mais par défaut liste)
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('typesReunionsView') || 'list';
    toggleView(savedView);
});

// Gestion du nombre d'éléments par page
document.getElementById('perPage').addEventListener('change', function() {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', this.value);
    window.location.href = url.toString();
});

// Dupliquer un type
function duplicateType(typeId) {
    if (confirm('Dupliquer ce type de réunion ?')) {
        fetch(`{{ route('private.types-reunions.dupliquer', ':type') }}`.replace(':type', typeId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
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

// Basculer le statut
function toggleStatus(typeId, action) {
    const actionText = action ? 'activer' : 'désactiver';
    const confirmed = confirm(`Voulez-vous ${actionText} ce type de réunion ?`);
    if (confirmed) {
        const endpoint = action ? 'activer' : 'desactiver';
        fetch(`{{ route('private.types-reunions.activer', ':type') }}`.replace(':type', typeId).replace('activer', endpoint), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
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

// Archiver un type
function archiveType(typeId, action) {
    if (confirm('Archiver ce type de réunion ? Il ne sera plus affiché dans les listes actives.')) {
        fetch(`{{ route('private.types-reunions.archiver', ':type') }}`.replace(':type', typeId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
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

// Restaurer un type
function restoreType(typeId) {
    if (confirm('Restaurer ce type de réunion ?')) {
        fetch(`{{ route('private.types-reunions.restaurer', ':type') }}`.replace(':type', typeId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
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
</script>
@endpush

@endsection
