<!-- Modal Création de Récurrence -->
<div id="recurrenceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-repeat text-indigo-600 mr-3"></i>
                    Créer une récurrence
                </h3>
                <button type="button" onclick="closeRecurrenceModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 p-4 bg-indigo-50 border border-indigo-200 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-indigo-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-indigo-800">Réunions récurrentes</h4>
                        <p class="text-sm text-indigo-700 mt-1">
                            Créez automatiquement plusieurs occurrences de cette réunion selon un planning régulier.
                        </p>
                    </div>
                </div>
            </div>

            <form id="recurrenceForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" id="recurrence_reunion_id" name="reunion_id">

                <div class="space-y-6">
                    <!-- Type de récurrence -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">
                            Fréquence de récurrence <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center p-4 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="frequence" value="hebdomadaire" checked class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Hebdomadaire</div>
                                    <div class="text-xs text-slate-500">Chaque semaine, même jour</div>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="frequence" value="bimensuel" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Bimensuel</div>
                                    <div class="text-xs text-slate-500">Toutes les 2 semaines</div>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="frequence" value="mensuel" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Mensuel</div>
                                    <div class="text-xs text-slate-500">Chaque mois, même date</div>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="frequence" value="trimestriel" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Trimestriel</div>
                                    <div class="text-xs text-slate-500">Tous les 3 mois</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Personnalisation de la fréquence -->
                    <div id="frequence_personnalisee" class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Personnaliser la fréquence</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="intervalle_recurrence" class="block text-sm font-medium text-slate-700 mb-2">
                                    Tous les
                                </label>
                                <div class="flex items-center space-x-2">
                                    <input type="number" id="intervalle_recurrence" name="intervalle" min="1" max="12" value="1"
                                        class="w-20 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-center">
                                    <span id="unite_intervalle" class="text-sm text-slate-600">semaine(s)</span>
                                </div>
                            </div>

                            <div id="jour_semaine_container" class="hidden">
                                <label for="jour_semaine" class="block text-sm font-medium text-slate-700 mb-2">
                                    Jour de la semaine
                                </label>
                                <select id="jour_semaine" name="jour_semaine"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="1">Lundi</option>
                                    <option value="2">Mardi</option>
                                    <option value="3">Mercredi</option>
                                    <option value="4">Jeudi</option>
                                    <option value="5">Vendredi</option>
                                    <option value="6">Samedi</option>
                                    <option value="0">Dimanche</option>
                                </select>
                            </div>

                            <div id="jour_mois_container" class="hidden">
                                <label for="jour_mois" class="block text-sm font-medium text-slate-700 mb-2">
                                    Jour du mois
                                </label>
                                <select id="jour_mois" name="jour_mois"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="meme_date">Même date</option>
                                    <option value="premier_lundi">Premier lundi</option>
                                    <option value="deuxieme_lundi">Deuxième lundi</option>
                                    <option value="troisieme_lundi">Troisième lundi</option>
                                    <option value="dernier_lundi">Dernier lundi</option>
                                    <!-- Plus d'options pour autres jours... -->
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Durée de la récurrence -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">
                            Durée de la récurrence <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="duree_type" value="nombre_occurrences" checked class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm font-medium text-slate-700">Nombre d'occurrences :</span>
                                        <input type="number" id="nombre_occurrences" name="nombre_occurrences" min="1" max="52" value="4"
                                            class="w-20 px-2 py-1 border border-slate-300 rounded focus:ring-1 focus:ring-indigo-500 text-center text-sm">
                                        <span class="text-sm text-slate-600">réunions au total</span>
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="duree_type" value="date_fin" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm font-medium text-slate-700">Jusqu'au :</span>
                                        <input type="date" id="fin_recurrence" name="fin_recurrence"
                                            class="px-2 py-1 border border-slate-300 rounded focus:ring-1 focus:ring-indigo-500 text-sm">
                                    </div>
                                </div>
                            </label>
                            <label class="flex items-center p-3 border border-slate-300 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="radio" name="duree_type" value="infinie" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-slate-700">Récurrence infinie</div>
                                    <div class="text-xs text-slate-500">Continuer indéfiniment (peut être arrêtée plus tard)</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Options de copie -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Que copier pour chaque occurrence ?</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="checkbox" id="copier_participants_recurrence" name="copier_participants" value="1"
                                        class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="copier_participants_recurrence" class="ml-3 text-sm text-slate-700">
                                        Liste des participants actuels
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="copier_responsables_recurrence" name="copier_responsables" value="1" checked
                                        class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="copier_responsables_recurrence" class="ml-3 text-sm text-slate-700">
                                        Équipe organisatrice et responsables
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="copier_contenu_recurrence" name="copier_contenu" value="1" checked
                                        class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="copier_contenu_recurrence" class="ml-3 text-sm text-slate-700">
                                        Contenu et programme
                                    </label>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input type="checkbox" id="copier_lieu_recurrence" name="copier_lieu" value="1" checked
                                        class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="copier_lieu_recurrence" class="ml-3 text-sm text-slate-700">
                                        Lieu et logistique
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="copier_parametres_recurrence" name="copier_parametres" value="1" checked
                                        class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="copier_parametres_recurrence" class="ml-3 text-sm text-slate-700">
                                        Paramètres (diffusion, frais, etc.)
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="copier_documents_recurrence" name="copier_documents" value="1"
                                        class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="copier_documents_recurrence" class="ml-3 text-sm text-slate-700">
                                        Documents et ressources
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ajustements pour les nouvelles réunions -->
                    <div>
                        <h4 class="font-semibold text-slate-800 mb-3">Ajustements pour les nouvelles réunions</h4>
                        <div class="space-y-4">
                            <div>
                                <label for="prefixe_titre" class="block text-sm font-medium text-slate-700 mb-2">
                                    Modification du titre
                                </label>
                                <div class="flex items-center space-x-3">
                                    <select id="modification_titre" name="modification_titre"
                                        class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="numero">Ajouter un numéro (#1, #2, #3...)</option>
                                        <option value="date">Ajouter la date</option>
                                        <option value="prefixe">Ajouter un préfixe</option>
                                        <option value="aucune">Garder le même titre</option>
                                    </select>
                                    <input type="text" id="prefixe_personnalise" name="prefixe" placeholder="Préfixe personnalisé"
                                        class="hidden flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label for="statut_nouvelles_reunions" class="block text-sm font-medium text-slate-700 mb-2">
                                    Statut des nouvelles réunions
                                </label>
                                <select id="statut_nouvelles_reunions" name="statut_nouvelles"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="planifiee" selected>Planifiée</option>
                                    <option value="confirmee">Confirmée</option>
                                    <option value="planifie">En préparation</option>
                                </select>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="auto_rappels" name="programmer_rappels_auto" value="1" checked
                                    class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="auto_rappels" class="ml-3 text-sm text-slate-700">
                                    Programmer automatiquement les rappels pour chaque réunion
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Aperçu du planning -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-calendar-check text-blue-600 mr-3 mt-1"></i>
                            <div class="flex-1">
                                <h4 class="font-semibold text-blue-800 mb-3">Aperçu du planning généré</h4>
                                <div class="bg-white border rounded-lg p-3">
                                    <div class="text-sm text-blue-700 mb-2">
                                        <strong>Réunion de base :</strong> <span id="preview_reunion_base">-</span>
                                    </div>
                                    <div class="text-sm text-blue-700 mb-3">
                                        <strong>Fréquence :</strong> <span id="preview_frequence">Hebdomadaire</span> |
                                        <strong>Nombre :</strong> <span id="preview_nombre">4 occurrences</span>
                                    </div>
                                    <div class="space-y-1" id="preview_dates">
                                        <div class="text-xs text-blue-600">Calcul des dates...</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Avertissements -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-yellow-800">Points importants</h4>
                                <ul class="text-sm text-yellow-700 mt-1 space-y-1">
                                    <li>• Les réunions récurrentes seront créées avec le statut sélectionné</li>
                                    <li>• Chaque réunion pourra être modifiée individuellement par la suite</li>
                                    <li>• Les inscriptions devront être gérées pour chaque occurrence</li>
                                    <li id="warning_dates" class="hidden text-yellow-800 font-medium">⚠️ Certaines dates tombent un weekend ou jour férié</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between space-x-3 p-6 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            <button type="button" onclick="previsualiserPlanning()"
                class="px-4 py-2 text-indigo-700 bg-white border border-indigo-300 rounded-lg hover:bg-indigo-50 transition-colors text-sm">
                <i class="fas fa-eye mr-2"></i>
                Prévisualiser le planning
            </button>
            <div class="flex space-x-3">
                <button type="button" onclick="closeRecurrenceModal()"
                    class="px-6 py-3 text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
                <button type="button" onclick="creerRecurrence()"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                    <i class="fas fa-repeat mr-2"></i>
                    Créer la récurrence
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales pour le modal de récurrence
let currentReunionRecurrence = null;
let reunionDataRecurrence = null;
let previewDates = [];

function openRecurrenceModal(reunionId, reunionData = null) {
    currentReunionRecurrence = reunionId;
    reunionDataRecurrence = reunionData;

    document.getElementById('recurrence_reunion_id').value = reunionId;
    document.getElementById('recurrenceModal').classList.remove('hidden');

    // Remplir les informations de la réunion si disponibles
    if (reunionData) {
        // Définir le jour de la semaine par défaut selon la date de la réunion
        const dateReunion = new Date(reunionData.date_reunion);
        document.getElementById('jour_semaine').value = dateReunion.getDay();

        // Mettre à jour l'aperçu initial
        updateRecurrencePreview();
    }

    // Initialiser les événements
    initializeRecurrenceEvents();

    // Calculer les dates initiales
    calculatePreviewDates();

    // Focus sur le premier champ
    setTimeout(() => {
        document.querySelector('input[name="frequence"]:checked').focus();
    }, 100);
}

function closeRecurrenceModal() {
    document.getElementById('recurrenceModal').classList.add('hidden');
    document.getElementById('recurrenceForm').reset();
    currentReunionRecurrence = null;
    reunionDataRecurrence = null;
    previewDates = [];
}

function initializeRecurrenceEvents() {
    // Événements pour la fréquence
    document.querySelectorAll('input[name="frequence"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateFrequenceOptions(this.value);
            updateRecurrencePreview();
            calculatePreviewDates();
        });
    });

    // Événements pour la durée
    document.querySelectorAll('input[name="duree_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateRecurrencePreview();
            calculatePreviewDates();
        });
    });

    // Événements pour les paramètres
    document.getElementById('nombre_occurrences').addEventListener('change', function() {
        updateRecurrencePreview();
        calculatePreviewDates();
    });

    document.getElementById('fin_recurrence').addEventListener('change', function() {
        updateRecurrencePreview();
        calculatePreviewDates();
    });

    document.getElementById('intervalle_recurrence').addEventListener('change', function() {
        updateRecurrencePreview();
        calculatePreviewDates();
    });

    // Événement pour la modification du titre
    document.getElementById('modification_titre').addEventListener('change', function() {
        const prefixeInput = document.getElementById('prefixe_personnalise');
        if (this.value === 'prefixe') {
            prefixeInput.classList.remove('hidden');
            prefixeInput.focus();
        } else {
            prefixeInput.classList.add('hidden');
        }
    });

    // Événement pour l'activation du nombre d'occurrences
    document.getElementById('nombre_occurrences').addEventListener('click', function() {
        document.querySelector('input[name="duree_type"][value="nombre_occurrences"]').checked = true;
        updateRecurrencePreview();
        calculatePreviewDates();
    });

    // Événement pour l'activation de la date de fin
    document.getElementById('fin_recurrence').addEventListener('click', function() {
        document.querySelector('input[name="duree_type"][value="date_fin"]').checked = true;
        updateRecurrencePreview();
        calculatePreviewDates();
    });
}

function updateFrequenceOptions(frequence) {
    const intervalleContainer = document.getElementById('frequence_personnalisee');
    const uniteSpan = document.getElementById('unite_intervalle');
    const jourSemaineContainer = document.getElementById('jour_semaine_container');
    const jourMoisContainer = document.getElementById('jour_mois_container');

    // Masquer tous les conteneurs spéciaux
    jourSemaineContainer.classList.add('hidden');
    jourMoisContainer.classList.add('hidden');

    // Mettre à jour l'unité
    const unites = {
        'hebdomadaire': 'semaine(s)',
        'bimensuel': 'semaine(s)',
        'mensuel': 'mois',
        'trimestriel': 'trimestre(s)'
    };

    uniteSpan.textContent = unites[frequence];

    // Définir les valeurs par défaut
    const intervalleInput = document.getElementById('intervalle_recurrence');
    switch(frequence) {
        case 'hebdomadaire':
            intervalleInput.value = 1;
            jourSemaineContainer.classList.remove('hidden');
            break;
        case 'bimensuel':
            intervalleInput.value = 2;
            jourSemaineContainer.classList.remove('hidden');
            break;
        case 'mensuel':
            intervalleInput.value = 1;
            jourMoisContainer.classList.remove('hidden');
            break;
        case 'trimestriel':
            intervalleInput.value = 3;
            uniteSpan.textContent = 'mois';
            break;
    }
}

function updateRecurrencePreview() {
    // Mise à jour de la réunion de base
    if (reunionDataRecurrence) {
        const dateFormatee = new Date(reunionDataRecurrence.date_reunion).toLocaleDateString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('preview_reunion_base').textContent =
            `${reunionDataRecurrence.titre} - ${dateFormatee}`;
    }

    // Mise à jour de la fréquence
    const frequence = document.querySelector('input[name="frequence"]:checked')?.value;
    const intervalle = document.getElementById('intervalle_recurrence').value;

    const frequenceLabels = {
        'hebdomadaire': intervalle == 1 ? 'Chaque semaine' : `Toutes les ${intervalle} semaines`,
        'bimensuel': 'Toutes les 2 semaines',
        'mensuel': intervalle == 1 ? 'Chaque mois' : `Tous les ${intervalle} mois`,
        'trimestriel': 'Tous les 3 mois'
    };

    document.getElementById('preview_frequence').textContent = frequenceLabels[frequence] || 'Non défini';

    // Mise à jour du nombre
    const dureeType = document.querySelector('input[name="duree_type"]:checked')?.value;
    let nombreText = '';

    if (dureeType === 'nombre_occurrences') {
        const nombre = document.getElementById('nombre_occurrences').value;
        nombreText = `${nombre} occurrence${nombre > 1 ? 's' : ''}`;
    } else if (dureeType === 'date_fin') {
        const dateFin = document.getElementById('fin_recurrence').value;
        if (dateFin) {
            const dateFinFormatee = new Date(dateFin).toLocaleDateString('fr-FR');
            nombreText = `Jusqu'au ${dateFinFormatee}`;
        } else {
            nombreText = 'Date de fin non définie';
        }
    } else if (dureeType === 'infinie') {
        nombreText = 'Récurrence infinie';
    }

    document.getElementById('preview_nombre').textContent = nombreText;
}

function calculatePreviewDates() {
    if (!reunionDataRecurrence) return;

    const dateBase = new Date(reunionDataRecurrence.date_reunion);
    const frequence = document.querySelector('input[name="frequence"]:checked')?.value;
    const intervalle = parseInt(document.getElementById('intervalle_recurrence').value) || 1;
    const dureeType = document.querySelector('input[name="duree_type"]:checked')?.value;

    previewDates = [];

    // Calculer l'intervalle en jours
    let intervalleJours = 7; // par défaut hebdomadaire
    switch(frequence) {
        case 'hebdomadaire':
            intervalleJours = 7 * intervalle;
            break;
        case 'bimensuel':
            intervalleJours = 14;
            break;
        case 'mensuel':
            intervalleJours = 30 * intervalle; // approximatif
            break;
        case 'trimestriel':
            intervalleJours = 90; // approximatif
            break;
    }

    // Déterminer le nombre d'occurrences à calculer
    let nombreOccurrences = 4; // par défaut
    if (dureeType === 'nombre_occurrences') {
        nombreOccurrences = parseInt(document.getElementById('nombre_occurrences').value) || 4;
    } else if (dureeType === 'date_fin') {
        const dateFin = new Date(document.getElementById('fin_recurrence').value);
        if (dateFin > dateBase) {
            nombreOccurrences = Math.ceil((dateFin - dateBase) / (intervalleJours * 24 * 60 * 60 * 1000));
        }
    }

    // Limiter le nombre d'occurrences affichées dans l'aperçu
    nombreOccurrences = Math.min(nombreOccurrences, 10);

    // Générer les dates
    for (let i = 1; i <= nombreOccurrences; i++) {
        const nouvelleDate = new Date(dateBase);
        nouvelleDate.setDate(dateBase.getDate() + (i * intervalleJours));

        previewDates.push({
            date: nouvelleDate,
            numero: i,
            isWeekend: nouvelleDate.getDay() === 0 || nouvelleDate.getDay() === 6
        });
    }

    // Afficher les dates
    displayPreviewDates();
}

function displayPreviewDates() {
    const container = document.getElementById('preview_dates');
    const modificationTitre = document.getElementById('modification_titre').value;
    const prefixe = document.getElementById('prefixe_personnalise').value;

    if (previewDates.length === 0) {
        container.innerHTML = '<div class="text-xs text-blue-600">Aucune date calculée</div>';
        return;
    }

    let html = '';
    let hasWeekendWarning = false;

    previewDates.forEach((item, index) => {
        const dateFormatee = item.date.toLocaleDateString('fr-FR', {
            weekday: 'short',
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });

        let titre = reunionDataRecurrence?.titre || 'Réunion';
        switch(modificationTitre) {
            case 'numero':
                titre += ` #${item.numero}`;
                break;
            case 'date':
                titre += ` - ${item.date.toLocaleDateString('fr-FR')}`;
                break;
            case 'prefixe':
                if (prefixe) titre = `${prefixe} ${titre}`;
                break;
        }

        const weekendClass = item.isWeekend ? 'text-orange-600 font-medium' : 'text-blue-600';
        if (item.isWeekend) hasWeekendWarning = true;

        html += `<div class="flex justify-between items-center text-xs ${weekendClass}">
            <span>${titre}</span>
            <span>${dateFormatee}${item.isWeekend ? ' ⚠️' : ''}</span>
        </div>`;
    });

    if (previewDates.length >= 10) {
        html += '<div class="text-xs text-blue-500 italic">... et plus</div>';
    }

    container.innerHTML = html;

    // Afficher/masquer l'avertissement weekend
    const warningElement = document.getElementById('warning_dates');
    if (hasWeekendWarning) {
        warningElement.classList.remove('hidden');
    } else {
        warningElement.classList.add('hidden');
    }
}

function previsualiserPlanning() {
    if (previewDates.length === 0) {
        alert('Aucun planning à prévisualiser. Vérifiez vos paramètres.');
        return;
    }

    // Créer une fenêtre de prévisualisation
    const previewWindow = window.open('', 'planning_preview', 'width=800,height=600');

    let html = `
        <html>
            <head>
                <title>Aperçu du planning récurrent</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .header { border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
                    .reunion-base { background: #f1f5f9; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
                    .planning { border-collapse: collapse; width: 100%; }
                    .planning th, .planning td { border: 1px solid #e2e8f0; padding: 10px; text-align: left; }
                    .planning th { background: #4f46e5; color: white; }
                    .weekend { background: #fef3c7; color: #d97706; }
                    .actions { margin-top: 20px; text-align: center; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>Aperçu du planning récurrent</h2>
                    <p>Réunion : ${reunionDataRecurrence?.titre || 'Non défini'}</p>
                </div>

                <div class="reunion-base">
                    <h3>Réunion de base</h3>
                    <p><strong>Date :</strong> ${reunionDataRecurrence ? new Date(reunionDataRecurrence.date_reunion).toLocaleDateString('fr-FR') : 'Non définie'}</p>
                    <p><strong>Heure :</strong> ${reunionDataRecurrence?.heure_debut_prevue || 'Non définie'}</p>
                    <p><strong>Lieu :</strong> ${reunionDataRecurrence?.lieu || 'Non défini'}</p>
                </div>

                <h3>Planning généré (${previewDates.length} occurrences)</h3>
                <table class="planning">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Jour</th>
                            <th>Titre</th>
                            <th>Remarques</th>
                        </tr>
                    </thead>
                    <tbody>
    `;

    previewDates.forEach((item, index) => {
        const dateFormatee = item.date.toLocaleDateString('fr-FR');
        const jourFormate = item.date.toLocaleDateString('fr-FR', { weekday: 'long' });

        let titre = reunionDataRecurrence?.titre || 'Réunion';
        const modificationTitre = document.getElementById('modification_titre').value;
        const prefixe = document.getElementById('prefixe_personnalise').value;

        switch(modificationTitre) {
            case 'numero':
                titre += ` #${item.numero}`;
                break;
            case 'date':
                titre += ` - ${dateFormatee}`;
                break;
            case 'prefixe':
                if (prefixe) titre = `${prefixe} ${titre}`;
                break;
        }

        const rowClass = item.isWeekend ? 'weekend' : '';
        const remarques = item.isWeekend ? 'Weekend' : '';

        html += `
            <tr class="${rowClass}">
                <td>${item.numero}</td>
                <td>${dateFormatee}</td>
                <td>${jourFormate}</td>
                <td>${titre}</td>
                <td>${remarques}</td>
            </tr>
        `;
    });

    html += `
                    </tbody>
                </table>

                <div class="actions">
                    <button onclick="window.print()">Imprimer</button>
                    <button onclick="window.close()">Fermer</button>
                </div>
            </body>
        </html>
    `;

    previewWindow.document.write(html);
}

function creerRecurrence() {
    const form = document.getElementById('recurrenceForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('recurrence_reunion_id').value;

    // Validation côté client
    const frequence = document.querySelector('input[name="frequence"]:checked')?.value;
    const dureeType = document.querySelector('input[name="duree_type"]:checked')?.value;

    if (!frequence) {
        alert('Veuillez sélectionner une fréquence de récurrence.');
        return;
    }

    if (!dureeType) {
        alert('Veuillez sélectionner une durée de récurrence.');
        return;
    }

    if (dureeType === 'nombre_occurrences') {
        const nombre = parseInt(document.getElementById('nombre_occurrences').value);
        if (!nombre || nombre < 1 || nombre > 52) {
            alert('Le nombre d\'occurrences doit être entre 1 et 52.');
            document.getElementById('nombre_occurrences').focus();
            return;
        }
    }

    if (dureeType === 'date_fin') {
        const dateFin = document.getElementById('fin_recurrence').value;
        if (!dateFin) {
            alert('Veuillez sélectionner une date de fin.');
            document.getElementById('fin_recurrence').focus();
            return;
        }

        const dateFinParsed = new Date(dateFin);
        const dateBase = new Date(reunionDataRecurrence?.date_reunion);
        if (dateFinParsed <= dateBase) {
            alert('La date de fin doit être postérieure à la date de la réunion de base.');
            document.getElementById('fin_recurrence').focus();
            return;
        }
    }

    // Confirmation finale
    const nombreEstime = previewDates.length;
    if (!confirm(`Confirmer la création de ${nombreEstime} réunion(s) récurrente(s) ?`)) {
        return;
    }

    // Désactiver le bouton pour éviter les double-clics
    const submitButton = document.querySelector('#recurrenceModal button[onclick="creerRecurrence()"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Création en cours...';

    fetch(`<?php echo e(route('private.reunions.creer-recurrence', ':reunion')); ?>`.replace(':reunion', reunionId), {

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
        if (data.success || data.message?.includes('succès') || (data.data && Array.isArray(data.data))) {
            const nombreCrees = data.data ? data.data.length : nombreEstime;

            // Afficher un message de succès
            showSuccessMessage(`${nombreCrees} réunion(s) récurrente(s) créée(s) avec succès.`);

            // Fermer le modal
            closeRecurrenceModal();

            // Recharger la page pour mettre à jour les données
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur lors de la création de la récurrence:', error);
        alert(error.message || 'Une erreur est survenue lors de la création de la récurrence');
    })
    .finally(() => {
        // Réactiver le bouton
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('recurrenceModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRecurrenceModal();
    }
});

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('recurrenceModal').classList.contains('hidden')) {
        closeRecurrenceModal();
    }
});

// Événements pour les champs de durée
document.addEventListener('DOMContentLoaded', function() {
    // Définir une date de fin par défaut (dans 3 mois)
    const dateFin = new Date();
    dateFin.setMonth(dateFin.getMonth() + 3);
    document.getElementById('fin_recurrence').value = dateFin.toISOString().split('T')[0];

    // Initialiser l'aperçu
    updateRecurrencePreview();
});
</script>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/components/private/reunions/modals/recurrence.blade.php ENDPATH**/ ?>