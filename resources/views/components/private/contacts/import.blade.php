@extends('layouts.private.main')
@section('title', 'Importer des Contacts')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Importer des Contacts</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.contacts.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-church mr-2"></i>
                        Contacts
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Importer</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Instructions et aide -->
    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-2xl p-6">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-info-circle text-white text-xl"></i>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-blue-900 mb-2">Instructions d'import</h3>
                <div class="text-blue-800 space-y-2">
                    <p>• Formats supportés : CSV, Excel (.xlsx, .xls)</p>
                    <p>• Taille maximale : 10 MB</p>
                    <p>• Maximum 1000 lignes par fichier</p>
                    <p>• Téléchargez d'abord le modèle pour connaître le format attendu</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulaire d'import -->
        <div class="lg:col-span-2">
            <!-- Étape 1: Télécharger le modèle -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 mb-8">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 rounded-full mr-3 text-sm font-bold">1</span>
                        Télécharger le Modèle
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-slate-600 mb-4">Téléchargez le fichier modèle pour connaître la structure exacte attendue.</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('private.contacts.import-template') }}?format=csv" class="flex items-center justify-center p-4 border-2 border-dashed border-green-300 rounded-xl hover:border-green-400 hover:bg-green-50 transition-colors">
                            <div class="text-center">
                                <i class="fas fa-file-csv text-green-600 text-2xl mb-2"></i>
                                <p class="text-sm font-medium text-green-600">Modèle CSV</p>
                                <p class="text-xs text-green-500">Format universel</p>
                            </div>
                        </a>
                        <a href="{{ route('private.contacts.import-template') }}?format=excel" class="flex items-center justify-center p-4 border-2 border-dashed border-blue-300 rounded-xl hover:border-blue-400 hover:bg-blue-50 transition-colors">
                            <div class="text-center">
                                <i class="fas fa-file-excel text-blue-600 text-2xl mb-2"></i>
                                <p class="text-sm font-medium text-blue-600">Modèle Excel</p>
                                <p class="text-xs text-blue-500">Avec formules</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Étape 2: Préparer le fichier -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 mb-8">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-amber-100 text-amber-600 rounded-full mr-3 text-sm font-bold">2</span>
                        Préparer Votre Fichier
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <h4 class="font-semibold text-amber-800 mb-2">Champs obligatoires</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-amber-700">
                                <div>• nom_eglise</div>
                                <div>• type_contact</div>
                                <div>• telephone_principal</div>
                                <div>• email_principal</div>
                            </div>
                        </div>

                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                            <h4 class="font-semibold text-blue-800 mb-2">Champs recommandés</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-blue-700">
                                <div>• denomination</div>
                                <div>• ville</div>
                                <div>• adresse_complete</div>
                                <div>• pasteur_principal</div>
                            </div>
                        </div>

                        <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                            <h4 class="font-semibold text-green-800 mb-2">Types de contact acceptés</h4>
                            <div class="flex flex-wrap gap-2 text-sm">
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">principal</span>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">pastoral</span>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">administratif</span>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">urgence</span>
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded">jeunesse</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Étape 3: Uploader le fichier -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-600 rounded-full mr-3 text-sm font-bold">3</span>
                        Importer le Fichier
                    </h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('private.contacts.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf

                        <!-- Zone de drop -->
                        <div class="mb-6">
                            <div id="dropZone" class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-blue-400 hover:bg-blue-50 transition-colors cursor-pointer">
                                <div id="dropZoneContent">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-4"></i>
                                    <p class="text-lg font-medium text-slate-600 mb-2">Glissez votre fichier ici</p>
                                    <p class="text-sm text-slate-500 mb-4">ou cliquez pour parcourir</p>
                                    <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-folder-open mr-2"></i> Parcourir
                                    </button>
                                </div>
                                <input type="file" id="fileInput" name="import_file" accept=".csv,.xlsx,.xls" class="hidden" required>
                            </div>

                            <div id="fileInfo" class="hidden mt-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file text-green-600"></i>
                                        <div>
                                            <p id="fileName" class="font-medium text-green-800"></p>
                                            <p id="fileSize" class="text-sm text-green-600"></p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Options d'import -->
                        <div class="space-y-4 mb-6">
                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div>
                                    <label for="skip_duplicates" class="text-sm font-medium text-slate-700">Ignorer les doublons</label>
                                    <p class="text-xs text-slate-500">Éviter d'importer les contacts existants</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="skip_duplicates" name="skip_duplicates" value="1" checked class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div>
                                    <label for="validate_emails" class="text-sm font-medium text-slate-700">Valider les emails</label>
                                    <p class="text-xs text-slate-500">Vérifier le format des adresses email</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="validate_emails" name="validate_emails" value="1" checked class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                                <div>
                                    <label for="auto_verify" class="text-sm font-medium text-slate-700">Vérification automatique</label>
                                    <p class="text-xs text-slate-500">Marquer automatiquement comme vérifiés</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="auto_verify" name="auto_verify" value="1" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button type="submit" id="importBtn" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas fa-upload mr-2"></i> Importer les Contacts
                            </button>
                            <a href="{{ route('private.contacts.index') }}" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                <i class="fas fa-times mr-2"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Colonne de droite -->
        <div class="space-y-6">
            <!-- Aperçu du mapping -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-table text-purple-600 mr-2"></i>
                        Mapping des Champs
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center p-2 bg-slate-50 rounded-lg">
                            <span class="font-medium text-slate-700">nom_eglise</span>
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">Obligatoire</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-slate-50 rounded-lg">
                            <span class="font-medium text-slate-700">type_contact</span>
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">Obligatoire</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-slate-50 rounded-lg">
                            <span class="font-medium text-slate-700">telephone_principal</span>
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">Obligatoire</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-slate-50 rounded-lg">
                            <span class="font-medium text-slate-700">email_principal</span>
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">Obligatoire</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-slate-50 rounded-lg">
                            <span class="font-medium text-slate-700">denomination</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">Optionnel</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-slate-50 rounded-lg">
                            <span class="font-medium text-slate-700">ville</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">Optionnel</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-slate-50 rounded-lg">
                            <span class="font-medium text-slate-700">adresse_complete</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">Optionnel</span>
                        </div>
                        <div class="flex justify-between items-center p-2 bg-slate-50 rounded-lg">
                            <span class="font-medium text-slate-700">pasteur_principal</span>
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">Optionnel</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aide et support -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-question-circle text-amber-600 mr-2"></i>
                        Aide et Support
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <h4 class="font-medium text-amber-800 mb-1">Problème d'import ?</h4>
                        <p class="text-sm text-amber-700">Vérifiez que votre fichier respecte le format du modèle téléchargé.</p>
                    </div>

                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="font-medium text-blue-800 mb-1">Format de date</h4>
                        <p class="text-sm text-blue-700">Utilisez le format JJ/MM/AAAA ou AAAA-MM-JJ pour les dates.</p>
                    </div>

                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                        <h4 class="font-medium text-green-800 mb-1">Encodage</h4>
                        <p class="text-sm text-green-700">Sauvegardez vos fichiers CSV en UTF-8 pour éviter les problèmes d'accents.</p>
                    </div>

                    <button onclick="showHelp()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded-xl hover:bg-amber-700 transition-colors">
                        <i class="fas fa-life-ring mr-2"></i> Documentation complète
                    </button>
                </div>
            </div>

            <!-- Historique des imports -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-slate-600 mr-2"></i>
                        Derniers Imports
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-green-800">150 contacts</p>
                                <p class="text-xs text-green-600">Il y a 2 jours</p>
                            </div>
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-yellow-800">25 contacts</p>
                                <p class="text-xs text-yellow-600">Il y a 1 semaine</p>
                            </div>
                            <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-blue-800">300 contacts</p>
                                <p class="text-xs text-blue-600">Il y a 2 semaines</p>
                            </div>
                            <i class="fas fa-check-circle text-blue-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de progression -->
<div id="progressModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="text-center mb-4">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-upload text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Import en cours...</h3>
                <p class="text-slate-600">Veuillez patienter pendant le traitement de votre fichier.</p>
            </div>

            <div class="mb-4">
                <div class="flex items-center justify-between text-sm text-slate-600 mb-2">
                    <span>Progression</span>
                    <span id="progressPercent">0%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <div id="progressStatus" class="text-sm text-slate-600 text-center">
                Initialisation...
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Variables globales
let selectedFile = null;

document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const importForm = document.getElementById('importForm');

    // Gestion du drag & drop
    dropZone.addEventListener('click', () => fileInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-blue-400', 'bg-blue-50');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFileSelect(files[0]);
        }
    });

    // Gestion de la sélection de fichier
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    // Gestion de la soumission du formulaire
    importForm.addEventListener('submit', handleFormSubmit);
});

// Gérer la sélection de fichier
function handleFileSelect(file) {
    // Vérifier le type de fichier
    const allowedTypes = [
        'text/csv',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    if (!allowedTypes.includes(file.type) && !file.name.match(/\.(csv|xlsx|xls)$/i)) {
        alert('Format de fichier non supporté. Utilisez CSV, XLS ou XLSX.');
        return;
    }

    // Vérifier la taille
    if (file.size > 10 * 1024 * 1024) { // 10MB
        alert('Le fichier est trop volumineux. Taille maximale : 10MB.');
        return;
    }

    selectedFile = file;
    document.getElementById('fileInput').files = createFileList(file);

    // Afficher les informations du fichier
    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = formatFileSize(file.size);
    document.getElementById('fileInfo').classList.remove('hidden');
    document.getElementById('dropZoneContent').innerHTML = `
        <i class="fas fa-file text-4xl text-green-600 mb-4"></i>
        <p class="text-lg font-medium text-green-600 mb-2">Fichier sélectionné</p>
        <p class="text-sm text-green-500">${file.name}</p>
    `;
}

// Supprimer le fichier sélectionné
function removeFile() {
    selectedFile = null;
    document.getElementById('fileInput').value = '';
    document.getElementById('fileInfo').classList.add('hidden');
    document.getElementById('dropZoneContent').innerHTML = `
        <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-4"></i>
        <p class="text-lg font-medium text-slate-600 mb-2">Glissez votre fichier ici</p>
        <p class="text-sm text-slate-500 mb-4">ou cliquez pour parcourir</p>
        <button type="button" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
            <i class="fas fa-folder-open mr-2"></i> Parcourir
        </button>
    `;
}

// Gérer la soumission du formulaire
function handleFormSubmit(e) {
    e.preventDefault();

    if (!selectedFile) {
        alert('Veuillez sélectionner un fichier à importer.');
        return;
    }

    showProgressModal();
    simulateImport();
}

// Afficher le modal de progression
function showProgressModal() {
    document.getElementById('progressModal').classList.remove('hidden');
}

// Cacher le modal de progression
function hideProgressModal() {
    document.getElementById('progressModal').classList.add('hidden');
}

// Simuler l'import avec progression
function simulateImport() {
    let progress = 0;
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const progressStatus = document.getElementById('progressStatus');

    const steps = [
        { progress: 10, status: 'Lecture du fichier...' },
        { progress: 25, status: 'Validation des données...' },
        { progress: 50, status: 'Traitement des contacts...' },
        { progress: 75, status: 'Vérification des doublons...' },
        { progress: 90, status: 'Sauvegarde en base...' },
        { progress: 100, status: 'Import terminé !' }
    ];

    let currentStep = 0;

    const interval = setInterval(() => {
        if (currentStep < steps.length) {
            const step = steps[currentStep];
            progress = step.progress;

            progressBar.style.width = progress + '%';
            progressPercent.textContent = progress + '%';
            progressStatus.textContent = step.status;

            currentStep++;
        } else {
            clearInterval(interval);
            setTimeout(() => {
                hideProgressModal();
                // Simuler le succès
                alert('Import réussi ! 150 contacts ont été importés avec succès.');
                window.location.href = '{{ route("private.contacts.index") }}';
            }, 1000);
        }
    }, 800);
}

// Formater la taille de fichier
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Créer un FileList à partir d'un fichier
function createFileList(file) {
    const dt = new DataTransfer();
    dt.items.add(file);
    return dt.files;
}

// Afficher l'aide
function showHelp() {
    window.open('/docs/import-contacts', '_blank');
}
</script>
@endpush

@endsection
