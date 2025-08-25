@extends('layouts.private.main')
@section('title', 'Suivi Pastoral')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Suivi Pastoral</h1>
        <p class="text-slate-500 mt-1">Gestion et planification du suivi pastoral des membres et visiteurs</p>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Priorités
                </h2>
                <div class="flex flex-wrap gap-2">
                    <button type="button" onclick="openPlanificationModal()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-calendar-plus mr-2"></i> Planifier visite
                    </button>
                    <button type="button" onclick="exportSuivi()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i> Exporter
                    </button>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.participantscultes.suivi-pastoral') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                    <select name="priorite" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les priorités</option>
                        <option value="urgent" {{ request('priorite') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="haute" {{ request('priorite') == 'haute' ? 'selected' : '' }}>Haute</option>
                        <option value="normale" {{ request('priorite') == 'normale' ? 'selected' : '' }}>Normale</option>
                        <option value="faible" {{ request('priorite') == 'faible' ? 'selected' : '' }}>Faible</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut du suivi</label>
                    <select name="statut_suivi" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut_suivi') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="en_cours" {{ request('statut_suivi') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="programme" {{ request('statut_suivi') == 'programme' ? 'selected' : '' }}>Programmé</option>
                        <option value="termine" {{ request('statut_suivi') == 'termine' ? 'selected' : '' }}>Terminé</option>
                        <option value="reporte" {{ request('statut_suivi') == 'reporte' ? 'selected' : '' }}>Reporté</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Responsable</label>
                    <select name="responsable_id" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les responsables</option>
                        <option value="non_assigne" {{ request('responsable_id') == 'non_assigne' ? 'selected' : '' }}>Non assigné</option>
                        <!-- Options seront remplies par le contrôleur -->
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques du suivi -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['urgent'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Urgent</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-arrow-up text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['haute'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Priorité haute</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['en_attente'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">En attente</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['programme'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Programmé</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-user-friends text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $stats['non_assigne'] ?? 0 }}</p>
                    <p class="text-sm text-slate-500">Non assigné</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Planning du suivi -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Liste des suivis -->
        <div class="lg:col-span-3">
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-list text-purple-600 mr-2"></i>
                            Liste des Suivis ({{ $suivis->count() ?? 0 }})
                        </h2>
                        <div class="flex items-center space-x-2">
                            <label class="flex items-center text-sm">
                                <input type="checkbox" id="selectAllSuivi" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mr-2">
                                Tout sélectionner
                            </label>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    @if(isset($suivis) && $suivis->count() > 0)
                        <div class="space-y-4">
                            @foreach($suivis as $suivi)
                                <div class="bg-gradient-to-r from-slate-50 to-white border border-slate-200 rounded-xl p-6 hover:shadow-md transition-all duration-300">
                                    <!-- Header avec checkbox et priorité -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-start space-x-3">
                                            <input type="checkbox" name="suivis[]" value="{{ $suivi->id ?? '' }}" class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mt-1">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-bold text-slate-900 mb-1">
                                                    {{ $suivi->participant->nom ?? 'N/A' }} {{ $suivi->participant->prenom ?? '' }}
                                                </h3>
                                                <p class="text-sm text-slate-600">{{ $suivi->culte->titre ?? 'Culte supprimé' }} - {{ $suivi->culte ? \Carbon\Carbon::parse($suivi->culte->date_culte)->format('d/m/Y') : 'N/A' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end space-y-2">
                                            @php
                                                $prioriteColors = [
                                                    'urgent' => 'bg-red-100 text-red-800',
                                                    'haute' => 'bg-orange-100 text-orange-800',
                                                    'normale' => 'bg-blue-100 text-blue-800',
                                                    'faible' => 'bg-gray-100 text-gray-800'
                                                ];
                                                $statutColors = [
                                                    'en_attente' => 'bg-yellow-100 text-yellow-800',
                                                    'en_cours' => 'bg-blue-100 text-blue-800',
                                                    'programme' => 'bg-green-100 text-green-800',
                                                    'termine' => 'bg-gray-100 text-gray-800',
                                                    'reporte' => 'bg-red-100 text-red-800'
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $prioriteColors[$suivi->priorite ?? 'normale'] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($suivi->priorite ?? 'Normale') }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statutColors[$suivi->statut_suivi ?? 'en_attente'] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $suivi->statut_suivi ?? 'En attente')) }}
                                            </span>
                                            @if($suivi->date_limite ?? false)
                                                @php
                                                    $dateLimite = \Carbon\Carbon::parse($suivi->date_limite);
                                                    $joursRestants = $dateLimite->diffInDays(now(), false);
                                                @endphp
                                                @if($joursRestants > 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i> En retard
                                                    </span>
                                                @elseif($joursRestants === 0)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                        <i class="fas fa-clock mr-1"></i> Aujourd'hui
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Informations de contact -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div class="space-y-2">
                                            @if($suivi->participant->email ?? false)
                                                <div class="flex items-center text-sm text-slate-600">
                                                    <i class="fas fa-envelope w-4 mr-2"></i>
                                                    <a href="mailto:{{ $suivi->participant->email }}" class="text-blue-600 hover:underline">{{ $suivi->participant->email }}</a>
                                                </div>
                                            @endif
                                            @if($suivi->participant->telephone_1 ?? false)
                                                <div class="flex items-center text-sm text-slate-600">
                                                    <i class="fas fa-phone w-4 mr-2"></i>
                                                    <a href="tel:{{ $suivi->participant->telephone_1 }}" class="text-blue-600 hover:underline">{{ $suivi->participant->telephone_1 }}</a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="space-y-2">
                                            @if($suivi->responsable ?? false)
                                                <div class="flex items-center text-sm text-slate-600">
                                                    <i class="fas fa-user w-4 mr-2"></i>
                                                    <span>Responsable: {{ $suivi->responsable->nom ?? 'N/A' }} {{ $suivi->responsable->prenom ?? '' }}</span>
                                                </div>
                                            @endif
                                            @if($suivi->date_planifiee ?? false)
                                                <div class="flex items-center text-sm text-slate-600">
                                                    <i class="fas fa-calendar w-4 mr-2"></i>
                                                    <span>Planifié: {{ \Carbon\Carbon::parse($suivi->date_planifiee)->format('d/m/Y H:i') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Besoins identifiés -->
                                    @if($suivi->demande_contact_pastoral || $suivi->interesse_bapteme || $suivi->souhaite_devenir_membre)
                                        <div class="mb-4">
                                            <h4 class="text-sm font-semibold text-slate-700 mb-2">Besoins identifiés :</h4>
                                            <div class="flex flex-wrap gap-1">
                                                @if($suivi->demande_contact_pastoral)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                                        <i class="fas fa-praying-hands mr-1"></i> Contact pastoral
                                                    </span>
                                                @endif
                                                @if($suivi->interesse_bapteme)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-cyan-100 text-cyan-800">
                                                        <i class="fas fa-water mr-1"></i> Baptême
                                                    </span>
                                                @endif
                                                @if($suivi->souhaite_devenir_membre)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-heart mr-1"></i> Devenir membre
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Notes de suivi -->
                                    @if($suivi->notes_suivi ?? false)
                                        <div class="mb-4">
                                            <h4 class="text-sm font-semibold text-slate-700 mb-2">Notes de suivi :</h4>
                                            <div class="bg-slate-50 p-3 rounded-lg">
                                                <p class="text-sm text-slate-600">{{ $suivi->notes_suivi }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between pt-4 border-t border-slate-200">
                                        <div class="flex items-center space-x-2">
                                            <button type="button" onclick="updateSuivi('{{ $suivi->id ?? '' }}', 'en_cours')" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors" title="Marquer en cours">
                                                <i class="fas fa-play text-sm"></i>
                                            </button>

                                            <button type="button" onclick="planifierSuivi('{{ $suivi->id ?? '' }}')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Planifier">
                                                <i class="fas fa-calendar-plus text-sm"></i>
                                            </button>

                                            <button type="button" onclick="ajouterNote('{{ $suivi->id ?? '' }}')" class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors" title="Ajouter note">
                                                <i class="fas fa-sticky-note text-sm"></i>
                                            </button>

                                            <button type="button" onclick="assignerResponsable('{{ $suivi->id ?? '' }}')" class="inline-flex items-center justify-center w-8 h-8 text-orange-600 bg-orange-100 rounded-lg hover:bg-orange-200 transition-colors" title="Assigner">
                                                <i class="fas fa-user-plus text-sm"></i>
                                            </button>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-slate-500">
                                                {{ $suivi->created_at ? $suivi->created_at->diffForHumans() : 'Date inconnue' }}
                                            </span>
                                            <button type="button" onclick="marquerTermine('{{ $suivi->id ?? '' }}')" class="inline-flex items-center justify-center w-8 h-8 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors" title="Marquer terminé">
                                                <i class="fas fa-check text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination si nécessaire -->
                        @if(method_exists($suivis, 'links'))
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                                <div class="text-sm text-slate-700">
                                    Affichage de <span class="font-medium">{{ $suivis->firstItem() }}</span> à <span class="font-medium">{{ $suivis->lastItem() }}</span>
                                    sur <span class="font-medium">{{ $suivis->total() }}</span> résultats
                                </div>
                                <div>
                                    {{ $suivis->appends(request()->query())->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user-friends text-3xl text-slate-400"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun suivi en cours</h3>
                            <p class="text-slate-500 mb-6">
                                @if(request()->hasAny(['priorite', 'statut_suivi', 'responsable_id']))
                                    Aucun suivi ne correspond à vos critères de recherche.
                                @else
                                    Aucun suivi pastoral nécessaire pour le moment.
                                @endif
                            </p>
                            <button type="button" onclick="openPlanificationModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-calendar-plus mr-2"></i> Planifier un suivi
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Planning et calendrier -->
        <div class="space-y-6">
            <!-- Calendrier des suivis -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calendar text-green-600 mr-2"></i>
                        Planning Hebdomadaire
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @for($i = 0; $i < 7; $i++)
                            @php
                                $jour = now()->addDays($i);
                                $suivisDuJour = collect($suivis ?? [])->filter(function($suivi) use ($jour) {
                                    return $suivi->date_planifiee && \Carbon\Carbon::parse($suivi->date_planifiee)->isSameDay($jour);
                                });
                            @endphp
                            <div class="p-3 {{ $i === 0 ? 'bg-blue-50 border-blue-200' : 'bg-slate-50' }} rounded-lg border">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-slate-800">{{ $jour->format('l d/m') }}</h4>
                                    @if($suivisDuJour->count() > 0)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">{{ $suivisDuJour->count() }}</span>
                                    @endif
                                </div>
                                @if($suivisDuJour->count() > 0)
                                    <div class="space-y-1">
                                        @foreach($suivisDuJour->take(3) as $suivi)
                                            <div class="text-xs text-slate-600 flex items-center">
                                                <i class="fas fa-circle text-blue-500 mr-2" style="font-size: 4px;"></i>
                                                <span>{{ $suivi->participant->prenom ?? 'N/A' }} {{ substr($suivi->participant->nom ?? 'N/A', 0, 1) }}.</span>
                                                <span class="ml-1 text-slate-500">{{ \Carbon\Carbon::parse($suivi->date_planifiee)->format('H:i') }}</span>
                                            </div>
                                        @endforeach
                                        @if($suivisDuJour->count() > 3)
                                            <div class="text-xs text-slate-500">
                                                +{{ $suivisDuJour->count() - 3 }} autres...
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-xs text-slate-400">Aucun suivi prévu</p>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bolt text-yellow-600 mr-2"></i>
                        Actions Rapides
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    <button type="button" onclick="actionEnMasse('marquer_contacte')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                        <i class="fas fa-check mr-2"></i> Marquer sélectionnés comme contactés
                    </button>

                    <button type="button" onclick="actionEnMasse('assigner_responsable')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200">
                        <i class="fas fa-user-plus mr-2"></i> Assigner responsable
                    </button>

                    <button type="button" onclick="actionEnMasse('planifier')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                        <i class="fas fa-calendar-plus mr-2"></i> Planifier en masse
                    </button>

                    <button type="button" onclick="genererRapport()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200">
                        <i class="fas fa-chart-line mr-2"></i> Générer rapport
                    </button>
                </div>
            </div>

            <!-- Notifications et alertes -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-bell text-red-600 mr-2"></i>
                        Alertes
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @if(isset($alertes) && count($alertes) > 0)
                            @foreach($alertes as $alerte)
                                <div class="p-3 {{ $alerte['type'] == 'urgent' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200' }} border rounded-lg">
                                    <div class="flex items-start space-x-2">
                                        <i class="fas {{ $alerte['type'] == 'urgent' ? 'fa-exclamation-triangle text-red-500' : 'fa-clock text-yellow-500' }} mt-1"></i>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-900">{{ $alerte['titre'] ?? 'Alerte' }}</p>
                                            <p class="text-xs text-slate-600 mt-1">{{ $alerte['message'] ?? 'Message par défaut' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle text-2xl text-green-500 mb-2"></i>
                                <p class="text-sm text-slate-500">Aucune alerte</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de planification -->
<div id="planificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="p-6 border-b border-slate-200">
            <h3 class="text-lg font-semibold text-slate-900">Planifier un suivi pastoral</h3>
        </div>
        <div class="p-6 overflow-y-auto">
            <form id="planificationForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Participant</label>
                        <select name="participant_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Sélectionner un participant...</option>
                            <!-- Options seront remplies dynamiquement -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Responsable</label>
                        <select name="responsable_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Sélectionner un responsable...</option>
                            <!-- Options seront remplies dynamiquement -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date et heure</label>
                        <input type="datetime-local" name="date_planifiee" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                        <select name="priorite" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="normale">Normale</option>
                            <option value="haute">Haute</option>
                            <option value="urgent">Urgent</option>
                            <option value="faible">Faible</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de suivi</label>
                    <select name="type_suivi" required class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="visite_domicile">Visite à domicile</option>
                        <option value="appel_telephonique">Appel téléphonique</option>
                        <option value="rencontre_eglise">Rencontre à l'église</option>
                        <option value="accompagnement_spiritual">Accompagnement spirituel</option>
                        <option value="preparation_bapteme">Préparation au baptême</option>
                        <option value="integration_membre">Intégration nouveau membre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Objectifs du suivi</label>
                    <textarea name="objectifs" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" placeholder="Décrivez les objectifs de ce suivi..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none" placeholder="Notes complémentaires..."></textarea>
                </div>
            </form>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closePlanificationModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" onclick="savePlanification()" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Planifier
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Gestion de la sélection multiple
document.getElementById('selectAllSuivi').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('input[name="suivis[]"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Fonctions d'action sur les suivis
function updateSuivi(suiviId, nouveauStatut) {
    fetch(`/private/suivi-pastoral/${suiviId}/statut`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ statut: nouveauStatut })
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

function planifierSuivi(suiviId) {
    // Ouvrir le modal de planification avec le suivi pré-sélectionné
    document.getElementById('planificationModal').classList.remove('hidden');
    // Logique pour pré-remplir le formulaire
}

function ajouterNote(suiviId) {
    const note = prompt('Ajouter une note de suivi:');
    if (note) {
        fetch(`/private/suivi-pastoral/${suiviId}/note`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ note: note })
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
}

function assignerResponsable(suiviId) {
    // Ouvrir un modal ou une liste déroulante pour sélectionner le responsable
    alert('Fonctionnalité d\'assignation à implémenter');
}

function marquerTermine(suiviId) {
    if (confirm('Marquer ce suivi comme terminé ?')) {
        updateSuivi(suiviId, 'termine');
    }
}

function actionEnMasse(action) {
    const selected = getSelectedSuivis();
    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un suivi');
        return;
    }

    switch(action) {
        case 'marquer_contacte':
            if (confirm(`Marquer ${selected.length} suivi(s) comme contacté(s) ?`)) {
                // Logique de mise à jour en masse
                console.log('Marquer contactés:', selected);
            }
            break;
        case 'assigner_responsable':
            // Ouvrir modal d'assignation
            alert('Fonctionnalité d\'assignation en masse à implémenter');
            break;
        case 'planifier':
            // Ouvrir modal de planification en masse
            alert('Fonctionnalité de planification en masse à implémenter');
            break;
    }
}

function getSelectedSuivis() {
    const checkboxes = document.querySelectorAll('input[name="suivis[]"]:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

// Gestion du modal de planification
function openPlanificationModal() {
    document.getElementById('planificationModal').classList.remove('hidden');
}

function closePlanificationModal() {
    document.getElementById('planificationModal').classList.add('hidden');
    document.getElementById('planificationForm').reset();
}

function savePlanification() {
    const form = document.getElementById('planificationForm');
    const formData = new FormData(form);

    fetch('/private/suivi-pastoral/planifier', {
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
            alert('Suivi planifié avec succès !');
            closePlanificationModal();
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

function exportSuivi() {
    window.open('/private/suivi-pastoral/export', '_blank');
}

function genererRapport() {
    window.open('/private/suivi-pastoral/rapport', '_blank');
}

// Fermer les modals en cliquant à l'extérieur
document.getElementById('planificationModal').addEventListener('click', function(e) {
    if (e.target === this) closePlanificationModal();
});
</script>
@endpush
@endsection
