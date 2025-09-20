@extends('layouts.private.main')
@section('title', 'Alertes d\'Assiduité')

@section('content')
    <!-- Meta CSRF pour les appels AJAX -->

    <div class="space-y-8">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-red-800 to-orange-600 bg-clip-text text-transparent">
                Alertes d'Assiduité
            </h1>
            <p class="text-slate-500 mt-1">
                Membres nécessitant un suivi pastoral - {{ $dateAnalyse->locale('fr')->format('l d F Y') }}
            </p>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-slate-500 to-gray-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-users text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ $statistiques['total_membres'] ?? 0 }}</p>
                        <p class="text-sm text-slate-500">Membres analysés</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ count($membres) }}</p>
                        <p class="text-sm text-slate-500">En alerte</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-calendar-times text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ count($statistiques['dimanches_successifs'] ?? []) }}</p>
                        <p class="text-sm text-slate-500">Dimanches manqués</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl shadow-lg p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ count($statistiques['cultes_mensuels'] ?? []) }}</p>
                        <p class="text-sm text-slate-500">Faible mensuel</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 rounded-2xl  p-6 border border-white/20 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">

                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-exclamation-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-slate-800">{{ count($statistiques['critique'] ?? []) }}</p>
                        <p class="text-sm text-slate-500">Situation critique</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Filtres et Actions -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-filter text-blue-600 mr-2"></i>
                        Filtres et Actions
                    </h2>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="exporterAlertes()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-600 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-cyan-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-download mr-2"></i> Exporter
                        </button>
                        <button onclick="envoyerRappels()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white text-sm font-medium rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-envelope mr-2"></i> Envoyer rappels
                        </button>
                        <button onclick="planifierVisites()"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg">
                            <i class="fas fa-calendar-plus mr-2"></i> Planifier visites
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <form method="GET" action="{{ route('private.alertes.assiduite-faible') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Type d'alerte</label>
                        <select name="type_alerte"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="both" {{ $typeAlerte === 'both' ? 'selected' : '' }}>Toutes les alertes</option>
                            <option value="dimanches_successifs" {{ $typeAlerte === 'dimanches_successifs' ? 'selected' : '' }}>Dimanches successifs</option>
                            <option value="cultes_mensuels" {{ $typeAlerte === 'cultes_mensuels' ? 'selected' : '' }}>Cultes mensuels</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Période (mois)</label>
                        <select name="periode_mois"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="1" {{ $periodeMois == 1 ? 'selected' : '' }}>1 mois</option>
                            <option value="2" {{ $periodeMois == 2 ? 'selected' : '' }}>2 mois</option>
                            <option value="3" {{ $periodeMois == 3 ? 'selected' : '' }}>3 mois</option>
                            <option value="6" {{ $periodeMois == 6 ? 'selected' : '' }}>6 mois</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Sévérité</label>
                        <select name="severite"
                            class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Toutes les sévérités</option>
                            <option value="critique" {{ request('severite') === 'critique' ? 'selected' : '' }}>Critique</option>
                            <option value="attention" {{ request('severite') === 'attention' ? 'selected' : '' }}>Attention</option>
                        </select>
                    </div>

                    <div class="lg:col-span-4 flex gap-2 pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <i class="fas fa-search mr-2"></i> Actualiser
                        </button>
                        <a href="{{ route('private.alertes.assiduite-faible') }}"
                            class="inline-flex items-center px-4 py-2 bg-slate-600 text-white text-sm font-medium rounded-xl hover:bg-slate-700 transition-colors">
                            <i class="fas fa-refresh mr-2"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des membres en alerte -->
        <div class="bg-white/80 rounded-2xl shadow-lg border border-white/20 hover:shadow-xl transition-all duration-300">
            <div class="p-6 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center">
                        <i class="fas fa-users text-red-600 mr-2"></i>
                        Membres nécessitant un suivi ({{ count($membres) }})
                    </h2>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            {{ count($membres) }} membres en alerte
                        </span>
                        <span class="text-sm text-slate-600">
                            Analyse du {{ $dateAnalyse->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if (count($membres) > 0)
                    <div class="space-y-6">
                        @foreach ($membres as $index => $membre)
                            <div class="bg-gradient-to-r from-slate-50 to-gray-50 rounded-xl p-6 border-l-4 @if($membre['severite'] === 'critique') border-red-500 @elseif($membre['severite'] === 'attention') border-orange-500 @else border-yellow-500 @endif hover:shadow-md transition-all duration-200 relative" data-membre-id="{{ $membre['membre']['id'] }}">

                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                                    <!-- Informations du membre -->
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <h3 class="text-lg font-bold text-slate-900">
                                                    {{ $membre['membre']['nom_complet'] }}
                                                </h3>
                                                <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-slate-600">
                                                    @if($membre['membre']['email'])
                                                        <span class="flex items-center">
                                                            <i class="fas fa-envelope mr-1 text-blue-500"></i>
                                                            {{ $membre['membre']['email'] }}
                                                        </span>
                                                    @endif
                                                    @if($membre['membre']['telephone'])
                                                        <span class="flex items-center">
                                                            <i class="fas fa-phone mr-1 text-green-500"></i>
                                                            {{ $membre['membre']['telephone'] }}
                                                        </span>
                                                    @endif
                                                    @if($membre['membre']['date_adhesion'])
                                                        <span class="flex items-center">
                                                            <i class="fas fa-calendar mr-1 text-purple-500"></i>
                                                            Membre depuis {{ \Carbon\Carbon::parse($membre['membre']['date_adhesion'])->format('d/m/Y') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-3">
                                                <!-- Badge de sévérité -->
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                    @if($membre['severite'] === 'critique') bg-red-100 text-red-800
                                                    @elseif($membre['severite'] === 'attention') bg-orange-100 text-orange-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    @if($membre['severite'] === 'critique')
                                                        <i class="fas fa-exclamation-circle mr-1"></i> Critique
                                                    @elseif($membre['severite'] === 'attention')
                                                        <i class="fas fa-exclamation-triangle mr-1"></i> Attention
                                                    @else
                                                        <i class="fas fa-info-circle mr-1"></i> Normal
                                                    @endif
                                                </span>

                                                <!-- Score d'assiduité -->
                                                <div class="text-center">
                                                    <div class="text-lg font-bold
                                                        @if($membre['score_assiduite'] >= 70) text-green-600
                                                        @elseif($membre['score_assiduite'] >= 40) text-yellow-600
                                                        @else text-red-600 @endif">
                                                        {{ $membre['score_assiduite'] }}%
                                                    </div>
                                                    <div class="text-xs text-slate-500">Assiduité</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Alertes détaillées -->
                                        <div class="space-y-3 mb-4">
                                            @foreach($membre['alertes'] as $alerte)
                                                <div class="bg-white rounded-lg p-4 border-l-4
                                                    @if($alerte['severite'] === 'critique') border-red-400 bg-red-50
                                                    @else border-orange-400 bg-orange-50 @endif">
                                                    <div class="flex items-start justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center mb-2">
                                                                <i class="fas
                                                                    @if($alerte['type'] === 'dimanches_successifs') fa-calendar-times text-red-500
                                                                    @else fa-chart-line text-orange-500 @endif mr-2"></i>
                                                                <span class="font-medium text-slate-800">
                                                                    {{ ucfirst(str_replace('_', ' ', $alerte['type'])) }}
                                                                </span>
                                                            </div>
                                                            <p class="text-sm text-slate-700">{{ $alerte['description'] }}</p>

                                                            @if(isset($alerte['details']['pourcentage_assiduite']))
                                                                <div class="mt-2">
                                                                    <div class="flex items-center space-x-2">
                                                                        <div class="flex-1 bg-slate-200 rounded-full h-2">
                                                                            <div class="h-2 rounded-full
                                                                                @if($alerte['details']['pourcentage_assiduite'] >= 70) bg-green-500
                                                                                @elseif($alerte['details']['pourcentage_assiduite'] >= 40) bg-yellow-500
                                                                                @else bg-red-500 @endif"
                                                                                 style="width: {{ $alerte['details']['pourcentage_assiduite'] }}%"></div>
                                                                        </div>
                                                                        <span class="text-xs font-medium text-slate-700">
                                                                            {{ $alerte['details']['pourcentage_assiduite'] }}%
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                            @if($alerte['severite'] === 'critique') bg-red-100 text-red-700
                                                            @else bg-orange-100 text-orange-700 @endif">
                                                            {{ ucfirst($alerte['severite']) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Dernier culte -->
                                        @if($membre['dernier_culte'])
                                            <div class="bg-slate-100 rounded-lg p-3">
                                                <div class="flex items-center justify-between text-sm">
                                                    <div>
                                                        <span class="font-medium text-slate-700">Dernier culte :</span>
                                                        <span class="text-slate-600">{{ $membre['dernier_culte']['titre'] }}</span>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="text-slate-600">{{ \Carbon\Carbon::parse($membre['dernier_culte']['date'])->format('d/m/Y') }}</div>
                                                        <div class="text-xs text-slate-500">Il y a {{ $membre['dernier_culte']['jours_depuis'] }} jours</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-red-100 rounded-lg p-3">
                                                <div class="flex items-center text-sm text-red-700">
                                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                                    Aucune participation enregistrée
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex flex-col space-y-2 lg:w-48">
                                        <button onclick="contactMembre('{{ $membre['membre']['id'] }}')" class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-phone mr-2"></i> Contacter
                                        </button>
                                        <button onclick="planifierVisite('{{ $membre['membre']['id'] }}')" class="inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                            <i class="fas fa-calendar-plus mr-2"></i> Planifier visite
                                        </button>
                                        <button onclick="ajouterNote('{{ $membre['membre']['id'] }}')" class="inline-flex items-center justify-center px-3 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                            <i class="fas fa-sticky-note mr-2"></i> Ajouter note
                                        </button>
                                        <button onclick="marquerSuivi('{{ $membre['membre']['id'] }}')"
                                            class="inline-flex items-center justify-center px-3 py-2 bg-orange-600 text-white text-sm font-medium rounded-lg hover:bg-orange-700 transition-colors">
                                            <i class="fas fa-check mr-2"></i> Marquer suivi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-3xl text-green-500"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 mb-2">Aucune alerte d'assiduité</h3>
                        <p class="text-slate-500 mb-6">
                            @if ($typeAlerte !== 'both' || $periodeMois !== 1)
                                Aucun membre ne correspond aux critères sélectionnés.
                            @else
                                Tous les membres ont une assiduité satisfaisante !
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Variables globales pour les modales
            let currentMembreId = null;
            let alertesData = [];

            // =============================================
            // FONCTION CONTACT MEMBRE
            // =============================================
            function contactMembre(membreId) {
                currentMembreId = membreId;

                // Créer la modale de contact
                const modalHtml = `
                    <div id="contactModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-phone text-white text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800">Contacter le membre</h3>
                                <p class="text-slate-500 text-sm mt-1">Choisissez le moyen de contact</p>
                            </div>

                            <div class="space-y-3 mb-6">
                                <button onclick="initiateCall('${membreId}')"
                                    class="w-full flex items-center p-4 border-2 border-slate-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-phone text-green-600 text-lg"></i>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-semibold text-slate-800">Appel téléphonique</div>
                                        <div class="text-sm text-slate-500">Contact direct immédiat</div>
                                    </div>
                                </button>

                                <button onclick="sendSMS('${membreId}')"
                                    class="w-full flex items-center p-4 border-2 border-slate-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-sms text-blue-600 text-lg"></i>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-semibold text-slate-800">SMS</div>
                                        <div class="text-sm text-slate-500">Message de rappel personnalisé</div>
                                    </div>
                                </button>

                                <button onclick="sendEmail('${membreId}')"
                                    class="w-full flex items-center p-4 border-2 border-slate-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-envelope text-purple-600 text-lg"></i>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-semibold text-slate-800">Email</div>
                                        <div class="text-sm text-slate-500">Email de suivi pastoral</div>
                                    </div>
                                </button>
                            </div>

                            <div class="flex gap-3">
                                <button onclick="closeModal('contactModal')"
                                    class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                                    Annuler
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', modalHtml);
            }

            // Fonctions spécifiques de contact
            function initiateCall(membreId) {
                fetch(`#/api/membres/${membreId}/contact/call`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        type: 'call',
                        timestamp: new Date().toISOString()
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Appel initié avec succès', 'success');
                        if (data.phone_number) {
                            window.location.href = `tel:${data.phone_number}`;
                        }
                    } else {
                        showNotification(data.message || 'Erreur lors de l\'initiation de l\'appel', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Erreur lors de l\'initiation de l\'appel', 'error');
                });

                closeModal('contactModal');
            }

            function sendSMS(membreId) {
                const message = prompt("Message SMS à envoyer:", "Bonjour, nous avons remarqué votre absence récente aux cultes. N'hésitez pas à nous contacter si vous avez besoin de quoi que ce soit. L'équipe pastorale.");

                if (message) {
                    fetch(`#/api/membres/${membreId}/contact/sms`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            message: message,
                            type: 'sms'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('SMS envoyé avec succès', 'success');
                        } else {
                            showNotification(data.message || 'Erreur lors de l\'envoi du SMS', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Erreur lors de l\'envoi du SMS', 'error');
                    });
                }

                closeModal('contactModal');
            }

            function sendEmail(membreId) {
                // Rediriger vers la page de composition d'email
                window.location.href = `#/private/membres/${membreId}/send-email?context=assiduite_alerte`;
                closeModal('contactModal');
            }

            // =============================================
            // FONCTION PLANIFIER VISITE
            // =============================================
            function planifierVisite(membreId) {
                currentMembreId = membreId;

                const modalHtml = `
                    <div id="visiteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4 transform transition-all">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-calendar-plus text-white text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800">Planifier une visite</h3>
                                <p class="text-slate-500 text-sm mt-1">Organiser un suivi pastoral</p>
                            </div>

                            <form id="visiteForm" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Date de visite</label>
                                    <input type="date" id="dateVisite"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        min="${new Date().toISOString().split('T')[0]}" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Heure</label>
                                    <input type="time" id="heureVisite"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Responsable de la visite</label>
                                    <select id="responsableVisite"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Sélectionner un responsable</option>
                                        <option value="pasteur">Pasteur principal</option>
                                        <option value="assistant">Assistant pastoral</option>
                                        <option value="diacre">Diacre</option>
                                        <option value="autre">Autre membre</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Objectif de la visite</label>
                                    <select id="objectifVisite"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Sélectionner un objectif</option>
                                        <option value="suivi_assiduite">Suivi d'assiduité</option>
                                        <option value="encouragement">Encouragement</option>
                                        <option value="priere">Temps de prière</option>
                                        <option value="conseil">Conseil pastoral</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Notes</label>
                                    <textarea id="notesVisite" rows="3"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Notes additionnelles..."></textarea>
                                </div>
                            </form>

                            <div class="flex gap-3 mt-6">
                                <button onclick="closeModal('visiteModal')"
                                    class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                                    Annuler
                                </button>
                                <button onclick="confirmerVisite()"
                                    class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-xl hover:from-green-700 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                                    Planifier
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', modalHtml);

                // Définir l'heure par défaut à 14:00
                document.getElementById('heureVisite').value = '14:00';
            }

            function confirmerVisite() {
                const form = document.getElementById('visiteForm');
                const formData = new FormData(form);

                const visiteData = {
                    membre_id: currentMembreId,
                    date_visite: document.getElementById('dateVisite').value,
                    heure_visite: document.getElementById('heureVisite').value,
                    responsable: document.getElementById('responsableVisite').value,
                    objectif: document.getElementById('objectifVisite').value,
                    notes: document.getElementById('notesVisite').value
                };

                fetch('#/api/visites-pastorales', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(visiteData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Visite planifiée avec succès', 'success');
                        closeModal('visiteModal');
                    } else {
                        showNotification(data.message || 'Erreur lors de la planification', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Erreur lors de la planification de la visite', 'error');
                });
            }

            // =============================================
            // FONCTION AJOUTER NOTE
            // =============================================
            function ajouterNote(membreId) {
                currentMembreId = membreId;

                const modalHtml = `
                    <div id="noteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-lg w-full mx-4 transform transition-all">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-sticky-note text-white text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800">Ajouter une note</h3>
                                <p class="text-slate-500 text-sm mt-1">Note de suivi pastoral</p>
                            </div>

                            <form id="noteForm" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Type de note</label>
                                    <select id="typeNote"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="suivi_assiduite">Suivi d'assiduité</option>
                                        <option value="contact_telephonique">Contact téléphonique</option>
                                        <option value="visite_domicile">Visite à domicile</option>
                                        <option value="conseil_pastoral">Conseil pastoral</option>
                                        <option value="priere_specifique">Demande de prière spécifique</option>
                                        <option value="situation_personnelle">Situation personnelle</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Priorité</label>
                                    <select id="prioriteNote"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="normale">Normale</option>
                                        <option value="importante">Importante</option>
                                        <option value="urgente">Urgente</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Contenu de la note *</label>
                                    <textarea id="contenuNote" rows="5"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="Détaillez votre observation ou action entreprise..." required></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Action de suivi requise ?</label>
                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="suiviRequis" value="oui" class="mr-2">
                                            <span class="text-sm">Oui</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="suiviRequis" value="non" class="mr-2" checked>
                                            <span class="text-sm">Non</span>
                                        </label>
                                    </div>
                                </div>

                                <div id="actionSuivi" class="hidden">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Date limite pour le suivi</label>
                                    <input type="date" id="dateLimiteSuivi"
                                        class="w-full px-3 py-2 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        min="${new Date().toISOString().split('T')[0]}">
                                </div>
                            </form>

                            <div class="flex gap-3 mt-6">
                                <button onclick="closeModal('noteModal')"
                                    class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                                    Annuler
                                </button>
                                <button onclick="sauvegarderNote()"
                                    class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-200 shadow-md hover:shadow-lg font-medium">
                                    Sauvegarder
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', modalHtml);

                // Gérer l'affichage conditionnel du suivi
                document.querySelectorAll('input[name="suiviRequis"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        const actionDiv = document.getElementById('actionSuivi');
                        if (this.value === 'oui') {
                            actionDiv.classList.remove('hidden');
                        } else {
                            actionDiv.classList.add('hidden');
                        }
                    });
                });
            }

            function sauvegarderNote() {
                const noteData = {
                    membre_id: currentMembreId,
                    type: document.getElementById('typeNote').value,
                    priorite: document.getElementById('prioriteNote').value,
                    contenu: document.getElementById('contenuNote').value,
                    suivi_requis: document.querySelector('input[name="suiviRequis"]:checked').value === 'oui',
                    date_limite_suivi: document.getElementById('dateLimiteSuivi').value || null
                };

                if (!noteData.contenu.trim()) {
                    showNotification('Le contenu de la note est requis', 'error');
                    return;
                }

                fetch('#/api/notes-pastorales', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(noteData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Note ajoutée avec succès', 'success');
                        closeModal('noteModal');
                    } else {
                        showNotification(data.message || 'Erreur lors de la sauvegarde', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Erreur lors de la sauvegarde de la note', 'error');
                });
            }

            // =============================================
            // FONCTION MARQUER SUIVI
            // =============================================
            function marquerSuivi(membreId) {
                if (confirm('Marquer ce membre comme ayant été suivi ?')) {
                    fetch(`#/api/membres/${membreId}/marquer-suivi`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            date_suivi: new Date().toISOString(),
                            type_suivi: 'alerte_assiduite'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Membre marqué comme suivi', 'success');
                            // Masquer ou marquer visuellement le membre dans la liste
                            const membreCard = document.querySelector(`[data-membre-id="${membreId}"]`);
                            if (membreCard) {
                                membreCard.style.opacity = '0.6';
                                membreCard.insertAdjacentHTML('afterbegin',
                                    '<div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">Suivi effectué</div>'
                                );
                            }
                        } else {
                            showNotification(data.message || 'Erreur lors du marquage', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Erreur lors du marquage du suivi', 'error');
                    });
                }
            }

            // =============================================
            // FONCTIONS EXPORT ET ACTIONS EN MASSE
            // =============================================
            function exporterAlertes() {
                const modalHtml = `
                    <div id="exportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform transition-all">
                            <div class="text-center mb-6">
                                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-download text-white text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-slate-800">Exporter les alertes</h3>
                                <p class="text-slate-500 text-sm mt-1">Choisissez le format d'export</p>
                            </div>

                            <div class="space-y-3 mb-6">
                                <button onclick="exportFormat('pdf')"
                                    class="w-full flex items-center p-4 border-2 border-slate-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-semibold text-slate-800">PDF</div>
                                        <div class="text-sm text-slate-500">Rapport complet imprimable</div>
                                    </div>
                                </button>

                                <button onclick="exportFormat('excel')"
                                    class="w-full flex items-center p-4 border-2 border-slate-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-file-excel text-green-600 text-lg"></i>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-semibold text-slate-800">Excel</div>
                                        <div class="text-sm text-slate-500">Données pour analyse</div>
                                    </div>
                                </button>

                                <button onclick="exportFormat('json')"
                                    class="w-full flex items-center p-4 border-2 border-slate-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                                    <div class="flex-shrink-0 w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-code text-orange-600 text-lg"></i>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <div class="font-semibold text-slate-800">JSON</div>
                                        <div class="text-sm text-slate-500">Données structurées</div>
                                    </div>
                                </button>
                            </div>

                            <div class="flex gap-3">
                                <button onclick="closeModal('exportModal')"
                                    class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 transition-colors font-medium">
                                    Annuler
                                </button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', modalHtml);
            }

            function exportFormat(format) {
                const params = new URLSearchParams(window.location.search);
                params.set('format', format);

                showNotification('Export en cours...', 'info');
                window.location.href = `/private/alertes/export?${params.toString()}`;
                closeModal('exportModal');
            }

            function envoyerRappels() {
                if (confirm('Envoyer des rappels à tous les membres en alerte ?')) {
                    showNotification('Envoi des rappels en cours...', 'info');

                    fetch('#/api/alertes/envoyer-rappels', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(`${data.count || 0} rappels envoyés avec succès`, 'success');
                        } else {
                            showNotification(data.message || 'Erreur lors de l\'envoi des rappels', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Erreur lors de l\'envoi des rappels', 'error');
                    });
                }
            }

            function planifierVisites() {
                if (confirm('Planifier des visites pour tous les membres critiques ?')) {
                    showNotification('Planification des visites en cours...', 'info');

                    fetch('#/api/alertes/planifier-visites-masse', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            severite: 'critique',
                            date_debut: new Date(Date.now() + 24*60*60*1000).toISOString().split('T')[0], // Demain
                            responsable_defaut: 'pasteur'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showNotification(`${data.count || 0} visites planifiées`, 'success');
                        } else {
                            showNotification(data.message || 'Erreur lors de la planification', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Erreur lors de la planification des visites', 'error');
                    });
                }
            }

            // =============================================
            // FONCTIONS UTILITAIRES
            // =============================================
            function closeModal(modalId) {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.remove();
                }
            }

            function showNotification(message, type = 'info') {
                const colors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    warning: 'bg-yellow-500',
                    info: 'bg-blue-500'
                };

                const icons = {
                    success: 'fa-check-circle',
                    error: 'fa-exclamation-circle',
                    warning: 'fa-exclamation-triangle',
                    info: 'fa-info-circle'
                };

                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
                notification.innerHTML = `
                    <div class="flex items-center space-x-2">
                        <i class="fas ${icons[type]}"></i>
                        <span class="font-medium">${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                // Animation d'entrée
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);

                // Suppression automatique après 5 secondes
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }, 5000);
            }

            // Animation des cartes au chargement
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.bg-white\\/80, .bg-gradient-to-r');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });

                // Ajouter l'attribut data-membre-id aux cartes de membres pour le suivi
                document.querySelectorAll('[onclick*="contactMembre"]').forEach((element, index) => {
                    const card = element.closest('.bg-gradient-to-r');
                    if (card) {
                        // Extraire l'ID du membre depuis l'attribut onclick
                        const onclickValue = element.getAttribute('onclick');
                        const membreId = onclickValue.match(/'([^']+)'/)?.[1];
                        if (membreId) {
                            card.setAttribute('data-membre-id', membreId);
                            card.style.position = 'relative';
                        }
                    }
                });
            });

            // Fermeture des modales avec la touche Escape
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    const modals = ['contactModal', 'visiteModal', 'noteModal', 'exportModal'];
                    modals.forEach(modalId => {
                        const modal = document.getElementById(modalId);
                        if (modal) {
                            closeModal(modalId);
                        }
                    });
                }
            });

            // Gestion des clics à l'extérieur des modales
            document.addEventListener('click', function(event) {
                const modals = ['contactModal', 'visiteModal', 'noteModal', 'exportModal'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal && event.target === modal) {
                        closeModal(modalId);
                    }
                });
            });

            // =============================================
            // FONCTIONS DE RECHERCHE ET FILTRAGE
            // =============================================
            function filtrerAlertes(critere, valeur) {
                const cartes = document.querySelectorAll('[data-membre-id]');

                cartes.forEach(carte => {
                    let afficher = true;

                    switch(critere) {
                        case 'severite':
                            const badgeSeverite = carte.querySelector('.inline-flex.items-center.px-3.py-1.rounded-full');
                            if (badgeSeverite) {
                                const severiteActuelle = badgeSeverite.textContent.toLowerCase().trim();
                                afficher = valeur === 'tous' || severiteActuelle.includes(valeur.toLowerCase());
                            }
                            break;

                        case 'type_alerte':
                            const alerteElements = carte.querySelectorAll('.border-l-3');
                            let aLeTypeAlerte = false;
                            alerteElements.forEach(element => {
                                if (element.textContent.toLowerCase().includes(valeur.toLowerCase())) {
                                    aLeTypeAlerte = true;
                                }
                            });
                            afficher = valeur === 'tous' || aLeTypeAlerte;
                            break;
                    }

                    if (afficher) {
                        carte.style.display = 'block';
                        carte.style.opacity = '1';
                    } else {
                        carte.style.display = 'none';
                    }
                });

                // Mettre à jour le compteur
                const cartesVisibles = document.querySelectorAll('[data-membre-id][style*="block"]:not([style*="none"])').length;
                const compteur = document.querySelector('h2 span');
                if (compteur) {
                    compteur.textContent = `(${cartesVisibles})`;
                }
            }

            function rechercherMembres(terme) {
                const cartes = document.querySelectorAll('[data-membre-id]');
                const termeRecherche = terme.toLowerCase();

                cartes.forEach(carte => {
                    const nomElement = carte.querySelector('.text-lg.font-bold');
                    const emailElement = carte.querySelector('.fas.fa-envelope').nextSibling;
                    const telephoneElement = carte.querySelector('.fas.fa-phone').nextSibling;

                    let texteRecherche = '';
                    if (nomElement) texteRecherche += nomElement.textContent.toLowerCase();
                    if (emailElement) texteRecherche += emailElement.textContent.toLowerCase();
                    if (telephoneElement) texteRecherche += telephoneElement.textContent.toLowerCase();

                    const afficher = termeRecherche === '' || texteRecherche.includes(termeRecherche);

                    if (afficher) {
                        carte.style.display = 'block';
                        carte.style.opacity = '1';
                    } else {
                        carte.style.display = 'none';
                    }
                });
            }

            // =============================================
            // FONCTIONS DE MISE À JOUR EN TEMPS RÉEL
            // =============================================
            function actualiserStatistiques() {
                fetch('#/api/alertes/statistiques', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.statistiques) {
                        // Mettre à jour les cartes de statistiques
                        const stats = data.statistiques;

                        // Membres analysés
                        const totalElement = document.querySelector('.text-2xl.font-bold.text-slate-800');
                        if (totalElement) {
                            totalElement.textContent = stats.total_membres_actifs || 0;
                        }

                        // Autres statistiques peuvent être mises à jour de manière similaire
                        console.log('Statistiques actualisées:', stats);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de l\'actualisation des statistiques:', error);
                });
            }

            // =============================================
            // VALIDATION DES FORMULAIRES
            // =============================================
            function validerFormulaireVisite() {
                const date = document.getElementById('dateVisite').value;
                const heure = document.getElementById('heureVisite').value;
                const responsable = document.getElementById('responsableVisite').value;
                const objectif = document.getElementById('objectifVisite').value;

                if (!date || !heure || !responsable || !objectif) {
                    showNotification('Veuillez remplir tous les champs obligatoires', 'error');
                    return false;
                }

                // Vérifier que la date n'est pas dans le passé
                const dateVisite = new Date(date);
                const aujourd = new Date();
                aujourd.setHours(0, 0, 0, 0);

                if (dateVisite < aujourd) {
                    showNotification('La date de visite ne peut pas être dans le passé', 'error');
                    return false;
                }

                return true;
            }

            function validerFormulaireNote() {
                const type = document.getElementById('typeNote').value;
                const contenu = document.getElementById('contenuNote').value.trim();

                if (!type || !contenu) {
                    showNotification('Le type et le contenu de la note sont obligatoires', 'error');
                    return false;
                }

                if (contenu.length < 10) {
                    showNotification('Le contenu de la note doit contenir au moins 10 caractères', 'error');
                    return false;
                }

                return true;
            }

            // =============================================
            // INITIALISATION
            // =============================================
            // Vérifier la présence du token CSRF
            if (!document.querySelector('meta[name="csrf-token"]')) {
                console.warn('Token CSRF non trouvé. Ajoutez <meta name="csrf-token" content="{{ csrf_token() }}"> dans votre layout.');
            }

            // Actualiser les statistiques toutes les 5 minutes
            setInterval(actualiserStatistiques, 5 * 60 * 1000);

            // Message de bienvenue pour le debugging
            console.log('Système d\'alertes d\'assiduité initialisé avec succès');
            console.log('Fonctions disponibles: contactMembre, planifierVisite, ajouterNote, marquerSuivi, exporterAlertes');

            // =============================================
            // GESTION DES ERREURS GLOBALES
            // =============================================
            window.addEventListener('error', function(event) {
                console.error('Erreur JavaScript:', event.error);
                showNotification('Une erreur s\'est produite. Veuillez actualiser la page.', 'error');
            });

            // Gestion des erreurs de promesses non capturées
            window.addEventListener('unhandledrejection', function(event) {
                console.error('Promesse rejetée:', event.reason);
                showNotification('Erreur de connexion. Veuillez vérifier votre connexion internet.', 'error');
            });

            // Fonction pour déboguer les appels API
            function debugApiCall(url, method, data) {
                console.log(`API Call: ${method} ${url}`);
                if (data) {
                    console.log('Data:', data);
                }
            }
        </script>
    @endpush

@endsection
