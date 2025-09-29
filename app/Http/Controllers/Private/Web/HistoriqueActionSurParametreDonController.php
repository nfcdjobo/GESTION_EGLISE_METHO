<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\User;
use Illuminate\View\View;
use App\Models\ParametreDon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\HistoriqueActionSurParametreDon;

class HistoriqueActionSurParametreDonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = HistoriqueActionSurParametreDon::with(['parametreDon', 'effectuerPar']);

        // Filtres
        if ($request->filled('parametre_don_id')) {
            $query->parParametreDon($request->parametre_don_id);
        }

        if ($request->filled('action')) {
            $query->parAction($request->action);
        }

        if ($request->filled('effectuer_par')) {
            $query->parUtilisateur($request->effectuer_par);
        }

        if ($request->filled('periode')) {
            $periode = $request->periode;
            switch ($periode) {
                case 'aujourd_hui':
                    $query->whereDate('created_at', today());
                    break;
                case 'cette_semaine':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'ce_mois':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'cette_annee':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        if ($request->filled(['date_debut', 'date_fin'])) {
            $query->whereBetween('created_at', [
                $request->date_debut . ' 00:00:00',
                $request->date_fin . ' 23:59:59'
            ]);
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('parametreDon', function($subQ) use ($search) {
                    $subQ->where('operateur', 'like', "%{$search}%")
                         ->orWhere('numero_compte', 'like', "%{$search}%");
                })->orWhereHas('effectuerPar', function($subQ) use ($search) {
                    $subQ->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                });
            });
        }

        $historiques = $query->recentsEnPremier()->paginate(25);

        // Statistiques pour le dashboard
        $statistiques = null;
        if ($request->get('with_stats', false)) {
            $statistiques = [
                'total_actions' => HistoriqueActionSurParametreDon::count(),
                'actions_aujourd_hui' => HistoriqueActionSurParametreDon::whereDate('created_at', today())->count(),
                'actions_ce_mois' => HistoriqueActionSurParametreDon::whereMonth('created_at', now()->month)
                                                                   ->whereYear('created_at', now()->year)
                                                                   ->count(),
                'par_action' => HistoriqueActionSurParametreDon::selectRaw('action, COUNT(*) as total')
                                                              ->groupBy('action')
                                                              ->orderBy('total', 'desc')
                                                              ->get(),
                'utilisateurs_actifs' => HistoriqueActionSurParametreDon::with('effectuerPar')
                                                                       ->selectRaw('effectuer_par, COUNT(*) as total')
                                                                       ->groupBy('effectuer_par')
                                                                       ->orderBy('total', 'desc')
                                                                       ->limit(10)
                                                                       ->get(),
            ];
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $historiques->items(),
                'statistiques' => $statistiques,
                'pagination' => [
                    'current_page' => $historiques->currentPage(),
                    'last_page' => $historiques->lastPage(),
                    'per_page' => $historiques->perPage(),
                    'total' => $historiques->total(),
                    'has_more' => $historiques->hasMorePages()
                ]
            ]);
        }

        return view('components.private.historiques.index', compact('historiques', 'statistiques'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, HistoriqueActionSurParametreDon $historique)
    {
        $historique->load(['parametreDon', 'effectuerPar']);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $historique
            ]);
        }

        return view('components.private.historiques.show', compact('historique'));
    }

    /**
     * Afficher l'historique d'un paramètre spécifique.
     */
    public function historiqueParametre(Request $request, ParametreDon $parametreDon)
    {
        $query = $parametreDon->historiques()->with(['effectuerPar']);

        // Filtres spécifiques
        if ($request->filled('action')) {
            $query->parAction($request->action);
        }

        if ($request->filled('effectuer_par')) {
            $query->parUtilisateur($request->effectuer_par);
        }

        if ($request->filled('periode')) {
            $jours = match($request->periode) {
                '7_jours' => 7,
                '30_jours' => 30,
                '90_jours' => 90,
                default => null
            };

            if ($jours) {
                $query->where('created_at', '>=', now()->subDays($jours));
            }
        }

        $historiques = $query->recentsEnPremier()->paginate(20);

        // Résumé des actions
        $resume = [
            'total_actions' => $parametreDon->historiques()->count(),
            'derniere_action' => $parametreDon->historiques()->latest()->first(),
            'premiere_action' => $parametreDon->historiques()->oldest()->first(),
            'par_action' => $parametreDon->historiques()
                                       ->selectRaw('action, COUNT(*) as total')
                                       ->groupBy('action')
                                       ->get()
                                       ->pluck('total', 'action')
                                       ->toArray(),
            'par_utilisateur' => $parametreDon->historiques()
                                             ->with('effectuerPar')
                                             ->selectRaw('effectuer_par, COUNT(*) as total')
                                             ->groupBy('effectuer_par')
                                             ->orderBy('total', 'desc')
                                             ->get()
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $historiques->items(),
                'resume' => $resume,
                'parametre' => $parametreDon,
                'pagination' => [
                    'current_page' => $historiques->currentPage(),
                    'last_page' => $historiques->lastPage(),
                    'per_page' => $historiques->perPage(),
                    'total' => $historiques->total(),
                    'has_more' => $historiques->hasMorePages()
                ]
            ]);
        }

        return view('components.private.historiques.parametre', compact('historiques', 'parametreDon', 'resume'));
    }

    /**
     * Afficher l'historique des actions d'un utilisateur.
     */
    public function historiqueUtilisateur(Request $request, User $user)
    {
        $query = HistoriqueActionSurParametreDon::with(['parametreDon'])
                                               ->parUtilisateur($user->id);

        // Filtres
        if ($request->filled('action')) {
            $query->parAction($request->action);
        }

        if ($request->filled('parametre_don_id')) {
            $query->parParametreDon($request->parametre_don_id);
        }

        if ($request->filled('periode')) {
            $periode = $request->periode;
            switch ($periode) {
                case 'aujourd_hui':
                    $query->whereDate('created_at', today());
                    break;
                case 'cette_semaine':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'ce_mois':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'cette_annee':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }

        $historiques = $query->recentsEnPremier()->paginate(20);

        // Statistiques de l'utilisateur
        $statistiques = [
            'total_actions' => HistoriqueActionSurParametreDon::parUtilisateur($user->id)->count(),
            'actions_ce_mois' => HistoriqueActionSurParametreDon::parUtilisateur($user->id)
                                                               ->whereMonth('created_at', now()->month)
                                                               ->whereYear('created_at', now()->year)
                                                               ->count(),
            'derniere_action' => HistoriqueActionSurParametreDon::parUtilisateur($user->id)
                                                               ->with('parametreDon')
                                                               ->latest()
                                                               ->first(),
            'par_action' => HistoriqueActionSurParametreDon::parUtilisateur($user->id)
                                                          ->selectRaw('action, COUNT(*) as total')
                                                          ->groupBy('action')
                                                          ->orderBy('total', 'desc')
                                                          ->get(),
            'parametres_modifies' => HistoriqueActionSurParametreDon::parUtilisateur($user->id)
                                                                   ->with('parametreDon')
                                                                   ->distinct('parametre_don_id')
                                                                   ->count('parametre_don_id'),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $historiques->items(),
                'statistiques' => $statistiques,
                'utilisateur' => $user,
                'pagination' => [
                    'current_page' => $historiques->currentPage(),
                    'last_page' => $historiques->lastPage(),
                    'per_page' => $historiques->perPage(),
                    'total' => $historiques->total(),
                    'has_more' => $historiques->hasMorePages()
                ]
            ]);
        }

        return view('components.private.historiques.utilisateur', compact('historiques', 'user', 'statistiques'));
    }

    /**
     * Exporter l'historique en CSV.
     */
    public function exporterCsv(Request $request)
    {
        $query = HistoriqueActionSurParametreDon::with(['parametreDon', 'effectuerPar']);

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('parametre_don_id')) {
            $query->parParametreDon($request->parametre_don_id);
        }

        if ($request->filled('action')) {
            $query->parAction($request->action);
        }

        if ($request->filled('effectuer_par')) {
            $query->parUtilisateur($request->effectuer_par);
        }

        if ($request->filled(['date_debut', 'date_fin'])) {
            $query->whereBetween('created_at', [
                $request->date_debut . ' 00:00:00',
                $request->date_fin . ' 23:59:59'
            ]);
        }

        $historiques = $query->recentsEnPremier()->get();

        $filename = 'historique_actions_' . now()->format('Y_m_d_H_i_s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($historiques) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Date/Heure',
                'Action',
                'Opérateur',
                'Type de paramètre',
                'Numéro de compte',
                'Effectué par',
                'Email utilisateur',
                'Informations supplémentaires',
            ], ';');

            // Données
            foreach ($historiques as $historique) {
                $infos = is_array($historique->infos)
                    ? json_encode($historique->infos, JSON_UNESCAPED_UNICODE)
                    : '';

                fputcsv($file, [
                    $historique->id,
                    $historique->created_at->format('d/m/Y H:i:s'),
                    $historique->action_libelle,
                    $historique->parametreDon->operateur ?? 'N/A',
                    $historique->parametreDon->type_libelle ?? 'N/A',
                    $historique->parametreDon->numero_compte ?? 'N/A',
                    $historique->effectuerPar->name ?? 'Utilisateur supprimé',
                    $historique->effectuerPar->email ?? 'N/A',
                    $infos,
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Statistiques détaillées de l'historique.
     */
    public function statistiques(Request $request)
    {
        $periode = $request->get('periode', 'ce_mois');

        $query = HistoriqueActionSurParametreDon::query();

        // Appliquer la période
        switch ($periode) {
            case 'aujourd_hui':
                $query->whereDate('created_at', today());
                break;
            case 'cette_semaine':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'ce_mois':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'cette_annee':
                $query->whereYear('created_at', now()->year);
                break;
            case 'personnalisee':
                if ($request->filled(['date_debut', 'date_fin'])) {
                    $query->whereBetween('created_at', [
                        $request->date_debut . ' 00:00:00',
                        $request->date_fin . ' 23:59:59'
                    ]);
                }
                break;
        }

        $statistiques = [
            'resume' => [
                'total_actions' => $query->count(),
                'utilisateurs_actifs' => $query->distinct('effectuer_par')->count('effectuer_par'),
                'parametres_modifies' => $query->distinct('parametre_don_id')->count('parametre_don_id'),
            ],
            'par_action' => $query->selectRaw('action, COUNT(*) as total')
                                 ->groupBy('action')
                                 ->orderBy('total', 'desc')
                                 ->get()
                                 ->map(function($item) {
                                     $item->action_libelle = HistoriqueActionSurParametreDon::ACTIONS[$item->action] ?? $item->action;
                                     return $item;
                                 }),
            'par_utilisateur' => $query->with('effectuerPar')
                                      ->selectRaw('effectuer_par, COUNT(*) as total')
                                      ->groupBy('effectuer_par')
                                      ->orderBy('total', 'desc')
                                      ->limit(10)
                                      ->get(),
            'evolution_quotidienne' => $query->selectRaw('
                                            DATE(created_at) as date,
                                            COUNT(*) as total_actions,
                                            COUNT(DISTINCT effectuer_par) as utilisateurs_actifs,
                                            COUNT(DISTINCT parametre_don_id) as parametres_modifies
                                        ')
                                        ->where('created_at', '>=', now()->subDays(30))
                                        ->groupBy('date')
                                        ->orderBy('date', 'desc')
                                        ->get(),
            'par_type_parametre' => $query->join('parametres_dons', 'historiques_actions_sur_parametres_dons.parametre_don_id', '=', 'parametres_dons.id')
                                         ->selectRaw('parametres_dons.type, COUNT(historiques_actions_sur_parametres_dons.id) as total')
                                         ->groupBy('parametres_dons.type')
                                         ->orderBy('total', 'desc')
                                         ->get()
                                         ->map(function($item) {
                                             $typeLabels = [
                                                 ParametreDon::TYPE_VIREMENT_BANCAIRE => 'Virement Bancaire',
                                                 ParametreDon::TYPE_CARTE_BANCAIRE => 'Carte Bancaire',
                                                 ParametreDon::TYPE_MOBILE_MONEY => 'Mobile Money',
                                             ];
                                             $item->type_libelle = $typeLabels[$item->type] ?? $item->type;
                                             return $item;
                                         }),
            'activite_horaire' => $query->selectRaw('
                                        HOUR(created_at) as heure,
                                        COUNT(*) as total_actions
                                    ')
                                    ->groupBy('heure')
                                    ->orderBy('heure')
                                    ->get(),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $statistiques,
                'periode' => $periode
            ]);
        }

        return view('components.private.historiques.statistiques', compact('statistiques', 'periode'));
    }

    /**
     * Comparer les actions entre deux périodes.
     */
    public function comparerPeriodes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode1_debut' => 'required|date',
            'periode1_fin' => 'required|date|after_or_equal:periode1_debut',
            'periode2_debut' => 'required|date',
            'periode2_fin' => 'required|date|after_or_equal:periode2_debut',
        ], [
            'periode1_debut.required' => 'La date de début de la première période est obligatoire',
            'periode1_fin.required' => 'La date de fin de la première période est obligatoire',
            'periode1_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début',
            'periode2_debut.required' => 'La date de début de la deuxième période est obligatoire',
            'periode2_fin.required' => 'La date de fin de la deuxième période est obligatoire',
            'periode2_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        // Période 1
        $periode1 = HistoriqueActionSurParametreDon::whereBetween('created_at', [
            $request->periode1_debut . ' 00:00:00',
            $request->periode1_fin . ' 23:59:59'
        ]);

        // Période 2
        $periode2 = HistoriqueActionSurParametreDon::whereBetween('created_at', [
            $request->periode2_debut . ' 00:00:00',
            $request->periode2_fin . ' 23:59:59'
        ]);

        $comparaison = [
            'periode1' => [
                'debut' => $request->periode1_debut,
                'fin' => $request->periode1_fin,
                'total_actions' => $periode1->count(),
                'utilisateurs_actifs' => $periode1->distinct('effectuer_par')->count(),
                'parametres_modifies' => $periode1->distinct('parametre_don_id')->count(),
                'par_action' => $periode1->selectRaw('action, COUNT(*) as total')
                                       ->groupBy('action')
                                       ->orderBy('total', 'desc')
                                       ->get()
                                       ->pluck('total', 'action')
                                       ->toArray(),
            ],
            'periode2' => [
                'debut' => $request->periode2_debut,
                'fin' => $request->periode2_fin,
                'total_actions' => $periode2->count(),
                'utilisateurs_actifs' => $periode2->distinct('effectuer_par')->count(),
                'parametres_modifies' => $periode2->distinct('parametre_don_id')->count(),
                'par_action' => $periode2->selectRaw('action, COUNT(*) as total')
                                       ->groupBy('action')
                                       ->orderBy('total', 'desc')
                                       ->get()
                                       ->pluck('total', 'action')
                                       ->toArray(),
            ],
        ];

        // Calcul des différences
        $comparaison['differences'] = [
            'total_actions' => $comparaison['periode2']['total_actions'] - $comparaison['periode1']['total_actions'],
            'utilisateurs_actifs' => $comparaison['periode2']['utilisateurs_actifs'] - $comparaison['periode1']['utilisateurs_actifs'],
            'parametres_modifies' => $comparaison['periode2']['parametres_modifies'] - $comparaison['periode1']['parametres_modifies'],
            'pourcentage_evolution' => $comparaison['periode1']['total_actions'] > 0
                ? round((($comparaison['periode2']['total_actions'] - $comparaison['periode1']['total_actions']) / $comparaison['periode1']['total_actions']) * 100, 2)
                : ($comparaison['periode2']['total_actions'] > 0 ? 100 : 0),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $comparaison
            ]);
        }

        return view('components.private.historiques.comparaison', compact('comparaison'));
    }

    /**
     * Purger les anciens historiques.
     */
    public function purger(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'avant_date' => 'required|date|before:today',
            'confirmer' => 'required|boolean|accepted',
        ], [
            'avant_date.required' => 'La date limite est obligatoire',
            'avant_date.date' => 'La date limite doit être une date valide',
            'avant_date.before' => 'La date limite doit être antérieure à aujourd\'hui',
            'confirmer.required' => 'Vous devez confirmer la purge',
            'confirmer.accepted' => 'Vous devez confirmer la purge',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            $nombreSupprime = HistoriqueActionSurParametreDon::where('created_at', '<', $request->avant_date . ' 23:59:59')
                                                            ->delete();

            $message = "Purge effectuée avec succès. {$nombreSupprime} enregistrement(s) supprimé(s).";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'nombre_supprime' => $nombreSupprime
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la purge: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                           ->with('error', 'Erreur lors de la purge');
        }
    }

    /**
     * Restaurer un historique supprimé (si soft delete était activé).
     * Note: Cette méthode est préparée pour une éventuelle activation du soft delete.
     */
    public function restaurer(Request $request, $id)
    {
        // Cette méthode pourrait être utilisée si SoftDeletes était activé sur le modèle
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'La restauration n\'est pas disponible pour les historiques'
            ], 404);
        }

        return redirect()->back()
                       ->with('error', 'La restauration n\'est pas disponible pour les historiques');
    }

    /**
     * Recherche dans les informations JSON des historiques.
     */
    public function rechercherDansInfos(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'terme' => 'required|string|min:3|max:100',
            'action' => 'nullable|in:' . implode(',', array_keys(HistoriqueActionSurParametreDon::ACTIONS)),
        ], [
            'terme.required' => 'Le terme de recherche est obligatoire',
            'terme.min' => 'Le terme de recherche doit contenir au moins 3 caractères',
            'terme.max' => 'Le terme de recherche ne peut pas dépasser 100 caractères',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $query = HistoriqueActionSurParametreDon::with(['parametreDon', 'effectuerPar'])
                                               ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(infos, ')) LIKE ?", ['%' . $request->terme . '%']);

        if ($request->filled('action')) {
            $query->parAction($request->action);
        }

        $resultats = $query->recentsEnPremier()->paginate(15);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $resultats->items(),
                'terme_recherche' => $request->terme,
                'pagination' => [
                    'current_page' => $resultats->currentPage(),
                    'last_page' => $resultats->lastPage(),
                    'per_page' => $resultats->perPage(),
                    'total' => $resultats->total(),
                    'has_more' => $resultats->hasMorePages()
                ]
            ]);
        }

        return view('components.private.historiques.recherche-infos', compact('resultats'));
    }
}
