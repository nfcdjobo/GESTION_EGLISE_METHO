@extends('layouts.private.main')
@section('title', 'Modifier une Transaction')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier la Transaction</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.fonds.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-receipt mr-2"></i>
                        Fonds
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <a href="{{ route('private.fonds.show', $formData['fonds']) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">{{ $formData['fonds']->numero_transaction }}</a>
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

    @if(!$formData['fonds']->peutEtreModifiee())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">
                        Cette transaction ne peut plus être modifiée car elle a été {{ $formData['fonds']->statut == 'validee' ? 'validée' : $formData['fonds']->statut }}.
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('private.fonds.update', $formData['fonds']) }}" method="POST" id="transactionForm" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations de base -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations de Base
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="bg-slate-50 rounded-xl p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-medium text-slate-700">N° Transaction</div>
                                    <div class="text-lg font-bold text-slate-900">{{ $formData['fonds']->numero_transaction }}</div>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($formData['fonds']->statut == 'validee') bg-green-100 text-green-800
                                        @elseif($formData['fonds']->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                        @elseif($formData['fonds']->statut == 'annulee') bg-red-100 text-red-800
                                        @elseif($formData['fonds']->statut == 'remboursee') bg-purple-100 text-purple-800
                                        @endif">
                                        {{ $formData['fonds']->statut_libelle ?? ucfirst($formData['fonds']->statut) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type_transaction" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de transaction <span class="text-red-500">*</span>
                                </label>
                                <select id="type_transaction" name="type_transaction" required {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_transaction') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                    <option value="">Sélectionner le type</option>
                                    <option value="dime" {{ old('type_transaction', $formData['fonds']->type_transaction) == 'dime' ? 'selected' : '' }}>Dîme</option>
                                    <option value="offrande_libre" {{ old('type_transaction', $formData['fonds']->type_transaction) == 'offrande_libre' ? 'selected' : '' }}>Offrande libre</option>
                                    <option value="offrande_ordinaire" {{ old('type_transaction', $formData['fonds']->type_transaction) == 'offrande_ordinaire' ? 'selected' : '' }}>Offrande ordinaire</option>
                                    <option value="offrande_speciale" {{ old('type_transaction', $formData['fonds']->type_transaction) == 'offrande_speciale' ? 'selected' : '' }}>Offrande spéciale</option>
                                    <option value="offrande_mission" {{ old('type_transaction', $formData['fonds']->type_transaction) == 'offrande_mission' ? 'selected' : '' }}>Offrande mission</option>
                                    <option value="offrande_construction" {{ old('type_transaction', $formData['fonds']->type_transaction) == 'offrande_construction' ? 'selected' : '' }}>Offrande construction</option>
                                    <option value="don_special" {{ old('type_transaction', $formData['fonds']->type_transaction) == 'don_special' ? 'selected' : '' }}>Don spécial</option>
                                    <option value="soutien_pasteur" {{ old('type_transaction', $formData['fonds']->type_transaction) == 'soutien_pasteur' ? 'selected' : '' }}>Soutien pasteur</option>
                                    <option value="frais_ceremonie" {{ old('type_transaction', $formData['fonds']->type_transaction) == 'frais_ceremonie' ? 'selected' : '' }}>Frais cérémonie</option>
                                    <option value="don_materiel" {{ old('type_transaction', $formData['fonds']->type_transaction) == 'don_materiel' ? 'selected' : '' }}>Don matériel</option>
                                    <option value="autres" {{ old('type_transaction', $formData['fonds']->type_transaction) == 'autres' ? 'selected' : '' }}>Autres</option>
                                </select>
                                @error('type_transaction')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="categorie" class="block text-sm font-medium text-slate-700 mb-2">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <select id="categorie" name="categorie" required {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('categorie') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                    <option value="reguliere" {{ old('categorie', $formData['fonds']->categorie) == 'reguliere' ? 'selected' : '' }}>Régulière</option>
                                    <option value="exceptionnelle" {{ old('categorie', $formData['fonds']->categorie) == 'exceptionnelle' ? 'selected' : '' }}>Exceptionnelle</option>
                                    <option value="urgente" {{ old('categorie', $formData['fonds']->categorie) == 'urgente' ? 'selected' : '' }}>Urgente</option>
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
                                <input type="date" id="date_transaction" name="date_transaction" value="{{ old('date_transaction', $formData['fonds']->date_transaction->format('Y-m-d')) }}" required {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_transaction') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                @error('date_transaction')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="heure_transaction" class="block text-sm font-medium text-slate-700 mb-2">Heure</label>
                                <input type="time" id="heure_transaction" name="heure_transaction" value="{{ old('heure_transaction', $formData['fonds']->heure_transaction?->format('H:i')) }}" {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_transaction') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                @error('heure_transaction')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="culte_id" class="block text-sm font-medium text-slate-700 mb-2">Culte associé</label>
                                <select id="culte_id" name="culte_id" {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('culte_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                    <option value="">Aucun culte</option>
                                    @foreach($formData['cultes'] as $culte)
                                        <option value="{{ $culte->id }}" {{ old('culte_id', $formData['fonds']->culte_id) == $culte->id ? 'selected' : '' }}>
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
                                    <input type="number" id="montant" name="montant" value="{{ old('montant', $formData['fonds']->montant) }}" required min="0.01" step="0.01" {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                        class="w-full pl-4 pr-20 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('montant') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                        <select name="devise" {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }} class="border-none bg-transparent text-sm text-slate-600 focus:outline-none {{ !$formData['fonds']->peutEtreModifiee() ? 'cursor-not-allowed' : '' }}">
                                            <option value="XOF" {{ old('devise', $formData['fonds']->devise) == 'XOF' ? 'selected' : '' }}>XOF</option>
                                            <option value="EUR" {{ old('devise', $formData['fonds']->devise) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                            <option value="USD" {{ old('devise', $formData['fonds']->devise) == 'USD' ? 'selected' : '' }}>USD</option>
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
                                <select id="mode_paiement" name="mode_paiement" required {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('mode_paiement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                    <option value="">Sélectionner le mode</option>
                                    <option value="especes" {{ old('mode_paiement', $formData['fonds']->mode_paiement) == 'especes' ? 'selected' : '' }}>Espèces</option>
                                    <option value="mobile_money" {{ old('mode_paiement', $formData['fonds']->mode_paiement) == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                    <option value="virement" {{ old('mode_paiement', $formData['fonds']->mode_paiement) == 'virement' ? 'selected' : '' }}>Virement bancaire</option>
                                    <option value="cheque" {{ old('mode_paiement', $formData['fonds']->mode_paiement) == 'cheque' ? 'selected' : '' }}>Chèque</option>
                                    <option value="nature" {{ old('mode_paiement', $formData['fonds']->mode_paiement) == 'nature' ? 'selected' : '' }}>Don en nature</option>
                                </select>
                                @error('mode_paiement')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="reference_paiement" class="block text-sm font-medium text-slate-700 mb-2">Référence de paiement</label>
                            <input type="text" id="reference_paiement" name="reference_paiement" value="{{ old('reference_paiement', $formData['fonds']->reference_paiement) }}" placeholder="Numéro de transaction, chèque, etc." {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('reference_paiement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                            @error('reference_paiement')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informations du donateur -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
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
                                    <input type="checkbox" id="est_anonyme" name="est_anonyme" value="1" {{ old('est_anonyme', $formData['fonds']->est_anonyme) ? 'checked' : '' }} {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 {{ !$formData['fonds']->peutEtreModifiee() ? 'cursor-not-allowed' : '' }}">
                                    <label for="est_anonyme" class="ml-2 text-sm font-medium text-slate-700">
                                        Don anonyme
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="est_membre" name="est_membre" value="1" {{ old('est_membre', $formData['fonds']->est_membre) ? 'checked' : '' }} {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 {{ !$formData['fonds']->peutEtreModifiee() ? 'cursor-not-allowed' : '' }}">
                                    <label for="est_membre" class="ml-2 text-sm font-medium text-slate-700">
                                        Donateur membre de l'église
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="collecteur_id" class="block text-sm font-medium text-slate-700 mb-2">Collecteur</label>
                                <select id="collecteur_id" name="collecteur_id" {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('collecteur_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                    <option value="">Sélectionner un collecteur</option>
                                    @foreach($formData['collecteurs'] as $collecteur)
                                        <option value="{{ $collecteur->id }}" {{ old('collecteur_id', $formData['fonds']->collecteur_id) == $collecteur->id ? 'selected' : '' }}>
                                            {{ $collecteur->nom }} {{ $collecteur->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('collecteur_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div id="donateur_section" class="space-y-4">
                            <div>
                                <label for="donateur_id" class="block text-sm font-medium text-slate-700 mb-2">Donateur (membre)</label>
                                <select id="donateur_id" name="donateur_id" {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('donateur_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                    <option value="">Sélectionner un donateur</option>
                                    @foreach($formData['donateurs'] as $donateur)
                                        <option value="{{ $donateur->id }}" {{ old('donateur_id', $formData['fonds']->donateur_id) == $donateur->id ? 'selected' : '' }}>
                                            {{ $donateur->nom }} {{ $donateur->prenom }} - {{ $donateur->email }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('donateur_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div id="donateur_externe_section" class="grid grid-cols-1 md:grid-cols-2 gap-4 {{ !$formData['fonds']->est_membre && !$formData['fonds']->est_anonyme ? '' : 'hidden' }}">
                                <div>
                                    <label for="nom_donateur_anonyme" class="block text-sm font-medium text-slate-700 mb-2">Nom du donateur</label>
                                    <input type="text" id="nom_donateur_anonyme" name="nom_donateur_anonyme" value="{{ old('nom_donateur_anonyme', $formData['fonds']->nom_donateur_anonyme) }}" placeholder="Nom et prénom" {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom_donateur_anonyme') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                    @error('nom_donateur_anonyme')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="contact_donateur" class="block text-sm font-medium text-slate-700 mb-2">Contact</label>
                                    <input type="text" id="contact_donateur" name="contact_donateur" value="{{ old('contact_donateur', $formData['fonds']->contact_donateur) }}" placeholder="Téléphone ou email" {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('contact_donateur') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                    @error('contact_donateur')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Don en nature -->
                @if($formData['fonds']->type_transaction == 'don_materiel' || $formData['fonds']->description_don_nature)
                    <div id="don_nature_section" class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
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
                                <div class="@error('description_don_nature') has-error @enderror">
                                    <textarea id="description_don_nature" name="description_don_nature" rows="3" placeholder="Description détaillée du don en nature" {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">{{ old('description_don_nature', $formData['fonds']->description_don_nature) }}</textarea>
                                </div>
                                @error('description_don_nature')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="valeur_estimee" class="block text-sm font-medium text-slate-700 mb-2">Valeur estimée</label>
                                <input type="number" id="valeur_estimee" name="valeur_estimee" value="{{ old('valeur_estimee', $formData['fonds']->valeur_estimee) }}" min="0" step="0.01" placeholder="Valeur en XOF" {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('valeur_estimee') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                @error('valeur_estimee')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Affectation et destination -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-target text-amber-600 mr-2"></i>
                            Affectation et Destination
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="est_flechee" name="est_flechee" value="1" {{ old('est_flechee', $formData['fonds']->est_flechee) ? 'checked' : '' }} {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 {{ !$formData['fonds']->peutEtreModifiee() ? 'cursor-not-allowed' : '' }}">
                            <label for="est_flechee" class="ml-2 text-sm font-medium text-slate-700">
                                Offrande fléchée pour un usage spécifique
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="destination" class="block text-sm font-medium text-slate-700 mb-2">Destination</label>
                                <input type="text" id="destination" name="destination" value="{{ old('destination', $formData['fonds']->destination) }}" placeholder="Projet ou usage spécifique" {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('destination') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                @error('destination')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="projet_id" class="block text-sm font-medium text-slate-700 mb-2">Projet bénéficiaire</label>
                                <select id="projet_id" name="projet_id" {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('projet_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                    <option value="">Aucun projet spécifique</option>
                                    @foreach($formData['projets'] as $projet)
                                        <option value="{{ $projet->id }}" {{ old('projet_id', $formData['fonds']->projet_id) == $projet->id ? 'selected' : '' }}>
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
                            <label for="instructions_donateur" class="block text-sm font-medium text-slate-700 mb-2">Instructions particulières du donateur</label>
                            <div class="@error('instructions_donateur') has-error @enderror">
                                <textarea id="instructions_donateur" name="instructions_donateur" rows="3" placeholder="Instructions ou souhaits particuliers du donateur" {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">{{ old('instructions_donateur', $formData['fonds']->instructions_donateur) }}</textarea>
                            </div>
                            @error('instructions_donateur')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Options avancées -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
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
                                    <input type="checkbox" id="recu_demande" name="recu_demande" value="1" {{ old('recu_demande', $formData['fonds']->recu_demande) ? 'checked' : '' }} {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 {{ !$formData['fonds']->peutEtreModifiee() ? 'cursor-not-allowed' : '' }}">
                                    <label for="recu_demande" class="ml-2 text-sm font-medium text-slate-700">
                                        Reçu fiscal demandé
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="deductible_impots" name="deductible_impots" value="1" {{ old('deductible_impots', $formData['fonds']->deductible_impots) ? 'checked' : '' }} {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 {{ !$formData['fonds']->peutEtreModifiee() ? 'cursor-not-allowed' : '' }}">
                                    <label for="deductible_impots" class="ml-2 text-sm font-medium text-slate-700">
                                        Don déductible des impôts
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="est_recurrente" name="est_recurrente" value="1" {{ old('est_recurrente', $formData['fonds']->est_recurrente) ? 'checked' : '' }} {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 {{ !$formData['fonds']->peutEtreModifiee() ? 'cursor-not-allowed' : '' }}">
                                    <label for="est_recurrente" class="ml-2 text-sm font-medium text-slate-700">
                                        Transaction récurrente
                                    </label>
                                </div>
                            </div>

                            <div id="recurrence_section" class="space-y-4 {{ $formData['fonds']->est_recurrente ? '' : 'hidden' }}">
                                <div>
                                    <label for="frequence_recurrence" class="block text-sm font-medium text-slate-700 mb-2">Fréquence</label>
                                    <select id="frequence_recurrence" name="frequence_recurrence" {{ !$formData['fonds']->peutEtreModifiee() ? 'disabled' : '' }}
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                                        <option value="">Sélectionner la fréquence</option>
                                        <option value="hebdomadaire" {{ old('frequence_recurrence', $formData['fonds']->frequence_recurrence) == 'hebdomadaire' ? 'selected' : '' }}>Hebdomadaire</option>
                                        <option value="mensuelle" {{ old('frequence_recurrence', $formData['fonds']->frequence_recurrence) == 'mensuelle' ? 'selected' : '' }}>Mensuelle</option>
                                        <option value="trimestrielle" {{ old('frequence_recurrence', $formData['fonds']->frequence_recurrence) == 'trimestrielle' ? 'selected' : '' }}>Trimestrielle</option>
                                        <option value="annuelle" {{ old('frequence_recurrence', $formData['fonds']->frequence_recurrence) == 'annuelle' ? 'selected' : '' }}>Annuelle</option>
                                    </select>
                                </div>

                                @if($formData['fonds']->prochaine_echeance)
                                    <div class="text-sm text-slate-600">
                                        <strong>Prochaine échéance:</strong> {{ $formData['fonds']->prochaine_echeance->format('d/m/Y') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="occasion_speciale" class="block text-sm font-medium text-slate-700 mb-2">Occasion spéciale</label>
                                <input type="text" id="occasion_speciale" name="occasion_speciale" value="{{ old('occasion_speciale', $formData['fonds']->occasion_speciale) }}" placeholder="Noël, Pâques, anniversaire..." {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                            </div>

                            <div>
                                <label for="lieu_collecte" class="block text-sm font-medium text-slate-700 mb-2">Lieu de collecte</label>
                                <input type="text" id="lieu_collecte" name="lieu_collecte" value="{{ old('lieu_collecte', $formData['fonds']->lieu_collecte) }}" placeholder="Lieu où la collecte a été effectuée" {{ !$formData['fonds']->peutEtreModifiee() ? 'readonly' : '' }}
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ !$formData['fonds']->peutEtreModifiee() ? 'bg-slate-100 cursor-not-allowed' : '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                @if($formData['fonds']->statut == 'validee' && ($formData['fonds']->validateur || $formData['fonds']->validee_le))
                    <!-- Informations de validation -->
                    <div class="bg-green-50 rounded-2xl shadow-lg border border-green-200">
                        <div class="p-6 border-b border-green-200">
                            <h2 class="text-xl font-bold text-green-800 flex items-center">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                Informations de Validation
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($formData['fonds']->validateur)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-green-700">Validé par:</span>
                                    <span class="text-sm text-green-900">{{ $formData['fonds']->validateur->nom }} {{ $formData['fonds']->validateur->prenom }}</span>
                                </div>
                            @endif

                            @if($formData['fonds']->validee_le)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-green-700">Date de validation:</span>
                                    <span class="text-sm text-green-900">{{ $formData['fonds']->validee_le->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif

                            @if($formData['fonds']->notes_validation)
                                <div>
                                    <span class="text-sm font-medium text-green-700 block mb-2">Notes de validation:</span>
                                    <div class="bg-green-100 rounded-lg p-3 text-sm text-green-800">
                                        {{ $formData['fonds']->notes_validation }}
                                    </div>
                                </div>
                            @endif

                            @if($formData['fonds']->recu_emis && $formData['fonds']->numero_recu)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-green-700">N° Reçu fiscal:</span>
                                    <span class="text-sm font-bold text-green-900">{{ $formData['fonds']->numero_recu }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar - Informations et historique -->
            <div class="space-y-6">
                <!-- Informations de suivi -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                            Informations de Suivi
                        </h2>
                    </div>
                    <div class="p-6 space-y-4 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-slate-700">N° Transaction:</span>
                            <span class="text-slate-900 font-mono">{{ $formData['fonds']->numero_transaction }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="font-medium text-slate-700">Créé le:</span>
                            <span class="text-slate-600">{{ $formData['fonds']->created_at->format('d/m/Y H:i') }}</span>
                        </div>

                        @if($formData['fonds']->updated_at && $formData['fonds']->updated_at != $formData['fonds']->created_at)
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-slate-700">Modifié le:</span>
                                <span class="text-slate-600">{{ $formData['fonds']->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif

                        @if($formData['fonds']->createur)
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-slate-700">Créé par:</span>
                                <span class="text-slate-600">{{ $formData['fonds']->createur->nom }} {{ $formData['fonds']->createur->prenom }}</span>
                            </div>
                        @endif

                        @if($formData['fonds']->modificateur && $formData['fonds']->modificateur->id != $formData['fonds']->createur?->id)
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-slate-700">Modifié par:</span>
                                <span class="text-slate-600">{{ $formData['fonds']->modificateur->nom }} {{ $formData['fonds']->modificateur->prenom }}</span>
                            </div>
                        @endif

                        <hr class="border-slate-200">

                        <div class="flex items-center justify-between">
                            <span class="font-medium text-slate-700">Statut:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($formData['fonds']->statut == 'validee') bg-green-100 text-green-800
                                @elseif($formData['fonds']->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                @elseif($formData['fonds']->statut == 'annulee') bg-red-100 text-red-800
                                @elseif($formData['fonds']->statut == 'remboursee') bg-purple-100 text-purple-800
                                @endif">
                                {{ $formData['fonds']->statut_libelle ?? ucfirst($formData['fonds']->statut) }}
                            </span>
                        </div>

                        @if($formData['fonds']->est_recurrente)
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-slate-700">Récurrence:</span>
                                <span class="text-slate-600">{{ ucfirst($formData['fonds']->frequence_recurrence) }}</span>
                            </div>
                        @endif

                        @if($formData['fonds']->recu_demande)
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-slate-700">Reçu fiscal:</span>
                                <span class="text-slate-600">
                                    @if($formData['fonds']->recu_emis)
                                        <span class="text-green-600"><i class="fas fa-check mr-1"></i>Émis</span>
                                    @else
                                        <span class="text-orange-600"><i class="fas fa-clock mr-1"></i>Demandé</span>
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($formData['fonds']->transactionsEnfants && $formData['fonds']->transactionsEnfants->count() > 0)
                    <!-- Transactions récurrentes -->
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-sync-alt text-blue-600 mr-2"></i>
                                Transactions Récurrentes ({{ $formData['fonds']->transactionsEnfants->count() }})
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3 max-h-60 overflow-y-auto">
                                @foreach($formData['fonds']->transactionsEnfants->take(5) as $enfant)
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                                        <div>
                                            <div class="font-medium text-slate-900">{{ $enfant->numero_transaction }}</div>
                                            <div class="text-xs text-slate-500">{{ $enfant->date_transaction->format('d/m/Y') }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-sm font-semibold text-slate-900">{{ number_format($enfant->montant, 0, ',', ' ') }} {{ $enfant->devise }}</div>
                                            <div class="text-xs px-2 py-1 rounded text-center
                                                @if($enfant->statut == 'validee') bg-green-100 text-green-800
                                                @elseif($enfant->statut == 'en_attente') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800
                                                @endif">
                                                {{ $enfant->statut_libelle ?? ucfirst($enfant->statut) }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Aide -->
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl shadow-lg border border-blue-200 p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-blue-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-blue-900">Informations importantes</h3>
                            <ul class="mt-2 text-xs text-blue-800 space-y-1">
                                @if($formData['fonds']->peutEtreModifiee())
                                    <li>• Vous pouvez modifier cette transaction</li>
                                    <li>• Les modifications seront conservées</li>
                                @else
                                    <li>• Cette transaction ne peut plus être modifiée</li>
                                    <li>• Statut: {{ $formData['fonds']->statut_libelle }}</li>
                                @endif
                                <li>• Un reçu peut être généré après validation</li>
                                @if($formData['fonds']->est_recurrente)
                                    <li>• Transaction récurrente active</li>
                                @endif
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
                    @if($formData['fonds']->peutEtreModifiee())
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Sauvegarder les modifications
                        </button>
                    @endif
                    <a href="{{ route('private.fonds.show', $formData['fonds']) }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i> Voir la transaction
                    </a>
                    <a href="{{ route('private.fonds.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-500 text-white font-medium rounded-xl hover:bg-slate-600 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Inclure les ressources CKEditor --}}
@include('partials.ckeditor-resources')

@push('scripts')
<script>
// Variables globales
const canEdit = {{ $formData['fonds']->peutEtreModifiee() ? 'true' : 'false' }};

// Gestion des sections conditionnelles
function toggleDonateurSection() {
    if (!canEdit) return;

    const estAnonyme = document.getElementById('est_anonyme').checked;
    const estMembre = document.getElementById('est_membre').checked;
    const donateurSection = document.getElementById('donateur_section');
    const externeSection = document.getElementById('donateur_externe_section');
    const donateurSelect = document.getElementById('donateur_id');

    if (estAnonyme) {
        donateurSelect.style.display = 'none';
        externeSection.classList.add('hidden');
        donateurSelect.value = '';
    } else if (estMembre) {
        donateurSelect.style.display = 'block';
        externeSection.classList.add('hidden');
    } else {
        donateurSelect.style.display = 'none';
        externeSection.classList.remove('hidden');
        donateurSelect.value = '';
    }
}

function toggleRecurrenceSection() {
    if (!canEdit) return;

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

// Événements seulement si modification autorisée
if (canEdit) {
    document.getElementById('est_anonyme')?.addEventListener('change', toggleDonateurSection);
    document.getElementById('est_membre')?.addEventListener('change', toggleDonateurSection);
    document.getElementById('est_recurrente')?.addEventListener('change', toggleRecurrenceSection);

    // Validation du formulaire
    document.getElementById('transactionForm').addEventListener('submit', function(e) {
        // Synchroniser tous les éditeurs CKEditor avant validation
        if (window.CKEditorInstances) {
            Object.values(window.CKEditorInstances).forEach(editor => {
                const element = editor.sourceElement;
                if (element) {
                    element.value = editor.getData();
                }
            });
        }

        const typeTransaction = document.getElementById('type_transaction').value;
        const montant = document.getElementById('montant').value;
        const modePaiement = document.getElementById('mode_paiement').value;

        if (!typeTransaction || !montant || !modePaiement) {
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
            document.getElementById('description_don_nature') &&
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

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    if (canEdit) {
        toggleDonateurSection();
        toggleRecurrenceSection();
    }
});
</script>
@endpush
@endsection
