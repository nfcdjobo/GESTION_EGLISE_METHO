<?php

namespace App\Http\Controllers\Private\Web;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Fimeco;
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

class FimecoController extends Controller
{
    /**
     * Affiche la liste des FIMECOs avec pagination et filtres
     */
    public function index(Request $request)
    {
        try {
            $query = Fimeco::with(['responsable:id,nom,email']);

            // Filtres
            $this->applyFilters($query, $request);

            // Tri
            $this->applySorting($query, $request);

            // Pagination
            $perPage = min($request->get('per_page', 15), 100);
            $fimecos = $query->paginate($perPage);

            // Enrichissement des données


            if ($request->expectsJson()) {
                /** @var \Illuminate\Pagination\LengthAwarePaginator $fimecos */
            $fimecos->getCollection()->transform(function ($fimeco) {
                return $this->enrichFimecoData($fimeco);
            });

                // Retour JSON pour les appels API
                return response()->json([
                    'success' => true,
                    'data' => $fimecos,
                    'meta' => [
                        'total' => $fimecos->total(),
                        'per_page' => $fimecos->perPage(),
                        'current_page' => $fimecos->currentPage(),
                        'last_page' => $fimecos->lastPage(),
                    ]
                ]);
            }

            // Retour Blade pour la vue
            return view('components.private.fimecos.index', compact('fimecos'));

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des FIMECOs',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la récupération des FIMECOs']);
        }
    }

    /**
     * Affiche un FIMECO spécifique avec toutes ses informations
     */
    public function show(Request $request, string $id)
    {
        try {
            $fimeco = Fimeco::with([
                'responsable:id,nom,email,telephone',
                'subscriptions.souscripteur:id,nom,email',
                'subscriptions.payments' => function ($query) {
                    $query->latest('date_paiement')->limit(5);
                }
            ])->findOrFail($id);

            // Statistiques détaillées
            $statistiques = $fimeco->getStatistiques();

            // Paiements en attente
            $paiementsEnAttente = $fimeco->getPaiementsEnAttente();

            // Souscriptions en retard
            $souscriptionsEnRetard = $fimeco->subscriptions()
                ->enRetard()
                ->with('souscripteur:id,nom,email')
                ->get();

            // Évolution mensuelle des paiements
            $evolutionMensuelle = $this->getEvolutionMensuelle($fimeco);

            $data = [
                'fimeco' => $this->enrichFimecoData($fimeco),
                'statistiques' => $statistiques,
                'paiements_en_attente' => $paiementsEnAttente,
                'souscriptions_en_retard' => $souscriptionsEnRetard,
                'evolution_mensuelle' => $evolutionMensuelle,
                'souscriptions_recentes' => $fimeco->subscriptions()
                    ->with('souscripteur:id,nom')
                    ->latest('date_souscription')
                    ->limit(10)
                    ->get(),
            ];

            if ($request->expectsJson()) {
                // Retour JSON pour les appels API
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            // Retour Blade pour la vue
            return view('components.private.fimecos.show', $data);

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'FIMECO non trouvé',
                    'error' => $e->getMessage()
                ], 404);
            }

            return redirect()->route('private.fimecos.index')
                ->withErrors(['error' => 'FIMECO non trouvé']);
        }
    }

    /**
     * Affiche le formulaire de création
     */
    public function create(Request $request)
    {
        $responsables = User::whereHas('roles', function ($query) {
                $query->whereIn('slug', ['regisseur']);
            })
                ->orderBy('nom')
                ->get();
        if ($request->expectsJson()) {
            // Pour les API, retourner les données nécessaires au formulaire


            return response()->json([
                'success' => true,
                'data' => [
                    'responsables' => $responsables,
                    'statuts' => ['active', 'inactive'],
                ]
            ]);
        }



        return view('components.private.fimecos.create', compact('responsables'));
    }

    /**
     * Crée un nouveau FIMECO
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100|unique:fimecos,nom',
            'description' => 'nullable|string',
            'debut' => 'required|date|after_or_equal:' . now()->subYear()->toDateString() . '|before_or_equal:' . now()->toDateString(),

            'fin' => 'required|date|after:debut',
            'cible' => 'required|numeric|min:1',
            'responsable_id' => 'nullable|exists:users,id',
        ], [
            'nom.required' => 'Le nom du FIMECO est obligatoire',
            'nom.unique' => 'Ce nom de FIMECO existe déjà',
            'debut.after_or_equal' => 'La date de début ne peut pas être antérieure au ' . now()->subYear()->format('d/m/Y'),
            'debut.before_or_equal' => 'La date de début ne peut pas être postérieure à aujourd\'hui',
            'fin.after' => 'La date de fin doit être postérieure à la date de début',
            'cible.min' => 'La cible doit être supérieure à zéro',
        ]);

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

        try {
            DB::beginTransaction();

            $validator = $validator->validated();
            $validator["responsable_id"] = auth()->id();

            $validator["status"] = "inactive";

            $fimeco = Fimeco::create($validator);

            // Log de création
            Log::info('FIMECO créé', [
                'fimeco_id' => $fimeco->id,
                'nom' => $fimeco->nom,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->nom ?? auth()->user()->name
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'FIMECO créé avec succès',
                    'data' => $this->enrichFimecoData($fimeco->load('responsable'))
                ], 201);
            }

            return redirect()->route('private.fimecos.show', $fimeco->id)
                ->with('success', 'FIMECO créé avec succès');

        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du FIMECO',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Erreur lors de la création du FIMECO'])
                ->withInput();
        }
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Request $request, string $id)
    {
        try {
            $fimeco = Fimeco::with('responsable')->findOrFail($id);

            // Vérification des permissions
            if (!$this->canUpdateFimeco($fimeco)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Non autorisé à modifier ce FIMECO'
                    ], 403);
                }

                return redirect()->route('private.fimecos.index')
                    ->withErrors(['error' => 'Non autorisé à modifier ce FIMECO']);
            }

            if ($request->expectsJson()) {
                $responsables = User::where('role', 'responsable')
                    ->orWhere('role', 'admin')
                    ->select('id', 'nom', 'email')
                    ->get();

                return response()->json([
                    'success' => true,
                    'data' => [
                        'fimeco' => $this->enrichFimecoData($fimeco),
                        'responsables' => $responsables,
                        'statuts' => ['active', 'inactive', 'cloturee'],
                    ]
                ]);
            }

            $responsables = User::where('role', 'responsable')
                ->orWhere('role', 'admin')
                ->get();

            return view('components.private.fimecos.edit', compact('fimeco', 'responsables'));

        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'FIMECO non trouvé',
                    'error' => $e->getMessage()
                ], 404);
            }

            return redirect()->route('private.fimecos.index')
                ->withErrors(['error' => 'FIMECO non trouvé']);
        }
    }

    /**
     * Met à jour un FIMECO
     */
    public function update(Request $request, string $id)
    {
        try {
            $fimeco = Fimeco::findOrFail($id);

            // Vérification des permissions
            if (!$this->canUpdateFimeco($fimeco)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Non autorisé à modifier ce FIMECO'
                    ], 403);
                }

                return back()->withErrors(['error' => 'Non autorisé à modifier ce FIMECO']);
            }

            $validator = Validator::make($request->all(), [
                'nom' => 'sometimes|string|max:100|unique:fimecos,nom,' . $id,
                'description' => 'nullable|string',
                'debut' => 'sometimes|date',
                'fin' => 'sometimes|date|after:debut',
                'cible' => 'sometimes|numeric|min:1',
                'responsable_id' => 'nullable|exists:users,id',
                'statut' => 'sometimes|in:active,inactive,cloturee',
            ]);

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

            // Vérifications métier avant mise à jour
            $this->validateBusinessRules($fimeco, $request);

            DB::beginTransaction();

            $oldData = $fimeco->toArray();
            $fimeco->update($request->validated());

            // Log des modifications
            $this->logChanges($fimeco, $oldData, $fimeco->toArray());

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'FIMECO mis à jour avec succès',
                    'data' => $this->enrichFimecoData($fimeco->load('responsable'))
                ]);
            }

            return redirect()->route('private.fimecos.show', $fimeco->id)
                ->with('success', 'FIMECO mis à jour avec succès');

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
     * Supprime un FIMECO (soft delete)
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $fimeco = Fimeco::findOrFail($id);

            // Vérifications avant suppression
            if (!$this->canDeleteFimeco($fimeco)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce FIMECO ne peut pas être supprimé car il contient des souscriptions actives'
                    ], 400);
                }

                return back()->withErrors(['error' => 'Ce FIMECO ne peut pas être supprimé']);
            }

            DB::beginTransaction();

            $fimeco->delete();

            // Log de suppression
            Log::info('FIMECO supprimé', [
                'fimeco_id' => $fimeco->id,
                'nom' => $fimeco->nom,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->nom ?? auth()->user()->name
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'FIMECO supprimé avec succès'
                ]);
            }

            return redirect()->route('private.fimecos.index')
                ->with('success', 'FIMECO supprimé avec succès');

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
     * Dashboard avec statistiques globales
     */
    public function dashboard(Request $request)
    {
        try {
            $cacheKey = 'fimeco_dashboard_' . auth()->id();

            $data = Cache::remember($cacheKey, 300, function () {
                return [
                    'statistiques_globales' => $this->getStatistiquesGlobales(),
                    'fimecos_urgents' => $this->getFimecosUrgents(),
                    'performance_mensuelle' => $this->getPerformanceMensuelle(),
                    'top_performeurs' => $this->getTopPerformeurs(),
                    'alertes' => $this->getAlertes(),
                ];
            });

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            // Retour Blade pour la vue
            return view('components.private.fimecos.dashboard', $data);

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
     * Clôture un FIMECO
     */
    public function cloture(string $id): JsonResponse
    {
        try {
            $fimeco = Fimeco::findOrFail($id);

            if ($fimeco->statut === 'cloturee') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce FIMECO est déjà clôturé'
                ], 400);
            }

            DB::beginTransaction();

            // Vérifications avant clôture
            $paiementsEnAttente = $fimeco->getPaiementsEnAttente();
            if ($paiementsEnAttente->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Impossible de clôturer : il reste des paiements en attente de validation'
                ], 400);
            }

            $fimeco->update([
                'statut' => 'cloturee',
                'fin' => now()->toDateString()
            ]);

            // Log de clôture
            Log::info('FIMECO clôturé', [
                'fimeco_id' => $fimeco->id,
                'nom' => $fimeco->nom,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->nom ?? auth()->user()->name
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'FIMECO clôturé avec succès',
                'data' => $this->enrichFimecoData($fimeco)
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la clôture',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Réouvre un FIMECO clôturé
     */
    public function reouvrir(string $id): JsonResponse
    {
        try {
            $fimeco = Fimeco::findOrFail($id);

            if ($fimeco->statut !== 'cloturee') {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce FIMECO n\'est pas clôturé'
                ], 400);
            }

            DB::beginTransaction();

            $fimeco->update([
                'statut' => 'active'
            ]);

            // Log de réouverture
            Log::info('FIMECO réouvert', [
                'fimeco_id' => $fimeco->id,
                'nom' => $fimeco->nom,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->nom ?? auth()->user()->name
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'FIMECO réouvert avec succès',
                'data' => $this->enrichFimecoData($fimeco)
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la réouverture',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Génère un rapport détaillé du FIMECO
     */
    public function rapport(string $id, Request $request): JsonResponse
    {
        try {
            $fimeco = Fimeco::with([
                'responsable',
                'subscriptions.souscripteur',
                'subscriptions.payments'
            ])->findOrFail($id);

            $format = $request->get('format', 'json'); // json, pdf, excel

            $rapport = [
                'informations_generales' => [
                    'nom' => $fimeco->nom,
                    'description' => $fimeco->description,
                    'responsable' => $fimeco->responsable?->nom,
                    'periode' => $fimeco->debut->format('d/m/Y') . ' - ' . $fimeco->fin->format('d/m/Y'),
                    'statut' => $fimeco->statut,
                    'date_creation' => $fimeco->created_at->format('d/m/Y H:i'),
                ],
                'objectifs_et_resultats' => [
                    'cible' => $fimeco->cible,
                    'montant_solde' => $fimeco->montant_solde,
                    'reste' => $fimeco->reste,
                    'montant_supplementaire' => $fimeco->montant_supplementaire,
                    'progression' => $fimeco->progression,
                    'statut_global' => $fimeco->statut_global,
                ],
                'statistiques_souscriptions' => $fimeco->getStatistiques(),
                'souscriptions_detail' => $fimeco->subscriptions->map(function ($subscription) {
                    return [
                        'souscripteur' => $subscription->souscripteur->nom,
                        'montant_souscrit' => $subscription->montant_souscrit,
                        'montant_paye' => $subscription->montant_paye,
                        'reste_a_payer' => $subscription->reste_a_payer,
                        'progression' => $subscription->progression,
                        'statut' => $subscription->statut,
                        'date_souscription' => $subscription->date_souscription->format('d/m/Y'),
                        'nb_paiements' => $subscription->payments->count(),
                    ];
                }),
                'paiements_detail' => $this->getPaiementsDetail($fimeco),
                'analyses' => [
                    'taux_reussite' => $this->calculateTauxReussite($fimeco),
                    'duree_moyenne_paiement' => $this->calculateDureeMoyennePaiement($fimeco),
                    'repartition_types_paiement' => $this->getRepartitionTypesPaiement($fimeco),
                ],
                'date_generation' => now()->format('d/m/Y H:i:s'),
            ];

            // Selon le format demandé
            if ($format === 'pdf') {
                // Génération PDF (nécessite une librairie comme DomPDF)
                return $this->generatePdfReport($rapport, $fimeco);
            } elseif ($format === 'excel') {
                // Génération Excel (nécessite Laravel Excel)
                return $this->generateExcelReport($rapport, $fimeco);
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
     * Exporte les données des FIMECOs
     */

    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'excel'); // excel, csv, pdf
            $filters = $request->get('filters', []);

            $query = Fimeco::with(['responsable', 'subscriptions']);
            $this->applyFilters($query, $request);

            $fimecos = $query->get();

            // Préparation des données d'export

            $data = $fimecos->map(function ($fimeco) {
                $stats = $fimeco->getStatistiques();
                return [
                    'Nom' => $fimeco->nom,
                    'Responsable' => $fimeco->responsable?->nom,
                    'Date début' => $fimeco->debut->format('d/m/Y'),
                    'Date fin' => $fimeco->fin->format('d/m/Y'),
                    'Cible' => $fimeco->cible,
                    'Montant soldé' => $fimeco->montant_solde,
                    'Reste' => $fimeco->reste,
                    'Progression (%)' => $fimeco->progression,
                    'Statut global' => $fimeco->statut_global,
                    'Statut' => $fimeco->statut,
                    'Nb souscriptions' => $stats['nb_souscriptions_total'],
                    'Nb souscriptions complètes' => $stats['nb_souscriptions_completes'],
                    'Date création' => $fimeco->created_at->format('d/m/Y H:i'),
                ];
            });

            $data = $data instanceof Collection ? $data->toArray() : $data;

            // Génération selon le format
            if ($format === 'csv') {
                return $this->generateCsvExport($data, 'fimecos_' . now()->format('Y-m-d'));
            } elseif ($format === 'pdf') {
                return $this->generatePdfExport($data, 'fimecos_' . now()->format('Y-m-d'));
            } else {
                return $this->generateExcelExport($data, 'fimecos_' . now()->format('Y-m-d'));
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
        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par statut global
        if ($request->filled('statut_global')) {
            $query->where('statut_global', $request->statut_global);
        }

        // Filtre par responsable
        if ($request->filled('responsable_id')) {
            $query->where('responsable_id', $request->responsable_id);
        }

        // Filtre par période
        if ($request->filled('date_debut')) {
            $query->where('debut', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->where('fin', '<=', $request->date_fin);
        }

        // Filtre par progression
        if ($request->filled('progression_min')) {
            $query->where('progression', '>=', $request->progression_min);
        }
        if ($request->filled('progression_max')) {
            $query->where('progression', '<=', $request->progression_max);
        }

        // Recherche textuelle
        if ($request->filled('search')) {
            $query->recherche($request->search);
        }

        // Filtre par montant cible
        if ($request->filled('cible_min')) {
            $query->where('cible', '>=', $request->cible_min);
        }
        if ($request->filled('cible_max')) {
            $query->where('cible', '<=', $request->cible_max);
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
            'nom', 'debut', 'fin', 'cible', 'montant_solde',
            'progression', 'statut_global', 'statut', 'created_at'
        ];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDirection);
        }
    }

    /**
     * Enrichit les données d'un FIMECO
     */
    private function enrichFimecoData(Fimeco $fimeco): array
    {
        return array_merge($fimeco->toArray(), [
            'progression_formatee' => $fimeco->progression_formatee,
            'objectif_atteint' => $fimeco->objectif_atteint,
            'jours_restants' => $fimeco->jours_restants,
            'en_retard' => $fimeco->en_retard,
            'peut_accepter_souscriptions' => $fimeco->peutAccepterNouvellesSouscriptions(),
            'montant_disponible' => $fimeco->getMontantDisponible(),
        ]);
    }

    /**
     * Vérifie si l'utilisateur peut modifier le FIMECO
     */
    private function canUpdateFimeco(Fimeco $fimeco): bool
    {
        /**
         * @var User $user
         */
        $user = auth()->user();

        // Vérifier si l'utilisateur est le responsable
        if ($user->id === $fimeco->responsable_id) {
            return true;
        }

        // Vérifier si l'utilisateur a le rôle admin (si vous utilisez Spatie Permission)
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        // Alternative sans package de permissions
        if (isset($user->role) && $user->role === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Vérifie si l'utilisateur peut supprimer le FIMECO
     */
    private function canDeleteFimeco(Fimeco $fimeco): bool
    {
        // Ne peut pas supprimer s'il y a des souscriptions actives
        if ($fimeco->subscriptions()->actives()->exists()) {
            return false;
        }

         /**
         * @var User $user
         */
        $user = auth()->user();

        // Vérifier si l'utilisateur a le rôle admin
        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        // Alternative sans package de permissions
        if (isset($user->role) && $user->role === 'admin') {
            return true;
        }

        return false;
    }

    /**
     * Valide les règles métier avant mise à jour
     */
    private function validateBusinessRules(Fimeco $fimeco, Request $request): void
    {
        // Si on change la cible et qu'elle devient inférieure au montant déjà soldé
        if ($request->filled('cible') && $request->cible < $fimeco->montant_solde) {
            throw new Exception('La nouvelle cible ne peut pas être inférieure au montant déjà soldé');
        }

        // Si on change les dates et qu'il y a des souscriptions hors période
        if ($request->filled('debut') || $request->filled('fin')) {
            $debut = $request->get('debut', $fimeco->debut);
            $fin = $request->get('fin', $fimeco->fin);

            $souscriptionsHorsPeriode = $fimeco->subscriptions()
                ->where(function ($query) use ($debut, $fin) {
                    $query->where('date_souscription', '<', $debut)
                          ->orWhere('date_souscription', '>', $fin);
                })
                ->exists();

            if ($souscriptionsHorsPeriode) {
                throw new Exception('Impossible de modifier les dates : des souscriptions existent en dehors de la nouvelle période');
            }
        }
    }

    /**
     * Log des modifications
     */
    private function logChanges(Fimeco $fimeco, array $oldData, array $newData): void
    {
        $changes = [];
        foreach (['nom', 'cible', 'debut', 'fin', 'statut', 'responsable_id'] as $field) {
            if (isset($oldData[$field]) && isset($newData[$field]) && $oldData[$field] !== $newData[$field]) {
                $changes[$field] = [
                    'old' => $oldData[$field],
                    'new' => $newData[$field]
                ];
            }
        }

        if (!empty($changes)) {
            Log::info('FIMECO modifié', [
                'fimeco_id' => $fimeco->id,
                'nom' => $fimeco->nom,
                'changes' => $changes,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->nom ?? auth()->user()->name
            ]);
        }
    }

    // Méthodes pour les statistiques et rapports...

    private function getStatistiquesGlobales(): array
    {
        return [
            'total_fimecos' => Fimeco::count(),
            'fimecos_actifs' => Fimeco::actifs()->count(),
            'objectifs_atteints' => Fimeco::objectifAtteint()->count(),
            'montant_total_cible' => Fimeco::sum('cible'),
            'montant_total_solde' => Fimeco::sum('montant_solde'),
            'progression_globale' => Fimeco::avg('progression'),
        ];
    }

    private function getFimecosUrgents(): array
    {
        return Fimeco::with('responsable')
            ->where(function ($query) {
                $query->where('fin', '<=', now()->addDays(30))
                      ->where('statut_global', '!=', 'objectif_atteint')
                      ->where('statut', 'active');
            })
            ->orWhere('statut_global', 'tres_faible')
            ->orderBy('fin')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function getPerformanceMensuelle(): array
    {
        // Récupération des données des 12 derniers mois
        $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $data[] = [
                'mois' => $month->format('Y-m'),
                'nouveaux_fimecos' => Fimeco::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'objectifs_atteints' => Fimeco::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->where('statut_global', 'objectif_atteint')
                    ->count(),
            ];
        }
        return $data;
    }

    private function getTopPerformeurs(): array
    {
        return Fimeco::with('responsable')
            ->where('progression', '>', 90)
            ->orderBy('progression', 'desc')
            ->limit(5)
            ->get()
            ->toArray();
    }

    private function getAlertes(): array
    {
        $alertes = [];

        // FIMECOs en retard
        $enRetard = Fimeco::where('fin', '<', now())
            ->where('statut_global', '!=', 'objectif_atteint')
            ->count();

        if ($enRetard > 0) {
            $alertes[] = [
                'type' => 'danger',
                'message' => "{$enRetard} FIMECO(s) en retard sans objectif atteint",
                'count' => $enRetard
            ];
        }

        // Paiements en attente
        $paiementsEnAttente = SubscriptionPayment::enAttente()->count();
        if ($paiementsEnAttente > 0) {
            $alertes[] = [
                'type' => 'warning',
                'message' => "{$paiementsEnAttente} paiement(s) en attente de validation",
                'count' => $paiementsEnAttente
            ];
        }

        return $alertes;
    }

    private function getEvolutionMensuelle(Fimeco $fimeco): array
    {
        // Évolution mensuelle des paiements pour ce FIMECO
        return DB::table('subscription_payments')
            ->join('subscriptions', 'subscription_payments.subscription_id', '=', 'subscriptions.id')
            ->where('subscriptions.fimeco_id', $fimeco->id)
            ->where('subscription_payments.statut', 'valide')
            ->selectRaw('
                DATE_TRUNC(\'month\', subscription_payments.date_paiement) as mois,
                SUM(subscription_payments.montant) as montant_total,
                COUNT(subscription_payments.id) as nb_paiements
            ')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->toArray();
    }

    private function getPaiementsDetail(Fimeco $fimeco): array
    {
        return SubscriptionPayment::whereHas('subscription', function ($query) use ($fimeco) {
            $query->where('fimeco_id', $fimeco->id);
        })
        ->with(['subscription.souscripteur', 'validateur'])
        ->orderBy('date_paiement', 'desc')
        ->get()
        ->map(function ($payment) {
            return [
                'id' => $payment->id,
                'souscripteur' => $payment->subscription->souscripteur->nom,
                'montant' => $payment->montant,
                'type_paiement' => $payment->getTypePaiementLibelle(),
                'statut' => $payment->getStatutLibelle(),
                'date_paiement' => $payment->date_paiement->format('d/m/Y H:i'),
                'validateur' => $payment->validateur?->nom,
                'reference' => $payment->reference_paiement,
            ];
        })
        ->toArray();
    }

    private function calculateTauxReussite(Fimeco $fimeco): float
    {
        $totalSouscriptions = $fimeco->subscriptions()->count();
        if ($totalSouscriptions === 0) return 0;

        $souscriptionsCompletes = $fimeco->subscriptions()
            ->where('statut', 'completement_payee')
            ->count();

        return round(($souscriptionsCompletes / $totalSouscriptions) * 100, 2);
    }

    private function calculateDureeMoyennePaiement(Fimeco $fimeco): float
    {
        $subscriptions = $fimeco->subscriptions()
            ->where('statut', 'completement_payee')
            ->get();

        if ($subscriptions->isEmpty()) return 0;

        $totalJours = 0;
        $count = 0;

        foreach ($subscriptions as $subscription) {
            $dernierPaiement = $subscription->payments()
                ->where('statut', 'valide')
                ->latest('date_paiement')
                ->first();

            if ($dernierPaiement) {
                $jours = $subscription->date_souscription
                    ->diffInDays($dernierPaiement->date_paiement);
                $totalJours += $jours;
                $count++;
            }
        }

        return $count > 0 ? round($totalJours / $count, 1) : 0;
    }

    private function getRepartitionTypesPaiement(Fimeco $fimeco): array
    {
        return SubscriptionPayment::whereHas('subscription', function ($query) use ($fimeco) {
            $query->where('fimeco_id', $fimeco->id);
        })
        ->where('statut', 'valide')
        ->selectRaw('type_paiement, COUNT(*) as count, SUM(montant) as total')
        ->groupBy('type_paiement')
        ->get()
        ->mapWithKeys(function ($item) {
            return [$item->type_paiement => [
                'count' => $item->count,
                'total' => $item->total,
                'libelle' => (new SubscriptionPayment(['type_paiement' => $item->type_paiement]))
                    ->getTypePaiementLibelle()
            ]];
        })
        ->toArray();
    }

    /**
     * Méthodes d'export - nécessitent des packages additionnels
     */
    private function generatePdfReport(array $rapport, Fimeco $fimeco)
    {
        // Nécessite le package barryvdh/laravel-dompdf
        // composer require barryvdh/laravel-dompdf

        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return response()->json([
                'success' => false,
                'message' => 'Le package DomPDF n\'est pas installé. Exécutez: composer require barryvdh/laravel-dompdf',
            ], 500);
        }

        try {
            $pdf = Pdf::loadView('exports.fimecos.reports-pdf', compact('rapport', 'fimeco'));

            $filename = 'rapport_fimeco_' . str_replace(' ', '_', $fimeco->nom) . '_' . now()->format('Y-m-d') . '.pdf';

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

    private function generateExcelReport(array $rapport, Fimeco $fimeco)
    {
        // Nécessite le package maatwebsite/excel
        // composer require maatwebsite/excel

        if (!class_exists('Maatwebsite\Excel\Facades\Excel')) {
            return response()->json([
                'success' => false,
                'message' => 'Le package Laravel Excel n\'est pas installé. Exécutez: composer require maatwebsite/excel',
            ], 500);
        }

        try {
            $filename = 'rapport_fimeco_' . str_replace(' ', '_', $fimeco->nom) . '_' . now()->format('Y-m-d') . '.xlsx';

            // Il faudra créer la classe FimecoReportExport
            // return \Maatwebsite\Excel\Facades\Excel::download(new FimecoReportExport($rapport), $filename);

            // Version alternative sans classe d'export
            return $this->generateExcelExport($rapport, 'rapport_fimeco_' . str_replace(' ', '_', $fimeco->nom) . '_' . now()->format('Y-m-d'));

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération Excel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function generateCsvExport(array $data, string $filename): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            // Headers CSV
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
        // Simple export Excel sans package externe
        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');

            // Headers avec encodage UTF-8 pour Excel
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
        // Export PDF simple (nécessite DomPDF)
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            return response()->json([
                'success' => false,
                'message' => 'PDF export non disponible. Installez: composer require barryvdh/laravel-dompdf',
            ], 500);
        }

        try {
            $pdf = Pdf::loadView('exports.fimecos.reports-pdf', compact('data'));

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
     * Méthodes de validation avancées
     */
    public function validateFimecoData(Request $request): JsonResponse
    {
        try {
            $rules = [
                'nom' => 'required|string|max:100',
                'cible' => 'required|numeric|min:1',
                'debut' => 'required|date|after_or_equal:today',
                'fin' => 'required|date|after:debut',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validations métier supplémentaires
            $businessValidation = $this->performBusinessValidation($request);

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

    private function performBusinessValidation(Request $request): array
    {
        $warnings = [];
        $suggestions = [];

        // Vérifier s'il existe déjà un FIMECO similaire
        $similar = Fimeco::where('nom', 'ILIKE', '%' . $request->nom . '%')
            ->where('statut', 'active')
            ->exists();

        if ($similar) {
            $warnings[] = 'Un FIMECO avec un nom similaire existe déjà et est actif';
        }

        // Vérifier la durée du FIMECO
        $debut = Carbon::parse($request->debut);
        $fin = Carbon::parse($request->fin);
        $dureeJours = $debut->diffInDays($fin);

        if ($dureeJours < 30) {
            $warnings[] = 'La durée du FIMECO est très courte (moins de 30 jours)';
        } elseif ($dureeJours > 365) {
            $warnings[] = 'La durée du FIMECO est très longue (plus d\'un an)';
        }

        // Suggestions basées sur la cible
        if ($request->cible) {
            if ($request->cible < 10000) {
                $suggestions[] = 'Pour une cible faible, considérez une durée plus courte';
            } elseif ($request->cible > 1000000) {
                $suggestions[] = 'Pour une cible élevée, assurez-vous d\'avoir un plan de communication robuste';
            }
        }

        return compact('warnings', 'suggestions');
    }

    /**
     * API pour mobile/externe - version simplifiée
     */
    public function apiIndex(Request $request): JsonResponse
    {
        try {
            $fimecos = Fimeco::actifs()
                ->with('responsable:id,nom')
                ->select(['id', 'nom', 'cible', 'montant_solde', 'progression', 'statut_global', 'fin', 'responsable_id'])
                ->orderBy('progression', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => $fimecos->items(),
                'pagination' => [
                    'current_page' => $fimecos->currentPage(),
                    'total_pages' => $fimecos->lastPage(),
                    'total_items' => $fimecos->total(),
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
     * Statistiques en temps réel
     */
    public function liveStats(): JsonResponse
    {
        try {
            $stats = Cache::remember('fimeco_live_stats', 60, function () {
                return [
                    'fimecos_actifs' => Fimeco::actifs()->count(),
                    'objectifs_atteints_aujourd_hui' => Fimeco::whereDate('updated_at', today())
                        ->where('statut_global', 'objectif_atteint')
                        ->count(),
                    'paiements_aujourd_hui' => SubscriptionPayment::aujourdhui()
                        ->where('statut', 'valide')
                        ->sum('montant'),
                    'nouvelles_souscriptions_aujourd_hui' => Subscription::whereDate('created_at', today())->count(),
                    'paiements_en_attente' => SubscriptionPayment::enAttente()->count(),
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
     * Recherche avancée avec suggestions
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $query = $request->get('q', '');
            $limit = min($request->get('limit', 10), 50);

            if (strlen($query) < 2) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'suggestions' => $this->getSearchSuggestions()
                ]);
            }

            $results = Fimeco::with('responsable:id,nom')
                ->where(function ($q) use ($query) {
                    $q->where('nom', 'ILIKE', "%{$query}%")
                      ->orWhere('description', 'ILIKE', "%{$query}%");
                })
                ->orWhereHas('responsable', function ($q) use ($query) {
                    $q->where('nom', 'ILIKE', "%{$query}%");
                })
                ->orderByRaw("
                    CASE
                        WHEN nom ILIKE ? THEN 1
                        WHEN nom ILIKE ? THEN 2
                        ELSE 3
                    END
                ", [$query, "%{$query}%"])
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $results,
                'count' => $results->count()
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de recherche',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getSearchSuggestions(): array
    {
        return Cache::remember('fimeco_search_suggestions', 3600, function () {
            return [
                'fimecos_populaires' => Fimeco::withCount('subscriptions')
                    ->orderBy('subscriptions_count', 'desc')
                    ->limit(5)
                    ->pluck('nom')
                    ->toArray(),
                'statuts_disponibles' => ['active', 'inactive', 'cloturee'],
                'statuts_globaux' => ['objectif_atteint', 'presque_atteint', 'en_cours', 'tres_faible']
            ];
        });
    }
}

