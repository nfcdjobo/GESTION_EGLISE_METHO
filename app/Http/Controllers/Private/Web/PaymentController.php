<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\Fimeco;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPayment;
use App\Http\Controllers\Controller;
use App\Http\Requests\AjouterPaiementRequest;
use App\Http\Resources\SubscriptionPaymentResource;

class PaymentController extends Controller
{


    public function __construct(private PaymentService $paymentService)
{
    $this->middleware('auth');
    $this->middleware('permission:payments.read')->only(['index', 'show', 'fimecosDisponibles', 'typesPaiement']);
    $this->middleware('permission:payments.create')->only(['create', 'store']);
    $this->middleware('permission:payments.update')->only(['edit', 'update']);
    $this->middleware('permission:payments.moderate')->only(['valider', 'refuser', 'annuler', 'enAttente', 'traiterEnLot']);
}

    /**
     * Afficher la liste des paiements de l'membres
     */
    public function index(Request $request)
    {
        $query = SubscriptionPayment::with(['subscription.fimeco', 'validateur'])
            ->whereHas('subscription', function ($query) {
                $query->where('souscripteur_id', auth()->id());
            });

        // Filtres
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('fimeco_id')) {
            $query->whereHas('subscription', function ($q) use ($request) {
                $q->where('fimeco_id', $request->fimeco_id);
            });
        }

        if ($request->filled('type_paiement')) {
            $query->where('type_paiement', $request->type_paiement);
        }

        // Filtrage par période
        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        $payments = $query->orderByDesc('date_paiement')->paginate(15);

        $data = SubscriptionPaymentResource::collection($payments);
        $meta = [
            'current_page' => $payments->currentPage(),
            'last_page' => $payments->lastPage(),
            'per_page' => $payments->perPage(),
            'total' => $payments->total()
        ];

        // Statistiques rapides
        $stats = [
            'total_paye' => $query->where('statut', 'valide')->sum('montant'),
            'en_attente' => $query->where('statut', 'en_attente')->count(),
            'refuses' => $query->where('statut', 'refuse')->count()
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $data,
                'meta' => $meta,
                'statistiques' => $stats
            ]);
        }

        return view(
            'components.private.paiements.index',
            compact('payments', 'meta', 'stats')
        );
    }

    /**
     * Afficher le formulaire de création d'un nouveau paiement pour une souscription
     */
    public function create(Request $request, string $subscription)
    {
        // Récupérer la souscription avec vérification que l'membres en est le propriétaire
        $subscription = Subscription::with(['fimeco', 'souscripteur'])
            ->where('id', $subscription)
            ->firstOrFail();

        // Vérifier que la souscription permet l'ajout de paiements
        if (!in_array($subscription->statut, ['active', 'partiellement_payee'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Cette souscription ne permet pas l\'ajout de nouveaux paiements',
                    'statut_actuel' => $subscription->statut
                ], 403);
            }

            return redirect()->route('private.subscriptions.show', $subscription)
                ->withErrors('Cette souscription ne permet pas l\'ajout de nouveaux paiements');
        }

        // Vérifier qu'il reste un montant à payer
        if ($subscription->reste_a_payer <= 0) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Cette souscription est entièrement payée',
                    'reste_a_payer' => $subscription->reste_a_payer
                ], 403);
            }

            return redirect()->route('private.subscriptions.show', $subscription)
                ->withErrors('Cette souscription est entièrement payée');
        }

        // Vérifier que la FIMECO est encore active
        if ($subscription->fimeco->statut !== 'active') {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'La FIMECO associée n\'est plus active',
                    'fimeco_statut' => $subscription->fimeco->statut
                ], 403);
            }

            return redirect()->route('private.subscriptions.show', $subscription)
                ->withErrors('La FIMECO associée n\'est plus active');
        }

        // Types de paiement disponibles
        $typesPaiement = config('fimeco.types_paiement_autorises', [
            'especes' => 'Espèces',
            'cheque' => 'Chèque',
            'virement' => 'Virement',
            'carte' => 'Carte bancaire',
            'mobile_money' => 'Mobile Money'
        ]);

        // Calculer le montant suggéré (reste à payer ou montant partiel)
        $montantSuggere = $subscription->reste_a_payer;

        // Paiements en attente pour cette souscription (pour information)
        $paiementsEnAttente = $subscription->payments()
            ->where('statut', 'en_attente')
            ->orderBy('date_paiement', 'desc')
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'subscription' => [
                    'id' => $subscription->id,
                    'fimeco_nom' => $subscription->fimeco->nom,
                    'fimeco_id' => $subscription->fimeco->id,
                    'montant_souscrit' => $subscription->montant_souscrit,
                    'montant_paye' => $subscription->montant_paye,
                    'reste_a_payer' => $subscription->reste_a_payer,
                    'statut' => $subscription->statut,
                    'date_souscription' => $subscription->date_souscription->format('d/m/Y')
                ],
                'montant_suggere' => $montantSuggere,
                'types_paiement' => $typesPaiement,
                'paiements_en_attente' => $paiementsEnAttente->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'montant' => $payment->montant,
                        'type_paiement' => $payment->type_paiement,
                        'date_paiement' => $payment->date_paiement->format('d/m/Y'),
                        'statut' => $payment->statut
                    ];
                })
            ]);
        }

        return view(
            'components.private.paiements.create',
            compact('subscription', 'montantSuggere', 'typesPaiement', 'paiementsEnAttente')
        );
    }


    /**
     * Afficher les détails d'un paiement spécifique
     */
    public function show(Request $request, string $payment)
    {
        $payment = SubscriptionPayment::with([
            'subscription.fimeco',
            'subscription.souscripteur',
            'validateur'
        ])
            ->whereHas('subscription')
            ->findOrFail($payment);

        $payment = SubscriptionPaymentResource::make($payment);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $payment
            ]);
        }

        return view('components.private.paiements.show', compact('payment'));
    }

    /**
     * Afficher le formulaire d'édition d'un paiement (limité aux paiements en attente)
     */
    public function edit(Request $request, string $id)
    {
        $payment = SubscriptionPayment::with(['subscription.fimeco'])
            ->whereHas('subscription', function ($query) {
                $query->where('souscripteur_id', auth()->id());
            })
            ->findOrFail($id);

        // Seuls les paiements en attente peuvent être modifiés
        if ($payment->statut !== 'en_attente') {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Seuls les paiements en attente peuvent être modifiés'
                ], 403);
            }

            return redirect()->back()
                ->withErrors('Seuls les paiements en attente peuvent être modifiés');
        }

        // Vérifier que la FIMECO est encore active
        if ($payment->subscription->fimeco->statut !== 'active') {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'La FIMECO associée n\'est plus active'
                ], 403);
            }

            return redirect()->back()
                ->withErrors('La FIMECO associée n\'est plus active');
        }

        // Types de paiement disponibles
        $typesPaiement = config('fimeco.types_paiement_autorises', [
            'especes' => 'Espèces',
            'cheque' => 'Chèque',
            'virement' => 'Virement',
            'carte' => 'Carte bancaire',
            'mobile_money' => 'Mobile Money'
        ]);

        $payment = SubscriptionPaymentResource::make($payment);

        if ($request->expectsJson()) {
            return response()->json([
                'data' => $payment,
                'types_paiement' => $typesPaiement
            ]);
        }

        return view(
            'components.private.payments.edit',
            compact('payment', 'typesPaiement')
        );
    }

    /**
     * Mettre à jour un paiement en attente
     */
    public function update(Request $request, string $payment)
    {

        $request->validate([
            'montant' => ['required', 'numeric'],
            'type_paiement' => ['required', 'in:especes,cheque,virement,carte,mobile_money'],
            'reference_paiement' => ['nullable', 'string', 'max:100'],
            'date_paiement' => ['required', 'date', 'before_or_equal:today'],
            'commentaire' => ['nullable', 'string', 'max:500']
        ]);

        try {
            $payment = $this->paymentService->modifierPaiement($payment, $request->validated());

            if ($request->expectsJson()) {
                return response()->json([
                    'data' => SubscriptionPaymentResource::make($payment->load(['subscription', 'validateur'])),
                    'message' => 'Paiement modifié avec succès'
                ]);
            }

            return redirect()->route('private.paiements.show', $payment)
                ->with('success', 'Paiement modifié avec succès');

        } catch (\Exception $e) {
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



    /**
     * Récupérer les FIMECO disponibles pour souscription (API helper)
     */
    public function fimecosDisponibles(Request $request)
    {
        $fimecos = Fimeco::enCours()
            ->whereDoesntHave('subscriptions', function ($query) {
                $query->where('souscripteur_id', auth()->id());
            })
            ->select(['id', 'nom', 'description', 'debut', 'fin', 'cible'])
            ->orderBy('nom')
            ->get();

        return response()->json([
            'data' => $fimecos
        ]);
    }

    /**
     * Récupérer les types de paiement autorisés
     */
    public function typesPaiement(Request $request)
    {
        $types = config('fimeco.types_paiement_autorises', [
            'especes' => 'Espèces',
            'cheque' => 'Chèque',
            'virement' => 'Virement',
            'carte' => 'Carte bancaire',
            'mobile_money' => 'Mobile Money'
        ]);

        return response()->json([
            'data' => $types
        ]);
    }



    public function store(AjouterPaiementRequest $request)
    {
        try {


            $payment = DB::transaction(function () use ($request) {
                return $this->paymentService->ajouterPaiement(
                    $request->subscription_id,
                    $request->validated()
                );
            });
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => SubscriptionPaymentResource::make($payment->load(['subscription', 'validateur'])),
                    'message' => 'Paiement ajouté avec succès'
                ], 201);
            }

            $payment = SubscriptionPaymentResource::make($payment->load(['subscription', 'validateur']));

            return redirect()->route('private.paiements.show', $payment)->with('success', 'Paiement ajouté avec succès');


        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function valider(string $payment): JsonResponse
    {
        $this->authorize('validate', SubscriptionPayment::findOrFail($payment));

        try {
            $this->paymentService->validerPaiement($payment, request('commentaire'));

            return response()->json([
                'success' => true,
                'message' => 'Paiement validé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function refuser(Request $request, string $id): JsonResponse
    {
        $this->authorize('validate', SubscriptionPayment::findOrFail($id));

        $request->validate([
            'raison' => ['required', 'string', 'max:500']
        ]);

        try {
            $this->paymentService->refuserPaiement($id, $request->raison);

            return response()->json([
                'message' => 'Paiement refusé'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function annuler(Request $request, string $id): JsonResponse
    {
        $this->authorize('cancel', SubscriptionPayment::findOrFail($id));

        $request->validate([
            'raison' => ['required', 'string', 'max:500']
        ]);

        try {
            $this->paymentService->annulerPaiement($id, $request->raison);

            return response()->json([
                'message' => 'Paiement annulé'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function enAttente(Request $request): JsonResponse
    {
        $this->authorize('viewAny', SubscriptionPayment::class);

        $payments = $this->paymentService->obtenirPaiementsEnAttente($request->fimeco_id);

        return response()->json([
            'data' => SubscriptionPaymentResource::collection($payments)
        ]);
    }

    public function traiterEnLot(Request $request): JsonResponse
    {
        $this->authorize('validateMultiple', SubscriptionPayment::class);

        $request->validate([
            'payment_ids' => ['required', 'array', 'min:1'],
            'payment_ids.*' => ['uuid', 'exists:subscription_payments,id'],
            'action' => ['required', 'in:valider,refuser,annuler'],
            'commentaire' => ['nullable', 'string', 'max:500']
        ]);

        $resultats = $this->paymentService->traiterPaiementsEnLot(
            $request->payment_ids,
            $request->action,
            $request->commentaire
        );

        return response()->json([
            'message' => "Traitement terminé: {$resultats['succes']} succès, {$resultats['echecs']} échecs",
            'data' => $resultats
        ]);
    }
}
