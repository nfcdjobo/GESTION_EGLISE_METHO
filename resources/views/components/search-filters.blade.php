@props([
    'action' => '',
    'method' => 'GET',
    'searchPlaceholder' => 'Rechercher...',
    'showSearch' => true,
    'showReset' => true,
    'filters' => [],
    'searchValue' => '',
    'class' => ''
])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 {{ $class }}">
    <form method="{{ $method }}" action="{{ $action }}" class="space-y-4" id="search-filters-form">
        @if($method !== 'GET')
            @csrf
        @endif

        <div class="flex flex-col lg:flex-row lg:items-end lg:space-x-4 space-y-4 lg:space-y-0">
            {{-- Champ de recherche principal --}}
            @if($showSearch)
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1 text-gray-500"></i>
                    Recherche
                </label>
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        id="search"
                        value="{{ $searchValue ?: request('search') }}"
                        placeholder="{{ $searchPlaceholder }}"
                        class="w-full rounded-lg border-gray-300 pl-10 focus:border-indigo-500 focus:ring-indigo-500"
                        autocomplete="off"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    {{-- Bouton pour effacer la recherche --}}
                    <button
                        type="button"
                        id="clear-search"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 hidden"
                        onclick="clearSearch()"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            @endif

            {{-- Filtres dynamiques --}}
            @foreach($filters as $filter)
            <div class="min-w-0 {{ $filter['width'] ?? 'w-full lg:w-48' }}">
                <label for="{{ $filter['name'] }}" class="block text-sm font-medium text-gray-700 mb-2">
                    @if(isset($filter['icon']))
                        <i class="{{ $filter['icon'] }} mr-1 text-gray-500"></i>
                    @endif
                    {{ $filter['label'] }}
                </label>

                @if($filter['type'] === 'select')
                    <select
                        name="{{ $filter['name'] }}"
                        id="{{ $filter['name'] }}"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        @if(isset($filter['onchange'])) onchange="{{ $filter['onchange'] }}" @endif
                    >
                        <option value="">{{ $filter['placeholder'] ?? 'Tous' }}</option>
                        @foreach($filter['options'] as $value => $label)
                            <option
                                value="{{ $value }}"
                                {{ request($filter['name']) == $value ? 'selected' : '' }}
                            >
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                @elseif($filter['type'] === 'date')
                    <input
                        type="date"
                        name="{{ $filter['name'] }}"
                        id="{{ $filter['name'] }}"
                        value="{{ request($filter['name']) }}"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        @if(isset($filter['min'])) min="{{ $filter['min'] }}" @endif
                        @if(isset($filter['max'])) max="{{ $filter['max'] }}" @endif
                    >

                @elseif($filter['type'] === 'daterange')
                    <div class="flex space-x-2">
                        <input
                            type="date"
                            name="{{ $filter['name'] }}_start"
                            placeholder="Du"
                            value="{{ request($filter['name'] . '_start') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        <input
                            type="date"
                            name="{{ $filter['name'] }}_end"
                            placeholder="Au"
                            value="{{ request($filter['name'] . '_end') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                @elseif($filter['type'] === 'number')
                    <input
                        type="number"
                        name="{{ $filter['name'] }}"
                        id="{{ $filter['name'] }}"
                        value="{{ request($filter['name']) }}"
                        placeholder="{{ $filter['placeholder'] ?? '' }}"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        @if(isset($filter['min'])) min="{{ $filter['min'] }}" @endif
                        @if(isset($filter['max'])) max="{{ $filter['max'] }}" @endif
                    >

                @elseif($filter['type'] === 'text')
                    <input
                        type="text"
                        name="{{ $filter['name'] }}"
                        id="{{ $filter['name'] }}"
                        value="{{ request($filter['name']) }}"
                        placeholder="{{ $filter['placeholder'] ?? '' }}"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                @elseif($filter['type'] === 'multiselect')
                    <div class="relative" x-data="{ open: false }">
                        <button
                            type="button"
                            @click="open = !open"
                            class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-left focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <span class="block truncate">
                                {{ count(request($filter['name'], [])) > 0 ? count(request($filter['name'], [])) . ' sélectionné(s)' : ($filter['placeholder'] ?? 'Sélectionner...') }}
                            </span>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-2">
                                <i class="fas fa-chevron-down text-gray-400"></i>
                            </span>
                        </button>

                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none"
                        >
                            @foreach($filter['options'] as $value => $label)
                            <label class="flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="{{ $filter['name'] }}[]"
                                    value="{{ $value }}"
                                    {{ in_array($value, request($filter['name'], [])) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-2"
                                >
                                <span class="text-sm text-gray-900">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            @endforeach

            {{-- Boutons d'action --}}
            <div class="flex space-x-2 lg:flex-shrink-0">
                <button
                    type="submit"
                    class="btn-primary"
                    title="Appliquer les filtres"
                >
                    <i class="fas fa-search mr-2"></i>
                    <span class="hidden sm:inline">Filtrer</span>
                </button>

                @if($showReset)
                <a
                    href="{{ $action }}"
                    class="btn-secondary"
                    title="Réinitialiser les filtres"
                >
                    <i class="fas fa-refresh mr-2"></i>
                    <span class="hidden sm:inline">Reset</span>
                </a>
                @endif
            </div>
        </div>

        {{-- Filtres actifs --}}
        @if(request()->hasAny(['search', 'sort', 'direction']) || collect($filters)->pluck('name')->intersect(array_keys(request()->all()))->isNotEmpty())
        <div class="border-t border-gray-200 pt-4">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-sm font-medium text-gray-700">Filtres actifs :</span>

                {{-- Recherche active --}}
                @if(request('search'))
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    <i class="fas fa-search mr-1"></i>
                    "{{ request('search') }}"
                    <button type="button" onclick="removeFilter('search')" class="ml-1 text-indigo-600 hover:text-indigo-800">
                        <i class="fas fa-times"></i>
                    </button>
                </span>
                @endif

                {{-- Autres filtres actifs --}}
                @foreach($filters as $filter)
                    @if(request($filter['name']))
                        @if($filter['type'] === 'multiselect')
                            @foreach(request($filter['name'], []) as $value)
                                @php
                                    $label = $filter['options'][$value] ?? $value;
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $filter['label'] }}: {{ $label }}
                                    <button type="button" onclick="removeMultiselectValue('{{ $filter['name'] }}', '{{ $value }}')" class="ml-1 text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endforeach
                        @elseif($filter['type'] === 'daterange')
                            @if(request($filter['name'] . '_start') || request($filter['name'] . '_end'))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $filter['label'] }}:
                                    {{ request($filter['name'] . '_start') }}
                                    @if(request($filter['name'] . '_start') && request($filter['name'] . '_end')) → @endif
                                    {{ request($filter['name'] . '_end') }}
                                    <button type="button" onclick="removeDateRange('{{ $filter['name'] }}')" class="ml-1 text-green-600 hover:text-green-800">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                            @endif
                        @else
                            @php
                                $displayValue = $filter['type'] === 'select' && isset($filter['options'][request($filter['name'])])
                                    ? $filter['options'][request($filter['name'])]
                                    : request($filter['name']);
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $filter['label'] }}: {{ $displayValue }}
                                <button type="button" onclick="removeFilter('{{ $filter['name'] }}')" class="ml-1 text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        @endif
                    @endif
                @endforeach

                {{-- Bouton tout effacer --}}
                <a href="{{ $action }}" class="text-xs text-red-600 hover:text-red-800 font-medium">
                    <i class="fas fa-trash mr-1"></i>Tout effacer
                </a>
            </div>
        </div>
        @endif
    </form>
</div>

@push('scripts')
<script>
// Gestion de la recherche en temps réel (optionnel)
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const clearButton = document.getElementById('clear-search');

    if (searchInput && clearButton) {
        // Afficher/masquer le bouton de suppression
        function toggleClearButton() {
            if (searchInput.value.length > 0) {
                clearButton.classList.remove('hidden');
            } else {
                clearButton.classList.add('hidden');
            }
        }

        searchInput.addEventListener('input', toggleClearButton);
        toggleClearButton(); // Check initial state
    }
});

function clearSearch() {
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.value = '';
        searchInput.dispatchEvent(new Event('input'));
        searchInput.focus();
    }
}

function removeFilter(filterName) {
    const url = new URL(window.location);
    url.searchParams.delete(filterName);
    window.location.href = url.toString();
}

function removeMultiselectValue(filterName, value) {
    const form = document.getElementById('search-filters-form');
    const checkbox = form.querySelector(`input[name="${filterName}[]"][value="${value}"]`);
    if (checkbox) {
        checkbox.checked = false;
        form.submit();
    }
}

function removeDateRange(filterName) {
    const url = new URL(window.location);
    url.searchParams.delete(filterName + '_start');
    url.searchParams.delete(filterName + '_end');
    window.location.href = url.toString();
}

// Soumission automatique sur changement de filtre (optionnel)
function enableAutoSubmit() {
    const form = document.getElementById('search-filters-form');
    const inputs = form.querySelectorAll('select, input[type="date"], input[type="number"]');

    inputs.forEach(input => {
        input.addEventListener('change', () => {
            form.submit();
        });
    });
}

// Recherche en temps réel avec debounce (optionnel)
function enableLiveSearch(delay = 500) {
    const searchInput = document.getElementById('search');
    const form = document.getElementById('search-filters-form');
    let timeout;

    if (searchInput && form) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                form.submit();
            }, delay);
        });
    }
}

// Activer les fonctionnalités (décommentez selon vos besoins)
// enableAutoSubmit();
// enableLiveSearch(800);
</script>
@endpush

{{--
Exemple d'utilisation :

<x-search-filters
    :action="route('private.users.index')"
    searchPlaceholder="Nom, prénom, email, téléphone..."
    :searchValue="request('search')"
    :filters="[
        [
            'name' => 'statut_membre',
            'label' => 'Statut membre',
            'type' => 'select',
            'icon' => 'fas fa-user-tag',
            'placeholder' => 'Tous les statuts',
            'options' => [
                'actif' => 'Actif',
                'inactif' => 'Inactif',
                'visiteur' => 'Visiteur',
                'nouveau_converti' => 'Nouveau converti'
            ]
        ],
        [
            'name' => 'role',
            'label' => 'Rôle',
            'type' => 'select',
            'icon' => 'fas fa-key',
            'options' => $roles->pluck('name', 'slug')->toArray()
        ],
        [
            'name' => 'classe_id',
            'label' => 'Classe',
            'type' => 'select',
            'icon' => 'fas fa-users',
            'options' => $classes->pluck('nom', 'id')->toArray()
        ],
        [
            'name' => 'date_adhesion',
            'label' => 'Période d\'adhésion',
            'type' => 'daterange',
            'icon' => 'fas fa-calendar'
        ],
        [
            'name' => 'sexe',
            'label' => 'Sexe',
            'type' => 'multiselect',
            'icon' => 'fas fa-venus-mars',
            'options' => [
                'masculin' => 'Masculin',
                'feminin' => 'Féminin'
            ]
        ]
    ]"
/>
--}}
