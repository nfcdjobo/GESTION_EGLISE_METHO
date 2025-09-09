@extends('layouts.private.main')
@section('title', 'Modifier le Paiement')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier le Paiement</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.paiements.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-credit-card mr-2"></i>
                        Paiements
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <a href="{{ route('private.paiements.show', $payment['id']) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            Paiement #{{ substr($payment['id'], 0, 8) }}
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
    @if($payment['statut'] !== 'en_attente')
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
            <div class="flex">
                <i class="fas fa-exclamation-triangle text-red-400 mt-0.5 mr-3"></i>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Paiement non modifiable</h3>
                    <p class="text-sm text-red-700 mt-1">
                        Seuls les paiements en attente peuvent être modifiés. Ce paiement a le statut :
                        <strong>{{ ucfirst(str_replace('_', ' ', $payment['statut'])) }}</strong>
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if($payment['subscription']['fimeco']['statut'] !== 'active')
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
            <div class="flex">
                <i class="fas fa-info-circle text-yellow-400 mt-0.5 mr-3"></i>
                <div>
                    <h3 class="text-sm font-medium text-yellow-800">FIMECO inactive</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        La FIMECO associée n'est plus active. Les modifications sont limitées.
                    </p>
                </div>
            </div>
        </div>
    @endif

    @can('paiements.create')
    <form action="{{ route('private.paiements.update', $payment['id']) }}" method="POST" id="paymentEditForm" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulaire principal -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-edit text-blue-600 mr-2"></i>
                            Modifier le Paiement
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Montant -->
                        <div>
                            <label for="montant" class="block text-sm font-medium text-slate-700 mb-2">
                                Montant (FCFA) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="montant" name="montant"
                                   value="{{ old('montant', $payment['montant']) }}"
                                   required min="1" max="999999.99" step="0.01" onchange="updateCalculations()"
                                   {{ $payment['statut'] !== 'en_attente' ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('montant') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ $payment['statut'] !== 'en_attente' ? 'bg-slate-100' : '' }}">
                            @error('montant')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-slate-500">
                                Maximum autorisé : {{ number_format($payment['subscription']['reste_a_payer'] + $payment['montant'], 0, ',', ' ') }} FCFA
                            </p>
                        </div>

                        <!-- Type de paiement -->
                        <div>
                            <label for="type_paiement" class="block text-sm font-medium text-slate-700 mb-2">
                                Type de paiement <span class="text-red-500">*</span>
                            </label>
                            <select id="type_paiement" name="type_paiement" required
                                    {{ $payment['statut'] !== 'en_attente' ? 'disabled' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_paiement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ $payment['statut'] !== 'en_attente' ? 'bg-slate-100' : '' }}">
                                @foreach($typesPaiement as $key => $label)
                                    <option value="{{ $key }}" {{ old('type_paiement', $payment['type_paiement']) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_paiement')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Référence de paiement -->
                        <div>
                            <label for="reference_paiement" class="block text-sm font-medium text-slate-700 mb-2">
                                Référence de paiement
                            </label>
                            <input type="text" id="reference_paiement" name="reference_paiement"
                                   value="{{ old('reference_paiement', $payment['reference_paiement']) }}"
                                   maxlength="100" placeholder="N° chèque, référence virement, etc."
                                   {{ $payment['statut'] !== 'en_attente' ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('reference_paiement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ $payment['statut'] !== 'en_attente' ? 'bg-slate-100' : '' }}">
                            @error('reference_paiement')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-slate-500">Optionnel - utile pour le suivi</p>
                        </div>

                        <!-- Date de paiement -->
                        <div>
                            <label for="date_paiement" class="block text-sm font-medium text-slate-700 mb-2">
                                Date du paiement <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="date_paiement" name="date_paiement"
                                   value="{{ old('date_paiement', \Carbon\Carbon::parse($payment['date_paiement'])->format('Y-m-d')) }}"
                                   required max="{{ date('Y-m-d') }}"
                                   {{ $payment['statut'] !== 'en_attente' ? 'readonly' : '' }}
                                   class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_paiement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ $payment['statut'] !== 'en_attente' ? 'bg-slate-100' : '' }}">
                            @error('date_paiement')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-slate-500">Ne peut pas être dans le futur</p>
                        </div>

                        <!-- Commentaire -->
                        <div>
                            <label for="commentaire" class="block text-sm font-medium text-slate-700 mb-2">Commentaire (optionnel)</label>
                            <textarea id="commentaire" name="commentaire" rows="3" maxlength="500"
                                      placeholder="Détails supplémentaires sur ce paiement"
                                      {{ $payment['statut'] !== 'en_attente' ? 'readonly' : '' }}
                                      class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('commentaire') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ $payment['statut'] !== 'en_attente' ? 'bg-slate-100' : '' }}">{{ old('commentaire', $payment['commentaire']) }}</textarea>
                            @error('commentaire')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-slate-500">Maximum 500 caractères</p>
                        </div>

                        <!-- Calculs dynamiques -->
                        <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-xl p-6">
                            <h3 class="font-semibold text-slate-900 mb-4">Impact sur la Souscription</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                    <div class="text-sm text-slate-600 mb-1">Reste avant</div>
                                    <div class="text-lg font-bold text-orange-600">{{ number_format($payment['ancien_reste'], 0, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                    <div class="text-sm text-slate-600 mb-1">Nouveau montant</div>
                                    <div id="display-nouveau-montant" class="text-lg font-bold text-blue-600">{{ number_format($payment['montant'], 0, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="text-center p-3 bg-white rounded-lg shadow-sm">
                                    <div class="text-sm text-slate-600 mb-1">Reste après</div>
                                    <div id="display-nouveau-reste" class="text-lg font-bold text-green-600">{{ number_format($payment['nouveau_reste'], 0, ',', ' ') }} FCFA</div>
                                </div>
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
                            <span class="text-sm font-medium text-slate-700">Statut:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($payment['statut'] === 'valide') bg-green-100 text-green-800
                                @elseif($payment['statut'] === 'en_attente') bg-yellow-100 text-yellow-800
                                @elseif($payment['statut'] === 'refuse') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $payment['statut'])) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Créé le:</span>
                            <span class="text-sm text-slate-900">{{ \Carbon\Carbon::parse($payment['created_at'])->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($payment['updated_at'] !== $payment['created_at'])
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Modifié le:</span>
                                <span class="text-sm text-slate-900">{{ \Carbon\Carbon::parse($payment['updated_at'])->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                        @if($payment['validateur'])
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Validé par:</span>
                                <span class="text-sm text-slate-900">{{ $payment['validateur']['name'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Souscription associée -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-link text-green-600 mr-2"></i>
                            Souscription
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <h3 class="font-medium text-slate-900">{{ $payment['subscription']['fimeco']['nom'] }}</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Souscrit:</span>
                                <span class="font-medium">{{ number_format($payment['subscription']['montant_souscrit'], 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Payé:</span>
                                <span class="font-medium text-green-600">{{ number_format($payment['subscription']['montant_paye'], 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Reste:</span>
                                <span class="font-medium text-orange-600">{{ number_format($payment['subscription']['reste_a_payer'], 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                        @php
                            $pourcentagePaye = ($payment['subscription']['montant_souscrit'] > 0) ?
                                              round(($payment['subscription']['montant_paye'] / $payment['subscription']['montant_souscrit']) * 100, 1) : 0;
                        @endphp
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full" style="width: {{ $pourcentagePaye }}%"></div>
                        </div>
                        <div class="text-center text-sm font-medium text-slate-700">{{ $pourcentagePaye }}% payé</div>
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
                        <a href="{{ route('private.paiements.show', $payment['id']) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-xl hover:bg-cyan-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i> Voir Détails
                        </a>
                        <a href="{{ route('private.subscriptions.show', $payment['subscription']['id']) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-hand-holding-usd mr-2"></i> Voir Souscription
                        </a>
                        <a href="{{ route('private.paiements.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-list mr-2"></i> Tous les Paiements
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if($payment['statut'] === 'en_attente')
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Mettre à jour
                        </button>
                    @endif
                    <a href="{{ route('private.paiements.show', $payment['id']) }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i> Voir Paiement
                    </a>
                    <a href="{{ route('private.paiements.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-list mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </form>
    @endcan
</div>

@push('scripts')
<script>
// Mise à jour des calculs en temps réel
function updateCalculations() {
    const montant = parseFloat(document.getElementById('montant').value) || 0;
    const ancienReste = {{ $payment['ancien_reste'] }};
    const nouveauReste = Math.max(0, ancienReste - montant);

    // Mise à jour de l'affichage
    document.getElementById('display-nouveau-montant').textContent =
        new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
    document.getElementById('display-nouveau-reste').textContent =
        new Intl.NumberFormat('fr-FR').format(nouveauReste) + ' FCFA';
}

// Validation du formulaire
document.getElementById('paymentEditForm')?.addEventListener('submit', function(e) {
    const montant = parseFloat(document.getElementById('montant').value);
    const maxMontant = {{ $payment['subscription']['reste_a_payer'] + $payment['montant'] }};
    const datePaiement = document.getElementById('date_paiement').value;

    if (montant <= 0) {
        e.preventDefault();
        alert('Le montant doit être supérieur à 0 FCFA.');
        return false;
    }

    if (montant > maxMontant) {
        e.preventDefault();
        alert(`Le montant ne peut pas dépasser ${new Intl.NumberFormat('fr-FR').format(maxMontant)} FCFA.`);
        return false;
    }

    // Vérifier que la date n'est pas dans le futur
    if (datePaiement) {
        const datePayment = new Date(datePaiement);
        const aujourdhui = new Date();
        aujourdhui.setHours(23, 59, 59, 999);

        if (datePayment > aujourdhui) {
            e.preventDefault();
            alert('La date de paiement ne peut pas être dans le futur.');
            return false;
        }
    }

    // Confirmation si changement significatif
    const montantOriginal = {{ $payment['montant'] }};
    if (Math.abs(montant - montantOriginal) > 1000) {
        const confirmation = confirm(
            `Vous modifiez significativement le montant du paiement :\n` +
            `Ancien: ${new Intl.NumberFormat('fr-FR').format(montantOriginal)} FCFA\n` +
            `Nouveau: ${new Intl.NumberFormat('fr-FR').format(montant)} FCFA\n\n` +
            `Confirmez-vous cette modification ?`
        );

        if (!confirmation) {
            e.preventDefault();
            return false;
        }
    }
});

// Événements
document.getElementById('montant')?.addEventListener('input', updateCalculations);

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updateCalculations();
});
</script>
@endpush
@endsection
