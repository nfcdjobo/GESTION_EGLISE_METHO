<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\User;
use App\Models\Fimeco;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use App\Http\Resources\SubscriptionResource;
use App\Http\Requests\CreerSouscriptionRequest;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // Validation des paramètres de recherche
        $request->validate([
            'search' => 'nullable|string|max:255',
            'fimeco_id' => 'nullable|exists:fimecos,id',
            'statut' => 'nullable|in:active,partiellement_payee,completement_payee,annulee,suspendue',
            'montant_min' => 'nullable|numeric|min:0',
            'montant_max' => 'nullable|numeric|min:0|gte:montant_min',
            'date_souscription' => 'nullable|date',
            'per_page' => 'nullable|integer|min:5|max:100'
        ]);

        $query = Subscription::with(['souscripteur', 'fimeco', 'paymentsValides']);

        // Recherche générale (nom du souscripteur, ID de souscription)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function (Builder $q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                  ->orWhereHas('souscripteur', function (Builder $subQuery) use ($searchTerm) {
                      $subQuery->where('nom', 'like', "%{$searchTerm}%")
                               ->orWhere('prenom', 'like', "%{$searchTerm}%")
                               ->orWhere('telephone_1', 'like', "%{$searchTerm}%")
                               ->orWhere('email', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('fimeco', function (Builder $subQuery) use ($searchTerm) {
                      $subQuery->where('nom', 'like', "%{$searchTerm}%");
                  });
            });
        }

        // Filtre par FIMECO
        if ($request->filled('fimeco_id')) {
            $query->where('fimeco_id', $request->fimeco_id);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par montant minimum
        if ($request->filled('montant_min')) {
            $query->where('montant_souscrit', '>=', $request->montant_min);
        }

        // Filtre par montant maximum
        if ($request->filled('montant_max')) {
            $query->where('montant_souscrit', '<=', $request->montant_max);
        }

        // Filtre par date de souscription
        if ($request->filled('date_souscription')) {
            $query->whereDate('date_souscription', $request->date_souscription);
        }

        // Tri par défaut
        $query->orderByDesc('date_souscription')->orderByDesc('created_at');

        // Pagination
        $perPage = $request->get('per_page', 10);
        $subscriptions = $query->paginate($perPage)->appends($request->except('page'));

        $data = SubscriptionResource::collection($subscriptions);

        $meta = [
            'current_page' => $subscriptions->currentPage(),
            'last_page' => $subscriptions->lastPage(),
            'per_page' => $subscriptions->perPage(),
            'total' => $subscriptions->total(),
            'has_filters' => $request->hasAny(['search', 'fimeco_id', 'statut', 'montant_min', 'montant_max', 'date_souscription'])
        ];

        // Récupérer les FIMECO pour le dropdown
        $fimecosDisponibles = Fimeco::where('statut', 'active')
            ->orderBy('nom')
            ->get(['id', 'nom']);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $data,
                'meta' => $meta,
                'fimecos_disponibles' => $fimecosDisponibles
            ]);
        }

        return view('components.private.subscriptions.index', [
            'subscriptions' => $subscriptions, 
            'meta' => $meta,
            'fimecosDisponibles' => $fimecosDisponibles
        ]);
    }

    /**
     * Afficher le formulaire de création d'une nouvelle souscription
     */
    public function create(Request $request, string $fimeco = null)
    {
        $queryBuilder = Fimeco::where('statut', 'active')->orderBy('nom');

        if ($fimeco) {
            $fimecoActive = $queryBuilder->where('id', $fimeco)->first();
        } else {
            $fimecoActive = $queryBuilder->first();
        }

        if (!$fimecoActive) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Aucune FIMECO disponible pour souscription'
                ], 404);
            }

            return redirect()->back()->with('info', 'Aucune FIMECO disponible pour souscription');
        }

        $utilisateursDisponibles = User::whereDoesntHave('subscriptions', function ($query) use ($fimecoActive) {
            $query->where('fimeco_id', $fimecoActive->id);
        })
        ->orderBy('nom')
        ->get(['id', 'nom', 'prenom', 'telephone_1', 'email']);

        if ($request->expectsJson()) {
            return response()->json([
                'fimecos_disponibles' => $fimecoActive,
                'fimeco_selectionnee' => $fimecoActive,
                'utilisateurs_disponibles' => $utilisateursDisponibles
            ]);
        }

        return view('components.private.subscriptions.create', compact('fimecoActive', 'utilisateursDisponibles'));
    }

    public function usersDisponibles(string $fimeco)
    {
        try {
            $utilisateursDisponibles = User::whereDoesntHave('subscriptions', function ($query) use ($fimeco) {
                $query->where('fimeco_id', $fimeco);
            })
            ->orderBy('nom')
            ->get(['id', 'nom', 'prenom', 'telephone_1']);

            return response()->json([
                'success' => true,
                'data' => $utilisateursDisponibles
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les FIMECO disponibles pour les filtres
     */
    public function fimecosDisponibles(Request $request)
    {
        try {
            $fimecos = Fimeco::where('statut', 'active')
                ->orderBy('nom')
                ->get(['id', 'nom']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $fimecos
                ]);
            }

            return $fimecos;
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ], 500);
            }

            return collect([]);
        }
    }

    /**
     * Vérifier si un utilisateur peut souscrire à une FIMECO
     */
    public function peutSouscrire(Request $request, string $fimeco)
    {
        try {
            $fimecoExists = Fimeco::where('id', $fimeco)
                ->where('statut', 'active')
                ->exists();

            if (!$fimecoExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'FIMECO non trouvée ou inactive'
                ], 404);
            }

            $dejaSOuscrit = Subscription::where('fimeco_id', $fimeco)
                ->where('souscripteur_id', auth()->id())
                ->exists();

            $peutSouscrire = !$dejaSOuscrit;

            return response()->json([
                'success' => true,
                'peut_souscrire' => $peutSouscrire,
                'message' => $peutSouscrire 
                    ? 'Vous pouvez souscrire à cette FIMECO' 
                    : 'Vous avez déjà souscrit à cette FIMECO'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher le formulaire d'édition d'une souscription
     */
    public function edit(Request $request, string $id)
    {
        $subscription = Subscription::with(['fimeco'])
            ->where('souscripteur_id', auth()->id())
            ->findOrFail($id);

        // Vérifier que la souscription peut être modifiée
        if (in_array($subscription->statut, ['annulee', 'completement_payee'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Cette souscription ne peut plus être modifiée'
                ], 403);
            }

            return redirect()->back()->withErrors('Cette souscription ne peut plus être modifiée');
        }

        // Vérifier que la FIMECO est encore active
        if ($subscription->fimeco->statut !== 'active') {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'La FIMECO associée n\'est plus active'
                ], 403);
            }

            return redirect()->back()->withErrors('La FIMECO associée n\'est plus active');
        }

        $subscription = SubscriptionResource::make($subscription);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $subscription
            ]);
        }

        return view('components.private.subscriptions.edit', compact('subscription'));
    }

    public function show(Request $request, string $subscription)
    {
        $subscription = Subscription::with(['souscripteur', 'fimeco', 'payments.validateur'])
            ->findOrFail($subscription);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => SubscriptionResource::make($subscription)
            ]);
        }

        return view('components.private.subscriptions.show', ['subscription' => $subscription]);
    }

    public function store(CreerSouscriptionRequest $request)
    {
        try {
            $subscription = $this->subscriptionService->souscrire(
                $request->validated()
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'data' => SubscriptionResource::make($subscription),
                    'message' => 'Souscription créée avec succès'
                ], 201);
            }

            return redirect()->route('private.subscriptions.show', $subscription)
                ->with('success', 'Souscription créée avec succès');

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 400);
            }
            
            return redirect()->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'montant_souscrit' => ['required', 'numeric', 'min:10'],
            'expected_version' => ['nullable', 'integer']
        ]);

        try {
            $subscription = $this->subscriptionService->modifierMontantSouscription(
                $id,
                $request->montant_souscrit,
                $request->expected_version
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'data' => SubscriptionResource::make($subscription),
                    'message' => 'Souscription mise à jour avec succès'
                ]);
            }

            return redirect()->route('private.subscriptions.show', $subscription)
                ->with('success', 'Souscription mise à jour avec succès');

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 400);
            }

            return redirect()->back()
                ->withErrors($e->getMessage())
                ->withInput();
        }
    }

    public function mesStatistiques(Request $request)
    {
        $statistiques = $this->subscriptionService->calculerStatistiquesUtilisateur(auth()->id());

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $statistiques
            ]);
        }

        return view('components.private.subscriptions.statistiques', ['statistiques' => $statistiques]);
    }

    /**
     * Annuler une souscription
     */
    public function annuler(Request $request, string $id)
    {
        try {
            $subscription = $this->subscriptionService->annulerSouscription($id, '');

            if ($request->expectsJson()) {
                return response()->json([
                    'data' => SubscriptionResource::make($subscription),
                    'message' => 'Souscription annulée avec succès'
                ]);
            }

            return redirect()->route('private.subscriptions.show', $subscription)
                ->with('success', 'Souscription annulée avec succès');

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 400);
            }

            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    /**
     * Suspendre une souscription
     */
    public function suspendre(Request $request, string $id)
    {
        try {
            $subscription = $this->subscriptionService->suspendreSouscription($id, '');

            if ($request->expectsJson()) {
                return response()->json([
                    'data' => SubscriptionResource::make($subscription),
                    'message' => 'Souscription suspendue avec succès'
                ]);
            }

            return redirect()->route('private.subscriptions.show', $subscription)
                ->with('success', 'Souscription suspendue avec succès');

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage()
                ], 400);
            }

            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}