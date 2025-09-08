<!-- Top Bar -->
<header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200">
    <div class="flex items-center justify-between h-16 px-6">
        <div class="flex items-center space-x-4">
            <button id="mobile-menu-btn"
                class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <div class="flex items-center space-x-2">
                <div
                    class="h-10 w-10 rounded-full  rounded-lg flex items-center justify-center">
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
                <input type="text" placeholder="Search..."
                    class="bg-transparent outline-none text-sm text-slate-600 w-full">
            </div>

            <!-- Notifications -->
            <div class="flex items-center space-x-3">
                <div class="relative">
                    <button class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors relative">
                        <i class="fas fa-bell text-lg"></i>
                        
                    </button>
                </div>
                <button class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors hidden sm:block">
                    <i class="fas fa-question-circle text-lg"></i>
                </button>
                <div class="relative">
                    <button class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors relative">
                        <i class="fas fa-envelope text-lg"></i>
                        
                    </button>
                </div>
            </div>

            <!-- User Dropdown -->
            <div class="relative">
                <button onclick="toggleUserMenu()" class="flex items-center space-x-2 p-2 rounded-xl hover:bg-slate-100 transition-colors">
                    <img class="h-9 w-9 rounded-full object-cover ring-2 ring-blue-500"
                        src="<?php echo e(auth()->user()->photo_profil ? Storage::url(auth()->user()->photo_profil) : 'https://ui-avatars.com/api/?name='.auth()->user()->nom.'+'.auth()->user()->prenom.'&background=3b82f6&color=fff'); ?>"
                        alt="<?php echo e(auth()->user()->nom_complet); ?>" />
                    <span class="text-sm font-medium text-slate-700 hidden sm:block"><?php echo e(auth()->user()->nom); ?></span>
                    <i class="fas fa-chevron-down text-xs text-slate-400"></i>
                </button>
                <div id="user-menu"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 opacity-0 invisible transform scale-95 transition-all duration-200 z-50">
                    <a href="profile.html"
                        class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 rounded-t-xl transition-colors">
                        <i class="fas fa-user mr-3 text-slate-400"></i>
                        Mon profil
                    </a>
                    <a href="settings.html"
                        class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                        <i class="fas fa-cog mr-3 text-slate-400"></i>
                        Paramètres
                    </a>
                    <a href="help.html" class="flex items-center px-4 py-3 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                        <i class="fas fa-question-circle mr-3 text-slate-400"></i>
                        Aides
                    </a>
                    <div class="border-t border-slate-200"></div>

                    <form action="<?php echo e(route('security.logout')); ?>" method="post">
                        <?php echo csrf_field(); ?>
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
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/layouts/private/topbar.blade.php ENDPATH**/ ?>