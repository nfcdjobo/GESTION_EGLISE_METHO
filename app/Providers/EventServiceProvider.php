<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\UserPermission;
use App\Models\UserRole;
use App\Models\RolePermission;
use App\Observers\PermissionObserver;
use App\Observers\RoleObserver;
use App\Observers\UserPermissionObserver;
use App\Observers\UserRoleObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        // Événements de permissions
        'App\Events\PermissionGranted' => [
            'App\Listeners\LogPermissionGrant',
            'App\Listeners\NotifyUserOfPermissionGrant',
            'App\Listeners\RefreshUserPermissionCache',
        ],

        'App\Events\PermissionRevoked' => [
            'App\Listeners\LogPermissionRevoke',
            'App\Listeners\NotifyUserOfPermissionRevoke',
            'App\Listeners\RefreshUserPermissionCache',
        ],

        'App\Events\RoleAssigned' => [
            'App\Listeners\LogRoleAssignment',
            'App\Listeners\NotifyUserOfRoleAssignment',
            'App\Listeners\RefreshUserPermissionCache',
        ],

        'App\Events\RoleRemoved' => [
            'App\Listeners\LogRoleRemoval',
            'App\Listeners\NotifyUserOfRoleRemoval',
            'App\Listeners\RefreshUserPermissionCache',
        ],

        'App\Events\PermissionExpiring' => [
            'App\Listeners\NotifyUserOfExpiringPermission',
            'App\Listeners\LogExpiringPermission',
        ],

        'App\Events\PermissionExpired' => [
            'App\Listeners\HandleExpiredPermission',
            'App\Listeners\NotifyUserOfExpiredPermission',
            'App\Listeners\RefreshUserPermissionCache',
        ],
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Permission::class => [PermissionObserver::class],
        Role::class => [RoleObserver::class],
        UserPermission::class => [UserPermissionObserver::class],
        UserRole::class => [UserRoleObserver::class],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();

        // Enregistrer les observateurs
        Permission::observe(PermissionObserver::class);
        Role::observe(RoleObserver::class);
        UserPermission::observe(UserPermissionObserver::class);
        UserRole::observe(UserRoleObserver::class);

        // Événements personnalisés supplémentaires
        $this->registerCustomEvents();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }

    /**
     * Enregistrer des événements personnalisés
     */
    protected function registerCustomEvents(): void
    {
        // Écouter les changements de permissions via Gate
        Event::listen('gate.allows', function ($gate, $result, $arguments) {
            if ($result && auth()->check()) {
                // Mettre à jour last_used_at de la permission
                $permission = Permission::where('slug', $gate)->first();
                if ($permission) {
                    $permission->updateLastUsed();
                }
            }
        });

        // Écouter les connexions pour vérifier les permissions expirées
        Event::listen('auth.login', function ($user) {
            // Vérifier et nettoyer les permissions expirées
            $expiredPermissions = $user->permissions()
                ->whereNotNull('expires_at')
                ->where('expires_at', '<', now())
                ->where('is_granted', true)
                ->get();

            foreach ($expiredPermissions as $permission) {
                $permission->pivot->update(['is_expired' => true]);
            }

            // Vérifier les rôles expirés
            $expiredRoles = $user->roles()
                ->whereNotNull('expire_le')
                ->where('expire_le', '<', now())
                ->where('actif', true)
                ->get();

            foreach ($expiredRoles as $role) {
                $role->pivot->update(['actif' => false]);
            }

            // Rafraîchir le cache
            $user->clearPermissionsCache();
        });

        // Écouter les tentatives d'accès non autorisées
        Event::listen('authorization.failed', function ($user, $ability) {
            Log::warning('Tentative d\'accès non autorisée', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'ability' => $ability,
                'ip' => request()->ip(),
                'url' => request()->fullUrl(),
            ]);

            // Créer un log d'audit
            \App\Models\PermissionAuditLog::create([
                'action' => 'access_denied',
                'model_type' => 'Authorization',
                'model_id' => $ability,
                'user_id' => $user->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'context' => [
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                ],
            ]);
        });

        // Écouter les changements de rôles super admin
        Event::listen('role.superadmin.assigned', function ($user) {
            // Notification spéciale pour l'attribution du rôle super admin
            Log::critical('Rôle Super Admin attribué', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'assigned_by' => auth()->id(),
                'ip' => request()->ip(),
            ]);

            // Envoyer une notification aux autres super admins
            $superAdmins = \App\Models\User::whereHas('roles', function ($query) {
                $query->where('slug', 'super-admin');
            })->where('id', '!=', $user->id)->get();

            foreach ($superAdmins as $admin) {
                // Notification::send($admin, new SuperAdminRoleAssigned($user));
            }
        });
    }
}
















