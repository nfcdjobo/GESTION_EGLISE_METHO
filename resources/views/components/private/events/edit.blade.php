@extends('layouts.private.main')
@section('title', 'Modifier l\'Événement')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier l'Événement</h1>
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
                        <a href="{{ route('private.events.show', $event) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            {{ Str::limit($event->titre, 20) }}
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

    <form action="{{ route('private.events.update', $event) }}" method="POST" id="eventForm" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

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
                                <input type="text" id="titre" name="titre" value="{{ old('titre', $event->titre) }}" required maxlength="200"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('titre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('titre')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sous_titre" class="block text-sm font-medium text-slate-700 mb-2">Sous-titre</label>
                                <input type="text" id="sous_titre" name="sous_titre" value="{{ old('sous_titre', $event->sous_titre) }}" maxlength="200"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('sous_titre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('sous_titre')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="resume_court" class="block text-sm font-medium text-slate-700 mb-2">Résumé court</label>
                            <textarea id="resume_court" name="resume_court" rows="2" maxlength="500"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('resume_court') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('resume_court', $event->resume_court) }}</textarea>
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
                                    <option value="conference" {{ old('type_evenement', $event->type_evenement) == 'conference' ? 'selected' : '' }}>Conférence</option>
                                    <option value="seminaire" {{ old('type_evenement', $event->type_evenement) == 'seminaire' ? 'selected' : '' }}>Séminaire</option>
                                    <option value="atelier" {{ old('type_evenement', $event->type_evenement) == 'atelier' ? 'selected' : '' }}>Atelier</option>
                                    <option value="camps" {{ old('type_evenement', $event->type_evenement) == 'camps' ? 'selected' : '' }}>Camps</option>
                                    <option value="formation" {{ old('type_evenement', $event->type_evenement) == 'formation' ? 'selected' : '' }}>Formation</option>
                                    <option value="celebration" {{ old('type_evenement', $event->type_evenement) == 'celebration' ? 'selected' : '' }}>Célébration</option>
                                    <option value="concert" {{ old('type_evenement', $event->type_evenement) == 'concert' ? 'selected' : '' }}>Concert</option>
                                    <option value="retraite" {{ old('type_evenement', $event->type_evenement) == 'retraite' ? 'selected' : '' }}>Retraite</option>
                                    <option value="autre" {{ old('type_evenement', $event->type_evenement) == 'autre' ? 'selected' : '' }}>Autre</option>
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
                                    <option value="spirituel" {{ old('categorie', $event->categorie) == 'spirituel' ? 'selected' : '' }}>Spirituel</option>
                                    <option value="educatif" {{ old('categorie', $event->categorie) == 'educatif' ? 'selected' : '' }}>Éducatif</option>
                                    <option value="social" {{ old('categorie', $event->categorie) == 'social' ? 'selected' : '' }}>Social</option>
                                    <option value="culturel" {{ old('categorie', $event->categorie) == 'culturel' ? 'selected' : '' }}>Culturel</option>
                                    <option value="caritatif" {{ old('categorie', $event->categorie) == 'caritatif' ? 'selected' : '' }}>Caritatif</option>
                                    <option value="formation" {{ old('categorie', $event->categorie) == 'formation' ? 'selected' : '' }}>Formation</option>
                                </select>
                                @error('categorie')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="priorite" class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                                <select id="priorite" name="priorite"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="faible" {{ old('priorite', $event->priorite) == 'faible' ? 'selected' : '' }}>Faible</option>
                                    <option value="normale" {{ old('priorite', $event->priorite) == 'normale' ? 'selected' : '' }}>Normale</option>
                                    <option value="haute" {{ old('priorite', $event->priorite) == 'haute' ? 'selected' : '' }}>Haute</option>
                                    <option value="urgente" {{ old('priorite', $event->priorite) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                                </select>
                            </div>

                            <div>
                                <label for="audience_cible" class="block text-sm font-medium text-slate-700 mb-2">Audience ciblée</label>
                                <select id="audience_cible" name="audience_cible"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="tous" {{ old('audience_cible', $event->audience_cible) == 'tous' ? 'selected' : '' }}>Tous</option>
                                    <option value="membres" {{ old('audience_cible', $event->audience_cible) == 'membres' ? 'selected' : '' }}>Membres</option>
                                    <option value="jeunes" {{ old('audience_cible', $event->audience_cible) == 'jeunes' ? 'selected' : '' }}>Jeunes</option>
                                    <option value="adultes" {{ old('audience_cible', $event->audience_cible) == 'adultes' ? 'selected' : '' }}>Adultes</option>
                                    <option value="familles" {{ old('audience_cible', $event->audience_cible) == 'familles' ? 'selected' : '' }}>Familles</option>
                                    <option value="public_externe" {{ old('audience_cible', $event->audience_cible) == 'public_externe' ? 'selected' : '' }}>Public externe</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aperçu et options -->
            <div class="space-y-6">
                <!-- Statut actuel -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info text-blue-600 mr-2"></i>
                            Statut Actuel
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium
                                @if($event->statut == 'planifie') bg-blue-100 text-blue-800
                                @elseif($event->statut == 'en_promotion') bg-yellow-100 text-yellow-800
                                @elseif($event->statut == 'ouvert_inscription') bg-green-100 text-green-800
                                @elseif($event->statut == 'complet') bg-orange-100 text-orange-800
                                @elseif($event->statut == 'en_cours') bg-purple-100 text-purple-800
                                @elseif($event->statut == 'termine') bg-gray-100 text-gray-800
                                @elseif($event->statut == 'annule') bg-red-100 text-red-800
                                @else bg-slate-100 text-slate-800
                                @endif">
                                @switch($event->statut)
                                    @case('planifie') <i class="fas fa-calendar mr-2"></i> Planifié @break
                                    @case('en_promotion') <i class="fas fa-bullhorn mr-2"></i> En promotion @break
                                    @case('ouvert_inscription') <i class="fas fa-user-plus mr-2"></i> Inscriptions ouvertes @break
                                    @case('complet') <i class="fas fa-users mr-2"></i> Complet @break
                                    @case('en_cours') <i class="fas fa-play mr-2"></i> En cours @break
                                    @case('termine') <i class="fas fa-check mr-2"></i> Terminé @break
                                    @case('annule') <i class="fas fa-times mr-2"></i> Annulé @break
                                    @default <i class="fas fa-edit mr-2"></i> Brouillon @break
                                @endswitch
                            </span>
                        </div>

                        @if($event->inscription_requise)
                            <div class="mt-4 text-center">
                                <div class="text-2xl font-bold text-slate-800">{{ $event->nombre_inscrits }}</div>
                                <div class="text-sm text-slate-600">
                                    @if($event->capacite_totale)
                                        sur {{ $event->capacite_totale }} places
                                    @else
                                        inscrits
                                    @endif
                                </div>
                            </div>
                        @endif
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
                            <input type="checkbox" id="ouvert_public" name="ouvert_public" value="1" {{ old('ouvert_public', $event->ouvert_public) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="ouvert_public" class="ml-2 text-sm font-medium text-slate-700">
                                Ouvert au public
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="inscription_requise" name="inscription_requise" value="1" {{ old('inscription_requise', $event->inscription_requise) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="inscription_requise" class="ml-2 text-sm font-medium text-slate-700">
                                Inscription requise
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="inscription_payante" name="inscription_payante" value="1" {{ old('inscription_payante', $event->inscription_payante) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="inscription_payante" class="ml-2 text-sm font-medium text-slate-700">
                                Inscription payante
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="diffusion_en_ligne" name="diffusion_en_ligne" value="1" {{ old('diffusion_en_ligne', $event->diffusion_en_ligne) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="diffusion_en_ligne" class="ml-2 text-sm font-medium text-slate-700">
                                Diffusion en ligne
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" id="publication_site_web" name="publication_site_web" value="1" {{ old('publication_site_web', $event->publication_site_web) ? 'checked' : '' }}
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                            <label for="publication_site_web" class="ml-2 text-sm font-medium text-slate-700">
                                Publier sur le site web
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
                        <input type="date" id="date_debut" name="date_debut" value="{{ old('date_debut', $event->date_debut ? $event->date_debut->format('Y-m-d') : '') }}" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_debut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('date_debut')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_debut" class="block text-sm font-medium text-slate-700 mb-2">
                            Heure de début <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="heure_debut" name="heure_debut" value="{{ old('heure_debut', $event->heure_debut ? $event->heure_debut->format('H:i') : '') }}" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_debut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('heure_debut')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-slate-700 mb-2">Date de fin</label>
                        <input type="date" id="date_fin" name="date_fin" value="{{ old('date_fin', $event->date_fin ? $event->date_fin->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_fin') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('date_fin')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heure_fin" class="block text-sm font-medium text-slate-700 mb-2">Heure de fin</label>
                        <input type="time" id="heure_fin" name="heure_fin" value="{{ old('heure_fin', $event->heure_fin ? $event->heure_fin->format('H:i') : '') }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_fin') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('heure_fin')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
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
                        <input type="text" id="lieu_nom" name="lieu_nom" value="{{ old('lieu_nom', $event->lieu_nom) }}" required maxlength="200"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lieu_nom') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('lieu_nom')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lieu_ville" class="block text-sm font-medium text-slate-700 mb-2">Ville</label>
                        <input type="text" id="lieu_ville" name="lieu_ville" value="{{ old('lieu_ville', $event->lieu_ville) }}" maxlength="100"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lieu_ville') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('lieu_ville')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="lieu_adresse" class="block text-sm font-medium text-slate-700 mb-2">Adresse complète</label>
                    <textarea id="lieu_adresse" name="lieu_adresse" rows="3"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('lieu_adresse') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">{{ old('lieu_adresse', $event->lieu_adresse) }}</textarea>
                    @error('lieu_adresse')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Capacité et inscriptions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300" id="inscription-section" style="{{ $event->inscription_requise ? 'display: block;' : 'display: none;' }}">
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
                        <input type="number" id="capacite_totale" name="capacite_totale" value="{{ old('capacite_totale', $event->capacite_totale) }}" min="1"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('capacite_totale') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('capacite_totale')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="prix-section" style="{{ $event->inscription_payante ? 'display: block;' : 'display: none;' }}">
                        <label for="prix_inscription" class="block text-sm font-medium text-slate-700 mb-2">Prix d'inscription</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500">FCFA</span>
                            <input type="number" id="prix_inscription" name="prix_inscription" value="{{ old('prix_inscription', $event->prix_inscription) }}" min="0" step="0.01"
                                class="w-full pl-16 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('prix_inscription') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        </div>
                        @error('prix_inscription')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Options</label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="liste_attente" name="liste_attente" value="1" {{ old('liste_attente', $event->liste_attente) ? 'checked' : '' }}
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
                        <label for="date_ouverture_inscription" class="block text-sm font-medium text-slate-700 mb-2">Ouverture des inscriptions</label>
                        <input type="date" id="date_ouverture_inscription" name="date_ouverture_inscription" value="{{ old('date_ouverture_inscription', $event->date_ouverture_inscription ? $event->date_ouverture_inscription->format('Y-m-d') : '') }}"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_ouverture_inscription') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('date_ouverture_inscription')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date_fermeture_inscription" class="block text-sm font-medium text-slate-700 mb-2">Fermeture des inscriptions</label>
                        <input type="date" id="date_fermeture_inscription" name="date_fermeture_inscription" value="{{ old('date_fermeture_inscription', $event->date_fermeture_inscription ? $event->date_fermeture_inscription->format('Y-m-d') : '') }}"
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
                                <option value="{{ $organisateur->id }}" {{ old('organisateur_principal_id', $event->organisateur_principal_id) == $organisateur->id ? 'selected' : '' }}>
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
                                <option value="{{ $user->id }}" {{ old('coordinateur_id', $event->coordinateur_id) == $user->id ? 'selected' : '' }}>
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
                                <option value="{{ $user->id }}" {{ old('responsable_logistique_id', $event->responsable_logistique_id) == $user->id ? 'selected' : '' }}>
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
                                <option value="{{ $user->id }}" {{ old('responsable_communication_id', $event->responsable_communication_id) == $user->id ? 'selected' : '' }}>
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
                @if($event->image_principale)
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Image principale actuelle</label>
                        <div class="w-32 h-24 bg-gray-200 rounded-lg overflow-hidden mb-2">
                            <img src="{{ $event->image_principale }}" alt="Image actuelle" class="w-full h-full object-cover">
                        </div>
                        <p class="text-sm text-slate-500">Téléchargez une nouvelle image pour remplacer celle-ci</p>
                    </div>
                @endif

                <div>
                    <label for="image_principale" class="block text-sm font-medium text-slate-700 mb-2">{{ $event->image_principale ? 'Nouvelle image principale' : 'Image principale' }}</label>
                    <input type="file" id="image_principale" name="image_principale" accept="image/*"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('image_principale') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    @error('image_principale')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-slate-500">JPEG, JPG, PNG, WebP - Max 2Mo</p>
                </div>

                <div>
                    <label for="video_presentation" class="block text-sm font-medium text-slate-700 mb-2">Vidéo de présentation</label>
                    <input type="url" id="video_presentation" name="video_presentation" value="{{ old('video_presentation', $event->video_presentation) }}" placeholder="https://youtube.com/watch?v=..."
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('video_presentation') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                    @error('video_presentation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="diffusion-section" style="{{ $event->diffusion_en_ligne ? 'display: block;' : 'display: none;' }}">
                    <label for="lien_diffusion" class="block text-sm font-medium text-slate-700 mb-2">Lien de diffusion</label>
                    <input type="url" id="lien_diffusion" name="lien_diffusion" value="{{ old('lien_diffusion', $event->lien_diffusion) }}" placeholder="https://meet.google.com/..."
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
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                    </button>
                    <a href="{{ route('private.events.show', $event) }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Gestion des options conditionnelles
document.getElementById('inscription_requise').addEventListener('change', function() {
    const section = document.getElementById('inscription-section');
    if (this.checked) {
        section.style.display = 'block';
    } else {
        section.style.display = 'none';
    }
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
</script>
@endpush
@endsection
