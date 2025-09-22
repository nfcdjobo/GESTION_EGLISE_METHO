@extends('layouts.private.main')
@section('title', 'Gestion des Moissons')

@section('content')

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    min-width: 400px;
    max-width: 500px;
}

.modal-title {
    font-size: 20px;
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 20px;
    text-align: center;
}

.format-options {
    display: grid;
    gap: 12px;
    margin-bottom: 20px;
}

.format-option {
    display: flex;
    align-items: center;
    padding: 15px;
    border: 2px solid #e1e8ed;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.format-option:hover {
    border-color: #3498db;
    background: #e3f2fd;
    transform: translateY(-2px);
}

.format-option.selected {
    border-color: #2980b9;
    background: #d4edda;
}

.format-radio {
    margin-right: 12px;
    transform: scale(1.2);
}

.format-info {
    flex: 1;
}

.format-name {
    font-weight: bold;
    color: #2c3e50;
    margin-bottom: 4px;
}

.format-description {
    font-size: 12px;
    color: #6c757d;
}

.format-icon {
    width: 32px;
    height: 32px;
    margin-left: 12px;
    opacity: 0.7;
    font-size: 24px;
}

.modal-buttons {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: bold;
    transition: all 0.3s ease;
}

.btn-primary {
    background: #3498db;
    color: white;
}

.btn-primary:hover {
    background: #2980b9;
}

.btn-secondary {
    background: #95a5a6;
    color: white;
}

.btn-secondary:hover {
    background: #7f8c8d;
}
</style>


    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion
                des Moissons</h1>
            <p class="text-slate-500 mt-1">Administration des moissons d'église -
                {{ \Carbon\Carbon::now()->locale('fr')->format('l d F Y') }}</p>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-seedling text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $moissons->total() }}</p>
                        <p class="text-sm text-slate-500">Total moissons</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $moissons->where('status', true)->count() }}</p>
                        <p class="text-sm text-slate-500">Actives</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-bullseye text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $moissons->where('objectif_atteint', true)->count() }}</p>
                        <p class="text-sm text-slate-500">Objectifs atteints</p>
                    </div>
                </div>
            </div>


            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-coins text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($totalCollecte ?? 0, 0, ',', ' ') }}</p>
                        <p class="text-sm text-slate-500">Total collecté (FCFA)</p>
                    </div>
                </div>
            </div>


            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-percentage text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($pourcentageMoyen ?? 0, 1) }}%</p>
                        <p class="text-sm text-slate-500">Performance moyenne</p>
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
                        @can('moissons.create')
                            <a href="{{ route('private.moissons.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i> Nouvelle Moisson
                            </a>
                        @endcan
                        @can('moissons.dashboard')
                            <a href="{{ route('private.moissons.dashboard') }}"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-chart-line mr-2"></i> Tableau de bord
                            </a>
                        @endcan
                        @can('moissons.export')
                            <button onclick="exporterMoissons()"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-download mr-2"></i> Exporter
                            </button>
                        @endcan






                    </div>
                </div>
            </div>












            @can('moissons.search')
                <div class="p-6">
                    <form method="GET" action="{{ route('private.moissons.index') }}"
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                            <div class="relative">
                                <input type="text" name="recherche" value="{{ request('recherche') }}"
                                    placeholder="Thème, passages bibliques..."
                                    class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <select name="status"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les statuts</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Progression</label>
                            <select name="statut_progression"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Toutes les progressions</option>
                                <option value="objectif atteint" {{ request('statut_progression') === 'objectif atteint' ? 'selected' : '' }}>Objectif atteint</option>
                                <option value="presque atteint" {{ request('statut_progression') === 'presque atteint' ? 'selected' : '' }}>Presque atteint</option>
                                <option value="bonne progression" {{ request('statut_progression') === 'bonne progression' ? 'selected' : '' }}>Bonne progression</option>
                                <option value="en cours" {{ request('statut_progression') === 'en cours' ? 'selected' : '' }}>En cours</option>
                                <option value="début" {{ request('statut_progression') === 'début' ? 'selected' : '' }}>Début</option>
                                <option value="très faible" {{ request('statut_progression') === 'très faible' ? 'selected' : '' }}>Très faible</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                            <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date fin</label>
                            <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>

                        <div class="lg:col-span-6 flex gap-2 pt-4">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-search mr-2"></i> Filtrer
                            </button>
                            <a href="{{ route('private.moissons.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                <i class="fas fa-refresh mr-2"></i> Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            @endcan
        </div>

        <!-- Liste des moissons -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-list text-purple-600 mr-2"></i>
                        Liste des Moissons ({{ $moissons->total() }})
                    </h2>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $moissons->total() }} résultats
                        </span>
                        @if ($moissons->hasPages())
                            <span class="text-sm text-slate-600">
                                Page {{ $moissons->currentPage() }} sur {{ $moissons->lastPage() }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if ($moissons->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['tri' => 'theme', 'ordre' => request('ordre') === 'asc' ? 'desc' : 'asc']) }}"
                                            class="group inline-flex items-center hover:text-blue-600 transition-colors">
                                            Moisson
                                            <span class="ml-2 flex-none rounded text-slate-400 group-hover:text-blue-500">
                                                <i class="fas fa-sort"></i>
                                            </span>
                                        </a>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['tri' => 'date', 'ordre' => request('ordre') === 'asc' ? 'desc' : 'asc']) }}"
                                            class="group inline-flex items-center hover:text-blue-600 transition-colors">
                                            Date
                                            <span class="ml-2 flex-none rounded text-slate-400 group-hover:text-blue-500">
                                                <i class="fas fa-sort"></i>
                                            </span>
                                        </a>
                                    </th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Objectifs</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Progression</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Composants</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($moissons as $moisson)
                                    <tr class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200">
                                        <td class="px-4 py-4">
                                            <div>
                                                <div class="text-sm font-bold text-slate-900">
                                                    {{ Str::limit($moisson->theme, 40) }}
                                                </div>
                                                @if($moisson->passages_bibliques)
                                                    <div class="text-xs text-slate-500 mt-1">
                                                        <i class="fas fa-book mr-1"></i>
                                                        {{ Str::limit(collect($moisson->passages_bibliques)->implode(', '), 30) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm text-slate-900">
                                                {{ $moisson->date->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                {{ $moisson->date->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="space-y-1">
                                                <div class="text-sm font-medium text-slate-900">
                                                    Cible: {{ number_format($moisson->cible, 0, ',', ' ') }} FCFA
                                                </div>
                                                <div class="text-sm text-slate-600">
                                                    Collecté: {{ number_format($moisson->montant_solde, 0, ',', ' ') }} FCFA
                                                </div>
                                                @if($moisson->montant_supplementaire > 0)
                                                    <div class="text-xs text-green-600">
                                                        Supplément: +{{ number_format($moisson->montant_supplementaire, 0, ',', ' ') }} FCFA
                                                    </div>
                                                @elseif($moisson->reste > 0)
                                                    <div class="text-xs text-orange-600">
                                                        Reste: {{ number_format($moisson->reste, 0, ',', ' ') }} FCFA
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="space-y-2">
                                                <div class="flex items-center space-x-2">
                                                    <div class="flex-1 bg-slate-200 rounded-full h-2">
                                                        <div class="h-2 rounded-full {{ $moisson->pourcentage_realise >= 100 ? 'bg-green-500' : ($moisson->pourcentage_realise >= 70 ? 'bg-blue-500' : ($moisson->pourcentage_realise >= 50 ? 'bg-yellow-500' : 'bg-red-500')) }}"
                                                             style="width: {{ min($moisson->pourcentage_realise, 100) }}%"></div>
                                                    </div>
                                                    <span class="text-xs font-medium text-slate-700">
                                                        {{ number_format($moisson->pourcentage_realise, 1) }}%
                                                    </span>
                                                </div>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($moisson->statut_progression === 'Objectif atteint') bg-green-100 text-green-800
                                                    @elseif($moisson->statut_progression === 'Bonne progression') bg-blue-100 text-blue-800
                                                    @elseif($moisson->statut_progression === 'En cours') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ $moisson->statut_progression }}
                                                </span>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
                                            <div class="flex">
                                                <!-- Colonne Passages -->
                                                <div class="flex flex-col items-center w-6 mr-1">
                                                    <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full">
                                                        {{ $moisson->nb_passages ?? 0 }}
                                                    </span>
                                                    <div class="text-xs text-slate-500 mt-1">
                                                        P
                                                    </div>
                                                </div>

                                                <!-- Colonne Ventes -->
                                                <div class="flex flex-col items-center w-6 mr-1">
                                                    <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                                        {{ $moisson->nb_ventes ?? 0 }}
                                                    </span>
                                                    <div class="text-xs text-slate-500 mt-1">
                                                        V
                                                    </div>
                                                </div>

                                                <!-- Colonne Engagements -->
                                                <div class="flex flex-col items-center w-6">
                                                    <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full">
                                                        {{ $moisson->nb_engagements ?? 0 }}
                                                    </span>
                                                    <div class="text-xs text-slate-500 mt-1">
                                                        E
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($moisson->status) bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                @if($moisson->status)
                                                    <i class="fas fa-check-circle mr-1"></i> Active
                                                @else
                                                    <i class="fas fa-pause-circle mr-1"></i> Inactive
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-end space-x-2">
                                                @can('moissons.read')
                                                    <a href="{{ route('private.moissons.show', $moisson) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                        title="Voir détails">
                                                        <i class="fas fa-eye text-sm"></i>
                                                    </a>
                                                @endcan
                                                @can('moissons.update')
                                                    <a href="{{ route('private.moissons.edit', $moisson) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                                                        title="Modifier">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                @endcan
                                                @can('moissons.recalculate')
                                                    <button onclick="recalculerTotaux('{{ $moisson->id }}')"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors"
                                                        title="Recalculer totaux">
                                                        <i class="fas fa-calculator text-sm"></i>
                                                    </button>
                                                @endcan
                                                @can('moissons.delete')
                                                    <button onclick="deleteMoisson('{{ $moisson->id }}')"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                                        title="Supprimer">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                @endcan
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
                            Affichage de <span class="font-medium">{{ $moissons->firstItem() }}</span> à <span
                                class="font-medium">{{ $moissons->lastItem() }}</span> sur <span
                                class="font-medium">{{ $moissons->total() }}</span> résultats
                        </div>
                        <div>
                            {{ $moissons->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-seedling text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune moisson trouvée</h3>
                        <p class="text-slate-500 mb-6">
                            @if (request()->hasAny(['recherche', 'status', 'statut_progression', 'date_debut', 'date_fin']))
                                Aucune moisson ne correspond à vos critères de recherche.
                            @else
                                Commencez par créer votre première moisson.
                            @endif
                        </p>
                        @can('moissons.create')
                            <a href="{{ route('private.moissons.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i> Créer une moisson
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>

                            <!-- Modal d'export avec Tailwind CSS -->
<div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-download text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Choisir le format d'export</h3>
            <p class="text-slate-500 text-sm mt-1">Sélectionnez le format qui vous convient</p>
        </div>

        <div class="space-y-3 mb-6">
            <label class="format-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-slate-50 transition-all duration-200" onclick="selectFormat('json')">
                <input type="radio" name="format" value="json" class="sr-only">
                <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-code text-orange-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-slate-800">JSON</div>
                    <div class="text-sm text-slate-500">Données structurées pour intégration</div>
                </div>
                <div class="w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center">
                    <div class="w-2.5 h-2.5 bg-blue-500 rounded-full opacity-0 transition-opacity"></div>
                </div>
            </label>

            <label class="format-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-slate-50 transition-all duration-200" onclick="selectFormat('excel')">
                <input type="radio" name="format" value="excel" class="sr-only">
                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-file-excel text-green-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-slate-800">Excel</div>
                    <div class="text-sm text-slate-500">Tableau de bord avec calculs</div>
                </div>
                <div class="w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center">
                    <div class="w-2.5 h-2.5 bg-blue-500 rounded-full opacity-0 transition-opacity"></div>
                </div>
            </label>

            <label class="format-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-blue-300 hover:bg-slate-50 transition-all duration-200" onclick="selectFormat('pdf')">
                <input type="radio" name="format" value="pdf" class="sr-only">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <div class="font-semibold text-slate-800">PDF</div>
                    <div class="text-sm text-slate-500">Rapport complet imprimable</div>
                </div>
                <div class="w-5 h-5 border-2 border-slate-300 rounded-full flex items-center justify-center">
                    <div class="w-2.5 h-2.5 bg-blue-500 rounded-full opacity-0 transition-opacity"></div>
                </div>
            </label>
        </div>

        <div class="flex gap-3">
            <button onclick="closeExportModal()" class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                Annuler
            </button>
            <button onclick="confirmExport()" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                Exporter
            </button>
        </div>
    </div>
</div>

    @push('scripts')
        <script>
            function deleteMoisson(moissonId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette moisson ?')) {
                    const url = "{{ route('private.moissons.destroy', ':moissonid') }}".replace(':moissonid', moissonId);

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

            function recalculerTotaux(moissonId) {
                if (confirm('Recalculer les totaux de cette moisson ?')) {
                    const url = "{{ route('private.moissons.recalculer-totaux', ':moissonid') }}".replace(':moissonid', moissonId);

                    fetch(url, {
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
                                alert(data.message || 'Erreur lors du recalcul');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Erreur lors du recalcul');
                        });
                }
            }

            // function exporterMoissons() {
            //     const format = prompt('Format d\'export (json/excel/pdf):', 'excel');
            //     if (format && ['json', 'excel', 'pdf'].includes(format.toLowerCase())) {
            //         window.location.href = `{{ route('private.moissons.export.liste') }}?format=${format.toLowerCase()}`;
            //     }
            // }

            let selectedFormat = '';

function exporterMoissons() {
    document.getElementById('exportModal').classList.remove('hidden');
    selectedFormat = '';
    document.querySelectorAll('input[name="format"]').forEach(radio => {
        radio.checked = false;
    });
    document.querySelectorAll('.format-option').forEach(option => {
        option.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
    });
}


function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

function selectFormat(format) {
    selectedFormat = format;
    document.querySelectorAll('.format-option').forEach(option => {
        option.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
    });
    event.currentTarget.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
    document.querySelector(`input[value="${format}"]`).checked = true;
}

function confirmExport() {
    if (selectedFormat) {
        window.location.href = `{{ route('private.moissons.export.liste') }}?format=${selectedFormat}`;
        closeExportModal();
    } else {
        alert('Veuillez sélectionner un format d\'export');
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

                document.getElementById('exportModal')?.addEventListener('click', function(event) {
        if (event.target === this) {
            closeExportModal();
        }
    });
            });
        </script>
    @endpush
@endsection
