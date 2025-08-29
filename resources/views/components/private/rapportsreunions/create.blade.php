@extends('layouts.private.main')
@section('title', 'Nouveau Rapport de Réunion')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Nouveau Rapport de Réunion</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.rapports-reunions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-file-alt mr-2"></i>
                        Rapports de Réunions
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Nouveau Rapport</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <form action="{{ route('private.rapports-reunions.store') }}" method="POST" id="rapportForm" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
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
                        <div>
                            <label for="reunion_id" class="block text-sm font-medium text-slate-700 mb-2">
                                Réunion concernée <span class="text-red-500">*</span>
                            </label>
                            <select id="reunion_id" name="reunion_id" required
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('reunion_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">Sélectionner une réunion</option>
                                @foreach($reunions as $reunion)
                                    <option value="{{ $reunion->id }}" {{ old('reunion_id') == $reunion->id ? 'selected' : '' }}>
                                        {{ $reunion->titre }} - {{ \Carbon\Carbon::parse($reunion->date_reunion)->format('d/m/Y') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('reunion_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-slate-500">Seules les réunions terminées sans rapport sont disponibles</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="titre_rapport" class="block text-sm font-medium text-slate-700 mb-2">
                                    Titre du rapport <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="titre_rapport" name="titre_rapport" value="{{ old('titre_rapport') }}" required maxlength="200" placeholder="Ex: Rapport de la réunion mensuelle"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('titre_rapport') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('titre_rapport')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type_rapport" class="block text-sm font-medium text-slate-700 mb-2">
                                    Type de rapport <span class="text-red-500">*</span>
                                </label>
                                <select id="type_rapport" name="type_rapport" required
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_rapport') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner le type</option>
                                    @foreach(\App\Models\RapportReunion::TYPES_RAPPORT as $key => $value)
                                        <option value="{{ $value }}" {{ old('type_rapport') == $value ? 'selected' : '' }}>
                                            @switch($value)
                                                @case('proces_verbal') Procès-verbal @break
                                                @case('compte_rendu') Compte-rendu @break
                                                @case('rapport_activite') Rapport d'activité @break
                                                @case('rapport_financier') Rapport financier @break
                                            @endswitch
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_rapport')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="redacteur_id" class="block text-sm font-medium text-slate-700 mb-2">Rédacteur</label>
                                <select id="redacteur_id" name="redacteur_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('redacteur_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Auto-assignation</option>
                                    @foreach($redacteurs as $redacteur)
                                        <option value="{{ $redacteur->id }}" {{ old('redacteur_id') == $redacteur->id ? 'selected' : '' }}>
                                            {{ $redacteur->nom }} {{ $redacteur->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('redacteur_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-slate-500">Laissez vide pour vous assigner automatiquement</p>
                            </div>

                            <div>
                                <label for="validateur_id" class="block text-sm font-medium text-slate-700 mb-2">Validateur</label>
                                <select id="validateur_id" name="validateur_id"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('validateur_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    <option value="">Sélectionner un validateur</option>
                                    @foreach($validateurs as $validateur)
                                        <option value="{{ $validateur->id }}" {{ old('validateur_id') == $validateur->id ? 'selected' : '' }}>
                                            {{ $validateur->nom }} {{ $validateur->prenom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('validateur_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="resume" class="block text-sm font-medium text-slate-700 mb-2">Résumé</label>
                            <div class="@error('resume') has-error @enderror">
                                <textarea id="resume" name="resume" rows="4" placeholder="Résumé du rapport"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('resume') }}</textarea>
                            </div>
                            @error('resume')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contenu du rapport -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-edit text-green-600 mr-2"></i>
                            Contenu du Rapport
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label for="decisions_prises" class="block text-sm font-medium text-slate-700 mb-2">Décisions prises</label>
                            <div class="@error('decisions_prises') has-error @enderror">
                                <textarea id="decisions_prises" name="decisions_prises" rows="4" placeholder="Décisions importantes prises lors de la réunion"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('decisions_prises') }}</textarea>
                            </div>
                            @error('decisions_prises')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="actions_decidees" class="block text-sm font-medium text-slate-700 mb-2">Actions décidées</label>
                            <div class="@error('actions_decidees') has-error @enderror">
                                <textarea id="actions_decidees" name="actions_decidees" rows="4" placeholder="Actions décidées et à mettre en œuvre"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('actions_decidees') }}</textarea>
                            </div>
                            @error('actions_decidees')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="recommandations" class="block text-sm font-medium text-slate-700 mb-2">Recommandations</label>
                            <div class="@error('recommandations') has-error @enderror">
                                <textarea id="recommandations" name="recommandations" rows="4" placeholder="Recommandations pour l'avenir"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('recommandations') }}</textarea>
                            </div>
                            @error('recommandations')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="commentaires" class="block text-sm font-medium text-slate-700 mb-2">Commentaires généraux</label>
                            <div class="@error('commentaires') has-error @enderror">
                                <textarea id="commentaires" name="commentaires" rows="3" placeholder="Commentaires généraux sur la réunion"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('commentaires') }}</textarea>
                            </div>
                            @error('commentaires')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Points traités -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-list text-purple-600 mr-2"></i>
                            Points Traités
                        </h2>
                    </div>
                    <div class="p-6">
                        <div id="points-traites-container">
                            <div class="space-y-4" id="points-list">
                                <div class="flex items-center gap-2">
                                    <input type="text" name="points_traites[]" placeholder="Titre du point traité"
                                        class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <button type="button" onclick="ajouterPoint()" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">Ajoutez les différents points qui ont été traités lors de la réunion</p>
                    </div>
                </div>

                <!-- Présences -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-cyan-600 mr-2"></i>
                            Présences
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nombre_presents" class="block text-sm font-medium text-slate-700 mb-2">Nombre de présents</label>
                                <input type="number" id="nombre_presents" name="nombre_presents" value="{{ old('nombre_presents', 0) }}" min="0"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nombre_presents') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('nombre_presents')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="montant_collecte" class="block text-sm font-medium text-slate-700 mb-2">Montant collecté (€)</label>
                                <input type="number" id="montant_collecte" name="montant_collecte" value="{{ old('montant_collecte') }}" min="0" step="0.01"
                                    class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('montant_collecte') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                @error('montant_collecte')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div id="presences-container">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Liste des présences (optionnel)</label>
                            <div class="space-y-2" id="presences-list">
                                <div class="flex items-center gap-2">
                                    <input type="text" name="presences_data[0][nom]" placeholder="Nom et prénom"
                                        class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <input type="text" name="presences_data[0][role]" placeholder="Rôle (optionnel)"
                                        class="w-32 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <button type="button" onclick="ajouterPresence()" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-slate-500">Vous pouvez ajouter les présences maintenant ou les gérer plus tard</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Aperçu et options -->
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
                            <span class="text-sm font-medium text-slate-700">Réunion:</span>
                            <span id="preview-reunion" class="text-sm text-slate-600">-</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Rédacteur:</span>
                            <span id="preview-redacteur" class="text-sm text-slate-600">{{ auth()->user()->nom }} {{ auth()->user()->prenom }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Présents:</span>
                            <span id="preview-presents" class="text-sm text-slate-600">0</span>
                        </div>
                    </div>
                </div>

                <!-- Options d'évaluation -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-star text-yellow-600 mr-2"></i>
                            Évaluation
                        </h2>
                    </div>
                    <div class="p-6">
                        <div>
                            <label for="note_satisfaction" class="block text-sm font-medium text-slate-700 mb-2">Note de satisfaction (1-5)</label>
                            <select id="note_satisfaction" name="note_satisfaction"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('note_satisfaction') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                <option value="">Non évaluée</option>
                                @for($i = 1; $i <= 5; $i++)
                                    <option value="{{ $i }}" {{ old('note_satisfaction') == $i ? 'selected' : '' }}>{{ $i }} - {{ ['Très insatisfaisante', 'Insatisfaisante', 'Correcte', 'Satisfaisante', 'Excellente'][$i-1] }}</option>
                                @endfor
                            </select>
                            @error('note_satisfaction')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions de suivi -->
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-tasks text-indigo-600 mr-2"></i>
                            Actions de Suivi
                        </h2>
                    </div>
                    <div class="p-6">
                        <div id="actions-container">
                            <div class="space-y-3" id="actions-list">
                                <div class="border border-slate-200 rounded-lg p-3">
                                    <input type="text" name="actions_initiales[0][titre]" placeholder="Titre de l'action"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors mb-2">
                                    <textarea name="actions_initiales[0][description]" placeholder="Description (optionnel)" rows="2"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none mb-2"></textarea>
                                    <div class="flex gap-2">
                                        <input type="date" name="actions_initiales[0][echeance]"
                                            class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <select name="actions_initiales[0][priorite]" class="w-32 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                            <option value="normale">Normale</option>
                                            <option value="faible">Faible</option>
                                            <option value="haute">Haute</option>
                                            <option value="critique">Critique</option>
                                        </select>
                                        <button type="button" onclick="ajouterAction()" class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">Les actions de suivi peuvent être ajoutées maintenant ou plus tard</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i> Créer le rapport
                    </button>
                    <button type="submit" name="save_and_continue" value="1" class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-arrow-right mr-2"></i> Créer et continuer
                    </button>
                    <a href="{{ route('private.rapports-reunions.index') }}" class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

@include('partials.ckeditor-resources')
@push('scripts')
<script>
let pointsCount = 1;
let presencesCount = 1;
let actionsCount = 1;

// Mise à jour de l'aperçu en temps réel
function updatePreview() {
    const titre = document.getElementById('titre_rapport').value || '-';
    const typeSelect = document.getElementById('type_rapport');
    const type = typeSelect.options[typeSelect.selectedIndex]?.text || '-';
    const reunionSelect = document.getElementById('reunion_id');
    const reunion = reunionSelect.options[reunionSelect.selectedIndex]?.text || '-';
    const redacteurSelect = document.getElementById('redacteur_id');
    const redacteur = redacteurSelect.options[redacteurSelect.selectedIndex]?.text || '{{ auth()->user()->nom }} {{ auth()->user()->prenom }}';
    const presents = document.getElementById('nombre_presents').value || '0';

    document.getElementById('preview-titre').textContent = titre;
    document.getElementById('preview-type').textContent = type;
    document.getElementById('preview-reunion').textContent = reunion;
    document.getElementById('preview-redacteur').textContent = redacteur;
    document.getElementById('preview-presents').textContent = presents;
}

// Ajouter un point traité
function ajouterPoint() {
    const container = document.getElementById('points-list');
    const newPoint = document.createElement('div');
    newPoint.className = 'flex items-center gap-2';
    newPoint.innerHTML = `
        <input type="text" name="points_traites[]" placeholder="Titre du point traité"
            class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
        <button type="button" onclick="supprimerPoint(this)" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newPoint);
    pointsCount++;
}

function supprimerPoint(button) {
    button.closest('.flex').remove();
}

// Ajouter une présence
function ajouterPresence() {
    const container = document.getElementById('presences-list');
    const newPresence = document.createElement('div');
    newPresence.className = 'flex items-center gap-2';
    newPresence.innerHTML = `
        <input type="text" name="presences_data[${presencesCount}][nom]" placeholder="Nom et prénom"
            class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
        <input type="text" name="presences_data[${presencesCount}][role]" placeholder="Rôle (optionnel)"
            class="w-32 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
        <button type="button" onclick="supprimerPresence(this)" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(newPresence);
    presencesCount++;
}

function supprimerPresence(button) {
    button.closest('.flex').remove();
}

// Ajouter une action de suivi
function ajouterAction() {
    const container = document.getElementById('actions-list');
    const newAction = document.createElement('div');
    newAction.className = 'border border-slate-200 rounded-lg p-3';
    newAction.innerHTML = `
        <input type="text" name="actions_initiales[${actionsCount}][titre]" placeholder="Titre de l'action"
            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors mb-2">
        <textarea name="actions_initiales[${actionsCount}][description]" placeholder="Description (optionnel)" rows="2"
            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none mb-2"></textarea>
        <div class="flex gap-2">
            <input type="date" name="actions_initiales[${actionsCount}][echeance]"
                class="flex-1 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
            <select name="actions_initiales[${actionsCount}][priorite]" class="w-32 px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                <option value="normale">Normale</option>
                <option value="faible">Faible</option>
                <option value="haute">Haute</option>
                <option value="critique">Critique</option>
            </select>
            <button type="button" onclick="supprimerAction(this)" class="px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    container.appendChild(newAction);
    actionsCount++;
}

function supprimerAction(button) {
    button.closest('.border').remove();
}

// Événements pour la mise à jour de l'aperçu
['titre_rapport', 'type_rapport', 'reunion_id', 'redacteur_id', 'nombre_presents'].forEach(id => {
    const element = document.getElementById(id);
    if (element) {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    }
});

// Auto-génération du titre basé sur la réunion
document.getElementById('reunion_id').addEventListener('change', function() {
    const titreInput = document.getElementById('titre_rapport');
    if (!titreInput.value && this.selectedIndex > 0) {
        const reunionText = this.options[this.selectedIndex].text;
        titreInput.value = 'Rapport - ' + reunionText.split(' - ')[0];
        updatePreview();
    }
});

// Validation du formulaire
document.getElementById('rapportForm').addEventListener('submit', function(e) {
    // Synchroniser tous les éditeurs CKEditor avant validation
    if (window.CKEditorInstances) {
        Object.values(window.CKEditorInstances).forEach(editor => {
            const element = editor.sourceElement;
            if (element) {
                element.value = editor.getData();
            }
        });
    }

    const reunion = document.getElementById('reunion_id').value;
    const titre = document.getElementById('titre_rapport').value.trim();
    const type = document.getElementById('type_rapport').value;

    if (!reunion || !titre || !type) {
        e.preventDefault();
        alert('Veuillez remplir tous les champs obligatoires.');
        return false;
    }

    // Nettoyer les champs vides dans les arrays
    cleanupArrayFields();
});

function cleanupArrayFields() {
    // Nettoyer les points traités vides
    const pointsInputs = document.querySelectorAll('input[name="points_traites[]"]');
    pointsInputs.forEach(input => {
        if (!input.value.trim()) {
            input.remove();
        }
    });

    // Nettoyer les présences vides
    const presencesInputs = document.querySelectorAll('input[name^="presences_data"][name$="[nom]"]');
    presencesInputs.forEach(input => {
        if (!input.value.trim()) {
            const container = input.closest('.flex');
            if (container) container.remove();
        }
    });

    // Nettoyer les actions vides
    const actionsInputs = document.querySelectorAll('input[name^="actions_initiales"][name$="[titre]"]');
    actionsInputs.forEach(input => {
        if (!input.value.trim()) {
            const container = input.closest('.border');
            if (container) container.remove();
        }
    });
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
});
</script>
@endpush
@endsection
