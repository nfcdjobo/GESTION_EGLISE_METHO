@extends('layouts.private.main')
@section('title', 'Confirmation en Masse - ' . $culte->titre)

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Confirmation en Masse
                </h1>
                <p class="text-slate-500 mt-1">{{ $culte->titre }} - {{ \Carbon\Carbon::parse($culte->date_culte)->format('l d F Y') }}</p>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.cultes.index') }}"
                                class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-church mr-2"></i>
                                Cultes
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <a href="{{ route('private.cultes.show', $culte) }}"
                                    class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">{{ $culte->titre }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">Confirmation en masse</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Actions rapides -->
            <div class="flex items-center space-x-2">
                <a href="{{ route('private.cultes.show', $culte) }}"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Retour au culte
                </a>
            </div>
        </div>
    </div>

    <!-- Informations du culte -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Informations du Culte
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-4 rounded-xl">
                    <p class="text-sm text-blue-600 font-medium">Date et heure</p>
                    <p class="text-lg font-bold text-blue-800">
                        {{ \Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') }}
                    </p>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl">
                    <p class="text-sm text-green-600 font-medium">Participants inscrits</p>
                    <p class="text-lg font-bold text-green-800">{{ $totalParticipants ?? 0 }}</p>
                </div>
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 p-4 rounded-xl">
                    <p class="text-sm text-yellow-600 font-medium">Confirmés</p>
                    <p class="text-lg font-bold text-yellow-800">{{ $participantsConfirmes ?? 0 }}</p>
                </div>
                <div class="bg-gradient-to-r from-red-50 to-pink-50 p-4 rounded-xl">
                    <p class="text-sm text-red-600 font-medium">En attente</p>
                    <p class="text-lg font-bold text-red-800">{{ $participantsEnAttente ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et options -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-filter text-purple-600 mr-2"></i>
                Filtres et Options
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut confirmation</label>
                    <select id="filterConfirmation" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="confirme">Confirmés</option>
                        <option value="non_confirme">Non confirmés</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut présence</label>
                    <select id="filterPresence" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="present">Présent</option>
                        <option value="present_partiel">Présent partiel</option>
                        <option value="en_retard">En retard</option>
                        <option value="parti_tot">Parti tôt</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type participation</label>
                    <select id="filterType" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="physique">Physique</option>
                        <option value="en_ligne">En ligne</option>
                        <option value="hybride">Hybride</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Rôle</label>
                    <select id="filterRole" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="participant">Participant</option>
                        <option value="equipe_technique">Équipe technique</option>
                        <option value="equipe_louange">Équipe louange</option>
                        <option value="equipe_accueil">Équipe accueil</option>
                        <option value="nouveau_visiteur">Nouveau visiteur</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <input type="text" id="searchParticipant" placeholder="Nom, prénom..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="resetFilters()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions en masse -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-tasks text-green-600 mr-2"></i>
                    Actions en Masse
                </h2>
                <div class="flex items-center space-x-2">
                    <span id="selectedCount" class="text-sm text-slate-600">0 sélectionné(s)</span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button type="button" onclick="confirmerSelectionnes()" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 disabled:opacity-50" disabled id="btnConfirmer">
                    <i class="fas fa-check mr-2"></i> Confirmer présences
                </button>

                <button type="button" onclick="modifierStatutSelectionnes()" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200 disabled:opacity-50" disabled id="btnModifierStatut">
                    <i class="fas fa-edit mr-2"></i> Modifier statut
                </button>

                <button type="button" onclick="marquerAbsents()" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white text-sm font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 disabled:opacity-50" disabled id="btnAbsents">
                    <i class="fas fa-times mr-2"></i> Marquer absents
                </button>

                <button type="button" onclick="exportSelection()" class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 disabled:opacity-50" disabled id="btnExporter">
                    <i class="fas fa-download mr-2"></i> Exporter sélection
                </button>
            </div>

            <!-- Actions rapides -->
            <div class="mt-6 pt-6 border-t border-slate-200">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Actions Rapides</h3>
                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick="selectionnerTous()" class="inline-flex items-center px-3 py-2 bg-slate-100 text-slate-700 text-sm rounded-lg hover:bg-slate-200 transition-colors">
                        <i class="fas fa-check-square mr-2"></i> Tout sélectionner
                    </button>
                    <button type="button" onclick="deselectionnerTous()" class="inline-flex items-center px-3 py-2 bg-slate-100 text-slate-700 text-sm rounded-lg hover:bg-slate-200 transition-colors">
                        <i class="fas fa-square mr-2"></i> Tout désélectionner
                    </button>
                    <button type="button" onclick="selectionnerNonConfirmes()" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 text-sm rounded-lg hover:bg-red-200 transition-colors">
                        <i class="fas fa-clock mr-2"></i> Sélectionner non confirmés
                    </button>
                    <button type="button" onclick="selectionnerNouveauxVisiteurs()" class="inline-flex items-center px-3 py-2 bg-purple-100 text-purple-700 text-sm rounded-lg hover:bg-purple-200 transition-colors">
                        <i class="fas fa-star mr-2"></i> Sélectionner nouveaux visiteurs
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des participants -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-amber-600 mr-2"></i>
                    Liste des Participants (<span id="totalVisible">{{ count($participants ?? []) }}</span>)
                </h2>
                <div class="flex items-center space-x-2">
                    <label class="flex items-center text-sm">
                        <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mr-2">
                        Sélectionner tout
                    </label>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if(isset($participants) && count($participants) > 0)
                <div id="participantsList" class="space-y-4">
                    @foreach($participants as $participant)
                        <div class="participant-item bg-gradient-to-r from-slate-50 to-white border border-slate-200 rounded-xl p-6 hover:shadow-md transition-all duration-300"
                             data-confirmation="{{ $participant->presence_confirmee ? 'confirme' : 'non_confirme' }}"
                             data-presence="{{ $participant->statut_presence }}"
                             data-type="{{ $participant->type_participation }}"
                             data-role="{{ $participant->role_culte }}"
                             data-nom="{{ strtolower($participant->participant->nom ?? '') }} {{ strtolower($participant->participant->prenom ?? '') }}">

                            <!-- Header avec checkbox et informations principales -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" name="participants[]" value="{{ $participant->participant_id }}"
                                           class="participant-checkbox w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mt-1"
                                           data-participant-id="{{ $participant->participant_id }}">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-slate-900 mb-1">
                                            {{ $participant->participant->nom ?? 'N/A' }} {{ $participant->participant->prenom ?? '' }}
                                        </h3>
                                        <div class="flex flex-wrap gap-2 text-sm text-slate-600">
                                            @if($participant->participant->email)
                                                <span class="flex items-center">
                                                    <i class="fas fa-envelope w-4 mr-1"></i>
                                                    {{ $participant->participant->email }}
                                                </span>
                                            @endif
                                            @if($participant->participant->telephone_1)
                                                <span class="flex items-center">
                                                    <i class="fas fa-phone w-4 mr-1"></i>
                                                    {{ $participant->participant->telephone_1 }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end space-y-2">
                                    <!-- Statut de confirmation -->
                                    @if($participant->presence_confirmee)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i> Confirmé
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-clock mr-1"></i> En attente
                                        </span>
                                    @endif

                                    <!-- Badges spéciaux -->
                                    @if($participant->premiere_visite)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-star mr-1"></i> Nouvelle visite
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Détails de participation -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div class="bg-slate-100 p-3 rounded-lg">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Statut présence</label>
                                    <select class="statut-presence w-full text-sm px-2 py-1 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            data-participant-id="{{ $participant->participant_id }}"
                                            data-original="{{ $participant->statut_presence }}">
                                        <option value="present" {{ $participant->statut_presence == 'present' ? 'selected' : '' }}>Présent</option>
                                        <option value="present_partiel" {{ $participant->statut_presence == 'present_partiel' ? 'selected' : '' }}>Présent partiel</option>
                                        <option value="en_retard" {{ $participant->statut_presence == 'en_retard' ? 'selected' : '' }}>En retard</option>
                                        <option value="parti_tot" {{ $participant->statut_presence == 'parti_tot' ? 'selected' : '' }}>Parti tôt</option>
                                    </select>
                                </div>

                                <div class="bg-slate-100 p-3 rounded-lg">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Type participation</label>
                                    <select class="type-participation w-full text-sm px-2 py-1 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            data-participant-id="{{ $participant->participant_id }}"
                                            data-original="{{ $participant->type_participation }}">
                                        <option value="physique" {{ $participant->type_participation == 'physique' ? 'selected' : '' }}>Physique</option>
                                        <option value="en_ligne" {{ $participant->type_participation == 'en_ligne' ? 'selected' : '' }}>En ligne</option>
                                        <option value="hybride" {{ $participant->type_participation == 'hybride' ? 'selected' : '' }}>Hybride</option>
                                    </select>
                                </div>

                                <div class="bg-slate-100 p-3 rounded-lg">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Heure arrivée</label>
                                    <input type="time" class="heure-arrivee w-full text-sm px-2 py-1 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           value="{{ $participant->heure_arrivee ? \Carbon\Carbon::parse($participant->heure_arrivee)->format('H:i') : '' }}"
                                           data-participant-id="{{ $participant->participant_id }}">
                                </div>

                                <div class="bg-slate-100 p-3 rounded-lg">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Heure départ</label>
                                    <input type="time" class="heure-depart w-full text-sm px-2 py-1 border border-slate-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           value="{{ $participant->heure_depart ? \Carbon\Carbon::parse($participant->heure_depart)->format('H:i') : '' }}"
                                           data-participant-id="{{ $participant->participant_id }}">
                                </div>
                            </div>

                            <!-- Informations complémentaires -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Rôle</label>
                                    <span class="text-sm text-slate-700">{{ $participant->role_culte_libelle ?? $participant->role_culte }}</span>
                                </div>

                                @if($participant->accompagnateur)
                                    <div>
                                        <label class="block text-xs font-medium text-slate-500 mb-1">Accompagné par</label>
                                        <span class="text-sm text-slate-700">{{ $participant->accompagnateur->nom }} {{ $participant->accompagnateur->prenom }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Besoins de suivi -->
                            @if($participant->demande_contact_pastoral || $participant->interesse_bapteme || $participant->souhaite_devenir_membre)
                                <div class="mt-4 pt-4 border-t border-slate-200">
                                    <h4 class="text-sm font-semibold text-slate-700 mb-2">Besoins de suivi :</h4>
                                    <div class="flex flex-wrap gap-1">
                                        @if($participant->demande_contact_pastoral)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-praying-hands mr-1"></i> Contact pastoral
                                            </span>
                                        @endif
                                        @if($participant->interesse_bapteme)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-cyan-100 text-cyan-800">
                                                <i class="fas fa-water mr-1"></i> Baptême
                                            </span>
                                        @endif
                                        @if($participant->souhaite_devenir_membre)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-heart mr-1"></i> Devenir membre
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Actions individuelles -->
                            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                <div class="flex items-center space-x-2">
                                    @can('participants-cultes.confirm-presence')
                                    @if(!$participant->presence_confirmee)
                                        <button type="button" onclick="confirmerParticipant('{{ $participant->participant_id }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                                                title="Confirmer présence">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                    @endif
                                    @endcan

                                    @can('participants-cultes.update')
                                    <button type="button" onclick="sauvegarderParticipant('{{ $participant->participant_id }}')"
                                            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors"
                                            title="Sauvegarder modifications">
                                        <i class="fas fa-save text-sm"></i>
                                    </button>
                                    @endcan

                                    <a href="{{ route('private.participantscultes.show', [$participant->participant_id, $participant->culte_id]) }}"
                                       class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                       title="Voir détails">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                </div>

                                <div class="flex items-center space-x-2 text-xs text-slate-500">
                                    @if($participant->confirmateur)
                                        <span>Confirmé par {{ $participant->confirmateur->prenom }} {{ $participant->confirmateur->nom }}</span>
                                    @endif
                                    <span>{{ $participant->created_at ? $participant->created_at->diffForHumans() : 'Date inconnue' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Message si aucun participant visible après filtrage -->
                <div id="noParticipants" class="text-center py-12 hidden">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-filter text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun participant correspondant</h3>
                    <p class="text-slate-500 mb-6">Aucun participant ne correspond aux filtres sélectionnés.</p>
                    <button type="button" onclick="resetFilters()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser les filtres
                    </button>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun participant inscrit</h3>
                    <p class="text-slate-500 mb-6">Aucun participant n'est encore inscrit à ce culte.</p>
                    @can('participants-cultes.nouveaux-visiteurs')
                    <a href="{{ route('private.cultes.participants', $culte) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i> Ajouter des participants
                    </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de modification de statut en masse -->
<div id="statutModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Modifier le statut en masse</h3>
            <form id="statutForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau statut de présence</label>
                        <select name="statut_presence" id="nouveauStatut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="present">Présent</option>
                            <option value="present_partiel">Présent partiel</option>
                            <option value="en_retard">En retard</option>
                            <option value="parti_tot">Parti tôt</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type de participation</label>
                        <select name="type_participation" id="nouveauType" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Ne pas modifier</option>
                            <option value="physique">Physique</option>
                            <option value="en_ligne">En ligne</option>
                            <option value="hybride">Hybride</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="confirmer_presence" id="confirmerPresence" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="confirmerPresence" class="ml-2 text-sm text-slate-700">Confirmer automatiquement les présences</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeStatutModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            @can('participants-cultes.update')
            <button type="button" onclick="appliquerStatut()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Appliquer
            </button>
            @endcan
        </div>
    </div>
</div>

@push('scripts')
<script>
let selectedParticipants = [];

// Gestion de la sélection
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.participant-checkbox:not([style*="display: none"])');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelection();
});

// Gestion des filtres
function setupFilters() {
    const filters = ['filterConfirmation', 'filterPresence', 'filterType', 'filterRole'];
    filters.forEach(filterId => {
        document.getElementById(filterId).addEventListener('change', applyFilters);
    });

    document.getElementById('searchParticipant').addEventListener('input', debounce(applyFilters, 300));
}

function applyFilters() {
    const confirmation = document.getElementById('filterConfirmation').value;
    const presence = document.getElementById('filterPresence').value;
    const type = document.getElementById('filterType').value;
    const role = document.getElementById('filterRole').value;
    const search = document.getElementById('searchParticipant').value.toLowerCase();

    const participants = document.querySelectorAll('.participant-item');
    let visibleCount = 0;

    participants.forEach(participant => {
        let visible = true;

        // Filtre confirmation
        if (confirmation && participant.dataset.confirmation !== confirmation) {
            visible = false;
        }

        // Filtre présence
        if (presence && participant.dataset.presence !== presence) {
            visible = false;
        }

        // Filtre type
        if (type && participant.dataset.type !== type) {
            visible = false;
        }

        // Filtre rôle
        if (role && participant.dataset.role !== role) {
            visible = false;
        }

        // Filtre recherche
        if (search && !participant.dataset.nom.includes(search)) {
            visible = false;
        }

        participant.style.display = visible ? 'block' : 'none';
        if (visible) visibleCount++;

        // Décocher si masqué
        if (!visible) {
            const checkbox = participant.querySelector('.participant-checkbox');
            if (checkbox) checkbox.checked = false;
        }
    });

    // Mettre à jour le compteur
    document.getElementById('totalVisible').textContent = visibleCount;

    // Afficher/masquer le message "aucun participant"
    const noParticipants = document.getElementById('noParticipants');
    if (noParticipants) {
        noParticipants.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    updateSelection();
}

function resetFilters() {
    document.getElementById('filterConfirmation').value = '';
    document.getElementById('filterPresence').value = '';
    document.getElementById('filterType').value = '';
    document.getElementById('filterRole').value = '';
    document.getElementById('searchParticipant').value = '';
    applyFilters();
}

// Gestion de la sélection
function updateSelection() {
    const checkboxes = document.querySelectorAll('.participant-checkbox:checked');
    selectedParticipants = Array.from(checkboxes).map(cb => cb.value);

    const count = selectedParticipants.length;
    document.getElementById('selectedCount').textContent = `${count} sélectionné(s)`;

    // Activer/désactiver les boutons d'action
    const buttons = ['btnConfirmer', 'btnModifierStatut', 'btnAbsents', 'btnExporter'];
    buttons.forEach(btnId => {
        const btn = document.getElementById(btnId);
        if (btn) {
            btn.disabled = count === 0;
            btn.classList.toggle('opacity-50', count === 0);
        }
    });
}

function selectionnerTous() {
    const checkboxes = document.querySelectorAll('.participant-checkbox:not([style*="display: none"])');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    document.getElementById('selectAll').checked = true;
    updateSelection();
}

function deselectionnerTous() {
    const checkboxes = document.querySelectorAll('.participant-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    document.getElementById('selectAll').checked = false;
    updateSelection();
}

function selectionnerNonConfirmes() {
    deselectionnerTous();
    const participants = document.querySelectorAll('.participant-item[data-confirmation="non_confirme"]:not([style*="display: none"])');
    participants.forEach(participant => {
        const checkbox = participant.querySelector('.participant-checkbox');
        if (checkbox) checkbox.checked = true;
    });
    updateSelection();
}

function selectionnerNouveauxVisiteurs() {
    deselectionnerTous();
    const participants = document.querySelectorAll('.participant-item:not([style*="display: none"])');
    participants.forEach(participant => {
        const badge = participant.querySelector('.bg-purple-100');
        if (badge && badge.textContent.includes('Nouvelle visite')) {
            const checkbox = participant.querySelector('.participant-checkbox');
            if (checkbox) checkbox.checked = true;
        }
    });
    updateSelection();
}

// Actions en masse
function confirmerSelectionnes() {
    if (selectedParticipants.length === 0) {
        alert('Veuillez sélectionner au moins un participant');
        return;
    }

    if (confirm(`Confirmer la présence de ${selectedParticipants.length} participant(s) ?`)) {
        fetch('{{ route("private.participantscultes.confirmer-masse") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                participants: selectedParticipants,
                culte_id: '{{ $culte->id }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(`${data.confirmes || selectedParticipants.length} présence(s) confirmée(s)`, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message || 'Une erreur est survenue', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Une erreur est survenue', 'error');
        });
    }
}

function modifierStatutSelectionnes() {
    if (selectedParticipants.length === 0) {
        alert('Veuillez sélectionner au moins un participant');
        return;
    }

    document.getElementById('statutModal').classList.remove('hidden');
}

function marquerAbsents() {
    if (selectedParticipants.length === 0) {
        alert('Veuillez sélectionner au moins un participant');
        return;
    }

    if (confirm(`Marquer ${selectedParticipants.length} participant(s) comme absent(s) ?`)) {
        // Logique pour marquer comme absents
        console.log('Marquer absents:', selectedParticipants);
        showNotification('Fonctionnalité à implémenter', 'info');
    }
}

function exportSelection() {
    if (selectedParticipants.length === 0) {
        alert('Veuillez sélectionner au moins un participant');
        return;
    }

    // Logique d'export de la sélection
    console.log('Export sélection:', selectedParticipants);
    showNotification('Export en cours...', 'info');
}

// Modal de statut
function closeStatutModal() {
    document.getElementById('statutModal').classList.add('hidden');
}

function appliquerStatut() {
    const form = document.getElementById('statutForm');
    const formData = new FormData(form);

    const data = {
        participants: selectedParticipants,
        culte_id: '{{ $culte->id }}',
        statut_presence: formData.get('statut_presence'),
        type_participation: formData.get('type_participation'),
        confirmer_presence: formData.get('confirmer_presence') === '1'
    };

    fetch('{{ route("private.participantscultes.modifier-statut-masse") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`${data.modifies || selectedParticipants.length} participant(s) modifié(s)`, 'success');
            closeStatutModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Une erreur est survenue', 'error');
    });
}

// Actions individuelles
function confirmerParticipant(participantId) {
    fetch(`{{ route("private.participantscultes.confirmer-presence", ["PARTICIPANT_ID", $culte->id]) }}`.replace('PARTICIPANT_ID', participantId), {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Présence confirmée', 'success');
            // Mettre à jour l'affichage local
            updateParticipantDisplay(participantId, 'confirmed');
        } else {
            showNotification(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Une erreur est survenue', 'error');
    });
}

function sauvegarderParticipant(participantId) {
    const participant = document.querySelector(`[data-participant-id="${participantId}"]`).closest('.participant-item');

    const data = {
        statut_presence: participant.querySelector('.statut-presence').value,
        type_participation: participant.querySelector('.type-participation').value,
        heure_arrivee: participant.querySelector('.heure-arrivee').value,
        heure_depart: participant.querySelector('.heure-depart').value
    };

    fetch(`{{ route("private.participantscultes.update", ["PARTICIPANT_ID", $culte->id]) }}`.replace('PARTICIPANT_ID', participantId), {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Modifications sauvegardées', 'success');
        } else {
            showNotification(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showNotification('Une erreur est survenue', 'error');
    });
}

function updateParticipantDisplay(participantId, action) {
    const participant = document.querySelector(`[data-participant-id="${participantId}"]`).closest('.participant-item');
    if (action === 'confirmed') {
        // Mettre à jour le badge de confirmation
        const badge = participant.querySelector('.bg-red-100');
        if (badge) {
            badge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
            badge.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Confirmé';
        }
        participant.dataset.confirmation = 'confirme';
    }
}

// Utilitaires
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    setupFilters();

    // Gestion des changements de sélection
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('participant-checkbox')) {
            updateSelection();
        }
    });

    // Fermer le modal en cliquant à l'extérieur
    document.getElementById('statutModal').addEventListener('click', function(e) {
        if (e.target === this) closeStatutModal();
    });
});
</script>
@endpush
@endsection
