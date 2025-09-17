@extends('layouts.private.main')
@section('title', 'Nouvelle Vente - ' . $moisson->theme)

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
                <a href="{{ route('private.moissons.ventes.index', $moisson) }}" class="hover:text-blue-600 transition-colors">
                    Ventes
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium">Nouvelle vente</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Créer une nouvelle vente
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Ajouter une vente de moisson pour "{{ $moisson->theme }}"
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('private.moissons.ventes.index', $moisson) }}"
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
                    Catégories de ventes déjà utilisées pour cette moisson
                </h4>
                <div class="flex flex-wrap gap-2">
                    @foreach($categoriesUtilisees as $categorie)
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ \App\Models\VenteMoisson::CATEGORIES[$categorie] ?? $categorie }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Formulaire principal -->
        <form id="vente-form" action="{{ route('private.moissons.ventes.store', $moisson) }}" method="POST" class="space-y-8">
            @csrf

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-store text-blue-600 mr-2"></i>
                        Informations de la vente
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Catégorie -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Type de vente <span class="text-red-500">*</span>
                            </label>
                            <select name="categorie" id="categorie" required onchange="mettreAJourResume()"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionnez un type de vente</option>
                                @foreach(\App\Models\VenteMoisson::CATEGORIES as $code => $libelle)
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
                                Choisissez le type de vente à organiser
                            </p>
                        </div>

                        <!-- Objectif financier -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Objectif financier (FCFA) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="cible" id="cible" required min="1" step="1"
                                placeholder="Ex: 300000"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('cible') }}" onchange="mettreAJourResume()">
                            @error('cible')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">
                                Montant à atteindre pour cette vente
                            </p>
                        </div>

                        <!-- Collecteur -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Responsable de la vente <span class="text-red-500">*</span>
                            </label>
                            <select name="collecter_par" id="collecter_par" required onchange="mettreAJourResume()"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionnez un responsable</option>
                                @foreach($collecteurs as $collecteur)
                                    <option value="{{ $collecteur->id }}" {{ old('collecter_par') === $collecteur->id ? 'selected' : '' }}>
                                        {{ $collecteur->nom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('collecter_par')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">
                                Personne responsable de cette vente
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Description de la vente
                            </label>
                            <textarea name="description" id="description" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Décrivez les produits ou services vendus, les modalités, etc...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-slate-500 mt-1">
                                Détails sur ce qui sera vendu (optionnel)
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
                        Configuration de la vente
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Statut -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">Statut initial de la vente</label>
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="1" {{ old('status', '1') === '1' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300" onchange="mettreAJourResume()">
                                    <span class="ml-3 text-sm text-slate-700">
                                        <span class="font-medium">Active</span>
                                        <span class="block text-xs text-slate-500">La vente est opérationnelle et peut commencer</span>
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="0" {{ old('status', '1') === '0' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300" onchange="mettreAJourResume()">
                                    <span class="ml-3 text-sm text-slate-700">
                                        <span class="font-medium">Inactive</span>
                                        <span class="block text-xs text-slate-500">La vente est créée mais pas encore démarrée</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé de la vente -->
            <div id="resume-vente" class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl shadow-lg border border-green-200/50 p-6 hidden">
                <h3 class="text-lg font-bold text-slate-800 mb-4">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    Résumé de la vente à créer
                </h3>
                <div id="resume-contenu" class="grid grid-cols-1 lg:grid-cols-2 gap-4 text-sm">
                    <!-- Le contenu sera mis à jour dynamiquement -->
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('private.moissons.ventes.index', $moisson) }}"
                    class="inline-flex items-center px-6 py-3 border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" id="btn-submit"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Créer la vente
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>


            // Mettre à jour le résumé de la vente
            function mettreAJourResume() {
                const categorie = document.getElementById('categorie');
                const cible = document.getElementById('cible');
                const collecteur = document.getElementById('collecter_par');
                const description = document.getElementById('description');
                const status = document.querySelector('input[name="status"]:checked');

                const resumeDiv = document.getElementById('resume-vente');
                const resumeContenu = document.getElementById('resume-contenu');

                if (categorie.value && cible.value && collecteur.value) {
                    const categorieText = categorie.options[categorie.selectedIndex].text;
                    const collecteurText = collecteur.options[collecteur.selectedIndex].text;
                    const cibleFormatted = parseInt(cible.value).toLocaleString('fr-FR');
                    const statusText = status?.value === '1' ? 'Active' : 'Inactive';

                    let html = `
                        <div>
                            <span class="text-slate-600">Type de vente:</span>
                            <span class="font-medium text-slate-800 ml-1">${categorieText}</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Objectif:</span>
                            <span class="font-medium text-slate-800 ml-1">${cibleFormatted} FCFA</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Responsable:</span>
                            <span class="font-medium text-slate-800 ml-1">${collecteurText}</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Statut:</span>
                            <span class="font-medium ${status?.value === '1' ? 'text-green-600' : 'text-red-600'} ml-1">${statusText}</span>
                        </div>
                    `;



                    if (description.value.trim()) {
                        html += `
                            <div class="lg:col-span-2">
                                <span class="text-slate-600">Description:</span>
                                <span class="font-medium text-slate-800 ml-1">${description.value.trim()}</span>
                            </div>
                        `;
                    }

                    resumeContenu.innerHTML = html;
                    resumeDiv.classList.remove('hidden');
                } else {
                    resumeDiv.classList.add('hidden');
                }
            }

            // Validation du formulaire
            document.getElementById('vente-form').addEventListener('submit', function(e) {
                const categorie = document.getElementById('categorie').value.trim();
                const cible = parseFloat(document.getElementById('cible').value || 0);
                const collecteur = document.getElementById('collecter_par').value;

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
                ['categorie', 'cible', 'collecter_par', 'description'].forEach(id => {
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
        </script>
    @endpush
@endsection
