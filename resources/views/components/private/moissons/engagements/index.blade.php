@extends('layouts.private.main')
@section('title', 'Engagements de la Moisson - ' . $moisson->theme)

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
                <span class="text-slate-800 font-medium">Engagements</span>
            </div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Engagements de moisson
                    </h1>
                    <p class="text-slate-500 mt-1">
                        Gestion des engagements pour la moisson "{{ $moisson->theme }}"
                    </p>
                </div>

                <div class="flex gap-2">
                    <button onclick="exporterDonnees()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors">
                        <i class="fas fa-download mr-2"></i> Exporter
                    </button>
                    <a href="{{ route('private.moissons.engagements.create', $moisson) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Nouvel engagement
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques globales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Engagements</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $statistiques['total_engagements'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-handshake text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Engagements Actifs</p>
                        <p class="text-2xl font-bold text-green-600">{{ $statistiques['engagements_actifs'] }}</p>
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
                        <p class="text-sm font-medium text-slate-600">En Retard</p>
                        <p class="text-2xl font-bold text-red-600">{{ $statistiques['engagements_en_retard'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
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
            <form id="filtres-form" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Catégorie</label>
                    <select name="categorie" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les catégories</option>
                        @foreach(\App\Models\EngagementMoisson::CATEGORIES as $code => $libelle)
                            <option value="{{ $code }}" {{ request('categorie') === $code ? 'selected' : '' }}>
                                {{ $libelle }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Statut</label>
                    <select name="status" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Objectif</label>
                    <select name="objectif_atteint" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les objectifs</option>
                        <option value="1" {{ request('objectif_atteint') === '1' ? 'selected' : '' }}>Atteint</option>
                        <option value="0" {{ request('objectif_atteint') === '0' ? 'selected' : '' }}>Non atteint</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Retard</label>
                    <select name="en_retard" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous</option>
                        <option value="1" {{ request('en_retard') === '1' ? 'selected' : '' }}>En retard</option>
                        <option value="0" {{ request('en_retard') === '0' ? 'selected' : '' }}>À jour</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Recherche</label>
                    <input type="text" name="recherche" value="{{ request('recherche') }}"
                        placeholder="Nom, email, téléphone..."
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

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

        <!-- Liste des engagements -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 overflow-hidden">
            <div class="p-6 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 flex items-center">
                    <i class="fas fa-list text-blue-600 mr-2"></i>
                    Liste des engagements ({{ $engagements->total() }})
                </h3>
            </div>

            @if($engagements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Donateur
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Engagement
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Versé
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Progression
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                    Échéance
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
                            @foreach($engagements as $engagement)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium text-slate-900">
                                                {{ $engagement->nom_donateur }}
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $engagement->categorie_libelle }}
                                                </span>
                                            </div>
                                            @if($engagement->telephone)
                                                <div class="text-xs text-slate-500 mt-1">
                                                    <i class="fas fa-phone mr-1"></i>{{ $engagement->telephone }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-slate-900">
                                            {{ number_format($engagement->cible, 0, ',', ' ') }} FCFA
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="text-sm font-medium
                                                @if($engagement->objectif_atteint) text-green-600 @else text-slate-900 @endif">
                                                {{ number_format($engagement->montant_solde, 0, ',', ' ') }} FCFA
                                            </div>
                                            @if($engagement->reste > 0)
                                                <div class="text-xs text-red-500">
                                                    Reste: {{ number_format($engagement->reste, 0, ',', ' ') }} FCFA
                                                </div>
                                            @elseif($engagement->montant_supplementaire > 0)
                                                <div class="text-xs text-green-600">
                                                    Supplément: {{ number_format($engagement->montant_supplementaire, 0, ',', ' ') }} FCFA
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <div class="flex items-center">
                                                <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="h-2 rounded-full
                                                        @if($engagement->pourcentage_realise >= 100) bg-green-500
                                                        @elseif($engagement->pourcentage_realise >= 70) bg-blue-500
                                                        @elseif($engagement->pourcentage_realise >= 50) bg-yellow-500
                                                        @else bg-red-500
                                                        @endif"
                                                        style="width: {{ min($engagement->pourcentage_realise, 100) }}%">
                                                    </div>
                                                </div>
                                                <span class="text-sm font-medium text-slate-900 ml-2">
                                                    {{ $engagement->pourcentage_realise }}%
                                                </span>
                                            </div>
                                            <div class="text-xs mt-1
                                                @if($engagement->pourcentage_realise >= 100) text-green-600
                                                @elseif($engagement->pourcentage_realise >= 70) text-blue-600
                                                @elseif($engagement->pourcentage_realise >= 50) text-yellow-600
                                                @else text-red-600
                                                @endif">
                                                @php
                                                    $pourcentage = $engagement->pourcentage_realise;
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            @if($engagement->date_echeance)
                                                <div class="text-sm text-slate-900">
                                                    {{ $engagement->date_echeance->format('d/m/Y') }}
                                                </div>
                                                @if($engagement->est_en_retard)
                                                    <div class="text-xs text-red-600 font-medium">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        Retard: {{ $engagement->jours_retard }}j
                                                    </div>
                                                @else
                                                    <div class="text-xs text-slate-500">
                                                        Dans {{ now()->diffInDays($engagement->date_echeance) }}j
                                                    </div>
                                                @endif
                                            @else
                                                <div class="text-sm text-slate-500">Non définie</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col space-y-1">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $engagement->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $engagement->status ? 'Actif' : 'Inactif' }}
                                            </span>
                                            @if($engagement->est_en_retard)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    {{ $engagement->niveau_urgence_libelle }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="ajouterPaiement('{{ $engagement->id }}')"
                                                class="text-green-600 hover:text-green-900 transition-colors"
                                                title="Ajouter un paiement">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                            <a href="{{ route('private.moissons.engagements.show', [$moisson, $engagement]) }}"
                                                class="text-blue-600 hover:text-blue-900 transition-colors"
                                                title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('private.moissons.engagements.edit', [$moisson, $engagement]) }}"
                                                class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                                title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($engagement->reste > 0)
                                                <button onclick="planifierRappel('{{ $engagement->id }}')"
                                                    class="text-purple-600 hover:text-purple-900 transition-colors"
                                                    title="Planifier rappel">
                                                    <i class="fas fa-bell"></i>
                                                </button>
                                            @endif
                                            <button onclick="toggleStatus('{{ $engagement->id }}', {{ $engagement->status ? 'false' : 'true' }})"
                                                class="{{ $engagement->status ? 'text-green-600 hover:text-green-900' : 'text-red-600 hover:text-red-900' }} transition-colors"
                                                title="{{ $engagement->status ? 'Désactiver' : 'Activer' }}">
                                                <i class="fas fa-{{ $engagement->status ? 'toggle-on' : 'toggle-off' }}"></i>
                                            </button>
                                            <button onclick="supprimerEngagement('{{ $engagement->id }}')"
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
                    {{ $engagements->links() }}
                </div>
            @else
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-handshake text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">Aucun engagement trouvé</h3>
                    <p class="text-slate-500 mb-4">Il n'y a aucun engagement pour cette moisson avec les filtres sélectionnés.</p>
                    <a href="{{ route('private.moissons.engagements.create', $moisson) }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Créer le premier engagement
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal pour ajouter un paiement -->
    <div id="modal-ajouter-paiement" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-lg w-full">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800">Ajouter un paiement</h3>
                    <p class="text-sm text-slate-600 mt-1">Enregistrer un paiement pour cet engagement</p>
                </div>
                <form id="form-ajouter-paiement" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Montant du paiement (FCFA) *</label>
                        <input type="number" name="montant" id="montant-input" required min="0.01" step="0.01"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Ex: 50000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Notes (optionnel)</label>
                        <textarea name="notes" rows="3"
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Notes sur ce paiement..."></textarea>
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

    <!-- Modal pour planifier un rappel -->
    <div id="modal-planifier-rappel" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800">Planifier un rappel</h3>
                    <p class="text-sm text-slate-600 mt-1">Définir une date de rappel pour cet engagement</p>
                </div>
                <form id="form-planifier-rappel" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date du rappel *</label>
                        <input type="date" name="date_rappel" id="date-rappel-input" required
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="fermerModalRappel()"
                            class="px-4 py-2 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-bell mr-1"></i> Planifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let engagementIdActuel = null;

            // Gestion des filtres
            document.getElementById('filtres-form').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const params = new URLSearchParams();

                for (let [key, value] of formData.entries()) {
                    if (value) params.append(key, value);
                }

                const url = params.toString() ?
                    `{{ route('private.moissons.engagements.index', $moisson) }}?${params.toString()}` :
                    `{{ route('private.moissons.engagements.index', $moisson) }}`;

                window.location.href = url;
            });

            function resetFiltres() {
                window.location.href = `{{ route('private.moissons.engagements.index', $moisson) }}`;
            }

            // Modal pour ajouter un paiement
            function ajouterPaiement(engagementId) {
                engagementIdActuel = engagementId;
                document.getElementById('modal-ajouter-paiement').classList.remove('hidden');
                document.getElementById('montant-input').focus();
            }

            function fermerModal() {
                document.getElementById('modal-ajouter-paiement').classList.add('hidden');
                document.getElementById('form-ajouter-paiement').reset();
                engagementIdActuel = null;
            }

            // Modal pour planifier un rappel
            function planifierRappel(engagementId) {
                engagementIdActuel = engagementId;
                document.getElementById('modal-planifier-rappel').classList.remove('hidden');
                document.getElementById('date-rappel-input').focus();
            }

            function fermerModalRappel() {
                document.getElementById('modal-planifier-rappel').classList.add('hidden');
                document.getElementById('form-planifier-rappel').reset();
                engagementIdActuel = null;
            }

            // Soumission du formulaire d'ajout de paiement
            document.getElementById('form-ajouter-paiement').addEventListener('submit', function(e) {
                e.preventDefault();

                if (!engagementIdActuel) return;

                const formData = new FormData(this);
                const donnees = {
                    montant: parseFloat(formData.get('montant')),
                    notes: formData.get('notes')
                };

                fetch(`{{ route('private.moissons.engagements.ajouter-montant', [$moisson, ':engagement']) }}`.replace(':engagement', engagementIdActuel), {
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
                        showNotification('Paiement ajouté avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de l\'ajout du paiement', 'error');
                    }
                    fermerModal();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de l\'ajout du paiement', 'error');
                    fermerModal();
                });
            });

            // Soumission du formulaire de planification de rappel
            document.getElementById('form-planifier-rappel').addEventListener('submit', function(e) {
                e.preventDefault();

                if (!engagementIdActuel) return;

                const formData = new FormData(this);
                const donnees = {
                    date_rappel: formData.get('date_rappel')
                };

                fetch(`{{ route('private.moissons.engagements.planifier-rappel', [$moisson, ':engagement']) }}`.replace(':engagement', engagementIdActuel), {
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
                        showNotification('Rappel planifié avec succès', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showNotification(data.message || 'Erreur lors de la planification', 'error');
                    }
                    fermerModalRappel();
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de la planification du rappel', 'error');
                    fermerModalRappel();
                });
            });

            // Toggle status
            function toggleStatus(engagementId, nouveauStatut) {
                if (!confirm('Êtes-vous sûr de vouloir modifier le statut de cet engagement ?')) {
                    return;
                }

                fetch(`{{ route('private.moissons.engagements.toggle-status', [$moisson, ':engagement']) }}`.replace(':engagement', engagementId), {
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

            // Supprimer engagement
            function supprimerEngagement(engagementId) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer cet engagement ? Cette action est irréversible.')) {
                    return;
                }

                fetch(`{{ route('private.moissons.engagements.destroy', [$moisson, ':engagement']) }}`.replace(':engagement', engagementId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Engagement supprimé avec succès', 'success');
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

                window.location.href = `{{ route('private.moissons.engagements.exporter', $moisson) }}?format=${format}`;
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

            // Fermer modales avec ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    fermerModal();
                    fermerModalRappel();
                }
            });

            // Définir la date minimale pour le rappel (demain)
            document.addEventListener('DOMContentLoaded', function() {
                const today = new Date();
                const tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);

                const dateInput = document.getElementById('date-rappel-input');
                if (dateInput) {
                    dateInput.min = tomorrow.toISOString().split('T')[0];
                }
            });
        </script>
    @endpush
@endsection
