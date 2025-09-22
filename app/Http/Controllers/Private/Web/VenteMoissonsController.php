<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\Moisson;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\VenteMoissonRequest;
use App\Models\VenteMoisson;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class VenteMoissonsController extends Controller
{
    /**
     * Liste des ventes d'une moisson
     */
    public function index(Request $request, Moisson $moisson)
    {
        $query = $moisson->venteMoissons()
            ->with(['collecteur:id,nom', 'createur:id,nom']);

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



        if ($request->filled('recherche')) {
            $terme = $request->input('recherche');
            $query->rechercheDescription($terme);
        }

        // Tri
        $sortField = $request->input('tri', 'collecte_le');
        $sortDirection = $request->input('ordre', 'desc');
        $allowedSorts = ['collecte_le', 'categorie', 'cible', 'montant_solde', 'created_at'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $ventes = $query->paginate($request->input('per_page', 10));

        // Statistiques pour le tableau de bord
        $statistiques = [
            'total_ventes' => $moisson->venteMoissons()->count(),
            'ventes_actives' => $moisson->venteMoissons()->where('status', true)->count(),
            'objectifs_atteints' => $moisson->venteMoissons()->objectifAtteint()->count(),
            'montant_total_collecte' => $moisson->venteMoissons()->sum('montant_solde'),
            'montant_total_cible' => $moisson->venteMoissons()->sum('cible'),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $ventes->items(),
                'statistiques' => $statistiques,
                'meta' => [
                    'current_page' => $ventes->currentPage(),
                    'last_page' => $ventes->lastPage(),
                    'per_page' => $ventes->perPage(),
                    'total' => $ventes->total()
                ]
            ]);
        }

        return view('components.private.moissons.ventes.index', compact('moisson', 'ventes', 'statistiques'));
    }

    /**
     * Formulaire de création d'une vente
     */
    public function create(Moisson $moisson)
    {
        $collecteurs = User::orderByRaw('LOWER(nom) ASC')->get();

        // Catégories déjà utilisées pour cette moisson
        $categoriesUtilisees = $moisson->venteMoissons()
            ->pluck('categorie')
            ->toArray();

        return view('components.private.moissons.ventes.create', compact(
            'moisson',
            'collecteurs',
            'categoriesUtilisees'
        ));
    }

    /**
     * Enregistrement d'une nouvelle vente
     */
    public function store(VenteMoissonRequest $request, Moisson $moisson)
    {
        try {
            DB::beginTransaction();

            $donnees = $request->validated();
            $donnees['moisson_id'] = $moisson->id;
            $donnees['creer_par'] = auth()->id();
            $donnees['collecte_le'] = now();

            // Vérifier l'unicité de la catégorie pour cette moisson
            $existeDeja = $moisson->venteMoissons()
                ->where('categorie', $donnees['categorie'])
                ->exists();

            if ($existeDeja) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cette catégorie de vente existe déjà pour cette moisson'
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors(['categorie' => 'Cette catégorie de vente existe déjà pour cette moisson'])
                    ->withInput();
            }

            $vente = VenteMoisson::create($donnees);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vente créée avec succès',
                    'data' => $vente->load(['collecteur', 'createur'])
                ], 201);
            }

            return redirect()
                ->route('private.moissons.ventes.show', [$moisson, $vente])
                ->with('success', 'Vente créée avec succès');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de la vente',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la vente')
                ->withInput();
        }
    }

    /**
     * Affichage détaillé d'une vente
     */
    public function show(Request $request, Moisson $moisson, VenteMoisson $venteMoisson)
    {
        $venteMoisson->load(['collecteur', 'createur', 'moisson']);

        // Historique des modifications
        $historique = $venteMoisson->editeurs ?? [];

        // Statistiques de la vente
        $statistiquesVente = $venteMoisson->calculerStatsVente();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'vente' => $venteMoisson,
                    'historique' => $historique,
                    'statistiques' => $statistiquesVente
                ]
            ]);
        }

        return view('components.private.moissons.ventes.show', compact(
            'moisson',
            'venteMoisson',
            'historique',
            'statistiquesVente'
        ));
    }

    /**
     * Formulaire d'édition d'une vente
     */
    public function edit(Moisson $moisson, VenteMoisson $venteMoisson)
    {
        $collecteurs = User::orderByRaw('LOWER(nom) ASC')->get();

        return view('components.private.moissons.ventes.edit', compact(
            'moisson',
            'venteMoisson',
            'collecteurs'
        ));
    }

    /**
     * Mise à jour d'une vente
     */
    public function update(VenteMoissonRequest $request, Moisson $moisson, VenteMoisson $venteMoisson)
    {
        try {
            DB::beginTransaction();

            $donnees = $request->validated();

            // Ajouter à l'historique avant modification
            $venteMoisson->ajouterEditeur(auth()->id(), 'modification');

            $venteMoisson->update($donnees);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vente mise à jour avec succès',
                    'data' => $venteMoisson->fresh()->load(['collecteur', 'createur'])
                ]);
            }

            return redirect()
                ->route('private.moissons.ventes.show', [$moisson, $venteMoisson])
                ->with('success', 'Vente mise à jour avec succès');

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
     * Suppression d'une vente
     */
    public function destroy(Request $request, Moisson $moisson, VenteMoisson $venteMoisson)
    {
        try {
            $venteMoisson->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Vente supprimée avec succès'
                ]);
            }

            return redirect()
                ->route('private.moissons.ventes.index', $moisson)
                ->with('success', 'Vente supprimée avec succès');

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
     * Ajouter une vente (montant)
     */
    public function ajouterMontant(Request $request, Moisson $moisson, VenteMoisson $venteMoisson): JsonResponse
    {
        $request->validate([
            'montant' => 'required|numeric|min:0.01|max:99999999999999.99',
        ]);

        try {
            DB::beginTransaction();

            $montant = $request->input('montant');
            $notes = $request->input('notes');

            $ancienMontant = $venteMoisson->montant_solde;

            $venteMoisson->ajouterVente($montant, auth()->id());

            // Ajouter à l'historique
            $venteMoisson->ajouterEditeur(auth()->id(), $notes ?? 'ajout_vente');

            $venteMoisson->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Vente ajoutée avec succès',
                'data' => [
                    'ancien_montant' => $ancienMontant,
                    'montant_ajoute' => $montant,
                    'nouveau_montant' => $venteMoisson->montant_solde,
                    'reste' => $venteMoisson->reste,
                    'pourcentage' => $venteMoisson->pourcentage_realise,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de la vente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Basculer le statut d'une vente
     */
    public function toggleStatus(Request $request, Moisson $moisson, VenteMoisson $venteMoisson): JsonResponse
    {
        try {
            $venteMoisson->status = !$venteMoisson->status;
            $venteMoisson->ajouterEditeur(auth()->id(), $venteMoisson->status ? 'activation' : 'desactivation');
            $venteMoisson->save();

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'data' => [
                    'status' => $venteMoisson->status,
                    'status_text' => $venteMoisson->status ? 'Actif' : 'Inactif'
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
     * Statistiques des ventes pour une moisson
     */
    public function statistiques(Request $request, Moisson $moisson): JsonResponse
    {
        try {
            $statistiques = [
                'globales' => [
                    'total_ventes' => $moisson->venteMoissons()->count(),
                    'ventes_actives' => $moisson->venteMoissons()->where('status', true)->count(),
                    'objectifs_atteints' => $moisson->venteMoissons()->objectifAtteint()->count(),
                    'montant_total_collecte' => $moisson->venteMoissons()->sum('montant_solde'),
                    'montant_total_cible' => $moisson->venteMoissons()->sum('cible'),
                ],
                'par_categorie' => VenteMoisson::statistiquesParCategorie($moisson->id),
                'top_ventes' => $this->getTopVentes($moisson),
                'evolution_mensuelle' => $this->getEvolutionMensuelle($moisson)
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
     * Obtenir les meilleures ventes pour une moisson
     */
    private function getTopVentes(Moisson $moisson): array
    {
        return $moisson->venteMoissons()
            ->where('status', true)
            ->orderByDesc('montant_solde')
            ->limit(5)
            ->get()
            ->map(function ($vente) {
                return [
                    'categorie' => $vente->categorie_libelle,
                    'montant' => $vente->montant_solde,
                    'pourcentage' => $vente->pourcentage_realise,
                ];
            })
            ->toArray();
    }

    /**
     * Obtenir l'évolution mensuelle des ventes
     */
    private function getEvolutionMensuelle(Moisson $moisson): array
    {
        return $moisson->venteMoissons()
            ->where('status', true)
            ->selectRaw('
                DATE_TRUNC(\'month\', collecte_le) as mois,
                SUM(montant_solde) as total_montant,
                COUNT(*) as nombre_ventes
            ')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->map(function ($row) {
                return [
                    'mois' => $row->mois,
                    'total_montant' => $row->total_montant,
                    'nombre_ventes' => $row->nombre_ventes
                ];
            })
            ->toArray();
    }

    /**
     * Exporter les données des ventes
     */
    public function exporter(Request $request, Moisson $moisson): JsonResponse
    {
        $request->validate([
            'format' => 'sometimes|in:json,csv,excel'
        ]);

        try {
            $format = $request->input('format', 'json');
            $ventes = $moisson->venteMoissons()
                ->with(['collecteur', 'createur'])
                ->get();

            $donnees = $ventes->map(function ($vente) {
                return [
                    'id' => $vente->id,
                    'categorie' => $vente->categorie_libelle,
                    'cible' => $vente->cible,
                    'montant_collecte' => $vente->montant_solde,
                    'reste' => $vente->reste,
                    'supplement' => $vente->montant_supplementaire,
                    'pourcentage_realise' => $vente->pourcentage_realise,
                    'montant_theorique' => $vente->montant_theorique,
                    'marge_beneficiaire' => $vente->benefice_marge,
                    'description' => $vente->description,
                    'collecteur' => $vente->collecteur?->nom,
                    'date_collecte' => $vente->collecte_le,
                    'status' => $vente->status ? 'Actif' : 'Inactif',
                    'objectif_atteint' => $vente->objectif_atteint ? 'Oui' : 'Non'
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $donnees,
                'meta' => [
                    'format' => $format,
                    'moisson' => $moisson->theme,
                    'date_export' => now()->toISOString(),
                    'total_ventes' => $ventes->count()
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
