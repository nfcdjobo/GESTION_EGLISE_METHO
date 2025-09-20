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
                        Sélectionnez le souscripteur et le FIMECO concerné
                    </p>
                </div>

                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Sélection du souscripteur -->
                        <div>
                            <label for="souscripteur_id" class="block text-sm font-medium text-slate-700 mb-2">
                                Souscripteur *
                            </label>
                            <select name="souscripteur_id" id="souscripteur_id" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('souscripteur_id') border-red-500 @enderror">
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
                        </div>

                        <!-- Sélection du FIMECO -->
                        <div>
                            <label for="fimeco_id" class="block text-sm font-medium text-slate-700 mb-2">
                                FIMECO *
                            </label>
                            <select name="fimeco_id" id="fimeco_id" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fimeco_id') border-red-500 @enderror">
                                <option value="">Sélectionnez un FIMECO</option>
                                @foreach($fimecosDisponibles as $fimeco)
                                    <option value="{{ $fimeco->id }}"
                                            data-cible="{{ $fimeco->cible }}"
                                            data-collecte="{{ $fimeco->montant_solde }}"
                                            data-progression="{{ $fimeco->progression }}"
                                            data-fin="{{ $fimeco->fin->format('Y-m-d') }}"
                                            {{ old('fimeco_id') == $fimeco->id ? 'selected' : '' }}>
                                        {{ $fimeco->nom }} - {{ number_format($fimeco->cible, 0, ',', ' ') }} FCFA ({{ number_format($fimeco->progression, 1) }}%)
                                    </option>
                                @endforeach
                            </select>
                            @error('fimeco_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

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
        <script>
            let currentFimecoData = null;
            let formValidated = false;

            // Format number with thousands separator
            function formatNumber(num) {
                return new Intl.NumberFormat('fr-FR').format(num);
            }

            // Update FIMECO info when selection changes
            document.getElementById('fimeco_id').addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const fimecoInfo = document.getElementById('fimecoInfo');

                if (this.value) {
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
                }

                checkForExistingSubscription();
                updateResume();
            });

            // Update amount preview
            document.getElementById('montant_souscrit').addEventListener('input', function() {
                const amount = parseFloat(this.value);
                const preview = document.getElementById('montantPreview');

                if (amount >= 1000) {
                    document.getElementById('montantFormatted').textContent = formatNumber(amount);
                    preview.classList.remove('hidden');
                } else {
                    preview.classList.add('hidden');
                }

                updateResume();
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

            // Check for existing subscription
            function checkForExistingSubscription() {
                const souscripteurId = document.getElementById('souscripteur_id').value;
                const fimecoId = document.getElementById('fimeco_id').value;
                const alert = document.getElementById('subscriptionExistsAlert');

                if (souscripteurId && fimecoId) {
                    // This would typically be an AJAX call to check for existing subscription
                    // For now, we'll hide the alert
                    alert.classList.add('hidden');
                }
            }

            // Update resume section
            function updateResume() {
                const souscripteur = document.getElementById('souscripteur_id');
                const fimeco = document.getElementById('fimeco_id');
                const montant = document.getElementById('montant_souscrit').value;
                const dateSouscription = document.getElementById('date_souscription').value;
                const dateEcheance = document.getElementById('date_echeance').value;

                const resumeSection = document.getElementById('resumeSection');
                const resumePlaceholder = document.getElementById('resumePlaceholder');

                if (souscripteur.value && fimeco.value && montant && dateSouscription) {
                    // Update resume content
                    document.getElementById('resumeSouscripteur').textContent = souscripteur.options[souscripteur.selectedIndex].text;
                    document.getElementById('resumeFimeco').textContent = fimeco.options[fimeco.selectedIndex].text.split(' - ')[0];
                    document.getElementById('resumeMontant').textContent = formatNumber(montant) + ' FCFA';
                    document.getElementById('resumeDateSouscription').textContent = new Date(dateSouscription).toLocaleDateString('fr-FR');
                    document.getElementById('resumeEcheance').textContent = dateEcheance ? new Date(dateEcheance).toLocaleDateString('fr-FR') : 'Aucune';

                    // Calculate impact
                    if (currentFimecoData) {
                        const currentAmount = parseFloat(montant);
                        const newTotal = currentFimecoData.collecte + currentAmount;
                        const newProgression = (newTotal / currentFimecoData.cible) * 100;
                        const impactText = `+${(currentAmount / currentFimecoData.cible * 100).toFixed(2)}% (nouvelle progression: ${newProgression.toFixed(1)}%)`;
                        document.getElementById('resumeImpact').textContent = impactText;
                    }

                    resumeSection.classList.remove('hidden');
                    resumePlaceholder.classList.add('hidden');
                } else {
                    resumeSection.classList.add('hidden');
                    resumePlaceholder.classList.remove('hidden');
                }
            }

            // Validate form
            function validateForm() {
                const souscripteurId = document.getElementById('souscripteur_id').value;
                const fimecoId = document.getElementById('fimeco_id').value;
                const montant = document.getElementById('montant_souscrit').value;
                const dateSouscription = document.getElementById('date_souscription').value;

                if (!souscripteurId) {
                    alert('Veuillez sélectionner un souscripteur');
                    return;
                }

                if (!fimecoId) {
                    alert('Veuillez sélectionner un FIMECO');
                    return;
                }

                if (!montant || parseFloat(montant) < 1000) {
                    alert('Veuillez saisir un montant d\'au moins 1,000 FCFA');
                    return;
                }

                if (!dateSouscription) {
                    alert('Veuillez saisir une date de souscription');
                    return;
                }

                // Additional business validation via AJAX
                const formData = {
                    souscripteur_id: souscripteurId,
                    fimeco_id: fimecoId,
                    montant_souscrit: montant,
                    date_souscription: dateSouscription
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
                    if (data.success) {
                        formValidated = true;
                        document.getElementById('submitButton').disabled = false;

                        // Show warnings if any
                        if (data.warnings && data.warnings.length > 0) {
                            alert('Attention: ' + data.warnings.join('\n'));
                        }

                        alert('Validation réussie. Vous pouvez maintenant créer la souscription.');
                    } else {
                        alert('Erreurs de validation: ' + Object.values(data.errors).flat().join('\n'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de la validation');
                });
            }

            // Add event listeners for resume updates
            document.getElementById('souscripteur_id').addEventListener('change', function() {
                checkForExistingSubscription();
                updateResume();
            });

            document.getElementById('date_souscription').addEventListener('change', updateResume);
            document.getElementById('date_echeance').addEventListener('change', updateResume);

            // Form submission
            document.getElementById('subscriptionForm').addEventListener('submit', function(e) {
                if (!formValidated) {
                    e.preventDefault();
                    alert('Veuillez d\'abord valider les données du formulaire');
                }
            });

            // Initialize
            document.addEventListener('DOMContentLoaded', function() {
                updateResume();
            });
        </script>
    @endpush
@endsection
