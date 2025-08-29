@extends('layouts.private.main')
@section('title', 'Tableau de Bord des Rôles')

@section('content')
<div class="midde_cont">
    <div class="container-fluid">
        <div class="row column_title">
            <div class="col-md-12">
                <div class="page_title">
                    <h2>Tableau de Bord - Gestion des Rôles</h2>
                    <p class="text-muted">Vue d'ensemble du système de rôles et permissions - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row">
            <div class="col-md-12">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_revenue">
                        <div class="row text-center">
                            <div class="col-md-2">
                                <a href="{{ route('private.roles.create') }}" class="dashboard-action">
                                    <div class="action-icon">
                                        <i class="fa fa-plus-circle fa-2x text-primary"></i>
                                    </div>
                                    <h6 class="mt-2">Nouveau Rôle</h6>
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('private.roles.users') }}" class="dashboard-action">
                                    <div class="action-icon">
                                        <i class="fa fa-users fa-2x text-success"></i>
                                    </div>
                                    <h6 class="mt-2">Gérer Utilisateurs</h6>
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('private.roles.reports') }}" class="dashboard-action">
                                    <div class="action-icon">
                                        <i class="fa fa-chart-bar fa-2x text-info"></i>
                                    </div>
                                    <h6 class="mt-2">Rapports</h6>
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('private.roles.audit') }}" class="dashboard-action">
                                    <div class="action-icon">
                                        <i class="fa fa-history fa-2x text-warning"></i>
                                    </div>
                                    <h6 class="mt-2">Audit</h6>
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('private.roles.import-export') }}" class="dashboard-action">
                                    <div class="action-icon">
                                        <i class="fa fa-exchange fa-2x text-secondary"></i>
                                    </div>
                                    <h6 class="mt-2">Import/Export</h6>
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('private.roles.hierarchy') }}" class="dashboard-action">
                                    <div class="action-icon">
                                        <i class="fa fa-sitemap fa-2x text-dark"></i>
                                    </div>
                                    <h6 class="mt-2">Hiérarchie</h6>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Métriques principales -->
        <div class="row">
            <div class="col-md-3">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0 text-center">
                            <div class="metric-icon mb-2">
                                <i class="fa fa-users fa-3x text-primary"></i>
                            </div>
                            <h2 class="metric-value text-primary">{{ $metrics['total_roles'] ?? 0 }}</h2>
                            <p class="metric-label">Rôles Total</p>
                            <div class="metric-trend">
                                @if(($metrics['roles_growth'] ?? 0) >= 0)
                                    <small class="text-success">
                                        <i class="fa fa-arrow-up"></i> +{{ $metrics['roles_growth'] ?? 0 }}% ce mois
                                    </small>
                                @else
                                    <small class="text-danger">
                                        <i class="fa fa-arrow-down"></i> {{ $metrics['roles_growth'] ?? 0 }}% ce mois
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0 text-center">
                            <div class="metric-icon mb-2">
                                <i class="fa fa-user-check fa-3x text-success"></i>
                            </div>
                            <h2 class="metric-value text-success">{{ $metrics['active_users'] ?? 0 }}</h2>
                            <p class="metric-label">Utilisateurs Actifs</p>
                            <div class="metric-trend">
                                <small class="text-muted">
                                    {{ $metrics['coverage_rate'] ?? 0 }}% de couverture
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0 text-center">
                            <div class="metric-icon mb-2">
                                <i class="fa fa-key fa-3x text-warning"></i>
                            </div>
                            <h2 class="metric-value text-warning">{{ $metrics['total_permissions'] ?? 0 }}</h2>
                            <p class="metric-label">Permissions</p>
                            <div class="metric-trend">
                                <small class="text-muted">
                                    Moyenne {{ $metrics['avg_permissions_per_role'] ?? 0 }} par rôle
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0 text-center">
                            <div class="metric-icon mb-2">
                                <i class="fa fa-exclamation-triangle fa-3x text-danger"></i>
                            </div>
                            <h2 class="metric-value text-danger">{{ $metrics['issues_count'] ?? 0 }}</h2>
                            <p class="metric-label">Alertes Actives</p>
                            <div class="metric-trend">
                                @if(($metrics['issues_count'] ?? 0) > 0)
                                    <small class="text-danger">
                                        Attention requise
                                    </small>
                                @else
                                    <small class="text-success">
                                        <i class="fa fa-check"></i> Système sain
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques et tendances -->
        <div class="row">
            <div class="col-md-8">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0 d-flex justify-content-between align-items-center">
                            <h2>Évolution des Rôles et Utilisateurs</h2>
                            <div class="chart-controls">
                                <select class="form-select form-select-sm" id="chartPeriod" onchange="updateCharts()">
                                    <option value="7">7 derniers jours</option>
                                    <option value="30" selected>30 derniers jours</option>
                                    <option value="90">3 derniers mois</option>
                                    <option value="365">Dernière année</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <canvas id="evolutionChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Répartition par Niveau</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <canvas id="levelDistributionChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activité récente et alertes -->
        <div class="row">
            <div class="col-md-6">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0 d-flex justify-content-between align-items-center">
                            <h2>Activité Récente</h2>
                            <a href="{{ route('private.roles.audit') }}" class="btn btn-sm btn-outline-primary">
                                Voir tout <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <div class="activity-timeline">
                            @forelse($recentActivity ?? [] as $activity)
                                <div class="timeline-item d-flex mb-3">
                                    <div class="timeline-marker me-3">
                                        <div class="timeline-icon
                                            @switch($activity->action)
                                                @case('created') bg-success @break
                                                @case('updated') bg-warning @break
                                                @case('deleted') bg-danger @break
                                                @case('assigned') bg-info @break
                                                @default bg-secondary @break
                                            @endswitch rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 32px; height: 32px;">
                                            @switch($activity->action)
                                                @case('created')
                                                    <i class="fa fa-plus text-white"></i>
                                                    @break
                                                @case('updated')
                                                    <i class="fa fa-edit text-white"></i>
                                                    @break
                                                @case('deleted')
                                                    <i class="fa fa-trash text-white"></i>
                                                    @break
                                                @case('assigned')
                                                    <i class="fa fa-user-plus text-white"></i>
                                                    @break
                                                @default
                                                    <i class="fa fa-circle text-white"></i>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                    <div class="timeline-content flex-grow-1">
                                        <div class="timeline-header d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong>{{ $activity->user?->nom_complet ?? 'Système' }}</strong>
                                                <span class="action-description">{{ $activity->description }}</span>
                                            </div>
                                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if($activity->target)
                                            <div class="timeline-target">
                                                <small class="text-muted">
                                                    {{ class_basename($activity->target_type) }}:
                                                    <strong>{{ $activity->target_name }}</strong>
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fa fa-clock fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Aucune activité récente</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0 d-flex justify-content-between align-items-center">
                            <h2>Alertes et Notifications</h2>
                            <button class="btn btn-sm btn-outline-secondary" onclick="refreshAlerts()">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <div class="alerts-container">
                            @forelse($alerts ?? [] as $alert)
                                <div class="alert alert-{{ $alert->type }} alert-dismissible fade show" role="alert">
                                    <div class="d-flex align-items-start">
                                        <div class="alert-icon me-2">
                                            @switch($alert->type)
                                                @case('danger')
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                    @break
                                                @case('warning')
                                                    <i class="fa fa-exclamation-circle"></i>
                                                    @break
                                                @case('info')
                                                    <i class="fa fa-info-circle"></i>
                                                    @break
                                                @case('success')
                                                    <i class="fa fa-check-circle"></i>
                                                    @break
                                                @default
                                                    <i class="fa fa-bell"></i>
                                                    @break
                                            @endswitch
                                        </div>
                                        <div class="flex-grow-1">
                                            <strong>{{ $alert->title }}</strong>
                                            <div>{{ $alert->message }}</div>
                                            <small class="text-muted">{{ $alert->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" onclick="dismissAlert('{{ $alert->id }}')"></button>
                                </div>
                            @empty
                                <div class="alert alert-success text-center" role="alert">
                                    <i class="fa fa-check-circle fa-2x mb-2"></i>
                                    <h6>Aucune alerte active</h6>
                                    <p class="mb-0">Votre système de rôles fonctionne correctement</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top rôles et analyses -->
        <div class="row">
            <div class="col-md-4">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Rôles les Plus Utilisés</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <div class="top-roles-list">
                            @forelse($topRoles ?? [] as $index => $role)
                                <div class="role-rank-item d-flex align-items-center mb-3 p-2 border rounded">
                                    <div class="rank-badge me-3">
                                        <span class="badge
                                            @if($index === 0) bg-warning
                                            @elseif($index === 1) bg-secondary
                                            @elseif($index === 2) bg-info
                                            @else bg-light text-dark
                                            @endif">
                                            #{{ $index + 1 }}
                                        </span>
                                    </div>
                                    <div class="role-info flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $role->name }}</strong>
                                                @if($role->is_system_role)
                                                    <i class="fa fa-lock text-warning ms-1"></i>
                                                @endif
                                                <br><small class="text-muted">{{ $role->users_count }} utilisateur(s)</small>
                                            </div>
                                            <div class="usage-progress">
                                                @php
                                                    $maxUsers = $topRoles->max('users_count') ?: 1;
                                                    $percentage = ($role->users_count / $maxUsers) * 100;
                                                @endphp
                                                <div class="progress" style="width: 60px; height: 6px;">
                                                    <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ round($percentage) }}%</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fa fa-chart-bar fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Aucune donnée d'utilisation</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Santé du Système</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <div class="system-health">
                            <div class="health-metric mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Taux d'utilisation des rôles</span>
                                    <strong>{{ $systemHealth['role_usage_rate'] ?? 0 }}%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: {{ $systemHealth['role_usage_rate'] ?? 0 }}%"></div>
                                </div>
                            </div>

                            <div class="health-metric mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Couverture des permissions</span>
                                    <strong>{{ $systemHealth['permission_coverage'] ?? 0 }}%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-info" style="width: {{ $systemHealth['permission_coverage'] ?? 0 }}%"></div>
                                </div>
                            </div>

                            <div class="health-metric mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Efficacité des assignations</span>
                                    <strong>{{ $systemHealth['assignment_efficiency'] ?? 0 }}%</strong>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: {{ $systemHealth['assignment_efficiency'] ?? 0 }}%"></div>
                                </div>
                            </div>

                            <div class="health-score text-center mt-4">
                                @php
                                    $avgHealth = round(($systemHealth['role_usage_rate'] + $systemHealth['permission_coverage'] + $systemHealth['assignment_efficiency']) / 3);
                                @endphp
                                <div class="score-circle mx-auto mb-2" style="width: 80px; height: 80px;">
                                    <div class="circular-progress" data-percentage="{{ $avgHealth }}">
                                        <span class="percentage">{{ $avgHealth }}%</span>
                                    </div>
                                </div>
                                <h6 class="
                                    @if($avgHealth >= 80) text-success
                                    @elseif($avgHealth >= 60) text-warning
                                    @else text-danger
                                    @endif">
                                    @if($avgHealth >= 80) Excellent
                                    @elseif($avgHealth >= 60) Bon
                                    @else À améliorer
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Actions Recommandées</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <div class="recommendations">
                            @forelse($recommendations ?? [] as $recommendation)
                                <div class="recommendation-item border-start border-4
                                    @if($recommendation->priority === 'high') border-danger
                                    @elseif($recommendation->priority === 'medium') border-warning
                                    @else border-info
                                    @endif ps-3 mb-3">
                                    <div class="recommendation-header d-flex justify-content-between align-items-start mb-1">
                                        <strong class="
                                            @if($recommendation->priority === 'high') text-danger
                                            @elseif($recommendation->priority === 'medium') text-warning
                                            @else text-info
                                            @endif">
                                            {{ $recommendation->title }}
                                        </strong>
                                        <span class="badge
                                            @if($recommendation->priority === 'high') bg-danger
                                            @elseif($recommendation->priority === 'medium') bg-warning
                                            @else bg-info
                                            @endif">
                                            {{ ucfirst($recommendation->priority) }}
                                        </span>
                                    </div>
                                    <p class="small text-muted mb-2">{{ $recommendation->description }}</p>
                                    @if($recommendation->action_url)
                                        <a href="{{ $recommendation->action_url }}" class="btn btn-sm btn-outline-primary">
                                            {{ $recommendation->action_label ?? 'Agir' }}
                                        </a>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="fa fa-thumbs-up fa-2x text-success mb-2"></i>
                                    <h6 class="text-success">Système Optimisé</h6>
                                    <p class="text-muted small">Aucune action recommandée pour le moment</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Raccourcis utiles -->
        <div class="row">
            <div class="col-md-12">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Raccourcis Utiles</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="shortcut-card text-center p-3 border rounded">
                                    <i class="fa fa-plus-circle fa-2x text-primary mb-2"></i>
                                    <h6>Création Rapide</h6>
                                    <div class="shortcut-buttons">
                                        <a href="{{ route('private.roles.create') }}" class="btn btn-sm btn-primary mb-1">Nouveau Rôle</a>
                                        <button class="btn btn-sm btn-outline-primary" onclick="openBulkAssign()">Assignation Multiple</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="shortcut-card text-center p-3 border rounded">
                                    <i class="fa fa-search fa-2x text-info mb-2"></i>
                                    <h6>Recherche Avancée</h6>
                                    <div class="shortcut-buttons">
                                        <button class="btn btn-sm btn-info mb-1" onclick="openAdvancedSearch()">Rechercher</button>
                                        <a href="{{ route('private.roles.compare') }}" class="btn btn-sm btn-outline-info">Comparer</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="shortcut-card text-center p-3 border rounded">
                                    <i class="fa fa-download fa-2x text-success mb-2"></i>
                                    <h6>Export/Import</h6>
                                    <div class="shortcut-buttons">
                                        <button class="btn btn-sm btn-success mb-1" onclick="quickExport()">Export Rapide</button>
                                        <a href="{{ route('private.roles.import-export') }}" class="btn btn-sm btn-outline-success">Gestion Masse</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="shortcut-card text-center p-3 border rounded">
                                    <i class="fa fa-cog fa-2x text-secondary mb-2"></i>
                                    <h6>Configuration</h6>
                                    <div class="shortcut-buttons">
                                        <button class="btn btn-sm btn-secondary mb-1" onclick="openSettings()">Paramètres</button>
                                        <a href="{{ route('private.roles.audit') }}" class="btn btn-sm btn-outline-secondary">Audit</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
// Données pour les graphiques
const dashboardData = {
    evolution: @json($evolutionData ?? []),
    levelDistribution: @json($levelDistributionData ?? [])
};

// Graphique d'évolution
const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
const evolutionChart = new Chart(evolutionCtx, {
    type: 'line',
    data: {
        labels: dashboardData.evolution.labels || [],
        datasets: [
            {
                label: 'Rôles créés',
                data: dashboardData.evolution.roles || [],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Utilisateurs assignés',
                data: dashboardData.evolution.users || [],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
            },
            {
                label: 'Permissions accordées',
                data: dashboardData.evolution.permissions || [],
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                tension: 0.4,
                fill: false
            }
        ]
    },
    options: {
        responsive: true,
        interaction: {
            intersect: false,
        },
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                position: 'top'
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        }
    }
});

// Graphique de distribution par niveau
const levelCtx = document.getElementById('levelDistributionChart').getContext('2d');
new Chart(levelCtx, {
    type: 'doughnut',
    data: {
        labels: ['Super Admin (100)', 'Admin (80-99)', 'Direction (60-79)', 'Responsables (40-59)', 'Membres Actifs (20-39)', 'Membres (10-19)', 'Visiteurs (0-9)'],
        datasets: [{
            data: dashboardData.levelDistribution || [0, 0, 0, 0, 0, 0, 0],
            backgroundColor: [
                '#dc3545', '#ffc107', '#17a2b8', '#007bff',
                '#28a745', '#6c757d', '#343a40'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: true,
                    padding: 15,
                    font: {
                        size: 11
                    }
                }
            }
        }
    }
});

// Mise à jour des graphiques
function updateCharts() {
    // const period = document.getElementById('chartPeriod').value;

    // fetch(`{..{ route('private.roles.dashboard') }..}?period=${period}&ajax=1`, {
    //     headers: {
    //         'Accept': 'application/json',
    //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //     }
    // })
    // .then(response => response.json())
    // .then(data => {
    //     // Mettre à jour les données du graphique d'évolution
    //     evolutionChart.data.labels = data.evolution.labels;
    //     evolutionChart.data.datasets[0].data = data.evolution.roles;
    //     evolutionChart.data.datasets[1].data = data.evolution.users;
    //     evolutionChart.data.datasets[2].data = data.evolution.permissions;
    //     evolutionChart.update();
    // })
    // .catch(error => {
    //     console.error('Erreur lors de la mise à jour:', error);
    // });
}

// Actualisation des alertes
function refreshAlerts() {
    // fetch('{..{ route("private.roles.dashboard") }..}?alerts=1', {
    //     headers: {
    //         'Accept': 'application/json',
    //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //     }
    // })
    // .then(response => response.json())
    // .then(data => {
    //     document.querySelector('.alerts-container').innerHTML = data.alerts_html;
    // })
    // .catch(error => {
    //     console.error('Erreur lors de l\'actualisation des alertes:', error);
    // });
}

// Supprimer une alerte
function dismissAlert(alertId) {
    // fetch(`/admin/alerts/${alertId}/dismiss`, {
    //     method: 'POST',
    //     headers: {
    //         'X-CSRF-TOKEN': '{{ csrf_token() }}',
    //         'Accept': 'application/json'
    //     }
    // })
    // .then(response => response.json())
    // .then(data => {
    //     if (!data.success) {
    //         console.error('Erreur lors de la suppression de l\'alerte');
    //     }
    // })
    // .catch(error => {
    //     console.error('Erreur:', error);
    // });
}

// Fonctions des raccourcis
function openBulkAssign() {
    window.location.href = '{{ route("private.roles.users") }}';
}

function openAdvancedSearch() {
    // Ouvrir modal de recherche avancée ou rediriger
    window.location.href = '{{ route("private.roles.index") }}?advanced=1';
}

function quickExport() {
    window.location.href = '{{ route("private.roles.export") }}?format=csv&quick=1';
}

function openSettings() {
    // Ouvrir modal de paramètres ou rediriger vers page de configuration
    alert('Page de paramètres à implémenter');
}

// Animation du score de santé
function animateHealthScore() {
    const circles = document.querySelectorAll('.circular-progress');
    circles.forEach(circle => {
        const percentage = circle.dataset.percentage;
        // Animation CSS ou JS pour le cercle de progression
        circle.style.setProperty('--percentage', percentage);
    });
}

// Actualisation automatique des métriques
function refreshMetrics() {
    // fetch('{...{ route("private.roles.dashboard") }...}?metrics=1', {
    //     headers: {
    //         'Accept': 'application/json',
    //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
    //     }
    // })
    // .then(response => response.json())
    // .then(data => {
    //     // Mettre à jour les métriques principales
    //     Object.keys(data.metrics).forEach(key => {
    //         const element = document.querySelector(`[data-metric="${key}"]`);
    //         if (element) {
    //             element.textContent = data.metrics[key];
    //         }
    //     });
    // })
    // .catch(error => {
    //     console.error('Erreur lors de l\'actualisation des métriques:', error);
    // });
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    animateHealthScore();

    // Actualisation automatique toutes les 5 minutes
    setInterval(refreshMetrics, 300000);

    // Actualisation des alertes toutes les 2 minutes
    setInterval(refreshAlerts, 120000);
});

// Gestion du responsive pour les graphiques
window.addEventListener('resize', function() {
    evolutionChart.resize();
});
</script>

@push('styles')
<style>
.dashboard-action {
    display: block;
    text-decoration: none;
    color: inherit;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    padding: 1rem;
    border-radius: 0.5rem;
}

.dashboard-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    color: inherit;
    text-decoration: none;
}

.metric-icon {
    opacity: 0.8;
}

.metric-value {
    font-size: 2.5rem;
    font-weight: bold;
    margin: 0;
}

.metric-label {
    font-size: 0.9rem;
    margin: 0;
    color: #6c757d;
}

.metric-trend {
    font-size: 0.8rem;
    margin-top: 0.25rem;
}

.timeline-item {
    position: relative;
}

.timeline-marker {
    flex-shrink: 0;
}

.timeline-icon {
    font-size: 0.8rem;
}

.activity-timeline {
    max-height: 400px;
    overflow-y: auto;
}

.alert-icon {
    font-size: 1.1rem;
    margin-top: 2px;
}

.role-rank-item {
    transition: background-color 0.2s ease;
}

.role-rank-item:hover {
    background-color: #f8f9fa;
}

.usage-progress {
    text-align: center;
}

.health-metric .progress {
    height: 8px;
}

.score-circle {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.circular-progress {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: conic-gradient(#28a745 0deg, #28a745 calc(var(--percentage, 0) * 3.6deg), #e9ecef calc(var(--percentage, 0) * 3.6deg));
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.circular-progress::before {
    content: '';
    position: absolute;
    width: 60%;
    height: 60%;
    background: white;
    border-radius: 50%;
}

.circular-progress .percentage {
    position: relative;
    z-index: 1;
    font-weight: bold;
    font-size: 0.9rem;
}

.recommendation-item {
    position: relative;
}

.shortcut-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.shortcut-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.shortcut-buttons .btn {
    width: 100%;
    margin-bottom: 0.25rem;
}

.chart-controls .form-select {
    width: auto;
    min-width: 150px;
}

@media (max-width: 768px) {
    .metric-value {
        font-size: 2rem;
    }

    .dashboard-action {
        margin-bottom: 1rem;
    }

    .shortcut-card {
        margin-bottom: 1rem;
    }

    .timeline-item {
        margin-bottom: 1rem;
    }

    .role-rank-item {
        margin-bottom: 0.5rem;
    }
}

/* Animation pour les métriques */
@keyframes countUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.metric-value {
    animation: countUp 0.6s ease-out;
}

/* Responsive pour les alertes */
.alerts-container {
    max-height: 400px;
    overflow-y: auto;
}

.alerts-container .alert {
    margin-bottom: 0.5rem;
}

.alerts-container .alert:last-child {
    margin-bottom: 0;
}
</style>
@endpush
@endsection
