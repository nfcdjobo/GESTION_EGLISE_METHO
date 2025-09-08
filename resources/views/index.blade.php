@extends('layouts.public.main')
@section('title', 'Accueil')
@section('content')
    <!-- Hero Section -->
    <section class="hero" id="accueil">
        <div class="hero-content">
            <h1>Bienvenue dans notre Communauté</h1>
            <p>Une famille de foi unie depuis plus de 100 ans au service de Dieu et de la communauté en Côte d'Ivoire</p>
            <a href="#services" class="cta-button">Découvrir nos Programmes</a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section" id="programmes">
        <h2>Nos programmes</h2>
        <div class="services-grid">
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-praying-hands"></i></div>
                <h3>Cultes Dominicaux</h3>
                <p>Rejoignez-nous chaque dimanche pour des moments de louange, de prière et d'enseignement biblique enrichissant.</p>
                <p><strong>Dimanche : 9h00 - 11h30</strong></p>
            </div>
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-book-open"></i></div>
                <h3>Étude Biblique</h3>
                <p>Approfondissez votre connaissance de la Parole de Dieu à travers nos études bibliques interactives.</p>
                <p><strong>Mercredi : 18h00 - 19h30</strong></p>
            </div>
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-child"></i></div>
                <h3>École du Dimanche</h3>
                <p>Enseignement adapté aux enfants et adolescents pour grandir dans la foi chrétienne.</p>
                <p><strong>Dimanche : 8h00 - 9h00</strong></p>
            </div>
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-heart"></i></div>
                <h3>Œuvres Sociales</h3>
                <p>Actions communautaires, aide aux plus démunis et projets de développement local.</p>
                <p><strong>Actions permanentes</strong></p>
            </div>
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-ring"></i></div>
                <h3>Mariages & Baptêmes</h3>
                <p>Célébration des moments importants de la vie chrétienne dans la joie et la communion.</p>
                <p><strong>Sur rendez-vous</strong></p>
            </div>
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-music"></i></div>
                <h3>Chœur & Musique</h3>
                <p>Groupes de louange et chorales pour magnifier le Seigneur par la musique.</p>
                <p><strong>Samedi : 15h00 - 17h00</strong></p>
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="section events" id="events">
        <h2>Événements à venir</h2>
        <div class="events-list">
            <div class="event-item fade-in">
                <div class="event-date">
                    <div style="font-size: 1.5rem; font-weight: bold;">20</div>
                    <div>AOÛT</div>
                </div>
                <div class="event-info">
                    <h3>Concert de Louange</h3>
                    <p>Soirée spéciale de louange et d'adoration avec la participation de plusieurs chorales.</p>
                </div>
            </div>
            <div class="event-item fade-in">
                <div class="event-date">
                    <div style="font-size: 1.5rem; font-weight: bold;">25</div>
                    <div>AOÛT</div>
                </div>
                <div class="event-info">
                    <h3>Conférence Jeunesse</h3>
                    <p>Rencontre dédiée aux jeunes sur le thème "Être disciple au 21ème siècle".</p>
                </div>
            </div>
            <div class="event-item fade-in">
                <div class="event-date">
                    <div style="font-size: 1.5rem; font-weight: bold;">01</div>
                    <div>SEPT</div>
                </div>
                <div class="event-info">
                    <h3>Journée Communautaire</h3>
                    <p>Activités familiales, repas partagé et témoignages de la communauté.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section contact" id="contact">
        <h2 style="color: #ffff">Contactez-nous</h2>
        <div class="contact-grid">
            <div class="contact-item fade-in">
                <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                <h3>Adresse</h3>
                <p>Rue des Églises<br>Abidjan, Côte d'Ivoire</p>
            </div>
            <div class="contact-item fade-in">
                <div class="contact-icon"><i class="fas fa-phone"></i></div>
                <h3>Téléphone</h3>
                <p>+225 XX XX XX XX XX</p>
            </div>
            <div class="contact-item fade-in">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <h3>Email</h3>
                <p>contact@emu-ci.org</p>
            </div>
            <div class="contact-item fade-in">
                <div class="contact-icon"><i class="fas fa-clock"></i></div>
                <h3>Horaires</h3>
                <p>Dim: 8h00-12h00<br>Mer: 18h00-19h30</p>
            </div>
        </div>
    </section>

@endsection
