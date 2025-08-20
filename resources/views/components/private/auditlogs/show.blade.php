@extends('layouts.private.main')
@section('title', 'Détails du Log d\'Audit')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Détails du Log d'Audit</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.audit.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-clipboard-list mr-2"></i>
                        Journal d'Audit
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Détails #{{ $auditLog->id }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Vue d'ensemble -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Vue d'ensemble
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">ID du Log</label>
                            <div class="text-lg font-semibold text-slate-900">{{ $auditLog->id }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date et Heure</label>
                            <div class="text-lg font-semibold text-slate-900">{{ $auditLog->created_at->format('d/m/Y à H:i:s') }}</div>
                            <div class="text-sm text-slate-500">{{ $auditLog->created_at->diffForHumans() }}</div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Action</label>
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-medium
                            @if(in_array($auditLog->action, ['deleted', 'permission_revoked', 'role_removed'])) bg-red-100 text-red-800
                            @elseif(in_array($auditLog->action, ['permission_granted', 'role_assigned'])) bg-green-100 text-green-800
                            @elseif(in_array($auditLog->action, ['updated', 'permission_updated'])) bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            <i class="fas fa-bolt mr-2"></i>
                            {{ $auditLog->action_name }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                        <div class="p-4 bg-slate-50 rounded-xl text-slate-900">
                            {{ $auditLog->description }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Type de Modèle</label>
                            <div class="text-lg font-semibold text-slate-900">{{ $auditLog->model_type }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">ID du Modèle</label>
                            <div class="text-lg font-semibold text-slate-900">{{ $auditLog->model_id }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Changements -->
            @if($auditLog->changes || $auditLog->original)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-exchange-alt text-amber-600 mr-2"></i>
                            Changements
                        </h2>
                    </div>
                    <div class="p-6">
                        @if($auditLog->formatted_changes)
                            <div class="space-y-4">
                                @foreach($auditLog->formatted_changes as $change)
                                    <div class="border border-slate-200 rounded-xl p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wide">{{ $change['field'] }}</h3>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-red-700 mb-2">AVANT</label>
                                                <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                                    @if($change['old_value'])
                                                        <code class="text-sm text-red-800">{{ is_array($change['old_value']) ? json_encode($change['old_value'], JSON_PRETTY_PRINT) : $change['old_value'] }}</code>
                                                    @else
                                                        <span class="text-sm text-red-600 italic">Non défini</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-green-700 mb-2">APRÈS</label>
                                                <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                                    @if($change['new_value'])
                                                        <code class="text-sm text-green-800">{{ is_array($change['new_value']) ? json_encode($change['new_value'], JSON_PRETTY_PRINT) : $change['new_value'] }}</code>
                                                    @else
                                                        <span class="text-sm text-green-600 italic">Supprimé</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-exchange-alt text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500">Aucun changement détaillé disponible</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Modèle associé -->
            @if($relatedModel)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-link text-purple-600 mr-2"></i>
                            Modèle Associé
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                                <div class="text-lg font-semibold text-slate-900">{{ class_basename($relatedModel) }}</div>
                            </div>

                            @if(method_exists($relatedModel, 'getName') || isset($relatedModel->name))
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Nom</label>
                                    <div class="text-lg font-semibold text-slate-900">
                                        {{ method_exists($relatedModel, 'getName') ? $relatedModel->getName() : $relatedModel->name }}
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                                @if(method_exists($relatedModel, 'trashed') && $relatedModel->trashed())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-trash mr-1"></i> Supprimé
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Actif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informations utilisateur -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-user text-green-600 mr-2"></i>
                        Utilisateur
                    </h2>
                </div>
                <div class="p-6">
                    @if($auditLog->user)
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($auditLog->user->prenom, 0, 1) . substr($auditLog->user->nom, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900">{{ $auditLog->user->nom_complet }}</div>
                                <div class="text-sm text-slate-500">{{ $auditLog->user->email }}</div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <a href="{{ route('private.audit.user.logs', $auditLog->user) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors w-full justify-center">
                                <i class="fas fa-history mr-2"></i>
                                Voir tous ses logs
                            </a>
                            @can('users.read')
                                <a href="{{ route('private.users.show', $auditLog->user) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors w-full justify-center">
                                    <i class="fas fa-user mr-2"></i>
                                    Profil utilisateur
                                </a>
                            @endcan
                        </div>
                    @else
                        <div class="text-center">
                            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-robot text-2xl text-slate-400"></i>
                            </div>
                            <p class="font-semibold text-slate-900">Système</p>
                            <p class="text-sm text-slate-500">Action automatique</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Utilisateur cible -->
            @if($auditLog->targetUser)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bullseye text-orange-600 mr-2"></i>
                            Utilisateur Cible
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($auditLog->targetUser->prenom, 0, 1) . substr($auditLog->targetUser->nom, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900">{{ $auditLog->targetUser->nom_complet }}</div>
                                <div class="text-sm text-slate-500">{{ $auditLog->targetUser->email }}</div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <a href="{{ route('private.audit.user.logs', $auditLog->targetUser) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors w-full justify-center">
                                <i class="fas fa-history mr-2"></i>
                                Voir tous ses logs
                            </a>
                            @can('users.read')
                                <a href="{{ route('private.users.show', $auditLog->targetUser) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors w-full justify-center">
                                    <i class="fas fa-user mr-2"></i>
                                    Profil utilisateur
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            @endif

            <!-- Informations techniques -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cog text-cyan-600 mr-2"></i>
                        Informations Techniques
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($auditLog->ip_address)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Adresse IP</label>
                            <code class="px-3 py-2 bg-slate-100 text-slate-800 rounded-lg text-sm">{{ $auditLog->ip_address }}</code>
                        </div>
                    @endif

                    @if($auditLog->session_id)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">ID de Session</label>
                            <code class="px-3 py-2 bg-slate-100 text-slate-800 rounded-lg text-sm break-all">{{ Str::limit($auditLog->session_id, 20) }}</code>
                        </div>
                    @endif

                    @if($auditLog->user_agent)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">User Agent</label>
                            <div class="p-3 bg-slate-100 rounded-lg">
                                <code class="text-xs text-slate-700 break-all">{{ $auditLog->user_agent }}</code>
                            </div>
                        </div>
                    @endif

                    @if($auditLog->context)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Contexte</label>
                            <div class="p-3 bg-slate-100 rounded-lg">
                                <pre class="text-xs text-slate-700 whitespace-pre-wrap">{{ json_encode($auditLog->context, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('private.audit.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors w-full justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour à la liste
                    </a>

                    @can('audit.export')
                        <a href="{{ route('private.audit.export') }}?action={{ $auditLog->action }}&date_from={{ $auditLog->created_at->format('Y-m-d') }}&date_to={{ $auditLog->created_at->format('Y-m-d') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors w-full justify-center">
                            <i class="fas fa-download mr-2"></i>
                            Exporter similaires
                        </a>
                    @endcan

                    <button onclick="copyLogData()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors w-full justify-center">
                        <i class="fas fa-copy mr-2"></i>
                        Copier les données
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs connexes -->
    @if($relatedLogs && $relatedLogs->count() > 0)
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-link text-indigo-600 mr-2"></i>
                    Logs Connexes
                    <span class="ml-2 text-sm font-normal text-slate-500">(même utilisateur, ±5 minutes)</span>
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($relatedLogs as $relatedLog)
                        <div class="flex items-center justify-between p-4 border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-slate-900">{{ $relatedLog->action_name }}</div>
                                    <div class="text-sm text-slate-500">{{ $relatedLog->model_type }} • {{ $relatedLog->created_at->format('H:i:s') }}</div>
                                </div>
                            </div>
                            <a href="{{ route('private.audit.show', $relatedLog) }}" class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-medium rounded-lg hover:bg-indigo-200 transition-colors">
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
<script>
function copyLogData() {
    const logData = {
        id: '{{ $auditLog->id }}',
        action: '{{ $auditLog->action }}',
        model_type: '{{ $auditLog->model_type }}',
        model_id: '{{ $auditLog->model_id }}',
        user: @if($auditLog->user) '{{ $auditLog->user->nom_complet }}' @else null @endif,
        target_user: @if($auditLog->targetUser) '{{ $auditLog->targetUser->nom_complet }}' @else null @endif,
        created_at: '{{ $auditLog->created_at->toISOString() }}',
        description: '{{ addslashes($auditLog->description) }}',
        ip_address: '{{ $auditLog->ip_address }}',
        changes: @json($auditLog->changes),
        original: @json($auditLog->original)
    };

    navigator.clipboard.writeText(JSON.stringify(logData, null, 2))
        .then(() => {
            // Animation de succès
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;

            button.innerHTML = '<i class="fas fa-check mr-2"></i>Copié !';
            button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            button.classList.add('bg-green-600', 'hover:bg-green-700');

            setTimeout(() => {
                button.innerHTML = originalContent;
                button.classList.remove('bg-green-600', 'hover:bg-green-700');
                button.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }, 2000);
        })
        .catch(err => {
            console.error('Erreur lors de la copie:', err);
            alert('Impossible de copier les données');
        });
}

// Animation d'entrée pour les éléments
document.addEventListener('DOMContentLoaded', function() {
    const elements = document.querySelectorAll('.bg-white\\/80');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';

        setTimeout(() => {
            element.style.transition = 'all 0.6s ease-out';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endpush
@endsection
