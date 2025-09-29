<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\User;
use App\Models\Fonds;
use App\Models\Projet;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProjetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:projets.read')->only(['index', 'show', 'statistiques', 'projetsPublics', 'options', 'validerWorkflow']);
        $this->middleware('permission:projets.create')->only(['create', 'store', 'dupliquer']);
        $this->middleware('permission:projets.update')->only(['edit', 'update', 'mettreAJourProgression', 'uploadImage', 'planifier', 'rechercherFinancement', 'mettreEnAttente', 'demarrer', 'suspendre', 'reprendre', 'terminer', 'annuler', 'executerAction']);
        $this->middleware('permission:projets.delete')->only(['destroy']);
        $this->middleware('permission:projets.approve')->only(['approuver']);
    }

    /**
     * Affiche la liste des projets avec filtrage et pagination
     */
    public function index(Request $request)
    {
        try {
            $query = Projet::with(['responsable', 'coordinateur', 'chefProjet']);

            // Filtres
            if ($request->has('statut') && $request->statut !== '') {
                $query->where('statut', $request->statut);
            }

            if ($request->has('type_projet') && $request->type_projet !== '') {
                $query->where('type_projet', $request->type_projet);
            }

            if ($request->has('categorie') && $request->categorie !== '') {
                $query->where('categorie', $request->categorie);
            }

            if ($request->has('priorite') && $request->priorite !== '') {
                $query->where('priorite', $request->priorite);
            }

            if ($request->has('responsable_id') && $request->responsable_id !== '') {
                $query->where('responsable_id', $request->responsable_id);
            }

            if ($request->has('ville') && $request->ville !== '') {
                $query->where('ville', 'ILIKE', '%' . $request->ville . '%');
            }

            if ($request->has('region') && $request->region !== '') {
                $query->where('region', $request->region);
            }

            // Recherche par mot-clé
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('nom_projet', 'ILIKE', '%' . $search . '%')
                        ->orWhere('code_projet', 'ILIKE', '%' . $search . '%')
                        ->orWhere('description', 'ILIKE', '%' . $search . '%')
                        ->orWhere('objectif', 'ILIKE', '%' . $search . '%');
                });
            }

            // Filtres de date
            if ($request->has('date_debut_min')) {
                $query->where('date_debut', '>=', $request->date_debut_min);
            }

            if ($request->has('date_debut_max')) {
                $query->where('date_debut', '<=', $request->date_debut_max);
            }

            // Filtres budgétaires
            if ($request->has('budget_min')) {
                $query->where('budget_prevu', '>=', $request->budget_min);
            }

            if ($request->has('budget_max')) {
                $query->where('budget_prevu', '<=', $request->budget_max);
            }

            // Filtres spécifiques
            if ($request->has('visible_public') && $request->visible_public !== '') {
                $query->where('visible_public', $request->boolean('visible_public'));
            }

            if ($request->has('ouvert_aux_dons') && $request->ouvert_aux_dons !== '') {
                $query->where('ouvert_aux_dons', $request->boolean('ouvert_aux_dons'));
            }

            if ($request->has('en_retard') && $request->boolean('en_retard')) {
                $query->enRetard();
            }

            if ($request->has('necessite_approbation') && $request->boolean('necessite_approbation')) {
                $query->enAttenteApprobation();
            }

            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortDirection = $request->get('sort_direction', 'desc');

            $allowedSorts = [
                'nom_projet',
                'code_projet',
                'date_creation',
                'date_debut',
                'date_fin_prevue',
                'budget_prevu',
                'budget_collecte',
                'pourcentage_completion',
                'priorite',
                'statut',
                'created_at'
            ];

            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDirection);
            }

            // Pagination
            $perPage = min($request->get('per_page', 10), 100);
            $projets = $query->paginate($perPage);

            // Retour conditionnel
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $projets,
                    'message' => 'Projets récupérés avec succès'
                ]);
            }

            // Données pour la vue Blade
            $options = $this->getOptionsForView();

            return view('components.private.projets.index', compact('projets', 'options'));

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des projets: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des projets',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la récupération des projets')
                ->withInput();
        }
    }

    /**
     * Affiche le formulaire de création d'un projet
     */
    public function create(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette route n\'est disponible que pour les vues web'
            ], 405);
        }

        $options = $this->getOptionsForView();

        return view('components.private.projets.create', compact('options'));
    }

    /**
     * Affiche les détails d'un projet spécifique
     */
    public function show(string $id, Request $request)
    {
        try {
            $projet = Projet::with([
                'responsable',
                'coordinateur',
                'chefProjet',
                'approbateur',
                'createur',
                'projetParent',
                'projetsEnfants',
                'fonds' => function ($query) {
                    $query->validees()->latest();
                }
            ])->findOrFail($id);

            // Calculer les statistiques financières
            $statistiquesFinancieres = [
                'total_collecte' => $projet->fonds->sum('montant'),
                'nombre_donations' => $projet->fonds->count(),
                'derniere_donation' => $projet->fonds->first()?->date_transaction,
                'pourcentage_financement' => $projet->pourcentage_financement,
                'montant_restant' => $projet->montant_restant
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $projet,
                    'statistiques_financieres' => $statistiquesFinancieres,
                    'message' => 'Projet récupéré avec succès'
                ]);
            }
            // dd($projet->budget_collecte);
            return view('components.private.projets.show', compact('projet', 'statistiquesFinancieres'));

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Projet non trouvé',
                    'error' => $e->getMessage()
                ], 404);
            }

            return redirect()->route('private.projets.index')
                ->with('error', 'Projet non trouvé');
        }
    }

    /**
     * Affiche le formulaire d'édition d'un projet
     */
    public function edit(string $id, Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette route n\'est disponible que pour les vues web'
            ], 405);
        }

        try {
            $projet = Projet::with(['responsable', 'coordinateur', 'chefProjet'])->findOrFail($id);
            $options = $this->getOptionsForView();

            return view('components.private.projets.edit', compact('projet', 'options'));

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du projet pour édition: ' . $e->getMessage());

            return redirect()->route('private.projets.index')
                ->with('error', 'Projet non trouvé');
        }
    }

    /**
     * Crée un nouveau projet
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom_projet' => 'required|string|max:200',
                // 'code_projet' => 'required|string|max:50|unique:projets,code_projet',
                'description' => 'nullable|string',
                'objectif' => 'nullable|string',
                'contexte' => 'nullable|string',
                'type_projet' => 'required|in:construction,renovation,social,evangelisation,formation,mission,equipement,technologie,communautaire,humanitaire,education,sante,autre',
                'categorie' => 'required|in:infrastructure,spirituel,social,educatif,technique,administratif',
                'budget_prevu' => 'nullable|numeric|min:0',
                'budget_minimum' => 'nullable|numeric|min:0',
                'devise' => 'nullable|string|max:3',
                'date_debut' => 'nullable|date',
                'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut',
                'duree_prevue_jours' => 'nullable|integer|min:1',
                'responsable_id' => 'nullable|exists:users,id',
                'coordinateur_id' => 'nullable|exists:users,id',
                'chef_projet_id' => 'nullable|exists:users,id',
                'localisation' => 'nullable|string|max:200',
                'ville' => 'nullable|string|max:100',
                'region' => 'nullable|string|max:100',
                'pays' => 'nullable|string|max:100',
                'priorite' => 'nullable|in:faible,normale,haute,urgente,critique',
                'visible_public' => 'boolean',
                'ouvert_aux_dons' => 'boolean',
                'image_principale' => 'nullable|string|max:500',
                'site_web' => 'nullable|url',
                'equipe_projet' => 'nullable|array',
                'partenaires' => 'nullable|array',
                'beneficiaires' => 'nullable|array',
                'detail_budget' => 'nullable|array',
                'sources_financement' => 'nullable|array',
                'objectifs_mesurables' => 'nullable|array',
                'indicateurs_succes' => 'nullable|array',
                'risques_identifies' => 'nullable|array',
                'mesures_mitigation' => 'nullable|array'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Données de validation invalides',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            $data = $request->all();
            // $data['code_projet'] = Projet::genererNouveauCode();

            $projet = Projet::create($data);

            // Valider les données métier
            $erreurs = $projet->validate();
            if (!empty($erreurs)) {
                DB::rollBack();

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreurs de validation métier',
                        'errors' => $erreurs
                    ], 422);
                }

                return redirect()->back()
                    ->with('error', 'Erreurs de validation: ' . implode(', ', $erreurs))
                    ->withInput();
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $projet->load(['responsable', 'coordinateur', 'chefProjet']),
                    'message' => 'Projet créé avec succès'
                ], 201);
            }

            return redirect()->route('private.projets.show', $projet->id)
                ->with('success', 'Projet créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la création du projet')
                ->withInput();
        }
    }

    /**
     * Met à jour un projet existant
     */
    public function update(Request $request, string $id)
    {
        try {
            $projet = Projet::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nom_projet' => 'sometimes|required|string|max:200',
                'code_projet' => 'sometimes|required|string|max:50|unique:projets,code_projet,' . $id,
                'description' => 'nullable|string',
                'objectif' => 'nullable|string',
                'contexte' => 'nullable|string',
                'type_projet' => 'sometimes|required|in:construction,renovation,social,evangelisation,formation,mission,equipement,technologie,communautaire,humanitaire,education,sante,autre',
                'categorie' => 'sometimes|required|in:infrastructure,spirituel,social,educatif,technique,administratif',
                'budget_prevu' => 'nullable|numeric|min:0',
                'budget_minimum' => 'nullable|numeric|min:0',
                'devise' => 'nullable|string|max:3',
                'date_debut' => 'nullable|date',
                'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut',
                'duree_prevue_jours' => 'nullable|integer|min:1',
                'responsable_id' => 'nullable|exists:users,id',
                'coordinateur_id' => 'nullable|exists:users,id',
                'chef_projet_id' => 'nullable|exists:users,id',
                'localisation' => 'nullable|string|max:200',
                'ville' => 'nullable|string|max:100',
                'region' => 'nullable|string|max:100',
                'pays' => 'nullable|string|max:100',
                'priorite' => 'nullable|in:faible,normale,haute,urgente,critique',
                'pourcentage_completion' => 'nullable|numeric|min:0|max:100',
                'visible_public' => 'boolean',
                'ouvert_aux_dons' => 'boolean',
                'image_principale' => 'nullable|string|max:500',
                'site_web' => 'nullable|url'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Données de validation invalides',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();

            $projet->update($request->all());

            // Valider les données métier
            $erreurs = $projet->validate();
            if (!empty($erreurs)) {
                DB::rollBack();

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreurs de validation métier',
                        'errors' => $erreurs
                    ], 422);
                }

                return redirect()->back()
                    ->with('error', 'Erreurs de validation: ' . implode(', ', $erreurs))
                    ->withInput();
            }

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $projet->load(['responsable', 'coordinateur', 'chefProjet']),
                    'message' => 'Projet mis à jour avec succès'
                ]);
            }

            return redirect()->route('private.projets.show', $projet->id)
                ->with('success', 'Projet mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour du projet')
                ->withInput();
        }
    }

    /**
     * Supprime un projet (soft delete)
     */
    public function destroy(string $id, Request $request)
    {
        try {
            $projet = Projet::findOrFail($id);

            // Vérifier si le projet peut être supprimé
            if (in_array($projet->statut, ['en_cours', 'suspendu'])) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de supprimer un projet en cours ou suspendu'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Impossible de supprimer un projet en cours ou suspendu');
            }

            $projet->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Projet supprimé avec succès'
                ]);
            }

            return redirect()->route('private.projets.index')
                ->with('success', 'Projet supprimé avec succès');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du projet');
        }
    }

    /**
     * Approuve un projet
     */
    public function approuver(Request $request, string $id)
    {
        try {
            $projet = Projet::findOrFail($id);

            if (!$projet->peutEtreApprouve()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce projet ne peut pas être approuvé dans son état actuel'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Ce projet ne peut pas être approuvé dans son état actuel');
            }

            $validator = Validator::make($request->all(), [
                'commentaires_approbation' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Données invalides',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator);
            }

            $success = $projet->approuver(auth()->id(), $request->commentaires_approbation);

            if ($success) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $projet->refresh(),
                        'message' => 'Projet approuvé avec succès'
                    ]);
                }

                return redirect()->route('private.projets.show', $projet->id)
                    ->with('success', 'Projet approuvé avec succès');
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de l\'approbation du projet'
                    ], 500);
                }

                return redirect()->back()
                    ->with('error', 'Erreur lors de l\'approbation du projet');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'approbation du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'approbation du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'approbation du projet');
        }
    }

    /**
     * Démarre un projet
     */
    public function demarrer(Request $request, string $id)
    {
        try {
            $projet = Projet::findOrFail($id);

            // Vérification stricte du workflow cohérent
            if (!$projet->peutEtreDemarre()) {
                $message = 'Ce projet ne peut pas être démarré. ';

                // Messages d'aide contextuelle
                if ($projet->statut === 'conception') {
                    $message .= 'Il doit d\'abord être approuvé puis planifié.';
                } elseif ($projet->statut === 'planification') {
                    $message .= 'Il doit d\'abord passer par les étapes de financement ou être mis en attente.';
                } elseif ($projet->statut === 'recherche_financement') {
                    $message .= 'Le financement minimum requis n\'est pas encore atteint.';
                } else {
                    $message .= 'Statut actuel : ' . $projet->statut_libelle;
                }

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', $message);
            }

            $validator = Validator::make($request->all(), [
                'date_debut' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Date invalide',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator);
            }

            $success = $projet->demarrer($request->date_debut);

            if ($success) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $projet->refresh(),
                        'message' => 'Projet démarré avec succès'
                    ]);
                }

                return redirect()->route('private.projets.show', $projet->id)
                    ->with('success', 'Projet démarré avec succès');
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors du démarrage du projet'
                    ], 500);
                }

                return redirect()->back()
                    ->with('error', 'Erreur lors du démarrage du projet');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors du démarrage du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du démarrage du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du démarrage du projet');
        }
    }

    /**
     * Suspend un projet
     */
    public function suspendre(Request $request, string $id)
    {
        try {
            $projet = Projet::findOrFail($id);

            if (!$projet->peutEtreSuspendu()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce projet ne peut pas être suspendu'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Ce projet ne peut pas être suspendu');
            }

            $validator = Validator::make($request->all(), [
                'motif' => 'required|string'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Motif requis',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator);
            }

            $success = $projet->suspendre($request->motif);

            if ($success) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $projet->refresh(),
                        'message' => 'Projet suspendu avec succès'
                    ]);
                }

                return redirect()->route('private.projets.show', $projet->id)
                    ->with('success', 'Projet suspendu avec succès');
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de la suspension du projet'
                    ], 500);
                }

                return redirect()->back()
                    ->with('error', 'Erreur lors de la suspension du projet');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suspension du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suspension du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suspension du projet');
        }
    }

    /**
     * Reprend un projet suspendu
     */
    public function reprendre(string $id, Request $request)
    {
        try {
            $projet = Projet::findOrFail($id);

            if (!$projet->peutEtreRepris()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce projet ne peut pas être repris'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Ce projet ne peut pas être repris');
            }

            $success = $projet->reprendre();

            if ($success) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $projet->refresh(),
                        'message' => 'Projet repris avec succès'
                    ]);
                }

                return redirect()->route('private.projets.show', $projet->id)
                    ->with('success', 'Projet repris avec succès');
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de la reprise du projet'
                    ], 500);
                }

                return redirect()->back()
                    ->with('error', 'Erreur lors de la reprise du projet');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la reprise du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la reprise du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la reprise du projet');
        }
    }

    /**
     * Termine un projet
     */
    public function terminer(Request $request, string $id)
    {
        try {
            $projet = Projet::findOrFail($id);

            if (!$projet->peutEtreTermine()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce projet ne peut pas être terminé'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Ce projet ne peut pas être terminé');
            }

            $validator = Validator::make($request->all(), [
                'date_fin_reelle' => 'nullable|date',
                'resultats_obtenus' => 'nullable|string',
                'note_satisfaction' => 'nullable|numeric|min:1|max:10',
                'impact_communaute' => 'nullable|string',
                'lecons_apprises' => 'nullable|string',
                'recommandations' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Données invalides',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator);
            }

            DB::beginTransaction();

            $success = $projet->terminer($request->date_fin_reelle, $request->resultats_obtenus);

            if ($success) {
                // Mettre à jour les champs supplémentaires
                $projet->update($request->only([
                    'note_satisfaction',
                    'impact_communaute',
                    'lecons_apprises',
                    'recommandations'
                ]));

                DB::commit();

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $projet->refresh(),
                        'message' => 'Projet terminé avec succès'
                    ]);
                }

                return redirect()->route('private.projets.show', $projet->id)
                    ->with('success', 'Projet terminé avec succès');
            } else {
                DB::rollBack();

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de la finalisation du projet'
                    ], 500);
                }

                return redirect()->back()
                    ->with('error', 'Erreur lors de la finalisation du projet');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la finalisation du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la finalisation du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la finalisation du projet');
        }
    }

    /**
     * Annule un projet
     */
    public function annuler(Request $request, string $id)
    {
        try {
            $projet = Projet::findOrFail($id);

            if (!$projet->peutEtreAnnule()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce projet ne peut pas être annulé'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Ce projet ne peut pas être annulé');
            }

            $validator = Validator::make($request->all(), [
                'motif' => 'required|string'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Motif requis',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator);
            }

            $success = $projet->annuler($request->motif);

            if ($success) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $projet->refresh(),
                        'message' => 'Projet annulé avec succès'
                    ]);
                }

                return redirect()->route('private.projets.show', $projet->id)
                    ->with('success', 'Projet annulé avec succès');
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de l\'annulation du projet'
                    ], 500);
                }

                return redirect()->back()
                    ->with('error', 'Erreur lors de l\'annulation du projet');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'annulation du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'annulation du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'annulation du projet');
        }
    }



    /**
     * Planifie un projet (passage de conception à planification)
     */
    public function planifier(string $id, Request $request)
    {
        try {
            $projet = Projet::findOrFail($id);

            if (!$projet->peutEtrePlanifie()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce projet ne peut pas être planifié dans son état actuel'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Ce projet ne peut pas être planifié dans son état actuel');
            }

            $success = $projet->planifier();

            if ($success) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $projet->refresh(),
                        'message' => 'Projet planifié avec succès'
                    ]);
                }

                return redirect()->route('private.projets.show', $projet->id)
                    ->with('success', 'Projet planifié avec succès');
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de la planification du projet'
                    ], 500);
                }

                return redirect()->back()
                    ->with('error', 'Erreur lors de la planification du projet');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la planification du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la planification du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la planification du projet');
        }
    }




    /**
     * Met un projet en recherche de financement
     */
    public function rechercherFinancement(string $id, Request $request)
    {
        try {
            $projet = Projet::findOrFail($id);

            if (!$projet->peutEtreEnRechercheFinancement()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce projet ne peut pas être mis en recherche de financement dans son état actuel'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Ce projet ne peut pas être mis en recherche de financement dans son état actuel');
            }

            $success = $projet->mettreEnRechercheFinancement();

            if ($success) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $projet->refresh(),
                        'message' => 'Projet mis en recherche de financement avec succès'
                    ]);
                }

                return redirect()->route('private.projets.show', $projet->id)
                    ->with('success', 'Projet mis en recherche de financement avec succès');
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de la mise en recherche de financement'
                    ], 500);
                }

                return redirect()->back()
                    ->with('error', 'Erreur lors de la mise en recherche de financement');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise en recherche de financement: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise en recherche de financement',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise en recherche de financement');
        }
    }


    /**
     * Met à jour la progression d'un projet
     */
    public function mettreAJourProgression(Request $request, string $id)
    {
        try {
            $projet = Projet::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'pourcentage_completion' => 'required|numeric|min:0|max:100',
                'derniere_activite' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Données invalides',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator);
            }

            $success = $projet->mettreAJourProgression(
                $request->pourcentage_completion,
                $request->derniere_activite
            );

            if ($success) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $projet->refresh(),
                        'message' => 'Progression mise à jour avec succès'
                    ]);
                }

                return redirect()->route('private.projets.show', $projet->id)
                    ->with('success', 'Progression mise à jour avec succès');
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Impossible de mettre à jour la progression pour ce projet'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Impossible de mettre à jour la progression pour ce projet');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de progression: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de progression',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de progression');
        }
    }

    /**
     * Duplique un projet
     */
    public function dupliquer(Request $request, string $id)
    {
        try {
            $projet = Projet::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nouveau_nom' => 'nullable|string|max:200',
                'nouveau_code' => 'nullable|string|max:50|unique:projets,code_projet'
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Données invalides',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return redirect()->back()
                    ->withErrors($validator);
            }

            DB::beginTransaction();

            $nouveauProjet = $projet->dupliquer($request->nouveau_nom, $request->nouveau_code);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $nouveauProjet->load(['responsable', 'coordinateur', 'chefProjet']),
                    'message' => 'Projet dupliqué avec succès'
                ], 201);
            }

            return redirect()->route('private.projets.show', $nouveauProjet->id)
                ->with('success', 'Projet dupliqué avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la duplication du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la duplication du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la duplication du projet');
        }
    }

    /**
     * Retourne les statistiques des projets
     */
    public function statistiques(Request $request)
    {
        try {
            // Statistiques par statutc
            $statsStatut = Projet::statistiquesParStatut();

            // Statistiques par type
            $statsType = Projet::statistiquesParType();

            // Projets en retard
            $projetsEnRetard = Projet::projetsEnRetard()->count();

            // Projets nécessitant une action
            $projetsAction = Projet::projetsNecessitantAction()->count();
            

            // Budget total et collecté
            $budgetTotal = Projet::sum('budget_prevu');
            $budgetCollecte = Projet::sum('budget_collecte');
            $budgetDepense = Projet::sum('budget_depense');

            // Statistiques par période (optionnel)
            $statistiques = [
                'par_statut' => $statsStatut,
                'par_type' => $statsType,
                'projets_en_retard' => $projetsEnRetard,
                'projets_necessitant_action' => $projetsAction,
                'budget' => [
                    'total_prevu' => $budgetTotal,
                    'total_collecte' => $budgetCollecte,
                    'total_depense' => $budgetDepense,
                    'pourcentage_financement_global' => $budgetTotal > 0 ? round(($budgetCollecte / $budgetTotal) * 100, 2) : 0
                ]
            ];

            // Statistiques par période si demandé
            if ($request->has('periode')) {
                $periode = $request->periode; // 'mois', 'trimestre', 'annee'

                switch ($periode) {
                    case 'mois':
                        $dateDebut = now()->startOfMonth();
                        $dateFin = now()->endOfMonth();
                        break;
                    case 'trimestre':
                        $dateDebut = now()->startOfQuarter();
                        $dateFin = now()->endOfQuarter();
                        break;
                    case 'annee':
                        $dateDebut = now()->startOfYear();
                        $dateFin = now()->endOfYear();
                        break;
                    default:
                        $dateDebut = now()->startOfMonth();
                        $dateFin = now()->endOfMonth();
                }

                $statistiques['periode'] = [
                    'debut' => $dateDebut->toDateString(),
                    'fin' => $dateFin->toDateString(),
                    'projets_crees' => Projet::whereBetween('created_at', [$dateDebut, $dateFin])->count(),
                    'projets_demarres' => Projet::whereBetween('date_debut', [$dateDebut, $dateFin])->count(),
                    'projets_termines' => Projet::where('statut', 'termine')
                        ->whereBetween('date_fin_reelle', [$dateDebut, $dateFin])
                        ->count()
                ];
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $statistiques,
                    'message' => 'Statistiques récupérées avec succès'
                ]);
            }

            return view('components.private.projets.statistiques', compact('statistiques'));

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des statistiques',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la récupération des statistiques');
        }
    }

    /**
     * Retourne les projets publics ouverts aux dons
     */
    public function projetsPublics(Request $request)
    {
        try {
            $query = Projet::visiblesPublic()
                ->ouvertsAuxDons()
                ->with(['responsable']);

            // Filtres optionnels
            if ($request->has('type_projet')) {
                $query->where('type_projet', $request->type_projet);
            }

            if ($request->has('ville')) {
                $query->where('ville', 'ILIKE', '%' . $request->ville . '%');
            }

            if ($request->has('region')) {
                $query->where('region', $request->region);
            }

            // Tri par défaut : priorité puis pourcentage de financement
            $query->orderBy('priorite', 'desc')
                ->orderBy('pourcentage_completion', 'asc');

            $projets = $query->get();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $projets,
                    'message' => 'Projets publics récupérés avec succès'
                ]);
            }

            return view('components.private.projets.publics', compact('projets'));

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des projets publics: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des projets publics',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la récupération des projets publics');
        }
    }

    /**
     * Upload d'image pour un projet
     */
    public function uploadImage(Request $request, string $id): JsonResponse
    {
        try {
            $projet = Projet::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'type' => 'required|in:principale,galerie'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fichier image invalide',
                    'errors' => $validator->errors()
                ], 422);
            }

            $image = $request->file('image');
            $type = $request->type;

            // Créer le nom du fichier
            $filename = 'projets/' . $projet->id . '/' . $type . '/' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Stocker l'image
            $path = $image->storeAs('public', $filename);

            // Mettre à jour le projet selon le type
            if ($type === 'principale') {
                $projet->update(['image_principale' => Storage::url($filename)]);
            } else {
                // Pour la galerie, ajouter à photos_projet
                $photos = $projet->photos_projet ?? [];
                $photos[] = [
                    'url' => Storage::url($filename),
                    'filename' => $filename,
                    'uploaded_at' => now()->toDateTimeString(),
                    'uploaded_by' => auth()->id()
                ];
                $projet->update(['photos_projet' => $photos]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'url' => Storage::url($filename),
                    'filename' => $filename
                ],
                'message' => 'Image uploadée avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'upload d\'image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload d\'image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retourne les options de sélection pour les formulaires
     */
    public function options(Request $request)
    {
        try {
            $options = $this->getOptionsForView();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $options,
                    'message' => 'Options récupérées avec succès'
                ]);
            }

            // Pour les vues Blade, les options sont généralement passées directement aux vues
            return response()->json($options);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des options: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des options',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la récupération des options');
        }
    }

    /**
     * Retourne les options de sélection pour les vues
     */
    private function getOptionsForView(): array
    {
        return [
            'types_projet' => [
                'construction' => 'Construction',
                'renovation' => 'Rénovation',
                'social' => 'Social',
                'evangelisation' => 'Évangélisation',
                'formation' => 'Formation',
                'mission' => 'Mission',
                'equipement' => 'Équipement',
                'technologie' => 'Technologie',
                'communautaire' => 'Communautaire',
                'humanitaire' => 'Humanitaire',
                'education' => 'Éducation',
                'sante' => 'Santé',
                'autre' => 'Autre'
            ],
            'categories' => [
                'infrastructure' => 'Infrastructure',
                'spirituel' => 'Spirituel',
                'social' => 'Social',
                'educatif' => 'Éducatif',
                'technique' => 'Technique',
                'administratif' => 'Administratif'
            ],
            'statuts' => [
                'conception' => 'En conception',
                'planification' => 'En planification',
                'recherche_financement' => 'Recherche de financement',
                'en_attente' => 'En attente',
                'en_cours' => 'En cours',
                'suspendu' => 'Suspendu',
                'termine' => 'Terminé',
                'annule' => 'Annulé',
                'archive' => 'Archivé'
            ],
            'priorites' => [
                'faible' => 'Faible',
                'normale' => 'Normale',
                'haute' => 'Haute',
                'urgente' => 'Urgente',
                'critique' => 'Critique'
            ],
            'responsables' => User::actifs()
                ->select('id', 'prenom', 'nom')
                ->orderBy('nom')
                ->get()
                ->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'nom_complet' => $user->nom_complet
                    ];
                }),
            'frequences_recurrence' => [
                'annuelle' => 'Annuelle',
                'semestrielle' => 'Semestrielle',
                'trimestrielle' => 'Trimestrielle',
                'mensuelle' => 'Mensuelle',
                'ponctuelle' => 'Ponctuelle'
            ]
        ];
    }


    /**
     * Met un projet en attente (prêt à démarrer)
     */
    public function mettreEnAttente(string $id, Request $request)
    {
        try {
            $projet = Projet::findOrFail($id);

            if (!$projet->peutEtreEnAttente()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ce projet ne peut pas être mis en attente dans son état actuel'
                    ], 400);
                }

                return redirect()->back()
                    ->with('error', 'Ce projet ne peut pas être mis en attente dans son état actuel');
            }

            $success = $projet->mettreEnAttente();

            if ($success) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $projet->refresh(),
                        'message' => 'Projet mis en attente avec succès'
                    ]);
                }

                return redirect()->route('private.projets.show', $projet->id)
                    ->with('success', 'Projet mis en attente avec succès');
            } else {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreur lors de la mise en attente du projet'
                    ], 500);
                }

                return redirect()->back()
                    ->with('error', 'Erreur lors de la mise en attente du projet');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise en attente du projet: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise en attente du projet',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise en attente du projet');
        }
    }


    /**
     * Valide le workflow d'un projet et suggère la prochaine action
     */
    public function validerWorkflow(string $id, Request $request)
    {
        try {
            $projet = Projet::findOrFail($id);

            $workflow = [
                'statut_actuel' => $projet->statut,
                'statut_libelle' => $projet->statut_libelle,
                'est_approuve' => $projet->est_approuve,
                'necessiteAction' => $projet->necessiteAction(),
                'prochaine_action' => $projet->getProchainePossibleAction(),
                'actions_possibles' => $projet->getWorkflowPossible(),
                'erreurs_validation' => $projet->validate(),
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $workflow,
                    'message' => 'Workflow validé avec succès'
                ]);
            }

            return view('components.private.projets.workflow', compact('projet', 'workflow'));

        } catch (\Exception $e) {
            Log::error('Erreur lors de la validation du workflow: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la validation du workflow',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la validation du workflow');
        }
    }



    /**
     *  Méthode générique pour exécuter n'importe quelle action du workflow
     */
    // public function executerAction(string $id, string $action, Request $request)
    // {
    //     try {
    //         $projet = Projet::findOrFail($id);

    //         $actionsPermises = $projet->getWorkflowPossible();

    //         if (!in_array($action, $actionsPermises)) {
    //             if ($request->expectsJson()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Cette action n\'est pas permise pour le statut actuel du projet',
    //                     'actions_disponibles' => $actionsPermises
    //                 ], 400);
    //             }

    //             return redirect()->back()
    //                 ->with('error', 'Cette action n\'est pas permise pour le statut actuel du projet');
    //         }

    //         $success = false;
    //         $message = '';

    //         // Exécuter l'action correspondante
    //         switch ($action) {
    //             case 'approuver':
    //                 $success = $projet->approuver(auth()->id(), $request->commentaires_approbation);
    //                 $message = 'Projet approuvé avec succès';
    //                 break;

    //             case 'planifier':
    //                 $success = $projet->planifier();
    //                 $message = 'Projet planifié avec succès';
    //                 break;

    //             case 'rechercher_financement':
    //                 $success = $projet->mettreEnRechercheFinancement();
    //                 $message = 'Projet mis en recherche de financement avec succès';
    //                 break;

    //             case 'mettre_en_attente':
    //                 $success = $projet->mettreEnAttente();
    //                 $message = 'Projet mis en attente avec succès';
    //                 break;

    //             case 'demarrer':
    //                 $success = $projet->demarrer($request->date_debut);
    //                 $message = 'Projet démarré avec succès';
    //                 break;

    //             case 'suspendre':
    //                 $success = $projet->suspendre($request->motif);
    //                 $message = 'Projet suspendu avec succès';
    //                 break;

    //             case 'reprendre':
    //                 $success = $projet->reprendre();
    //                 $message = 'Projet repris avec succès';
    //                 break;

    //             case 'terminer':
    //                 $success = $projet->terminer($request->date_fin_reelle, $request->resultats_obtenus);
    //                 $message = 'Projet terminé avec succès';
    //                 break;

    //             case 'annuler':
    //                 $success = $projet->annuler($request->motif);
    //                 $message = 'Projet annulé avec succès';
    //                 break;

    //             default:
    //                 throw new \InvalidArgumentException('Action non reconnue: ' . $action);
    //         }

    //         if ($success) {
    //             if ($request->expectsJson()) {
    //                 return response()->json([
    //                     'success' => true,
    //                     'data' => $projet->refresh(),
    //                     'message' => $message
    //                 ]);
    //             }

    //             return redirect()->route('private.projets.show', $projet->id)
    //                 ->with('success', $message);
    //         } else {
    //             if ($request->expectsJson()) {
    //                 return response()->json([
    //                     'success' => false,
    //                     'message' => 'Erreur lors de l\'exécution de l\'action'
    //                 ], 500);
    //             }

    //             return redirect()->back()
    //                 ->with('error', 'Erreur lors de l\'exécution de l\'action');
    //         }

    //     } catch (\Exception $e) {
    //         Log::error('Erreur lors de l\'exécution de l\'action ' . $action . ': ' . $e->getMessage());

    //         if ($request->expectsJson()) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'Erreur lors de l\'exécution de l\'action',
    //                 'error' => $e->getMessage()
    //             ], 500);
    //         }

    //         return redirect()->back()
    //             ->with('error', 'Erreur lors de l\'exécution de l\'action');
    //     }
    // }

    /**
 * AMÉLIORATION : Méthode executerAction mise à jour
 */
public function executerAction(string $id, string $action, Request $request)
{
    try {
        $projet = Projet::findOrFail($id);

        $actionsPermises = $projet->getWorkflowPossible();

        if (!in_array($action, $actionsPermises)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette action n\'est pas permise pour le statut actuel du projet',
                    'actions_disponibles' => $actionsPermises,
                    'statut_actuel' => $projet->statut,
                    'financement' => $projet->getStatutFinancement() // NOUVEAU
                ], 400);
            }

            return redirect()->back()
                           ->with('error', 'Cette action n\'est pas permise pour le statut actuel du projet');
        }

        $success = false;
        $message = '';

        // Exécuter l'action correspondante
        switch ($action) {
            case 'approuver':
                $success = $projet->approuver(auth()->id(), $request->commentaires_approbation);
                $message = 'Projet approuvé avec succès';
                break;

            case 'planifier':
                $success = $projet->planifier();
                $message = 'Projet planifié avec succès';
                break;

            case 'rechercher_financement':
                $success = $projet->mettreEnRechercheFinancement();
                $message = 'Projet mis en recherche de financement avec succès';
                break;

            case 'mettre_en_attente':
                $success = $projet->mettreEnAttente();
                $message = 'Projet mis en attente avec succès';
                break;

            case 'forcer_attente': // NOUVEAU
                $validator = Validator::make($request->all(), [
                    'justification' => 'required|string|min:10'
                ]);

                if ($validator->fails()) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Justification requise',
                            'errors' => $validator->errors()
                        ], 422);
                    }
                    return redirect()->back()->withErrors($validator);
                }

                $success = $projet->forcerMiseEnAttente($request->justification);
                $message = 'Projet forcé en attente avec succès (financement incomplet)';
                break;

            case 'demarrer':
                $success = $projet->demarrer($request->date_debut);
                $message = 'Projet démarré avec succès';
                break;

            case 'suspendre':
                $success = $projet->suspendre($request->motif);
                $message = 'Projet suspendu avec succès';
                break;

            case 'reprendre':
                $success = $projet->reprendre();
                $message = 'Projet repris avec succès';
                break;

            case 'terminer':
                $success = $projet->terminer($request->date_fin_reelle, $request->resultats_obtenus);
                $message = 'Projet terminé avec succès';
                break;

            case 'annuler':
                $success = $projet->annuler($request->motif);
                $message = 'Projet annulé avec succès';
                break;

            default:
                throw new \InvalidArgumentException('Action non reconnue: ' . $action);
        }

        if ($success) {
            // NOUVEAU : Ajouter des informations contextuelles
            $responseData = [
                'success' => true,
                'data' => $projet->refresh(),
                'message' => $message,
                'workflow_suivant' => $projet->getWorkflowPossible(),
                'statut_financement' => $projet->getStatutFinancement()
            ];

            if ($request->expectsJson()) {
                return response()->json($responseData);
            }

            return redirect()->route('private.projets.show', $projet->id)
                           ->with('success', $message);
        } else {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'exécution de l\'action'
                ], 500);
            }

            return redirect()->back()
                           ->with('error', 'Erreur lors de l\'exécution de l\'action');
        }

    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'exécution de l\'action ' . $action . ': ' . $e->getMessage());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'exécution de l\'action',
                'error' => $e->getMessage()
            ], 500);
        }

        return redirect()->back()
                       ->with('error', 'Erreur lors de l\'exécution de l\'action');
    }
}




/**
 *  Obtenir le statut détaillé d'un projet
 */
public function getStatutDetaille(string $id, Request $request)
{
    try {
        $projet = Projet::findOrFail($id);

        $statut = [
            'statut_actuel' => $projet->statut,
            'statut_libelle' => $projet->statut_libelle,
            'workflow_possible' => $projet->getWorkflowPossible(),
            'prochaine_action' => $projet->getProchainePossibleAction(),
            'financement' => $projet->getStatutFinancement(),
            'validations' => $projet->validate(),
            'coherence' => $projet->verifierCoherence(),
            'necessite_action' => $projet->necessiteAction()
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $statut,
                'message' => 'Statut détaillé récupéré avec succès'
            ]);
        }

        return view('components.private.projets.statut-detaille', compact('projet', 'statut'));

    } catch (\Exception $e) {
        Log::error('Erreur lors de la récupération du statut détaillé: ' . $e->getMessage());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du statut',
                'error' => $e->getMessage()
            ], 500);
        }

        return redirect()->back()
                       ->with('error', 'Erreur lors de la récupération du statut');
    }
}



    /**
 *  Forcer la mise en attente d'un projet
 */
public function forcerMiseEnAttente(string $id, Request $request)
{
    try {
        $projet = Projet::findOrFail($id);

        if ($projet->statut !== 'recherche_financement') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette action n\'est disponible que pour les projets en recherche de financement'
                ], 400);
            }

            return redirect()->back()
                           ->with('error', 'Cette action n\'est disponible que pour les projets en recherche de financement');
        }

        $validator = Validator::make($request->all(), [
            'justification' => 'required|string|min:10',
            'confirmation' => 'required|accepted'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Justification requise',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                           ->withErrors($validator);
        }

        $success = $projet->forcerMiseEnAttente($request->justification);

        if ($success) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $projet->refresh(),
                    'message' => 'Projet forcé en attente avec succès'
                ]);
            }

            return redirect()->route('private.projets.show', $projet->id)
                           ->with('success', 'Projet forcé en attente avec succès')
                           ->with('warning', 'Le financement n\'est pas encore complet');
        } else {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du forçage en attente'
                ], 500);
            }

            return redirect()->back()
                           ->with('error', 'Erreur lors du forçage en attente');
        }

    } catch (\Exception $e) {
        Log::error('Erreur lors du forçage en attente: ' . $e->getMessage());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du forçage en attente',
                'error' => $e->getMessage()
            ], 500);
        }

        return redirect()->back()
                       ->with('error', 'Erreur lors du forçage en attente');
    }
}





}
