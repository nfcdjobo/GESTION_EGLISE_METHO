@extends('layouts.private.main')
@section('title', 'Nouveaux Visiteurs - Suivi Pastoral')

@section('content')
<div class="space-y-8">

    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Nouveaux Visiteurs</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.participantscultes.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-users-cog mr-2"></i>
                        Participants
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-users text-slate-500">Suivi pastoral des nouveaux visiteurs et personnes nécessitant un accompagnement - {{ \Carbon\Carbon::now()->format('l d F Y') }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Filtres -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Options
                </h2>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('private.participantscultes.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-list mr-2"></i> Toutes les participations
                    </a>
                    <a href="{{ route('private.participantscultes.statistiques') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.participantscultes.nouveaux-visiteurs') }}" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Période</label>
                    <select name="jours" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="7" {{ request('jours') == '7' ? 'selected' : '' }}>7 derniers jours</option>
                        <option value="30" {{ request('jours', 30) == '30' ? 'selected' : '' }}>30 derniers jours</option>
                        <option value="60" {{ request('jours') == '60' ? 'selected' : '' }}>60 derniers jours</option>
                        <option value="90" {{ request('jours') == '90' ? 'selected' : '' }}>90 derniers jours</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de suivi</label>
                    <select name="type_suivi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="premiere_visite" {{ request('type_suivi') == 'premiere_visite' ? 'selected' : '' }}>Première visite uniquement</option>
                        <option value="contact_pastoral" {{ request('type_suivi') == 'contact_pastoral' ? 'selected' : '' }}>Contact pastoral demandé</option>
                        <option value="bapteme" {{ request('type_suivi') == 'bapteme' ? 'selected' : '' }}>Intéressé baptême</option>
                        <option value="membre" {{ request('type_suivi') == 'membre' ? 'selected' : '' }}>Souhaite devenir membre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut suivi</label>
                    <select name="statut_suivi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="non_contacte" {{ request('statut_suivi') == 'non_contacte' ? 'selected' : '' }}>Non contacté</option>
                        <option value="contacte" {{ request('statut_suivi') == 'contacte' ? 'selected' : '' }}>Contacté</option>
                        <option value="suivi_en_cours" {{ request('statut_suivi') == 'suivi_en_cours' ? 'selected' : '' }}>Suivi en cours</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Accompagnateur</label>
                    <select name="accompagnateur_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="avec" {{ request('accompagnateur_id') == 'avec' ? 'selected' : '' }}>Avec accompagnateur</option>
                        <option value="sans" {{ request('accompagnateur_id') == 'sans' ? 'selected' : '' }}>Sans accompagnateur</option>
                        <!-- Options spécifiques seront remplies par le contrôleur -->
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-star text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $visiteurs->count() ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Nouveaux visiteurs</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-praying-hands text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $visiteurs->where('demande_contact_pastoral', true)->count() ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Demandes contact</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-water text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $visiteurs->where('interesse_bapteme', true)->count() ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Intérêt baptême</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-heart text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $visiteurs->where('souhaite_devenir_membre', true)->count() ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Futurs membres</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions en masse -->
    @can('participants-cultes.update')
    @if($visiteurs && $visiteurs->count() > 0)
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-tasks text-green-600 mr-2"></i>
                    Actions en Masse
                </h2>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-3">
                    <button type="button" onclick="exportVisiteurs()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                        <i class="fas fa-download mr-2"></i> Exporter la liste
                    </button>
                    <button type="button" onclick="marquerContactes()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200">
                        <i class="fas fa-check mr-2"></i> Marquer comme contactés
                    </button>
                    <button type="button" onclick="assignerResponsable()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                        <i class="fas fa-user-plus mr-2"></i> Assigner responsable
                    </button>
                </div>
            </div>
        </div>
    @endif
    @endcan

    <!-- Liste des nouveaux visiteurs -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Nouveaux Visiteurs ({{ $visiteurs->count() ?? 0 }})
                </h2>
                <div class="flex items-center space-x-2">
                    <label class="flex items-center text-sm">
                        <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mr-2">
                        Tout sélectionner
                    </label>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($visiteurs && $visiteurs->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($visiteurs as $visiteur)
                        <div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <!-- Header avec checkbox -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start space-x-3">
                                    <input type="checkbox" name="visiteurs[]" value="{{ $visiteur->participant_id }}" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mt-1">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-slate-900 mb-1">
                                            {{ $visiteur->participant->nom ?? 'N/A' }} {{ $visiteur->participant->prenom ?? '' }}
                                        </h3>
                                        <p class="text-sm text-slate-600">{{ $visiteur->culte->titre ?? 'Culte supprimé' }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-star mr-1"></i> Nouvelle visite
                                    </span>
                                    @if($visiteur->statut_presence !== 'present')
                                        @php
                                            $statutColors = [
                                                'present_partiel' => 'bg-yellow-100 text-yellow-800',
                                                'en_retard' => 'bg-orange-100 text-orange-800',
                                                'parti_tot' => 'bg-red-100 text-red-800'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statutColors[$visiteur->statut_presence] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $visiteur->statut_presence_libelle }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Informations de contact -->
                            <div class="space-y-3 mb-4">
                                @if($visiteur->participant->email)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-envelope w-4 mr-2"></i>
                                        <a href="mailto:{{ $visiteur->participant->email }}" class="text-blue-600 hover:underline">{{ $visiteur->participant->email }}</a>
                                    </div>
                                @endif

                                @if($visiteur->participant->telephone_1)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-phone w-4 mr-2"></i>
                                        <a href="tel:{{ $visiteur->participant->telephone_1 }}" class="text-blue-600 hover:underline">{{ $visiteur->participant->telephone_1 }}</a>
                                    </div>
                                @endif

                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                    <span>{{ $visiteur->culte ? \Carbon\Carbon::parse($visiteur->culte->date_culte)->format('d/m/Y') : 'N/A' }}</span>
                                </div>

                                @if($visiteur->accompagnateur)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-user-friends w-4 mr-2"></i>
                                        <span>Accompagné par {{ $visiteur->accompagnateur->nom }} {{ $visiteur->accompagnateur->prenom }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Besoins de suivi -->
                            @if($visiteur->demande_contact_pastoral || $visiteur->interesse_bapteme || $visiteur->souhaite_devenir_membre)
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-slate-700 mb-2">Besoins identifiés :</h4>
                                    <div class="flex flex-wrap gap-1">
                                        @if($visiteur->demande_contact_pastoral)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-praying-hands mr-1"></i> Contact pastoral
                                            </span>
                                        @endif
                                        @if($visiteur->interesse_bapteme)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-cyan-100 text-cyan-800">
                                                <i class="fas fa-water mr-1"></i> Baptême
                                            </span>
                                        @endif
                                        @if($visiteur->souhaite_devenir_membre)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-heart mr-1"></i> Devenir membre
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Commentaires -->
                            @if($visiteur->commentaires_participant)
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold text-slate-700 mb-2">Commentaires :</h4>
                                    <div class="bg-slate-50 p-3 rounded-lg">
                                        <p class="text-sm text-slate-600">{{ $visiteur->commentaires_participant }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('private.participantscultes.show', [$visiteur->participant_id, $visiteur->culte_id]) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir détails">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>

                                    @if($visiteur->participant->email)
                                        <a href="mailto:{{ $visiteur->participant->email }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Envoyer email">
                                            <i class="fas fa-envelope text-sm"></i>
                                        </a>
                                    @endif

                                    @if($visiteur->participant->telephone_1)
                                        <a href="tel:{{ $visiteur->participant->telephone_1 }}" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Appeler">
                                            <i class="fas fa-phone text-sm"></i>
                                        </a>
                                    @endif

                                    <button type="button" onclick="ajouterNote('{{ $visiteur->participant_id }}', '{{ $visiteur->culte_id }}')" class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors" title="Ajouter note">
                                        <i class="fas fa-sticky-note text-sm"></i>
                                    </button>
                                </div>

                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-slate-500">
                                        {{ $visiteur->created_at ? $visiteur->created_at->diffForHumans() : 'Date inconnue' }}
                                    </span>
                                    @if($visiteur->urgence ?? false)
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Urgent
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination si nécessaire -->
                @if(method_exists($visiteurs, 'links'))
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                        <div class="text-sm text-slate-700">
                            Affichage de <span class="font-medium">{{ $visiteurs->firstItem() }}</span> à <span class="font-medium">{{ $visiteurs->lastItem() }}</span>
                            sur <span class="font-medium">{{ $visiteurs->total() }}</span> résultats
                        </div>
                        <div>
                            {{ $visiteurs->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-plus text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun nouveau visiteur</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['jours', 'type_suivi', 'statut_suivi']))
                            Aucun nouveau visiteur ne correspond à vos critères de recherche.
                        @else
                            Aucun nouveau visiteur enregistré pour la période sélectionnée.
                        @endif
                    </p>
                    <a href="{{ route('private.participantscultes.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-list mr-2"></i> Voir toutes les participations
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour ajouter une note -->
<div id="noteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Ajouter une note de suivi</h3>
            <form id="noteForm">
                @csrf
                <input type="hidden" id="note_participant_id" name="participant_id">
                <input type="hidden" id="note_culte_id" name="culte_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de note</label>
                    <select name="type_note" id="type_note" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="contact">Contact effectué</option>
                        <option value="suivi">Note de suivi</option>
                        <option value="urgence">Note urgente</option>
                        <option value="information">Information générale</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Note</label>
                    <textarea name="contenu_note" id="contenu_note" rows="4" required
                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                        placeholder="Détails du contact ou informations importantes..."></textarea>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeNoteModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="saveNote()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Enregistrer
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Gestion de la sélection
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="visiteurs[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Fonctions d'action
function exportVisiteurs() {
    const selected = getSelectedVisiteurs();
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un visiteur');
        return;
    }
    // Logique d'export
    console.log('Export des visiteurs:', selected);
    alert('Fonctionnalité d\'export à implémenter');
}

function marquerContactes() {
    const selected = getSelectedVisiteurs();
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un visiteur');
        return;
    }
    if (confirm(`Marquer ${selected.length} visiteur(s) comme contacté(s) ?`)) {
        // Logique de mise à jour
        console.log('Marquer comme contactés:', selected);
        alert('Fonctionnalité à implémenter');
    }
}

function assignerResponsable() {
    const selected = getSelectedVisiteurs();
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un visiteur');
        return;
    }
    // Logique d'assignation
    console.log('Assigner responsable:', selected);
    alert('Fonctionnalité d\'assignation à implémenter');
}

function getSelectedVisiteurs() {
    const checkboxes = document.querySelectorAll('input[name="visiteurs[]"]:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// Gestion des notes
function ajouterNote(participantId, culteId) {
    document.getElementById('note_participant_id').value = participantId;
    document.getElementById('note_culte_id').value = culteId;
    document.getElementById('noteModal').classList.remove('hidden');
}

function closeNoteModal() {
    document.getElementById('noteModal').classList.add('hidden');
    document.getElementById('noteForm').reset();
}

function saveNote() {
    const form = document.getElementById('noteForm');
    const formData = new FormData(form);

    // Simulation d'envoi - à remplacer par l'appel API réel
    console.log('Sauvegarde de la note:', Object.fromEntries(formData));
    alert('Note sauvegardée avec succès !');
    closeNoteModal();
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('noteModal').addEventListener('click', function(e) {
    if (e.target === this) closeNoteModal();
});
</script>
@endpush
@endsection
