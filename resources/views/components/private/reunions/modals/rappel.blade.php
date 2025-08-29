<!-- Modal Envoi de Rappel -->
<div id="rappelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-bell text-cyan-600 mr-3"></i>
                    Envoyer un rappel
                </h3>
                <button type="button" onclick="closeRappelModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 p-4 bg-cyan-50 border border-cyan-200 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-cyan-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-cyan-800">Rappel aux participants</h4>
                        <p class="text-sm text-cyan-700 mt-1">
                            Envoyez un rappel personnalisé aux participants inscrits à cette réunion.
                        </p>
                    </div>
                </div>
            </div>

            <form id="rappelForm">
                @csrf
                <input type="hidden" id="rappel_reunion_id" name="reunion_id">

                <div class="space-y-6">
                    <!-- Type de rappel -->
                    <div>
                        <label for="type_rappel" class="block text-sm font-medium text-slate-700 mb-2">
                            Type de rappel <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <label class="flex items-center p-4 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="type_rappel" value="1_semaine" class="w-4 h-4 text-cyan-600 border-gray-300 focus:ring-cyan-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Rappel J-7</div>
                                    <div class="text-xs text-slate-500">Une semaine avant</div>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="type_rappel" value="1_jour" class="w-4 h-4 text-cyan-600 border-gray-300 focus:ring-cyan-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Rappel J-1</div>
                                    <div class="text-xs text-slate-500">La veille</div>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="type_rappel" value="personnalise" checked class="w-4 h-4 text-cyan-600 border-gray-300 focus:ring-cyan-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Personnalisé</div>
                                    <div class="text-xs text-slate-500">Message libre</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Objet du rappel -->
                    <div>
                        <label for="objet_rappel" class="block text-sm font-medium text-slate-700 mb-2">
                            Objet du message <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="objet_rappel" name="objet" required maxlength="200"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-colors"
                            placeholder="Rappel : Réunion de [Titre] - [Date]">
                        <p class="text-xs text-slate-500 mt-1">L'objet sera automatiquement généré selon le type de rappel choisi</p>
                    </div>

                    <!-- Message personnalisé -->
                    <div id="message_personnalise_container">
                        <label for="message_rappel" class="block text-sm font-medium text-slate-700 mb-2">
                            Message personnalisé <span class="text-red-500">*</span>
                        </label>
                        <div class="has-error-container">
                            <textarea id="message_rappel" name="message_personnalise" rows="6" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-colors resize-none"
                                placeholder="Rédigez votre message de rappel personnalisé..."></textarea>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Les variables {titre}, {date}, {heure}, {lieu} seront automatiquement remplacées</p>
                    </div>

                    <!-- Canaux de notification -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Canaux de notification</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="checkbox" id="envoyer_email" name="canaux[]" value="email" checked
                                        class="w-4 h-4 text-cyan-600 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500">
                                    <label for="envoyer_email" class="ml-3 text-sm text-slate-700">
                                        <i class="fas fa-envelope text-blue-600 mr-2"></i>
                                        <strong>Email</strong> - Notification par email
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="envoyer_sms" name="canaux[]" value="sms"
                                        class="w-4 h-4 text-cyan-600 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500">
                                    <label for="envoyer_sms" class="ml-3 text-sm text-slate-700">
                                        <i class="fas fa-sms text-green-600 mr-2"></i>
                                        <strong>SMS</strong> - Message texte (si disponible)
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="checkbox" id="envoyer_whatsapp" name="canaux[]" value="whatsapp"
                                        class="w-4 h-4 text-cyan-600 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500">
                                    <label for="envoyer_whatsapp" class="ml-3 text-sm text-slate-700">
                                        <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                                        <strong>WhatsApp</strong> - Message WhatsApp
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="notification_app" name="canaux[]" value="app"
                                        class="w-4 h-4 text-cyan-600 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500">
                                    <label for="notification_app" class="ml-3 text-sm text-slate-700">
                                        <i class="fas fa-mobile-alt text-purple-600 mr-2"></i>
                                        <strong>App</strong> - Notification push dans l'app
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Destinataires -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">
                            Destinataires <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="destinataires" value="tous_inscrits" checked class="w-4 h-4 text-cyan-600 border-gray-300 focus:ring-cyan-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Tous les participants inscrits</div>
                                    <div class="text-xs text-slate-500">Envoyer à tous les inscrits (<span id="nombre_inscrits">0</span> personnes)</div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="destinataires" value="responsables" class="w-4 h-4 text-cyan-600 border-gray-300 focus:ring-cyan-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Responsables uniquement</div>
                                    <div class="text-xs text-slate-500">Organisateur, animateur, équipe technique</div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="destinataires" value="liste_attente" class="w-4 h-4 text-cyan-600 border-gray-300 focus:ring-cyan-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Liste d'attente uniquement</div>
                                    <div class="text-xs text-slate-500">Personnes en liste d'attente</div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="destinataires" value="personnalise" class="w-4 h-4 text-cyan-600 border-gray-300 focus:ring-cyan-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Sélection personnalisée</div>
                                    <div class="text-xs text-slate-500">Choisir manuellement les destinataires</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Sélection personnalisée des destinataires -->
                    <div id="destinataires_personnalises" class="hidden space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Sélectionner les destinataires
                            </label>
                            <div class="border border-slate-300 rounded-xl p-4 max-h-40 overflow-y-auto">
                                <div class="space-y-2" id="liste_destinataires">
                                    <!-- Les participants seront chargés dynamiquement -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Options avancées -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Options avancées</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="programmation_rappel" name="programmer" value="1"
                                    class="w-4 h-4 text-cyan-600 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500">
                                <label for="programmation_rappel" class="ml-3 text-sm text-slate-700">
                                    Programmer l'envoi à une date/heure précise
                                </label>
                            </div>
                            <div id="programmation_container" class="hidden grid grid-cols-2 gap-4 mt-3">
                                <div>
                                    <label for="date_programmee" class="block text-xs font-medium text-slate-600 mb-1">Date d'envoi</label>
                                    <input type="date" id="date_programmee" name="date_programmee"
                                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                                </div>
                                <div>
                                    <label for="heure_programmee" class="block text-xs font-medium text-slate-600 mb-1">Heure d'envoi</label>
                                    <input type="time" id="heure_programmee" name="heure_programmee"
                                        class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                                </div>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="inclure_pieces_jointes" name="inclure_pieces_jointes" value="1"
                                    class="w-4 h-4 text-cyan-600 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500">
                                <label for="inclure_pieces_jointes" class="ml-3 text-sm text-slate-700">
                                    Inclure les documents de la réunion en pièces jointes
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="demander_confirmation" name="demander_confirmation" value="1"
                                    class="w-4 h-4 text-cyan-600 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500">
                                <label for="demander_confirmation" class="ml-3 text-sm text-slate-700">
                                    Demander une confirmation de présence
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="inclure_calendrier" name="inclure_calendrier" value="1" checked
                                    class="w-4 h-4 text-cyan-600 bg-gray-100 border-gray-300 rounded focus:ring-cyan-500">
                                <label for="inclure_calendrier" class="ml-3 text-sm text-slate-700">
                                    Inclure un fichier iCal pour ajouter au calendrier
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Aperçu du message -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-eye text-blue-600 mr-3 mt-1"></i>
                            <div class="flex-1">
                                <h4 class="font-semibold text-blue-800 mb-2">Aperçu du message</h4>
                                <div class="bg-white border rounded-lg p-3 text-sm">
                                    <div class="border-b pb-2 mb-2">
                                        <strong>Objet :</strong> <span id="preview_objet">-</span>
                                    </div>
                                    <div class="text-slate-700" id="preview_message">
                                        Sélectionnez un type de rappel pour voir l'aperçu...
                                    </div>
                                </div>
                                <div class="mt-2 text-xs text-blue-600">
                                    <strong>Destinataires :</strong> <span id="preview_destinataires">Tous les inscrits</span><br>
                                    <strong>Canaux :</strong> <span id="preview_canaux">Email</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historique des rappels -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <h4 class="font-semibold text-gray-800 mb-3">Historique des rappels</h4>
                        <div class="text-sm text-gray-600" id="historique_rappels">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-gray-400 mr-2"></i>
                                <span>Aucun rappel envoyé pour cette réunion</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between space-x-3 p-6 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            <button type="button" onclick="previsualiserRappel()"
                class="px-4 py-2 text-cyan-700 bg-white border border-cyan-300 rounded-lg hover:bg-cyan-50 transition-colors text-sm">
                <i class="fas fa-eye mr-2"></i>
                Prévisualiser
            </button>
            <div class="flex space-x-3">
                <button type="button" onclick="closeRappelModal()"
                    class="px-6 py-3 text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
                <button type="button" onclick="envoyerRappel()"
                    class="px-6 py-3 bg-cyan-600 text-white rounded-xl hover:bg-cyan-700 transition-colors font-medium">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Envoyer le rappel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales pour le modal de rappel
let currentReunionRappel = null;
let reunionDataRappel = null;

const messageTemplates = {
    '1_semaine': {
        objet: 'Rappel : {titre} - dans une semaine',
        message: `Bonjour,

Nous vous rappelons que la réunion "{titre}" aura lieu dans une semaine :

📅 Date : {date}
🕐 Heure : {heure}
📍 Lieu : {lieu}

Nous comptons sur votre présence !

Cordialement,
L'équipe organisatrice`
    },
    '1_jour': {
        objet: 'Rappel urgent : {titre} - demain',
        message: `Bonjour,

La réunion "{titre}" aura lieu demain :

📅 Date : {date}
🕐 Heure : {heure}
📍 Lieu : {lieu}

N'oubliez pas de vous joindre à nous !

À très bientôt,
L'équipe organisatrice`
    },
    'personnalise': {
        objet: 'Rappel : {titre}',
        message: ''
    }
};

function openRappelModal(reunionId, reunionData = null) {
    currentReunionRappel = reunionId;
    reunionDataRappel = reunionData;

    document.getElementById('rappel_reunion_id').value = reunionId;
    document.getElementById('rappelModal').classList.remove('hidden');

    // Remplir les informations de la réunion si disponibles
    if (reunionData) {
        document.getElementById('nombre_inscrits').textContent = reunionData.nombre_inscrits || '0';
        chargerHistoriqueRappels(reunionId);
    }

    // Initialiser les événements
    initializeRappelEvents();

    // Mettre à jour l'aperçu initial
    updateRappelPreview();

    // Focus sur le premier champ
    setTimeout(() => {
        document.getElementById('message_rappel').focus();
    }, 100);

    // Initialiser CKEditor sur le message après un court délai
    setTimeout(() => {
        if (document.getElementById('message_rappel') && typeof ClassicEditor !== 'undefined') {
            if (!document.querySelector('#message_rappel + .ck-editor')) {
                initializeCKEditor('#message_rappel', 'simple', {
                    placeholder: 'Rédigez votre message de rappel personnalisé...'
                });
            }
        }
    }, 100);
}

function closeRappelModal() {
    // Nettoyer l'instance CKEditor si elle existe
    const editorContainer = document.querySelector('#message_rappel + .ck-editor');
    if (editorContainer && window.CKEditorInstances && window.CKEditorInstances['#message_rappel']) {
        window.CKEditorInstances['#message_rappel'].destroy()
            .then(() => {
                delete window.CKEditorInstances['#message_rappel'];
            })
            .catch(error => {
                console.error('Erreur lors de la destruction de CKEditor:', error);
            });
    }

    document.getElementById('rappelModal').classList.add('hidden');
    document.getElementById('rappelForm').reset();
    currentReunionRappel = null;
    reunionDataRappel = null;
}

function initializeRappelEvents() {
    // Événements pour les types de rappel
    document.querySelectorAll('input[name="type_rappel"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateMessageTemplate(this.value);
            updateRappelPreview();
        });
    });

    // Événements pour les destinataires
    document.querySelectorAll('input[name="destinataires"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const container = document.getElementById('destinataires_personnalises');
            if (this.value === 'personnalise') {
                container.classList.remove('hidden');
                chargerListeDestinataires();
            } else {
                container.classList.add('hidden');
            }
            updateRappelPreview();
        });
    });

    // Événements pour les canaux
    document.querySelectorAll('input[name="canaux[]"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateRappelPreview);
    });

    // Événement pour la programmation
    document.getElementById('programmation_rappel').addEventListener('change', function() {
        const container = document.getElementById('programmation_container');
        if (this.checked) {
            container.classList.remove('hidden');
            // Définir la date par défaut (demain)
            const demain = new Date();
            demain.setDate(demain.getDate() + 1);
            document.getElementById('date_programmee').value = demain.toISOString().split('T')[0];
            document.getElementById('heure_programmee').value = '09:00';
        } else {
            container.classList.add('hidden');
        }
    });

    // Événements pour la mise à jour de l'aperçu
    document.getElementById('objet_rappel').addEventListener('input', updateRappelPreview);
    document.getElementById('message_rappel').addEventListener('input', updateRappelPreview);
}

function updateMessageTemplate(typeRappel) {
    const template = messageTemplates[typeRappel];
    if (template && reunionDataRappel) {
        const objet = replaceVariables(template.objet, reunionDataRappel);
        const message = replaceVariables(template.message, reunionDataRappel);

        document.getElementById('objet_rappel').value = objet;
        document.getElementById('message_rappel').value = message;

        // Mettre à jour CKEditor si actif
        if (window.CKEditorInstances && window.CKEditorInstances['#message_rappel']) {
            window.CKEditorInstances['#message_rappel'].setData(message);
        }
    }
}

function replaceVariables(text, reunionData) {
    if (!reunionData) return text;

    const dateFormatee = reunionData.date_reunion ?
        new Date(reunionData.date_reunion).toLocaleDateString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) : '[Date]';

    return text
        .replace(/{titre}/g, reunionData.titre || '[Titre]')
        .replace(/{date}/g, dateFormatee)
        .replace(/{heure}/g, reunionData.heure_debut_prevue ?
            new Date('2000-01-01 ' + reunionData.heure_debut_prevue).toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            }) : '[Heure]')
        .replace(/{lieu}/g, reunionData.lieu || '[Lieu]');
}

function updateRappelPreview() {
    // Mise à jour de l'objet
    const objet = document.getElementById('objet_rappel').value || 'Rappel : [Titre de la réunion]';
    document.getElementById('preview_objet').textContent = objet;

    // Mise à jour du message
    let message = document.getElementById('message_rappel').value;
    if (window.CKEditorInstances && window.CKEditorInstances['#message_rappel']) {
        message = window.CKEditorInstances['#message_rappel'].getData();
    }

    if (message.trim()) {
        // Remplacer les variables si possible
        if (reunionDataRappel) {
            message = replaceVariables(message, reunionDataRappel);
        }
        document.getElementById('preview_message').innerHTML = message.replace(/\n/g, '<br>');
    } else {
        document.getElementById('preview_message').textContent = 'Aucun message défini';
    }

    // Mise à jour des destinataires
    const destinataires = document.querySelector('input[name="destinataires"]:checked')?.value;
    const destinatairesLabels = {
        'tous_inscrits': `Tous les inscrits (${document.getElementById('nombre_inscrits').textContent} personnes)`,
        'responsables': 'Responsables uniquement',
        'liste_attente': 'Liste d\'attente',
        'personnalise': 'Sélection personnalisée'
    };
    document.getElementById('preview_destinataires').textContent = destinatairesLabels[destinataires] || 'Non défini';

    // Mise à jour des canaux
    const canaux = Array.from(document.querySelectorAll('input[name="canaux[]"]:checked'))
        .map(cb => cb.nextElementSibling.textContent.trim().split(' ')[0])
        .join(', ');
    document.getElementById('preview_canaux').textContent = canaux || 'Aucun canal sélectionné';
}

function chargerListeDestinataires() {
    // Simuler le chargement des participants
    const listeContainer = document.getElementById('liste_destinataires');
    listeContainer.innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin text-gray-400 mr-2"></i>
            <span class="text-gray-500">Chargement des participants...</span>
        </div>
    `;

    // TODO: Faire un appel AJAX pour charger la vraie liste des participants
    setTimeout(() => {
        listeContainer.innerHTML = `
            <div class="space-y-2">
                <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                    <input type="checkbox" name="destinataires_selectionnes[]" value="user1" class="w-4 h-4 text-cyan-600">
                    <span class="ml-3 text-sm">Jean Dupont (jean.dupont@email.com)</span>
                </label>
                <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                    <input type="checkbox" name="destinataires_selectionnes[]" value="user2" class="w-4 h-4 text-cyan-600">
                    <span class="ml-3 text-sm">Marie Martin (marie.martin@email.com)</span>
                </label>
                <!-- Plus de participants... -->
            </div>
        `;
    }, 1000);
}

function chargerHistoriqueRappels(reunionId) {
    const historiqueContainer = document.getElementById('historique_rappels');

    // TODO: Faire un appel AJAX pour charger l'historique réel
    // Pour la démo, on affiche un message par défaut
    setTimeout(() => {
        historiqueContainer.innerHTML = `
            <div class="space-y-2 text-sm">
                <div class="flex items-center justify-between p-2 bg-white rounded border">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span>Rappel J-7 envoyé le 15/01/2024 à 09:00</span>
                    </div>
                    <span class="text-xs text-gray-500">25 destinataires</span>
                </div>
                <div class="flex items-center text-gray-500">
                    <i class="fas fa-clock text-gray-400 mr-2"></i>
                    <span>Rappel J-1 : prévu automatiquement</span>
                </div>
            </div>
        `;
    }, 500);
}

function previsualiserRappel() {
    // Ouvrir une nouvelle fenêtre avec l'aperçu complet du message
    const objet = document.getElementById('objet_rappel').value;
    let message = document.getElementById('message_rappel').value;

    if (window.CKEditorInstances && window.CKEditorInstances['#message_rappel']) {
        message = window.CKEditorInstances['#message_rappel'].getData();
    }

    if (reunionDataRappel) {
        message = replaceVariables(message, reunionDataRappel);
    }

    const previewWindow = window.open('', 'preview', 'width=600,height=400');
    previewWindow.document.write(`
        <html>
            <head>
                <title>Aperçu du rappel</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .email-container { border: 1px solid #ccc; border-radius: 8px; padding: 20px; }
                    .subject { font-weight: bold; color: #333; margin-bottom: 10px; }
                    .message { line-height: 1.5; }
                </style>
            </head>
            <body>
                <div class="email-container">
                    <div class="subject">Objet : ${objet}</div>
                    <div class="message">${message.replace(/\n/g, '<br>')}</div>
                </div>
                <br>
                <button onclick="window.close()">Fermer</button>
            </body>
        </html>
    `);
}

function envoyerRappel() {
    // Synchroniser CKEditor avant l'envoi
    if (window.CKEditorInstances && window.CKEditorInstances['#message_rappel']) {
        const editor = window.CKEditorInstances['#message_rappel'];
        const textarea = document.getElementById('message_rappel');
        if (textarea) {
            textarea.value = editor.getData();
        }
    }

    const form = document.getElementById('rappelForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('rappel_reunion_id').value;

    // Validation côté client
    const typeRappel = document.querySelector('input[name="type_rappel"]:checked')?.value;
    const message = document.getElementById('message_rappel').value.trim();
    const objet = document.getElementById('objet_rappel').value.trim();
    const canauxSelectionnes = document.querySelectorAll('input[name="canaux[]"]:checked');

    if (!typeRappel) {
        alert('Veuillez sélectionner un type de rappel.');
        return;
    }

    if (!message) {
        alert('Veuillez saisir un message.');
        document.getElementById('message_rappel').focus();
        return;
    }

    if (!objet) {
        alert('Veuillez saisir un objet.');
        document.getElementById('objet_rappel').focus();
        return;
    }

    if (canauxSelectionnes.length === 0) {
        alert('Veuillez sélectionner au moins un canal de notification.');
        return;
    }

    // Confirmation finale
    const nombreDestinataires = document.getElementById('nombre_inscrits').textContent;
    if (!confirm(`Confirmer l'envoi du rappel à ${nombreDestinataires} destinataire(s) ?`)) {
        return;
    }

    // Désactiver le bouton pour éviter les double-clics
    const submitButton = document.querySelector('#rappelModal button[onclick="envoyerRappel()"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';

    fetch(`{{route('private.reunions.envoyer-rappel', ':reunion')}}`.replace(':reunion', reunionId), {
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
            showSuccessMessage(`Rappel envoyé avec succès à ${nombreDestinataires} destinataire(s).`);

            // Fermer le modal
            closeRappelModal();

            // Recharger la page pour mettre à jour les données
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'envoi du rappel:', error);
        alert(error.message || 'Une erreur est survenue lors de l\'envoi du rappel');
    })
    .finally(() => {
        // Réactiver le bouton
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('rappelModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRappelModal();
    }
});

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('rappelModal').classList.contains('hidden')) {
        closeRappelModal();
    }
});

// Initialisation par défaut lors du chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Mettre le template par défaut sur "personnalisé"
    const personnaliseRadio = document.querySelector('input[name="type_rappel"][value="personnalise"]');
    if (personnaliseRadio) {
        personnaliseRadio.checked = true;
    }
});
</script>
