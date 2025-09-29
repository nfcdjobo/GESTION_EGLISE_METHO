@extends('layouts.private.main')
@section('title', 'Détails des Paramètres')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Détails des Paramètres</h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.parametres.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-cogs mr-2"></i>
                        Paramètres
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Détails</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @can('parametres.update')
                    <a href="{{ route('private.parametres.edit') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                @endcan

                <button type="button" onclick="exportParametres()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-medium rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i> Exporter
                </button>

                <button type="button" onclick="printParametres()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i> Imprimer
                </button>

                <a href="{{ route('private.parametres.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Identité de l'église -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-church text-blue-600 mr-2"></i>
                        Identité de l'Église
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="flex items-center space-x-6">
                        @if($parametres->logo_url)
                            <img src="{{ $parametres->logo_url }}" alt="Logo" class="w-24 h-24 object-cover rounded-xl shadow-lg">
                        @else
                            <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-church text-white text-3xl"></i>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900">{{ $parametres->nom_eglise }}</h3>
                            @if($parametres->date_fondation)
                                <p class="text-slate-600 mt-1">Fondée le {{ $parametres->date_fondation->format('d/m/Y') }}</p>
                                <p class="text-sm text-slate-500">{{ $parametres->date_fondation->diffInYears() }} années d'existence</p>
                            @endif

@if($parametres->nombre_membres)
                                <p class="text-sm text-slate-500 mt-1">{{ number_format($parametres->nombre_membres) }} membres</p>
                            @endif
                        </div>
                    </div>

                    @if($parametres->description_eglise)
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <h4 class="font-semibold text-slate-900 mb-2">Description</h4>
                            <p class="text-slate-700 leading-relaxed">{{ $parametres->description_eglise }}</p>
                        </div>
                    @endif

                    @if($parametres->histoire_eglise)
                        <div class="p-4 bg-blue-50 rounded-xl">
                            <h4 class="font-semibold text-blue-900 mb-2">Histoire</h4>
                            <p class="text-blue-800 leading-relaxed">{{ $parametres->histoire_eglise }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contenu spirituel -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bible text-amber-600 mr-2"></i>
                        Contenu Spirituel
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    @if($parametres->verset_biblique)
                        <div class="p-6 bg-amber-50 rounded-xl border-l-4 border-amber-500">
                            <h4 class="font-semibold text-amber-900 mb-3">Verset de l'Église</h4>
                            <blockquote class="text-amber-800 italic text-lg leading-relaxed">
                                "{{ $parametres->verset_biblique }}"
                            </blockquote>
                            @if($parametres->reference_verset)
                                <p class="text-amber-700 font-medium mt-3">- {{ $parametres->reference_verset }}</p>
                            @endif
                        </div>
                    @endif

                    @if($parametres->mission_statement)
                        <div class="p-4 bg-green-50 rounded-xl">
                            <h4 class="font-semibold text-green-900 mb-2 flex items-center">
                                <i class="fas fa-bullseye text-green-600 mr-2"></i>
                                Mission
                            </h4>
                            <p class="text-green-800 leading-relaxed">{{ $parametres->mission_statement }}</p>
                        </div>
                    @endif

                    @if($parametres->vision)
                        <div class="p-4 bg-purple-50 rounded-xl">
                            <h4 class="font-semibold text-purple-900 mb-2 flex items-center">
                                <i class="fas fa-eye text-purple-600 mr-2"></i>
                                Vision
                            </h4>
                            <p class="text-purple-800 leading-relaxed">{{ $parametres->vision }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-address-book text-green-600 mr-2"></i>
                        Informations de Contact
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-phone text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Téléphone principal</p>
                                    <p class="font-medium text-slate-900">{{ $parametres->telephone_1 }}</p>
                                </div>
                            </div>

                            @if($parametres->telephone_2)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-phone-alt text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-500">Téléphone secondaire</p>
                                        <p class="font-medium text-slate-900">{{ $parametres->telephone_2 }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-envelope text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Email principal</p>
                                    <p class="font-medium text-slate-900">{{ $parametres->email_principal }}</p>
                                </div>
                            </div>

                            @if($parametres->email_secondaire)
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-envelope-open text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-500">Email secondaire</p>
                                        <p class="font-medium text-slate-900">{{ $parametres->email_secondaire }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-xl">
                        <h4 class="font-semibold text-slate-900 mb-2 flex items-center">
                            <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                            Adresse
                        </h4>
                        <p class="text-slate-700">{{ $parametres->getAdresseComplete() }}</p>
                    </div>
                </div>
            </div>

            <!-- Programmes de l'église -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar-alt text-amber-600 mr-2"></i>
                        Programmes de l'Église
                        <span class="ml-auto text-sm font-normal text-slate-500">
                            {{ count($parametres->getProgrammes()) }} programme(s)
                        </span>
                    </h2>
                </div>
                <div class="p-6">
                    @if($parametres->getProgrammes() && count($parametres->getProgrammes()) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($parametres->getProgrammes() as $programme)
                                <div class="p-4 bg-slate-50 rounded-xl border {{ ($programme['est_public'] ?? true) ? 'border-green-200' : 'border-orange-200' }}">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-12 h-12 bg-gradient-to-r from-amber-100 to-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="{{ $programme['icone'] ?? 'fas fa-calendar' }} text-amber-600"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <h4 class="font-semibold text-slate-900 truncate">{{ $programme['titre'] ?? 'Programme sans titre' }}</h4>
                                                <div class="flex items-center space-x-1 ml-2">
                                                    @if($programme['est_public'] ?? true)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-eye mr-1"></i> Public
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                            <i class="fas fa-lock mr-1"></i> Privé
                                                        </span>
                                                    @endif

                                                    @if(!($programme['est_actif'] ?? true))
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                            <i class="fas fa-pause mr-1"></i> Inactif
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if($programme['description'] ?? '')
                                                <p class="text-sm text-slate-600 mt-1 line-clamp-2">{{ Str::limit($programme['description'], 100) }}</p>
                                            @endif

                                            <div class="mt-3 space-y-2">
                                                <div class="flex items-center text-sm text-slate-700">
                                                    <i class="fas fa-tag text-slate-400 mr-2 w-4"></i>
                                                    <span class="capitalize">{{ str_replace('_', ' ', $programme['type_horaire'] ?? 'regulier') }}</span>
                                                </div>

                                                @if($programme['horaire_texte'] ?? '')
                                                    <div class="flex items-center text-sm text-slate-700">
                                                        <i class="fas fa-clock text-slate-400 mr-2 w-4"></i>
                                                        <span>{{ $programme['horaire_texte'] }}</span>
                                                    </div>
                                                @endif

                                                @if(($programme['jour'] ?? '') && ($programme['heure_debut'] ?? ''))
                                                    <div class="flex items-center text-sm text-slate-700">
                                                        <i class="fas fa-calendar-day text-slate-400 mr-2 w-4"></i>
                                                        <span>{{ $programme['jour'] }} à {{ $programme['heure_debut'] }}
                                                            @if($programme['heure_fin'] ?? '')
                                                                - {{ $programme['heure_fin'] }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Statistiques des programmes -->
                        <div class="mt-6 pt-4 border-t border-slate-200">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                                <div class="p-3 bg-blue-50 rounded-lg">
                                    <div class="text-lg font-bold text-blue-900">{{ count($parametres->getProgrammesPublics()) }}</div>
                                    <div class="text-xs text-blue-700">Publics</div>
                                </div>
                                <div class="p-3 bg-green-50 rounded-lg">
                                    <div class="text-lg font-bold text-green-900">{{ collect($parametres->getProgrammes())->where('est_actif', true)->count() }}</div>
                                    <div class="text-xs text-green-700">Actifs</div>
                                </div>
                                <div class="p-3 bg-purple-50 rounded-lg">
                                    <div class="text-lg font-bold text-purple-900">{{ collect($parametres->getProgrammes())->where('type_horaire', 'regulier')->count() }}</div>
                                    <div class="text-xs text-purple-700">Réguliers</div>
                                </div>
                                <div class="p-3 bg-amber-50 rounded-lg">
                                    <div class="text-lg font-bold text-amber-900">{{ collect($parametres->getProgrammes())->whereIn('type_horaire', ['sur_rendez_vous', 'permanent'])->count() }}</div>
                                    <div class="text-xs text-amber-700">Spéciaux</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar-alt text-2xl text-slate-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-slate-900 mb-2">Aucun programme configuré</h3>
                            <p class="text-slate-500 mb-4">Ajoutez des programmes pour organiser les activités de votre église.</p>
                            @can('parametres.update')
                                <a href="{{ route('private.parametres.edit') }}#programmes" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i> Ajouter des programmes
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>

            <!-- Médias -->
            @if($parametres->images_hero_urls && count($parametres->images_hero_urls) > 0)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-images text-purple-600 mr-2"></i>
                            Galerie d'Images
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($parametres->images_hero_urls as $imageUrl)
                                <div class="group relative">
                                    <img src="{{ $imageUrl }}" alt="Image de l'église" class="w-full h-32 object-cover rounded-xl shadow-md group-hover:shadow-lg transition-shadow">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-xl transition-opacity cursor-pointer" onclick="openImageModal('{{ $imageUrl }}')">
                                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-expand text-white text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Résumé -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-cyan-600 mr-2"></i>
                        Résumé
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Membres:</span>
                        <span class="text-lg font-bold text-slate-900">{{ number_format($parametres->nombre_membres ?: 0) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Fondation:</span>
                        <span class="text-lg font-bold text-slate-900">{{ $parametres->date_fondation ? $parametres->date_fondation->format('Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Années:</span>
                        <span class="text-lg font-bold text-slate-900">{{ $parametres->date_fondation ? $parametres->date_fondation->diffInYears() : '0' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Programmes:</span>
                        <span class="text-lg font-bold text-slate-900">{{ count($parametres->getProgrammes()) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Pays:</span>
                        <span class="text-lg font-bold text-slate-900">{{ $parametres->pays }}</span>
                    </div>
                </div>
            </div>

            <!-- Réseaux sociaux -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-share-alt text-pink-600 mr-2"></i>
                        Réseaux Sociaux
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @if($parametres->facebook_url)
                        <a href="{{ $parametres->facebook_url }}" target="_blank" class="flex items-center space-x-3 p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                            <i class="fab fa-facebook text-blue-600 text-xl"></i>
                            <span class="text-blue-800 font-medium">Facebook</span>
                            <i class="fas fa-external-link-alt text-blue-500 text-sm ml-auto"></i>
                        </a>
                    @endif

                    @if($parametres->instagram_url)
                        <a href="{{ $parametres->instagram_url }}" target="_blank" class="flex items-center space-x-3 p-3 bg-pink-50 rounded-xl hover:bg-pink-100 transition-colors">
                            <i class="fab fa-instagram text-pink-600 text-xl"></i>
                            <span class="text-pink-800 font-medium">Instagram</span>
                            <i class="fas fa-external-link-alt text-pink-500 text-sm ml-auto"></i>
                        </a>
                    @endif

                    @if($parametres->youtube_url)
                        <a href="{{ $parametres->youtube_url }}" target="_blank" class="flex items-center space-x-3 p-3 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                            <i class="fab fa-youtube text-red-600 text-xl"></i>
                            <span class="text-red-800 font-medium">YouTube</span>
                            <i class="fas fa-external-link-alt text-red-500 text-sm ml-auto"></i>
                        </a>
                    @endif

                    @if($parametres->twitter_url)
                        <a href="{{ $parametres->twitter_url }}" target="_blank" class="flex items-center space-x-3 p-3 bg-sky-50 rounded-xl hover:bg-sky-100 transition-colors">
                            <i class="fab fa-twitter text-sky-600 text-xl"></i>
                            <span class="text-sky-800 font-medium">Twitter</span>
                            <i class="fas fa-external-link-alt text-sky-500 text-sm ml-auto"></i>
                        </a>
                    @endif

                    @if($parametres->website_url)
                        <a href="{{ $parametres->website_url }}" target="_blank" class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <i class="fas fa-globe text-gray-600 text-xl"></i>
                            <span class="text-gray-800 font-medium">Site Web</span>
                            <i class="fas fa-external-link-alt text-gray-500 text-sm ml-auto"></i>
                        </a>
                    @endif

                    @if(!$parametres->facebook_url && !$parametres->instagram_url && !$parametres->youtube_url && !$parametres->twitter_url && !$parametres->website_url)
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-share-alt text-2xl text-slate-400"></i>
                            </div>
                            <p class="text-slate-500">Aucun réseau social configuré</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Programmes rapides -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar-week text-amber-600 mr-2"></i>
                        Programmes Cette Semaine
                    </h2>
                </div>
                <div class="p-6">
                    @php
                        $programmesReguliers = collect($parametres->getProgrammesPublics())->where('type_horaire', 'regulier')->sortBy('ordre');
                    @endphp

                    @if($programmesReguliers->count() > 0)
                        <div class="space-y-3">
                            @foreach($programmesReguliers->take(5) as $programme)
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                            <i class="{{ $programme['icone'] ?? 'fas fa-calendar' }} text-amber-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-900 text-sm">{{ Str::limit($programme['titre'], 20) }}</p>
                                            <p class="text-xs text-slate-500">{{ $programme['jour'] ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-slate-900 text-sm">{{ $programme['heure_debut'] ?? '' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($programmesReguliers->count() > 5)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-slate-500">
                                    et {{ $programmesReguliers->count() - 5 }} autre(s) programme(s)
                                </p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-calendar-week text-xl text-slate-400"></i>
                            </div>
                            <p class="text-slate-500 text-sm">Aucun programme régulier</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Paramètres système -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cogs text-slate-600 mr-2"></i>
                        Paramètres Système
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Devise</span>
                                <span class="text-sm font-bold text-slate-900">{{ $parametres->devise }}</span>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Langue</span>
                                <span class="text-sm font-bold text-slate-900">
                                    @switch($parametres->langue)
                                        @case('fr') Français @break
                                        @case('en') English @break
                                        @case('es') Español @break
                                        @default {{ ucfirst($parametres->langue) }}
                                    @endswitch
                                </span>
                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700">Fuseau horaire</span>
                                <span class="text-sm font-bold text-slate-900">{{ $parametres->fuseau_horaire }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Créé le</span>
                            <span class="text-xs text-slate-600">{{ $parametres->created_at->format('d/m/Y à H:i') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Modifié le</span>
                            <span class="text-xs text-slate-600">{{ $parametres->updated_at->format('d/m/Y à H:i') }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Statut</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i> Actif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions rapides sidebar -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-tools text-orange-600 mr-2"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @can('parametres.update')
                        <a href="{{ route('private.parametres.edit') }}" class="flex items-center space-x-3 p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                            <i class="fas fa-edit text-blue-600"></i>
                            <span class="text-blue-800 font-medium">Modifier les paramètres</span>
                        </a>
                    @endcan

                    <button type="button" onclick="shareParametres()" class="w-full flex items-center space-x-3 p-3 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                        <i class="fas fa-share text-green-600"></i>
                        <span class="text-green-800 font-medium">Partager</span>
                    </button>

                    <button type="button" onclick="copyToClipboard()" class="w-full flex items-center space-x-3 p-3 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors">
                        <i class="fas fa-copy text-purple-600"></i>
                        <span class="text-purple-800 font-medium">Copier les infos</span>
                    </button>

                    <button type="button" onclick="generateQRCode()" class="w-full flex items-center space-x-3 p-3 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-qrcode text-indigo-600"></i>
                        <span class="text-indigo-800 font-medium">QR Code</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les images en grand -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button type="button" onclick="closeImageModal()" class="absolute top-4 right-4 w-10 h-10 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full flex items-center justify-center text-white transition-colors z-10">
            <i class="fas fa-times text-xl"></i>
        </button>
        <img id="modalImage" src="" alt="Image agrandie" class="max-w-full max-h-full object-contain rounded-xl">
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <button type="button" onclick="downloadImage()" class="px-3 py-1 bg-white bg-opacity-20 hover:bg-opacity-30 rounded-lg text-white text-sm transition-colors">
                <i class="fas fa-download mr-1"></i> Télécharger
            </button>
        </div>
    </div>
</div>

<!-- Modal QR Code -->
<div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <!-- En-tête du modal -->
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 flex items-center">
                        <i class="fas fa-qrcode text-indigo-600 mr-2"></i>
                        QR Code - Informations Église
                    </h3>
                    <p class="text-sm text-slate-600 mt-1">{{ $parametres->nom_eglise }}</p>
                </div>
                <button type="button" onclick="closeQRModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Contenu du modal -->
        <div class="p-6">
            <!-- Zone de génération du QR code -->
            <div class="text-center mb-6">
                <div id="qrCodeContainer" class="mb-4">
                    <!-- Le QR code sera généré ici -->
                    <div class="w-48 h-48 bg-slate-100 rounded-xl flex items-center justify-center mx-auto">
                        <div class="text-center">
                            <i class="fas fa-qrcode text-4xl text-slate-400 mb-2"></i>
                            <p class="text-sm text-slate-500">Génération en cours...</p>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="bg-blue-50 rounded-xl p-4 mb-4">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                        <div class="text-left">
                            <p class="text-sm font-medium text-blue-900 mb-1">Comment utiliser ce QR code :</p>
                            <ul class="text-xs text-blue-800 space-y-1">
                                <li>• Ouvrez l'appareil photo de votre smartphone</li>
                                <li>• Pointez vers le QR code</li>
                                <li>• Appuyez sur la notification qui apparaît</li>
                                <li>• Les informations s'afficheront automatiquement</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Aperçu des données -->
                <div class="bg-slate-50 rounded-xl p-4 mb-4 text-left">
                    <h4 class="font-medium text-slate-900 mb-3 flex items-center">
                        <i class="fas fa-list text-slate-600 mr-2"></i>
                        Informations incluses :
                    </h4>
                    <div class="space-y-2 text-sm text-slate-700">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-church text-indigo-500 w-4"></i>
                            <span>{{ $parametres->nom_eglise }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-phone text-green-500 w-4"></i>
                            <span>{{ $parametres->telephone_1 }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-envelope text-blue-500 w-4"></i>
                            <span>{{ $parametres->email_principal }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-map-marker-alt text-red-500 w-4"></i>
                            <span>{{ $parametres->getAdresseComplete() }}</span>
                        </div>
                        @if($parametres->website_url)
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-globe text-purple-500 w-4"></i>
                            <span>{{ $parametres->website_url }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="grid grid-cols-2 gap-3">
                <button type="button" onclick="downloadQRCode()"
                        class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-download mr-2"></i>
                    Télécharger
                </button>

                <button type="button" onclick="printQRCode()"
                        class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-print mr-2"></i>
                    Imprimer
                </button>

                <button type="button" onclick="shareQRCode()"
                        class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-share mr-2"></i>
                    Partager
                </button>

                <button type="button" onclick="copyQRCodeData()"
                        class="flex items-center justify-center px-4 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-copy mr-2"></i>
                    Copier
                </button>
            </div>

            <!-- Information sur les formats -->
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <p class="text-xs text-gray-600 text-center">
                    <i class="fas fa-info-circle mr-1"></i>
                    Le QR code est généré au format PNG haute qualité (200x200px)
                    avec correction d'erreurs de niveau M pour une lecture optimale.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales pour le QR Code
window.qrCodeCanvas = null;
window.qrCodeData = null;

// Fonction pour générer le QR Code
function generateQRCode() {
    document.getElementById('qrModal').classList.remove('hidden');

    const churchInfo = {
        nom: "{{ $parametres->nom_eglise }}",
        telephone: "{{ $parametres->telephone_1 }}",
        email: "{{ $parametres->email_principal }}",
        adresse: "{{ $parametres->getAdresseComplete() }}",
        @if($parametres->website_url)
        website: "{{ $parametres->website_url }}"
        @endif
    };

    let qrText = `${churchInfo.nom}\n`;
    qrText += `📞 ${churchInfo.telephone}\n`;
    qrText += `📧 ${churchInfo.email}\n`;
    qrText += `📍 ${churchInfo.adresse}`;
    @if($parametres->website_url)
    qrText += `\n🌐 ${churchInfo.website}`;
    @endif

    window.qrCodeData = qrText;

    const container = document.getElementById('qrCodeContainer');
    container.innerHTML = `
        <div class="w-48 h-48 bg-blue-50 rounded-xl flex items-center justify-center mx-auto">
            <div class="text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-2"></div>
                <p class="text-sm text-blue-600 font-medium">Génération du QR Code...</p>
            </div>
        </div>
    `;

    generateQRCodeWithAPI(qrText, container);
}

function generateQRCodeWithAPI(qrText, container) {
    try {
        const encodedText = encodeURIComponent(qrText);
        const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodedText}&format=png&margin=10&color=1e293b&bgcolor=ffffff`;

        const displayImg = new Image();
        displayImg.crossOrigin = 'anonymous';

        displayImg.onload = function() {
            container.innerHTML = '';
            const wrapper = document.createElement('div');
            wrapper.className = 'bg-white p-4 rounded-xl inline-block shadow-lg';

            const visibleImg = displayImg.cloneNode(true);
            visibleImg.className = 'mx-auto rounded-xl shadow-lg';
            visibleImg.style.width = '200px';
            visibleImg.style.height = '200px';

            wrapper.appendChild(visibleImg);
            container.appendChild(wrapper);

            createCanvasFromImage(displayImg, qrText);
        };

        displayImg.onerror = function() {
            showQRError('Impossible de charger l\'image QR depuis l\'API');
        };

        displayImg.src = qrApiUrl;

    } catch (error) {
        showQRError('Erreur lors de la génération du QR Code');
    }
}

function createCanvasFromImage(img, qrText) {
    try {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');

        canvas.width = 200;
        canvas.height = 200;
        ctx.drawImage(img, 0, 0, 200, 200);

        window.qrCodeCanvas = canvas;
        window.qrCodeData = qrText;
    } catch (error) {
        console.error('Erreur lors de la création du canvas:', error);
    }
}

function showQRError(errorMessage = 'Erreur inconnue') {
    const container = document.getElementById('qrCodeContainer');
    container.innerHTML = `
        <div class="w-48 h-48 bg-red-50 border-2 border-red-200 rounded-xl flex items-center justify-center mx-auto">
            <div class="text-center p-4">
                <i class="fas fa-exclamation-triangle text-3xl text-red-400 mb-2"></i>
                <p class="text-sm text-red-600 font-medium mb-1">Erreur de génération</p>
                <p class="text-xs text-red-500 mb-2">${errorMessage}</p>
                <button onclick="retryQRGeneration()" class="text-xs bg-red-100 hover:bg-red-200 text-red-700 px-2 py-1 rounded transition-colors">
                    Réessayer
                </button>
            </div>
        </div>
    `;
}

function retryQRGeneration() {
    generateQRCode();
}

function downloadQRCode() {
    if (window.qrCodeCanvas) {
        try {
            const link = document.createElement('a');
            const fileName = `qr_code_${slugify("{{ $parametres->nom_eglise }}")}_${new Date().toISOString().slice(0, 10)}.png`;
            link.download = fileName;

            const dataURL = window.qrCodeCanvas.toDataURL('image/png', 1.0);
            link.href = dataURL;

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showNotification('QR Code téléchargé avec succès!', 'success');
        } catch (error) {
            showNotification('Erreur lors du téléchargement: ' + error.message, 'error');
        }
    } else {
        showNotification('Aucun QR Code disponible pour le téléchargement', 'error');
    }
}

function shareQRCode() {
    if (window.qrCodeCanvas) {
        if (navigator.share) {
            window.qrCodeCanvas.toBlob(function(blob) {
                if (blob) {
                    const file = new File([blob], `qr_code_${slugify("{{ $parametres->nom_eglise }}")}.png`, {
                        type: 'image/png'
                    });

                    navigator.share({
                        title: "QR Code - {{ $parametres->nom_eglise }}",
                        text: "Informations de contact de {{ $parametres->nom_eglise }}",
                        files: [file]
                    }).then(() => {
                        showNotification('QR Code partagé avec succès!', 'success');
                    }).catch(function(error) {
                        copyQRCodeData();
                    });
                } else {
                    copyQRCodeData();
                }
            }, 'image/png');
        } else {
            copyQRCodeData();
        }
    } else {
        showNotification('Aucun QR Code à partager', 'error');
    }
}

function copyQRCodeData() {
    if (window.qrCodeData) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(window.qrCodeData).then(function() {
                showNotification('Informations du QR Code copiées!', 'success');
            }).catch(function(error) {
                fallbackCopyTextToClipboard(window.qrCodeData);
            });
        } else {
            fallbackCopyTextToClipboard(window.qrCodeData);
        }
    } else {
        showNotification('Aucune donnée à copier', 'error');
    }
}

function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showNotification('Informations copiées!', 'success');
        } else {
            showNotification('Impossible de copier automatiquement', 'error');
        }
    } catch (err) {
        showNotification('Erreur lors de la copie', 'error');
    }

    document.body.removeChild(textArea);
}

function printQRCode() {
    if (window.qrCodeCanvas) {
        try {
            const printWindow = window.open('', '_blank');
            const qrDataURL = window.qrCodeCanvas.toDataURL('image/png', 1.0);

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>QR Code - {{ $parametres->nom_eglise }}</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            text-align: center;
                            padding: 20px;
                            margin: 0;
                        }
                        .qr-container {
                            max-width: 400px;
                            margin: 0 auto;
                            padding: 20px;
                            border: 2px solid #e5e7eb;
                            border-radius: 12px;
                        }
                        .church-name {
                            font-size: 24px;
                            font-weight: bold;
                            margin-bottom: 10px;
                            color: #1e293b;
                        }
                        .subtitle {
                            font-size: 16px;
                            color: #64748b;
                            margin-bottom: 20px;
                        }
                        .qr-image {
                            margin: 20px 0;
                        }
                        .instructions {
                            font-size: 14px;
                            color: #64748b;
                            margin-top: 15px;
                        }
                        @media print {
                            body { margin: 0; }
                            .qr-container {
                                border: 1px solid #000;
                                page-break-inside: avoid;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="qr-container">
                        <div class="church-name">{{ $parametres->nom_eglise }}</div>
                        <div class="subtitle">Informations de contact</div>
                        <div class="qr-image">
                            <img src="${qrDataURL}" alt="QR Code" style="max-width: 100%; height: auto;">
                        </div>
                        <div class="instructions">
                            Scannez ce QR code avec votre smartphone<br>
                            pour accéder aux informations de contact
                        </div>
                    </div>
                </body>
                </html>
            `);

            printWindow.document.close();
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                    printWindow.close();
                }, 250);
            };

            showNotification('Impression lancée!', 'success');
        } catch (error) {
            showNotification('Erreur lors de l\'impression', 'error');
        }
    } else {
        showNotification('Aucun QR Code à imprimer', 'error');
    }
}

function slugify(text) {
    return text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-');
}

function exportParametres() {
    const data = {
        nom_eglise: "{{ $parametres->nom_eglise }}",
        date_fondation: "{{ $parametres->date_fondation?->format('Y-m-d') }}",
        nombre_membres: {{ $parametres->nombre_membres ?: 0 }},
        contact: {
            telephone_1: "{{ $parametres->telephone_1 }}",
            telephone_2: "{{ $parametres->telephone_2 }}",
            email_principal: "{{ $parametres->email_principal }}",
            email_secondaire: "{{ $parametres->email_secondaire }}",
            adresse: "{{ $parametres->getAdresseComplete() }}"
        },
        spirituel: {
            verset_biblique: `{{ $parametres->verset_biblique }}`,
            reference_verset: "{{ $parametres->reference_verset }}",
            mission: `{{ $parametres->mission_statement }}`,
            vision: `{{ $parametres->vision }}`,
            histoire: `{{ $parametres->histoire_eglise }}`
        },
        reseaux_sociaux: {
            facebook: "{{ $parametres->facebook_url }}",
            instagram: "{{ $parametres->instagram_url }}",
            youtube: "{{ $parametres->youtube_url }}",
            twitter: "{{ $parametres->twitter_url }}",
            website: "{{ $parametres->website_url }}"
        },
        programmes: @json($parametres->getProgrammes()),
        parametres_systeme: {
            devise: "{{ $parametres->devise }}",
            langue: "{{ $parametres->langue }}",
            fuseau_horaire: "{{ $parametres->fuseau_horaire }}"
        },
        export_date: "{{ now()->format('Y-m-d H:i:s') }}"
    };

    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'parametres_eglise_{{ now()->format("Y-m-d") }}.json';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

function printParametres() {
    window.print();
}

function openImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function closeQRModal() {
    document.getElementById('qrModal').classList.add('hidden');
}

function downloadImage() {
    const img = document.getElementById('modalImage');
    const a = document.createElement('a');
    a.href = img.src;
    a.download = 'image_eglise_' + Date.now() + '.jpg';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

function shareParametres() {
    if (navigator.share) {
        navigator.share({
            title: "{{ $parametres->nom_eglise }}",
            text: "Découvrez les informations de {{ $parametres->nom_eglise }}",
            url: "{{ $parametres->website_url }}"
        }).catch(console.error);
    } else {
        copyToClipboard();
    }
}

function copyToClipboard() {
    const text = `{{ $parametres->nom_eglise }}
Contact: {{ $parametres->telephone_1 }} | {{ $parametres->email_principal }}
Adresse: {{ $parametres->getAdresseComplete() }}
@if($parametres->website_url)Site web: {{ $parametres->website_url }}@endif`;

    navigator.clipboard.writeText(text).then(() => {
        showNotification('Informations copiées dans le presse-papier!', 'success');
    }).catch(() => {
        showNotification('Erreur lors de la copie', 'error');
    });
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-xl shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Fermer les modals en cliquant en dehors
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

document.getElementById('qrModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQRModal();
    }
});

// Fermer les modals avec la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
        closeQRModal();
    }
});

// Styles d'impression
const printStyles = `
    @media print {
        .no-print { display: none !important; }
        .print-break { page-break-before: always; }
        body { font-size: 12pt; }
        h1 { font-size: 18pt; }
        h2 { font-size: 16pt; }
        h3 { font-size: 14pt; }
        .bg-gradient-to-r { background: none !important; color: #000 !important; }
        .shadow-lg, .shadow-md { box-shadow: none !important; }
        .rounded-2xl, .rounded-xl { border-radius: 8px !important; }
        .bg-white\/80 { background: white !important; }
        .border-white\/20 { border-color: #e5e7eb !important; }
    }
`;

const styleSheet = document.createElement('style');
styleSheet.textContent = printStyles;
document.head.appendChild(styleSheet);
</script>

@endsection



