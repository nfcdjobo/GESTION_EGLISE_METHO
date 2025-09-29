@extends('layouts.private.main')
@section('title', 'Modifier l\'Annonce')

@section('content')
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Modifier l'Annonce</h1>
            <nav class="flex mt-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('private.annonces.index') }}"
                            class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                            <i class="fas fa-bullhorn mr-2"></i>
                            Annonces
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                            <a href="{{ route('private.annonces.show', $annonce) }}"
                                class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                {{ Str::limit($annonce->titre, 20) }}
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

        @can('annonces.update')
            <form action="{{ route('private.annonces.update', $annonce) }}" method="POST" id="annonceForm" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Informations principales -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Alerte si brouillon -->
                        @if($annonce->statut === 'brouillon')
                            <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800">Brouillon</h3>
                                        <div class="mt-2 text-sm text-yellow-700">
                                            <p>Cette annonce est encore en brouillon. N'oubliez pas de la publier une fois les
                                                modifications terminées.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                                <div>
                                    <label for="titre" class="block text-sm font-medium text-slate-700 mb-2">
                                        Titre de l'annonce <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="titre" name="titre" value="{{ old('titre', $annonce->titre) }}"
                                        required maxlength="200" placeholder="Titre accrocheur pour l'annonce"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('titre') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                    @error('titre')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="contenu" class="block text-sm font-medium text-slate-700 mb-2">
                                        Contenu de l'annonce <span class="text-red-500">*</span>
                                    </label>
                                    <div class="@error('contenu') has-error @enderror">
                                        <textarea id="contenu" name="contenu" rows="6"
                                            placeholder="Contenu détaillé de l'annonce"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none">{{ old('contenu', $annonce->contenu) }}</textarea>
                                    </div>
                                    @error('contenu')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="type_annonce" class="block text-sm font-medium text-slate-700 mb-2">
                                            Type d'annonce <span class="text-red-500">*</span>
                                        </label>
                                        <select id="type_annonce" name="type_annonce" required
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('type_annonce') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            <option value="">Sélectionner le type</option>
                                            @foreach($typesAnnonces as $key => $label)
                                                <option value="{{ $key }}" {{ old('type_annonce', $annonce->type_annonce) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('type_annonce')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="niveau_priorite" class="block text-sm font-medium text-slate-700 mb-2">
                                            Niveau de priorité
                                        </label>
                                        <select id="niveau_priorite" name="niveau_priorite"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('niveau_priorite') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @foreach($niveauxPriorite as $key => $label)
                                                <option value="{{ $key }}" {{ old('niveau_priorite', $annonce->niveau_priorite) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('niveau_priorite')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="audience_cible" class="block text-sm font-medium text-slate-700 mb-2">
                                            Audience cible
                                        </label>
                                        <select id="audience_cible" name="audience_cible"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('audience_cible') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                            @foreach($audiencesCibles as $key => $label)
                                                <option value="{{ $key }}" {{ old('audience_cible', $annonce->audience_cible) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('audience_cible')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Détails de l'événement (conditionnel) -->
                        <div id="evenement_section"
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 {{ $annonce->type_annonce !== 'evenement' ? 'hidden' : '' }}">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-calendar-event text-green-600 mr-2"></i>
                                    Détails de l'Événement
                                </h2>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="date_evenement" class="block text-sm font-medium text-slate-700 mb-2">
                                            Date de l'événement
                                        </label>
                                        <input type="date" id="date_evenement" name="date_evenement"
                                            value="{{ old('date_evenement', $annonce->date_evenement?->format('Y-m-d')) }}"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('date_evenement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('date_evenement')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="lieu_evenement" class="block text-sm font-medium text-slate-700 mb-2">
                                            Lieu de l'événement
                                        </label>
                                        <input type="text" id="lieu_evenement" name="lieu_evenement"
                                            value="{{ old('lieu_evenement', $annonce->lieu_evenement) }}" maxlength="255"
                                            placeholder="Lieu de l'événement"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lieu_evenement') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        @error('lieu_evenement')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact et responsabilité -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-user-friends text-purple-600 mr-2"></i>
                                    Contact et Responsabilité
                                </h2>
                            </div>
                            <div class="p-6 space-y-6">
                                <div>
                                    <label for="contact_principal_id" class="block text-sm font-medium text-slate-700 mb-2">
                                        Contact principal
                                    </label>
                                    <select id="contact_principal_id" name="contact_principal_id"
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('contact_principal_id') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <option value="">Sélectionner un contact</option>
                                        @foreach($contacts as $contact)
                                            <option value="{{ $contact->id }}" {{ old('contact_principal_id', $annonce->contact_principal_id) == $contact->id ? 'selected' : '' }}>
                                                {{ $contact->nom }} {{ $contact->prenom }} ({{ $contact->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('contact_principal_id')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Paramètres de diffusion -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-broadcast-tower text-amber-600 mr-2"></i>
                                    Paramètres de Diffusion
                                </h2>
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="afficher_site_web" name="afficher_site_web" value="1" {{ old('afficher_site_web', $annonce->afficher_site_web) ? 'checked' : '' }}
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="afficher_site_web" class="ml-2 text-sm font-medium text-slate-700">
                                                Afficher sur le site web
                                            </label>
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" id="annoncer_culte" name="annoncer_culte" value="1" {{ old('annoncer_culte', $annonce->annoncer_culte) ? 'checked' : '' }}
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="annoncer_culte" class="ml-2 text-sm font-medium text-slate-700">
                                                Annoncer pendant le culte
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="expire_le" class="block text-sm font-medium text-slate-700 mb-2">
                                            Date d'expiration
                                        </label>
                                        <input type="datetime-local" id="expire_le" name="expire_le"
                                            value="{{ old('expire_le', $annonce->expire_le?->format('Y-m-d\TH:i')) }}"
                                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('expire_le') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror">
                                        <p class="mt-1 text-xs text-slate-500">Laisser vide pour une annonce permanente</p>
                                        @error('expire_le')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar - Aperçu et informations -->
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
                            <div class="p-6 space-y-4">
                                <div class="text-center">
                                    <span
                                        class="inline-flex items-center px-4 py-2 rounded-full text-lg font-medium {{ $annonce->badge_statut }}">
                                        {{ \App\Models\Annonce::getStatuts()[$annonce->statut] ?? $annonce->statut }}
                                    </span>
                                </div>

                                @if($annonce->publie_le)
                                    <div class="text-center text-sm text-slate-600 pt-2 border-t border-slate-100">
                                        Publié le {{ $annonce->publie_le->format('d/m/Y à H:i') }}
                                    </div>
                                @endif

                                @if($annonce->expire_le && $annonce->jours_restants !== null)
                                    <div class="text-center pt-2 border-t border-slate-100">
                                        @if($annonce->jours_restants > 0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $annonce->jours_restants <= 3 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $annonce->jours_restants }} jour{{ $annonce->jours_restants > 1 ? 's' : '' }}
                                                restant{{ $annonce->jours_restants > 1 ? 's' : '' }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Expirée
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Aperçu -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-eye text-purple-600 mr-2"></i>
                                    Aperçu
                                </h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Titre:</span>
                                    <span id="preview-titre"
                                        class="text-sm text-slate-900 font-semibold">{{ $annonce->titre }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Type:</span>
                                    <span id="preview-type"
                                        class="text-sm text-slate-600">{{ $typesAnnonces[$annonce->type_annonce] ?? $annonce->type_annonce }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Priorité:</span>
                                    <span id="preview-priorite"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $annonce->badge_priorite }}">{{ $niveauxPriorite[$annonce->niveau_priorite] ?? $annonce->niveau_priorite }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Audience:</span>
                                    <span id="preview-audience"
                                        class="text-sm text-slate-600">{{ $audiencesCibles[$annonce->audience_cible] ?? $annonce->audience_cible }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Diffusion:</span>
                                    <div id="preview-diffusion" class="flex flex-col items-end space-y-1">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 {{ $annonce->afficher_site_web ? '' : 'hidden' }}"
                                            id="badge-web">
                                            Site web
                                        </span>
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 {{ $annonce->annoncer_culte ? '' : 'hidden' }}"
                                            id="badge-culte">
                                            Culte
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Historique -->
                        <div
                            class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                            <div class="p-6 border-b border-slate-200">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                    <i class="fas fa-history text-green-600 mr-2"></i>
                                    Historique
                                </h2>
                            </div>
                            <div class="p-6 space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="font-medium text-slate-700">Créé:</span>
                                    <span class="text-slate-600">{{ $annonce->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if($annonce->updated_at && $annonce->updated_at != $annonce->created_at)
                                    <div class="flex justify-between">
                                        <span class="font-medium text-slate-700">Modifié:</span>
                                        <span class="text-slate-600">{{ $annonce->updated_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                                @if($annonce->publie_le)
                                    <div class="flex justify-between">
                                        <span class="font-medium text-slate-700">Publié:</span>
                                        <span class="text-slate-600">{{ $annonce->publie_le->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                                @if($annonce->auteur)
                                    <div class="pt-2 border-t border-slate-100">
                                        <span class="font-medium text-slate-700">Auteur:</span>
                                        <span class="text-slate-600">{{ $annonce->auteur->nom }}
                                            {{ $annonce->auteur->prenom }}</span>
                                    </div>
                                @endif
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

                            @if($annonce->statut === 'brouillon')
                                @can('publish', $annonce)
                                    <button type="button" onclick="publierAnnonce('{{ $annonce->id }}')"
                                        class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                        <i class="fas fa-paper-plane mr-2"></i> Publier maintenant
                                    </button>
                                @endcan
                            @endif

                            <a href="{{ route('private.annonces.show', $annonce) }}"
                                class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i> Voir l'annonce
                            </a>

                            <a href="{{ route('private.annonces.index') }}"
                                class="inline-flex items-center justify-center px-8 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
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
            // Même logique que create mais avec données pré-remplies
            function updatePreview() {
                const titre = document.getElementById('titre').value || '-';
                const typeSelect = document.getElementById('type_annonce');
                const type = typeSelect.options[typeSelect.selectedIndex]?.text || '-';
                const prioriteSelect = document.getElementById('niveau_priorite');
                const priorite = prioriteSelect.options[prioriteSelect.selectedIndex]?.text || '-';
                const audienceSelect = document.getElementById('audience_cible');
                const audience = audienceSelect.options[audienceSelect.selectedIndex]?.text || '-';

                const afficherWeb = document.getElementById('afficher_site_web').checked;
                const annoncerCulte = document.getElementById('annoncer_culte').checked;

                document.getElementById('preview-titre').textContent = titre;
                document.getElementById('preview-type').textContent = type;
                document.getElementById('preview-priorite').textContent = priorite;
                document.getElementById('preview-audience').textContent = audience;

                // Mise à jour des badges de diffusion
                const badgeWeb = document.getElementById('badge-web');
                const badgeCulte = document.getElementById('badge-culte');

                if (afficherWeb) {
                    badgeWeb.classList.remove('hidden');
                } else {
                    badgeWeb.classList.add('hidden');
                }

                if (annoncerCulte) {
                    badgeCulte.classList.remove('hidden');
                } else {
                    badgeCulte.classList.add('hidden');
                }

                // Mise à jour de la classe de priorité
                const prioriteElement = document.getElementById('preview-priorite');
                prioriteElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';

                switch (prioriteSelect.value) {
                    case 'urgent':
                        prioriteElement.classList.add('bg-red-100', 'text-red-800');
                        break;
                    case 'important':
                        prioriteElement.classList.add('bg-yellow-100', 'text-yellow-800');
                        break;
                    default:
                        prioriteElement.classList.add('bg-gray-100', 'text-gray-800');
                }
            }

            function toggleEvenementSection() {
                const typeSelect = document.getElementById('type_annonce');
                const evenementSection = document.getElementById('evenement_section');
                const dateInput = document.getElementById('date_evenement');
                const lieuInput = document.getElementById('lieu_evenement');

                if (typeSelect.value === 'evenement') {
                    evenementSection.classList.remove('hidden');
                    dateInput.required = true;
                    lieuInput.required = true;
                } else {
                    evenementSection.classList.add('hidden');
                    dateInput.required = false;
                    lieuInput.required = false;
                }
            }

            function handleUrgencyType() {
                const typeSelect = document.getElementById('type_annonce');
                const prioriteSelect = document.getElementById('niveau_priorite');

                if (typeSelect.value === 'urgence') {
                    prioriteSelect.value = 'urgent';
                    prioriteSelect.disabled = true;
                } else {
                    prioriteSelect.disabled = false;
                }
                updatePreview();
            }

            function publierAnnonce(annonceId) {
                if (confirm('Êtes-vous sûr de vouloir publier cette annonce ? \nElle sera visible par le public.')) {
                    fetch("{{ route('private.annonces.publier', ':annonce')}}".replace(':annonce', annonceId), {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = "{{route('private.annonces.show', ':annonce')}}".replace(':annonce', annonceId);
                        } else {
                            alert(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
                }
            };

            // Événements
            ['titre', 'type_annonce', 'niveau_priorite', 'audience_cible'].forEach(id => {
                document.getElementById(id).addEventListener('input', updatePreview);
                document.getElementById(id).addEventListener('change', updatePreview);
            });

            document.getElementById('afficher_site_web').addEventListener('change', updatePreview);
            document.getElementById('annoncer_culte').addEventListener('change', updatePreview);
            document.getElementById('type_annonce').addEventListener('change', function () {
                toggleEvenementSection();
                handleUrgencyType();
            });

            // Validation
            document.getElementById('annonceForm').addEventListener('submit', function (e) {
                // Synchroniser CKEditor
                if (window.CKEditorInstances) {
                    Object.values(window.CKEditorInstances).forEach(editor => {
                        const element = editor.sourceElement;
                        if (element) {
                            element.value = editor.getData();
                        }
                    });
                }

                const titre = document.getElementById('titre').value.trim();
                const contenu = document.getElementById('contenu').value.trim();
                const type = document.getElementById('type_annonce').value;

                if (!titre || !contenu || !type) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires.');
                    return false;
                }

                // Validation pour les événements
                if (type === 'evenement') {
                    const dateEvenement = document.getElementById('date_evenement').value;
                    const lieuEvenement = document.getElementById('lieu_evenement').value.trim();

                    if (!dateEvenement || !lieuEvenement) {
                        e.preventDefault();
                        alert('Pour un événement, la date et le lieu sont obligatoires.');
                        return false;
                    }
                }
            });

            // Initialisation
            document.addEventListener('DOMContentLoaded', function () {
                updatePreview();
                toggleEvenementSection();
                handleUrgencyType();
            });
        </script>
    @endpush
@endsection
