<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $__env->yieldContent('title', isset($appAcronym) ? $appAcronym : "Église Méthodiste Unie - Côte d'Ivoire"); ?></title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="https://www.cevaa.org/la-communaute/fiches-deglises/afrique-occidentale-centrafrique/logo-emci.png/image_preview" type="image/png" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            overflow-x: hidden;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #2d5a2d 0%, #4a7c59 100%);
            color: white;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #fff;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 2rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }

        nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Menu Hamburger */
        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
            padding: 5px;
            z-index: 1001;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background: white;
            margin: 3px 0;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Menu mobile */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100vh;
            background: linear-gradient(135deg, #2d5a2d 0%, #4a7c59 100%);
            z-index: 999;
            transition: all 0.3s ease;
            padding-top: 100px;
        }

        .mobile-menu.active {
            left: 0;
        }

        .mobile-menu ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 2rem;
            padding: 2rem;
        }

        .mobile-menu a {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 500;
            padding: 1rem 2rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            display: block;
            text-align: center;
        }

        .mobile-menu a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(45, 90, 45, 0.5), rgba(74, 124, 89, 0.5)),
                url('https://www.yeclo.com/wp-content/uploads/2021/02/featured_aip_214907.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            padding: 2rem;
            animation: fadeInUp 1s ease-out;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
            background: linear-gradient(45deg, #fff, #f0d000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
        }

        .cta-button {
            display: inline-block;
            background: linear-gradient(45deg, #d32f2f, #ff6b35);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        /* Sections */
        .section {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: #2d5a2d;
            position: relative;
        }

        .section h2::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(45deg, #d32f2f, #f0d000);
            margin: 1rem auto;
            border-radius: 2px;
        }

        /* Services Grid */
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .service-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border-top: 5px solid #4a7c59;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .service-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #4a7c59, #2d5a2d);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        /* About Section */
        .about {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: center;
        }

        .about-text {
            font-size: 1.1rem;
            line-height: 1.8;
        }

        .about-image {
            text-align: center;
        }

        .heritage-badge {
            display: inline-block;
            background: linear-gradient(45deg, #d32f2f, #ff6b35);
            color: white;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Events Section */
        .events {
            background: white;
        }

        .events-list {
            display: grid;
            gap: 1.5rem;
        }

        .event-item {
            background: linear-gradient(135deg, #4a7c59 0%, #2d5a2d 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 2rem;
            transition: all 0.3s ease;
        }

        .event-item:hover {
            transform: scale(1.02);
        }

        .event-date {
            background: rgba(255, 255, 255, 0.2);
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
            min-width: 100px;
        }

        .event-info h3 {
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        /* Contact Section */
        .contact {
            background: linear-gradient(135deg, #2d5a2d 0%, #4a7c59 100%);
            color: white;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .contact-item {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.2rem;
        }

        /* Footer */
        footer {
            background: #1a1a1a;
            color: white;
            padding: 3rem 2rem 1rem;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-section h3 {
            color: #4a7c59;
            margin-bottom: 1rem;
            font-size: 1.2rem;
            border-bottom: 2px solid #4a7c59;
            padding-bottom: 0.5rem;
        }

        .footer-section p,
        .footer-section a {
            color: #ccc;
            text-decoration: none;
            margin-bottom: 0.5rem;
            display: block;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: #4a7c59;
        }

        .footer-section i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .social-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(74, 124, 89, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .social-link:hover {
            background: #4a7c59;
            transform: translateY(-2px);
        }

        .footer-menu {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 2rem;
            border-top: 1px solid #333;
            color: #999;
        }

        .footer-bottom p {
            margin-bottom: 0.5rem;
        }

        /* Responsive Footer */
        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .social-links {
                justify-content: center;
            }

            .social-link {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: row;
                justify-content: space-between;
                gap: 1rem;
            }

            .desktop-nav {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .mobile-menu {
                display: block;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .about-content {
                grid-template-columns: 1fr;
            }

            .event-item {
                flex-direction: column;
                text-align: center;
            }

            .logo-text {
                font-size: 1.2rem;
            }

            .section {
                padding: 3rem 1rem;
            }

            .section h2 {
                font-size: 2rem;
            }

            .services-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .contact-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2rem;
            }

            .logo-text {
                display: none;
            }

            .hero-content {
                padding: 1rem;
            }

            .cta-button {
                padding: 12px 24px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>

    <?php echo $__env->make('layouts.public.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldContent('content'); ?>

    <?php echo $__env->make('layouts.public.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


     <script>
        // ==========================================
        // MENU HAMBURGER - CODE MANQUANT AJOUTÉ
        // ==========================================
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        // Toggle du menu hamburger
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');

            // Empêcher le scroll du body quand le menu est ouvert
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
        });

        // Fermer le menu quand on clique sur un lien
        mobileLinks.forEach(link => {
            link.addEventListener('click', function() {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Fermer le menu si on clique en dehors
        document.addEventListener('click', function(e) {
            if (!hamburger.contains(e.target) && !mobileMenu.contains(e.target)) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // ==========================================
        // RESTE DU CODE JAVASCRIPT EXISTANT
        // ==========================================

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Header background on scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 100) {
                header.style.background = 'rgba(45, 90, 45, 0.95)';
            } else {
                header.style.background = 'linear-gradient(135deg, #2d5a2d 0%, #4a7c59 100%)';
            }
        });
    </script>

</body>

</html>
<?php /**PATH C:\Users\Dell\Desktop\MICRISERVICES\methodiste_belle_ville\resources\views/layouts/public/main.blade.php ENDPATH**/ ?>