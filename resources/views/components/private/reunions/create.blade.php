@extends('layouts.private.main')
@section('title', 'Créer une Réunion')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer une Nouvelle Réunion</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.reunions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Réunions
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
    @can('reunions.create')
    <form action="{{ route('private.reunions.store') }}" method="POST" enctype="multipart/form-data" id="reunionForm" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Contenu principal -->
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
                                    Titre de la réunion <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="titre" name="titre" value="{{ old('titre') }}" required maxlength="200" placeholder="Ex: Réunion du conseil d'administration"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('titre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('titre')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type_reunion_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de réunion <span class="text-red-500">*</span>
                                </label>
                                <select id="type_reunion_id" name="type_reunion_id" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_reunion_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un type</option>
                                    @foreach($typesReunions as $type)
                                        <option value="{{ $type->id }}" {{ old('type_reunion_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_reunion_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <div class="@error('description') has-error @enderror">
                                <textarea id="description" name="description" rows="4" placeholder="Description de la réunion"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="objectifs" class="block text-sm font-medium text-slate-700 mb-2">Objectifs</label>
                            <div class="@error('objectifs') has-error @enderror">
                                <textarea id="objectif" name="objectifs" rows="4" placeholder="Objectifs de la réunion"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('objectifs') }}</textarea>
                            </div>
                            @error('objectifs')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="statut" class="block text-sm font-medium text-slate-700 mb-2">
                                    Statut initial <span class="text-red-500">*</span>
                                </label>
                                <select id="statut" name="statut" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('statut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="planifiee" {{ old('statut', 'planifiee') == 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                                    <option value="confirmee" {{ old('statut') == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="planifie" {{ old('statut') == 'planifie' ? 'selected' : '' }}>En préparation</option>
                                </select>
                                @error('statut')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="niveau_priorite" class="block text-sm font-medium text-slate-700 mb-2">
                                    Niveau de priorité <span class="text-red-500">*</span>
                                </label>
                                <select id="niveau_priorite" name="niveau_priorite" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('niveau_priorite') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="faible" {{ old('niveau_priorite') == 'faible' ? 'selected' : '' }}>Faible</option>
                                    <option value="normale" {{ old('niveau_priorite', 'normale') == 'normale' ? 'selected' : '' }}>Normale</option>
                                    <option value="haute" {{ old('niveau_priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                                    <option value="urgente" {{ old('niveau_priorite') == 'urgente' ? 'selected' : '' }}>Urgente</option>
                                    <option value="critique" {{ old('niveau_priorite') == 'critique' ? 'selected' : '' }}>Critique</option>
                                </select>
                                @error('niveau_priorite')
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
                                <label for="date_reunion" class="block text-sm font-medium text-slate-700 mb-2">
                                    Date de la réunion <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="date_reunion" name="date_reunion" value="{{ old('date_reunion') }}" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_reunion') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('date_reunion')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="heure_debut_prevue" class="block text-sm font-medium text-slate-700 mb-2">
                                    Heure de début <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="heure_debut_prevue" name="heure_debut_prevue" value="{{ old('heure_debut_prevue') }}" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_debut_prevue') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('heure_debut_prevue')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="heure_fin_prevue" class="block text-sm font-medium text-slate-700 mb-2">Heure de fin (prévue)</label>
                                <input type="time" id="heure_fin_prevue" name="heure_fin_prevue" value="{{ old('heure_fin_prevue') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_fin_prevue') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('heure_fin_prevue')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="lieu" class="block text-sm font-medium text-slate-700 mb-2">
                                    Lieu <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="lieu" name="lieu" value="{{ old('lieu', 'Salle de réunion') }}" required maxlength="200"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lieu') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('lieu')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="salle" class="block text-sm font-medium text-slate-700 mb-2">Salle spécifique</label>
                                <input type="text" id="salle" name="salle" value="{{ old('salle') }}" maxlength="100" placeholder="Ex: Salle de conférence A"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('salle') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('salle')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="adresse_complete" class="block text-sm font-medium text-slate-700 mb-2">Adresse complète (si lieu externe)</label>
                            <div class="@error('adresse_complete') has-error @enderror">
                                <textarea id="adresse_complete" name="adresse_complete" rows="2" placeholder="Adresse complète du lieu si différent du siège"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('adresse_complete') }}</textarea>
                            </div>
                            @error('adresse_complete')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="capacite_salle" class="block text-sm font-medium text-slate-700 mb-2">Capacité de la salle</label>
                                <input type="number" id="capacite_salle" name="capacite_salle" value="{{ old('capacite_salle') }}" min="1"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('capacite_salle') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('capacite_salle')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nombre_places_disponibles" class="block text-sm font-medium text-slate-700 mb-2">Nombre de places disponibles</label>
                                <input type="number" id="nombre_places_disponibles" name="nombre_places_disponibles" value="{{ old('nombre_places_disponibles') }}" min="1"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_places_disponibles') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('nombre_places_disponibles')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responsables et équipe -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-purple-600 mr-2"></i>
                            Responsables et Équipe
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="organisateur_principal_id" class="block text-sm font-medium text-slate-700 mb-2">
                                    Organisateur principal <span class="text-red-500">*</span>
                                </label>
                                <select id="organisateur_principal_id" name="organisateur_principal_id" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('organisateur_principal_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un organisateur</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('organisateur_principal_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->nom }} {{ $user->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('organisateur_principal_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="animateur_id" class="block text-sm font-medium text-slate-700 mb-2">Animateur/Facilitateur</label>
                                <select id="animateur_id" name="animateur_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('animateur_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un animateur</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('animateur_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->nom }} {{ $user->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('animateur_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="responsable_technique_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable technique</label>
                                <select id="responsable_technique_id" name="responsable_technique_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('responsable_technique_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un responsable technique</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('responsable_technique_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->nom }} {{ $user->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsable_technique_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="responsable_accueil_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable accueil</label>
                                <select id="responsable_accueil_id" name="responsable_accueil_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('responsable_accueil_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un responsable accueil</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('responsable_accueil_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->nom }} {{ $user->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsable_accueil_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenu et programme -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-list-alt text-amber-600 mr-2"></i>
                            Contenu et Programme
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="message_principal" class="block text-sm font-medium text-slate-700 mb-2">Message principal</label>
                            <div class="@error('message_principal') has-error @enderror">
                                <textarea id="message_principal" name="message_principal" rows="4" placeholder="Message ou enseignement principal de la réunion"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('message_principal') }}</textarea>
                            </div>
                            @error('message_principal')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passage_biblique" class="block text-sm font-medium text-slate-700 mb-2">Passage biblique de référence</label>
                            <input type="text" id="passage_biblique" name="passage_biblique" value="{{ old('passage_biblique') }}" maxlength="500" placeholder="Ex: Jean 3:16-17"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('passage_biblique') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('passage_biblique')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="materiel_fourni" class="block text-sm font-medium text-slate-700 mb-2">Matériel fourni</label>
                                <div class="@error('materiel_fourni') has-error @enderror">
                                    <textarea id="materiel_fourni" name="materiel_fourni" rows="3" placeholder="Matériel qui sera fourni aux participants"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('materiel_fourni') }}</textarea>
                                </div>
                                @error('materiel_fourni')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="materiel_apporter" class="block text-sm font-medium text-slate-700 mb-2">Matériel à apporter</label>
                                <div class="@error('materiel_apporter') has-error @enderror">
                                    <textarea id="materiel_apporter" name="materiel_apporter" rows="3" placeholder="Matériel que les participants doivent apporter"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('materiel_apporter') }}</textarea>
                                </div>
                                @error('materiel_apporter')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Options et paramètres -->
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
                                <h3 class="text-lg font-semibold text-slate-800">Diffusion et enregistrement</h3>
                                <div class="flex items-center">
                                    <input type="checkbox" id="diffusion_en_ligne" name="diffusion_en_ligne" value="1" {{ old('diffusion_en_ligne') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="diffusion_en_ligne" class="ml-2 text-sm font-medium text-slate-700">
                                        Diffusion en ligne
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="enregistrement_autorise" name="enregistrement_autorise" value="1" {{ old('enregistrement_autorise') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="enregistrement_autorise" class="ml-2 text-sm font-medium text-slate-700">
                                        Enregistrement autorisé
                                    </label>
                                </div>

                                <div id="liens_section" class="space-y-4 hidden">
                                    <div>
                                        <label for="lien_diffusion" class="block text-sm font-medium text-slate-700 mb-2">Lien de diffusion</label>
                                        <input type="url" id="lien_diffusion" name="lien_diffusion" value="{{ old('lien_diffusion') }}" placeholder="https://..."
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lien_diffusion') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('lien_diffusion')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-slate-800">Inscription</h3>
                                <div>
                                    <label for="limite_inscription" class="block text-sm font-medium text-slate-700 mb-2">Date limite d'inscription</label>
                                    <input type="date" id="limite_inscription" name="limite_inscription" value="{{ old('limite_inscription') }}"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('limite_inscription') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('limite_inscription')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="liste_attente_activee" name="liste_attente_activee" value="1" {{ old('liste_attente_activee') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="liste_attente_activee" class="ml-2 text-sm font-medium text-slate-700">
                                        Activer la liste d'attente
                                    </label>
                                </div>

                                <div>
                                    <label for="frais_inscription" class="block text-sm font-medium text-slate-700 mb-2">Frais d'inscription (FCFA)</label>
                                    <input type="number" id="frais_inscription" name="frais_inscription" value="{{ old('frais_inscription') }}" step="0.01" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('frais_inscription') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('frais_inscription')
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
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Priorité:</span>
                            <span id="preview-priorite" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">-</span>
                        </div>
                    </div>
                </div>

                <!-- Guide de création -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-lightbulb text-green-600 mr-2"></i>
                            Conseils
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div><strong>Titre:</strong> Choisissez un titre clair et descriptif</div>
                        <div><strong>Type:</strong> Le type détermine le modèle de réunion utilisé</div>
                        <div><strong>Organisateur:</strong> Responsable principal de la réunion</div>
                        <div><strong>Animateur:</strong> Personne qui facilite la réunion</div>
                        <div><strong>Priorité:</strong> Détermine l'urgence et l'importance</div>
                        <div><strong>Diffusion:</strong> Pour les réunions en ligne ou hybrides</div>
                        <div><strong>Places:</strong> Limitez si nécessaire pour gérer l'affluence</div>
                    </div>
                </div>

                <!-- Récurrence -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-repeat text-indigo-600 mr-2"></i>
                            Récurrence
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="create_recurrence" name="create_recurrence" value="1"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="create_recurrence" class="ml-2 text-sm font-medium text-slate-700">
                                Créer une série récurrente
                            </label>
                        </div>

                        <div id="recurrence_options" class="space-y-4 hidden">
                            <div>
                                <label for="frequence" class="block text-sm font-medium text-slate-700 mb-2">Fréquence</label>
                                <select id="frequence" name="frequence" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                                    <option value="hebdomadaire">Hebdomadaire</option>
                                    <option value="bimensuel">Bimensuel</option>
                                    <option value="mensuel">Mensuel</option>
                                    <option value="trimestriel">Trimestriel</option>
                                </select>
                            </div>

                            <div>
                                <label for="nombre_occurrences" class="block text-sm font-medium text-slate-700 mb-2">Nombre d'occurrences</label>
                                <input type="number" id="nombre_occurrences" name="nombre_occurrences" min="1" max="52" value="4" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                            </div>

                            <div>
                                <label for="fin_recurrence" class="block text-sm font-medium text-slate-700 mb-2">Date de fin (optionnel)</label>
                                <input type="date" id="fin_recurrence" name="fin_recurrence" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer la Réunion
                    </button>
                    <a href="{{ route('private.reunions.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
    @endcan
</div>

{{-- Inclure les ressources CKEditor --}}
@include('partials.ckeditor-resources')

@push('scripts')
<script>
// Mise à jour de l'aperçu en temps réel
function updatePreview() {
    const titre = document.getElementById('titre').value || '-';
    const typeSelect = document.getElementById('type_reunion_id');
    const type = typeSelect.options[typeSelect.selectedIndex]?.text || '-';
    const date = document.getElementById('date_reunion').value || '-';
    const heureDebut = document.getElementById('heure_debut_prevue').value || '';
    const heureFin = document.getElementById('heure_fin_prevue').value || '';
    const lieu = document.getElementById('lieu').value || '-';
    const statutSelect = document.getElementById('statut');
    const statut = statutSelect.options[statutSelect.selectedIndex]?.text || '-';
    const prioriteSelect = document.getElementById('niveau_priorite');
    const priorite = prioriteSelect.options[prioriteSelect.selectedIndex]?.text || '-';

    document.getElementById('preview-titre').textContent = titre;
    document.getElementById('preview-type').textContent = type;
    document.getElementById('preview-date').textContent = date !== '-' ? new Date(date).toLocaleDateString('fr-FR') : '-';
    document.getElementById('preview-heure').textContent = heureDebut + (heureFin ? ' - ' + heureFin : '');
    document.getElementById('preview-lieu').textContent = lieu;
    document.getElementById('preview-statut').textContent = statut;
    document.getElementById('preview-priorite').textContent = priorite;
}

// Gestion des options de diffusion
function toggleLinksSection() {
    const diffusionCheckbox = document.getElementById('diffusion_en_ligne');
    const enregistrementCheckbox = document.getElementById('enregistrement_autorise');
    const linksSection = document.getElementById('liens_section');

    if (diffusionCheckbox.checked || enregistrementCheckbox.checked) {
        linksSection.classList.remove('hidden');
    } else {
        linksSection.classList.add('hidden');
    }
}

// Gestion des options de récurrence
function toggleRecurrenceOptions() {
    const recurrenceCheckbox = document.getElementById('create_recurrence');
    const recurrenceOptions = document.getElementById('recurrence_options');

    if (recurrenceCheckbox.checked) {
        recurrenceOptions.classList.remove('hidden');
    } else {
        recurrenceOptions.classList.add('hidden');
    }
}

// Événements pour la mise à jour de l'aperçu
['titre', 'type_reunion_id', 'date_reunion', 'heure_debut_prevue', 'heure_fin_prevue', 'lieu', 'statut', 'niveau_priorite'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    }
});

// Événements pour les options
document.getElementById('diffusion_en_ligne').addEventListener('change', toggleLinksSection);
document.getElementById('enregistrement_autorise').addEventListener('change', toggleLinksSection);
document.getElementById('create_recurrence').addEventListener('change', toggleRecurrenceOptions);

// Validation du formulaire avec synchronisation CKEditor
document.getElementById('reunionForm').addEventListener('submit', function(e) {
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
    const date = document.getElementById('date_reunion').value;
    const heure = document.getElementById('heure_debut_prevue').value;
    const lieu = document.getElementById('lieu').value.trim();
    const typeReunion = document.getElementById('type_reunion_id').value;
    const organisateur = document.getElementById('organisateur_principal_id').value;

    if (!titre || !date || !heure || !lieu || !typeReunion || !organisateur) {
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
        alert('La date de la réunion ne peut pas être dans le passé.');
        return false;
    }

    // Vérifier que l'heure de fin est après l'heure de début
    const heureFin = document.getElementById('heure_fin_prevue').value;
    if (heureFin && heure >= heureFin) {
        e.preventDefault();
        alert('L\'heure de fin doit être postérieure à l\'heure de début.');
        return false;
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    toggleLinksSection();
    toggleRecurrenceOptions();

    // Définir la date de demain par défaut
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    document.getElementById('date_reunion').value = tomorrow.toISOString().split('T')[0];

    updatePreview();
});
</script>
@endpush
@endsection
