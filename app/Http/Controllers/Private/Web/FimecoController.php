<?php

// =================================================================
// app/Http/Controllers/FimecoController.php

namespace App\Http\Controllers\Private\Web;

use App\Models\User;
use App\Models\Fimeco;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Services\FimecoService;
use Illuminate\Http\JsonResponse;
use App\Models\SubscriptionPayment;
use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use App\Http\Resources\FimecoResource;
use App\Http\Requests\CreerFimecoRequest;

class FimecoController extends Controller
{
    public function __construct(private FimecoService $fimecoService, private SubscriptionService $subscriptionService)
    {
        $this->middleware('auth');
        $this->middleware('can:manage,App\Models\Fimeco')->except(['index', 'show']);
        // $this->middleware('permission:fimecos.read')->only(['index', 'show', 'statistics', 'search']);
        // $this->middleware('permission:fimecos.create')->only(['create', 'store']);
        // $this->middleware('permission:fimecos.update')->only(['edit', 'update', 'changerStatut', 'updateVisibility']);
        // $this->middleware('permission:fimecos.delete')->only(['destroy']);
        // $this->middleware('permission:fimecos.export')->only(['export']);
    }

    public function index(Request $request)
    {
        $query = Fimeco::with(['responsable', 'subscriptions']);

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('en_cours')) {
            $query->enCours();
        }

        if ($request->filled('terminee')) {
            $query->terminee();
        }

        $fimecos = $query->orderByDesc('created_at')->paginate(10);

        $fimeco = FimecoResource::collection($fimecos);
        $meta = [
            'current_page' => $fimecos->currentPage(),
            'last_page' => $fimecos->lastPage(),
            'per_page' => $fimecos->perPage(),
            'total' => $fimecos->total()
        ];
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $fimeco,
                'meta' => $meta
            ]);
        }

        return view('components.private.fimecos.index', compact('fimeco', 'meta'));
    }

    /**
     * Afficher le formulaire de création d'une nouvelle FIMECO
     */
    public function create()
    {
        $this->authorize('create', Fimeco::class);

        // Récupérer les utilisateurs qui peuvent être responsables
        $responsables = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'responsable_fimeco']);
        })
            ->orderBy('nom')
            ->get();

        if (request()->expectsJson()) {
            return response()->json([
                'responsables' => $responsables
            ]);
        }

        return view('components.private.fimecos.create', compact('responsables'));
    }

    /**
     * Afficher le formulaire d'édition d'une FIMECO
     */
    public function edit(Request $request, string $id)
    {
        $fimeco = Fimeco::findOrFail($id);
        $this->authorize('update', $fimeco);

        // Vérifier que la FIMECO peut encore être modifiée
        if ($fimeco->statut === 'cloturee') {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Une FIMECO clôturée ne peut pas être modifiée'
                ], 403);
            }

            return redirect()->back()->withErrors('Une FIMECO clôturée ne peut pas être modifiée');
        }

        $responsables = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['admin', 'responsable_fimeco']);
        })
            ->orderBy('nom')
            ->get();

        $fimeco = FimecoResource::make($fimeco);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $fimeco,
                'responsables' => $responsables
            ]);
        }

        return view('components.private.fimecos.edit', compact('fimeco', 'responsables'));
    }


    public function show(Request $request, string $id)
    {
        $fimeco = Fimeco::with(['responsable', 'subscriptions.souscripteur', 'subscriptions.paymentsValides'])
            ->findOrFail($id);
        $fimeco = FimecoResource::make($fimeco);
        $statistiques = $this->fimecoService->obtenirStatistiquesFimeco($id);
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $fimeco,
                'statistiques' => $statistiques
            ]);
        }


        return view('components.private.fimecos.show', compact('fimeco', 'statistiques'));
    }

    public function store(CreerFimecoRequest $request)
    {
        // dd($request->all());
        $fimeco = $this->fimecoService->creerFimeco($request->validated());

        $fimeco = FimecoResource::make($fimeco);
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $fimeco,
                'message' => 'FIMECO créée avec succès'
            ], 201);
        }
        return redirect()->route('private.fimecos.show', $fimeco);
    }


    public function update(CreerFimecoRequest $request, string $id)
    {
        $fimeco = Fimeco::findOrFail($id);
        $fimeco->update($request->validated());

        $fimeco = FimecoResource::make($fimeco->fresh());
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $fimeco,
                'message' => 'FIMECO mise à jour avec succès'
            ]);
        }

        return redirect()->route('private.fimecos.show', $fimeco);
    }

    public function cloturer(Request $request, string $id)
    {
        try {
            $this->fimecoService->cloturerFimeco($id, $request->commentaire);
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'FIMECO clôturée avec succès'
                ]);
            }
            return redirect()->back()->with('success', 'FIMECO clôturée avec succès');
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }


    public function statistiques(Request $request, string $fimeco)
    {
        $statistiques = $this->fimecoService->obtenirStatistiquesFimeco($fimeco);
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $statistiques
            ]);
        }

        return view('components.private.fimecos.statistiques', compact('statistiques'));

    }


    public function fimeco(Request $request)
    {

        /**
         * @var User $user
         */
        $user = auth()->user();

        // FIMECO active
        $fimecoActive = $this->fimecoService->obtenirFimecoActive();

        // Statistiques utilisateur
        $statsUtilisateur = $this->subscriptionService->calculerStatistiquesUtilisateur($user->id);

        // Souscriptions récentes de l'utilisateur
        $souscriptionsRecentes = Subscription::where('souscripteur_id', $user->id)
            ->with(['fimeco', 'paymentsValides'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Paiements récents de l'utilisateur
        $paiementsRecents = SubscriptionPayment::whereHas('subscription')
            ->with(['subscription.fimeco'])
            ->where('statut', 'valide')
            ->orderByDesc('date_paiement')
            ->limit(5)
            ->get();


        // Si l'utilisateur peut gérer les FIMECO, ajouter des stats globales
        $statsGlobales = null;
        if ($user->can('manage', Fimeco::class)) {
            $statsGlobales = $this->obtenirStatistiquesGlobales();
        }

        $fimeco_active = $fimecoActive ? [
            'id' => $fimecoActive->id,
            'nom' => $fimecoActive->nom,
            'total_paye' => $fimecoActive->total_paye,
            'pourcentage_realisation' => $fimecoActive->pourcentage_realisation,
            'jours_restants' => now()->diffInDays($fimecoActive->fin),
            'peut_souscrire' => !$souscriptionsRecentes->where('fimeco_id', $fimecoActive->id)->first()
        ] : null;


        $statistiques_utilisateur = $statsUtilisateur;


        $souscriptions_recentes = $souscriptionsRecentes->map(function ($subscription) {
            return [
                'id' => $subscription->id,
                'fimeco_nom' => $subscription->fimeco->nom,
                'montant_souscrit' => $subscription->montant_souscrit,
                'montant_paye' => $subscription->montant_paye,
                'reste_a_payer' => $subscription->reste_a_payer,
                'statut' => $subscription->statut,
                'date_souscription' => $subscription->date_souscription->format('d/m/Y')
            ];
        });


        $paiements_recents = $paiementsRecents->map(function ($payment) {
            return [
                'id' => $payment->id,
                'fimeco_nom' => $payment->subscription->fimeco->nom,
                'montant' => $payment->montant,
                'type_paiement' => config('fimeco.types_paiement_autorises')[$payment->type_paiement],
                'date_paiement' => $payment->date_paiement->format('d/m/Y')
            ];
        });


        $statistiques_globales = $statsGlobales;
        if ($request->expectsJson()) {
            return response()->json([
                'fimeco_active' => $fimeco_active,
                'statistiques_utilisateur' => $statistiques_utilisateur,
                'souscriptions_recentes' => $souscriptions_recentes,
                'paiements_recents' => $paiements_recents,
                'statistiques_globales' => $statistiques_globales
            ]);
        }

        return view('components.private.fimecos.dashbaord', compact('fimeco_active', 'statistiques_utilisateur', 'souscriptions_recentes', 'paiements_recents', 'statistiques_globales'));
    }

    private function obtenirStatistiquesGlobales(): array
    {
        $fimecoActive = Fimeco::enCours()->first();

        if (!$fimecoActive) {
            return [];
        }

        // Paiements en attente
        $paiementsEnAttente = SubscriptionPayment::enAttente()
            ->whereHas('subscription', function ($query) use ($fimecoActive) {
                $query->where('fimeco_id', $fimecoActive->id);
            })
            ->count();

        // Souscriptions en retard
        $souscriptionsEnRetard = Subscription::enRetard()
            ->where('fimeco_id', $fimecoActive->id)
            ->count();

        // Évolution des paiements (30 derniers jours)
        $evolutionPaiements = SubscriptionPayment::where('statut', 'valide')
            ->whereHas('subscription', function ($query) use ($fimecoActive) {
                $query->where('fimeco_id', $fimecoActive->id);
            })
            ->where('date_paiement', '>=', now()->subDays(30))
            ->selectRaw('DATE(date_paiement) as date, SUM(montant) as total, COUNT(*) as nombre')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'fimeco_active' => $fimecoActive->calculerStatistiques(),
            'paiements_en_attente' => $paiementsEnAttente,
            'souscriptions_en_retard' => $souscriptionsEnRetard,
            'evolution_paiements' => $evolutionPaiements
        ];
    }
}







