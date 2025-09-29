<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\User;
use App\Models\Classe;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClasseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:classes.read')->only(['index', 'show', 'statistiques', 'getMembers', 'getUtilisateursDisponibles']);
        $this->middleware('permission:classes.create')->only(['create', 'store']);
        $this->middleware('permission:classes.update')->only(['edit', 'update', 'toggleStatus', 'duplicate', 'archive', 'restore', 'inscrireUtilisateur', 'desinscrireUtilisateur', 'ajouterNouveauxMembres', 'ajouterResponsable', 'retirerResponsable']);
        $this->middleware('permission:classes.delete')->only(['destroy', 'bulkActions']);
        $this->middleware('permission:classes.export')->only(['export']);
    }

    /**
     * Affiche la liste des classes
     */
    public function index(Request $request)
    {
        try {
            $query = Classe::query();

            if (Auth::user()->hasAnyRole(['conducteur'])) {
                $query->where('id', Auth::user()->classe_id);
            }

            // Filtres
            if ($request->has('tranche_age')) {
                $query->parTrancheAge($request->tranche_age);
            }

            if ($request->has('actives_seulement') && $request->actives_seulement) {
                $query->actives();
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('tranche_age', 'like', "%{$search}%");
                });
            }

            

            // Pagination
            $perPage = $request->get('per_page', 10);

            /** @var \Illuminate\Pagination\LengthAwarePaginator $classes */
            $classes = $query->paginate($perPage);

            // Ajouter les statistiques pour chaque classe
            $classes->through(function ($classe) {
                $classe->statistiques = $classe->getStatistiques();
                $classe->responsables_collection = $classe->responsables();
                return $classe;
            });

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $classes,
                    'message' => 'Classes récupérées avec succès'
                ]);
            }

            return view('components.private.classes.index', compact('classes'));

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des classes',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des classes']);
        }
    }

    /**
     * Affiche le formulaire de création d'une nouvelle classe
     */
    /**
     * Affiche le formulaire de création d'une nouvelle classe
     */
    public function create(Request $request)
    {
        try {
            // Récupérer seulement les utilisateurs qui ne sont dans aucune classe
            $utilisateurs = User::actifs()
                ->whereNull('classe_id') // Filtrer les utilisateurs sans classe
                ->select('id', 'prenom', 'nom', 'email', 'telephone_1')
                ->orderBy('prenom')
                ->orderBy('nom')
                ->get();

            $tranches_age = $this->getTrancheAgeOptions();
            $types_responsabilite = $this->getTypesResponsabilite();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'utilisateurs' => $utilisateurs,
                        'tranches_age_disponibles' => $tranches_age,
                        'types_responsabilite' => $types_responsabilite
                    ],
                    'message' => 'Données pour création récupérées avec succès'
                ]);
            }

            return view('components.private.classes.create', compact('utilisateurs', 'tranches_age', 'types_responsabilite'));

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
     * Enregistre une nouvelle classe
     */
    public function store(Request $request)
    {



        // Filtrer les responsables vides et valider
        if ($request->has('responsables')) {
            $responsables = array_filter($request->responsables, function ($responsable) {
                return !empty($responsable['id']) && !empty($responsable['responsabilite']);
            });

            // Vérifier qu'il n'y a pas de responsables incomplets
            foreach ($request->responsables as $index => $responsable) {
                if (
                    (!empty($responsable['id']) && empty($responsable['responsabilite'])) ||
                    (empty($responsable['id']) && !empty($responsable['responsabilite']))
                ) {
                    return back()->withErrors([
                        'responsables' => "Le responsable à la position " . ($index + 1) . " est incomplet. Veuillez remplir tous les champs ou le supprimer."
                    ])->withInput();
                }
            }

            $request->merge(['responsables' => empty($responsables) ? null : array_values($responsables)]);
        }


        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:classes,nom',
            'description' => 'nullable|string',
            'tranche_age' => 'nullable|string|max:255',
            'age_minimum' => 'nullable|integer|min:0|max:120',
            'age_maximum' => 'nullable|integer|min:0|max:120|gte:age_minimum',
            'responsables' => 'nullable|array',
            'responsables.*.id' => 'required_with:responsables.*|nullable|uuid|exists:users,id',
            'responsables.*.responsabilite' => 'required_with:responsables.*.id|nullable|string',
            'responsables.*.superieur' => 'nullable|boolean',
            'programme' => 'nullable|array',
            'image_classe' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($validator->fails()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $data = $request->except(['image_classe']);
            $data['nombre_inscrits'] = 0;

            // Gestion de l'upload d'image
            if ($request->hasFile('image_classe')) {
                $imagePath = $request->file('image_classe')->store('classes', 'public');
                $data['image_classe'] = $imagePath;
            }

            $classe = Classe::create($data);

            if ($classe->responsables) {
                foreach ($classe->responsables as $responsable) {
                    $user = User::find($responsable['id']);
                    $user->classe_id = $classe->id;
                    $user->save();
                }
            }

            // Gérer les changements de responsables
            // $this->gererChangementsResponsables($classe, $anciensResponsables);


            DB::commit();

            // Charger les responsables pour la réponse
            $classe->responsables_collection = $classe->responsables();
            $classe->statistiques = $classe->getStatistiques();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $classe,
                    'message' => 'Classe créée avec succès'
                ], 201);
            }

            return redirect()->route('private.classes.show', $classe->id)->with('success', 'Classe créée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la classe',
                    'error' => $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Erreur lors de la création de la classe'])->withInput();
        }
    }

    /**
     * Affiche une classe spécifique
     */
    public function show(Request $request, $id)
    {
        try {
            $classe = Classe::with([
                'membres' => function ($query) {
                    $query->select('id', 'prenom', 'nom', 'email', 'telephone_1', 'classe_id', 'statut_membre')
                        ->orderBy('prenom')
                        ->orderBy('nom');
                }
            ])->findOrFail($id);

            $classe->responsables_collection = $classe->responsables();
            $classe->statistiques = $classe->getStatistiques();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $classe,
                    'message' => 'Classe récupérée avec succès'
                ]);
            }

            return view('components.private.classes.show', compact('classe'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Classe non trouvée'
                ], 404);
            }

            return redirect()->route('private.classes.index')
                ->withErrors(['error' => 'Classe non trouvée']);
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération de la classe',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération de la classe']);
        }
    }

    /**
     * Affiche le formulaire d'édition d'une classe
     */
public function edit(Request $request, $id)
{
    try {
        $classe = Classe::findOrFail($id);

        // Récupérer les utilisateurs sans classe ET les responsables actuels de cette classe
        $responsablesIds = collect($classe->responsables ?? [])->pluck('id')->toArray();

        $utilisateurs = User::actifs()
            ->where(function($query) use ($responsablesIds) {
                $query->whereNull('classe_id')
                      ->orWhereIn('id', $responsablesIds);
            })
            ->select('id', 'prenom', 'nom', 'email', 'telephone_1')
            ->orderBy('prenom')
            ->orderBy('nom')
            ->get();

        $tranches_age = $this->getTrancheAgeOptions();
        $types_responsabilite = $this->getTypesResponsabilite();

        // Charger les responsables actuels
        $classe->responsables_collection = $classe->responsables();

        if ($this->isApiRequest($request)) {
            return response()->json([
                'success' => true,
                'data' => [
                    'classe' => $classe,
                    'utilisateurs' => $utilisateurs,
                    'tranches_age_disponibles' => $tranches_age,
                    'types_responsabilite' => $types_responsabilite
                ],
                'message' => 'Données pour édition récupérées avec succès'
            ]);
        }

        return view('components.private.classes.edit', compact('classe', 'utilisateurs', 'tranches_age', 'types_responsabilite'));

    } catch (\Exception $e) {
        // ... gestion des erreurs
    }
}



    /**
     * Met à jour une classe spécifique
     */
    public function update(Request $request, $id)
    {
        // Filtrer les responsables vides et valider
        if ($request->has('responsables')) {
            $responsables = array_filter($request->responsables, function ($responsable) {
                return !empty($responsable['id']) && !empty($responsable['responsabilite']);
            });

            // Vérifier qu'il n'y a pas de responsables incomplets
            foreach ($request->responsables as $index => $responsable) {
                if (
                    (!empty($responsable['id']) && empty($responsable['responsabilite'])) ||
                    (empty($responsable['id']) && !empty($responsable['responsabilite']))
                ) {
                    return back()->withErrors([
                        'responsables' => "Le responsable à la position " . ($index + 1) . " est incomplet. Veuillez remplir tous les champs ou le supprimer."
                    ])->withInput();
                }
            }

            $request->merge(['responsables' => empty($responsables) ? null : array_values($responsables)]);
        }

        $validator = Validator::make($request->all(), [
            'nom' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classes', 'nom')->ignore($id)
            ],
            'description' => 'nullable|string',
            'tranche_age' => 'nullable|string|max:255',
            'age_minimum' => 'nullable|integer|min:0|max:120',
            'age_maximum' => 'nullable|integer|min:0|max:120|gte:age_minimum',
            'responsables' => 'nullable|array',
            'responsables.*.id' => 'required_with:responsables.*|nullable|uuid|exists:users,id',
            'responsables.*.responsabilite' => 'required_with:responsables.*.id|nullable|string',
            'responsables.*.superieur' => 'boolean',
            'programme' => 'nullable|array',
            'image_classe' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($validator->fails()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        try {
            $classe = Classe::findOrFail($id);

            DB::beginTransaction();

            // Récupérer les anciens responsables avant la mise à jour
            $anciensResponsables = collect($classe->responsables ?? [])->pluck('id')->toArray();

            $data = $request->except(['image_classe', '_method', '_token']);

            // Gestion de l'upload d'image
            if ($request->hasFile('image_classe')) {
                // Supprimer l'ancienne image si elle existe
                if ($classe->image_classe) {
                    Storage::disk('public')->delete($classe->image_classe);
                }

                $imagePath = $request->file('image_classe')->store('classes', 'public');
                $data['image_classe'] = $imagePath;
            }

            $classe->update($data);

            // Gérer les changements de responsables
            $this->gererChangementsResponsables($classe, $anciensResponsables);

            DB::commit();

            // Charger les responsables pour la réponse
            $classe->responsables_collection = $classe->responsables();
            $classe->statistiques = $classe->getStatistiques();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $classe,
                    'message' => 'Classe mise à jour avec succès'
                ]);
            }

            return redirect()->route('private.classes.show', $classe->id)
                ->with('success', 'Classe mise à jour avec succès');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Classe non trouvée'
                ], 404);
            }

            return redirect()->route('private.classes.index')
                ->withErrors(['error' => 'Classe non trouvée']);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de la classe',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la mise à jour de la classe'])
                ->withInput();
        }
    }


    /**
     * Gérer les changements de responsables lors de la mise à jour
     */
    private function gererChangementsResponsables($classe, $anciensResponsables)
    {
        $nouveauxResponsables = collect($classe->responsables ?? [])->pluck('id')->toArray();

        // Calculer les différences
        $responsablesARetirer = array_diff($anciensResponsables, $nouveauxResponsables);
        $responsablesAAjouter = array_diff($nouveauxResponsables, $anciensResponsables);

        // Retirer les anciens responsables qui ne sont plus responsables
        foreach ($responsablesARetirer as $userId) {
            $user = User::find($userId);
            if ($user && $user->classe_id === $classe->id) {
                // Vérifier s'il est aussi membre normal (pas seulement responsable)
                $estMembreNormal = $this->verifierSiMembreNormal($user, $classe);

                if (!$estMembreNormal) {
                    $user->update(['classe_id' => null]);
                    $classe->decrementerInscrits();
                }
            }
        }

        // Ajouter les nouveaux responsables
        foreach ($responsablesAAjouter as $userId) {
            $user = User::find($userId);
            if ($user) {
                // Si l'utilisateur n'était pas déjà membre, l'ajouter
                if (!$user->classe_id) {
                    $user->update(['classe_id' => $classe->id]);
                    $classe->incrementerInscrits();
                } elseif ($user->classe_id !== $classe->id) {
                    // L'utilisateur est dans une autre classe, erreur
                    throw new \Exception("L'utilisateur {$user->nom_complet} est déjà membre d'une autre classe");
                }
                // Si déjà membre de cette classe, ne rien faire (juste ajout de responsabilité)
            }
        }
    }


    /**
     * Vérifier si un utilisateur est membre normal (pas seulement responsable)
     */
    private function verifierSiMembreNormal($user, $classe)
    {
        // Logique pour déterminer si l'utilisateur était membre avant d'être responsable
        // Pour simplifier, on considère que s'il a un statut_membre actif, il reste membre
        return $user->statut_membre === 'actif';
    }

    /**
     * Ajouter un responsable à une classe
     */
    public function ajouterResponsable(Request $request, $classeId)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|uuid|exists:users,id',
            'responsabilite' => 'required|string',
            'superieur' => 'boolean'
        ]);

        if ($validator->fails()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        try {
            $classe = Classe::findOrFail($classeId);
            $user = Auth::user();

            // Vérifier les permissions
            if (!$classe->peutGererResponsables($user->id) && !$user->can('classes.update')) {
                $message = 'Vous n\'avez pas les permissions pour gérer les responsables de cette classe';

                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 403);
                }

                return back()->withErrors(['error' => $message]);
            }

            $success = $classe->ajouterResponsable(
                $request->user_id,
                $request->responsabilite,
                $request->get('superieur', false)
            );

            if (!$success) {
                $message = 'L\'utilisateur est déjà responsable de cette classe';

                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }

                return back()->withErrors(['error' => $message]);
            }

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Responsable ajouté avec succès'
                ]);
            }

            return back()->with('success', 'Responsable ajouté avec succès');

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout du responsable',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de l\'ajout du responsable']);
        }
    }

    /**
     * Retirer un responsable d'une classe
     */
    public function retirerResponsable(Request $request, $classeId)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|uuid|exists:users,id'
        ]);

        if ($validator->fails()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        try {
            $classe = Classe::findOrFail($classeId);
            $user = Auth::user();

            // Vérifier les permissions
            if (!$classe->peutGererResponsables($user->id) && !$user->can('classes.update')) {
                $message = 'Vous n\'avez pas les permissions pour gérer les responsables de cette classe';

                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 403);
                }

                return back()->withErrors(['error' => $message]);
            }

            $success = $classe->retirerResponsable($request->user_id);

            if (!$success) {
                $message = 'Erreur lors de la suppression du responsable';

                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }

                return back()->withErrors(['error' => $message]);
            }

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Responsable retiré avec succès'
                ]);
            }

            return back()->with('success', 'Responsable retiré avec succès');

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du responsable',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la suppression du responsable']);
        }
    }

    /**
     * Inscrire un utilisateur à une classe
     */
    public function inscrireUtilisateur(Request $request, $classeId)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|uuid|exists:users,id'
        ]);

        if ($validator->fails()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        try {
            $classe = Classe::findOrFail($classeId);
            $user = User::findOrFail($request->user_id);

            // Vérifier si l'utilisateur n'est pas déjà dans une classe
            if ($user->classe_id) {
                $message = 'L\'utilisateur est déjà inscrit dans une classe';

                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }

                return back()->withErrors(['error' => $message]);
            }

            // Vérifier la compatibilité d'âge
            if ($user->date_naissance) {
                $age = $user->date_naissance->diffInYears(now());
                if (!$classe->ageCompatible($age)) {
                    $message = 'L\'âge de l\'utilisateur n\'est pas compatible avec cette classe';

                    if ($this->isApiRequest($request)) {
                        return response()->json([
                            'success' => false,
                            'message' => $message
                        ], 400);
                    }

                    return back()->withErrors(['error' => $message]);
                }
            }

            DB::beginTransaction();

            // Inscrire l'utilisateur
            $user->update(['classe_id' => $classe->id]);
            $classe->incrementerInscrits();

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Utilisateur inscrit avec succès dans la classe'
                ]);
            }

            return back()->with('success', 'Utilisateur inscrit avec succès dans la classe');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Classe ou utilisateur non trouvé'
                ], 404);
            }

            return back()->withErrors(['error' => 'Classe ou utilisateur non trouvé']);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'inscription',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de l\'inscription']);
        }
    }

    /**
     * Désinscrire un utilisateur d'une classe
     */
    public function desinscrireUtilisateur(Request $request, $classeId)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|uuid|exists:users,id'
        ]);

        if ($validator->fails()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        try {
            $classe = Classe::findOrFail($classeId);
            $user = User::findOrFail($request->user_id);

            // Vérifier si l'utilisateur est bien dans cette classe
            if ($user->classe_id !== $classe->id) {
                $message = 'L\'utilisateur n\'est pas inscrit dans cette classe';

                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }

                return back()->withErrors(['error' => $message]);
            }

            DB::beginTransaction();

            // Désinscrire l'utilisateur
            $user->update(['classe_id' => null]);
            $classe->decrementerInscrits();

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Utilisateur désinscrit avec succès de la classe'
                ]);
            }

            return back()->with('success', 'Utilisateur désinscrit avec succès de la classe');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Classe ou utilisateur non trouvé'
                ], 404);
            }

            return back()->withErrors(['error' => 'Classe ou utilisateur non trouvé']);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la désinscription',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la désinscription']);
        }
    }

    /**
     * Ajouter plusieurs nouveaux membres à une classe
     */
    public function ajouterNouveauxMembres(Request $request, $classeId)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'uuid|exists:users,id',
            'force_age_check' => 'boolean'
        ]);

        if ($validator->fails()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        try {
            $classe = Classe::findOrFail($classeId);
            $userIds = $request->user_ids;
            $forceAgeCheck = $request->get('force_age_check', false);

            // Récupérer tous les utilisateurs
            $users = User::whereIn('id', $userIds)->get();

            if ($users->count() !== count($userIds)) {
                $message = 'Certains utilisateurs n\'ont pas été trouvés';

                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }

                return back()->withErrors(['error' => $message]);
            }

            // Vérifications préliminaires
            $erreurs = [];
            $utilisateursDejaInscrits = [];
            $utilisateursAgeIncompatible = [];
            $utilisateursValides = [];

            foreach ($users as $user) {
                // Vérifier si l'utilisateur n'est pas déjà dans une classe
                if ($user->classe_id) {
                    $utilisateursDejaInscrits[] = $user->prenom . ' ' . $user->nom;
                    continue;
                }

                // Vérifier la compatibilité d'âge si pas forcé
                if (!$forceAgeCheck && $user->date_naissance) {
                    $age = $user->date_naissance->diffInYears(now());
                    if (!$classe->ageCompatible($age)) {
                        $utilisateursAgeIncompatible[] = $user->prenom . ' ' . $user->nom . ' (' . $age . ' ans)';
                        continue;
                    }
                }

                $utilisateursValides[] = $user;
            }

            // Construire les messages d'erreur
            if (!empty($utilisateursDejaInscrits)) {
                $erreurs[] = 'Déjà inscrits dans une classe: ' . implode(', ', $utilisateursDejaInscrits);
            }

            if (!empty($utilisateursAgeIncompatible)) {
                $erreurs[] = 'Âge incompatible: ' . implode(', ', $utilisateursAgeIncompatible);
            }

            // Si il y a des erreurs critiques, arrêter
            if (!empty($erreurs) && empty($utilisateursValides)) {
                $message = 'Aucun utilisateur ne peut être inscrit. ' . implode('. ', $erreurs);

                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => $message,
                        'details' => [
                            'deja_inscrits' => $utilisateursDejaInscrits,
                            'age_incompatible' => $utilisateursAgeIncompatible
                        ]
                    ], 400);
                }

                return back()->withErrors(['error' => $message]);
            }

            // Procéder à l'inscription des utilisateurs valides
            DB::beginTransaction();

            $utilisateursInscrits = [];
            foreach ($utilisateursValides as $user) {
                $user->update(['classe_id' => $classe->id]);
                $utilisateursInscrits[] = $user->prenom . ' ' . $user->nom;
            }

            // Mettre à jour le nombre d'inscrits
            $classe->increment('nombre_inscrits', count($utilisateursInscrits));

            DB::commit();

            // Préparer le message de succès
            $messageSucces = count($utilisateursInscrits) . ' utilisateur(s) inscrit(s) avec succès: ' . implode(', ', $utilisateursInscrits);

            if (!empty($erreurs)) {
                $messageSucces .= '. Avertissements: ' . implode('. ', $erreurs);
            }

            // Recharger la classe avec les nouvelles données
            $classe->refresh();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'classe' => $classe,
                        'utilisateurs_inscrits' => $utilisateursInscrits,
                        'nombre_inscrits' => count($utilisateursInscrits),
                        'avertissements' => $erreurs
                    ],
                    'message' => $messageSucces
                ]);
            }

            $flashType = empty($erreurs) ? 'success' : 'warning';
            return back()->with($flashType, $messageSucces);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Classe non trouvée'
                ], 404);
            }

            return back()->withErrors(['error' => 'Classe non trouvée']);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'inscription des membres',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de l\'inscription des membres: ' . $e->getMessage()]);
        }
    }

    /**
     * Récupérer les utilisateurs disponibles (sans classe) pour inscription
     */
    public function getUtilisateursDisponibles(Request $request, $classeId)
    {
        try {
            $classe = Classe::findOrFail($classeId);

            // Query de base pour les utilisateurs sans classe
            $query = User::actifs()
                ->whereNull('classe_id')
                ->select('id', 'prenom', 'nom', 'email', 'telephone_1', 'date_naissance');

            // Filtres optionnels
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('prenom', 'like', "%{$search}%")
                        ->orWhere('nom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filtrer par compatibilité d'âge si demandé
            if ($request->has('age_compatible') && $request->age_compatible) {
                if ($classe->age_minimum || $classe->age_maximum) {
                    $query->whereNotNull('date_naissance');

                    if ($classe->age_minimum) {
                        $dateMax = now()->subYears($classe->age_minimum);
                        $query->where('date_naissance', '<=', $dateMax);
                    }

                    if ($classe->age_maximum) {
                        $dateMin = now()->subYears($classe->age_maximum + 1);
                        $query->where('date_naissance', '>', $dateMin);
                    }
                }
            }

            // Tri
            $query->orderBy('prenom')->orderBy('nom');

            // Utiliser la pagination pour tous les cas (API et Web)
            $perPage = $request->get('per_page', 20);
            $utilisateurs = $query->paginate($perPage);

            // dd($classe);
            // Ajouter l'âge et la compatibilité
            $utilisateurs->through(function ($user) use ($classe) {
                if ($user->date_naissance) {
                    $age = $user->date_naissance->diffInYears(now());
                    $user->age = $age;
                    $user->age_compatible = $classe->ageCompatible($age);
                } else {
                    $user->age = null;
                    $user->age_compatible = true;
                }
                return $user;
            });

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'classe' => [
                            'id' => $classe->id,
                            'nom' => $classe->nom,
                            'tranche_age' => $classe->tranche_age,
                            'age_minimum' => $classe->age_minimum,
                            'age_maximum' => $classe->age_maximum
                        ],
                        'utilisateurs' => $utilisateurs
                    ],
                    'message' => 'Utilisateurs disponibles récupérés avec succès'
                ]);
            }

            return view('components.private.classes.utilisateurs-disponibles', compact('classe', 'utilisateurs'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Classe non trouvée'
                ], 404);
            }

            return redirect()->route('private.classes.index')
                ->withErrors(['error' => 'Classe non trouvée']);
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des utilisateurs disponibles',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des utilisateurs disponibles']);
        }
    }

    /**
     * Récupérer tous les membres d'une classe avec pagination et filtres
     */
    public function getMembers(Request $request, $classeId)
    {
        try {
            $classe = Classe::findOrFail($classeId);

            // Query de base pour les membres
            $query = $classe->membres()
                ->select('id', 'prenom', 'nom', 'email', 'telephone_1', 'date_naissance', 'statut_membre', 'created_at');

            // Filtres optionnels
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('prenom', 'like', "%{$search}%")
                        ->orWhere('nom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('telephone_1', 'like', "%{$search}%");
                });
            }

            if ($request->has('statut') && $request->statut) {
                $query->where('statut_membre', $request->statut);
            }

            // Tri
            $sortBy = $request->get('sort_by', 'prenom');
            $sortDirection = $request->get('sort_direction', 'asc');

            if (in_array($sortBy, ['prenom', 'nom', 'email', 'created_at'])) {
                $query->orderBy($sortBy, $sortDirection);
            }

            // Pour API : pagination, pour Web : get all
            if ($this->isApiRequest($request)) {
                $perPage = $request->get('per_page', 15);
                $membres = $query->paginate($perPage);
            } else {
                $membres = $query->orderBy('prenom')->orderBy('nom')->get();
            }

            // Ajouter l'âge calculé pour chaque membre
            if ($this->isApiRequest($request)) {
                $membres->through(function ($membre) {
                    if ($membre->date_naissance) {
                        $membre->age = $membre->date_naissance->diffInYears(now());
                    }
                    return $membre;
                });
            } else {
                $membres->each(function ($membre) {
                    if ($membre->date_naissance) {
                        $membre->age = $membre->date_naissance->diffInYears(now());
                    }
                });
            }

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'classe' => [
                            'id' => $classe->id,
                            'nom' => $classe->nom,
                            'tranche_age' => $classe->tranche_age,
                            'nombre_inscrits' => $classe->nombre_inscrits
                        ],
                        'membres' => $membres
                    ],
                    'message' => 'Membres récupérés avec succès'
                ]);
            }

            return view('components.private.classes.membres', compact('classe', 'membres'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Classe non trouvée'
                ], 404);
            }

            return redirect()->route('private.classes.index')
                ->withErrors(['error' => 'Classe non trouvée']);
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des membres',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des membres']);
        }
    }

    /**
     * Supprime une classe spécifique
     */
    public function destroy(Request $request, $id)
    {
        try {
            $classe = Classe::findOrFail($id);

            // Vérifier s'il y a des membres inscrits
            if ($classe->nombre_inscrits > 0) {
                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de supprimer une classe ayant des membres inscrits'
                    ], 400);
                }

                return back()->withErrors(['error' => 'Impossible de supprimer une classe ayant des membres inscrits']);
            }

            DB::beginTransaction();

            // Supprimer l'image si elle existe
            if ($classe->image_classe) {
                Storage::disk('public')->delete($classe->image_classe);
            }

            $classe->delete();

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Classe supprimée avec succès'
                ]);
            }

            return redirect()->route('private.classes.index')
                ->with('success', 'Classe supprimée avec succès');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Classe non trouvée'
                ], 404);
            }

            return redirect()->route('private.classes.index')
                ->withErrors(['error' => 'Classe non trouvée']);
        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de la classe',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la suppression de la classe']);
        }
    }

    /**
     * Obtenir les statistiques globales des classes
     */
    public function statistiques(Request $request)
    {

        try {
            $stats = [
                'total_classes' => Classe::count(),
                'classes_actives' => Classe::actives()->count(),
                'total_inscrits' => Classe::sum('nombre_inscrits'),
                'tranches_age' => Classe::select('tranche_age')
                    ->whereNotNull('tranche_age')
                    ->groupBy('tranche_age')
                    ->get()
                    ->pluck('tranche_age'),
                'nombre_inscrits_moyen' => round(Classe::avg('nombre_inscrits'), 2)
            ];

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $stats,
                    'message' => 'Statistiques récupérées avec succès'
                ]);
            }

            return view('components.private.classes.statistiques', compact('stats'));

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des statistiques',
                    'error' => $e->getMessage()
                ], 500);
            }
            dd($e->getMessage());
            return back()->withErrors(['error' => 'Erreur lors de la récupération des statistiques']);
        }
    }

    /**
     * Dupliquer une classe existante
     */
    public function duplicate(Request $request, $id)
    {
        try {
            $classeOriginale = Classe::findOrFail($id);

            DB::beginTransaction();

            $nouvelleClasse = $classeOriginale->replicate();
            $nouvelleClasse->nom = $classeOriginale->nom . ' (Copie)';
            $nouvelleClasse->nombre_inscrits = 0;
            $nouvelleClasse->save();

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $nouvelleClasse,
                    'message' => 'Classe dupliquée avec succès'
                ], 201);
            }

            return redirect()->route('private.classes.edit', $nouvelleClasse->id)
                ->with('success', 'Classe dupliquée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la duplication',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la duplication']);
        }
    }

    /**
     * Actions en lot sur plusieurs classes
     */
    public function bulkActions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:delete,archive',
            'classe_ids' => 'required|array',
            'classe_ids.*' => 'uuid|exists:classes,id'
        ]);

        if ($validator->fails()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        try {
            DB::beginTransaction();

            $classes = Classe::whereIn('id', $request->classe_ids);
            $count = $classes->count();

            switch ($request->action) {
                case 'delete':
                    // Vérifier qu'aucune classe n'a de membres
                    if ($classes->where('nombre_inscrits', '>', 0)->exists()) {
                        throw new \Exception('Impossible de supprimer des classes ayant des membres inscrits');
                    }
                    $classes->delete();
                    $message = "$count classe(s) supprimée(s) avec succès";
                    break;

                case 'archive':
                    $classes->update(['archived_at' => now()]);
                    $message = "$count classe(s) archivée(s) avec succès";
                    break;
            }

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'affected_count' => $count
                ]);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'action en lot',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de l\'action en lot: ' . $e->getMessage()]);
        }
    }

    /**
     * Archiver une classe
     */
    public function archive(Request $request, $id)
    {
        try {
            $classe = Classe::findOrFail($id);

            $classe->update(['archived_at' => now()]);

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $classe,
                    'message' => 'Classe archivée avec succès'
                ]);
            }

            return back()->with('success', 'Classe archivée avec succès');

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'archivage',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de l\'archivage']);
        }
    }

    /**
     * Restaurer une classe archivée
     */
    public function restore(Request $request, $id)
    {
        try {
            $classe = Classe::findOrFail($id);

            $classe->update(['archived_at' => null]);

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $classe,
                    'message' => 'Classe restaurée avec succès'
                ]);
            }

            return back()->with('success', 'Classe restaurée avec succès');

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la restauration',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la restauration']);
        }
    }

    /**
     * Exporter les données d'une classe
     */
    public function export(Request $request, $id)
    {
        try {
            $classe = Classe::with(['membres'])->findOrFail($id);

            $format = $request->get('format', 'csv');

            switch ($format) {
                case 'pdf':
                    return $this->exportToPdf($classe);
                case 'excel':
                    return $this->exportToExcel($classe);
                default:
                    return $this->exportToCsv($classe);
            }

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'export',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de l\'export']);
        }
    }

    /**
     * Détermine si la requête est une requête API
     */
    private function isApiRequest(Request $request)
    {
        return $request->wantsJson() ||
            $request->expectsJson() ||
            $request->is('api/*') ||
            $request->header('Accept') === 'application/json' ||
            $request->ajax();
    }

    /**
     * Récupérer les options de tranche d'âge disponibles
     */
    private function getTrancheAgeOptions()
    {
        return [
            '0-3 ans',
            '4-6 ans',
            '7-9 ans',
            '10-12 ans',
            '13-15 ans',
            '16-18 ans',
            '19-25 ans',
            '26-35 ans',
            '36-50 ans',
            '51-65 ans',
            '65+ ans',
            'Adultes',
            'Tous âges'
        ];
    }

    /**
     * Récupérer les types de responsabilité disponibles
     */
    private function getTypesResponsabilite()
    {
        return [
            'principal',
            'enseignant',
            'assistant',
            'delegue',
            'sous-delegue',
            'coordinateur',
            'superviseur'
        ];
    }

    /**
     * Méthodes d'export privées
     */
    private function exportToCsv($classe)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="classe-' . $classe->nom . '.csv"',
        ];

        $callback = function () use ($classe) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Prénom', 'Nom', 'Email', 'Téléphone', 'Statut']);

            foreach ($classe->membres as $membre) {
                fputcsv($file, [
                    $membre->prenom,
                    $membre->nom,
                    $membre->email,
                    $membre->telephone_1,
                    $membre->statut_membre
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }









    /**
     * Récupérer tous les membres d'une classe pour la gestion des responsables
     */
    public function getMembresForResponsables(Request $request, $classeId)
    {
        try {
            $classe = Classe::findOrFail($classeId);

            // Query de base pour tous les membres de la classe
            $query = $classe->membres()
                ->select('id', 'prenom', 'nom', 'email', 'telephone_1', 'date_naissance', 'statut_membre');

            // Filtres optionnels
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('prenom', 'like', "%{$search}%")
                        ->orWhere('nom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Tri
            $query->orderBy('prenom')->orderBy('nom');

            // Pagination pour éviter la surcharge
            $perPage = $request->get('per_page', 15);
            $membres = $query->paginate($perPage);

            // Ajouter les informations de responsabilité existantes
            $responsables = $classe->responsables ?? [];
            $responsablesIds = collect($responsables)->pluck('id')->toArray();

            $membres->through(function ($membre) use ($responsables, $responsablesIds) {
                $membre->is_responsable = in_array($membre->id, $responsablesIds);

                if ($membre->is_responsable) {
                    $responsableData = collect($responsables)->firstWhere('id', $membre->id);
                    $membre->responsabilite = $responsableData['responsabilite'] ?? null;
                    $membre->superieur = $responsableData['superieur'] ?? false;
                } else {
                    $membre->responsabilite = null;
                    $membre->superieur = false;
                }

                return $membre;
            });

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'classe' => [
                            'id' => $classe->id,
                            'nom' => $classe->nom,
                            'nombre_inscrits' => $classe->nombre_inscrits
                        ],
                        'membres' => $membres,
                        'types_responsabilite' => $this->getTypesResponsabilite()
                    ],
                    'message' => 'Membres récupérés avec succès'
                ]);
            }

            return view('components.private.classes.membres-responsables', compact('classe', 'membres'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Classe non trouvée'
                ], 404);
            }

            return back()->withErrors(['error' => 'Classe non trouvée']);
        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des membres',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des membres']);
        }
    }

    /**
     * Mettre à jour les responsabilités d'un membre
     */
    // public function updateResponsabilite(Request $request, $classeId)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'user_id' => 'required|uuid|exists:users,id',
    //         'action' => 'required|in:add,remove,update',
    //         'responsabilite' => 'required_if:action,add,update|nullable|string',
    //         'superieur' => 'nullable|boolean'
    //     ]);

    //     if ($validator->fails()) {
    //         if ($this->isApiRequest($request)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Erreurs de validation',
    //                 'errors' => $validator->errors()
    //             ], 422);
    //         }

    //         return back()->withErrors($validator);
    //     }

    //     try {
    //         $classe = Classe::findOrFail($classeId);
    //         $user = User::findOrFail($request->user_id);
    //         $currentUser = Auth::user();

    //         // Vérifier si l'utilisateur est membre de la classe
    //         if ($user->classe_id !== $classe->id) {
    //             $message = 'L\'utilisateur n\'est pas membre de cette classe';

    //             if ($this->isApiRequest($request)) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => $message
    //                 ], 400);
    //             }

    //             return back()->withErrors(['error' => $message]);
    //         }

    //         // Vérifier les permissions
    //         if (!$classe->peutGererResponsables($currentUser->id) && !$currentUser->can('classes.update')) {
    //             $message = 'Vous n\'avez pas les permissions pour gérer les responsables de cette classe';

    //             if ($this->isApiRequest($request)) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => $message
    //                 ], 403);
    //             }

    //             return back()->withErrors(['error' => $message]);
    //         }

    //         DB::beginTransaction();

    //         $responsables = $classe->responsables ?? [];
    //         $userIndex = null;

    //         // Trouver l'utilisateur dans la liste des responsables
    //         foreach ($responsables as $index => $responsable) {
    //             if ($responsable['id'] === $request->user_id) {
    //                 $userIndex = $index;
    //                 break;
    //             }
    //         }

    //         switch ($request->action) {
    //             case 'add':
    //                 if ($userIndex === null) {
    //                     // Vérifier s'il y a déjà un supérieur si on veut en ajouter un
    //                     if ($request->get('superieur', false)) {
    //                         foreach ($responsables as &$responsable) {
    //                             $responsable['superieur'] = false;
    //                         }
    //                     }

    //                     $responsables[] = [
    //                         'id' => $request->user_id,
    //                         'responsabilite' => $request->responsabilite,
    //                         'superieur' => $request->get('superieur', false)
    //                     ];
    //                     $message = 'Responsabilité ajoutée avec succès';
    //                 } else {
    //                     $message = 'L\'utilisateur est déjà responsable';
    //                 }
    //                 break;

    //             case 'update':
    //                 if ($userIndex !== null) {
    //                     // Vérifier s'il y a déjà un supérieur si on veut en ajouter un
    //                     if ($request->get('superieur', false)) {
    //                         foreach ($responsables as &$responsable) {
    //                             $responsable['superieur'] = false;
    //                         }
    //                     }

    //                     $responsables[$userIndex] = [
    //                         'id' => $request->user_id,
    //                         'responsabilite' => $request->responsabilite,
    //                         'superieur' => $request->get('superieur', false)
    //                     ];
    //                     $message = 'Responsabilité mise à jour avec succès';
    //                 } else {
    //                     $message = 'L\'utilisateur n\'est pas responsable';
    //                 }
    //                 break;

    //             case 'remove':
    //                 if ($userIndex !== null) {
    //                     unset($responsables[$userIndex]);
    //                     $responsables = array_values($responsables); // Réindexer
    //                     $message = 'Responsabilité retirée avec succès';
    //                 } else {
    //                     $message = 'L\'utilisateur n\'est pas responsable';
    //                 }
    //                 break;
    //         }

    //         $classe->update(['responsables' => $responsables]);

    //         DB::commit();

    //         if ($this->isApiRequest($request)) {
    //             return response()->json([
    //                 'success' => true,
    //                 'message' => $message
    //             ]);
    //         }

    //         return back()->with('success', $message);

    //     } catch (\Exception $e) {
    //         DB::rollBack();

    //         if ($this->isApiRequest($request)) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Erreur lors de la mise à jour',
    //                 'error' => $e->getMessage()
    //             ], 500);
    //         }

    //         return back()->withErrors(['error' => 'Erreur lors de la mise à jour']);
    //     }
    // }

     /**
     * Mettre à jour les responsabilités d'un membre (version corrigée)
     */
    public function updateResponsabilite(Request $request, $classeId)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|uuid|exists:users,id',
            'action' => 'required|in:add,remove,update',
            'responsabilite' => 'required_if:action,add,update|nullable|string',
            'superieur' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        try {
            $classe = Classe::findOrFail($classeId);
            $user = User::findOrFail($request->user_id);
            $currentUser = Auth::user();

            // Vérifier si l'utilisateur est membre de la classe
            if ($user->classe_id !== $classe->id) {
                $message = 'L\'utilisateur n\'est pas membre de cette classe';

                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }

                return back()->withErrors(['error' => $message]);
            }

            // Vérifier les permissions
            if (!$classe->peutGererResponsables($currentUser->id) && !$currentUser->can('classes.update')) {
                $message = 'Vous n\'avez pas les permissions pour gérer les responsables de cette classe';

                if ($this->isApiRequest($request)) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 403);
                }

                return back()->withErrors(['error' => $message]);
            }

            DB::beginTransaction();

            $responsables = $classe->responsables ?? [];
            $userIndex = null;
            $etaitResponsable = false;

            // Trouver l'utilisateur dans la liste des responsables
            foreach ($responsables as $index => $responsable) {
                if ($responsable['id'] === $request->user_id) {
                    $userIndex = $index;
                    $etaitResponsable = true;
                    break;
                }
            }

            switch ($request->action) {
                case 'add':
                    if ($userIndex === null) {
                        // Vérifier s'il y a déjà un supérieur si on veut en ajouter un
                        if ($request->get('superieur', false)) {
                            foreach ($responsables as &$responsable) {
                                $responsable['superieur'] = false;
                            }
                        }

                        $responsables[] = [
                            'id' => $request->user_id,
                            'responsabilite' => $request->responsabilite,
                            'superieur' => $request->get('superieur', false)
                        ];

                        $message = 'Responsabilité ajoutée avec succès';

                        // Note: L'utilisateur est déjà membre, donc pas besoin d'incrémenter
                    } else {
                        $message = 'L\'utilisateur est déjà responsable';
                    }
                    break;

                case 'update':
                    if ($userIndex !== null) {
                        // Vérifier s'il y a déjà un supérieur si on veut en ajouter un
                        if ($request->get('superieur', false)) {
                            foreach ($responsables as &$responsable) {
                                $responsable['superieur'] = false;
                            }
                        }

                        $responsables[$userIndex] = [
                            'id' => $request->user_id,
                            'responsabilite' => $request->responsabilite,
                            'superieur' => $request->get('superieur', false)
                        ];
                        $message = 'Responsabilité mise à jour avec succès';
                    } else {
                        $message = 'L\'utilisateur n\'est pas responsable';
                    }
                    break;

                case 'remove':
                    if ($userIndex !== null) {
                        unset($responsables[$userIndex]);
                        $responsables = array_values($responsables); // Réindexer
                        $message = 'Responsabilité retirée avec succès';

                        // Vérifier s'il faut retirer l'utilisateur de la classe
                        if (!$this->verifierSiMembreNormal($user, $classe)) {
                            $user->update(['classe_id' => null]);
                            $classe->decrementerInscrits();
                        }
                    } else {
                        $message = 'L\'utilisateur n\'est pas responsable';
                    }
                    break;
            }

            $classe->update(['responsables' => $responsables]);

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la mise à jour']);
        }
    }




    // private function exportToExcel($classe)
    // {

    // }

    // private function exportToPdf($classe)
    // {

    // }

    /**
     * Export Excel avec mise en forme professionnelle
     */
    private function exportToExcel($classe)
    {
        // Vérifier que PhpSpreadsheet est installé
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            throw new \Exception('PhpSpreadsheet n\'est pas installé. Installez-le avec: composer require phpoffice/phpspreadsheet');
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Configuration de la page
        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // Marges
        $sheet->getPageMargins()
            ->setTop(0.75)
            ->setRight(0.7)
            ->setBottom(0.75)
            ->setLeft(0.7);

        // Préparer les données
        $responsables = $classe->responsables();
        $membres = $classe->membres()->orderBy('prenom')->orderBy('nom')->get();

        // Générer le contenu Excel
        $this->generateExcelContent($sheet, $classe, $responsables, $membres);

        // Configuration pour l'export
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        $filename = 'classe-' . \Str::slug($classe->nom) . '-' . now()->format('Y-m-d') . '.xlsx';

        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ];

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, $headers);
    }

    /**
     * Export PDF avec mise en forme professionnelle
     */
    private function exportToPdf($classe)
    {
        // Vérifier que DomPDF est installé
        if (!class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            throw new \Exception('DomPDF n\'est pas installé. Installez-le avec: composer require barryvdh/laravel-dompdf');
        }

        $responsables = $classe->responsables();
        $membres = $classe->membres()->orderBy('prenom')->orderBy('nom')->get();

        // Préparer les données pour la vue
        $data = [
            'classe' => $classe,
            'responsables' => $responsables,
            'membres' => $membres,
            'dateGeneration' => now()->format('d/m/Y à H:i'),
            'stats' => [
                'total_membres' => $classe->nombre_inscrits,
                'total_responsables' => $responsables->count(),
                'membres_simples' => $membres->count() - $responsables->count(),
                'ages_couverts' => ($classe->age_minimum && $classe->age_maximum)
                    ? ($classe->age_maximum - $classe->age_minimum) . ' ans'
                    : 'Libre'
            ]
        ];

        // Générer le PDF à partir de la vue
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.classes.classe-pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = 'classe-' . \Str::slug($classe->nom) . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Génère le contenu Excel
     */
    private function generateExcelContent($sheet, $classe, $responsables, $membres)
    {
        $currentRow = 1;

        // En-tête du document
        $sheet->setCellValue('A1', 'ÉGLISE - GESTION DES CLASSES');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['rgb' => '2563EB']
            ]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Informations de la classe
        $currentRow = 3;
        $this->addClasseInfo($sheet, $classe, $currentRow);

        // Responsables
        $currentRow += 10;
        $this->addResponsables($sheet, $classe, $responsables, $currentRow);

        // Membres
        $currentRow += $responsables->count() + 5;
        $this->addMembres($sheet, $membres, $currentRow);

        // Ajuster la largeur des colonnes
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Bordures pour toutes les cellules utilisées
        $lastRow = $currentRow + $membres->count() + 5;
        $sheet->getStyle('A1:H' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB']
                ]
            ]
        ]);
    }

    /**
     * Ajoute les informations de la classe dans Excel
     */
    private function addClasseInfo($sheet, $classe, &$currentRow)
    {
        $sheet->setCellValue('A' . $currentRow, 'DÉTAILS DE LA CLASSE');
        $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
        $sheet->getStyle('A' . $currentRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1F2937']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'E5E7EB']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);

        $currentRow += 2;
        $classeDetails = [
            ['Nom de la classe:', $classe->nom],
            ['Description:', $classe->description ?: 'Aucune description'],
            ['Tranche d\'âge:', $classe->tranche_age ?: 'Non spécifiée'],
            ['Âge minimum:', $classe->age_minimum ? $classe->age_minimum . ' ans' : 'Non spécifié'],
            ['Âge maximum:', $classe->age_maximum ? $classe->age_maximum . ' ans' : 'Non spécifié'],
            ['Nombre total de membres:', $classe->nombre_inscrits],
            ['Date de création:', $classe->created_at->format('d/m/Y H:i')]
        ];

        foreach ($classeDetails as $detail) {
            $sheet->setCellValue('A' . $currentRow, $detail[0]);
            $sheet->setCellValue('C' . $currentRow, $detail[1]);
            $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
            $currentRow++;
        }
    }

    /**
     * Ajoute les responsables dans Excel
     */
    private function addResponsables($sheet, $classe, $responsables, &$currentRow)
    {
        $sheet->setCellValue('A' . $currentRow, 'RESPONSABLES DE LA CLASSE');
        $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
        $sheet->getStyle('A' . $currentRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '059669']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);

        $currentRow++;

        if ($responsables->count() > 0) {
            // En-têtes
            $headers = ['Nom Complet', 'Responsabilité', 'Supérieur', 'Email', 'Téléphone', 'Téléphone 2', 'Ville'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $currentRow, $header);
                $sheet->getStyle($col . $currentRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '047857']],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                ]);
                $col++;
            }

            $currentRow++;
            foreach ($responsables as $responsable) {
                $responsableData = collect($classe->responsables)->firstWhere('id', $responsable->id);
                $sheet->setCellValue('A' . $currentRow, $responsable->nom_complet);
                $sheet->setCellValue('B' . $currentRow, ucfirst($responsableData['responsabilite'] ?? ''));
                $sheet->setCellValue('C' . $currentRow, ($responsableData['superieur'] ?? false) ? 'Oui' : 'Non');
                $sheet->setCellValue('D' . $currentRow, $responsable->email ?: 'Non renseigné');
                $sheet->setCellValue('E' . $currentRow, $responsable->telephone_1 ?: 'Non renseigné');
                $sheet->setCellValue('F' . $currentRow, $responsable->telephone_2 ?: 'Non renseigné');
                $sheet->setCellValue('G' . $currentRow, $responsable->ville ?: 'Non renseignée');

                if ($currentRow % 2 == 0) {
                    $sheet->getStyle('A' . $currentRow . ':G' . $currentRow)->applyFromArray([
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'F9FAFB']]
                    ]);
                }
                $currentRow++;
            }
        } else {
            $currentRow++;
            $sheet->setCellValue('A' . $currentRow, 'Aucun responsable assigné');
            $sheet->mergeCells('A' . $currentRow . ':G' . $currentRow);
        }
    }

    /**
     * Ajoute les membres dans Excel
     */
    private function addMembres($sheet, $membres, &$currentRow)
    {
        $sheet->setCellValue('A' . $currentRow, 'MEMBRES DE LA CLASSE');
        $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
        $sheet->getStyle('A' . $currentRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'DC2626']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);

        $currentRow++;

        if ($membres->count() > 0) {
            // En-têtes
            $headers = ['Nom Complet', 'Statut', 'Email', 'Téléphone 1', 'Téléphone 2', 'Ville', 'Âge', 'Sexe'];
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . $currentRow, $header);
                $sheet->getStyle($col . $currentRow)->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'B91C1C']],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
                ]);
                $col++;
            }

            $currentRow++;
            foreach ($membres as $membre) {
                $age = $membre->date_naissance ? $membre->date_naissance->diffInYears(now()) . ' ans' : 'Non renseigné';

                $sheet->setCellValue('A' . $currentRow, $membre->nom_complet);
                $sheet->setCellValue('B' . $currentRow, ucfirst($membre->statut_membre));
                $sheet->setCellValue('C' . $currentRow, $membre->email ?: 'Non renseigné');
                $sheet->setCellValue('D' . $currentRow, $membre->telephone_1 ?: 'Non renseigné');
                $sheet->setCellValue('E' . $currentRow, $membre->telephone_2 ?: 'Non renseigné');
                $sheet->setCellValue('F' . $currentRow, $membre->ville ?: 'Non renseignée');
                $sheet->setCellValue('G' . $currentRow, $age);
                $sheet->setCellValue('H' . $currentRow, ucfirst($membre->sexe ?? 'N/A'));

                if ($currentRow % 2 == 0) {
                    $sheet->getStyle('A' . $currentRow . ':H' . $currentRow)->applyFromArray([
                        'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'FEF2F2']]
                    ]);
                }
                $currentRow++;
            }
        } else {
            $currentRow++;
            $sheet->setCellValue('A' . $currentRow, 'Aucun membre inscrit');
            $sheet->mergeCells('A' . $currentRow . ':H' . $currentRow);
        }
    }


}
