@extends('layouts.private.main')
@section('title', 'Détails de la Réunion')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">{{ $reunion->titre }}</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.reunions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Réunions
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">{{ Str::limit($reunion->titre, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @can('reunions.update')
                    <a href="{{ route('private.reunions.edit', $reunion) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                @endcan

                @can('reunions.confirm')
                @if($reunion->statut === 'planifiee')
                    <button onclick="changerStatut('{{ $reunion->id }}', 'confirmer')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-check mr-2"></i> Confirmer
                    </button>

                @endif
                @endcan

                @can('reunions.start')
                @if($reunion->peutCommencer())
                    <button onclick="changerStatut('{{ $reunion->id }}', 'commencer')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-play mr-2"></i> Commencer
                    </button>
                @endif
                @endcan

                @can('reunions.end')
                @if($reunion->peutEtreTerminee())
                    <button onclick="changerStatut('{{ $reunion->id }}', 'terminer')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-stop mr-2"></i> Terminer
                    </button>
                @endif
                @endcan

                @can('reunions.update')
                @if($reunion->statut === 'suspendue')
                    <button onclick="changerStatut('{{ $reunion->id }}', 'reprendre')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-teal-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-teal-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-play mr-2"></i> Reprendre
                    </button>
                @endif
                @endcan

                @can('reunions.suspend')
                @if($reunion->statut === 'en_cours')
                    <button onclick="openSuspendreModal('{{ $reunion->id }}')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white text-sm font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-pause mr-2"></i> Suspendre
                    </button>
                @endif

                @if($reunion->peutEtreAnnulee())
                    <button onclick="openAnnulerModal('{{ $reunion->id }}')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </button>
                @endif

                @if($reunion->peutEtreReportee())
                    <button onclick="openReporterModal('{{ $reunion->id }}', {{ $reunion->id }})" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-calendar-alt mr-2"></i> Reporter
                    </button>
                @endif

                @can('reunions.create')
                    <button onclick="openDuplicateModal('{{ $reunion->id }}', {{$reunion}})" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-600 to-slate-600 text-white text-sm font-medium rounded-xl hover:from-gray-700 hover:to-slate-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-copy mr-2"></i> Dupliquer
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Informations générales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations Générales
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Titre</label>
                            <p class="text-lg font-semibold text-slate-900">{{ $reunion->titre }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Type</label>
                            <p class="text-lg text-slate-900">{{ $reunion->typeReunion->nom ?? 'Non défini' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Statut</label>
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
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statutColors[$reunion->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($reunion->statut) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Priorité</label>
                            @php
                                $prioriteColors = [
                                    'faible' => 'bg-gray-100 text-gray-800',
                                    'normale' => 'bg-blue-100 text-blue-800',
                                    'haute' => 'bg-yellow-100 text-yellow-800',
                                    'urgente' => 'bg-orange-100 text-orange-800',
                                    'critique' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $prioriteColors[$reunion->niveau_priorite] ?? 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($reunion->niveau_priorite) }}
                            </span>
                        </div>
                    </div>

                    @if($reunion->description)
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">Description</label>
                            <div class="prose prose-sm max-w-none text-slate-700">
                                <x-ckeditor-display :model="$reunion" field="description" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
                            </div>
                        </div>
                    @endif

                    @if($reunion->objectifs)
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">Objectifs</label>
                            <div class="prose prose-sm max-w-none text-slate-700">
                                <x-ckeditor-display :model="$reunion" field="objectifs" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Planning et lieu -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                        Planning et Lieu
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Date</label>
                            <p class="text-lg font-semibold text-slate-900">{{ \Carbon\Carbon::parse($reunion->date_reunion)->format('l d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Heure prévue</label>
                            <p class="text-lg text-slate-900">
                                {{ \Carbon\Carbon::parse($reunion->heure_debut_prevue)->format('H:i') }}
                                @if($reunion->heure_fin_prevue)
                                    - {{ \Carbon\Carbon::parse($reunion->heure_fin_prevue)->format('H:i') }}
                                @endif
                            </p>
                        </div>
                        @if($reunion->heure_debut_reelle)
                            <div>
                                <label class="block text-sm font-medium text-slate-600">Heure réelle</label>
                                <p class="text-lg text-slate-900">
                                    {{ \Carbon\Carbon::parse($reunion->heure_debut_reelle)->format('H:i') }}
                                    @if($reunion->heure_fin_reelle)
                                        - {{ \Carbon\Carbon::parse($reunion->heure_fin_reelle)->format('H:i') }}
                                    @endif
                                </p>
                            </div>
                        @endif
                        <div>
                            <label class="block text-sm font-medium text-slate-600">Lieu</label>
                            <p class="text-lg text-slate-900">{{ $reunion->lieu }}</p>
                        </div>
                    </div>

                    @if($reunion->adresse_complete)
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">Adresse complète</label>
                            <p class="text-slate-700">{{ $reunion->adresse_complete }}</p>
                        </div>
                    @endif

                    @if($reunion->salle)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-600">Salle</label>
                                <p class="text-slate-900">{{ $reunion->salle }}</p>
                            </div>
                            @if($reunion->capacite_salle)
                                <div>
                                    <label class="block text-sm font-medium text-slate-600">Capacité</label>
                                    <p class="text-slate-900">{{ $reunion->capacite_salle }} personnes</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Responsables -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-users text-purple-600 mr-2"></i>
                        Responsables et Équipe
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($reunion->organisateurPrincipal)
                            <div>
                                <label class="block text-sm font-medium text-slate-600">Organisateur principal</label>
                                <p class="text-lg text-slate-900">{{ $reunion->organisateurPrincipal->nom }} {{ $reunion->organisateurPrincipal->prenom }}</p>
                            </div>
                        @endif

                        @if($reunion->animateur)
                            <div>
                                <label class="block text-sm font-medium text-slate-600">Animateur</label>
                                <p class="text-lg text-slate-900">{{ $reunion->animateur->nom }} {{ $reunion->animateur->prenom }}</p>
                            </div>
                        @endif

                        @if($reunion->responsableTechnique)
                            <div>
                                <label class="block text-sm font-medium text-slate-600">Responsable technique</label>
                                <p class="text-lg text-slate-900">{{ $reunion->responsableTechnique->nom }} {{ $reunion->responsableTechnique->prenom }}</p>
                            </div>
                        @endif

                        @if($reunion->responsableAccueil)
                            <div>
                                <label class="block text-sm font-medium text-slate-600">Responsable accueil</label>
                                <p class="text-lg text-slate-900">{{ $reunion->responsableAccueil->nom }} {{ $reunion->responsableAccueil->prenom }}</p>
                            </div>
                        @endif
                    </div>

                    @if($reunion->equipe_organisation && count($reunion->equipe_organisation) > 0)
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">Équipe d'organisation</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($reunion->equipe_organisation as $membre)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                        {{ $membre }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($reunion->intervenants && count($reunion->intervenants) > 0)
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-2">Intervenants</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($reunion->intervenants as $intervenant)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                        {{ $intervenant }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Message et contenu -->
            @if($reunion->message_principal || $reunion->passage_biblique)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bible text-amber-600 mr-2"></i>
                            Message et Contenu
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($reunion->passage_biblique)
                            <div>
                                <label class="block text-sm font-medium text-slate-600">Passage biblique</label>
                                <p class="text-lg font-semibold text-slate-900">{{ $reunion->passage_biblique }}</p>
                            </div>
                        @endif

                        @if($reunion->message_principal)
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-2">Message principal</label>
                                <div class="prose prose-sm max-w-none text-slate-700">
                                    {!! nl2br(e($reunion->message_principal)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Participants et statistiques -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-bar text-cyan-600 mr-2"></i>
                        Participants et Statistiques
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $reunion->nombre_inscrits }}</div>
                            <div class="text-sm text-slate-600">Inscrits</div>
                        </div>
                        @if($reunion->nombre_participants_reel)
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $reunion->nombre_participants_reel }}</div>
                                <div class="text-sm text-slate-600">Présents</div>
                            </div>
                        @endif
                        @if($reunion->nombre_nouveaux)
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ $reunion->nombre_nouveaux }}</div>
                                <div class="text-sm text-slate-600">Nouveaux</div>
                            </div>
                        @endif
                        @if($reunion->nombre_decisions)
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-600">{{ $reunion->nombre_decisions }}</div>
                                <div class="text-sm text-slate-600">Décisions</div>
                            </div>
                        @endif
                    </div>

                    @if($reunion->statut === 'terminee' && $reunion->note_globale)
                        <div class="border-t border-slate-200 pt-6">
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">Évaluation</h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <div class="text-xl font-bold text-amber-600">{{ $reunion->note_globale }}/10</div>
                                    <div class="text-sm text-slate-600">Note globale</div>
                                </div>
                                @if($reunion->note_contenu)
                                    <div class="text-center">
                                        <div class="text-xl font-bold text-amber-600">{{ $reunion->note_contenu }}/10</div>
                                        <div class="text-sm text-slate-600">Contenu</div>
                                    </div>
                                @endif
                                @if($reunion->note_organisation)
                                    <div class="text-center">
                                        <div class="text-xl font-bold text-amber-600">{{ $reunion->note_organisation }}/10</div>
                                        <div class="text-sm text-slate-600">Organisation</div>
                                    </div>
                                @endif
                                @if($reunion->taux_satisfaction)
                                    <div class="text-center">
                                        <div class="text-xl font-bold text-amber-600">{{ $reunion->taux_satisfaction }}%</div>
                                        <div class="text-sm text-slate-600">Satisfaction</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($reunion->statut === 'en_cours' || ($reunion->statut === 'terminee' && !$reunion->nombre_participants_reel))
                        <div class="border-t border-slate-200 pt-6">
                            <button onclick="openPresencesModal('{{$reunion->id}}', '{{$reunion}}')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-user-check mr-2"></i> Marquer les présences
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informations rapides -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800">Informations Rapides</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Jours restants:</span>
                        <span class="font-semibold text-slate-900">{{ $reunion->jours_restants ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Diffusion en ligne:</span>
                        <span class="text-sm {{ $reunion->diffusion_en_ligne ? 'text-green-600' : 'text-slate-500' }}">
                            {{ $reunion->diffusion_en_ligne ? 'Oui' : 'Non' }}
                        </span>
                    </div>
                    @if($reunion->lien_diffusion)
                        <div>
                            <label class="block text-sm text-slate-600 mb-1">Lien diffusion:</label>
                            <a href="{{ $reunion->lien_diffusion }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-external-link-alt mr-1"></i> Ouvrir
                            </a>
                        </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Enregistrement:</span>
                        <span class="text-sm {{ $reunion->enregistrement_autorise ? 'text-green-600' : 'text-slate-500' }}">
                            {{ $reunion->enregistrement_autorise ? 'Autorisé' : 'Non autorisé' }}
                        </span>
                    </div>
                    @if($reunion->preparation_terminee !== null)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Préparation:</span>
                            <span class="text-sm {{ $reunion->preparation_terminee ? 'text-green-600' : 'text-orange-600' }}">
                                {{ $reunion->preparation_terminee ? 'Terminée' : 'En cours' }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions récentes -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800">Actions Rapides</h2>
                </div>
                <div class="p-6 space-y-3">
                    @if($reunion->statut === 'terminee' && !$reunion->note_globale)
                        <button onclick="openEvaluationModal('{{$reunion->id}}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-xl hover:bg-amber-700 transition-colors">
                            <i class="fas fa-star mr-2"></i> Évaluer la réunion
                        </button>
                    @endif

                    @if(in_array($reunion->statut, ['en_cours', 'terminee']))
                        @can('reunions.create')
                        <button onclick="openTemoignageModal('{{$reunion->id}}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                            <i class="fas fa-heart mr-2"></i> Ajouter témoignage
                        </button>
                        @endcan

                        <button onclick="openPriereModal('{{$reunion->id}}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                            <i class="fas fa-praying-hands mr-2"></i> Demande de prière
                        </button>
                    @endif
                    @can('reunions.create')
                    <button onclick="openRappelModal('{{$reunion->id}}', '{{$reunion}}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-xl hover:bg-cyan-700 transition-colors">
                        <i class="fas fa-bell mr-2"></i> Envoyer rappel
                    </button>
                    @endcan
                    @can('reunions.create')
                    @if(!$reunion->est_recurrente)
                        <button onclick="openRecurrenceModal('{{$reunion->id}}', '{{$reunion}}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-repeat mr-2"></i> Créer récurrence
                        </button>
                    @endif
                    @endcan
                </div>
            </div>

            <!-- Documents et médias -->
            @if($reunion->documents_annexes || $reunion->photos_reunion)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800">Documents et Médias</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($reunion->documents_annexes && count($reunion->documents_annexes) > 0)
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-2">Documents</label>
                                <div class="space-y-2">
                                    @foreach($reunion->documents_annexes as $document)
                                        <a href="{{ $document['url'] ?? '#' }}" class="flex items-center p-2 bg-slate-50 rounded-lg hover:bg-slate-100 transition-colors">
                                            <i class="fas fa-file text-slate-400 mr-2"></i>
                                            <span class="text-sm text-slate-700">{{ $document['nom'] ?? 'Document sans nom' }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($reunion->photos_reunion && count($reunion->photos_reunion) > 0)
                            <div>
                                <label class="block text-sm font-medium text-slate-600 mb-2">Photos</label>
                                <div class="grid grid-cols-2 gap-2">
                                    @foreach(array_slice($reunion->photos_reunion, 0, 4) as $photo)
                                        <img src="{{ $photo['url'] ?? '' }}" alt="Photo de la réunion" class="w-full h-16 object-cover rounded-lg">
                                    @endforeach
                                </div>
                                @if(count($reunion->photos_reunion) > 4)
                                    <p class="text-sm text-slate-500 mt-2">+{{ count($reunion->photos_reunion) - 4 }} autres photos</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals -->
@can('reunions.create')
@include('components.private.reunions.modals.annuler', $reunion)

@include('components.private.reunions.modals.reporter', $reunion)

@include('components.private.reunions.modals.suspendre', $reunion)

@include('components.private.reunions.modals.duplicate', $reunion)

@include('components.private.reunions.modals.presences', $reunion)

@include('components.private.reunions.modals.evaluation', $reunion)

@include('components.private.reunions.modals.temoignage', $reunion)

@include('components.private.reunions.modals.priere', $reunion)

@include('components.private.reunions.modals.rappel', $reunion)

@include('components.private.reunions.modals.recurrence', $reunion)
@endcan



@push('scripts')
<script>
// Fonctions globales pour les modals et actions
function changerStatut(reunionId, action) {

        const actions = {
            'confirmer': 'confirmer',
            'commencer': 'commencer',
            'terminer': 'terminer',
            'reprendre': 'reprendre'
        };

    if (!actions[action]) return;
    let route = `{{route('private.reunions.confirmer', ':action')}}`.replace(':action', reunionId).replace('confirmer', actions[action]);

    fetch(route, {
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
</script>
@endpush
@endsection
