<!-- Modal Report de Réunion -->
<div id="reporterModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-calendar-alt text-indigo-600 mr-3"></i>
                    Reporter la réunion
                </h3>
                <button type="button" onclick="closeReporterModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-indigo-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-indigo-800">Information</h4>
                        <p class="text-sm text-indigo-700 mt-1">
                            Le report permettra de déplacer la réunion à une nouvelle date tout en conservant les inscriptions actuelles.
                        </p>
                    </div>
                </div>
            </div>

            <form id="reporterForm">
                @csrf
                <input type="hidden" id="reporter_reunion_id" name="reunion_id">

                <div class="space-y-6">
                    <!-- Nouvelle date -->
                    <div>
                        <label for="nouvelle_date" class="block text-sm font-medium text-slate-700 mb-2">
                            Nouvelle date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="nouvelle_date" name="nouvelle_date" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <p class="text-xs text-slate-500 mt-1">La nouvelle date doit être dans le futur</p>
                    </div>

                    <!-- Nouvelle heure -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="nouvelle_heure_debut" class="block text-sm font-medium text-slate-700 mb-2">
                                Nouvelle heure de début
                            </label>
                            <input type="time" id="nouvelle_heure_debut" name="nouvelle_heure_debut"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                            <p class="text-xs text-slate-500 mt-1">Laisser vide pour conserver l'heure actuelle</p>
                        </div>

                        <div>
                            <label for="nouvelle_heure_fin" class="block text-sm font-medium text-slate-700 mb-2">
                                Nouvelle heure de fin
                            </label>
                            <input type="time" id="nouvelle_heure_fin" name="nouvelle_heure_fin"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>
                    </div>

                    <!-- Motif du report -->
                    <div>
                        <label for="motif_report" class="block text-sm font-medium text-slate-700 mb-2">
                            Motif du report <span class="text-red-500">*</span>
                        </label>
                        <div class="has-error-container">
                            <textarea name="motif_annulation" id="motif_report" rows="3" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"
                                placeholder="Expliquez la raison du report (conflit d'agenda, problème technique, etc.)"></textarea>
                        </div>
                    </div>

                    <!-- Message aux participants -->
                    <div>
                        <label for="message_participants_report" class="block text-sm font-medium text-slate-700 mb-2">
                            Message aux participants
                        </label>
                        <div class="has-error-container">
                            <textarea name="message_participants" id="message_participants_report" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"
                                placeholder="Message optionnel expliquant le changement de date aux participants"></textarea>
                        </div>
                    </div>

                    <!-- Options du report -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Options du report</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="conserver_inscriptions" name="conserver_inscriptions" value="1" checked
                                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="conserver_inscriptions" class="ml-3 text-sm text-slate-700">
                                    Conserver toutes les inscriptions actuelles
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="permettre_desinscription" name="permettre_desinscription" value="1" checked
                                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="permettre_desinscription" class="ml-3 text-sm text-slate-700">
                                    Permettre aux participants de se désinscrire après notification
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="rouvrir_inscriptions" name="rouvrir_inscriptions" value="1"
                                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="rouvrir_inscriptions" class="ml-3 text-sm text-slate-700">
                                    Rouvrir les inscriptions pour de nouveaux participants
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Options de notification -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Notifications</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="notifier_email_report" name="notifier_email" value="1" checked
                                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="notifier_email_report" class="ml-3 text-sm text-slate-700">
                                    Notifier par email tous les participants inscrits
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="notifier_calendrier" name="notifier_calendrier" value="1" checked
                                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="notifier_calendrier" class="ml-3 text-sm text-slate-700">
                                    Mettre à jour les invitations calendrier automatiquement
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="programmer_rappels" name="programmer_rappels" value="1" checked
                                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="programmer_rappels" class="ml-3 text-sm text-slate-700">
                                    Reprogrammer les rappels pour la nouvelle date
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Aperçu du changement -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-calendar-check text-blue-600 mr-3 mt-1"></i>
                            <div class="flex-1">
                                <h4 class="font-semibold text-blue-800 mb-2">Aperçu du changement</h4>
                                <div class="text-sm text-blue-700 space-y-1">
                                    <div class="flex justify-between">
                                        <span>Date actuelle :</span>
                                        <span id="date_actuelle_preview" class="font-medium">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Nouvelle date :</span>
                                        <span id="nouvelle_date_preview" class="font-medium text-green-700">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Participants inscrits :</span>
                                        <span id="participants_count_preview" class="font-medium">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Suggestion de calendrier -->
                    <div class="border border-slate-200 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Suggestions de dates</h4>
                        <div class="grid grid-cols-3 gap-2 text-sm">
                            <button type="button" onclick="setSuggestedDate(7)" class="p-2 text-left bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                <div class="font-medium">Dans 1 semaine</div>
                                <div class="text-slate-600" id="suggestion_1_week">-</div>
                            </button>
                            <button type="button" onclick="setSuggestedDate(14)" class="p-2 text-left bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                <div class="font-medium">Dans 2 semaines</div>
                                <div class="text-slate-600" id="suggestion_2_weeks">-</div>
                            </button>
                            <button type="button" onclick="setSuggestedDate(30)" class="p-2 text-left bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                <div class="font-medium">Dans 1 mois</div>
                                <div class="text-slate-600" id="suggestion_1_month">-</div>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between space-x-3 p-6 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            <button type="button" onclick="closeReporterModal()"
                class="flex-1 px-6 py-3 text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </button>
            <button type="button" onclick="confirmerReport()"
                class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                <i class="fas fa-calendar-alt mr-2"></i>
                Confirmer le report
            </button>
        </div>
    </div>
</div>

<script>
// Variables globales pour le modal de report
let currentReunionToPostpone = null;
let currentReunionData = null;

function openReporterModal(reunionId, reunionData = null) {
    currentReunionToPostpone = reunionId;
    currentReunionData = reunionData;

    document.getElementById('reporter_reunion_id').value = reunionId;
    document.getElementById('reporterModal').classList.remove('hidden');

    // Initialiser les suggestions de dates
    initializeDateSuggestions();

    // Remplir l'aperçu si on a les données
    if (reunionData) {
        updatePreview();
    }

    // Focus sur le premier champ
    setTimeout(() => {
        document.getElementById('nouvelle_date').focus();
    }, 100);

    // Initialiser CKEditor sur le motif après un court délai
    setTimeout(() => {
        if (document.getElementById('motif_report') && typeof ClassicEditor !== 'undefined') {
            if (!document.querySelector('#motif_report + .ck-editor')) {
                initializeCKEditor('#motif_report', 'simple', {
                    placeholder: 'Expliquez la raison du report...'
                });
            }
        }
    }, 100);
}

function closeReporterModal() {
    // Nettoyer l'instance CKEditor si elle existe
    const editorContainer = document.querySelector('#motif_report + .ck-editor');
    if (editorContainer && window.CKEditorInstances && window.CKEditorInstances['#motif_report']) {
        window.CKEditorInstances['#motif_report'].destroy()
            .then(() => {
                delete window.CKEditorInstances['#motif_report'];
            })
            .catch(error => {
                console.error('Erreur lors de la destruction de CKEditor:', error);
            });
    }

    document.getElementById('reporterModal').classList.add('hidden');
    document.getElementById('reporterForm').reset();
    currentReunionToPostpone = null;
    currentReunionData = null;
}

function initializeDateSuggestions() {
    const today = new Date();

    // Calculer les suggestions
    const oneWeek = new Date(today);
    oneWeek.setDate(today.getDate() + 7);

    const twoWeeks = new Date(today);
    twoWeeks.setDate(today.getDate() + 14);

    const oneMonth = new Date(today);
    oneMonth.setDate(today.getDate() + 30);

    // Formater et afficher
    document.getElementById('suggestion_1_week').textContent = formatDateForDisplay(oneWeek);
    document.getElementById('suggestion_2_weeks').textContent = formatDateForDisplay(twoWeeks);
    document.getElementById('suggestion_1_month').textContent = formatDateForDisplay(oneMonth);
}

function setSuggestedDate(daysFromNow) {
    const suggestedDate = new Date();
    suggestedDate.setDate(suggestedDate.getDate() + daysFromNow);

    document.getElementById('nouvelle_date').value = suggestedDate.toISOString().split('T')[0];
    updatePreview();
}

function updatePreview() {
    const nouvelleDate = document.getElementById('nouvelle_date').value;

    if (nouvelleDate) {
        const dateObj = new Date(nouvelleDate);
        document.getElementById('nouvelle_date_preview').textContent = formatDateForDisplay(dateObj);
    } else {
        document.getElementById('nouvelle_date_preview').textContent = 'Non définie';
    }

    // Mettre à jour les autres infos si disponibles
    if (currentReunionData) {
        document.getElementById('date_actuelle_preview').textContent =
            formatDateForDisplay(new Date(currentReunionData.date_reunion));
        document.getElementById('participants_count_preview').textContent =
            currentReunionData.nombre_inscrits || '0';
    }
}

function formatDateForDisplay(date) {
    return date.toLocaleDateString('fr-FR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function confirmerReport() {
    // Synchroniser CKEditor avant l'envoi
    if (window.CKEditorInstances && window.CKEditorInstances['#motif_report']) {
        const editor = window.CKEditorInstances['#motif_report'];
        const textarea = document.getElementById('motif_report');
        if (textarea) {
            textarea.value = editor.getData();
        }
    }

    const form = document.getElementById('reporterForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('reporter_reunion_id').value;

    // Validation côté client
    const nouvelleDate = document.getElementById('nouvelle_date').value;
    const motif = document.getElementById('motif_report').value.trim();

    if (!nouvelleDate) {
        alert('Veuillez sélectionner une nouvelle date.');
        document.getElementById('nouvelle_date').focus();
        return;
    }

    if (!motif) {
        alert('Veuillez saisir un motif de report.');
        document.getElementById('motif_report').focus();
        return;
    }

    // Vérifier que la nouvelle date est dans le futur
    const selectedDate = new Date(nouvelleDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate <= today) {
        alert('La nouvelle date doit être dans le futur.');
        document.getElementById('nouvelle_date').focus();
        return;
    }

    // Confirmation finale
    const formattedDate = formatDateForDisplay(selectedDate);
    if (!confirm(`Confirmer le report de cette réunion au ${formattedDate} ?`)) {
        return;
    }

    // Désactiver le bouton pour éviter les double-clics
    const submitButton = document.querySelector('#reporterModal button[onclick="confirmerReport()"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Report en cours...';

    fetch(`{{route('private.reunions.reporter', ':reunion')}}`.replace(':reunion', reunionId), {

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
            showSuccessMessage(`Réunion reportée avec succès au ${formattedDate}. Les participants ont été notifiés.`);

            // Fermer le modal
            closeReporterModal();

            // Recharger la page ou rediriger
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur lors du report:', error);
        alert(error.message || 'Une erreur est survenue lors du report de la réunion');
    })
    .finally(() => {
        // Réactiver le bouton
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

// Écouteurs d'événements
document.getElementById('nouvelle_date').addEventListener('change', updatePreview);

// Fermer le modal en cliquant à l'extérieur
document.getElementById('reporterModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReporterModal();
    }
});

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('reporterModal').classList.contains('hidden')) {
        closeReporterModal();
    }
});

// Validation des heures
document.getElementById('nouvelle_heure_debut').addEventListener('change', function() {
    const heureDebut = this.value;
    const heureFin = document.getElementById('nouvelle_heure_fin').value;

    if (heureDebut && heureFin && heureDebut >= heureFin) {
        alert('L\'heure de fin doit être postérieure à l\'heure de début.');
        document.getElementById('nouvelle_heure_fin').focus();
    }
});

document.getElementById('nouvelle_heure_fin').addEventListener('change', function() {
    const heureDebut = document.getElementById('nouvelle_heure_debut').value;
    const heureFin = this.value;

    if (heureDebut && heureFin && heureDebut >= heureFin) {
        alert('L\'heure de fin doit être postérieure à l\'heure de début.');
        this.focus();
    }
});
</script>
