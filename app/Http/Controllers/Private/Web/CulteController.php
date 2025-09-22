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
     * Afficher un culte spécifique avec ses statistiques financières
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
                    'metriques' => $metriques
                ]
            ]);
        }

        return view('components.private.cultes.show', compact('culte', 'fondsStatistiques', 'metriques'));
    }


    /**
     * Calculer les statistiques financières pour un culte
     */
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
                ($fondsStats['donateurs_uniques'] / $culte->nombre_participants) * 100, 1
            );
        } else {
            $metriques['taux_participation_financiere'] = 0;
        }

        // Don moyen par donateur
        if ($fondsStats['donateurs_uniques'] > 0) {
            $metriques['don_moyen_par_donateur'] = round(
                $fondsStats['montant_total'] / $fondsStats['donateurs_uniques'], 0
            );
        } else {
            $metriques['don_moyen_par_donateur'] = 0;
        }

        // Transaction moyenne
        if ($fondsStats['total_transactions'] > 0) {
            $metriques['transaction_moyenne'] = round(
                $fondsStats['montant_total'] / $fondsStats['total_transactions'], 0
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










    /**
     * Export PDF d'un culte
     */
    public function exportPdf(Culte $culte)
    {

        try {
            // Charger les relations nécessaires
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

            // Générer le PDF
            $pdf = Pdf::loadView('exports.cultes.culte-pdf', $data);
            $pdf->setPaper('A4', 'portrait');

            // Nom du fichier
            $filename = 'rapport-culte-' . $culte->date_culte->format('Y-m-d') . '-' . \Str::slug($culte->titre) . '.pdf';

            return $pdf->download($filename);

        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la génération du PDF : ' . $e->getMessage());
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
                'pasteurPrincipal',
                'predicateur',
                'responsableCulte',
                'dirigeantLouange',
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
                ->with([
                    'programme',
                    'pasteurPrincipal',
                    'predicateur',
                    'responsableCulte',
                    'dirigeantLouange'
                ])
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



    /**
     * Export PDF multiple avec paramètres URL
     */
    public function exportMultiplePdfDirect(Request $request)
    {
        $request->validate([
            'culte_ids' => 'required|string', // IDs séparés par des virgules
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'type_culte' => 'nullable|string',
            'programme_id' => 'nullable|uuid|exists:programmes,id'
        ]);

        try {
            // Parser les IDs des cultes
            $culteIds = explode(',', $request->culte_ids);
            $culteIds = array_filter($culteIds, 'is_numeric');

            if (empty($culteIds)) {
                return redirect()->back()
                    ->with('error', 'Aucun culte valide sélectionné pour l\'export');
            }

            // Construire la requête avec filtres
            $query = Culte::whereIn('id', $culteIds)
                ->with([
                    'programme',
                    'pasteurPrincipal',
                    'predicateur',
                    'responsableCulte',
                    'dirigeantLouange'
                ]);

            // Appliquer les filtres additionnels
            if ($request->filled('date_debut') && $request->filled('date_fin')) {
                $query->whereBetween('date_culte', [
                    $request->date_debut,
                    $request->date_fin
                ]);
            }

            if ($request->filled('type_culte')) {
                $query->where('type_culte', $request->type_culte);
            }

            if ($request->filled('programme_id')) {
                $query->where('programme_id', $request->programme_id);
            }

            $cultes = $query->orderBy('date_culte')->get();

            if ($cultes->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Aucun culte trouvé avec les critères spécifiés');
            }

            return $this->exportMultiplePdf($cultes);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'export PDF : ' . $e->getMessage());
        }
    }


     /**
     * Export Excel multiple avec paramètres URL
     */
    public function exportMultipleExcelDirect(Request $request)
    {
        $request->validate([
            'culte_ids' => 'required|string', // IDs séparés par des virgules
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'type_culte' => 'nullable|string',
            'programme_id' => 'nullable|uuid|exists:programmes,id'
        ]);

        try {
            // Parser les IDs des cultes
            $culteIds = explode(',', $request->culte_ids);
            $culteIds = array_filter($culteIds, 'is_numeric');

            if (empty($culteIds)) {
                return redirect()->back()
                    ->with('error', 'Aucun culte valide sélectionné pour l\'export');
            }

            // Construire la requête avec filtres
            $query = Culte::whereIn('id', $culteIds)
                ->with([
                    'programme',
                    'pasteurPrincipal',
                    'predicateur',
                    'responsableCulte',
                    'dirigeantLouange'
                ]);

            // Appliquer les filtres additionnels
            if ($request->filled('date_debut') && $request->filled('date_fin')) {
                $query->whereBetween('date_culte', [
                    $request->date_debut,
                    $request->date_fin
                ]);
            }

            if ($request->filled('type_culte')) {
                $query->where('type_culte', $request->type_culte);
            }

            if ($request->filled('programme_id')) {
                $query->where('programme_id', $request->programme_id);
            }

            $cultes = $query->orderBy('date_culte')->get();

            if ($cultes->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Aucun culte trouvé avec les critères spécifiés');
            }

            return $this->exportMultipleExcel($cultes);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'export Excel : ' . $e->getMessage());
        }
    }



    /**
     * Générer un rapport consolidé par période
     */
    public function exportPeriode(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'format' => 'required|in:pdf,excel',
            'type_culte' => 'nullable|string',
            'programme_id' => 'nullable|uuid|exists:programmes,id',
            'statut' => 'nullable|in:planifie,en_preparation,en_cours,termine,annule,reporte',
            'pasteur_id' => 'nullable|uuid|exists:users,id'
        ]);

        try {
            // Construire la requête
            $query = Culte::whereBetween('date_culte', [
                    $request->date_debut,
                    $request->date_fin
                ])
                ->with([
                    'programme',
                    'pasteurPrincipal',
                    'predicateur',
                    'responsableCulte',
                    'dirigeantLouange'
                ]);

            // Appliquer les filtres
            if ($request->filled('type_culte')) {
                $query->where('type_culte', $request->type_culte);
            }

            if ($request->filled('programme_id')) {
                $query->where('programme_id', $request->programme_id);
            }

            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
            }

            if ($request->filled('pasteur_id')) {
                $query->where('pasteur_principal_id', $request->pasteur_id);
            }

            $cultes = $query->orderBy('date_culte')->get();

            if ($cultes->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Aucun culte trouvé pour la période spécifiée');
            }

            // Export selon le format demandé
            if ($request->get('format') === 'pdf') {
                return $this->exportMultiplePdf($cultes);
            } else {
                return $this->exportMultipleExcel($cultes);
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'export par période : ' . $e->getMessage());
        }
    }



    /**
     * Export des statistiques de performance
     */
    public function exportStatistiques(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'format' => 'required|in:pdf,excel',
            'grouper_par' => 'nullable|in:type,pasteur,mois,programme'
        ]);

        try {
            $dateDebut = $request->date_debut;
            $dateFin = $request->date_fin;
            $grouperPar = $request->get('grouper_par', 'type');

            // Récupérer les statistiques selon la méthode existante
            $statistiques = $this->calculerStatistiquesPeriode($dateDebut, $dateFin, $grouperPar);

            // Nom du fichier
            $filename = 'statistiques-cultes-' . $dateDebut . '-au-' . $dateFin;

            if ($request->get('format') === 'pdf') {
                return $this->exportStatistiquesPdf($statistiques, $filename);
            } else {
                return $this->exportStatistiquesExcel($statistiques, $filename);
            }

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'export des statistiques : ' . $e->getMessage());
        }
    }



    /**
     * Export PDF des statistiques
     */
    private function exportStatistiquesPdf(array $statistiques, string $filename): \Illuminate\Http\Response
    {
        $dateGeneration = now()->format('d/m/Y à H:i');

        $data = [
            'statistiques' => $statistiques,
            'dateGeneration' => $dateGeneration,
        ];

        $pdf = Pdf::loadView('exports.statistiques-pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download($filename . '.pdf');
    }

    /**
     * Export Excel des statistiques
     */
    private function exportStatistiquesExcel(array $statistiques, string $filename)
    {
        // return Excel::download(
        //     // new StatistiquesExport($statistiques),
        //     $filename . '.xlsx'
        // );
        return [];
    }


    /**
     * Calculer les statistiques pour une période donnée
     */
    private function calculerStatistiquesPeriode(string $dateDebut, string $dateFin, string $grouperPar): array
    {
        $query = Culte::whereBetween('date_culte', [$dateDebut, $dateFin]);

        $statistiques = [
            'periode' => [
                'debut' => $dateDebut,
                'fin' => $dateFin,
                'grouper_par' => $grouperPar
            ],
            'totaux' => [
                'nombre_cultes' => $query->count(),
                'total_participants' => $query->sum('nombre_participants') ?: 0,
                'total_conversions' => $query->sum('nombre_conversions') ?: 0,
                'total_baptemes' => $query->sum('nombre_baptemes') ?: 0,
                'total_offrandes' => $query->sum('offrande_totale') ?: 0,
            ],
            'moyennes' => [
                'participants_par_culte' => round($query->avg('nombre_participants') ?: 0, 1),
                'note_globale' => round($query->avg('note_globale') ?: 0, 1),
                'offrandes_par_culte' => round($query->avg('offrande_totale') ?: 0, 2),
            ]
        ];

        // Groupement selon le critère
        switch ($grouperPar) {
            case 'type':
                $statistiques['groupes'] = $query->select('type_culte')
                    ->selectRaw('COUNT(*) as nombre')
                    ->selectRaw('SUM(nombre_participants) as total_participants')
                    ->selectRaw('SUM(offrande_totale) as total_offrandes')
                    ->groupBy('type_culte')
                    ->get();
                break;

            case 'pasteur':
                $statistiques['groupes'] = $query->join('users', 'cultes.pasteur_principal_id', '=', 'users.id')
                    ->select('users.nom_complet as pasteur')
                    ->selectRaw('COUNT(*) as nombre')
                    ->selectRaw('SUM(cultes.nombre_participants) as total_participants')
                    ->selectRaw('SUM(cultes.offrande_totale) as total_offrandes')
                    ->groupBy('users.id', 'users.nom_complet')
                    ->get();
                break;

            case 'mois':
                $statistiques['groupes'] = $query->selectRaw('EXTRACT(YEAR FROM date_culte) as annee')
                    ->selectRaw('EXTRACT(MONTH FROM date_culte) as mois')
                    ->selectRaw('COUNT(*) as nombre')
                    ->selectRaw('SUM(nombre_participants) as total_participants')
                    ->selectRaw('SUM(offrande_totale) as total_offrandes')
                    ->groupBy('annee', 'mois')
                    ->orderBy('annee')
                    ->orderBy('mois')
                    ->get();
                break;

            case 'programme':
                $statistiques['groupes'] = $query->join('programmes', 'cultes.programme_id', '=', 'programmes.id')
                    ->select('programmes.nom_programme as programme')
                    ->selectRaw('COUNT(*) as nombre')
                    ->selectRaw('SUM(cultes.nombre_participants) as total_participants')
                    ->selectRaw('SUM(cultes.offrande_totale) as total_offrandes')
                    ->groupBy('programmes.id', 'programmes.nom_programme')
                    ->get();
                break;
        }

        return $statistiques;
    }




}
