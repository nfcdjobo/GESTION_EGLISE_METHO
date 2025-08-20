<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Programme;
use App\Models\User;

class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les utilisateurs pour les assigner comme responsables
        $pasteur = User::where('email', 'pasteur@methodiste-belle-ville.ci')->first();
        $secretaire = User::where('email', 'secretaire@methodiste-belle-ville.ci')->first();
        $enseignantGrace = User::where('email', 'grace.yao@methodiste-belle-ville.ci')->first();
        $enseignantDavid = User::where('email', 'david.kone@methodiste-belle-ville.ci')->first();

        $programmes = [
            [
                'nom_programme' => 'Programme Cultuel Hebdomadaire',
                'code_programme' => 'CULTE_HEBDO_2025',
                'description' => 'Programme complet des cultes hebdomadaires de l\'église incluant les services du dimanche et les réunions de prière.',

                'type_programme' => 'culte_regulier',
                
                'frequence' => 'hebdomadaire',
                'jours_semaine' => json_encode([3, 7]), // Mercredi et Dimanche
                'heure_debut' => '08:30',
                'heure_fin' => '20:00',
                'duree_minutes' => 690,
                'date_debut' => '2025-01-01',
                'programme_permanent' => true,
                'lieu_principal' => 'Sanctuaire Principal',
                'responsable_principal_id' => $pasteur?->id,
                'coordinateur_id' => $secretaire?->id,
                'audience_cible' => 'tous',
                'participants_attendus' => 200,
                'participants_max' => 300,
                'objectifs' => json_encode([
                    'Offrir des moments de culte enrichissants',
                    'Favoriser la croissance spirituelle',
                    'Renforcer la communion fraternelle',
                    'Évangéliser la communauté locale'
                ]),
                'statut' => 'actif',
                'priorite' => 'haute',
                'promotion_requise' => true,
                'evaluation_requise' => true,
                'synchroniser_calendrier' => true,
                'generer_notifications' => true,
                'tracking_presence' => true,
            ],
            [
                'nom_programme' => 'École du Dimanche - Année 2025',
                'code_programme' => 'ECOLE_DIM_2025',
                'description' => 'Programme annuel d\'enseignement biblique pour tous les âges avec curriculum adapté à chaque tranche d\'âge.',
                'theme_annuel' => 'Grandir en Sagesse et en Grâce',
                'type_programme' => 'formation',
                'niveau_programme' => 'local',
                'frequence' => 'hebdomadaire',
                'jours_semaine' => json_encode([7]), // Dimanche
                'heure_debut' => '09:00',
                'heure_fin' => '10:30',
                'duree_minutes' => 90,
                'date_debut' => '2025-01-05',
                'date_fin' => '2025-12-28',
                'lieu_principal' => 'Salles de Classes',
                'responsable_principal_id' => $enseignantGrace?->id,
                'coordinateur_id' => $enseignantDavid?->id,
                'audience_cible' => 'tous',
                'participants_attendus' => 80,
                'participants_max' => 120,
                'inscription_requise' => true,
                'modules_contenu' => json_encode([
                    'Enfants 4-6 ans' => 'Histoires bibliques illustrées',
                    'Enfants 7-9 ans' => 'Héros de la Bible',
                    'Préados 10-12 ans' => 'Principes de vie chrétienne',
                    'Adolescents 13-17 ans' => 'Identité et foi',
                    'Jeunes adultes 18-30 ans' => 'Vie chrétienne pratique',
                    'Adultes 30+ ans' => 'Études bibliques approfondies'
                ]),
                'equipements_necessaires' => json_encode([
                    'Tableaux',
                    'Projecteurs',
                    'Matériel pédagogique',
                    'Bibles',
                    'Cahiers d\'activités'
                ]),
                'statut' => 'actif',
                'priorite' => 'haute',
                'necessite_approbation' => true,
                'approuve_par' => $pasteur?->id,
                'approuve_le' => now(),
                'evaluation_requise' => true,
                'tracking_presence' => true,
            ],
            [
                'nom_programme' => 'Ministère Jeunesse - Dynamique 2025',
                'code_programme' => 'JEUNESSE_2025',
                'description' => 'Programme dynamique pour les jeunes avec activités spirituelles, sociales et formatrices.',
                'theme_annuel' => 'Jeunes Disciples, Futurs Leaders',
                'type_programme' => 'jeunesse',
                'niveau_programme' => 'local',
                'frequence' => 'hebdomadaire',
                'jours_semaine' => json_encode([5]), // Vendredi
                'heure_debut' => '18:00',
                'heure_fin' => '20:30',
                'duree_minutes' => 150,
                'date_debut' => '2025-01-03',
                'date_fin' => '2025-12-26',
                'lieu_principal' => 'Salle des Jeunes',
                'responsable_principal_id' => $enseignantDavid?->id,
                'audience_cible' => 'jeunes',
                'participants_attendus' => 40,
                'participants_max' => 60,
                // 'age_minimum' => 13,
                'objectifs' => json_encode([
                    'Former des leaders chrétiens',
                    'Développer les talents des jeunes',
                    'Créer une communauté soudée',
                    'Préparer l\'avenir de l\'église'
                ]),
                'modules_contenu' => json_encode([
                    'Formation spirituelle',
                    'Leadership chrétien',
                    'Musique et louange',
                    'Sports et loisirs',
                    'Service communautaire',
                    'Camps et retraites'
                ]),
                'budget_prevu' => 500000, // 500,000 FCFA
                'statut' => 'actif',
                'priorite' => 'normale',
                'promotion_requise' => true,
                'canaux_communication' => json_encode(['Réseaux sociaux', 'Affiches', 'Annonces cultes']),
                'tracking_presence' => true,
            ],
            [
                'nom_programme' => 'Formation des Enseignants',
                'code_programme' => 'FORM_ENSEIG_2025',
                'description' => 'Programme trimestriel de formation continue pour les enseignants de l\'école du dimanche.',
                'type_programme' => 'formation',
                'niveau_programme' => 'local',
                'frequence' => 'trimestriel',
                'date_debut' => '2025-02-01',
                'date_fin' => '2025-11-30',
                'lieu_principal' => 'Salle de Conférence',
                'responsable_principal_id' => $pasteur?->id,
                'audience_cible' => 'leadership',
                'participants_attendus' => 15,
                'participants_max' => 20,
                'inscription_requise' => true,
                'objectifs' => json_encode([
                    'Améliorer les méthodes pédagogiques',
                    'Approfondir la connaissance biblique',
                    'Développer les compétences relationnelles',
                    'Partager les meilleures pratiques'
                ]),
                'modules_contenu' => json_encode([
                    'Pédagogie biblique',
                    'Psychologie de l\'enfant',
                    'Méthodes d\'enseignement interactives',
                    'Gestion de classe',
                    'Spiritualité de l\'enseignant'
                ]),
                'statut' => 'planifie',
                'priorite' => 'normale',
                'necessite_approbation' => true,
                'evaluation_requise' => true,
            ],
            [
                'nom_programme' => 'Campagne d\'Évangélisation Communautaire',
                'code_programme' => 'EVANG_COMM_2025',
                'description' => 'Programme spécial d\'évangélisation pour atteindre la communauté d\'Akouai-Santai.',
                'theme_annuel' => 'Partager l\'Amour du Christ',
                'type_programme' => 'evangelisation',
                'niveau_programme' => 'local',
                'frequence' => 'mensuel',
                'date_debut' => '2025-03-01',
                'date_fin' => '2025-10-31',
                'lieu_principal' => 'Divers lieux communautaires',
                'responsable_principal_id' => $pasteur?->id,
                'coordinateur_id' => $secretaire?->id,
                'audience_cible' => 'visiteurs',
                'participants_attendus' => 100,
                'objectifs' => json_encode([
                    'Partager l\'Évangile avec 500 personnes',
                    'Organiser 8 événements d\'évangélisation',
                    'Former 20 évangélistes locaux',
                    'Accueillir 50 nouveaux visiteurs'
                ]),
                'budget_prevu' => 1000000, // 1,000,000 FCFA
                'statut' => 'planifie',
                'priorite' => 'haute',
                'promotion_requise' => true,
                'necessite_approbation' => true,
                'evaluation_requise' => true,
            ],
        ];

        foreach ($programmes as $programmeData) {
            Programme::updateOrCreate(
                ['code_programme' => $programmeData['code_programme']],
                $programmeData
            );
        }

        $this->command->info('✅ Programmes de base créés avec succès !');
    }
}
