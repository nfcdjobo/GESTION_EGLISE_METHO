<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Services\PermissionService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;


class ManagePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:manage
                            {action : Action à effectuer (create-role|create-permission|assign-role|grant-permission|revoke-permission|list-roles|list-permissions|audit-user|cleanup|stats)}
                            {--user= : ID ou email de l\'utilisateur}
                            {--role= : Slug du rôle}
                            {--permission= : Slug de la permission}
                            {--name= : Nom du rôle ou de la permission}
                            {--slug= : Slug du rôle ou de la permission}
                            {--resource= : Ressource pour la permission}
                            {--action= : Action pour la permission}
                            {--level= : Niveau hiérarchique pour le rôle}
                            {--expires= : Date d\'expiration (format: Y-m-d H:i:s)}
                            {--reason= : Raison de l\'attribution/révocation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gérer les permissions et rôles du système';

    protected PermissionService $permissionService;

    /**
     * Create a new command instance.
     */
    public function __construct(PermissionService $permissionService)
    {
        parent::__construct();
        $this->permissionService = $permissionService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = $this->argument('action');

        try {
            return match ($action) {
                'create-role' => $this->createRole(),
                'create-permission' => $this->createPermission(),
                'assign-role' => $this->assignRole(),
                'grant-permission' => $this->grantPermission(),
                'revoke-permission' => $this->revokePermission(),
                'list-roles' => $this->listRoles(),
                'list-permissions' => $this->listPermissions(),
                'audit-user' => $this->auditUser(),
                'cleanup' => $this->cleanup(),
                'stats' => $this->showStats(),
                default => $this->invalidAction(),
            };
        } catch (\Exception $e) {
            $this->error('Erreur : ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Créer un nouveau rôle
     */
    protected function createRole(): int
    {
        $name = $this->option('name') ?? $this->ask('Nom du rôle');
        $slug = $this->option('slug') ?? Str::slug($name);
        $level = $this->option('level') ?? $this->ask('Niveau hiérarchique (0-100)', 10);

        $role = $this->permissionService->createRole([
            'name' => $name,
            'slug' => $slug,
            'level' => (int) $level,
            'description' => $this->ask('Description (optionnel)'),
        ]);

        $this->info("Rôle '{$role->name}' créé avec succès!");

        if ($this->confirm('Voulez-vous attribuer des permissions à ce rôle?')) {
            $this->assignPermissionsToRole($role);
        }

        return Command::SUCCESS;
    }

    /**
     * Créer une nouvelle permission
     */
    protected function createPermission(): int
    {
        $name = $this->option('name') ?? $this->ask('Nom de la permission');
        $slug = $this->option('slug') ?? Str::slug($name);
        $resource = $this->option('resource') ?? $this->ask('Ressource (ex: users, roles)');
        $action = $this->option('action') ?? $this->choice(
            'Action',
            ['create', 'read', 'update', 'delete', 'manage', 'export', 'import', 'validate', 'approve'],
            'read'
        );

        $permission = $this->permissionService->createPermission([
            'name' => $name,
            'slug' => $slug,
            'resource' => $resource,
            'action' => $action,
            'category' => $this->ask('Catégorie (optionnel)'),
            'description' => $this->ask('Description (optionnel)'),
        ]);

        $this->info("Permission '{$permission->name}' créée avec succès!");

        return Command::SUCCESS;
    }

    /**
     * Attribuer un rôle à un utilisateur
     */
    protected function assignRole(): int
    {
        $userIdentifier = $this->option('user') ?? $this->ask('Email ou ID de l\'utilisateur');
        $roleSlug = $this->option('role') ?? $this->ask('Slug du rôle');
        $expires = $this->option('expires');

        $user = $this->findUser($userIdentifier);
        $role = Role::where('slug', $roleSlug)->firstOrFail();

        $expiresAt = $expires ? \Carbon\Carbon::parse($expires) : null;

        $this->permissionService->assignRoleToUser($user, $role, auth()->user(), $expiresAt);

        $this->info("Rôle '{$role->name}' attribué à {$user->nom_complet} avec succès!");

        if ($expiresAt) {
            $this->info("Le rôle expirera le {$expiresAt->format('d/m/Y H:i')}");
        }

        return Command::SUCCESS;
    }

    /**
     * Accorder une permission à un utilisateur
     */
    protected function grantPermission(): int
    {
        $userIdentifier = $this->option('user') ?? $this->ask('Email ou ID de l\'utilisateur');
        $permissionSlug = $this->option('permission') ?? $this->ask('Slug de la permission');
        $expires = $this->option('expires');
        $reason = $this->option('reason');

        $user = $this->findUser($userIdentifier);
        $permission = Permission::where('slug', $permissionSlug)->firstOrFail();

        $expiresAt = $expires ? \Carbon\Carbon::parse($expires) : null;

        $this->permissionService->grantPermissionToUser(
            $user,
            $permission,
            auth()->user(),
            $expiresAt,
            $reason
        );

        $this->info("Permission '{$permission->name}' accordée à {$user->nom_complet} avec succès!");

        if ($expiresAt) {
            $this->info("La permission expirera le {$expiresAt->format('d/m/Y H:i')}");
        }

        return Command::SUCCESS;
    }

    /**
     * Révoquer une permission d'un utilisateur
     */
    protected function revokePermission(): int
    {
        $userIdentifier = $this->option('user') ?? $this->ask('Email ou ID de l\'utilisateur');
        $permissionSlug = $this->option('permission') ?? $this->ask('Slug de la permission');
        $reason = $this->option('reason') ?? $this->ask('Raison de la révocation (optionnel)');

        $user = $this->findUser($userIdentifier);
        $permission = Permission::where('slug', $permissionSlug)->firstOrFail();

        $this->permissionService->revokePermissionFromUser(
            $user,
            $permission,
            auth()->user(),
            $reason
        );

        $this->info("Permission '{$permission->name}' révoquée de {$user->nom_complet} avec succès!");

        return Command::SUCCESS;
    }

    /**
     * Lister tous les rôles
     */
    protected function listRoles(): int
    {
        $roles = Role::withCount('users', 'permissions')
            ->orderBy('level', 'desc')
            ->get();

        $this->table(
            ['ID', 'Nom', 'Slug', 'Niveau', 'Utilisateurs', 'Permissions', 'Système'],
            $roles->map(function ($role) {
                return [
                    $role->id,
                    $role->name,
                    $role->slug,
                    $role->level,
                    $role->users_count,
                    $role->permissions_count,
                    $role->is_system_role ? 'Oui' : 'Non',
                ];
            })
        );

        return Command::SUCCESS;
    }

    /**
     * Lister toutes les permissions
     */
    protected function listPermissions(): int
    {
        $permissions = Permission::orderBy('category')
            ->orderBy('resource')
            ->orderBy('action')
            ->get()
            ->groupBy('category');

        foreach ($permissions as $category => $perms) {
            $this->info("\n📁 Catégorie: " . ($category ?: 'Sans catégorie'));

            $this->table(
                ['Nom', 'Slug', 'Ressource', 'Action', 'Actif'],
                $perms->map(function ($permission) {
                    return [
                        $permission->name,
                        $permission->slug,
                        $permission->resource,
                        $permission->action,
                        $permission->is_active ? '✓' : '✗',
                    ];
                })
            );
        }

        return Command::SUCCESS;
    }

    /**
     * Auditer les permissions d'un utilisateur
     */
    protected function auditUser(): int
    {
        $userIdentifier = $this->option('user') ?? $this->ask('Email ou ID de l\'utilisateur');
        $user = $this->findUser($userIdentifier);

        $audit = $this->permissionService->auditUserPermissions($user);

        $this->info("\n👤 Utilisateur: {$audit['user']['name']} ({$audit['user']['email']})");

        // Rôles
        $this->info("\n📋 Rôles actifs:");
        if ($audit['roles']->isEmpty()) {
            $this->warn("  Aucun rôle");
        } else {
            foreach ($audit['roles'] as $role) {
                $this->line("  • {$role['name']} (Niveau: {$role['level']}, Permissions: {$role['permissions_count']})");
                if ($role['expires_at']) {
                    $this->warn("    ⏱️ Expire le: " . $role['expires_at']->format('d/m/Y H:i'));
                }
            }
        }

        // Permissions directes
        $this->info("\n🔑 Permissions directes:");
        if ($audit['direct_permissions']->isEmpty()) {
            $this->warn("  Aucune permission directe");
        } else {
            foreach ($audit['direct_permissions'] as $permission) {
                $this->line("  • {$permission['name']} ({$permission['resource']}.{$permission['action']})");
                if ($permission['expires_at']) {
                    $this->warn("    ⏱️ Expire le: " . $permission['expires_at']->format('d/m/Y H:i'));
                }
            }
        }

        // Statistiques
        $this->info("\n📊 Statistiques:");
        $this->line("  • Total de rôles: {$audit['statistics']['total_roles']}");
        $this->line("  • Permissions directes: {$audit['statistics']['total_direct_permissions']}");
        $this->line("  • Total de permissions: {$audit['statistics']['total_all_permissions']}");
        $this->line("  • Niveau hiérarchique max: " . ($audit['statistics']['highest_role_level'] ?? 'N/A'));

        return Command::SUCCESS;
    }

    /**
     * Nettoyer les permissions expirées
     */
    protected function cleanup(): int
    {
        $this->info('Nettoyage des permissions expirées...');

        $result = $this->permissionService->cleanupExpiredPermissions();

        $this->info("✓ Permissions directes expirées: {$result['direct_permissions']}");
        $this->info("✓ Rôles expirés: {$result['roles']}");
        $this->info("✓ Permissions de rôles expirées: {$result['role_permissions']}");

        $this->info("\nNettoyage terminé avec succès!");

        return Command::SUCCESS;
    }

    /**
     * Afficher les statistiques du système
     */
    protected function showStats(): int
    {
        $stats = $this->permissionService->getSystemStatistics();

        $this->info("\n📊 STATISTIQUES DU SYSTÈME DE PERMISSIONS");
        $this->info("==========================================");

        $this->info("\n🔑 Permissions:");
        $this->line("  • Total: {$stats['total_permissions']}");
        $this->line("  • Actives: {$stats['active_permissions']}");

        $this->info("\n📋 Rôles:");
        $this->line("  • Total: {$stats['total_roles']}");
        $this->line("  • Système: {$stats['system_roles']}");

        $this->info("\n👥 Utilisateurs:");
        $this->line("  • Avec rôles: {$stats['users_with_roles']}");
        $this->line("  • Avec permissions directes: {$stats['users_with_direct_permissions']}");

        $this->info("\n⏱️ Expirations:");
        $this->line("  • Permissions expirées: {$stats['expired_permissions']}");
        $this->line("  • Expirent bientôt (7 jours):");
        $this->line("    - Permissions: {$stats['expiring_soon']['permissions']}");
        $this->line("    - Rôles: {$stats['expiring_soon']['roles']}");

        return Command::SUCCESS;
    }

    /**
     * Action invalide
     */
    protected function invalidAction(): int
    {
        $this->error("Action invalide!");
        $this->info("Actions disponibles:");
        $this->line("  • create-role       : Créer un nouveau rôle");
        $this->line("  • create-permission : Créer une nouvelle permission");
        $this->line("  • assign-role       : Attribuer un rôle à un utilisateur");
        $this->line("  • grant-permission  : Accorder une permission à un utilisateur");
        $this->line("  • revoke-permission : Révoquer une permission d'un utilisateur");
        $this->line("  • list-roles        : Lister tous les rôles");
        $this->line("  • list-permissions  : Lister toutes les permissions");
        $this->line("  • audit-user        : Auditer les permissions d'un utilisateur");
        $this->line("  • cleanup           : Nettoyer les permissions expirées");
        $this->line("  • stats             : Afficher les statistiques du système");

        return Command::FAILURE;
    }

    /**
     * Trouver un utilisateur par email ou ID
     */
    protected function findUser(string $identifier): User
    {
        if (is_numeric($identifier)) {
            return User::findOrFail($identifier);
        }

        return User::where('email', $identifier)->firstOrFail();
    }

    /**
     * Attribuer des permissions à un rôle
     */
    protected function assignPermissionsToRole(Role $role): void
    {
        $permissions = Permission::orderBy('category')->orderBy('name')->pluck('name', 'id');

        $selected = $this->choice(
            'Sélectionnez les permissions (séparées par des virgules)',
            $permissions->toArray(),
            null,
            null,
            true
        );

        $selectedIds = array_keys(array_intersect($permissions->toArray(), $selected));

        $this->permissionService->syncRolePermissions($role, $selectedIds);

        $this->info(count($selectedIds) . " permissions attribuées au rôle!");
    }
}
