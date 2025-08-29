@extends('layouts.private.main')
@section('title', 'Modifier ' . $multimedia->titre)

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Modifier le Média</h1>
                    <p class="text-slate-500 mt-1">{{ $multimedia->titre }}</p>
                    <div class="flex items-center space-x-4 mt-2 text-sm text-slate-500">
                        <span class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            Créé le {{ $multimedia->created_at->format('d/m/Y') }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-user mr-1"></i>
                            {{ $multimedia->uploadedBy->nom. ' '.$multimedia->uploadedBy->prenom ?? 'Inconnu' }}
                        </span>
                        <span class="flex items-center capitalize">
                            <i class="fas fa-tag mr-1"></i>
                            {{ $multimedia->categorie_label }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('private.multimedia.show', $multimedia) }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i> Voir
                    </a>
                    <a href="{{ route('private.multimedia.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Aperçu du fichier actuel -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                <i class="fas fa-file-image text-blue-600 mr-2"></i>
                Fichier Actuel
            </h2>
        </div>
        <div class="p-6">
            <div class="bg-slate-50 rounded-xl p-6">
                <div class="flex items-center space-x-6">
                    <!-- Aperçu -->
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl overflow-hidden">
                            @if($multimedia->est_image && $multimedia->url_miniature)
                                <img src="{{ $multimedia->url_miniature }}" alt="{{ $multimedia->titre }}" class="w-full h-full object-cover">
                            @elseif($multimedia->est_image && $multimedia->url_complete)
                                <img src="{{ $multimedia->url_complete }}" alt="{{ $multimedia->titre }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    @if($multimedia->type_media == 'video')
                                        <i class="fas fa-video text-2xl text-red-500"></i>
                                    @elseif($multimedia->type_media == 'audio')
                                        <i class="fas fa-music text-2xl text-purple-500"></i>
                                    @elseif($multimedia->type_media == 'document')
                                        <i class="fas fa-file-alt text-2xl text-blue-500"></i>
                                    @else
                                        <i class="fas fa-file text-2xl text-slate-400"></i>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informations du fichier -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-slate-900 mb-2">{{ $multimedia->nom_fichier_original }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-slate-500">Type</span>
                                <p class="font-medium text-slate-900">{{ $multimedia->type_media_label }}</p>
                            </div>
                            <div>
                                <span class="text-slate-500">Taille</span>
                                <p class="font-medium text-slate-900">{{ $multimedia->taille_formatee }}</p>
                            </div>
                            @if($multimedia->dimensions_formatee)
                                <div>
                                    <span class="text-slate-500">Dimensions</span>
                                    <p class="font-medium text-slate-900">{{ $multimedia->dimensions_formatee }}</p>
                                </div>
                            @endif
                            @if($multimedia->duree_formatee)
                                <div>
                                    <span class="text-slate-500">Durée</span>
                                    <p class="font-medium text-slate-900">{{ $multimedia->duree_formatee }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action de téléchargement -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('private.multimedia.download', $multimedia) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-download mr-2"></i> Télécharger
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire d'édition -->
    <form action="{{ route('private.multimedia.update', $multimedia) }}" method="POST" id="editForm" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Section: Informations de base -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-edit text-blue-600 mr-2"></i>
                    Informations de Base
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Titre -->
                    <div>
                        <label for="titre" class="block text-sm font-medium text-slate-700 mb-2">
                            Titre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="titre" id="titre" value="{{ old('titre', $multimedia->titre) }}" required
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Titre du média">
                        @error('titre')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label for="categorie" class="block text-sm font-medium text-slate-700 mb-2">
                            Catégorie <span class="text-red-500">*</span>
                        </label>
                        <select name="categorie" id="categorie" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $key => $label)
                                <option value="{{ $key }}" {{ old('categorie', $multimedia->categorie) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('categorie')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                              placeholder="Description détaillée du média">{{ old('description', $multimedia->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Légende -->
                <div>
                    <label for="legende" class="block text-sm font-medium text-slate-700 mb-2">Légende</label>
                    <textarea name="legende" id="legende" rows="2"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                              placeholder="Légende courte pour l'affichage">{{ old('legende', $multimedia->legende) }}</textarea>
                    @error('legende')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


            </div>
        </div>

        <!-- Section: Association à un événement -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-calendar text-green-600 mr-2"></i>
                    Association à un Événement
                    <span class="text-sm font-normal text-red-500 ml-2">(au moins un requis)</span>
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Culte -->
                    <div>
                        <label for="culte_id" class="block text-sm font-medium text-slate-700 mb-2">Culte</label>
                        <select name="culte_id" id="culte_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Aucun culte sélectionné</option>
                            @foreach($cultes as $culte)
                                <option value="{{ $culte->id }}" {{ old('culte_id', $multimedia->culte_id) == $culte->id ? 'selected' : '' }}>
                                    {{ $culte->titre }} - {{ \Carbon\Carbon::parse($culte->date_culte)->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('culte_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Événement -->
                    <div>
                        <label for="event_id" class="block text-sm font-medium text-slate-700 mb-2">Événement</label>
                        <select name="event_id" id="event_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Aucun événement sélectionné</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id', $multimedia->event_id) == $event->id ? 'selected' : '' }}>
                                    {{ $event->titre }} - {{ \Carbon\Carbon::parse($event->date_debut)->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('event_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Intervention -->
                    <div>
                        <label for="intervention_id" class="block text-sm font-medium text-slate-700 mb-2">Intervention</label>
                        <select name="intervention_id" id="intervention_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Aucune intervention sélectionnée</option>
                            @foreach($interventions as $intervention)
                                <option value="{{ $intervention->id }}" {{ old('intervention_id', $multimedia->intervention_id) == $intervention->id ? 'selected' : '' }}>
                                    {{ $intervention->titre }}
                                    @if($intervention->culte)
                                        ({{ $intervention->culte->titre }})
                                    @elseif($intervention->reunion)
                                        ({{ $intervention->reunion->titre }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        @error('intervention_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Réunion -->
                    <div>
                        <label for="reunion_id" class="block text-sm font-medium text-slate-700 mb-2">Réunion</label>
                        <select name="reunion_id" id="reunion_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Aucune réunion sélectionnée</option>
                            @foreach($reunions as $reunion)
                                <option value="{{ $reunion->id }}" {{ old('reunion_id', $multimedia->reunion_id) == $reunion->id ? 'selected' : '' }}>
                                    {{ $reunion->titre }} - {{ \Carbon\Carbon::parse($reunion->date_reunion)->format('d/m/Y') }}
                                </option>
                            @endforeach
                        </select>
                        @error('reunion_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @error('evenement')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Section: Métadonnées de capture -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-camera text-purple-600 mr-2"></i>
                    Métadonnées de Capture
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Date de prise -->
                    <div>
                        <label for="date_prise" class="block text-sm font-medium text-slate-700 mb-2">Date de prise</label>
                        <input type="datetime-local" name="date_prise" id="date_prise"
                               value="{{ old('date_prise', $multimedia->date_prise ? $multimedia->date_prise->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @error('date_prise')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Lieu de prise -->
                    <div>
                        <label for="lieu_prise" class="block text-sm font-medium text-slate-700 mb-2">Lieu de prise</label>
                        <input type="text" name="lieu_prise" id="lieu_prise" value="{{ old('lieu_prise', $multimedia->lieu_prise) }}"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Lieu où le média a été capturé">
                        @error('lieu_prise')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Photographe -->
                    <div>
                        <label for="photographe" class="block text-sm font-medium text-slate-700 mb-2">Photographe/Créateur</label>
                        <input type="text" name="photographe" id="photographe" value="{{ old('photographe', $multimedia->photographe) }}"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Nom du photographe ou créateur">
                        @error('photographe')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Appareil -->
                    <div>
                        <label for="appareil" class="block text-sm font-medium text-slate-700 mb-2">Appareil utilisé</label>
                        <input type="text" name="appareil" id="appareil" value="{{ old('appareil', $multimedia->appareil) }}"
                               class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Modèle d'appareil photo/caméra">
                        @error('appareil')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Qualité -->
                <div>
                    <label for="qualite" class="block text-sm font-medium text-slate-700 mb-2">Niveau de qualité</label>
                    <select name="qualite" id="qualite"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @foreach($qualites as $key => $label)
                            <option value="{{ $key }}" {{ old('qualite', $multimedia->qualite) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('qualite')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section: Permissions et accès -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-shield-alt text-indigo-600 mr-2"></i>
                    Permissions et Accès
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <!-- Niveau d'accès -->
                <div>
                    <label for="niveau_acces" class="block text-sm font-medium text-slate-700 mb-2">
                        Niveau d'accès <span class="text-red-500">*</span>
                    </label>
                    <select name="niveau_acces" id="niveau_acces" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        @foreach($niveaux_acces as $key => $label)
                            <option value="{{ $key }}" {{ old('niveau_acces', $multimedia->niveau_acces) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('niveau_acces')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Options d'usage -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <h3 class="font-medium text-slate-900">Autorisations d'usage</h3>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="hidden" name="usage_public" value="0">
                                <input type="checkbox" name="usage_public" value="1" {{ old('usage_public', $multimedia->usage_public) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-slate-700">Usage public autorisé</span>
                            </label>
                            <label class="flex items-center">
                                <input type="hidden" name="usage_site_web" value="0">
                                <input type="checkbox" name="usage_site_web" value="1" {{ old('usage_site_web', $multimedia->usage_site_web) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-slate-700">Publication sur le site web</span>
                            </label>
                            <label class="flex items-center">
                                <input type="hidden" name="usage_reseaux_sociaux" value="0">
                                <input type="checkbox" name="usage_reseaux_sociaux" value="1" {{ old('usage_reseaux_sociaux', $multimedia->usage_reseaux_sociaux) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-slate-700">Partage sur réseaux sociaux</span>
                            </label>
                            <label class="flex items-center">
                                <input type="hidden" name="usage_commercial" value="0">
                                <input type="checkbox" name="usage_commercial" value="1" {{ old('usage_commercial', $multimedia->usage_commercial) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-3 text-sm text-slate-700">Usage commercial autorisé</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="font-medium text-slate-900">Options spéciales</h3>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="hidden" name="contenu_sensible" value="0">
                                <input type="checkbox" name="contenu_sensible" value="1" {{ old('contenu_sensible', $multimedia->contenu_sensible) ? 'checked' : '' }}
                                       class="w-4 h-4 text-orange-600 bg-gray-100 border-gray-300 rounded focus:ring-orange-500">
                                <span class="ml-3 text-sm text-slate-700">Contenu sensible</span>
                            </label>
                            @can('feature_media')
                                <label class="flex items-center">
                                    <input type="hidden" name="est_featured" value="0">
                                    <input type="checkbox" name="est_featured" value="1" {{ old('est_featured', $multimedia->est_featured) ? 'checked' : '' }}
                                           class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500">
                                    <span class="ml-3 text-sm text-slate-700">Mettre à la une</span>
                                </label>
                            @endcan
                            @can('moderate_media')
                                <label class="flex items-center">
                                    <input type="hidden" name="est_visible" value="0">
                                    <input type="checkbox" name="est_visible" value="1" {{ old('est_visible', $multimedia->est_visible) ? 'checked' : '' }}
                                           class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500">
                                    <span class="ml-3 text-sm text-slate-700">Visible</span>
                                </label>
                            @endcan
                        </div>
                    </div>
                </div>

                <!-- Restrictions d'usage -->
                <div>
                    <label for="restrictions_usage" class="block text-sm font-medium text-slate-700 mb-2">Restrictions d'usage spécifiques</label>
                    <textarea name="restrictions_usage" id="restrictions_usage" rows="3"
                              class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                              placeholder="Précisez les restrictions particulières d'usage de ce média">{{ old('restrictions_usage', $multimedia->restrictions_usage) }}</textarea>
                    @error('restrictions_usage')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Avertissement pour contenu sensible -->
                <div id="warningSection" class="{{ $multimedia->contenu_sensible ? '' : 'hidden' }}">
                    <label for="avertissement" class="block text-sm font-medium text-slate-700 mb-2">Avertissement pour contenu sensible</label>
                    <textarea name="avertissement" id="avertissement" rows="2"
                              class="w-full px-4 py-3 border border-orange-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                              placeholder="Décrivez pourquoi ce contenu est sensible">{{ old('avertissement', $multimedia->avertissement) }}</textarea>
                </div>
            </div>
        </div>

       

        @can('moderate_media')
            @if($multimedia->statut_moderation != 'approuve')
                <!-- Section: Statut de modération (pour les modérateurs) -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-orange/20">
                    <div class="p-6 border-b border-orange-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-gavel text-orange-600 mr-2"></i>
                            Modération
                            <span class="ml-3 px-3 py-1 bg-orange-100 text-orange-800 text-sm rounded-full">
                                {{ $multimedia->statut_moderation_label }}
                            </span>
                        </h2>
                    </div>
                    <div class="p-6 bg-orange-50">
                        <div class="flex items-center space-x-4">
                            <div class="flex-1">
                                @if($multimedia->statut_moderation == 'rejete' && $multimedia->commentaire_moderation)
                                    <div class="mb-4 p-4 bg-red-100 border border-red-200 rounded-lg">
                                        <h4 class="font-medium text-red-900 mb-2">Motif de rejet précédent :</h4>
                                        <p class="text-sm text-red-800">{{ $multimedia->commentaire_moderation }}</p>
                                        @if($multimedia->moderator)
                                            <p class="text-xs text-red-700 mt-2">
                                                Par {{ $multimedia->moderator->nom. ' '.$multimedia->moderator->prenom }} le {{ $multimedia->modere_le->format('d/m/Y à H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                <p class="text-orange-800 text-sm">
                                    Ce média nécessite une modération. Les modifications seront soumises à approbation.
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="quickApprove()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i> Approuver
                                </button>
                                <button type="button" onclick="quickReject()" class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                    <i class="fas fa-times mr-2"></i> Rejeter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endcan

        <!-- Actions -->
        <div class="flex items-center justify-between gap-4 pt-6">
            <a href="{{ route('private.multimedia.show', $multimedia) }}" class="inline-flex items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Annuler
            </a>
            <div class="flex items-center gap-4">
                <button type="submit" name="action" value="save" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Inclure les ressources CKEditor --}}
@include('partials.ckeditor-resources')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contenuSensibleCheckbox = document.querySelector('input[name="contenu_sensible"]');
    const warningSection = document.getElementById('warningSection');





    // Gestion du contenu sensible
    contenuSensibleCheckbox.addEventListener('change', function() {
        if (this.checked) {
            warningSection.classList.remove('hidden');
        } else {
            warningSection.classList.add('hidden');
        }
    });

});

// Validation avant soumission
document.getElementById('editForm').addEventListener('submit', function(e) {
    // Vérifier qu'au moins un événement est sélectionné
    const culte = document.getElementById('culte_id').value;
    const event = document.getElementById('event_id').value;
    const intervention = document.getElementById('intervention_id').value;
    const reunion = document.getElementById('reunion_id').value;

    if (!culte && !event && !intervention && !reunion) {
        e.preventDefault();
        alert('Veuillez associer ce média à au moins un événement (culte, événement, intervention ou réunion).');
        return false;
    }

    // Vérifier le contenu sensible
    const contenuSensible = document.querySelector('input[name="contenu_sensible"]').checked;
    const avertissement = document.getElementById('avertissement').value.trim();

    if (contenuSensible && !avertissement) {
        e.preventDefault();
        alert('Veuillez fournir un avertissement pour le contenu sensible.');
        document.getElementById('avertissement').focus();
        return false;
    }

});

@can('moderate_media')
// Actions rapides de modération
function quickApprove() {
    if (confirm('Approuver ce média après sauvegarde ?')) {
        // Ajouter un champ caché pour indiquer l'approbation
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'quick_action';
        input.value = 'approve';
        document.getElementById('editForm').appendChild(input);

        document.getElementById('editForm').submit();
    }
}

function quickReject() {
    const reason = prompt('Motif de rejet :');
    if (reason && reason.trim()) {
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'quick_action';
        actionInput.value = 'reject';
        document.getElementById('editForm').appendChild(actionInput);

        const reasonInput = document.createElement('input');
        reasonInput.type = 'hidden';
        reasonInput.name = 'reject_reason';
        reasonInput.value = reason.trim();
        document.getElementById('editForm').appendChild(reasonInput);

        document.getElementById('editForm').submit();
    }
}
@endcan

// Auto-génération du titre SEO et alt text
document.getElementById('titre').addEventListener('input', function() {
    const titre = this.value;
    const titreSeo = document.getElementById('titre_seo');
    const altText = document.getElementById('alt_text');

    // Ne remplir automatiquement que si les champs sont vides
    if (!titreSeo.value.trim()) {
        titreSeo.value = titre;
    }

    if (!altText.value.trim()) {
        altText.value = titre;
    }
});
</script>

@endsection
