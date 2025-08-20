<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Envoyer un email de réinitialisation de mot de passe
     */
    public function sendPasswordResetEmail(User $user, string $token)
    {
        try {
            $data = [
                'user' => $user,
                'token' => $token,
                'resetUrl' => route('security.password.reset', ['token' => $token, 'email' => $user->email]),
                'appName' => config('app.name', 'Plateforme de l\'Église'),
            ];

            // Vous pouvez créer un Mailable personnalisé pour plus de flexibilité
            Mail::send('emails.password-reset', $data, function($message) use ($user) {
                $message->to($user->email, $user->nom_complet)
                       ->subject('Réinitialisation de votre mot de passe - ' . config('app.name'));
                $message->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info('Email de réinitialisation envoyé', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de réinitialisation', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Envoyer un SMS de réinitialisation (pour l'avenir)
     */
    public function sendPasswordResetSMS(User $user, string $code)
    {
        try {
            // Intégration avec un service SMS (ex: Twilio, Orange SMS API, etc.)
            $message = "Votre code de réinitialisation : {$code}. Valide pendant 10 minutes.";

            // Exemple d'implémentation (à adapter selon votre fournisseur SMS)
            // SMS::send($user->telephone_1, $message);

            Log::info('SMS de réinitialisation envoyé', [
                'user_id' => $user->id,
                'phone' => $user->telephone_1
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi du SMS de réinitialisation', [
                'user_id' => $user->id,
                'phone' => $user->telephone_1,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Envoyer un email de bienvenue après inscription
     */
    public function sendWelcomeEmail(User $user)
    {
        try {
            $data = [
                'user' => $user,
                'loginUrl' => route('security.login'),
                'appName' => config('app.name', 'Plateforme de l\'Église'),
            ];

            Mail::send('emails.welcome', $data, function($message) use ($user) {
                $message->to($user->email, $user->nom_complet)
                       ->subject('Bienvenue sur ' . config('app.name'));
                $message->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info('Email de bienvenue envoyé', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de bienvenue', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Notifier les administrateurs d'une nouvelle inscription
     */
    public function notifyAdminsNewRegistration(User $newUser)
    {
        try {
            // Récupérer tous les administrateurs
            $admins = User::whereHas('roles', function($query) {
                $query->whereIn('slug', ['admin', 'super_admin']);
            })->where('actif', true)->get();

            foreach ($admins as $admin) {
                $data = [
                    'admin' => $admin,
                    'newUser' => $newUser,
                    'adminUrl' => route('admin.users.show', $newUser->id),
                ];

                Mail::send('emails.admin-new-user', $data, function($message) use ($admin, $newUser) {
                    $message->to($admin->email, $admin->nom_complet)
                           ->subject('Nouvelle inscription - ' . $newUser->nom_complet);
                    $message->from(config('mail.from.address'), config('mail.from.name'));
                });
            }

            Log::info('Notification d\'inscription envoyée aux admins', [
                'new_user_id' => $newUser->id,
                'admin_count' => $admins->count()
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de la notification aux admins', [
                'new_user_id' => $newUser->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Envoyer une notification de tentative de connexion suspecte
     */
    public function notifySuspiciousLogin(User $user, array $details)
    {
        try {
            $data = [
                'user' => $user,
                'details' => $details,
                'securityUrl' => route('security.login'),
            ];

            Mail::send('emails.suspicious-login', $data, function($message) use ($user) {
                $message->to($user->email, $user->nom_complet)
                       ->subject('Tentative de connexion suspecte détectée');
                $message->from(config('mail.from.address'), config('mail.from.name'));
            });

            Log::info('Notification de connexion suspecte envoyée', [
                'user_id' => $user->id,
                'details' => $details
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification de sécurité', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}
