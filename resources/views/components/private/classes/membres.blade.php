@extends('layouts.private.main')
@section('title', 'Membres - ' . $classe->nom)

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Membres de {{ $classe->nom }}</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.classes.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-chalkboard-teacher mr-2"></i>
                                Classes
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <a href="{{ route('private.classes.show', $classe) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">{{ $classe->nom }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">Membres</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="flex items-center space-x-3">
                @can('classes.manage-members')
                    <button onclick="showAddMemberModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white text-sm font-medium rounded-xl hover:from-blue-600 hover:to-purple-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i> Ajouter un membre
                    </button>
                    <button onclick="showBulkAddModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-users mr-2"></i> Ajout groupé
                    </button>
                @endcan
                @can('classes.export')
                    <div class="relative">
                        <button onclick="toggleExportMenu()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-sm font-medium rounded-xl hover:from-indigo-600 hover:to-purple-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                            <i class="fas fa-chevron-down ml-2"></i>
                        </button>
                        <div id="exportMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 z-10">
                            <div class="py-2">
                                <a href="{{ route('private.classes.export', $classe->id) }}?format=csv" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    <i class="fas fa-file-csv mr-2"></i> Export CSV
                                </a>
                                <a href="{{ route('private.classes.export', $classe->id) }}?format=excel" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                                </a>
                                <a href="{{ route('private.classes.export', $classe->id) }}?format=pdf" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
        <p class="text-slate-500 mt-2">Gestion des membres de la classe - {{ $classe->membres->count() }} membre(s) inscrit(s)</p>
    </div>

    <!-- Informations de la classe -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-xl">
                    <div class="text-2xl font-bold text-blue-600">{{ $classe->membres->count() }}</div>
                    <div class="text-sm text-blue-700 font-medium">Membres inscrits</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-xl">
                    <div class="text-2xl font-bold text-green-600">{{ 50 - $classe->membres->count() }}</div>
                    <div class="text-sm text-green-700 font-medium">Places disponibles</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-xl">
                    <div class="text-2xl font-bold text-purple-600">{{ round(($classe->membres->count() / 50) * 100, 1) }}%</div>
                    <div class="text-sm text-purple-700 font-medium">Taux de remplissage</div>
                </div>
                <div class="text-center p-4 bg-amber-50 rounded-xl">
                    <div class="text-2xl font-bold text-amber-600">{{ $classe->tranche_age ?: 'Non définie' }}</div>
                    <div class="text-sm text-amber-700 font-medium">Tranche d'âge</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-filter text-blue-600 mr-2"></i>
                Filtres et Recherche
            </h2>
        </div>
        <div class="p-6">
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" id="searchMember" placeholder="Nom, prénom ou email..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut membre</label>
                    <select id="filterStatus" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                        <option value="visiteur">Visiteur</option>
                        <option value="nouveau_converti">Nouveau converti</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Âge</label>
                    <select id="filterAge" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les âges</option>
                        <option value="0-12">0-12 ans</option>
                        <option value="13-17">13-17 ans</option>
                        <option value="18-30">18-30 ans</option>
                        <option value="31-50">31-50 ans</option>
                        <option value="51+">51+ ans</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="clearFilters()" class="w-full px-4 py-2 bg-slate-600 text-white rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des membres -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-users text-purple-600 mr-2"></i>
                    Liste des Membres (<span id="memberCount">{{ $classe->membres->count() }}</span>)
                </h2>
                @can('classes.manage-members')
                    <div class="flex items-center space-x-2">
                        <button onclick="toggleBulkActions()" class="inline-flex items-center px-3 py-2 bg-amber-600 text-white text-sm font-medium rounded-lg hover:bg-amber-700 transition-colors">
                            <i class="fas fa-tasks mr-2"></i> Actions groupées
                        </button>
                        <button onclick="showTransferModal()" class="inline-flex items-center px-3 py-2 bg-cyan-600 text-white text-sm font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                            <i class="fas fa-exchange-alt mr-2"></i> Transférer
                        </button>
                    </div>
                @endcan
            </div>
        </div>
        <div class="p-6">
            @if($classe->membres->count() > 0)
                <div id="membersContainer">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($classe->membres as $membre)
                            <div class="member-card bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors border border-slate-200 overflow-hidden" data-member-id="{{ $membre->id }}">
                                <!-- Header avec checkbox et actions -->
                                <div class="p-4 pb-3">
                                    <div class="flex items-center justify-between mb-3">
                                        @can('classes.manage-members')
                                            <input type="checkbox" class="member-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" value="{{ $membre->id }}">
                                        @else
                                            <div></div>
                                        @endcan

                                        <!-- Menu actions -->
                                        @can('classes.manage-members')
                                            <div class="relative">
                                                <button onclick="toggleMemberMenu('{{ $membre->id }}')" class="p-1 text-slate-400 hover:text-slate-600 hover:bg-slate-200 rounded-lg transition-colors">
                                                    <i class="fas fa-ellipsis-v text-sm"></i>
                                                </button>
                                                <div id="memberMenu-{{ $membre->id }}" class="hidden absolute right-0 top-8 w-40 bg-white rounded-lg shadow-lg border border-slate-200 z-20">
                                                    <div class="py-1">
                                                        <button onclick="viewMember('{{ $membre->id }}')" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 flex items-center">
                                                            <i class="fas fa-eye mr-2 text-blue-600"></i> Voir le profil
                                                        </button>
                                                        <button onclick="editMember('{{ $membre->id }}')" class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 flex items-center">
                                                            <i class="fas fa-edit mr-2 text-yellow-600"></i> Modifier
                                                        </button>
                                                        <div class="border-t border-slate-100"></div>
                                                        <button onclick="removeMember('{{ $membre->id }}')" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center">
                                                            <i class="fas fa-user-times mr-2"></i> Retirer
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endcan
                                    </div>

                                    <!-- Profil membre -->
                                    <div class="flex items-center space-x-3">
                                        <!-- Avatar -->
                                        <div class="relative flex-shrink-0">
                                            @if($membre->photo_profil)
                                                <img src="{{ asset('storage/' . $membre->photo_profil) }}" alt="{{ $membre->nom_complet }}" class="w-12 h-12 rounded-full object-cover">
                                            @else
                                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ substr($membre->prenom, 0, 1) }}{{ substr($membre->nom, 0, 1) }}
                                                </div>
                                            @endif

                                            <!-- Indicateur de statut -->
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white
                                                @if($membre->statut_membre === 'actif') bg-green-500
                                                @elseif($membre->statut_membre === 'inactif') bg-red-500
                                                @elseif($membre->statut_membre === 'visiteur') bg-yellow-500
                                                @else bg-blue-500
                                                @endif">
                                            </div>
                                        </div>

                                        <!-- Informations -->
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-slate-900 truncate">{{ $membre->nom_complet }}</h4>
                                            <p class="text-sm text-slate-600 truncate">{{ $membre->email }}</p>
                                            @if($membre->telephone_1)
                                                <p class="text-sm text-slate-500 truncate">{{ $membre->telephone_1 }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer avec informations supplémentaires et badges -->
                                <div class="px-4 pb-4">
                                    <!-- Âge si disponible -->
                                    @if($membre->date_naissance)
                                        <div class="text-xs text-slate-400 mb-2">
                                            {{ $membre->date_naissance->diffInYears(now()) }} ans
                                            @if($membre->date_naissance->format('m-d') === now()->format('m-d'))
                                                🎂 <span class="text-blue-600">Anniversaire aujourd'hui !</span>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Badges de statut -->
                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($membre->statut_membre === 'actif') bg-green-100 text-green-800
                                            @elseif($membre->statut_membre === 'inactif') bg-red-100 text-red-800
                                            @elseif($membre->statut_membre === 'visiteur') bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $membre->statut_membre)) }}
                                        </span>

                                        @if($membre->statut_bapteme === 'baptise')
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-check mr-1"></i> Baptisé
                                            </span>
                                        @endif

                                        @if($membre->responsabilites && is_array($membre->responsabilites))
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                <i class="fas fa-star mr-1"></i> Responsable
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions groupées (masquées par défaut) -->
                <div id="bulkActions" class="hidden mt-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-medium text-amber-800">
                                <span id="selectedCount">0</span> membre(s) sélectionné(s)
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button onclick="bulkTransfer()" class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-exchange-alt mr-1"></i> Transférer
                            </button>
                            <button onclick="bulkRemove()" class="px-3 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-user-times mr-1"></i> Retirer
                            </button>
                            <button onclick="bulkCommunicate()" class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-envelope mr-1"></i> Contacter
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-friends text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun membre inscrit</h3>
                    <p class="text-slate-500 mb-6">Cette classe n'a pas encore de membres inscrits.</p>
                    @can('classes.manage-members')
                        <div class="flex items-center justify-center space-x-3">
                            <button onclick="showAddMemberModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-user-plus mr-2"></i> Ajouter le premier membre
                            </button>
                            <button onclick="showBulkAddModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-users mr-2"></i> Ajout groupé
                            </button>
                        </div>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'ajout de membre unique -->
<div id="addMemberModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Ajouter un membre à la classe</h3>
        </div>
        <div class="p-6">
            <form id="addMemberForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Rechercher un utilisateur</label>
                    <div class="relative">
                        <input type="text" id="userSearch" placeholder="Tapez le nom ou email..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                    <div id="searchResults" class="mt-2 max-h-32 overflow-y-auto hidden"></div>
                </div>
                <input type="hidden" id="selectedUserId" name="user_id">
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeAddMemberModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal d'ajout groupé -->
<div id="bulkAddModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Ajout groupé de membres</h3>
            <p class="text-sm text-slate-600 mt-1">Sélectionnez plusieurs utilisateurs à ajouter à la classe</p>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh]">
            <form id="bulkAddForm">
                <div class="mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-slate-700">Utilisateurs disponibles</label>
                        <div class="flex items-center space-x-2">
                            <label class="flex items-center">
                                <input type="checkbox" id="filterCompatible" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-600">Âge compatible uniquement</span>
                            </label>
                            <input type="text" id="searchUsers" placeholder="Rechercher..." class="px-3 py-1 text-sm border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    <div id="availableUsersList" class="space-y-2 max-h-64 overflow-y-auto border border-slate-200 rounded-lg p-3">
                        <div class="text-center py-8 text-slate-500">
                            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                            <p>Chargement des utilisateurs...</p>
                        </div>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-sm">
                        <span id="selectedBulkCount" class="text-slate-600">0 utilisateur(s) sélectionné(s)</span>
                        <button type="button" onclick="selectAllVisible()" class="text-blue-600 hover:text-blue-800">Tout sélectionner</button>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" id="forceAgeCheck" class="rounded border-slate-300 text-orange-600 focus:ring-orange-500">
                        <span class="ml-2 text-sm text-slate-700">Forcer l'ajout même si l'âge n'est pas compatible</span>
                    </label>
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeBulkAddModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-users mr-2"></i> Ajouter les membres
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de transfert -->
<div id="transferModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Transférer des membres</h3>
        </div>
        <div class="p-6">
            <form id="transferForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Classe de destination</label>
                    <select id="targetClass" name="target_class_id" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner une classe</option>
                        <!-- Options chargées dynamiquement -->
                    </select>
                </div>
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeTransferModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        Transférer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Variables globales
let selectedMembers = [];
let availableUsers = [];
let filteredUsers = [];

// Menu export
function toggleExportMenu() {
    const menu = document.getElementById('exportMenu');
    menu.classList.toggle('hidden');
}

// Menu actions membre
function toggleMemberMenu(memberId) {
    // Fermer tous les autres menus
    document.querySelectorAll('[id^="memberMenu-"]').forEach(menu => {
        if (menu.id !== `memberMenu-${memberId}`) {
            menu.classList.add('hidden');
        }
    });

    const menu = document.getElementById(`memberMenu-${memberId}`);
    menu.classList.toggle('hidden');
}

// Fermer menus quand on clique ailleurs
document.addEventListener('click', function(e) {
    if (!e.target.closest('[id^="memberMenu-"]') && !e.target.closest('button[onclick^="toggleMemberMenu"]')) {
        document.querySelectorAll('[id^="memberMenu-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }

    const exportMenu = document.getElementById('exportMenu');
    if (!e.target.closest('#exportMenu') && !e.target.closest('button[onclick="toggleExportMenu()"]')) {
        exportMenu.classList.add('hidden');
    }
});

// Fonctions de recherche et filtrage
function filterMembers() {
    const search = document.getElementById('searchMember').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    const age = document.getElementById('filterAge').value;

    const memberCards = document.querySelectorAll('.member-card');
    let visibleCount = 0;

    memberCards.forEach(card => {
        const memberText = card.textContent.toLowerCase();
        let visible = true;

        // Filtrage par recherche
        if (search && !memberText.includes(search)) {
            visible = false;
        }

        // Filtrage par statut
        if (status) {
            const memberStatus = card.querySelector('.member-card').textContent.toLowerCase();
            if (!memberStatus.includes(status)) {
                visible = false;
            }
        }

        if (visible) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('memberCount').textContent = visibleCount;
}

function clearFilters() {
    document.getElementById('searchMember').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterAge').value = '';
    filterMembers();
}

// Event listeners pour les filtres
document.getElementById('searchMember').addEventListener('input', filterMembers);
document.getElementById('filterStatus').addEventListener('change', filterMembers);
document.getElementById('filterAge').addEventListener('change', filterMembers);

// Fonctions modales
function showAddMemberModal() {
    document.getElementById('addMemberModal').classList.remove('hidden');
}

function closeAddMemberModal() {
    document.getElementById('addMemberModal').classList.add('hidden');
    document.getElementById('selectedUserId').value = '';
    document.getElementById('userSearch').value = '';
    document.getElementById('searchResults').classList.add('hidden');
}

function showBulkAddModal() {
    document.getElementById('bulkAddModal').classList.remove('hidden');
    loadAvailableUsersForBulk();
}

function closeBulkAddModal() {
    document.getElementById('bulkAddModal').classList.add('hidden');
    availableUsers = [];
    filteredUsers = [];
    document.getElementById('availableUsersList').innerHTML = '';
    updateSelectedBulkCount();
}

function showTransferModal() {
    document.getElementById('transferModal').classList.remove('hidden');
    loadAvailableClasses();
}

function closeTransferModal() {
    document.getElementById('transferModal').classList.add('hidden');
}

// Fonctions de gestion des membres
function viewMember(memberId) {
    window.location.href = `/private/users/${memberId}`;
}

function editMember(memberId) {
    window.location.href = `/private/users/${memberId}/edit`;
}

function removeMember(memberId) {
    if (!confirm('Êtes-vous sûr de vouloir retirer ce membre de la classe ?')) {
        return;
    }

    fetch("{{ route('private.classes.desinscrire', $classe->id) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            user_id: memberId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            alert(data.message || 'Erreur lors de la suppression du membre');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Fonctions de gestion des sélections groupées
function toggleBulkActions() {
    const bulkActions = document.getElementById('bulkActions');
    const checkboxes = document.querySelectorAll('.member-checkbox');

    if (bulkActions.classList.contains('hidden')) {
        bulkActions.classList.remove('hidden');
        checkboxes.forEach(cb => cb.style.display = 'block');
    } else {
        bulkActions.classList.add('hidden');
        checkboxes.forEach(cb => {
            cb.style.display = 'none';
            cb.checked = false;
        });
        updateSelectedCount();
    }
}

function updateSelectedCount() {
    const selected = document.querySelectorAll('.member-checkbox:checked');
    document.getElementById('selectedCount').textContent = selected.length;
    selectedMembers = Array.from(selected).map(cb => cb.value);
}

function updateSelectedBulkCount() {
    const checkedBoxes = document.querySelectorAll('#availableUsersList input[type="checkbox"]:checked');
    const count = checkedBoxes.length;
    document.getElementById('selectedBulkCount').textContent = `${count} utilisateur(s) sélectionné(s)`;
}

// Event listeners pour les checkboxes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.member-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
});

// Fonctions d'actions groupées
function bulkTransfer() {
    if (selectedMembers.length === 0) {
        alert('Veuillez sélectionner au moins un membre');
        return;
    }
    showTransferModal();
}

function bulkRemove() {
    if (selectedMembers.length === 0) {
        alert('Veuillez sélectionner au moins un membre');
        return;
    }

    if (!confirm(`Êtes-vous sûr de vouloir retirer ${selectedMembers.length} membre(s) de la classe ?`)) {
        return;
    }

    // Implémenter la suppression groupée
    console.log('Retirer les membres:', selectedMembers);
}

function bulkCommunicate() {
    if (selectedMembers.length === 0) {
        alert('Veuillez sélectionner au moins un membre');
        return;
    }

    // Implémenter la communication groupée
    console.log('Contacter les membres:', selectedMembers);
}

// Chargement des utilisateurs pour l'ajout groupé
function loadAvailableUsersForBulk() {
    const container = document.getElementById('availableUsersList');
    container.innerHTML = `
        <div class="text-center py-8 text-slate-500">
            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
            <p>Chargement des utilisateurs...</p>
        </div>
    `;

    fetch("{{ route('private.classes.utilisateurs-disponibles', $classe->id) }}", {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            availableUsers = data.data.utilisateurs_disponibles;
            filteredUsers = [...availableUsers];
            renderUsersList();
        } else {
            container.innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                    <p>Erreur lors du chargement</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        container.innerHTML = `
            <div class="text-center py-8 text-red-500">
                <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                <p>Erreur lors du chargement</p>
            </div>
        `;
    });
}

// Rendu de la liste des utilisateurs
function renderUsersList() {
    const container = document.getElementById('availableUsersList');

    if (filteredUsers.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-slate-500">
                <i class="fas fa-users text-2xl mb-2"></i>
                <p>Aucun utilisateur disponible</p>
            </div>
        `;
        return;
    }

    container.innerHTML = filteredUsers.map(user => `
        <label class="flex items-center p-3 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
            <input type="checkbox" name="user_ids[]" value="${user.id}" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 mr-3" onchange="updateSelectedBulkCount()">
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <span class="font-medium text-slate-900">${user.prenom} ${user.nom}</span>
                    <div class="flex items-center space-x-2">
                        ${user.age ? `<span class="text-sm text-slate-500">${user.age} ans</span>` : ''}
                        ${user.age_compatible ?
                            '<span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Compatible</span>' :
                            '<span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">⚠️ Âge incompatible</span>'
                        }
                    </div>
                </div>
                <p class="text-sm text-slate-500">${user.email}</p>
            </div>
        </label>
    `).join('');
}

// Filtrage des utilisateurs
function filterUsers() {
    const searchTerm = document.getElementById('searchUsers').value.toLowerCase();
    const compatibleOnly = document.getElementById('filterCompatible').checked;

    filteredUsers = availableUsers.filter(user => {
        const matchesSearch = !searchTerm ||
            user.prenom.toLowerCase().includes(searchTerm) ||
            user.nom.toLowerCase().includes(searchTerm) ||
            user.email.toLowerCase().includes(searchTerm);

        const matchesCompatible = !compatibleOnly || user.age_compatible;

        return matchesSearch && matchesCompatible;
    });

    renderUsersList();
    updateSelectedBulkCount();
}

// Event listeners pour le filtrage
document.getElementById('searchUsers').addEventListener('input', filterUsers);
document.getElementById('filterCompatible').addEventListener('change', filterUsers);

// Sélectionner tous les utilisateurs visibles
function selectAllVisible() {
    const checkboxes = document.querySelectorAll('#availableUsersList input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    checkboxes.forEach(cb => {
        cb.checked = !allChecked;
    });

    updateSelectedBulkCount();
}

// Chargement des classes disponibles pour le transfert
function loadAvailableClasses() {
    fetch("{{ route('private.classes.index') }}", {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const select = document.getElementById('targetClass');
        select.innerHTML = '<option value="">Sélectionner une classe</option>';

        if (data.success && data.data.data) {

            data.data.data.forEach(classe => {
                if (classe.id !== '{{ $classe->id }}') {
                    const option = document.createElement('option');
                    option.value = classe.id;
                    option.textContent = `${classe.nom} (${50 - classe.nombre_inscrits} places libres)`;
                    select.appendChild(option);
                }
            });
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}

// Recherche d'utilisateurs pour l'ajout simple
let searchTimeout;
document.getElementById('userSearch').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const query = this.value.trim();

    if (query.length < 2) {
        document.getElementById('searchResults').classList.add('hidden');
        return;
    }

    searchTimeout = setTimeout(() => {
        fetch(`{{ route('private.classes.utilisateurs-disponibles', $classe->id) }}?search=${encodeURIComponent(query)}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const results = document.getElementById('searchResults');
            results.innerHTML = '';

            if (data.success && data.data.utilisateurs_disponibles.length > 0) {
                data.data.utilisateurs_disponibles.slice(0, 5).forEach(user => {
                    const item = document.createElement('div');
                    item.className = 'p-2 hover:bg-slate-100 cursor-pointer rounded border-b border-slate-200 last:border-b-0';
                    item.innerHTML = `
                        <div class="font-medium">${user.prenom} ${user.nom}</div>
                        <div class="text-sm text-slate-500">${user.email}</div>
                        ${user.age ? `<div class="text-xs text-slate-400">${user.age} ans${user.age_compatible ? ' - Compatible' : ' - ⚠️ Incompatible'}</div>` : ''}
                    `;
                    item.onclick = () => selectUser(user);
                    results.appendChild(item);
                });
                results.classList.remove('hidden');
            } else {
                results.innerHTML = '<div class="p-2 text-slate-500">Aucun utilisateur trouvé</div>';
                results.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    }, 300);
});

function selectUser(user) {
    document.getElementById('userSearch').value = `${user.prenom} ${user.nom}`;
    document.getElementById('selectedUserId').value = user.id;
    document.getElementById('searchResults').classList.add('hidden');
}

// Gestion du formulaire d'ajout simple
document.getElementById('addMemberForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const userId = document.getElementById('selectedUserId').value;
    if (!userId) {
        alert('Veuillez sélectionner un utilisateur');
        return;
    }

    fetch("{{ route('private.classes.inscrire', $classe->id) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            user_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddMemberModal();
            showSuccessMessage(data.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            alert(data.message || 'Erreur lors de l\'ajout du membre');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
});

// Gestion du formulaire d'ajout groupé
document.getElementById('bulkAddForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const checkedBoxes = document.querySelectorAll('#availableUsersList input[type="checkbox"]:checked');
    const userIds = Array.from(checkedBoxes).map(cb => cb.value);

    if (userIds.length === 0) {
        alert('Veuillez sélectionner au moins un utilisateur');
        return;
    }

    const forceAgeCheck = document.getElementById('forceAgeCheck').checked;

    if (!confirm(`Voulez-vous ajouter ${userIds.length} utilisateur(s) à la classe ?`)) {
        return;
    }

    fetch("{{ route('private.classes.ajouter-membres', $classe->id) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            user_ids: userIds,
            force_age_check: forceAgeCheck
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeBulkAddModal();
            showSuccessMessage(data.message);
            setTimeout(() => location.reload(), 2000);
        } else {
            alert(data.message || 'Erreur lors de l\'ajout des membres');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
});

// Fonction d'affichage des messages de succès
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}

// Fermer les modals en cliquant à l'extérieur
document.getElementById('addMemberModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddMemberModal();
    }
});

document.getElementById('bulkAddModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkAddModal();
    }
});

document.getElementById('transferModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTransferModal();
    }
});
</script>

@endsection
