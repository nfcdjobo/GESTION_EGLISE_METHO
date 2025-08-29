<?php

namespace App\Http\Controllers\Private\Web;

// =================================================================
// app/Http/Controllers/SubscriptionController.php


use App\Models\Fimeco;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use App\Http\Resources\SubscriptionResource;
use App\Http\Requests\CreerSouscriptionRequest;

class SubscriptionController extends Controller
{
    public function __construct(private SubscriptionService $subscriptionService)
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Subscription::with(['souscripteur', 'fimeco', 'paymentsValides'])
            ->where('souscripteur_id', auth()->id());

        if ($request->filled('fimeco_id')) {
            $query->where('fimeco_id', $request->fimeco_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $subscriptions = $query->orderByDesc('date_souscription')->paginate(10);

        $data = SubscriptionResource::collection($subscriptions);
        $meta = [
            'current_page' => $subscriptions->currentPage(),
            'last_page' => $subscriptions->lastPage(),
            'per_page' => $subscriptions->perPage(),
            'total' => $subscriptions->total()
        ];

        if ($request->acceptsJson()) {
            return response()->json([
                'data' => $data,
                'meta' => $meta
            ]);
        }

        return view('components.private.subscriptions.index', ['subscriptions' => $subscriptions, 'meta' => $meta]);

    }

/**
 * Afficher le formulaire de création d'une nouvelle souscription
 */
public function create(Request $request)
{
    // Récupérer les FIMECO actives auxquelles l'utilisateur peut souscrire
    $fimecoActives = Fimeco::enCours()
        ->whereDoesntHave('subscriptions', function($query) {
            $query->where('souscripteur_id', auth()->id());
        })
        ->orderBy('nom')
        ->get();

    if ($fimecoActives->isEmpty()) {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Aucune FIMECO disponible pour souscription'
            ], 404);
        }

        return redirect()->route('private.fimecos.index')
            ->with('info', 'Aucune FIMECO disponible pour souscription');
    }

    // Si une FIMECO spécifique est demandée
    $fimecoId = $request->get('fimeco_id');
    $fimecoSelectionnee = null;

    if ($fimecoId) {
        $fimecoSelectionnee = $fimecoActives->firstWhere('id', $fimecoId);
        if (!$fimecoSelectionnee) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'FIMECO non disponible pour souscription'
                ], 404);
            }

            return redirect()->back()->withErrors('FIMECO non disponible pour souscription');
        }
    }

    if ($request->expectsJson()) {
        return response()->json([
            'fimecos_disponibles' => $fimecoActives,
            'fimeco_selectionnee' => $fimecoSelectionnee
        ]);
    }

    return view('components.private.subscriptions.create',
        compact('fimecoActives', 'fimecoSelectionnee'));
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


    public function show(Request $request, string $id)
    {
        $subscription = Subscription::with(['souscripteur', 'fimeco', 'payments.validateur'])
            ->where('souscripteur_id', auth()->id())
            ->findOrFail($id);

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
                array_merge($request->validated(), [
                    'souscripteur_id' => auth()->id()
                ])
            );
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => SubscriptionResource::make($subscription),
                    'message' => 'Souscription créée avec succès'
                ], 201);
            }

            return redirect()->route('private.subscriptions.show', $subscription);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }



    public function update(Request $request, string $id)
    {
        $request->validate([
            'montant_souscrit' => ['required', 'numeric', 'min:10', 'max:999999.99'],
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
            return redirect()->route('private.subscriptions.show', $subscription);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
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
}

