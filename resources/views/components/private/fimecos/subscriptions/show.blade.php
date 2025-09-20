
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
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-history text-purple-600 mr-2"></i>
                                Historique des paiements ({{ count($historique_paiements) }})
                            </h2>
                            @if($peut_effectuer_paiement && ($montant_maximum_payable ?? 0) > 0)
                                <button onclick="ouvrirModalPaiement()"
                                        class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                    <i class="fas fa-plus mr-2"></i> Nouveau paiement
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        @if(count($historique_paiements) > 0)
                            <div class="space-y-4">
                                @foreach($historique_paiements as $payment)
                                    <div class="flex items-start justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 rounded-lg {{ $payment->statut === 'valide' ? 'bg-green-100' : ($payment->statut === 'en_attente' ? 'bg-yellow-100' : 'bg-red-100') }} flex items-center justify-center">
                                                    @if($payment->statut === 'valide')
                                                        <i class="fas fa-check text-green-600"></i>
                                                    @elseif($payment->statut === 'en_attente')
                                                        <i class="fas fa-clock text-yellow-600"></i>
                                                    @else
                                                        <i class="fas fa-times text-red-600"></i>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-medium text-slate-900">{{ number_format($payment->montant, 0, ',', ' ') }} FCFA</span>
                                                    <span class="text-sm text-slate-500">•</span>
                                                    <span class="text-sm text-slate-600">{{ $payment->getTypePaiementLibelle() }}</span>
                                                    @if($payment->reference_paiement)
                                                        <span class="text-sm text-slate-500">•</span>
                                                        <span class="text-sm text-blue-600">{{ $payment->reference_paiement }}</span>
                                                    @endif
                                                </div>

                                                <div class="text-sm text-slate-500 mt-1">
                                                    {{ $payment->date_paiement->format('d/m/Y à H:i') }}
                                                    @if($payment->validateur)
                                                        • Validé par {{ $payment->validateur->nom }}
                                                    @endif
                                                </div>

                                                @if($payment->commentaire)
                                                    <div class="text-sm text-slate-600 mt-2 italic">
                                                        "{{ $payment->commentaire }}"
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-end">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $payment->statut === 'valide' ? 'bg-green-100 text-green-800' : ($payment->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $payment->getStatutLibelle() }}
                                            </span>
                                            @if($payment->statut === 'en_attente')
                                                <div class="flex space-x-1 mt-2">
                                                    @can('payments.validate')
                                                        <button onclick="validerPaiement('{{ $payment->id }}')"
                                                                class="text-green-600 hover:text-green-800 text-xs">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endcan
                                                    @can('payments.reject')
                                                        <button onclick="rejeterPaiement('{{ $payment->id }}')"
                                                                class="text-red-600 hover:text-red-800 text-xs">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endcan
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-credit-card text-2xl text-slate-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-slate-900 mb-2">Aucun paiement</h3>
                                <p class="text-slate-500 mb-4">Cette souscription n'a pas encore de paiements enregistrés.</p>
                                @if($peut_effectuer_paiement && ($montant_maximum_payable ?? 0) > 0)
                                    <button onclick="ouvrirModalPaiement()"
                                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                                        <i class="fas fa-plus mr-2"></i> Premier paiement
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
    <div id="paiementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
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
                        <input type="number" id="montant" name="montant" step="0.01" min="1" max="{{ $montant_maximum_payable ?? 0 }}" required
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

    @push('scripts')
        <script>
            let currentPaymentId = '';
            let currentAction = '';

            // Payment modal functions
            function ouvrirModalPaiement() {
                // Set current date/time
                const now = new Date();
                now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                document.getElementById('datePaiement').value = now.toISOString().slice(0, 16);

                document.getElementById('paiementModal').classList.remove('hidden');
            }

            function closePaiementModal() {
                document.getElementById('paiementModal').classList.add('hidden');
                document.getElementById('paiementForm').reset();
                document.getElementById('referenceField').classList.add('hidden');
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

            // Handle payment form submission
            document.getElementById('paiementForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData);

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
                        alert('Paiement enregistré avec succès');
                        location.reload();
                    } else {
                        alert(data.message || 'Erreur lors de l\'enregistrement du paiement');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de l\'enregistrement du paiement');
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
                    alert('Erreur: données manquantes');
                    return;
                }

                const commentaire = document.getElementById('commentaireValidation').value;

                if (currentAction === 'reject' && !commentaire.trim()) {
                    alert('Un commentaire est obligatoire pour rejeter un paiement');
                    return;
                }

                const url = currentAction === 'validate' ?
                    `{{ route('private.payments.validate', ':id') }}`.replace(':id', currentPaymentId) :
                    `{{ route('private.payments.reject', ':id') }}`.replace(':id', currentPaymentId);

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
                        alert(data.message || 'Action effectuée avec succès');
                        location.reload();
                    } else {
                        alert(data.message || 'Erreur lors de l\'action');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de l\'action');
                });
            });

            // Action functions
            function desactiverSouscription() {
                if (confirm('Désactiver cette souscription ?')) {
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
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de la désactivation');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la désactivation');
                    });
                }
            }

            function reactiverSouscription() {
                if (confirm('Réactiver cette souscription ?')) {
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
                            location.reload();
                        } else {
                            alert(data.message || 'Erreur lors de la réactivation');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la réactivation');
                    });
                }
            }

            function deleteSouscription() {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette souscription ? Cette action est irréversible.')) {
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
                            window.location.href = "{{ route('private.subscriptions.index') }}";
                        } else {
                            alert(data.message || 'Erreur lors de la suppression');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Erreur lors de la suppression');
                    });
                }
            }

            function genererRapport() {
                const format = prompt('Format du rapport (json/pdf):', 'pdf');
                if (format && ['json', 'pdf'].includes(format.toLowerCase())) {
                    window.location.href = `{{ route('private.subscriptions.rapport', $subscription['id']) }}?format=${format}`;
                }
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
        </script>
    @endpush
@endsection
