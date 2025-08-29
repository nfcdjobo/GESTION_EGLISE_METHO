@extends('layouts.private.main')
@section('title', 'Créer une Intervention')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer une Nouvelle Intervention</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.interventions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-microphone mr-2"></i>
                        Interventions
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

    <form action="{{ route('private.interventions.store') }}" method="POST" id="interventionForm" class="space-y-8">
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
                        <!-- Titre -->
                        <div>
                            <label for="titre" class="block text-sm font-medium text-slate-700 mb-2">
                                Titre de l'intervention <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required maxlength="200" placeholder="Ex: Prédication sur l'espérance"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('titre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('titre')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type et Intervenant -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type_intervention" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type d'intervention <span class="text-red-500">*</span>
                                </label>
                                <select id="type_intervention" name="type_intervention" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_intervention') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un type</option>
                                    @foreach($types_intervention as $key => $label)
                                        <option value="{{ $key }}" {{ old('type_intervention') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_intervention')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="intervenant_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Intervenant <span class="text-red-500">*</span>
                                </label>
                                <select id="intervenant_id" name="intervenant_id" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('intervenant_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un intervenant</option>
                                    @foreach($intervenants as $intervenant)
                                        <option value="{{ $intervenant->id }}" {{ old('intervenant_id') == $intervenant->id ? 'selected' : '' }}>
                                            {{ $intervenant->nom. ' '. $intervenant->prenom }} ({{ $intervenant->telephone_1 }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('intervenant_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="3" placeholder="Description détaillée de l'intervention..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Passage biblique -->
                        <div>
                            <label for="passage_biblique" class="block text-sm font-medium text-slate-700 mb-2">Passage biblique</label>
                            <input type="text" id="passage_biblique" name="passage_biblique" value="{{ old('passage_biblique') }}" maxlength="300" placeholder="Ex: Jean 3:16-21"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('passage_biblique') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('passage_biblique')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-slate-500">Référence biblique pour cette intervention</p>
                        </div>
                    </div>
                </div>

                <!-- Événement et Planification -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 mt-6">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                            Événement et Planification
                        </h2>
                        <p class="text-slate-500 mt-1">Associer l'intervention à un culte OU une réunion</p>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Sélection Culte/Réunion -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="culte_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Culte
                                </label>
                                <select id="culte_id" name="culte_id" onchange="handleEventSelection('culte')"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('culte_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un culte</option>
                                    @foreach($cultes as $culte)
                                        <option value="{{ $culte->id }}" {{ old('culte_id') == $culte->id ? 'selected' : '' }}>
                                            {{ $culte->titre }} - {{ $culte->date_culte ? $culte->date_culte->format('d/m/Y') : 'Date TBD' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('culte_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="reunion_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Réunion
                                </label>
                                <select id="reunion_id" name="reunion_id" onchange="handleEventSelection('reunion')"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('reunion_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner une réunion</option>
                                    @foreach($reunions as $reunion)
                                        <option value="{{ $reunion->id }}" {{ old('reunion_id') == $reunion->id ? 'selected' : '' }}>
                                            {{ $reunion->titre }} - {{ $reunion->date_reunion ? $reunion->date_reunion->format('d/m/Y') : 'Date TBD' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('reunion_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        @error('evenement')
                            <div class="p-4 bg-red-50 border border-red-200 rounded-xl">
                                <div class="flex">
                                    <i class="fas fa-exclamation-circle text-red-400 mt-0.5 mr-3"></i>
                                    <p class="text-sm text-red-700">{{ $message }}</p>
                                </div>
                            </div>
                        @enderror

                        <!-- Timing -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="heure_debut" class="block text-sm font-medium text-slate-700 mb-2">
                                    Heure de début
                                </label>
                                <input type="time" id="heure_debut" name="heure_debut" value="{{ old('heure_debut') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_debut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('heure_debut')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="duree_minutes" class="block text-sm font-medium text-slate-700 mb-2">
                                    Durée (minutes)
                                </label>
                                <input type="number" id="duree_minutes" name="duree_minutes" value="{{ old('duree_minutes', 15) }}" min="1" max="480"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('duree_minutes') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('duree_minutes')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="ordre_passage" class="block text-sm font-medium text-slate-700 mb-2">
                                    Ordre de passage
                                </label>
                                <input type="number" id="ordre_passage" name="ordre_passage" value="{{ old('ordre_passage') }}" min="1"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('ordre_passage') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('ordre_passage')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Position dans le programme</p>
                            </div>
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
                            <span class="text-sm font-medium text-slate-700">Titre:</span>
                            <span id="preview-titre" class="text-sm text-slate-900 font-semibold">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Type:</span>
                            <span id="preview-type" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Intervenant:</span>
                            <span id="preview-intervenant" class="text-sm text-slate-900">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Événement:</span>
                            <span id="preview-evenement" class="text-sm text-slate-900">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Durée:</span>
                            <span id="preview-duree" class="text-sm text-slate-900">15 min</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Statut:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Prévue</span>
                        </div>
                    </div>
                </div>

                <!-- Guide des types -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-green-600 mr-2"></i>
                            Types d'Intervention
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="text-sm space-y-2">
                            <div class="flex justify-between">
                                <span class="font-medium text-slate-700">Prédication</span>
                                <span class="text-slate-500">Message principal</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-slate-700">Témoignage</span>
                                <span class="text-slate-500">Partage personnel</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-slate-700">Louange</span>
                                <span class="text-slate-500">Chants et musique</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-slate-700">Prière</span>
                                <span class="text-slate-500">Temps de prière</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-slate-700">Lecture</span>
                                <span class="text-slate-500">Lecture biblique</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium text-slate-700">Autres</span>
                                <span class="text-slate-500">Annonces, offrandes...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statut -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-tasks text-amber-600 mr-2"></i>
                            Statut Initial
                        </h2>
                    </div>
                    <div class="p-6">
                        <select name="statut" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @foreach($statuts as $key => $label)
                                <option value="{{ $key }}" {{ old('statut', 'prevue') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-sm text-slate-500">Par défaut: Prévue</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer l'Intervention
                    </button>
                    <a href="{{ route('private.interventions.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Types d'intervention pour l'aperçu
const typesIntervention = @json($types_intervention);
const intervenants = @json($intervenants->pluck('name', 'id'));
const cultes = @json($cultes->pluck('nom', 'id'));
const reunions = @json($reunions->pluck('nom', 'id'));

// Mise à jour de l'aperçu
function updatePreview() {
    const titre = document.getElementById('titre').value || '-';
    const type = document.getElementById('type_intervention').value;
    const intervenantId = document.getElementById('intervenant_id').value;
    const culteId = document.getElementById('culte_id').value;
    const reunionId = document.getElementById('reunion_id').value;
    const duree = document.getElementById('duree_minutes').value || 15;

    document.getElementById('preview-titre').textContent = titre;

    // Type
    const typeLabel = type && typesIntervention[type] ? typesIntervention[type] : '-';
    document.getElementById('preview-type').textContent = typeLabel;

    // Intervenant
    const intervenantName = intervenantId && intervenants[intervenantId] ? intervenants[intervenantId] : '-';
    document.getElementById('preview-intervenant').textContent = intervenantName;

    // Événement
    let evenement = '-';
    if (culteId && cultes[culteId]) {
        evenement = 'Culte: ' + cultes[culteId];
    } else if (reunionId && reunions[reunionId]) {
        evenement = 'Réunion: ' + reunions[reunionId];
    }
    document.getElementById('preview-evenement').textContent = evenement;

    // Durée
    document.getElementById('preview-duree').textContent = duree + ' min';
}

// Gestion de la sélection exclusive culte/réunion
function handleEventSelection(selectedType) {
    if (selectedType === 'culte') {
        const culteSelect = document.getElementById('culte_id');
        if (culteSelect.value) {
            document.getElementById('reunion_id').value = '';
        }
    } else if (selectedType === 'reunion') {
        const reunionSelect = document.getElementById('reunion_id');
        if (reunionSelect.value) {
            document.getElementById('culte_id').value = '';
        }
    }
    updatePreview();
}

// Événements pour la mise à jour de l'aperçu
document.getElementById('titre').addEventListener('input', updatePreview);
document.getElementById('type_intervention').addEventListener('change', updatePreview);
document.getElementById('intervenant_id').addEventListener('change', updatePreview);
document.getElementById('culte_id').addEventListener('change', updatePreview);
document.getElementById('reunion_id').addEventListener('change', updatePreview);
document.getElementById('duree_minutes').addEventListener('input', updatePreview);

// Validation du formulaire
document.getElementById('interventionForm').addEventListener('submit', function(e) {
    const titre = document.getElementById('titre').value.trim();
    const typeIntervention = document.getElementById('type_intervention').value;
    const intervenantId = document.getElementById('intervenant_id').value;
    const culteId = document.getElementById('culte_id').value;
    const reunionId = document.getElementById('reunion_id').value;

    if (!titre || !typeIntervention || !intervenantId) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }

    if (!culteId && !reunionId) {
        e.preventDefault();
        alert('Veuillez sélectionner soit un culte, soit une réunion.');
        return false;
    }

    if (culteId && reunionId) {
        e.preventDefault();
        alert('Veuillez sélectionner soit un culte, soit une réunion, mais pas les deux.');
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
