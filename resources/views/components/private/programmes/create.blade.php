@extends('layouts.private.main')
@section('title', 'Créer un Programme')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer un Nouveau Programme</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.programmes.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Programmes
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
    @can('programmes.create')
    <form action="{{ route('private.programmes.store') }}" method="POST" id="programmeForm" class="space-y-8">
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
                                <label for="nom_programme" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nom du programme <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nom_programme" name="nom_programme" value="{{ old('nom_programme') }}" required maxlength="200" placeholder="Ex: Culte du dimanche"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom_programme') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('nom_programme')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="code_programme" class="block text-sm font-medium text-slate-700 mb-2">
                                    Code programme
                                </label>
                                <input type="text" id="code_programme" name="code_programme" value="{{ old('code_programme') }}" maxlength="50" placeholder="Généré automatiquement"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('code_programme') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('code_programme')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Laissez vide pour générer automatiquement</p>
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3" placeholder="Description du programme et de ses objectifs"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type_programme" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de programme <span class="text-red-500">*</span>
                                </label>
                                <select id="type_programme" name="type_programme" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_programme') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un type</option>
                                    @foreach(\App\Models\Programme::TYPES_PROGRAMME as $key => $label)
                                        <option value="{{ $key }}" {{ old('type_programme') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type_programme')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="audience_cible" class="block text-sm font-medium text-slate-700 mb-2">
                                    Audience ciblée <span class="text-red-500">*</span>
                                </label>
                                <select id="audience_cible" name="audience_cible" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('audience_cible') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @foreach(\App\Models\Programme::AUDIENCES as $key => $label)
                                        <option value="{{ $key }}" {{ old('audience_cible', 'tous') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('audience_cible')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Planification -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 mt-8">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-clock text-green-600 mr-2"></i>
                            Planification
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="frequence" class="block text-sm font-medium text-slate-700 mb-2">
                                Fréquence <span class="text-red-500">*</span>
                            </label>
                            <select id="frequence" name="frequence" required onchange="toggleFrequencyFields()"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('frequence') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @foreach(\App\Models\Programme::FREQUENCES as $key => $label)
                                    <option value="{{ $key }}" {{ old('frequence') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('frequence')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="joursContainer" class="hidden">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Jours de la semaine</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @php
                                    $jours = [
                                        1 => 'Lundi',
                                        2 => 'Mardi',
                                        3 => 'Mercredi',
                                        4 => 'Jeudi',
                                        5 => 'Vendredi',
                                        6 => 'Samedi',
                                        7 => 'Dimanche'
                                    ]
                                @endphp
                                @foreach($jours as $numero => $nom)
                                    <div class="flex items-center">
                                        <input type="checkbox" name="jours_semaine[]" value="{{ $numero }}" id="jour_{{ $numero }}"
                                            {{ in_array($numero, old('jours_semaine', [])) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="jour_{{ $numero }}" class="ml-2 text-sm text-slate-700">{{ $nom }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('jours_semaine')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="heure_debut" class="block text-sm font-medium text-slate-700 mb-2">Heure de début</label>
                                <input type="time" id="heure_debut" name="heure_debut" value="{{ old('heure_debut') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_debut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('heure_debut')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="heure_fin" class="block text-sm font-medium text-slate-700 mb-2">Heure de fin</label>
                                <input type="time" id="heure_fin" name="heure_fin" value="{{ old('heure_fin') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_fin') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('heure_fin')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="date_debut" class="block text-sm font-medium text-slate-700 mb-2">Date de début</label>
                                <input type="date" id="date_debut" name="date_debut" value="{{ old('date_debut') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_debut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('date_debut')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_fin" class="block text-sm font-medium text-slate-700 mb-2">Date de fin</label>
                                <input type="date" id="date_fin" name="date_fin" value="{{ old('date_fin') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_fin') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('date_fin')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Laissez vide pour un programme permanent</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Organisation -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 mt-8">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-purple-600 mr-2"></i>
                            Organisation
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="lieu_principal" class="block text-sm font-medium text-slate-700 mb-2">Lieu principal</label>
                                <input type="text" id="lieu_principal" name="lieu_principal" value="{{ old('lieu_principal') }}" maxlength="200" placeholder="Ex: Sanctuaire principal"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lieu_principal') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('lieu_principal')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="responsable_principal_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable principal</label>
                                <select id="responsable_principal_id" name="responsable_principal_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('responsable_principal_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un responsable</option>
                                    @foreach($responsables as $responsable)
                                        <option value="{{ $responsable->id }}" {{ old('responsable_principal_id') == $responsable->id ? 'selected' : '' }}>
                                            {{ $responsable->prenom }} {{ $responsable->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsable_principal_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Notes supplémentaires, instructions spéciales..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('notes') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes')
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
                            <span id="preview-name" class="text-sm text-slate-900 font-semibold">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Code:</span>
                            <code id="preview-code" class="px-2 py-1 text-xs bg-slate-100 text-slate-800 rounded">-</code>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Type:</span>
                            <span id="preview-type" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Fréquence:</span>
                            <span id="preview-frequence" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Audience:</span>
                            <span id="preview-audience" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">-</span>
                        </div>
                    </div>
                </div>

                <!-- Guide des types -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-green-600 mr-2"></i>
                            Types de Programmes
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div><strong>Culte régulier:</strong> Services dominicaux, prières</div>
                        <div><strong>Formation:</strong> Études bibliques, séminaires</div>
                        <div><strong>Évangélisation:</strong> Missions, témoignages</div>
                        <div><strong>Jeunesse:</strong> Activités pour les jeunes</div>
                        <div><strong>Enfants:</strong> École du dimanche, garderie</div>
                        <div><strong>Conférence:</strong> Événements spéciaux</div>
                    </div>
                </div>

                <!-- Guide des fréquences -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-clock text-amber-600 mr-2"></i>
                            Fréquences
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div><strong>Quotidien:</strong> Tous les jours</div>
                        <div><strong>Hebdomadaire:</strong> Chaque semaine</div>
                        <div><strong>Mensuel:</strong> Une fois par mois</div>
                        <div><strong>Annuel:</strong> Une fois par an</div>
                        <div><strong>Ponctuel:</strong> Événement unique</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statut -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-toggle-on text-amber-600 mr-2"></i>
                    Statut Initial
                </h2>
            </div>
            <div class="p-6">
                <select id="statut" name="statut" required
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('statut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    @foreach(\App\Models\Programme::STATUTS as $key => $label)
                        <option value="{{ $key }}" {{ old('statut', 'planifie') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('statut')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer le Programme
                    </button>
                    <a href="{{ route('private.programmes.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
    @endcan
</div>

<script>
// Mise à jour de l'aperçu
function updatePreview() {
    const nom = document.getElementById('nom_programme').value || '-';
    const code = document.getElementById('code_programme').value || 'Auto-généré';
    const type = document.getElementById('type_programme').value;
    const frequence = document.getElementById('frequence').value;
    const audience = document.getElementById('audience_cible').value;

    document.getElementById('preview-name').textContent = nom;
    document.getElementById('preview-code').textContent = code;

    // Type
    const typeLabel = type ? @json(\App\Models\Programme::TYPES_PROGRAMME)[type] : '-';
    document.getElementById('preview-type').textContent = typeLabel;

    // Fréquence
    const frequenceLabel = frequence ? @json(\App\Models\Programme::FREQUENCES)[frequence] : '-';
    document.getElementById('preview-frequence').textContent = frequenceLabel;

    // Audience
    const audienceLabel = audience ? @json(\App\Models\Programme::AUDIENCES)[audience] : '-';
    document.getElementById('preview-audience').textContent = audienceLabel;
}

// Toggle champs selon la fréquence
function toggleFrequencyFields() {
    const frequence = document.getElementById('frequence').value;
    const joursContainer = document.getElementById('joursContainer');

    if (frequence === 'quotidien' || frequence === 'hebdomadaire') {
        joursContainer.classList.remove('hidden');
    } else {
        joursContainer.classList.add('hidden');
        // Décocher tous les jours
        document.querySelectorAll('input[name="jours_semaine[]"]').forEach(cb => cb.checked = false);
    }
}

// Événements pour la mise à jour de l'aperçu
document.getElementById('nom_programme').addEventListener('input', updatePreview);
document.getElementById('code_programme').addEventListener('input', updatePreview);
document.getElementById('type_programme').addEventListener('change', updatePreview);
document.getElementById('frequence').addEventListener('change', function() {
    updatePreview();
    toggleFrequencyFields();
});
document.getElementById('audience_cible').addEventListener('change', updatePreview);

// Validation du formulaire
document.getElementById('programmeForm').addEventListener('submit', function(e) {
    const nom = document.getElementById('nom_programme').value.trim();
    const type = document.getElementById('type_programme').value;
    const frequence = document.getElementById('frequence').value;
    const audience = document.getElementById('audience_cible').value;
    const statut = document.getElementById('statut').value;

    if (!nom || !type || !frequence || !audience || !statut) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }

    // Vérifier les horaires si spécifiés
    const heureDebut = document.getElementById('heure_debut').value;
    const heureFin = document.getElementById('heure_fin').value;

    if (heureDebut && heureFin && heureDebut >= heureFin) {
        e.preventDefault();
        alert('L\'heure de fin doit être postérieure à l\'heure de début.');
        return false;
    }

    // Vérifier les dates si spécifiées
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin').value;

    if (dateDebut && dateFin && dateDebut > dateFin) {
        e.preventDefault();
        alert('La date de fin doit être postérieure à la date de début.');
        return false;
    }
});

// Initialiser
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    toggleFrequencyFields();
});
</script>

@endsection
