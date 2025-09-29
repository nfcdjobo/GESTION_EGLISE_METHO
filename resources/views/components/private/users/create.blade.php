@extends('layouts.private.main')
@section('title', 'Créer un membres')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer un Membres</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.users.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-credit-card mr-2"></i>
                            Membres
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <span class="text-sm font-users text-slate-500">Ajouter un nouveau membre à votre communauté - {{ \Carbon\Carbon::now()->format('l d F Y') }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- En-tête avec gradient -->
        <div class="bg-white/80 rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 px-6 sm:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="mb-4 sm:mb-0">
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">Créer un nouvel membres</h2>
                        <p class="text-indigo-100 mt-2 text-sm sm:text-base">Remplissez les informations ci-dessous pour créer un compte</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('private.users.index') }}"
                           class="inline-flex items-center justify-center px-4 py-2.5 bg-white/10border border-white/20 rounded-xl font-medium text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('private.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="member-form">
            @csrf

            <!-- Section : Informations personnelles -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        Informations personnelles
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="prenom" class="block text-sm font-medium text-slate-700">Prénom *</label>
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 @error('prenom') border-red-300 @enderror">
                            @error('prenom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="nom" class="block text-sm font-medium text-slate-700">Nom *</label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 @error('nom') border-red-300 @enderror">
                            @error('nom')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="date_naissance" class="block text-sm font-medium text-slate-700">Date de naissance</label>
                            <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 @error('date_naissance') border-red-300 @enderror">
                            @error('date_naissance')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="sexe" class="block text-sm font-medium text-slate-700">Sexe *</label>
                            <select name="sexe" id="sexe" required
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 @error('sexe') border-red-300 @enderror">
                                <option value="">Sélectionner...</option>
                                <option value="masculin" {{ old('sexe') === 'masculin' ? 'selected' : '' }}>Masculin</option>
                                <option value="feminin" {{ old('sexe') === 'feminin' ? 'selected' : '' }}>Féminin</option>
                            </select>
                            @error('sexe')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="statut_matrimonial" class="block text-sm font-medium text-slate-700">Statut matrimonial</label>
                            <select name="statut_matrimonial" id="statut_matrimonial"
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                                <option value="">Sélectionner...</option>
                                <option value="celibataire" {{ old('statut_matrimonial') === 'celibataire' ? 'selected' : '' }}>Célibataire</option>
                                <option value="marie" {{ old('statut_matrimonial') === 'marie' ? 'selected' : '' }}>Marié(e)</option>
                                <option value="divorce" {{ old('statut_matrimonial') === 'divorce' ? 'selected' : '' }}>Divorcé(e)</option>
                                <option value="veuf" {{ old('statut_matrimonial') === 'veuf' ? 'selected' : '' }}>Veuf/Veuve</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label for="nombre_enfants" class="block text-sm font-medium text-slate-700">Nombre d'enfants</label>
                            <input type="number" name="nombre_enfants" id="nombre_enfants" value="{{ old('nombre_enfants', 0) }}" min="0"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="photo_profil" class="block text-sm font-medium text-slate-700">Photo de profil</label>
                            <input type="file" name="photo_profil" id="photo_profil" accept="image/*"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                            <p class="mt-1 text-sm text-slate-500">Formats acceptés: JPG, PNG. Taille max: 2MB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Contact -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-phone text-white"></i>
                        </div>
                        Informations de contact
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="telephone_1" class="block text-sm font-medium text-slate-700">Téléphone principal *</label>
                            <input type="tel" name="telephone_1" id="telephone_1" value="{{ old('telephone_1') }}" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 @error('telephone_1') border-red-300 @enderror">
                            @error('telephone_1')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="telephone_2" class="block text-sm font-medium text-slate-700">Téléphone secondaire</label>
                            <input type="tel" name="telephone_2" id="telephone_2" value="{{ old('telephone_2') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="email" class="block text-sm font-medium text-slate-700">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 @error('email') border-red-300 @enderror">
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Adresse -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-map-marker-alt text-white"></i>
                        </div>
                        Adresse
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label for="adresse_ligne_1" class="block text-sm font-medium text-slate-700">Adresse ligne 1 *</label>
                            <input type="text" name="adresse_ligne_1" id="adresse_ligne_1" value="{{ old('adresse_ligne_1') }}" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 @error('adresse_ligne_1') border-red-300 @enderror">
                            @error('adresse_ligne_1')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="adresse_ligne_2" class="block text-sm font-medium text-slate-700">Adresse ligne 2</label>
                            <input type="text" name="adresse_ligne_2" id="adresse_ligne_2" value="{{ old('adresse_ligne_2') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="ville" class="block text-sm font-medium text-slate-700">Ville *</label>
                            <input type="text" name="ville" id="ville" value="{{ old('ville') }}" required
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 @error('ville') border-red-300 @enderror">
                            @error('ville')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="code_postal" class="block text-sm font-medium text-slate-700">Code postal</label>
                            <input type="text" name="code_postal" id="code_postal" value="{{ old('code_postal') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="region" class="block text-sm font-medium text-slate-700">Région</label>
                            <input type="text" name="region" id="region" value="{{ old('region') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="pays" class="block text-sm font-medium text-slate-700">Pays</label>
                            <select name="pays" id="pays"
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                                <option value="CI" {{ old('pays', 'CI') === 'CI' ? 'selected' : '' }}>Côte d'Ivoire</option>
                                <option value="BF" {{ old('pays') === 'BF' ? 'selected' : '' }}>Burkina Faso</option>
                                <option value="ML" {{ old('pays') === 'ML' ? 'selected' : '' }}>Mali</option>
                                <option value="GH" {{ old('pays') === 'GH' ? 'selected' : '' }}>Ghana</option>
                                <option value="SN" {{ old('pays') === 'SN' ? 'selected' : '' }}>Sénégal</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Informations professionnelles -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-briefcase text-white"></i>
                        </div>
                        Informations professionnelles
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="profession" class="block text-sm font-medium text-slate-700">Profession</label>
                            <input type="text" name="profession" id="profession" value="{{ old('profession') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="employeur" class="block text-sm font-medium text-slate-700">Employeur</label>
                            <input type="text" name="employeur" id="employeur" value="{{ old('employeur') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Informations d'église -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-church text-white"></i>
                        </div>
                        Informations d'église
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="classe_id" class="block text-sm font-medium text-slate-700">Classe</label>
                            <select name="classe_id" id="classe_id"
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                                <option value="" >Aucune classe</option>
                                @foreach($classes as $classe)
                                <option value="{{ $classe->id }}" {{ old('classe_id') == $classe->id ? 'selected' : '' }}>
                                    {{ $classe->nom }}
                                </option>
                                @endforeach
                            </select>
                            @error('classe_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="date_adhesion" class="block text-sm font-medium text-slate-700">Date d'adhésion</label>
                            <input type="date" name="date_adhesion" id="date_adhesion" value="{{ old('date_adhesion') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                            @error('date_adhesion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="statut_membre" class="block text-sm font-medium text-slate-700">Statut membre *</label>
                            <select name="statut_membre" id="statut_membre" required
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 @error('statut_membre') border-red-300 @enderror">
                                <option value="">Sélectionner...</option>
                                <option value="actif" {{ old('statut_membre') === 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ old('statut_membre') === 'inactif' ? 'selected' : '' }}>Inactif</option>
                                <option value="visiteur" {{ old('statut_membre') === 'visiteur' ? 'selected' : '' }}>Visiteur</option>
                                <option value="nouveau_converti" {{ old('statut_membre') === 'nouveau_converti' ? 'selected' : '' }}>Nouveau converti</option>
                            </select>
                            @error('statut_membre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="statut_bapteme" class="block text-sm font-medium text-slate-700">Statut baptême *</label>
                            <select name="statut_bapteme" id="statut_bapteme" required
                                    class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 @error('statut_bapteme') border-red-300 @enderror">
                                <option value="">Sélectionner...</option>
                                <option value="non_baptise" {{ old('statut_bapteme') === 'non_baptise' ? 'selected' : '' }}>Non baptisé</option>
                                <option value="baptise" {{ old('statut_bapteme') === 'baptise' ? 'selected' : '' }}>Baptisé</option>
                                <option value="confirme" {{ old('statut_bapteme') === 'confirme' ? 'selected' : '' }}>Confirmé</option>
                            </select>
                            @error('statut_bapteme')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="date_bapteme_container" style="display: none;" class="space-y-2">
                            <label for="date_bapteme" class="block text-sm font-medium text-slate-700">Date de baptême</label>
                            <input type="date" name="date_bapteme" id="date_bapteme" value="{{ old('date_bapteme') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="eglise_precedente" class="block text-sm font-medium text-slate-700">Église précédente</label>
                            <input type="text" name="eglise_precedente" id="eglise_precedente" value="{{ old('eglise_precedente') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Contact d'urgence -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        Contact d'urgence
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label for="contact_urgence_nom" class="block text-sm font-medium text-slate-700">Nom du contact</label>
                            <input type="text" name="contact_urgence_nom" id="contact_urgence_nom" value="{{ old('contact_urgence_nom') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="contact_urgence_telephone" class="block text-sm font-medium text-slate-700">Téléphone du contact</label>
                            <input type="tel" name="contact_urgence_telephone" id="contact_urgence_telephone" value="{{ old('contact_urgence_telephone') }}"
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>

                        <div class="space-y-2">
                            <label for="contact_urgence_relation" class="block text-sm font-medium text-slate-700">Relation</label>
                            <input type="text" name="contact_urgence_relation" id="contact_urgence_relation" value="{{ old('contact_urgence_relation') }}"
                                   placeholder="Ex: Époux/Épouse, Parent, Ami..."
                                   class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Question pour création de compte -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-question text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-bold text-slate-800 mb-2">Création de compte utilisateur</h3>
                            <p class="text-slate-600 mb-4">Souhaitez-vous créer un compte utilisateur pour ce membre afin qu'il puisse se connecter à la plateforme ?</p>
                            <div class="flex space-x-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="create_account" value="yes" id="create_account_yes" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm font-medium text-slate-700">Oui, créer un compte</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="create_account" value="no" id="create_account_no" checked class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm font-medium text-slate-700">Non, pas de compte</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section : Compte et rôles (cachée par défaut) -->
            <div id="account-section" class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300" style="display: none;">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-key text-white"></i>
                        </div>
                        Compte et rôles
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-slate-700">Mot de passe *</label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                       class="w-full px-4 py-3 pr-12 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300 @error('password') border-red-300 @enderror">
                                <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors">
                                    <i class="fas fa-eye" id="password-eye-icon"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">
                                Le mot de passe doit contenir au moins 8 caractères, incluant des majuscules, minuscules et chiffres.
                            </p>
                            @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirmer le mot de passe *</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="w-full px-4 py-3 pr-12 border-2 border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-0 transition-all duration-200 hover:border-slate-300">
                                <button type="button" id="toggle-password-confirmation" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors">
                                    <i class="fas fa-eye" id="password-confirmation-eye-icon"></i>
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">Retapez le mot de passe pour confirmation.</p>
                            <div id="password-match-indicator" class="mt-2 text-sm hidden"></div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-4">Rôles</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach($roles as $role)
                                <label class="flex items-center p-4 border-2 border-slate-200 rounded-xl hover:bg-slate-50 hover:border-indigo-300 transition-all duration-200 cursor-pointer">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                           {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                                           class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $role->name }}</div>
                                        <div class="text-sm text-slate-500">{{ $role->description }}</div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            <p class="mt-2 text-sm text-slate-500">
                                Si aucun rôle n'est sélectionné, un rôle par défaut sera attribué selon le statut membre.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section CAPTCHA -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-teal-500 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        Vérification de sécurité
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-center">
                        <div class="bg-slate-100 border-2 border-slate-300 rounded-xl p-6 text-center">
                            <!-- Captcha simple mathématique -->
                            <div id="captcha-question" class="text-lg font-bold text-slate-700 mb-4"></div>
                            <input type="number" id="captcha-answer" name="captcha_answer"
                                   class="w-32 px-4 py-2 border-2 border-slate-300 rounded-lg text-center focus:border-indigo-500 focus:ring-0"
                                   placeholder="Réponse" required>
                            <input type="hidden" id="captcha-result" name="captcha_result">
                            <button type="button" id="refresh-captcha" class="ml-3 px-3 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                                <i class="fas fa-refresh"></i>
                            </button>
                            <p class="text-sm text-slate-500 mt-2">Résolvez cette opération pour confirmer que vous n'êtes pas un robot</p>
                            <div id="captcha-error" class="text-red-600 text-sm mt-2 hidden">La réponse du captcha est incorrecte</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-4 pt-6">
                <a href="{{ route('private.users.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
                <button type="submit" id="submit-btn" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i>Créer le membre
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélection des éléments DOM
    const statutBapteme = document.getElementById('statut_bapteme');
    const dateBaptemeContainer = document.getElementById('date_bapteme_container');
    const createAccountYes = document.getElementById('create_account_yes');
    const createAccountNo = document.getElementById('create_account_no');
    const accountSection = document.getElementById('account-section');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const passwordMatchIndicator = document.getElementById('password-match-indicator');
    const submitBtn = document.getElementById('submit-btn');
    const memberForm = document.getElementById('member-form');

    // Variables pour le captcha
    let captchaResult = 0;

    // Fonction pour gérer l'affichage de la date de baptême
    function toggleDateBapteme() {
        if (!statutBapteme || !dateBaptemeContainer) return;

        const value = statutBapteme.value;
        if (value === 'baptise' || value === 'confirme') {
            dateBaptemeContainer.style.display = 'block';
            dateBaptemeContainer.classList.add('animate-fade-in');
            const dateBaptemeInput = document.getElementById('date_bapteme');
            if (dateBaptemeInput) dateBaptemeInput.required = true;
        } else {
            dateBaptemeContainer.style.display = 'none';
            dateBaptemeContainer.classList.remove('animate-fade-in');
            const dateBaptemeInput = document.getElementById('date_bapteme');
            if (dateBaptemeInput) dateBaptemeInput.required = false;
        }
    }

    // Fonction pour gérer l'affichage de la section compte
    function toggleAccountSection() {
        if (!createAccountYes || !accountSection) return;

        if (createAccountYes.checked) {
            accountSection.style.display = 'block';
            accountSection.classList.add('animate-fade-in');

            // Rendre les champs requis
            if (passwordInput) passwordInput.required = true;
            if (passwordConfirmInput) passwordConfirmInput.required = true;

            // Configurer les boutons d'affichage/masquage des mots de passe
            setupPasswordToggleListeners();
        } else {
            accountSection.style.display = 'none';
            accountSection.classList.remove('animate-fade-in');

            // Rendre les champs non requis et les vider
            if (passwordInput) {
                passwordInput.required = false;
                passwordInput.value = '';
            }
            if (passwordConfirmInput) {
                passwordConfirmInput.required = false;
                passwordConfirmInput.value = '';
            }
        }
    }

    // Fonction pour basculer l'affichage des mots de passe
    function togglePasswordVisibility(inputField, eyeIcon) {
        if (!inputField || !eyeIcon) return;

        if (inputField.type === 'password') {
            inputField.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            inputField.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }

    // Configuration des event listeners pour les boutons de mot de passe
    function setupPasswordToggleListeners() {
        const togglePasswordBtn = document.getElementById('toggle-password');
        const togglePasswordConfirmBtn = document.getElementById('toggle-password-confirmation');
        const passwordEyeIcon = document.getElementById('password-eye-icon');
        const passwordConfirmEyeIcon = document.getElementById('password-confirmation-eye-icon');

        // Bouton mot de passe principal
        if (togglePasswordBtn && passwordEyeIcon && passwordInput) {
            // Supprimer les anciens listeners s'ils existent
            togglePasswordBtn.replaceWith(togglePasswordBtn.cloneNode(true));
            const newTogglePasswordBtn = document.getElementById('toggle-password');

            newTogglePasswordBtn.addEventListener('click', function(e) {
                e.preventDefault();
                togglePasswordVisibility(passwordInput, passwordEyeIcon);
            });
        }

        // Bouton confirmation mot de passe
        if (togglePasswordConfirmBtn && passwordConfirmEyeIcon && passwordConfirmInput) {
            // Supprimer les anciens listeners s'ils existent
            togglePasswordConfirmBtn.replaceWith(togglePasswordConfirmBtn.cloneNode(true));
            const newTogglePasswordConfirmBtn = document.getElementById('toggle-password-confirmation');

            newTogglePasswordConfirmBtn.addEventListener('click', function(e) {
                e.preventDefault();
                togglePasswordVisibility(passwordConfirmInput, passwordConfirmEyeIcon);
            });
        }
    }

    // Fonction pour vérifier la correspondance des mots de passe
    function checkPasswordMatch() {
        if (!passwordInput || !passwordConfirmInput || !passwordMatchIndicator) return;

        const password = passwordInput.value;
        const confirmPassword = passwordConfirmInput.value;

        if (confirmPassword.length > 0) {
            if (password === confirmPassword) {
                passwordMatchIndicator.textContent = '✓ Les mots de passe correspondent';
                passwordMatchIndicator.className = 'mt-2 text-sm text-green-600';
                passwordMatchIndicator.classList.remove('hidden');
            } else {
                passwordMatchIndicator.textContent = '✗ Les mots de passe ne correspondent pas';
                passwordMatchIndicator.className = 'mt-2 text-sm text-red-600';
                passwordMatchIndicator.classList.remove('hidden');
            }
        } else {
            passwordMatchIndicator.classList.add('hidden');
        }
    }

    // Fonction pour générer un nouveau captcha
    function generateCaptcha() {
        const num1 = Math.floor(Math.random() * 20) + 1;
        const num2 = Math.floor(Math.random() * 20) + 1;
        const operations = ['+', '-', '*'];
        const operation = operations[Math.floor(Math.random() * operations.length)];

        let question, result;

        switch(operation) {
            case '+':
                question = `${num1} + ${num2} = ?`;
                result = num1 + num2;
                break;
            case '-':
                question = `${num1} - ${num2} = ?`;
                result = num1 - num2;
                break;
            case '*':
                question = `${num1} × ${num2} = ?`;
                result = num1 * num2;
                break;
        }

        const captchaQuestion = document.getElementById('captcha-question');
        const captchaResultInput = document.getElementById('captcha-result');
        const captchaAnswer = document.getElementById('captcha-answer');
        const captchaError = document.getElementById('captcha-error');

        if (captchaQuestion) captchaQuestion.textContent = question;
        if (captchaResultInput) captchaResultInput.value = result;
        if (captchaAnswer) captchaAnswer.value = '';
        if (captchaError) captchaError.classList.add('hidden');

        captchaResult = result;
    }

    // Fonction pour valider le captcha
    function validateCaptcha() {
        const captchaAnswer = document.getElementById('captcha-answer');
        const captchaResultInput = document.getElementById('captcha-result');
        const captchaError = document.getElementById('captcha-error');

        if (!captchaAnswer || !captchaResultInput) return false;

        const userAnswer = parseInt(captchaAnswer.value);
        const correctAnswer = parseInt(captchaResultInput.value);

        if (userAnswer === correctAnswer) {
            if (captchaError) captchaError.classList.add('hidden');
            return true;
        } else {
            if (captchaError) captchaError.classList.remove('hidden');
            return false;
        }
    }

    // Event listeners principaux
    if (statutBapteme) {
        statutBapteme.addEventListener('change', toggleDateBapteme);
    }

    if (createAccountYes) {
        createAccountYes.addEventListener('change', toggleAccountSection);
    }

    if (createAccountNo) {
        createAccountNo.addEventListener('change', toggleAccountSection);
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', checkPasswordMatch);
    }

    if (passwordConfirmInput) {
        passwordConfirmInput.addEventListener('input', checkPasswordMatch);
    }

    // Event listener pour le refresh du captcha
    const refreshCaptchaBtn = document.getElementById('refresh-captcha');
    if (refreshCaptchaBtn) {
        refreshCaptchaBtn.addEventListener('click', generateCaptcha);
    }

    // Event listener pour la soumission du formulaire
    if (memberForm) {
        memberForm.addEventListener('submit', function(e) {
            let isValid = true;

            // Vérification du captcha
            if (!validateCaptcha()) {
                isValid = false;
                e.preventDefault();
            }

            // Vérification des mots de passe si compte créé
            if (createAccountYes && createAccountYes.checked && passwordInput && passwordConfirmInput) {
                const password = passwordInput.value;
                const confirmPassword = passwordConfirmInput.value;

                if (password !== confirmPassword) {
                    isValid = false;
                    e.preventDefault();
                    alert('Les mots de passe ne correspondent pas.');
                }

                if (password.length < 8) {
                    isValid = false;
                    e.preventDefault();
                    alert('Le mot de passe doit contenir au moins 8 caractères.');
                }
            }

            // Gestion du bouton de soumission
            if (submitBtn) {
                if (!isValid) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Créer le membre';
                } else {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Création en cours...';
                }
            }
        });
    }

    // Initialisation
    toggleDateBapteme();
    toggleAccountSection();
    generateCaptcha();

    // Animation des cartes au chargement
    const cards = document.querySelectorAll('.bg-white\\/80');
    if (cards.length > 0) {
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    // Animation de focus sur les champs
    const inputs = document.querySelectorAll('input, select, textarea');
    if (inputs.length > 0) {
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                const parent = this.closest('.space-y-2');
                if (parent) {
                    parent.classList.add('scale-102');
                }
            });

            input.addEventListener('blur', function() {
                const parent = this.closest('.space-y-2');
                if (parent) {
                    parent.classList.remove('scale-102');
                }
            });
        });
    }

    // Validation en temps réel pour le mot de passe
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const value = this.value;
            const parent = this.closest('.space-y-2');
            if (!parent) return;

            let strengthIndicator = parent.querySelector('.password-strength');

            if (!strengthIndicator && value.length > 0) {
                strengthIndicator = document.createElement('div');
                strengthIndicator.className = 'password-strength mt-2 text-xs';
                parent.appendChild(strengthIndicator);
            }

            if (value.length === 0) {
                if (strengthIndicator) strengthIndicator.remove();
                return;
            }

            let strength = 0;
            let messages = [];

            if (value.length >= 8) strength++; else messages.push('8 caractères min');
            if (/[A-Z]/.test(value)) strength++; else messages.push('1 majuscule');
            if (/[a-z]/.test(value)) strength++; else messages.push('1 minuscule');
            if (/[0-9]/.test(value)) strength++; else messages.push('1 chiffre');

            const colors = ['text-red-500', 'text-orange-500', 'text-yellow-500', 'text-green-500'];
            const labels = ['Faible', 'Moyen', 'Bon', 'Fort'];

            strengthIndicator.className = `password-strength mt-2 text-xs ${colors[strength - 1] || 'text-red-500'}`;
            strengthIndicator.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">Force: ${labels[strength - 1] || 'Très faible'}</span>
                    ${messages.length > 0 ? `<span class="text-slate-500">Manque: ${messages.join(', ')}</span>` : ''}
                </div>
            `;
        });
    }
});

// Styles CSS pour les animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
    .scale-102 {
        transform: scale(1.02);
        transition: transform 0.2s ease;
    }
    .space-y-2 > * + * {
        margin-top: 0.5rem;
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection
