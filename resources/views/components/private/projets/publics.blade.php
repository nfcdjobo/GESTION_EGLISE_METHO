@extends('layouts.private.main')
@section('title', 'Projets Publics')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Description -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Projets Publics</h1>
        <p class="text-slate-500 mt-1">Projets ouverts aux dons et visibles au public</p>
    </div>
 
    <!-- Filtres -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-filter text-blue-600 mr-2"></i>
                Filtrer les Projets
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.projets.publics') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de projet</label>
                    <select name="type_projet" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <option value="construction" {{ request('type_projet') == 'construction' ? 'selected' : '' }}>Construction</option>
                        <option value="renovation" {{ request('type_projet') == 'renovation' ? 'selected' : '' }}>Rénovation</option>
                        <option value="social" {{ request('type_projet') == 'social' ? 'selected' : '' }}>Social</option>
                        <option value="evangelisation" {{ request('type_projet') == 'evangelisation' ? 'selected' : '' }}>Évangélisation</option>
                        <option value="formation" {{ request('type_projet') == 'formation' ? 'selected' : '' }}>Formation</option>
                        <option value="education" {{ request('type_projet') == 'education' ? 'selected' : '' }}>Éducation</option>
                        <option value="sante" {{ request('type_projet') == 'sante' ? 'selected' : '' }}>Santé</option>
                        <option value="humanitaire" {{ request('type_projet') == 'humanitaire' ? 'selected' : '' }}>Humanitaire</option>
                        <option value="communautaire" {{ request('type_projet') == 'communautaire' ? 'selected' : '' }}>Communautaire</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Ville</label>
                    <input type="text" name="ville" value="{{ request('ville') }}" placeholder="Rechercher par ville..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Région</label>
                    <input type="text" name="region" value="{{ request('region') }}" placeholder="Rechercher par région..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Projets ouverts</p>
                    <p class="text-2xl font-bold">{{ $projets->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-project-diagram text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Budget total recherché</p>
                    <p class="text-2xl font-bold">{{ number_format($projets->sum('budget_prevu'), 0, ',', ' ') }} XOF</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Montant collecté</p>
                    <p class="text-2xl font-bold">{{ number_format($projets->sum('budget_collecte'), 0, ',', ' ') }} XOF</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-donate text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des projets publics -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-globe text-green-600 mr-2"></i>
                Projets Ouverts aux Dons ({{ $projets->count() }})
            </h2>
        </div>
        <div class="p-6">
            @if($projets->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($projets as $projet)
                        <div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <!-- Image du projet -->
                            @if($projet->image_principale)
                                <div class="h-48 bg-cover bg-center" style="background-image: url('{{ $projet->image_principale }}')">
                                    <div class="h-full bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
                                        @php
                                            $prioriteColors = [
                                                'faible' => 'bg-gray-500',
                                                'normale' => 'bg-blue-500',
                                                'haute' => 'bg-yellow-500',
                                                'urgente' => 'bg-orange-500',
                                                'critique' => 'bg-red-500'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-white {{ $prioriteColors[$projet->priorite] ?? 'bg-gray-500' }}">
                                            {{ $projet->priorite_libelle }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="h-48 bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center">
                                    <div class="text-center text-white">
                                        <i class="fas fa-project-diagram text-4xl mb-2"></i>
                                        <p class="text-sm font-medium">{{ $projet->type_projet_libelle }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="p-6">
                                <!-- En-tête du projet -->
                                <div class="mb-4">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2 line-clamp-2">{{ $projet->nom_projet }}</h3>
                                    <div class="flex items-center text-sm text-slate-600 mb-2">
                                        <i class="fas fa-tag mr-2"></i>
                                        <span>{{ $projet->type_projet_libelle }}</span>
                                    </div>
                                    @if($projet->localisation)
                                        <div class="flex items-center text-sm text-slate-600">
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            <span>{{ $projet->localisation }}@if($projet->ville), {{ $projet->ville }}@endif</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Description -->
                                @if($projet->description)
                                    <div class="mb-4">
                                        <p class="text-sm text-slate-600 line-clamp-3">
                                            {{ strip_tags(Str::limit($projet->description, 150)) }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Objectifs -->
                                @if($projet->objectif)
                                    <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                                        <h4 class="text-sm font-semibold text-blue-900 mb-1">
                                            <i class="fas fa-bullseye mr-1"></i>Objectifs
                                        </h4>
                                        <p class="text-xs text-blue-800 line-clamp-2">
                                            {{ strip_tags(Str::limit($projet->objectif, 100)) }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Responsable -->
                                @if($projet->responsable)
                                    <div class="mb-4 flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-blue-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $projet->responsable->nom_complet }}</p>
                                            <p class="text-xs text-slate-500">Responsable du projet</p>
                                        </div>
                                    </div>
                                @endif

                                <!-- Budget et progression -->
                                @if($projet->budget_prevu)
                                    <div class="mb-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-sm font-medium text-slate-700">Financement</span>
                                            <span class="text-sm font-semibold text-green-600">{{ $projet->pourcentage_financement }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-2 mb-2">
                                            <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ min($projet->pourcentage_financement, 100) }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-slate-600">
                                            <span>Collecté: {{ number_format($projet->budget_collecte, 0, ',', ' ') }} {{ $projet->devise }}</span>
                                            <span>Objectif: {{ number_format($projet->budget_prevu, 0, ',', ' ') }} {{ $projet->devise }}</span>
                                        </div>
                                        @if($projet->montant_restant > 0)
                                            <div class="text-center mt-2">
                                                <span class="text-sm font-medium text-orange-600">
                                                    Reste: {{ number_format($projet->montant_restant, 0, ',', ' ') }} {{ $projet->devise }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <a href="{{ route('private.projets.show', $projet) }}" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                                        <i class="fas fa-eye mr-2"></i>Détails
                                    </a>
                                    @if($projet->site_web)
                                        <a href="{{ $projet->site_web }}" target="_blank" class="inline-flex items-center justify-center w-10 h-10 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors" title="Site web">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-globe text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun projet public trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['type_projet', 'ville', 'region']))
                            Aucun projet ne correspond à vos critères de recherche.
                        @else
                            Il n'y a actuellement aucun projet ouvert aux dons publics.
                        @endif
                    </p>
                    @if(request()->hasAny(['type_projet', 'ville', 'region']))
                        <a href="{{ route('private.projets.publics') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                            <i class="fas fa-refresh mr-2"></i>Voir tous les projets
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Comment faire un don -->
    @if($projets->count() > 0)
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl shadow-lg text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold mb-2">
                        <i class="fas fa-heart mr-2"></i>
                        Soutenez nos Projets
                    </h3>
                    <p class="text-green-100 mb-4">Votre contribution peut faire la différence dans la vie de nombreuses personnes.</p>
                    <div class="flex flex-wrap gap-4 text-sm text-green-100">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt mr-2"></i>
                            <span>Donations sécurisées</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-chart-line mr-2"></i>
                            <span>Suivi transparent</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            <span>Impact communautaire</span>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-donate text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
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
</style>
@endpush
@endsection
