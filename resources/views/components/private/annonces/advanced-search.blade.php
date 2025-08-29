{{-- components/private/annonces/advanced-search.blade.php --}}
@props([
    'action' => '',
    'method' => 'GET',
    'showTitle' => true,
    'compact' => false
])

<div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
    @if($showTitle)
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-search text-blue-600 mr-2"></i>
                Recherche Avancée
            </h2>
            <p class="text-slate-500 mt-1">Filtrez et trouvez vos annonces rapidement</p>
        </div>
    @endif

    <div class="p-6">
        <form method="{{ $method }}" action="{{ $action }}" id="advanced-search-form">
            @if($method !== 'GET')
                @csrf
            @endif

            <div class="grid grid-cols-1 {{ $compact ? 'lg:grid-cols-3' : 'lg:grid-cols-4' }} gap-6">
                <!-- Recherche textuelle -->
                <div class="{{ $compact ? 'lg:col-span-1' : 'lg:col-span-2' }}">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        <i class="fas fa-search mr-1"></i>
                        Recherche textuelle
                    </label>
                    <div class="relative">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Titre, contenu, lieu..."
                               class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                        <button type="button"
                                onclick="clearSearchField()"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-600 hidden"
                                id="clear-search">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Statut -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        <i class="fas fa-flag mr-1"></i>
                        Statut
                    </label>
                    <select name="statut" class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>📝 Brouillon</option>
                        <option value="publiee" {{ request('statut') == 'publiee' ? 'selected' : '' }}>✅ Publiée</option>
                        <option value="expiree" {{ request('statut') == 'expiree' ? 'selected' : '' }}>❌ Expirée</option>
                    </select>
                </div>

                <!-- Type d'annonce -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        <i class="fas fa-tag mr-1"></i>
                        Type d'annonce
                    </label>
                    <select name="type_annonce" class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        <option value="evenement" {{ request('type_annonce') == 'evenement' ? 'selected' : '' }}>📅 Événement</option>
                        <option value="administrative" {{ request('type_annonce') == 'administrative' ? 'selected' : '' }}>⚙️ Administrative</option>
                        <option value="pastorale" {{ request('type_annonce') == 'pastorale' ? 'selected' : '' }}>✝️ Pastorale</option>
                        <option value="urgence" {{ request('type_annonce') == 'urgence' ? 'selected' : '' }}>🚨 Urgence</option>
                        <option value="information" {{ request('type_annonce') == 'information' ? 'selected' : '' }}>ℹ️ Information</option>
                    </select>
                </div>

                @if(!$compact)
                    <!-- Niveau de priorité -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Priorité
                        </label>
                        <select name="niveau_priorite" class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Toutes priorités</option>
                            <option value="urgent" {{ request('niveau_priorite') == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                            <option value="important" {{ request('niveau_priorite') == 'important' ? 'selected' : '' }}>🟡 Important</option>
                            <option value="normal" {{ request('niveau_priorite') == 'normal' ? 'selected' : '' }}>⚪ Normal</option>
                        </select>
                    </div>
                @endif
            </div>

            @if(!$compact)
                <!-- Ligne 2 -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-6">
                    <!-- Audience cible -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-users mr-1"></i>
                            Audience cible
                        </label>
                        <select name="audience_cible" class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Toutes audiences</option>
                            <option value="tous" {{ request('audience_cible') == 'tous' ? 'selected' : '' }}>👥 Tous</option>
                            <option value="membres" {{ request('audience_cible') == 'membres' ? 'selected' : '' }}>👤 Membres</option>
                            <option value="leadership" {{ request('audience_cible') == 'leadership' ? 'selected' : '' }}>👔 Leadership</option>
                            <option value="jeunes" {{ request('audience_cible') == 'jeunes' ? 'selected' : '' }}>🧒 Jeunes</option>
                        </select>
                    </div>

                    <!-- Auteur -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-user-edit mr-1"></i>
                            Auteur
                        </label>
                        <select name="auteur_id" class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les auteurs</option>
                            <option value="me" {{ request('auteur_id') == 'me' ? 'selected' : '' }}>🙋 Mes annonces</option>
                            @foreach(\App\Models\User::whereHas('annoncesCreees')->orderBy('nom')->get() as $user)
                                <option value="{{ $user->id }}" {{ request('auteur_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->nom }} {{ $user->prenom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date de début -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            À partir du
                        </label>
                        <input type="date"
                               name="date_debut"
                               value="{{ request('date_debut') }}"
                               class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <!-- Date de fin -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-calendar-times mr-1"></i>
                            Jusqu'au
                        </label>
                        <input type="date"
                               name="date_fin"
                               value="{{ request('date_fin') }}"
                               class="w-full px-3 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <!-- Options avancées -->
                <div class="mt-6">
                    <button type="button"
                            onclick="toggleAdvancedOptions()"
                            class="text-sm text-blue-600 hover:text-blue-800 font-medium mb-4">
                        <i class="fas fa-chevron-down mr-1" id="advanced-toggle-icon"></i>
                        Options avancées
                    </button>

                    <div id="advanced-options" class="hidden">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 p-4 bg-slate-50 rounded-xl">
                            <!-- Filtres booléens -->
                            <div class="space-y-3">
                                <h4 class="font-medium text-slate-700">Options de diffusion</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                               name="afficher_site_web"
                                               value="1"
                                               {{ request('afficher_site_web') ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Site web</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                               name="annoncer_culte"
                                               value="1"
                                               {{ request('annoncer_culte') ? 'checked' : '' }}
                                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Culte</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Contact -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Contact principal</label>
                                <select name="contact_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                                    <option value="">Tous les contacts</option>
                                    @foreach(\App\Models\User::whereHas('annoncesContact')->orderBy('nom')->get() as $contact)
                                        <option value="{{ $contact->id }}" {{ request('contact_id') == $contact->id ? 'selected' : '' }}>
                                            {{ $contact->nom }} {{ $contact->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Expiration -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">État d'expiration</label>
                                <select name="expiration" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                                    <option value="">Toutes</option>
                                    <option value="expire_soon" {{ request('expiration') == 'expire_soon' ? 'selected' : '' }}>Expire bientôt</option>
                                    <option value="expires_today" {{ request('expiration') == 'expires_today' ? 'selected' : '' }}>Expire aujourd'hui</option>
                                    <option value="no_expiration" {{ request('expiration') == 'no_expiration' ? 'selected' : '' }}>Sans expiration</option>
                                </select>
                            </div>

                            <!-- Tri -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Trier par</label>
                                <div class="flex space-x-2">
                                    <select name="sort_by" class="flex-1 px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date création</option>
                                        <option value="publie_le" {{ request('sort_by') == 'publie_le' ? 'selected' : '' }}>Date publication</option>
                                        <option value="titre" {{ request('sort_by') == 'titre' ? 'selected' : '' }}>Titre</option>
                                        <option value="niveau_priorite" {{ request('sort_by') == 'niveau_priorite' ? 'selected' : '' }}>Priorité</option>
                                        <option value="expire_le" {{ request('sort_by') == 'expire_le' ? 'selected' : '' }}>Expiration</option>
                                    </select>
                                    <select name="sort_direction" class="px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                                        <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>↓ Desc</option>
                                        <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>↑ Asc</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Boutons d'action -->
            <div class="flex flex-wrap gap-3 items-center justify-between mt-6 pt-6 border-t border-slate-200">
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Rechercher
                    </button>

                    <button type="button"
                            onclick="clearAllFilters()"
                            class="inline-flex items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-eraser mr-2"></i>
                        Effacer
                    </button>

                    <button type="button"
                            onclick="saveSearchPreset()"
                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Sauvegarder
                    </button>
                </div>

                <!-- Recherches sauvegardées -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-slate-700">Recherches sauvées:</label>
                    <select id="saved-searches" onchange="loadSearchPreset(this.value)" class="px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                        <option value="">Sélectionner...</option>
                        <!-- Les options seront ajoutées dynamiquement -->
                    </select>
                </div>
            </div>

            <!-- Résumé des filtres actifs -->
            <div id="active-filters" class="mt-4 hidden">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-medium text-slate-700">Filtres actifs:</span>
                    <div id="filter-tags" class="flex flex-wrap gap-1">
                        <!-- Les tags de filtres seront ajoutés dynamiquement -->
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gérer l'affichage du bouton de suppression de recherche
    const searchInput = document.querySelector('input[name="search"]');
    const clearSearchBtn = document.getElementById('clear-search');

    searchInput.addEventListener('input', function() {
        clearSearchBtn.classList.toggle('hidden', !this.value);
    });

    // Initialiser l'affichage
    if (searchInput.value) {
        clearSearchBtn.classList.remove('hidden');
    }

    // Afficher les filtres actifs au chargement
    updateActiveFilters();

    // Charger les recherches sauvegardées
    loadSavedSearches();
});

function clearSearchField() {
    const searchInput = document.querySelector('input[name="search"]');
    searchInput.value = '';
    document.getElementById('clear-search').classList.add('hidden');
    searchInput.focus();
}

function toggleAdvancedOptions() {
    const advancedOptions = document.getElementById('advanced-options');
    const toggleIcon = document.getElementById('advanced-toggle-icon');

    if (advancedOptions.classList.contains('hidden')) {
        advancedOptions.classList.remove('hidden');
        toggleIcon.className = 'fas fa-chevron-up mr-1';
    } else {
        advancedOptions.classList.add('hidden');
        toggleIcon.className = 'fas fa-chevron-down mr-1';
    }
}

function clearAllFilters() {
    const form = document.getElementById('advanced-search-form');

    // Réinitialiser tous les champs
    form.reset();

    // Cacher les options avancées
    document.getElementById('advanced-options').classList.add('hidden');
    document.getElementById('advanced-toggle-icon').className = 'fas fa-chevron-down mr-1';

    // Cacher le bouton de suppression de recherche
    document.getElementById('clear-search').classList.add('hidden');

    // Mettre à jour les filtres actifs
    updateActiveFilters();
}

function updateActiveFilters() {
    const form = document.getElementById('advanced-search-form');
    const activeFiltersDiv = document.getElementById('active-filters');
    const filterTagsDiv = document.getElementById('filter-tags');
    const formData = new FormData(form);

    let activeTags = [];

    // Parcourir tous les champs du formulaire
    for (let [name, value] of formData) {
        if (value && name !== 'sort_by' && name !== 'sort_direction') {
            const field = form.querySelector(`[name="${name}"]`);
            let label = name;

            // Obtenir un libellé plus lisible
            const labelElement = form.querySelector(`label[for="${field?.id}"], label`);
            if (labelElement) {
                label = labelElement.textContent.replace(/[*:]/g, '').trim();
            }

            // Gérer les valeurs spéciales
            let displayValue = value;
            if (field?.tagName === 'SELECT') {
                const selectedOption = field.querySelector(`option[value="${value}"]`);
                if (selectedOption) {
                    displayValue = selectedOption.textContent;
                }
            } else if (field?.type === 'checkbox') {
                displayValue = label;
                label = '';
            }

            activeTags.push({ label, value: displayValue });
        }
    }

    if (activeTags.length > 0) {
        filterTagsDiv.innerHTML = activeTags.map(tag =>
            `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                ${tag.label ? `${tag.label}: ` : ''}${tag.value}
                <button type="button" onclick="removeFilter('${tag.name}')" class="ml-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-times"></i>
                </button>
            </span>`
        ).join('');
        activeFiltersDiv.classList.remove('hidden');
    } else {
        activeFiltersDiv.classList.add('hidden');
    }
}

function saveSearchPreset() {
    const name = prompt('Nom de la recherche sauvegardée:');
    if (!name) return;

    const form = document.getElementById('advanced-search-form');
    const formData = new FormData(form);
    const searchData = {};

    for (let [key, value] of formData) {
        if (value) {
            searchData[key] = value;
        }
    }

    // Sauvegarder dans le localStorage
    let savedSearches = JSON.parse(localStorage.getItem('annonces_saved_searches') || '{}');
    savedSearches[name] = searchData;
    localStorage.setItem('annonces_saved_searches', JSON.stringify(savedSearches));

    // Actualiser la liste
    loadSavedSearches();

    alert(`Recherche "${name}" sauvegardée avec succès!`);
}

function loadSavedSearches() {
    const select = document.getElementById('saved-searches');
    const savedSearches = JSON.parse(localStorage.getItem('annonces_saved_searches') || '{}');

    // Vider les options existantes (sauf la première)
    select.innerHTML = '<option value="">Sélectionner...</option>';

    // Ajouter les recherches sauvegardées
    for (let name in savedSearches) {
        const option = document.createElement('option');
        option.value = name;
        option.textContent = name;
        select.appendChild(option);
    }
}

function loadSearchPreset(presetName) {
    if (!presetName) return;

    const savedSearches = JSON.parse(localStorage.getItem('annonces_saved_searches') || '{}');
    const searchData = savedSearches[presetName];

    if (!searchData) return;

    const form = document.getElementById('advanced-search-form');

    // Réinitialiser le formulaire
    form.reset();

    // Appliquer les valeurs sauvegardées
    for (let [name, value] of Object.entries(searchData)) {
        const field = form.querySelector(`[name="${name}"]`);
        if (field) {
            if (field.type === 'checkbox') {
                field.checked = value === '1' || value === true;
            } else {
                field.value = value;
            }
        }
    }

    // Mettre à jour l'affichage
    updateActiveFilters();
}

// Écouter les changements de formulaire pour mettre à jour les filtres actifs
document.getElementById('advanced-search-form').addEventListener('change', updateActiveFilters);
document.getElementById('advanced-search-form').addEventListener('input', updateActiveFilters);
</script>
@endpush
