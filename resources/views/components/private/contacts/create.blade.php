@extends('layouts.private.main')
@section('title', 'Créer un Contact')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer un Nouveau Contact</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.contacts.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-church mr-2"></i>
                        Contacts
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

    <form action="{{ route('private.contacts.store') }}" method="POST" id="contactForm" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations générales -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations de base -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informations de Base
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="nom_eglise" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nom de l'église <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nom_eglise" name="nom_eglise" value="{{ old('nom_eglise') }}" required maxlength="200" placeholder="Ex: Église Baptiste de la Paix"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom_eglise') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('nom_eglise')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="denomination" class="block text-sm font-medium text-slate-700 mb-2">Dénomination</label>
                                <input type="text" id="denomination" name="denomination" value="{{ old('denomination') }}" maxlength="100" placeholder="Ex: Baptiste, Pentecôtiste..."
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('denomination') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('denomination')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type_contact" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de contact <span class="text-red-500">*</span>
                                </label>
                                <select id="type_contact" name="type_contact" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_contact') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un type</option>
                                    @foreach($types_contact as $type)
                                        <option value="{{ $type }}" {{ old('type_contact') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                    @endforeach
                                </select>
                                @error('type_contact')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description_courte" class="block text-sm font-medium text-slate-700 mb-2">Description courte</label>
                            <textarea id="description_courte" name="description_courte" rows="3" placeholder="Brève description de l'église et de ses activités"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description_courte') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description_courte') }}</textarea>
                            @error('description_courte')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mission_vision" class="block text-sm font-medium text-slate-700 mb-2">Mission et Vision</label>
                            <textarea id="mission_vision" name="mission_vision" rows="4" placeholder="Mission, vision et valeurs de l'église"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('mission_vision') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('mission_vision') }}</textarea>
                            @error('mission_vision')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Coordonnées -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-phone text-green-600 mr-2"></i>
                            Coordonnées
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="telephone_principal" class="block text-sm font-medium text-slate-700 mb-2">Téléphone principal</label>
                                <input type="tel" id="telephone_principal" name="telephone_principal" value="{{ old('telephone_principal') }}" maxlength="20" placeholder="+225 XX XX XX XX XX"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('telephone_principal') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('telephone_principal')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="telephone_secondaire" class="block text-sm font-medium text-slate-700 mb-2">Téléphone secondaire</label>
                                <input type="tel" id="telephone_secondaire" name="telephone_secondaire" value="{{ old('telephone_secondaire') }}" maxlength="20" placeholder="+225 XX XX XX XX XX"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('telephone_secondaire') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('telephone_secondaire')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email_principal" class="block text-sm font-medium text-slate-700 mb-2">Email principal</label>
                                <input type="email" id="email_principal" name="email_principal" value="{{ old('email_principal') }}" placeholder="contact@eglise.org"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email_principal') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('email_principal')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="whatsapp" class="block text-sm font-medium text-slate-700 mb-2">WhatsApp</label>
                                <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" maxlength="20" placeholder="+225 XX XX XX XX XX"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('whatsapp') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('whatsapp')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Adresse -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                            Localisation
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="adresse_complete" class="block text-sm font-medium text-slate-700 mb-2">Adresse complète</label>
                            <textarea id="adresse_complete" name="adresse_complete" rows="3" placeholder="Adresse complète de l'église"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('adresse_complete') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('adresse_complete') }}</textarea>
                            @error('adresse_complete')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="quartier" class="block text-sm font-medium text-slate-700 mb-2">Quartier</label>
                                <input type="text" id="quartier" name="quartier" value="{{ old('quartier') }}" maxlength="100" placeholder="Ex: Cocody"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('quartier') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('quartier')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="ville" class="block text-sm font-medium text-slate-700 mb-2">Ville</label>
                                <input type="text" id="ville" name="ville" value="{{ old('ville') }}" maxlength="100" placeholder="Ex: Abidjan"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('ville') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('ville')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pays" class="block text-sm font-medium text-slate-700 mb-2">Pays</label>
                                <input type="text" id="pays" name="pays" value="{{ old('pays', 'Côte d\'Ivoire') }}" maxlength="100" placeholder="Côte d'Ivoire"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('pays') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('pays')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="latitude" class="block text-sm font-medium text-slate-700 mb-2">Latitude</label>
                                <input type="number" id="latitude" name="latitude" value="{{ old('latitude') }}" step="any" min="-90" max="90" placeholder="Ex: 5.3600"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('latitude') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('latitude')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="longitude" class="block text-sm font-medium text-slate-700 mb-2">Longitude</label>
                                <input type="number" id="longitude" name="longitude" value="{{ old('longitude') }}" step="any" min="-180" max="180" placeholder="Ex: -4.0083"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('longitude') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('longitude')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Leadership -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-tie text-purple-600 mr-2"></i>
                            Leadership
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pasteur_principal" class="block text-sm font-medium text-slate-700 mb-2">Pasteur principal</label>
                                <input type="text" id="pasteur_principal" name="pasteur_principal" value="{{ old('pasteur_principal') }}" maxlength="100" placeholder="Nom du pasteur principal"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('pasteur_principal') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('pasteur_principal')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="telephone_pasteur" class="block text-sm font-medium text-slate-700 mb-2">Téléphone du pasteur</label>
                                <input type="tel" id="telephone_pasteur" name="telephone_pasteur" value="{{ old('telephone_pasteur') }}" maxlength="20" placeholder="+225 XX XX XX XX XX"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('telephone_pasteur') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('telephone_pasteur')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="email_pasteur" class="block text-sm font-medium text-slate-700 mb-2">Email du pasteur</label>
                            <input type="email" id="email_pasteur" name="email_pasteur" value="{{ old('email_pasteur') }}" placeholder="pasteur@eglise.org"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email_pasteur') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('email_pasteur')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne de droite -->
            <div class="space-y-6">
                <!-- Aperçu -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-eye text-purple-600 mr-2"></i>
                            Aperçu
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Nom:</span>
                            <span id="preview-name" class="text-sm text-slate-900 font-semibold">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Type:</span>
                            <span id="preview-type" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Localisation:</span>
                            <span id="preview-location" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Pasteur:</span>
                            <span id="preview-pastor" class="text-sm text-slate-600">-</span>
                        </div>
                    </div>
                </div>

                <!-- Réseaux sociaux -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-share-alt text-blue-600 mr-2"></i>
                            Réseaux Sociaux
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label for="site_web_principal" class="block text-sm font-medium text-slate-700 mb-2">Site web</label>
                            <input type="url" id="site_web_principal" name="site_web_principal" value="{{ old('site_web_principal') }}" placeholder="https://www.eglise.org"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('site_web_principal') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('site_web_principal')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="facebook_url" class="block text-sm font-medium text-slate-700 mb-2">Facebook</label>
                            <input type="url" id="facebook_url" name="facebook_url" value="{{ old('facebook_url') }}" placeholder="https://facebook.com/eglise"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('facebook_url') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('facebook_url')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="instagram_url" class="block text-sm font-medium text-slate-700 mb-2">Instagram</label>
                            <input type="url" id="instagram_url" name="instagram_url" value="{{ old('instagram_url') }}" placeholder="https://instagram.com/eglise"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('instagram_url') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('instagram_url')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="youtube_url" class="block text-sm font-medium text-slate-700 mb-2">YouTube</label>
                            <input type="url" id="youtube_url" name="youtube_url" value="{{ old('youtube_url') }}" placeholder="https://youtube.com/eglise"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('youtube_url') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('youtube_url')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Paramètres -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-cog text-gray-600 mr-2"></i>
                            Paramètres
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <label for="visible_public" class="text-sm font-medium text-slate-700">Visible au public</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="visible_public" name="visible_public" value="1" {{ old('visible_public') ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div>
                            <label for="responsable_contact_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable du contact</label>
                            <select id="responsable_contact_id" name="responsable_contact_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('responsable_contact_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">Sélectionner un responsable</option>
                                @foreach($responsables as $responsable)
                                    <option value="{{ $responsable->id }}" {{ old('responsable_contact_id') == $responsable->id ? 'selected' : '' }}>
                                        {{ $responsable->nom }} {{ $responsable->prenom }}
                                    </option>
                                @endforeach
                            </select>
                            @error('responsable_contact_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="capacite_accueil" class="block text-sm font-medium text-slate-700 mb-2">Capacité d'accueil</label>
                                <input type="number" id="capacite_accueil" name="capacite_accueil" value="{{ old('capacite_accueil') }}" min="0" placeholder="200"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('capacite_accueil') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('capacite_accueil')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nombre_membres" class="block text-sm font-medium text-slate-700 mb-2">Nombre de membres</label>
                                <input type="number" id="nombre_membres" name="nombre_membres" value="{{ old('nombre_membres') }}" min="0" placeholder="150"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_membres') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('nombre_membres')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer le Contact
                    </button>
                    <a href="{{ route('private.contacts.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Mise à jour de l'aperçu
function updatePreview() {
    const nom = document.getElementById('nom_eglise').value || '-';
    const type = document.getElementById('type_contact').value || '-';
    const ville = document.getElementById('ville').value || '';
    const quartier = document.getElementById('quartier').value || '';
    const pasteur = document.getElementById('pasteur_principal').value || '-';

    document.getElementById('preview-name').textContent = nom;
    document.getElementById('preview-pastor').textContent = pasteur;

    // Type avec badge coloré
    const typeBadge = document.getElementById('preview-type');
    if (type !== '-') {
        typeBadge.textContent = type.charAt(0).toUpperCase() + type.slice(1);
        typeBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' + getTypeBadgeClass(type);
    } else {
        typeBadge.textContent = '-';
        typeBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800';
    }

    // Localisation
    let location = '';
    if (quartier && ville) {
        location = `${quartier}, ${ville}`;
    } else if (ville) {
        location = ville;
    } else if (quartier) {
        location = quartier;
    } else {
        location = '-';
    }
    document.getElementById('preview-location').textContent = location;
}

function getTypeBadgeClass(type) {
    switch(type) {
        case 'principal': return 'bg-blue-100 text-blue-800';
        case 'pastoral': return 'bg-green-100 text-green-800';
        case 'administratif': return 'bg-purple-100 text-purple-800';
        case 'urgence': return 'bg-red-100 text-red-800';
        case 'jeunesse': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

// Événements pour la mise à jour de l'aperçu
document.getElementById('nom_eglise').addEventListener('input', updatePreview);
document.getElementById('type_contact').addEventListener('change', updatePreview);
document.getElementById('ville').addEventListener('input', updatePreview);
document.getElementById('quartier').addEventListener('input', updatePreview);
document.getElementById('pasteur_principal').addEventListener('input', updatePreview);

// Validation du formulaire
document.getElementById('contactForm').addEventListener('submit', function(e) {
    const nom = document.getElementById('nom_eglise').value.trim();
    const type = document.getElementById('type_contact').value;

    if (!nom) {
        e.preventDefault();
        alert('Le nom de l\'église est obligatoire.');
        document.getElementById('nom_eglise').focus();
        return false;
    }

    if (!type) {
        e.preventDefault();
        alert('Le type de contact est obligatoire.');
        document.getElementById('type_contact').focus();
        return false;
    }
});

// Géolocalisation automatique
if (navigator.geolocation) {
    function getLocation() {
        if (confirm('Voulez-vous utiliser votre position actuelle pour la géolocalisation ?')) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
            });
        }
    }

    // Ajouter un bouton pour la géolocalisation
    const latInput = document.getElementById('latitude');
    const lonInput = document.getElementById('longitude');

    if (latInput && lonInput) {
        const geoButton = document.createElement('button');
        geoButton.type = 'button';
        geoButton.className = 'mt-2 inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors';
        geoButton.innerHTML = '<i class="fas fa-location-arrow mr-1"></i> Ma position';
        geoButton.onclick = getLocation;

        lonInput.parentNode.appendChild(geoButton);
    }
}

// Initialiser l'aperçu
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
@endpush
@endsection
