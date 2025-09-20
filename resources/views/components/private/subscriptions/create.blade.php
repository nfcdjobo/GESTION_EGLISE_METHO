@extends('layouts.private.main')
@section('title', 'Nouvelle Souscription')

@section('content')
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Nouvelle Souscription
                </h1>
                <p class="text-slate-500 mt-1">
                    Créer une nouvelle souscription à un FIMECO
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('private.subscriptions.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>

        <!-- Alert d'information -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Information importante</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Une souscription lie un utilisateur à un FIMECO avec un montant défini. Une fois créée, le souscripteur pourra effectuer des paiements pour atteindre le montant souscrit.</p>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('private.subscriptions.store') }}"
              class="space-y-8"
              id="subscriptionForm">
            @csrf

            <!-- Informations principales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                        Informations de la souscription
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">
                        Sélectionnez le souscripteur concerné
                    </p>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Sélection du FIMECO -->
                        <div>
                            <label for="fimeco_id" class="block text-sm font-medium text-slate-700 mb-2">
                                FIMECO *
                            </label>

                            <select id="fimeco_id" required disabled class="appearance-none w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fimeco_id') border-red-500 @enderror">

                                    <option value="{{ $fimeco->id }}"
                                            data-cible="{{ $fimeco->cible }}"
                                            data-collecte="{{ $fimeco->montant_solde }}"
                                            data-progression="{{ $fimeco->progression }}"
                                            data-fin="{{ $fimeco->fin->format('Y-m-d') }}"
                                            selected>
                                        {{ $fimeco->nom }} - {{ number_format($fimeco->cible, 0, ',', ' ') }} FCFA ({{ number_format($fimeco->progression, 1) }}%)
                                    </option>
                            </select>
                            @error('fimeco_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <input type="hidden" name="fimeco_id" value="{{ $fimeco->id }}"  required>

                            <!-- Informations FIMECO sélectionné -->
                            <div id="fimecoInfo" class="hidden mt-3 p-4 bg-slate-50 rounded-xl">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-slate-600">Objectif:</span>
                                        <span class="font-medium text-slate-900" id="fimecoCible">-</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-600">Collecté:</span>
                                        <span class="font-medium text-slate-900" id="fimecoCollecte">-</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-600">Progression:</span>
                                        <span class="font-medium text-slate-900" id="fimecoProgression">-</span>
                                    </div>
                                    <div>
                                        <span class="text-slate-600">Fin:</span>
                                        <span class="font-medium text-slate-900" id="fimecoFin">-</span>
                                    </div>
                                </div>

                                <!-- Barre de progression -->
                                <div class="mt-3">
                                    <div class="flex items-center justify-between text-sm mb-1">
                                        <span class="text-slate-600">Progression</span>
                                        <span class="font-medium" id="fimecoProgressionText">0%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div id="fimecoProgressionBar" class="h-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- <!-- Sélection du souscripteur -->
                        <div>
                            <label for="souscripteur_id" class="block text-sm font-medium text-slate-700 mb-2">
                                Souscripteur *
                            </label>
                            <select name="souscripteur_id" id="souscripteur_id" required class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('souscripteur_id') border-red-500 @enderror">
                                <option value="">Sélectionnez un souscripteur</option>
                                @foreach($souscripteursPossibles as $user)
                                    <option value="{{ $user->id }}" {{ old('souscripteur_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->nom }} {{ $user->prenom }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('souscripteur_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div> --}}


<!-- Remplacer la section "Sélection du souscripteur" par ce code -->
<div class="relative">
    <label for="souscripteur_search" class="block text-sm font-medium text-slate-700 mb-2">
        Souscripteur *
    </label>

    <!-- Champ de recherche visible -->
    <div class="relative">
        <input type="text"
               id="souscripteur_search"
               placeholder="Tapez pour rechercher un souscripteur..."
               autocomplete="off"
               class="w-full px-4 py-3 pr-10 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('souscripteur_id') border-red-500 @enderror disabled:bg-slate-50 disabled:text-slate-400 disabled:cursor-not-allowed">

        <!-- Icône de recherche -->
        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Champ hidden pour la valeur réelle -->
    <input type="hidden" name="souscripteur_id" id="souscripteur_id" required>

    <!-- Dropdown des résultats -->
    <div id="souscripteur_dropdown"
         class="hidden absolute z-50 w-full mt-1 bg-white border border-slate-300 rounded-xl shadow-xl max-h-60 overflow-y-auto transform transition-all duration-200 origin-top scale-95 opacity-0">

        <!-- Indicateur de chargement -->
        <div id="loading_indicator" class="hidden p-4 text-center text-slate-500">
            <div class="flex items-center justify-center space-x-2">
                <div class="animate-spin rounded-full h-4 w-4 border-2 border-slate-200 border-t-blue-500"></div>
                <span class="text-sm">Recherche en cours...</span>
            </div>
        </div>

        <!-- Message aucun résultat -->
        <div id="no_results" class="hidden p-4 text-center text-slate-500">
            <div class="flex items-center justify-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.09-2.33"></path>
                </svg>
                <span class="text-sm">Aucun souscripteur trouvé</span>
            </div>
        </div>

        <!-- Liste des résultats -->
        <ul id="souscripteur_results" class="divide-y divide-slate-100">
            <!-- Les résultats seront insérés ici dynamiquement -->
        </ul>
    </div>

    <!-- Souscripteur sélectionné -->
    <div id="selected_souscripteur" class="hidden mt-3 p-3 bg-green-50 border border-green-200 rounded-lg transform transition-all duration-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <div class="font-medium text-green-800" id="selected_name">-</div>
                    <div class="text-sm text-green-600" id="selected_details">-</div>
                </div>
            </div>
            <button type="button" onclick="clearSelection()"
                    class="text-green-600 hover:text-green-800 transition-colors transform hover:rotate-90 duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    @error('souscripteur_id')
        <p class="mt-1 text-sm text-red-600 animate-pulse">{{ $message }}</p>
    @enderror
</div>



                    </div>

                    <!-- Vérification d'unicité -->
                    <div id="subscriptionExistsAlert" class="hidden p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Attention</h3>
                                <div class="mt-1 text-sm text-red-700">
                                    Ce souscripteur a déjà une souscription pour ce FIMECO.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails financiers -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-coins text-green-600 mr-2"></i>
                        Détails financiers
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">
                        Définissez le montant de la souscription
                    </p>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Montant souscrit -->
                        <div>
                            <label for="montant_souscrit" class="block text-sm font-medium text-slate-700 mb-2">
                                Montant souscrit (FCFA) *
                            </label>
                            <div class="relative">
                                <input type="number" name="montant_souscrit" id="montant_souscrit"
                                       min="1000" step="500" required
                                       value="{{ old('montant_souscrit') }}"
                                       class="w-full pl-4 pr-16 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('montant_souscrit') border-red-500 @enderror"
                                       placeholder="100000">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-slate-500 text-sm">FCFA</span>
                                </div>
                            </div>
                            <div class="mt-1 text-xs text-slate-500">
                                Montant minimum: 1,000 FCFA
                            </div>
                            @error('montant_souscrit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <!-- Preview du montant -->
                            <div id="montantPreview" class="hidden mt-3 p-3 bg-green-50 rounded-lg">
                                <div class="text-sm text-green-800">
                                    <div class="font-medium">Aperçu de la souscription:</div>
                                    <div class="mt-1">
                                        <span id="montantFormatted">0</span> FCFA
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Suggestions de montant -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Suggestions de montant
                            </label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" onclick="setSuggestedAmount(10000)"
                                        class="px-3 py-2 text-sm bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                                    10,000 FCFA
                                </button>
                                <button type="button" onclick="setSuggestedAmount(25000)"
                                        class="px-3 py-2 text-sm bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                                    25,000 FCFA
                                </button>
                                <button type="button" onclick="setSuggestedAmount(50000)"
                                        class="px-3 py-2 text-sm bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                                    50,000 FCFA
                                </button>
                                <button type="button" onclick="setSuggestedAmount(100000)"
                                        class="px-3 py-2 text-sm bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                                    100,000 FCFA
                                </button>
                            </div>

                            <!-- Calcul automatique basé sur pourcentage -->
                            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                                <div class="text-sm text-blue-800">
                                    <div class="font-medium mb-2">Calcul automatique:</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" onclick="calculatePercentage(1)"
                                                class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                            1% de l'objectif
                                        </button>
                                        <button type="button" onclick="calculatePercentage(5)"
                                                class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                            5% de l'objectif
                                        </button>
                                        <button type="button" onclick="calculatePercentage(10)"
                                                class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                            10% de l'objectif
                                        </button>
                                        <button type="button" onclick="calculatePercentage(20)"
                                                class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors">
                                            20% de l'objectif
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planification -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar text-purple-600 mr-2"></i>
                        Planification
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">
                        Définissez les dates importantes
                    </p>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Date de souscription -->
                        <div>
                            <label for="date_souscription" class="block text-sm font-medium text-slate-700 mb-2">
                                Date de souscription *
                            </label>
                            <input type="date" name="date_souscription" id="date_souscription" required
                                   value="{{ old('date_souscription', now()->format('Y-m-d')) }}"
                                   max="{{ now()->format('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_souscription') border-red-500 @enderror">
                            @error('date_souscription')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date d'échéance -->
                        <div>
                            <label for="date_echeance" class="block text-sm font-medium text-slate-700 mb-2">
                                Date d'échéance (optionnelle)
                            </label>
                            <input type="date" name="date_echeance" id="date_echeance"
                                   value="{{ old('date_echeance') }}"
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_echeance') border-red-500 @enderror">
                            <div class="mt-1 text-xs text-slate-500">
                                Laissez vide si aucune échéance spécifique
                            </div>
                            @error('date_echeance')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Suggestions d'échéance -->
                    <div class="p-4 bg-purple-50 rounded-xl">
                        <div class="text-sm text-purple-800">
                            <div class="font-medium mb-2">Suggestions d'échéance:</div>
                            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                                <button type="button" onclick="setDeadlineSuggestion(30)"
                                        class="px-3 py-2 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition-colors">
                                    Dans 30 jours
                                </button>
                                <button type="button" onclick="setDeadlineSuggestion(60)"
                                        class="px-3 py-2 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition-colors">
                                    Dans 60 jours
                                </button>
                                <button type="button" onclick="setDeadlineSuggestion(90)"
                                        class="px-3 py-2 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition-colors">
                                    Dans 90 jours
                                </button>
                                <button type="button" onclick="setFimecoEndDate()"
                                        class="px-3 py-2 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200 transition-colors">
                                    Fin du FIMECO
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé et validation -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                        Résumé de la souscription
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">
                        Vérifiez les informations avant validation
                    </p>
                </div>

                <div class="p-6">
                    <div id="resumeSection" class="hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <div class="text-sm font-medium text-slate-700">Souscripteur:</div>
                                    <div class="text-slate-900" id="resumeSouscripteur">-</div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-700">FIMECO:</div>
                                    <div class="text-slate-900" id="resumeFimeco">-</div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-700">Date de souscription:</div>
                                    <div class="text-slate-900" id="resumeDateSouscription">-</div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <div class="text-sm font-medium text-slate-700">Montant souscrit:</div>
                                    <div class="text-lg font-bold text-green-600" id="resumeMontant">-</div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-700">Date d'échéance:</div>
                                    <div class="text-slate-900" id="resumeEcheance">Aucune</div>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-700">Impact sur le FIMECO:</div>
                                    <div class="text-sm text-blue-600" id="resumeImpact">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="resumePlaceholder" class="text-center py-8 text-slate-500">
                        <i class="fas fa-info-circle text-2xl mb-2"></i>
                        <div>Remplissez les champs ci-dessus pour voir le résumé</div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <a href="{{ route('private.subscriptions.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>

                <button type="button" onclick="validateForm()"
                        class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                    <i class="fas fa-check mr-2"></i> Valider les données
                </button>

                <button type="submit" id="submitButton" disabled
                        class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-save mr-2"></i> Créer la souscription
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        {{-- <script>
            let currentFimecoData = null;
            let formValidated = false;




            // Format number with thousands separator
            function formatNumber(num) {
                return new Intl.NumberFormat('fr-FR').format(num);
            }

            const fetchUsersNotSubscribedToFimeco = async (fimecoId, formSouscripteur) => {

                const response = await fetch("{{route('private.users.not-subscribed-to-fimeco', ':fimeco')}}".replace(':fimeco', fimecoId), {
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    }
                });

                const usersNotSubscribedToFimeco = await response.json();
                const {users} = usersNotSubscribedToFimeco;

                if(users.length){
                    let option = "";
                    users.forEach((user, key) => {
                        if(key === 0) option =`<option value="">Sélectionnez un souscripteur</option>`;
                        else option += `<option value="${ user.id }" >${ user.nom } ${ user.prenom } - ${ user.telephone_1 }</option>`
                    })

                    formSouscripteur.disabled = false;
                    formSouscripteur.innerHTML = option;

                }else{
                    formSouscripteur.innerHTML = `<option value="">Aucun membre disponible</option>`;
                    formSouscripteur.disabled = true;
                }
            }



                // Update FIMECO info when selection changes
                const fimeco = document.getElementById('fimeco_id');
                const selectedOption = fimeco.options[fimeco.selectedIndex];
                const fimecoInfo = document.getElementById('fimecoInfo');

                const formSouscripteur = document.getElementById('souscripteur_id');

                if (fimeco.value) {
                    currentFimecoData = {
                        cible: parseFloat(selectedOption.dataset.cible),
                        collecte: parseFloat(selectedOption.dataset.collecte),
                        progression: parseFloat(selectedOption.dataset.progression),
                        fin: selectedOption.dataset.fin
                    };



                    // Update info display
                    document.getElementById('fimecoCible').textContent = formatNumber(currentFimecoData.cible) + ' FCFA';
                    document.getElementById('fimecoCollecte').textContent = formatNumber(currentFimecoData.collecte) + ' FCFA';
                    document.getElementById('fimecoProgression').textContent = currentFimecoData.progression.toFixed(1) + '%';
                    document.getElementById('fimecoProgressionText').textContent = currentFimecoData.progression.toFixed(1) + '%';
                    document.getElementById('fimecoFin').textContent = new Date(currentFimecoData.fin).toLocaleDateString('fr-FR');

                    // Update progress bar
                    const progressBar = document.getElementById('fimecoProgressionBar');
                    const clampedProgress = Math.min(currentFimecoData.progression, 100);
                    progressBar.style.width = clampedProgress + '%';

                    // Update progress bar color
                    if (currentFimecoData.progression >= 100) {
                        progressBar.className = 'h-2 rounded-full bg-gradient-to-r from-green-500 to-emerald-500';
                    } else if (currentFimecoData.progression >= 75) {
                        progressBar.className = 'h-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500';
                    } else if (currentFimecoData.progression >= 50) {
                        progressBar.className = 'h-2 rounded-full bg-gradient-to-r from-yellow-500 to-orange-500';
                    } else {
                        progressBar.className = 'h-2 rounded-full bg-gradient-to-r from-red-500 to-pink-500';
                    }

                    fimecoInfo.classList.remove('hidden');

                    // Set max date for échéance
                    const dateEcheance = document.getElementById('date_echeance');
                    dateEcheance.max = currentFimecoData.fin;

                } else {
                    currentFimecoData = null;
                    fimecoInfo.classList.add('hidden');
                    formSouscripteur.innerHTML = `<option value="">Aucun membre disponible</option>`;
                    formSouscripteur.disabled = true;
                }

                checkForExistingSubscription();
                updateResume();


// Gestion des montants avec formatage en temps réel
document.getElementById('montant_souscrit').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');

    if (value) {
        if (parseInt(value) > 100000000) {
            value = '100000000';
        }

        e.target.value = value;

        const amount = parseFloat(value);
        const preview = document.getElementById('montantPreview');

        // Animation du preview
        if (amount >= 1000) {
            document.getElementById('montantFormatted').textContent = formatNumber(amount);
            preview.classList.remove('hidden');
            preview.classList.add('animate-pulse');
            setTimeout(() => preview.classList.remove('animate-pulse'), 1000);

            // Supprimer les styles d'erreur
            e.target.classList.remove('border-red-500', 'bg-red-50');
        } else {
            preview.classList.add('hidden');

            // Ajouter les styles d'erreur si montant insuffisant
            if (value) {
                e.target.classList.add('border-red-500', 'bg-red-50');
            }
        }
    }

    updateResume();
});


// Validation du montant en temps réel avec feedback visuel
document.getElementById('montant_souscrit').addEventListener('blur', function(e) {
    const amount = parseFloat(e.target.value);
    const container = e.target.parentNode;

    // Supprimer les anciens messages d'erreur
    const existingError = container.querySelector('.error-message');
    if (existingError) existingError.remove();

    if (e.target.value && amount < 1000) {
        const errorDiv = document.createElement('p');
        errorDiv.className = 'error-message mt-1 text-sm text-red-600 animate-pulse';
        errorDiv.textContent = 'Le montant minimum est de 1,000 FCFA';
        container.appendChild(errorDiv);

        e.target.classList.add('border-red-500', 'bg-red-50');

        // Supprimer l'erreur après 5 secondes
        setTimeout(() => {
            errorDiv.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            setTimeout(() => errorDiv.remove(), 300);
        }, 5000);
    } else {
        e.target.classList.remove('border-red-500', 'bg-red-50');
    }
});


            // Set suggested amounts
            function setSuggestedAmount(amount) {
                document.getElementById('montant_souscrit').value = amount;
                document.getElementById('montant_souscrit').dispatchEvent(new Event('input'));
            }

            // Calculate percentage of FIMECO target
            function calculatePercentage(percentage) {
                if (currentFimecoData) {
                    const amount = Math.round(currentFimecoData.cible * percentage / 100);
                    setSuggestedAmount(amount);
                } else {
                    alert('Veuillez d\'abord sélectionner un FIMECO');
                }
            }

            // Set deadline suggestions
            function setDeadlineSuggestion(days) {
                const date = new Date();
                date.setDate(date.getDate() + days);

                // Check if date exceeds FIMECO end date
                if (currentFimecoData) {
                    const fimecoEnd = new Date(currentFimecoData.fin);
                    if (date > fimecoEnd) {
                        date = fimecoEnd;
                    }
                }

                document.getElementById('date_echeance').value = date.toISOString().split('T')[0];
                updateResume();
            }

            function setFimecoEndDate() {
                if (currentFimecoData) {
                    document.getElementById('date_echeance').value = currentFimecoData.fin;
                    updateResume();
                } else {
                    alert('Veuillez d\'abord sélectionner un FIMECO');
                }
            }

// Fonction pour vérifier les souscriptions existantes avec feedback visuel
function checkForExistingSubscription() {
    const souscripteurId = document.getElementById('souscripteur_id').value;
    const fimecoId = document.getElementById('fimeco_id').value;
    const alert = document.getElementById('subscriptionExistsAlert');

    if (souscripteurId && fimecoId) {
        // Ajouter un indicateur de vérification
        const searchContainer = document.getElementById('souscripteur_search').parentNode;
        const checkingIndicator = document.createElement('div');
        checkingIndicator.id = 'checking_indicator';
        checkingIndicator.className = 'absolute right-12 top-1/2 transform -translate-y-1/2';
        checkingIndicator.innerHTML = '<div class="animate-spin h-4 w-4 border-2 border-slate-200 border-t-blue-500 rounded-full"></div>';
        searchContainer.appendChild(checkingIndicator);

        fetch(`/api/subscriptions/check-exists`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                souscripteur_id: souscripteurId,
                fimeco_id: fimecoId
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('checking_indicator')?.remove();

            if (data.exists) {
                alert.classList.remove('hidden');
                alert.classList.add('animate-pulse');
                document.getElementById('submitButton').disabled = true;
                document.getElementById('submitButton').classList.add('opacity-50', 'cursor-not-allowed');

                // Ajouter une classe d'erreur au champ de recherche
                document.getElementById('souscripteur_search').classList.add('border-red-500', 'bg-red-50');
            } else {
                alert.classList.add('hidden');
                alert.classList.remove('animate-pulse');

                // Supprimer les classes d'erreur
                document.getElementById('souscripteur_search').classList.remove('border-red-500', 'bg-red-50');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification:', error);
            document.getElementById('checking_indicator')?.remove();
            alert.classList.add('hidden');
        });
    } else {
        alert.classList.add('hidden');
        document.getElementById('souscripteur_search').classList.remove('border-red-500', 'bg-red-50');
    }
}

// Fonction mise à jour pour le résumé avec animations Tailwind
function updateResume() {
    const souscripteurInput = document.getElementById('souscripteur_id');
    const fimeco = document.getElementById('fimeco_id');
    const montant = document.getElementById('montant_souscrit').value;
    const dateSouscription = document.getElementById('date_souscription').value;
    const dateEcheance = document.getElementById('date_echeance').value;

    const resumeSection = document.getElementById('resumeSection');
    const resumePlaceholder = document.getElementById('resumePlaceholder');

    if (souscripteurInput.value && fimeco.value && montant && dateSouscription) {
        let souscripteurName = '';
        if (selectedSouscripteur) {
            souscripteurName = `${selectedSouscripteur.prenom} ${selectedSouscripteur.nom}`;
            if (selectedSouscripteur.email) {
                souscripteurName += ` (${selectedSouscripteur.email})`;
            }
        } else {
            souscripteurName = 'Souscripteur sélectionné';
        }

        // Mise à jour du contenu avec animation
        document.getElementById('resumeSouscripteur').textContent = souscripteurName;
        document.getElementById('resumeFimeco').textContent = fimeco.options[fimeco.selectedIndex].text.split(' - ')[0];
        document.getElementById('resumeMontant').textContent = formatNumber(montant) + ' FCFA';
        document.getElementById('resumeDateSouscription').textContent = new Date(dateSouscription).toLocaleDateString('fr-FR');
        document.getElementById('resumeEcheance').textContent = dateEcheance ? new Date(dateEcheance).toLocaleDateString('fr-FR') : 'Aucune';

        // Calcul de l'impact avec animation
        if (currentFimecoData) {
            const currentAmount = parseFloat(montant);
            const newTotal = currentFimecoData.collecte + currentAmount;
            const newProgression = (newTotal / currentFimecoData.cible) * 100;
            const impactText = `+${(currentAmount / currentFimecoData.cible * 100).toFixed(2)}% (nouvelle progression: ${newProgression.toFixed(1)}%)`;
            document.getElementById('resumeImpact').textContent = impactText;

            // Animation de l'impact
            const impactElement = document.getElementById('resumeImpact');
            impactElement.classList.add('animate-pulse');
            setTimeout(() => impactElement.classList.remove('animate-pulse'), 2000);
        }

        // Animation d'apparition du résumé
        resumeSection.classList.remove('hidden');
        resumeSection.classList.add('animate-fadeIn');
        resumePlaceholder.classList.add('hidden');
    } else {
        resumeSection.classList.add('hidden');
        resumeSection.classList.remove('animate-fadeIn');
        resumePlaceholder.classList.remove('hidden');
    }
}

// Fonction de validation avec feedback visuel amélioré
function validateForm() {
    const souscripteurId = document.getElementById('souscripteur_id').value;
    const fimecoId = document.getElementById('fimeco_id').value;
    const montant = document.getElementById('montant_souscrit').value;
    const dateSouscription = document.getElementById('date_souscription').value;

    // Animation de validation en cours
    const validateButton = document.querySelector('button[onclick="validateForm()"]');
    const originalText = validateButton.innerHTML;
    validateButton.innerHTML = '<div class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full mr-2 inline-block"></div> Validation...';
    validateButton.disabled = true;
    validateButton.classList.add('opacity-75');

    // Validation côté client avec feedback visuel
    const validationErrors = [];

    if (!souscripteurId) {
        validationErrors.push({ field: 'souscripteur_search', message: 'Veuillez sélectionner un souscripteur' });
    }

    if (!fimecoId) {
        validationErrors.push({ field: 'fimeco_id', message: 'Veuillez sélectionner un FIMECO' });
    }

    if (!montant || parseFloat(montant) < 1000) {
        validationErrors.push({ field: 'montant_souscrit', message: 'Montant minimum: 1,000 FCFA' });
    }

    if (!dateSouscription) {
        validationErrors.push({ field: 'date_souscription', message: 'Date de souscription requise' });
    }

    // Afficher les erreurs de validation côté client
    if (validationErrors.length > 0) {
        validationErrors.forEach(error => {
            const field = document.getElementById(error.field);
            field.classList.add('border-red-500', 'animate-pulse');
            field.focus();

            // Retirer l'animation après 2 secondes
            setTimeout(() => {
                field.classList.remove('animate-pulse');
            }, 2000);
        });

        // Restaurer le bouton
        validateButton.innerHTML = originalText;
        validateButton.disabled = false;
        validateButton.classList.remove('opacity-75');

        // Afficher le premier message d'erreur
        showNotification(validationErrors[0].message, 'error');
        return;
    }

    // Validation serveur
    const formData = {
        souscripteur_id: souscripteurId,
        fimeco_id: fimecoId,
        montant_souscrit: montant,
        date_souscription: dateSouscription,
        souscripteur_info: selectedSouscripteur
    };

    fetch("{{ route('private.subscriptions.validate-data') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        // Restaurer le bouton
        validateButton.innerHTML = originalText;
        validateButton.disabled = false;
        validateButton.classList.remove('opacity-75');

        if (data.success) {
            formValidated = true;
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = false;

            // Animation de succès
            submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
            submitButton.classList.add('animate-pulse', 'shadow-lg', 'scale-105');

            setTimeout(() => {
                submitButton.classList.remove('animate-pulse', 'scale-105');
            }, 2000);

            // Afficher les avertissements s'il y en a
            if (data.warnings && data.warnings.length > 0) {
                const warningMessage = 'Attention: ' + data.warnings.join('\n');
                if (confirm(warningMessage + '\n\nVoulez-vous continuer ?')) {
                    showNotification('Validation réussie ! Vous pouvez créer la souscription.', 'success');
                } else {
                    formValidated = false;
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
            } else {
                showNotification('Validation réussie ! Vous pouvez créer la souscription.', 'success');
            }
        } else {
            // Afficher les erreurs avec animation
            const errorMessages = Object.values(data.errors).flat();
            showNotification('Erreurs de validation: ' + errorMessages.join(', '), 'error');

            // Highlight des champs en erreur
            Object.keys(data.errors).forEach(fieldName => {
                const fieldMapping = {
                    'souscripteur_id': 'souscripteur_search',
                    'fimeco_id': 'fimeco_id',
                    'montant_souscrit': 'montant_souscrit',
                    'date_souscription': 'date_souscription'
                };

                const fieldToHighlight = fieldMapping[fieldName];
                if (fieldToHighlight) {
                    const field = document.getElementById(fieldToHighlight);
                    field.classList.add('border-red-500', 'bg-red-50', 'animate-pulse');

                    setTimeout(() => {
                        field.classList.remove('animate-pulse', 'bg-red-50');
                    }, 3000);
                }
            });
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        validateButton.innerHTML = originalText;
        validateButton.disabled = false;
        validateButton.classList.remove('opacity-75');
        showNotification('Erreur lors de la validation. Veuillez réessayer.', 'error');
    });
}


// Fonction pour afficher les notifications avec classes Tailwind
function showNotification(message, type = 'info') {
    // Supprimer les anciennes notifications
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(n => n.remove());

    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-full opacity-0 max-w-md`;

    // Classes selon le type
    const typeClasses = {
        success: 'bg-green-50 border border-green-200 text-green-800',
        error: 'bg-red-50 border border-red-200 text-red-800',
        warning: 'bg-yellow-50 border border-yellow-200 text-yellow-800',
        info: 'bg-blue-50 border border-blue-200 text-blue-800'
    };

    notification.classList.add(...typeClasses[type].split(' '));

    const icons = {
        success: '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        error: '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        warning: '<svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.866 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>',
        info: '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };

    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                ${icons[type]}
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ml-3 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Animation d'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
        notification.classList.add('translate-x-0', 'opacity-100');
    }, 100);

    // Auto-suppression après 5 secondes
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}



            // Add event listeners for resume updates
            document.getElementById('souscripteur_id').addEventListener('change', function() {
                checkForExistingSubscription();
                updateResume();
            });

// Event listeners avec animations
document.getElementById('date_souscription').addEventListener('change', function() {
    this.classList.add('animate-pulse');
    setTimeout(() => this.classList.remove('animate-pulse'), 1000);
    updateResume();
})


document.getElementById('date_echeance').addEventListener('change', function() {
    this.classList.add('animate-pulse');
    setTimeout(() => this.classList.remove('animate-pulse'), 1000);
    updateResume();
});



// Soumission du formulaire avec animation
document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
    if (!formValidated) {
        e.preventDefault();
        showNotification('Veuillez d\'abord valider les données du formulaire', 'warning');

        const validateButton = document.querySelector('button[onclick="validateForm()"]');
        validateButton.classList.add('animate-bounce');
        setTimeout(() => validateButton.classList.remove('animate-bounce'), 1000);

        validateButton.focus();
        return;
    }

    if (!selectedSouscripteur) {
        e.preventDefault();
        showNotification('Erreur: aucun souscripteur sélectionné', 'error');
        document.getElementById('souscripteur_search').focus();
        return;
    }

    // Animation du bouton de soumission
    const submitButton = document.getElementById('submitButton');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<div class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full mr-2 inline-block"></div> Création en cours...';
    submitButton.disabled = true;
    submitButton.classList.add('opacity-75');

    // Restaurer après timeout pour éviter le blocage
    setTimeout(() => {
        if (submitButton.innerHTML.includes('Création en cours')) {
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-75');
        }
    }, 10000);
});



// Fonction pour réinitialiser le formulaire avec animations
function resetForm() {
    // Animation de réinitialisation
    const form = document.getElementById('subscriptionForm');
    form.classList.add('animate-pulse');

    setTimeout(() => {
        clearSelection();

        document.getElementById('montant_souscrit').value = '';
        document.getElementById('date_souscription').value = new Date().toISOString().split('T')[0];
        document.getElementById('date_echeance').value = '';

        formValidated = false;
        const submitButton = document.getElementById('submitButton');
        submitButton.disabled = true;
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');

        document.getElementById('subscriptionExistsAlert').classList.add('hidden');
        document.getElementById('montantPreview').classList.add('hidden');

        // Supprimer toutes les classes d'erreur
        document.querySelectorAll('.border-red-500, .bg-red-50').forEach(el => {
            el.classList.remove('border-red-500', 'bg-red-50');
        });

        // Supprimer les messages d'erreur
        document.querySelectorAll('.error-message').forEach(el => el.remove());

        updateResume();
        form.classList.remove('animate-pulse');
    }, 500);
}



// Raccourcis clavier avec feedback visuel
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        const validateButton = document.querySelector('button[onclick="validateForm()"]');
        validateButton.classList.add('animate-pulse');
        validateForm();
    }

    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        if (formValidated) {
            document.getElementById('subscriptionForm').submit();
        } else {
            showNotification('Veuillez d\'abord valider le formulaire avec Ctrl+S', 'info');
        }
    }

    if (e.key === 'Escape' && document.activeElement === document.getElementById('souscripteur_search')) {
        clearSelection();
    }
});



// Sauvegarde automatique avec indicateur visuel
function autoSave() {
    if (!window.localStorage) return;

    const formData = {
        fimeco_id: document.getElementById('fimeco_id').value,
        souscripteur: selectedSouscripteur,
        montant_souscrit: document.getElementById('montant_souscrit').value,
        date_souscription: document.getElementById('date_souscription').value,
        date_echeance: document.getElementById('date_echeance').value,
        timestamp: Date.now()
    };

    localStorage.setItem('subscription_form_draft', JSON.stringify(formData));

    // Indicateur visuel de sauvegarde
    const saveIndicator = document.createElement('div');
    saveIndicator.className = 'fixed bottom-4 right-4 bg-green-100 text-green-800 px-3 py-2 rounded-lg text-sm font-medium shadow-lg transform transition-all duration-300 translate-y-full opacity-0';
    saveIndicator.textContent = 'Brouillon sauvegardé';

    document.body.appendChild(saveIndicator);

    setTimeout(() => {
        saveIndicator.classList.remove('translate-y-full', 'opacity-0');
        saveIndicator.classList.add('translate-y-0', 'opacity-100');
    }, 100);

    setTimeout(() => {
        saveIndicator.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => saveIndicator.remove(), 300);
    }, 2000);
}



// Event listeners pour la sauvegarde automatique
['input', 'change'].forEach(eventType => {
    document.getElementById('montant_souscrit').addEventListener(eventType, autoSave);
    document.getElementById('date_souscription').addEventListener(eventType, autoSave);
    document.getElementById('date_echeance').addEventListener(eventType, autoSave);
});


// Suppression de la sauvegarde après soumission
document.getElementById('subscriptionForm').addEventListener('submit', function() {
    localStorage.removeItem('subscription_form_draft');
})



// Avertissement avant fermeture avec modifications non sauvées
window.addEventListener('beforeunload', function(e) {
    const hasUnsavedData = document.getElementById('souscripteur_id').value ||
                          document.getElementById('montant_souscrit').value ||
                          document.getElementById('date_echeance').value;

    if (hasUnsavedData && !formValidated) {
        e.preventDefault();
        e.returnValue = 'Vous avez des modifications non sauvegardées.';
    }
});

            // Initialize
            // Fonction mise à jour pour l'initialisation
            document.addEventListener('DOMContentLoaded', function() {
            const fimeco = document.getElementById('fimeco_id');
            const selectedOption = fimeco.options[fimeco.selectedIndex];
            const fimecoInfo = document.getElementById('fimecoInfo');

            if (fimeco.value) {
                currentFimecoData = {
                    cible: parseFloat(selectedOption.dataset.cible),
                    collecte: parseFloat(selectedOption.dataset.collecte),
                    progression: parseFloat(selectedOption.dataset.progression),
                    fin: selectedOption.dataset.fin
                };

                // Mise à jour de l'affichage
                document.getElementById('fimecoCible').textContent = formatNumber(currentFimecoData.cible) + ' FCFA';
                document.getElementById('fimecoCollecte').textContent = formatNumber(currentFimecoData.collecte) + ' FCFA';
                document.getElementById('fimecoProgression').textContent = currentFimecoData.progression.toFixed(1) + '%';
                document.getElementById('fimecoProgressionText').textContent = currentFimecoData.progression.toFixed(1) + '%';
                document.getElementById('fimecoFin').textContent = new Date(currentFimecoData.fin).toLocaleDateString('fr-FR');

                // Mise à jour de la barre de progression avec classes Tailwind
                const progressBar = document.getElementById('fimecoProgressionBar');
                const clampedProgress = Math.min(currentFimecoData.progression, 100);
                progressBar.style.width = clampedProgress + '%';

                // Suppression des anciennes classes et ajout des nouvelles
                progressBar.className = 'h-2 rounded-full transition-all duration-500';
                if (currentFimecoData.progression >= 100) {
                    progressBar.classList.add('bg-gradient-to-r', 'from-green-500', 'to-emerald-500');
                } else if (currentFimecoData.progression >= 75) {
                    progressBar.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-purple-500');
                } else if (currentFimecoData.progression >= 50) {
                    progressBar.classList.add('bg-gradient-to-r', 'from-yellow-500', 'to-orange-500');
                } else {
                    progressBar.classList.add('bg-gradient-to-r', 'from-red-500', 'to-pink-500');
                }

                fimecoInfo.classList.remove('hidden');
                const dateEcheance = document.getElementById('date_echeance');
                dateEcheance.max = currentFimecoData.fin;

                currentFimecoId = fimeco.value;
            } else {
                currentFimecoData = null;
                fimecoInfo.classList.add('hidden');
                currentFimecoId = null;
            }

            checkForExistingSubscription();
            updateResume();
            updateSearchability();
        });
        </script> --}}

        <script>
            // =============================================================================
// SCRIPT JAVASCRIPT POUR LA CRÉATION DE SOUSCRIPTIONS
// =============================================================================

// Variables globales
let currentFimecoData = null;
let formValidated = false;
let currentFimecoId = null;
let searchTimeout;
let searchCache = new Map();
let selectedSouscripteur = null;

// Éléments DOM pour le composant de recherche
let searchInput, hiddenInput, dropdown, loadingIndicator, noResults, resultsList, selectedDiv;

// =============================================================================
// FONCTIONS UTILITAIRES
// =============================================================================

// Formatage des nombres avec séparateurs de milliers
function formatNumber(num) {
    return new Intl.NumberFormat('fr-FR').format(num);
}

// Notification système avec animations Tailwind
function showNotification(message, type = 'info') {
    // Supprimer les anciennes notifications
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(n => n.remove());

    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg transform transition-all duration-300 translate-x-full opacity-0 max-w-md`;

    // Classes selon le type
    const typeClasses = {
        success: 'bg-green-50 border border-green-200 text-green-800',
        error: 'bg-red-50 border border-red-200 text-red-800',
        warning: 'bg-yellow-50 border border-yellow-200 text-yellow-800',
        info: 'bg-blue-50 border border-blue-200 text-blue-800'
    };

    notification.classList.add(...typeClasses[type].split(' '));

    const icons = {
        success: '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        error: '<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        warning: '<svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.866 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>',
        info: '<svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };

    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                ${icons[type]}
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ml-3 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Animation d'entrée
    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
        notification.classList.add('translate-x-0', 'opacity-100');
    }, 100);

    // Auto-suppression après 5 secondes
    setTimeout(() => {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// =============================================================================
// COMPOSANT DE RECHERCHE DE SOUSCRIPTEUR
// =============================================================================

// Initialisation du composant de recherche
function initializeSearchComponent() {
    // Initialiser les éléments du DOM
    searchInput = document.getElementById('souscripteur_search');
    hiddenInput = document.getElementById('souscripteur_id');
    dropdown = document.getElementById('souscripteur_dropdown');
    loadingIndicator = document.getElementById('loading_indicator');
    noResults = document.getElementById('no_results');
    resultsList = document.getElementById('souscripteur_results');
    selectedDiv = document.getElementById('selected_souscripteur');

    if (!searchInput) return; // Si les éléments n'existent pas, sortir
    initializeSearch();
}

// Configuration des event listeners pour la recherche
function initializeSearch() {
    // Recherche en temps réel avec debouncing
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();

        if (query.length < 2) {
            hideDropdown();
            return;
        }

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    // Navigation clavier (flèches, Enter, Escape)
    searchInput.addEventListener('keydown', function(e) {
        const options = resultsList.querySelectorAll('.souscripteur-option');
        const selectedOption = resultsList.querySelector('.souscripteur-option.bg-blue-50');
        let newIndex = -1;

        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                if (selectedOption) {
                    const currentIndex = Array.from(options).indexOf(selectedOption);
                    newIndex = Math.min(currentIndex + 1, options.length - 1);
                } else {
                    newIndex = 0;
                }
                break;

            case 'ArrowUp':
                e.preventDefault();
                if (selectedOption) {
                    const currentIndex = Array.from(options).indexOf(selectedOption);
                    newIndex = Math.max(currentIndex - 1, 0);
                } else {
                    newIndex = options.length - 1;
                }
                break;

            case 'Enter':
                e.preventDefault();
                if (selectedOption) {
                    selectSouscripteur(selectedOption.dataset);
                }
                break;

            case 'Escape':
                hideDropdown();
                break;
        }

        // Mettre à jour la sélection visuelle
        if (newIndex >= 0 && options[newIndex]) {
            options.forEach(opt => opt.classList.remove('bg-blue-50', 'border-l-4', 'border-blue-500'));
            options[newIndex].classList.add('bg-blue-50', 'border-l-4', 'border-blue-500');
        }
    });

    // Cacher le dropdown quand on clique ailleurs
    document.addEventListener('click', function(e) {
        if (searchInput && dropdown &&
            !e.target.closest('#souscripteur_search') &&
            !e.target.closest('#souscripteur_dropdown')) {
            hideDropdown();
        }
    });
}

// Recherche AJAX avec cache
async function performSearch(query) {
    if (!currentFimecoId) {
        showMessage('Veuillez d\'abord sélectionner un FIMECO');
        return;
    }

    const cacheKey = `${currentFimecoId}-${query.toLowerCase()}`;
    if (searchCache.has(cacheKey)) {
        displayResults(searchCache.get(cacheKey));
        return;
    }

    showLoading();

    try {
        const url = new URL("{{ route('private.users.not-subscribed-to-fimeco', ':fimeco') }}".replace(':fimeco', currentFimecoId));
        url.searchParams.append('search', query);
        url.searchParams.append('per_page', '10');

        const response = await fetch(url, {
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        if (data.success && data.users) {
            searchCache.set(cacheKey, data.users);
            displayResults(data.users);
        } else {
            displayResults([]);
        }

    } catch (error) {
        console.error('Erreur lors de la recherche:', error);
        showMessage('Erreur lors de la recherche');
    }
}

// Affichage des résultats de recherche
function displayResults(users) {
    if (!resultsList) return;

    hideLoading();

    if (users.length === 0) {
        showNoResults();
        return;
    }

    resultsList.innerHTML = users.map((user, index) => {
        const statusBadgeClass = getStatusBadgeClass(user.statut_membre);
        const statusLabel = getStatusLabel(user.statut_membre);

        return `
        <li class="souscripteur-option cursor-pointer p-3 hover:bg-slate-50 transition-all duration-150 ${index === 0 ? 'bg-blue-50 border-l-4 border-blue-500' : 'border-l-4 border-transparent'} ${index === 0 ? 'first:rounded-t-xl' : ''} ${index === users.length - 1 ? 'last:rounded-b-xl' : ''}"
            data-id="${user.id}"
            data-nom="${user.nom}"
            data-prenom="${user.prenom}"
            data-email="${user.email || ''}"
            data-telephone="${user.telephone_1 || ''}"
            data-ville="${user.ville || ''}"
            onclick="selectSouscripteur(this.dataset)">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-medium text-sm shadow-sm">
                    ${user.prenom.charAt(0)}${user.nom.charAt(0)}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-slate-900 truncate">
                        ${user.prenom} ${user.nom}
                    </div>
                    <div class="text-sm text-slate-500 space-y-1">
                        ${user.email ? `<div class="flex items-center space-x-1"><span>📧</span><span class="truncate">${user.email}</span></div>` : ''}
                        ${user.telephone_1 ? `<div class="flex items-center space-x-1"><span>📞</span><span>${user.telephone_1}</span></div>` : ''}
                        <div class="flex items-center justify-between">
                            ${user.ville ? `<span class="text-xs">📍 ${user.ville}</span>` : ''}
                            ${user.statut_membre ? `<span class="inline-flex px-2 py-1 text-xs font-medium rounded-full ${statusBadgeClass}">${statusLabel}</span>` : ''}
                        </div>
                    </div>
                </div>
            </div>
        </li>`;
    }).join('');

    showDropdown();
}

// Sélection d'un souscripteur
function selectSouscripteur(data) {
    selectedSouscripteur = {
        id: data.id,
        nom: data.nom,
        prenom: data.prenom,
        email: data.email,
        telephone: data.telephone,
        ville: data.ville
    };

    if (searchInput && hiddenInput) {
        searchInput.value = `${data.prenom} ${data.nom}`;
        hiddenInput.value = data.id;
    }

    if (selectedDiv) {
        document.getElementById('selected_name').textContent = `${data.prenom} ${data.nom}`;
        const details = [];
        if (data.email) details.push(data.email);
        if (data.telephone) details.push(data.telephone);
        if (data.ville) details.push(data.ville);
        document.getElementById('selected_details').textContent = details.join(' • ');

        selectedDiv.classList.remove('hidden');
        selectedDiv.classList.add('animate-pulse');
        setTimeout(() => selectedDiv.classList.remove('animate-pulse'), 1000);
    }

    hideDropdown();
    checkForExistingSubscription();
    updateResume();
}

// Effacement de la sélection
function clearSelection() {
    selectedSouscripteur = null;
    if (searchInput) searchInput.value = '';
    if (hiddenInput) hiddenInput.value = '';
    if (selectedDiv) selectedDiv.classList.add('hidden');
    clearSearch();

    checkForExistingSubscription();
    updateResume();
}

// Nettoyage de la recherche
function clearSearch() {
    if (resultsList) resultsList.innerHTML = '';
    hideDropdown();
}

// Gestion des états du dropdown
function showDropdown() {
    if (!dropdown) return;
    dropdown.classList.remove('hidden', 'scale-95', 'opacity-0');
    dropdown.classList.add('scale-100', 'opacity-100');
    if (noResults) noResults.classList.add('hidden');
}

function hideDropdown() {
    if (!dropdown) return;
    dropdown.classList.add('hidden', 'scale-95', 'opacity-0');
    dropdown.classList.remove('scale-100', 'opacity-100');
}

function showLoading() {
    showDropdown();
    if (loadingIndicator) loadingIndicator.classList.remove('hidden');
    if (noResults) noResults.classList.add('hidden');
    if (resultsList) resultsList.innerHTML = '';
}

function hideLoading() {
    if (loadingIndicator) loadingIndicator.classList.add('hidden');
}

function showNoResults() {
    showDropdown();
    if (noResults) noResults.classList.remove('hidden');
    if (resultsList) resultsList.innerHTML = '';
}

function showMessage(message) {
    if (!resultsList) return;
    resultsList.innerHTML = `
        <li class="p-4 text-center text-slate-500 rounded-xl">
            <div class="flex flex-col items-center space-y-2">
                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.866 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="text-sm">${message}</span>
            </div>
        </li>
    `;
    showDropdown();
}

// Gestion de la disponibilité de la recherche
function updateSearchability() {
    if (!searchInput) return;

    if (!currentFimecoId) {
        searchInput.disabled = true;
        searchInput.placeholder = 'Sélectionnez d\'abord un FIMECO';
        searchInput.classList.add('bg-slate-50', 'cursor-not-allowed');
        if (hiddenInput) hiddenInput.value = '';
        clearSelection();
    } else {
        searchInput.disabled = false;
        searchInput.placeholder = 'Tapez pour rechercher un souscripteur...';
        searchInput.classList.remove('bg-slate-50', 'cursor-not-allowed');
    }
}

// Fonctions utilitaires pour les badges de statut
function getStatusBadgeClass(status) {
    const classes = {
        'actif': 'bg-green-100 text-green-800',
        'inactif': 'bg-gray-100 text-gray-800',
        'visiteur': 'bg-blue-100 text-blue-800',
        'nouveau_converti': 'bg-purple-100 text-purple-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function getStatusLabel(status) {
    const labels = {
        'actif': 'Actif',
        'inactif': 'Inactif',
        'visiteur': 'Visiteur',
        'nouveau_converti': 'Nouveau'
    };
    return labels[status] || status;
}

// =============================================================================
// GESTION DU FIMECO ET INITIALISATION
// =============================================================================

// Initialisation des données du FIMECO
function initializeFimecoData() {
    const fimeco = document.getElementById('fimeco_id');
    if (!fimeco || !fimeco.value) return;

    const selectedOption = fimeco.options[fimeco.selectedIndex];
    const fimecoInfo = document.getElementById('fimecoInfo');

    currentFimecoData = {
        cible: parseFloat(selectedOption.dataset.cible),
        collecte: parseFloat(selectedOption.dataset.collecte),
        progression: parseFloat(selectedOption.dataset.progression),
        fin: selectedOption.dataset.fin
    };

    // Mise à jour de l'affichage des informations
    const elements = {
        fimecoCible: formatNumber(currentFimecoData.cible) + ' FCFA',
        fimecoCollecte: formatNumber(currentFimecoData.collecte) + ' FCFA',
        fimecoProgression: currentFimecoData.progression.toFixed(1) + '%',
        fimecoProgressionText: currentFimecoData.progression.toFixed(1) + '%',
        fimecoFin: new Date(currentFimecoData.fin).toLocaleDateString('fr-FR')
    };

    Object.keys(elements).forEach(id => {
        const element = document.getElementById(id);
        if (element) element.textContent = elements[id];
    });

    // Mise à jour de la barre de progression
    const progressBar = document.getElementById('fimecoProgressionBar');
    if (progressBar) {
        const clampedProgress = Math.min(currentFimecoData.progression, 100);
        progressBar.style.width = clampedProgress + '%';

        progressBar.className = 'h-2 rounded-full transition-all duration-500';
        if (currentFimecoData.progression >= 100) {
            progressBar.classList.add('bg-gradient-to-r', 'from-green-500', 'to-emerald-500');
        } else if (currentFimecoData.progression >= 75) {
            progressBar.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-purple-500');
        } else if (currentFimecoData.progression >= 50) {
            progressBar.classList.add('bg-gradient-to-r', 'from-yellow-500', 'to-orange-500');
        } else {
            progressBar.classList.add('bg-gradient-to-r', 'from-red-500', 'to-pink-500');
        }
    }

    if (fimecoInfo) fimecoInfo.classList.remove('hidden');

    // Configuration de la date d'échéance maximale
    const dateEcheance = document.getElementById('date_echeance');
    if (dateEcheance) dateEcheance.max = currentFimecoData.fin;

    currentFimecoId = fimeco.value;
}

// =============================================================================
// GESTION DES MONTANTS ET SUGGESTIONS
// =============================================================================

// Gestion du formatage des montants en temps réel
function initializeMontantHandling() {
    const montantInput = document.getElementById('montant_souscrit');
    if (!montantInput) return;

    montantInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');

        if (value) {
            if (parseInt(value) > 100000000) {
                value = '100000000';
            }

            e.target.value = value;

            const amount = parseFloat(value);
            const preview = document.getElementById('montantPreview');

            if (amount >= 1000 && preview) {
                const montantFormatted = document.getElementById('montantFormatted');
                if (montantFormatted) montantFormatted.textContent = formatNumber(amount);
                preview.classList.remove('hidden');
                preview.classList.add('animate-pulse');
                setTimeout(() => preview.classList.remove('animate-pulse'), 1000);

                e.target.classList.remove('border-red-500', 'bg-red-50');
            } else if (preview) {
                preview.classList.add('hidden');
                if (value) {
                    e.target.classList.add('border-red-500', 'bg-red-50');
                }
            }
        }

        updateResume();
    });

    // Validation du montant au focus perdu
    montantInput.addEventListener('blur', function(e) {
        const amount = parseFloat(e.target.value);
        const container = e.target.parentNode;

        // Supprimer les anciens messages d'erreur
        const existingError = container.querySelector('.error-message');
        if (existingError) existingError.remove();

        if (e.target.value && amount < 1000) {
            const errorDiv = document.createElement('p');
            errorDiv.className = 'error-message mt-1 text-sm text-red-600 animate-pulse';
            errorDiv.textContent = 'Le montant minimum est de 1,000 FCFA';
            container.appendChild(errorDiv);

            e.target.classList.add('border-red-500', 'bg-red-50');

            setTimeout(() => {
                errorDiv.classList.add('opacity-0', 'transition-opacity', 'duration-300');
                setTimeout(() => errorDiv.remove(), 300);
            }, 5000);
        } else {
            e.target.classList.remove('border-red-500', 'bg-red-50');
        }
    });
}

// Définition d'un montant suggéré
function setSuggestedAmount(amount) {
    const montantInput = document.getElementById('montant_souscrit');
    if (montantInput) {
        montantInput.value = amount;
        montantInput.dispatchEvent(new Event('input'));
    }
}

// Calcul d'un pourcentage de l'objectif FIMECO
function calculatePercentage(percentage) {
    if (currentFimecoData) {
        const amount = Math.round(currentFimecoData.cible * percentage / 100);
        setSuggestedAmount(amount);
    } else {
        alert('Veuillez d\'abord sélectionner un FIMECO');
    }
}

// =============================================================================
// GESTION DES DATES ET ÉCHÉANCES
// =============================================================================

// Suggestion d'échéance basée sur un nombre de jours
function setDeadlineSuggestion(days) {
    const date = new Date();
    date.setDate(date.getDate() + days);

    if (currentFimecoData) {
        const fimecoEnd = new Date(currentFimecoData.fin);
        if (date > fimecoEnd) {
            date = fimecoEnd;
        }
    }

    const dateEcheance = document.getElementById('date_echeance');
    if (dateEcheance) {
        dateEcheance.value = date.toISOString().split('T')[0];
        updateResume();
    }
}

// Définition de l'échéance à la fin du FIMECO
function setFimecoEndDate() {
    if (currentFimecoData) {
        const dateEcheance = document.getElementById('date_echeance');
        if (dateEcheance) {
            dateEcheance.value = currentFimecoData.fin;
            updateResume();
        }
    } else {
        alert('Veuillez d\'abord sélectionner un FIMECO');
    }
}

// Initialisation des event listeners pour les dates
function initializeDateHandlers() {
    const dateSouscription = document.getElementById('date_souscription');
    const dateEcheance = document.getElementById('date_echeance');

    if (dateSouscription) {
        dateSouscription.addEventListener('change', function() {
            this.classList.add('animate-pulse');
            setTimeout(() => this.classList.remove('animate-pulse'), 1000);
            updateResume();
        });
    }

    if (dateEcheance) {
        dateEcheance.addEventListener('change', function() {
            this.classList.add('animate-pulse');
            setTimeout(() => this.classList.remove('animate-pulse'), 1000);
            updateResume();
        });
    }
}

// =============================================================================
// VALIDATION ET VÉRIFICATIONS
// =============================================================================

// Vérification des souscriptions existantes (doublons)
function checkForExistingSubscription() {
    const souscripteurId = document.getElementById('souscripteur_id')?.value;
    const fimecoId = document.getElementById('fimeco_id')?.value;
    const alert = document.getElementById('subscriptionExistsAlert');

    if (souscripteurId && fimecoId && searchInput) {
        // Indicateur de vérification en cours
        const searchContainer = searchInput.parentNode;
        const checkingIndicator = document.createElement('div');
        checkingIndicator.id = 'checking_indicator';
        checkingIndicator.className = 'absolute right-12 top-1/2 transform -translate-y-1/2';
        checkingIndicator.innerHTML = '<div class="animate-spin h-4 w-4 border-2 border-slate-200 border-t-blue-500 rounded-full"></div>';
        searchContainer.appendChild(checkingIndicator);

        fetch("{{route('private.subscriptions.check-exists')}}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                souscripteur_id: souscripteurId,
                fimeco_id: fimecoId
            })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('checking_indicator')?.remove();

            if (data.exists && alert) {
                alert.classList.remove('hidden');
                alert.classList.add('animate-pulse');
                const submitButton = document.getElementById('submitButton');
                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                }
                searchInput.classList.add('border-red-500', 'bg-red-50');
            } else if (alert) {
                alert.classList.add('hidden');
                alert.classList.remove('animate-pulse');
                searchInput.classList.remove('border-red-500', 'bg-red-50');
            }
        })
        .catch(error => {
            console.error('Erreur lors de la vérification:', error);
            document.getElementById('checking_indicator')?.remove();
            if (alert) alert.classList.add('hidden');
        });
    } else if (alert) {
        alert.classList.add('hidden');
        if (searchInput) searchInput.classList.remove('border-red-500', 'bg-red-50');
    }
}

// Validation complète du formulaire
function validateForm() {
    const souscripteurId = document.getElementById('souscripteur_id')?.value;
    const fimecoId = document.getElementById('fimeco_id')?.value;
    const montant = document.getElementById('montant_souscrit')?.value;
    const dateSouscription = document.getElementById('date_souscription')?.value;

    // Animation du bouton de validation
    const validateButton = document.querySelector('button[onclick="validateForm()"]');
    if (!validateButton) return;

    const originalText = validateButton.innerHTML;
    validateButton.innerHTML = '<div class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full mr-2 inline-block"></div> Validation...';
    validateButton.disabled = true;
    validateButton.classList.add('opacity-75');

    // Validation côté client
    const validationErrors = [];

    if (!souscripteurId) {
        validationErrors.push({ field: 'souscripteur_search', message: 'Veuillez sélectionner un souscripteur' });
    }
    if (!fimecoId) {
        validationErrors.push({ field: 'fimeco_id', message: 'Veuillez sélectionner un FIMECO' });
    }
    if (!montant || parseFloat(montant) < 1000) {
        validationErrors.push({ field: 'montant_souscrit', message: 'Montant minimum: 1,000 FCFA' });
    }
    if (!dateSouscription) {
        validationErrors.push({ field: 'date_souscription', message: 'Date de souscription requise' });
    }

    // Afficher les erreurs de validation côté client
    if (validationErrors.length > 0) {
        validationErrors.forEach(error => {
            const field = document.getElementById(error.field);
            if (field) {
                field.classList.add('border-red-500', 'animate-pulse');
                field.focus();
                setTimeout(() => field.classList.remove('animate-pulse'), 2000);
            }
        });

        // Restaurer le bouton
        validateButton.innerHTML = originalText;
        validateButton.disabled = false;
        validateButton.classList.remove('opacity-75');

        showNotification(validationErrors[0].message, 'error');
        return;
    }

    // Validation serveur
    const formData = {
        souscripteur_id: souscripteurId,
        fimeco_id: fimecoId,
        montant_souscrit: montant,
        date_souscription: dateSouscription,
        souscripteur_info: selectedSouscripteur
    };

    fetch("{{ route('private.subscriptions.validate-data') }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        // Restaurer le bouton
        validateButton.innerHTML = originalText;
        validateButton.disabled = false;
        validateButton.classList.remove('opacity-75');

        if (data.success) {
            formValidated = true;
            const submitButton = document.getElementById('submitButton');
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                submitButton.classList.add('animate-pulse', 'shadow-lg', 'scale-105');

                setTimeout(() => {
                    submitButton.classList.remove('animate-pulse', 'scale-105');
                }, 2000);
            }

            // Afficher les avertissements s'il y en a
            if (data.warnings && data.warnings.length > 0) {
                const warningMessage = 'Attention: ' + data.warnings.join('\n');
                if (confirm(warningMessage + '\n\nVoulez-vous continuer ?')) {
                    showNotification('Validation réussie ! Vous pouvez créer la souscription.', 'success');
                } else {
                    formValidated = false;
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                }
            } else {
                showNotification('Validation réussie ! Vous pouvez créer la souscription.', 'success');
            }
        } else {
            const errorMessages = Object.values(data.errors).flat();
            showNotification('Erreurs de validation: ' + errorMessages.join(', '), 'error');

            // Highlight des champs en erreur
            Object.keys(data.errors).forEach(fieldName => {
                const fieldMapping = {
                    'souscripteur_id': 'souscripteur_search',
                    'fimeco_id': 'fimeco_id',
                    'montant_souscrit': 'montant_souscrit',
                    'date_souscription': 'date_souscription'
                };

                const fieldToHighlight = fieldMapping[fieldName];
                if (fieldToHighlight) {
                    const field = document.getElementById(fieldToHighlight);
                    if (field) {
                        field.classList.add('border-red-500', 'bg-red-50', 'animate-pulse');
                        setTimeout(() => {
                            field.classList.remove('animate-pulse', 'bg-red-50');
                        }, 3000);
                    }
                }
            });
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        validateButton.innerHTML = originalText;
        validateButton.disabled = false;
        validateButton.classList.remove('opacity-75');
        showNotification('Erreur lors de la validation. Veuillez réessayer.', 'error');
    });
}

// =============================================================================
// MISE À JOUR DU RÉSUMÉ
// =============================================================================

// Mise à jour du résumé de la souscription
function updateResume() {
    const souscripteurInput = document.getElementById('souscripteur_id');
    const fimeco = document.getElementById('fimeco_id');
    const montant = document.getElementById('montant_souscrit')?.value;
    const dateSouscription = document.getElementById('date_souscription')?.value;
    const dateEcheance = document.getElementById('date_echeance')?.value;

    const resumeSection = document.getElementById('resumeSection');
    const resumePlaceholder = document.getElementById('resumePlaceholder');

    if (souscripteurInput?.value && fimeco?.value && montant && dateSouscription) {
        let souscripteurName = '';
        if (selectedSouscripteur) {
            souscripteurName = `${selectedSouscripteur.prenom} ${selectedSouscripteur.nom}`;
            if (selectedSouscripteur.email) {
                souscripteurName += ` (${selectedSouscripteur.email})`;
            }
        } else {
            souscripteurName = 'Souscripteur sélectionné';
        }

        // Mise à jour du contenu
        const resumeElements = {
            resumeSouscripteur: souscripteurName,
            resumeFimeco: fimeco.options[fimeco.selectedIndex].text.split(' - ')[0],
            resumeMontant: formatNumber(montant) + ' FCFA',
            resumeDateSouscription: new Date(dateSouscription).toLocaleDateString('fr-FR'),
            resumeEcheance: dateEcheance ? new Date(dateEcheance).toLocaleDateString('fr-FR') : 'Aucune'
        };

        Object.keys(resumeElements).forEach(id => {
            const element = document.getElementById(id);
            if (element) element.textContent = resumeElements[id];
        });

        // Calcul de l'impact sur le FIMECO
        if (currentFimecoData) {
            const currentAmount = parseFloat(montant);
            const newTotal = currentFimecoData.collecte + currentAmount;
            const newProgression = (newTotal / currentFimecoData.cible) * 100;
            const impactText = `+${(currentAmount / currentFimecoData.cible * 100).toFixed(2)}% (nouvelle progression: ${newProgression.toFixed(1)}%)`;

            const resumeImpact = document.getElementById('resumeImpact');
            if (resumeImpact) {
                resumeImpact.textContent = impactText;
                resumeImpact.classList.add('animate-pulse');
                setTimeout(() => resumeImpact.classList.remove('animate-pulse'), 2000);
            }
        }

        // Affichage du résumé
        if (resumeSection && resumePlaceholder) {
            resumeSection.classList.remove('hidden');
            resumePlaceholder.classList.add('hidden');
        }
    } else if (resumeSection && resumePlaceholder) {
        resumeSection.classList.add('hidden');
        resumePlaceholder.classList.remove('hidden');
    }
}

// =============================================================================
// SAUVEGARDE AUTOMATIQUE ET UTILITAIRES
// =============================================================================

// Sauvegarde automatique des données du formulaire
function autoSave() {
    if (!window.localStorage) return;

    const formData = {
        fimeco_id: document.getElementById('fimeco_id')?.value,
        souscripteur: selectedSouscripteur,
        montant_souscrit: document.getElementById('montant_souscrit')?.value,
        date_souscription: document.getElementById('date_souscription')?.value,
        date_echeance: document.getElementById('date_echeance')?.value,
        timestamp: Date.now()
    };

    localStorage.setItem('subscription_form_draft', JSON.stringify(formData));

    // Indicateur visuel de sauvegarde
    const saveIndicator = document.createElement('div');
    saveIndicator.className = 'fixed bottom-4 right-4 bg-green-100 text-green-800 px-3 py-2 rounded-lg text-sm font-medium shadow-lg transform transition-all duration-300 translate-y-full opacity-0';
    saveIndicator.textContent = 'Brouillon sauvegardé';

    document.body.appendChild(saveIndicator);

    setTimeout(() => {
        saveIndicator.classList.remove('translate-y-full', 'opacity-0');
        saveIndicator.classList.add('translate-y-0', 'opacity-100');
    }, 100);

    setTimeout(() => {
        saveIndicator.classList.add('translate-y-full', 'opacity-0');
        setTimeout(() => saveIndicator.remove(), 300);
    }, 2000);
}

// Restauration des données sauvegardées
function restoreAutoSave() {
    if (!window.localStorage) return;

    const savedData = localStorage.getItem('subscription_form_draft');
    if (!savedData) return;

    try {
        const formData = JSON.parse(savedData);

        // Vérifier que les données ne sont pas trop anciennes (24h)
        if (Date.now() - formData.timestamp > 24 * 60 * 60 * 1000) {
            localStorage.removeItem('subscription_form_draft');
            return;
        }

        // Proposer de restaurer les données
        if (confirm('Des données de formulaire ont été sauvegardées. Voulez-vous les restaurer ?')) {
            const montantInput = document.getElementById('montant_souscrit');
            const dateSouscriptionInput = document.getElementById('date_souscription');
            const dateEcheanceInput = document.getElementById('date_echeance');

            if (formData.montant_souscrit && montantInput) {
                montantInput.value = formData.montant_souscrit;
            }
            if (formData.date_souscription && dateSouscriptionInput) {
                dateSouscriptionInput.value = formData.date_souscription;
            }
            if (formData.date_echeance && dateEcheanceInput) {
                dateEcheanceInput.value = formData.date_echeance;
            }
            if (formData.souscripteur) {
                selectSouscripteur(formData.souscripteur);
            }

            updateResume();
        }
    } catch (error) {
        console.error('Erreur lors de la restauration:', error);
        localStorage.removeItem('subscription_form_draft');
    }
}

// Réinitialisation complète du formulaire
function resetForm() {
    const form = document.getElementById('subscriptionForm');
    if (form) form.classList.add('animate-pulse');

    setTimeout(() => {
        clearSelection();

        const montantInput = document.getElementById('montant_souscrit');
        const dateSouscriptionInput = document.getElementById('date_souscription');
        const dateEcheanceInput = document.getElementById('date_echeance');

        if (montantInput) montantInput.value = '';
        if (dateSouscriptionInput) dateSouscriptionInput.value = new Date().toISOString().split('T')[0];
        if (dateEcheanceInput) dateEcheanceInput.value = '';

        formValidated = false;
        const submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.classList.add('opacity-50', 'cursor-not-allowed');
        }

        const alert = document.getElementById('subscriptionExistsAlert');
        const preview = document.getElementById('montantPreview');
        if (alert) alert.classList.add('hidden');
        if (preview) preview.classList.add('hidden');

        // Supprimer toutes les classes d'erreur
        document.querySelectorAll('.border-red-500, .bg-red-50').forEach(el => {
            el.classList.remove('border-red-500', 'bg-red-50');
        });

        // Supprimer les messages d'erreur
        document.querySelectorAll('.error-message').forEach(el => el.remove());

        updateResume();
        if (form) form.classList.remove('animate-pulse');
    }, 500);
}

// =============================================================================
// EVENT LISTENERS ET INITIALISATION FINALE
// =============================================================================

// Initialisation des event listeners pour la sauvegarde automatique
function initializeAutoSave() {
    ['input', 'change'].forEach(eventType => {
        const montantInput = document.getElementById('montant_souscrit');
        const dateSouscriptionInput = document.getElementById('date_souscription');
        const dateEcheanceInput = document.getElementById('date_echeance');

        if (montantInput) montantInput.addEventListener(eventType, autoSave);
        if (dateSouscriptionInput) dateSouscriptionInput.addEventListener(eventType, autoSave);
        if (dateEcheanceInput) dateEcheanceInput.addEventListener(eventType, autoSave);
    });
}

// Gestion des raccourcis clavier
function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S pour valider
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const validateButton = document.querySelector('button[onclick="validateForm()"]');
            if (validateButton) validateButton.classList.add('animate-pulse');
            validateForm();
        }

        // Ctrl/Cmd + Enter pour soumettre
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            if (formValidated) {
                const form = document.getElementById('subscriptionForm');
                if (form) form.submit();
            } else {
                showNotification('Veuillez d\'abord valider le formulaire avec Ctrl+S', 'info');
            }
        }

        // Escape pour réinitialiser la recherche
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            clearSelection();
        }
    });
}

// Gestion de la soumission du formulaire
function initializeFormSubmission() {
    const form = document.getElementById('subscriptionForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        if (!formValidated) {
            e.preventDefault();
            showNotification('Veuillez d\'abord valider les données du formulaire', 'warning');

            const validateButton = document.querySelector('button[onclick="validateForm()"]');
            if (validateButton) {
                validateButton.classList.add('animate-bounce');
                setTimeout(() => validateButton.classList.remove('animate-bounce'), 1000);
                validateButton.focus();
            }
            return;
        }

        if (!selectedSouscripteur) {
            e.preventDefault();
            showNotification('Erreur: aucun souscripteur sélectionné', 'error');
            if (searchInput) searchInput.focus();
            return;
        }

        // Animation du bouton de soumission
        const submitButton = document.getElementById('submitButton');
        if (submitButton) {
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<div class="animate-spin h-5 w-5 border-2 border-white border-t-transparent rounded-full mr-2 inline-block"></div> Création en cours...';
            submitButton.disabled = true;
            submitButton.classList.add('opacity-75');

            // Restaurer après timeout pour éviter le blocage
            setTimeout(() => {
                if (submitButton.innerHTML.includes('Création en cours')) {
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                    submitButton.classList.remove('opacity-75');
                }
            }, 10000);
        }
    });

    // Suppression de la sauvegarde après soumission réussie
    form.addEventListener('submit', function() {
        localStorage.removeItem('subscription_form_draft');
    });
}

// Avertissement avant fermeture de la page
function initializeBeforeUnload() {
    window.addEventListener('beforeunload', function(e) {
        const souscripteurId = document.getElementById('souscripteur_id')?.value;
        const montant = document.getElementById('montant_souscrit')?.value;
        const dateEcheance = document.getElementById('date_echeance')?.value;

        const hasUnsavedData = souscripteurId || montant || dateEcheance;

        if (hasUnsavedData && !formValidated) {
            e.preventDefault();
            e.returnValue = 'Vous avez des modifications non sauvegardées.';
        }
    });
}

// =============================================================================
// INITIALISATION PRINCIPALE
// =============================================================================

// Initialisation complète au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les composants principaux
    initializeSearchComponent();
    initializeFimecoData();
    initializeMontantHandling();
    initializeDateHandlers();

    // Initialiser les fonctionnalités annexes
    initializeAutoSave();
    initializeKeyboardShortcuts();
    initializeFormSubmission();
    initializeBeforeUnload();

    // États initiaux
    updateSearchability();
    checkForExistingSubscription();
    updateResume();

    // Restaurer les données sauvegardées après un délai
    setTimeout(restoreAutoSave, 1000);
});
        </script>
    @endpush
@endsection
