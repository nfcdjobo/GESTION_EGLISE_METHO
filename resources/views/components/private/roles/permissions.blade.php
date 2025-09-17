@extends('layouts.private.main')
@section('title', 'Gérer les Permissions: ' . $role->name)

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 mb-2">
            @if($role->is_system_role)
                <i class="fas fa-lock text-yellow-500 text-xl"></i>
            @endif
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gérer les Permissions: {{ $role->name }}</h1>
        </div>
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.roles.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-users mr-2"></i>
                        Rôles
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <a href="{{ route('private.roles.show', $role) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">{{ $role->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Permissions</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Informations du rôle -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Informations du Rôle
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="flex items-center space-x-3">
                    @if($role->is_system_role)
                        <i class="fas fa-lock text-yellow-500 text-lg"></i>
                    @endif
                    <div>
                        <div class="font-bold text-slate-900">{{ $role->name }}</div>
                        <code class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded">{{ $role->slug }}</code>
                    </div>
                </div>
                <div>
                    <span class="text-sm font-medium text-slate-700">Niveau:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                        @if($role->level >= 100) bg-red-100 text-red-800
                        @elseif($role->level >= 80) bg-yellow-100 text-yellow-800
                        @elseif($role->level >= 60) bg-blue-100 text-blue-800
                        @elseif($role->level >= 40) bg-purple-100 text-purple-800
                        @elseif($role->level >= 20) bg-green-100 text-green-800
                        @elseif($role->level >= 10) bg-gray-100 text-gray-800
                        @else bg-slate-100 text-slate-800
                        @endif">
                        {{ $role->level }}
                    </span>
                </div>
                <div>
                    <span class="text-sm font-medium text-slate-700">Membress:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">{{ $role->users()->count() }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-slate-700">Permissions actuelles:</span>
                    <span id="current-permissions-count" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800 ml-2">{{ $rolePermissions->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions et filtres -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-magic text-purple-600 mr-2"></i>
                    Actions Rapides
                </h2>
                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick="savePermissions()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                    <button type="button" onclick="resetPermissions()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-undo mr-2"></i> Réinitialiser
                    </button>
                    <a href="{{ route('private.roles.show', $role) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-eye mr-2"></i> Voir le rôle
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <!-- Actions de sélection -->
            <div class="flex flex-wrap gap-3 mb-6">
                <button type="button" onclick="selectAllPermissions()" class="inline-flex items-center px-3 py-2 bg-green-100 text-green-700 text-sm font-medium rounded-lg hover:bg-green-200 transition-colors">
                    <i class="fas fa-check-square mr-2"></i> Tout sélectionner
                </button>
                <button type="button" onclick="clearAllPermissions()" class="inline-flex items-center px-3 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors">
                    <i class="fas fa-square mr-2"></i> Tout désélectionner
                </button>
                <button type="button" onclick="toggleAllCategories()" class="inline-flex items-center px-3 py-2 bg-cyan-100 text-cyan-700 text-sm font-medium rounded-lg hover:bg-cyan-200 transition-colors">
                    <i class="fas fa-list mr-2"></i> Basculer les catégories
                </button>
                <div class="relative">
                    <button type="button" onclick="togglePresetMenu()" class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 text-sm font-medium rounded-lg hover:bg-blue-200 transition-colors">
                        <i class="fas fa-magic mr-2"></i> Presets
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div id="preset-menu" class="absolute top-full left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 opacity-0 invisible transform scale-95 transition-all duration-200 z-10">
                        <a href="#" onclick="applyPreset('basic')" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-t-lg transition-colors">Permissions de base</a>
                        <a href="#" onclick="applyPreset('moderate')" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Permissions modérées</a>
                        <a href="#" onclick="applyPreset('advanced')" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">Permissions avancées</a>
                        <div class="border-t border-slate-200"></div>
                        <a href="#" onclick="copyFromRole()" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded-b-lg transition-colors">Copier d'un autre rôle</a>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" id="search-permissions" placeholder="Rechercher une permission..." onkeyup="filterPermissions()" class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                    <select id="filter-category" onchange="filterPermissions()" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les catégories</option>
                        @foreach($permissions as $category => $categoryPermissions)
                            <option value="{{ $category }}">{{ ucfirst($category) }} ({{ $categoryPermissions->count() }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select id="filter-status" onchange="filterPermissions()" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="assigned">Assignées</option>
                        <option value="not-assigned">Non assignées</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Actions</label>
                    <button type="button" onclick="clearFilters()" class="w-full inline-flex items-center justify-center px-3 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Effacer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé des modifications -->
    <div id="changes-summary" class="hidden bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-chart-line text-amber-600 mr-2"></i>
                Modifications en cours
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-green-500 to-emerald-500 text-white rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold mb-2" id="added-count">0</div>
                    <div class="text-green-100">Permissions ajoutées</div>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-rose-500 text-white rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold mb-2" id="removed-count">0</div>
                    <div class="text-red-100">Permissions supprimées</div>
                </div>
                <div class="bg-gradient-to-br from-blue-500 to-cyan-500 text-white rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold mb-2" id="total-selected">{{ $rolePermissions->count() }}</div>
                    <div class="text-blue-100">Total sélectionné</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des permissions par catégorie -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-key text-indigo-600 mr-2"></i>
                Permissions Disponibles
            </h2>
            <p class="text-slate-500 mt-1">Sélectionnez les permissions à attribuer à ce rôle</p>
        </div>
        <div class="p-6">
            @if($permissions->count() > 0)
                <div class="space-y-6">
                    @foreach($permissions as $category => $categoryPermissions)
                        <div class="permission-category border border-slate-200 rounded-xl overflow-hidden" data-category="{{ $category }}">
                            <div class="bg-gradient-to-r from-slate-50 to-blue-50 p-4 border-b border-slate-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" data-category="{{ $category }}" onchange='toggleCategoryPermissions("{{ $category }}")' class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 category-checkbox">
                                        <h3 class="text-lg font-semibold text-slate-800">{{ ucfirst($category) }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-700">
                                            {{ $categoryPermissions->count() }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 category-selected-count" data-category="{{ $category }}">0</span>
                                    </div>
                                    <button type="button" onclick='toggleCategoryVisibility("{{ $category }}")' class="p-2 text-slate-600 hover:text-slate-800 hover:bg-white/50 rounded-lg transition-colors">
                                        <i class="fas fa-chevron-down transition-transform duration-200"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="category-permissions p-4" id="category-{{ $category }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($categoryPermissions as $permission)
                                        @php
                                            $isAssigned = $rolePermissions->contains('id', $permission->id);
                                            $rolePermission = $rolePermissions->firstWhere('id', $permission->id);
                                        @endphp
                                        <div class="permission-item" data-category="{{ $category }}" data-permission-name="{{ strtolower($permission->name) }}" data-permission-slug="{{ strtolower($permission->slug) }}" data-assigned="{{ $isAssigned ? 'true' : 'false' }}">
                                            <div class="permission-card h-full border-2 rounded-xl p-4 transition-all duration-300 hover:shadow-md {{ $isAssigned ? 'border-green-300 bg-green-50' : 'border-slate-200 bg-white hover:border-blue-300' }}">
                                                <div class="flex items-start space-x-3">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}" data-category="{{ $category }}" {{ $isAssigned ? 'checked' : '' }} onchange="updatePermissionState(this)" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 permission-checkbox mt-1">
                                                    <div class="flex-1">
                                                        <label for="permission_{{ $permission->id }}" class="block cursor-pointer">
                                                            <div class="permission-info">
                                                                <h4 class="permission-name font-semibold text-slate-900 mb-1">{{ $permission->name }}</h4>
                                                                @if($permission->description)
                                                                    <p class="permission-description text-sm text-slate-600 mb-2">{{ $permission->description }}</p>
                                                                @endif
                                                                <code class="permission-slug text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded">{{ $permission->slug }}</code>

                                                                @if($isAssigned && $rolePermission)
                                                                    <div class="mt-3 permission-details space-y-1">
                                                                        <div class="flex items-center text-sm text-green-600">
                                                                            <i class="fas fa-check-circle mr-2"></i>
                                                                            <span>Assigné
                                                                                @if($rolePermission->pivot->attribue_le)
                                                                                    le {{ \Carbon\Carbon::parse($rolePermission->pivot->attribue_le)->format('d/m/Y') }}
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                        @if($rolePermission->pivot->expire_le)
                                                                            <div class="flex items-center text-sm text-yellow-600">
                                                                                <i class="fas fa-clock mr-2"></i>
                                                                                <span>Expire le {{ \Carbon\Carbon::parse($rolePermission->pivot->expire_le)->format('d/m/Y') }}</span>
                                                                            </div>
                                                                        @endif
                                                                        @if($rolePermission->pivot->attribue_par)
                                                                            <div class="flex items-center text-sm text-slate-500">
                                                                                <i class="fas fa-user mr-2"></i>
                                                                                <span>Par: {{ \App\Models\User::find($rolePermission->pivot->attribue_par)?->nom_complet ?? 'N/A' }}</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-key text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune permission disponible</h3>
                    <p class="text-slate-500">Créez d'abord des permissions avant de les attribuer aux rôles.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions finales -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 p-6">
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button type="button" onclick="savePermissions()" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-save mr-2"></i> Enregistrer les Permissions
            </button>
            <button type="button" onclick="resetPermissions()" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-undo mr-2"></i> Réinitialiser
            </button>
            <a href="{{ route('private.roles.show', $role) }}" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-slate-600 to-gray-600 text-white font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                <i class="fas fa-eye mr-2"></i> Retour au rôle
            </a>
        </div>
    </div>
</div>

<!-- Modal de copie depuis un autre rôle -->
<div id="copyFromRoleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Copier les permissions d'un autre rôle</h3>
                <button type="button" onclick="closeCopyRoleModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label for="source_role" class="block text-sm font-medium text-slate-700 mb-2">Rôle source</label>
                <select id="source_role" name="source_role" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Sélectionnez un rôle...</option>
                </select>
            </div>
            <div class="flex items-start space-x-3">
                <input type="checkbox" id="replace_permissions" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mt-0.5">
                <div>
                    <label for="replace_permissions" class="text-sm font-medium text-slate-700">
                        Remplacer les permissions actuelles
                    </label>
                    <p class="text-xs text-slate-500 mt-1">
                        Si décochée, les permissions seront ajoutées aux permissions existantes
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeCopyRoleModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="executeRoleCopy()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Copier
            </button>
        </div>
    </div>
</div>


<script>
// Variables globales
let originalPermissions = [];
let currentPermissions = [];
let hasChanges = false;

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    originalPermissions = Array.from(document.querySelectorAll('.permission-checkbox:checked'))
                              .map(cb => cb.value);
    currentPermissions = [...originalPermissions];
    updateCategoryCounters();
    updateChangesDisplay();
});

// Modal functions
function togglePresetMenu() {
    const menu = document.getElementById('preset-menu');
    menu.classList.toggle('opacity-0');
    menu.classList.toggle('invisible');
    menu.classList.toggle('scale-95');
}

function closeCopyRoleModal() {
    document.getElementById('copyFromRoleModal').classList.add('hidden');
}

// Close menus when clicking outside
document.addEventListener('click', function(event) {
    const presetMenu = document.getElementById('preset-menu');
    const presetButton = event.target.closest('[onclick="togglePresetMenu()"]');

    if (!presetButton && !presetMenu.contains(event.target)) {
        presetMenu.classList.add('opacity-0', 'invisible', 'scale-95');
    }

    const modal = document.getElementById('copyFromRoleModal');
    if (event.target === modal) {
        closeCopyRoleModal();
    }
});

// Fonction de mise à jour d'état d'une permission
function updatePermissionState(checkbox) {
    const permissionId = checkbox.value;
    const isChecked = checkbox.checked;
    const card = checkbox.closest('.permission-card');
    const category = checkbox.dataset.category;

    if (isChecked) {
        card.classList.remove('border-slate-200', 'bg-white');
        card.classList.add('border-green-300', 'bg-green-50');
        if (!currentPermissions.includes(permissionId)) {
            currentPermissions.push(permissionId);
        }
    } else {
        card.classList.remove('border-green-300', 'bg-green-50');
        card.classList.add('border-slate-200', 'bg-white');
        currentPermissions = currentPermissions.filter(id => id !== permissionId);
    }

    updateCategoryCounters();
    updateCategoryCheckboxState(category);
    updateChangesDisplay();
    hasChanges = true;
}

// Mise à jour des compteurs par catégorie
function updateCategoryCounters() {
    document.querySelectorAll('.category-selected-count').forEach(counter => {
        const category = counter.dataset.category;
        const selectedCount = document.querySelectorAll(
            `.permission-checkbox[data-category="${category}"]:checked`
        ).length;
        counter.textContent = selectedCount;
    });
}

// Mise à jour de l'état de la checkbox de catégorie
function updateCategoryCheckboxState(category) {
    const categoryCheckbox = document.querySelector(`[data-category="${category}"].category-checkbox`);
    const categoryPermissions = document.querySelectorAll(`[data-category="${category}"].permission-checkbox`);
    const checkedCount = Array.from(categoryPermissions).filter(cb => cb.checked).length;

    if (checkedCount === 0) {
        categoryCheckbox.checked = false;
        categoryCheckbox.indeterminate = false;
    } else if (checkedCount === categoryPermissions.length) {
        categoryCheckbox.checked = true;
        categoryCheckbox.indeterminate = false;
    } else {
        categoryCheckbox.checked = false;
        categoryCheckbox.indeterminate = true;
    }
}

// Basculer les permissions d'une catégorie
function toggleCategoryPermissions(category) {
    const categoryCheckbox = document.querySelector(`[data-category="${category}"].category-checkbox`);
    const permissionCheckboxes = document.querySelectorAll(`[data-category="${category}"].permission-checkbox`);

    permissionCheckboxes.forEach(cb => {
        cb.checked = categoryCheckbox.checked;
        updatePermissionState(cb);
    });
}

// Basculer la visibilité d'une catégorie
function toggleCategoryVisibility(category) {
    const element = document.getElementById(`category-${category}`);
    const button = element.previousElementSibling.querySelector('button i');

    if (element.style.display === 'none') {
        element.style.display = 'block';
        button.style.transform = 'rotate(0deg)';
    } else {
        element.style.display = 'none';
        button.style.transform = 'rotate(-90deg)';
    }
}

// Sélectionner toutes les permissions
function selectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => {
        cb.checked = true;
        updatePermissionState(cb);
    });
    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.checked = true;
        cb.indeterminate = false;
    });
}

// Désélectionner toutes les permissions
function clearAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => {
        cb.checked = false;
        updatePermissionState(cb);
    });
    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.checked = false;
        cb.indeterminate = false;
    });
}

// Basculer toutes les catégories
function toggleAllCategories() {
    document.querySelectorAll('.category-permissions').forEach(element => {
        const button = element.previousElementSibling.querySelector('button i');
        if (element.style.display === 'none') {
            element.style.display = 'block';
            button.style.transform = 'rotate(0deg)';
        } else {
            element.style.display = 'none';
            button.style.transform = 'rotate(-90deg)';
        }
    });
}

// Filtrer les permissions
function filterPermissions() {
    const searchTerm = document.getElementById('search-permissions').value.toLowerCase();
    const categoryFilter = document.getElementById('filter-category').value;
    const statusFilter = document.getElementById('filter-status').value;

    document.querySelectorAll('.permission-item').forEach(item => {
        const name = item.dataset.permissionName;
        const slug = item.dataset.permissionSlug;
        const category = item.dataset.category;
        const isAssigned = item.dataset.assigned === 'true';

        let show = true;

        if (searchTerm && !name.includes(searchTerm) && !slug.includes(searchTerm)) {
            show = false;
        }

        if (categoryFilter && category !== categoryFilter) {
            show = false;
        }

        if (statusFilter === 'assigned' && !isAssigned) {
            show = false;
        } else if (statusFilter === 'not-assigned' && isAssigned) {
            show = false;
        }

        item.style.display = show ? 'block' : 'none';
    });
}

// Effacer les filtres
function clearFilters() {
    document.getElementById('search-permissions').value = '';
    document.getElementById('filter-category').value = '';
    document.getElementById('filter-status').value = '';
    filterPermissions();
}

// Appliquer un preset de permissions
function applyPreset(preset) {
    clearAllPermissions();

    const presets = {
        basic: ['users.read', 'profile.read', 'profile.update'],
        moderate: ['users.read', 'users.create', 'profile.read', 'profile.update', 'roles.read'],
        advanced: ['users.*', 'roles.*', 'permissions.*']
    };

    if (presets[preset]) {
        presets[preset].forEach(permissionSlug => {
            const checkbox = document.querySelector(`[value="${permissionSlug}"]`);
            if (checkbox) {
                checkbox.checked = true;
                updatePermissionState(checkbox);
            }
        });
    }

    // Close preset menu
    togglePresetMenu();
}

// Copier d'un autre rôle
function copyFromRole() {
    fetch("{{route('private.roles.index')}}", {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        const select = document.getElementById('source_role');
        select.innerHTML = '<option value="">Sélectionnez un rôle...</option>';

        if(data.success){
            const roles = data.data;
            roles.forEach(role => {
            if (role.id !== "{{ $role->id }}") {
                const option = document.createElement('option');
                option.value = role.id;
                option.textContent = `${role.name} (${role.permissions_count} permissions)`;
                select.appendChild(option);
            }
        });
        }


    });

    document.getElementById('copyFromRoleModal').classList.remove('hidden');
}

// Exécuter la copie de rôle
function executeRoleCopy() {
    const sourceRoleId = document.getElementById('source_role').value;
    const replacePermissions = document.getElementById('replace_permissions').checked;

    if (!sourceRoleId) {
        alert('Veuillez sélectionner un rôle source');
        return;
    }

    fetch( `{{route('private.roles.permissions', ':roleid')}}`.replace(':roleid', sourceRoleId), {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (replacePermissions) {
            clearAllPermissions();
        }

        if(data.success){
            const permissions = data.data;

            for(let key in permissions){
                permissions[key].forEach(permission => {
                    const checkbox = document.querySelector(`[value="${permission.id}"]`);
                    if (checkbox) {
                        checkbox.checked = true;
                        updatePermissionState(checkbox);
                    }
                });
            }

        }

        closeCopyRoleModal();
    });
}

// Mise à jour de l'affichage des changements
function updateChangesDisplay() {
    const added = currentPermissions.filter(id => !originalPermissions.includes(id));
    const removed = originalPermissions.filter(id => !currentPermissions.includes(id));
    const totalSelected = currentPermissions.length;

    document.getElementById('added-count').textContent = added.length;
    document.getElementById('removed-count').textContent = removed.length;
    document.getElementById('total-selected').textContent = totalSelected;
    document.getElementById('current-permissions-count').textContent = totalSelected;

    const changesSection = document.getElementById('changes-summary');
    if (added.length > 0 || removed.length > 0) {
        changesSection.classList.remove('hidden');
    } else {
        changesSection.classList.add('hidden');
    }
}

// Réinitialiser aux permissions originales
function resetPermissions() {
    if (confirm('Voulez-vous annuler tous les changements et revenir à l\'état initial ?')) {
        document.querySelectorAll('.permission-checkbox').forEach(cb => {
            cb.checked = false;
            updatePermissionState(cb);
        });

        originalPermissions.forEach(permissionId => {
            const checkbox = document.querySelector(`[value="${permissionId}"]`);
            if (checkbox) {
                checkbox.checked = true;
                updatePermissionState(checkbox);
            }
        });

        currentPermissions = [...originalPermissions];
        hasChanges = false;
        updateChangesDisplay();
        updateCategoryCounters();
    }
}



// Sauvegarder les permissions
function savePermissions() {
    if (!hasChanges) {
        alert('Aucune modification à enregistrer');
        return;
    }

    const formData = {
        permissions: currentPermissions
    };

    fetch('{{ route("private.roles.permissions.sync", $role) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            originalPermissions = [...currentPermissions];
            hasChanges = false;
            updateChangesDisplay();
            alert(data.message);
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la sauvegarde');
    });
}

// Avertissement de navigation
window.addEventListener('beforeunload', function(e) {
    if (hasChanges) {
        e.preventDefault();
        e.returnValue = 'Vous avez des modifications non sauvegardées. Voulez-vous vraiment quitter ?';
    }
});
</script>

@endsection
