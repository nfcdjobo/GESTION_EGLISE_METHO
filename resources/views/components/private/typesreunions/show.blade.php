@extends('layouts.private.main')
@section('title', $typeReunion->nom)

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">{{ $typeReunion->nom }}</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.types-reunions.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Types de Réunions
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">{{ $typeReunion->code }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Statut et actions rapides -->
            <div class="flex items-center space-x-3">
                @if($typeReunion->actif && !$typeReunion->est_archive)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i>Actif
                    </span>
                @elseif($typeReunion->est_archive)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-archive mr-1"></i>Archivé
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-1"></i>Inactif
                    </span>
                @endif

                <div class="flex items-center space-x-2">
                    @can('modifier_types_reunions')
                        <a href="{{ route('private.types-reunions.edit', $typeReunion) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </a>
                    @endcan

                    @can('dupliquer_types_reunions')
                        <button type="button" onclick="duplicateType('{{ $typeReunion->id }}')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-copy mr-2"></i> Dupliquer
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Contenu principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Carte principale avec en-tête coloré -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300 overflow-hidden">
                <!-- En-tête avec couleur personnalisée -->
                <div class="h-4 bg-gradient-to-r" style="background: linear-gradient(90deg, {{ $typeReunion->couleur ?? '#3498db' }}, {{ adjustBrightness($typeReunion->couleur ?? '#3498db', -20) }})"></div>

                <div class="p-8">
                    <!-- Titre avec icône -->
                    <div class="flex items-start space-x-4 mb-6">
                        @if($typeReunion->icone)
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white shadow-lg" style="background-color: {{ $typeReunion->couleur ?? '#3498db' }}">
                                <i class="fas fa-{{ $typeReunion->icone }} text-2xl"></i>
                            </div>
                        @else
                            <div class="w-16 h-16 bg-gradient-to-br from-slate-400 to-slate-500 rounded-2xl flex items-center justify-center text-white shadow-lg">
                                <i class="fas fa-calendar text-2xl"></i>
                            </div>
                        @endif

                        <div class="flex-1">
                            <h2 class="text-2xl font-bold text-slate-800 mb-2">{{ $typeReunion->nom }}</h2>
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-tag mr-1"></i>{{ $typeReunion->code }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    {{ ucfirst($typeReunion->categorie) }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800">
                                    {{ ucfirst(str_replace('_', ' ', $typeReunion->niveau_acces)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($typeReunion->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-3">Description</h3>
                            <div class="prose max-w-none text-slate-600">
                                {!! nl2br(e($typeReunion->description)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Informations détaillées -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                                <i class="fas fa-clock text-blue-600 mr-2"></i>
                                Configuration Temporelle
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Fréquence:</span>
                                    <span class="font-medium text-slate-800">{{ ucfirst(str_replace('_', ' ', $typeReunion->frequence_type)) }}</span>
                                </div>
                                @if($typeReunion->duree_standard)
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Durée standard:</span>
                                        <span class="font-medium text-slate-800">{{ $typeReunion->duree_standard->format('H:i') }}</span>
                                    </div>
                                @endif
                                @if($typeReunion->duree_min)
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Durée min:</span>
                                        <span class="font-medium text-slate-800">{{ $typeReunion->duree_min->format('H:i') }}</span>
                                    </div>
                                @endif
                                @if($typeReunion->duree_max)
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Durée max:</span>
                                        <span class="font-medium text-slate-800">{{ $typeReunion->duree_max->format('H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                                <i class="fas fa-users text-green-600 mr-2"></i>
                                Participation
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Inscription:</span>
                                    <span class="font-medium text-slate-800">{{ $typeReunion->necessite_inscription ? 'Requise' : 'Libre' }}</span>
                                </div>
                                @if($typeReunion->a_limite_participants && $typeReunion->limite_participants)
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Limite:</span>
                                        <span class="font-medium text-slate-800">{{ $typeReunion->limite_participants }} participants</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Enfants:</span>
                                    <span class="font-medium text-slate-800">{{ $typeReunion->permet_enfants ? 'Autorisés' : 'Non autorisés' }}</span>
                                </div>
                                @if($typeReunion->age_minimum)
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Âge minimum:</span>
                                        <span class="font-medium text-slate-800">{{ $typeReunion->age_minimum }} ans</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Caractéristiques spirituelles -->
                    @if($typeReunion->inclut_louange || $typeReunion->inclut_message || $typeReunion->inclut_priere || $typeReunion->inclut_communion || $typeReunion->permet_temoignages)
                        <div class="mb-6">
                            <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                                <i class="fas fa-praying-hands text-purple-600 mr-2"></i>
                                Éléments Spirituels
                            </h4>
                            <div class="flex flex-wrap gap-2">
                                @if($typeReunion->inclut_louange)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <i class="fas fa-music mr-1"></i>Louange
                                    </span>
                                @endif
                                @if($typeReunion->inclut_message)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-microphone mr-1"></i>Message
                                    </span>
                                @endif
                                @if($typeReunion->inclut_priere)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-praying-hands mr-1"></i>Prière
                                    </span>
                                @endif
                                @if($typeReunion->inclut_communion)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        <i class="fas fa-bread-slice mr-1"></i>Communion
                                    </span>
                                @endif
                                @if($typeReunion->permet_temoignages)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">
                                        <i class="fas fa-heart mr-1"></i>Témoignages
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Aspects financiers -->
                    @if($typeReunion->collecte_offrandes || $typeReunion->a_frais_participation)
                        <div class="mb-6">
                            <h4 class="font-semibold text-slate-800 mb-3 flex items-center">
                                <i class="fas fa-money-bill text-amber-600 mr-2"></i>
                                Aspects Financiers
                            </h4>
                            <div class="space-y-2 text-sm">
                                @if($typeReunion->collecte_offrandes)
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-600 mr-2"></i>
                                        <span class="text-slate-700">Collecte d'offrandes</span>
                                    </div>
                                @endif
                                @if($typeReunion->a_frais_participation)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-check text-green-600 mr-2"></i>
                                            <span class="text-slate-700">Frais de participation</span>
                                        </div>
                                        @if($typeReunion->frais_standard)
                                            <span class="font-medium text-slate-800">{{ number_format($typeReunion->frais_standard, 0) }} XOF</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques d'utilisation -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-line text-green-600 mr-2"></i>
                        Statistiques d'Utilisation
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $stats['nombre_reunions_totales'] }}</div>
                            <div class="text-sm text-slate-600">Réunions totales</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $stats['nombre_reunions_a_venir'] }}</div>
                            <div class="text-sm text-slate-600">À venir</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $stats['dernier_mois'] }}</div>
                            <div class="text-sm text-slate-600">Ce mois</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $stats['moyenne_participants'] ? number_format($stats['moyenne_participants'], 1) : '-' }}</div>
                            <div class="text-sm text-slate-600">Moy. participants</div>
                        </div>
                    </div>

                    @if($typeReunion->derniere_utilisation)
                        <div class="mt-4 p-3 bg-slate-50 rounded-lg">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-slate-600">Dernière utilisation:</span>
                                <span class="font-medium text-slate-800">{{ $typeReunion->derniere_utilisation->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm mt-1">
                                <span class="text-slate-600">Statut d'utilisation:</span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($stats['statut_utilisation'] === 'Récent') bg-green-100 text-green-800
                                    @elseif($stats['statut_utilisation'] === 'Modéré') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $stats['statut_utilisation'] }}
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="mt-4 p-3 bg-slate-50 rounded-lg text-center">
                            <span class="text-slate-500 italic">Ce type de réunion n'a jamais été utilisé</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informations système -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations Système
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-sm">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-slate-600">Priorité:</span>
                            <div class="flex items-center">
                                @for($i = 1; $i <= $typeReunion->priorite; $i++)
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                @endfor
                                @for($i = $typeReunion->priorite + 1; $i <= 10; $i++)
                                    <i class="far fa-star text-slate-300 text-xs"></i>
                                @endfor
                                <span class="ml-2 font-medium text-slate-800">{{ $typeReunion->priorite }}/10</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-slate-600">Ordre d'affichage:</span>
                            <span class="font-medium text-slate-800">{{ $typeReunion->ordre_affichage }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-slate-600">Utilisations:</span>
                            <span class="font-medium text-slate-800">{{ $typeReunion->nombre_utilisations }}</span>
                        </div>
                    </div>

                    <div class="border-t border-slate-200 pt-4">
                        <h4 class="font-medium text-slate-800 mb-2">Affichage</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center">
                                <i class="fas fa-{{ $typeReunion->afficher_calendrier_public ? 'check' : 'times' }} text-{{ $typeReunion->afficher_calendrier_public ? 'green' : 'red' }}-600 mr-2"></i>
                                <span class="text-slate-700">Calendrier public</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-{{ $typeReunion->afficher_site_web ? 'check' : 'times' }} text-{{ $typeReunion->afficher_site_web ? 'green' : 'red' }}-600 mr-2"></i>
                                <span class="text-slate-700">Site web</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Responsable -->
            @if($typeReunion->responsableType)
                <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                    <div class="p-6 border-b border-slate-200">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-user-tie text-green-600 mr-2"></i>
                            Responsable
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ substr($typeReunion->responsableType->prenom, 0, 1) }}{{ substr($typeReunion->responsableType->nom, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-medium text-slate-800">{{ $typeReunion->responsableType->nom }} {{ $typeReunion->responsableType->prenom }}</div>
                                <div class="text-sm text-slate-500">{{ $typeReunion->responsableType->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Métadonnées -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-clock text-slate-600 mr-2"></i>
                        Métadonnées
                    </h2>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    <div>
                        <span class="text-slate-600">Créé le:</span>
                        <div class="font-medium text-slate-800">{{ $typeReunion->created_at->format('d/m/Y à H:i') }}</div>
                        @if($typeReunion->createurType)
                            <div class="text-slate-500">par {{ $typeReunion->createurType->nom }} {{ $typeReunion->createurType->prenom }}</div>
                        @endif
                    </div>

                    @if($typeReunion->updated_at->gt($typeReunion->created_at))
                        <div class="border-t border-slate-200 pt-3">
                            <span class="text-slate-600">Modifié le:</span>
                            <div class="font-medium text-slate-800">{{ $typeReunion->updated_at->format('d/m/Y à H:i') }}</div>
                            @if($typeReunion->modificateur)
                                <div class="text-slate-500">par {{ $typeReunion->modificateur->nom }} {{ $typeReunion->modificateur->prenom }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-tools text-purple-600 mr-2"></i>
                        Actions
                    </h2>
                </div>
                <div class="p-6 space-y-3">
                    @if($typeReunion->actif && !$typeReunion->est_archive)
                        @can('desactiver_types_reunions')
                            <button type="button" onclick="toggleStatus('{{ $typeReunion->id }}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-xl hover:bg-orange-700 transition-colors">
                                <i class="fas fa-pause mr-2"></i> Désactiver
                            </button>
                        @endcan
                    @else
                        @can('activer_types_reunions')
                            <button type="button" onclick="toggleStatus('{{ $typeReunion->id }}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                <i class="fas fa-play mr-2"></i> Activer
                            </button>
                        @endcan
                    @endif

                    @if(!$typeReunion->est_archive)
                        @can('archiver_types_reunions')
                            <button type="button" onclick="archiveType('{{ $typeReunion->id }}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                <i class="fas fa-archive mr-2"></i> Archiver
                            </button>
                        @endcan
                    @else
                        @can('restaurer_types_reunions')
                            <button type="button" onclick="restoreType('{{ $typeReunion->id }}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-undo mr-2"></i> Restaurer
                            </button>
                        @endcan
                    @endif

                    @can('supprimer_types_reunions')
                        @if($typeReunion->reunions()->count() == 0)
                            <button type="button" onclick="deleteType('{{ $typeReunion->id }}')" class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-2"></i> Supprimer
                            </button>
                        @else
                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-amber-600 mr-2"></i>
                                    <span class="text-sm text-amber-800">Ce type est utilisé par {{ $typeReunion->reunions()->count() }} réunion(s)</span>
                                </div>
                            </div>
                        @endif
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Dupliquer un type
function duplicateType(typeId) {
    if (confirm('Dupliquer ce type de réunion ?')) {
        fetch(`{{ route('private.types-reunions.dupliquer', ':type')}}`.replace(':type', typeId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.data && data.data.id) {
                    window.location.href = `{{ route('private.types-reunions.show', ':id') }}`.replace(':id', data.data.id);
                } else {
                    location.reload();
                }
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

// Basculer le statut
function toggleStatus(typeId) {
    const isActive = "{{ $typeReunion->actif ? 'true' : 'false' }}";
    const action = isActive ? 'desactiver' : 'activer';
    const message = isActive ? 'Désactiver ce type de réunion ?' : 'Activer ce type de réunion ?';
    @php
        $isActive = $typeReunion->actif ? true : false;
        $routeName = "private.types-reunions.".($isActive ? 'desactiver' : 'activer' );
    @endphp

    if (confirm(message)) {
        fetch(`{{ route($routeName, ':type')}}`.replace(':type', typeId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        });
    }
}

// Archiver un type
function archiveType(typeId) {
    if (confirm('Archiver ce type de réunion ? Il ne sera plus affiché dans les listes actives.')) {
        fetch(`{{ route('private.types-reunions.archiver', ':type')}}`.replace(':type', typeId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        });
    }
}

// Restaurer un type
function restoreType(typeId) {
    if (confirm('Restaurer ce type de réunion ?')) {
        fetch(`{{ route('private.types-reunions.restaurer', ':type')}}`.replace(':type', typeId), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        });
    }
}

// Supprimer un type
function deleteType(typeId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer définitivement ce type de réunion ? Cette action est irréversible.')) {
        fetch(`{{ route('private.types-reunions.destroy', ':type')}}`.replace(':type', typeId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route('private.types-reunions.index') }}';
            } else {
                alert(data.message || 'Une erreur est survenue');
            }
        });
    }
}
</script>
@endpush


@endsection
