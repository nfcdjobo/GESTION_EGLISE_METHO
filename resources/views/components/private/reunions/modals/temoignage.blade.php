<!-- Modal Témoignage -->
<div id="temoignageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-heart text-purple-600 mr-3"></i>
                    Ajouter un témoignage
                </h3>
                <button type="button" onclick="closeTemoignageModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-purple-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-purple-800">Recueil de témoignages</h4>
                        <p class="text-sm text-purple-700 mt-1">
                            Enregistrez les témoignages partagés pendant la réunion pour garder une trace des bénédictions et encouragements.
                        </p>
                    </div>
                </div>
            </div>

            <form id="temoignageForm">
                @csrf
                <input type="hidden" id="temoignage_reunion_id" name="reunion_id">

                <div class="space-y-6">
                    <!-- Témoignage principal -->
                    <div>
                        <label for="contenu_temoignage" class="block text-sm font-medium text-slate-700 mb-2">
                            Contenu du témoignage <span class="text-red-500">*</span>
                        </label>
                        <div class="has-error-container">
                            <textarea id="contenu_temoignage" name="temoignage" rows="6" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none"
                                placeholder="Décrivez le témoignage partagé (guérison, réponse à la prière, transformation, bénédiction, etc.)"></textarea>
                        </div>
                    </div>

                    <!-- Informations sur le témoin -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="auteur_temoignage" class="block text-sm font-medium text-slate-700 mb-2">
                                Auteur du témoignage <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="auteur_temoignage" name="auteur" required maxlength="200"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                placeholder="Nom de la personne qui témoigne">
                        </div>

                        <div>
                            <label for="contact_temoin" class="block text-sm font-medium text-slate-700 mb-2">
                                Contact (optionnel)
                            </label>
                            <input type="text" id="contact_temoin" name="contact" maxlength="200"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                placeholder="Email ou téléphone pour suivi">
                        </div>
                    </div>

                    <!-- Type et catégorie -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="type_temoignage" class="block text-sm font-medium text-slate-700 mb-2">
                                Type de témoignage <span class="text-red-500">*</span>
                            </label>
                            <select id="type_temoignage" name="type" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                <option value="">Sélectionner un type</option>
                                <option value="guerison">Guérison</option>
                                <option value="conversion">Conversion/Salut</option>
                                <option value="restoration">Restauration</option>
                                <option value="benediction">Bénédiction financière</option>
                                <option value="famille">Restauration familiale</option>
                                <option value="travail">Réponse professionnelle</option>
                                <option value="delivrance">Délivrance</option>
                                <option value="miracle">Miracle</option>
                                <option value="transformation">Transformation personnelle</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div>
                            <label for="impact_temoignage" class="block text-sm font-medium text-slate-700 mb-2">
                                Niveau d'impact
                            </label>
                            <select id="impact_temoignage" name="niveau_impact"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                <option value="personnel">Personnel</option>
                                <option value="familial">Familial</option>
                                <option value="communautaire">Communautaire</option>
                                <option value="exceptionnel">Exceptionnel</option>
                            </select>
                        </div>
                    </div>

                    <!-- Contexte et timing -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Contexte du témoignage</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="duree_situation" class="block text-sm font-medium text-slate-700 mb-2">
                                    Durée de la situation
                                </label>
                                <select id="duree_situation" name="duree_situation"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Non précisée</option>
                                    <option value="recent">Récente (< 1 mois)</option>
                                    <option value="courte">Courte (1-6 mois)</option>
                                    <option value="moyenne">Moyenne (6 mois - 2 ans)</option>
                                    <option value="longue">Longue (2-10 ans)</option>
                                    <option value="tres_longue">Très longue (> 10 ans)</option>
                                </select>
                            </div>

                            <div>
                                <label for="moment_reponse" class="block text-sm font-medium text-slate-700 mb-2">
                                    Moment de la réponse
                                </label>
                                <select id="moment_reponse" name="moment_reponse"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                    <option value="">Non précisé</option>
                                    <option value="immediate">Immédiate</option>
                                    <option value="reunion">Pendant cette réunion</option>
                                    <option value="semaine">Cette semaine</option>
                                    <option value="mois">Ce mois-ci</option>
                                    <option value="anterieur">Antérieure</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Détails complémentaires -->
                    <div>
                        <label for="details_complementaires" class="block text-sm font-medium text-slate-700 mb-2">
                            Détails complémentaires
                        </label>
                        <div class="@error('details_complementaires') has-error @enderror">
                            <textarea id="details_complementaires" name="details_complementaires" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors resize-none"
                                placeholder="Ajoutez des détails sur le contexte, les circonstances, l'impact sur la famille/entourage, etc."></textarea>
                        </div>
                    </div>

                    <!-- Références bibliques ou spirituelles -->
                    <div>
                        <label for="reference_biblique" class="block text-sm font-medium text-slate-700 mb-2">
                            Référence biblique ou verset lié (optionnel)
                        </label>
                        <input type="text" id="reference_biblique" name="reference_biblique" maxlength="200"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                            placeholder="Ex: Jean 14:14, Psaume 23, etc.">
                    </div>

                    <!-- Options de partage et confidentialité -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Options de partage</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="autoriser_partage_public" name="autoriser_partage_public" value="1" checked
                                    class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                <label for="autoriser_partage_public" class="ml-3 text-sm text-slate-700">
                                    <strong>Autoriser le partage public</strong> - Peut être partagé lors d'autres réunions ou événements
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="inclure_newsletter" name="inclure_newsletter" value="1"
                                    class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                <label for="inclure_newsletter" class="ml-3 text-sm text-slate-700">
                                    Inclure dans la newsletter ou les communications
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="temoignage_anonyme" name="anonyme" value="1"
                                    class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                <label for="temoignage_anonyme" class="ml-3 text-sm text-slate-700">
                                    Témoignage anonyme (ne pas mentionner le nom)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="contact_suivi" name="accepte_contact_suivi" value="1"
                                    class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                <label for="contact_suivi" class="ml-3 text-sm text-slate-700">
                                    Accepte d'être contacté pour un suivi ou des nouvelles
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Témoins et vérification -->
                    <div>
                        <label for="temoins_verification" class="block text-sm font-medium text-slate-700 mb-2">
                            Témoins ou personnes pouvant confirmer (optionnel)
                        </label>
                        <input type="text" id="temoins_verification" name="temoins" maxlength="500"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                            placeholder="Noms des personnes présentes lors de la situation ou pouvant témoigner">
                    </div>

                    <!-- Émotion et impact spirituel -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <h4 class="font-semibold text-blue-800 mb-3">Impact et réception</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="reaction_assemblee" class="block text-sm font-medium text-slate-700 mb-2">
                                    Réaction de l'assemblée
                                </label>
                                <select id="reaction_assemblee" name="reaction_assemblee"
                                    class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                    <option value="">Non observée</option>
                                    <option value="encouragee">Encouragée et édifiée</option>
                                    <option value="emue">Émue et touchée</option>
                                    <option value="reconnaissante">Reconnaissante et joyeuse</option>
                                    <option value="inspiree">Inspirée à la foi</option>
                                    <option value="reverentielle">Révérentielle et respectueuse</option>
                                </select>
                            </div>

                            <div>
                                <label for="niveau_emotion" class="block text-sm font-medium text-slate-700 mb-2">
                                    Intensité émotionnelle
                                </label>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-slate-500">Calme</span>
                                    <input type="range" id="niveau_emotion" name="niveau_emotion" min="1" max="10" value="5"
                                        class="flex-1 h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer">
                                    <span class="text-xs text-slate-500">Intense</span>
                                    <span id="emotion_value" class="w-8 text-center text-sm font-medium text-purple-600">5</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Suivi recommandé -->
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-slate-700">
                            Actions de suivi recommandées
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label class="flex items-center p-3 border border-slate-300 rounded-lg cursor-pointer hover:bg-slate-50">
                                <input type="checkbox" name="actions_suivi[]" value="visite_pastorale" class="w-4 h-4 text-purple-600">
                                <span class="ml-3 text-sm text-slate-700">Visite pastorale</span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-lg cursor-pointer hover:bg-slate-50">
                                <input type="checkbox" name="actions_suivi[]" value="integration_groupe" class="w-4 h-4 text-purple-600">
                                <span class="ml-3 text-sm text-slate-700">Intégration dans un groupe</span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-lg cursor-pointer hover:bg-slate-50">
                                <input type="checkbox" name="actions_suivi[]" value="accompagnement_spirituel" class="w-4 h-4 text-purple-600">
                                <span class="ml-3 text-sm text-slate-700">Accompagnement spirituel</span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-lg cursor-pointer hover:bg-slate-50">
                                <input type="checkbox" name="actions_suivi[]" value="formation_disciplat" class="w-4 h-4 text-purple-600">
                                <span class="ml-3 text-sm text-slate-700">Formation/Disciplat</span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-lg cursor-pointer hover:bg-slate-50">
                                <input type="checkbox" name="actions_suivi[]" value="contact_regulier" class="w-4 h-4 text-purple-600">
                                <span class="ml-3 text-sm text-slate-700">Contact régulier</span>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-lg cursor-pointer hover:bg-slate-50">
                                <input type="checkbox" name="actions_suivi[]" value="aucune" class="w-4 h-4 text-purple-600">
                                <span class="ml-3 text-sm text-slate-700">Aucune action nécessaire</span>
                            </label>
                        </div>
                    </div>

                    <!-- Aperçu du témoignage -->
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-eye text-green-600 mr-3 mt-1"></i>
                            <div class="flex-1">
                                <h4 class="font-semibold text-green-800 mb-2">Aperçu du témoignage</h4>
                                <div class="bg-white border rounded-lg p-3 text-sm">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="font-medium" id="preview_auteur">-</span>
                                        <span class="text-xs text-gray-500" id="preview_type">-</span>
                                    </div>
                                    <div class="text-gray-700 text-sm" id="preview_contenu">
                                        Saisissez le contenu pour voir l'aperçu...
                                    </div>
                                    <div class="text-xs text-gray-500 mt-2" id="preview_reference"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between space-x-3 p-6 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            <button type="button" onclick="ajouterAutreTemoignage()"
                class="px-4 py-2 text-purple-700 bg-white border border-purple-300 rounded-lg hover:bg-purple-50 transition-colors text-sm">
                <i class="fas fa-plus mr-2"></i>
                Ajouter un autre
            </button>
            <div class="flex space-x-3">
                <button type="button" onclick="closeTemoignageModal()"
                    class="px-6 py-3 text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
                <button type="button" onclick="enregistrerTemoignage()"
                    class="px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors font-medium">
                    <i class="fas fa-heart mr-2"></i>
                    Enregistrer le témoignage
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales pour le modal de témoignage
let currentReunionTemoignage = null;
let temoignagesCollectes = [];

function openTemoignageModal(reunionId) {

    currentReunionTemoignage = reunionId;
    document.getElementById('temoignage_reunion_id').value = reunionId;
    document.getElementById('temoignageModal').classList.remove('hidden');

    // Initialiser les événements
    initializeTemoignageEvents();

    // Focus sur le premier champ
    setTimeout(() => {
        document.getElementById('contenu_temoignage').focus();
    }, 100);

    // Initialiser CKEditor sur les textareas après un court délai
    setTimeout(() => {
        const textareas = ['contenu_temoignage', 'details_complementaires'];
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

function closeTemoignageModal() {
    // Nettoyer les instances CKEditor si elles existent
    const textareas = ['contenu_temoignage', 'details_complementaires'];
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

    document.getElementById('temoignageModal').classList.add('hidden');
    document.getElementById('temoignageForm').reset();
    currentReunionTemoignage = null;
    temoignagesCollectes = [];
    updateTemoignagePreview();
}

function initializeTemoignageEvents() {
    // Événements pour la mise à jour de l'aperçu
    document.getElementById('auteur_temoignage').addEventListener('input', updateTemoignagePreview);
    document.getElementById('contenu_temoignage').addEventListener('input', updateTemoignagePreview);
    document.getElementById('type_temoignage').addEventListener('change', updateTemoignagePreview);
    document.getElementById('reference_biblique').addEventListener('input', updateTemoignagePreview);

    // Événement pour le slider d'intensité émotionnelle
    document.getElementById('niveau_emotion').addEventListener('input', function() {
        document.getElementById('emotion_value').textContent = this.value;
    });

    // Gestion de l'anonymat
    document.getElementById('temoignage_anonyme').addEventListener('change', function() {
        const auteurField = document.getElementById('auteur_temoignage');
        const contactField = document.getElementById('contact_temoin');

        if (this.checked) {
            auteurField.placeholder = 'Témoignage anonyme';
            contactField.disabled = true;
            contactField.placeholder = 'Non applicable pour témoignage anonyme';
        } else {
            auteurField.placeholder = 'Nom de la personne qui témoigne';
            contactField.disabled = false;
            contactField.placeholder = 'Email ou téléphone pour suivi';
        }
        updateTemoignagePreview();
    });

    // Gestion exclusive "Aucune action" pour le suivi
    document.querySelector('input[name="actions_suivi[]"][value="aucune"]').addEventListener('change', function() {
        if (this.checked) {
            document.querySelectorAll('input[name="actions_suivi[]"]:not([value="aucune"])').forEach(cb => {
                cb.checked = false;
            });
        }
    });

    document.querySelectorAll('input[name="actions_suivi[]"]:not([value="aucune"])').forEach(cb => {
        cb.addEventListener('change', function() {
            if (this.checked) {
                document.querySelector('input[name="actions_suivi[]"][value="aucune"]').checked = false;
            }
        });
    });
}

function updateTemoignagePreview() {
    // Mise à jour de l'auteur
    const auteur = document.getElementById('auteur_temoignage').value.trim();
    const anonyme = document.getElementById('temoignage_anonyme').checked;

    if (anonyme) {
        document.getElementById('preview_auteur').textContent = 'Témoignage anonyme';
    } else if (auteur) {
        document.getElementById('preview_auteur').textContent = auteur;
    } else {
        document.getElementById('preview_auteur').textContent = 'Auteur non défini';
    }

    // Mise à jour du type
    const type = document.getElementById('type_temoignage').value;
    const typeLabels = {
        'guerison': 'Guérison',
        'conversion': 'Conversion/Salut',
        'restoration': 'Restauration',
        'benediction': 'Bénédiction financière',
        'famille': 'Restauration familiale',
        'travail': 'Réponse professionnelle',
        'delivrance': 'Délivrance',
        'miracle': 'Miracle',
        'transformation': 'Transformation personnelle',
        'autre': 'Autre'
    };
    document.getElementById('preview_type').textContent = type ? typeLabels[type] : 'Type non défini';

    // Mise à jour du contenu
    let contenu = document.getElementById('contenu_temoignage').value.trim();
    if (window.CKEditorInstances && window.CKEditorInstances['#contenu_temoignage']) {
        contenu = window.CKEditorInstances['#contenu_temoignage'].getData();
        // Nettoyer le HTML pour l'aperçu
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = contenu;
        contenu = tempDiv.textContent || tempDiv.innerText || '';
    }

    if (contenu) {
        // Limiter à 200 caractères pour l'aperçu
        const apercu = contenu.length > 200 ? contenu.substring(0, 200) + '...' : contenu;
        document.getElementById('preview_contenu').textContent = apercu;
    } else {
        document.getElementById('preview_contenu').textContent = 'Saisissez le contenu pour voir l\'aperçu...';
    }

    // Mise à jour de la référence
    const reference = document.getElementById('reference_biblique').value.trim();
    if (reference) {
        document.getElementById('preview_reference').textContent = `Référence : ${reference}`;
        document.getElementById('preview_reference').style.display = 'block';
    } else {
        document.getElementById('preview_reference').style.display = 'none';
    }
}

function ajouterAutreTemoignage() {
    // Sauvegarder le témoignage actuel
    const temoignageData = collectTemoignageData();
    if (temoignageData.temoignage.trim() && temoignageData.auteur.trim()) {
        temoignagesCollectes.push(temoignageData);

        // Réinitialiser le formulaire
        document.getElementById('temoignageForm').reset();
        updateTemoignagePreview();

        // Réinitialiser le slider
        document.getElementById('niveau_emotion').value = 5;
        document.getElementById('emotion_value').textContent = '5';

        // Focus sur le nouveau champ
        document.getElementById('contenu_temoignage').focus();

        showSuccessMessage(`${temoignagesCollectes.length} témoignage(s) collecté(s). Ajoutez-en un autre ou enregistrez.`);
    } else {
        alert('Veuillez saisir le contenu et l\'auteur du témoignage avant d\'en ajouter un nouveau.');
        if (!document.getElementById('contenu_temoignage').value.trim()) {
            document.getElementById('contenu_temoignage').focus();
        } else {
            document.getElementById('auteur_temoignage').focus();
        }
    }
}

function collectTemoignageData() {
    // Synchroniser CKEditor avant collecte
    if (window.CKEditorInstances) {
        Object.entries(window.CKEditorInstances).forEach(([selector, editor]) => {
            const textarea = document.querySelector(selector);
            if (textarea) {
                textarea.value = editor.getData();
            }
        });
    }

    // Collecter les actions de suivi
    const actionsSuivi = [];
    document.querySelectorAll('input[name="actions_suivi[]"]:checked').forEach(cb => {
        actionsSuivi.push(cb.value);
    });

    return {
        temoignage: document.getElementById('contenu_temoignage').value.trim(),
        auteur: document.getElementById('auteur_temoignage').value.trim(),
        contact: document.getElementById('contact_temoin').value.trim(),
        type: document.getElementById('type_temoignage').value,
        niveau_impact: document.getElementById('impact_temoignage').value,
        duree_situation: document.getElementById('duree_situation').value,
        moment_reponse: document.getElementById('moment_reponse').value,
        details_complementaires: document.getElementById('details_complementaires').value.trim(),
        reference_biblique: document.getElementById('reference_biblique').value.trim(),
        autoriser_partage_public: document.getElementById('autoriser_partage_public').checked,
        inclure_newsletter: document.getElementById('inclure_newsletter').checked,
        anonyme: document.getElementById('temoignage_anonyme').checked,
        accepte_contact_suivi: document.getElementById('contact_suivi').checked,
        temoins: document.getElementById('temoins_verification').value.trim(),
        reaction_assemblee: document.getElementById('reaction_assemblee').value,
        niveau_emotion: parseInt(document.getElementById('niveau_emotion').value),
        actions_suivi: actionsSuivi,
        date_creation: new Date().toISOString(),
        cree_par: 'current_user' // À remplacer par l'membres connecté
    };
}

function enregistrerTemoignage() {
    // Synchroniser CKEditor avant l'envoi
    if (window.CKEditorInstances) {
        Object.entries(window.CKEditorInstances).forEach(([selector, editor]) => {
            const textarea = document.querySelector(selector);
            if (textarea) {
                textarea.value = editor.getData();
            }
        });
    }

    // Ajouter le témoignage actuel s'il n'est pas vide
    const temoignageActuel = collectTemoignageData();
    if (temoignageActuel.temoignage.trim() && temoignageActuel.auteur.trim()) {
        temoignagesCollectes.push(temoignageActuel);
    }

    const reunionId = document.getElementById('temoignage_reunion_id').value;

    // Validation côté client
    if (temoignagesCollectes.length === 0) {
        alert('Veuillez saisir au moins un témoignage avec son auteur.');
        document.getElementById('contenu_temoignage').focus();
        return;
    }

    // Vérifier que chaque témoignage a un type
    const temoignagesSansType = temoignagesCollectes.filter(t => !t.type);
    if (temoignagesSansType.length > 0) {
        alert('Veuillez sélectionner un type pour tous les témoignages.');
        document.getElementById('type_temoignage').focus();
        return;
    }

    // Confirmation finale
    const message = temoignagesCollectes.length === 1 ?
        'Confirmer l\'enregistrement de ce témoignage ?' :
        `Confirmer l\'enregistrement de ces ${temoignagesCollectes.length} témoignages ?`;

    if (!confirm(message)) {
        return;
    }

    // Désactiver le bouton pour éviter les double-clics
    const submitButton = document.querySelector('#temoignageModal button[onclick="enregistrerTemoignage()"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...';

    // Préparer les données pour l'envoi
    const formData = new FormData();
    formData.append('temoignages[]', JSON.stringify(temoignagesCollectes));

    fetch(`{{route('private.reunions.temoignages', ':reunion')}}`.replace(':reunion', reunionId), {
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
            const nombre = temoignagesCollectes.length;
            showSuccessMessage(`${nombre} témoignage(s) enregistré(s) avec succès.`);

            // Fermer le modal
            closeTemoignageModal();

            // Recharger la page pour mettre à jour les données
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'enregistrement des témoignages:', error);
        alert(error.message || 'Une erreur est survenue lors de l\'enregistrement des témoignages');
    })
    .finally(() => {
        // Réactiver le bouton
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('temoignageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTemoignageModal();
    }
});

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('temoignageModal').classList.contains('hidden')) {
        closeTemoignageModal();
    }
});

// Initialisation par défaut
document.addEventListener('DOMContentLoaded', function() {
    updateTemoignagePreview();
});
</script>
