@extends('layouts.private.main')
@section('title', 'Modifier la FIMECO')

@section('content')
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Modifier la FIMECO
            </h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.fimecos.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-coins mr-2"></i>
                            FIMECO
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <a href="{{ route('private.fimecos.show', $fimeco['id']) }}"
                                class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                {{ $fimeco['nom'] }}
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

        <!-- Alerte si FIMECO clôturée -->
        @if($fimeco['statut'] === 'cloturee')
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-red-400 mt-0.5 mr-3"></i>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">FIMECO clôturée</h3>
                        <p class="text-sm text-red-700 mt-1">Cette FIMECO est clôturée et ne peut plus être modifiée.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulaire principal -->
        @can('fimecos.update')
            <form action="{{ route('private.fimecos.update', $fimeco) }}" method="POST" id="fimecoEditForm" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Informations générales -->
                    <div class="lg:col-span-2">
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                    Informations Générales
                                </h2>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Champ Nom -->
                                <div>
                                    <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">
                                        Nom de la FIMECO <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="nom" name="nom" value="{{ old('nom', $fimeco['nom']) }}" required
                                        maxlength="100" {{ $fimeco['statut'] === 'cloturee' ? 'readonly' : '' }}
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ $fimeco['statut'] === 'cloturee' ? 'bg-slate-100' : '' }}">
                                    @error('nom')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Champ Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                                        Description
                                    </label>
                                    <textarea id="description" name="description" rows="4" {{ $fimeco['statut'] === 'cloturee' ? 'readonly' : '' }}
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ $fimeco['statut'] === 'cloturee' ? 'bg-slate-100' : '' }}">{{ old('description', $fimeco['description']) }}</textarea>
                                    @error('description')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Champs Dates -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Date de début -->
                                    <div>
                                        <label for="debut" class="block text-sm font-medium text-slate-700 mb-2">
                                            Date de début <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" id="debut" name="debut"
                                            value="{{ old('debut', \Carbon\Carbon::parse($fimeco['debut'])->format('Y-m-d')) }}"
                                            required {{ $fimeco['statut'] === 'cloturee' ? 'readonly' : '' }}
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('debut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ $fimeco['statut'] === 'cloturee' ? 'bg-slate-100' : '' }}">
                                        @error('debut')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Date de fin -->
                                    <div>
                                        <label for="fin" class="block text-sm font-medium text-slate-700 mb-2">
                                            Date de fin <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" id="fin" name="fin"
                                            value="{{ old('fin', \Carbon\Carbon::parse($fimeco['fin'])->format('Y-m-d')) }}"
                                            required {{ $fimeco['statut'] === 'cloturee' ? 'readonly' : '' }}
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fin') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ $fimeco['statut'] === 'cloturee' ? 'bg-slate-100' : '' }}">
                                        @error('fin')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Champ Responsable -->
                                <div>
                                    <label for="responsable_id" class="block text-sm font-medium text-slate-700 mb-2">
                                        Responsable
                                    </label>
                                    <select id="responsable_id" name="responsable_id" {{ $fimeco['statut'] === 'cloturee' ? 'disabled' : '' }}
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('responsable_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ $fimeco['statut'] === 'cloturee' ? 'bg-slate-100' : '' }}">
                                        <option value="">Sélectionner un responsable</option>
                                        @foreach($responsables as $responsable)
                                            <option value="{{ $responsable->id }}" {{ (old('responsable_id', $fimeco['responsable']['id'] ?? '') == $responsable->id) ? 'selected' : '' }}>
                                                {{ $responsable->nom_complet }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('responsable_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Champ Statut -->
                                <div>
                                    <label for="statut" class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                                    <select id="statut" name="statut" {{ $fimeco['statut'] === 'cloturee' ? 'disabled' : '' }}
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $fimeco['statut'] === 'cloturee' ? 'bg-slate-100' : '' }}">
                                        <option value="active" {{ old('statut', $fimeco['statut']) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('statut', $fimeco['statut']) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        @if($fimeco['statut'] === 'cloturee')
                                            <option value="cloturee" selected>Clôturée</option>
                                        @endif
                                    </select>
                                    @if($fimeco['statut'] !== 'cloturee')
                                        <p class="mt-1 text-sm text-slate-500">Une FIMECO active peut recevoir des souscriptions</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne de droite : Statistiques et aperçu -->
                    <div class="space-y-6">
                        <!-- Statistiques actuelles -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                                    Statistiques Actuelles
                                </h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Total collecté:</span>
                                    <span class="text-sm text-green-600 font-semibold">
                                        {{ number_format($fimeco['total_paye'], 0, ',', ' ') }} FCFA
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Progression:</span>
                                    <span
                                        class="text-sm text-blue-600 font-semibold">{{ $fimeco['pourcentage_realisation'] }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full"
                                        style="width: {{ min($fimeco['pourcentage_realisation'], 100) }}%">
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Souscripteurs:</span>
                                    <span
                                        class="text-sm text-purple-600 font-semibold">{{ $fimeco['nombre_souscripteurs'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Aperçu des modifications -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-eye text-purple-600 mr-2"></i>
                                    Aperçu
                                </h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Nom:</span>
                                    <span id="preview-nom"
                                        class="text-sm text-slate-900 font-semibold">{{ $fimeco['nom'] }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Durée:</span>
                                    <span id="preview-duree" class="text-sm text-slate-600">-</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Statut:</span>
                                    <span id="preview-statut" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                                  @if($fimeco['statut'] === 'active') bg-green-100 text-green-800
                                                                                  @elseif($fimeco['statut'] === 'cloturee') bg-red-100 text-red-800
                                                                                  @else bg-yellow-100 text-yellow-800
                                                                                  @endif">
                                        {{ ucfirst($fimeco['statut']) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Actions rapides -->
                        @if($fimeco['statut'] !== 'cloturee')
                            <div
                                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                                <div class="p-6 border-b border-slate-200">
                                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                        <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                                        Actions Rapides
                                    </h2>
                                </div>
                                <div class="p-6 space-y-3">
                                    @if($fimeco['statut'] !== 'cloturee')
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                            <i class="fas fa-lock mr-2"></i> Mettre à jour
                                        </button>
                                    @endif

                                    <a href="{{ route('private.fimecos.show', $fimeco['id']) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                        <i class="fas fa-eye mr-2"></i> Voir FIMECO
                                    </a>
                                    <a href="{{ route('private.fimecos.index') }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                        <i class="fas fa-list mr-2"></i> Retour à la liste
                                    </a>

                                    <form action="{{ route('private.fimecos.cloturer', $fimeco['id']) }}" method="POST"
                                        id="clotureForm">
                                        @csrf
                                        <button type="button" onclick="confirmerCloture()"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                            <i class="fas fa-lock mr-2"></i> Clôturer la FIMECO
                                        </button>
                                    </form>
                                    <a href="{{ route('private.fimecos.statistiques', $fimeco['id']) }}"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-chart-line mr-2"></i> Voir Statistiques
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                
            </form>
        @endcan
    </div>

    <!-- Modal de confirmation de clôture -->
    <div id="clotureModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-lock text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900">Clôturer la FIMECO</h3>
                </div>
                <p class="text-slate-600 mb-4">
                    Êtes-vous sûr de vouloir clôturer cette FIMECO ? Cette action est définitive.
                </p>
                <div class="mb-4">
                    <label for="commentaireCloture" class="block text-sm font-medium text-slate-700 mb-2">
                        Commentaire (optionnel)
                    </label>
                    <textarea id="commentaireCloture" rows="3"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Motif de la clôture..."></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="fermerModalCloture()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                @can('fimecos.close')
                    <button type="button" onclick="executerCloture()"
                        class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                        Clôturer
                    </button>
                @endcan
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // JavaScript repris complètement - FIMECO Edit Form
            (function () {
                'use strict';

                console.log('Initialisation du script FIMECO...');

                // Variables globales
                let formElements = {};
                let isSubmitting = false;

                // Fonction d'initialisation principale
                function initializeForm() {
                    console.log('Recherche des éléments du formulaire...');

                    // Récupération des éléments
                    formElements = {
                        form: document.getElementById('fimecoEditForm'),
                        submitBtn: document.querySelector('#fimecoEditForm button[type="submit"]'),
                        nom: document.getElementById('nom'),
                        debut: document.getElementById('debut'),
                        fin: document.getElementById('fin'),
                        statut: document.getElementById('statut'),
                        previewNom: document.getElementById('preview-nom'),
                        previewDuree: document.getElementById('preview-duree'),
                        previewStatut: document.getElementById('preview-statut'),
                        clotureModal: document.getElementById('clotureModal'),
                        clotureForm: document.getElementById('clotureForm'),
                        commentaireCloture: document.getElementById('commentaireCloture')
                    };

                    // Vérification des éléments critiques
                    if (!formElements.form) {
                        console.error('Formulaire principal non trouvé !');
                        return false;
                    }

                    console.log('Éléments trouvés:', {
                        form: !!formElements.form,
                        submitBtn: !!formElements.submitBtn,
                        nom: !!formElements.nom
                    });

                    return true;
                }

                // Fonction de validation du formulaire
                function validateForm() {
                    const nom = formElements.nom?.value?.trim() || '';
                    const debut = formElements.debut?.value || '';
                    const fin = formElements.fin?.value || '';

                    // Validation nom
                    if (nom.length < 3) {
                        alert('Le nom de la FIMECO doit contenir au moins 3 caractères.');
                        formElements.nom?.focus();
                        return false;
                    }

                    // Validation dates
                    if (!debut || !fin) {
                        alert('Veuillez renseigner les dates de début et de fin.');
                        return false;
                    }

                    const dateDebut = new Date(debut);
                    const dateFin = new Date(fin);

                    if (dateFin <= dateDebut) {
                        alert('La date de fin doit être postérieure à la date de début.');
                        formElements.fin?.focus();
                        return false;
                    }

                    return true;
                }

                // Fonction de mise à jour de l'aperçu
                function updatePreview() {
                    if (!formElements.previewNom) return;

                    const nom = formElements.nom?.value || '{{ $fimeco['nom'] ?? '' }}';
                    const debut = formElements.debut?.value;
                    const fin = formElements.fin?.value;
                    const statut = formElements.statut?.value;

                    // Mettre à jour le nom
                    formElements.previewNom.textContent = nom;

                    // Calculer et afficher la durée
                    if (debut && fin && formElements.previewDuree) {
                        const dateDebut = new Date(debut);
                        const dateFin = new Date(fin);
                        const diffTime = Math.abs(dateFin - dateDebut);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                        if (diffDays <= 1) {
                            formElements.previewDuree.textContent = '1 jour';
                        } else if (diffDays < 30) {
                            formElements.previewDuree.textContent = diffDays + ' jours';
                        } else {
                            const diffMonths = Math.ceil(diffDays / 30);
                            formElements.previewDuree.textContent = diffMonths + ' mois';
                        }
                    }

                    // Mettre à jour le statut
                    if (formElements.previewStatut && statut) {
                        let className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ';
                        let text = '';

                        switch (statut) {
                            case 'active':
                                className += 'bg-green-100 text-green-800';
                                text = 'Active';
                                break;
                            case 'cloturee':
                                className += 'bg-red-100 text-red-800';
                                text = 'Clôturée';
                                break;
                            default:
                                className += 'bg-yellow-100 text-yellow-800';
                                text = 'Inactive';
                        }

                        formElements.previewStatut.className = className;
                        formElements.previewStatut.textContent = text;
                    }
                }

                // Fonction de gestion de la soumission
                function handleSubmit(event) {
                    console.log('Tentative de soumission du formulaire');

                    // Empêcher les doubles soumissions
                    if (isSubmitting) {
                        console.log('Soumission déjà en cours, annulation');
                        event.preventDefault();
                        return false;
                    }

                    // Valider le formulaire
                    if (!validateForm()) {
                        console.log('Validation échouée');
                        event.preventDefault();
                        return false;
                    }

                    // Marquer comme en cours de soumission
                    isSubmitting = true;
                    console.log('Validation réussie, soumission autorisée');

                    // Feedback visuel
                    if (formElements.submitBtn) {
                        formElements.submitBtn.disabled = true;
                        formElements.submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mise à jour en cours...';
                    }

                    // Laisser le formulaire se soumettre naturellement
                    return true;
                }

                // Fonctions pour le modal de clôture
                function openClotureModal() {
                    if (formElements.clotureModal) {
                        formElements.clotureModal.classList.remove('hidden');
                    }
                }

                function closeClotureModal() {
                    if (formElements.clotureModal) {
                        formElements.clotureModal.classList.add('hidden');
                    }
                }

                function executeClotureForm() {
                    if (!formElements.clotureForm) return;

                    const commentaire = formElements.commentaireCloture?.value || '';

                    // Ajouter le commentaire au formulaire de clôture
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'commentaire';
                    input.value = commentaire;
                    formElements.clotureForm.appendChild(input);

                    // Soumettre le formulaire de clôture
                    formElements.clotureForm.submit();
                }

                // Fonction d'attachement des événements
                function attachEvents() {
                    console.log('Attachement des événements...');

                    // Événement principal de soumission
                    if (formElements.form) {
                        formElements.form.addEventListener('submit', handleSubmit);
                        console.log('Événement submit attaché au formulaire');
                    }

                    // Événement de clic sur le bouton submit (backup)
                    if (formElements.submitBtn) {
                        formElements.submitBtn.addEventListener('click', function (e) {
                            console.log('Clic détecté sur le bouton submit');
                        });
                    }

                    // Événements pour la mise à jour de l'aperçu
                    const fieldsToWatch = ['nom', 'debut', 'fin', 'statut'];
                    fieldsToWatch.forEach(fieldName => {
                        const field = formElements[fieldName];
                        if (field) {
                            const eventType = field.type === 'date' || field.tagName === 'SELECT' ? 'change' : 'input';
                            field.addEventListener(eventType, updatePreview);
                        }
                    });

                    // Événements pour le modal de clôture
                    if (formElements.clotureModal) {
                        formElements.clotureModal.addEventListener('click', function (e) {
                            if (e.target === this) {
                                closeClotureModal();
                            }
                        });
                    }

                    console.log('Tous les événements ont été attachés');
                }

                // Exposition des fonctions globales nécessaires
                window.confirmerCloture = openClotureModal;
                window.fermerModalCloture = closeClotureModal;
                window.executerCloture = executeClotureForm;

                // Initialisation au chargement du DOM
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', function () {
                        console.log('DOM chargé, initialisation...');
                        if (initializeForm()) {
                            attachEvents();
                            updatePreview();
                            console.log('Initialisation terminée avec succès');
                        }
                    });
                } else {
                    // DOM déjà chargé
                    console.log('DOM déjà chargé, initialisation immédiate...');
                    if (initializeForm()) {
                        attachEvents();
                        updatePreview();
                        console.log('Initialisation terminée avec succès');
                    }
                }

            })();
        </script>
    @endpush
@endsection
