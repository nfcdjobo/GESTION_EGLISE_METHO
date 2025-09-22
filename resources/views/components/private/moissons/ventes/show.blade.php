@extends('layouts.private.main')
@section('title', 'Détails de la Vente - ' . $venteMoisson->categorie_libelle)

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
                <a href="{{ route('private.moissons.ventes.index', $moisson) }}" class="hover:text-blue-600 transition-colors">
                    Ventes
                </a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-800 font-medium">{{ $venteMoisson->categorie_libelle }}</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        {{ $venteMoisson->categorie_libelle }}
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Détails et suivi de la vente pour la moisson "{{ $moisson->theme }}"
                    </p>
                </div>

                <div class="flex gap-2">
                    <button onclick="ajouterVente()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Ajouter vente
                    </button>
                    <a href="{{ route('private.moissons.ventes.edit', [$moisson, $venteMoisson]) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit mr-2"></i> Modifier
                    </a>
                    <button onclick="toggleStatus()"
                        class="inline-flex items-center px-4 py-2 {{ $venteMoisson->status ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-sm font-medium rounded-xl transition-colors">
                        <i class="fas fa-toggle-{{ $venteMoisson->status ? 'off' : 'on' }} mr-2"></i>
                        {{ $venteMoisson->status ? 'Désactiver' : 'Activer' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Informations de la moisson -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-2xl shadow-lg border border-blue-200/50 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $moisson->theme }}</h3>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-slate-600">Date:</span>
                            <span class="font-medium text-slate-800 ml-1">{{ $moisson->date->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Culte:</span>
                            <span class="font-medium text-slate-800 ml-1">{{ $moisson->culte->titre ?? 'Non défini' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Objectif global:</span>
                            <span class="font-medium text-slate-800 ml-1">{{ number_format($moisson->cible, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div>
                            <span class="text-slate-600">Progression globale:</span>
                            <span class="font-medium text-blue-600 ml-1">{{ $moisson->pourcentage_realise }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicateurs de performance de la vente -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Objectif</p>
                        <p class="text-2xl font-bold text-slate-900">{{ number_format($venteMoisson->cible, 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-bullseye text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Vendu</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($venteMoisson->montant_solde, 0, ',', ' ') }}</p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-coins text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">
                            {{ $venteMoisson->reste > 0 ? 'Reste' : 'Supplément' }}
                        </p>
                        <p class="text-2xl font-bold {{ $venteMoisson->reste > 0 ? 'text-red-600' : 'text-purple-600' }}">
                            {{ number_format($venteMoisson->reste > 0 ? $venteMoisson->reste : $venteMoisson->montant_supplementaire, 0, ',', ' ') }}
                        </p>
                        <p class="text-xs text-slate-500">FCFA</p>
                    </div>
                    <div class="w-12 h-12 {{ $venteMoisson->reste > 0 ? 'bg-red-100' : 'bg-purple-100' }} rounded-xl flex items-center justify-center">
                        <i class="fas fa-{{ $venteMoisson->reste > 0 ? 'exclamation-triangle' : 'trophy' }} {{ $venteMoisson->reste > 0 ? 'text-red-600' : 'text-purple-600' }}"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Progression</p>
                        <p class="text-2xl font-bold
                            @if($venteMoisson->pourcentage_realise >= 100) text-green-600
                            @elseif($venteMoisson->pourcentage_realise >= 70) text-blue-600
                            @elseif($venteMoisson->pourcentage_realise >= 50) text-yellow-600
                            @else text-red-600
                            @endif">{{ $venteMoisson->pourcentage_realise }}%</p>
                        <p class="text-xs text-slate-500">
                            @php
                                $pourcentage = $venteMoisson->pourcentage_realise;
                                if ($pourcentage >= 100) $statut = 'Objectif atteint';
                                elseif ($pourcentage >= 90) $statut = 'Presque atteint';
                                elseif ($pourcentage >= 70) $statut = 'Bonne progression';
                                elseif ($pourcentage >= 50) $statut = 'En cours';
                                elseif ($pourcentage >= 30) $statut = 'Début';
                                else $statut = 'Très faible';
                            @endphp
                            {{ $statut }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-slate-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barre de progression visuelle -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">
                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                Progression visuelle
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Progression de l'objectif</span>
                    <span class="text-sm font-medium text-slate-900">{{ $venteMoisson->pourcentage_realise }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full transition-all duration-300
                        @if($venteMoisson->pourcentage_realise >= 100) bg-green-500
                        @elseif($venteMoisson->pourcentage_realise >= 70) bg-blue-500
                        @elseif($venteMoisson->pourcentage_realise >= 50) bg-yellow-500
                        @else bg-red-500
                        @endif"
                        style="width: {{ min($venteMoisson->pourcentage_realise, 100) }}%">
                    </div>
                </div>
                <div class="flex justify-between text-xs text-slate-500">
                    <span>0 FCFA</span>
                    <span>{{ number_format($venteMoisson->cible, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        <!-- Détails de la vente -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations principales -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informations de la vente
                    </h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-slate-600">Type de vente</dt>
                            <dd class="mt-1 text-sm text-slate-900 font-medium">{{ $venteMoisson->categorie_libelle }}</dd>
                        </div>

                        @if($venteMoisson->description)
                            <div>
                                <dt class="text-sm font-medium text-slate-600">Description</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $venteMoisson->description }}</dd>
                            </div>
                        @endif

                        <div>
                            <dt class="text-sm font-medium text-slate-600">Statut</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $venteMoisson->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $venteMoisson->status ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-600">Responsable</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $venteMoisson->collecteur?->nom ?? 'Non défini' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-600">Date de collecte</dt>
                            <dd class="mt-1 text-sm text-slate-900">
                                {{ $venteMoisson->collecte_le ? $venteMoisson->collecte_le->format('d/m/Y à H:i') : 'Non définie' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-600">Créé par</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $venteMoisson->createur?->nom ?? 'Inconnu' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-slate-600">Date de création</dt>
                            <dd class="mt-1 text-sm text-slate-900">{{ $venteMoisson->created_at->format('d/m/Y à H:i') }}</dd>
                        </div>

                        @if($venteMoisson->updated_at != $venteMoisson->created_at)
                            <div>
                                <dt class="text-sm font-medium text-slate-600">Dernière modification</dt>
                                <dd class="mt-1 text-sm text-slate-900">{{ $venteMoisson->updated_at->format('d/m/Y à H:i') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Montants et analyses -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-calculator text-green-600 mr-2"></i>
                        Analyse financière
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        <!-- Objectif -->
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-blue-800">Objectif fixé</p>
                                <p class="text-xs text-blue-600">Montant à atteindre</p>
                            </div>
                            <p class="text-lg font-bold text-blue-900">
                                {{ number_format($venteMoisson->cible, 0, ',', ' ') }} FCFA
                            </p>
                        </div>

                        <!-- Montant vendu -->
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl">
                            <div>
                                <p class="text-sm font-medium text-green-800">Montant vendu</p>
                                <p class="text-xs text-green-600">Recettes générées</p>
                            </div>
                            <p class="text-lg font-bold text-green-900">
                                {{ number_format($venteMoisson->montant_solde, 0, ',', ' ') }} FCFA
                            </p>
                        </div>



                        @if($venteMoisson->reste > 0)
                            <!-- Reste à vendre -->
                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-red-800">Reste à vendre</p>
                                    <p class="text-xs text-red-600">Montant manquant</p>
                                </div>
                                <p class="text-lg font-bold text-red-900">
                                    {{ number_format($venteMoisson->reste, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        @endif

                        @if($venteMoisson->montant_supplementaire > 0)
                            <!-- Dépassement d'objectif -->
                            <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl">
                                <div>
                                    <p class="text-sm font-medium text-purple-800">Dépassement d'objectif</p>
                                    <p class="text-xs text-purple-600">Ventes supplémentaires</p>
                                </div>
                                <p class="text-lg font-bold text-purple-900">
                                    +{{ number_format($venteMoisson->montant_supplementaire, 0, ',', ' ') }} FCFA
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des modifications -->
        @if(count($historique) > 0)
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-purple-600 mr-2"></i>
                        Historique des modifications
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach(array_reverse($historique) as $index => $edit)
                            <div class="flex items-start gap-4 p-4 {{ $index % 2 === 0 ? 'bg-slate-50' : 'bg-white' }} rounded-lg">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-{{ $edit['action'] === 'creation' ? 'plus' : ($edit['action'] === 'modification' ? 'edit' : 'store') }} text-blue-600 text-xs"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-slate-900">
                                            @switch($edit['action'])
                                                @case('creation')
                                                    Création de la vente
                                                    @break
                                                @case('modification')
                                                    Modification
                                                    @break
                                                @case('ajout_vente')
                                                    Ajout de vente
                                                    @break
                                                @case('activation')
                                                    Activation
                                                    @break
                                                @case('desactivation')
                                                    Désactivation
                                                    @break
                                                @default
                                                    {{ ucfirst($edit['action']) }}
                                            @endswitch
                                        </h4>
                                        <span class="text-xs text-slate-500">
                                            {{ \Carbon\Carbon::parse($edit['date'])->format('d/m/Y H:i') }}
                                        </span>
                                    </div>

                                    @if(isset($edit['details']))
                                        <div class="mt-2 text-sm text-slate-600">
                                            @if(isset($edit['details']['ancien_montant']))
                                                <p>Ancien montant: {{ number_format($edit['details']['ancien_montant'], 0, ',', ' ') }} FCFA</p>
                                            @endif
                                            @if(isset($edit['details']['nouveau_montant']))
                                                <p>Nouveau montant: {{ number_format($edit['details']['nouveau_montant'], 0, ',', ' ') }} FCFA</p>
                                            @endif
                                            @if(isset($edit['details']['montant_ajoute']))
                                                <p>Montant ajouté: +{{ number_format($edit['details']['montant_ajoute'], 0, ',', ' ') }} FCFA</p>
                                            @endif


                                            @if(isset($edit['details']['notes']) && $edit['details']['notes'])
                                                <p class="italic">{{ $edit['details']['notes'] }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
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
            // Modal pour ajouter une vente
            function ajouterVente() {
                document.getElementById('modal-ajouter-vente').classList.remove('hidden');
                document.getElementById('montant-input').focus();
            }

            function fermerModal() {
                document.getElementById('modal-ajouter-vente').classList.add('hidden');
                document.getElementById('form-ajouter-vente').reset();
                document.getElementById('calcul-theorique').classList.add('hidden');
            }



            // Soumission du formulaire d'ajout de vente
            document.getElementById('form-ajouter-vente').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const donnees = {
                    montant: parseFloat(formData.get('montant')),

                    notes: formData.get('notes')
                };



                fetch(`{{ route('private.moissons.ventes.ajouter-montant', [$moisson, $venteMoisson]) }}`, {
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
            function toggleStatus() {
                const currentStatus = {{ $venteMoisson->status ? 'true' : 'false' }};
                const action = currentStatus ? 'désactiver' : 'activer';

                if (!confirm(`Êtes-vous sûr de vouloir ${action} cette vente ?`)) {
                    return;
                }

                fetch(`{{ route('private.moissons.ventes.toggle-status', [$moisson, $venteMoisson]) }}`, {
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

            // Animation des cartes au chargement
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.bg-white\\/80');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    // card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        // card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
    @endpush
@endsection

