<?php

namespace App\Http\Controllers\Private\Web;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DashboardExport;

class DashboardController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
        $this->middleware('auth');
    }

    /**
     * Affichage principal du dashboard unifié
     */
    public function index(Request $request)
    {
        try {
            $period = $request->get('period', 'mensuelle');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');

            // Calcul des dates selon la période
            $dateRange = $this->calculateDateRange($period, $startDate, $endDate);

            $dashboardData = [
                'period' => $period,
                'date_range' => $dateRange,
                'kpis' => $this->getMainKPIs($dateRange),
                'members_evolution' => $this->getMembersEvolution($dateRange, $period),
                'culte_attendance' => $this->getCulteAttendance($dateRange, $period),
                'offrandes_evolution' => $this->getOffrandesEvolution($dateRange, $period),
                'presence_offrande_ratio' => $this->getPresenceOffradeRatio($dateRange, $period),
                'souscripteur_fimeco_ratio' => $this->getSouscripteurFimecoRatio($dateRange, $period),
                'fimeco_evolution' => $this->getFimecoEvolution($dateRange, $period),
                'ratios' => $this->calculateRatios($dateRange),
                'trends' => $this->getTrends($dateRange)
            ];

            // Détecter le type de requête et retourner la réponse appropriée
            if ($request->wantsJson() || $request->ajax()) {
                // Réponse JSON pour les requêtes AJAX/API
                return response()->json([
                    'success' => true,
                    'data' => $dashboardData
                ]);
            } else {
                // Réponse Blade pour les requêtes web
                return view('components.private.index', compact('dashboardData'));
            }

        } catch (\Exception $e) {
            Log::error('Erreur Dashboard: ' . $e->getMessage());

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du chargement du dashboard'
                ], 500);
            } else {
                return redirect()->back()->with('error', 'Erreur lors du chargement du dashboard');
            }
        }
    }

    /**
     * Export des données du dashboard
     */
    // public function exporte(Request $request)
    // {
    //     try {
    //         $period = $request->get('period', 'mensuelle');
    //         $format = $request->get('format', 'excel'); // excel, csv, pdf
    //         $startDate = $request->get('start_date');
    //         $endDate = $request->get('end_date');

    //         $dateRange = $this->calculateDateRange($period, $startDate, $endDate);

    //         // Récupération de toutes les données
    //         $exportData = [
    //             'members' => $this->getMembersEvolution($dateRange, $period),
    //             'cultes' => $this->getCulteAttendance($dateRange, $period),
    //             'offrandes' => $this->getOffrandesEvolution($dateRange, $period),
    //             'fimecos' => $this->getFimecoEvolution($dateRange, $period),
    //             'ratios' => $this->calculateRatios($dateRange),
    //             'metadata' => [
    //                 'period' => $period,
    //                 'start_date' => $dateRange['start']->format('Y-m-d'),
    //                 'end_date' => $dateRange['end']->format('Y-m-d'),
    //                 'exported_at' => now()->format('Y-m-d H:i:s'),
    //                 'exported_by' => Auth::user()->prenom . ' ' . Auth::user()->nom
    //             ]
    //         ];

    //         return response()->json([
    //             'success' => true,
    //             'data' => $exportData,
    //             'message' => 'Données exportées avec succès'
    //         ]);

    //     } catch (\Exception $e) {
    //         Log::error('Erreur Export Dashboard: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Erreur lors de l\'export des données'
    //         ], 500);
    //     }
    // }

    /**
     * Export des données du dashboard en Excel ou PDF
     */
    public function exporte(Request $request)
    {
        try {
            $period = $request->get('period', 'mensuelle');
            $format = $request->get('format', 'excel'); // excel ou pdf
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');

            $dateRange = $this->calculateDateRange($period, $startDate, $endDate);

            // Récupération de toutes les données
            $dashboardData = [
                'period' => $period,
                'date_range' => $dateRange,
                'kpis' => $this->getMainKPIs($dateRange),
                'members_evolution' => $this->getMembersEvolution($dateRange, $period),
                'culte_attendance' => $this->getCulteAttendance($dateRange, $period),
                'offrandes_evolution' => $this->getOffrandesEvolution($dateRange, $period),
                'presence_offrande_ratio' => $this->getPresenceOffradeRatio($dateRange, $period),
                'souscripteur_fimeco_ratio' => $this->getSouscripteurFimecoRatio($dateRange, $period),
                'fimeco_evolution' => $this->getFimecoEvolution($dateRange, $period),
                'ratios' => $this->calculateRatios($dateRange),
                'trends' => $this->getTrends($dateRange),
                'metadata' => [
                    'period' => $period,
                    'start_date' => $dateRange['start']->format('d/m/Y'),
                    'end_date' => $dateRange['end']->format('d/m/Y'),
                    'exported_at' => now()->format('d/m/Y H:i:s'),
                    'exported_by' => Auth::user()->prenom . ' ' . Auth::user()->nom,
                    'church_name' => 'Église - Tableau de Bord',
                    'period_label' => $this->getPeriodLabel($period)
                ]
            ];

            if ($format === 'excel') {
                return $this->exportExcel($dashboardData);
            } elseif ($format === 'pdf') {
                return $this->exportPdf($dashboardData);
            }

            return response()->json([
                'success' => false,
                'message' => 'Format d\'export non supporté'
            ], 400);

        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Erreur Export Dashboard: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export des données: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Export Excel
     */
    private function exportExcel($data)
    {
        $filename = 'dashboard-eglise-' . Carbon::now()->format('Y-m-d-H-i-s') . '.xlsx';

        return Excel::download(new DashboardExport($data), $filename);
    }


    /**
     * Export PDF
     */
    private function exportPdf($data)
    {
        $pdf = Pdf::loadView('exports.dashboard.dashboard-pdf', compact('data'))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans'
            ]);

        $filename = 'dashboard-eglise-' . Carbon::now()->format('Y-m-d-H-i-s') . '.pdf';

        return $pdf->download($filename);
    }


    /**
     * Obtenir le label de la période
     */
    private function getPeriodLabel($period)
    {
        $labels = [
            'semaine' => 'Hebdomadaire',
            'mensuelle' => 'Mensuelle',
            'trimestrielle' => 'Trimestrielle',
            'semestrielle' => 'Semestrielle',
            'annuelle' => 'Annuelle'
        ];

        return $labels[$period] ?? 'Personnalisée';
    }



    /**
 * 1. Évolution du nombre de personnes (membres) inscrites
 */
public function getMembersEvolution($dateRange, $period)
{
    $groupBy = $this->getGroupByPeriod($period);

    // Requête simplifiée sans paramètres complexes
    $data = DB::table('users')
        ->selectRaw("
            {$groupBy} as period,
            COUNT(*) as total_membres,
            COUNT(CASE WHEN statut_membre = 'actif' THEN 1 END) as membres_actifs,
            COUNT(CASE WHEN statut_membre = 'visiteur' THEN 1 END) as visiteurs,
            COUNT(CASE WHEN statut_membre = 'nouveau_converti' THEN 1 END) as nouveaux_convertis
        ")
        ->whereNull('deleted_at')
        ->where('created_at', '>=', $dateRange['start'])
        ->where('created_at', '<=', $dateRange['end'])
        ->groupByRaw($groupBy)
        ->orderByRaw($groupBy)
        ->get();

    $results = [];
    foreach ($data as $item) {

        // Calcul simple des nouveaux membres sans paramètres complexes
        $nouveauxMembres = 0; // Temporairement mis à 0 pour éviter l'erreur

         $nouveauxMembres = DB::table('users')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();

        $results[] = [
            'period' => $this->formatPeriodLabel($item->period, $period),
            'total_membres' => (int) $item->total_membres,
            'nouveaux_membres' => (int) $nouveauxMembres,
            'membres_actifs' => (int) $item->membres_actifs,
            'visiteurs' => (int) $item->visiteurs,
            'nouveaux_convertis' => (int) $item->nouveaux_convertis
        ];
    }

    return $results;
}

    /**
     * 2. Évolution du nombre de personnes présentes au culte
     */
    public function getCulteAttendance($dateRange, $period)
    {
        $groupBy = $this->getGroupByPeriod($period, 'c.date_culte');

        $query = DB::table('cultes as c')
            ->leftJoin('participant_cultes as pc', 'c.id', '=', 'pc.culte_id')
            ->select([
                DB::raw($groupBy . ' as period'),
                DB::raw('AVG(c.nombre_participants) as avg_participants'),
                DB::raw('SUM(c.nombre_participants) as total_participants'),
                DB::raw('AVG(c.nombre_adultes) as avg_adultes'),
                DB::raw('AVG(c.nombre_enfants) as avg_enfants'),
                DB::raw('AVG(c.nombre_jeunes) as avg_jeunes'),
                DB::raw('SUM(c.nombre_nouveaux) as total_nouveaux'),
                DB::raw('COUNT(CASE WHEN pc.type_participation = \'en_ligne\' THEN 1 END) as participants_en_ligne'),
                DB::raw('COUNT(CASE WHEN pc.type_participation = \'physique\' THEN 1 END) as participants_physiques'),
                DB::raw('COUNT(CASE WHEN pc.premiere_visite = true THEN 1 END) as nouveaux_visiteurs'),
                DB::raw('COUNT(DISTINCT c.id) as nombre_cultes')
            ])
            ->whereNull('c.deleted_at')
            ->where('c.statut', 'termine')
            ->whereBetween('c.date_culte', [$dateRange['start'], $dateRange['end']])
            ->groupBy(DB::raw($groupBy))
            ->orderBy(DB::raw($groupBy));

        return $query->get()->map(function($item) use ($period) {
            return [
                'period' => $this->formatPeriodLabel($item->period, $period),
                'avg_participants' => round($item->avg_participants, 0),
                'total_participants' => $item->total_participants,
                'avg_adultes' => round($item->avg_adultes, 0),
                'avg_enfants' => round($item->avg_enfants, 0),
                'avg_jeunes' => round($item->avg_jeunes, 0),
                'total_nouveaux' => $item->total_nouveaux,
                'participants_en_ligne' => $item->participants_en_ligne,
                'participants_physiques' => $item->participants_physiques,
                'nouveaux_visiteurs' => $item->nouveaux_visiteurs,
                'nombre_cultes' => $item->nombre_cultes,
                'taux_presence' => $item->total_participants > 0 ? round(($item->participants_physiques / $item->total_participants) * 100, 1) : 0
            ];
        });
    }

    /**
     * 3. Évolution des offrandes avec graphiques
     */
    public function getOffrandesEvolution($dateRange, $period)
    {
        $groupBy = $this->getGroupByPeriod($period, 'date_transaction');

        $query = DB::table('fonds')
            ->select([
                DB::raw($groupBy . ' as period'),
                DB::raw('SUM(CASE WHEN type_transaction = \'dime\' THEN montant ELSE 0 END) as dimes'),
                DB::raw('SUM(CASE WHEN type_transaction = \'offrande_ordinaire\' THEN montant ELSE 0 END) as offrandes_ordinaires'),
                DB::raw('SUM(CASE WHEN type_transaction = \'offrande_libre\' THEN montant ELSE 0 END) as offrandes_libres'),
                DB::raw('SUM(CASE WHEN type_transaction = \'offrande_speciale\' THEN montant ELSE 0 END) as offrandes_speciales'),
                DB::raw('SUM(CASE WHEN type_transaction = \'offrande_mission\' THEN montant ELSE 0 END) as offrandes_missions'),
                DB::raw('SUM(CASE WHEN type_transaction = \'offrande_construction\' THEN montant ELSE 0 END) as offrandes_construction'),
                DB::raw('SUM(montant) as total_offrandes'),
                DB::raw('COUNT(*) as nombre_transactions'),
                DB::raw('AVG(montant) as montant_moyen'),
                DB::raw('COUNT(DISTINCT donateur_id) as donateurs_uniques')
            ])
            ->whereNull('deleted_at')
            ->where('statut', 'validee')
            ->whereBetween('date_transaction', [$dateRange['start'], $dateRange['end']])
            ->groupBy(DB::raw($groupBy))
            ->orderBy(DB::raw($groupBy));

        return $query->get()->map(function($item) use ($period) {
            return [
                'period' => $this->formatPeriodLabel($item->period, $period),
                'dimes' => $item->dimes,
                'offrandes_ordinaires' => $item->offrandes_ordinaires,
                'offrandes_libres' => $item->offrandes_libres,
                'offrandes_speciales' => $item->offrandes_speciales,
                'offrandes_missions' => $item->offrandes_missions,
                'offrandes_construction' => $item->offrandes_construction,
                'total_offrandes' => $item->total_offrandes,
                'nombre_transactions' => $item->nombre_transactions,
                'montant_moyen' => round($item->montant_moyen, 0),
                'donateurs_uniques' => $item->donateurs_uniques
            ];
        });
    }

    /**
     * 4. Ratio entre nombre de personnes présentes au culte et offrande
     */
    public function getPresenceOffradeRatio($dateRange, $period)
    {
        $groupBy = $this->getGroupByPeriod($period, 'c.date_culte');

        $query = DB::table('cultes as c')
            ->leftJoin('fonds as f', 'c.id', '=', 'f.culte_id')
            ->select([
                DB::raw($groupBy . ' as period'),
                DB::raw('AVG(c.nombre_participants) as avg_participants'),
                DB::raw('SUM(CASE WHEN f.statut = \'validee\' THEN f.montant ELSE 0 END) as total_offrandes'),
                DB::raw('COUNT(DISTINCT c.id) as nombre_cultes')
            ])
            ->whereNull('c.deleted_at')
            ->where('c.statut', 'termine')
            ->whereBetween('c.date_culte', [$dateRange['start'], $dateRange['end']])
            ->groupBy(DB::raw($groupBy))
            ->orderBy(DB::raw($groupBy));

        return $query->get()->map(function($item) use ($period) {
            $ratio = $item->avg_participants > 0 ?
                round($item->total_offrandes / $item->avg_participants, 0) : 0;

            return [
                'period' => $this->formatPeriodLabel($item->period, $period),
                'avg_participants' => round($item->avg_participants, 0),
                'total_offrandes' => $item->total_offrandes,
                'ratio_offrande_par_personne' => $ratio,
                'nombre_cultes' => $item->nombre_cultes
            ];
        });
    }

    /**
     * 5. Ratio entre nombre de souscripteurs et montant collecté FIMECO
     */
    public function getSouscripteurFimecoRatio($dateRange, $period)
    {
        $groupBy = $this->getGroupByPeriod($period, 's.date_souscription');

        $query = DB::table('subscriptions as s')
            ->join('fimecos as f', 's.fimeco_id', '=', 'f.id')
            ->select([
                DB::raw($groupBy . ' as period'),
                DB::raw('COUNT(DISTINCT s.souscripteur_id) as nombre_souscripteurs'),
                DB::raw('SUM(s.montant_paye) as total_collecte'),
                DB::raw('SUM(s.montant_souscrit) as total_souscrit'),
                DB::raw('AVG(s.montant_paye) as montant_moyen_paye'),
                DB::raw('AVG(s.progression) as progression_moyenne'),
                DB::raw('COUNT(DISTINCT f.id) as nombre_fimecos')
            ])
            ->whereNull('s.deleted_at')
            ->whereNull('f.deleted_at')
            ->whereBetween('s.date_souscription', [$dateRange['start'], $dateRange['end']])
            ->groupBy(DB::raw($groupBy))
            ->orderBy(DB::raw($groupBy));

        return $query->get()->map(function($item) use ($period) {
            $ratio = $item->nombre_souscripteurs > 0 ?
                round($item->total_collecte / $item->nombre_souscripteurs, 0) : 0;

            return [
                'period' => $this->formatPeriodLabel($item->period, $period),
                'nombre_souscripteurs' => $item->nombre_souscripteurs,
                'total_collecte' => $item->total_collecte,
                'total_souscrit' => $item->total_souscrit,
                'ratio_collecte_par_souscripteur' => $ratio,
                'montant_moyen_paye' => round($item->montant_moyen_paye, 0),
                'progression_moyenne' => round($item->progression_moyenne, 1),
                'taux_realisation' => $item->total_souscrit > 0 ?
                    round(($item->total_collecte / $item->total_souscrit) * 100, 1) : 0
            ];
        });
    }

    /**
     * 6. Évaluation et évolution des FIMECO
     */
    public function getFimecoEvolution($dateRange, $period)
    {
        $groupBy = $this->getGroupByPeriod($period, 'debut');

        $fimecos = DB::table('fimecos')
            ->select([
                DB::raw($groupBy . ' as period'),
                'id',
                'nom',
                'cible',
                'montant_solde',
                'reste',
                'montant_supplementaire',
                'progression',
                'statut_global',
                'statut',
                'debut',
                'fin'
            ])
            ->whereNull('deleted_at')
            ->whereBetween('debut', [$dateRange['start'], $dateRange['end']])
            ->orderBy('debut');

        $evolution = [];
        $currentFimecos = $fimecos->get();

        foreach ($currentFimecos as $fimeco) {
            $souscriptions = DB::table('subscriptions')
                ->where('fimeco_id', $fimeco->id)
                ->whereNull('deleted_at')
                ->get();

            $evolution[] = [
                'period' => $this->formatPeriodLabel($fimeco->period, $period),
                'fimeco_id' => $fimeco->id,
                'nom' => $fimeco->nom,
                'cible' => $fimeco->cible,
                'montant_solde' => $fimeco->montant_solde,
                'reste' => $fimeco->reste,
                'montant_supplementaire' => $fimeco->montant_supplementaire,
                'progression' => $fimeco->progression,
                'statut_global' => $fimeco->statut_global,
                'statut' => $fimeco->statut,
                'nombre_souscripteurs' => $souscriptions->count(),
                'montant_moyen_souscription' => $souscriptions->avg('montant_souscrit'),
                'taux_paiement' => $souscriptions->avg('progression'),
                'debut' => $fimeco->debut,
                'fin' => $fimeco->fin
            ];
        }

        // Agrégation par période si nécessaire
        $aggregated = collect($evolution)->groupBy('period')->map(function($group) {
            return [
                'period' => $group->first()['period'],
                'nombre_fimecos' => $group->count(),
                'cible_totale' => $group->sum('cible'),
                'collecte_totale' => $group->sum('montant_solde'),
                'progression_moyenne' => $group->avg('progression'),
                'souscripteurs_totaux' => $group->sum('nombre_souscripteurs'),
                'fimecos_details' => $group->toArray()
            ];
        })->values();

        return $aggregated;
    }

    /**
     * Calcul des ratios principaux
     */
    public function calculateRatios($dateRange)
    {
        // Ratio présence/offrande
        $culteStats = DB::table('cultes as c')
            ->leftJoin('fonds as f', 'c.id', '=', 'f.culte_id')
            ->select([
                DB::raw('AVG(c.nombre_participants) as avg_participants'),
                DB::raw('SUM(CASE WHEN f.statut = \'validee\' THEN f.montant ELSE 0 END) as total_offrandes')
            ])
            ->whereNull('c.deleted_at')
            ->where('c.statut', 'termine')
            ->whereBetween('c.date_culte', [$dateRange['start'], $dateRange['end']])
            ->first();

        // Ratio souscripteur/collecte FIMECO
        $fimecoStats = DB::table('subscriptions as s')
            ->join('fimecos as f', 's.fimeco_id', '=', 'f.id')
            ->select([
                DB::raw('COUNT(DISTINCT s.souscripteur_id) as total_souscripteurs'),
                DB::raw('SUM(s.montant_paye) as total_collecte')
            ])
            ->whereNull('s.deleted_at')
            ->whereNull('f.deleted_at')
            ->whereBetween('s.date_souscription', [$dateRange['start'], $dateRange['end']])
            ->first();

        return [
            'presence_offrande_ratio' => $culteStats->avg_participants > 0 ?
                round($culteStats->total_offrandes / $culteStats->avg_participants, 0) : 0,
            'souscripteur_collecte_ratio' => $fimecoStats->total_souscripteurs > 0 ?
                round($fimecoStats->total_collecte / $fimecoStats->total_souscripteurs, 0) : 0,
            'avg_participants' => round($culteStats->avg_participants, 0),
            'total_offrandes' => $culteStats->total_offrandes,
            'total_souscripteurs' => $fimecoStats->total_souscripteurs,
            'total_collecte_fimeco' => $fimecoStats->total_collecte
        ];
    }

    /**
     * KPIs principaux pour la vue d'ensemble
     */
    public function getMainKPIs($dateRange)
    {
        // Total membres
        $totalMembres = DB::table('users')
            ->whereNull('deleted_at')
            ->count();

        $nouveauxMembres = DB::table('users')
            ->whereNull('deleted_at')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->count();

        // Présence moyenne
        $presenceStats = DB::table('cultes')
            ->whereNull('deleted_at')
            ->where('statut', 'termine')
            ->whereBetween('date_culte', [$dateRange['start'], $dateRange['end']])
            ->selectRaw('AVG(nombre_participants) as avg_participants, COUNT(*) as nombre_cultes')
            ->first();

        // Offrandes totales
        $offrandesTotales = DB::table('fonds')
            ->whereNull('deleted_at')
            ->where('statut', 'validee')
            ->whereBetween('date_transaction', [$dateRange['start'], $dateRange['end']])
            ->sum('montant');

        // FIMECO actuel
        $fimecoActuel = DB::table('fimecos')
            ->whereNull('deleted_at')
            ->where('statut', 'active')
            ->first();

        return [
            'total_membres' => $totalMembres,
            'nouveaux_membres' => $nouveauxMembres,
            'avg_participants' => round($presenceStats->avg_participants ?? 0, 0),
            'nombre_cultes' => $presenceStats->nombre_cultes ?? 0,
            'total_offrandes' => $offrandesTotales,
            'fimeco_progression' => $fimecoActuel ? round($fimecoActuel->progression, 1) : 0,
            'fimeco_nom' => $fimecoActuel->nom ?? 'Aucun FIMECO actif'
        ];
    }

    /**
     * Vue d'ensemble générale - SUPPRIMÉE car intégrée dans index()
     */
    // public function getOverviewData($dateRange) - Supprimée

    /**
     * Calcul des tendances
     */
    private function getTrends($dateRange)
    {
        // Comparaison avec la période précédente
        $currentPeriod = Carbon::parse($dateRange['start'])->diffInDays(Carbon::parse($dateRange['end']));
        $previousStart = Carbon::parse($dateRange['start'])->subDays($currentPeriod);
        $previousEnd = Carbon::parse($dateRange['start']);

        $currentOffrandes = DB::table('fonds')
            ->where('statut', 'validee')
            ->whereBetween('date_transaction', [$dateRange['start'], $dateRange['end']])
            ->sum('montant');

        $previousOffrandes = DB::table('fonds')
            ->where('statut', 'validee')
            ->whereBetween('date_transaction', [$previousStart, $previousEnd])
            ->sum('montant');

        $offrandesTrend = $previousOffrandes > 0 ?
            round((($currentOffrandes - $previousOffrandes) / $previousOffrandes) * 100, 1) : 0;

        return [
            'offrandes_trend' => $offrandesTrend,
            'current_offrandes' => $currentOffrandes,
            'previous_offrandes' => $previousOffrandes
        ];
    }

    /**
     * Calcul des plages de dates selon la période
     */
    private function calculateDateRange($period, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            return [
                'start' => Carbon::parse($startDate)->startOfDay(),
                'end' => Carbon::parse($endDate)->endOfDay()
            ];
        }

        $now = Carbon::now();

        switch ($period) {
            case 'semaine':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek()
                ];
            case 'mensuelle':
                return [
                    'start' => $now->copy()->subMonths(11)->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
            case 'trimestrielle':
                return [
                    'start' => $now->copy()->subMonths(12)->startOfQuarter(),
                    'end' => $now->copy()->endOfQuarter()
                ];
            case 'semestrielle':
                return [
                    'start' => $now->copy()->subMonths(12)->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            case 'annuelle':
                return [
                    'start' => $now->copy()->subYears(4)->startOfYear(),
                    'end' => $now->copy()->endOfYear()
                ];
            default:
                return [
                    'start' => $now->copy()->subMonths(11)->startOfMonth(),
                    'end' => $now->copy()->endOfMonth()
                ];
        }
    }

    /**
     * Génération du GROUP BY selon la période - Version PostgreSQL
     */
    private function getGroupByPeriod($period, $dateField = 'created_at')
    {
        switch ($period) {
            case 'semaine':
                return "DATE({$dateField})";
            case 'mensuelle':
                return "TO_CHAR({$dateField}, 'YYYY-MM')";
            case 'trimestrielle':
                return "CONCAT(EXTRACT(YEAR FROM {$dateField}), '-Q', EXTRACT(QUARTER FROM {$dateField}))";
            case 'semestrielle':
                return "CONCAT(EXTRACT(YEAR FROM {$dateField}), '-S', CASE WHEN EXTRACT(MONTH FROM {$dateField}) <= 6 THEN '1' ELSE '2' END)";
            case 'annuelle':
                return "EXTRACT(YEAR FROM {$dateField})";
            default:
                return "TO_CHAR({$dateField}, 'YYYY-MM')";
        }
    }

    /**
     * Formatage des labels de période - Version PostgreSQL
     */
    private function formatPeriodLabel($period, $type)
    {
        switch ($type) {
            case 'semaine':
                return Carbon::parse($period)->format('d/m');
            case 'mensuelle':
                // Pour PostgreSQL, $period est au format 'YYYY-MM'
                return Carbon::createFromFormat('Y-m', $period)->format('M Y');
            case 'trimestrielle':
                return $period; // Format 'YYYY-QX'
            case 'semestrielle':
                return $period; // Format 'YYYY-SX'
            case 'annuelle':
                return $period; // Format 'YYYY'
            default:
                return $period;
        }
    }

    /**
     * Calcul du début de période - Version PostgreSQL
     */
    private function getPeriodStart($period, $type)
    {
        switch ($type) {
            case 'semaine':
                return Carbon::parse($period)->startOfDay();
            case 'mensuelle':
                // Pour PostgreSQL, $period est au format 'YYYY-MM'
                return Carbon::createFromFormat('Y-m', $period)->startOfMonth();
            case 'trimestrielle':
                // Format 'YYYY-QX'
                $parts = explode('-Q', $period);
                $year = $parts[0];
                $quarter = $parts[1];
                return Carbon::create($year)->quarter($quarter)->startOfQuarter();
            case 'semestrielle':
                // Format 'YYYY-SX'
                $parts = explode('-S', $period);
                $year = $parts[0];
                $semester = $parts[1];
                $month = $semester == '1' ? 1 : 7;
                return Carbon::create($year, $month)->startOfMonth();
            case 'annuelle':
                return Carbon::create($period)->startOfYear();
            default:
                return Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        }
    }

    /**
     * Calcul de la fin de période - Version PostgreSQL
     */
    private function getPeriodEnd($period, $type)
    {
        switch ($type) {
            case 'semaine':
                return Carbon::parse($period)->endOfDay();
            case 'mensuelle':
                // Pour PostgreSQL, $period est au format 'YYYY-MM'
                return Carbon::createFromFormat('Y-m', $period)->endOfMonth();
            case 'trimestrielle':
                // Format 'YYYY-QX'
                $parts = explode('-Q', $period);
                $year = $parts[0];
                $quarter = $parts[1];
                return Carbon::create($year)->quarter($quarter)->endOfQuarter();
            case 'semestrielle':
                // Format 'YYYY-SX'
                $parts = explode('-S', $period);
                $year = $parts[0];
                $semester = $parts[1];
                $month = $semester == '1' ? 6 : 12;
                return Carbon::create($year, $month)->endOfMonth();
            case 'annuelle':
                return Carbon::create($period)->endOfYear();
            default:
                return Carbon::createFromFormat('Y-m', $period)->endOfMonth();
        }
    }

    /**
     * Export des données du dashboard
     */
    public function export(Request $request)
    {
        try {
            $period = $request->get('period', 'mensuelle');
            $format = $request->get('format', 'excel'); // excel, csv, pdf
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');

            $dateRange = $this->calculateDateRange($period, $startDate, $endDate);

            // Récupération de toutes les données
            $exportData = [
                'members' => $this->getMembersEvolution($dateRange, $period),
                'cultes' => $this->getCulteAttendance($dateRange, $period),
                'offrandes' => $this->getOffrandesEvolution($dateRange, $period),
                'fimecos' => $this->getFimecoEvolution($dateRange, $period),
                'ratios' => $this->calculateRatios($dateRange),
                'metadata' => [
                    'period' => $period,
                    'start_date' => $dateRange['start']->format('Y-m-d'),
                    'end_date' => $dateRange['end']->format('Y-m-d'),
                    'exported_at' => now()->format('Y-m-d H:i:s'),
                    'exported_by' => Auth::user()->prenom . ' ' . Auth::user()->nom
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $exportData,
                'message' => 'Données exportées avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur Export Dashboard: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export des données'
            ], 500);
        }
    }
}
