@extends('layouts.private.main')
@section('title', 'Détails de l\'Engagement - ' . $engagementMoisson->nom_donateur)

@section('content')
    <div class="space-y-8">
        <!-- En-tête avec navigation -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-slate-600 mb-4">
                <a href="{{ route('private.moissons.index') }}" class="hover:text-blue-600 transition-colors">
                    <i class="fas fa-seedling mr-1"></i> Moissons
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('private.moissons.show', $moisson) }}" class="hover:text-blue-600 transition-colors">
                    {{ Str::limit($moisson->theme, 30) }}
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('private.moissons.engagements.index', $moisson) }}" class="hover:text-blue-600 transition-colors">
                    Engagements
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium">{{ $engagementMoisson->nom_donateur }}</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        {{ $engagementMoisson->nom_donateur }}
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Détails et suivi de l'engagement pour la moisson "{{ $moisson->theme }}"
                    </p>
                </div>

                <div class="flex gap-2">
                    <button onclick="ajouterPaiement()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Ajouter paiement
                    </button>
                    @if($engagementMoisson->reste > 0 && $engagementMoisson->date_echeance)
                        <button onclick="planifierRappel()"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                            <i class="fas fa-bell mr-2"></i> Rappel
                        </button>
                        <button onclick="prolongerEcheance()"
                            class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 transition-colors">
                            <i class="fas fa-calendar-plus mr-2"></i> Prolonger
                        </button>
                    @endif
                    <a href="{{ route('private.moissons.engagements.edit', [$moisson, $engagementMoisson]) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                    <button onclick="toggleStatus()"
                        class="inline-flex items-center px-4 py-2 {{ $engagementMoisson->status ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-sm font-medium rounded-xl transition-colors">
                        <i class="fas fa-toggle-{{ $engagementMoisson->status ? 'off' : 'on' }} mr-2"></i>
                        {{ $engagementMoisson->status ? 'Désactiver' : 'Activer' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Informations de la moisson -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-lg border border-blue-200/50 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $moisson->theme }}</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-slate-600">Date:</span>
                            <span class="font-medium text-slate-800 ml-1">{{ $moisson->date->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Culte:</span>
                            <span class="font-medium text-slate-800 ml-1">{{ $moisson->culte->titre ?? 'Non défini' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Objectif global:</span>
                            <span class="font-medium text-slate-800 ml-1">{{ number_format($moisson->cible, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Progression globale:</span>
                            <span class="font-medium text-blue-600 ml-1">{{ $moisson->pourcentage_realise }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicateurs de performance de l'engagement -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Engagement</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($engagementMoisson->cible, 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-handshake text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Versé</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($engagementMoisson->montant_solde, 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-coins text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">
                            {{ $engagementMoisson->reste > 0 ? 'Reste' : 'Supplément' }}
                        </p>
                        <p class="text-2xl font-bold {{ $engagementMoisson->reste > 0 ? 'text-red-600' : 'text-purple-600' }}">
                            {{ number_format($engagementMoisson->reste > 0 ? $engagementMoisson->reste : $engagementMoisson->montant_supplementaire, 0, ',', ' ') }}
                        </p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 {{ $engagementMoisson->reste > 0 ? 'bg-red-100' : 'bg-purple-100' }} rounded-xl flex items-center justify-center">
                        <i class="fas fa-{{ $engagementMoisson->reste > 0 ? 'exclamation-triangle' : 'trophy' }} {{ $engagementMoisson->reste > 0 ? 'text-red-600' : 'text-purple-600' }}"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Progression</p>
                        <p class="text-2xl font-bold
                            @if($engagementMoisson->pourcentage_realise >= 100) text-green-600
                            @elseif($engagementMoisson->pourcentage_realise >= 70) text-blue-600
                            @elseif($engagementMoisson->pourcentage_realise >= 50) text-yellow-600
                            @else text-red-600
                            @endif">{{ $engagementMoisson->pourcentage_realise }}%</p>
                        <p class="text-xs text-slate-500">
                            @php
                                $pourcentage = $engagementMoisson->pourcentage_realise;
                                if ($pourcentage >= 100) $statut = 'Objectif atteint';
                                elseif ($pourcentage >= 90) $statut = 'Presque atteint';
                                elseif ($pourcentage >= 70) $statut = 'Bonne progression';
                                elseif ($pourcentage >= 50) $statut = 'En cours';
                                elseif ($pourcentage >= 30) $statut = 'Début';
                                else $statut = 'Très faible';
                            @endphp
                            {{ $statut }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-slate-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barre de progression visuelle -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">
                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                Progression visuelle
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Progression de l'engagement</span>
                    <span class="text-sm font-medium text-slate-900">{{ $engagementMoisson->pourcentage_realise }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full transition-all duration-300
                        @if($engagementMoisson->pourcentage_realise >= 100) bg-green-500
                        @elseif($engagementMoisson->pourcentage_realise >= 70) bg-blue-500
                        @elseif($engagementMoisson->pourcentage_realise >= 50) bg-yellow-500
                        @else bg-red-500
                        @endif"
                        style="width: {{ min($engagementMoisson->pourcentage_realise, 100) }}%">
                    </div>
                </div>
                <div class="flex justify-between text-xs text-slate-500">
                    <span>0 FCFA</span>
                    <span>{{ number_format($engagementMoisson->cible, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>

            @if($engagementMoisson->est_en_retard)
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        <div>
                            <p class="text-sm font-medium text-red-800">Engagement en retard</p>
                            <p class="text-xs text-red-600 mt-1">
                                {{ $engagementMoisson->jours_retard }} jour(s) de retard
                                @if($engagementMoisson->niveau_urgence_libelle)
                                    - {{ $engagementMoisson->niveau_urgence_libelle }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Détails de l'engagement -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations principales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations de l'engagement
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="flex flex-wrap gap-y-6">
                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Type d'engagement</dt>
                            <dd class="mt-1 text-base text-slate-900 font-semibold">
                                {{ $engagementMoisson->categorie_libelle }}
                            </dd>
                        </div>

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Donateur</dt>
                            <dd class="mt-1 text-base text-slate-900 font-semibold">
                                {{ $engagementMoisson->nom_donateur }}
                            </dd>
                        </div>

                        @if($engagementMoisson->telephone)
                            <div class="w-full sm:w-1/2 pr-4">
                                <dt class="text-sm font-medium text-slate-500">Téléphone</dt>
                                <dd class="mt-1 text-base text-slate-900">
                                    <a href="tel:{{ $engagementMoisson->telephone }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $engagementMoisson->telephone }}
                                    </a>
                                </dd>
                            </div>
                        @endif

                        @if($engagementMoisson->email)
                            <div class="w-full sm:w-1/2 pr-4">
                                <dt class="text-sm font-medium text-slate-500">Email</dt>
                                <dd class="mt-1 text-base text-slate-900">
                                    <a href="mailto:{{ $engagementMoisson->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $engagementMoisson->email }}
                                    </a>
                                </dd>
                            </div>
                        @endif

                        @if($engagementMoisson->adresse)
                            <div class="w-full pr-4">
                                <dt class="text-sm font-medium text-slate-500">Adresse</dt>
                                <dd class="mt-1 text-base text-slate-900">
                                    {{ $engagementMoisson->adresse }}
                                </dd>
                            </div>
                        @endif

                        @if($engagementMoisson->description)
                            <div class="w-full pr-4">
                                <dt class="text-sm font-medium text-slate-500">Description</dt>
                                <dd class="mt-1 text-base text-slate-900">
                                    {{ $engagementMoisson->description }}
                                </dd>
                            </div>
                        @endif

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Date d'échéance</dt>
                            <dd class="mt-1 text-base text-slate-900">
                                {{ $engagementMoisson->date_echeance ? $engagementMoisson->date_echeance->format('d/m/Y') : 'Non définie' }}
                            </dd>
                        </div>

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Statut</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $engagementMoisson->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $engagementMoisson->status ? 'Actif' : 'Inactif' }}
                                </span>
                            </dd>
                        </div>

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Collecteur responsable</dt>
                            <dd class="mt-1 text-base text-slate-900 font-semibold">
                                {{ $engagementMoisson->collecteur?->nom_complet ?? 'Non défini' }}
                            </dd>
                        </div>

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Créé par</dt>
                            <dd class="mt-1 text-base text-slate-900 font-semibold">
                                {{ $engagementMoisson->createur?->nom_complet ?? 'Inconnu' }}
                            </dd>
                        </div>

                        <div class="w-full sm:w-1/2 pr-4">
                            <dt class="text-sm font-medium text-slate-500">Date de création</dt>
                            <dd class="mt-1 text-base text-slate-900">
                                {{ $engagementMoisson->created_at->format('d/m/Y à H:i') }}
                            </dd>
                        </div>

                        @if($engagementMoisson->updated_at != $engagementMoisson->created_at)
                            <div class="w-full sm:w-1/2 pr-4">
                                <dt class="text-sm font-medium text-slate-500">Dernière modification</dt>
                                <dd class="mt-1 text-base text-slate-900">
                                    {{ $engagementMoisson->updated_at->format('d/m/Y à H:i') }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Montants et analyses -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calculator text-green-600 mr-2"></i>
                        Analyse financière
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Engagement initial -->
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-blue-800">Engagement initial</p>
                                <p class="text-xs text-blue-600">Montant promis</p>
                            </div>
                            <p class="text-lg font-bold text-blue-900">
                                {{ number_format($engagementMoisson->cible, 0, ',', ' ') }} FCFA
                            </p>
                        </div>

                        <!-- Montant versé -->
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-green-800">Montant versé</p>
                                <p class="text-xs text-green-600">Paiements reçus</p>
                            </div>
                            <p class="text-lg font-bold text-green-900">
                                {{ number_format($engagementMoisson->montant_solde, 0, ',', ' ') }} FCFA
                            </p>
                        </div>

                        @if($engagementMoisson->reste > 0)
                            <!-- Reste à verser -->
                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-red-800">Reste à verser</p>
                                    <p class="text-xs text-red-600">Montant en attente</p>
                                </div>
                                <p class="text-lg font-bold text-red-900">
                                    {{ number_format($engagementMoisson->reste, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        @endif

                        @if($engagementMoisson->montant_supplementaire > 0)
                            <!-- Dépassement d'engagement -->
                            <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-purple-800">Dépassement d'engagement</p>
                                    <p class="text-xs text-purple-600">Versements supplémentaires</p>
                                </div>
                                <p class="text-lg font-bold text-purple-900">
                                    +{{ number_format($engagementMoisson->montant_supplementaire, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        @endif

                        @if($engagementMoisson->date_rappel && $engagementMoisson->doit_etre_rappele)
                            <!-- Rappel programmé -->
                            <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">Rappel programmé</p>
                                    <p class="text-xs text-yellow-600">Date de rappel</p>
                                </div>
                                <p class="text-lg font-bold text-yellow-900">
                                    {{ $engagementMoisson->date_rappel->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des modifications -->
        @if(count($historique) > 0)
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-purple-600 mr-2"></i>
                        Historique des paiements et modifications
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach(array_reverse($historique) as $index => $edit)
                            <div class="flex items-start gap-4 p-4 {{ $index % 2 === 0 ? 'bg-slate-50' : 'bg-white' }} rounded-lg">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-{{ $edit['action'] === 'creation' ? 'plus' : ($edit['action'] === 'modification' ? 'edit' : ($edit['action'] === 'ajout_paiement' ? 'coins' : 'bell')) }} text-blue-600 text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-slate-900">
                                            @switch($edit['action'])
                                                @case('creation')
                                                    Création de l'engagement
                                                    @break
                                                @case('modification')
                                                    Modification
                                                    @break
                                                @case('ajout_paiement')
                                                    Paiement reçu
                                                    @break
                                                @case('paiement_partiel')
                                                    Paiement partiel
                                                    @break
                                                @case('paiement_complet')
                                                    Paiement complet
                                                    @break
                                                @case('rappel_planifie')
                                                    Rappel planifié
                                                    @break
                                                @case('rappel_effectue')
                                                    Rappel effectué
                                                    @break
                                                @case('prolongation_echeance')
                                                    Échéance prolongée
                                                    @break
                                                @case('activation')
                                                    Activation
                                                    @break
                                                @case('desactivation')
                                                    Désactivation
                                                    @break
                                                @default
                                                    {{ ucfirst($edit['action']) }}
                                            @endswitch
                                        </h4>
                                        <span class="text-xs text-slate-500">
                                            {{ \Carbon\Carbon::parse($edit['date'])->format('d/m/Y H:i') }}
                                        </span>
                                    </div>

                                    @if(isset($edit['details']) || isset($edit['montant']))
                                        <div class="mt-2 text-sm text-slate-600">
                                            @if(isset($edit['ancien_montant']))
                                                <p>Ancien montant: {{ number_format($edit['ancien_montant'], 0, ',', ' ') }} FCFA</p>
                                            @endif
                                            @if(isset($edit['nouveau_montant']))
                                                <p>Nouveau montant: {{ number_format($edit['nouveau_montant'], 0, ',', ' ') }} FCFA</p>
                                            @endif
                                            @if(isset($edit['montant_ajoute']))
                                                <p>Montant ajouté: +{{ number_format($edit['montant_ajoute'], 0, ',', ' ') }} FCFA</p>
                                            @endif
                                            @if(isset($edit['montant']))
                                                <p>Montant: {{ number_format($edit['montant'], 0, ',', ' ') }} FCFA</p>
                                            @endif
                                            @if(isset($edit['motif']) && $edit['motif'])
                                                <p class="italic">Motif: {{ $edit['motif'] }}</p>
                                            @endif
                                            @if(isset($edit['notes']) && $edit['notes'])
                                                <p class="italic">{{ $edit['notes'] }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal pour ajouter un paiement -->
    <div id="modal-ajouter-paiement" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800">Ajouter un paiement</h3>
                    <p class="text-sm text-slate-600 mt-1">Enregistrer un paiement pour cet engagement</p>
                </div>
                <form id="form-ajouter-paiement" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Montant du paiement (FCFA) *</label>
                        <input type="number" name="montant" id="montant-input" required min="0.01" step="0.01"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ex: 50000">
                        @if($engagementMoisson->reste > 0)
                            <p class="text-xs text-slate-500 mt-1">Reste à payer: {{ number_format($engagementMoisson->reste, 0, ',', ' ') }} FCFA</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Notes (optionnel)</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Notes sur ce paiement..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="fermerModal()"
                            class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-1"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour planifier un rappel -->
    <div id="modal-planifier-rappel" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800">Planifier un rappel</h3>
                    <p class="text-sm text-slate-600 mt-1">Définir une date de rappel pour cet engagement</p>
                </div>
                <form id="form-planifier-rappel" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date du rappel *</label>
                        <input type="date" name="date_rappel" id="date-rappel-input" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="fermerModalRappel()"
                            class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-bell mr-1"></i> Planifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal pour prolonger l'échéance -->
    <div id="modal-prolonger-echeance" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800">Prolonger l'échéance</h3>
                    <p class="text-sm text-slate-600 mt-1">Modifier la date d'échéance de cet engagement</p>
                </div>
                <form id="form-prolonger-echeance" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle échéance *</label>
                        <input type="date" name="nouvelle_echeance" id="nouvelle-echeance-input" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ $engagementMoisson->date_echeance?->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Motif de la prolongation</label>
                        <textarea name="motif" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Raison de la prolongation..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="fermerModalEcheance()"
                            class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                            <i class="fas fa-calendar-plus mr-1"></i> Prolonger
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Modal pour ajouter un paiement
            function ajouterPaiement() {
                document.getElementById('modal-ajouter-paiement').classList.remove('hidden');
                document.getElementById('montant-input').focus();
            }

            function fermerModal() {
                document.getElementById('modal-ajouter-paiement').classList.add('hidden');
                document.getElementById('form-ajouter-paiement').reset();
            }

            // Modal pour planifier un rappel
            function planifierRappel() {
                document.getElementById('modal-planifier-rappel').classList.remove('hidden');
                document.getElementById('date-rappel-input').focus();
            }

            function fermerModalRappel() {
                document.getElementById('modal-planifier-rappel').classList.add('hidden');
                document.getElementById('form-planifier-rappel').reset();
            }

            // Modal pour prolonger l'échéance
            function prolongerEcheance() {
                document.getElementById('modal-prolonger-echeance').classList.remove('hidden');
                document.getElementById('nouvelle-echeance-input').focus();
            }

            function fermerModalEcheance() {
                document.getElementById('modal-prolonger-echeance').classList.add('hidden');
                document.getElementById('form-prolonger-echeance').reset();
            }

            // Soumission du formulaire d'ajout de paiement
            document.getElementById('form-ajouter-paiement').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const donnees = {
                    montant: parseFloat(formData.get('montant')),
                    notes: formData.get('notes')
                };

                fetch(`{{ route('private.moissons.engagements.ajouter-montant', [$moisson, $engagementMoisson]) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(donnees)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Paiement ajouté avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de l\'ajout du paiement', 'error');
                    }
                    fermerModal();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de l\'ajout du paiement', 'error');
                    fermerModal();
                });
            });

            // Soumission du formulaire de planification de rappel
            document.getElementById('form-planifier-rappel').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const donnees = {
                    date_rappel: formData.get('date_rappel')
                };

                fetch(`{{ route('private.moissons.engagements.planifier-rappel', [$moisson, $engagementMoisson]) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(donnees)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Rappel planifié avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de la planification', 'error');
                    }
                    fermerModalRappel();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de la planification du rappel', 'error');
                    fermerModalRappel();
                });
            });

            // Soumission du formulaire de prolongation d'échéance
            document.getElementById('form-prolonger-echeance').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const donnees = {
                    nouvelle_echeance: formData.get('nouvelle_echeance'),
                    motif: formData.get('motif')
                };

                fetch(`{{ route('private.moissons.engagements.prolonger-echeance', [$moisson, $engagementMoisson]) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(donnees)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Échéance prolongée avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de la prolongation', 'error');
                    }
                    fermerModalEcheance();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de la prolongation de l\'échéance', 'error');
                    fermerModalEcheance();
                });
            });

            // Toggle status
            function toggleStatus() {
                const currentStatus = {{ $engagementMoisson->status ? 'true' : 'false' }};
                const action = currentStatus ? 'désactiver' : 'activer';

                if (!confirm(`Êtes-vous sûr de vouloir ${action} cet engagement ?`)) {
                    return;
                }

                fetch(`{{ route('private.moissons.engagements.toggle-status', [$moisson, $engagementMoisson]) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Statut mis à jour avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de la mise à jour', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de la mise à jour du statut', 'error');
                });
            }

            // Fonction utilitaire pour les notifications
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium ${
                    type === 'success' ? 'bg-green-500' :
                    type === 'error' ? 'bg-red-500' : 'bg-blue-500'
                }`;
                notification.textContent = message;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }

            // Fermer modales avec ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    fermerModal();
                    fermerModalRappel();
                    fermerModalEcheance();
                }
            });

            // Définir les dates minimales
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date();
                const tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);

                const dateRappelInput = document.getElementById('date-rappel-input');
                const nouvelleEcheanceInput = document.getElementById('nouvelle-echeance-input');

                if (dateRappelInput) {
                    dateRappelInput.min = tomorrow.toISOString().split('T')[0];
                }

                if (nouvelleEcheanceInput) {
                    nouvelleEcheanceInput.min = tomorrow.toISOString().split('T')[0];
                }

                // Animation des cartes au chargement
                const cards = document.querySelectorAll('.bg-white\\/80');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    // card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        // card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
    @endpush
@endsection
