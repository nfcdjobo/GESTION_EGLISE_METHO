@extends('layouts.private.main')
@section('title', 'Créer un Rôle')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer un Nouveau Rôle</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
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
                        <span class="text-sm font-medium text-slate-500">Créer</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('private.roles.store') }}" method="POST" id="roleForm" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations générales -->
            <div class="lg:col-span-2">
                <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations Générales
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nom du rôle <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="Ex: Administrateur"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Nom d'affichage du rôle (100 caractères max)</p>
                            </div>

                            <div>
                                <label for="slug" class="block text-sm font-medium text-slate-700 mb-2">
                                    Slug <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required maxlength="100" placeholder="administrateur"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('slug') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('slug')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Identifiant unique (lettres, chiffres, tirets)</p>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3" placeholder="Description du rôle et de ses responsabilités"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="level" class="block text-sm font-medium text-slate-700 mb-2">
                                    Niveau hiérarchique <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="level" name="level" value="{{ old('level', 10) }}" required min="0" max="100"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('level') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('level')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">0-9: Visiteur, 10-19: Membre, 20-39: Actif, 40-59: Responsable, 60-79: Direction, 80-99: Admin, 100: Super Admin</p>
                            </div>

                            <div class="flex items-end">
                                <div class="w-full">
                                    <div class="flex items-center h-12">
                                        <input type="checkbox" id="is_system_role" name="is_system_role" value="1" {{ old('is_system_role') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 @error('is_system_role') border-red-500 @enderror">
                                        <label for="is_system_role" class="ml-2 text-sm font-medium text-slate-700">
                                            Rôle système
                                        </label>
                                    </div>
                                    @error('is_system_role')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-slate-500">Les rôles système ne peuvent être modifiés que par le super admin</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aperçu et aide -->
            <div class="space-y-6">
                <!-- Aperçu -->
                <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-eye text-purple-600 mr-2"></i>
                            Aperçu
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Nom:</span>
                            <span id="preview-name" class="text-sm text-slate-900 font-semibold">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Slug:</span>
                            <code id="preview-slug" class="px-2 py-1 text-xs bg-slate-100 text-slate-800 rounded">-</code>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Niveau:</span>
                            <div class="flex items-center space-x-2">
                                <span id="preview-level-badge" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">-</span>
                                <span id="preview-level-text" class="text-sm text-slate-600">-</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Type:</span>
                            <span id="preview-type" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Personnalisé</span>
                        </div>
                    </div>
                </div>

                <!-- Guide des Niveaux -->
                <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-layer-group text-green-600 mr-2"></i>
                            Guide des Niveaux
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">100</span>
                            <span class="text-sm text-slate-700">Super Admin</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">80-99</span>
                            <span class="text-sm text-slate-700">Administration</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">60-79</span>
                            <span class="text-sm text-slate-700">Direction</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">40-59</span>
                            <span class="text-sm text-slate-700">Responsables</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">20-39</span>
                            <span class="text-sm text-slate-700">Membres Actifs</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">10-19</span>
                            <span class="text-sm text-slate-700">Membres</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">0-9</span>
                            <span class="text-sm text-slate-700">Visiteurs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-key text-amber-600 mr-2"></i>
                    Permissions
                </h2>
                <p class="text-slate-500 mt-1">Sélectionnez les permissions à attribuer à ce rôle</p>
            </div>
            <div class="p-6">
                @if($permissions->count() > 0)
                    <div class="mb-6">
                        <div class="flex flex-wrap gap-3">
                            <button type="button" onclick="selectAllPermissions()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-check-double mr-2"></i> Tout sélectionner
                            </button>
                            <button type="button" onclick="clearAllPermissions()" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                <i class="fas fa-times mr-2"></i> Tout désélectionner
                            </button>
                            <button type="button" onclick="toggleAllCategories()" class="inline-flex items-center px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-xl hover:bg-cyan-700 transition-colors">
                                <i class="fas fa-exchange-alt mr-2"></i> Basculer les catégories
                            </button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @foreach($permissions as $category => $categoryPermissions)
                            <div class="permission-category border border-slate-200 rounded-xl overflow-hidden">
                                <div class="bg-gradient-to-r from-slate-50 to-blue-50 p-4 border-b border-slate-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <input type="checkbox" id="category-{{ $category }}" data-category="{{ $category }}" onchange="toggleCategoryPermissions('{{ $category }}')"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 category-checkbox">
                                            <label for="category-{{ $category }}" class="text-lg font-semibold text-slate-800">
                                                {{ ucfirst($category) }}
                                            </label>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-200 text-slate-700">
                                                {{ $categoryPermissions->count() }}
                                            </span>
                                        </div>
                                        <button type="button" onclick="toggleCategoryVisibility('{{ $category }}')" class="p-2 text-slate-600 hover:text-slate-800 hover:bg-white/50 rounded-lg transition-colors">
                                            <i class="fas fa-chevron-down transition-transform duration-200" id="icon-{{ $category }}"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="category-permissions p-4" id="permissions-{{ $category }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                        @foreach($categoryPermissions as $permission)
                                            <div class="p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                                                <div class="flex items-start space-x-3">
                                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}" data-category="{{ $category }}"
                                                        {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 permission-checkbox mt-1">
                                                    <div class="flex-1">
                                                        <label for="permission_{{ $permission->id }}" class="block text-sm font-semibold text-slate-900 cursor-pointer">
                                                            {{ $permission->name }}
                                                        </label>
                                                        @if($permission->description)
                                                            <p class="text-xs text-slate-600 mt-1">{{ $permission->description }}</p>
                                                        @endif
                                                        <code class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded mt-2 inline-block">{{ $permission->slug }}</code>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('permissions')
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex">
                                <i class="fas fa-exclamation-circle text-red-400 mt-0.5 mr-3"></i>
                                <p class="text-sm text-red-700">{{ $message }}</p>
                            </div>
                        </div>
                    @enderror
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-key text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune permission disponible</h3>
                        <p class="text-slate-500">Créez d'abord des permissions avant d'attribuer des rôles.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer le Rôle
                    </button>
                    <a href="{{ route('private.roles.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Génération automatique du slug
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const slug = name.toLowerCase()
                     .replace(/[^a-z0-9\s-]/g, '')
                     .replace(/\s+/g, '-')
                     .replace(/-+/g, '-')
                     .trim('-');
    document.getElementById('slug').value = slug;
    updatePreview();
});

// Mise à jour de l'aperçu
function updatePreview() {
    const name = document.getElementById('name').value || '-';
    const slug = document.getElementById('slug').value || '-';
    const level = parseInt(document.getElementById('level').value) || 0;
    const isSystem = document.getElementById('is_system_role').checked;

    document.getElementById('preview-name').textContent = name;
    document.getElementById('preview-slug').textContent = slug;

    // Niveau avec badge coloré
    const levelBadge = document.getElementById('preview-level-badge');
    const levelText = document.getElementById('preview-level-text');

    levelBadge.textContent = level;
    levelBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' + getLevelBadgeClass(level);
    levelText.textContent = getLevelText(level);

    // Type
    const typeBadge = document.getElementById('preview-type');
    if (isSystem) {
        typeBadge.textContent = 'Système';
        typeBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
    } else {
        typeBadge.textContent = 'Personnalisé';
        typeBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
    }
}

function getLevelBadgeClass(level) {
    if (level >= 100) return 'bg-red-100 text-red-800';
    if (level >= 80) return 'bg-yellow-100 text-yellow-800';
    if (level >= 60) return 'bg-blue-100 text-blue-800';
    if (level >= 40) return 'bg-purple-100 text-purple-800';
    if (level >= 20) return 'bg-green-100 text-green-800';
    if (level >= 10) return 'bg-gray-100 text-gray-800';
    return 'bg-slate-100 text-slate-800';
}

function getLevelText(level) {
    if (level >= 100) return 'Super Admin';
    if (level >= 80) return 'Administration';
    if (level >= 60) return 'Direction';
    if (level >= 40) return 'Responsable';
    if (level >= 20) return 'Membre Actif';
    if (level >= 10) return 'Membre';
    return 'Visiteur';
}

// Événements pour la mise à jour de l'aperçu
document.getElementById('slug').addEventListener('input', updatePreview);
document.getElementById('level').addEventListener('input', updatePreview);
document.getElementById('is_system_role').addEventListener('change', updatePreview);

// Fonctions de gestion des permissions
function selectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.checked = true;
        cb.indeterminate = false;
    });
}

function clearAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.checked = false;
        cb.indeterminate = false;
    });
}

function toggleAllCategories() {
    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.checked = !cb.checked;
        const category = cb.dataset.category;
        toggleCategoryPermissions(category);
    });
}

function toggleCategoryPermissions(category) {
    const categoryCheckbox = document.querySelector(`[data-category="${category}"].category-checkbox`);
    const permissionCheckboxes = document.querySelectorAll(`[data-category="${category}"].permission-checkbox`);

    permissionCheckboxes.forEach(cb => {
        cb.checked = categoryCheckbox.checked;
    });

    categoryCheckbox.indeterminate = false;
}

function toggleCategoryVisibility(category) {
    const element = document.getElementById(`permissions-${category}`);
    const icon = document.getElementById(`icon-${category}`);

    if (element.style.display === 'none') {
        element.style.display = 'block';
        icon.style.transform = 'rotate(0deg)';
    } else {
        element.style.display = 'none';
        icon.style.transform = 'rotate(-90deg)';
    }
}

// Mise à jour de l'état des cases de catégorie
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const category = this.dataset.category;
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
        });
    });

    updatePreview();
});

// Validation du formulaire
document.getElementById('roleForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const slug = document.getElementById('slug').value.trim();
    const level = document.getElementById('level').value;

    if (!name || !slug || !level) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }

    if (!/^[a-z0-9-]+$/.test(slug)) {
        e.preventDefault();
        alert('Le slug ne peut contenir que des lettres minuscules, des chiffres et des tirets.');
        return false;
    }
});
</script>
@endpush
@endsection
