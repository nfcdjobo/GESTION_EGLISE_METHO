@extends('layouts.private.main')
@section('title', 'Gestion des Contacts')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Gestion des Contacts</h1>
        <p class="text-slate-500 mt-1">Administration des contacts d'églises - {{ \Carbon\Carbon::now()->format('l d F Y') }}</p>
    </div>

    <!-- Filtres et actions -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-filter text-blue-600 mr-2"></i>
                    Filtres et Actions
                </h2>
                <div class="flex flex-wrap gap-2">
                    @can('contacts.create')
                        <a href="{{ route('private.contacts.create') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Nouveau Contact
                        </a>
                    @endcan
                    @can('contacts.export')
                        <a href="{{ route('private.contacts.export') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </a>
                    @endcan
                    <a href="{{ route('private.contacts.map') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-map mr-2"></i> Carte
                    </a>
                    <a href="{{ route('private.contacts.statistics') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-chart-bar mr-2"></i> Statistiques
                    </a>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('private.contacts.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom église, pasteur, ville..." class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Type Contact</label>
                    <select name="type_contact" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous les types</option>
                        @foreach($types_contact as $type)
                            <option value="{{ $type }}" {{ request('type_contact') == $type ? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Ville</label>
                    <select name="ville" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes les villes</option>
                        @foreach($villes as $ville)
                            <option value="{{ $ville }}" {{ request('ville') == $ville ? 'selected' : '' }}>{{ $ville }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Dénomination</label>
                    <select name="denomination" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Toutes</option>
                        @foreach($denominations as $denomination)
                            <option value="{{ $denomination }}" {{ request('denomination') == $denomination ? 'selected' : '' }}>{{ $denomination }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="verifie" class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Tous</option>
                        <option value="true" {{ request('verifie') == 'true' ? 'selected' : '' }}>Vérifiés</option>
                        <option value="false" {{ request('verifie') == 'false' ? 'selected' : '' }}>Non vérifiés</option>
                    </select>
                </div>
                <div class="lg:col-span-6 flex gap-2 pt-4">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-2"></i> Rechercher
                    </button>
                    <a href="{{ route('private.contacts.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                        <i class="fas fa-refresh mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-church text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $contacts->total() }}</p>
                    <p class="text-sm text-slate-500">Total des contacts</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $contacts->where('verifie', true)->count() }}</p>
                    <p class="text-sm text-slate-500">Vérifiés</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-eye text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $contacts->where('visible_public', true)->count() }}</p>
                    <p class="text-sm text-slate-500">Publics</p>
                </div>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-map-marker-alt text-white text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-2xl font-bold text-slate-800">{{ $contacts->whereNotNull('latitude')->whereNotNull('longitude')->count() }}</p>
                    <p class="text-sm text-slate-500">Géolocalisés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des contacts -->
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
        <div class="p-6 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-2"></i>
                    Liste des Contacts ({{ $contacts->total() }})
                </h2>
                <button type="button" onclick="bulkActions()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-sm font-medium rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-tasks mr-2"></i> Actions groupées
                </button>
            </div>
        </div>
        <div class="p-6">
            @if($contacts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" id="selectAll" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Église</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Localisation</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Contact</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($contacts as $contact)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-4">
                                        <input type="checkbox" name="selected_contacts[]" value="{{ $contact->id }}" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 contact-checkbox">
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                @if($contact->logo_url)
                                                    <img class="h-10 w-10 rounded-full object-cover" src="{{ $contact->logo_url }}" alt="{{ $contact->nom_eglise }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                                        <span class="text-white font-semibold text-sm">{{ substr($contact->nom_eglise, 0, 2) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-semibold text-slate-900">{{ $contact->nom_eglise }}</div>
                                                @if($contact->denomination)
                                                    <div class="text-sm text-slate-500">{{ $contact->denomination }}</div>
                                                @endif
                                                @if($contact->pasteur_principal)
                                                    <div class="text-xs text-slate-400">{{ $contact->pasteur_principal }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @switch($contact->type_contact)
                                                @case('principal') bg-blue-100 text-blue-800 @break
                                                @case('pastoral') bg-green-100 text-green-800 @break
                                                @case('administratif') bg-purple-100 text-purple-800 @break
                                                @case('urgence') bg-red-100 text-red-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch">
                                            {{ ucfirst($contact->type_contact) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="text-sm text-slate-900">{{ $contact->ville ?? '-' }}</div>
                                        @if($contact->quartier)
                                            <div class="text-xs text-slate-500">{{ $contact->quartier }}</div>
                                        @endif
                                        @if($contact->latitude && $contact->longitude)
                                            <div class="flex items-center mt-1">
                                                <i class="fas fa-map-marker-alt text-green-500 text-xs mr-1"></i>
                                                <span class="text-xs text-green-600">Géolocalisé</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($contact->telephone_principal)
                                            <div class="text-sm text-slate-900">{{ $contact->telephone_principal }}</div>
                                        @endif
                                        @if($contact->email_principal)
                                            <div class="text-xs text-slate-500">{{ $contact->email_principal }}</div>
                                        @endif
                                        @if($contact->site_web_principal)
                                            <div class="flex items-center mt-1">
                                                <i class="fas fa-globe text-blue-500 text-xs mr-1"></i>
                                                <span class="text-xs text-blue-600">Site web</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex flex-col space-y-1">
                                            @if($contact->verifie)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i> Vérifié
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i> En attente
                                                </span>
                                            @endif

                                            @if($contact->visible_public)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-eye mr-1"></i> Public
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-eye-slash mr-1"></i> Privé
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center space-x-2">
                                            @can('contacts.read')
                                                <a href="{{ route('private.contacts.show', $contact) }}" class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors" title="Voir">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                            @endcan

                                            @can('contacts.update')
                                                <a href="{{ route('private.contacts.edit', $contact) }}" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors" title="Modifier">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            @endcan

                                            @can('contacts.update')
                                                @if(!$contact->verifie)
                                                    <button type="button" onclick="verifyContact('{{ $contact->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors" title="Vérifier">
                                                        <i class="fas fa-check text-sm"></i>
                                                    </button>
                                                @endif
                                            @endcan

                                            <button type="button" onclick="showQRCode('{{ $contact->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-purple-600 bg-purple-100 rounded-lg hover:bg-purple-200 transition-colors" title="QR Code">
                                                <i class="fas fa-qrcode text-sm"></i>
                                            </button>

                                            @can('contacts.delete')
                                                <button type="button" onclick="deleteContact('{{ $contact->id }}')" class="inline-flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition-colors" title="Supprimer">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                    <div class="text-sm text-slate-700">
                        Affichage de <span class="font-medium">{{ $contacts->firstItem() }}</span> à <span class="font-medium">{{ $contacts->lastItem() }}</span>
                        sur <span class="font-medium">{{ $contacts->total() }}</span> résultats
                    </div>
                    <div>
                        {{ $contacts->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-church text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucun contact trouvé</h3>
                    <p class="text-slate-500 mb-6">
                        @if(request()->hasAny(['search', 'type_contact', 'ville', 'denomination']))
                            Aucun contact ne correspond à vos critères de recherche.
                        @else
                            Commencez par ajouter votre premier contact d'église.
                        @endif
                    </p>
                    @can('contacts.create')
                        <a href="{{ route('private.contacts.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-plus mr-2"></i> Créer un contact
                        </a>
                    @endcan
                </div>
            @endif
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
            <p class="text-slate-600 mb-2">Êtes-vous sûr de vouloir supprimer ce contact ?</p>
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

@push('scripts')
<script>
// Sélection multiple
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.contact-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Modal functions
function showDeleteModal() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Suppression d'un contact
function deleteContact(contactId) {
    showDeleteModal();
    document.getElementById('confirmDelete').onclick = function() {
        fetch(`{{route('private.contacts.destroy', ':contactid')}}`.replace(':contactid', contactId), {
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
                location.reload();
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

// Vérification d'un contact
function verifyContact(contactId) {
    if (confirm('Voulez-vous marquer ce contact comme vérifié ?')) {
        fetch(`{{route('private.contacts.verify', ':contactid')}}`.replace(':contactid', contactId), {
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
}

// Afficher QR Code
function showQRCode(contactId) {
    window.open(`/private/contacts/${contactId}/qr-code`, '_blank', 'width=400,height=400');
}

// Actions groupées
function bulkActions() {
    const selected = Array.from(document.querySelectorAll('.contact-checkbox:checked'))
                         .map(cb => cb.value);

    if (selected.length === 0) {
        alert('Veuillez sélectionner au moins un contact');
        return;
    }

    const action = prompt('Action à effectuer:\n1. verify (vérifier)\n2. unverify (dévérifier)\n3. activate (rendre public)\n4. deactivate (rendre privé)\n5. delete (supprimer)');

    if (!action || !['verify', 'unverify', 'activate', 'deactivate', 'delete'].includes(action)) {
        alert('Action non valide');
        return;
    }

    if (action === 'delete' && !confirm('Êtes-vous sûr de vouloir supprimer ces contacts ?')) {
        return;
    }

    fetch("{{route('private.classes.bulk-actions')}}", {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            action: action,
            contact_ids: selected
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

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endpush

@endsection
