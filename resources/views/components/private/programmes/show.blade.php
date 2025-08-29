@extends('layouts.private.main')
@section('title', 'Détails du Programme')

@section('content')
<div class="space-y-8">
    <!-- Header avec actions -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">{{ $programme->nom_programme }}</h1>
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('private.programmes.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Programmes
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                                <span class="text-sm font-medium text-slate-500">{{ $programme->code_programme }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="flex flex-wrap gap-2">
                @if($programme->peutEtreModifie())
                    <a href="{{ route('private.programmes.edit', $programme) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-yellow-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                @endif

                @if($programme->statut === 'planifie')
                    <button type="button" onclick="changerStatut('activer')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-play mr-2"></i> Activer
                    </button>
                @endif

                @if($programme->statut === 'actif')
                    <button type="button" onclick="changerStatut('suspendre')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white text-sm font-medium rounded-xl hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-pause mr-2"></i> Suspendre
                    </button>
                @endif

                @if($programme->statut === 'suspendu')
                    <button type="button" onclick="changerStatut('activer')" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 text-white text-sm font-medium rounded-xl hover:from-green-600 hover:to-emerald-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-play mr-2"></i> Réactiver
                    </button>
                @endif

                <button type="button" onclick="dupliquerProgramme()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-blue-500 text-white text-sm font-medium rounded-xl hover:from-cyan-600 hover:to-blue-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-copy mr-2"></i> Dupliquer
                </button>

                @if($programme->peutEtreModifie())
                    <button type="button" onclick="supprimerProgramme()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white text-sm font-medium rounded-xl hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Informations générales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations Générales
                    </h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-slate-700 mb-1">Nom du programme</dt>
                            <dd class="text-lg font-semibold text-slate-900">{{ $programme->nom_programme }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-700 mb-1">Code programme</dt>
                            <dd><code class="px-3 py-1 text-sm bg-slate-100 text-slate-800 rounded-lg">{{ $programme->code_programme }}</code></dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-700 mb-1">Type</dt>
                            <dd>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-tag mr-2"></i>
                                    {{ \App\Models\Programme::TYPES_PROGRAMME[$programme->type_programme] ?? $programme->type_programme }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-700 mb-1">Audience ciblée</dt>
                            <dd>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800">
                                    <i class="fas fa-users mr-2"></i>
                                    {{ \App\Models\Programme::AUDIENCES[$programme->audience_cible] ?? $programme->audience_cible }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-700 mb-1">Statut</dt>
                            <dd>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $programme->statut_badge }}-100 text-{{ $programme->statut_badge }}-800">
                                    <i class="fas fa-circle mr-2"></i>
                                    {{ \App\Models\Programme::STATUTS[$programme->statut] ?? $programme->statut }}
                                </span>
                            </dd>
                        </div>

                        @if($programme->lieu_principal)
                        <div>
                            <dt class="text-sm font-medium text-slate-700 mb-1">Lieu principal</dt>
                            <dd class="text-slate-900">
                                <i class="fas fa-map-marker-alt text-slate-500 mr-2"></i>
                                {{ $programme->lieu_principal }}
                            </dd>
                        </div>
                        @endif
                    </dl>

                    @if($programme->description)
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-slate-700 mb-2">Description</dt>
                        <dd class="text-slate-700 bg-slate-50 p-4 rounded-lg">{{ $programme->description }}</dd>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Planification -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-clock text-green-600 mr-2"></i>
                        Planification
                    </h2>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-slate-700 mb-1">Fréquence</dt>
                            <dd>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-repeat mr-2"></i>
                                    {{ \App\Models\Programme::FREQUENCES[$programme->frequence] ?? $programme->frequence }}
                                </span>
                            </dd>
                        </div>

                        @if($programme->heure_debut && $programme->heure_fin)
                        <div>
                            <dt class="text-sm font-medium text-slate-700 mb-1">Horaires</dt>
                            <dd class="text-slate-900 font-medium">
                                <i class="fas fa-clock text-slate-500 mr-2"></i>
                                {{ $programme->horaires }}
                            </dd>
                        </div>
                        @endif

                        @if($programme->jours_semaine)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-slate-700 mb-2">Jours de la semaine</dt>
                            <dd>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($programme->jours_semaine as $jour)
                                        @php
                                            $jours = [1 => 'Lundi', 2 => 'Mardi', 3 => 'Mercredi', 4 => 'Jeudi', 5 => 'Vendredi', 6 => 'Samedi', 7 => 'Dimanche'];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-800">
                                            {{ $jours[$jour] ?? $jour }}
                                        </span>
                                    @endforeach
                                </div>
                            </dd>
                        </div>
                        @endif

                        @if($programme->date_debut)
                        <div>
                            <dt class="text-sm font-medium text-slate-700 mb-1">Date de début</dt>
                            <dd class="text-slate-900">
                                <i class="fas fa-calendar-check text-slate-500 mr-2"></i>
                                {{ $programme->date_debut->format('d/m/Y') }}
                            </dd>
                        </div>
                        @endif

                        @if($programme->date_fin)
                        <div>
                            <dt class="text-sm font-medium text-slate-700 mb-1">Date de fin</dt>
                            <dd class="text-slate-900">
                                <i class="fas fa-calendar-times text-slate-500 mr-2"></i>
                                {{ $programme->date_fin->format('d/m/Y') }}
                            </dd>
                        </div>
                        @else
                        <div>
                            <dt class="text-sm font-medium text-slate-700 mb-1">Durée</dt>
                            <dd>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-infinity mr-2"></i>
                                    Programme permanent
                                </span>
                            </dd>
                        </div>
                        @endif

                        @if($programme->estEnCours() && $programme->obtenirProchainOccurrence())
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-slate-700 mb-1">Prochaine occurrence</dt>
                            <dd class="text-slate-900 font-medium">
                                <i class="fas fa-arrow-right text-blue-500 mr-2"></i>
                                {{ $programme->obtenirProchainOccurrence()->format('l d F Y') }}
                                @if($programme->heure_debut)
                                    à {{ $programme->heure_debut->format('H:i') }}
                                @endif
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Notes -->
            @if($programme->notes)
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-sticky-note text-amber-600 mr-2"></i>
                        Notes
                    </h2>
                </div>
                <div class="p-6">
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <p class="text-amber-800">{{ $programme->notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Responsable -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-user-tie text-purple-600 mr-2"></i>
                        Responsable
                    </h2>
                </div>
                <div class="p-6">
                    @if($programme->responsablePrincipal)
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-lg"></i>
                            </div>
                            <div>
                                <div class="font-semibold text-slate-900">
                                    {{ $programme->responsablePrincipal->prenom }} {{ $programme->responsablePrincipal->nom }}
                                </div>
                                @if($programme->responsablePrincipal->email)
                                    <div class="text-sm text-slate-600">{{ $programme->responsablePrincipal->email }}</div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-user-slash text-slate-400 text-xl"></i>
                            </div>
                            <p class="text-slate-500">Aucun responsable assigné</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistiques rapides -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-line text-green-600 mr-2"></i>
                        Informations
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Créé le:</span>
                        <span class="text-sm text-slate-900">{{ $programme->created_at->format('d/m/Y') }}</span>
                    </div>

                    @if($programme->createurUtilisateur)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Créé par:</span>
                        <span class="text-sm text-slate-900">{{ $programme->createurUtilisateur->prenom }} {{ $programme->createurUtilisateur->nom }}</span>
                    </div>
                    @endif

                    @if($programme->updated_at != $programme->created_at)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Modifié le:</span>
                        <span class="text-sm text-slate-900">{{ $programme->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endif

                    @if($programme->modificateurUtilisateur && $programme->updated_at != $programme->created_at)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Modifié par:</span>
                        <span class="text-sm text-slate-900">{{ $programme->modificateurUtilisateur->prenom }} {{ $programme->modificateurUtilisateur->nom }}</span>
                    </div>
                    @endif
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
                    <a href="{{ route('private.programmes.index') }}?type={{ $programme->type_programme }}" class="flex items-center p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        <i class="fas fa-filter text-blue-500 mr-3"></i>
                        <span class="text-sm font-medium text-slate-700">Voir programmes similaires</span>
                    </a>

                    <a href="{{ route('private.programmes.planning') }}" class="flex items-center p-3 rounded-lg hover:bg-slate-50 transition-colors">
                        <i class="fas fa-calendar text-green-500 mr-3"></i>
                        <span class="text-sm font-medium text-slate-700">Voir planning général</span>
                    </a>

                    <button type="button" onclick="imprimerProgramme()" class="flex items-center p-3 rounded-lg hover:bg-slate-50 transition-colors w-full text-left">
                        <i class="fas fa-print text-purple-500 mr-3"></i>
                        <span class="text-sm font-medium text-slate-700">Imprimer les détails</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-question-circle text-yellow-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900" id="modalTitle">Confirmer l'action</h3>
            </div>
            <p class="text-slate-600 mb-2" id="modalMessage">Êtes-vous sûr de vouloir effectuer cette action ?</p>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeModal()" class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" id="confirmAction" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                Confirmer
            </button>
        </div>
    </div>
</div>

<script>
// Modal functions
function showModal(title, message, onConfirm) {
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('modalMessage').textContent = message;
    document.getElementById('confirmAction').onclick = onConfirm;
    document.getElementById('confirmModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

// Changer le statut du programme
function changerStatut(action) {
    const actions = {
        'activer': { title: 'Activer le programme', message: 'Voulez-vous activer ce programme ?' },
        'suspendre': { title: 'Suspendre le programme', message: 'Voulez-vous suspendre ce programme ?' },
        'terminer': { title: 'Terminer le programme', message: 'Voulez-vous marquer ce programme comme terminé ?' },
        'annuler': { title: 'Annuler le programme', message: 'Voulez-vous annuler ce programme ?' }
    };

    const actionData = actions[action];
    showModal(actionData.title, actionData.message, function() {
        fetch(`{{ route('private.programmes.show', $programme) }}/${action}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeModal();
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
}

// Dupliquer le programme
function dupliquerProgramme() {
    showModal('Dupliquer le programme', 'Voulez-vous créer une copie de ce programme ?', function() {
        closeModal();
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('private.programmes.dupliquer', $programme) }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);

        document.body.appendChild(form);
        form.submit();
    });
}

// Supprimer le programme
function supprimerProgramme() {
    showModal('Supprimer le programme', 'Cette action est irréversible. Voulez-vous vraiment supprimer ce programme ?', function() {
        fetch('{{ route("private.programmes.destroy", $programme) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            closeModal();
            if (data.success) {
                window.location.href = '{{ route('private.programmes.index') }}';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    });
}

// Imprimer le programme
function imprimerProgramme() {
    window.print();
}

// Close modal when clicking outside
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

@endsection
