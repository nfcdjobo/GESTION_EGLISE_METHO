<!-- Modal Gestion des Présences -->
<div id="presencesModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-user-check text-green-600 mr-3"></i>
                    Marquer les présences
                </h3>
                <button type="button" onclick="closePresencesModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-green-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-green-800">Comptage des présences</h4>
                        <p class="text-sm text-green-700 mt-1">
                            Enregistrez le nombre exact de participants présents à cette réunion pour vos statistiques.
                        </p>
                    </div>
                </div>
            </div>

            <form id="presencesForm">
                @csrf
                <input type="hidden" id="presences_reunion_id" name="reunion_id">

                <div class="space-y-6">
                    <!-- Informations actuelles -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Informations actuelles</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Inscrits :</span>
                                <span id="current_inscrits" class="font-medium">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Places disponibles :</span>
                                <span id="current_places" class="font-medium">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Présents actuels :</span>
                                <span id="current_presents" class="font-medium text-green-600">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Taux de présence :</span>
                                <span id="current_taux" class="font-medium">-</span>
                            </div>
                        </div>
                    </div>

                    <!-- Comptage principal -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="nombre_adultes" class="block text-sm font-medium text-slate-700 mb-2">
                                Nombre d'adultes <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="nombre_adultes" name="nombre_adultes" min="0" required
                                    class="w-full px-4 py-3 pr-12 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-center text-lg font-semibold">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <div class="flex flex-col">
                                        <button type="button" onclick="incrementCounter('nombre_adultes')" class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                        <button type="button" onclick="decrementCounter('nombre_adultes')" class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="nombre_enfants" class="block text-sm font-medium text-slate-700 mb-2">
                                Nombre d'enfants
                            </label>
                            <div class="relative">
                                <input type="number" id="nombre_enfants" name="nombre_enfants" min="0" value="0"
                                    class="w-full px-4 py-3 pr-12 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-center text-lg font-semibold">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <div class="flex flex-col">
                                        <button type="button" onclick="incrementCounter('nombre_enfants')" class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                        <button type="button" onclick="decrementCounter('nombre_enfants')" class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="nombre_nouveaux" class="block text-sm font-medium text-slate-700 mb-2">
                                Nouveaux participants
                            </label>
                            <div class="relative">
                                <input type="number" id="nombre_nouveaux" name="nombre_nouveaux" min="0" value="0"
                                    class="w-full px-4 py-3 pr-12 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors text-center text-lg font-semibold">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <div class="flex flex-col">
                                        <button type="button" onclick="incrementCounter('nombre_nouveaux')" class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-chevron-up"></i>
                                        </button>
                                        <button type="button" onclick="decrementCounter('nombre_nouveaux')" class="text-green-600 hover:text-green-800 text-sm">
                                            <i class="fas fa-chevron-down"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé en temps réel -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-blue-800">Total des présents</h4>
                            <div class="text-2xl font-bold text-blue-600" id="total_presents">0</div>
                        </div>
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div class="text-center">
                                <div class="font-medium" id="pourcentage_adultes">0%</div>
                                <div class="text-blue-600">Adultes</div>
                            </div>
                            <div class="text-center">
                                <div class="font-medium" id="pourcentage_enfants">0%</div>
                                <div class="text-blue-600">Enfants</div>
                            </div>
                            <div class="text-center">
                                <div class="font-medium" id="pourcentage_nouveaux">0%</div>
                                <div class="text-blue-600">Nouveaux</div>
                            </div>
                        </div>
                    </div>

                    <!-- Comptage rapide -->
                    <div class="border border-slate-200 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Comptage rapide</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
                            <button type="button" onclick="setQuickCount(10)" class="p-3 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors text-center">
                                <div class="font-semibold">10</div>
                                <div class="text-slate-600">personnes</div>
                            </button>
                            <button type="button" onclick="setQuickCount(25)" class="p-3 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors text-center">
                                <div class="font-semibold">25</div>
                                <div class="text-slate-600">personnes</div>
                            </button>
                            <button type="button" onclick="setQuickCount(50)" class="p-3 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors text-center">
                                <div class="font-semibold">50</div>
                                <div class="text-slate-600">personnes</div>
                            </button>
                            <button type="button" onclick="setQuickCount(100)" class="p-3 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors text-center">
                                <div class="font-semibold">100</div>
                                <div class="text-slate-600">personnes</div>
                            </button>
                        </div>
                    </div>

                    <!-- Notes sur la présence -->
                    <div>
                        <label for="notes_presence" class="block text-sm font-medium text-slate-700 mb-2">
                            Notes sur la présence (optionnel)
                        </label>
                        <textarea id="notes_presence" name="notes_presence" rows="3"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"
                            placeholder="Observations particulières, problèmes de comptage, etc."></textarea>
                    </div>

                    <!-- Validation des données -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-yellow-800">Vérification</h4>
                                <div class="text-sm text-yellow-700 mt-1 space-y-1">
                                    <div id="validation_message">Vérifiez que le total correspond au nombre réel de personnes présentes.</div>
                                    <div id="validation_details" class="hidden">
                                        <div>• Le nombre d'inscrits était de <span id="validation_inscrits" class="font-medium">0</span></div>
                                        <div>• <span id="validation_difference" class="font-medium">0</span> personnes de différence avec les inscriptions</div>
                                        <div id="validation_warning" class="text-yellow-800 font-medium mt-1"></div>
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
            <button type="button" onclick="resetCounters()"
                class="px-4 py-2 text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors text-sm">
                <i class="fas fa-undo mr-2"></i>
                Remettre à zéro
            </button>
            <div class="flex space-x-3">
                <button type="button" onclick="closePresencesModal()"
                    class="px-6 py-3 text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </button>
                <button type="button" onclick="enregistrerPresences()"
                    class="px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Enregistrer les présences
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales pour le modal des présences
let currentReunionPresences = null;
let originalReunionData = null;

function openPresencesModal(reunionId, reunionData = null) {
    currentReunionPresences = reunionId;
    originalReunionData = reunionData;

    document.getElementById('presences_reunion_id').value = reunionId;
    document.getElementById('presencesModal').classList.remove('hidden');

    // Remplir les informations actuelles si disponibles
    if (reunionData) {
        document.getElementById('current_inscrits').textContent = reunionData.nombre_inscrits || '0';
        document.getElementById('current_places').textContent = reunionData.nombre_places_disponibles || 'Illimité';
        document.getElementById('current_presents').textContent = reunionData.nombre_participants_reel || '0';

        // Calculer le taux de présence
        if (reunionData.nombre_inscrits && reunionData.nombre_participants_reel) {
            const taux = Math.round((reunionData.nombre_participants_reel / reunionData.nombre_inscrits) * 100);
            document.getElementById('current_taux').textContent = taux + '%';
        }

        // Pré-remplir avec les valeurs existantes si disponibles
        if (reunionData.nombre_adultes) {
            document.getElementById('nombre_adultes').value = reunionData.nombre_adultes;
        }
        if (reunionData.nombre_enfants) {
            document.getElementById('nombre_enfants').value = reunionData.nombre_enfants;
        }
        if (reunionData.nombre_nouveaux) {
            document.getElementById('nombre_nouveaux').value = reunionData.nombre_nouveaux;
        }
    }

    // Mettre à jour les totaux
    updateTotals();

    // Focus sur le premier champ
    setTimeout(() => {
        document.getElementById('nombre_adultes').focus();
        document.getElementById('nombre_adultes').select();
    }, 100);
}

function closePresencesModal() {
    document.getElementById('presencesModal').classList.add('hidden');
    document.getElementById('presencesForm').reset();
    currentReunionPresences = null;
    originalReunionData = null;
}

function incrementCounter(fieldId) {
    const field = document.getElementById(fieldId);
    const currentValue = parseInt(field.value) || 0;
    field.value = currentValue + 1;
    updateTotals();
}

function decrementCounter(fieldId) {
    const field = document.getElementById(fieldId);
    const currentValue = parseInt(field.value) || 0;
    if (currentValue > 0) {
        field.value = currentValue - 1;
    }
    updateTotals();
}

function setQuickCount(total) {
    // Répartir approximativement (90% adultes, 10% enfants pour un événement typique)
    const adultes = Math.round(total * 0.9);
    const enfants = total - adultes;

    document.getElementById('nombre_adultes').value = adultes;
    document.getElementById('nombre_enfants').value = enfants;
    document.getElementById('nombre_nouveaux').value = 0;

    updateTotals();
}

function resetCounters() {
    if (confirm('Êtes-vous sûr de vouloir remettre tous les compteurs à zéro ?')) {
        document.getElementById('nombre_adultes').value = 0;
        document.getElementById('nombre_enfants').value = 0;
        document.getElementById('nombre_nouveaux').value = 0;
        document.getElementById('notes_presence').value = '';
        updateTotals();
    }
}

function updateTotals() {
    const adultes = parseInt(document.getElementById('nombre_adultes').value) || 0;
    const enfants = parseInt(document.getElementById('nombre_enfants').value) || 0;
    const nouveaux = parseInt(document.getElementById('nombre_nouveaux').value) || 0;
    const total = adultes + enfants;

    // Mettre à jour l'affichage du total
    document.getElementById('total_presents').textContent = total;

    // Calculer les pourcentages
    if (total > 0) {
        document.getElementById('pourcentage_adultes').textContent = Math.round((adultes / total) * 100) + '%';
        document.getElementById('pourcentage_enfants').textContent = Math.round((enfants / total) * 100) + '%';
        document.getElementById('pourcentage_nouveaux').textContent = Math.round((nouveaux / total) * 100) + '%';
    } else {
        document.getElementById('pourcentage_adultes').textContent = '0%';
        document.getElementById('pourcentage_enfants').textContent = '0%';
        document.getElementById('pourcentage_nouveaux').textContent = '0%';
    }

    // Validation et messages
    updateValidation(total, adultes, enfants, nouveaux);
}

function updateValidation(total, adultes, enfants, nouveaux) {
    const inscrits = parseInt(document.getElementById('current_inscrits').textContent) || 0;
    const difference = total - inscrits;

    document.getElementById('validation_inscrits').textContent = inscrits;
    document.getElementById('validation_difference').textContent = Math.abs(difference);

    let validationWarning = '';
    let showDetails = total > 0;

    if (total === 0) {
        document.getElementById('validation_message').textContent = 'Vérifiez que le total correspond au nombre réel de personnes présentes.';
        showDetails = false;
    } else if (difference > 0) {
        validationWarning = `${difference} personne(s) de plus que prévu - Vérifiez le comptage`;
        document.getElementById('validation_message').textContent = 'Plus de présents que d\'inscrits détectés.';
    } else if (difference < 0) {
        validationWarning = `${Math.abs(difference)} personne(s) de moins que prévu - Normal (absences)`;
        document.getElementById('validation_message').textContent = 'Moins de présents que d\'inscrits (absences).';
    } else {
        validationWarning = 'Nombres conformes aux inscriptions';
        document.getElementById('validation_message').textContent = 'Nombre de présents conforme aux inscriptions.';
    }

    document.getElementById('validation_warning').textContent = validationWarning;
    document.getElementById('validation_details').classList.toggle('hidden', !showDetails);

    // Validation des nouveaux participants
    if (nouveaux > total) {
        document.getElementById('nombre_nouveaux').value = total;
        alert('Le nombre de nouveaux participants ne peut pas dépasser le total des présents.');
    }
}

function enregistrerPresences() {
    const form = document.getElementById('presencesForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('presences_reunion_id').value;

    // Validation côté client
    const adultes = parseInt(document.getElementById('nombre_adultes').value) || 0;
    const enfants = parseInt(document.getElementById('nombre_enfants').value) || 0;
    const nouveaux = parseInt(document.getElementById('nombre_nouveaux').value) || 0;
    const total = adultes + enfants;

    if (total === 0) {
        alert('Veuillez saisir au moins un participant présent.');
        document.getElementById('nombre_adultes').focus();
        return;
    }

    if (nouveaux > total) {
        alert('Le nombre de nouveaux participants ne peut pas dépasser le total des présents.');
        document.getElementById('nombre_nouveaux').focus();
        return;
    }

    // Confirmation si écart important avec les inscriptions
    const inscrits = parseInt(document.getElementById('current_inscrits').textContent) || 0;
    const difference = Math.abs(total - inscrits);

    if (inscrits > 0 && difference > (inscrits * 0.3)) { // Plus de 30% d'écart
        if (!confirm(`Écart important détecté (${difference} personnes de différence avec les inscriptions). Confirmer l'enregistrement ?`)) {
            return;
        }
    }

    // Désactiver le bouton pour éviter les double-clics
    const submitButton = document.querySelector('#presencesModal button[onclick="enregistrerPresences()"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement...';

    fetch(`{{route('private.reunions.marquer-presences', ':reunion')}}`.replace(':reunion', reunionId), {
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
            showSuccessMessage(`Présences enregistrées avec succès : ${total} participants présents.`);

            // Fermer le modal
            closePresencesModal();

            // Recharger la page pour mettre à jour les statistiques
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur lors de l\'enregistrement des présences:', error);
        alert(error.message || 'Une erreur est survenue lors de l\'enregistrement des présences');
    })
    .finally(() => {
        // Réactiver le bouton
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

// Écouteurs d'événements pour la mise à jour automatique
document.getElementById('nombre_adultes').addEventListener('input', updateTotals);
document.getElementById('nombre_enfants').addEventListener('input', updateTotals);
document.getElementById('nombre_nouveaux').addEventListener('input', updateTotals);

// Fermer le modal en cliquant à l'extérieur
document.getElementById('presencesModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePresencesModal();
    }
});

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('presencesModal').classList.contains('hidden')) {
        closePresencesModal();
    }
});

// Support des touches fléchées pour incrémenter/décrémenter
document.addEventListener('keydown', function(e) {
    if (document.getElementById('presencesModal').classList.contains('hidden')) return;

    const activeElement = document.activeElement;
    if (activeElement && activeElement.type === 'number') {
        if (e.key === 'ArrowUp' && e.ctrlKey) {
            e.preventDefault();
            incrementCounter(activeElement.id);
        } else if (e.key === 'ArrowDown' && e.ctrlKey) {
            e.preventDefault();
            decrementCounter(activeElement.id);
        }
    }
});
</script>
