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
            ['email' => 'nfcdjobo@gmail.com'],
            [
                'id' => Str::uuid(),
                'prenom' => 'Carêm',
                'nom' => 'DJOBO',
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
                'password' => Hash::make('Admin@2025!'), // Mot de passe sécurisé
                'actif' => true,
                'notes_admin' => 'Super administrateur du système - Accès complet',
                'dons_spirituels' => 'Administration, Leadership',
            ]
        );

        // Attribuer le rôle Super Admin
        $superAdminRole = Role::where('slug', 'secretaire')->first();
        if ($superAdminRole) {
            $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id => [
                'attribue_par' => $superAdmin->id,
                'attribue_le' => now(),
                'actif' => true,
            ]]);
        }



        $this->command->info('✅ Utilisateurs de base créés avec succès !');
        $this->command->info('📧 Comptes administratifs :');
        $this->command->info('   - nfcdjobo@gmail.com (Secrétaire)');

        $this->command->info('🔑 Mot de passe par défaut pour tous : password');
    }
}
