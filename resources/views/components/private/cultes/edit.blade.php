@extends('layouts.private.main')
@section('title', 'Modifier le Culte')

@section('content')
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Modifier le Culte</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.cultes.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-church mr-2"></i>
                            Cultes
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <a href="{{ route('private.cultes.show', $culte) }}"
                                class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">{{ $culte->titre }}</a>
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

        @can('cultes.update')
            <form action="{{ route('private.cultes.update', $culte) }}" method="POST" enctype="multipart/form-data"
                id="culteForm" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Informations générales -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Informations de base -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
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
                                        <input type="text" id="titre" name="titre" value="{{ old('titre', $culte->titre) }}"
                                            required maxlength="200" placeholder="Ex: Culte du dimanche matin"
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
                                                <option value="{{ $programme->id }}" {{ old('programme_id', $culte->programme_id) == $programme->id ? 'selected' : '' }}>
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
                                    <label for="description"
                                        class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                                    <div class="@error('description') has-error @enderror">
                                        <textarea id="description" name="description" rows="3"
                                            placeholder="Description du culte"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('description', $culte->description) }}</textarea>
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
                                                <option value="{{ $key }}" {{ old('type_culte', $culte->type_culte) == $key ? 'selected' : '' }}>{{ $label }}</option>
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
                                                <option value="{{ $key }}" {{ old('categorie', $culte->categorie) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('categorie')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="statut" class="block text-sm font-medium text-slate-700 mb-2">
                                            Statut <span class="text-red-500">*</span>
                                        </label>
                                        <select id="statut" name="statut" required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('statut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @foreach(\App\Models\Culte::STATUT as $key => $label)
                                                <option value="{{ $key }}" {{ old('statut', $culte->statut) == $key ? 'selected' : '' }}>{{ $label }}</option>
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
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
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
                                        <input type="date" id="date_culte" name="date_culte"
                                            value="{{ old('date_culte', $culte->date_culte->format('Y-m-d')) }}" required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_culte') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('date_culte')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="heure_debut" class="block text-sm font-medium text-slate-700 mb-2">
                                            Heure de début <span class="text-red-500">*</span>
                                        </label>
                                        <input type="time" id="heure_debut" name="heure_debut"
                                            value="{{ old('heure_debut', $culte->heure_debut ? \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') : '') }}"
                                            required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_debut') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('heure_debut')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="heure_fin" class="block text-sm font-medium text-slate-700 mb-2">Heure de
                                            fin (prévue)</label>
                                        <input type="time" id="heure_fin" name="heure_fin"
                                            value="{{ old('heure_fin', $culte->heure_fin ? \Carbon\Carbon::parse($culte->heure_fin)->format('H:i') : '') }}"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('heure_fin') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('heure_fin')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                @if($culte->statut === 'termine' && ($culte->heure_debut_reelle || $culte->heure_fin_reelle))
                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-green-50 rounded-xl border border-green-200">
                                        <div>
                                            <label for="heure_debut_reelle"
                                                class="block text-sm font-medium text-slate-700 mb-2">Heure de début réelle</label>
                                            <input type="time" id="heure_debut_reelle" name="heure_debut_reelle"
                                                value="{{ old('heure_debut_reelle', $culte->heure_debut_reelle ? \Carbon\Carbon::parse($culte->heure_debut_reelle)->format('H:i') : '') }}"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        </div>

                                        <div>
                                            <label for="heure_fin_reelle"
                                                class="block text-sm font-medium text-slate-700 mb-2">Heure de fin réelle</label>
                                            <input type="time" id="heure_fin_reelle" name="heure_fin_reelle"
                                                value="{{ old('heure_fin_reelle', $culte->heure_fin_reelle ? \Carbon\Carbon::parse($culte->heure_fin_reelle)->format('H:i') : '') }}"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        </div>
                                    </div>
                                @endif

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="lieu" class="block text-sm font-medium text-slate-700 mb-2">
                                            Lieu <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="lieu" name="lieu" value="{{ old('lieu', $culte->lieu) }}"
                                            required maxlength="200"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lieu') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('lieu')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="capacite_prevue"
                                            class="block text-sm font-medium text-slate-700 mb-2">Capacité prévue</label>
                                        <input type="number" id="capacite_prevue" name="capacite_prevue"
                                            value="{{ old('capacite_prevue', $culte->capacite_prevue) }}" min="1"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('capacite_prevue') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('capacite_prevue')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="adresse_lieu" class="block text-sm font-medium text-slate-700 mb-2">Adresse
                                        complète (si lieu externe)</label>
                                    <div class="@error('adresse_lieu') has-error @enderror">
                                        <textarea id="adresse_lieu" name="adresse_lieu" rows="2"
                                            placeholder="Adresse complète du lieu si différent de l'église"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('adresse_lieu', $culte->adresse_lieu) }}</textarea>
                                    </div>
                                    @error('adresse_lieu')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Officiants et Responsables -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                        <i class="fas fa-users text-purple-600 mr-2"></i>
                                        Officiants et Responsables
                                    </h2>
                                    <button type="button" id="addOfficiantBtn"
                                        class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-plus mr-2"></i>Ajouter un officiant
                                    </button>
                                </div>
                            </div>
                            <div class="p-6 space-y-6">
                                <!-- Liste des officiants -->
                                <div id="officiants-container">
                                    <div class="space-y-4" id="officiants-list">
                                        <!-- Les officiants existants seront chargés ici -->
<!-- Liste des officiants existants -->
@if(old('officiants', $culte->officiants ?? []))
    @foreach(old('officiants', $culte->officiants ?? []) as $index => $officiant)
        <div class="bg-slate-50 rounded-lg p-4 border border-slate-200 officiant-item">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Utilisateur</label>
                    <div class="relative">
                        @php
                            $user = $users->firstWhere('id', $officiant['user_id']);
                            $userName = $user ? ($user->nom . ' ' . $user->prenom) : '';
                        @endphp
                        <input type="text" 
                               class="officiant-user-search w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               value="{{ $userName }}"
                               placeholder="Rechercher un utilisateur..." 
                               autocomplete="off">
                        <input type="hidden" 
                               class="officiant-user" 
                               name="officiants[{{ $index }}][user_id]" 
                               value="{{ $officiant['user_id'] }}">
                        <div class="officiant-user-dropdown absolute z-10 w-full mt-1 bg-white border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden"></div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Titre/Rôle</label>
                    <input type="text" 
                           class="officiant-titre w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                           name="officiants[{{ $index }}][titre]"
                           value="{{ $officiant['titre'] }}"
                           placeholder="Ex: Pasteur Principal">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Provenance</label>
                    <input type="text" 
                           class="officiant-provenance w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                           name="officiants[{{ $index }}][provenance]"
                           value="{{ $officiant['provenance'] ?? 'Église Locale' }}"
                           placeholder="Église Locale">
                </div>
                <div class="flex justify-end">
                    <button type="button" 
                            class="remove-officiant inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash mr-1"></i>Supprimer
                    </button>
                </div>
            </div>
        </div>
    @endforeach
@endif
                                    </div>
                                    <div id="no-officiants"
                                        class="text-center py-8 text-slate-500 {{ (old('officiants', $culte->officiants ?? [])) ? 'hidden' : '' }}">
                                        <i class="fas fa-user-plus text-3xl mb-2"></i>
                                        <p>Aucun officiant ajouté. Cliquez sur "Ajouter un officiant" pour commencer.</p>
                                    </div>
                                </div>

                              
<!-- Template d'officiant (caché) -->
<div id="officiant-template" class="hidden">
    <div class="bg-slate-50 rounded-lg p-4 border border-slate-200 officiant-item">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Utilisateur</label>
                <div class="relative">
                    <input type="text" class="officiant-user-search w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                           placeholder="Rechercher un utilisateur..." autocomplete="off">
                    <input type="hidden" class="officiant-user">
                    <div class="officiant-user-dropdown absolute z-10 w-full mt-1 bg-white border border-slate-300 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden"></div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Titre/Rôle</label>
                <input type="text" class="officiant-titre w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                       placeholder="Ex: Pasteur Principal">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Provenance</label>
                <input type="text" class="officiant-provenance w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                       placeholder="Église Locale" value="Église Locale">
            </div>
            <div class="flex justify-end">
                <button type="button" class="remove-officiant inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-1"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
                            </div>
                        </div>

                        <!-- Message et prédication -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-bible text-amber-600 mr-2"></i>
                                    Message et Prédication
                                </h2>
                            </div>
                            <div class="p-6 space-y-6">
                                <div>
                                    <label for="titre_message" class="block text-sm font-medium text-slate-700 mb-2">Titre du
                                        message</label>
                                    <input type="text" id="titre_message" name="titre_message"
                                        value="{{ old('titre_message', $culte->titre_message) }}" maxlength="300"
                                        placeholder="Titre de la prédication"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('titre_message') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('titre_message')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="passage_biblique" class="block text-sm font-medium text-slate-700 mb-2">Passage
                                        biblique principal</label>
                                    <input type="text" id="passage_biblique" name="passage_biblique"
                                        value="{{ old('passage_biblique', $culte->passage_biblique) }}" maxlength="500"
                                        placeholder="Ex: Jean 3:16-17"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('passage_biblique') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('passage_biblique')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="resume_message" class="block text-sm font-medium text-slate-700 mb-2">Résumé du
                                        message</label>
                                    <div class="@error('resume_message') has-error @enderror">
                                        <textarea id="resume_message" name="resume_message" rows="4"
                                            placeholder="Résumé de la prédication"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('resume_message', $culte->resume_message) }}</textarea>
                                    </div>
                                    @error('resume_message')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="plan_message" class="block text-sm font-medium text-slate-700 mb-2">Plan du
                                        message</label>
                                    <div class="@error('plan_message') has-error @enderror">
                                        <textarea id="plan_message" name="plan_message" rows="4"
                                            placeholder="Plan détaillé du message"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('plan_message', $culte->plan_message) }}</textarea>
                                    </div>
                                    @error('plan_message')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Participation et statistiques (si culte terminé) -->
                        @if($culte->statut === 'termine' || $culte->nombre_participants)
                            <div
                                class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                                <div class="p-6 border-b border-slate-200">
                                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                        <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                                        Participation et Statistiques
                                    </h2>
                                </div>
                                <div class="p-6 space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                        <div>
                                            <label for="nombre_participants"
                                                class="block text-sm font-medium text-slate-700 mb-2">Participants total</label>
                                            <input type="number" id="nombre_participants" name="nombre_participants"
                                                value="{{ old('nombre_participants', $culte->nombre_participants) }}" min="0"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_participants') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @error('nombre_participants')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="nombre_adultes"
                                                class="block text-sm font-medium text-slate-700 mb-2">Adultes</label>
                                            <input type="number" id="nombre_adultes" name="nombre_adultes"
                                                value="{{ old('nombre_adultes', $culte->nombre_adultes) }}" min="0"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_adultes') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @error('nombre_adultes')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="nombre_jeunes"
                                                class="block text-sm font-medium text-slate-700 mb-2">Jeunes</label>
                                            <input type="number" id="nombre_jeunes" name="nombre_jeunes"
                                                value="{{ old('nombre_jeunes', $culte->nombre_jeunes) }}" min="0"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_jeunes') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @error('nombre_jeunes')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="nombre_enfants"
                                                class="block text-sm font-medium text-slate-700 mb-2">Enfants</label>
                                            <input type="number" id="nombre_enfants" name="nombre_enfants"
                                                value="{{ old('nombre_enfants', $culte->nombre_enfants) }}" min="0"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_enfants') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @error('nombre_enfants')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <label for="nombre_nouveaux"
                                                class="block text-sm font-medium text-slate-700 mb-2">Nouveaux visiteurs</label>
                                            <input type="number" id="nombre_nouveaux" name="nombre_nouveaux"
                                                value="{{ old('nombre_nouveaux', $culte->nombre_nouveaux) }}" min="0"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_nouveaux') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @error('nombre_nouveaux')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="nombre_conversions"
                                                class="block text-sm font-medium text-slate-700 mb-2">Conversions</label>
                                            <input type="number" id="nombre_conversions" name="nombre_conversions"
                                                value="{{ old('nombre_conversions', $culte->nombre_conversions) }}" min="0"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_conversions') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @error('nombre_conversions')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="nombre_baptemes"
                                                class="block text-sm font-medium text-slate-700 mb-2">Baptêmes</label>
                                            <input type="number" id="nombre_baptemes" name="nombre_baptemes"
                                                value="{{ old('nombre_baptemes', $culte->nombre_baptemes) }}" min="0"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_baptemes') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @error('nombre_baptemes')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Options avancées -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
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
                                            <input type="checkbox" id="est_public" name="est_public" value="1" {{ old('est_public', $culte->est_public) ? 'checked' : '' }}
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="est_public" class="ml-2 text-sm font-medium text-slate-700">
                                                Culte ouvert au public
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="necessite_invitation" name="necessite_invitation"
                                                value="1" {{ old('necessite_invitation', $culte->necessite_invitation) ? 'checked' : '' }}
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="necessite_invitation" class="ml-2 text-sm font-medium text-slate-700">
                                                Culte sur invitation uniquement
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="diffusion_en_ligne" name="diffusion_en_ligne" value="1"
                                                {{ old('diffusion_en_ligne', $culte->diffusion_en_ligne) ? 'checked' : '' }}
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="diffusion_en_ligne" class="ml-2 text-sm font-medium text-slate-700">
                                                Diffusion en ligne
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="est_enregistre" name="est_enregistre" value="1" {{ old('est_enregistre', $culte->est_enregistre) ? 'checked' : '' }}
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="est_enregistre" class="ml-2 text-sm font-medium text-slate-700">
                                                Culte enregistré (audio/vidéo)
                                            </label>
                                        </div>
                                    </div>

                                    <div id="liens_section"
                                        class="space-y-4 {{ !$culte->diffusion_en_ligne && !$culte->est_enregistre ? 'hidden' : '' }}">
                                        <div>
                                            <label for="lien_diffusion_live"
                                                class="block text-sm font-medium text-slate-700 mb-2">Lien de diffusion en
                                                direct</label>
                                            <input type="url" id="lien_diffusion_live" name="lien_diffusion_live"
                                                value="{{ old('lien_diffusion_live', $culte->lien_diffusion_live) }}"
                                                placeholder="https://..."
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lien_diffusion_live') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @error('lien_diffusion_live')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="lien_enregistrement_video"
                                                class="block text-sm font-medium text-slate-700 mb-2">Lien enregistrement
                                                vidéo</label>
                                            <input type="url" id="lien_enregistrement_video" name="lien_enregistrement_video"
                                                value="{{ old('lien_enregistrement_video', $culte->lien_enregistrement_video) }}"
                                                placeholder="https://..."
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lien_enregistrement_video') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @error('lien_enregistrement_video')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="lien_enregistrement_audio"
                                                class="block text-sm font-medium text-slate-700 mb-2">Lien enregistrement
                                                audio</label>
                                            <input type="url" id="lien_enregistrement_audio" name="lien_enregistrement_audio"
                                                value="{{ old('lien_enregistrement_audio', $culte->lien_enregistrement_audio) }}"
                                                placeholder="https://..."
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lien_enregistrement_audio') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @error('lien_enregistrement_audio')
                                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes et commentaires -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-comment-alt text-indigo-600 mr-2"></i>
                                    Notes et Commentaires
                                </h2>
                            </div>
                            <div class="p-6 space-y-6">
                                <div>
                                    <label for="notes_pasteur" class="block text-sm font-medium text-slate-700 mb-2">Notes du
                                        pasteur</label>
                                    <div class="@error('notes_pasteur') has-error @enderror">
                                        <textarea id="notes_pasteur" name="notes_pasteur" rows="3"
                                            placeholder="Notes et observations du pasteur"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('notes_pasteur', $culte->notes_pasteur) }}</textarea>
                                    </div>
                                    @error('notes_pasteur')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="notes_organisateur" class="block text-sm font-medium text-slate-700 mb-2">Notes
                                        de l'organisateur</label>
                                    <div class="@error('notes_organisateur') has-error @enderror">
                                        <textarea id="notes_organisateur" name="notes_organisateur" rows="3"
                                            placeholder="Notes organisationnelles"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('notes_organisateur', $culte->notes_organisateur) }}</textarea>
                                    </div>
                                    @error('notes_organisateur')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="points_forts" class="block text-sm font-medium text-slate-700 mb-2">Points
                                            forts</label>
                                        <div class="@error('points_forts') has-error @enderror">
                                            <textarea id="points_forts" name="points_forts" rows="3"
                                                placeholder="Points positifs du culte"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('points_forts', $culte->points_forts) }}</textarea>
                                        </div>
                                        @error('points_forts')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="points_amelioration"
                                            class="block text-sm font-medium text-slate-700 mb-2">Points d'amélioration</label>
                                        <div class="@error('points_amelioration') has-error @enderror">
                                            <textarea id="points_amelioration" name="points_amelioration" rows="3"
                                                placeholder="Points à améliorer"
                                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('points_amelioration', $culte->points_amelioration) }}</textarea>
                                        </div>
                                        @error('points_amelioration')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div>
                                    <label for="temoignages"
                                        class="block text-sm font-medium text-slate-700 mb-2">Témoignages</label>
                                    <div class="@error('temoignages') has-error @enderror">
                                        <textarea id="temoignages" name="temoignages" rows="3"
                                            placeholder="Témoignages recueillis pendant le culte"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('temoignages', $culte->temoignages) }}</textarea>
                                    </div>
                                    @error('temoignages')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar - Aperçu et aide -->
                    <div class="space-y-6">
                        <!-- Statut actuel -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                    Statut Actuel
                                </h2>
                            </div>
                            <div class="p-6 text-center">
                                @php
                                    $statutColors = [
                                        'planifie' => 'bg-blue-100 text-blue-800',
                                        'en_preparation' => 'bg-yellow-100 text-yellow-800',
                                        'en_cours' => 'bg-orange-100 text-orange-800',
                                        'termine' => 'bg-green-100 text-green-800',
                                        'annule' => 'bg-red-100 text-red-800',
                                        'reporte' => 'bg-purple-100 text-purple-800'
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statutColors[$culte->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $culte->statut_libelle }}
                                </span>
                                <p class="text-sm text-slate-500 mt-2">Créé le {{ $culte->created_at->format('d/m/Y à H:i') }}
                                </p>
                                <p class="text-sm text-slate-500">Modifié le {{ $culte->updated_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>

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
        <div class="border-t border-slate-200 pt-4">
            <div class="text-sm font-medium text-slate-700 mb-2">Officiants:</div>
            <div id="preview-officiants" class="text-sm text-slate-600">
                <span class="italic">Aucun officiant</span>
            </div>
        </div>
    </div>
</div>

                        <!-- Actions rapides -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                                    Actions Rapides
                                </h2>
                            </div>
                            <div class="p-6 space-y-3">
                                <a href="{{ route('private.cultes.show', $culte) }}"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200">
                                    <i class="fas fa-eye mr-2"></i> Voir les détails
                                </a>

                                @can('cultes.create')
                                    <button type="button" onclick="openDuplicateModal('{{ $culte->id }}')"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                                        <i class="fas fa-copy mr-2"></i> Dupliquer
                                    </button>
                                @endcan

                                @can('cultes.delete')
                                    @if($culte->statut !== 'en_cours')
                                        <button type="button" onclick="deleteCulte('{{ $culte->id }}')"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200">
                                            <i class="fas fa-trash mr-2"></i> Supprimer
                                        </button>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <button type="submit"
                                class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                            </button>
                            <a href="{{ route('private.cultes.show', $culte) }}"
                                class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                <i class="fas fa-times mr-2"></i> Annuler
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        @endcan
    </div>

    <!-- Modal duplication -->
    <div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Dupliquer le culte</h3>
                <form id="duplicateForm">
                    @csrf
                    <input type="hidden" id="duplicate_culte_id" name="culte_id" value="{{ $culte->id }}">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle date</label>
                            <input type="date" name="nouvelle_date" id="nouvelle_date" required
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nouvelle heure</label>
                            <input type="time" name="nouvelle_heure" id="nouvelle_heure"
                                value="{{ $culte->heure_debut ? \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') : '' }}"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau titre (optionnel)</label>
                            <input type="text" name="nouveau_titre" id="nouveau_titre"
                                class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                placeholder="Laisser vide pour ajouter (Copie)">
                        </div>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeDuplicateModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                @can('cultes.duplicate')
                    <button type="button" onclick="duplicateCulte()"
                        class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                        Dupliquer
                    </button>
                @endcan
            </div>
        </div>
    </div>
    @include('partials.ckeditor-resources')



    @push('scripts')
        <script>
            // Données des utilisateurs pour la recherche
            const usersData = [
                @foreach($users as $user)
                        {
                        id: "{{ $user->id }}",
                        nom: "{{ $user->nom }}",
                        prenom: "{{ $user->prenom }}",
                        nom_complet: "{{ $user->nom }} {{ $user->prenom }}",
                        email: "{{ $user->email ?? '' }}"
                    }@if(!$loop->last), @endif
                @endforeach
            ];

            // Gestion des officiants
            let officiantIndex = {{ count(old('officiants', $culte->officiants ?? [])) }};

            // Configuration de la recherche d'utilisateur pour un élément officiant
            function setupUserSearch(container) {
                const searchInput = container.querySelector('.officiant-user-search');
                const hiddenInput = container.querySelector('.officiant-user');
                const dropdown = container.querySelector('.officiant-user-dropdown');

                if (!searchInput || !hiddenInput || !dropdown) return;

                // Fonction de recherche
                function performSearch(query) {
                    const lowerQuery = query.toLowerCase().trim();

                    if (lowerQuery.length < 2) {
                        dropdown.classList.add('hidden');
                        return;
                    }

                    const results = usersData.filter(user => {
                        return user.nom_complet.toLowerCase().includes(lowerQuery) ||
                            user.nom.toLowerCase().includes(lowerQuery) ||
                            user.prenom.toLowerCase().includes(lowerQuery) ||
                            (user.email && user.email.toLowerCase().includes(lowerQuery));
                    }).slice(0, 10); // Limiter à 10 résultats

                    if (results.length > 0) {
                        displayResults(results);
                        dropdown.classList.remove('hidden');
                    } else {
                        dropdown.innerHTML = '<div class="p-3 text-sm text-slate-500 text-center">Aucun utilisateur trouvé</div>';
                        dropdown.classList.remove('hidden');
                    }
                }

                // Afficher les résultats
                function displayResults(results) {
                    dropdown.innerHTML = results.map(user => `
                    <div class="user-result p-3 hover:bg-blue-50 cursor-pointer border-b border-slate-100 last:border-b-0 transition-colors" 
                         data-user-id="${user.id}" 
                         data-user-name="${user.nom_complet}">
                        <div class="font-medium text-slate-900">${user.nom_complet}</div>
                        ${user.email ? `<div class="text-xs text-slate-500">${user.email}</div>` : ''}
                    </div>
                `).join('');

                    // Ajouter les événements de clic sur les résultats
                    dropdown.querySelectorAll('.user-result').forEach(result => {
                        result.addEventListener('click', function () {
                            const userId = this.dataset.userId;
                            const userName = this.dataset.userName;

                            searchInput.value = userName;
                            hiddenInput.value = userId;
                            dropdown.classList.add('hidden');

                            updatePreviewOfficiants();
                        });
                    });
                }

                // Événement de saisie
                searchInput.addEventListener('input', function () {
                    performSearch(this.value);
                });

                // Événement de focus
                searchInput.addEventListener('focus', function () {
                    if (this.value.length >= 2) {
                        performSearch(this.value);
                    }
                });

                // Fermer le dropdown en cliquant ailleurs
                document.addEventListener('click', function (e) {
                    if (!container.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });

                // Effacer la sélection si on modifie manuellement
                searchInput.addEventListener('keydown', function (e) {
                    if (e.key !== 'Tab' && e.key !== 'Enter') {
                        // Ne pas effacer si c'est juste la navigation
                        if (e.key !== 'ArrowUp' && e.key !== 'ArrowDown') {
                            hiddenInput.value = '';
                        }
                    }
                });
            }

            // Ajouter un officiant
            document.getElementById('addOfficiantBtn').addEventListener('click', function () {
                const template = document.getElementById('officiant-template');
                const clone = template.cloneNode(true);
                clone.id = '';
                clone.classList.remove('hidden');

                // Mettre à jour les noms des champs
                const searchInput = clone.querySelector('.officiant-user-search');
                const hiddenInput = clone.querySelector('.officiant-user');
                const titreInput = clone.querySelector('.officiant-titre');
                const provenanceInput = clone.querySelector('.officiant-provenance');

                hiddenInput.name = `officiants[${officiantIndex}][user_id]`;
                titreInput.name = `officiants[${officiantIndex}][titre]`;
                provenanceInput.name = `officiants[${officiantIndex}][provenance]`;

                // Configurer la recherche pour ce nouvel élément
                setupUserSearch(clone);

                // Ajouter l'événement de suppression
                clone.querySelector('.remove-officiant').addEventListener('click', function () {
                    clone.remove();
                    updatePreviewOfficiants();
                    checkNoOfficiants();
                });

                // Ajouter événement pour mise à jour de l'aperçu
                titreInput.addEventListener('input', updatePreviewOfficiants);
                provenanceInput.addEventListener('input', updatePreviewOfficiants);

                document.getElementById('officiants-list').appendChild(clone);
                document.getElementById('no-officiants').classList.add('hidden');

                // Focus sur le champ de recherche
                searchInput.focus();

                officiantIndex++;
                updatePreviewOfficiants();
            });

            // Vérifier si aucun officiant
            function checkNoOfficiants() {
                const officiants = document.querySelectorAll('.officiant-item:not(#officiant-template)');
                if (officiants.length === 0) {
                    document.getElementById('no-officiants').classList.remove('hidden');
                }
            }

            // Mise à jour de l'aperçu des officiants
// Mise à jour de l'aperçu des officiants
function updatePreviewOfficiants() {
    const previewContainer = document.getElementById('preview-officiants');
    
    // Vérifier si l'élément existe (peut ne pas exister dans edit.blade.php)
    if (!previewContainer) {
        return;
    }
    
    const officiants = document.querySelectorAll('.officiant-item:not(#officiant-template)');
    
    if (officiants.length === 0) {
        previewContainer.innerHTML = '<span class="italic">Aucun officiant</span>';
        return;
    }
    
    let html = '<div class="space-y-1">';
    officiants.forEach(officiant => {
        const searchInput = officiant.querySelector('.officiant-user-search');
        const hiddenInput = officiant.querySelector('.officiant-user');
        const titre = officiant.querySelector('.officiant-titre').value;
        const provenance = officiant.querySelector('.officiant-provenance').value;
        const userName = searchInput.value;
        
        if (hiddenInput.value && userName) {
            html += `<div class="text-xs">
                <span class="font-medium">${titre || 'Sans titre'}:</span> ${userName}
                ${provenance && provenance !== 'Église Locale' ? `<span class="text-slate-500">(${provenance})</span>` : ''}
            </div>`;
        }
    });
    html += '</div>';
    
    previewContainer.innerHTML = html;
}

            // Mise à jour de l'aperçu en temps réel
// Mise à jour de l'aperçu en temps réel
function updatePreview() {
    const titre = document.getElementById('titre')?.value || '-';
    const typeSelect = document.getElementById('type_culte');
    const type = typeSelect?.options[typeSelect.selectedIndex]?.text || '-';
    const date = document.getElementById('date_culte')?.value || '-';
    const heureDebut = document.getElementById('heure_debut')?.value || '';
    const heureFin = document.getElementById('heure_fin')?.value || '';
    const lieu = document.getElementById('lieu')?.value || '-';
    const statutSelect = document.getElementById('statut');
    const statut = statutSelect?.options[statutSelect.selectedIndex]?.text || '-';

    // Vérifier si les éléments d'aperçu existent
    const previewTitre = document.getElementById('preview-titre');
    const previewType = document.getElementById('preview-type');
    const previewDate = document.getElementById('preview-date');
    const previewHeure = document.getElementById('preview-heure');
    const previewLieu = document.getElementById('preview-lieu');
    const previewStatut = document.getElementById('preview-statut');

    if (previewTitre) previewTitre.textContent = titre;
    if (previewType) previewType.textContent = type;
    if (previewDate) previewDate.textContent = date !== '-' ? new Date(date).toLocaleDateString('fr-FR') : '-';
    if (previewHeure) previewHeure.textContent = heureDebut + (heureFin ? ' - ' + heureFin : '');
    if (previewLieu) previewLieu.textContent = lieu;
    if (previewStatut) previewStatut.textContent = statut;
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
            document.getElementById('est_public').addEventListener('change', function () {
                const invitationCheckbox = document.getElementById('necessite_invitation');
                if (this.checked) {
                    invitationCheckbox.checked = false;
                }
            });

            document.getElementById('necessite_invitation').addEventListener('change', function () {
                const publicCheckbox = document.getElementById('est_public');
                if (this.checked) {
                    publicCheckbox.checked = false;
                }
            });

            // Événements pour la mise à jour de l'aperçu
            ['titre', 'type_culte', 'date_culte', 'heure_debut', 'heure_fin', 'lieu', 'statut'].forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('input', updatePreview);
                    element.addEventListener('change', updatePreview);
                }
            });

            // Événements pour les liens
            document.getElementById('diffusion_en_ligne').addEventListener('change', toggleLinksSection);
            document.getElementById('est_enregistre').addEventListener('change', toggleLinksSection);

            // Validation du formulaire avec synchronisation CKEditor
            document.getElementById('culteForm').addEventListener('submit', function (e) {
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

                // Vérifier qu'au moins un officiant a toutes ses informations remplies
                const officiants = document.querySelectorAll('.officiant-item:not(#officiant-template)');
                let hasInvalidOfficiant = false;

                officiants.forEach(officiant => {
                    const userId = officiant.querySelector('.officiant-user').value;
                    const titre = officiant.querySelector('.officiant-titre').value.trim();

                    if ((userId && !titre) || (!userId && titre)) {
                        hasInvalidOfficiant = true;
                    }
                });

                if (hasInvalidOfficiant) {
                    e.preventDefault();
                    alert('Veuillez compléter toutes les informations des officiants ou les supprimer.');
                    return false;
                }
            });

            // Initialisation au chargement de la page
            document.addEventListener('DOMContentLoaded', function () {
                // Configurer la recherche pour les officiants déjà présents
                document.querySelectorAll('.officiant-item:not(#officiant-template)').forEach((item) => {
                    setupUserSearch(item);

                    // Ajouter l'événement de suppression
                    const removeBtn = item.querySelector('.remove-officiant');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function () {
                            item.remove();
                            updatePreviewOfficiants();
                            checkNoOfficiants();
                        });
                    }

                    // Ajouter événements pour mise à jour de l'aperçu
                    const titreInput = item.querySelector('.officiant-titre');
                    const provenanceInput = item.querySelector('.officiant-provenance');

                    if (titreInput) {
                        titreInput.addEventListener('input', updatePreviewOfficiants);
                    }
                    if (provenanceInput) {
                        provenanceInput.addEventListener('input', updatePreviewOfficiants);
                    }
                });

                updatePreview();
                toggleLinksSection();
                checkNoOfficiants();
                updatePreviewOfficiants();
            });
        </script>
    @endpush
@endsection