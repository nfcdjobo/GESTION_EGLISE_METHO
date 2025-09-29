<?php

namespace App\Http\Controllers\Private\Web;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Culte;
use App\Models\Fonds;
use App\Models\Programme;
use App\Exports\CulteExport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CulteRequest;
use App\Http\Controllers\Controller;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CultesMultipleExport;
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
            'responsableFinances'
        ]);


        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'ILIKE', "%{$search}%")
                    ->orWhere('description', 'ILIKE', "%{$search}%")
                    ->orWhere('titre_message', 'ILIKE', "%{$search}%")
                    ->orWhere('lieu', 'ILIKE', "%{$search}%")
                    // Recherche dans les officiants JSON
                    ->orWhere('officiants', 'ILIKE', "%{$search}%");
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

        // Filtre par officiant (utilisateur spécifique)
        if ($request->filled('officiant_id')) {
            $query->avecOfficiant($request->get('officiant_id'));
        }

        // Filtre par titre d'officiant
        if ($request->filled('titre_officiant')) {
            $query->avecOfficiantTitre($request->get('titre_officiant'));
        }

        // Maintenir la compatibilité avec l'ancien filtre pasteur
        if ($request->filled('pasteur_id')) {
            $query->avecOfficiant($request->get('pasteur_id'));
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
            'date_culte',
            'heure_debut',
            'titre',
            'type_culte',
            'statut',
            'nombre_participants',
            'created_at'
        ];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = min($request->get('per_page', 10), 100);
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
        $users = User::orderBy('nom')->get(); // Pour les officiants

        return view('components.private.cultes.index', compact('cultes', 'programmes', 'users'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        $programmes = Programme::orderBy('nom_programme')->get();
        $users = User::orderBy('nom')->get();

        return view('components.private.cultes.create', compact('programmes', 'users'));
    }

    /**
     * Afficher un culte spécifique avec ses statistiques financières
     */
    public function show(Culte $culte)
    {
        $culte->load([
            'programme',
            'responsableFinances',
            'createur',
            'modificateur'
        ]);

        // Récupérer les fonds associés au culte
        $fondsStatistiques = $this->calculerStatistiquesFinancieres($culte);

        // Calculer les ratios et métriques
        $metriques = $this->calculerMetriques($culte, $fondsStatistiques);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'culte' => $culte,
                    'fonds_statistiques' => $fondsStatistiques,
                    'metriques' => $metriques,
                    'officiants' => $culte->oficiants_detail // Nouvelle structure des officiants
                ]
            ]);
        }

        return view('components.private.cultes.show', compact('culte', 'fondsStatistiques', 'metriques'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Culte $culte)
    {
        $programmes = Programme::orderBy('nom_programme')->get();
        $users = User::orderBy('nom')->get();

        return view('components.private.cultes.edit', compact('culte', 'programmes', 'users'));
    }

    /**
     * Créer un nouveau culte
     */
    public function store(CulteRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Traitement spécial pour les officiants
            if ($request->has('officiants')) {
                $data['officiants'] = $this->processOfficiants($request->get('officiants'));
            }

            $culte = Culte::create($data);

            // Gestion des photos si présentes
            if ($request->hasFile('photos')) {
                $photosUrls = $this->handlePhotosUpload($request->file('photos'));
                $culte->update(['photos_culte' => $photosUrls]);
            }

            DB::commit();

            $culte->load(['programme', 'responsableFinances']);

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

            $data = $request->validated();

            // Traitement spécial pour les officiants
            if ($request->has('officiants')) {
                $data['officiants'] = $this->processOfficiants($request->get('officiants'));
            }

            $culte->update($data);

            // Gestion des nouvelles photos si présentes
            if ($request->hasFile('photos')) {
                // Supprimer les anciennes photos si nécessaire
                $this->deleteOldPhotos($culte->photos_culte);

                $photosUrls = $this->handlePhotosUpload($request->file('photos'));
                $culte->update(['photos_culte' => $photosUrls]);
            }

            DB::commit();

            $culte->load(['programme', 'responsableFinances']);

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
     * Traiter les données des officiants
     */
    private function processOfficiants($officiants)
    {
        if (!is_array($officiants)) {
            return [];
        }

        $processedOfficiants = [];

        foreach ($officiants as $officiant) {
            if (isset($officiant['user_id']) && isset($officiant['titre'])) {
                $processedOfficiants[] = [
                    'user_id' => $officiant['user_id'],
                    'titre' => trim($officiant['titre']),
                    'provenance' => trim($officiant['provenance'] ?? 'Église Locale')
                ];
            }
        }

        return $processedOfficiants;
    }

    /**
     * Ajouter un officiant à un culte
     */
    public function ajouterOfficiant(Request $request, Culte $culte)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|uuid|exists:users,id',
            'titre' => 'required|string|max:100',
            'provenance' => 'nullable|string|max:100'
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
            $culte->ajouterOfficiant(
                $request->get('user_id'),
                $request->get('titre'),
                $request->get('provenance', 'Église Locale')
            );

            $culte->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Officiant ajouté avec succès',
                    'data' => $culte->oficiants_detail
                ]);
            }

            return redirect()->back()
                ->with('success', 'Officiant ajouté avec succès');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout de l\'officiant',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'ajout de l\'officiant: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un officiant d'un culte
     */
    public function supprimerOfficiant(Request $request, Culte $culte)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|uuid|exists:users,id'
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
                ->withErrors($validator);
        }

        try {
            $culte->supprimerOfficiant($request->get('user_id'));
            $culte->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Officiant supprimé avec succès',
                    'data' => $culte->oficiants_detail
                ]);
            }

            return redirect()->back()
                ->with('success', 'Officiant supprimé avec succès');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de l\'officiant',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de l\'officiant: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les officiants d'un culte
     */
    public function getOfficiants(Culte $culte)
    {
        try {
            $oficiants = $culte->oficiants_detail;

            return response()->json([
                'success' => true,
                'data' => $oficiants
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des officiants',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
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
            'nouveau_titre' => ['nullable', 'string', 'max:200'],
            'conserver_officiants' => ['boolean'] // Nouvelle option
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

            // Conserver les officiants si demandé
            if (!$request->boolean('conserver_officiants')) {
                $nouveauCulte->officiants = null;
            }

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

            $cultes = Culte::with('programme')
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

    // Garder les autres méthodes inchangées mais supprimer les références aux anciennes relations
    // Voici les méthodes importantes à adapter :

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

    // Les autres méthodes restent largement inchangées
    // mais il faut supprimer les références aux anciennes relations dans les loads/with

    private function calculerStatistiquesFinancieres(Culte $culte): array
    {
        $fonds = Fonds::where('culte_id', $culte->id)
            ->where('statut', 'validee')
            ->get();

        if ($fonds->isEmpty()) {
            return [
                'total_transactions' => 0,
                'montant_total' => 0,
                'par_type' => [],
                'par_mode_paiement' => [],
                'donateurs_uniques' => 0,
                'transactions_anonymes' => 0,
                'dons_en_nature' => 0,
                'valeur_dons_nature' => 0,
                'recus_demandes' => 0,
                'recus_emis' => 0,
                'top_donateurs' => [],
            ];
        }

        $statistiques = [
            'total_transactions' => $fonds->count(),
            'montant_total' => $fonds->sum('montant'),
            'par_type' => [],
            'par_mode_paiement' => [],
            'donateurs_uniques' => $fonds->whereNotNull('donateur_id')->unique('donateur_id')->count(),
            'transactions_anonymes' => $fonds->where('est_anonyme', true)->count(),
            'dons_en_nature' => $fonds->where('type_transaction', 'don_materiel')->count(),
            'valeur_dons_nature' => $fonds->where('type_transaction', 'don_materiel')->sum('valeur_estimee'),
            'recus_demandes' => $fonds->where('recu_demande', true)->count(),
            'recus_emis' => $fonds->where('recu_emis', true)->count(),
        ];

        // Grouper par type de transaction
        $parType = $fonds->groupBy('type_transaction');
        foreach ($parType as $type => $transactions) {
            $statistiques['par_type'][$type] = [
                'nombre' => $transactions->count(),
                'montant' => $transactions->sum('montant'),
                'pourcentage' => $statistiques['montant_total'] > 0
                    ? round(($transactions->sum('montant') / $statistiques['montant_total']) * 100, 1)
                    : 0
            ];
        }

        // Grouper par mode de paiement
        $parMode = $fonds->groupBy('mode_paiement');
        foreach ($parMode as $mode => $transactions) {
            $statistiques['par_mode_paiement'][$mode] = [
                'nombre' => $transactions->count(),
                'montant' => $transactions->sum('montant'),
                'pourcentage' => $statistiques['montant_total'] > 0
                    ? round(($transactions->sum('montant') / $statistiques['montant_total']) * 100, 1)
                    : 0
            ];
        }

        // Top donateurs (non anonymes)
        $statistiques['top_donateurs'] = $fonds->whereNotNull('donateur_id')
            ->where('est_anonyme', false)
            ->groupBy('donateur_id')
            ->map(function ($transactions) {
                $donateur = $transactions->first()->donateur;
                return [
                    'donateur' => $donateur ? $donateur->nom_complet : 'Inconnu',
                    'nombre_dons' => $transactions->count(),
                    'montant_total' => $transactions->sum('montant')
                ];
            })
            ->sortByDesc('montant_total')
            ->take(5)
            ->values()
            ->toArray();

        return $statistiques;
    }

    /**
     * Calculer les métriques et ratios pour un culte
     */
    private function calculerMetriques(Culte $culte, array $fondsStats): array
    {
        $metriques = [];

        // Ratios financiers
        if ($culte->nombre_participants > 0) {
            $metriques['offrande_par_participant'] = round($fondsStats['montant_total'] / $culte->nombre_participants, 0);

            // Ratio dîme par participant
            $montantDimes = $fondsStats['par_type']['dime']['montant'] ?? 0;
            $metriques['dime_par_participant'] = round($montantDimes / $culte->nombre_participants, 0);

            // Ratio offrandes (hors dîmes) par participant
            $montantOffrandes = $fondsStats['montant_total'] - $montantDimes;
            $metriques['offrande_pure_par_participant'] = round($montantOffrandes / $culte->nombre_participants, 0);
        } else {
            $metriques['offrande_par_participant'] = 0;
            $metriques['dime_par_participant'] = 0;
            $metriques['offrande_pure_par_participant'] = 0;
        }

        // Ratio donateurs/participants
        if ($culte->nombre_participants > 0) {
            $metriques['taux_participation_financiere'] = round(
                ($fondsStats['donateurs_uniques'] / $culte->nombre_participants) * 100,
                1
            );
        } else {
            $metriques['taux_participation_financiere'] = 0;
        }

        // Don moyen par donateur
        if ($fondsStats['donateurs_uniques'] > 0) {
            $metriques['don_moyen_par_donateur'] = round(
                $fondsStats['montant_total'] / $fondsStats['donateurs_uniques'],
                0
            );
        } else {
            $metriques['don_moyen_par_donateur'] = 0;
        }

        // Transaction moyenne
        if ($fondsStats['total_transactions'] > 0) {
            $metriques['transaction_moyenne'] = round(
                $fondsStats['montant_total'] / $fondsStats['total_transactions'],
                0
            );
        } else {
            $metriques['transaction_moyenne'] = 0;
        }

        // Pourcentage de dîmes vs offrandes
        if ($fondsStats['montant_total'] > 0) {
            $montantDimes = $fondsStats['par_type']['dime']['montant'] ?? 0;
            $metriques['pourcentage_dimes'] = round(($montantDimes / $fondsStats['montant_total']) * 100, 1);
            $metriques['pourcentage_offrandes'] = round(100 - $metriques['pourcentage_dimes'], 1);
        } else {
            $metriques['pourcentage_dimes'] = 0;
            $metriques['pourcentage_offrandes'] = 0;
        }

        // Comparaison avec la moyenne des cultes similaires (même type, derniers 6 mois)
        $moyenneSimilaires = $this->obtenirMoyenneCultesSimilaires($culte);
        $metriques['comparaison'] = [
            'moyenne_type_culte' => $moyenneSimilaires,
            'ecart_pourcentage' => $moyenneSimilaires > 0
                ? round((($fondsStats['montant_total'] - $moyenneSimilaires) / $moyenneSimilaires) * 100, 1)
                : 0
        ];

        return $metriques;
    }

    /**
     * Obtenir la moyenne des offrandes pour des cultes similaires
     */
    private function obtenirMoyenneCultesSimilaires(Culte $culte): float
    {
        return Culte::where('type_culte', $culte->type_culte)
            ->where('statut', 'termine')
            ->where('date_culte', '>=', now()->subMonths(6))
            ->where('id', '!=', $culte->id)
            ->whereNotNull('offrande_totale')
            ->avg('offrande_totale') ?? 0;
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
                        ->with('programme')
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
                    ->with('programme')
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
        if (!$photosUrls)
            return;

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

    /**
     * Export PDF d'un culte
     */
    public function exportPdf(Culte $culte)
    {
        try {
            // Charger les relations nécessaires
            $culte->load([
                'programme',
                'responsableFinances',
                'createur',
                'modificateur'
            ]);

            // Calculer les statistiques financières
            $fondsStatistiques = $this->calculerStatistiquesFinancieres($culte);
            $metriques = $this->calculerMetriques($culte, $fondsStatistiques);

            // Date de génération
            $dateGeneration = now()->format('d/m/Y à H:i');

            // Données pour la vue
            $data = [
                'culte' => $culte,
                'fondsStatistiques' => $fondsStatistiques,
                'metriques' => $metriques,
                'dateGeneration' => $dateGeneration,
            ];
            // dd($culte->officiants_detail);
            // Générer le PDF
            $pdf = Pdf::loadView('exports.cultes.culte-pdf', $data);
            $pdf->setPaper('A4', 'portrait');

            // Nom du fichier
            $filename = 'rapport-culte-' . $culte->date_culte->format('Y-m-d') . '-' . \Str::slug($culte->titre) . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
             dd($e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de la génération du PDF : ' . $e->getMessage());
        }
    }

    /**
     * Export Excel d'un culte
     */
    public function exportExcel(Culte $culte)
    {
        try {
            // Charger les relations nécessaires
            $culte->load([
                'programme',
                'responsableFinances',
                'createur',
                'modificateur'
            ]);

            // Calculer les statistiques financières
            $fondsStatistiques = $this->calculerStatistiquesFinancieres($culte);
            $metriques = $this->calculerMetriques($culte, $fondsStatistiques);

            // Nom du fichier
            $filename = 'rapport-culte-' . $culte->date_culte->format('Y-m-d') . '-' . \Str::slug($culte->titre) . '.xlsx';

            return Excel::download(
                new CulteExport($culte, $fondsStatistiques, $metriques),
                $filename
            );

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la génération du fichier Excel : ' . $e->getMessage());
        }
    }

    /**
     * Export en lot (multiple cultes)
     */
    public function exportMultiple(Request $request)
    {
        $request->validate([
            'culte_ids' => 'required|array|min:1',
            'culte_ids.*' => 'exists:cultes,id',
            'format' => 'required|in:pdf,excel'
        ]);

        try {
            $cultes = Culte::whereIn('id', $request->culte_ids)
                ->with(['programme', 'responsableFinances'])
                ->orderBy('date_culte')
                ->get();

            if ($request->get('format') === 'pdf') {
                return $this->exportMultiplePdf($cultes);
            } else {
                return $this->exportMultipleExcel($cultes);
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'export multiple : ' . $e->getMessage());
        }
    }

    /**
     * Export PDF multiple
     */
    private function exportMultiplePdf($cultes)
    {
        $dateGeneration = now()->format('d/m/Y à H:i');

        // Calculer les statistiques pour chaque culte
        $cultesData = $cultes->map(function ($culte) {
            $fondsStatistiques = $this->calculerStatistiquesFinancieres($culte);
            $metriques = $this->calculerMetriques($culte, $fondsStatistiques);

            return [
                'culte' => $culte,
                'fondsStatistiques' => $fondsStatistiques,
                'metriques' => $metriques,
            ];
        });

        $data = [
            'cultes' => $cultesData,
            'dateGeneration' => $dateGeneration,
            'totalCultes' => $cultes->count(),
            'periodeDebut' => $cultes->first()->date_culte->format('d/m/Y'),
            'periodeFin' => $cultes->last()->date_culte->format('d/m/Y'),
        ];

        $pdf = Pdf::loadView('exports.cultes.cultes-multiple-pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = 'rapport-cultes-' . $cultes->first()->date_culte->format('Y-m-d') .
            '-au-' . $cultes->last()->date_culte->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export Excel multiple
     */
    private function exportMultipleExcel($cultes)
    {
        $filename = 'rapport-cultes-' . $cultes->first()->date_culte->format('Y-m-d') .
            '-au-' . $cultes->last()->date_culte->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new CultesMultipleExport($cultes),
            $filename
        );
    }
}
