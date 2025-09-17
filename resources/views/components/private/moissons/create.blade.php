@extends('layouts.private.main')
@section('title', 'Nouvelle Moisson')

@section('content')
    <div class="space-y-8">
        <!-- En-tête avec navigation -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-slate-600 mb-4">
                <a href="{{ route('private.moissons.index') }}" class="hover:text-blue-600 transition-colors">
                    <i class="fas fa-seedling mr-1"></i> Moissons
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium">Nouvelle moisson</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Créer une nouvelle moisson
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Configurez tous les aspects de votre moisson d'église
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('private.moissons.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Formulaire principal -->
        <form id="moisson-form" action="{{ route('private.moissons.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Informations générales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations générales
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Thème -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Thème de la prédication <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="theme" id="theme" required
                                placeholder="Ex: La moisson de Dieu"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('theme') }}">
                            @error('theme')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Date de célébration <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date" id="date" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('date') }}">
                            @error('date')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Culte -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Culte <span class="text-red-500">*</span>
                            </label>
                            <select name="culte_id" id="culte_id" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionnez un culte</option>
                                @foreach($cultes as $culte)
                                    <option value="{{ $culte->id }}" {{ old('culte_id') == $culte->id ? 'selected' : '' }}>
                                        {{ $culte->titre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('culte_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cible financière -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Objectif financier (FCFA) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="cible" id="cible" required min="1" step="1"
                                placeholder="1000000"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('cible') }}">
                            @error('cible')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut initial</label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="1" {{ old('status', '0') == '1' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <span class="ml-2 text-sm text-slate-700">Active</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="0" {{ old('status', '0') == '0' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <span class="ml-2 text-sm text-slate-700">Inactive</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Passages bibliques -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-book text-blue-600 mr-2"></i>
                        Passages bibliques
                    </h3>
                    <p class="text-sm text-slate-600 mt-1">Ajoutez les références bibliques liées à cette moisson</p>
                </div>
                <div class="p-6">
                    <div id="passages-container" class="space-y-4">
                        <div class="passage-item flex items-center gap-4">
                            <input type="text" name="passages_bibliques[]" placeholder="Ex: Matthieu 9:37-38"
                                class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <button type="button" onclick="removePassage(this)"
                                class="w-10 h-10 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors opacity-50 cursor-not-allowed">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>

                    <button type="button" onclick="addPassage()"
                        class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Ajouter un passage
                    </button>
                </div>
            </div>

            <!-- Configuration des composants -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cogs text-blue-600 mr-2"></i>
                        Configuration des composants de collecte
                    </h3>
                    <p class="text-sm text-slate-600 mt-1">Définissez les différents types de collecte pour cette moisson</p>
                </div>

                <!-- Onglets -->
                <div class="border-b border-slate-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button type="button" onclick="switchTab('passages')" id="config-tab-passages"
                            class="config-tab-button border-b-2 border-blue-500 text-blue-600 py-4 px-1 text-sm font-medium">
                            <i class="fas fa-users mr-2"></i> Passages
                        </button>
                        <button type="button" onclick="switchTab('ventes')" id="config-tab-ventes"
                            class="config-tab-button border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 py-4 px-1 text-sm font-medium">
                            <i class="fas fa-store mr-2"></i> Ventes
                        </button>
                        <button type="button" onclick="switchTab('engagements')" id="config-tab-engagements"
                            class="config-tab-button border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 py-4 px-1 text-sm font-medium">
                            <i class="fas fa-handshake mr-2"></i> Engagements
                        </button>
                    </nav>
                </div>

                <div class="p-6">
                    <!-- Configuration Passages -->
                    <div id="config-passages" class="config-tab-content">
                        <div class="mb-4">
                            <h4 class="font-medium text-slate-800 mb-2">Passages de collecte</h4>
                            <p class="text-sm text-slate-600">Définissez les passages par catégorie de fidèles</p>
                        </div>

                        <div id="passages-config-container" class="space-y-4">
                            <!-- Les passages seront ajoutés dynamiquement -->
                        </div>

                        <button type="button" onclick="addPassageConfig()"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Ajouter un passage
                        </button>
                    </div>

                    <!-- Configuration Ventes -->
                    <div id="config-ventes" class="config-tab-content hidden">
                        <div class="mb-4">
                            <h4 class="font-medium text-slate-800 mb-2">Ventes de moisson</h4>
                            <p class="text-sm text-slate-600">Configurez les différents types de ventes</p>
                        </div>

                        <div id="ventes-config-container" class="space-y-4">
                            <!-- Les ventes seront ajoutées dynamiquement -->
                        </div>

                        <button type="button" onclick="addVenteConfig()"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Ajouter une vente
                        </button>
                    </div>

                    <!-- Configuration Engagements -->
                    <div id="config-engagements" class="config-tab-content hidden">
                        <div class="mb-4">
                            <h4 class="font-medium text-slate-800 mb-2">Engagements de moisson</h4>
                            <p class="text-sm text-slate-600">Préparez les cadres pour collecter les engagements</p>
                        </div>

                        <div id="engagements-config-container" class="space-y-4">
                            <!-- Les engagements seront ajoutés dynamiquement -->
                        </div>

                        <button type="button" onclick="addEngagementConfig()"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Ajouter un engagement
                        </button>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('private.moissons.index') }}"
                    class="inline-flex items-center px-6 py-3 border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Créer la moisson
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Configuration des catégories
            const passageCategories = {
                'passage_hommes': 'Passage des hommes',
                'passage_femmes': 'Passage des femmes',
                'passage_jeunesses': 'Passage des jeunes',
                'passage_enfants': 'Passage des enfants',
                'passage_classe_communautaire': 'Passage de la classe communautaire',
                'passage_predicateurs': 'Passage des prédicateurs',
                'passage_conseil': 'Passage du conseil',
                'passage_assemble': 'Passage de l\'assemblée'
            };

            const venteCategories = {
                'aliments': 'Vente d\'aliments',
                'arbres_vie': 'Vente d\'arbres de vie',
                'americaine': 'Vente américaine'
            };

            const engagementCategories = {
                'entite_physique': 'Personne physique',
                'entite_morale': 'Entité morale'
            };

            let passageCounter = 0;
            let venteCounter = 0;
            let engagementCounter = 0;

            // Gestion des onglets de configuration
            function switchTab(tabName) {
                // Masquer tous les contenus
                document.querySelectorAll('.config-tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Réinitialiser tous les boutons d'onglet
                document.querySelectorAll('.config-tab-button').forEach(button => {
                    button.classList.remove('border-blue-500', 'text-blue-600');
                    button.classList.add('border-transparent', 'text-slate-500');
                });

                // Afficher le contenu sélectionné
                document.getElementById('config-' + tabName).classList.remove('hidden');

                // Activer le bouton d'onglet sélectionné
                const activeTab = document.getElementById('config-tab-' + tabName);
                activeTab.classList.remove('border-transparent', 'text-slate-500');
                activeTab.classList.add('border-blue-500', 'text-blue-600');
            }

            // Gestion des passages bibliques
            function addPassage() {
                const container = document.getElementById('passages-container');
                const newPassage = document.createElement('div');
                newPassage.className = 'passage-item flex items-center gap-4';
                newPassage.innerHTML = `
                    <input type="text" name="passages_bibliques[]" placeholder="Ex: Jean 4:35"
                        class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <button type="button" onclick="removePassage(this)"
                        class="w-10 h-10 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors">
                        <i class="fas fa-minus"></i>
                    </button>
                `;
                container.appendChild(newPassage);
                updateRemoveButtons();
            }

            function removePassage(button) {
                const container = document.getElementById('passages-container');
                if (container.children.length > 1) {
                    button.closest('.passage-item').remove();
                    updateRemoveButtons();
                }
            }

            function updateRemoveButtons() {
                const container = document.getElementById('passages-container');
                const removeButtons = container.querySelectorAll('.passage-item button');
                removeButtons.forEach((button, index) => {
                    if (container.children.length === 1) {
                        button.classList.add('opacity-50', 'cursor-not-allowed');
                        button.disabled = true;
                    } else {
                        button.classList.remove('opacity-50', 'cursor-not-allowed');
                        button.disabled = false;
                    }
                });
            }

            // Gestion des configurations de passage
            function addPassageConfig() {
                const container = document.getElementById('passages-config-container');
                const id = `passage_${passageCounter++}`;

                const configDiv = document.createElement('div');
                configDiv.className = 'bg-slate-50 rounded-xl p-4 space-y-4';
                configDiv.innerHTML = `
                    <div class="flex items-center justify-between">
                        <h5 class="font-medium text-slate-800">Passage #${passageCounter}</h5>
                        <button type="button" onclick="removeConfig(this)"
                            class="text-red-600 hover:text-red-700 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                            <select name="passages[${id}][categorie]" onchange="toggleClasseField(this)" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Sélectionnez une catégorie</option>
                                ${Object.entries(passageCategories).map(([key, label]) =>
                                    `<option value="${key}">${label}</option>`
                                ).join('')}
                            </select>
                        </div>
                        <div class="classe-field hidden">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Classe</label>
                            <select name="passages[${id}][classe_id]"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Sélectionnez une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}">{{ $classe->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Objectif (FCFA)</label>
                            <input type="number" name="passages[${id}][cible]" min="1" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <select name="ventes[${id}][status]"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="0">Inactif</option>
                                <option value="1">Actif</option>
                            </select>
                        </div>
                    </div>
                `;
                container.appendChild(configDiv);
            }

            function addEngagementConfig() {
                const container = document.getElementById('engagements-config-container');
                const id = `engagement_${engagementCounter++}`;

                const configDiv = document.createElement('div');
                configDiv.className = 'bg-slate-50 rounded-xl p-4 space-y-4';
                configDiv.innerHTML = `
                    <div class="flex items-center justify-between">
                        <h5 class="font-medium text-slate-800">Engagement #${engagementCounter}</h5>
                        <button type="button" onclick="removeConfig(this)"
                            class="text-red-600 hover:text-red-700 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Type d'engagement</label>
                            <select name="engagements[${id}][categorie]" onchange="toggleDonateurField(this)" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Sélectionnez un type</option>
                                ${Object.entries(engagementCategories).map(([key, label]) =>
                                    `<option value="${key}">${label}</option>`
                                ).join('')}
                            </select>
                        </div>
                        <div class="donateur-field hidden">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Personne</label>
                            <select name="engagements[${id}][donateur_id]"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Sélectionnez une personne</option>
                                @foreach($responsables as $responsable)
                                    <option value="{{ $responsable->id }}">{{ $responsable->nom_complet }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="entite-field hidden">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nom de l'entité</label>
                            <input type="text" name="engagements[${id}][nom_entite]"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ex: Entreprise ABC">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Montant engagé (FCFA)</label>
                            <input type="number" name="engagements[${id}][cible]" min="1" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date d'échéance</label>
                            <input type="date" name="engagements[${id}][date_echeance]"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Téléphone</label>
                            <input type="tel" name="engagements[${id}][telephone]"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="+225 XX XX XX XX XX">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                            <input type="email" name="engagements[${id}][email]"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="email@exemple.com">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea name="engagements[${id}][description]" rows="3"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Détails de l'engagement..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <select name="engagements[${id}][status]"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="0">Inactif</option>
                                <option value="1">Actif</option>
                            </select>
                        </div>
                    </div>
                `;
                container.appendChild(configDiv);
            }

            function removeConfig(button) {
                button.closest('.bg-slate-50').remove();
            }

            function toggleClasseField(select) {
                const classeField = select.closest('.grid').querySelector('.classe-field');
                const classeSelect = classeField.querySelector('select');

                if (select.value === 'passage_classe_communautaire') {
                    classeField.classList.remove('hidden');
                    classeSelect.required = true;
                } else {
                    classeField.classList.add('hidden');
                    classeSelect.required = false;
                    classeSelect.value = '';
                }
            }

            function toggleDonateurField(select) {
                const parent = select.closest('.grid');
                const donateurField = parent.querySelector('.donateur-field');
                const entiteField = parent.querySelector('.entite-field');
                const donateurSelect = donateurField.querySelector('select');
                const entiteInput = entiteField.querySelector('input');

                if (select.value === 'entite_physique') {
                    donateurField.classList.remove('hidden');
                    entiteField.classList.add('hidden');
                    donateurSelect.required = true;
                    entiteInput.required = false;
                    entiteInput.value = '';
                } else if (select.value === 'entite_morale') {
                    donateurField.classList.add('hidden');
                    entiteField.classList.remove('hidden');
                    donateurSelect.required = false;
                    donateurSelect.value = '';
                    entiteInput.required = true;
                } else {
                    donateurField.classList.add('hidden');
                    entiteField.classList.add('hidden');
                    donateurSelect.required = false;
                    entiteInput.required = false;
                    donateurSelect.value = '';
                    entiteInput.value = '';
                }
            }


            function addVenteConfig() {
                const container = document.getElementById('ventes-config-container');
                const id = `vente_${venteCounter++}`;

                const configDiv = document.createElement('div');
                configDiv.className = 'bg-slate-50 rounded-xl p-4 space-y-4';
                configDiv.innerHTML = `
                    <div class="flex items-center justify-between">
                        <h5 class="font-medium text-slate-800">Vente #${venteCounter}</h5>
                        <button type="button" onclick="removeConfig(this)"
                            class="text-red-600 hover:text-red-700 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Type de vente</label>
                            <select name="ventes[${id}][categorie]" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Sélectionnez un type</option>
                                ${Object.entries(venteCategories).map(([key, label]) =>
                                    `<option value="${key}">${label}</option>`
                                ).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Objectif (FCFA)</label>
                            <input type="number" name="ventes[${id}][cible]" min="1" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea name="ventes[${id}][description]" rows="3"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Description de la vente..."></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <select name="passages[${id}][status]"
                                class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="0">Inactif</option>
                                <option value="1">Actif</option>
                            </select>
                        </div>
                    </div>
                `;
                container.appendChild(configDiv);
            }


            // Validation du formulaire
            document.getElementById('moisson-form').addEventListener('submit', function(e) {
                const theme = document.getElementById('theme').value.trim();
                const date = document.getElementById('date').value;
                const cible = document.getElementById('cible').value;
                const culte = document.getElementById('culte_id').value;

                if (!theme || !date || !cible || !culte) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires.');
                    return;
                }

                if (parseInt(cible) < 1) {
                    e.preventDefault();
                    alert('L\'objectif financier doit être supérieur à 0.');
                    return;
                }

                // Vérifier que la date n'est pas dans le passé
                const selectedDate = new Date(date);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                if (selectedDate < today) {
                    if (!confirm('La date sélectionnée est dans le passé. Voulez-vous continuer ?')) {
                        e.preventDefault();
                        return;
                    }
                }
            });

            // Initialisation
            document.addEventListener('DOMContentLoaded', function() {
                // Initialiser la première section active
                updateRemoveButtons();

                // Animation des cartes au chargement
                const cards = document.querySelectorAll('.bg-white\\/80');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });

                // Définir la date par défaut (aujourd'hui + 7 jours)
                const dateInput = document.getElementById('date');
                const futureDate = new Date();
                futureDate.setDate(futureDate.getDate() + 7);
                dateInput.value = futureDate.toISOString().split('T')[0];
            });
        </script>
    @endpush
@endsection


