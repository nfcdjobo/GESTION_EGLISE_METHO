<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', isset($appAcronym) ? $appAcronym : "Église Méthodiste - Belle Ville")</title>
    <meta name="description" content="Beautiful responsive admin dashboard built with Tailwind CSS">
    <meta name="author" content="">
    <link rel="icon" href="../images/fevicon.png" type="image/png" />
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script src="{{asset('tailwindcss/tailwindcss.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- <link rel="stylesheet" href="{{asset('css/all.min.css')}}"> --}}
</head>

<body class="bg-gradient-to-br from-slate-50 to-blue-50 font-sans antialiased">
    <div class="flex h-screen overflow-hidden">
        <!-- Mobile menu overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

        @include('layouts.private.header')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            @include('layouts.private.topbar')
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-gradient-to-br from-slate-50 to-blue-50">
                <div class="p-6">


                    @yield('content')


                    @include('layouts.private.footer')

                </div>

            </main>
        </div>
    </div>


    <!-- Scripts -->
    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const mobileOverlay = document.getElementById('mobile-overlay');

        function toggleMobileMenu() {
            sidebar.classList.toggle('-translate-x-full');
            mobileOverlay.classList.toggle('hidden');
        }

        function closeMobileMenu() {
            sidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.add('hidden');
        }

        mobileMenuBtn.addEventListener('click', toggleMobileMenu);
        mobileOverlay.addEventListener('click', closeMobileMenu);

        // Dropdown menu functionality
        function toggleDropdown(menuId) {
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(menuId + '-icon');

            if (menu.style.maxHeight && menu.style.maxHeight !== '0px') {
                menu.style.maxHeight = '0px';
                icon.style.transform = 'rotate(0deg)';
            } else {
                menu.style.maxHeight = menu.scrollHeight + 'px';
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // User menu functionality
        function toggleUserMenu() {
            const userMenu = document.getElementById('user-menu');
            userMenu.classList.toggle('opacity-0');
            userMenu.classList.toggle('invisible');
            userMenu.classList.toggle('scale-95');
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userButton = event.target.closest('[onclick="toggleUserMenu()"]');

            if (!userButton && !userMenu.contains(event.target)) {
                userMenu.classList.add('opacity-0', 'invisible', 'scale-95');
            }
        });

        // Smooth scroll behavior for better UX
        document.documentElement.style.scrollBehavior = 'smooth';

        // Close mobile menu when screen size changes
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeMobileMenu();
            }
        });
    </script>


    @stack('scripts')
</body>

</html>
