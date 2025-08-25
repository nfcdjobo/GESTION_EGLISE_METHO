@extends('layouts.private.main')
@section('title', 'Importer des utilisateurs')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Importer des Utilisateurs</h1>
            <p class="text-slate-500 mt-1">Importation en lot depuis un fichier CSV - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
        </div>

        <!-- En-tête avec gradient -->
        <div class="bg-white/80 rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 px-6 sm:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">Importer des utilisateurs</h2>
                        <p class="text-green-100 mt-2 text-sm sm:text-base">Importez plusieurs utilisateurs depuis un fichier CSV</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('private.users.index') }}"
                           class="inline-flex items-center justify-center px-4 py-2.5 bg-white/10border border-white/20 rounded-xl font-medium text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions d'importation -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    Instructions d'importation
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="bg-blue-50 rounded-xl p-4 border-2 border-blue-200">
                            <h4 class="font-bold text-blue-900 mb-3 flex items-center">
                                <i class="fas fa-file-csv text-blue-600 mr-2"></i>
                                Format de fichier requis :
                            </h4>
                            <ul class="list-disc list-inside space-y-2 text-sm text-blue-800">
                                <li>Fichier CSV (.csv) ou TXT (.txt) séparé par des virgules</li>
                                <li>Encodage UTF-8 recommandé</li>
                                <li>Taille maximale : 10 MB</li>
                                <li>Première ligne = en-têtes des colonnes</li>
                            </ul>
                        </div>

                        <div class="bg-green-50 rounded-xl p-4 border-2 border-green-200">
                            <h4 class="font-bold text-green-900 mb-3 flex items-center">
                                <i class="fas fa-columns text-green-600 mr-2"></i>
                                Colonnes requises :
                            </h4>
                            <div class="bg-white rounded-lg border p-3 font-mono text-xs overflow-x-auto">
                                <code class="text-green-700">
                                    prenom,nom,email,telephone,sexe,adresse,ville,statut_membre,statut_bapteme
                                </code>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-purple-50 rounded-xl p-4 border-2 border-purple-200">
                            <h4 class="font-bold text-purple-900 mb-3 flex items-center">
                                <i class="fas fa-plus-circle text-purple-600 mr-2"></i>
                                Colonnes optionnelles :
                            </h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-purple-800">
                                <li><strong>date_naissance</strong> : Format YYYY-MM-DD</li>
                                <li><strong>telephone_2</strong> : Téléphone secondaire</li>
                                <li><strong>profession</strong> : Profession de l'utilisateur</li>
                                <li><strong>employeur</strong> : Nom de l'employeur</li>
                            </ul>
                        </div>

                        <div class="bg-orange-50 rounded-xl p-4 border-2 border-orange-200">
                            <h4 class="font-bold text-orange-900 mb-3 flex items-center">
                                <i class="fas fa-check-circle text-orange-600 mr-2"></i>
                                Valeurs autorisées :
                            </h4>
                            <ul class="list-disc list-inside space-y-1 text-sm text-orange-800">
                                <li><strong>sexe</strong> : masculin, feminin</li>
                                <li><strong>statut_membre</strong> : actif, inactif, visiteur, nouveau_converti</li>
                                <li><strong>statut_bapteme</strong> : non_baptise, baptise, confirme</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exemple de fichier -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                        <i class="fas fa-file-csv text-white"></i>
                    </div>
                    Exemple de fichier CSV
                </h3>
            </div>
            <div class="p-6">
                <div class="bg-slate-50 rounded-xl border-2 border-slate-200 p-4 overflow-x-auto mb-4">
                    <pre class="text-xs text-slate-700 font-mono"><code>prenom,nom,email,telephone,sexe,adresse,ville,statut_membre,statut_bapteme
Jean,Dupont,jean.dupont@email.com,0123456789,masculin,123 Rue de la Paix,Abidjan,actif,baptise
Marie,Martin,marie.martin@email.com,0987654321,feminin,456 Avenue des Fleurs,Bouaké,visiteur,non_baptise
Pierre,Kouassi,pierre.kouassi@email.com,0147258369,masculin,789 Boulevard du Progrès,Yamoussoukro,nouveau_converti,baptise</code></pre>
                </div>

                <div class="flex justify-center">
                    <button onclick="downloadSampleFile()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i>Télécharger un fichier d'exemple
                    </button>
                </div>
            </div>
        </div>

        <!-- Formulaire d'importation -->
        <form action="{{ route('private.users.process-import') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Section : Sélectionner le fichier -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-upload text-white"></i>
                        </div>
                        Sélectionner le fichier
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label for="file" class="block text-sm font-medium text-slate-700 mb-3">Fichier CSV *</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="file" class="flex flex-col items-center justify-center w-full h-40 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-slate-100 hover:border-indigo-400 transition-all duration-300">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                                            <i class="fas fa-cloud-upload-alt text-2xl text-white"></i>
                                        </div>
                                        <p class="mb-2 text-sm text-slate-700">
                                            <span class="font-semibold">Cliquez pour télécharger</span> ou glissez-déposez
                                        </p>
                                        <p class="text-xs text-slate-500">CSV ou TXT (MAX. 10MB)</p>
                                    </div>
                                    <input id="file" name="file" type="file" accept=".csv,.txt" required class="hidden">
                                </label>
                            </div>
                            @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="file-info" class="hidden bg-blue-50 border-2 border-blue-200 rounded-xl p-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                                    <i class="fas fa-file-csv text-white"></i>
                                </div>
                                <div>
                                    <span id="file-name" class="text-sm font-medium text-blue-900 block"></span>
                                    <span id="file-size" class="text-sm text-blue-600"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Options d'importation -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-cog text-white"></i>
                        </div>
                        Options d'importation
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-slate-50 rounded-xl border-2 border-slate-200 hover:border-indigo-300 transition-all duration-200">
                            <div class="flex items-start">
                                <input type="checkbox" name="update_existing" id="update_existing" value="1"
                                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 mt-1">
                                <label for="update_existing" class="ml-3 cursor-pointer">
                                    <span class="text-sm font-medium text-slate-700">Mettre à jour les utilisateurs existants</span>
                                    <p class="text-sm text-slate-500 mt-1">Si un email existe déjà, mettre à jour les informations au lieu de l'ignorer</p>
                                </label>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-xl border-2 border-slate-200 hover:border-indigo-300 transition-all duration-200">
                            <div class="flex items-start">
                                <input type="checkbox" name="send_welcome_email" id="send_welcome_email" value="1"
                                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 mt-1">
                                <label for="send_welcome_email" class="ml-3 cursor-pointer">
                                    <span class="text-sm font-medium text-slate-700">Envoyer un email de bienvenue</span>
                                    <p class="text-sm text-slate-500 mt-1">Envoyer un email de bienvenue aux nouveaux utilisateurs créés</p>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-4 pt-6">
                <a href="{{ route('private.users.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg" id="import-btn" disabled>
                    <i class="fas fa-upload mr-2"></i>Commencer l'importation
                </button>
            </div>
        </form>

        <!-- Barre de progression (cachée par défaut) -->
        <div id="progress-container" class="hidden bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                    <i class="fas fa-spinner fa-spin text-white"></i>
                </div>
                Importation en cours...
            </h3>
            <div class="w-full bg-slate-200 rounded-full h-3 mb-3">
                <div id="progress-bar" class="bg-gradient-to-r from-indigo-600 to-purple-600 h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p id="progress-text" class="text-sm text-slate-600">Préparation...</p>
        </div>

        <!-- Affichage des résultats précédents -->
        @if(session('success') || session('import_errors'))
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                    Résultats de la dernière importation
                </h3>
            </div>
            <div class="p-6">
                @if(session('success'))
                <div class="bg-green-50 border-2 border-green-200 rounded-xl p-4 mb-4">
                    <div class="flex">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-check-circle text-white"></i>
                        </div>
                        <div>
                            <h4 class="text-green-800 font-medium">Importation réussie</h4>
                            <p class="text-green-700 text-sm mt-1">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('import_errors') && count(session('import_errors')) > 0)
                <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4">
                    <div class="flex">
                        <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-red-800 font-medium">Erreurs rencontrées ({{ count(session('import_errors')) }})</h4>
                            <div class="mt-3 max-h-48 overflow-y-auto">
                                <ul class="text-sm text-red-700 space-y-2">
                                    @foreach(session('import_errors') as $error)
                                    <li class="flex items-start p-2 bg-white/50 rounded-lg">
                                        <span class="w-2 h-2 bg-red-400 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                        {{ $error }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const importBtn = document.getElementById('import-btn');
    const form = document.querySelector('form');
    const progressContainer = document.getElementById('progress-container');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            fileName.textContent = file.name;
            fileSize.textContent = `(${formatFileSize(file.size)})`;
            fileInfo.classList.remove('hidden');
            importBtn.disabled = false;

            // Animation d'apparition
            fileInfo.style.opacity = '0';
            fileInfo.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                fileInfo.style.transition = 'all 0.3s ease';
                fileInfo.style.opacity = '1';
                fileInfo.style.transform = 'translateY(0)';
            }, 100);
        } else {
            fileInfo.classList.add('hidden');
            importBtn.disabled = true;
        }
    });

    form.addEventListener('submit', function(e) {
        // Afficher la barre de progression
        progressContainer.classList.remove('hidden');
        importBtn.disabled = true;
        importBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Importation en cours...';

        // Simuler une progression (en réalité, cela dépendra de votre backend)
        simulateProgress();
    });

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function simulateProgress() {
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        let progress = 0;

        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress > 90) progress = 90; // Arrêter à 90% jusqu'à la vraie fin

            progressBar.style.width = progress + '%';

            if (progress < 30) {
                progressText.textContent = 'Lecture du fichier...';
            } else if (progress < 60) {
                progressText.textContent = 'Validation des données...';
            } else if (progress < 90) {
                progressText.textContent = 'Création des utilisateurs...';
            }

            if (progress >= 90) {
                clearInterval(interval);
                progressText.textContent = 'Finalisation...';
            }
        }, 200);
    }

    // Animation des cartes au chargement
    const cards = document.querySelectorAll('.bg-white\\/80');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

function downloadSampleFile() {
    const csvContent = `prenom,nom,email,telephone,sexe,adresse,ville,statut_membre,statut_bapteme
Jean,Dupont,jean.dupont@email.com,0123456789,masculin,123 Rue de la Paix,Abidjan,actif,baptise
Marie,Martin,marie.martin@email.com,0987654321,feminin,456 Avenue des Fleurs,Bouaké,visiteur,non_baptise
Pierre,Kouassi,pierre.kouassi@email.com,0147258369,masculin,789 Boulevard du Progrès,Yamoussoukro,nouveau_converti,baptise`;

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');

    if (link.download !== undefined) {
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', 'exemple_import_utilisateurs.csv');
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.querySelector('label[for="file"]');
    const fileInput = document.getElementById('file');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    dropZone.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        fileInput.files = files;
        fileInput.dispatchEvent(new Event('change', { bubbles: true }));
    }
});
</script>
@endpush
@endsection
