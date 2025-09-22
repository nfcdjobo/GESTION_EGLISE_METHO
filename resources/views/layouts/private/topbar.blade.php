<!-- Top Bar -->
<header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200">
    <div class="flex items-center justify-between h-16 px-6">
        <div class="flex items-center space-x-4">
            <button id="mobile-menu-btn"
                class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <div class="flex items-center space-x-2">
                <div class="h-10 w-10 rounded-full  rounded-lg flex items-center justify-center">
                    <img class="h-10 w-10 rounded-full object-cover ring-2 ring-blue-500"
                        src="https://www.cevaa.org/la-communaute/fiches-deglises/afrique-occidentale-centrafrique/logo-emci.png/image_preview"
                        alt="Logo église" />
                </div>
                <span
                    class="font-bold text-xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent hidden sm:block">Église
                    Méthodiste</span>
            </div>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Search Bar (Hidden on mobile) -->
            <div class="hidden md:flex items-center bg-slate-100 rounded-xl px-4 py-2 w-64">
                <i class="fas fa-search text-slate-400 mr-2"></i>
                <input type="text" placeholder="Rechercher..."
                    class="bg-transparent outline-none text-sm text-slate-600 w-full">
            </div>

            <!-- Notifications & Alertes -->
            <div class="flex items-center space-x-3">
                <!-- Alertes d'assiduité -->
                <div class="relative">
                    <button onclick="toggleAlertesMenu()"
                        class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors relative group">
                        <i
                            class="fas fa-exclamation-triangle text-lg group-hover:text-orange-600 transition-colors"></i>
                        @php
                            // Simulation des données d'alertes - remplacer par vos vraies données
                            $alertesAssiduite = app(App\Http\Controllers\Private\Web\AlerteController::class)->statistiquesAssiduite(request())->getData();
                            $totalAlertes = $alertesAssiduite->statistiques->membres_critiques + $alertesAssiduite->statistiques->membres_alerte_dimanches;
                        @endphp
                        @if($totalAlertes > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">
                                {{ min($totalAlertes, 99) }}{{ $totalAlertes > 99 ? '+' : '' }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown des alertes d'assiduité -->
                    <div id="alertes-menu"
                        class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-slate-200 opacity-0 invisible transform scale-95 transition-all duration-200 z-50">

                        <div class="p-4 border-b border-slate-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-slate-800 flex items-center">
                                    <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                                    Alertes d'Assiduité
                                </h3>
                                <span class="text-xs text-slate-500">Aujourd'hui</span>
                            </div>
                        </div>

                        <div class="max-h-96 overflow-y-auto">
                            @if($totalAlertes > 0)
                                <!-- Résumé des statistiques -->
                                <div class="p-4 bg-gradient-to-r from-red-50 to-orange-50 border-b border-slate-200">
                                    <div class="grid grid-cols-2 gap-3 text-center">
                                        <div class="bg-white/80 rounded-lg p-2">
                                            <div class="text-lg font-bold text-red-600">
                                                {{ $alertesAssiduite->statistiques->membres_critiques }}
                                            </div>
                                            <div class="text-xs text-slate-600">Critique</div>
                                        </div>
                                        <div class="bg-white/80 rounded-lg p-2">
                                            <div class="text-lg font-bold text-orange-600">
                                                {{ $alertesAssiduite->statistiques->membres_alerte_dimanches }}
                                            </div>
                                            <div class="text-xs text-slate-600">Attention</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alertes spécifiques -->
                                <div class="divide-y divide-slate-100">
                                    @if($alertesAssiduite->statistiques->membres_critiques > 0)
                                        <a href="{{ route('private.alertes.assiduite-faible', ['severite' => 'critique']) }}"
                                            class="block p-4 hover:bg-red-50 transition-colors group">
                                            <div class="flex items-start space-x-3">
                                                <div
                                                    class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center group-hover:bg-red-200 transition-colors">
                                                    <i class="fas fa-exclamation-circle text-red-600 text-sm"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-slate-900">
                                                        {{ $alertesAssiduite->statistiques->membres_critiques }} membre(s) en
                                                        situation critique
                                                    </div>
                                                    <div class="text-xs text-slate-500 mt-1">
                                                        Absence prolongée aux cultes - Suivi urgent requis
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 text-xs text-slate-400">
                                                    <i class="fas fa-chevron-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    @endif

                                    @if($alertesAssiduite->statistiques->membres_alerte_dimanches > 0)
                                        <a href="{{ route('private.alertes.assiduite-faible', ['type_alerte' => 'dimanches_successifs']) }}"
                                            class="block p-4 hover:bg-orange-50 transition-colors group">
                                            <div class="flex items-start space-x-3">
                                                <div
                                                    class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                                                    <i class="fas fa-calendar-times text-orange-600 text-sm"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-slate-900">
                                                        {{ $alertesAssiduite->statistiques->membres_alerte_dimanches }}
                                                        membre(s) absent(s) aux dimanches
                                                    </div>
                                                    <div class="text-xs text-slate-500 mt-1">
                                                        2+ dimanches consécutifs manqués
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 text-xs text-slate-400">
                                                    <i class="fas fa-chevron-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    @endif

                                    @if($alertesAssiduite->statistiques->membres_alerte_mensuelle > 0)
                                        <a href="{{ route('private.alertes.assiduite-faible', ['type_alerte' => 'cultes_mensuels']) }}"
                                            class="block p-4 hover:bg-yellow-50 transition-colors group">
                                            <div class="flex items-start space-x-3">
                                                <div
                                                    class="flex-shrink-0 w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                                                    <i class="fas fa-chart-line text-yellow-600 text-sm"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-slate-900">
                                                        {{ $alertesAssiduite->statistiques->membres_alerte_mensuelle }}
                                                        membre(s) faible participation mensuelle
                                                    </div>
                                                    <div class="text-xs text-slate-500 mt-1">
                                                        Moins de 2 cultes ce mois
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 text-xs text-slate-400">
                                                    <i class="fas fa-chevron-right"></i>
                                                </div>
                                            </div>
                                        </a>
                                    @endif
                                </div>

                                <!-- Actions rapides -->
                                <div class="p-4 bg-slate-50 border-t border-slate-200">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('private.alertes.assiduite-faible') }}"
                                            class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            Voir tout
                                        </a>
                                        <button onclick="exporterAlertes()"
                                            class="flex-1 text-center px-3 py-2 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition-colors">
                                            Exporter
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- Aucune alerte -->
                                <div class="p-6 text-center">
                                    <div
                                        class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                    </div>
                                    <div class="text-sm font-medium text-slate-900 mb-1">Aucune alerte</div>
                                    <div class="text-xs text-slate-500">Tous les membres ont une bonne assiduité</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative">
                <button onclick="toggleUserMenu()"
                    class="flex items-center space-x-2 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                    <img class="h-9 w-9 rounded-full object-cover ring-2 ring-blue-500"
                        src="{{auth()->user()->photo_profil ? Storage::url(auth()->user()->photo_profil) : 'https://ui-avatars.com/api/?name=' . auth()->user()->nom . '+' . auth()->user()->prenom . '&background=3b82f6&color=fff'}}"
                        alt="{{auth()->user()->nom_complet}}" />
                    <span class="text-sm font-medium text-slate-700 hidden sm:block">{{auth()->user()->nom}}</span>
                    <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                </button>
                <div id="user-menu"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 opacity-0 invisible transform scale-95 transition-all duration-200 z-50">
                    <a href="#"
                        class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 rounded-t-xl transition-colors">
                        <i class="fas fa-user mr-3 text-slate-400"></i>
                        Mon profil
                    </a>
                    <a href="{{route('private.parametres.index')}}"
                        class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                        <i class="fas fa-cog mr-3 text-slate-400"></i>
                        Paramètres
                    </a>
                    <a href="#"
                        class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                        <i class="fas fa-question-circle mr-3 text-slate-400"></i>
                        Aides
                    </a>
                    <div class="border-t border-slate-200"></div>

                    <form action="{{ route('security.logout') }}" method="post">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors appearance-none bg-transparent border-0 focus:outline-none cursor-pointer rounded-b-xl text-left">
                            <i class="fas fa-sign-out-alt mr-3 text-slate-400"></i>
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleAlertesMenu() {
        const menu = document.getElementById('alertes-menu');
        const isVisible = !menu.classList.contains('invisible');

        // Fermer tous les autres menus
        closeAllMenus();

        if (!isVisible) {
            menu.classList.remove('invisible', 'opacity-0', 'scale-95');
            menu.classList.add('visible', 'opacity-100', 'scale-100');
        }
    }

    function toggleNotificationsMenu() {
        const menu = document.getElementById('notifications-menu');
        const isVisible = !menu.classList.contains('invisible');

        // Fermer tous les autres menus
        closeAllMenus();

        if (!isVisible) {
            menu.classList.remove('invisible', 'opacity-0', 'scale-95');
            menu.classList.add('visible', 'opacity-100', 'scale-100');
        }
    }

    function toggleUserMenu() {
        const menu = document.getElementById('user-menu');
        const isVisible = !menu.classList.contains('invisible');

        // Fermer tous les autres menus
        closeAllMenus();

        if (!isVisible) {
            menu.classList.remove('invisible', 'opacity-0', 'scale-95');
            menu.classList.add('visible', 'opacity-100', 'scale-100');
        }
    }

    function closeAllMenus() {
        const menus = ['alertes-menu', 'notifications-menu', 'user-menu'];
        menus.forEach(menuId => {
            const menu = document.getElementById(menuId);
            if (menu) {
                menu.classList.add('invisible', 'opacity-0', 'scale-95');
                menu.classList.remove('visible', 'opacity-100', 'scale-100');
            }
        });
    }

    function exporterAlertes() {
        window.location.href = "{{ route('private.alertes.assiduite-faible', ['format' => 'json']) }}";
    }

    // Fermer les menus en cliquant à l'extérieur
    document.addEventListener('click', function (event) {
        const isClickInsideMenu = event.target.closest('#alertes-menu, #notifications-menu, #user-menu, [onclick*="toggle"]');
        if (!isClickInsideMenu) {
            closeAllMenus();
        }
    });

    // Actualiser les alertes périodiquement (toutes les 5 minutes)
    setInterval(function () {
        // Ici vous pouvez ajouter une requête AJAX pour actualiser les alertes
        console.log('Actualisation des alertes...');
    }, 300000); // 5 minutes
</script>
