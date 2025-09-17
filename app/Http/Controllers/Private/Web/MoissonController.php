<?php

namespace App\Http\Controllers\Private\Web;

use Log;
use Str;
use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Culte;
use App\Models\Classe;
use App\Models\Moisson;
use Illuminate\Http\Request;
use InvalidArgumentException;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\MoissonService;
use App\Models\EngagementMoisson;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Helpers\MoissonCacheHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MoissonRequest;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class MoissonController extends Controller
{
    public function __construct(private MoissonService $moissonService)
    {
    }

    /**
     * Liste paginée des moissons avec filtres
     */
    public function index(Request $request)
    {
        $query = Moisson::with(['culte:id,titre', 'createur:id,nom'])->avecStatistiques();

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('date', '>=', $request->date('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date', '<=', $request->date('date_fin'));
        }

        if ($request->filled('statut_progression')) {
            $statut = $request->input('statut_progression');
            $query->parStatutProgression($statut);
        }

        if ($request->filled('recherche')) {
            $terme = $request->input('recherche');
            $query->where(function ($q) use ($terme) {
                $q->where('theme', 'ILIKE', "%{$terme}%")
                    ->orWhereRaw("passages_bibliques::text ILIKE ?", ["%{$terme}%"]);
            });
        }

        // Tri
        $sortField = $request->input('tri', 'date');
        $sortDirection = $request->input('ordre', 'desc');

        $allowedSorts = ['date', 'theme', 'cible', 'montant_solde', 'created_at'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $moissons = $query->paginate($request->input('per_page', 15));

        // Calculer les statistiques pour la page - (PostgreSQL)
        $totalCollecte = Moisson::sum('montant_solde');

        // Correction : Séparer le calcul de la moyenne
        $pourcentageMoyenResult = Moisson::selectRaw('AVG(montant_solde * 100.0 / NULLIF(cible, 0)) as avg_percentage')
            ->whereRaw('cible > 0')
            ->first();

        $pourcentageMoyen = $pourcentageMoyenResult ? round($pourcentageMoyenResult->avg_percentage, 2) : 0;

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $moissons->items(),
                'meta' => [
                    'current_page' => $moissons->currentPage(),
                    'last_page' => $moissons->lastPage(),
                    'per_page' => $moissons->perPage(),
                    'total' => $moissons->total(),
                    'from' => $moissons->firstItem(),
                    'to' => $moissons->lastItem()
                ]
            ]);
        }

        return view('components.private.moissons.index', compact(
            'moissons',
            'totalCollecte',
            'pourcentageMoyen'
        ));
    }


    public function create()
    {
        $responsables = User::orderByRaw('LOWER(nom) ASC')->get();

        $cultes = Culte::orderByDesc('created_at')->get();

        $classes = Classe::orderByRaw('LOWER(nom) ASC')->get();

        return view('components.private.moissons.create', compact('responsables', 'cultes', 'classes'));

    }

    /**
     * Créer une nouvelle moisson
     */
    public function store(MoissonRequest $request)
    {
        try {

            $donnees = $request->validated();
            $donnees['creer_par'] = auth()->id();

            $composants = [
                'passages' => $request->input('passages', []),
                'ventes' => $request->input('ventes', []),
                'engagements' => $request->input('engagements', [])
            ];

            $moisson = $this->moissonService->creerMoissonComplete($donnees, $composants);

            // Invalider les caches
            MoissonCacheHelper::invalidateStatsCache();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Moisson créée avec succès',
                    'data' => $moisson->load(['culte', 'createur'])
                ], 201);
            }

            return redirect()->route('private.moissons.show', $moisson)->with('success', 'Moisson créée avec succès');

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la moisson',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back();
        }
    }

    /**
     * Afficher une moisson spécifique avec son tableau de bord
     */
    public function show(Request $request, Moisson $moisson)
    {
        try {
            $tableauDeBord = MoissonCacheHelper::cacheTableauDeBord($moisson->id);

            $moisson->load(['culte', 'createur']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $tableauDeBord
                ]);
            }

            return view('components.private.moissons.show', compact('tableauDeBord', 'moisson'));

        } catch (InvalidArgumentException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Moisson non trouvée'
                ], 404);
            }
            return redirect()->back();
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du chargement de la moisson',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back();
        }
    }

    public function edit(Moisson $moisson)
    {
        $responsables = User::orderByRaw('LOWER(nom) ASC')->get();

        $cultes = Culte::orderByDesc('created_at')->get();

        $classes = Classe::orderByRaw('LOWER(nom) ASC')->get();

        return view('components.private.moissons.edit', compact('moisson', 'responsables', 'cultes', 'classes'));
    }

    /**
     * Mettre à jour une moisson
     */
    public function update(MoissonRequest $request, Moisson $moisson)
    {
        try {
            $donnees = $request->validated();

            // Ajouter à l'historique
            $moisson->ajouterEditeur(auth()->id(), 'modification');

            $moisson->update($donnees);

            // Invalider le cache de cette moisson
            MoissonCacheHelper::invalidateMoissonCache($moisson->id);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Moisson mise à jour avec succès',
                    'data' => $moisson->fresh()->load(['culte', 'createur'])
                ]);
            }

            return redirect()->route('private.moissons.show', $moisson)->with('success', 'Moisson mise à jour avec succès');


        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back();
        }
    }

    /**
     * Supprimer une moisson (soft delete)
     */
    public function destroy(Request $request, Moisson $moisson)
    {
        try {
            $moisson->delete();

            MoissonCacheHelper::invalidateStatsCache();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Moisson supprimée avec succès'
                ]);
            }

            return redirect()->route('private.moissons.index')->with('success', 'Moisson supprimée avec succès');


        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back();
        }
    }

    /**
     * Recalculer les totaux d'une moisson
     */
    public function recalculerTotaux(Moisson $moisson): JsonResponse
    {
        try {
            $succes = $moisson->recalculerTotaux();

            if ($succes) {
                MoissonCacheHelper::invalidateMoissonCache($moisson->id);

                return response()->json([
                    'success' => true,
                    'message' => 'Totaux recalculés avec succès',
                    'data' => [
                        'cible' => $moisson->fresh()->cible,
                        'montant_solde' => $moisson->fresh()->montant_solde,
                        'reste' => $moisson->fresh()->reste,
                        'supplement' => $moisson->fresh()->montant_supplementaire,
                        'pourcentage' => $moisson->fresh()->pourcentage_realise
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du recalcul des totaux'
            ], 500);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du recalcul',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Analyser la performance d'une moisson
     */
    public function analyserPerformance(string $id): JsonResponse
    {
        try {
            $analyse = $this->moissonService->analyserPerformance($id);

            return response()->json([
                'success' => true,
                'data' => $analyse
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'analyse',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clôturer une moisson
     */
    public function cloturer(Request $request, string $moisson)
    {
        $request->validate([
            'motif' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $options = [
                'motif' => $request->input('motif'),
                'notes' => $request->input('notes')
            ];

            $resultat = $this->moissonService->cloturerMoisson($moisson, auth()->id(), $options);

            if ($resultat['succes']) {
                MoissonCacheHelper::invalidateMoissonCache($moisson);
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => $resultat['message'],
                        'data' => $resultat['rapport_final']
                    ]);
                }
                return redirect()->route('private.moissons.edit', $moisson)->with('success', $resultat['message']);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $resultat['message'],
                    'errors' => $resultat['erreurs'] ?? []
                ], 422);
            }
            return redirect()->back()->with('error', $resultat['message']);

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la clôture',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Erreur lors de la clôture');
        }
    }

    /**
     * Obtenir les statistiques globales
     */
    public function statistiquesGlobales(Request $request): JsonResponse
    {
        $request->validate([
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut'
        ]);

        try {
            $dateDebut = $request->filled('date_debut') ? Carbon::parse($request->input('date_debut')) : null;
            $dateFin = $request->filled('date_fin') ? Carbon::parse($request->input('date_fin')) : null;

            $statistiques = MoissonCacheHelper::cacheStatistiquesGlobales($dateDebut, $dateFin);

            return response()->json([
                'success' => true,
                'data' => $statistiques
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir les données pour les graphiques
     */
    public function donneesGraphique(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:evolution_mensuelle,repartition_par_type,performance_comparative',
            'parametres' => 'sometimes|array'
        ]);

        try {
            $type = $request->input('type');
            $parametres = $request->input('parametres', []);

            $donnees = MoissonCacheHelper::cacheDonneesGraphique($type, $parametres);

            return response()->json([
                'success' => true,
                'data' => $donnees,
                'meta' => [
                    'type' => $type,
                    'parametres' => $parametres
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des données',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtenir le tableau de bord général
     */

    public function dashboard(Request $request)
    {
        try {
            // Gestion des filtres de période
            $periode = $request->input('periode', '30');
            $dateDebut = null;
            $dateFin = null;

            if ($request->filled('date_debut') && $request->filled('date_fin')) {
                $dateDebut = Carbon::parse($request->input('date_debut'));
                $dateFin = Carbon::parse($request->input('date_fin'));
            } else {
                switch ($periode) {
                    case '7':
                        $dateDebut = now()->subDays(7);
                        break;
                    case '30':
                        $dateDebut = now()->subDays(30);
                        break;
                    case '90':
                        $dateDebut = now()->subDays(90);
                        break;
                    case '365':
                        $dateDebut = now()->subDays(365);
                        break;
                    case 'all':
                    default:
                        // Pas de filtre de date
                        break;
                }
                $dateFin = now();
            }

            // Requête de base avec filtres de période
            $baseQuery = Moisson::query();
            if ($dateDebut && $dateFin) {
                $baseQuery->whereBetween('date', [$dateDebut, $dateFin]);
            }

            // Statistiques principales - (PostgreSQL)
            $stats = [
                'total_moissons' => $baseQuery->count(),
                'total_collecte' => $baseQuery->sum('montant_solde'),
                'objectifs_atteints' => $baseQuery->whereRaw('montant_solde >= cible')->count(),
            ];

            // Calculer la performance moyenne séparément
            $performanceResult = $baseQuery->clone()
                ->selectRaw('AVG(montant_solde * 100.0 / NULLIF(cible, 0)) as avg_performance')
                ->whereRaw('cible > 0')
                ->first();

            $stats['performance_moyenne'] = $performanceResult ? round($performanceResult->avg_performance, 2) : 0;

            // Calculer les pourcentages
            $stats['pourcentage_objectifs'] = $stats['total_moissons'] > 0
                ? round(($stats['objectifs_atteints'] * 100) / $stats['total_moissons'], 2)
                : 0;

            // Nouvelles moissons ce mois
            $stats['nouvelles_moissons'] = Moisson::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            // Évolution par rapport à la période précédente
            if ($dateDebut && $dateFin) {
                $duree = $dateDebut->diffInDays($dateFin);
                $debutPrec = $dateDebut->copy()->subDays($duree);
                $finPrec = $dateFin->copy()->subDays($duree);

                $collectePrecedente = Moisson::whereBetween('date', [$debutPrec, $finPrec])
                    ->sum('montant_solde');

                $stats['evolution_collecte'] = $collectePrecedente > 0
                    ? round((($stats['total_collecte'] - $collectePrecedente) * 100) / $collectePrecedente, 1)
                    : 0;
            } else {
                $stats['evolution_collecte'] = 0;
            }

            // Top moissons performantes - (PostgreSQL)
            $topMoissons = $this->getTopMoissonsPerformantes($baseQuery);

            // Moissons récentes
            $moissonRecentes = $baseQuery->clone()
                ->with(['culte:id,titre'])
                ->orderBy('date', 'desc')
                ->limit(10)
                ->get();

            // Alertes - (PostgreSQL)
            $alertes = [
                'engagements_retard' => EngagementMoisson::enRetard()->get(),
                'rappels_jour' => EngagementMoisson::aRappeler()->get(),
                'objectifs_danger' => $this->getObjectifsEnDangerSimple($baseQuery),
            ];

            // Statistiques par composant - (PostgreSQL)
            $statsComposants = [
                'passages' => [
                    'nombre' => $this->getComposantCount('passage_moissons', $dateDebut, $dateFin),
                    'total' => $this->getComposantSum('passage_moissons', $dateDebut, $dateFin),
                ],
                'ventes' => [
                    'nombre' => $this->getComposantCount('vente_moissons', $dateDebut, $dateFin),
                    'total' => $this->getComposantSum('vente_moissons', $dateDebut, $dateFin),
                ],
                'engagements' => [
                    'nombre' => $this->getComposantCount('engagement_moissons', $dateDebut, $dateFin),
                    'total' => $this->getComposantSum('engagement_moissons', $dateDebut, $dateFin),
                ]
            ];

            // Calculer les pourcentages pour les composants
            $totalComposants = $statsComposants['passages']['total'] +
                $statsComposants['ventes']['total'] +
                $statsComposants['engagements']['total'];

            if ($totalComposants > 0) {
                $statsComposants['passages']['pourcentage'] = round(($statsComposants['passages']['total'] * 100) / $totalComposants, 2);
                $statsComposants['ventes']['pourcentage'] = round(($statsComposants['ventes']['total'] * 100) / $totalComposants, 2);
                $statsComposants['engagements']['pourcentage'] = round(($statsComposants['engagements']['total'] * 100) / $totalComposants, 2);
            } else {
                $statsComposants['passages']['pourcentage'] = 0;
                $statsComposants['ventes']['pourcentage'] = 0;
                $statsComposants['engagements']['pourcentage'] = 0;
            }

            // Données pour les graphiques
            $donneesGraphiques = [
                'evolution' => $this->getDonneesEvolution($dateDebut, $dateFin),
                'repartition' => $this->getDonneesRepartition($dateDebut, $dateFin)
            ];


            // Tendances
            $tendances = [
                'croissance_mensuelle' => $stats['evolution_collecte'],
                'moyenne_moisson' => $stats['total_moissons'] > 0 ? round($stats['total_collecte'] / $stats['total_moissons'], 0) : 0,
                'meilleur_mois' => $this->getMeilleurMois($dateDebut, $dateFin),
                'montant_meilleur_mois' => $this->getMontantMeilleurMois($dateDebut, $dateFin),
                'prochaine_echeance' => $this->getProchaineEcheance(),
                'montant_echeance' => $this->getMontantProchaineEcheance()
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'statistiques' => $stats,
                    'top_performers' => $topMoissons,
                    'moissons_recentes' => $moissonRecentes,
                    'donnees_graphiques' => $donneesGraphiques
                ]);
            }

            return view('components.private.moissons.dashboard', compact(
                'stats',
                'topMoissons',
                'moissonRecentes',
                'alertes',
                'statsComposants',
                'donneesGraphiques',
                'tendances'
            ));

        } catch (Exception $e) {
            Log::error('Erreur dashboard moissons: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'periode' => $periode ?? null,
                'date_debut' => $dateDebut ?? null,
                'date_fin' => $dateFin ?? null
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du chargement du dashboard',
                    'error' => config('app.debug') ? $e->getMessage() : 'Erreur interne'
                ], 500);
            }
            return redirect()->back()->with('error', 'Erreur lors du chargement du dashboard');
        }
    }


    /**
     * Compte le nombre d'enregistrements pour un composant (compatible PostgreSQL)
     */
    private function getComposantCount($table, $dateDebut, $dateFin)
    {
        $query = DB::table($table)
            ->whereNull($table . '.deleted_at');

        if ($dateDebut && $dateFin) {
            $query->join('moissons', $table . '.moisson_id', '=', 'moissons.id')
                ->whereBetween('moissons.date', [$dateDebut, $dateFin])
                ->whereNull('moissons.deleted_at');
        }

        return $query->count();
    }


    /**
     * Récupère les moissons avec objectifs en danger (< 50% de réalisation)
     * Version simple et robuste pour PostgreSQL
     */
    private function getObjectifsEnDangerSimple($baseQuery)
    {
        // Récupérer toutes les moissons actives avec cible > 0
        $moissons = $baseQuery->clone()
            ->where('status', true)
            ->where('cible', '>', 0)
            ->get();

        // Filtrer celles avec performance < 50% côté PHP
        return $moissons->filter(function ($moisson) {
            $pourcentage = ($moisson->montant_solde * 100) / $moisson->cible;
            return $pourcentage < 50;
        });
    }

    /**
     * Récupère le top des moissons performantes
     * Version PostgreSQL-compatible
     */
    private function getTopMoissonsPerformantes($baseQuery)
    {
        try {
            // Première tentative : calculer la performance et trier
            return $baseQuery->clone()
                ->where('cible', '>', 0)
                ->get()
                ->map(function ($moisson) {
                    $moisson->performance_score = ($moisson->montant_solde * 100) / $moisson->cible;
                    return $moisson;
                })
                ->sortByDesc('performance_score')
                ->take(5);

        } catch (Exception $e) {
            // Fallback : requête simple sans calcul complexe
            return $baseQuery->clone()
                ->where('cible', '>', 0)
                ->orderByDesc('montant_solde')
                ->limit(5)
                ->get();
        }
    }

    /**
     * Calcule la somme des montants pour un composant (compatible PostgreSQL)
     */
    private function getComposantSum($table, $dateDebut, $dateFin)
    {
        $query = DB::table($table . ' as t');

        if ($dateDebut && $dateFin) {
            $query->join('moissons as m', 't.moisson_id', '=', 'm.id')
                ->whereBetween('m.date', [$dateDebut, $dateFin])
                ->whereNull('m.deleted_at');
        }

        return $query->whereNull('t.deleted_at')
            ->sum('t.montant_solde') ?? 0;
    }


    // Méthodes d'aide pour le dashboard
    private function getDonneesEvolution($dateDebut, $dateFin)
    {
        $query = Moisson::selectRaw("
            DATE_TRUNC('month', date) as mois,
            SUM(montant_solde) as total
        ");

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date', [$dateDebut, $dateFin]);
        } else {
            $query->where('date', '>=', now()->subMonths(6));
        }

        $donnees = $query->groupBy(DB::raw("DATE_TRUNC('month', date)"))
            ->orderBy(DB::raw("DATE_TRUNC('month', date)"))
            ->get();

        return [
            'labels' => $donnees->map(fn($d) => Carbon::parse($d->mois)->format('M Y'))->toArray(),
            'data' => $donnees->pluck('total')->toArray()
        ];
    }



    private function getDonneesRepartition($dateDebut, $dateFin)
    {
        $baseQuery = function ($table, $alias) use ($dateDebut, $dateFin) {
            $query = DB::table($table . ' as ' . $alias);

            if ($dateDebut && $dateFin) {
                $query->join('moissons as m', $alias . '.moisson_id', '=', 'm.id')
                    ->whereBetween('m.date', [$dateDebut->format('Y-m-d'), $dateFin->format('Y-m-d')])
                    ->whereNull('m.deleted_at');
            }

            return $query->whereNull($alias . '.deleted_at')
                ->sum($alias . '.montant_solde') ?? 0;
        };

        $passages = $baseQuery('passage_moissons', 'pm');
        $ventes = $baseQuery('vente_moissons', 'vm');
        $engagements = $baseQuery('engagement_moissons', 'em');

        return [
            'labels' => ['Passages', 'Ventes', 'Engagements'],
            'data' => [$passages, $ventes, $engagements]
        ];
    }


    private function getMeilleurMois($dateDebut, $dateFin)
    {
        $query = Moisson::selectRaw("
            DATE_TRUNC('month', date) as mois,
            SUM(montant_solde) as total
        ");

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date', [$dateDebut, $dateFin]);
        }

        $meilleur = $query->groupBy(DB::raw("DATE_TRUNC('month', date)"))
            ->orderByDesc('total')
            ->first();

        return $meilleur ? Carbon::parse($meilleur->mois)->format('F Y') : 'N/A';
    }



    private function getMontantMeilleurMois($dateDebut, $dateFin)
    {
        $query = Moisson::selectRaw("
            DATE_TRUNC('month', date) as mois,
            SUM(montant_solde) as total
        ");

        if ($dateDebut && $dateFin) {
            $query->whereBetween('date', [$dateDebut, $dateFin]);
        }

        $meilleur = $query->groupBy(DB::raw("DATE_TRUNC('month', date)"))
            ->orderByDesc('total')
            ->first();

        return $meilleur ? $meilleur->total : 0;
    }


    private function getProchaineEcheance()
    {
        $prochaine = EngagementMoisson::where('date_echeance', '>', now())
            ->where('reste', '>', 0)
            ->orderBy('date_echeance')
            ->first();

        return $prochaine ? $prochaine->date_echeance->format('d/m/Y') : 'Aucune';
    }

    private function getMontantProchaineEcheance()
    {
        $prochaine = EngagementMoisson::where('date_echeance', '>', now())
            ->where('reste', '>', 0)
            ->orderBy('date_echeance')
            ->first();

        return $prochaine ? number_format($prochaine->reste, 0, ',', ' ') . ' FCFA' : '';
    }


    /**
     * Rafraîchir les caches et vues matérialisées
     */
    public function rafraichirCaches(): JsonResponse
    {
        try {
            // Invalider tous les caches
            MoissonCacheHelper::invalidateAllCaches();

            // Rafraîchir la vue matérialisée
            $vueRefreshie = MoissonCacheHelper::refreshMaterializedView();

            // Recalculer tous les totaux
            $totauxRecalcules = $this->moissonService->recalculerTousLesTotaux();

            return response()->json([
                'success' => true,
                'message' => 'Caches et données rafraîchis avec succès',
                'details' => [
                    'caches_invalides' => true,
                    'vue_materialisee_refreshie' => $vueRefreshie,
                    'totaux_recalcules' => $totauxRecalcules
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rafraîchissement',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Exporter la liste des moissons avec filtres
     */
    public function exporterListeMoissons(Request $request)
    {
        $request->validate([
            'format' => 'required|in:json,excel,pdf',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'status' => 'nullable|boolean',
            'statut_progression' => 'nullable|string',
            'recherche' => 'nullable|string|max:255'
        ]);

        try {
            $format = $request->input('format');

            // Construire la requête avec filtres
            $query = Moisson::with(['culte:id,titre', 'createur:id,nom'])
                ->avecStatistiques();

            // Appliquer les filtres
            if ($request->filled('status')) {
                $query->where('status', $request->boolean('status'));
            }

            if ($request->filled('date_debut')) {
                $query->whereDate('date', '>=', $request->date('date_debut'));
            }

            if ($request->filled('date_fin')) {
                $query->whereDate('date', '<=', $request->date('date_fin'));
            }

            if ($request->filled('statut_progression')) {
                $query->parStatutProgression($request->input('statut_progression'));
            }

            if ($request->filled('recherche')) {
                $terme = $request->input('recherche');
                $query->where(function ($q) use ($terme) {
                    $q->where('theme', 'ILIKE', "%{$terme}%")
                        ->orWhereRaw("passages_bibliques::text ILIKE ?", ["%{$terme}%"]);
                });
            }

            $moissons = $query->orderBy('date', 'desc')->get();

            // Préparer les données pour l'export
            $donneesExport = $this->preparerDonneesListeMoissons($moissons);

            switch ($format) {
                case 'json':
                    return $this->exporterListeJson($donneesExport);

                case 'excel':
                    return $this->exporterListeExcel($donneesExport, $request->all());

                case 'pdf':
                    return $this->exporterListePdf($donneesExport, $request->all());
            }

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'exportation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportation de moisson complète corrigée
     */
    public function exporterMoissonComplete(Request $request, string $moissonId)
    {
        $request->validate([
            'format' => 'required|in:json,excel,pdf'
        ]);

        try {
            $format = $request->input('format');

            // Récupérer les données complètes de la moisson avec validation
            $donneesComplete = $this->moissonService->exporterDonneesMoisson($moissonId, 'array');

            // Nettoyer les données pour éviter les tableaux
            $donneesComplete = $this->nettoyerDonneesExport($donneesComplete);

            switch ($format) {
                case 'json':
                    return $this->exporterMoissonJson($donneesComplete);

                case 'excel':
                    return $this->exporterMoissonExcel($donneesComplete);

                case 'pdf':
                    return $this->exporterMoissonPdf($donneesComplete);
            }

        } catch (Exception $e) {
            Log::error('Erreur export moisson: ' . $e->getMessage(), [
                'moisson_id' => $moissonId,
                'format' => $request->input('format'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'exportation de la moisson',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Nettoyer les données d'export pour éviter les problèmes de type
     */
    private function nettoyerDonneesExport($donnees)
    {
        if (is_array($donnees)) {
            foreach ($donnees as $key => $value) {
                $donnees[$key] = $this->nettoyerDonneesExport($value);
            }
        } elseif (is_object($donnees)) {
            // Convertir les objets en tableaux si nécessaire
            if ($donnees instanceof Carbon) {
                return $donnees->format('d/m/Y');
            }
            return $this->sanitizeForExcel($donnees);
        }

        return $donnees;
    }


    /**
     * Méthode corrigée pour préparer les données pour l'export de la liste
     */
    private function preparerDonneesListeMoissons($moissons)
    {
        return [
            'meta' => [
                'titre' => 'Liste des Moissons',
                'date_export' => now()->format('d/m/Y H:i'),
                'nombre_total' => $moissons->count(),
                'periode' => $this->determinerPeriodeExport($moissons),
                'eglise' => [
                    'nom' => config('app.church_name', 'Église Baptiste'),
                    'adresse' => config('app.church_address', ''),
                    'telephone' => config('app.church_phone', ''),
                    'email' => config('app.church_email', ''),
                    'logo' => config('app.church_logo', '')
                ]
            ],
            'donnees' => $moissons->map(function ($moisson) {
                return [
                    'theme' => (string) ($moisson->theme ?? ''),
                    'date' => $moisson->date ? $moisson->date->format('d/m/Y') : '',
                    'culte' => (string) (optional($moisson->culte)->titre ?? 'Non spécifié'),
                    'objectif' => (string) number_format($moisson->cible ?? 0, 0, ',', ' '),
                    'collecte' => (string) number_format($moisson->montant_solde ?? 0, 0, ',', ' '),
                    'reste' => (string) number_format($moisson->reste ?? 0, 0, ',', ' '),
                    'supplement' => (string) number_format($moisson->montant_supplementaire ?? 0, 0, ',', ' '),
                    'pourcentage' => (string) (($moisson->pourcentage_realise ?? 0) . '%'),
                    'statut' => (string) ($moisson->statut_progression ?? 'N/A'),
                    'nb_passages' => (string) ($moisson->nb_passages ?? 0),
                    'nb_ventes' => (string) ($moisson->nb_ventes ?? 0),
                    'nb_engagements' => (string) ($moisson->nb_engagements ?? 0),
                    'createur' => (string) (optional($moisson->createur)->nom ?? 'Inconnu'),
                    'status' => (string) ($moisson->status ? 'Actif' : 'Inactif'),
                    'date_creation' => $moisson->created_at ? $moisson->created_at->format('d/m/Y') : ''
                ];
            })->toArray(),
            'statistiques' => [
                'total_objectifs' => (string) number_format($moissons->sum('cible') ?? 0, 0, ',', ' '),
                'total_collecte' => (string) number_format($moissons->sum('montant_solde') ?? 0, 0, ',', ' '),
                'objectifs_atteints' => (string) $moissons->filter(function ($m) {
                    return ($m->montant_solde ?? 0) >= ($m->cible ?? 1);
                })->count(),
                'performance_moyenne' => (string) (round($moissons->avg(function ($m) {
                    return ($m->cible ?? 0) > 0 ? (($m->montant_solde ?? 0) * 100) / $m->cible : 0;
                }) ?? 0, 2) . '%')
            ]
        ];
    }


    /**
     * Export JSON pour la liste
     */
    private function exporterListeJson($donnees)
    {
        $nomFichier = 'liste_moissons_' . now()->format('Y-m-d_H-i-s') . '.json';

        return response()->json($donnees)
            ->header('Content-Disposition', 'attachment; filename="' . $nomFichier . '"')
            ->header('Content-Type', 'application/json');
    }

    /**
     * Export JSON pour une moisson
     */
    private function exporterMoissonJson($donnees)
    {
        $nomFichier = 'moisson_' .
            Str::slug($donnees['informations_generales']['theme'] ?? 'moisson') .
            '_' . now()->format('Y-m-d') . '.json';

        return response()->json($donnees)
            ->header('Content-Disposition', 'attachment; filename="' . $nomFichier . '"')
            ->header('Content-Type', 'application/json');
    }



    /**
     * Export Excel corrigé avec validation des données
     */
    private function exporterListeExcel($donnees, $filtres = [])
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Liste des Moissons');

        // Configuration de base
        $this->configurerStyleExcel($sheet);

        $ligne = 1;

        // Header avec logo et informations de l'église
        $ligne = $this->ajouterHeaderExcel($sheet, $donnees['meta'], $ligne);

        // Titre principal
        $sheet->setCellValue("A{$ligne}", 'LISTE DES MOISSONS');
        $sheet->mergeCells("A{$ligne}:M{$ligne}");
        $this->appliquerStyleTitre($sheet, "A{$ligne}");
        $ligne += 2;

        // Informations sur la période et filtres
        if (!empty($filtres)) {
            $ligne = $this->ajouterInfosFiltres($sheet, $filtres, $ligne);
        }

        // En-têtes du tableau
        $entetes = [
            'A' => 'Thème',
            'B' => 'Date',
            'C' => 'Culte',
            'D' => 'Objectif (FCFA)',
            'E' => 'Collecté (FCFA)',
            'F' => 'Reste (FCFA)',
            'G' => 'Supplément (FCFA)',
            'H' => 'Pourcentage',
            'I' => 'Statut',
            'J' => 'Passages',
            'K' => 'Ventes',
            'L' => 'Engagements',
            'M' => 'Créateur'
        ];

        foreach ($entetes as $col => $titre) {
            $sheet->setCellValue("{$col}{$ligne}", (string) $titre);
        }
        $this->appliquerStyleEntetes($sheet, "A{$ligne}:M{$ligne}");
        $ligne++;

        // Données avec validation
        foreach ($donnees['donnees'] as $moisson) {
            $sheet->setCellValue("A{$ligne}", $this->sanitizeForExcel($moisson['theme']));
            $sheet->setCellValue("B{$ligne}", $this->sanitizeForExcel($moisson['date']));
            $sheet->setCellValue("C{$ligne}", $this->sanitizeForExcel($moisson['culte']));
            $sheet->setCellValue("D{$ligne}", $this->sanitizeForExcel($moisson['objectif']));
            $sheet->setCellValue("E{$ligne}", $this->sanitizeForExcel($moisson['collecte']));
            $sheet->setCellValue("F{$ligne}", $this->sanitizeForExcel($moisson['reste']));
            $sheet->setCellValue("G{$ligne}", $this->sanitizeForExcel($moisson['supplement']));
            $sheet->setCellValue("H{$ligne}", $this->sanitizeForExcel($moisson['pourcentage']));
            $sheet->setCellValue("I{$ligne}", $this->sanitizeForExcel($moisson['statut']));
            $sheet->setCellValue("J{$ligne}", $this->sanitizeForExcel($moisson['nb_passages']));
            $sheet->setCellValue("K{$ligne}", $this->sanitizeForExcel($moisson['nb_ventes']));
            $sheet->setCellValue("L{$ligne}", $this->sanitizeForExcel($moisson['nb_engagements']));
            $sheet->setCellValue("M{$ligne}", $this->sanitizeForExcel($moisson['createur']));

            // Colorier la ligne selon le statut
            $this->colorierLigneSelonStatut($sheet, $ligne, $moisson['statut']);
            $ligne++;
        }

        // Ligne de totaux
        $ligne++;
        $sheet->setCellValue("A{$ligne}", 'TOTAUX');
        $sheet->setCellValue("D{$ligne}", $this->sanitizeForExcel($donnees['statistiques']['total_objectifs']));
        $sheet->setCellValue("E{$ligne}", $this->sanitizeForExcel($donnees['statistiques']['total_collecte']));
        $sheet->setCellValue("H{$ligne}", $this->sanitizeForExcel($donnees['statistiques']['performance_moyenne']));
        $this->appliquerStyleTotaux($sheet, "A{$ligne}:M{$ligne}");

        // Footer
        $this->ajouterFooterExcel($sheet, $ligne + 2);

        // Ajuster les largeurs de colonnes
        $this->ajusterLargeursColonnes($sheet);

        // Générer le fichier
        $nomFichier = 'liste_moissons_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $nomFichier, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }


    /**
     * Méthode pour nettoyer les données avant insertion dans Excel
     */
    private function sanitizeForExcel($value)
    {
        // Si c'est un tableau, le convertir en chaîne
        if (is_array($value)) {
            if (empty($value)) {
                return '';
            }
            // Si c'est un tableau de passages bibliques par exemple
            if (isset($value[0]) && is_string($value[0])) {
                return implode(', ', $value);
            }
            return json_encode($value);
        }

        // Si c'est un objet, le convertir en chaîne
        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return (string) $value;
            }
            if (method_exists($value, 'format') && $value instanceof \Carbon\Carbon) {
                return $value->format('d/m/Y');
            }
            return json_encode($value);
        }

        // Si c'est null, retourner chaîne vide
        if (is_null($value)) {
            return '';
        }

        // Sinon, convertir en chaîne
        return (string) $value;
    }


    /**
     * Export Excel pour une moisson complète
     */
    private function exporterMoissonExcel($donnees)
    {
        $spreadsheet = new Spreadsheet();

        // Feuille principale - Vue d'ensemble
        $this->creerFeuilleVueEnsemble($spreadsheet, $donnees);

        // Feuille détails passages
        $this->creerFeuillePassages($spreadsheet, $donnees);

        // Feuille détails ventes
        $this->creerFeuilleVentes($spreadsheet, $donnees);

        // Feuille détails engagements
        $this->creerFeuilleEngagements($spreadsheet, $donnees);

        // Générer le nom du fichier Excel
        $nomFichier = 'moisson_' . Str::slug($donnees['informations_generales']['theme']) . '_' . now()->format('Y-m-d') . '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $nomFichier, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Export PDF pour la liste des moissons
     */
    private function exporterListePdf($donnees, $filtres = [])
    {

        $html = view('exports.moissons.liste_pdf', compact('donnees', 'filtres'))->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true
            ]);

        $nomFichier = 'liste_moissons_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        return $pdf->download($nomFichier);
    }

    /**
     * Export PDF pour une moisson complète
     */
    private function exporterMoissonPdf($donnees)
    {

        $html = view('exports.moissons.moisson_complete_pdf', compact('donnees'))->render();

        $pdf = Pdf::loadHTML($html)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Arial',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true
            ]);

        $nomFichier = 'moisson_' . Str::slug($donnees['informations_generales']['theme']) . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($nomFichier);
    }

    /**
     * Configuration corrigée pour PhpSpreadsheet
     */
    private function configurerStyleExcel($sheet)
    {
        // Remplacer getDefaultStyle() par getStyle() avec une plage
        $sheet->getStyle('A1:Z1000')->getFont()->setName('Arial')->setSize(10);

        $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);

        // Définir les marges
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setRight(0.25);
        $sheet->getPageMargins()->setLeft(0.25);
        $sheet->getPageMargins()->setBottom(0.75);
    }


    /**
     * Header Excel corrigé avec validation des données
     */
    private function ajouterHeaderExcel($sheet, $meta, $ligne)
    {
        // Logo et informations église
        if (!empty($meta['eglise']['logo']) && file_exists(public_path($meta['eglise']['logo']))) {
            try {
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo de l\'église');
                $drawing->setPath(public_path($meta['eglise']['logo']));
                $drawing->setHeight(60);
                $drawing->setCoordinates('A' . $ligne);
                $drawing->setWorksheet($sheet);
            } catch (Exception $e) {
                Log::warning('Impossible de charger le logo pour l\'export Excel: ' . $e->getMessage());
            }
        }

        // Informations église avec validation
        $sheet->setCellValue("G{$ligne}", $this->sanitizeForExcel($meta['eglise']['nom'] ?? ''));
        $sheet->setCellValue("G" . ($ligne + 1), $this->sanitizeForExcel($meta['eglise']['adresse'] ?? ''));
        $sheet->setCellValue("G" . ($ligne + 2), 'Tél: ' . $this->sanitizeForExcel($meta['eglise']['telephone'] ?? ''));
        $sheet->setCellValue("G" . ($ligne + 3), 'Email: ' . $this->sanitizeForExcel($meta['eglise']['email'] ?? ''));

        // Style pour les informations
        $sheet->getStyle("G{$ligne}:G" . ($ligne + 3))->applyFromArray([
            'font' => ['bold' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]
        ]);

        return $ligne + 5;
    }

    private function appliquerStyleTitre($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF2E74B5']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(substr($range, 1))->setRowHeight(30);
    }

    private function appliquerStyleEntetes($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4472C4']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000']
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
    }

    private function colorierLigneSelonStatut($sheet, $ligne, $statut)
    {
        $couleur = 'FFFFFFFF'; // Blanc par défaut

        switch ($statut) {
            case 'Objectif atteint':
                $couleur = 'FFD5E8D4'; // Vert clair
                break;
            case 'Presque atteint':
                $couleur = 'FFFFF2CC'; // Jaune clair
                break;
            case 'En cours':
                $couleur = 'FFDAE8FC'; // Bleu clair
                break;
            case 'Très faible':
                $couleur = 'FFFCE4EC'; // Rouge clair
                break;
        }

        $sheet->getStyle("A{$ligne}:M{$ligne}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => $couleur]
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFE0E0E0']
                ]
            ]
        ]);
    }

    private function ajusterLargeursColonnes($sheet)
    {
        $largeurs = [
            'A' => 25,
            'B' => 12,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 12,
            'I' => 18,
            'J' => 10,
            'K' => 10,
            'L' => 12,
            'M' => 15
        ];

        foreach ($largeurs as $col => $largeur) {
            $sheet->getColumnDimension($col)->setWidth($largeur);
        }
    }

    private function determinerPeriodeExport($moissons)
    {
        if ($moissons->isEmpty())
            return 'Aucune donnée';

        $dateMin = $moissons->min('date');
        $dateMax = $moissons->max('date');

        return "Du {$dateMin->format('d/m/Y')} au {$dateMax->format('d/m/Y')}";
    }

    // Autres méthodes utilitaires pour les feuilles Excel détaillées...
    /**
     * Créer la feuille vue d'ensemble pour Excel
     */
    private function creerFeuilleVueEnsemble($spreadsheet, $donnees)
    {

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Vue d\'ensemble');


        $this->configurerStyleExcel($sheet);

        $ligne = 1;

        // Header principal
        $ligne = $this->ajouterHeaderExcel($sheet, [
            'eglise' => [
                'nom' => config('app.church_name', 'Église Baptiste'),
                'adresse' => config('app.church_address', ''),
                'telephone' => config('app.church_phone', ''),
                'email' => config('app.church_email', ''),
                'logo' => config('app.church_logo', '')
            ]
        ], $ligne);

        // Titre de la moisson
        $sheet->setCellValue("A{$ligne}", 'RAPPORT DÉTAILLÉ DE MOISSON');
        $sheet->mergeCells("A{$ligne}:F{$ligne}");
        $this->appliquerStyleTitre($sheet, "A{$ligne}");
        $ligne += 2;

        $sheet->setCellValue("A{$ligne}", $donnees['informations_generales']['theme']);
        $sheet->mergeCells("A{$ligne}:F{$ligne}");
        $sheet->getStyle("A{$ligne}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['argb' => 'FF2E74B5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        $ligne += 2;

        // Informations générales
        $sheet->setCellValue("A{$ligne}", 'INFORMATIONS GÉNÉRALES');
        $sheet->mergeCells("A{$ligne}:F{$ligne}");
        $this->appliquerStyleSousTitle($sheet, "A{$ligne}");
        $ligne++;

        $infos = [
            ['Date de la moisson:', $donnees['informations_generales']['date']],
            ['Culte:', $donnees['informations_generales']['culte']],
            ['Créateur:', $donnees['informations_generales']['createur']],
            ['Statut:', $donnees['informations_generales']['statut']],
            ['Date de création:', $donnees['informations_generales']['date_creation']]
        ];

        foreach ($infos as $info) {
            $sheet->setCellValue("A{$ligne}", $info[0]);
            $sheet->setCellValue("B{$ligne}", $info[1]);
            $sheet->getStyle("A{$ligne}")->getFont()->setBold(true);
            $ligne++;
        }

        $ligne++;

        // Performance financière
        $sheet->setCellValue("A{$ligne}", 'PERFORMANCE FINANCIÈRE');
        $sheet->mergeCells("A{$ligne}:F{$ligne}");
        $this->appliquerStyleSousTitle($sheet, "A{$ligne}");
        $ligne++;

        $perf = [
            ['Objectif initial:', number_format($donnees['objectifs_et_realisations']['objectif_initial'], 0, ',', ' ') . ' FCFA'],
            ['Montant collecté:', number_format($donnees['objectifs_et_realisations']['montant_collecte'], 0, ',', ' ') . ' FCFA'],
            ['Reste à collecter:', number_format($donnees['objectifs_et_realisations']['reste_a_collecter'], 0, ',', ' ') . ' FCFA'],
            ['Montant supplémentaire:', number_format($donnees['objectifs_et_realisations']['montant_supplementaire'], 0, ',', ' ') . ' FCFA'],
            ['Pourcentage de réalisation:', $donnees['objectifs_et_realisations']['pourcentage_realisation'] . '%'],
            ['Statut de progression:', $donnees['objectifs_et_realisations']['statut_progression']]
        ];

        foreach ($perf as $p) {
            $sheet->setCellValue("A{$ligne}", $p[0]);
            $sheet->setCellValue("B{$ligne}", $p[1]);
            $sheet->getStyle("A{$ligne}")->getFont()->setBold(true);
            $sheet->getStyle("B{$ligne}")->getFont()->setBold(true);
            $ligne++;
        }

        // Barre de progression visuelle
        $ligne++;
        $sheet->setCellValue("A{$ligne}", 'Progression:');
        $sheet->getStyle("A{$ligne}")->getFont()->setBold(true);

        $pourcentage = min($donnees['objectifs_et_realisations']['pourcentage_realisation'], 100);
        $cellules_remplies = round($pourcentage / 10); // 10 cellules pour 100%

        for ($i = 0; $i < 10; $i++) {
            $col = chr(66 + $i); // B à K
            $sheet->setCellValue("{$col}{$ligne}", $i < $cellules_remplies ? '█' : '░');
            $sheet->getStyle("{$col}{$ligne}")->applyFromArray([
                'font' => [
                    'color' => ['argb' => $i < $cellules_remplies ? 'FF28A745' : 'FFE9ECEF'],
                    'size' => 12
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
        }
        $ligne += 2;

        // Passages bibliques
        if (!empty($donnees['passages_bibliques'])) {
            $sheet->setCellValue("A{$ligne}", 'PASSAGES BIBLIQUES');
            $sheet->mergeCells("A{$ligne}:F{$ligne}");
            $this->appliquerStyleSousTitle($sheet, "A{$ligne}");
            $ligne++;

            foreach ($donnees['passages_bibliques'] as $passage) {
                $verse = "";
                if ($passage["livre"]) {
                    $verse = $passage["livre"];
                }

                if ($passage["chapitre"]) {
                    $verse .= " " . $passage["chapitre"];
                }

                if ($passage["verset_debut"]) {
                    $verse .= ": " . $passage["verset_debut"];
                }

                if ($passage["verset_debut"]) {
                    $verse .= " - " . $passage["verset_debut"];
                }

                $sheet->setCellValue("A{$ligne}", $verse);
                $sheet->mergeCells("A{$ligne}:F{$ligne}");
                $sheet->getStyle("A{$ligne}")->applyFromArray([
                    'font' => ['italic' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFFFF3CD']
                    ]
                ]);

                $ligne++;
            }
            $ligne++;
        }

        // Résumé des activités
        $sheet->setCellValue("A{$ligne}", 'RÉSUMÉ DES ACTIVITÉS');
        $sheet->mergeCells("A{$ligne}:F{$ligne}");
        $this->appliquerStyleSousTitle($sheet, "A{$ligne}");
        $ligne++;

        $resume = [
            ['Nombre de passages:', count($donnees['detail_passages'])],
            ['Nombre de ventes:', count($donnees['detail_ventes'])],
            ['Nombre d\'engagements:', count($donnees['detail_engagements'])]
        ];

        foreach ($resume as $r) {
            $sheet->setCellValue("A{$ligne}", $r[0]);
            $sheet->setCellValue("B{$ligne}", $r[1]);
            $sheet->getStyle("A{$ligne}")->getFont()->setBold(true);
            $ligne++;
        }

        $this->ajusterLargeursColonnes($sheet);
    }



    /**
     * Créer la feuille détails passages
     */
    private function creerFeuillePassages($spreadsheet, $donnees)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Passages');

        $this->configurerStyleExcel($sheet);

        $ligne = 1;

        // Titre
        $sheet->setCellValue("A{$ligne}", 'DÉTAIL DES PASSAGES');
        $sheet->mergeCells("A{$ligne}:H{$ligne}");
        $this->appliquerStyleTitre($sheet, "A{$ligne}");
        $ligne += 2;

        if (count($donnees['detail_passages']) > 0) {
            // En-têtes
            $entetes = [
                'A' => 'Catégorie',
                'B' => 'Classe',
                'C' => 'Objectif (FCFA)',
                'D' => 'Collecté (FCFA)',
                'E' => 'Reste (FCFA)',
                'F' => 'Pourcentage',
                'G' => 'Collecteur',
                'H' => 'Date collecte'
            ];

            foreach ($entetes as $col => $titre) {
                $sheet->setCellValue("{$col}{$ligne}", $titre);
            }
            $this->appliquerStyleEntetes($sheet, "A{$ligne}:H{$ligne}");
            $ligne++;

            // Données
            $totalObjectif = 0;
            $totalCollecte = 0;

            foreach ($donnees['detail_passages'] as $passage) {
                $sheet->setCellValue("A{$ligne}", $passage['categorie']);
                $sheet->setCellValue("B{$ligne}", $passage['classe'] ?? 'N/A');
                $sheet->setCellValue("C{$ligne}", number_format($passage['objectif'], 0, ',', ' '));
                $sheet->setCellValue("D{$ligne}", number_format($passage['collecte'], 0, ',', ' '));
                $sheet->setCellValue("E{$ligne}", number_format($passage['reste'], 0, ',', ' '));
                $sheet->setCellValue("F{$ligne}", $passage['pourcentage'] . '%');
                $sheet->setCellValue("G{$ligne}", $passage['collecteur']);
                $sheet->setCellValue("H{$ligne}", $passage['date_collecte'] ?? 'N/A');

                // Colorier selon le pourcentage
                $this->colorierLigneSelonPourcentage($sheet, $ligne, $passage['pourcentage']);

                $totalObjectif += $passage['objectif'];
                $totalCollecte += $passage['collecte'];
                $ligne++;
            }

            // Ligne de totaux
            $sheet->setCellValue("A{$ligne}", 'TOTAUX');
            $sheet->setCellValue("C{$ligne}", number_format($totalObjectif, 0, ',', ' '));
            $sheet->setCellValue("D{$ligne}", number_format($totalCollecte, 0, ',', ' '));
            $sheet->setCellValue("F{$ligne}", $totalObjectif > 0 ? round(($totalCollecte * 100) / $totalObjectif, 2) . '%' : '0%');
            $this->appliquerStyleTotaux($sheet, "A{$ligne}:H{$ligne}");
        } else {
            $sheet->setCellValue("A{$ligne}", 'Aucun passage enregistré');
            $sheet->mergeCells("A{$ligne}:H{$ligne}");
            $sheet->getStyle("A{$ligne}")->applyFromArray([
                'font' => ['italic' => true, 'color' => ['argb' => 'FF999999']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
        }

        $this->ajusterLargeursColonnes($sheet);
    }



    /**
     * Créer la feuille détails ventes
     */
    private function creerFeuilleVentes($spreadsheet, $donnees)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Ventes');

        $this->configurerStyleExcel($sheet);

        $ligne = 1;

        // Titre
        $sheet->setCellValue("A{$ligne}", 'DÉTAIL DES VENTES');
        $sheet->mergeCells("A{$ligne}:I{$ligne}");
        $this->appliquerStyleTitre($sheet, "A{$ligne}");
        $ligne += 2;

        if (count($donnees['detail_ventes']) > 0) {
            // En-têtes
            $entetes = [
                'A' => 'Catégorie',
                'B' => 'Description',
                'C' => 'Objectif (FCFA)',
                'D' => 'Collecté (FCFA)',
                'E' => 'Reste (FCFA)',
                'F' => 'Pourcentage',
                'G' => 'Collecteur',
                'H' => 'Date collecte',
                'I' => 'Statut'
            ];

            foreach ($entetes as $col => $titre) {
                $sheet->setCellValue("{$col}{$ligne}", $titre);
            }
            $this->appliquerStyleEntetes($sheet, "A{$ligne}:I{$ligne}");
            $ligne++;

            // Données
            $totalObjectif = 0;
            $totalCollecte = 0;

            foreach ($donnees['detail_ventes'] as $vente) {
                $sheet->setCellValue("A{$ligne}", $vente['categorie']);
                $sheet->setCellValue("B{$ligne}", $vente['description'] ?? 'N/A');
                $sheet->setCellValue("C{$ligne}", number_format($vente['objectif'], 0, ',', ' '));
                $sheet->setCellValue("D{$ligne}", number_format($vente['collecte'], 0, ',', ' '));
                $sheet->setCellValue("E{$ligne}", number_format($vente['reste'], 0, ',', ' '));
                $sheet->setCellValue("F{$ligne}", $vente['pourcentage'] . '%');
                $sheet->setCellValue("G{$ligne}", $vente['collecteur']);
                $sheet->setCellValue("H{$ligne}", $vente['date_collecte'] ?? 'N/A');
                $sheet->setCellValue("I{$ligne}", $vente['statut']);

                $this->colorierLigneSelonPourcentage($sheet, $ligne, $vente['pourcentage']);

                $totalObjectif += $vente['objectif'];
                $totalCollecte += $vente['collecte'];
                $ligne++;
            }

            // Ligne de totaux
            $sheet->setCellValue("A{$ligne}", 'TOTAUX');
            $sheet->setCellValue("C{$ligne}", number_format($totalObjectif, 0, ',', ' '));
            $sheet->setCellValue("D{$ligne}", number_format($totalCollecte, 0, ',', ' '));
            $sheet->setCellValue("F{$ligne}", $totalObjectif > 0 ? round(($totalCollecte * 100) / $totalObjectif, 2) . '%' : '0%');
            $this->appliquerStyleTotaux($sheet, "A{$ligne}:I{$ligne}");
        } else {
            $sheet->setCellValue("A{$ligne}", 'Aucune vente enregistrée');
            $sheet->mergeCells("A{$ligne}:I{$ligne}");
            $sheet->getStyle("A{$ligne}")->applyFromArray([
                'font' => ['italic' => true, 'color' => ['argb' => 'FF999999']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
        }

        $this->ajusterLargeursColonnes($sheet);
    }



    /**
     * Créer la feuille détails engagements
     */
    private function creerFeuilleEngagements($spreadsheet, $donnees)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Engagements');

        $this->configurerStyleExcel($sheet);

        $ligne = 1;

        // Titre
        $sheet->setCellValue("A{$ligne}", 'DÉTAIL DES ENGAGEMENTS');
        $sheet->mergeCells("A{$ligne}:J{$ligne}");
        $this->appliquerStyleTitre($sheet, "A{$ligne}");
        $ligne += 2;

        if (count($donnees['detail_engagements']) > 0) {
            // En-têtes
            $entetes = [
                'A' => 'Type',
                'B' => 'Donateur',
                'C' => 'Objectif (FCFA)',
                'D' => 'Collecté (FCFA)',
                'E' => 'Reste (FCFA)',
                'F' => 'Pourcentage',
                'G' => 'Échéance',
                'H' => 'Téléphone',
                'I' => 'Email',
                'J' => 'Statut'
            ];

            foreach ($entetes as $col => $titre) {
                $sheet->setCellValue("{$col}{$ligne}", $titre);
            }
            $this->appliquerStyleEntetes($sheet, "A{$ligne}:J{$ligne}");
            $ligne++;

            // Données
            $totalObjectif = 0;
            $totalCollecte = 0;
            $engagementsEnRetard = 0;

            foreach ($donnees['detail_engagements'] as $engagement) {
                $sheet->setCellValue("A{$ligne}", $engagement['categorie']);
                $sheet->setCellValue("B{$ligne}", $engagement['donateur']);
                $sheet->setCellValue("C{$ligne}", number_format($engagement['objectif'], 0, ',', ' '));
                $sheet->setCellValue("D{$ligne}", number_format($engagement['collecte'], 0, ',', ' '));
                $sheet->setCellValue("E{$ligne}", number_format($engagement['reste'], 0, ',', ' '));
                $sheet->setCellValue("F{$ligne}", $engagement['pourcentage'] . '%');
                $sheet->setCellValue("G{$ligne}", $engagement['date_echeance'] ?? 'N/A');
                $sheet->setCellValue("H{$ligne}", $engagement['telephone'] ?? 'N/A');
                $sheet->setCellValue("I{$ligne}", $engagement['email'] ?? 'N/A');
                $sheet->setCellValue("J{$ligne}", $engagement['statut']);

                // Mettre en évidence les retards
                if ($engagement['en_retard']) {
                    $sheet->getStyle("A{$ligne}:J{$ligne}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FFFCE4EC']
                        ],
                        'font' => ['color' => ['argb' => 'FF721C24']]
                    ]);
                    $engagementsEnRetard++;
                } else {
                    $this->colorierLigneSelonPourcentage($sheet, $ligne, $engagement['pourcentage']);
                }

                $totalObjectif += $engagement['objectif'];
                $totalCollecte += $engagement['collecte'];
                $ligne++;
            }

            // Ligne de totaux
            $sheet->setCellValue("A{$ligne}", 'TOTAUX');
            $sheet->setCellValue("C{$ligne}", number_format($totalObjectif, 0, ',', ' '));
            $sheet->setCellValue("D{$ligne}", number_format($totalCollecte, 0, ',', ' '));
            $sheet->setCellValue("F{$ligne}", $totalObjectif > 0 ? round(($totalCollecte * 100) / $totalObjectif, 2) . '%' : '0%');
            $this->appliquerStyleTotaux($sheet, "A{$ligne}:J{$ligne}");
            $ligne++;

            // Alertes
            if ($engagementsEnRetard > 0) {
                $ligne++;
                $sheet->setCellValue("A{$ligne}", "⚠️ ALERTE: {$engagementsEnRetard} engagement(s) en retard");
                $sheet->mergeCells("A{$ligne}:J{$ligne}");
                $sheet->getStyle("A{$ligne}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['argb' => 'FF721C24']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF8D7DA']
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
            }
        } else {
            $sheet->setCellValue("A{$ligne}", 'Aucun engagement enregistré');
            $sheet->mergeCells("A{$ligne}:J{$ligne}");
            $sheet->getStyle("A{$ligne}")->applyFromArray([
                'font' => ['italic' => true, 'color' => ['argb' => 'FF999999']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
        }

        $this->ajusterLargeursColonnes($sheet);
    }



    /**
     * Méthodes utilitaires Excel supplémentaires
     */
    private function appliquerStyleSousTitle($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);
    }


    private function colorierLigneSelonPourcentage($sheet, $ligne, $pourcentage)
    {
        $couleur = 'FFFFFFFF'; // Blanc par défaut

        if ($pourcentage >= 100) {
            $couleur = 'FFD5E8D4'; // Vert clair
        } elseif ($pourcentage >= 90) {
            $couleur = 'FFFFF2CC'; // Jaune clair
        } elseif ($pourcentage >= 70) {
            $couleur = 'FFDAE8FC'; // Bleu clair
        } elseif ($pourcentage >= 50) {
            $couleur = 'FFF0F8FF'; // Bleu très clair
        } else {
            $couleur = 'FFFCE4EC'; // Rouge très clair
        }

        $lastCol = $sheet->getHighestColumn();
        $sheet->getStyle("A{$ligne}:{$lastCol}{$ligne}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => $couleur]
            ]
        ]);
    }


    /**
     * Méthode corrigée pour les filtres avec validation
     */
    private function ajouterInfosFiltres($sheet, $filtres, $ligne)
    {
        $sheet->setCellValue("A{$ligne}", 'FILTRES APPLIQUÉS');
        $sheet->mergeCells("A{$ligne}:M{$ligne}");
        $sheet->getStyle("A{$ligne}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FF4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);
        $ligne++;

        $infos = [];
        if (isset($filtres['date_debut']) && !empty($filtres['date_debut'])) {
            try {
                $date = Carbon::parse($filtres['date_debut']);
                $infos[] = 'Date début: ' . $date->format('d/m/Y');
            } catch (Exception $e) {
                $infos[] = 'Date début: ' . $this->sanitizeForExcel($filtres['date_debut']);
            }
        }

        if (isset($filtres['date_fin']) && !empty($filtres['date_fin'])) {
            try {
                $date = Carbon::parse($filtres['date_fin']);
                $infos[] = 'Date fin: ' . $date->format('d/m/Y');
            } catch (Exception $e) {
                $infos[] = 'Date fin: ' . $this->sanitizeForExcel($filtres['date_fin']);
            }
        }

        if (isset($filtres['status'])) {
            $infos[] = 'Statut: ' . ($filtres['status'] ? 'Actif' : 'Inactif');
        }

        if (isset($filtres['statut_progression']) && !empty($filtres['statut_progression'])) {
            $infos[] = 'Progression: ' . $this->sanitizeForExcel($filtres['statut_progression']);
        }

        if (!empty($infos)) {
            $sheet->setCellValue("A{$ligne}", implode(' | ', $infos));
            $sheet->mergeCells("A{$ligne}:M{$ligne}");
            $sheet->getStyle("A{$ligne}")->applyFromArray([
                'font' => ['italic' => true, 'color' => ['argb' => 'FF666666']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
            $ligne++;
        }

        return $ligne + 1;
    }

    private function appliquerStyleTotaux($sheet, $range)
    {
        $sheet->getStyle($range)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF2E74B5']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK,
                    'color' => ['argb' => 'FF2E74B5']
                ]
            ]
        ]);
    }

    private function ajouterFooterExcel($sheet, $ligne)
    {
        $ligne += 2;
        $sheet->setCellValue("A{$ligne}", 'Généré le ' . now()->format('d/m/Y à H:i'));
        $sheet->setCellValue("G{$ligne}", 'Système de Gestion des Moissons');

        $sheet->getStyle("A{$ligne}:G{$ligne}")->applyFromArray([
            'font' => ['size' => 9, 'color' => ['argb' => 'FF888888']],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFDDDDDD']
                ]
            ]
        ]);
    }

}
