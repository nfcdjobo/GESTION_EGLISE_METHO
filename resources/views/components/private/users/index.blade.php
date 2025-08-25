@extends('layouts.private.main')
@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Utilisateurs</h1>
        <p class="text-slate-500 mt-1">Administration des membres et utilisateurs - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $users->total() }}</p>
                    <p class="text-sm text-slate-500">Total utilisateurs</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $users->where('statut_membre', 'actif')->count() }}</p>
                    <p class="text-sm text-slate-500">Membres actifs</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $users->where('statut_membre', 'nouveau_converti')->count() }}</p>
                    <p class="text-sm text-slate-500">Nouveaux convertis</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-eye text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $users->where('statut_membre', 'visiteur')->count() }}</p>
                    <p class="text-sm text-slate-500">Visiteurs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et Actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Actions
                </h2>
                <div class="flex flex-wrap gap-2">
                    @can('users.create')
                        <a href="{{ route('private.users.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouvel Utilisateur
                        </a>
                    @endcan
                    @can('users.import')
                        <a href="{{ route('private.users.import') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-upload mr-2"></i> Importer
                        </a>
                    @endcan
                    @can('export-data', 'users')
                        <a href="{{ route('private.users.export') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="p-6">
            <form method="GET" action="{{ route('private.users.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, prénom, email, téléphone..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut membre</label>
                    <select name="statut_membre" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="actif" {{ request('statut_membre') === 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ request('statut_membre') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="visiteur" {{ request('statut_membre') === 'visiteur' ? 'selected' : '' }}>Visiteur</option>
                        <option value="nouveau_converti" {{ request('statut_membre') === 'nouveau_converti' ? 'selected' : '' }}>Nouveau converti</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Rôle</label>
                    <select name="role" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les rôles</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->slug }}" {{ request('role') === $role->slug ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Classe</label>
                    <select name="classe_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $classe)
                        <option value="{{ $classe->id }}" {{ request('classe_id') == $classe->id ? 'selected' : '' }}>
                            {{ $classe->nom }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Filtrer
                    </button>
                    <a href="{{ route('private.users.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Utilisateurs ({{ $users->total() }})
                </h2>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $users->total() }} résultats
                    </span>
                    @if($users->hasPages())
                    <span class="text-sm text-slate-600">
                        Page {{ $users->currentPage() }} sur {{ $users->lastPage() }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'nom', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                       class="group inline-flex items-center hover:text-blue-600 transition-colors">
                                        Utilisateur
                                        <span class="ml-2 flex-none rounded text-slate-400 group-hover:text-blue-500">
                                            <i class="fas fa-sort"></i>
                                        </span>
                                    </a>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Contact</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Rôles</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Classe</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    <a href="{{ request()->fullUrlWithQuery(['sort' => 'date_adhesion', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}"
                                       class="group inline-flex items-center hover:text-blue-600 transition-colors">
                                        Adhésion
                                        <span class="ml-2 flex-none rounded text-slate-400 group-hover:text-blue-500">
                                            <i class="fas fa-sort"></i>
                                        </span>
                                    </a>
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($users as $user)
                            <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                <td class="px-4 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if($user->photo_profil)
                                            <img class="h-12 w-12 rounded-full object-cover ring-4 ring-white shadow-lg"
                                                 src="{{ Storage::url($user->photo_profil) }}"
                                                 alt="{{ $user->nom_complet }}">
                                            @else
                                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-{{ $user->sexe === 'feminin' ? 'pink' : 'blue' }}-400 to-{{ $user->sexe === 'feminin' ? 'purple' : 'indigo' }}-500 flex items-center justify-center shadow-lg ring-4 ring-white">
                                                <span class="text-sm font-bold text-white">
                                                    {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-slate-900">
                                                {{ $user->nom_complet }}
                                            </div>
                                            <div class="text-sm text-slate-500">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center text-sm text-slate-700">
                                            <i class="fas fa-phone text-green-500 mr-2"></i>
                                            {{ $user->telephone_1 }}
                                        </div>
                                        @if($user->telephone_2)
                                        <div class="flex items-center text-sm text-slate-500">
                                            <i class="fas fa-phone text-slate-400 mr-2"></i>
                                            {{ $user->telephone_2 }}
                                        </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-col space-y-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($user->statut_membre === 'actif') bg-green-100 text-green-800
                                            @elseif($user->statut_membre === 'visiteur') bg-blue-100 text-blue-800
                                            @elseif($user->statut_membre === 'nouveau_converti') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $user->statut_membre)) }}
                                        </span>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($user->statut_bapteme === 'baptise') bg-blue-100 text-blue-800
                                            @elseif($user->statut_bapteme === 'confirme') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $user->statut_bapteme)) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles->take(3) as $role)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-lg bg-indigo-100 text-indigo-800">
                                            {{ $role->name }}
                                        </span>
                                        @endforeach
                                        @if($user->roles->count() > 3)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-600">
                                            +{{ $user->roles->count() - 3 }}
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    @if($user->classe)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $user->classe->nom }}
                                    </span>
                                    @else
                                    <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-700">
                                    {{ $user->date_adhesion ? $user->date_adhesion->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('private.users.show', $user) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        @can('users.update')
                                        <a href="{{ route('private.users.edit', $user) }}"
                                           class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        @endcan
                                        @can('users.delete')
                                        <button onclick="deleteUser('{{ $user->id }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                        @endcan
                                        <button onclick="toggleStatus('{{ $user->id }}')"
                                                class="inline-flex items-center justify-center w-8 h-8 {{ $user->actif ? 'text-orange-600 bg-orange-100 hover:bg-orange-200' : 'text-green-600 bg-green-100 hover:bg-green-200' }} rounded-lg transition-colors"
                                                title="{{ $user->actif ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas fa-{{ $user->actif ? 'ban' : 'check' }} text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium">{{ $users->firstItem() }}</span> à <span class="font-medium">{{ $users->lastItem() }}</span>sur <span class="font-medium">{{ $users->total() }}</span> résultats
                    </div>
                    <div>
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun utilisateur trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['search', 'statut_membre', 'role', 'classe_id']))
                            Aucun utilisateur ne correspond à vos critères de recherche.
                        @else
                            Commencez par créer votre premier utilisateur.
                        @endif
                    </p>
                    @can('users.create')
                        <a href="{{ route('private.users.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Créer un utilisateur
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
        const url = "{{route('private.users.destroy', ':userid')}}".replace(':userid', userId);

        fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

function toggleStatus(userId) {
    fetch(`{{route('private.users.toggle-status', ':userId')}}`.replace(':userId', userId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Erreur lors du changement de statut');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors du changement de statut');
    });
}

// Animation des cartes au chargement
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.bg-white\\/80');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush
@endsection
