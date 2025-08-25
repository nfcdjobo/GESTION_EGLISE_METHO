<?php

namespace App\Http\Controllers\Private\Web;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Culte;
use App\Models\Fonds;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Validation\ValidationException;

class FondsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:voir_fonds')->only(['index', 'show', 'dashboard', 'statistics']);
        $this->middleware('permission:creer_fonds')->only(['create', 'store']);
        $this->middleware('permission:modifier_fonds')->only(['edit', 'update']);
        $this->middleware('permission:supprimer_fonds')->only(['destroy']);
        $this->middleware('permission:valider_fonds')->only(['validateTransaction', 'cancel', 'refund']);
        $this->middleware('permission:generer_recu')->only(['generateReceipt']);
        $this->middleware('permission:analyser_fonds')->only(['analytics', 'reports']);
    }

    /**
     * Retourne une réponse standardisée selon le type de requête
     */
    private function responseFormat($data = null, $message = '', $status = 200, $errors = [])
    {
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json([
                'success' => $status >= 200 && $status < 300,
                'message' => $message,
                'data' => $data,
                'errors' => $errors,
                'status' => $status
            ], $status);
        }

        if ($status >= 400) {
            return back()->withErrors($errors)->withInput();
        }

        if ($message) {
            $alertType = $status >= 200 && $status < 300 ? 'success' : 'error';
            return redirect()->back()->with($alertType, $message);
        }

        return back();
    }

    /**
     * Affichage de la liste des transactions
     */
    public function index(Request $request)
    {
        try {

            $query = Fonds::with(['donateur', 'culte', 'collecteur', 'validateur'])
                          ->orderBy('created_at', 'desc');

            // Filtres
            if ($request->filled('statut')) {
                $query->where('statut', $request->statut);
            }

            if ($request->filled('type_transaction')) {
                $query->where('type_transaction', $request->type_transaction);
            }

            if ($request->filled('donateur_id')) {
                $query->where('donateur_id', $request->donateur_id);
            }

            if ($request->filled('culte_id')) {
                $query->where('culte_id', $request->culte_id);
            }

            if ($request->filled('date_debut')) {
                $query->whereDate('date_transaction', '>=', $request->date_debut);
            }

            if ($request->filled('date_fin')) {
                $query->whereDate('date_transaction', '<=', $request->date_fin);
            }

            if ($request->filled('montant_min')) {
                $query->where('montant', '>=', $request->montant_min);
            }

            if ($request->filled('montant_max')) {
                $query->where('montant', '<=', $request->montant_max);
            }

            if ($request->filled('mode_paiement')) {
                $query->where('mode_paiement', $request->mode_paiement);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('numero_transaction', 'LIKE', "%{$search}%")
                      ->orWhere('nom_donateur_anonyme', 'LIKE', "%{$search}%")
                      ->orWhereHas('donateur', function($dq) use ($search) {
                          $dq->whereRaw("CONCAT(prenom, ' ', nom) ILIKE ?", ["%{$search}%"]);
                      });
                });
            }

            $perPage = $request->get('per_page', 20);
            $fonds = $query->paginate($perPage);

            // Données pour les filtres
            $filterData = [
                'donateurs' => User::where('statut_membre', '!=', 'visiteur')->orderBy('nom')->get(),
                'cultes' => Culte::orderBy('date_culte', 'desc')->limit(50)->get(),
                'types_transaction' => $this->getTypesTransaction(),
                'modes_paiement' => $this->getModePaiements(),
                'statuts' => $this->getStatuts()
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'fonds' => $fonds,
                        'filters' => $filterData
                    ]
                ]);
            }

            return view('components.private.fonds.index', compact('fonds', 'filterData'));

        } catch (QueryException $e) {
            Log::error('Erreur base de données dans FondsController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]);

            return $this->responseFormat(null, 'Erreur lors de la récupération des données.', 500);

        } catch (Exception $e) {
            Log::error('Erreur générale dans FondsController@index', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return $this->responseFormat(null, 'Une erreur inattendue s\'est produite.', 500);
        }
    }

    /**
     * Dashboard financier
     */
    public function dashboard(Request $request)
    {
        try {
            $periode = $request->get('periode', 'mois');
            $dateDebut = $this->getStartDate($periode);
            $dateFin = now();

            // KPIs principaux
            $kpis = $this->getKPIs($dateDebut, $dateFin);

            // Évolution des transactions
            $evolution = $this->getEvolutionData($periode);

            // Répartition par type
            $repartitionTypes = $this->getRepartitionParType($dateDebut, $dateFin);

            // Répartition par mode de paiement
            $repartitionModes = $this->getRepartitionParMode($dateDebut, $dateFin);

            // Top donateurs
            $topDonateurs = $this->getTopDonateurs($dateDebut, $dateFin);

            // Transactions récentes
            $transactionsRecentes = Fonds::with(['donateur', 'culte'])
                                        ->orderBy('created_at', 'desc')
                                        ->limit(10)
                                        ->get();

            // Transactions en attente
            $transactionsEnAttente = Fonds::enAttente()
                                         ->with(['donateur', 'culte', 'collecteur'])
                                         ->orderBy('date_transaction', 'asc')
                                         ->limit(5)
                                         ->get();

            // Échéances à traiter
            $echeances = Fonds::transactionsEcheancesArrivees();

            // Comparaison avec période précédente
            $periodeComparaison = $this->getPeriodeComparaison($periode, $dateDebut);
            $kpisPrecedents = $this->getKPIs($periodeComparaison['debut'], $periodeComparaison['fin']);

            $dashboardData = [
                'kpis' => $kpis,
                'evolution' => $evolution,
                'repartition_types' => $repartitionTypes,
                'repartition_modes' => $repartitionModes,
                'top_donateurs' => $topDonateurs,
                'transactions_recentes' => $transactionsRecentes,
                'transactions_en_attente' => $transactionsEnAttente,
                'echeances' => $echeances,
                'kpis_precedents' => $kpisPrecedents,
                'periode' => $periode,
                'comparaisons' => $this->calculerComparaisons($kpis, $kpisPrecedents)
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $dashboardData
                ]);
            }

            return view('components.private.fonds.dashboard', $dashboardData);

        } catch (Exception $e) {
            Log::error('Erreur dans FondsController@dashboard', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'periode' => $periode ?? 'N/A'
            ]);

            return $this->responseFormat(null, 'Erreur lors du chargement du dashboard.', 500);
        }
    }

    /**
     * Statistiques complètes
     */
    public function statistics(Request $request)
    {
        try {
            $periode = $request->get('periode', 'mois');
            $annee = $request->get('annee', date('Y'));
            $mois = $request->get('mois', date('m'));
// dd([$periode, $annee, $mois]);
            $stats = [
                'resume_global' => $this->getResumeGlobal(),
                'tendances_annuelles' => $this->getTendancesAnnuelles($annee),
                'comparaison_mensuelle' => $this->getComparaisonMensuelle($annee),
                'performance_donateurs' => $this->getPerformanceDonateurs($annee),
                'analyse_cultes' => $this->getAnalyseCultes($annee),
                'projections' => $this->getProjections(),
                'alertes' => $this->getAlertes(),
                'ratios_financiers' => $this->getRatiosFinanciers($annee),
                'saisonnalite' => $this->getAnalyseSaisonnalite($annee),
                'fidelisation' => $this->getAnalyseFidelisation()
            ];

            // dd($this->getAlertes());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $stats
                ]);
            }

            return view('components.private.fonds.statistics', compact('stats', 'periode', 'annee', 'mois'));

        } catch (Exception $e) {
            dd($e->getMessage());
            Log::error('Erreur dans FondsController@statistics', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return $this->responseFormat(null, 'Erreur lors du calcul des statistiques.', 500);
        }
    }

    /**
     * Page d'analytiques avancées
     */
    public function analytics(Request $request)
    {
        try {
            $annee = $request->get('annee', date('Y'));
            $typeAnalyse = $request->get('type', 'generale');
// dd(855);
            $data = match($typeAnalyse) {
                'donateur' => $this->getAnalyseDonateurs($annee),
                'culte' => $this->getAnalyseCultes($annee),
                'tendance' => $this->getAnalyseTendances($annee),
                'predictive' => $this->getAnalysePredictive($annee),
                default => $this->getAnalyseGenerale($annee)
            };

            // dd($data);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'type' => $typeAnalyse,
                    'annee' => $annee
                ]);
            }

            return view('components.private.fonds.analytics', compact('data', 'annee', 'typeAnalyse'));

        } catch (Exception $e) {

            Log::error('Erreur dans FondsController@analytics', [
                'error' => $e->getMessage(),
                'type' => $typeAnalyse ?? 'N/A'
            ]);

            return $this->responseFormat(null, 'Erreur lors de l\'analyse.', 500);
        }
    }

    /**
     * Création d'une nouvelle transaction
     */
    public function create()
    {

        try {
            $donateurs = User::where('statut_membre', '!=', 'visiteur')->orderBy('nom')->get();
            $cultes = Culte::where('date_culte', '>=', now()->subDays(30))->orderBy('date_culte', 'desc')->get();

            $collecteurs = User::withRole('Collecteur');
            $projets = Projet::actifs()->orderBy('nom_projet')->get();

            // dd($projets);

            $formData = [
                'donateurs' => $donateurs,
                'cultes' => $cultes,
                'collecteurs' => $collecteurs,
                'projets' => $projets,
                'types_transaction' => $this->getTypesTransaction(),
                'modes_paiement' => $this->getModePaiements()
            ];

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $formData
                ]);
            }

            return view('components.private.fonds.create', compact('formData'));

        } catch (Exception $e) {
            Log::error('Erreur dans FondsController@create', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return $this->responseFormat(null, 'Erreur lors du chargement du formulaire.', 500);
        }
    }

    /**
     * Sauvegarde d'une nouvelle transaction
     */
    public function store(Request $request)
    {
        try {

            $validated = $this->validateFondsData($request);

            // Validation personnalisée
            $customErrors = $this->customValidation($validated);
            if (!empty($customErrors)) {
                return $this->responseFormat(null, 'Erreurs de validation.', 422, $customErrors);
            }



            DB::beginTransaction();

            $fonds = Fonds::create($validated);

            DB::commit();

            Log::info('Transaction créée avec succès', [
                'transaction_id' => $fonds->id,
                'numero' => $fonds->numero_transaction,
                'montant' => $fonds->montant,
                'user_id' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction créée avec succès.',
                    'data' => $fonds->load(['donateur', 'culte', 'collecteur'])
                ], 201);
            }

            return redirect()->route('private.fonds.show', $fonds)
                            ->with('success', 'Transaction créée avec succès.');

        } catch (ValidationException $e) {
            DB::rollback();
            // dd($e->getMessage());
            return $this->responseFormat(null, 'Données invalides.', 422, $e->errors());

        } catch (QueryException $e) {
            DB::rollback();
            // dd($e->getMessage());
            Log::error('Erreur base de données lors de la création', [
                'error' => $e->getMessage(),
                'data' => $validated ?? []
            ]);

            return $this->responseFormat(null, 'Erreur lors de l\'enregistrement.', 500);

        } catch (Exception $e) {
            DB::rollback();
            // dd($e->getMessage());
            Log::error('Erreur générale lors de la création', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return $this->responseFormat(null, 'Une erreur inattendue s\'est produite.', 500);
        }
    }

    /**
     * Affichage des détails d'une transaction
     */
    public function show(Fonds $fonds)
    {
        try {
            $fonds->load(['donateur', 'culte', 'collecteur', 'validateur', 'projet', 'transactionsEnfants']);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $fonds
                ]);
            }

            return view('components.private.fonds.show', compact('fonds'));

        } catch (Exception $e) {
            Log::error('Erreur dans FondsController@show', [
                'error' => $e->getMessage(),
                'fonds_id' => $fonds->id ?? 'N/A'
            ]);

            return $this->responseFormat(null, 'Erreur lors du chargement des détails.', 500);
        }
    }

    /**
     * Modification d'une transaction
     */
    public function edit(Fonds $fonds)
    {
        try {
            if (!$fonds->peutEtreModifiee()) {
                return $this->responseFormat(null, 'Cette transaction ne peut plus être modifiée.', 403);
            }

            $formData = [
                'fonds' => $fonds,
                'donateurs' => User::where('statut_membre', '!=', 'visiteur')->orderBy('nom')->get(),
                'cultes' => Culte::where('date_culte', '>=', now()->subDays(30))->orderBy('date_culte', 'desc')->get(),
                'collecteurs' => User::withRole('Collecteur'),
                'projets' => Projet::actifs()->orderBy('nom_projet')->get(),
                'types_transaction' => $this->getTypesTransaction(),
                'modes_paiement' => $this->getModePaiements()
            ];


            // dd($formData);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $formData
                ]);
            }

            return view('components.private.fonds.edit', compact('formData'));

        } catch (Exception $e) {
            Log::error('Erreur dans FondsController@edit', [
                'error' => $e->getMessage(),
                'fonds_id' => $fonds->id
            ]);

            return $this->responseFormat(null, 'Erreur lors du chargement du formulaire.', 500);
        }
    }

    /**
     * Mise à jour d'une transaction
     */
    public function update(Request $request, Fonds $fonds)
    {
        try {
            if (!$fonds->peutEtreModifiee()) {
                return $this->responseFormat(null, 'Cette transaction ne peut plus être modifiée.', 403);
            }

            $validated = $this->validateFondsData($request, $fonds);
            $customErrors = $this->customValidation($validated);

            if (!empty($customErrors)) {
                return $this->responseFormat(null, 'Erreurs de validation.', 422, $customErrors);
            }

            DB::beginTransaction();

            $oldData = $fonds->toArray();
            $fonds->update($validated);

            DB::commit();

            Log::info('Transaction mise à jour', [
                'transaction_id' => $fonds->id,
                'changes' => array_diff_assoc($validated, $oldData),
                'user_id' => auth()->id()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transaction mise à jour avec succès.',
                    'data' => $fonds->fresh()->load(['donateur', 'culte', 'collecteur'])
                ]);
            }

            return redirect()->route('private.fonds.show', $fonds)
                            ->with('success', 'Transaction mise à jour avec succès.');

        } catch (ValidationException $e) {
            DB::rollback();
            return $this->responseFormat(null, 'Données invalides.', 422, $e->errors());

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la mise à jour', [
                'error' => $e->getMessage(),
                'fonds_id' => $fonds->id
            ]);

            return $this->responseFormat(null, 'Erreur lors de la mise à jour.', 500);
        }
    }

    /**
     * Suppression d'une transaction
     */
    public function destroy(Fonds $fonds)
    {
        try {
            if (!$fonds->peutEtreModifiee()) {
                return $this->responseFormat(null, 'Cette transaction ne peut pas être supprimée.', 403);
            }

            DB::beginTransaction();

            $numero = $fonds->numero_transaction;
            $fonds->delete();

            DB::commit();

            Log::warning('Transaction supprimée', [
                'numero_transaction' => $numero,
                'montant' => $fonds->montant,
                'user_id' => auth()->id()
            ]);

            return $this->responseFormat(null, 'Transaction supprimée avec succès.');

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la suppression', [
                'error' => $e->getMessage(),
                'fonds_id' => $fonds->id
            ]);

            return $this->responseFormat(null, 'Erreur lors de la suppression.', 500);
        }
    }

    /**
     * Validation d'une transaction
     */
    public function validateTransaction(Request $request, Fonds $fonds)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'notes_validation' => 'nullable|string|max:1000'
            ]);

            if (!$fonds->peutEtreValidee()) {
                DB::rollback();
                return $this->responseFormat(null, 'Cette transaction ne peut pas être validée.', 400);
            }

            $success = $fonds->valider(auth()->id(), $request->notes_validation);

            if (!$success) {
                DB::rollback();
                return $this->responseFormat(null, 'Impossible de valider cette transaction.', 400);
            }

            DB::commit();

            Log::info('Transaction validée', [
                'transaction_id' => $fonds->id,
                'numero' => $fonds->numero_transaction,
                'montant' => $fonds->montant,
                'validateur_id' => auth()->id()
            ]);

            $responseData = $fonds->fresh();
            $message = 'Transaction validée avec succès.';

            return $this->responseFormat($responseData, $message);

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la validation', [
                'error' => $e->getMessage(),
                'fonds_id' => $fonds->id
            ]);

            return $this->responseFormat(null, 'Erreur lors de la validation.', 500);
        }
    }

    /**
     * Annulation d'une transaction
     */
    public function cancel(Request $request, Fonds $fonds)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'motif_annulation' => 'required|string|max:1000'
            ]);

            $success = $fonds->annuler($request->motif_annulation, auth()->id());

            if (!$success) {
                DB::rollback();
                return $this->responseFormat(null, 'Impossible d\'annuler cette transaction.', 400);
            }

            DB::commit();

            Log::warning('Transaction annulée', [
                'transaction_id' => $fonds->id,
                'motif' => $request->motif_annulation,
                'user_id' => auth()->id()
            ]);

            return $this->responseFormat($fonds->fresh(), 'Transaction annulée avec succès.');

        } catch (ValidationException $e) {
            DB::rollback();
            return $this->responseFormat(null, 'Motif d\'annulation requis.', 422, $e->errors());

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de l\'annulation', [
                'error' => $e->getMessage(),
                'fonds_id' => $fonds->id
            ]);

            return $this->responseFormat(null, 'Erreur lors de l\'annulation.', 500);
        }
    }

    /**
     * Remboursement d'une transaction
     */
    public function refund(Request $request, Fonds $fonds)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'motif_annulation' => 'required|string|max:1000'
            ]);

            $success = $fonds->rembourser($request->motif_annulation, auth()->id());

            if (!$success) {
                DB::rollback();
                return $this->responseFormat(null, 'Impossible de rembourser cette transaction.', 400);
            }

            DB::commit();

            Log::warning('Transaction remboursée', [
                'transaction_id' => $fonds->id,
                'motif' => $request->motif_annulation,
                'user_id' => auth()->id()
            ]);

            return $this->responseFormat($fonds->fresh(), 'Transaction remboursée avec succès.');

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Erreur lors du remboursement', [
                'error' => $e->getMessage(),
                'fonds_id' => $fonds->id
            ]);

            return $this->responseFormat(null, 'Erreur lors du remboursement.', 500);
        }
    }

    /**
     * Génération d'un reçu fiscal
     */
    public function generateReceipt(Fonds $fonds)
    {
        DB::beginTransaction();

        try {
            if (!$fonds->peutGenererRecu()) {
                DB::rollback();
                return $this->responseFormat(null, 'Impossible de générer le reçu pour cette transaction.', 400);
            }

            $numeroRecu = $fonds->genererRecu();

            if (!$numeroRecu) {
                DB::rollback();
                return $this->responseFormat(null, 'Erreur lors de la génération du reçu.', 500);
            }

            DB::commit();

            Log::info('Reçu fiscal généré', [
                'transaction_id' => $fonds->id,
                'numero_recu' => $numeroRecu,
                'user_id' => auth()->id()
            ]);

            $responseData = [
                'numero_recu' => $numeroRecu,
                'transaction' => $fonds->fresh()
            ];

            return $this->responseFormat($responseData, "Reçu généré avec le numéro : {$numeroRecu}");

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la génération du reçu', [
                'error' => $e->getMessage(),
                'fonds_id' => $fonds->id
            ]);

            return $this->responseFormat(null, 'Erreur lors de la génération du reçu.', 500);
        }
    }

    /**
     * Export des données
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'excel');

            // Appliquer les filtres
            $query = Fonds::with(['donateur', 'culte', 'collecteur', 'validateur']);

            // ... (même logique de filtrage que dans index())

            $fonds = $query->get();

            if ($fonds->isEmpty()) {
                return $this->responseFormat(null, 'Aucune donnée à exporter.', 404);
            }

            return match($format) {
                'csv' => $this->exportCSV($fonds),
                'pdf' => $this->exportPDF($fonds),
                default => $this->exportExcel($fonds)
            };

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'export', [
                'error' => $e->getMessage(),
                'format' => $format ?? 'N/A'
            ]);

            return $this->responseFormat(null, 'Erreur lors de l\'export.', 500);
        }
    }

    // ========== MÉTHODES PRIVÉES POUR LES STATISTIQUES ==========

    /**
     * Résumé global des finances
     */
    private function getResumeGlobal()
    {
        return [
            'total_collecte' => Fonds::validees()->sum('montant'),
            'nombre_transactions' => Fonds::validees()->count(),
            'nombre_donateurs' => Fonds::validees()->distinct('donateur_id')->count('donateur_id'),
            'montant_moyen' => Fonds::validees()->avg('montant'),
            'plus_gros_don' => Fonds::validees()->max('montant'),
            'plus_petit_don' => Fonds::validees()->min('montant'),
            'dimes_total' => Fonds::validees()->dimes()->sum('montant'),
            'offrandes_total' => Fonds::validees()->offrandes()->sum('montant'),
            'dons_total' => Fonds::validees()->dons()->sum('montant'),
        ];
    }

    // /**
    //  * Tendances annuelles
    //  */
    // private function getTendancesAnnuelles($annee)
    // {
    //     $donnees = DB::table('fonds')
    //         ->selectRaw('
    //             MONTH(date_transaction) as mois,
    //             SUM(montant) as total,
    //             COUNT(*) as nombre,
    //             AVG(montant) as moyenne
    //         ')
    //         ->whereYear('date_transaction', $annee)
    //         ->where('statut', 'validee')
    //         ->whereNull('deleted_at')
    //         ->groupBy('mois')
    //         ->orderBy('mois')
    //         ->get();

    //     $croissance = $this->calculerCroissance($donnees);

    //     return [
    //         'donnees_mensuelles' => $donnees,
    //         'croissance_moyenne' => $croissance['moyenne'],
    //         'meilleur_mois' => $croissance['meilleur'],
    //         'moins_bon_mois' => $croissance['moins_bon'],
    //         'tendance_generale' => $croissance['tendance']
    //     ];
    // }


    /**
 * Tendances annuelles
 */
private function getTendancesAnnuelles($annee)
{
    $donnees = DB::table('fonds')
        ->selectRaw('
            EXTRACT(MONTH FROM date_transaction) as mois,
            SUM(montant) as total,
            COUNT(*) as nombre,
            AVG(montant) as moyenne
        ')
        ->whereYear('date_transaction', $annee)
        ->where('statut', 'validee')
        ->whereNull('deleted_at')
        ->groupBy('mois')
        ->orderBy('mois')
        ->get();

    $croissance = $this->calculerCroissance($donnees);

    return [
        'donnees_mensuelles' => $donnees,
        'croissance_moyenne' => $croissance['moyenne'],
        'meilleur_mois' => $croissance['meilleur'],
        'moins_bon_mois' => $croissance['moins_bon'],
        'tendance_generale' => $croissance['tendance']
    ];
}

    /**
     * Performance des donateurs
     */
    private function getPerformanceDonateurs($annee)
    {
        return [
            'nouveaux_donateurs' => $this->getNouveauxDonateurs($annee),
            'donateurs_fideles' => $this->getDonateursFideles($annee),
            'donateurs_inactifs' => $this->getDonateursInactifs($annee),
            'segmentation' => $this->getSegmentationDonateurs($annee),
            'retention_rate' => $this->calculerRetentionRate($annee)
        ];
    }

    /**
     * Analyse des cultes
     */
    private function getAnalyseCultes($annee)
    {
        return DB::table('transactions_par_culte')
            ->whereYear('date_culte', $annee)
            ->orderBy('total_montant', 'desc')
            ->get();
    }

    /**
     * Projections financières
     */
    private function getProjections()
    {
        $historique = $this->getHistoriqueTroisDernieresAnnees();

        return [
            'projection_mensuelle' => $this->calculerProjectionMensuelle($historique),
            'projection_annuelle' => $this->calculerProjectionAnnuelle($historique),
            'objectifs_recommandes' => $this->recommanderObjectifs($historique),
            'confiance_projection' => $this->calculerConfianceProjection($historique)
        ];
    }

    /**
     * Alertes financières
     */
    private function getAlertes()
    {
        return [
            'transactions_suspectes' => $this->detecterTransactionsSuspectes(),
            'baisse_significative' => $this->detecterBaisseSignificative(),
            'donateurs_risque' => $this->detecterDonateursRisque(),
            'echeances_manquees' => $this->getEcheancesManquees(),
            'validations_en_retard' => $this->getValidationsEnRetard()
        ];
    }

    /**
     * Ratios financiers
     */
    private function getRatiosFinanciers($annee)
    {
        $dateDebut = Carbon::create($annee, 1, 1);
        $dateFin = Carbon::create($annee, 12, 31);

        $totalDimes = Fonds::validees()->dimes()->whereBetween('date_transaction', [$dateDebut, $dateFin])->sum('montant');
        $totalOffrandes = Fonds::validees()->offrandes()->whereBetween('date_transaction', [$dateDebut, $dateFin])->sum('montant');
        $totalDons = Fonds::validees()->dons()->whereBetween('date_transaction', [$dateDebut, $dateFin])->sum('montant');
        $total = $totalDimes + $totalOffrandes + $totalDons;

        return [
            'ratio_dimes' => $total > 0 ? ($totalDimes / $total) * 100 : 0,
            'ratio_offrandes' => $total > 0 ? ($totalOffrandes / $total) * 100 : 0,
            'ratio_dons' => $total > 0 ? ($totalDons / $total) * 100 : 0,
            'diversification_score' => $this->calculerScoreDiversification($totalDimes, $totalOffrandes, $totalDons),
            'stabilite_score' => $this->calculerScoreStabilite($annee)
        ];
    }

    /**
     * Validation personnalisée des données
     */
    private function customValidation($data)
    {
        $errors = [];

        if (isset($data['type_transaction']) && $data['type_transaction'] === 'don_materiel' && empty($data['description_don_nature'])) {
            $errors['description_don_nature'] = 'La description est obligatoire pour un don matériel.';
        }

        if (isset($data['est_recurrente']) && $data['est_recurrente'] && empty($data['frequence_recurrence'])) {
            $errors['frequence_recurrence'] = 'La fréquence est obligatoire pour une transaction récurrente.';
        }

        if (isset($data['est_anonyme']) && !$data['est_anonyme'] && !$data['donateur_id'] && empty($data['nom_donateur_anonyme'])) {
            $errors['donateur'] = 'Le donateur doit être identifié.';
        }

        return $errors;
    }

    /**
     * Calculer les comparaisons avec période précédente
     */
    private function calculerComparaisons($kpis, $kpisPrecedents)
    {
        $comparaisons = [];

        foreach ($kpis as $key => $value) {
            if (isset($kpisPrecedents[$key]) && $kpisPrecedents[$key] > 0) {
                $variation = (($value - $kpisPrecedents[$key]) / $kpisPrecedents[$key]) * 100;
                $comparaisons[$key] = [
                    'variation' => round($variation, 2),
                    'tendance' => $variation > 0 ? 'hausse' : ($variation < 0 ? 'baisse' : 'stable')
                ];
            }
        }

        return $comparaisons;
    }

    /**
     * Types de transactions disponibles
     */
    private function getTypesTransaction()
    {
        return [
            'dime' => 'Dîme',
            'offrande_libre' => 'Offrande libre',
            'offrande_ordinaire' => 'Offrande ordinaire',
            'offrande_speciale' => 'Offrande spéciale',
            'offrande_mission' => 'Offrande mission',
            'offrande_construction' => 'Offrande construction',
            'don_special' => 'Don spécial',
            'soutien_pasteur' => 'Soutien pasteur',
            'frais_ceremonie' => 'Frais cérémonie',
            'don_materiel' => 'Don matériel',
            'autres' => 'Autres'
        ];
    }

    /**
     * Modes de paiement disponibles
     */
    private function getModePaiements()
    {
        return [
            'especes' => 'Espèces',
            'mobile_money' => 'Mobile Money',
            'virement' => 'Virement bancaire',
            'cheque' => 'Chèque',
            'nature' => 'Don en nature'
        ];
    }

    /**
     * Statuts disponibles
     */
    private function getStatuts()
    {
        return [
            'en_attente' => 'En attente',
            'validee' => 'Validée',
            'annulee' => 'Annulée',
            'remboursee' => 'Remboursée'
        ];
    }

    /**
     * Validation des données de fonds
     */
    private function validateFondsData(Request $request, Fonds $fonds = null)
    {
        $rules = [
            'culte_id' => 'nullable|uuid|exists:cultes,id',
            'donateur_id' => 'nullable|uuid|exists:users,id',
            'collecteur_id' => 'nullable|uuid|exists:users,id',
            'date_transaction' => 'required|date|before_or_equal:today',
            'heure_transaction' => 'nullable|date_format:H:i',
            'montant' => 'required|numeric|min:0.01',
            'devise' => 'required|string|size:3',
            'type_transaction' => ['required', Rule::in(array_keys($this->getTypesTransaction()))],
            'categorie' => ['required', Rule::in(['reguliere', 'exceptionnelle', 'urgente'])],
            'nom_donateur_anonyme' => 'nullable|string|max:255',
            'contact_donateur' => 'nullable|string|max:255',
            'est_anonyme' => 'boolean',
            'est_membre' => 'boolean',
            'mode_paiement' => ['required', Rule::in(array_keys($this->getModePaiements()))],
            'reference_paiement' => 'nullable|string|max:255',
            'details_paiement' => 'nullable|array',
            'description_don_nature' => 'nullable|string',
            'valeur_estimee' => 'nullable|numeric|min:0',
            'destination' => 'nullable|string|max:255',
            'projet_id' => 'nullable|uuid|exists:projets,id',
            'est_flechee' => 'boolean',
            'instructions_donateur' => 'nullable|string',
            'recu_demande' => 'boolean',
            'est_recurrente' => 'boolean',
            'frequence_recurrence' => 'nullable|in:hebdomadaire,mensuelle,trimestrielle,annuelle',
            'occasion_speciale' => 'nullable|string|max:255',
            'lieu_collecte' => 'nullable|string|max:255',
        ];

        if ($fonds) {
            // Règles spécifiques pour la mise à jour
            $rules['numero_transaction'] = [
                'sometimes',
                'string',
                Rule::unique('fonds')->ignore($fonds->id)
            ];
        }

        return $request->validate($rules);
    }

    // ========== MÉTHODES STATISTIQUES DE BASE ==========

    /**
     * Obtenir la date de début selon la période
     */
    private function getStartDate($periode)
    {
        return match($periode) {
            'jour' => now()->startOfDay(),
            'semaine' => now()->startOfWeek(),
            'mois' => now()->startOfMonth(),
            'trimestre' => now()->startOfQuarter(),
            'annee' => now()->startOfYear(),
            default => now()->startOfMonth()
        };
    }

    /**
     * Obtenir les KPIs principaux
     */
    private function getKPIs($dateDebut, $dateFin)
    {
        try {
            $baseQuery = Fonds::validees()->whereBetween('date_transaction', [$dateDebut, $dateFin]);

            return [
                'total_transactions' => $baseQuery->count(),
                'total_montant' => $baseQuery->sum('montant'),
                'total_dimes' => $baseQuery->clone()->dimes()->sum('montant'),
                'total_offrandes' => $baseQuery->clone()->offrandes()->sum('montant'),
                'total_dons' => $baseQuery->clone()->dons()->sum('montant'),
                'transactions_attente' => Fonds::enAttente()->count(),
                'montant_moyen' => $baseQuery->avg('montant') ?? 0,
                'donateurs_uniques' => $baseQuery->distinct('donateur_id')->whereNotNull('donateur_id')->count(),
                'montant_median' => $this->calculerMediane($baseQuery->pluck('montant')),
                'transactions_especes' => $baseQuery->clone()->especes()->count(),
                'transactions_mobile_money' => $baseQuery->clone()->mobileMoney()->count(),
                'transactions_recurrentes' => $baseQuery->clone()->recurrentes()->count(),
                'recus_emis' => $baseQuery->clone()->recusEmis()->count()
            ];
        } catch (Exception $e) {
            Log::error('Erreur calcul KPIs', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Obtenir les données d'évolution
     */
    private function getEvolutionData($periode)
    {
        try {
            $groupByFormat = match($periode) {
                'jour' => 'DATE(date_transaction)',
                'semaine' => "DATE_FORMAT(date_transaction, '%Y-%u')",
                'mois' => "DATE_FORMAT(date_transaction, '%Y-%m')",
                'trimestre' => "CONCAT(YEAR(date_transaction), '-Q', QUARTER(date_transaction))",
                'annee' => 'YEAR(date_transaction)',
                default => "DATE_FORMAT(date_transaction, '%Y-%m')"
            };

            $dateDebut = $this->getStartDate($periode)->subYear();

            return DB::table('fonds')
                    ->select(
                        DB::raw("{$groupByFormat} as periode"),
                        DB::raw('SUM(montant) as total'),
                        DB::raw('COUNT(*) as nombre'),
                        DB::raw('AVG(montant) as moyenne'),
                        DB::raw('COUNT(DISTINCT donateur_id) as donateurs_uniques')
                    )
                    ->where('statut', 'validee')
                    ->where('date_transaction', '>=', $dateDebut)
                    ->whereNull('deleted_at')
                    ->groupBy('periode')
                    ->orderBy('periode')
                    ->get();
        } catch (Exception $e) {
            Log::error('Erreur données évolution', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Obtenir la répartition par type de transaction
     */
    private function getRepartitionParType($dateDebut, $dateFin)
    {
        try {
            return Fonds::validees()
                       ->whereBetween('date_transaction', [$dateDebut, $dateFin])
                       ->select(
                           'type_transaction',
                           DB::raw('SUM(montant) as total'),
                           DB::raw('COUNT(*) as nombre'),
                           DB::raw('AVG(montant) as moyenne'),
                           DB::raw('ROUND((SUM(montant) * 100.0 / (SELECT SUM(montant) FROM fonds WHERE statut = "validee" AND date_transaction BETWEEN ? AND ? AND deleted_at IS NULL)), 2) as pourcentage')
                       )
                       ->addBinding([$dateDebut, $dateFin], 'select')
                       ->groupBy('type_transaction')
                       ->orderBy('total', 'desc')
                       ->get();
        } catch (Exception $e) {
            Log::error('Erreur répartition par type', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Obtenir la répartition par mode de paiement
     */
    private function getRepartitionParMode($dateDebut, $dateFin)
    {
        try {
            return Fonds::validees()
                       ->whereBetween('date_transaction', [$dateDebut, $dateFin])
                       ->select(
                           'mode_paiement',
                           DB::raw('SUM(montant) as total'),
                           DB::raw('COUNT(*) as nombre'),
                           DB::raw('AVG(montant) as moyenne'),
                           DB::raw('ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM fonds WHERE statut = "validee" AND date_transaction BETWEEN ? AND ? AND deleted_at IS NULL)), 2) as pourcentage_nombre')
                       )
                       ->addBinding([$dateDebut, $dateFin], 'select')
                       ->groupBy('mode_paiement')
                       ->orderBy('total', 'desc')
                       ->get();
        } catch (Exception $e) {
            Log::error('Erreur répartition par mode', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Obtenir le top des donateurs
     */
    private function getTopDonateurs($dateDebut, $dateFin, $limit = 10)
    {
        try {
            return Fonds::validees()
                       ->whereBetween('date_transaction', [$dateDebut, $dateFin])
                       ->whereNotNull('donateur_id')
                       ->with('donateur:id,prenom,nom,email')
                       ->select(
                           'donateur_id',
                           DB::raw('SUM(montant) as total'),
                           DB::raw('COUNT(*) as nombre'),
                           DB::raw('AVG(montant) as moyenne'),
                           DB::raw('MIN(date_transaction) as premier_don'),
                           DB::raw('MAX(date_transaction) as dernier_don')
                       )
                       ->groupBy('donateur_id')
                       ->orderBy('total', 'desc')
                       ->limit($limit)
                       ->get()
                       ->map(function ($item) {
                           $item->regularite = $this->calculerRegulariteDonateur($item->donateur_id, $item->premier_don, $item->dernier_don);
                           return $item;
                       });
        } catch (Exception $e) {
            Log::error('Erreur top donateurs', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Obtenir la période de comparaison
     */
    private function getPeriodeComparaison($periode, $dateDebut)
    {
        try {
            $duree = $dateDebut->diffInDays(now());

            return [
                'debut' => $dateDebut->copy()->subDays($duree)->startOfDay(),
                'fin' => $dateDebut->copy()->subDay()->endOfDay()
            ];
        } catch (Exception $e) {
            Log::error('Erreur période comparaison', ['error' => $e->getMessage()]);
            return ['debut' => now()->subMonth(), 'fin' => now()];
        }
    }

    // ========== MÉTHODES D'EXPORT ==========

    /**
     * Export CSV
     */
    private function exportCSV($fonds)
    {
        try {
            $filename = 'transactions_fonds_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ];

            $callback = function() use ($fonds) {
                $file = fopen('php://output', 'w');

                // BOM UTF-8 pour Excel
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // En-têtes
                fputcsv($file, [
                    'Numéro Transaction',
                    'Date Transaction',
                    'Heure',
                    'Montant',
                    'Devise',
                    'Type Transaction',
                    'Catégorie',
                    'Donateur',
                    'Email Donateur',
                    'Mode Paiement',
                    'Référence Paiement',
                    'Statut',
                    'Culte',
                    'Date Culte',
                    'Collecteur',
                    'Validateur',
                    'Date Validation',
                    'Reçu Émis',
                    'Numéro Reçu',
                    'Est Récurrente',
                    'Destination',
                    'Notes',
                    'Date Création'
                ], ';');

                // Données
                foreach ($fonds as $fond) {
                    fputcsv($file, [
                        $fond->numero_transaction,
                        $fond->date_transaction ? $fond->date_transaction->format('d/m/Y') : '',
                        $fond->heure_transaction ? $fond->heure_transaction->format('H:i') : '',
                        number_format($fond->montant, 2, ',', ''),
                        $fond->devise,
                        $fond->type_transaction_libelle ?? $fond->type_transaction,
                        $fond->categorie,
                        $fond->nom_donateur ?? 'N/A',
                        $fond->donateur?->email ?? '',
                        $fond->mode_paiement_libelle ?? $fond->mode_paiement,
                        $fond->reference_paiement ?? '',
                        $fond->statut_libelle ?? $fond->statut,
                        $fond->culte?->titre ?? '',
                        $fond->culte?->date_culte?->format('d/m/Y') ?? '',
                        $fond->collecteur ? "{$fond->collecteur->prenom} {$fond->collecteur->nom}" : '',
                        $fond->validateur ? "{$fond->validateur->prenom} {$fond->validateur->nom}" : '',
                        $fond->validee_le?->format('d/m/Y H:i') ?? '',
                        $fond->recu_emis ? 'Oui' : 'Non',
                        $fond->numero_recu ?? '',
                        $fond->est_recurrente ? 'Oui' : 'Non',
                        $fond->destination ?? '',
                        $fond->notes_validation ?? $fond->instructions_donateur ?? '',
                        $fond->created_at->format('d/m/Y H:i')
                    ], ';');
                }

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);

        } catch (Exception $e) {
            Log::error('Erreur export CSV', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Export Excel
     */
    private function exportExcel($fonds)
    {
        try {
            // Utilisation de Laravel Excel si disponible
            if (class_exists('\Maatwebsite\Excel\Facades\Excel')) {
                return $this->exportExcelAvecLibrairie($fonds);
            }

            // Sinon, export CSV avec headers Excel
            $filename = 'transactions_fonds_' . date('Y-m-d_H-i-s') . '.xlsx';

            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ];

            $callback = function() use ($fonds) {
                $file = fopen('php://output', 'w');

                // BOM UTF-8 pour Excel
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

                // En-têtes Excel
                fputcsv($file, [
                    'Numéro Transaction', 'Date Transaction', 'Heure', 'Montant', 'Devise',
                    'Type Transaction', 'Catégorie', 'Donateur', 'Email Donateur',
                    'Mode Paiement', 'Référence Paiement', 'Statut', 'Culte', 'Date Culte',
                    'Collecteur', 'Validateur', 'Date Validation', 'Reçu Émis', 'Numéro Reçu',
                    'Est Récurrente', 'Destination', 'Notes', 'Date Création'
                ], ';');

                // Données
                foreach ($fonds as $fond) {
                    fputcsv($file, [
                        $fond->numero_transaction,
                        $fond->date_transaction ? $fond->date_transaction->format('d/m/Y') : '',
                        $fond->heure_transaction ? $fond->heure_transaction->format('H:i') : '',
                        number_format($fond->montant, 2, ',', ''),
                        $fond->devise,
                        $fond->type_transaction_libelle ?? $fond->type_transaction,
                        $fond->categorie,
                        $fond->nom_donateur ?? 'N/A',
                        $fond->donateur?->email ?? '',
                        $fond->mode_paiement_libelle ?? $fond->mode_paiement,
                        $fond->reference_paiement ?? '',
                        $fond->statut_libelle ?? $fond->statut,
                        $fond->culte?->titre ?? '',
                        $fond->culte?->date_culte?->format('d/m/Y') ?? '',
                        $fond->collecteur ? "{$fond->collecteur->prenom} {$fond->collecteur->nom}" : '',
                        $fond->validateur ? "{$fond->validateur->prenom} {$fond->validateur->nom}" : '',
                        $fond->validee_le?->format('d/m/Y H:i') ?? '',
                        $fond->recu_emis ? 'Oui' : 'Non',
                        $fond->numero_recu ?? '',
                        $fond->est_recurrente ? 'Oui' : 'Non',
                        $fond->destination ?? '',
                        $fond->notes_validation ?? $fond->instructions_donateur ?? '',
                        $fond->created_at->format('d/m/Y H:i')
                    ], ';');
                }

                fclose($file);
            };

            return Response::stream($callback, 200, $headers);

        } catch (Exception $e) {
            Log::error('Erreur export Excel', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Export PDF
     */
    private function exportPDF($fonds)
    {
        try {
            $filename = 'transactions_fonds_' . date('Y-m-d_H-i-s') . '.pdf';

            // Statistiques résumées
            $totalMontant = $fonds->where('statut', 'validee')->sum('montant');
            $nombreTransactions = $fonds->count();
            $moyenneMontant = $fonds->where('statut', 'validee')->avg('montant');

            $html = $this->genererHtmlPourPDF($fonds, [
                'total_montant' => $totalMontant,
                'nombre_transactions' => $nombreTransactions,
                'moyenne_montant' => $moyenneMontant,
                'date_generation' => now()->format('d/m/Y H:i')
            ]);

            // Si DomPDF est disponible
            if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
                return $pdf->download($filename);
            }

            // Sinon, retour HTML avec headers PDF
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            return Response::make($html, 200, $headers);

        } catch (Exception $e) {
            Log::error('Erreur export PDF', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // ========== MÉTHODES DE CALCULS AVANCÉS ==========

    /**
     * Calculer la croissance
     */
    private function calculerCroissance($donnees)
    {
        try {
            if ($donnees->count() < 2) {
                return [
                    'moyenne' => 0,
                    'meilleur' => null,
                    'moins_bon' => null,
                    'tendance' => 'stable'
                ];
            }

            $croissances = [];
            $donneesList = $donnees->sortBy('periode')->values();

            for ($i = 1; $i < $donneesList->count(); $i++) {
                $precedent = $donneesList[$i - 1]->total;
                $actuel = $donneesList[$i]->total;

                if ($precedent > 0) {
                    $croissances[] = (($actuel - $precedent) / $precedent) * 100;
                }
            }

            $croissanceMoyenne = count($croissances) > 0 ? array_sum($croissances) / count($croissances) : 0;

            $meilleurIndex = $croissances ? array_keys($croissances, max($croissances))[0] + 1 : null;
            $moinsBonIndex = $croissances ? array_keys($croissances, min($croissances))[0] + 1 : null;

            return [
                'moyenne' => round($croissanceMoyenne, 2),
                'meilleur' => $meilleurIndex ? $donneesList[$meilleurIndex] : null,
                'moins_bon' => $moinsBonIndex ? $donneesList[$moinsBonIndex] : null,
                'tendance' => $croissanceMoyenne > 5 ? 'hausse' : ($croissanceMoyenne < -5 ? 'baisse' : 'stable'),
                'croissances_detaillees' => $croissances
            ];

        } catch (Exception $e) {
            Log::error('Erreur calcul croissance', ['error' => $e->getMessage()]);
            return ['moyenne' => 0, 'meilleur' => null, 'moins_bon' => null, 'tendance' => 'stable'];
        }
    }

    /**
     * Obtenir les nouveaux donateurs
     */
    private function getNouveauxDonateurs($annee)
    {
        try {
            return DB::table('fonds as f')
                     ->select(
                         'f.donateur_id',
                         DB::raw('MIN(f.date_transaction) as premier_don'),
                         DB::raw('COUNT(*) as nombre_dons'),
                         DB::raw('SUM(f.montant) as total_dons'),
                         'u.prenom',
                         'u.nom',
                         'u.email'
                     )
                     ->join('users as u', 'f.donateur_id', '=', 'u.id')
                     ->where('f.statut', 'validee')
                     ->whereNull('f.deleted_at')
                     ->whereNotNull('f.donateur_id')
                     ->groupBy('f.donateur_id', 'u.prenom', 'u.nom', 'u.email')
                     ->havingRaw('YEAR(MIN(f.date_transaction)) = ?', [$annee])
                     ->orderBy('premier_don', 'desc')
                     ->get();

        } catch (Exception $e) {
            Log::error('Erreur nouveaux donateurs', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Obtenir les donateurs fidèles
     */
    private function getDonateursFideles($annee)
    {
        try {
            return DB::table('fonds as f')
                     ->select(
                         'f.donateur_id',
                         DB::raw('COUNT(*) as nombre_dons'),
                         DB::raw('SUM(f.montant) as total_dons'),
                         DB::raw('AVG(f.montant) as don_moyen'),
                         DB::raw('COUNT(DISTINCT DATE_FORMAT(f.date_transaction, "%Y-%m")) as mois_actifs'),
                         DB::raw('MIN(f.date_transaction) as premier_don'),
                         DB::raw('MAX(f.date_transaction) as dernier_don'),
                         'u.prenom',
                         'u.nom',
                         'u.email'
                     )
                     ->join('users as u', 'f.donateur_id', '=', 'u.id')
                     ->whereYear('f.date_transaction', $annee)
                     ->where('f.statut', 'validee')
                     ->whereNull('f.deleted_at')
                     ->whereNotNull('f.donateur_id')
                     ->groupBy('f.donateur_id', 'u.prenom', 'u.nom', 'u.email')
                     ->having('nombre_dons', '>=', 6) // Au moins 6 dons dans l'année
                     ->having('mois_actifs', '>=', 4) // Actif sur au moins 4 mois
                     ->orderBy('total_dons', 'desc')
                     ->get()
                     ->map(function ($donateur) {
                         $donateur->score_fidelite = $this->calculerScoreFidelite($donateur);
                         return $donateur;
                     });

        } catch (Exception $e) {
            Log::error('Erreur donateurs fidèles', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Calculer le taux de rétention
     */
    private function calculerRetentionRate($annee)
    {
        try {
            // Donateurs de l'année précédente
            $donateursAnneePrecedente = DB::table('fonds')
                                          ->whereYear('date_transaction', $annee - 1)
                                          ->where('statut', 'validee')
                                          ->whereNull('deleted_at')
                                          ->whereNotNull('donateur_id')
                                          ->distinct('donateur_id')
                                          ->pluck('donateur_id');

            if ($donateursAnneePrecedente->isEmpty()) {
                return 0;
            }

            // Donateurs de l'année précédente qui ont aussi donné cette année
            $donateursRevenus = DB::table('fonds')
                                  ->whereYear('date_transaction', $annee)
                                  ->where('statut', 'validee')
                                  ->whereNull('deleted_at')
                                  ->whereIn('donateur_id', $donateursAnneePrecedente)
                                  ->distinct('donateur_id')
                                  ->count();

            return round(($donateursRevenus / $donateursAnneePrecedente->count()) * 100, 2);

        } catch (Exception $e) {
            Log::error('Erreur calcul retention rate', ['error' => $e->getMessage()]);
            return 0;
        }
    }

    // ========== MÉTHODES D'ANALYSE AVANCÉES ==========

    /**
     * Obtenir l'analyse générale
     */
    private function getAnalyseGenerale($annee)
    {
        try {
            return [
                'evolution_mensuelle' => $this->getEvolutionMensuelle($annee),
                'comparaison_types' => $this->getComparaisonTypes($annee),
                'tendances' => $this->calculerTendancesAnnee($annee),
                'performance_globale' => $this->getPerformanceGlobale($annee),
                'saisonnalite' => $this->getAnalyseSaisonnalite($annee)
            ];
        } catch (Exception $e) {
            Log::error('Erreur analyse générale', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Analyse prédictive
     */
    private function getAnalysePredictive($annee)
    {
        try {
            $historique = $this->getHistoriqueTroisDernieresAnnees();

            return [
                'projections_mensuelles' => $this->calculerProjectionsMensuelles($historique),
                'tendances_predictives' => $this->calculerTendancesPredictives($historique),
                'alertes_predictives' => $this->genererAlertesPredictives($historique),
                'recommandations' => $this->genererRecommandations($historique),
                'confiance_predictions' => $this->evaluerConfiancePredictions($historique)
            ];
        } catch (Exception $e) {
            Log::error('Erreur analyse prédictive', ['error' => $e->getMessage()]);
            return [];
        }
    }

    // ========== MÉTHODES UTILITAIRES ==========

    /**
     * Calculer la médiane
     */
    private function calculerMediane($values)
    {
        try {
            $sorted = $values->sort()->values();
            $count = $sorted->count();

            if ($count === 0) return 0;
            if ($count === 1) return $sorted[0];

            if ($count % 2 === 0) {
                return ($sorted[intval($count / 2) - 1] + $sorted[intval($count / 2)]) / 2;
            }

            return $sorted[intval($count / 2)];
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Calculer la régularité d'un donateur
     */
    private function calculerRegulariteDonateur($donateurId, $premierDon, $dernierDon)
    {
        try {
            $premierDon = Carbon::parse($premierDon);
            $dernierDon = Carbon::parse($dernierDon);
            $dureeJours = $premierDon->diffInDays($dernierDon);

            if ($dureeJours < 30) return 'nouveau';

            $nombreDons = Fonds::validees()
                              ->where('donateur_id', $donateurId)
                              ->whereBetween('date_transaction', [$premierDon, $dernierDon])
                              ->count();

            $frequenceMoyenne = $dureeJours / max($nombreDons, 1);

            if ($frequenceMoyenne <= 30) return 'tres_regulier';
            if ($frequenceMoyenne <= 90) return 'regulier';
            if ($frequenceMoyenne <= 180) return 'occasionnel';

            return 'sporadique';
        } catch (Exception $e) {
            return 'inconnu';
        }
    }

    /**
     * Calculer le score de fidélité
     */
    private function calculerScoreFidelite($donateur)
    {
        try {
            $score = 0;

            // Points pour la fréquence
            $score += min($donateur->nombre_dons * 2, 50);

            // Points pour la régularité (mois actifs)
            $score += $donateur->mois_actifs * 5;

            // Points pour le montant
            if ($donateur->total_dons > 100000) $score += 20;
            elseif ($donateur->total_dons > 50000) $score += 15;
            elseif ($donateur->total_dons > 20000) $score += 10;

            // Points pour l'ancienneté
            $anciennete = Carbon::parse($donateur->premier_don)->diffInMonths($donateur->dernier_don);
            $score += min($anciennete, 30);

            return min($score, 100);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Générer HTML pour PDF
     */
    private function genererHtmlPourPDF($fonds, $stats)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Rapport Transactions Financières</title>
            <style>
                body { font-family: Arial, sans-serif; font-size: 12px; }
                .header { text-align: center; margin-bottom: 20px; }
                .stats { background: #f5f5f5; padding: 10px; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; font-size: 10px; }
                th, td { border: 1px solid #ddd; padding: 5px; text-align: left; }
                th { background-color: #4CAF50; color: white; }
                .footer { margin-top: 20px; text-align: center; font-size: 10px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Rapport des Transactions Financières</h1>
                <p>Généré le ' . $stats['date_generation'] . '</p>
            </div>

            <div class="stats">
                <h3>Résumé</h3>
                <p><strong>Nombre total de transactions :</strong> ' . number_format($stats['nombre_transactions']) . '</p>
                <p><strong>Montant total :</strong> ' . number_format($stats['total_montant'], 0, ',', ' ') . ' XOF</p>
                <p><strong>Montant moyen :</strong> ' . number_format($stats['moyenne_montant'], 0, ',', ' ') . ' XOF</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>N° Transaction</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Type</th>
                        <th>Donateur</th>
                        <th>Mode Paiement</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($fonds as $fond) {
            $html .= '
                    <tr>
                        <td>' . $fond->numero_transaction . '</td>
                        <td>' . $fond->date_transaction->format('d/m/Y') . '</td>
                        <td>' . number_format($fond->montant, 0, ',', ' ') . '</td>
                        <td>' . ($fond->type_transaction_libelle ?? $fond->type_transaction) . '</td>
                        <td>' . ($fond->nom_donateur ?? 'N/A') . '</td>
                        <td>' . ($fond->mode_paiement_libelle ?? $fond->mode_paiement) . '</td>
                        <td>' . ($fond->statut_libelle ?? $fond->statut) . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="footer">
                <p>Ce rapport a été généré automatiquement par le système de gestion des fonds.</p>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * Obtenir l'historique des trois dernières années
     */
    private function getHistoriqueTroisDernieresAnnees()
    {
        try {
            return DB::table('fonds')
                     ->select(
                         DB::raw('YEAR(date_transaction) as annee'),
                         DB::raw('MONTH(date_transaction) as mois'),
                         DB::raw('SUM(montant) as total'),
                         DB::raw('COUNT(*) as nombre'),
                         DB::raw('AVG(montant) as moyenne')
                     )
                     ->where('statut', 'validee')
                     ->where('date_transaction', '>=', now()->subYears(3))
                     ->whereNull('deleted_at')
                     ->groupBy('annee', 'mois')
                     ->orderBy('annee')
                     ->orderBy('mois')
                     ->get();
        } catch (Exception $e) {
            Log::error('Erreur historique 3 ans', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    // ========== MÉTHODES D'ANALYSES AVANCÉES MANQUANTES ==========

    /**
     * Evolution mensuelle pour une année donnée
     */
    private function getEvolutionMensuelle($annee)
    {
        try {
            return DB::table('fonds')
                     ->select(
                         DB::raw('MONTH(date_transaction) as mois'),
                         DB::raw('SUM(montant) as total'),
                         DB::raw('COUNT(*) as nombre'),
                         DB::raw('AVG(montant) as moyenne'),
                         DB::raw('COUNT(DISTINCT donateur_id) as donateurs_uniques'),
                         DB::raw('SUM(CASE WHEN type_transaction = "dime" THEN montant ELSE 0 END) as dimes'),
                         DB::raw('SUM(CASE WHEN type_transaction LIKE "offrande%" THEN montant ELSE 0 END) as offrandes'),
                         DB::raw('SUM(CASE WHEN type_transaction LIKE "don%" THEN montant ELSE 0 END) as dons')
                     )
                     ->whereYear('date_transaction', $annee)
                     ->where('statut', 'validee')
                     ->whereNull('deleted_at')
                     ->groupBy('mois')
                     ->orderBy('mois')
                     ->get()
                     ->map(function ($item) {
                         $item->nom_mois = Carbon::create()->month($item->mois)->locale('fr')->monthName;
                         return $item;
                     });
        } catch (Exception $e) {
            Log::error('Erreur évolution mensuelle', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Comparaison des types de transactions
     */
    private function getComparaisonTypes($annee)
    {
        try {
            $anneeActuelle = DB::table('fonds')
                               ->select('type_transaction', DB::raw('SUM(montant) as total'))
                               ->whereYear('date_transaction', $annee)
                               ->where('statut', 'validee')
                               ->whereNull('deleted_at')
                               ->groupBy('type_transaction')
                               ->pluck('total', 'type_transaction');

            $anneePrecedente = DB::table('fonds')
                                 ->select('type_transaction', DB::raw('SUM(montant) as total'))
                                 ->whereYear('date_transaction', $annee - 1)
                                 ->where('statut', 'validee')
                                 ->whereNull('deleted_at')
                                 ->groupBy('type_transaction')
                                 ->pluck('total', 'type_transaction');

            $comparaison = [];
            foreach ($this->getTypesTransaction() as $type => $libelle) {
                $actuel = $anneeActuelle[$type] ?? 0;
                $precedent = $anneePrecedente[$type] ?? 0;
                $variation = $precedent > 0 ? (($actuel - $precedent) / $precedent) * 100 : 0;

                $comparaison[$type] = [
                    'libelle' => $libelle,
                    'actuel' => $actuel,
                    'precedent' => $precedent,
                    'variation' => round($variation, 2),
                    'tendance' => $variation > 5 ? 'hausse' : ($variation < -5 ? 'baisse' : 'stable')
                ];
            }

            return $comparaison;
        } catch (Exception $e) {
            Log::error('Erreur comparaison types', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Calculer les tendances pour une année
     */
    private function calculerTendancesAnnee($annee)
    {
        try {
            $donneesMensuelles = $this->getEvolutionMensuelle($annee);

            if ($donneesMensuelles->count() < 2) {
                return ['tendance' => 'stable', 'croissance' => 0, 'volatilite' => 0];
            }

            // Calcul de la croissance globale
            $premier = $donneesMensuelles->first()->total;
            $dernier = $donneesMensuelles->last()->total;
            $croissanceGlobale = $premier > 0 ? (($dernier - $premier) / $premier) * 100 : 0;

            // Calcul de la volatilité
            $moyennes = $donneesMensuelles->pluck('total')->toArray();
            $moyenne = array_sum($moyennes) / count($moyennes);
            $variance = array_sum(array_map(function($x) use ($moyenne) {
                return pow($x - $moyenne, 2);
            }, $moyennes)) / count($moyennes);
            $volatilite = sqrt($variance) / $moyenne * 100;

            // Détection de tendance
            $croissances = [];
            for ($i = 1; $i < $donneesMensuelles->count(); $i++) {
                $precedent = $donneesMensuelles[$i-1]->total;
                $actuel = $donneesMensuelles[$i]->total;
                if ($precedent > 0) {
                    $croissances[] = (($actuel - $precedent) / $precedent) * 100;
                }
            }

            $croissanceMoyenne = count($croissances) > 0 ? array_sum($croissances) / count($croissances) : 0;

            return [
                'tendance' => $croissanceMoyenne > 2 ? 'hausse' : ($croissanceMoyenne < -2 ? 'baisse' : 'stable'),
                'croissance_globale' => round($croissanceGlobale, 2),
                'croissance_moyenne' => round($croissanceMoyenne, 2),
                'volatilite' => round($volatilite, 2),
                'meilleur_mois' => $donneesMensuelles->sortByDesc('total')->first(),
                'moins_bon_mois' => $donneesMensuelles->sortBy('total')->first()
            ];
        } catch (Exception $e) {
            Log::error('Erreur tendances année', ['error' => $e->getMessage()]);
            return ['tendance' => 'stable', 'croissance' => 0, 'volatilite' => 0];
        }
    }

    /**
     * Performance globale d'une année
     */
    private function getPerformanceGlobale($annee)
    {
        try {
            $dateDebut = Carbon::create($annee, 1, 1);
            $dateFin = Carbon::create($annee, 12, 31);

            $stats = DB::table('fonds')
                       ->select(
                           DB::raw('COUNT(*) as total_transactions'),
                           DB::raw('SUM(montant) as montant_total'),
                           DB::raw('AVG(montant) as montant_moyen'),
                           DB::raw('COUNT(DISTINCT donateur_id) as donateurs_uniques'),
                           DB::raw('COUNT(DISTINCT culte_id) as cultes_beneficiaires'),
                           DB::raw('SUM(CASE WHEN recu_emis = 1 THEN 1 ELSE 0 END) as recus_emis')
                       )
                       ->whereBetween('date_transaction', [$dateDebut, $dateFin])
                       ->where('statut', 'validee')
                       ->whereNull('deleted_at')
                       ->first();

            // Comparaison avec année précédente
            $statsPrecedentes = DB::table('fonds')
                                  ->select(DB::raw('SUM(montant) as montant_total'))
                                  ->whereYear('date_transaction', $annee - 1)
                                  ->where('statut', 'validee')
                                  ->whereNull('deleted_at')
                                  ->first();

            $variationAnnuelle = $statsPrecedentes->montant_total > 0
                ? (($stats->montant_total - $statsPrecedentes->montant_total) / $statsPrecedentes->montant_total) * 100
                : 0;

            // Score de performance global (0-100)
            $score = min(100, max(0, 50 + $variationAnnuelle));

            return [
                'stats_principales' => $stats,
                'variation_annuelle' => round($variationAnnuelle, 2),
                'score_performance' => round($score, 1),
                'evaluation' => $this->evaluerPerformance($score),
                'objectif_recommande' => $stats->montant_total * 1.1 // +10%
            ];
        } catch (Exception $e) {
            Log::error('Erreur performance globale', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Analyse de saisonnalité
     */
    private function getAnalyseSaisonnalite($annee)
    {
        try {
            $donnees = $this->getEvolutionMensuelle($annee);

            if ($donnees->isEmpty()) {
                return ['pics' => [], 'creux' => [], 'pattern' => 'irregular'];
            }

            $moyenneAnnuelle = $donnees->avg('total');

            $pics = $donnees->filter(function ($item) use ($moyenneAnnuelle) {
                return $item->total > $moyenneAnnuelle * 1.2;
            })->values();

            $creux = $donnees->filter(function ($item) use ($moyenneAnnuelle) {
                return $item->total < $moyenneAnnuelle * 0.8;
            })->values();

            // Détection de patterns
            $pattern = 'irregular';
            if ($pics->contains('mois', 12) || $pics->contains('mois', 4)) {
                $pattern = 'religious'; // Noël, Pâques
            } elseif ($creux->contains('mois', 7) || $creux->contains('mois', 8)) {
                $pattern = 'vacation'; // Baisse estivale
            }

            return [
                'pics' => $pics,
                'creux' => $creux,
                'pattern' => $pattern,
                'moyenne_annuelle' => round($moyenneAnnuelle, 2),
                'coefficient_variation' => round(($donnees->pluck('total')->std() / $moyenneAnnuelle) * 100, 2),
                'mois_le_plus_fort' => $donnees->sortByDesc('total')->first(),
                'mois_le_plus_faible' => $donnees->sortBy('total')->first()
            ];
        } catch (Exception $e) {
            Log::error('Erreur analyse saisonnalité', ['error' => $e->getMessage()]);
            return ['pics' => [], 'creux' => [], 'pattern' => 'irregular'];
        }
    }

    /**
     * Calcul des projections mensuelles
     */
    private function calculerProjectionsMensuelles($historique)
    {
        try {
            if ($historique->count() < 12) {
                return ['projections' => [], 'confiance' => 'faible'];
            }

            $projections = [];
            $anneeCourante = now()->year;

            // Moyenne mobile sur 12 mois
            for ($mois = 1; $mois <= 12; $mois++) {
                $donneesHistoriques = $historique->where('mois', $mois);

                if ($donneesHistoriques->count() >= 2) {
                    $moyenne = $donneesHistoriques->avg('total');
                    $tendance = $this->calculerTendanceMois($donneesHistoriques);

                    $projections[$mois] = [
                        'montant_projete' => round($moyenne * (1 + $tendance), 2),
                        'montant_historique_moyen' => round($moyenne, 2),
                        'tendance' => round($tendance * 100, 2),
                        'nom_mois' => Carbon::create()->month($mois)->locale('fr')->monthName
                    ];
                }
            }

            return [
                'projections' => $projections,
                'total_projete_annee' => array_sum(array_column($projections, 'montant_projete')),
                'confiance' => $historique->count() >= 24 ? 'elevee' : 'moyenne'
            ];
        } catch (Exception $e) {
            Log::error('Erreur projections mensuelles', ['error' => $e->getMessage()]);
            return ['projections' => [], 'confiance' => 'faible'];
        }
    }

    /**
     * Calcul des tendances prédictives
     */
    private function calculerTendancesPredictives($historique)
    {
        try {
            $tendances = [
                'croissance_annuelle' => $this->calculerCroissanceAnnuelle($historique),
                'saisonnalite' => $this->detecterSaisonnalite($historique),
                'cycles' => $this->detecterCycles($historique),
                'volatilite' => $this->calculerVolatilite($historique)
            ];

            // Prédiction pour l'année suivante
            $derniereMoyenne = $historique->where('annee', now()->year)->avg('total') ?? 0;
            $croissanceMoyenne = $tendances['croissance_annuelle'] / 100;

            $tendances['prediction_annee_suivante'] = [
                'montant_estime' => round($derniereMoyenne * 12 * (1 + $croissanceMoyenne), 2),
                'intervalle_confiance' => [
                    'min' => round($derniereMoyenne * 12 * (1 + $croissanceMoyenne - 0.1), 2),
                    'max' => round($derniereMoyenne * 12 * (1 + $croissanceMoyenne + 0.1), 2)
                ]
            ];

            return $tendances;
        } catch (Exception $e) {
            Log::error('Erreur tendances prédictives', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Générer des alertes prédictives
     */
    private function genererAlertesPredictives($historique)
    {
        try {
            $alertes = [];
            $derniersMois = $historique->sortByDesc('annee')->sortByDesc('mois')->take(3);

            if ($derniersMois->count() >= 3) {
                $tendanceRecente = $this->calculerTendanceRecente($derniersMois);

                if ($tendanceRecente < -10) {
                    $alertes[] = [
                        'type' => 'baisse_significative',
                        'severity' => 'high',
                        'message' => 'Baisse de ' . abs(round($tendanceRecente, 1)) . '% sur les 3 derniers mois',
                        'recommandation' => 'Analyser les causes et mettre en place des actions correctives'
                    ];
                }
            }

            // Alerte donateurs inactifs
            $donateursSansActivite = $this->detecterDonateursInactifs();
            if ($donateursSansActivite > 5) {
                $alertes[] = [
                    'type' => 'donateurs_inactifs',
                    'severity' => 'medium',
                    'message' => $donateursSansActivite . ' donateurs sans activité depuis 3 mois',
                    'recommandation' => 'Relancer les donateurs réguliers inactifs'
                ];
            }

            // Alerte saisonnalité
            $moisActuel = now()->month;
            if (in_array($moisActuel, [7, 8]) && $this->detecterBaisseEstivale($historique)) {
                $alertes[] = [
                    'type' => 'baisse_saisonniere',
                    'severity' => 'low',
                    'message' => 'Période de baisse estivale habituelle détectée',
                    'recommandation' => 'Prévoir des campagnes spéciales pour maintenir les dons'
                ];
            }

            return $alertes;
        } catch (Exception $e) {
            Log::error('Erreur alertes prédictives', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Générer des recommandations
     */
    private function genererRecommandations($historique)
    {
        try {
            $recommandations = [];
            $performance = $this->evaluerPerformanceHistorique($historique);

            if ($performance['croissance'] < 5) {
                $recommandations[] = [
                    'categorie' => 'croissance',
                    'priorite' => 'haute',
                    'titre' => 'Améliorer la croissance des dons',
                    'description' => 'La croissance est inférieure à 5%. Considérez diversifier les sources de financement.',
                    'actions' => [
                        'Organiser des événements spéciaux de collecte',
                        'Développer le programme de dons récurrents',
                        'Améliorer la communication sur les projets'
                    ]
                ];
            }

            if ($performance['volatilite'] > 30) {
                $recommandations[] = [
                    'categorie' => 'stabilite',
                    'priorite' => 'moyenne',
                    'titre' => 'Stabiliser les revenus',
                    'description' => 'Les revenus sont très volatils. Travaillez sur la régularité.',
                    'actions' => [
                        'Encourager les dons mensuels automatiques',
                        'Diversifier les types de contributions',
                        'Créer un fonds de réserve'
                    ]
                ];
            }

            $ratioDigital = $this->calculerRatioPayementDigital();
            if ($ratioDigital < 30) {
                $recommandations[] = [
                    'categorie' => 'modernisation',
                    'priorite' => 'moyenne',
                    'titre' => 'Moderniser les méthodes de paiement',
                    'description' => 'Seulement ' . round($ratioDigital, 1) . '% des dons utilisent les moyens modernes.',
                    'actions' => [
                        'Promouvoir le Mobile Money',
                        'Faciliter les virements bancaires',
                        'Former l\'équipe aux nouveaux outils'
                    ]
                ];
            }

            return $recommandations;
        } catch (Exception $e) {
            Log::error('Erreur recommandations', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Évaluer la confiance des prédictions
     */
    private function evaluerConfiancePredictions($historique)
    {
        try {
            $facteurs = [
                'quantite_donnees' => min(100, ($historique->count() / 36) * 100), // 3 ans = 100%
                'regularite_donnees' => $this->evaluerRegulariteDonnees($historique),
                'stabilite_tendances' => $this->evaluerStabiliteTendances($historique),
                'coherence_saisonniere' => $this->evaluerCoherenceSaisonniere($historique)
            ];

            $scoreMoyen = array_sum($facteurs) / count($facteurs);

            return [
                'score_global' => round($scoreMoyen, 1),
                'niveau' => $scoreMoyen > 80 ? 'elevee' : ($scoreMoyen > 60 ? 'moyenne' : 'faible'),
                'facteurs_detailles' => $facteurs,
                'recommandations_amelioration' => $this->recommandationsConfiancePredictions($facteurs)
            ];
        } catch (Exception $e) {
            Log::error('Erreur confiance prédictions', ['error' => $e->getMessage()]);
            return ['score_global' => 0, 'niveau' => 'faible'];
        }
    }

    // ========== MÉTHODES UTILITAIRES POUR LES ANALYSES ==========

    private function calculerTendanceMois($donneesHistoriques)
    {
        $donnees = $donneesHistoriques->sortBy('annee')->values();
        if ($donnees->count() < 2) return 0;

        $premiere = $donnees->first()->total;
        $derniere = $donnees->last()->total;

        return $premiere > 0 ? (($derniere - $premiere) / $premiere) / ($donnees->count() - 1) : 0;
    }

    private function calculerCroissanceAnnuelle($historique)
    {
        $parAnnee = $historique->groupBy('annee')->map(function ($mois) {
            return $mois->sum('total');
        })->sortKeys();

        if ($parAnnee->count() < 2) return 0;

        $premiere = $parAnnee->first();
        $derniere = $parAnnee->last();

        return $premiere > 0 ? (($derniere - $premiere) / $premiere) * 100 / ($parAnnee->count() - 1) : 0;
    }

    private function evaluerPerformance($score)
    {
        if ($score >= 80) return 'excellente';
        if ($score >= 60) return 'bonne';
        if ($score >= 40) return 'moyenne';
        return 'faible';
    }

    private function detecterDonateursInactifs()
    {
        return DB::table('fonds')
                 ->where('date_transaction', '<', now()->subMonths(3))
                 ->where('statut', 'validee')
                 ->whereNotNull('donateur_id')
                 ->distinct('donateur_id')
                 ->count();
    }

    private function calculerRatioPayementDigital()
    {
        $total = Fonds::validees()->count();
        if ($total === 0) return 0;

        $digital = Fonds::validees()->whereIn('mode_paiement', ['mobile_money', 'virement'])->count();
        return ($digital / $total) * 100;
    }

    // ========== TOUTES LES MÉTHODES MANQUANTES ==========

    /**
     * Calculer la tendance récente
     */
    private function calculerTendanceRecente($derniersMois)
    {
        try {
            if ($derniersMois->count() < 2) return 0;

            $donnees = $derniersMois->sortBy('mois')->values();
            $premier = $donnees->first()->total;
            $dernier = $donnees->last()->total;

            return $premier > 0 ? (($dernier - $premier) / $premier) * 100 : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Détecter la baisse estivale
     */
    private function detecterBaisseEstivale($historique)
    {
        try {
            $moyenneEte = $historique->whereIn('mois', [7, 8])->avg('total');
            $moyenneAnnuelle = $historique->avg('total');

            return $moyenneEte < ($moyenneAnnuelle * 0.8);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Évaluer la performance historique
     */
    private function evaluerPerformanceHistorique($historique)
    {
        try {
            $croissanceAnnuelle = $this->calculerCroissanceAnnuelle($historique);
            $volatilite = $this->calculerVolatilite($historique);

            return [
                'croissance' => $croissanceAnnuelle,
                'volatilite' => $volatilite,
                'stabilite' => 100 - $volatilite,
                'score_global' => max(0, min(100, 50 + $croissanceAnnuelle - ($volatilite / 2)))
            ];
        } catch (Exception $e) {
            return ['croissance' => 0, 'volatilite' => 0, 'stabilite' => 0, 'score_global' => 0];
        }
    }

    /**
     * Détecter la saisonnalité
     */
    private function detecterSaisonnalite($historique)
    {
        try {
            $parMois = $historique->groupBy('mois')->map(function ($donnees) {
                return $donnees->avg('total');
            });

            if ($parMois->count() < 12) return 0;

            $moyenne = $parMois->avg();
            $variance = $parMois->map(function ($valeur) use ($moyenne) {
                return pow($valeur - $moyenne, 2);
            })->avg();

            return sqrt($variance) / $moyenne * 100;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Détecter les cycles
     */
    private function detecterCycles($historique)
    {
        try {
            if ($historique->count() < 24) return [];

            $donnees = $historique->sortBy('annee')->sortBy('mois');
            $cycles = [];

            // Cycle annuel
            $parAnnee = $donnees->groupBy('annee')->map(function ($annee) {
                return $annee->sum('total');
            });

            if ($parAnnee->count() >= 2) {
                $variationAnnuelle = $parAnnee->std() / $parAnnee->avg() * 100;
                $cycles['annuel'] = [
                    'detected' => $variationAnnuelle > 15,
                    'variation' => round($variationAnnuelle, 2)
                ];
            }

            // Cycle mensuel
            $parMois = $donnees->groupBy('mois')->map(function ($mois) {
                return $mois->avg('total');
            });

            if ($parMois->count() >= 12) {
                $variationMensuelle = $parMois->std() / $parMois->avg() * 100;
                $cycles['mensuel'] = [
                    'detected' => $variationMensuelle > 20,
                    'variation' => round($variationMensuelle, 2),
                    'pic_mois' => $parMois->keys()->nth($parMois->search($parMois->max())),
                    'creux_mois' => $parMois->keys()->nth($parMois->search($parMois->min()))
                ];
            }

            return $cycles;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Calculer la volatilité
     */
    private function calculerVolatilite($historique)
    {
        try {
            $valeurs = $historique->pluck('total');
            if ($valeurs->count() < 2) return 0;

            $moyenne = $valeurs->avg();
            $variance = $valeurs->map(function ($valeur) use ($moyenne) {
                return pow($valeur - $moyenne, 2);
            })->avg();

            return $moyenne > 0 ? (sqrt($variance) / $moyenne) * 100 : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Évaluer la régularité des données
     */
    private function evaluerRegulariteDonnees($historique)
    {
        try {
            $totalMoisPossibles = now()->diffInMonths(now()->subYears(3));
            $moisAvecDonnees = $historique->count();

            return ($moisAvecDonnees / $totalMoisPossibles) * 100;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Évaluer la stabilité des tendances
     */
    private function evaluerStabiliteTendances($historique)
    {
        try {
            if ($historique->count() < 12) return 0;

            $tendancesAnnuelles = [];
            $parAnnee = $historique->groupBy('annee');

            foreach ($parAnnee as $annee => $donnees) {
                if ($donnees->count() >= 6) {
                    $premiere = $donnees->sortBy('mois')->first()->total;
                    $derniere = $donnees->sortByDesc('mois')->first()->total;
                    $tendancesAnnuelles[] = $premiere > 0 ? (($derniere - $premiere) / $premiere) * 100 : 0;
                }
            }

            if (empty($tendancesAnnuelles)) return 0;

            $moyenne = array_sum($tendancesAnnuelles) / count($tendancesAnnuelles);
            $variance = array_sum(array_map(function($x) use ($moyenne) {
                return pow($x - $moyenne, 2);
            }, $tendancesAnnuelles)) / count($tendancesAnnuelles);

            return max(0, 100 - sqrt($variance));
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Évaluer la cohérence saisonnière
     */
    private function evaluerCoherenceSaisonniere($historique)
    {
        try {
            $parMoisEtAnnee = $historique->groupBy(['mois', 'annee']);
            $coherence = 0;
            $compteur = 0;

            for ($mois = 1; $mois <= 12; $mois++) {
                $valeursParAnnee = [];
                foreach ($parMoisEtAnnee as $moisData) {
                    if (isset($moisData[$mois])) {
                        foreach ($moisData[$mois] as $data) {
                            $valeursParAnnee[] = $data->total;
                        }
                    }
                }

                if (count($valeursParAnnee) >= 2) {
                    $moyenne = array_sum($valeursParAnnee) / count($valeursParAnnee);
                    $cv = $moyenne > 0 ? (sqrt(array_sum(array_map(function($x) use ($moyenne) {
                        return pow($x - $moyenne, 2);
                    }, $valeursParAnnee)) / count($valeursParAnnee)) / $moyenne) * 100 : 100;

                    $coherence += max(0, 100 - $cv);
                    $compteur++;
                }
            }

            return $compteur > 0 ? $coherence / $compteur : 0;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Recommandations pour améliorer la confiance des prédictions
     */
    private function recommandationsConfiancePredictions($facteurs)
    {
        $recommandations = [];

        if ($facteurs['quantite_donnees'] < 70) {
            $recommandations[] = "Collecter plus de données historiques pour améliorer les prédictions";
        }

        if ($facteurs['regularite_donnees'] < 80) {
            $recommandations[] = "Assurer une saisie régulière des transactions pour éviter les lacunes";
        }

        if ($facteurs['stabilite_tendances'] < 60) {
            $recommandations[] = "Travailler sur la stabilisation des tendances financières";
        }

        if ($facteurs['coherence_saisonniere'] < 70) {
            $recommandations[] = "Améliorer la prévisibilité saisonnière des contributions";
        }

        return $recommandations;
    }

    /**
     * Comparaison mensuelle
     */
    private function getComparaisonMensuelle($annee)
    {
        try {
            $anneeActuelle = $this->getEvolutionMensuelle($annee);
            $anneePrecedente = $this->getEvolutionMensuelle($annee - 1);

            $comparaison = [];
            for ($mois = 1; $mois <= 12; $mois++) {
                $actuel = $anneeActuelle->where('mois', $mois)->first();
                $precedent = $anneePrecedente->where('mois', $mois)->first();

                $totalActuel = $actuel ? $actuel->total : 0;
                $totalPrecedent = $precedent ? $precedent->total : 0;
                $variation = $totalPrecedent > 0 ? (($totalActuel - $totalPrecedent) / $totalPrecedent) * 100 : 0;

                $comparaison[$mois] = [
                    'mois' => $mois,
                    'nom_mois' => Carbon::create()->month($mois)->locale('fr')->monthName,
                    'actuel' => $totalActuel,
                    'precedent' => $totalPrecedent,
                    'variation' => round($variation, 2),
                    'tendance' => $variation > 5 ? 'hausse' : ($variation < -5 ? 'baisse' : 'stable')
                ];
            }

            return $comparaison;
        } catch (Exception $e) {
            Log::error('Erreur comparaison mensuelle', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Obtenir les donateurs inactifs
     */
    private function getDonateursInactifs($annee)
    {
        try {
            $dateLimit = now()->subMonths(6);

            return DB::table('fonds as f')
                     ->select(
                         'f.donateur_id',
                         DB::raw('MAX(f.date_transaction) as derniere_transaction'),
                         DB::raw('DATEDIFF(NOW(), MAX(f.date_transaction)) as jours_inactivite'),
                         DB::raw('COUNT(*) as nombre_transactions_historique'),
                         DB::raw('SUM(f.montant) as total_historique'),
                         'u.prenom',
                         'u.nom',
                         'u.email',
                         'u.telephone_1'
                     )
                     ->join('users as u', 'f.donateur_id', '=', 'u.id')
                     ->where('f.statut', 'validee')
                     ->whereNull('f.deleted_at')
                     ->whereNotNull('f.donateur_id')
                     ->groupBy('f.donateur_id', 'u.prenom', 'u.nom', 'u.email', 'u.telephone_1')
                     ->having('derniere_transaction', '<', $dateLimit)
                     ->having('nombre_transactions_historique', '>=', 3) // Anciens donateurs réguliers
                     ->orderBy('derniere_transaction', 'desc')
                     ->get();
        } catch (Exception $e) {
            Log::error('Erreur donateurs inactifs', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Segmentation des donateurs
     */
    private function getSegmentationDonateurs($annee)
    {
        try {
            $donateurs = DB::table('fonds as f')
                           ->select(
                               'f.donateur_id',
                               DB::raw('COUNT(*) as frequence'),
                               DB::raw('SUM(f.montant) as montant_total'),
                               DB::raw('AVG(f.montant) as montant_moyen'),
                               DB::raw('MAX(f.date_transaction) as derniere_transaction')
                           )
                           ->whereYear('f.date_transaction', $annee)
                           ->where('f.statut', 'validee')
                           ->whereNull('f.deleted_at')
                           ->whereNotNull('f.donateur_id')
                           ->groupBy('f.donateur_id')
                           ->get();

            $segments = [
                'champions' => $donateurs->where('frequence', '>=', 12)->where('montant_total', '>=', 100000),
                'fideles' => $donateurs->where('frequence', '>=', 6)->where('montant_total', '>=', 50000)->where('montant_total', '<', 100000),
                'reguliers' => $donateurs->where('frequence', '>=', 4)->where('montant_total', '>=', 20000)->where('montant_total', '<', 50000),
                'occasionnels' => $donateurs->where('frequence', '<', 4)->where('montant_total', '>=', 10000),
                'nouveaux' => $donateurs->where('frequence', '<=', 2)->where('montant_total', '<', 10000)
            ];

            return [
                'champions' => [
                    'count' => $segments['champions']->count(),
                    'total_montant' => $segments['champions']->sum('montant_total'),
                    'pourcentage_montant' => $donateurs->sum('montant_total') > 0 ?
                        round(($segments['champions']->sum('montant_total') / $donateurs->sum('montant_total')) * 100, 2) : 0
                ],
                'fideles' => [
                    'count' => $segments['fideles']->count(),
                    'total_montant' => $segments['fideles']->sum('montant_total'),
                    'pourcentage_montant' => $donateurs->sum('montant_total') > 0 ?
                        round(($segments['fideles']->sum('montant_total') / $donateurs->sum('montant_total')) * 100, 2) : 0
                ],
                'reguliers' => [
                    'count' => $segments['reguliers']->count(),
                    'total_montant' => $segments['reguliers']->sum('montant_total'),
                    'pourcentage_montant' => $donateurs->sum('montant_total') > 0 ?
                        round(($segments['reguliers']->sum('montant_total') / $donateurs->sum('montant_total')) * 100, 2) : 0
                ],
                'occasionnels' => [
                    'count' => $segments['occasionnels']->count(),
                    'total_montant' => $segments['occasionnels']->sum('montant_total'),
                    'pourcentage_montant' => $donateurs->sum('montant_total') > 0 ?
                        round(($segments['occasionnels']->sum('montant_total') / $donateurs->sum('montant_total')) * 100, 2) : 0
                ],
                'nouveaux' => [
                    'count' => $segments['nouveaux']->count(),
                    'total_montant' => $segments['nouveaux']->sum('montant_total'),
                    'pourcentage_montant' => $donateurs->sum('montant_total') > 0 ?
                        round(($segments['nouveaux']->sum('montant_total') / $donateurs->sum('montant_total')) * 100, 2) : 0
                ]
            ];
        } catch (Exception $e) {
            Log::error('Erreur segmentation donateurs', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Validations en retard
     */
    private function getValidationsEnRetard()
    {
        try {
            return Fonds::enAttente()
                        ->where('created_at', '<', now()->subDays(7))
                        ->with(['donateur', 'culte', 'collecteur'])
                        ->orderBy('created_at', 'asc')
                        ->get()
                        ->map(function ($fond) {
                            $fond->jours_attente = now()->diffInDays($fond->created_at);
                            return $fond;
                        });
        } catch (Exception $e) {
            Log::error('Erreur validations en retard', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Échéances manquées
     */
    private function getEcheancesManquees()
    {
        try {
            return Fonds::where('est_recurrente', true)
                        ->where('prochaine_echeance', '<', now()->subDays(7))
                        ->where('statut', 'validee')
                        ->with(['donateur'])
                        ->get()
                        ->map(function ($fond) {
                            $fond->jours_retard = now()->diffInDays($fond->prochaine_echeance);
                            return $fond;
                        });
        } catch (Exception $e) {
            Log::error('Erreur échéances manquées', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Détecter les transactions suspectes
     */
    private function detecterTransactionsSuspectes()
    {
        try {
            $moyenneDons = Fonds::validees()->avg('montant');
            $seuilSuspicion = $moyenneDons * 10; // 10x la moyenne

            return Fonds::where('montant', '>', $seuilSuspicion)
                        ->orWhere(function ($query) {
                            $query->where('created_at', '>', now()->subHours(1))
                                  ->groupBy('donateur_id')
                                  ->havingRaw('COUNT(*) > 5'); // Plus de 5 transactions en 1h
                        })
                        ->with(['donateur', 'collecteur'])
                        ->orderBy('created_at', 'desc')
                        ->limit(10)
                        ->get();
        } catch (Exception $e) {
            Log::error('Erreur transactions suspectes', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Détecter une baisse significative
     */
    private function detecterBaisseSignificative()
    {
        try {
            $moisActuel = Fonds::validees()
                               ->whereMonth('date_transaction', now()->month)
                               ->whereYear('date_transaction', now()->year)
                               ->sum('montant');

            $moisPrecedent = Fonds::validees()
                                  ->whereMonth('date_transaction', now()->subMonth()->month)
                                  ->whereYear('date_transaction', now()->subMonth()->year)
                                  ->sum('montant');

            if ($moisPrecedent > 0) {
                $baisse = (($moisPrecedent - $moisActuel) / $moisPrecedent) * 100;
                return $baisse > 20 ? [
                    'detected' => true,
                    'pourcentage_baisse' => round($baisse, 2),
                    'montant_actuel' => $moisActuel,
                    'montant_precedent' => $moisPrecedent
                ] : ['detected' => false];
            }

            return ['detected' => false];
        } catch (Exception $e) {
            Log::error('Erreur détection baisse', ['error' => $e->getMessage()]);
            return ['detected' => false];
        }
    }

    /**
     * Détecter les donateurs à risque
     */
    private function detecterDonateursRisque()
    {
        try {
            return DB::table('fonds as f1')
                     ->select(
                         'f1.donateur_id',
                         'u.prenom',
                         'u.nom',
                         DB::raw('MAX(f1.date_transaction) as derniere_transaction'),
                         DB::raw('COUNT(*) as nombre_dons_historique'),
                         DB::raw('AVG(f1.montant) as don_moyen')
                     )
                     ->join('users as u', 'f1.donateur_id', '=', 'u.id')
                     ->where('f1.statut', 'validee')
                     ->whereNull('f1.deleted_at')
                     ->whereNotNull('f1.donateur_id')
                     ->where('f1.date_transaction', '>=', now()->subYear()) // Actifs l'année dernière
                     ->groupBy('f1.donateur_id', 'u.prenom', 'u.nom')
                     ->having('nombre_dons_historique', '>=', 6) // Réguliers
                     ->having('derniere_transaction', '<', now()->subMonths(3)) // Mais inactifs depuis 3 mois
                     ->orderBy('don_moyen', 'desc')
                     ->get();
        } catch (Exception $e) {
            Log::error('Erreur donateurs à risque', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Calculer le score de diversification
     */
    private function calculerScoreDiversification($dimes, $offrandes, $dons)
    {
        try {
            $total = $dimes + $offrandes + $dons;
            if ($total <= 0) return 0;

            $ratios = [$dimes / $total, $offrandes / $total, $dons / $total];

            // Indice de diversification basé sur l'entropie de Shannon
            $entropie = 0;
            foreach ($ratios as $ratio) {
                if ($ratio > 0) {
                    $entropie -= $ratio * log($ratio, 3); // Log base 3 car 3 catégories
                }
            }

            return round($entropie * 100, 2); // Score sur 100
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Calculer le score de stabilité
     */
    private function calculerScoreStabilite($annee)
    {
        try {
            $donneesMensuelles = $this->getEvolutionMensuelle($annee);
            if ($donneesMensuelles->count() < 6) return 0;

            $montants = $donneesMensuelles->pluck('total');
            $moyenne = $montants->avg();

            if ($moyenne <= 0) return 0;

            $coefficientVariation = ($montants->std() / $moyenne) * 100;

            // Score inversé: moins de variation = plus de stabilité
            return round(max(0, 100 - $coefficientVariation), 2);
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Export Excel avec librairie
     */
    private function exportExcelAvecLibrairie($fonds)
    {
        try {
            // Si Laravel Excel est installé, créer un export simple
            if (class_exists('\Maatwebsite\Excel\Facades\Excel')) {

                $data = $fonds->map(function ($fond) {
                    return [
                        'numero_transaction' => $fond->numero_transaction,
                        'date_transaction' => $fond->date_transaction ? $fond->date_transaction->format('d/m/Y') : '',
                        'heure_transaction' => $fond->heure_transaction ? $fond->heure_transaction->format('H:i') : '',
                        'montant' => $fond->montant,
                        'devise' => $fond->devise,
                        'type_transaction' => $fond->type_transaction_libelle ?? $fond->type_transaction,
                        'categorie' => $fond->categorie,
                        'donateur' => $fond->nom_donateur ?? 'N/A',
                        'email_donateur' => $fond->donateur?->email ?? '',
                        'mode_paiement' => $fond->mode_paiement_libelle ?? $fond->mode_paiement,
                        'reference_paiement' => $fond->reference_paiement ?? '',
                        'statut' => $fond->statut_libelle ?? $fond->statut,
                        'culte' => $fond->culte?->titre ?? '',
                        'date_culte' => $fond->culte?->date_culte?->format('d/m/Y') ?? '',
                        'collecteur' => $fond->collecteur ? "{$fond->collecteur->prenom} {$fond->collecteur->nom}" : '',
                        'validateur' => $fond->validateur ? "{$fond->validateur->prenom} {$fond->validateur->nom}" : '',
                        'date_validation' => $fond->validee_le?->format('d/m/Y H:i') ?? '',
                        'recu_emis' => $fond->recu_emis ? 'Oui' : 'Non',
                        'numero_recu' => $fond->numero_recu ?? '',
                        'est_recurrente' => $fond->est_recurrente ? 'Oui' : 'Non',
                        'destination' => $fond->destination ?? '',
                        'notes' => $fond->notes_validation ?? $fond->instructions_donateur ?? '',
                        'date_creation' => $fond->created_at->format('d/m/Y H:i')
                    ];
                })->toArray();

                $filename = 'transactions_fonds_' . date('Y-m-d_H-i-s') . '.xlsx';

                // Créer un export simple avec une classe anonyme
                $export = new class($data) implements FromArray, WithHeadings {
                    private $data;

                    public function __construct($data)
                    {
                        $this->data = $data;
                    }

                    public function array(): array
                    {
                        return $this->data;
                    }

                    public function headings(): array
                    {
                        return [
                            'Numéro Transaction',
                            'Date Transaction',
                            'Heure',
                            'Montant',
                            'Devise',
                            'Type Transaction',
                            'Catégorie',
                            'Donateur',
                            'Email Donateur',
                            'Mode Paiement',
                            'Référence Paiement',
                            'Statut',
                            'Culte',
                            'Date Culte',
                            'Collecteur',
                            'Validateur',
                            'Date Validation',
                            'Reçu Émis',
                            'Numéro Reçu',
                            'Est Récurrente',
                            'Destination',
                            'Notes',
                            'Date Création'
                        ];
                    }
                };

                return Excel::download($export, $filename);
            }

            // Fallback si Laravel Excel n'est pas installé
            throw new Exception("Laravel Excel non installé");

        } catch (Exception $e) {
            Log::error('Erreur export Excel avec librairie', ['error' => $e->getMessage()]);
            // Fallback vers export CSV avec headers Excel
            return $this->exportExcel($fonds);
        }
    }

    /**
     * Rapport par donateur
     */
    private function getRapportDonateur($dateDebut, $dateFin)
    {
        try {
            return DB::table('fonds as f')
                     ->select(
                         'f.donateur_id',
                         'u.prenom',
                         'u.nom',
                         'u.email',
                         DB::raw('COUNT(*) as nombre_dons'),
                         DB::raw('SUM(f.montant) as total_dons'),
                         DB::raw('AVG(f.montant) as don_moyen'),
                         DB::raw('MIN(f.date_transaction) as premier_don'),
                         DB::raw('MAX(f.date_transaction) as dernier_don'),
                         DB::raw('COUNT(CASE WHEN f.type_transaction = "dime" THEN 1 END) as nombre_dimes'),
                         DB::raw('SUM(CASE WHEN f.type_transaction = "dime" THEN f.montant ELSE 0 END) as total_dimes'),
                         DB::raw('COUNT(CASE WHEN f.recu_emis = 1 THEN 1 END) as recus_emis')
                     )
                     ->join('users as u', 'f.donateur_id', '=', 'u.id')
                     ->whereBetween('f.date_transaction', [$dateDebut, $dateFin])
                     ->where('f.statut', 'validee')
                     ->whereNull('f.deleted_at')
                     ->whereNotNull('f.donateur_id')
                     ->groupBy('f.donateur_id', 'u.prenom', 'u.nom', 'u.email')
                     ->orderBy('total_dons', 'desc')
                     ->get();
        } catch (Exception $e) {
            Log::error('Erreur rapport donateur', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Rapport par culte
     */
    private function getRapportCulte($dateDebut, $dateFin)
    {
        try {
            return DB::table('fonds as f')
                     ->select(
                         'f.culte_id',
                         'c.titre as titre_culte',
                         'c.date_culte',
                         'c.type_culte',
                         DB::raw('COUNT(*) as nombre_transactions'),
                         DB::raw('SUM(f.montant) as total_collecte'),
                         DB::raw('AVG(f.montant) as don_moyen'),
                         DB::raw('COUNT(DISTINCT f.donateur_id) as donateurs_uniques'),
                         DB::raw('SUM(CASE WHEN f.type_transaction = "dime" THEN f.montant ELSE 0 END) as dimes'),
                         DB::raw('SUM(CASE WHEN f.type_transaction LIKE "offrande%" THEN f.montant ELSE 0 END) as offrandes')
                     )
                     ->join('cultes as c', 'f.culte_id', '=', 'c.id')
                     ->whereBetween('f.date_transaction', [$dateDebut, $dateFin])
                     ->where('f.statut', 'validee')
                     ->whereNull('f.deleted_at')
                     ->whereNotNull('f.culte_id')
                     ->groupBy('f.culte_id', 'c.titre', 'c.date_culte', 'c.type_culte')
                     ->orderBy('c.date_culte', 'desc')
                     ->get();
        } catch (Exception $e) {
            Log::error('Erreur rapport culte', ['error' => $e->getMessage()]);
            return collect();
        }
    }

    /**
     * Rapport comptable
     */
    private function getRapportComptable($dateDebut, $dateFin)
    {
        try {
            $resume = [
                'periode' => [
                    'debut' => $dateDebut,
                    'fin' => $dateFin
                ],
                'totaux_par_type' => $this->getRepartitionParType($dateDebut, $dateFin),
                'totaux_par_mode' => $this->getRepartitionParMode($dateDebut, $dateFin),
                'transactions_par_statut' => DB::table('fonds')
                    ->select('statut', DB::raw('COUNT(*) as nombre'), DB::raw('SUM(montant) as total'))
                    ->whereBetween('date_transaction', [$dateDebut, $dateFin])
                    ->whereNull('deleted_at')
                    ->groupBy('statut')
                    ->get(),
                'recus_fiscaux' => [
                    'demandes' => Fonds::whereBetween('date_transaction', [$dateDebut, $dateFin])
                                       ->where('recu_demande', true)->count(),
                    'emis' => Fonds::whereBetween('date_transaction', [$dateDebut, $dateFin])
                                   ->where('recu_emis', true)->count(),
                    'en_attente' => Fonds::whereBetween('date_transaction', [$dateDebut, $dateFin])
                                         ->where('recu_demande', true)
                                         ->where('recu_emis', false)->count()
                ],
                'verification' => [
                    'transactions_verifiees' => Fonds::whereBetween('date_transaction', [$dateDebut, $dateFin])
                                                     ->whereNotNull('derniere_verification')->count(),
                    'transactions_non_verifiees' => Fonds::whereBetween('date_transaction', [$dateDebut, $dateFin])
                                                          ->where('statut', 'validee')
                                                          ->whereNull('derniere_verification')->count()
                ]
            ];

            return $resume;
        } catch (Exception $e) {
            Log::error('Erreur rapport comptable', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Rapport mensuel
     */
    private function getRapportMensuel($dateDebut, $dateFin)
    {
        try {
            return [
                'resume' => $this->getKPIs($dateDebut, $dateFin),
                'transactions_par_jour' => DB::table('fonds')
                    ->select(
                        DB::raw('DATE(date_transaction) as date'),
                        DB::raw('SUM(montant) as total'),
                        DB::raw('COUNT(*) as nombre')
                    )
                    ->whereBetween('date_transaction', [$dateDebut, $dateFin])
                    ->where('statut', 'validee')
                    ->whereNull('deleted_at')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get(),
                'top_donateurs' => $this->getTopDonateurs($dateDebut, $dateFin),
                'repartition_types' => $this->getRepartitionParType($dateDebut, $dateFin),
                'performance_collecteurs' => DB::table('fonds as f')
                    ->select(
                        'f.collecteur_id',
                        'u.prenom',
                        'u.nom',
                        DB::raw('COUNT(*) as nombre_collectes'),
                        DB::raw('SUM(f.montant) as total_collecte')
                    )
                    ->join('users as u', 'f.collecteur_id', '=', 'u.id')
                    ->whereBetween('f.date_transaction', [$dateDebut, $dateFin])
                    ->where('f.statut', 'validee')
                    ->whereNull('f.deleted_at')
                    ->whereNotNull('f.collecteur_id')
                    ->groupBy('f.collecteur_id', 'u.prenom', 'u.nom')
                    ->orderBy('total_collecte', 'desc')
                    ->get()
            ];
        } catch (Exception $e) {
            Log::error('Erreur rapport mensuel', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Rapport annuel
     */
    private function getRapportAnnuel($annee)
    {
        try {
            $dateDebut = Carbon::create($annee, 1, 1)->startOfDay();
            $dateFin = Carbon::create($annee, 12, 31)->endOfDay();

            return [
                'resume_annuel' => $this->getKPIs($dateDebut, $dateFin),
                'evolution_mensuelle' => $this->getEvolutionMensuelle($annee),
                'analyse_trimestrielle' => $this->getAnalyseTrimestrielle($annee),
                'donateurs_reguliers' => $this->getTopDonateurs($dateDebut, $dateFin, 20),
                'performance_saisonniere' => $this->getAnalyseSaisonnalite($annee),
                'comparaison_annee_precedente' => $this->getComparaisonTypes($annee),
                'projections' => $this->calculerProjectionsMensuelles($this->getHistoriqueTroisDernieresAnnees())
            ];
        } catch (Exception $e) {
            Log::error('Erreur rapport annuel', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Analyse trimestrielle
     */
    private function getAnalyseTrimestrielle($annee)
    {
        try {
            return DB::table('fonds')
                     ->select(
                         DB::raw('QUARTER(date_transaction) as trimestre'),
                         DB::raw('SUM(montant) as total'),
                         DB::raw('COUNT(*) as nombre'),
                         DB::raw('AVG(montant) as moyenne'),
                         DB::raw('COUNT(DISTINCT donateur_id) as donateurs_uniques'),
                         DB::raw('MIN(date_transaction) as debut_trimestre'),
                         DB::raw('MAX(date_transaction) as fin_trimestre')
                     )
                     ->whereYear('date_transaction', $annee)
                     ->where('statut', 'validee')
                     ->whereNull('deleted_at')
                     ->groupBy('trimestre')
                     ->orderBy('trimestre')
                     ->get()
                     ->map(function ($item) {
                         $item->nom_trimestre = "T{$item->trimestre} {$item->debut_trimestre->format('Y')}";
                         return $item;
                     });
        } catch (Exception $e) {
            Log::error('Erreur analyse trimestrielle', ['error' => $e->getMessage()]);
            return collect();
        }
    }













    /**
     * Analyse de fidélisation des donateurs
     */
    private function getAnalyseFidelisation()
    {
        try {
            // Taux de rétention des 12 derniers mois
            $anneeCourante = now()->year;
            $anneePrecedente = $anneeCourante - 1;

            $donateursAnneePrecedente = Fonds::whereYear('date_transaction', $anneePrecedente)
                                             ->where('statut', 'validee')
                                             ->whereNotNull('donateur_id')
                                             ->distinct('donateur_id')
                                             ->count('donateur_id');

            $donateursRetenus = Fonds::whereYear('date_transaction', $anneeCourante)
                                     ->where('statut', 'validee')
                                     ->whereIn('donateur_id', function($query) use ($anneePrecedente) {
                                         $query->select('donateur_id')
                                               ->from('fonds')
                                               ->whereYear('date_transaction', $anneePrecedente)
                                               ->where('statut', 'validee')
                                               ->whereNotNull('donateur_id');
                                     })
                                     ->distinct('donateur_id')
                                     ->count('donateur_id');

            $tauxRetention = $donateursAnneePrecedente > 0 ? ($donateursRetenus / $donateursAnneePrecedente) * 100 : 0;

            // Analyse des donateurs réguliers (3+ transactions)
            $donateursReguliers = DB::table('fonds')
                                    ->select('donateur_id', DB::raw('COUNT(*) as nb_transactions'))
                                    ->where('statut', 'validee')
                                    ->whereNotNull('donateur_id')
                                    ->whereNull('deleted_at')
                                    ->groupBy('donateur_id')
                                    ->having('nb_transactions', '>=', 3)
                                    ->get();

            // Transactions récurrentes
            $transactionsRecurrentes = Fonds::where('est_recurrente', true)->count();
            $totalTransactions = Fonds::count();
            $pourcentageRecurrence = $totalTransactions > 0 ? ($transactionsRecurrentes / $totalTransactions) * 100 : 0;

            // Donateurs inactifs (plus de 6 mois)
            $donateursInactifs = DB::table('fonds as f1')
                                   ->select('f1.donateur_id')
                                   ->where('f1.statut', 'validee')
                                   ->whereNotNull('f1.donateur_id')
                                   ->whereNull('f1.deleted_at')
                                   ->groupBy('f1.donateur_id')
                                   ->havingRaw('MAX(f1.date_transaction) < ?', [now()->subMonths(6)])
                                   ->havingRaw('COUNT(*) >= 2') // Avaient donné au moins 2 fois
                                   ->get();

            return [
                'taux_retention' => round($tauxRetention, 2),
                'donateurs_precedents' => $donateursAnneePrecedente,
                'donateurs_retenus' => $donateursRetenus,
                'donateurs_reguliers' => [
                    'count' => $donateursReguliers->count(),
                    'pourcentage' => $donateursReguliers->count() > 0 ?
                        ($donateursReguliers->count() / Fonds::distinct('donateur_id')->whereNotNull('donateur_id')->count()) * 100 : 0
                ],
                'recurrence' => [
                    'transactions' => $transactionsRecurrentes,
                    'pourcentage' => round($pourcentageRecurrence, 2)
                ],
                'donateurs_inactifs' => [
                    'count' => $donateursInactifs->count(),
                    'pourcentage' => $donateursInactifs->count() > 0 ?
                        ($donateursInactifs->count() / Fonds::distinct('donateur_id')->whereNotNull('donateur_id')->count()) * 100 : 0
                ]
            ];
        } catch (Exception $e) {
            Log::error('Erreur analyse fidélisation', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Analyse approfondie des donateurs
     */
    private function getAnalyseDonateurs($annee)
    {
        try {
            $dateDebut = Carbon::create($annee, 1, 1);
            $dateFin = Carbon::create($annee, 12, 31);

            // Segmentation basique des donateurs
            $donateurs = DB::table('fonds as f')
                           ->select(
                               'f.donateur_id',
                               DB::raw('COUNT(*) as frequence'),
                               DB::raw('SUM(f.montant) as montant_total'),
                               DB::raw('AVG(f.montant) as montant_moyen'),
                               DB::raw('MAX(f.date_transaction) as derniere_transaction'),
                               DB::raw('DATEDIFF(?, MAX(f.date_transaction)) as jours_depuis_dernier_don')
                           )
                           ->addBinding([$dateFin], 'select')
                           ->whereBetween('f.date_transaction', [$dateDebut, $dateFin])
                           ->where('f.statut', 'validee')
                           ->whereNotNull('f.donateur_id')
                           ->whereNull('f.deleted_at')
                           ->groupBy('f.donateur_id')
                           ->get();

            // Classification simple
            $champions = $donateurs->where('frequence', '>=', 12)->where('montant_total', '>=', 100000);
            $reguliers = $donateurs->where('frequence', '>=', 6)->where('montant_total', '>=', 50000)->where('montant_total', '<', 100000);
            $occasionnels = $donateurs->where('frequence', '<', 6);
            $inactifs = $donateurs->where('jours_depuis_dernier_don', '>', 90);

            // Analyse des préférences
            $typesPopulaires = Fonds::whereBetween('date_transaction', [$dateDebut, $dateFin])
                                   ->where('statut', 'validee')
                                   ->groupBy('type_transaction')
                                   ->select('type_transaction', DB::raw('COUNT(*) as count'), DB::raw('AVG(montant) as moyenne'))
                                   ->orderBy('count', 'desc')
                                   ->get();

            $modesPopulaires = Fonds::whereBetween('date_transaction', [$dateDebut, $dateFin])
                                   ->where('statut', 'validee')
                                   ->groupBy('mode_paiement')
                                   ->select('mode_paiement', DB::raw('COUNT(*) as count'))
                                   ->orderBy('count', 'desc')
                                   ->get();

            return [
                'total_donateurs' => $donateurs->count(),
                'segmentation' => [
                    'champions' => [
                        'count' => $champions->count(),
                        'pourcentage' => $donateurs->count() > 0 ? ($champions->count() / $donateurs->count()) * 100 : 0,
                        'contribution_totale' => $champions->sum('montant_total')
                    ],
                    'reguliers' => [
                        'count' => $reguliers->count(),
                        'pourcentage' => $donateurs->count() > 0 ? ($reguliers->count() / $donateurs->count()) * 100 : 0,
                        'contribution_totale' => $reguliers->sum('montant_total')
                    ],
                    'occasionnels' => [
                        'count' => $occasionnels->count(),
                        'pourcentage' => $donateurs->count() > 0 ? ($occasionnels->count() / $donateurs->count()) * 100 : 0,
                        'contribution_totale' => $occasionnels->sum('montant_total')
                    ],
                    'inactifs' => [
                        'count' => $inactifs->count(),
                        'pourcentage' => $donateurs->count() > 0 ? ($inactifs->count() / $donateurs->count()) * 100 : 0
                    ]
                ],
                'preferences' => [
                    'types_populaires' => $typesPopulaires,
                    'modes_populaires' => $modesPopulaires
                ],
                'statistiques' => [
                    'don_moyen' => round($donateurs->avg('montant_moyen'), 2),
                    'frequence_moyenne' => round($donateurs->avg('frequence'), 2),
                    'contribution_moyenne' => round($donateurs->avg('montant_total'), 2)
                ]
            ];
        } catch (Exception $e) {
            Log::error('Erreur analyse donateurs', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Analyse des tendances
     */
    private function getAnalyseTendances($annee)
    {
        try {
            // Évolution mensuelle de l'année
            $evolutionMensuelle = $this->getEvolutionMensuelle($annee);

            // Comparaison avec l'année précédente
            $evolutionPrecedente = $this->getEvolutionMensuelle($annee - 1);

            // Calcul des tendances
            $tendancesComparees = [];
            for ($mois = 1; $mois <= 12; $mois++) {
                $actuel = $evolutionMensuelle->where('mois', $mois)->first();
                $precedent = $evolutionPrecedente->where('mois', $mois)->first();

                $totalActuel = $actuel ? $actuel->total : 0;
                $totalPrecedent = $precedent ? $precedent->total : 0;

                $variation = 0;
                if ($totalPrecedent > 0) {
                    $variation = (($totalActuel - $totalPrecedent) / $totalPrecedent) * 100;
                }

                $tendancesComparees[] = [
                    'mois' => $mois,
                    'nom_mois' => Carbon::create()->month($mois)->locale('fr')->monthName,
                    'actuel' => $totalActuel,
                    'precedent' => $totalPrecedent,
                    'variation' => round($variation, 2),
                    'tendance' => $variation > 5 ? 'hausse' : ($variation < -5 ? 'baisse' : 'stable')
                ];
            }

            // Détection d'anomalies simples
            $moyenneAnnuelle = $evolutionMensuelle->avg('total');
            $ecartType = sqrt($evolutionMensuelle->map(function($item) use ($moyenneAnnuelle) {
                return pow($item->total - $moyenneAnnuelle, 2);
            })->avg());

            $anomalies = $evolutionMensuelle->filter(function($item) use ($moyenneAnnuelle, $ecartType) {
                return abs($item->total - $moyenneAnnuelle) > (2 * $ecartType);
            });

            // Tendance générale de l'année
            $premierTrimestre = $evolutionMensuelle->whereIn('mois', [1,2,3])->avg('total');
            $dernierTrimestre = $evolutionMensuelle->whereIn('mois', [10,11,12])->avg('total');

            $tendanceGenerale = 'stable';
            $variationGenerale = 0;
            if ($premierTrimestre > 0) {
                $variationGenerale = (($dernierTrimestre - $premierTrimestre) / $premierTrimestre) * 100;
                $tendanceGenerale = $variationGenerale > 10 ? 'croissante' : ($variationGenerale < -10 ? 'decroissante' : 'stable');
            }

            return [
                'evolution_mensuelle' => $evolutionMensuelle,
                'comparaison_annee_precedente' => collect($tendancesComparees),
                'tendance_generale' => [
                    'direction' => $tendanceGenerale,
                    'variation' => round($variationGenerale, 2)
                ],
                'anomalies' => $anomalies,
                'volatilite' => round(($ecartType / max($moyenneAnnuelle, 1)) * 100, 2),
                'meilleur_mois' => $evolutionMensuelle->sortByDesc('total')->first(),
                'moins_bon_mois' => $evolutionMensuelle->sortBy('total')->first()
            ];
        } catch (Exception $e) {
            Log::error('Erreur analyse tendances', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Calculer projection mensuelle
     */
    private function calculerProjectionMensuelle($historique)
    {
        try {
            if ($historique->count() < 12) {
                return [
                    'erreur' => 'Données historiques insuffisantes (minimum 12 mois)',
                    'donnees_disponibles' => $historique->count()
                ];
            }

            $projections = [];
            $anneeCourante = now()->year;

            // Calcul par mois basé sur la moyenne historique et la tendance
            for ($mois = 1; $mois <= 12; $mois++) {
                $donneesHistoriques = $historique->where('mois', $mois);

                if ($donneesHistoriques->count() >= 2) {
                    // Moyenne historique pour ce mois
                    $moyenneHistorique = $donneesHistoriques->avg('total');

                    // Tendance simple : comparaison dernière année vs moyenne
                    $derniereAnnee = $donneesHistoriques->sortByDesc('annee')->first();
                    $facteurTendance = 1;

                    if ($derniereAnnee && $moyenneHistorique > 0) {
                        $facteurTendance = $derniereAnnee->total / $moyenneHistorique;
                        // Limiter les variations extrêmes
                        $facteurTendance = max(0.7, min(1.3, $facteurTendance));
                    }

                    $projection = $moyenneHistorique * $facteurTendance;

                    // Ajustement saisonnier simple
                    $facteurSaisonnier = 1;
                    if (in_array($mois, [12, 4])) $facteurSaisonnier = 1.2; // Noël, Pâques
                    if (in_array($mois, [7, 8])) $facteurSaisonnier = 0.8; // Vacances

                    $projectionFinale = $projection * $facteurSaisonnier;

                    $projections[$mois] = [
                        'mois' => $mois,
                        'nom_mois' => Carbon::create()->month($mois)->locale('fr')->monthName,
                        'projection' => round($projectionFinale, 2),
                        'moyenne_historique' => round($moyenneHistorique, 2),
                        'facteur_tendance' => round($facteurTendance, 3),
                        'facteur_saisonnier' => $facteurSaisonnier,
                        'donnees_historiques' => $donneesHistoriques->count()
                    ];
                }
            }

            $totalProjecte = array_sum(array_column($projections, 'projection'));

            return [
                'projections' => $projections,
                'total_projete_annee' => round($totalProjecte, 2),
                'methodologie' => 'Moyenne historique avec ajustements tendance et saisonnalité',
                'fiabilite' => $historique->count() >= 24 ? 'bonne' : 'moyenne'
            ];

        } catch (Exception $e) {
            Log::error('Erreur projection mensuelle', ['error' => $e->getMessage()]);
            return ['erreur' => 'Erreur dans le calcul des projections'];
        }
    }

    /**
     * Calculer projection annuelle
     */
    private function calculerProjectionAnnuelle($historique)
    {
        try {
            if ($historique->count() < 24) {
                return [
                    'erreur' => 'Données historiques insuffisantes (minimum 24 mois)',
                    'donnees_disponibles' => $historique->count()
                ];
            }

            // Grouper par année et calculer les totaux
            $donneesAnnuelles = $historique->groupBy('annee')->map(function ($mois) {
                return [
                    'annee' => $mois->first()->annee,
                    'total' => $mois->sum('total'),
                    'nombre_mois' => $mois->count()
                ];
            })->values()->sortBy('annee');

            if ($donneesAnnuelles->count() < 2) {
                return ['erreur' => 'Au moins 2 années complètes nécessaires'];
            }

            // Calcul de la tendance simple
            $premiereAnnee = $donneesAnnuelles->first();
            $derniereAnnee = $donneesAnnuelles->last();
            $nombreAnnees = $donneesAnnuelles->count();

            $croissanceAnnuelleMoyenne = 0;
            if ($premiereAnnee['total'] > 0 && $nombreAnnees > 1) {
                $croissanceAnnuelleMoyenne = pow($derniereAnnee['total'] / $premiereAnnee['total'], 1 / ($nombreAnnees - 1)) - 1;
            }

            // Projection pour l'année suivante
            $anneeSuivante = now()->year + 1;
            $projectionBase = $derniereAnnee['total'] * (1 + $croissanceAnnuelleMoyenne);

            // Calcul de la volatilité
            $moyenne = $donneesAnnuelles->avg('total');
            $variance = $donneesAnnuelles->map(function($annee) use ($moyenne) {
                return pow($annee['total'] - $moyenne, 2);
            })->avg();
            $volatilite = sqrt($variance) / $moyenne * 100;

            // Scénarios basés sur la volatilité
            $facteurVolatilite = $volatilite / 100;
            $scenarios = [
                'optimiste' => round($projectionBase * (1 + $facteurVolatilite), 2),
                'realiste' => round($projectionBase, 2),
                'pessimiste' => round($projectionBase * (1 - $facteurVolatilite), 2)
            ];

            return [
                'annee_projection' => $anneeSuivante,
                'projection_principale' => round($projectionBase, 2),
                'croissance_moyenne' => round($croissanceAnnuelleMoyenne * 100, 2),
                'volatilite' => round($volatilite, 2),
                'scenarios' => $scenarios,
                'donnees_historiques' => $donneesAnnuelles,
                'fiabilite' => $donneesAnnuelles->count() >= 3 ? 'bonne' : 'moyenne'
            ];

        } catch (Exception $e) {
            Log::error('Erreur projection annuelle', ['error' => $e->getMessage()]);
            return ['erreur' => 'Erreur dans le calcul de la projection annuelle'];
        }
    }

    /**
     * Calculer confiance projection
     */
    private function calculerConfianceProjection($historique)
    {
        try {
            if ($historique->count() < 12) {
                return [
                    'score' => 0,
                    'niveau' => 'très_faible',
                    'commentaire' => 'Données insuffisantes (moins de 12 mois)'
                ];
            }

            $score = 0;
            $details = [];

            // 1. Quantité de données (30%)
            $quantiteDonnees = min(100, ($historique->count() / 36) * 100);
            $score += $quantiteDonnees * 0.30;
            $details['quantite_donnees'] = round($quantiteDonnees, 1);

            // 2. Régularité des données (25%)
            $moisUniques = $historique->groupBy(['annee', 'mois'])->count();
            $moisPossibles = now()->diffInMonths(now()->subYears(3));
            $regularite = $moisPossibles > 0 ? ($moisUniques / $moisPossibles) * 100 : 0;
            $score += $regularite * 0.25;
            $details['regularite'] = round($regularite, 1);

            // 3. Stabilité (pas de variations extrêmes) (25%)
            $valeurs = $historique->pluck('total');
            $moyenne = $valeurs->avg();
            $ecartType = sqrt($valeurs->map(function($val) use ($moyenne) {
                return pow($val - $moyenne, 2);
            })->avg());

            $coefficientVariation = $moyenne > 0 ? ($ecartType / $moyenne) * 100 : 100;
            $stabilite = max(0, 100 - $coefficientVariation);
            $score += $stabilite * 0.25;
            $details['stabilite'] = round($stabilite, 1);

            // 4. Absence d'outliers extrêmes (20%)
            $q1 = $valeurs->sort()->values()->get(intval($valeurs->count() * 0.25));
            $q3 = $valeurs->sort()->values()->get(intval($valeurs->count() * 0.75));
            $iqr = $q3 - $q1;

            $outliers = $valeurs->filter(function($val) use ($q1, $q3, $iqr) {
                return $val < ($q1 - 1.5 * $iqr) || $val > ($q3 + 1.5 * $iqr);
            });

            $absenceOutliers = max(0, 100 - ($outliers->count() / $valeurs->count() * 100));
            $score += $absenceOutliers * 0.20;
            $details['absence_outliers'] = round($absenceOutliers, 1);

            // Détermination du niveau
            $niveau = 'très_faible';
            if ($score >= 80) $niveau = 'très_élevée';
            elseif ($score >= 65) $niveau = 'élevée';
            elseif ($score >= 50) $niveau = 'moyenne';
            elseif ($score >= 35) $niveau = 'faible';

            $interpretation = match($niveau) {
                'très_élevée' => 'Projections très fiables, utilisables pour la planification stratégique',
                'élevée' => 'Projections fiables, bonnes pour la planification opérationnelle',
                'moyenne' => 'Projections correctes, à surveiller et ajuster régulièrement',
                'faible' => 'Projections indicatives, prudence recommandée',
                'très_faible' => 'Projections peu fiables, collecte de données additionnelles nécessaire',
                default => 'Niveau indéterminé'
            };

            return [
                'score_global' => round($score, 1),
                'niveau' => $niveau,
                'interpretation' => $interpretation,
                'details' => $details,
                'marge_erreur_estimee' => round((100 - $score) / 4, 1), // Estimation simple
                'recommandations' => $this->genererRecommandationsSimples($details)
            ];

        } catch (Exception $e) {
            Log::error('Erreur calcul confiance projection', ['error' => $e->getMessage()]);
            return [
                'score_global' => 0,
                'niveau' => 'indetermine',
                'erreur' => 'Impossible de calculer la confiance'
            ];
        }
    }



    /**
 * Recommander des objectifs financiers basés sur l'historique
 */
private function recommanderObjectifs($historique)
{
    try {
        if ($historique->count() < 12) {
            return [
                'erreur' => 'Données historiques insuffisantes (minimum 12 mois)',
                'objectifs' => []
            ];
        }

        // Analyse de l'historique
        $donneesAnnuelles = $historique->groupBy('annee')->map(function ($mois) {
            return [
                'annee' => $mois->first()->annee,
                'total' => $mois->sum('total'),
                'nombre_mois' => $mois->count(),
                'moyenne_mensuelle' => $mois->avg('total')
            ];
        })->values()->sortBy('annee');

        if ($donneesAnnuelles->count() < 2) {
            return [
                'erreur' => 'Au moins 2 années de données nécessaires',
                'objectifs' => []
            ];
        }

        // Calcul de la croissance historique
        $derniereAnnee = $donneesAnnuelles->last();
        $premiereAnnee = $donneesAnnuelles->first();
        $nombreAnnees = $donneesAnnuelles->count();

        $croissanceAnnuelleMoyenne = 0;
        if ($premiereAnnee['total'] > 0 && $nombreAnnees > 1) {
            $croissanceAnnuelleMoyenne = pow($derniereAnnee['total'] / $premiereAnnee['total'], 1 / ($nombreAnnees - 1)) - 1;
        }

        // Calcul de la stabilité
        $moyenneGenerale = $donneesAnnuelles->avg('total');
        $ecartType = sqrt($donneesAnnuelles->map(function($annee) use ($moyenneGenerale) {
            return pow($annee['total'] - $moyenneGenerale, 2);
        })->avg());

        $coefficientVariation = $moyenneGenerale > 0 ? ($ecartType / $moyenneGenerale) * 100 : 100;
        $stabilite = max(0, 100 - $coefficientVariation);

        // Base de calcul pour les objectifs
        $baseCalcul = $derniereAnnee['total'];

        // Facteurs d'ajustement selon la performance historique
        $facteurStabilite = $stabilite / 100;
        $facteurCroissance = max(-0.1, min(0.3, $croissanceAnnuelleMoyenne)); // Limité entre -10% et +30%

        // Objectifs recommandés
        $objectifs = [
            'conservateur' => [
                'montant_annuel' => round($baseCalcul * (1 + max(0, $facteurCroissance * 0.5)), 2),
                'croissance_cible' => round(max(0, $facteurCroissance * 0.5) * 100, 1),
                'description' => 'Objectif sûr basé sur la tendance historique modérée',
                'probabilite_atteinte' => min(90, 70 + ($stabilite * 0.2))
            ],
            'realiste' => [
                'montant_annuel' => round($baseCalcul * (1 + $facteurCroissance), 2),
                'croissance_cible' => round($facteurCroissance * 100, 1),
                'description' => 'Objectif basé sur la croissance historique moyenne',
                'probabilite_atteinte' => min(75, 50 + ($stabilite * 0.3))
            ],
            'ambitieux' => [
                'montant_annuel' => round($baseCalcul * (1 + $facteurCroissance * 1.5), 2),
                'croissance_cible' => round($facteurCroissance * 1.5 * 100, 1),
                'description' => 'Objectif optimiste pour stimuler la performance',
                'probabilite_atteinte' => min(60, 30 + ($stabilite * 0.4))
            ]
        ];

        // Objectifs mensuels détaillés pour l'année suivante
        $objectifsMensuels = [];
        $anneeSuivante = now()->year + 1;

        for ($mois = 1; $mois <= 12; $mois++) {
            // Moyenne historique pour ce mois
            $donneesHistoriquesMois = $historique->where('mois', $mois);
            $moyenneMois = $donneesHistoriquesMois->count() > 0 ?
                $donneesHistoriquesMois->avg('total') : ($baseCalcul / 12);

            // Facteur saisonnier
            $facteurSaisonnier = 1;
            if (in_array($mois, [12, 4])) $facteurSaisonnier = 1.15; // Noël, Pâques
            if (in_array($mois, [7, 8])) $facteurSaisonnier = 0.85; // Vacances d'été

            $objectifRealisteMois = $moyenneMois * (1 + $facteurCroissance) * $facteurSaisonnier;

            $objectifsMensuels[$mois] = [
                'mois' => $mois,
                'nom_mois' => Carbon::create()->month($mois)->locale('fr')->monthName,
                'objectif' => round($objectifRealisteMois, 2),
                'moyenne_historique' => round($moyenneMois, 2),
                'facteur_saisonnier' => $facteurSaisonnier
            ];
        }

        // Recommandations d'actions
        $recommandationsActions = [];

        if ($croissanceAnnuelleMoyenne < 0) {
            $recommandationsActions[] = [
                'priorite' => 'haute',
                'action' => 'Analyser les causes de la décroissance et mettre en place des actions correctives',
                'objectif' => 'Stopper la baisse et retrouver la croissance'
            ];
        } elseif ($croissanceAnnuelleMoyenne < 0.05) {
            $recommandationsActions[] = [
                'priorite' => 'moyenne',
                'action' => 'Diversifier les sources de revenus et améliorer la fidélisation',
                'objectif' => 'Atteindre une croissance de 5-10% annuelle'
            ];
        }

        if ($stabilite < 70) {
            $recommandationsActions[] = [
                'priorite' => 'haute',
                'action' => 'Développer les dons récurrents pour stabiliser les revenus',
                'objectif' => 'Réduire la volatilité mensuelle'
            ];
        }

        // Performance nécessaire par rapport à l'année précédente
        $performanceRequise = [
            'conservateur' => [
                'augmentation_necessaire' => round(($objectifs['conservateur']['montant_annuel'] - $baseCalcul), 2),
                'pourcentage_augmentation' => round((($objectifs['conservateur']['montant_annuel'] - $baseCalcul) / $baseCalcul) * 100, 2)
            ],
            'realiste' => [
                'augmentation_necessaire' => round(($objectifs['realiste']['montant_annuel'] - $baseCalcul), 2),
                'pourcentage_augmentation' => round((($objectifs['realiste']['montant_annuel'] - $baseCalcul) / $baseCalcul) * 100, 2)
            ],
            'ambitieux' => [
                'augmentation_necessaire' => round(($objectifs['ambitieux']['montant_annuel'] - $baseCalcul), 2),
                'pourcentage_augmentation' => round((($objectifs['ambitieux']['montant_annuel'] - $baseCalcul) / $baseCalcul) * 100, 2)
            ]
        ];

        return [
            'annee_reference' => $anneeSuivante,
            'base_calcul' => $baseCalcul,
            'analyse_historique' => [
                'croissance_moyenne' => round($croissanceAnnuelleMoyenne * 100, 2),
                'stabilite_score' => round($stabilite, 1),
                'coefficient_variation' => round($coefficientVariation, 2),
                'nombre_annees_analysees' => $nombreAnnees
            ],
            'objectifs_annuels' => $objectifs,
            'objectifs_mensuels' => $objectifsMensuels,
            'performance_requise' => $performanceRequise,
            'recommandations_actions' => $recommandationsActions,
            'indicateurs_suivi' => [
                'objectif_recommande' => $objectifs['realiste']['montant_annuel'],
                'seuil_alerte_bas' => round($objectifs['conservateur']['montant_annuel'] * 0.9, 2),
                'seuil_excellence' => round($objectifs['ambitieux']['montant_annuel'] * 0.95, 2)
            ],
            'fiabilite' => $stabilite > 70 ? 'élevée' : ($stabilite > 50 ? 'moyenne' : 'faible'),
            'methodologie' => 'Basé sur l\'analyse de ' . $nombreAnnees . ' années d\'historique avec ajustements saisonniers'
        ];

    } catch (Exception $e) {
        Log::error('Erreur recommandations objectifs', [
            'error' => $e->getMessage(),
            'historique_count' => $historique ? $historique->count() : 0
        ]);

        return [
            'erreur' => 'Impossible de calculer les recommandations d\'objectifs',
            'objectifs' => [],
            'message' => 'Erreur technique lors du calcul'
        ];
    }
}



    /**
     * Générer recommandations simples basées sur les détails de confiance
     */
    private function genererRecommandationsSimples($details)
    {
        $recommandations = [];

        if ($details['quantite_donnees'] < 70) {
            $recommandations[] = 'Collecter plus de données historiques pour améliorer la fiabilité';
        }

        if ($details['regularite'] < 80) {
            $recommandations[] = 'Assurer une saisie plus régulière des données';
        }

        if ($details['stabilite'] < 60) {
            $recommandations[] = 'Travailler sur la stabilisation des revenus pour améliorer la prédictibilité';
        }

        if ($details['absence_outliers'] < 80) {
            $recommandations[] = 'Analyser les causes des variations extrêmes';
        }

        if (empty($recommandations)) {
            $recommandations[] = 'Continuer le suivi régulier pour maintenir la qualité des projections';
        }

        return $recommandations;
    }
}

