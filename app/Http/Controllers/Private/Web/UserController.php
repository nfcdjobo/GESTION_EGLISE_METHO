<?php

namespace App\Http\Controllers\Private\Web;

use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Classe;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;

        // Appliquer les middlewares de permissions
        $this->middleware('auth');
        $this->middleware('user.status');
        $this->middleware('permission:users.read')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.update')->only(['edit', 'update']);
        $this->middleware('permission:users.delete')->only(['destroy']);
        $this->middleware('permission:users.export')->only(['export']);
        $this->middleware('permission:users.import')->only(['import', 'processImport']);
        $this->middleware('permission:users.validate')->only(['validate']);
        $this->middleware('permission:users.archive')->only(['archive']);
        $this->middleware('permission:users.restore')->only(['restore']);
    }

    /**
     * Afficher la liste des membres
     */
    public function index(Request $request)
    {

        $query = User::with(['roles', 'classe'])
        ->whereDoesntHave('roles', function ($q) {
            $q->where('roles.slug', 'super-admin');
        }); // exclure les super-admins


        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telephone_1', 'like', "%{$search}%");
            });
        }

        // Filtre par statut membre
        if ($request->filled('statut_membre')) {
            $query->where('statut_membre', $request->statut_membre);
        }

        // Filtre par statut baptême
        if ($request->filled('statut_bapteme')) {
            $query->where('statut_bapteme', $request->statut_bapteme);
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.slug', $request->role);
            });
        }

        // Filtre par classe
        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        // Filtre par statut actif
        if ($request->filled('actif')) {
            $query->where('actif', $request->actif === 'true');
        }

        // Filtre par sexe
        if ($request->filled('sexe')) {
            $query->where('sexe', $request->sexe);
        }

        // Tri
        $sortField = $request->get('sort', 'nom');
        $sortDirection = $request->get('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);


        // Pagination
        $users = $query->paginate(10);
        $users->appends($request->query());

        // Données pour les filtres
        $roles = Role::orderBy('level', 'desc')->get();
        $classes = Classe::orderBy('nom')->get();


        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $users,
            ]);
        }


        // Statistiques
        $stats = [
            'total' => User::count(),
            'actifs' => User::where('actif', true)->count(),
            'membres' => User::where('statut_membre', 'actif')->count(),
            'baptises' => User::where('statut_bapteme', 'baptise')->count(),
            'hommes' => User::where('sexe', 'masculin')->count(),
            'femmes' => User::where('sexe', 'feminin')->count(),
        ];

        return view('components.private.users.index', compact('users', 'roles', 'classes', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $roles = Role::orderBy('level', 'desc')->get();
        $classes = Classe::orderBy('nom')->get();
        /**
         * @var User $user
         */
        $user = auth()->user();
        // Vérifier le niveau de l'membres connecté pour filtrer les rôles
        if (!$user->isSuperAdmin()) {
            $userMaxLevel = $user->getHighestRoleLevel() ?? 0;
            $roles = $roles->filter(function ($role) use ($userMaxLevel) {
                return $role->level < $userMaxLevel;
            });
        }

        return view('components.private.users.create', compact('roles', 'classes'));
    }

    /**
     * Enregistrer un nouvel membres
     */

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            // Informations personnelles
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date|before:today',
            'sexe' => 'required|in:masculin,feminin',

            // Contact
            'telephone_1' => 'required|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email',

            // Adresse
            'adresse_ligne_1' => 'required|string|max:255',
            'adresse_ligne_2' => 'nullable|string|max:255',
            'ville' => 'required|string|max:100',
            'code_postal' => 'nullable|string|max:50',
            'region' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:2',

            // Informations familiales
            'statut_matrimonial' => 'nullable|in:celibataire,marie,divorce,veuf',
            'nombre_enfants' => 'nullable|integer|min:0',

            // Informations professionnelles
            'profession' => 'nullable|string|max:100',
            'employeur' => 'nullable|string|max:100',

            // Informations d'église - CORRIGÉ
            'classe_id' => 'nullable|uuid|exists:classes,id',
            'date_adhesion' => 'nullable|date',
            'statut_membre' => 'required|in:actif,inactif,visiteur,nouveau_converti',
            'statut_bapteme' => 'required|in:non_baptise,baptise,confirme',
            'date_bapteme' => 'nullable|date|required_if:statut_bapteme,baptise,confirme',
            'eglise_precedente' => 'nullable|string|max:255',

            // Contact d'urgence
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'contact_urgence_relation' => 'nullable|string|max:100',

            // Compte
            'password' => [
                'nullable',
                Password::min(8)->mixedCase()->numbers()
            ],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',

            // Photo
            'photo_profil' => 'nullable|image|max:2048',
        ], [
            // Messages personnalisés
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'telephone_1.required' => 'Le numéro de téléphone principal est obligatoire.',
            // 'password.required' => 'Le mot de passe est obligatoire.',
            'statut_membre.required' => 'Le statut de membre est obligatoire.',
            'statut_bapteme.required' => 'Le statut de baptême est obligatoire.',
            'date_bapteme.required_if' => 'La date de baptême est obligatoire pour les membres baptisés.',
            'classe_id.uuid' => 'L\'identifiant de classe doit être un UUID valide.',
            'classe_id.exists' => 'La classe sélectionnée n\'existe pas.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez corriger les erreurs ci-dessous.',
                    'error' => $validator->errors()
                ]);
            }
            return back()->withErrors($validator)->withInput()->with('error', 'Veuillez corriger les erreurs ci-dessous.');
        }

        $validated = $validator->validated();

        // Démarrer la transaction manuellement
        DB::beginTransaction();

        try {
            // Gérer l'upload de la photo
            if ($request->hasFile('photo_profil')) {
                $path = $request->file('photo_profil')->store('photos-profil', 'public');
                $validated['photo_profil'] = $path;
            }

            if (!isset($validated['password']) || !$validated['password']) {
                $validated['password'] = env('DEFAULT_PASSWORD', 'Metho@' . date('Y') . '!');
            }

            // Créer l'membres
            $user = User::create($validated);

            // AJOUT : Incrémenter le compteur de la classe si assignée
            if (!empty($validated['classe_id'])) {
                $classe = Classe::find($validated['classe_id']);
                if ($classe) {
                    $classe->incrementerInscrits();
                }
            }

            // Gestion des responsables si présents
            if (!empty($validated['responsables'])) {
                foreach ($validated['responsables'] as $responsable) {
                    $userResp = User::find($responsable['id']);
                    if ($userResp && !$userResp->classe_id) {
                        $userResp->classe_id = $user->id; // Erreur ici, devrait être $classe->id
                        $userResp->save();
                        // Pas besoin d'incrémenter car déjà fait ci-dessus
                    }
                }
            }

            /**
             * @var User $use
             */
            $use = auth()->user();

            // Attribuer les rôles
            if (!empty($validated['roles'])) {
                foreach ($validated['roles'] as $roleId) {
                    $role = Role::find($roleId);

                    // Vérifier que l'membres connecté peut attribuer ce rôle
                    if (!$use->isSuperAdmin()) {
                        $authUserLevel = $use->getHighestRoleLevel();
                        if ($authUserLevel === null || $role->level >= $authUserLevel) {
                            continue; // Passer ce rôle
                        }
                    }

                    $this->permissionService->assignRoleToUser(
                        $user,
                        $role,
                        $use
                    );

                    $user->roles()->syncWithoutDetaching([
                        $role->id => [
                            'attribue_par' => $use->id,
                            'attribue_le' => now(),
                            'actif' => true,
                        ]
                    ]);

                }
            } else {
                // Attribuer le rôle par défaut (visiteur ou membre)
                $defaultRole = $validated['statut_membre'] === 'visiteur' ? Role::where('slug', 'visiteur')->first() : Role::where('slug', 'membre')->first();

                if ($defaultRole) {
                    $this->permissionService->assignRoleToUser(
                        $user,
                        $defaultRole,
                        $use
                    );

                    $user->roles()->syncWithoutDetaching([
                        $defaultRole->id => [
                            'attribue_par' => $use->id,
                            'attribue_le' => now(),
                            'actif' => true,
                        ]
                    ]);

                }
            }

            // Envoyer un email de bienvenue (optionnel)
            // $user->sendWelcomeNotification();

            // Valider la transaction
            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $user,
                ]);
            }

            return redirect()
                ->route('private.users.index')
                ->with('success', 'Membres créé avec succès!');

        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollback();

            // Supprimer le fichier uploadé en cas d'erreur
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
// dd($e->getMessage());
            // Log l'erreur pour le debugging
            Log::error('Erreur lors de la création d\'un membres', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_data' => $validated
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Erreur lors de la création d'un membres",
                    'error' => $e->getMessage()
                ]);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création : ' . $e->getMessage());
        }
    }

    public function ajoutmembre(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'nom' => 'string|max:50',
            'prenom' => 'string|max:50',
            'sexe' => 'required|in:masculin,feminin',
            'telephone_1' => [
                'required',
                'string',
                'max:20',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('users')
                        ->where('telephone_1', $value)
                        ->orWhere('telephone_2', $value)
                        ->exists();

                    if ($exists) {
                        $fail('Le numéro de téléphone est déjà utilisé.');
                    }
                },
            ],
            'email' => 'nullable|email|unique:users,email',
        ], [
            // Messages personnalisés
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'telephone_1.required' => 'Le numéro de téléphone principal est obligatoire.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Veuillez corriger les erreurs ci-dessous.',
                'errors' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();

        $validated['password'] = env('DEFAULT_PASSWORD', 'Metho@' . date('Y') . '!');

        // Démarrer la transaction manuellement
        DB::beginTransaction();
        try {
            // Créer l'membres
            $user = User::create($validated);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Membres créé avec succès!',
                'data' => [
                    'id' => $user->id,
                    'text' => $user->nom . ' ' . $user->prenom . ($user->email ? ' (' . $user->email . ')' : ''),
                    'email' => $user->email
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est suivenu lors de l\'enregistrement.',
                'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Afficher les détails d'un membres
     */

    public function show(User $user)
    {
        /**
         * @var User $use
         */
        $use = auth()->user();
        // Vérifier que l'membres connecté peut voir cet membres
        if (!$use->isSuperAdmin() && auth()->id() !== $user->id) {
            Gate::authorize('users.read', $user);
        }

        $user->load([
            'roles.permissions',
            'permissions',
            'classe',
        ]);

        // Statistiques de l'membres
        $stats = [
            'roles_count' => $user->roles()->wherePivot('actif', true)->count(),
            'permissions_count' => $user->getAllPermissions()->count(),
            'direct_permissions_count' => $user->permissions()->wherePivot('is_granted', true)->count(),
            'transactions_count' => $user->transactionsDonateur()->count(),
            'reunions_count' => $user->reunionsOrganisees()->count(),
        ];

        // Historique des actions récentes
        $recentActivity = \App\Models\PermissionAuditLog::where('target_user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('components.private.users.show', compact('user', 'stats', 'recentActivity'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(User $user)
    {
        /**
         * @var User $use
         */
        $use = auth()->user();

        // Vérifier les permissions
        if (!$use->isSuperAdmin() && auth()->id() !== $user->id) {
            Gate::authorize('users.update', $user);
        }

        $roles = Role::orderBy('level', 'desc')->get();
        $classes = Classe::orderBy('nom')->get();
        $userRoles = $user->roles->pluck('id')->toArray();

        // Filtrer les rôles selon le niveau de l'membres connecté
        if (!$use->isSuperAdmin()) {
            $userMaxLevel = $use->getHighestRoleLevel() ?? 0;
            $roles = $roles->filter(function ($role) use ($userMaxLevel) {
                return $role->level < $userMaxLevel;
            });
        }

        return view('components.private.users.edit', compact('user', 'roles', 'classes', 'userRoles'));
    }

    /**
     * Mettre à jour un membres
     */
    public function update(Request $request, User $user)
    {
        /**
         * @var User $use
         */
        $use = auth()->user();

        // Vérifier les permissions
        if (!$use->isSuperAdmin() && auth()->id() !== $user->id) {
            Gate::authorize('users.update', $user);
        }


        $data = $request->all();
        $validator = Validator::make($data, [
            // Informations personnelles
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date|before:today',
            'sexe' => 'required|in:masculin,feminin',

            // Contact
            'telephone_1' => 'required|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,

            // Adresse
            'adresse_ligne_1' => 'required|string|max:255',
            'adresse_ligne_2' => 'nullable|string|max:255',
            'ville' => 'required|string|max:100',
            'code_postal' => 'nullable|string|max:50',
            'region' => 'nullable|string|max:100',
            'pays' => 'nullable|string|max:2',

            // Informations familiales
            'statut_matrimonial' => 'nullable|in:celibataire,marie,divorce,veuf',
            'nombre_enfants' => 'nullable|integer|min:0',

            // Informations professionnelles
            'profession' => 'nullable|string|max:100',
            'employeur' => 'nullable|string|max:100',

            // Informations d'église - CORRIGÉ
            'classe_id' => 'nullable|uuid|exists:classes,id',
            'date_adhesion' => 'nullable|date',
            'statut_membre' => 'required|in:actif,inactif,visiteur,nouveau_converti',
            'statut_bapteme' => 'required|in:non_baptise,baptise,confirme',
            'date_bapteme' => 'nullable|date|required_if:statut_bapteme,baptise,confirme',
            'eglise_precedente' => 'nullable|string|max:255',

            // Contact d'urgence
            'contact_urgence_nom' => 'nullable|string|max:255',
            'contact_urgence_telephone' => 'nullable|string|max:20',
            'contact_urgence_relation' => 'nullable|string|max:100',

            // Compte
            'password' => [
                'nullable',
                Password::min(8)->mixedCase()->numbers()
            ],
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',

            // Photo
            'photo_profil' => 'nullable|image|max:2048',
        ], [
            // Messages personnalisés
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'telephone_1.required' => 'Le numéro de téléphone principal est obligatoire.',
            // 'password.required' => 'Le mot de passe est obligatoire.',
            'statut_membre.required' => 'Le statut de membre est obligatoire.',
            'statut_bapteme.required' => 'Le statut de baptême est obligatoire.',
            'date_bapteme.required_if' => 'La date de baptême est obligatoire pour les membres baptisés.',
            'classe_id.uuid' => 'L\'identifiant de classe doit être un UUID valide.',
            'classe_id.exists' => 'La classe sélectionnée n\'existe pas.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs ci-dessous.');
        }

        $validated = $validator->validated();




        try {
            DB::transaction(function () use ($validated, $request, $user) {
                // Gérer l'upload de la photo
                if ($request->hasFile('photo_profil')) {
                    // Supprimer l'ancienne photo
                    if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
                        Storage::disk('public')->delete($user->photo_profil);
                    }

                    $path = $request->file('photo_profil')->store('photos-profil', 'public');
                    $validated['photo_profil'] = $path;
                }

                // Ne pas mettre à jour le mot de passe s'il n'est pas fourni
                if (empty($validated['password'])) {
                    unset($validated['password']);
                }

                $ancienneClasseId = $user->classe_id;
                $nouvelleClasseId = $validated['classe_id'] ?? null;

                // Mettre à jour l'membres
                $user->update($validated);

                // Gestion des changements de classe
                if ($ancienneClasseId !== $nouvelleClasseId) {
                    // Décrémenter l'ancienne classe
                    if ($ancienneClasseId) {
                        $ancienneClasse = Classe::find($ancienneClasseId);
                        if ($ancienneClasse) {
                            $ancienneClasse->decrementerInscrits();
                        }
                    }

                    // Incrémenter la nouvelle classe
                    if ($nouvelleClasseId) {
                        $nouvelleClasse = Classe::find($nouvelleClasseId);
                        if ($nouvelleClasse) {
                            $nouvelleClasse->incrementerInscrits();
                        }
                    }
                }

                // Synchroniser les rôles si l'membres a la permission
                if (Gate::allows('roles.assign') && isset($validated['roles'])) {
                    $rolesToSync = [];

                    foreach ($validated['roles'] as $roleId) {
                        $role = Role::find($roleId);
                        /**
                         * @var User $use
                         */
                        $use = auth()->user();
                        // Vérifier que l'membres connecté peut attribuer ce rôle
                        if (!$use->isSuperAdmin()) {
                            $authUserLevel = $use->getHighestRoleLevel();
                            if ($authUserLevel === null || $role->level >= $authUserLevel) {
                                continue; // Passer ce rôle
                            }
                        }

                        $user->roles()->syncWithoutDetaching([
                            $role->id => [
                                'attribue_par' => $use->id,
                                'attribue_le' => now(),
                                'actif' => false,
                            ]
                        ]);

                        $rolesToSync[] = $roleId;
                    }

                    $user->syncRoles($rolesToSync, auth()->id());
                }elseif(Gate::allows('roles.assign') && !isset($validated['roles'])){
                    $user->syncRoles([], auth()->id());
                }
            });

            return redirect()
                ->route('private.users.show', $user)
                ->with('success', 'Membres mis à jour avec succès!');
        } catch (Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un membres
     */
    public function destroy(User $user)
    {
        // Empêcher la suppression de son propre compte
        if (auth()->id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte.'
            ], 403);
        }

        // Vérifier les permissions
        Gate::authorize('manage-user', $user);

        // Empêcher la suppression du dernier super admin
        if ($user->hasRole('pasteur')) {
            $superAdminCount = User::whereHas('roles', function ($q) {
                $q->where('slug', 'pasteur');
            })->count();

            if ($superAdminCount <= 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer le dernier pasteur.'
                ], 403);
            }
        }

        try {
            // Soft delete (utilise SoftDeletes)
            $user->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Membres supprimé avec succès!'
                ]);
            }

            return redirect()
                ->route('private.users.index')
                ->with('success', 'Membres supprimé avec succès!');
        } catch (Exception $e) {
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
     * Valider un membre
     */
    public function validated(Request $request, User $user)
    {
        Gate::authorize('users.validate');

        try {
            $user->update([
                'statut_membre' => 'actif',
                'date_adhesion' => $user->date_adhesion ?? now(),
                'notes_admin' => $user->notes_admin . "\n[" . now()->format('d/m/Y H:i') . "] Validé par " . auth()->user()->nom_complet,
            ]);

            // Attribuer le rôle de membre actif
            $membreActifRole = Role::where('slug', 'membre-actif')->first();
            if ($membreActifRole) {
                $this->permissionService->assignRoleToUser(
                    $user,
                    $membreActifRole,
                    auth()->user()
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Membre validé avec succès!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Archiver un membres
     */
    public function archive(User $user)
    {
        Gate::authorize('users.archive');

        try {
            $user->update([
                'actif' => false,
                'notes_admin' => $user->notes_admin . "\n[" . now()->format('d/m/Y H:i') . "] Archivé par " . auth()->user()->nom_complet,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Membres archivé avec succès!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restaurer un membres archivé
     */
    public function restore($id)
    {
        Gate::authorize('users.restore');

        try {
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();
            $user->update([
                'actif' => true,
                'notes_admin' => $user->notes_admin . "\n[" . now()->format('d/m/Y H:i') . "] Restauré par " . auth()->user()->nom_complet,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Membres restauré avec succès!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exporter les membres
     */
    public function export(Request $request)
    {

        Gate::authorize('users.export');

        $users = User::with(['roles', 'classe'])->get();

        $csv = "ID,Prénom,Nom,Email,Téléphone,Statut Membre,Statut Baptême,Classe,Rôles,Actif,Date Adhésion\n";

        foreach ($users as $user) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s"' . "\n",
                $user->id,
                $user->prenom,
                $user->nom,
                $user->email,
                $user->telephone_1,
                $user->statut_membre,
                $user->statut_bapteme,
                $user->classe?->nom ?? '',
                $user->roles->pluck('name')->implode(', '),
                $user->actif ? 'Oui' : 'Non',
                $user->date_adhesion?->format('d/m/Y') ?? ''
            );
        }

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="membres_' . date('Y-m-d_H-i-s') . '.csv"');
    }

    /**
     * Afficher le formulaire d'import
     */
    public function import()
    {

        Gate::authorize('users.import');

        return view('components.private.users.import');
    }

    /**
     * Traiter l'import de fichier
     */
    public function processImport(Request $request)
    {
        Gate::authorize('users.import');

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:10240',
            'update_existing' => 'boolean',
            'send_welcome_email' => 'boolean',
        ]);

        try {
            $file = $request->file('file');
            $content = file_get_contents($file->getRealPath());

            // Parser le CSV
            $lines = explode("\n", $content);
            $headers = str_getcsv(array_shift($lines));

            $imported = 0;
            $updated = 0;
            $errors = [];

            DB::transaction(function () use ($lines, $headers, $request, &$imported, &$updated, &$errors) {
                foreach ($lines as $lineNumber => $line) {
                    if (empty(trim($line)))
                        continue;

                    $data = str_getcsv($line);
                    if (count($data) !== count($headers)) {
                        $errors[] = "Ligne " . ($lineNumber + 2) . ": Nombre de colonnes incorrect";
                        continue;
                    }

                    $row = array_combine($headers, $data);

                    try {
                        // Vérifier si l'membres existe
                        $existingUser = User::where('email', $row['email'])->first();

                        if ($existingUser && !$request->update_existing) {
                            $errors[] = "Ligne " . ($lineNumber + 2) . ": Email {$row['email']} existe déjà";
                            continue;
                        }

                        $userData = [
                            'prenom' => $row['prenom'] ?? $row['Prénom'] ?? '',
                            'nom' => $row['nom'] ?? $row['Nom'] ?? '',
                            'email' => $row['email'] ?? $row['Email'] ?? '',
                            'telephone_1' => $row['telephone'] ?? $row['Téléphone'] ?? '',
                            'sexe' => $row['sexe'] ?? $row['Sexe'] ?? 'masculin',
                            'adresse_ligne_1' => $row['adresse'] ?? $row['Adresse'] ?? 'Non renseignée',
                            'ville' => $row['ville'] ?? $row['Ville'] ?? 'Abidjan',
                            'statut_membre' => $row['statut_membre'] ?? $row['Statut'] ?? 'visiteur',
                            'statut_bapteme' => $row['statut_bapteme'] ?? $row['Baptême'] ?? 'non_baptise',
                        ];

                        if ($existingUser) {
                            $existingUser->update($userData);
                            $updated++;
                        } else {
                            $userData['password'] = Hash::make(Str::random(12));
                            $user = User::create($userData);

                            // Attribuer le rôle par défaut
                            $defaultRole = Role::where('slug', 'membre')->first();
                            if ($defaultRole) {
                                $this->permissionService->assignRoleToUser(
                                    $user,
                                    $defaultRole,
                                    auth()->user()
                                );
                            }

                            $imported++;

                            // Envoyer email de bienvenue si demandé
                            if ($request->send_welcome_email) {
                                // $user->sendWelcomeNotification();
                            }
                        }
                    } catch (Exception $e) {
                        $errors[] = "Ligne " . ($lineNumber + 2) . ": " . $e->getMessage();
                    }
                }
            });

            $message = "Import terminé : {$imported} membres importés";
            if ($updated > 0) {
                $message .= ", {$updated} mis à jour";
            }
            if (count($errors) > 0) {
                $message .= ". " . count($errors) . " erreurs rencontrées.";
            }

            return back()->with('success', $message)->with('import_errors', $errors);
        } catch (Exception $e) {
            return back()->with('error', 'Erreur lors de l\'import : ' . $e->getMessage());
        }
    }

    /**
     * Recherche AJAX des membres
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $users = User::where('actif', true)
            ->where(function ($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                    ->orWhere('prenom', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhereRaw("CONCAT(nom, ' ', prenom) LIKE ?", ["%{$query}%"]);
            })
            ->select('id', 'nom', 'prenom', 'email')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'text' => $user->nom_complet . ' (' . $user->email . ')',
                    'email' => $user->email,
                ];
            });

        return response()->json($users);
    }

    /**
     * Changer le statut actif/inactif
     */
    public function toggleStatus(User $user)
    {
        Gate::authorize('manage-user', $user);

        try {
            $user->update([
                'actif' => !$user->actif,
                'notes_admin' => $user->notes_admin . "\n[" . now()->format('d/m/Y H:i') . "] Statut changé par " . auth()->user()->nom_complet,
            ]);

            return response()->json([
                'success' => true,
                'actif' => $user->actif,
                'message' => $user->actif ? 'Membres activé' : 'Membres désactivé'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Réinitialiser le mot de passe
     */
    public function resetPassword(Request $request, User $user)
    {
        Gate::authorize('manage-user', $user);

        $validated = $request->validate([
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()
            ],
        ]);

        try {
            $user->update([
                'password' => Hash::make($validated['new_password']),
                'notes_admin' => $user->notes_admin . "\n[" . now()->format('d/m/Y H:i') . "] Mot de passe réinitialisé par " . auth()->user()->nom_complet,
            ]);

            // Optionnel : Envoyer un email de notification
            // $user->notify(new PasswordResetNotification());

            return response()->json([
                'success' => true,
                'message' => 'Mot de passe réinitialisé avec succès!'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ], 500);
        }
    }


    public function usersNotSubscribedToFimeco(Request $request, string $fimecoId)
    {
        try {
            // Validation du FIMECO
            $fimeco = DB::table('fimecos')
                ->where('id', $fimecoId)
                ->whereNull('deleted_at')
                ->first();

            if (!$fimeco) {
                return response()->json([
                    'error' => true,
                    'status_code' => 404,
                    'message' => 'FIMECO introuvable'
                ], 404);
            }

            // Paramètres de recherche et pagination
            $search = $request->get('search', '');
            $perPage = min((int) $request->get('per_page', 20), 100); // Limite max 100
            $page = max((int) $request->get('page', 1), 1);
            $sortBy = $request->get('sort_by', 'nom');
            $sortDirection = $request->get('sort_direction', 'asc');

            // Filtres additionnels
            $filters = [
                'statut_membre' => $request->get('statut_membre'),
                'sexe' => $request->get('sexe'),
                'ville' => $request->get('ville'),
                'classe_id' => $request->get('classe_id'),
                'age_min' => $request->get('age_min'),
                'age_max' => $request->get('age_max'),
                'date_adhesion_debut' => $request->get('date_adhesion_debut'),
                'date_adhesion_fin' => $request->get('date_adhesion_fin'),
            ];

            // Colonnes autorisées pour le tri
            $allowedSortColumns = [
                'nom',
                'prenom',
                'email',
                'telephone_1',
                'date_naissance',
                'date_adhesion',
                'statut_membre',
                'ville',
                'profession',
                'created_at'
            ];

            if (!in_array($sortBy, $allowedSortColumns)) {
                $sortBy = 'nom';
            }

            if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
                $sortDirection = 'asc';
            }

            // Construction de la requête optimisée
            $query = DB::table('users as u')
                ->selectRaw("
                u.id,
                u.prenom,
                u.nom,
                CONCAT(u.prenom, ' ', u.nom) as nom_complet,
                u.email,
                u.telephone_1,
                u.telephone_2,
                u.sexe,
                u.date_naissance,
                EXTRACT(YEAR FROM AGE(COALESCE(u.date_naissance, CURRENT_DATE)))::INTEGER as age,
                u.statut_membre,
                u.statut_bapteme,
                u.date_adhesion,
                u.classe_id,
                u.profession,
                u.ville,
                u.region,
                u.pays,
                u.photo_profil,
                u.created_at,
                u.updated_at
            ")
                // Anti-jointure optimisée : utilisateurs NON souscrits au FIMECO
                ->whereNotExists(function ($subQuery) use ($fimecoId) {
                    $subQuery->selectRaw('1')
                        ->from('subscriptions as s')
                        ->whereColumn('s.souscripteur_id', 'u.id')
                        ->where('s.fimeco_id', '=', $fimecoId)
                        ->whereNull('s.deleted_at');
                })
                // Filtres de base
                ->whereNull('u.deleted_at')
                ->where('u.actif', true);

            // Application des filtres de recherche (VERSION SIMPLIFIÉE SANS EXTENSIONS)
            if (!empty($search)) {
                $searchTerms = trim($search);

                $query->where(function ($searchQuery) use ($searchTerms) {
                    // Recherche dans nom/prénom (insensible à la casse)
                    $searchQuery->whereRaw(
                        "CONCAT(LOWER(u.prenom), ' ', LOWER(u.nom)) LIKE ?",
                        ['%' . strtolower($searchTerms) . '%']
                    )
                        // Recherche inverse (nom puis prénom)
                        ->orWhereRaw(
                            "CONCAT(LOWER(u.nom), ' ', LOWER(u.prenom)) LIKE ?",
                            ['%' . strtolower($searchTerms) . '%']
                        )
                        // Recherche dans email
                        ->orWhere('u.email', 'ILIKE', "%{$searchTerms}%")
                        // Recherche dans téléphones
                        ->orWhere('u.telephone_1', 'LIKE', "%{$searchTerms}%")
                        ->orWhere('u.telephone_2', 'LIKE', "%{$searchTerms}%")
                        // Recherche dans profession et ville
                        ->orWhere('u.profession', 'ILIKE', "%{$searchTerms}%")
                        ->orWhere('u.ville', 'ILIKE', "%{$searchTerms}%")
                        // Recherche par prénom seul
                        ->orWhere('u.prenom', 'ILIKE', "%{$searchTerms}%")
                        // Recherche par nom seul
                        ->orWhere('u.nom', 'ILIKE', "%{$searchTerms}%");
                });
            }

            // Application des filtres métier
            foreach ($filters as $key => $value) {
                if (!empty($value)) {
                    switch ($key) {
                        case 'statut_membre':
                            if (in_array($value, ['actif', 'inactif', 'visiteur', 'nouveau_converti'])) {
                                $query->where('u.statut_membre', $value);
                            }
                            break;

                        case 'sexe':
                            if (in_array($value, ['masculin', 'feminin'])) {
                                $query->where('u.sexe', $value);
                            }
                            break;

                        case 'ville':
                            $query->where('u.ville', 'ILIKE', "%{$value}%");
                            break;

                        case 'classe_id':
                            $query->where('u.classe_id', $value);
                            break;

                        case 'age_min':
                            $ageMin = (int) $value;
                            if ($ageMin > 0 && $ageMin < 120) {
                                $query->whereRaw("EXTRACT(YEAR FROM AGE(COALESCE(u.date_naissance, CURRENT_DATE))) >= ?", [$ageMin]);
                            }
                            break;

                        case 'age_max':
                            $ageMax = (int) $value;
                            if ($ageMax > 0 && $ageMax < 120) {
                                $query->whereRaw("EXTRACT(YEAR FROM AGE(COALESCE(u.date_naissance, CURRENT_DATE))) <= ?", [$ageMax]);
                            }
                            break;

                        case 'date_adhesion_debut':
                            if (Carbon::hasFormat($value, 'Y-m-d')) {
                                $query->where('u.date_adhesion', '>=', $value);
                            }
                            break;

                        case 'date_adhesion_fin':
                            if (Carbon::hasFormat($value, 'Y-m-d')) {
                                $query->where('u.date_adhesion', '<=', $value);
                            }
                            break;
                    }
                }
            }

            // Application du tri avec gestion des valeurs nulles
            switch ($sortBy) {
                case 'age':
                    $query->orderByRaw("EXTRACT(YEAR FROM AGE(COALESCE(u.date_naissance, CURRENT_DATE))) {$sortDirection} NULLS LAST");
                    break;

                case 'nom_complet':
                    $query->orderBy('u.nom', $sortDirection)
                        ->orderBy('u.prenom', $sortDirection);
                    break;

                default:
                    $query->orderBy("u.{$sortBy}", $sortDirection);
                    break;
            }

            // Tri secondaire pour la cohérence
            if ($sortBy !== 'nom') {
                $query->orderBy('u.nom', 'asc')
                    ->orderBy('u.prenom', 'asc');
            }

            // Exécution avec pagination Laravel optimisée
            $offset = ($page - 1) * $perPage;

            // Requête de comptage optimisée (même logique de recherche)
            $totalCountQuery = DB::table('users as u')
                ->whereNotExists(function ($subQuery) use ($fimecoId) {
                    $subQuery->selectRaw('1')
                        ->from('subscriptions as s')
                        ->whereColumn('s.souscripteur_id', 'u.id')
                        ->where('s.fimeco_id', '=', $fimecoId)
                        ->whereNull('s.deleted_at');
                })
                ->whereNull('u.deleted_at')
                ->where('u.actif', true);

            // Application des mêmes filtres de recherche pour le comptage
            if (!empty($search)) {
                $totalCountQuery->where(function ($searchQuery) use ($search) {
                    $searchQuery->whereRaw(
                        "CONCAT(LOWER(u.prenom), ' ', LOWER(u.nom)) LIKE ?",
                        ['%' . strtolower($search) . '%']
                    )
                        ->orWhereRaw(
                            "CONCAT(LOWER(u.nom), ' ', LOWER(u.prenom)) LIKE ?",
                            ['%' . strtolower($search) . '%']
                        )
                        ->orWhere('u.email', 'ILIKE', "%{$search}%")
                        ->orWhere('u.telephone_1', 'LIKE', "%{$search}%")
                        ->orWhere('u.telephone_2', 'LIKE', "%{$search}%")
                        ->orWhere('u.profession', 'ILIKE', "%{$search}%")
                        ->orWhere('u.ville', 'ILIKE', "%{$search}%")
                        ->orWhere('u.prenom', 'ILIKE', "%{$search}%")
                        ->orWhere('u.nom', 'ILIKE', "%{$search}%");
                });
            }

            // Appliquer les filtres métier au comptage
            foreach ($filters as $key => $value) {
                if (!empty($value)) {
                    switch ($key) {
                        case 'statut_membre':
                            if (in_array($value, ['actif', 'inactif', 'visiteur', 'nouveau_converti'])) {
                                $totalCountQuery->where('u.statut_membre', $value);
                            }
                            break;
                        case 'sexe':
                            if (in_array($value, ['masculin', 'feminin'])) {
                                $totalCountQuery->where('u.sexe', $value);
                            }
                            break;
                        case 'ville':
                            $totalCountQuery->where('u.ville', 'ILIKE', "%{$value}%");
                            break;
                        case 'classe_id':
                            $totalCountQuery->where('u.classe_id', $value);
                            break;
                        case 'age_min':
                            $ageMin = (int) $value;
                            if ($ageMin > 0) {
                                $totalCountQuery->whereRaw("EXTRACT(YEAR FROM AGE(COALESCE(u.date_naissance, CURRENT_DATE))) >= ?", [$ageMin]);
                            }
                            break;
                        case 'age_max':
                            $ageMax = (int) $value;
                            if ($ageMax > 0) {
                                $totalCountQuery->whereRaw("EXTRACT(YEAR FROM AGE(COALESCE(u.date_naissance, CURRENT_DATE))) <= ?", [$ageMax]);
                            }
                            break;
                    }
                }
            }

            // Exécution des requêtes
            $totalCount = $totalCountQuery->count();
            $users = $query->offset($offset)->limit($perPage)->get();

            // Calculs de pagination
            $totalPages = ceil($totalCount / $perPage);
            $hasNextPage = $page < $totalPages;
            $hasPrevPage = $page > 1;

            // Statistiques rapides
            $stats = [
                'total_non_souscris' => $totalCount,
                'page_actuelle' => $page,
                'par_page' => $perPage,
                'total_pages' => $totalPages,
                'has_next_page' => $hasNextPage,
                'has_prev_page' => $hasPrevPage,
            ];

            // Ajout d'informations sur le FIMECO pour contexte
            $fimecoInfo = [
                'id' => $fimeco->id,
                'nom' => $fimeco->nom,
                'statut' => $fimeco->statut,
                'progression' => $fimeco->progression ?? 0,
            ];

            return response()->json([
                'success' => true,
                'message' => 'Utilisateurs non souscrits au FIMECO récupérés avec succès',
                'fimeco' => $fimecoInfo,
                'users' => $users,
                'pagination' => $stats,
                'filters_applied' => array_filter($filters),
                'search_term' => $search,
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'error' => true,
                'status_code' => 422,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);

        } catch (QueryException $e) {
            Log::error('Erreur PostgreSQL dans usersNotSubscribedToFimeco', [
                'fimeco_id' => $fimecoId,
                'error' => $e->getMessage(),
                'sql' => $e->getSql(),
            ]);

            return response()->json([
                'error' => true,
                'status_code' => 500,
                'message' => 'Erreur de base de données',
                'details' => app()->environment('local') ? $e->getMessage() : 'Erreur interne'
            ], 500);

        } catch (Exception $e) {
            Log::error('Erreur générale dans usersNotSubscribedToFimeco', [
                'fimeco_id' => $fimecoId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => true,
                'status_code' => 500,
                'message' => 'Une erreur inattendue s\'est produite',
                'details' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }


}
