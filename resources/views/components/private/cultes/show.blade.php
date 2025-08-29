@extends('layouts.private.main')
@section('title', 'Détails du Culte')

@section('content')
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        {{ $culte->titre }}</h1>
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
                                    <span class="text-sm font-medium text-slate-500">{{ $culte->titre }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- Actions rapides -->
                <div class="flex items-center space-x-2">
                    @can('cultes.update')
                        <a href="{{ route('private.cultes.edit', $culte) }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    @endcan
                    @can('cultes.create')
                        <button type="button" onclick="openDuplicateModal('{{ $culte->id }}')"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-copy mr-2"></i> Dupliquer
                        </button>
                    @endcan
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informations générales -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Informations Générales
                            </h2>
                            @php
                                $statutColors = [
                                    'planifie' => 'bg-blue-100 text-blue-800',
                                    'en_preparation' => 'bg-yellow-100 text-yellow-800',
                                    'en_cours' => 'bg-orange-100 text-orange-800',
                                    'termine' => 'bg-green-100 text-green-800',
                                    'annule' => 'bg-red-100 text-red-800',
                                    'reporte' => 'bg-purple-100 text-purple-800',
                                ];
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statutColors[$culte->statut] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $culte->statut_libelle }}
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Type de culte</span>
                                    <p class="text-lg font-semibold text-slate-900">{{ $culte->type_culte_libelle }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Catégorie</span>
                                    <p class="text-lg font-semibold text-slate-900">{{ $culte->categorie_libelle }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Programme</span>
                                    <p class="text-lg font-semibold text-slate-900">
                                        {{ $culte->programme->nom ?? 'Non défini' }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Date</span>
                                    <p class="text-lg font-semibold text-slate-900">
                                        {{ \Carbon\Carbon::parse($culte->date_culte)->format('l d F Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Horaires</span>
                                    <p class="text-lg font-semibold text-slate-900">
                                        {{ \Carbon\Carbon::parse($culte->heure_debut)->format('H:i') }}
                                        @if ($culte->heure_fin)
                                            - {{ \Carbon\Carbon::parse($culte->heure_fin)->format('H:i') }}
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-slate-500">Lieu</span>
                                    <p class="text-lg font-semibold text-slate-900">{{ $culte->lieu }}</p>
                                </div>
                            </div>
                        </div>

                        @if ($culte->description)
                            <div class="mt-6 pt-6 border-t border-slate-200">
                                <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                    <i class="fas fa-align-left text-blue-600 mr-2"></i>
                                    Description
                                </h3>
                                <x-ckeditor-display :model="$culte" field="description" show-meta="true" class="bg-slate-50 p-4 rounded-lg" />
                            </div>
                        @endif

                        <!-- Options -->
                        <div class="mt-6 pt-6 border-t border-slate-200">
                            <div class="flex flex-wrap gap-2">
                                @if ($culte->est_public)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                        <i class="fas fa-globe mr-1"></i> Public
                                    </span>
                                @endif
                                @if ($culte->necessite_invitation)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-envelope mr-1"></i> Sur invitation
                                    </span>
                                @endif
                                @if ($culte->diffusion_en_ligne)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-broadcast-tower mr-1"></i> Diffusion en ligne
                                    </span>
                                @endif
                                @if ($culte->est_enregistre)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-video mr-1"></i> Enregistré
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Responsables -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-users text-purple-600 mr-2"></i>
                            Responsables et Intervenants
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($culte->pasteurPrincipal)
                                <div
                                    class="flex items-center space-x-3 p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-tie text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Pasteur principal</p>
                                        <p class="font-semibold text-slate-900">{{ $culte->pasteurPrincipal->nom }}
                                            {{ $culte->pasteurPrincipal->prenom }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($culte->predicateur)
                                <div
                                    class="flex items-center space-x-3 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-microphone text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Prédicateur</p>
                                        <p class="font-semibold text-slate-900">{{ $culte->predicateur->nom }}
                                            {{ $culte->predicateur->prenom }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($culte->responsableCulte)
                                <div
                                    class="flex items-center space-x-3 p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-cog text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Responsable du culte</p>
                                        <p class="font-semibold text-slate-900">{{ $culte->responsableCulte->nom }}
                                            {{ $culte->responsableCulte->prenom }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($culte->dirigeantLouange)
                                <div
                                    class="flex items-center space-x-3 p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl">
                                    <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-music text-amber-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-500">Dirigeant de louange</p>
                                        <p class="font-semibold text-slate-900">{{ $culte->dirigeantLouange->nom }}
                                            {{ $culte->dirigeantLouange->prenom }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Message et prédication -->
                @if ($culte->titre_message || $culte->passage_biblique || $culte->resume_message || $culte->plan_message)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-bible text-amber-600 mr-2"></i>
                                Message et Prédication
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            @if ($culte->titre_message)
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Titre du message</h3>
                                    <p class="text-xl font-bold text-blue-700">{{ $culte->titre_message }}</p>
                                </div>
                            @endif

                            @if ($culte->passage_biblique)
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-2">Passage biblique</h3>
                                    <p class="text-lg font-semibold text-blue-700 bg-blue-50 p-3 rounded-lg">
                                        {{ $culte->passage_biblique }}</p>
                                </div>
                            @endif

                            @if ($culte->resume_message)
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Résumé du message</h3>
                                    <x-ckeditor-display :model="$culte" field="resume_message" show-meta="true"
                                        show-reading-time="true" />
                                </div>
                            @endif

                            @if ($culte->plan_message)
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3">Plan du message</h3>
                                    <x-ckeditor-display :model="$culte" field="plan_message" show-meta="true"
                                        class="bg-amber-50 p-4 rounded-lg border border-amber-200" />
                                </div>
                            @endif

                            {{-- Métadonnées globales du message --}}
                            @if ($culte->resume_message || $culte->plan_message)
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-amber-50 p-4 rounded-lg border border-blue-200">
                                    <h4 class="font-semibold text-slate-800 mb-2">Aperçu du message</h4>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                                        <div class="text-center">
                                            <div class="text-lg font-bold text-blue-600">
                                                {{ $culte->getMessageWordCount() }}</div>
                                            <div class="text-slate-600">Mots au total</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-lg font-bold text-amber-600">
                                                {{ $culte->getMessageReadingTime() }}</div>
                                            <div class="text-slate-600">Min de lecture</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-lg font-bold text-purple-600">
                                                {{ $culte->hasRichContent() ? 'Oui' : 'Non' }}
                                            </div>
                                            <div class="text-slate-600">Mise en forme</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif


                <!-- Statistiques et participation -->
                @if ($culte->statut === 'termine' || $culte->nombre_participants)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                                Statistiques et Participation
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @if ($culte->nombre_participants)
                                    <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl">
                                        <div class="text-2xl font-bold text-blue-600">{{ $culte->nombre_participants }}
                                        </div>
                                        <div class="text-sm text-slate-600">Participants</div>
                                    </div>
                                @endif

                                @if ($culte->nombre_adultes)
                                    <div class="text-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl">
                                        <div class="text-2xl font-bold text-green-600">{{ $culte->nombre_adultes }}</div>
                                        <div class="text-sm text-slate-600">Adultes</div>
                                    </div>
                                @endif

                                @if ($culte->nombre_jeunes)
                                    <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl">
                                        <div class="text-2xl font-bold text-purple-600">{{ $culte->nombre_jeunes }}</div>
                                        <div class="text-sm text-slate-600">Jeunes</div>
                                    </div>
                                @endif

                                @if ($culte->nombre_enfants)
                                    <div class="text-center p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl">
                                        <div class="text-2xl font-bold text-amber-600">{{ $culte->nombre_enfants }}</div>
                                        <div class="text-sm text-slate-600">Enfants</div>
                                    </div>
                                @endif

                                @if ($culte->nombre_nouveaux)
                                    <div class="text-center p-4 bg-gradient-to-r from-cyan-50 to-blue-50 rounded-xl">
                                        <div class="text-2xl font-bold text-cyan-600">{{ $culte->nombre_nouveaux }}</div>
                                        <div class="text-sm text-slate-600">Nouveaux</div>
                                    </div>
                                @endif

                                @if ($culte->nombre_conversions)
                                    <div class="text-center p-4 bg-gradient-to-r from-yellow-50 to-amber-50 rounded-xl">
                                        <div class="text-2xl font-bold text-yellow-600">{{ $culte->nombre_conversions }}
                                        </div>
                                        <div class="text-sm text-slate-600">Conversions</div>
                                    </div>
                                @endif

                                @if ($culte->nombre_baptemes)
                                    <div class="text-center p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl">
                                        <div class="text-2xl font-bold text-indigo-600">{{ $culte->nombre_baptemes }}
                                        </div>
                                        <div class="text-sm text-slate-600">Baptêmes</div>
                                    </div>
                                @endif

                                @if ($culte->offrande_totale)
                                    <div class="text-center p-4 bg-gradient-to-r from-emerald-50 to-green-50 rounded-xl">
                                        <div class="text-2xl font-bold text-emerald-600">
                                            {{ number_format($culte->offrande_totale, 0) }}€</div>
                                        <div class="text-sm text-slate-600">Offrandes</div>
                                    </div>
                                @endif
                            </div>

                            @if ($culte->heure_debut_reelle || $culte->heure_fin_reelle)
                                <div class="mt-6 pt-6 border-t border-slate-200">
                                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Horaires réels</h3>
                                    <div class="flex items-center space-x-6">
                                        @if ($culte->heure_debut_reelle)
                                            <div>
                                                <span class="text-sm font-medium text-slate-500">Début réel</span>
                                                <p class="text-lg font-semibold text-slate-900">
                                                    {{ \Carbon\Carbon::parse($culte->heure_debut_reelle)->format('H:i') }}
                                                </p>
                                            </div>
                                        @endif
                                        @if ($culte->heure_fin_reelle)
                                            <div>
                                                <span class="text-sm font-medium text-slate-500">Fin réelle</span>
                                                <p class="text-lg font-semibold text-slate-900">
                                                    {{ \Carbon\Carbon::parse($culte->heure_fin_reelle)->format('H:i') }}
                                                </p>
                                            </div>
                                        @endif
                                        @if ($culte->duree_totale)
                                            <div>
                                                <span class="text-sm font-medium text-slate-500">Durée totale</span>
                                                <p class="text-lg font-semibold text-slate-900">{{ $culte->duree_totale }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Notes et commentaires -->
                @if (
                    $culte->notes_pasteur ||
                        $culte->notes_organisateur ||
                        $culte->temoignages ||
                        $culte->points_forts ||
                        $culte->points_amelioration)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-comment-alt text-cyan-600 mr-2"></i>
                                Notes et Commentaires
                            </h2>
                        </div>
                        <div class="p-6 space-y-6">
                            @if ($culte->notes_pasteur)
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                        <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                                        Notes du pasteur
                                    </h3>
                                    <x-ckeditor-display :model="$culte" field="notes_pasteur"
                                        class="bg-blue-50 p-4 rounded-lg border border-blue-200" show-meta="true" />
                                </div>
                            @endif

                            @if ($culte->notes_organisateur)
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                        <i class="fas fa-user-cog text-green-600 mr-2"></i>
                                        Notes de l'organisateur
                                    </h3>
                                    <x-ckeditor-display :model="$culte" field="notes_organisateur"
                                        class="bg-green-50 p-4 rounded-lg border border-green-200" show-meta="true" />
                                </div>
                            @endif

                            @if ($culte->points_forts || $culte->points_amelioration)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @if ($culte->points_forts)
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                                <i class="fas fa-thumbs-up text-emerald-600 mr-2"></i>
                                                Points forts
                                            </h3>
                                            <x-ckeditor-display :model="$culte" field="points_forts"
                                                class="bg-emerald-50 p-4 rounded-lg border border-emerald-200" />
                                        </div>
                                    @endif

                                    @if ($culte->points_amelioration)
                                        <div>
                                            <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                                <i class="fas fa-arrow-up text-amber-600 mr-2"></i>
                                                Points d'amélioration
                                            </h3>
                                            <x-ckeditor-display :model="$culte" field="points_amelioration"
                                                class="bg-amber-50 p-4 rounded-lg border border-amber-200" />
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if ($culte->temoignages)
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-800 mb-3 flex items-center">
                                        <i class="fas fa-heart text-purple-600 mr-2"></i>
                                        Témoignages
                                    </h3>
                                    <x-ckeditor-display :model="$culte" field="temoignages"
                                        class="bg-purple-50 p-4 rounded-lg border border-purple-200" show-meta="true" />
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
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
    @can('cultes.participant')
        <a href="{{route('private.cultes.participants', $culte->id)}}"
            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
            <i class="fas fa-user-plus mr-2"></i> Ajouter des participants
        </a>
    @endcan

    @if ($culte->statut !== 'termine')
        <button type="button"
            onclick="openStatusModal('{{ $culte->id }}', '{{ $culte->statut }}')"
            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200">
            <i class="fas fa-exchange-alt mr-2"></i> Changer le statut
        </button>
    @endif

    @if ($culte->lien_diffusion_live)
        <a href="{{ $culte->lien_diffusion_live }}" target="_blank"
            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white text-sm font-medium rounded-xl hover:from-red-600 hover:to-pink-600 transition-all duration-200">
            <i class="fas fa-broadcast-tower mr-2"></i> Diffusion live
        </a>
    @endif

    @if ($culte->lien_enregistrement_video)
        <a href="{{ $culte->lien_enregistrement_video }}" target="_blank"
            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
            <i class="fas fa-video mr-2"></i> Enregistrement vidéo
        </a>
    @endif

    @if ($culte->lien_enregistrement_audio)
        <a href="{{ $culte->lien_enregistrement_audio }}" target="_blank"
            class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200">
            <i class="fas fa-volume-up mr-2"></i> Enregistrement audio
        </a>
    @endif

    @can('cultes.delete')
        @if ($culte->statut !== 'en_cours')
            <button type="button" onclick="deleteCulte('{{ $culte->id }}')"
                class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200">
                <i class="fas fa-trash mr-2"></i> Supprimer
            </button>
        @endif
    @endcan
</div>
                </div>

                <!-- Évaluations -->
                @if ($culte->note_globale || $culte->note_louange || $culte->note_message || $culte->note_organisation)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-star text-amber-600 mr-2"></i>
                                Évaluations
                            </h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @if ($culte->note_globale)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Note globale</span>
                                    <div class="flex items-center">
                                        <span
                                            class="text-lg font-bold text-amber-600 mr-2">{{ $culte->note_globale }}/10</span>
                                        <div class="flex">
                                            @for ($i = 1; $i <= 10; $i++)
                                                <i
                                                    class="fas fa-star text-xs {{ $i <= $culte->note_globale ? 'text-amber-400' : 'text-slate-300' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($culte->note_louange)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Louange</span>
                                    <span class="text-lg font-bold text-purple-600">{{ $culte->note_louange }}/10</span>
                                </div>
                            @endif

                            @if ($culte->note_message)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Message</span>
                                    <span class="text-lg font-bold text-blue-600">{{ $culte->note_message }}/10</span>
                                </div>
                            @endif

                            @if ($culte->note_organisation)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-slate-700">Organisation</span>
                                    <span
                                        class="text-lg font-bold text-green-600">{{ $culte->note_organisation }}/10</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Informations système -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-cog text-slate-600 mr-2"></i>
                            Informations Système
                        </h2>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Créé le:</span>
                            <span class="text-slate-900 font-medium">{{ $culte->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if ($culte->createur)
                            <div class="flex justify-between">
                                <span class="text-slate-500">Créé par:</span>
                                <span class="text-slate-900 font-medium">{{ $culte->createur->nom }}
                                    {{ $culte->createur->prenom }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-slate-500">Modifié le:</span>
                            <span class="text-slate-900 font-medium">{{ $culte->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if ($culte->modificateur)
                            <div class="flex justify-between">
                                <span class="text-slate-500">Modifié par:</span>
                                <span class="text-slate-900 font-medium">{{ $culte->modificateur->nom }}
                                    {{ $culte->modificateur->prenom }}</span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal changement de statut -->
    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Changer le statut du culte</h3>
                <form id="statusForm">
                    @csrf
                    <input type="hidden" id="culte_id" name="culte_id" value="{{ $culte->id }}">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau statut</label>
                        <select id="nouveau_statut" name="statut"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="planifie">Planifié</option>
                            <option value="en_preparation">En Préparation</option>
                            <option value="en_cours">En Cours</option>
                            <option value="termine">Terminé</option>
                            <option value="annule">Annulé</option>
                            <option value="reporte">Reporté</option>
                        </select>
                    </div>
                    <div id="raisonDiv" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Raison</label>
                        <textarea name="raison" id="raison" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                            placeholder="Raison de l'annulation ou du report..."></textarea>
                    </div>
                </form>
            </div>
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
                <button type="button" onclick="closeStatusModal()"
                    class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Annuler
                </button>
                <button type="button" onclick="updateStatus()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                    Changer le statut
                </button>
            </div>
        </div>
    </div>

    <!-- Modal duplication -->
    <div id="duplicateModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
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
                                value="{{ $culte->heure_debut }}"
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
                <button type="button" onclick="duplicateCulte()"
                    class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                    Dupliquer
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Modal statut
            function openStatusModal(culteId, currentStatus) {
                document.getElementById('culte_id').value = culteId;
                document.getElementById('nouveau_statut').value = currentStatus;
                toggleRaisonField();
                document.getElementById('statusModal').classList.remove('hidden');
            }

            function closeStatusModal() {
                document.getElementById('statusModal').classList.add('hidden');
                document.getElementById('statusForm').reset();
            }

            function toggleRaisonField() {
                const statut = document.getElementById('nouveau_statut').value;
                const raisonDiv = document.getElementById('raisonDiv');
                if (statut === 'annule' || statut === 'reporte') {
                    raisonDiv.classList.remove('hidden');
                    document.getElementById('raison').required = true;
                } else {
                    raisonDiv.classList.add('hidden');
                    document.getElementById('raison').required = false;
                }
            }

            document.getElementById('nouveau_statut').addEventListener('change', toggleRaisonField);

            function updateStatus() {
                const form = document.getElementById('statusForm');
                const formData = new FormData(form);
                const culteId = document.getElementById('culte_id').value;

                fetch(`{{ route('private.cultes.statut', ':culteid') }}`.replace(':culteid', culteId), {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
            }

            // Modal duplication
            function openDuplicateModal(culteId) {
                document.getElementById('duplicate_culte_id').value = culteId;
                // Définir la date de demain par défaut
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('nouvelle_date').value = tomorrow.toISOString().split('T')[0];
                document.getElementById('duplicateModal').classList.remove('hidden');
            }

            function closeDuplicateModal() {
                document.getElementById('duplicateModal').classList.add('hidden');
                document.getElementById('duplicateForm').reset();
            }

            function duplicateCulte() {
                const form = document.getElementById('duplicateForm');
                const formData = new FormData(form);
                const culteId = document.getElementById('duplicate_culte_id').value;

                fetch(`{{ route('private.cultes.dupliquer', ':culteid') }}`.replace(':culteid', culteId), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = `{{ route('private.cultes.show', ':culteid') }}`.replace(':culteid',
                                data.data.id);
                        } else {
                            alert(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('Une erreur est survenue');
                    });
            }

            // Suppression
            function deleteCulte(culteId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce culte ?')) {
                    fetch(`{{ route('private.cultes.destroy', ':culteid') }}`.replace(':culteid', culteId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = '{{ route('private.cultes.index') }}';
                            } else {
                                alert(data.message || 'Une erreur est survenue');
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            alert('Une erreur est survenue');
                        });
                }
            }

            // Fermer les modals en cliquant à l'extérieur
            document.getElementById('statusModal').addEventListener('click', function(e) {
                if (e.target === this) closeStatusModal();
            });

            document.getElementById('duplicateModal').addEventListener('click', function(e) {
                if (e.target === this) closeDuplicateModal();
            });
        </script>
    @endpush
@endsection
