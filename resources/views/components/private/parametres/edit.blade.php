@extends('layouts.private.main')
@section('title', 'Modifier les Paramètres')

@section('content')
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Modifier les Paramètres</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.parametres.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-cogs mr-2"></i>
                            Paramètres
                        </a>
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

        <form action="{{ route('private.parametres.update') }}" method="POST" enctype="multipart/form-data"
            class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Actions -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                        </button>

                        <a href="{{ route('private.parametres.index') }}"
                            class="inline-flex items-center px-4 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>

                        <button type="button" onclick="resetForm()"
                            class="inline-flex items-center px-4 py-3 bg-yellow-600 text-white font-medium rounded-xl hover:bg-yellow-700 transition-colors">
                            <i class="fas fa-undo mr-2"></i> Réinitialiser
                        </button>
                    </div>
                </div>
            </div>

            <!-- Informations de base -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-church text-blue-600 mr-2"></i>
                        Informations de Base
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nom_eglise" class="block text-sm font-medium text-slate-700 mb-2">Nom de l'Église
                                <span class="text-red-500">*</span></label>
                            <input type="text" name="nom_eglise" id="nom_eglise"
                                value="{{ old('nom_eglise', $parametres->nom_eglise) }}" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom_eglise') border-red-500 @enderror">
                            @error('nom_eglise')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="date_fondation" class="block text-sm font-medium text-slate-700 mb-2">Date de
                                Fondation</label>
                            <input type="date" name="date_fondation" id="date_fondation"
                                value="{{ old('date_fondation', $parametres->date_fondation?->format('Y-m-d')) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_fondation') border-red-500 @enderror">
                            @error('date_fondation')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description_eglise"
                            class="block text-sm font-medium text-slate-700 mb-2">Mot d'accueil et de salutation</label>
                        <input type="text" name="description_eglise" id="description_eglise" required value="{{ old('description_eglise', $parametres->description_eglise) }}" max="50"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description_eglise') border-red-500 @enderror"
                            placeholder="Fourmule de bienvenue..."/>
                        @error('description_eglise')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="vision" class="block text-sm font-medium text-slate-700 mb-2">Phrase introductive</label>
                        <input name="vision" type="text" id="vision" max="100" required value="{{ old('vision', $parametres->vision) }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('vision') border-red-500 @enderror"
                            placeholder="Phrase courte descriptive..."/>
                        @error('vision')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                </div>
            </div>

            <!-- Contact -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-address-book text-green-600 mr-2"></i>
                        Informations de Contact
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="telephone_1" class="block text-sm font-medium text-slate-700 mb-2">Téléphone
                                Principal <span class="text-red-500">*</span></label>
                            <input type="tel" name="telephone_1" id="telephone_1"
                                value="{{ old('telephone_1', $parametres->telephone_1) }}" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('telephone_1') border-red-500 @enderror">
                            @error('telephone_1')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telephone_2" class="block text-sm font-medium text-slate-700 mb-2">Téléphone
                                Secondaire</label>
                            <input type="tel" name="telephone_2" id="telephone_2"
                                value="{{ old('telephone_2', $parametres->telephone_2) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('telephone_2') border-red-500 @enderror">
                            @error('telephone_2')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email_principal" class="block text-sm font-medium text-slate-700 mb-2">Email
                                Principal <span class="text-red-500">*</span></label>
                            <input type="email" name="email_principal" id="email_principal"
                                value="{{ old('email_principal', $parametres->email_principal) }}" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email_principal') border-red-500 @enderror">
                            @error('email_principal')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email_secondaire" class="block text-sm font-medium text-slate-700 mb-2">Email
                                Secondaire</label>
                            <input type="email" name="email_secondaire" id="email_secondaire"
                                value="{{ old('email_secondaire', $parametres->email_secondaire) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email_secondaire') border-red-500 @enderror">
                            @error('email_secondaire')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label for="adresse" class="block text-sm font-medium text-slate-700 mb-2">Adresse <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="adresse" id="adresse" rows="3" required value="{{ old('adresse', $parametres->adresse) }}" max="50"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('adresse') border-red-500 @enderror"
                            placeholder="Adresse complète de l'église"/>
                        @error('adresse')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="ville" class="block text-sm font-medium text-slate-700 mb-2">Ville <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="ville" id="ville" value="{{ old('ville', $parametres->ville) }}"
                                required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('ville') border-red-500 @enderror">
                            @error('ville')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="commune" class="block text-sm font-medium text-slate-700 mb-2">Commune</label>
                            <input type="text" name="commune" id="commune"
                                value="{{ old('commune', $parametres->commune) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('commune') border-red-500 @enderror">
                            @error('commune')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="code_postal" class="block text-sm font-medium text-slate-700 mb-2">Code
                                Postal</label>
                            <input type="text" name="code_postal" id="code_postal"
                                value="{{ old('code_postal', $parametres->code_postal) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('code_postal') border-red-500 @enderror">
                            @error('code_postal')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="pays" class="block text-sm font-medium text-slate-700 mb-2">Pays <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="pays" id="pays" value="{{ old('pays', $parametres->pays) }}" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('pays') border-red-500 @enderror">
                        @error('pays')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Médias -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-images text-purple-600 mr-2"></i>
                        Médias
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Logo -->
                    <div>
                        <label for="logo" class="block text-sm font-medium text-slate-700 mb-2">Logo de l'Église</label>
                        <div class="flex items-center space-x-6">
                            @if($parametres->logo_url)
                                <div class="flex-shrink-0">
                                    <img src="{{ $parametres->logo_url }}" alt="Logo actuel"
                                        class="w-20 h-20 object-cover rounded-xl shadow-md">
                                </div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="logo" id="logo" accept="image/*"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('logo') border-red-500 @enderror">
                                <p class="text-sm text-slate-500 mt-1">Format accepté: JPG, PNG, SVG. Taille max: 2MB</p>
                            </div>
                        </div>
                        @error('logo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Images Hero -->
                    <div>
                        <label for="images_hero" class="block text-sm font-medium text-slate-700 mb-2">Images pour la Page
                            d'Accueil</label>

                        @if($parametres->images_hero_urls && count($parametres->images_hero_urls) > 0)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                @foreach($parametres->images_hero_urls as $index => $imageUrl)
                                    <div class="relative group">
                                        <img src="{{ $imageUrl }}" alt="Image hero {{ $index + 1 }}"
                                            class="w-full h-24 object-cover rounded-xl shadow-md">
                                        <button type="button" onclick="removeHeroImage({{ $index }})"
                                            class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <input type="file" name="images_hero[]" id="images_hero" multiple accept="image/*"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('images_hero.*') border-red-500 @enderror">
                        <p class="text-sm text-slate-500 mt-1">Vous pouvez sélectionner plusieurs images. Format accepté:
                            JPG, PNG. Taille max: 5MB par image</p>
                        @error('images_hero.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contenu Spirituel -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bible text-amber-600 mr-2"></i>
                        Contenu Spirituel
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label for="verset_biblique" class="block text-sm font-medium text-slate-700 mb-2">Verset
                                Biblique</label>
                            <textarea name="verset_biblique" id="verset_biblique" rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('verset_biblique') border-red-500 @enderror"
                                placeholder="Verset inspirant pour votre église...">{{ old('verset_biblique', $parametres->verset_biblique) }}</textarea>
                            @error('verset_biblique')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="reference_verset"
                                class="block text-sm font-medium text-slate-700 mb-2">Référence</label>
                            <input type="text" name="reference_verset" id="reference_verset"
                                value="{{ old('reference_verset', $parametres->reference_verset) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('reference_verset') border-red-500 @enderror"
                                placeholder="ex: Jean 3:16">
                            @error('reference_verset')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>






                </div>
            </div>

            <!-- Réseaux Sociaux -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-share-alt text-pink-600 mr-2"></i>
                        Réseaux Sociaux
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fab fa-facebook text-blue-600 mr-1"></i> Facebook
                            </label>
                            <input type="url" name="facebook_url" id="facebook_url"
                                value="{{ old('facebook_url', $parametres->facebook_url) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('facebook_url') border-red-500 @enderror"
                                placeholder="https://facebook.com/votre-eglise">
                            @error('facebook_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fab fa-instagram text-pink-600 mr-1"></i> Instagram
                            </label>
                            <input type="url" name="instagram_url" id="instagram_url"
                                value="{{ old('instagram_url', $parametres->instagram_url) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('instagram_url') border-red-500 @enderror"
                                placeholder="https://instagram.com/votre-eglise">
                            @error('instagram_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="youtube_url" class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fab fa-youtube text-red-600 mr-1"></i> YouTube
                            </label>
                            <input type="url" name="youtube_url" id="youtube_url"
                                value="{{ old('youtube_url', $parametres->youtube_url) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('youtube_url') border-red-500 @enderror"
                                placeholder="https://youtube.com/c/votre-eglise">
                            @error('youtube_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="twitter_url" class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fab fa-twitter text-sky-600 mr-1"></i> Twitter
                            </label>
                            <input type="url" name="twitter_url" id="twitter_url"
                                value="{{ old('twitter_url', $parametres->twitter_url) }}"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('twitter_url') border-red-500 @enderror"
                                placeholder="https://twitter.com/votre-eglise">
                            @error('twitter_url')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="website_url" class="block text-sm font-medium text-slate-700 mb-2">
                            <i class="fas fa-globe text-blue-600 mr-1"></i> Site Web
                        </label>
                        <input type="url" name="website_url" id="website_url"
                            value="{{ old('website_url', $parametres->website_url) }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('website_url') border-red-500 @enderror"
                            placeholder="https://www.votre-eglise.com">
                        @error('website_url')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>



            <!-- Programmes de l'église -->
            <div id="programmes"
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar-alt text-amber-600 mr-2"></i>
                        Programmes de l'Église
                    </h2>
                </div>
                <div class="p-6">
                    <div id="programmes-container" class="space-y-4">
                        @php
                            // Récupérer les données old() ou les données du modèle
                            $programmesData = old('programmes', $parametres->getProgrammes() ?: []);
                        @endphp

                        @if($programmesData && count($programmesData) > 0)
                            @foreach($programmesData as $index => $programme)
                                <div class="programme-item p-6 bg-slate-50 rounded-xl border border-slate-200"
                                    data-programme-id="{{ $programme['id'] ?? '' }}">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                        <!-- Titre et Description -->
                                        <div class="md:col-span-6">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Titre du Programme
                                                        <span class="text-red-500">*</span></label>
                                                    <input type="text" name="programmes[{{ $index }}][titre]"
                                                        value="{{ $programme['titre'] ?? '' }}" placeholder="Ex: Cultes Dominicaux"
                                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('programmes.' . $index . '.titre') border-red-500 @enderror"
                                                        required>
                                                    @error('programmes.' . $index . '.titre')
                                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                                                    <textarea name="programmes[{{ $index }}][description]" rows="2"
                                                        placeholder="Description du programme..."
                                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('programmes.' . $index . '.description') border-red-500 @enderror">{{ $programme['description'] ?? '' }}</textarea>
                                                    @error('programmes.' . $index . '.description')
                                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Icône et Type -->
                                        <div class="md:col-span-3">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Icône
                                                        FontAwesome</label>
                                                    <input type="text" name="programmes[{{ $index }}][icone]"
                                                        value="{{ $programme['icone'] ?? 'fas fa-calendar' }}"
                                                        placeholder="fas fa-praying-hands"
                                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('programmes.' . $index . '.icone') border-red-500 @enderror">
                                                    @error('programmes.' . $index . '.icone')
                                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Type
                                                        d'horaire</label>
                                                    <select name="programmes[{{ $index }}][type_horaire]"
                                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('programmes.' . $index . '.type_horaire') border-red-500 @enderror">
                                                        <option value="regulier" {{ ($programme['type_horaire'] ?? '') == 'regulier' ? 'selected' : '' }}>Régulier</option>
                                                        <option value="sur_rendez_vous" {{ ($programme['type_horaire'] ?? '') == 'sur_rendez_vous' ? 'selected' : '' }}>Sur rendez-vous</option>
                                                        <option value="permanent" {{ ($programme['type_horaire'] ?? '') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                                                        <option value="ponctuel" {{ ($programme['type_horaire'] ?? '') == 'ponctuel' ? 'selected' : '' }}>Ponctuel</option>
                                                    </select>
                                                    @error('programmes.' . $index . '.type_horaire')
                                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Horaires -->
                                        <div class="md:col-span-3">
                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-sm font-medium text-slate-700 mb-1">Jour</label>
                                                    <select name="programmes[{{ $index }}][jour]"
                                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('programmes.' . $index . '.jour') border-red-500 @enderror">
                                                        <option value="">-- Sélectionner --</option>
                                                        @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'] as $jour)
                                                            <option value="{{ $jour }}" {{ ($programme['jour'] ?? '') == $jour ? 'selected' : '' }}>{{ $jour }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('programmes.' . $index . '.jour')
                                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div class="grid grid-cols-2 gap-2">
                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">Début</label>
                                                        <input type="time" name="programmes[{{ $index }}][heure_debut]"
                                                            value="{{ $programme['heure_debut'] ?? '' }}"
                                                            class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('programmes.' . $index . '.heure_debut') border-red-500 @enderror">
                                                        @error('programmes.' . $index . '.heure_debut')
                                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-medium text-slate-700 mb-1">Fin</label>
                                                        <input type="time" name="programmes[{{ $index }}][heure_fin]"
                                                            value="{{ $programme['heure_fin'] ?? '' }}"
                                                            class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('programmes.' . $index . '.heure_fin') border-red-500 @enderror">
                                                        @error('programmes.' . $index . '.heure_fin')
                                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-slate-700 mb-1">Texte
                                                        d'affichage</label>
                                                    <input type="text" name="programmes[{{ $index }}][horaire_texte]"
                                                        value="{{ $programme['horaire_texte'] ?? '' }}"
                                                        placeholder="Ex: Dimanche : 9h00 - 11h30"
                                                        class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 @error('programmes.' . $index . '.horaire_texte') border-red-500 @enderror">
                                                    @error('programmes.' . $index . '.horaire_texte')
                                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Options et Actions -->
                                        <div class="md:col-span-12">
                                            <div class="flex items-center justify-between pt-4 border-t border-slate-300">
                                                <div class="flex items-center space-x-4">
                                                    <label class="flex items-center">
                                                        <input type="checkbox" name="programmes[{{ $index }}][est_public]" value="1"
                                                            {{ ($programme['est_public'] ?? true) ? 'checked' : '' }}
                                                            class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                        <span class="ml-2 text-sm text-slate-700">Public</span>
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="checkbox" name="programmes[{{ $index }}][est_actif]" value="1"
                                                            {{ ($programme['est_actif'] ?? true) ? 'checked' : '' }}
                                                            class="rounded border-slate-300 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                                        <span class="ml-2 text-sm text-slate-700">Actif</span>
                                                    </label>
                                                    <div class="text-sm text-slate-500">
                                                        Ordre: <input type="number" name="programmes[{{ $index }}][ordre]"
                                                            value="{{ $programme['ordre'] ?? ($index + 1) }}" min="1"
                                                            class="w-16 px-2 py-1 border border-slate-300 rounded text-xs @error('programmes.' . $index . '.ordre') border-red-500 @enderror">
                                                        @error('programmes.' . $index . '.ordre')
                                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <button type="button" onclick="duplicateProgramme(this)"
                                                        class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                                        <i class="fas fa-copy mr-1"></i> Dupliquer
                                                    </button>
                                                    <button type="button" onclick="removeProgramme(this)"
                                                        class="px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Champs cachés pour l'ID -->
                                        <input type="hidden" name="programmes[{{ $index }}][id]"
                                            value="{{ $programme['id'] ?? '' }}">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-200">
                        <button type="button" onclick="addProgramme()"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Ajouter un programme
                        </button>

                        <div class="text-sm text-slate-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            <span id="programmes-count">{{ count($programmesData) }}</span> programme(s) configuré(s)
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paramètres Système -->
            <div
                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cogs text-slate-600 mr-2"></i>
                        Paramètres Système
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="devise" class="block text-sm font-medium text-slate-700 mb-2">Devise</label>
                            <select name="devise" id="devise"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('devise') border-red-500 @enderror">
                                <option value="EUR" {{ old('devise', $parametres->devise) == 'EUR' ? 'selected' : '' }}>Euro
                                    (EUR)</option>
                                <option value="USD" {{ old('devise', $parametres->devise) == 'USD' ? 'selected' : '' }}>Dollar
                                    US (USD)</option>
                                <option value="XOF" {{ old('devise', $parametres->devise) == 'XOF' ? 'selected' : '' }}>Franc
                                    CFA (XOF)</option>
                                <option value="XAF" {{ old('devise', $parametres->devise) == 'XAF' ? 'selected' : '' }}>Franc
                                    CFA Central (XAF)</option>
                            </select>
                            @error('devise')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="langue" class="block text-sm font-medium text-slate-700 mb-2">Langue</label>
                            <select name="langue" id="langue"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('langue') border-red-500 @enderror">
                                <option value="fr" {{ old('langue', $parametres->langue) == 'fr' ? 'selected' : '' }}>Français
                                </option>
                                <option value="en" {{ old('langue', $parametres->langue) == 'en' ? 'selected' : '' }}>Anglais
                                </option>
                                <option value="es" {{ old('langue', $parametres->langue) == 'es' ? 'selected' : '' }}>Espagnol
                                </option>
                            </select>
                            @error('langue')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fuseau_horaire" class="block text-sm font-medium text-slate-700 mb-2">Fuseau
                                Horaire</label>
                            <select name="fuseau_horaire" id="fuseau_horaire"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('fuseau_horaire') border-red-500 @enderror">
                                <option value="Europe/Paris" {{ old('fuseau_horaire', $parametres->fuseau_horaire) == 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris</option>
                                <option value="Africa/Abidjan" {{ old('fuseau_horaire', $parametres->fuseau_horaire) == 'Africa/Abidjan' ? 'selected' : '' }}>Africa/Abidjan
                                </option>
                                <option value="America/New_York" {{ old('fuseau_horaire', $parametres->fuseau_horaire) == 'America/New_York' ? 'selected' : '' }}>America/New_York
                                </option>
                            </select>
                            @error('fuseau_horaire')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions finales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                        </button>

                        <a href="{{ route('private.parametres.index') }}"
                            class="inline-flex items-center px-4 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Retour sans sauvegarder
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @php
        $programmesData = old('programmes', $parametres->getProgrammes() ?: []);
       @endphp

    <script>

        let programmeIndex = {{ count($programmesData) }};
        // let programmeIndex = {{ $parametres->getProgrammes() ? count($parametres->getProgrammes()) : 0 }};

        function addProgramme() {
            const container = document.getElementById('programmes-container');
            const programmeHtml = `
                <div class="programme-item p-6 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <!-- Titre et Description -->
                        <div class="md:col-span-6">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Titre du Programme <span class="text-red-500">*</span></label>
                                    <input type="text" name="programmes[${programmeIndex}][titre]" placeholder="Ex: Cultes Dominicaux" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                                    <textarea name="programmes[${programmeIndex}][description]" rows="2" placeholder="Description du programme..." class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Icône et Type -->
                        <div class="md:col-span-3">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Icône FontAwesome</label>
                                    <input type="text" name="programmes[${programmeIndex}][icone]" value="fas fa-calendar" placeholder="fas fa-praying-hands" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Type d'horaire</label>
                                    <select name="programmes[${programmeIndex}][type_horaire]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="regulier">Régulier</option>
                                        <option value="sur_rendez_vous">Sur rendez-vous</option>
                                        <option value="permanent">Permanent</option>
                                        <option value="ponctuel">Ponctuel</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Horaires -->
                        <div class="md:col-span-3">
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Jour</label>
                                    <select name="programmes[${programmeIndex}][jour]" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">-- Sélectionner --</option>
                                        <option value="Lundi">Lundi</option>
                                        <option value="Mardi">Mardi</option>
                                        <option value="Mercredi">Mercredi</option>
                                        <option value="Jeudi">Jeudi</option>
                                        <option value="Vendredi">Vendredi</option>
                                        <option value="Samedi">Samedi</option>
                                        <option value="Dimanche">Dimanche</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 mb-1">Début</label>
                                        <input type="time" name="programmes[${programmeIndex}][heure_debut]" class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-700 mb-1">Fin</label>
                                        <input type="time" name="programmes[${programmeIndex}][heure_fin]" class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1">Texte d'affichage</label>
                                    <input type="text" name="programmes[${programmeIndex}][horaire_texte]" placeholder="Ex: Dimanche : 9h00 - 11h30" class="w-full px-2 py-1 text-sm border border-slate-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Options et Actions -->
                        <div class="md:col-span-12">
                            <div class="flex items-center justify-between pt-4 border-t border-slate-300">
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="programmes[${programmeIndex}][est_public]" value="1" checked class="rounded border-slate-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-slate-700">Public</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="programmes[${programmeIndex}][est_actif]" value="1" checked class="rounded border-slate-300 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-slate-700">Actif</span>
                                    </label>
                                    <div class="text-sm text-slate-500">
                                        Ordre: <input type="number" name="programmes[${programmeIndex}][ordre]" value="${programmeIndex + 1}" min="1" class="w-16 px-2 py-1 border border-slate-300 rounded text-xs">
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button" onclick="duplicateProgramme(this)" class="px-3 py-1 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-copy mr-1"></i> Dupliquer
                                    </button>
                                    <button type="button" onclick="removeProgramme(this)" class="px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors">
                                        <i class="fas fa-trash mr-1"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Champ caché pour l'ID (nouveau programme) -->
                        <input type="hidden" name="programmes[${programmeIndex}][id]" value="">
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', programmeHtml);
            programmeIndex++;
            updateProgrammesCount();
        }

        function removeProgramme(button) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce programme ?')) {
                button.closest('.programme-item').remove();
                updateProgrammesCount();
                reindexProgrammes();
            }
        }

        function duplicateProgramme(button) {
            const programmeItem = button.closest('.programme-item');
            const clonedItem = programmeItem.cloneNode(true);

            // Mettre à jour les names avec le nouvel index
            const inputs = clonedItem.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                if (input.name && input.name.includes('programmes[')) {
                    input.name = input.name.replace(/programmes\[\d+\]/, `programmes[${programmeIndex}]`);
                }
                // Vider l'ID pour en créer un nouveau
                if (input.name && input.name.includes('[id]')) {
                    input.value = '';
                }
            });

            // Ajouter "Copie de" au titre
            const titreInput = clonedItem.querySelector('input[name*="[titre]"]');
            if (titreInput && titreInput.value) {
                titreInput.value = 'Copie de ' + titreInput.value;
            }

            programmeItem.parentNode.insertBefore(clonedItem, programmeItem.nextSibling);
            programmeIndex++;
            updateProgrammesCount();
        }

        function reindexProgrammes() {
            const programmeItems = document.querySelectorAll('.programme-item');
            programmeItems.forEach((item, index) => {
                const inputs = item.querySelectorAll('input, textarea, select');
                inputs.forEach(input => {
                    if (input.name && input.name.includes('programmes[')) {
                        input.name = input.name.replace(/programmes\[\d+\]/, `programmes[${index}]`);
                    }
                });
            });
            programmeIndex = programmeItems.length;
        }

        function updateProgrammesCount() {
            const count = document.querySelectorAll('.programme-item').length;
            document.getElementById('programmes-count').textContent = count;
        }

        function removeHeroImage(index) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
                // Ici vous pouvez ajouter la logique pour supprimer l'image du serveur
                // Pour l'instant, on cache juste l'élément
                event.target.closest('.group').style.display = 'none';
            }
        }

        function resetForm() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ? Toutes les modifications non sauvegardées seront perdues.')) {
                document.querySelector('form').reset();
                location.reload();
            }
        }

        // Validation côté client
        document.querySelector('form').addEventListener('submit', function (e) {
            const requiredFields = [
                'nom_eglise',
                'telephone_1',
                'email_principal',
                'adresse',
                'ville',
                'pays'
            ];

            let hasErrors = false;

            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    hasErrors = true;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            // Vérifier qu'au moins un programme a un titre
            const programmeTitres = document.querySelectorAll('input[name*="[titre]"]');
            let hasValidProgramme = false;
            programmeTitres.forEach(input => {
                if (input.value.trim()) {
                    hasValidProgramme = true;
                }
            });

            if (hasErrors) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
                document.querySelector('.border-red-500').scrollIntoView({ behavior: 'smooth' });
            }
        });

        // Prévisualisation des images
        document.getElementById('logo').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    let preview = document.querySelector('#logo-preview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.id = 'logo-preview';
                        preview.className = 'w-20 h-20 object-cover rounded-xl shadow-md mt-2';
                        document.getElementById('logo').parentNode.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Validation des URLs
        document.querySelectorAll('input[type="url"]').forEach(input => {
            input.addEventListener('blur', function () {
                if (this.value && !this.value.match(/^https?:\/\/.+/)) {
                    this.setCustomValidity('Veuillez entrer une URL valide commençant par http:// ou https://');
                } else {
                    this.setCustomValidity('');
                }
            });
        });

        // Auto-génération du texte d'affichage
        document.addEventListener('change', function (e) {
            if (e.target.name && (e.target.name.includes('[jour]') || e.target.name.includes('[heure_debut]') || e.target.name.includes('[heure_fin]'))) {
                const programmeContainer = e.target.closest('.programme-item');
                const jour = programmeContainer.querySelector('select[name*="[jour]"]').value;
                const heureDebut = programmeContainer.querySelector('input[name*="[heure_debut]"]').value;
                const heureFin = programmeContainer.querySelector('input[name*="[heure_fin]"]').value;
                const horaireTexte = programmeContainer.querySelector('input[name*="[horaire_texte]"]');

                if (jour && heureDebut && heureFin && !horaireTexte.value) {
                    horaireTexte.value = `${jour} : ${heureDebut} - ${heureFin}`;
                }
            }
        });

        // Gestion du type d'horaire
        document.addEventListener('change', function (e) {
            if (e.target.name && e.target.name.includes('[type_horaire]')) {
                const programmeContainer = e.target.closest('.programme-item');
                const typeHoraire = e.target.value;
                const horaireTexte = programmeContainer.querySelector('input[name*="[horaire_texte]"]');

                if (typeHoraire === 'sur_rendez_vous' && !horaireTexte.value) {
                    horaireTexte.value = 'Sur rendez-vous';
                } else if (typeHoraire === 'permanent' && !horaireTexte.value) {
                    horaireTexte.value = 'Actions permanentes';
                }
            }
        });

        // Auto-sauvegarde en brouillon (optionnel)
        let autoSaveTimeout;
        document.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', function () {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    const formData = new FormData(document.querySelector('form'));
                    const data = {};
                    for (let [key, value] of formData.entries()) {
                        data[key] = value;
                    }
                    localStorage.setItem('parametres_draft', JSON.stringify(data));
                }, 2000);
            });
        });

        // Nettoyer le brouillon après soumission réussie
        document.querySelector('form').addEventListener('submit', function () {
            setTimeout(() => {
                localStorage.removeItem('parametres_draft');
            }, 1000);
        });
    </script>

@endsection
