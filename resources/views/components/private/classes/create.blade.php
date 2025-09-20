@extends('layouts.private.main')
@section('title', 'Créer une nouvelle classe')

@section('content')
    <div class="space-y-8">
        <!-- En-tête de page -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('private.classes.index') }}"
                    class="inline-flex items-center text-slate-600 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la liste
                </a>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Créer une nouvelle classe
            </h1>
            <p class="text-slate-500 mt-1">
                Ajoutez une nouvelle classe avec ses responsables et paramètres
            </p>
        </div>

        <!-- Formulaire de création -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-plus-circle text-blue-600 mr-2"></i>
                    Informations de la classe
                </h2>
            </div>

            <form action="{{ route('private.classes.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Colonne gauche - Informations de base -->
                    <div class="space-y-6">
                        <!-- Nom de la classe -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">
                                Nom de la classe <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required
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
                                placeholder="Description détaillée de la classe...">{{ old('description') }}</textarea>
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
                                    <option value="{{ $tranche }}" {{ old('tranche_age') == $tranche ? 'selected' : '' }}>
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
                                <input type="number" id="age_minimum" name="age_minimum" value="{{ old('age_minimum') }}"
                                    min="0" max="120"
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
                                <input type="number" id="age_maximum" name="age_maximum" value="{{ old('age_maximum') }}"
                                    min="0" max="120"
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
                                <div id="image-preview" class="hidden">
                                    <img id="preview-img" src="" alt="Aperçu" class="mx-auto max-h-32 rounded-lg mb-3">
                                    <button type="button" onclick="removeImage()"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                    </button>
                                </div>
                                <div id="upload-placeholder">
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
                                <!-- Le premier responsable sera ajouté automatiquement -->
                            </div>

                            <button type="button" onclick="addResponsable()"
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
                                <!-- Template pour les éléments du programme -->
                                <div class="programme-item flex gap-3">
                                    <input type="text" name="programme[]"
                                        class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Ex: Mathématiques de base">
                                    <button type="button" onclick="removeProgrammeItem(this)"
                                        class="w-10 h-10 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            <button type="button" onclick="addProgrammeItem()"
                                class="mt-3 inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Ajouter un élément
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-slate-200">
                    <a href="{{ route('private.classes.index') }}"
                        class="px-6 py-3 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer la classe
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts JavaScript -->



    <script>
        let responsableIndex = 1;
        let programmeIndex = 1;
        let selectedUsers = new Set(); // Pour suivre les utilisateurs sélectionnés
        let allUsers = @json($utilisateurs); // Tous les utilisateurs disponibles

        // Gestion de l'aperçu d'image
        function previewImage(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            document.getElementById('image_classe').value = '';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('upload-placeholder').classList.remove('hidden');
        }

        // Vérifier et mettre à jour l'état du bouton "Ajouter un responsable"
        // Version alternative plus robuste
        function updateAddResponsableButton() {
            // Attendre que le DOM soit mis à jour
            setTimeout(() => {
                alert(85)
                const addButton = document.querySelector('button[onclick="addResponsable()"]');
                if (!addButton) {
                    console.error('Bouton "Ajouter un responsable" non trouvé');
                    return;
                }

                const availableUsers = allUsers.filter(user => !selectedUsers.has(user.id));

                if (availableUsers.length === 0) {
                    // Désactiver le bouton
                    addButton.disabled = true;
                    addButton.style.opacity = '0.5';
                    addButton.style.cursor = 'not-allowed';
                    addButton.title = 'Tous les utilisateurs sont déjà sélectionnés';

                    // Supprimer les événements hover
                    addButton.onmouseenter = null;
                    addButton.onmouseleave = null;
                } else {
                    // Activer le bouton
                    addButton.disabled = false;
                    addButton.style.opacity = '1';
                    addButton.style.cursor = 'pointer';
                    addButton.title = '';

                    // Rétablir les effets hover
                    addButton.onmouseenter = function () {
                        this.style.backgroundColor = 'rgb(187 247 208)'; // bg-green-200
                    };
                    addButton.onmouseleave = function () {
                        this.style.backgroundColor = 'rgb(220 252 231)'; // bg-green-100
                    };
                }

                console.log('Bouton mis à jour - Disponible:', !addButton.disabled);
            }, 10);
        }

        // Gestion des responsables avec recherche
        function addResponsable() {
            // Vérifier s'il y a encore des utilisateurs disponibles
            const availableUsers = allUsers.filter(user => !selectedUsers.has(user.id));
            if (availableUsers.length === 0) {
                alert('Tous les utilisateurs disponibles sont déjà sélectionnés');
                return;
            }

            const container = document.getElementById('responsables-container');

            const responsableDiv = document.createElement('div');
            responsableDiv.className = 'responsable-item bg-slate-50 p-4 rounded-xl border border-slate-200';
            // Dans la fonction addResponsable, s'assurer que le select a la structure correcte
            responsableDiv.innerHTML = `
                <div class="grid grid-cols-12 gap-3 items-end">
                    <div class="col-span-5">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Utilisateur</label>
                        <div class="relative">
                            <input type="text"
                                class="user-search w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Rechercher un utilisateur..."
                                autocomplete="off"
                                onkeyup="searchUsers(this)"
                                onfocus="showUserDropdown(this)"
                                onblur="hideUserDropdown(this)">
                            <select name="responsables[${responsableIndex}][id]" class="hidden user-select">
                                <option value="">Sélectionner un utilisateur</option>
                            </select>
                            <!-- Bouton pour désélectionner -->
                            <button type="button" class="clear-user-btn absolute right-2 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-red-500 hidden" onclick="clearSelectedUser(this)" title="Désélectionner">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                            <div class="user-dropdown absolute z-10 w-full bg-white border border-slate-300 rounded-lg shadow-lg mt-1 max-h-40 overflow-y-auto hidden">
                                <!-- Options dynamiques -->
                            </div>
                        </div>
                    </div>
                    <div class="col-span-4">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Responsabilité</label>
                        <select name="responsables[${responsableIndex}][responsabilite]" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Type de responsabilité</option>
                            @foreach($types_responsabilite as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-slate-600 mb-1">Supérieur</label>
                        <div class="flex items-center justify-center">
                            <input type="checkbox" name="responsables[${responsableIndex}][superieur]" value="1"
                                class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500"
                                onchange="handleSuperieurChange(this)">
                        </div>
                    </div>
                    <div class="col-span-1">
                        <button type="button" onclick="removeResponsable(this)"
                            class="w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(responsableDiv);

            // Initialiser la recherche pour ce nouveau responsable
            initializeUserSearch(responsableDiv);

            responsableIndex++;

            // Mettre à jour l'état du bouton
            updateAddResponsableButton();
        }

        function removeResponsable(button) {
            const responsableItem = button.closest('.responsable-item');
            const userSelect = responsableItem.querySelector('.user-select');

            // Retirer l'utilisateur de la liste des sélectionnés
            if (userSelect.value) {
                selectedUsers.delete(userSelect.value);
                console.log('Responsable supprimé, utilisateur libéré:', userSelect.value); // Debug
            }

            // Supprimer l'élément
            responsableItem.remove();

            // Mettre à jour tous les dropdowns et le bouton
            updateAllUserDropdowns();
            updateAddResponsableButton();

            console.log('Utilisateurs sélectionnés après suppression:', Array.from(selectedUsers)); // Debug
        }


        // Fonction pour désélectionner un utilisateur
        function clearSelectedUser(button) {
            const responsableItem = button.closest('.responsable-item');
            const searchInput = responsableItem.querySelector('.user-search');
            const userSelect = responsableItem.querySelector('.user-select');
            const clearButton = button;

            // Retirer l'utilisateur de la liste des sélectionnés
            if (userSelect.value) {
                selectedUsers.delete(userSelect.value);
                console.log('Utilisateur désélectionné:', userSelect.value);
            }

            // Réinitialiser l'interface
            searchInput.value = '';
            userSelect.value = ''; // Vider le select caché

            // Supprimer toutes les options sauf la première (option vide)
            const options = userSelect.querySelectorAll('option');
            options.forEach((option, index) => {
                if (index > 0) { // Garder la première option vide
                    option.remove();
                }
            });

            clearButton.classList.add('hidden');

            // Mettre à jour tous les dropdowns et le bouton
            updateAllUserDropdowns();
            updateAddResponsableButton();

            // Afficher le dropdown mis à jour
            showUserDropdown(searchInput);

            console.log('Select value after clear:', userSelect.value);
        }


        // Initialiser la recherche d'utilisateur pour un élément responsable
        function initializeUserSearch(responsableElement) {
            const searchInput = responsableElement.querySelector('.user-search');
            const dropdown = responsableElement.querySelector('.user-dropdown');
            const select = responsableElement.querySelector('.user-select');

            // Peupler le dropdown initial
            updateUserDropdown(dropdown, select);
        }

        // Recherche d'utilisateurs
        function searchUsers(input) {
            const dropdown = input.nextElementSibling.nextElementSibling.nextElementSibling; // Skip select and clear button
            const select = input.nextElementSibling;
            const searchTerm = input.value.toLowerCase();

            // Si le champ est vide et qu'un utilisateur est sélectionné, ne pas afficher le dropdown
            if (!searchTerm && select.value) {
                dropdown.classList.add('hidden');
                return;
            }

            // Filtrer les utilisateurs disponibles
            const availableUsers = allUsers.filter(user =>
                !selectedUsers.has(user.id) &&
                (user.prenom.toLowerCase().includes(searchTerm) ||
                    user.nom.toLowerCase().includes(searchTerm) ||
                    user.telephone_1.toLowerCase().includes(searchTerm) ||
                    user.email?.toLowerCase()?.includes(searchTerm))
            );

            // Mettre à jour le dropdown
            dropdown.innerHTML = '';
            if (availableUsers.length > 0) {
                availableUsers.forEach(user => {
                    const option = document.createElement('div');
                    option.className = 'px-3 py-2 hover:bg-slate-100 cursor-pointer text-sm';
                    option.innerHTML = `
                            <div class="font-medium">${user.prenom} ${user.nom}</div>
                            <div class="text-xs text-slate-500">${user.telephone_1}</div>
                            <div class="text-xs text-slate-500">${user.email ?? 'Aucun email disponible'}</div>
                        `;
                    option.onclick = () => selectUser(input, user, select, dropdown);
                    dropdown.appendChild(option);
                });
                dropdown.classList.remove('hidden');
            } else {
                dropdown.innerHTML = '<div class="px-3 py-2 text-sm text-slate-500">Aucun utilisateur trouvé</div>';
                dropdown.classList.remove('hidden');
            }
        }

        // Fonction pour nettoyer les erreurs de validation visuelles
        function clearValidationErrors(element) {
            element.style.border = '';
        }

        // Dans la fonction selectUser, ajoutez :
        // Sélectionner un utilisateur
        function selectUser(input, user, select, dropdown) {
            alert()
            const clearButton = input.parentElement.querySelector('.clear-user-btn');

            // Retirer l'ancien utilisateur sélectionné
            if (select.value) {
                selectedUsers.delete(select.value);
            }

            // Ajouter le nouveau
            selectedUsers.add(user.id);

            // Mettre à jour l'interface
            input.value = `${user.prenom} ${user.nom}`;
            select.value = user.id; // IMPORTANT: S'assurer que le select caché a la bonne valeur
            dropdown.classList.add('hidden');
            clearButton.classList.remove('hidden');

            // Créer ou mettre à jour une option dans le select caché
            let existingOption = select.querySelector(`option[value="${user.id}"]`);
            if (!existingOption) {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.prenom} ${user.nom}`;
                option.selected = true;
                select.appendChild(option);
            } else {
                existingOption.selected = true;
            }

            // Nettoyer les erreurs de validation
            clearValidationErrors(input);

            // Mettre à jour tous les autres dropdowns et le bouton
            updateAllUserDropdowns();
            updateAddResponsableButton();

            // Debug: vérifier que la valeur est bien définie
            console.log('Select value after selection:', select.value);
            console.log('Select name:', select.name);
        }

        // Afficher le dropdown
        function showUserDropdown(input) {
            const dropdown = input.nextElementSibling.nextElementSibling.nextElementSibling;
            const select = input.nextElementSibling;

            // Si un utilisateur est déjà sélectionné et le champ est vide, ne pas afficher
            if (select.value && !input.value) {
                return;
            }

            if (!input.value) {
                updateUserDropdown(dropdown, select);
            }
            dropdown.classList.remove('hidden');
        }

        // Cacher le dropdown avec délai pour permettre la sélection
        function hideUserDropdown(input) {
            setTimeout(() => {
                const dropdown = input.nextElementSibling.nextElementSibling.nextElementSibling;
                dropdown.classList.add('hidden');
            }, 200);
        }

        // Mettre à jour un dropdown spécifique
        function updateUserDropdown(dropdown, select) {
            const availableUsers = allUsers.filter(user => !selectedUsers.has(user.id));

            dropdown.innerHTML = '';
            if (availableUsers.length > 0) {
                availableUsers.forEach(user => {
                    const option = document.createElement('div');
                    option.className = 'px-3 py-2 hover:bg-slate-100 cursor-pointer text-sm';
                    option.innerHTML = `
                            <div class="font-medium">${user.prenom} ${user.nom}</div>
                            <div class="text-xs text-slate-500">${user.telephone_1}</div>
                            <div class="text-xs text-slate-500">${user.email ?? 'Aucun email disponible'}</div>
                        `;
                    option.onclick = () => {
                        const input = dropdown.previousElementSibling.previousElementSibling.previousElementSibling;
                        selectUser(input, user, select, dropdown);
                    };
                    dropdown.appendChild(option);
                });
            } else {
                dropdown.innerHTML = '<div class="px-3 py-2 text-sm text-slate-500">Tous les utilisateurs sont sélectionnés</div>';
            }
        }

        // Mettre à jour tous les dropdowns
        function updateAllUserDropdowns() {
            document.querySelectorAll('.user-dropdown').forEach(dropdown => {
                const select = dropdown.previousElementSibling.previousElementSibling;
                updateUserDropdown(dropdown, select);
            });
        }

        // Gérer le changement de supérieur (un seul autorisé)
        function handleSuperieurChange(checkbox) {
            if (checkbox.checked) {
                // Décocher tous les autres checkboxes supérieur
                document.querySelectorAll('input[name*="[superieur]"]').forEach(cb => {
                    if (cb !== checkbox) {
                        cb.checked = false;
                    }
                });
            }
        }

        // Gestion du programme
        function addProgrammeItem() {
            const container = document.getElementById('programme-container');
            const template = container.children[0].cloneNode(true);

            // Réinitialiser la valeur
            template.querySelector('input').value = '';

            container.appendChild(template);
        }

        function removeProgrammeItem(button) {
            const container = document.getElementById('programme-container');
            if (container.children.length > 1) {
                button.closest('.programme-item').remove();
            }
        }

        // Validation côté client
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const ageMin = document.getElementById('age_minimum');
            const ageMax = document.getElementById('age_maximum');

            // Initialiser l'état du bouton
            updateAddResponsableButton();

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

            ageMin.addEventListener('input', validateAges);
            ageMax.addEventListener('input', validateAges);

            // Validation avant soumission
            // Validation avant soumission
            form.addEventListener('submit', function (e) {
                let hasError = false;

                // Validation des responsables supérieurs
                const superieurs = document.querySelectorAll('input[name*="[superieur]"]:checked');
                if (superieurs.length > 1) {
                    e.preventDefault();
                    alert('Une seule personne peut être désignée comme responsable supérieur');
                    return false;
                }

                // Validation des responsables
                const responsableItems = document.querySelectorAll('.responsable-item');
                const erreurs = [];

                // responsableItems.forEach((item, index) => {
                //     const userSelect = item.querySelector('.user-select');
                //     const responsabiliteSelect = item.querySelector('select[name*="[responsabilite]"]');
                //     const searchInput = item.querySelector('.user-search');

                //     // Vérifier si un utilisateur est sélectionné
                //     if (!userSelect.value) {
                //         erreurs.push(`Responsable ${index + 1} : Veuillez sélectionner un utilisateur`);
                //         searchInput.style.border = '2px solid #ef4444'; // Rouge
                //         hasError = true;
                //     } else {
                //         searchInput.style.border = ''; // Réinitialiser
                //     }

                //     // Vérifier si une responsabilité est sélectionnée
                //     if (!responsabiliteSelect.value) {
                //         erreurs.push(`Responsable ${index + 1} : Veuillez sélectionner un type de responsabilité`);
                //         responsabiliteSelect.style.border = '2px solid #ef4444'; // Rouge
                //         hasError = true;
                //     } else {
                //         responsabiliteSelect.style.border = ''; // Réinitialiser
                //     }
                // });

                responsableItems.forEach((item, index) => {
                    const userSelect = item.querySelector('.user-select');
                    const responsabiliteSelect = item.querySelector('select[name*="[responsabilite]"]');
                    const superieurCheckbox = item.querySelector('input[name*="[superieur]"]');

                    console.log(`Responsable ${index + 1}:`);
                    console.log('  User ID:', userSelect.value);
                    console.log('  User name:', userSelect.name);
                    console.log('  Responsabilité:', responsabiliteSelect.value);
                    console.log('  Supérieur:', superieurCheckbox.checked);
                });

                if (hasError) {
                    e.preventDefault();
                    alert('Erreurs détectées :\n\n' + erreurs.join('\n'));

                    // Faire défiler vers le premier élément en erreur
                    const firstErrorElement = document.querySelector('.responsable-item input[style*="border: 2px solid"], .responsable-item select[style*="border: 2px solid"]');
                    if (firstErrorElement) {
                        firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstErrorElement.focus();
                    }

                    return false;
                }
            });




            // Fermer les dropdowns en cliquant ailleurs
            document.addEventListener('click', function (e) {
                if (!e.target.closest('.user-search') &&
                    !e.target.closest('.user-dropdown') &&
                    !e.target.closest('.clear-user-btn')) {
                    document.querySelectorAll('.user-dropdown').forEach(dropdown => {
                        dropdown.classList.add('hidden');
                    });
                }
            });
        });
    </script>

@endsection
