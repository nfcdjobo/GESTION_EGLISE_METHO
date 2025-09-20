
@extends('layouts.private.main')
@section('title', 'Détails de la Souscription')

@section('content')
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Détails de la Souscription
                </h1>
                <p class="text-slate-500 mt-1">
                    {{ $subscription['souscripteur']['nom'] ?? 'Souscripteur' }} - {{ $subscription['fimeco']['nom'] ?? 'FIMECO' }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('private.subscriptions.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
                @can('subscriptions.update')
                    <a href="{{ route('private.subscriptions.edit', $subscription['id']) }}"
                        class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-xl hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                @endcan
                @can('subscriptions.report')
                    <button onclick="genererRapport()" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                        <i class="fas fa-file-alt mr-2"></i> Rapport
                    </button>
                @endcan
            </div>
        </div>

        <!-- Alertes -->
        @if(count($alertes) > 0)
            <div class="space-y-3">
                @foreach($alertes as $alerte)
                    <div class="p-4 rounded-xl border @if($alerte['type'] === 'danger') bg-red-50 border-red-200 @elseif($alerte['type'] === 'warning') bg-yellow-50 border-yellow-200 @else bg-blue-50 border-blue-200 @endif">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                @if($alerte['type'] === 'danger')
                                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                                @elseif($alerte['type'] === 'warning')
                                    <i class="fas fa-exclamation-circle text-yellow-400"></i>
                                @else
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm @if($alerte['type'] === 'danger') text-red-800 @elseif($alerte['type'] === 'warning') text-yellow-800 @else text-blue-800 @endif">
                                    {{ $alerte['message'] }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Vue d'ensemble -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Souscripteur et FIMECO -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                            Informations générales
                        </h2>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Souscripteur -->
                            <div class="flex items-start space-x-4">
                                @if($subscription['souscripteur']['photo_profil'] ?? false)
                                    <img class="h-16 w-16 rounded-xl object-cover"
                                         src="{{ asset('storage/' . $subscription['souscripteur']['photo_profil']) }}"
                                         alt="{{ $subscription['souscripteur']['nom'] }}">
                                @else
                                    <div class="h-16 w-16 rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                        <span class="text-xl font-bold text-white">
                                            {{ strtoupper(substr($subscription['souscripteur']['nom'] ?? 'U', 0, 1)) }}
                                        </span>
                                    </div>
                                @endif

                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-slate-900">{{ $subscription['souscripteur']['nom'] ?? 'N/A' }}</h3>
                                    @if($subscription['souscripteur']['email'] ?? false)
                                        <p class="text-sm text-slate-600">{{ $subscription['souscripteur']['email'] }}</p>
                                    @endif
                                    @if($subscription['souscripteur']['telephone_1'] ?? false)
                                        <p class="text-sm text-blue-600">
                                            <i class="fas fa-phone mr-1"></i>
                                            {{ $subscription['souscripteur']['telephone_1'] }}
                                        </p>
                                    @endif
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-2">
                                        Souscripteur
                                    </span>
                                </div>
                            </div>

                            <!-- FIMECO -->
                            <div class="border-l border-slate-200 pl-6">
                                <h3 class="text-lg font-bold text-slate-900">{{ $subscription['fimeco']['nom'] ?? 'N/A' }}</h3>
                                @if($subscription['fimeco']['description'] ?? false)
                                    <p class="text-sm text-slate-600 mt-1">{{ Str::limit($subscription['fimeco']['description'], 100) }}</p>
                                @endif

                                <div class="mt-3 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-600">Progression FIMECO:</span>
                                        <span class="font-medium">{{ number_format($subscription['fimeco']['progression'] ?? 0, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-blue-500 to-purple-500"
                                             style="width: {{ min($subscription['fimeco']['progression'] ?? 0, 100) }}%"></div>
                                    </div>
                                </div>

                                @if($subscription['fimeco']['responsable'] ?? false)
                                    <p class="text-xs text-slate-500 mt-2">
                                        Responsable: {{ $subscription['fimeco']['responsable']['nom'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progression de la souscription -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-chart-line text-green-600 mr-2"></i>
                            Progression de la souscription
                        </h2>
                    </div>

                    <div class="p-6">
                        <div class="space-y-6">
                            <!-- Barre de progression principale -->
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm font-medium text-slate-700">Progression globale</span>
                                    <span class="text-lg font-bold text-slate-900">{{ number_format($subscription['progression'] ?? 0, 1) }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 rounded-full h-4">
                                    <div class="h-4 rounded-full transition-all duration-500 {{ ($subscription['progression'] ?? 0) >= 100 ? 'bg-gradient-to-r from-green-500 to-emerald-500' : (($subscription['progression'] ?? 0) >= 75 ? 'bg-gradient-to-r from-blue-500 to-purple-500' : (($subscription['progression'] ?? 0) >= 50 ? 'bg-gradient-to-r from-yellow-500 to-orange-500' : 'bg-gradient-to-r from-red-500 to-pink-500')) }}"
                                         style="width: {{ min($subscription['progression'] ?? 0, 100) }}%"></div>
                                </div>
                            </div>

                            <!-- Montants détaillés -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center p-4 bg-slate-50 rounded-xl">
                                    <div class="text-2xl font-bold text-slate-900">{{ number_format($subscription['montant_souscrit'] ?? 0, 0, ',', ' ') }}</div>
                                    <div class="text-sm text-slate-600">Montant souscrit (FCFA)</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 rounded-xl">
                                    <div class="text-2xl font-bold text-green-600">{{ number_format($subscription['montant_paye'] ?? 0, 0, ',', ' ') }}</div>
                                    <div class="text-sm text-slate-600">Montant payé (FCFA)</div>
                                </div>
                                <div class="text-center p-4 bg-orange-50 rounded-xl">
                                    <div class="text-2xl font-bold text-orange-600">{{ number_format($subscription['reste_a_payer'] ?? 0, 0, ',', ' ') }}</div>
                                    <div class="text-sm text-slate-600">Reste à payer (FCFA)</div>
                                </div>
                            </div>

                            <!-- Étapes de progression -->
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                    <div class="w-full border-t border-slate-300"></div>
                                </div>
                                <div class="relative flex justify-between">
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full {{ ($subscription['progression'] ?? 0) >= 25 ? 'bg-green-500' : 'bg-slate-300' }} flex items-center justify-center">
                                            <i class="fas fa-play text-white text-xs"></i>
                                        </div>
                                        <div class="text-xs text-center mt-2">
                                            <div class="font-medium">Démarrage</div>
                                            <div class="text-slate-500">25%</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full {{ ($subscription['progression'] ?? 0) >= 50 ? 'bg-green-500' : 'bg-slate-300' }} flex items-center justify-center">
                                            <i class="fas fa-forward text-white text-xs"></i>
                                        </div>
                                        <div class="text-xs text-center mt-2">
                                            <div class="font-medium">Avancement</div>
                                            <div class="text-slate-500">50%</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full {{ ($subscription['progression'] ?? 0) >= 75 ? 'bg-green-500' : 'bg-slate-300' }} flex items-center justify-center">
                                            <i class="fas fa-fast-forward text-white text-xs"></i>
                                        </div>
                                        <div class="text-xs text-center mt-2">
                                            <div class="font-medium">Finalisation</div>
                                            <div class="text-slate-500">75%</div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <div class="w-8 h-8 rounded-full {{ ($subscription['progression'] ?? 0) >= 100 ? 'bg-green-500' : 'bg-slate-300' }} flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs"></i>
                                        </div>
                                        <div class="text-xs text-center mt-2">
                                            <div class="font-medium">Terminé</div>
                                            <div class="text-slate-500">100%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Historique des paiements -->
                <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl border border-white/30 hover:shadow-2xl transition-all duration-500">
                    <div class="p-6 border-b border-slate-200/50">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-history text-white text-lg"></i>
                                </div>
                                Historique des paiements
                                <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 text-slate-700">
                                    {{ count($historique_paiements) }}
                                </span>
                            </h2>
                            @if($peut_effectuer_paiement && ($montant_maximum_payable ?? 0) > 0)
                                <button onclick="ouvrirModalPaiement()"
                                        class="group relative inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-emerald-500 via-green-600 to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
                                    <!-- Effet de brillance -->
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                    <div class="w-5 h-5 bg-white/20 rounded-full flex items-center justify-center mr-2">
                                        <i class="fas fa-plus text-xs"></i>
                                    </div>
                                    Nouveau paiement
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        @if(count($historique_paiements) > 0)
                            <div class="space-y-3">
                                @foreach($historique_paiements as $payment)
                                    <div class="group relative p-5 bg-gradient-to-r from-slate-50 to-slate-50/80 rounded-xl border border-slate-200/50 hover:from-slate-100 hover:to-slate-100/80 hover:border-slate-300/50 hover:shadow-md transition-all duration-300">
                                        <!-- Ligne colorée selon le statut -->
                                        <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-xl {{ $payment->statut === 'valide' ? 'bg-gradient-to-b from-green-400 to-green-600' : ($payment->statut === 'en_attente' ? 'bg-gradient-to-b from-amber-400 to-orange-500' : 'bg-gradient-to-b from-red-400 to-red-600') }}"></div>

                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start space-x-4 flex-1">
                                                <!-- Icône de statut -->
                                                <div class="flex-shrink-0">
                                                    <div class="relative w-12 h-12 rounded-xl {{ $payment->statut === 'valide' ? 'bg-gradient-to-br from-green-100 to-green-200' : ($payment->statut === 'en_attente' ? 'bg-gradient-to-br from-amber-100 to-orange-200' : 'bg-gradient-to-br from-red-100 to-red-200') }} flex items-center justify-center shadow-sm">
                                                        @if($payment->statut === 'valide')
                                                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                                                        @elseif($payment->statut === 'en_attente')
                                                            <i class="fas fa-clock text-amber-600 text-xl"></i>
                                                        @else
                                                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                                                        @endif
                                                        <!-- Pulse animation pour les paiements en attente -->
                                                        @if($payment->statut === 'en_attente')
                                                            <div class="absolute inset-0 rounded-xl bg-amber-400 opacity-20 animate-pulse"></div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <!-- Montant et type -->
                                                    <div class="flex items-center flex-wrap gap-2 mb-2">
                                                        <span class="text-xl font-bold text-slate-900">{{ number_format($payment->montant, 0, ',', ' ') }} FCFA</span>
                                                        <div class="flex items-center space-x-2 text-sm">
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-100 text-blue-800 font-medium">
                                                                <i class="fas fa-{{ $payment->type_paiement === 'especes' ? 'coins' : ($payment->type_paiement === 'cheque' ? 'money-check' : ($payment->type_paiement === 'virement' ? 'university' : ($payment->type_paiement === 'carte' ? 'credit-card' : 'mobile-alt'))) }} mr-1 text-xs"></i>
                                                                {{ $payment->getTypePaiementLibelle() }}
                                                            </span>
                                                            @if($payment->reference_paiement)
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-mono text-xs">
                                                                    <i class="fas fa-hashtag mr-1"></i>
                                                                    {{ $payment->reference_paiement }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Date et validateur -->
                                                    <div class="flex items-center space-x-3 text-sm text-slate-600 mb-2">
                                                        <span class="flex items-center">
                                                            <i class="far fa-calendar-alt mr-1.5 text-slate-400"></i>
                                                            {{ $payment->date_paiement->format('d/m/Y à H:i') }}
                                                        </span>
                                                        @if($payment->validateur)
                                                            <span class="flex items-center">
                                                                <i class="fas fa-user-check mr-1.5 text-green-500"></i>
                                                                Validé par {{ $payment->validateur->nom }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Commentaire -->
                                                    @if($payment->commentaire)
                                                        <div class="mt-3 p-3 bg-white/70 rounded-lg border-l-4 border-slate-300">
                                                            <div class="flex items-start space-x-2">
                                                                <i class="fas fa-quote-left text-slate-400 text-xs mt-1"></i>
                                                                <span class="text-sm text-slate-700 italic">{{ $payment->commentaire }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Statut et actions -->
                                            <div class="flex flex-col items-end space-y-3 ml-4">
                                                <!-- Badge de statut -->
                                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold shadow-sm {{ $payment->statut === 'valide' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200' : ($payment->statut === 'en_attente' ? 'bg-gradient-to-r from-amber-100 to-orange-100 text-amber-800 border border-amber-200' : 'bg-gradient-to-r from-red-100 to-rose-100 text-red-800 border border-red-200') }}">
                                                    {{ $payment->getStatutLibelle() }}
                                                </span>

                                                <!-- Boutons d'action pour les paiements en attente -->
                                                @if($payment->statut === 'en_attente')
                                                    <div class="flex space-x-2">
                                                        @can('payments.validate')
                                                            <button onclick="validerPaiement('{{ $payment->id }}')"
                                                                    class="group relative inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 hover:from-green-600 hover:to-emerald-700"
                                                                    title="Valider le paiement">
                                                                <i class="fas fa-check text-sm group-hover:scale-110 transition-transform duration-200"></i>
                                                                <!-- Effet de brillance -->
                                                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-500 rounded-xl"></div>
                                                            </button>
                                                        @endcan
                                                        @can('payments.reject')
                                                            <button onclick="rejeterPaiement('{{ $payment->id }}')"
                                                                    class="group relative inline-flex items-center justify-center w-10 h-10 bg-gradient-to-r from-red-500 to-rose-600 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 hover:from-red-600 hover:to-rose-700"
                                                                    title="Rejeter le paiement">
                                                                <i class="fas fa-times text-sm group-hover:scale-110 transition-transform duration-200"></i>
                                                                <!-- Effet de brillance -->
                                                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-500 rounded-xl"></div>
                                                            </button>
                                                        @endcan
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination ou bouton "Voir plus" si nécessaire -->
                            @if(count($historique_paiements) >= 10)
                                <div class="mt-6 text-center">
                                    <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-800 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors duration-200">
                                        <i class="fas fa-chevron-down mr-2"></i>
                                        Afficher plus de paiements
                                    </button>
                                </div>
                            @endif
                        @else
                            <!-- État vide avec design amélioré -->
                            <div class="text-center py-12">
                                <div class="relative mb-6">
                                    <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl flex items-center justify-center mx-auto shadow-inner">
                                        <i class="fas fa-credit-card text-3xl text-slate-400"></i>
                                    </div>
                                    <!-- Icônes décoratives -->
                                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-white text-xs"></i>
                                    </div>
                                </div>

                                <h3 class="text-xl font-bold text-slate-900 mb-3">Aucun paiement enregistré</h3>
                                <p class="text-slate-500 mb-6 max-w-md mx-auto">Cette souscription n'a pas encore de paiements. Ajoutez le premier paiement pour commencer le suivi.</p>

                                @if($peut_effectuer_paiement && ($montant_maximum_payable ?? 0) > 0)
                                    <button onclick="ouvrirModalPaiement()"
                                            class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 via-green-600 to-emerald-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                                        <!-- Effet de brillance -->
                                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                        <div class="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-plus text-sm"></i>
                                        </div>
                                        Ajouter le premier paiement
                                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-200"></i>
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Statut et dates -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-cyan-600 mr-2"></i>
                            Statut et dates
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <div class="text-sm font-medium text-slate-700">Statut de la souscription</div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1 {{ ($subscription['statut'] ?? '') === 'completement_payee' ? 'bg-green-100 text-green-800' : (($subscription['statut'] ?? '') === 'partiellement_payee' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                @if(($subscription['statut'] ?? '') === 'completement_payee')
                                    <i class="fas fa-check-circle mr-2"></i> Complètement payée
                                @elseif(($subscription['statut'] ?? '') === 'partiellement_payee')
                                    <i class="fas fa-hourglass-half mr-2"></i> Partiellement payée
                                @else
                                    <i class="fas fa-pause-circle mr-2"></i> Inactive
                                @endif
                            </span>
                        </div>

                        <div>
                            <div class="text-sm font-medium text-slate-700">Statut global</div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1 {{ ($subscription['statut_global'] ?? '') === 'objectif_atteint' ? 'bg-green-100 text-green-800' : (($subscription['statut_global'] ?? '') === 'presque_atteint' ? 'bg-blue-100 text-blue-800' : (($subscription['statut_global'] ?? '') === 'en_cours' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ $subscription['statut_global_libelle'] ?? 'N/A' }}
                            </span>
                        </div>

                        <hr class="border-slate-200">

                        <div>
                            <div class="text-sm font-medium text-slate-700">Date de souscription</div>
                            <div class="text-slate-900 mt-1">
                                <i class="fas fa-calendar-plus text-green-600 mr-2"></i>
                                {{ \Carbon\Carbon::parse($subscription['date_souscription'])->format('d/m/Y') }}
                            </div>
                        </div>

                        @if($subscription['date_echeance'] ?? false)
                            <div>
                                <div class="text-sm font-medium text-slate-700">Date d'échéance</div>
                                <div class="text-slate-900 mt-1 {{ $subscription['en_retard'] ?? false ? 'text-red-600' : '' }}">
                                    <i class="fas fa-calendar-times {{ $subscription['en_retard'] ?? false ? 'text-red-600' : 'text-orange-600' }} mr-2"></i>
                                    {{ \Carbon\Carbon::parse($subscription['date_echeance'])->format('d/m/Y') }}
                                </div>
                                @if($subscription['en_retard'] ?? false)
                                    <div class="text-sm text-red-600 mt-1">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        En retard de {{ $subscription['jours_retard'] ?? 0 }} jours
                                    </div>
                                @elseif(($subscription['jours_restants'] ?? 0) > 0)
                                    <div class="text-sm text-green-600 mt-1">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $subscription['jours_restants'] }} jours restants
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistiques des paiements -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <i class="fas fa-chart-bar text-indigo-600 mr-2"></i>
                            Statistiques paiements
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-lg font-bold text-blue-600">{{ $statistiques_paiements['nb_paiements_total'] ?? 0 }}</div>
                                <div class="text-xs text-slate-600">Total</div>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-lg font-bold text-green-600">{{ $statistiques_paiements['nb_paiements_valides'] ?? 0 }}</div>
                                <div class="text-xs text-slate-600">Validés</div>
                            </div>
                            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                <div class="text-lg font-bold text-yellow-600">{{ $statistiques_paiements['nb_paiements_en_attente'] ?? 0 }}</div>
                                <div class="text-xs text-slate-600">En attente</div>
                            </div>
                            <div class="text-center p-3 bg-red-50 rounded-lg">
                                <div class="text-lg font-bold text-red-600">{{ $statistiques_paiements['nb_paiements_rejetes'] ?? 0 }}</div>
                                <div class="text-xs text-slate-600">Rejetés</div>
                            </div>
                        </div>

                        @if(($statistiques_paiements['montant_moyen_paiement'] ?? 0) > 0)
                            <hr class="border-slate-200">
                            <div>
                                <div class="text-sm font-medium text-slate-700">Paiement moyen</div>
                                <div class="text-lg font-bold text-slate-900">{{ number_format($statistiques_paiements['montant_moyen_paiement'], 0, ',', ' ') }} FCFA</div>
                            </div>
                        @endif

                        @if($statistiques_paiements['premier_paiement'] ?? false)
                            <div>
                                <div class="text-sm font-medium text-slate-700">Premier paiement</div>
                                <div class="text-slate-900">{{ \Carbon\Carbon::parse($statistiques_paiements['premier_paiement'])->format('d/m/Y') }}</div>
                            </div>
                        @endif

                        @if($statistiques_paiements['dernier_paiement'] ?? false)
                            <div>
                                <div class="text-sm font-medium text-slate-700">Dernier paiement</div>
                                <div class="text-slate-900">{{ \Carbon\Carbon::parse($statistiques_paiements['dernier_paiement'])->format('d/m/Y') }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-slate-800 flex items-center">
                            <i class="fas fa-cogs text-slate-600 mr-2"></i>
                            Actions
                        </h3>
                    </div>

                    <div class="p-6 space-y-3">
                        @if($peut_effectuer_paiement && ($montant_maximum_payable ?? 0) > 0)
                            <button onclick="ouvrirModalPaiement()"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                <i class="fas fa-credit-card mr-2"></i> Enregistrer un paiement
                            </button>
                        @endif

                        @can('subscriptions.update')
                            <a href="{{ route('private.subscriptions.edit', $subscription['id']) }}"
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white font-medium rounded-xl hover:bg-yellow-700 transition-colors">
                                <i class="fas fa-edit mr-2"></i> Modifier la souscription
                            </a>
                        @endcan

                        @if(($subscription['statut'] ?? '') === 'partiellement_payee')
                            @can('subscriptions.deactivate')
                                <button onclick="desactiverSouscription()"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-orange-600 text-white font-medium rounded-xl hover:bg-orange-700 transition-colors">
                                    <i class="fas fa-pause mr-2"></i> Désactiver
                                </button>
                            @endcan
                        @elseif(($subscription['statut'] ?? '') === 'inactive')
                            @can('subscriptions.reactivate')
                                <button onclick="reactiverSouscription()"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-colors">
                                    <i class="fas fa-play mr-2"></i> Réactiver
                                </button>
                            @endcan
                        @endif

                        @can('subscriptions.report')
                            <button onclick="genererRapport()"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white font-medium rounded-xl hover:bg-purple-700 transition-colors">
                                <i class="fas fa-file-alt mr-2"></i> Générer un rapport
                            </button>
                        @endcan

                        @can('subscriptions.delete')
                            <button onclick="deleteSouscription()"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i> Supprimer
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de paiement -->
    {{-- <div id="paiementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4 transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Enregistrer un paiement</h3>
                <p class="text-slate-500 text-sm mt-1">Souscription: {{ $subscription['fimeco']['nom'] ?? 'N/A' }} - {{ $subscription['souscripteur']['nom'] ?? 'N/A' }}</p>
            </div>

            <form id="paiementForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Montant (FCFA) *</label>
                        <input type="number" id="montant" name="montant" step="0.01" min="1" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <div class="text-xs text-slate-500 mt-1">Montant maximum: {{ number_format($montant_maximum_payable ?? 0, 0, ',', ' ') }} FCFA</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type de paiement *</label>
                        <select id="typePaiement" name="type_paiement" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Sélectionnez un type</option>
                            <option value="especes">Espèces</option>
                            <option value="cheque">Chèque</option>
                            <option value="virement">Virement bancaire</option>
                            <option value="carte">Carte bancaire</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>

                    <div id="referenceField" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Référence *</label>
                        <input type="text" id="reference" name="reference_paiement" maxlength="100"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Numéro de chèque, référence virement...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date de paiement *</label>
                        <input type="datetime-local" id="datePaiement" name="date_paiement" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Commentaire</label>
                    <textarea id="commentaire" name="commentaire" rows="3" maxlength="1000"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Informations complémentaires sur le paiement..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closePaiementModal()" class="flex-1 px-4 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                        Annuler
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                        <i class="fas fa-save mr-2"></i> Enregistrer le paiement
                    </button>
                </div>
            </form>
        </div>
    </div> --}}


    <!-- Modal de paiement modifiée avec support des paiements supplémentaires -->
<div id="paiementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-3xl w-full mx-4 transform transition-all max-h-[90vh] overflow-y-auto">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-credit-card text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">Enregistrer un paiement</h3>
            <p class="text-slate-500 text-sm mt-1">Souscription: {{ $subscription['fimeco']['nom'] ?? 'N/A' }} - {{ $subscription['souscripteur']['nom'] ?? 'N/A' }}</p>

            <!-- Informations sur la souscription -->
            <div class="mt-4 p-4 bg-slate-50 rounded-xl">
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-slate-600">Souscrit:</span>
                        <div class="font-bold text-slate-900">{{ number_format($subscription['montant_souscrit'] ?? 0, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div>
                        <span class="text-slate-600">Déjà payé:</span>
                        <div class="font-bold text-green-600">{{ number_format($subscription['montant_paye'] ?? 0, 0, ',', ' ') }} FCFA</div>
                    </div>
                    <div>
                        <span class="text-slate-600">Reste à payer:</span>
                        <div class="font-bold text-orange-600">{{ number_format($subscription['reste_a_payer'] ?? 0, 0, ',', ' ') }} FCFA</div>
                    </div>
                </div>
            </div>
        </div>

        <form id="paiementForm" class="space-y-6">
            <!-- Section principale du paiement -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Montant (FCFA) *</label>
                    <input type="number" id="montant" name="montant" step="0.01" min="1" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        oninput="calculerImpactPaiement()">
                    <div id="montantInfo" class="text-xs text-slate-500 mt-1">
                        Vous pouvez payer jusqu'à {{ number_format($subscription['reste_a_payer'] ?? 0, 0, ',', ' ') }} FCFA pour compléter votre souscription
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de paiement *</label>
                    <select id="typePaiement" name="type_paiement" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Sélectionnez un type</option>
                        <option value="especes">Espèces</option>
                        <option value="cheque">Chèque</option>
                        <option value="virement">Virement bancaire</option>
                        <option value="carte">Carte bancaire</option>
                        <option value="mobile_money">Mobile Money</option>
                    </select>
                </div>

                <div id="referenceField" class="hidden">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Référence *</label>
                    <input type="text" id="reference" name="reference_paiement" maxlength="100"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Numéro de chèque, référence virement...">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date de paiement *</label>
                    <input type="datetime-local" id="datePaiement" name="date_paiement" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
            </div>

            <!-- Simulation de l'impact du paiement -->
            <div id="impactPaiement" class="hidden">
                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl">
                    <h4 class="font-semibold text-blue-900 mb-3 flex items-center">
                        <i class="fas fa-calculator text-blue-600 mr-2"></i>
                        Impact de votre paiement
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                        <div class="bg-white/70 p-3 rounded-lg">
                            <span class="text-slate-600">Pour la souscription:</span>
                            <div id="montantPourBase" class="font-bold text-blue-700">0 FCFA</div>
                        </div>
                        <div id="montantSupplementaireDiv" class="bg-white/70 p-3 rounded-lg hidden">
                            <span class="text-amber-600">Paiement supplémentaire:</span>
                            <div id="montantSupplementaire" class="font-bold text-amber-700">0 FCFA</div>
                        </div>
                        <div class="bg-white/70 p-3 rounded-lg">
                            <span class="text-slate-600">Nouvelle progression:</span>
                            <div id="nouvelleProgression" class="font-bold text-green-700">0%</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Zone d'alerte pour paiement supplémentaire -->
            <div id="alerteSupplementaire" class="hidden">
                <div class="p-4 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-xl">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-gift text-white"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-amber-900 mb-2">Paiement supplémentaire détecté</h4>
                            <p id="messageSupplementaire" class="text-amber-800 text-sm mb-3"></p>

                            <!-- Checkbox de confirmation -->
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="accepterSupplementaire" name="accepter_paiement_supplementaire"
                                    class="w-5 h-5 text-amber-600 bg-white border-amber-300 rounded focus:ring-amber-500 focus:ring-2">
                                <label for="accepterSupplementaire" class="text-sm font-medium text-amber-900 cursor-pointer">
                                    Je confirme vouloir effectuer ce paiement supplémentaire
                                </label>
                            </div>

                            <!-- Message d'encouragement -->
                            <div class="mt-3 p-3 bg-white/50 rounded-lg border border-amber-200">
                                <p class="text-xs text-amber-700 flex items-center">
                                    <i class="fas fa-heart text-amber-600 mr-2"></i>
                                    Votre générosité contribue au succès du projet au-delà de votre engagement initial. Merci !
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suggestions de montants -->
            <div id="suggestionsContainer" class="hidden">
                <div class="p-4 bg-slate-50 rounded-xl">
                    <h4 class="font-medium text-slate-800 mb-3 flex items-center">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Suggestions de montants
                    </h4>
                    <div id="suggestionsList" class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <!-- Les suggestions seront ajoutées dynamiquement -->
                    </div>
                </div>
            </div>

            <!-- Commentaire -->
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Commentaire</label>
                <textarea id="commentaire" name="commentaire" rows="3" maxlength="1000"
                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                    placeholder="Informations complémentaires sur le paiement..."></textarea>
            </div>

            <!-- Boutons d'action -->
            <div class="flex gap-3">
                <button type="button" onclick="closePaiementModal()"
                    class="flex-1 px-4 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                    Annuler
                </button>
                <button type="button" onclick="simulerPaiement()"
                    class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-calculator mr-2"></i> Simuler
                </button>
                <button type="submit" id="submitPaiement"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fas fa-save mr-2"></i> Enregistrer le paiement
                </button>
            </div>
        </form>
    </div>
</div>




    <!-- Modal de validation/rejet de paiement -->
    <div id="validationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all">
            <div class="text-center mb-6">
                <div id="validationIcon" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i id="validationIconClass" class="text-white text-2xl"></i>
                </div>
                <h3 id="validationTitle" class="text-xl font-bold text-slate-800"></h3>
                <p id="validationSubtitle" class="text-slate-500 text-sm mt-1"></p>
            </div>

            <form id="validationForm" class="space-y-4">
                <div id="commentaireValidationField">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Commentaire</label>
                    <textarea id="commentaireValidation" name="commentaire" rows="3" maxlength="1000"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Raison du rejet ou commentaire de validation..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeValidationModal()" class="flex-1 px-4 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                        Annuler
                    </button>
                    <button type="submit" id="validationSubmitButton" class="flex-1 px-4 py-3 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                        <i id="validationSubmitIcon" class="mr-2"></i> <span id="validationSubmitText"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal de notification -->
<div id="notificationModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[60]">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full mx-4 transform transition-all scale-95" id="notificationContent">
        <!-- Icône et contenu dynamique -->
        <div class="text-center">
            <div id="notificationIcon" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <i id="notificationIconClass" class="text-2xl"></i>
            </div>
            <h3 id="notificationTitle" class="text-xl font-bold text-slate-800 mb-2"></h3>
            <p id="notificationMessage" class="text-slate-600 mb-6"></p>

            <!-- Boutons d'action -->
            <div id="notificationButtons" class="flex gap-3">
                <button onclick="closeNotification()"
                        class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

    @push('scripts')
        <script>


            let currentPaymentId = '';
            let currentAction = '';

            // Variables globales pour le formulaire de paiement
            let impactSimulation = null;
            let suggestionsCache = null;

            // Système de notifications modales
            class NotificationModal {
                constructor() {
                    this.modal = document.getElementById('notificationModal');
                    this.content = document.getElementById('notificationContent');
                    this.icon = document.getElementById('notificationIcon');
                    this.iconClass = document.getElementById('notificationIconClass');
                    this.title = document.getElementById('notificationTitle');
                    this.message = document.getElementById('notificationMessage');
                    this.buttons = document.getElementById('notificationButtons');
                }

                show(type, title, message, options = {}) {
                    // Configuration selon le type
                    const configs = {
                        success: {
                            iconBg: 'bg-gradient-to-r from-green-500 to-emerald-500',
                            iconClass: 'fas fa-check text-white',
                            titleColor: 'text-green-800'
                        },
                        error: {
                            iconBg: 'bg-gradient-to-r from-red-500 to-pink-500',
                            iconClass: 'fas fa-times text-white',
                            titleColor: 'text-red-800'
                        },
                        warning: {
                            iconBg: 'bg-gradient-to-r from-amber-500 to-orange-500',
                            iconClass: 'fas fa-exclamation-triangle text-white',
                            titleColor: 'text-amber-800'
                        },
                        info: {
                            iconBg: 'bg-gradient-to-r from-blue-500 to-indigo-500',
                            iconClass: 'fas fa-info-circle text-white',
                            titleColor: 'text-blue-800'
                        },
                        confirm: {
                            iconBg: 'bg-gradient-to-r from-purple-500 to-indigo-500',
                            iconClass: 'fas fa-question text-white',
                            titleColor: 'text-purple-800'
                        }
                    };

                    const config = configs[type] || configs.info;

                    // Mise à jour de l'interface
                    this.icon.className = `w-16 h-16 ${config.iconBg} rounded-full flex items-center justify-center mx-auto mb-4`;
                    this.iconClass.className = config.iconClass;
                    this.title.className = `text-xl font-bold ${config.titleColor} mb-2`;
                    this.title.textContent = title;
                    this.message.textContent = message;

                    // Gestion des boutons
                    if (options.showConfirm) {
                        this.buttons.innerHTML = `
                            <button onclick="closeNotification()"
                                    class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                                ${options.cancelText || 'Annuler'}
                            </button>
                            <button onclick="confirmAction()"
                                    class="flex-1 px-4 py-2.5 ${config.iconBg} text-white rounded-xl hover:opacity-90 transition-all font-medium">
                                ${options.confirmText || 'Confirmer'}
                            </button>
                        `;
                    } else {
                        this.buttons.innerHTML = `
                            <button onclick="closeNotification()"
                                    class="w-full px-4 py-2.5 ${config.iconBg} text-white rounded-xl hover:opacity-90 transition-all font-medium">
                                ${options.buttonText || 'OK'}
                            </button>
                        `;
                    }

                    // Affichage avec animation
                    this.modal.classList.remove('hidden');
                    setTimeout(() => {
                        this.content.classList.remove('scale-95');
                        this.content.classList.add('scale-100');
                    }, 10);

                    // Fermeture automatique si spécifiée
                    if (options.autoClose) {
                        setTimeout(() => {
                            this.close();
                        }, options.autoClose);
                    }

                    // Stockage de la callback de confirmation
                    if (options.onConfirm) {
                        window.currentConfirmCallback = options.onConfirm;
                    }
                }

                close() {
                    this.content.classList.remove('scale-100');
                    this.content.classList.add('scale-95');
                    setTimeout(() => {
                        this.modal.classList.add('hidden');
                        window.currentConfirmCallback = null;
                    }, 200);
                }
            }

            // Instance globale
            window.notificationModal = new NotificationModal();

            // Fonctions helper
            function showNotification(type, title, message, options = {}) {
                window.notificationModal.show(type, title, message, options);
            }

            function closeNotification() {
                window.notificationModal.close();
            }

            function confirmAction() {
                if (window.currentConfirmCallback) {
                    window.currentConfirmCallback();
                }
                closeNotification();
            }


            // Remplacer les anciennes alertes
            function showSuccess(message, title = 'Succès') {
                showNotification('success', title, message, { autoClose: 3000 });
            }

            function showError(message, title = 'Erreur') {
                showNotification('error', title, message);
            }

            function showWarning(message, title = 'Attention') {
                showNotification('warning', title, message);
            }

            function showInfo(message, title = 'Information') {
                showNotification('info', title, message);
            }

            function showConfirm(message, onConfirm, title = 'Confirmation', options = {}) {
                showNotification('confirm', title, message, {
                    showConfirm: true,
                    onConfirm: onConfirm,
                    ...options
                });
            }


            // Fermeture en cliquant sur le backdrop
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('notificationModal')?.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closeNotification();
                    }
                });


                document.getElementById('accepterSupplementaire')?.addEventListener('change', validatePaiementForm);
                document.getElementById('montant')?.addEventListener('input', function() {
                    setTimeout(validatePaiementForm, 100);
                });
            });




























            // Payment modal functions
            function ouvrirModalPaiement() {
                // Set current date/time
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                document.getElementById('datePaiement').value = now.toISOString().slice(0, 16);

                document.getElementById('paiementModal').classList.remove('hidden');
            }

// Fonction pour réinitialiser le formulaire
function closePaiementModal() {
    document.getElementById('paiementModal').classList.add('hidden');
    document.getElementById('paiementForm').reset();
    document.getElementById('referenceField').classList.add('hidden');
    document.getElementById('impactPaiement').classList.add('hidden');
    document.getElementById('alerteSupplementaire').classList.add('hidden');
    document.getElementById('suggestionsContainer').classList.add('hidden');
    impactSimulation = null;
    suggestionsCache = null;

    // Réinitialiser les infos
    document.getElementById('montantInfo').innerHTML =
        'Vous pouvez payer jusqu\'à {{ number_format($subscription["reste_a_payer"] ?? 0, 0, ",", " ") }} FCFA pour compléter votre souscription';
}

            // Handle type paiement change to show/hide reference field
            document.getElementById('typePaiement').addEventListener('change', function() {
                const referenceField = document.getElementById('referenceField');
                const typesWithReference = ['cheque', 'virement', 'carte'];

                if (typesWithReference.includes(this.value)) {
                    referenceField.classList.remove('hidden');
                    document.getElementById('reference').required = true;
                } else {
                    referenceField.classList.add('hidden');
                    document.getElementById('reference').required = false;
                }
            });





// Modification de la soumission du formulaire
document.getElementById('paiementForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    // Validation finale
    if (impactSimulation && impactSimulation.montant_supplementaire > 0) {
        if (!document.getElementById('accepterSupplementaire').checked) {
            showWarning('Veuillez confirmer le paiement supplémentaire en cochant la case', 'Confirmation requise');
            return;
        }
    }

    // Envoi des données
    fetch("{{ route('private.subscriptions.effectuer-paiement', $subscription['id']) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const message = impactSimulation && impactSimulation.montant_supplementaire > 0 ?
                'Paiement supplémentaire enregistré avec succès ! Merci pour votre générosité.' :
                'Paiement enregistré avec succès';
            showSuccess(message, 'Paiement créé');
            setTimeout(() => location.reload(), 2000);
        } else if (data.data && data.data.type === 'confirmation_required') {
            // Gestion spéciale si le serveur demande une confirmation
            showConfirm(
                data.data.message_confirmation,
                function() {
                    // Renvoyer avec confirmation
                    data.accepter_paiement_supplementaire = true;
                    fetch("{{ route('private.subscriptions.effectuer-paiement', $subscription['id']) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            showSuccess('Paiement supplémentaire confirmé et enregistré !');
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            showError(result.message || 'Erreur lors de l\'enregistrement');
                        }
                    });
                },
                'Confirmer le paiement supplémentaire'
            );
        } else {
            showError(data.message || 'Erreur lors de l\'enregistrement du paiement');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Une erreur technique est survenue. Veuillez réessayer.');
    });
});




            // Validation/rejection functions
            function validerPaiement(paymentId) {
                currentPaymentId = paymentId;
                currentAction = 'validate';

                document.getElementById('validationIcon').className = 'w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4';
                document.getElementById('validationIconClass').className = 'fas fa-check text-white text-2xl';
                document.getElementById('validationTitle').textContent = 'Valider le paiement';
                document.getElementById('validationSubtitle').textContent = 'Confirmez la validation de ce paiement';
                document.getElementById('commentaireValidation').placeholder = 'Commentaire de validation (optionnel)...';
                document.getElementById('commentaireValidation').required = false;
                document.getElementById('validationSubmitButton').className = 'flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium';
                document.getElementById('validationSubmitIcon').className = 'fas fa-check mr-2';
                document.getElementById('validationSubmitText').textContent = 'Valider';

                document.getElementById('validationModal').classList.remove('hidden');
            }

            function rejeterPaiement(paymentId) {
                currentPaymentId = paymentId;
                currentAction = 'reject';

                document.getElementById('validationIcon').className = 'w-16 h-16 bg-gradient-to-r from-red-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4';
                document.getElementById('validationIconClass').className = 'fas fa-times text-white text-2xl';
                document.getElementById('validationTitle').textContent = 'Rejeter le paiement';
                document.getElementById('validationSubtitle').textContent = 'Indiquez la raison du rejet';
                document.getElementById('commentaireValidation').placeholder = 'Raison du rejet (obligatoire)...';
                document.getElementById('commentaireValidation').required = true;
                document.getElementById('validationSubmitButton').className = 'flex-1 px-4 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-xl hover:from-red-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium';
                document.getElementById('validationSubmitIcon').className = 'fas fa-times mr-2';
                document.getElementById('validationSubmitText').textContent = 'Rejeter';

                document.getElementById('validationModal').classList.remove('hidden');
            }

            function closeValidationModal() {
                document.getElementById('validationModal').classList.add('hidden');
                document.getElementById('validationForm').reset();
                currentPaymentId = '';
                currentAction = '';
            }

// Handle validation form submission
document.getElementById('validationForm').addEventListener('submit', function(e) {
    e.preventDefault();

    if (!currentPaymentId || !currentAction) {
        showError('Données manquantes pour traiter la demande', 'Erreur technique');
        return;
    }

    const commentaire = document.getElementById('commentaireValidation').value;

    if (currentAction === 'reject' && !commentaire.trim()) {
        showWarning('Un commentaire est obligatoire pour rejeter un paiement', 'Commentaire requis');
        return;
    }

    const url = currentAction === 'validate' ?
        `{{ route('private.paiements.validate', ':id') }}`.replace(':id', currentPaymentId) :
        `{{ route('private.paiements.reject', ':id') }}`.replace(':id', currentPaymentId);

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ commentaire: commentaire })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const actionText = currentAction === 'validate' ? 'validé' : 'rejeté';
            console.log("+++++++++++++++++++++++++++++", currentAction, data.message)
            showSuccess(data.message || `Paiement ${actionText} avec succès`, 'Action réalisée');
            setTimeout(() => location.reload(), 2000);
        } else {
            showError(data.message || 'Erreur lors de l\'action', 'Échec de l\'opération');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Une erreur technique est survenue lors de l\'action', 'Erreur réseau');
    });
});

// Action functions avec confirmations modales
function desactiverSouscription() {
    showConfirm(
        'Êtes-vous sûr de vouloir désactiver cette souscription ? Elle pourra être réactivée plus tard.',
        function() {
            fetch("{{ route('private.subscriptions.desactiver', $subscription['id']) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Souscription désactivée avec succès', 'Désactivation réussie');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showError(data.message || 'Erreur lors de la désactivation', 'Échec de la désactivation');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Une erreur technique est survenue', 'Erreur réseau');
            });
        },
        'Désactiver la souscription',
        {
            confirmText: 'Désactiver',
            cancelText: 'Annuler'
        }
    );
}


function reactiverSouscription() {
    showConfirm(
        'Êtes-vous sûr de vouloir réactiver cette souscription ?',
        function() {
            fetch("{{ route('private.subscriptions.reactiver', $subscription['id']) }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Souscription réactivée avec succès', 'Réactivation réussie');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showError(data.message || 'Erreur lors de la réactivation', 'Échec de la réactivation');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Une erreur technique est survenue', 'Erreur réseau');
            });
        },
        'Réactiver la souscription',
        {
            confirmText: 'Réactiver',
            cancelText: 'Annuler'
        }
    );
}


function deleteSouscription() {
    showConfirm(
        'Êtes-vous sûr de vouloir supprimer définitivement cette souscription ? Cette action est irréversible et supprimera tous les paiements associés.',
        function() {
            fetch("{{ route('private.subscriptions.destroy', $subscription['id']) }}", {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Souscription supprimée avec succès', 'Suppression réussie');
                    setTimeout(() => {
                        window.location.href = "{{ route('private.subscriptions.index') }}";
                    }, 2000);
                } else {
                    showError(data.message || 'Erreur lors de la suppression', 'Échec de la suppression');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Une erreur technique est survenue', 'Erreur réseau');
            });
        },
        'Supprimer la souscription',
        {
            confirmText: 'Supprimer définitivement',
            cancelText: 'Annuler'
        }
    );
}




            // function genererRapport() {
            //     const format = prompt('Format du rapport (json/pdf):', 'pdf');
            //     if (format && ['json', 'pdf'].includes(format.toLowerCase())) {
            //         window.location.href = `{{ route('private.subscriptions.rapport', $subscription['id']) }}?format=${format}`;
            //     }
            // }



            function genererRapport() {
                showConfirm(
                    'Quel format souhaitez-vous pour le rapport ?',
                    function() {
                        // Cette fonction sera appelée après sélection du format
                    },
                    'Générer un rapport',
                    {
                        showConfirm: false,
                        buttonText: 'Choisir le format'
                    }
                );

                // Modifier les boutons pour proposer les formats
                document.getElementById('notificationButtons').innerHTML = `
                    <button onclick="closeNotification()"
                            class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-colors font-medium">
                        Annuler
                    </button>
                    <button onclick="generateReport('pdf')"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-xl hover:opacity-90 transition-all font-medium">
                        <i class="fas fa-file-pdf mr-1"></i> PDF
                    </button>
                    <button onclick="generateReport('json')"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-xl hover:opacity-90 transition-all font-medium">
                        <i class="fas fa-file-code mr-1"></i> JSON
                    </button>
                `;
            }


function generateReport(format) {
    closeNotification();
    showInfo(`Génération du rapport ${format.toUpperCase()} en cours...`, 'Traitement');
    window.location.href = `{{ route('private.subscriptions.rapport', $subscription['id']) }}?format=${format}`;
}

            // Close modals on backdrop click
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('paiementModal')?.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closePaiementModal();
                    }
                });

                document.getElementById('validationModal')?.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closeValidationModal();
                    }
                });

                // Animation des cartes au chargement
                const cards = document.querySelectorAll('.bg-white\\/80');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });







            // Fonction de calcul d'impact en temps réel
function calculerImpactPaiement() {
    const montant = parseFloat(document.getElementById('montant').value) || 0;
    const resteAPayer = {{ $subscription['reste_a_payer'] ?? 0 }};
    const montantSouscrit = {{ $subscription['montant_souscrit'] ?? 0 }};
    const progressionActuelle = {{ $subscription['progression'] ?? 0 }};

    if (montant <= 0) {
        document.getElementById('impactPaiement').classList.add('hidden');
        document.getElementById('alerteSupplementaire').classList.add('hidden');
        return;
    }

    // Calculs
    const montantPourBase = Math.min(montant, resteAPayer);
    const montantSupplementaire = Math.max(0, montant - resteAPayer);
    const nouvelleProgression = montantSouscrit > 0 ?
        Math.min(100, (({{ $subscription['montant_paye'] ?? 0 }} + montantPourBase) / montantSouscrit) * 100) : 0;

    // Mise à jour de l'affichage de l'impact
    document.getElementById('impactPaiement').classList.remove('hidden');
    document.getElementById('montantPourBase').textContent =
        new Intl.NumberFormat('fr-FR').format(montantPourBase) + ' FCFA';
    document.getElementById('nouvelleProgression').textContent =
        nouvelleProgression.toFixed(1) + '%';

    // Gestion du paiement supplémentaire
    if (montantSupplementaire > 0) {
        document.getElementById('montantSupplementaireDiv').classList.remove('hidden');
        document.getElementById('montantSupplementaire').textContent =
            new Intl.NumberFormat('fr-FR').format(montantSupplementaire) + ' FCFA';

        document.getElementById('alerteSupplementaire').classList.remove('hidden');
        document.getElementById('messageSupplementaire').textContent =
            `Votre paiement de ${new Intl.NumberFormat('fr-FR').format(montant)} FCFA inclut ${new Intl.NumberFormat('fr-FR').format(montantSupplementaire)} FCFA au-delà de votre souscription initiale.`;

        // Info supplémentaire selon le contexte
        const infoSupp = document.getElementById('montantInfo');
        if (resteAPayer > 0) {
            infoSupp.innerHTML = `
                <span class="text-orange-600">
                    <i class="fas fa-info-circle mr-1"></i>
                    ${new Intl.NumberFormat('fr-FR').format(montantPourBase)} FCFA complèteront votre souscription
                </span>
                <br>
                <span class="text-amber-600">
                    <i class="fas fa-gift mr-1"></i>
                    ${new Intl.NumberFormat('fr-FR').format(montantSupplementaire)} FCFA seront un don supplémentaire
                </span>
            `;
        } else {
            infoSupp.innerHTML = `
                <span class="text-amber-600">
                    <i class="fas fa-gift mr-1"></i>
                    Paiement entièrement supplémentaire (souscription déjà complète)
                </span>
            `;
        }
    } else {
        document.getElementById('montantSupplementaireDiv').classList.add('hidden');
        document.getElementById('alerteSupplementaire').classList.add('hidden');
        document.getElementById('montantInfo').innerHTML =
            `Reste ${new Intl.NumberFormat('fr-FR').format(resteAPayer - montant)} FCFA pour compléter votre souscription`;
    }

    // Stockage de la simulation
    impactSimulation = {
        montant_total: montant,
        montant_pour_base: montantPourBase,
        montant_supplementaire: montantSupplementaire,
        nouvelle_progression: nouvelleProgression,
        sera_complete: (montantPourBase >= resteAPayer)
    };

    // Validation du formulaire
    validatePaiementForm();
}



// Simulation via API
function simulerPaiement() {
    const montant = parseFloat(document.getElementById('montant').value);

    if (!montant || montant <= 0) {
        showWarning('Veuillez saisir un montant valide pour la simulation');
        return;
    }

    fetch("{{ route('private.subscriptions.simuler-paiement', $subscription['id']) }}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}",
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ montant: montant })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher les suggestions si disponibles
            if (data.data.suggestions_prochains_paiements) {
                afficherSuggestions(data.data.suggestions_prochains_paiements);
            }

            // Afficher les avertissements
            if (data.data.warnings && data.data.warnings.length > 0) {
                let warningsText = data.data.warnings.map(w => w.message || w).join('\n');
                showInfo(warningsText, 'Informations sur votre paiement');
            }

            showSuccess('Simulation terminée. Vérifiez l\'impact ci-dessus.', 'Simulation réussie');
        } else {
            showError(data.message || 'Erreur lors de la simulation');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showError('Erreur technique lors de la simulation');
    });
}




// Affichage des suggestions
function afficherSuggestions(suggestions) {
    const container = document.getElementById('suggestionsContainer');
    const list = document.getElementById('suggestionsList');

    if (!suggestions || Object.keys(suggestions).length === 0) {
        container.classList.add('hidden');
        return;
    }

    list.innerHTML = '';

    Object.entries(suggestions).forEach(([key, suggestion]) => {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `p-3 text-sm bg-white border border-slate-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors text-left ${suggestion.type === 'supplementaire' ? 'border-amber-200 bg-amber-50' : ''}`;
        button.innerHTML = `
            <div class="font-medium ${suggestion.type === 'supplementaire' ? 'text-amber-700' : 'text-slate-800'}">
                ${new Intl.NumberFormat('fr-FR').format(suggestion.montant)} FCFA
            </div>
            <div class="text-xs text-slate-600 mt-1">${suggestion.description}</div>
        `;
        button.onclick = () => {
            document.getElementById('montant').value = suggestion.montant;
            calculerImpactPaiement();
        };
        list.appendChild(button);
    });

    container.classList.remove('hidden');
    suggestionsCache = suggestions;
}



// Validation du formulaire
function validatePaiementForm() {
    const montant = parseFloat(document.getElementById('montant').value) || 0;
    const accepterSupplementaire = document.getElementById('accepterSupplementaire');
    const submitButton = document.getElementById('submitPaiement');

    // Si paiement supplémentaire et pas d'acceptation
    if (impactSimulation && impactSimulation.montant_supplementaire > 0) {
        if (!accepterSupplementaire.checked) {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-lock mr-2"></i> Confirmez le paiement supplémentaire';
            return false;
        }
    }

    if (montant > 0) {
        submitButton.disabled = false;
        submitButton.innerHTML = '<i class="fas fa-save mr-2"></i> Enregistrer le paiement';
        return true;
    }

    submitButton.disabled = true;
    return false;
}



        </script>
    @endpush
@endsection
