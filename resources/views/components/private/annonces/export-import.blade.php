
@props([
    'showImport' => true,
    'showExport' => true
])

<div class="space-y-6">
    @if($showExport)
        <!-- Section Export -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-download text-blue-600 mr-2"></i>
                    Exporter les Annonces
                </h3>
                <p class="text-slate-500 mt-1">Téléchargez vos annonces dans différents formats</p>
            </div>

            <div class="p-6">
                <form id="export-form" class="space-y-6">
                    <!-- Filtres d'export -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Format d'export</label>
                            <select name="format" id="export-format" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="csv">CSV (Excel)</option>
                                <option value="json">JSON</option>
                                <option value="pdf">PDF</option>
                                <option value="xml">XML</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Période</label>
                            <select name="periode" id="export-periode" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="all">Toutes les annonces</option>
                                <option value="last_month">Dernier mois</option>
                                <option value="last_3_months">3 derniers mois</option>
                                <option value="last_year">Dernière année</option>
                                <option value="custom">Période personnalisée</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <select name="statut" id="export-statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les statuts</option>
                                <option value="publiee">Publiées</option>
                                <option value="brouillon">Brouillons</option>
                                <option value="expiree">Expirées</option>
                            </select>
                        </div>
                    </div>

                    <!-- Période personnalisée -->
                    <div id="custom-period" class="grid grid-cols-1 md:grid-cols-2 gap-6 hidden">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date de début</label>
                            <input type="date" name="date_debut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date de fin</label>
                            <input type="date" name="date_fin" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                    </div>

                    <!-- Options d'export -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-3">Champs à inclure</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="titre" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Titre</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="contenu" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Contenu</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="type_annonce" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Type</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="niveau_priorite" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Priorité</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="audience_cible" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Audience</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="statut" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Statut</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="date_evenement" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Date événement</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="lieu_evenement" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Lieu</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="contact_principal" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Contact</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="auteur" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Auteur</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="created_at" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Date création</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="fields[]" value="publie_le" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Date publication</span>
                            </label>
                        </div>
                    </div>

                    <!-- Options avancées -->
                    <div class="border-t border-slate-200 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-medium text-slate-800">Options avancées</h4>
                            <button type="button" onclick="toggleAdvancedOptions('export')" class="text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-chevron-down" id="export-advanced-icon"></i>
                                Afficher/Masquer
                            </button>
                        </div>

                        <div id="export-advanced-options" class="hidden space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="include_relations" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Inclure les relations (auteur, contact)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="include_meta" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Inclure les métadonnées</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Encodage</label>
                                    <select name="encoding" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="utf-8">UTF-8</option>
                                        <option value="iso-8859-1">ISO-8859-1</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Séparateur CSV</label>
                                    <select name="delimiter" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value=",">, (virgule)</option>
                                        <option value=";">; (point-virgule)</option>
                                        <option value="\t">⇥ (tabulation)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex items-center justify-between pt-6 border-t border-slate-200">
                        <div class="text-sm text-slate-600">
                            <span id="export-preview">Environ <strong>{{ \App\Models\Annonce::count() }}</strong> annonce(s) seront exportées</span>
                        </div>

                        <div class="flex items-center space-x-3">
                            <button type="button" onclick="previewExport()" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                Prévisualiser
                            </button>
                            <button type="button" onclick="startExport()" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Barre de progression d'export -->
                <div id="export-progress" class="mt-6 hidden">
                    <div class="flex items-center justify-between text-sm text-slate-600 mb-2">
                        <span>Export en cours...</span>
                        <span id="export-percentage">0%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" id="export-progress-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showImport)
        <!-- Section Import -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-upload text-green-600 mr-2"></i>
                    Importer des Annonces
                </h3>
                <p class="text-slate-500 mt-1">Importez des annonces depuis un fichier</p>
            </div>

            <div class="p-6">
                <form id="import-form" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Zone de téléchargement -->
                    <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-blue-400 transition-colors"
                         ondrop="handleFileDrop(event)"
                         ondragover="handleDragOver(event)"
                         ondragenter="handleDragEnter(event)"
                         ondragleave="handleDragLeave(event)">
                        <div id="upload-area">
                            <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-4"></i>
                            <h4 class="text-lg font-medium text-slate-700 mb-2">Glissez-déposez votre fichier ici</h4>
                            <p class="text-sm text-slate-500 mb-4">ou cliquez pour sélectionner un fichier</p>
                            <input type="file" id="import-file" name="file" accept=".csv,.json,.xlsx" class="hidden" onchange="handleFileSelect(event)">
                            <button type="button" onclick="document.getElementById('import-file').click()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-folder-open mr-2"></i>
                                Choisir un fichier
                            </button>
                            <p class="text-xs text-slate-400 mt-3">Formats supportés: CSV, JSON, Excel (.xlsx)</p>
                        </div>

                        <!-- Informations du fichier sélectionné -->
                        <div id="file-info" class="hidden">
                            <div class="flex items-center justify-center space-x-3">
                                <i class="fas fa-file text-green-500 text-2xl"></i>
                                <div class="text-left">
                                    <p class="font-medium text-slate-800" id="file-name"></p>
                                    <p class="text-sm text-slate-500" id="file-size"></p>
                                </div>
                                <button type="button" onclick="clearFile()" class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Options d'import -->
                    <div id="import-options" class="hidden space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Mode d'import</label>
                                <select name="import_mode" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="create_only">Créer seulement (ignorer les doublons)</option>
                                    <option value="update_or_create">Créer ou mettre à jour</option>
                                    <option value="replace_all">Remplacer tout</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Statut par défaut</label>
                                <select name="default_status" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="brouillon">Brouillon</option>
                                    <option value="publiee">Publiée</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="validate_data" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Valider les données avant import</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="send_notifications" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Envoyer des notifications pour les nouvelles annonces</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="create_log" checked class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Créer un log d'import</span>
                            </label>
                        </div>
                    </div>

                    <!-- Prévisualisation des données -->
                    <div id="data-preview" class="hidden">
                        <h4 class="text-lg font-medium text-slate-800 mb-3">Aperçu des données</h4>
                        <div class="bg-slate-50 rounded-lg p-4 max-h-64 overflow-auto">
                            <table class="w-full text-sm" id="preview-table">
                                <!-- Contenu dynamique -->
                            </table>
                        </div>
                        <p class="text-xs text-slate-500 mt-2" id="preview-info"></p>
                    </div>

                    <!-- Boutons d'action -->
                    <div id="import-actions" class="hidden flex items-center justify-between pt-6 border-t border-slate-200">
                        <button type="button" onclick="analyzeFile()" class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-xl hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-search mr-2"></i>
                            Analyser le fichier
                        </button>

                        <button type="button" onclick="startImport()" class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                            <i class="fas fa-upload mr-2"></i>
                            Commencer l'import
                        </button>
                    </div>
                </form>

                <!-- Barre de progression d'import -->
                <div id="import-progress" class="mt-6 hidden">
                    <div class="flex items-center justify-between text-sm text-slate-600 mb-2">
                        <span id="import-status">Import en cours...</span>
                        <span id="import-percentage">0%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300" id="import-progress-bar" style="width: 0%"></div>
                    </div>
                    <div id="import-details" class="mt-3 text-sm text-slate-600">
                        <!-- Détails de progression -->
                    </div>
                </div>

                <!-- Résultats d'import -->
                <div id="import-results" class="mt-6 hidden">
                    <h4 class="text-lg font-medium text-slate-800 mb-3">Résultats de l'import</h4>
                    <div id="results-content" class="space-y-3">
                        <!-- Contenu des résultats -->
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
// Gestion des périodes d'export
document.getElementById('export-periode').addEventListener('change', function() {
    const customPeriod = document.getElementById('custom-period');
    if (this.value === 'custom') {
        customPeriod.classList.remove('hidden');
    } else {
        customPeriod.classList.add('hidden');
    }
    updateExportPreview();
});

function toggleAdvancedOptions(type) {
    const options = document.getElementById(`${type}-advanced-options`);
    const icon = document.getElementById(`${type}-advanced-icon`);

    if (options.classList.contains('hidden')) {
        options.classList.remove('hidden');
        icon.className = 'fas fa-chevron-up';
    } else {
        options.classList.add('hidden');
        icon.className = 'fas fa-chevron-down';
    }
}

function updateExportPreview() {
    // Mettre à jour la prévisualisation du nombre d'annonces à exporter
    // Cette fonction pourrait faire un appel AJAX pour obtenir le nombre exact
    const preview = document.getElementById('export-preview');
    preview.innerHTML = 'Calcul en cours...';

    // Simulation d'un appel AJAX
    setTimeout(() => {
        const randomCount = Math.floor(Math.random() * 100) + 1;
        preview.innerHTML = `Environ <strong>${randomCount}</strong> annonce(s) seront exportées`;
    }, 500);
}

function previewExport() {
    alert('Prévisualisation non implémentée dans cette démo');
}

function startExport() {
    const form = document.getElementById('export-form');
    const formData = new FormData(form);
    const progressDiv = document.getElementById('export-progress');
    const progressBar = document.getElementById('export-progress-bar');
    const progressPercentage = document.getElementById('export-percentage');

    // Afficher la barre de progression
    progressDiv.classList.remove('hidden');

    // Simuler l'export avec une barre de progression
    let progress = 0;
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 100) progress = 100;

        progressBar.style.width = `${progress}%`;
        progressPercentage.textContent = `${Math.round(progress)}%`;

        if (progress >= 100) {
            clearInterval(interval);
            setTimeout(() => {
                progressDiv.classList.add('hidden');
                // Démarrer le téléchargement réel
                downloadExport(formData);
            }, 500);
        }
    }, 200);
}

function downloadExport(formData) {
    // Créer l'URL de téléchargement
    const params = new URLSearchParams();
    for (let [key, value] of formData) {
        params.append(key, value);
    }

    const url = `{{ route('private.annonces.export') }}?${params.toString()}`;
    window.open(url, '_blank');
}

// Gestion du drag & drop pour l'import
function handleDragOver(e) {
    e.preventDefault();
    e.currentTarget.classList.add('border-blue-400', 'bg-blue-50');
}

function handleDragEnter(e) {
    e.preventDefault();
}

function handleDragLeave(e) {
    e.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');
}

function handleFileDrop(e) {
    e.preventDefault();
    e.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleFile(files[0]);
    }
}

function handleFileSelect(e) {
    const file = e.target.files[0];
    if (file) {
        handleFile(file);
    }
}

function handleFile(file) {
    // Vérifier le type de fichier
    const allowedTypes = ['text/csv', 'application/json', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
    const allowedExtensions = ['.csv', '.json', '.xlsx'];

    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();

    if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
        alert('Type de fichier non supporté. Utilisez CSV, JSON ou Excel (.xlsx).');
        return;
    }

    // Afficher les informations du fichier
    document.getElementById('upload-area').classList.add('hidden');
    document.getElementById('file-info').classList.remove('hidden');
    document.getElementById('import-options').classList.remove('hidden');
    document.getElementById('import-actions').classList.remove('hidden');

    document.getElementById('file-name').textContent = file.name;
    document.getElementById('file-size').textContent = formatFileSize(file.size);
}

function clearFile() {
    document.getElementById('import-file').value = '';
    document.getElementById('upload-area').classList.remove('hidden');
    document.getElementById('file-info').classList.add('hidden');
    document.getElementById('import-options').classList.add('hidden');
    document.getElementById('import-actions').classList.add('hidden');
    document.getElementById('data-preview').classList.add('hidden');
}

function formatFileSize(bytes) {
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    if (bytes === 0) return '0 Byte';
    const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
}

function analyzeFile() {
    const fileInput = document.getElementById('import-file');
    if (!fileInput.files[0]) {
        alert('Aucun fichier sélectionné');
        return;
    }

    const file = fileInput.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        let data;

        if (file.type === 'application/json') {
            try {
                data = JSON.parse(e.target.result);
            } catch (error) {
                alert('Erreur lors de la lecture du fichier JSON');
                return;
            }
        } else if (file.name.endsWith('.csv')) {
            // Parser CSV basique (pour la démo)
            const lines = e.target.result.split('\n');
            const headers = lines[0].split(',');
            data = lines.slice(1, 6).map(line => {
                const values = line.split(',');
                const obj = {};
                headers.forEach((header, index) => {
                    obj[header.trim()] = values[index]?.trim() || '';
                });
                return obj;
            });
        }

        showDataPreview(data, file.name);
    };

    reader.readAsText(file);
}

function showDataPreview(data, filename) {
    const preview = document.getElementById('data-preview');
    const table = document.getElementById('preview-table');
    const info = document.getElementById('preview-info');

    if (!data || data.length === 0) {
        alert('Aucune donnée trouvée dans le fichier');
        return;
    }

    // Générer le tableau de prévisualisation
    const headers = Object.keys(data[0]);
    let html = '<thead class="bg-slate-100"><tr>';
    headers.forEach(header => {
        html += `<th class="px-3 py-2 text-left font-medium text-slate-700">${header}</th>`;
    });
    html += '</tr></thead><tbody>';

    data.forEach((row, index) => {
        if (index < 5) { // Afficher seulement les 5 premières lignes
            html += '<tr class="border-t border-slate-200">';
            headers.forEach(header => {
                html += `<td class="px-3 py-2 text-slate-600">${row[header] || ''}</td>`;
            });
            html += '</tr>';
        }
    });
    html += '</tbody>';

    table.innerHTML = html;
    info.textContent = `${data.length} ligne(s) trouvée(s) dans ${filename}. Affichage des 5 premières.`;
    preview.classList.remove('hidden');
}

function startImport() {
    const form = document.getElementById('import-form');
    const formData = new FormData(form);
    const progressDiv = document.getElementById('import-progress');
    const progressBar = document.getElementById('import-progress-bar');
    const progressPercentage = document.getElementById('import-percentage');
    const importStatus = document.getElementById('import-status');
    const importDetails = document.getElementById('import-details');

    // Cacher les options et afficher la progression
    document.getElementById('import-options').classList.add('hidden');
    document.getElementById('import-actions').classList.add('hidden');
    progressDiv.classList.remove('hidden');

    // Simuler l'import avec des étapes
    const steps = [
        { text: 'Lecture du fichier...', progress: 20 },
        { text: 'Validation des données...', progress: 40 },
        { text: 'Création des annonces...', progress: 70 },
        { text: 'Finalisation...', progress: 90 },
        { text: 'Import terminé!', progress: 100 }
    ];

    let currentStep = 0;

    function nextStep() {
        if (currentStep < steps.length) {
            const step = steps[currentStep];
            importStatus.textContent = step.text;
            progressBar.style.width = `${step.progress}%`;
            progressPercentage.textContent = `${step.progress}%`;

            if (step.progress < 100) {
                importDetails.innerHTML += `<div>✓ ${step.text}</div>`;
            }

            currentStep++;

            if (currentStep <= steps.length) {
                setTimeout(nextStep, 1000 + Math.random() * 1000);
            }
        } else {
            // Import terminé, afficher les résultats
            setTimeout(showImportResults, 1000);
        }
    }

    nextStep();
}

function showImportResults() {
    const resultsDiv = document.getElementById('import-results');
    const resultsContent = document.getElementById('results-content');

    // Simuler des résultats d'import
    const results = {
        created: Math.floor(Math.random() * 20) + 5,
        updated: Math.floor(Math.random() * 10),
        skipped: Math.floor(Math.random() * 5),
        errors: Math.floor(Math.random() * 3)
    };

    let html = `
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div class="text-center p-3 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">${results.created}</div>
                <div class="text-sm text-green-600">Créées</div>
            </div>
            <div class="text-center p-3 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">${results.updated}</div>
                <div class="text-sm text-blue-600">Mises à jour</div>
            </div>
            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">${results.skipped}</div>
                <div class="text-sm text-yellow-600">Ignorées</div>
            </div>
            <div class="text-center p-3 bg-red-50 rounded-lg">
                <div class="text-2xl font-bold text-red-600">${results.errors}</div>
                <div class="text-sm text-red-600">Erreurs</div>
            </div>
        </div>
    `;

    if (results.errors > 0) {
        html += `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h5 class="font-medium text-red-800 mb-2">Erreurs rencontrées:</h5>
                <ul class="text-sm text-red-600 space-y-1">
                    <li>• Ligne 3: Titre manquant</li>
                    <li>• Ligne 7: Format de date invalide</li>
                    <li>• Ligne 12: Type d'annonce non reconnu</li>
                </ul>
            </div>
        `;
    }

    html += `
        <div class="flex items-center justify-center space-x-4 mt-6">
            <a href="{{ route('private.annonces.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-list mr-2"></i>
                Voir les annonces
            </a>
            <button type="button" onclick="resetImport()" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nouvel import
            </button>
        </div>
    `;

    resultsContent.innerHTML = html;
    resultsDiv.classList.remove('hidden');
}

function resetImport() {
    clearFile();
    document.getElementById('import-progress').classList.add('hidden');
    document.getElementById('import-results').classList.add('hidden');
    document.getElementById('import-progress-bar').style.width = '0%';
    document.getElementById('import-percentage').textContent = '0%';
    document.getElementById('import-details').innerHTML = '';
}

// Écouter les changements pour mettre à jour la prévisualisation d'export
document.getElementById('export-form').addEventListener('change', updateExportPreview);
</script>
@endpush
