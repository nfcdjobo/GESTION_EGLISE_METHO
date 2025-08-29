<?php

namespace App\Console;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\RefreshPermissionCache;
use App\Jobs\EnvoyerRappelsPaiements;
use App\Jobs\MettreAJourStatistiques;
use App\Jobs\GeneratePermissionReport;
use App\Jobs\CleanupExpiredPermissions;
use App\Jobs\NotifyExpiringPermissions;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\ManagePermissions::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // ========================================
        // TÂCHES DE PERMISSIONS
        // ========================================

        // Nettoyer les permissions expirées tous les jours à 2h du matin
        $schedule->job(new CleanupExpiredPermissions())
            ->daily()
            ->at('02:00')
            ->onFailure(function () {
                Log::error('Échec du nettoyage des permissions expirées');
            })
            ->onSuccess(function () {
                Log::info('Nettoyage des permissions expirées réussi');
            });

        // Notifier les permissions expirant dans 7 jours - tous les jours à 9h
        $schedule->job(new NotifyExpiringPermissions(7))
            ->daily()
            ->at('09:00')
            ->onFailure(function () {
                Log::error('Échec de notification des permissions expirant dans 7 jours');
            });

        // Notifier les permissions expirant dans 1 jour - tous les jours à 8h et 16h
        $schedule->job(new NotifyExpiringPermissions(1))
            ->twiceDaily(8, 16)
            ->onFailure(function () {
                Log::error('Échec de notification des permissions expirant dans 1 jour');
            });

        // Rafraîchir complètement le cache des permissions une fois par semaine
        $schedule->job(new RefreshPermissionCache(null, true))
            ->weekly()
            ->sundays()
            ->at('03:00')
            ->onSuccess(function () {
                Log::info('Cache des permissions rafraîchi avec succès');
            });

        // Générer un rapport hebdomadaire des permissions
        $schedule->job(new GeneratePermissionReport('full', 'csv'))
            ->weekly()
            ->mondays()
            ->at('06:00')
            ->onSuccess(function () {
                Log::info('Rapport hebdomadaire des permissions généré');
            });

        // Générer un rapport mensuel détaillé
        $schedule->job(new GeneratePermissionReport('audit', 'json'))
            ->monthly()
            ->at('01:00')
            ->onSuccess(function () {
                Log::info('Rapport mensuel d\'audit généré');
            });

        // Nettoyer les vieux logs d'audit (plus de 90 jours)
        $schedule->call(function () {
            \App\Models\PermissionAuditLog::where('created_at', '<', now()->subDays(90))
                ->delete();
            Log::info('Vieux logs d\'audit supprimés');
        })->monthly()->at('04:00');

        // Vérifier l'intégrité du système de permissions
        $schedule->call(function () {
            $this->checkPermissionSystemIntegrity();
        })->daily()->at('05:00');

        // Optimiser les tables de permissions
        $schedule->call(function () {
            DB::statement('OPTIMIZE TABLE permissions');
            DB::statement('OPTIMIZE TABLE roles');
            DB::statement('OPTIMIZE TABLE user_permissions');
            DB::statement('OPTIMIZE TABLE user_roles');
            DB::statement('OPTIMIZE TABLE role_permissions');
            DB::statement('OPTIMIZE TABLE permission_audit_logs');
            Log::info('Tables de permissions optimisées');
        })->weekly()->sundays()->at('04:00');




        // Rappels de paiement à 8h00 chaque matin
        $schedule->job(new EnvoyerRappelsPaiements(7))->dailyAt('08:00');
        $schedule->job(new EnvoyerRappelsPaiements(3))->dailyAt('08:00');
        $schedule->job(new EnvoyerRappelsPaiements(1))->dailyAt('08:00');

        // Rappels pour paiements en retard à 8h00
        $schedule->job(new EnvoyerRappelsPaiements(0))->dailyAt('08:00');

        // Mise à jour des statistiques toutes les heures
        $schedule->job(new MettreAJourStatistiques())->hourly();

        // Nettoyage des logs anciens (90 jours)
        $schedule->command('model:prune', ['--model' => 'App\Models\SubscriptionPaymentLog'])
                 ->monthly()
                 ->where('created_at', '<', now()->subDays(90));

        // ========================================
        // AUTRES TÂCHES DE L'APPLICATION
        // ========================================

        // Vos autres tâches planifiées ici...
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Vérifier l'intégrité du système de permissions
     */
    protected function checkPermissionSystemIntegrity()
    {
        try {
            // Vérifier les permissions orphelines
            $orphanedUserPermissions = DB::table('user_permissions')
                ->leftJoin('users', 'user_permissions.user_id', '=', 'users.id')
                ->leftJoin('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
                ->where(function ($query) {
                    $query->whereNull('users.id')
                          ->orWhereNull('permissions.id');
                })
                ->count();

            if ($orphanedUserPermissions > 0) {
                Log::warning("Permissions utilisateur orphelines détectées", [
                    'count' => $orphanedUserPermissions
                ]);

                // Nettoyer les permissions orphelines
                DB::table('user_permissions')
                    ->leftJoin('users', 'user_permissions.user_id', '=', 'users.id')
                    ->leftJoin('permissions', 'user_permissions.permission_id', '=', 'permissions.id')
                    ->where(function ($query) {
                        $query->whereNull('users.id')
                              ->orWhereNull('permissions.id');
                    })
                    ->delete();
            }

            // Vérifier les rôles orphelins
            $orphanedUserRoles = DB::table('user_roles')
                ->leftJoin('users', 'user_roles.user_id', '=', 'users.id')
                ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
                ->where(function ($query) {
                    $query->whereNull('users.id')
                          ->orWhereNull('roles.id');
                })
                ->count();

            if ($orphanedUserRoles > 0) {
                Log::warning("Rôles utilisateur orphelins détectés", [
                    'count' => $orphanedUserRoles
                ]);

                // Nettoyer les rôles orphelins
                DB::table('user_roles')
                    ->leftJoin('users', 'user_roles.user_id', '=', 'users.id')
                    ->leftJoin('roles', 'user_roles.role_id', '=', 'roles.id')
                    ->where(function ($query) {
                        $query->whereNull('users.id')
                              ->orWhereNull('roles.id');
                    })
                    ->delete();
            }

            // Vérifier la cohérence des niveaux hiérarchiques
            $inconsistentLevels = DB::table('user_roles as ur1')
                ->join('user_roles as ur2', 'ur1.user_id', '=', 'ur2.user_id')
                ->join('roles as r1', 'ur1.role_id', '=', 'r1.id')
                ->join('roles as r2', 'ur2.role_id', '=', 'r2.id')
                ->where('ur1.actif', true)
                ->where('ur2.actif', true)
                ->whereRaw('r1.level > r2.level + 50') // Écart anormal
                ->count();

            if ($inconsistentLevels > 0) {
                Log::warning("Niveaux hiérarchiques incohérents détectés", [
                    'count' => $inconsistentLevels
                ]);
            }

            // Vérifier les permissions système manquantes
            $requiredPermissions = [
                'dashboard.access',
                'users.read',
                'users.create',
                'users.update',
                'users.delete',
                'roles.read',
                'roles.create',
                'roles.update',
                'roles.delete',
            ];

            foreach ($requiredPermissions as $permissionSlug) {
                if (!\App\Models\Permission::where('slug', $permissionSlug)->exists()) {
                    Log::error("Permission système manquante", [
                        'slug' => $permissionSlug
                    ]);
                }
            }

            // Vérifier les rôles système manquants
            $requiredRoles = [
                'super-admin',
                'admin',
                'membre',
            ];

            foreach ($requiredRoles as $roleSlug) {
                if (!\App\Models\Role::where('slug', $roleSlug)->exists()) {
                    Log::error("Rôle système manquant", [
                        'slug' => $roleSlug
                    ]);
                }
            }

            // Vérifier qu'il y a au moins un super admin
            $superAdminRole = \App\Models\Role::where('slug', 'super-admin')->first();
            if ($superAdminRole) {
                $superAdminCount = $superAdminRole->users()
                    ->wherePivot('actif', true)
                    ->count();

                if ($superAdminCount === 0) {
                    Log::critical("AUCUN SUPER ADMIN ACTIF DANS LE SYSTÈME!");
                }
            }

            Log::info("Vérification d'intégrité du système de permissions terminée");

        } catch (\Exception $e) {
            Log::error("Erreur lors de la vérification d'intégrité", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
