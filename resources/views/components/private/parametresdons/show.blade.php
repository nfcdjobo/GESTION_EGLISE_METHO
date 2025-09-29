@extends('layouts.private.main')
@section('title', 'Détails du Paramètre')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
            {{ $parametreDon->operateur }}
        </h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.parametresdons.index') }}" class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-credit-card mr-2"></i>
                        Paramètres de Don
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">{{ $parametreDon->operateur }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @can('parametresdons.update')
                    <a href="{{ route('private.parametresdons.edit', $parametreDon) }}"
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                @endcan

                @can('parametresdons.toggle-status')
                    <button type="button" onclick="toggleStatut()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-power-off mr-2"></i>
                        {{ $parametreDon->statut ? 'Désactiver' : 'Activer' }}
                    </button>
                @endcan

                @can('parametresdons.toggle-publication')
                    <button type="button" onclick="togglePublication()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-medium rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-globe mr-2"></i>
                        {{ $parametreDon->publier ? 'Dépublier' : 'Publier' }}
                    </button>
                @endcan

                @can('parametresdons.delete')
                    <button type="button" onclick="deleteParametre()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                @endcan

                <a href="{{ route('private.parametresdons.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Détails du paramètre -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations Générales
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Opérateur</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-building text-white text-sm"></i>
                                    </div>
                                    <span class="font-semibold text-slate-900">{{ $parametreDon->operateur }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Type</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($parametreDon->type)
                                        @case('virement_bancaire') bg-blue-100 text-blue-800 @break
                                        @case('carte_bancaire') bg-green-100 text-green-800 @break
                                        @case('mobile_money') bg-orange-100 text-orange-800 @break
                                        @default bg-gray-100 text-gray-800 @break
                                    @endswitch">
                                    {{ $parametreDon->type_libelle }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Numéro de Compte</label>
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <code class="text-lg text-slate-800">{{ $parametreDon->numero_compte }}</code>
                        </div>
                    </div>

                    @if($parametreDon->logo)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Logo de l'Opérateur</label>
                            <div class="p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-center space-x-4">
                                    <img src="{{ Storage::url($parametreDon->logo) }}"
                                        alt="Logo {{ $parametreDon->operateur }}"
                                        class="w-24 h-24 object-contain border-2 border-slate-200 rounded-lg shadow-sm bg-white p-2" />
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-slate-700">{{ $parametreDon->operateur }}</p>
                                        <p class="text-xs text-slate-500 mt-1">Logo officiel de l'opérateur</p>
                                        @can('parametres-dons.download-logo')
                                            <a href="{{ Storage::url($parametreDon->logo) }}"
                                            download="logo_{{ Str::slug($parametreDon->operateur) }}.{{ pathinfo($parametreDon->logo, PATHINFO_EXTENSION) }}"
                                            class="inline-flex items-center mt-2 text-xs text-blue-600 hover:text-blue-800 transition-colors">
                                                <i class="fas fa-download mr-1"></i> Télécharger
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($parametreDon->qrcode)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Code QR</label>
                            <div class="p-4 bg-slate-50 rounded-xl">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-1">
                                        <img src="{{ Storage::url( $parametreDon->qrcode) }}"
                                            alt="QR Code"
                                            class="w-32 h-32 object-contain border rounded-lg shadow" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                @if($parametreDon->statut)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i> Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i> Inactif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Publication</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                @if($parametreDon->publier)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-globe mr-1"></i> Publié
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-eye-slash mr-1"></i> Non publié
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dons récents -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-donate text-purple-600 mr-2"></i>
                        Dons Récents ({{ $parametreDon->dons->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    @if($parametreDon->dons->count() > 0)
                        <div class="space-y-4 max-h-64 overflow-y-auto">
                            @foreach($parametreDon->dons as $don)
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-donate text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-slate-900">{{ number_format($don->montant) }} FCFA</div>
                                            <div class="text-sm text-slate-500">{{ $don->created_at->format('d/m/Y à H:i') }}</div>
                                        </div>
                                    </div>
                                    @can('dons.read')
                                        <a href="{{ route('private.dons.show', $don) }}" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endcan
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-donate text-2xl text-slate-400"></i>
                            </div>
                            <p class="text-slate-500">Aucun don enregistré</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Historique -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-amber-600 mr-2"></i>
                        Historique des Actions ({{ $parametreDon->historiques->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    @if($parametreDon->historiques->count() > 0)
                        <div class="space-y-4 max-h-64 overflow-y-auto">
                            @foreach($parametreDon->historiques as $historique)
                                <div class="flex items-start space-x-3 p-3 bg-slate-50 rounded-xl">
                                    <div class="w-8 h-8 bg-gradient-to-r from-amber-500 to-orange-500 rounded-full flex items-center justify-center mt-0.5">
                                        <i class="fas fa-history text-white text-sm"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-medium text-slate-900">{{ ucfirst(str_replace('_', ' ', $historique->action)) }}</div>
                                        <div class="text-sm text-slate-500">
                                            Par {{ $historique->effectuerPar->nom_complet ?? 'Système' }} •
                                            {{ $historique->created_at->format('d/m/Y à H:i') }}
                                        </div>
                                        @if($historique->donnees)
                                            <div class="text-xs text-slate-400 mt-1">
                                                {{ Str::limit(json_encode($historique->donnees), 100) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-history text-2xl text-slate-400"></i>
                            </div>
                            <p class="text-slate-500">Aucun historique disponible</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistiques -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-pie text-cyan-600 mr-2"></i>
                        Statistiques
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Total dons:</span>
                        <span class="text-lg font-bold text-slate-900">{{ $parametreDon->dons_count }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Montant total:</span>
                        <span class="text-lg font-bold text-slate-900">{{ number_format($parametreDon->dons->sum('montant')) }} FCFA</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-slate-700">Don moyen:</span>
                        <span class="text-lg font-bold text-slate-900">
                            {{ $parametreDon->dons_count > 0 ? number_format($parametreDon->dons->avg('montant')) : 0 }} FCFA
                        </span>
                    </div>
                    <div class="pt-4 border-t border-slate-200">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Dernier don:</span>
                            <span class="text-sm text-slate-600">
                                {{ $parametreDon->dons->first()?->created_at->format('d/m/Y') ?? 'Aucun' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-cog text-amber-600 mr-2"></i>
                        Informations Système
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Créé le</label>
                        <p class="text-sm text-slate-600">{{ $parametreDon->created_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($parametreDon->creerPar)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Créé par</label>
                            <p class="text-sm text-slate-600">{{ $parametreDon->creerPar->nom_complet }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Modifié le</label>
                        <p class="text-sm text-slate-600">{{ $parametreDon->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($parametreDon->modifierPar)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Modifié par</label>
                            <p class="text-sm text-slate-600">{{ $parametreDon->modifierPar->nom_complet }}</p>
                        </div>
                    @endif

                    @if($parametreDon->publier && $parametreDon->publierPar)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Publié par</label>
                            <p class="text-sm text-slate-600">{{ $parametreDon->publierPar->nom_complet }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">ID</label>
                        <code class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded">{{ $parametreDon->id }}</code>
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
                    @can('dons.create')
                        <a href="{{ route('private.dons.create', ['parametre' => $parametreDon->id]) }}" class="w-full inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200">
                            <i class="fas fa-plus mr-2"></i> Nouveau Don
                        </a>
                    @endcan

                    @can('dons.index')
                        <a href="{{ route('private.dons.index', ['parametre' => $parametreDon->id]) }}" class="w-full inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                            <i class="fas fa-list mr-2"></i> Voir tous les dons
                        </a>
                    @endcan

                    @can('parametres-dons.duplicate')
                        <button type="button" onclick="duplicateParametre()" class="w-full inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-cyan-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-cyan-700 transition-all duration-200">
                            <i class="fas fa-copy mr-2"></i> Dupliquer
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Confirmer la suppression</h3>
            </div>
            <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer ce paramètre de don ?</p>
            <p class="text-red-600 font-medium">Cette action est irréversible et supprimera tous les dons associés.</p>
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
function showDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Toggle statut
function toggleStatut() {
    if (!confirm('Êtes-vous sûr de vouloir changer le statut de ce paramètre ?')) {
        return;
    }

    fetch(`{{ route('private.parametresdons.toggle-statut', $parametreDon->id) }}`, {
        method: 'PATCH',
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

// Toggle publication
function togglePublication() {
    if (!confirm('Êtes-vous sûr de vouloir changer la publication de ce paramètre ?')) {
        return;
    }

    fetch(`{{ route('private.parametresdons.toggle-publication', $parametreDon->id) }}`, {
        method: 'PATCH',
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

// Supprimer paramètre
function deleteParametre() {
    showDeleteModal();
    document.getElementById('confirmDelete').onclick = function() {
        fetch(`{{ route('private.parametresdons.destroy', $parametreDon->id) }}`, {
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
                window.location.href = "{{ route('private.parametresdons.index') }}";
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

// Dupliquer paramètre
function duplicateParametre() {
    if (confirm('Voulez-vous dupliquer ce paramètre ?')) {
        window.location.href = `{{ route('private.parametresdons.create') }}?duplicate={{ $parametreDon->id }}`;
    }
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

@endsection
