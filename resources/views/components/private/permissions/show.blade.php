@extends('layouts.private.main')
@section('title', 'Détails de la Permission')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">{{ $permission->name }}</h1>
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
                        <span class="text-sm font-medium text-slate-500">{{ $permission->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @can(['permissions.update', 'permissions.toggle'])
                @can('permissions.create')
                    @if(!$permission->is_system || auth()->user()->isSuperAdmin())
                        <a href="{{ route('private.permissions.edit', $permission) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    @endif
                    @endcan
                        @can('permissions.create')
                    <button type="button" onclick="togglePermission()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-power-off mr-2"></i>
                        {{ $permission->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                    @endcan
                @endcan

                @can('permissions.clone')
                    <button type="button" onclick="clonePermission()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-medium rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-copy mr-2"></i> Cloner
                    </button>
                @endcan

                @can('permissions.delete')
                    @if(!$permission->is_system)
                        <button type="button" onclick="deletePermission()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i> Supprimer
                        </button>
                    @endif
                @endcan

                <a href="{{ route('private.permissions.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Détails de la permission -->
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
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nom</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <div class="flex items-center space-x-2">
                                    @if($permission->is_system)
                                        <i class="fas fa-lock text-yellow-500" title="Permission système"></i>
                                    @endif
                                    <span class="font-semibold text-slate-900">{{ $permission->name }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Slug</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <code class="text-sm text-slate-800">{{ $permission->slug }}</code>
                            </div>
                        </div>
                    </div>

                    @if($permission->description)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <div class="p-4 bg-slate-50 rounded-xl">
                                <p class="text-slate-700">{{ $permission->description }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Ressource</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                @if($permission->resource)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $permission->resource }}
                                    </span>
                                @else
                                    <span class="text-slate-400">Non définie</span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Action</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($permission->action)
                                        @case('create') bg-green-100 text-green-800 @break
                                        @case('read') bg-blue-100 text-blue-800 @break
                                        @case('update') bg-yellow-100 text-yellow-800 @break
                                        @case('delete') bg-red-100 text-red-800 @break
                                        @case('manage') bg-purple-100 text-purple-800 @break
                                        @default bg-gray-100 text-gray-800 @break
                                    @endswitch">
                                    {{ ucfirst($permission->action) }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                @if($permission->category)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                        {{ ucfirst($permission->category) }}
                                    </span>
                                @else
                                    <span class="text-slate-400">Non définie</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                @if($permission->is_system)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-lock mr-1"></i> Système
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-cog mr-1"></i> Personnalisée
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                @if($permission->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i> Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="font-medium text-slate-900">{{ $permission->priority }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rôles et utilisateurs -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-users text-purple-600 mr-2"></i>
                        Rôles et Utilisateurs
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Rôles -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-user-tag text-blue-600 mr-2"></i>
                                Rôles ({{ $permission->roles->count() }})
                            </h3>

                            @if($permission->roles->count() > 0)
                                <div class="space-y-3 max-h-64 overflow-y-auto">
                                    @foreach($permission->roles as $role)
                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                            <div class="flex items-center space-x-3">
                                                @if($role->is_system_role)
                                                    <i class="fas fa-lock text-yellow-500" title="Rôle système"></i>
                                                @endif
                                                <div>
                                                    <div class="font-medium text-slate-900">{{ $role->name }}</div>
                                                    <div class="text-sm text-slate-500">Niveau {{ $role->level }}</div>
                                                </div>
                                            </div>
                                            @can('roles.read')
                                                <a href="{{ route('private.roles.show', $role) }}" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-user-tag text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500">Aucun rôle assigné</p>
                                </div>
                            @endif
                        </div>

                        <!-- Utilisateurs directs -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-user text-green-600 mr-2"></i>
                                Utilisateurs directs ({{ $permission->users->count() }})
                            </h3>

                            @if($permission->users->count() > 0)
                                <div class="space-y-3 max-h-64 overflow-y-auto">
                                    @foreach($permission->users as $user)
                                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-xs font-medium">
                                                        {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-slate-900">{{ $user->nom_complet }}</div>
                                                    <div class="text-sm text-slate-500">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                            @can('users.read')
                                                <a href="{{ route('private.users.show', $user) }}" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-user text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="text-slate-500">Aucun utilisateur assigné directement</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-pie text-cyan-600 mr-2"></i>
                        Statistiques
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Rôles assignés:</span>
                        <span class="text-lg font-bold text-slate-900">{{ $stats['total_roles'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Utilisateurs directs:</span>
                        <span class="text-lg font-bold text-slate-900">{{ $stats['total_users_direct'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Via rôles:</span>
                        <span class="text-lg font-bold text-slate-900">{{ $stats['total_users_via_roles'] }}</span>
                    </div>
                    <div class="pt-4 border-t border-slate-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Total utilisateurs:</span>
                            <span class="text-xl font-bold text-blue-600">{{ $stats['total_users_direct'] + $stats['total_users_via_roles'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cog text-amber-600 mr-2"></i>
                        Informations Système
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Créée le</label>
                        <p class="text-sm text-slate-600">{{ $permission->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($permission->createur)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Créée par</label>
                            <p class="text-sm text-slate-600">{{ $permission->createur->nom_complet }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Modifiée le</label>
                        <p class="text-sm text-slate-600">{{ $permission->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($permission->modificateur)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Modifiée par</label>
                            <p class="text-sm text-slate-600">{{ $permission->modificateur->nom_complet }}</p>
                        </div>
                    @endif

                    @if($permission->last_used_at)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Dernière utilisation</label>
                            <p class="text-sm text-slate-600">{{ $permission->last_used_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    @else
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Dernière utilisation</label>
                            <p class="text-sm text-slate-400">Jamais utilisée</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Guard</label>
                        <code class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded">{{ $permission->guard_name }}</code>
                    </div>
                </div>
            </div>

            <!-- Conditions supplémentaires -->
            @if($permission->conditions)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-code text-purple-600 mr-2"></i>
                            Conditions
                        </h2>
                    </div>
                    <div class="p-6">
                        <pre class="text-xs bg-slate-100 p-4 rounded-xl overflow-x-auto"><code>{{ json_encode($permission->conditions, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3>
            </div>
            <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer cette permission ?</p>
            <p class="text-red-600 font-medium">Cette action est irréversible et supprimera toutes les associations.</p>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Supprimer
            </button>
        </div>
    </div>
</div>

<script>
// Modal functions
function showDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Activer/Désactiver la permission
function togglePermission() {
    if (!confirm('Êtes-vous sûr de vouloir changer le statut de cette permission ?')) {
        return;
    }

    fetch(`{{route('private.permissions.toggle', $permission->id)}}`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Cloner la permission
function clonePermission() {
    if (confirm('Voulez-vous cloner cette permission ?')) {
        window.location.href = `{{route('private.permissions.clone', $permission->id)}}`;
    }
}

// Supprimer la permission
function deletePermission() {
    showDeleteModal();
    document.getElementById('confirmDelete').onclick = function() {
        fetch(`{{route('private.permissions.destroy', $permission->id)}}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeDeleteModal();
            if (data.success) {
                window.location.href = '{{ route("private.permissions.index") }}';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    };
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

@endsection
