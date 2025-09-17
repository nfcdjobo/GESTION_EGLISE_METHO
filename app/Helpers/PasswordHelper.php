<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PasswordHelper
{
    /**
     * Vérifier si un hash est un hash bcrypt valide
     */
    public static function isBcryptHash($hash)
    {
        return is_string($hash) &&
               strlen($hash) === 60 &&
               substr($hash, 0, 4) === '$2y$';
    }

    /**
     * Forcer le hachage d'un mot de passe avec bcrypt
     */
    public static function forceHash($password)
    {
        if (empty($password)) {
            return null;
        }

        // Si c'est déjà un hash bcrypt, le retourner tel quel
        if (self::isBcryptHash($password)) {
            return $password;
        }

        // Sinon, le hacher
        return Hash::make($password);
    }

    /**
     * Vérifier un mot de passe contre un hash
     */
    public static function verify($password, $hash)
    {
        if (!self::isBcryptHash($hash)) {
            // Si le hash n'est pas bcrypt, comparaison directe (temporaire)
            return $password === $hash;
        }

        return Hash::check($password, $hash);
    }

    /**
     * Générer un mot de passe temporaire sécurisé
     */
    public static function generateTemporaryPassword($length = 12)
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $symbols = '!@#$%^&*';

        $password = '';

        // S'assurer qu'on a au moins un caractère de chaque type
        $password .= $uppercase[random_int(0, strlen($uppercase) - 1)];
        $password .= $lowercase[random_int(0, strlen($lowercase) - 1)];
        $password .= $numbers[random_int(0, strlen($numbers) - 1)];
        $password .= $symbols[random_int(0, strlen($symbols) - 1)];

        // Compléter avec des caractères aléatoires
        $allChars = $uppercase . $lowercase . $numbers . $symbols;
        for ($i = 4; $i < $length; $i++) {
            $password .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        // Mélanger le mot de passe
        return str_shuffle($password);
    }

    /**
     * Valider la force d'un mot de passe
     */
    public static function validatePasswordStrength($password)
    {
        $score = 0;
        $feedback = [];

        // Longueur
        if (strlen($password) >= 8) {
            $score += 1;
        } else {
            $feedback[] = 'Doit contenir au moins 8 caractères';
        }

        if (strlen($password) >= 12) {
            $score += 1;
        }

        // Minuscules et majuscules
        if (preg_match('/[a-z]/', $password) && preg_match('/[A-Z]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Doit contenir des minuscules ET des majuscules';
        }

        // Chiffres
        if (preg_match('/\d/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Doit contenir au moins un chiffre';
        }

        // Caractères spéciaux
        if (preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $score += 1;
        } else {
            $feedback[] = 'Doit contenir au moins un caractère spécial';
        }

        $strength = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'][$score] ?? 'Très faible';

        return [
            'score' => $score,
            'strength' => $strength,
            'is_valid' => $score >= 3, // Minimum "Fort" requis
            'feedback' => $feedback
        ];
    }

    /**
     * Corriger automatiquement les mots de passe non-bcrypt d'un membres
     */
    public static function fixUserPassword(User $user, $newPassword = null)
    {
        try {
            // Si le mot de passe est déjà bcrypt, rien à faire
            if (self::isBcryptHash($user->password)) {
                return [
                    'success' => true,
                    'message' => 'Mot de passe déjà correctement haché'
                ];
            }

            // Générer un nouveau mot de passe si non fourni
            if (!$newPassword) {
                $newPassword = self::generateTemporaryPassword();
            }

            // Hacher et sauvegarder
            $user->password = self::forceHash($newPassword);
            $user->save();

            return [
                'success' => true,
                'message' => 'Mot de passe corrigé avec succès',
                'temporary_password' => $newPassword
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la correction : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Diagnostic de tous les membres
     */
    public static function diagnoseAllUsers()
    {
        $users = User::all();
        $stats = [
            'total' => $users->count(),
            'bcrypt_ok' => 0,
            'needs_fix' => 0,
            'empty_password' => 0,
            'users_to_fix' => []
        ];

        foreach ($users as $user) {
            if (empty($user->password)) {
                $stats['empty_password']++;
                $stats['users_to_fix'][] = [
                    'id' => $user->id,
                    'email' => $user->email,
                    'issue' => 'Mot de passe vide'
                ];
            } elseif (self::isBcryptHash($user->password)) {
                $stats['bcrypt_ok']++;
            } else {
                $stats['needs_fix']++;
                $stats['users_to_fix'][] = [
                    'id' => $user->id,
                    'email' => $user->email,
                    'issue' => 'Hash non-bcrypt'
                ];
            }
        }

        return $stats;
    }
}
