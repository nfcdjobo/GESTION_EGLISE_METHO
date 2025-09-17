@extends('layouts.private.main')
@section('title', 'Créer un Événement')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer un Nouvel Événement</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.events.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Événements
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

    <form action="{{ route('private.events.store') }}" method="POST" id="eventForm" enctype="multipart/form-data" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations générales -->
            <div class="lg:col-span-2">
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations Générales
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="titre" class="block text-sm font-medium text-slate-700 mb-2">
                                    Titre de l'événement <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required maxlength="200" placeholder="Ex: Conférence de Pâques 2024"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('titre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('titre')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sous_titre" class="block text-sm font-medium text-slate-700 mb-2">Sous-titre</label>
                                <input type="text" id="sous_titre" name="sous_titre" value="{{ old('sous_titre') }}" maxlength="200" placeholder="Sous-titre optionnel"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('sous_titre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('sous_titre')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4" placeholder="Description détaillée de l'événement"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="resume_court" class="block text-sm font-medium text-slate-700 mb-2">Résumé court</label>
                            <textarea id="resume_court" name="resume_court" rows="2" maxlength="500" placeholder="Résumé pour l'aperçu (500 caractères max)"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('resume_court') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('resume_court') }}</textarea>
                            @error('resume_court')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type_evenement" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type d'événement <span class="text-red-500">*</span>
                                </label>
                                <select id="type_evenement" name="type_evenement" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_evenement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionnez un type</option>
                                    <option value="conference" {{ old('type_evenement') == 'conference' ? 'selected' : '' }}>Conférence</option>
                                    <option value="seminaire" {{ old('type_evenement') == 'seminaire' ? 'selected' : '' }}>Séminaire</option>
                                    <option value="atelier" {{ old('type_evenement') == 'atelier' ? 'selected' : '' }}>Atelier</option>
                                    <option value="camps" {{ old('type_evenement') == 'camps' ? 'selected' : '' }}>Camps</option>
                                    <option value="formation" {{ old('type_evenement') == 'formation' ? 'selected' : '' }}>Formation</option>
                                    <option value="celebration" {{ old('type_evenement') == 'celebration' ? 'selected' : '' }}>Célébration</option>
                                    <option value="festival" {{ old('type_evenement') == 'festival' ? 'selected' : '' }}>Festival</option>
                                    <option value="concert" {{ old('type_evenement') == 'concert' ? 'selected' : '' }}>Concert</option>
                                    <option value="retraite" {{ old('type_evenement') == 'retraite' ? 'selected' : '' }}>Retraite</option>
                                    <option value="evangelisation" {{ old('type_evenement') == 'evangelisation' ? 'selected' : '' }}>Évangélisation</option>
                                    <option value="autre" {{ old('type_evenement') == 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                @error('type_evenement')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="categorie" class="block text-sm font-medium text-slate-700 mb-2">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <select id="categorie" name="categorie" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('categorie') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionnez une catégorie</option>
                                    <option value="spirituel" {{ old('categorie') == 'spirituel' ? 'selected' : '' }}>Spirituel</option>
                                    <option value="educatif" {{ old('categorie') == 'educatif' ? 'selected' : '' }}>Éducatif</option>
                                    <option value="social" {{ old('categorie') == 'social' ? 'selected' : '' }}>Social</option>
                                    <option value="culturel" {{ old('categorie') == 'culturel' ? 'selected' : '' }}>Culturel</option>
                                    <option value="caritatif" {{ old('categorie') == 'caritatif' ? 'selected' : '' }}>Caritatif</option>
                                    <option value="formation" {{ old('categorie') == 'formation' ? 'selected' : '' }}>Formation</option>
                                    <option value="divertissement" {{ old('categorie') == 'divertissement' ? 'selected' : '' }}>Divertissement</option>
                                </select>
                                @error('categorie')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aperçu et options -->
            <div class="space-y-6">
                <!-- Aperçu -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-eye text-purple-600 mr-2"></i>
                            Aperçu
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="aspect-video bg-gradient-to-br from-blue-400 to-purple-500 rounded-xl flex items-center justify-center" id="image-preview">
                            <div class="text-center text-white">
                                <i class="fas fa-image text-3xl mb-2"></i>
                                <p class="text-sm">Image principale</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 id="preview-titre" class="font-bold text-slate-900">Titre de l'événement</h3>
                            <p id="preview-sous-titre" class="text-sm text-slate-600 hidden"></p>
                            <div class="flex justify-center items-center space-x-2 mt-2">
                                <span id="preview-type" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800"></span>
                                <span id="preview-categorie" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Options rapides -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-cog text-amber-600 mr-2"></i>
                            Options
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="ouvert_public" name="ouvert_public" value="1" {{ old('ouvert_public', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="ouvert_public" class="ml-2 text-sm font-medium text-slate-700">
                                Ouvert au public
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="inscription_requise" name="inscription_requise" value="1" {{ old('inscription_requise') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="inscription_requise" class="ml-2 text-sm font-medium text-slate-700">
                                Inscription requise
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="inscription_payante" name="inscription_payante" value="1" {{ old('inscription_payante') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="inscription_payante" class="ml-2 text-sm font-medium text-slate-700">
                                Inscription payante
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="diffusion_en_ligne" name="diffusion_en_ligne" value="1" {{ old('diffusion_en_ligne') ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="diffusion_en_ligne" class="ml-2 text-sm font-medium text-slate-700">
                                Diffusion en ligne
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date et heure -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar text-green-600 mr-2"></i>
                    Date et Heure
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-slate-700 mb-2">
                            Date de début <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date_debut" name="date_debut" value="{{ old('date_debut') }}" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_debut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('date_debut')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_debut" class="block text-sm font-medium text-slate-700 mb-2">
                            Heure de début <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="heure_debut" name="heure_debut" value="{{ old('heure_debut') }}" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_debut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('heure_debut')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-slate-700 mb-2">Date de fin</label>
                        <input type="date" id="date_fin" name="date_fin" value="{{ old('date_fin') }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_fin') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('date_fin')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_fin" class="block text-sm font-medium text-slate-700 mb-2">Heure de fin</label>
                        <input type="time" id="heure_fin" name="heure_fin" value="{{ old('heure_fin') }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_fin') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('heure_fin')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="evenement_multi_jours" name="evenement_multi_jours" value="1" {{ old('evenement_multi_jours') ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="evenement_multi_jours" class="ml-2 text-sm font-medium text-slate-700">
                        Événement sur plusieurs jours
                    </label>
                </div>
            </div>
        </div>

        <!-- Lieu -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                    Lieu
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="lieu_nom" class="block text-sm font-medium text-slate-700 mb-2">
                            Nom du lieu <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="lieu_nom" name="lieu_nom" value="{{ old('lieu_nom') }}" required maxlength="200" placeholder="Ex: Église Centrale"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lieu_nom') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('lieu_nom')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lieu_ville" class="block text-sm font-medium text-slate-700 mb-2">Ville</label>
                        <input type="text" id="lieu_ville" name="lieu_ville" value="{{ old('lieu_ville') }}" maxlength="100" placeholder="Ex: Abidjan"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lieu_ville') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('lieu_ville')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="lieu_adresse" class="block text-sm font-medium text-slate-700 mb-2">Adresse complète</label>
                    <textarea id="lieu_adresse" name="lieu_adresse" rows="3" placeholder="Adresse complète du lieu"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('lieu_adresse') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('lieu_adresse') }}</textarea>
                    @error('lieu_adresse')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="instructions_acces" class="block text-sm font-medium text-slate-700 mb-2">Instructions d'accès</label>
                    <textarea id="instructions_acces" name="instructions_acces" rows="2" placeholder="Indications particulières pour accéder au lieu"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('instructions_acces') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('instructions_acces') }}</textarea>
                    @error('instructions_acces')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Capacité et inscriptions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300" id="inscription-section" style="display: none;">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-users text-cyan-600 mr-2"></i>
                    Capacité et Inscriptions
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="capacite_totale" class="block text-sm font-medium text-slate-700 mb-2">Capacité totale</label>
                        <input type="number" id="capacite_totale" name="capacite_totale" value="{{ old('capacite_totale') }}" min="1" placeholder="100"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('capacite_totale') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('capacite_totale')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="prix-section" style="display: none;">
                        <label for="prix_inscription" class="block text-sm font-medium text-slate-700 mb-2">Prix d'inscription</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500">FCFA</span>
                            <input type="number" id="prix_inscription" name="prix_inscription" value="{{ old('prix_inscription') }}" min="0" step="0.01" placeholder="5000"
                                class="w-full pl-16 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('prix_inscription') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        </div>
                        @error('prix_inscription')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Options d'inscription</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="liste_attente" name="liste_attente" value="1" {{ old('liste_attente') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="liste_attente" class="ml-2 text-sm text-slate-700">
                                    Liste d'attente
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_ouverture_inscription" class="block text-sm font-medium text-slate-700 mb-2">Ouverture des inscriptions <span class="text-red-500" id="star-ouverture" style="display:none;">*</span></label>
                        <input type="date" id="date_ouverture_inscription" name="date_ouverture_inscription" value="{{ old('date_ouverture_inscription') }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_ouverture_inscription') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('date_ouverture_inscription')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_fermeture_inscription" class="block text-sm font-medium text-slate-700 mb-2">Fermeture des inscriptions <span class="text-red-500" id="star-fermeture" style="display:none;">*</span></label>
                        <input type="date" id="date_fermeture_inscription" name="date_fermeture_inscription" value="{{ old('date_fermeture_inscription') }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_fermeture_inscription') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('date_fermeture_inscription')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsables -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-user-tie text-indigo-600 mr-2"></i>
                    Responsables
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="organisateur_principal_id" class="block text-sm font-medium text-slate-700 mb-2">Organisateur principal</label>
                        <select id="organisateur_principal_id" name="organisateur_principal_id"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('organisateur_principal_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="">Sélectionner un organisateur</option>
                            @foreach($organisateurs as $organisateur)
                                <option value="{{ $organisateur->id }}" {{ old('organisateur_principal_id') == $organisateur->id ? 'selected' : '' }}>
                                    {{ $organisateur->prenom }} {{ $organisateur->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('organisateur_principal_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="coordinateur_id" class="block text-sm font-medium text-slate-700 mb-2">Coordinateur</label>
                        <select id="coordinateur_id" name="coordinateur_id"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('coordinateur_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="">Sélectionner un coordinateur</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('coordinateur_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->prenom }} {{ $user->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('coordinateur_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="responsable_logistique_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable logistique</label>
                        <select id="responsable_logistique_id" name="responsable_logistique_id"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('responsable_logistique_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="">Sélectionner un responsable</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('responsable_logistique_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->prenom }} {{ $user->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('responsable_logistique_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="responsable_communication_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable communication</label>
                        <select id="responsable_communication_id" name="responsable_communication_id"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('responsable_communication_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            <option value="">Sélectionner un responsable</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('responsable_communication_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->prenom }} {{ $user->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('responsable_communication_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Médias -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-images text-pink-600 mr-2"></i>
                    Médias
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label for="image_principale" class="block text-sm font-medium text-slate-700 mb-2">Image principale</label>
                    <input type="file" id="image_principale" name="image_principale" accept="image/*"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('image_principale') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    @error('image_principale')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-slate-500">JPEG, JPG, PNG, WebP - Max 2Mo</p>
                </div>

                <div>
                    <label for="video_presentation" class="block text-sm font-medium text-slate-700 mb-2">Vidéo de présentation</label>
                    <input type="url" id="video_presentation" name="video_presentation" value="{{ old('video_presentation') }}" placeholder="https://youtube.com/watch?v=..."
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('video_presentation') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    @error('video_presentation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="diffusion-section" style="display: none;">
                    <label for="lien_diffusion" class="block text-sm font-medium text-slate-700 mb-2">Lien de diffusion</label>
                    <input type="url" id="lien_diffusion" name="lien_diffusion" value="{{ old('lien_diffusion') }}" placeholder="https://meet.google.com/..."
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lien_diffusion') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    @error('lien_diffusion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" name="statut" value="brouillon" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-slate-600 to-slate-700 text-white font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer en brouillon
                    </button>
                    @can('events.update')
                    <button type="submit" name="statut" value="planifie" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-calendar-check mr-2"></i> Créer et Planifier
                    </button>
                    @endcan
                    <a href="{{ route('private.events.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Génération automatique du slug
document.getElementById('titre').addEventListener('input', function() {
    updatePreview();
});

document.getElementById('sous_titre').addEventListener('input', function() {
    updatePreview();
});

document.getElementById('type_evenement').addEventListener('change', function() {
    updatePreview();
});

document.getElementById('categorie').addEventListener('change', function() {
    updatePreview();
});

// Gestion des options conditionnelles DJOBO
// document.getElementById('inscription_requise').addEventListener('change', function() {
//     const section = document.getElementById('inscription-section');
//     const date_fin = document.getElementById('date_fin');
//     const date_ouverture_inscription = document.getElementById('date_ouverture_inscription');
//     const date_fermeture_inscription = document.getElementById('date_fermeture_inscription');
//     if (this.checked) {
//         section.style.display = 'block';

//     } else {
//         section.style.display = 'none';
//     }
// });




document.getElementById('inscription_requise').addEventListener('change', function() {
    const section = document.getElementById('inscription-section');
    const dateFinEvent = document.getElementById('date_fin');
    const dateOuverture = document.getElementById('date_ouverture_inscription');
    const dateFermeture = document.getElementById('date_fermeture_inscription');
    const starOuverture = document.getElementById('star-ouverture');
    const starFermeture = document.getElementById('star-fermeture');

    if (this.checked) {
        // Afficher la section
        section.style.display = 'block';

        // Activer et rendre obligatoire
        dateOuverture.disabled = false;
        dateFermeture.disabled = false;
        dateOuverture.required = true;
        dateFermeture.required = true;

        // Afficher l’étoile rouge
        starOuverture.style.display = 'inline';
        starFermeture.style.display = 'inline';

        // Contraintes
        if (dateFinEvent.value) {
            dateOuverture.max = dateFinEvent.value;
            dateFermeture.max = dateFinEvent.value;
        }
        if (dateOuverture.value) {
            dateFermeture.min = dateOuverture.value;
        }

    } else {
        // Cacher la section
        section.style.display = 'none';

        // Désactiver et enlever obligatoire
        dateOuverture.disabled = true;
        dateFermeture.disabled = true;
        dateOuverture.required = false;
        dateFermeture.required = false;

        // Enlever l’étoile rouge
        starOuverture.style.display = 'none';
        starFermeture.style.display = 'none';

        // Vider les valeurs
        dateOuverture.value = '';
        dateFermeture.value = '';
    }
});

// Ajuster les contraintes si la date de fin change
document.getElementById('date_fin').addEventListener('change', function() {
    const dateOuverture = document.getElementById('date_ouverture_inscription');
    const dateFermeture = document.getElementById('date_fermeture_inscription');
    dateOuverture.max = this.value;
    dateFermeture.max = this.value;
});

// Ajuster la fermeture par rapport à l’ouverture
document.getElementById('date_ouverture_inscription').addEventListener('change', function() {
    const dateFermeture = document.getElementById('date_fermeture_inscription');
    dateFermeture.min = this.value;
});










document.getElementById('inscription_payante').addEventListener('change', function() {
    const section = document.getElementById('prix-section');
    if (this.checked) {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
    }
});

document.getElementById('diffusion_en_ligne').addEventListener('change', function() {
    const section = document.getElementById('diffusion-section');
    if (this.checked) {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
    }
});

// Prévisualisation de l'image
document.getElementById('image_principale').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            preview.innerHTML = `<img src="${e.target.result}" alt="Aperçu" class="w-full h-full object-cover rounded-xl">`;
        };
        reader.readAsDataURL(file);
    }
});

// Mise à jour de l'aperçu
function updatePreview() {
    const titre = document.getElementById('titre').value || 'Titre de l\'événement';
    const sousTitre = document.getElementById('sous_titre').value;
    const type = document.getElementById('type_evenement').value;
    const categorie = document.getElementById('categorie').value;

    document.getElementById('preview-titre').textContent = titre;

    const sousTitreElement = document.getElementById('preview-sous-titre');
    if (sousTitre) {
        sousTitreElement.textContent = sousTitre;
        sousTitreElement.classList.remove('hidden');
    } else {
        sousTitreElement.classList.add('hidden');
    }

    const typeElement = document.getElementById('preview-type');
    const categorieElement = document.getElementById('preview-categorie');

    if (type) {
        typeElement.textContent = type.charAt(0).toUpperCase() + type.slice(1).replace('_', ' ');
        typeElement.style.display = 'inline-flex';
    } else {
        typeElement.style.display = 'none';
    }

    if (categorie) {
        categorieElement.textContent = categorie.charAt(0).toUpperCase() + categorie.slice(1);
        categorieElement.style.display = 'inline-flex';
    } else {
        categorieElement.style.display = 'none';
    }
}

// Validation du formulaire
document.getElementById('eventForm').addEventListener('submit', function(e) {
    const titre = document.getElementById('titre').value.trim();
    const dateDebut = document.getElementById('date_debut').value;
    const heureDebut = document.getElementById('heure_debut').value;
    const lieuNom = document.getElementById('lieu_nom').value.trim();

    if (!titre || !dateDebut || !heureDebut || !lieuNom) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }

    // Validation des dates
    const dateFin = document.getElementById('date_fin').value;
    if (dateFin && new Date(dateFin) < new Date(dateDebut)) {
        e.preventDefault();
        alert('La date de fin doit être après la date de début.');
        return false;
    }

    // Validation inscription payante
    const inscriptionPayante = document.getElementById('inscription_payante').checked;
    const prixInscription = document.getElementById('prix_inscription').value;

    if (inscriptionPayante && !prixInscription) {
        e.preventDefault();
        alert('Le prix d\'inscription est obligatoire si l\'inscription est payante.');
        return false;
    }

    // Validation diffusion en ligne
    const diffusionEnLigne = document.getElementById('diffusion_en_ligne').checked;
    const lienDiffusion = document.getElementById('lien_diffusion').value;

    if (diffusionEnLigne && !lienDiffusion) {
        e.preventDefault();
        alert('Le lien de diffusion est obligatoire si la diffusion en ligne est activée.');
        return false;
    }
});

// Initialisation
updatePreview();

// Définir la date minimum à aujourd'hui
document.getElementById('date_debut').min = new Date().toISOString().split('T')[0];
document.getElementById('date_fin').min = new Date().toISOString().split('T')[0];
</script>
@endpush
@endsection
