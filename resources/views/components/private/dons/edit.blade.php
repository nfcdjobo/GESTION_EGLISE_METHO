@extends('layouts.private.main')
@section('title', 'Modifier Don')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
            Modifier Don #{{ $don->id }}
        </h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.dons.index') }}"
                        class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-dove mr-2"></i>
                        Donations
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <a href="{{ route('private.dons.show', $don) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600">
                            Don #{{ $don->id }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Modifier</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('private.dons.show', $don) }}"
                    class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-eye mr-2"></i> Voir le don
                </a>
                <a href="{{ route('private.dons.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-edit text-blue-600 mr-2"></i>
                Modifier les informations du don
            </h2>
            <p class="text-slate-500 mt-1">Modifiez les informations nécessaires</p>
        </div>

        <form action="{{ route('private.dons.update', $don) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            <!-- Informations du donateur -->
            <div class="bg-slate-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-6 flex items-center">
                    <i class="fas fa-user text-purple-600 mr-2"></i>
                    Informations du donateur
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="prenom_donateur" class="block text-sm font-medium text-slate-700 mb-2">
                            Prénom <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="prenom_donateur"
                               name="prenom_donateur"
                               value="{{ old('prenom_donateur', $don->prenom_donateur) }}"
                               required
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('prenom_donateur') border-red-300 @enderror">
                        @error('prenom_donateur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nom_donateur" class="block text-sm font-medium text-slate-700 mb-2">
                            Nom <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nom_donateur"
                               name="nom_donateur"
                               value="{{ old('nom_donateur', $don->nom_donateur) }}"
                               required
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom_donateur') border-red-300 @enderror">
                        @error('nom_donateur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telephone_1" class="block text-sm font-medium text-slate-700 mb-2">
                            Téléphone principal <span class="text-red-500">*</span>
                        </label>
                        <input type="tel"
                               id="telephone_1"
                               name="telephone_1"
                               value="{{ old('telephone_1', $don->telephone_1) }}"
                               required
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('telephone_1') border-red-300 @enderror">
                        @error('telephone_1')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telephone_2" class="block text-sm font-medium text-slate-700 mb-2">
                            Téléphone secondaire (optionnel)
                        </label>
                        <input type="tel"
                               id="telephone_2"
                               name="telephone_2"
                               value="{{ old('telephone_2', $don->telephone_2) }}"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('telephone_2') border-red-300 @enderror">
                        @error('telephone_2')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informations du don -->
            <div class="bg-slate-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-6 flex items-center">
                    <i class="fas fa-coins text-green-600 mr-2"></i>
                    Détails du don
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="parametre_fond_id" class="block text-sm font-medium text-slate-700 mb-2">
                            Paramètre de don <span class="text-red-500">*</span>
                        </label>
                        <select id="parametre_fond_id"
                                name="parametre_fond_id"
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('parametre_fond_id') border-red-300 @enderror">
                            <option value="">Sélectionner un paramètre</option>
                            @foreach($parametres as $parametre)
                                <option value="{{ $parametre->id }}" {{ old('parametre_fond_id', $don->parametre_fond_id) == $parametre->id ? 'selected' : '' }}>
                                    {{ $parametre->operateur }} - {{ $parametre->type_libelle }}
                                    @if($parametre->numero_compte)
                                        ({{ $parametre->numero_compte }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('parametre_fond_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="devise" class="block text-sm font-medium text-slate-700 mb-2">
                            Devise <span class="text-red-500">*</span>
                        </label>
                        <select id="devise"
                                name="devise"
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('devise') border-red-300 @enderror">
                            <option value="">Sélectionner une devise</option>
                            @foreach(\App\Models\Don::DEVISES as $code => $libelle)
                                <option value="{{ $code }}" {{ old('devise', $don->devise) == $code ? 'selected' : '' }}>
                                    {{ $libelle }}
                                </option>
                            @endforeach
                        </select>
                        @error('devise')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="montant" class="block text-sm font-medium text-slate-700 mb-2">
                            Montant <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number"
                                   id="montant"
                                   name="montant"
                                   value="{{ old('montant', $don->montant) }}"
                                   step="0.01"
                                   min="0.01"
                                   required
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('montant') border-red-300 @enderror">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-slate-500 sm:text-sm" id="devise-symbol">{{ $don->devise }}</span>
                            </div>
                        </div>
                        @error('montant')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Preuve de paiement -->
            <div class="bg-slate-50 rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-6 flex items-center">
                    <i class="fas fa-file-upload text-amber-600 mr-2"></i>
                    Preuve de paiement
                </h3>

                @if($don->aUnePreuve())
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-file-check text-green-600"></i>
                                <div>
                                    <p class="text-sm font-medium text-green-800">Preuve actuelle</p>
                                    <p class="text-sm text-green-600">{{ basename($don->preuve) }}</p>
                                </div>
                            </div>
                            <a href="{{ route('private.dons.telechargerPreuve', $don) }}"
                               class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-download mr-2"></i> Télécharger
                            </a>
                        </div>
                    </div>
                @endif

                <div>
                    <label for="preuve" class="block text-sm font-medium text-slate-700 mb-2">
                        @if($don->aUnePreuve())
                            Remplacer la preuve (optionnel)
                        @else
                            Fichier de preuve <span class="text-red-500">*</span>
                        @endif
                    </label>
                    <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="preuve" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Télécharger un fichier</span>
                                    <input id="preuve" name="preuve" type="file" class="sr-only" accept=".jpg,.jpeg,.png,.pdf" onchange="handleFileSelect(this)" {{ !$don->aUnePreuve() ? 'required' : '' }}>
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, PDF jusqu'à 5MB</p>
                        </div>
                    </div>
                    <div id="file-preview" class="hidden mt-4">
                        <div class="flex items-center space-x-3 p-3 bg-white rounded-lg border">
                            <i class="fas fa-file text-gray-400"></i>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900" id="file-name"></p>
                                <p class="text-sm text-gray-500" id="file-size"></p>
                            </div>
                            <button type="button" onclick="removeFile()" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    @error('preuve')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-200">
                <button type="submit" class="inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    Mettre à jour
                </button>

                <button type="reset" class="inline-flex justify-center items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                    <i class="fas fa-undo mr-2"></i>
                    Réinitialiser
                </button>

                <a href="{{ route('private.dons.show', $don) }}" class="inline-flex justify-center items-center px-6 py-3 bg-white border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Mise à jour du symbole de devise
document.getElementById('devise').addEventListener('change', function() {
    const deviseSymbol = document.getElementById('devise-symbol');
    const selectedOption = this.options[this.selectedIndex];

    if (this.value) {
        deviseSymbol.textContent = this.value;
    } else {
        deviseSymbol.textContent = '';
    }
});

// Gestion de l'upload de fichier
function handleFileSelect(input) {
    const file = input.files[0];
    if (file) {
        const preview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');

        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);

        preview.classList.remove('hidden');
    }
}

function removeFile() {
    const input = document.getElementById('preuve');
    const preview = document.getElementById('file-preview');

    input.value = '';
    preview.classList.add('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Validation en temps réel
document.getElementById('montant').addEventListener('input', function() {
    const value = parseFloat(this.value);
    if (value <= 0) {
        this.setCustomValidity('Le montant doit être supérieur à 0');
    } else if (value > 999999999999.99) {
        this.setCustomValidity('Le montant est trop élevé');
    } else {
        this.setCustomValidity('');
    }
});

// Format du numéro de téléphone
function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length > 0) {
        value = value.replace(/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/, '$1 $2 $3 $4 $5');
    }
    input.value = value;
}

document.getElementById('telephone_1').addEventListener('input', function() {
    formatPhoneNumber(this);
});

document.getElementById('telephone_2').addEventListener('input', function() {
    formatPhoneNumber(this);
});

// Confirmation avant soumission si des changements importants
document.querySelector('form').addEventListener('submit', function(e) {
    const originalAmount = {{ $don->montant }};
    const newAmount = parseFloat(document.getElementById('montant').value);

    if (Math.abs(originalAmount - newAmount) > (originalAmount * 0.1)) { // Plus de 10% de différence
        if (!confirm('Le montant a significativement changé. Êtes-vous sûr de vouloir continuer ?')) {
            e.preventDefault();
        }
    }
});
</script>

@endsection
