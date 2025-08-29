@extends('layouts.public.main')
@section('title', 'Galerie Multimédia')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50">
    <!-- En-tête de la galerie -->
    <div class="relative py-16 lg:py-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 via-purple-600/10 to-pink-600/10"></div>
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-white/80 backdrop-blur-sm"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold bg-gradient-to-r from-slate-800 via-blue-700 to-purple-700 bg-clip-text text-transparent">
                    Galerie Multimédia
                </h1>
                <p class="mt-4 text-xl text-slate-600 max-w-3xl mx-auto">
                    Découvrez les moments forts de notre communauté à travers photos, vidéos et enregistrements
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-4 text-sm text-slate-500">
                    <div class="flex items-center">
                        <i class="fas fa-images text-green-500 mr-2"></i>
                        <span>{{ $galerie->where('type_media', 'image')->count() }} Photos</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-video text-red-500 mr-2"></i>
                        <span>{{ $galerie->where('type_media', 'video')->count() }} Vidéos</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-music text-purple-500 mr-2"></i>
                        <span>{{ $galerie->where('type_media', 'audio')->count() }} Audios</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                        <span>{{ $galerie->where('est_featured', true)->count() }} À la une</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres publics -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-white/40 p-6">
            <form method="GET" action="{{ route('public.multimedia.index') }}" class="space-y-6" id="filterForm">
                <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                    <!-- Recherche -->
                    <div class="flex-1 max-w-md">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Rechercher</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ $currentFilters['search'] ?? '' }}"
                                   placeholder="Rechercher par titre, description..."
                                   class="w-full pl-10 pr-4 py-3 bg-white/70 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <!-- Filtres -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 flex-1">
                        <!-- Type de média -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                            <select name="type_media" class="w-full px-4 py-3 bg-white/70 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">Tous les types</option>
                                @foreach($types_media as $key => $label)
                                    <option value="{{ $key }}" {{ ($currentFilters['type_media'] ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Catégorie -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                            <select name="categorie" class="w-full px-4 py-3 bg-white/70 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                <option value="">Toutes</option>
                                @foreach($categories as $key => $label)
                                    <option value="{{ $key }}" {{ ($currentFilters['categorie'] ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-search mr-2"></i> Filtrer
                            </button>
                            <a href="{{ route('public.multimedia.index') }}" class="inline-flex items-center justify-center px-4 py-3 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-all duration-200">
                                <i class="fas fa-refresh"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filtres rapides -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-medium text-slate-700">Filtres rapides:</span>
                    <a href="{{ route('public.multimedia.photos') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-full transition-all duration-200 {{ request()->routeIs('public.multimedia.photos') ? 'bg-green-100 text-green-800' : 'bg-white/50 text-slate-600 hover:bg-green-50' }}">
                        <i class="fas fa-images mr-1"></i> Photos
                    </a>
                    <a href="{{ route('public.multimedia.videos') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-full transition-all duration-200 {{ request()->routeIs('public.multimedia.videos') ? 'bg-red-100 text-red-800' : 'bg-white/50 text-slate-600 hover:bg-red-50' }}">
                        <i class="fas fa-video mr-1"></i> Vidéos
                    </a>
                    <a href="{{ route('public.multimedia.audios') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-full transition-all duration-200 {{ request()->routeIs('public.multimedia.audios') ? 'bg-purple-100 text-purple-800' : 'bg-white/50 text-slate-600 hover:bg-purple-50' }}">
                        <i class="fas fa-music mr-1"></i> Audios
                    </a>
                    @if(($currentFilters['search'] ?? '') || ($currentFilters['type_media'] ?? '') || ($currentFilters['categorie'] ?? ''))
                        <a href="{{ route('public.multimedia.index') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-red-50 rounded-full hover:bg-red-100 transition-all duration-200">
                            <i class="fas fa-times mr-1"></i> Effacer les filtres
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Média à la une -->
    @php $featured = $galerie->where('est_featured', true)->take(3); @endphp
    @if($featured->count() > 0)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-slate-800 mb-2">À la Une</h2>
                <p class="text-slate-600">Les moments forts mis en avant</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($featured as $media)
                    <div class="group relative bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/40 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                        <!-- Badge Featured -->
                        <div class="absolute top-4 left-4 z-20">
                            <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-yellow-500 to-amber-500 text-white text-sm font-medium rounded-full shadow-lg">
                                <i class="fas fa-star mr-1"></i> À la une
                            </span>
                        </div>

                        <!-- Aperçu média -->
                        <div class="aspect-video relative overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                            @if($media->type_media == 'image')
                                <img src="{{ $media->url_publique }}" alt="{{ $media->titre }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @elseif($media->type_media == 'video')
                                <div class="relative w-full h-full bg-black flex items-center justify-center">
                                    <video class="w-full h-full object-cover" preload="metadata">
                                        <source src="{{ $media->url_publique }}" type="{{ $media->type_mime }}">
                                    </video>
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/10 transition-colors duration-300">
                                        <div class="w-16 h-16 bg-white/90 rounded-full flex items-center justify-center shadow-lg">
                                            <i class="fas fa-play text-slate-700 ml-1"></i>
                                        </div>
                                    </div>
                                </div>
                            @elseif($media->type_media == 'audio')
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-500 to-pink-500">
                                    <div class="text-center text-white">
                                        <i class="fas fa-music text-6xl mb-4 opacity-80"></i>
                                        <p class="text-lg font-medium">{{ $media->titre }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Overlay avec infos -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="flex items-center justify-between text-white">
                                        <div class="flex items-center space-x-2 text-sm">
                                            <i class="fas fa-eye"></i>
                                            <span>{{ $media->nombre_vues }}</span>
                                            @if($media->duree_secondes)
                                                <span class="ml-3">{{ gmdate('i:s', $media->duree_secondes) }}</span>
                                            @endif
                                        </div>
                                        <div class="text-xs opacity-80">
                                            {{ $media->created_at->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations -->
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <h3 class="font-bold text-slate-900 text-lg leading-tight line-clamp-2">{{ $media->titre }}</h3>
                                <div class="flex-shrink-0 ml-2">
                                    <span class="inline-flex items-center px-2 py-1 bg-slate-100 text-slate-600 text-xs rounded-full">
                                        {{ $media->type_media }}
                                    </span>
                                </div>
                            </div>

                            @if($media->legende)
                                <p class="text-slate-600 text-sm leading-relaxed line-clamp-3 mb-4">{{ $media->legende }}</p>
                            @endif

                            <div class="flex items-center justify-between">
                                <div class="text-xs text-slate-500 capitalize">
                                    {{ str_replace('_', ' ', $media->categorie) }}
                                </div>
                                <a href="{{ route('public.multimedia.show', $media) }}"
                                   class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-eye mr-2"></i> Voir
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Grille principale des médias -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">
                    @if($currentFilters['search'] ?? false)
                        Résultats pour "{{ $currentFilters['search'] }}"
                    @elseif($currentFilters['type_media'] ?? false)
                        {{ $types_media[$currentFilters['type_media']] ?? 'Médias' }}
                    @elseif($currentFilters['categorie'] ?? false)
                        {{ $categories[$currentFilters['categorie']] ?? 'Médias' }}
                    @else
                        Tous les médias
                    @endif
                </h2>
                <p class="text-slate-600">{{ $galerie->total() }} média(s) trouvé(s)</p>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" onclick="toggleView('grid')" class="p-2 text-slate-600 hover:text-blue-600 transition-colors" id="gridViewBtn">
                    <i class="fas fa-th"></i>
                </button>
                <button type="button" onclick="toggleView('list')" class="p-2 text-slate-600 hover:text-blue-600 transition-colors" id="listViewBtn">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>

        @if($galerie->count() > 0)
            <!-- Vue grille (par défaut) -->
            <div id="gridView" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($galerie as $media)
                    <div class="group bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-white/40 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <!-- Aperçu média -->
                        <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                            @if($media->type_media == 'image')
                                <img src="{{ $media->url_publique }}" alt="{{ $media->titre }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @elseif($media->type_media == 'video')
                                <div class="relative w-full h-full bg-black flex items-center justify-center">
                                    <video class="w-full h-full object-cover" preload="metadata">
                                        <source src="{{ $media->url_publique }}" type="{{ $media->type_mime }}">
                                    </video>
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/10 transition-colors duration-300">
                                        <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center">
                                            <i class="fas fa-play text-slate-700 ml-0.5"></i>
                                        </div>
                                    </div>
                                </div>
                            @elseif($media->type_media == 'audio')
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-500 to-pink-500">
                                    <div class="text-center text-white">
                                        <i class="fas fa-music text-4xl mb-2 opacity-80"></i>
                                        <p class="text-sm font-medium px-2">{{ Str::limit($media->titre, 30) }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <i class="fas fa-file text-4xl text-slate-400 mb-2"></i>
                                        <p class="text-sm text-slate-600 font-medium">{{ strtoupper($media->extension ?? 'FILE') }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Badge featured -->
                            @if($media->est_featured)
                                <div class="absolute top-2 left-2">
                                    <span class="inline-flex items-center px-2 py-1 bg-yellow-500 text-white text-xs rounded-full">
                                        <i class="fas fa-star mr-1"></i>
                                    </span>
                                </div>
                            @endif

                            <!-- Info overlay -->
                            <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="bg-black/60 text-white text-xs px-2 py-1 rounded">
                                    @if($media->duree_secondes)
                                        {{ gmdate('i:s', $media->duree_secondes) }}
                                    @else
                                        {{ $media->created_at->format('d/m') }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Informations -->
                        <div class="p-4">
                            <h3 class="font-semibold text-slate-900 text-sm line-clamp-2 mb-2">{{ $media->titre }}</h3>
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-3">
                                <span class="capitalize">{{ str_replace('_', ' ', $media->categorie) }}</span>
                                <div class="flex items-center space-x-2">
                                    <span class="flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        {{ $media->nombre_vues }}
                                    </span>
                                </div>
                            </div>
                            <a href="{{ route('public.multimedia.show', $media) }}"
                               class="inline-flex items-center justify-center w-full px-3 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-eye mr-2"></i> Voir
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Vue liste (cachée par défaut) -->
            <div id="listView" class="hidden space-y-4">
                @foreach($galerie as $media)
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-lg border border-white/40 p-6 hover:shadow-xl transition-all duration-300">
                        <div class="flex items-center space-x-6">
                            <!-- Miniature -->
                            <div class="flex-shrink-0 w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 rounded-lg overflow-hidden">
                                @if($media->type_media == 'image')
                                    <img src="{{ $media->url_publique }}" alt="{{ $media->titre }}" class="w-full h-full object-cover">
                                @elseif($media->type_media == 'video')
                                    <div class="relative w-full h-full bg-black flex items-center justify-center">
                                        <i class="fas fa-play text-white text-2xl"></i>
                                    </div>
                                @elseif($media->type_media == 'audio')
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-purple-500 to-pink-500">
                                        <i class="fas fa-music text-white text-2xl"></i>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-file text-slate-400 text-2xl"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Informations -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="min-w-0 flex-1">
                                        <h3 class="font-semibold text-slate-900 text-lg truncate">{{ $media->titre }}</h3>
                                        <div class="flex items-center space-x-4 text-sm text-slate-500 mt-1">
                                            <span class="capitalize">{{ $types_media[$media->type_media] ?? $media->type_media }}</span>
                                            <span class="capitalize">{{ str_replace('_', ' ', $media->categorie) }}</span>
                                            @if($media->duree_secondes)
                                                <span>{{ gmdate('i:s', $media->duree_secondes) }}</span>
                                            @endif
                                            <span>{{ $media->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        @if($media->legende)
                                            <p class="text-slate-600 text-sm mt-2 line-clamp-2">{{ $media->legende }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-4 ml-4">
                                        @if($media->est_featured)
                                            <span class="inline-flex items-center px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">
                                                <i class="fas fa-star mr-1"></i> À la une
                                            </span>
                                        @endif
                                        <div class="text-sm text-slate-500 flex items-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            {{ $media->nombre_vues }}
                                        </div>
                                        <a href="{{ route('public.multimedia.show', $media) }}"
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                            <i class="fas fa-eye mr-2"></i> Voir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-12 pt-8 border-t border-white/40">
                <div class="text-sm text-slate-700">
                    Affichage de <span class="font-medium">{{ $galerie->firstItem() }}</span> à <span class="font-medium">{{ $galerie->lastItem() }}</span>
                    sur <span class="font-medium">{{ $galerie->total() }}</span> résultats
                </div>
                <div class="pagination-custom">
                    {{ $galerie->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            <!-- État vide -->
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-photo-video text-4xl text-slate-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-900 mb-3">Aucun média trouvé</h3>
                <p class="text-slate-600 mb-6 max-w-md mx-auto">
                    @if($currentFilters['search'] ?? false)
                        Aucun média ne correspond à votre recherche "{{ $currentFilters['search'] }}".
                    @else
                        Aucun média ne correspond à vos critères de filtrage.
                    @endif
                </p>
                <a href="{{ route('public.multimedia.index') }}"
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-refresh mr-2"></i> Voir tous les médias
                </a>
            </div>
        @endif
    </div>

    <!-- Section suggestions -->
    @if($galerie->count() > 0)
        <div class="bg-white/50 backdrop-blur-sm border-t border-white/40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-slate-800 mb-2">Découvrez aussi</h2>
                    <p class="text-slate-600">D'autres contenus qui pourraient vous intéresser</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                    <a href="{{ route('public.multimedia.photos') }}"
                       class="group bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-8 text-white hover:from-green-600 hover:to-emerald-700 transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-2xl">
                        <div class="text-center">
                            <i class="fas fa-images text-4xl mb-4 opacity-90 group-hover:scale-110 transition-transform duration-300"></i>
                            <h3 class="text-xl font-bold mb-2">Galerie Photos</h3>
                            <p class="text-green-100 text-sm">Moments capturés de notre communauté</p>
                        </div>
                    </a>
                    <a href="{{ route('public.multimedia.videos') }}"
                       class="group bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl p-8 text-white hover:from-red-600 hover:to-pink-700 transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-2xl">
                        <div class="text-center">
                            <i class="fas fa-video text-4xl mb-4 opacity-90 group-hover:scale-110 transition-transform duration-300"></i>
                            <h3 class="text-xl font-bold mb-2">Vidéothèque</h3>
                            <p class="text-red-100 text-sm">Prédications et événements filmés</p>
                        </div>
                    </a>
                    <a href="{{ route('public.multimedia.audios') }}"
                       class="group bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl p-8 text-white hover:from-purple-600 hover:to-indigo-700 transition-all duration-300 hover:-translate-y-1 shadow-lg hover:shadow-2xl">
                        <div class="text-center">
                            <i class="fas fa-music text-4xl mb-4 opacity-90 group-hover:scale-110 transition-transform duration-300"></i>
                            <h3 class="text-xl font-bold mb-2">Audiothèque</h3>
                            <p class="text-purple-100 text-sm">Louanges et enseignements audio</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

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
        if(gridView) gridView.classList.remove('hidden');
        if(listView) listView.classList.add('hidden');
        if(gridBtn) {
            gridBtn.classList.add('text-blue-600');
            gridBtn.classList.remove('text-slate-600');
        }
        if(listBtn) {
            listBtn.classList.add('text-slate-600');
            listBtn.classList.remove('text-blue-600');
        }
    } else {
        if(gridView) gridView.classList.add('hidden');
        if(listView) listView.classList.remove('hidden');
        if(listBtn) {
            listBtn.classList.add('text-blue-600');
            listBtn.classList.remove('text-slate-600');
        }
        if(gridBtn) {
            gridBtn.classList.add('text-slate-600');
            gridBtn.classList.remove('text-blue-600');
        }
    }

    // Sauvegarder la préférence
    localStorage.setItem('public_multimedia_view', view);
}

// Charger la préférence de vue au chargement
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('public_multimedia_view') || 'grid';
    toggleView(savedView);
});

// Auto-submit des filtres au changement
document.getElementById('filterForm').addEventListener('change', function(e) {
    if (e.target.type !== 'text') {
        this.submit();
    }
});

// Smooth scroll pour les liens d'ancrage
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Animations d'entrée au scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-fadeInUp');
        }
    });
}, observerOptions);

// Observer tous les éléments de média
document.querySelectorAll('.group').forEach(el => {
    observer.observe(el);
});
</script>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Pagination personnalisée */
.pagination-custom nav {
    @apply flex items-center justify-center space-x-2;
}

.pagination-custom .page-link {
    @apply px-4 py-2 text-sm font-medium text-slate-700 bg-white/70 border border-slate-200 rounded-lg hover:bg-blue-50 hover:text-blue-700 transition-all duration-200;
}

.pagination-custom .page-item.active .page-link {
    @apply bg-gradient-to-r from-blue-600 to-purple-600 text-white border-transparent shadow-md;
}

.pagination-custom .page-item.disabled .page-link {
    @apply text-slate-400 cursor-not-allowed;
}

/* Responsive */
@media (max-width: 640px) {
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
}
</style>

@endsection
