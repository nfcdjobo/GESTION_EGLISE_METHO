@extends('layouts.private.main')
@section('title', 'Nouveaux Visiteurs')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Nouveaux Visiteurs</h1>
            <p class="text-slate-500 mt-1">Suivi des nouvelles personnes et visiteurs nécessitant un accompagnement pastoral - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Période
                </h2>
                <div class="flex flex-wrap gap-2">
                    <button onclick="exportNouveauxVisiteurs()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i> Exporter
                    </button>
                    <button onclick="planifierSuivi()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-calendar-plus mr-2"></i> Planifier Suivi
                    </button>
                    <a href="{{ route('private.participants-cultes.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.participants-cultes.nouveaux-visiteurs') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Période (jours)</label>
                    <select name="jours" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="7" {{ request('jours', 30) == '7' ? 'selected' : '' }}>7 derniers jours</option>
                        <option value="15" {{ request('jours', 30) == '15' ? 'selected' : '' }}>15 derniers jours</option>
                        <option value="30" {{ request('jours', 30) == '30' ? 'selected' : '' }}>30 derniers jours</option>
                        <option value="60" {{ request('jours', 30) == '60' ? 'selected' : '' }}>60 derniers jours</option>
                        <option value="90" {{ request('jours', 30) == '90' ? 'selected' : '' }}>90 derniers jours</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de suivi</label>
                    <select name="type_suivi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="contact_pastoral" {{ request('type_suivi') == 'contact_pastoral' ? 'selected' : '' }}>Contact pastoral</option>
                        <option value="bapteme" {{ request('type_suivi') == 'bapteme' ? 'selected' : '' }}>Intéressé baptême</option>
                        <option value="membre" {{ request('type_suivi') == 'membre' ? 'selected' : '' }}>Veut devenir membre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut du suivi</label>
                    <select name="statut_suivi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="non_contacte" {{ request('statut_suivi') == 'non_contacte' ? 'selected' : '' }}>Non contacté</option>
                        <option value="en_cours" {{ request('statut_suivi') == 'en_cours' ? 'selected' : '' }}>Suivi en cours</option>
                        <option value="complete" {{ request('statut_suivi') == 'complete' ? 'selected' : '' }}>Suivi terminé</option>
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
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-plus text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $visiteurs->count() }}</p>
                    <p class="text-sm text-slate-500">Nouveaux visiteurs</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-pray text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $visiteurs->where('demande_contact_pastoral', true)->count() }}</p>
                    <p class="text-sm text-slate-500">Contact pastoral</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-water text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $visiteurs->where('interesse_bapteme', true)->count() }}</p>
                    <p class="text-sm text-slate-500">Intéressés baptême</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $visiteurs->where('souhaite_devenir_membre', true)->count() }}</p>
                    <p class="text-sm text-slate-500">Futurs membres</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des nouveaux visiteurs -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-teal-600 mr-2"></i>
                    Liste des Nouveaux Visiteurs ({{ $visiteurs->count() }})
                </h2>
                <div class="flex items-center space-x-2">
                    <select id="bulkAction" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="">Actions en lot</option>
                        <option value="marquer_contacte">Marquer contacté</option>
                        <option value="planifier_suivi">Planifier suivi</option>
                        <option value="export">Exporter sélection</option>
                    </select>
                    <button type="button" onclick="executeBulkAction()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200">
                        <i class="fas fa-play mr-2"></i> Exécuter
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($visiteurs->count() > 0)
                <div class="space-y-6">
                    @foreach($visiteurs as $visiteur)
                        <div class="border border-slate-200 rounded-xl overflow-hidden hover:shadow-md transition-all duration-200">
                            <div class="p-6">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <input type="checkbox" name="selected_visiteurs[]" value="{{ $visiteur->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 visiteur-checkbox mt-1">
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-emerald-500 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-lg">{{ substr($visiteur->participant->prenom, 0, 1) }}{{ substr($visiteur->participant->nom, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-slate-900 mb-2">
                                                    {{ $visiteur->participant->prenom }} {{ $visiteur->participant->nom }}
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 ml-2">
                                                        <i class="fas fa-star mr-1"></i> Première visite
                                                    </span>
                                                </h3>

                                                <!-- Informations de contact -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                                                    @if($visiteur->participant->email)
                                                        <div class="flex items-center space-x-2">
                                                            <i class="fas fa-envelope text-blue-600"></i>
                                                            <span class="text-sm text-slate-700">{{ $visiteur->participant->email }}</span>
                                                        </div>
                                                    @endif
                                                    @if($visiteur->participant->telephone_1)
                                                        <div class="flex items-center space-x-2">
                                                            <i class="fas fa-phone text-green-600"></i>
                                                            <span class="text-sm text-slate-700">{{ $visiteur->participant->telephone_1 }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="flex items-center space-x-2">
                                                        <i class="fas fa-calendar text-purple-600"></i>
                                                        <span class="text-sm text-slate-700">{{ $visiteur->culte->date_culte->format('d/m/Y') }}</span>
                                                    </div>
                                                </div>

                                                <!-- Informations du culte -->
                                                <div class="bg-slate-50 rounded-lg p-3 mb-4">
                                                    <div class="flex items-center space-x-4 text-sm">
                                                        <div class="flex items-center space-x-2">
                                                            <i class="fas fa-church text-blue-600"></i>
                                                            <span class="font-medium">{{ $visiteur->culte->titre }}</span>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <i class="fas fa-tag text-purple-600"></i>
                                                            <span>{{ ucfirst(str_replace('_', ' ', $visiteur->culte->type_culte ?? 'culte')) }}</span>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <i class="fas fa-user-check text-green-600"></i>
                                                            <span>{{ ucfirst(str_replace('_', ' ', $visiteur->statut_presence)) }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Besoins de suivi -->
                                                <div class="flex flex-wrap gap-2 mb-4">
                                                    @if($visiteur->demande_contact_pastoral)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                                                            <i class="fas fa-pray mr-1"></i> Contact pastoral demandé
                                                        </span>
                                                    @endif
                                                    @if($visiteur->interesse_bapteme)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                            <i class="fas fa-water mr-1"></i> Intéressé par le baptême
                                                        </span>
                                                    @endif
                                                    @if($visiteur->souhaite_devenir_membre)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                            <i class="fas fa-users mr-1"></i> Veut devenir membre
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Accompagnateur -->
                                                @if($visiteur->nom_accompagnateur && $visiteur->nom_accompagnateur !== 'Aucun')
                                                    <div class="flex items-center space-x-2 mb-4">
                                                        <i class="fas fa-user-friends text-cyan-600"></i>
                                                        <span class="text-sm text-slate-700">
                                                            <span class="font-medium">Accompagné par:</span> {{ $visiteur->nom_accompagnateur }}
                                                        </span>
                                                    </div>
                                                @endif

                                                <!-- Commentaires -->
                                                @if($visiteur->commentaires_participant)
                                                    <div class="bg-blue-50 rounded-lg p-3 mb-4">
                                                        <div class="flex items-start space-x-2">
                                                            <i class="fas fa-comment text-blue-600 mt-1"></i>
                                                            <div>
                                                                <div class="font-medium text-blue-900 mb-1">Commentaires du visiteur:</div>
                                                                <p class="text-sm text-blue-800">{{ $visiteur->commentaires_participant }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm text-slate-500 mb-2">
                                                    Inscrit le {{ $visiteur->date_inscription->format('d/m/Y') }}
                                                </div>
                                                <div class="text-xs text-slate-400">
                                                    Il y a {{ $visiteur->date_inscription->diffInDays(now()) }} jours
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="text-sm text-slate-600">
                                            <span class="font-medium">Priorité de suivi:</span>
                                            @if($visiteur->demande_contact_pastoral || $visiteur->interesse_bapteme || $visiteur->souhaite_devenir_membre)
                                                <span class="text-red-600 font-medium">Haute</span>
                                            @else
                                                <span class="text-yellow-600 font-medium">Normale</span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-slate-600">
                                            <span class="font-medium">Statut:</span>
                                            <span class="text-orange-600 font-medium">À contacter</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button onclick="contacterVisiteur('{{ $visiteur->id }}')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                            <i class="fas fa-phone mr-2"></i> Contacter
                                        </button>
                                        <button onclick="planifierRendezVous('{{ $visiteur->id }}')" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-calendar-plus mr-2"></i> RDV
                                        </button>
                                        <button onclick="marquerSuivi('{{ $visiteur->id }}')" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                            <i class="fas fa-check mr-2"></i> Marquer suivi
                                        </button>
                                        <a href="{{ route('private.participants-cultes.show', [$visiteur->participant_id, $visiteur->culte_id]) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors">
                                            <i class="fas fa-eye mr-2"></i> Détails
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-friends text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun nouveau visiteur</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request('jours'))
                            Aucun nouveau visiteur trouvé pour les {{ request('jours') }} derniers jours.
                        @else
                            Aucun nouveau visiteur trouvé pour la période sélectionnée.
                        @endif
                    </p>
                    <a href="{{ route('private.participants-cultes.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-users mr-2"></i> Voir toutes les participations
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de contact -->
<div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-phone text-green-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Contacter le visiteur</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de contact</label>
                    <select id="typeContact" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="telephone">Téléphone</option>
                        <option value="email">Email</option>
                        <option value="visite">Visite à domicile</option>
                        <option value="rencontre">Rencontre à l'église</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Notes du contact</label>
                    <textarea id="notesContact" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" placeholder="Notes sur le contact..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Responsable du suivi</label>
                    <select id="responsableSuivi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Sélectionner un responsable</option>
                        <option value="pasteur">Pasteur</option>
                        <option value="ancien">Ancien</option>
                        <option value="diacre">Diacre</option>
                        <option value="responsable_accueil">Responsable accueil</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeContactModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="enregistrerContact()" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                Enregistrer contact
            </button>
        </div>
    </div>
</div>

<script>
let currentVisiteurId = null;

// Sélection multiple
document.querySelectorAll('.visiteur-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        updateBulkActionButton();
    });
});

function updateBulkActionButton() {
    const selected = document.querySelectorAll('.visiteur-checkbox:checked').length;
    const button = document.querySelector('button[onclick="executeBulkAction()"]');
    if (button) {
        button.textContent = selected > 0 ? `Exécuter (${selected})` : 'Exécuter';
    }
}

// Actions individuelles
function contacterVisiteur(visiteurId) {
    currentVisiteurId = visiteurId;
    document.getElementById('contactModal').classList.remove('hidden');
}

function planifierRendezVous(visiteurId) {
    alert(`Planifier RDV pour visiteur ${visiteurId} - À implémenter`);
}

function marquerSuivi(visiteurId) {
    if (confirm('Marquer ce visiteur comme suivi ?')) {
        // API call to mark as followed up
        console.log('Marquer suivi pour visiteur:', visiteurId);
    }
}

// Modal functions
function closeContactModal() {
    document.getElementById('contactModal').classList.add('hidden');
    currentVisiteurId = null;
}

function enregistrerContact() {
    const typeContact = document.getElementById('typeContact').value;
    const notesContact = document.getElementById('notesContact').value;
    const responsableSuivi = document.getElementById('responsableSuivi').value;

    if (!notesContact.trim()) {
        alert('Veuillez saisir des notes sur le contact');
        return;
    }

    // API call to save contact information
    console.log('Enregistrer contact:', {
        visiteurId: currentVisiteurId,
        type: typeContact,
        notes: notesContact,
        responsable: responsableSuivi
    });

    closeContactModal();
    // Optionally reload or update the UI
}

// Actions en lot
function executeBulkAction() {
    const action = document.getElementById('bulkAction').value;
    const selected = Array.from(document.querySelectorAll('.visiteur-checkbox:checked')).map(cb => cb.value);

    if (!action || selected.length === 0) {
        alert('Veuillez sélectionner une action et au moins un visiteur');
        return;
    }

    switch (action) {
        case 'marquer_contacte':
            if (confirm(`Marquer ${selected.length} visiteur(s) comme contacté(s) ?`)) {
                console.log('Marquer contactés:', selected);
            }
            break;
        case 'planifier_suivi':
            alert(`Planifier suivi pour ${selected.length} visiteur(s) - À implémenter`);
            break;
        case 'export':
            exportNouveauxVisiteurs(selected);
            break;
    }
}

// Export functions
function exportNouveauxVisiteurs(selected = null) {
    const params = new URLSearchParams();
    if (selected) {
        selected.forEach(id => params.append('ids[]', id));
    }

    // Add current filters to export
    params.append('jours', document.querySelector('select[name="jours"]').value);
    const typeFilter = document.querySelector('select[name="type_suivi"]').value;
    if (typeFilter) params.append('type_suivi', typeFilter);

    window.open(`/participants-cultes/nouveaux-visiteurs/export?${params.toString()}`, '_blank');
}

function planifierSuivi() {
    alert('Planification globale du suivi - À implémenter');
}

// Close modal when clicking outside
document.getElementById('contactModal').addEventListener('click', function(e) {
    if (e.target === this) closeContactModal();
});
</script>
@endsection
