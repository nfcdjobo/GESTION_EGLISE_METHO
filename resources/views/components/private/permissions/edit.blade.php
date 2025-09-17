@extends('layouts.private.main')
@section('title', 'Modifier une Permission')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier la Permission</h1>
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
                        <a href="{{ route('private.permissions.show', $permission) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            {{ $permission->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Modifier</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    @if($permission->is_system && !auth()->user()->isSuperAdmin())
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Permission système</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Cette permission est protégée et ne peut être modifiée que par le super administrateur.</p>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('private.permissions.show', $permission) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-xl hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Retour aux détails
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
    @can('permissions.update')
        <form action="{{ route('private.permissions.update', $permission) }}" method="POST" id="permissionForm" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Informations générales -->
                <div class="lg:col-span-2">
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
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
                                    <input type="text" id="name" name="name" value="{{ old('name', $permission->name) }}" required maxlength="100" placeholder="Ex: Consulter les membres"
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
                                    <input type="text" id="slug" name="slug" value="{{ old('slug', $permission->slug) }}" required maxlength="100" placeholder="users.read"
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
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description', $permission->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="resource" class="block text-sm font-medium text-slate-700 mb-2">Ressource</label>
                                    <input type="text" id="resource" name="resource" value="{{ old('resource', $permission->resource) }}" maxlength="100" placeholder="Ex: users, posts, orders"
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
                                            <option value="{{ $action }}" {{ old('action', $permission->action) == $action ? 'selected' : '' }}>
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
                                            <option value="{{ $category }}" {{ old('category', $permission->category) == $category ? 'selected' : '' }}>
                                                {{ ucfirst($category) }}
                                            </option>
                                        @endforeach
                                        <option value="autre" {{ old('category') == 'autre' || (!$categories->contains($permission->category) && $permission->category) ? 'selected' : '' }}>Autre (saisir ci-dessous)</option>
                                    </select>
                                    @error('category')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-slate-500">Catégorie pour grouper les permissions</p>
                                </div>

                                <div id="custom-category-div" class="{{ (!$categories->contains($permission->category) && $permission->category) || old('category') == 'autre' ? '' : 'hidden' }}">
                                    <label for="custom_category" class="block text-sm font-medium text-slate-700 mb-2">Nouvelle catégorie</label>
                                    <input type="text" id="custom_category" name="custom_category"
                                           value="{{ old('custom_category', (!$categories->contains($permission->category) && $permission->category) ? $permission->category : '') }}"
                                           maxlength="100" placeholder="Nom de la nouvelle catégorie"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <p class="mt-1 text-sm text-slate-500">Créer une nouvelle catégorie</p>
                                </div>

                                <div>
                                    <label for="priority" class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                                    <input type="number" id="priority" name="priority" value="{{ old('priority', $permission->priority) }}" min="0" max="255"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('priority') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('priority')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-slate-500">Ordre de priorité (0-255)</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $permission->is_active) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 @error('is_active') border-red-500 @enderror">
                                    <label for="is_active" class="ml-2 text-sm font-medium text-slate-700">
                                        Permission active
                                    </label>
                                    @error('is_active')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                @if(!$permission->is_system)
                                    <div class="flex items-center">
                                        <input type="checkbox" id="is_system" name="is_system" value="1" {{ old('is_system', $permission->is_system) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 @error('is_system') border-red-500 @enderror">
                                        <label for="is_system" class="ml-2 text-sm font-medium text-slate-700">
                                            Permission système
                                        </label>
                                        @error('is_system')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-1 text-sm text-slate-500">Les permissions système ne peuvent être modifiées que par le super admin</p>
                                    </div>
                                @else
                                    <div class="flex items-center p-3 bg-yellow-50 rounded-xl">
                                        <i class="fas fa-lock text-yellow-500 mr-2"></i>
                                        <span class="text-sm text-yellow-700 font-medium">Permission système protégée</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Conditions JSON (pour les membres avancés) -->
                            @if($permission->conditions || auth()->user()->isSuperAdmin())
                                <div>
                                    <label for="conditions" class="block text-sm font-medium text-slate-700 mb-2">
                                        Conditions (JSON)
                                        <span class="text-amber-500 ml-1" title="Fonctionnalité avancée">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                    </label>
                                    <textarea id="conditions" name="conditions" rows="4" placeholder='{"condition": "value"}'
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none font-mono text-sm @error('conditions') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('conditions', $permission->conditions ? json_encode($permission->conditions, JSON_PRETTY_PRINT) : '') }}</textarea>
                                    @error('conditions')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-slate-500">Conditions supplémentaires en format JSON (optionnel)</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Aperçu et informations -->
                <div class="space-y-6">
                    <!-- Aperçu -->
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-eye text-purple-600 mr-2"></i>
                                Aperçu
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Nom:</span>
                                <span id="preview-name" class="text-sm text-slate-900 font-semibold">{{ $permission->name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Slug:</span>
                                <code id="preview-slug" class="px-2 py-1 text-xs bg-slate-100 text-slate-800 rounded">{{ $permission->slug }}</code>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Ressource:</span>
                                <span id="preview-resource" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ $permission->resource ?: '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Action:</span>
                                <span id="preview-action" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ ucfirst($permission->action) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Catégorie:</span>
                                <span id="preview-category" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">{{ ucfirst($permission->category) ?: '-' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Type:</span>
                                <span id="preview-type" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $permission->is_system ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $permission->is_system ? 'Système' : 'Personnalisée' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Statut:</span>
                                <span id="preview-status" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $permission->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $permission->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques actuelles -->
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-chart-pie text-cyan-600 mr-2"></i>
                                Utilisation Actuelle
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Rôles:</span>
                                <span class="text-lg font-bold text-slate-900">{{ $permission->roles->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Membress directs:</span>
                                <span class="text-lg font-bold text-slate-900">{{ $permission->users->count() }}</span>
                            </div>
                            <div class="pt-3 border-t border-slate-200">
                                <div class="text-sm text-slate-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Dernière modification : {{ $permission->updated_at->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historique -->
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-history text-amber-600 mr-2"></i>
                                Historique
                            </h2>
                        </div>
                        <div class="p-6 space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Créée le:</span>
                                <span class="text-slate-900">{{ $permission->created_at->format('d/m/Y') }}</span>
                            </div>
                            @if($permission->createur)
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-700">Créée par:</span>
                                    <span class="text-slate-900">{{ $permission->createur->nom_complet }}</span>
                                </div>
                            @endif
                            @if($permission->last_used_at)
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-700">Dernière utilisation:</span>
                                    <span class="text-slate-900">{{ $permission->last_used_at->diffForHumans() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Mettre à jour
                        </button>
                        <a href="{{ route('private.permissions.show', $permission) }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                    </div>
                </div>
            </div>
        </form>
        @endcan
    @endif
</div>

@push('scripts')
<script>
// Génération automatique du slug
document.getElementById('resource').addEventListener('input', updateSlug);
document.getElementById('action').addEventListener('change', updateSlug);

function updateSlug() {
    // Ne pas régénérer automatiquement le slug lors de la modification
    // L'membres peut le faire manuellement si nécessaire
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
    const isSystem = document.getElementById('is_system') ? document.getElementById('is_system').checked : {{ $permission->is_system ? 'true' : 'false' }};
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
@if(!$permission->is_system)
document.getElementById('is_system').addEventListener('change', updatePreview);
@endif
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

    // Validation JSON pour les conditions
    const conditionsField = document.getElementById('conditions');
    if (conditionsField && conditionsField.value.trim()) {
        try {
            JSON.parse(conditionsField.value);
        } catch (e) {
            alert('Le format JSON des conditions est invalide.');
            return false;
        }
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

    // Vérifier si une catégorie personnalisée est nécessaire au chargement
    const categorySelect = document.getElementById('category');
    if (categorySelect.value === 'autre') {
        document.getElementById('custom-category-div').classList.remove('hidden');
    }
});
</script>
@endpush
@endsection
