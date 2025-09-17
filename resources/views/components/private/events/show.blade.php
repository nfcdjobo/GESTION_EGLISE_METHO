@extends('layouts.private.main')
@section('title', $event->titre)

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">{{ $event->titre }}</h1>
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
                                <span class="text-sm font-medium text-slate-500">{{ Str::limit($event->titre, 30) }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                @if($event->sous_titre)
                    <p class="text-slate-600 mt-1">{{ $event->sous_titre }}</p>
                @endif
            </div>
            <div class="flex items-center space-x-2">
                <!-- Statut badge -->
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($event->statut == 'planifie') bg-blue-100 text-blue-800
                    @elseif($event->statut == 'en_promotion') bg-yellow-100 text-yellow-800
                    @elseif($event->statut == 'ouvert_inscription') bg-green-100 text-green-800
                    @elseif($event->statut == 'complet') bg-orange-100 text-orange-800
                    @elseif($event->statut == 'en_cours') bg-purple-100 text-purple-800
                    @elseif($event->statut == 'termine') bg-gray-100 text-gray-800
                    @elseif($event->statut == 'annule') bg-red-100 text-red-800
                    @elseif($event->statut == 'reporte') bg-yellow-100 text-yellow-800
                    @else bg-slate-100 text-slate-800
                    @endif">
                    @switch($event->statut)
                        @case('planifie') <i class="fas fa-calendar mr-1"></i> Planifié @break
                        @case('en_promotion') <i class="fas fa-bullhorn mr-1"></i> En promotion @break
                        @case('ouvert_inscription') <i class="fas fa-user-plus mr-1"></i> Inscriptions ouvertes @break
                        @case('complet') <i class="fas fa-users mr-1"></i> Complet @break
                        @case('en_cours') <i class="fas fa-play mr-1"></i> En cours @break
                        @case('termine') <i class="fas fa-check mr-1"></i> Terminé @break
                        @case('annule') <i class="fas fa-times mr-1"></i> Annulé @break
                        @case('reporte') <i class="fas fa-calendar-alt mr-1"></i> Reporté @break
                        @default <i class="fas fa-edit mr-1"></i> Brouillon @break
                    @endswitch
                </span>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @can('events.update')
                    @if($event->peutEtreModifie())
                        <a href="{{ route('private.events.edit', $event) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    @endif

                    <button type="button" onclick="showStatusModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-exchange-alt mr-2"></i> Changer statut
                    </button>
                @endcan

                @if($event->inscription_requise)
                    <a href="{{ route('private.events.inscriptions', $event) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-users mr-2"></i> Gérer les inscriptions
                    </a>
                @endif

                <button type="button" onclick="duplicateEvent()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-copy mr-2"></i> Dupliquer
                </button>

                @if($event->lien_diffusion)
                    <a href="{{ $event->lien_diffusion }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-video mr-2"></i> Rejoindre en ligne
                    </a>
                @endif

                @can('events.delete')
                    @if($event->statut !== 'en_cours')
                        <button type="button" onclick="deleteEvent()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-trash mr-2"></i> Supprimer
                        </button>
                    @endif
                @endcan
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Image et informations principales -->
            @if($event->image_principale || $event->description)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    @if($event->image_principale)
                        <div class="h-64 bg-gray-200 rounded-t-2xl overflow-hidden">
                            <img src="{{ $event->image_principale }}" alt="{{ $event->titre }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    @if($event->description)
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                Description
                            </h2>
                            <div class="prose max-w-none text-slate-700">
                                {!! nl2br(e($event->description)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Programme détaillé -->
            @if($event->programme_detaille)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-list text-green-600 mr-2"></i>
                            Programme
                        </h2>
                    </div>
                    <div class="p-6">
                        @foreach($event->programme_detaille as $item)
                            <div class="flex items-start space-x-4 py-3 border-b border-slate-100 last:border-b-0">
                                <div class="w-16 text-sm font-medium text-slate-600">
                                    {{ $item['heure'] ?? '' }}
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-slate-900">{{ $item['titre'] ?? '' }}</h3>
                                    @if(isset($item['description']))
                                        <p class="text-slate-600 text-sm mt-1">{{ $item['description'] }}</p>
                                    @endif
                                    @if(isset($item['intervenant']))
                                        <p class="text-blue-600 text-sm mt-1">
                                            <i class="fas fa-user mr-1"></i> {{ $item['intervenant'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Intervenants -->
            @if($event->intervenants)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-microphone text-purple-600 mr-2"></i>
                            Intervenants
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($event->intervenants as $intervenant)
                                <div class="flex items-center space-x-3 p-4 bg-slate-50 rounded-xl">
                                    @if(isset($intervenant['photo']))
                                        <img src="{{ $intervenant['photo'] }}" alt="{{ $intervenant['nom'] }}" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-semibold text-slate-900">{{ $intervenant['nom'] ?? '' }}</h3>
                                        @if(isset($intervenant['role']))
                                            <p class="text-sm text-slate-600">{{ $intervenant['role'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Objectifs -->
            @if($event->objectifs)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bullseye text-red-600 mr-2"></i>
                            Objectifs
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="prose max-w-none text-slate-700">
                            {!! nl2br(e($event->objectifs)) !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Budget -->
            @if($event->budget_prevu || $event->cout_realise || $event->recettes_inscriptions)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-coins text-yellow-600 mr-2"></i>
                            Budget
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @if($event->budget_prevu)
                                <div class="text-center p-4 bg-blue-50 rounded-xl">
                                    <div class="text-2xl font-bold text-blue-600">{{ number_format($event->budget_prevu, 0, ',', ' ') }} FCFA</div>
                                    <div class="text-sm text-blue-800 mt-1">Budget prévu</div>
                                </div>
                            @endif

                            @if($event->cout_realise)
                                <div class="text-center p-4 bg-red-50 rounded-xl">
                                    <div class="text-2xl font-bold text-red-600">{{ number_format($event->cout_realise, 0, ',', ' ') }} FCFA</div>
                                    <div class="text-sm text-red-800 mt-1">Coût réalisé</div>
                                </div>
                            @endif

                            @if($event->recettes_inscriptions)
                                <div class="text-center p-4 bg-green-50 rounded-xl">
                                    <div class="text-2xl font-bold text-green-600">{{ number_format($event->recettes_inscriptions, 0, ',', ' ') }} FCFA</div>
                                    <div class="text-sm text-green-800 mt-1">Recettes inscriptions</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Évaluation -->
            @if($event->statut === 'termine' && ($event->note_globale || $event->feedback_participants))
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-star text-amber-600 mr-2"></i>
                            Évaluation
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        @if($event->note_globale || $event->note_organisation || $event->note_contenu || $event->note_lieu)
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @if($event->note_globale)
                                    <div class="text-center p-3 bg-yellow-50 rounded-xl">
                                        <div class="text-xl font-bold text-yellow-600">{{ $event->note_globale }}/10</div>
                                        <div class="text-xs text-yellow-800">Note globale</div>
                                    </div>
                                @endif

                                @if($event->note_organisation)
                                    <div class="text-center p-3 bg-blue-50 rounded-xl">
                                        <div class="text-xl font-bold text-blue-600">{{ $event->note_organisation }}/10</div>
                                        <div class="text-xs text-blue-800">Organisation</div>
                                    </div>
                                @endif

                                @if($event->note_contenu)
                                    <div class="text-center p-3 bg-green-50 rounded-xl">
                                        <div class="text-xl font-bold text-green-600">{{ $event->note_contenu }}/10</div>
                                        <div class="text-xs text-green-800">Contenu</div>
                                    </div>
                                @endif

                                @if($event->note_lieu)
                                    <div class="text-center p-3 bg-purple-50 rounded-xl">
                                        <div class="text-xl font-bold text-purple-600">{{ $event->note_lieu }}/10</div>
                                        <div class="text-xs text-purple-800">Lieu</div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($event->feedback_participants)
                            <div>
                                <h3 class="font-semibold text-slate-900 mb-2">Feedback des participants</h3>
                                <div class="bg-slate-50 rounded-xl p-4">
                                    <div class="prose max-w-none text-slate-700">
                                        {!! nl2br(e($event->feedback_participants)) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($event->points_positifs || $event->points_amelioration)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($event->points_positifs)
                                    <div>
                                        <h3 class="font-semibold text-green-700 mb-2 flex items-center">
                                            <i class="fas fa-thumbs-up mr-2"></i>
                                            Points positifs
                                        </h3>
                                        <div class="bg-green-50 rounded-xl p-4">
                                            <div class="prose max-w-none text-green-800">
                                                {!! nl2br(e($event->points_positifs)) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($event->points_amelioration)
                                    <div>
                                        <h3 class="font-semibold text-orange-700 mb-2 flex items-center">
                                            <i class="fas fa-tools mr-2"></i>
                                            À améliorer
                                        </h3>
                                        <div class="bg-orange-50 rounded-xl p-4">
                                            <div class="prose max-w-none text-orange-800">
                                                {!! nl2br(e($event->points_amelioration)) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informations essentielles -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info text-blue-600 mr-2"></i>
                        Informations
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Date et heure -->
                    <div class="flex items-start space-x-3">
    <i class="fas fa-calendar text-blue-600 mt-1"></i>
    <div>
        <div class="font-semibold text-slate-900">
            {{ \Carbon\Carbon::parse($event->date_debut)->locale('fr')->isoFormat('dddd D MMMM Y') }} à {{ \Carbon\Carbon::parse($event->heure_debut)->format('H:i') }}
        </div>
        <div class="text-sm text-slate-600">
            au
        </div>
        @if($event->date_fin && $event->date_fin != $event->date_debut)
            <div class="font-semibold text-slate-900">
                {{ \Carbon\Carbon::parse($event->date_fin)->locale('fr')->isoFormat('dddd D MMMM Y') }}
                @if($event->heure_fin)
                    à {{ \Carbon\Carbon::parse($event->heure_fin)->format('H:i') }}
                @endif
            </div>
        @endif
    </div>
</div>

                    <!-- Lieu -->
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-map-marker-alt text-red-600 mt-1"></i>
                        <div>
                            <div class="font-semibold text-slate-900">{{ $event->lieu_nom }}</div>
                            @if($event->lieu_adresse)
                                <div class="text-sm text-slate-600">{{ $event->lieu_adresse }}</div>
                            @endif
                            @if($event->lieu_ville)
                                <div class="text-sm text-slate-500">{{ $event->lieu_ville }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Type et catégorie -->
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst(str_replace('_', ' ', $event->type_evenement)) }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                            {{ ucfirst($event->categorie) }}
                        </span>
                        @if($event->ouvert_public)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-cyan-100 text-cyan-800">
                                <i class="fas fa-globe mr-1"></i> Public
                            </span>
                        @endif
                    </div>

                    <!-- Audience -->
                    @if($event->audience_cible !== 'tous')
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-users text-purple-600 mt-1"></i>
                            <div>
                                <div class="font-semibold text-slate-900">Audience ciblée</div>
                                <div class="text-sm text-slate-600">{{ ucfirst(str_replace('_', ' ', $event->audience_cible)) }}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Âge -->
                    @if($event->age_minimum || $event->age_maximum)
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-birthday-cake text-orange-600 mt-1"></i>
                            <div>
                                <div class="font-semibold text-slate-900">Âge</div>
                                <div class="text-sm text-slate-600">
                                    @if($event->age_minimum && $event->age_maximum)
                                        {{ $event->age_minimum }} - {{ $event->age_maximum }} ans
                                    @elseif($event->age_minimum)
                                        À partir de {{ $event->age_minimum }} ans
                                    @else
                                        Jusqu'à {{ $event->age_maximum }} ans
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Inscriptions -->
            @if($event->inscription_requise)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-plus text-green-600 mr-2"></i>
                            Inscriptions
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Statistiques -->
                        <div class="text-center">
                            <div class="text-3xl font-bold text-green-600">{{ $event->nombre_inscrits }}</div>
                            <div class="text-sm text-slate-600">
                                @if($event->capacite_totale)
                                    sur {{ $event->capacite_totale }} places
                                @else
                                    inscrits
                                @endif
                            </div>

                            @if($event->capacite_totale)
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: {{ $event->pourcentage_remplissage }}%"></div>
                                </div>
                                <div class="text-xs text-slate-500 mt-1">{{ $event->pourcentage_remplissage }}% complet</div>
                            @endif
                        </div>

                        <!-- Statut inscription -->
                        <div class="text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($event->statut_inscription == 'Inscriptions ouvertes') bg-green-100 text-green-800
                                @elseif($event->statut_inscription == 'Complet') bg-red-100 text-red-800
                                @elseif($event->statut_inscription == 'Liste d\'attente') bg-orange-100 text-orange-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $event->statut_inscription }}
                            </span>
                        </div>

                        <!-- Prix -->
                        @if($event->inscription_payante && $event->prix_inscription)
                            <div class="text-center p-3 bg-yellow-50 rounded-xl">
                                <div class="text-xl font-bold text-yellow-600">{{ number_format($event->prix_inscription, 0, ',', ' ') }} FCFA</div>
                                <div class="text-sm text-yellow-800">Prix d'inscription</div>
                            </div>
                        @endif

                        <!-- Dates d'inscription -->
                        @if($event->date_ouverture_inscription || $event->date_fermeture_inscription)
                            <div class="space-y-2 text-sm">
                                @if($event->date_ouverture_inscription)
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Ouverture:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($event->date_ouverture_inscription)->format('d/m/Y') }}</span>
                                    </div>
                                @endif
                                @if($event->date_fermeture_inscription)
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Fermeture:</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($event->date_fermeture_inscription)->format('d/m/Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Action -->
                        <a href="{{ route('private.events.inscriptions', $event) }}" class="block w-full text-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                            <i class="fas fa-users mr-2"></i> Gérer les inscriptions
                        </a>
                    </div>
                </div>
            @endif

            <!-- Responsables -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-user-tie text-indigo-600 mr-2"></i>
                        Responsables
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @if($event->organisateurPrincipal)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-crown text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-slate-900">{{ $event->organisateurPrincipal->prenom }} {{ $event->organisateurPrincipal->nom }}</div>
                                <div class="text-xs text-slate-500">Organisateur principal</div>
                            </div>
                        </div>
                    @endif

                    @if($event->coordinateur)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-cog text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-slate-900">{{ $event->coordinateur->prenom }} {{ $event->coordinateur->nom }}</div>
                                <div class="text-xs text-slate-500">Coordinateur</div>
                            </div>
                        </div>
                    @endif

                    @if($event->responsableLogistique)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-boxes text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-slate-900">{{ $event->responsableLogistique->prenom }} {{ $event->responsableLogistique->nom }}</div>
                                <div class="text-xs text-slate-500">Responsable logistique</div>
                            </div>
                        </div>
                    @endif

                    @if($event->responsableCommunication)
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-pink-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-bullhorn text-pink-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-slate-900">{{ $event->responsableCommunication->prenom }} {{ $event->responsableCommunication->nom }}</div>
                                <div class="text-xs text-slate-500">Responsable communication</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Liens utiles -->
            @if($event->site_web_evenement || $event->video_presentation || $event->lien_diffusion)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-link text-cyan-600 mr-2"></i>
                            Liens
                        </h2>
                    </div>
                    <div class="p-6 space-y-3">
                        @if($event->site_web_evenement)
                            <a href="{{ $event->site_web_evenement }}" target="_blank" class="flex items-center space-x-3 p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                                <i class="fas fa-globe text-blue-600"></i>
                                <span class="text-blue-800 font-medium">Site web de l'événement</span>
                                <i class="fas fa-external-link-alt text-blue-400 ml-auto"></i>
                            </a>
                        @endif

                        @if($event->video_presentation)
                            <a href="{{ $event->video_presentation }}" target="_blank" class="flex items-center space-x-3 p-3 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                                <i class="fas fa-play text-red-600"></i>
                                <span class="text-red-800 font-medium">Vidéo de présentation</span>
                                <i class="fas fa-external-link-alt text-red-400 ml-auto"></i>
                            </a>
                        @endif

                        @if($event->lien_diffusion)
                            <a href="{{ $event->lien_diffusion }}" target="_blank" class="flex items-center space-x-3 p-3 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors">
                                <i class="fas fa-video text-purple-600"></i>
                                <span class="text-purple-800 font-medium">Diffusion en ligne</span>
                                <i class="fas fa-external-link-alt text-purple-400 ml-auto"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Informations système -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cog text-slate-600 mr-2"></i>
                        Système
                    </h2>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Créé le:</span>
                        <span class="font-medium">{{ $event->created_at->format('d/m/Y à H:i') }}</span>
                    </div>

                    @if($event->createur)
                        <div class="flex justify-between">
                            <span class="text-slate-600">Créé par:</span>
                            <span class="font-medium">{{ $event->createur->prenom }} {{ $event->createur->nom }}</span>
                        </div>
                    @endif

                    @if($event->updated_at != $event->created_at)
                        <div class="flex justify-between">
                            <span class="text-slate-600">Modifié le:</span>
                            <span class="font-medium">{{ $event->updated_at->format('d/m/Y à H:i') }}</span>
                        </div>

                        @if($event->modificateur)
                            <div class="flex justify-between">
                                <span class="text-slate-600">Modifié par:</span>
                                <span class="font-medium">{{ $event->modificateur->prenom }} {{ $event->modificateur->nom }}</span>
                            </div>
                        @endif
                    @endif

                    @if($event->statut === 'annule' && $event->annulePar)
                        <div class="flex justify-between text-red-600">
                            <span>Annulé par:</span>
                            <span class="font-medium">{{ $event->annulePar->prenom }} {{ $event->annulePar->nom }}</span>
                        </div>
                        @if($event->annule_le)
                            <div class="flex justify-between text-red-600">
                                <span>Annulé le:</span>
                                <span class="font-medium">{{ $event->annule_le->format('d/m/Y à H:i') }}</span>
                            </div>
                        @endif
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
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-exchange-alt text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Changer le statut</h3>
            </div>
            <form id="statusForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau statut</label>
                    <select id="newStatus" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="brouillon">Brouillon</option>
                        <option value="planifie">Planifié</option>
                        <option value="en_promotion">En promotion</option>
                        <option value="ouvert_inscription">Inscriptions ouvertes</option>
                        <option value="complet">Complet</option>
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                        <option value="annule">Annulé</option>
                        <option value="reporte">Reporté</option>
                        <option value="archive">Archivé</option>
                    </select>
                </div>
                <div id="reasonField" class="mb-4 hidden">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Raison de l'annulation/report</label>
                    <textarea id="statusReason" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" placeholder="Précisez la raison..."></textarea>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" id="confirmStatusChange" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Changer
            </button>
        </div>
    </div>
</div>

<!-- Modal de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3>
            </div>
            <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer cet événement ?</p>
            <p class="text-red-600 font-medium">Cette action est irréversible.</p>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" id="confirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
                Supprimer
            </button>
        </div>
    </div>
</div>

<script>
// Modal functions
function showStatusModal() {
    document.getElementById('newStatus').value = '{{ $event->statut }}';
    document.getElementById('statusModal').classList.remove('hidden');

    // Show/hide reason field based on current status
    toggleReasonField();
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function showDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function toggleReasonField() {
    const statusSelect = document.getElementById('newStatus');
    const reasonField = document.getElementById('reasonField');

    if (['annule', 'reporte'].includes(statusSelect.value)) {
        reasonField.classList.remove('hidden');
    } else {
        reasonField.classList.add('hidden');
    }
}

// Event listeners
document.getElementById('newStatus').addEventListener('change', toggleReasonField);

// Status change
document.getElementById('confirmStatusChange').addEventListener('click', function() {
    const newStatus = document.getElementById('newStatus').value;
    const reason = document.getElementById('statusReason').value;

    const data = { statut: newStatus };
    if (['annule', 'reporte'].includes(newStatus) && reason) {
        data.raison = reason;
    }

    fetch('{{ route("private.events.statut", $event) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        closeStatusModal();
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
});

// Delete event
function deleteEvent() {
    showDeleteModal();
    document.getElementById('confirmDelete').onclick = function() {
        fetch('{{ route("private.events.destroy", $event->id) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeDeleteModal();
            if (data.success) {
                window.location.href = '{{ route("private.events.index") }}';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    };
}

// Duplicate event
function duplicateEvent() {
    const nouvelleDate = prompt('Date du nouvel événement (YYYY-MM-DD):');
    if (!nouvelleDate) return;

    const nouvelleHeure = prompt('Heure du nouvel événement (HH:MM):');
    const nouveauTitre = prompt('Titre du nouvel événement (optionnel):');

    const data = {
        nouvelle_date: nouvelleDate
    };

    if (nouvelleHeure) data.nouvelle_heure = nouvelleHeure;
    if (nouveauTitre) data.nouveau_titre = nouveauTitre;

    fetch('{{ route("private.events.dupliquer", $event->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = `{{ route('private.events.index') }}/${data.data.id}`;
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    });
}

// Close modals when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatusModal();
});

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>

@endsection
