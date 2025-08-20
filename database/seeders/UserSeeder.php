<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer l'utilisateur Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@methodiste-belle-ville.ci'],
            [
                'id' => Str::uuid(),
                'prenom' => 'Super',
                'nom' => 'Administrateur',
                'date_naissance' => '1980-01-01',
                'sexe' => 'masculin',
                'telephone_1' => '+225 27 22 00 00 00',
                'email_verified_at' => now(),
                'adresse_ligne_1' => 'Cocody Riviera',
                'ville' => 'Abidjan',
                'region' => 'Abidjan',
                'pays' => 'CI',
                'statut_matrimonial' => 'marie',
                'nombre_enfants' => 2,
                'profession' => 'Administrateur Système',
                'date_adhesion' => '2020-01-01',
                'statut_membre' => 'actif',
                'statut_bapteme' => 'confirme',
                'date_bapteme' => '2000-01-01',
                'password' => Hash::make('password'),
                'actif' => true,
                'notes_admin' => 'Super administrateur du système - Accès complet',
                'dons_spirituels' => 'Administration, Leadership',
            ]
        );

        // Attribuer le rôle Super Admin
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        if ($superAdminRole) {
            $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id => [
                'attribue_par' => $superAdmin->id,
                'attribue_le' => now(),
                'actif' => true,
            ]]);
        }

        // Créer le pasteur principal
        $pasteur = User::updateOrCreate(
            ['email' => 'pasteur@methodiste-belle-ville.ci'],
            [
                'id' => Str::uuid(),
                'prenom' => 'Jean',
                'nom' => 'KOUAME',
                'date_naissance' => '1975-05-15',
                'sexe' => 'masculin',
                'telephone_1' => '+225 27 22 11 11 11',
                'telephone_2' => '+225 07 01 02 03 04',
                'email_verified_at' => now(),
                'adresse_ligne_1' => 'Quartier Akouai-Santai',
                'adresse_ligne_2' => 'Près de l\'école primaire',
                'ville' => 'Abidjan',
                'code_postal' => '00225',
                'region' => 'Abidjan',
                'pays' => 'CI',
                'statut_matrimonial' => 'marie',
                'nombre_enfants' => 3,
                'profession' => 'Pasteur',
                'employeur' => 'Église Méthodiste Unie Belle-Ville',
                'date_adhesion' => '1995-06-20',
                'statut_membre' => 'actif',
                'statut_bapteme' => 'confirme',
                'date_bapteme' => '1995-06-20',
                'contact_urgence_nom' => 'Marie KOUAME',
                'contact_urgence_telephone' => '+225 07 05 06 07 08',
                'contact_urgence_relation' => 'Épouse',
                'temoignage' => 'Appelé au ministère pastoral en 1994, converti depuis l\'adolescence.',
                'dons_spirituels' => 'Prédication, Enseignement, Leadership pastoral, Conseil spirituel',
                'password' => Hash::make('password'),
                'actif' => true,
                'notes_admin' => 'Pasteur principal - Responsabilité pastorale complète',
            ]
        );

        $pasteurRole = Role::where('slug', 'pasteur')->first();
        if ($pasteurRole) {
            $pasteur->roles()->syncWithoutDetaching([$pasteurRole->id => [
                'attribue_par' => $superAdmin->id,
                'attribue_le' => now(),
                'actif' => true,
            ]]);
        }

        // Créer le secrétaire
        $secretaire = User::updateOrCreate(
            ['email' => 'secretaire@methodiste-belle-ville.ci'],
            [
                'id' => Str::uuid(),
                'prenom' => 'Marie',
                'nom' => 'DIABATE',
                'date_naissance' => '1985-08-20',
                'sexe' => 'feminin',
                'telephone_1' => '+225 27 22 22 22 22',
                'email_verified_at' => now(),
                'adresse_ligne_1' => 'Yopougon Maroc',
                'ville' => 'Abidjan',
                'region' => 'Abidjan',
                'pays' => 'CI',
                'statut_matrimonial' => 'marie',
                'nombre_enfants' => 1,
                'profession' => 'Secrétaire',
                'employeur' => 'Ministère de l\'Éducation',
                'date_adhesion' => '2005-09-15',
                'statut_membre' => 'actif',
                'statut_bapteme' => 'confirme',
                'date_bapteme' => '2005-09-15',
                'contact_urgence_nom' => 'Ibrahim DIABATE',
                'contact_urgence_telephone' => '+225 07 09 10 11 12',
                'contact_urgence_relation' => 'Époux',
                'dons_spirituels' => 'Administration, Organisation, Accueil',
                'password' => Hash::make('password'),
                'actif' => true,
                'notes_admin' => 'Secrétaire générale - Gestion administrative',
            ]
        );

        $secretaireRole = Role::where('slug', 'secretaire')->first();
        if ($secretaireRole) {
            $secretaire->roles()->syncWithoutDetaching([$secretaireRole->id => [
                'attribue_par' => $superAdmin->id,
                'attribue_le' => now(),
                'actif' => true,
            ]]);
        }

        // Créer le trésorier
        $tresorier = User::updateOrCreate(
            ['email' => 'tresorier@methodiste-belle-ville.ci'],
            [
                'id' => Str::uuid(),
                'prenom' => 'Paul',
                'nom' => 'TRAORE',
                'date_naissance' => '1978-12-10',
                'sexe' => 'masculin',
                'telephone_1' => '+225 27 22 33 33 33',
                'email_verified_at' => now(),
                'adresse_ligne_1' => 'Adjamé 220 Logements',
                'ville' => 'Abidjan',
                'region' => 'Abidjan',
                'pays' => 'CI',
                'statut_matrimonial' => 'marie',
                'nombre_enfants' => 2,
                'profession' => 'Comptable',
                'employeur' => 'Cabinet d\'expertise comptable KPMG',
                'date_adhesion' => '1998-03-22',
                'statut_membre' => 'actif',
                'statut_bapteme' => 'confirme',
                'date_bapteme' => '1998-03-22',
                'contact_urgence_nom' => 'Fatou TRAORE',
                'contact_urgence_telephone' => '+225 07 13 14 15 16',
                'contact_urgence_relation' => 'Épouse',
                'dons_spirituels' => 'Administration financière, Intendance',
                'password' => Hash::make('password'),
                'actif' => true,
                'notes_admin' => 'Trésorier - Gestion financière de l\'église',
            ]
        );

        $tresorierRole = Role::where('slug', 'tresorier')->first();
        if ($tresorierRole) {
            $tresorier->roles()->syncWithoutDetaching([$tresorierRole->id => [
                'attribue_par' => $superAdmin->id,
                'attribue_le' => now(),
                'actif' => true,
            ]]);
        }

        // Créer quelques enseignants
        $enseignants = [
            [
                'prenom' => 'Grace',
                'nom' => 'YAO',
                'email' => 'grace.yao@methodiste-belle-ville.ci',
                'specialite' => 'École du Dimanche Enfants',
                'age_groupe' => '6-8 ans',
                'adresse' => 'Cocody Danga',
                'telephone_urgence' => '+225 07 17 18 19 20'
            ],
            [
                'prenom' => 'David',
                'nom' => 'KONE',
                'email' => 'david.kone@methodiste-belle-ville.ci',
                'specialite' => 'École du Dimanche Jeunes',
                'age_groupe' => '13-17 ans',
                'adresse' => 'Plateau',
                'telephone_urgence' => '+225 07 21 22 23 24'
            ],
            [
                'prenom' => 'Esther',
                'nom' => 'BAMBA',
                'email' => 'esther.bamba@methodiste-belle-ville.ci',
                'specialite' => 'École du Dimanche Adultes',
                'age_groupe' => '18+ ans',
                'adresse' => 'Marcory Zone 4',
                'telephone_urgence' => '+225 07 25 26 27 28'
            ]
        ];

        $enseignantRole = Role::where('slug', 'enseignant')->first();

        foreach ($enseignants as $index => $enseignantData) {
            $enseignant = User::updateOrCreate(
                ['email' => $enseignantData['email']],
                [
                    'id' => Str::uuid(),
                    'prenom' => $enseignantData['prenom'],
                    'nom' => $enseignantData['nom'],
                    'date_naissance' => '1990-0' . ($index + 3) . '-15',
                    'sexe' => $index % 2 === 0 ? 'feminin' : 'masculin',
                    'telephone_1' => '+225 27 22 44 44 ' . str_pad($index + 10, 2, '0', STR_PAD_LEFT),
                    'email_verified_at' => now(),
                    'adresse_ligne_1' => $enseignantData['adresse'],
                    'ville' => 'Abidjan',
                    'region' => 'Abidjan',
                    'pays' => 'CI',
                    'statut_matrimonial' => $index % 2 === 0 ? 'celibataire' : 'marie',
                    'nombre_enfants' => $index % 2,
                    'profession' => 'Enseignant(e)',
                    'employeur' => $index % 2 === 0 ? 'École privée' : 'Ministère Éducation Nationale',
                    'date_adhesion' => '2010-0' . ($index + 3) . '-15',
                    'statut_membre' => 'actif',
                    'statut_bapteme' => 'confirme',
                    'date_bapteme' => '2010-0' . ($index + 3) . '-15',
                    'contact_urgence_nom' => 'Contact ' . $enseignantData['prenom'],
                    'contact_urgence_telephone' => $enseignantData['telephone_urgence'],
                    'contact_urgence_relation' => $index % 2 === 0 ? 'Parent' : 'Conjoint(e)',
                    'dons_spirituels' => 'Enseignement, Patience avec les ' . strtolower($enseignantData['age_groupe']),
                    'password' => Hash::make('password'),
                    'actif' => true,
                    'notes_admin' => 'Enseignant(e) - ' . $enseignantData['specialite'] . ' (' . $enseignantData['age_groupe'] . ')',
                ]
            );

            if ($enseignantRole) {
                $enseignant->roles()->syncWithoutDetaching([$enseignantRole->id => [
                    'attribue_par' => $superAdmin->id,
                    'attribue_le' => now(),
                    'actif' => true,
                ]]);
            }
        }

        // Créer quelques membres ordinaires
        $membres = [
            [
                'prenom' => 'Ange',
                'nom' => 'KOUASSI',
                'email' => 'ange.kouassi@methodiste-belle-ville.ci',
                'sexe' => 'masculin',
                'statut' => 'actif'
            ],
            [
                'prenom' => 'Bénédicte',
                'nom' => 'OUATTARA',
                'email' => 'benedicte.ouattara@methodiste-belle-ville.ci',
                'sexe' => 'feminin',
                'statut' => 'actif'
            ],
            [
                'prenom' => 'Christian',
                'nom' => 'GBAGBO',
                'email' => 'christian.gbagbo@methodiste-belle-ville.ci',
                'sexe' => 'masculin',
                'statut' => 'nouveau_converti'
            ],
            [
                'prenom' => 'Dorothée',
                'nom' => 'SANOGO',
                'email' => 'dorothee.sanogo@methodiste-belle-ville.ci',
                'sexe' => 'feminin',
                'statut' => 'visiteur'
            ]
        ];

        $membreRole = Role::where('slug', 'membre')->first();

        foreach ($membres as $index => $membreData) {
            $membre = User::updateOrCreate(
                ['email' => $membreData['email']],
                [
                    'id' => Str::uuid(),
                    'prenom' => $membreData['prenom'],
                    'nom' => $membreData['nom'],
                    'date_naissance' => '199' . ($index + 5) . '-0' . ($index + 6) . '-20',
                    'sexe' => $membreData['sexe'],
                    'telephone_1' => '+225 07 50 50 ' . str_pad($index + 50, 2, '0', STR_PAD_LEFT) . ' ' . str_pad($index + 60, 2, '0', STR_PAD_LEFT),
                    'email_verified_at' => $membreData['statut'] !== 'visiteur' ? now() : null,
                    'adresse_ligne_1' => 'Quartier ' . ($index % 2 === 0 ? 'Akouai-Santai' : 'Belle-Ville'),
                    'ville' => 'Abidjan',
                    'region' => 'Abidjan',
                    'pays' => 'CI',
                    'statut_matrimonial' => ['celibataire', 'marie', 'celibataire', 'marie'][$index],
                    'nombre_enfants' => $index % 3,
                    'profession' => ['Étudiant', 'Infirmière', 'Mécanicien', 'Commerçante'][$index],
                    'date_adhesion' => $membreData['statut'] !== 'visiteur' ? '202' . ($index + 1) . '-01-15' : null,
                    'statut_membre' => $membreData['statut'],
                    'statut_bapteme' => $membreData['statut'] === 'visiteur' ? 'non_baptise' : ($membreData['statut'] === 'nouveau_converti' ? 'baptise' : 'confirme'),
                    'date_bapteme' => $membreData['statut'] !== 'visiteur' && $membreData['statut'] !== 'nouveau_converti' ? '202' . ($index + 1) . '-02-20' : null,
                    'password' => Hash::make('password'),
                    'actif' => true,
                    'notes_admin' => 'Membre - Statut: ' . $membreData['statut'],
                ]
            );

            if ($membreRole && $membreData['statut'] !== 'visiteur') {
                $membre->roles()->syncWithoutDetaching([$membreRole->id => [
                    'attribue_par' => $superAdmin->id,
                    'attribue_le' => now(),
                    'actif' => true,
                ]]);
            }
        }

        $this->command->info('✅ Utilisateurs de base créés avec succès !');
        $this->command->info('📧 Comptes administratifs :');
        $this->command->info('   - admin@methodiste-belle-ville.ci (Super Admin)');
        $this->command->info('   - pasteur@methodiste-belle-ville.ci (Pasteur)');
        $this->command->info('   - secretaire@methodiste-belle-ville.ci (Secrétaire)');
        $this->command->info('   - tresorier@methodiste-belle-ville.ci (Trésorier)');
        $this->command->info('📚 Enseignants :');
        $this->command->info('   - grace.yao@methodiste-belle-ville.ci (Enfants 6-8 ans)');
        $this->command->info('   - david.kone@methodiste-belle-ville.ci (Jeunes 13-17 ans)');
        $this->command->info('   - esther.bamba@methodiste-belle-ville.ci (Adultes)');
        $this->command->info('👥 Membres :');
        $this->command->info('   - ange.kouassi@methodiste-belle-ville.ci (Membre actif)');
        $this->command->info('   - benedicte.ouattara@methodiste-belle-ville.ci (Membre actif)');
        $this->command->info('   - christian.gbagbo@methodiste-belle-ville.ci (Nouveau converti)');
        $this->command->info('   - dorothee.sanogo@methodiste-belle-ville.ci (Visiteur)');
        $this->command->info('🔑 Mot de passe par défaut pour tous : password');
    }
}
