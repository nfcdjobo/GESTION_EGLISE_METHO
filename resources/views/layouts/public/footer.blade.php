<!-- Footer -->
    <footer>
        <div class="footer-content">
            <!-- Section Contact -->
            <div class="footer-section">
                <h3><i class="fas fa-phone"></i> Contacts</h3>
                <a href="tel:{{isset($appPhone1) ? $appPhone1 : ''}}"><i class="fas fa-phone"></i> {{isset($appPhone1) ? $appPhone1 : ''}}</a>
                <a href="tel:{{isset($appPhone2) ? $appPhone2 : ''}}"><i class="fas fa-mobile-alt"></i> {{isset($appPhone2) ? $appPhone2 : ''}}</a>
                <a href="tel:{{isset($appPhone3) ? $appPhone3 : ''}}"><i class="fas fa-phone-alt"></i> {{isset($appPhone3) ? $appPhone3 : ''}}</a>
                <a href="mailto:{{isset($appContactMail) ? $appContactMail : 'contact@emu-ci.org'}}"><i class="fas fa-envelope"></i> contact@emu-ci.org</a>
                <a href="mailto:{{isset($appInfoMail) ? $appInfoMail : 'info@emu-ci.org'}}"><i class="fas fa-envelope"></i> info@emu-ci.org</a>
            </div>

            <!-- Section Navigation -->
            <div class="footer-section">
                <h3><i class="fas fa-sitemap"></i> Navigation</h3>
                <div class="footer-menu">
                    <a href="{{route('public.accueil')}}#accueil">Accueil</a>
                    <a href="{{route('public.culte')}}#services">Nos Activités</a>
                    <a href="{{route('public.about')}}#about">Historique</a>
                    <a href="{{route('public.events')}}#events">Événements</a>
                    <a href="{{route('public.contact')}}#contact">Contact</a>
                    <a href="{{route('public.horaires')}}#horaires">Horaires</a>
                </div>
            </div>

            <!-- Section Localisation -->
            <div class="footer-section">
                <h3><i class="fas fa-map-marker-alt"></i> Localisation</h3>
                <p><i class="fas fa-building"></i> <strong>Siège Social</strong></p>
                <p>Rue des Églises, Plateau<br>Abidjan, Côte d'Ivoire</p>
                <p><i class="fas fa-map"></i> <strong>Localisation</strong></p>
                <p>Quartier Cocody, près du marché<br>Face à l'école primaire</p>
                <p><i class="fas fa-clock"></i> <strong>Heures d'ouverture</strong></p>
                <p>Lun-Ven: 8h00-17h00<br>Sam: 9h00-15h00<br>Dim: 7h00-13h00</p>
            </div>

            <!-- Section Réseaux Sociaux -->
            <div class="footer-section">
                <h3><i class="fas fa-share-alt"></i> Suivez-nous</h3>
                <div class="social-links">
                    <a href="{{isset($appFacebookLinl) ? $appFacebookLinl : '#'}}" class="social-link">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                    <a href="{{isset($appYouTubeLink) ? $appYouTubeLink : '#'}}" class="social-link">
                        <i class="fab fa-youtube"></i> YouTube
                    </a>
                    <a href="{{isset($appTwitterLink) ? $appTwitterLink : '#'}}" class="social-link">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                    <a href="{{isset($appInstagramLink) ? $appInstagramLink : '#'}}" class="social-link">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                    <a href="{{isset($appTikTokLink) ? $appTikTokLink : '#'}}" class="social-link">
                        <i class="fab fa-tiktok"></i> TikTok
                    </a>
                    <a href="{{isset($appWhatsAppLink) ? $appWhatsAppLink : '#'}}" class="social-link">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                </div>
                <p style="margin-top: 1rem; font-size: 0.9rem; color: #999;">
                    <i class="fas fa-bell"></i> Restez connectés pour nos dernières actualités et événements
                </p>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>&copy; 2024 Église Méthodiste Unie - Côte d'Ivoire. Tous droits réservés.</p>
            <p><i class="fas fa-heart" style="color: #d32f2f;"></i> "Allez donc et faites de toutes les nations des disciples" - Matthieu 28:19</p>
            <p style="font-size: 0.8rem; margin-top: 1rem;">
                Développé avec <i class="fas fa-heart" style="color: #d32f2f;"></i> pour la communauté
            </p>
        </div>
    </footer>
