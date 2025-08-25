
<!-- Sidebar -->
<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-2xl transform -translate-x-full transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 border-r border-slate-200">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-center h-16 bg-gradient-to-r from-blue-600 to-purple-600 border-b border-blue-700">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center">
                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-blue-500" src="https://www.cevaa.org/la-communaute/fiches-deglises/afrique-occidentale-centrafrique/logo-emci.png/image_preview" alt="Logo église" />
            </div>
            <span class="ml-2 text-white font-bold text-lg">Méthodiste</span>
        </div>
    </div>

    <!-- User Profile Section -->
    <div class="p-4 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-blue-50">
        <div class="flex items-center space-x-3">
            <div class="relative">
                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-blue-500" src="https://ui-avatars.com/api/?name=John+David&background=3b82f6&color=fff" alt="User" />
                <span class="absolute bottom-0 right-0 h-3 w-3 bg-green-400 rounded-full border-2 border-white animate-pulse"></span>
            </div>
            <div>
                <h6 class="text-sm font-semibold text-slate-900">Mr. DJOBO NFC</h6>
                <div class="flex items-center space-x-1">
                    <span class="h-2 w-2 bg-green-400 rounded-full animate-pulse"></span>
                    <p class="text-xs text-slate-500">En ligne</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 overflow-y-auto max-h-[80%]">
        <div class="p-4">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Navigation</h4>
            <nav class="space-y-2">

                <!-- Dashboard -->
                <a href="{{route('private.dashboard')}}" class="flex items-center w-full px-3 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-500 to-purple-500 rounded-xl hover:from-blue-600 hover:to-purple-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-chart-line mr-3 text-yellow-300"></i>
                    <span>Tableau de bord</span>
                </a>

                <!-- Section : Administration -->
                <div class="pt-4 pb-2">
                    <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Administration</h5>
                </div>

                <!-- Rôles -->
                <a href="{{route('private.roles.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 hover:text-indigo-600 transition-all duration-200 group">
                    <i class="fas fa-user-tag text-indigo-500 mr-3 group-hover:text-indigo-600"></i>
                    <span>Rôles</span>
                </a>



                <!-- Permissions -->
                <a href="{{route('private.permissions.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-purple-50 hover:to-indigo-50 hover:text-purple-600 transition-all duration-200 group">
                    <i class="fas fa-key text-purple-500 mr-3 group-hover:text-purple-600"></i>
                    <span>Permissions</span>
                </a>


                {{-- <a href="{{route('private.audit.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 hover:text-indigo-600 transition-all duration-200 group">
                    <i class="fas fa-user-tag text-indigo-500 mr-3 group-hover:text-indigo-600"></i>
                    <span>Log d'activité</span>
                </a> --}}

                <!-- Classes communautaires -->
                <a href="{{route('private.classes.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-cyan-50 hover:to-blue-50 hover:text-cyan-600 transition-all duration-200 group">
                    <i class="fas fa-users text-cyan-500 mr-3 group-hover:text-cyan-600"></i>
                    <span>Classes communautaires</span>
                </a>

                <!-- Section : Membres -->
                <div class="pt-4 pb-2">
                    <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Gestion des Membres</h5>
                </div>

                <!-- Membres -->
                <a href="{{route('private.users.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600 transition-all duration-200 group">
                    <i class="fas fa-user-friends text-green-500 mr-3 group-hover:text-green-600"></i>
                    <span>Membres</span>
                </a>

                <!-- Contacts -->
                <a href="{{route('private.contacts.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50 hover:text-teal-600 transition-all duration-200 group">
                    <i class="fas fa-address-book text-teal-500 mr-3 group-hover:text-teal-600"></i>
                    <span>Contacts</span>
                </a>

                <!-- Section : Activités Spirituelles -->
                <div class="pt-4 pb-2">
                    <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Activités Spirituelles</h5>
                </div>

                <!-- Programmes -->
                <a href="{{route('private.programmes.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-all duration-200 group">
                    <i class="fas fa-calendar-alt text-blue-500 mr-3 group-hover:text-blue-600"></i>
                    <span>Programmes</span>
                </a>

                <!-- Cultes -->
                <a href="{{route('private.cultes.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-amber-50 hover:to-yellow-50 hover:text-amber-600 transition-all duration-200 group">
                    <i class="fas fa-church text-amber-500 mr-3 group-hover:text-amber-600"></i>
                    <span>Cultes</span>
                </a>

                <a href="{{route('private.participantscultes.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-amber-50 hover:to-yellow-50 hover:text-amber-600 transition-all duration-200 group">
                    <i class="fas fa-church text-amber-500 mr-3 group-hover:text-amber-600"></i>
                    <span>Participants</span>
                </a>

                <!-- Événements -->
                <a href="{{route('private.events.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-rose-50 hover:to-pink-50 hover:text-rose-600 transition-all duration-200 group">
                    <i class="fas fa-calendar-check text-rose-500 mr-3 group-hover:text-rose-600"></i>
                    <span>Événements</span>
                </a>

                <!-- Section : Finances -->
                <div class="pt-4 pb-2">
                    <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Finances</h5>
                </div>

                <!-- Offrandes -->
                <a href="{{route('private.fonds.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-emerald-50 hover:to-green-50 hover:text-emerald-600 transition-all duration-200 group">
                    <i class="fas fa-donate text-emerald-500 mr-3 group-hover:text-emerald-600"></i>
                    <span>Offrandes</span>
                </a>

                <!-- Projets -->
                <a href="{{route('private.projets.index')}}" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-violet-50 hover:to-purple-50 hover:text-violet-600 transition-all duration-200 group">
                    <i class="fas fa-project-diagram text-violet-500 mr-3 group-hover:text-violet-600"></i>
                    <span>Projets</span>
                </a>

                <!-- Section : Réunions -->
                <div class="pt-4 pb-2">
                    <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Réunions</h5>
                </div>

                <!-- Catégories de réunion -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-orange-50 hover:to-amber-50 hover:text-orange-600 transition-all duration-200 group">
                    <i class="fas fa-tags text-orange-500 mr-3 group-hover:text-orange-600"></i>
                    <span>Catégories de réunion</span>
                </a>

                <!-- Réunion -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-sky-50 hover:to-blue-50 hover:text-sky-600 transition-all duration-200 group">
                    <i class="fas fa-handshake text-sky-500 mr-3 group-hover:text-sky-600"></i>
                    <span>Réunions</span>
                </a>

                <!-- Rapports des réunions -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-slate-50 hover:to-gray-50 hover:text-slate-600 transition-all duration-200 group">
                    <i class="fas fa-file-alt text-slate-500 mr-3 group-hover:text-slate-600"></i>
                    <span>Rapports des réunions</span>
                </a>

                <!-- Interventions -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-red-50 hover:to-rose-50 hover:text-red-600 transition-all duration-200 group">
                    <i class="fas fa-microphone text-red-500 mr-3 group-hover:text-red-600"></i>
                    <span>Interventions</span>
                </a>

                <!-- Section : Médias -->
                <div class="pt-4 pb-2">
                    <h5 class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Médias & Communication</h5>
                </div>

                <!-- Médias -->
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-pink-50 hover:to-rose-50 hover:text-pink-600 transition-all duration-200 group">
                    <i class="fas fa-photo-video text-pink-500 mr-3 group-hover:text-pink-600"></i>
                    <span>Médias</span>
                </a>

                <!-- Additional Pages -->
                <div class="pt-4 space-y-1">
                    <button onclick="toggleDropdown('pages-menu')" class="flex items-center w-full px-3 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-copy mr-3 text-yellow-200"></i>
                        <span>Pages Additionnelles</span>
                        <i class="fas fa-chevron-down ml-auto transform transition-transform duration-200" id="pages-menu-icon"></i>
                    </button>
                    <div id="pages-menu" class="pl-6 space-y-1 max-h-0 overflow-hidden transition-all duration-300">
                        <a href="#" class="flex items-center px-3 py-2 text-sm text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                            <i class="fas fa-user-circle text-amber-500 mr-2 text-xs"></i>
                            Mon profil
                        </a>
                        <a href="#" class="flex items-center px-3 py-2 text-sm text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                            <i class="fas fa-cogs text-amber-500 mr-2 text-xs"></i>
                            Paramètres
                        </a>
                        <a href="#" class="flex items-center px-3 py-2 text-sm text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
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
                <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-slate-700 rounded-xl hover:bg-gradient-to-r hover:from-gray-50 hover:to-slate-50 hover:text-gray-600 transition-all duration-200 group">
                    <i class="fas fa-cog text-gray-500 mr-3 group-hover:text-gray-600"></i>
                    <span>Paramètres</span>
                </a>

                <!-- Déconnexion -->
                <div class="pt-2">
                    <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-red-600 rounded-xl hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 hover:text-red-700 transition-all duration-200 group border border-red-200">
                        <i class="fas fa-sign-out-alt text-red-500 mr-3 group-hover:text-red-700"></i>
                        <span>Déconnexion</span>
                    </a>
                </div>
            </nav>
        </div>
    </div>
</aside>

<script>
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
</script>
