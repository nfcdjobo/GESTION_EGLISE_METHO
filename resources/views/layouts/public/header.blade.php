    <!-- Header -->
    <header>
        <div class="nav-container">
            <div class="logo">
                <img src="{{isset($appLog) ? $appLogo : ''}}" alt="Logo {{isset($appName) ? $appName : ''}}">
                <div class="logo-text">{{isset($appName) ? $appLogo : ''}}</div>
            </div>

            <!-- Menu Desktop -->
            <nav class="desktop-nav">
                <ul>
                    <li><a href="{{route('public.accueil')}}#accueil">Accueil</a></li>
                    <li><a href="{{route('public.culte')}}#services">Activités</a></li>
                    <li><a href="{{route('public.about')}}#about">Historique</a></li>
                    <li><a href="{{route('public.events')}}#events">Événements</a></li>
                    <li><a href="{{route('public.contact')}}#contact">Contact</a></li>
                    <li><a href="{{route('security.login')}}">Connexion</a></li>
                </ul>
            </nav>

            <!-- Menu Hamburger -->
            <div class="hamburger" id="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div class="mobile-menu" id="mobileMenu">
            <ul>
                <li><a href="{{route('public.accueil')}}#accueil" class="mobile-link">Accueil</a></li>
                <li><a href="{{route('public.culte')}}#services" class="mobile-link">Activités</a></li>
                <li><a href="{{route('public.about')}}#about" class="mobile-link">Historique</a></li>
                <li><a href="{{route('public.events')}}#events" class="mobile-link">Événements</a></li>
                <li><a href="{{route('public.contact')}}#contact" class="mobile-link">Contact</a></li>
                <li><a href="{{route('security.login')}}">Connexion</a></li>
            </ul>
        </div>
    </header>
