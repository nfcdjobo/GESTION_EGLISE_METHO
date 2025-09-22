@extends('layouts.private.main')
@section('title', 'Modifier la classe - ' . $classe->nom)

@section('content')
    <div class="space-y-8">
        <!-- En-tête de page -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Modifier la classe</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.classes.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-users  mr-2"></i>
                            Classes
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-medium text-slate-500">Modification de "{{ $classe->nom }}" -
                                {{ $classe->nombre_inscrits }} membre(s) inscrit(s)</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Formulaire de modification -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-edit text-yellow-600 mr-2"></i>
                    Modifier les informations
                </h2>
            </div>

            <form action="{{ route('private.classes.update', $classe) }}" method="POST" enctype="multipart/form-data"
                class="p-6" id="classe-form">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Colonne gauche - Informations de base -->
                    <div class="space-y-6">
                        <!-- Nom de la classe -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">
                                Nom de la classe <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom', $classe->nom) }}" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom') border-red-500 @enderror"
                                placeholder="Ex: Classe préparatoire A">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror"
                                placeholder="Description détaillée de la classe...">{{ old('description', $classe->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tranche d'âge -->
                        <div>
                            <label for="tranche_age" class="block text-sm font-medium text-slate-700 mb-2">
                                Tranche d'âge
                            </label>
                            <select id="tranche_age" name="tranche_age"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('tranche_age') border-red-500 @enderror">
                                <option value="">Sélectionner une tranche d'âge</option>
                                @foreach($tranches_age as $tranche)
                                    <option value="{{ $tranche }}" {{ old('tranche_age', $classe->tranche_age) == $tranche ? 'selected' : '' }}>
                                        {{ $tranche }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tranche_age')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Âges spécifiques -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="age_minimum" class="block text-sm font-medium text-slate-700 mb-2">
                                    Âge minimum
                                </label>
                                <input type="number" id="age_minimum" name="age_minimum"
                                    value="{{ old('age_minimum', $classe->age_minimum) }}" min="0" max="120"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('age_minimum') border-red-500 @enderror"
                                    placeholder="Ex: 6">
                                @error('age_minimum')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="age_maximum" class="block text-sm font-medium text-slate-700 mb-2">
                                    Âge maximum
                                </label>
                                <input type="number" id="age_maximum" name="age_maximum"
                                    value="{{ old('age_maximum', $classe->age_maximum) }}" min="0" max="120"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('age_maximum') border-red-500 @enderror"
                                    placeholder="Ex: 12">
                                @error('age_maximum')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Image de la classe -->
                        <div>
                            <label for="image_classe" class="block text-sm font-medium text-slate-700 mb-2">
                                Image de la classe
                            </label>
                            <div
                                class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-slate-400 transition-colors">
                                <input type="file" id="image_classe" name="image_classe" accept="image/*" class="hidden"
                                    onchange="previewImage(this)">

                                @if($classe->image_classe)
                                    <div id="current-image" class="mb-4">
                                        <img src="{{ asset('storage/' . $classe->image_classe) }}" alt="Image actuelle"
                                            class="mx-auto max-h-32 rounded-lg mb-3">
                                        <p class="text-sm text-slate-600 mb-2">Image actuelle</p>
                                        <button type="button" onclick="showUploadNew()"
                                            class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                                            Changer l'image
                                        </button>
                                    </div>
                                @endif

                                <div id="image-preview" class="hidden">
                                    <img id="preview-img" src="" alt="Aperçu" class="mx-auto max-h-32 rounded-lg mb-3">
                                    <button type="button" onclick="removeImage()"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                    </button>
                                </div>

                                <div id="upload-placeholder" class="{{ $classe->image_classe ? 'hidden' : '' }}">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-3"></i>
                                    <p class="text-slate-600 mb-2">Cliquez pour ajouter une image</p>
                                    <button type="button" onclick="document.getElementById('image_classe').click()"
                                        class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                                        Choisir un fichier
                                    </button>
                                </div>
                            </div>
                            @error('image_classe')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Colonne droite - Responsables et programme -->
                    <div class="space-y-6">
                        <!-- Responsables -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-4">
                                Responsables de la classe
                            </label>
                            <div id="responsables-container" class="space-y-4">
                                <!-- Les responsables seront ajoutés dynamiquement -->
                            </div>

                            <button type="button" id="add-responsable-btn" onclick="addResponsable()"
                                class="mt-3 inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Ajouter un responsable
                            </button>

                            @if($utilisateurs->isEmpty())
                                <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-triangle text-amber-600 mr-2"></i>
                                        <span class="text-sm text-amber-700">
                                            Aucun utilisateur disponible pour être responsable. Tous les utilisateurs sont déjà
                                            inscrits dans des classes.
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Programme -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-4">
                                Programme de la classe
                            </label>
                            <div id="programme-container" class="space-y-3">
                                @if($classe->programme && count($classe->programme) > 0)
                                    @foreach($classe->programme as $index => $element)
                                        <div class="programme-item flex gap-3">
                                            <input type="text" name="programme[]" value="{{ old("programme.{$index}", $element) }}"
                                                class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Ex: Mathématiques de base">
                                            <button type="button" onclick="removeProgrammeItem(this)"
                                                class="w-10 h-10 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Template par défaut si aucun programme -->
                                    <div class="programme-item flex gap-3">
                                        <input type="text" name="programme[]"
                                            class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Ex: Mathématiques de base">
                                        <button type="button" onclick="removeProgrammeItem(this)"
                                            class="w-10 h-10 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <button type="button" onclick="addProgrammeItem()"
                                class="mt-3 inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Ajouter un élément
                            </button>
                        </div>

                        <!-- Statistiques actuelles (lecture seule) -->
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                            <h3 class="text-sm font-medium text-blue-900 mb-3">Statistiques actuelles</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-blue-700">Membres inscrits:</span>
                                    <span class="font-medium text-blue-900">{{ $classe->nombre_inscrits }}</span>
                                </div>
                                <div>
                                    <span class="text-blue-700">Responsables:</span>
                                    <span class="font-medium text-blue-900">{{ count($classe->responsables ?? []) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-200">
                    <div class="flex items-center space-x-3">
                        @can('classes.duplicate')
                            <form action="{{ route('private.classes.duplicate', $classe) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-cyan-100 text-cyan-700 rounded-xl hover:bg-cyan-200 transition-colors">
                                    <i class="fas fa-copy mr-2"></i> Dupliquer
                                </button>
                            </form>
                        @endcan

                        @can('classes.archive')
                            <form action="{{ route('private.classes.archive', $classe) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    onclick="return confirm('Êtes-vous sûr de vouloir archiver cette classe ?')"
                                    class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-xl hover:bg-amber-200 transition-colors">
                                    <i class="fas fa-archive mr-2"></i> Archiver
                                </button>
                            </form>
                        @endcan
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="{{ route('private.classes.show', $classe) }}"
                            class="px-6 py-3 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                            Annuler
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script>
        // Configuration et variables globales
        let responsableIndex = 0;
        let selectedUsers = new Set();
        let allUsers = [];
        let existingResponsables = [];
        let typesResponsabilite = [];
        let dropdownTimeout = null;

        // Initialisation des données depuis PHP
        document.addEventListener('DOMContentLoaded', function() {
            // Récupération sécurisée des données PHP
            try {
                allUsers = @json($utilisateurs);
                existingResponsables = @json($classe->responsables ?? []);
                typesResponsabilite = @json($types_responsabilite);

                // Conversion des IDs en string pour cohérence
                allUsers = allUsers.map(user => ({
                    ...user,
                    id: String(user.id)
                }));

                existingResponsables = existingResponsables.map(resp => ({
                    ...resp,
                    id: String(resp.id)
                }));

                console.log('Données initialisées:', { allUsers, existingResponsables });

            } catch (error) {
                console.error('Erreur lors de l\'initialisation des données:', error);
                allUsers = [];
                existingResponsables = [];
                typesResponsabilite = [];
            }

            // Initialisation des responsables existants
            existingResponsables.forEach(responsable => {
                selectedUsers.add(String(responsable.id));
                createResponsableItem(responsable);
            });

            initializeEventListeners();
            updateAddResponsableButton();
        });

        // Initialisation des écouteurs d'événements
        function initializeEventListeners() {
            const form = document.getElementById('classe-form');
            const ageMin = document.getElementById('age_minimum');
            const ageMax = document.getElementById('age_maximum');

            // Validation des âges
            function validateAges() {
                const min = parseInt(ageMin.value);
                const max = parseInt(ageMax.value);

                if (min && max && min > max) {
                    ageMax.setCustomValidity('L\'âge maximum doit être supérieur à l\'âge minimum');
                } else {
                    ageMax.setCustomValidity('');
                }
            }

            ageMin?.addEventListener('input', validateAges);
            ageMax?.addEventListener('input', validateAges);

            // Validation du formulaire
            form?.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                }
            });

            // Fermeture des dropdowns au clic externe
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.responsable-search-container')) {
                    closeAllDropdowns();
                }
            });
        }

        // Validation du formulaire
        function validateForm() {
            // Vérification qu'un seul responsable supérieur est sélectionné
            const superieurs = document.querySelectorAll('input[name*="[superieur]"]:checked');
            if (superieurs.length > 1) {
                alert('Une seule personne peut être désignée comme responsable supérieur');
                return false;
            }

            // Vérification que tous les responsables ont un utilisateur sélectionné
            const responsableItems = document.querySelectorAll('.responsable-item');
            for (let item of responsableItems) {
                const select = item.querySelector('.user-select');
                if (!select || !select.value) {
                    alert('Veuillez sélectionner un utilisateur pour chaque responsable');
                    return false;
                }
            }

            return true;
        }

        // Création d'un élément responsable
        function createResponsableItem(responsable = null) {
            const container = document.getElementById('responsables-container');
            if (!container) return;

            const currentIndex = responsableIndex++;
            const responsableDiv = document.createElement('div');
            responsableDiv.className = 'responsable-item bg-slate-50 p-4 rounded-xl border border-slate-200';
            responsableDiv.dataset.index = currentIndex;

            let selectedUser = null;
            let userName = '';

            if (responsable) {
                selectedUser = allUsers.find(u => String(u.id) === String(responsable.id));
                userName = selectedUser ? `${selectedUser.prenom} ${selectedUser.nom}` : 'Utilisateur non trouvé';
            }

            responsableDiv.innerHTML = `
                <div class="grid grid-cols-12 gap-3 items-end">
                    <div class="col-span-5">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Utilisateur</label>
                        <div class="relative responsable-search-container">
                            <input type="text"
                                class="user-search w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Rechercher un utilisateur..."
                                value="${userName}"
                                autocomplete="off"
                                data-index="${currentIndex}">
                            <select name="responsables[${currentIndex}][id]" class="hidden user-select" required>
                                <option value="">Sélectionner un utilisateur</option>
                                ${responsable ? `<option value="${responsable.id}" selected>${userName}</option>` : ''}
                            </select>
                            <button type="button" class="clear-user-btn absolute right-2 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-red-500 ${!responsable ? 'hidden' : ''}"
                                title="Désélectionner">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                            <div class="user-dropdown absolute z-20 w-full bg-white border border-slate-300 rounded-lg shadow-lg mt-1 max-h-40 overflow-y-auto hidden">
                            </div>
                        </div>
                    </div>
                    <div class="col-span-4">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Responsabilité</label>
                        <select name="responsables[${currentIndex}][responsabilite]"
                            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Type de responsabilité</option>
                            ${typesResponsabilite.map(type =>
                                `<option value="${type}" ${responsable && responsable.responsabilite === type ? 'selected' : ''}>${type.charAt(0).toUpperCase() + type.slice(1)}</option>`
                            ).join('')}
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Supérieur</label>
                        <div class="flex items-center justify-center">
                            <input type="checkbox" name="responsables[${currentIndex}][superieur]" value="1"
                                ${responsable && responsable.superieur ? 'checked' : ''}
                                class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 superieur-checkbox">
                        </div>
                    </div>
                    <div class="col-span-1">
                        <button type="button" class="remove-responsable-btn w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(responsableDiv);
            initializeResponsableEvents(responsableDiv);
            return responsableDiv;
        }

        // Initialisation des événements pour un responsable
        function initializeResponsableEvents(responsableDiv) {
            const searchInput = responsableDiv.querySelector('.user-search');
            const clearBtn = responsableDiv.querySelector('.clear-user-btn');
            const removeBtn = responsableDiv.querySelector('.remove-responsable-btn');
            const superieurCheckbox = responsableDiv.querySelector('.superieur-checkbox');

            // Événements de recherche
            searchInput.addEventListener('input', function() {
                handleUserSearch(this);
            });

            searchInput.addEventListener('focus', function() {
                showUserDropdown(this);
            });

            searchInput.addEventListener('blur', function() {
                scheduleDropdownHide(this);
            });

            // Bouton de suppression d'utilisateur
            clearBtn.addEventListener('click', function() {
                clearSelectedUser(this);
            });

            // Bouton de suppression de responsable
            removeBtn.addEventListener('click', function() {
                removeResponsable(this);
            });

            // Checkbox supérieur
            superieurCheckbox.addEventListener('change', function() {
                handleSuperieurChange(this);
            });

            // Préparer les données du dropdown SANS l'afficher
            updateUserDropdownData(responsableDiv);
        }

        // Ajout d'un nouveau responsable
        function addResponsable() {
            const availableUsers = getAvailableUsers();
            if (availableUsers.length === 0) {
                alert('Tous les utilisateurs disponibles sont déjà sélectionnés');
                return;
            }

            createResponsableItem();
            updateAddResponsableButton();
        }

        // Suppression d'un responsable
        function removeResponsable(button) {
            const responsableItem = button.closest('.responsable-item');
            const userSelect = responsableItem.querySelector('.user-select');

            if (userSelect && userSelect.value) {
                selectedUsers.delete(String(userSelect.value));
            }

            responsableItem.remove();
            updateAllDropdowns();
            updateAddResponsableButton();
        }

        // Gestion de la recherche d'utilisateurs
        function handleUserSearch(input) {
            const responsableDiv = input.closest('.responsable-item');
            const dropdown = responsableDiv.querySelector('.user-dropdown');
            const searchTerm = input.value.toLowerCase().trim();

            if (!searchTerm) {
                updateUserDropdown(responsableDiv);
                return;
            }

            const availableUsers = getAvailableUsers().filter(user =>
                user.prenom.toLowerCase().includes(searchTerm) ||
                user.nom.toLowerCase().includes(searchTerm) ||
                (user.email && user.email.toLowerCase().includes(searchTerm))
            );

            displayUsers(dropdown, availableUsers, input);
        }

        // Affichage des utilisateurs dans le dropdown
        function displayUsers(dropdown, users, searchInput) {
            dropdown.innerHTML = '';

            if (users.length === 0) {
                dropdown.innerHTML = '<div class="px-3 py-2 text-sm text-slate-500">Aucun utilisateur trouvé</div>';
            } else {
                users.forEach(user => {
                    const option = document.createElement('div');
                    option.className = 'px-3 py-2 hover:bg-slate-100 cursor-pointer text-sm user-option';
                    option.innerHTML = `
                        <div class="font-medium">${escapeHtml(user.prenom)} ${escapeHtml(user.nom)}</div>
                        <div class="text-xs text-slate-500">${user.email ? escapeHtml(user.email) : 'Aucun email disponible'}</div>
                    `;

                    option.addEventListener('mousedown', function(e) {
                        e.preventDefault(); // Empêche le blur de l'input
                        selectUser(searchInput, user);
                    });

                    dropdown.appendChild(option);
                });
            }

            dropdown.classList.remove('hidden');
        }

        // Sélection d'un utilisateur
        function selectUser(searchInput, user) {
            const responsableDiv = searchInput.closest('.responsable-item');
            const userSelect = responsableDiv.querySelector('.user-select');
            const clearBtn = responsableDiv.querySelector('.clear-user-btn');
            const dropdown = responsableDiv.querySelector('.user-dropdown');

            // Retirer l'ancien utilisateur s'il y en avait un
            if (userSelect.value) {
                selectedUsers.delete(String(userSelect.value));
            }

            // Ajouter le nouveau utilisateur
            selectedUsers.add(String(user.id));

            // Mettre à jour l'interface
            searchInput.value = `${user.prenom} ${user.nom}`;

            // Mettre à jour le select
            userSelect.value = String(user.id);

            // Créer/mettre à jour l'option dans le select
            let existingOption = userSelect.querySelector(`option[value="${user.id}"]`);
            if (!existingOption) {
                const option = document.createElement('option');
                option.value = String(user.id);
                option.textContent = `${user.prenom} ${user.nom}`;
                option.selected = true;
                userSelect.appendChild(option);
            } else {
                existingOption.selected = true;
            }

            // Afficher le bouton de suppression
            clearBtn.classList.remove('hidden');

            // Cacher le dropdown actuel
            dropdown.classList.add('hidden');

            // Mettre à jour seulement les autres dropdowns (pas celui actuel)
            updateOtherDropdowns(responsableDiv);
            updateAddResponsableButton();
        }

        // Effacer la sélection d'utilisateur
        function clearSelectedUser(button) {
            const responsableDiv = button.closest('.responsable-item');
            const searchInput = responsableDiv.querySelector('.user-search');
            const userSelect = responsableDiv.querySelector('.user-select');
            const clearBtn = button;

            if (userSelect.value) {
                selectedUsers.delete(String(userSelect.value));
            }

            // Réinitialiser l'interface
            searchInput.value = '';
            userSelect.value = '';
            clearBtn.classList.add('hidden');

            // Supprimer les options du select (sauf la première)
            const options = userSelect.querySelectorAll('option');
            options.forEach((option, index) => {
                if (index > 0) {
                    option.remove();
                }
            });

            updateOtherDropdowns(responsableDiv);
            updateAddResponsableButton();
        }

        // Affichage du dropdown utilisateur
        function showUserDropdown(input) {
            const responsableDiv = input.closest('.responsable-item');
            const dropdown = responsableDiv.querySelector('.user-dropdown');
            const userSelect = responsableDiv.querySelector('.user-select');

            // Si un utilisateur est déjà sélectionné et l'input est vide, ne pas montrer le dropdown
            if (userSelect.value && !input.value) {
                return;
            }

            clearTimeout(dropdownTimeout);
            updateUserDropdown(responsableDiv);
        }

        // Programmation de la fermeture du dropdown
        function scheduleDropdownHide(input) {
            dropdownTimeout = setTimeout(() => {
                const responsableDiv = input.closest('.responsable-item');
                const dropdown = responsableDiv.querySelector('.user-dropdown');
                dropdown.classList.add('hidden');
            }, 150);
        }

        // Mise à jour des autres dropdowns (exclut le dropdown actuel)
        function updateOtherDropdowns(currentResponsableDiv) {
            document.querySelectorAll('.responsable-item').forEach(responsableDiv => {
                if (responsableDiv !== currentResponsableDiv) {
                    const dropdown = responsableDiv.querySelector('.user-dropdown');
                    // Ne mettre à jour que si le dropdown n'est pas visible
                    if (dropdown.classList.contains('hidden')) {
                        updateUserDropdownData(responsableDiv);
                    }
                }
            });
        }

        // Mise à jour du dropdown d'un responsable
        function updateUserDropdown(responsableDiv) {
            const dropdown = responsableDiv.querySelector('.user-dropdown');
            const searchInput = responsableDiv.querySelector('.user-search');
            const availableUsers = getAvailableUsers();

            displayUsers(dropdown, availableUsers, searchInput);
        }

        // Mise à jour des données du dropdown sans l'afficher
        function updateUserDropdownData(responsableDiv) {
            const dropdown = responsableDiv.querySelector('.user-dropdown');
            const searchInput = responsableDiv.querySelector('.user-search');
            const availableUsers = getAvailableUsers();

            // Mettre à jour le contenu sans afficher le dropdown
            dropdown.innerHTML = '';
            availableUsers.forEach(user => {
                const option = document.createElement('div');
                option.className = 'px-3 py-2 hover:bg-slate-100 cursor-pointer text-sm user-option';
                option.innerHTML = `
                    <div class="font-medium">${escapeHtml(user.prenom)} ${escapeHtml(user.nom)}</div>
                    <div class="text-xs text-slate-500">${user.email ? escapeHtml(user.email) : 'Aucun email disponible'}</div>
                `;

                option.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    selectUser(searchInput, user);
                });

                dropdown.appendChild(option);
            });
        }

        // Mise à jour de tous les dropdowns (utilisé seulement lors du remove)
        function updateAllDropdowns() {
            document.querySelectorAll('.responsable-item').forEach(responsableDiv => {
                updateUserDropdownData(responsableDiv);
            });
        }

        // Fermeture de tous les dropdowns
        function closeAllDropdowns() {
            document.querySelectorAll('.user-dropdown').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }

        // Obtenir les utilisateurs disponibles
        function getAvailableUsers() {
            return allUsers.filter(user => !selectedUsers.has(String(user.id)));
        }

        // Mise à jour du bouton d'ajout de responsable
        function updateAddResponsableButton() {
            const addButton = document.getElementById('add-responsable-btn');
            if (!addButton) return;

            const availableUsers = getAvailableUsers();

            if (availableUsers.length === 0) {
                addButton.disabled = true;
                addButton.classList.add('opacity-50', 'cursor-not-allowed');
                addButton.title = 'Tous les utilisateurs sont déjà sélectionnés';
            } else {
                addButton.disabled = false;
                addButton.classList.remove('opacity-50', 'cursor-not-allowed');
                addButton.title = '';
            }
        }

        // Gestion du responsable supérieur
        function handleSuperieurChange(checkbox) {
            if (checkbox.checked) {
                // Décocher tous les autres checkboxes supérieur
                document.querySelectorAll('.superieur-checkbox').forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
            }
        }

        // Échappement HTML pour sécurité
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        // Gestion de l'aperçu d'image
        function previewImage(input) {
            const file = input.files[0];
            if (file) {
                if (file.size > 5 * 1024 * 1024) { // 5MB max
                    alert('Le fichier est trop volumineux. Taille maximale : 5MB');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                    const currentImage = document.getElementById('current-image');
                    if (currentImage) currentImage.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function showUploadNew() {
            const currentImage = document.getElementById('current-image');
            const uploadPlaceholder = document.getElementById('upload-placeholder');

            if (currentImage) currentImage.classList.add('hidden');
            if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
        }

        function removeImage() {
            const imageInput = document.getElementById('image_classe');
            const imagePreview = document.getElementById('image-preview');
            const uploadPlaceholder = document.getElementById('upload-placeholder');
            const currentImage = document.getElementById('current-image');

            if (imageInput) imageInput.value = '';
            if (imagePreview) imagePreview.classList.add('hidden');
            if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
            if (currentImage) currentImage.classList.remove('hidden');
        }

        // Gestion du programme
        function addProgrammeItem() {
            const container = document.getElementById('programme-container');
            if (!container) return;

            const programmeDiv = document.createElement('div');
            programmeDiv.className = 'programme-item flex gap-3';
            programmeDiv.innerHTML = `
                <input type="text" name="programme[]"
                    class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Ex: Mathématiques de base">
                <button type="button" onclick="removeProgrammeItem(this)"
                    class="w-10 h-10 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            `;
            container.appendChild(programmeDiv);
        }

        function removeProgrammeItem(button) {
            const container = document.getElementById('programme-container');
            const item = button.closest('.programme-item');

            // Garder au moins un élément de programme
            if (container.children.length > 1) {
                item.remove();
            } else {
                // Si c'est le dernier élément, vider juste l'input
                const input = item.querySelector('input[name="programme[]"]');
                if (input) input.value = '';
            }
        }
    </script>

@endsection
