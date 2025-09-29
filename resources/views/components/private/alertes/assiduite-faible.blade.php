@extends('layouts.private.main')
@section('title', 'Alertes d\'Assiduité')

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-red-800 to-orange-600 bg-clip-text text-transparent">
                Alertes d'Assiduité
            </h1>
            <p class="text-slate-500 mt-1">
                Membres nécessitant un suivi pastoral - {{ $dateAnalyse->locale('fr')->format('l d F Y') }}
            </p>
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
                    <button onclick="exporterAlertes()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </button>
                        <button onclick="envoyerRappels()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-envelope mr-2"></i> Envoyer rappels
                        </button>
                        <button onclick="planifierVisites()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-calendar-plus mr-2"></i> Planifier visites
                        </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.alertes.assiduite-faible') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nom, prénom, email..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type d'alerte</label>
                        <select name="type_alerte"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="both" {{ $typeAlerte === 'both' ? 'selected' : '' }}>Toutes les alertes</option>
                            <option value="dimanches_successifs" {{ $typeAlerte === 'dimanches_successifs' ? 'selected' : '' }}>Dimanches successifs</option>
                            <option value="cultes_mensuels" {{ $typeAlerte === 'cultes_mensuels' ? 'selected' : '' }}>Cultes mensuels</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Sévérité</label>
                        <select name="severite"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Toutes les sévérités</option>
                            <option value="critique" {{ request('severite') === 'critique' ? 'selected' : '' }}>Critique</option>
                            <option value="attention" {{ request('severite') === 'attention' ? 'selected' : '' }}>Attention</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Période (mois)</label>
                        <select name="periode_mois"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="1" {{ $periodeMois == 1 ? 'selected' : '' }}>1 mois</option>
                            <option value="2" {{ $periodeMois == 2 ? 'selected' : '' }}>2 mois</option>
                            <option value="3" {{ $periodeMois == 3 ? 'selected' : '' }}>3 mois</option>
                            <option value="6" {{ $periodeMois == 6 ? 'selected' : '' }}>6 mois</option>
                        </select>
                    </div>

                    <div class="lg:col-span-4 flex gap-2 pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i> Rechercher
                        </button>
                        <a href="{{ route('private.alertes.assiduite-faible') }}"
                            class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-refresh mr-2"></i> Réinitialiser
                        </a>
                    </div>
                </form>
        </div>
    </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-slate-500 to-gray-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $statistiques['total_membres'] ?? 0 }}</p>
                        <p class="text-sm text-slate-500">Membres analysés</p>
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
                        <p class="text-2xl font-bold text-slate-800">{{ count($membres) }}</p>
                        <p class="text-sm text-slate-500">En alerte</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-calendar-times text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ count($statistiques['dimanches_successifs'] ?? []) }}</p>
                        <p class="text-sm text-slate-500">Dimanches manqués</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ count($statistiques['cultes_mensuels'] ?? []) }}</p>
                        <p class="text-sm text-slate-500">Faible mensuel</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ count($statistiques['critique'] ?? []) }}</p>
                        <p class="text-sm text-slate-500">Situation critique</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des membres en alerte -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-users text-red-600 mr-2"></i>
                        Membres nécessitant un suivi ({{ count($membres) }})
                    </h2>

                    <div class="flex items-center space-x-4">
                        <!-- Toggle Vue Liste/Grille -->
                        <div class="flex bg-slate-100 rounded-lg p-1">
                            <button id="listViewBtn"
                                    class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 bg-blue-600 text-white">
                                <i class="fas fa-list mr-2"></i>Liste
                            </button>
                            <button id="gridViewBtn"
                                    class="flex items-center px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-slate-600 hover:text-slate-900">
                                <i class="fas fa-th-large mr-2"></i>Grille
                            </button>
                        </div>

                        <!-- Informations d'état -->
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                {{ count($membres) }} membres en alerte
                            </span>
                            <span class="text-sm text-slate-600">
                                {{ $dateAnalyse->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if (count($membres) > 0)
                    <!-- Vue Liste (affichée par défaut) -->
                    <div id="listView" class="space-y-4">
                        @foreach ($membres as $membre)
                            <div class="bg-gradient-to-r from-white to-slate-50 rounded-xl border border-slate-200 p-4 hover:shadow-lg transition-all duration-300
                                @if($membre['severite'] === 'critique') border-l-4 border-l-red-500
                                @elseif($membre['severite'] === 'attention') border-l-4 border-l-orange-500
                                @else border-l-4 border-l-yellow-500 @endif"
                                data-membre-id="{{ $membre['membre']['id'] }}">

                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                    <!-- Informations principales -->
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                        <!-- Membre et Contact -->
                                        <div class="space-y-1">
                                            <div class="font-bold text-slate-900">
                                                {{ $membre['membre']['nom_complet'] }}
                                            </div>
                                            @if($membre['membre']['email'])
                                                <div class="text-sm text-slate-600 flex items-center">
                                                    <i class="fas fa-envelope w-4 mr-1 text-blue-500"></i>
                                                    {{ $membre['membre']['email'] }}
                                                </div>
                                            @endif
                                            @if($membre['membre']['telephone'])
                                                <div class="text-sm text-slate-600 flex items-center">
                                                    <i class="fas fa-phone w-4 mr-1 text-green-500"></i>
                                                    {{ $membre['membre']['telephone'] }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Statut et Score -->
                                        <div class="space-y-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($membre['severite'] === 'critique') bg-red-100 text-red-800
                                                @elseif($membre['severite'] === 'attention') bg-orange-100 text-orange-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                @if($membre['severite'] === 'critique')
                                                    <i class="fas fa-exclamation-circle mr-1"></i> Critique
                                                @elseif($membre['severite'] === 'attention')
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Attention
                                                @else
                                                    <i class="fas fa-info-circle mr-1"></i> Normal
                                                @endif
                                            </span>
                                            <div class="text-sm">
                                                <span class="font-medium
                                                    @if($membre['score_assiduite'] >= 70) text-green-600
                                                    @elseif($membre['score_assiduite'] >= 40) text-yellow-600
                                                    @else text-red-600 @endif">
                                                    Assiduité : {{ $membre['score_assiduite'] }}%
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Alertes -->
                                        <div class="space-y-1">
                                            @foreach($membre['alertes'] as $alerte)
                                                <div class="text-xs">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md font-medium
                                                        @if($alerte['severite'] === 'critique') bg-red-50 text-red-800 border border-red-200
                                                        @else bg-orange-50 text-orange-800 border border-orange-200 @endif">
                                                        <i class="fas
                                                            @if($alerte['type'] === 'dimanches_successifs') fa-calendar-times
                                                            @else fa-chart-line @endif mr-1"></i>
                                                        {{ ucfirst(str_replace('_', ' ', $alerte['type'])) }}
                                                    </span>
                                                    <p class="text-slate-600 mt-1">{{ $alerte['description'] }}</p>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Dernier culte -->
                                        <div class="space-y-1">
                                            @if($membre['dernier_culte'])
                                                <div class="text-sm text-slate-600">
                                                    <i class="fas fa-calendar-alt w-4 mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($membre['dernier_culte']['date'])->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    Il y a {{ $membre['dernier_culte']['jours_depuis'] }} jours
                                                </div>
                                            @else
                                                <div class="text-sm text-red-600">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    Aucune participation
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex flex-wrap lg:flex-col gap-2 lg:w-48">
                                        <button onclick="contactMembre('{{ $membre['membre']['id'] }}')"
                                            class="flex-1 lg:w-full inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-phone mr-2"></i> Contacter
                                        </button>
                                        <button onclick="planifierVisite('{{ $membre['membre']['id'] }}')"
                                            class="flex-1 lg:w-full inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                            <i class="fas fa-calendar-plus mr-2"></i> Planifier
                                        </button>
                                        <button onclick="ajouterNote('{{ $membre['membre']['id'] }}')"
                                            class="flex-1 lg:w-full inline-flex items-center justify-center px-3 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                            <i class="fas fa-sticky-note mr-2"></i> Note
                                        </button>
                                        <button onclick="marquerSuivi('{{ $membre['membre']['id'] }}')"
                                            class="flex-1 lg:w-full inline-flex items-center justify-center px-3 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                            <i class="fas fa-check mr-2"></i> Marquer suivi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Vue Grille (masquée par défaut) -->
                    <div id="gridView" class="hidden grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach ($membres as $membre)
                            <div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1
                                @if($membre['severite'] === 'critique') border-l-4 border-l-red-500
                                @elseif($membre['severite'] === 'attention') border-l-4 border-l-orange-500
                                @else border-l-4 border-l-yellow-500 @endif"
                                data-membre-id="{{ $membre['membre']['id'] }}">

                                <!-- Header -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                                            {{ $membre['membre']['nom_complet'] }}
                                        </h3>
                                        <div class="space-y-1 text-sm text-slate-600">
                                            @if($membre['membre']['email'])
                                                <div class="flex items-center">
                                                    <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                                    {{ $membre['membre']['email'] }}
                                                </div>
                                            @endif
                                            @if($membre['membre']['telephone'])
                                                <div class="flex items-center">
                                                    <i class="fas fa-phone mr-2 text-green-500"></i>
                                                    {{ $membre['membre']['telephone'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-end space-y-2">
                                        <!-- Badge de sévérité -->
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($membre['severite'] === 'critique') bg-red-100 text-red-800
                                            @elseif($membre['severite'] === 'attention') bg-orange-100 text-orange-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            @if($membre['severite'] === 'critique')
                                                <i class="fas fa-exclamation-circle mr-1"></i> Critique
                                            @elseif($membre['severite'] === 'attention')
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Attention
                                            @else
                                                <i class="fas fa-info-circle mr-1"></i> Normal
                                            @endif
                                        </span>

                                        <!-- Score d'assiduité -->
                                        <div class="text-center">
                                            <div class="text-xl font-bold
                                                @if($membre['score_assiduite'] >= 70) text-green-600
                                                @elseif($membre['score_assiduite'] >= 40) text-yellow-600
                                                @else text-red-600 @endif">
                                                {{ $membre['score_assiduite'] }}%
                                            </div>
                                            <div class="text-xs text-slate-500">Assiduité</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alertes détaillées -->
                                <div class="space-y-2 mb-4">
                                    @foreach($membre['alertes'] as $alerte)
                                        <div class="bg-white rounded-lg p-3 border-l-4
                                            @if($alerte['severite'] === 'critique') border-red-400 bg-red-50
                                            @else border-orange-400 bg-orange-50 @endif">
                                            <div class="flex items-center justify-between mb-1">
                                                <div class="flex items-center">
                                                    <i class="fas
                                                        @if($alerte['type'] === 'dimanches_successifs') fa-calendar-times text-red-500
                                                        @else fa-chart-line text-orange-500 @endif mr-2"></i>
                                                    <span class="font-medium text-slate-800 text-sm">
                                                        {{ ucfirst(str_replace('_', ' ', $alerte['type'])) }}
                                                    </span>
                                                </div>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                    @if($alerte['severite'] === 'critique') bg-red-100 text-red-700
                                                    @else bg-orange-100 text-orange-700 @endif">
                                                    {{ ucfirst($alerte['severite']) }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-600">{{ $alerte['description'] }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Dernier culte -->
                                @if($membre['dernier_culte'])
                                    <div class="bg-slate-100 rounded-lg p-3 mb-4">
                                        <div class="text-sm">
                                            <div class="flex justify-between items-center">
                                                <span class="font-medium text-slate-700">Dernier culte :</span>
                                                <span class="text-slate-600">{{ \Carbon\Carbon::parse($membre['dernier_culte']['date'])->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="text-xs text-slate-500 mt-1">
                                                Il y a {{ $membre['dernier_culte']['jours_depuis'] }} jours
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-red-100 rounded-lg p-3 mb-4">
                                        <div class="flex items-center text-sm text-red-700">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            Aucune participation enregistrée
                                        </div>
                                    </div>
                                @endif

                                <!-- Actions -->
                                <div class="grid grid-cols-2 gap-2">
                                    <button onclick="contactMembre('{{ $membre['membre']['id'] }}')"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-phone mr-1"></i> Contact
                                    </button>
                                    <button onclick="planifierVisite('{{ $membre['membre']['id'] }}')"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-calendar-plus mr-1"></i> Visite
                                    </button>
                                    <button onclick="ajouterNote('{{ $membre['membre']['id'] }}')"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-purple-600 text-white text-xs font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                        <i class="fas fa-sticky-note mr-1"></i> Note
                                    </button>
                                    <button onclick="marquerSuivi('{{ $membre['membre']['id'] }}')"
                                        class="inline-flex items-center justify-center px-3 py-2 bg-orange-600 text-white text-xs font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                        <i class="fas fa-check mr-1"></i> Suivi
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- État vide -->
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-3xl text-green-500"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune alerte d'assiduité</h3>
                        <p class="text-slate-500 mb-6">
                            @if ($typeAlerte !== 'both' || $periodeMois !== 1)
                                Aucun membre ne correspond aux critères sélectionnés.
                            @else
                                Tous les membres ont une assiduité satisfaisante !
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>


        @push('scripts')
            <script>
                // Variables globales
                let currentMembreId = null;

                // =============================================
                // GESTION DES VUES LISTE/GRILLE
                // =============================================
                document.addEventListener('DOMContentLoaded', function() {
                    const listViewBtn = document.getElementById('listViewBtn');
                    const gridViewBtn = document.getElementById('gridViewBtn');
                    const listView = document.getElementById('listView');
                    const gridView = document.getElementById('gridView');

                    // Récupérer la préférence sauvegardée
                    let currentView = localStorage.getItem('alertesView') || 'list';
                    setView(currentView);

                    // Événements des boutons
                    listViewBtn.addEventListener('click', () => {
                        setView('list');
                        localStorage.setItem('alertesView', 'list');
                    });

                    gridViewBtn.addEventListener('click', () => {
                        setView('grid');
                        localStorage.setItem('alertesView', 'grid');
                    });

                    function setView(view) {
                        if (view === 'list') {
                            listViewBtn.className = 'flex items-center px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 bg-blue-600 text-white';
                            gridViewBtn.className = 'flex items-center px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-slate-600 hover:text-slate-900';
                            listView.classList.remove('hidden');
                            gridView.classList.add('hidden');
                        } else {
                            gridViewBtn.className = 'flex items-center px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 bg-blue-600 text-white';
                            listViewBtn.className = 'flex items-center px-3 py-2 rounded-md text-sm font-medium transition-all duration-200 text-slate-600 hover:text-slate-900';
                            gridView.classList.remove('hidden');
                            listView.classList.add('hidden');
                        }
                    }
                });

                // =============================================
                // FONCTIONS DE CONTACT
                // =============================================
                function contactMembre(membreId) {
                    currentMembreId = membreId;
                    showContactModal();
                }

                function showContactModal() {
                    const modalHtml = `
                        <div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                                <div class="text-center mb-6">
                                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-phone text-white text-2xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800">Contacter le membre</h3>
                                    <p class="text-slate-500 text-sm mt-1">Choisissez le moyen de contact</p>
                                </div>

                                <div class="space-y-3 mb-6">
                                    <button onclick="initiateCall()"
                                        class="w-full flex items-center p-4 border-2 border-slate-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-phone text-green-600"></i>
                                        </div>
                                        <div class="text-left">
                                            <div class="font-semibold text-slate-800">Appel téléphonique</div>
                                            <div class="text-sm text-slate-500">Contact direct immédiat</div>
                                        </div>
                                    </button>

                                    <button onclick="sendSMS()"
                                        class="w-full flex items-center p-4 border-2 border-slate-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-sms text-blue-600"></i>
                                        </div>
                                        <div class="text-left">
                                            <div class="font-semibold text-slate-800">SMS</div>
                                            <div class="text-sm text-slate-500">Message de rappel personnalisé</div>
                                        </div>
                                    </button>

                                    <button onclick="sendEmail()"
                                        class="w-full flex items-center p-4 border-2 border-slate-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-envelope text-purple-600"></i>
                                        </div>
                                        <div class="text-left">
                                            <div class="font-semibold text-slate-800">Email</div>
                                            <div class="text-sm text-slate-500">Email de suivi pastoral</div>
                                        </div>
                                    </button>
                                </div>

                                <button onclick="closeModal('contactModal')"
                                    class="w-full px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                                    Annuler
                                </button>
                            </div>
                        </div>
                    `;
                    document.body.insertAdjacentHTML('beforeend', modalHtml);
                }

                function initiateCall() {
                    // Logique d'appel
                    showNotification('Initiation de l\'appel...', 'info');
                    closeModal('contactModal');

                    // Ici vous pourriez faire un appel API pour récupérer le numéro et initier l'appel
                    fetch(`/api/membres/${currentMembreId}/contact/call`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.phone_number) {
                            window.location.href = `tel:${data.phone_number}`;
                            showNotification('Appel initié', 'success');
                        } else {
                            showNotification('Numéro non disponible', 'error');
                        }
                    })
                    .catch(() => showNotification('Erreur lors de l\'initiation de l\'appel', 'error'));
                }

                function sendSMS() {
                    const message = prompt("Message SMS à envoyer:",
                        "Bonjour, nous avons remarqué votre absence récente aux cultes. N'hésitez pas à nous contacter si vous avez besoin d'aide. L'équipe pastorale.");

                    if (message) {
                        fetch(`/api/membres/${currentMembreId}/contact/sms`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ message })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showNotification('SMS envoyé avec succès', 'success');
                            } else {
                                showNotification('Erreur lors de l\'envoi du SMS', 'error');
                            }
                        })
                        .catch(() => showNotification('Erreur lors de l\'envoi du SMS', 'error'));
                    }
                    closeModal('contactModal');
                }

                function sendEmail() {
                    window.location.href = `/membres/${currentMembreId}/send-email?context=assiduite_alerte`;
                    closeModal('contactModal');
                }

                // =============================================
                // FONCTION PLANIFIER VISITE
                // =============================================
                function planifierVisite(membreId) {
                    currentMembreId = membreId;
                    showVisiteModal();
                }

                function showVisiteModal() {
                    const modalHtml = `
                        <div id="visiteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4">
                                <div class="text-center mb-6">
                                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-calendar-plus text-white text-2xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800">Planifier une visite</h3>
                                    <p class="text-slate-500 text-sm mt-1">Organiser un suivi pastoral</p>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Date de visite</label>
                                        <input type="date" id="dateVisite"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            min="${new Date().toISOString().split('T')[0]}" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Heure</label>
                                        <input type="time" id="heureVisite" value="14:00"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Responsable de la visite</label>
                                        <select id="responsableVisite"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="">Sélectionner un responsable</option>
                                            <option value="pasteur">Pasteur principal</option>
                                            <option value="assistant">Assistant pastoral</option>
                                            <option value="diacre">Diacre</option>
                                            <option value="autre">Autre membre</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Objectif de la visite</label>
                                        <select id="objectifVisite"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="">Sélectionner un objectif</option>
                                            <option value="suivi_assiduite">Suivi d'assiduité</option>
                                            <option value="encouragement">Encouragement</option>
                                            <option value="priere">Temps de prière</option>
                                            <option value="conseil">Conseil pastoral</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                                        <textarea id="notesVisite" rows="3"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Notes additionnelles..."></textarea>
                                    </div>
                                </div>

                                <div class="flex gap-3 mt-6">
                                    <button onclick="closeModal('visiteModal')"
                                        class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                                        Annuler
                                    </button>
                                    <button onclick="confirmerVisite()"
                                        class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-xl hover:from-green-700 hover:to-blue-700 transition-all duration-200 font-medium">
                                        Planifier
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.insertAdjacentHTML('beforeend', modalHtml);
                }

                function confirmerVisite() {
                    const dateVisite = document.getElementById('dateVisite').value;
                    const heureVisite = document.getElementById('heureVisite').value;
                    const responsable = document.getElementById('responsableVisite').value;
                    const objectif = document.getElementById('objectifVisite').value;
                    const notes = document.getElementById('notesVisite').value;

                    if (!dateVisite || !heureVisite || !responsable || !objectif) {
                        showNotification('Veuillez remplir tous les champs obligatoires', 'error');
                        return;
                    }

                    const visiteData = {
                        membre_id: currentMembreId,
                        date_visite: dateVisite,
                        heure_visite: heureVisite,
                        responsable: responsable,
                        objectif: objectif,
                        notes: notes
                    };

                    fetch('/api/visites-pastorales', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(visiteData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Visite planifiée avec succès', 'success');
                            closeModal('visiteModal');
                        } else {
                            showNotification(data.message || 'Erreur lors de la planification', 'error');
                        }
                    })
                    .catch(() => showNotification('Erreur lors de la planification de la visite', 'error'));
                }

                // =============================================
                // FONCTION AJOUTER NOTE
                // =============================================
                function ajouterNote(membreId) {
                    currentMembreId = membreId;
                    showNoteModal();
                }

                function showNoteModal() {
                    const modalHtml = `
                        <div id="noteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4">
                                <div class="text-center mb-6">
                                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-sticky-note text-white text-2xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800">Ajouter une note</h3>
                                    <p class="text-slate-500 text-sm mt-1">Note de suivi pastoral</p>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Type de note</label>
                                        <select id="typeNote"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                            <option value="">Sélectionner un type</option>
                                            <option value="suivi_assiduite">Suivi d'assiduité</option>
                                            <option value="contact_telephonique">Contact téléphonique</option>
                                            <option value="visite_domicile">Visite à domicile</option>
                                            <option value="conseil_pastoral">Conseil pastoral</option>
                                            <option value="priere_specifique">Demande de prière spécifique</option>
                                            <option value="situation_personnelle">Situation personnelle</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                                        <select id="prioriteNote"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="normale">Normale</option>
                                            <option value="importante">Importante</option>
                                            <option value="urgente">Urgente</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Contenu de la note *</label>
                                        <textarea id="contenuNote" rows="5"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Détaillez votre observation ou action entreprise..." required></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Action de suivi requise ?</label>
                                        <div class="flex items-center space-x-4">
                                            <label class="flex items-center">
                                                <input type="radio" name="suiviRequis" value="oui" class="mr-2">
                                                <span class="text-sm">Oui</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="suiviRequis" value="non" class="mr-2" checked>
                                                <span class="text-sm">Non</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div id="actionSuivi" class="hidden">
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Date limite pour le suivi</label>
                                        <input type="date" id="dateLimiteSuivi"
                                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            min="${new Date().toISOString().split('T')[0]}">
                                    </div>
                                </div>

                                <div class="flex gap-3 mt-6">
                                    <button onclick="closeModal('noteModal')"
                                        class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                                        Annuler
                                    </button>
                                    <button onclick="sauvegarderNote()"
                                        class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 font-medium">
                                        Sauvegarder
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.insertAdjacentHTML('beforeend', modalHtml);

                    // Gestion du suivi conditionnel
                    document.querySelectorAll('input[name="suiviRequis"]').forEach(radio => {
                        radio.addEventListener('change', function() {
                            const actionDiv = document.getElementById('actionSuivi');
                            if (this.value === 'oui') {
                                actionDiv.classList.remove('hidden');
                            } else {
                                actionDiv.classList.add('hidden');
                            }
                        });
                    });
                }

                function sauvegarderNote() {
                    const type = document.getElementById('typeNote').value;
                    const contenu = document.getElementById('contenuNote').value.trim();

                    if (!type || !contenu) {
                        showNotification('Le type et le contenu de la note sont requis', 'error');
                        return;
                    }

                    const noteData = {
                        membre_id: currentMembreId,
                        type: type,
                        priorite: document.getElementById('prioriteNote').value,
                        contenu: contenu,
                        suivi_requis: document.querySelector('input[name="suiviRequis"]:checked').value === 'oui',
                        date_limite_suivi: document.getElementById('dateLimiteSuivi').value || null
                    };

                    fetch('/api/notes-pastorales', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(noteData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Note ajoutée avec succès', 'success');
                            closeModal('noteModal');
                        } else {
                            showNotification(data.message || 'Erreur lors de la sauvegarde', 'error');
                        }
                    })
                    .catch(() => showNotification('Erreur lors de la sauvegarde de la note', 'error'));
                }

                // =============================================
                // FONCTION MARQUER SUIVI
                // =============================================
                function marquerSuivi(membreId) {
                    if (!confirm('Marquer ce membre comme ayant été suivi ?')) return;

                    fetch(`/api/membres/${membreId}/marquer-suivi`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            date_suivi: new Date().toISOString(),
                            type_suivi: 'alerte_assiduite'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Membre marqué comme suivi', 'success');
                            // Marquer visuellement le membre
                            const membreCard = document.querySelector(`[data-membre-id="${membreId}"]`);
                            if (membreCard) {
                                membreCard.style.opacity = '0.7';
                                membreCard.style.position = 'relative';
                                const badge = document.createElement('div');
                                badge.className = 'absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium z-10';
                                badge.textContent = 'Suivi effectué';
                                membreCard.appendChild(badge);
                            }
                        } else {
                            showNotification(data.message || 'Erreur lors du marquage', 'error');
                        }
                    })
                    .catch(() => showNotification('Erreur lors du marquage du suivi', 'error'));
                }

                // =============================================
                // FONCTIONS D'EXPORT ET ACTIONS EN MASSE
                // =============================================
                function exporterAlertes() {
                    showExportModal();
                }

                function showExportModal() {
                    const modalHtml = `
                        <div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                                <div class="text-center mb-6">
                                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-download text-white text-2xl"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800">Exporter les alertes</h3>
                                    <p class="text-slate-500 text-sm mt-1">Choisissez le format d'export</p>
                                </div>

                                <div class="space-y-3 mb-6">
                                    <button onclick="exportFormat('pdf')"
                                        class="w-full flex items-center p-4 border-2 border-slate-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-file-pdf text-red-600"></i>
                                        </div>
                                        <div class="text-left">
                                            <div class="font-semibold text-slate-800">PDF</div>
                                            <div class="text-sm text-slate-500">Rapport complet imprimable</div>
                                        </div>
                                    </button>

                                    <button onclick="exportFormat('excel')"
                                        class="w-full flex items-center p-4 border-2 border-slate-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-file-excel text-green-600"></i>
                                        </div>
                                        <div class="text-left">
                                            <div class="font-semibold text-slate-800">Excel</div>
                                            <div class="text-sm text-slate-500">Données pour analyse</div>
                                        </div>
                                    </button>
                                </div>

                                <button onclick="closeModal('exportModal')"
                                    class="w-full px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                                    Annuler
                                </button>
                            </div>
                        </div>
                    `;
                    document.body.insertAdjacentHTML('beforeend', modalHtml);
                }

                function exportFormat(format) {
                    showNotification('Export en cours...', 'info');
                    const params = new URLSearchParams(window.location.search);
                    params.set('format', format);
                    window.location.href = `/alertes/export?${params.toString()}`;
                    closeModal('exportModal');
                }

                function envoyerRappels() {
                    if (!confirm('Envoyer des rappels à tous les membres en alerte ?')) return;

                    showNotification('Envoi des rappels en cours...', 'info');

                    fetch('/api/alertes/envoyer-rappels', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            severite: 'critique',
                            date_debut: new Date(Date.now() + 24*60*60*1000).toISOString().split('T')[0], // Demain
                            responsable_defaut: 'pasteur'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(`${data.count || 0} visites planifiées`, 'success');
                        } else {
                            showNotification(data.message || 'Erreur lors de la planification', 'error');
                        }
                    })
                    .catch(() => showNotification('Erreur lors de la planification des visites', 'error'));
                }

                // =============================================
                // FONCTIONS UTILITAIRES
                // =============================================
                function closeModal(modalId) {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.remove();
                    }
                }

                function showNotification(message, type = 'info') {
                    const colors = {
                        success: 'bg-green-500',
                        error: 'bg-red-500',
                        warning: 'bg-yellow-500',
                        info: 'bg-blue-500'
                    };

                    const icons = {
                        success: 'fa-check-circle',
                        error: 'fa-exclamation-circle',
                        warning: 'fa-exclamation-triangle',
                        info: 'fa-info-circle'
                    };

                    const notification = document.createElement('div');
                    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full z-50`;
                    notification.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <i class="fas ${icons[type]}"></i>
                            <span class="font-medium">${message}</span>
                        </div>
                    `;

                    document.body.appendChild(notification);

                    // Animation d'entrée
                    setTimeout(() => notification.classList.remove('translate-x-full'), 100);

                    // Suppression automatique après 5 secondes
                    setTimeout(() => {
                        notification.classList.add('translate-x-full');
                        setTimeout(() => {
                            if (notification.parentNode) {
                                notification.parentNode.removeChild(notification);
                            }
                        }, 300);
                    }, 5000);
                }

                // =============================================
                // GESTION DES ÉVÉNEMENTS
                // =============================================

                // Fermeture des modales avec Escape
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        const modals = ['contactModal', 'visiteModal', 'noteModal', 'exportModal'];
                        modals.forEach(modalId => {
                            const modal = document.getElementById(modalId);
                            if (modal) closeModal(modalId);
                        });
                    }
                });

                // Fermeture des modales en cliquant à l'extérieur
                document.addEventListener('click', function(event) {
                    const modals = ['contactModal', 'visiteModal', 'noteModal', 'exportModal'];
                    modals.forEach(modalId => {
                        const modal = document.getElementById(modalId);
                        if (modal && event.target === modal) {
                            closeModal(modalId);
                        }
                    });
                });

                // Animation des cartes au chargement
                document.addEventListener('DOMContentLoaded', function() {
                    // Animation des statistiques
                    const statsCards = document.querySelectorAll('.bg-white\\/80');
                    statsCards.forEach((card, index) => {
                        card.style.opacity = '0';
                        setTimeout(() => {
                            card.style.transition = 'all 0.5s ease';
                            card.style.opacity = '1';
                        }, index * 100);
                    });

                    // Animation des cartes membres
                    const memberCards = document.querySelectorAll('[data-membre-id]');
                    memberCards.forEach((card, index) => {
                        card.style.opacity = '0';
                        card.style.transform = 'translateX(-20px)';
                        setTimeout(() => {
                            card.style.transition = 'all 0.3s ease';
                            card.style.opacity = '1';
                            card.style.transform = 'translateX(0)';
                        }, (index * 50) + 500); // Délai après les statistiques
                    });

                    // Recherche en temps réel (optionnel)
                    const searchInput = document.querySelector('input[name="search"]');
                    if (searchInput) {
                        let searchTimeout;
                        searchInput.addEventListener('input', function() {
                            clearTimeout(searchTimeout);
                            searchTimeout = setTimeout(() => {
                                // Ici vous pourriez implémenter une recherche côté client
                                console.log('Recherche:', this.value);
                            }, 500);
                        });
                    }
                });

                // =============================================
                // GESTION DES ERREURS GLOBALES
                // =============================================
                window.addEventListener('error', function(event) {
                    console.error('Erreur JavaScript:', event.error);
                    showNotification('Une erreur s\'est produite. Veuillez actualiser la page.', 'error');
                });

                window.addEventListener('unhandledrejection', function(event) {
                    console.error('Promesse rejetée:', event.reason);
                    showNotification('Erreur de connexion. Vérifiez votre connexion internet.', 'error');
                });

                // =============================================
                // INITIALISATION
                // =============================================
                console.log('Système d\'alertes d\'assiduité initialisé');
                console.log('Fonctions disponibles: contactMembre, planifierVisite, ajouterNote, marquerSuivi, exporterAlertes');
            </script>
        @endpush
    </div>

@endsection
