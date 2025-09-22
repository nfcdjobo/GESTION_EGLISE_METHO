@extends('layouts.private.main')
@section('title', 'Modifier le Rôle: ' . $role->name)

@section('content')

    <div class="space-y-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Hiérarchie des Rôles</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.roles.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-users mr-2"></i>
                            Rôles
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <li><a href="{{ route('private.roles.show', $role) }}"
                            class="text-blue-600 hover:text-blue-800 transition-colors">{{ $role->name }}</a></li>
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-medium text-slate-500">Modifier</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        @if ($role->is_system_role && !auth()->user()->isSuperAdmin())
            <div class="mb-6">
                <div class="w-full">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                            <div>
                                <strong class="text-yellow-800">Attention:</strong>
                                <span class="text-yellow-700">Ce rôle système ne peut être modifié que par le super
                                    administrateur.
                                    Seules les informations de base peuvent être mises à jour.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('private.roles.update', $role) }}" method="POST" id="roleForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informations générales -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 mb-6">
                        <div class="border-b border-slate-200 p-6">
                            <h3 class="text-lg font-semibold text-slate-800">Informations Générales</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">
                                        Nom du rôle <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                                        id="name" name="name" value="{{ old('name', $role->name) }}" required
                                        maxlength="100" placeholder="Ex: Administrateur">
                                    @error('name')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-slate-500 text-sm mt-1">Nom d'affichage du rôle (100 caractères max)</p>
                                </div>

                                <div>
                                    <label for="slug" class="block text-sm font-medium text-slate-700 mb-2">
                                        Slug <span class="text-red-500">*</span>
                                        @if ($role->is_system_role)
                                            <i class="fas fa-lock text-yellow-500 ml-1"
                                                title="Protégé pour les rôles système"></i>
                                        @endif
                                    </label>
                                    <input type="text"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('slug') border-red-500 @enderror @if ($role->is_system_role) bg-slate-100 @endif"
                                        id="slug" name="slug" value="{{ old('slug', $role->slug) }}" required
                                        maxlength="100" placeholder="administrateur"
                                        @if ($role->is_system_role) readonly @endif>
                                    @error('slug')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-slate-500 text-sm mt-1">
                                        Identifiant unique (lettres, chiffres, tirets)
                                        @if ($role->is_system_role)
                                            <br><strong class="text-yellow-600">Non modifiable pour les rôles système</strong>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label for="description"
                                    class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                                <textarea
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror"
                                    id="description" name="description" rows="3" placeholder="Description du rôle et de ses responsabilités">{{ old('description', $role->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="level" class="block text-sm font-medium text-slate-700 mb-2">
                                        Niveau hiérarchique <span class="text-red-500">*</span>
                                        @if ($role->is_system_role && $role->level >= 80)
                                            <i class="fas fa-lock text-yellow-500 ml-1" title="Niveau protégé"></i>
                                        @endif
                                    </label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('level') border-red-500 @enderror @if ($role->is_system_role && $role->level >= 80 && !auth()->user()->isSuperAdmin()) bg-slate-100 @endif"
                                        id="level" name="level" value="{{ old('level', $role->level) }}" required
                                        min="0" max="100" @if ($role->is_system_role && $role->level >= 80 && !auth()->user()->isSuperAdmin()) readonly @endif>
                                    @error('level')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-slate-500 text-sm mt-1">
                                        0-9: Visiteur, 10-19: Membre, 20-39: Actif, 40-59: Responsable, 60-79: Direction, 80-99:
                                        Admin, 100: Super Admin
                                        @if ($role->is_system_role && $role->level >= 80)
                                            <br><strong class="text-yellow-600">Modification restreinte pour les rôles de haut
                                                niveau</strong>
                                        @endif
                                    </p>
                                </div>

                                <div>
                                    <div class="mt-8">
                                        <div class="flex items-center">
                                            <input type="checkbox"
                                                class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500"
                                                id="is_system_role" name="is_system_role" value="1"
                                                {{ old('is_system_role', $role->is_system_role) ? 'checked' : '' }} disabled>
                                            <label class="ml-2 text-sm font-medium text-slate-700" for="is_system_role">
                                                Rôle système
                                            </label>
                                        </div>
                                        <p class="text-slate-500 text-sm mt-1">
                                            <i class="fas fa-info-circle"></i> Cette propriété ne peut pas être modifiée après
                                            création
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Informations de modification -->
                            <div class="mt-6">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <strong class="text-slate-700">Créé le:</strong>
                                            <span class="text-slate-600">{{ $role->created_at->format('d/m/Y à H:i') }}</span>
                                        </div>
                                        <div>
                                            <strong class="text-slate-700">Dernière modification:</strong>
                                            <span class="text-slate-600">{{ $role->updated_at->format('d/m/Y à H:i') }}</span>
                                        </div>
                                    </div>
                                    @if ($role->users()->count() > 0)
                                        <div class="mt-3">
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                                <div class="flex items-center">
                                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                                    <div>
                                                        <strong class="text-yellow-800">Attention:</strong>
                                                        <span class="text-yellow-700">Ce rôle est attribué à
                                                            {{ $role->users()->count() }} membres(s).
                                                            Les modifications affecteront leurs permissions.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aperçu et statistiques -->
                <div class="lg:col-span-1">
                    <!-- Aperçu -->
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 mb-6">
                        <div class="border-b border-slate-200 p-6">
                            <h3 class="text-lg font-semibold text-slate-800">Aperçu</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <strong class="text-slate-700">Nom:</strong>
                                    <span id="preview-name" class="text-slate-600">{{ $role->name }}</span>
                                </div>
                                <div>
                                    <strong class="text-slate-700">Slug:</strong>
                                    <code id="preview-slug"
                                        class="bg-slate-100 px-2 py-1 rounded text-sm">{{ $role->slug }}</code>
                                </div>
                                <div>
                                    <strong class="text-slate-700">Niveau:</strong>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                        id="preview-level-badge">{{ $role->level }}</span>
                                    <span id="preview-level-text" class="text-slate-600 ml-2">
                                        @if ($role->level >= 100)
                                            Super Admin
                                        @elseif($role->level >= 80)
                                            Administration
                                        @elseif($role->level >= 60)
                                            Direction
                                        @elseif($role->level >= 40)
                                            Responsable
                                        @elseif($role->level >= 20)
                                            Membre Actif
                                        @elseif($role->level >= 10)
                                            Membre
                                        @else
                                            Visiteur
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <strong class="text-slate-700">Type:</strong>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $role->is_system_role ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}"
                                        id="preview-type">
                                        {{ $role->is_system_role ? 'Système' : 'Personnalisé' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 mb-6">
                        <div class="border-b border-slate-200 p-6">
                            <h3 class="text-lg font-semibold text-slate-800">Statistiques</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-2xl font-bold text-blue-700">{{ $role->users()->count() }}</h4>
                                            <p class="text-blue-600 text-sm">Membress</p>
                                        </div>
                                        <i class="fas fa-users text-2xl text-blue-500"></i>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-2xl font-bold text-indigo-700">{{ $role->permissions()->count() }}
                                            </h4>
                                            <p class="text-indigo-600 text-sm">Permissions</p>
                                        </div>
                                        <i class="fas fa-key text-2xl text-indigo-500"></i>
                                    </div>
                                </div>

                                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-2xl font-bold text-green-700">
                                                {{ $role->users()->wherePivot('actif', true)->count() }}
                                            </h4>
                                            <p class="text-green-600 text-sm">Actifs</p>
                                        </div>
                                        <i class="fas fa-user-check text-2xl text-green-500"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 space-y-2">
                                <a href="{{ route('private.roles.show', $role) }}"
                                    class="w-full flex items-center justify-center px-4 py-2 border border-blue-300 text-blue-700 rounded-lg hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-eye mr-2"></i> Voir les détails
                                </a>
                                @can('roles.manage')
                                    <a href="{{ route('private.roles.permissions', $role) }}"
                                        class="w-full flex items-center justify-center px-4 py-2 border border-indigo-300 text-indigo-700 rounded-lg hover:bg-indigo-50 transition-colors">
                                        <i class="fas fa-key mr-2"></i> Gérer les permissions
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- Guide des Niveaux -->
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200 mb-6">
                        <div class="border-b border-slate-200 p-6">
                            <h3 class="text-lg font-semibold text-slate-800">Guide des Niveaux</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 w-12 justify-center">100</span>
                                    <span class="ml-2 text-sm text-slate-600">Super Admin</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 w-12 justify-center">80-99</span>
                                    <span class="ml-2 text-sm text-slate-600">Administration</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 w-12 justify-center">60-79</span>
                                    <span class="ml-2 text-sm text-slate-600">Direction</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 w-12 justify-center">40-59</span>
                                    <span class="ml-2 text-sm text-slate-600">Responsables</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 w-12 justify-center">20-39</span>
                                    <span class="ml-2 text-sm text-slate-600" Actifs</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-800 w-12 justify-center">10-19</span>
                                    <span class="ml-2 text-sm text-slate-600"</span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 w-12 justify-center">0-9</span>
                                    <span class="ml-2 text-sm text-slate-600">Visiteurs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions actuelles (en lecture seule) -->
            @if ($rolePermissions && count($rolePermissions) > 0)
                <div class="mb-6">
                    <div class="bg-white rounded-xl shadow-lg border border-slate-200">
                        <div class="border-b border-slate-200 p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800">Permissions Actuelles
                                        ({{ count($rolePermissions) }})</h3>
                                    <p class="text-slate-600 text-sm mt-1">Utilisez le gestionnaire de permissions pour
                                        modifier les permissions de ce rôle.</p>
                                </div>
                                @can('roles.manage')
                                    <a href="{{ route('private.roles.permissions', $role) }}"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-edit mr-2"></i> Gérer les permissions
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="p-6">
                            @php
                                //  dd( $rolePermissions);
                                $permissionsByCategory = collect($rolePermissions)->groupBy('category');
                            @endphp

                            @foreach ($permissionsByCategory as $category => $categoryPermissions)
                                <div class="mb-6 last:mb-0">
                                    <div class="bg-slate-50 rounded-lg p-4 mb-4">
                                        <div class="flex items-center justify-between cursor-pointer"
                                            onclick="toggleCategoryVisibility('{{ $category }}')">
                                            <div class="flex items-center">
                                                <h5 class="text-lg font-medium text-slate-800">{{ ucfirst($category) }}</h5>
                                                <span
                                                    class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-slate-200 text-slate-700">{{ $categoryPermissions->count() }}</span>
                                            </div>
                                            <button type="button"
                                                class="text-slate-500 hover:text-slate-700 transition-colors">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="permissions-{{ $category }}">

                                        @foreach ($categoryPermissions as $permission)
                                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                                                <div class="flex items-start">
                                                    <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                                                    <div class="flex-1">

                                                        <h6 class="font-medium text-slate-800">{{ $permission['name'] }}</h6>
                                                        @if (!empty($permission['description']))
                                                            <p class="text-slate-600 text-sm mt-1">{{ $permission['description'] }}</p>
                                                        @endif
                                                        <code class="text-xs bg-slate-200 px-2 py-1 rounded mt-2 inline-block">{{ $permission['slug'] }}</code>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="mb-6">
                <div class="bg-white rounded-xl shadow-lg border border-slate-200">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row items-center justify-center space-y-3 sm:space-y-0 sm:space-x-4">
                            <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                            <a href="{{ route('private.roles.show', $role) }}"
                                class="w-full sm:w-auto px-6 py-3 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i> Voir le rôle
                            </a>
                            <a href="{{ route('private.roles.index') }}"
                                class="w-full sm:w-auto px-6 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                                <i class="fas fa-times mr-2"></i> Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <script>
        // Génération automatique du slug (seulement si pas un rôle système)
        @if (!$role->is_system_role)
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
        @endif

        // Mise à jour de l'aperçu
        function updatePreview() {
            const name = document.getElementById('name').value || '{{ $role->name }}';
            const slug = document.getElementById('slug').value || '{{ $role->slug }}';
            const level = parseInt(document.getElementById('level').value) || {{ $role->level }};

            document.getElementById('preview-name').textContent = name;
            document.getElementById('preview-slug').textContent = slug;

            // Niveau avec badge coloré
            const levelBadge = document.getElementById('preview-level-badge');
            const levelText = document.getElementById('preview-level-text');

            levelBadge.textContent = level;
            levelBadge.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ' +
                getLevelBadgeClass(level);
            levelText.textContent = getLevelText(level);
        }

        function getLevelBadgeClass(level) {
            if (level >= 100) return 'bg-red-100 text-red-800';
            if (level >= 80) return 'bg-yellow-100 text-yellow-800';
            if (level >= 60) return 'bg-blue-100 text-blue-800';
            if (level >= 40) return 'bg-indigo-100 text-indigo-800';
            if (level >= 20) return 'bg-green-100 text-green-800';
            if (level >= 10) return 'bg-slate-100 text-slate-800';
            return 'bg-gray-100 text-gray-800';
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

        // Basculer la visibilité des catégories de permissions
        function toggleCategoryVisibility(category) {
            const element = document.getElementById(`permissions-${category}`);
            const button = element.previousElementSibling.querySelector('button i');

            if (element.style.display === 'none') {
                element.style.display = 'grid';
                button.className = 'fas fa-chevron-down';
            } else {
                element.style.display = 'none';
                button.className = 'fas fa-chevron-right';
            }
        }

        // Événements pour la mise à jour de l'aperçu
        document.getElementById('slug').addEventListener('input', updatePreview);
        document.getElementById('level').addEventListener('input', updatePreview);

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

            // Confirmation pour les modifications importantes
            @if ($role->users()->count() > 0)
                if (!confirm('Ce rôle est attribué à {{ $role->users()->count() }} membres(s). Continuer ?')) {
                    e.preventDefault();
                    return false;
                }
            @endif
        });

        // Initialiser l'aperçu
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
        });
    </script>

@endsection
