    <!-- Header -->
    <header>
        <div class="nav-container">
            <a href="<?php echo e(route('public.accueil')); ?>" class="logo">
                <img src="https://www.cevaa.org/la-communaute/fiches-deglises/afrique-occidentale-centrafrique/logo-emci.png/image_preview"
                    alt="Logo <?php echo e(isset($appName) ? $appName : ''); ?>">
                <div class="logo-text"><?php echo e(isset($appName) ? $appLogo : ''); ?></div>
            </a>

            <!-- Menu Desktop -->
            <nav class="desktop-nav">
                <ul>
                    <li><a href="<?php echo e(route('public.accueil')); ?>">Accueil</a></li>
                    <li><a href="">Programme</a></li>
                    <li><a href="">Evénements</a></li>
                    <li><a href="<?php echo e(route('public.contact')); ?>">Contact</a></li>

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
                        <li><a href="<?php echo e(route('security.login')); ?>">Connexion</a></li>
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
                <li><a href="<?php echo e(route('public.accueil')); ?>" class="mobile-link">Accueil</a></li>
                <li><a href="">Programme</a></li>
                <li><a href="">Evénements</a></li>
                <li><a href="<?php echo e(route('public.contact')); ?>" class="mobile-link">Contact</a></li>
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
                    
                    <li><a href="<?php echo e(route('security.login')); ?>">Connexion</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/layouts/public/header.blade.php ENDPATH**/ ?>