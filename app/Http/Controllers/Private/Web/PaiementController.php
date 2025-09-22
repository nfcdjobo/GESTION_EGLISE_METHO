<?php

namespace App\Http\Controllers\Private\Web;

use Exception;
use Carbon\Carbon;
use App\Models\User;
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
use Illuminate\Support\Facades\Validator;

class PaiementController extends Controller
{
    /**
     * Affiche la liste des paiements avec pagination et filtres
     */
    public function index(Request $request)
    {
        try {


            $query = SubscriptionPayment::with([
                'subscription.souscripteur:id,nom,prenom,email,photo_profil',
                'subscription.fimeco:id,nom,statut',
                'validateur:id,nom,email'
            ]);

            // Filtres
            $this->applyFilters($query, $request);

            // Tri
            $this->applySorting($query, $request);

            // Pagination
            $perPage = min($request->get('per_page', 10), 100);
            /** @var \Illuminate\Pagination\LengthAwarePaginator $payments */
            $payments = $query->paginate($perPage);

            if ($request->expectsJson()) {
                $payments->getCollection()->transform(function ($payment) {
                    return $this->enrichPaymentData($payment);
                });

                return response()->json([
                    'success' => true,
                    'data' => $payments,
                    'meta' => [
                        'total' => $payments->total(),
                        'per_page' => $payments->perPage(),
                        'current_page' => $payments->currentPage(),
                        'last_page' => $payments->lastPage(),
                    ]
                ]);
            }

            $meta = [
                        'total' => $payments->total(),
                        'per_page' => $payments->perPage(),
                        'current_page' => $payments->currentPage(),
                        'last_page' => $payments->lastPage(),
            ];
            return view('components.private.paiements.index', compact('payments', 'meta'));

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des paiements',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des paiements']);
        }
    }

    /**
     * Affiche un paiement spécifique
     */
    public function show(Request $request, string $id)
    {
        try {
            $payment = SubscriptionPayment::with([
                'subscription.souscripteur:id,nom,prenom,email,photo_profil,telephone_1',
                'subscription.fimeco:id,nom,description,cible,montant_solde,progression',
                'validateur:id,nom,prenom,telephone_1,email'
            ])->findOrFail($id);

            $data = [
                'payment' => $this->enrichPaymentData($payment),
                'peut_valider' => $this->canValidatePayment($payment),
                'peut_rejeter' => $this->canRejectPayment($payment),
                'historique_validation' => $this->getValidationHistory($payment),
                'recepisse' => $payment->genererRecepisse(),
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return view('components.private.paiements.show', $data);

        } catch (Exception $e) {
            dd($e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paiement non trouvé',
                    'error' => $e->getMessage()
                ], 404);
            }

            return redirect()->route('private.paiements.index')
                ->withErrors(['error' => 'Paiement non trouvé']);
        }
    }

    /**
     * Méthode de validation modifiée pour accepter les paiements supplémentaires
     */
    public function valider(Request $request, string $id): JsonResponse
    {
        try {
            $payment = SubscriptionPayment::findOrFail($id);

            if (!$this->canValidatePayment($payment)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé à valider ce paiement'
                ], 403);
            }

            if ($payment->statut === 'valide') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce paiement est déjà validé'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'commentaire' => 'nullable|string|max:1000',
                'confirmer_paiement_supplementaire' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Vérification spéciale pour les paiements supplémentaires
            $estPaiementSupplementaire = $payment->est_paiement_supplementaire;
            if ($estPaiementSupplementaire && !$request->confirmer_paiement_supplementaire) {
                return response()->json([
                    'success' => false,
                    'message' => 'Confirmation requise pour valider un paiement supplémentaire',
                    'data' => [
                        'type' => 'confirmation_required',
                        'paiement_supplementaire' => true,
                        'montant_supplementaire' => $payment->montant_supplementaire_du_paiement,
                        'message_confirmation' => "Ce paiement inclut " .
                            number_format($payment->montant_supplementaire_du_paiement, 0, ',', ' ') .
                            " FCFA au-delà de la souscription initiale. Confirmez-vous la validation ?",
                        'action_required' => 'Ajoutez "confirmer_paiement_supplementaire": true pour confirmer'
                    ]
                ], 400);
            }

            DB::beginTransaction();

            $payment->statut = 'valide';
            $payment->validateur_id = auth()->id();
            $payment->date_validation = now();

            // Enrichissement du commentaire pour les paiements supplémentaires
            $commentaire = $request->commentaire;
            if ($estPaiementSupplementaire) {
                $commentaireSupplementaire = "Paiement supplémentaire validé - Montant au-delà de la souscription: " .
                    number_format($payment->montant_supplementaire_du_paiement, 0, ',', ' ') . " FCFA";
                $commentaire = $commentaire ?
                    $commentaire . " | " . $commentaireSupplementaire :
                    $commentaireSupplementaire;
            }

            $payment->commentaire = $commentaire;
            $payment->save();

            // Log de validation enrichi
            Log::info('Paiement validé', [
                'payment_id' => $payment->id,
                'subscription_id' => $payment->subscription_id,
                'montant' => $payment->montant,
                'validated_by' => auth()->id(),
                'validator_name' => auth()->user()->nom,
                'est_paiement_supplementaire' => $estPaiementSupplementaire,
                'montant_supplementaire' => $payment->montant_supplementaire_du_paiement,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $estPaiementSupplementaire ?
                    'Paiement supplémentaire validé avec succès' :
                    'Paiement validé avec succès',
                'data' => $this->enrichPaymentData($payment->fresh())
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Rejette un paiement
     */
    public function reject(Request $request, string $id): JsonResponse
    {
        try {
            $payment = SubscriptionPayment::findOrFail($id);

            if (!$this->canRejectPayment($payment)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé à rejeter ce paiement'
                ], 403);
            }

            if ($payment->statut === 'rejete') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce paiement est déjà rejeté'
                ], 400);
            }

            $validator = Validator::make($request->all(), [
                'commentaire' => 'required|string|max:1000',
            ], [
                'commentaire.required' => 'Un commentaire est obligatoire pour rejeter un paiement',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $payment->statut = 'rejete';
            $payment->save();

            // Log de rejet
            Log::info('Paiement rejeté', [
                'payment_id' => $payment->id,
                'subscription_id' => $payment->subscription_id,
                'montant' => $payment->montant,
                'rejected_by' => auth()->id(),
                'reason' => $request->commentaire
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Paiement rejeté avec succès',
                'data' => $this->enrichPaymentData($payment->fresh())
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rejet',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Annule une validation ou un rejet (retour en attente)
     */
    public function cancel(Request $request, string $id): JsonResponse
    {
        try {
            $payment = SubscriptionPayment::findOrFail($id);

            if (!$this->canCancelValidation($payment)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Non autorisé à annuler cette action'
                ], 403);
            }

            if ($payment->statut === 'en_attente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce paiement est déjà en attente'
                ], 400);
            }

            DB::beginTransaction();

            $oldStatus = $payment->statut;
            $payment->update([
                'statut' => 'en_attente',
                'validateur_id' => null,
                'date_validation' => null,
                'commentaire' => null,
            ]);

            // Log d'annulation
            Log::info('Validation/Rejet annulé', [
                'payment_id' => $payment->id,
                'old_status' => $oldStatus,
                'cancelled_by' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Action annulée avec succès - paiement remis en attente',
                'data' => $this->enrichPaymentData($payment->fresh())
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard des paiements avec statistiques
     */
    public function dashboard(Request $request)
    {
        try {
            $cacheKey = 'payments_dashboard_' . auth()->id();

            $data = Cache::remember($cacheKey, 300, function () {
                return [
                    'statistiques_globales' => $this->getStatistiquesGlobales(),
                    'paiements_en_attente' => $this->getPaiementsEnAttente(),
                    'performance_validation' => $this->getPerformanceValidation(),
                    'evolution_mensuelle' => $this->getEvolutionMensuelle(),
                    'repartition_types' => $this->getRepartitionTypes(),
                    'alertes' => $this->getAlertes(),
                ];
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return view('components.private.payments.dashboard', $data);

        } catch (Exception $e) {
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
     * Validation en lot des paiements
     */
    public function batchValidate(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'payment_ids' => 'required|array|min:1|max:50',
                'payment_ids.*' => 'exists:subscription_payments,id',
                'commentaire' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            $payments = SubscriptionPayment::whereIn('id', $request->payment_ids)
                ->where('statut', 'en_attente')
                ->get();

            if ($payments->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun paiement en attente trouvé'
                ], 400);
            }

            // Vérifier les permissions pour chaque paiement
            $unauthorizedCount = 0;
            $validatedCount = 0;

            DB::beginTransaction();

            foreach ($payments as $payment) {
                if (!$this->canValidatePayment($payment)) {
                    $unauthorizedCount++;
                    continue;
                }

                $payment->update([
                    'statut' => 'valide',
                    'validateur_id' => auth()->id(),
                    'date_validation' => now(),
                    'commentaire' => $request->commentaire,
                ]);

                $validatedCount++;
            }

            DB::commit();

            // Log de validation en lot
            Log::info('Validation en lot', [
                'validated_count' => $validatedCount,
                'unauthorized_count' => $unauthorizedCount,
                'validated_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Validation terminée : {$validatedCount} paiement(s) validé(s)" .
                    ($unauthorizedCount > 0 ? ", {$unauthorizedCount} non autorisé(s)" : ""),
                'data' => [
                    'validated_count' => $validatedCount,
                    'unauthorized_count' => $unauthorizedCount,
                ]
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la validation en lot',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère un reçu de paiement
     */
    public function recepisse(string $id, Request $request)
    {
        try {
            $payment = SubscriptionPayment::with([
                'subscription.souscripteur',
                'subscription.fimeco',
                'validateur'
            ])->findOrFail($id);

            if ($payment->statut !== 'valide') {
                return response()->json([
                    'success' => false,
                    'message' => 'Seuls les paiements validés peuvent générer un reçu'
                ], 400);
            }

            $format = $request->get('format', 'pdf');

            $receiptData = $payment->genererRecepisse();

            if ($format === 'json') {
                return response()->json([
                    'success' => true,
                    'data' => $receiptData
                ]);
            }

            // Génération PDF
            return $this->generateReceiptPdf($receiptData, $payment);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération du reçu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export des paiements
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'excel');

            $query = SubscriptionPayment::with(['subscription.souscripteur', 'subscription.fimeco', 'validateur']);
            $this->applyFilters($query, $request);

            $payments = $query->get();

            $data = $payments->map(function ($payment) {
                return [
                    'ID' => $payment->id,
                    'Souscripteur' => $payment->subscription->souscripteur->nom ?? 'N/A',
                    'FIMECO' => $payment->subscription->fimeco->nom ?? 'N/A',
                    'Montant' => $payment->montant,
                    'Type paiement' => $payment->getTypePaiementLibelle(),
                    'Référence' => $payment->reference_paiement,
                    'Date paiement' => $payment->date_paiement->format('d/m/Y H:i'),
                    'Statut' => $payment->getStatutLibelle(),
                    'Validateur' => $payment->validateur->nom ?? 'N/A',
                    'Date validation' => $payment->date_validation?->format('d/m/Y H:i'),
                    'Commentaire' => $payment->commentaire,
                ];
            });

            $data = $data instanceof Collection ? $data->toArray() : $data;

            if ($format === 'csv') {
                return $this->generateCsvExport($data, 'paiements_' . now()->format('Y-m-d'));
            } elseif ($format === 'pdf') {
                return $this->generatePdfExport($data, 'paiements_' . now()->format('Y-m-d'));
            } else {
                return $this->generateExcelExport($data, 'paiements_' . now()->format('Y-m-d'));
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

        if ($request->filled('type_paiement')) {
            $query->where('type_paiement', $request->type_paiement);
        }

        if ($request->filled('validateur_id')) {
            $query->where('validateur_id', $request->validateur_id);
        }

        if ($request->filled('date_debut')) {
            $query->where('date_paiement', '>=', $request->date_debut);
        }

        if ($request->filled('date_fin')) {
            $query->where('date_paiement', '<=', $request->date_fin . ' 23:59:59');
        }

        if ($request->filled('montant_min')) {
            $query->where('montant', '>=', $request->montant_min);
        }

        if ($request->filled('montant_max')) {
            $query->where('montant', '<=', $request->montant_max);
        }

        if ($request->filled('fimeco_id')) {
            $query->whereHas('subscription', function ($q) use ($request) {
                $q->where('fimeco_id', $request->fimeco_id);
            });
        }

        if ($request->filled('souscripteur_id')) {
            $query->whereHas('subscription', function ($q) use ($request) {
                $q->where('souscripteur_id', $request->souscripteur_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_paiement', 'ILIKE', "%{$search}%")
                    ->orWhereHas('subscription.souscripteur', function ($sq) use ($search) {
                        $sq->where('nom', 'ILIKE', "%{$search}%")
                            ->orWhere('prenom', 'ILIKE', "%{$search}%")
                            ->orWhere('email', 'ILIKE', "%{$search}%");
                    })
                    ->orWhereHas('subscription.fimeco', function ($sq) use ($search) {
                        $sq->where('nom', 'ILIKE', "%{$search}%");
                    });
            });
        }
    }

    /**
     * Applique le tri à la requête
     */
    private function applySorting($query, Request $request): void
    {
        $sortBy = $request->get('sort_by', 'date_paiement');
        $sortDirection = $request->get('sort_direction', 'desc');

        $allowedSorts = [
            'date_paiement',
            'montant',
            'type_paiement',
            'statut',
            'date_validation',
            'created_at'
        ];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }
    }





    /**
     * Méthode privée modifiée pour enrichir les données de paiement
     */
    private function enrichPaymentData(SubscriptionPayment $payment): array
    {
        return array_merge($payment->toArray(), [
            'type_paiement_libelle' => $payment->getTypePaiementLibelle(),
            'statut_libelle' => $payment->getStatutLibelle(),
            'delai_validation' => $payment->delai_validation_heures,
            'peut_etre_valide' => $payment->peutEtreModifie(),
            'peut_etre_rejete' => $payment->peutEtreModifie(),
            'peut_etre_annule' => !$payment->peutEtreSupprime(),
            'montant_formatte' => $payment->montant_formatte,
            'age_jours' => $payment->age_jours,
            'est_valide' => $payment->est_valide,
            'est_en_attente' => $payment->est_en_attente,
            'est_rejete' => $payment->est_rejete,
            'infos_validation' => $payment->getInfosValidation(),
            // Nouvelles informations pour les paiements supplémentaires
            'est_paiement_supplementaire' => $payment->est_paiement_supplementaire,
            'montant_supplementaire_du_paiement' => $payment->montant_supplementaire_du_paiement,
            'impact_sur_fimeco' => $this->getImpactPaiementSurFimeco($payment),
        ]);
    }


    /**
     * Nouvelle méthode pour calculer l'impact d'un paiement sur le FIMECO
     */
    private function getImpactPaiementSurFimeco(SubscriptionPayment $payment): array
    {
        if (!$payment->subscription || !$payment->subscription->fimeco) {
            return [];
        }

        $fimeco = $payment->subscription->fimeco;

        return [
            'contribue_au_depassement_objectif' => $payment->est_paiement_supplementaire && $fimeco->progression >= 100,
            'pourcentage_contribution_fimeco' => $fimeco->cible > 0 ?
                round(($payment->montant / $fimeco->cible) * 100, 4) : 0,
            'impact_progression' => $fimeco->cible > 0 ?
                round(($payment->montant / $fimeco->cible) * 100, 2) : 0,
        ];
    }





    /**
     * Vérifie si l'utilisateur peut valider le paiement
     */
    private function canValidatePayment(SubscriptionPayment $payment): bool
    {
        if ($payment->statut !== 'en_attente') {
            return false;
        }

        /** @var User $user */
        $user = auth()->user();

        // Admin peut valider
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        // Responsable du FIMECO peut valider
        if (
            $payment->subscription &&
            $payment->subscription->fimeco &&
            $user->id === $payment->subscription->fimeco->responsable_id
        ) {
            return true;
        }

        // Vérification de permissions spécifiques
        if (method_exists($user, 'can') && $user->can('validate-payments')) {
            return true;
        }

        return false;
    }



    /**
     * Vérifie si l'utilisateur peut rejeter le paiement
     */
    private function canRejectPayment(SubscriptionPayment $payment): bool
    {
        return $this->canValidatePayment($payment); // Mêmes permissions que pour valider
    }

    // /**
    //  * Vérifie si l'utilisateur peut annuler une validation/rejet
    //  */
    // private function canCancelValidation(SubscriptionPayment $payment): bool
    // {
    //     if ($payment->statut === 'en_attente') {
    //         return false;
    //     }

    //     /**
    //      * @var User $user
    //      */
    //     $user = auth()->user();

    //     // Seul un admin peut annuler une validation/rejet
    //     if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
    //         return true;
    //     }

    //     // Le validateur original peut annuler dans les 24h
    //     if ($payment->validateur_id === $user->id &&
    //         $payment->date_validation &&
    //         $payment->date_validation->diffInHours(now()) <= 24) {
    //         return true;
    //     }

    //     return false;
    // }



    /**
     * Vérifie si l'utilisateur peut annuler une validation/rejet
     */
    private function canCancelValidation(SubscriptionPayment $payment): bool
    {
        if ($payment->statut === 'en_attente') {
            return false;
        }

        /** @var User $user */
        $user = auth()->user();

        // Seul un admin peut annuler une validation/rejet
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        // Le validateur original peut annuler dans les 24h
        if (
            $payment->validateur_id === $user->id &&
            $payment->date_validation &&
            $payment->date_validation->diffInHours(now()) <= 24
        ) {
            return true;
        }

        return false;
    }

    /**
     * Historique de validation d'un paiement
     */
    private function getValidationHistory(SubscriptionPayment $payment): array
    {
        // Cette méthode pourrait être étendue pour inclure un historique complet
        // Pour l'instant, on retourne les informations de validation actuelles
        return [
            'statut_actuel' => $payment->statut,
            'validateur' => $payment->validateur?->nom,
            'date_validation' => $payment->date_validation?->format('d/m/Y H:i'),
            'commentaire' => $payment->commentaire,
        ];
    }

    /**
     * Statistiques globales des paiements
     */
    private function getStatistiquesGlobales(): array
    {
        return [
            'total_paiements' => SubscriptionPayment::count(),
            'paiements_en_attente' => SubscriptionPayment::enAttente()->count(),
            'paiements_valides' => SubscriptionPayment::valides()->count(),
            'paiements_rejetes' => SubscriptionPayment::rejetes()->count(),
            'montant_total_valide' => SubscriptionPayment::valides()->sum('montant'),
            'montant_en_attente' => SubscriptionPayment::enAttente()->sum('montant'),
            'paiements_aujourd_hui' => SubscriptionPayment::aujourdhui()->count(),
            'montant_aujourd_hui' => SubscriptionPayment::aujourdhui()->sum('montant'),
        ];
    }

    /**
     * Paiements en attente de validation
     */
    private function getPaiementsEnAttente(): array
    {
        return SubscriptionPayment::with(['subscription.souscripteur', 'subscription.fimeco'])
            ->enAttente()
            ->orderBy('date_paiement', 'asc')
            ->limit(20)
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'souscripteur' => $payment->subscription->souscripteur->nom,
                    'fimeco' => $payment->subscription->fimeco->nom,
                    'montant' => $payment->montant,
                    'type_paiement' => $payment->getTypePaiementLibelle(),
                    'date_paiement' => $payment->date_paiement->format('d/m/Y H:i'),
                    'delai_attente' => $payment->date_paiement->diffForHumans(),
                ];
            })
            ->toArray();
    }

    /**
     * Performance de validation
     */
    private function getPerformanceValidation(): array
    {
        return [
            'delai_moyen_validation' => $this->calculateDelaiMoyenValidation(),
            'taux_validation' => $this->calculateTauxValidation(),
            'validations_par_validateur' => $this->getValidationsParValidateur(),
        ];
    }

    /**
     * Évolution mensuelle des paiements
     */
    private function getEvolutionMensuelle(): array
    {
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $data[] = [
                'mois' => $month->format('Y-m'),
                'nb_paiements' => SubscriptionPayment::whereYear('date_paiement', $month->year)
                    ->whereMonth('date_paiement', $month->month)
                    ->count(),
                'montant_total' => SubscriptionPayment::whereYear('date_paiement', $month->year)
                    ->whereMonth('date_paiement', $month->month)
                    ->where('statut', 'valide')
                    ->sum('montant'),
                'nb_valides' => SubscriptionPayment::whereYear('date_validation', $month->year)
                    ->whereMonth('date_validation', $month->month)
                    ->where('statut', 'valide')
                    ->count(),
            ];
        }
        return $data;
    }

    /**
     * Répartition par types de paiement
     */
    private function getRepartitionTypes(): array
    {
        return SubscriptionPayment::selectRaw('type_paiement, COUNT(*) as count, SUM(montant) as total')
            ->where('statut', 'valide')
            ->groupBy('type_paiement')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->type_paiement => [
                        'count' => $item->count,
                        'total' => $item->total,
                        'libelle' => (new SubscriptionPayment(['type_paiement' => $item->type_paiement]))
                            ->getTypePaiementLibelle()
                    ]
                ];
            })
            ->toArray();
    }

    /**
     * Alertes relatives aux paiements
     */
    private function getAlertes(): array
    {
        $alertes = [];

        // Paiements en attente depuis longtemps
        $paiementsAnciens = SubscriptionPayment::enAttente()
            ->where('date_paiement', '<', now()->subDays(7))
            ->count();

        if ($paiementsAnciens > 0) {
            $alertes[] = [
                'type' => 'warning',
                'message' => "{$paiementsAnciens} paiement(s) en attente depuis plus de 7 jours",
                'count' => $paiementsAnciens
            ];
        }

        // Montant important en attente
        $montantEnAttente = SubscriptionPayment::enAttente()->sum('montant');
        if ($montantEnAttente > 500000) { // Seuil configurable
            $alertes[] = [
                'type' => 'info',
                'message' => number_format($montantEnAttente, 0, ',', ' ') . ' FCFA en attente de validation',
                'count' => 1
            ];
        }

        return $alertes;
    }

    // Méthodes de calcul pour les statistiques

    private function calculateDelaiMoyenValidation(): float
    {
        $payments = SubscriptionPayment::where('statut', 'valide')
            ->whereNotNull('date_validation')
            ->whereDate('date_validation', '>=', now()->subDays(30))
            ->get();

        if ($payments->isEmpty()) {
            return 0;
        }

        $totalHeures = 0;
        foreach ($payments as $payment) {
            $totalHeures += $payment->date_paiement->diffInHours($payment->date_validation);
        }

        return round($totalHeures / $payments->count(), 1);
    }

    private function calculateTauxValidation(): float
    {
        $totalPaiements = SubscriptionPayment::whereDate('created_at', '>=', now()->subDays(30))->count();
        if ($totalPaiements === 0)
            return 0;

        $paiementsValides = SubscriptionPayment::where('statut', 'valide')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->count();

        return round(($paiementsValides / $totalPaiements) * 100, 2);
    }

    private function getValidationsParValidateur(): array
    {
        return SubscriptionPayment::with('validateur:id,nom')
            ->where('statut', 'valide')
            ->whereDate('date_validation', '>=', now()->subDays(30))
            ->selectRaw('validateur_id, COUNT(*) as count, SUM(montant) as total')
            ->groupBy('validateur_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'validateur' => $item->validateur->nom ?? 'N/A',
                    'count' => $item->count,
                    'total' => $item->total,
                ];
            })
            ->toArray();
    }

    // Méthodes d'export

    private function generateReceiptPdf(array $receiptData, SubscriptionPayment $payment)
    {
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return response()->json([
                'success' => false,
                'message' => 'Le package DomPDF n\'est pas installé',
            ], 500);
        }

        try {
            $pdf = Pdf::loadView('exports.payments.recepisse-pdf', compact('receiptData', 'payment'));

            $filename = 'recepisse_' . $payment->id . '_' . now()->format('Y-m-d') . '.pdf';

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

    private function generateExcelExport(array $data, string $filename)
    {
        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF");

            if (!empty($data)) {
                fputcsv($handle, array_keys($data[0]), "\t");
                foreach ($data as $row) {
                    fputcsv($handle, $row, "\t");
                }
            }

            fclose($handle);
        }, $filename . '.xls', [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xls"',
        ]);
    }

    private function generatePdfExport(array $data, string $filename)
    {
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return response()->json([
                'success' => false,
                'message' => 'PDF export non disponible',
            ], 500);
        }

        try {
            $pdf = Pdf::loadView('exports.payments.liste-pdf', compact('data'));

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
     * API pour recherche de paiements
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

            $results = SubscriptionPayment::with(['subscription.souscripteur:id,nom', 'subscription.fimeco:id,nom'])
                ->where(function ($q) use ($query) {
                    $q->where('reference_paiement', 'ILIKE', "%{$query}%")
                        ->orWhereHas('subscription.souscripteur', function ($sq) use ($query) {
                            $sq->where('nom', 'ILIKE', "%{$query}%")
                                ->orWhere('email', 'ILIKE', "%{$query}%");
                        })
                        ->orWhereHas('subscription.fimeco', function ($sq) use ($query) {
                            $sq->where('nom', 'ILIKE', "%{$query}%");
                        });
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

            return view('components.private.payments.search-results', compact('results', 'query'));

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
        return Cache::remember('payment_search_suggestions', 3600, function () {
            return [
                'types_paiement' => ['especes', 'cheque', 'virement', 'carte', 'mobile_money'],
                'statuts_disponibles' => ['en_attente', 'valide', 'rejete'],
                'references_recentes' => SubscriptionPayment::whereNotNull('reference_paiement')
                    ->limit(5)
                    ->pluck('reference_paiement')
                    ->toArray(),
            ];
        });
    }

    /**
     * Statistiques en temps réel
     */
    public function liveStats(): JsonResponse
    {
        try {
            $stats = Cache::remember('payment_live_stats', 60, function () {
                return [
                    'paiements_en_attente' => SubscriptionPayment::enAttente()->count(),
                    'paiements_aujourd_hui' => SubscriptionPayment::aujourdhui()->count(),
                    'montant_aujourd_hui' => SubscriptionPayment::aujourdhui()->where('statut', 'valide')->sum('montant'),
                    'validations_en_cours' => SubscriptionPayment::enAttente()
                        ->where('date_paiement', '>=', now()->subHours(24))
                        ->count(),
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
            $query = SubscriptionPayment::with(['subscription.fimeco:id,nom'])
                ->select(['id', 'subscription_id', 'montant', 'type_paiement', 'statut', 'date_paiement']);

            // Si l'utilisateur n'est pas admin, ne montrer que les paiements liés à ses souscriptions
            /**
             * @var User $user
             */
            $user = auth()->user();
            if (!method_exists($user, 'hasRole') || !$user->hasRole('admin')) {
                $query->whereHas('subscription', function ($q) use ($user) {
                    $q->where('souscripteur_id', $user->id);
                });
            }

            $payments = $query->orderBy('date_paiement', 'desc')->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $payments->items(),
                'pagination' => [
                    'current_page' => $payments->currentPage(),
                    'total_pages' => $payments->lastPage(),
                    'total_items' => $payments->total(),
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
     * Validation des données de paiement modifiée
     */
    public function validatePaymentData(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'subscription_id' => 'required|exists:subscriptions,id',
                'montant' => 'required|numeric|min:1|max:10000000', // Limite haute de sécurité
                'type_paiement' => 'required|in:especes,cheque,virement,carte,mobile_money',
                'date_paiement' => 'required|date|before_or_equal:now',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validations métier modifiées
            $businessValidation = $this->performPaymentBusinessValidation($request);

            return response()->json([
                'success' => true,
                'message' => 'Données valides',
                'warnings' => $businessValidation['warnings'] ?? [],
                'suggestions' => $businessValidation['suggestions'] ?? [],
                'impact_paiement' => $businessValidation['impact_paiement'] ?? null,
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
     * Validation métier pour paiement modifiée
     */
    private function performPaymentBusinessValidation(Request $request): array
    {
        $warnings = [];
        $suggestions = [];
        $impactPaiement = null;

        if ($request->subscription_id) {
            $subscription = Subscription::find($request->subscription_id);

            if ($subscription && $request->montant) {
                // Calcul de l'impact du paiement
                $impactPaiement = $subscription->getImpactPaiement($request->montant);

                // Avertissement pour paiement supplémentaire
                if ($impactPaiement['montant_supplementaire'] > 0) {
                    $warnings[] = 'Ce paiement inclut ' .
                        number_format($impactPaiement['montant_supplementaire'], 0, ',', ' ') .
                        ' FCFA au-delà de la souscription initiale';

                    $suggestions[] = 'Assurez-vous que le souscripteur souhaite effectuer ce paiement supplémentaire';
                }

                // Vérifier si c'est un gros paiement
                if ($request->montant > 100000) {
                    $suggestions[] = 'Paiement important - vérifiez bien les références et la volonté du souscripteur';
                }

                // Vérifier la cohérence du type de paiement avec le montant
                if ($request->type_paiement === 'especes' && $request->montant > 50000) {
                    $warnings[] = 'Montant important en espèces - considérez un autre moyen de paiement';
                }

                // Alerte sur dépassement important
                if ($impactPaiement['taux_depassement'] > 100) {
                    $warnings[] = 'Attention: ce paiement porte le total à plus du double de la souscription initiale';
                }

                // Suggestions de montants alternatifs
                if ($impactPaiement['montant_supplementaire'] > 0) {
                    $suggestions[] = 'Montant suggéré pour compléter uniquement la souscription: ' .
                        number_format($impactPaiement['montant_pour_base'], 0, ',', ' ') . ' FCFA';
                }
            }
        }

        return compact('warnings', 'suggestions', 'impact_paiement');
    }




    /**
     * Nouvelle méthode : Statistiques sur les paiements supplémentaires
     */
    public function statistiquesPaiementsSupplementaires(Request $request): JsonResponse
    {
        try {
            $query = SubscriptionPayment::where('statut', 'valide')
                ->whereHas('subscription', function ($q) {
                    $q->whereRaw('(
                    SELECT COALESCE(SUM(sp2.montant), 0)
                    FROM subscription_payments sp2
                    WHERE sp2.subscription_id = subscriptions.id
                    AND sp2.statut = \'valide\'
                ) > subscriptions.montant_souscrit');
                });

            // Filtres optionnels
            if ($request->filled('date_debut')) {
                $query->where('date_paiement', '>=', $request->date_debut);
            }
            if ($request->filled('date_fin')) {
                $query->where('date_paiement', '<=', $request->date_fin);
            }
            if ($request->filled('fimeco_id')) {
                $query->whereHas('subscription', function ($q) use ($request) {
                    $q->where('fimeco_id', $request->fimeco_id);
                });
            }

            $paiementsSupplementaires = $query->with([
                'subscription.souscripteur:id,nom',
                'subscription.fimeco:id,nom'
            ])->get();

            $statistiques = [
                'nombre_total' => $paiementsSupplementaires->count(),
                'montant_total_supplementaire' => $paiementsSupplementaires->sum('montant_supplementaire_du_paiement'),
                'montant_moyen_supplementaire' => $paiementsSupplementaires->avg('montant_supplementaire_du_paiement') ?? 0,
                'repartition_par_fimeco' => $paiementsSupplementaires->groupBy('subscription.fimeco.nom')
                    ->map(function ($group) {
                        return [
                            'nombre' => $group->count(),
                            'montant_total' => $group->sum('montant_supplementaire_du_paiement'),
                        ];
                    }),
                'repartition_par_type' => $paiementsSupplementaires->groupBy('type_paiement')
                    ->map(function ($group) {
                        return [
                            'nombre' => $group->count(),
                            'montant_total' => $group->sum('montant_supplementaire_du_paiement'),
                        ];
                    }),
                'top_contributeurs' => $paiementsSupplementaires->groupBy('subscription.souscripteur.nom')
                    ->map(function ($group) {
                        return [
                            'nombre_paiements' => $group->count(),
                            'montant_total_supplementaire' => $group->sum('montant_supplementaire_du_paiement'),
                        ];
                    })
                    ->sortByDesc('montant_total_supplementaire')
                    ->take(10),
            ];

            return response()->json([
                'success' => true,
                'data' => $statistiques,
                'periode' => [
                    'debut' => $request->date_debut ?? 'Toutes les données',
                    'fin' => $request->date_fin ?? 'Toutes les données'
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
