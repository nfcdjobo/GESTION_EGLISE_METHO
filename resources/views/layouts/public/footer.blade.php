<!-- Footer -->
<footer>
    <div class="footer-content">
        <!-- Section Contact -->
        <div class="footer-section">
            <h3><i class="fas fa-phone"></i> Contacts</h3>
            <a href="tel:{{$AppParametres->telephone_1}}"><i class="fas fa-phone"></i>{{$AppParametres->telephone_1}}</a>
            @if ($AppParametres->telephone_2)
                <a href="tel:{{$AppParametres->telephone_2}}"><i class="fas fa-mobile-alt"></i>{{$AppParametres->telephone_2}}</a>
            @endif
            <a href="mailto:{{$AppParametres->email_principal}}"><i class="fas fa-envelope"></i> {{$AppParametres->email_principal}}</a>
        </div>

        <!-- Section Navigation -->
        <div class="footer-section">
            <h3><i class="fas fa-sitemap"></i> Navigation</h3>
            <div class="footer-menu">
                <a href="{{ url()->current() === route('public.accueil') ? '#accueil' : route('public.accueil') . '#accueil' }}" class="footer-nav-link" data-section="accueil">Accueil</a>
                <a href="{{ url()->current() === route('public.accueil') ? '#programmes' : route('public.accueil') . '#programmes' }}" class="footer-nav-link" data-section="programmes">Nos Programmes</a>
                <a href="{{ url()->current() === route('public.accueil') ? '#events' : route('public.accueil') . '#events' }}" class="footer-nav-link" data-section="events">Nos Événements</a>
                <a href="{{route('public.donates.index')}}" class="footer-nav-link external">Faire un don</a>
                <a href="{{ url()->current() === route('public.accueil') ? '#contact' : route('public.accueil') . '#contact' }}" class="footer-nav-link" data-section="contact">Nos Contacts</a>

                @auth
                    <a href="{{ route('private.dashboard') }}" class="dashboard-link footer-nav-link external">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('security.login') }}" class="login-link footer-nav-link external">
                        <i class="fas fa-sign-in-alt"></i>
                        Connexion
                    </a>
                @endauth
            </div>
        </div>

        <!-- Section Localisation -->
        <div class="footer-section">
            <h3><i class="fas fa-map-marker-alt"></i> Localisation</h3>
            <p><i class="fas fa-building"></i> <strong>Siège Social</strong></p>
            <p>{{$AppParametres->adresse}}, <br>{{$AppParametres->ville}}, {{$AppParametres->pays}}</p>
            <p><i class="fas fa-map"></i> <strong>Localisation</strong></p>
            <p>{{$AppParametres->commune}}, {{$AppParametres->ville}}<br>{{$AppParametres->pays}}</p>
            <p><i class="fas fa-clock"></i> <strong>Heures d'ouverture</strong></p>
            <p>24h/24<br>7jrs/7</p>
        </div>

        <!-- Section Réseaux Sociaux -->
        <div class="footer-section">
            <h3><i class="fas fa-share-alt"></i> Suivez-nous</h3>
            <div class="social-links">
                @if ($AppParametres->facebook_url)
                    <a href="{{$AppParametres->facebook_url ?? '#'}}" class="social-link" target="_blank">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                @endif

                @if ($AppParametres->youtube_url)
                    <a href="{{$AppParametres->youtube_url ?? '#'}}" class="social-link" target="_blank">
                        <i class="fab fa-youtube"></i> YouTube
                    </a>
                @endif

                @if ($AppParametres->twitter_url)
                    <a href="{{$AppParametres->twitter_url ?? '#'}}" class="social-link" target="_blank">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                @endif

                @if ($AppParametres->instagram_url)
                    <a href="{{$AppParametres->instagram_url ?? '#'}}" class="social-link" target="_blank">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                @endif

                @if ($AppParametres->tiktok_url)
                    <a href="{{$AppParametres->tiktok_url ?? '#'}}" class="social-link" target="_blank">
                        <i class="fab fa-tiktok"></i> TikTok
                    </a>
                @endif

                @if ($AppParametres->telephone_1)
                    <a href="https://wa.me/{{$AppParametres->telephone_1 ?? '#'}}" class="social-link" target="_blank">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                @endif

            </div>
            <p style="margin-top: 1rem; font-size: 0.9rem; color: #999;">
                <i class="fas fa-bell"></i> Restez connectés pour nos dernières actualités et événements
            </p>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <p>&copy; 2024 Église Méthodiste Unie - Côte d'Ivoire. Tous droits réservés.</p>
        <p><i class="fas fa-heart" style="color: #d32f2f;"></i> "Allez donc et faites de toutes les nations des
            disciples" - Matthieu 28:19</p>
        <p style="font-size: 0.8rem; margin-top: 1rem;">
            Développé avec <i class="fas fa-heart" style="color: #d32f2f;"></i> pour la communauté
        </p>
    </div>
</footer>

<style>
/* Styles pour les liens de navigation du footer */
.footer-nav-link {
    position: relative;
    transition: all 0.3s ease !important;
    display: block;
    padding: 0.5rem 0;
    border-left: 3px solid transparent;
    padding-left: 0.75rem;
    margin: 0.25rem 0;
}

/* État actif pour les liens du footer */
.footer-nav-link.active {
    color: #4a7c59 !important;
    font-weight: 600 !important;
    border-left-color: #4a7c59 !important;
    background: rgba(74, 124, 89, 0.1) !important;
    transform: translateX(4px) !important;
    border-radius: 0 8px 8px 0;
}

/* Indicateur pulsant pour les liens actifs */
.footer-nav-link.active::after {
    content: '';
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    width: 6px;
    height: 6px;
    background: #4a7c59;
    border-radius: 50%;
    animation: pulse-footer 2s infinite;
}

@keyframes pulse-footer {
    0% {
        transform: translateY(-50%) scale(1);
        opacity: 1;
    }
    50% {
        transform: translateY(-50%) scale(1.3);
        opacity: 0.6;
    }
    100% {
        transform: translateY(-50%) scale(1);
        opacity: 1;
    }
}

/* Hover amélioré pour les liens actifs */
.footer-nav-link.active:hover {
    color: #2d5a2d !important;
    background: rgba(74, 124, 89, 0.15) !important;
    transform: translateX(6px) !important;
}

/* Styles spéciaux pour les liens Dashboard et Connexion */
.footer-nav-link.dashboard-link.active {
    background: rgba(16, 185, 129, 0.1) !important;
    border-left-color: #10b981 !important;
}

.footer-nav-link.dashboard-link.active::after {
    background: #10b981;
}

.footer-nav-link.login-link.active {
    background: rgba(59, 130, 246, 0.1) !important;
    border-left-color: #3b82f6 !important;
}

.footer-nav-link.login-link.active::after {
    background: #3b82f6;
}

/* Désactiver les styles actifs pour les liens externes */
.footer-nav-link.external {
    border-left-color: transparent !important;
}

.footer-nav-link.external:hover {
    color: #4a7c59 !important;
    transform: translateX(2px) !important;
}

/* Responsive pour le footer */
@media (max-width: 768px) {
    .footer-nav-link {
        padding: 0.4rem 0;
        padding-left: 0.5rem;
        font-size: 0.9rem;
    }

    .footer-nav-link.active {
        transform: translateX(2px) !important;
    }

    .footer-nav-link.active:hover {
        transform: translateX(4px) !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const footerNavLinks = document.querySelectorAll('.footer-nav-link[data-section]');
    const sections = ['accueil', 'programmes', 'events', 'contact'];

    // Fonction pour synchroniser l'état actif du footer avec le header
    function updateFooterActiveNav(sectionId) {
        // Retirer l'état actif de tous les liens du footer
        footerNavLinks.forEach(link => {
            link.classList.remove('active');
        });

        // Ajouter l'état actif aux liens correspondants du footer
        const activeFooterLinks = document.querySelectorAll(`.footer-nav-link[data-section="${sectionId}"]`);
        activeFooterLinks.forEach(link => {
            link.classList.add('active');
        });
    }

    // Observer les changements d'état actif dans le header
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const target = mutation.target;
                if (target.classList.contains('nav-link') && target.hasAttribute('data-section')) {
                    const sectionId = target.getAttribute('data-section');
                    if (target.classList.contains('active')) {
                        updateFooterActiveNav(sectionId);
                    }
                }
            }
        });
    });

    // Observer tous les liens de navigation du header
    const headerNavLinks = document.querySelectorAll('.nav-link[data-section]');
    headerNavLinks.forEach(link => {
        observer.observe(link, {
            attributes: true,
            attributeFilter: ['class']
        });
    });

    // Gestionnaire de clic pour les liens du footer
    function handleFooterNavClick(e) {
        const link = e.target.closest('.footer-nav-link[data-section]');
        if (!link) return;

        // e.preventDefault();
        const targetSection = link.getAttribute('data-section');
        const targetElement = document.getElementById(targetSection);

        if (targetElement) {
            const header = document.querySelector('header');
            const headerHeight = header ? header.offsetHeight : 80;
            const targetPosition = targetElement.offsetTop - headerHeight - 10;

            // Mettre à jour immédiatement l'état actif du footer
            updateFooterActiveNav(targetSection);

            // Scroll vers la section
            window.scrollTo({
                top: Math.max(0, targetPosition),
                behavior: 'smooth'
            });

            // Déclencher aussi la mise à jour du header si la fonction existe
            if (window.updateActiveNav) {
                window.updateActiveNav(targetSection);
            }
        }
    }

    // Event listener pour les clics sur les liens du footer
    document.addEventListener('click', handleFooterNavClick);

    // Initialisation : synchroniser avec l'état actuel du header
    setTimeout(() => {
        const activeHeaderLink = document.querySelector('.nav-link.active[data-section]');
        if (activeHeaderLink) {
            const sectionId = activeHeaderLink.getAttribute('data-section');
            updateFooterActiveNav(sectionId);
        } else {
            // Par défaut, activer "accueil"
            updateFooterActiveNav('accueil');
        }
    }, 300);

    // Exposer la fonction pour synchronisation externe
    window.updateFooterActiveNav = updateFooterActiveNav;
});
</script>
