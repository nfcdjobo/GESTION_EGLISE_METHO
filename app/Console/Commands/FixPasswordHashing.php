<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FixPasswordHashing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:fix-passwords {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix password hashing for existing users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 Mode test activé - Aucune modification ne sera apportée');
        } else {
            $this->warn('⚠️  Mode réel - Les mots de passe seront modifiés');
            if (!$this->confirm('Voulez-vous continuer ?')) {
                $this->info('Opération annulée');
                return;
            }
        }

        $this->info('🔍 Recherche des utilisateurs avec des mots de passe non-hachés...');

        // Récupérer tous les utilisateurs
        $users = User::all();
        $fixedCount = 0;
        $errorCount = 0;
        $alreadyHashedCount = 0;

        foreach ($users as $user) {
            try {
                // Vérifier si le mot de passe est déjà haché avec bcrypt
                if ($this->isBcryptHash($user->password)) {
                    $alreadyHashedCount++;
                    $this->line("✅ {$user->email} - Déjà haché correctement");
                    continue;
                }

                if ($dryRun) {
                    $this->warn("🔧 {$user->email} - Nécessite un nouveau hachage");
                    $fixedCount++;
                } else {
                    // Si le mot de passe n'est pas haché ou utilise un autre algorithme
                    // On va créer un mot de passe temporaire sécurisé
                    $tempPassword = $this->generateTemporaryPassword();

                    // Hacher le nouveau mot de passe
                    $user->password = Hash::make($tempPassword);
                    $user->save();

                    $this->warn("🔧 {$user->email} - Mot de passe réinitialisé : {$tempPassword}");
                    $this->warn("   ⚠️  L'utilisateur devra changer son mot de passe à la prochaine connexion");

                    $fixedCount++;
                }

            } catch (\Exception $e) {
                $this->error("❌ Erreur pour {$user->email}: " . $e->getMessage());
                $errorCount++;
            }
        }

        // Résumé
        $this->info("\n📊 Résumé :");
        $this->info("   Total d'utilisateurs : " . $users->count());
        $this->info("   Déjà hachés correctement : {$alreadyHashedCount}");
        $this->info("   " . ($dryRun ? 'À corriger' : 'Corrigés') . " : {$fixedCount}");
        $this->info("   Erreurs : {$errorCount}");

        if (!$dryRun && $fixedCount > 0) {
            $this->warn("\n⚠️  IMPORTANT :");
            $this->warn("   Les utilisateurs avec des mots de passe réinitialisés doivent :");
            $this->warn("   1. Utiliser leur nouveau mot de passe temporaire pour se connecter");
            $this->warn("   2. Changer immédiatement leur mot de passe via leur profil");
            $this->warn("   3. Ou utiliser la fonction 'Mot de passe oublié'");
        }

        if ($dryRun && $fixedCount > 0) {
            $this->info("\n💡 Pour appliquer les corrections, exécutez :");
            $this->info("   php artisan auth:fix-passwords");
        }
    }

    /**
     * Vérifier si une chaîne est un hash bcrypt
     */
    private function isBcryptHash($password)
    {
        // Un hash bcrypt commence par $2y$ et fait 60 caractères
        return is_string($password) &&
               strlen($password) === 60 &&
               substr($password, 0, 4) === '$2y$';
    }

    /**
     * Générer un mot de passe temporaire sécurisé
     */
    private function generateTemporaryPassword()
    {
        // Générer un mot de passe de 12 caractères avec lettres, chiffres et symboles
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        $password = '';

        for ($i = 0; $i < 12; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }

        return $password;
    }
}
