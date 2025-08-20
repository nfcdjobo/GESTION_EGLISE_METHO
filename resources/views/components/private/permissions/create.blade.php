@extends('layouts.private.main')
@section('title', 'Créer une Permission')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer une Nouvelle Permission</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.permissions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-key mr-2"></i>
                        Permissions
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

    <form action="{{ route('private.permissions.store') }}" method="POST" id="permissionForm" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations générales -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
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
                                    Nom de la permission <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="Ex: Consulter les utilisateurs"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Nom d'affichage de la permission (100 caractères max)</p>
                            </div>

                            <div>
                                <label for="slug" class="block text-sm font-medium text-slate-700 mb-2">
                                    Slug <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" required maxlength="100" placeholder="users.read"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('slug') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('slug')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Identifiant unique (lettres, chiffres, points, tirets)</p>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3" placeholder="Description de la permission et de son utilisation"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="resource" class="block text-sm font-medium text-slate-700 mb-2">Ressource</label>
                                <input type="text" id="resource" name="resource" value="{{ old('resource') }}" maxlength="100" placeholder="Ex: users, posts, orders"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('resource') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('resource')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Entité concernée par cette permission</p>
                            </div>

                            <div>
                                <label for="action" class="block text-sm font-medium text-slate-700 mb-2">
                                    Action <span class="text-red-500">*</span>
                                </label>
                                <select id="action" name="action" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('action') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner une action</option>
                                    @foreach($actions as $action)
                                        <option value="{{ $action }}" {{ old('action') == $action ? 'selected' : '' }}>
                                            {{ ucfirst($action) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('action')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Type d'action autorisée</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="category" class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                                <select id="category" name="category"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('category') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                            {{ ucfirst($category) }}
                                        </option>
                                    @endforeach
                                    <option value="autre">Autre (saisir ci-dessous)</option>
                                </select>
                                @error('category')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Catégorie pour grouper les permissions</p>
                            </div>

                            <div id="custom-category-div" class="hidden">
                                <label for="custom_category" class="block text-sm font-medium text-slate-700 mb-2">Nouvelle catégorie</label>
                                <input type="text" id="custom_category" name="custom_category" value="{{ old('custom_category') }}" maxlength="100" placeholder="Nom de la nouvelle catégorie"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <p class="mt-1 text-sm text-slate-500">Créer une nouvelle catégorie</p>
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                                <input type="number" id="priority" name="priority" value="{{ old('priority', 0) }}" min="0" max="255"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('priority') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('priority')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Ordre de priorité (0-255)</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 @error('is_active') border-red-500 @enderror">
                                <label for="is_active" class="ml-2 text-sm font-medium text-slate-700">
                                    Permission active
                                </label>
                                @error('is_active')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="is_system" name="is_system" value="1" {{ old('is_system') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 @error('is_system') border-red-500 @enderror">
                                <label for="is_system" class="ml-2 text-sm font-medium text-slate-700">
                                    Permission système
                                </label>
                                @error('is_system')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Les permissions système ne peuvent être modifiées que par le super admin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aperçu et aide -->
            <div class="space-y-6">
                <!-- Aperçu -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
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
                            <span class="text-sm font-medium text-slate-700">Ressource:</span>
                            <span id="preview-resource" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Action:</span>
                            <span id="preview-action" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Catégorie:</span>
                            <span id="preview-category" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Type:</span>
                            <span id="preview-type" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Personnalisée</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Statut:</span>
                            <span id="preview-status" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                        </div>
                    </div>
                </div>

                <!-- Guide des Actions -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-cogs text-green-600 mr-2"></i>
                            Guide des Actions
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">create</span>
                            <span class="text-sm text-slate-700">Créer</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">read</span>
                            <span class="text-sm text-slate-700">Consulter</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">update</span>
                            <span class="text-sm text-slate-700">Modifier</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">delete</span>
                            <span class="text-sm text-slate-700">Supprimer</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">manage</span>
                            <span class="text-sm text-slate-700">Gestion complète</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">export</span>
                            <span class="text-sm text-slate-700">Exporter</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">validate</span>
                            <span class="text-sm text-slate-700">Valider</span>
                        </div>
                    </div>
                </div>

                <!-- Conseils -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-lightbulb text-amber-600 mr-2"></i>
                            Conseils
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm text-slate-600">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            <p>Utilisez un nom descriptif pour identifier facilement la permission.</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            <p>Le slug doit être unique et suivre la convention ressource.action.</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            <p>Groupez les permissions similaires dans la même catégorie.</p>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-warning text-amber-500 mt-0.5"></i>
                            <p>Les permissions système ne peuvent être modifiées que par le super admin.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer la Permission
                    </button>
                    <a href="{{ route('private.permissions.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
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
document.getElementById('resource').addEventListener('input', updateSlug);
document.getElementById('action').addEventListener('change', updateSlug);

function updateSlug() {
    const resource = document.getElementById('resource').value.toLowerCase().trim();
    const action = document.getElementById('action').value.toLowerCase().trim();

    if (resource && action) {
        const slug = `${resource}.${action}`;
        document.getElementById('slug').value = slug;
    }
    updatePreview();
}

// Gestion de la catégorie personnalisée
document.getElementById('category').addEventListener('change', function() {
    const customDiv = document.getElementById('custom-category-div');
    if (this.value === 'autre') {
        customDiv.classList.remove('hidden');
    } else {
        customDiv.classList.add('hidden');
    }
    updatePreview();
});

// Mise à jour de l'aperçu
function updatePreview() {
    const name = document.getElementById('name').value || '-';
    const slug = document.getElementById('slug').value || '-';
    const resource = document.getElementById('resource').value || '-';
    const action = document.getElementById('action').value || '-';
    const category = document.getElementById('category').value === 'autre'
        ? document.getElementById('custom_category').value || '-'
        : document.getElementById('category').value || '-';
    const isSystem = document.getElementById('is_system').checked;
    const isActive = document.getElementById('is_active').checked;

    document.getElementById('preview-name').textContent = name;
    document.getElementById('preview-slug').textContent = slug;
    document.getElementById('preview-resource').textContent = resource;
    document.getElementById('preview-action').textContent = action.charAt(0).toUpperCase() + action.slice(1);
    document.getElementById('preview-category').textContent = category.charAt(0).toUpperCase() + category.slice(1);

    // Type
    const typeBadge = document.getElementById('preview-type');
    if (isSystem) {
        typeBadge.textContent = 'Système';
        typeBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
    } else {
        typeBadge.textContent = 'Personnalisée';
        typeBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
    }

    // Statut
    const statusBadge = document.getElementById('preview-status');
    if (isActive) {
        statusBadge.textContent = 'Active';
        statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
    } else {
        statusBadge.textContent = 'Inactive';
        statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
    }

    // Couleur de l'action
    const actionBadge = document.getElementById('preview-action');
    switch (action) {
        case 'create':
            actionBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
            break;
        case 'read':
            actionBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
            break;
        case 'update':
            actionBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
            break;
        case 'delete':
            actionBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
            break;
        case 'manage':
            actionBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800';
            break;
        default:
            actionBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
    }
}

// Événements pour la mise à jour de l'aperçu
document.getElementById('name').addEventListener('input', updatePreview);
document.getElementById('slug').addEventListener('input', updatePreview);
document.getElementById('resource').addEventListener('input', updatePreview);
document.getElementById('action').addEventListener('change', updatePreview);
document.getElementById('custom_category').addEventListener('input', updatePreview);
document.getElementById('is_system').addEventListener('change', updatePreview);
document.getElementById('is_active').addEventListener('change', updatePreview);

// Validation du formulaire
document.getElementById('permissionForm').addEventListener('submit', function(e) {
    const name = document.getElementById('name').value.trim();
    const slug = document.getElementById('slug').value.trim();
    const action = document.getElementById('action').value;

    if (!name || !slug || !action) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }

    if (!/^[a-z0-9.-]+$/.test(slug)) {
        e.preventDefault();
        alert('Le slug ne peut contenir que des lettres minuscules, des chiffres, des points et des tirets.');
        return false;
    }

    // Gestion de la catégorie personnalisée
    const categorySelect = document.getElementById('category');
    const customCategory = document.getElementById('custom_category');

    if (categorySelect.value === 'autre' && customCategory.value.trim()) {
        // Remplacer la valeur du select par la catégorie personnalisée
        categorySelect.value = customCategory.value.trim().toLowerCase();
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
@endpush
@endsection
