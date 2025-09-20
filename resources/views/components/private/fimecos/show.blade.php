@extends('layouts.private.main')
@section('title', 'Détails du FIMECO - ' . $fimeco['nom'])

@section('content')
    <div class="space-y-8">
        <!-- En-tête avec navigation -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('private.fimecos.index') }}"
                        class="inline-flex items-center justify-center w-10 h-10 bg-white/80 rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                        <i class="fas fa-arrow-left text-slate-600"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                            {{ $fimeco['nom'] }}
                        </h1>
                        <p class="text-slate-500 mt-1">
                            FIMECO créé le {{ \Carbon\Carbon::parse($fimeco['created_at'])->format('d/m/Y') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @can('fimecos.update')
                        <a href="{{ route('private.fimecos.edit', $fimeco['id']) }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    @endcan
                    @can('fimecos.rapport')
                        <a href="{{ route('private.fimecos.rapport', $fimeco['id']) }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-file-alt mr-2"></i> Rapport
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- Statistiques en temps réel -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-bullseye text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($fimeco['cible'], 0, ',', ' ') }}</p>
                        <p class="text-sm text-slate-500">Cible (FCFA)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-coins text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($fimeco['montant_solde'], 0, ',', ' ') }}</p>
                        <p class="text-sm text-slate-500">Collecté (FCFA)</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-percentage text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($fimeco['progression'], 1) }}%</p>
                        <p class="text-sm text-slate-500">Progression</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $fimeco['jours_restants'] }}</p>
                        <p class="text-sm text-slate-500">Jours restants</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progression visuelle -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Progression du FIMECO
                </h2>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Barre de progression principale -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-slate-700">Progression générale</span>
                            <span class="text-sm font-medium text-slate-700">{{ number_format($fimeco['progression'], 1) }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-4">
                            <div class="h-4 rounded-full {{ $fimeco['progression'] >= 100 ? 'bg-green-500' : ($fimeco['progression'] >= 75 ? 'bg-blue-500' : ($fimeco['progression'] >= 50 ? 'bg-yellow-500' : 'bg-red-500')) }}"
                                 style="width: {{ min($fimeco['progression'], 100) }}%"></div>
                        </div>
                    </div>

                    <!-- Informations détaillées -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200">
                            <div class="text-sm text-blue-600 font-medium">Statut global</div>
                            <div class="text-lg font-bold text-blue-800 capitalize">
                                {{ str_replace('_', ' ', $fimeco['statut_global']) }}
                            </div>
                        </div>

                        @if($fimeco['reste'] > 0)
                            <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-4 border border-orange-200">
                                <div class="text-sm text-orange-600 font-medium">Reste à collecter</div>
                                <div class="text-lg font-bold text-orange-800">
                                    {{ number_format($fimeco['reste'], 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        @endif

                        @if($fimeco['montant_supplementaire'] > 0)
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                                <div class="text-sm text-green-600 font-medium">Montant supplémentaire</div>
                                <div class="text-lg font-bold text-green-800">
                                    +{{ number_format($fimeco['montant_supplementaire'], 0, ',', ' ') }} FCFA
                                </div>
                            </div>
                        @endif

                        <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-4 border border-purple-200">
                            <div class="text-sm text-purple-600 font-medium">Statut</div>
                            <div class="text-lg font-bold text-purple-800 capitalize">
                                {{ $fimeco['statut'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations générales et Souscriptions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations générales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations générales
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($fimeco['description'])
                        <div>
                            <label class="text-sm font-medium text-slate-600">Description</label>
                            <p class="text-slate-800 mt-1">{{ $fimeco['description'] }}</p>
                        </div>
                    @endif

                    @if(isset($fimeco['responsable']))
                        {{-- <div>
                            <label class="text-sm font-medium text-slate-600">Responsable</label>
                            <div class="flex items-center mt-1">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-slate-800 font-medium">{{ $fimeco['responsable']['nom']. ' '. $fimeco['responsable']['prenom'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $fimeco['responsable']['email'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $fimeco['responsable']['telephone_1'] }}</p>
                                </div>
                            </div>
                        </div> --}}
                        <div>
                            <label class="text-sm font-medium text-slate-600">Responsable</label>
                            <div class="flex items-center mt-1">
                                @if(!empty($fimeco['responsable']['photo_profil']))
                                    <div class="w-8 h-8 rounded-full overflow-hidden mr-3">
                                        <img src="{{ Storage::url($fimeco['responsable']['photo_profil'])  }}"
                                            alt="Photo de {{ $fimeco['responsable']['nom'] }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-slate-800 font-medium">
                                        {{ $fimeco['responsable']['nom'] . ' ' . $fimeco['responsable']['prenom'] }}
                                    </p>
                                    <p class="text-xs text-slate-500">{{ $fimeco['responsable']['email'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $fimeco['responsable']['telephone_1'] }}</p>
                                </div>
                            </div>
                        </div>

                    @endif

                    <div>
                        <label class="text-sm font-medium text-slate-600">Période</label>
                        <p class="text-slate-800 mt-1">
                            Du {{ \Carbon\Carbon::parse($fimeco['debut'])->format('d/m/Y') }}
                            au {{ \Carbon\Carbon::parse($fimeco['fin'])->format('d/m/Y') }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            Durée : {{ \Carbon\Carbon::parse($fimeco['debut'])->diffInDays(\Carbon\Carbon::parse($fimeco['fin'])) }} jours
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-600">Dates importantes</label>
                        <div class="space-y-1 mt-1">
                            <p class="text-sm text-slate-700">
                                <i class="fas fa-calendar-plus text-green-600 mr-2"></i>
                                Créé le {{ \Carbon\Carbon::parse($fimeco['created_at'])->format('d/m/Y à H:i') }}
                            </p>
                            <p class="text-sm text-slate-700">
                                <i class="fas fa-calendar-edit text-blue-600 mr-2"></i>
                                Modifié le {{ \Carbon\Carbon::parse($fimeco['updated_at'])->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques des souscriptions -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-users text-purple-600 mr-2"></i>
                        Souscriptions
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $statistiques['nb_souscriptions_total'] }}
                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg border border-green-200">
                            <div class="text-2xl font-bold text-green-600">{{ $statistiques['nb_souscriptions_completes'] }}</div>
                            <div class="text-sm text-green-700">Complètes</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                            <div class="text-2xl font-bold text-blue-600">{{ $statistiques['nb_souscriptions_actives'] }}</div>
                            <div class="text-sm text-blue-700">Actives</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                            <div class="text-2xl font-bold text-yellow-600">{{ $statistiques['nb_souscriptions_partielles'] }}</div>
                            <div class="text-sm text-yellow-700">Partielles</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-r from-gray-50 to-slate-50 rounded-lg border border-gray-200">
                            <div class="text-2xl font-bold text-gray-600">{{ $statistiques['nb_souscriptions_inactives'] }}</div>
                            <div class="text-sm text-gray-700">Inactives</div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600">Montant total souscrit</span>
                            <span class="text-sm font-bold text-slate-800">
                                {{ number_format($statistiques['montant_total_souscrit'], 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600">Montant total payé</span>
                            <span class="text-sm font-bold text-slate-800">
                                {{ number_format($statistiques['montant_total_paye'], 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-600">Progression moyenne</span>
                            <span class="text-sm font-bold text-slate-800">
                                {{ number_format($statistiques['progression_moyenne_souscriptions'], 1) }}%
                            </span>
                        </div>
                        @if($statistiques['nb_souscriptions_en_retard'] > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-red-600">Souscriptions en retard</span>
                                <span class="text-sm font-bold text-red-800">
                                    {{ $statistiques['nb_souscriptions_en_retard'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Souscriptions récentes et Paiements en attente -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Souscriptions récentes -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-clock text-blue-600 mr-2"></i>
                        Souscriptions récentes
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ count($souscriptions_recentes) }}
                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    @if(count($souscriptions_recentes) > 0)
                        <div class="space-y-3">
                            @foreach($souscriptions_recentes as $souscription)
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">{{ $souscription['souscripteur']['nom'] }}</p>
                                            <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($souscription['date_souscription'])->format('d/m/Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-slate-800">{{ number_format($souscription['montant_souscrit'], 0, ',', ' ') }} FCFA</p>
                                        <p class="text-xs text-slate-500">{{ $souscription['statut'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-users text-3xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">Aucune souscription récente</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Paiements en attente -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-hourglass-half text-orange-600 mr-2"></i>
                        Paiements en attente
                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            {{ count($paiements_en_attente) }}
                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    @if(count($paiements_en_attente) > 0)
                        <div class="space-y-3">
                            @foreach($paiements_en_attente as $paiement)
                                <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-clock text-white text-xs"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-800">{{ number_format($paiement['montant'], 0, ',', ' ') }} FCFA</p>
                                            <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($paiement['date_paiement'])->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            {{ $paiement['type_paiement'] }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-3xl text-green-300 mb-3"></i>
                            <p class="text-slate-500">Aucun paiement en attente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Évolution mensuelle -->
        @if(count($evolution_mensuelle) > 0)
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-area text-green-600 mr-2"></i>
                        Évolution mensuelle des paiements
                    </h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Mois</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Nombre de paiements</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Montant total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($evolution_mensuelle as $mois)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                            {{ \Carbon\Carbon::parse($mois->mois)->format('F Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-slate-600">
                                            {{ $mois->nb_paiements }}
                                        </td>
                                        <td class="px-4 py-3 text-sm font-medium text-slate-900">
                                            {{ number_format($mois->montant_total, 0, ',', ' ') }} FCFA
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions rapides -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-lg border border-blue-200 p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                <i class="fas fa-bolt text-blue-600 mr-2"></i>
                Actions rapides
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($fimeco['statut'] === 'active')
                    @can('fimecos.cloture')
                        <button onclick="cloturerFimeco('{{ $fimeco['id'] }}')"
                            class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white font-medium rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-lock mr-2"></i>
                            Clôturer le FIMECO
                        </button>
                    @endcan
                @elseif($fimeco['statut'] === 'cloturee')
                    @can('fimecos.reouvrir')
                        <button onclick="reouvririFimeco('{{ $fimeco['id'] }}')"
                            class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-unlock mr-2"></i>
                            Réouvrir le FIMECO
                        </button>
                    @endcan
                @endif

                @can('subscriptions.create')
                    <a href="{{ route('private.subscriptions.create', $fimeco['id']) }}"
                        class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-user-plus mr-2"></i>
                        Nouvelle souscription
                    </a>
                @endcan

                @can('fimecos.rapport')
                    <a href="{{ route('private.fimecos.rapport', ['id' => $fimeco['id'], 'format' => 'pdf']) }}"
                        class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-medium rounded-xl hover:from-indigo-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Télécharger PDF
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function cloturerFimeco(fimecoId) {
                if (confirm('Êtes-vous sûr de vouloir clôturer ce FIMECO ? Cette action changera son statut.')) {
                    const url = "{{ route('private.fimecos.cloture', ':fimecoid') }}".replace(':fimecoid', fimecoId);

                    fetch(url, {
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
                                alert(data.message || 'Erreur lors de la clôture');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Erreur lors de la clôture');
                        });
                }
            }

            function reouvririFimeco(fimecoId) {
                if (confirm('Êtes-vous sûr de vouloir réouvrir ce FIMECO ?')) {
                    const url = "{{ route('private.fimecos.reouvrir', ':fimecoid') }}".replace(':fimecoid', fimecoId);

                    fetch(url, {
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
                                alert(data.message || 'Erreur lors de la réouverture');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Erreur lors de la réouverture');
                        });
                }
            }

            // Animation des cartes au chargement
            document.addEventListener('DOMContentLoaded', function() {
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
