<!-- Sidebar -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transform -translate-x-full transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 border-r border-slate-200">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-center h-16 bg-gradient-to-r from-blue-600 to-purple-600 border-b border-blue-700">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">

                <img src="{{$AppParametres->logo ? Storage::url($AppParametres->logo) :  ''}}"
                    alt="Logo {{ $AppParametres->nom_eglise ?? 'Logo église' }}"  class="h-10 w-10 rounded-full object-cover ring-2 ring-blue-500">
            </div>
            <span class="ml-2 text-white font-bold text-lg">{{ $AppParametres->nom_eglise ?? 'Méthodiste' }}</span>
        </div>
    </div>

    <!-- User Profile Section -->
    <div class="p-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-blue-50">
        <div class="flex items-center space-x-3">
            <div class="relative">
                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-blue-500"
                    src="{{ auth()->user()->photo_profil ? Storage::url(auth()->user()->photo_profil) : 'https://ui-avatars.com/api/?name=' . auth()->user()->nom . '+' . auth()->user()->prenom . '&background=3b82f6&color=fff' }}"
                    alt="{{ auth()->user()->photo_profil }}" />
                <span class="absolute bottom-0 right-0 h-3 w-3 bg-green-400 rounded-full border-2 border-white animate-pulse"></span>
            </div>
            <div>
                <h6 class="text-sm font-semibold text-slate-900">
                    {{ (auth()->user()->sexe == 'masculin' ? 'Mr. ' : 'Mme/Mlle. ') . auth()->user()->nom }}</h6>
                <div class="flex items-center space-x-1">
                    <span class="h-2 w-2 bg-green-400 rounded-full animate-pulse"></span>
                    <p class="text-xs text-slate-500">En ligne</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto max-h-[80%]" id="sidebar-nav">
        <div class="p-4">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Navigation</h4>
            <nav class="space-y-2">

                @can('dashboard.read')
                    <!-- Dashboard -->
                    <a href="{{ route('private.dashboard') }}"
                        class="nav-item flex items-center w-full px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.dashboard') ? 'text-white bg-gradient-to-r from-blue-500 to-purple-500 shadow-lg ring-2 ring-blue-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 hover:text-blue-600' }} rounded-xl transition-all duration-200 group"
                        data-route="private.dashboard">
                        <i
                            class="fas fa-chart-line mr-3 {{ request()->routeIs('private.dashboard') ? 'text-yellow-300' : 'text-blue-500 group-hover:text-blue-600' }}"></i>
                        <span>Tableau de bord</span>
                    </a>
                @endcan

                <!-- Section : Administration -->
                @canany(['roles.read', 'permissions.read', 'classes.read', 'parametresdons.read'])
                    <div class="pt-4 pb-2">
                        <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Administration</h5>
                    </div>

                    <!-- Rôles -->
                    @can('roles.read')
                        <a href="{{ route('private.roles.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.roles.*') ? 'text-white bg-gradient-to-r from-indigo-500 to-blue-500 shadow-lg ring-2 ring-indigo-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 hover:text-indigo-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.roles.index">
                            <i
                                class="fas fa-user-tag {{ request()->routeIs('private.roles.*') ? 'text-yellow-300' : 'text-indigo-500 group-hover:text-indigo-600' }} mr-3"></i>
                            <span>Rôles</span>
                        </a>
                    @endcan

                    <!-- Permissions -->
                    @can('permissions.read')
                        <a href="{{ route('private.permissions.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.permissions.*') ? 'text-white bg-gradient-to-r from-purple-500 to-indigo-500 shadow-lg ring-2 ring-purple-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 hover:text-purple-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.permissions.index">
                            <i
                                class="fas fa-key {{ request()->routeIs('private.permissions.*') ? 'text-yellow-300' : 'text-purple-500 group-hover:text-purple-600' }} mr-3"></i>
                            <span>Permissions</span>
                        </a>
                    @endcan

                    <!-- Classes communautaires -->
                    @can('classes.read')
                        <a href="{{ route('private.classes.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.classes.*') ? 'text-white bg-gradient-to-r from-cyan-500 to-blue-500 shadow-lg ring-2 ring-cyan-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-cyan-50 hover:to-blue-50 hover:text-cyan-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.classes.index">
                            <i
                                class="fas fa-users {{ request()->routeIs('private.classes.*') ? 'text-yellow-300' : 'text-cyan-500 group-hover:text-cyan-600' }} mr-3"></i>
                            <span>Classes</span>
                        </a>
                    @endcan

                    <!-- Projets -->
                    @can('parametresdons.read')
                            <a href="{{ route('private.parametresdons.index') }}"
                                class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.parametresdons.*') ? 'text-white bg-gradient-to-r from-violet-500 to-purple-500 shadow-lg ring-2 ring-violet-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-violet-50 hover:to-purple-50 hover:text-violet-600' }} rounded-xl transition-all duration-200 group"
                                data-route="private.parametresdons.index">
                                <i class="fas fa-arrows-rotate {{ request()->routeIs('private.parametresdons.*') ? 'text-yellow-300' : 'text-violet-500 group-hover:text-violet-600' }} mr-3"></i>
                                <span>Moyens de paiement</span>
                            </a>
                        @endcan

                @endcanany

                <!-- Section : Membres -->
                @canany(['users.read', 'contacts.read'])
                    <div class="pt-4 pb-2">
                        <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Gestion des Membres
                        </h5>
                    </div>


                    <!-- Membres -->
                    @can('users.read')
                        <a href="{{ route('private.users.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.users.*') ? 'text-white bg-gradient-to-r from-green-500 to-emerald-500 shadow-lg ring-2 ring-green-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.users.index">
                            <i class="fas fa-user-friends {{ request()->routeIs('private.users.*') ? 'text-yellow-300' : 'text-green-500 group-hover:text-green-600' }} mr-3"></i>
                            <span>Membres</span>
                        </a>
                    @endcan

                    <!-- Contacts -->
                    @can('contacts.read')
                        <a href="{{ route('private.contacts.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.contacts.*') ? 'text-white bg-gradient-to-r from-teal-500 to-cyan-500 shadow-lg ring-2 ring-teal-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50 hover:text-teal-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.contacts.index">
                            <i
                                class="fas fa-address-book {{ request()->routeIs('private.contacts.*') ? 'text-yellow-300' : 'text-teal-500 group-hover:text-teal-600' }} mr-3"></i>
                            <span>Contacts</span>
                        </a>
                    @endcan
                @endcanany

                <!-- Section : Activités Spirituelles -->
                @canany(['programmes.read', 'cultes.read', 'participantscultes.read', 'events.read'])
                    <div class="pt-4 pb-2">
                        <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Activités Spirituelles
                        </h5>
                    </div>

                    <!-- Programmes -->
                    @can('programmes.read')
                        <a href="{{ route('private.programmes.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.programmes.*') ? 'text-white bg-gradient-to-r from-blue-500 to-indigo-500 shadow-lg ring-2 ring-blue-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.programmes.index">
                            <i
                                class="fas fa-calendar-alt {{ request()->routeIs('private.programmes.*') ? 'text-yellow-300' : 'text-blue-500 group-hover:text-blue-600' }} mr-3"></i>
                            <span>Programmes</span>
                        </a>
                    @endcan

                    <!-- Cultes -->
                    @can('cultes.read')
                        <a href="{{ route('private.cultes.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.cultes.*') ? 'text-white bg-gradient-to-r from-amber-500 to-yellow-500 shadow-lg ring-2 ring-amber-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-amber-50 hover:to-yellow-50 hover:text-amber-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.cultes.index">
                            <i
                                class="fas fa-church {{ request()->routeIs('private.cultes.*') ? 'text-yellow-200' : 'text-amber-500 group-hover:text-amber-600' }} mr-3"></i>
                            <span>Cultes</span>
                        </a>
                    @endcan

                    <!-- Participants -->
                    @can('participantscultes.read')
                        <a href="{{ route('private.participantscultes.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.participantscultes.*') ? 'text-white bg-gradient-to-r from-amber-500 to-orange-500 shadow-lg ring-2 ring-amber-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-amber-50 hover:to-yellow-50 hover:text-amber-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.participantscultes.index">
                            <i
                                class="fas fa-users-cog {{ request()->routeIs('private.participantscultes.*') ? 'text-yellow-200' : 'text-amber-500 group-hover:text-amber-600' }} mr-3"></i>
                            <span>Participants</span>
                        </a>
                    @endcan

                    <!-- Événements -->
                    @can('events.read')
                        <a href="{{ route('private.events.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.events.*') ? 'text-white bg-gradient-to-r from-rose-500 to-pink-500 shadow-lg ring-2 ring-rose-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 hover:text-rose-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.events.index">
                            <i
                                class="fas fa-calendar-check {{ request()->routeIs('private.events.*') ? 'text-yellow-300' : 'text-rose-500 group-hover:text-rose-600' }} mr-3"></i>
                            <span>Événements</span>
                        </a>
                    @endcan
                @endcanany

                <!-- Section : Finances -->
                @canany(['fonds.read', 'fimecos.read', 'subscriptions.read', 'paiements.read', 'projets.read',
                    'moissons.read'])
                    <div class="pt-4 pb-2">
                        <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Finances</h5>
                    </div>

                    <!-- Offrandes -->
                    @can('fonds.read')
                        <a href="{{ route('private.fonds.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.fonds.*') ? 'text-white bg-gradient-to-r from-emerald-500 to-green-500 shadow-lg ring-2 ring-emerald-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-emerald-50 hover:to-green-50 hover:text-emerald-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.fonds.index">
                            <i
                                class="fas fa-donate {{ request()->routeIs('private.fonds.*') ? 'text-yellow-300' : 'text-emerald-500 group-hover:text-emerald-600' }} mr-3"></i>
                            <span>Offrandes</span>
                        </a>
                    @endcan

                    <!-- FIMECO -->
                    @can('fimecos.read')
                        <a href="{{ route('private.fimecos.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.fimecos.*') ? 'text-white bg-gradient-to-r from-green-500 to-teal-500 shadow-lg ring-2 ring-green-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-emerald-50 hover:to-green-50 hover:text-emerald-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.fimecos.index">
                            <i class="fas fa-piggy-bank {{ request()->routeIs('private.fimecos.*') ? 'text-yellow-300' : 'text-emerald-500 group-hover:text-emerald-600' }} mr-3"></i>
                            <span>FIMECO<sub>s</sub></span>
                        </a>
                    @endcan



                    <!-- Souscriptions -->
                    @can('subscriptions.read')
                        <a href="{{ route('private.subscriptions.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.subscriptions.*') ? 'text-white bg-gradient-to-r from-teal-500 to-emerald-500 shadow-lg ring-2 ring-teal-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-teal-50 hover:to-emerald-50 hover:text-teal-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.subscriptions.index">
                            <i
                                class="fas fa-file-contract {{ request()->routeIs('private.subscriptions.*') ? 'text-yellow-300' : 'text-teal-500 group-hover:text-teal-600' }} mr-3"></i>
                            <span>Souscriptions</span>
                        </a>
                    @endcan

                    <!-- Paiements -->
                    @can('paiements.read')
                        <a href="{{ route('private.paiements.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.paiements.*') ? 'text-white bg-gradient-to-r from-cyan-500 to-teal-500 shadow-lg ring-2 ring-cyan-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-cyan-50 hover:to-teal-50 hover:text-cyan-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.paiements.index">
                            <i
                                class="fas fa-credit-card {{ request()->routeIs('private.paiements.*') ? 'text-yellow-300' : 'text-cyan-500 group-hover:text-cyan-600' }} mr-3"></i>
                            <span>Paiements</span>
                        </a>
                    @endcan

                    <!-- Moissons -->
                    @can('moissons.read')
                        <a href="{{ route('private.moissons.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.moissons.*') ? 'text-white bg-gradient-to-r from-green-500 to-teal-500 shadow-lg ring-2 ring-green-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-emerald-50 hover:to-green-50 hover:text-emerald-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.moissons.index">
                            <i
                                class="fas fa-seedling {{ request()->routeIs('private.moissons.*') ? 'text-yellow-300' : 'text-emerald-500 group-hover:text-emerald-600' }} mr-3"></i>
                            <span>Moissons</span>
                        </a>
                    @endcan

                    <!-- Projets -->
                    @can('projets.read')
                        <a href="{{ route('private.projets.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.projets.*') ? 'text-white bg-gradient-to-r from-violet-500 to-purple-500 shadow-lg ring-2 ring-violet-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-violet-50 hover:to-purple-50 hover:text-violet-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.projets.index">
                            <i
                                class="fas fa-project-diagram {{ request()->routeIs('private.projets.*') ? 'text-yellow-300' : 'text-violet-500 group-hover:text-violet-600' }} mr-3"></i>
                            <span>Projets</span>
                        </a>
                    @endcan




                    <!-- Donations -->
                    @can('dons.read')
                            <a href="{{ route('private.dons.index') }}"
                                class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.dons.*') ? 'text-white bg-gradient-to-r from-violet-500 to-purple-500 shadow-lg ring-2 ring-violet-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-violet-50 hover:to-purple-50 hover:text-violet-600' }} rounded-xl transition-all duration-200 group"
                                data-route="private.dons.index">
                                <i class="fas fa-dove {{ request()->routeIs('private.dons.*') ? 'text-yellow-300' : 'text-violet-500 group-hover:text-violet-600' }} mr-3"></i>
                                <span>Donations</span>
                            </a>
                        @endcan
                    @endcanany

                <!-- Section : Réunions -->
                @canany(['types-reunions.read', 'reunions.read', 'rapports-reunions.read', 'annonces.read',
                    'interventions.read'])
                    <div class="pt-4 pb-2">
                        <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Réunions</h5>
                    </div>

                    <!-- Catégories de réunion -->
                    @can('types-reunions.read')
                        <a href="{{ route('private.types-reunions.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.types-reunions.*') ? 'text-white bg-gradient-to-r from-orange-500 to-amber-500 shadow-lg ring-2 ring-orange-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 hover:text-orange-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.types-reunions.index">
                            <i
                                class="fas fa-tags {{ request()->routeIs('private.types-reunions.*') ? 'text-yellow-300' : 'text-orange-500 group-hover:text-orange-600' }} mr-3"></i>
                            <span>Catégories de réunion</span>
                        </a>
                    @endcan

                    <!-- Réunion -->
                    @can('reunions.read')
                        <a href="{{ route('private.reunions.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.reunions.*') ? 'text-white bg-gradient-to-r from-sky-500 to-blue-500 shadow-lg ring-2 ring-sky-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-sky-50 hover:to-blue-50 hover:text-sky-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.reunions.index">
                            <i
                                class="fas fa-handshake {{ request()->routeIs('private.reunions.*') ? 'text-yellow-300' : 'text-sky-500 group-hover:text-sky-600' }} mr-3"></i>
                            <span>Réunions</span>
                        </a>
                    @endcan

                    <!-- Rapports des réunions -->
                    @can('rapports-reunions.read')
                        <a href="{{ route('private.rapports-reunions.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.rapports-reunions.*') ? 'text-white bg-gradient-to-r from-slate-500 to-gray-500 shadow-lg ring-2 ring-slate-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-slate-50 hover:to-gray-50 hover:text-slate-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.rapports-reunions.index">
                            <i
                                class="fas fa-file-alt {{ request()->routeIs('private.rapports-reunions.*') ? 'text-yellow-300' : 'text-slate-500 group-hover:text-slate-600' }} mr-3"></i>
                            <span>Rapports des réunions</span>
                        </a>
                    @endcan


                    <!-- Annonces -->
                    @can('annonces.read')
                        <a href="{{ route('private.annonces.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.annonces.*') ? 'text-white bg-gradient-to-r from-red-500 to-rose-500 shadow-lg ring-2 ring-red-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-red-50 hover:to-rose-50 hover:text-red-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.annonces.index">
                            <i
                                class="fas fa-bullhorn {{ request()->routeIs('private.annonces.*') ? 'text-yellow-300' : 'text-red-500 group-hover:text-red-600' }} mr-3"></i>
                            <span>Annonces</span>
                        </a>
                    @endcan

                    <!-- Interventions -->
                    @can('interventions.read')
                        <a href="{{ route('private.interventions.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.interventions.*') ? 'text-white bg-gradient-to-r from-pink-500 to-red-500 shadow-lg ring-2 ring-pink-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-red-50 hover:to-rose-50 hover:text-red-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.interventions.index">
                            <i
                                class="fas fa-microphone {{ request()->routeIs('private.interventions.*') ? 'text-yellow-300' : 'text-red-500 group-hover:text-red-600' }} mr-3"></i>
                            <span>Interventions</span>
                        </a>
                    @endcan
                @endcanany

                {{-- @canany(['multimedia.read'])
                    <!-- Section : Médias -->
                    <div class="pt-4 pb-2">
                        <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Médias & Communication
                        </h5>
                    </div>

                    <!-- Médias -->
                    @can('multimedia.read')
                        <a href="{{ route('private.multimedia.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.multimedia.*') ? 'text-white bg-gradient-to-r from-pink-500 to-rose-500 shadow-lg ring-2 ring-pink-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-pink-50 hover:to-rose-50 hover:text-pink-600' }} rounded-xl transition-all duration-200 group"
                            data-route="private.multimedia.index">
                            <i
                                class="fas fa-photo-video {{ request()->routeIs('private.multimedia.*') ? 'text-yellow-300' : 'text-pink-500 group-hover:text-pink-600' }} mr-3"></i>
                            <span>Médias</span>
                        </a>
                    @endcan
                @endcanany --}}

                <!-- Additional Pages -->
                <div class="pt-4 space-y-1">
                    <button onclick="toggleDropdown('pages-menu')"
                        class="flex items-center w-full px-3 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-copy mr-3 text-yellow-200"></i>
                        <span>Gérer mon compte</span>
                        <i class="fas fa-chevron-down ml-auto transform transition-transform duration-200"
                            id="pages-menu-icon"></i>
                    </button>
                    <div id="pages-menu" class="pl-6 space-y-1 max-h-0 overflow-hidden transition-all duration-300">
                        <a href="{{route('private.profil.index')}}"
                            class="flex items-center px-3 py-2 text-sm text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                            <i class="fas fa-user-circle text-amber-500 mr-2 text-xs"></i>
                            Mon profil
                        </a>
                        <a href="{{route('private.parametres.index')}}"
                            class="flex items-center px-3 py-2 text-sm text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                            <i class="fas fa-cogs text-amber-500 mr-2 text-xs"></i>
                            Paramètres
                        </a>
                        <a href="#"
                            class="flex items-center px-3 py-2 text-sm text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                            <i class="fas fa-question-circle text-amber-500 mr-2 text-xs"></i>
                            Aides
                        </a>
                    </div>
                </div>

                <!-- Section : Configuration -->
                <div class="pt-4 pb-2">
                    <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Configuration</h5>
                </div>

                <!-- Paramètres -->
                <!-- Donations -->
                    @can('parametres.read')
                        <a href="{{ route('private.parametres.index') }}"
                            class="nav-item flex items-center px-3 py-2.5 text-sm font-medium {{ request()->routeIs('private.parametres.*') ? 'text-white bg-gradient-to-r from-gray-500 to-purple-500 shadow-lg ring-2 ring-gray-200' : 'text-slate-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-purple-50 hover:text-gray-600' }} rounded-xl transition-all duration-200 group" data-route="private.parametres.index">

                            <i class="fas fa-cog {{ request()->routeIs('private.parametres.*') ? 'text-gray-300' : 'text-violet-500 group-hover:text-gray-600' }} mr-3"></i>
                            <span>Paramètres</span>
                        </a>
                    @endcan



                <!-- Déconnexion -->
                <div class="pt-2">
                    <a href="#"
                        class="flex items-center px-3 py-2.5 text-sm font-medium text-red-600 rounded-xl hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 hover:text-red-700 transition-all duration-200 group border border-red-200">
                        <i class="fas fa-sign-out-alt text-red-500 mr-3 group-hover:text-red-700"></i>
                        <span>Déconnexion</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>
</aside>

<script>
    // Fonction pour le dropdown
    function toggleDropdown(menuId) {
        const menu = document.getElementById(menuId);
        const icon = document.getElementById(menuId + '-icon');

        if (menu.style.maxHeight === '0px' || menu.style.maxHeight === '') {
            menu.style.maxHeight = menu.scrollHeight + 'px';
            icon.style.transform = 'rotate(180deg)';
        } else {
            menu.style.maxHeight = '0px';
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // Auto-close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdowns = document.querySelectorAll('[id$="-menu"]');
        const buttons = document.querySelectorAll('button[onclick*="toggleDropdown"]');

        let clickedButton = false;
        buttons.forEach(button => {
            if (button.contains(event.target)) {
                clickedButton = true;
            }
        });

        if (!clickedButton) {
            dropdowns.forEach(dropdown => {
                if (dropdown.style.maxHeight !== '0px' && dropdown.style.maxHeight !== '') {
                    dropdown.style.maxHeight = '0px';
                    const icon = document.getElementById(dropdown.id + '-icon');
                    if (icon) icon.style.transform = 'rotate(0deg)';
                }
            });
        }
    });

    // Scroll automatique vers l'élément actif au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        const activeItem = document.querySelector('.nav-item.text-white[class*="bg-gradient"]');
        if (activeItem) {
            // Petit délai pour s'assurer que la page est complètement chargée
            setTimeout(() => {
                activeItem.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                    inline: 'nearest'
                });
            }, 300);
        }
    });

    // Animation lors du hover sur les éléments non actifs
    document.addEventListener('DOMContentLoaded', function() {
        const navItems = document.querySelectorAll('.nav-item');

        navItems.forEach(item => {
            if (!item.classList.contains('text-white')) {
                item.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateX(4px)';
                });

                item.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateX(0)';
                });
            }
        });
    });
</script>
