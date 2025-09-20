@extends('layouts.private.main')
@section('title', 'Modifier la classe - ' . $classe->nom)

@section('content')
    <div class="space-y-8">
        <!-- En-tête de page -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('private.classes.show', $classe) }}"
                   class="inline-flex items-center text-slate-600 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la classe
                </a>
            </div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Modifier la classe
            </h1>
            <p class="text-slate-500 mt-1">
                Modification de "{{ $classe->nom }}" - {{ $classe->nombre_inscrits }} membre(s) inscrit(s)
            </p>
        </div>

        <!-- Formulaire de modification -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-edit text-yellow-600 mr-2"></i>
                    Modifier les informations
                </h2>
            </div>

            <form action="{{ route('private.classes.update', $classe) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Colonne gauche - Informations de base -->
                    <div class="space-y-6">
                        <!-- Nom de la classe -->
                        <div>
                            <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">
                                Nom de la classe <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom', $classe->nom) }}" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom') border-red-500 @enderror"
                                placeholder="Ex: Classe préparatoire A">
                            @error('nom')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror"
                                placeholder="Description détaillée de la classe...">{{ old('description', $classe->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tranche d'âge -->
                        <div>
                            <label for="tranche_age" class="block text-sm font-medium text-slate-700 mb-2">
                                Tranche d'âge
                            </label>
                            <select id="tranche_age" name="tranche_age"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('tranche_age') border-red-500 @enderror">
                                <option value="">Sélectionner une tranche d'âge</option>
                                @foreach($tranches_age as $tranche)
                                    <option value="{{ $tranche }}" {{ old('tranche_age', $classe->tranche_age) == $tranche ? 'selected' : '' }}>
                                        {{ $tranche }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tranche_age')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Âges spécifiques -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="age_minimum" class="block text-sm font-medium text-slate-700 mb-2">
                                    Âge minimum
                                </label>
                                <input type="number" id="age_minimum" name="age_minimum" value="{{ old('age_minimum', $classe->age_minimum) }}"
                                    min="0" max="120"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('age_minimum') border-red-500 @enderror"
                                    placeholder="Ex: 6">
                                @error('age_minimum')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="age_maximum" class="block text-sm font-medium text-slate-700 mb-2">
                                    Âge maximum
                                </label>
                                <input type="number" id="age_maximum" name="age_maximum" value="{{ old('age_maximum', $classe->age_maximum) }}"
                                    min="0" max="120"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('age_maximum') border-red-500 @enderror"
                                    placeholder="Ex: 12">
                                @error('age_maximum')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Image de la classe -->
                        <div>
                            <label for="image_classe" class="block text-sm font-medium text-slate-700 mb-2">
                                Image de la classe
                            </label>
                            <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-slate-400 transition-colors">
                                <input type="file" id="image_classe" name="image_classe" accept="image/*"
                                    class="hidden" onchange="previewImage(this)">

                                @if($classe->image_classe)
                                    <div id="current-image" class="mb-4">
                                        <img src="{{ asset('storage/' . $classe->image_classe) }}" alt="Image actuelle"
                                            class="mx-auto max-h-32 rounded-lg mb-3">
                                        <p class="text-sm text-slate-600 mb-2">Image actuelle</p>
                                        <button type="button" onclick="showUploadNew()"
                                            class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                                            Changer l'image
                                        </button>
                                    </div>
                                @endif

                                <div id="image-preview" class="hidden">
                                    <img id="preview-img" src="" alt="Aperçu" class="mx-auto max-h-32 rounded-lg mb-3">
                                    <button type="button" onclick="removeImage()"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                    </button>
                                </div>

                                <div id="upload-placeholder" class="{{ $classe->image_classe ? 'hidden' : '' }}">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-3"></i>
                                    <p class="text-slate-600 mb-2">Cliquez pour ajouter une image</p>
                                    <button type="button" onclick="document.getElementById('image_classe').click()"
                                        class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                                        Choisir un fichier
                                    </button>
                                </div>
                            </div>
                            @error('image_classe')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Colonne droite - Responsables et programme -->
                    <div class="space-y-6">
                        <!-- Responsables -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-4">
                                Responsables de la classe
                            </label>
                            <div id="responsables-container" class="space-y-4">
                                @if($classe->responsables && count($classe->responsables) > 0)
                                    @foreach($classe->responsables as $index => $responsable)
                                        <div class="responsable-item bg-slate-50 p-4 rounded-xl border border-slate-200">
                                            <div class="grid grid-cols-12 gap-3 items-end">
                                                <div class="col-span-5">
                                                    <label class="block text-xs font-medium text-slate-600 mb-1">Utilisateur</label>
                                                    <select name="responsables[{{ $index }}][id]" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        <option value="">Sélectionner un utilisateur</option>
                                                        @foreach($utilisateurs as $utilisateur)
                                                            <option value="{{ $utilisateur->id }}"
                                                                {{ old("responsables.{$index}.id", $responsable['id']) == $utilisateur->id ? 'selected' : '' }}>
                                                                {{ $utilisateur->prenom }} {{ $utilisateur->nom }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-span-4">
                                                    <label class="block text-xs font-medium text-slate-600 mb-1">Responsabilité</label>
                                                    <select name="responsables[{{ $index }}][responsabilite]" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                        <option value="">Type de responsabilité</option>
                                                        @foreach($types_responsabilite as $type)
                                                            <option value="{{ $type }}"
                                                                {{ old("responsables.{$index}.responsabilite", $responsable['responsabilite']) == $type ? 'selected' : '' }}>
                                                                {{ ucfirst($type) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-span-2">
                                                    <label class="block text-xs font-medium text-slate-600 mb-1">Supérieur</label>
                                                    <div class="flex items-center justify-center">
                                                        <input type="checkbox" name="responsables[{{ $index }}][superieur]" value="1"
                                                            {{ old("responsables.{$index}.superieur", $responsable['superieur']) ? 'checked' : '' }}
                                                            class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500">
                                                    </div>
                                                </div>
                                                <div class="col-span-1">
                                                    <button type="button" onclick="removeResponsable(this)"
                                                        class="w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Template par défaut si aucun responsable -->
                                    <div class="responsable-item bg-slate-50 p-4 rounded-xl border border-slate-200">
                                        <div class="grid grid-cols-12 gap-3 items-end">
                                            <div class="col-span-5">
                                                <label class="block text-xs font-medium text-slate-600 mb-1">Utilisateur</label>
                                                <select name="responsables[0][id]" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">Sélectionner un utilisateur</option>
                                                    @foreach($utilisateurs as $utilisateur)
                                                        <option value="{{ $utilisateur->id }}">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-span-4">
                                                <label class="block text-xs font-medium text-slate-600 mb-1">Responsabilité</label>
                                                <select name="responsables[0][responsabilite]" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">Type de responsabilité</option>
                                                    @foreach($types_responsabilite as $type)
                                                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-span-2">
                                                <label class="block text-xs font-medium text-slate-600 mb-1">Supérieur</label>
                                                <div class="flex items-center justify-center">
                                                    <input type="checkbox" name="responsables[0][superieur]" value="1"
                                                        class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500">
                                                </div>
                                            </div>
                                            <div class="col-span-1">
                                                <button type="button" onclick="removeResponsable(this)"
                                                    class="w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <button type="button" onclick="addResponsable()"
                                class="mt-3 inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Ajouter un responsable
                            </button>
                        </div>

                        <!-- Programme -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-4">
                                Programme de la classe
                            </label>
                            <div id="programme-container" class="space-y-3">
                                @if($classe->programme && count($classe->programme) > 0)
                                    @foreach($classe->programme as $index => $element)
                                        <div class="programme-item flex gap-3">
                                            <input type="text" name="programme[]" value="{{ old("programme.{$index}", $element) }}"
                                                class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Ex: Mathématiques de base">
                                            <button type="button" onclick="removeProgrammeItem(this)"
                                                class="w-10 h-10 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Template par défaut si aucun programme -->
                                    <div class="programme-item flex gap-3">
                                        <input type="text" name="programme[]"
                                            class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Ex: Mathématiques de base">
                                        <button type="button" onclick="removeProgrammeItem(this)"
                                            class="w-10 h-10 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <button type="button" onclick="addProgrammeItem()"
                                class="mt-3 inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-plus mr-2"></i> Ajouter un élément
                            </button>
                        </div>

                        <!-- Statistiques actuelles (lecture seule) -->
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                            <h3 class="text-sm font-medium text-blue-900 mb-3">Statistiques actuelles</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-blue-700">Membres inscrits:</span>
                                    <span class="font-medium text-blue-900">{{ $classe->nombre_inscrits }}</span>
                                </div>
                                <div>
                                    <span class="text-blue-700">Responsables:</span>
                                    <span class="font-medium text-blue-900">{{ count($classe->responsables ?? []) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-200">
                    <div class="flex items-center space-x-3">
                        @can('classes.duplicate')
                            <form action="{{ route('private.classes.duplicate', $classe) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-cyan-100 text-cyan-700 rounded-xl hover:bg-cyan-200 transition-colors">
                                    <i class="fas fa-copy mr-2"></i> Dupliquer
                                </button>
                            </form>
                        @endcan

                        @can('classes.archive')
                            <form action="{{ route('private.classes.archive', $classe) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir archiver cette classe ?')"
                                    class="inline-flex items-center px-4 py-2 bg-amber-100 text-amber-700 rounded-xl hover:bg-amber-200 transition-colors">
                                    <i class="fas fa-archive mr-2"></i> Archiver
                                </button>
                            </form>
                        @endcan
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="{{ route('private.classes.show', $classe) }}"
                            class="px-6 py-3 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                            Annuler
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts JavaScript -->
    <script>
        let responsableIndex = {{ count($classe->responsables ?? []) }};
        let programmeIndex = {{ count($classe->programme ?? []) }};

        // Gestion de l'aperçu d'image
        function previewImage(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                    document.getElementById('current-image')?.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        }

        function showUploadNew() {
            document.getElementById('current-image').classList.add('hidden');
            document.getElementById('upload-placeholder').classList.remove('hidden');
        }

        function removeImage() {
            document.getElementById('image_classe').value = '';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('upload-placeholder').classList.remove('hidden');
            document.getElementById('current-image')?.classList.remove('hidden');
        }

        // Gestion des responsables
        function addResponsable() {
            const container = document.getElementById('responsables-container');
            const template = `
                <div class="responsable-item bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <div class="grid grid-cols-12 gap-3 items-end">
                        <div class="col-span-5">
                            <label class="block text-xs font-medium text-slate-600 mb-1">Utilisateur</label>
                            <select name="responsables[${responsableIndex}][id]" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Sélectionner un utilisateur</option>
                                @foreach($utilisateurs as $utilisateur)
                                    <option value="{{ $utilisateur->id }}">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-4">
                            <label class="block text-xs font-medium text-slate-600 mb-1">Responsabilité</label>
                            <select name="responsables[${responsableIndex}][responsabilite]" class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Type de responsabilité</option>
                                @foreach($types_responsabilite as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-slate-600 mb-1">Supérieur</label>
                            <div class="flex items-center justify-center">
                                <input type="checkbox" name="responsables[${responsableIndex}][superieur]" value="1"
                                    class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500">
                            </div>
                        </div>
                        <div class="col-span-1">
                            <button type="button" onclick="removeResponsable(this)"
                                class="w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                                <i class="fas fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', template);
            responsableIndex++;
        }

        function removeResponsable(button) {
            const container = document.getElementById('responsables-container');
            if (container.children.length > 1) {
                button.closest('.responsable-item').remove();
            }
        }

        // Gestion du programme
        function addProgrammeItem() {
            const container = document.getElementById('programme-container');
            const template = `
                <div class="programme-item flex gap-3">
                    <input type="text" name="programme[]"
                        class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Ex: Mathématiques de base">
                    <button type="button" onclick="removeProgrammeItem(this)"
                        class="w-10 h-10 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors flex items-center justify-center">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', template);
        }

        function removeProgrammeItem(button) {
            const container = document.getElementById('programme-container');
            if (container.children.length > 1) {
                button.closest('.programme-item').remove();
            }
        }

        // Validation côté client
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const ageMin = document.getElementById('age_minimum');
            const ageMax = document.getElementById('age_maximum');

            // Validation des âges
            function validateAges() {
                const min = parseInt(ageMin.value);
                const max = parseInt(ageMax.value);

                if (min && max && min > max) {
                    ageMax.setCustomValidity('L\'âge maximum doit être supérieur à l\'âge minimum');
                } else {
                    ageMax.setCustomValidity('');
                }
            }

            ageMin.addEventListener('input', validateAges);
            ageMax.addEventListener('input', validateAges);

            // Validation des responsables supérieurs
            form.addEventListener('submit', function(e) {
                const superieurs = document.querySelectorAll('input[name*="[superieur]"]:checked');
                if (superieurs.length > 1) {
                    e.preventDefault();
                    alert('Une seule personne peut être désignée comme responsable supérieur');
                }
            });
        });
    </script>

@endsection
