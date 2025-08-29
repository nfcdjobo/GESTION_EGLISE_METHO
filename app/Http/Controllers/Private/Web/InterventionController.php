<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\User;
use App\Models\Culte;
use App\Models\Reunion;
use Illuminate\View\View;
use App\Models\Intervention;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InterventionController extends Controller
{
    /**
     * Afficher la liste des interventions avec filtrage et pagination
     */
    public function index(Request $request)
    {
        $query = Intervention::with(['culte', 'reunion', 'intervenant'])
                             ->orderBy('created_at', 'desc');

        // Filtres disponibles
        if ($request->filled('culte_id')) {
            $query->pourCulte($request->culte_id);
        }

        if ($request->filled('reunion_id')) {
            $query->pourReunion($request->reunion_id);
        }

        if ($request->filled('intervenant_id')) {
            $query->parIntervenant($request->intervenant_id);
        }

        if ($request->filled('type_intervention')) {
            $query->type($request->type_intervention);
        }

        if ($request->filled('statut')) {
            $query->statut($request->statut);
        }

        // Recherche par titre ou description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('passage_biblique', 'like', "%{$search}%");
            });
        }

        // Tri par ordre de passage si demandé
        if ($request->boolean('ordre_passage')) {
            $query->ordonneesParPassage();
        }

        $interventions = $query->paginate($request->get('per_page', 15));

        // Données pour les filtres
        $cultes = Culte::select('id', 'titre')->orderBy('titre')->get();
        $reunions = Reunion::select('id', 'titre')->orderBy('titre')->get();
        $intervenants = User::select('id', 'nom')->orderBy('nom')->get();

        $data = [
            'interventions' => $interventions,
            'cultes' => $cultes,
            'reunions' => $reunions,
            'intervenants' => $intervenants,
            'filters' => [
                'types_intervention' => Intervention::TYPES_INTERVENTION,
                'statuts' => Intervention::STATUTS
            ],
            'currentFilters' => $request->only(['culte_id', 'reunion_id', 'intervenant_id', 'type_intervention', 'statut', 'search'])
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('components.private.interventions.index', $data);
    }

    /**
     * Afficher le formulaire de création d'une nouvelle intervention
     */
    public function create(Request $request)
    {
        $cultes = Culte::select('id', 'titre', 'date_culte')->orderBy('date_culte', 'desc')->get();
        $reunions = Reunion::select('id', 'titre', 'date_reunion')->orderBy('date_reunion', 'desc')->get();
        $intervenants = User::select('id', 'nom', 'prenom', 'telephone_1')->orderBy('nom')->get();

        $data = [
            'cultes' => $cultes,
            'reunions' => $reunions,
            'intervenants' => $intervenants,
            'types_intervention' => Intervention::TYPES_INTERVENTION,
            'statuts' => Intervention::STATUTS,
            'intervention' => new Intervention() // Pour le formulaire
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('components.private.interventions.create', $data);
    }

    /**
     * Enregistrer une nouvelle intervention
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'culte_id' => 'nullable|uuid|exists:cultes,id',
            'reunion_id' => 'nullable|uuid|exists:reunions,id',
            'intervenant_id' => 'required|uuid|exists:users,id',
            'titre' => 'required|string|max:200',
            'type_intervention' => [
                'required',
                Rule::in(array_keys(Intervention::TYPES_INTERVENTION))
            ],
            'heure_debut' => 'nullable|date_format:H:i',
            'duree_minutes' => 'nullable|integer|min:1|max:480',
            'ordre_passage' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'passage_biblique' => 'nullable|string|max:300',
            'statut' => [
                'nullable',
                Rule::in(array_keys(Intervention::STATUTS))
            ]
        ]);

        // Validation personnalisée : au moins culte_id ou reunion_id requis
        $validator->after(function ($validator) use ($request) {
            if (!$request->filled('culte_id') && !$request->filled('reunion_id')) {
                $validator->errors()->add('evenement', 'Une intervention doit être liée soit à un culte, soit à une réunion.');
            }

            // Vérifier que culte_id et reunion_id ne sont pas tous deux remplis
            if ($request->filled('culte_id') && $request->filled('reunion_id')) {
                $validator->errors()->add('evenement', 'Une intervention ne peut être liée qu\'à un seul événement (culte ou réunion).');
            }
        });

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        try {
            $intervention = Intervention::create([
                'culte_id' => $request->culte_id,
                'reunion_id' => $request->reunion_id,
                'intervenant_id' => $request->intervenant_id,
                'titre' => $request->titre,
                'type_intervention' => $request->type_intervention,
                'heure_debut' => $request->heure_debut,
                'duree_minutes' => $request->duree_minutes ?? 15,
                'ordre_passage' => $request->ordre_passage,
                'description' => $request->description,
                'passage_biblique' => $request->passage_biblique,
                'statut' => $request->statut ?? 'prevue'
            ]);

            $intervention->load(['culte', 'reunion', 'intervenant']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Intervention créée avec succès',
                    'data' => $intervention
                ], 201);
            }

            return redirect()->route('private.interventions.show', $intervention)
                           ->with('success', 'Intervention créée avec succès');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'intervention',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withError('Erreur lors de la création de l\'intervention: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Afficher une intervention spécifique
     */
    public function show(Request $request, string $id)
    {
        try {
            $intervention = Intervention::with(['culte', 'reunion', 'intervenant'])
                                       ->findOrFail($id);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $intervention
                ]);
            }

            return view('components.private.interventions.show', compact('intervention'));

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Intervention introuvable'
                ], 404);
            }

            abort(404, 'Intervention introuvable');
        }
    }

    /**
     * Afficher le formulaire d'édition d'une intervention
     */
    public function edit(Request $request, string $id)
    {
        try {
            $intervention = Intervention::with(['culte', 'reunion', 'intervenant'])
                                       ->findOrFail($id);

            $cultes = Culte::select('id', 'nom', 'date_culte')->orderBy('date_culte', 'desc')->get();
            $reunions = Reunion::select('id', 'nom', 'date_reunion')->orderBy('date_reunion', 'desc')->get();
            $intervenants = User::select('id', 'name', 'email')->orderBy('name')->get();

            $data = [
                'intervention' => $intervention,
                'cultes' => $cultes,
                'reunions' => $reunions,
                'intervenants' => $intervenants,
                'types_intervention' => Intervention::TYPES_INTERVENTION,
                'statuts' => Intervention::STATUTS
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return view('components.private.interventions.edit', $data);

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Intervention introuvable'
                ], 404);
            }

            abort(404, 'Intervention introuvable');
        }
    }

    /**
     * Mettre à jour une intervention
     */
    public function update(Request $request, string $id)
    {
        try {
            $intervention = Intervention::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'culte_id' => 'nullable|uuid|exists:cultes,id',
                'reunion_id' => 'nullable|uuid|exists:reunions,id',
                'intervenant_id' => 'required|uuid|exists:users,id',
                'titre' => 'required|string|max:200',
                'type_intervention' => [
                    'required',
                    Rule::in(array_keys(Intervention::TYPES_INTERVENTION))
                ],
                'heure_debut' => 'nullable|date_format:H:i',
                'duree_minutes' => 'nullable|integer|min:1|max:480',
                'ordre_passage' => 'nullable|integer|min:1',
                'description' => 'nullable|string',
                'passage_biblique' => 'nullable|string|max:300',
                'statut' => [
                    'required',
                    Rule::in(array_keys(Intervention::STATUTS))
                ]
            ]);

            // Validation personnalisée
            $validator->after(function ($validator) use ($request) {
                if (!$request->filled('culte_id') && !$request->filled('reunion_id')) {
                    $validator->errors()->add('evenement', 'Une intervention doit être liée soit à un culte, soit à une réunion.');
                }

                if ($request->filled('culte_id') && $request->filled('reunion_id')) {
                    $validator->errors()->add('evenement', 'Une intervention ne peut être liée qu\'à un seul événement (culte ou réunion).');
                }
            });

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreurs de validation',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return back()->withErrors($validator)->withInput();
            }

            $intervention->update($request->only([
                'culte_id', 'reunion_id', 'intervenant_id', 'titre', 'type_intervention',
                'heure_debut', 'duree_minutes', 'ordre_passage', 'description',
                'passage_biblique', 'statut'
            ]));

            $intervention->load(['culte', 'reunion', 'intervenant']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Intervention mise à jour avec succès',
                    'data' => $intervention
                ]);
            }

            return redirect()->route('private.interventions.show', $intervention)
                           ->with('success', 'Intervention mise à jour avec succès');

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Intervention introuvable'
                ], 404);
            }

            abort(404, 'Intervention introuvable');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de l\'intervention',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withError('Erreur lors de la mise à jour: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Supprimer une intervention
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $intervention = Intervention::findOrFail($id);
            $intervention->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Intervention supprimée avec succès'
                ]);
            }

            return redirect()->route('private.interventions.index')
                           ->with('success', 'Intervention supprimée avec succès');

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Intervention introuvable'
                ], 404);
            }

            return back()->withError('Intervention introuvable');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de l\'intervention',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withError('Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Restaurer une intervention supprimée (soft delete)
     */
    public function restore(Request $request, string $id)
    {
        try {
            $intervention = Intervention::withTrashed()->findOrFail($id);

            if (!$intervention->trashed()) {
                $message = 'Cette intervention n\'est pas supprimée';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }

                return back()->withError($message);
            }

            $intervention->restore();
            $intervention->load(['culte', 'reunion', 'intervenant']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Intervention restaurée avec succès',
                    'data' => $intervention
                ]);
            }

            return redirect()->route('private.interventions.show', $intervention)
                           ->with('success', 'Intervention restaurée avec succès');

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Intervention introuvable'
                ], 404);
            }

            return back()->withError('Intervention introuvable');
        }
    }

    /**
     * Changer le statut d'une intervention
     */
    public function changeStatut(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'statut' => [
                'required',
                Rule::in(array_keys(Intervention::STATUTS))
            ]
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Statut invalide',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        try {
            $intervention = Intervention::findOrFail($id);
            $intervention->update(['statut' => $request->statut]);
            $intervention->load(['culte', 'reunion', 'intervenant']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Statut mis à jour avec succès',
                    'data' => $intervention
                ]);
            }

            return back()->with('success', 'Statut mis à jour avec succès');

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Intervention introuvable'
                ], 404);
            }

            return back()->withError('Intervention introuvable');
        }
    }

    /**
     * Obtenir les interventions d'un événement spécifique ordonnées
     */
    public function parEvenement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'culte_id' => 'nullable|uuid|exists:cultes,id',
            'reunion_id' => 'nullable|uuid|exists:reunions,id'
        ]);

        $validator->after(function ($validator) use ($request) {
            if (!$request->filled('culte_id') && !$request->filled('reunion_id')) {
                $validator->errors()->add('evenement', 'Veuillez spécifier soit culte_id, soit reunion_id.');
            }
        });

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paramètres invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        $query = Intervention::with(['intervenant'])
                            ->ordonneesParPassage();

        if ($request->filled('culte_id')) {
            $query->pourCulte($request->culte_id);
            $evenement = Culte::find($request->culte_id);
        } else {
            $query->pourReunion($request->reunion_id);
            $evenement = Reunion::find($request->reunion_id);
        }

        $interventions = $query->get();

        $data = [
            'interventions' => $interventions,
            'evenement' => $evenement
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('components.private.interventions.par-evenement', $data);
    }

    /**
     * Afficher les interventions supprimées (corbeille)
     */
    public function trash(Request $request): View|JsonResponse
    {
        $interventions = Intervention::onlyTrashed()
                                   ->with(['culte', 'reunion', 'intervenant'])
                                   ->orderBy('deleted_at', 'desc')
                                   ->paginate(15);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $interventions
            ]);
        }

        return view('components.private.interventions.trash', compact('interventions'));
    }
}
