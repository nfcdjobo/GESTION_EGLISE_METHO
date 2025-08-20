<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Contact principal de l'église
        Contact::updateOrCreate(
            ['type_contact' => 'principal'],
            [
                'nom_eglise' => 'Église Méthodiste Belle Ville',
                'denomination' => 'Église Méthodiste Unie',
                'description_courte' => 'Une communauté chrétienne accueillante au cœur d\'Abidjan, dédiée à l\'amour, au service et à la croissance spirituelle.',
                'mission_vision' => 'Notre mission est de faire des disciples du Christ qui transforment le monde par l\'amour, la justice et la grâce de Dieu.',

                // Coordonnées
                'telephone_principal' => '+225 27 22 50 50 50',
                'telephone_secondaire' => '+225 07 08 09 10 11',
                'whatsapp' => '+225 07 08 09 10 11',

                // Emails
                'email_principal' => 'contact@methodiste-belle-ville.ci',
                'email_administratif' => 'admin@methodiste-belle-ville.ci',
                'email_pastoral' => 'pasteur@methodiste-belle-ville.ci',
                'email_info' => 'info@methodiste-belle-ville.ci',

                // Adresse
                'adresse_complete' => 'Akouai-Santai, Abidjan Autonomous District, Côte d\'Ivoire',
                'rue' => 'Rue de l\'Église Méthodiste',
                'quartier' => 'Akouai-Santai',
                'ville' => 'Abidjan',
                'commune' => 'Abidjan Autonomous District',
                'region' => 'Abidjan Autonomous District',
                'pays' => 'Côte d\'Ivoire',

                // Coordonnées GPS (approximatives pour Akouai-Santai)
                'latitude' => 5.2893,
                'longitude' => -4.0267,
                'indications_acces' => 'Proche du marché d\'Akouai-Santai, à côté de l\'école primaire.',
                'points_repere' => 'Face à la pharmacie Belle Ville, près de l\'arrêt de bus principal.',

                // Réseaux sociaux
                'facebook_url' => 'https://facebook.com/methodiste-belle-ville',
                'facebook_handle' => '@methodiste-belle-ville',
                'instagram_url' => 'https://instagram.com/methodiste_belle_ville',
                'instagram_handle' => '@methodiste_belle_ville',
                'youtube_url' => 'https://youtube.com/@MethodisteBelleVille',
                'youtube_handle' => '@MethodisteBelleVille',

                // Site web
                'site_web_principal' => 'https://methodiste-belle-ville.ci',

                // Streaming
                'youtube_live_url' => 'https://youtube.com/live/methodiste-belle-ville',
                'facebook_live_url' => 'https://facebook.com/methodiste-belle-ville/live',

                // Horaires (JSON)
                'horaires_bureau' => json_encode([
                    'lundi' => ['09:00', '17:00'],
                    'mardi' => ['09:00', '17:00'],
                    'mercredi' => ['09:00', '17:00'],
                    'jeudi' => ['09:00', '17:00'],
                    'vendredi' => ['09:00', '17:00'],
                    'samedi' => ['09:00', '12:00'],
                    'dimanche' => 'Fermé (Cultes uniquement)'
                ]),

                'horaires_cultes' => json_encode([
                    'dimanche_matin' => '08:30 - 11:00',
                    'dimanche_soir' => '17:00 - 19:00',
                    'mercredi_priere' => '18:30 - 20:00',
                    'vendredi_jeunes' => '18:00 - 20:30'
                ]),

                // Informations bancaires pour dons
                'mobile_money_orange' => '+225 07 08 09 10 11',
                'mobile_money_mtn' => '+225 05 06 07 08 09',
                'mobile_money_moov' => '+225 01 02 03 04 05',

                // Leadership
                'pasteur_principal' => 'Pasteur Jean KOUAME',
                'telephone_pasteur' => '+225 27 22 11 11 11',
                'email_pasteur' => 'pasteur@methodiste-belle-ville.ci',
                'secretaire_general' => 'Marie DIABATE',
                'telephone_secretaire' => '+225 27 22 22 22 22',
                'tresorier' => 'Paul TRAORE',
                'telephone_tresorier' => '+225 27 22 33 33 33',

                // Langues
                'langues_parlees' => json_encode(['Français', 'Baoulé', 'Dioula', 'Anglais']),

                // Capacité et infos
                'capacite_accueil' => 300,
                'nombre_membres' => 150,
                'accessibilite_handicap' => true,
                'services_speciaux' => 'École du dimanche, Groupe de jeunes, Ministère des femmes, Chorale, Études bibliques',
                'equipements_disponibles' => 'Sonorisation, Projection, Climatisation, Parking, Salle pour enfants',

                // Paramètres
                'visible_public' => true,
                'afficher_site_web' => true,
                'afficher_app_mobile' => true,
                'partage_autorise' => true,

                // QR Codes et codes
                'hashtag_officiel' => '#MethodisteBelleVille',

                // Contact urgence
                'contact_urgence_medical' => 'SAMU: 185, Dr. KOFFI: +225 07 77 77 77 77',
                'contact_police' => 'Police: 170, Commissariat Akouai-Santai: +225 27 22 40 40 40',
                'contact_pompiers' => 'Pompiers: 180',

                'derniere_mise_a_jour' => now()->toDateString(),
                'verifie' => true,
                'derniere_verification' => now(),
            ]
        );

        // Contact pastoral spécialisé
        Contact::updateOrCreate(
            ['type_contact' => 'pastoral'],
            [
                'nom_eglise' => 'Église Méthodiste Belle Ville - Service Pastoral',
                'type_contact' => 'pastoral',
                'telephone_principal' => '+225 27 22 11 11 11',
                'email_principal' => 'pasteur@methodiste-belle-ville.ci',
                'pasteur_principal' => 'Pasteur Jean KOUAME',
                'telephone_pasteur' => '+225 27 22 11 11 11',
                'email_pasteur' => 'pasteur@methodiste-belle-ville.ci',
                'services_speciaux' => 'Conseil pastoral, Mariage, Baptême, Visite aux malades, Prière individuelle',
                'horaires_speciaux' => 'Consultations pastorales: Mardi et Jeudi 14h-17h sur rendez-vous',
                'visible_public' => true,
                'derniere_mise_a_jour' => now()->toDateString(),
            ]
        );

        // Contact jeunesse
        Contact::updateOrCreate(
            ['type_contact' => 'jeunesse'],
            [
                'nom_eglise' => 'Église Méthodiste Belle Ville - Ministère Jeunesse',
                'type_contact' => 'jeunesse',
                'telephone_principal' => '+225 27 22 44 44 12',
                'email_principal' => 'jeunesse@methodiste-belle-ville.ci',
                'services_speciaux' => 'Groupe de jeunes, Camp d\'été, Activités sportives, Formation leadership jeunes',
                'horaires_speciaux' => 'Réunion jeunes: Vendredi 18h-20h30, Sortie mensuelle: 1er samedi du mois',
                'visible_public' => true,
                'derniere_mise_a_jour' => now()->toDateString(),
            ]
        );

        $this->command->info('✅ Informations de contact de l\'église créées avec succès !');
    }
}
