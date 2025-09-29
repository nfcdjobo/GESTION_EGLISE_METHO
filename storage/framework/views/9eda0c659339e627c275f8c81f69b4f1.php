<!-- Header -->
    <header>
        <div class="nav-container">
            <a href="<?php echo e(route('public.accueil')); ?>" class="logo">
                <img src="<?php echo e($AppParametres->logo ? Storage::url($AppParametres->logo) :  ''); ?>"
                    alt="Logo <?php echo e($AppParametres->nom_eglise ?? ''); ?>">
                <div class="logo-text-v3"><?php echo e($AppParametres->nom_eglise ?? 'Méthodiste'); ?></div>
            </a>

            <!-- Menu Desktop -->
            <nav class="desktop-nav">
                <ul>
                    <li><a href="<?php echo e(url()->current() === route('public.accueil') ? '#accueil' : route('public.accueil') . '#accueil'); ?>" class="nav-link" data-section="accueil">Accueil</a></li>
                    <li><a href="<?php echo e(url()->current() === route('public.accueil') ? '#programmes' : route('public.accueil') . '#programmes'); ?>" class="nav-link" data-section="programmes">Programme</a></li>
                    <li><a href="<?php echo e(url()->current() === route('public.accueil') ? '#events' : route('public.accueil') . '#events'); ?>" class="nav-link" data-section="events">Événements</a></li>
                    <li><a href="<?php echo e(route('public.donates.index')); ?>" class="nav-link external">Faire un don</a></li>
                    <li><a href="<?php echo e(url()->current() === route('public.accueil') ? '#contact' : route('public.accueil') . '#contact'); ?>" class="nav-link" data-section="contact">Contact</a></li>
                    <?php if(auth()->guard()->check()): ?>
                        <li>
                            <a href="<?php echo e(route('private.dashboard')); ?>" class="nav-link external">Dashboard</a>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo e(route('security.login')); ?>" class="nav-link external">Connexion</a></li>
                    <?php endif; ?>
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
                <li><a href="<?php echo e(url()->current() === route('public.accueil') ? '#accueil' : route('public.accueil') . '#accueil'); ?>" class="mobile-link nav-link" data-section="accueil">Accueil</a></li>
                <li><a href="<?php echo e(url()->current() === route('public.accueil') ? '#programmes' : route('public.accueil') . '#programmes'); ?>" class="mobile-link nav-link" data-section="programmes">Programme</a></li>
                <li><a href="<?php echo e(url()->current() === route('public.accueil') ? '#events' : route('public.accueil') . '#events'); ?>" class="mobile-link nav-link" data-section="events">Événements</a></li>
                <li><a href="<?php echo e(route('public.donates.index')); ?>" class="mobile-link nav-link external">Faire un don</a></li>
                <li><a href="<?php echo e(url()->current() === route('public.accueil') ? '#contact' : route('public.accueil') . '#contact'); ?>" class="mobile-link nav-link" data-section="contact">Contact</a></li>
                <?php if(auth()->guard()->check()): ?>
                    
                    <li>
                        <form method="POST" action="<?php echo e(route('security.logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                    style="color: white;
                                        text-decoration: none;
                                        font-weight: bold;
                                        transition: all 0.3s ease;
                                        padding: 0.5rem 1rem;
                                        border-radius: 25px;
                                        background: transparent;
                                        border: none;
                                        cursor: pointer;"
                                    onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(-2px)';"
                                    onmouseout="this.style.background='transparent'; this.style.transform='none';"
                                    onfocus="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(-2px)';"
                                    onblur="this.style.background='transparent'; this.style.transform='none';">
                                    Déconnexion
                                </button>
                        </form>
                    </li>
                <?php else: ?>
                    
                    <li><a href="<?php echo e(route('security.login')); ?>" class="nav-link external">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <style>
    /* Styles pour la navigation active */
    .nav-link {
        position: relative;
        transition: all 0.3s ease !important;
    }

    /* État actif pour le menu desktop */
    .desktop-nav .nav-link.active {
        background: rgba(255, 255, 255, 0.25) !important;
        color: #f0d000 !important;
        font-weight: 600 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2) !important;
    }

    .desktop-nav .nav-link.active::before {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 6px;
        height: 6px;
        background: #f0d000;
        border-radius: 50%;
        animation: pulse-dot 2s infinite;
    }

    @keyframes pulse-dot {
        0% {
            transform: translateX(-50%) scale(1);
            opacity: 1;
        }
        50% {
            transform: translateX(-50%) scale(1.3);
            opacity: 0.7;
        }
        100% {
            transform: translateX(-50%) scale(1);
            opacity: 1;
        }
    }

    /* État actif pour le menu mobile */
    .mobile-menu .nav-link.active {
        background: rgba(240, 208, 0, 0.2) !important;
        color: #f0d000 !important;
        font-weight: 600 !important;
        border-left: 4px solid #f0d000 !important;
        padding-left: calc(2rem - 4px) !important;
        transform: translateX(4px) !important;
    }

    .mobile-menu .nav-link.active::after {
        content: '●';
        margin-left: auto;
        color: #f0d000;
        font-size: 0.8rem;
        animation: pulse-dot 2s infinite;
    }

    /* Animation hover améliorée pour les liens actifs */
    .desktop-nav .nav-link.active:hover {
        background: rgba(255, 255, 255, 0.35) !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25) !important;
    }

    .mobile-menu .nav-link.active:hover {
        background: rgba(240, 208, 0, 0.3) !important;
        transform: translateX(6px) !important;
    }

    /* Styles pour les liens externes (pas de navigation active) */
    .nav-link.external {
        opacity: 0.9;
    }

    .nav-link.external:hover {
        opacity: 1;
    }

    /* Indicateur de section courante dans le header */
    .nav-container::after {
        content: attr(data-current-section);
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(240, 208, 0, 0.9);
        color: #2d5a2d;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 999;
        white-space: nowrap;
    }

    .nav-container.show-section::after {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(4px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .nav-container::after {
            display: none;
        }

        .mobile-menu .nav-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('.nav-link[data-section]');
        const navContainer = document.querySelector('.nav-container');
        const sections = ['accueil', 'programmes', 'events', 'contact'];

        let isScrolling = false;
        let currentSection = 'accueil';

        // Configuration des noms de sections pour l'affichage
        const sectionNames = {
            'accueil': 'Accueil',
            'programmes': 'Programmes',
            'events': 'Événements',
            'contact': 'Contact'
        };

        // Fonction pour mettre à jour l'état actif
        function updateActiveNav(sectionId) {
            if (currentSection === sectionId) return;

            // Retirer l'état actif de tous les liens
            navLinks.forEach(link => {
                link.classList.remove('active');
            });

            // Ajouter l'état actif aux liens correspondants
            const activeLinks = document.querySelectorAll(`[data-section="${sectionId}"]`);
            activeLinks.forEach(link => {
                link.classList.add('active');
            });

            // Mettre à jour l'indicateur du header
            if (navContainer && sectionNames[sectionId]) {
                navContainer.setAttribute('data-current-section', sectionNames[sectionId]);
                navContainer.classList.add('show-section');

                // Masquer l'indicateur après 3 secondes
                setTimeout(() => {
                    navContainer.classList.remove('show-section');
                }, 3000);
            }

            currentSection = sectionId;
        }

        // Fonction pour obtenir la section actuellement visible
        function getCurrentSection() {
            const header = document.querySelector('header');
            const headerHeight = header ? header.offsetHeight : 80;
            const scrollPosition = window.scrollY + headerHeight + 50; // Offset plus précis

            let currentSectionId = 'accueil';

            // Parcourir les sections pour trouver celle qui est visible
            for (let i = 0; i < sections.length; i++) {
                const section = document.getElementById(sections[i]);
                if (section) {
                    const sectionTop = section.offsetTop;
                    const sectionBottom = sectionTop + section.offsetHeight;

                    // Vérifier si la position de scroll est dans cette section
                    if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                        currentSectionId = sections[i];
                        break;
                    }

                    // Si on a dépassé toutes les sections, prendre la dernière
                    if (scrollPosition >= sectionTop) {
                        currentSectionId = sections[i];
                    }
                }
            }

            return currentSectionId;
        }

        // Gestionnaire de scroll avec throttling
        let scrollTimeout;
        function handleScroll() {
            // Débounce le scroll pour éviter trop d'appels
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                const visibleSection = getCurrentSection();
                updateActiveNav(visibleSection);
            }, 50);
        }

        // Gestionnaire de clic sur les liens de navigation
        function handleNavClick(e) {
            const link = e.target.closest('.nav-link[data-section]');
            if (!link) return;

            // e.preventDefault();
            const targetSection = link.getAttribute('data-section');
            const targetElement = document.getElementById(targetSection);

            if (targetElement) {
                const header = document.querySelector('header');
                const headerHeight = header ? header.offsetHeight : 80;
                const targetPosition = targetElement.offsetTop - headerHeight - 10;

                // Mettre à jour immédiatement l'état actif AVANT le scroll
                updateActiveNav(targetSection);

                // Scroll vers la section
                window.scrollTo({
                    top: Math.max(0, targetPosition),
                    behavior: 'smooth'
                });

                // Fermer le menu mobile si ouvert
                const mobileMenu = document.getElementById('mobileMenu');
                const hamburger = document.getElementById('hamburger');
                if (mobileMenu && hamburger) {
                    mobileMenu.classList.remove('active');
                    hamburger.classList.remove('active');
                    document.body.style.overflow = '';
                }

                // Forcer une vérification après le scroll
                setTimeout(() => {
                    updateActiveNav(targetSection);
                }, 100);
            }
        }

        // Initialiser l'état actif au chargement de la page
        function initializeActiveState() {
            // Attendre que tout soit chargé
            setTimeout(() => {
                const initialSection = getCurrentSection();
                updateActiveNav(initialSection);

                // Debug: afficher les informations de débogage
                console.log('Sections détectées:', sections.map(s => {
                    const el = document.getElementById(s);
                    return el ? `${s}: ${el.offsetTop}px` : `${s}: non trouvé`;
                }));
                console.log('Section initiale:', initialSection);
            }, 200);
        }

        // Event listeners
        window.addEventListener('scroll', handleScroll, { passive: true });
        document.addEventListener('click', handleNavClick);

        // Initialiser quand tout est prêt
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeActiveState);
        } else {
            initializeActiveState();
        }

        // Mettre à jour lors du redimensionnement de la fenêtre
        window.addEventListener('resize', () => {
            clearTimeout(scrollTimeout);
            setTimeout(() => {
                const visibleSection = getCurrentSection();
                updateActiveNav(visibleSection);
            }, 150);
        });

        // Gestion spéciale pour les liens de retour vers l'accueil
        const logoLink = document.querySelector('.logo');
        if (logoLink) {
            logoLink.addEventListener('click', (e) => {
                // e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                updateActiveNav('accueil');
            });
        }
    });
    </script>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/layouts/public/header.blade.php ENDPATH**/ ?>