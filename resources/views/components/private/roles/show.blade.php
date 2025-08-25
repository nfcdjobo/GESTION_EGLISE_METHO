@extends('layouts.private.main')
@section('title', 'Détails du Rôle: ' . $role->name)

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center space-x-2 mb-2">
            @if($role->is_system_role)
                <i class="fas fa-lock text-yellow-500 text-xl"></i>
            @endif
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">{{ $role->name }}</h1>
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
                        <span class="text-sm font-medium text-slate-500">{{ $role->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions principales -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 p-6">
        <div class="flex flex-wrap justify-center gap-3">
            @can('roles.update')
                <a href="{{ route('private.roles.edit', $role) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-edit mr-2"></i> Modifier
                </a>
            @endcan

            @can('roles.manage')
                <a href="{{ route('private.roles.permissions', $role) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-key mr-2"></i> Gérer les Permissions
                </a>
            @endcan

            @can('roles.create')
                <button type="button" onclick="cloneRole()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-copy mr-2"></i> Cloner
                </button>
            @endcan

            @can('roles.assign')
                <button type="button" onclick="showAssignUserModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-user-plus mr-2"></i> Attribuer à un utilisateur
                </button>
            @endcan

            @can('roles.delete')
                @if($role->canBeDeleted())
                    <button type="button" onclick="deleteRole()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                @endif
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations du rôle et Statistiques -->
        <div class="space-y-6">
            <!-- Informations du rôle -->
            <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations du Rôle
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <span class="text-sm font-medium text-slate-700">Nom:</span>
                        <div class="mt-1 flex items-center space-x-2">
                            @if($role->is_system_role)
                                <i class="fas fa-lock text-yellow-500" title="Rôle système"></i>
                            @endif
                            <span class="font-semibold text-slate-900">{{ $role->name }}</span>
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Slug:</span>
                        <div class="mt-1">
                            <code class="px-2 py-1 text-xs bg-slate-100 text-slate-800 rounded">{{ $role->slug }}</code>
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Description:</span>
                        <div class="mt-1 text-slate-600">
                            {{ $role->description ?: 'Aucune description' }}
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Niveau hiérarchique:</span>
                        <div class="mt-1 flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                            <span class="text-sm text-slate-600">
                                @if($role->level >= 100)
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
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Type:</span>
                        <div class="mt-1">
                            @if($role->is_system_role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-lock mr-1"></i> Rôle Système
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-cog mr-1"></i> Rôle Personnalisé
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Créé le:</span>
                        <div class="mt-1 text-sm text-slate-600">
                            {{ $role->created_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>

                    <div>
                        <span class="text-sm font-medium text-slate-700">Dernière modification:</span>
                        <div class="mt-1 text-sm text-slate-600">
                            {{ $role->updated_at->format('d/m/Y à H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                        Statistiques
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 p-4 rounded-xl border border-blue-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-blue-800">{{ $stats['total_users'] }}</p>
                                <p class="text-sm text-blue-600">Utilisateurs Total</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl border border-green-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-green-800">{{ $stats['active_users'] }}</p>
                                <p class="text-sm text-green-600">Utilisateurs Actifs</p>
                            </div>
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-user-check text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-4 rounded-xl border border-purple-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-bold text-purple-800">{{ $stats['total_permissions'] }}</p>
                                <p class="text-sm text-purple-600">Permissions</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-key text-white text-xl"></i>
                            </div>
                        </div>
                    </div>

                    @if($stats['expiring_soon'] > 0)
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-4 rounded-xl border border-yellow-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold text-yellow-800">{{ $stats['expiring_soon'] }}</p>
                                    <p class="text-sm text-yellow-700">Expire sous 7 jours</p>
                                </div>
                                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Permissions -->
        <div class="lg:col-span-2">
            <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-key text-amber-600 mr-2"></i>
                            Permissions ({{ $stats['total_permissions'] }})
                        </h2>
                        @can('roles.manage')
                            <a href="{{ route('private.roles.permissions', $role) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i> Gérer
                            </a>
                        @endcan
                    </div>
                </div>
                <div class="p-6">
                    @if($permissions->count() > 0)
                        <div class="space-y-6">
                            @foreach($permissions as $category => $categoryPermissions)
                                <div class="permission-category border border-slate-200 rounded-xl overflow-hidden">
                                    <div class="bg-gradient-to-r from-slate-50 to-blue-50 p-4 border-b border-slate-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <h3 class="text-lg font-semibold text-slate-800">{{ ucfirst($category) }}</h3>
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
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($categoryPermissions as $permission)
                                                <div class="p-4 border border-slate-200 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mt-0.5">
                                                            <i class="fas fa-check text-green-600 text-xs"></i>
                                                        </div>
                                                        <div class="flex-1">
                                                            <h4 class="font-semibold text-slate-900">{{ $permission->name }}</h4>
                                                            @if($permission->description)
                                                                <p class="text-sm text-slate-600 mt-1">{{ $permission->description }}</p>
                                                            @endif
                                                            <code class="text-xs bg-slate-200 text-slate-700 px-2 py-1 rounded mt-2 inline-block">{{ $permission->slug }}</code>
                                                            @if($permission->pivot->expire_le)
                                                                <div class="mt-2 flex items-center text-xs text-yellow-600">
                                                                    <i class="fas fa-clock mr-1"></i>
                                                                    Expire le {{ \Carbon\Carbon::parse($permission->pivot->expire_le)->format('d/m/Y') }}
                                                                </div>
                                                            @endif
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
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune permission attribuée</h3>
                            <p class="text-slate-500 mb-6">Ce rôle n'a aucune permission pour le moment.</p>
                            @can('roles.manage')
                                <a href="{{ route('private.roles.permissions', $role) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-plus mr-2"></i> Attribuer des permissions
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Utilisateurs récents -->
    <div class="bg-white/80  rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-users text-indigo-600 mr-2"></i>
                    Utilisateurs Récents
                </h2>
                @can('roles.assign')
                    <button type="button" onclick="showAssignUserModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-user-plus mr-2"></i> Attribuer
                    </button>
                @endcan
            </div>
        </div>
        <div class="p-6">
            @if($recentUsers->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Utilisateur</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Attribué le</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Attribué par</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Expire le</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($recentUsers as $user)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            @if($user->avatar)
                                                <img src="{{ $user->avatar }}" class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($user->nom_complet, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-semibold text-slate-900 flex items-center">
                                                    {{ $user->nom_complet }}
                                                    @if($user->pivot->actif && (!$user->pivot->expire_le || $user->pivot->expire_le > now()))
                                                        <i class="fas fa-check-circle text-green-500 ml-2" title="Actif"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($user->pivot->attribue_le)->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-4 text-sm text-slate-600">
                                        @if($user->pivot->attribue_par)
                                            {{ \App\Models\User::find($user->pivot->attribue_par)?->nom_complet ?? 'N/A' }}
                                        @else
                                            <span class="text-slate-400">Système</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        @if($user->pivot->expire_le)
                                            @php
                                                $expireDate = \Carbon\Carbon::parse($user->pivot->expire_le);
                                                $isExpiringSoon = $expireDate->diffInDays(now()) <= 7 && $expireDate->isFuture();
                                                $isExpired = $expireDate->isPast();
                                            @endphp
                                            <span class="
                                                @if($isExpired) text-red-600
                                                @elseif($isExpiringSoon) text-yellow-600
                                                @else text-slate-600
                                                @endif">
                                                {{ $expireDate->format('d/m/Y') }}
                                                @if($isExpiringSoon)
                                                    <i class="fas fa-exclamation-triangle ml-1"></i>
                                                @elseif($isExpired)
                                                    <i class="fas fa-times-circle ml-1"></i>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-slate-400">Permanent</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($user->pivot->actif)
                                            @if(!$user->pivot->expire_le || $user->pivot->expire_le > now())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Actif</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Expiré</span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @can('roles.assign')
                                            <button type="button" onclick="removeUserRole({{ $user->id }})" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun utilisateur</h3>
                    <p class="text-slate-500 mb-6">Ce rôle n'est attribué à aucun utilisateur pour le moment.</p>
                    @can('roles.assign')
                        <button type="button" onclick="showAssignUserModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i> Attribuer à un utilisateur
                        </button>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'attribution d'utilisateur -->
@can('roles.assign')
<div id="assignUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Attribuer le rôle à un utilisateur</h3>
                <button type="button" onclick="closeAssignUserModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <form id="assignUserForm" class="p-6 space-y-4">
            <div>
                <label for="user_id" class="block text-sm font-medium text-slate-700 mb-2">Utilisateur</label>
                <select id="user_id" name="user_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Sélectionnez un utilisateur...</option>
                </select>
            </div>

            <div>
                <label for="expires_at" class="block text-sm font-medium text-slate-700 mb-2">Date d'expiration (optionnel)</label>
                <input type="datetime-local" id="expires_at" name="expires_at" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                <p class="mt-1 text-sm text-slate-500">Laissez vide pour une attribution permanente</p>
            </div>

            <div>
                <label for="reason" class="block text-sm font-medium text-slate-700 mb-2">Raison (optionnel)</label>
                <textarea id="reason" name="reason" rows="3" placeholder="Motif de l'attribution..." class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"></textarea>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4">
                <button type="button" onclick="closeAssignUserModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    Attribuer
                </button>
            </div>
        </form>
    </div>
</div>
@endcan

<script>
// Basculer la visibilité des catégories de permissions
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

// Cloner le rôle
function cloneRole() {
    if (confirm('Voulez-vous cloner ce rôle ?')) {
        window.location.href = `{{ route('private.roles.clone', $role) }}`;
    }
}

// Supprimer le rôle
function deleteRole() {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?\nCette action est irréversible.')) {
        fetch(`{{ route('private.roles.destroy', $role) }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route("private.roles.index") }}';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Retirer le rôle d'un utilisateur
function removeUserRole(userId) {
    if (confirm('Voulez-vous retirer ce rôle de cet utilisateur ?')) {
        fetch(`{{ route('private.roles.remove.user', $role) }}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ user_id: userId })
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
}

@can('roles.assign')
// Modal functions
function showAssignUserModal() {
    document.getElementById('assignUserModal').classList.remove('hidden');
    loadUsers();
}

function closeAssignUserModal() {
    document.getElementById('assignUserModal').classList.add('hidden');
    document.getElementById('assignUserForm').reset();
}

// Charger la liste des utilisateurs
function loadUsers() {
    fetch('/admin/users/search', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(users => {
        const select = document.getElementById('user_id');
        select.innerHTML = '<option value="">Sélectionnez un utilisateur...</option>';

        users.forEach(user => {
            // Exclure les utilisateurs qui ont déjà ce rôle
            const hasRole = {{ $role->users->pluck('id')->toJson() }}.includes(user.id);
            if (!hasRole) {
                const option = document.createElement('option');
                option.value = user.id;
                option.textContent = `${user.nom_complet} (${user.email})`;
                select.appendChild(option);
            }
        });
    })
    .catch(error => {
        console.error('Erreur lors du chargement des utilisateurs:', error);
    });
}

// Soumission du formulaire d'attribution
document.getElementById('assignUserForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    fetch(`{{ route('private.roles.assign.user', $role) }}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAssignUserModal();
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
});

// Close modal when clicking outside
document.getElementById('assignUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAssignUserModal();
    }
});
@endcan
</script>

@endsection
