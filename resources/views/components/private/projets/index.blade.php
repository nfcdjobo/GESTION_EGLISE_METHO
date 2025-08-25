@extends('layouts.private.main')
@section('title', 'Gestion des Projets')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Projets</h1>
        <p class="text-slate-500 mt-1">Suivi et gestion des projets de l'église - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Actions
                </h2>
                <div class="flex flex-wrap gap-2">
                    @can('projets.create')
                        <a href="{{ route('private.projets.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouveau Projet
                        </a>
                    @endcan
                    <a href="{{ route('private.projets.statistiques') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                    <a href="{{ route('private.projets.publics') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-globe mr-2"></i> Projets Publics
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.projets.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, code, description..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        @foreach($options['statuts'] as $key => $label)
                            <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de Projet</label>
                    <select name="type_projet" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        @foreach($options['types_projet'] as $key => $label)
                            <option value="{{ $key }}" {{ request('type_projet') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                    <select name="priorite" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les priorités</option>
                        @foreach($options['priorites'] as $key => $label)
                            <option value="{{ $key }}" {{ request('priorite') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Responsable</label>
                    <select name="responsable_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les responsables</option>
                        @foreach($options['responsables'] as $responsable)
                            <option value="{{ $responsable['id'] }}" {{ request('responsable_id') == $responsable['id'] ? 'selected' : '' }}>{{ $responsable['nom_complet'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date début min</label>
                        <input type="date" name="date_debut_min" value="{{ request('date_debut_min') }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date début max</label>
                        <input type="date" name="date_debut_max" value="{{ request('date_debut_max') }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Budget min</label>
                        <input type="number" name="budget_min" value="{{ request('budget_min') }}" placeholder="0" min="0" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div class="flex items-end">
                        <div class="w-full space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="visible_public" value="1" {{ request('visible_public') ? 'checked' : '' }} id="visible_public" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="visible_public" class="ml-2 text-sm text-slate-700">Visibles publiquement</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="en_retard" value="1" {{ request('en_retard') ? 'checked' : '' }} id="en_retard" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="en_retard" class="ml-2 text-sm text-slate-700">Projets en retard</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="{{ route('private.projets.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-project-diagram text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $projets->total() }}</p>
                    <p class="text-sm text-slate-500">Total des projets</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-play text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $projets->where('statut', 'en_cours')->count() }}</p>
                    <p class="text-sm text-slate-500">Projets en cours</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $projets->where('statut', 'termine')->count() }}</p>
                    <p class="text-sm text-slate-500">Projets terminés</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-coins text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($projets->sum('budget_prevu') ?: 0) }} XOF</p>
                    <p class="text-sm text-slate-500">Budget total prévu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des projets -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Projets ({{ $projets->total() }})
                </h2>
                <div class="flex items-center space-x-2">
                    <select id="sortBy" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date de création</option>
                        <option value="nom_projet" {{ request('sort_by') == 'nom_projet' ? 'selected' : '' }}>Nom</option>
                        <option value="type_projet" {{ request('sort_by') == 'type_projet' ? 'selected' : '' }}>Type</option>
                        <option value="statut" {{ request('sort_by') == 'statut' ? 'selected' : '' }}>Statut</option>
                        <option value="priorite" {{ request('sort_by') == 'priorite' ? 'selected' : '' }}>Priorité</option>
                        <option value="budget_prevu" {{ request('sort_by') == 'budget_prevu' ? 'selected' : '' }}>Budget</option>
                        <option value="pourcentage_completion" {{ request('sort_by') == 'pourcentage_completion' ? 'selected' : '' }}>Progression</option>
                    </select>
                    <select id="sortOrder" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($projets->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($projets as $projet)
                        <div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $projet->nom_projet }}</h3>
                                    <p class="text-sm text-slate-600">{{ $projet->code_projet }} • {{ $projet->type_projet_libelle }}</p>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    @php
                                        $statutColors = [
                                            'conception' => 'bg-gray-100 text-gray-800',
                                            'planification' => 'bg-blue-100 text-blue-800',
                                            'recherche_financement' => 'bg-yellow-100 text-yellow-800',
                                            'en_attente' => 'bg-orange-100 text-orange-800',
                                            'en_cours' => 'bg-green-100 text-green-800',
                                            'suspendu' => 'bg-red-100 text-red-800',
                                            'termine' => 'bg-emerald-100 text-emerald-800',
                                            'annule' => 'bg-red-100 text-red-800',
                                            'archive' => 'bg-slate-100 text-slate-800'
                                        ];

                                        $prioriteColors = [
                                            'faible' => 'bg-gray-100 text-gray-600',
                                            'normale' => 'bg-blue-100 text-blue-600',
                                            'haute' => 'bg-yellow-100 text-yellow-600',
                                            'urgente' => 'bg-orange-100 text-orange-600',
                                            'critique' => 'bg-red-100 text-red-600'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statutColors[$projet->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $projet->statut_libelle }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioriteColors[$projet->priorite] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $projet->priorite_libelle }}
                                    </span>
                                </div>
                            </div>

                            <!-- Détails -->
                            <div class="space-y-3 mb-4">
                                @if($projet->date_debut)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                        <span>{{ \Carbon\Carbon::parse($projet->date_debut)->format('d/m/Y') }}</span>
                                        @if($projet->date_fin_prevue)
                                            <span class="mx-2">→</span>
                                            <span>{{ \Carbon\Carbon::parse($projet->date_fin_prevue)->format('d/m/Y') }}</span>
                                        @endif
                                    </div>
                                @endif

                                @if($projet->responsable)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-user w-4 mr-2"></i>
                                        <span>{{ $projet->responsable->nom }} {{ $projet->responsable->prenom }}</span>
                                    </div>
                                @endif

                                @if($projet->budget_prevu)
                                    <div class="flex items-center justify-between text-sm text-slate-600">
                                        <div class="flex items-center">
                                            <i class="fas fa-coins w-4 mr-2"></i>
                                            <span>{{ $projet->budget_format }}</span>
                                        </div>
                                        @if($projet->budget_collecte > 0)
                                            <span class="text-green-600 font-medium">{{ $projet->pourcentage_financement }}%</span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Barre de progression -->
                                <div class="space-y-1">
                                    <div class="flex justify-between text-sm text-slate-600">
                                        <span>Progression</span>
                                        <span>{{ $projet->pourcentage_completion }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ $projet->pourcentage_completion }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                <div class="flex items-center space-x-2">
                                    @can('projets.read')
                                        <a href="{{ route('private.projets.show', $projet) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    @endcan

                                    @can('projets.update')
                                        <a href="{{ route('private.projets.edit', $projet) }}" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    @endcan

                                    @if($projet->peutEtreApprouve())
                                        <button type="button" onclick="approveProject('{{ $projet->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Approuver">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                    @endif

                                    @if($projet->peutEtreDemarre())
                                        <button type="button" onclick="startProject('{{ $projet->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Démarrer">
                                            <i class="fas fa-play text-sm"></i>
                                        </button>
                                    @endif

                                    @can('projets.create')
                                        <button type="button" onclick="openDuplicateModal('{{ $projet->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors" title="Dupliquer">
                                            <i class="fas fa-copy text-sm"></i>
                                        </button>
                                    @endcan
                                </div>

                                @can('projets.delete')
                                    @if(!in_array($projet->statut, ['en_cours', 'suspendu']))
                                        <button type="button" onclick="deleteProject('{{ $projet->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium">{{ $projets->firstItem() }}</span> à <span class="font-medium">{{ $projets->lastItem() }}</span>
                        sur <span class="font-medium">{{ $projets->total() }}</span> résultats
                    </div>
                    <div>
                        {{ $projets->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-project-diagram text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun projet trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['search', 'statut', 'type_projet', 'priorite']))
                            Aucun projet ne correspond à vos critères de recherche.
                        @else
                            Commencez par créer votre premier projet.
                        @endif
                    </p>
                    @can('projets.create')
                        <a href="{{ route('private.projets.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Créer un projet
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal duplication -->
<div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Dupliquer le projet</h3>
            <form id="duplicateForm">
                @csrf
                <input type="hidden" id="duplicate_project_id" name="project_id">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau nom</label>
                        <input type="text" name="nouveau_nom" id="nouveau_nom" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Laisser vide pour ajouter (Copie)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau code</label>
                        <input type="text" name="nouveau_code" id="nouveau_code" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Laisser vide pour génération automatique">
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDuplicateModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="duplicateProject()" class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                Dupliquer
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Gestion du tri
document.getElementById('sortBy').addEventListener('change', function() {
    updateSort();
});

document.getElementById('sortOrder').addEventListener('change', function() {
    updateSort();
});

function updateSort() {
    const sortBy = document.getElementById('sortBy').value;
    const sortOrder = document.getElementById('sortOrder').value;
    const url = new URL(window.location.href);
    url.searchParams.set('sort_by', sortBy);
    url.searchParams.set('sort_order', sortOrder);
    window.location.href = url.toString();
}

// Approbation rapide
function approveProject(projectId) {
    if (confirm('Êtes-vous sûr de vouloir approuver ce projet ?')) {
        fetch(`{{ route('private.projets.approuver', ':projectid') }}`.replace(':projectid', projectId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Démarrage rapide
function startProject(projectId) {
    if (confirm('Êtes-vous sûr de vouloir démarrer ce projet ?')) {
        fetch(`{{ route('private.projets.demarrer', ':projectid') }}`.replace(':projectid', projectId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Modal duplication
function openDuplicateModal(projectId) {
    document.getElementById('duplicate_project_id').value = projectId;
    document.getElementById('duplicateModal').classList.remove('hidden');
}

function closeDuplicateModal() {
    document.getElementById('duplicateModal').classList.add('hidden');
    document.getElementById('duplicateForm').reset();
}

function duplicateProject() {
    const form = document.getElementById('duplicateForm');
    const formData = new FormData(form);
    const projectId = document.getElementById('duplicate_project_id').value;

    fetch(`{{ route('private.projets.dupliquer', ':projectid') }}`.replace(':projectid', projectId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = `{{ route('private.projets.show', ':projectid') }}`.replace(':projectid', data.data.id);
        } else {
            alert(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Suppression
function deleteProject(projectId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')) {
        fetch(`{{ route('private.projets.destroy', ':projectid') }}`.replace(':projectid', projectId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    }
}

// Fermer les modals en cliquant à l'extérieur
document.getElementById('duplicateModal').addEventListener('click', function(e) {
    if (e.target === this) closeDuplicateModal();
});
</script>
@endpush
@endsection
