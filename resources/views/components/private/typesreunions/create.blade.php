@extends('layouts.private.main')
@section('title', 'Créer un Type de Réunion')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer un Type de Réunion</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.types-reunions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Types de Réunions
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

    <form action="{{ route('private.types-reunions.store') }}" method="POST" id="typeReunionForm" enctype="multipart/form-data" class="space-y-8">
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
                                <label for="nom" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nom du type <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required maxlength="150"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Ex: Culte dominical">
                                @error('nom')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="code" class="block text-sm font-medium text-slate-700 mb-2">
                                    Code unique <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="code" name="code" value="{{ old('code') }}" required maxlength="50"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('code') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Ex: culte-dominical">
                                @error('code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <textarea id="description" name="description" rows="4"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                placeholder="Description détaillée du type de réunion...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="categorie" class="block text-sm font-medium text-slate-700 mb-2">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <select id="categorie" name="categorie" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('categorie') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner une catégorie</option>
                                    @foreach($categories as $key => $label)
                                        <option value="{{ $key }}" {{ old('categorie') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('categorie')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="niveau_acces" class="block text-sm font-medium text-slate-700 mb-2">
                                    Niveau d'accès <span class="text-red-500">*</span>
                                </label>
                                <select id="niveau_acces" name="niveau_acces" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('niveau_acces') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner le niveau</option>
                                    @foreach($niveauxAcces as $key => $label)
                                        <option value="{{ $key }}" {{ old('niveau_acces', 'public') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('niveau_acces')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="icone" class="block text-sm font-medium text-slate-700 mb-2">Icône FontAwesome</label>
                                <input type="text" id="icone" name="icone" value="{{ old('icone') }}" maxlength="100"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('icone') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="calendar-alt">
                                <p class="text-xs text-slate-500 mt-1">Nom de l'icône sans le préfixe "fa-"</p>
                                @error('icone')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="couleur" class="block text-sm font-medium text-slate-700 mb-2">Couleur</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" id="couleur" name="couleur" value="{{ old('couleur', '#3498db') }}"
                                        class="w-12 h-12 border border-slate-300 rounded-xl @error('couleur') border-red-500 @enderror">
                                    <input type="text" id="couleur_text" value="{{ old('couleur', '#3498db') }}"
                                        class="flex-1 px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="#3498db">
                                </div>
                                @error('couleur')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="priorite" class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                                <select id="priorite" name="priorite"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('priorite', 5) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <p class="text-xs text-slate-500 mt-1">1 = Priorité maximale, 10 = Priorité minimale</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configuration temporelle -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-clock text-green-600 mr-2"></i>
                            Configuration Temporelle
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="frequence_type" class="block text-sm font-medium text-slate-700 mb-2">
                                Fréquence type <span class="text-red-500">*</span>
                            </label>
                            <select id="frequence_type" name="frequence_type" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('frequence_type') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">Sélectionner la fréquence</option>
                                @foreach($frequences as $key => $label)
                                    <option value="{{ $key }}" {{ old('frequence_type', 'unique') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('frequence_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="duree_standard" class="block text-sm font-medium text-slate-700 mb-2">Durée standard</label>
                                <input type="time" id="duree_standard" name="duree_standard" value="{{ old('duree_standard') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('duree_standard') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('duree_standard')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="duree_min" class="block text-sm font-medium text-slate-700 mb-2">Durée minimale</label>
                                <input type="time" id="duree_min" name="duree_min" value="{{ old('duree_min') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('duree_min') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('duree_min')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="duree_max" class="block text-sm font-medium text-slate-700 mb-2">Durée maximale</label>
                                <input type="time" id="duree_max" name="duree_max" value="{{ old('duree_max') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('duree_max') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('duree_max')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paramètres de configuration -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-cogs text-purple-600 mr-2"></i>
                            Paramètres de Configuration
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <h3 class="text-sm font-semibold text-slate-700 mb-3">Organisation</h3>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="necessite_preparation" value="1" {{ old('necessite_preparation') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Nécessite une préparation spéciale</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="necessite_inscription" value="1" {{ old('necessite_inscription') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Inscription obligatoire</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="a_limite_participants" value="1" {{ old('a_limite_participants') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" id="checkbox_limite">
                                        <span class="ml-2 text-sm text-slate-700">Nombre de participants limité</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="permet_enfants" value="1" {{ old('permet_enfants', true) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" id="checkbox_enfants">
                                        <span class="ml-2 text-sm text-slate-700">Enfants autorisés</span>
                                    </label>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-sm font-semibold text-slate-700 mb-3">Contenu spirituel</h3>
                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="inclut_louange" value="1" {{ old('inclut_louange') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Inclut un temps de louange</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="inclut_message" value="1" {{ old('inclut_message') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Inclut un message/enseignement</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="inclut_priere" value="1" {{ old('inclut_priere', true) ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Inclut un temps de prière</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="inclut_communion" value="1" {{ old('inclut_communion') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Peut inclure la communion</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="permet_temoignages" value="1" {{ old('permet_temoignages') ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-slate-700">Permet les témoignages</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="section_limite_participants" class="hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="limite_participants" class="block text-sm font-medium text-slate-700 mb-2">Limite de participants</label>
                                    <input type="number" id="limite_participants" name="limite_participants" value="{{ old('limite_participants') }}" min="1"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>

                        <div id="section_age_enfants" class="">
                            <div>
                                <label for="age_minimum" class="block text-sm font-medium text-slate-700 mb-2">Âge minimum</label>
                                <input type="number" id="age_minimum" name="age_minimum" value="{{ old('age_minimum') }}" min="0" max="99"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <p class="text-xs text-slate-500 mt-1">Laissez vide si aucun âge minimum requis</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gestion financière -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-money-bill text-amber-600 mr-2"></i>
                            Gestion Financière
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="collecte_offrandes" value="1" {{ old('collecte_offrandes') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Collecte d'offrandes</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="a_frais_participation" value="1" {{ old('a_frais_participation') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" id="checkbox_frais">
                                    <span class="ml-2 text-sm text-slate-700">Frais de participation</span>
                                </label>
                            </div>

                            <div id="section_frais_participation" class="hidden">
                                <div>
                                    <label for="frais_standard" class="block text-sm font-medium text-slate-700 mb-2">Frais standard (XOF)</label>
                                    <input type="number" id="frais_standard" name="frais_standard" value="{{ old('frais_standard') }}" min="0" step="0.01"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Options d'affichage -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-eye text-cyan-600 mr-2"></i>
                            Options d'Affichage
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <label class="flex items-center">
                                    <input type="checkbox" name="afficher_calendrier_public" value="1" {{ old('afficher_calendrier_public', true) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Afficher sur le calendrier public</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="afficher_site_web" value="1" {{ old('afficher_site_web', true) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Afficher sur le site web</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="actif" value="1" {{ old('actif', true) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-slate-700">Type de réunion actif</span>
                                </label>
                            </div>

                            <div>
                                <label for="ordre_affichage" class="block text-sm font-medium text-slate-700 mb-2">Ordre d'affichage</label>
                                <input type="number" id="ordre_affichage" name="ordre_affichage" value="{{ old('ordre_affichage', 0) }}" min="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <p class="text-xs text-slate-500 mt-1">0 = Première position</p>
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
                        <div class="text-center mb-4">
                            <div id="preview-icon" class="w-16 h-16 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-lg mx-auto mb-2">
                                <i class="fas fa-calendar-alt text-2xl"></i>
                            </div>
                            <h3 id="preview-name" class="font-semibold text-slate-800">Nom du type</h3>
                            <p id="preview-code" class="text-sm text-slate-500">code-type</p>
                        </div>

                        <div class="space-y-2 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Catégorie:</span>
                                <span id="preview-categorie" class="text-slate-600">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Accès:</span>
                                <span id="preview-acces" class="text-slate-600">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Fréquence:</span>
                                <span id="preview-frequence" class="text-slate-600">-</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-700">Durée:</span>
                                <span id="preview-duree" class="text-slate-600">-</span>
                            </div>
                        </div>

                        <div id="preview-badges" class="flex flex-wrap gap-1 mt-4">
                            <!-- Badges dynamiques -->
                        </div>
                    </div>
                </div>

                <!-- Responsable -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-tie text-green-600 mr-2"></i>
                            Responsable
                        </h2>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="responsable_type_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable par défaut</label>
                            <select id="responsable_type_id" name="responsable_type_id"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Aucun responsable défini</option>
                                <!-- Les options seront ajoutées dynamiquement -->
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Responsable par défaut pour ce type de réunion</p>
                        </div>
                    </div>
                </div>

                <!-- Guide des catégories -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info text-blue-600 mr-2"></i>
                            Guide des Catégories
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div><strong>Spirituel:</strong> Cultes, prières, études bibliques</div>
                        <div><strong>Formation:</strong> Formations, séminaires, écoles</div>
                        <div><strong>Social:</strong> Événements communautaires</div>
                        <div><strong>Ministériel:</strong> Réunions des ministères</div>
                        <div><strong>Spécial:</strong> Événements exceptionnels</div>
                    </div>
                </div>

                <!-- Conseils -->
                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 rounded-2xl shadow-lg border border-blue-200 p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-blue-600 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-blue-900">Conseils</h3>
                            <ul class="mt-2 text-xs text-blue-800 space-y-1">
                                <li>• Choisissez un nom clair et descriptif</li>
                                <li>• Le code doit être unique et sans espaces</li>
                                <li>• Configurez les paramètres selon vos besoins</li>
                                <li>• Vous pourrez modifier ces paramètres plus tard</li>
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
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer le Type de Réunion
                    </button>
                    <a href="{{ route('private.types-reunions.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Gestion de l'aperçu en temps réel
function updatePreview() {
    const nom = document.getElementById('nom').value || 'Nom du type';
    const code = document.getElementById('code').value || 'code-type';
    const icone = document.getElementById('icone').value || 'calendar-alt';
    const couleur = document.getElementById('couleur').value || '#3498db';
    const categorieSelect = document.getElementById('categorie');
    const accesSelect = document.getElementById('niveau_acces');
    const frequenceSelect = document.getElementById('frequence_type');
    const dureeStandard = document.getElementById('duree_standard').value;

    // Mise à jour des éléments
    document.getElementById('preview-name').textContent = nom;
    document.getElementById('preview-code').textContent = code;
    document.getElementById('preview-icon').style.backgroundColor = couleur;
    document.getElementById('preview-icon').innerHTML = `<i class="fas fa-${icone} text-2xl"></i>`;

    // Mise à jour des informations
    document.getElementById('preview-categorie').textContent = categorieSelect.options[categorieSelect.selectedIndex]?.text || '-';
    document.getElementById('preview-acces').textContent = accesSelect.options[accesSelect.selectedIndex]?.text || '-';
    document.getElementById('preview-frequence').textContent = frequenceSelect.options[frequenceSelect.selectedIndex]?.text || '-';
    document.getElementById('preview-duree').textContent = dureeStandard || '-';

    // Mise à jour des badges
    updatePreviewBadges();
}

function updatePreviewBadges() {
    const badgesContainer = document.getElementById('preview-badges');
    badgesContainer.innerHTML = '';

    const badges = [
        { checkbox: 'necessite_inscription', text: 'Inscription', color: 'orange' },
        { checkbox: 'inclut_louange', text: 'Louange', color: 'purple' },
        { checkbox: 'inclut_message', text: 'Message', color: 'blue' },
        { checkbox: 'permet_enfants', text: 'Enfants', color: 'green' },
        { checkbox: 'collecte_offrandes', text: 'Offrandes', color: 'yellow' },
    ];

    badges.forEach(badge => {
        const checkbox = document.getElementById(badge.checkbox) || document.querySelector(`input[name="${badge.checkbox}"]`);
        if (checkbox && checkbox.checked) {
            const badgeElement = document.createElement('span');
            badgeElement.className = `inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-${badge.color}-100 text-${badge.color}-800`;
            badgeElement.textContent = badge.text;
            badgesContainer.appendChild(badgeElement);
        }
    });
}

// Synchronisation des champs couleur
function syncColorInputs() {
    const colorPicker = document.getElementById('couleur');
    const colorText = document.getElementById('couleur_text');

    colorPicker.addEventListener('change', function() {
        colorText.value = this.value;
        updatePreview();
    });

    colorText.addEventListener('input', function() {
        if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
            colorPicker.value = this.value;
            updatePreview();
        }
    });
}

// Génération automatique du code
function setupCodeGeneration() {
    const nomInput = document.getElementById('nom');
    const codeInput = document.getElementById('code');

    nomInput.addEventListener('input', function() {
        if (!codeInput.dataset.manual) {
            const code = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s]/g, '')
                .replace(/\s+/g, '-')
                .substring(0, 50);
            codeInput.value = code;
        }
        updatePreview();
    });

    codeInput.addEventListener('input', function() {
        this.dataset.manual = 'true';
        updatePreview();
    });
}

// Gestion des sections conditionnelles
function setupConditionalSections() {
    // Limite de participants
    const limitCheckbox = document.getElementById('checkbox_limite');
    const limitSection = document.getElementById('section_limite_participants');

    if (limitCheckbox && limitSection) {
        limitCheckbox.addEventListener('change', function() {
            limitSection.classList.toggle('hidden', !this.checked);
            document.getElementById('limite_participants').required = this.checked;
        });
    }

    // Âge minimum pour enfants
    const enfantsCheckbox = document.getElementById('checkbox_enfants');
    const ageSection = document.getElementById('section_age_enfants');

    if (enfantsCheckbox && ageSection) {
        enfantsCheckbox.addEventListener('change', function() {
            ageSection.classList.toggle('hidden', !this.checked);
        });
    }

    // Frais de participation
    const fraisCheckbox = document.getElementById('checkbox_frais');
    const fraisSection = document.getElementById('section_frais_participation');

    if (fraisCheckbox && fraisSection) {
        fraisCheckbox.addEventListener('change', function() {
            fraisSection.classList.toggle('hidden', !this.checked);
            document.getElementById('frais_standard').required = this.checked;
        });
    }
}

// Validation du formulaire
function setupFormValidation() {
    const form = document.getElementById('typeReunionForm');

    form.addEventListener('submit', function(e) {
        const nom = document.getElementById('nom').value.trim();
        const code = document.getElementById('code').value.trim();
        const categorie = document.getElementById('categorie').value;
        const niveauAcces = document.getElementById('niveau_acces').value;
        const frequence = document.getElementById('frequence_type').value;

        if (!nom || !code || !categorie || !niveauAcces || !frequence) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
            return false;
        }

        // Validation du code
        if (!/^[a-z0-9\-]+$/.test(code)) {
            e.preventDefault();
            alert('Le code ne peut contenir que des lettres minuscules, des chiffres et des tirets.');
            return false;
        }

        // Validation de la couleur
        const couleur = document.getElementById('couleur').value;
        if (!/^#[0-9A-Fa-f]{6}$/.test(couleur)) {
            e.preventDefault();
            alert('La couleur doit être au format hexadécimal (#123456).');
            return false;
        }
    });
}

// Événements pour la mise à jour de l'aperçu
function setupPreviewEvents() {
    const elementsToWatch = [
        'nom', 'code', 'icone', 'couleur', 'categorie', 'niveau_acces',
        'frequence_type', 'duree_standard'
    ];

    elementsToWatch.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        }
    });

    // Checkboxes
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updatePreview);
    });
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    syncColorInputs();
    setupCodeGeneration();
    setupConditionalSections();
    setupFormValidation();
    setupPreviewEvents();
    updatePreview();
});
</script>
@endpush
@endsection
