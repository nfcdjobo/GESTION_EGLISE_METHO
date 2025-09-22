@extends('layouts.private.main')
@section('title', 'Statistiques des Cultes')

@section('content')
    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Statistiques des Cultes</h1>
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
                            <span class="text-sm font-medium text-slate-500">Statistiques</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Filtres -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres de Période
                </h2>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('private.cultes.statistiques') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date début</label>
                        <input type="date" name="date_debut"
                            value="{{ request('date_debut', $statistiques['periode']['debut']) }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date fin</label>
                        <input type="date" name="date_fin"
                            value="{{ request('date_fin', $statistiques['periode']['fin']) }}"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type de culte</label>
                        <select name="type_culte"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les types</option>
                            @foreach (\App\Models\Culte::TYPE_CULTE as $key => $label)
                                <option value="{{ $key }}" {{ request('type_culte') == $key ? 'selected' : '' }}>
                                    {{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Programme</label>
                        <select name="programme_id"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Tous les programmes</option>
                            @foreach ($programmes as $programme)
                                <option value="{{ $programme->id }}"
                                    {{ request('programme_id') == $programme->id ? 'selected' : '' }}>{{ $programme->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i> Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Période et résumé -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                        Période d'Analyse
                    </h2>
                    <div class="flex items-center space-x-2">
                        <button onclick="exportStats()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </button>
                        <button onclick="printStats()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                            <i class="fas fa-print mr-2"></i> Imprimer
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="text-center">
                    <p class="text-lg text-slate-700">
                        Statistiques du <span
                            class="font-bold text-blue-600">{{ \Carbon\Carbon::parse($statistiques['periode']['debut'])->format('d/m/Y') }}</span>
                        au <span
                            class="font-bold text-blue-600">{{ \Carbon\Carbon::parse($statistiques['periode']['fin'])->format('d/m/Y') }}</span>
                    </p>
                    <p class="text-sm text-slate-500 mt-1">
                        Période de
                        {{ \Carbon\Carbon::parse($statistiques['periode']['debut'])->diffInDays(\Carbon\Carbon::parse($statistiques['periode']['fin'])) + 1 }}
                        jours
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistiques générales -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-church text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $statistiques['totaux']['nombre_cultes'] }}</p>
                        <p class="text-sm text-slate-500">Total cultes</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $statistiques['totaux']['cultes_termines'] }}</p>
                        <p class="text-sm text-slate-500">Cultes terminés</p>
                        @if ($statistiques['totaux']['nombre_cultes'] > 0)
                            <p class="text-xs text-green-600">
                                {{ round(($statistiques['totaux']['cultes_termines'] / $statistiques['totaux']['nombre_cultes']) * 100, 1) }}%
                                de réalisation</p>
                        @endif
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ number_format($statistiques['totaux']['total_participants']) }}</p>
                        <p class="text-sm text-slate-500">Total participants</p>
                        @if ($statistiques['moyennes']['participants_par_culte'])
                            <p class="text-xs text-purple-600">
                                {{ round($statistiques['moyennes']['participants_par_culte'], 1) }} par culte</p>
                        @endif
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-hand-holding-heart text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ number_format($statistiques['totaux']['total_offrandes'], 0) }}FCFA</p>
                        <p class="text-sm text-slate-500">Total offrandes</p>
                        @if ($statistiques['moyennes']['offrandes_par_culte'])
                            <p class="text-xs text-orange-600">
                                {{ number_format($statistiques['moyennes']['offrandes_par_culte'], 0) }}FCFA par culte</p>
                        @endif
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-heart text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $statistiques['totaux']['total_conversions'] }}
                        </p>
                        <p class="text-sm text-slate-500">Conversions</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-water text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $statistiques['totaux']['total_baptemes'] }}</p>
                        <p class="text-sm text-slate-500">Baptêmes</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-times-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $statistiques['totaux']['cultes_annules'] }}</p>
                        <p class="text-sm text-slate-500">Cultes annulés</p>
                        @if ($statistiques['totaux']['nombre_cultes'] > 0)
                            <p class="text-xs text-red-600">
                                {{ round(($statistiques['totaux']['cultes_annules'] / $statistiques['totaux']['nombre_cultes']) * 100, 1) }}%
                                d'annulation</p>
                        @endif
                    </div>
                </div>
            </div>

            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-amber-500 to-yellow-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">
                            {{ $statistiques['moyennes']['note_globale'] ? round($statistiques['moyennes']['note_globale'], 1) : '-' }}
                        </p>
                        <p class="text-sm text-slate-500">Note moyenne</p>
                        @if ($statistiques['moyennes']['note_globale'])
                            <div class="flex mt-1">
                                @for ($i = 1; $i <= 10; $i++)
                                    <i
                                        class="fas fa-star text-xs {{ $i <= round($statistiques['moyennes']['note_globale']) ? 'text-amber-400' : 'text-slate-300' }}"></i>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Statistiques par type -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                        Répartition par Type de Culte
                    </h2>
                </div>
                <div class="p-6">
                    @if ($statistiques['par_type']->count() > 0)
                        <div class="space-y-4">
                            @foreach ($statistiques['par_type'] as $type)
                                @php
                                    $percentage =
                                        $statistiques['totaux']['nombre_cultes'] > 0
                                            ? ($type->nombre / $statistiques['totaux']['nombre_cultes']) * 100
                                            : 0;
                                    $typeLabel = \App\Models\Culte::TYPE_CULTE[$type->type_culte] ?? $type->type_culte;
                                @endphp
                                <div
                                    class="flex items-center justify-between p-4 bg-gradient-to-r from-slate-50 to-blue-50 rounded-xl">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="font-semibold text-slate-900">{{ $typeLabel }}</span>
                                            <span class="text-sm text-slate-600">{{ $type->nombre }} cultes
                                                ({{ round($percentage, 1) }}%)
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full transition-all duration-300"
                                                style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <div class="flex items-center justify-between mt-2 text-sm text-slate-600">
                                            <span>{{ number_format($type->total_participants ?: 0) }} participants</span>
                                            <span>Moy: {{ round($type->moyenne_participants ?: 0, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-chart-pie text-4xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500">Aucune donnée disponible pour cette période</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Évolution mensuelle -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-line text-green-600 mr-2"></i>
                        Évolution Mensuelle
                    </h2>
                </div>
                <div class="p-6">
                    @if ($statistiques['par_mois']->count() > 0)
                        <div class="space-y-4">
                            @foreach ($statistiques['par_mois'] as $mois)
                                @php
                                    $moisNom = \Carbon\Carbon::create($mois->annee, $mois->mois, 1)->format('F Y');
                                    $maxParticipants = $statistiques['par_mois']->max('total_participants') ?: 1;
                                    $participantsPercentage = ($mois->total_participants / $maxParticipants) * 100;
                                @endphp
                                <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-semibold text-slate-900">{{ ucfirst($moisNom) }}</span>
                                        <span class="text-sm text-slate-600">{{ $mois->nombre_cultes }} cultes</span>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-slate-600">Participants:</span>
                                            <span
                                                class="font-semibold text-green-700">{{ number_format($mois->total_participants ?: 0) }}</span>
                                        </div>
                                        <div>
                                            <span class="text-slate-600">Offrandes:</span>
                                            <span
                                                class="font-semibold text-emerald-700">{{ number_format($mois->total_offrandes ?: 0) }}FCFA</span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
                                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 h-2 rounded-full transition-all duration-300"
                                            style="width: {{ $participantsPercentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-chart-line text-4xl text-slate-300 mb-4"></i>
                            <p class="text-slate-500">Aucune donnée mensuelle disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Moyennes et indicateurs -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calculator text-amber-600 mr-2"></i>
                    Moyennes et Indicateurs
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center p-6 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl">
                        <div class="text-3xl font-bold text-blue-600 mb-2">
                            {{ round($statistiques['moyennes']['participants_par_culte'] ?: 0, 1) }}</div>
                        <div class="text-sm text-slate-600">Participants par culte</div>
                    </div>

                    <div class="text-center p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                        <div class="text-3xl font-bold text-green-600 mb-2">
                            {{ number_format($statistiques['moyennes']['offrandes_par_culte'] ?: 0, 0) }}FCFA</div>
                        <div class="text-sm text-slate-600">Offrande par culte</div>
                    </div>

                    <div class="text-center p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
                        <div class="text-3xl font-bold text-purple-600 mb-2">
                            {{ round($statistiques['moyennes']['note_louange'] ?: 0, 1) }}/10</div>
                        <div class="text-sm text-slate-600">Note louange</div>
                    </div>

                    <div class="text-center p-6 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl">
                        <div class="text-3xl font-bold text-amber-600 mb-2">
                            {{ round($statistiques['moyennes']['note_message'] ?: 0, 1) }}/10</div>
                        <div class="text-sm text-slate-600">Note message</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-bolt text-red-600 mr-2"></i>
                    Actions Rapides
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('private.cultes.index') }}"
                        class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200">
                        <i class="fas fa-list mr-2"></i> Liste des cultes
                    </a>
                    @can('cultes.planning')
                        <a href="{{ route('private.cultes.planning') }}"
                            class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                            <i class="fas fa-calendar mr-2"></i> Planning
                        </a>
                    @endcan
                    @can('cultes.dashboard')
                        <a href="{{ route('private.cultes.dashboard') }}"
                            class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                            <i class="fas fa-tachometer-alt mr-2"></i> Tableau de bord
                        </a>
                    @endcan
                    @can('cultes.create')
                        <a href="{{ route('private.cultes.create') }}"
                            class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i> Nouveau culte
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Raccourcis période
            function setPeriod(type) {
                const now = new Date();
                const form = document.querySelector('form');
                let startDate, endDate;

                switch (type) {
                    case 'week':
                        startDate = new Date(now.setDate(now.getDate() - now.getDay()));
                        endDate = new Date(now.setDate(now.getDate() - now.getDay() + 6));
                        break;
                    case 'month':
                        startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                        endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);
                        break;
                    case 'year':
                        startDate = new Date(now.getFullYear(), 0, 1);
                        endDate = new Date(now.getFullYear(), 11, 31);
                        break;
                    case 'last_month':
                        startDate = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                        endDate = new Date(now.getFullYear(), now.getMonth(), 0);
                        break;
                }

                if (startDate && endDate) {
                    form.querySelector('input[name="date_debut"]').value = startDate.toISOString().split('T')[0];
                    form.querySelector('input[name="date_fin"]').value = endDate.toISOString().split('T')[0];
                    form.submit();
                }
            }

            // Export des statistiques
            function exportStats() {
                const params = new URLSearchParams(window.location.search);
                params.append('export', 'excel');
                window.location.href = "{{ route('private.cultes.statistiques') }}?" + params.toString();
            }

            // Impression
            function printStats() {
                window.print();
            }

            // Animation des cartes au scroll
            function observeCards() {
                const cards = document.querySelectorAll('.hover\\:-translate-y-1');
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

                cards.forEach(card => {
                    card.style.opacity = '0';
                    // card.style.transform = 'translateY(20px)';
                    card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    observer.observe(card);
                });
            }

            // Mise à jour automatique
            function autoRefresh() {
                // Optionnel: rafraîchissement automatique toutes les 5 minutes
                setTimeout(() => {
                    if (document.visibilityState === 'visible') {
                        location.reload();
                    }
                }, 300000); // 5 minutes
            }

            // Raccourcis clavier
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey || e.metaKey) {
                    switch (e.key) {
                        case 'p':
                            e.preventDefault();
                            printStats();
                            break;
                        case 'e':
                            e.preventDefault();
                            exportStats();
                            break;
                        case 'r':
                            e.preventDefault();
                            location.reload();
                            break;
                    }
                }
            });

            // Initialisation
            document.addEventListener('DOMContentLoaded', function() {
                observeCards();
                autoRefresh();

                // Tooltip pour les raccourcis
                const shortcuts = document.createElement('div');
                shortcuts.className =
                    'fixed bottom-4 right-4 bg-slate-800 text-white text-xs p-3 rounded-lg shadow-lg opacity-0 transition-opacity duration-300';
                shortcuts.innerHTML = `
                    <div class="font-semibold mb-2">Raccourcis clavier:</div>
                    <div>Ctrl+P : Imprimer</div>
                    <div>Ctrl+E : Exporter</div>
                    <div>Ctrl+R : Actualiser</div>
                `;
                document.body.appendChild(shortcuts);

                // Afficher les raccourcis au hover du bouton aide
                let showShortcuts = false;
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'F1') {
                        e.preventDefault();
                        showShortcuts = !showShortcuts;
                        shortcuts.style.opacity = showShortcuts ? '1' : '0';
                    }
                });
            });

            // Graphiques simples avec animation
            function animateProgressBars() {
                const progressBars = document.querySelectorAll('.bg-gradient-to-r.from-blue-500');
                progressBars.forEach(bar => {
                    const width = bar.style.width;
                    bar.style.width = '0%';
                    setTimeout(() => {
                        bar.style.width = width;
                    }, 500);
                });
            }

            // Animation des compteurs
            function animateCounters() {
                const counters = document.querySelectorAll('.text-2xl.font-bold');
                counters.forEach(counter => {
                    const target = parseInt(counter.textContent.replace(/[^\d]/g, ''));
                    if (target && target > 0) {
                        let current = 0;
                        const increment = target / 50;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                counter.textContent = counter.textContent.replace(/\d+/, target);
                                clearInterval(timer);
                            } else {
                                counter.textContent = counter.textContent.replace(/\d+/, Math.floor(current));
                            }
                        }, 30);
                    }
                });
            }

            // Démarrer les animations après le chargement
            window.addEventListener('load', () => {
                setTimeout(() => {
                    animateProgressBars();
                    animateCounters();
                }, 200);
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
            }
        </style>
    @endpush
@endsection
