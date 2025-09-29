<?php

namespace App\Http\Controllers\Private\Web;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Fimeco;
use App\Models\Parametres;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPayment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SubscriptionController extends Controller
{

    public function __construct()
    {
        // Middleware d'authentification de base
        $this->middleware('auth');

        // ================================
        // PERMISSIONS CRUD PRINCIPALES
        // ================================

        // Lecture des souscriptions
        $this->middleware('permission:subscriptions.read')->only([
            'index',
            'show',
            'search',
            'liveStats',
            'peutSouscrire'
        ]);

        // Création de souscriptions
        $this->middleware('permission:subscriptions.create')->only(['create', 'store']);

        // Modification de souscriptions
        $this->middleware('permission:subscriptions.update')->only([
            'edit',
            'update',
            'checkExists',
            'usersDisponibles'
        ]);

        // Suppression de souscriptions
        $this->middleware('permission:subscriptions.delete')->only(['destroy']);

        // ================================
        // PERMISSIONS FONCTIONNELLES
        // ================================

        // Dashboard et statistiques
        $this->middleware('permission:subscriptions.dashboard')->only(['dashboard']);
        $this->middleware('permission:subscriptions.mes-statistiques')->only(['mesStatistiques']);

        // Export de données
        $this->middleware('permission:subscriptions.export')->only(['export']);

        // Validation de données
        $this->middleware('permission:subscriptions.validate-data')->only(['validateSubscriptionData']);

        // Gestion des paiements
        $this->middleware('permission:subscriptions.paiement')->only([
            'effectuerPaiement',
            'simulerPaiement'
        ]);

        // Validation de souscription
        $this->middleware('permission:subscriptions.validate')->only(['validate']);

        // Rapports
        $this->middleware('permission:subscriptions.rapport')->only(['rapport']);

        // Gestion du statut
        $this->middleware('permission:subscriptions.desactiver')->only(['desactiver']);
        $this->middleware('permission:subscriptions.reactiver')->only(['reactiver']);
        $this->middleware('permission:subscriptions.annuler')->only(['annuler']);
        $this->middleware('permission:subscriptions.suspendre')->only(['suspendre']);
    }


    /**
     * Affiche la liste des souscriptions avec pagination et filtres
     */
    public function index(Request $request)
    {
        try {
            $query = Subscription::with([
                'souscripteur:id,nom,prenom,email,photo_profil,telephone_1',
                'fimeco:id,nom,statut,statut_global,cible,progression'
            ]);



            // Filtres
            $this->applyFilters($query, $request);

            // Tri
            $this->applySorting($query, $request);

            // Pagination
            $perPage = min($request->get('per_page', 10), 100);
            $subscriptions = $query->paginate($perPage);

            $meta = [
                'total' => $subscriptions->total(),
                'per_page' => $subscriptions->perPage(),
                'current_page' => $subscriptions->currentPage(),
                'last_page' => $subscriptions->lastPage(),
            ];

            if ($request->expectsJson()) {
                /** @var \Illuminate\Pagination\LengthAwarePaginator $subscriptions */
                $subscriptions->getCollection()->transform(function ($subscription) {
                    return $this->enrichSubscriptionData($subscription);
                });

                $meta = [
                    'total' => $subscriptions->total(),
                    'per_page' => $subscriptions->perPage(),
                    'current_page' => $subscriptions->currentPage(),
                    'last_page' => $subscriptions->lastPage(),
                ];

                return response()->json([
                    'success' => true,
                    'data' => $subscriptions,
                    'meta' => $meta
                ]);
            }

            return view('components.private.subscriptions.index', compact('subscriptions', 'meta'));

        } catch (Exception $e) {

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des souscriptions',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des souscriptions']);
        }
    }

    /**
     * Affiche une souscription spécifique avec ses détails
     */
    public function show(Request $request, string $id)
    {
        try {
            $subscription = Subscription::with([
                'souscripteur:id,nom,prenom,email,photo_profil,telephone_1',
                'fimeco:id,nom,description,cible,montant_solde,progression,statut,responsable_id',
                'fimeco.responsable:id,nom,email',
                'payments' => function ($query) {
                    $query->latest('date_paiement');
                },
                'payments.validateur:id,nom'
            ])->findOrFail($id);

            // Statistiques des paiements
            $statistiquesPaiements = $subscription->getStatistiquesPaiements();

            // Historique complet des paiements
            $historiquePaiements = $subscription->getHistoriquePaiements();

            // Alertes pour cette souscription
            $alertes = $this->getAlertesSubscription($subscription);

            $data = [
                'subscription' => $this->enrichSubscriptionData($subscription),
                'statistiques_paiements' => $statistiquesPaiements,
                'historique_paiements' => $historiquePaiements,
                'alertes' => $alertes,
                'peut_effectuer_paiement' => $this->canMakePayment($subscription),
                'montant_maximum_payable' => $subscription->getMontantMaximumPayableBase(),
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return view('components.private.subscriptions.show', $data);

        } catch (Exception $e) {
            // dd($e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Souscription non trouvée',
                    'error' => $e->getMessage()
                ], 404);
            }

            return redirect()->route('private.subscriptions.index')
                ->withErrors(['error' => 'Souscription non trouvée']);
        }
    }

    /**
     * Affiche le formulaire de création
     */
    public function create(Request $request, Fimeco $fimeco)
    {
        try {
            // Récupérer les FIMECOs disponibles pour souscription

            $fimecoId = $fimeco->id;

            $souscripteursPossibles = User::whereNotExists(function ($query) use ($fimecoId) {
                $query->selectRaw('1')
                    ->from('subscriptions')
                    ->whereColumn('subscriptions.souscripteur_id', 'users.id')
                    ->where('subscriptions.fimeco_id', '=', $fimecoId);
            })->get();


            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'fimecos_disponibles' => $fimeco,
                        'souscripteurs_possibles' => $souscripteursPossibles,
                        'montant_minimum' => 1000, // Montant minimum configurable
                    ]
                ]);
            }

            return view('components.private.subscriptions.create', compact('souscripteursPossibles', 'fimeco'));

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du chargement du formulaire',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors du chargement du formulaire']);
        }
    }

    /**
     * Crée une nouvelle souscription
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'souscripteur_id' => 'required|exists:users,id',
            'fimeco_id' => 'required|exists:fimecos,id',
            'montant_souscrit' => 'required|numeric|min:1000',
            'date_souscription' => 'required|date|before_or_equal:today',
            'date_echeance' => 'nullable|date|after:date_souscription',
        ], [
            'souscripteur_id.required' => 'Le souscripteur est obligatoire',
            'souscripteur_id.exists' => 'Le souscripteur sélectionné n\'existe pas',
            'fimeco_id.required' => 'Le FIMECO est obligatoire',
            'fimeco_id.exists' => 'Le FIMECO sélectionné n\'existe pas',
            'montant_souscrit.required' => 'Le montant souscrit est obligatoire',
            'montant_souscrit.min' => 'Le montant minimum est de 1000',
            'date_souscription.required' => 'La date de souscription est obligatoire',
            'date_souscription.before_or_equal' => 'La date de souscription ne peut pas être future',
            'date_echeance.after' => 'La date d\'échéance doit être postérieure à la date de souscription',
        ]);

        if ($validator->fails()) {
            // dd($validator->errors());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Vérifications métier
            $this->validateSubscriptionBusinessRules($request);

            // Vérifier si l'utilisateur n'a pas déjà souscrit à ce FIMECO
            $existingSubscription = Subscription::where('souscripteur_id', $request->souscripteur_id)
                ->where('fimeco_id', $request->fimeco_id)
                ->first();

            if ($existingSubscription) {
                throw new Exception('Ce souscripteur a déjà une souscription pour ce FIMECO');
            }

            $subscription = Subscription::create($validator->validated());

            // Log de création
            Log::info('Souscription créée', [
                'subscription_id' => $subscription->id,
                'souscripteur_id' => $subscription->souscripteur_id,
                'fimeco_id' => $subscription->fimeco_id,
                'montant' => $subscription->montant_souscrit,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Souscription créée avec succès',
                    'data' => $this->enrichSubscriptionData($subscription->load(['souscripteur', 'fimeco']))
                ], 201);
            }

            return redirect()->route('private.subscriptions.show', $subscription->id)
                ->with('success', 'Souscription créée avec succès');

        } catch (Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la souscription',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Request $request, string $id)
    {
        try {
            $subscription = Subscription::with(['souscripteur', 'fimeco'])->findOrFail($id);

            // Vérification des permissions
            if (!$this->canUpdateSubscription($subscription)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Non autorisé à modifier cette souscription'
                    ], 403);
                }

                return redirect()->route('private.subscriptions.index')
                    ->withErrors(['error' => 'Non autorisé à modifier cette souscription']);
            }

            $data = [
                'subscription' => $this->enrichSubscriptionData($subscription),
                'peut_modifier_montant' => $this->canModifyAmount($subscription),
                'peut_modifier_echeance' => $this->canModifyDeadline($subscription),
            ];
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return view('components.private.subscriptions.edit', $data);

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Souscription non trouvée',
                    'error' => $e->getMessage()
                ], 404);
            }

            return redirect()->route('private.subscriptions.index')
                ->withErrors(['error' => 'Souscription non trouvée']);
        }
    }

    /**
     * Met à jour une souscription
     */
    public function update(Request $request, string $id)
    {
        try {
            $subscription = Subscription::findOrFail($id);

            if (!$this->canUpdateSubscription($subscription)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Non autorisé à modifier cette souscription'
                    ], 403);
                }

                return back()->withErrors(['error' => 'Non autorisé à modifier cette souscription']);
            }

            $rules = [
                'date_echeance' => 'nullable|date|after:date_souscription',
            ];

            // Permettre la modification du montant seulement si aucun paiement n'a été effectué
            if ($this->canModifyAmount($subscription)) {
                $rules['montant_souscrit'] = 'sometimes|numeric|min:1000';
            }

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Données de validation incorrectes',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return back()->withErrors($validator)->withInput();
            }

            DB::beginTransaction();

            $oldData = $subscription->toArray();
            $subscription->update($validator->validated());

            // Log des modifications
            $this->logSubscriptionChanges($subscription, $oldData, $subscription->toArray());

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Souscription mise à jour avec succès',
                    'data' => $this->enrichSubscriptionData($subscription->load(['souscripteur', 'fimeco']))
                ]);
            }

            return redirect()->route('private.subscriptions.show', $subscription->id)
                ->with('success', 'Souscription mise à jour avec succès');

        } catch (Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la mise à jour'])
                ->withInput();
        }
    }

    /**
     * Supprime une souscription (soft delete)
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $subscription = Subscription::findOrFail($id);

            if (!$this->canDeleteSubscription($subscription)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cette souscription ne peut pas être supprimée car elle contient des paiements validés'
                    ], 400);
                }

                return back()->withErrors(['error' => 'Cette souscription ne peut pas être supprimée']);
            }

            DB::beginTransaction();

            $subscription->delete();

            Log::info('Souscription supprimée', [
                'subscription_id' => $subscription->id,
                'souscripteur_id' => $subscription->souscripteur_id,
                'fimeco_id' => $subscription->fimeco_id,
                'deleted_by' => auth()->id(),
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Souscription supprimée avec succès'
                ]);
            }

            return redirect()->route('private.subscriptions.index')
                ->with('success', 'Souscription supprimée avec succès');

        } catch (Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la suppression']);
        }
    }

    /**
     * Dashboard des souscriptions avec statistiques
     */
    public function dashboard(Request $request)
    {
        try {
            $cacheKey = 'subscriptions_dashboard_' . auth()->id();

            $data = Cache::remember($cacheKey, 300, function () {
                return [
                    'statistiques_globales' => $this->getStatistiquesGlobales(),
                    'souscriptions_urgentes' => $this->getSubscriptionsUrgentes(),
                    'performance_mensuelle' => $this->getPerformanceMensuelle(),
                    'top_souscripteurs' => $this->getTopSouscripteurs(),
                    'alertes' => $this->getAlertes(),
                    'evolution_souscriptions' => $this->getEvolutionSouscriptions(),
                ];
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return view('components.private.subscriptions.dashboard', $data);

        } catch (Exception $e) {
            // dd($e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du chargement du dashboard',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors du chargement du dashboard']);
        }
    }

    /**
     * Effectue un paiement pour une souscription
     */
    /**
     * Effectue un paiement pour une souscription - VERSION MODIFIÉE pour accepter les paiements supplémentaires
     */
    public function effectuerPaiement(Request $request, string $id): JsonResponse
    {
        try {
            $subscription = Subscription::findOrFail($id);

            // Règles de validation modifiées - on ne limite plus le montant au reste à payer
            $validator = Validator::make($request->all(), [
                'montant' => 'required|numeric|min:1', // Limite haute de sécurité
                'type_paiement' => 'required|in:especes,cheque,virement,carte,mobile_money',
                'reference_paiement' => 'nullable|string|max:100',
                'date_paiement' => 'required|date|before_or_equal:now',
                'commentaire' => 'nullable|string|max:1000',
                'accepter_paiement_supplementaire' => 'sometimes|in:1,true,on'
            ], [
                'montant.required' => 'Le montant est obligatoire',
                'montant.min' => 'Le montant doit être supérieur à zéro',
                'montant.max' => 'Le montant ne peut pas dépasser 10,000,000',
                'type_paiement.required' => 'Le type de paiement est obligatoire',
                'type_paiement.in' => 'Type de paiement invalide',
                'date_paiement.required' => 'La date de paiement est obligatoire',
                'date_paiement.before_or_equal' => 'La date de paiement ne peut pas être future',
            ]);

            if ($validator->fails()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Vérifications métier modifiées
            if (!$subscription->peutAccepterPaiement($request->montant)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce paiement ne peut pas être accepté'
                ], 400);
            }

            // Analyse de l'impact du paiement
            $impactPaiement = $subscription->getImpactPaiement($request->montant);

            // Avertissement si paiement supplémentaire sans confirmation explicite
            if ($impactPaiement['montant_supplementaire'] > 0 && !$request->accepter_paiement_supplementaire) {
                return response()->json([
                    'success' => false,
                    'message' => 'Confirmation requise pour paiement supplémentaire',
                    'data' => [
                        'type' => 'confirmation_required',
                        'impact_paiement' => $impactPaiement,
                        'message_confirmation' => "Ce paiement inclut " .
                            number_format($impactPaiement['montant_supplementaire'], 0, ',', ' ') .
                            " FCFA au-delà de votre souscription initiale. Confirmez-vous ce paiement supplémentaire ?",
                        'action_required' => 'Ajoutez "accepter_paiement_supplementaire": true pour confirmer'
                    ]
                ], 400);
            }

            DB::beginTransaction();

            $paymentData = $validator->validated();
            $paymentData['subscription_id'] = $subscription->id;

            // Calcul des restes pour le paiement - VERSION MODIFIÉE
            $resteActuel = max(0, $subscription->montant_souscrit - $subscription->montant_paye);
            $paymentData['ancien_reste'] = $resteActuel;
            $paymentData['nouveau_reste'] = max(0, $resteActuel - $request->montant);

            $paymentData['subscription_version_at_payment'] = $subscription->updated_at->timestamp;

            // Ajout d'informations sur le paiement supplémentaire
            if ($impactPaiement['montant_supplementaire'] > 0) {
                $commentaireSupplementaire = "Paiement supplémentaire de " .
                    number_format($impactPaiement['montant_supplementaire'], 0, ',', ' ') .
                    " FCFA au-delà de la souscription initiale.";

                $paymentData['commentaire'] = $paymentData['commentaire'] ?
                    $paymentData['commentaire'] . " | " . $commentaireSupplementaire :
                    $commentaireSupplementaire;
            }

            $payment = SubscriptionPayment::create($paymentData);

            // Log de création enrichi
            Log::info('Paiement créé', [
                'payment_id' => $payment->id,
                'subscription_id' => $subscription->id,
                'montant' => $payment->montant,
                'type' => $payment->type_paiement,
                'montant_supplementaire' => $impactPaiement['montant_supplementaire'],
                'est_paiement_supplementaire' => $impactPaiement['montant_supplementaire'] > 0,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $impactPaiement['montant_supplementaire'] > 0 ?
                    'Paiement supplémentaire enregistré avec succès' :
                    'Paiement enregistré avec succès',
                'data' => [
                    'payment' => $payment,
                    'subscription' => $this->enrichSubscriptionData($subscription->fresh()),
                    'impact_paiement' => $impactPaiement,
                    'recepisse' => $payment->genererRecepisse(),
                    'suggestions_prochains_paiements' => $subscription->fresh()->getMontantSuggereProchainPaiement(),
                ]
            ], 201);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement du paiement',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Nouvelle méthode : Simule l'impact d'un paiement sans l'enregistrer
     */
    public function simulerPaiement(Request $request, string $id): JsonResponse
    {
        try {
            $subscription = Subscription::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'montant' => 'required|numeric|min:1|max:10000000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $impactPaiement = $subscription->getImpactPaiement($request->montant);

            return response()->json([
                'success' => true,
                'data' => [
                    'impact_paiement' => $impactPaiement,
                    'subscription_actuelle' => $this->enrichSubscriptionData($subscription),
                    'warnings' => $this->getWarningsForPayment($subscription, $request->montant),
                    'suggestions' => $subscription->getMontantSuggereProchainPaiement(),
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la simulation',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Méthode privée pour générer des avertissements sur un paiement
     */
    private function getWarningsForPayment(Subscription $subscription, float $montant): array
    {
        $warnings = [];
        $impactPaiement = $subscription->getImpactPaiement($montant);

        if ($impactPaiement['montant_supplementaire'] > 0) {
            $warnings[] = [
                'type' => 'info',
                'message' => "Ce paiement inclut " .
                    number_format($impactPaiement['montant_supplementaire'], 0, ',', ' ') .
                    " FCFA de paiement supplémentaire au-delà de votre souscription initiale."
            ];
        }

        if ($montant > 100000) {
            $warnings[] = [
                'type' => 'warning',
                'message' => 'Montant important - vérifiez bien avant de confirmer.'
            ];
        }

        if ($subscription->date_echeance && $subscription->date_echeance < now()) {
            $warnings[] = [
                'type' => 'info',
                'message' => 'Cette souscription a dépassé son échéance.'
            ];
        }

        if ($impactPaiement['taux_depassement'] > 100) {
            $warnings[] = [
                'type' => 'warning',
                'message' => 'Avec ce paiement, vous aurez payé plus du double de votre souscription initiale.'
            ];
        }

        return $warnings;
    }



    /**
     * Marque une souscription comme inactive
     */
    public function desactiver(string $id): JsonResponse
    {
        try {
            $subscription = Subscription::findOrFail($id);

            if ($subscription->statut === 'inactive') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette souscription est déjà inactive'
                ], 400);
            }

            if ($subscription->montant_paye > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de désactiver une souscription avec des paiements'
                ], 400);
            }

            DB::beginTransaction();

            $subscription->update(['statut' => 'inactive']);

            Log::info('Souscription désactivée', [
                'subscription_id' => $subscription->id,
                'deactivated_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Souscription désactivée avec succès',
                'data' => $this->enrichSubscriptionData($subscription)
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la désactivation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Réactive une souscription
     */
    public function reactiver(string $id): JsonResponse
    {
        try {
            $subscription = Subscription::findOrFail($id);

            if ($subscription->statut !== 'inactive') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette souscription n\'est pas inactive'
                ], 400);
            }

            // Vérifier si le FIMECO accepte encore des souscriptions
            if (!$subscription->fimeco->peutAccepterNouvellesSouscriptions()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le FIMECO associé n\'accepte plus de nouvelles souscriptions'
                ], 400);
            }

            DB::beginTransaction();

            $subscription->update(['statut' => 'partiellement_payee']);

            Log::info('Souscription réactivée', [
                'subscription_id' => $subscription->id,
                'reactivated_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Souscription réactivée avec succès',
                'data' => $this->enrichSubscriptionData($subscription)
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la réactivation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère un rapport détaillé de la souscription
     */
    public function rapport(string $id, Request $request): JsonResponse
    {
        try {
            $subscription = Subscription::with([
                'souscripteur',
                'fimeco',
                'payments.validateur'
            ])->findOrFail($id);

            $format = $request->get('format', 'json');

            $rapport = [
                'informations_generales' => [
                    'id' => $subscription->id,
                    'souscripteur' => $subscription->souscripteur->nom,
                    'fimeco' => $subscription->fimeco->nom,
                    'date_souscription' => $subscription->date_souscription->format('d/m/Y'),
                    'date_echeance' => $subscription->date_echeance?->format('d/m/Y'),
                    'statut' => $subscription->getStatutLibelle(),
                    'statut_global' => $subscription->getStatutGlobalLibelle(),
                ],
                'montants' => [
                    'montant_souscrit' => $subscription->montant_souscrit,
                    'montant_paye' => $subscription->montant_paye,
                    'reste_a_payer' => $subscription->reste_a_payer,
                    'progression' => $subscription->progression_formatee,
                ],
                'statistiques_paiements' => $subscription->getStatistiquesPaiements(),
                'historique_paiements' => $subscription->payments->map(function ($payment) {
                    return [
                        'date' => $payment->date_paiement->format('d/m/Y H:i'),
                        'montant' => $payment->montant,
                        'type' => $payment->getTypePaiementLibelle(),
                        'statut' => $payment->getStatutLibelle(),
                        'validateur' => $payment->validateur?->nom,
                        'reference' => $payment->reference_paiement,
                    ];
                }),
                'alertes' => $this->getAlertesSubscription($subscription),
                'date_generation' => now()->format('d/m/Y H:i:s'),
            ];

            if ($format === 'pdf') {
                return $this->generateSubscriptionPdfReport($rapport, $subscription);
            }

            return response()->json([
                'success' => true,
                'data' => $rapport
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du rapport',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export des souscriptions
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'excel');

            $query = Subscription::with(['souscripteur', 'fimeco']);
            $this->applyFilters($query, $request);

            $subscriptions = $query->get();

            $data = $subscriptions->map(function ($subscription) {
                return [
                    'ID' => $subscription->id,
                    'Souscripteur' => $subscription->souscripteur->nom,
                    'FIMECO' => $subscription->fimeco->nom,
                    'Date souscription' => $subscription->date_souscription->format('d/m/Y'),
                    'Date échéance' => $subscription->date_echeance?->format('d/m/Y'),
                    'Montant souscrit' => $subscription->montant_souscrit,
                    'Montant payé' => $subscription->montant_paye,
                    'Reste à payer' => $subscription->reste_a_payer,
                    'Progression (%)' => $subscription->progression,
                    'Statut' => $subscription->getStatutLibelle(),
                    'Statut global' => $subscription->getStatutGlobalLibelle(),
                    'En retard' => $subscription->en_retard ? 'Oui' : 'Non',
                    'Nb paiements' => $subscription->payments->count(),
                ];
            });

            $data = $data instanceof Collection ? $data->toArray() : $data;

            if ($format === 'csv') {
                return $this->generateCsvExport($data, 'souscriptions_' . now()->format('Y-m-d'));
            } elseif ($format === 'pdf') {
                return $this->generatePdfExport($data, 'souscriptions_' . now()->format('Y-m-d'));
            } else {
                return $this->generateExcelExport($data, 'souscriptions_' . now()->format('Y-m-d'));
            }

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // === MÉTHODES PRIVÉES ===

    /**
     * Applique les filtres à la requête
     */
    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('statut_global')) {
            $query->where('statut_global', $request->statut_global);
        }

        if ($request->filled('souscripteur_id')) {
            $query->where('souscripteur_id', $request->souscripteur_id);
        }

        if ($request->filled('fimeco_id')) {
            $query->where('fimeco_id', $request->fimeco_id);
        }

        if ($request->filled('date_souscription_debut')) {
            $query->where('date_souscription', '>=', $request->date_souscription_debut);
        }

        if ($request->filled('date_souscription_fin')) {
            $query->where('date_souscription', '<=', $request->date_souscription_fin);
        }

        if ($request->filled('en_retard') && $request->en_retard) {
            $query->enRetard();
        }

        if ($request->filled('montant_min')) {
            $query->where('montant_souscrit', '>=', $request->montant_min);
        }

        if ($request->filled('montant_max')) {
            $query->where('montant_souscrit', '<=', $request->montant_max);
        }

        if ($request->filled('progression_min')) {
            $query->where('progression', '>=', $request->progression_min);
        }

        if ($request->filled('progression_max')) {
            $query->where('progression', '<=', $request->progression_max);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('souscripteur', function ($q) use ($search) {
                $q->where('nom', 'ILIKE', "%{$search}%")
                    ->orWhere('prenom', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%");
            })->orWhereHas('fimeco', function ($q) use ($search) {
                $q->where('nom', 'ILIKE', "%{$search}%");
            });
        }
    }

    /**
     * Applique le tri à la requête
     */
    private function applySorting($query, Request $request): void
    {
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSorts = [
            'date_souscription',
            'date_echeance',
            'montant_souscrit',
            'montant_paye',
            'reste_a_payer',
            'progression',
            'statut',
            'statut_global',
            'created_at'
        ];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }
    }

    /**
     * Enrichit les données d'une souscription
     */
    private function enrichSubscriptionData(Subscription $subscription): array
    {
        return array_merge($subscription->toArray(), [
            'progression_formatee' => $subscription->progression_formatee,
            'est_complete' => $subscription->est_complete,
            'en_retard' => $subscription->en_retard,
            'jours_retard' => $subscription->jours_retard,
            'jours_restants' => $subscription->jours_restants,
            'montant_total_paye' => $subscription->montant_total_paye,
            'nombre_paiements' => $subscription->nombre_paiements,
            'statut_libelle' => $subscription->getStatutLibelle(),
            'statut_global_libelle' => $subscription->getStatutGlobalLibelle(),
            'necessite_attention' => $subscription->necessiteAttention(),
        ]);
    }

    /**
     * Valide les règles métier pour la création d'une souscription
     */
    private function validateSubscriptionBusinessRules(Request $request): void
    {
        $fimeco = Fimeco::findOrFail($request->fimeco_id);

        // Vérifier que le FIMECO accepte encore des souscriptions
        if (!$fimeco->peutAccepterNouvellesSouscriptions()) {
            throw new Exception('Ce FIMECO n\'accepte plus de nouvelles souscriptions');
        }

        // Vérifier que le montant ne dépasse pas ce qui reste disponible
        $montantDisponible = $fimeco->getMontantDisponible();
        if ($request->montant_souscrit > $montantDisponible) {
            throw new Exception("Le montant souscrit dépasse le montant disponible ({$montantDisponible})");
        }

        // Vérifier que la date de souscription est dans la période du FIMECO
        $datesouscription = Carbon::parse($request->date_souscription);
        if ($datesouscription < $fimeco->debut || $datesouscription > $fimeco->fin) {
            throw new Exception('La date de souscription doit être dans la période du FIMECO');
        }
    }

    /**
     * Vérifie si l'utilisateur peut modifier la souscription
     */
    private function canUpdateSubscription(Subscription $subscription): bool
    {
        /**
         * @var User $user
         */
        $user = auth()->user();

        // Admin peut tout modifier
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        // Responsable du FIMECO peut modifier
        if ($user->id === $subscription->fimeco->responsable_id) {
            return true;
        }

        // Le souscripteur peut modifier sa propre souscription (limité)
        if ($user->id === $subscription->souscripteur_id && $subscription->statut !== 'completement_payee') {
            return true;
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur peut supprimer la souscription
     */
    private function canDeleteSubscription(Subscription $subscription): bool
    {
        // Ne peut pas supprimer s'il y a des paiements validés
        if ($subscription->paymentsValides()->exists()) {
            return false;
        }

        /**
         * @var User $user
         */
        $user = auth()->user();

        // Admin peut supprimer
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        // Responsable du FIMECO peut supprimer
        if ($user->id === $subscription->fimeco->responsable_id) {
            return true;
        }

        return false;
    }

    /**
     * Vérifie si on peut modifier le montant
     */
    private function canModifyAmount(Subscription $subscription): bool
    {
        return $subscription->montant_paye == 0;
    }

    /**
     * Vérifie si on peut modifier l'échéance
     */
    private function canModifyDeadline(Subscription $subscription): bool
    {
        return $subscription->statut !== 'completement_payee';
    }

    /**
     * Vérifie si on peut effectuer un paiement
     */
    private function canMakePayment(Subscription $subscription): bool
    {
        return $subscription->statut !== 'completement_payee' &&
            $subscription->reste_a_payer > 0 &&
            $subscription->fimeco->statut === 'active';
    }

    /**
     * Log des modifications de souscription
     */
    private function logSubscriptionChanges(Subscription $subscription, array $oldData, array $newData): void
    {
        $changes = [];
        foreach (['montant_souscrit', 'date_echeance'] as $field) {
            if (isset($oldData[$field]) && isset($newData[$field]) && $oldData[$field] !== $newData[$field]) {
                $changes[$field] = [
                    'old' => $oldData[$field],
                    'new' => $newData[$field]
                ];
            }
        }

        if (!empty($changes)) {
            Log::info('Souscription modifiée', [
                'subscription_id' => $subscription->id,
                'changes' => $changes,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->nom ?? auth()->user()->name
            ]);
        }
    }

    /**
     * Retourne les statistiques globales des souscriptions
     */
    private function getStatistiquesGlobales(): array
    {
        return [
            'total_souscriptions' => Subscription::count(),
            'souscriptions_actives' => Subscription::actives()->count(),
            'souscriptions_completes' => Subscription::completes()->count(),
            'souscriptions_en_retard' => Subscription::enRetard()->count(),
            'montant_total_souscrit' => Subscription::sum('montant_souscrit'),
            'montant_total_paye' => Subscription::sum('montant_paye'),
            'progression_moyenne' => Subscription::avg('progression'),
            'taux_completion' => $this->calculateTauxCompletion(),
        ];
    }

    /**
     * Retourne les souscriptions urgentes
     */
    private function getSubscriptionsUrgentes(): array
    {
        return Subscription::with(['souscripteur:id,nom', 'fimeco:id,nom'])
            ->where(function ($query) {
                $query->enRetard()
                    ->orWhere(function ($q) {
                        $q->where('date_echeance', '<=', now()->addDays(7))
                            ->where('statut', '!=', 'completement_payee');
                    });
            })
            ->orderBy('date_echeance')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Retourne la performance mensuelle
     */
    private function getPerformanceMensuelle(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $data[] = [
                'mois' => $month->format('Y-m'),
                'nouvelles_souscriptions' => Subscription::whereYear('date_souscription', $month->year)
                    ->whereMonth('date_souscription', $month->month)
                    ->count(),
                'souscriptions_completees' => Subscription::whereYear('updated_at', $month->year)
                    ->whereMonth('updated_at', $month->month)
                    ->where('statut', 'completement_payee')
                    ->count(),
                'montant_souscrit' => Subscription::whereYear('date_souscription', $month->year)
                    ->whereMonth('date_souscription', $month->month)
                    ->sum('montant_souscrit'),
            ];
        }
        return $data;
    }

    /**
     * Retourne les top souscripteurs - VERSION CORRIGÉE pour PostgreSQL
     */
    private function getTopSouscripteurs(): array
    {
        return DB::table('users')
            ->join('subscriptions', 'users.id', '=', 'subscriptions.souscripteur_id')
            ->select(
                'users.id',
                'users.nom',
                'users.prenom',
                'users.email',
                DB::raw('COUNT(subscriptions.id) as nb_souscriptions'),
                DB::raw('SUM(subscriptions.cible) as montant_total_souscrit'),
                DB::raw('SUM(subscriptions.montant_paye) as montant_total_paye'),
                DB::raw('CASE
                WHEN SUM(subscriptions.cible) > 0
                THEN ROUND((SUM(subscriptions.montant_paye) / SUM(subscriptions.cible)) * 100, 2)
                ELSE 0
            END as taux_paiement')
            )
            ->whereNull('subscriptions.deleted_at')
            ->whereNull('users.deleted_at')
            ->groupBy('users.id', 'users.nom', 'users.prenom', 'users.email')
            ->havingRaw('COUNT(subscriptions.id) > 0') // Utiliser havingRaw au lieu de having avec alias
            ->orderByRaw('SUM(subscriptions.cible) DESC') // Utiliser orderByRaw pour être sûr
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'nom' => trim($user->nom . ' ' . ($user->prenom ?? '')),
                    'email' => $user->email,
                    'nb_souscriptions' => (int) $user->nb_souscriptions,
                    'montant_total_souscrit' => (float) $user->montant_total_souscrit,
                    'montant_total_paye' => (float) $user->montant_total_paye,
                    'taux_paiement' => (float) $user->taux_paiement,
                ];
            })
            ->toArray();
    }




    // ALTERNATIVE avec Eloquent (plus lisible mais potentiellement moins performante)
    private function getTopSouscripteursEloquent(): array
    {
        return User::select('id', 'nom', 'prenom', 'email')
            ->with([
                'subscriptions' => function ($query) {
                    $query->select('souscripteur_id', 'cible', 'montant_paye');
                }
            ])
            ->whereHas('subscriptions')
            ->get()
            ->map(function ($user) {
                $subscriptions = $user->subscriptions;
                $montantTotalSouscrit = $subscriptions->sum('cible');
                $montantTotalPaye = $subscriptions->sum('montant_paye');

                return [
                    'nom' => trim($user->nom . ' ' . ($user->prenom ?? '')),
                    'email' => $user->email,
                    'nb_souscriptions' => $subscriptions->count(),
                    'montant_total_souscrit' => $montantTotalSouscrit,
                    'montant_total_paye' => $montantTotalPaye,
                    'taux_paiement' => $montantTotalSouscrit > 0 ?
                        round(($montantTotalPaye / $montantTotalSouscrit) * 100, 2) : 0,
                ];
            })
            ->sortByDesc('montant_total_souscrit')
            ->take(10)
            ->values()
            ->toArray();
    }


    // VERSION OPTIMISÉE avec scope sur le modèle User
// À ajouter dans le modèle User :
/*
public function scopeTopSouscripteurs($query, $limit = 10)
{
    return $query->select('users.*')
        ->selectRaw('COUNT(subscriptions.id) as subscriptions_count')
        ->selectRaw('COALESCE(SUM(subscriptions.montant_souscrit), 0) as subscriptions_montant_souscrit_sum')
        ->selectRaw('COALESCE(SUM(subscriptions.montant_paye), 0) as subscriptions_montant_paye_sum')
        ->leftJoin('subscriptions', function($join) {
            $join->on('users.id', '=', 'subscriptions.souscripteur_id')
                 ->whereNull('subscriptions.deleted_at');
        })
        ->whereNull('users.deleted_at')
        ->groupBy('users.id')
        ->having('subscriptions_count', '>', 0)
        ->orderBy('subscriptions_montant_souscrit_sum', 'desc')
        ->limit($limit);
}
*/



    // Et dans le contrôleur :
    private function getTopSouscripteursAvecScope(): array
    {
        return User::topSouscripteurs(10)
            ->get()
            ->map(function ($user) {
                return [
                    'nom' => trim($user->nom . ' ' . ($user->prenom ?? '')),
                    'email' => $user->email,
                    'nb_souscriptions' => $user->subscriptions_count,
                    'montant_total_souscrit' => $user->subscriptions_montant_souscrit_sum,
                    'montant_total_paye' => $user->subscriptions_montant_paye_sum,
                    'taux_paiement' => $user->subscriptions_montant_souscrit_sum > 0 ?
                        round(($user->subscriptions_montant_paye_sum / $user->subscriptions_montant_souscrit_sum) * 100, 2) : 0,
                ];
            })
            ->toArray();
    }




    /**
     * Retourne les alertes
     */
    private function getAlertes(): array
    {
        $alertes = [];

        // Souscriptions en retard
        $enRetard = Subscription::enRetard()->count();
        if ($enRetard > 0) {
            $alertes[] = [
                'type' => 'danger',
                'message' => "{$enRetard} souscription(s) en retard",
                'count' => $enRetard
            ];
        }

        // Échéances proches
        $echeancesProches = Subscription::echeanceProche(7)->count();
        if ($echeancesProches > 0) {
            $alertes[] = [
                'type' => 'warning',
                'message' => "{$echeancesProches} souscription(s) arrivent à échéance dans 7 jours",
                'count' => $echeancesProches
            ];
        }

        // Paiements en attente de validation
        $paiementsEnAttente = SubscriptionPayment::enAttente()->count();
        if ($paiementsEnAttente > 0) {
            $alertes[] = [
                'type' => 'info',
                'message' => "{$paiementsEnAttente} paiement(s) en attente de validation",
                'count' => $paiementsEnAttente
            ];
        }

        return $alertes;
    }

    /**
     * Retourne l'évolution des souscriptions
     */
    private function getEvolutionSouscriptions(): array
    {
        return DB::table('subscriptions')
            ->selectRaw('
                DATE_TRUNC(\'month\', date_souscription) as mois,
                COUNT(*) as nb_souscriptions,
                SUM(montant_souscrit) as montant_total,
                AVG(montant_souscrit) as montant_moyen
            ')
            ->where('deleted_at', null)
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->toArray();
    }

    /**
     * Calcule le taux de completion
     */
    private function calculateTauxCompletion(): float
    {
        $total = Subscription::count();
        if ($total === 0)
            return 0;

        $completes = Subscription::completes()->count();
        return round(($completes / $total) * 100, 2);
    }

    /**
     * Retourne les alertes pour une souscription spécifique
     */
    private function getAlertesSubscription(Subscription $subscription): array
    {
        $alertes = [];

        if ($subscription->en_retard) {
            $alertes[] = [
                'type' => 'danger',
                'message' => "En retard de {$subscription->jours_retard} jour(s)"
            ];
        } elseif ($subscription->jours_restants <= 7 && $subscription->statut !== 'completement_payee') {
            $alertes[] = [
                'type' => 'warning',
                'message' => "Échéance dans {$subscription->jours_restants} jour(s)"
            ];
        }

        if ($subscription->paymentsEnAttente()->exists()) {
            $nbEnAttente = $subscription->paymentsEnAttente()->count();
            $alertes[] = [
                'type' => 'info',
                'message' => "{$nbEnAttente} paiement(s) en attente de validation"
            ];
        }

        if ($subscription->progression < 25 && $subscription->jours_restants <= 30) {
            $alertes[] = [
                'type' => 'warning',
                'message' => 'Progression très faible avec échéance proche'
            ];
        }

        return $alertes;
    }

    /**
     * Génère un rapport PDF pour une souscription
     */
    private function generateSubscriptionPdfReport(array $rapport, Subscription $subscription)
    {
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return response()->json([
                'success' => false,
                'message' => 'Le package DomPDF n\'est pas installé',
            ], 500);
        }

        try {
            $pdf = Pdf::loadView('exports.subscriptions.rapport-pdf', compact('rapport', 'subscription'));

            $filename = 'rapport_souscription_' . $subscription->id . '_' . now()->format('Y-m-d') . '.pdf';

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère un export CSV
     */
    private function generateCsvExport(array $data, string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            if (!empty($data)) {
                fputcsv($handle, array_keys($data[0]), ';');
                foreach ($data as $row) {
                    fputcsv($handle, $row, ';');
                }
            }

            fclose($handle);
        }, $filename . '.csv', [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ]);
    }

    /**
     * Génère un export Excel
     */
    // private function generateExcelExport(array $data, string $filename)
    // {
    //     return response()->streamDownload(function () use ($data) {
    //         $handle = fopen('php://output', 'w');
    //         fputs($handle, "\xEF\xBB\xBF");

    //         if (!empty($data)) {
    //             fputcsv($handle, array_keys($data[0]), "\t");
    //             foreach ($data as $row) {
    //                 fputcsv($handle, $row, "\t");
    //             }
    //         }

    //         fclose($handle);
    //     }, $filename . '.xls', [
    //         'Content-Type' => 'application/vnd.ms-excel',
    //         'Content-Disposition' => 'attachment; filename="' . $filename . '.xls"',
    //     ]);
    // }


    // Dans SubscriptionController.php


    // private function generateExcelExport(array $data, string $filename)
    // {
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // En-tête du document
    //     $sheet->setCellValue('A1', 'RAPPORT DES SOUSCRIPTIONS');
    //     $sheet->mergeCells('A1:K1');
    //     $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    //     $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //     $sheet->getStyle('A1')->getFill()
    //         ->setFillType(Fill::FILL_SOLID)
    //         ->getStartColor()->setRGB('1e40af');
    //     $sheet->getStyle('A1')->getFont()->getColor()->setRGB('FFFFFF');
    //     $sheet->getRowDimension(1)->setRowHeight(30);

    //     // Statistiques (ligne 3)
    //     $totalSouscrit = array_sum(array_column($data, 'Montant souscrit'));
    //     $totalPaye = array_sum(array_column($data, 'Montant payé'));
    //     $progressionMoyenne = count($data) > 0 ? array_sum(array_column($data, 'Progression (%)')) / count($data) : 0;

    //     $sheet->setCellValue('A3', 'Total Souscrit:');
    //     $sheet->setCellValue('B3', number_format($totalSouscrit, 0, ',', ' ') . ' FCFA');
    //     $sheet->setCellValue('D3', 'Total Payé:');
    //     $sheet->setCellValue('E3', number_format($totalPaye, 0, ',', ' ') . ' FCFA');
    //     $sheet->setCellValue('G3', 'Progression Moy.:');
    //     $sheet->setCellValue('H3', number_format($progressionMoyenne, 1) . '%');

    //     // Style des statistiques
    //     $sheet->getStyle('A3:H3')->getFont()->setBold(true);
    //     $sheet->getStyle('A3:H3')->getFill()
    //         ->setFillType(Fill::FILL_SOLID)
    //         ->getStartColor()->setRGB('f8fafc');

    //     // En-têtes du tableau (ligne 5)
    //     $headers = array_keys($data[0]);
    //     $col = 'A';
    //     foreach ($headers as $header) {
    //         $sheet->setCellValue($col . '5', $header);
    //         $col++;
    //     }

    //     // Style des en-têtes
    //     $sheet->getStyle('A5:K5')->getFont()->setBold(true);
    //     $sheet->getStyle('A5:K5')->getFill()
    //         ->setFillType(Fill::FILL_SOLID)
    //         ->getStartColor()->setRGB('f3f4f6');
    //     $sheet->getStyle('A5:K5')->getBorders()->getAllBorders()
    //         ->setBorderStyle(Border::BORDER_THIN);
    //     $sheet->getStyle('A5:K5')->getAlignment()
    //         ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    //     // Données
    //     $row = 6;
    //     foreach ($data as $item) {
    //         $col = 'A';
    //         foreach ($item as $key => $value) {
    //             $sheet->setCellValue($col . $row, $value);

    //             // Colorisation selon progression
    //             if ($key === 'Progression (%)') {
    //                 $progression = floatval($value);
    //                 $color = $progression >= 100 ? 'd1fae5' :
    //                     ($progression >= 75 ? 'fef3c7' :
    //                         ($progression >= 50 ? 'dbeafe' :
    //                             ($progression >= 25 ? 'fef9c3' : 'fee2e2')));

    //                 $sheet->getStyle($col . $row)->getFill()
    //                     ->setFillType(Fill::FILL_SOLID)
    //                     ->getStartColor()->setRGB($color);
    //             }

    //             $col++;
    //         }

    //         // Alternance de couleurs
    //         if ($row % 2 === 0) {
    //             $sheet->getStyle('A' . $row . ':K' . $row)->getFill()
    //                 ->setFillType(Fill::FILL_SOLID)
    //                 ->getStartColor()->setRGB('f9fafb');
    //         }

    //         $row++;
    //     }

    //     // Bordures pour toutes les cellules de données
    //     $lastRow = $row - 1;
    //     $sheet->getStyle('A5:K' . $lastRow)->getBorders()->getAllBorders()
    //         ->setBorderStyle(Border::BORDER_THIN);

    //     // Ajuster la largeur des colonnes
    //     foreach (range('A', 'K') as $col) {
    //         $sheet->getColumnDimension($col)->setAutoSize(true);
    //     }

    //     // Téléchargement
    //     $writer = new Xlsx($spreadsheet);

    //     return response()->streamDownload(function () use ($writer) {
    //         $writer->save('php://output');
    //     }, $filename . '.xlsx', [
    //         'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    //     ]);
    // }


    /**
 * Génère un export Excel
 */
private function generateExcelExport(array $data, string $filename)
{
    $AppParametres = Parametres::first();

    $html = view('exports.subscriptions.liste-excel-html', compact('data', 'AppParametres'))->render();

    return response($html, 200, [
        'Content-Type' => 'application/vnd.ms-excel',
        'Content-Disposition' => 'attachment; filename="' . $filename . '.xls"',
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0'
    ]);
}

    /**
     * Génère un export PDF
     */
    private function generatePdfExport(array $data, string $filename)
    {
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return response()->json([
                'success' => false,
                'message' => 'PDF export non disponible',
            ], 500);
        }

        try {
            $pdf = Pdf::loadView('exports.subscriptions.liste-souscripteur-pdf', compact('data'));

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $filename . '.pdf', [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'PDF export non disponible',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API pour recherche de souscriptions
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $limit = min($request->get('limit', 10), 50);

            if (strlen($query) < 2) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => [],
                        'suggestions' => $this->getSearchSuggestions()
                    ]);
                }
                return redirect()->back();
            }

            $results = Subscription::with(['souscripteur:id,nom', 'fimeco:id,nom'])
                ->whereHas('souscripteur', function ($q) use ($query) {
                    $q->where('nom', 'ILIKE', "%{$query}%")
                        ->orWhere('email', 'ILIKE', "%{$query}%");
                })
                ->orWhereHas('fimeco', function ($q) use ($query) {
                    $q->where('nom', 'ILIKE', "%{$query}%");
                })
                ->limit($limit)
                ->get();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $results,
                    'count' => $results->count()
                ]);
            }

            return view('components.private.subscriptions.search-results', compact('results'));

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur de recherche',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur de recherche');
        }
    }

    /**
     * Suggestions de recherche
     */
    private function getSearchSuggestions(): array
    {
        return Cache::remember('subscription_search_suggestions', 3600, function () {
            return [
                'souscripteurs_actifs' => User::whereHas('subscriptions')
                    ->limit(5)
                    ->pluck('nom')
                    ->toArray(),
                'statuts_disponibles' => ['inactive', 'partiellement_payee', 'completement_payee'],
                'statuts_globaux' => ['tres_faible', 'en_cours', 'presque_atteint', 'objectif_atteint']
            ];
        });
    }

    /**
     * Statistiques en temps réel
     */
    public function liveStats(): JsonResponse
    {
        try {
            $stats = Cache::remember('subscription_live_stats', 60, function () {
                return [
                    'souscriptions_actives' => Subscription::actives()->count(),
                    'nouvelles_souscriptions_aujourd_hui' => Subscription::whereDate('created_at', today())->count(),
                    'paiements_aujourd_hui' => SubscriptionPayment::aujourdhui()
                        ->where('statut', 'valide')
                        ->sum('montant'),
                    'souscriptions_en_retard' => Subscription::enRetard()->count(),
                    'echeances_cette_semaine' => Subscription::echeanceProche(7)->count(),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats,
                'timestamp' => now()->toISOString()
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API pour mobile - version simplifiée
     */
    public function apiIndex(Request $request): JsonResponse
    {
        try {
            $userId = auth()->id();

            $subscriptions = Subscription::with(['fimeco:id,nom,progression'])
                ->where('souscripteur_id', $userId)
                ->select(['id', 'fimeco_id', 'montant_souscrit', 'montant_paye', 'reste_a_payer', 'progression', 'statut', 'date_echeance'])
                ->orderBy('date_souscription', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $subscriptions->items(),
                'pagination' => [
                    'current_page' => $subscriptions->currentPage(),
                    'total_pages' => $subscriptions->lastPage(),
                    'total_items' => $subscriptions->total(),
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur API',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validation des données de souscription
     */
    public function validateSubscriptionData(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'souscripteur_id' => 'required|exists:users,id',
                'fimeco_id' => 'required|exists:fimecos,id',
                'montant_souscrit' => 'required|numeric|min:1000',
                'date_souscription' => 'required|date|before_or_equal:today',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validations métier
            $businessValidation = $this->performSubscriptionBusinessValidation($request);

            return response()->json([
                'success' => true,
                'message' => 'Données valides',
                'warnings' => $businessValidation['warnings'] ?? [],
                'suggestions' => $businessValidation['suggestions'] ?? []
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validation métier pour souscription
     */
    private function performSubscriptionBusinessValidation(Request $request): array
    {
        $warnings = [];
        $suggestions = [];

        if ($request->fimeco_id) {
            $fimeco = Fimeco::find($request->fimeco_id);

            if ($fimeco) {
                // Vérifier si proche de la fin
                $joursRestants = now()->diffInDays($fimeco->fin, false);
                if ($joursRestants < 30) {
                    $warnings[] = "Ce FIMECO se termine dans {$joursRestants} jours";
                }

                // Vérifier la disponibilité
                $montantDisponible = $fimeco->getMontantDisponible();
                if ($request->montant_souscrit && $request->montant_souscrit > $montantDisponible * 0.8) {
                    $warnings[] = 'Le montant souscrit représente une part importante du montant restant disponible';
                }
            }
        }

        // Vérifier si l'utilisateur a déjà des souscriptions
        if ($request->souscripteur_id) {
            $existingSubscriptions = Subscription::where('souscripteur_id', $request->souscripteur_id)->count();
            if ($existingSubscriptions >= 5) {
                $warnings[] = 'Ce souscripteur a déjà beaucoup de souscriptions actives';
            }
        }

        return compact('warnings', 'suggestions');
    }






    /**
     * Vérifie si une souscription existe déjà pour un utilisateur et un FIMECO donnés
     */
    public function checkExists(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'souscripteur_id' => 'required|uuid|exists:users,id',
                'fimeco_id' => 'required|uuid|exists:fimecos,id',
            ], [
                'souscripteur_id.required' => 'L\'identifiant du souscripteur est requis',
                'souscripteur_id.uuid' => 'L\'identifiant du souscripteur doit être un UUID valide',
                'souscripteur_id.exists' => 'Le souscripteur spécifié n\'existe pas',
                'fimeco_id.required' => 'L\'identifiant du FIMECO est requis',
                'fimeco_id.uuid' => 'L\'identifiant du FIMECO doit être un UUID valide',
                'fimeco_id.exists' => 'Le FIMECO spécifié n\'existe pas',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Vérifier l'existence de la souscription
            $subscription = Subscription::where('souscripteur_id', $request->souscripteur_id)
                ->where('fimeco_id', $request->fimeco_id)
                ->whereNull('deleted_at')
                ->first();

            $exists = $subscription !== null;

            // Préparer les données de réponse
            $responseData = [
                'exists' => $exists,
                'message' => $exists
                    ? 'Une souscription existe déjà pour ce membre et ce FIMECO'
                    : 'Aucune souscription existante trouvée'
            ];

            // Si une souscription existe, ajouter des détails
            if ($exists) {
                $responseData['subscription_details'] = [
                    'id' => $subscription->id,
                    'statut' => $subscription->statut,
                    'statut_libelle' => $subscription->getStatutLibelle(),
                    'montant_souscrit' => $subscription->montant_souscrit,
                    'montant_paye' => $subscription->montant_paye,
                    'reste_a_payer' => $subscription->reste_a_payer,
                    'progression' => $subscription->progression,
                    'date_souscription' => $subscription->date_souscription->format('Y-m-d'),
                    'date_echeance' => $subscription->date_echeance?->format('Y-m-d'),
                    'created_at' => $subscription->created_at->format('Y-m-d H:i:s')
                ];

                // Ajouter des informations contextuelles
                $responseData['actions_possibles'] = $this->getPossibleActionsForExistingSubscription($subscription);
            }

            return response()->json([
                'success' => true,
                'data' => $responseData
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la vérification d\'existence de souscription', [
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la vérification',
                'error' => app()->environment('local') ? $e->getMessage() : 'Erreur interne du serveur'
            ], 500);
        }
    }

    /**
     * Détermine les actions possibles pour une souscription existante
     */
    private function getPossibleActionsForExistingSubscription(Subscription $subscription): array
    {
        $actions = [];

        // Action pour voir les détails
        $actions['voir_details'] = [
            'disponible' => true,
            'url' => route('private.subscriptions.show', $subscription->id),
            'libelle' => 'Voir les détails de la souscription existante'
        ];

        // Action pour effectuer un paiement
        $actions['effectuer_paiement'] = [
            'disponible' => $this->canMakePayment($subscription),
            'libelle' => $subscription->statut === 'completement_payee'
                ? 'Souscription déjà complètement payée'
                : 'Effectuer un paiement',
            'montant_restant' => $subscription->reste_a_payer
        ];

        // Action pour modifier la souscription
        $actions['modifier_souscription'] = [
            'disponible' => $this->canUpdateSubscription($subscription),
            'url' => $this->canUpdateSubscription($subscription)
                ? route('private.subscriptions.edit', $subscription->id)
                : null,
            'libelle' => 'Modifier la souscription'
        ];

        // Alertes spécifiques
        $actions['alertes'] = [];

        if ($subscription->en_retard) {
            $actions['alertes'][] = [
                'type' => 'warning',
                'message' => "Cette souscription est en retard de {$subscription->jours_retard} jour(s)"
            ];
        }

        if ($subscription->statut === 'inactive') {
            $actions['alertes'][] = [
                'type' => 'info',
                'message' => 'Cette souscription est actuellement inactive'
            ];
        }

        return $actions;
    }
}
