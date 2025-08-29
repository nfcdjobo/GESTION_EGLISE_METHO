<!-- Modal Évaluation de Réunion -->
<div id="evaluationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-3xl w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-star text-amber-600 mr-3"></i>
                    Évaluation de la réunion
                </h3>
                <button type="button" onclick="closeEvaluationModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-amber-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-amber-800">Évaluation post-réunion</h4>
                        <p class="text-sm text-amber-700 mt-1">
                            Cette évaluation permettra d'améliorer l'organisation des prochaines réunions et de mesurer la satisfaction des participants.
                        </p>
                    </div>
                </div>
            </div>

            <form id="evaluationForm">
                @csrf
                <input type="hidden" id="evaluation_reunion_id" name="reunion_id">

                <div class="space-y-6">
                    <!-- Notes générales -->
                    <div class="bg-slate-50 rounded-xl p-6">
                        <h4 class="font-semibold text-slate-800 mb-4">Notes générales</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div>
                                <label for="note_globale" class="block text-sm font-medium text-slate-700 mb-2">
                                    Note globale <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" id="note_globale" name="note_globale" min="1" max="10" step="0.1" required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors text-center text-lg font-semibold">
                                    <div class="text-xs text-slate-500 text-center mt-1">/ 10</div>
                                </div>
                            </div>

                            <div>
                                <label for="note_contenu" class="block text-sm font-medium text-slate-700 mb-2">
                                    Note du contenu
                                </label>
                                <div class="relative">
                                    <input type="number" id="note_contenu" name="note_contenu" min="1" max="10" step="0.1"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors text-center text-lg font-semibold">
                                    <div class="text-xs text-slate-500 text-center mt-1">/ 10</div>
                                </div>
                            </div>

                            <div>
                                <label for="note_organisation" class="block text-sm font-medium text-slate-700 mb-2">
                                    Note de l'organisation
                                </label>
                                <div class="relative">
                                    <input type="number" id="note_organisation" name="note_organisation" min="1" max="10" step="0.1"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors text-center text-lg font-semibold">
                                    <div class="text-xs text-slate-500 text-center mt-1">/ 10</div>
                                </div>
                            </div>

                            <div>
                                <label for="note_lieu" class="block text-sm font-medium text-slate-700 mb-2">
                                    Note du lieu
                                </label>
                                <div class="relative">
                                    <input type="number" id="note_lieu" name="note_lieu" min="1" max="10" step="0.1"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors text-center text-lg font-semibold">
                                    <div class="text-xs text-slate-500 text-center mt-1">/ 10</div>
                                </div>
                            </div>
                        </div>

                        <!-- Système d'étoiles visuelles -->
                        <div class="mt-4 pt-4 border-t border-slate-200">
                            <label class="block text-sm font-medium text-slate-700 mb-3">Évaluation visuelle (note globale)</label>
                            <div class="flex items-center space-x-2">
                                <div class="flex space-x-1" id="star-rating">
                                    <button type="button" class="star-btn text-2xl text-slate-300 hover:text-amber-500 transition-colors" data-rating="1">★</button>
                                    <button type="button" class="star-btn text-2xl text-slate-300 hover:text-amber-500 transition-colors" data-rating="2">★</button>
                                    <button type="button" class="star-btn text-2xl text-slate-300 hover:text-amber-500 transition-colors" data-rating="3">★</button>
                                    <button type="button" class="star-btn text-2xl text-slate-300 hover:text-amber-500 transition-colors" data-rating="4">★</button>
                                    <button type="button" class="star-btn text-2xl text-slate-300 hover:text-amber-500 transition-colors" data-rating="5">★</button>
                                    <button type="button" class="star-btn text-2xl text-slate-300 hover:text-amber-500 transition-colors" data-rating="6">★</button>
                                    <button type="button" class="star-btn text-2xl text-slate-300 hover:text-amber-500 transition-colors" data-rating="7">★</button>
                                    <button type="button" class="star-btn text-2xl text-slate-300 hover:text-amber-500 transition-colors" data-rating="8">★</button>
                                    <button type="button" class="star-btn text-2xl text-slate-300 hover:text-amber-500 transition-colors" data-rating="9">★</button>
                                    <button type="button" class="star-btn text-2xl text-slate-300 hover:text-amber-500 transition-colors" data-rating="10">★</button>
                                </div>
                                <span id="rating-text" class="text-sm text-slate-600 ml-4">Cliquez sur les étoiles</span>
                            </div>
                        </div>
                    </div>

                    <!-- Satisfaction générale -->
                    <div>
                        <label for="taux_satisfaction" class="block text-sm font-medium text-slate-700 mb-2">
                            Taux de satisfaction des participants (%)
                        </label>
                        <div class="flex items-center space-x-4">
                            <input type="range" id="taux_satisfaction" name="taux_satisfaction" min="0" max="100" step="5"
                                class="flex-1 h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer">
                            <div class="w-20 text-center">
                                <span id="satisfaction_value" class="text-lg font-semibold text-amber-600">50%</span>
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-slate-500 mt-1">
                            <span>0% - Très insatisfait</span>
                            <span>50% - Neutre</span>
                            <span>100% - Très satisfait</span>
                        </div>
                    </div>

                    <!-- Points positifs et améliorations -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="points_positifs" class="block text-sm font-medium text-slate-700 mb-2">
                                Points positifs
                            </label>
                            <div class="has-error-container">
                                <textarea id="points_positifs" name="points_positifs" rows="4"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"
                                    placeholder="Qu'est-ce qui a bien fonctionné ? Points forts de la réunion..."></textarea>
                            </div>
                        </div>

                        <div>
                            <label for="points_amelioration" class="block text-sm font-medium text-slate-700 mb-2">
                                Points d'amélioration
                            </label>
                            <div class="has-error-container">
                                <textarea id="points_amelioration" name="points_amelioration" rows="4"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors resize-none"
                                    placeholder="Qu'est-ce qui pourrait être amélioré pour les prochaines fois ?"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Feedback des participants -->
                    <div>
                        <label for="feedback_participants" class="block text-sm font-medium text-slate-700 mb-2">
                            Commentaires et feedback des participants
                        </label>
                        <div class="has-error-container">
                            <textarea id="feedback_participants" name="feedback_participants" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                placeholder="Résumé des retours des participants, suggestions recueillies..."></textarea>
                        </div>
                    </div>

                    <!-- Métriques spirituelles (si applicable) -->
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-6">
                        <h4 class="font-semibold text-purple-800 mb-4 flex items-center">
                            <i class="fas fa-heart text-purple-600 mr-2"></i>
                            Résultats spirituels (si applicable)
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="nombre_decisions" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nombre de décisions spirituelles
                                </label>
                                <input type="number" id="nombre_decisions" name="nombre_decisions" min="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-center">
                            </div>

                            <div>
                                <label for="nombre_recommitments" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nombre de re-engagements
                                </label>
                                <input type="number" id="nombre_recommitments" name="nombre_recommitments" min="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-center">
                            </div>

                            <div>
                                <label for="nombre_guerisons" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nombre de guérisons rapportées
                                </label>
                                <input type="number" id="nombre_guerisons" name="nombre_guerisons" min="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors text-center">
                            </div>
                        </div>
                    </div>

                    <!-- Évaluation détaillée par critères -->
                    <div class="bg-slate-50 rounded-xl p-6">
                        <h4 class="font-semibold text-slate-800 mb-4">Évaluation détaillée</h4>
                        <div class="space-y-4">
                            <!-- Préparation -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Qualité de la préparation</span>
                                <div class="flex space-x-1">
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-red-500 hover:text-white transition-colors text-sm" data-criteria="preparation" data-value="1">1</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-orange-500 hover:text-white transition-colors text-sm" data-criteria="preparation" data-value="2">2</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-yellow-500 hover:text-white transition-colors text-sm" data-criteria="preparation" data-value="3">3</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-green-500 hover:text-white transition-colors text-sm" data-criteria="preparation" data-value="4">4</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-blue-500 hover:text-white transition-colors text-sm" data-criteria="preparation" data-value="5">5</button>
                                </div>
                                <input type="hidden" id="note_preparation" name="note_preparation">
                            </div>

                            <!-- Ponctualité -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Respect des horaires</span>
                                <div class="flex space-x-1">
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-red-500 hover:text-white transition-colors text-sm" data-criteria="ponctualite" data-value="1">1</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-orange-500 hover:text-white transition-colors text-sm" data-criteria="ponctualite" data-value="2">2</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-yellow-500 hover:text-white transition-colors text-sm" data-criteria="ponctualite" data-value="3">3</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-green-500 hover:text-white transition-colors text-sm" data-criteria="ponctualite" data-value="4">4</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-blue-500 hover:text-white transition-colors text-sm" data-criteria="ponctualite" data-value="5">5</button>
                                </div>
                                <input type="hidden" id="note_ponctualite" name="note_ponctualite">
                            </div>

                            <!-- Participation -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Niveau de participation</span>
                                <div class="flex space-x-1">
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-red-500 hover:text-white transition-colors text-sm" data-criteria="participation" data-value="1">1</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-orange-500 hover:text-white transition-colors text-sm" data-criteria="participation" data-value="2">2</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-yellow-500 hover:text-white transition-colors text-sm" data-criteria="participation" data-value="3">3</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-green-500 hover:text-white transition-colors text-sm" data-criteria="participation" data-value="4">4</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-blue-500 hover:text-white transition-colors text-sm" data-criteria="participation" data-value="5">5</button>
                                </div>
                                <input type="hidden" id="note_participation" name="note_participation">
                            </div>

                            <!-- Atmosphère -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Qualité de l'atmosphère</span>
                                <div class="flex space-x-1">
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-red-500 hover:text-white transition-colors text-sm" data-criteria="atmosphere" data-value="1">1</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-orange-500 hover:text-white transition-colors text-sm" data-criteria="atmosphere" data-value="2">2</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-yellow-500 hover:text-white transition-colors text-sm" data-criteria="atmosphere" data-value="3">3</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-green-500 hover:text-white transition-colors text-sm" data-criteria="atmosphere" data-value="4">4</button>
                                    <button type="button" class="criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-blue-500 hover:text-white transition-colors text-sm" data-criteria="atmosphere" data-value="5">5</button>
                                </div>
                                <input type="hidden" id="note_atmosphere" name="note_atmosphere">
                            </div>
                        </div>
                    </div>

                    <!-- Recommandations -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <h4 class="font-semibold text-blue-800 mb-3">Recommandations pour l'avenir</h4>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="recommandations[]" value="reconduire_format" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-blue-700">Reconduire ce format de réunion</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="recommandations[]" value="modifier_duree" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-blue-700">Modifier la durée des prochaines réunions</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="recommandations[]" value="changer_lieu" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-blue-700">Envisager un changement de lieu</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="recommandations[]" value="ameliorer_communication" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-blue-700">Améliorer la communication en amont</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="recommandations[]" value="formation_equipe" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-blue-700">Formation complémentaire pour l'équipe</span>
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between space-x-3 p-6 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            <button type="button" onclick="sauvegarderBrouillon()"
                class="px-4 py-2 text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-sm">
                <i class="fas fa-save mr-2"></i>
                Sauvegarder en brouillon
            </button>
            <div class="flex space-x-3">
                <button type="button" onclick="closeEvaluationModal()"
                    class="px-6 py-3 text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
                <button type="button" onclick="enregistrerEvaluation()"
                    class="px-6 py-3 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition-colors font-medium">
                    <i class="fas fa-star mr-2"></i>
                    Enregistrer l'évaluation
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales pour le modal d'évaluation
let currentReunionEvaluation = null;

function openEvaluationModal(reunionId) {
    currentReunionEvaluation = reunionId;
    document.getElementById('evaluation_reunion_id').value = reunionId;
    document.getElementById('evaluationModal').classList.remove('hidden');

    // Initialiser les événements interactifs
    initializeStarRating();
    initializeSatisfactionSlider();
    initializeCriteriaButtons();

    // Focus sur le premier champ
    setTimeout(() => {
        document.getElementById('note_globale').focus();
    }, 100);

    // Initialiser CKEditor sur les textareas après un court délai
    setTimeout(() => {
        const textareas = ['points_positifs', 'points_amelioration', 'feedback_participants'];
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

function closeEvaluationModal() {
    // Nettoyer les instances CKEditor si elles existent
    const textareas = ['points_positifs', 'points_amelioration', 'feedback_participants'];
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

    document.getElementById('evaluationModal').classList.add('hidden');
    document.getElementById('evaluationForm').reset();
    currentReunionEvaluation = null;

    // Réinitialiser les étoiles
    document.querySelectorAll('.star-btn').forEach(btn => {
        btn.classList.remove('text-amber-500');
        btn.classList.add('text-slate-300');
    });
    document.getElementById('rating-text').textContent = 'Cliquez sur les étoiles';

    // Réinitialiser les critères
    document.querySelectorAll('.criteria-btn').forEach(btn => {
        btn.className = 'criteria-btn w-8 h-8 rounded-full bg-slate-200 hover:bg-red-500 hover:text-white transition-colors text-sm';
    });
}

function initializeStarRating() {
    document.querySelectorAll('.star-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const rating = parseInt(this.getAttribute('data-rating'));
            document.getElementById('note_globale').value = rating;

            // Mettre à jour l'affichage des étoiles
            document.querySelectorAll('.star-btn').forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-slate-300');
                    star.classList.add('text-amber-500');
                } else {
                    star.classList.remove('text-amber-500');
                    star.classList.add('text-slate-300');
                }
            });

            // Mettre à jour le texte
            const labels = ['', 'Très faible', 'Faible', 'Insuffisant', 'Passable', 'Correct', 'Bien', 'Très bien', 'Excellent', 'Remarquable', 'Exceptionnel'];
            document.getElementById('rating-text').textContent = `${rating}/10 - ${labels[rating]}`;
        });
    });
}

function initializeSatisfactionSlider() {
    const slider = document.getElementById('taux_satisfaction');
    const valueDisplay = document.getElementById('satisfaction_value');

    slider.addEventListener('input', function() {
        valueDisplay.textContent = this.value + '%';

        // Changer la couleur selon la valeur
        const value = parseInt(this.value);
        if (value < 30) {
            valueDisplay.className = 'text-lg font-semibold text-red-600';
        } else if (value < 60) {
            valueDisplay.className = 'text-lg font-semibold text-orange-600';
        } else if (value < 80) {
            valueDisplay.className = 'text-lg font-semibold text-yellow-600';
        } else {
            valueDisplay.className = 'text-lg font-semibold text-green-600';
        }
    });

    // Initialiser avec la valeur par défaut
    slider.dispatchEvent(new Event('input'));
}

function initializeCriteriaButtons() {
    document.querySelectorAll('.criteria-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const criteria = this.getAttribute('data-criteria');
            const value = parseInt(this.getAttribute('data-value'));

            // Mettre à jour le champ caché
            document.getElementById(`note_${criteria}`).value = value;

            // Mettre à jour l'affichage des boutons de ce critère
            document.querySelectorAll(`[data-criteria="${criteria}"]`).forEach((button, index) => {
                button.className = 'criteria-btn w-8 h-8 rounded-full transition-colors text-sm';
                if (index < value) {
                    const colors = ['bg-red-500 text-white', 'bg-orange-500 text-white', 'bg-yellow-500 text-white', 'bg-green-500 text-white', 'bg-blue-500 text-white'];
                    button.className += ' ' + colors[value - 1];
                } else {
                    button.className += ' bg-slate-200 hover:bg-red-500 hover:text-white';
                }
            });
        });
    });
}

function sauvegarderBrouillon() {
    // Synchroniser CKEditor avant sauvegarde
    if (window.CKEditorInstances) {
        Object.entries(window.CKEditorInstances).forEach(([selector, editor]) => {
            const textarea = document.querySelector(selector);
            if (textarea) {
                textarea.value = editor.getData();
            }
        });
    }

    // Sauvegarder en localStorage comme brouillon
    const formData = new FormData(document.getElementById('evaluationForm'));
    const brouillon = {};

    for (let [key, value] of formData.entries()) {
        brouillon[key] = value;
    }

    localStorage.setItem(`evaluation_brouillon_${currentReunionEvaluation}`, JSON.stringify(brouillon));

    showSuccessMessage('Évaluation sauvegardée en brouillon');
}

function chargerBrouillon() {
    const brouillon = localStorage.getItem(`evaluation_brouillon_${currentReunionEvaluation}`);
    if (brouillon) {
        const data = JSON.parse(brouillon);

        Object.entries(data).forEach(([key, value]) => {
            const element = document.getElementById(key);
            if (element) {
                element.value = value;

                // Déclencher les événements pour mettre à jour l'affichage
                if (key === 'note_globale' && value) {
                    const starButtons = document.querySelectorAll('.star-btn');
                    starButtons.forEach((btn, index) => {
                        if (index < parseInt(value)) {
                            btn.classList.add('text-amber-500');
                            btn.classList.remove('text-slate-300');
                        }
                    });
                }

                if (key === 'taux_satisfaction') {
                    element.dispatchEvent(new Event('input'));
                }
            }
        });
    }
}

function enregistrerEvaluation() {
    // Synchroniser CKEditor avant l'envoi
    if (window.CKEditorInstances) {
        Object.entries(window.CKEditorInstances).forEach(([selector, editor]) => {
            const textarea = document.querySelector(selector);
            if (textarea) {
                textarea.value = editor.getData();
            }
        });
    }

    const form = document.getElementById('evaluationForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('evaluation_reunion_id').value;

    // Validation côté client
    const noteGlobale = document.getElementById('note_globale').value;

    if (!noteGlobale || noteGlobale < 1 || noteGlobale > 10) {
        alert('Veuillez saisir une note globale entre 1 et 10.');
        document.getElementById('note_globale').focus();
        return;
    }

    // Confirmation finale
    if (!confirm('Confirmer l\'enregistrement de cette évaluation ?')) {
        return;
    }

    // Désactiver le bouton pour éviter les double-clics
    const submitButton = document.querySelector('#evaluationModal button[onclick="enregistrerEvaluation()"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...';

    fetch(`{{route('private.reunions.evaluation', ':reunion')}}`.replace(':reunion', reunionId), {
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
            // Supprimer le brouillon
            localStorage.removeItem(`evaluation_brouillon_${currentReunionEvaluation}`);

            // Afficher un message de succès
            showSuccessMessage(`Évaluation enregistrée avec succès (Note globale: ${noteGlobale}/10).`);

            // Fermer le modal
            closeEvaluationModal();

            // Recharger la page pour mettre à jour les données
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'enregistrement de l\'évaluation:', error);
        alert(error.message || 'Une erreur est survenue lors de l\'enregistrement de l\'évaluation');
    })
    .finally(() => {
        // Réactiver le bouton
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('evaluationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEvaluationModal();
    }
});

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('evaluationModal').classList.contains('hidden')) {
        closeEvaluationModal();
    }
});

// Synchronisation automatique des notes
document.getElementById('note_globale').addEventListener('input', function() {
    const value = parseFloat(this.value);
    if (value >= 1 && value <= 10) {
        // Mettre à jour les étoiles automatiquement
        const rating = Math.round(value);
        document.querySelectorAll('.star-btn').forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-slate-300');
                star.classList.add('text-amber-500');
            } else {
                star.classList.remove('text-amber-500');
                star.classList.add('text-slate-300');
            }
        });

        // Mettre à jour le texte
        const labels = ['', 'Très faible', 'Faible', 'Insuffisant', 'Passable', 'Correct', 'Bien', 'Très bien', 'Excellent', 'Remarquable', 'Exceptionnel'];
        document.getElementById('rating-text').textContent = `${value}/10 - ${labels[rating]}`;
    }
});
</script>
