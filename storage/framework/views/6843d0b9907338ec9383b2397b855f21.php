<!-- Modal Annulation de Réunion -->
<div id="annulerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-times-circle text-red-600 mr-3"></i>
                    Annuler la réunion
                </h3>
                <button type="button" onclick="closeAnnulerModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-red-800">Attention</h4>
                        <p class="text-sm text-red-700 mt-1">
                            Cette action annulera définitivement la réunion. Tous les participants inscrits seront automatiquement notifiés.
                        </p>
                    </div>
                </div>
            </div>

            <form id="annulerForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="annuler_reunion_id" name="reunion_id">

                <div class="space-y-6">
                    <!-- Motif d'annulation -->
                    <div>
                        <label for="motif_annulation" class="block text-sm font-medium text-slate-700 mb-2">
                            Motif d'annulation <span class="text-red-500">*</span>
                        </label>
                        <div class="has-error-container">
                            <textarea name="motif_annulation" id="motif_annulation" rows="4" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                                placeholder="Expliquez la raison de l'annulation (problème technique, indisponibilité, report, etc.)"></textarea>
                        </div>
                    </div>

                    <!-- Message aux participants -->
                    <div>
                        <label for="message_participants" class="block text-sm font-medium text-slate-700 mb-2">
                            Message personnalisé aux participants
                        </label>
                        <div class="has-error-container">
                            <textarea name="message_participants" id="message_participants" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                placeholder="Message optionnel à envoyer aux participants (excuses, informations complémentaires, etc.)"></textarea>
                        </div>
                    </div>

                    <!-- Options de notification -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Options de notification</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="notifier_email" name="notifier_email" value="1" checked
                                    class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                                <label for="notifier_email" class="ml-3 text-sm text-slate-700">
                                    Envoyer une notification par email à tous les participants
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="notifier_sms" name="notifier_sms" value="1"
                                    class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                                <label for="notifier_sms" class="ml-3 text-sm text-slate-700">
                                    Envoyer une notification SMS aux participants (si disponible)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="notifier_responsables" name="notifier_responsables" value="1" checked
                                    class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                                <label for="notifier_responsables" class="ml-3 text-sm text-slate-700">
                                    Notifier les autres responsables et organisateurs
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Informations sur les conséquences -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-blue-800 mb-2">Conséquences de l'annulation :</h4>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Tous les participants inscrits seront automatiquement désinscrits</li>
                                    <li>• Les rappels programmés seront annulés</li>
                                    <li>• La réunion apparaîtra comme "Annulée" dans les historiques</li>
                                    <li>• Les éventuels frais d'inscription pourront être remboursés</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Proposer un report comme alternative -->
                    <div class="border-t border-slate-200 pt-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-yellow-800">Alternative :</h4>
                                    <p class="text-sm text-yellow-700">
                                        Plutôt que d'annuler, souhaitez-vous reporter cette réunion à une date ultérieure ?
                                    </p>
                                </div>
                                <button type="button" onclick="switchToReportModal()"
                                    class="px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                                    Reporter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between space-x-3 p-6 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            <button type="button" onclick="closeAnnulerModal()"
                class="flex-1 px-6 py-3 text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </button>
            <button type="button" onclick="confirmerAnnulation()"
                class="flex-1 px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-medium">
                <i class="fas fa-check mr-2"></i>
                Confirmer l'annulation
            </button>
        </div>
    </div>
</div>

<script>
// Variables globales pour le modal d'annulation
let currentReunionToCancel = null;

function openAnnulerModal(reunionId) {
    currentReunionToCancel = reunionId;
    document.getElementById('annuler_reunion_id').value = reunionId;
    document.getElementById('annulerModal').classList.remove('hidden');

    // Focus sur le premier champ
    setTimeout(() => {
        document.getElementById('motif_annulation').focus();
    }, 100);

    // Initialiser CKEditor sur le motif d'annulation après un court délai
    setTimeout(() => {
        if (document.getElementById('motif_annulation') && typeof ClassicEditor !== 'undefined') {
            // Vérifier si CKEditor n'est pas déjà initialisé sur cet élément
            if (!document.querySelector('#motif_annulation + .ck-editor')) {
                initializeCKEditor('#motif_annulation', 'simple', {
                    placeholder: 'Expliquez la raison de l\'annulation...'
                });
            }
        }
    }, 100);
}

function closeAnnulerModal() {
    // Nettoyer l'instance CKEditor si elle existe
    const editorContainer = document.querySelector('#motif_annulation + .ck-editor');
    if (editorContainer && window.CKEditorInstances && window.CKEditorInstances['#motif_annulation']) {
        window.CKEditorInstances['#motif_annulation'].destroy()
            .then(() => {
                delete window.CKEditorInstances['#motif_annulation'];
            })
            .catch(error => {
                console.error('Erreur lors de la destruction de CKEditor:', error);
            });
    }

    document.getElementById('annulerModal').classList.add('hidden');
    document.getElementById('annulerForm').reset();
    currentReunionToCancel = null;
}

function switchToReportModal() {
    closeAnnulerModal();
    // Ouvrir le modal de report avec la même réunion
    if (currentReunionToCancel && typeof openReporterModal === 'function') {
        openReporterModal(currentReunionToCancel);
    }
}

function confirmerAnnulation() {
    // Synchroniser CKEditor avant l'envoi
    if (window.CKEditorInstances && window.CKEditorInstances['#motif_annulation']) {
        const editor = window.CKEditorInstances['#motif_annulation'];
        const textarea = document.getElementById('motif_annulation');
        if (textarea) {
            textarea.value = editor.getData();
        }
    }

    const form = document.getElementById('annulerForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('annuler_reunion_id').value;

    // Validation côté client
    const motif = document.getElementById('motif_annulation').value.trim();
    if (!motif) {
        alert('Veuillez saisir un motif d\'annulation.');
        document.getElementById('motif_annulation').focus();
        return;
    }

    // Confirmation finale
    if (!confirm('Êtes-vous sûr de vouloir annuler cette réunion ? Cette action est irréversible.')) {
        return;
    }

    // Désactiver le bouton pour éviter les double-clics
    const submitButton = document.querySelector('#annulerModal button[onclick="confirmerAnnulation()"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Annulation en cours...';

    fetch(`<?php echo e(route('private.reunions.annuler', ':reunion')); ?>`.replace(':reunion', reunionId), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>",
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
            showSuccessMessage('Réunion annulée avec succès. Les participants ont été notifiés.');

            // Fermer le modal
            closeAnnulerModal();

            // Recharger la page ou rediriger
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'annulation:', error);
        alert(error.message || 'Une erreur est survenue lors de l\'annulation de la réunion');
    })
    .finally(() => {
        // Réactiver le bouton
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

function showSuccessMessage(message) {
    // Créer une notification de succès temporaire
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl shadow-lg z-50 flex items-center';
    notification.innerHTML = `
        <i class="fas fa-check-circle text-green-600 mr-3"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(notification);

    // Animation d'entrée
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100px)';

    setTimeout(() => {
        notification.style.transition = 'all 0.3s ease-out';
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 10);

    // Suppression automatique après 4 secondes
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100px)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('annulerModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAnnulerModal();
    }
});

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('annulerModal').classList.contains('hidden')) {
        closeAnnulerModal();
    }
});
</script>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/reunions/modals/annuler.blade.php ENDPATH**/ ?>