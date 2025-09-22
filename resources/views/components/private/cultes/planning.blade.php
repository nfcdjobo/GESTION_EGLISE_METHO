@extends('layouts.private.main')
@section('title', 'Planning des Cultes')

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Planning des Cultes</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.cultes.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-church mr-2"></i>
                            Cultes
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-medium text-slate-500">Planning</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Contrôles de navigation et filtres -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                        Navigation et Filtres
                    </h2>
                    <div class="flex items-center space-x-2">
                        @can('cultes.create')
                            <a href="{{ route('private.cultes.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i> Nouveau Culte
                            </a>
                        @endcan
                        <button onclick="printPlanning()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-print mr-2"></i> Imprimer
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('private.cultes.planning') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <!-- Navigation temporelle -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Navigation rapide</label>
                        <div class="flex space-x-2">
                            <button type="button" onclick="navigatePeriod('prev')"
                                class="px-3 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button" onclick="goToToday()"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                Aujourd'hui
                            </button>
                            <button type="button" onclick="navigatePeriod('next')"
                                class="px-3 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Type de vue -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Vue</label>
                        <select name="vue" onchange="this.form.submit()"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="semaine" {{ request('vue', $periode['vue']) == 'semaine' ? 'selected' : '' }}>
                                Semaine</option>
                            <option value="mois" {{ request('vue', $periode['vue']) == 'mois' ? 'selected' : '' }}>Mois
                            </option>
                            <option value="annee" {{ request('vue', $periode['vue']) == 'annee' ? 'selected' : '' }}>Année
                            </option>
                        </select>
                    </div>

                    <!-- Dates personnalisées -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                        <input type="date" name="date_debut" value="{{ request('date_debut', $periode['debut']) }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date fin</label>
                        <input type="date" name="date_fin" value="{{ request('date_fin', $periode['fin']) }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-filter mr-2"></i> Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Période actuelle -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 text-center">
                <div class="flex items-center justify-center space-x-4">
                    <div>
                        <span class="text-sm text-slate-500">Période affichée</span>
                        <p class="text-lg font-bold text-slate-800">
                            {{ \Carbon\Carbon::parse($periode['debut'])->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($periode['fin'])->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="w-px h-12 bg-slate-300"></div>
                    <div>
                        <span class="text-sm text-slate-500">Vue actuelle</span>
                        <p class="text-lg font-bold text-blue-600">{{ ucfirst($periode['vue']) }}</p>
                    </div>
                    <div class="w-px h-12 bg-slate-300"></div>
                    <div>
                        <span class="text-sm text-slate-500">Cultes planifiés</span>
                        <p class="text-lg font-bold text-green-600">{{ $cultes->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Planning principal -->
        @if ($cultes->count() > 0)
            @if ($periode['vue'] === 'semaine')
                <!-- Vue semaine -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-week text-blue-600 mr-2"></i>
                            Planning Hebdomadaire
                        </h2>
                    </div>
                    <div class="p-6">
                        @php
                            $semaine = collect();
                            $debut = \Carbon\Carbon::parse($periode['debut'])->startOfWeek();
                            for ($i = 0; $i < 7; $i++) {
                                $jour = $debut->copy()->addDays($i);
                                $cultesJour = $cultes->filter(function ($culte) use ($jour) {
                                    return \Carbon\Carbon::parse($culte->date_culte)->isSameDay($jour);
                                });
                                $semaine->push([
                                    'date' => $jour,
                                    'cultes' => $cultesJour,
                                ]);
                            }
                        @endphp

                        <div class="grid grid-cols-7 gap-2">
                            @foreach ($semaine as $jour)
                                <div
                                    class="min-h-48 border border-slate-200 rounded-xl p-3 {{ $jour['date']->isToday() ? 'bg-blue-50 border-blue-300' : 'bg-white' }}">
                                    <div class="text-center mb-3">
                                        <div class="text-sm font-medium text-slate-700">{{ $jour['date']->format('D') }}
                                        </div>
                                        <div
                                            class="text-lg font-bold {{ $jour['date']->isToday() ? 'text-blue-600' : 'text-slate-900' }}">
                                            {{ $jour['date']->format('j') }}</div>
                                        <div class="text-xs text-slate-500">{{ $jour['date']->format('M') }}</div>
                                    </div>

                                    <div class="space-y-2">
                                        @foreach ($jour['cultes'] as $culte)
                                            <div class="p-2 rounded-lg text-xs cursor-pointer transition-all duration-200 hover:shadow-md
                                            @switch($culte->statut)
                                                @case('planifie') bg-blue-100 text-blue-800 hover:bg-blue-200 @break
                                                @case('en_preparation') bg-yellow-100 text-yellow-800 hover:bg-yellow-200 @break
                                                @case('en_cours') bg-orange-100 text-orange-800 hover:bg-orange-200 @break
                                                @case('termine') bg-green-100 text-green-800 hover:bg-green-200 @break
                                                @case('annule') bg-red-100 text-red-800 hover:bg-red-200 @break
                                                @case('reporte') bg-purple-100 text-purple-800 hover:bg-purple-200 @break
                                                @default bg-gray-100 text-gray-800 hover:bg-gray-200
                                            @endswitch"
                                                onclick="showCulteDetails('{{ $culte->id }}')"
                                                data-culte-id="{{ $culte->id }}" title="{{ $culte->titre }}">
                                                <div class="font-semibold truncate">
                                                    {{ \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') }}</div>
                                                <div class="truncate">{{ $culte->titre }}</div>
                                                <div class="truncate text-xs opacity-75">{{ $culte->type_culte_libelle }}
                                                </div>
                                            </div>
                                        @endforeach

                                        @if ($jour['cultes']->isEmpty())
                                            <div class="text-center text-slate-400 text-xs mt-4">Aucun culte</div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @elseif($periode['vue'] === 'mois')
                <!-- Vue mois -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar text-green-600 mr-2"></i>
                            Planning Mensuel
                        </h2>
                    </div>
                    <div class="p-6">
                        @php
                            $mois = collect();
                            $debut = \Carbon\Carbon::parse($periode['debut'])->startOfMonth()->startOfWeek();
                            $fin = \Carbon\Carbon::parse($periode['fin'])->endOfMonth()->endOfWeek();

                            $current = $debut->copy();
                            while ($current->lte($fin)) {
                                $cultesJour = $cultes->filter(function ($culte) use ($current) {
                                    return \Carbon\Carbon::parse($culte->date_culte)->isSameDay($current);
                                });
                                $mois->push([
                                    'date' => $current->copy(),
                                    'cultes' => $cultesJour,
                                    'dans_mois' => $current->month === \Carbon\Carbon::parse($periode['debut'])->month,
                                ]);
                                $current->addDay();
                            }
                        @endphp

                        <!-- En-têtes des jours -->
                        <div class="grid grid-cols-7 gap-2 mb-4">
                            @foreach (['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'] as $jour)
                                <div class="text-center font-semibold text-slate-700 py-2">{{ $jour }}</div>
                            @endforeach
                        </div>

                        <!-- Grille du calendrier -->
                        <div class="grid grid-cols-7 gap-2">
                            @foreach ($mois->chunk(7) as $semaine)
                                @foreach ($semaine as $jour)
                                    <div
                                        class="min-h-24 border border-slate-200 rounded-lg p-2 {{ $jour['date']->isToday() ? 'bg-blue-50 border-blue-300' : ($jour['dans_mois'] ? 'bg-white' : 'bg-slate-50') }}">
                                        <div class="text-center mb-1">
                                            <div
                                                class="text-sm {{ $jour['date']->isToday() ? 'font-bold text-blue-600' : ($jour['dans_mois'] ? 'text-slate-900' : 'text-slate-400') }}">
                                                {{ $jour['date']->format('j') }}
                                            </div>
                                        </div>

                                        <div class="space-y-1">
                                            @foreach ($jour['cultes']->take(2) as $culte)
                                                <div class="px-1 py-0.5 rounded text-xs cursor-pointer transition-all duration-200 hover:shadow-sm
                                                @switch($culte->statut)
                                                    @case('planifie') bg-blue-100 text-blue-800 @break
                                                    @case('en_preparation') bg-yellow-100 text-yellow-800 @break
                                                    @case('en_cours') bg-orange-100 text-orange-800 @break
                                                    @case('termine') bg-green-100 text-green-800 @break
                                                    @case('annule') bg-red-100 text-red-800 @break
                                                    @case('reporte') bg-purple-100 text-purple-800 @break
                                                    @default bg-gray-100 text-gray-800
                                                @endswitch"
                                                    onclick="showCulteDetails('{{ $culte->id }}')"
                                                    title="{{ $culte->titre }} - {{ \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') }}">
                                                    <div class="truncate">
                                                        {{ \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') }}
                                                        {{ Str::limit($culte->titre, 10) }}</div>
                                                </div>
                                            @endforeach

                                            @if ($jour['cultes']->count() > 2)
                                                <div class="text-xs text-slate-500 text-center">
                                                    +{{ $jour['cultes']->count() - 2 }} autres</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <!-- Vue année (liste par mois) -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                            Planning Annuel
                        </h2>
                    </div>
                    <div class="p-6">
                        @php
                            $cultesParMois = $cultes->groupBy(function ($culte) {
                                return \Carbon\Carbon::parse($culte->date_culte)->format('Y-m');
                            });
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($cultesParMois as $mois => $cultesMois)
                                @php
                                    $dateMois = \Carbon\Carbon::createFromFormat('Y-m', $mois);
                                @endphp
                                <div class="border border-slate-200 rounded-xl p-4 bg-white">
                                    <h3 class="text-lg font-bold text-slate-800 mb-4 text-center">
                                        {{ $dateMois->format('F Y') }}
                                        <span class="text-sm text-slate-500 font-normal">({{ $cultesMois->count() }}
                                            cultes)</span>
                                    </h3>

                                    <div class="space-y-3">
                                        @foreach ($cultesMois->sortBy('date_culte') as $culte)
                                            <div class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer"
                                                onclick="showCulteDetails('{{ $culte->id }}')">
                                                <div class="flex-shrink-0">
                                                    <div
                                                        class="w-10 h-10 rounded-lg flex items-center justify-center text-xs font-bold
                                                    @switch($culte->statut)
                                                        @case('planifie') bg-blue-100 text-blue-800 @break
                                                        @case('en_preparation') bg-yellow-100 text-yellow-800 @break
                                                        @case('en_cours') bg-orange-100 text-orange-800 @break
                                                        @case('termine') bg-green-100 text-green-800 @break
                                                        @case('annule') bg-red-100 text-red-800 @break
                                                        @case('reporte') bg-purple-100 text-purple-800 @break
                                                        @default bg-gray-100 text-gray-800
                                                    @endswitch">
                                                        {{ \Carbon\Carbon::parse($culte->date_culte)->format('j') }}
                                                    </div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-semibold text-slate-900 truncate">{{ $culte->titre }}
                                                    </p>
                                                    <p class="text-sm text-slate-600">
                                                        {{ \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') }} -
                                                        {{ $culte->type_culte_libelle }}
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Légende -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-amber-600 mr-2"></i>
                        Légende des Statuts
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded"></div>
                            <span class="text-sm text-slate-700">Planifié</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 rounded"></div>
                            <span class="text-sm text-slate-700">En préparation</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-orange-100 border border-orange-300 rounded"></div>
                            <span class="text-sm text-slate-700">En cours</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-green-100 border border-green-300 rounded"></div>
                            <span class="text-sm text-slate-700">Terminé</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-red-100 border border-red-300 rounded"></div>
                            <span class="text-sm text-slate-700">Annulé</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-purple-100 border border-purple-300 rounded"></div>
                            <span class="text-sm text-slate-700">Reporté</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Aucun culte -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-12 text-center">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-calendar-times text-4xl text-slate-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-2">Aucun culte planifié</h3>
                    <p class="text-slate-500 mb-6">
                        Aucun culte n'est planifié pour la période sélectionnée.
                    </p>
                    @can('cultes.create')
                        <a href="{{ route('private.cultes.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Planifier un culte
                        </a>
                    @endcan
                </div>
            </div>
        @endif
    </div>

    <!-- Modal détails culte -->
    <div id="culteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-screen overflow-y-auto">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-slate-900">Détails du Culte</h3>
                    <button onclick="closeCulteModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div id="culteModalContent" class="p-6">
                <!-- Contenu chargé dynamiquement -->
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button onclick="closeCulteModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Fermer
                </button>
                <a id="culteViewLink" href="#"
                    class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    Voir les détails complets
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Navigation dans le planning
            function navigatePeriod(direction) {
                const form = document.querySelector('form');
                const dateDebut = new Date(form.querySelector('input[name="date_debut"]').value);
                const dateFin = new Date(form.querySelector('input[name="date_fin"]').value);
                const vue = form.querySelector('select[name="vue"]').value;

                let newDateDebut, newDateFin;

                if (vue === 'semaine') {
                    const days = direction === 'next' ? 7 : -7;
                    newDateDebut = new Date(dateDebut.getTime() + (days * 24 * 60 * 60 * 1000));
                    newDateFin = new Date(dateFin.getTime() + (days * 24 * 60 * 60 * 1000));
                } else if (vue === 'mois') {
                    if (direction === 'next') {
                        newDateDebut = new Date(dateDebut.getFullYear(), dateDebut.getMonth() + 1, 1);
                        newDateFin = new Date(dateDebut.getFullYear(), dateDebut.getMonth() + 2, 0);
                    } else {
                        newDateDebut = new Date(dateDebut.getFullYear(), dateDebut.getMonth() - 1, 1);
                        newDateFin = new Date(dateDebut.getFullYear(), dateDebut.getMonth(), 0);
                    }
                } else { // année
                    const year = direction === 'next' ? 1 : -1;
                    newDateDebut = new Date(dateDebut.getFullYear() + year, 0, 1);
                    newDateFin = new Date(dateDebut.getFullYear() + year, 11, 31);
                }

                form.querySelector('input[name="date_debut"]').value = newDateDebut.toISOString().split('T')[0];
                form.querySelector('input[name="date_fin"]').value = newDateFin.toISOString().split('T')[0];
                form.submit();
            }

            // Aller à aujourd'hui
            function goToToday() {
                const form = document.querySelector('form');
                const today = new Date();
                const vue = form.querySelector('select[name="vue"]').value;

                let dateDebut, dateFin;

                if (vue === 'semaine') {
                    const dayOfWeek = today.getDay();
                    const diff = today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1); // Commence le lundi
                    dateDebut = new Date(today.setDate(diff));
                    dateFin = new Date(dateDebut.getTime() + (6 * 24 * 60 * 60 * 1000));
                } else if (vue === 'mois') {
                    dateDebut = new Date(today.getFullYear(), today.getMonth(), 1);
                    dateFin = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                } else {
                    dateDebut = new Date(today.getFullYear(), 0, 1);
                    dateFin = new Date(today.getFullYear(), 11, 31);
                }

                form.querySelector('input[name="date_debut"]').value = dateDebut.toISOString().split('T')[0];
                form.querySelector('input[name="date_fin"]').value = dateFin.toISOString().split('T')[0];
                form.submit();
            }

            // Afficher les détails d'un culte
            function showCulteDetails(culteId) {
                const modal = document.getElementById('culteModal');
                const content = document.getElementById('culteModalContent');
                const viewLink = document.getElementById('culteViewLink');

                // Afficher le modal avec un loader
                content.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
            <p class="text-slate-500 mt-4">Chargement...</p>
        </div>
    `;
                modal.classList.remove('hidden');

                // Charger les détails via AJAX
                fetch(`{{ route('private.cultes.show', ':id') }}`.replace(':id', culteId), {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const culte = data.data;
                            content.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-slate-900">${culte.titre}</h4>
                        <p class="text-slate-600">${culte.type_culte_libelle}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-500">Date:</span>
                            <div class="font-semibold">${new Date(culte.date_culte).toLocaleDateString('fr-FR')}</div>
                        </div>
                        <div>
                            <span class="text-slate-500">Heure:</span>
                            <div class="font-semibold">${culte.heure_debut ? new Date('1970-01-01T' + culte.heure_debut).toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'}) : 'Non définie'}</div>
                        </div>
                        <div>
                            <span class="text-slate-500">Lieu:</span>
                            <div class="font-semibold">${culte.lieu}</div>
                        </div>
                        <div>
                            <span class="text-slate-500">Statut:</span>
                            <div class="font-semibold">${culte.statut_libelle}</div>
                        </div>
                    </div>

                    ${culte.description ? `
                                <div>
                                    <span class="text-slate-500">Description:</span>
                                    <p class="text-slate-700 mt-1">${culte.description}</p>
                                </div>
                            ` : ''}

                    ${culte.pasteur_principal ? `
                                <div>
                                    <span class="text-slate-500">Pasteur principal:</span>
                                    <div class="font-semibold">${culte.pasteur_principal.nom} ${culte.pasteur_principal.prenom}</div>
                                </div>
                            ` : ''}

                    ${culte.titre_message ? `
                                <div>
                                    <span class="text-slate-500">Message:</span>
                                    <div class="font-semibold">${culte.titre_message}</div>
                                </div>
                            ` : ''}
                </div>
            `;

                            viewLink.href = `{{ route('private.cultes.show', ':id') }}`.replace(':id', culteId);
                        } else {
                            content.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                    <p class="text-slate-500">Erreur lors du chargement des détails</p>
                </div>
            `;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        content.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-exclamation-triangle text-4xl text-red-400 mb-4"></i>
                <p class="text-slate-500">Erreur lors du chargement des détails</p>
            </div>
        `;
                    });
            }

            // Fermer le modal
            function closeCulteModal() {
                document.getElementById('culteModal').classList.add('hidden');
            }

            // Impression du planning
            function printPlanning() {
                window.print();
            }

            // Raccourcis clavier
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch (e.key) {
                        case 'p':
                            e.preventDefault();
                            printPlanning();
                            break;
                        case 'ArrowLeft':
                            e.preventDefault();
                            navigatePeriod('prev');
                            break;
                        case 'ArrowRight':
                            e.preventDefault();
                            navigatePeriod('next');
                            break;
                        case 'h':
                            e.preventDefault();
                            goToToday();
                            break;
                    }
                }

                if (e.key === 'Escape') {
                    closeCulteModal();
                }
            });

            // Fermer le modal en cliquant à l'extérieur
            document.getElementById('culteModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCulteModal();
                }
            });

            // Drag and drop pour déplacer les cultes (fonctionnalité avancée)
            function initDragAndDrop() {
                const culteElements = document.querySelectorAll('[data-culte-id]');

                culteElements.forEach(element => {
                    element.draggable = true;

                    element.addEventListener('dragstart', function(e) {
                        e.dataTransfer.setData('text/plain', this.dataset.culteId);
                        this.style.opacity = '0.5';
                    });

                    element.addEventListener('dragend', function(e) {
                        this.style.opacity = '1';
                    });
                });

                // Zones de drop (jours)
                const dropZones = document.querySelectorAll('.min-h-24, .min-h-48');

                dropZones.forEach(zone => {
                    zone.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        this.style.backgroundColor = '#f0f9ff';
                    });

                    zone.addEventListener('dragleave', function(e) {
                        this.style.backgroundColor = '';
                    });

                    zone.addEventListener('drop', function(e) {
                        e.preventDefault();
                        this.style.backgroundColor = '';

                        const culteId = e.dataTransfer.getData('text/plain');
                        // Ici on pourrait implémenter la logique de déplacement via AJAX
                        console.log('Déplacer le culte', culteId, 'vers cette date');
                    });
                });
            }

            // Animation au scroll
            function observeElements() {
                const elements = document.querySelectorAll('.hover\\:shadow-xl');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            // entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, {
                    threshold: 0.1
                });

                elements.forEach(element => {
                    element.style.opacity = '0';
                    // element.style.transform = 'translateY(20px)';
                    element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    observer.observe(element);
                });
            }

            // Initialisation
            document.addEventListener('DOMContentLoaded', function() {
                initDragAndDrop();
                observeElements();

                // Auto-refresh toutes les 5 minutes si la page est visible
                setInterval(() => {
                    if (document.visibilityState === 'visible') {
                        // Optionnel: rafraîchir la page
                        // location.reload();
                    }
                }, 300000);
            });
        </script>

        <style>
            @media print {

                .no-print,
                .fixed,
                button,
                .hover\\:shadow-xl {
                    display: none !important;
                }

                .bg-white\/80 {
                    background-color: white !important;
                }

                .shadow-lg {
                    box-shadow: none !important;
                }

                .rounded-2xl {
                    border-radius: 8px !important;
                }
            }

            /* Animation pour les éléments du planning */
            .calendar-item {
                transition: all 0.2s ease;
            }

            .calendar-item:hover {
                /* transform: translateY(-2px); */
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            /* Amélioration de l'accessibilité */
            .calendar-item:focus {
                outline: 2px solid #3b82f6;
                outline-offset: 2px;
            }

            /* Responsive improvements */
            @media (max-width: 768px) {
                .grid-cols-7 {
                    grid-template-columns: repeat(7, minmax(0, 1fr));
                    gap: 1px;
                }

                .min-h-24 {
                    min-height: 3rem;
                }

                .min-h-48 {
                    min-height: 6rem;
                }
            }
        </style>
    @endpush
@endsection
