@extends('layouts.private.main')
@section('title', 'Créer une Transaction')

@section('content')
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Créer une Nouvelle Transaction
            </h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.fonds.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-receipt mr-2"></i>
                            Fonds
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-medium text-slate-500">Créer</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <form action="{{ route('private.fonds.store') }}" method="POST" id="transactionForm" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Informations principales -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Informations de base -->
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div
                            class="flex flex-col p-6 border-b border-slate-200 sm:flex-row sm:items-center sm:justify-between gap-4">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Informations de Base
                            </h2>
                            <div class="flex flex-wrap gap-2">
                                @can('fonds.dashboard')
                                    <a href="{{ route('private.fonds.dashboard') }}"
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="fas fa-tachometer-alt mr-2"></i> Tableau de Bord
                                    </a>
                                @endcan
                                @can('fonds.statistics')
                                    <a href="{{ route('private.fonds.statistics') }}"
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                                    </a>
                                @endcan
                                @can('fonds.analytics')
                                    <a href="{{ route('private.fonds.analytics') }}"
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="fas fa-chart-line mr-2"></i> Analytics
                                    </a>
                                @endcan
                            </div>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="type_transaction" class="block text-sm font-medium text-slate-700 mb-2">
                                        Type de transaction <span class="text-red-500">*</span>
                                    </label>
                                    <select id="type_transaction" name="type_transaction" required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_transaction') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <option value="">Sélectionner le type</option>
                                        <option value="dime" {{ old('type_transaction') == 'dime' ? 'selected' : '' }}>Dîme
                                        </option>
                                        <option value="offrande_libre" {{ old('type_transaction') == 'offrande_libre' ? 'selected' : '' }}>Offrande libre</option>
                                        <option value="offrande_ordinaire" {{ old('type_transaction') == 'offrande_ordinaire' ? 'selected' : '' }}>Offrande ordinaire</option>
                                        <option value="offrande_speciale" {{ old('type_transaction') == 'offrande_speciale' ? 'selected' : '' }}>Offrande spéciale</option>
                                        <option value="offrande_mission" {{ old('type_transaction') == 'offrande_mission' ? 'selected' : '' }}>Offrande mission</option>
                                        <option value="offrande_construction" {{ old('type_transaction') == 'offrande_construction' ? 'selected' : '' }}>Offrande
                                            construction</option>
                                        <option value="don_special" {{ old('type_transaction') == 'don_special' ? 'selected' : '' }}>Don spécial</option>
                                        <option value="soutien_pasteur" {{ old('type_transaction') == 'soutien_pasteur' ? 'selected' : '' }}>Soutien pasteur</option>
                                        <option value="frais_ceremonie" {{ old('type_transaction') == 'frais_ceremonie' ? 'selected' : '' }}>Frais cérémonie</option>
                                        <option value="don_materiel" {{ old('type_transaction') == 'don_materiel' ? 'selected' : '' }}>Don matériel</option>
                                        <option value="autres" {{ old('type_transaction') == 'autres' ? 'selected' : '' }}>
                                            Autres</option>
                                    </select>
                                    @error('type_transaction')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="categorie" class="block text-sm font-medium text-slate-700 mb-2">
                                        Catégorie <span class="text-red-500">*</span>
                                    </label>
                                    <select id="categorie" name="categorie" required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('categorie') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <option value="reguliere" {{ old('categorie', 'reguliere') == 'reguliere' ? 'selected' : '' }}>Régulière</option>
                                        <option value="exceptionnelle" {{ old('categorie') == 'exceptionnelle' ? 'selected' : '' }}>Exceptionnelle</option>
                                        <option value="urgente" {{ old('categorie') == 'urgente' ? 'selected' : '' }}>Urgente
                                        </option>
                                    </select>
                                    @error('categorie')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="date_transaction" class="block text-sm font-medium text-slate-700 mb-2">
                                        Date de transaction <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" id="date_transaction" name="date_transaction"
                                        value="{{ old('date_transaction', now()->format('Y-m-d')) }}" required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_transaction') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('date_transaction')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="heure_transaction"
                                        class="block text-sm font-medium text-slate-700 mb-2">Heure</label>
                                    <input type="time" id="heure_transaction" name="heure_transaction"
                                        value="{{ old('heure_transaction') }}"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_transaction') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('heure_transaction')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="culte_id" class="block text-sm font-medium text-slate-700 mb-2">Culte associé</label>
                                    <select id="culte_id" name="culte_id"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('culte_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <option value="">Aucun culte</option>
                                        @foreach($formData['cultes'] as $culte)
                                            <option value="{{ $culte->id }}" {{ in_array($culte->id, [old('culte_id'), $culte?->id]) ? 'selected' : '' }}>
                                                {{ $culte->titre }} - {{ $culte->date_culte->format('d/m/Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('culte_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="montant" class="block text-sm font-medium text-slate-700 mb-2">
                                        Montant <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" id="montant" name="montant" value="{{ old('montant') }}"
                                            required min="0.01" step="0.01"
                                            class="w-full pl-4 pr-20 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('montant') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                            <select name="devise"
                                                class="border-none bg-transparent text-sm text-slate-600 focus:outline-none">
                                                <option value="XOF" {{ old('devise', 'XOF') == 'XOF' ? 'selected' : '' }}>XOF
                                                </option>
                                                <option value="EUR" {{ old('devise') == 'EUR' ? 'selected' : '' }}>EUR
                                                </option>
                                                <option value="USD" {{ old('devise') == 'USD' ? 'selected' : '' }}>USD
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    @error('montant')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="mode_paiement" class="block text-sm font-medium text-slate-700 mb-2">
                                        Mode de paiement <span class="text-red-500">*</span>
                                    </label>
                                    <select id="mode_paiement" name="mode_paiement" required
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('mode_paiement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <option value="">Sélectionner le mode</option>
                                        <option value="especes" {{ old('mode_paiement', 'especes') == 'especes' ? 'selected' : '' }}>Espèces</option>
                                        <option value="mobile_money" {{ old('mode_paiement') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                        <option value="virement" {{ old('mode_paiement') == 'virement' ? 'selected' : '' }}>
                                            Virement bancaire</option>
                                        <option value="cheque" {{ old('mode_paiement') == 'cheque' ? 'selected' : '' }}>Chèque
                                        </option>
                                        <option value="nature" {{ old('mode_paiement') == 'nature' ? 'selected' : '' }}>Don en
                                            nature</option>
                                    </select>
                                    @error('mode_paiement')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="reference_paiement"
                                    class="block text-sm font-medium text-slate-700 mb-2">Référence de paiement</label>
                                <input type="text" id="reference_paiement" name="reference_paiement"
                                    value="{{ old('reference_paiement') }}"
                                    placeholder="Numéro de transaction, chèque, etc."
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('reference_paiement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('reference_paiement')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Informations du donateur -->
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-user text-green-600 mr-2"></i>
                                Informations du Donateur
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="est_anonyme" name="est_anonyme" value="1" {{ old('est_anonyme') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="est_anonyme" class="ml-2 text-sm font-medium text-slate-700">
                                            Don anonyme
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" id="est_membre" name="est_membre" value="1" {{ old('est_membre', true) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="est_membre" class="ml-2 text-sm font-medium text-slate-700">
                                            Donateur membre de l'église
                                        </label>
                                    </div>
                                </div>

                                <!-- Collecteur avec recherche -->
                                <div>
                                    <label for="collecteur_search" class="block text-sm font-medium text-slate-700 mb-2">
                                        Collecteur <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative" data-type="collecteur">

                                        <input type="text" id="collecteur_search" name="collecteur_search"
                                            placeholder="Rechercher un collecteur..."
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('collecteur_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                            autocomplete="off" value="">

                                        <input type="hidden" id="collecteur_id" name="collecteur_id"
                                            value="{{ old('collecteur_id') }}">

                                        <div class="autocomplete-dropdown absolute top-full left-0 right-0 bg-white border border-slate-200 rounded-lg shadow-xl max-h-72 overflow-y-auto z-50"
                                            id="collecteur_dropdown">
                                            <div class="loading-item p-3 text-center text-slate-500 hidden">
                                                <i class="fas fa-spinner fa-spin mr-2"></i>Recherche en cours...
                                            </div>
                                            <div class="no-results p-3 text-center text-slate-500 hidden">
                                                Aucun collecteur trouvé
                                            </div>
                                            <div
                                                class="add-new-item p-3 border-t border-slate-200 cursor-pointer hover:bg-slate-50 transition-colors hidden">
                                                <i class="fas fa-plus text-blue-600 mr-2"></i>
                                                <span class="text-blue-600">Ajouter ce collecteur</span>
                                            </div>
                                        </div>
                                    </div>
                                    @error('collecteur_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div id="donateur_section" class="space-y-4">
                                <!-- Donateur avec recherche -->
                                <div>
                                    <label for="donateur_search"
                                        class="block text-sm font-medium text-slate-700 mb-2">Donateur (membre)</label>
                                    <div class="relative" data-type="donateur">
                                        <input type="text" id="donateur_search" name="donateur_search"
                                            placeholder="Rechercher un donateur..."
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('donateur_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                            autocomplete="off" value="{{ old('donateur_search') }}">
                                        <input type="hidden" id="donateur_id" name="donateur_id"
                                            value="{{ old('donateur_id') }}">

                                        <div class="autocomplete-dropdown absolute top-full left-0 right-0 bg-white border border-slate-200 rounded-lg shadow-xl max-h-72 overflow-y-auto z-50"
                                            id="donateur_dropdown">
                                            <div class="loading-item p-3 text-center text-slate-500 hidden">
                                                <i class="fas fa-spinner fa-spin mr-2"></i>Recherche en cours...
                                            </div>
                                            <div class="no-results p-3 text-center text-slate-500 hidden">
                                                Aucun donateur trouvé
                                            </div>
                                            <div
                                                class="add-new-item p-3 border-t border-slate-200 cursor-pointer hover:bg-slate-50 transition-colors hidden">
                                                <i class="fas fa-plus text-blue-600 mr-2"></i>
                                                <span class="text-blue-600">Ajouter ce donateur</span>
                                            </div>
                                        </div>
                                    </div>
                                    @error('donateur_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div id="donateur_externe_section" class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                                    <div>
                                        <label for="nom_donateur_anonyme"
                                            class="block text-sm font-medium text-slate-700 mb-2">Nom du donateur</label>
                                        <input type="text" id="nom_donateur_anonyme" name="nom_donateur_anonyme"
                                            value="{{ old('nom_donateur_anonyme') }}" placeholder="Nom et prénom"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom_donateur_anonyme') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('nom_donateur_anonyme')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="contact_donateur"
                                            class="block text-sm font-medium text-slate-700 mb-2">Contact</label>
                                        <input type="text" id="contact_donateur" name="contact_donateur"
                                            value="{{ old('contact_donateur') }}" placeholder="Téléphone ou email"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('contact_donateur') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('contact_donateur')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Don en nature -->
                    <div id="don_nature_section"
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 hidden">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-gift text-purple-600 mr-2"></i>
                                Détails du Don en Nature
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div>
                                <label for="description_don_nature" class="block text-sm font-medium text-slate-700 mb-2">
                                    Description du don <span class="text-red-500">*</span>
                                </label>
                                <textarea id="description_don_nature" name="description_don_nature" rows="3"
                                    placeholder="Description détaillée du don en nature"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description_don_nature') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description_don_nature') }}</textarea>
                                @error('description_don_nature')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="valeur_estimee" class="block text-sm font-medium text-slate-700 mb-2">Valeur
                                    estimée</label>
                                <input type="number" id="valeur_estimee" name="valeur_estimee"
                                    value="{{ old('valeur_estimee') }}" min="0" step="0.01" placeholder="Valeur en XOF"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('valeur_estimee') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('valeur_estimee')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Affectation et destination -->
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-bullseye text-amber-600 mr-2"></i>
                                Affectation et Destination
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex items-center">
                                <input type="checkbox" id="est_flechee" name="est_flechee" value="1" {{ old('est_flechee') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="est_flechee" class="ml-2 text-sm font-medium text-slate-700">
                                    Offrande fléchée pour un usage spécifique
                                </label>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="destination"
                                        class="block text-sm font-medium text-slate-700 mb-2">Destination</label>
                                    <input type="text" id="destination" name="destination" value="{{ old('destination') }}"
                                        placeholder="Projet ou usage spécifique"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('destination') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('destination')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="projet_id" class="block text-sm font-medium text-slate-700 mb-2">Projet
                                        bénéficiaire</label>
                                    <select id="projet_id" name="projet_id"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('projet_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <option value="">Aucun projet spécifique</option>
                                        @foreach($formData['projets'] as $projet)
                                            <option value="{{ $projet->id }}" {{ old('projet_id') == $projet->id ? 'selected' : '' }}>
                                                {{ $projet->nom_projet }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('projet_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="instructions_donateur"
                                    class="block text-sm font-medium text-slate-700 mb-2">Instructions particulières du
                                    donateur</label>
                                <textarea id="instructions_donateur" name="instructions_donateur" rows="3"
                                    placeholder="Instructions ou souhaits particuliers du donateur"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('instructions_donateur') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('instructions_donateur') }}</textarea>
                                @error('instructions_donateur')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Options avancées -->
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-cogs text-cyan-600 mr-2"></i>
                                Options Avancées
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="recu_demande" name="recu_demande" value="1" {{ old('recu_demande') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="recu_demande" class="ml-2 text-sm font-medium text-slate-700">
                                            Reçu fiscal demandé
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" id="deductible_impots" name="deductible_impots" value="1" {{ old('deductible_impots', true) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="deductible_impots" class="ml-2 text-sm font-medium text-slate-700">
                                            Don déductible des impôts
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input type="checkbox" id="est_recurrente" name="est_recurrente" value="1" {{ old('est_recurrente') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <label for="est_recurrente" class="ml-2 text-sm font-medium text-slate-700">
                                            Transaction récurrente
                                        </label>
                                    </div>
                                </div>

                                <div id="recurrence_section" class="space-y-4 hidden">
                                    <div>
                                        <label for="frequence_recurrence"
                                            class="block text-sm font-medium text-slate-700 mb-2">Fréquence</label>
                                        <select id="frequence_recurrence" name="frequence_recurrence"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            <option value="">Sélectionner la fréquence</option>
                                            <option value="hebdomadaire" {{ old('frequence_recurrence') == 'hebdomadaire' ? 'selected' : '' }}>Hebdomadaire</option>
                                            <option value="mensuelle" {{ old('frequence_recurrence') == 'mensuelle' ? 'selected' : '' }}>Mensuelle</option>
                                            <option value="trimestrielle" {{ old('frequence_recurrence') == 'trimestrielle' ? 'selected' : '' }}>Trimestrielle</option>
                                            <option value="annuelle" {{ old('frequence_recurrence') == 'annuelle' ? 'selected' : '' }}>Annuelle</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="occasion_speciale"
                                        class="block text-sm font-medium text-slate-700 mb-2">Occasion spéciale</label>
                                    <input type="text" id="occasion_speciale" name="occasion_speciale"
                                        value="{{ old('occasion_speciale') }}" placeholder="Noël, Pâques, anniversaire..."
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>

                                <div>
                                    <label for="lieu_collecte" class="block text-sm font-medium text-slate-700 mb-2">Lieu de
                                        collecte</label>
                                    <input type="text" id="lieu_collecte" name="lieu_collecte"
                                        value="{{ old('lieu_collecte', 'Église principale') }}"
                                        placeholder="Lieu où la collecte a été effectuée"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Aperçu et aide -->
                <div class="space-y-6">
                    <!-- Aperçu -->
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-eye text-purple-600 mr-2"></i>
                                Aperçu
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Type:</span>
                                <span id="preview-type" class="text-sm text-slate-900 font-semibold">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Montant:</span>
                                <span id="preview-montant" class="text-sm text-slate-600">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Date:</span>
                                <span id="preview-date" class="text-sm text-slate-600">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Mode:</span>
                                <span id="preview-mode" class="text-sm text-slate-600">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Collecteur:</span>
                                <span id="preview-collecteur" class="text-sm text-slate-600">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Donateur:</span>
                                <span id="preview-donateur" class="text-sm text-slate-600">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Statut:</span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En
                                    attente</span>
                            </div>
                        </div>
                    </div>

                    <!-- Guide des types -->
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-info text-green-600 mr-2"></i>
                                Guide des Types
                            </h2>
                        </div>
                        <div class="p-6 space-y-3 text-sm">
                            <div><strong>Dîme:</strong> 10% des revenus</div>
                            <div><strong>Offrande libre:</strong> Don volontaire</div>
                            <div><strong>Offrande spéciale:</strong> Événements particuliers</div>
                            <div><strong>Don matériel:</strong> Bien en nature</div>
                            <div><strong>Soutien pasteur:</strong> Aide au ministère</div>
                        </div>
                    </div>

                    <!-- Informations importantes -->
                    <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl shadow-lg border border-blue-200 p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold text-blue-900">Informations importantes</h3>
                                <ul class="mt-2 text-xs text-blue-800 space-y-1">
                                    <li>• Les transactions sont en attente par défaut</li>
                                    <li>• Un reçu peut être généré après validation</li>
                                    <li>• Les dons récurrents créent des échéances automatiques</li>
                                    <li>• Les dons matériels nécessitent une description</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button type="submit" id="btnCreateTransaction"
                            class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Créer la Transaction
                        </button>
                        <a href="{{ route('private.fonds.index') }}"
                            class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-times mr-2"></i> Annuler
                        </a>
                    </div>
                </div>
            </div>
        </form>

    </div>

    {{-- Inclure les ressources CKEditor --}}
    @include('partials.ckeditor-resources')

    <style>
        .autocomplete-dropdown {
            display: none;
        }

        .autocomplete-dropdown.show {
            display: block;
        }
    </style>

    @push('scripts')
        <script>
            class AutoComplete {
                constructor(containerElement, type) {
                    this.container = containerElement;
                    this.type = type;
                    this.input = containerElement.querySelector(`input[name="${type}_search"]`);
                    this.hiddenInput = containerElement.querySelector(`input[name="${type}_id"]`);
                    this.dropdown = containerElement.querySelector(`.autocomplete-dropdown`);
                    this.loadingItem = this.dropdown.querySelector('.loading-item');
                    this.noResultsItem = this.dropdown.querySelector('.no-results');
                    this.addNewItem = this.dropdown.querySelector('.add-new-item');

                    this.debounceTimer = null;
                    this.selectedIndex = -1;
                    this.currentItems = [];
                    this.currentRequest = null; // Initialiser ici
                    this.isSelecting = false; // Pour éviter la fermeture prématurée

                    this.init();
                }


                init() {
                    this.input.addEventListener('input', this.handleInput.bind(this));
                    this.input.addEventListener('focus', this.handleFocus.bind(this));
                    this.input.addEventListener('blur', this.handleBlur.bind(this));
                    this.input.addEventListener('keydown', this.handleKeydown.bind(this));

                    // Retirer le required du champ de recherche
                    this.input.removeAttribute('required');

                    if (this.addNewItem) {
                        this.addNewItem.addEventListener('click', () => {
                            this.isSelecting = true;
                            this.handleAddNew();
                        });
                    }

                    // Fermer le dropdown si on clique ailleurs
                    this.documentClickHandler = (e) => {
                        if (!this.container.contains(e.target) && !this.isSelecting) {
                            this.hideDropdown();
                        }
                        this.isSelecting = false;
                    };
                    document.addEventListener('click', this.documentClickHandler);
                }

                handleInput() {
                    const query = this.input.value.trim();

                    if (query.length === 0) {
                        this.hideDropdown();
                        this.hiddenInput.value = '';
                        this.clearStoredSelection();
                        updatePreview();
                        return;
                    }

                    // Vérifier si la sélection actuelle correspond
                    const stored = this.getStoredSelection();
                    if (stored && stored.display !== query) {
                        this.hiddenInput.value = '';
                        this.clearStoredSelection();
                        updatePreview();
                    }

                    if (query.length < 2) {
                        this.hideDropdown();
                        return;
                    }

                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => {
                        this.search(query);
                    }, 300);
                }

                handleFocus() {
                    const query = this.input.value.trim();
                    if (query.length >= 2) {
                        this.search(query);
                    }
                }


                getStoredSelection() {
                    const key = `autocomplete_${this.type}_selection`;
                    const stored = sessionStorage.getItem(key);
                    return stored ? JSON.parse(stored) : null;
                }

                handleBlur() {
                    // Augmenter le délai et vérifier si on est en train de sélectionner
                    setTimeout(() => {
                        if (!this.isSelecting) {
                            this.hideDropdown();
                        }
                    }, 300);
                }

                handleBlur() {
                    // Délai pour permettre le clic sur un élément du dropdown
                    setTimeout(() => {
                        this.hideDropdown();
                    }, 200);
                }

                handleKeydown(e) {
                    if (!this.dropdown.classList.contains('show')) return;

                    switch (e.key) {
                        case 'ArrowDown':
                            e.preventDefault();
                            this.selectedIndex = Math.min(this.selectedIndex + 1, this.currentItems.length - 1);
                            this.updateSelection();
                            break;
                        case 'ArrowUp':
                            e.preventDefault();
                            this.selectedIndex = Math.max(this.selectedIndex - 1, -1);
                            this.updateSelection();
                            break;
                        case 'Enter':
                            e.preventDefault();
                            if (this.selectedIndex >= 0 && this.currentItems[this.selectedIndex]) {
                                const user = this.currentItems[this.selectedIndex];
                                const emailMatch = user.text.match(/^(.+)\s+\([^)]+\)$/);
                                const displayName = emailMatch ? emailMatch[1].trim() : user.text;
                                this.selectItem(user, displayName);
                            } else if (this.addNewItem && !this.addNewItem.classList.contains('hidden')) {
                                this.handleAddNew();
                            }
                            break;
                        case 'Escape':
                            this.hideDropdown();
                            this.input.blur();
                            break;
                    }
                }

                async search(query) {
                    if (this.currentRequest) {
                        this.currentRequest.abort();
                    }

                    this.showLoading();

                    try {
                        this.currentRequest = new AbortController();

                        const response = await fetch(`{{ route('private.users.search') }}?q=${encodeURIComponent(query)}`, {
                            signal: this.currentRequest.signal
                        });

                        if (!response.ok) {
                            throw new Error('Erreur lors de la recherche');
                        }

                        const data = await response.json();

                        // if (!this.currentRequest.signal.aborted) {
                        const users = Array.isArray(data) ? data : (data.users || []);
                        this.displayResults(users);
                        // }
                    } catch (error) {
                        if (error.name !== 'AbortError') {
                            console.error('Erreur lors de la recherche:', error);
                            this.hideDropdown();
                        }
                    } finally {
                        this.currentRequest = null;
                    }
                }

                displayResults(users) {
                    this.hideLoading();
                    this.clearResults();
                    this.currentItems = users;
                    this.selectedIndex = -1;

                    if (users.length === 0) {
                        this.showNoResults();
                        this.showAddNew();
                    } else {
                        users.forEach((user, index) => {
                            const item = this.createUserItem(user, index);
                            this.dropdown.appendChild(item);
                        });
                        this.showAddNew();
                    }

                    this.showDropdown();
                }

                createUserItem(user, index) {
                    const div = document.createElement('div');
                    div.className = 'p-3 cursor-pointer border-b border-slate-100 hover:bg-slate-50 transition-colors';
                    div.dataset.index = index;

                    const emailMatch = user.text.match(/^(.+)\s+\([^)]+\)$/);
                    let nameOnly = emailMatch ? emailMatch[1].trim() : user.text;

                    div.innerHTML = `
                            <div class="font-medium text-slate-700">${this.escapeHtml(nameOnly)}</div>
                            <div class="text-sm text-slate-500">${this.escapeHtml(user.email)}</div>
                        `;

                    div.addEventListener('mousedown', (e) => {
                        e.preventDefault(); // Empêcher le blur
                        this.isSelecting = true;
                    });

                    div.addEventListener('click', () => {
                        this.selectItem(user, nameOnly);
                    });

                    return div;
                }

                escapeHtml(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }

                selectItem(user, displayName = null) {
                    let nameToDisplay = displayName;
                    if (!nameToDisplay) {
                        const emailMatch = user.text.match(/^(.+)\s+\([^)]+\)$/);
                        nameToDisplay = emailMatch ? emailMatch[1].trim() : user.text;
                    }

                    this.input.value = nameToDisplay;
                    this.hiddenInput.value = user.id;
                    this.hideDropdown();

                    this.storeSelection(user.id, nameToDisplay);
                    this.isSelecting = false;

                    updatePreview();
                }

                handleAddNew() {
                    const name = this.input.value.trim();
                    if (!name) return;

                    // Ouvrir un modal pour créer un nouvel utilisateur
                    this.showAddUserModal(name);
                }

                showAddUserModal(name) {
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                    modal.innerHTML = `
                            <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4 shadow-2xl">
                                <h3 class="text-lg font-semibold text-slate-800 mb-4">Ajouter un nouvel utilisateur</h3>
                                <form id="addUserForm">
                                    <div class="space-y-4">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Nom <sup class="text-red-500">*</sup> </label>
                                                <input type="text" name="nom" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-slate-700 mb-1">Prénom <sup class="text-red-500">*</sup></label>
                                                <input type="text" name="prenom" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Téléphone <sup class="text-red-500">*</sup></label>
                                            <input type="text" required name="telephone_1" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Genre <sup class="text-red-500">*</sup></label>
                                            <select name="sexe" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                                <option value="">Choisir</option>
                                                <option value="masculin">Masculin</option>
                                                <option value="feminin">Féminin</option>
                                            </select>
                                        </div>

                                         <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                                            <input type="email" name="email" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        </div>

                                    </div>
                                    <div class="flex justify-end space-x-3 mt-6">
                                        <button type="button" class="cancel-btn px-4 py-2 text-slate-600 hover:text-slate-800 transition-colors rounded-lg">Annuler</button>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md">Ajouter</button>
                                    </div>
                                </form>
                            </div>
                        `;

                    document.body.appendChild(modal);

                    // Pré-remplir le nom si possible
                    const [nom, ...prenomParts] = name.split(' ');
                    const prenom = prenomParts.join(' ');
                    modal.querySelector('input[name="nom"]').value = nom;
                    modal.querySelector('input[name="prenom"]').value = prenom;

                    // Gestionnaires d'événements
                    modal.querySelector('.cancel-btn').addEventListener('click', () => {
                        document.body.removeChild(modal);
                    });

                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            document.body.removeChild(modal);
                        }
                    });

                    modal.querySelector('#addUserForm').addEventListener('submit', async (e) => {
                        e.preventDefault();
                        await this.createUser(new FormData(e.target), modal);
                    });
                }

                async createUser(formData, modal) {
                    try {
                        const response = await fetch("{{ route('private.users.ajoutmembre') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: formData
                        });

                        const data = await response.json();

                        // Correction : vérifier response.ok (statut HTTP) ou data.success
                        if (response.ok && data.success) {
                            // Sélectionner l'utilisateur créé
                            this.selectItem(data.data);
                            document.body.removeChild(modal);

                            // Afficher un message de succès
                            this.showSuccessMessage(data.message);
                        } else {
                            throw new Error(data.message || 'Erreur lors de la création');
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        alert('Erreur lors de la création du membre: ' + error.message);
                    }
                }

                showSuccessMessage(message) {
                    const toast = document.createElement('div');
                    toast.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    toast.textContent = message;
                    document.body.appendChild(toast);

                    setTimeout(() => {

                        document.body.removeChild(toast);
                    }, 3000);
                }

                storeSelection(userId, displayName) {
                    const key = `autocomplete_${this.type}_selection`;
                    sessionStorage.setItem(key, JSON.stringify({
                        id: userId,
                        display: displayName
                    }));
                }

                clearStoredSelection() {
                    const key = `autocomplete_${this.type}_selection`;
                    sessionStorage.removeItem(key);
                }

                async restoreSelection() {
                    const stored = this.getStoredSelection();

                    if (this.hiddenInput.value) {
                        if (this.input.value && this.input.value.trim() !== '') {
                            return;
                        }

                        if (stored && stored.id === parseInt(this.hiddenInput.value)) {
                            this.input.value = stored.display;
                            return;
                        }

                        try {
                            const response = await fetch(`{{ route('private.users.search') }}?q=${this.hiddenInput.value}`);
                            const data = await response.json();
                            const users = Array.isArray(data) ? data : (data.users || []);

                            const user = users.find(u => u.id === parseInt(this.hiddenInput.value));
                            if (user && user.email) {
                                const emailMatch = user.text.match(/^(.+)\s+\([^)]+\)$/);
                                const displayName = emailMatch ? emailMatch[1].trim() : user.text;
                                this.input.value = displayName;
                                this.storeSelection(user.id, displayName);
                            }
                        } catch (error) {
                            console.error('Erreur lors de la restauration:', error);
                        }
                    }
                }


                clearResults() {
                    // Supprimer tous les éléments avec data-index (les résultats utilisateur)
                    const items = this.dropdown.querySelectorAll('[data-index]');
                    items.forEach(item => item.remove());

                    // Cacher les éléments statiques
                    this.noResultsItem.classList.add('hidden');
                    this.addNewItem.classList.add('hidden');
                }

                updateSelection() {
                    const items = this.dropdown.querySelectorAll('[data-index]');
                    items.forEach((item, index) => {
                        if (index === this.selectedIndex) {
                            item.classList.add('bg-blue-50');
                            item.classList.remove('hover:bg-slate-50');
                        } else {
                            item.classList.remove('bg-blue-50');
                            item.classList.add('hover:bg-slate-50');
                        }
                    });
                }

                showLoading() {
                    this.loadingItem.classList.remove('hidden');
                    this.showDropdown();
                }

                hideLoading() {
                    this.loadingItem.classList.add('hidden');
                }

                showNoResults() {
                    this.noResultsItem.classList.remove('hidden');
                }

                showAddNew() {
                    this.addNewItem.classList.remove('hidden');
                    this.addNewItem.querySelector('span').textContent = `Ajouter "${this.input.value}"`;
                }

                showDropdown() {
                    this.dropdown.classList.add('show');
                }

                hideDropdown() {
                    this.dropdown.classList.remove('show');
                }

                destroy() {
                    if (this.documentClickHandler) {
                        document.removeEventListener('click', this.documentClickHandler);
                    }

                    if (this.currentRequest) {
                        this.currentRequest.abort();
                    }

                    if (this.debounceTimer) {
                        clearTimeout(this.debounceTimer);
                    }
                }
            }

            // Mise à jour de l'aperçu en temps réel
            function updatePreview() {
                const typeSelect = document.getElementById('type_transaction');
                const type = typeSelect.options[typeSelect.selectedIndex]?.text || '-';
                const montant = document.getElementById('montant').value || '0';
                const devise = document.querySelector('select[name="devise"]').value || 'XOF';
                const date = document.getElementById('date_transaction').value || '-';
                const modeSelect = document.getElementById('mode_paiement');
                const mode = modeSelect.options[modeSelect.selectedIndex]?.text || '-';

                // Collecteur
                const collecteurSearch = document.getElementById('collecteur_search');
                const collecteur = collecteurSearch ? collecteurSearch.value || '-' : '-';

                // Donateur
                let donateur = '-';
                if (document.getElementById('est_anonyme').checked) {
                    donateur = 'Anonyme';
                } else {
                    const donateurSearch = document.getElementById('donateur_search');
                    const nomAnonyme = document.getElementById('nom_donateur_anonyme').value;

                    if (donateurSearch && donateurSearch.value) {
                        donateur = donateurSearch.value;
                    } else if (nomAnonyme) {
                        donateur = nomAnonyme;
                    }
                }

                document.getElementById('preview-type').textContent = type;
                document.getElementById('preview-montant').textContent = montant ? number_format(montant) + ' ' + devise : '-';
                document.getElementById('preview-date').textContent = date !== '-' ? new Date(date).toLocaleDateString('fr-FR') : '-';
                document.getElementById('preview-mode').textContent = mode;
                document.getElementById('preview-collecteur').textContent = collecteur;
                document.getElementById('preview-donateur').textContent = donateur;
            }

            document.getElementById("btnCreateTransaction").addEventListener("click", () => {
                sessionStorage.removeItem("autocomplete_collecteur_selection");
                sessionStorage.removeItem("autocomplete_donateur_selection");
            });

            // Gestion des sections conditionnelles
            function toggleDonNatureSection() {
                const typeTransaction = document.getElementById('type_transaction').value;
                const modePaiement = document.getElementById('mode_paiement').value;
                const section = document.getElementById('don_nature_section');

                if (typeTransaction === 'don_materiel' || modePaiement === 'nature') {
                    section.classList.remove('hidden');
                    document.getElementById('description_don_nature').required = true;
                } else {
                    section.classList.add('hidden');
                    document.getElementById('description_don_nature').required = false;
                }
            }

            function toggleDonateurSection() {
                const estAnonyme = document.getElementById('est_anonyme').checked;
                const estMembre = document.getElementById('est_membre').checked;
                const donateurContainer = document.querySelector('[data-type="donateur"]');
                const externeSection = document.getElementById('donateur_externe_section');
                const donateurSearch = document.getElementById('donateur_search');
                const donateurId = document.getElementById('donateur_id');

                if (estAnonyme) {
                    donateurContainer.parentElement.style.display = 'none';
                    externeSection.classList.add('hidden');
                    donateurSearch.value = '';
                    donateurId.value = '';
                } else if (estMembre) {
                    donateurContainer.parentElement.style.display = 'block';
                    externeSection.classList.add('hidden');
                } else {
                    donateurContainer.parentElement.style.display = 'none';
                    externeSection.classList.remove('hidden');
                    donateurSearch.value = '';
                    donateurId.value = '';
                }
            }

            function toggleRecurrenceSection() {
                const estRecurrente = document.getElementById('est_recurrente').checked;
                const section = document.getElementById('recurrence_section');
                const frequenceSelect = document.getElementById('frequence_recurrence');

                if (estRecurrente) {
                    section.classList.remove('hidden');
                    frequenceSelect.required = true;
                } else {
                    section.classList.add('hidden');
                    frequenceSelect.required = false;
                    frequenceSelect.value = '';
                }
            }

            // Fonction de formatage des nombres
            function number_format(number) {
                return new Intl.NumberFormat('fr-FR').format(number);
            }

            // Événements pour la mise à jour de l'aperçu
            function setupPreviewListeners() {
                ['type_transaction', 'montant', 'date_transaction', 'mode_paiement'].forEach(id => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.addEventListener('input', updatePreview);
                        element.addEventListener('change', updatePreview);
                    }
                });

                // Devise
                const deviseSelect = document.querySelector('select[name="devise"]');
                if (deviseSelect) {
                    deviseSelect.addEventListener('change', updatePreview);
                }

                // Champs de recherche
                const collecteurSearch = document.getElementById('collecteur_search');
                const donateurSearch = document.getElementById('donateur_search');
                const nomAnonyme = document.getElementById('nom_donateur_anonyme');

                if (collecteurSearch) collecteurSearch.addEventListener('input', updatePreview);
                if (donateurSearch) donateurSearch.addEventListener('input', updatePreview);
                if (nomAnonyme) nomAnonyme.addEventListener('input', updatePreview);
            }

            // Événements pour les sections conditionnelles
            function setupConditionalSections() {
                document.getElementById('type_transaction').addEventListener('change', function () {
                    toggleDonNatureSection();
                    updatePreview();
                });

                document.getElementById('mode_paiement').addEventListener('change', function () {
                    toggleDonNatureSection();
                    updatePreview();
                });

                document.getElementById('est_anonyme').addEventListener('change', function () {
                    toggleDonateurSection();
                    updatePreview();
                });

                document.getElementById('est_membre').addEventListener('change', function () {
                    toggleDonateurSection();
                    updatePreview();
                });

                document.getElementById('est_recurrente').addEventListener('change', toggleRecurrenceSection);
            }

            // Validation du formulaire
            function setupFormValidation() {
                document.getElementById('transactionForm').addEventListener('submit', function (e) {
                    const collecteurId = document.getElementById('collecteur_id').value;
                    const typeTransaction = document.getElementById('type_transaction').value;
                    const montant = document.getElementById('montant').value;
                    const modePaiement = document.getElementById('mode_paiement').value;

                    if (!collecteurId) {
                        e.preventDefault();
                        alert('Veuillez sélectionner un collecteur.');
                        document.getElementById('collecteur_search').focus();
                        return false;
                    }

                    // Synchroniser tous les éditeurs CKEditor avant validation
                    if (window.CKEditorInstances) {
                        Object.values(window.CKEditorInstances).forEach(editor => {
                            const element = editor.sourceElement;
                            if (element) {
                                element.value = editor.getData();
                            }
                        });
                    }




                    // Validation des champs obligatoires
                    if (!typeTransaction || !montant || !modePaiement || !collecteurId) {
                        e.preventDefault();
                        alert('Veuillez remplir tous les champs obligatoires.');
                        return false;
                    }

                    if (parseFloat(montant) <= 0) {
                        e.preventDefault();
                        alert('Le montant doit être positif.');
                        return false;
                    }

                    // Validation spécifique don matériel
                    if ((typeTransaction === 'don_materiel' || modePaiement === 'nature') &&
                        !document.getElementById('description_don_nature').value.trim()) {
                        e.preventDefault();
                        alert('La description est obligatoire pour un don matériel.');
                        return false;
                    }

                    // Validation récurrence
                    if (document.getElementById('est_recurrente').checked &&
                        !document.getElementById('frequence_recurrence').value) {
                        e.preventDefault();
                        alert('Veuillez sélectionner une fréquence de récurrence.');
                        return false;
                    }

                    // Validation donateur
                    const estAnonyme = document.getElementById('est_anonyme').checked;
                    const estMembre = document.getElementById('est_membre').checked;
                    const donateurId = document.getElementById('donateur_id').value;
                    const nomAnonyme = document.getElementById('nom_donateur_anonyme').value;

                    if (!estAnonyme && estMembre && !donateurId) {
                        e.preventDefault();
                        alert('Veuillez sélectionner un donateur membre.');
                        return false;
                    }

                    if (!estAnonyme && !estMembre && !nomAnonyme.trim()) {
                        e.preventDefault();
                        alert('Veuillez saisir le nom du donateur externe.');
                        return false;
                    }
                });
            }

            // Initialisation complète
            document.addEventListener('DOMContentLoaded', function () {
                // Initialiser les systèmes d'autocomplétion
                const collecteurContainer = document.querySelector('[data-type="collecteur"]');
                const donateurContainer = document.querySelector('[data-type="donateur"]');

                if (collecteurContainer) {
                    window.collecteurAutocomplete = new AutoComplete(collecteurContainer, 'collecteur');
                    // Restaurer l'affichage si une valeur est déjà sélectionnée
                    window.collecteurAutocomplete.restoreSelection();
                }

                if (donateurContainer) {
                    window.donateurAutocomplete = new AutoComplete(donateurContainer, 'donateur');
                    // Restaurer l'affichage si une valeur est déjà sélectionnée
                    window.donateurAutocomplete.restoreSelection();
                }

                // Configurer les événements
                setupPreviewListeners();
                setupConditionalSections();
                setupFormValidation();

                // Initialiser l'état des sections
                toggleDonNatureSection();
                toggleDonateurSection();
                toggleRecurrenceSection();
                updatePreview();
            });
        </script>
    @endpush
@endsection
