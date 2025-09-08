<?php

namespace App\Http\Controllers\private\Web;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Services\PermissionService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;

        $this->middleware('auth');
        $this->middleware('permission:roles.read')->only(['index', 'show']);
        $this->middleware('permission:roles.create')->only(['create', 'store']);
        $this->middleware('permission:roles.update')->only(['edit', 'update']);
        $this->middleware('permission:roles.delete')->only(['destroy']);
        $this->middleware('permission:roles.manage')->only(['managePermissions', 'syncPermissions']);
        $this->middleware('permission:roles.assign')->only(['assignToUser']);
        $this->middleware('permission:roles.remove')->only(['removeFromUser']);
    }

    /**
     * Afficher la liste des rôles
     */
    public function index(Request $request)
    {

        $query = Role::withCount(['users', 'permissions']);

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('is_system_role', $request->type === 'system');
        }

        // Filtre par niveau
        if ($request->filled('min_level')) {
            $query->where('level', '>=', $request->min_level);
        }

        if ($request->filled('max_level')) {
            $query->where('level', '<=', $request->max_level);
        }

        if ($request->expectsJson()) {
            $roles = $query->orderByRaw("LOWER(name) ASC")->get();
            return response()->json([
                'success' => true,
                'data' => $roles,

            ]);
        }

        // Tri
        $sortField = $request->get('sort', 'level');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);



        $roles = $query->paginate(15)->withQueryString();



        return view('components.private.roles.index', compact('roles'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $permissions = Permission::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('components.private.roles.create', compact('permissions'));
    }

    /**
     * Enregistrer un nouveau rôle
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
            'slug' => 'required|string|max:100|unique:roles,slug',
            'description' => 'nullable|string',
            'level' => 'required|integer|min:0|max:100',
            'is_system_role' => 'boolean',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $role = $this->permissionService->createRole([
                    'name' => $validated['name'],
                    'slug' => $validated['slug'],
                    'description' => $validated['description'] ?? null,
                    'level' => $validated['level'],
                    'is_system_role' => $validated['is_system_role'] ?? false,
                ]);

                if (!empty($validated['permissions'])) {
                    $this->permissionService->syncRolePermissions(
                        $role,
                        $validated['permissions'],
                        auth()->user()
                    );
                }
            });

            return redirect()
                ->route('private.roles.index')
                ->with('success', "Rôle créé avec succès!");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Afficher un rôle
     */
    public function show(Role $role)
    {
        $role->load(['permissions', 'users']);

        // Statistiques
        $stats = [
            'total_users' => $role->users()->wherePivot('actif', true)->count(),
            'total_permissions' => $role->permissions()->wherePivot('actif', true)->count(),
            'active_users' => $role->users()
                ->wherePivot('actif', true)
                ->where(function ($q) {
                    $q->whereNull('user_roles.expire_le')
                      ->orWhere('user_roles.expire_le', '>', now());
                })
                ->count(),
            'expiring_soon' => $role->users()
                ->wherePivot('actif', true)
                ->whereNotNull('user_roles.expire_le')
                ->whereBetween('user_roles.expire_le', [now(), now()->addDays(7)])
                ->count(),
        ];

        // Permissions groupées par catégorie
        $permissions = $role->permissions()
            ->wherePivot('actif', true)
            ->get()
            ->groupBy('category');

        // Utilisateurs récents
        $recentUsers = $role->users()
            ->wherePivot('actif', true)
            ->orderByPivot('attribue_le', 'desc')
            ->take(10)
            ->get();

        return view('components.private.roles.show', compact('role', 'stats', 'permissions', 'recentUsers'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Role $role)
    {
       /** @var User|null $user */
        $user = auth()->user();
        if ($role->is_system_role && !$user->isSuperAdmin()) {
            return back()->with('error', 'Les rôles système ne peuvent être modifiés que par le super admin.');
        }

        $permissions = Permission::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('components.private.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Mettre à jour un rôle
     */
    public function update(Request $request, Role $role)
    {
        /** @var User|null $user */
        $user = auth()->user();
        if ($role->is_system_role && !$user->isSuperAdmin()) {
            return back()->with('error', 'Les rôles système ne peuvent être modifiés que par le super admin.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $role->id,
            'slug' => 'required|string|max:100|unique:roles,slug,' . $role->id,
            'description' => 'nullable|string',
            'level' => 'required|integer|min:0|max:100',
        ]);

        try {
            $role->update($validated);

            $this->permissionService->clearCache();

            return redirect()
                ->route('private.roles.show', $role)
                ->with('success', "Rôle mis à jour avec succès!");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un rôle
     */
    public function destroy(Role $role)
    {
        if ($role->is_system_role) {
            return response()->json([
                'success' => false,
                'message' => 'Les rôles système ne peuvent pas être supprimés.'
            ], 403);
        }

        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Ce rôle est attribué à des utilisateurs et ne peut pas être supprimé.'
            ], 400);
        }

        try {
            DB::transaction(function () use ($role) {
                $role->permissions()->detach();
                $role->delete();
            });

            $this->permissionService->clearCache();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Rôle supprimé avec succès!"
                ]);
            }

            return redirect()
                ->route('private.roles.index')
                ->with('success', "Rôle supprimé avec succès!");
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Gérer les permissions d'un rôle
     */
    public function managePermissions(Request $request, Role $role)
    {
        $permissions = Permission::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $permissions,
                ]);
            }

        $rolePermissions = $role->permissions()
            ->withPivot('attribue_par', 'attribue_le', 'expire_le', 'actif')
            ->get();

        return view('components.private.roles.permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Synchroniser les permissions d'un rôle
     */
    public function syncPermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        try {
            $this->permissionService->syncRolePermissions(
                $role,
                $validated['permissions'] ?? [],
                auth()->user()
            );

            return response()->json([
                'success' => true,
                'message' => 'Permissions synchronisées avec succès!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Attribuer un rôle à un utilisateur
     */
    public function assignToUser(Request $request, Role $role)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'expires_at' => 'nullable|date|after:now',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $user = User::findOrFail($validated['user_id']);
            $expiresAt = isset($validated['expires_at'])
                ? \Carbon\Carbon::parse($validated['expires_at'])
                : null;

            $this->permissionService->assignRoleToUser(
                $user,
                $role,
                auth()->user(),
                $expiresAt
            );

            return response()->json([
                'success' => true,
                'message' => "Rôle attribué à {$user->nom_complet} avec succès!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retirer un rôle d'un utilisateur
     */
    public function removeFromUser(Request $request, Role $role)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $user = User::findOrFail($validated['user_id']);

            $recentUser = $role->users()
            ->wherePivot('actif', true)
            ->wherePivot('attribue_par', Auth::id())
            ->wherePivot('attribue_par', $user->id)
            ->first();


            if( $user->isSuperAdmin() &&  $recentUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous ne pouvez pas vous-même retirer ce rôle de super administrateur.'
                ], 403);
            }

            $this->permissionService->removeRoleFromUser($user, $role);

            return response()->json([
                'success' => true,
                'message' => "Rôle retiré de {$user->nom_complet} avec succès!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cloner un rôle
     */
    public function clone(Role $role)
    {
        try {
            $newRole = $role->replicate();
            $newRole->name = $role->name . ' (Copie)';
            $newRole->slug = $role->slug . '-copy-' . time();
            $newRole->is_system_role = false;
            $newRole->save();

            // Copier les permissions
            $this->permissionService->copyRolePermissions($role, $newRole, auth()->user());

            return redirect()
                ->route('private.roles.edit', $newRole)
                ->with('success', 'Rôle cloné avec succès! Veuillez modifier les informations.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du clonage : ' . $e->getMessage());
        }
    }

    /**
     * Comparer des rôles
     */
    public function compare(Request $request)
    {
        $validated = $request->validate([
            'role_ids' => 'required|array|min:2|max:5',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $roles = Role::with('permissions')
            ->whereIn('id', $validated['role_ids'])
            ->get();

        // Obtenir toutes les permissions uniques
        $allPermissions = collect();
        foreach ($roles as $role) {
            $allPermissions = $allPermissions->merge($role->permissions);
        }
        $allPermissions = $allPermissions->unique('id')->sortBy('category')->sortBy('name');

        // Créer la matrice de comparaison
        $comparison = [];
        foreach ($allPermissions as $permission) {
            $row = [
                'permission' => $permission,
                'roles' => []
            ];
            foreach ($roles as $role) {
                $row['roles'][$role->id] = $role->permissions->contains('id', $permission->id);
            }
            $comparison[] = $row;
        }

        return view('components.private.roles.compare', compact('roles', 'comparison'));
    }

    /**
     * Exporter les rôles
     */
    public function export()
    {
        Gate::authorize('export-data', 'roles');

        $roles = Role::with(['permissions', 'users'])->get();

        $csv = "ID,Nom,Slug,Description,Niveau,Système,Nb Utilisateurs,Nb Permissions\n";

        foreach ($roles as $role) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $role->id,
                $role->name,
                $role->slug,
                $role->description ?? '',
                $role->level,
                $role->is_system_role ? 'Oui' : 'Non',
                $role->users()->count(),
                $role->permissions()->count()
            );
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="roles_' . date('Y-m-d_H-i-s') . '.csv"');
    }

    /**
     * Afficher la hiérarchie des rôles
     */
    public function hierarchy()
    {
        $roles = Role::orderBy('level', 'desc')->get();

        // Grouper par tranches de niveau
        $hierarchy = [
            'Super Admin (100)' => $roles->where('level', 100),
            'Administration (80-99)' => $roles->whereBetween('level', [80, 99]),
            'Direction (60-79)' => $roles->whereBetween('level', [60, 79]),
            'Responsables (40-59)' => $roles->whereBetween('level', [40, 59]),
            'Membres actifs (20-39)' => $roles->whereBetween('level', [20, 39]),
            'Membres (10-19)' => $roles->whereBetween('level', [10, 19]),
            'Visiteurs (0-9)' => $roles->whereBetween('level', [0, 9]),
        ];

        return view('components.private.roles.hierarchy', compact('hierarchy'));
    }
}
