

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
        min-height: 120px;
        max-height: 300px;
    }

    /* Style pour les éditeurs avancés */
    .ck-editor--advanced .ck-editor__editable {
        min-height: 300px;
        max-height: 800px;
    }

    /* Style pour les éditeurs ultra-avancés */
    .ck-editor--ultra .ck-editor__editable {
        min-height: 400px;
        max-height: 1000px;
        font-size: 14px;
        line-height: 1.8;
    }

    /* Adaptation au thème */
    .ck.ck-toolbar {
        border-radius: 0.75rem 0.75rem 0 0;
        border-color: #d1d5db;
        background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
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

    /* Compteur de mots */
    .ck-word-count-container {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding: 8px 12px;
        background-color: #f9fafb;
        border: 1px solid #d1d5db;
        border-top: none;
        border-radius: 0 0 0.75rem 0.75rem;
        font-size: 12px;
        color: #6b7280;
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
 * Configuration Ultra-Avancée de CKEditor 5 - Version 4 Optimisée
 * Structure améliorée avec toutes les fonctionnalités disponibles
 */

// ========================================
// CONFIGURATIONS PRÉDÉFINIES
// ========================================

/**
 * Configuration ULTRA-AVANCÉE
 * Pour les contenus riches nécessitant toutes les fonctionnalités
 */
const ultraAdvancedCKEditorConfig = {
    toolbar: {
        items: [
            'heading',
            '|',
            'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor',
            '|',
            'bold', 'italic', 'underline', 'strikethrough', 'subscript', 'superscript', 'code',
            '|',
            'alignment',
            '|',
            'numberedList', 'bulletedList',
            '|',
            'outdent', 'indent',
            '|',
            'link', 'blockQuote', 'insertTable',
            '|',
            'horizontalLine', 'specialCharacters',
            '|',
            'highlight', 'removeFormat',
            '|',
            'undo', 'redo'
        ],
        shouldNotGroupWhenFull: true
    },

    heading: {
        options: [
            { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
            { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
            { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
            { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' },
            { model: 'heading4', view: 'h4', title: 'Titre 4', class: 'ck-heading_heading4' },
            { model: 'heading5', view: 'h5', title: 'Titre 5', class: 'ck-heading_heading5' },
            { model: 'heading6', view: 'h6', title: 'Titre 6', class: 'ck-heading_heading6' }
        ]
    },

    fontSize: {
        options: [8, 9, 10, 11, 12, 'default', 14, 16, 18, 20, 22, 24, 26, 28, 32, 36, 48, 72],
        supportAllValues: true
    },

    fontFamily: {
        options: [
            'default',
            'Arial, sans-serif',
            'Courier New, monospace',
            'Georgia, serif',
            'Lucida Console, monospace',
            'Tahoma, sans-serif',
            'Times New Roman, serif',
            'Trebuchet MS, sans-serif',
            'Verdana, sans-serif',
            'DejaVu Sans, sans-serif'
        ],
        supportAllValues: true
    },

    fontColor: {
        colors: [
            { color: '#000000', label: 'Noir' },
            { color: '#4a4a4a', label: 'Gris foncé' },
            { color: '#999999', label: 'Gris' },
            { color: '#ffffff', label: 'Blanc', hasBorder: true },
            { color: '#e74c3c', label: 'Rouge' },
            { color: '#e67e22', label: 'Orange' },
            { color: '#f1c40f', label: 'Jaune' },
            { color: '#2ecc71', label: 'Vert' },
            { color: '#3498db', label: 'Bleu' },
            { color: '#9b59b6', label: 'Violet' },
            { color: '#1abc9c', label: 'Turquoise' },
            { color: '#34495e', label: 'Bleu marine' }
        ],
        columns: 6
    },

    fontBackgroundColor: {
        colors: [
            { color: '#ffffff', label: 'Blanc', hasBorder: true },
            { color: '#f8f9fa', label: 'Gris très clair' },
            { color: '#e9ecef', label: 'Gris clair' },
            { color: '#dee2e6', label: 'Gris' },
            { color: '#ffebee', label: 'Rouge clair' },
            { color: '#fff3e0', label: 'Orange clair' },
            { color: '#fff9c4', label: 'Jaune clair' },
            { color: '#e8f5e9', label: 'Vert clair' },
            { color: '#e3f2fd', label: 'Bleu clair' },
            { color: '#f3e5f5', label: 'Violet clair' },
            { color: '#e0f2f1', label: 'Turquoise clair' },
            { color: '#eceff1', label: 'Bleu-gris clair' }
        ],
        columns: 6
    },

    alignment: {
        options: ['left', 'center', 'right', 'justify']
    },

    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableProperties', 'tableCellProperties'],
        tableProperties: {
            borderColors: [
                { color: '#000000', label: 'Noir' },
                { color: '#e74c3c', label: 'Rouge' },
                { color: '#2ecc71', label: 'Vert' },
                { color: '#3498db', label: 'Bleu' }
            ],
            backgroundColors: [
                { color: '#ffffff', label: 'Blanc' },
                { color: '#f8f9fa', label: 'Gris clair' },
                { color: '#e3f2fd', label: 'Bleu clair' }
            ]
        },
        tableCellProperties: {
            borderColors: [
                { color: '#000000', label: 'Noir' },
                { color: '#e74c3c', label: 'Rouge' },
                { color: '#2ecc71', label: 'Vert' },
                { color: '#3498db', label: 'Bleu' }
            ],
            backgroundColors: [
                { color: '#ffffff', label: 'Blanc' },
                { color: '#f8f9fa', label: 'Gris clair' },
                { color: '#e3f2fd', label: 'Bleu clair' }
            ]
        }
    },

    link: {
        decorators: {
            openInNewTab: {
                mode: 'manual',
                label: 'Ouvrir dans un nouvel onglet',
                attributes: {
                    target: '_blank',
                    rel: 'noopener noreferrer'
                }
            }
        },
        addTargetToExternalLinks: true,
        defaultProtocol: 'https://'
    },

    highlight: {
        options: [
            { model: 'yellowMarker', class: 'marker-yellow', title: 'Marqueur jaune', color: '#fff59d', type: 'marker' },
            { model: 'greenMarker', class: 'marker-green', title: 'Marqueur vert', color: '#a5d6a7', type: 'marker' },
            { model: 'pinkMarker', class: 'marker-pink', title: 'Marqueur rose', color: '#f48fb1', type: 'marker' },
            { model: 'blueMarker', class: 'marker-blue', title: 'Marqueur bleu', color: '#90caf9', type: 'marker' },
            { model: 'redPen', class: 'pen-red', title: 'Stylo rouge', color: '#ef5350', type: 'pen' },
            { model: 'greenPen', class: 'pen-green', title: 'Stylo vert', color: '#66bb6a', type: 'pen' }
        ]
    },

    specialCharacters: {
        options: [
            { title: 'Euro', character: '€' },
            { title: 'Dollar', character: '$' },
            { title: 'Livre', character: '£' },
            { title: 'Yen', character: '¥' },
            { title: 'Copyright', character: '©' },
            { title: 'Marque déposée', character: '®' },
            { title: 'Paragraphe', character: '§' },
            { title: 'Degré', character: '°' },
            { title: 'Plus ou moins', character: '±' },
            { title: 'Multiplication', character: '×' },
            { title: 'Division', character: '÷' },
            { title: 'Inférieur ou égal', character: '≤' },
            { title: 'Supérieur ou égal', character: '≥' },
            { title: 'Différent de', character: '≠' },
            { title: 'Flèche droite', character: '→' },
            { title: 'Flèche gauche', character: '←' },
            { title: 'Flèche haut', character: '↑' },
            { title: 'Flèche bas', character: '↓' }
        ]
    },

    list: {
        properties: {
            styles: true,
            startIndex: true,
            reversed: true
        }
    },

    language: 'fr',
    placeholder: 'Commencez à écrire votre contenu ici...'
};

/**
 * Configuration AVANCÉE
 * Pour les contenus structurés avec formatage
 */
const advancedCKEditorConfig = {
    toolbar: [
        'heading',
        '|',
        'bold', 'italic', 'underline', 'strikethrough',
        '|',
        'fontSize', 'fontColor', 'fontBackgroundColor',
        '|',
        'numberedList', 'bulletedList',
        '|',
        'outdent', 'indent', 'alignment',
        '|',
        'link', 'blockQuote', 'insertTable',
        '|',
        'removeFormat',
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
        options: [9, 11, 13, 'default', 17, 19, 21]
    },
    fontColor: {
        colors: [
            { color: '#000000', label: 'Noir' },
            { color: '#e74c3c', label: 'Rouge' },
            { color: '#2ecc71', label: 'Vert' },
            { color: '#3498db', label: 'Bleu' }
        ]
    },
    fontBackgroundColor: {
        colors: [
            { color: '#ffffff', label: 'Blanc', hasBorder: true },
            { color: '#ffebee', label: 'Rouge clair' },
            { color: '#e8f5e9', label: 'Vert clair' },
            { color: '#e3f2fd', label: 'Bleu clair' }
        ]
    },
    alignment: {
        options: ['left', 'center', 'right', 'justify']
    },
    table: {
        contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
    },
    language: 'fr',
    placeholder: 'Saisissez votre texte ici...'
};

/**
 * Configuration BASIQUE
 * Pour les textes simples avec formatage minimal
 */
const basicCKEditorConfig = {
    toolbar: [
        'heading',
        '|',
        'bold', 'italic', 'underline',
        '|',
        'numberedList', 'bulletedList',
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

/**
 * Configuration SIMPLE
 * Pour les notes courtes et rapides
 */
const simpleCKEditorConfig = {
    toolbar: [
        'bold', 'italic',
        '|',
        'numberedList', 'bulletedList',
        '|',
        'undo', 'redo'
    ],
    language: 'fr',
    placeholder: 'Saisissez votre texte ici...'
};

// ========================================
// FONCTIONS UTILITAIRES
// ========================================

/**
 * Initialise CKEditor sur un élément
 * @param {string} selector - Sélecteur CSS de l'élément
 * @param {string} configType - Type de configuration ('simple', 'basic', 'advanced', 'ultra')
 * @param {object} customConfig - Configuration personnalisée (optionnel)
 * @returns {Promise} Promise de l'instance CKEditor
 */
function initializeCKEditor(selector, configType = 'basic', customConfig = {}) {
    const element = document.querySelector(selector);

    if (!element) {
        console.warn(`Élément CKEditor non trouvé: ${selector}`);
        return Promise.resolve(null);
    }

    // Vérifier si CKEditor est déjà initialisé sur cet élément
    if (element.classList.contains('ck-editor__editable')) {
        console.warn(`CKEditor déjà initialisé sur: ${selector}`);
        return Promise.resolve(null);
    }

    // Sélection de la configuration
    let config;
    switch (configType) {
        case 'ultra':
            config = { ...ultraAdvancedCKEditorConfig };
            break;
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

    // Ajustement du placeholder
    if (element.placeholder) {
        config.placeholder = element.placeholder;
    }

    // Initialisation de CKEditor
    return ClassicEditor
        .create(element, config)
        .then(editor => {
            // Ajouter une classe CSS selon le type
            const editorElement = editor.ui.view.element;
            editorElement.classList.add(`ck-editor--${configType}`);

            // Ajouter le compteur de mots pour ultra
            if (configType === 'ultra') {
                const wordCountContainer = document.createElement('div');
                wordCountContainer.className = 'ck-word-count-container';
                editorElement.parentNode.insertBefore(wordCountContainer, editorElement.nextSibling);

                // Mettre à jour le compteur
                editor.model.document.on('change:data', () => {
                    const data = editor.getData();
                    const text = data.replace(/<[^>]*>/g, '');
                    const words = text.trim().split(/\s+/).filter(word => word.length > 0).length;
                    const chars = text.length;

                    wordCountContainer.innerHTML = `
                        <span><strong>Mots:</strong> ${words}</span>
                        <span><strong>Caractères:</strong> ${chars}</span>
                    `;
                });
            }

            // Gestion des erreurs de validation
            const parentDiv = element.closest('.relative') || element.parentElement;
            if (parentDiv && parentDiv.querySelector('.text-red-600')) {
                editorElement.classList.add('has-error');
            }

            // Synchronisation avec le formulaire
            const form = element.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    element.value = editor.getData();
                });
            }

            // Sauvegarde automatique
            if (config.autoSave !== false) {
                setInterval(() => {
                    element.value = editor.getData();
                }, 5000);
            }

            // Event listener pour les changements
            editor.model.document.on('change:data', () => {
                element.value = editor.getData();
                element.dispatchEvent(new Event('input', { bubbles: true }));
            });

            // Stocker l'instance
            if (!window.CKEditorInstances) {
                window.CKEditorInstances = {};
            }
            window.CKEditorInstances[selector] = editor;

            console.log(`CKEditor initialisé avec succès: ${selector} (${configType})`);
            return editor;
        })
        .catch(error => {
            console.error(`Erreur lors de l'initialisation de CKEditor sur ${selector}:`, error);
            // Fallback: afficher le textarea en cas d'erreur
            element.style.display = 'block';
            return null;
        });
}

/**
 * Initialise plusieurs instances de CKEditor
 * @param {Array} configs - Tableau de configurations [{ selector, type, config }]
 * @returns {Promise<Array>} Promesse avec toutes les instances
 */
function initializeMultipleCKEditors(configs) {
    const promises = configs.map(({ selector, type = 'basic', config = {} }) => {
        return initializeCKEditor(selector, type, config);
    });
    return Promise.all(promises);
}

/**
 * Détruit une instance CKEditor
 * @param {string} selector - Sélecteur de l'éditeur à détruire
 */
function destroyCKEditor(selector) {
    if (window.CKEditorInstances && window.CKEditorInstances[selector]) {
        window.CKEditorInstances[selector].destroy()
            .then(() => {
                delete window.CKEditorInstances[selector];
                console.log(`CKEditor détruit: ${selector}`);
            })
            .catch(error => {
                console.error(`Erreur lors de la destruction de CKEditor ${selector}:`, error);
            });
    }
}

/**
 * Récupère le contenu d'un éditeur
 * @param {string} selector - Sélecteur de l'éditeur
 * @returns {string} Contenu HTML de l'éditeur
 */
function getCKEditorData(selector) {
    if (window.CKEditorInstances && window.CKEditorInstances[selector]) {
        return window.CKEditorInstances[selector].getData();
    }
    return '';
}

/**
 * Définit le contenu d'un éditeur
 * @param {string} selector - Sélecteur de l'éditeur
 * @param {string} data - Contenu HTML à définir
 */
function setCKEditorData(selector, data) {
    if (window.CKEditorInstances && window.CKEditorInstances[selector]) {
        window.CKEditorInstances[selector].setData(data);
    }
}

// ========================================
// CONFIGURATION DES ÉDITEURS PAR CHAMPS
// ========================================

/**
 * Mapping des champs avec leur configuration appropriée
 */
const EDITOR_FIELD_CONFIG = {
    ultra: [
        'resume_message',
        'plan_message',
        'message_principal',
        'contenu_temoignage',
        'resume',
        'contenu'
    ],
    advanced: [
        'description',
        'notes_pasteur',
        'notes_organisateur',
        'temoignages',
        'message_participants',
        'feedback_participants',
        'demande_priere',
        'instructions_priere',
        'details_complementaires',
        'decisions_prises',
        'actions_decidees',
        'recommandations'
    ],
    simple: [
        'adresse_lieu',
        'raison',
        'objectif',
        'contexte',
        'objectifs',
        'materiel_fourni',
        'materiel_apporter',
        'points_positifs',
        'points_forts',
        'points_amelioration',
        'motif_annulation',
        'point_positif',
        'message_rappel',
        'motif_suspension',
        'legende',
        'avertissement',
        'restrictions_usage'
    ]
};

/**
 * Fonction d'initialisation pour tous les formulaires
 */
function initializeCulteFormEditors() {
    if (typeof ClassicEditor === 'undefined') {
        console.warn('CKEditor n\'est pas encore chargé. Nouvelle tentative...');
        setTimeout(initializeCulteFormEditors, 100);
        return;
    }

    console.log('Initialisation des éditeurs CKEditor...');

    // Compteur pour le suivi
    let totalInitialized = 0;

    // Initialiser chaque type d'éditeur
    Object.entries(EDITOR_FIELD_CONFIG).forEach(([type, fields]) => {
        fields.forEach(fieldName => {
            const selector = `#${fieldName}`;
            const element = document.querySelector(selector);

            if (element) {
                initializeCKEditor(selector, type, {
                    placeholder: element.getAttribute('placeholder') || `Saisissez ${fieldName.replace(/_/g, ' ')}...`
                }).then(editor => {
                    if (editor) {
                        totalInitialized++;
                    }
                });
            }
        });
    });

    console.log(`Initialisation terminée: ${totalInitialized} éditeur(s) CKEditor créé(s)`);
}

// ========================================
// AUTO-INITIALISATION
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    // Attendre un court délai pour s'assurer que CKEditor est chargé
    setTimeout(initializeCulteFormEditors, 500);
});

// ========================================
// EXPORT DES FONCTIONS
// ========================================

if (typeof window !== 'undefined') {
    window.CKEditorUtils = {
        // Fonctions principales
        initializeCKEditor,
        initializeMultipleCKEditors,
        destroyCKEditor,
        getCKEditorData,
        setCKEditorData,
        initializeCulteFormEditors,

        // Configurations
        configs: {
            ultra: ultraAdvancedCKEditorConfig,
            advanced: advancedCKEditorConfig,
            basic: basicCKEditorConfig,
            simple: simpleCKEditorConfig
        },

        // Mapping des champs
        fieldConfig: EDITOR_FIELD_CONFIG,

        // Accès aux instances
        get instances() {
            return window.CKEditorInstances || {};
        }
    };

    console.log('CKEditor Utils chargé avec succès');
}
</script>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/partials/ckeditor-resources.blade.php ENDPATH**/ ?>