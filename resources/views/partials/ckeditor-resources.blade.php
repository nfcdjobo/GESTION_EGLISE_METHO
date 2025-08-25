{{-- resources/views/partials/ckeditor-resources.blade.php --}}

<!-- CKEditor 5 CSS -->
<style>
    /* Styles personnalisés pour CKEditor */
    .ck-editor__editable {
        min-height: 120px;
        max-height: 400px;
    }

    .ck-editor__editable.ck-focused {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Style pour les éditeurs simples */
    .ck-editor--simple .ck-editor__editable {
        min-height: 80px;
        max-height: 200px;
    }

    /* Style pour les éditeurs avancés */
    .ck-editor--advanced .ck-editor__editable {
        min-height: 200px;
        max-height: 600px;
    }

    /* Adaptation au thème */
    .ck.ck-toolbar {
        border-radius: 0.75rem 0.75rem 0 0;
        border-color: #d1d5db;
    }

    .ck.ck-editor__main > .ck-editor__editable {
        border-radius: 0 0 0.75rem 0.75rem;
        border-color: #d1d5db;
    }

    /* Style pour les erreurs de validation */
    .has-error .ck.ck-editor__main > .ck-editor__editable {
        border-color: #ef4444;
    }

    .has-error .ck.ck-toolbar {
        border-color: #ef4444;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .ck.ck-toolbar {
            flex-wrap: wrap;
        }

        .ck.ck-toolbar > .ck-toolbar__items {
            flex-wrap: wrap;
        }
    }
</style>

<!-- CKEditor 5 JavaScript -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.2.0/classic/ckeditor.js"></script>

<!-- Script de configuration personnalisé -->
<script>
/**
 * Configuration et initialisation de CKEditor 5
 * Utilisation : initializeCKEditor(selector, config)
 */

// Configuration de base pour CKEditor
const basicCKEditorConfig = {
    toolbar: [
        'heading',
        '|',
        'bold', 'italic', 'underline',
        '|',
        'bulletedList', 'numberedList',
        '|',
        'outdent', 'indent',
        '|',
        'link',
        '|',
        'undo', 'redo'
    ],
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
            { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
            { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' }
        ]
    },
    language: 'fr',
    placeholder: 'Saisissez votre texte ici...'
};

// Configuration avancée pour les textes plus complexes
const advancedCKEditorConfig = {
    toolbar: [
        'heading',
        '|',
        'bold', 'italic', 'underline', 'strikethrough',
        '|',
        'fontSize', 'fontColor', 'fontBackgroundColor',
        '|',
        'bulletedList', 'numberedList',
        '|',
        'outdent', 'indent', 'alignment',
        '|',
        'link', 'blockQuote', 'insertTable',
        '|',
        'undo', 'redo'
    ],
    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
            { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
            { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' }
        ]
    },
    fontSize: {
        options: [ 9, 11, 13, 'default', 17, 19, 21 ]
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
    },
    language: 'fr',
    placeholder: 'Saisissez votre texte ici...'
};

// Configuration simple pour les notes courtes
const simpleCKEditorConfig = {
    toolbar: [
        'bold', 'italic',
        '|',
        'bulletedList', 'numberedList',
        '|',
        'undo', 'redo'
    ],
    language: 'fr',
    placeholder: 'Saisissez votre texte ici...'
};

/**
 * Initialise CKEditor sur un élément
 * @param {string} selector - Sélecteur CSS de l'élément
 * @param {string} configType - Type de configuration ('basic', 'advanced', 'simple')
 * @param {object} customConfig - Configuration personnalisée (optionnel)
 */
function initializeCKEditor(selector, configType = 'basic', customConfig = {}) {
    const element = document.querySelector(selector);
    if (!element) {
        console.warn(`Élément CKEditor non trouvé: ${selector}`);
        return;
    }

    // Sélection de la configuration
    let config;
    switch (configType) {
        case 'advanced':
            config = { ...advancedCKEditorConfig };
            break;
        case 'simple':
            config = { ...simpleCKEditorConfig };
            break;
        case 'basic':
        default:
            config = { ...basicCKEditorConfig };
            break;
    }

    // Fusion avec la configuration personnalisée
    config = { ...config, ...customConfig };

    // Ajustement du placeholder si fourni dans l'élément
    if (element.placeholder) {
        config.placeholder = element.placeholder;
    }

    // Initialisation de CKEditor
    ClassicEditor
        .create(element, config)
        .then(editor => {

            // Ajouter une classe CSS selon le type de config
            const editorElement = editor.ui.view.element;
            editorElement.classList.add(`ck-editor--${configType}`);

            // Gestion des erreurs de validation Laravel
            const parentDiv = element.closest('.relative') || element.parentElement;
            if (parentDiv && parentDiv.querySelector('.text-red-600')) {
                editorElement.classList.add('has-error');
            }

            // Synchronisation avec le formulaire parent
            const form = element.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    // S'assurer que les données CKEditor sont synchronisées
                    element.value = editor.getData();
                });
            }

            // Sauvegarde automatique périodique (optionnel)
            if (config.autoSave !== false) {
                setInterval(() => {
                    element.value = editor.getData();
                }, 5000); // Sauvegarde toutes les 5 secondes
            }

            // Event listener pour les changements
            editor.model.document.on('change:data', () => {
                element.value = editor.getData();
                // Déclencher l'événement input pour la compatibilité
                element.dispatchEvent(new Event('input', { bubbles: true }));
            });
        })
        .catch(error => {
            console.error(`Erreur lors de l'initialisation de CKEditor sur ${selector}:`, error);
        });
}

/**
 * Initialise plusieurs instances de CKEditor
 * @param {Array} configs - Tableau de configurations [{ selector, type, config }]
 */
function initializeMultipleCKEditors(configs) {
    configs.forEach(({ selector, type = 'basic', config = {} }) => {
        initializeCKEditor(selector, type, config);
    });
}

/**
 * Fonction d'initialisation pour les formulaires de cultes
 */
function initializeCulteFormEditors() {
    // Attendre que CKEditor soit chargé
    if (typeof ClassicEditor === 'undefined') {
        console.warn('CKEditor n\'est pas encore chargé. Attente...');
        setTimeout(initializeCulteFormEditors, 100);
        return;
    }

    const editorConfigs = [
        // Champs de description - configuration de base
        {
            selector: '#description',
            type: 'basic',
            config: { placeholder: 'Description du culte' }
        },

        // Champs d'adresse - configuration simple
        {
            selector: '#adresse_lieu',
            type: 'simple',
            config: { placeholder: 'Adresse complète du lieu si différent de l\'église' }
        },

        // Résumé de message - configuration avancée
        {
            selector: '#resume_message',
            type: 'advanced',
            config: { placeholder: 'Résumé de la prédication' }
        },

        // Plan du message - configuration avancée
        {
            selector: '#plan_message',
            type: 'advanced',
            config: { placeholder: 'Plan détaillé du message' }
        },

        // Notes du pasteur - configuration de base
        {
            selector: '#notes_pasteur',
            type: 'basic',
            config: { placeholder: 'Notes et observations du pasteur' }
        },

        // Notes de l'organisateur - configuration de base
        {
            selector: '#notes_organisateur',
            type: 'basic',
            config: { placeholder: 'Notes organisationnelles' }
        },

        // Points forts - configuration simple
        {
            selector: '#points_forts',
            type: 'simple',
            config: { placeholder: 'Points positifs du culte' }
        },

        // Points d'amélioration - configuration simple
        {
            selector: '#points_amelioration',
            type: 'simple',
            config: { placeholder: 'Points à améliorer' }
        },

        // Témoignages - configuration de base
        {
            selector: '#temoignages',
            type: 'basic',
            config: { placeholder: 'Témoignages recueillis pendant le culte' }
        },

        // Raison (modal) - configuration simple
        {
            selector: '#raison',
            type: 'simple',
            config: { placeholder: 'Raison de l\'annulation ou du report...' }
        },

        // Raison (modal) - configuration simple
        {
            selector: '#objectif',
            type: 'simple',
            config: { placeholder: 'Objectif...' }
        },

        // Raison (modal) - configuration simple contexte
        {
            selector: '#contexte',
            type: 'simple',
            config: { placeholder: 'Contexte...' }
        }
    ];

    // Initialisation de tous les éditeurs présents sur la page
    editorConfigs.forEach(config => {
        if (document.querySelector(config.selector)) {
            initializeCKEditor(config.selector, config.type, config.config);
        }
    });
}

// Auto-initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Attendre un court délai pour s'assurer que CKEditor est chargé
    setTimeout(initializeCulteFormEditors, 500);
});

// Export des fonctions pour utilisation externe
if (typeof window !== 'undefined') {
    window.CKEditorUtils = {
        initializeCKEditor,
        initializeMultipleCKEditors,
        initializeCulteFormEditors,
        basicConfig: basicCKEditorConfig,
        advancedConfig: advancedCKEditorConfig,
        simpleConfig: simpleCKEditorConfig
    };
}
</script>
