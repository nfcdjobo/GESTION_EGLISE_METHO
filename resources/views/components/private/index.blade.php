@extends('layouts.private.main')
@section('title', 'Tableau de bord')

@section('content')
    <div class="space-y-8" id="dashboard-container">
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                Gestion des Permissions</h1>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-slate-600">Vue d'ensemble des activités de l'église</p>
                </div>
                <div class="flex gap-3 mt-4 md:mt-0">
                    <button onclick="exportDashboard()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Exporter
                    </button>
                    <button onclick="refreshDashboard()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Actualiser
                    </button>
                </div>
            </div>
        </div>

        <!-- Contrôles de période -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <label class="text-sm font-medium text-slate-700">Période:</label>
                </div>

                <div class="flex flex-wrap gap-2">
                    <button onclick="setPeriod('semaine')" class="period-btn px-4 py-2 text-sm font-medium rounded-lg border transition-colors hover:bg-slate-50" data-period="semaine">Semaine</button>
                    <button onclick="setPeriod('mensuelle')" class="period-btn px-4 py-2 text-sm font-medium rounded-lg border transition-colors hover:bg-slate-50 bg-blue-50 border-blue-200 text-blue-700" data-period="mensuelle">Mensuelle</button>
                    <button onclick="setPeriod('trimestrielle')" class="period-btn px-4 py-2 text-sm font-medium rounded-lg border transition-colors hover:bg-slate-50" data-period="trimestrielle">Trimestrielle</button>
                    <button onclick="setPeriod('semestrielle')" class="period-btn px-4 py-2 text-sm font-medium rounded-lg border transition-colors hover:bg-slate-50" data-period="semestrielle">Semestrielle</button>
                    <button onclick="setPeriod('annuelle')" class="period-btn px-4 py-2 text-sm font-medium rounded-lg border transition-colors hover:bg-slate-50" data-period="annuelle">Annuelle</button>
                </div>

                <div class="flex items-center gap-2 ml-auto">
                    <input type="date" id="start_date" class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <span class="text-slate-500">à</span>
                    <input type="date" id="end_date" class="px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <button onclick="applyCustomPeriod()" class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-2 rounded-lg text-sm transition-colors">Appliquer</button>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loading-indicator" class="hidden">
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <div class="flex items-center justify-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-3 text-slate-600">Chargement des données...</span>
                </div>
            </div>
        </div>

        <!-- KPIs Principaux -->
        <div id="kpis-section" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Membres -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white  transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Membres</p>
                        <p id="total-membres" class="text-3xl font-bold">0</p>
                        <p id="nouveaux-membres" class="text-blue-100 text-xs mt-1">+0 nouveaux</p>
                    </div>
                    <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Présence Moyenne -->
            <div
                class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white  transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Présence Moyenne</p>
                        <p id="avg-participants" class="text-3xl font-bold">0</p>
                        <p id="nombre-cultes" class="text-green-100 text-xs mt-1">0 cultes</p>
                    </div>
                    <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Offrandes -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white  transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Total Offrandes</p>
                        <p id="total-offrandes" class="text-3xl font-bold">0 FCFA</p>
                        <p id="ratio-presence-offrande" class="text-yellow-100 text-xs mt-1">0 FCFA/personne</p>
                    </div>
                    <div class="bg-yellow-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- FIMECO Progression -->
            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white  transition-transform">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">FIMECO Progression</p>
                        <p id="fimeco-progression" class="text-3xl font-bold">0%</p>
                        <p id="fimeco-nom" class="text-purple-100 text-xs mt-1">Aucun FIMECO actif</p>
                    </div>
                    <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Évolution des Membres -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-800">1. Évolution du Nombre de Membres Inscrits</h3>
                <div class="flex gap-2">
                    <button onclick="toggleMembersChart('line')"
                        class="chart-type-btn px-3 py-1 text-sm rounded-md bg-blue-100 text-blue-700"
                        data-chart="line">Ligne</button>
                    <button onclick="toggleMembersChart('bar')"
                        class="chart-type-btn px-3 py-1 text-sm rounded-md hover:bg-slate-100"
                        data-chart="bar">Barre</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="members-chart"></canvas>
            </div>
        </div>

        <!-- Section Présence aux Cultes -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-slate-800">2. Évolution de la Présence aux Cultes</h3>
                <div class="flex gap-2">
                    <button onclick="toggleCultesChart('area')"
                        class="chart-type-btn px-3 py-1 text-sm rounded-md bg-green-100 text-green-700"
                        data-chart="area">Aires</button>
                    <button onclick="toggleCultesChart('line')"
                        class="chart-type-btn px-3 py-1 text-sm rounded-md hover:bg-slate-100"
                        data-chart="line">Ligne</button>
                </div>
            </div>
            <div class="h-80">
                <canvas id="cultes-chart"></canvas>
            </div>
        </div>

        <!-- Section Offrandes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Évolution des Offrandes -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 mb-6">3. Évolution des Offrandes</h3>
                <div class="h-80">
                    <canvas id="offrandes-chart"></canvas>
                </div>
            </div>

            <!-- Ratio Présence/Offrande -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 mb-6">4. Ratio Présence/Offrande</h3>
                <div class="h-80">
                    <canvas id="ratio-presence-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Section FIMECO -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Évolution FIMECO -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 mb-6">6. Évolution des FIMECO</h3>
                <div class="h-80">
                    <canvas id="fimeco-chart"></canvas>
                </div>
            </div>

            <!-- Ratio Souscripteurs/Collecte -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <h3 class="text-xl font-bold text-slate-800 mb-6">5. Ratio Souscripteurs/Collecte FIMECO</h3>
                <div class="h-80">
                    <canvas id="ratio-fimeco-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Section Statistiques Détaillées -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Statistiques Membres -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <h4 class="text-lg font-semibold text-slate-800 mb-4">Détails Membres</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Membres Actifs</span>
                        <span id="detail-membres-actifs" class="font-semibold text-blue-600">0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Visiteurs</span>
                        <span id="detail-visiteurs" class="font-semibold text-green-600">0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Nouveaux Convertis</span>
                        <span id="detail-nouveaux-convertis" class="font-semibold text-purple-600">0</span>
                    </div>
                </div>
            </div>

            <!-- Statistiques Cultes -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <h4 class="text-lg font-semibold text-slate-800 mb-4">Détails Cultes</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Participants Physiques</span>
                        <span id="detail-physiques" class="font-semibold text-blue-600">0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Participants En Ligne</span>
                        <span id="detail-en-ligne" class="font-semibold text-green-600">0</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Nouveaux Visiteurs</span>
                        <span id="detail-nouveaux-visiteurs" class="font-semibold text-purple-600">0</span>
                    </div>
                </div>
            </div>

            <!-- Tendances -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-slate-200">
                <h4 class="text-lg font-semibold text-slate-800 mb-4">Tendances</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Évolution Offrandes</span>
                        <span id="trend-offrandes" class="font-semibold">0%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Période Actuelle</span>
                        <span id="current-offrandes" class="font-semibold text-blue-600">0 FCFA</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600">Période Précédente</span>
                        <span id="previous-offrandes" class="font-semibold text-slate-600">0 FCFA</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Variables globales
            let currentPeriod = 'mensuelle';
            let currentData = null;
            let charts = {};

            // Configuration des couleurs
            const colors = {
                primary: '#3b82f6',
                success: '#10b981',
                warning: '#f59e0b',
                danger: '#ef4444',
                purple: '#8b5cf6',
                info: '#06b6d4'
            };

            // Initialisation au chargement de la page
            document.addEventListener('DOMContentLoaded', function () {
                loadDashboardData();
            });

            // Fonction pour définir la période
            function setPeriod(period) {
                currentPeriod = period;

                // Mettre à jour les boutons
                document.querySelectorAll('.period-btn').forEach(btn => {
                    btn.classList.remove('bg-blue-50', 'border-blue-200', 'text-blue-700');
                    btn.classList.add('hover:bg-slate-50');
                });

                const activeBtn = document.querySelector(`[data-period="${period}"]`);
                if (activeBtn) {
                    activeBtn.classList.add('bg-blue-50', 'border-blue-200', 'text-blue-700');
                    activeBtn.classList.remove('hover:bg-slate-50');
                }

                loadDashboardData();
            }

            // Fonction pour appliquer une période personnalisée
            function applyCustomPeriod() {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;

                if (startDate && endDate) {
                    loadDashboardData(null, startDate, endDate);
                } else {
                    alert('Veuillez sélectionner les deux dates');
                }
            }

            // Fonction pour charger les données du dashboard
            function loadDashboardData(period = null, startDate = null, endDate = null) {
                const loadingIndicator = document.getElementById('loading-indicator');
                const dashboardContainer = document.getElementById('dashboard-container');

                // Afficher le loader
                loadingIndicator.classList.remove('hidden');

                // Construire les paramètres de la requête
                const params = new URLSearchParams();
                params.append('period', period || currentPeriod);
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);

                // Faire la requête AJAX
                fetch(`{{route('private.dashboard')}}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            currentData = data.data;
                            updateDashboard(data.data);
                        } else {
                            console.error('Erreur:', data.message);
                            alert('Erreur lors du chargement des données: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur réseau:', error);
                        alert('Erreur de connexion. Veuillez réessayer.');
                    })
                    .finally(() => {
                        // Masquer le loader
                        loadingIndicator.classList.add('hidden');
                    });
            }

            // Fonction pour mettre à jour le dashboard
            function updateDashboard(data) {
                // Mettre à jour les KPIs
                updateKPIs(data.kpis);

                // Mettre à jour les graphiques
                updateMembersChart(data.members_evolution);
                updateCultesChart(data.culte_attendance);
                updateOffrandesChart(data.offrandes_evolution);
                updateRatioPresenceChart(data.presence_offrande_ratio);
                updateFimecoChart(data.fimeco_evolution);
                updateRatioFimecoChart(data.souscripteur_fimeco_ratio);

                // Mettre à jour les détails
                updateDetails(data);
            }

            // Fonction pour mettre à jour les KPIs
            function updateKPIs(kpis) {
                document.getElementById('total-membres').textContent = kpis.total_membres.toLocaleString();
                document.getElementById('nouveaux-membres').textContent = `+${kpis.nouveaux_membres} nouveaux`;
                document.getElementById('avg-participants').textContent = kpis.avg_participants.toLocaleString();
                document.getElementById('nombre-cultes').textContent = `${kpis.nombre_cultes} cultes`;
                document.getElementById('total-offrandes').textContent = `${kpis.total_offrandes.toLocaleString()} FCFA`;
                document.getElementById('fimeco-progression').textContent = `${kpis.fimeco_progression}%`;
                document.getElementById('fimeco-nom').textContent = kpis.fimeco_nom;
            }

            // Fonction pour mettre à jour les détails
            function updateDetails(data) {
                // Calculer les moyennes pour les détails
                const membersData = data.members_evolution;
                const cultesData = data.culte_attendance;
                const trends = data.trends;

                if (membersData.length > 0) {
                    const lastMember = membersData[membersData.length - 1];
                    document.getElementById('detail-membres-actifs').textContent = lastMember.membres_actifs || 0;
                    document.getElementById('detail-visiteurs').textContent = lastMember.visiteurs || 0;
                    document.getElementById('detail-nouveaux-convertis').textContent = lastMember.nouveaux_convertis || 0;
                }

                if (cultesData.length > 0) {
                    const totalPhysiques = cultesData.reduce((sum, item) => sum + (item.participants_physiques || 0), 0);
                    const totalEnLigne = cultesData.reduce((sum, item) => sum + (item.participants_en_ligne || 0), 0);
                    const totalNouveauxVisiteurs = cultesData.reduce((sum, item) => sum + (item.nouveaux_visiteurs || 0), 0);

                    document.getElementById('detail-physiques').textContent = totalPhysiques;
                    document.getElementById('detail-en-ligne').textContent = totalEnLigne;
                    document.getElementById('detail-nouveaux-visiteurs').textContent = totalNouveauxVisiteurs;
                }

                // Mettre à jour les ratios dans les KPIs
                if (data.ratios) {
                    document.getElementById('ratio-presence-offrande').textContent = `${data.ratios.presence_offrande_ratio.toLocaleString()} FCFA/personne`;
                }

                // Mettre à jour les tendances
                if (trends) {
                    const trendElement = document.getElementById('trend-offrandes');
                    const trendValue = trends.offrandes_trend;
                    trendElement.textContent = `${trendValue > 0 ? '+' : ''}${trendValue}%`;
                    trendElement.className = `font-semibold ${trendValue >= 0 ? 'text-green-600' : 'text-red-600'}`;

                    document.getElementById('current-offrandes').textContent = `${trends.current_offrandes.toLocaleString()} FCFA`;
                    document.getElementById('previous-offrandes').textContent = `${trends.previous_offrandes.toLocaleString()} FCFA`;
                }
            }


            // Fonction pour créer/mettre à jour le graphique des membres
            function updateMembersChart(data) {
                const ctx = document.getElementById('members-chart').getContext('2d');

                if (charts.members) {
                    charts.members.destroy();
                }
                charts.members = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => item.period),
                        datasets: [
                            {
                                label: 'Total Membres',
                                data: data.map(item => item.total_membres),
                                borderColor: colors.primary,
                                backgroundColor: colors.primary + '20',
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Membres Actifs',
                                data: data.map(item => item.membres_actifs),
                                borderColor: colors.info,
                                backgroundColor: colors.info + '20',
                                tension: 0.4
                            },
                            {
                                label: 'Nouveaux Convertis',
                                data: data.map(item => item.nouveaux_convertis),
                                borderColor: colors.purple,
                                backgroundColor: colors.purple + '20',
                                tension: 0.4
                            },
                            {
                                label: 'Visiteurs',
                                data: data.map(item => item.visiteurs),
                                borderColor: colors.warning,
                                backgroundColor: colors.warning + '20',
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f1f5f9'
                                }
                            },
                            x: {
                                grid: {
                                    color: '#f1f5f9'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            filler: {
                                propagate: false
                            }
                        },
                        interaction: {
                            intersect: false
                        }
                    }
                });
            }


            // Fonction pour créer/mettre à jour le graphique des offrandes
            function updateOffrandesChart(data) {
                const ctx = document.getElementById('offrandes-chart').getContext('2d');

                if (charts.offrandes) {
                    charts.offrandes.destroy();
                }

                charts.offrandes = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(item => item.period),
                        datasets: [
                            {
                                label: 'Dîmes',
                                data: data.map(item => item.dimes),
                                backgroundColor: colors.primary + '80',
                                borderColor: colors.primary,
                                borderWidth: 1
                            },
                            {
                                label: 'Offrandes Ordinaires',
                                data: data.map(item => item.offrandes_ordinaires),
                                backgroundColor: colors.success + '80',
                                borderColor: colors.success,
                                borderWidth: 1
                            },
                            {
                                label: 'Offrandes Spéciales',
                                data: data.map(item => item.offrandes_speciales),
                                backgroundColor: colors.warning + '80',
                                borderColor: colors.warning,
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f1f5f9'
                                },
                                ticks: {
                                    callback: function (value) {
                                        return value.toLocaleString() + ' FCFA';
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    color: '#f1f5f9'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' FCFA';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Fonction pour créer/mettre à jour le graphique ratio présence/offrande
            function updateRatioPresenceChart(data) {
                const ctx = document.getElementById('ratio-presence-chart').getContext('2d');

                if (charts.ratioPresence) {
                    charts.ratioPresence.destroy();
                }

                charts.ratioPresence = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => item.period),
                        datasets: [
                            {
                                label: 'Participants Moyens',
                                data: data.map(item => item.avg_participants),
                                borderColor: colors.primary,
                                backgroundColor: colors.primary + '20',
                                yAxisID: 'y'
                            },
                            {
                                label: 'Ratio FCFA/Personne',
                                data: data.map(item => item.ratio_offrande_par_personne),
                                borderColor: colors.danger,
                                backgroundColor: colors.danger + '20',
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                grid: {
                                    color: '#f1f5f9'
                                },
                                title: {
                                    display: true,
                                    text: 'Participants'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                grid: {
                                    drawOnChartArea: false
                                },
                                title: {
                                    display: true,
                                    text: 'FCFA/Personne'
                                },
                                ticks: {
                                    callback: function (value) {
                                        return value.toLocaleString() + ' FCFA';
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });
            }

            // Fonction pour créer/mettre à jour le graphique FIMECO
            function updateFimecoChart(data) {
                const ctx = document.getElementById('fimeco-chart').getContext('2d');

                if (charts.fimeco) {
                    charts.fimeco.destroy();
                }

                if (!data || data.length === 0) {
                    // Afficher un message si pas de données FIMECO
                    ctx.font = '16px Arial';
                    ctx.fillStyle = '#64748b';
                    ctx.textAlign = 'center';
                    ctx.fillText('Aucune donnée FIMECO disponible', ctx.canvas.width / 2, ctx.canvas.height / 2);
                    return;
                }

                charts.fimeco = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => item.period),
                        datasets: [
                            {
                                label: 'Collecte Totale',
                                data: data.map(item => item.collecte_totale),
                                borderColor: colors.success,
                                backgroundColor: colors.success + '20',
                                tension: 0.4
                            },
                            {
                                label: 'Cible Totale',
                                data: data.map(item => item.cible_totale),
                                borderColor: colors.danger,
                                backgroundColor: colors.danger + '20',
                                borderDash: [5, 5],
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f1f5f9'
                                },
                                ticks: {
                                    callback: function (value) {
                                        return value.toLocaleString() + ' FCFA';
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (context) {
                                        return context.dataset.label + ': ' + context.parsed.y.toLocaleString() + ' FCFA';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Fonction pour créer/mettre à jour le graphique ratio FIMECO
            function updateRatioFimecoChart(data) {
                const ctx = document.getElementById('ratio-fimeco-chart').getContext('2d');

                if (charts.ratioFimeco) {
                    charts.ratioFimeco.destroy();
                }

                if (!data || data.length === 0) {
                    // Afficher un message si pas de données
                    ctx.font = '16px Arial';
                    ctx.fillStyle = '#64748b';
                    ctx.textAlign = 'center';
                    ctx.fillText('Aucune donnée de souscription disponible', ctx.canvas.width / 2, ctx.canvas.height / 2);
                    return;
                }

                charts.ratioFimeco = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(item => item.period),
                        datasets: [
                            {
                                label: 'Nombre Souscripteurs',
                                data: data.map(item => item.nombre_souscripteurs),
                                backgroundColor: colors.purple + '80',
                                borderColor: colors.purple,
                                borderWidth: 1,
                                yAxisID: 'y'
                            },
                            {
                                label: 'Ratio Collecte/Souscripteur',
                                data: data.map(item => item.ratio_collecte_par_souscripteur),
                                type: 'line',
                                borderColor: colors.info,
                                backgroundColor: colors.info + '20',
                                borderWidth: 2,
                                yAxisID: 'y1'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Souscripteurs'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                beginAtZero: true,
                                grid: {
                                    drawOnChartArea: false
                                },
                                title: {
                                    display: true,
                                    text: 'FCFA/Souscripteur'
                                },
                                ticks: {
                                    callback: function (value) {
                                        return value.toLocaleString() + ' FCFA';
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });
            }

            // Fonction pour changer le type de graphique des membres
            function toggleMembersChart(type) {
                if (!currentData) return;

                // Mettre à jour les boutons
                document.querySelectorAll('[data-chart]').forEach(btn => {
                    btn.classList.remove('bg-blue-100', 'text-blue-700');
                    btn.classList.add('hover:bg-slate-100');
                });

                const activeBtn = document.querySelector(`[data-chart="${type}"]`);
                if (activeBtn) {
                    activeBtn.classList.add('bg-blue-100', 'text-blue-700');
                    activeBtn.classList.remove('hover:bg-slate-100');
                }

                // Recréer le graphique avec le nouveau type
                const ctx = document.getElementById('members-chart').getContext('2d');

                if (charts.members) {
                    charts.members.destroy();
                }

                charts.members = new Chart(ctx, {
                    type: type,
                    data: {
                        labels: currentData.members_evolution.map(item => item.period),
                        datasets: [
                            {
                                label: 'Total Membres',
                                data: currentData.members_evolution.map(item => item.total_membres),
                                borderColor: colors.primary,
                                backgroundColor: colors.primary + (type === 'bar' ? '80' : '20'),
                                tension: 0.4,
                                fill: type === 'line'
                            },
                            {
                                label: 'Membres Actifs',
                                data: currentData.members_evolution.map(item => item.membres_actifs),
                                borderColor: colors.info,
                                backgroundColor: colors.info + (type === 'bar' ? '80' : '20'),
                                tension: 0.4
                            },
                            {
                                label: 'Nouveaux Convertis',
                                data: currentData.members_evolution.map(item => item.nouveaux_convertis),
                                borderColor: colors.purple,
                                backgroundColor: colors.purple + (type === 'bar' ? '80' : '20'),
                                tension: 0.4
                            },
                            {
                                label: 'Visiteurs',
                                data: currentData.members_evolution.map(item => item.visiteurs),
                                borderColor: colors.warning,
                                backgroundColor: colors.warning + (type === 'bar' ? '80' : '20'),
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f1f5f9'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });
            }

            // Fonction pour créer/mettre à jour le graphique des cultes
            function updateCultesChart(data) {
                const ctx = document.getElementById('cultes-chart').getContext('2d');

                if (charts.cultes) {
                    charts.cultes.destroy();
                }


                charts.cultes = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => item.period),
                        datasets: [
                            {
                                label: 'Participants Physiques',
                                data: data.map(item => item.participants_physiques),
                                borderColor: colors.primary,
                                backgroundColor: colors.primary + '40',
                                fill: '+1'
                            },
                            {
                                label: 'Participants En Ligne',
                                data: data.map(item => item.participants_en_ligne),
                                borderColor: colors.success,
                                backgroundColor: colors.success + '40',
                                fill: 'origin'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f1f5f9'
                                }
                            },
                            x: {
                                grid: {
                                    color: '#f1f5f9'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });
            }




            // Fonction pour changer le type de graphique des cultes
            function toggleCultesChart(type) {
                if (!currentData) return;

                // Recréer le graphique avec le nouveau type
                updateCultesChart(currentData.culte_attendance);
            }

            // Fonction pour actualiser le dashboard
            function refreshDashboard() {
                loadDashboardData();
            }



            // Fonction pour exporter les données avec choix du format
            function exportDashboard() {
                if (!currentData) {
                    alert('Aucune donnée à exporter');
                    return;
                }

                // Créer une modale pour choisir le format
                showExportModal();
            }


            // Afficher la modale de choix du format d'export
            function showExportModal() {
                // Créer la modale dynamiquement
                const modal = document.createElement('div');
                modal.id = 'exportModal';
                modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                modal.innerHTML = `
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-bold text-gray-900">Exporter le Dashboard</h3>
                                    <button onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 mb-3">Choisissez le format d'export souhaité :</p>

                                    <div class="space-y-3">
                                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input type="radio" name="exportFormat" value="excel" checked class="mr-3">
                                            <div class="flex items-center">
                                                <svg class="w-8 h-8 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm0 2h12v10H4V5z"/>
                                                    <path d="M6 7h8v2H6V7zm0 4h8v2H6v-2z"/>
                                                </svg>
                                                <div>
                                                    <div class="font-medium text-gray-900">Excel (.xlsx)</div>
                                                    <div class="text-sm text-gray-500">Fichier Excel avec mise en forme</div>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input type="radio" name="exportFormat" value="pdf" class="mr-3">
                                            <div class="flex items-center">
                                                <svg class="w-8 h-8 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm0 2h12v10H4V5z"/>
                                                    <path d="M8 7v6l4-3-4-3z"/>
                                                </svg>
                                                <div>
                                                    <div class="font-medium text-gray-900">PDF (.pdf)</div>
                                                    <div class="text-sm text-gray-500">Document PDF formaté pour impression</div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <button onclick="closeExportModal()"
                                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg transition-colors">
                                        Annuler
                                    </button>
                                    <button onclick="processExport()"
                                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Exporter
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                document.body.appendChild(modal);
            }


            // Fermer la modale d'export
            function closeExportModal() {
                const modal = document.getElementById('exportModal');
                if (modal) {
                    modal.remove();
                }
            }

            // Traiter l'export selon le format choisi
            function processExport() {
                const selectedFormat = document.querySelector('input[name="exportFormat"]:checked')?.value;

                if (!selectedFormat) {
                    alert('Veuillez sélectionner un format d\'export');
                    return;
                }

                closeExportModal();

                // Afficher un indicateur de chargement
                showExportProgress();

                // Préparer les paramètres
                const params = new URLSearchParams();
                params.append('period', currentPeriod);
                params.append('format', selectedFormat);

                // Ajouter les dates personnalisées si elles existent
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);

                // Faire la requête d'export
                fetch("{{route('private.dashboard.exporte')}}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: params.toString()
                })
                    .then(response => {
                        hideExportProgress();

                        if (response.ok) {
                            // Pour les fichiers, on peut rediriger vers l'URL d'export
                            if (selectedFormat === 'excel' || selectedFormat === 'pdf') {
                                // Créer un lien de téléchargement avec les paramètres
                                const downloadUrl = "{{route('private.dashboard.exporte')}}?" + params.toString();
                                window.open(downloadUrl, '_blank');

                                showSuccessMessage(`Fichier ${selectedFormat.toUpperCase()} généré avec succès !`);
                            }
                        } else {
                            throw new Error('Erreur lors de la génération du fichier');
                        }
                    })
                    .catch(error => {
                        hideExportProgress();
                        console.error('Erreur export:', error);
                        showErrorMessage('Erreur lors de l\'export des données. Veuillez réessayer.');
                    });
            }

            // Afficher l'indicateur de progression d'export
            function showExportProgress() {
                const progressModal = document.createElement('div');
                progressModal.id = 'exportProgress';
                progressModal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50';
                progressModal.innerHTML = `
                        <div class="relative top-1/2 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white transform -translate-y-1/2">
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-green-600 mx-auto mb-4"></div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Génération en cours...</h3>
                                <p class="text-sm text-gray-600">Veuillez patienter pendant la création du fichier.</p>
                            </div>
                        </div>
                    `;

                document.body.appendChild(progressModal);
            }

            // Masquer l'indicateur de progression
            function hideExportProgress() {
                const progressModal = document.getElementById('exportProgress');
                if (progressModal) {
                    progressModal.remove();
                }
            }

            // Afficher un message de succès
            function showSuccessMessage(message) {
                const successAlert = document.createElement('div');
                successAlert.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50';
                successAlert.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>${message}</span>
                        </div>
                    `;

                document.body.appendChild(successAlert);

                // Supprimer automatiquement après 5 secondes
                setTimeout(() => {
                    if (successAlert.parentNode) {
                        successAlert.remove();
                    }
                }, 5000);
            }

            // Afficher un message d'erreur
            function showErrorMessage(message) {
                const errorAlert = document.createElement('div');
                errorAlert.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50';
                errorAlert.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <span>${message}</span>
                        </div>
                    `;

                document.body.appendChild(errorAlert);

                // Supprimer automatiquement après 7 secondes
                setTimeout(() => {
                    if (errorAlert.parentNode) {
                        errorAlert.remove();
                    }
                }, 7000);
            }

            // Fermer la modale en cliquant à l'extérieur
            document.addEventListener('click', function (event) {
                const modal = document.getElementById('exportModal');
                if (modal && event.target === modal) {
                    closeExportModal();
                }
            });




            // Gestion des erreurs globales
            window.addEventListener('error', function (e) {
                console.error('Erreur JavaScript:', e.error);
            });

            // Fonction utilitaire pour formater les nombres
            function formatNumber(num) {
                return new Intl.NumberFormat('fr-FR').format(num);
            }

            // Fonction utilitaire pour formater les montants
            function formatCurrency(amount) {
                return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
            }
        </script>
    @endpush
@endsection
