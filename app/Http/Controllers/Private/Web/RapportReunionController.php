<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\User;
use App\Models\Reunion;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\RapportReunion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class RapportReunionController extends Controller
{
    /**
     * Constructor - Appliquer les middlewares d'authentification
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:viewAny,App\Models\RapportReunion')->only(['index', 'statistiques']);
        $this->middleware('can:view,rapport')->only(['show']);
        $this->middleware('can:create,App\Models\RapportReunion')->only(['create', 'store']);
        $this->middleware('can:update,rapport')->only(['edit', 'update']);
        $this->middleware('can:delete,rapport')->only(['destroy']);
    }

    // ================================
    // MÉTHODES CRUD PRINCIPALES
    // ================================

    /**
     * Afficher la liste des rapports avec filtres
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = RapportReunion::with(['reunion', 'redacteur', 'validateur']);

        // Filtres avancés
        $this->applyFilters($query, $request);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $rapports = $query->paginate($perPage);

        // Statistiques pour le tableau de bord
        $statistiques = RapportReunion::getStatistiquesGlobales();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'rapports' => $rapports,
                    'statistiques' => $statistiques,
                    'filtres' => $this->getFilterOptions()
                ]
            ]);
        }

        return view('components.private.rapportsreunions.index', compact('rapports', 'statistiques'));
    }

    /**
     * Afficher le formulaire de création d'un nouveau rapport
     */
    public function create(Request $request): View|JsonResponse
    {
        $reunions = Reunion::where('statut', 'terminee')
            ->whereDoesntHave('rapports')
            ->orderBy('date_reunion', 'desc')
            ->get();

        $redacteurs = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['redacteur', 'admin']);
        })->get();

        $validateurs = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['validateur', 'admin']);
        })->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'reunions' => $reunions,
                    'redacteurs' => $redacteurs,
                    'validateurs' => $validateurs,
                    'types_rapport' => RapportReunion::TYPES_RAPPORT,
                    'validation_rules' => RapportReunion::validationRules()
                ]
            ]);
        }

        return view('components.private.rapportsreunions.create', compact('reunions', 'redacteurs', 'validateurs'));
    }

    /**
     * Enregistrer un nouveau rapport
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // Validation des données
        $validator = Validator::make(
            $request->all(),
            RapportReunion::validationRules(),
            RapportReunion::validationMessages()
        );

        if ($validator->fails()) {
            dd($validator->errors());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $data = $validator->validated();
            $data['cree_par'] = Auth::id();
            $data['redacteur_id'] = $data['redacteur_id'] ?? Auth::id();

            $rapport = RapportReunion::create($data);

            // Ajouter les présences si fournies
            if ($request->has('presences_data')) {
                $this->ajouterPresencesInitiales($rapport, $request->get('presences_data'));
            }

            // Ajouter les actions de suivi si fournies
            if ($request->has('actions_initiales')) {
                foreach ($request->get('actions_initiales', []) as $action) {
                    $rapport->ajouterActionSuivi($action);
                }
            }

            DB::commit();

            Log::info("Nouveau rapport créé", ['rapport_id' => $rapport->id, 'user_id' => Auth::id()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rapport créé avec succès',
                    'data' => $rapport->load(['reunion', 'redacteur'])
                ], 201);
            }

            return redirect()->route('private.rapports-reunions.show', $rapport)
                           ->with('success', 'Rapport créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            Log::error("Erreur création rapport", ['error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du rapport'
                ], 500);
            }

            return redirect()->back()
                           ->withErrors('Erreur lors de la création du rapport')
                           ->withInput();
        }
    }

    /**
     * Afficher un rapport spécifique
     */
    public function show(Request $request, RapportReunion $rapport): View|JsonResponse
    {
        $rapport->load(['reunion', 'redacteur', 'validateur', 'createur', 'modificateur']);

        // Statistiques du rapport
        $statistiques = $rapport->getStatistiques();

        // Actions de suivi avec statut
        $actionsSuivre = collect($rapport->actions_suivre ?? []);
        $actionsEnCours = $actionsSuivre->where('terminee', '!=', true);
        $actionsTerminees = $actionsSuivre->where('terminee', true);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'rapport' => $rapport,
                    'statistiques' => $statistiques,
                    'actions_en_cours' => $actionsEnCours,
                    'actions_terminees' => $actionsTerminees,
                    'peut_modifier' => $rapport->peutEtreModifiePar(Auth::user()),
                    'workflow_suivant' => $this->getProchainWorkflow($rapport)
                ]
            ]);
        }

        return view('components.private.rapportsreunions.show', compact(
            'rapport', 'statistiques', 'actionsEnCours', 'actionsTerminees'
        ));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Request $request, RapportReunion $rapport)
    {

        if (!$rapport->peutEtreModifiePar(Auth::user())) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas les droits pour modifier ce rapport'
                ], 403);
            }

            return redirect()->route('private.rapports-reunions.show', $rapport)
                           ->withErrors('Vous n\'avez pas les droits pour modifier ce rapport');
        }

        $rapport->load(['reunion', 'redacteur', 'validateur']);

        $redacteurs = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['redacteur', 'admin']);
        })->get();


        $validateurs = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['validateur', 'admin']);
        })->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'rapport' => $rapport,
                    'redacteurs' => $redacteurs,
                    'validateurs' => $validateurs,
                    'validation_rules' => RapportReunion::validationRules($rapport->id)
                ]
            ]);
        }

        return view('components.private.rapportsreunions.edit', compact('rapport', 'redacteurs', 'validateurs'));
    }

    /**
     * Mettre à jour un rapport
     */
    public function update(Request $request, RapportReunion $rapport): RedirectResponse|JsonResponse
    {
        if (!$rapport->peutEtreModifiePar(Auth::user())) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'avez pas les droits pour modifier ce rapport'
                ], 403);
            }

            return redirect()->route('private.rapports-reunions.show', $rapport)
                           ->withErrors('Vous n\'avez pas les droits pour modifier ce rapport');
        }

        $validator = Validator::make(
            $request->all(),
            RapportReunion::validationRules($rapport->id),
            RapportReunion::validationMessages()
        );

        if ($validator->fails()) {
            dd($validator->errors());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $data = $validator->validated();
            $data['modifie_par'] = Auth::id();

            $rapport->update($data);

            DB::commit();

            Log::info("Rapport mis à jour", ['rapport_id' => $rapport->id, 'user_id' => Auth::id()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rapport mis à jour avec succès',
                    'data' => $rapport->fresh(['reunion', 'redacteur', 'validateur'])
                ]);
            }

            return redirect()->route('private.rapports-reunions.show', $rapport)
                           ->with('success', 'Rapport mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur mise à jour rapport", ['error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour'
                ], 500);
            }

            return redirect()->back()->withErrors('Erreur lors de la mise à jour');
        }
    }

    /**
     * Supprimer un rapport
     */
    public function destroy(Request $request, RapportReunion $rapport): RedirectResponse|JsonResponse
    {
        if ($rapport->statut === 'publie') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de supprimer un rapport publié'
                ], 422);
            }

            return redirect()->back()
                           ->withErrors('Impossible de supprimer un rapport publié');
        }

        try {
            $rapport->delete();

            Log::info("Rapport supprimé", ['rapport_id' => $rapport->id, 'user_id' => Auth::id()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rapport supprimé avec succès'
                ]);
            }

            return redirect()->route('private.rapports-reunions.index')
                           ->with('success', 'Rapport supprimé avec succès');

        } catch (\Exception $e) {
            Log::error("Erreur suppression rapport", ['error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression'
                ], 500);
            }

            return redirect()->back()->withErrors('Erreur lors de la suppression');
        }
    }

    // ================================
    // MÉTHODES DE WORKFLOW
    // ================================

    /**
     * Passer un rapport en révision
     */
    public function passerEnRevision(Request $request, RapportReunion $rapport): RedirectResponse|JsonResponse
    {
        if (!$rapport->passerEnRevision(Auth::id())) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de passer ce rapport en révision'
                ], 422);
            }

            return redirect()->back()
                           ->withErrors('Impossible de passer ce rapport en révision');
        }

        Log::info("Rapport passé en révision", ['rapport_id' => $rapport->id]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rapport passé en révision avec succès',
                'data' => $rapport->fresh()
            ]);
        }

        return redirect()->route('private.rapports-reunions.show', $rapport)
                       ->with('success', 'Rapport passé en révision avec succès');
    }

    /**
     * Valider un rapport
     */
    public function valider(Request $request, RapportReunion $rapport): RedirectResponse|JsonResponse
    {
        $request->validate([
            'commentaires' => 'nullable|string|max:1000'
        ]);

        if (!$rapport->valider(Auth::id(), $request->get('commentaires'))) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de valider ce rapport'
                ], 422);
            }

            return redirect()->back()
                           ->withErrors('Impossible de valider ce rapport');
        }

        Log::info("Rapport validé", ['rapport_id' => $rapport->id, 'validateur_id' => Auth::id()]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rapport validé avec succès',
                'data' => $rapport->fresh(['validateur'])
            ]);
        }

        return redirect()->route('private.rapports-reunions.show', $rapport)
                       ->with('success', 'Rapport validé avec succès');
    }

    /**
     * Publier un rapport
     */
    public function publier(Request $request, RapportReunion $rapport): RedirectResponse|JsonResponse
    {
        if (!$rapport->publier(Auth::id())) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de publier ce rapport'
                ], 422);
            }

            return redirect()->back()
                           ->withErrors('Impossible de publier ce rapport');
        }

        Log::info("Rapport publié", ['rapport_id' => $rapport->id]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rapport publié avec succès',
                'data' => $rapport->fresh()
            ]);
        }

        return redirect()->route('private.rapports-reunions.show', $rapport)
                       ->with('success', 'Rapport publié avec succès');
    }

    /**
     * Rejeter un rapport
     */
    public function rejeter(Request $request, RapportReunion $rapport): RedirectResponse|JsonResponse
    {
        $request->validate([
            'raison' => 'required|string|max:500'
        ], [
            'raison.required' => 'La raison du rejet est obligatoire'
        ]);

        if (!$rapport->rejeter($request->get('raison'), Auth::id())) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de rejeter ce rapport'
                ], 422);
            }

            return redirect()->back()
                           ->withErrors('Impossible de rejeter ce rapport');
        }

        Log::info("Rapport rejeté", [
            'rapport_id' => $rapport->id,
            'raison' => $request->get('raison'),
            'user_id' => Auth::id()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Rapport rejeté avec succès',
                'data' => $rapport->fresh()
            ]);
        }

        return redirect()->route('private.rapports-reunions.show', $rapport)
                       ->with('success', 'Rapport rejeté avec succès');
    }

    // ================================
    // GESTION DES PRÉSENCES
    // ================================

    /**
     * Ajouter une présence au rapport
     */
    public function ajouterPresence(Request $request, RapportReunion $rapport): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|uuid|exists:users,id',
            'nom' => 'required|string|max:100',
            'role' => 'nullable|string|max:50',
            'presente_a' => 'nullable|in:debut,milieu,fin'
        ]);

        try {
            $presence = [
                'user_id' => $request->get('user_id'),
                'nom' => $request->get('nom'),
                'role' => $request->get('role'),
                'presente_a' => $request->get('presente_a', 'debut'),
                'ajoutee_le' => now()->toISOString(),
                'ajoutee_par' => Auth::id()
            ];

            $rapport->ajouterPresence($presence);

            return response()->json([
                'success' => true,
                'message' => 'Présence ajoutée avec succès',
                'data' => [
                    'presences' => $rapport->presences,
                    'nombre_presents' => $rapport->nombre_presents
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur ajout présence", ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de la présence'
            ], 500);
        }
    }

    /**
     * Supprimer une présence du rapport
     */
    public function supprimerPresence(Request $request, RapportReunion $rapport): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|uuid'
        ]);

        try {
            $rapport->supprimerPresence($request->get('user_id'));

            return response()->json([
                'success' => true,
                'message' => 'Présence supprimée avec succès',
                'data' => [
                    'presences' => $rapport->presences,
                    'nombre_presents' => $rapport->nombre_presents
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur suppression présence", ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la présence'
            ], 500);
        }
    }

    // ================================
    // GESTION DES ACTIONS DE SUIVI
    // ================================

    /**
     * Ajouter une action de suivi
     */
    public function ajouterAction(Request $request, RapportReunion $rapport): JsonResponse
    {
        $request->validate([
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string|max:1000',
            'responsable_id' => 'required|uuid|exists:users,id',
            'echeance' => 'nullable|date',
            'priorite' => 'nullable|in:faible,normale,haute,critique'
        ]);

        try {
            $action = [
                'titre' => $request->get('titre'),
                'description' => $request->get('description'),
                'responsable_id' => $request->get('responsable_id'),
                'echeance' => $request->get('echeance'),
                'priorite' => $request->get('priorite', 'normale'),
                'terminee' => false,
                'creee_par' => Auth::id()
            ];

            $rapport->ajouterActionSuivi($action);

            return response()->json([
                'success' => true,
                'message' => 'Action de suivi ajoutée avec succès',
                'data' => $rapport->actions_suivre
            ]);

        } catch (\Exception $e) {
            Log::error("Erreur ajout action", ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de l\'action'
            ], 500);
        }
    }

    /**
     * Terminer une action de suivi
     */
    public function terminerAction(Request $request, RapportReunion $rapport): JsonResponse
    {
        $request->validate([
            'action_id' => 'required|uuid'
        ]);

        try {
            if ($rapport->terminerAction($request->get('action_id'))) {
                return response()->json([
                    'success' => true,
                    'message' => 'Action marquée comme terminée',
                    'data' => $rapport->actions_suivre
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Action non trouvée'
            ], 404);

        } catch (\Exception $e) {
            Log::error("Erreur terminer action", ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la finalisation de l\'action'
            ], 500);
        }
    }

    // ================================
    // STATISTIQUES ET RAPPORTS
    // ================================

    /**
     * Statistiques globales des rapports
     */
    public function statistiques(Request $request): View|JsonResponse
    {
        $statistiques = RapportReunion::getStatistiquesGlobales();

        // Statistiques par période
        // $statistiquesParMois = RapportReunion::selectRaw('
        //         DATE_FORMAT(created_at, "%Y-%m") as mois,
        //         COUNT(*) as total,
        //         COUNT(CASE WHEN statut = "publie" THEN 1 END) as publies
        //     ')
        //     ->where('created_at', '>=', now()->subMonths(12))
        //     ->groupBy('mois')
        //     ->orderBy('mois')
        //     ->get();
        $statistiquesParMois = RapportReunion::selectRaw('
        DATE_TRUNC(\'month\', created_at) as mois,
        COUNT(*) as total,
        COUNT(CASE WHEN statut = \'publie\' THEN 1 END) as publies
    ')
    ->where('created_at', '>=', now()->subMonths(12))
    ->groupByRaw('DATE_TRUNC(\'month\', created_at)')
    ->orderBy('mois')
    ->get();

        // Top redacteurs
        $topRedacteurs = RapportReunion::with('redacteur')
            ->selectRaw('redacteur_id, COUNT(*) as total_rapports')
            ->whereNotNull('redacteur_id')
            ->groupBy('redacteur_id')
            ->orderByDesc('total_rapports')
            ->limit(10)
            ->get();

        // Rapports en attente par type
        $rapportsEnAttente = RapportReunion::whereIn('statut', ['brouillon', 'en_revision'])
            ->selectRaw('type_rapport, COUNT(*) as total')
            ->groupBy('type_rapport')
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'statistiques_globales' => $statistiques,
                    'evolution_mensuelle' => $statistiquesParMois,
                    'top_redacteurs' => $topRedacteurs,
                    'rapports_en_attente' => $rapportsEnAttente
                ]
            ]);
        }

        $statistiques_globales = $statistiques;
        $evolution_mensuelle = $statistiquesParMois;
        $top_redacteurs = $topRedacteurs;
        $rapports_en_attente = $rapportsEnAttente;

        return view('components.private.rapportsreunions.statistiques', compact(
            'statistiques_globales', 'evolution_mensuelle', 'top_redacteurs', 'rapports_en_attente'
        ));
    }

    /**
     * Export des rapports (PDF/Excel)
     */
public function export(Request $request)
{
    $request->validate([
        'format' => 'required|in:pdf,excel',
        'statut' => 'nullable|in:' . implode(',', RapportReunion::STATUTS),
        'type' => 'nullable|in:' . implode(',', RapportReunion::TYPES_RAPPORT),
        'date_debut' => 'nullable|date',
        'date_fin' => 'nullable|date',
        'rapport_ids' => 'nullable|array',
        'rapport_ids.*' => 'uuid|exists:rapport_reunions,id'
    ]);
// dd(14);
    try {
        $query = RapportReunion::with(['reunion', 'redacteur', 'validateur']);

        // Si des IDs spécifiques sont fournis
        if ($request->filled('rapport_ids')) {
            $query->whereIn('id', $request->get('rapport_ids'));
        } else {
            // Appliquer les filtres standards
            $this->applyFilters($query, $request);
        }

        $rapports = $query->get();

        if ($rapports->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun rapport à exporter'
                ], 404);
            }

            return redirect()->back()->withErrors('Aucun rapport à exporter');
        }

        // Génération selon le format
        if ($request->get('format') === 'pdf') {
            return $this->exportPDF($rapports);
        } else {
            return $this->exportExcel($rapports);
        }

    } catch (\Exception $e) {
        Log::error("Erreur export rapports", ['error' => $e->getMessage()]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export'
            ], 500);
        }

        return redirect()->back()->withErrors('Erreur lors de l\'export');
    }
}

    // ================================
    // MÉTHODES UTILITAIRES PRIVÉES
    // ================================

    /**
     * Appliquer les filtres à la requête
     */
    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('statut')) {
            $query->where('statut', $request->get('statut'));
        }

        if ($request->filled('type')) {
            $query->where('type_rapport', $request->get('type'));
        }

        if ($request->filled('redacteur_id')) {
            $query->where('redacteur_id', $request->get('redacteur_id'));
        }

        if ($request->filled('date_debut')) {
            $query->where('created_at', '>=', $request->get('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->where('created_at', '<=', $request->get('date_fin'));
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('titre_rapport', 'like', "%{$search}%")
                  ->orWhere('resume', 'like', "%{$search}%")
                  ->orWhereHas('reunion', function ($reunion) use ($search) {
                      $reunion->where('titre', 'like', "%{$search}%");
                  });
            });
        }

        // Tri par défaut
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);
    }

    /**
     * Obtenir les options de filtres pour les vues
     */
    private function getFilterOptions(): array
    {
        return [
            'types_rapport' => RapportReunion::TYPES_RAPPORT,
            'statuts' => RapportReunion::STATUTS,
            'redacteurs' => User::whereHas('rapportsRediges')->pluck('nom', 'id'),
        ];
    }

    /**
     * Déterminer la prochaine étape du workflow
     */
    private function getProchainWorkflow(RapportReunion $rapport)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return match($rapport->statut) {
            'brouillon' => [
                'action' => 'passer_en_revision',
                'label' => 'Passer en révision',
                'can_execute' => $rapport->peutEtreModifiePar(Auth::user())
            ],
            'en_revision' => [
                'action' => 'valider',
                'label' => 'Valider le rapport',
                'can_execute' => $user->can('validate', $rapport)
            ],
            'valide' => [
                'action' => 'publier',
                'label' => 'Publier le rapport',
                'can_execute' => $user->can('publish', $rapport)
            ],
            'publie' => null,
            default => null
        };
    }

    /**
     * Ajouter les présences initiales lors de la création
     */
    private function ajouterPresencesInitiales(RapportReunion $rapport, array $presencesData): void
    {
        foreach ($presencesData as $presenceData) {
            $rapport->ajouterPresence($presenceData);
        }
    }







    /**
 * Export PDF d'un rapport individuel
 */
// public function exportRapportPDF(RapportReunion $rapport)
// {
//     $rapport->load(['reunion', 'redacteur', 'validateur']);

//     $pdf = Pdf::loadView('components.private.rapportsreunions.export.rapport-pdf', compact('rapport'))
//               ->setPaper('a4', 'portrait')
//               ->setOptions([
//                   'dpi' => 150,
//                   'defaultFont' => 'sans-serif',
//                   'isHtml5ParserEnabled' => true,
//                   'isPhpEnabled' => true
//               ]);

//     $filename = 'rapport_' . Str::slug($rapport->titre_rapport) . '_' . now()->format('Y-m-d') . '.pdf';

//     return $pdf->download($filename);
// }

/**
 * Export PDF (implémenté avec DOMPDF)
 */
// private function exportPDF($rapports)
// {
//     $rapports->load(['reunion', 'redacteur', 'validateur']);

//     $pdf = Pdf::loadView('components.private.rapportsreunions.export.rapports-pdf', compact('rapports'))
//               ->setPaper('a4', 'portrait')
//               ->setOptions([
//                   'dpi' => 150,
//                   'defaultFont' => 'sans-serif',
//                   'isHtml5ParserEnabled' => true,
//                   'isPhpEnabled' => true
//               ]);

//     $filename = 'rapports_' . now()->format('Y-m-d_H-i') . '.pdf';

//     return $pdf->download($filename);
// }



/**
 * Export d'un rapport individuel en PDF avec configuration optimisée
 */
public function exportRapportPDF(RapportReunion $rapport)
{
    try {
        // Chargement des relations nécessaires
        $rapport->load([
            'reunion',
            'redacteur',
            'validateur',
            // 'reunion.participants',
            // 'commentaires.auteur'
        ]);

        // Configuration avancée pour DOMPDF
        $pdf = Pdf::loadView('components.private.rapportsreunions.export.rapport-pdf', compact('rapport'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions([
                      'dpi' => 200,
                      'defaultFont' => 'Inter',
                      'defaultFontSize' => 11,
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'isRemoteEnabled' => true,
                      'debugKeepTemp' => false,
                      'debugPng' => false,
                      'debugCss' => false,
                      'fontSubsetting' => true,
                      'fontHeightRatio' => 1.1,
                      'isFontSubsettingEnabled' => true,
                      'tempDir' => sys_get_temp_dir(),
                      'chroot' => public_path(),
                      'logOutputFile' => storage_path('logs/dompdf.log'),
                      'enable_javascript' => false,
                      'enable_css_float' => true,
                      'enable_html5_parser' => true,
                  ]);

        // Configuration des marges optimisées
        $pdf->setOption('margin-top', 15);
        $pdf->setOption('margin-right', 15);
        $pdf->setOption('margin-bottom', 15);
        $pdf->setOption('margin-left', 15);

        // Nom du fichier optimisé
        $filename = sprintf(
            'rapport_%s_%s_%s.pdf',
            Str::slug($rapport->titre_rapport, '_'),
            $rapport->id,
            now()->format('Y-m-d_H-i')
        );

        return $pdf->download($filename);

    } catch (\Exception $e) {
        \Log::error('Erreur lors de la génération du PDF:', [
            'rapport_id' => $rapport->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
    }
}

/**
 * Export de plusieurs rapports consolidés en PDF
 */
public function exportRapportsPDF($filters = [])
{
    try {
        // Construction de la requête avec filtres
        $query = RapportReunion::with([
            'reunion',
            'redacteur',
            'validateur',
            'reunion.participants'
        ]);

        // Application des filtres
        if (!empty($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (!empty($filters['type_rapport'])) {
            $query->where('type_rapport', $filters['type_rapport']);
        }

        if (!empty($filters['date_debut'])) {
            $query->whereDate('created_at', '>=', $filters['date_debut']);
        }

        if (!empty($filters['date_fin'])) {
            $query->whereDate('created_at', '<=', $filters['date_fin']);
        }

        if (!empty($filters['redacteur_id'])) {
            $query->where('redacteur_id', $filters['redacteur_id']);
        }

        // Tri par défaut
        $rapports = $query->orderBy('created_at', 'desc')->get();

        if ($rapports->isEmpty()) {
            return back()->with('warning', 'Aucun rapport trouvé avec les critères sélectionnés.');
        }

        // Configuration PDF optimisée pour documents longs
        $pdf = Pdf::loadView('components.private.rapportsreunions.export.rapports-pdf', compact('rapports'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions([
                      'dpi' => 150,
                      'defaultFont' => 'Inter',
                      'defaultFontSize' => 10,
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'isRemoteEnabled' => true,
                      'fontSubsetting' => true,
                      'fontHeightRatio' => 1.0,
                      'enable_css_float' => true,
                      'enable_html5_parser' => true,
                      // Optimisations pour gros documents
                      'max_image_width' => 1200,
                      'max_image_height' => 1200,
                      'compress_images' => true,
                      // Gestion mémoire
                      'memory_limit' => '512M',
                      'time_limit' => 300,
                  ]);

        // Configuration des marges
        $pdf->setOption('margin-top', 12);
        $pdf->setOption('margin-right', 12);
        $pdf->setOption('margin-bottom', 12);
        $pdf->setOption('margin-left', 12);

        // Nom du fichier avec informations contextuelles
        $filename = sprintf(
            'rapports_consolidés_%d_rapports_%s.pdf',
            $rapports->count(),
            now()->format('Y-m-d_H-i')
        );

        // Log de l'export
        \Log::info('Export PDF consolidé généré', [
            'nombre_rapports' => $rapports->count(),
            'filtres' => $filters,
            'filename' => $filename,
            'user_id' => auth()->id()
        ]);

        return $pdf->download($filename);

    } catch (\Exception $e) {
        \Log::error('Erreur lors de la génération du PDF consolidé:', [
            'filtres' => $filters,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
    }
}

/**
 * Export avec streaming pour très gros documents
 */
public function exportRapportsPDFStream($filters = [])
{
    try {
        $query = RapportReunion::with(['reunion', 'redacteur', 'validateur']);

        // Application des filtres (même logique que ci-dessus)
        // ...

        $rapports = $query->orderBy('created_at', 'desc')->get();

        // Configuration pour streaming
        $pdf = Pdf::loadView('components.private.rapportsreunions.export.rapports-pdf', compact('rapports'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions([
                      'dpi' => 120, // DPI réduit pour les gros documents
                      'defaultFont' => 'DejaVu Sans',
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'fontSubsetting' => false, // Désactivé pour le streaming
                      'compress_images' => true,
                      'memory_limit' => '1024M',
                      'time_limit' => 0, // Pas de limite de temps
                  ]);

        $filename = sprintf(
            'rapports_stream_%d_%s.pdf',
            $rapports->count(),
            now()->format('Y-m-d_H-i')
        );

        // Stream le PDF directement au navigateur
        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);

    } catch (\Exception $e) {
        \Log::error('Erreur lors du streaming PDF:', [
            'error' => $e->getMessage()
        ]);

        return back()->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
    }
}

/**
 * Génération d'un aperçu PDF (première page seulement)
 */
public function previewRapportPDF(RapportReunion $rapport)
{
    try {
        $rapport->load(['reunion', 'redacteur', 'validateur']);

        // Vue spéciale pour l'aperçu (première page uniquement)
        $pdf = Pdf::loadView('components.private.rapportsreunions.export.rapport-preview', compact('rapport'))
                  ->setPaper('a4', 'portrait')
                  ->setOptions([
                      'dpi' => 100,
                      'defaultFont' => 'Inter',
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'memory_limit' => '256M',
                  ]);

        $filename = sprintf('apercu_rapport_%d.pdf', $rapport->id);

        return $pdf->stream($filename);

    } catch (\Exception $e) {
        \Log::error('Erreur aperçu PDF:', [
            'rapport_id' => $rapport->id,
            'error' => $e->getMessage()
        ]);

        return back()->with('error', 'Erreur lors de la génération de l\'aperçu');
    }
}

/**
 * Export en lot avec progression (pour AJAX)
 */
public function exportRapportsProgressif(Request $request)
{
    try {
        $batchSize = 10; // Traiter par lots de 10
        $page = $request->get('page', 1);

        $query = RapportReunion::with(['reunion', 'redacteur', 'validateur']);

        // Pagination pour le traitement par lots
        $rapports = $query->forPage($page, $batchSize)->get();
        $totalRapports = $query->count();

        if ($rapports->isEmpty()) {
            return response()->json([
                'status' => 'completed',
                'message' => 'Export terminé',
                'progress' => 100
            ]);
        }

        // Génération PDF pour ce lot
        $pdf = Pdf::loadView('components.private.rapportsreunions.export.rapports-batch', compact('rapports'));

        // Sauvegarde temporaire du lot
        $tempPath = storage_path('app/temp/batch_' . $page . '_' . time() . '.pdf');
        $pdf->save($tempPath);

        $progress = min(100, ($page * $batchSize / $totalRapports) * 100);

        return response()->json([
            'status' => 'processing',
            'progress' => round($progress, 2),
            'current_page' => $page,
            'total_pages' => ceil($totalRapports / $batchSize),
            'temp_file' => basename($tempPath)
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * Configuration globale optimisée pour DOMPDF
 */
private function getOptimizedPdfOptions()
{
    return [
        // Qualité et performance
        'dpi' => 150,
        'defaultFont' => 'Inter',
        'defaultFontSize' => 11,

        // Fonctionnalités
        'isHtml5ParserEnabled' => true,
        'isPhpEnabled' => true,
        'isRemoteEnabled' => true,
        'enable_css_float' => true,
        'enable_html5_parser' => true,

        // Optimisations
        'fontSubsetting' => true,
        'fontHeightRatio' => 1.1,
        'compress_images' => true,
        'max_image_width' => 1000,
        'max_image_height' => 1000,

        // Chemins et logs
        'tempDir' => sys_get_temp_dir(),
        'chroot' => public_path(),
        'logOutputFile' => storage_path('logs/dompdf.log'),

        // Sécurité
        'enable_javascript' => false,
        'debugKeepTemp' => app()->environment('local'),

        // Ressources
        'memory_limit' => '512M',
        'time_limit' => 300,
    ];
}

/**
 * Nettoyage des fichiers temporaires
 */
public function cleanupTempFiles()
{
    $tempPath = storage_path('app/temp/');

    if (is_dir($tempPath)) {
        $files = glob($tempPath . 'batch_*.pdf');
        $cleaned = 0;

        foreach ($files as $file) {
            if (filemtime($file) < strtotime('-1 hour')) {
                unlink($file);
                $cleaned++;
            }
        }

        \Log::info("Nettoyage fichiers temporaires PDF", ['files_cleaned' => $cleaned]);
    }
}


/**
 * Export Excel simple (CSV en attendant Laravel Excel)
 */
private function exportExcel($rapports)
{
    $headers = [
        'Content-Type' => 'text/csv; charset=utf-8',
        'Content-Disposition' => 'attachment; filename="rapports_' . now()->format('Y-m-d') . '.csv"',
    ];

    $callback = function() use ($rapports) {

        $file = fopen('php://output', 'w');

        // BOM pour Excel UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // Headers
        fputcsv($file, [
            'ID',
            'Titre',
            'Type',
            'Statut',
            'Réunion',
            'Rédacteur',
            'Validateur',
            'Présents',
            'Montant collecté',
            'Note satisfaction',
            'Date création',
            'Date validation',
            'Date publication'
        ], ';');

        // Données
        foreach ($rapports as $rapport) {
            fputcsv($file, [
                $rapport->id,
                $rapport->titre_rapport,
                $rapport->type_rapport_traduit,
                $rapport->statut_traduit,
                $rapport->reunion ? $rapport->reunion->titre : '',
                $rapport->redacteur ? $rapport->redacteur->nom . ' ' . $rapport->redacteur->prenom : '',
                $rapport->validateur ? $rapport->validateur->nom . ' ' . $rapport->validateur->prenom : '',
                $rapport->nombre_presents ?: '',
                $rapport->montant_collecte ?: '',
                $rapport->note_satisfaction ?: '',
                $rapport->created_at->format('d/m/Y H:i'),
                $rapport->valide_le ? $rapport->valide_le->format('d/m/Y H:i') : '',
                $rapport->publie_le ? $rapport->publie_le->format('d/m/Y H:i') : ''
            ], ';');
        }

        fclose($file);
    };

    return Response::stream($callback, 200, $headers);
}

/**
 * Export PDF d'un rapport depuis l'URL
 */
public function downloadPDF(Request $request, RapportReunion $rapport)
{
    return $this->exportRapportPDF($rapport);
}
}
