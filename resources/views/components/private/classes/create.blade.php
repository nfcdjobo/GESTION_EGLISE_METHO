@extends('layouts.private.main')
@section('title', 'Créer une Classe')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer une Nouvelle Classe</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.classes.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>
                        Classes
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Créer</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('private.classes.store') }}" method="POST" enctype="multipart/form-data" id="classeForm" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations générales -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations Générales
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nom de la classe <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required maxlength="255" placeholder="Ex: École du Dimanche - Enfants"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('nom')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Nom d'affichage de la classe (255 caractères max)</p>
                            </div>

                            <div>
                                <label for="tranche_age" class="block text-sm font-medium text-slate-700 mb-2">
                                    Tranche d'âge
                                </label>
                                <select id="tranche_age" name="tranche_age" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('tranche_age') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner une tranche</option>
                                    @foreach($tranches_age as $tranche)
                                        <option value="{{ $tranche }}" {{ old('tranche_age') == $tranche ? 'selected' : '' }}>{{ $tranche }}</option>
                                    @endforeach
                                </select>
                                @error('tranche_age')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3" placeholder="Description de la classe, objectifs et activités"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="age_minimum" class="block text-sm font-medium text-slate-700 mb-2">
                                    Âge minimum
                                </label>
                                <input type="number" id="age_minimum" name="age_minimum" value="{{ old('age_minimum') }}" min="0" max="120"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('age_minimum') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('age_minimum')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Âge minimum requis (optionnel)</p>
                            </div>

                            <div>
                                <label for="age_maximum" class="block text-sm font-medium text-slate-700 mb-2">
                                    Âge maximum
                                </label>
                                <input type="number" id="age_maximum" name="age_maximum" value="{{ old('age_maximum') }}" min="0" max="120"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('age_maximum') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('age_maximum')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Âge maximum autorisé (optionnel)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responsables -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 mt-8">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-green-600 mr-2"></i>
                            Responsables
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="responsable_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Responsable de classe
                                </label>
                                <select id="responsable_id" name="responsable_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('responsable_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Aucun responsable</option>
                                    @foreach($utilisateurs as $utilisateur)
                                        <option value="{{ $utilisateur->id }}" {{ old('responsable_id') == $utilisateur->id ? 'selected' : '' }}>
                                            {{ $utilisateur->nom_complet }} ({{ $utilisateur->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsable_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="enseignant_principal_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Enseignant principal
                                </label>
                                <select id="enseignant_principal_id" name="enseignant_principal_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('enseignant_principal_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Aucun enseignant</option>
                                    @foreach($utilisateurs as $utilisateur)
                                        <option value="{{ $utilisateur->id }}" {{ old('enseignant_principal_id') == $utilisateur->id ? 'selected' : '' }}>
                                            {{ $utilisateur->nom_complet }} ({{ $utilisateur->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('enseignant_principal_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image et médias -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 mt-8">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-image text-purple-600 mr-2"></i>
                            Image et Médias
                        </h2>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="image_classe" class="block text-sm font-medium text-slate-700 mb-2">
                                Image de la classe
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-blue-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600">
                                        <label for="image_classe" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Télécharger un fichier</span>
                                            <input id="image_classe" name="image_classe" type="file" accept="image/*" class="sr-only" onchange="previewImage(this)">
                                        </label>
                                        <p class="pl-1">ou glisser-déposer</p>
                                    </div>
                                    <p class="text-xs text-slate-500">PNG, JPG, GIF jusqu'à 2MB</p>
                                </div>
                            </div>
                            <div id="imagePreview" class="mt-4 hidden">
                                <img id="previewImg" src="" alt="Aperçu" class="max-w-full h-32 object-cover rounded-lg mx-auto">
                            </div>
                            @error('image_classe')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aperçu et aide -->
            <div class="space-y-6">
                <!-- Aperçu -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-eye text-purple-600 mr-2"></i>
                            Aperçu
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Nom:</span>
                            <span id="preview-nom" class="text-sm text-slate-900 font-semibold">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Tranche d'âge:</span>
                            <span id="preview-tranche" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Âges:</span>
                            <span id="preview-ages" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Responsable:</span>
                            <span id="preview-responsable" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Enseignant:</span>
                            <span id="preview-enseignant" class="text-sm text-slate-600">-</span>
                        </div>
                    </div>
                </div>

                <!-- Guide des tranches d'âge -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-child text-green-600 mr-2"></i>
                            Guide des Tranches d'Âge
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-pink-100 text-pink-800">0-3 ans</span>
                            <span class="text-sm text-slate-700">Petite enfance</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">4-6 ans</span>
                            <span class="text-sm text-slate-700">Maternelle</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">7-9 ans</span>
                            <span class="text-sm text-slate-700">Primaire</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">10-12 ans</span>
                            <span class="text-sm text-slate-700">Pré-adolescent</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">13-18 ans</span>
                            <span class="text-sm text-slate-700">Adolescent</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Adultes</span>
                            <span class="text-sm text-slate-700">18+ ans</span>
                        </div>
                    </div>
                </div>

                <!-- Conseils -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-lightbulb text-amber-600 mr-2"></i>
                            Conseils
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm text-slate-600">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                            <span>Choisissez un nom descriptif et facile à retenir</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                            <span>Définissez clairement la tranche d'âge ciblée</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                            <span>Assignez un responsable dès la création</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                            <span>Ajoutez une image attrayante pour la classe</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Programme de la classe -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-book text-amber-600 mr-2"></i>
                    Programme de la Classe
                </h2>
                <p class="text-slate-500 mt-1">Définissez le programme et les objectifs de la classe (optionnel)</p>
            </div>
            <div class="p-6">
                <div id="programme-container">
                    <div class="programme-item border border-slate-200 rounded-xl p-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Titre de la leçon</label>
                                <input type="text" name="programme[0][titre]" placeholder="Ex: L'amour de Dieu" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Durée (minutes)</label>
                                <input type="number" name="programme[0][duree]" placeholder="45" min="1" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="flex items-end">
                                <button type="button" onclick="removeProgrammeItem(this)" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                    <i class="fas fa-trash mr-2"></i> Supprimer
                                </button>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea name="programme[0][description]" rows="2" placeholder="Description détaillée de la leçon..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="addProgrammeItem()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Ajouter une leçon
                </button>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer la Classe
                    </button>
                    <a href="{{ route('private.classes.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let programmeItemCount = 1;

// Mise à jour de l'aperçu
function updatePreview() {
    const nom = document.getElementById('nom').value || '-';
    const tranche = document.getElementById('tranche_age').value || '-';
    const ageMin = document.getElementById('age_minimum').value;
    const ageMax = document.getElementById('age_maximum').value;
    const responsableSelect = document.getElementById('responsable_id');
    const enseignantSelect = document.getElementById('enseignant_principal_id');

    document.getElementById('preview-nom').textContent = nom;

    if (tranche !== '-') {
        document.getElementById('preview-tranche').textContent = tranche;
        document.getElementById('preview-tranche').className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800';
    } else {
        document.getElementById('preview-tranche').textContent = '-';
        document.getElementById('preview-tranche').className = 'text-sm text-slate-600';
    }

    // Gestion des âges
    let agesText = '-';
    if (ageMin && ageMax) {
        agesText = `${ageMin}-${ageMax} ans`;
    } else if (ageMin) {
        agesText = `${ageMin}+ ans`;
    } else if (ageMax) {
        agesText = `Jusqu'à ${ageMax} ans`;
    }
    document.getElementById('preview-ages').textContent = agesText;

    // Responsable
    const responsableText = responsableSelect.selectedIndex > 0 ?
        responsableSelect.options[responsableSelect.selectedIndex].text.split(' (')[0] : '-';
    document.getElementById('preview-responsable').textContent = responsableText;

    // Enseignant
    const enseignantText = enseignantSelect.selectedIndex > 0 ?
        enseignantSelect.options[enseignantSelect.selectedIndex].text.split(' (')[0] : '-';
    document.getElementById('preview-enseignant').textContent = enseignantText;
}

// Événements pour la mise à jour de l'aperçu
document.getElementById('nom').addEventListener('input', updatePreview);
document.getElementById('tranche_age').addEventListener('change', updatePreview);
document.getElementById('age_minimum').addEventListener('input', updatePreview);
document.getElementById('age_maximum').addEventListener('input', updatePreview);
document.getElementById('responsable_id').addEventListener('change', updatePreview);
document.getElementById('enseignant_principal_id').addEventListener('change', updatePreview);

// Aperçu de l'image
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('hidden');
    }
}

// Gestion du programme
function addProgrammeItem() {
    const container = document.getElementById('programme-container');
    const itemHtml = `
        <div class="programme-item border border-slate-200 rounded-xl p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Titre de la leçon</label>
                    <input type="text" name="programme[${programmeItemCount}][titre]" placeholder="Ex: L'amour de Dieu" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Durée (minutes)</label>
                    <input type="number" name="programme[${programmeItemCount}][duree]" placeholder="45" min="1" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="removeProgrammeItem(this)" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                <textarea name="programme[${programmeItemCount}][description]" rows="2" placeholder="Description détaillée de la leçon..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', itemHtml);
    programmeItemCount++;
}

function removeProgrammeItem(button) {
    const item = button.closest('.programme-item');
    const container = document.getElementById('programme-container');

    if (container.children.length > 1) {
        item.remove();
    } else {
        alert('Au moins une leçon doit être définie dans le programme');
    }
}

// Validation du formulaire
document.getElementById('classeForm').addEventListener('submit', function(e) {
    const nom = document.getElementById('nom').value.trim();
    const ageMin = document.getElementById('age_minimum').value;
    const ageMax = document.getElementById('age_maximum').value;

    if (!nom) {
        e.preventDefault();
        alert('Veuillez saisir le nom de la classe.');
        return false;
    }

    if (ageMin && ageMax && parseInt(ageMin) > parseInt(ageMax)) {
        e.preventDefault();
        alert('L\'âge minimum ne peut pas être supérieur à l\'âge maximum.');
        return false;
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
@endpush
@endsection
