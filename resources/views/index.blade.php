@extends('layouts.public.main')
@section('title', 'Accueil')
@section('content')
    <!-- Hero Section -->
    <section class="hero" id="accueil">
        <div class="hero-content">
            <h1>{{$AppParametres->description_eglise}}</h1>
            <p>{{$AppParametres->vision}}</p>
            <a href="#programmes" class="cta-button">Découvrir nos Programmes</a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section" id="programmes">
        <h2>Nos programmes</h2>
        <div class="services-grid">
            @if($AppParametres && $AppParametres->count() > 0)
                @php
                    // Récupérer tous les programmes publics
                    $programmesPublics = $AppParametres->getProgrammesPublics();

                    // Pagination manuelle
                    $perPage = 6; // Nombre de programmes par page
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
            @else
                <div class="service-card fade-in">
                    <div class="service-icon">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h3>Configuration en cours</h3>
                    <p>Les programmes seront bientôt disponibles.</p>
                </div>
            @endif
        </div>

        <!-- Pagination personnalisée pour les programmes -->
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
                        <div style="font-size: 1.5rem; font-weight: bold;">{{ \Carbon\Carbon::parse($event->date_debut)->day }}
                        </div>
                        <div>{{ ucfirst(strtolower(\Carbon\Carbon::parse($event->date_debut)->translatedFormat('F'))) }}</div>
                    </div>
                    <div class="event-info">
                        <h3>{{$event->titre}}</h3>
                        <p>{{$event->resume_court}}</p>
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
                <p>{{$AppParametres->adresse}}<br>{{$AppParametres->ville}}, {{$AppParametres->pays}}</p>
            </div>
            <div class="contact-item fade-in">
                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                <h3>Téléphone</h3>
                <p>{{$AppParametres->telephone_1}} @if($AppParametres->telephone_2) <br> {{$AppParametres->telephone_2}}
                @endif</p>
            </div>
            <div class="contact-item fade-in">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <h3>Email</h3>
                <p>{{$AppParametres->email_principal}}</p>
            </div>
        </div>
    </section>

    <style>
        /* Styles pour la pagination des programmes */
        .pagination-programmes {
            margin-top: 2rem;
            text-align: center;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .pagination-link {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1rem;
            background: #ffffff;
            color: #4a5568;
            text-decoration: none;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            font-weight: 500;
            gap: 0.25rem;
        }

        .pagination-link:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
            color: #2d3748;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .pagination-numbers {
            display: flex;
            gap: 0.25rem;
        }

        .pagination-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            background: #ffffff;
            color: #4a5568;
            text-decoration: none;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .pagination-number:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
            color: #2d3748;
            transform: translateY(-1px);
        }

        .pagination-number.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .pagination-info {
            font-size: 0.875rem;
            color: #718096;
            margin-top: 0.5rem;
        }

        /* Responsive design pour la pagination */
        @media (max-width: 768px) {
            .pagination-container {
                gap: 0.25rem;
            }

            .pagination-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }

            .pagination-number {
                width: 2rem;
                height: 2rem;
                font-size: 0.875rem;
            }

            .pagination-info {
                font-size: 0.75rem;
            }
        }

        /* Animation pour le changement de page */
        .services-grid {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>



    <script>
        // JavaScript pour améliorer l'expérience utilisateur
        document.addEventListener('DOMContentLoaded', function () {
            // Smooth scroll vers la section programmes lors du changement de page
            if (window.location.hash === '#programmes') {
                setTimeout(() => {
                    document.getElementById('programmes').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }, 100);
            }

            // Ajouter un effet de chargement lors du changement de page
            const paginationLinks = document.querySelectorAll('.pagination-link, .pagination-number');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    if (!this.classList.contains('active')) {
                        // Afficher un indicateur de chargement
                        const servicesGrid = document.querySelector('.services-grid');
                        servicesGrid.style.opacity = '0.6';
                        servicesGrid.style.transform = 'scale(0.98)';
                    }
                });
            });
        });
    </script>

@endsection
