<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\Moisson;
use App\Models\Classe;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PassageMoissonRequest;
use App\Models\PassageMoisson;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PassageMoissonsController extends Controller
{
    /**
     * Liste des passages d'une moisson
     */
    public function index(Request $request, Moisson $moisson)
    {
        $query = $moisson->passageMoissons()
            ->with(['classe:id,nom', 'collecteur:id,nom', 'createur:id,nom']);

        // Filtres
        if ($request->filled('categorie')) {
            $query->where('categorie', $request->input('categorie'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        if ($request->filled('objectif_atteint')) {
            if ($request->boolean('objectif_atteint')) {
                $query->objectifAtteint();
            } else {
                $query->where('reste', '>', 0);
            }
        }

        // Tri
        $sortField = $request->input('tri', 'collecte_le');
        $sortDirection = $request->input('ordre', 'desc');
        $allowedSorts = ['collecte_le', 'categorie', 'cible', 'montant_solde', 'created_at'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $passages = $query->paginate($request->input('per_page', 10));

        // Statistiques pour le tableau de bord
        $statistiques = [
            'total_passages' => $moisson->passageMoissons()->count(),
            'passages_actifs' => $moisson->passageMoissons()->where('status', true)->count(),
            'objectifs_atteints' => $moisson->passageMoissons()->objectifAtteint()->count(),
            'montant_total_collecte' => $moisson->passageMoissons()->sum('montant_solde'),
            'montant_total_cible' => $moisson->passageMoissons()->sum('cible'),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $passages->items(),
                'statistiques' => $statistiques,
                'meta' => [
                    'current_page' => $passages->currentPage(),
                    'last_page' => $passages->lastPage(),
                    'per_page' => $passages->perPage(),
                    'total' => $passages->total()
                ]
            ]);
        }

        return view('components.private.moissons.passages.index', compact('moisson', 'passages', 'statistiques'));
    }

    /**
     * Formulaire de création d'un passage
     */
    public function create(Moisson $moisson)
    {
        $collecteurs = User::orderByRaw('LOWER(nom) ASC')->get();
        $classes = Classe::orderByRaw('LOWER(nom) ASC')->get();

        // Catégories déjà utilisées pour cette moisson
        $categoriesUtilisees = $moisson->passageMoissons()
            ->pluck('categorie')
            ->toArray();

        return view('components.private.moissons.passages.create', compact(
            'moisson',
            'collecteurs',
            'classes',
            'categoriesUtilisees'
        ));
    }

    /**
     * Enregistrement d'un nouveau passage
     */
    public function store(PassageMoissonRequest $request, Moisson $moisson)
    {
        try {
            DB::beginTransaction();

            $donnees = $request->validated();
            $donnees['moisson_id'] = $moisson->id;
            $donnees['creer_par'] = auth()->id();
            $donnees['collecte_le'] = now();

            // Vérifier l'unicité de la catégorie pour cette moisson
            $existeDeja = $moisson->passageMoissons()
                ->where('categorie', $donnees['categorie'])
                ->when(
                    $donnees['categorie'] === 'passage_classe_communautaire',
                    fn($q) => $q->where('classe_id', $donnees['classe_id'])
                )
                ->exists();

            if ($existeDeja) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cette catégorie de passage existe déjà pour cette moisson'
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors(['categorie' => 'Cette catégorie de passage existe déjà pour cette moisson'])
                    ->withInput();
            }

            $passage = PassageMoisson::create($donnees);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Passage créé avec succès',
                    'data' => $passage->load(['classe', 'collecteur', 'createur'])
                ], 201);
            }

            return redirect()
                ->route('private.moissons.passages.show', [$moisson, $passage])
                ->with('success', 'Passage créé avec succès');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du passage',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la création du passage')
                ->withInput();
        }
    }

    /**
     * Affichage détaillé d'un passage
     */
    public function show(Request $request, Moisson $moisson, PassageMoisson $passageMoisson)
    {
        $passageMoisson->load(['classe', 'collecteur', 'createur', 'moisson']);

        // Historique des modifications
        $historique = $passageMoisson->editeurs ?? [];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'passage' => $passageMoisson,
                    'historique' => $historique,
                    'statistiques' => [
                        'pourcentage_realise' => $passageMoisson->pourcentage_realise,
                        'objectif_atteint' => $passageMoisson->objectif_atteint,
                        'statut_progression' => $this->getStatutProgression($passageMoisson->pourcentage_realise)
                    ]
                ]
            ]);
        }

        return view('components.private.moissons.passages.show', compact(
            'moisson',
            'passageMoisson',
            'historique'
        ));
    }

    /**
     * Formulaire d'édition d'un passage
     */
    public function edit(Moisson $moisson, PassageMoisson $passageMoisson)
    {
        $collecteurs = User::orderByRaw('LOWER(nom) ASC')->get();
        $classes = Classe::orderByRaw('LOWER(nom) ASC')->get();

        return view('components.private.moissons.passages.edit', compact(
            'moisson',
            'passageMoisson',
            'collecteurs',
            'classes'
        ));
    }

    /**
     * Mise à jour d'un passage
     */
    public function update(PassageMoissonRequest $request, Moisson $moisson, PassageMoisson $passageMoisson)
    {
        try {
            DB::beginTransaction();
// dd($request->all());
            $donnees = $request->validated();

            // Ajouter à l'historique avant modification
            $passageMoisson->ajouterEditeur(auth()->id(), 'modification');

            $passageMoisson->update($donnees);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Passage mis à jour avec succès',
                    'data' => $passageMoisson->fresh()->load(['classe', 'collecteur', 'createur'])
                ]);
            }

            return redirect()
                ->route('private.moissons.passages.show', [$moisson, $passageMoisson])
                ->with('success', 'Passage mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour')
                ->withInput();
        }
    }

    /**
     * Suppression d'un passage
     */
    public function destroy(Request $request, Moisson $moisson, PassageMoisson $passageMoisson)
    {
        try {
            $passageMoisson->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Passage supprimé avec succès'
                ]);
            }

            return redirect()
                ->route('private.moissons.passages.index', $moisson)
                ->with('success', 'Passage supprimé avec succès');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression');
        }
    }

    /**
     * Ajouter un montant à un passage
     */
    public function ajouterMontant(Request $request, Moisson $moisson, PassageMoisson $passageMoisson): JsonResponse
    {
        $request->validate([
            'montant' => 'required|numeric|min:0.01|max:99999999999999.99',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $montant = $request->input('montant');
            $notes = $request->input('notes');

            $ancienMontant = $passageMoisson->montant_solde;
            $passageMoisson->ajouterMontant($montant, auth()->id());

            // Ajouter à l'historique
            $passageMoisson->ajouterEditeur(auth()->id(), 'ajout_montant', [
                'ancien_montant' => $ancienMontant,
                'montant_ajoute' => $montant,
                'nouveau_montant' => $passageMoisson->montant_solde,
                'notes' => $notes
            ]);

            $passageMoisson->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Montant ajouté avec succès',
                'data' => [
                    'ancien_montant' => $ancienMontant,
                    'montant_ajoute' => $montant,
                    'nouveau_montant' => $passageMoisson->montant_solde,
                    'reste' => $passageMoisson->reste,
                    'pourcentage' => $passageMoisson->pourcentage_realise
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du montant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Basculer le statut d'un passage
     */
    public function toggleStatus(Request $request, Moisson $moisson, PassageMoisson $passageMoisson): JsonResponse
    {
        try {
            $passageMoisson->status = !$passageMoisson->status;
            $passageMoisson->ajouterEditeur(auth()->id(), $passageMoisson->status ? 'activation' : 'desactivation');
            $passageMoisson->save();

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'data' => [
                    'status' => $passageMoisson->status,
                    'status_text' => $passageMoisson->status ? 'Actif' : 'Inactif'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistiques des passages pour une moisson
     */
    public function statistiques(Request $request, Moisson $moisson): JsonResponse
    {
        try {
            $statistiques = [
                'globales' => [
                    'total_passages' => $moisson->passageMoissons()->count(),
                    'passages_actifs' => $moisson->passageMoissons()->where('status', true)->count(),
                    'objectifs_atteints' => $moisson->passageMoissons()->objectifAtteint()->count(),
                    'montant_total_collecte' => $moisson->passageMoissons()->sum('montant_solde'),
                    'montant_total_cible' => $moisson->passageMoissons()->sum('cible'),
                ],
                'par_categorie' => PassageMoisson::statistiquesParCategorie($moisson->id),
                'progression' => $this->calculerProgressionPassages($moisson)
            ];

            return response()->json([
                'success' => true,
                'data' => $statistiques
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Calculer la progression des passages
     */
    private function calculerProgressionPassages(Moisson $moisson): array
    {
        $passages = $moisson->passageMoissons()->get();

        $progression = [];
        foreach (PassageMoisson::CATEGORIES as $code => $libelle) {
            $passage = $passages->where('categorie', $code)->first();

            if ($passage) {
                $progression[] = [
                    'categorie' => $code,
                    'libelle' => $libelle,
                    'cible' => $passage->cible,
                    'collecte' => $passage->montant_solde,
                    'pourcentage' => $passage->pourcentage_realise,
                    'statut' => $this->getStatutProgression($passage->pourcentage_realise),
                    'status' => $passage->status
                ];
            }
        }

        return $progression;
    }

    /**
     * Obtenir le statut de progression basé sur le pourcentage
     */
    private function getStatutProgression(float $pourcentage): string
    {
        if ($pourcentage >= 100) return 'Objectif atteint';
        if ($pourcentage >= 90) return 'Presque atteint';
        if ($pourcentage >= 70) return 'Bonne progression';
        if ($pourcentage >= 50) return 'En cours';
        if ($pourcentage >= 30) return 'Début';
        return 'Très faible';
    }

    /**
     * Exporter les données des passages
     */
    public function exporter(Request $request, Moisson $moisson): JsonResponse
    {
        $request->validate([
            'format' => 'sometimes|in:json,csv,excel'
        ]);

        try {
            $format = $request->input('format', 'json');
            $passages = $moisson->passageMoissons()
                ->with(['classe', 'collecteur', 'createur'])
                ->get();

            $donnees = $passages->map(function ($passage) {
                return [
                    'id' => $passage->id,
                    'categorie' => $passage->categorie_libelle,
                    'classe' => $passage->classe?->nom,
                    'cible' => $passage->cible,
                    'montant_collecte' => $passage->montant_solde,
                    'reste' => $passage->reste,
                    'supplement' => $passage->montant_supplementaire,
                    'pourcentage_realise' => $passage->pourcentage_realise,
                    'collecteur' => $passage->collecteur?->nom,
                    'date_collecte' => $passage->collecte_le,
                    'status' => $passage->status ? 'Actif' : 'Inactif',
                    'objectif_atteint' => $passage->objectif_atteint ? 'Oui' : 'Non'
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $donnees,
                'meta' => [
                    'format' => $format,
                    'moisson' => $moisson->theme,
                    'date_export' => now()->toISOString(),
                    'total_passages' => $passages->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'export',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
