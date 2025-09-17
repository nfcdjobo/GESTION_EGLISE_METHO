@extends('layouts.private.main')
@section('title', 'Ventes de la Moisson - ' . $moisson->theme)

@section('content')
    <div class="space-y-8">
        <!-- En-tête avec navigation -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-slate-600 mb-4">
                <a href="{{ route('private.moissons.index') }}" class="hover:text-blue-600 transition-colors">
                    <i class="fas fa-seedling mr-1"></i> Moissons
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('private.moissons.show', $moisson) }}" class="hover:text-blue-600 transition-colors">
                    {{ Str::limit($moisson->theme, 30) }}
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium">Ventes</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Ventes de moisson
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Gestion des ventes pour la moisson "{{ $moisson->theme }}"
                    </p>
                </div>

                <div class="flex gap-2">
                    <button onclick="exporterDonnees()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i> Exporter
                    </button>
                    <a href="{{ route('private.moissons.ventes.create', $moisson) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Nouvelle vente
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques globales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Ventes</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $statistiques['total_ventes'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-store text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Ventes Actives</p>
                        <p class="text-2xl font-bold text-green-600">{{ $statistiques['ventes_actives'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Objectifs Atteints</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $statistiques['objectifs_atteints'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-trophy text-purple-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Montant Collecté</p>
                        <p class="text-xl font-bold text-emerald-600">{{ number_format($statistiques['montant_total_collecte'], 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-coins text-emerald-600"></i>
                    </div>
                </div>
            </div>



            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">% Réalisation</p>
                        @php
                            $pourcentage = $statistiques['montant_total_cible'] > 0
                                ? round(($statistiques['montant_total_collecte'] * 100) / $statistiques['montant_total_cible'], 1)
                                : 0;
                        @endphp
                        <p class="text-2xl font-bold
                            @if($pourcentage >= 100) text-green-600
                            @elseif($pourcentage >= 70) text-blue-600
                            @elseif($pourcentage >= 50) text-yellow-600
                            @else text-red-600
                            @endif">{{ $pourcentage }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-slate-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
            <form id="filtres-form" class="grid grid-cols-1 md:grid-cols-5 gap-4">


                <div class="flex items-end gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search mr-1"></i> Filtrer
                    </button>
                    <button type="button" onclick="resetFiltres()" class="px-4 py-2 bg-slate-600 text-white rounded-lg hover:bg-slate-700 transition-colors">
                        <i class="fas fa-times mr-1"></i> Reset
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des ventes -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-blue-600 mr-2"></i>
                    Liste des ventes ({{ $ventes->total() }})
                </h3>
            </div>

            @if($ventes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Catégorie
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Objectif
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Collecté
                                </th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Progression
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Collecteur
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Statut
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($ventes as $vente)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-slate-900">
                                                {{ $vente->categorie_libelle }}
                                            </div>
                                            @if($vente->description)
                                                <div class="text-xs text-slate-500 max-w-xs truncate">
                                                    {{ $vente->description }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900">
                                            {{ number_format($vente->cible, 0, ',', ' ') }} FCFA
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium
                                                @if($vente->objectif_atteint) text-green-600 @else text-slate-900 @endif">
                                                {{ number_format($vente->montant_solde, 0, ',', ' ') }} FCFA
                                            </div>
                                            @if($vente->reste > 0)
                                                <div class="text-xs text-red-500">
                                                    Reste: {{ number_format($vente->reste, 0, ',', ' ') }} FCFA
                                                </div>
                                            @elseif($vente->montant_supplementaire > 0)
                                                <div class="text-xs text-green-600">
                                                    Supplément: {{ number_format($vente->montant_supplementaire, 0, ',', ' ') }} FCFA
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="flex items-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="h-2 rounded-full
                                                        @if($vente->pourcentage_realise >= 100) bg-green-500
                                                        @elseif($vente->pourcentage_realise >= 70) bg-blue-500
                                                        @elseif($vente->pourcentage_realise >= 50) bg-yellow-500
                                                        @else bg-red-500
                                                        @endif"
                                                        style="width: {{ min($vente->pourcentage_realise, 100) }}%">
                                                    </div>
                                                </div>
                                                <span class="text-sm font-medium text-slate-900 ml-2">
                                                    {{ $vente->pourcentage_realise }}%
                                                </span>
                                            </div>
                                            <div class="text-xs mt-1
                                                @if($vente->pourcentage_realise >= 100) text-green-600
                                                @elseif($vente->pourcentage_realise >= 70) text-blue-600
                                                @elseif($vente->pourcentage_realise >= 50) text-yellow-600
                                                @else text-red-600
                                                @endif">
                                                @php
                                                    $pourcentage = $vente->pourcentage_realise;
                                                    if ($pourcentage >= 100) $statut = 'Objectif atteint';
                                                    elseif ($pourcentage >= 90) $statut = 'Presque atteint';
                                                    elseif ($pourcentage >= 70) $statut = 'Bonne progression';
                                                    elseif ($pourcentage >= 50) $statut = 'En cours';
                                                    elseif ($pourcentage >= 30) $statut = 'Début';
                                                    else $statut = 'Très faible';
                                                @endphp
                                                {{ $statut }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        <div class="flex flex-col">
                                            <div>{{ $vente->collecteur?->nom ?? '-' }}</div>
                                            @if($vente->collecte_le)
                                                <div class="text-xs text-slate-500">
                                                    {{ $vente->collecte_le->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $vente->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $vente->status ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="ajouterVente('{{ $vente->id }}')"
                                                class="text-green-600 hover:text-green-900 transition-colors"
                                                title="Ajouter une vente">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                            <a href="{{ route('private.moissons.ventes.show', [$moisson, $vente]) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors"
                                                title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('private.moissons.ventes.edit', [$moisson, $vente]) }}"
                                                class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                                title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="toggleStatus('{{ $vente->id }}', {{ $vente->status ? 'false' : 'true' }})"
                                                class="{{ $vente->status ? 'text-green-600 hover:text-green-900' : 'text-red-600 hover:text-red-900' }} transition-colors"
                                                title="{{ $vente->status ? 'Désactiver' : 'Activer' }}">
                                                <i class="fas fa-{{ $vente->status ? 'toggle-on' : 'toggle-off' }}"></i>
                                            </button>
                                            <button onclick="supprimerVente('{{ $vente->id }}')"
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $ventes->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-store text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">Aucune vente trouvée</h3>
                    <p class="text-slate-500 mb-4">Il n'y a aucune vente pour cette moisson avec les filtres sélectionnés.</p>
                    <a href="{{ route('private.moissons.ventes.create', $moisson) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Créer la première vente
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal pour ajouter une vente -->
    <div id="modal-ajouter-vente" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800">Ajouter une vente</h3>
                    <p class="text-sm text-slate-600 mt-1">Enregistrer une nouvelle vente pour cette catégorie</p>
                </div>
                <form id="form-ajouter-vente" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Montant de la vente (FCFA) *</label>
                        <input type="number" name="montant" id="montant-input" required min="0.01" step="0.01"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ex: 25000">
                    </div>



                    <div id="calcul-theorique" class="p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                        <div class="text-sm">
                            <span class="text-blue-700">Montant théorique:</span>
                            <span class="font-medium text-blue-900 ml-1" id="montant-theorique">0 FCFA</span>
                        </div>
                        <div class="text-xs text-blue-600 mt-1" id="difference-theorique"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Notes (optionnel)</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Détails sur cette vente..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="fermerModal()"
                            class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-1"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let venteIdActuelle = null;

            // Gestion des filtres
            document.getElementById('filtres-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const params = new URLSearchParams();

                for (let [key, value] of formData.entries()) {
                    if (value) params.append(key, value);
                }

                const url = params.toString() ?
                    `{{ route('private.moissons.ventes.index', $moisson) }}?${params.toString()}` :
                    `{{ route('private.moissons.ventes.index', $moisson) }}`;

                window.location.href = url;
            });

            function resetFiltres() {
                window.location.href = `{{ route('private.moissons.ventes.index', $moisson) }}`;
            }

            // Modal pour ajouter une vente
            function ajouterVente(venteId) {
                venteIdActuelle = venteId;
                document.getElementById('modal-ajouter-vente').classList.remove('hidden');
                document.getElementById('montant-input').focus();
            }

            function fermerModal() {
                document.getElementById('modal-ajouter-vente').classList.add('hidden');
                document.getElementById('form-ajouter-vente').reset();
                document.getElementById('calcul-theorique').classList.add('hidden');
                venteIdActuelle = null;
            }





            // Soumission du formulaire d'ajout de vente
            document.getElementById('form-ajouter-vente').addEventListener('submit', function(e) {
                e.preventDefault();

                if (!venteIdActuelle) return;

                const formData = new FormData(this);
                const donnees = {
                    montant: parseFloat(formData.get('montant')),
                    notes: formData.get('notes')
                };

                fetch(`{{ route('private.moissons.ventes.ajouter-montant', [$moisson, ':vente']) }}`.replace(':vente', venteIdActuelle), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(donnees)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Vente ajoutée avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de l\'ajout de la vente', 'error');
                    }
                    fermerModal();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de l\'ajout de la vente', 'error');
                    fermerModal();
                });
            });

            // Toggle status
            function toggleStatus(venteId, nouveauStatut) {
                if (!confirm('Êtes-vous sûr de vouloir modifier le statut de cette vente ?')) {
                    return;
                }

                fetch(`{{ route('private.moissons.ventes.toggle-status', [$moisson, ':vente']) }}`.replace(':vente', venteId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Statut mis à jour avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de la mise à jour', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de la mise à jour du statut', 'error');
                });
            }

            // Supprimer vente
            function supprimerVente(venteId) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cette vente ? Cette action est irréversible.')) {
                    return;
                }

                fetch(`{{ route('private.moissons.show', $moisson) }}/ventes/${venteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Vente supprimée avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de la suppression', 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de la suppression', 'error');
                });
            }

            // Exporter données
            function exporterDonnees() {
                const format = prompt('Format d\'export (json/csv/excel):', 'json');
                if (!format || !['json', 'csv', 'excel'].includes(format)) {
                    return;
                }

                window.location.href = `{{ route('private.moissons.ventes.exporter', $moisson) }}?format=${format}`;
            }

            // Fonction utilitaire pour les notifications
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium ${
                    type === 'success' ? 'bg-green-500' :
                    type === 'error' ? 'bg-red-500' : 'bg-blue-500'
                }`;
                notification.textContent = message;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }

            // Fermer modal avec ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    fermerModal();
                }
            });
        </script>
    @endpush
@endsection

