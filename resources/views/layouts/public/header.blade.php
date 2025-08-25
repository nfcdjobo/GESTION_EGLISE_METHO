    <!-- Header -->
    <header>
        <div class="nav-container">
            <a href="{{route('public.accueil')}}" class="logo">
                <img src="https://www.cevaa.org/la-communaute/fiches-deglises/afrique-occidentale-centrafrique/logo-emci.png/image_preview" alt="Logo {{isset($appName) ? $appName : ''}}">
                <div class="logo-text">{{isset($appName) ? $appLogo : ''}}</div>
            </a>

            <!-- Menu Desktop -->
            <nav class="desktop-nav">
                <ul>
                    <li><a href="{{route('public.accueil')}}">Accueil</a></li>
                    <li><a href="{{route('public.culte')}}">Activités</a></li>
                    <li><a href="{{route('public.about')}}">Historique</a></li>
                    <li><a href="{{route('public.events')}}">Événements</a></li>
                    <li><a href="{{route('public.contact')}}">Contact</a></li>

                    @auth
                        {{-- Si l'utilisateur est connecté --}}
                        <li>
                            <form method="POST" action="{{ route('security.logout') }}">
                                @csrf
                                {{-- <button type="submit" style="color: white; text-decoration: none; font-weight: 500; transition: all 0.3s ease; padding: 0.5rem 1rem; border-radius: 25px; background: rgba(255, 255, 255, 0.2); transform: translateY(-2px);">
                                    Déconnexion
                                </button> --}}
                                <button type="submit"
                                    style="color: white;
                                        text-decoration: none;
                                        font-weight: blod;
                                        transition: all 0.3s ease;
                                        padding: 0.5rem 1rem;
                                        border-radius: 25px;
                                        background: transparent;
                                        border: none;
                                        cursor: pointer;"
                                    onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(-2px)';"
                                    onmouseout="this.style.background='transparent'; this.style.transform='none';">
                                    Déconnexion
                                </button>
                            </form>
                        </li>
                    @else
                        {{-- Si l'utilisateur n'est pas connecté --}}
                        <li><a href="{{ route('security.login') }}">Connexion</a></li>
                    @endauth
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
                <li><a href="{{route('public.accueil')}}" class="mobile-link">Accueil</a></li>
                <li><a href="{{route('public.culte')}}" class="mobile-link">Activités</a></li>
                <li><a href="{{route('public.about')}}" class="mobile-link">Historique</a></li>
                <li><a href="{{route('public.events')}}" class="mobile-link">Événements</a></li>
                <li><a href="{{route('public.contact')}}" class="mobile-link">Contact</a></li>
                {{-- <li><a href="{{route('security.login')}}">Connexion</a></li> --}}
                 @auth
                    {{-- Si l'utilisateur est connecté --}}
                    <li>
                        <form method="POST" action="{{route('security.logout')}}">
                            @csrf
                            <button type="submit"
                                style="color: white;
                                    text-decoration: none;
                                    font-weight: blod;
                                    transition: all 0.3s ease;
                                    padding: 0.5rem 1rem;
                                    border-radius: 25px;
                                    background: transparent;
                                    border: none;
                                    cursor: pointer;"
                                onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(-2px)';"
                                onmouseout="this.style.background='transparent'; this.style.transform='none';">
                                Déconnexion
                            </button>
                        </form>
                    </li>
                @else
                    {{-- Si l'utilisateur n'est pas connecté --}}
                    <li><a href="{{route('security.login')}}">Connexion</a></li>
                @endauth
            </ul>
        </div>
    </header>
