{{--
    Composant partiel pour les modals communes des cultes
    À inclure dans les vues qui en ont besoin avec @include('components.private.cultes._modals')
--}}

<!-- Modal changement de statut -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Changer le statut du culte</h3>
            </div>

            <form id="statusForm">
                @csrf
                <input type="hidden" id="culte_id" name="culte_id">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau statut</label>
                    <select id="nouveau_statut" name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="planifie">Planifié</option>
                        <option value="en_preparation">En Préparation</option>
                        <option value="en_cours">En Cours</option>
                        <option value="termine">Terminé</option>
                        <option value="annule">Annulé</option>
                        <option value="reporte">Reporté</option>
                    </select>
                </div>

                <div id="raisonDiv" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Raison</label>
                    <textarea name="raison" id="raison" rows="3"
                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                        placeholder="Raison de l'annulation ou du report..."></textarea>
                </div>

                <div id="participantsDiv" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nombre de participants</label>
                    <input type="number" name="nombre_participants" id="modal_nombre_participants" min="0"
                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Requis pour les cultes terminés">
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeStatusModal()"
                class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="updateStatus()"
                class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Changer le statut
            </button>
        </div>
    </div>
</div>

<!-- Modal duplication -->
<div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-copy text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Dupliquer le culte</h3>
            </div>

            <form id="duplicateForm">
                @csrf
                <input type="hidden" id="duplicate_culte_id" name="culte_id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Nouvelle date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="nouvelle_date" id="nouvelle_date" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle heure</label>
                        <input type="time" name="nouvelle_heure" id="nouvelle_heure"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau titre (optionnel)</label>
                        <input type="text" name="nouveau_titre" id="nouveau_titre"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Laisser vide pour ajouter (Copie)">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="copier_responsables" name="copier_responsables" value="1" checked
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="copier_responsables" class="ml-2 text-sm text-slate-700">
                            Copier les responsables
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="copier_message" name="copier_message" value="1" checked
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="copier_message" class="ml-2 text-sm text-slate-700">
                            Copier le message et les détails
                        </label>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDuplicateModal()"
                class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="duplicateCulte()"
                class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                Dupliquer
            </button>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3>
            </div>

            <div class="mb-4">
                <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer ce culte ?</p>
                <p class="text-red-600 font-medium text-sm">Cette action est irréversible.</p>
                <div id="deleteCulteInfo" class="mt-3 p-3 bg-slate-50 rounded-lg">
                    <!-- Informations du culte à supprimer -->
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDeleteModal()"
                class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" id="confirmDelete"
                class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Supprimer définitivement
            </button>
        </div>
    </div>
</div>

<!-- Modal d'information sur un culte (pour le planning) -->
<div id="culteInfoModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-screen overflow-y-auto">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Détails du Culte</h3>
                <button onclick="closeCulteInfoModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div id="culteInfoContent" class="p-6">
            <!-- Contenu chargé dynamiquement -->
            <div class="text-center py-8">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
                <p class="text-slate-500 mt-4">Chargement...</p>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button onclick="closeCulteInfoModal()"
                class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Fermer
            </button>
            <a id="culteInfoViewLink" href="#"
                class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Voir les détails complets
            </a>
        </div>
    </div>
</div>

<!-- Modal de notification/toast -->
<div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2">
    <!-- Les toasts seront ajoutés dynamiquement ici -->
</div>

@push('scripts')
<script>
// Variables globales pour les modals
let currentCulteId = null;
let deleteCallback = null;

// Modal changement de statut
function openStatusModal(culteId, currentStatus) {
    currentCulteId = culteId;
    document.getElementById('culte_id').value = culteId;
    document.getElementById('nouveau_statut').value = currentStatus;
    toggleRaisonField();
    toggleParticipantsField();
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusForm').reset();
    currentCulteId = null;
}

function toggleRaisonField() {
    const statut = document.getElementById('nouveau_statut').value;
    const raisonDiv = document.getElementById('raisonDiv');
    if (statut === 'annule' || statut === 'reporte') {
        raisonDiv.classList.remove('hidden');
        document.getElementById('raison').required = true;
    } else {
        raisonDiv.classList.add('hidden');
        document.getElementById('raison').required = false;
    }
}

function toggleParticipantsField() {
    const statut = document.getElementById('nouveau_statut').value;
    const participantsDiv = document.getElementById('participantsDiv');
    if (statut === 'termine') {
        participantsDiv.classList.remove('hidden');
        document.getElementById('modal_nombre_participants').required = true;
    } else {
        participantsDiv.classList.add('hidden');
        document.getElementById('modal_nombre_participants').required = false;
    }
}

function updateStatus() {
    if (!currentCulteId) return;

    const form = document.getElementById('statusForm');
    const formData = new FormData(form);

    // Validation côté client
    const statut = document.getElementById('nouveau_statut').value;
    if ((statut === 'annule' || statut === 'reporte') && !document.getElementById('raison').value.trim()) {
        showToast('Veuillez indiquer la raison de l\'annulation ou du report', 'error');
        return;
    }

    if (statut === 'termine' && !document.getElementById('modal_nombre_participants').value) {
        showToast('Le nombre de participants est requis pour les cultes terminés', 'error');
        return;
    }

    fetch(`{{route('private.cultes.statut', ':culte')}}`.replace(':culte', currentCulteId), {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Statut mis à jour avec succès', 'success');
            closeStatusModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Une erreur est survenue', 'error');
    });
}

// Modal duplication
function openDuplicateModal(culteId) {
    currentCulteId = culteId;
    document.getElementById('duplicate_culte_id').value = culteId;

    // Définir la date de demain par défaut
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('nouvelle_date').value = tomorrow.toISOString().split('T')[0];

    document.getElementById('duplicateModal').classList.remove('hidden');
}

function closeDuplicateModal() {
    document.getElementById('duplicateModal').classList.add('hidden');
    document.getElementById('duplicateForm').reset();
    currentCulteId = null;
}

function duplicateCulte() {
    if (!currentCulteId) return;

    const form = document.getElementById('duplicateForm');
    const formData = new FormData(form);

    // Validation côté client
    if (!document.getElementById('nouvelle_date').value) {
        showToast('Veuillez sélectionner une date', 'error');
        return;
    }

    fetch(`{{route('private.cultes.dupliquer', ':culte')}}`.replace(':culte', currentCulteId), {
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
            showToast('Culte dupliqué avec succès', 'success');
            closeDuplicateModal();
            window.location.href = `{{route('private.cultes.show', ':culte')}}`.replace(':culte', data.data.id);
        } else {
            showToast(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Une erreur est survenue', 'error');
    });
}

// Modal suppression
function deleteCulte(culteId, culteInfo = null) {
    currentCulteId = culteId;

    // Afficher les informations du culte dans le modal
    if (culteInfo) {
        document.getElementById('deleteCulteInfo').innerHTML = `
            <div class="text-sm">
                <div class="font-semibold text-slate-900">${culteInfo.titre}</div>
                <div class="text-slate-600">${culteInfo.date} - ${culteInfo.type}</div>
            </div>
        `;
    }

    document.getElementById('deleteModal').classList.remove('hidden');

    document.getElementById('confirmDelete').onclick = function() {
        executeDelete();
    };
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    currentCulteId = null;
    deleteCallback = null;
}

function executeDelete() {
    if (!currentCulteId) return;

    fetch(`{{route('private.cultes.destroy', ':culte')}}`.replace(':culte', currentCulteId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Culte supprimé avec succès', 'success');
            closeDeleteModal();
            if (deleteCallback) {
                deleteCallback();
            } else {
                setTimeout(() => location.reload(), 1000);
            }
        } else {
            showToast(data.message || 'Une erreur est survenue', 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Une erreur est survenue', 'error');
    });
}

// Modal d'information sur un culte
function showCulteDetails(culteId) {
    const modal = document.getElementById('culteInfoModal');
    const content = document.getElementById('culteInfoContent');
    const viewLink = document.getElementById('culteInfoViewLink');

    // Réinitialiser le contenu
    content.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="text-slate-500 mt-4">Chargement...</p>
        </div>
    `;

    modal.classList.remove('hidden');

    // Charger les détails via AJAX
    fetch(`{{route('private.cultes.show', ':culte')}}`.replace(':culte', culteId), {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const culte = data.data;
            content.innerHTML = generateCulteInfoHTML(culte);
            viewLink.href =`{{route('private.cultes.show', ':culte')}}`.replace(':culte', culteId);
        } else {
            content.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                    <p class="text-slate-500">Erreur lors du chargement des détails</p>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        content.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                <p class="text-slate-500">Erreur lors du chargement des détails</p>
            </div>
        `;
    });
}

function closeCulteInfoModal() {
    document.getElementById('culteInfoModal').classList.add('hidden');
}

function generateCulteInfoHTML(culte) {
    return `
        <div class="space-y-4">
            <div>
                <h4 class="font-semibold text-slate-900">${culte.titre}</h4>
                <p class="text-slate-600">${culte.type_culte_libelle}</p>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-slate-500">Date:</span>
                    <div class="font-semibold">${new Date(culte.date_culte).toLocaleDateString('fr-FR')}</div>
                </div>
                <div>
                    <span class="text-slate-500">Heure:</span>
                    <div class="font-semibold">${culte.heure_debut ? formatTime(culte.heure_debut) : 'Non définie'}</div>
                </div>
                <div>
                    <span class="text-slate-500">Lieu:</span>
                    <div class="font-semibold">${culte.lieu}</div>
                </div>
                <div>
                    <span class="text-slate-500">Statut:</span>
                    <div class="font-semibold">${culte.statut_libelle}</div>
                </div>
            </div>

            ${culte.description ? `
                <div>
                    <span class="text-slate-500">Description:</span>
                    <p class="text-slate-700 mt-1">${culte.description}</p>
                </div>
            ` : ''}

            ${culte.pasteur_principal ? `
                <div>
                    <span class="text-slate-500">Pasteur principal:</span>
                    <div class="font-semibold">${culte.pasteur_principal.nom} ${culte.pasteur_principal.prenom}</div>
                </div>
            ` : ''}

            ${culte.titre_message ? `
                <div>
                    <span class="text-slate-500">Message:</span>
                    <div class="font-semibold">${culte.titre_message}</div>
                </div>
            ` : ''}
        </div>
    `;
}

// Système de notifications toast
function showToast(message, type = 'info', duration = 5000) {
    const container = document.getElementById('toastContainer');
    const toast = document.createElement('div');

    const colors = {
        success: 'bg-green-100 border-green-300 text-green-800',
        error: 'bg-red-100 border-red-300 text-red-800',
        warning: 'bg-yellow-100 border-yellow-300 text-yellow-800',
        info: 'bg-blue-100 border-blue-300 text-blue-800'
    };

    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-triangle',
        warning: 'fa-exclamation-circle',
        info: 'fa-info-circle'
    };

    toast.className = `p-4 rounded-xl border shadow-lg transform translate-x-full transition-transform duration-300 ${colors[type]}`;
    toast.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas ${icons[type]}"></i>
            <span class="flex-1">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-sm hover:opacity-75">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    container.appendChild(toast);

    // Animation d'entrée
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 100);

    // Suppression automatique
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Utilitaires
function formatTime(timeString) {
    try {
        const time = new Date(`1970-01-01T${timeString}`);
        return time.toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'});
    } catch (e) {
        return timeString;
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des événements du statut
    const statutSelect = document.getElementById('nouveau_statut');
    if (statutSelect) {
        statutSelect.addEventListener('change', function() {
            toggleRaisonField();
            toggleParticipantsField();
        });
    }

    // Fermer les modals en cliquant à l'extérieur
    ['statusModal', 'duplicateModal', 'deleteModal', 'culteInfoModal'].forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    eval(`close${modalId.charAt(0).toUpperCase() + modalId.slice(1).replace('Modal', '')}Modal()`);
                }
            });
        }
    });

    // Raccourcis clavier
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Fermer tous les modals ouverts
            ['statusModal', 'duplicateModal', 'deleteModal', 'culteInfoModal'].forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden')) {
                    eval(`close${modalId.charAt(0).toUpperCase() + modalId.slice(1).replace('Modal', '')}Modal()`);
                }
            });
        }
    });
});
</script>
@endpush
