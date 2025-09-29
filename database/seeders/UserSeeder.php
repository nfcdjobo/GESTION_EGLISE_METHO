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
        // Créer l'membres Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => env('SUPER_ADMIN_ACCESS_EMAIL', 'nfcdjobo@gmail.com')],
            [
                'id' => Str::uuid(),
                'prenom' => env('SUPER_ADMIN_ACCESS_PRENOM', 'N’DRI FRANÇOIS'),
                'nom' => env('SUPER_ADMIN_ACCESS_NOM', 'DJOBO'),
                'sexe' => 'masculin',
                'telephone_1' => env('SUPER_ADMIN_ACCESS_PHONE', '+2250575554499'),
                'email_verified_at' => now(),
                'adresse_ligne_1' => env('SUPER_ADMIN_ACCESS_ADDRESS', 'Cocody, Paris Village'),
                'ville' => env('SUPER_ADMIN_ACCESS_VILLE', 'Abidjan'),
                'region' => env('SUPER_ADMIN_ACCESS_REGION', 'District autonome d’Abidjan'),
                'pays' => env('SUPER_ADMIN_ACCESS_PAYS', 'CÔTE D’IVOIRE'),
                'statut_matrimonial' => env('SUPER_ADMIN_ACCESS_STATUS_MATRIMONIAL', 'celibataire'),
                'nombre_enfants' => env('SUPER_ADMIN_ACCESS_NOMBRE_ENFANT', 0),
                'profession' => env('SUPER_ADMIN_ACCESS_PROFESSION', null),
                'date_adhesion' => env('SUPER_ADMIN_ACCESS_DATE_ADHESION', null),
                'statut_membre' => env('SUPER_ADMIN_ACCESS_DATE_STATUS_MEMBRE', 'actif'),
                'statut_bapteme' => env('SUPER_ADMIN_ACCESS_DATE_STATUS_BAPTEME', 'non_baptise'),
                'date_bapteme' => env('SUPER_ADMIN_ACCESS_DATE_DATE_BAPTEME', null),
                'password' => Hash::make(env('SUPER_ADMIN_ACCESS_PASSWORD', 'nfcDJ0B0@CanaanBelleVille@07078315M@')), // Mot de passe sécurisé
                'actif' => true,
                'notes_admin' => 'Super administrateur du système - Accès complet',
                'dons_spirituels' => null,
            ]
        );

        // Attribuer le rôle Sécrétaire
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        if ($superAdminRole) {
            $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id => [
                'attribue_par' => $superAdmin->id,
                'attribue_le' => now(),
                'actif' => true,
            ]]);
        }


        $this->command->info('✅ Membress de base créés avec succès !');
        $this->command->info('📧 Comptes administratifs :');
        $this->command->info('   - nfcdjobo@gmail.com (Secrétaire)');

        $this->command->info('🔑 Mot de passe par défaut pour tous : password');
    }
}
