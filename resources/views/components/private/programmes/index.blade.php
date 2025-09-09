@extends('layouts.private.main')
@section('title', 'Gestion des Programmes')

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion
                des Programmes</h1>
            <p class="text-slate-500 mt-1">Administration des programmes d'église -
                {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
        </div>

        <!-- Filtres et actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            @can(['programmes.planning', 'programmes.create', 'programmes.statistics'])
                <div class="p-6 border-b border-slate-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-filter text-blue-600 mr-2"></i>
                            Filtres et Actions
                        </h2>
                        <div class="flex flex-wrap gap-2">
                            @can('programmes.create')
                                <a href="{{ route('private.programmes.create') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-plus mr-2"></i> Nouveau Programme
                                </a>
                            @endcan
                            @can('programmes.planning')
                                <a href="{{ route('private.programmes.planning') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-calendar mr-2"></i> Planning
                                </a>
                            @endcan
                            @can('programmes.statistics')
                                <a href="{{ route('private.programmes.statistiques') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-chart-bar mr-2"></i> Statistiques
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            @endcan
            <div class="p-6">
                <form method="GET" action="{{ route('private.programmes.index') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nom, code ou description..."
                                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                        <select name="type"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les types</option>
                            @foreach (\App\Models\Programme::TYPES_PROGRAMME as $key => $label)
                                <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                        <select name="statut"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les statuts</option>
                            @foreach (\App\Models\Programme::STATUTS as $key => $label)
                                <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Audience</label>
                        <select name="audience"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Toutes les audiences</option>
                            @foreach (\App\Models\Programme::AUDIENCES as $key => $label)
                                <option value="{{ $key }}" {{ request('audience') == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tri</label>
                        <select name="sort"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date
                                création</option>
                            <option value="nom_programme" {{ request('sort') == 'nom_programme' ? 'selected' : '' }}>Nom
                            </option>
                            <option value="date_debut" {{ request('sort') == 'date_debut' ? 'selected' : '' }}>Date début
                            </option>
                            <option value="type_programme" {{ request('sort') == 'type_programme' ? 'selected' : '' }}>Type
                            </option>
                        </select>
                    </div>
                    <div class="lg:col-span-6 flex gap-2 pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i> Rechercher
                        </button>
                        <a href="{{ route('private.programmes.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-refresh mr-2"></i> Réinitialiser
                        </a>
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
                            <i class="fas fa-calendar-alt text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $programmes->total() }}</p>
                        <p class="text-sm text-slate-500">Total programmes</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-play text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $programmes->where('statut', 'actif')->count() }}
                        </p>
                        <p class="text-sm text-slate-500">Programmes actifs</p>
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
                            {{ $programmes->where('statut', 'planifie')->count() }}</p>
                        <p class="text-sm text-slate-500">En planification</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ $programmes->whereIn('frequence', ['quotidien', 'hebdomadaire'])->count() }}</p>
                        <p class="text-sm text-slate-500">Programmes réguliers</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des programmes -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-list text-purple-600 mr-2"></i>
                        Liste des Programmes ({{ $programmes->total() }})
                    </h2>
                    <div class="flex gap-2">
                        <button type="button" onclick="showSelectedActions()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-tasks mr-2"></i> Actions groupées
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                @if ($programmes->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="px-4 py-3 text-left">
                                        <input type="checkbox" id="selectAll"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Programme</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Type</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Fréquence</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Horaires</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Responsable</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Audience</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Statut</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach ($programmes as $programme)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-4">
                                            <input type="checkbox" name="selected_programmes[]"
                                                value="{{ $programme->id }}"
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 programme-checkbox">
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div>
                                                    <div class="font-semibold text-slate-900">
                                                        {{ $programme->nom_programme }}</div>
                                                    <div class="text-sm text-slate-500">
                                                        <code
                                                            class="px-2 py-1 text-xs bg-slate-100 text-slate-800 rounded">{{ $programme->code_programme }}</code>
                                                    </div>
                                                    @if ($programme->description)
                                                        <div class="text-sm text-slate-600 mt-1">
                                                            {{ Str::limit($programme->description, 50) }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ \App\Models\Programme::TYPES_PROGRAMME[$programme->type_programme] ?? $programme->type_programme }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ \App\Models\Programme::FREQUENCES[$programme->frequence] ?? $programme->frequence }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="text-sm">
                                                @if ($programme->heure_debut && $programme->heure_fin)
                                                    <div class="font-medium text-slate-900">{{ $programme->horaires }}
                                                    </div>
                                                @endif
                                                @if ($programme->jours_semaine)
                                                    <div class="text-slate-600">{{ $programme->jours_semaine_texte }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            @if ($programme->responsablePrincipal)
                                                <div class="text-sm">
                                                    <div class="font-medium text-slate-900">
                                                        {{ $programme->responsablePrincipal->prenom }}
                                                        {{ $programme->responsablePrincipal->nom }}</div>
                                                </div>
                                            @else
                                                <span class="text-slate-400">Non assigné</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                                {{ \App\Models\Programme::AUDIENCES[$programme->audience_cible] ?? $programme->audience_cible }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $programme->statut_badge }}-100 text-{{ $programme->statut_badge }}-800">
                                                <i class="fas fa-circle mr-1 text-xs"></i>
                                                {{ \App\Models\Programme::STATUTS[$programme->statut] ?? $programme->statut }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('private.programmes.show', $programme) }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                    title="Voir">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                                @can('programmes.update')
                                                    @if ($programme->peutEtreModifie())
                                                        <a href="{{ route('private.programmes.edit', $programme) }}"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                                                            title="Modifier">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>
                                                    @endif
                                                @endcan
                                                @can('programmes.activate')
                                                    @if ($programme->statut === 'planifie')
                                                        <button type="button"
                                                            onclick="changerStatut('{{ $programme->id }}', 'activer')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                                                            title="Activer">
                                                            <i class="fas fa-play text-sm"></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                                @can('programmes.suspend')
                                                    @if ($programme->statut === 'actif')
                                                        <button type="button"
                                                            onclick="changerStatut('{{ $programme->id }}', 'suspendre')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-orange-600 bg-orange-100 rounded-lg hover:bg-orange-200 transition-colors"
                                                            title="Suspendre">
                                                            <i class="fas fa-pause text-sm"></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                                @can('programmes.duplicate')
                                                    <button type="button"
                                                        onclick="dupliquerProgramme('{{ $programme->id }}')"
                                                        class="inline-flex items-center justify-center w-8 h-8 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                                                        title="Dupliquer">
                                                        <i class="fas fa-copy text-sm"></i>
                                                    </button>
                                                @endcan
                                                @can('programmes.delete')
                                                    @if ($programme->peutEtreModifie())
                                                        <button type="button"
                                                            onclick="supprimerProgramme('{{ $programme->id }}')"
                                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors"
                                                            title="Supprimer">
                                                            <i class="fas fa-trash text-sm"></i>
                                                        </button>
                                                    @endif
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
                            Affichage de <span class="font-medium">{{ $programmes->firstItem() }}</span> à <span
                                class="font-medium">{{ $programmes->lastItem() }}</span>
                            sur <span class="font-medium">{{ $programmes->total() }}</span> résultats
                        </div>
                        <div>
                            {{ $programmes->appends(request()->query())->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-alt text-3xl text-slate-400"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun programme trouvé</h3>
                        <p class="text-slate-500 mb-6">
                            @if (request()->hasAny(['search', 'type', 'statut', 'audience']))
                                Aucun programme ne correspond à vos critères de recherche.
                            @else
                                Commencez par créer votre premier programme.
                            @endif
                        </p>
                        @can('programmes.create')
                        <a href="{{ route('private.programmes.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Créer un programme
                        </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de confirmation -->
    <div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-question-circle text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900" id="modalTitle">Confirmer l'action</h3>
                </div>
                <p class="text-slate-600 mb-2" id="modalMessage">Êtes-vous sûr de vouloir effectuer cette action ?</p>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" id="confirmAction"
                    class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    Confirmer
                </button>
            </div>
        </div>
    </div>

    <script>
        // Sélection multiple
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.programme-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Modal functions
        function showModal(title, message, onConfirm) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalMessage').textContent = message;
            document.getElementById('confirmAction').onclick = onConfirm;
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.add('hidden');
        }

        // Changer le statut d'un programme
        function changerStatut(programmeId, action) {
            const actions = {
                'activer': {
                    title: 'Activer le programme',
                    message: 'Voulez-vous activer ce programme ?'
                },
                'suspendre': {
                    title: 'Suspendre le programme',
                    message: 'Voulez-vous suspendre ce programme ?'
                },
                'terminer': {
                    title: 'Terminer le programme',
                    message: 'Voulez-vous marquer ce programme comme terminé ?'
                },
                'annuler': {
                    title: 'Annuler le programme',
                    message: 'Voulez-vous annuler ce programme ?'
                }
            };

            const actionData = actions[action];
            showModal(actionData.title, actionData.message, function() {
                fetch(`{{ route('private.programmes.activer', ':programmeid') }}`.replace(':programmeid',
                        programmeId).replace('activer', action), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        closeModal();
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
            });
        }

        // Dupliquer un programme
        function dupliquerProgramme(programmeId) {
            showModal('Dupliquer le programme', 'Voulez-vous créer une copie de ce programme ?', function() {
                closeModal();

                fetch(`{{ route('private.programmes.dupliquer', ':programmeid') }}`.replace(':programmeid',
                        programmeId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        closeModal();
                        if (data.success) {

                            // location.reload();
                            window.location.href = `{{ route('private.programmes.edit', ':programmeid') }}`
                                .replace(':programmeid', data.data.id);
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
            });
        }

        // Supprimer un programme
        function supprimerProgramme(programmeId) {
            showModal('Supprimer le programme',
                'Cette action est irréversible. Voulez-vous vraiment supprimer ce programme ?',
                function() {
                    fetch(`{{ route('private.programmes.destroy', ':programmeid') }}`.replace(':programmeid',
                            programmeId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            closeModal();
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
                });
        }

        // Actions groupées
        function showSelectedActions() {
            const selected = Array.from(document.querySelectorAll('.programme-checkbox:checked'))
                .map(cb => cb.value);

            if (selected.length === 0) {
                alert('Veuillez sélectionner au moins un programme');
                return;
            }

            // Ici vous pouvez ajouter la logique pour les actions groupées
            console.log('Programmes sélectionnés:', selected);
        }

        // Close modal when clicking outside
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>

@endsection
