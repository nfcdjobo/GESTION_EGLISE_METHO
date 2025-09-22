@extends('layouts.private.main')
@section('title', 'Modifier le FIMECO - ' . $fimeco['nom'])

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('private.fimecos.show', $fimeco['id']) }}"
                    class="inline-flex items-center justify-center w-10 h-10 bg-white/80 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                    <i class="fas fa-arrow-left text-slate-600"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Modifier le FIMECO
                    </h1>
                    <p class="text-slate-500 mt-1">{{ $fimeco['nom'] }}</p>
                </div>
            </div>
        </div>

        <!-- Informations actuelles -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-lg border border-blue-200 p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                État actuel du FIMECO
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-sm text-slate-600">Progression</div>
                    <div class="text-xl font-bold text-blue-600">{{ number_format($fimeco['progression'], 1) }}%</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-sm text-slate-600">Collecté</div>
                    <div class="text-xl font-bold text-green-600">{{ number_format($fimeco['montant_solde'], 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-sm text-slate-600">Souscriptions</div>
                    <div class="text-xl font-bold text-purple-600">{{ $fimeco['subscriptions']->count() }}</div>
                </div>
                <div class="bg-white rounded-lg p-4 shadow-sm">
                    <div class="text-sm text-slate-600">Statut</div>
                    <div class="text-xl font-bold text-orange-600 capitalize">{{ $fimeco['statut'] }}</div>
                </div>
            </div>
        </div>

        <!-- Formulaire de modification -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-edit text-orange-600 mr-2"></i>
                    Modifier les informations
                </h2>
                <p class="text-slate-500 text-sm mt-1">Attention : certaines modifications peuvent affecter les souscriptions existantes</p>
            </div>

            <form action="{{ route('private.fimecos.update', $fimeco['id']) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Informations de base -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Nom du FIMECO -->
                    <div class="lg:col-span-2">
                        <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">
                            Nom du FIMECO <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom', $fimeco['nom']) }}" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom') border-red-500 @enderror"
                            placeholder="Ex: Construction du nouveau sanctuaire">
                        @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror"
                            placeholder="Décrivez l'objectif et les détails du FIMECO...">{{ old('description', $fimeco['description']) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Responsable -->
                    <div>
                        <label for="responsable_id" class="block text-sm font-medium text-slate-700 mb-2">
                            Responsable
                        </label>
                        <select id="responsable_id" name="responsable_id"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('responsable_id') border-red-500 @enderror">
                            <option value="">Sélectionner un responsable</option>
                            @foreach($responsables as $responsable)
                                <option value="{{ $responsable->id }}"
                                    {{ old('responsable_id', $fimeco['responsable_id']) == $responsable->id ? 'selected' : '' }}>
                                    {{ $responsable->nom }} ({{ $responsable->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('responsable_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Statut -->
                     <div>
                                <label for="statut" class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                                <select id="statut" name="statut"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors  @error('statut') border-red-500 @enderror">
                                    <option value="active" {{ old('statut', $fimeco->statut) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('statut', $fimeco->statut) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="cloturee" {{ old('statut', $fimeco->statut) == 'cloturee' ? 'selected' : '' }}>Cloturée</option>
                                </select>

                                @error('statut')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @else
                                    <p class="mt-1 text-sm text-slate-500">Une FIMECO active peut recevoir des souscriptions</p>
                                @enderror
                            </div>

                </div>

                <!-- Objectifs financiers -->
                <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
                    <div>
                        <label for="cible" class="block text-sm font-medium text-slate-700 mb-2">
                            Montant cible (FCFA) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" id="cible" name="cible" value="{{ old('cible', $fimeco['cible']) }}" required min="1"
                                class="w-full px-4 py-3 pr-12 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('cible') border-red-500 @enderror"
                                placeholder="1000000">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <span class="text-slate-500 text-sm">FCFA</span>
                            </div>
                        </div>
                        @error('cible')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($fimeco['montant_solde'] > 0)
                            <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                                    <div class="text-sm text-yellow-800">
                                        <strong>Attention :</strong> Ce FIMECO a déjà collecté {{ number_format($fimeco['montant_solde'], 0, ',', ' ') }} FCFA.
                                        La nouvelle cible ne peut pas être inférieure à ce montant.
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Période -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Date de début -->
                    <div>
                        <label for="debut" class="block text-sm font-medium text-slate-700 mb-2">
                            Date de début <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="debut" name="debut" value="{{ old('debut', \Carbon\Carbon::parse($fimeco["debut"])->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('debut') border-red-500 @enderror">
                        @error('debut')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date de fin -->
                    <div>
                        <label for="fin" class="block text-sm font-medium text-slate-700 mb-2">
                            Date de fin <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="fin" name="fin" value="{{ old('fin', \Carbon\Carbon::parse($fimeco["fin"])->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fin') border-red-500 @enderror">
                        @error('fin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if($fimeco['subscriptions']->count() > 0)
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-amber-600 mr-2"></i>
                            <div class="text-sm text-amber-800">
                                <strong>Information :</strong> Ce FIMECO contient {{ $fimeco['subscriptions']->count() }} souscription(s).
                                Les modifications des dates ne doivent pas exclure les souscriptions existantes.
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Aperçu des modifications -->
                <div id="apercu-modifications" class="hidden bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                        <i class="fas fa-eye text-purple-600 mr-2"></i>
                        Aperçu des modifications
                    </h3>
                    <div id="modifications-details" class="space-y-2">
                        <!-- Les détails seront générés par JavaScript -->
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-200">
                    <button type="button" onclick="previsualiserModifications()"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-eye mr-2"></i>
                        Prévisualiser
                    </button>
                    <button type="submit" id="btn-submit"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Enregistrer les modifications
                    </button>
                    <a href="{{ route('private.fimecos.show', $fimeco['id']) }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Annuler
                    </a>
                </div>
            </form>
        </div>

        <!-- Informations de sécurité -->
        <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-2xl shadow-lg border border-red-200 p-6">
            <h3 class="text-lg font-semibold text-red-800 mb-4 flex items-center">
                <i class="fas fa-shield-alt text-red-600 mr-2"></i>
                Consignes de sécurité
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Modification de la cible</div>
                            <div class="text-sm text-slate-600">Ne peut pas être inférieure au montant déjà collecté</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-calendar-times text-red-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Modification des dates</div>
                            <div class="text-sm text-slate-600">Les souscriptions existantes doivent rester dans la période</div>
                        </div>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-start">
                        <i class="fas fa-user-times text-red-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Changement de responsable</div>
                            <div class="text-sm text-slate-600">Notifiera automatiquement l'ancien et le nouveau responsable</div>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <i class="fas fa-lock text-red-600 mt-1 mr-3"></i>
                        <div>
                            <div class="font-medium text-slate-800">Clôture du FIMECO</div>
                            <div class="text-sm text-slate-600">Vérifiera qu'il n'y a pas de paiements en attente</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Données originales pour comparaison
            const originalData = {
                nom: '{{ addslashes($fimeco['nom']) }}',
                description: '{{ addslashes($fimeco['description'] ?? '') }}',
                cible: {{ $fimeco['cible'] }},
                debut: '{{ $fimeco['debut'] }}',
                fin: '{{ $fimeco['fin'] }}',
                responsable_id: '{{ $fimeco['responsable_id'] ?? '' }}',
                statut: '{{ $fimeco['statut'] }}'
            };

            function previsualiserModifications() {
                const currentData = {
                    nom: document.getElementById('nom').value,
                    description: document.getElementById('description').value,
                    cible: parseFloat(document.getElementById('cible').value) || 0,
                    debut: document.getElementById('debut').value,
                    fin: document.getElementById('fin').value,
                    responsable_id: document.getElementById('responsable_id').value,
                    statut: document.getElementById('statut').value
                };

                const modifications = [];
                const detailsContainer = document.getElementById('modifications-details');

                // Comparer les données
                Object.keys(originalData).forEach(key => {
                    if (originalData[key] != currentData[key]) {
                        let oldValue = originalData[key];
                        let newValue = currentData[key];

                        // Formatage spécial pour certains champs
                        if (key === 'cible') {
                            oldValue = new Intl.NumberFormat('fr-FR').format(oldValue) + ' FCFA';
                            newValue = new Intl.NumberFormat('fr-FR').format(newValue) + ' FCFA';
                        } else if (key === 'debut' || key === 'fin') {
                            oldValue = new Date(oldValue).toLocaleDateString('fr-FR');
                            newValue = new Date(newValue).toLocaleDateString('fr-FR');
                        } else if (key === 'responsable_id') {
                            const oldSelect = document.querySelector(`#responsable_id option[value="${oldValue}"]`);
                            const newSelect = document.querySelector(`#responsable_id option[value="${newValue}"]`);
                            oldValue = oldSelect ? oldSelect.textContent : 'Aucun';
                            newValue = newSelect ? newSelect.textContent : 'Aucun';
                        }

                        modifications.push({
                            field: key,
                            label: getFieldLabel(key),
                            oldValue: oldValue || 'Non défini',
                            newValue: newValue || 'Non défini'
                        });
                    }
                });

                if (modifications.length === 0) {
                    detailsContainer.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle text-blue-600 text-2xl mb-2"></i>
                            <p class="text-slate-600">Aucune modification détectée</p>
                        </div>
                    `;
                } else {
                    detailsContainer.innerHTML = modifications.map(mod => `
                        <div class="flex justify-between items-center p-3 bg-white rounded-lg border">
                            <div class="font-medium text-slate-800">${mod.label}</div>
                            <div class="text-right">
                                <div class="text-sm text-red-600 line-through">${mod.oldValue}</div>
                                <div class="text-sm text-green-600 font-medium">${mod.newValue}</div>
                            </div>
                        </div>
                    `).join('');
                }

                document.getElementById('apercu-modifications').classList.remove('hidden');
            }

            function getFieldLabel(field) {
                const labels = {
                    'nom': 'Nom',
                    'description': 'Description',
                    'cible': 'Cible',
                    'debut': 'Date de début',
                    'fin': 'Date de fin',
                    'responsable_id': 'Responsable',
                    'statut': 'Statut'
                };
                return labels[field] || field;
            }

            // Validation en temps réel
            document.addEventListener('DOMContentLoaded', function() {
                const cibleInput = document.getElementById('cible');
                const montantSolde = {{ $fimeco['montant_solde'] }};

                cibleInput.addEventListener('input', function() {
                    const newCible = parseFloat(this.value) || 0;
                    if (newCible < montantSolde) {
                        this.setCustomValidity(`La cible ne peut pas être inférieure au montant déjà collecté (${new Intl.NumberFormat('fr-FR').format(montantSolde)} FCFA)`);
                    } else {
                        this.setCustomValidity('');
                    }
                });

                // Animation d'entrée
                const form = document.querySelector('form');
                form.style.opacity = '0';
                // form.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    form.style.transition = 'all 0.5s ease';
                    form.style.opacity = '1';
                    // form.style.transform = 'translateY(0)';
                }, 100);
            });
        </script>
    @endpush
@endsection
