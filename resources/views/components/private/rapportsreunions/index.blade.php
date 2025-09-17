@extends('layouts.private.main')
@section('title', 'Gestion des Rapports de Réunions')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Rapports de Réunions</h1>
        <p class="text-slate-500 mt-1">Suivi et gestion des rapports de réunions - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
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
                    @can('create', App\Models\RapportReunion::class)
                        <a href="{{ route('private.rapports-reunions.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouveau Rapport
                        </a>
                    @endcan
                    <a href="{{ route('private.rapports-reunions.statistiques') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                    <a href="{{ route('private.rapports-reunions.a-valider') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-check mr-2"></i> À Valider
                    </a>
                    <a href="{{ route('private.rapports-reunions.mes-rapports') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-user mr-2"></i> Mes Rapports
                    </a>

                    <button type="button" onclick="exporterSelection()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.rapports-reunions.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Titre, résumé, réunion..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        @foreach(\App\Models\RapportReunion::STATUTS as $key => $value)
                            <option value="{{ $value }}" {{ request('statut') == $value ? 'selected' : '' }}>
                                @switch($value)
                                    @case('brouillon') Brouillon @break
                                    @case('en_revision') En Révision @break
                                    @case('valide') Validé @break
                                    @case('publie') Publié @break
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de Rapport</label>
                    <select name="type" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        @foreach(\App\Models\RapportReunion::TYPES_RAPPORT as $key => $value)
                            <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>
                                @switch($value)
                                    @case('proces_verbal') Procès-verbal @break
                                    @case('compte_rendu') Compte-rendu @break
                                    @case('rapport_activite') Rapport d'activité @break
                                    @case('rapport_financier') Rapport financier @break
                                @endswitch
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Rédacteur</label>
                    <select name="redacteur_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les rédacteurs</option>
                        @foreach(\App\Models\User::whereHas('rapportsRediges')->get() as $redacteur)
                            <option value="{{ $redacteur->id }}" {{ request('redacteur_id') == $redacteur->id ? 'selected' : '' }}>{{ $redacteur->nom }} {{ $redacteur->prenom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="{{ route('private.rapports-reunions.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
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
                        <i class="fas fa-file-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['total'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Total des rapports</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-edit text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['en_revision'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">En révision</p>
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
                    <p class="text-2xl font-bold text-slate-800">{{ $statistiques['publies'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Publiés</p>
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
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($statistiques['satisfaction_moyenne'] ?? 0, 1) }}</p>
                    <p class="text-sm text-slate-500">Satisfaction moyenne</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des rapports -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Rapports ({{ $rapports->total() }})
                </h2>
                <div class="flex items-center space-x-2">
                    <select id="sortBy" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date de création</option>
                        <option value="titre_rapport" {{ request('sort_by') == 'titre_rapport' ? 'selected' : '' }}>Titre</option>
                        <option value="statut" {{ request('sort_by') == 'statut' ? 'selected' : '' }}>Statut</option>
                        <option value="type_rapport" {{ request('sort_by') == 'type_rapport' ? 'selected' : '' }}>Type</option>
                    </select>
                    <select id="sortOrder" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Croissant</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($rapports->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($rapports as $rapport)
                        <div class="bg-gradient-to-br from-white to-slate-50 rounded-xl border border-slate-200 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $rapport->titre_rapport }}</h3>
                                    <p class="text-sm text-slate-600">{{ $rapport->type_rapport_traduit }}</p>
                                    @if($rapport->reunion)
                                        <p class="text-xs text-slate-500 mt-1">{{ $rapport->reunion->titre }}</p>
                                    @endif
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    @php
                                        $statutColors = [
                                            'brouillon' => 'bg-gray-100 text-gray-800',
                                            'en_revision' => 'bg-yellow-100 text-yellow-800',
                                            'valide' => 'bg-blue-100 text-blue-800',
                                            'publie' => 'bg-green-100 text-green-800'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statutColors[$rapport->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $rapport->statut_traduit }}
                                    </span>
                                    <div class="text-xs text-slate-500 text-right">
                                        Complété à {{ $rapport->pourcentage_completion }}%
                                    </div>
                                </div>
                            </div>

                            <!-- Détails -->
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm text-slate-600">
                                    <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                    <span>{{ $rapport->created_at->format('d/m/Y à H:i') }}</span>
                                </div>

                                @if($rapport->redacteur)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-user-edit w-4 mr-2"></i>
                                        <span>{{ $rapport->redacteur->nom }} {{ $rapport->redacteur->prenom }}</span>
                                    </div>
                                @endif

                                @if($rapport->nombre_presents)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-users w-4 mr-2"></i>
                                        <span>{{ $rapport->nombre_presents }} présents</span>
                                    </div>
                                @endif

                                @if($rapport->note_satisfaction)
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i class="fas fa-star w-4 mr-2"></i>
                                        <span>{{ $rapport->note_satisfaction }}/5</span>
                                    </div>
                                @endif

                                @if($rapport->resume)
                                    <div class="text-sm text-slate-600">
                                        <p class="truncate">
                                            {{ Str::limit(strip_tags($rapport->resume), 80) }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                <div class="flex items-center space-x-2">
                                    @can('view', $rapport)
                                        <a href="{{ route('private.rapports-reunions.show', $rapport) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                    @endcan

                                    @can('rapports-reunions.update')
                                        <a href="{{ route('private.rapports-reunions.edit', $rapport) }}" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                    @endcan

                                    @can('rapports-reunions.revision')
                                    @if($rapport->statut === 'brouillon')
                                        <button type="button" onclick="changerStatut('{{ $rapport->id }}', 'en_revision')" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Passer en révision">
                                            <i class="fas fa-arrow-right text-sm"></i>
                                        </button>
                                    @endif
                                    @endcan

                                    @if($rapport->statut === 'en_revision')
                                        <button type="button" onclick="openValidationModal('{{ $rapport->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Valider">
                                            <i class="fas fa-check text-sm"></i>
                                        </button>
                                    @endif
                                    @can('rapports-reunions.publish')
                                    @if($rapport->statut === 'valide')
                                        <button type="button" onclick="changerStatut('{{ $rapport->id }}', 'publie')" class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors" title="Publier">
                                            <i class="fas fa-share text-sm"></i>
                                        </button>
                                    @endif
                                    @endcan
                                </div>

                                @can('rapports-reunions.delete')
                                    @if($rapport->statut !== 'publie')
                                        <button type="button" onclick="supprimerRapport('{{ $rapport->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
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
                        Affichage de <span class="font-medium">{{ $rapports->firstItem() }}</span> à <span class="font-medium">{{ $rapports->lastItem() }}</span>
                        sur <span class="font-medium">{{ $rapports->total() }}</span> résultats
                    </div>
                    <div>
                        {{ $rapports->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun rapport trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['search', 'statut', 'type', 'redacteur_id']))
                            Aucun rapport ne correspond à vos critères de recherche.
                        @else
                            Commencez par créer votre premier rapport de réunion.
                        @endif
                    </p>
                    @can('rapports-reunions.create')
                        <a href="{{ route('private.rapports-reunions.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Créer un rapport
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal validation -->
<div id="validationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Valider le rapport</h3>
            <form id="validationForm">
                @csrf
                <input type="hidden" id="rapport_id" name="rapport_id">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Commentaires (optionnel)</label>
                    <div class="has-error-container">
                        <textarea name="commentaires" id="commentaires" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Commentaires sur la validation..."></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeValidationModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            @can('rapports-reunions.validate')
            <button type="button" onclick="validerRapport()" class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                Valider
            </button>
            @endcan
        </div>
    </div>
</div>

@include('partials.ckeditor-resources')
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
    url.searchParams.set('sort_direction', sortOrder);
    window.location.href = url.toString();
}

// Modal validation
function openValidationModal(rapportId) {
    document.getElementById('rapport_id').value = rapportId;
    document.getElementById('validationModal').classList.remove('hidden');

    // Initialiser CKEditor
    setTimeout(() => {
        if (document.getElementById('commentaires') && typeof ClassicEditor !== 'undefined') {
            if (!document.querySelector('#commentaires + .ck-editor')) {
                initializeCKEditor('#commentaires', 'simple', {
                    placeholder: 'Commentaires sur la validation...'
                });
            }
        }
    }, 100);
}


let selectedRapports = [];

function exporterSelection() {
    const checkboxes = document.querySelectorAll('input[name="rapport_ids[]"]:checked');
    const rapportIds = Array.from(checkboxes).map(cb => cb.value);

    if (rapportIds.length === 0) {
        alert('Veuillez sélectionner au moins un rapport');
        return;
    }

    const url = new URL('{{ route("private.rapports-reunions.export") }}');
    url.searchParams.set('format', 'pdf');
    rapportIds.forEach(id => url.searchParams.append('rapport_ids[]', id));

    window.open(url.toString(), '_blank');
}

// Ajouter des checkboxes dans chaque carte de rapport
function ajouterCheckboxRapport() {
    // Ajouter cette checkbox dans chaque carte de rapport
    return `
        <div class="absolute top-2 left-2">
            <input type="checkbox" name="rapport_ids[]" value="${rapportId}"
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
        </div>
    `;
}



function closeValidationModal() {
    // Nettoyer l'instance CKEditor
    const editorContainer = document.querySelector('#commentaires + .ck-editor');
    if (editorContainer && window.CKEditorInstances && window.CKEditorInstances['#commentaires']) {
        window.CKEditorInstances['#commentaires'].destroy()
            .then(() => {
                delete window.CKEditorInstances['#commentaires'];
            })
            .catch(error => {
                console.error('Erreur lors de la destruction de CKEditor:', error);
            });
    }

    document.getElementById('validationModal').classList.add('hidden');
    document.getElementById('validationForm').reset();
}

function validerRapport() {
    // Synchroniser CKEditor
    if (window.CKEditorInstances && window.CKEditorInstances['#commentaires']) {
        const editor = window.CKEditorInstances['#commentaires'];
        const textarea = document.getElementById('commentaires');
        if (textarea) {
            textarea.value = editor.getData();
        }
    }

    const form = document.getElementById('validationForm');
    const formData = new FormData(form);
    const rapportId = document.getElementById('rapport_id').value;

    fetch(`{{ route('private.rapports-reunions.valider', ':rapportid') }}`.replace(':rapportid', rapportId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
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

// Changer statut
function changerStatut(rapportId, nouveauStatut) {
    let url;
    switch(nouveauStatut) {
        case 'en_revision':
            url = `{{ route('private.rapports-reunions.revision', ':rapportid') }}`.replace(':rapportid', rapportId);
            break;
        case 'publie':
            url = `{{ route('private.rapports-reunions.publier', ':rapportid') }}`.replace(':rapportid', rapportId);
            break;
        default:
            return;
    }

    fetch(url, {
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

// Suppression
function supprimerRapport(rapportId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')) {
        fetch(`{{ route('private.rapports-reunions.destroy', ':rapportid') }}`.replace(':rapportid', rapportId), {
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
document.getElementById('validationModal').addEventListener('click', function(e) {
    if (e.target === this) closeValidationModal();
});
</script>
@endpush
@endsection
