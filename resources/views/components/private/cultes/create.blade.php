@extends('layouts.private.main')
@section('title', 'Créer un Culte')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer un Nouveau Culte</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.cultes.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-church mr-2"></i>
                        Cultes
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

    <form action="{{ route('private.cultes.store') }}" method="POST" enctype="multipart/form-data" id="culteForm" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations générales -->
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="titre" class="block text-sm font-medium text-slate-700 mb-2">
                                    Titre du culte <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required maxlength="200" placeholder="Ex: Culte du dimanche matin"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('titre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('titre')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="programme_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Programme <span class="text-red-500">*</span>
                                </label>
                                <select id="programme_id" name="programme_id" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('programme_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un programme</option>
                                    @foreach($programmes as $programme)
                                        <option value="{{ $programme->id }}" {{ old('programme_id') == $programme->id ? 'selected' : '' }}>
                                            {{ $programme->nom_programme }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('programme_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <div class="@error('description') has-error @enderror">
                                <textarea id="description" name="description" rows="3" placeholder="Description du culte"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="type_culte" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de culte <span class="text-red-500">*</span>
                                </label>
                                <select id="type_culte" name="type_culte" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_culte') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner le type</option>
                                    @foreach(\App\Models\Culte::TYPE_CULTE as $key => $label)
                                        <option value="{{ $key }}" {{ old('type_culte') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type_culte')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="categorie" class="block text-sm font-medium text-slate-700 mb-2">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <select id="categorie" name="categorie" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('categorie') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @foreach(\App\Models\Culte::CATEGORIE as $key => $label)
                                        <option value="{{ $key }}" {{ old('categorie', 'regulier') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('categorie')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="statut" class="block text-sm font-medium text-slate-700 mb-2">
                                    Statut initial <span class="text-red-500">*</span>
                                </label>
                                <select id="statut" name="statut" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('statut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @foreach(\App\Models\Culte::STATUT as $key => $label)
                                        <option value="{{ $key }}" {{ old('statut', 'planifie') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('statut')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date, heure et lieu -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-alt text-green-600 mr-2"></i>
                            Date, Heure et Lieu
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="date_culte" class="block text-sm font-medium text-slate-700 mb-2">
                                    Date du culte <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="date_culte" name="date_culte" value="{{ old('date_culte') }}" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_culte') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('date_culte')
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
                                <label for="heure_fin" class="block text-sm font-medium text-slate-700 mb-2">Heure de fin (prévue)</label>
                                <input type="time" id="heure_fin" name="heure_fin" value="{{ old('heure_fin') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_fin') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('heure_fin')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="lieu" class="block text-sm font-medium text-slate-700 mb-2">
                                    Lieu <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="lieu" name="lieu" value="{{ old('lieu', 'Église principale') }}" required maxlength="200"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lieu') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('lieu')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="capacite_prevue" class="block text-sm font-medium text-slate-700 mb-2">Capacité prévue</label>
                                <input type="number" id="capacite_prevue" name="capacite_prevue" value="{{ old('capacite_prevue') }}" min="1"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('capacite_prevue') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('capacite_prevue')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="adresse_lieu" class="block text-sm font-medium text-slate-700 mb-2">Adresse complète (si lieu externe)</label>
                            <div class="@error('adresse_lieu') has-error @enderror">
                                <textarea id="adresse_lieu" name="adresse_lieu" rows="2" placeholder="Adresse complète du lieu si différent de l'église"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('adresse_lieu') }}</textarea>
                            </div>
                            @error('adresse_lieu')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Responsables -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-purple-600 mr-2"></i>
                            Responsables et Intervenants
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pasteur_principal_id" class="block text-sm font-medium text-slate-700 mb-2">Pasteur principal</label>
                                <select id="pasteur_principal_id" name="pasteur_principal_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('pasteur_principal_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un pasteur</option>
                                    @foreach($pasteurs as $pasteur)
                                        <option value="{{ $pasteur->id }}" {{ old('pasteur_principal_id') == $pasteur->id ? 'selected' : '' }}>
                                            {{ $pasteur->nom }} {{ $pasteur->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('pasteur_principal_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="predicateur_id" class="block text-sm font-medium text-slate-700 mb-2">Prédicateur</label>
                                <select id="predicateur_id" name="predicateur_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('predicateur_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un prédicateur</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('predicateur_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->nom }} {{ $user->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('predicateur_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="responsable_culte_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable du culte</label>
                                <select id="responsable_culte_id" name="responsable_culte_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('responsable_culte_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un responsable</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('responsable_culte_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->nom }} {{ $user->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsable_culte_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="dirigeant_louange_id" class="block text-sm font-medium text-slate-700 mb-2">Dirigeant de louange</label>
                                <select id="dirigeant_louange_id" name="dirigeant_louange_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('dirigeant_louange_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un dirigeant</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('dirigeant_louange_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->nom }} {{ $user->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dirigeant_louange_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message et prédication -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bible text-amber-600 mr-2"></i>
                            Message et Prédication
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="titre_message" class="block text-sm font-medium text-slate-700 mb-2">Titre du message</label>
                            <input type="text" id="titre_message" name="titre_message" value="{{ old('titre_message') }}" maxlength="300" placeholder="Titre de la prédication"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('titre_message') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('titre_message')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passage_biblique" class="block text-sm font-medium text-slate-700 mb-2">Passage biblique principal</label>
                            <input type="text" id="passage_biblique" name="passage_biblique" value="{{ old('passage_biblique') }}" maxlength="500" placeholder="Ex: Jean 3:16-17"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('passage_biblique') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('passage_biblique')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="resume_message" class="block text-sm font-medium text-slate-700 mb-2">Résumé du message</label>
                            <div class="@error('resume_message') has-error @enderror">
                                <textarea id="resume_message" name="resume_message" rows="4" placeholder="Résumé de la prédication"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('resume_message') }}</textarea>
                            </div>
                            @error('resume_message')
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
                            Options et Paramètres
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" id="est_public" name="est_public" value="1" {{ old('est_public', true) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="est_public" class="ml-2 text-sm font-medium text-slate-700">
                                        Culte ouvert au public
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="necessite_invitation" name="necessite_invitation" value="1" {{ old('necessite_invitation') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="necessite_invitation" class="ml-2 text-sm font-medium text-slate-700">
                                        Culte sur invitation uniquement
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="diffusion_en_ligne" name="diffusion_en_ligne" value="1" {{ old('diffusion_en_ligne') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="diffusion_en_ligne" class="ml-2 text-sm font-medium text-slate-700">
                                        Diffusion en ligne
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="est_enregistre" name="est_enregistre" value="1" {{ old('est_enregistre') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="est_enregistre" class="ml-2 text-sm font-medium text-slate-700">
                                        Culte enregistré (audio/vidéo)
                                    </label>
                                </div>
                            </div>

                            <div id="liens_section" class="space-y-4 hidden">
                                <div>
                                    <label for="lien_diffusion_live" class="block text-sm font-medium text-slate-700 mb-2">Lien de diffusion en direct</label>
                                    <input type="url" id="lien_diffusion_live" name="lien_diffusion_live" value="{{ old('lien_diffusion_live') }}" placeholder="https://..."
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lien_diffusion_live') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('lien_diffusion_live')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Aperçu et aide -->
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
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Titre:</span>
                            <span id="preview-titre" class="text-sm text-slate-900 font-semibold">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Type:</span>
                            <span id="preview-type" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Date:</span>
                            <span id="preview-date" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Heure:</span>
                            <span id="preview-heure" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Lieu:</span>
                            <span id="preview-lieu" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Statut:</span>
                            <span id="preview-statut" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">-</span>
                        </div>
                    </div>
                </div>

                <!-- Guide des types de cultes -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info text-green-600 mr-2"></i>
                            Guide des Types
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div><strong>Dimanche Matin:</strong> Culte principal</div>
                        <div><strong>Dimanche Soir:</strong> Culte du soir</div>
                        <div><strong>Mercredi:</strong> Culte de milieu de semaine</div>
                        <div><strong>Vendredi:</strong> Jeûne et prière</div>
                        <div><strong>Samedi Jeunes:</strong> Culte des jeunes</div>
                        <div><strong>Spécial:</strong> Événement particulier</div>
                        <div><strong>Conférence:</strong> Événement de formation</div>
                        <div><strong>Baptême:</strong> Cérémonie de baptême</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer le Culte
                    </button>
                    <a href="{{ route('private.cultes.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
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
// Mise à jour de l'aperçu en temps réel
function updatePreview() {
    const titre = document.getElementById('titre').value || '-';
    const typeSelect = document.getElementById('type_culte');
    const type = typeSelect.options[typeSelect.selectedIndex]?.text || '-';
    const date = document.getElementById('date_culte').value || '-';
    const heureDebut = document.getElementById('heure_debut').value || '';
    const heureFin = document.getElementById('heure_fin').value || '';
    const lieu = document.getElementById('lieu').value || '-';
    const statutSelect = document.getElementById('statut');
    const statut = statutSelect.options[statutSelect.selectedIndex]?.text || '-';

    document.getElementById('preview-titre').textContent = titre;
    document.getElementById('preview-type').textContent = type;
    document.getElementById('preview-date').textContent = date !== '-' ? new Date(date).toLocaleDateString('fr-FR') : '-';
    document.getElementById('preview-heure').textContent = heureDebut + (heureFin ? ' - ' + heureFin : '');
    document.getElementById('preview-lieu').textContent = lieu;
    document.getElementById('preview-statut').textContent = statut;
}

// Gestion des liens de diffusion
function toggleLinksSection() {
    const diffusionCheckbox = document.getElementById('diffusion_en_ligne');
    const enregistreCheckbox = document.getElementById('est_enregistre');
    const linksSection = document.getElementById('liens_section');

    if (diffusionCheckbox.checked || enregistreCheckbox.checked) {
        linksSection.classList.remove('hidden');
    } else {
        linksSection.classList.add('hidden');
    }
}

// Gestion des options exclusives
document.getElementById('est_public').addEventListener('change', function() {
    const invitationCheckbox = document.getElementById('necessite_invitation');
    if (this.checked) {
        invitationCheckbox.checked = false;
    }
});

document.getElementById('necessite_invitation').addEventListener('change', function() {
    const publicCheckbox = document.getElementById('est_public');
    if (this.checked) {
        publicCheckbox.checked = false;
    }
});

// Événements pour la mise à jour de l'aperçu
['titre', 'type_culte', 'date_culte', 'heure_debut', 'heure_fin', 'lieu', 'statut'].forEach(id => {
    document.getElementById(id).addEventListener('input', updatePreview);
    document.getElementById(id).addEventListener('change', updatePreview);
});

// Événements pour les liens
document.getElementById('diffusion_en_ligne').addEventListener('change', toggleLinksSection);
document.getElementById('est_enregistre').addEventListener('change', toggleLinksSection);

// Validation du formulaire avec synchronisation CKEditor
document.getElementById('culteForm').addEventListener('submit', function(e) {
    // Synchroniser tous les éditeurs CKEditor avant validation
    if (window.CKEditorInstances) {
        Object.values(window.CKEditorInstances).forEach(editor => {
            const element = editor.sourceElement;
            if (element) {
                element.value = editor.getData();
            }
        });
    }

    const titre = document.getElementById('titre').value.trim();
    const date = document.getElementById('date_culte').value;
    const heure = document.getElementById('heure_debut').value;
    const lieu = document.getElementById('lieu').value.trim();
    const programme = document.getElementById('programme_id').value;

    if (!titre || !date || !heure || !lieu || !programme) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }

    // Vérifier que la date n'est pas dans le passé
    const selectedDate = new Date(date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (selectedDate < today) {
        e.preventDefault();
        alert('La date du culte ne peut pas être dans le passé.');
        return false;
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    toggleLinksSection();
});
</script>
@endpush
@endsection
