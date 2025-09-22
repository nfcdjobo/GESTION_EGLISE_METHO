{{--
    Composant partiel pour les filtres de recherche des cultes

    Props attendues:
    - $programmes: Collection des programmes
    - $pasteurs: Collection des pasteurs (optionnel)
    - $users: Collection des membres (optionnel)
    - $showAdvanced: Boolean pour afficher les filtres avancés (default: false)
    - $action: URL d'action du formulaire (default: route actuelle)
    - $compact: Boolean pour un affichage compact (default: false)
--}}

@php
    $showAdvanced = $showAdvanced ?? false;
    $action = $action ?? request()->url();
    $compact = $compact ?? false;
    $programmes = $programmes ?? collect();
    $pasteurs = $pasteurs ?? collect();
    $users = $users ?? collect();
@endphp

<div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
    <div class="p-6 border-b border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-filter text-blue-600 mr-2"></i>
                Filtres et Recherche
            </h2>
            <div class="flex items-center space-x-2">
                @if($showAdvanced)
                    <button type="button" id="toggleAdvancedFilters"
                        onclick="toggleAdvancedFilters()"
                        class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                        <i class="fas fa-cog mr-2"></i>
                        <span id="advancedToggleText">Filtres avancés</span>
                    </button>
                @endif

                <button type="button" onclick="resetFilters()"
                    class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200">
                    <i class="fas fa-refresh mr-2"></i> Réinitialiser
                </button>
            </div>
        </div>
    </div>

    <div class="p-6">
        <form method="GET" action="{{ $action }}" id="filtersForm" class="space-y-6">
            <!-- Filtres de base -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ $compact ? '4' : '6' }} gap-4">
                <!-- Recherche textuelle -->
                <div class="{{ $compact ? '' : 'lg:col-span-2' }}">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Titre, lieu, message..."
                            class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <!-- Statut -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="statut" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        @foreach(\App\Models\Culte::STATUT as $key => $label)
                            <option value="{{ $key }}" {{ request('statut') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Type de culte -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de culte</label>
                    <select name="type_culte" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        @foreach(\App\Models\Culte::TYPE_CULTE as $key => $label)
                            <option value="{{ $key }}" {{ request('type_culte') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                @if($programmes->count() > 0)
                    <!-- Programme -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Programme</label>
                        <select name="programme_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les programmes</option>
                            @foreach($programmes as $programme)
                                <option value="{{ $programme->id }}" {{ request('programme_id') == $programme->id ? 'selected' : '' }}>{{ $programme->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if(!$compact)
                    @if($pasteurs->count() > 0)
                        <!-- Pasteur -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Pasteur</label>
                            <select name="pasteur_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les pasteurs</option>
                                @foreach($pasteurs as $pasteur)
                                    <option value="{{ $pasteur->id }}" {{ request('pasteur_id') == $pasteur->id ? 'selected' : '' }}>{{ $pasteur->nom }} {{ $pasteur->prenom }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Catégorie -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                        <select name="categorie" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Toutes les catégories</option>
                            @foreach(\App\Models\Culte::CATEGORIE as $key => $label)
                                <option value="{{ $key }}" {{ request('categorie') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>

            <!-- Filtres de dates -->
            <div class="grid grid-cols-1 md:grid-cols-{{ $compact ? '2' : '3' }} gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                @if(!$compact)
                    <div class="flex items-end">
                        <div class="w-full space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="a_venir" value="1" {{ request('a_venir') ? 'checked' : '' }}
                                    id="a_venir" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="a_venir" class="ml-2 text-sm text-slate-700">Cultes à venir</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="publics_seulement" value="1" {{ request('publics_seulement') ? 'checked' : '' }}
                                    id="publics_seulement" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="publics_seulement" class="ml-2 text-sm text-slate-700">Publics seulement</label>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            @if($showAdvanced)
                <!-- Filtres avancés (masqués par défaut) -->
                <div id="advancedFilters" class="space-y-4 {{ request()->hasAny(['dirigeant_louange_id', 'responsable_culte_id', 'min_participants', 'max_participants', 'note_min', 'diffusion_en_ligne', 'avec_offrandes']) ? '' : 'hidden' }}">
                    <div class="border-t border-slate-200 pt-4">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">Filtres Avancés</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @if($users->count() > 0)
                                <!-- Dirigeant de louange -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Dirigeant de louange</label>
                                    <select name="dirigeant_louange_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Tous</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('dirigeant_louange_id') == $user->id ? 'selected' : '' }}>{{ $user->nom }} {{ $user->prenom }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Responsable du culte -->
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Responsable du culte</label>
                                    <select name="responsable_culte_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Tous</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('responsable_culte_id') == $user->id ? 'selected' : '' }}>{{ $user->nom }} {{ $user->prenom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <!-- Nombre de participants min -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Participants min</label>
                                <input type="number" name="min_participants" value="{{ request('min_participants') }}" min="0"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>

                            <!-- Nombre de participants max -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Participants max</label>
                                <input type="number" name="max_participants" value="{{ request('max_participants') }}" min="0"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>

                            <!-- Note minimale -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Note min</label>
                                <select name="note_min" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Toutes</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ request('note_min') == $i ? 'selected' : '' }}>{{ $i }}/10 et plus</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Atmosphère -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Atmosphère</label>
                                <select name="atmosphere" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Toutes</option>
                                    @foreach(\App\Models\Culte::ATMOSPHERE as $key => $label)
                                        <option value="{{ $key }}" {{ request('atmosphere') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Montant offrande min -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Offrande min (FCFA)</label>
                                <input type="number" name="offrande_min" value="{{ request('offrande_min') }}" min="0" step="0.01"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>

                            <!-- Lieu -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Lieu</label>
                                <input type="text" name="lieu" value="{{ request('lieu') }}" placeholder="Nom du lieu"
                                    class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                        </div>

                        <!-- Options booléennes -->
                        <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="diffusion_en_ligne" value="1" {{ request('diffusion_en_ligne') ? 'checked' : '' }}
                                    id="diffusion_en_ligne" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="diffusion_en_ligne" class="ml-2 text-sm text-slate-700">Diffusion en ligne</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="avec_offrandes" value="1" {{ request('avec_offrandes') ? 'checked' : '' }}
                                    id="avec_offrandes" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="avec_offrandes" class="ml-2 text-sm text-slate-700">Avec offrandes</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="avec_conversions" value="1" {{ request('avec_conversions') ? 'checked' : '' }}
                                    id="avec_conversions" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="avec_conversions" class="ml-2 text-sm text-slate-700">Avec conversions</label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="avec_baptemes" value="1" {{ request('avec_baptemes') ? 'checked' : '' }}
                                    id="avec_baptemes" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="avec_baptemes" class="ml-2 text-sm text-slate-700">Avec baptêmes</label>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Boutons d'action -->
            <div class="flex flex-col sm:flex-row gap-4 {{ $compact ? 'justify-center' : 'justify-between' }} pt-4 border-t border-slate-200">
                <div class="flex gap-2">
                    <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="{{ $action }}" class="inline-flex items-center px-6 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                </div>

                @if(!$compact)
                    <!-- Raccourcis de dates -->
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setDateRange('today')" class="px-3 py-1 text-xs bg-cyan-100 text-cyan-800 rounded-lg hover:bg-cyan-200 transition-colors">
                            Aujourd'hui
                        </button>
                        <button type="button" onclick="setDateRange('week')" class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors">
                            Cette semaine
                        </button>
                        <button type="button" onclick="setDateRange('month')" class="px-3 py-1 text-xs bg-purple-100 text-purple-800 rounded-lg hover:bg-purple-200 transition-colors">
                            Ce mois
                        </button>
                        <button type="button" onclick="setDateRange('last_month')" class="px-3 py-1 text-xs bg-amber-100 text-amber-800 rounded-lg hover:bg-amber-200 transition-colors">
                            Mois dernier
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Toggle des filtres avancés
function toggleAdvancedFilters() {
    const advancedFilters = document.getElementById('advancedFilters');
    const toggleText = document.getElementById('advancedToggleText');

    if (advancedFilters.classList.contains('hidden')) {
        advancedFilters.classList.remove('hidden');
        toggleText.textContent = 'Masquer filtres avancés';
    } else {
        advancedFilters.classList.add('hidden');
        toggleText.textContent = 'Filtres avancés';
    }
}

// Réinitialiser tous les filtres
function resetFilters() {
    const form = document.getElementById('filtersForm');
    const inputs = form.querySelectorAll('input, select');

    inputs.forEach(input => {
        if (input.type === 'checkbox') {
            input.checked = false;
        } else {
            input.value = '';
        }
    });

    // Masquer les filtres avancés
    const advancedFilters = document.getElementById('advancedFilters');
    if (advancedFilters) {
        advancedFilters.classList.add('hidden');
        const toggleText = document.getElementById('advancedToggleText');
        if (toggleText) {
            toggleText.textContent = 'Filtres avancés';
        }
    }
}

// Définir une plage de dates
function setDateRange(range) {
    const today = new Date();
    const debutInput = document.querySelector('input[name="date_debut"]');
    const finInput = document.querySelector('input[name="date_fin"]');

    let dateDebut, dateFin;

    switch(range) {
        case 'today':
            dateDebut = dateFin = today;
            break;
        case 'week':
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay() + 1); // Lundi
            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6); // Dimanche
            dateDebut = startOfWeek;
            dateFin = endOfWeek;
            break;
        case 'month':
            dateDebut = new Date(today.getFullYear(), today.getMonth(), 1);
            dateFin = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            break;
        case 'last_month':
            dateDebut = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            dateFin = new Date(today.getFullYear(), today.getMonth(), 0);
            break;
        default:
            return;
    }

    if (debutInput) debutInput.value = dateDebut.toISOString().split('T')[0];
    if (finInput) dateFin.value = dateFin.toISOString().split('T')[0];
}

// Soumission automatique du formulaire (optionnel)
function setupAutoSubmit() {
    const form = document.getElementById('filtersForm');
    const selects = form.querySelectorAll('select');
    const checkboxes = form.querySelectorAll('input[type="checkbox"]');

    // Auto-submit sur changement des selects
    selects.forEach(select => {
        select.addEventListener('change', function() {
            if (this.dataset.autoSubmit !== 'false') {
                setTimeout(() => form.submit(), 100);
            }
        });
    });

    // Auto-submit sur changement des checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.dataset.autoSubmit !== 'false') {
                setTimeout(() => form.submit(), 100);
            }
        });
    });
}

// Sauvegarde des filtres dans le localStorage
function saveFiltersToStorage() {
    const form = document.getElementById('filtersForm');
    const formData = new FormData(form);
    const filters = {};

    for (const [key, value] of formData.entries()) {
        if (value) {
            filters[key] = value;
        }
    }

    localStorage.setItem('cultes_filters', JSON.stringify(filters));
}

// Restauration des filtres depuis le localStorage
function loadFiltersFromStorage() {
    const saved = localStorage.getItem('cultes_filters');
    if (!saved) return;

    try {
        const filters = JSON.parse(saved);
        const form = document.getElementById('filtersForm');

        Object.entries(filters).forEach(([key, value]) => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'checkbox') {
                    input.checked = true;
                } else {
                    input.value = value;
                }
            }
        });
    } catch (e) {
        console.error('Erreur lors du chargement des filtres:', e);
    }
}

// Validation des dates
function validateDates() {
    const dateDebut = document.querySelector('input[name="date_debut"]').value;
    const dateFin = document.querySelector('input[name="date_fin"]').value;

    if (dateDebut && dateFin && new Date(dateDebut) > new Date(dateFin)) {
        alert('La date de début ne peut pas être postérieure à la date de fin.');
        return false;
    }

    return true;
}

// Validation des participants
function validateParticipants() {
    const minParticipants = document.querySelector('input[name="min_participants"]')?.value;
    const maxParticipants = document.querySelector('input[name="max_participants"]')?.value;

    if (minParticipants && maxParticipants && parseInt(minParticipants) > parseInt(maxParticipants)) {
        alert('Le nombre minimum de participants ne peut pas être supérieur au maximum.');
        return false;
    }

    return true;
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Charger les filtres sauvegardés si aucun filtre n'est actuellement appliqué
    if (!window.location.search) {
        loadFiltersFromStorage();
    }

    // Configuration de l'auto-submit (commenté par défaut)
    // setupAutoSubmit();

    // Validation du formulaire
    const form = document.getElementById('filtersForm');
    form.addEventListener('submit', function(e) {
        if (!validateDates() || !validateParticipants()) {
            e.preventDefault();
            return false;
        }

        // Sauvegarder les filtres
        saveFiltersToStorage();
    });

    // Gestion des raccourcis clavier
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey || e.metaKey) {
            switch(e.key) {
                case 'f':
                    e.preventDefault();
                    document.querySelector('input[name="search"]').focus();
                    break;
                case 'Enter':
                    e.preventDefault();
                    form.submit();
                    break;
            }
        }
    });
});
</script>
@endpush
