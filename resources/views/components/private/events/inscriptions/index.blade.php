@extends('layouts.private.main')
@section('title', 'Inscriptions - ' . $event->titre)

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Inscriptions</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.events.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Événements
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <a href="{{ route('private.events.show', $event) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                    {{ Str::limit($event->titre, 20) }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">Inscriptions</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <p class="text-slate-600 mt-1">{{ $event->sous_titre ?? 'Gestion des participants à l\'événement' }}</p>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @can('events.manage_inscriptions')
                    <button type="button" onclick="showAddInscriptionModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i> Ajouter une inscription
                    </button>
                @endcan

                <a href="{{ route('private.events.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à l'événement
                </a>

                @if($event->liste_attente)
                    <a href="{{ route('private.events.liste-attente', $event) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white text-sm font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-hourglass-half mr-2"></i> Liste d'attente
                    </a>
                @endif

                <a href="" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-chart-line mr-2"></i> Statistiques
                </a>
            </div>
        </div>
    </div>

    <!-- Informations sur l'événement -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Informations sur l'Événement
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-xl">
                    <div class="text-2xl font-bold text-blue-600">{{ $statistiques['total_inscriptions'] }}</div>
                    <div class="text-sm text-blue-800">Total inscriptions</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-xl">
                    <div class="text-2xl font-bold text-green-600">{{ $statistiques['inscriptions_actives'] }}</div>
                    <div class="text-sm text-green-800">Inscriptions actives</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-xl">
                    <div class="text-2xl font-bold text-red-600">{{ $statistiques['inscriptions_annulees'] }}</div>
                    <div class="text-sm text-red-800">Inscriptions annulées</div>
                </div>
                @if($event->capacite_totale)
                    <div class="text-center p-4 bg-purple-50 rounded-xl">
                        <div class="text-2xl font-bold text-purple-600">{{ $statistiques['places_restantes'] }}</div>
                        <div class="text-sm text-purple-800">Places restantes</div>
                    </div>
                @endif
            </div>

            @if($event->capacite_totale)
                <div class="mt-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">Taux de remplissage</span>
                        <span class="text-sm text-slate-500">{{ $statistiques['taux_remplissage'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-3 rounded-full" style="width: {{ $statistiques['taux_remplissage'] }}%"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-filter text-purple-600 mr-2"></i>
                Filtres et Recherche
            </h2>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.events.inscriptions', $event) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, téléphone..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('statut') == 'active' ? 'selected' : '' }}>Actives</option>
                        <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulées</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date inscription</label>
                    <input type="date" name="date_inscription" value="{{ request('date_inscription') }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des inscriptions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-users text-green-600 mr-2"></i>
                    Liste des Inscriptions ({{ $inscriptions->total() }})
                </h2>
                <div class="flex items-center space-x-2">
                    <select id="bulkAction" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="">Actions en lot</option>
                        <option value="export">Exporter sélection</option>
                        <option value="cancel">Annuler sélection</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($inscriptions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Participant</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Contact</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Inscription</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($inscriptions as $inscription)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <input type="checkbox" name="selected_inscriptions[]" value="{{ $inscription->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 inscription-checkbox">
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold">{{ substr($inscription->inscrit->prenom, 0, 1) }}{{ substr($inscription->inscrit->nom, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-900">{{ $inscription->inscrit->prenom }} {{ $inscription->inscrit->nom }}</div>
                                                @if($inscription->inscrit->date_naissance)
                                                    <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($inscription->inscrit->date_naissance)->age }} ans</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm">
                                            <div class="text-slate-900">{{ $inscription->inscrit->email }}</div>
                                            @if($inscription->inscrit->telephone_1)
                                                <div class="text-slate-500">{{ $inscription->inscrit->telephone_1 }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm">
                                            <div class="font-medium text-slate-900">{{ $inscription->date_inscription->format('d/m/Y H:i') }}</div>
                                            @if($inscription->createur)
                                                <div class="text-slate-500">
                                                    @if($inscription->est_auto_inscription)
                                                        Auto-inscription
                                                    @else
                                                        Par {{ $inscription->createur->prenom }} {{ $inscription->createur->nom }}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($inscription->est_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Active
                                            </span>
                                        @elseif($inscription->est_annulee)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i> Annulée
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <i class="fas fa-trash mr-1"></i> Supprimée
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            @can('events.manage_inscriptions')
                                                @if($inscription->est_active)
                                                    <button type="button" onclick="cancelInscription('{{ $inscription->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Annuler">
                                                        <i class="fas fa-times text-sm"></i>
                                                    </button>
                                                @elseif($inscription->est_annulee && $inscription->peutEtreReactivee())
                                                    <button type="button" onclick="reactivateInscription('{{ $inscription->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Réactiver">
                                                        <i class="fas fa-redo text-sm"></i>
                                                    </button>
                                                @endif

                                                <button type="button" onclick="editInscription('{{ $inscription->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Modifier">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </button>

                                                <button type="button" onclick="deleteInscription('{{ $inscription->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" title="Supprimer">
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
                        Affichage de <span class="font-medium">{{ $inscriptions->firstItem() }}</span> à <span class="font-medium">{{ $inscriptions->lastItem() }}</span>
                        sur <span class="font-medium">{{ $inscriptions->total() }}</span> résultats
                    </div>
                    <div>
                        {{ $inscriptions->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-slash text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune inscription</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['search', 'statut', 'date_inscription']))
                            Aucune inscription ne correspond à vos critères de recherche.
                        @else
                            Aucune inscription n'a encore été enregistrée pour cet événement.
                        @endif
                    </p>
                    @can('events.manage_inscriptions')
                        <button type="button" onclick="showAddInscriptionModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-user-plus mr-2"></i> Ajouter une inscription
                        </button>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal ajout inscription -->
<div id="addInscriptionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-user-plus text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Ajouter une inscription</h3>
            </div>
            <form id="addInscriptionForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Participant</label>
                    <select id="inscrit_id" name="inscrit_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Sélectionner un participant</option>
                        {{-- @foreach($users as $user)
                            <optgroup label="Culte {{ $culte->date_culte->format('d/m/Y') }}">
                                @foreach($culte->participants as $participant)
                                    <option value="{{ $participant->id }}">{{ $participant->prenom }} {{ $participant->nom }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach --}}
                        @foreach($users as $user)

                                    <option value="{{ $user->id }}">{{ $user->prenom }} {{ $user->nom }}</option>

                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeAddInscriptionModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" id="confirmAddInscription" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Ajouter
            </button>
        </div>
    </div>
</div>

<script>
let currentInscriptionId = null;

// Modal functions
function showAddInscriptionModal() {
    document.getElementById('addInscriptionModal').classList.remove('hidden');
}

function closeAddInscriptionModal() {
    document.getElementById('addInscriptionModal').classList.add('hidden');
    document.getElementById('addInscriptionForm').reset();
}

// Sélection multiple
const selectAll = document.getElementById('selectAll');
if(selectAll) {
    selectAll.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.inscription-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
}

// Ajouter inscription
document.getElementById('confirmAddInscription').addEventListener('click', function() {
    const inscritId = document.getElementById('inscrit_id').value;

    if (!inscritId) {
        alert('Veuillez sélectionner un participant');
        return;
    }

    fetch('{{ route("private.events.inscriptions.ajouter", $event) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            inscrit_id: inscritId
        })
    })
    .then(response => response.json())
    .then(data => {
        closeAddInscriptionModal();
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

// Annuler inscription
function cancelInscription(inscriptionId) {
    if (!confirm('Êtes-vous sûr de vouloir annuler cette inscription ?')) return;

    fetch(`{{ route('private.events.inscriptions', $event) }}/${inscriptionId}/cancel`, {
        method: 'POST',
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

// Réactiver inscription
function reactivateInscription(inscriptionId) {
    if (!confirm('Êtes-vous sûr de vouloir réactiver cette inscription ?')) return;

    fetch(`{{ route('private.events.inscriptions', $event) }}/${inscriptionId}/reactivate`, {
        method: 'POST',
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

// Supprimer inscription
function deleteInscription(inscriptionId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer définitivement cette inscription ?')) return;

    fetch(`{{ route('private.events.inscriptions', $event) }}/${inscriptionId}`, {
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

// Modifier inscription
function editInscription(inscriptionId) {
    // Redirection vers page de modification
    alert('Fonctionnalité de modification à implémenter');
}

// Fermer modal en cliquant à l'extérieur
document.getElementById('addInscriptionModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddInscriptionModal();
});
</script>

@endsection
