<!-- Modal Demande de Prière -->
<div id="priereModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-praying-hands text-green-600 mr-3"></i>
                    Ajouter une demande de prière
                </h3>
                <button type="button" onclick="closePriereModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-green-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-green-800">Demande de prière</h4>
                        <p class="text-sm text-green-700 mt-1">
                            Recueillez et enregistrez les demandes de prière exprimées pendant la réunion.
                        </p>
                    </div>
                </div>
            </div>

            <form id="priereForm">
                @csrf
                <input type="hidden" id="priere_reunion_id" name="reunion_id">

                <div class="space-y-6">
                    <!-- Demande de prière -->
                    <div>
                        <label for="demande_priere" class="block text-sm font-medium text-slate-700 mb-2">
                            Demande de prière <span class="text-red-500">*</span>
                        </label>
                        <div class="has-error-container">
                            <textarea id="demande_priere" name="demande" rows="4" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"
                                placeholder="Décrivez la demande de prière (guérison, situation personnelle, famille, etc.)"></textarea>
                        </div>
                    </div>

                    <!-- Demandeur -->
                    <div>
                        <label for="demandeur_priere" class="block text-sm font-medium text-slate-700 mb-2">
                            Demandeur (optionnel)
                        </label>
                        <input type="text" id="demandeur_priere" name="demandeur" maxlength="200"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                            placeholder="Nom de la personne qui fait la demande">
                        <p class="text-xs text-slate-500 mt-1">Laisser vide pour une demande anonyme</p>
                    </div>

                    <!-- Type de demande -->
                    <div>
                        <label for="type_demande_priere" class="block text-sm font-medium text-slate-700 mb-2">
                            Type de demande
                        </label>
                        <select id="type_demande_priere" name="type_demande"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <option value="guerison">Guérison</option>
                            <option value="famille">Famille</option>
                            <option value="travail">Travail/Finances</option>
                            <option value="salut">Salut d'une âme</option>
                            <option value="direction">Direction divine</option>
                            <option value="protection">Protection</option>
                            <option value="reconciliation">Réconciliation</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>

                    <!-- Niveau d'urgence -->
                    <div>
                        <label for="urgence_priere" class="block text-sm font-medium text-slate-700 mb-2">
                            Niveau d'urgence
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="urgence" value="normale" checked class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                <span class="ml-3 text-sm font-medium text-slate-700">Normale</span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="urgence" value="importante" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                <span class="ml-3 text-sm font-medium text-slate-700">Importante</span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="urgence" value="urgente" class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                                <span class="ml-3 text-sm font-medium text-slate-700">Urgente</span>
                            </label>
                        </div>
                    </div>

                    <!-- Options de confidentialité -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Options de confidentialité</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="confidentiel_priere" name="confidentiel" value="1"
                                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                <label for="confidentiel_priere" class="ml-3 text-sm text-slate-700">
                                    <strong>Demande confidentielle</strong> - Ne partager qu'avec les responsables
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="anonyme_priere" name="anonyme" value="1"
                                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                <label for="anonyme_priere" class="ml-3 text-sm text-slate-700">
                                    Garder l'identité du demandeur anonyme
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="suivi_priere" name="suivi_requis" value="1" checked
                                    class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                <label for="suivi_priere" class="ml-3 text-sm text-slate-700">
                                    Demande un suivi et des nouvelles
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Contact pour le suivi -->
                    <div id="contact_suivi_container" class="space-y-4">
                        <div>
                            <label for="contact_suivi" class="block text-sm font-medium text-slate-700 mb-2">
                                Contact pour le suivi (optionnel)
                            </label>
                            <input type="text" id="contact_suivi" name="contact_suivi" maxlength="200"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                placeholder="Email ou téléphone pour les nouvelles">
                        </div>

                        <div>
                            <label for="methode_contact" class="block text-sm font-medium text-slate-700 mb-2">
                                Méthode de contact préférée
                            </label>
                            <select id="methode_contact" name="methode_contact"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                                <option value="email">Email</option>
                                <option value="telephone">Téléphone</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="visite">Visite personnelle</option>
                            </select>
                        </div>
                    </div>

                    <!-- Instructions complémentaires -->
                    <div>
                        <label for="instructions_priere" class="block text-sm font-medium text-slate-700 mb-2">
                            Instructions complémentaires
                        </label>
                        <textarea id="instructions_priere" name="instructions" rows="3"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"
                            placeholder="Informations supplémentaires, contexte, actions à entreprendre..."></textarea>
                    </div>

                    <!-- Aperçu de la demande -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-eye text-blue-600 mr-3 mt-1"></i>
                            <div class="flex-1">
                                <h4 class="font-semibold text-blue-800 mb-2">Aperçu de la demande</h4>
                                <div class="text-sm text-blue-700 space-y-1">
                                    <div class="flex justify-between">
                                        <span>Type :</span>
                                        <span id="preview_type" class="font-medium">Guérison</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Urgence :</span>
                                        <span id="preview_urgence" class="font-medium">Normale</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Statut :</span>
                                        <span id="preview_statut" class="font-medium">Public</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between space-x-3 p-6 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            <button type="button" onclick="ajouterAutrePriere()"
                class="px-4 py-2 text-green-700 bg-white border border-green-300 rounded-lg hover:bg-green-50 transition-colors text-sm">
                <i class="fas fa-plus mr-2"></i>
                Ajouter une autre
            </button>
            <div class="flex space-x-3">
                <button type="button" onclick="closePriereModal()"
                    class="px-6 py-3 text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
                <button type="button" onclick="enregistrerDemandesPriere()"
                    class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-medium">
                    <i class="fas fa-praying-hands mr-2"></i>
                    Enregistrer la demande
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales pour le modal de prière
let currentReunionPriere = null;
let demandesCollectees = [];

function openPriereModal(reunionId) {
    currentReunionPriere = reunionId;
    document.getElementById('priere_reunion_id').value = reunionId;
    document.getElementById('priereModal').classList.remove('hidden');

    // Initialiser les événements
    initializePriereEvents();

    // Focus sur le premier champ
    setTimeout(() => {
        document.getElementById('demande_priere').focus();
    }, 100);

    // Initialiser CKEditor sur les textareas après un court délai
    setTimeout(() => {
        const textareas = ['demande_priere', 'instructions_priere'];
        textareas.forEach(id => {
            const element = document.getElementById(id);
            if (element && typeof ClassicEditor !== 'undefined') {
                if (!document.querySelector(`#${id} + .ck-editor`)) {
                    initializeCKEditor(`#${id}`, 'simple', {
                        placeholder: element.getAttribute('placeholder')
                    });
                }
            }
        });
    }, 100);
}

function closePriereModal() {
    // Nettoyer les instances CKEditor si elles existent
    const textareas = ['demande_priere', 'instructions_priere'];
    textareas.forEach(id => {
        const editorContainer = document.querySelector(`#${id} + .ck-editor`);
        if (editorContainer && window.CKEditorInstances && window.CKEditorInstances[`#${id}`]) {
            window.CKEditorInstances[`#${id}`].destroy()
                .then(() => {
                    delete window.CKEditorInstances[`#${id}`];
                })
                .catch(error => {
                    console.error('Erreur lors de la destruction de CKEditor:', error);
                });
        }
    });

    document.getElementById('priereModal').classList.add('hidden');
    document.getElementById('priereForm').reset();
    currentReunionPriere = null;
    demandesCollectees = [];
    updatePrierePreview();
}

function initializePriereEvents() {
    // Événements pour la mise à jour de l'aperçu
    document.getElementById('type_demande_priere').addEventListener('change', updatePrierePreview);
    document.querySelectorAll('input[name="urgence"]').forEach(radio => {
        radio.addEventListener('change', updatePrierePreview);
    });
    document.getElementById('confidentiel_priere').addEventListener('change', updatePrierePreview);
    document.getElementById('anonyme_priere').addEventListener('change', updatePrierePreview);

    // Gestion du conteneur de contact
    document.getElementById('suivi_priere').addEventListener('change', function() {
        const container = document.getElementById('contact_suivi_container');
        if (this.checked) {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    });

    // Synchronisation anonyme et demandeur
    document.getElementById('anonyme_priere').addEventListener('change', function() {
        const demandeurField = document.getElementById('demandeur_priere');
        if (this.checked) {
            demandeurField.value = '';
            demandeurField.disabled = true;
            demandeurField.placeholder = 'Demande anonyme';
        } else {
            demandeurField.disabled = false;
            demandeurField.placeholder = 'Nom de la personne qui fait la demande';
        }
    });
}

function updatePrierePreview() {
    const type = document.getElementById('type_demande_priere').value;
    const urgence = document.querySelector('input[name="urgence"]:checked')?.value || 'normale';
    const confidentiel = document.getElementById('confidentiel_priere').checked;
    const anonyme = document.getElementById('anonyme_priere').checked;

    // Mise à jour des labels
    const typeLabels = {
        'guerison': 'Guérison',
        'famille': 'Famille',
        'travail': 'Travail/Finances',
        'salut': 'Salut d\'une âme',
        'direction': 'Direction divine',
        'protection': 'Protection',
        'reconciliation': 'Réconciliation',
        'autre': 'Autre'
    };

    const urgenceLabels = {
        'normale': 'Normale',
        'importante': 'Importante',
        'urgente': 'Urgente'
    };

    document.getElementById('preview_type').textContent = typeLabels[type];
    document.getElementById('preview_urgence').textContent = urgenceLabels[urgence];

    let statut = 'Public';
    if (confidentiel) {
        statut = 'Confidentiel';
    }
    if (anonyme) {
        statut += ' - Anonyme';
    }

    document.getElementById('preview_statut').textContent = statut;
}

function ajouterAutrePriere() {
    // Sauvegarder la demande actuelle
    const formData = collectPriereData();
    if (formData.demande.trim()) {
        demandesCollectees.push(formData);

        // Réinitialiser le formulaire
        document.getElementById('priereForm').reset();
        updatePrierePreview();

        // Focus sur le nouveau champ
        document.getElementById('demande_priere').focus();

        showSuccessMessage(`${demandesCollectees.length} demande(s) collectée(s). Ajoutez-en une autre ou enregistrez.`);
    } else {
        alert('Veuillez saisir une demande de prière avant d\'en ajouter une nouvelle.');
        document.getElementById('demande_priere').focus();
    }
}

function collectPriereData() {
    // Synchroniser CKEditor avant collecte
    if (window.CKEditorInstances) {
        Object.entries(window.CKEditorInstances).forEach(([selector, editor]) => {
            const textarea = document.querySelector(selector);
            if (textarea) {
                textarea.value = editor.getData();
            }
        });
    }

    return {
        demande: document.getElementById('demande_priere').value.trim(),
        demandeur: document.getElementById('demandeur_priere').value.trim(),
        type_demande: document.getElementById('type_demande_priere').value,
        urgence: document.querySelector('input[name="urgence"]:checked')?.value || 'normale',
        confidentiel: document.getElementById('confidentiel_priere').checked,
        anonyme: document.getElementById('anonyme_priere').checked,
        suivi_requis: document.getElementById('suivi_priere').checked,
        contact_suivi: document.getElementById('contact_suivi').value.trim(),
        methode_contact: document.getElementById('methode_contact').value,
        instructions: document.getElementById('instructions_priere').value.trim(),
        date_creation: new Date().toISOString(),
        cree_par: 'current_user' // À remplacer par l'membres connecté
    };
}

function enregistrerDemandesPriere() {
    // Synchroniser CKEditor avant l'envoi
    if (window.CKEditorInstances) {
        Object.entries(window.CKEditorInstances).forEach(([selector, editor]) => {
            const textarea = document.querySelector(selector);
            if (textarea) {
                textarea.value = editor.getData();
            }
        });
    }

    // Ajouter la demande actuelle si elle n'est pas vide
    const demandeActuelle = collectPriereData();
    if (demandeActuelle.demande.trim()) {
        demandesCollectees.push(demandeActuelle);
    }

    const reunionId = document.getElementById('priere_reunion_id').value;

    // Validation côté client
    if (demandesCollectees.length === 0) {
        alert('Veuillez saisir au moins une demande de prière.');
        document.getElementById('demande_priere').focus();
        return;
    }

    // Confirmation finale
    const message = demandesCollectees.length === 1 ?
        'Confirmer l\'enregistrement de cette demande de prière ?' :
        `Confirmer l\'enregistrement de ces ${demandesCollectees.length} demandes de prière ?`;

    if (!confirm(message)) {
        return;
    }

    // Désactiver le bouton pour éviter les double-clics
    const submitButton = document.querySelector('#priereModal button[onclick="enregistrerDemandesPriere()"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...';

    // Préparer les données pour l'envoi
    const formData = new FormData();
    formData.append('reunion_id', reunionId);
    formData.append('demandes', JSON.stringify(demandesCollectees));

    fetch(`{{route('private.reunions.demandes-priere', ':reunion')}}`.replace(':reunion', reunionId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success || data.message?.includes('succès')) {
            // Afficher un message de succès
            const nombre = demandesCollectees.length;
            showSuccessMessage(`${nombre} demande(s) de prière enregistrée(s) avec succès.`);

            // Fermer le modal
            closePriereModal();

            // Recharger la page pour mettre à jour les données
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'enregistrement des demandes de prière:', error);
        alert(error.message || 'Une erreur est survenue lors de l\'enregistrement des demandes de prière');
    })
    .finally(() => {
        // Réactiver le bouton
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('priereModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePriereModal();
    }
});

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('priereModal').classList.contains('hidden')) {
        closePriereModal();
    }
});

// Initialisation par défaut
document.addEventListener('DOMContentLoaded', function() {
    updatePrierePreview();
});
</script>
