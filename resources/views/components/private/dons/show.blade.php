@extends('layouts.private.main')
@section('title', 'Détails du Don')

@section('content')
<div class="space-y-8">
    <!-- Page Title & Breadcrumb -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
            Don #{{ $don->id }}
        </h1>
        <nav class="flex mt-2" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('private.dons.index') }}"
                        class="inline-flex items-center text-sm font-medium text-slate-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-dove mr-2"></i>
                        Donations
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 mx-2"></i>
                        <span class="text-sm font-medium text-slate-500">Don #{{ $don->id }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @can('dons.update')
                    <a href="{{ route('private.dons.edit', $don) }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                @endcan

                @can('dons.duplicate')
                    <button type="button" onclick="duplicateDon()"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-600 to-green-600 text-white text-sm font-medium rounded-xl hover:from-emerald-700 hover:to-green-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-copy mr-2"></i> Dupliquer
                    </button>
                @endcan

                @if($don->aUnePreuve())
                    <a href="{{ route('private.dons.telechargerPreuve', $don) }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-download mr-2"></i> Télécharger preuve
                    </a>
                @endif

                @can('dons.delete')
                    <button type="button" onclick="deleteDon()"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-red-600 to-rose-600 text-white text-sm font-medium rounded-xl hover:from-red-700 hover:to-rose-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-trash mr-2"></i> Supprimer
                    </button>
                @endcan

                <a href="{{ route('private.dons.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Informations principales -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Détails du donateur -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-user text-purple-600 mr-2"></i>
                        Informations du Donateur
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-xl font-bold">
                                {{ substr($don->prenom_donateur, 0, 1) }}{{ substr($don->nom_donateur, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900">{{ $don->nom_complet }}</h3>
                            <p class="text-slate-500">Donateur</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Prénom</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="font-medium text-slate-900">{{ $don->prenom_donateur }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nom</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="font-medium text-slate-900">{{ $don->nom_donateur }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Téléphone principal</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="font-medium text-slate-900">{{ $don->telephone_1 }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Téléphone secondaire</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                @if($don->telephone_2)
                                    <span class="font-medium text-slate-900">{{ $don->telephone_2 }}</span>
                                @else
                                    <span class="text-slate-400">Non renseigné</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du don -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-coins text-green-600 mr-2"></i>
                        Détails du Don
                    </h2>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Montant</label>
                            <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                                <span class="text-3xl font-bold text-green-700">{{ $don->montant_formate }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Devise</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @switch($don->devise)
                                        @case('XOF') bg-orange-100 text-orange-800 @break
                                        @case('EUR') bg-blue-100 text-blue-800 @break
                                        @case('USD') bg-green-100 text-green-800 @break
                                        @default bg-gray-100 text-gray-800 @break
                                    @endswitch">
                                    {{ $don->devise_libelle }}
                                </span>
                            </div>
                        </div>

                        @if($don->parametreDon)
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Opérateur</label>
                                <div class="p-3 bg-slate-50 rounded-xl">
                                    <span class="font-medium text-slate-900">{{ $don->parametreDon->operateur }}</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">Type de paiement</label>
                                <div class="p-3 bg-slate-50 rounded-xl">
                                    <span class="font-medium text-slate-900">{{ $don->parametreDon->type_libelle }}</span>
                                </div>
                            </div>

                            @if($don->parametreDon->numero_compte)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Numéro de compte</label>
                                    <div class="p-3 bg-slate-50 rounded-xl">
                                        <code class="text-sm font-mono text-slate-800">{{ $don->parametreDon->numero_compte }}</code>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Date du don</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                <span class="font-medium text-slate-900">{{ $don->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Preuve de paiement</label>
                            <div class="p-3 bg-slate-50 rounded-xl">
                                @if($don->aUnePreuve())
                                    <a href="{{ route('private.dons.telechargerPreuve', $don) }}"
                                       class="inline-flex items-center px-3 py-2 bg-green-100 text-green-800 rounded-lg hover:bg-green-200 transition-colors">
                                        <i class="fas fa-download mr-2"></i>
                                        Télécharger
                                    </a>
                                @else
                                    <span class="text-red-600">Aucune preuve fournie</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historique des dons de ce donateur -->
            @if(isset($autresDons) && $autresDons->count() > 0)
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-amber-600 mr-2"></i>
                        Autres dons de {{ $don->prenom_donateur }}
                    </h2>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-slate-200">
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Montant</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Opérateur</th>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200">
                                @foreach($autresDons as $autreDon)
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-4 py-3 text-sm text-slate-900">{{ $autreDon->created_at->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3 text-sm font-medium text-green-600">{{ $autreDon->montant_formate }}</td>
                                        <td class="px-4 py-3 text-sm text-slate-900">{{ $autreDon->parametreDon->operateur ?? '-' }}</td>
                                        <td class="px-4 py-3">
                                            @can('dons.read')
                                                <a href="{{ route('private.dons.show', $autreDon) }}" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistiques du donateur -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-chart-pie text-cyan-600 mr-2"></i>
                        Statistiques
                    </h2>
                </div>
                <div class="p-6 space-y-4">
                    @if(isset($statsDonateurStats))
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Total dons:</span>
                            <span class="text-lg font-bold text-slate-900">{{ $statsDonateurStats['total_dons'] ?? 1 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Montant total:</span>
                            <span class="text-lg font-bold text-green-600">{{ number_format($statsDonateurStats['montant_total'] ?? $don->montant, 2, ',', ' ') }} {{ $don->devise }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-700">Don moyen:</span>
                            <span class="text-lg font-bold text-slate-900">{{ number_format($statsDonateurStats['montant_moyen'] ?? $don->montant, 2, ',', ' ') }} {{ $don->devise }}</span>
                        </div>
                        @if(isset($statsDonateurStats['premier_don']))
                        <div class="pt-4 border-t border-slate-200">
                            <span class="text-sm font-medium text-slate-700">Premier don:</span>
                            <p class="text-sm text-slate-600">{{ $statsDonateurStats['premier_don']->created_at->format('d/m/Y') }}</p>
                        </div>
                        @endif
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
                    @can('dons.create')
                        <a href="{{ route('private.dons.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i> Nouveau don
                        </a>
                    @endcan

                    @can('dons.read')
                        <button onclick="showDonateursHistory()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                            <i class="fas fa-user-friends mr-2"></i> Voir tous ses dons
                        </button>
                    @endcan

                    @can('dons.export')
                        <button onclick="exportDonateur()" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors">
                            <i class="fas fa-file-export mr-2"></i> Exporter
                        </button>
                    @endcan
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
                        <label class="block text-sm font-medium text-slate-700 mb-1">ID du don</label>
                        <code class="text-sm bg-slate-100 text-slate-700 px-2 py-1 rounded">{{ $don->id }}</code>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Créé le</label>
                        <p class="text-sm text-slate-600">{{ $don->created_at->format('d/m/Y à H:i:s') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Modifié le</label>
                        <p class="text-sm text-slate-600">{{ $don->updated_at->format('d/m/Y à H:i:s') }}</p>
                    </div>

                    @if($don->preuve)
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Fichier preuve</label>
                            <p class="text-sm text-slate-600">{{ basename($don->preuve) }}</p>
                        </div>
                    @endif
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
            <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer ce don ?</p>
            <p class="text-red-600 font-medium">Cette action est irréversible et supprimera également la preuve de paiement.</p>
        </div>
        <div class="flex items-center justify-end space-x-3 p-6 border-t border-slate-200">
            <button type="button" onclick="closeDeleteModal()"
                class="px-4 py-2 text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                Annuler
            </button>
            <button type="button" id="confirmDelete"
                class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors">
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

// Dupliquer le don
function duplicateDon() {
    if (confirm('Voulez-vous dupliquer ce don ?')) {
        window.location.href = `{{ route('private.dons.dupliquer', $don->id) }}`;
    }
}

// Supprimer le don
function deleteDon() {
    showDeleteModal();
    document.getElementById('confirmDelete').onclick = function() {
        fetch(`{{ route('private.dons.destroy', $don->id) }}`, {
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
                    window.location.href = '{{ route('private.dons.index') }}';
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

// Voir l'historique des dons du donateur
function showDonateursHistory() {
    const params = new URLSearchParams({
        nom_donateur: '{{ $don->nom_donateur }}',
        prenom_donateur: '{{ $don->prenom_donateur }}',
        telephone_1: '{{ $don->telephone_1 }}'
    });
    window.location.href = `{{ route('private.dons.parDonateur') }}?${params.toString()}`;
}

// Exporter les données du donateur
function exportDonateur() {
    const params = new URLSearchParams({
        nom_donateur: '{{ $don->nom_donateur }}',
        prenom_donateur: '{{ $don->prenom_donateur }}',
        telephone_1: '{{ $don->telephone_1 }}'
    });
    window.location.href = `{{ route('private.dons.exporter') }}?${params.toString()}`;
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>

@endsection
