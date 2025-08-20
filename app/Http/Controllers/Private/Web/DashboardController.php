<?php

namespace App\Http\Controllers\Private\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard générale avec statistiques pour l'église.
     */
    public function index()
    {
        // Statistiques principales
        $stats = $this->getMainStats();

        // Statistiques des ministères/programmes
        $ministryStats = $this->getMinistryStats();

        // Données pour le graphique des offrandes
        $offeringChart = $this->getOfferingChartData();

        // Événements à venir
        $upcomingEvents = $this->getUpcomingEvents();

        // Dernières transactions
        $recentTransactions = $this->getRecentTransactions();

        // Réunions du jour
        $todayMeetings = $this->getTodayMeetings();

        // Cultes à venir
        $upcomingCultes = $this->getUpcomingCultes();

        // Annonces actives
        $activeAnnouncements = $this->getActiveAnnouncements();

        // Projets en cours
        $activeProjects = $this->getActiveProjects();

        // Indicateurs de performance
        $performanceIndicators = $this->getPerformanceIndicators();

        // Actions requises (alertes)
        $actionsRequired = $this->getActionsRequired();

        return view('dashboard', compact(
            'stats',
            'ministryStats',
            'offeringChart',
            'upcomingEvents',
            'recentTransactions',
            'todayMeetings',
            'upcomingCultes',
            'activeAnnouncements',
            'activeProjects',
            'performanceIndicators',
            'actionsRequired'
        ));
    }

    /**
     * Récupère les statistiques principales
     */
    private function getMainStats()
    {
        $currentMonth = Carbon::now();
        $currentYear = Carbon::now()->year;

        return [
            // Nombre total de membres actifs
            'total_members' => DB::table('users')
                ->where('actif', true)
                ->where('statut_membre', 'actif')
                ->whereNull('deleted_at')
                ->count(),

            // Nouveaux membres ce mois
            'new_members_month' => DB::table('users')
                ->where('actif', true)
                ->whereMonth('created_at', $currentMonth->month)
                ->whereYear('created_at', $currentYear)
                ->whereNull('deleted_at')
                ->count(),

            // Événements ce mois
            'monthly_events' => DB::table('events')
                ->whereMonth('date_debut', $currentMonth->month)
                ->whereYear('date_debut', $currentYear)
                ->whereNotIn('statut', ['annule', 'archive'])
                ->whereNull('deleted_at')
                ->count(),

            // Total offrandes ce mois (en XOF)
            'monthly_offerings' => DB::table('transactions_spirituelles')
                ->where('statut', 'validee')
                ->whereMonth('date_transaction', $currentMonth->month)
                ->whereYear('date_transaction', $currentYear)
                ->where('devise', 'XOF')
                ->whereNull('deleted_at')
                ->sum('montant') ?? 0,

            // Réunions cette semaine
            'weekly_meetings' => DB::table('reunions')
                ->whereBetween('date_reunion', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ])
                ->whereIn('statut', ['confirmee', 'planifie', 'en_cours'])
                ->whereNull('deleted_at')
                ->count(),

            // Projets actifs
            'active_projects' => DB::table('projets')
                ->where('statut', 'en_cours')
                ->whereNull('deleted_at')
                ->count(),

            // Classes actives
            'active_classes' => DB::table('classes')
                ->whereNull('deleted_at')
                ->count(),

            // Total étudiants dans les classes
            'total_students' => DB::table('classes')
                ->whereNull('deleted_at')
                ->sum('nombre_inscrits') ?? 0
        ];
    }

    /**
     * Récupère les statistiques des ministères/programmes
     */
    private function getMinistryStats()
    {
        return [
            // Programmes jeunesse actifs
            'youth_programs' => [
                'count' => DB::table('programmes')
                    ->where('type_programme', 'jeunesse')
                    ->where('statut', 'actif')
                    ->whereNull('deleted_at')
                    ->count()
                // 'participants' => DB::table('programmes')
                //     ->where('type_programme', 'jeunesse')
                //     ->where('statut', 'actif')
                //     ->whereNull('deleted_at')
                //     ->sum('participants_attendus') ?? 0
            ],

            // Classes d'école du dimanche
            'sunday_school' => [
                'classes' => DB::table('classes')->whereNull('deleted_at')->count(),
                'students' => DB::table('classes')->whereNull('deleted_at')->sum('nombre_inscrits') ?? 0
            ],

            // Formations en cours
            'formations' => [
                'active' => DB::table('programmes')
                    ->where('type_programme', 'formation')
                    ->where('statut', 'actif')
                    ->whereNull('deleted_at')
                    ->count()
                // 'participants' => DB::table('programmes')
                //     ->where('type_programme', 'formation')
                //     ->where('statut', 'actif')
                //     ->whereNull('deleted_at')
                //     ->sum('participants_attendus') ?? 0
            ],

            // Missions et évangélisation
            'missions' => [
                'programs' => DB::table('programmes')
                    ->whereIn('type_programme', ['mission', 'evangelisation'])
                    ->where('statut', 'actif')
                    ->whereNull('deleted_at')
                    ->count(),
                'events' => DB::table('events')
                    ->where('type_evenement', 'evangelisation')
                    ->where('date_debut', '>=', Carbon::now())
                    ->whereNotIn('statut', ['annule', 'archive'])
                    ->whereNull('deleted_at')
                    ->count()
            ],

            // Cultes ce mois
            'monthly_cultes' => [
                'total' => DB::table('cultes')
                    ->whereMonth('date_culte', Carbon::now()->month)
                    ->whereYear('date_culte', Carbon::now()->year)
                    ->whereNull('deleted_at')
                    ->count(),
                'participants_total' => DB::table('cultes')
                    ->whereMonth('date_culte', Carbon::now()->month)
                    ->whereYear('date_culte', Carbon::now()->year)
                    ->where('statut', 'termine')
                    ->whereNull('deleted_at')
                    ->sum('nombre_participants') ?? 0
            ]
        ];
    }

    /**
     * Données pour le graphique des offrandes (6 derniers mois)
     */
    private function getOfferingChartData()
    {
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');

            $amount = DB::table('transactions_spirituelles')
                ->where('statut', 'validee')
                ->whereMonth('date_transaction', $date->month)
                ->whereYear('date_transaction', $date->year)
                ->where('devise', 'XOF')
                ->whereNull('deleted_at')
                ->sum('montant') ?? 0;

            $chartData[] = [
                'month' => $month,
                'amount' => $amount
            ];
        }

        return $chartData;
    }

    /**
     * Récupère les prochains événements
     */
    private function getUpcomingEvents()
    {
        return DB::table('events')
            ->select(
                'id', 'titre', 'date_debut', 'heure_debut',
                'lieu_nom', 'type_evenement', 'nombre_inscrits',
                'places_disponibles', 'statut'
            )
            ->where('date_debut', '>=', Carbon::now()->toDateString())
            ->whereNotIn('statut', ['annule', 'archive'])
            ->whereNull('deleted_at')
            ->orderBy('date_debut')
            ->orderBy('heure_debut')
            ->limit(5)
            ->get();
    }

    /**
     * Récupère les prochains cultes
     */
    private function getUpcomingCultes()
    {
        return DB::table('cultes as c')
            ->leftJoin('users as pasteur', 'c.pasteur_principal_id', '=', 'pasteur.id')
            ->leftJoin('users as pred', 'c.predicateur_id', '=', 'pred.id')
            ->select(
                'c.id', 'c.titre', 'c.date_culte', 'c.heure_debut',
                'c.type_culte', 'c.lieu', 'c.statut',
                DB::raw("CONCAT(COALESCE(pasteur.prenom, ''), ' ', COALESCE(pasteur.nom, '')) as pasteur"),
                DB::raw("CONCAT(COALESCE(pred.prenom, ''), ' ', COALESCE(pred.nom, '')) as predicateur")
            )
            ->where('c.date_culte', '>=', Carbon::now()->toDateString())
            ->whereIn('c.statut', ['planifie', 'planifie'])
            ->whereNull('c.deleted_at')
            ->orderBy('c.date_culte')
            ->orderBy('c.heure_debut')
            ->limit(3)
            ->get();
    }

    /**
     * Récupère les dernières transactions
     */
    private function getRecentTransactions()
    {
        return DB::table('transactions_spirituelles as ts')
            ->leftJoin('users as u', 'ts.donateur_id', '=', 'u.id')
            ->select(
                'ts.id', 'ts.montant', 'ts.devise', 'ts.type_transaction',
                'ts.date_transaction', 'ts.heure_transaction',
                DB::raw("COALESCE(CONCAT(u.prenom, ' ', u.nom), ts.nom_donateur_anonyme, 'Anonyme') as donateur"),
                'ts.est_anonyme'
            )
            ->where('ts.statut', 'validee')
            ->whereNull('ts.deleted_at')
            ->orderBy('ts.date_transaction', 'desc')
            ->orderBy('ts.heure_transaction', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Récupère les réunions du jour
     */
    private function getTodayMeetings()
    {
        return DB::table('reunions as r')
            ->leftJoin('type_reunions as tr', 'r.type_reunion_id', '=', 'tr.id')
            ->leftJoin('users as org', 'r.organisateur_principal_id', '=', 'org.id')
            ->select(
                'r.id', 'r.titre', 'r.heure_debut_prevue', 'r.heure_fin_prevue',
                'r.lieu', 'r.statut', 'tr.nom as type_reunion',
                DB::raw("CONCAT(COALESCE(org.prenom, ''), ' ', COALESCE(org.nom, '')) as organisateur")
            )
            ->where('r.date_reunion', Carbon::now()->toDateString())
            ->whereIn('r.statut', ['confirmee', 'planifie', 'en_cours'])
            ->whereNull('r.deleted_at')
            ->orderBy('r.heure_debut_prevue')
            ->get();
    }

    /**
     * Récupère les annonces actives
     */
    private function getActiveAnnouncements()
    {
        return DB::table('annonces as a')
            ->leftJoin('users as u', 'a.contact_principal_id', '=', 'u.id')
            ->select(
                'a.id', 'a.titre', 'a.resume_court', 'a.niveau_priorite',
                'a.date_evenement', 'a.publie_le', 'a.type_annonce',
                DB::raw("CONCAT(COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, '')) as contact")
            )
            ->where('a.statut', 'publiee')
            ->where(function($query) {
                $query->whereNull('expire_le')
                      ->orWhere('expire_le', '>', Carbon::now());
            })
            ->whereNull('a.deleted_at')
            ->orderBy('a.niveau_priorite', 'desc')
            ->orderBy('a.publie_le', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Récupère les projets actifs
     */
    private function getActiveProjects()
    {
        return DB::table('projets as p')
            ->leftJoin('users as resp', 'p.responsable_id', '=', 'resp.id')
            ->select(
                'p.id', 'p.nom_projet', 'p.budget_prevu', 'p.budget_collecte',
                'p.pourcentage_completion', 'p.statut', 'p.priorite',
                DB::raw("CONCAT(COALESCE(resp.prenom, ''), ' ', COALESCE(resp.nom, '')) as responsable")
            )
            ->where('p.statut', 'en_cours')
            ->whereNull('p.deleted_at')
            ->orderBy('p.priorite', 'desc')
            ->orderBy('p.pourcentage_completion', 'asc')
            ->limit(5)
            ->get();
    }

    /**
     * Indicateurs de performance
     */
    private function getPerformanceIndicators()
    {
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        // Évolution du nombre de membres
        $currentMembers = DB::table('users')
            ->where('actif', true)
            ->whereMonth('created_at', $currentMonth->month)
            ->whereYear('created_at', $currentMonth->year)
            ->whereNull('deleted_at')
            ->count();

        $lastMonthMembers = DB::table('users')
            ->where('actif', true)
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->whereNull('deleted_at')
            ->count();

        // Évolution des offrandes
        $currentOffrandes = DB::table('transactions_spirituelles')
            ->where('statut', 'validee')
            ->whereMonth('date_transaction', $currentMonth->month)
            ->whereYear('date_transaction', $currentMonth->year)
            ->whereNull('deleted_at')
            ->sum('montant') ?? 0;

        $lastMonthOffrandes = DB::table('transactions_spirituelles')
            ->where('statut', 'validee')
            ->whereMonth('date_transaction', $lastMonth->month)
            ->whereYear('date_transaction', $lastMonth->year)
            ->whereNull('deleted_at')
            ->sum('montant') ?? 0;

        // Calcul des pourcentages
        $membersGrowth = $lastMonthMembers > 0
            ? (($currentMembers - $lastMonthMembers) / $lastMonthMembers) * 100
            : 0;

        $offeringGrowth = $lastMonthOffrandes > 0
            ? (($currentOffrandes - $lastMonthOffrandes) / $lastMonthOffrandes) * 100
            : 0;

        return [
            'members_growth' => round($membersGrowth, 1),
            'offering_growth' => round($offeringGrowth, 1),
            'attendance_rate' => $this->calculateAttendanceRate(),
            'program_completion' => $this->calculateProgramCompletion(),
            'project_completion' => $this->calculateProjectCompletion()
        ];
    }

    /**
     * Actions requises (alertes et notifications)
     */
    private function getActionsRequired()
    {
        $actions = [];

        // Réunions nécessitant une préparation
        $unpreparedMeetings = DB::table('reunions')
            ->where('date_reunion', '<=', Carbon::now()->addDays(3))
            ->where('preparation_terminee', false)
            ->whereIn('statut', ['confirmee', 'planifie'])
            ->whereNull('deleted_at')
            ->count();

        if ($unpreparedMeetings > 0) {
            $actions[] = [
                'type' => 'warning',
                'message' => "{$unpreparedMeetings} réunion(s) nécessitent une préparation",
                'action' => 'Voir les réunions',
                'link' => route('reunions.index')
            ];
        }

        // Projets en retard
        $delayedProjects = DB::table('projets')
            ->where('date_fin_prevue', '<', Carbon::now())
            ->where('statut', 'en_cours')
            ->whereNull('deleted_at')
            ->count();

        if ($delayedProjects > 0) {
            $actions[] = [
                'type' => 'danger',
                'message' => "{$delayedProjects} projet(s) en retard",
                'action' => 'Voir les projets',
                'link' => route('projets.index')
            ];
        }

        // Annonces expirant bientôt
        $expiringAnnouncements = DB::table('annonces')
            ->where('expire_le', '<=', Carbon::now()->addDays(2))
            ->where('statut', 'publiee')
            ->whereNull('deleted_at')
            ->count();

        if ($expiringAnnouncements > 0) {
            $actions[] = [
                'type' => 'info',
                'message' => "{$expiringAnnouncements} annonce(s) expirent bientôt",
                'action' => 'Voir les annonces',
                'link' => route('annonces.index')
            ];
        }

        return $actions;
    }

    /**
     * Calcule le taux de présence moyen
     */
    private function calculateAttendanceRate()
    {
        $totalMembers = DB::table('users')
            ->where('actif', true)
            ->where('statut_membre', 'actif')
            ->whereNull('deleted_at')
            ->count();

        if ($totalMembers == 0) return 0;

        $averageAttendance = DB::table('cultes')
            ->where('statut', 'termine')
            ->whereMonth('date_culte', Carbon::now()->month)
            ->whereYear('date_culte', Carbon::now()->year)
            ->whereNull('deleted_at')
            ->avg('nombre_participants') ?? 0;

        return $totalMembers > 0 ? round(($averageAttendance / $totalMembers) * 100, 1) : 0;
    }

    /**
     * Calcule le taux de completion des programmes
     */
    private function calculateProgramCompletion()
    {
        $totalPrograms = DB::table('programmes')
            ->whereIn('statut', ['actif', 'termine'])
            ->whereNull('deleted_at')
            ->count();

        if ($totalPrograms == 0) return 0;

        $completedPrograms = DB::table('programmes')
            ->where('statut', 'termine')
            ->whereNull('deleted_at')
            ->count();

        return round(($completedPrograms / $totalPrograms) * 100, 1);
    }

    /**
     * Calcule le taux de completion des projets
     */
    private function calculateProjectCompletion()
    {
        $averageCompletion = DB::table('projets')
            ->where('statut', 'en_cours')
            ->whereNull('deleted_at')
            ->avg('pourcentage_completion') ?? 0;

        return round($averageCompletion, 1);
    }

    /**
     * Récupère les données pour les graphiques AJAX
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'offerings');

        switch ($type) {
            case 'offerings':
                return response()->json($this->getOfferingChartData());

            case 'attendance':
                return response()->json($this->getAttendanceChartData());

            case 'growth':
                return response()->json($this->getMemberGrowthData());

            case 'projects':
                return response()->json($this->getProjectsChartData());

            default:
                return response()->json([]);
        }
    }

    /**
     * Données pour le graphique de présence
     */
    private function getAttendanceChartData()
    {
        return DB::table('cultes')
            ->select(
                'date_culte as date',
                'nombre_participants as attendance'
            )
            ->where('statut', 'termine')
            ->where('date_culte', '>=', Carbon::now()->subDays(30))
            ->whereNull('deleted_at')
            ->orderBy('date_culte')
            ->get();
    }

    /**
     * Données pour la croissance des membres
     */
    private function getMemberGrowthData()
    {
        return DB::table('users')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as new_members')
            )
            ->where('actif', true)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->whereNull('deleted_at')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    /**
     * Données pour les projets
     */
    private function getProjectsChartData()
    {
        return DB::table('projets')
            ->select(
                'statut',
                DB::raw('COUNT(*) as count')
            )
            ->whereNull('deleted_at')
            ->groupBy('statut')
            ->get();
    }
}
