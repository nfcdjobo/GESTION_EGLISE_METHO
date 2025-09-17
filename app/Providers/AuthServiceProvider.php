<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Ajouter ici les mappings Model => Policy
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Définir les Gates dynamiquement basés sur les permissions
        $this->registerDynamicGates();

        // Super Admin bypass - peut tout faire
        Gate::before(function ($user, $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

        // Gates personnalisés
        $this->registerCustomGates();
    }

    /**
     * Enregistrer les Gates dynamiques basés sur les permissions
     */
    protected function registerDynamicGates(): void
    {
        try {
            // Charger toutes les permissions actives
            $permissions = Permission::where('is_active', true)->get();

            foreach ($permissions as $permission) {
                Gate::define($permission->slug, function (User $user) use ($permission) {
                    return $user->hasPermission($permission->slug);
                });

                // Gate pour resource.action (ex: users.create)
                if ($permission->resource && $permission->action) {
                    $gateName = $permission->resource . '.' . $permission->action;
                    Gate::define($gateName, function (User $user) use ($permission) {
                        return $user->hasPermission($permission->slug);
                    });
                }
            }
        } catch (\Exception $e) {
            // Si la table n'existe pas encore (migrations)
            // Log::error('Unable to register dynamic gates: ' . $e->getMessage());
        }
    }

    /**
     * Enregistrer les Gates personnalisés
     */
    protected function registerCustomGates(): void
    {
        // Gate pour vérifier si un membres peut gérer un autre membres
        Gate::define('manage-user', function (User $authUser, User $targetUser) {
            // Ne peut pas se gérer soi-même sauf super admin
            if ($authUser->id === $targetUser->id && !$authUser->isSuperAdmin()) {
                return false;
            }

            return $authUser->canManageUser($targetUser);
        });

        // Gate pour vérifier l'accès au tableau de bord
        Gate::define('access-dashboard', function (User $user) {
            return $user->hasPermission('dashboard.access') || $user->isAdmin();
        });

        // Gate pour vérifier l'accès à l'administration
        Gate::define('access-admin', function (User $user) {
            return $user->isAdmin();
        });

        // Gate pour gérer les permissions
        Gate::define('manage-permissions', function (User $user) {
            return $user->hasAnyPermission(['roles.manage', 'users.manage']) || $user->isAdmin();
        });

        // Gate pour les opérations financières
        Gate::define('manage-finances', function (User $user) {
            return $user->hasAnyPermission([
                'transactions.create',
                'transactions.update',
                'transactions.validate',
                'transactions.approve'
            ]) || $user->hasRole('tresorier');
        });

        // Gate pour la gestion des classes
        Gate::define('manage-class', function (User $user, $classeId = null) {
            // Admin et pasteur peuvent gérer toutes les classes
            if ($user->hasAnyRole(['admin', 'pasteur'])) {
                return true;
            }

            // Responsable de classe peut gérer sa classe
            if ($classeId && $user->hasRole('responsable-classe')) {
                return $user->classesResponsables()->where('id', $classeId)->exists();
            }

            return $user->hasPermission('classes.manage');
        });

        // Gate pour créer des annonces
        Gate::define('create-announcement', function (User $user) {
            return $user->hasPermission('annonces.create') ||
                   $user->hasAnyRole(['pasteur', 'secretaire', 'admin']);
        });

        // Gate pour approuver des annonces
        Gate::define('approve-announcement', function (User $user) {
            return $user->hasPermission('annonces.approve') ||
                   $user->hasAnyRole(['pasteur', 'admin']);
        });

        // Gate pour exporter des données
        Gate::define('export-data', function (User $user, string $resource) {
            return $user->hasPermission($resource . '.export') || $user->isAdmin();
        });

        // Gate pour importer des données
        Gate::define('import-data', function (User $user, string $resource) {
            return $user->hasPermission($resource . '.import') || $user->isAdmin();
        });

        // Gate pour voir les rapports
        Gate::define('view-reports', function (User $user) {
            return $user->hasPermission('reports.read') ||
                   $user->hasAnyRole(['pasteur', 'secretaire', 'tresorier', 'admin']);
        });

        // Gate pour les sauvegardes
        Gate::define('manage-backups', function (User $user) {
            return $user->hasAnyPermission(['backup.create', 'backup.restore']) ||
                   $user->isSuperAdmin();
        });

        // Gate pour voir les logs
        Gate::define('view-logs', function (User $user) {
            return $user->hasPermission('logs.read') || $user->isAdmin();
        });

        // Gate pour gérer les paramètres
        Gate::define('manage-settings', function (User $user) {
            return $user->hasPermission('settings.manage') || $user->isAdmin();
        });

        // Gate pour valider les membres
        Gate::define('validate-members', function (User $user) {
            return $user->hasPermission('users.validate') ||
                   $user->hasAnyRole(['pasteur', 'admin']);
        });

        // Gate pour archiver/restaurer
        Gate::define('archive-data', function (User $user, string $resource) {
            return $user->hasAnyPermission([
                $resource . '.archive',
                $resource . '.restore'
            ]) || $user->isAdmin();
        });

        // Gate pour accéder aux informations sensibles
        Gate::define('view-sensitive-info', function (User $user) {
            return $user->hasAnyRole(['pasteur', 'admin', 'secretaire']) ||
                   $user->isSuperAdmin();
        });

        // Gate pour gérer les réunions
        Gate::define('manage-meetings', function (User $user) {
            return $user->hasPermission('reunions.manage') ||
                   $user->hasAnyRole(['pasteur', 'secretaire', 'admin']);
        });

        // Gate pour convoquer aux réunions
        Gate::define('convoke-meetings', function (User $user) {
            return $user->hasPermission('reunions.convoke') ||
                   $user->hasRole('secretaire');
        });

        // Gate pour valider les cultes
        Gate::define('validate-worship', function (User $user) {
            return $user->hasPermission('cultes.validate') ||
                   $user->hasRole('pasteur');
        });

        // Gate pour gérer les programmes
        Gate::define('manage-programs', function (User $user) {
            return $user->hasPermission('programmes.manage') ||
                   $user->hasAnyRole(['pasteur', 'admin']);
        });

        // Gate générique pour vérifier une permission sur une ressource
        Gate::define('resource-permission', function (User $user, string $resource, string $action) {
            return $user->hasResourcePermission($resource, $action);
        });

        // Gate pour vérifier plusieurs permissions
        Gate::define('any-permission', function (User $user, array $permissions) {
            return $user->hasAnyPermission($permissions);
        });

        // Gate pour vérifier toutes les permissions
        Gate::define('all-permissions', function (User $user, array $permissions) {
            return $user->hasAllPermissions($permissions);
        });

        // Gate pour vérifier un rôle
        Gate::define('has-role', function (User $user, $role) {
            return $user->hasRole($role);
        });

        // Gate pour vérifier plusieurs rôles
        Gate::define('any-role', function (User $user, array $roles) {
            return $user->hasAnyRole($roles);
        });
    }
}
