@extends('layouts.private.main')
@section('title', 'Détails du Paiement')

@section('content')
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Paiement #{{ substr($payment['id'], 0, 8) }}
            </h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.paiements.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-credit-card mr-2"></i>
                            Paiements
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-medium text-slate-500">Paiement
                                #{{ substr($payment['id'], 0, 8) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <p class="text-slate-500 mt-1">{{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
        </div>

        <!-- Statut et actions rapides -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if ($payment['statut'] === 'valide') bg-green-100 text-green-800
                        @elseif($payment['statut'] === 'en_attente') bg-yellow-100 text-yellow-800
                        @elseif($payment['statut'] === 'refuse') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                            @if ($payment['statut'] === 'valide')
                                <i class="fas fa-check-circle mr-2"></i>
                            @elseif($payment['statut'] === 'en_attente')
                                <i class="fas fa-clock mr-2"></i>
                            @elseif($payment['statut'] === 'refuse')
                                <i class="fas fa-times-circle mr-2"></i>
                            @else
                                <i class="fas fa-ban mr-2"></i>
                            @endif
                            {{ ucfirst(str_replace('_', ' ', $payment['statut'])) }}
                        </span>
                        <div class="text-2xl font-bold text-slate-900">
                            {{ number_format($payment['montant'], 0, ',', ' ') }} FCFA
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @can('paiements.update')
                            @if ($payment['statut'] === 'en_attente')
                                <a href="{{ route('private.paiements.edit', $payment['id']) }}"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <i class="fas fa-edit mr-2"></i> Modifier
                                </a>
                            @endif
                        @endcan
                        <a href="{{ route('private.subscriptions.show', $payment['subscription']['id']) }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-eye mr-2"></i> Voir Souscription
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Détails du paiement -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-file-invoice-dollar text-blue-600 mr-2"></i>
                            Informations du Paiement
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-slate-700 mb-2">Date du paiement</h4>
                                <p class="text-slate-900 text-lg">
                                    {{ \Carbon\Carbon::parse($payment['date_paiement'])->format('d/m/Y à H:i') }}</p>
                            </div>
                            <div>
                                <h4 class="font-medium text-slate-700 mb-2">Type de paiement</h4>
                                <p class="text-slate-900 text-lg capitalize">
                                    {{ str_replace('_', ' ', $payment['type_paiement']) }}</p>
                            </div>
                            @if ($payment['reference_paiement'])
                                <div>
                                    <h4 class="font-medium text-slate-700 mb-2">Référence</h4>
                                    <code
                                        class="px-3 py-2 bg-slate-100 text-slate-800 rounded-lg text-sm">{{ $payment['reference_paiement'] }}</code>
                                </div>
                            @endif
                        </div>

                        <!-- Montants -->
                        <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-xl p-6">
                            <h3 class="font-semibold text-slate-900 mb-4">Détail des Montants</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">
                                        {{ number_format($payment['montant'], 0, ',', ' ') }}</div>
                                    <div class="text-sm text-green-700">Montant payé</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-slate-600">
                                        {{ number_format($payment['ancien_reste'], 0, ',', ' ') }}</div>
                                    <div class="text-sm text-slate-500">Reste avant</div>
                                </div>
                                {{-- <div class="text-center">
                                    <div class="text-lg font-semibold text-orange-600">{{ number_format($payment['nouveau_reste'], 0, ',', ' ') >= 0 ? number_format($payment['nouveau_reste'], 0, ',', ' ') : '+'. (-1*number_format($payment['nouveau_reste'], 0, ',', ' ')) }}</div>
                                    <div class="text-sm text-orange-700">{{$payment['nouveau_reste'] >= 0 ? 'Reste après' : 'Montant supplementaire'}}</div>
                                </div> --}}
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-orange-600">
                                        {{ ($payment['nouveau_reste'] < 0 ? '+' : '') . number_format(abs($payment['nouveau_reste']), 0, ',', ' ') }}
                                    </div>
                                    <div class="text-sm text-orange-700">
                                        {{ $payment['nouveau_reste'] >= 0 ? 'Reste après' : 'Montant supplementaire' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Commentaire -->
                        @if ($payment['commentaire'])
                            <div>
                                <h4 class="font-medium text-slate-700 mb-2">Commentaire</h4>
                                <div class="p-4 bg-slate-50 rounded-lg">
                                    <p class="text-slate-700">{{ $payment['commentaire'] }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Informations de validation -->
                @if ($payment['validateur'] || $payment['statut'] !== 'en_attente')
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-user-check text-green-600 mr-2"></i>
                                Informations de Validation
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @if ($payment['validateur'])
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <h4 class="font-medium text-slate-700 mb-2">Validé par</h4>
                                        <p class="text-slate-900">{{ $payment['validateur']['nom'] . ' '.$payment['validateur']['prenom'] }}</p>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-slate-700 mb-2">Contacts</h4>
                                        <p class="text-slate-900">{{ $payment['validateur']['telephone_1'] }}</p>
                                    </div>
                                    @if ($payment['date_validation'])
                                        <div>
                                            <h4 class="font-medium text-slate-700 mb-2">Date de validation</h4>
                                            <p class="text-slate-900">
                                                {{ \Carbon\Carbon::parse($payment['date_validation'])->format('d/m/Y à H:i') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if ($payment['statut'] === 'refuse' && $payment['commentaire'])
                                <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <h4 class="font-medium text-red-800 mb-2">Motif du refus</h4>
                                    <p class="text-red-700">{{ $payment['commentaire'] }}</p>
                                </div>
                            @endif

                            @if ($payment['statut'] === 'en_attente')
                                <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex">
                                        <i class="fas fa-info-circle text-yellow-400 mt-0.5 mr-3"></i>
                                        <div class="text-sm text-yellow-800">
                                            <p class="font-medium">En attente de validation</p>
                                            <p class="mt-1">Votre paiement est en cours de vérification par les
                                                responsables de la FIMECO.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Souscription associée -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-link text-purple-600 mr-2"></i>
                            Souscription Associée
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">
                                    {{ $payment['subscription']['fimeco']['nom'] }}</h3>
                                <p class="text-sm text-slate-600">Souscrit le
                                    {{ \Carbon\Carbon::parse($payment['subscription']['date_souscription'])->format('d/m/Y') }}
                                </p>
                            </div>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if ($payment['subscription']['statut'] === 'completement_payee') bg-green-100 text-green-800
                            @elseif($payment['subscription']['statut'] === 'partiellement_payee') bg-yellow-100 text-yellow-800
                            @elseif($payment['subscription']['statut'] === 'active') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $payment['subscription']['statut'])) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-lg font-bold text-blue-600">
                                    {{ number_format($payment['subscription']['montant_souscrit'], 0, ',', ' ') }}</div>
                                <div class="text-sm text-blue-700">Souscrit</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-lg font-bold text-green-600">
                                    {{ number_format($payment['subscription']['montant_paye'], 0, ',', ' ') }}</div>
                                <div class="text-sm text-green-700">Payé</div>
                            </div>
                            <div class="text-center p-3 bg-orange-50 rounded-lg">
                                <div class="text-lg font-bold text-orange-600">
                                    {{ number_format($payment['subscription']['reste_a_payer'], 0, ',', ' ') }}</div>
                                <div class="text-sm text-orange-700">Reste</div>
                            </div>
                        </div>

                        @php
                            $pourcentagePaye =
                                $payment['subscription']['montant_souscrit'] > 0
                                    ? round(
                                        ($payment['subscription']['montant_paye'] /
                                            $payment['subscription']['montant_souscrit']) *
                                            100,
                                        1,
                                    )
                                    : 0;
                        @endphp

                        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full"
                                style="width: {{ $pourcentagePaye < 100 ? $pourcentagePaye : 100 }}%"></div>
                        </div>
                        <div class="text-center text-sm font-medium text-slate-700">{{ $pourcentagePaye }}% payé</div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Actions rapides -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                            Actions
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        @if ($payment['statut'] === 'en_attente')
                            <!-- Bouton Valider - Vert pour action positive -->
                            @can('paiements.validate')
                            <button onclick="valider()"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                <i class="fas fa-check-circle mr-2"></i> Valider
                            </button>
                            @endcan

                            <!-- Bouton Modifier - Bleu pour action neutre -->
                            @can('paiements.update')
                            <a href="{{ route('private.paiements.edit', $payment['id']) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                                <i class="fas fa-pen mr-2"></i> Modifier ce Paiement
                            </a>
                            @endcan
                        @endif

                        <!-- Bouton Voir Souscription - Orange pour visualisation -->
                        <a href="{{ route('private.subscriptions.show', $payment['subscription']['id']) }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-orange-600 to-amber-600 text-white text-sm font-medium rounded-xl hover:from-orange-700 hover:to-amber-700 transition-all duration-200">
                            <i class="fas fa-file-contract mr-2"></i> Voir la souscription
                        </a>

                        <!-- Bouton Voir FIMECO - Violet -->
                        <a href="{{ route('private.fimecos.show', $payment['subscription']['fimeco']['id']) }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-violet-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-violet-700 transition-all duration-200">
                            <i class="fas fa-coins mr-2"></i> Voir la FIMECO
                        </a>

                        @can('paiements.create')
                        @if ($payment['subscription']['reste_a_payer'] > 0 && !in_array($payment['statut'], ['en_attente', 'annulee']))
                            <!-- Bouton Solder - Teal pour action financière -->
                            <a href="{{ route('private.paiements.create', $payment['subscription']['id']) }}"
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-teal-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-teal-700 hover:to-cyan-700 transition-all duration-200">
                                <i class="fas fa-credit-card mr-2"></i> Solder la souscription
                            </a>
                        @endif
                        @endcan

                        <!-- Bouton Retour - Gris pour navigation -->
                        <a href="{{ route('private.paiements.index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-slate-600 to-gray-600 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-gray-700 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                        </a>
                    </div>
                </div>

                <!-- Timeline du paiement -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-history text-amber-600 mr-2"></i>
                            Historique
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mt-1.5"></div>
                            <div>
                                <h3 class="font-medium text-slate-900">Paiement créé</h3>
                                <p class="text-sm text-slate-600">
                                    {{ \Carbon\Carbon::parse($payment['created_at'])->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>

                        @if ($payment['updated_at'] !== $payment['created_at'])
                            <div class="flex items-start space-x-3">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mt-1.5"></div>
                                <div>
                                    <h3 class="font-medium text-slate-900">Dernière modification</h3>
                                    <p class="text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($payment['updated_at'])->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($payment['date_validation'])
                            <div class="flex items-start space-x-3">
                                <div
                                    class="w-3 h-3 {{ $payment['statut'] === 'valide' ? 'bg-green-500' : 'bg-red-500' }} rounded-full mt-1.5">
                                </div>
                                <div>
                                    <h3 class="font-medium text-slate-900">
                                        {{ $payment['statut'] === 'valide' ? 'Paiement validé' : 'Paiement refusé' }}
                                    </h3>
                                    <p class="text-sm text-slate-600">
                                        {{ \Carbon\Carbon::parse($payment['date_validation'])->format('d/m/Y à H:i') }}</p>
                                    @if ($payment['validateur'])
                                        <p class="text-sm text-slate-500">par {{ $payment['validateur']['nom'] . ' '.$payment['validateur']['prenom']}}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


            </div>
        </div>
    </div>
    <script>
        // Function to handle payment validation en appelant l'api {{ route('private.paiements.valider', $payment['id']) }}
        function valider() {
            if (confirm('Êtes-vous sûr de vouloir valider ce paiement ?')) {
                fetch("{{ route('private.paiements.valider', $payment['id']) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Paiement validé avec succès.');
                            location.reload();
                        } else {
                            alert('Erreur lors de la validation du paiement : ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Une erreur est survenue lors de la validation du paiement.');
                    });
            }
        }
    </script>
@endsection
