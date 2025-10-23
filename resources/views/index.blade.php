@extends('layouts.public.main')
@section('title', 'Accueil')

@push('styles')
<style>
    /* Carrousel Hero Styles */
    .hero-carousel {
        position: relative;
        height: 100vh;
        overflow: hidden;
    }

    .carousel-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 1s ease-in-out;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .carousel-slide.active {
        opacity: 1;
        z-index: 1;
    }

    .carousel-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            to bottom,
            rgba(45, 90, 45, 0.6) 0%,
            rgba(74, 124, 89, 0.7) 50%,
            rgba(29, 42, 29, 0.8) 100%
        );
    }

    .carousel-content {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .carousel-text {
        animation: fadeInUp 1s ease-out;
    }

    .carousel-title {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.4);
        background: linear-gradient(45deg, #fff, #f0d000);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: bold;
    }

    .carousel-description {
        font-size: 1.3rem;
        margin-bottom: 2rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
        line-height: 1.6;
    }

    /* Navigation du carrousel */
    .carousel-controls {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .carousel-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        border: 2px solid rgba(255, 255, 255, 0.8);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .carousel-dot:hover {
        background: rgba(255, 255, 255, 0.8);
        transform: scale(1.2);
    }

    .carousel-dot.active {
        background: #f0d000;
        border-color: #f0d000;
        width: 40px;
        border-radius: 6px;
    }

    /* Flèches de navigation */
    .carousel-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.5);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .carousel-arrow:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.8);
        transform: translateY(-50%) scale(1.1);
    }

    .carousel-arrow.prev {
        left: 30px;
    }

    .carousel-arrow.next {
        right: 30px;
    }

    /* Indicateur de progression */
    .carousel-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: rgba(255, 255, 255, 0.2);
        z-index: 10;
    }

    .carousel-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #f0d000, #ffd700);
        width: 0%;
        transition: width 0.1s linear;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .carousel-title {
            font-size: 2.5rem;
        }

        .carousel-description {
            font-size: 1.1rem;
        }

        .carousel-arrow {
            width: 40px;
            height: 40px;
        }

        .carousel-arrow.prev {
            left: 15px;
        }

        .carousel-arrow.next {
            right: 15px;
        }

        .carousel-controls {
            bottom: 30px;
        }
    }

    @media (max-width: 480px) {
        .carousel-title {
            font-size: 2rem;
        }

        .carousel-description {
            font-size: 1rem;
        }

        .cta-button {
            padding: 12px 24px;
            font-size: 0.9rem;
        }
    }

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
</style>
@endpush

@section('content')
    <!-- Hero Carousel Section -->
    <section class="hero-carousel" id="accueil">
        @php
            $heroImages = $AppParametres->images_hero ?? [];
            $activeSlides = collect($heroImages)->where('active', true)->sortBy('ordre')->values()->all();
            $hasCarousel = count($activeSlides) > 0;
        @endphp

        @if($hasCarousel)
            <!-- Slides du carrousel -->
            @foreach($activeSlides as $index => $slide)
                <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}"
                     style="background-image: url('{{ asset('storage/' . $slide['url']) }}');"
                     data-slide="{{ $index }}">
                    <div class="carousel-overlay"></div>
                    <div class="carousel-content">
                        <div class="carousel-text">
                            <h1 class="carousel-title">{{ $slide['titre'] ?? $AppParametres->description_eglise }}</h1>
                            <p class="carousel-description">{{ $slide['description'] ?? $AppParametres->vision }}</p>
                            <a href="#programmes" class="cta-button">Découvrir nos Programmes</a>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Flèches de navigation -->
            @if(count($activeSlides) > 1)
                <div class="carousel-arrow prev" onclick="changeSlide(-1)">
                    <i class="fas fa-chevron-left"></i>
                </div>
                <div class="carousel-arrow next" onclick="changeSlide(1)">
                    <i class="fas fa-chevron-right"></i>
                </div>

                <!-- Indicateurs de points -->
                <div class="carousel-controls">
                    @foreach($activeSlides as $index => $slide)
                        <div class="carousel-dot {{ $index === 0 ? 'active' : '' }}"
                             onclick="goToSlide({{ $index }})"
                             data-dot="{{ $index }}"></div>
                    @endforeach
                </div>

                <!-- Barre de progression -->
                <div class="carousel-progress">
                    <div class="carousel-progress-bar" id="progressBar"></div>
                </div>
            @endif
        @else
            <!-- Fallback si aucun slide actif -->
            <div class="carousel-slide active"
                 style="background: linear-gradient(rgba(45, 90, 45, 0.5), rgba(74, 124, 89, 0.5)), url('https://www.yeclo.com/wp-content/uploads/2021/02/featured_aip_214907.jpg'); background-size: cover; background-position: center;">
                <div class="carousel-overlay"></div>
                <div class="carousel-content">
                    <div class="carousel-text">
                        <h1 class="carousel-title">{{ $AppParametres->description_eglise }}</h1>
                        <p class="carousel-description">{{ $AppParametres->vision }}</p>
                        <a href="#programmes" class="cta-button">Découvrir nos Programmes</a>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <!-- Services Section -->
    <section class="section" id="programmes">
        <h2>Nos programmes</h2>
        <div class="services-grid">
            @if($AppParametres && $AppParametres->count() > 0)
                @php
                    $programmesPublics = $AppParametres->getProgrammesPublics();
                    $perPage = 6;
                    $currentPage = request()->get('page', 1);
                    $offset = ($currentPage - 1) * $perPage;
                    $programmesPage = array_slice($programmesPublics, $offset, $perPage);
                    $totalPages = ceil(count($programmesPublics) / $perPage);
                @endphp

                @forelse ($programmesPage as $programme)
                    <div class="service-card fade-in">
                        <div class="service-icon">
                            <i class="{{ $programme['icone'] ?? 'fas fa-calendar' }}"></i>
                        </div>
                        <h3>{{ $programme['titre'] }}</h3>
                        <p>{{ $programme['description'] ?? 'Description non disponible' }}</p>
                        <p><strong>{{ $programme['horaire_texte'] ?? 'Horaires à définir' }}</strong></p>
                    </div>
                @empty
                    <div class="service-card fade-in">
                        <div class="service-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3>Aucun programme disponible</h3>
                        <p>Nous travaillons actuellement sur nos programmes. Revenez bientôt !</p>
                    </div>
                @endforelse
            @endif
        </div>

        @if($AppParametres && count($AppParametres->getProgrammesPublics()) > $perPage)
            <div class="pagination-programmes">
                <div class="pagination-container">
                    @if($currentPage > 1)
                        <a href="?page={{ $currentPage - 1 }}#programmes" class="pagination-link pagination-prev">
                            <i class="fas fa-chevron-left"></i> Précédent
                        </a>
                    @endif

                    <div class="pagination-numbers">
                        @for($i = 1; $i <= $totalPages; $i++)
                            <a href="?page={{ $i }}#programmes" class="pagination-number {{ $i == $currentPage ? 'active' : '' }}">
                                {{ $i }}
                            </a>
                        @endfor
                    </div>

                    @if($currentPage < $totalPages)
                        <a href="?page={{ $currentPage + 1 }}#programmes" class="pagination-link pagination-next">
                            Suivant <i class="fas fa-chevron-right"></i>
                        </a>
                    @endif
                </div>

                <div class="pagination-info">
                    Affichage {{ $offset + 1 }} - {{ min($offset + $perPage, count($programmesPublics)) }}
                    sur {{ count($programmesPublics) }} programmes
                </div>
            </div>
        @endif
    </section>

    <!-- Events Section -->
    <section class="section events" id="events">
        <h2>Événements à venir</h2>
        <div class="events-list">
            @forelse ($AppEvents as $event)
                <div class="event-item fade-in">
                    <div class="event-date">
                        <div style="font-size: 1.5rem; font-weight: bold;">{{ \Carbon\Carbon::parse($event->date_debut)->day }}</div>
                        <div>{{ ucfirst(strtolower(\Carbon\Carbon::parse($event->date_debut)->translatedFormat('F'))) }}</div>
                    </div>
                    <div class="event-info">
                        <h3>{{ $event->titre }}</h3>
                        <p>{{ $event->resume_court }}</p>
                    </div>
                </div>
            @empty
                <div class="no-events">
                    <h3>Aucun événement programmé pour le moment</h3>
                    <p>Nous préparons de nouveaux événements spirituels et communautaires. Restez connectés pour être informés de nos prochaines activités !</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section contact" id="contact">
        <h2 style="color: #ffff">Contactez-nous</h2>
        <div class="contact-grid">
            <div class="contact-item fade-in">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Adresse</h3>
                <p>{{ $AppParametres->adresse }}<br>{{ $AppParametres->ville }}, {{ $AppParametres->pays }}</p>
            </div>
            <div class="contact-item fade-in">
                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                <h3>Téléphone</h3>
                <p>{{ $AppParametres->telephone_1 }} @if($AppParametres->telephone_2) <br> {{ $AppParametres->telephone_2 }} @endif</p>
            </div>
            <div class="contact-item fade-in">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <h3>Email</h3>
                <p>{{ $AppParametres->email_principal }}</p>
            </div>
        </div>
    </section>

    <script>
        // Configuration du carrousel
        const totalSlides = {{ count($activeSlides) }};
        let currentSlide = 0;
        let autoPlayInterval;
        let progressInterval;
        const autoPlayDuration = 6000; // 6 secondes par slide

        // Fonction pour changer de slide
        function changeSlide(direction) {
            if (totalSlides <= 1) return;

            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.carousel-dot');

            // Retirer la classe active
            slides[currentSlide].classList.remove('active');
            dots[currentSlide].classList.remove('active');

            // Calculer le nouvel index
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;

            // Ajouter la classe active
            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');

            // Réinitialiser l'autoplay et la progression
            resetAutoPlay();
        }

        // Fonction pour aller à un slide spécifique
        function goToSlide(index) {
            if (totalSlides <= 1 || index === currentSlide) return;

            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.carousel-dot');

            slides[currentSlide].classList.remove('active');
            dots[currentSlide].classList.remove('active');

            currentSlide = index;

            slides[currentSlide].classList.add('active');
            dots[currentSlide].classList.add('active');

            resetAutoPlay();
        }

        // Démarrer l'autoplay
        function startAutoPlay() {
            if (totalSlides <= 1) return;

            autoPlayInterval = setInterval(() => {
                changeSlide(1);
            }, autoPlayDuration);

            startProgressBar();
        }

        // Réinitialiser l'autoplay
        function resetAutoPlay() {
            clearInterval(autoPlayInterval);
            clearInterval(progressInterval);
            startAutoPlay();
        }

        // Barre de progression
        function startProgressBar() {
            const progressBar = document.getElementById('progressBar');
            if (!progressBar) return;

            let progress = 0;
            const increment = 100 / (autoPlayDuration / 100);

            progressBar.style.width = '0%';

            progressInterval = setInterval(() => {
                progress += increment;
                if (progress >= 100) {
                    progress = 100;
                }
                progressBar.style.width = progress + '%';
            }, 100);
        }

        // Gestion du clavier
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') changeSlide(-1);
            if (e.key === 'ArrowRight') changeSlide(1);
        });

        // Support du swipe sur mobile
        const carousel = document.querySelector('.hero-carousel');
        let touchStartX = 0;
        let touchEndX = 0;

        if (carousel) {
            carousel.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            });

            carousel.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            });
        }

        function handleSwipe() {
            if (touchEndX < touchStartX - 50) changeSlide(1);
            if (touchEndX > touchStartX + 50) changeSlide(-1);
        }

        // Démarrer le carrousel au chargement
        if (totalSlides > 1) {
            startAutoPlay();
        }
    </script>
@endsection
