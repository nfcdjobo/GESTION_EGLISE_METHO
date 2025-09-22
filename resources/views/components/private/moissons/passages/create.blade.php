@extends('layouts.private.main')
@section('title', 'Nouveau Passage - ' . $moisson->theme)

@section('content')
    <div class="space-y-8">
        <!-- En-tête avec navigation -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-slate-600 mb-4">
                <a href="{{ route('private.moissons.index') }}" class="hover:text-blue-600 transition-colors">
                    <i class="fas fa-seedling mr-1"></i> Moissons
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('private.moissons.show', $moisson) }}" class="hover:text-blue-600 transition-colors">
                    {{ Str::limit($moisson->theme, 30) }}
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('private.moissons.passages.index', $moisson) }}" class="hover:text-blue-600 transition-colors">
                    Passages
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium">Nouveau passage</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Créer un nouveau passage
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Ajouter un passage de collecte pour la moisson "{{ $moisson->theme }}"
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('private.moissons.passages.index', $moisson) }}"
                        class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations sur la moisson -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-lg border border-blue-200/50 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $moisson->theme }}</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-slate-600">Date:</span>
                            <span class="font-medium text-slate-800 ml-1">{{ $moisson->date->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Objectif:</span>
                            <span class="font-medium text-slate-800 ml-1">{{ number_format($moisson->cible, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Collecté:</span>
                            <span class="font-medium text-green-600 ml-1">{{ number_format($moisson->montant_solde, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Progression:</span>
                            <span class="font-medium text-blue-600 ml-1">{{ $moisson->pourcentage_realise }}%</span>
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $moisson->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $moisson->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Catégories déjà utilisées -->
        @if(count($categoriesUtilisees) > 0)
            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
                <h4 class="text-sm font-medium text-yellow-800 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>
                    Catégories déjà utilisées pour cette moisson
                </h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($categoriesUtilisees as $categorie)
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ \App\Models\PassageMoisson::CATEGORIES[$categorie] ?? $categorie }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Formulaire principal -->
        <form id="passage-form" action="{{ route('private.moissons.passages.store', $moisson) }}" method="POST" class="space-y-8">
            @csrf

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Informations du passage
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Catégorie -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Catégorie de passage <span class="text-red-500">*</span>
                            </label>
                            <select name="categorie" id="categorie" required onchange="toggleClasseField()"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach(\App\Models\PassageMoisson::CATEGORIES as $code => $libelle)
                                    <option value="{{ $code }}"
                                        {{ old('categorie') === $code ? 'selected' : '' }}
                                        {{ in_array($code, $categoriesUtilisees) ? 'data-used=true' : '' }}>
                                        {{ $libelle }}
                                        {{ in_array($code, $categoriesUtilisees) ? ' (déjà utilisée)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categorie')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">
                                Choisissez le type de passage pour cette collecte
                            </p>
                        </div>

                        <!-- Classe (si passage classe communautaire) -->
                        <div id="classe-field" class="lg:col-span-2 hidden">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Classe <span class="text-red-500">*</span>
                            </label>
                            <select name="classe_id" id="classe_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionnez une classe</option>
                                @foreach($classes as $classe)
                                    <option value="{{ $classe->id }}" {{ old('classe_id') === $classe->id ? 'selected' : '' }}>
                                        {{ $classe->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">
                                Classe concernée par ce passage communautaire
                            </p>
                        </div>

                        <!-- Objectif financier -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Objectif financier (FCFA) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="cible" id="cible" required min="1" step="1"
                                placeholder="Ex: 500000"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('cible') }}">
                            @error('cible')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">
                                Montant à atteindre pour ce passage
                            </p>
                        </div>

                        <!-- Collecteur -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Collecteur responsable <span class="text-red-500">*</span>
                            </label>
                            <select name="collecter_par" id="collecter_par" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionnez un collecteur</option>
                                @foreach($collecteurs as $collecteur)
                                    <option value="{{ $collecteur->id }}" {{ old('collecter_par') === $collecteur->id ? 'selected' : '' }}>
                                        {{ $collecteur->nom_complet }}
                                    </option>
                                @endforeach
                            </select>
                            @error('collecter_par')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">
                                Personne responsable de la collecte
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Montant initial (optionnel) -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-coins text-green-600 mr-2"></i>
                        Montant initial (optionnel)
                    </h3>
                    <p class="text-sm text-slate-600 mt-1">Si vous avez déjà collecté des fonds pour ce passage</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Montant déjà collecté (FCFA)
                            </label>
                            <input type="number" name="montant_initial" id="montant_initial" min="0" step="0.01"
                                placeholder="Ex: 150000"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('montant_initial', 0) }}">
                            <p class="text-xs text-slate-500 mt-1">
                                Laissez à 0 si aucun montant n'a encore été collecté
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Date de collecte initiale
                            </label>
                            <input type="datetime-local" name="date_collecte_initiale" id="date_collecte_initiale"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('date_collecte_initiale') }}">
                            <p class="text-xs text-slate-500 mt-1">
                                Date et heure de la collecte initiale (si applicable)
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statut initial -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-toggle-on text-blue-600 mr-2"></i>
                        Configuration du passage
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Statut -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">Statut initial du passage</label>
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="1" {{ old('status', '1') === '1' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <span class="ml-3 text-sm text-slate-700">
                                        <span class="font-medium">Actif</span>
                                        <span class="block text-xs text-slate-500">Le passage est opérationnel et peut recevoir des collectes</span>
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="0" {{ old('status', '1') === '0' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <span class="ml-3 text-sm text-slate-700">
                                        <span class="font-medium">Inactif</span>
                                        <span class="block text-xs text-slate-500">Le passage est créé mais pas encore opérationnel</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Notes et remarques
                            </label>
                            <textarea name="notes" id="notes" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Notes, instructions particulières, ou remarques concernant ce passage...">{{ old('notes') }}</textarea>
                            <p class="text-xs text-slate-500 mt-1">
                                Informations complémentaires sur ce passage (optionnel)
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé du passage -->
            <div id="resume-passage" class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl shadow-lg border border-green-200/50 p-6 hidden">
                <h3 class="text-lg font-bold text-slate-800 mb-4">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    Résumé du passage à créer
                </h3>
                <div id="resume-contenu" class="grid grid-cols-1 lg:grid-cols-2 gap-4 text-sm">
                    <!-- Le contenu sera mis à jour dynamiquement -->
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('private.moissons.passages.index', $moisson) }}"
                    class="inline-flex items-center px-6 py-3 border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" id="btn-submit"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Créer le passage
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Gestion du champ classe pour passage classe communautaire
            function toggleClasseField() {
                const categorieSelect = document.getElementById('categorie');
                const classeField = document.getElementById('classe-field');
                const classeSelect = document.getElementById('classe_id');

                if (categorieSelect.value === 'passage_classe_communautaire') {
                    classeField.classList.remove('hidden');
                    classeSelect.required = true;
                } else {
                    classeField.classList.add('hidden');
                    classeSelect.required = false;
                    classeSelect.value = '';
                }

                mettreAJourResume();
            }

            // Mettre à jour le résumé du passage
            function mettreAJourResume() {
                const categorie = document.getElementById('categorie');
                const classe = document.getElementById('classe_id');
                const cible = document.getElementById('cible');
                const collecteur = document.getElementById('collecter_par');
                const montantInitial = document.getElementById('montant_initial');
                const status = document.querySelector('input[name="status"]:checked');

                const resumeDiv = document.getElementById('resume-passage');
                const resumeContenu = document.getElementById('resume-contenu');

                if (categorie.value && cible.value && collecteur.value) {
                    const categorieText = categorie.options[categorie.selectedIndex].text;
                    const collecteurText = collecteur.options[collecteur.selectedIndex].text;
                    const classeText = classe.value ? classe.options[classe.selectedIndex].text : '';
                    const cibleFormatted = parseInt(cible.value).toLocaleString('fr-FR');
                    const montantInitialFormatted = parseFloat(montantInitial.value || 0).toLocaleString('fr-FR');
                    const statusText = status?.value === '1' ? 'Actif' : 'Inactif';

                    let html = `
                        <div>
                            <span class="text-slate-600">Catégorie:</span>
                            <span class="font-medium text-slate-800 ml-1">${categorieText}</span>
                        </div>
                    `;

                    if (classeText) {
                        html += `
                            <div>
                                <span class="text-slate-600">Classe:</span>
                                <span class="font-medium text-slate-800 ml-1">${classeText}</span>
                            </div>
                        `;
                    }

                    html += `
                        <div>
                            <span class="text-slate-600">Objectif:</span>
                            <span class="font-medium text-slate-800 ml-1">${cibleFormatted} FCFA</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Collecteur:</span>
                            <span class="font-medium text-slate-800 ml-1">${collecteurText}</span>
                        </div>
                    `;

                    if (parseFloat(montantInitial.value || 0) > 0) {
                        const pourcentage = ((parseFloat(montantInitial.value || 0) / parseFloat(cible.value)) * 100).toFixed(1);
                        html += `
                            <div>
                                <span class="text-slate-600">Montant initial:</span>
                                <span class="font-medium text-green-600 ml-1">${montantInitialFormatted} FCFA (${pourcentage}%)</span>
                            </div>
                        `;
                    }

                    html += `
                        <div>
                            <span class="text-slate-600">Statut:</span>
                            <span class="font-medium ${status?.value === '1' ? 'text-green-600' : 'text-red-600'} ml-1">${statusText}</span>
                        </div>
                    `;

                    resumeContenu.innerHTML = html;
                    resumeDiv.classList.remove('hidden');
                } else {
                    resumeDiv.classList.add('hidden');
                }
            }

            // Validation du formulaire
            document.getElementById('passage-form').addEventListener('submit', function(e) {
                const categorie = document.getElementById('categorie').value.trim();
                const cible = parseFloat(document.getElementById('cible').value || 0);
                const collecteur = document.getElementById('collecter_par').value;
                const classe = document.getElementById('classe_id').value;
                const montantInitial = parseFloat(document.getElementById('montant_initial').value || 0);

                // Validations de base
                if (!categorie || !cible || !collecteur) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires.');
                    return;
                }

                if (cible <= 0) {
                    e.preventDefault();
                    alert('L\'objectif financier doit être supérieur à 0.');
                    document.getElementById('cible').focus();
                    return;
                }

                // Validation classe communautaire
                if (categorie === 'passage_classe_communautaire' && !classe) {
                    e.preventDefault();
                    alert('Une classe doit être sélectionnée pour un passage de classe communautaire.');
                    document.getElementById('classe_id').focus();
                    return;
                }

                // Validation montant initial
                if (montantInitial > cible) {
                    e.preventDefault();
                    const confirmer = confirm('Le montant initial est supérieur à l\'objectif. Voulez-vous continuer ?');
                    if (!confirmer) {
                        document.getElementById('montant_initial').focus();
                        return;
                    }
                }

                // Vérifier les catégories déjà utilisées
                const categorieOption = document.querySelector(`option[value="${categorie}"]`);
                if (categorieOption && categorieOption.hasAttribute('data-used')) {
                    e.preventDefault();
                    const confirmer = confirm('Cette catégorie est déjà utilisée pour cette moisson. Voulez-vous vraiment continuer ?');
                    if (!confirmer) {
                        return;
                    }
                }

                // Désactiver le bouton de soumission pour éviter les doublons
                const btnSubmit = document.getElementById('btn-submit');
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Création en cours...';
            });

            // Écouteurs d'événements pour mettre à jour le résumé
            document.addEventListener('DOMContentLoaded', function() {
                // Mettre à jour le résumé lors des changements
                ['categorie', 'classe_id', 'cible', 'collecter_par', 'montant_initial'].forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.addEventListener('change', mettreAJourResume);
                        element.addEventListener('input', mettreAJourResume);
                    }
                });

                // Écouteur pour les boutons radio status
                document.querySelectorAll('input[name="status"]').forEach(radio => {
                    radio.addEventListener('change', mettreAJourResume);
                });

                // Initialiser l'affichage du champ classe
                toggleClasseField();

                // Définir la date/heure actuelle par défaut si montant initial > 0
                const montantInitial = document.getElementById('montant_initial');
                const dateCollecte = document.getElementById('date_collecte_initiale');

                montantInitial.addEventListener('input', function() {
                    if (parseFloat(this.value || 0) > 0 && !dateCollecte.value) {
                        const now = new Date();
                        const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000)
                            .toISOString().slice(0, 16);
                        dateCollecte.value = localDateTime;
                    } else if (parseFloat(this.value || 0) === 0) {
                        dateCollecte.value = '';
                    }
                });

                // Animation des cartes au chargement
                const cards = document.querySelectorAll('.bg-white\\/80');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    // card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        // card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
    @endpush
@endsection
