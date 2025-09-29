<!-- Modal Duplication de Réunion -->
<div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-slate-900 flex items-center">
                    <i class="fas fa-copy text-purple-600 mr-3"></i>
                    Dupliquer la réunion
                </h3>
                <button type="button" onclick="closeDuplicateModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-xl">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-purple-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="font-semibold text-purple-800">Duplication</h4>
                        <p class="text-sm text-purple-700 mt-1">
                            Créer une nouvelle réunion basée sur celle-ci avec les mêmes paramètres et responsables.
                        </p>
                    </div>
                </div>
            </div>

            <form id="duplicateForm">
                @csrf
                <input type="hidden" id="duplicate_reunion_id" name="reunion_id">

                <div class="space-y-6">
                    <!-- Nouvelle date -->
                    <div>
                        <label for="nouvelle_date_duplicate" class="block text-sm font-medium text-slate-700 mb-2">
                            Nouvelle date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="nouvelle_date_duplicate" name="nouvelle_date" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                    </div>

                    <!-- Nouvelles heures -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="nouvelle_heure_debut_duplicate" class="block text-sm font-medium text-slate-700 mb-2">
                                Heure de début
                            </label>
                            <input type="time" id="nouvelle_heure_debut_duplicate" name="nouvelle_heure_debut"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                            <p class="text-xs text-slate-500 mt-1">Laisser vide pour conserver</p>
                        </div>

                        <div>
                            <label for="nouvelle_heure_fin_duplicate" class="block text-sm font-medium text-slate-700 mb-2">
                                Heure de fin
                            </label>
                            <input type="time" id="nouvelle_heure_fin_duplicate" name="nouvelle_heure_fin"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                        </div>
                    </div>

                    <!-- Nouveau titre -->
                    <div>
                        <label for="nouveau_titre_duplicate" class="block text-sm font-medium text-slate-700 mb-2">
                            Nouveau titre (optionnel)
                        </label>
                        <input type="text" id="nouveau_titre_duplicate" name="nouveau_titre" maxlength="200"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                            placeholder="Laisser vide pour ajouter '(Copie)' au titre actuel">
                    </div>

                    <!-- Nouveau lieu -->
                    <div>
                        <label for="nouveau_lieu_duplicate" class="block text-sm font-medium text-slate-700 mb-2">
                            Nouveau lieu (optionnel)
                        </label>
                        <input type="text" id="nouveau_lieu_duplicate" name="nouveau_lieu" maxlength="200"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                            placeholder="Laisser vide pour conserver le lieu actuel">
                    </div>

                    <!-- Options de duplication -->
                    <div class="bg-slate-50 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Que souhaitez-vous dupliquer ?</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="copier_participants" name="copier_participants" value="1"
                                    class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                <label for="copier_participants" class="ml-3 text-sm text-slate-700">
                                    Copier la liste des participants inscrits
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="copier_responsables" name="copier_responsables" value="1" checked
                                    class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                <label for="copier_responsables" class="ml-3 text-sm text-slate-700">
                                    Conserver les mêmes responsables (organisateur, animateur, etc.)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="copier_contenu" name="copier_contenu" value="1" checked
                                    class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                <label for="copier_contenu" class="ml-3 text-sm text-slate-700">
                                    Copier le contenu (message, passage biblique, matériel)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="copier_parametres" name="copier_parametres" value="1" checked
                                    class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                <label for="copier_parametres" class="ml-3 text-sm text-slate-700">
                                    Copier les paramètres (diffusion, enregistrement, frais)
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="copier_documents" name="copier_documents" value="1"
                                    class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500">
                                <label for="copier_documents" class="ml-3 text-sm text-slate-700">
                                    Copier les documents annexes
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Statut de la nouvelle réunion -->
                    <div>
                        <label for="statut_nouvelle_reunion" class="block text-sm font-medium text-slate-700 mb-2">
                            Statut de la nouvelle réunion
                        </label>
                        <select id="statut_nouvelle_reunion" name="statut"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                            <option value="planifiee" selected>Planifiée</option>
                            <option value="confirmee">Confirmée</option>
                            <option value="planifie">En préparation</option>
                        </select>
                    </div>

                    <!-- Suggestions de dates rapides -->
                    <div class="border border-slate-200 rounded-xl p-4">
                        <h4 class="font-semibold text-slate-800 mb-3">Suggestions rapides</h4>
                        <div class="grid grid-cols-3 gap-2 text-sm">
                            <button type="button" onclick="setSuggestedDateDuplicate(7)" class="p-2 text-left bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                <div class="font-medium">+1 semaine</div>
                                <div class="text-slate-600" id="suggestion_duplicate_1_week">-</div>
                            </button>
                            <button type="button" onclick="setSuggestedDateDuplicate(14)" class="p-2 text-left bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                <div class="font-medium">+2 semaines</div>
                                <div class="text-slate-600" id="suggestion_duplicate_2_weeks">-</div>
                            </button>
                            <button type="button" onclick="setSuggestedDateDuplicate(30)" class="p-2 text-left bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                                <div class="font-medium">+1 mois</div>
                                <div class="text-slate-600" id="suggestion_duplicate_1_month">-</div>
                            </button>
                        </div>
                    </div>

                    <!-- Aperçu de la duplication -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-eye text-blue-600 mr-3 mt-1"></i>
                            <div class="flex-1">
                                <h4 class="font-semibold text-blue-800 mb-2">Aperçu de la duplication</h4>
                                <div class="text-sm text-blue-700 space-y-1">
                                    <div class="flex justify-between">
                                        <span>Réunion originale :</span>
                                        <span id="reunion_originale_preview" class="font-medium">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Nouvelle date :</span>
                                        <span id="nouvelle_reunion_preview" class="font-medium text-green-700">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Nouveau titre :</span>
                                        <span id="nouveau_titre_preview" class="font-medium">-</span>
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
            <button type="button" onclick="closeDuplicateModal()"
                class="flex-1 px-6 py-3 text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                <i class="fas fa-times mr-2"></i>
                Annuler
            </button>
            <button type="button" onclick="confirmerDuplication()"
                class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors font-medium">
                <i class="fas fa-copy mr-2"></i>
                Dupliquer la réunion
            </button>
        </div>
    </div>
</div>

<script>
// Variables globales pour le modal de duplication
let currentReunionToDuplicate = null;
let originalReunionDat = null;

function openDuplicateModal(reunionId, reunionData = null) {
    currentReunionToDuplicate = reunionId;
    originalReunionDat = reunionData;

    document.getElementById('duplicate_reunion_id').value = reunionId;
    document.getElementById('duplicateModal').classList.remove('hidden');

    // Initialiser les suggestions de dates
    initializeDateSuggestionsDuplicate();

    // Définir la date par défaut (demain)
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('nouvelle_date_duplicate').value = tomorrow.toISOString().split('T')[0];

    // Mettre à jour l'aperçu
    updateDuplicatePreview();

    // Focus sur le premier champ
    setTimeout(() => {
        document.getElementById('nouvelle_date_duplicate').focus();
    }, 100);
}

function closeDuplicateModal() {
    document.getElementById('duplicateModal').classList.add('hidden');
    document.getElementById('duplicateForm').reset();
    currentReunionToDuplicate = null;
    originalReunionDat = null;
}

function initializeDateSuggestionsDuplicate() {
    const today = new Date();

    // Calculer les suggestions
    const oneWeek = new Date(today);
    oneWeek.setDate(today.getDate() + 7);

    const twoWeeks = new Date(today);
    twoWeeks.setDate(today.getDate() + 14);

    const oneMonth = new Date(today);
    oneMonth.setDate(today.getDate() + 30);

    // Formater et afficher
    document.getElementById('suggestion_duplicate_1_week').textContent = formatDateForDisplayShort(oneWeek);
    document.getElementById('suggestion_duplicate_2_weeks').textContent = formatDateForDisplayShort(twoWeeks);
    document.getElementById('suggestion_duplicate_1_month').textContent = formatDateForDisplayShort(oneMonth);
}

function setSuggestedDateDuplicate(daysFromNow) {
    const suggestedDate = new Date();
    suggestedDate.setDate(suggestedDate.getDate() + daysFromNow);

    document.getElementById('nouvelle_date_duplicate').value = suggestedDate.toISOString().split('T')[0];
    updateDuplicatePreview();
}

function updateDuplicatePreview() {
    const nouvelleDate = document.getElementById('nouvelle_date_duplicate').value;
    const nouveauTitre = document.getElementById('nouveau_titre_duplicate').value;

    // Mise à jour de l'aperçu
    if (nouvelleDate) {
        const dateObj = new Date(nouvelleDate);
        document.getElementById('nouvelle_reunion_preview').textContent = formatDateForDisplayShort(dateObj);
    } else {
        document.getElementById('nouvelle_reunion_preview').textContent = 'Non définie';
    }

    // Aperçu du titre
    if (nouveauTitre.trim()) {
        document.getElementById('nouveau_titre_preview').textContent = nouveauTitre;
    } else if (originalReunionDat && originalReunionDat.titre) {
        document.getElementById('nouveau_titre_preview').textContent = originalReunionDat.titre + ' (Copie)';
    } else {
        document.getElementById('nouveau_titre_preview').textContent = 'Titre original + (Copie)';
    }

    // Aperçu de la réunion originale
    if (originalReunionDat) {
        const originalDate = new Date(originalReunionDat.date_reunion);
        document.getElementById('reunion_originale_preview').textContent =
            formatDateForDisplayShort(originalDate) + ' - ' + originalReunionDat.titre;
    } else {
        document.getElementById('reunion_originale_preview').textContent = 'Réunion à dupliquer';
    }
}

function formatDateForDisplayShort(date) {
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

function confirmerDuplication() {
    const form = document.getElementById('duplicateForm');
    const formData = new FormData(form);
    const reunionId = document.getElementById('duplicate_reunion_id').value;

    // Validation côté client
    const nouvelleDate = document.getElementById('nouvelle_date_duplicate').value;

    if (!nouvelleDate) {
        alert('Veuillez sélectionner une nouvelle date.');
        document.getElementById('nouvelle_date_duplicate').focus();
        return;
    }

    // Vérifier que la nouvelle date est dans le futur
    const selectedDate = new Date(nouvelleDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate <= today) {
        alert('La nouvelle date doit être dans le futur.');
        document.getElementById('nouvelle_date_duplicate').focus();
        return;
    }

    // Vérifier les heures si elles sont renseignées
    const heureDebut = document.getElementById('nouvelle_heure_debut_duplicate').value;
    const heureFin = document.getElementById('nouvelle_heure_fin_duplicate').value;

    if (heureDebut && heureFin && heureDebut >= heureFin) {
        alert('L\'heure de fin doit être postérieure à l\'heure de début.');
        document.getElementById('nouvelle_heure_fin_duplicate').focus();
        return;
    }

    // Confirmation finale
    const formattedDate = formatDateForDisplayShort(selectedDate);
    if (!confirm(`Confirmer la duplication de cette réunion pour le ${formattedDate} ?`)) {
        return;
    }

    // Désactiver le bouton pour éviter les double-clics
    const submitButton = document.querySelector('#duplicateModal button[onclick="confirmerDuplication()"]');
    const originalText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Duplication en cours...';

    fetch(`{{route('private.reunions.dupliquer', ':reunion')}}`.replace(':reunion', reunionId), {
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
        if (data.success || data.message?.includes('succès') || data.data?.id) {
            // Afficher un message de succès
            showSuccessMessage(`Réunion dupliquée avec succès pour le ${formattedDate}.`);

            // Fermer le modal
            closeDuplicateModal();

            // Rediriger vers la nouvelle réunion ou recharger la page
            setTimeout(() => {
                if (data.data && data.data.id) {
                    window.location.href = `/reunions/${data.data.id}`;
                } else {
                    window.location.reload();
                }
            }, 1500);
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur lors de la duplication:', error);
        alert(error.message || 'Une erreur est survenue lors de la duplication de la réunion');
    })
    .finally(() => {
        // Réactiver le bouton
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
}

// Écouteurs d'événements
document.getElementById('nouvelle_date_duplicate').addEventListener('change', updateDuplicatePreview);
document.getElementById('nouveau_titre_duplicate').addEventListener('input', updateDuplicatePreview);

// Fermer le modal en cliquant à l'extérieur
document.getElementById('duplicateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDuplicateModal();
    }
});

// Fermer le modal avec la touche Échap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('duplicateModal').classList.contains('hidden')) {
        closeDuplicateModal();
    }
});

// Validation des heures en temps réel
document.getElementById('nouvelle_heure_debut_duplicate').addEventListener('change', function() {
    const heureDebut = this.value;
    const heureFin = document.getElementById('nouvelle_heure_fin_duplicate').value;

    if (heureDebut && heureFin && heureDebut >= heureFin) {
        alert('L\'heure de fin doit être postérieure à l\'heure de début.');
        document.getElementById('nouvelle_heure_fin_duplicate').focus();
    }
});

document.getElementById('nouvelle_heure_fin_duplicate').addEventListener('change', function() {
    const heureDebut = document.getElementById('nouvelle_heure_debut_duplicate').value;
    const heureFin = this.value;

    if (heureDebut && heureFin && heureDebut >= heureFin) {
        alert('L\'heure de fin doit être postérieure à l\'heure de début.');
        this.focus();
    }
});
</script>
