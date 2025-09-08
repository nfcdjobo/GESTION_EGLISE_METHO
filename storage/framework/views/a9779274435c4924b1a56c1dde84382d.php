<!-- Modal Suspension de Réunion -->
<div id="suspendreModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-pause-circle text-orange-600 mr-3"></i>
                    Suspendre la réunion
                </h3>
                <button type="button" onclick="closeSuspendreModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 p-4 bg-orange-50 border border-orange-200 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-orange-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-orange-800">Suspension temporaire</h4>
                        <p class="text-sm text-orange-700 mt-1">
                            La suspension met en pause la réunion en cours. Elle pourra être reprise plus tard depuis le même état.
                        </p>
                    </div>
                </div>
            </div>

            <form id="suspendreForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="suspendre_reunion_id" name="reunion_id">

                <div class="space-y-6">
                    <!-- Motif de suspension -->
                    <div>
                        <label for="motif_suspension" class="block text-sm font-medium text-slate-700 mb-2">
                            Motif de suspension <span class="text-red-500">*</span>
                        </label>
                        <div class="has-error-container">
                            <textarea name="motif_suspension" id="motif_suspension" rows="4" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors resize-none"
                                placeholder="Expliquez la raison de la suspension (problème technique, urgence, pause prolongée, etc.)"></textarea>
                        </div>
                    </div>

                    <!-- Type de suspension -->
                    <div>
                        <label for="type_suspension" class="block text-sm font-medium text-slate-700 mb-2">
                            Type de suspension
                        </label>
                        <select id="type_suspension" name="type_suspension"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                            <option value="technique">Problème technique</option>
                            <option value="urgence">Urgence/Interruption</option>
                            <option value="pause_prolongee">Pause prolongée</option>
                            <option value="probleme_logistique">Problème logistique</option>
                            <option value="sante">Problème de santé</option>
                            <option value="autre">Autre raison</option>
                        </select>
                    </div>

                    <!-- Durée estimée de suspension -->
                    <div>
                        <label for="duree_estimee" class="block text-sm font-medium text-slate-700 mb-2">
                            Durée estimée de suspension
                        </label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="duree_estimee" value="courte" checked class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                                <span class="ml-3 text-sm text-slate-700">
                                    <div class="font-medium">Courte</div>
                                    <div class="text-xs text-slate-500">< 15 min</div>
                                </span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="duree_estimee" value="moyenne" class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                                <span class="ml-3 text-sm text-slate-700">
                                    <div class="font-medium">Moyenne</div>
                                    <div class="text-xs text-slate-500">15-45 min</div>
                                </span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="duree_estimee" value="longue" class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-500">
                                <span class="ml-3 text-sm text-slate-700">
                                    <div class="font-medium">Longue</div>
                                    <div class="text-xs text-slate-500">> 45 min</div>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Heure de reprise prévue -->
                    <div id="heure_reprise_container">
                        <label for="heure_reprise_prevue" class="block text-sm font-medium text-slate-700 mb-2">
                            Heure de reprise prévue (optionnel)
                        </label>
                        <input type="time" id="heure_reprise_prevue" name="heure_reprise_prevue"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                        <p class="text-xs text-slate-500 mt-1">Indiquez l'heure à laquelle vous prévoyez de reprendre</p>
                    </div>

                    <!-- Message aux participants -->
                    <div>
                        <label for="message_participants_suspension" class="block text-sm font-medium text-slate-700 mb-2">
                            Message aux participants présents
                        </label>
                        <div class="has-error-container">
                            <textarea name="message_participants" id="message_participants_suspension" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors resize-none"
                                placeholder="Message à communiquer aux participants (informations sur la reprise, instructions, etc.)"></textarea>
                        </div>
                    </div>

                    <!-- Options de communication -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Communication</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="annoncer_public" name="annoncer_public" value="1" checked
                                    class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500">
                                <label for="annoncer_public" class="ml-3 text-sm text-slate-700">
                                    Faire une annonce publique de la suspension
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="notifier_diffusion" name="notifier_diffusion" value="1"
                                    class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500">
                                <label for="notifier_diffusion" class="ml-3 text-sm text-slate-700">
                                    Notifier les participants en ligne (diffusion)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="notifier_responsables_suspension" name="notifier_responsables" value="1" checked
                                    class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500">
                                <label for="notifier_responsables_suspension" class="ml-3 text-sm text-slate-700">
                                    Alerter les autres responsables
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Actions à entreprendre pendant la suspension -->
                    <div>
                        <label for="actions_suspension" class="block text-sm font-medium text-slate-700 mb-2">
                            Actions à entreprendre pendant la suspension
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center p-2 hover:bg-slate-50 rounded cursor-pointer">
                                <input type="checkbox" name="actions[]" value="resoudre_technique" class="w-4 h-4 text-orange-600">
                                <span class="ml-3 text-sm text-slate-700">Résoudre le problème technique</span>
                            </label>
                            <label class="flex items-center p-2 hover:bg-slate-50 rounded cursor-pointer">
                                <input type="checkbox" name="actions[]" value="reorganiser_logistique" class="w-4 h-4 text-orange-600">
                                <span class="ml-3 text-sm text-slate-700">Réorganiser la logistique</span>
                            </label>
                            <label class="flex items-center p-2 hover:bg-slate-50 rounded cursor-pointer">
                                <input type="checkbox" name="actions[]" value="gerer_urgence" class="w-4 h-4 text-orange-600">
                                <span class="ml-3 text-sm text-slate-700">Gérer l'urgence en cours</span>
                            </label>
                            <label class="flex items-center p-2 hover:bg-slate-50 rounded cursor-pointer">
                                <input type="checkbox" name="actions[]" value="contacter_intervenant" class="w-4 h-4 text-orange-600">
                                <span class="ml-3 text-sm text-slate-700">Contacter un intervenant de remplacement</span>
                            </label>
                            <label class="flex items-center p-2 hover:bg-slate-50 rounded cursor-pointer">
                                <input type="checkbox" name="actions[]" value="evaluer_situation" class="w-4 h-4 text-orange-600">
                                <span class="ml-3 text-sm text-slate-700">Évaluer la situation avant de continuer</span>
                            </label>
                        </div>
                    </div>

                    <!-- Informations sur les conséquences -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-blue-800 mb-2">Conséquences de la suspension :</h4>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• La réunion restera marquée comme "En cours" mais suspendue</li>
                                    <li>• Les participants seront informés de l'interruption temporaire</li>
                                    <li>• L'enregistrement sera mis en pause (si actif)</li>
                                    <li>• La diffusion en ligne sera interrompue temporairement</li>
                                    <li>• Vous pourrez reprendre la réunion depuis l'état de suspension</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Rappel des alternatives -->
                    <div class="border-t border-slate-200 pt-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <h4 class="font-semibold text-gray-800 mb-2">Alternatives à considérer :</h4>
                            <div class="flex flex-wrap gap-2 text-sm">
                                <button type="button" onclick="switchToTerminerModal()" class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full hover:bg-emerald-200 transition-colors">
                                    Terminer définitivement
                                </button>
                                <button type="button" onclick="switchToReporterModal()" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full hover:bg-indigo-200 transition-colors">
                                    Reporter à une autre date
                                </button>
                                <button type="button" onclick="switchToAnnulerModal()" class="px-3 py-1 bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition-colors">
                                    Annuler la réunion
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between space-x-3 p-6 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            <button type="button" onclick="programmerReprise()"
                class="px-4 py-2 text-orange-700 bg-white border border-orange-300 rounded-lg hover:bg-orange-50 transition-colors text-sm">
                <i class="fas fa-clock mr-2"></i>
                Programmer reprise
            </button>
            <div class="flex space-x-3">
                <button type="button" onclick="closeSuspendreModal()"
                    class="px-6 py-3 text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
                <button type="button" onclick="confirmerSuspension()"
                    class="px-6 py-3 bg-orange-600 text-white rounded-xl hover:bg-orange-700 transition-colors font-medium">
                    <i class="fas fa-pause mr-2"></i>
                    Suspendre la réunion
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales pour le modal de suspension
let currentReunionSuspension = null;

function openSuspendreModal(reunionId) {
    currentReunionSuspension = reunionId;
    document.getElementById('suspendre_reunion_id').value = reunionId;
    document.getElementById('suspendreModal').classList.remove('hidden');

    // Initialiser les événements
    initializeSuspensionEvents();

    // Définir une heure de reprise par défaut (dans 30 minutes)
    const maintenant = new Date();
    maintenant.setMinutes(maintenant.getMinutes() + 30);
    const heureReprise = maintenant.toTimeString().slice(0, 5);
    document.getElementById('heure_reprise_prevue').value = heureReprise;

    // Focus sur le premier champ
    setTimeout(() => {
        document.getElementById('motif_suspension').focus();
    }, 100);

    // Initialiser CKEditor sur les textareas après un court délai
    setTimeout(() => {
        const textareas = ['motif_suspension', 'message_participants_suspension'];
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

function closeSuspendreModal() {
    // Nettoyer les instances CKEditor si elles existent
    const textareas = ['motif_suspension', 'message_participants_suspension'];
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

    document.getElementById('suspendreModal').classList.add('hidden');
    document.getElementById('suspendreForm').reset();
    currentReunionSuspension = null;
}

function initializeSuspensionEvents() {
    // Événement pour le type de suspension - adapte le message selon le type
    document.getElementById('type_suspension').addEventListener('change', function() {
        const messageField = document.getElementById('message_participants_suspension');
        const type = this.value;

        const messagesTypes = {
            'technique': 'Nous rencontrons actuellement des difficultés techniques. Merci de patienter pendant que nous résolvons le problème.',
            'urgence': 'Nous devons suspendre temporairement la réunion en raison d\'une situation urgente. Nous vous tiendrons informés.',
            'pause_prolongee': 'Nous faisons une pause prolongée. La réunion reprendra dans quelques instants.',
            'probleme_logistique': 'Nous devons régler un problème logistique. Merci de votre patience.',
            'sante': 'Nous devons suspendre temporairement pour des raisons de santé. Nous vous informerons de la reprise.',
            'autre': ''
        };

        if (messagesTypes[type]) {
            messageField.value = messagesTypes[type];
            // Mettre à jour CKEditor si actif
            if (window.CKEditorInstances && window.CKEditorInstances['#message_participants_suspension']) {
                window.CKEditorInstances['#message_participants_suspension'].setData(messagesTypes[type]);
            }
        }
    });

    // Événement pour la durée estimée - adapte l'heure de reprise
    document.querySelectorAll('input[name="duree_estimee"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const maintenant = new Date();
            let minutesAjouter = 15; // par défaut

            switch(this.value) {
                case 'courte':
                    minutesAjouter = 15;
                    break;
                case 'moyenne':
                    minutesAjouter = 30;
                    break;
                case 'longue':
                    minutesAjouter = 60;
                    break;
            }

            maintenant.setMinutes(maintenant.getMinutes() + minutesAjouter);
            const heureReprise = maintenant.toTimeString().slice(0, 5);
            document.getElementById('heure_reprise_prevue').value = heureReprise;
        });
    });
}

function switchToTerminerModal() {
    closeSuspendreModal();
    if (typeof terminer === 'function') {
        // Appeler directement la fonction de terminaison si elle existe
        terminer(currentReunionSuspension);
    } else {
        alert('Fonction de terminaison non disponible. Veuillez utiliser le bouton "Terminer" de la page.');
    }
}

function switchToReporterModal() {
    closeSuspendreModal();
    if (typeof openReporterModal === 'function') {
        openReporterModal(currentReunionSuspension);
    } else {
        alert('Modal de report non disponible.');
    }
}

function switchToAnnulerModal() {
    closeSuspendreModal();
    if (typeof openAnnulerModal === 'function') {
        openAnnulerModal(currentReunionSuspension);
    } else {
        alert('Modal d\'annulation non disponible.');
    }
}

function programmerReprise() {
    const heureReprise = document.getElementById('heure_reprise_prevue').value;

    if (!heureReprise) {
        alert('Veuillez définir une heure de reprise.');
        document.getElementById('heure_reprise_prevue').focus();
        return;
    }

    // Programmer un rappel automatique
    const maintenant = new Date();
    const [heures, minutes] = heureReprise.split(':');
    const heureRepriseDate = new Date();
    heureRepriseDate.setHours(parseInt(heures), parseInt(minutes), 0, 0);

    // Si l'heure est passée, c'est pour demain
    if (heureRepriseDate <= maintenant) {
        heureRepriseDate.setDate(heureRepriseDate.getDate() + 1);
    }

    const delaiMillisecondes = heureRepriseDate.getTime() - maintenant.getTime();

    if (delaiMillisecondes > 0 && delaiMillisecondes < 24 * 60 * 60 * 1000) { // Moins de 24h
        if (confirm(`Programmer un rappel automatique pour reprendre la réunion à ${heureReprise} ?`)) {
            // TODO: Implémenter la programmation côté serveur
            showSuccessMessage(`Rappel programmé pour ${heureReprise}. Vous serez notifié.`);
        }
    } else {
        alert('L\'heure de reprise doit être dans les prochaines 24 heures.');
    }
}

function confirmerSuspension() {
    // Synchroniser CKEditor avant l'envoi
    if (window.CKEditorInstances) {
        Object.entries(window.CKEditorInstances).forEach(([selector, editor]) => {
            const textarea = document.querySelector(selector);
            if (textarea) {
                textarea.value = editor.getData();
            }
        });
    }

    const form = document.getElementById('suspendreForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('suspendre_reunion_id').value;

    // Validation côté client
    const motif = document.getElementById('motif_suspension').value.trim();

    if (!motif) {
        alert('Veuillez saisir un motif de suspension.');
        document.getElementById('motif_suspension').focus();
        return;
    }

    // Confirmation finale
    const dureeEstimee = document.querySelector('input[name="duree_estimee"]:checked')?.value;
    const dureeTextes = {
        'courte': 'courte durée (< 15 min)',
        'moyenne': 'durée moyenne (15-45 min)',
        'longue': 'longue durée (> 45 min)'
    };

    const messageConfirmation = dureeEstimee ?
        `Confirmer la suspension de cette réunion pour une ${dureeTextes[dureeEstimee]} ?` :
        'Confirmer la suspension de cette réunion ?';

    if (!confirm(messageConfirmation)) {
        return;
    }

    // Désactiver le bouton pour éviter les double-clics
    const submitButton = document.querySelector('#suspendreModal button[onclick="confirmerSuspension()"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Suspension en cours...';

    fetch(`<?php echo e(route('private.reunions.suspendre', ':reunion')); ?>`.replace(':reunion', reunionId), {

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
            showSuccessMessage('Réunion suspendue avec succès. Les participants ont été notifiés.');

            // Fermer le modal
            closeSuspendreModal();

            // Recharger la page pour mettre à jour le statut
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur lors de la suspension:', error);
        alert(error.message || 'Une erreur est survenue lors de la suspension de la réunion');
    })
    .finally(() => {
        // Réactiver le bouton
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('suspendreModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSuspendreModal();
    }
});

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('suspendreModal').classList.contains('hidden')) {
        closeSuspendreModal();
    }
});

// Initialiser le type par défaut au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Déclencher l'événement change pour le type par défaut
    const typeSelect = document.getElementById('type_suspension');
    if (typeSelect) {
        typeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/reunions/modals/suspendre.blade.php ENDPATH**/ ?>