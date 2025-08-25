<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', isset($appAcronym) ? $appAcronym : "Église Méthodiste - Belle Ville")</title>
    <meta name="description" content="Beautiful responsive admin dashboard built with Tailwind CSS">
    <meta name="author" content="">
    <link rel="icon" href="https://www.cevaa.org/la-communaute/fiches-deglises/afrique-occidentale-centrafrique/logo-emci.png/image_preview" type="image/png" />




    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script src="{{asset('tailwindcss/tailwindcss.js')}}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- <link rel="stylesheet" href="{{asset('css/all.min.css')}}"> --}}

    <style>
        /* Corriger le conflit entre Tailwind et CKEditor */
        .ck-content h1,
        .ck-content h2,
        .ck-content p,
        .ck-content ul,
        .ck-content ol {
            all: revert; /* rétablit les styles par défaut */
        }
    </style>

    @push('styles')
<style>
    /* Styles pour l'affichage du contenu CKEditor */
    .ckeditor-content {
        line-height: 1.6;
        color: #374151;
    }

    .ckeditor-content h1,
    .ckeditor-content h2,
    .ckeditor-content h3,
    .ckeditor-content h4,
    .ckeditor-content h5,
    .ckeditor-content h6 {
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        color: #1f2937;
    }

    .ckeditor-content h1 { font-size: 1.5rem; }
    .ckeditor-content h2 { font-size: 1.25rem; }
    .ckeditor-content h3 { font-size: 1.125rem; }

    .ckeditor-content p {
        margin-bottom: 1rem;
    }

    .ckeditor-content ul,
    .ckeditor-content ol {
        margin-bottom: 1rem;
        padding-left: 1.5rem;
    }

    .ckeditor-content ul {
        list-style-type: disc;
    }

    .ckeditor-content ol {
        list-style-type: decimal;
    }

    .ckeditor-content li {
        margin-bottom: 0.25rem;
    }

    .ckeditor-content blockquote {
        border-left: 4px solid #3b82f6;
        padding-left: 1rem;
        margin: 1.5rem 0;
        font-style: italic;
        color: #6b7280;
        background-color: #f8fafc;
        padding: 1rem;
        border-radius: 0.5rem;
    }

    .ckeditor-content table {
        min-width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    .ckeditor-content table th,
    .ckeditor-content table td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
    }

    .ckeditor-content table th {
        background-color: #f9fafb;
        font-weight: 600;
        color: #374151;
    }

    .ckeditor-content table tr:hover {
        background-color: #f9fafb;
    }

    .ckeditor-content a {
        color: #3b82f6;
        text-decoration: underline;
        transition: color 0.2s;
    }

    .ckeditor-content a:hover {
        color: #1d4ed8;
    }

    .ckeditor-content a[target="_blank"]:after {
        content: " ↗";
        font-size: 0.8em;
        color: #6b7280;
    }

    .ckeditor-content strong,
    .ckeditor-content b {
        font-weight: 600;
    }

    .ckeditor-content em,
    .ckeditor-content i {
        font-style: italic;
    }

    .ckeditor-content u {
        text-decoration: underline;
    }

    .ckeditor-content s,
    .ckeditor-content strike {
        text-decoration: line-through;
    }

    /* Styles pour les contenus vides ou courts */
    .ckeditor-content-empty {
        color: #9ca3af;
        font-style: italic;
        padding: 1rem;
        background-color: #f9fafb;
        border-radius: 0.5rem;
        border: 1px dashed #d1d5db;
        text-align: center;
    }

    .ckeditor-content-short {
        font-size: 0.875rem;
        color: #6b7280;
    }
</style>
@endpush
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
