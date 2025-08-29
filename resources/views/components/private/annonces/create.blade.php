@extends('layouts.private.main')
@section('title', 'Créer une Annonce')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer une Nouvelle Annonce</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.annonces.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-bullhorn mr-2"></i>
                        Annonces
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

    <form action="{{ route('private.annonces.store') }}" method="POST" id="annonceForm" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations de base -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations de Base
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="titre" class="block text-sm font-medium text-slate-700 mb-2">
                                Titre de l'annonce <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required maxlength="200" placeholder="Titre accrocheur pour l'annonce"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('titre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('titre')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contenu" class="block text-sm font-medium text-slate-700 mb-2">
                                Contenu de l'annonce <span class="text-red-500">*</span>
                            </label>
                            <div class="@error('contenu') has-error @enderror">
                                <textarea id="contenu" name="contenu" rows="6" placeholder="Contenu détaillé de l'annonce"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('contenu') }}</textarea>
                            </div>
                            @error('contenu')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="type_annonce" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type d'annonce <span class="text-red-500">*</span>
                                </label>
                                <select id="type_annonce" name="type_annonce" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_annonce') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner le type</option>
                                    @foreach($typesAnnonces as $key => $label)
                                        <option value="{{ $key }}" {{ old('type_annonce') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type_annonce')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="niveau_priorite" class="block text-sm font-medium text-slate-700 mb-2">
                                    Niveau de priorité
                                </label>
                                <select id="niveau_priorite" name="niveau_priorite"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('niveau_priorite') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @foreach($niveauxPriorite as $key => $label)
                                        <option value="{{ $key }}" {{ old('niveau_priorite', 'normal') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('niveau_priorite')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="audience_cible" class="block text-sm font-medium text-slate-700 mb-2">
                                    Audience cible
                                </label>
                                <select id="audience_cible" name="audience_cible"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('audience_cible') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @foreach($audiencesCibles as $key => $label)
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

                <!-- Détails de l'événement (conditionnel) -->
                <div id="evenement_section" class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 hidden">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-event text-green-600 mr-2"></i>
                            Détails de l'Événement
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="date_evenement" class="block text-sm font-medium text-slate-700 mb-2">
                                    Date de l'événement
                                </label>
                                <input type="date" id="date_evenement" name="date_evenement" value="{{ old('date_evenement') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_evenement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('date_evenement')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="lieu_evenement" class="block text-sm font-medium text-slate-700 mb-2">
                                    Lieu de l'événement
                                </label>
                                <input type="text" id="lieu_evenement" name="lieu_evenement" value="{{ old('lieu_evenement') }}" maxlength="255" placeholder="Lieu de l'événement"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lieu_evenement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('lieu_evenement')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact et responsabilité -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-friends text-purple-600 mr-2"></i>
                            Contact et Responsabilité
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="contact_principal_id" class="block text-sm font-medium text-slate-700 mb-2">
                                Contact principal
                            </label>
                            <select id="contact_principal_id" name="contact_principal_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('contact_principal_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">Sélectionner un contact</option>
                                @foreach($contacts as $contact)
                                    <option value="{{ $contact->id }}" {{ old('contact_principal_id') == $contact->id ? 'selected' : '' }}>
                                        {{ $contact->nom }} {{ $contact->prenom }} ({{ $contact->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('contact_principal_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Paramètres de diffusion -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-broadcast-tower text-amber-600 mr-2"></i>
                            Paramètres de Diffusion
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="afficher_site_web" name="afficher_site_web" value="1" {{ old('afficher_site_web', true) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="afficher_site_web" class="ml-2 text-sm font-medium text-slate-700">
                                        Afficher sur le site web
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="annoncer_culte" name="annoncer_culte" value="1" {{ old('annoncer_culte') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="annoncer_culte" class="ml-2 text-sm font-medium text-slate-700">
                                        Annoncer pendant le culte
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="expire_le" class="block text-sm font-medium text-slate-700 mb-2">
                                    Date d'expiration
                                </label>
                                <input type="datetime-local" id="expire_le" name="expire_le" value="{{ old('expire_le') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('expire_le') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                <p class="mt-1 text-xs text-slate-500">Laisser vide pour une annonce permanente</p>
                                @error('expire_le')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Aperçu et aide -->
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
                            <span id="preview-type" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Priorité:</span>
                            <span id="preview-priorite" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Audience:</span>
                            <span id="preview-audience" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Diffusion:</span>
                            <div id="preview-diffusion" class="flex flex-col items-end space-y-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hidden" id="badge-web">
                                    Site web
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 hidden" id="badge-culte">
                                    Culte
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guide des types d'annonces -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info text-green-600 mr-2"></i>
                            Guide des Types
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div><strong>Événement:</strong> Activités, conférences, réunions</div>
                        <div><strong>Administrative:</strong> Informations organisationnelles</div>
                        <div><strong>Pastorale:</strong> Messages spirituels, exhortations</div>
                        <div><strong>Urgence:</strong> Communications importantes et urgentes</div>
                        <div><strong>Information:</strong> Informations générales</div>
                    </div>
                </div>

                <!-- Conseils -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                            Conseils
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm text-slate-600">
                        <div>• Utilisez un titre accrocheur et informatif</div>
                        <div>• Précisez toujours la date et le lieu pour les événements</div>
                        <div>• Définissez une date d'expiration appropriée</div>
                        <div>• Choisissez l'audience cible pour une meilleure pertinence</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" name="action" value="save" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-slate-600 to-slate-700 text-white font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer comme brouillon
                    </button>
                    <button type="submit" name="publier_maintenant" value="1" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i> Publier maintenant
                    </button>
                    <a href="{{ route('private.annonces.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Inclure les ressources CKEditor --}}
@include('partials.ckeditor-resources')

@push('scripts')
<script>
// Mise à jour de l'aperçu en temps réel
function updatePreview() {
    const titre = document.getElementById('titre').value || '-';
    const typeSelect = document.getElementById('type_annonce');
    const type = typeSelect.options[typeSelect.selectedIndex]?.text || '-';
    const prioriteSelect = document.getElementById('niveau_priorite');
    const priorite = prioriteSelect.options[prioriteSelect.selectedIndex]?.text || '-';
    const audienceSelect = document.getElementById('audience_cible');
    const audience = audienceSelect.options[audienceSelect.selectedIndex]?.text || '-';

    const afficherWeb = document.getElementById('afficher_site_web').checked;
    const annoncerCulte = document.getElementById('annoncer_culte').checked;

    document.getElementById('preview-titre').textContent = titre;
    document.getElementById('preview-type').textContent = type;
    document.getElementById('preview-priorite').textContent = priorite;
    document.getElementById('preview-audience').textContent = audience;

    // Mise à jour des badges de diffusion
    const badgeWeb = document.getElementById('badge-web');
    const badgeCulte = document.getElementById('badge-culte');

    if (afficherWeb) {
        badgeWeb.classList.remove('hidden');
    } else {
        badgeWeb.classList.add('hidden');
    }

    if (annoncerCulte) {
        badgeCulte.classList.remove('hidden');
    } else {
        badgeCulte.classList.add('hidden');
    }

    // Mise à jour de la classe de priorité
    const prioriteElement = document.getElementById('preview-priorite');
    prioriteElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';

    switch(prioriteSelect.value) {
        case 'urgent':
            prioriteElement.classList.add('bg-red-100', 'text-red-800');
            break;
        case 'important':
            prioriteElement.classList.add('bg-yellow-100', 'text-yellow-800');
            break;
        default:
            prioriteElement.classList.add('bg-gray-100', 'text-gray-800');
    }
}

// Gestion conditionnelle de la section événement
function toggleEvenementSection() {
    const typeSelect = document.getElementById('type_annonce');
    const evenementSection = document.getElementById('evenement_section');
    const dateInput = document.getElementById('date_evenement');
    const lieuInput = document.getElementById('lieu_evenement');

    if (typeSelect.value === 'evenement') {
        evenementSection.classList.remove('hidden');
        dateInput.required = true;
        lieuInput.required = true;
    } else {
        evenementSection.classList.add('hidden');
        dateInput.required = false;
        lieuInput.required = false;
    }
}

// Gestion de la priorité urgente pour le type urgence
function handleUrgencyType() {
    const typeSelect = document.getElementById('type_annonce');
    const prioriteSelect = document.getElementById('niveau_priorite');

    if (typeSelect.value === 'urgence') {
        prioriteSelect.value = 'urgent';
        prioriteSelect.disabled = true;
    } else {
        prioriteSelect.disabled = false;
    }
    updatePreview();
}

// Événements pour la mise à jour de l'aperçu
['titre', 'type_annonce', 'niveau_priorite', 'audience_cible'].forEach(id => {
    document.getElementById(id).addEventListener('input', updatePreview);
    document.getElementById(id).addEventListener('change', updatePreview);
});

document.getElementById('afficher_site_web').addEventListener('change', updatePreview);
document.getElementById('annoncer_culte').addEventListener('change', updatePreview);
document.getElementById('type_annonce').addEventListener('change', function() {
    toggleEvenementSection();
    handleUrgencyType();
});

// Validation du formulaire
document.getElementById('annonceForm').addEventListener('submit', function(e) {
    // Synchroniser CKEditor
    if (window.CKEditorInstances) {
        Object.values(window.CKEditorInstances).forEach(editor => {
            const element = editor.sourceElement;
            if (element) {
                element.value = editor.getData();
            }
        });
    }

    const titre = document.getElementById('titre').value.trim();
    const contenu = document.getElementById('contenu').value.trim();
    const type = document.getElementById('type_annonce').value;

    if (!titre || !contenu || !type) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }

    // Validation spécifique pour les événements
    if (type === 'evenement') {
        const dateEvenement = document.getElementById('date_evenement').value;
        const lieuEvenement = document.getElementById('lieu_evenement').value.trim();

        if (!dateEvenement || !lieuEvenement) {
            e.preventDefault();
            alert('Pour un événement, la date et le lieu sont obligatoires.');
            return false;
        }

        // Vérifier que la date n'est pas dans le passé
        const selectedDate = new Date(dateEvenement);
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (selectedDate < today) {
            e.preventDefault();
            alert('La date de l\'événement ne peut pas être dans le passé.');
            return false;
        }
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    toggleEvenementSection();
    handleUrgencyType();

    // Définir une date d'expiration par défaut (dans 30 jours)
    const expireInput = document.getElementById('expire_le');
    if (!expireInput.value) {
        const futureDate = new Date();
        futureDate.setDate(futureDate.getDate() + 30);
        expireInput.value = futureDate.toISOString().slice(0, 16);
    }
});
</script>
@endpush
@endsection
