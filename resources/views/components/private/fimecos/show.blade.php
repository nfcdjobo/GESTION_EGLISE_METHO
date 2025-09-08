@extends('layouts.private.main')
@section('title', 'Détails FIMECO')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">{{ $fimeco['nom'] }}</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.fimecos.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-coins mr-2"></i>
                        FIMECO
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">{{ $fimeco['nom'] }}</span>
                    </div>
                </li>
            </ol>
        </nav>
        <p class="text-slate-500 mt-1">{{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @can('fimecos.update')
                    @if($fimeco['statut'] !== 'cloturee')
                        <a href="{{ route('private.fimecos.edit', $fimeco['id']) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    @endif
                @endcan

                @can('fimecos.statistics')
                <a href="{{ route('private.fimecos.statistiques', $fimeco['id']) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-chart-line mr-2"></i> Statistiques
                </a>
                @endcan
                @can('fimecos.create')
                @if($fimeco['est_en_cours'])
                    <a href="{{ route('private.subscriptions.create') }}?fimero={{$fimeco->id}}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-hand-holding-usd mr-2"></i> Souscrire
                    </a>
                @endif
                @endcan
                @can('fimecos.close')
                    @if($fimeco['statut'] !== 'cloturee')
                        <form action="{{ route('private.fimecos.cloturer', $fimeco['id']) }}" method="POST" class="inline" id="clotureForm">
                            @csrf
                            <button type="button" onclick="confirmerCloture()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-lock mr-2"></i> Clôturer
                            </button>
                        </form>
                    @endif
                @endcan
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Détails généraux -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations Générales
                        </h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($fimeco['statut'] === 'active') bg-green-100 text-green-800
                            @elseif($fimeco['statut'] === 'cloturee') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            <i class="fas fa-circle mr-2 text-xs"></i>
                            {{ ucfirst($fimeco['statut']) }}
                        </span>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    @if($fimeco['description'])
                        <div>
                            <h3 class="text-sm font-semibold text-slate-700 mb-2">Description</h3>
                            <p class="text-slate-600 leading-relaxed">{{ $fimeco['description'] }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-700 mb-2">Date de début</h3>
                                <p class="text-slate-900 font-medium">{{ \Carbon\Carbon::parse($fimeco['debut'])->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-700 mb-2">Date de fin</h3>
                                <p class="text-slate-900 font-medium">{{ \Carbon\Carbon::parse($fimeco['fin'])->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-700 mb-2">Durée</h3>
                                <p class="text-slate-900 font-medium">
                                    {{ \Carbon\Carbon::parse($fimeco['debut'])->diffInDays(\Carbon\Carbon::parse($fimeco['fin'])) }} jours
                                </p>
                            </div>
                            @if(isset($fimeco['responsable']) && $fimeco['responsable'])
                                <div>
                                    <h3 class="text-sm font-semibold text-slate-700 mb-2">Responsable</h3>
                                    <p class="text-slate-900 font-medium">{{ $fimeco['responsable']['name'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress et objectif -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bullseye text-green-600 mr-2"></i>
                        Progression
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600 mb-2">
                                {{ number_format($fimeco['total_paye'], 0, ',', ' ') }} FCFA
                            </div>

                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">Progression</span>
                                <span class="font-semibold text-slate-900">{{ $fimeco['pourcentage_realisation'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-300"
                                     style="width: {{ min($fimeco['pourcentage_realisation'], 100) }}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-200">
                            <div class="text-center p-4 bg-blue-50 rounded-xl">
                                <div class="text-2xl font-bold text-blue-600">{{ $fimeco->subscriptions->count() }}</div>
                                <div class="text-sm text-blue-700">Souscripteurs</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des souscriptions -->
            @if(isset($fimeco['subscriptions']) && count($fimeco['subscriptions']) > 0)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-purple-600 mr-2"></i>
                            Souscriptions ({{ count($fimeco['subscriptions']) }})
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead>
                                    <tr class="border-b border-slate-200">
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Souscripteur</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Montant</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Payé</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Statut</th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach($fimeco['subscriptions'] as $subscription)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-4 py-4">
                                                <a href="{{route('private.users.show', $subscription->souscripteur->nom)}}" class="font-medium text-blue-900">
                                                    {{ $subscription->souscripteur->nom ?? 'N/A' }}
                                                </a>
                                            </td>
                                            <td class="px-4 py-4 text-slate-900 font-medium">
                                                {{ number_format($subscription['montant_souscrit'], 0, ',', ' ') }} FCFA
                                            </td>
                                            <td class="px-4 py-4 text-green-600 font-medium">
                                                {{ number_format($subscription['montant_paye'], 0, ',', ' ') }} FCFA
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($subscription['statut'] === 'completement_payee') bg-green-100 text-green-800
                                                    @elseif($subscription['statut'] === 'partiellement_payee') bg-yellow-100 text-yellow-800
                                                    @elseif($subscription['statut'] === 'active') bg-blue-100 text-blue-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $subscription['statut'])) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-slate-600">
                                                {{ \Carbon\Carbon::parse($subscription['date_souscription'])->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar avec statistiques -->
        <div class="space-y-6">
            <!-- Statistiques détaillées -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-bar text-amber-600 mr-2"></i>
                        Statistiques
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($statistiques as $key => $value)
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700 capitalize">
                                {{ str_replace('_', ' ', $key) }}:
                            </span>
                            <span class="text-sm font-semibold text-slate-900">
                                @if(is_numeric($value))
                                    @if($key === 'pourcentage_realisation')
                                        {{ $value }}%
                                    @elseif(in_array($key, ['total_paye', 'total_souscriptions', 'reste_a_collecter', 'montant_moyen_souscription']))
                                        {{ number_format($value, 0, ',', ' ') }} FCFA
                                    @else
                                        {{ $value }}
                                    @endif
                                @else
                                    {{-- {{ $value }} --}}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Calendrier et dates importantes -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar text-pink-600 mr-2"></i>
                        Timeline
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full mt-1.5"></div>
                        <div>
                            <h3 class="font-medium text-slate-900">Début</h3>
                            <p class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($fimeco['debut'])->format('d/m/Y') }}</p>
                        </div>
                    </div>

                    @if($fimeco['est_en_cours'])
                        <div class="flex items-start space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mt-1.5 animate-pulse"></div>
                            <div>
                                <h3 class="font-medium text-slate-900">Maintenant</h3>
                                <p class="text-sm text-slate-600">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
                                <p class="text-xs text-blue-600">En cours</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start space-x-3">
                        <div class="w-3 h-3 {{ $fimeco['est_terminee'] ? 'bg-red-500' : 'bg-gray-300' }} rounded-full mt-1.5"></div>
                        <div>
                            <h3 class="font-medium text-slate-900">Fin</h3>
                            <p class="text-sm text-slate-600">{{ \Carbon\Carbon::parse($fimeco['fin'])->format('d/m/Y') }}</p>
                            @if($fimeco['est_terminee'])
                                <p class="text-xs text-red-600">Terminée</p>
                            @else
                                <p class="text-xs text-gray-600">
                                    {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($fimeco['fin'])) }} jours restants
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('private.subscriptions.index', ['fimeco_id' => $fimeco['id']]) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-list mr-2"></i> Voir Souscriptions
                    </a>
                    <a href="{{ route('private.paiements.index', ['fimeco_id' => $fimeco['id']]) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                        <i class="fas fa-credit-card mr-2"></i> Voir Paiements
                    </a>
                    <a href="{{ route('private.fimecos.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de clôture -->
<div id="clotureModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-lock text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Clôturer la FIMECO</h3>
            </div>
            <p class="text-slate-600 mb-4">Êtes-vous sûr de vouloir clôturer cette FIMECO ? Cette action est définitive et empêchera toute nouvelle souscription.</p>

            <div class="mb-4">
                <label for="commentaireCloture" class="block text-sm font-medium text-slate-700 mb-2">Commentaire (optionnel)</label>
                <textarea id="commentaireCloture" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Motif de la clôture..."></textarea>
            </div>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="fermerModalCloture()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            @can('fimecos.close')
            <button type="button" onclick="executerCloture()" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Clôturer définitivement
            </button>
            @endcan
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmerCloture() {
    document.getElementById('clotureModal').classList.remove('hidden');
}

function fermerModalCloture() {
    document.getElementById('clotureModal').classList.add('hidden');
}

function executerCloture() {
    const commentaire = document.getElementById('commentaireCloture').value;
    const form = document.getElementById('clotureForm');

    // Ajouter le commentaire au formulaire
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'commentaire';
    input.value = commentaire;
    form.appendChild(input);

    form.submit();
}

// Fermer le modal en cliquant à l'extérieur
document.getElementById('clotureModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        fermerModalCloture();
    }
});
</script>
@endpush
@endsection
