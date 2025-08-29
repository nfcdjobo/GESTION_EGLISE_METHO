@extends('layouts.private.main')
@section('title', 'Gestion des Participations aux Cultes')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Participations</h1>
        <p class="text-slate-500 mt-1">Suivi des présences et participations aux cultes - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
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
                    <a href="{{ route('private.participantscultes.nouveaux-visiteurs') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i> Nouveaux Visiteurs
                    </a>
                    <a href="{{ route('private.participantscultes.statistiques') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.participantscultes.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Participant</label>
                    <div class="relative">
                        <input type="text" name="participant_search" value="{{ request('participant_search') }}" placeholder="Nom, prénom, email..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Culte</label>
                    <select name="culte_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les cultes</option>
                        @foreach ($cultes as $key => $culte)
                            <option value="{{$culte->id}}">{{$culte->titre. ' ('.$culte->date_culte->format("Y-h-d").')'}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut de présence</label>
                    <select name="statut_presence" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="present" {{ request('statut_presence') == 'present' ? 'selected' : '' }}>Présent</option>
                        <option value="present_partiel" {{ request('statut_presence') == 'present_partiel' ? 'selected' : '' }}>Présent Partiel</option>
                        <option value="en_retard" {{ request('statut_presence') == 'en_retard' ? 'selected' : '' }}>En Retard</option>
                        <option value="parti_tot" {{ request('statut_presence') == 'parti_tot' ? 'selected' : '' }}>Parti Tôt</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de participation</label>
                    <select name="type_participation" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <option value="physique" {{ request('type_participation') == 'physique' ? 'selected' : '' }}>Physique</option>
                        <option value="en_ligne" {{ request('type_participation') == 'en_ligne' ? 'selected' : '' }}>En Ligne</option>
                        <option value="hybride" {{ request('type_participation') == 'hybride' ? 'selected' : '' }}>Hybride</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Rôle</label>
                    <select name="role_culte" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les rôles</option>
                        <option value="participant" {{ request('role_culte') == 'participant' ? 'selected' : '' }}>Participant</option>
                        <option value="equipe_technique" {{ request('role_culte') == 'equipe_technique' ? 'selected' : '' }}>Équipe Technique</option>
                        <option value="equipe_louange" {{ request('role_culte') == 'equipe_louange' ? 'selected' : '' }}>Équipe Louange</option>
                        <option value="equipe_accueil" {{ request('role_culte') == 'equipe_accueil' ? 'selected' : '' }}>Équipe Accueil</option>
                        <option value="orateur" {{ request('role_culte') == 'orateur' ? 'selected' : '' }}>Orateur</option>
                        <option value="dirigeant" {{ request('role_culte') == 'dirigeant' ? 'selected' : '' }}>Dirigeant</option>
                        <option value="nouveau_visiteur" {{ request('role_culte') == 'nouveau_visiteur' ? 'selected' : '' }}>Nouveau Visiteur</option>
                    </select>
                </div>
                <div class="lg:col-span-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                        <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date fin</label>
                        <input type="date" name="date_fin" value="{{ request('date_fin') }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div class="flex items-end">
                        <div class="w-full space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="premiere_visite" value="1" {{ request('premiere_visite') ? 'checked' : '' }} id="premiere_visite" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="premiere_visite" class="ml-2 text-sm text-slate-700">Première visite</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="necessite_suivi" value="1" {{ request('necessite_suivi') ? 'checked' : '' }} id="necessite_suivi" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="necessite_suivi" class="ml-2 text-sm text-slate-700">Nécessite suivi</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="{{ route('private.participantscultes.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
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
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $participations->total() ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Total participations</p>
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
                    <p class="text-2xl font-bold text-slate-800">{{ $participations->where('statut_presence', 'present')->count() ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Présents complets</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $participations->where('premiere_visite', true)->count() ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Nouvelles visites</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-hand-holding-heart text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $participations->where('demande_contact_pastoral', true)->count() ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Demandes suivi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des participations -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Participations ({{ $participations->total() ?? 0 }})
                </h2>
                <div class="flex items-center space-x-2">
                    <select id="sortBy" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date d'ajout</option>
                        <option value="date_culte" {{ request('sort_by') == 'date_culte' ? 'selected' : '' }}>Date du culte</option>
                        <option value="participant" {{ request('sort_by') == 'participant' ? 'selected' : '' }}>Participant</option>
                        <option value="statut_presence" {{ request('sort_by') == 'statut_presence' ? 'selected' : '' }}>Statut</option>
                    </select>
                    <select id="sortOrder" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($participations && $participations->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($participations as $participation)
                        <div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $participation->participant->nom ?? 'N/A' }} {{ $participation->participant->prenom ?? '' }}</h3>
                                    <p class="text-sm text-slate-600">{{ $participation->culte->titre ?? 'Culte supprimé' }}</p>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    @php
                                        $statutColors = [
                                            'present' => 'bg-green-100 text-green-800',
                                            'present_partiel' => 'bg-yellow-100 text-yellow-800',
                                            'en_retard' => 'bg-orange-100 text-orange-800',
                                            'parti_tot' => 'bg-red-100 text-red-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statutColors[$participation->statut_presence] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $participation->statut_presence_libelle }}
                                    </span>
                                    @if($participation->premiere_visite)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-star mr-1"></i> Nouvelle visite
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Détails -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                    <span>{{ $participation->culte ? \Carbon\Carbon::parse($participation->culte->date_culte)->format('d/m/Y') : 'N/A' }}</span>
                                    @if($participation->heure_arrivee)
                                        <i class="fas fa-clock w-4 ml-4 mr-2"></i>
                                        <span>{{ \Carbon\Carbon::parse($participation->heure_arrivee)->format('H:i') }}</span>
                                    @endif
                                </div>

                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-user-tag w-4 mr-2"></i>
                                    <span>{{ $participation->role_culte_libelle }}</span>
                                </div>

                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-laptop w-4 mr-2"></i>
                                    <span>{{ $participation->type_participation_libelle }}</span>
                                </div>

                                @if($participation->accompagnateur)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-user-friends w-4 mr-2"></i>
                                        <span>Accompagné par {{ $participation->accompagnateur->nom }} {{ $participation->accompagnateur->prenom }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Badges de suivi -->
                            @if($participation->demande_contact_pastoral || $participation->interesse_bapteme || $participation->souhaite_devenir_membre)
                                <div class="flex flex-wrap gap-1 mb-4">
                                    @if($participation->demande_contact_pastoral)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-praying-hands mr-1"></i> Contact pastoral
                                        </span>
                                    @endif
                                    @if($participation->interesse_bapteme)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-cyan-100 text-cyan-800">
                                            <i class="fas fa-water mr-1"></i> Baptême
                                        </span>
                                    @endif
                                    @if($participation->souhaite_devenir_membre)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-heart mr-1"></i> Membre
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('private.participantscultes.show', [$participation->participant_id, $participation->culte_id]) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>

                                    <button type="button" onclick="editParticipation('{{ $participation->participant_id }}', '{{ $participation->culte_id }}')" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>

                                    @if(!$participation->presence_confirmee)
                                        <button type="button" onclick="confirmerPresence('{{ $participation->participant_id }}', '{{ $participation->culte_id }}')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Confirmer">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                    @endif
                                </div>

                                <button type="button" onclick="deleteParticipation('{{ $participation->participant_id }}', '{{ $participation->culte_id }}')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium">{{ $participations->firstItem() }}</span> à <span class="font-medium">{{ $participations->lastItem() }}</span>
                        sur <span class="font-medium">{{ $participations->total() }}</span> résultats
                    </div>
                    <div>
                        {{ $participations->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune participation trouvée</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['participant_search', 'culte_id', 'statut_presence']))
                            Aucune participation ne correspond à vos critères de recherche.
                        @else
                            Aucune participation enregistrée pour le moment.
                        @endif
                    </p>
                </div>
            @endif
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

// Confirmer présence
function confirmerPresence(participantId, culteId) {
    if (confirm('Confirmer la présence de ce participant ?')) {
        fetch(`{{route('private.participantscultes.confirmer-presence', [':participant', ':culte'])}}`.replace(':participant', participantId).replace(':culte', culteId), {
            method: 'PATCH',
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

// Supprimer participation
function deleteParticipation(participantId, culteId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette participation ?')) {
        fetch(`{{route('private.participantscultes.destroy', [':participant', ':culte'])}}`.replace(':participant', participantId).replace(':culte', culteId), {
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

function editParticipation(participantId, culteId) {
    // Redirection vers la page de modification ou ouverture d'un modal
    window.location.href = `/private/participants-cultes/${participantId}/${culteId}`;
}
</script>
@endpush
@endsection
