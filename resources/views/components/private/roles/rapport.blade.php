@extends('layouts.private.main')
@section('title', 'Rapports des Rôles')

@section('content')
<div class="midde_cont">
    <div class="container-fluid">
        <div class="row column_title">
            <div class="col-md-12">
                <div class="page_title">
                    <h2>Rapports et Statistiques des Rôles</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('private.roles.index') }}">Rôles</a></li>
                            <li class="breadcrumb-item active">Rapports</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Filtres de période -->
        <div class="row">
            <div class="col-md-12">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Filtres et Paramètres</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <form method="GET" class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Période</label>
                                <select name="period" class="form-control" onchange="this.form.submit()">
                                    <option value="7" {{ request('period') == '7' ? 'selected' : '' }}>7 derniers jours</option>
                                    <option value="30" {{ request('period') == '30' ? 'selected' : '' }}>30 derniers jours</option>
                                    <option value="90" {{ request('period') == '90' ? 'selected' : '' }}>3 derniers mois</option>
                                    <option value="365" {{ request('period') == '365' ? 'selected' : '' }}>Dernière année</option>
                                    <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>Toute la période</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Type de rôle</label>
                                <select name="role_type" class="form-control" onchange="this.form.submit()">
                                    <option value="all" {{ request('role_type') == 'all' ? 'selected' : '' }}>Tous les types</option>
                                    <option value="system" {{ request('role_type') == 'system' ? 'selected' : '' }}>Système</option>
                                    <option value="custom" {{ request('role_type') == 'custom' ? 'selected' : '' }}>Personnalisés</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date de début</label>
                                <input type="date" name="start_date" class="form-control"
                                       value="{{ request('start_date') }}" onchange="this.form.submit()">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date de fin</label>
                                <input type="date" name="end_date" class="form-control"
                                       value="{{ request('end_date') }}" onchange="this.form.submit()">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI principaux -->
        <div class="row">
            <div class="col-md-3">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0 text-center">
                            <div class="kpi-icon mb-2">
                                <i class="fa fa-users fa-3x text-primary"></i>
                            </div>
                            <h2 class="text-primary">{{ $totalRoles ?? 0 }}</h2>
                            <p>Rôles Totaux</p>
                            <small class="text-success">
                                <i class="fa fa-arrow-up"></i>
                                +{{ $newRolesThisPeriod ?? 0 }} cette période
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0 text-center">
                            <div class="kpi-icon mb-2">
                                <i class="fa fa-user-check fa-3x text-success"></i>
                            </div>
                            <h2 class="text-success">{{ $activeUsers ?? 0 }}</h2>
                            <p>Membress Actifs</p>
                            <small class="text-info">
                                Taux d'utilisation: {{ $usageRate ?? 0 }}%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0 text-center">
                            <div class="kpi-icon mb-2">
                                <i class="fa fa-key fa-3x text-warning"></i>
                            </div>
                            <h2 class="text-warning">{{ $totalPermissions ?? 0 }}</h2>
                            <p>Permissions Totales</p>
                            <small class="text-muted">
                                Moyenne: {{ $avgPermissionsPerRole ?? 0 }} par rôle
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0 text-center">
                            <div class="kpi-icon mb-2">
                                <i class="fa fa-exclamation-triangle fa-3x text-danger"></i>
                            </div>
                            <h2 class="text-danger">{{ $unusedRoles ?? 0 }}</h2>
                            <p>Rôles Inutilisés</p>
                            <small class="text-danger">
                                {{ $unusedRolesPercentage ?? 0 }}% du total
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques de distribution -->
        <div class="row">
            <div class="col-md-6">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Distribution par Niveau Hiérarchique</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <canvas id="levelDistributionChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Répartition Système vs Personnalisé</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <canvas id="typeDistributionChart" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Évolution dans le temps -->
        <div class="row">
            <div class="col-md-12">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Évolution des Rôles et Membress</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <canvas id="evolutionChart" width="400" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top rôles et statistiques détaillées -->
        <div class="row">
            <div class="col-md-6">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Rôles les Plus Utilisés</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Rang</th>
                                        <th>Rôle</th>
                                        <th>Membress</th>
                                        <th>Permissions</th>
                                        <th>Utilisation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topRoles ?? [] as $index => $role)
                                        <tr>
                                            <td>
                                                <span class="badge
                                                    @if($index === 0) bg-warning
                                                    @elseif($index === 1) bg-secondary
                                                    @elseif($index === 2) bg-info
                                                    @else bg-light text-dark
                                                    @endif">
                                                    #{{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($role->is_system_role)
                                                        <i class="fa fa-lock text-warning me-1"></i>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $role->name }}</strong>
                                                        <br><small class="text-muted">{{ $role->slug }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $role->users_count }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $role->permissions_count }}</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar"
                                                         style="width: {{ ($role->users_count / max($totalUsers ?? 1, 1)) * 100 }}%">
                                                        {{ round(($role->users_count / max($totalUsers ?? 1, 1)) * 100, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Aucune donnée disponible</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Permissions les Plus Communes</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Permission</th>
                                        <th>Rôles</th>
                                        <th>Couverture</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topPermissions ?? [] as $permission)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $permission->name }}</strong>
                                                    <br><code class="small">{{ $permission->slug }}</code>
                                                    @if($permission->category)
                                                        <br><small class="text-muted">{{ ucfirst($permission->category) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $permission->roles_count }}</span>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success"
                                                         style="width: {{ ($permission->roles_count / max($totalRoles ?? 1, 1)) * 100 }}%">
                                                        {{ round(($permission->roles_count / max($totalRoles ?? 1, 1)) * 100, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Aucune donnée disponible</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analyse des problèmes potentiels -->
        <div class="row">
            <div class="col-md-12">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_head">
                        <div class="heading1 margin_0">
                            <h2>Analyse et Recommandations</h2>
                        </div>
                    </div>
                    <div class="full graph_revenue">
                        <div class="row">
                            <!-- Rôles sans permissions -->
                            <div class="col-md-4">
                                <div class="alert alert-warning">
                                    <h5 class="alert-heading">
                                        <i class="fa fa-exclamation-triangle"></i>
                                        Rôles sans Permissions
                                    </h5>
                                    <p class="mb-2">{{ $rolesWithoutPermissions ?? 0 }} rôle(s) n'ont aucune permission.</p>
                                    @if(($rolesWithoutPermissions ?? 0) > 0)
                                        <hr>
                                        <p class="mb-0">
                                            <small>
                                                <strong>Recommandation:</strong> Assignez des permissions ou supprimez ces rôles inutiles.
                                            </small>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Rôles inutilisés -->
                            <div class="col-md-4">
                                <div class="alert alert-info">
                                    <h5 class="alert-heading">
                                        <i class="fa fa-info-circle"></i>
                                        Rôles Inutilisés
                                    </h5>
                                    <p class="mb-2">{{ $unusedRoles ?? 0 }} rôle(s) ne sont assignés à aucun membres.</p>
                                    @if(($unusedRoles ?? 0) > 0)
                                        <hr>
                                        <p class="mb-0">
                                            <small>
                                                <strong>Recommandation:</strong> Évaluez la nécessité de ces rôles ou archivez-les.
                                            </small>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Permissions redondantes -->
                            <div class="col-md-4">
                                <div class="alert alert-success">
                                    <h5 class="alert-heading">
                                        <i class="fa fa-check-circle"></i>
                                        Optimisation
                                    </h5>
                                    <p class="mb-2">
                                        Taux d'utilisation: {{ $usageRate ?? 0 }}%<br>
                                        Efficacité: {{ $efficiencyRate ?? 0 }}%
                                    </p>
                                    @if(($efficiencyRate ?? 0) > 80)
                                        <hr>
                                        <p class="mb-0">
                                            <small>
                                                <strong>Excellent!</strong> Votre système de rôles est bien optimisé.
                                            </small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tableau détaillé des problèmes -->
                        @if(isset($issuesRoles) && $issuesRoles->count() > 0)
                            <div class="mt-4">
                                <h5>Rôles Nécessitant une Attention</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Rôle</th>
                                                <th>Problème</th>
                                                <th>Détails</th>
                                                <th>Action Suggérée</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($issuesRoles as $issue)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $issue->role->name }}</strong>
                                                        <br><small class="text-muted">{{ $issue->role->slug }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge
                                                            @if($issue->type === 'no_permissions') bg-warning
                                                            @elseif($issue->type === 'no_users') bg-info
                                                            @elseif($issue->type === 'expired') bg-danger
                                                            @else bg-secondary
                                                            @endif">
                                                            {{ $issue->type_label }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $issue->details }}</td>
                                                    <td>
                                                        <small>{{ $issue->suggestion }}</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions d'export et de génération de rapports -->
        <div class="row">
            <div class="col-md-12">
                <div class="white_shd full margin_bottom_30">
                    <div class="full graph_revenue text-center">
                        <h5 class="mb-3">Actions sur les Rapports</h5>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary" onclick="exportReport('pdf')">
                                <i class="fa fa-file-pdf"></i> Exporter PDF
                            </button>
                            <button type="button" class="btn btn-success" onclick="exportReport('excel')">
                                <i class="fa fa-file-excel"></i> Exporter Excel
                            </button>
                            <button type="button" class="btn btn-info" onclick="exportReport('csv')">
                                <i class="fa fa-file-text"></i> Exporter CSV
                            </button>
                            <button type="button" class="btn btn-warning" onclick="printReport()">
                                <i class="fa fa-print"></i> Imprimer
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="scheduleReport()">
                                <i class="fa fa-clock"></i> Programmer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de programmation de rapport -->
<div class="modal fade" id="scheduleReportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Programmer un Rapport</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="scheduleForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Fréquence</label>
                        <select class="form-control" name="frequency" required>
                            <option value="">Sélectionnez...</option>
                            <option value="daily">Quotidien</option>
                            <option value="weekly">Hebdomadaire</option>
                            <option value="monthly">Mensuel</option>
                            <option value="quarterly">Trimestriel</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Format</label>
                        <select class="form-control" name="format" required>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email de destination</label>
                        <input type="email" class="form-control" name="email"
                               value="{{ auth()->user()->email }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Programmer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
// Données pour les graphiques (à adapter selon vos données réelles)
const chartData = {
    levelDistribution: @json($levelDistributionData ?? []),
    typeDistribution: @json($typeDistributionData ?? []),
    evolution: @json($evolutionData ?? [])
};

// Graphique de distribution par niveau
const levelCtx = document.getElementById('levelDistributionChart').getContext('2d');
new Chart(levelCtx, {
    type: 'doughnut',
    data: {
        labels: ['Super Admin (100)', 'Administration (80-99)', 'Direction (60-79)', 'Responsables (40-59)', 'Membres Actifs (20-39)', 'Membres (10-19)', 'Visiteurs (0-9)'],
        datasets: [{
            data: chartData.levelDistribution,
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
                position: 'bottom'
            }
        }
    }
});

// Graphique de répartition type
const typeCtx = document.getElementById('typeDistributionChart').getContext('2d');
new Chart(typeCtx, {
    type: 'pie',
    data: {
        labels: ['Rôles Système', 'Rôles Personnalisés'],
        datasets: [{
            data: chartData.typeDistribution,
            backgroundColor: ['#ffc107', '#28a745'],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Graphique d'évolution
const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
new Chart(evolutionCtx, {
    type: 'line',
    data: {
        labels: chartData.evolution.labels || [],
        datasets: [
            {
                label: 'Rôles Créés',
                data: chartData.evolution.roles || [],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4
            },
            {
                label: 'Membress Assignés',
                data: chartData.evolution.users || [],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                position: 'top'
            }
        }
    }
});

// Fonctions d'export
function exportReport(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);

    window.location.href = `{{ route('private.roles.reports') }}?${params.toString()}`;
}

function printReport() {
    window.print();
}

function scheduleReport() {
    const modal = new bootstrap.Modal(document.getElementById('scheduleReportModal'));
    modal.show();
}

// Soumission du formulaire de programmation
document.getElementById('scheduleForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    fetch('{{ route("private.roles.scheduleReport") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('scheduleReportModal')).hide();
            alert('Rapport programmé avec succès!');
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
});

// Actualisation automatique des données
setInterval(function() {
    // Actualiser les KPI toutes les 5 minutes
    fetch('{{ route("private.roles.reports") }}?ajax=1', {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Mettre à jour les KPI
        if (data.kpi) {
            document.querySelector('.kpi-total-roles').textContent = data.kpi.totalRoles;
            document.querySelector('.kpi-active-users').textContent = data.kpi.activeUsers;
            document.querySelector('.kpi-total-permissions').textContent = data.kpi.totalPermissions;
            document.querySelector('.kpi-unused-roles').textContent = data.kpi.unusedRoles;
        }
    })
    .catch(error => {
        console.log('Erreur de mise à jour automatique:', error);
    });
}, 300000); // 5 minutes
</script>

@push('styles')
<style>
@media print {
    .btn, .breadcrumb, .form-control, .modal {
        display: none !important;
    }

    .white_shd {
        box-shadow: none !important;
        border: 1px solid #ddd;
    }

    .page-break {
        page-break-before: always;
    }
}

.kpi-icon {
    opacity: 0.8;
}

.progress {
    background-color: #e9ecef;
}

.alert-heading {
    font-size: 1.1em;
}

.table th {
    border-top: none;
}

.chart-container {
    position: relative;
    height: 300px;
}
</style>
@endpush
@endsection
