@extends('layouts.private.main')
@section('title', 'Modifier la Vente - ' . $venteMoisson->categorie_libelle)

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
                <a href="{{ route('private.moissons.ventes.show', [$moisson, $venteMoisson]) }}" class="hover:text-blue-600 transition-colors">
                    {{ $venteMoisson->categorie_libelle }}
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium">Modifier</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Modifier la vente
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Modification de la vente "{{ $venteMoisson->categorie_libelle }}" pour la moisson "{{ $moisson->theme }}"
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('private.moissons.ventes.show', [$moisson, $venteMoisson]) }}"
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
                            <span class="text-slate-600">Objectif global:</span>
                            <span class="font-medium text-slate-800 ml-1">{{ number_format($moisson->cible, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Collecté global:</span>
                            <span class="font-medium text-green-600 ml-1">{{ number_format($moisson->montant_solde, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Progression globale:</span>
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

        <!-- État actuel de la vente -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl shadow-lg border border-green-200/50 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                État actuel de la vente
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-slate-600">Type:</span>
                    <span class="font-medium text-slate-800 ml-1">{{ $venteMoisson->categorie_libelle }}</span>
                </div>
                <div>
                    <span class="text-slate-600">Objectif actuel:</span>
                    <span class="font-medium text-slate-800 ml-1">{{ number_format($venteMoisson->cible, 0, ',', ' ') }} FCFA</span>
                </div>
                <div>
                    <span class="text-slate-600">Montant vendu:</span>
                    <span class="font-medium text-green-600 ml-1">{{ number_format($venteMoisson->montant_solde, 0, ',', ' ') }} FCFA</span>
                </div>
                <div>
                    <span class="text-slate-600">Progression:</span>
                    <span class="font-medium
                        @if($venteMoisson->pourcentage_realise >= 100) text-green-600
                        @elseif($venteMoisson->pourcentage_realise >= 70) text-blue-600
                        @elseif($venteMoisson->pourcentage_realise >= 50) text-yellow-600
                        @else text-red-600
                        @endif ml-1">{{ $venteMoisson->pourcentage_realise }}%</span>
                </div>
            </div>


        </div>

        <!-- Formulaire de modification -->
        <form id="vente-form" action="{{ route('private.moissons.ventes.update', [$moisson, $venteMoisson]) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-edit text-blue-600 mr-2"></i>
                        Informations de la vente
                    </h3>
                    <p class="text-sm text-slate-600 mt-1">Modifiez les informations de base de la vente</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Catégorie (non modifiable mais affichée) -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Type de vente
                            </label>
                            <div class="w-full px-4 py-3 border border-slate-200 bg-slate-50 rounded-xl text-slate-600">
                                {{ $venteMoisson->categorie_libelle }}
                                <span class="text-xs text-slate-500 block mt-1">Le type de vente ne peut pas être modifié après création</span>
                            </div>
                            <!-- Champ caché pour maintenir la catégorie -->
                            <input type="hidden" name="categorie" value="{{ $venteMoisson->categorie }}">
                        </div>

                        <!-- Objectif financier -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Objectif financier (FCFA) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="cible" id="cible" required min="1" step="1"
                                placeholder="Ex: 300000"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('cible', $venteMoisson->cible) }}" onchange="calculerComparaison()">
                            @error('cible')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <div class="text-xs text-slate-500 mt-1">
                                <p>Montant à atteindre pour cette vente</p>
                                @if($venteMoisson->montant_solde > 0)
                                    <p class="text-amber-600">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Attention: Cette vente a déjà {{ number_format($venteMoisson->montant_solde, 0, ',', ' ') }} FCFA de vendus
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Responsable -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Responsable de la vente <span class="text-red-500">*</span>
                            </label>
                            <select name="collecter_par" id="collecter_par" required onchange="mettreAJourResume()"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionnez un responsable</option>
                                @foreach($collecteurs as $collecteur)
                                    <option value="{{ $collecteur->id }}"
                                        {{ (old('collecter_par', $venteMoisson->collecter_par) == $collecteur->id) ? 'selected' : '' }}>
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
                                placeholder="Décrivez les produits ou services vendus, les modalités, etc..." onchange="mettreAJourResume()">{{ old('description', $venteMoisson->description) }}</textarea>
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


            <!-- Configuration de la vente -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cog text-blue-600 mr-2"></i>
                        Configuration de la vente
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Statut -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-3">Statut de la vente</label>
                            <div class="flex items-center space-x-6">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="1"
                                        {{ old('status', $venteMoisson->status) == '1' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300" onchange="mettreAJourResume()">
                                    <span class="ml-3 text-sm text-slate-700">
                                        <span class="font-medium">Active</span>
                                        <span class="block text-xs text-slate-500">La vente est opérationnelle</span>
                                    </span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="0"
                                        {{ old('status', $venteMoisson->status) == '0' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300" onchange="mettreAJourResume()">
                                    <span class="ml-3 text-sm text-slate-700">
                                        <span class="font-medium">Inactive</span>
                                        <span class="block text-xs text-slate-500">La vente est temporairement désactivée</span>
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Notes de modification -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Notes de modification
                            </label>
                            <textarea name="notes_modification" id="notes_modification" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Précisez les raisons de cette modification...">{{ old('notes_modification') }}</textarea>
                            <p class="text-xs text-slate-500 mt-1">
                                Ces notes seront ajoutées à l'historique des modifications (optionnel)
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Résumé des modifications -->
            <div id="resume-modifications" class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-lg border border-blue-200/50 p-6 hidden">
                <h3 class="text-lg font-bold text-slate-800 mb-4">
                    <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                    Résumé des modifications
                </h3>
                <div id="resume-contenu" class="space-y-2 text-sm">
                    <!-- Le contenu sera mis à jour dynamiquement -->
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('private.moissons.ventes.show', [$moisson, $venteMoisson]) }}"
                    class="inline-flex items-center px-6 py-3 border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Annuler
                </a>
                <button type="submit" id="btn-submit"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const montantActuel = {{ $venteMoisson->montant_solde }};
            const cibleActuelle = {{ $venteMoisson->cible }};

           

            // Calculer la comparaison avec l'objectif actuel
            function calculerComparaison() {
                mettreAJourResume();
            }

            // Mettre à jour le résumé des modifications
            function mettreAJourResume() {
                const cible = parseFloat(document.getElementById('cible').value) || 0;
                const collecteur = document.getElementById('collecter_par');

                const description = document.getElementById('description').value.trim();
                const status = document.querySelector('input[name="status"]:checked');
                const notes = document.getElementById('notes_modification').value.trim();

                const resumeDiv = document.getElementById('resume-modifications');
                const resumeContenu = document.getElementById('resume-contenu');

                let modifications = [];

                // Vérifier les changements
                if (cible !== cibleActuelle) {
                    modifications.push(`Objectif: ${cibleActuelle.toLocaleString('fr-FR')} → ${cible.toLocaleString('fr-FR')} FCFA`);
                }

                if (collecteur.value !== '{{ $venteMoisson->collecter_par }}') {
                    const nouveauCollecteur = collecteur.options[collecteur.selectedIndex].text;
                    modifications.push(`Responsable: {{ $venteMoisson->collecteur?->nom ?? 'Non défini' }} → ${nouveauCollecteur}`);
                }





                if (description !== '{{ $venteMoisson->description ?? '' }}') {
                    modifications.push(`Description modifiée`);
                }

                const statusActuel = {{ $venteMoisson->status ? 'true' : 'false' }};
                const nouveauStatus = status?.value === '1';
                if (nouveauStatus !== statusActuel) {
                    modifications.push(`Statut: ${statusActuel ? 'Active' : 'Inactive'} → ${nouveauStatus ? 'Active' : 'Inactive'}`);
                }

                if (notes) {
                    modifications.push(`Notes: ${notes}`);
                }

                if (modifications.length > 0) {
                    let html = modifications.map(mod => `<div class="flex items-center"><i class="fas fa-arrow-right text-blue-500 mr-2"></i>${mod}</div>`).join('');
                    resumeContenu.innerHTML = html;
                    resumeDiv.classList.remove('hidden');
                } else {
                    resumeDiv.classList.add('hidden');
                }
            }

            // Validation du formulaire
            document.getElementById('vente-form').addEventListener('submit', function(e) {
                const cible = parseFloat(document.getElementById('cible').value || 0);
                const collecteur = document.getElementById('collecter_par').value;

                // Validations de base
                if (cible <= 0) {
                    e.preventDefault();
                    alert('L\'objectif financier doit être supérieur à 0.');
                    document.getElementById('cible').focus();
                    return;
                }

                if (!collecteur) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un responsable.');
                    document.getElementById('collecter_par').focus();
                    return;
                }



                // Avertissement si objectif inférieur au montant déjà vendu
                if (cible < montantActuel) {
                    const confirmer = confirm(`L'objectif (${cible.toLocaleString('fr-FR')} FCFA) est inférieur au montant déjà vendu (${montantActuel.toLocaleString('fr-FR')} FCFA). Voulez-vous continuer ?`);
                    if (!confirmer) {
                        document.getElementById('cible').focus();
                        return;
                    }
                }



                // Désactiver le bouton de soumission
                const btnSubmit = document.getElementById('btn-submit');
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Enregistrement en cours...';
            });

            // Écouteurs d'événements pour mettre à jour le résumé
            document.addEventListener('DOMContentLoaded', function() {
                ['cible', 'collecter_par', 'description', 'notes_modification'].forEach(id => {
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
