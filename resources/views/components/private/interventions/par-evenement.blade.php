@extends('layouts.private.main')
@section('title', 'Interventions par Événement')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                @if($evenement)
                    Interventions - {{ $evenement->nom }}
                @else
                    Interventions par Événement
                @endif
            </h1>
            <p class="text-slate-500 mt-1">
                Programme détaillé des interventions - {{ \Carbon\Carbon::now()->format('l d F Y') }}
            </p>
        </div>
    </div>

    @if($evenement)
        <!-- Informations sur l'événement -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        @if(class_basename($evenement) === 'Culte')
                            <i class="fas fa-church text-blue-600 mr-2"></i>
                            Détails du Culte
                        @else
                            <i class="fas fa-users text-green-600 mr-2"></i>
                            Détails de la Réunion
                        @endif
                    </h2>
                    <div class="flex gap-2">
                        <a href="{{ route('private.interventions.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Retour
                        </a>
                        @can('interventions.create')
                        <a href="{{ route('private.interventions.create') }}?{{ class_basename($evenement) === 'Culte' ? 'culte_id' : 'reunion_id' }}={{ $evenement->id }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Ajouter Intervention
                        </a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Nom de l'événement -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-{{ class_basename($evenement) === 'Culte' ? 'blue' : 'green' }}-500 to-{{ class_basename($evenement) === 'Culte' ? 'cyan' : 'emerald' }}-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-{{ class_basename($evenement) === 'Culte' ? 'church' : 'users' }} text-white text-xl"></i>
                        </div>
                        <div class="text-lg font-bold text-slate-800">{{ $evenement->nom }}</div>
                        <div class="text-sm text-slate-500">
                            {{ class_basename($evenement) === 'Culte' ? 'Culte' : 'Réunion' }}
                        </div>
                    </div>

                    <!-- Date -->
                    @if((class_basename($evenement) === 'Culte' && $evenement->date_culte) || (class_basename($evenement) === 'Reunion' && $evenement->date_reunion))
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-calendar text-white text-xl"></i>
                            </div>
                            <div class="text-lg font-bold text-slate-800">
                                {{ class_basename($evenement) === 'Culte' ? $evenement->date_culte->format('d/m/Y') : $evenement->date_reunion->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-slate-500">
                                {{ class_basename($evenement) === 'Culte' ? $evenement->date_culte->format('l') : $evenement->date_reunion->format('l') }}
                            </div>
                        </div>
                    @endif

                    <!-- Nombre d'interventions -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-microphone text-white text-xl"></i>
                        </div>
                        <div class="text-lg font-bold text-slate-800">{{ $interventions->count() }}</div>
                        <div class="text-sm text-slate-500">Interventions</div>
                    </div>

                    <!-- Durée totale -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-clock text-white text-xl"></i>
                        </div>
                        <div class="text-lg font-bold text-slate-800">{{ $interventions->sum('duree_minutes') }}</div>
                        <div class="text-sm text-slate-500">Minutes au total</div>
                    </div>
                </div>

                <!-- Description de l'événement -->
                @if($evenement->description)
                    <div class="mt-6 p-4 bg-gradient-to-r from-slate-50 to-blue-50 rounded-xl border border-slate-200">
                        <h3 class="text-lg font-semibold text-slate-800 mb-2 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Description
                        </h3>
                        <p class="text-slate-700 leading-relaxed">{{ $evenement->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sélecteur d'événement alternatif -->
    @else
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-search text-purple-600 mr-2"></i>
                    Sélectionner un Événement
                </h2>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('private.interventions.par-evenement') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Culte</label>
                        <select name="culte_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Sélectionner un culte</option>
                            <!-- Il faudrait passer les cultes depuis le controller -->
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Réunion</label>
                        <select name="reunion_id" class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Sélectionner une réunion</option>
                            <!-- Il faudrait passer les réunions depuis le controller -->
                        </select>
                    </div>
                    <div class="md:col-span-2 flex justify-center">
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-search mr-2"></i> Voir les Interventions
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Programme des interventions -->
    @if($interventions->count() > 0)
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-list-ol text-indigo-600 mr-2"></i>
                        Programme des Interventions
                    </h2>
                    <div class="text-sm text-slate-600">
                        {{ $interventions->count() }} intervention(s) •
                        {{ $interventions->sum('duree_minutes') }} minutes
                    </div>
                </div>
            </div>
            <div class="p-6">
                <!-- Timeline des interventions -->
                <div class="space-y-6">
                    @php $cumulativeTime = 0; @endphp
                    @foreach($interventions as $index => $intervention)
                        @php
                            $isLastItem = $index === $interventions->count() - 1;
                            $cumulativeTime += $intervention->duree_minutes;
                        @endphp

                        <div class="relative {{ !$isLastItem ? 'pb-6' : '' }}">
                            <!-- Timeline line -->
                            @if(!$isLastItem)
                                <div class="absolute left-8 top-16 w-0.5 h-full bg-gradient-to-b from-slate-300 to-transparent"></div>
                            @endif

                            <div class="flex items-start space-x-4">
                                <!-- Timeline icon -->
                                <div class="flex-shrink-0 relative z-10">
                                    <div class="w-16 h-16 rounded-full flex items-center justify-center text-white font-bold text-lg
                                        @if($intervention->statut == 'terminee')
                                            bg-gradient-to-r from-green-500 to-emerald-500
                                        @elseif($intervention->statut == 'annulee')
                                            bg-gradient-to-r from-red-500 to-pink-500
                                        @else
                                            bg-gradient-to-r from-blue-500 to-purple-500
                                        @endif">
                                        @if($intervention->ordre_passage)
                                            {{ $intervention->ordre_passage }}
                                        @else
                                            <i class="fas fa-microphone"></i>
                                        @endif
                                    </div>
                                </div>

                                <!-- Intervention card -->
                                <div class="flex-1 bg-gradient-to-r from-slate-50 to-blue-50 rounded-xl border border-slate-200 hover:shadow-lg transition-all duration-200 hover:border-blue-300">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $intervention->titre }}</h3>
                                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                        @if($intervention->type_intervention == 'predication') bg-purple-100 text-purple-800
                                                        @elseif($intervention->type_intervention == 'louange') bg-yellow-100 text-yellow-800
                                                        @elseif($intervention->type_intervention == 'priere') bg-blue-100 text-blue-800
                                                        @elseif($intervention->type_intervention == 'temoignage') bg-pink-100 text-pink-800
                                                        @else bg-gray-100 text-gray-800
                                                        @endif">
                                                        <i class="fas fa-tag mr-1"></i>
                                                        {{ $intervention->type_intervention_label }}
                                                    </span>

                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                        @if($intervention->statut == 'terminee') bg-green-100 text-green-800
                                                        @elseif($intervention->statut == 'annulee') bg-red-100 text-red-800
                                                        @else bg-yellow-100 text-yellow-800
                                                        @endif">
                                                        @if($intervention->statut == 'terminee')
                                                            <i class="fas fa-check mr-1"></i>
                                                        @elseif($intervention->statut == 'annulee')
                                                            <i class="fas fa-times mr-1"></i>
                                                        @else
                                                            <i class="fas fa-clock mr-1"></i>
                                                        @endif
                                                        {{ $intervention->statut_label }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex items-center space-x-2 ml-4">
                                                <a href="{{ route('private.interventions.show', $intervention) }}" class="inline-flex items-center justify-center w-10 h-10 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @can('interventions.update')
                                                <a href="{{ route('private.interventions.edit', $intervention) }}" class="inline-flex items-center justify-center w-10 h-10 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endcan
                                            </div>
                                        </div>

                                        <!-- Informations détaillées -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                                    {{ strtoupper(substr($intervention->intervenant->nom, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-slate-800">{{ $intervention->intervenant->nom }}</div>
                                                    <div class="text-xs text-slate-500">Intervenant</div>
                                                </div>
                                            </div>

                                            @if($intervention->heure_debut)
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-clock text-blue-500"></i>
                                                    <div>
                                                        <div class="text-sm font-medium text-slate-800">{{ $intervention->heure_debut->format('H:i') }}</div>
                                                        <div class="text-xs text-slate-500">Début</div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="flex items-center space-x-2">
                                                <i class="fas fa-stopwatch text-green-500"></i>
                                                <div>
                                                    <div class="text-sm font-medium text-slate-800">{{ $intervention->duree_minutes }} min</div>
                                                    <div class="text-xs text-slate-500">Durée</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($intervention->description)
                                            <div class="mb-4">
                                                <p class="text-slate-600 text-sm leading-relaxed">{{ $intervention->description }}</p>
                                            </div>
                                        @endif

                                        @if($intervention->passage_biblique)
                                            <div class="flex items-center p-3 bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg border border-purple-200">
                                                <i class="fas fa-book-open text-purple-600 mr-2"></i>
                                                <span class="text-purple-800 font-medium">{{ $intervention->passage_biblique }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Résumé du programme -->
                <div class="mt-8 p-6 bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl border border-indigo-200">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-indigo-600 mr-2"></i>
                        Résumé du Programme
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-indigo-800">{{ $interventions->count() }}</div>
                            <div class="text-sm text-indigo-600">Interventions totales</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-800">{{ $interventions->where('statut', 'terminee')->count() }}</div>
                            <div class="text-sm text-green-600">Terminées</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-800">{{ $interventions->where('statut', 'prevue')->count() }}</div>
                            <div class="text-sm text-yellow-600">Prévues</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-slate-800">{{ $interventions->sum('duree_minutes') }}</div>
                            <div class="text-sm text-slate-600">Minutes au total</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif($evenement)
        <!-- Aucune intervention trouvée -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
            <div class="p-12 text-center">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-microphone-slash text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune intervention programmée</h3>
                <p class="text-slate-500 mb-6">
                    Cet événement n'a encore aucune intervention de planifiée.
                </p>
                <div class="flex justify-center gap-3">
                    @can('interventions.create')
                    <a href="{{ route('private.interventions.create') }}?{{ class_basename($evenement) === 'Culte' ? 'culte_id' : 'reunion_id' }}={{ $evenement->id }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-plus mr-2"></i> Ajouter une Intervention
                    </a>
                    @endcan
                    <a href="{{ route('private.interventions.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-list mr-2"></i> Voir Toutes les Interventions
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
/* Animation pour la timeline */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.relative {
    animation: fadeInUp 0.6s ease-out forwards;
}

.relative:nth-child(2) { animation-delay: 0.1s; }
.relative:nth-child(3) { animation-delay: 0.2s; }
.relative:nth-child(4) { animation-delay: 0.3s; }
.relative:nth-child(5) { animation-delay: 0.4s; }
.relative:nth-child(6) { animation-delay: 0.5s; }
</style>

@endsection
