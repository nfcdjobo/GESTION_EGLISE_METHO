<?php

namespace App\Http\Controllers\Private;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Services\PermissionService;
use App\Notifications\PermissionGranted;
use App\Notifications\PermissionRevoked;
use App\Notifications\RoleAssigned;
use App\Notifications\RoleRemoved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UserPermissionController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;

        $this->middleware('auth');
        $this->middleware('permission:users.manage');
    }

    /**
     * Afficher les permissions d'un utilisateur
     */
    public function index(User $user)
    {
        Gate::authorize('manage-user', $user);

        // Charger toutes les relations nécessaires
        $user->load(['roles.permissions', 'permissions']);

        // Permissions directes
        $directPermissions = $user->permissions()
            ->wherePivot('is_granted', true)
            ->withPivot('granted_by', 'granted_at', 'expires_at', 'reason')
            ->get();

        // Rôles actifs
        $activeRoles = $user->roles()
            ->wherePivot('actif', true)
            ->withPivot('attribue_par', 'attribue_le', 'expire_le')
            ->get();

        // Toutes les permissions (directes + via rôles)
        $allPermissions = $user->getAllPermissions();

        // Permissions groupées par catégorie
        $permissionsByCategory = $allPermissions->groupBy('category');

        // Permissions expirant bientôt
        $expiringPermissions = $user->getExpiringPermissions(7);
        $expiringRoles = $user->getExpiringRoles(7);

        // Statistiques
        $stats = [
            'total_permissions' => $allPermissions->count(),
            'direct_permissions' => $directPermissions->count(),
            'permissions_via_roles' => $allPermissions->count() - $directPermissions->count(),
            'active_roles' => $activeRoles->count(),
            'expiring_soon' => $expiringPermissions->count() + $expiringRoles->count(),
        ];

        return view('admin.users.permissions.index', compact(
            'user',
            'directPermissions',
            'activeRoles',
            'permissionsByCategory',
            'expiringPermissions',
            'expiringRoles',
            'stats'
        ));
    }

    /**
     * Accorder une permission à un utilisateur
     */
    public function grant(Request $request, User $user)
    {
        Gate::authorize('manage-user', $user);

        $validated = $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'expires_at' => 'nullable|date|after:now',
            'reason' => 'nullable|string|max:500',
            'notify_user' => 'boolean',
        ]);

        try {
            $permission = Permission::findOrFail($validated['permission_id']);
            $expiresAt = isset($validated['expires_at'])
                ? \Carbon\Carbon::parse($validated['expires_at'])
                : null;

            $this->permissionService->grantPermissionToUser(
                $user,
                $permission,
                auth()->user(),
                $expiresAt,
                $validated['reason'] ?? null
            );

            // Envoyer une notification si demandé
            if ($validated['notify_user'] ?? false) {
                $user->notify(new PermissionGranted(
                    $permission,
                    auth()->user(),
                    $expiresAt,
                    $validated['reason'] ?? null
                ));
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Permission '{$permission->name}' accordée à {$user->nom_complet}",
                ]);
            }

            return back()->with('success', "Permission accordée avec succès!");
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur : ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Révoquer une permission d'un utilisateur
     */
    public function revoke(Request $request, User $user)
    {
        Gate::authorize('manage-user', $user);

        $validated = $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'reason' => 'nullable|string|max:500',
            'notify_user' => 'boolean',
        ]);

        try {
            $permission = Permission::findOrFail($validated['permission_id']);

            $this->permissionService->revokePermissionFromUser(
                $user,
                $permission,
                auth()->user(),
                $validated['reason'] ?? null
            );

            // Envoyer une notification si demandé
            if ($validated['notify_user'] ?? false) {
                $user->notify(new PermissionRevoked(
                    $permission,
                    auth()->user(),
                    $validated['reason'] ?? null
                ));
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Permission '{$permission->name}' révoquée de {$user->nom_complet}",
                ]);
            }

            return back()->with('success', "Permission révoquée avec succès!");
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur : ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Synchroniser les permissions d'un utilisateur
     */
    public function sync(Request $request, User $user)
    {
        Gate::authorize('manage-user', $user);

        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($user, $validated) {
                // Révoquer toutes les permissions existantes
                $existingPermissions = $user->permissions()->pluck('permissions.id')->toArray();
                foreach ($existingPermissions as $permissionId) {
                    $this->permissionService->revokePermissionFromUser(
                        $user,
                        Permission::find($permissionId),
                        auth()->user(),
                        'Synchronisation des permissions'
                    );
                }

                // Accorder les nouvelles permissions
                if (!empty($validated['permissions'])) {
                    foreach ($validated['permissions'] as $permissionId) {
                        $this->permissionService->grantPermissionToUser(
                            $user,
                            Permission::find($permissionId),
                            auth()->user(),
                            null,
                            $validated['reason'] ?? 'Synchronisation des permissions'
                        );
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Permissions synchronisées avec succès!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Afficher l'audit des permissions d'un utilisateur
     */
    public function audit(User $user)
    {
        Gate::authorize('manage-user', $user);

        $audit = $this->permissionService->auditUserPermissions($user);

        // Historique des changements
        $history = DB::table('user_permissions')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        $roleHistory = DB::table('user_roles')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return view('admin.users.permissions.audit', compact('user', 'audit', 'history', 'roleHistory'));
    }

    /**
     * Afficher les permissions expirant bientôt
     */
    public function expiring(User $user)
    {
        Gate::authorize('manage-user', $user);

        $expiringPermissions = $user->getExpiringPermissions(30);
        $expiringRoles = $user->getExpiringRoles(30);

        return view('admin.users.permissions.expiring', compact(
            'user',
            'expiringPermissions',
            'expiringRoles'
        ));
    }

    /**
     * Afficher les rôles d'un utilisateur
     */
    public function roles(User $user)
    {
        Gate::authorize('manage-user', $user);

        $userRoles = $user->roles()
            ->withPivot('attribue_par', 'attribue_le', 'expire_le', 'actif')
            ->get();

        $availableRoles = Role::whereNotIn('id', $userRoles->pluck('id'))
            ->orderBy('level', 'desc')
            ->get();

        return view('admin.users.roles.index', compact('user', 'userRoles', 'availableRoles'));
    }

    /**
     * Attribuer un rôle à un utilisateur
     */
    public function assignRole(Request $request, User $user)
    {
        Gate::authorize('manage-user', $user);

        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'expires_at' => 'nullable|date|after:now',
            'notify_user' => 'boolean',
        ]);

        try {
            $role = Role::findOrFail($validated['role_id']);
            $expiresAt = isset($validated['expires_at'])
                ? \Carbon\Carbon::parse($validated['expires_at'])
                : null;

            // Vérifier que l'utilisateur actuel peut attribuer ce rôle
            /** @var User|null $user */
            $user = auth()->user();
            if (!$user->isSuperAdmin()) {
                $authUserLevel = $user->getHighestRoleLevel();
                if ($authUserLevel === null || $role->level >= $authUserLevel) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vous ne pouvez pas attribuer un rôle de niveau égal ou supérieur au vôtre.',
                    ], 403);
                }
            }

            $this->permissionService->assignRoleToUser(
                $user,
                $role,
                auth()->user(),
                $expiresAt
            );

            // Envoyer une notification si demandé
            if ($validated['notify_user'] ?? false) {
                $user->notify(new RoleAssigned(
                    $role,
                    auth()->user(),
                    $expiresAt
                ));
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Rôle '{$role->name}' attribué à {$user->nom_complet}",
                ]);
            }

            return back()->with('success', "Rôle attribué avec succès!");
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur : ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Retirer un rôle d'un utilisateur
     */
    public function removeRole(Request $request, User $user)
    {
        Gate::authorize('manage-user', $user);

        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'notify_user' => 'boolean',
        ]);

        try {
            $role = Role::findOrFail($validated['role_id']);

            $this->permissionService->removeRoleFromUser($user, $role);

            // Envoyer une notification si demandé
            if ($validated['notify_user'] ?? false) {
                $user->notify(new RoleRemoved(
                    $role,
                    auth()->user()
                ));
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Rôle '{$role->name}' retiré de {$user->nom_complet}",
                ]);
            }

            return back()->with('success', "Rôle retiré avec succès!");
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur : ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Synchroniser les rôles d'un utilisateur
     */
    public function syncRoles(Request $request, User $user)
    {
        Gate::authorize('manage-user', $user);

        $validated = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        try {
            $user->syncRoles($validated['roles'] ?? [], auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Rôles synchronisés avec succès!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Voir mes permissions (utilisateur connecté)
     */
    public function myPermissions()
    {
        /** @var User|null $user */
        $user = auth()->user();

        // Permissions directes
        $directPermissions = $user->permissions()
            ->wherePivot('is_granted', true)
            ->withPivot('granted_at', 'expires_at', 'reason')
            ->get();

        // Rôles actifs
        $activeRoles = $user->roles()
            ->wherePivot('actif', true)
            ->withPivot('attribue_le', 'expire_le')
            ->with('permissions')
            ->get();

        // Toutes les permissions
        $allPermissions = $user->getAllPermissions()->groupBy('category');

        // Permissions expirant bientôt
        $expiringPermissions = $user->getExpiringPermissions(7);
        $expiringRoles = $user->getExpiringRoles(7);

        return view('profile.permissions', compact(
            'directPermissions',
            'activeRoles',
            'allPermissions',
            'expiringPermissions',
            'expiringRoles'
        ));
    }

    /**
     * Voir mes rôles (utilisateur connecté)
     */
    public function myRoles()
    {
        /** @var User|null $user */
        $user = auth()->user();

        $roles = $user->roles()
            ->withPivot('attribue_le', 'expire_le', 'actif')
            ->with('permissions')
            ->get();

        return view('profile.roles', compact('roles'));
    }

    /**
     * Voir mes permissions expirant bientôt
     */
    public function myExpiringPermissions()
    {
        /** @var User|null $user */
        $user = auth()->user();

        $expiringPermissions = $user->getExpiringPermissions(30);
        $expiringRoles = $user->getExpiringRoles(30);

        return view('profile.expiring', compact('expiringPermissions', 'expiringRoles'));
    }
}
