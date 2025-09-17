<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\Moisson;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EngagementMoissonRequest;
use App\Models\EngagementMoisson;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EngagementMoissonsController extends Controller
{
    /**
     * Liste des engagements d'une moisson
     */
    public function index(Request $request, Moisson $moisson)
    {
        $query = $moisson->engagementMoissons()
            ->with(['donateur:id,nom', 'collecteur:id,nom', 'createur:id,nom']);

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

        if ($request->filled('en_retard')) {
            if ($request->boolean('en_retard')) {
                $query->enRetard();
            }
        }

        if ($request->filled('niveau_urgence')) {
            $query->parNiveauUrgence($request->input('niveau_urgence'));
        }

        if ($request->filled('recherche')) {
            $terme = $request->input('recherche');
            $query->rechercheTexte($terme);
        }

        // Tri
        $sortField = $request->input('tri', 'collecter_le');
        $sortDirection = $request->input('ordre', 'desc');
        $allowedSorts = ['collecter_le', 'categorie', 'cible', 'montant_solde', 'date_echeance', 'created_at'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        }

        $engagements = $query->paginate($request->input('per_page', 15));

        // Statistiques pour le tableau de bord
        $statistiques = [
            'total_engagements' => $moisson->engagementMoissons()->count(),
            'engagements_actifs' => $moisson->engagementMoissons()->where('status', true)->count(),
            'objectifs_atteints' => $moisson->engagementMoissons()->objectifAtteint()->count(),
            'engagements_en_retard' => $moisson->engagementMoissons()->enRetard()->count(),
            'montant_total_collecte' => $moisson->engagementMoissons()->sum('montant_solde'),
            'montant_total_cible' => $moisson->engagementMoissons()->sum('cible'),
            'montant_reste_total' => $moisson->engagementMoissons()->sum('reste'),
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $engagements->items(),
                'statistiques' => $statistiques,
                'meta' => [
                    'current_page' => $engagements->currentPage(),
                    'last_page' => $engagements->lastPage(),
                    'per_page' => $engagements->perPage(),
                    'total' => $engagements->total()
                ]
            ]);
        }

        return view('components.private.moissons.engagements.index', compact('moisson', 'engagements', 'statistiques'));
    }

    /**
     * Formulaire de création d'un engagement
     */
    public function create(Moisson $moisson)
    {
        $collecteurs = User::orderByRaw('LOWER(nom) ASC')->get();
        $donateurs = User::orderByRaw('LOWER(nom) ASC')->get();

        return view('components.private.moissons.engagements.create', compact(
            'moisson',
            'collecteurs',
            'donateurs'
        ));
    }

    /**
     * Enregistrement d'un nouvel engagement
     */
    public function store(EngagementMoissonRequest $request, Moisson $moisson)
    {
        try {
            DB::beginTransaction();

            $donnees = $request->validated();
            $donnees['moisson_id'] = $moisson->id;
            $donnees['creer_par'] = auth()->id();
            $donnees['collecter_le'] = now();

            $engagement = EngagementMoisson::create($donnees);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Engagement créé avec succès',
                    'data' => $engagement->load(['donateur', 'collecteur', 'createur'])
                ], 201);
            }

            return redirect()
                ->route('private.moissons.engagements.show', [$moisson, $engagement])
                ->with('success', 'Engagement créé avec succès');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'engagement',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la création de l\'engagement')
                ->withInput();
        }
    }

    /**
     * Affichage détaillé d'un engagement
     */
    public function show(Request $request, Moisson $moisson, EngagementMoisson $engagementMoisson)
    {
        $engagementMoisson->load(['donateur', 'collecteur', 'createur', 'moisson']);

        // Historique des modifications
        $historique = $engagementMoisson->editeurs ?? [];

        // Statistiques de l'engagement
        $statistiquesEngagement = $engagementMoisson->calculerStatistiques();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'engagement' => $engagementMoisson,
                    'historique' => $historique,
                    'statistiques' => $statistiquesEngagement
                ]
            ]);
        }

        return view('components.private.moissons.engagements.show', compact(
            'moisson',
            'engagementMoisson',
            'historique',
            'statistiquesEngagement'
        ));
    }

    /**
     * Formulaire d'édition d'un engagement
     */
    public function edit(Moisson $moisson, EngagementMoisson $engagementMoisson)
    {
        $collecteurs = User::orderByRaw('LOWER(nom) ASC')->get();
        $donateurs = User::orderByRaw('LOWER(nom) ASC')->get();

        return view('components.private.moissons.engagements.edit', compact(
            'moisson',
            'engagementMoisson',
            'collecteurs',
            'donateurs'
        ));
    }

    /**
     * Mise à jour d'un engagement
     */
    public function update(EngagementMoissonRequest $request, Moisson $moisson, EngagementMoisson $engagementMoisson)
    {
        try {
            DB::beginTransaction();

            $donnees = $request->validated();

            // Ajouter à l'historique avant modification
            $engagementMoisson->ajouterEditeur(auth()->id(), 'modification');

            $engagementMoisson->update($donnees);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Engagement mis à jour avec succès',
                    'data' => $engagementMoisson->fresh()->load(['donateur', 'collecteur', 'createur'])
                ]);
            }

            return redirect()
                ->route('private.moissons.engagements.show', [$moisson, $engagementMoisson])
                ->with('success', 'Engagement mis à jour avec succès');

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
     * Suppression d'un engagement
     */
    public function destroy(Request $request, Moisson $moisson, EngagementMoisson $engagementMoisson)
    {
        try {
            $engagementMoisson->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Engagement supprimé avec succès'
                ]);
            }

            return redirect()
                ->route('private.moissons.engagements.index', $moisson)
                ->with('success', 'Engagement supprimé avec succès');

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
     * Ajouter un paiement à un engagement
     */
    public function ajouterMontant(Request $request, Moisson $moisson, EngagementMoisson $engagementMoisson): JsonResponse
    {
        $request->validate([
            'montant' => 'required|numeric|min:0.01|max:99999999999999.99',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $montant = $request->input('montant');
            $notes = $request->input('notes');

            $ancienMontant = $engagementMoisson->montant_solde;
            $engagementMoisson->ajouterPaiement($montant, auth()->id());

            // Ajouter à l'historique
            $engagementMoisson->ajouterEditeur(auth()->id(), 'ajout_paiement', [
                'ancien_montant' => $ancienMontant,
                'montant_ajoute' => $montant,
                'nouveau_montant' => $engagementMoisson->montant_solde,
                'notes' => $notes
            ]);

            $engagementMoisson->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Paiement ajouté avec succès',
                'data' => [
                    'ancien_montant' => $ancienMontant,
                    'montant_ajoute' => $montant,
                    'nouveau_montant' => $engagementMoisson->montant_solde,
                    'reste' => $engagementMoisson->reste,
                    'pourcentage' => $engagementMoisson->pourcentage_realise,
                    'objectif_atteint' => $engagementMoisson->objectif_atteint
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du paiement',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Planifier un rappel pour un engagement
     */
    public function planifierRappel(Request $request, Moisson $moisson, EngagementMoisson $engagementMoisson): JsonResponse
    {
        $request->validate([
            'date_rappel' => 'required|date|after:today'
        ]);

        try {
            $dateRappel = Carbon::parse($request->input('date_rappel'));

            $engagementMoisson->planifierRappel($dateRappel);

            return response()->json([
                'success' => true,
                'message' => 'Rappel planifié avec succès',
                'data' => [
                    'date_rappel' => $dateRappel->format('d/m/Y'),
                    'jours_restants' => now()->diffInDays($dateRappel)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la planification du rappel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Prolonger l'échéance d'un engagement
     */
    public function prolongerEcheance(Request $request, Moisson $moisson, EngagementMoisson $engagementMoisson): JsonResponse
    {
        $request->validate([
            'nouvelle_echeance' => 'required|date|after:today',
            'motif' => 'nullable|string|max:500'
        ]);

        try {
            $nouvelleEcheance = Carbon::parse($request->input('nouvelle_echeance'));
            $motif = $request->input('motif');

            $engagementMoisson->prolongerEcheance($nouvelleEcheance, $motif);

            return response()->json([
                'success' => true,
                'message' => 'Échéance prolongée avec succès',
                'data' => [
                    'nouvelle_echeance' => $nouvelleEcheance->format('d/m/Y'),
                    'motif' => $motif
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la prolongation de l\'échéance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Basculer le statut d'un engagement
     */
    public function toggleStatus(Request $request, Moisson $moisson, EngagementMoisson $engagementMoisson): JsonResponse
    {
        try {
            $engagementMoisson->status = !$engagementMoisson->status;
            $engagementMoisson->ajouterEditeur(auth()->id(), $engagementMoisson->status ? 'activation' : 'desactivation');
            $engagementMoisson->save();

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'data' => [
                    'status' => $engagementMoisson->status,
                    'status_text' => $engagementMoisson->status ? 'Actif' : 'Inactif'
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
     * Marquer un rappel comme effectué
     */
    public function marquerRappelEffectue(Request $request, Moisson $moisson, EngagementMoisson $engagementMoisson): JsonResponse
    {
        try {
            $engagementMoisson->marquerRappelEffectue();

            return response()->json([
                'success' => true,
                'message' => 'Rappel marqué comme effectué',
                'data' => [
                    'date_rappel' => null,
                    'doit_etre_rappele' => false
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du rappel',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistiques des engagements pour une moisson
     */
    public function statistiques(Request $request, Moisson $moisson): JsonResponse
    {
        try {
            $statistiques = EngagementMoisson::statistiquesGlobales($moisson->id);

            $detailsEngagements = [
                'par_categorie' => $statistiques['par_categorie'],
                'retards' => $statistiques['retards'],
                'rappels_jour' => EngagementMoisson::getRappelsDuJour()
                    ->where('moisson_id', $moisson->id)
                    ->count(),
                'evolution_mensuelle' => $this->getEvolutionMensuelle($moisson)
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'globales' => $statistiques['totaux'],
                    'details' => $detailsEngagements
                ]
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
     * Obtenir l'évolution mensuelle des engagements
     */
    private function getEvolutionMensuelle(Moisson $moisson): array
    {
        return $moisson->engagementMoissons()
            ->where('status', true)
            ->selectRaw('
                DATE_TRUNC(\'month\', collecter_le) as mois,
                SUM(montant_solde) as total_collecte,
                COUNT(*) as nombre_engagements
            ')
            ->groupBy('mois')
            ->orderBy('mois')
            ->get()
            ->map(function ($row) {
                return [
                    'mois' => $row->mois,
                    'total_collecte' => $row->total_collecte,
                    'nombre_engagements' => $row->nombre_engagements
                ];
            })
            ->toArray();
    }

    /**
     * Exporter les données des engagements
     */
    public function exporter(Request $request, Moisson $moisson): JsonResponse
    {
        $request->validate([
            'format' => 'sometimes|in:json,csv,excel'
        ]);

        try {
            $format = $request->input('format', 'json');
            $engagements = $moisson->engagementMoissons()
                ->with(['donateur', 'collecteur', 'createur'])
                ->get();

            $donnees = $engagements->map(function ($engagement) {
                return [
                    'id' => $engagement->id,
                    'categorie' => $engagement->categorie_libelle,
                    'donateur' => $engagement->nom_donateur,
                    'telephone' => $engagement->telephone,
                    'email' => $engagement->email,
                    'adresse' => $engagement->adresse,
                    'description' => $engagement->description,
                    'engagement' => $engagement->cible,
                    'montant_verse' => $engagement->montant_solde,
                    'reste' => $engagement->reste,
                    'supplement' => $engagement->montant_supplementaire,
                    'pourcentage_realise' => $engagement->pourcentage_realise,
                    'date_echeance' => $engagement->date_echeance?->format('d/m/Y'),
                    'en_retard' => $engagement->est_en_retard ? 'Oui' : 'Non',
                    'jours_retard' => $engagement->jours_retard,
                    'niveau_urgence' => $engagement->niveau_urgence_libelle,
                    'collecteur' => $engagement->collecteur?->nom,
                    'date_collecte' => $engagement->collecter_le?->format('d/m/Y H:i'),
                    'status' => $engagement->status ? 'Actif' : 'Inactif',
                    'objectif_atteint' => $engagement->objectif_atteint ? 'Oui' : 'Non'
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $donnees,
                'meta' => [
                    'format' => $format,
                    'moisson' => $moisson->theme,
                    'date_export' => now()->toISOString(),
                    'total_engagements' => $engagements->count()
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
