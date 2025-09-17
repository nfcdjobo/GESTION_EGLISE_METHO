<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\User;
use App\Models\Programme;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ProgrammeController extends Controller
{

    public function __construct()
{
    $this->middleware('auth');
    $this->middleware('permission:programmes.read')->only(['index', 'show', 'actifs', 'planning', 'statistiques']);
    $this->middleware('permission:programmes.create')->only(['create', 'store', 'dupliquer']);
    $this->middleware('permission:programmes.update')->only(['edit', 'update', 'activer', 'suspendre', 'terminer', 'annuler']);
    $this->middleware('permission:programmes.delete')->only(['destroy']);
}

    /**
     * Vérifier si c'est une requête API
     */
    private function isApiRequest(Request $request): bool
    {
        return $request->wantsJson() ||
               $request->ajax() ||
               $request->header('Accept') === 'application/json' ||
               $request->has('api');
    }

    /**
     * Formater la réponse selon le type de requête
     */
    private function response($data, string $view = null, int $status = 200)
    {
        if ($this->isApiRequest(request())) {
            return response()->json($data, $status);
        }

        return $view ? view($view, $data) : redirect()->back()->with($data);
    }

    /**
     * Afficher la liste des programmes
     */
    public function index(Request $request)
    {
        $query = Programme::with(['responsablePrincipal', 'createurMembres']);

        // Filtres
        if ($request->filled('type')) {
            $query->parType($request->type);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('audience')) {
            $query->parAudience($request->audience);
        }

        if ($request->filled('frequence')) {
            $query->parFrequence($request->frequence);
        }

        if ($request->filled('responsable')) {
            $query->where('responsable_principal_id', $request->responsable);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom_programme', 'like', "%{$search}%")->orWhere('code_programme', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tri
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $programmes = $query->paginate(15)->appends(request()->query());

        // Données pour les filtres
        $responsables = User::select('id', 'prenom', 'nom')->whereIn('id', Programme::pluck('responsable_principal_id'))->get();

        return view('components.private.programmes.index', compact('programmes', 'responsables'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(): View
    {
        $responsables = User::select('id', 'prenom', 'nom')->get();

        return view('components.private.programmes.create', compact('responsables'));
    }

    /**
     * Enregistrer un nouveau programme
     */
    public function store(Request $request)
    {
        $validated = $this->validateProgramme($request);

        try {
            DB::beginTransaction();

            $validated['cree_par'] = auth()->id();
            $programme = Programme::create($validated);

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Programme créé avec succès.',
                    'data' => $programme->load(['responsablePrincipal', 'createurMembres'])
                ], 201);
            }

            return redirect()
                ->route('private.programmes.show', $programme)
                ->with('success', 'Programme créé avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du programme.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du programme: ' . $e->getMessage());
        }
    }

    /**
     * Afficher un programme
     */
    public function show(Programme $programme, Request $request)
    {
        $programme->load(['responsablePrincipal', 'createurMembres', 'modificateurMembres']);

        if ($this->isApiRequest($request)) {
            return response()->json([
                'success' => true,
                'data' => $programme
            ]);
        }

        return view('components.private.programmes.show', compact('programme'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Programme $programme): View
    {
        if (!$programme->peutEtreModifie()) {
            abort(403, 'Ce programme ne peut plus être modifié.');
        }

        $responsables = User::select('id', 'prenom', 'nom')->get();

        return view('components.private.programmes.edit', compact('programme', 'responsables'));
    }

    /**
     * Mettre à jour un programme
     */
    public function update(Request $request, Programme $programme)
    {
        if (!$programme->peutEtreModifie()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce programme ne peut plus être modifié.'
                ], 403);
            }

            return back()->with('error', 'Ce programme ne peut plus être modifié.');
        }

        $validated = $this->validateProgramme($request, $programme->id);

        try {
            DB::beginTransaction();

            $validated['modifie_par'] = auth()->id();
            $programme->update($validated);

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Programme mis à jour avec succès.',
                    'data' => $programme->fresh()->load(['responsablePrincipal', 'createurMembres'])
                ]);
            }

            return redirect()
                ->route('private.programmes.show', $programme)
                ->with('success', 'Programme mis à jour avec succès.');

        } catch (\Exception $e) {
            DB::rollBack();
dd( $e->getMessage());
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un programme
     */
    public function destroy(Programme $programme, Request $request)
    {
        if (!$programme->peutEtreModifie()) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce programme ne peut pas être supprimé.'
                ], 403);
            }
            return back()->with('error', 'Ce programme ne peut pas être supprimé.');
        }

        try {
            $programme->delete();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Programme supprimé avec succès.'
                ]);
            }

            return redirect()
                ->route('private.programmes.index')
                ->with('success', 'Programme supprimé avec succès.');

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Activer un programme
     */
    public function activer(Programme $programme, Request $request)
    {
        try {
            $programme->activer();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Programme activé avec succès.',
                    'data' => $programme->fresh()
                ]);
            }

            return back()->with('success', 'Programme activé avec succès.');

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'activation.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'activation: ' . $e->getMessage());
        }
    }

    /**
     * Suspendre un programme
     */
    public function suspendre(Programme $programme, Request $request)
    {
        try {
            $programme->suspendre();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Programme suspendu avec succès.',
                    'data' => $programme->fresh()
                ]);
            }

            return back()->with('success', 'Programme suspendu avec succès.');

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suspension.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suspension: ' . $e->getMessage());
        }
    }

    /**
     * Terminer un programme
     */
    public function terminer(Programme $programme, Request $request)
    {
        try {
            $programme->terminer();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Programme terminé avec succès.',
                    'data' => $programme->fresh()
                ]);
            }

            return back()->with('success', 'Programme terminé avec succès.');

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la finalisation.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la finalisation: ' . $e->getMessage());
        }
    }

    /**
     * Annuler un programme
     */
    public function annuler(Programme $programme, Request $request)
    {
        try {
            $programme->annuler();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Programme annulé avec succès.',
                    'data' => $programme->fresh()
                ]);
            }

            return back()->with('success', 'Programme annulé avec succès.');

        } catch (\Exception $e) {
            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'annulation.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'annulation: ' . $e->getMessage());
        }
    }

    /**
     * Programmes actifs (API)
     */
    public function actifs(): JsonResponse
    {
        $programmes = Programme::actifs()
            ->with('responsablePrincipal')
            ->select('id', 'nom_programme', 'code_programme', 'type_programme', 'heure_debut', 'heure_fin')
            ->get();

        return response()->json($programmes);
    }

    /**
     * Planning hebdomadaire
     */
    public function planning(Request $request)
    {
        $programmes = Programme::enCours()
            ->with('responsablePrincipal')
            ->whereIn('frequence', ['quotidien', 'hebdomadaire'])
            ->whereNotNull('jours_semaine')
            ->orderBy('heure_debut')
            ->get();

        // Organiser par jour de la semaine
        $planning = [];
        $jours = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];

        foreach ($jours as $numero => $nom) {
            $planning[$numero] = [
                'nom' => $nom,
                'programmes' => $programmes->filter(function ($programme) use ($numero) {
                    return in_array($numero, $programme->jours_semaine ?? []);
                })->values()
            ];
        }

        if ($this->isApiRequest($request)) {
            return response()->json([
                'success' => true,
                'data' => $planning
            ]);
        }

        return view('components.private.programmes.planning', compact('planning'));
    }

    /**
     * Statistiques des programmes
     */
    public function statistiques(Request $request)
    {
        $stats = [
            'total' => Programme::count(),
            'actifs' => Programme::actifs()->count(),
            'planifies' => Programme::where('statut', 'planifie')->count(),
            'suspendus' => Programme::where('statut', 'suspendu')->count(),
            'termines' => Programme::where('statut', 'termine')->count(),
        ];

        $parType = Programme::select('type_programme', DB::raw('count(*) as total'))
            ->groupBy('type_programme')
            ->get()
            ->pluck('total', 'type_programme');

        $parAudience = Programme::select('audience_cible', DB::raw('count(*) as total'))
            ->groupBy('audience_cible')
            ->get()
            ->pluck('total', 'audience_cible');

        if ($this->isApiRequest($request)) {
            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'par_type' => $parType,
                    'par_audience' => $parAudience
                ]
            ]);
        }

        return view('components.private.programmes.statistiques', compact('stats', 'parType', 'parAudience'));
    }

    /**
     * Dupliquer un programme
     */
    public function dupliquer(Programme $programme, Request $request)
    {
        try {
            DB::beginTransaction();

            $nouveauProgramme = $programme->replicate();
            $nouveauProgramme->nom_programme = $programme->nom_programme . ' (Copie)';
            $nouveauProgramme->code_programme = null; // Sera généré automatiquement
            $nouveauProgramme->statut = 'planifie';
            $nouveauProgramme->cree_par = auth()->id();
            $nouveauProgramme->save();

            DB::commit();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Programme dupliqué avec succès.',
                    'data' => $nouveauProgramme->load(['responsablePrincipal', 'createurMembres'])
                ], 201);
            }

            return redirect()
                ->route('private.programmes.edit', $nouveauProgramme)
                ->with('success', 'Programme dupliqué avec succès. Vous pouvez maintenant le modifier.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->isApiRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la duplication.',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->with('error', 'Erreur lors de la duplication: ' . $e->getMessage());
        }
    }

    /**
     * Validation des données du programme
     */
    private function validateProgramme(Request $request, $programmeId = null): array
    {
        return $request->validate([
            'nom_programme' => 'required|string|max:200',
            'description' => 'nullable|string',
            'code_programme' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('programmes')->ignore($programmeId)
            ],
            'type_programme' => 'required|in:' . implode(',', array_keys(Programme::TYPES_PROGRAMME)),
            'frequence' => 'required|in:' . implode(',', array_keys(Programme::FREQUENCES)),
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin' => 'nullable|date_format:H:i|after:heure_debut',
            'jours_semaine' => 'nullable|array',
            'jours_semaine.*' => 'integer|between:1,7',
            'lieu_principal' => 'nullable|string|max:200',
            'responsable_principal_id' => 'nullable|exists:users,id',
            'audience_cible' => 'required|in:' . implode(',', array_keys(Programme::AUDIENCES)),
            'statut' => 'required|in:' . implode(',', array_keys(Programme::STATUTS)),
            'notes' => 'nullable|string',
        ], [
            'nom_programme.required' => 'Le nom du programme est obligatoire.',
            'type_programme.required' => 'Le type de programme est obligatoire.',
            'frequence.required' => 'La fréquence est obligatoire.',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
            'heure_fin.after' => 'L\'heure de fin doit être postérieure à l\'heure de début.',
            'code_programme.unique' => 'Ce code programme existe déjà.',
            'responsable_principal_id.exists' => 'Le responsable sélectionné n\'existe pas.',
        ]);
    }
}
