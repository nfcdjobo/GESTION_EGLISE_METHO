<?php

namespace App\Http\Controllers\Private\Web;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Culte;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CulteRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CulteController extends Controller
{
    /**
     * Constructeur avec middleware d'authentification
     */
    public function __construct()
{
    $this->middleware('auth');
    $this->middleware('permission:cultes.read')->only(['index', 'show', 'statistiques', 'planning', 'dashboard']);
    $this->middleware('permission:cultes.create')->only(['create', 'store', 'dupliquer']);
    $this->middleware('permission:cultes.update')->only(['edit', 'update', 'changerStatut', 'restore']);
    $this->middleware('permission:cultes.delete')->only(['destroy']);
}

    /**
     * Afficher la liste des cultes avec filtres et pagination
     */
    public function index(Request $request)
    {
        $query = Culte::query()->with([
            'programme',
            'pasteurPrincipal',
            'predicateur',
            'responsableCulte',
            'dirigeantLouange',
            'responsableFinances'
        ]);

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('titre', 'ILIKE', "%{$search}%")
                  ->orWhere('description', 'ILIKE', "%{$search}%")
                  ->orWhere('titre_message', 'ILIKE', "%{$search}%")
                  ->orWhere('lieu', 'ILIKE', "%{$search}%");
            });
        }

        // Filtres de statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->get('statut'));
        }

        if ($request->filled('type_culte')) {
            $query->where('type_culte', $request->get('type_culte'));
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->get('categorie'));
        }

        if ($request->filled('programme_id')) {
            $query->where('programme_id', $request->get('programme_id'));
        }

        // Filtre par date
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_culte', [
                $request->get('date_debut'),
                $request->get('date_fin')
            ]);
        } elseif ($request->filled('date_culte')) {
            $query->whereDate('date_culte', $request->get('date_culte'));
        }

        if ($request->filled('pasteur_id')) {
            $query->where('pasteur_principal_id', $request->get('pasteur_id'));
        }

        if ($request->boolean('publics_seulement')) {
            $query->public();
        }

        if ($request->boolean('a_venir')) {
            $query->aVenir();
        }

        if ($request->boolean('termines')) {
            $query->termines();
        }

        // Tri
        $sortBy = $request->get('sort_by', 'date_culte');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = [
            'date_culte', 'heure_debut', 'titre', 'type_culte',
            'statut', 'nombre_participants', 'created_at'
        ];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = min($request->get('per_page', 15), 100);
        $cultes = $query->paginate($perPage);

        // Réponse selon le type de requête
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $cultes,
                'meta' => [
                    'total' => $cultes->total(),
                    'per_page' => $cultes->perPage(),
                    'current_page' => $cultes->currentPage(),
                    'last_page' => $cultes->lastPage()
                ]
            ]);
        }

        // Données supplémentaires pour la vue Blade
        $programmes = Programme::orderBy('nom_programme')->get();
        $pasteurs = User::whereHas('roles', function($q) {
            $q->where('name', 'Pasteur');
        })->orderBy('nom')->get();

        return view('components.private.cultes.index', compact('cultes', 'programmes', 'pasteurs'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $programmes = Programme::orderBy('nom_programme')->get();
        $pasteurs = User::whereHas('roles', function($q) {
            $q->where('name', 'Pasteur');
        })->orderBy('nom')->get();

        $users = User::orderBy('nom')->get();

        return view('components.private.cultes.create', compact('programmes', 'pasteurs', 'users'));
    }

    /**
     * Afficher un culte spécifique
     */
    public function show(Culte $culte)
    {
        $culte->load([
            'programme',
            'pasteurPrincipal',
            'predicateur',
            'responsableCulte',
            'dirigeantLouange',
            'responsableFinances',
            'createur',
            'modificateur'
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $culte
            ]);
        }

        return view('components.private.cultes.show', compact('culte'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Culte $culte)
    {
        $programmes = Programme::orderBy('nom_programme')->get();
        $pasteurs = User::whereHas('roles', function($q) {
            $q->where('name', 'Pasteur');
        })->orderBy('nom')->get();

        $users = User::orderBy('nom')->get();

        return view('components.private.cultes.edit', compact('culte', 'programmes', 'pasteurs', 'users'));
    }

    /**
     * Créer un nouveau culte
     */
    public function store(CulteRequest $request)
    {
        try {
            DB::beginTransaction();

            $culte = Culte::create($request->validated());

            // Gestion des photos si présentes
            if ($request->hasFile('photos')) {
                $photosUrls = $this->handlePhotosUpload($request->file('photos'));
                $culte->update(['photos_culte' => $photosUrls]);
            }

            DB::commit();

            $culte->load([
                'programme',
                'pasteurPrincipal',
                'predicateur',
                'responsableCulte',
                'dirigeantLouange'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Culte créé avec succès',
                    'data' => $culte
                ], Response::HTTP_CREATED);
            }

            return redirect()->route('private.cultes.show', $culte)
                ->with('success', 'Culte créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du culte',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création du culte: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour un culte
     */
    public function update(CulteRequest $request, Culte $culte)
    {
        try {
            DB::beginTransaction();

            $culte->update($request->validated());

            // Gestion des nouvelles photos si présentes
            if ($request->hasFile('photos')) {
                // Supprimer les anciennes photos si nécessaire
                $this->deleteOldPhotos($culte->photos_culte);

                $photosUrls = $this->handlePhotosUpload($request->file('photos'));
                $culte->update(['photos_culte' => $photosUrls]);
            }

            DB::commit();

            $culte->load([
                'programme',
                'pasteurPrincipal',
                'predicateur',
                'responsableCulte',
                'dirigeantLouange'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Culte mis à jour avec succès',
                    'data' => $culte
                ]);
            }

            return redirect()->route('private.cultes.show', $culte)
                ->with('success', 'Culte mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du culte',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un culte (soft delete)
     */
    public function destroy(Culte $culte)
    {
        try {
            // Vérifier si le culte peut être supprimé
            if ($culte->statut === 'en_cours') {
                $message = 'Impossible de supprimer un culte en cours';

                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            $culte->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Culte supprimé avec succès'
                ]);
            }

            return redirect()->route('private.cultes.index')
                ->with('success', 'Culte supprimé avec succès');

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du culte',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Restaurer un culte supprimé
     */
    public function restore(string $id)
    {
        try {
            $culte = Culte::withTrashed()->findOrFail($id);
            $culte->restore();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Culte restauré avec succès',
                    'data' => $culte
                ]);
            }

            return redirect()->route('private.cultes.show', $culte)
                ->with('success', 'Culte restauré avec succès');

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la restauration du culte',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la restauration: ' . $e->getMessage());
        }
    }

    /**
     * Changer le statut d'un culte
     */
    public function changerStatut(Request $request, Culte $culte)
    {
        $validator = Validator::make($request->all(), [
            'statut' => ['required', 'in:planifie,en_preparation,en_cours,termine,annule,reporte'],
            'raison' => ['nullable:statut,annule,reporte', 'string', 'max:500']
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors(),
                    'paylaod' => $request->all()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $nouveauStatut = $request->get('statut');
            $ancienStatut = $culte->statut;

            // Vérifications métier selon le changement de statut
            if (!$this->peutChangerStatut($culte, $ancienStatut, $nouveauStatut)) {
                $message = "Changement de statut non autorisé de '{$ancienStatut}' vers '{$nouveauStatut}'";

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            // Actions spécifiques selon le nouveau statut
            $updates = ['statut' => $nouveauStatut];

            if ($nouveauStatut === 'en_cours' && !$culte->heure_debut_reelle) {
                $updates['heure_debut_reelle'] = now()->format('H:i:s');
            }

            if ($nouveauStatut === 'termine' && !$culte->heure_fin_reelle) {
                $updates['heure_fin_reelle'] = now()->format('H:i:s');
            }

            if (in_array($nouveauStatut, ['annule', 'reporte'])) {
                $updates['notes_organisateur'] = $request->get('raison');
            }

            $culte->update($updates);

            $message = "Statut changé avec succès de '{$ancienStatut}' vers '{$nouveauStatut}'";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $culte
                ]);
            }

            return redirect()->route('private.cultes.show', $culte)
                ->with('success', $message);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du changement de statut',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du changement de statut: ' . $e->getMessage());
        }
    }

    /**
     * Dupliquer un culte
     */
    public function dupliquer(Culte $culte, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nouvelle_date' => ['required', 'date', 'after:today'],
            'nouvelle_heure' => ['nullable', 'date_format:H:i'],
            'nouveau_titre' => ['nullable', 'string', 'max:200']
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $nouveauCulte = $culte->replicate();

            // Réinitialiser certains champs
            $nouveauCulte->date_culte = $request->get('nouvelle_date');
            $nouveauCulte->heure_debut = $request->get('nouvelle_heure', $culte->heure_debut);
            $nouveauCulte->titre = $request->get('nouveau_titre', $culte->titre . ' (Copie)');
            $nouveauCulte->statut = 'planifie';
            $nouveauCulte->heure_debut_reelle = null;
            $nouveauCulte->heure_fin_reelle = null;
            $nouveauCulte->nombre_participants = null;
            $nouveauCulte->nombre_adultes = null;
            $nouveauCulte->nombre_enfants = null;
            $nouveauCulte->nombre_jeunes = null;
            $nouveauCulte->nombre_nouveaux = null;
            $nouveauCulte->nombre_conversions = 0;
            $nouveauCulte->nombre_baptemes = 0;
            $nouveauCulte->detail_offrandes = null;
            $nouveauCulte->offrande_totale = null;
            $nouveauCulte->photos_culte = null;
            $nouveauCulte->notes_pasteur = null;
            $nouveauCulte->notes_organisateur = null;

            $nouveauCulte->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Culte dupliqué avec succès',
                    'data' => $nouveauCulte
                ], Response::HTTP_CREATED);
            }

            return redirect()->route('private.cultes.show', $nouveauCulte)
                ->with('success', 'Culte dupliqué avec succès');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la duplication du culte',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la duplication: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les statistiques des cultes
     */
    public function statistiques(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'type_culte' => ['nullable', 'string'],
            'programme_id' => ['nullable', 'uuid', 'exists:programmes,id']
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {

            // dd(now()->subYear()->format('Y-m-d'));
            $dateDebut = $request->get('date_debut', now()->subYear()->format('Y-m-d'));
            $dateFin = $request->get('date_fin', now()->format('Y-m-d'));

            // Requête de base pour les statistiques
            $baseQuery = Culte::whereBetween('date_culte', [$dateDebut, $dateFin]);

            if ($request->filled('type_culte')) {
                $baseQuery->where('type_culte', $request->get('type_culte'));
            }

            if ($request->filled('programme_id')) {
                $baseQuery->where('programme_id', $request->get('programme_id'));
            }

            $statistiques = [
                'periode' => [
                    'debut' => $dateDebut,
                    'fin' => $dateFin
                ],
                'totaux' => [
                    'nombre_cultes' => (clone $baseQuery)->count(),
                    'cultes_termines' => (clone $baseQuery)->where('statut', 'termine')->count(),
                    'cultes_annules' => (clone $baseQuery)->where('statut', 'annule')->count(),
                    'total_participants' => (clone $baseQuery)->sum('nombre_participants') ?: 0,
                    'total_conversions' => (clone $baseQuery)->sum('nombre_conversions') ?: 0,
                    'total_baptemes' => (clone $baseQuery)->sum('nombre_baptemes') ?: 0,
                    'total_offrandes' => round((clone $baseQuery)->sum('offrande_totale') ?: 0, 2)
                ],
                'moyennes' => [
                    'participants_par_culte' => round((clone $baseQuery)->avg('nombre_participants') ?: 0, 1),
                    'note_globale' => round((clone $baseQuery)->avg('note_globale') ?: 0, 1),
                    'note_louange' => round((clone $baseQuery)->avg('note_louange') ?: 0, 1),
                    'note_message' => round((clone $baseQuery)->avg('note_message') ?: 0, 1),
                    'offrandes_par_culte' => round((clone $baseQuery)->avg('offrande_totale') ?: 0, 2)
                ],
                'par_type' => (clone $baseQuery)->select('type_culte')
                    ->selectRaw('COUNT(*) as nombre')
                    ->selectRaw('COALESCE(SUM(nombre_participants), 0) as total_participants')
                    ->selectRaw('ROUND(COALESCE(AVG(nombre_participants), 0), 1) as moyenne_participants')
                    ->groupBy('type_culte')
                    ->orderBy('nombre', 'desc')
                    ->get(),
                'par_mois' => (clone $baseQuery)->select(
                        DB::raw('EXTRACT(YEAR FROM date_culte) as annee'),
                        DB::raw('EXTRACT(MONTH FROM date_culte) as mois')
                    )
                    ->selectRaw('COUNT(*) as nombre_cultes')
                    ->selectRaw('COALESCE(SUM(nombre_participants), 0) as total_participants')
                    ->selectRaw('ROUND(COALESCE(SUM(offrande_totale), 0), 2) as total_offrandes')
                    ->groupBy('annee', 'mois')
                    ->orderBy('annee', 'desc')
                    ->orderBy('mois', 'desc')
                    ->get()
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $statistiques
                ]);
            }

            $programmes = Programme::orderBy('nom_programme')->get();
            return view('components.private.cultes.statistiques', compact('statistiques', 'programmes'));

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du calcul des statistiques',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du calcul des statistiques: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir le planning des cultes
     */
    public function planning(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'vue' => ['nullable', 'in:semaine,mois,annee']
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $vue = $request->get('vue', 'mois');
            $dateDebut = $request->get('date_debut');
            $dateFin = $request->get('date_fin');

            // Définir les dates selon la vue
            if (!$dateDebut || !$dateFin) {
                $maintenant = now();
                switch ($vue) {
                    case 'semaine':
                        $dateDebut = $maintenant->copy()->startOfWeek()->format('Y-m-d');
                        $dateFin = $maintenant->copy()->endOfWeek()->format('Y-m-d');
                        break;
                    case 'annee':
                        $dateDebut = $maintenant->copy()->startOfYear()->format('Y-m-d');
                        $dateFin = $maintenant->copy()->endOfYear()->format('Y-m-d');
                        break;
                    default: // mois
                        $dateDebut = $maintenant->copy()->startOfMonth()->format('Y-m-d');
                        $dateFin = $maintenant->copy()->endOfMonth()->format('Y-m-d');
                }
            }

            $cultes = Culte::with([
                    'programme',
                    'pasteurPrincipal',
                    'predicateur'
                ])
                ->whereBetween('date_culte', [$dateDebut, $dateFin])
                ->whereIn('statut', ['planifie', 'en_preparation', 'en_cours'])
                ->orderBy('date_culte')
                ->orderBy('heure_debut')
                ->get();

            $data = [
                'periode' => [
                    'debut' => $dateDebut,
                    'fin' => $dateFin,
                    'vue' => $vue
                ],
                'cultes' => $cultes
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return view('components.private.cultes.planning', $data);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération du planning',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du chargement du planning: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les données pour le tableau de bord
     */
    public function dashboard()
    {
        try {
            $aujourd_hui = now()->format('Y-m-d');
            $debut_semaine = now()->copy()->startOfWeek()->format('Y-m-d');
            $fin_semaine = now()->copy()->endOfWeek()->format('Y-m-d');
            $debut_mois = now()->copy()->startOfMonth()->format('Y-m-d');

            $dashboard = [
                'aujourd_hui' => [
                    'cultes' => Culte::whereDate('date_culte', $aujourd_hui)
                        ->with(['pasteurPrincipal', 'programme'])
                        ->orderBy('heure_debut')
                        ->get(),
                    'nombre' => Culte::whereDate('date_culte', $aujourd_hui)->count()
                ],
                'cette_semaine' => [
                    'cultes_a_venir' => Culte::whereBetween('date_culte', [$debut_semaine, $fin_semaine])
                        ->whereIn('statut', ['planifie', 'en_preparation'])
                        ->count(),
                    'cultes_termines' => Culte::whereBetween('date_culte', [$debut_semaine, $fin_semaine])
                        ->where('statut', 'termine')
                        ->count()
                ],
                'ce_mois' => [
                    'total_cultes' => Culte::where('date_culte', '>=', $debut_mois)->count(),
                    'total_participants' => Culte::where('date_culte', '>=', $debut_mois)
                        ->sum('nombre_participants') ?: 0,
                    'total_offrandes' => round(Culte::where('date_culte', '>=', $debut_mois)
                        ->sum('offrande_totale') ?: 0, 2),
                    'total_conversions' => Culte::where('date_culte', '>=', $debut_mois)
                        ->sum('nombre_conversions') ?: 0
                ],
                'prochains_cultes' => Culte::where('date_culte', '>=', $aujourd_hui)
                    ->whereIn('statut', ['planifie', 'en_preparation'])
                    ->with(['programme', 'pasteurPrincipal'])
                    ->orderBy('date_culte')
                    ->orderBy('heure_debut')
                    ->limit(5)
                    ->get(),
                'statistiques_rapides' => [
                    'note_moyenne_mois' => round(Culte::where('date_culte', '>=', $debut_mois)
                        ->avg('note_globale') ?: 0, 1),
                    'taux_participation' => $this->calculerTauxParticipation($debut_mois)
                ]
            ];

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $dashboard
                ]);
            }

            return view('components.private.cultes.dashboard', compact('dashboard'));

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération du tableau de bord',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du chargement du tableau de bord: ' . $e->getMessage());
        }
    }

    /**
     * Gérer l'upload des photos
     */
    private function handlePhotosUpload(array $photos): array
    {
        $photosUrls = [];

        foreach ($photos as $photo) {
            if ($photo->isValid()) {
                $path = $photo->store('cultes/photos', 'public');
                $photosUrls[] = Storage::url($path);
            }
        }

        return $photosUrls;
    }

    /**
     * Supprimer les anciennes photos
     */
    private function deleteOldPhotos(?array $photosUrls): void
    {
        if (!$photosUrls) return;

        foreach ($photosUrls as $url) {
            $path = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Vérifier si un changement de statut est autorisé
     */
    private function peutChangerStatut(Culte $culte, string $ancienStatut, string $nouveauStatut): bool
    {
        $transitions_autorisees = [
            'planifie' => ['en_preparation', 'annule', 'reporte'],
            'en_preparation' => ['en_cours', 'planifie', 'annule', 'reporte'],
            'en_cours' => ['termine', 'annule'],
            'termine' => [], // Un culte terminé ne peut plus changer de statut
            'annule' => ['planifie', 'en_preparation'], // Peut être réactivé
            'reporte' => ['planifie', 'en_preparation'] // Peut être réactivé
        ];

        return in_array($nouveauStatut, $transitions_autorisees[$ancienStatut] ?? []);
    }

    /**
     * Calculer le taux de participation
     */
    private function calculerTauxParticipation(string $dateDebut): float
    {
        $cultes = Culte::where('date_culte', '>=', $dateDebut)
            ->where('statut', 'termine')
            ->whereNotNull('capacite_prevue')
            ->whereNotNull('nombre_participants')
            ->where('capacite_prevue', '>', 0)
            ->get();

        if ($cultes->isEmpty()) {
            return 0;
        }

        $totalCapacite = $cultes->sum('capacite_prevue');
        $totalParticipants = $cultes->sum('nombre_participants');

        return $totalCapacite > 0 ? round(($totalParticipants / $totalCapacite) * 100, 1) : 0;
    }
}
