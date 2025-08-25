@extends('layouts.private.main')
@section('title', 'Détails du Projet')

@section('content')
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                {{ $projet->nom_projet }}</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.projets.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-project-diagram mr-2"></i>
                            Projets
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-medium text-slate-500">{{ $projet->code_projet }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <p class="text-slate-500 mt-1">{{ $projet->type_projet_libelle }} • {{ $projet->categorie }} • Créé le
                {{ $projet->created_at->format('d/m/Y') }}</p>
        </div>

        <!-- Workflow de progression du projet -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-route text-purple-600 mr-2"></i>
                    Suivi de Progression
                </h2>
            </div>
            <div class="p-6">
                <!-- Workflow visuel -->
                <div class="relative">
                    <div class="flex items-center justify-between mb-8">
                        @php
                            $etapes = [
                                'conception' => [
                                    'label' => 'Conception',
                                    'icon' => 'fas fa-lightbulb',
                                    'color' => 'gray',
                                ],
                                'planification' => [
                                    'label' => 'Planification',
                                    'icon' => 'fas fa-calendar-check',
                                    'color' => 'blue',
                                ],
                                'recherche_financement' => [
                                    'label' => 'Financement',
                                    'icon' => 'fas fa-search-dollar',
                                    'color' => 'yellow',
                                ],
                                'en_attente' => [
                                    'label' => 'En attente',
                                    'icon' => 'fas fa-clock',
                                    'color' => 'orange',
                                ],
                                'en_cours' => ['label' => 'En cours', 'icon' => 'fas fa-play', 'color' => 'green'],
                                'termine' => [
                                    'label' => 'Terminé',
                                    'icon' => 'fas fa-flag-checkered',
                                    'color' => 'emerald',
                                ],
                            ];

                            $etapesOrdered = array_keys($etapes);
                            $currentIndex = array_search($projet->statut, $etapesOrdered);

                            // Gestion spéciale : si le projet est suspendu ou annulé, on garde l'index courant
if (in_array($projet->statut, ['suspendu', 'annule'])) {
    $currentIndex = array_search($projet->statut_precedent ?? 'conception', $etapesOrdered);
                            }
                        @endphp

                        @foreach ($etapes as $statutKey => $etape)
                            @php
                                $index = array_search($statutKey, $etapesOrdered);
                                $isActive = $projet->statut === $statutKey;
                                $isCompleted = $index < $currentIndex;
                                $isDisabled = $projet->statut === 'suspendu' || $projet->statut === 'annule';
                            @endphp

                            <div class="flex flex-col items-center relative">
                                <!-- Cercle de statut -->
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center mb-2 transition-all duration-300 {{ $isActive ? 'bg-' . $etape['color'] . '-500 text-white scale-110 shadow-lg' : ($isCompleted ? 'bg-' . $etape['color'] . '-500 text-white' : 'bg-slate-200 text-slate-400') }} {{ $isDisabled ? 'opacity-50' : '' }}">
                                    <i class="{{ $etape['icon'] }} {{ $isActive ? 'text-lg' : 'text-sm' }}"></i>
                                </div>

                                <!-- Label -->
                                <span
                                    class="text-xs font-medium text-center {{ $isActive ? 'text-' . $etape['color'] . '-600 font-bold' : 'text-slate-500' }}">
                                    {{ $etape['label'] }}
                                </span>

                                <!-- Ligne de connexion -->
                                @if (!$loop->last)
                                    <div
                                        class="absolute top-6 left-full w-full h-0.5 {{ $isCompleted ? 'bg-green-500' : 'bg-slate-300' }} transform translate-x-2 -translate-y-1/2 z-0">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Indicateurs spéciaux -->
                    @if ($projet->statut === 'suspendu')
                        <div class="flex items-center justify-center mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                <i class="fas fa-pause mr-2"></i>
                                Projet suspendu
                            </span>
                        </div>
                    @elseif($projet->statut === 'annule')
                        <div class="flex items-center justify-center mb-4">
                            <span
                                class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                <i class="fas fa-times mr-2"></i>
                                Projet annulé
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Actions de progression -->
                <div class="mt-6 p-4 bg-slate-50 rounded-xl">
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions disponibles</h3>
                    {{-- Messages d'aide contextuelle --}}
                    @if (!$projet->necessiteAction())
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex">
                                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                                <div class="text-blue-800 text-sm">
                                    @if ($projet->statut === 'termine')
                                        Ce projet est terminé. Aucune action supplémentaire n'est requise.
                                    @elseif($projet->statut === 'annule')
                                        Ce projet a été annulé.
                                    @elseif($projet->statut === 'archive')
                                        Ce projet est archivé.
                                    @else
                                        Toutes les étapes nécessaires ont été complétées pour ce statut.
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif


                    {{-- Messages d'aide pour les étapes suivantes --}}
                    @if ($projet->statut === 'conception' && !$projet->est_approuve)
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-2"></i>
                                <div class="text-yellow-800 text-sm">
                                    Ce projet nécessite une approbation avant de pouvoir passer à l'étape suivante.
                                </div>
                            </div>
                        </div>
                    @elseif($projet->statut === 'recherche_financement')
                        <div class="mb-4 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                            <div class="flex">
                                <i class="fas fa-search-dollar text-orange-500 mt-0.5 mr-2"></i>
                                <div class="text-orange-800 text-sm">
                                    <strong>Financement requis:</strong>
                                    {{ number_format($projet->budget_minimum ?? $projet->budget_prevu, 0, ',', ' ') }}
                                    {{ $projet->devise }}
                                    <br>
                                    <strong>Actuellement collecté:</strong>
                                    {{ number_format($projet->budget_collecte, 0, ',', ' ') }} {{ $projet->devise }}
                                    ({{ $projet->pourcentage_financement }}%)
                                </div>
                            </div>
                        </div>
                    @endif




                    <div class="flex flex-wrap gap-2">
                        {{-- APPROBATION --}}
                        @if ($projet->peutEtreApprouve())
                            <button type="button" onclick="approveProject()"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                <i class="fas fa-check mr-2"></i> Approuver
                            </button>
                        @endif

                        {{-- PLANIFICATION --}}
                        @if ($projet->peutEtrePlanifie())
                            <button type="button" onclick="planifyProject()"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                                <i class="fas fa-calendar-check mr-2"></i> Planifier
                            </button>
                        @endif

                        {{-- RECHERCHE FINANCEMENT --}}
                        @if ($projet->peutEtreEnRechercheFinancement())
                            <button type="button" onclick="searchFunding()"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors shadow-sm">
                                <i class="fas fa-search-dollar mr-2"></i> Rechercher financement
                            </button>
                        @endif

                        {{-- METTRE EN ATTENTE --}}
                        @if ($projet->peutEtreEnAttente())
                            <button type="button" onclick="putOnHold()"
                                class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors shadow-sm">
                                <i class="fas fa-clock mr-2"></i> Mettre en attente
                            </button>
                        @endif

                        {{-- DÉMARRER --}}
                        @if ($projet->peutEtreDemarre())
                            <button type="button" onclick="startProject()"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="fas fa-play mr-2"></i> Démarrer
                            </button>
                        @endif

                        {{-- METTRE À JOUR PROGRESSION --}}
                        @if ($projet->statut === 'en_cours')
                            <button type="button" onclick="openProgressModal()"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                                <i class="fas fa-chart-line mr-2"></i> Mettre à jour progression
                            </button>
                        @endif

                        {{-- TERMINER --}}
                        @if ($projet->peutEtreTermine())
                            <button type="button" onclick="openCompleteModal()"
                                class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                                <i class="fas fa-flag-checkered mr-2"></i> Terminer
                            </button>
                        @endif

                        {{-- SUSPENDRE --}}
                        @if ($projet->peutEtreSuspendu())
                            <button type="button" onclick="suspendProject()"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                <i class="fas fa-pause mr-2"></i> Suspendre
                            </button>
                        @endif

                        {{-- REPRENDRE --}}
                        @if ($projet->peutEtreRepris())
                            <button type="button" onclick="resumeProject()"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                <i class="fas fa-play mr-2"></i> Reprendre
                            </button>
                        @endif

                        {{-- ACTIONS TOUJOURS DISPONIBLES --}}
                        @can('projets.update')
                            <a href="{{ route('private.projets.edit', $projet) }}"
                                class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors shadow-sm">
                                <i class="fas fa-edit mr-2"></i> Modifier
                            </a>
                        @endcan

                        <button type="button" onclick="openDuplicateModal()"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                            <i class="fas fa-copy mr-2"></i> Dupliquer
                        </button>

                        {{-- ANNULER (si possible) --}}
                        @if ($projet->peutEtreAnnule())
                            <button type="button" onclick="cancelProject()"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                <i class="fas fa-times mr-2"></i> Annuler projet
                            </button>
                        @endif
                    </div>


                    {{-- Workflow suggéré --}}
                    @php $prochaineAction = $projet->getProchainePossibleAction(); @endphp
                    @if ($prochaineAction)
                        <div class="mt-3 p-2 bg-blue-50 rounded text-xs text-blue-700">
                            <i class="fas fa-lightbulb mr-1"></i>
                            <strong>Prochaine étape recommandée:</strong>
                            @switch($prochaineAction)
                                @case('approuver')
                                    Approuver le projet
                                @break

                                @case('planifier')
                                    Planifier le projet
                                @break

                                @case('rechercher_financement')
                                    Rechercher le financement
                                @break

                                @case('mettre_en_attente')
                                    Mettre en attente
                                @break

                                @case('demarrer')
                                    Démarrer le projet
                                @break

                                @case('terminer')
                                    Terminer le projet
                                @break

                                @default
                                    {{ ucfirst($prochaineAction) }}
                                @break
                            @endswitch
                        </div>
                    @endif






                    {{-- <div class="flex flex-wrap gap-2">
                        @if ($projet->peutEtreApprouve())
                            <button type="button" onclick="approveProject()"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                <i class="fas fa-check mr-2"></i> Approuver
                            </button>
                        @endif

                        @if ($projet->peutEtrePlanifie())
                            <button type="button" onclick="planifyProject()"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                                <i class="fas fa-calendar-check mr-2"></i> Planifier
                            </button>
                        @endif

                        @if ($projet->peutEtreEnRechercheFinancement())
                            <button type="button" onclick="searchFunding()"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors shadow-sm">
                                <i class="fas fa-search-dollar mr-2"></i> Rechercher financement
                            </button>
                        @endif



                        @if ($projet->peutEtreDemarre())
                            <button type="button" onclick="startProject()"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                <i class="fas fa-play mr-2"></i> Démarrer
                            </button>
                        @endif

                        @if ($projet->statut === 'en_cours')
                            <button type="button" onclick="openProgressModal()"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors shadow-sm">
                                <i class="fas fa-chart-line mr-2"></i> Mettre à jour progression
                            </button>
                        @endif

                        @if ($projet->peutEtreTermine())
                            <button type="button" onclick="openCompleteModal()"
                                class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                                <i class="fas fa-flag-checkered mr-2"></i> Terminer
                            </button>
                        @endif

                        @if ($projet->peutEtreSuspendu())
                            <button type="button" onclick="suspendProject()"
                                class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors shadow-sm">
                                <i class="fas fa-pause mr-2"></i> Suspendre
                            </button>
                        @endif

                        @if ($projet->peutEtreRepris())
                            <button type="button" onclick="resumeProject()"
                                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
                                <i class="fas fa-play mr-2"></i> Reprendre
                            </button>
                        @endif

                        @can('projets.update')
                            <a href="{{ route('private.projets.edit', $projet) }}"
                                class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors shadow-sm">
                                <i class="fas fa-edit mr-2"></i> Modifier
                            </a>
                        @endcan

                        <button type="button" onclick="openDuplicateModal()"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                            <i class="fas fa-copy mr-2"></i> Dupliquer
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations générales du projet -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-6">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-4">
                                    @php
                                        $statutColors = [
                                            'conception' => 'bg-gray-100 text-gray-800',
                                            'planification' => 'bg-blue-100 text-blue-800',
                                            'recherche_financement' => 'bg-yellow-100 text-yellow-800',
                                            'en_attente' => 'bg-orange-100 text-orange-800',
                                            'en_cours' => 'bg-green-100 text-green-800',
                                            'suspendu' => 'bg-red-100 text-red-800',
                                            'termine' => 'bg-emerald-100 text-emerald-800',
                                            'annule' => 'bg-red-100 text-red-800',
                                            'archive' => 'bg-slate-100 text-slate-800',
                                        ];

                                        $prioriteColors = [
                                            'faible' => 'bg-gray-100 text-gray-600',
                                            'normale' => 'bg-blue-100 text-blue-600',
                                            'haute' => 'bg-yellow-100 text-yellow-600',
                                            'urgente' => 'bg-orange-100 text-orange-600',
                                            'critique' => 'bg-red-100 text-red-600',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statutColors[$projet->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $projet->statut_libelle }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $prioriteColors[$projet->priorite] ?? 'bg-gray-100 text-gray-600' }}">
                                        Priorité {{ $projet->priorite_libelle }}
                                    </span>
                                    @if ($projet->visible_public)
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800">
                                            <i class="fas fa-globe mr-1"></i> Public
                                        </span>
                                    @endif
                                </div>

                                @if ($projet->description)
                                    <div class="mt-6 pt-6 border-t border-slate-200">
                                        <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                            <i class="fas fa-align-left text-blue-600 mr-2"></i>
                                            Description
                                        </h3>
                                        <x-ckeditor-display :model="$projet" field="description" show-meta="true"
                                            class="bg-slate-50 p-4 rounded-lg" />
                                    </div>
                                @endif
                            </div>
                            @if ($projet->image_principale)
                                <div class="ml-6">
                                    <img src="{{ $projet->image_principale }}" alt="{{ $projet->nom_projet }}"
                                        class="w-32 h-32 object-cover rounded-xl shadow-md">
                                </div>
                            @endif
                        </div>

                        <!-- Progression détaillée -->
                        @if ($projet->statut === 'en_cours')
                            <div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-green-50 rounded-xl">
                                <div class="flex justify-between items-center mb-3">
                                    <h4 class="font-semibold text-slate-900">Progression du projet</h4>
                                    <span
                                        class="text-lg font-bold text-green-600">{{ $projet->pourcentage_completion }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-4 mb-3">
                                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-4 rounded-full transition-all duration-500 shadow-sm"
                                        style="width: {{ $projet->pourcentage_completion }}%"></div>
                                </div>
                                @if ($projet->derniere_activite)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span class="font-medium">Dernière activité:</span>
                                        <span class="ml-1">{{ $projet->derniere_activite }}</span>
                                    </div>
                                @endif
                                @if ($projet->derniere_activite_date)
                                    <div class="text-xs text-slate-500 mt-1">
                                        Mis à jour le {{ $projet->derniere_activite_date->format('d/m/Y à H:i') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Objectifs et contexte -->
                @if ($projet->objectif || $projet->contexte)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-bullseye text-green-600 mr-2"></i>
                                Objectifs et Contexte
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            @if ($projet->objectif)
                                <div>
                                    <h3 class="font-semibold text-slate-900 mb-3 flex items-center">
                                        <i class="fas fa-target text-blue-600 mr-2"></i>
                                        Objectifs
                                    </h3>
                                    <x-ckeditor-display :model="$projet" field="objectif" show-meta="true"
                                        class="bg-slate-50 p-4 rounded-lg" />
                                </div>
                            @endif

                            @if ($projet->contexte)
                                <div class="pt-6 border-t border-slate-200">
                                    <h3 class="font-semibold text-slate-900 mb-3 flex items-center">
                                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                        Contexte
                                    </h3>
                                    <x-ckeditor-display :model="$projet" field="contexte" show-meta="true"
                                        class="bg-slate-50 p-4 rounded-lg" />
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Historique des changements de statut -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-history text-indigo-600 mr-2"></i>
                            Historique du Projet
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Événement de création -->
                            <div class="flex items-start space-x-4 p-3 bg-blue-50 rounded-lg">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-plus text-white text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-blue-900">Projet créé</p>
                                    <p class="text-sm text-blue-700">
                                        Le {{ $projet->created_at->format('d/m/Y à H:i') }}
                                        @if ($projet->createur)
                                            par {{ $projet->createur->nom_complet }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Événement d'approbation -->
                            @if ($projet->approuve_le)
                                <div class="flex items-start space-x-4 p-3 bg-green-50 rounded-lg">
                                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-green-900">Projet approuvé</p>
                                        <p class="text-sm text-green-700">
                                            Le {{ $projet->approuve_le->format('d/m/Y à H:i') }}
                                            @if ($projet->approbateur)
                                                par {{ $projet->approbateur->nom_complet }}
                                            @endif
                                        </p>
                                        @if ($projet->commentaires_approbation)
                                            <p class="text-sm text-green-600 mt-1 italic">
                                                {{ $projet->commentaires_approbation }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Événement de démarrage -->
                            @if ($projet->date_debut)
                                <div class="flex items-start space-x-4 p-3 bg-purple-50 rounded-lg">
                                    <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-play text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-purple-900">Projet démarré</p>
                                        <p class="text-sm text-purple-700">Le {{ $projet->date_debut->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            <!-- Événement de fin -->
                            @if ($projet->date_fin_reelle)
                                <div class="flex items-start space-x-4 p-3 bg-emerald-50 rounded-lg">
                                    <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-flag-checkered text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-emerald-900">Projet terminé</p>
                                        <p class="text-sm text-emerald-700">Le
                                            {{ $projet->date_fin_reelle->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Résultats et évaluation (si projet terminé) -->
                @if ($projet->statut === 'termine' && ($projet->resultats_obtenus || $projet->note_satisfaction))
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                                Résultats et Évaluation
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            @if ($projet->resultats_obtenus)
                                <div>
                                    <h3 class="font-semibold text-slate-900 mb-2">Résultats obtenus</h3>
                                    <div class="prose prose-slate max-w-none">
                                        {!! nl2br(e($projet->resultats_obtenus)) !!}
                                    </div>
                                </div>
                            @endif

                            @if ($projet->note_satisfaction)
                                <div>
                                    <h3 class="font-semibold text-slate-900 mb-2">Note de satisfaction</h3>
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400">
                                            @for ($i = 1; $i <= 10; $i++)
                                                @if ($i <= $projet->note_satisfaction)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-slate-600">{{ $projet->note_satisfaction }}/10</span>
                                    </div>
                                </div>
                            @endif

                            @if ($projet->impact_communaute)
                                <div>
                                    <h3 class="font-semibold text-slate-900 mb-2">Impact sur la communauté</h3>
                                    <div class="prose prose-slate max-w-none">
                                        {!! nl2br(e($projet->impact_communaute)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Historique des fonds -->
                @if ($projet->fonds->count() > 0)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-coins text-green-600 mr-2"></i>
                                Historique des Dons ({{ $projet->fonds->count() }})
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach ($projet->fonds->take(5) as $don)
                                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                                <i class="fas fa-donate text-green-600"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium text-slate-900">{{ $don->nom_donateur }}</p>
                                                <p class="text-sm text-slate-500">
                                                    {{ $don->date_transaction->format('d/m/Y') }} •
                                                    {{ $don->type_transaction_libelle }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-green-600">{{ $don->montant_format }}</p>
                                            <p class="text-sm text-slate-500">{{ $don->mode_paiement_libelle }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($projet->fonds->count() > 5)
                                <div class="mt-4 text-center">
                                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                        Voir tous les dons ({{ $projet->fonds->count() }})
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Projets liés -->
                @if ($projet->projetsEnfants->count() > 0)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-sitemap text-purple-600 mr-2"></i>
                                Projets Dérivés ({{ $projet->projetsEnfants->count() }})
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($projet->projetsEnfants as $enfant)
                                    <div
                                        class="border border-slate-200 rounded-xl p-4 hover:shadow-md transition-all duration-200">
                                        <h3 class="font-medium text-slate-900 mb-1">{{ $enfant->nom_projet }}</h3>
                                        <p class="text-sm text-slate-500 mb-2">{{ $enfant->code_projet }} •
                                            {{ $enfant->type_projet_libelle }}</p>
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statutColors[$enfant->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $enfant->statut_libelle }}
                                            </span>
                                            <a href="{{ route('private.projets.show', $enfant) }}"
                                                class="text-blue-600 hover:text-blue-800 text-sm">
                                                Voir détails →
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informations générales -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-slate-700">Code:</span>
                            <span class="text-sm text-slate-900 font-mono">{{ $projet->code_projet }}</span>
                        </div>

                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-slate-700">Type:</span>
                            <span class="text-sm text-slate-900">{{ $projet->type_projet_libelle }}</span>
                        </div>

                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium text-slate-700">Catégorie:</span>
                            <span class="text-sm text-slate-900">{{ $projet->categorie }}</span>
                        </div>

                        @if ($projet->localisation)
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-700">Lieu:</span>
                                <span class="text-sm text-slate-900">{{ $projet->localisation }}</span>
                            </div>
                        @endif

                        @if ($projet->ville)
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-700">Ville:</span>
                                <span class="text-sm text-slate-900">{{ $projet->ville }}</span>
                            </div>
                        @endif

                        @if ($projet->site_web)
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-medium text-slate-700">Site web:</span>
                                <a href="{{ $projet->site_web }}" target="_blank"
                                    class="text-sm text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Budget et financement -->
                @if ($projet->budget_prevu)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-wallet text-green-600 mr-2"></i>
                                Budget
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-green-600">{{ $projet->budget_format }}</div>
                                <div class="text-sm text-slate-500">Budget prévu</div>
                            </div>

                            @if ($statistiquesFinancieres['total_collecte'] > 0)
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Collecté</span>
                                        <span
                                            class="font-medium text-green-600">{{ number_format($statistiquesFinancieres['total_collecte'], 0, ',', ' ') }}
                                            {{ $projet->devise }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Progression</span>
                                        <span
                                            class="font-medium text-blue-600">{{ $statistiquesFinancieres['pourcentage_financement'] }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full"
                                            style="width: {{ min($statistiquesFinancieres['pourcentage_financement'], 100) }}%">
                                        </div>
                                    </div>
                                    @if ($statistiquesFinancieres['montant_restant'] > 0)
                                        <div class="text-center text-sm text-slate-600">
                                            Reste:
                                            {{ number_format($statistiquesFinancieres['montant_restant'], 0, ',', ' ') }}
                                            {{ $projet->devise }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if ($statistiquesFinancieres['nombre_donations'] > 0)
                                <div class="pt-4 border-t border-slate-200">
                                    <div class="text-center">
                                        <div class="text-xl font-bold text-blue-600">
                                            {{ $statistiquesFinancieres['nombre_donations'] }}</div>
                                        <div class="text-sm text-slate-500">donations reçues</div>
                                    </div>
                                    @if ($statistiquesFinancieres['derniere_donation'])
                                        <div class="text-center text-xs text-slate-400 mt-2">
                                            Dernière:
                                            {{ \Carbon\Carbon::parse($statistiquesFinancieres['derniere_donation'])->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Équipe -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-purple-600 mr-2"></i>
                            Équipe
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if ($projet->responsable)
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user-tie text-blue-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $projet->responsable->nom_complet }}</div>
                                    <div class="text-xs text-slate-500">Responsable principal</div>
                                </div>
                            </div>
                        @endif

                        @if ($projet->coordinateur)
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user-cog text-green-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-900">
                                        {{ $projet->coordinateur->nom_complet }}</div>
                                    <div class="text-xs text-slate-500">Coordinateur</div>
                                </div>
                            </div>
                        @endif

                        @if ($projet->chefProjet)
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user-crown text-purple-600 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-900">{{ $projet->chefProjet->nom_complet }}
                                    </div>
                                    <div class="text-xs text-slate-500">Chef de projet</div>
                                </div>
                            </div>
                        @endif

                        @if (!$projet->responsable && !$projet->coordinateur && !$projet->chefProjet)
                            <div class="text-center text-slate-500 text-sm py-4">
                                Aucun responsable assigné
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Échéances -->
                @if ($projet->date_debut || $projet->date_fin_prevue)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-calendar text-red-600 mr-2"></i>
                                Échéances
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @if ($projet->date_debut)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-slate-700">Début:</span>
                                    <span class="text-sm text-slate-900">{{ $projet->date_debut->format('d/m/Y') }}</span>
                                </div>
                            @endif

                            @if ($projet->date_fin_prevue)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-slate-700">Fin prévue:</span>
                                    <span
                                        class="text-sm text-slate-900">{{ $projet->date_fin_prevue->format('d/m/Y') }}</span>
                                </div>
                            @endif

                            @if ($projet->date_fin_reelle)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-slate-700">Fin réelle:</span>
                                    <span
                                        class="text-sm text-slate-900">{{ $projet->date_fin_reelle->format('d/m/Y') }}</span>
                                </div>
                            @endif

                            @if ($projet->date_fin_prevue && !$projet->date_fin_reelle)
                                @php $joursRestants = $projet->jours_restants; @endphp
                                <div class="pt-2 border-t border-slate-200">
                                    @if ($joursRestants > 0)
                                        <div class="text-center text-sm text-green-600">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $joursRestants }} jour{{ $joursRestants > 1 ? 's' : '' }}
                                            restant{{ $joursRestants > 1 ? 's' : '' }}
                                        </div>
                                    @elseif($joursRestants < 0)
                                        <div class="text-center text-sm text-red-600">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            En retard de {{ abs($joursRestants) }}
                                            jour{{ abs($joursRestants) > 1 ? 's' : '' }}
                                        </div>
                                    @else
                                        <div class="text-center text-sm text-orange-600">
                                            <i class="fas fa-calendar-day mr-1"></i>
                                            Échéance aujourd'hui
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal mise à jour progression -->
    <div id="progressModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Mettre à jour la progression</h3>
                <form id="progressForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Pourcentage de completion</label>
                            <div class="relative">
                                <input type="number" name="pourcentage_completion" id="pourcentage_completion"
                                    min="0" max="100" step="1"
                                    value="{{ $projet->pourcentage_completion }}" required
                                    class="w-full px-3 py-2 pr-8 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <span class="absolute right-3 top-2 text-slate-500 text-sm">%</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Dernière activité</label>
                            <textarea name="derniere_activite" id="derniere_activite" rows="3"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                placeholder="Décrivez la dernière activité réalisée...">{{ $projet->derniere_activite }}</textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeProgressModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" onclick="updateProgress()"
                    class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                    Mettre à jour
                </button>
            </div>
        </div>
    </div>

    <!-- Modal terminer projet -->
    <div id="completeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Terminer le projet</h3>
                <form id="completeForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date de fin réelle</label>
                            <input type="date" name="date_fin_reelle" id="date_fin_reelle"
                                value="{{ now()->format('Y-m-d') }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Résultats obtenus</label>
                            <textarea name="resultats_obtenus" id="resultats_obtenus" rows="4"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                placeholder="Décrivez les résultats obtenus..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Note de satisfaction
                                (1-10)</label>
                            <input type="number" name="note_satisfaction" id="note_satisfaction" min="1"
                                max="10" step="0.1"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Impact sur la communauté</label>
                            <textarea name="impact_communaute" id="impact_communaute" rows="3"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                placeholder="Décrivez l'impact sur la communauté..."></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeCompleteModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" onclick="completeProject()"
                    class="px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">
                    Terminer le projet
                </button>
            </div>
        </div>
    </div>

    <!-- Modal duplication -->
    <div id="duplicateModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Dupliquer le projet</h3>
                <form id="duplicateForm">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau nom</label>
                            <input type="text" name="nouveau_nom" id="nouveau_nom"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Laisser vide pour ajouter (Copie)">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau code</label>
                            <input type="text" name="nouveau_code" id="nouveau_code"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Laisser vide for génération automatique">
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeDuplicateModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" onclick="duplicateProject()"
                    class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                    Dupliquer
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Actions rapides
            function approveProject() {
                if (confirm('Êtes-vous sûr de vouloir approuver ce projet ?')) {
                    fetch(`{{ route('private.projets.approuver', $projet) }}`, {
                            method: 'POST',
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
            }

            function startProject() {
                if (confirm('Êtes-vous sûr de vouloir démarrer ce projet ? Il passera en cours d\'exécution.')) {
                    fetch(`{{ route('private.projets.demarrer', $projet) }}`, {
                            method: 'POST',
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
            }

            function suspendProject() {
                const motif = prompt('Motif de suspension:');
                if (motif) {
                    fetch(`{{ route('private.projets.suspendre', $projet) }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                motif: motif
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.message || 'Une erreur est survenue');
                            }
                        });
                }
            }

            function resumeProject() {
                if (confirm('Êtes-vous sûr de vouloir reprendre ce projet ?')) {
                    fetch(`{{ route('private.projets.reprendre', $projet) }}`, {
                            method: 'POST',
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
                        });
                }
            }

            // Modal progression
            function openProgressModal() {
                document.getElementById('progressModal').classList.remove('hidden');
            }

            function closeProgressModal() {
                document.getElementById('progressModal').classList.add('hidden');
            }

            function updateProgress() {
                const form = document.getElementById('progressForm');
                const formData = new FormData(form);

                fetch(`{{ route('private.projets.progression', $projet) }}`, {
                        method: 'POST',
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
                    });
            }

            // Modal terminer
            function openCompleteModal() {
                document.getElementById('completeModal').classList.remove('hidden');
            }

            function closeCompleteModal() {
                document.getElementById('completeModal').classList.add('hidden');
            }

            function completeProject() {
                const form = document.getElementById('completeForm');
                const formData = new FormData(form);

                fetch(`{{ route('private.projets.terminer', $projet) }}`, {
                        method: 'POST',
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
                    });
            }

            // Modal duplication
            function openDuplicateModal() {
                document.getElementById('duplicateModal').classList.remove('hidden');
            }

            function closeDuplicateModal() {
                document.getElementById('duplicateModal').classList.add('hidden');
                document.getElementById('duplicateForm').reset();
            }

            function duplicateProject() {
                const form = document.getElementById('duplicateForm');
                const formData = new FormData(form);

                fetch(`{{ route('private.projets.dupliquer', $projet) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = `{{ route('private.projets.show', ':projectid') }}`.replace(
                                ':projectid', data.data.id);
                        } else {
                            alert(data.message || 'Une erreur est survenue');
                        }
                    });
            }


            function planifyProject() {
                if (confirm(
                        'Êtes-vous sûr de vouloir planifier ce projet ? Il passera du statut "conception" à "planification".'
                    )) {
                    fetch(`{{ route('private.projets.planifier', $projet) }}`, {
                            method: 'POST',
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
            }


            function searchFunding() {
                if (confirm('Êtes-vous sûr de vouloir mettre ce projet en recherche de financement ?')) {
                    fetch(`{{ route('private.projets.rechercher-financement', $projet) }}`, {
                            method: 'POST',
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
            }


            function putOnHold() {
                if (confirm('Êtes-vous sûr de vouloir mettre ce projet en attente ? Il sera prêt à être démarré.')) {
                    fetch(`{{ route('private.projets.mettre-en-attente', $projet) }}`, {
                            method: 'POST',
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
            }

            // Nouvelle fonction pour annuler un projet
            function cancelProject() {
                const motif = prompt('Motif d\'annulation (obligatoire):');
                if (motif && motif.trim()) {
                    fetch(`{{ route('private.projets.annuler', $projet) }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                motif: motif.trim()
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
                        });
                }
            }


            // Fermer les modals en cliquant à l'extérieur
            document.getElementById('progressModal').addEventListener('click', function(e) {
                if (e.target === this) closeProgressModal();
            });

            document.getElementById('completeModal').addEventListener('click', function(e) {
                if (e.target === this) closeCompleteModal();
            });

            document.getElementById('duplicateModal').addEventListener('click', function(e) {
                if (e.target === this) closeDuplicateModal();
            });

            // Animation du pourcentage de progression en temps réel
            document.getElementById('pourcentage_completion')?.addEventListener('input', function(e) {
                const value = e.target.value;
                if (value >= 0 && value <= 100) {
                    // Optionnel: prévisualiser la progression en temps réel
                    console.log('Progression:', value + '%');
                }
            });
        </script>
    @endpush
@endsection
