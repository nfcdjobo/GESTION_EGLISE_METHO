@extends('layouts.private.main')
@section('title', 'Gestion des Interventions')

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Gestion des Interventions</h1>
                <p class="text-slate-500 mt-1">Planification et suivi des interventions -
                    {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
            </div>
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
                        @can('interventions.create')
                            <a href="{{ route('private.interventions.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i> Nouvelle Intervention
                            </a>
                        @endcan
                        @can('interventions.trash')
                            <a href="{{ route('private.interventions.trash') }}"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-600 to-slate-600 text-white text-sm font-medium rounded-xl hover:from-gray-700 hover:to-slate-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-trash mr-2"></i> Corbeille
                            </a>
                        @endcan
                        @can('interventions.by-event')
                            <a href="{{ route('private.interventions.par-evenement') }}"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-calendar mr-2"></i> Par Événement
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('private.interventions.index') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ $currentFilters['search'] ?? '' }}"
                                placeholder="Titre, description, passage biblique..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Culte</label>
                        <select name="culte_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les cultes</option>
                            @foreach ($cultes as $culte)
                                <option value="{{ $culte->id }}"
                                    {{ ($currentFilters['culte_id'] ?? '') == $culte->id ? 'selected' : '' }}>
                                    {{ $culte->titre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Réunion</label>
                        <select name="reunion_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Toutes les réunions</option>
                            @foreach ($reunions as $reunion)
                                <option value="{{ $reunion->id }}"
                                    {{ ($currentFilters['reunion_id'] ?? '') == $reunion->id ? 'selected' : '' }}>
                                    {{ $reunion->titre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Intervenant</label>
                        <select name="intervenant_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les intervenants</option>
                            @foreach ($intervenants as $intervenant)
                                <option value="{{ $intervenant->id }}"
                                    {{ ($currentFilters['intervenant_id'] ?? '') == $intervenant->id ? 'selected' : '' }}>
                                    {{ $intervenant->nom . ' ' . $intervenant->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                        <select name="type_intervention"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les types</option>
                            @foreach ($filters['types_intervention'] as $key => $label)
                                <option value="{{ $key }}"
                                    {{ ($currentFilters['type_intervention'] ?? '') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:col-span-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <select name="statut"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les statuts</option>
                                @foreach ($filters['statuts'] as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ ($currentFilters['statut'] ?? '') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center pt-6">
                            <input type="checkbox" name="ordre_passage" value="1"
                                {{ request('ordre_passage') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label class="ml-2 text-sm text-slate-700">Trier par ordre de passage</label>
                        </div>
                        <div class="md:col-span-2 flex gap-2 pt-6">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-search mr-2"></i> Rechercher
                            </button>
                            <a href="{{ route('private.interventions.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                <i class="fas fa-refresh mr-2"></i> Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-microphone text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $interventions->total() }}</p>
                        <p class="text-sm text-slate-500">Total interventions</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ $interventions->where('statut', 'terminee')->count() }}</p>
                        <p class="text-sm text-slate-500">Terminées</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ $interventions->where('statut', 'prevue')->count() }}</p>
                        <p class="text-sm text-slate-500">Prévues</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-times-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ $interventions->where('statut', 'annulee')->count() }}</p>
                        <p class="text-sm text-slate-500">Annulées</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des interventions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-list text-purple-600 mr-2"></i>
                        Liste des Interventions ({{ $interventions->total() }})
                    </h2>
                </div>
            </div>
            <div class="p-6">
                @if ($interventions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Titre</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Type</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Intervenant</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Événement</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Timing</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Statut</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($interventions as $intervention)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-4">
                                            <div>
                                                <div class="font-semibold text-slate-900">{{ $intervention->titre }}</div>
                                                @if ($intervention->description)
                                                    <div class="text-sm text-slate-500">
                                                        {{ Str::limit($intervention->description, 50) }}</div>
                                                @endif
                                                @if ($intervention->passage_biblique)
                                                    <div class="text-xs text-blue-600 font-medium">
                                                        {{ $intervention->passage_biblique }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $intervention->type_intervention_label }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center space-x-2">
                                                <div
                                                    class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                    {{ strtoupper(substr($intervention->intervenant->nom, 0, 1)) }}
                                                </div>
                                                <span
                                                    class="text-sm font-medium text-slate-900">{{ $intervention->intervenant->nom }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            @if ($intervention->culte)
                                                <div class="flex items-center text-sm">
                                                    <i class="fas fa-church text-blue-500 mr-2"></i>
                                                    {{ $intervention->culte->titre }}
                                                </div>
                                            @elseif($intervention->reunion)
                                                <div class="flex items-center text-sm">
                                                    <i class="fas fa-users text-green-500 mr-2"></i>
                                                    {{ $intervention->reunion->titre }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm">
                                                @if ($intervention->ordre_passage)
                                                    <div class="flex items-center mb-1">
                                                        <i class="fas fa-sort-numeric-up text-gray-400 mr-1"></i>
                                                        <span
                                                            class="font-medium">{{ $intervention->ordre_passage }}</span>
                                                    </div>
                                                @endif
                                                @if ($intervention->heure_debut)
                                                    <div class="flex items-center mb-1">
                                                        <i class="fas fa-clock text-blue-400 mr-1"></i>
                                                        {{ $intervention->heure_debut->format('H:i') }}
                                                    </div>
                                                @endif
                                                <div class="flex items-center text-gray-500">
                                                    <i class="fas fa-stopwatch text-gray-400 mr-1"></i>
                                                    {{ $intervention->duree_minutes }}min
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            @if ($intervention->statut == 'prevue')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i> {{ $intervention->statut_label }}
                                                </span>
                                            @elseif($intervention->statut == 'terminee')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i> {{ $intervention->statut_label }}
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times mr-1"></i> {{ $intervention->statut_label }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('private.interventions.show', $intervention) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                    title="Voir">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                                @can('interventions.update')
                                                    <a href="{{ route('private.interventions.edit', $intervention) }}"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                                                        title="Modifier">
                                                        <i class="fas fa-edit text-sm"></i>
                                                    </a>
                                                @endcan
                                                @can('interventions.change-status')
                                                    <form method="POST"
                                                        action="{{ route('private.interventions.change-statut', $intervention) }}"
                                                        class="inline-block">
                                                        @csrf
                                                        @method('PATCH')
                                                        <select name="statut" onchange="this.form.submit()"
                                                            class="text-xs bg-blue-100 text-blue-800 border border-blue-200 rounded-lg px-2 py-1">
                                                            @foreach ($filters['statuts'] as $key => $label)
                                                                <option value="{{ $key }}"
                                                                    {{ $intervention->statut == $key ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </form>
                                                @endcan
                                                @can('interventions.delete')
                                                    <button type="button"
                                                        onclick="deleteIntervention('{{ $intervention->id }}')"
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
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                        <div class="text-sm text-slate-700">
                            Affichage de <span class="font-medium">{{ $interventions->firstItem() }}</span> à <span
                                class="font-medium">{{ $interventions->lastItem() }}</span>
                            sur <span class="font-medium">{{ $interventions->total() }}</span> résultats
                        </div>
                        <div>
                            {{ $interventions->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-microphone text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune intervention trouvée</h3>
                        <p class="text-slate-500 mb-6">
                            @if (request()->hasAny(['search', 'culte_id', 'reunion_id', 'type_intervention', 'statut']))
                                Aucune intervention ne correspond à vos critères de recherche.
                            @else
                                Commencez par créer votre première intervention.
                            @endif
                        </p>
                        @can('interventions.create')
                            <a href="{{ route('private.interventions.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i> Créer une intervention
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
    @can('interventions.delete')
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3>
                    </div>
                    <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer cette intervention ?</p>
                    <p class="text-red-600 font-medium">Cette action peut être annulée depuis la corbeille.</p>
                </div>
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Annuler
                    </button>
                    <button type="button" id="confirmDelete"
                        class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endcan

    <script>
        // Modal functions
        function showDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Suppression d'une intervention
        function deleteIntervention(interventionId) {
            showDeleteModal();
            document.getElementById('confirmDelete').onclick = function() {
                fetch(`{{ route('private.interventions.index') }}/${interventionId}`, {
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
                            location.reload();
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
