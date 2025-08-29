{{-- components/private/annonces/bulk-actions.blade.php --}}
@props([
    'annonces' => collect(),
    'showSelectAll' => true
])

<!-- Barre d'actions en lot -->
<div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 mb-6"
     id="bulk-actions-bar"
     style="display: none;">
    <div class="p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <i class="fas fa-check-square text-blue-600 mr-2"></i>
                    <span class="font-medium text-slate-800">
                        <span id="selected-count">0</span> annonce(s) sélectionnée(s)
                    </span>
                </div>

                @if($showSelectAll)
                    <div class="flex items-center space-x-2">
                        <button type="button"
                                onclick="selectAll()"
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Tout sélectionner
                        </button>
                        <span class="text-slate-300">|</span>
                        <button type="button"
                                onclick="deselectAll()"
                                class="text-sm text-slate-600 hover:text-slate-800 font-medium">
                            Tout désélectionner
                        </button>
                    </div>
                @endif
            </div>

            <!-- Actions disponibles -->
            <div class="flex items-center space-x-2">
                <!-- Publication en lot -->
                @can('create', App\Models\Annonce::class)
                    <button type="button"
                            onclick="bulkPublish()"
                            id="bulk-publish-btn"
                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Publier
                    </button>
                @endcan

                <!-- Archivage en lot -->
                <button type="button"
                        onclick="bulkArchive()"
                        id="bulk-archive-btn"
                        class="inline-flex items-center px-3 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-archive mr-2"></i>
                    Archiver
                </button>

                <!-- Changement d'audience en lot -->
                <div class="relative" id="bulk-audience-dropdown">
                    <button type="button"
                            onclick="toggleBulkAudienceDropdown()"
                            class="inline-flex items-center px-3 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-users mr-2"></i>
                        Audience
                        <i class="fas fa-chevron-down ml-1"></i>
                    </button>

                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 z-10 hidden"
                         id="bulk-audience-menu">
                        <div class="p-2">
                            <button type="button"
                                    onclick="bulkChangeAudience('tous')"
                                    class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-md">
                                👥 Tous
                            </button>
                            <button type="button"
                                    onclick="bulkChangeAudience('membres')"
                                    class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-md">
                                👤 Membres
                            </button>
                            <button type="button"
                                    onclick="bulkChangeAudience('leadership')"
                                    class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-md">
                                👔 Leadership
                            </button>
                            <button type="button"
                                    onclick="bulkChangeAudience('jeunes')"
                                    class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-md">
                                🧒 Jeunes
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Changement de priorité en lot -->
                <div class="relative" id="bulk-priority-dropdown">
                    <button type="button"
                            onclick="toggleBulkPriorityDropdown()"
                            class="inline-flex items-center px-3 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Priorité
                        <i class="fas fa-chevron-down ml-1"></i>
                    </button>

                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 z-10 hidden"
                         id="bulk-priority-menu">
                        <div class="p-2">
                            <button type="button"
                                    onclick="bulkChangePriority('urgent')"
                                    class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-md">
                                🔴 Urgent
                            </button>
                            <button type="button"
                                    onclick="bulkChangePriority('important')"
                                    class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-md">
                                🟡 Important
                            </button>
                            <button type="button"
                                    onclick="bulkChangePriority('normal')"
                                    class="w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 rounded-md">
                                ⚪ Normal
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Export des sélectionnées -->
                <button type="button"
                        onclick="exportSelected()"
                        class="inline-flex items-center px-3 py-2 bg-cyan-600 text-white text-sm font-medium rounded-lg hover:bg-cyan-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>
                    Exporter
                </button>

                <!-- Suppression en lot -->
                @can('delete', App\Models\Annonce::class)
                    <button type="button"
                            onclick="bulkDelete()"
                            id="bulk-delete-btn"
                            class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Supprimer
                    </button>
                @endcan

                <!-- Fermer la barre -->
                <button type="button"
                        onclick="closeBulkActions()"
                        class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Barre de progression pour les opérations -->
        <div class="mt-4 hidden" id="bulk-progress">
            <div class="flex items-center justify-between text-sm text-slate-600 mb-2">
                <span id="progress-text">Traitement en cours...</span>
                <span id="progress-percentage">0%</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                     id="progress-bar"
                     style="width: 0%"></div>
            </div>
        </div>

        <!-- Résumé des actions -->
        <div class="mt-4 hidden" id="bulk-summary">
            <div class="bg-slate-50 rounded-lg p-4">
                <h4 class="font-medium text-slate-800 mb-2">Résumé des actions :</h4>
                <div id="summary-content" class="text-sm text-slate-600">
                    <!-- Contenu dynamique -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cases à cocher pour chaque annonce (à intégrer dans la liste) -->
<script id="bulk-checkbox-template" type="text/template">
    <div class="flex items-center mr-3">
        <input type="checkbox"
               class="annonce-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
               data-annonce-id="{{annonceId}}"
               data-annonce-titre="{{titre}}"
               data-annonce-statut="{{statut}}"
               onchange="handleCheckboxChange(this)">
    </div>
</script>

@push('scripts')
<script>
let selectedAnnonces = new Set();

function handleCheckboxChange(checkbox) {
    const annonceId = checkbox.dataset.annonceId;

    if (checkbox.checked) {
        selectedAnnonces.add({
            id: annonceId,
            titre: checkbox.dataset.annonceTitre,
            statut: checkbox.dataset.annonceStatut
        });
    } else {
        selectedAnnonces.forEach(annonce => {
            if (annonce.id === annonceId) {
                selectedAnnonces.delete(annonce);
            }
        });
    }

    updateBulkActionsBar();
}

function updateBulkActionsBar() {
    const bulkBar = document.getElementById('bulk-actions-bar');
    const selectedCount = document.getElementById('selected-count');
    const publishBtn = document.getElementById('bulk-publish-btn');
    const archiveBtn = document.getElementById('bulk-archive-btn');
    const deleteBtn = document.getElementById('bulk-delete-btn');

    selectedCount.textContent = selectedAnnonces.size;

    if (selectedAnnonces.size > 0) {
        bulkBar.style.display = 'block';

        // Activer/désactiver les boutons selon le statut des annonces sélectionnées
        const selectedArray = Array.from(selectedAnnonces);
        const hasBrouillons = selectedArray.some(a => a.statut === 'brouillon');
        const hasPubliees = selectedArray.some(a => a.statut === 'publiee');

        if (publishBtn) {
            publishBtn.disabled = !hasBrouillons;
            publishBtn.title = hasBrouillons ? 'Publier les brouillons sélectionnés' : 'Aucun brouillon sélectionné';
        }

        if (archiveBtn) {
            archiveBtn.disabled = !hasPubliees;
            archiveBtn.title = hasPubliees ? 'Archiver les annonces publiées' : 'Aucune annonce publiée sélectionnée';
        }
    } else {
        bulkBar.style.display = 'none';
    }
}

function selectAll() {
    const checkboxes = document.querySelectorAll('.annonce-checkbox');
    checkboxes.forEach(checkbox => {
        if (!checkbox.checked) {
            checkbox.checked = true;
            handleCheckboxChange(checkbox);
        }
    });
}

function deselectAll() {
    const checkboxes = document.querySelectorAll('.annonce-checkbox');
    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            checkbox.checked = false;
        }
    });
    selectedAnnonces.clear();
    updateBulkActionsBar();
}

function closeBulkActions() {
    deselectAll();
}

// Actions en lot
async function bulkPublish() {
    const brouillons = Array.from(selectedAnnonces).filter(a => a.statut === 'brouillon');

    if (brouillons.length === 0) {
        alert('Aucun brouillon sélectionné');
        return;
    }

    if (!confirm(`Publier ${brouillons.length} annonce(s) ?`)) {
        return;
    }

    await executeBulkAction('publish', brouillons, 'Publication en cours...');
}

async function bulkArchive() {
    const publiees = Array.from(selectedAnnonces).filter(a => a.statut === 'publiee');

    if (publiees.length === 0) {
        alert('Aucune annonce publiée sélectionnée');
        return;
    }

    if (!confirm(`Archiver ${publiees.length} annonce(s) ?`)) {
        return;
    }

    await executeBulkAction('archive', publiees, 'Archivage en cours...');
}

async function bulkDelete() {
    const toDelete = Array.from(selectedAnnonces);

    if (toDelete.length === 0) {
        return;
    }

    if (!confirm(`Supprimer définitivement ${toDelete.length} annonce(s) ? Cette action est irréversible.`)) {
        return;
    }

    await executeBulkAction('delete', toDelete, 'Suppression en cours...');
}

async function bulkChangeAudience(audience) {
    const selected = Array.from(selectedAnnonces);

    if (selected.length === 0) {
        return;
    }

    const audienceLabels = {
        'tous': 'Tous',
        'membres': 'Membres',
        'leadership': 'Leadership',
        'jeunes': 'Jeunes'
    };

    if (!confirm(`Changer l'audience de ${selected.length} annonce(s) vers "${audienceLabels[audience]}" ?`)) {
        return;
    }

    await executeBulkAction('change-audience', selected, 'Modification de l\'audience...', { audience });
    toggleBulkAudienceDropdown();
}

async function bulkChangePriority(priority) {
    const selected = Array.from(selectedAnnonces);

    if (selected.length === 0) {
        return;
    }

    const priorityLabels = {
        'urgent': 'Urgent',
        'important': 'Important',
        'normal': 'Normal'
    };

    if (!confirm(`Changer la priorité de ${selected.length} annonce(s) vers "${priorityLabels[priority]}" ?`)) {
        return;
    }

    await executeBulkAction('change-priority', selected, 'Modification de la priorité...', { priority });
    toggleBulkPriorityDropdown();
}

async function executeBulkAction(action, items, progressText, params = {}) {
    const progressDiv = document.getElementById('bulk-progress');
    const progressBar = document.getElementById('progress-bar');
    const progressPercentage = document.getElementById('progress-percentage');
    const progressTextEl = document.getElementById('progress-text');
    const summaryDiv = document.getElementById('bulk-summary');
    const summaryContent = document.getElementById('summary-content');

    // Afficher la barre de progression
    progressDiv.classList.remove('hidden');
    progressTextEl.textContent = progressText;

    let completed = 0;
    let successful = 0;
    let failed = 0;
    const failedItems = [];

    // Traiter les éléments par petits lots pour éviter la surcharge
    const batchSize = 5;
    for (let i = 0; i < items.length; i += batchSize) {
        const batch = items.slice(i, i + batchSize);
        const promises = batch.map(item => processBulkItem(action, item.id, params));

        const results = await Promise.allSettled(promises);

        results.forEach((result, index) => {
            completed++;
            const progress = (completed / items.length) * 100;

            if (result.status === 'fulfilled' && result.value.success) {
                successful++;
            } else {
                failed++;
                failedItems.push(batch[index].titre);
            }

            progressBar.style.width = `${progress}%`;
            progressPercentage.textContent = `${Math.round(progress)}%`;
        });

        // Petite pause entre les lots
        if (i + batchSize < items.length) {
            await new Promise(resolve => setTimeout(resolve, 200));
        }
    }

    // Afficher le résumé
    let summaryHtml = `<p><strong>Action terminée :</strong> ${action}</p>`;
    summaryHtml += `<p>✅ Réussies : ${successful}</p>`;

    if (failed > 0) {
        summaryHtml += `<p>❌ Échecs : ${failed}</p>`;
        if (failedItems.length > 0) {
            summaryHtml += `<p>Annonces en échec : ${failedItems.join(', ')}</p>`;
        }
    }

    summaryContent.innerHTML = summaryHtml;
    summaryDiv.classList.remove('hidden');

    // Cacher la barre de progression après 2 secondes
    setTimeout(() => {
        progressDiv.classList.add('hidden');
        progressBar.style.width = '0%';
        progressPercentage.textContent = '0%';
    }, 2000);

    // Cacher le résumé après 10 secondes
    setTimeout(() => {
        summaryDiv.classList.add('hidden');
    }, 10000);

    // Recharger la page si des actions ont réussi
    if (successful > 0) {
        setTimeout(() => {
            location.reload();
        }, 3000);
    }
}

async function processBulkItem(action, annonceId, params) {
    const endpoints = {
        'publish': `{{ url('dashboard/annonces') }}/${annonceId}/publier`,
        'archive': `{{ url('dashboard/annonces') }}/${annonceId}/archiver`,
        'delete': `{{ url('dashboard/annonces') }}/${annonceId}`,
        'change-audience': `{{ url('dashboard/annonces') }}/${annonceId}/bulk-update`,
        'change-priority': `{{ url('dashboard/annonces') }}/${annonceId}/bulk-update`
    };

    const methods = {
        'publish': 'PATCH',
        'archive': 'PATCH',
        'delete': 'DELETE',
        'change-audience': 'PATCH',
        'change-priority': 'PATCH'
    };

    const body = {
        'change-audience': { audience_cible: params.audience },
        'change-priority': { niveau_priorite: params.priority }
    };

    try {
        const response = await fetch(endpoints[action], {
            method: methods[action],
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: body[action] ? JSON.stringify(body[action]) : null
        });

        const data = await response.json();
        return { success: data.success || response.ok, data };
    } catch (error) {
        console.error(`Erreur lors de l'action ${action} sur l'annonce ${annonceId}:`, error);
        return { success: false, error };
    }
}

// Gestion des dropdowns
function toggleBulkAudienceDropdown() {
    const menu = document.getElementById('bulk-audience-menu');
    menu.classList.toggle('hidden');

    // Fermer les autres dropdowns
    document.getElementById('bulk-priority-menu').classList.add('hidden');
}

function toggleBulkPriorityDropdown() {
    const menu = document.getElementById('bulk-priority-menu');
    menu.classList.toggle('hidden');

    // Fermer les autres dropdowns
    document.getElementById('bulk-audience-menu').classList.add('hidden');
}

// Fermer les dropdowns en cliquant ailleurs
document.addEventListener('click', function(e) {
    if (!e.target.closest('#bulk-audience-dropdown')) {
        document.getElementById('bulk-audience-menu').classList.add('hidden');
    }
    if (!e.target.closest('#bulk-priority-dropdown')) {
        document.getElementById('bulk-priority-menu').classList.add('hidden');
    }
});

function exportSelected() {
    const selected = Array.from(selectedAnnonces);

    if (selected.length === 0) {
        alert('Aucune annonce sélectionnée');
        return;
    }

    const ids = selected.map(a => a.id).join(',');
    window.open(`{{ route('private.annonces.index') }}?export=csv&ids=${ids}`, '_blank');
}
</script>
@endpush
