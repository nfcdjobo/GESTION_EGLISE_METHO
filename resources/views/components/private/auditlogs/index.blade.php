@extends('layouts.private.main')
@section('title', 'Journal d\'Audit des Permissions')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Journal d'Audit des Permissions</h1>
        <p class="text-slate-500 mt-1">Suivi et monitoring de toutes les actions liées aux permissions - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clipboard-list text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $logs->total() }}</p>
                    <p class="text-sm text-slate-500">Total des logs</p>
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
                    <p class="text-2xl font-bold text-slate-800">{{ $logs->whereIn('action', ['deleted', 'permission_revoked', 'role_removed'])->count() }}</p>
                    <p class="text-sm text-slate-500">Actions critiques</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $logs->where('created_at', '>=', now()->subHours(24))->count() }}</p>
                    <p class="text-sm text-slate-500">Dernières 24h</p>
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
                    <p class="text-2xl font-bold text-slate-800">{{ $logs->whereNotNull('user_id')->distinct('user_id')->count() }}</p>
                    <p class="text-sm text-slate-500">Membress actifs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Actions
                </h2>
                <div class="flex flex-wrap gap-2">
                    @can('audit.export')
                        <a href="{{ route('private.audit.export') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </a>
                    @endcan
                    <a href="{{ route('private.audit.statistics') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                    @can('audit.manage')
                        <button onclick="showCleanupModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-600 to-red-600 text-white text-sm font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-broom mr-2"></i> Nettoyer
                        </button>
                    @endcan
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.audit.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Action, modèle, membres..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Action</label>
                    <select name="action" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les actions</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ \App\Models\PermissionAuditLog::ACTIONS[$action] ?? $action }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de modèle</label>
                    <select name="model_type" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        @foreach($modelTypes as $type)
                            <option value="{{ $type }}" {{ request('model_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Période</label>
                    <select name="period" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les dates</option>
                        <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="yesterday" {{ request('period') == 'yesterday' ? 'selected' : '' }}>Hier</option>
                        <option value="last_7_days" {{ request('period') == 'last_7_days' ? 'selected' : '' }}>7 derniers jours</option>
                        <option value="last_30_days" {{ request('period') == 'last_30_days' ? 'selected' : '' }}>30 derniers jours</option>
                        <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Ce mois</option>
                        <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Mois dernier</option>
                    </select>
                </div>

                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="{{ route('private.audit.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                    <div class="flex items-center ml-4">
                        <input type="checkbox" name="critical_only" id="critical_only" value="1" {{ request('critical_only') ? 'checked' : '' }} class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500">
                        <label for="critical_only" class="ml-2 text-sm text-slate-700">Actions critiques uniquement</label>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des logs -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-history text-purple-600 mr-2"></i>
                    Journal d'Audit ({{ $logs->total() }})
                </h2>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-slate-600">
                        <i class="fas fa-sync-alt mr-1"></i>
                        Actualisation auto: <span id="refresh-status" class="font-medium text-green-600">ON</span>
                    </div>
                    <button onclick="toggleAutoRefresh()" class="text-slate-600 hover:text-slate-800">
                        <i class="fas fa-pause" id="refresh-icon"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            @if($logs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Date/Heure</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Action</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Membres</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Cible</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Modèle</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200" id="logs-tbody">
                            @foreach($logs as $log)
                                <tr class="hover:bg-slate-50 transition-colors" data-log-id="{{ $log->id }}">
                                    <td class="px-4 py-4">
                                        @can('audit.manage')
                                            <input type="checkbox" name="selected_logs[]" value="{{ $log->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 log-checkbox">
                                        @endcan
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $log->created_at->format('d/m/Y') }}</div>
                                        <div class="text-sm text-slate-500">{{ $log->created_at->format('H:i:s') }}</div>
                                        <div class="text-xs text-slate-400">{{ $log->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if(in_array($log->action, ['deleted', 'permission_revoked', 'role_removed'])) bg-red-100 text-red-800
                                            @elseif(in_array($log->action, ['permission_granted', 'role_assigned'])) bg-green-100 text-green-800
                                            @elseif(in_array($log->action, ['updated', 'permission_updated'])) bg-yellow-100 text-yellow-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ $log->action_name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($log->user)
                                            <div class="flex items-center space-x-2">
                                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                    {{ strtoupper(substr($log->user->prenom, 0, 1) . substr($log->user->nom, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-slate-900">{{ $log->user->nom_complet }}</div>
                                                    <div class="text-xs text-slate-500">{{ $log->user->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-slate-500 italic">Système</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($log->targetUser)
                                            <div class="flex items-center space-x-2">
                                                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                                    {{ strtoupper(substr($log->targetUser->prenom, 0, 1) . substr($log->targetUser->nom, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-slate-900">{{ $log->targetUser->nom_complet }}</div>
                                                    <div class="text-xs text-slate-500">{{ $log->targetUser->email }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-slate-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm font-medium text-slate-900">{{ $log->model_type }}</div>
                                        <div class="text-xs text-slate-500">ID: {{ $log->model_id }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-slate-900 max-w-xs truncate" title="{{ $log->description }}">
                                            {{ $log->description }}
                                        </div>
                                        @if($log->ip_address)
                                            <div class="text-xs text-slate-500 mt-1">IP: {{ $log->ip_address }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('private.audit.show', $log) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir détails">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            @if($log->user)
                                                <a href="{{ route('private.audit.user.logs', $log->user) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Logs membres">
                                                    <i class="fas fa-user text-sm"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium">{{ $logs->firstItem() }}</span> à <span class="font-medium">{{ $logs->lastItem() }}</span>
                        sur <span class="font-medium">{{ $logs->total() }}</span> résultats
                    </div>
                    <div>
                        {{ $logs->appends(request()->query())->links() }}
                    </div>
                </div>

                <!-- Actions en lot -->
                @can('audit.manage')
                    <div id="bulk-actions" class="hidden mt-6 p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">
                                <span id="selected-count">0</span> élément(s) sélectionné(s)
                            </span>
                            <button onclick="bulkDeleteLogs()" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i> Supprimer sélection
                            </button>
                        </div>
                    </div>
                @endcan
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clipboard-list text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun log d'audit trouvé</h3>
                    <p class="text-slate-500">
                        @if(request()->hasAny(['search', 'action', 'model_type', 'period']))
                            Aucun log ne correspond à vos critères de recherche.
                        @else
                            Aucune activité d'audit enregistrée pour le moment.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de nettoyage -->
@can('audit.manage')
<div id="cleanupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-broom text-orange-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Nettoyer les logs anciens</h3>
            </div>
            <form id="cleanupForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Supprimer les logs plus anciens que</label>
                        <select name="older_than_days" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            <option value="30">30 jours</option>
                            <option value="90">90 jours</option>
                            <option value="180">180 jours</option>
                            <option value="365">1 an</option>
                            <option value="730">2 ans</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="keep_critical" id="keep_critical" value="1" checked class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500">
                        <label for="keep_critical" class="ml-2 text-sm text-slate-700">Conserver les actions critiques</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="dry_run" id="dry_run" value="1" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="dry_run" class="ml-2 text-sm text-slate-700">Mode test (simulation)</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeCleanupModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="executeCleanup()" class="px-4 py-2 bg-orange-600 text-white rounded-xl hover:bg-orange-700 transition-colors">
                Nettoyer
            </button>
        </div>
    </div>
</div>
@endcan

@push('scripts')
<script>
let autoRefreshInterval;
let autoRefreshEnabled = true;

// Actualisation automatique
function startAutoRefresh() {
    autoRefreshInterval = setInterval(function() {
        if (autoRefreshEnabled) {
            fetchNewLogs();
        }
    }, 30000); // Toutes les 30 secondes
}

function toggleAutoRefresh() {
    autoRefreshEnabled = !autoRefreshEnabled;
    const status = document.getElementById('refresh-status');
    const icon = document.getElementById('refresh-icon');

    if (autoRefreshEnabled) {
        status.textContent = 'ON';
        status.className = 'font-medium text-green-600';
        icon.className = 'fas fa-pause';
    } else {
        status.textContent = 'OFF';
        status.className = 'font-medium text-red-600';
        icon.className = 'fas fa-play';
    }
}

function fetchNewLogs() {
    const tbody = document.getElementById('logs-tbody');
    const lastLogId = tbody.querySelector('tr')?.dataset.logId || 0;

    fetch(`{{ route('private.audit.realtime') }}?last_id=${lastLogId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                // Ajouter les nouveaux logs en haut du tableau
                data.data.forEach(log => {
                    const row = createLogRow(log);
                    tbody.insertAdjacentHTML('afterbegin', row);
                });

                // Animation pour mettre en évidence les nouveaux logs
                const newRows = tbody.querySelectorAll('tr[data-new="true"]');
                newRows.forEach(row => {
                    row.classList.add('bg-green-50', 'animate-pulse');
                    setTimeout(() => {
                        row.classList.remove('bg-green-50', 'animate-pulse');
                        row.removeAttribute('data-new');
                    }, 3000);
                });
            }
        })
        .catch(error => console.error('Erreur lors de la récupération des logs:', error));
}

function createLogRow(log) {
    // Créer HTML pour une nouvelle ligne de log
    // (à adapter selon la structure exacte de vos données)
    return `<tr class="hover:bg-slate-50 transition-colors" data-log-id="${log.id}" data-new="true">
        <!-- Contenu de la ligne... -->
    </tr>`;
}

// Sélection multiple
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.log-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActions();
});

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('log-checkbox')) {
        updateBulkActions();
    }
});

function updateBulkActions() {
    const selected = document.querySelectorAll('.log-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');
    const selectedCount = document.getElementById('selected-count');

    if (bulkActions && selectedCount) {
        selectedCount.textContent = selected.length;

        if (selected.length > 0) {
            bulkActions.classList.remove('hidden');
        } else {
            bulkActions.classList.add('hidden');
        }
    }
}

// Suppression en lot
function bulkDeleteLogs() {
    const selected = Array.from(document.querySelectorAll('.log-checkbox:checked')).map(cb => cb.value);

    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un log à supprimer');
        return;
    }

    if (!confirm(`Êtes-vous sûr de vouloir supprimer ${selected.length} log(s) ? Cette action est irréversible.`)) {
        return;
    }

    fetch('{{ route("private.audit.bulk.delete") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            log_ids: selected
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Modal de nettoyage
function showCleanupModal() {
    document.getElementById('cleanupModal').classList.remove('hidden');
}

function closeCleanupModal() {
    document.getElementById('cleanupModal').classList.add('hidden');
}

function executeCleanup() {
    const form = document.getElementById('cleanupForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    fetch('{{ route("private.audit.cleanup") }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        closeCleanupModal();
        alert(data.message);
        if (data.success && !data.dry_run) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Démarrer l'actualisation automatique
document.addEventListener('DOMContentLoaded', function() {
    startAutoRefresh();
});

// Arrêter l'actualisation automatique lors de la fermeture de la page
window.addEventListener('beforeunload', function() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
});
</script>
@endpush
@endsection
