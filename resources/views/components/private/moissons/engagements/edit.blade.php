@extends('layouts.private.main')
@section('title', 'Modifier l\'Engagement - ' . $engagementMoisson->nom_donateur)

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
                <a href="{{ route('private.moissons.engagements.index', $moisson) }}" class="hover:text-blue-600 transition-colors">
                    Engagements
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('private.moissons.engagements.show', [$moisson, $engagementMoisson]) }}" class="hover:text-blue-600 transition-colors">
                    {{ Str::limit($engagementMoisson->nom_donateur, 20) }}
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium">Modifier</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Modifier l'engagement
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Modification de l'engagement de "{{ $engagementMoisson->nom_donateur }}"
                    </p>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('private.moissons.engagements.show', [$moisson, $engagementMoisson]) }}"
                        class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Informations actuelles de l'engagement -->
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl shadow-lg border border-purple-200/50 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Engagement actuel</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-slate-600">Montant:</span>
                            <span class="font-medium text-slate-800 ml-1">{{ number_format($engagementMoisson->cible, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Versé:</span>
                            <span class="font-medium text-green-600 ml-1">{{ number_format($engagementMoisson->montant_solde, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Reste:</span>
                            <span class="font-medium text-{{ $engagementMoisson->reste > 0 ? 'red' : 'green' }}-600 ml-1">
                                {{ number_format($engagementMoisson->reste, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-600">Progression:</span>
                            <span class="font-medium text-blue-600 ml-1">{{ $engagementMoisson->pourcentage_realise }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de modification -->
        <form id="engagement-form" action="{{ route('private.moissons.engagements.update', [$moisson, $engagementMoisson]) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Informations de base -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations de base
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Type d'engagement (lecture seule) -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Type d'engagement</label>
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                <div class="flex items-center">
                                    <i class="fas fa-{{ $engagementMoisson->categorie === 'entite_physique' ? 'user' : 'building' }} text-blue-600 mr-3"></i>
                                    <span class="font-medium text-slate-900">{{ $engagementMoisson->categorie_libelle }}</span>
                                    <span class="ml-2 text-xs text-slate-500">(Non modifiable)</span>
                                </div>
                            </div>
                            <input type="hidden" name="categorie" value="{{ $engagementMoisson->categorie }}">
                        </div>

                        <!-- Personne physique ou Entité morale (selon le type) -->
                        @if($engagementMoisson->categorie === 'entite_physique')
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Personne <span class="text-red-500">*</span>
                                </label>
                                <select name="donateur_id" id="donateur_id" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Sélectionnez une personne</option>
                                    @foreach($donateurs as $donateur)
                                        <option value="{{ $donateur->id }}"
                                            {{ (old('donateur_id', $engagementMoisson->donateur_id) == $donateur->id) ? 'selected' : '' }}>
                                            {{ $donateur->nom_complet }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('donateur_id')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Nom de l'entité <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nom_entite" id="nom_entite" required
                                    placeholder="Ex: Entreprise ABC, Association XYZ..."
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    value="{{ old('nom_entite', $engagementMoisson->nom_entite) }}">
                                @error('nom_entite')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <!-- Montant de l'engagement -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Montant de l'engagement (FCFA) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="cible" id="cible" required min="1" step="1"
                                placeholder="Ex: 100000"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('cible', $engagementMoisson->cible) }}">
                            @error('cible')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            @if($engagementMoisson->montant_solde > 0)
                                <p class="text-xs text-amber-600 mt-1">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Attention: {{ number_format($engagementMoisson->montant_solde, 0, ',', ' ') }} FCFA ont déjà été versés
                                </p>
                            @endif
                        </div>

                        <!-- Collecteur responsable -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Collecteur responsable <span class="text-red-500">*</span>
                            </label>
                            <select name="collecter_par" id="collecter_par" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Sélectionnez un collecteur</option>
                                @foreach($collecteurs as $collecteur)
                                    <option value="{{ $collecteur->id }}"
                                        {{ (old('collecter_par', $engagementMoisson->collecter_par) == $collecteur->id) ? 'selected' : '' }}>
                                        {{ $collecteur->nom_complet }}
                                    </option>
                                @endforeach
                            </select>
                            @error('collecter_par')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de contact -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-address-book text-green-600 mr-2"></i>
                        Informations de contact
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Téléphone -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Téléphone</label>
                            <input type="tel" name="telephone" id="telephone"
                                placeholder="+225 XX XX XX XX XX"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('telephone', $engagementMoisson->telephone) }}">
                            @error('telephone')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                            <input type="email" name="email" id="email"
                                placeholder="email@exemple.com"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('email', $engagementMoisson->email) }}">
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Adresse -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Adresse</label>
                            <textarea name="adresse" id="adresse" rows="3"
                                placeholder="Adresse complète..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('adresse', $engagementMoisson->adresse) }}</textarea>
                            @error('adresse')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Planification et suivi -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                        Planification et suivi
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Date d'échéance -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date d'échéance</label>
                            <input type="date" name="date_echeance" id="date_echeance"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('date_echeance', $engagementMoisson->date_echeance?->format('Y-m-d')) }}">
                            @error('date_echeance')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date de rappel -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date de rappel</label>
                            <input type="date" name="date_rappel" id="date_rappel"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                value="{{ old('date_rappel', $engagementMoisson->date_rappel?->format('Y-m-d')) }}">
                            @error('date_rappel')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Description de l'engagement</label>
                            <textarea name="description" id="description" rows="4"
                                placeholder="Détails de l'engagement, conditions particulières..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">{{ old('description', $engagementMoisson->description) }}</textarea>
                            @error('description')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="1"
                                        {{ old('status', $engagementMoisson->status) == '1' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <span class="ml-2 text-sm text-slate-700">Actif</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="0"
                                        {{ old('status', $engagementMoisson->status) == '0' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300">
                                    <span class="ml-2 text-sm text-slate-700">Inactif</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('private.moissons.engagements.show', [$moisson, $engagementMoisson]) }}"
                    class="inline-flex items-center px-6 py-3 border border-slate-300 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Annuler
                </a>
                <button type="submit"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Validation des dates
            function validateDates() {
                const dateEcheance = document.getElementById('date_echeance').value;
                const dateRappel = document.getElementById('date_rappel').value;

                if (dateEcheance && dateRappel) {
                    if (new Date(dateRappel) >= new Date(dateEcheance)) {
                        alert('La date de rappel doit être antérieure à la date d\'échéance.');
                        return false;
                    }
                }
                return true;
            }

            // Validation du formulaire
            document.getElementById('engagement-form').addEventListener('submit', function(e) {
                // Vérifier les champs obligatoires selon le type
                const categorie = '{{ $engagementMoisson->categorie }}';

                if (categorie === 'entite_physique') {
                    const donateur = document.getElementById('donateur_id').value;
                    if (!donateur) {
                        e.preventDefault();
                        alert('Veuillez sélectionner une personne pour l\'engagement.');
                        return;
                    }
                } else if (categorie === 'entite_morale') {
                    const entite = document.getElementById('nom_entite').value.trim();
                    if (!entite) {
                        e.preventDefault();
                        alert('Veuillez saisir le nom de l\'entité.');
                        return;
                    }
                }

                // Vérifier le montant
                const cible = parseInt(document.getElementById('cible').value);
                const montantVerse = {{ $engagementMoisson->montant_solde }};

                if (!cible || cible < 1) {
                    e.preventDefault();
                    alert('Le montant de l\'engagement doit être supérieur à 0.');
                    return;
                }

                if (cible < montantVerse) {
                    if (!confirm(`Le nouveau montant (${cible.toLocaleString()} FCFA) est inférieur au montant déjà versé (${montantVerse.toLocaleString()} FCFA). Voulez-vous continuer ?`)) {
                        e.preventDefault();
                        return;
                    }
                }

                // Vérifier le collecteur
                const collecteur = document.getElementById('collecter_par').value;
                if (!collecteur) {
                    e.preventDefault();
                    alert('Veuillez sélectionner un collecteur responsable.');
                    return;
                }

                // Validation des dates
                if (!validateDates()) {
                    e.preventDefault();
                    return;
                }
            });

            // Gérer les contraintes de dates
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date();
                const dateEcheanceInput = document.getElementById('date_echeance');
                const dateRappelInput = document.getElementById('date_rappel');

                // Date minimale = aujourd'hui
                const minDate = today.toISOString().split('T')[0];
                dateEcheanceInput.min = minDate;
                dateRappelInput.min = minDate;

                // Quand l'échéance change, ajuster la date max du rappel
                dateEcheanceInput.addEventListener('change', function() {
                    if (this.value) {
                        const echeance = new Date(this.value);
                        echeance.setDate(echeance.getDate() - 1);
                        dateRappelInput.max = echeance.toISOString().split('T')[0];
                    } else {
                        dateRappelInput.removeAttribute('max');
                    }
                });

                // Initialiser la contrainte si une échéance existe
                if (dateEcheanceInput.value) {
                    const echeance = new Date(dateEcheanceInput.value);
                    echeance.setDate(echeance.getDate() - 1);
                    dateRappelInput.max = echeance.toISOString().split('T')[0];
                }

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
