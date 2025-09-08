@extends('layouts.private.main')
@section('title', 'Nouveau Paiement')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Nouveau Paiement</h1>
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
                        <span class="text-sm font-medium text-slate-500">Nouveau</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Sélection de souscription (si pas définie) -->
    @if(!request('subscription'))
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-hand-holding-usd text-blue-600 mr-2"></i>
                    Sélectionner une Souscription
                </h2>
                <p class="text-slate-500 mt-1">Choisissez la souscription pour laquelle vous souhaitez effectuer un paiement</p>
            </div>
            <div class="p-6">
                <!-- Liste des souscriptions avec reste à payer -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="subscriptions-list">
                    <!-- Les souscriptions seront chargées dynamiquement ou affichées ici -->
                    <div class="text-center py-8 col-span-full">
                        <i class="fas fa-spinner fa-spin text-3xl text-slate-400 mb-4"></i>
                        <p class="text-slate-500">Chargement de vos souscriptions...</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Formulaire de paiement -->
        <form action="{{ route('private.paiements.store') }}" method="POST" id="paymentForm" class="space-y-8">
            @csrf
            <input type="hidden" name="subscription_id" value="{{ request('subscription') }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Formulaire principal -->
                <div class="lg:col-span-2">
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-credit-card text-blue-600 mr-2"></i>
                                Informations du Paiement
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Montant -->
                            <div>
                                <label for="montant" class="block text-sm font-medium text-slate-700 mb-2">
                                    Montant (FCFA) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" id="montant" name="montant" value="{{ old('montant') }}"
                                       required min="1" step="0.01" placeholder="{{$montantSuggere}}" onchange="updatePreview()"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('montant') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('montant')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500" id="montant-limite">Montant maximum : ...</p>
                            </div>

                            <!-- Type de paiement -->
                            <div>
                                <label for="type_paiement" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de paiement <span class="text-red-500">*</span>
                                </label>
                                <select id="type_paiement" name="type_paiement" required onchange="updateReferenceField()"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_paiement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un type</option>
                                    <option value="especes" {{ old('type_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                                    <option value="cheque" {{ old('type_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                                    <option value="virement" {{ old('type_paiement') == 'virement' ? 'selected' : '' }}>Virement bancaire</option>
                                    <option value="carte" {{ old('type_paiement') == 'carte' ? 'selected' : '' }}>Carte bancaire</option>
                                    <option value="mobile_money" {{ old('type_paiement') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                </select>
                                @error('type_paiement')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Référence de paiement -->
                            <div>
                                <label for="reference_paiement" class="block text-sm font-medium text-slate-700 mb-2" id="reference-label">
                                    Référence de paiement
                                </label>
                                <input type="text" id="reference_paiement" name="reference_paiement"
                                       value="{{ old('reference_paiement') }}" maxlength="100"
                                       placeholder="Ex: Numéro de chèque, référence de virement..."
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('reference_paiement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('reference_paiement')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500" id="reference-help">Optionnel - Référence pour identifier le paiement</p>
                            </div>

                            <!-- Date de paiement -->
                            <div>
                                <label for="date_paiement" class="block text-sm font-medium text-slate-700 mb-2">
                                    Date du paiement <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="date_paiement" name="date_paiement"
                                       value="{{ old('date_paiement', date('Y-m-d')) }}"
                                       required max="{{ date('Y-m-d') }}"
                                       class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_paiement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('date_paiement')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Date à laquelle le paiement a été effectué</p>
                            </div>

                            <!-- Commentaire -->
                            <div>
                                <label for="commentaire" class="block text-sm font-medium text-slate-700 mb-2">Commentaire (optionnel)</label>
                                <textarea id="commentaire" name="commentaire" rows="3" maxlength="500"
                                          placeholder="Détails ou informations supplémentaires sur ce paiement..."
                                          class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('commentaire') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('commentaire') }}</textarea>
                                @error('commentaire')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div class="flex justify-between mt-1">
                                    <p class="text-sm text-slate-500">Informations supplémentaires sur le paiement</p>
                                    <p class="text-sm text-slate-400"><span id="char-count">0</span>/500</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar avec informations -->
                <div class="space-y-6">
                    <!-- Aperçu du paiement -->
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-eye text-purple-600 mr-2"></i>
                                Aperçu
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Montant:</span>
                                <span id="preview-montant" class="text-sm text-green-600 font-semibold">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Type:</span>
                                <span id="preview-type" class="text-sm text-slate-900">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Date:</span>
                                <span id="preview-date" class="text-sm text-slate-600">{{ date('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Statut:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    En attente
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Informations sur la souscription -->
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-hand-holding-usd text-green-600 mr-2"></i>
                                Souscription
                            </h2>
                        </div>
                        <div class="p-6" id="subscription-info">
                            <div class="text-center py-4">
                                <i class="fas fa-spinner fa-spin text-slate-400"></i>
                                <p class="text-slate-500 text-sm mt-2">Chargement...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Guide des types de paiement -->
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-info text-amber-600 mr-2"></i>
                                Guide des Paiements
                            </h2>
                        </div>
                        <div class="p-6 space-y-3 text-sm">
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-money-bill-wave text-green-500 mt-1"></i>
                                <div>
                                    <strong>Espèces:</strong> Paiement en liquide
                                </div>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-money-check text-blue-500 mt-1"></i>
                                <div>
                                    <strong>Chèque:</strong> Indiquez le numéro du chèque
                                </div>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-university text-purple-500 mt-1"></i>
                                <div>
                                    <strong>Virement:</strong> Référence de transaction
                                </div>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-credit-card text-indigo-500 mt-1"></i>
                                <div>
                                    <strong>Carte:</strong> Paiement par carte bancaire
                                </div>
                            </div>
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-mobile-alt text-orange-500 mt-1"></i>
                                <div>
                                    <strong>Mobile Money:</strong> Orange Money, MTN, etc.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-credit-card mr-2"></i> Enregistrer le Paiement
                        </button>
                        <a href="{{ route('private.paiements.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>

@push('scripts')
<script>
// Variables globales
let subscriptionData = null;

// Chargement des souscriptions disponibles
@if(!request('subscription_id'))
document.addEventListener('DOMContentLoaded', function() {
    loadAvailableSubscriptions();
});

function loadAvailableSubscriptions() {
    // Simuler le chargement des souscriptions
    setTimeout(() => {
        const subscriptionsList = document.getElementById('subscriptions-list');
        if(!subscriptionsList) return;
        subscriptionsList.innerHTML = `
            <div class="text-center py-8 col-span-full">
                <i class="fas fa-info-circle text-3xl text-slate-400 mb-4"></i>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Accès direct aux paiements</h3>
                <p class="text-slate-500 mb-6">Rendez-vous dans vos souscriptions pour effectuer un paiement sur une souscription spécifique.</p>
                <a href="{{ route('private.subscriptions.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-hand-holding-usd mr-2"></i> Voir mes Souscriptions
                </a>
            </div>
        `;
    }, 1000);
}
@else
// Chargement des informations de souscription
document.addEventListener('DOMContentLoaded', function() {
    loadSubscriptionInfo();
    updatePreview();
});

function loadSubscriptionInfo() {
    // Simuler le chargement des informations de souscription
    const subscriptionInfo = document.getElementById('subscription-info');

    // En réalité, ces données viendraient d'une API ou du serveur
    setTimeout(() => {
        subscriptionInfo.innerHTML = `
            <div class="space-y-4">
                <h3 class="font-medium text-slate-900">FIMECO Exemple</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Souscrit:</span>
                        <span class="font-medium">100,000 FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Payé:</span>
                        <span class="font-medium text-green-600">40,000 FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Reste:</span>
                        <span class="font-medium text-orange-600" id="reste-a-payer">60,000 FCFA</span>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full" style="width: 40%"></div>
                </div>
                <div class="text-center text-sm font-medium text-slate-700">40% payé</div>
            </div>
        `;

        // Mettre à jour la limite de montant
        document.getElementById('montant-limite').textContent = 'Montant maximum : 60,000 FCFA';
        document.getElementById('montant').setAttribute('max', '60000');
    }, 500);
}
@endif

// Mise à jour de l'aperçu
function updatePreview() {
    const montant = document.getElementById('montant')?.value;
    const type = document.getElementById('type_paiement')?.value;
    const date = document.getElementById('date_paiement')?.value;

    // Montant
    if (montant && montant > 0) {
        document.getElementById('preview-montant').textContent =
            new Intl.NumberFormat('fr-FR').format(montant) + ' FCFA';
    } else {
        document.getElementById('preview-montant').textContent = '-';
    }

    // Type
    if (type) {
        const typeLabels = {
            'especes': 'Espèces',
            'cheque': 'Chèque',
            'virement': 'Virement',
            'carte': 'Carte bancaire',
            'mobile_money': 'Mobile Money'
        };
        document.getElementById('preview-type').textContent = typeLabels[type] || type;
    } else {
        document.getElementById('preview-type').textContent = '-';
    }

    // Date
    if (date) {
        const dateObj = new Date(date);
        document.getElementById('preview-date').textContent = dateObj.toLocaleDateString('fr-FR');
    }
}

// Mise à jour du champ référence selon le type
function updateReferenceField() {
    const type = document.getElementById('type_paiement').value;
    const referenceField = document.getElementById('reference_paiement');
    const referenceLabel = document.getElementById('reference-label');
    const referenceHelp = document.getElementById('reference-help');

    switch(type) {
        case 'cheque':
            referenceLabel.textContent = 'Numéro de chèque';
            referenceField.placeholder = 'Ex: 1234567';
            referenceHelp.textContent = 'Numéro du chèque pour identification';
            break;
        case 'virement':
            referenceLabel.textContent = 'Référence de virement';
            referenceField.placeholder = 'Ex: REF123456789';
            referenceHelp.textContent = 'Référence de transaction fournie par la banque';
            break;
        case 'mobile_money':
            referenceLabel.textContent = 'ID de transaction';
            referenceField.placeholder = 'Ex: OM123456789';
            referenceHelp.textContent = 'ID de transaction Mobile Money';
            break;
        case 'carte':
            referenceLabel.textContent = 'Référence de transaction';
            referenceField.placeholder = 'Ex: 4 derniers chiffres de la carte';
            referenceHelp.textContent = 'Référence pour identifier la transaction';
            break;
        default:
            referenceLabel.textContent = 'Référence de paiement';
            referenceField.placeholder = 'Ex: Référence interne';
            referenceHelp.textContent = 'Optionnel - Référence pour identifier le paiement';
    }

    updatePreview();
}

// Compteur de caractères pour le commentaire
document.getElementById('commentaire')?.addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('char-count').textContent = count;

    if (count > 400) {
        document.getElementById('char-count').className = 'text-orange-500 font-medium';
    } else if (count > 450) {
        document.getElementById('char-count').className = 'text-red-500 font-bold';
    } else {
        document.getElementById('char-count').className = 'text-slate-400';
    }
});

// Validation du formulaire
document.getElementById('paymentForm')?.addEventListener('submit', function(e) {
    const montant = parseFloat(document.getElementById('montant').value);
    const type = document.getElementById('type_paiement').value;
    const date = document.getElementById('date_paiement').value;

    if (!montant || montant <= 0) {
        e.preventDefault();
        alert('Veuillez saisir un montant valide.');
        return false;
    }

    if (!type) {
        e.preventDefault();
        alert('Veuillez sélectionner un type de paiement.');
        return false;
    }

    if (!date) {
        e.preventDefault();
        alert('Veuillez indiquer la date du paiement.');
        return false;
    }

    // Vérifier que la date n'est pas dans le futur
    const datePayment = new Date(date);
    const aujourdhui = new Date();
    aujourdhui.setHours(23, 59, 59, 999);

    if (datePayment > aujourdhui) {
        e.preventDefault();
        alert('La date de paiement ne peut pas être dans le futur.');
        return false;
    }

    // Confirmation
    const confirmation = confirm(
        `Confirmez-vous l'enregistrement de ce paiement de ${new Intl.NumberFormat('fr-FR').format(montant)} FCFA ?`
    );

    if (!confirmation) {
        e.preventDefault();
        return false;
    }
});

// Événements
document.getElementById('montant')?.addEventListener('input', updatePreview);
document.getElementById('type_paiement')?.addEventListener('change', updateReferenceField);
document.getElementById('date_paiement')?.addEventListener('change', updatePreview);
</script>
@endpush
@endsection
