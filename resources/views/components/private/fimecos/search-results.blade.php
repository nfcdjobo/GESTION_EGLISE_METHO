@extends('layouts.private.main')
@section('title', 'Résultats de recherche FIMECOs')

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('private.fimecos.index') }}"
                    class="inline-flex items-center justify-center w-10 h-10 bg-white/80 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                    <i class="fas fa-arrow-left text-slate-600"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Résultats de recherche
                    </h1>
                    <p class="text-slate-500 mt-1">
                        @if(isset($query) && $query)
                            Recherche pour "{{ $query }}" - {{ $count ?? 0 }} résultat(s) trouvé(s)
                        @else
                            Aucun terme de recherche fourni
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Barre de recherche -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <form method="GET" action="{{ route('private.fimecos.search') }}" class="flex gap-4">
                    <div class="flex-1 relative">
                        <input type="text" name="q" value="{{ $query ?? '' }}"
                            placeholder="Rechercher un FIMECO par nom, description ou responsable..."
                            class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-search mr-2"></i>
                        Rechercher
                    </button>
                </form>
            </div>
        </div>

        @if(isset($results) && $results->count() > 0)
            <!-- Résultats de recherche -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-list text-green-600 mr-2"></i>
                        Résultats trouvés
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $results->count() }}
                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($results as $fimeco)
                            <div class="bg-gradient-to-r from-white to-slate-50 rounded-xl shadow-md border border-slate-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                                <div class="p-6">
                                    <!-- En-tête du FIMECO -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-slate-800 mb-2">
                                                {{ Str::limit($fimeco->nom, 30) }}
                                            </h3>
                                            @if($fimeco->description)
                                                <p class="text-sm text-slate-600 mb-2">
                                                    {{ Str::limit($fimeco->description, 60) }}
                                                </p>
                                            @endif
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($fimeco->statut === 'active') bg-green-100 text-green-800
                                            @elseif($fimeco->statut === 'cloturee') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($fimeco->statut) }}
                                        </span>
                                    </div>

                                    <!-- Responsable -->
                                    @if($fimeco->responsable)
                                        <div class="flex items-center mb-4">
                                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-white text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-slate-800">{{ $fimeco->responsable->nom }}</p>
                                                <p class="text-xs text-slate-500">Responsable</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Statistiques -->
                                    <div class="space-y-3 mb-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-slate-600">Cible</span>
                                            <span class="text-sm font-bold text-slate-800">
                                                {{ number_format($fimeco->cible, 0, ',', ' ') }} FCFA
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-slate-600">Collecté</span>
                                            <span class="text-sm font-bold text-green-600">
                                                {{ number_format($fimeco->montant_solde, 0, ',', ' ') }} FCFA
                                            </span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-slate-600">Progression</span>
                                            <span class="text-sm font-bold text-blue-600">
                                                {{ number_format($fimeco->progression, 1) }}%
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Barre de progression -->
                                    <div class="mb-4">
                                        <div class="flex justify-between text-xs text-slate-600 mb-1">
                                            <span>Progression</span>
                                            <span>{{ number_format($fimeco->progression, 1) }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $fimeco->progression >= 100 ? 'bg-green-500' : ($fimeco->progression >= 75 ? 'bg-blue-500' : ($fimeco->progression >= 50 ? 'bg-yellow-500' : 'bg-red-500')) }}"
                                                 style="width: {{ min($fimeco->progression, 100) }}%"></div>
                                        </div>
                                    </div>

                                    <!-- Période -->
                                    <div class="mb-4">
                                        <div class="text-xs text-slate-500">
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $fimeco->debut->format('d/m/Y') }} - {{ $fimeco->fin->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1">
                                            @if($fimeco->jours_restants > 0)
                                                {{ $fimeco->jours_restants }} jours restants
                                            @elseif($fimeco->en_retard)
                                                <span class="text-red-600">En retard</span>
                                            @else
                                                Terminé
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex gap-2">
                                        @can('fimecos.read')
                                            <a href="{{ route('private.fimecos.show', $fimeco) }}"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                                                <i class="fas fa-eye mr-1"></i>
                                                Voir
                                            </a>
                                        @endcan
                                        @can('fimecos.update')
                                            <a href="{{ route('private.fimecos.edit', $fimeco) }}"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-lg hover:from-yellow-700 hover:to-orange-700 transition-all duration-200">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        @elseif(isset($query) && $query)
            <!-- Aucun résultat -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-8">
                <div class="text-center">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-search text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun résultat trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        Aucun FIMECO ne correspond à votre recherche "{{ $query }}".
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('private.fimecos.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-list mr-2"></i>
                            Voir tous les FIMECOs
                        </a>
                        @can('fimecos.create')
                            <a href="{{ route('private.fimecos.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Créer un FIMECO
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

        @else
            <!-- Suggestions de recherche -->
            @if(isset($suggestions))
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                            Suggestions de recherche
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- FIMECOs populaires -->
                            @if(isset($suggestions['fimecos_populaires']) && count($suggestions['fimecos_populaires']) > 0)
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                                    <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center">
                                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                                        FIMECOs populaires
                                    </h3>
                                    <div class="space-y-2">
                                        @foreach($suggestions['fimecos_populaires'] as $nom)
                                            <a href="{{ route('private.fimecos.search', ['q' => $nom]) }}"
                                                class="block text-sm text-blue-700 hover:text-blue-900 hover:underline transition-colors">
                                                {{ $nom }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Recherche par statut -->
                            @if(isset($suggestions['statuts_disponibles']))
                                <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                                    <h3 class="text-lg font-semibold text-green-800 mb-3 flex items-center">
                                        <i class="fas fa-tags text-green-600 mr-2"></i>
                                        Par statut
                                    </h3>
                                    <div class="space-y-2">
                                        @foreach($suggestions['statuts_disponibles'] as $statut)
                                            <a href="{{ route('private.fimecos.index', ['statut' => $statut]) }}"
                                                class="block text-sm text-green-700 hover:text-green-900 hover:underline transition-colors capitalize">
                                                {{ $statut }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Recherche par progression -->
                            @if(isset($suggestions['statuts_globaux']))
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                                    <h3 class="text-lg font-semibold text-purple-800 mb-3 flex items-center">
                                        <i class="fas fa-chart-line text-purple-600 mr-2"></i>
                                        Par progression
                                    </h3>
                                    <div class="space-y-2">
                                        @foreach($suggestions['statuts_globaux'] as $statut_global)
                                            <a href="{{ route('private.fimecos.index', ['statut_global' => $statut_global]) }}"
                                                class="block text-sm text-purple-700 hover:text-purple-900 hover:underline transition-colors capitalize">
                                                {{ str_replace('_', ' ', $statut_global) }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <!-- Recherche avancée -->
        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 rounded-2xl shadow-lg border border-amber-200 p-6">
            <h3 class="text-lg font-semibold text-amber-800 mb-4 flex items-center">
                <i class="fas fa-search-plus text-amber-600 mr-2"></i>
                Conseils de recherche
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex items-start">
                        <i class="fas fa-quote-left text-amber-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Recherche exacte</div>
                            <div class="text-sm text-slate-600">Utilisez des guillemets pour une phrase exacte</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-user text-amber-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Par responsable</div>
                            <div class="text-sm text-slate-600">Recherchez par nom du responsable</div>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <i class="fas fa-filter text-amber-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Filtres avancés</div>
                            <div class="text-sm text-slate-600">
                                <a href="{{ route('private.fimecos.index') }}" class="text-amber-700 hover:underline">
                                    Utilisez la page principale pour des filtres avancés
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-keyboard text-amber-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Recherche partielle</div>
                            <div class="text-sm text-slate-600">Tapez quelques lettres pour des suggestions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Animation des cartes au chargement
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.bg-gradient-to-r');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    // card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        // card.style.transform = 'translateY(0)';
                    }, index * 100);
                });

                // Focus automatique sur le champ de recherche
                const searchInput = document.querySelector('input[name="q"]');
                if (searchInput && !searchInput.value) {
                    searchInput.focus();
                }
            });
        </script>
    @endpush
@endsection
