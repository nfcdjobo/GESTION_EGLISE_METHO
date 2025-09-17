<?php

namespace App\Http\Controllers\Private\Web;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\PermissionService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
     protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;

        // Appliquer les middlewares de permissions
        $this->middleware('auth');
        $this->middleware('user.status');
        $this->middleware('permission:users.read')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.update')->only(['edit', 'update']);
        $this->middleware('permission:users.delete')->only(['destroy']);
        $this->middleware('permission:users.export')->only(['export']);
        $this->middleware('permission:users.import')->only(['import', 'processImport']);
        $this->middleware('permission:users.validate')->only(['validate']);
        $this->middleware('permission:users.archive')->only(['archive']);
        $this->middleware('permission:users.restore')->only(['restore']);
    }

    /**
     * Afficher le tableau de bord principal
     */
    public function index(Request $request)
    {

        try {
            $dashboardData = [
                'statistiques_generales' => $this->getStatistiquesGenerales(),
                'activites_recentes' => $this->getActivitesRecentes(),
                'finances' => $this->getStatistiquesFinancieres(),
                'evenements_a_venir' => $this->getEvenementsAVenir(),
                'cultes_recents' => $this->getCultesRecents(),
                'membres_statistiques' => $this->getMembresStatistiques(),
                'annonces_importantes' => $this->getAnnoncesImportantes(),
                'notifications' => $this->getNotifications(),
                'multimedia_recents' => $this->getMultimediaRecents(),
                'performances_mensuelles' => $this->getPerformancesMensuelles()
            ];

             if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $dashboardData
                ]);
            }

            return view('components.private.index', $dashboardData);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement du dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistiques générales de l'église
     */
    private function getStatistiquesGenerales()
    {
        return [
            // Membres
            'total_membres' => DB::table('users')
                ->whereNull('deleted_at')
                ->where('actif', true)
                ->count(),

            'nouveaux_membres_mois' => DB::table('users')
                ->whereNull('deleted_at')
                ->where('actif', true)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),

            'membres_actifs' => DB::table('users')
                ->whereNull('deleted_at')
                ->where('actif', true)
                ->where('statut_membre', 'actif')
                ->count(),

            // Cultes
            'total_cultes_mois' => DB::table('cultes')
                ->whereNull('deleted_at')
                ->whereMonth('date_culte', Carbon::now()->month)
                ->whereYear('date_culte', Carbon::now()->year)
                ->count(),

            'moyenne_participants_culte' => DB::table('cultes')
                ->whereNull('deleted_at')
                ->whereNotNull('nombre_participants')
                ->whereMonth('date_culte', Carbon::now()->month)
                ->avg('nombre_participants'),

            // Événements
            'evenements_planifies' => DB::table('events')
                ->whereNull('deleted_at')
                ->where('date_debut', '>=', Carbon::now())
                ->whereIn('statut', ['planifie', 'en_promotion', 'ouvert_inscription'])
                ->count(),

            // Finances
            'offrandes_mois' => DB::table('fonds')
                ->whereNull('deleted_at')
                ->where('statut', 'validee')
                ->whereMonth('date_transaction', Carbon::now()->month)
                ->whereYear('date_transaction', Carbon::now()->year)
                ->sum('montant'),

            // Classes
            'total_classes' => DB::table('classes')
                ->whereNull('deleted_at')
                ->count(),

            'total_inscrits_classes' => DB::table('classes')
                ->whereNull('deleted_at')
                ->sum('nombre_inscrits')
        ];
    }

    /**
     * Activités récentes
     */
    private function getActivitesRecentes()
    {
        $activites = [];

        // Cultes récents
        $cultes = DB::table('cultes')
            ->select('id', 'titre', 'date_culte', 'nombre_participants', 'created_at')
            ->whereNull('deleted_at')
            ->orderBy('date_culte', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($culte) {
                return [
                    'type' => 'culte',
                    'titre' => $culte->titre,
                    'date' => $culte->date_culte,
                    'details' => "{$culte->nombre_participants} participants",
                    'timestamp' => $culte->created_at
                ];
            });

        // Événements récents
        $events = DB::table('events')
            ->select('id', 'titre', 'date_debut', 'nombre_participants', 'created_at')
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($event) {
                return [
                    'type' => 'evenement',
                    'titre' => $event->titre,
                    'date' => $event->date_debut,
                    'details' => "Événement planifié",
                    'timestamp' => $event->created_at
                ];
            });

        // Nouveaux membres
        $nouveaux_membres = DB::table('users')
            ->select('prenom', 'nom', 'created_at')
            ->whereNull('deleted_at')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($membre) {
                return [
                    'type' => 'nouveau_membre',
                    'titre' => "Nouveau membre: {$membre->prenom} {$membre->nom}",
                    'date' => Carbon::parse($membre->created_at)->format('Y-m-d'),
                    'details' => 'Inscription récente',
                    'timestamp' => $membre->created_at
                ];
            });

        return collect($activites)
            ->merge($cultes)
            ->merge($events)
            ->merge($nouveaux_membres)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values();
    }

    /**
     * Statistiques financières
     */
    private function getStatistiquesFinancieres()
    {
        $moisActuel = Carbon::now();
        $moisPrecedent = Carbon::now()->subMonth();

        return [
            // Mois actuel
            'offrandes_mois_actuel' => DB::table('fonds')
                ->whereNull('deleted_at')
                ->where('statut', 'validee')
                ->whereMonth('date_transaction', $moisActuel->month)
                ->whereYear('date_transaction', $moisActuel->year)
                ->where('type_transaction', 'like', 'offrande_%')
                ->sum('montant'),

            'dimes_mois_actuel' => DB::table('fonds')
                ->whereNull('deleted_at')
                ->where('statut', 'validee')
                ->where('type_transaction', 'dime')
                ->whereMonth('date_transaction', $moisActuel->month)
                ->whereYear('date_transaction', $moisActuel->year)
                ->sum('montant'),

            // Mois précédent pour comparaison
            'offrandes_mois_precedent' => DB::table('fonds')
                ->whereNull('deleted_at')
                ->where('statut', 'validee')
                ->whereMonth('date_transaction', $moisPrecedent->month)
                ->whereYear('date_transaction', $moisPrecedent->year)
                ->where('type_transaction', 'like', 'offrande_%')
                ->sum('montant'),

            // Transactions en attente
            'transactions_en_attente' => DB::table('fonds')
                ->whereNull('deleted_at')
                ->where('statut', 'en_attente')
                ->count(),

            // Donateurs réguliers
            'donateurs_reguliers_count' => DB::table('donateurs_reguliers')->count(),

            // Évolution par type
            'repartition_par_type' => DB::table('fonds')
                ->select('type_transaction', DB::raw('SUM(montant) as total'))
                ->whereNull('deleted_at')
                ->where('statut', 'validee')
                ->whereMonth('date_transaction', $moisActuel->month)
                ->whereYear('date_transaction', $moisActuel->year)
                ->groupBy('type_transaction')
                ->get()
        ];
    }

    /**
     * Événements à venir
     */
    private function getEvenementsAVenir()
    {
        return DB::table('events_a_venir')
            ->select(
                'id',
                'titre',
                'date_debut',
                'heure_debut',
                'lieu_nom',
                'type_evenement',
                'nombre_inscrits',
                'places_disponibles',
                'statut_inscription',
                'jours_restants'
            )
            ->orderBy('date_debut')
            ->limit(10)
            ->get();
    }

    /**
     * Cultes récents avec statistiques
     */
    private function getCultesRecents()
    {
        return DB::table('cultes')
            ->select(
                'id',
                'titre',
                'date_culte',
                'type_culte',
                'nombre_participants',
                'nombre_conversions',
                'offrande_totale',
                'statut'
            )
            ->whereNull('deleted_at')
            ->orderBy('date_culte', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Statistiques des membres
     */
    private function getMembresStatistiques()
    {
        return [
            'par_statut' => DB::table('users')
                ->select('statut_membre', DB::raw('count(*) as total'))
                ->whereNull('deleted_at')
                ->where('actif', true)
                ->groupBy('statut_membre')
                ->get(),

            'par_tranche_age' => DB::table('users')
                ->select(
                    DB::raw("
                        CASE
                            WHEN EXTRACT(YEAR FROM AGE(COALESCE(date_naissance, CURRENT_DATE))) < 18 THEN 'Moins de 18 ans'
                            WHEN EXTRACT(YEAR FROM AGE(COALESCE(date_naissance, CURRENT_DATE))) BETWEEN 18 AND 35 THEN '18-35 ans'
                            WHEN EXTRACT(YEAR FROM AGE(COALESCE(date_naissance, CURRENT_DATE))) BETWEEN 36 AND 55 THEN '36-55 ans'
                            ELSE 'Plus de 55 ans'
                        END as tranche_age
                    "),
                    DB::raw('count(*) as total')
                )
                ->whereNull('deleted_at')
                ->where('actif', true)
                ->whereNotNull('date_naissance')
                ->groupBy('tranche_age')
                ->get(),

            'par_sexe' => DB::table('users')
                ->select('sexe', DB::raw('count(*) as total'))
                ->whereNull('deleted_at')
                ->where('actif', true)
                ->groupBy('sexe')
                ->get(),

            'anniversaires_mois' => DB::table('anniversaires_du_mois')
                ->count(),

            'nouveaux_visiteurs' => DB::table('nouveaux_visiteurs_suivi')
                ->where('date_inscription', '>=', Carbon::now()->subDays(30))
                ->count()
        ];
    }

    /**
     * Annonces importantes
     */
    private function getAnnoncesImportantes()
    {
        return DB::table('annonces_actives')
            ->select(
                'id',
                'titre',
                'contenu',
                'type_annonce',
                'niveau_priorite',
                'date_evenement',
                'lieu_evenement'
            )
            ->orderByRaw("
                CASE niveau_priorite
                    WHEN 'urgent' THEN 1
                    WHEN 'important' THEN 2
                    ELSE 3
                END
            ")
            ->orderBy('publie_le', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Notifications et alertes
     */
    private function getNotifications()
    {
        $notifications = [];

        // Vérifier les cultes nécessitant une action
        $cultesAction = DB::table('cultes')
            ->whereNull('deleted_at')
            ->where('date_culte', '<=', Carbon::now()->addDays(7))
            ->where('statut', 'planifie')
            ->count();

        if ($cultesAction > 0) {
            $notifications[] = [
                'type' => 'warning',
                'titre' => 'Cultes à confirmer',
                'message' => "{$cultesAction} culte(s) nécessitent une confirmation",
                'action_url' => route('private.cultes.index')
            ];
        }

        // Vérifier les transactions en attente
        $transactionsAttente = DB::table('fonds')
            ->whereNull('deleted_at')
            ->where('statut', 'en_attente')
            ->where('created_at', '<=', Carbon::now()->subDays(3))
            ->count();

        if ($transactionsAttente > 0) {
            $notifications[] = [
                'type' => 'info',
                'titre' => 'Transactions en attente',
                'message' => "{$transactionsAttente} transaction(s) en attente de validation",
                'action_url' => '/finances'
            ];
        }

        // Vérifier les événements bientôt complets
        $evenementsProchesComplets = DB::table('events')
            ->whereNull('deleted_at')
            ->whereNotNull('capacite_totale')
            ->whereRaw('nombre_inscrits >= (capacite_totale * 0.9)')
            ->where('date_debut', '>=', Carbon::now())
            ->count();

        if ($evenementsProchesComplets > 0) {
            $notifications[] = [
                'type' => 'success',
                'titre' => 'Événements populaires',
                'message' => "{$evenementsProchesComplets} événement(s) bientôt complets",
                'action_url' => '/evenements'
            ];
        }

        // Nouveaux visiteurs nécessitant un suivi
        $nouveauxVisiteurs = DB::table('nouveaux_visiteurs_suivi')
            ->where('demande_contact_pastoral', true)
            ->count();

        if ($nouveauxVisiteurs > 0) {
            $notifications[] = [
                'type' => 'info',
                'titre' => 'Suivi pastoral',
                'message' => "{$nouveauxVisiteurs} nouveau(x) visiteur(s) demandent un contact pastoral",
                'action_url' => '/membres'
            ];
        }

        return $notifications;
    }

    /**
     * Médias récents
     */
    private function getMultimediaRecents()
    {
        return DB::table('multimedia')
            ->select(
                'id',
                'titre',
                'type_media',
                'categorie',
                'miniature',
                'nombre_vues',
                'created_at'
            )
            ->whereNull('deleted_at')
            ->where('est_visible', true)
            ->where('statut_moderation', 'approuve')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
    }

    /**
     * Performances mensuelles
     */
    private function getPerformancesMensuelles()
    {
        $moisActuel = Carbon::now();
        $performances = [];

        // Données des 6 derniers mois
        for ($i = 5; $i >= 0; $i--) {
            $mois = $moisActuel->copy()->subMonths($i);

            $performances[] = [
                'mois' => $mois->format('M Y'),
                'cultes' => DB::table('cultes')
                    ->whereNull('deleted_at')
                    ->whereMonth('date_culte', $mois->month)
                    ->whereYear('date_culte', $mois->year)
                    ->count(),

                'participants_moyens' => (int) DB::table('cultes')
                    ->whereNull('deleted_at')
                    ->whereMonth('date_culte', $mois->month)
                    ->whereYear('date_culte', $mois->year)
                    ->avg('nombre_participants'),

                'offrandes' => (float) DB::table('fonds')
                    ->whereNull('deleted_at')
                    ->where('statut', 'validee')
                    ->whereMonth('date_transaction', $mois->month)
                    ->whereYear('date_transaction', $mois->year)
                    ->sum('montant'),

                'nouveaux_membres' => DB::table('users')
                    ->whereNull('deleted_at')
                    ->whereMonth('created_at', $mois->month)
                    ->whereYear('created_at', $mois->year)
                    ->count()
            ];
        }

        return $performances;
    }

    /**
     * Statistiques détaillées pour une période
     */
    public function getStatistiquesPeriode(Request $request)
    {
        $debut = Carbon::parse($request->input('debut', Carbon::now()->startOfMonth()));
        $fin = Carbon::parse($request->input('fin', Carbon::now()->endOfMonth()));

        return response()->json([
            'success' => true,
            'data' => [
                'periode' => [
                    'debut' => $debut->format('Y-m-d'),
                    'fin' => $fin->format('Y-m-d')
                ],
                'cultes' => $this->getStatistiquesCultesPeriode($debut, $fin),
                'finances' => $this->getStatistiquesFinancesPeriode($debut, $fin),
                'evenements' => $this->getStatistiquesEvenementsPeriode($debut, $fin),
                'membres' => $this->getStatistiquesMembresPeriode($debut, $fin)
            ]
        ]);
    }

    private function getStatistiquesCultesPeriode($debut, $fin)
    {
        return [
            'total_cultes' => DB::table('cultes')
                ->whereNull('deleted_at')
                ->whereBetween('date_culte', [$debut, $fin])
                ->count(),

            'total_participants' => DB::table('cultes')
                ->whereNull('deleted_at')
                ->whereBetween('date_culte', [$debut, $fin])
                ->sum('nombre_participants'),

            'total_conversions' => DB::table('cultes')
                ->whereNull('deleted_at')
                ->whereBetween('date_culte', [$debut, $fin])
                ->sum('nombre_conversions')
        ];
    }

    private function getStatistiquesFinancesPeriode($debut, $fin)
    {
        return [
            'total_offrandes' => DB::table('fonds')
                ->whereNull('deleted_at')
                ->where('statut', 'validee')
                ->where('type_transaction', 'like', 'offrande_%')
                ->whereBetween('date_transaction', [$debut, $fin])
                ->sum('montant'),

            'total_dimes' => DB::table('fonds')
                ->whereNull('deleted_at')
                ->where('statut', 'validee')
                ->where('type_transaction', 'dime')
                ->whereBetween('date_transaction', [$debut, $fin])
                ->sum('montant'),

            'nombre_transactions' => DB::table('fonds')
                ->whereNull('deleted_at')
                ->where('statut', 'validee')
                ->whereBetween('date_transaction', [$debut, $fin])
                ->count()
        ];
    }

    private function getStatistiquesEvenementsPeriode($debut, $fin)
    {
        return [
            'total_evenements' => DB::table('events')
                ->whereNull('deleted_at')
                ->whereBetween('date_debut', [$debut, $fin])
                ->count(),

            'total_participants' => DB::table('events')
                ->whereNull('deleted_at')
                ->whereBetween('date_debut', [$debut, $fin])
                ->sum('nombre_participants')
        ];
    }

    private function getStatistiquesMembresPeriode($debut, $fin)
    {
        return [
            'nouveaux_membres' => DB::table('users')
                ->whereNull('deleted_at')
                ->whereBetween('created_at', [$debut, $fin])
                ->count(),

            'nouvelles_inscriptions_culte' => DB::table('participant_cultes pc')
                ->join('cultes c', 'pc.culte_id', '=', 'c.id')
                ->whereNull('pc.deleted_at')
                ->whereNull('c.deleted_at')
                ->whereBetween('c.date_culte', [$debut, $fin])
                ->where('pc.premiere_visite', true)
                ->count()
        ];
    }
}
