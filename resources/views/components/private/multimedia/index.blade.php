@extends('layouts.private.main')
@section('title', 'Galerie Multimédia')

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Galerie Multimédia</h1>
                <p class="text-slate-500 mt-1">Gestion de la médiathèque de la communauté -
                    {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
            </div>
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
                        @can('multimedia.create')
                        <a href="{{ route('private.multimedia.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-upload mr-2"></i> Télécharger un Média
                        </a>
                        @endcan
                        @can('multimedia.moderate')
                            <a href="{{ route('private.multimedia.moderation.queue') }}"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white text-sm font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-gavel mr-2"></i> File de Modération
                                @if ($multimedia->where('statut_moderation', 'en_attente')->count() > 0)
                                    <span class="ml-1 px-2 py-0.5 bg-white/20 rounded-full text-xs">
                                        {{ $multimedia->where('statut_moderation', 'en_attente')->count() }}
                                    </span>
                                @endif
                            </a>
                        @endcan
                        <a href="{{ route('private.multimedia.galerie') }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-eye mr-2"></i> Vue Publique
                        </a>
                        @can('multimedia.statistics')
                            <a href="{{ route('private.multimedia.statistiques') }}"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-chart-bar mr-2"></i> Statistiques
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('private.multimedia.index') }}" class="space-y-6" id="filterForm">
                    <!-- Première ligne : Recherche et événements -->
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ $currentFilters['search'] ?? '' }}"
                                    placeholder="Titre, description, photographe, lieu..."
                                    class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <i
                                    class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Culte</label>
                            <select name="culte_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les cultes</option>
                                @foreach ($cultes as $culte)
                                    <option value="{{ $culte->id }}"
                                        {{ ($currentFilters['culte_id'] ?? '') == $culte->id ? 'selected' : '' }}>
                                        {{ $culte->titre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Événement</label>
                            <select name="event_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les événements</option>
                                @foreach ($events as $event)
                                    <option value="{{ $event->id }}"
                                        {{ ($currentFilters['event_id'] ?? '') == $event->id ? 'selected' : '' }}>
                                        {{ $event->titre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Deuxième ligne : Filtres de contenu -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Type de média</label>
                            <select name="type_media"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les types</option>
                                @foreach ($filters['types_media'] as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ ($currentFilters['type_media'] ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                            <select name="categorie"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Toutes les catégories</option>
                                @foreach ($filters['categories'] as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ ($currentFilters['categorie'] ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut Modération</label>
                            <select name="statut_moderation"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les statuts</option>
                                @foreach ($filters['statuts_moderation'] as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ ($currentFilters['statut_moderation'] ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Niveau d'Accès</label>
                            <select name="niveau_acces"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les niveaux</option>
                                @foreach ($filters['niveaux_acces'] as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ ($currentFilters['niveau_acces'] ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Téléchargé par</label>
                            <select name="telecharge_par"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les membres</option>
                                @foreach ($uploaders as $uploader)
                                    <option value="{{ $uploader->id }}"
                                        {{ ($currentFilters['telecharge_par'] ?? '') == $uploader->id ? 'selected' : '' }}>
                                        {{ $uploader->nom . ' ' . $uploader->prenom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Options</label>
                            <div class="space-y-2 pt-1">
                                <label class="flex items-center">
                                    <input type="checkbox" name="featured_only" value="1"
                                        {{ request('featured_only') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">À la une uniquement</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="visible_only" value="1"
                                        {{ request('visible_only') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Visibles uniquement</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Actions de filtre -->
                    <div class="flex gap-2 pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i> Filtrer
                        </button>
                        <a href="{{ route('private.multimedia.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-refresh mr-2"></i> Réinitialiser
                        </a>
                        <button type="button" onclick="toggleAdvancedFilters()"
                            class="inline-flex items-center px-4 py-3 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                            <i class="fas fa-cog mr-2"></i> Avancé
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-photo-video text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $multimedia->total() }}</p>
                        <p class="text-sm text-slate-500">Total médias</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-images text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ $multimedia->where('type_media', 'image')->count() }}</p>
                        <p class="text-sm text-slate-500">Images</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-video text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ $multimedia->where('type_media', 'video')->count() }}</p>
                        <p class="text-sm text-slate-500">Vidéos</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-music text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ $multimedia->where('type_media', 'audio')->count() }}</p>
                        <p class="text-sm text-slate-500">Audios</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ $multimedia->where('est_featured', true)->count() }}</p>
                        <p class="text-sm text-slate-500">À la une</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions en lot (pour les modérateurs) -->
        @can('multimedia.moderate')
            <div id="bulkActions" class="hidden bg-amber-50 border border-amber-200 rounded-2xl p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm font-medium text-amber-800">
                            <span id="selectedCount">0</span> média(s) sélectionné(s)
                        </span>
                    </div>
                    <div class="flex items-center space-x-2">
                        @can('multimedia.approve')
                            <button type="button" onclick="bulkAction('approve')"
                                class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-check mr-1"></i> Approuver
                            </button>
                        @endcan
                        @can('multimedia.reject')
                            <button type="button" onclick="bulkAction('reject')"
                                class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-times mr-1"></i> Rejeter
                            </button>
                        @endcan
                        @can('multimedia.delete')
                            <button type="button" onclick="bulkAction('delete')"
                                class="inline-flex items-center px-3 py-1.5 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-trash mr-1"></i> Supprimer
                            </button>
                        @endcan
                        <button type="button" onclick="clearSelection()"
                            class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-times mr-1"></i> Annuler
                        </button>
                    </div>
                </div>
            </div>
        @endcan

        <!-- Grille des médias -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-images text-purple-600 mr-2"></i>
                        Médiathèque ({{ $multimedia->total() }})
                    </h2>
                    <div class="flex items-center space-x-4">
                        @can('multimedia.moderate')
                            <label class="flex items-center">
                                <input type="checkbox" id="selectAll"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Tout sélectionner</span>
                            </label>
                        @endcan
                        <div class="flex items-center space-x-2">
                            <button type="button" onclick="toggleView('grid')"
                                class="p-2 text-slate-600 hover:text-blue-600 transition-colors" id="gridViewBtn">
                                <i class="fas fa-th"></i>
                            </button>
                            <button type="button" onclick="toggleView('list')"
                                class="p-2 text-slate-600 hover:text-blue-600 transition-colors" id="listViewBtn">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if ($multimedia->count() > 0)
                    <!-- Vue grille (par défaut) -->
                    <div id="gridView"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach ($multimedia as $media)
                            <div
                                class="group relative bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                @can('multimedia.moderate')
                                    <div class="absolute top-2 left-2 z-10">
                                        <input type="checkbox" name="selected_media[]" value="{{ $media->id }}"
                                            class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 media-checkbox">
                                    </div>
                                @endcan

                                <!-- Aperçu du média -->
                                <div
                                    class="aspect-square relative overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                                    @if ($media->est_image && $media->url_miniature)
                                        <img src="{{ asset($media->url_miniature) }}"
                                            alt="{{ $media->alt_text ?? $media->titre }}"
                                            class="w-full h-full object-cover">
                                    @elseif($media->est_image && $media->url_complete)
                                        <img src="{{ asset($media->url_complete) }}"
                                            alt="{{ $media->alt_text ?? $media->titre }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <div class="text-center">
                                                @if ($media->type_media == 'video')
                                                    <i class="fas fa-video text-4xl text-slate-400 mb-2"></i>
                                                @elseif($media->type_media == 'audio')
                                                    <i class="fas fa-music text-4xl text-slate-400 mb-2"></i>
                                                @elseif($media->type_media == 'document')
                                                    <i class="fas fa-file-alt text-4xl text-slate-400 mb-2"></i>
                                                @else
                                                    <i class="fas fa-file text-4xl text-slate-400 mb-2"></i>
                                                @endif
                                                <p class="text-xs text-slate-500 uppercase font-medium">
                                                    {{ $media->extension }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Overlay avec badges -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="absolute bottom-2 left-2 right-2">
                                            <div class="flex items-center justify-between">
                                                <div class="flex flex-wrap gap-1">
                                                    @if ($media->est_featured)
                                                        <span
                                                            class="inline-flex items-center px-1.5 py-0.5 bg-yellow-500 text-white text-xs rounded">
                                                            <i class="fas fa-star mr-1"></i>
                                                        </span>
                                                    @endif
                                                    @if ($media->statut_moderation == 'en_attente')
                                                        <span
                                                            class="inline-flex items-center px-1.5 py-0.5 bg-orange-500 text-white text-xs rounded">
                                                            <i class="fas fa-clock mr-1"></i>
                                                        </span>
                                                    @elseif($media->statut_moderation == 'approuve')
                                                        <span
                                                            class="inline-flex items-center px-1.5 py-0.5 bg-green-500 text-white text-xs rounded">
                                                            <i class="fas fa-check mr-1"></i>
                                                        </span>
                                                    @elseif($media->statut_moderation == 'rejete')
                                                        <span
                                                            class="inline-flex items-center px-1.5 py-0.5 bg-red-500 text-white text-xs rounded">
                                                            <i class="fas fa-times mr-1"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                                <span class="text-white text-xs bg-black/20 px-1.5 py-0.5 rounded">
                                                    {{ $media->taille_formatee }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions overlay -->
                                    <div
                                        class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="flex flex-col space-y-1">
                                            <a href="{{ route('private.multimedia.show', $media) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-white/90 text-slate-700 rounded-lg hover:bg-white transition-colors"
                                                title="Voir">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            @can('multimedia.update')
                                                <a href="{{ route('private.multimedia.edit', $media) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-white/90 text-slate-700 rounded-lg hover:bg-white transition-colors"
                                                    title="Modifier">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            @endcan
                                            @can('multimedia.download')
                                                <a href="{{ route('private.multimedia.download', $media) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-white/90 text-slate-700 rounded-lg hover:bg-white transition-colors"
                                                    title="Télécharger">
                                                    <i class="fas fa-download text-sm"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>

                                <!-- Informations du média -->
                                <div class="p-4">
                                    <div class="mb-2">
                                        <h3 class="font-semibold text-slate-900 text-sm line-clamp-2 mb-1">
                                            {{ $media->titre }}</h3>
                                        <p class="text-xs text-slate-500 capitalize">{{ $media->categorie_label }}</p>
                                    </div>

                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <div class="flex items-center space-x-2">
                                            @if ($media->largeur && $media->hauteur)
                                                <span>{{ $media->dimensions_formatee }}</span>
                                            @elseif($media->duree_formatee)
                                                <span>{{ $media->duree_formatee }}</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <i class="fas fa-eye text-slate-400"></i>
                                            <span>{{ $media->nombre_vues }}</span>
                                        </div>
                                    </div>

                                    <!-- Actions rapides -->
                                    <div class="mt-3 flex items-center justify-between">
                                        <div class="text-xs text-slate-500">
                                            {{ $media->created_at->diffForHumans() }}
                                        </div>
                                        @canany(['multimedia.approve', 'multimedia.reject', 'multimedia.toggle-featured'])
                                            <div class="flex items-center space-x-1">
                                                @canany(['multimedia.approve', 'multimedia.reject'])
                                                    @if ($media->statut_moderation == 'en_attente')
                                                        @can('multimedia.approve')
                                                            <button type="button"
                                                                onclick="moderateMedia('{{ $media->id }}', 'approve')"
                                                                class="text-green-600 hover:text-green-700 transition-colors"
                                                                title="Approuver">
                                                                <i class="fas fa-check text-sm"></i>
                                                            </button>
                                                        @endcan
                                                        @can('multimedia.reject')
                                                            <button type="button"
                                                                onclick="moderateMedia('{{ $media->id }}', 'reject')"
                                                                class="text-red-600 hover:text-red-700 transition-colors"
                                                                title="Rejeter">
                                                                <i class="fas fa-times text-sm"></i>
                                                            </button>
                                                        @endcan
                                                    @endif
                                               @endcanany
                                                @can('multimedia.toggle-featured')
                                                    <button type="button" onclick="toggleFeatured('{{ $media->id }}')"
                                                        class="text-yellow-600 hover:text-yellow-700 transition-colors {{ $media->est_featured ? 'opacity-100' : 'opacity-50' }}"
                                                        title="Mettre à la une">
                                                        <i class="fas fa-star text-sm"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                        @endcanany
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Vue liste (cachée par défaut) -->
                    <div id="listView" class="hidden space-y-4">
                        @foreach ($multimedia as $media)
                            <div
                                class="flex items-center space-x-4 p-4 bg-white border border-slate-200 rounded-xl hover:shadow-md transition-all duration-300">
                                @can('multimedia.moderate')
                                    <div class="flex-shrink-0">
                                        <input type="checkbox" name="selected_media[]" value="{{ $media->id }}"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 media-checkbox">
                                    </div>
                                @endcan

                                <!-- Miniature -->
                                <div
                                    class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-lg overflow-hidden">
                                    @if ($media->est_image && $media->url_miniature)
                                        <img src="{{ asset($media->url_miniature) }}" alt="{{ $media->titre }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            @if ($media->type_media == 'video')
                                                <i class="fas fa-video text-slate-400"></i>
                                            @elseif($media->type_media == 'audio')
                                                <i class="fas fa-music text-slate-400"></i>
                                            @else
                                                <i class="fas fa-file text-slate-400"></i>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Informations -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-semibold text-slate-900 truncate">{{ $media->titre }}</h3>
                                            <div class="flex items-center space-x-4 text-sm text-slate-500 mt-1">
                                                <span class="capitalize">{{ $media->type_media_label }}</span>
                                                <span class="capitalize">{{ $media->categorie_label }}</span>
                                                <span>{{ $media->taille_formatee }}</span>
                                                @if ($media->uploadedBy)
                                                    <span>par {{ $media->uploadedBy->name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2 ml-4">
                                            <!-- Badges de statut -->
                                            @if ($media->est_featured)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                                    <i class="fas fa-star mr-1"></i> À la une
                                                </span>
                                            @endif
                                            @if ($media->statut_moderation == 'en_attente')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-800 text-xs rounded-full">
                                                    <i class="fas fa-clock mr-1"></i> En attente
                                                </span>
                                            @elseif($media->statut_moderation == 'approuve')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                                    <i class="fas fa-check mr-1"></i> Approuvé
                                                </span>
                                            @elseif($media->statut_moderation == 'rejete')
                                                <span
                                                    class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                                    <i class="fas fa-times mr-1"></i> Rejeté
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('private.multimedia.show', $media) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                        title="Voir">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    @can('multimedia.update')
                                        <a href="{{ route('private.multimedia.edit', $media) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                                            title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    @endcan
                                    @can('multimedia.download')
                                    <a href="{{ route('private.multimedia.download', $media) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors"
                                        title="Télécharger">
                                        <i class="fas fa-download text-sm"></i>
                                    </a>
                                    @endcan
                                    @canany(['multimedia.approve', 'multimedia.reject'])
                                        @if ($media->statut_moderation == 'en_attente')
                                            @can('multimedia.approve')
                                            <button type="button" onclick="moderateMedia('{{ $media->id }}', 'approve')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                                                title="Approuver">
                                                <i class="fas fa-check text-sm"></i>
                                            </button>
                                            @endcan
                                            @can('multimedia.reject')
                                            <button type="button" onclick="moderateMedia('{{ $media->id }}', 'reject')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                                title="Rejeter">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                            @endcan
                                        @endif
                                    @endcanany
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-8 pt-6 border-t border-slate-200">
                        <div class="text-sm text-slate-700">
                            Affichage de <span class="font-medium">{{ $multimedia->firstItem() }}</span> à <span
                                class="font-medium">{{ $multimedia->lastItem() }}</span>
                            sur <span class="font-medium">{{ $multimedia->total() }}</span> résultats
                        </div>
                        <div>
                            {{ $multimedia->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-photo-video text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun média trouvé</h3>
                        <p class="text-slate-500 mb-6">
                            @if (request()->hasAny(['search', 'culte_id', 'event_id', 'type_media', 'categorie']))
                                Aucun média ne correspond à vos critères de recherche.
                            @else
                                Votre médiathèque est vide. Commencez par télécharger votre premier média.
                            @endif
                        </p>
                        @can('multimedia.download')
                        <a href="{{ route('private.multimedia.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-upload mr-2"></i> Télécharger un média
                        </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modals -->
    @can('multimedia.moderate')
        <!-- Modal de modération -->
        <div id="moderationModal"
            class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-gavel text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Modération du média</h3>
                    </div>
                    <p class="text-slate-600 mb-4" id="moderationMessage">Êtes-vous sûr de vouloir effectuer cette action ?
                    </p>
                    <div id="commentSection" class="hidden mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Commentaire (requis pour le rejet)</label>
                        <textarea id="moderationComment" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Raison du rejet ou commentaire..."></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                    <button type="button" onclick="closeModerationModal()"
                        class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="button" id="confirmModeration"
                        class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal d'action en lot -->
        <div id="bulkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-list-check text-amber-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Action en lot</h3>
                    </div>
                    <p class="text-slate-600 mb-4" id="bulkMessage">Confirmer l'action sur les médias sélectionnés ?</p>
                    <div id="bulkCommentSection" class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Commentaire</label>
                        <textarea id="bulkComment" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Commentaire optionnel..."></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                    <button type="button" onclick="closeBulkModal()"
                        class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="button" id="confirmBulk"
                        class="px-4 py-2 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition-colors">
                        Confirmer
                    </button>
                </div>
            </div>
        </div>
    @endcan

    <script>
        let currentView = 'grid';

        // Basculer entre les vues grille/liste
        function toggleView(view) {
            currentView = view;
            const gridView = document.getElementById('gridView');
            const listView = document.getElementById('listView');
            const gridBtn = document.getElementById('gridViewBtn');
            const listBtn = document.getElementById('listViewBtn');

            if (view === 'grid') {
                if (gridView) {
                    gridView.classList.remove('hidden');
                }

                if (listView) {
                    listView.classList.add('hidden');
                }

                if (gridBtn) {
                    gridBtn.classList.add('text-blue-600');
                    gridBtn.classList.remove('text-slate-600');
                }

                if (listBtn) {
                    listBtn.classList.add('text-slate-600');
                    listBtn.classList.remove('text-blue-600');
                }

            } else {
                if (gridView) {
                    gridView.classList.add('hidden');
                }

                if (listView) {
                    listView.classList.remove('hidden');
                }

                if (listBtn) {
                    listBtn.classList.add('text-blue-600');
                    listBtn.classList.remove('text-slate-600');
                }

                if (gridBtn) {
                    gridBtn.classList.add('text-slate-600');
                    gridBtn.classList.remove('text-blue-600');
                }
            }

            // Sauvegarder la préférence
            localStorage.setItem('multimedia_view', view);
        }

        // Charger la préférence de vue au chargement
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('multimedia_view') || 'grid';
            toggleView(savedView);
        });

        @can('multimedia.moderate')
            // Gestion de la sélection
            const selectAllCheckbox = document.getElementById('selectAll');
            const mediaCheckboxes = document.querySelectorAll('.media-checkbox');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');

            selectAllCheckbox?.addEventListener('change', function() {
                mediaCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateBulkActions();
            });

            mediaCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });

            function updateBulkActions() {
                const checked = document.querySelectorAll('.media-checkbox:checked');
                const count = checked.length;

                if (count > 0) {
                    bulkActions?.classList.remove('hidden');
                    selectedCount.textContent = count;
                } else {
                    bulkActions?.classList.add('hidden');
                    if (selectAllCheckbox) selectAllCheckbox.checked = false;
                }
            }

            function clearSelection() {
                mediaCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                if (selectAllCheckbox) selectAllCheckbox.checked = false;
                updateBulkActions();
            }

            // Modération individuelle
            function moderateMedia(mediaId, action) {
                const modal = document.getElementById('moderationModal');
                const message = document.getElementById('moderationMessage');
                const commentSection = document.getElementById('commentSection');
                const confirmBtn = document.getElementById('confirmModeration');

                if (action === 'approve') {
                    message.textContent = 'Êtes-vous sûr de vouloir approuver ce média ?';
                    commentSection.classList.add('hidden');
                    confirmBtn.className =
                        'px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors';
                    confirmBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Approuver';
                } else if (action === 'reject') {
                    message.textContent = 'Pourquoi voulez-vous rejeter ce média ?';
                    commentSection.classList.remove('hidden');
                    confirmBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors';
                    confirmBtn.innerHTML = '<i class="fas fa-times mr-2"></i>Rejeter';
                }

                confirmBtn.onclick = function() {
                    const comment = document.getElementById('moderationComment').value;
                    if (action === 'reject' && !comment.trim()) {
                        alert('Un commentaire est requis pour rejeter un média.');
                        return;
                    }

                    const formData = new FormData();
                    if (comment) formData.append('commentaire', comment);
                    if (action === 'reject') formData.append('raison', comment);

                    fetch(`{{ route('private.multimedia.index') }}/${mediaId}/${action}`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.message || 'Une erreur est survenue');
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            alert('Une erreur est survenue');
                        })
                        .finally(() => {
                            closeModerationModal();
                        });
                };

                modal.classList.remove('hidden');
            }

            function closeModerationModal() {
                const modal = document.getElementById('moderationModal');
                modal.classList.add('hidden');
                document.getElementById('moderationComment').value = '';
            }

            // Actions en lot
            function bulkAction(action) {
                const selected = Array.from(document.querySelectorAll('.media-checkbox:checked'))
                    .map(cb => cb.value);

                if (selected.length === 0) {
                    alert('Veuillez sélectionner au moins un média');
                    return;
                }

                const modal = document.getElementById('bulkModal');
                const message = document.getElementById('bulkMessage');
                const confirmBtn = document.getElementById('confirmBulk');

                const actions = {
                    approve: 'approuver',
                    reject: 'rejeter',
                    delete: 'supprimer'
                };

                message.textContent = `Êtes-vous sûr de vouloir ${actions[action]} ${selected.length} média(s) ?`;

                confirmBtn.onclick = function() {
                    const comment = document.getElementById('bulkComment').value;

                    if (action === 'reject' && !comment.trim()) {
                        alert('Un commentaire est requis pour rejeter des médias.');
                        return;
                    }

                    fetch("{{ route('private.multimedia.bulk-moderate') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                media_ids: selected,
                                action: action,
                                commentaire: comment
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.message || 'Une erreur est survenue');
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            alert('Une erreur est survenue');
                        })
                        .finally(() => {
                            closeBulkModal();
                        });
                };

                modal.classList.remove('hidden');
            }

            function closeBulkModal() {
                const modal = document.getElementById('bulkModal');
                modal.classList.add('hidden');
                document.getElementById('bulkComment').value = '';
            }
        @endcan

        @can('multimedia.toggle-featured')
            // Basculer featured
            function toggleFeatured(mediaId) {
                fetch(`{{ route('private.multimedia.toggle-featured', ':multimedia') }}`.replace(':multimedia', mediaId), {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
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
        @endcan

        // Fermeture des modals en cliquant à l'extérieur
        @can('multimedia.moderate')
            document.getElementById('moderationModal')?.addEventListener('click', function(e) {
                if (e.target === this) closeModerationModal();
            });

            document.getElementById('bulkModal')?.addEventListener('click', function(e) {
                if (e.target === this) closeBulkModal();
            });
        @endcan

        // Filtres avancés (placeholder pour extension future)
        function toggleAdvancedFilters() {
            // Logique pour afficher/masquer des filtres avancés
            console.log('Filtres avancés à implémenter');
        }

        // Auto-submit des filtres au changement
        document.getElementById('filterForm').addEventListener('change', function(e) {
            if (e.target.type !== 'text') {
                this.submit();
            }
        });
    </script>

@endsection
