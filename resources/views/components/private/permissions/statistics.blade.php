@extends('layouts.private.main')
@section('title', 'Statistiques des Permissions')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Statistiques des Permissions</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.permissions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-key mr-2"></i>
                        Permissions
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
        <p class="text-slate-500 mt-1">Analyse détaillée de l'utilisation des permissions - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('private.permissions.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux permissions
                </a>
                @can('permissions.export')
                    <a href="{{ route('private.permissions.export') }}?format=json&with_stats=true" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i> Exporter les statistiques
                    </a>
                @endcan
                
                <button onclick="refreshStats()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-refresh mr-2"></i> Actualiser
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques générales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-key text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $general['total_permissions'] }}</p>
                    <p class="text-sm text-slate-500">Total</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $general['active_permissions'] }}</p>
                    <p class="text-sm text-slate-500">Actives</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-lock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $general['system_permissions'] }}</p>
                    <p class="text-sm text-slate-500">Système</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-cogs text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $general['user_permissions'] }}</p>
                    <p class="text-sm text-slate-500">Personnalisées</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-rose-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-exclamation-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $general['unused_permissions'] }}</p>
                    <p class="text-sm text-slate-500">Non utilisées</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Permissions par catégorie -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-layer-group text-cyan-600 mr-2"></i>
                    Répartition par Catégorie
                </h2>
            </div>
            <div class="p-6">
                @if($by_category->count() > 0)
                    <div class="space-y-4">
                        @foreach($by_category as $category)
                            @php
                                $percentage = $general['total_permissions'] > 0 ? round(($category->count / $general['total_permissions']) * 100, 1) : 0;
                                $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500', 'bg-indigo-500', 'bg-red-500'];
                                $color = $colors[$loop->index % count($colors)];
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3 flex-1">
                                    <div class="w-4 h-4 {{ $color }} rounded-full"></div>
                                    <span class="font-medium text-slate-900">{{ ucfirst($category->category ?: 'Non définie') }}</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-24 bg-slate-200 rounded-full h-2">
                                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-slate-700 w-16 text-right">{{ $category->count }} ({{ $percentage }}%)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-layer-group text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Permissions par action -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-cogs text-green-600 mr-2"></i>
                    Répartition par Action
                </h2>
            </div>
            <div class="p-6">
                @if($by_action->count() > 0)
                    <div class="space-y-4">
                        @foreach($by_action as $action)
                            @php
                                $percentage = $general['total_permissions'] > 0 ? round(($action->count / $general['total_permissions']) * 100, 1) : 0;
                                $actionColors = [
                                    'create' => 'bg-green-500',
                                    'read' => 'bg-blue-500',
                                    'update' => 'bg-yellow-500',
                                    'delete' => 'bg-red-500',
                                    'manage' => 'bg-purple-500',
                                    'export' => 'bg-emerald-500',
                                    'import' => 'bg-orange-500',
                                    'validate' => 'bg-cyan-500',
                                    'approve' => 'bg-indigo-500',
                                    'reject' => 'bg-rose-500',
                                    'archive' => 'bg-gray-500',
                                    'restore' => 'bg-teal-500',
                                    'download' => 'bg-pink-500'
                                ];
                                $color = $actionColors[$action->action] ?? 'bg-slate-500';
                            @endphp
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3 flex-1">
                                    <div class="w-4 h-4 {{ $color }} rounded-full"></div>
                                    <span class="font-medium text-slate-900">{{ ucfirst($action->action) }}</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <div class="w-24 bg-slate-200 rounded-full h-2">
                                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-slate-700 w-16 text-right">{{ $action->count }} ({{ $percentage }}%)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-cogs text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Aucune donnée disponible</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Permissions par ressource -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-database text-purple-600 mr-2"></i>
                Répartition par Ressource
            </h2>
        </div>
        <div class="p-6">
            @if($by_resource->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($by_resource as $resource)
                        @php
                            $percentage = $general['total_permissions'] > 0 ? round(($resource->count / $general['total_permissions']) * 100, 1) : 0;
                        @endphp
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-slate-900">{{ $resource->resource }}</span>
                                <span class="text-sm font-medium text-slate-700">{{ $resource->count }}</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                            <div class="text-xs text-slate-500 mt-1">{{ $percentage }}% du total</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-database text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500">Aucune donnée disponible</p>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Permissions les plus utilisées -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-fire text-orange-600 mr-2"></i>
                    Permissions les Plus Utilisées
                </h2>
            </div>
            <div class="p-6">
                @if($most_used->count() > 0)
                    <div class="space-y-4">
                        @foreach($most_used as $permission)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <div class="flex items-center space-x-3">
                                    @if($permission->is_system)
                                        <i class="fas fa-lock text-yellow-500" title="Permission système"></i>
                                    @endif
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $permission->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $permission->resource }}.{{ $permission->action }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-slate-700">{{ $permission->last_used_at->diffForHumans() }}</div>
                                    <div class="text-xs text-slate-500">{{ $permission->last_used_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-fire text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Aucune utilisation enregistrée</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Permissions jamais utilisées -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                    Permissions Jamais Utilisées
                </h2>
            </div>
            <div class="p-6">
                @if($never_used->count() > 0)
                    <div class="space-y-4 max-h-64 overflow-y-auto">
                        @foreach($never_used as $permission)
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-xl border border-red-100">
                                <div class="flex items-center space-x-3">
                                    @if($permission->is_system)
                                        <i class="fas fa-lock text-yellow-500" title="Permission système"></i>
                                    @endif
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $permission->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $permission->resource }}.{{ $permission->action }}</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-red-700">Créée {{ $permission->created_at->diffForHumans() }}</div>
                                    <div class="text-xs text-red-600">{{ $permission->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl">
                        <div class="flex">
                            <i class="fas fa-lightbulb text-amber-500 mt-0.5 mr-2"></i>
                            <div class="text-sm text-amber-700">
                                <strong>Recommandation :</strong> Considérez la suppression ou la révision de ces permissions si elles ne sont plus nécessaires.
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check-circle text-2xl text-green-500"></i>
                        </div>
                        <p class="text-green-600 font-medium">Excellente nouvelle !</p>
                        <p class="text-slate-500">Toutes les permissions sont utilisées</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recommandations -->
    <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200 rounded-2xl p-6">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-lightbulb text-white text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-slate-800 mb-3">Recommandations d'Optimisation</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($general['unused_permissions'] > 0)
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-exclamation-triangle text-amber-500 mt-1"></i>
                            <div>
                                <p class="font-medium text-slate-800">Permissions non utilisées</p>
                                <p class="text-sm text-slate-600">{{ $general['unused_permissions'] }} permissions n'ont jamais été utilisées. Considérez leur suppression.</p>
                            </div>
                        </div>
                    @endif

                    @if($by_category->where('category', null)->first())
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-tag text-blue-500 mt-1"></i>
                            <div>
                                <p class="font-medium text-slate-800">Permissions sans catégorie</p>
                                <p class="text-sm text-slate-600">{{ $by_category->where('category', null)->first()->count ?? 0 }} permissions n'ont pas de catégorie définie.</p>
                            </div>
                        </div>
                    @endif

                    @php
                        $ratio = $general['total_permissions'] > 0 ? round(($general['system_permissions'] / $general['total_permissions']) * 100, 1) : 0;
                    @endphp
                    @if($ratio > 70)
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-balance-scale text-purple-500 mt-1"></i>
                            <div>
                                <p class="font-medium text-slate-800">Équilibre système/personnalisé</p>
                                <p class="text-sm text-slate-600">{{ $ratio }}% de permissions système. Considérez plus de permissions personnalisées.</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start space-x-2">
                        <i class="fas fa-chart-line text-green-500 mt-1"></i>
                        <div>
                            <p class="font-medium text-slate-800">Performance globale</p>
                            <p class="text-sm text-slate-600">
                                @if($general['unused_permissions'] == 0)
                                    Excellente ! Toutes vos permissions sont utilisées.
                                @elseif($general['unused_permissions'] < 5)
                                    Bonne gestion des permissions avec peu d'inutilisées.
                                @else
                                    Des améliorations sont possibles pour optimiser vos permissions.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshStats() {
    // Animation de chargement
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Actualisation...';
    button.disabled = true;

    // Recharger la page après une courte pause pour l'effet visuel
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

// Animation des barres de progression au chargement
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('[style*="width:"]');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = width;
        }, 100);
    });
});
</script>

@endsection
