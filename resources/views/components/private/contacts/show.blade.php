@extends('layouts.private.main')
@section('title', 'Détails du Contact - ' . $contact->nom_eglise)

@section('content')
    <div class="space-y-8">
        <!-- Page Title & Breadcrumb -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        {{ $contact->nom_eglise }}</h1>
                    <nav class="flex mt-2" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('private.contacts.index') }}"
                                    class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-church mr-2"></i>
                                    Contacts
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                    <span
                                        class="text-sm font-medium text-slate-500">{{ Str::limit($contact->nom_eglise, 30) }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <!-- Actions rapides -->
                <div class="flex flex-wrap gap-2">
                    @can('contacts.update')
                        <a href="{{ route('private.contacts.edit', $contact) }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-amber-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-amber-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    @endcan

                    @can('contacts.update')
                        @if (!$contact->verifie)
                            <button onclick="verifyContact('{{ $contact->id }}')"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-check mr-2"></i> Vérifier
                            </button>
                        @endif
                    @endcan


                    @can('contacts.export')
                        <a href="{{ route('private.contacts.export', $contact) }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> vCard
                        </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- En-tête avec informations principales -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <!-- Logo et infos principales -->
                    <div class="flex items-center space-x-6">
                        <div class="flex-shrink-0">
                            @if ($contact->logo_url)
                                <img class="h-20 w-20 rounded-2xl object-cover shadow-lg" src="{{ $contact->logo_url }}"
                                    alt="{{ $contact->nom_eglise }}">
                            @else
                                <div
                                    class="h-20 w-20 rounded-2xl bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center shadow-lg">
                                    <span
                                        class="text-white font-bold text-2xl">{{ substr($contact->nom_eglise, 0, 2) }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900">{{ $contact->nom_eglise }}</h2>
                            @if ($contact->denomination)
                                <p class="text-lg text-slate-600 mt-1">{{ $contact->denomination }}</p>
                            @endif
                            @if ($contact->description_courte)
                                <p class="text-sm text-slate-500 mt-2 max-w-md">{{ $contact->description_courte }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Badges de statut -->
                    <div class="flex flex-wrap gap-2 lg:flex-col lg:items-end">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @switch($contact->type_contact)
                            @case('principal') bg-blue-100 text-blue-800 @break
                            @case('pastoral') bg-green-100 text-green-800 @break
                            @case('administratif') bg-purple-100 text-purple-800 @break
                            @case('urgence') bg-red-100 text-red-800 @break
                            @default bg-gray-100 text-gray-800
                        @endswitch">
                            {{ ucfirst($contact->type_contact) }}
                        </span>

                        @if ($contact->verifie)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i> Vérifié
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> En attente
                            </span>
                        @endif

                        @if ($contact->visible_public)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-eye mr-1"></i> Public
                            </span>
                        @endif

                        @if ($contact->latitude && $contact->longitude)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-emerald-100 text-emerald-800">
                                <i class="fas fa-map-marker-alt mr-1"></i> Géolocalisé
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-percentage text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $stats['completude'] }}%</p>
                        <p class="text-sm text-slate-500">Complétude</p>
                    </div>
                </div>
            </div>

            @if ($contact->capacite_accueil)
                <div
                    class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-users text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-slate-800">{{ number_format($contact->capacite_accueil) }}
                            </p>
                            <p class="text-sm text-slate-500">Capacité</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($contact->nombre_membres)
                <div
                    class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-user-friends text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-2xl font-bold text-slate-800">{{ number_format($contact->nombre_membres) }}</p>
                            <p class="text-sm text-slate-500">Membres</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($stats['derniere_verification'])
                <div
                    class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-slate-800">
                                {{ $stats['derniere_verification']->diffForHumans() }}</p>
                            <p class="text-sm text-slate-500">Dernière vérification</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Contenu principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Colonne principale -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Coordonnées -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-phone text-green-600 mr-2"></i>
                            Coordonnées
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if ($contact->telephone_principal)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-phone text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Téléphone principal</p>
                                        <p class="text-lg font-semibold text-slate-900">{{ $contact->telephone_principal }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if ($contact->telephone_secondaire)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-phone text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Téléphone secondaire</p>
                                        <p class="text-lg font-semibold text-slate-900">
                                            {{ $contact->telephone_secondaire }}</p>
                                    </div>
                                </div>
                            @endif

                            @if ($contact->email_principal)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-envelope text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Email principal</p>
                                        <a href="mailto:{{ $contact->email_principal }}"
                                            class="text-lg font-semibold text-purple-600 hover:text-purple-700">{{ $contact->email_principal }}</a>
                                    </div>
                                </div>
                            @endif

                            @if ($contact->whatsapp)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fab fa-whatsapp text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">WhatsApp</p>
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contact->whatsapp) }}"
                                            target="_blank"
                                            class="text-lg font-semibold text-green-600 hover:text-green-700">{{ $contact->whatsapp }}</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Localisation -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                            Localisation
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($contact->adresse_complete)
                            <div class="mb-6">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mt-1">
                                        <i class="fas fa-map-marker-alt text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700 mb-1">Adresse complète</p>
                                        <p class="text-slate-900">{{ $contact->adresse_complete }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            @if ($contact->quartier)
                                <div>
                                    <p class="text-sm font-medium text-slate-700">Quartier</p>
                                    <p class="text-slate-900">{{ $contact->quartier }}</p>
                                </div>
                            @endif

                            @if ($contact->ville)
                                <div>
                                    <p class="text-sm font-medium text-slate-700">Ville</p>
                                    <p class="text-slate-900">{{ $contact->ville }}</p>
                                </div>
                            @endif

                            @if ($contact->pays)
                                <div>
                                    <p class="text-sm font-medium text-slate-700">Pays</p>
                                    <p class="text-slate-900">{{ $contact->pays }}</p>
                                </div>
                            @endif
                        </div>

                        @if ($contact->latitude && $contact->longitude)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-slate-700">Latitude</p>
                                    <p class="text-slate-900 font-mono">{{ $contact->latitude }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-700">Longitude</p>
                                    <p class="text-slate-900 font-mono">{{ $contact->longitude }}</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="https://www.google.com/maps?q={{ $contact->latitude }},{{ $contact->longitude }}"
                                    target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-external-link-alt mr-2"></i> Voir sur Google Maps
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Leadership -->
                @if ($contact->pasteur_principal || $contact->telephone_pasteur || $contact->email_pasteur)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-user-tie text-purple-600 mr-2"></i>
                                Leadership
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-tie text-purple-600 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    @if ($contact->pasteur_principal)
                                        <h4 class="text-lg font-semibold text-slate-900">{{ $contact->pasteur_principal }}
                                        </h4>
                                        <p class="text-sm text-slate-600 mb-2">Pasteur principal</p>
                                    @endif

                                    <div class="space-y-2">
                                        @if ($contact->telephone_pasteur)
                                            <div class="flex items-center space-x-2">
                                                <i class="fas fa-phone text-green-600 text-sm"></i>
                                                <span
                                                    class="text-sm text-slate-700">{{ $contact->telephone_pasteur }}</span>
                                            </div>
                                        @endif

                                        @if ($contact->email_pasteur)
                                            <div class="flex items-center space-x-2">
                                                <i class="fas fa-envelope text-purple-600 text-sm"></i>
                                                <a href="mailto:{{ $contact->email_pasteur }}"
                                                    class="text-sm text-purple-600 hover:text-purple-700">{{ $contact->email_pasteur }}</a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Mission et Vision -->
                @if ($contact->mission_vision)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-bullseye text-orange-600 mr-2"></i>
                                Mission et Vision
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-slate-700 leading-relaxed">{{ $contact->mission_vision }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Colonne de droite -->
            <div class="space-y-6">
                <!-- Réseaux sociaux et sites web -->
                @php
                    $hasSocialMedia =
                        $contact->site_web_principal ||
                        $contact->facebook_url ||
                        $contact->instagram_url ||
                        $contact->youtube_url;
                @endphp

                @if ($hasSocialMedia)
                    <div
                        class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-slate-200">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center">
                                <i class="fas fa-share-alt text-blue-600 mr-2"></i>
                                Présence en ligne
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @if ($contact->site_web_principal)
                                <a href="{{ $contact->site_web_principal }}" target="_blank"
                                    class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-globe text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Site web</p>
                                        <p class="text-blue-600 hover:text-blue-700">{{ $contact->site_web_principal }}
                                        </p>
                                    </div>
                                </a>
                            @endif

                            @if ($contact->facebook_url)
                                <a href="{{ $contact->facebook_url }}" target="_blank"
                                    class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fab fa-facebook text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Facebook</p>
                                        <p class="text-blue-600 hover:text-blue-700 text-sm">
                                            {{ Str::limit($contact->facebook_url, 30) }}</p>
                                    </div>
                                </a>
                            @endif

                            @if ($contact->instagram_url)
                                <a href="{{ $contact->instagram_url }}" target="_blank"
                                    class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                                        <i class="fab fa-instagram text-pink-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">Instagram</p>
                                        <p class="text-pink-600 hover:text-pink-700 text-sm">
                                            {{ Str::limit($contact->instagram_url, 30) }}</p>
                                    </div>
                                </a>
                            @endif

                            @if ($contact->youtube_url)
                                <a href="{{ $contact->youtube_url }}" target="_blank"
                                    class="flex items-center space-x-3 p-3 rounded-lg hover:bg-slate-50 transition-colors">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <i class="fab fa-youtube text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700">YouTube</p>
                                        <p class="text-red-600 hover:text-red-700 text-sm">
                                            {{ Str::limit($contact->youtube_url, 30) }}</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Informations supplémentaires -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-info-circle text-slate-600 mr-2"></i>
                            Informations
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Créé le</span>
                            <span class="text-sm text-slate-600">{{ $contact->created_at->format('d/m/Y') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Modifié le</span>
                            <span class="text-sm text-slate-600">{{ $contact->updated_at->format('d/m/Y') }}</span>
                        </div>

                        @if ($contact->derniere_verification)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Vérifié le</span>
                                <span
                                    class="text-sm text-slate-600">{{ $contact->derniere_verification->format('d/m/Y') }}</span>
                            </div>
                        @endif

                        @if ($contact->createur)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Créé par</span>
                                <span class="text-sm text-slate-600">{{ $contact->createur->nom }}
                                    {{ $contact->createur->prenom }}</span>
                            </div>
                        @endif

                        @if ($contact->responsableContact)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Responsable</span>
                                <span class="text-sm text-slate-600">{{ $contact->responsableContact->nom }}
                                    {{ $contact->responsableContact->prenom }}</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Complétude</span>
                            <div class="flex items-center space-x-2">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $stats['completude'] }}%">
                                    </div>
                                </div>
                                <span class="text-sm font-medium text-slate-700">{{ $stats['completude'] }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div
                    class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                            Actions rapides
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @can('contacts.update')
                            <button onclick="toggleVisibility('{{ $contact->id }}')"
                                class="w-full flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors">
                                <i class="fas fa-{{ $contact->visible_public ? 'eye-slash' : 'eye' }} mr-2"></i>
                                {{ $contact->visible_public ? 'Rendre privé' : 'Rendre public' }}
                            </button>
                        @endcan

                        @can('contacts.create')
                            <a href="{{ route('private.contacts.duplicate', $contact) }}"
                                class="w-full flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <i class="fas fa-copy mr-2"></i> Dupliquer
                            </a>
                        @endcan

                        @can('contacts.delete')
                            <button onclick="deleteContact('{{ $contact->id }}')"
                                class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i> Supprimer
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Vérification d'un contact
            function verifyContact(contactId) {
                if (confirm('Voulez-vous marquer ce contact comme vérifié ?')) {
                    fetch(`{{ route('private.contacts.verify', ':contactid') }}`.replace(':contactid', contactId), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
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
                }
            }

            // Basculer la visibilité
            function toggleVisibility(contactId) {
                const action = {{ $contact->visible_public ? 'false' : 'true' }};
                const message = "{{ $contact->visible_public ? 'rendre ce contact privé' : 'rendre ce contact public' }}";

                if (confirm(`Voulez-vous ${message} ?`)) {
                    fetch(`{{ route('private.contacts.update-visibility', ':contactid') }}`.replace(':contactid', contactId), {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                visible_public: action
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
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
                }
            }

            // Suppression d'un contact
            function deleteContact(contactId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer ce contact ? Cette action est irréversible.')) {
                    fetch(`{{ route('private.contacts.destroy', ':contactid') }}`.replace(':contactid', contactId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = '{{ route('private.contacts.index') }}';
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            alert('Une erreur est survenue');
                        });
                }
            }

            // Afficher QR Code
            // function showQRCode(contactId) {
            //     window.open(`{{ route('private.contacts.qr-code', ':contact') }}`.replace(':contact', contactId), '_blank', 'width=400,height=400');
            // }
        </script>
    @endpush

@endsection
