@extends('layouts.private.main')
@section('title', 'Créer un Projet')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Créer un Nouveau Projet</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.projets.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-project-diagram mr-2"></i>
                        Projets
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

    <form action="{{ route('private.projets.store') }}" method="POST" enctype="multipart/form-data" id="projetForm" class="space-y-8">
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
                                <label for="nom_projet" class="block text-sm font-medium text-slate-700 mb-2">
                                    Nom du projet <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nom_projet" name="nom_projet" value="{{ old('nom_projet') }}" required maxlength="200" placeholder="Ex: Construction nouveau sanctuaire"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nom_projet') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('nom_projet')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="code_projet" class="block text-sm font-medium text-slate-700 mb-2">
                                    Code du projet
                                </label>
                                <input type="text" id="code_projet" name="code_projet" value="{{ old('code_projet') }}" maxlength="50" placeholder="Ex: CONST2024001"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('code_projet') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('code_projet')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                            <div class="@error('description') has-error @enderror">
                                <textarea id="description" name="description" rows="4" placeholder="Description détaillée du projet"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('description') }}</textarea>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="objectif" class="block text-sm font-medium text-slate-700 mb-2">Objectifs</label>
                            <div class="@error('objectif') has-error @enderror">
                                <textarea id="objectif" name="objectif" rows="4" placeholder="Objectifs du projet"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('objectif') }}</textarea>
                            </div>
                            @error('objectif')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contexte" class="block text-sm font-medium text-slate-700 mb-2">contextes</label>
                            <div class="@error('contexte') has-error @enderror">
                                <textarea id="contexte" name="contexte" rows="4" placeholder="Contextes du projet"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('contexte') }}</textarea>
                            </div>
                            @error('contexte')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="type_projet" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de projet <span class="text-red-500">*</span>
                                </label>
                                <select id="type_projet" name="type_projet" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_projet') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner le type</option>
                                    @foreach($options['types_projet'] as $key => $label)
                                        <option value="{{ $key }}" {{ old('type_projet') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type_projet')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="categorie" class="block text-sm font-medium text-slate-700 mb-2">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <select id="categorie" name="categorie" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('categorie') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @foreach($options['categories'] as $key => $label)
                                        <option value="{{ $key }}" {{ old('categorie') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('categorie')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="priorite" class="block text-sm font-medium text-slate-700 mb-2">
                                    Priorité
                                </label>
                                <select id="priorite" name="priorite"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('priorite') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @foreach($options['priorites'] as $key => $label)
                                        <option value="{{ $key }}" {{ old('priorite', 'normale') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('priorite')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Budget et financement -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-coins text-green-600 mr-2"></i>
                            Budget et Financement
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="budget_prevu" class="block text-sm font-medium text-slate-700 mb-2">Budget prévu</label>
                                <input type="number" id="budget_prevu" name="budget_prevu" value="{{ old('budget_prevu') }}" min="0" step="0.01" placeholder="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('budget_prevu') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('budget_prevu')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="budget_minimum" class="block text-sm font-medium text-slate-700 mb-2">Budget minimum</label>
                                <input type="number" id="budget_minimum" name="budget_minimum" value="{{ old('budget_minimum') }}" min="0" step="0.01" placeholder="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('budget_minimum') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('budget_minimum')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="devise" class="block text-sm font-medium text-slate-700 mb-2">Devise</label>
                                <select id="devise" name="devise"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('devise') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="XOF" {{ old('devise', 'XOF') == 'XOF' ? 'selected' : '' }}>Franc CFA (XOF)</option>
                                    <option value="EUR" {{ old('devise') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                    <option value="USD" {{ old('devise') == 'USD' ? 'selected' : '' }}>Dollar US (USD)</option>
                                </select>
                                @error('devise')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Planification temporelle -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>
                            Planification Temporelle
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="date_debut" class="block text-sm font-medium text-slate-700 mb-2">Date de début</label>
                                <input type="date" id="date_debut" name="date_debut" value="{{ old('date_debut') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_debut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('date_debut')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date_fin_prevue" class="block text-sm font-medium text-slate-700 mb-2">Date de fin prévue</label>
                                <input type="date" id="date_fin_prevue" name="date_fin_prevue" value="{{ old('date_fin_prevue') }}"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_fin_prevue') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('date_fin_prevue')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="duree_prevue_jours" class="block text-sm font-medium text-slate-700 mb-2">Durée prévue (jours)</label>
                                <input type="number" id="duree_prevue_jours" name="duree_prevue_jours" value="{{ old('duree_prevue_jours') }}" min="1" placeholder="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('duree_prevue_jours') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('duree_prevue_jours')
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
                            <i class="fas fa-users text-amber-600 mr-2"></i>
                            Responsables et Équipe
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="responsable_id" class="block text-sm font-medium text-slate-700 mb-2">Responsable principal</label>
                                <select id="responsable_id" name="responsable_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('responsable_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un responsable</option>
                                    @foreach($options['responsables'] as $responsable)
                                        <option value="{{ $responsable['id'] }}" {{ old('responsable_id') == $responsable['id'] ? 'selected' : '' }}>{{ $responsable['nom_complet'] }}</option>
                                    @endforeach
                                </select>
                                @error('responsable_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="coordinateur_id" class="block text-sm font-medium text-slate-700 mb-2">Coordinateur</label>
                                <select id="coordinateur_id" name="coordinateur_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('coordinateur_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un coordinateur</option>
                                    @foreach($options['responsables'] as $responsable)
                                        <option value="{{ $responsable['id'] }}" {{ old('coordinateur_id') == $responsable['id'] ? 'selected' : '' }}>{{ $responsable['nom_complet'] }}</option>
                                    @endforeach
                                </select>
                                @error('coordinateur_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="chef_projet_id" class="block text-sm font-medium text-slate-700 mb-2">Chef de projet</label>
                                <select id="chef_projet_id" name="chef_projet_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('chef_projet_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un chef de projet</option>
                                    @foreach($options['responsables'] as $responsable)
                                        <option value="{{ $responsable['id'] }}" {{ old('chef_projet_id') == $responsable['id'] ? 'selected' : '' }}>{{ $responsable['nom_complet'] }}</option>
                                    @endforeach
                                </select>
                                @error('chef_projet_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                            Localisation
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="localisation" class="block text-sm font-medium text-slate-700 mb-2">Localisation</label>
                                <input type="text" id="localisation" name="localisation" value="{{ old('localisation') }}" maxlength="200" placeholder="Ex: Site de l'église"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('localisation') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('localisation')
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
                                <label for="region" class="block text-sm font-medium text-slate-700 mb-2">Région</label>
                                <input type="text" id="region" name="region" value="{{ old('region') }}" maxlength="100" placeholder="Ex: District Autonome d'Abidjan"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('region') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('region')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="adresse_complete" class="block text-sm font-medium text-slate-700 mb-2">Adresse complète</label>
                            <div class="@error('adresse_complete') has-error @enderror">
                                <textarea id="adresse_complete" name="adresse_complete" rows="2" placeholder="Adresse complète du lieu du projet"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('adresse_complete') }}</textarea>
                            </div>
                            @error('adresse_complete')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                                <div class="flex items-center">
                                    <input type="checkbox" id="visible_public" name="visible_public" value="1" {{ old('visible_public', false) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="visible_public" class="ml-2 text-sm font-medium text-slate-700">
                                        Visible au public
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="ouvert_aux_dons" name="ouvert_aux_dons" value="1" {{ old('ouvert_aux_dons', true) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="ouvert_aux_dons" class="ml-2 text-sm font-medium text-slate-700">
                                        Ouvert aux dons
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="necessite_approbation" name="necessite_approbation" value="1" {{ old('necessite_approbation', true) ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="necessite_approbation" class="ml-2 text-sm font-medium text-slate-700">
                                        Nécessite une approbation
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="projet_recurrent" name="projet_recurrent" value="1" {{ old('projet_recurrent') ? 'checked' : '' }}
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="projet_recurrent" class="ml-2 text-sm font-medium text-slate-700">
                                        Projet récurrent
                                    </label>
                                </div>
                            </div>

                            <div id="recurrence_section" class="space-y-4 hidden">
                                <div>
                                    <label for="frequence_recurrence" class="block text-sm font-medium text-slate-700 mb-2">Fréquence de récurrence</label>
                                    <select id="frequence_recurrence" name="frequence_recurrence"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        @foreach($options['frequences_recurrence'] as $key => $label)
                                            <option value="{{ $key }}" {{ old('frequence_recurrence') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="site_web" class="block text-sm font-medium text-slate-700 mb-2">Site web du projet</label>
                            <input type="url" id="site_web" name="site_web" value="{{ old('site_web') }}" placeholder="https://..."
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('site_web') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                            @error('site_web')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                            <span class="text-sm font-medium text-slate-700">Nom:</span>
                            <span id="preview-nom" class="text-sm text-slate-900 font-semibold">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Code:</span>
                            <span id="preview-code" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Type:</span>
                            <span id="preview-type" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Budget:</span>
                            <span id="preview-budget" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Priorité:</span>
                            <span id="preview-priorite" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">-</span>
                        </div>
                    </div>
                </div>

                <!-- Guide des types de projets -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info text-green-600 mr-2"></i>
                            Guide des Types
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div><strong>Construction:</strong> Nouveaux bâtiments</div>
                        <div><strong>Rénovation:</strong> Réhabilitation d'infrastructures</div>
                        <div><strong>Social:</strong> Projets d'aide sociale</div>
                        <div><strong>Évangélisation:</strong> Missions d'évangélisation</div>
                        <div><strong>Formation:</strong> Programmes de formation</div>
                        <div><strong>Éducation:</strong> Projets éducatifs</div>
                        <div><strong>Santé:</strong> Initiatives de santé</div>
                    </div>
                </div>

                <!-- Conseils -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
                            Conseils
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm text-slate-600">
                        <p>• Choisissez un nom explicite et un code unique</p>
                        <p>• Définissez clairement vos objectifs</p>
                        <p>• Assignez des responsables compétents</p>
                        <p>• Estimez le budget de manière réaliste</p>
                        <p>• Planifiez les échéances importantes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer le Projet
                    </button>
                    <a href="{{ route('private.projets.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
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
    const nom = document.getElementById('nom_projet').value || '-';
    const code = document.getElementById('code_projet').value || '-';
    const typeSelect = document.getElementById('type_projet');
    const type = typeSelect.options[typeSelect.selectedIndex]?.text || '-';
    const budget = document.getElementById('budget_prevu').value || 0;
    const devise = document.getElementById('devise').value || 'XOF';
    const prioriteSelect = document.getElementById('priorite');
    const priorite = prioriteSelect.options[prioriteSelect.selectedIndex]?.text || '-';

    document.getElementById('preview-nom').textContent = nom;
    document.getElementById('preview-code').textContent = code;
    document.getElementById('preview-type').textContent = type;
    document.getElementById('preview-budget').textContent = budget > 0 ? new Intl.NumberFormat('fr-FR').format(budget) + ' ' + devise : '-';
    document.getElementById('preview-priorite').textContent = priorite;
}

// Gestion de la récurrence
function toggleRecurrenceSection() {
    const recurrentCheckbox = document.getElementById('projet_recurrent');
    const recurrenceSection = document.getElementById('recurrence_section');

    if (recurrentCheckbox.checked) {
        recurrenceSection.classList.remove('hidden');
    } else {
        recurrenceSection.classList.add('hidden');
    }
}

// Calcul automatique de la durée
function calculateDuration() {
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin_prevue').value;

    if (dateDebut && dateFin) {
        const debut = new Date(dateDebut);
        const fin = new Date(dateFin);
        const diff = Math.ceil((fin - debut) / (1000 * 60 * 60 * 24));

        if (diff > 0) {
            document.getElementById('duree_prevue_jours').value = diff;
        }
    }
}

// Événements pour la mise à jour de l'aperçu
['nom_projet', 'code_projet', 'type_projet', 'budget_prevu', 'devise', 'priorite'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    }
});

// Événement pour la récurrence
document.getElementById('projet_recurrent').addEventListener('change', toggleRecurrenceSection);

// Événements pour le calcul de durée
document.getElementById('date_debut').addEventListener('change', calculateDuration);
document.getElementById('date_fin_prevue').addEventListener('change', calculateDuration);

// Validation du formulaire avec synchronisation CKEditor
document.getElementById('projetForm').addEventListener('submit', function(e) {
    // Synchroniser tous les éditeurs CKEditor avant validation
    if (window.CKEditorInstances) {
        Object.values(window.CKEditorInstances).forEach(editor => {
            const element = editor.sourceElement;
            if (element) {
                element.value = editor.getData();
            }
        });
    }

    const nom = document.getElementById('nom_projet').value.trim();
    const code = document.getElementById('code_projet').value.trim();
    const type = document.getElementById('type_projet').value;
    const categorie = document.getElementById('categorie').value;

    if (!nom || !code || !type || !categorie) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }

    // Vérifier que les dates sont cohérentes
    const dateDebut = document.getElementById('date_debut').value;
    const dateFin = document.getElementById('date_fin_prevue').value;

    if (dateDebut && dateFin && new Date(dateDebut) > new Date(dateFin)) {
        e.preventDefault();
        alert('La date de début ne peut pas être postérieure à la date de fin prévue.');
        return false;
    }

    // Vérifier que le budget minimum n'est pas supérieur au budget prévu
    const budgetPrevu = parseFloat(document.getElementById('budget_prevu').value) || 0;
    const budgetMinimum = parseFloat(document.getElementById('budget_minimum').value) || 0;

    if (budgetMinimum > budgetPrevu && budgetPrevu > 0) {
        e.preventDefault();
        alert('Le budget minimum ne peut pas être supérieur au budget prévu.');
        return false;
    }
});

// Génération automatique du code projet
document.getElementById('type_projet').addEventListener('change', function() {
    const type = this.value;
    const codeInput = document.getElementById('code_projet');

    if (type && !codeInput.value) {
        const year = new Date().getFullYear();
        const prefix = type.substring(0, 4).toUpperCase();
        const sequence = '001'; // À adapter selon votre logique
        codeInput.value = prefix + year + sequence;
        updatePreview();
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    toggleRecurrenceSection();
});
</script>
@endpush
@endsection
