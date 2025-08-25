@extends('layouts.private.main')
@section('title', 'Liste d\'Attente - ' . $event->titre)

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Liste d'Attente</h1>
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
                                <a href="{{ route('private.events.show', $event) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                    {{ Str::limit($event->titre, 20) }}
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <a href="{{ route('private.events.inscriptions', $event) }}" class="text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                    Inscriptions
                                </a>
                            </div>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">Liste d'attente</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <p class="text-slate-600 mt-1">{{ $event->sous_titre ?? 'Gestion de la liste d\'attente' }}</p>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('private.events.inscriptions', $event) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-users mr-2"></i> Toutes les inscriptions
                </a>

                <a href="{{ route('private.events.show', $event) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 text-white text-sm font-medium rounded-xl hover:from-slate-700 hover:to-slate-800 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à l'événement
                </a>

                @can('events.manage_inscriptions')
                    @if($inscriptionsListeAttente->count() > 0)
                        <button type="button" onclick="promoteAll()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-level-up-alt mr-2"></i> Promouvoir par ordre
                        </button>
                    @endif
                @endcan
            </div>
        </div>
    </div>

    <!-- Informations sur l'événement et la liste d'attente -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Informations événement -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    Événement
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-calendar text-blue-600 mt-1"></i>
                    <div>
                        <div class="font-semibold text-slate-900">{{ $event->titre }}</div>
                        <div class="text-sm text-slate-600">{{ $event->date_debut->format('l d F Y') }} à {{ $event->heure_debut ? $event->heure_debut->format('H:i') : '--' }}</div>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <i class="fas fa-map-marker-alt text-red-600 mt-1"></i>
                    <div>
                        <div class="font-semibold text-slate-900">{{ $event->lieu_nom }}</div>
                        @if($event->lieu_ville)
                            <div class="text-sm text-slate-600">{{ $event->lieu_ville }}</div>
                        @endif
                    </div>
                </div>

                @if($event->capacite_totale)
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-users text-purple-600 mt-1"></i>
                        <div>
                            <div class="font-semibold text-slate-900">Capacité: {{ $event->capacite_totale }} places</div>
                            <div class="text-sm text-slate-600">{{ $event->nombre_inscrits }} inscrits actifs</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistiques liste d'attente -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-hourglass-half text-orange-600 mr-2"></i>
                    Liste d'Attente
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-orange-50 rounded-xl">
                        <div class="text-3xl font-bold text-orange-600">{{ $inscriptionsListeAttente->count() }}</div>
                        <div class="text-sm text-orange-800">En attente</div>
                    </div>
                    @if($event->capacite_totale)
                        <div class="text-center p-4 bg-green-50 rounded-xl">
                            <div class="text-3xl font-bold text-green-600">{{ max(0, $event->capacite_totale - $event->nombre_inscrits) }}</div>
                            <div class="text-sm text-green-800">Places disponibles</div>
                        </div>
                    @endif
                </div>

                @if($event->capacite_totale && $inscriptionsListeAttente->count() > 0)
                    <div class="mt-4">
                        <div class="text-sm text-slate-600 mb-2">
                            @php
                                $placesDisponibles = max(0, $event->capacite_totale - $event->nombre_inscrits);
                                $peutPromouvoir = min($placesDisponibles, $inscriptionsListeAttente->count());
                            @endphp
                            @if($peutPromouvoir > 0)
                                <span class="text-green-600 font-medium">{{ $peutPromouvoir }} personne(s) peut/peuvent être promue(s)</span>
                            @else
                                <span class="text-orange-600 font-medium">Aucune place disponible actuellement</span>
                            @endif
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $event->capacite_totale > 0 ? ($inscriptionsListeAttente->count() / $event->capacite_totale) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Liste d'attente -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list-ol text-purple-600 mr-2"></i>
                    Personnes en Attente ({{ $inscriptionsListeAttente->count() }})
                </h2>
                @if($inscriptionsListeAttente->count() > 0)
                    <div class="text-sm text-slate-500">
                        Classé par ordre d'inscription
                    </div>
                @endif
            </div>
        </div>
        <div class="p-6">
            @if($inscriptionsListeAttente->count() > 0)
                <div class="space-y-4">
                    @foreach($inscriptionsListeAttente as $index => $inscription)
                        <div class="flex items-center space-x-4 p-4 border border-slate-200 rounded-xl hover:shadow-md transition-all duration-200 {{ $index < ($event->capacite_totale ? max(0, $event->capacite_totale - $event->nombre_inscrits) : 0) ? 'bg-green-50 border-green-200' : 'bg-orange-50' }}">
                            <!-- Position -->
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 {{ $index < ($event->capacite_totale ? max(0, $event->capacite_totale - $event->nombre_inscrits) : 0) ? 'bg-green-500' : 'bg-orange-500' }} rounded-full flex items-center justify-center text-white font-bold">
                                    {{ $index + 1 }}
                                </div>
                            </div>

                            <!-- Avatar et informations participant -->
                            <div class="flex items-center space-x-3 flex-1">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold">{{ substr($inscription->inscrit->prenom, 0, 1) }}{{ substr($inscription->inscrit->nom, 0, 1) }}</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-slate-900">{{ $inscription->inscrit->prenom }} {{ $inscription->inscrit->nom }}</h3>
                                    <div class="text-sm text-slate-600">{{ $inscription->inscrit->email }}</div>
                                    @if($inscription->inscrit->telephone_1)
                                        <div class="text-sm text-slate-500">{{ $inscription->inscrit->telephone_1 }}</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Informations inscription -->
                            <div class="text-center">
                                <div class="text-sm text-slate-600">Inscrit le</div>
                                <div class="font-medium text-slate-900">{{ $inscription->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $inscription->created_at->format('H:i') }}</div>
                            </div>

                            <!-- Temps d'attente -->
                            <div class="text-center">
                                <div class="text-sm text-slate-600">Attente</div>
                                <div class="font-medium text-slate-900">{{ $inscription->created_at->diffInDays(now()) }} jour(s)</div>
                                @if($inscription->createur && $inscription->createur->id !== $inscription->inscrit->id)
                                    <div class="text-xs text-slate-500">Par {{ $inscription->createur->prenom }}</div>
                                @endif
                            </div>

                            <!-- Indicateur de promotion -->
                            <div class="text-center">
                                @if($index < ($event->capacite_totale ? max(0, $event->capacite_totale - $event->nombre_inscrits) : 0))
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-arrow-up mr-1"></i> À promouvoir
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-hourglass-half mr-1"></i> En attente
                                    </span>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2">
                                @can('events.manage_inscriptions')
                                    @if($event->capacite_totale && ($event->capacite_totale - $event->nombre_inscrits) > 0)
                                        <button type="button" onclick="promoteInscription('{{ $inscription->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Promouvoir">
                                            <i class="fas fa-level-up-alt text-sm"></i>
                                        </button>
                                    @endif
                                    <button type="button" onclick="cancelWaitingInscription('{{ $inscription->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Annuler">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($event->capacite_totale && ($event->capacite_totale - $event->nombre_inscrits) > 0 && $inscriptionsListeAttente->count() > 0)
                    <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-green-800">Promotions automatiques</h3>
                                <p class="text-sm text-green-600">{{ min($event->capacite_totale - $event->nombre_inscrits, $inscriptionsListeAttente->count()) }} personne(s) peut/peuvent être promue(s) par ordre d'arrivée</p>
                            </div>
                            @can('events.manage_inscriptions')
                                <button type="button" onclick="promoteAll()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                                    <i class="fas fa-level-up-alt mr-2"></i> Promouvoir {{ min($event->capacite_totale - $event->nombre_inscrits, $inscriptionsListeAttente->count()) }} personne(s)
                                </button>
                            @endcan
                        </div>
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hourglass text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Liste d'attente vide</h3>
                    <p class="text-slate-500">
                        @if($event->capacite_totale && $event->nombre_inscrits < $event->capacite_totale)
                            Il reste {{ $event->capacite_totale - $event->nombre_inscrits }} place(s) disponible(s).
                        @else
                            L'événement est complet mais aucune personne n'est en liste d'attente.
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Promouvoir une inscription
function promoteInscription(inscriptionId) {
    if (!confirm('Promouvoir cette inscription de la liste d\'attente ?')) return;

    fetch(`{{ route('private.events.liste-attente', $event) }}/${inscriptionId}/promote`, {
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

// Promouvoir toutes les inscriptions possibles
function promoteAll() {
    const placesDisponibles = {{ $event->capacite_totale ? max(0, $event->capacite_totale - $event->nombre_inscrits) : 0 }};
    const enAttente = {{ $inscriptionsListeAttente->count() }};
    const aPromouvoir = Math.min(placesDisponibles, enAttente);

    if (!confirm(`Promouvoir les ${aPromouvoir} première(s) personne(s) de la liste d'attente ?`)) return;

    fetch(`{{ route('private.events.liste-attente', $event) }}/promote-all`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            nombre: aPromouvoir
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

// Annuler une inscription en attente
function cancelWaitingInscription(inscriptionId) {
    if (!confirm('Annuler cette inscription en liste d\'attente ?')) return;

    fetch(`{{ route('private.events.liste-attente', $event) }}/${inscriptionId}/cancel`, {
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
</script>

@endsection
