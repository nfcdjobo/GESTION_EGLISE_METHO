<?php

namespace App\Http\Controllers\Private\Web;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;

        $this->middleware('auth');
        $this->middleware('permission:permissions.read')->only(['index', 'show', 'statistics']);
        $this->middleware('permission:permissions.create')->only(['create', 'store']);
        $this->middleware('permission:permissions.update')->only(['edit', 'update', 'toggle']);
        $this->middleware('permission:permissions.delete')->only(['destroy']);
        $this->middleware('permission:permissions.manage')->only(['bulkAssign', 'bulkActions', 'clone']);
        $this->middleware('permission:permissions.export')->only(['export']);
    }

    /**
     * Afficher la liste des permissions
     */
    public function index(Request $request)
    {
        try {
            $query = Permission::query();

            // Filtres
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('resource', 'like', "%{$search}%");
                });
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            if ($request->filled('resource')) {
                $query->where('resource', $request->resource);
            }

            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active === 'true' || $request->is_active === true);
            }

            if ($request->filled('is_system')) {
                $query->where('is_system', $request->is_system === 'true' || $request->is_system === true);
            }

            // Tri
            $sortField = $request->get('sort', 'category');
            $sortDirection = $request->get('direction', 'asc');

            $allowedSorts = ['name', 'slug', 'category', 'resource', 'action', 'is_active', 'is_system', 'priority', 'created_at', 'updated_at'];
            if (in_array($sortField, $allowedSorts)) {
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy('category', 'asc')->orderBy('resource', 'asc');
            }

            // Pagination
            $perPage = $request->get('per_page', 20);
            $permissions = $query->with(['createur:id,nom,prenom', 'modificateur:id,nom,prenom'])
                                ->paginate($perPage)
                                ->withQueryString();

            // Ajouter les statistiques pour chaque permission si demandé
            if ($request->boolean('with_stats')) {
                $permissions->through(function ($permission) {
                    $permission->total_roles = $permission->roles()->count();
                    $permission->total_users = $permission->users()->count();
                    return $permission;
                });
            }

            // Données pour les filtres
            $filterData = [
                'categories' => Permission::distinct()->whereNotNull('category')->pluck('category')->filter()->sort()->values(),
                'resources' => Permission::distinct()->whereNotNull('resource')->pluck('resource')->filter()->sort()->values(),
                'actions' => ['create', 'read', 'update', 'delete', 'manage', 'export', 'import', 'validate', 'approve', 'reject', 'archive', 'restore', 'download']
            ];

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'permissions' => $permissions,
                        'filters' => $filterData
                    ],
                    'message' => 'Permissions récupérées avec succès'
                ]);
            }

            return view('components.private.permissions.index', array_merge(compact('permissions'), $filterData));

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des permissions',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des permissions']);
        }
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        try {
            $filterData = [
                'categories' => Permission::distinct()->whereNotNull('category')->pluck('category')->filter()->sort()->values(),
                'actions' => ['create', 'read', 'update', 'delete', 'manage', 'export', 'import', 'validate', 'approve', 'reject', 'archive', 'restore', 'download']
            ];

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $filterData,
                    'message' => 'Données pour création récupérées avec succès'
                ]);
            }

            return view('components.private.permissions.create', $filterData);

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des données',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des données']);
        }
    }

    /**
     * Enregistrer une nouvelle permission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:permissions,slug',
            'description' => 'nullable|string',
            'resource' => 'nullable|string|max:100',
            'action' => 'required|in:create,read,update,delete,export,import,validate,approve,reject,archive,restore,manage,download',
            'category' => 'nullable|string|max:100',
            'priority' => 'nullable|integer|min:0|max:255',
            'is_active' => 'boolean',
            'is_system' => 'boolean',
            'conditions' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();

            $permissionData = array_merge($validated, [
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'guard_name' => 'web'
            ]);

            $permission = Permission::create($permissionData);

            DB::commit();

            // Charger les relations pour la réponse
            $permission->load(['createur', 'modificateur']);

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $permission,
                    'message' => "Permission '{$permission->name}' créée avec succès"
                ], 201);
            }

            return redirect()
                ->route('private.permissions.show', $permission)
                ->with('success', "Permission '{$permission->name}' créée avec succès!");

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la permission',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    /**
     * Afficher une permission
     */
    public function show(Request $request, Permission $permission)
    {
        try {
            $permission->load(['roles', 'users', 'createur', 'modificateur']);

            // Statistiques
            $stats = [
                'total_roles' => $permission->roles()->count(),
                'total_users_direct' => $permission->users()->wherePivot('is_granted', true)->count(),
                'total_users_via_roles' => User::whereHas('roles.permissions', function ($q) use ($permission) {
                    $q->where('permissions.id', $permission->id);
                })->count(),
                'last_used' => $permission->last_used_at,
                'created_at' => $permission->created_at,
                'updated_at' => $permission->updated_at
            ];

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'permission' => $permission,
                        'statistics' => $stats
                    ],
                    'message' => 'Permission récupérée avec succès'
                ]);
            }

            return view('components.private.permissions.show', compact('permission', 'stats'));

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération de la permission',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération de la permission']);
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Request $request, Permission $permission)
    {
        try {
            /**
             * @var User $user
             */
            $user = auth()->user();
            if ($permission->is_system && !$user->isSuperAdmin()) {
                $message = 'Les permissions système ne peuvent être modifiées que par le super admin.';

                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 403);
                }

                return back()->with('error', $message);
            }

            $filterData = [
                'categories' => Permission::distinct()->whereNotNull('category')->pluck('category')->filter()->sort()->values(),
                'actions' => ['create', 'read', 'update', 'delete', 'manage', 'export', 'import', 'validate', 'approve', 'reject', 'archive', 'restore', 'download']
            ];

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => array_merge([
                        'permission' => $permission
                    ], $filterData),
                    'message' => 'Données pour édition récupérées avec succès'
                ]);
            }

            return view('components.private.permissions.edit', array_merge(compact('permission'), $filterData));

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des données',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des données']);
        }
    }

    /**
     * Mettre à jour une permission
     */
    public function update(Request $request, Permission $permission)
    {
        /**
         * @var User $user
         */
        $user = auth()->user();
        if ($permission->is_system && !$user->isSuperAdmin()) {
            $message = 'Les permissions système ne peuvent être modifiées que par le super admin.';

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 403);
            }

            return back()->with('error', $message);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => [
                'required',
                'string',
                'max:100',
                Rule::unique('permissions', 'slug')->ignore($permission->id)
            ],
            'description' => 'nullable|string',
            'resource' => 'nullable|string|max:100',
            'action' => 'required|in:create,read,update,delete,export,import,validate,approve,reject,archive,restore,manage,download',
            'category' => 'nullable|string|max:100',
            'priority' => 'nullable|integer|min:0|max:255',
            'is_active' => 'boolean',
            'conditions' => 'nullable|array'
        ]);

        try {
            DB::beginTransaction();

            $permission->update(array_merge($validated, [
                'updated_by' => auth()->id(),
            ]));

            $this->permissionService->clearCache();

            DB::commit();

            // Charger les relations pour la réponse
            $permission->load(['createur', 'modificateur']);

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $permission,
                    'message' => "Permission '{$permission->name}' mise à jour avec succès"
                ]);
            }

            return redirect()
                ->route('private.permissions.show', $permission)
                ->with('success', "Permission '{$permission->name}' mise à jour avec succès!");

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de la permission',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une permission
     */
    public function destroy(Request $request, Permission $permission)
    {
        if ($permission->is_system) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Les permissions système ne peuvent pas être supprimées.'
                ], 403);
            }

            return back()->with('error', 'Les permissions système ne peuvent pas être supprimées.');
        }

        try {
            DB::beginTransaction();

            // Supprimer les associations
            $permission->roles()->detach();
            $permission->users()->detach();

            // Supprimer la permission
            $permission->delete();

            $this->permissionService->clearCache();

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => "Permission supprimée avec succès"
                ]);
            }

            return redirect()
                ->route('private.permissions.index')
                ->with('success', "Permission supprimée avec succès!");

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la permission',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    /**
     * Attribuer des permissions en masse
     */
    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
            'role_ids' => 'nullable|array',
            'role_ids.*' => 'exists:roles,id',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'expires_at' => 'nullable|date|after:now',
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $expiresAt = isset($validated['expires_at'])
                ? \Carbon\Carbon::parse($validated['expires_at'])
                : null;

            $assignedCount = 0;

            // Attribuer aux rôles
            if (!empty($validated['role_ids'])) {
                foreach ($validated['role_ids'] as $roleId) {
                    $role = Role::find($roleId);
                    foreach ($validated['permission_ids'] as $permissionId) {
                        $role->permissions()->syncWithoutDetaching([
                            $permissionId => [
                                'attribue_par' => auth()->id(),
                                'attribue_le' => now(),
                                'expire_le' => $expiresAt,
                                'actif' => true,
                            ]
                        ]);
                        $assignedCount++;
                    }
                }
            }

            // Attribuer aux utilisateurs
            if (!empty($validated['user_ids'])) {
                foreach ($validated['user_ids'] as $userId) {
                    $user = User::find($userId);
                    foreach ($validated['permission_ids'] as $permissionId) {
                        $permission = Permission::find($permissionId);
                        $this->permissionService->grantPermissionToUser(
                            $user,
                            $permission,
                            auth()->user(),
                            $expiresAt,
                            $validated['reason'] ?? null
                        );
                        $assignedCount++;
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$assignedCount} attribution(s) effectuée(s) avec succès",
                'assigned_count' => $assignedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'attribution des permissions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actions en lot sur les permissions
     */
    public function bulkActions(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id'
        ]);

        try {
            DB::beginTransaction();

            $permissions = Permission::whereIn('id', $validated['permission_ids']);
            $count = $permissions->count();

            // Vérifier les permissions système pour la suppression
            if ($validated['action'] === 'delete') {
                $systemPermissions = $permissions->where('is_system', true)->count();
                if ($systemPermissions > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de supprimer des permissions système'
                    ], 400);
                }
            }

            switch ($validated['action']) {
                case 'activate':
                    $permissions->update(['is_active' => true, 'updated_by' => auth()->id()]);
                    $message = "{$count} permission(s) activée(s) avec succès";
                    break;

                case 'deactivate':
                    $permissions->update(['is_active' => false, 'updated_by' => auth()->id()]);
                    $message = "{$count} permission(s) désactivée(s) avec succès";
                    break;

                case 'delete':
                    // Supprimer les associations
                    foreach ($permissions->get() as $permission) {
                        $permission->roles()->detach();
                        $permission->users()->detach();
                    }
                    $permissions->delete();
                    $message = "{$count} permission(s) supprimée(s) avec succès";
                    break;
            }

            $this->permissionService->clearCache();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'affected_count' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'action en lot',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cloner une permission
     */
    public function clone(Request $request, Permission $permission)
    {
        try {
            DB::beginTransaction();

            $newPermission = $permission->replicate();
            $newPermission->name = $permission->name . ' (Copie)';
            $newPermission->slug = $permission->slug . '-copy-' . time();
            $newPermission->is_system = false;
            $newPermission->created_by = auth()->id();
            $newPermission->updated_by = auth()->id();
            $newPermission->save();

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $newPermission,
                    'message' => 'Permission clonée avec succès'
                ], 201);
            }

            return redirect()
                ->route('private.permissions.edit', $newPermission)
                ->with('success', 'Permission clonée avec succès! Veuillez modifier les informations.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du clonage',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors du clonage : ' . $e->getMessage());
        }
    }

    /**
     * Activer/Désactiver une permission
     */
    public function toggle(Request $request, Permission $permission)
    {
        try {
            $permission->update([
                'is_active' => !$permission->is_active,
                'updated_by' => auth()->id(),
            ]);

            $this->permissionService->clearCache();

            return response()->json([
                'success' => true,
                'data' => [
                    'is_active' => $permission->is_active,
                    'permission' => $permission
                ],
                'message' => $permission->is_active
                    ? 'Permission activée avec succès'
                    : 'Permission désactivée avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporter les permissions
     */
    public function export(Request $request)
    {
        Gate::authorize('export-data', 'permissions');

        try {
            $format = $request->get('format', 'csv');

            $query = Permission::with(['roles', 'users']);

            // Appliquer les filtres s'ils sont fournis
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            if ($request->filled('resource')) {
                $query->where('resource', $request->resource);
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active === 'true');
            }

            $permissions = $query->orderBy('category')
                ->orderBy('resource')
                ->orderBy('action')
                ->get();

            switch ($format) {
                case 'json':
                    return response()->json([
                        'success' => true,
                        'data' => $permissions,
                        'total' => $permissions->count(),
                        'exported_at' => now()->toISOString()
                    ]);

                default: // CSV
                    $csv = "ID,Nom,Slug,Description,Ressource,Action,Catégorie,Actif,Système,Rôles,Utilisateurs directs\n";

                    foreach ($permissions as $permission) {
                        $csv .= sprintf(
                            '"%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                            $permission->id,
                            $permission->name,
                            $permission->slug,
                            $permission->description ?? '',
                            $permission->resource ?? '',
                            $permission->action,
                            $permission->category ?? '',
                            $permission->is_active ? 'Oui' : 'Non',
                            $permission->is_system ? 'Oui' : 'Non',
                            $permission->roles->pluck('name')->implode(', '),
                            $permission->users->pluck('nom_complet')->implode(', ')
                        );
                    }

                    return response($csv)
                        ->header('Content-Type', 'text/csv; charset=utf-8')
                        ->header('Content-Disposition', 'attachment; filename="permissions_' . date('Y-m-d_H-i-s') . '.csv"');
            }

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'export',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'export : ' . $e->getMessage());
        }
    }

    /**
     * Statistiques des permissions
     */
    public function statistics(Request $request)
    {

        try {
            // Statistiques générales
            $generalStats = [
                'total_permissions' => Permission::count(),
                'active_permissions' => Permission::where('is_active', true)->count(),
                'system_permissions' => Permission::where('is_system', true)->count(),
                'user_permissions' => Permission::where('is_system', false)->count(),
                'unused_permissions' => Permission::whereNull('last_used_at')
                    ->where('created_at', '<', now()->subDays(30))
                    ->count(),
            ];

            // Permissions par catégorie
            $byCategory = Permission::selectRaw('category, COUNT(*) as count')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get();

            // Permissions par ressource
            $byResource = Permission::selectRaw('resource, COUNT(*) as count')
                ->whereNotNull('resource')
                ->groupBy('resource')
                ->orderBy('count', 'desc')
                ->get();

            // Permissions par action
            $byAction = Permission::selectRaw('action, COUNT(*) as count')
                ->groupBy('action')
                ->orderBy('count', 'desc')
                ->get();

            // Permissions les plus utilisées (basé sur last_used_at)
            $mostUsed = Permission::whereNotNull('last_used_at')
                ->orderBy('last_used_at', 'desc')
                ->take(10)
                ->get(['id', 'name', 'resource', 'action', 'last_used_at']);

            // Permissions jamais utilisées
            $neverUsed = Permission::whereNull('last_used_at')
                ->where('created_at', '<', now()->subDays(30))
                ->get(['id', 'name', 'resource', 'action', 'created_at']);

            $stats = [
                'general' => $generalStats,
                'by_category' => $byCategory,
                'by_resource' => $byResource,
                'by_action' => $byAction,
                'most_used' => $mostUsed,
                'never_used' => $neverUsed
            ];

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $stats,
                    'message' => 'Statistiques récupérées avec succès'
                ]);
            }

            return view('components.private.permissions.statistics', $stats);

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des statistiques',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des statistiques']);
        }
    }

    /**
     * Rechercher des permissions
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'q' => 'required|string|min:2',
            'limit' => 'integer|min:1|max:50'
        ]);

        try {
            $query = $validated['q'];
            $limit = $validated['limit'] ?? 10;

            $permissions = Permission::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('slug', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('resource', 'like', "%{$query}%");
            })
            ->where('is_active', true)
            ->select('id', 'name', 'slug', 'resource', 'action', 'category')
            ->limit($limit)
            ->get();

            return response()->json([
                'success' => true,
                'data' => $permissions,
                'total' => $permissions->count(),
                'query' => $query
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifier les permissions d'un utilisateur
     */
    public function checkUserPermissions(Request $request, User $user)
    {
        $validated = $request->validate([
            'permission_slugs' => 'required|array',
            'permission_slugs.*' => 'string'
        ]);

        try {
            $results = [];

            foreach ($validated['permission_slugs'] as $slug) {
                $results[$slug] = $user->hasPermission($slug);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user_id' => $user->id,
                    'user_name' => $user->nom_complet,
                    'permissions' => $results
                ],
                'message' => 'Vérification des permissions effectuée'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification des permissions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Déterminer si la requête est une requête API
     */
    private function isApiRequest(Request $request)
    {
        return $request->wantsJson() ||
               $request->expectsJson() ||
               $request->is('api/*') ||
               $request->header('Accept') === 'application/json' ||
               $request->ajax();
    }
}
