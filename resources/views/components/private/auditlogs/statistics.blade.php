@extends('layouts.private.main')
@section('title', 'Statistiques d\'Audit')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques d'Audit</h1>
        <p class="text-slate-500 mt-1">Analyse des activités et tendances - Période de {{ $period }} jours</p>
    </div>

    <!-- Contrôles de période -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                    Période d'Analyse
                </h2>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('private.audit.statistics', ['period' => 7]) }}" class="inline-flex items-center px-4 py-2 {{ $period == 7 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700' }} text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        7 jours
                    </a>
                    <a href="{{ route('private.audit.statistics', ['period' => 30]) }}" class="inline-flex items-center px-4 py-2 {{ $period == 30 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700' }} text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        30 jours
                    </a>
                    <a href="{{ route('private.audit.statistics', ['period' => 90]) }}" class="inline-flex items-center px-4 py-2 {{ $period == 90 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700' }} text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        90 jours
                    </a>
                    <a href="{{ route('private.audit.statistics', ['period' => 365]) }}" class="inline-flex items-center px-4 py-2 {{ $period == 365 ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-700' }} text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        1 an
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Métriques principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clipboard-list text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['total_actions']) }}</p>
                    <p class="text-sm text-slate-500">Actions totales</p>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-arrow-up"></i>
                        {{ round($stats['total_actions'] / $period, 1) }}/jour
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['recent_critical_actions']->count() }}</p>
                    <p class="text-sm text-slate-500">Actions critiques</p>
                    <p class="text-xs text-red-600 mt-1">
                        <i class="fas fa-shield-alt"></i>
                        Surveillance requise
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['most_active_users']->count() }}</p>
                    <p class="text-sm text-slate-500">Utilisateurs actifs</p>
                    <p class="text-xs text-purple-600 mt-1">
                        <i class="fas fa-user-friends"></i>
                        Derniers {{ $period }} jours
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $additionalStats['logs_by_day']->count() }}</p>
                    <p class="text-sm text-slate-500">Jours d'activité</p>
                    <p class="text-xs text-green-600 mt-1">
                        <i class="fas fa-calendar-check"></i>
                        {{ round(($additionalStats['logs_by_day']->count() / $period) * 100) }}% couverture
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Actions par type -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-pie text-amber-600 mr-2"></i>
                    Actions par Type
                </h2>
            </div>
            <div class="p-6">
                @if($stats['by_action']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['by_action']->sortDesc()->take(10) as $action => $count)
                            @php
                                $percentage = ($count / $stats['total_actions']) * 100;
                                $actionName = \App\Models\PermissionAuditLog::ACTIONS[$action] ?? $action;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-slate-700">{{ $actionName }}</span>
                                    <span class="text-sm text-slate-500">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-2 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-chart-pie text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modèles par type -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-cubes text-cyan-600 mr-2"></i>
                    Modèles par Type
                </h2>
            </div>
            <div class="p-6">
                @if($stats['by_model']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['by_model']->sortDesc() as $model => $count)
                            @php
                                $percentage = ($count / $stats['total_actions']) * 100;
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-slate-700">{{ $model }}</span>
                                    <span class="text-sm text-slate-500">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-cyan-500 to-blue-500 h-2 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-cubes text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Activité par jour -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-chart-line text-green-600 mr-2"></i>
                Activité par Jour
            </h2>
        </div>
        <div class="p-6">
            @if($additionalStats['logs_by_day']->count() > 0)
                <div class="h-64">
                    <canvas id="dailyActivityChart"></canvas>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500">Aucune donnée d'activité disponible</p>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Utilisateurs les plus actifs -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                    Utilisateurs les Plus Actifs
                </h2>
            </div>
            <div class="p-6">
                @if($stats['most_active_users']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['most_active_users']->take(10) as $index => $userStats)
                            <div class="flex items-center space-x-4 p-3 rounded-xl {{ $index < 3 ? 'bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200' : 'bg-slate-50' }}">
                                <div class="flex-shrink-0">
                                    @if($index < 3)
                                        <div class="w-10 h-10 bg-gradient-to-r
                                            {{ $index === 0 ? 'from-yellow-400 to-amber-500' : '' }}
                                            {{ $index === 1 ? 'from-slate-300 to-slate-400' : '' }}
                                            {{ $index === 2 ? 'from-amber-600 to-orange-600' : '' }}
                                            rounded-full flex items-center justify-center text-white font-bold">
                                            {{ $index + 1 }}
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center text-slate-600 font-semibold">
                                            {{ $index + 1 }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-slate-900">{{ $userStats->user->nom_complet ?? 'Utilisateur inconnu' }}</div>
                                    <div class="text-sm text-slate-500">{{ $userStats->user->email ?? 'N/A' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-slate-800">{{ $userStats->count }}</div>
                                    <div class="text-xs text-slate-500">actions</div>
                                </div>
                                @if($userStats->user)
                                    <a href="{{ route('private.audit.user.logs', $userStats->user) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-users text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Aucun utilisateur actif trouvé</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Cibles les plus fréquentes -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-bullseye text-purple-600 mr-2"></i>
                    Cibles les Plus Fréquentes
                </h2>
            </div>
            <div class="p-6">
                @if($additionalStats['top_target_users']->count() > 0)
                    <div class="space-y-4">
                        @foreach($additionalStats['top_target_users']->take(10) as $index => $targetStats)
                            <div class="flex items-center space-x-4 p-3 rounded-xl bg-slate-50">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center text-purple-600 font-semibold text-sm">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-slate-900">{{ $targetStats->targetUser->nom_complet ?? 'Utilisateur inconnu' }}</div>
                                    <div class="text-sm text-slate-500">{{ $targetStats->targetUser->email ?? 'N/A' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-slate-800">{{ $targetStats->count }}</div>
                                    <div class="text-xs text-slate-500">fois ciblé</div>
                                </div>
                                @if($targetStats->targetUser)
                                    <a href="{{ route('private.audit.user.logs', $targetStats->targetUser) }}" class="text-purple-600 hover:text-purple-800">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-bullseye text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Aucune cible fréquente trouvée</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Distribution horaire -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-clock text-indigo-600 mr-2"></i>
                Distribution Horaire de l'Activité
            </h2>
        </div>
        <div class="p-6">
            @if($additionalStats['hourly_distribution']->count() > 0)
                <div class="h-64">
                    <canvas id="hourlyDistributionChart"></canvas>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500">Aucune donnée horaire disponible</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions critiques récentes -->
    @if($stats['recent_critical_actions']->count() > 0)
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                    Actions Critiques Récentes
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($stats['recent_critical_actions']->take(10) as $criticalLog)
                        <div class="flex items-center justify-between p-4 border border-red-200 rounded-xl bg-red-50">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation text-red-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-red-900">{{ $criticalLog->action_name }}</div>
                                    <div class="text-sm text-red-700">
                                        {{ $criticalLog->user->nom_complet ?? 'Système' }} •
                                        {{ $criticalLog->created_at->diffForHumans() }}
                                    </div>
                                    <div class="text-xs text-red-600">{{ $criticalLog->description }}</div>
                                </div>
                            </div>
                            <a href="{{ route('private.audit.show', $criticalLog) }}" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-eye mr-1"></i>
                                Voir
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'activité quotidienne
    @if($additionalStats['logs_by_day']->count() > 0)
        const dailyActivityCtx = document.getElementById('dailyActivityChart').getContext('2d');
        new Chart(dailyActivityCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($additionalStats['logs_by_day'] as $dayData)
                        '{{ \Carbon\Carbon::parse($dayData->date)->format('d/m') }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Actions par jour',
                    data: [
                        @foreach($additionalStats['logs_by_day'] as $dayData)
                            {{ $dayData->count }},
                        @endforeach
                    ],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)'
                        }
                    }
                }
            }
        });
    @endif

    // Graphique de distribution horaire
    @if($additionalStats['hourly_distribution']->count() > 0)
        const hourlyCtx = document.getElementById('hourlyDistributionChart').getContext('2d');
        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: [
                    @for($i = 0; $i < 24; $i++)
                        '{{ sprintf("%02d:00", $i) }}',
                    @endfor
                ],
                datasets: [{
                    label: 'Actions par heure',
                    data: [
                        @for($i = 0; $i < 24; $i++)
                            {{ $additionalStats['hourly_distribution']->where('hour', $i)->first()->count ?? 0 }},
                        @endfor
                    ],
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: 'rgb(99, 102, 241)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)'
                        }
                    }
                }
            }
        });
    @endif

    // Animation des barres de progression
    const progressBars = document.querySelectorAll('.bg-gradient-to-r.from-amber-500, .bg-gradient-to-r.from-cyan-500');
    progressBars.forEach((bar, index) => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, index * 100);
    });
});
</script>
@endpush
@endsection
