@extends('layouts.private.main')
@section('title', 'Profil de ' . $user->nom_complet)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
       

        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Profil Membres</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.users.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-credit-card mr-2"></i>
                            Membres
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-users text-slate-500">Informations détaillées de {{ $user->nom_complet }} - {{ \Carbon\Carbon::now()->format('l d F Y') }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- En-tête du profil -->
        <div class="bg-white/80 rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            @if($user->photo_profil)
                            <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg"
                                 src="{{ Storage::url($user->photo_profil) }}"
                                 alt="{{ $user->nom_complet }}">
                            @else
                            <div class="h-24 w-24 rounded-full bg-white flex items-center justify-center border-4 border-white shadow-lg">
                                <span class="text-2xl font-bold text-indigo-600">
                                    {{ substr($user->prenom, 0, 1) }}{{ substr($user->nom, 0, 1) }}
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="text-white">
                            <h1 class="text-3xl font-bold">{{ $user->nom_complet }}</h1>
                            <p class="text-indigo-100 text-lg">{{ $user->email }}</p>
                            <div class="flex items-center space-x-4 mt-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($user->statut_membre === 'actif') bg-green-100 text-green-800
                                    @elseif($user->statut_membre === 'visiteur') bg-blue-100 text-blue-800
                                    @elseif($user->statut_membre === 'nouveau_converti') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $user->statut_membre)) }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($user->actif) bg-green-100 text-green-800 @else bg-red-100 text-red-800 @endif">
                                    <i class="fas fa-{{ $user->actif ? 'check' : 'ban' }} mr-1"></i>
                                    {{ $user->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('private.users.index') }}" class="inline-flex items-center px-4 py-2.5 bg-white/10border border-white/20 rounded-xl font-medium text-white hover:bg-white/20 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Retour
                        </a>
                        @can('users.update')
                        <a href="{{ route('private.users.edit', $user) }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-700 text-white rounded-xl font-medium hover:bg-indigo-800 transition-colors shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i>Modifier
                        </a>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-gray-50 border-b border-slate-200">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-lg">
                            <i class="fas fa-user-tag text-white"></i>
                        </div>
                        <div class="text-2xl font-bold text-indigo-600">{{ $stats['roles_count'] }}</div>
                        <div class="text-sm text-slate-600">Rôles actifs</div>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-lg">
                            <i class="fas fa-key text-white"></i>
                        </div>
                        <div class="text-2xl font-bold text-green-600">{{ $stats['permissions_count'] }}</div>
                        <div class="text-sm text-slate-600">Permissions</div>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-lg">
                            <i class="fas fa-credit-card text-white"></i>
                        </div>
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['transactions_count'] }}</div>
                        <div class="text-sm text-slate-600">Transactions</div>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-lg">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div class="text-2xl font-bold text-purple-600">{{ $stats['reunions_count'] }}</div>
                        <div class="text-sm text-slate-600">Réunions org.</div>
                    </div>
                    <div class="text-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mx-auto mb-2 shadow-lg">
                            <i class="fas fa-unlock text-white"></i>
                        </div>
                        <div class="text-2xl font-bold text-orange-600">{{ $stats['direct_permissions_count'] }}</div>
                        <div class="text-sm text-slate-600">Permissions directes</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations personnelles -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            Informations personnelles
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Prénom</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $user->prenom }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Nom</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $user->nom }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Date de naissance</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $user->date_naissance ? $user->date_naissance->format('d/m/Y') . ' (' . $user->date_naissance->age . ' ans)' : 'Non renseignée' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Sexe</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->sexe === 'masculin' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        <i class="fas fa-{{ $user->sexe === 'masculin' ? 'mars' : 'venus' }} mr-1"></i>
                                        {{ ucfirst($user->sexe) }}
                                    </span>
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Statut matrimonial</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $user->statut_matrimonial ? ucfirst($user->statut_matrimonial) : 'Non renseigné' }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Nombre d'enfants</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-baby mr-1"></i>
                                        {{ $user->nombre_enfants }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <i class="fas fa-phone text-white"></i>
                            </div>
                            Contact
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Téléphone principal</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    <a href="tel:{{ $user->telephone_1 }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 transition-colors">
                                        <i class="fas fa-phone mr-2"></i>
                                        {{ $user->telephone_1 }}
                                    </a>
                                </p>
                            </div>
                            @if($user->telephone_2)
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Téléphone secondaire</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    <a href="tel:{{ $user->telephone_2 }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 transition-colors">
                                        <i class="fas fa-phone mr-2"></i>
                                        {{ $user->telephone_2 }}
                                    </a>
                                </p>
                            </div>
                            @endif
                            <div class="md:col-span-2 space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Email</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    <a href="mailto:{{ $user->email }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 transition-colors">
                                        <i class="fas fa-envelope mr-2"></i>
                                        {{ $user->email }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <i class="fas fa-map-marker-alt text-white"></i>
                            </div>
                            Adresse
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="bg-slate-50 rounded-xl p-4 border-2 border-slate-200">
                            <div class="space-y-2 text-sm text-slate-900">
                                <p class="font-semibold">{{ $user->adresse_ligne_1 }}</p>
                                @if($user->adresse_ligne_2)
                                <p>{{ $user->adresse_ligne_2 }}</p>
                                @endif
                                <p>
                                    {{ $user->ville }}
                                    @if($user->code_postal)
                                    {{ $user->code_postal }}
                                    @endif
                                </p>
                                @if($user->region)
                                <p>{{ $user->region }}</p>
                                @endif
                                <p class="font-medium">{{ $user->pays }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations professionnelles -->
                @if($user->profession || $user->employeur)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <i class="fas fa-briefcase text-white"></i>
                            </div>
                            Informations professionnelles
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($user->profession)
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Profession</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $user->profession }}</p>
                            </div>
                            @endif
                            @if($user->employeur)
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Employeur</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $user->employeur }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Contact d'urgence -->
                @if($user->contact_urgence_nom || $user->contact_urgence_telephone)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                            Contact d'urgence
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @if($user->contact_urgence_nom)
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Nom</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $user->contact_urgence_nom }}</p>
                            </div>
                            @endif
                            @if($user->contact_urgence_telephone)
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Téléphone</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    <a href="tel:{{ $user->contact_urgence_telephone }}" class="inline-flex items-center text-red-600 hover:text-red-700 transition-colors">
                                        <i class="fas fa-phone mr-2"></i>
                                        {{ $user->contact_urgence_telephone }}
                                    </a>
                                </p>
                            </div>
                            @endif
                            @if($user->contact_urgence_relation)
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Relation</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $user->contact_urgence_relation }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Colonne latérale -->
            <div class="space-y-8">
                <!-- Informations d'église -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <i class="fas fa-church text-white"></i>
                            </div>
                            Informations d'église
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Classe</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    @if($user->classe)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $user->classe->nom }}
                                    </span>
                                    @else
                                    <span class="text-slate-400">Aucune</span>
                                    @endif
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Date d'adhésion</label>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $user->date_adhesion ? $user->date_adhesion->format('d/m/Y') : 'Non renseignée' }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Statut membre</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($user->statut_membre === 'actif') bg-green-100 text-green-800
                                    @elseif($user->statut_membre === 'visiteur') bg-blue-100 text-blue-800
                                    @elseif($user->statut_membre === 'nouveau_converti') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $user->statut_membre)) }}
                                </span>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Statut baptême</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($user->statut_bapteme === 'baptise') bg-blue-100 text-blue-800
                                    @elseif($user->statut_bapteme === 'confirme') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    <i class="fas fa-cross mr-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $user->statut_bapteme)) }}
                                </span>
                            </div>
                            @if($user->date_bapteme)
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Date de baptême</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $user->date_bapteme->format('d/m/Y') }}</p>
                            </div>
                            @endif
                            @if($user->eglise_precedente)
                            <div class="space-y-1">
                                <label class="block text-sm font-medium text-slate-500">Église précédente</label>
                                <p class="text-sm font-semibold text-slate-900">{{ $user->eglise_precedente }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Rôles et permissions -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <i class="fas fa-key text-white"></i>
                            </div>
                            Rôles et permissions
                        </h3>
                    </div>
                    <div class="p-6">
                        <!-- Rôles -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-slate-700 mb-3">Rôles actifs</h4>
                            @if($user->roles->count() > 0)
                            <div class="space-y-3">
                                @foreach($user->roles->where('pivot.actif', true) as $role)
                                <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-xl border-2 border-indigo-100">
                                    <div>
                                        <p class="text-sm font-medium text-indigo-900">{{ $role->name }}</p>
                                        <p class="text-xs text-indigo-600">Niveau {{ $role->level }}</p>
                                    </div>
                                    @if($role->pivot->expire_le)
                                    <span class="text-xs text-slate-500 bg-white px-2 py-1 rounded-lg">
                                        Expire: {{ \Carbon\Carbon::parse($role->pivot->expire_le)->format('d/m/Y') }}
                                    </span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @else
                            <p class="text-sm text-slate-500 italic">Aucun rôle attribué</p>
                            @endif
                        </div>

                        <!-- Permissions directes -->
                        @if($user->permissions->count() > 0)
                        <div>
                            <h4 class="text-sm font-medium text-slate-700 mb-3">Permissions directes</h4>
                            <div class="space-y-2">
                                @foreach($user->permissions->where('pivot.is_granted', true) as $permission)
                                <div class="flex items-center text-xs text-green-600 bg-green-50 px-3 py-2 rounded-lg">
                                    <i class="fas fa-check mr-2"></i>
                                    {{ $permission->name }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800">Actions rapides</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @can('users.update')
                            <button onclick="toggleStatus('{{ $user->id }}')"
                                    class="w-full text-left px-4 py-3 text-sm rounded-xl border-2 transition-all duration-200 flex items-center
                                    {{ $user->actif ? 'text-orange-600 border-orange-200 bg-orange-50 hover:bg-orange-100' : 'text-green-600 border-green-200 bg-green-50 hover:bg-green-100' }}">
                                <i class="fas fa-{{ $user->actif ? 'ban' : 'check' }} mr-3"></i>
                                {{ $user->actif ? 'Désactiver' : 'Activer' }}
                            </button>
                            @endcan

                            @if($user->statut_membre === 'visiteur')
                            @can('users.validate')
                            <button onclick="validateMember('{{ $user->id }}')"
                                    class="w-full text-left px-4 py-3 text-sm text-green-600 border-2 border-green-200 bg-green-50 rounded-xl hover:bg-green-100 transition-all duration-200 flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                Valider membre
                            </button>
                            @endcan
                            @endif

                            @can('users.archive')
                            <button onclick="archiveUser('{{ $user->id }}')"
                                    class="w-full text-left px-4 py-3 text-sm text-orange-600 border-2 border-orange-200 bg-orange-50 rounded-xl hover:bg-orange-100 transition-all duration-200 flex items-center">
                                <i class="fas fa-archive mr-3"></i>
                                Archiver
                            </button>
                            @endcan

                            <button onclick="resetPassword('{{ $user->id }}')"
                                    class="w-full text-left px-4 py-3 text-sm text-blue-600 border-2 border-blue-200 bg-blue-50 rounded-xl hover:bg-blue-100 transition-all duration-200 flex items-center">
                                <i class="fas fa-lock mr-3"></i>
                                Réinitialiser mot de passe
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Activité récente -->
                @if($recentActivity->count() > 0)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            Activité récente
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($recentActivity->take(5) as $activity)
                            <div class="p-3 bg-slate-50 rounded-xl border-2 border-slate-200">
                                <div class="font-medium text-slate-900 text-sm">{{ $activity->action }}</div>
                                <div class="text-slate-500 text-xs mt-1">{{ $activity->created_at->diffForHumans() }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Notes administratives -->
        @if($user->notes_admin)
        <div class="bg-yellow-50/80 backdrop-blur-sm border-2 border-yellow-200 rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-bold text-yellow-800 mb-3 flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                    <i class="fas fa-sticky-note text-white"></i>
                </div>
                Notes administratives
            </h3>
            <div class="text-sm text-yellow-700 whitespace-pre-line bg-white/50 p-4 rounded-xl border border-yellow-200">{{ $user->notes_admin }}</div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleStatus(userId) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut de cet membres ?')) {
        fetch(`{{route('private.users.toggle-status', ':user')}}`.replace(':user', userId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
        });
    }
}

function validateMember(userId) {
    if (confirm('Êtes-vous sûr de vouloir valider ce membre ?')) {
        fetch(`{{route('private.users.validate', ':user')}}`.replace(':user', userId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de la validation');
            }
        });
    }
}

function archiveUser(userId) {
    if (confirm('Êtes-vous sûr de vouloir archiver cet membres ?')) {
        fetch(`{{route('private.users.archive', ':user')}}`.replace(':user', userId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Erreur lors de l\'archivage');
            }
        });
    }
}

function resetPassword(userId) {
    const newPassword = prompt('Entrez le nouveau mot de passe:');
    const confirmPassword = prompt('Confirmez le nouveau mot de passe:');

    if (newPassword && confirmPassword) {
        if (newPassword !== confirmPassword) {
            alert('Les mots de passe ne correspondent pas');
            return;
        }

        fetch(`{{route('private.users.reset-password', ':user')}}`.replace(':user', userId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Mot de passe réinitialisé avec succès!');
            } else {
                alert(data.message || 'Erreur lors de la réinitialisation');
            }
        });
    }
}

// Animation des cartes au chargement
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.bg-white\\/80');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        // card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            // card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush
@endsection
