@extends('layouts.private.main')
@section('title', 'Modifier la Réunion')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier la Réunion</h1>
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
                        <a href="{{ route('private.reunions.show', $reunion) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            {{ Str::limit($reunion->titre, 30) }}
                        </a>
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

    <form action="{{ route('private.reunions.update', $reunion) }}" method="POST" enctype="multipart/form-data" id="reunionEditForm" class="space-y-8">
        @csrf
        @method('PUT')

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
                                <input type="text" id="titre" name="titre" value="{{ old('titre', $reunion->titre) }}" required maxlength="200" placeholder="Ex: Réunion du conseil d'administration"
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
                                        <option value="{{ $type->id }}" {{ old('type_reunion_id', $reunion->type_reunion_id) == $type->id ? 'selected' : '' }}>
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
                                <textarea id="description" name="description" rows="3" placeholder="Description de la réunion"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('description', $reunion->description) }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="objectifs" class="block text-sm font-medium text-slate-700 mb-2">Objectifs</label>
                            <div class="@error('objectifs') has-error @enderror">
                                <textarea id="objectifs" name="objectifs" rows="3" placeholder="Objectifs de la réunion"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('objectifs', $reunion->objectifs) }}</textarea>
                            </div>
                            @error('objectifs')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="statut" class="block text-sm font-medium text-slate-700 mb-2">
                                    Statut <span class="text-red-500">*</span>
                                </label>
                                <select id="statut" name="statut" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('statut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="planifiee" {{ old('statut', $reunion->statut) == 'planifiee' ? 'selected' : '' }}>Planifiée</option>
                                    <option value="confirmee" {{ old('statut', $reunion->statut) == 'confirmee' ? 'selected' : '' }}>Confirmée</option>
                                    <option value="planifie" {{ old('statut', $reunion->statut) == 'planifie' ? 'selected' : '' }}>En préparation</option>
                                    <option value="en_cours" {{ old('statut', $reunion->statut) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="terminee" {{ old('statut', $reunion->statut) == 'terminee' ? 'selected' : '' }}>Terminée</option>
                                    <option value="annulee" {{ old('statut', $reunion->statut) == 'annulee' ? 'selected' : '' }}>Annulée</option>
                                    <option value="reportee" {{ old('statut', $reunion->statut) == 'reportee' ? 'selected' : '' }}>Reportée</option>
                                    <option value="suspendue" {{ old('statut', $reunion->statut) == 'suspendue' ? 'selected' : '' }}>Suspendue</option>
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
                                    <option value="faible" {{ old('niveau_priorite', $reunion->niveau_priorite) == 'faible' ? 'selected' : '' }}>Faible</option>
                                    <option value="normale" {{ old('niveau_priorite', $reunion->niveau_priorite) == 'normale' ? 'selected' : '' }}>Normale</option>
                                    <option value="haute" {{ old('niveau_priorite', $reunion->niveau_priorite) == 'haute' ? 'selected' : '' }}>Haute</option>
                                    <option value="urgente" {{ old('niveau_priorite', $reunion->niveau_priorite) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                                    <option value="critique" {{ old('niveau_priorite', $reunion->niveau_priorite) == 'critique' ? 'selected' : '' }}>Critique</option>
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
                                <input type="date" id="date_reunion" name="date_reunion" value="{{ old('date_reunion', $reunion->date_reunion?->format('Y-m-d')) }}" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_reunion') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('date_reunion')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="heure_debut_prevue" class="block text-sm font-medium text-slate-700 mb-2">
                                    Heure de début <span class="text-red-500">*</span>
                                </label>
                                <input type="time" id="heure_debut_prevue" name="heure_debut_prevue" value="{{ old('heure_debut_prevue', $reunion->heure_debut_prevue?->format('H:i')) }}" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_debut_prevue') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('heure_debut_prevue')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="heure_fin_prevue" class="block text-sm font-medium text-slate-700 mb-2">Heure de fin (prévue)</label>
                                <input type="time" id="heure_fin_prevue" name="heure_fin_prevue" value="{{ old('heure_fin_prevue', $reunion->heure_fin_prevue?->format('H:i')) }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_fin_prevue') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('heure_fin_prevue')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        @if($reunion->statut === 'en_cours' || $reunion->statut === 'terminee')
                            <div class="border-t border-slate-200 pt-6">
                                <h3 class="text-lg font-semibold text-slate-800 mb-4">Heures réelles</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="heure_debut_reelle" class="block text-sm font-medium text-slate-700 mb-2">Heure de début réelle</label>
                                        <input type="time" id="heure_debut_reelle" name="heure_debut_reelle" value="{{ old('heure_debut_reelle', $reunion->heure_debut_reelle?->format('H:i')) }}"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_debut_reelle') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('heure_debut_reelle')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="heure_fin_reelle" class="block text-sm font-medium text-slate-700 mb-2">Heure de fin réelle</label>
                                        <input type="time" id="heure_fin_reelle" name="heure_fin_reelle" value="{{ old('heure_fin_reelle', $reunion->heure_fin_reelle?->format('H:i')) }}"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_fin_reelle') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('heure_fin_reelle')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="lieu" class="block text-sm font-medium text-slate-700 mb-2">
                                    Lieu <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="lieu" name="lieu" value="{{ old('lieu', $reunion->lieu) }}" required maxlength="200"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lieu') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('lieu')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="salle" class="block text-sm font-medium text-slate-700 mb-2">Salle spécifique</label>
                                <input type="text" id="salle" name="salle" value="{{ old('salle', $reunion->salle) }}" maxlength="100" placeholder="Ex: Salle de conférence A"
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
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('adresse_complete', $reunion->adresse_complete) }}</textarea>
                            </div>
                            @error('adresse_complete')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="capacite_salle" class="block text-sm font-medium text-slate-700 mb-2">Capacité de la salle</label>
                                <input type="number" id="capacite_salle" name="capacite_salle" value="{{ old('capacite_salle', $reunion->capacite_salle) }}" min="1"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('capacite_salle') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('capacite_salle')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="nombre_places_disponibles" class="block text-sm font-medium text-slate-700 mb-2">Nombre de places disponibles</label>
                                <input type="number" id="nombre_places_disponibles" name="nombre_places_disponibles" value="{{ old('nombre_places_disponibles', $reunion->nombre_places_disponibles) }}" min="1"
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
                                        <option value="{{ $user->id }}" {{ old('organisateur_principal_id', $reunion->organisateur_principal_id) == $user->id ? 'selected' : '' }}>
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
                                        <option value="{{ $user->id }}" {{ old('animateur_id', $reunion->animateur_id) == $user->id ? 'selected' : '' }}>
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
                                        <option value="{{ $user->id }}" {{ old('responsable_technique_id', $reunion->responsable_technique_id) == $user->id ? 'selected' : '' }}>
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
                                        <option value="{{ $user->id }}" {{ old('responsable_accueil_id', $reunion->responsable_accueil_id) == $user->id ? 'selected' : '' }}>
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
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('message_principal', $reunion->message_principal) }}</textarea>
                            </div>
                            @error('message_principal')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="passage_biblique" class="block text-sm font-medium text-slate-700 mb-2">Passage biblique de référence</label>
                            <input type="text" id="passage_biblique" name="passage_biblique" value="{{ old('passage_biblique', $reunion->passage_biblique) }}" maxlength="500" placeholder="Ex: Jean 3:16-17"
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
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('materiel_fourni', $reunion->materiel_fourni) }}</textarea>
                                </div>
                                @error('materiel_fourni')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="materiel_apporter" class="block text-sm font-medium text-slate-700 mb-2">Matériel à apporter</label>
                                <div class="@error('materiel_apporter') has-error @enderror">
                                    <textarea id="materiel_apporter" name="materiel_apporter" rows="3" placeholder="Matériel que les participants doivent apporter"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('materiel_apporter', $reunion->materiel_apporter) }}</textarea>
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
                                    <input type="checkbox" id="diffusion_en_ligne" name="diffusion_en_ligne" value="1" {{ old('diffusion_en_ligne', $reunion->diffusion_en_ligne) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="diffusion_en_ligne" class="ml-2 text-sm font-medium text-slate-700">
                                        Diffusion en ligne
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="enregistrement_autorise" name="enregistrement_autorise" value="1" {{ old('enregistrement_autorise', $reunion->enregistrement_autorise) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="enregistrement_autorise" class="ml-2 text-sm font-medium text-slate-700">
                                        Enregistrement autorisé
                                    </label>
                                </div>

                                <div id="liens_section" class="space-y-4 {{ $reunion->lien_diffusion ? '' : 'hidden' }}">
                                    <div>
                                        <label for="lien_diffusion" class="block text-sm font-medium text-slate-700 mb-2">Lien de diffusion</label>
                                        <input type="url" id="lien_diffusion" name="lien_diffusion" value="{{ old('lien_diffusion', $reunion->lien_diffusion) }}" placeholder="https://..."
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lien_diffusion') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('lien_diffusion')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="lien_enregistrement" class="block text-sm font-medium text-slate-700 mb-2">Lien de l'enregistrement</label>
                                        <input type="url" id="lien_enregistrement" name="lien_enregistrement" value="{{ old('lien_enregistrement', $reunion->lien_enregistrement) }}" placeholder="https://..."
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lien_enregistrement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('lien_enregistrement')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-slate-800">Inscription et préparation</h3>
                                <div>
                                    <label for="limite_inscription" class="block text-sm font-medium text-slate-700 mb-2">Date limite d'inscription</label>
                                    <input type="date" id="limite_inscription" name="limite_inscription" value="{{ old('limite_inscription', $reunion->limite_inscription?->format('Y-m-d')) }}"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('limite_inscription') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('limite_inscription')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="liste_attente_activee" name="liste_attente_activee" value="1" {{ old('liste_attente_activee', $reunion->liste_attente_activee) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="liste_attente_activee" class="ml-2 text-sm font-medium text-slate-700">
                                        Activer la liste d'attente
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="preparation_terminee" name="preparation_terminee" value="1" {{ old('preparation_terminee', $reunion->preparation_terminee) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="preparation_terminee" class="ml-2 text-sm font-medium text-slate-700">
                                        Préparation terminée
                                    </label>
                                </div>

                                <div>
                                    <label for="frais_inscription" class="block text-sm font-medium text-slate-700 mb-2">Frais d'inscription (€)</label>
                                    <input type="number" id="frais_inscription" name="frais_inscription" value="{{ old('frais_inscription', $reunion->frais_inscription) }}" step="0.01" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('frais_inscription') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('frais_inscription')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($reunion->statut === 'terminee')
                    <!-- Évaluation et résultats -->
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-star text-yellow-600 mr-2"></i>
                                Évaluation et Résultats
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <label for="note_globale" class="block text-sm font-medium text-slate-700 mb-2">Note globale (/10)</label>
                                    <input type="number" id="note_globale" name="note_globale" value="{{ old('note_globale', $reunion->note_globale) }}" step="0.1" min="1" max="10"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('note_globale') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('note_globale')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="note_contenu" class="block text-sm font-medium text-slate-700 mb-2">Note contenu (/10)</label>
                                    <input type="number" id="note_contenu" name="note_contenu" value="{{ old('note_contenu', $reunion->note_contenu) }}" step="0.1" min="1" max="10"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('note_contenu') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('note_contenu')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="note_organisation" class="block text-sm font-medium text-slate-700 mb-2">Note organisation (/10)</label>
                                    <input type="number" id="note_organisation" name="note_organisation" value="{{ old('note_organisation', $reunion->note_organisation) }}" step="0.1" min="1" max="10"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('note_organisation') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('note_organisation')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="taux_satisfaction" class="block text-sm font-medium text-slate-700 mb-2">Taux satisfaction (%)</label>
                                    <input type="number" id="taux_satisfaction" name="taux_satisfaction" value="{{ old('taux_satisfaction', $reunion->taux_satisfaction) }}" min="0" max="100"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('taux_satisfaction') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('taux_satisfaction')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="points_positifs" class="block text-sm font-medium text-slate-700 mb-2">Points positifs</label>
                                    <div class="@error('points_positifs') has-error @enderror">
                                        <textarea id="points_positifs" name="points_positifs" rows="3" placeholder="Points positifs relevés"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('points_positifs', $reunion->points_positifs) }}</textarea>
                                    </div>
                                    @error('points_positifs')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="points_amelioration" class="block text-sm font-medium text-slate-700 mb-2">Points d'amélioration</label>
                                    <div class="@error('points_amelioration') has-error @enderror">
                                        <textarea id="points_amelioration" name="points_amelioration" rows="3" placeholder="Points à améliorer"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('points_amelioration', $reunion->points_amelioration) }}</textarea>
                                    </div>
                                    @error('points_amelioration')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <div>
                                    <label for="nombre_decisions" class="block text-sm font-medium text-slate-700 mb-2">Nombre de décisions</label>
                                    <input type="number" id="nombre_decisions" name="nombre_decisions" value="{{ old('nombre_decisions', $reunion->nombre_decisions) }}" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_decisions') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('nombre_decisions')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nombre_recommitments" class="block text-sm font-medium text-slate-700 mb-2">Nombre de re-engagements</label>
                                    <input type="number" id="nombre_recommitments" name="nombre_recommitments" value="{{ old('nombre_recommitments', $reunion->nombre_recommitments) }}" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_recommitments') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('nombre_recommitments')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nombre_guerisons" class="block text-sm font-medium text-slate-700 mb-2">Nombre de guérisons</label>
                                    <input type="number" id="nombre_guerisons" name="nombre_guerisons" value="{{ old('nombre_guerisons', $reunion->nombre_guerisons) }}" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_guerisons') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('nombre_guerisons')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="nombre_participants_reel" class="block text-sm font-medium text-slate-700 mb-2">Participants réels</label>
                                    <input type="number" id="nombre_participants_reel" name="nombre_participants_reel" value="{{ old('nombre_participants_reel', $reunion->nombre_participants_reel) }}" min="0"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_participants_reel') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('nombre_participants_reel')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar - Informations actuelles -->
            <div class="space-y-6">
                <!-- État actuel -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800">État Actuel</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Statut:</span>
                            @php
                                $statutColors = [
                                    'planifiee' => 'bg-blue-100 text-blue-800',
                                    'confirmee' => 'bg-green-100 text-green-800',
                                    'planifie' => 'bg-yellow-100 text-yellow-800',
                                    'en_cours' => 'bg-orange-100 text-orange-800',
                                    'terminee' => 'bg-emerald-100 text-emerald-800',
                                    'annulee' => 'bg-red-100 text-red-800',
                                    'reportee' => 'bg-purple-100 text-purple-800',
                                    'suspendue' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statutColors[$reunion->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($reunion->statut) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Inscrits:</span>
                            <span class="font-semibold text-slate-900">{{ $reunion->nombre_inscrits ?? 0 }}</span>
                        </div>
                        @if($reunion->nombre_participants_reel)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">Présents:</span>
                                <span class="font-semibold text-green-600">{{ $reunion->nombre_participants_reel }}</span>
                            </div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Créée le:</span>
                            <span class="text-sm text-slate-900">{{ $reunion->created_at?->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($reunion->updated_at && $reunion->updated_at != $reunion->created_at)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-slate-600">Modifiée le:</span>
                                <span class="text-sm text-slate-900">{{ $reunion->updated_at?->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Historique des modifications -->
                @if($reunion->modificateur)
                    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800">Historique</h2>
                        </div>
                        <div class="p-6 space-y-3">
                            @if($reunion->createur)
                                <div class="text-sm">
                                    <span class="font-medium text-slate-700">Créée par:</span>
                                    <span class="text-slate-900">{{ $reunion->createur->nom }} {{ $reunion->createur->prenom }}</span>
                                </div>
                            @endif
                            @if($reunion->modificateur)
                                <div class="text-sm">
                                    <span class="font-medium text-slate-700">Modifiée par:</span>
                                    <span class="text-slate-900">{{ $reunion->modificateur->nom }} {{ $reunion->modificateur->prenom }}</span>
                                </div>
                            @endif
                            @if($reunion->validateur)
                                <div class="text-sm">
                                    <span class="font-medium text-slate-700">Validée par:</span>
                                    <span class="text-slate-900">{{ $reunion->validateur->nom }} {{ $reunion->validateur->prenom }}</span>
                                    <div class="text-xs text-slate-500">{{ $reunion->validee_le?->format('d/m/Y H:i') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions rapides -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800">Actions Rapides</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('private.reunions.show', $reunion) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-cyan-600 text-white text-sm font-medium rounded-xl hover:bg-cyan-700 transition-colors">
                            <i class="fas fa-eye mr-2"></i> Voir les détails
                        </a>

                        @if($reunion->peutEtreAnnulee())
                            <button onclick="openAnnulerModal('{{ $reunion->id }}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                <i class="fas fa-times mr-2"></i> Annuler la réunion
                            </button>
                        @endif

                        @if($reunion->peutEtreReportee())
                            <button onclick="openReporterModal('{{ $reunion->id }}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors">
                                <i class="fas fa-calendar-alt mr-2"></i> Reporter la réunion
                            </button>
                        @endif

                        <button onclick="openDuplicateModal('{{ $reunion->id }}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-xl hover:bg-gray-700 transition-colors">
                            <i class="fas fa-copy mr-2"></i> Dupliquer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('private.reunions.show', $reunion) }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <a href="{{ route('private.reunions.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-gray-600 text-white font-medium rounded-xl hover:bg-gray-700 transition-colors">
                        <i class="fas fa-list mr-2"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modals -->
@include('components.private.reunions.modals.annuler', $reunion)
@include('components.private.reunions.modals.reporter', $reunion)
@include('components.private.reunions.modals.duplicate', $reunion)

{{-- Inclure les ressources CKEditor --}}
@include('partials.ckeditor-resources')

@push('scripts')
<script>
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

// Événements pour les options
document.getElementById('diffusion_en_ligne').addEventListener('change', toggleLinksSection);
document.getElementById('enregistrement_autorise').addEventListener('change', toggleLinksSection);

// Validation du formulaire avec synchronisation CKEditor
document.getElementById('reunionEditForm').addEventListener('submit', function(e) {
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

    // Vérifier que l'heure de fin est après l'heure de début
    const heureFin = document.getElementById('heure_fin_prevue').value;
    if (heureFin && heure >= heureFin) {
        e.preventDefault();
        alert('L\'heure de fin doit être postérieure à l\'heure de début.');
        return false;
    }

    // Vérifier les heures réelles si le statut le permet
    const heureDebutReelle = document.getElementById('heure_debut_reelle')?.value;
    const heureFinReelle = document.getElementById('heure_fin_reelle')?.value;
    if (heureDebutReelle && heureFinReelle && heureDebutReelle >= heureFinReelle) {
        e.preventDefault();
        alert('L\'heure de fin réelle doit être postérieure à l\'heure de début réelle.');
        return false;
    }
});

// Modals (repris des autres vues)
function openAnnulerModal(reunionId) {
    // Implementation du modal d'annulation
    console.log('Ouverture modal annulation pour:', reunionId);
}

function openReporterModal(reunionId) {
    // Implementation du modal de report
    console.log('Ouverture modal report pour:', reunionId);
}

function openDuplicateModal(reunionId) {
    // Implementation du modal de duplication
    console.log('Ouverture modal duplication pour:', reunionId);
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    toggleLinksSection();
});
</script>
@endpush
@endsection
