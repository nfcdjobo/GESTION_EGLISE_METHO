{{-- components/private/annonces/live-preview.blade.php --}}
@props([
    'formId' => 'annonceForm',
    'position' => 'sidebar', // sidebar, modal, inline
    'theme' => 'site-web' // site-web, culte, mobile
])

@php
    $previewClasses = [
        'sidebar' => 'sticky top-4',
        'modal' => 'fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4',
        'inline' => 'w-full'
    ];

    $themeStyles = [
        'site-web' => [
            'container' => 'bg-white rounded-xl shadow-lg border border-slate-200',
            'header' => 'bg-gradient-to-r from-blue-600 to-purple-600 text-white',
            'content' => 'bg-white'
        ],
        'culte' => [
            'container' => 'bg-slate-800 rounded-xl shadow-lg border border-slate-700',
            'header' => 'bg-gradient-to-r from-purple-600 to-indigo-600 text-white',
            'content' => 'bg-slate-800 text-white'
        ],
        'mobile' => [
            'container' => 'bg-white rounded-2xl shadow-lg border border-slate-200 max-w-sm',
            'header' => 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white',
            'content' => 'bg-white'
        ]
    ];

    $currentTheme = $themeStyles[$theme] ?? $themeStyles['site-web'];
@endphp

<div class="{{ $previewClasses[$position] ?? $previewClasses['sidebar'] }}">
    @if($position === 'modal')
        <!-- Overlay modal -->
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-800">Prévisualisation en Direct</h3>
                <button onclick="closePreview()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
    @endif

    <!-- Widget de prévisualisation -->
    <div class="bg-white/90 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <!-- Header du widget -->
        <div class="p-4 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    <i class="fas fa-eye text-purple-600 mr-2"></i>
                    Prévisualisation
                </h3>

                <!-- Sélecteur de thème -->
                <div class="flex items-center space-x-2">
                    <select id="preview-theme" onchange="changePreviewTheme(this.value)" class="text-xs px-2 py-1 border border-slate-300 rounded-md focus:ring-1 focus:ring-blue-500">
                        <option value="site-web" {{ $theme === 'site-web' ? 'selected' : '' }}>🌐 Site web</option>
                        <option value="culte" {{ $theme === 'culte' ? 'selected' : '' }}>⛪ Écran culte</option>
                        <option value="mobile" {{ $theme === 'mobile' ? 'selected' : '' }}>📱 Mobile</option>
                    </select>

                    @if($position !== 'modal')
                        <button onclick="openPreviewModal()" class="text-slate-400 hover:text-slate-600" title="Ouvrir en grand">
                            <i class="fas fa-expand-alt"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Aperçu de l'annonce -->
        <div class="p-4">
            <div id="live-preview-container" class="{{ $currentTheme['container'] }} overflow-hidden">

                <!-- Header de l'annonce selon le thème -->
                <div id="preview-header" class="{{ $currentTheme['header'] }} px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <!-- Icône selon le type -->
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <i id="preview-type-icon" class="fas fa-bullhorn text-white"></i>
                            </div>
                            <div>
                                <h4 id="preview-title" class="font-bold text-lg">Titre de votre annonce</h4>
                                <p id="preview-type-text" class="text-white/90 text-sm">Information</p>
                            </div>
                        </div>

                        <!-- Badge de priorité -->
                        <div id="preview-priority-badge" class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium">
                            Normal
                        </div>
                    </div>
                </div>

                <!-- Contenu principal -->
                <div id="preview-content" class="{{ $currentTheme['content'] }} p-6 space-y-4">

                    <!-- Informations de l'événement (conditionnel) -->
                    <div id="preview-event-details" class="hidden bg-green-50 border border-green-200 rounded-lg p-4">
                        <h5 class="font-semibold text-green-800 mb-3 flex items-center">
                            <i class="fas fa-calendar-event mr-2"></i>
                            Détails de l'événement
                        </h5>
                        <div class="space-y-2 text-sm text-green-700">
                            <div id="preview-event-date" class="flex items-center">
                                <i class="fas fa-calendar-alt w-4 mr-2"></i>
                                <span>Date non définie</span>
                            </div>
                            <div id="preview-event-location" class="flex items-center hidden">
                                <i class="fas fa-map-marker-alt w-4 mr-2"></i>
                                <span>Lieu non défini</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu de l'annonce -->
                    <div class="prose prose-sm max-w-none">
                        <div id="preview-content-text" class="text-slate-600">
                            Saisissez le contenu de votre annonce pour voir un aperçu en temps réel...
                        </div>
                    </div>

                    <!-- Contact -->
                    <div id="preview-contact" class="hidden flex items-center space-x-3 pt-4 border-t border-slate-200">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            ?
                        </div>
                        <div>
                            <p class="font-medium text-slate-800" id="preview-contact-name">Contact principal</p>
                            <p class="text-sm text-slate-600" id="preview-contact-email">email@example.com</p>
                        </div>
                    </div>

                    <!-- Options de diffusion -->
                    <div class="flex flex-wrap gap-2 pt-4 border-t border-slate-200">
                        <span id="preview-badge-web" class="hidden inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                            <i class="fas fa-globe mr-1"></i>
                            Site web
                        </span>
                        <span id="preview-badge-culte" class="hidden inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                            <i class="fas fa-church mr-1"></i>
                            Culte
                        </span>
                        <span id="preview-badge-audience" class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-medium">
                            <i class="fas fa-users mr-1"></i>
                            <span>Tous</span>
                        </span>
                    </div>

                    <!-- Date d'expiration -->
                    <div id="preview-expiration" class="hidden text-sm text-orange-600 flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span>Expire le <span id="preview-expiration-date"></span></span>
                    </div>
                </div>

                <!-- Footer avec métadonnées -->
                <div class="px-6 py-3 bg-slate-50 border-t border-slate-200 text-xs text-slate-500">
                    <div class="flex items-center justify-between">
                        <span>Aperçu en temps réel</span>
                        <span id="preview-timestamp">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Conseils contextuels -->
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-medium text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i>
                    Conseils
                </h4>
                <div id="preview-tips" class="text-sm text-blue-700 space-y-1">
                    <p>• Utilisez un titre accrocheur et informatif</p>
                    <p>• Précisez toujours la date et le lieu pour les événements</p>
                    <p>• Choisissez l'audience cible appropriée</p>
                </div>
            </div>

            <!-- Validation en temps réel -->
            <div id="validation-feedback" class="mt-4 space-y-2">
                <!-- Les messages de validation apparaîtront ici -->
            </div>
        </div>
    </div>

    @if($position === 'modal')
        </div> <!-- Fermeture du modal -->
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeLivePreview();
});

function initializeLivePreview() {
    const form = document.getElementById('{{ $formId }}');
    if (!form) return;

    // Écouter tous les changements dans le formulaire
    const fields = [
        'titre', 'contenu', 'type_annonce', 'niveau_priorite',
        'audience_cible', 'date_evenement', 'lieu_evenement',
        'contact_principal_id', 'afficher_site_web', 'annoncer_culte', 'expire_le'
    ];

    fields.forEach(fieldName => {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (field) {
            if (field.type === 'checkbox') {
                field.addEventListener('change', updateLivePreview);
            } else {
                field.addEventListener('input', debounce(updateLivePreview, 300));
                field.addEventListener('change', updateLivePreview);
            }
        }
    });

    // Mise à jour initiale
    updateLivePreview();
}

function updateLivePreview() {
    const form = document.getElementById('{{ $formId }}');
    if (!form) return;

    // Récupérer les valeurs du formulaire
    const data = {
        titre: getFieldValue('titre'),
        contenu: getFieldValue('contenu'),
        type_annonce: getFieldValue('type_annonce'),
        niveau_priorite: getFieldValue('niveau_priorite'),
        audience_cible: getFieldValue('audience_cible'),
        date_evenement: getFieldValue('date_evenement'),
        lieu_evenement: getFieldValue('lieu_evenement'),
        contact_principal_id: getFieldValue('contact_principal_id'),
        afficher_site_web: getFieldValue('afficher_site_web', 'checkbox'),
        annoncer_culte: getFieldValue('annoncer_culte', 'checkbox'),
        expire_le: getFieldValue('expire_le')
    };

    // Mettre à jour le titre
    document.getElementById('preview-title').textContent =
        data.titre || 'Titre de votre annonce';

    // Mettre à jour le type et l'icône
    const typeIcons = {
        'evenement': 'fas fa-calendar-alt',
        'administrative': 'fas fa-cog',
        'pastorale': 'fas fa-cross',
        'urgence': 'fas fa-exclamation-triangle',
        'information': 'fas fa-info-circle'
    };

    const typeLabels = {
        'evenement': 'Événement',
        'administrative': 'Administrative',
        'pastorale': 'Pastorale',
        'urgence': 'Urgence',
        'information': 'Information'
    };

    document.getElementById('preview-type-icon').className =
        typeIcons[data.type_annonce] || 'fas fa-bullhorn';
    document.getElementById('preview-type-text').textContent =
        typeLabels[data.type_annonce] || 'Information';

    // Mettre à jour la priorité
    const priorityBadge = document.getElementById('preview-priority-badge');
    const priorityLabels = { 'urgent': 'Urgent', 'important': 'Important', 'normal': 'Normal' };
    const priorityColors = {
        'urgent': 'bg-red-500 text-white',
        'important': 'bg-yellow-500 text-white',
        'normal': 'bg-white/20 text-white'
    };

    priorityBadge.textContent = priorityLabels[data.niveau_priorite] || 'Normal';
    priorityBadge.className = `px-3 py-1 rounded-full text-sm font-medium ${priorityColors[data.niveau_priorite] || priorityColors.normal}`;

    // Mettre à jour le contenu
    const contentDiv = document.getElementById('preview-content-text');
    if (data.contenu) {
        contentDiv.innerHTML = data.contenu.replace(/\n/g, '<br>');
        contentDiv.className = 'text-slate-700';
    } else {
        contentDiv.textContent = 'Saisissez le contenu de votre annonce pour voir un aperçu en temps réel...';
        contentDiv.className = 'text-slate-400 italic';
    }

    // Gestion des détails d'événement
    const eventDetails = document.getElementById('preview-event-details');
    const eventDate = document.getElementById('preview-event-date');
    const eventLocation = document.getElementById('preview-event-location');

    if (data.type_annonce === 'evenement') {
        eventDetails.classList.remove('hidden');

        if (data.date_evenement) {
            const date = new Date(data.date_evenement);
            eventDate.querySelector('span').textContent = date.toLocaleDateString('fr-FR', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
        } else {
            eventDate.querySelector('span').textContent = 'Date non définie';
        }

        if (data.lieu_evenement) {
            eventLocation.classList.remove('hidden');
            eventLocation.querySelector('span').textContent = data.lieu_evenement;
        } else {
            eventLocation.classList.add('hidden');
        }
    } else {
        eventDetails.classList.add('hidden');
    }

    // Gestion du contact
    const contactDiv = document.getElementById('preview-contact');
    const contactName = document.getElementById('preview-contact-name');
    const contactEmail = document.getElementById('preview-contact-email');

    if (data.contact_principal_id) {
        const contactSelect = form.querySelector('[name="contact_principal_id"]');
        const selectedOption = contactSelect?.querySelector(`option[value="${data.contact_principal_id}"]`);

        if (selectedOption) {
            contactDiv.classList.remove('hidden');
            const contactText = selectedOption.textContent;
            const matches = contactText.match(/^(.+?)\s*\((.+)\)$/);

            if (matches) {
                contactName.textContent = matches[1].trim();
                contactEmail.textContent = matches[2];
            } else {
                contactName.textContent = contactText;
                contactEmail.textContent = '';
            }

            // Initiales pour l'avatar
            const initials = contactName.textContent.split(' ').map(word => word.charAt(0)).join('');
            contactDiv.querySelector('.w-8.h-8').textContent = initials.substr(0, 2);
        }
    } else {
        contactDiv.classList.add('hidden');
    }

    // Badges de diffusion
    document.getElementById('preview-badge-web').classList.toggle('hidden', !data.afficher_site_web);
    document.getElementById('preview-badge-culte').classList.toggle('hidden', !data.annoncer_culte);

    // Audience
    const audienceSpan = document.getElementById('preview-badge-audience').querySelector('span');
    const audienceLabels = { 'tous': 'Tous', 'membres': 'Membres', 'leadership': 'Leadership', 'jeunes': 'Jeunes' };
    audienceSpan.textContent = audienceLabels[data.audience_cible] || 'Tous';

    // Date d'expiration
    const expirationDiv = document.getElementById('preview-expiration');
    const expirationDate = document.getElementById('preview-expiration-date');

    if (data.expire_le) {
        const expireDate = new Date(data.expire_le);
        expirationDate.textContent = expireDate.toLocaleDateString('fr-FR');
        expirationDiv.classList.remove('hidden');
    } else {
        expirationDiv.classList.add('hidden');
    }

    // Mettre à jour l'horodatage
    document.getElementById('preview-timestamp').textContent =
        new Date().toLocaleString('fr-FR');

    // Validation en temps réel
    validateAnnonce(data);

    // Conseils contextuels
    updateContextualTips(data);
}

function getFieldValue(name, type = 'text') {
    const form = document.getElementById('{{ $formId }}');
    const field = form?.querySelector(`[name="${name}"]`);

    if (!field) return type === 'checkbox' ? false : '';

    if (type === 'checkbox') {
        return field.checked;
    }

    // Gérer CKEditor si présent
    if (window.CKEditorInstances && window.CKEditorInstances[`#${field.id}`]) {
        return window.CKEditorInstances[`#${field.id}`].getData();
    }

    return field.value;
}

function validateAnnonce(data) {
    const validationDiv = document.getElementById('validation-feedback');
    const errors = [];
    const warnings = [];
    const suggestions = [];

    // Validations critiques
    if (!data.titre) {
        errors.push('Le titre est obligatoire');
    } else if (data.titre.length < 5) {
        warnings.push('Le titre est très court (moins de 5 caractères)');
    }

    if (!data.contenu) {
        errors.push('Le contenu est obligatoire');
    } else if (data.contenu.length < 20) {
        warnings.push('Le contenu semble incomplet (moins de 20 caractères)');
    }

    if (!data.type_annonce) {
        errors.push('Le type d\'annonce est obligatoire');
    }

    // Validations spécifiques aux événements
    if (data.type_annonce === 'evenement') {
        if (!data.date_evenement) {
            errors.push('La date de l\'événement est obligatoire');
        } else {
            const eventDate = new Date(data.date_evenement);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (eventDate < today) {
                errors.push('La date de l\'événement ne peut pas être dans le passé');
            }
        }

        if (!data.lieu_evenement) {
            errors.push('Le lieu de l\'événement est obligatoire');
        }
    }

    // Suggestions d'amélioration
    if (data.type_annonce === 'urgence' && data.niveau_priorite !== 'urgent') {
        warnings.push('Une annonce d\'urgence devrait avoir une priorité urgente');
    }

    if (!data.contact_principal_id && data.type_annonce === 'evenement') {
        suggestions.push('Considérez ajouter un contact principal pour cet événement');
    }

    if (data.expire_le) {
        const expireDate = new Date(data.expire_le);
        const daysDiff = Math.ceil((expireDate - new Date()) / (1000 * 60 * 60 * 24));

        if (daysDiff < 1) {
            warnings.push('L\'annonce expire très bientôt');
        } else if (daysDiff > 365) {
            suggestions.push('Date d\'expiration très éloignée (plus d\'un an)');
        }
    }

    // Afficher les retours
    displayValidationFeedback(errors, warnings, suggestions);
}

function displayValidationFeedback(errors, warnings, suggestions) {
    const validationDiv = document.getElementById('validation-feedback');
    let html = '';

    if (errors.length > 0) {
        html += `
            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                <h5 class="font-medium text-red-800 text-sm mb-2 flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Erreurs (${errors.length})
                </h5>
                <ul class="text-sm text-red-600 space-y-1">
                    ${errors.map(error => `<li>• ${error}</li>`).join('')}
                </ul>
            </div>
        `;
    }

    if (warnings.length > 0) {
        html += `
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                <h5 class="font-medium text-yellow-800 text-sm mb-2 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Avertissements (${warnings.length})
                </h5>
                <ul class="text-sm text-yellow-600 space-y-1">
                    ${warnings.map(warning => `<li>• ${warning}</li>`).join('')}
                </ul>
            </div>
        `;
    }

    if (suggestions.length > 0 && errors.length === 0) {
        html += `
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <h5 class="font-medium text-blue-800 text-sm mb-2 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i>
                    Suggestions (${suggestions.length})
                </h5>
                <ul class="text-sm text-blue-600 space-y-1">
                    ${suggestions.map(suggestion => `<li>• ${suggestion}</li>`).join('')}
                </ul>
            </div>
        `;
    }

    if (errors.length === 0 && warnings.length === 0) {
        html += `
            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                <p class="font-medium text-green-800 text-sm flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Annonce prête à être publiée
                </p>
            </div>
        `;
    }

    validationDiv.innerHTML = html;
}

function updateContextualTips(data) {
    const tipsDiv = document.getElementById('preview-tips');
    const tips = [];

    if (!data.titre) {
        tips.push('• Commencez par saisir un titre accrocheur');
    }

    if (data.type_annonce === 'evenement') {
        tips.push('• N\'oubliez pas la date et le lieu de l\'événement');
        tips.push('• Ajoutez un contact pour les questions');
    }

    if (data.type_annonce === 'urgence') {
        tips.push('• Les annonces urgentes sont mises en évidence');
        tips.push('• Définissez une date d\'expiration courte');
    }

    if (!data.expire_le) {
        tips.push('• Considérez ajouter une date d\'expiration');
    }

    if (data.audience_cible !== 'tous') {
        tips.push('• Cette annonce sera visible uniquement par l\'audience ciblée');
    }

    tipsDiv.innerHTML = tips.length > 0 ? tips.join('<br>') : '• Votre annonce semble bien configurée!';
}

function changePreviewTheme(theme) {
    // Cette fonction pourrait changer l'apparence de la prévisualisation
    const container = document.getElementById('live-preview-container');
    const header = document.getElementById('preview-header');
    const content = document.getElementById('preview-content');

    // Réinitialiser les classes
    container.className = 'overflow-hidden';
    header.className = 'px-6 py-4';
    content.className = 'p-6 space-y-4';

    switch(theme) {
        case 'site-web':
            container.classList.add('bg-white', 'rounded-xl', 'shadow-lg', 'border', 'border-slate-200');
            header.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-purple-600', 'text-white');
            content.classList.add('bg-white');
            break;

        case 'culte':
            container.classList.add('bg-slate-800', 'rounded-xl', 'shadow-lg', 'border', 'border-slate-700');
            header.classList.add('bg-gradient-to-r', 'from-purple-600', 'to-indigo-600', 'text-white');
            content.classList.add('bg-slate-800', 'text-white');
            break;

        case 'mobile':
            container.classList.add('bg-white', 'rounded-2xl', 'shadow-lg', 'border', 'border-slate-200', 'max-w-sm', 'mx-auto');
            header.classList.add('bg-gradient-to-r', 'from-blue-500', 'to-cyan-500', 'text-white');
            content.classList.add('bg-white');
            break;
    }

    updateLivePreview();
}

function openPreviewModal() {
    // Créer une version modal de la prévisualisation
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4';
    modal.id = 'preview-modal';

    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-xl font-bold text-slate-800">Prévisualisation Complète</h3>
                <button onclick="closePreviewModal()" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-6">
                ${document.getElementById('live-preview-container').outerHTML}
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Fermer en cliquant sur l'overlay
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closePreviewModal();
        }
    });
}

function closePreviewModal() {
    const modal = document.getElementById('preview-modal');
    if (modal) {
        modal.remove();
    }
}

// Fonction utilitaire de debounce
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Gestion des touches de raccourci
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + P pour ouvrir la prévisualisation en grand
    if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
        e.preventDefault();
        openPreviewModal();
    }

    // Escape pour fermer la modal
    if (e.key === 'Escape') {
        closePreviewModal();
    }
});
</script>
@endpush

@push('styles')
<style>
/* Animations pour la prévisualisation */
#live-preview-container {
    transition: all 0.3s ease;
}

.validation-message {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Styles spécifiques aux thèmes */
.theme-culte .text-slate-700 {
    color: #e2e8f0 !important;
}

.theme-culte .bg-green-50 {
    background-color: #374151 !important;
    border-color: #4b5563 !important;
}

/* Responsive pour la prévisualisation mobile */
.preview-mobile {
    max-width: 375px;
    margin: 0 auto;
}

@media (max-width: 640px) {
    .preview-modal .max-w-4xl {
        max-width: 95vw;
    }
}
</style>
@endpush
