@extends('layouts.private.main')
@section('title', 'Modifier la Souscription')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier la Souscription</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.subscriptions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-hand-holding-usd mr-2"></i>
                        Souscriptions
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <a href="{{ route('private.subscriptions.show', $subscription['id']) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            Souscription #{{ substr($subscription['id'], 0, 8) }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Modifier</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Alertes de statut -->
    @if(in_array($subscription['statut'], ['annulee', 'completement_payee']))
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
            <div class="flex">
                <i class="fas fa-exclamation-triangle text-red-400 mt-0.5 mr-3"></i>
                <div>
                    <h3 class="text-sm font-medium text-red-800">
                        Souscription {{ $subscription['statut'] === 'annulee' ? 'annulée' : 'complètement payée' }}
                    </h3>
                    <p class="text-sm text-red-700 mt-1">
                        Cette souscription ne peut plus être modifiée car elle est
                        {{ $subscription['statut'] === 'annulee' ? 'annulée' : 'complètement payée' }}.
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($subscription['fimeco']['statut'] !== 'active')
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
            <div class="flex">
                <i class="fas fa-info-circle text-yellow-400 mt-0.5 mr-3"></i>
                <div>
                    <h3 class="text-sm font-medium text-yellow-800">FIMECO inactive</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        La FIMECO associée n'est plus active, les modifications sont limitées.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('private.subscriptions.update', $subscription['id']) }}" method="POST" id="subscriptionEditForm" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulaire principal -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-edit text-blue-600 mr-2"></i>
                            Modifier la Souscription
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Information FIMECO (lecture seule) -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">FIMECO</label>
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                                <h3 class="font-semibold text-slate-900">{{ $subscription['fimeco']['nom'] }}</h3>
                                @if($subscription['fimeco']['description'])
                                    <p class="text-sm text-slate-700 mt-1">{{ $subscription['fimeco']['description'] }}</p>
                                @endif
                                <div class="flex items-center gap-4 mt-3 text-sm text-slate-600">
                                    <span><i class="fas fa-calendar mr-1"></i>{{ $subscription['fimeco']['debut'] }} - {{ $subscription['fimeco']['fin'] }}</span>
                                    <span><i class="fas fa-bullseye mr-1"></i>{{ number_format($subscription['fimeco']['cible'], 0, ',', ' ') }} FCFA</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($subscription['fimeco']['statut'] === 'active') bg-green-100 text-green-800
                                        @elseif($subscription['fimeco']['statut'] === 'cloturee') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($subscription['fimeco']['statut']) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Montant de souscription -->
                        <div>
                            <label for="montant_souscrit" class="block text-sm font-medium text-slate-700 mb-2">
                                Montant de souscription (FCFA) <span class="text-red-500">*</span>
                            </label>
                            @if($subscription['montant_paye'] > 0)
                                <div class="mb-3 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                    <div class="text-sm text-orange-800">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <strong>Attention:</strong> Cette souscription a déjà {{ number_format($subscription['montant_paye'], 0, ',', ' ') }} FCFA payés.
                                        Le nouveau montant doit être au moins égal à ce montant.
                                    </div>
                                </div>
                            @endif
                            <input type="number" id="montant_souscrit" name="montant_souscrit"
                                   value="{{ old('montant_souscrit', $subscription['montant_souscrit']) }}"
                                   required min="{{ $subscription['montant_paye'] }}" step="0.01" onchange="updateCalculations()"
                                   {{ in_array($subscription['statut'], ['annulee', 'completement_payee']) || $subscription['fimeco']['statut'] !== 'active' ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('montant_souscrit') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ in_array($subscription['statut'], ['annulee', 'completement_payee']) || $subscription['fimeco']['statut'] !== 'active' ? 'bg-slate-100' : '' }}">
                            @error('montant_souscrit')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-slate-500">
                                Montant minimum : {{ number_format($subscription['montant_paye'], 0, ',', ' ') }} FCFA
                                (montant déjà payé)
                            </p>
                        </div>

                        <!-- Version pour contrôle de concurrence -->
                        <input type="hidden" name="expected_version" value="{{ $subscription['version'] ?? 0 }}">

                        <!-- Affichage des calculs -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-blue-50 rounded-xl">
                                <div class="text-sm text-blue-700 mb-1">Montant souscrit</div>
                                <div id="display-montant-souscrit" class="text-lg font-bold text-blue-900">
                                    {{ number_format($subscription['montant_souscrit'], 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                            <div class="p-4 bg-green-50 rounded-xl">
                                <div class="text-sm text-green-700 mb-1">Montant payé</div>
                                <div class="text-lg font-bold text-green-900">
                                    {{ number_format($subscription['montant_paye'], 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                            <div class="p-4 bg-orange-50 rounded-xl">
                                <div class="text-sm text-orange-700 mb-1">Reste à payer</div>
                                <div id="display-reste-payer" class="text-lg font-bold text-orange-900">
                                    {{ number_format($subscription['reste_a_payer'], 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        </div>

                        <!-- Barre de progression -->
                        @php
                            $pourcentagePaye = ($subscription['montant_souscrit'] > 0) ?
                                              round(($subscription['montant_paye'] / $subscription['montant_souscrit']) * 100, 1) : 0;
                        @endphp
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Progression des paiements</span>
                                <span id="display-pourcentage" class="font-semibold">{{ $pourcentagePaye }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div id="progress-bar" class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-300"
                                     style="width: {{ $pourcentagePaye }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar avec informations -->
            <div class="space-y-6">
                <!-- Informations actuelles -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                            Informations Actuelles
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Date de souscription:</span>
                            <span class="text-sm text-slate-900">{{ \Carbon\Carbon::parse($subscription['date_souscription'])->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Statut:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($subscription['statut'] === 'completement_payee') bg-green-100 text-green-800
                                @elseif($subscription['statut'] === 'partiellement_payee') bg-yellow-100 text-yellow-800
                                @elseif($subscription['statut'] === 'active') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $subscription['statut'])) }}
                            </span>
                        </div>
                        @if($subscription['date_echeance'])
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Échéance:</span>
                                <span class="text-sm text-slate-900">{{ \Carbon\Carbon::parse($subscription['date_echeance'])->format('d/m/Y') }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Version:</span>
                            <span class="text-sm text-slate-600">#{{ $subscription['version'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>

                <!-- Historique des paiements -->
                @if(isset($subscription['payments']) && count($subscription['payments']) > 0)
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-history text-green-600 mr-2"></i>
                                Historique des Paiements
                            </h2>
                        </div>
                        <div class="p-6 space-y-3">
                            @foreach(collect($subscription['payments'])->where('statut', 'valide')->take(5) as $payment)
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <div>
                                        <div class="text-sm font-medium text-slate-900">
                                            {{ number_format($payment['montant'], 0, ',', ' ') }} FCFA
                                        </div>
                                        <div class="text-xs text-slate-600">
                                            {{ \Carbon\Carbon::parse($payment['date_paiement'])->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Validé
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Actions rapides -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                            Actions Rapides
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('private.subscriptions.show', $subscription['id']) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-xl hover:bg-cyan-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i> Voir Détails
                        </a>
                        @if($subscription['reste_a_payer'] > 0 && $subscription['statut'] !== 'annulee')
                            <a href="{{ route('private.paiements.store') }}?subscription_id={{ $subscription['id'] }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                <i class="fas fa-credit-card mr-2"></i> Effectuer un Paiement
                            </a>
                        @endif
                        <a href="{{ route('private.paiements.index', ['subscription_id' => $subscription['id']]) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                            <i class="fas fa-list mr-2"></i> Voir Paiements
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if(!in_array($subscription['statut'], ['annulee', 'completement_payee']) && $subscription['fimeco']['statut'] === 'active')
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Mettre à jour
                        </button>
                    @endif
                    <a href="{{ route('private.subscriptions.show', $subscription['id']) }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i> Voir Souscription
                    </a>
                    <a href="{{ route('private.subscriptions.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-list mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Mise à jour des calculs en temps réel
function updateCalculations() {
    const montantSouscrit = parseFloat(document.getElementById('montant_souscrit').value) || 0;
    const montantPaye = {{ $subscription['montant_paye'] }};
    const resteAPayer = Math.max(0, montantSouscrit - montantPaye);
    const pourcentage = montantSouscrit > 0 ? Math.round((montantPaye / montantSouscrit) * 100 * 10) / 10 : 0;

    // Mise à jour de l'affichage
    document.getElementById('display-montant-souscrit').textContent =
        new Intl.NumberFormat('fr-FR').format(montantSouscrit) + ' FCFA';
    document.getElementById('display-reste-payer').textContent =
        new Intl.NumberFormat('fr-FR').format(resteAPayer) + ' FCFA';
    document.getElementById('display-pourcentage').textContent = pourcentage + '%';

    // Mise à jour de la barre de progression
    document.getElementById('progress-bar').style.width = Math.min(pourcentage, 100) + '%';
}

// Validation du formulaire
document.getElementById('subscriptionEditForm')?.addEventListener('submit', function(e) {
    const montantSouscrit = parseFloat(document.getElementById('montant_souscrit').value);
    const montantPaye = {{ $subscription['montant_paye'] }};

    if (montantSouscrit < montantPaye) {
        e.preventDefault();
        alert(`Le montant de souscription ne peut pas être inférieur au montant déjà payé (${new Intl.NumberFormat('fr-FR').format(montantPaye)} FCFA).`);
        return false;
    }

    if (montantSouscrit < 10) {
        e.preventDefault();
        alert('Le montant de souscription doit être d\'au moins 10 FCFA.');
        return false;
    }

    // Confirmation si changement significatif
    const montantOriginal = {{ $subscription['montant_souscrit'] }};
    if (Math.abs(montantSouscrit - montantOriginal) > 1000) {
        const confirmation = confirm(
            `Vous modifiez significativement le montant de souscription :\n` +
            `Ancien: ${new Intl.NumberFormat('fr-FR').format(montantOriginal)} FCFA\n` +
            `Nouveau: ${new Intl.NumberFormat('fr-FR').format(montantSouscrit)} FCFA\n\n` +
            `Confirmez-vous cette modification ?`
        );

        if (!confirmation) {
            e.preventDefault();
            return false;
        }
    }
});

// Événements
document.getElementById('montant_souscrit')?.addEventListener('input', updateCalculations);

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updateCalculations();
});
</script>
@endpush
@endsection
