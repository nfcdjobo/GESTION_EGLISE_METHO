@extends('layouts.private.main')
@section('title', 'Résultats de Recherche - Souscriptions')

@section('content')
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                    Résultats de Recherche
                </h1>
                <p class="text-slate-500 mt-1">
                    {{ count($results) }} résultat(s) trouvé(s) pour "{{ $query ?? '' }}"
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('private.subscriptions.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
                </a>
                @can('subscriptions.create')
                    <a href="{{ route('private.subscriptions.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white text-sm font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                        <i class="fas fa-plus mr-2"></i> Nouvelle souscription
                    </a>
                @endcan
            </div>
        </div>

        <!-- Barre de recherche améliorée -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6">
                <form method="GET" action="{{ route('private.subscriptions.search') }}" class="space-y-4">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <!-- Champ de recherche principal -->
                        <div class="flex-1">
                            <label for="q" class="block text-sm font-medium text-slate-700 mb-2">
                                <i class="fas fa-search text-blue-600 mr-2"></i>
                                Rechercher
                            </label>
                            <div class="relative">
                                <input type="text" name="q" id="q"
                                       value="{{ $query ?? '' }}"
                                       placeholder="Nom du souscripteur, email, nom du FIMECO..."
                                       class="w-full pl-12 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       autocomplete="off">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-slate-400"></i>
                                </div>
                                @if($query)
                                    <button type="button" onclick="clearSearch()"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <i class="fas fa-times text-slate-400 hover:text-slate-600 transition-colors"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Filtres rapides -->
                        <div class="flex gap-2">
                            <select name="status_filter"
                                    class="px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Tous les statuts</option>
                                <option value="completement_payee" {{ request('status_filter') === 'completement_payee' ? 'selected' : '' }}>Complètement payée</option>
                                <option value="partiellement_payee" {{ request('status_filter') === 'partiellement_payee' ? 'selected' : '' }}>Partiellement payée</option>
                                <option value="inactive" {{ request('status_filter') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>

                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-search mr-2"></i> Rechercher
                            </button>
                        </div>
                    </div>

                    <!-- Suggestions de recherche -->
                    @if(!$query && isset($suggestions))
                        <div class="mt-4 p-4 bg-blue-50 rounded-xl">
                            <div class="text-sm text-blue-800">
                                <div class="font-medium mb-2">Suggestions de recherche :</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($suggestions['souscripteurs_actifs'] ?? [] as $suggestion)
                                        <button type="button" onclick="setSearchQuery('{{ $suggestion }}')"
                                                class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs">
                                            {{ $suggestion }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Résultats de recherche -->
        @if(count($results) > 0)
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-800 flex items-center">
                            <i class="fas fa-list-ul text-purple-600 mr-2"></i>
                            Résultats de recherche ({{ count($results) }})
                        </h2>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ count($results) }} trouvé(s)
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($results as $subscription)
                            <div class="flex items-start justify-between p-6 bg-gradient-to-r from-slate-50 to-blue-50 rounded-xl border border-slate-200 hover:shadow-md transition-all duration-200">
                                <div class="flex items-start space-x-4 flex-1">
                                    <!-- Avatar du souscripteur -->
                                    <div class="flex-shrink-0">
                                        @if($subscription->souscripteur?->photo_profil)
                                            <img class="h-12 w-12 rounded-xl object-cover"
                                                 src="{{ asset('storage/' . $subscription->souscripteur->photo_profil) }}"
                                                 alt="{{ $subscription->souscripteur->nom }}">
                                        @else
                                            <div class="h-12 w-12 rounded-xl bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                                <span class="text-lg font-bold text-white">
                                                    {{ strtoupper(substr($subscription->souscripteur?->nom ?? 'U', 0, 1)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Informations principales -->
                                    <div class="flex-1">
                                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                            <!-- Souscripteur et FIMECO -->
                                            <div>
                                                <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $subscription->souscripteur?->nom ?? 'Souscripteur supprimé' }}</h3>
                                                <p class="text-sm text-slate-600 mb-2">{{ $subscription->souscripteur?->email ?? 'Email non disponible' }}</p>
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-handshake text-blue-600"></i>
                                                    <span class="text-sm font-medium text-blue-700">{{ Str::limit($subscription->fimeco?->nom ?? 'FIMECO supprimé', 30) }}</span>
                                                </div>
                                            </div>

                                            <!-- Montants et progression -->
                                            <div>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="text-sm text-slate-600">Souscrit:</span>
                                                        <span class="font-medium text-slate-900">{{ number_format($subscription->montant_souscrit, 0, ',', ' ') }} FCFA</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-sm text-slate-600">Payé:</span>
                                                        <span class="font-medium text-green-600">{{ number_format($subscription->montant_paye, 0, ',', ' ') }} FCFA</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-sm text-slate-600">Reste:</span>
                                                        <span class="font-medium text-orange-600">{{ number_format($subscription->reste_a_payer, 0, ',', ' ') }} FCFA</span>
                                                    </div>
                                                </div>

                                                <!-- Barre de progression -->
                                                <div class="mt-3">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <span class="text-xs text-slate-600">Progression</span>
                                                        <span class="text-xs font-medium">{{ number_format($subscription->progression, 1) }}%</span>
                                                    </div>
                                                    <div class="w-full bg-slate-200 rounded-full h-2">
                                                        <div class="h-2 rounded-full {{ $subscription->progression >= 100 ? 'bg-gradient-to-r from-green-500 to-emerald-500' : ($subscription->progression >= 75 ? 'bg-gradient-to-r from-blue-500 to-purple-500' : ($subscription->progression >= 50 ? 'bg-gradient-to-r from-yellow-500 to-orange-500' : 'bg-gradient-to-r from-red-500 to-pink-500')) }}"
                                                             style="width: {{ min($subscription->progression, 100) }}%"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Statuts et dates -->
                                            <div>
                                                <div class="space-y-2">
                                                    <!-- Statut -->
                                                    <div>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subscription->statut === 'completement_payee' ? 'bg-green-100 text-green-800' : ($subscription->statut === 'partiellement_payee' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                            @if($subscription->statut === 'completement_payee')
                                                                <i class="fas fa-check-circle mr-1"></i> Complète
                                                            @elseif($subscription->statut === 'partiellement_payee')
                                                                <i class="fas fa-hourglass-half mr-1"></i> Partielle
                                                            @else
                                                                <i class="fas fa-pause-circle mr-1"></i> Inactive
                                                            @endif
                                                        </span>
                                                    </div>

                                                    <!-- Dates -->
                                                    <div class="text-xs text-slate-600">
                                                        <div><i class="fas fa-calendar-plus text-green-500 mr-1"></i> {{ $subscription->date_souscription->format('d/m/Y') }}</div>
                                                        @if($subscription->date_echeance)
                                                            <div class="mt-1 {{ $subscription->en_retard ? 'text-red-600' : '' }}">
                                                                <i class="fas fa-calendar-times {{ $subscription->en_retard ? 'text-red-500' : 'text-orange-500' }} mr-1"></i>
                                                                {{ $subscription->date_echeance->format('d/m/Y') }}
                                                                @if($subscription->en_retard)
                                                                    <span class="font-medium">({{ $subscription->jours_retard }} j. retard)</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Paiements -->
                                                    <div class="text-xs text-slate-600">
                                                        <i class="fas fa-credit-card text-purple-500 mr-1"></i>
                                                        {{ $subscription->payments->count() }} paiement(s)
                                                        @if($subscription->payments->where('statut', 'en_attente')->count() > 0)
                                                            <span class="text-orange-600">({{ $subscription->payments->where('statut', 'en_attente')->count() }} en attente)</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col items-end space-y-2 ml-4">
                                    <div class="flex space-x-2">
                                        @can('subscriptions.read')
                                            <a href="{{ route('private.subscriptions.show', $subscription) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 text-cyan-600 bg-cyan-100 rounded-lg hover:bg-cyan-200 transition-colors"
                                                title="Voir détails">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                        @endcan
                                        @can('subscriptions.update')
                                            <a href="{{ route('private.subscriptions.edit', $subscription) }}"
                                                class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 bg-yellow-100 rounded-lg hover:bg-yellow-200 transition-colors"
                                                title="Modifier">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                        @endcan
                                        @can('subscriptions.payment')
                                            @if($subscription->statut !== 'completement_payee' && $subscription->reste_a_payer > 0)
                                                <button onclick="ouvrirModalPaiement('{{ $subscription->id }}')"
                                                    class="inline-flex items-center justify-center w-8 h-8 text-green-600 bg-green-100 rounded-lg hover:bg-green-200 transition-colors"
                                                    title="Ajouter paiement">
                                                    <i class="fas fa-credit-card text-sm"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>

                                    <!-- Alertes -->
                                    @if($subscription->necessiteAttention())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Attention
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination si nécessaire -->
                    @if(method_exists($results, 'hasPages') && $results->hasPages())
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                            <div class="text-sm text-slate-700">
                                Affichage de <span class="font-medium">{{ $results->firstItem() }}</span> à <span
                                    class="font-medium">{{ $results->lastItem() }}</span> sur <span
                                    class="font-medium">{{ $results->total() }}</span> résultats
                            </div>
                            <div>
                                {{ $results->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Aucun résultat -->
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20">
                <div class="p-12 text-center">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-3xl text-slate-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-4">Aucun résultat trouvé</h3>

                    @if($query)
                        <p class="text-slate-500 mb-6">
                            Aucune souscription ne correspond à votre recherche "<strong>{{ $query }}</strong>".
                        </p>

                        <!-- Suggestions pour améliorer la recherche -->
                        <div class="bg-blue-50 rounded-xl p-6 text-left max-w-md mx-auto mb-6">
                            <h4 class="font-medium text-blue-900 mb-3">Suggestions pour améliorer votre recherche :</h4>
                            <ul class="text-sm text-blue-800 space-y-2">
                                <li><i class="fas fa-lightbulb text-blue-600 mr-2"></i>Vérifiez l'orthographe des mots-clés</li>
                                <li><i class="fas fa-lightbulb text-blue-600 mr-2"></i>Essayez des termes plus généraux</li>
                                <li><i class="fas fa-lightbulb text-blue-600 mr-2"></i>Utilisez des mots-clés différents</li>
                                <li><i class="fas fa-lightbulb text-blue-600 mr-2"></i>Recherchez par email ou nom partiel</li>
                            </ul>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <button onclick="clearSearch()"
                                    class="inline-flex items-center px-4 py-2 bg-slate-600 text-white font-medium rounded-xl hover:bg-slate-700 transition-colors">
                                <i class="fas fa-times mr-2"></i> Effacer la recherche
                            </button>

                            <a href="{{ route('private.subscriptions.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <i class="fas fa-list mr-2"></i> Voir toutes les souscriptions
                            </a>
                        </div>
                    @else
                        <p class="text-slate-500 mb-6">
                            Commencez par saisir un terme de recherche dans le champ ci-dessus.
                        </p>

                        <a href="{{ route('private.subscriptions.index') }}"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-list mr-2"></i> Voir toutes les souscriptions
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <!-- Recherches récentes -->
        @if(isset($suggestions) && !empty($suggestions))
            <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
                <div class="p-6 border-b border-slate-200">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history text-slate-600 mr-2"></i>
                        Suggestions et raccourcis
                    </h2>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Souscripteurs actifs -->
                        @if(!empty($suggestions['souscripteurs_actifs']))
                            <div>
                                <h3 class="font-medium text-slate-900 mb-3">Souscripteurs actifs</h3>
                                <div class="space-y-2">
                                    @foreach(array_slice($suggestions['souscripteurs_actifs'], 0, 5) as $nom)
                                        <button onclick="setSearchQuery('{{ $nom }}')"
                                                class="block w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors">
                                            <i class="fas fa-user text-blue-500 mr-2"></i>{{ $nom }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Recherches par statut -->
                        <div>
                            <h3 class="font-medium text-slate-900 mb-3">Recherche par statut</h3>
                            <div class="space-y-2">
                                @foreach($suggestions['statuts_disponibles'] ?? [] as $statut)
                                    <a href="{{ route('private.subscriptions.index') }}?statut={{ $statut }}"
                                       class="block w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition-colors">
                                        <i class="fas fa-filter text-green-500 mr-2"></i>{{ ucfirst(str_replace('_', ' ', $statut)) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recherches par progression -->
                        <div>
                            <h3 class="font-medium text-slate-900 mb-3">Recherche par progression</h3>
                            <div class="space-y-2">
                                @foreach($suggestions['statuts_globaux'] ?? [] as $statut_global)
                                    <a href="{{ route('private.subscriptions.index') }}?statut_global={{ $statut_global }}"
                                       class="block w-full text-left px-3 py-2 text-sm text-slate-700 hover:bg-purple-50 hover:text-purple-700 rounded-lg transition-colors">
                                        <i class="fas fa-chart-line text-purple-500 mr-2"></i>{{ ucfirst(str_replace('_', ' ', $statut_global)) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal de paiement (si nécessaire) -->
    <div id="paiementModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-2xl w-full mx-4 transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-credit-card text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Enregistrer un paiement</h3>
                <p class="text-slate-500 text-sm mt-1" id="paiementModalSubtitle">Saisissez les détails du paiement</p>
            </div>

            <form id="paiementForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Montant (FCFA) *</label>
                        <input type="number" id="montant" name="montant" step="0.01" min="1" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <div class="text-xs text-slate-500 mt-1" id="montantInfo">Montant maximum: -</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type de paiement *</label>
                        <select id="typePaiement" name="type_paiement" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Sélectionnez un type</option>
                            <option value="especes">Espèces</option>
                            <option value="cheque">Chèque</option>
                            <option value="virement">Virement bancaire</option>
                            <option value="carte">Carte bancaire</option>
                            <option value="mobile_money">Mobile Money</option>
                        </select>
                    </div>

                    <div id="referenceField" class="hidden">
                        <label class="block text-sm font-medium text-slate-700 mb-2">Référence *</label>
                        <input type="text" id="reference" name="reference_paiement" maxlength="100"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                            placeholder="Numéro de chèque, référence virement...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Date de paiement *</label>
                        <input type="datetime-local" id="datePaiement" name="date_paiement" required
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Commentaire</label>
                    <textarea id="commentaire" name="commentaire" rows="3" maxlength="1000"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        placeholder="Informations complémentaires sur le paiement..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closePaiementModal()" class="flex-1 px-4 py-3 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                        Annuler
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                        <i class="fas fa-save mr-2"></i> Enregistrer le paiement
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentSubscriptionId = '';

            // Clear search function
            function clearSearch() {
                document.getElementById('q').value = '';
                document.querySelector('form').submit();
            }

            // Set search query
            function setSearchQuery(query) {
                document.getElementById('q').value = query;
                document.querySelector('form').submit();
            }

            // Payment modal functions
            function ouvrirModalPaiement(subscriptionId) {
                currentSubscriptionId = subscriptionId;

                // Fetch subscription details
                fetch(`{{ route('private.subscriptions.show', ':id') }}`.replace(':id', subscriptionId), {
                    headers: {
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const subscription = data.data.subscription;
                        document.getElementById('paiementModalSubtitle').textContent =
                            `Souscription: ${subscription.fimeco.nom} - ${subscription.souscripteur.nom}`;
                        document.getElementById('montantInfo').textContent =
                            `Montant maximum: ${new Intl.NumberFormat('fr-FR').format(subscription.reste_a_payer)} FCFA`;
                        document.getElementById('montant').max = subscription.reste_a_payer;

                        // Set current date/time
                        const now = new Date();
                        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                        document.getElementById('datePaiement').value = now.toISOString().slice(0, 16);

                        document.getElementById('paiementModal').classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors du chargement des détails de la souscription');
                });
            }

            function closePaiementModal() {
                document.getElementById('paiementModal').classList.add('hidden');
                document.getElementById('paiementForm').reset();
                document.getElementById('referenceField').classList.add('hidden');
                currentSubscriptionId = '';
            }

            // Handle type paiement change
            document.getElementById('typePaiement')?.addEventListener('change', function() {
                const referenceField = document.getElementById('referenceField');
                const typesWithReference = ['cheque', 'virement', 'carte'];

                if (typesWithReference.includes(this.value)) {
                    referenceField.classList.remove('hidden');
                    document.getElementById('reference').required = true;
                } else {
                    referenceField.classList.add('hidden');
                    document.getElementById('reference').required = false;
                }
            });

            // Handle payment form submission
            document.getElementById('paiementForm')?.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!currentSubscriptionId) {
                    alert('Erreur: ID de souscription manquant');
                    return;
                }

                const formData = new FormData(this);
                const data = Object.fromEntries(formData);

                fetch(`{{ route('private.subscriptions.effectuer-paiement', ':id') }}`.replace(':id', currentSubscriptionId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Paiement enregistré avec succès');
                        location.reload();
                    } else {
                        alert(data.message || 'Erreur lors de l\'enregistrement du paiement');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Erreur lors de l\'enregistrement du paiement');
                });
            });

            // Close modal on backdrop click
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('paiementModal')?.addEventListener('click', function(event) {
                    if (event.target === this) {
                        closePaiementModal();
                    }
                });

                // Auto-focus search field
                document.getElementById('q')?.focus();

                // Animation des résultats
                const results = document.querySelectorAll('.bg-gradient-to-r.from-slate-50');
                results.forEach((result, index) => {
                    result.style.opacity = '0';
                    result.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        result.style.transition = 'all 0.5s ease';
                        result.style.opacity = '1';
                        result.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
    @endpush
@endsection
