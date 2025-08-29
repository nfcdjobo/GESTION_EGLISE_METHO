@extends('layouts.private.main')
@section('title', 'Détails du Rapport')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">{{ $rapport->titre_rapport }}</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.rapports-reunions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>
                        Rapports de Réunions
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">{{ $rapport->titre_rapport }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Informations générales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations Générales
                        </h2>
                        @php
                            $statutColors = [
                                'brouillon' => 'bg-gray-100 text-gray-800',
                                'en_revision' => 'bg-yellow-100 text-yellow-800',
                                'valide' => 'bg-blue-100 text-blue-800',
                                'publie' => 'bg-green-100 text-green-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statutColors[$rapport->statut] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $rapport->statut_traduit }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Type de rapport</label>
                                <p class="text-slate-900 font-medium">{{ $rapport->type_rapport_traduit }}</p>
                            </div>

                            @if($rapport->reunion)
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Réunion concernée</label>
                                    <p class="text-slate-900 font-medium">{{ $rapport->reunion->titre }}</p>
                                    <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($rapport->reunion->date_reunion)->format('d/m/Y à H:i') }}</p>
                                </div>
                            @endif

                            @if($rapport->redacteur)
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Rédacteur</label>
                                    <p class="text-slate-900 font-medium">{{ $rapport->redacteur->nom }} {{ $rapport->redacteur->prenom }}</p>
                                </div>
                            @endif

                            @if($rapport->validateur)
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Validateur</label>
                                    <p class="text-slate-900 font-medium">{{ $rapport->validateur->nom }} {{ $rapport->validateur->prenom }}</p>
                                    @if($rapport->valide_le)
                                        <p class="text-sm text-slate-500">Validé le {{ $rapport->valide_le->format('d/m/Y à H:i') }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Dates</label>
                                <div class="text-sm space-y-1">
                                    <p><span class="font-medium">Créé:</span> {{ $rapport->created_at->format('d/m/Y à H:i') }}</p>
                                    <p><span class="font-medium">Modifié:</span> {{ $rapport->updated_at->format('d/m/Y à H:i') }}</p>
                                    @if($rapport->publie_le)
                                        <p><span class="font-medium">Publié:</span> {{ $rapport->publie_le->format('d/m/Y à H:i') }}</p>
                                    @endif
                                </div>
                            </div>

                            @if($rapport->nombre_presents || $rapport->montant_collecte)
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Statistiques</label>
                                    <div class="text-sm space-y-1">
                                        @if($rapport->nombre_presents)
                                            <p><span class="font-medium">Présents:</span> {{ $rapport->nombre_presents }}</p>
                                        @endif
                                        @if($rapport->montant_collecte)
                                            <p><span class="font-medium">Montant collecté:</span> {{ number_format($rapport->montant_collecte, 2) }} €</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($rapport->note_satisfaction)
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Note de satisfaction</label>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $rapport->note_satisfaction ? 'text-yellow-400' : 'text-slate-300' }} mr-1"></i>
                                        @endfor
                                        <span class="ml-2 text-sm text-slate-600">({{ $rapport->note_satisfaction }}/5)</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé -->
            @if($rapport->resume)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-file-text text-green-600 mr-2"></i>
                            Résumé
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-slate max-w-none">
                            <x-ckeditor-display :model="$rapport" field="resume" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
                            {{-- {!! nl2br(e($rapport->resume)) !!} --}}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Points traités -->
            @if($rapport->points_traites && count($rapport->points_traites) > 0)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-list text-purple-600 mr-2"></i>
                            Points Traités ({{ count($rapport->points_traites) }})
                        </h2>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            @foreach($rapport->points_traites as $point)
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3 flex-shrink-0"></i>
                                    <span class="text-slate-700">{{ is_array($point) ? $point['titre'] ?? $point : $point }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Décisions prises -->
            @if($rapport->decisions_prises)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-gavel text-amber-600 mr-2"></i>
                            Décisions Prises
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-slate max-w-none">
                            <x-ckeditor-display :model="$rapport" field="decisions_prises" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
                            {{-- {!! nl2br(e($rapport->decisions_prises)) !!} --}}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions décidées -->
            @if($rapport->actions_decidees)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-tasks text-indigo-600 mr-2"></i>
                            Actions Décidées
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-slate max-w-none">
                            <x-ckeditor-display :model="$rapport" field="actions_decidees" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
                            {{-- {!! nl2br(e($rapport->actions_decidees)) !!} --}}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions de suivi -->
            @if($actionsEnCours->count() > 0 || $actionsTerminees->count() > 0)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-project-diagram text-cyan-600 mr-2"></i>
                            Actions de Suivi
                            <span class="ml-2 text-sm font-normal text-slate-600">({{ $actionsEnCours->count() }} en cours, {{ $actionsTerminees->count() }} terminées)</span>
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($actionsEnCours->count() > 0)
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                    <i class="fas fa-hourglass-half text-orange-500 mr-2"></i>
                                    Actions en cours
                                </h3>
                                <div class="space-y-3">
                                    @foreach($actionsEnCours as $action)
                                        <div class="border border-orange-200 bg-orange-50 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-medium text-slate-900">{{ $action['titre'] ?? 'Action sans titre' }}</h4>
                                                @if(isset($action['priorite']))
                                                    @php
                                                        $prioriteColors = [
                                                            'faible' => 'bg-green-100 text-green-800',
                                                            'normale' => 'bg-blue-100 text-blue-800',
                                                            'haute' => 'bg-orange-100 text-orange-800',
                                                            'critique' => 'bg-red-100 text-red-800'
                                                        ];
                                                    @endphp
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $prioriteColors[$action['priorite']] ?? 'bg-gray-100 text-gray-800' }}">
                                                        {{ ucfirst($action['priorite']) }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if(isset($action['description']))
                                                <p class="text-sm text-slate-600 mb-2">{{ $action['description'] }}</p>
                                            @endif
                                            <div class="flex items-center justify-between text-sm text-slate-500">
                                                @if(isset($action['echeance']))
                                                    <span><i class="fas fa-calendar mr-1"></i>Échéance: {{ \Carbon\Carbon::parse($action['echeance'])->format('d/m/Y') }}</span>
                                                @else
                                                    <span></span>
                                                @endif
                                                @can('update', $rapport)
                                                    <button onclick="terminerAction('{{ $action['id'] ?? '' }}')" class="text-green-600 hover:text-green-800 font-medium">
                                                        <i class="fas fa-check mr-1"></i>Marquer terminée
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($actionsTerminees->count() > 0)
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    Actions terminées
                                </h3>
                                <div class="space-y-3">
                                    @foreach($actionsTerminees as $action)
                                        <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-medium text-slate-900">{{ $action['titre'] ?? 'Action sans titre' }}</h4>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i>Terminée
                                                </span>
                                            </div>
                                            @if(isset($action['description']))
                                                <p class="text-sm text-slate-600 mb-2">{{ $action['description'] }}</p>
                                            @endif
                                            <div class="text-sm text-slate-500">
                                                @if(isset($action['terminee_le']))
                                                    <span><i class="fas fa-calendar-check mr-1"></i>Terminée le {{ \Carbon\Carbon::parse($action['terminee_le'])->format('d/m/Y à H:i') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Présences -->
            @if($rapport->presences && count($rapport->presences) > 0)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-blue-600 mr-2"></i>
                            Présences ({{ count($rapport->presences) }})
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($rapport->presences as $presence)
                                <div class="flex items-center p-3 bg-slate-50 rounded-lg">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-900">{{ is_array($presence) ? $presence['nom'] : $presence }}</p>
                                        @if(is_array($presence) && isset($presence['role']))
                                            <p class="text-xs text-slate-500">{{ $presence['role'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Recommandations -->
            @if($rapport->recommandations)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                            Recommandations
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-slate max-w-none">
                            <x-ckeditor-display :model="$rapport" field="recommandations" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
                            {{-- {!! nl2br(e($rapport->recommandations)) !!} --}}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Commentaires -->
            @if($rapport->commentaires)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-comment-alt text-slate-600 mr-2"></i>
                            Commentaires
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-slate max-w-none">
                            <x-ckeditor-display :model="$rapport" field="commentaires" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
                            {{-- {!! nl2br(e($rapport->commentaires)) !!} --}}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cogs text-blue-600 mr-2"></i>
                        Actions
                    </h2>
                </div>
                <div class="p-6 space-y-3">

                    <button type="button" onclick="exporterPDF()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 mb-3">
                        <i class="fas fa-file-pdf mr-2"></i> Exporter en PDF
                    </button>

                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('private.rapports-reunions.export', ['format' => 'excel', 'rapport_ids' => [$rapport->id]]) }}" class="inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-xs font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                            <i class="fas fa-file-excel mr-1"></i> Excel
                        </a>

                        <button type="button" onclick="partagerRapport()" class="inline-flex items-center justify-center px-3 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-xs font-medium rounded-lg hover:from-blue-700 hover:to-cyan-700 transition-all duration-200">
                            <i class="fas fa-share mr-1"></i> Partager
                        </button>
                    </div>


                    @can('update', $rapport)
                        <a href="{{ route('private.rapports-reunions.edit', $rapport) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    @endcan

                    @if($rapport->statut === 'brouillon' && $rapport->peutEtreModifiePar(auth()->user()))
                        <button onclick="changerStatut('en_revision')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                            <i class="fas fa-arrow-right mr-2"></i> Passer en révision
                        </button>
                    @endif

                    @if($rapport->statut === 'en_revision' && auth()->user()->can('validate', $rapport))
                        <button onclick="openValidationModal()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                            <i class="fas fa-check mr-2"></i> Valider
                        </button>
                        <button onclick="openRejectionModal()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i> Rejeter
                        </button>
                    @endif

                    @if($rapport->statut === 'valide' && auth()->user()->can('publish', $rapport))
                        <button onclick="changerStatut('publie')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                            <i class="fas fa-share mr-2"></i> Publier
                        </button>
                    @endif

                    @can('delete', $rapport)
                        @if($rapport->statut !== 'publie')
                            <button onclick="supprimerRapport()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200">
                                <i class="fas fa-trash mr-2"></i> Supprimer
                            </button>
                        @endif
                    @endcan

                    <a href="{{ route('private.rapports-reunions.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>

            <!-- Statistiques -->
            @if($statistiques)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-chart-pie text-green-600 mr-2"></i>
                            Statistiques
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if(isset($statistiques['nombre_points_traites']))
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Points traités:</span>
                                <span class="text-sm text-slate-900 font-semibold">{{ $statistiques['nombre_points_traites'] }}</span>
                            </div>
                        @endif

                        @if(isset($statistiques['nombre_actions_suivre']))
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Actions de suivi:</span>
                                <span class="text-sm text-slate-900 font-semibold">{{ $statistiques['nombre_actions_suivre'] }}</span>
                            </div>
                        @endif

                        @if(isset($statistiques['actions_terminees']))
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Actions terminées:</span>
                                <span class="text-sm text-slate-900 font-semibold">{{ $statistiques['actions_terminees'] }}</span>
                            </div>
                        @endif

                        @if(isset($statistiques['taux_presence']) && $statistiques['taux_presence'])
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Taux de présence:</span>
                                <span class="text-sm text-slate-900 font-semibold">{{ $statistiques['taux_presence'] }}%</span>
                            </div>
                        @endif

                        @if(isset($statistiques['jours_pour_validation']) && $statistiques['jours_pour_validation'])
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Délai validation:</span>
                                <span class="text-sm text-slate-900 font-semibold">{{ $statistiques['jours_pour_validation'] }} jours</span>
                            </div>
                        @endif

                        <div class="pt-2 border-t border-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Complété à:</span>
                                <div class="flex items-center">
                                    <div class="w-16 h-2 bg-slate-200 rounded-full mr-2">
                                        <div class="h-2 bg-gradient-to-r from-blue-500 to-green-500 rounded-full" style="width: {{ $rapport->pourcentage_completion }}%"></div>
                                    </div>
                                    <span class="text-sm text-slate-900 font-semibold">{{ $rapport->pourcentage_completion }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Workflow -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-route text-purple-600 mr-2"></i>
                        Workflow
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @php
                            $etapes = [
                                'brouillon' => ['label' => 'Brouillon', 'icon' => 'fas fa-edit', 'color' => 'text-gray-500'],
                                'en_revision' => ['label' => 'En révision', 'icon' => 'fas fa-eye', 'color' => 'text-yellow-500'],
                                'valide' => ['label' => 'Validé', 'icon' => 'fas fa-check', 'color' => 'text-blue-500'],
                                'publie' => ['label' => 'Publié', 'icon' => 'fas fa-share', 'color' => 'text-green-500']
                            ];

                            $currentOrder = \App\Models\RapportReunion::WORKFLOW_ORDER[$rapport->statut] ?? 1;
                        @endphp

                        @foreach($etapes as $key => $etape)
                            @php
                                $order = \App\Models\RapportReunion::WORKFLOW_ORDER[$key] ?? 1;
                                $isActive = $rapport->statut === $key;
                                $isCompleted = $order < $currentOrder;
                                $isPending = $order > $currentOrder;
                            @endphp

                            <div class="flex items-center {{ $isActive ? 'text-blue-600 font-semibold' : ($isCompleted ? 'text-green-600' : 'text-gray-400') }}">
                                <div class="w-8 h-8 rounded-full {{ $isActive ? 'bg-blue-100' : ($isCompleted ? 'bg-green-100' : 'bg-gray-100') }} flex items-center justify-center mr-3">
                                    <i class="{{ $etape['icon'] }} text-sm"></i>
                                </div>
                                <span class="text-sm">{{ $etape['label'] }}</span>
                                @if($isCompleted)
                                    <i class="fas fa-check text-green-500 ml-auto"></i>
                                @elseif($isActive)
                                    <div class="w-2 h-2 bg-blue-500 rounded-full ml-auto animate-pulse"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal validation -->
<div id="validationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Valider le rapport</h3>
            <form id="validationForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Commentaires (optionnel)</label>
                    <div class="has-error-container">
                        <textarea name="commentaires" id="commentaires_validation" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Commentaires sur la validation..."></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeValidationModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="validerRapport()" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                Valider
            </button>
        </div>
    </div>
</div>

<!-- Modal rejet -->
<div id="rejectionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Rejeter le rapport</h3>
            <form id="rejectionForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Raison du rejet <span class="text-red-500">*</span></label>
                    <div class="has-error-container">
                        <textarea name="raison" id="raison_rejet" rows="3" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Expliquez pourquoi ce rapport est rejeté..."></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeRejectionModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="rejeterRapport()" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Rejeter
            </button>
        </div>
    </div>
</div>

@include('partials.ckeditor-resources')
@push('scripts')
<script>
// Modals
function openValidationModal() {
    document.getElementById('validationModal').classList.remove('hidden');
    // Initialiser CKEditor
    setTimeout(() => {
        if (document.getElementById('commentaires_validation') && typeof ClassicEditor !== 'undefined') {
            if (!document.querySelector('#commentaires_validation + .ck-editor')) {
                initializeCKEditor('#commentaires_validation', 'simple');
            }
        }
    }, 100);
}


function exporterPDF() {
    window.open('{{ route("private.rapports-reunions.export-pdf", $rapport->id) }}', '_blank');
}

function partagerRapport() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $rapport->titre_rapport }}',
            text: 'Rapport de réunion : {{ $rapport->titre_rapport }}',
            url: window.location.href
        });
    } else {
        // Fallback - copier le lien
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Lien copié dans le presse-papier');
        });
    }
}



function closeValidationModal() {
    // Nettoyer CKEditor
    const editorContainer = document.querySelector('#commentaires_validation + .ck-editor');
    if (editorContainer && window.CKEditorInstances && window.CKEditorInstances['#commentaires_validation']) {
        window.CKEditorInstances['#commentaires_validation'].destroy()
            .then(() => delete window.CKEditorInstances['#commentaires_validation'])
            .catch(console.error);
    }
    document.getElementById('validationModal').classList.add('hidden');
    document.getElementById('validationForm').reset();
}

function openRejectionModal() {
    document.getElementById('rejectionModal').classList.remove('hidden');
    // Initialiser CKEditor
    setTimeout(() => {
        if (document.getElementById('raison_rejet') && typeof ClassicEditor !== 'undefined') {
            if (!document.querySelector('#raison_rejet + .ck-editor')) {
                initializeCKEditor('#raison_rejet', 'simple');
            }
        }
    }, 100);
}

function closeRejectionModal() {
    // Nettoyer CKEditor
    const editorContainer = document.querySelector('#raison_rejet + .ck-editor');
    if (editorContainer && window.CKEditorInstances && window.CKEditorInstances['#raison_rejet']) {
        window.CKEditorInstances['#raison_rejet'].destroy()
            .then(() => delete window.CKEditorInstances['#raison_rejet'])
            .catch(console.error);
    }
    document.getElementById('rejectionModal').classList.add('hidden');
    document.getElementById('rejectionForm').reset();
}

// Actions workflow
function changerStatut(nouveauStatut) {
    let url;
    switch(nouveauStatut) {
        case 'en_revision':
            url = `{{ route('private.rapports-reunions.revision', $rapport->id) }}`;
            break;
        case 'publie':
            url = `{{ route('private.rapports-reunions.publier', $rapport->id) }}`;
            break;
        default:
            return;
    }

    fetch(url, {
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

function validerRapport() {
    // Synchroniser CKEditor
    if (window.CKEditorInstances && window.CKEditorInstances['#commentaires_validation']) {
        const editor = window.CKEditorInstances['#commentaires_validation'];
        const textarea = document.getElementById('commentaires_validation');
        if (textarea) {
            textarea.value = editor.getData();
        }
    }

    const form = document.getElementById('validationForm');
    const formData = new FormData(form);

    fetch(`{{ route('private.rapports-reunions.valider', $rapport->id) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
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
    });
}

function rejeterRapport() {
    // Synchroniser CKEditor
    if (window.CKEditorInstances && window.CKEditorInstances['#raison_rejet']) {
        const editor = window.CKEditorInstances['#raison_rejet'];
        const textarea = document.getElementById('raison_rejet');
        if (textarea) {
            textarea.value = editor.getData();
        }
    }

    const form = document.getElementById('rejectionForm');
    const formData = new FormData(form);

    const raison = document.getElementById('raison_rejet').value;
    if (!raison.trim()) {
        alert('Veuillez indiquer la raison du rejet');
        return;
    }

    fetch(`{{ route('private.rapports-reunions.rejeter', $rapport->id) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
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
    });
}

function terminerAction(actionId) {
    if (!actionId) {
        alert('Action non identifiée');
        return;
    }

    const formData = new FormData();
    formData.append('action_id', actionId);

    fetch(`{{ route('private.rapports-reunions.actions.terminer', $rapport->id) }}`, {
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
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

function supprimerRapport() {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')) {
        fetch(`{{ route('private.rapports-reunions.destroy', $rapport->id) }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("private.rapports-reunions.index") }}';
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
['validationModal', 'rejectionModal'].forEach(modalId => {
    document.getElementById(modalId).addEventListener('click', function(e) {
        if (e.target === this) {
            if (modalId === 'validationModal') closeValidationModal();
            if (modalId === 'rejectionModal') closeRejectionModal();
        }
    });
});
</script>
@endpush
@endsection
