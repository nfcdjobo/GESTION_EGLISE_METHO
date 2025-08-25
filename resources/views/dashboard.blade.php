@extends('layouts.private.main')
@section('title', 'Tableau de bord')
@section('content')

<div class="space-y-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h2 class="text-4xl font-bold text-slate-800 mb-2">Tableau de bord</h2>
            <p class="text-slate-600">Vue d'ensemble des activités de l'église - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
        </div>
    </div>

    <!-- Actions requises (Alertes) -->
    @if(count($actionsRequired) > 0)
    <div class="mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 relative">
            <button type="button" class="absolute top-4 right-4 text-yellow-600 hover:text-yellow-800 transition-colors" onclick="this.parentElement.style.display='none'">&times;</button>
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                <div>
                    <h5 class="font-semibold text-yellow-800 mb-3">Actions requises :</h5>
                    <ul class="list-disc list-inside space-y-1 text-yellow-700">
                        @foreach($actionsRequired as $action)
                            <li>
                                {{ $action['message'] }}
                                <a href="{{ $action['link'] }}" class="text-yellow-800 underline hover:text-yellow-900 font-medium">{{ $action['action'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-3xl font-bold text-slate-800">{{ number_format($stats['total_members']) }}</p>
                    <p class="text-slate-600 font-medium">Membres Actifs</p>
                    <small class="text-green-600 font-medium">+{{ $stats['new_members_month'] }} ce mois</small>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar text-white text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-3xl font-bold text-slate-800">{{ $stats['monthly_events'] }}</p>
                    <p class="text-slate-600 font-medium">Événements ce mois</p>
                    <small class="text-blue-600 font-medium">{{ $stats['weekly_meetings'] }} réunions cette semaine</small>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-money text-white text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-3xl font-bold text-slate-800">{{ number_format($stats['monthly_offerings']) }}</p>
                    <p class="text-slate-600 font-medium">Offrandes (XOF)</p>
                    <small class="text-slate-500">Ce mois</small>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tasks text-white text-2xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-3xl font-bold text-slate-800">{{ $stats['active_projects'] }}</p>
                    <p class="text-slate-600 font-medium">Projets Actifs</p>
                    <small class="text-slate-500">{{ $stats['active_classes'] }} classes ouvertes</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques des ministères -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-child text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold">Programmes Jeunesse</h3>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-bold text-2xl">{{ $ministryStats['youth_programs']['count'] }}</span>
                    <span>Programmes</span>
                </div>
                {{-- <div class="flex justify-between">
                    <span class="font-bold text-2xl">{{ $ministryStats['youth_programs']['participants'] }}</span>
                    <span>Participants</span>
                </div> --}}
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold">École Dominicale</h3>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-bold text-2xl">{{ $ministryStats['sunday_school']['classes'] }}</span>
                    <span>Classes ED</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold text-2xl">{{ $ministryStats['sunday_school']['students'] }}</span>
                    <span>Élèves</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold">Formations</h3>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-bold text-2xl">{{ $ministryStats['formations']['active'] }}</span>
                    <span>Formations</span>
                </div>
                {{-- <div class="flex justify-between">
                    <span class="font-bold text-2xl">{{ $ministryStats['formations']['participants'] }}</span>
                    <span>Apprenants</span>
                </div> --}}
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-globe text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="font-semibold">Missions</h3>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-bold text-2xl">{{ $ministryStats['missions']['programs'] }}</span>
                    <span>Missions</span>
                </div>
                <div class="flex justify-between">
                    <span class="font-bold text-2xl">{{ $ministryStats['missions']['events'] }}</span>
                    <span>Événements</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections principales -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Prochains Événements -->
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl shadow-lg border border-slate-200">
            <div class="border-b border-slate-700 p-6">
                <h3 class="text-xl font-semibold text-white">Prochains Événements</h3>
            </div>
            <div class="p-6">
                @if($upcomingEvents->count() > 0)
                    <div class="space-y-4">
                        @foreach($upcomingEvents as $event)
                            <div class="bg-slate-700/50 rounded-lg p-4 border border-slate-600">
                                <div class="flex justify-between items-start mb-2">
                                    <h6 class="font-semibold text-white">{{ $event->titre }}</h6>
                                    <small class="text-slate-300">
                                        {{ \Carbon\Carbon::parse($event->date_debut)->format('d/m/Y') }}
                                    </small>
                                </div>
                                <p class="text-slate-300 text-sm mb-2">
                                    <i class="fas fa-clock mr-2"></i>{{ $event->heure_debut ?? 'Non définie' }}
                                    <i class="fas fa-map-marker ml-4 mr-2"></i>{{ $event->lieu_nom ?? 'Lieu à définir' }}
                                </p>
                                <div class="flex justify-between items-center">
                                    <small class="text-slate-400">
                                        {{ ucfirst(str_replace('_', ' ', $event->type_evenement)) }}
                                    </small>
                                    @if($event->places_disponibles)
                                        <small class="text-slate-300">
                                            {{ $event->nombre_inscrits }}/{{ $event->places_disponibles }} inscrits
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-slate-300">Aucun événement prévu</p>
                @endif
            </div>
        </div>

        <!-- Indicateurs de performance -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200">
            <div class="border-b border-slate-200 p-6">
                <h3 class="text-xl font-semibold text-slate-800">Indicateurs de Performance</h3>
            </div>
            <div class="p-6 space-y-6">
                <!-- Croissance des membres -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-slate-700 font-medium">Croissance Membres</span>
                        <span class="text-sm font-semibold {{ $performanceIndicators['members_growth'] >= 0 ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $performanceIndicators['members_growth'] > 0 ? '+' : '' }}{{ $performanceIndicators['members_growth'] }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-3">
                        <div class="h-3 rounded-full {{ $performanceIndicators['members_growth'] >= 0 ? 'bg-green-500' : 'bg-yellow-500' }} transition-all duration-500"
                             style="width: {{ min(abs($performanceIndicators['members_growth']), 100) }}%;">
                        </div>
                    </div>
                </div>

                <!-- Évolution offrandes -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-slate-700 font-medium">Évolution Offrandes</span>
                        <span class="text-sm font-semibold {{ $performanceIndicators['offering_growth'] >= 0 ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $performanceIndicators['offering_growth'] > 0 ? '+' : '' }}{{ $performanceIndicators['offering_growth'] }}%
                        </span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-3">
                        <div class="h-3 rounded-full {{ $performanceIndicators['offering_growth'] >= 0 ? 'bg-green-500' : 'bg-yellow-500' }} transition-all duration-500"
                             style="width: {{ min(abs($performanceIndicators['offering_growth']), 100) }}%;">
                        </div>
                    </div>
                </div>

                <!-- Taux de présence -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-slate-700 font-medium">Taux de Présence</span>
                        <span class="text-sm font-semibold text-blue-600">{{ $performanceIndicators['attendance_rate'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-3">
                        <div class="bg-blue-500 h-3 rounded-full transition-all duration-500"
                             style="width: {{ $performanceIndicators['attendance_rate'] }}%;">
                        </div>
                    </div>
                </div>

                <!-- Completion projets -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-slate-700 font-medium">Avancement Projets</span>
                        <span class="text-sm font-semibold text-indigo-600">{{ $performanceIndicators['project_completion'] }}%</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-3">
                        <div class="bg-indigo-500 h-3 rounded-full transition-all duration-500"
                             style="width: {{ $performanceIndicators['project_completion'] }}%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section du bas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Réunions du jour -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200">
            <div class="border-b border-slate-200 p-6">
                <h3 class="text-xl font-semibold text-slate-800 flex items-center">
                    <i class="fas fa-calendar text-blue-600 mr-3"></i>
                    {{ \Carbon\Carbon::now()->format('d F Y') }}
                </h3>
                <p class="text-slate-600 text-sm mt-1">Réunions d'aujourd'hui</p>
            </div>
            <div class="p-6">
                @forelse($todayMeetings as $meeting)
                    <div class="border-b border-slate-200 last:border-b-0 py-4 last:pb-0">
                        <div class="flex justify-between items-start mb-2">
                            <a href="#" class="text-slate-800 font-medium hover:text-blue-600 transition-colors">{{ $meeting->titre }}</a>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $meeting->statut == 'confirmee' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($meeting->statut) }}
                            </span>
                        </div>
                        <div class="text-sm font-semibold text-slate-700 mb-1">
                            {{ $meeting->heure_debut_prevue ?? 'Non définie' }}
                        </div>
                        @if($meeting->lieu)
                            <div class="text-sm text-slate-600 mb-1">
                                <i class="fas fa-map-marker mr-2"></i>{{ $meeting->lieu }}
                            </div>
                        @endif
                        @if($meeting->organisateur)
                            <div class="text-sm text-slate-600">
                                <i class="fas fa-user mr-2"></i>{{ $meeting->organisateur }}
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-slate-500">Aucune réunion prévue aujourd'hui</p>
                @endforelse
            </div>
            <div class="border-t border-slate-200 p-4 text-center">
                <a href="{{ route('reunions.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Voir toutes
                </a>
            </div>
        </div>

        <!-- Dernières transactions -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200">
            <div class="border-b border-slate-200 p-6">
                <h3 class="text-xl font-semibold text-slate-800 flex items-center">
                    <i class="fas fa-money text-green-600 mr-3"></i>
                    Transactions Récentes
                </h3>
                <p class="text-slate-600 text-sm mt-1">Dernières offrandes et dons</p>
            </div>
            <div class="p-6">
                @forelse($recentTransactions->take(4) as $transaction)
                    <div class="flex items-center py-3 border-b border-slate-200 last:border-b-0">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-money text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-slate-800">{{ $transaction->donateur }}</div>
                            <div class="text-sm text-slate-600">
                                {{ number_format($transaction->montant) }} {{ $transaction->devise }}
                                - {{ ucfirst(str_replace('_', ' ', $transaction->type_transaction)) }}
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ \Carbon\Carbon::parse($transaction->date_transaction)->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-slate-500">Aucune transaction récente</p>
                @endforelse
            </div>
            <div class="border-t border-slate-200 p-4 text-center">
                <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    Voir toutes
                </a>
            </div>
        </div>
    </div>

    <!-- Prochains cultes -->
    @if($upcomingCultes->count() > 0)
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg border border-slate-200">
            <div class="border-b border-slate-200 p-6">
                <h3 class="text-xl font-semibold text-slate-800">Prochains Cultes</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($upcomingCultes as $culte)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h5 class="font-semibold text-blue-800 mb-3">{{ $culte->titre }}</h5>
                            <div class="space-y-2 text-sm">
                                <p class="text-blue-700">
                                    <i class="fas fa-calendar mr-2"></i>{{ \Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y') }}
                                    <i class="fas fa-clock ml-4 mr-2"></i>{{ $culte->heure_debut ?? 'Non définie' }}
                                </p>
                                <p class="text-blue-700">
                                    <i class="fas fa-tag mr-2"></i>{{ ucfirst(str_replace('_', ' ', $culte->type_culte)) }}
                                </p>
                                @if($culte->predicateur)
                                    <p class="text-blue-600">
                                        <i class="fas fa-user mr-2"></i>Prédicateur: {{ $culte->predicateur }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Projets actifs -->
    @if($activeProjects->count() > 0)
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg border border-slate-200">
            <div class="border-b border-slate-200 p-6">
                <h3 class="text-xl font-semibold text-slate-800">Projets en Cours</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="text-left py-3 px-4 font-semibold text-slate-700">Projet</th>
                                <th class="text-left py-3 px-4 font-semibold text-slate-700">Responsable</th>
                                <th class="text-left py-3 px-4 font-semibold text-slate-700">Budget</th>
                                <th class="text-left py-3 px-4 font-semibold text-slate-700">Avancement</th>
                                <th class="text-left py-3 px-4 font-semibold text-slate-700">Priorité</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeProjects as $project)
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                    <td class="py-3 px-4 text-slate-800">{{ $project->nom_projet }}</td>
                                    <td class="py-3 px-4 text-slate-600">{{ $project->responsable ?? 'Non assigné' }}</td>
                                    <td class="py-3 px-4">
                                        <div class="text-slate-800">
                                            {{ number_format($project->budget_collecte) }} / {{ number_format($project->budget_prevu) }} XOF
                                        </div>
                                        <div class="text-sm text-slate-500">
                                            {{ $project->budget_prevu > 0 ? round(($project->budget_collecte / $project->budget_prevu) * 100, 1) : 0 }}% financé
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="w-full bg-slate-200 rounded-full h-4">
                                            <div class="bg-blue-500 h-4 rounded-full flex items-center justify-center text-xs text-white font-medium"
                                                 style="width: {{ $project->pourcentage_completion }}%;">
                                                {{ $project->pourcentage_completion }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $project->priorite == 'haute' ? 'bg-red-100 text-red-800' : ($project->priorite == 'normale' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($project->priorite) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Annonces actives -->
    @if($activeAnnouncements->count() > 0)
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg border border-slate-200">
            <div class="border-b border-slate-200 p-6">
                <h3 class="text-xl font-semibold text-slate-800">Annonces Actives</h3>
            </div>
            <div class="p-6 space-y-4">
                @foreach($activeAnnouncements as $annonce)
                    <div class="border-l-4 {{ $annonce->niveau_priorite == 'critique' ? 'border-red-500 bg-red-50' : ($annonce->niveau_priorite == 'urgent' ? 'border-yellow-500 bg-yellow-50' : 'border-blue-500 bg-blue-50') }} rounded-lg p-4 relative">
                        <button type="button" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors" onclick="this.parentElement.style.display='none'">&times;</button>
                        <div class="pr-8">
                            <h5 class="font-semibold {{ $annonce->niveau_priorite == 'critique' ? 'text-red-800' : ($annonce->niveau_priorite == 'urgent' ? 'text-yellow-800' : 'text-blue-800') }} mb-2">
                                {{ $annonce->titre }}
                            </h5>
                            @if($annonce->resume_court)
                                <p class="{{ $annonce->niveau_priorite == 'critique' ? 'text-red-700' : ($annonce->niveau_priorite == 'urgent' ? 'text-yellow-700' : 'text-blue-700') }} mb-2">
                                    {{ $annonce->resume_court }}
                                </p>
                            @endif
                            <div class="text-sm {{ $annonce->niveau_priorite == 'critique' ? 'text-red-600' : ($annonce->niveau_priorite == 'urgent' ? 'text-yellow-600' : 'text-blue-600') }} space-y-1">
                                @if($annonce->date_evenement)
                                    <div><i class="fas fa-calendar mr-2"></i>{{ \Carbon\Carbon::parse($annonce->date_evenement)->format('d/m/Y') }}</div>
                                @endif
                                <div>
                                    <i class="fas fa-tag mr-2"></i>{{ ucfirst(str_replace('_', ' ', $annonce->type_annonce)) }}
                                    @if($annonce->contact)
                                        | <i class="fas fa-user mr-1 ml-3"></i>{{ $annonce->contact }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des compteurs au chargement
    const counters = document.querySelectorAll('.text-3xl');

    counters.forEach(counter => {
        const target = parseInt(counter.textContent.replace(/,/g, ''));
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target.toLocaleString();
                clearInterval(timer);
            } else {
                counter.textContent = Math.floor(current).toLocaleString();
            }
        }, 20);
    });

    // Actualisation automatique de la page toutes les 10 minutes
    setTimeout(function() {
        location.reload();
    }, 600000);
});
</script>


@endsection
