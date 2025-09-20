@extends('layouts.private.main')
@section('title', 'Utilisateurs disponibles - ' . $classe->nom)

@section('content')
    <div class="space-y-8">
        <!-- En-tête de page -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('private.classes.show', $classe) }}"
                   class="inline-flex items-center text-slate-600 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la classe
                </a>
            </div>
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Utilisateurs disponibles
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Ajouter de nouveaux membres à "{{ $classe->nom }}"
                    </p>
                </div>

                <!-- Informations de la classe -->
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($classe->image_classe)
                                <img src="{{ asset('storage/' . $classe->image_classe) }}" alt="{{ $classe->nom }}"
                                    class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-chalkboard-teacher text-xl text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-semibold text-blue-900">{{ $classe->nom }}</h3>
                            <div class="flex items-center space-x-3 text-sm text-blue-700">
                                @if($classe->tranche_age)
                                    <span>{{ $classe->tranche_age }}</span>
                                @endif
                                <span>{{ $classe->nombre_inscrits }} membre(s)</span>
                                @if($classe->age_minimum || $classe->age_maximum)
                                    <span>
                                        @if($classe->age_minimum && $classe->age_maximum)
                                            {{ $classe->age_minimum }}-{{ $classe->age_maximum }} ans
                                        @elseif($classe->age_minimum)
                                            {{ $classe->age_minimum }}+ ans
                                        @else
                                            Jusqu'à {{ $classe->age_maximum }} ans
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et recherche
                </h2>
            </div>

            <div class="p-6">
                <form method="GET" action="{{ route('private.classes.getUtilisateursDisponibles', $classe) }}"
                    class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">

                    <!-- Recherche -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nom, prénom ou email..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>

                    <!-- Compatibilité d'âge -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Compatibilité</label>
                        <select name="age_compatible"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les utilisateurs</option>
                            <option value="1" {{ request('age_compatible') == '1' ? 'selected' : '' }}>Âge compatible uniquement</option>
                        </select>
                    </div>

                    <!-- Bouton de recherche -->
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i> Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Formulaire d'ajout de membres -->
        @if($utilisateurs->count() > 0)
            <form action="{{ route('private.classes.ajouter-membres', $classe) }}" method="POST" id="addMembersForm">
                @csrf

                <!-- Actions groupées -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                    <div class="p-6 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-users text-green-600 mr-2"></i>
                                Utilisateurs disponibles ({{ $utilisateurs->count() }})
                            </h2>

                            <div class="flex items-center space-x-3">
                                <label class="flex items-center text-sm">
                                    <input type="checkbox" name="force_age_check" value="1"
                                        class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mr-2">
                                    <span class="text-slate-600">Forcer l'âge incompatible</span>
                                </label>

                                <button type="button" onclick="toggleSelectAll()"
                                    class="inline-flex items-center px-3 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors text-sm">
                                    <i class="fas fa-check-square mr-2"></i> Tout sélectionner
                                </button>

                                <button type="submit" id="addSelectedBtn" disabled
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors disabled:bg-slate-400 disabled:cursor-not-allowed">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    Ajouter les sélectionnés (<span id="selectedCount">0</span>)
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des utilisateurs -->
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            @foreach($utilisateurs as $utilisateur)
                                <div class="user-card border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition-colors {{ !$utilisateur->age_compatible ? 'border-amber-300 bg-amber-50' : '' }}">
                                    <label class="flex items-start space-x-4 cursor-pointer">
                                        <input type="checkbox" name="user_ids[]" value="{{ $utilisateur->id }}"
                                            class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 mt-1 user-checkbox"
                                            onchange="updateSelectedCount()">

                                        <div class="flex items-start space-x-3 flex-1">
                                            <!-- Avatar -->
                                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0">
                                                {{ substr($utilisateur->prenom, 0, 1) }}{{ substr($utilisateur->nom, 0, 1) }}
                                            </div>

                                            <!-- Informations utilisateur -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <h3 class="font-semibold text-slate-900">
                                                            {{ $utilisateur->prenom }} {{ $utilisateur->nom }}
                                                        </h3>
                                                        <p class="text-sm text-slate-600">{{ $utilisateur->email }}</p>

                                                        @if($utilisateur->telephone_1)
                                                            <p class="text-sm text-slate-500">
                                                                <i class="fas fa-phone mr-1"></i> {{ $utilisateur->telephone_1 }}
                                                            </p>
                                                        @endif

                                                        @if($utilisateur->age)
                                                            <p class="text-sm text-slate-500">
                                                                <i class="fas fa-birthday-cake mr-1"></i> {{ $utilisateur->age }} ans
                                                            </p>
                                                        @endif
                                                    </div>

                                                    <!-- Badges de statut -->
                                                    <div class="flex flex-col items-end space-y-1 ml-3">
                                                        @if($utilisateur->age_compatible)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <i class="fas fa-check mr-1"></i> Compatible
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                                <i class="fas fa-exclamation-triangle mr-1"></i> Âge incompatible
                                                            </span>
                                                        @endif

                                                        @if($utilisateur->age && ($classe->age_minimum || $classe->age_maximum))
                                                            <span class="text-xs text-slate-500">
                                                                Requis:
                                                                @if($classe->age_minimum && $classe->age_maximum)
                                                                    {{ $classe->age_minimum }}-{{ $classe->age_maximum }} ans
                                                                @elseif($classe->age_minimum)
                                                                    {{ $classe->age_minimum }}+ ans
                                                                @else
                                                                    ≤ {{ $classe->age_maximum }} ans
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Actions rapides -->
                                                <div class="mt-3 flex items-center space-x-2">
                                                    <button type="button" onclick="addSingleUser('{{ $utilisateur->id }}')"
                                                        class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs">
                                                        <i class="fas fa-plus mr-1"></i> Ajouter seul
                                                    </button>

                                                    @if(!$utilisateur->age_compatible)
                                                        <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs">
                                                            <i class="fas fa-info-circle mr-1"></i> Nécessite validation
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Message si aucun utilisateur -->
                        @if($utilisateurs->count() == 0)
                            <div class="text-center py-12">
                                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-3xl text-slate-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun utilisateur disponible</h3>
                                <p class="text-slate-500 mb-6">
                                    @if(request()->hasAny(['search', 'age_compatible']))
                                        Aucun utilisateur ne correspond à vos critères de recherche.
                                    @else
                                        Tous les utilisateurs sont déjà inscrits dans une classe.
                                    @endif
                                </p>
                                <a href="{{ route('private.classes.show', $classe) }}"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i> Retour à la classe
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        @endif

        <!-- Pagination -->
        @if($utilisateurs->hasPages())
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-slate-700">
                    Affichage de <span class="font-medium">{{ $utilisateurs->firstItem() }}</span> à
                    <span class="font-medium">{{ $utilisateurs->lastItem() }}</span>
                    sur <span class="font-medium">{{ $utilisateurs->total() }}</span> résultats
                </div>
                <div>
                    {{ $utilisateurs->appends(request()->query())->links() }}
                </div>
            </div>
        @endif

        <!-- Informations d'aide -->
        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-200">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-info-circle text-white text-xl"></i>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-blue-900 mb-2">Informations importantes</h3>
                    <div class="text-blue-800 space-y-2 text-sm">
                        <p>• Les utilisateurs affichés ne sont inscrits dans aucune autre classe.</p>
                        <p>• La compatibilité d'âge est vérifiée automatiquement selon les critères de la classe.</p>
                        <p>• Vous pouvez forcer l'ajout d'utilisateurs avec un âge incompatible en cochant l'option correspondante.</p>
                        <p>• La capacité de cette classe est illimitée - vous pouvez ajouter autant de membres que nécessaire.</p>
                        @if($classe->age_minimum || $classe->age_maximum)
                            <p>• <strong>Critères d'âge pour cette classe :</strong>
                                @if($classe->age_minimum && $classe->age_maximum)
                                    entre {{ $classe->age_minimum }} et {{ $classe->age_maximum }} ans
                                @elseif($classe->age_minimum)
                                    minimum {{ $classe->age_minimum }} ans
                                @else
                                    maximum {{ $classe->age_maximum }} ans
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script>
        let isAllSelected = false;

        // Basculer la sélection de tous les utilisateurs
        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            isAllSelected = !isAllSelected;

            checkboxes.forEach(checkbox => {
                checkbox.checked = isAllSelected;
            });

            updateSelectedCount();
            updateToggleButton();
        }

        // Mettre à jour le bouton de basculement
        function updateToggleButton() {
            const button = document.querySelector('[onclick="toggleSelectAll()"]');
            const icon = button.querySelector('i');
            const text = button.querySelector('span') || button.childNodes[button.childNodes.length - 1];

            if (isAllSelected) {
                icon.className = 'fas fa-square mr-2';
                if (text.textContent) text.textContent = ' Tout désélectionner';
            } else {
                icon.className = 'fas fa-check-square mr-2';
                if (text.textContent) text.textContent = ' Tout sélectionner';
            }
        }

        // Mettre à jour le compteur de sélectionnés
        function updateSelectedCount() {
            const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');
            const count = selectedCheckboxes.length;

            document.getElementById('selectedCount').textContent = count;

            const addButton = document.getElementById('addSelectedBtn');
            addButton.disabled = count === 0;

            if (count === 0) {
                addButton.classList.add('disabled:bg-slate-400', 'disabled:cursor-not-allowed');
                addButton.classList.remove('bg-green-600', 'hover:bg-green-700');
            } else {
                addButton.classList.remove('disabled:bg-slate-400', 'disabled:cursor-not-allowed');
                addButton.classList.add('bg-green-600', 'hover:bg-green-700');
            }

            // Mettre à jour l'état du bouton "Tout sélectionner"
            const allCheckboxes = document.querySelectorAll('.user-checkbox');
            const allSelected = allCheckboxes.length > 0 && selectedCheckboxes.length === allCheckboxes.length;

            if (allSelected !== isAllSelected) {
                isAllSelected = allSelected;
                updateToggleButton();
            }
        }

        // Ajouter un utilisateur seul
        function addSingleUser(userId) {
            if (confirm('Voulez-vous ajouter cet utilisateur à la classe ?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("private.classes.ajouter-membres", $classe) }}';
                form.style.display = 'none';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const userIdInput = document.createElement('input');
                userIdInput.type = 'hidden';
                userIdInput.name = 'user_ids[]';
                userIdInput.value = userId;

                form.appendChild(csrfToken);
                form.appendChild(userIdInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Validation du formulaire
        document.getElementById('addMembersForm').addEventListener('submit', function(e) {
            const selectedCheckboxes = document.querySelectorAll('.user-checkbox:checked');

            if (selectedCheckboxes.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins un utilisateur à ajouter.');
                return false;
            }

            // Vérifier s'il y a des utilisateurs avec âge incompatible
            const forceAgeCheck = document.querySelector('input[name="force_age_check"]').checked;
            let hasIncompatibleAge = false;

            selectedCheckboxes.forEach(checkbox => {
                const card = checkbox.closest('.user-card');
                if (card.classList.contains('border-amber-300') && !forceAgeCheck) {
                    hasIncompatibleAge = true;
                }
            });

            if (hasIncompatibleAge) {
                const confirmed = confirm(
                    'Certains utilisateurs sélectionnés ont un âge incompatible avec cette classe. ' +
                    'Voulez-vous continuer sans forcer l\'ajout ? ' +
                    '(Cochez "Forcer l\'âge incompatible" pour les inclure)'
                );

                if (!confirmed) {
                    e.preventDefault();
                    return false;
                }
            }

            // Afficher un indicateur de chargement
            const submitButton = document.getElementById('addSelectedBtn');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Ajout en cours...';
            submitButton.disabled = true;
        });

        // Initialiser le compteur au chargement
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectedCount();

            // Écouter les changements sur les checkboxes
            document.querySelectorAll('.user-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });
        });

        // Afficher les messages de statut
        @if(session('success'))
            showSuccessMessage('{{ session('success') }}');
        @endif

        @if(session('error'))
            showErrorMessage('{{ session('error') }}');
        @endif

        @if($errors->any())
            showErrorMessage('{{ $errors->first() }}');
        @endif

        // Fonctions d'affichage des messages
        function showSuccessMessage(message) {
            showMessage(message, 'success');
        }

        function showErrorMessage(message) {
            showMessage(message, 'error');
        }

        function showMessage(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-xl shadow-lg z-50 transform transition-all duration-300 translate-x-full`;
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${icon} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(alertDiv);

            // Animation d'entrée
            setTimeout(() => {
                alertDiv.classList.remove('translate-x-full');
                alertDiv.classList.add('translate-x-0');
            }, 100);

            // Animation de sortie et suppression
            setTimeout(() => {
                alertDiv.classList.remove('translate-x-0');
                alertDiv.classList.add('translate-x-full');
                setTimeout(() => alertDiv.remove(), 300);
            }, 4000);
        }
    </script>

@endsection
