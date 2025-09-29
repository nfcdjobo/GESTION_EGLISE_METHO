<?php

namespace App\Http\Controllers\Private\Web;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Reunion;
use Illuminate\View\View;
use App\Models\TypeReunion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReunionController extends Controller
{
    /**
     * Détermine si la requête attend une réponse JSON
     */
    private function expectsJson(Request $request): bool
    {
        return $request->expectsJson() ||
            $request->is('api/*') ||
            $request->header('Accept') === 'application/json' ||
            $request->get('format') === 'json';
    }

    /**
     * Liste des réunions
     */
    public function index(Request $request)
    {
        try {
            $query = Reunion::with([
                'typeReunion:id,nom,couleur,categorie',
                'organisateurPrincipal:id,nom,prenom',
                'animateur:id,nom,prenom'
            ]);

            // Filtres
            if ($request->filled('statut')) {
                $query->parStatut($request->statut);
            }

            if ($request->filled('type_reunion_id')) {
                $query->where('type_reunion_id', $request->type_reunion_id);
            }

            if ($request->filled('organisateur_id')) {
                $query->parOrganisateur($request->organisateur_id);
            }

            if ($request->filled('lieu')) {
                $query->parLieu($request->lieu);
            }

            if ($request->filled('date_debut') && $request->filled('date_fin')) {
                $query->whereBetween('date_reunion', [
                    $request->date_debut,
                    $request->date_fin
                ]);
            }

            if ($request->filled('diffusion_en_ligne')) {
                $query->avecDiffusionEnLigne();
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('titre', 'ILIKE', "%{$search}%")
                        ->orWhere('description', 'ILIKE', "%{$search}%")
                        ->orWhere('lieu', 'ILIKE', "%{$search}%");
                });
            }

            // Tri
            $sortBy = $request->get('sort_by', 'date_reunion');
            $sortOrder = $request->get('sort_order', 'desc');

            if ($sortBy === 'date_reunion') {
                $query->orderBy('date_reunion', $sortOrder)
                    ->orderBy('heure_debut_prevue', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }

            if ($request->boolean('paginate', true)) {
                $perPage = min($request->get('per_page', default: 10), 100);
                $reunions = $query->paginate($perPage);
            } else {
                $reunions = $query->get();
            }

            if ($this->expectsJson($request)) {
                return response()->json($reunions);
            }

            // Données pour la vue
            $typesReunions = TypeReunion::actif()->get(['id', 'nom', 'couleur']);
            $statuts = $this->getStatutsArray();

            return view('components.private.reunions.index', compact('reunions', 'typesReunions', 'statuts'));

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la récupération des réunions',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération des réunions: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        if ($this->expectsJson($request)) {
            return response()->json([
                'types_reunions' => TypeReunion::actif()->get(['id', 'nom', 'couleur', 'duree_standard']),
                'statuts' => $this->getStatutsArray(),
                'niveaux_priorite' => $this->getNiveauxPrioriteArray(),
                'organisateurs' => $this->getOrganisateursArray()
            ]);
        }

        $typesReunions = TypeReunion::actif()->get();
        $statuts = $this->getStatutsArray();
        $niveauxPriorite = $this->getNiveauxPrioriteArray();

        $users = User::orderByRaw('LOWER(nom) ASC')->get();

        return view('components.private.reunions.create', compact('typesReunions', 'statuts', 'niveauxPriorite', 'users'));
    }

    /**
     * Créer une nouvelle réunion
     */
    public function store(Request $request)
    {
        $validator = $this->validateReunion($request);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            DB::beginTransaction();

            // Récupérer le type de réunion pour marquer son utilisation
            $typeReunion = TypeReunion::findOrFail($request->type_reunion_id);
            $typeReunion->marquerUtilise();

            $reunion = Reunion::create(array_merge(
                $validator->validated(),
                ['cree_par' => auth()->id()]
            ));

            DB::commit();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion créée avec succès',
                    'data' => $reunion->load(['typeReunion', 'organisateurPrincipal'])
                ], 201);
            }

            return redirect()
                ->route('private.reunions.show', $reunion)
                ->with('success', 'Réunion créée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la création de la réunion',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Afficher une réunion spécifique
     */
    public function show(Request $request, Reunion $reunion)
    {
        try {
            $reunion->load([
                'typeReunion',
                'organisateurPrincipal:id,nom,prenom,email',
                'animateur:id,nom,prenom,email',
                'responsableTechnique:id,nom,prenom,email',
                'responsableAccueil:id,nom,prenom,email',
                'createur:id,nom,prenom',
                'modificateur:id,nom,prenom',
                'rapports:id,titre_rapport,type_rapport,statut'
            ]);

            if ($this->expectsJson($request)) {
                return response()->json(['data' => $reunion]);
            }

            return view('components.private.reunions.show', compact('reunion'));

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion introuvable',
                    'error' => $e->getMessage()
                ], 404);
            }

            return back()->with('error', 'Réunion introuvable');
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Request $request, Reunion $reunion)
    {
        if ($this->expectsJson($request)) {
            return response()->json([
                'data' => $reunion->load(['typeReunion', 'organisateurPrincipal']),
                'types_reunions' => TypeReunion::actif()->get(['id', 'nom', 'couleur']),
                'statuts' => $this->getStatutsArray(),
                'niveaux_priorite' => $this->getNiveauxPrioriteArray()
            ]);
        }

        $typesReunions = TypeReunion::actif()->get();
        $statuts = $this->getStatutsArray();
        $niveauxPriorite = $this->getNiveauxPrioriteArray();
        $users = User::orderByRaw('LOWER(nom) ASC')->get();

        return view('components.private.reunions.edit', compact('reunion', 'typesReunions', 'statuts', 'niveauxPriorite', 'users'));
    }

    /**
     * Mettre à jour une réunion
     */
    public function update(Request $request, Reunion $reunion)
    {
        $validator = $this->validateReunion($request, $reunion);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            DB::beginTransaction();

            $reunion->update(array_merge(
                $validator->validated(),
                ['modifie_par' => auth()->id()]
            ));

            DB::commit();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion mise à jour avec succès',
                    'data' => $reunion->load(['typeReunion', 'organisateurPrincipal'])
                ]);
            }

            return redirect()
                ->route('private.reunions.show', $reunion)
                ->with('success', 'Réunion mise à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la mise à jour de la réunion',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une réunion
     */
    public function destroy(Request $request, Reunion $reunion)
    {
        if (!in_array($reunion->statut, ['planifiee', 'confirmee'])) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Seules les réunions planifiées ou confirmées peuvent être supprimées'
                ], 400);
            }

            return back()->with('error', 'Seules les réunions planifiées ou confirmées peuvent être supprimées');
        }

        try {
            $reunion->delete();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion supprimée avec succès'
                ]);
            }

            return redirect()
                ->route('private.reunions.index')
                ->with('success', 'Réunion supprimée avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la suppression de la réunion',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Confirmer une réunion
     */
    public function confirmer(Request $request, Reunion $reunion)
    {
        if (!in_array($reunion->statut, ['planifiee'])) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Seules les réunions planifiées peuvent être confirmées'
                ], 400);
            }

            return back()->with('error', 'Seules les réunions planifiées peuvent être confirmées');
        }

        try {
            $reunion->update([
                'statut' => 'confirmee',
                'modifie_par' => auth()->id(),
                'validee_par' => auth()->id(),
                'validee_le' => now()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion confirmée avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return back()->with('success', 'Réunion confirmée avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la confirmation',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la confirmation: ' . $e->getMessage());
        }
    }

    /**
     * Commencer une réunion
     */
    public function commencer(Request $request, Reunion $reunion)
    {
        if (!$reunion->peutCommencer()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Cette réunion ne peut pas être commencée dans son état actuel'
                ], 400);
            }

            return back()->with('error', 'Cette réunion ne peut pas être commencée dans son état actuel');
        }

        try {
            $reunion->commencer(auth()->id());

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion commencée avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return back()->with('success', 'Réunion commencée avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors du commencement de la réunion',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors du commencement: ' . $e->getMessage());
        }
    }

    /**
     * Terminer une réunion
     */
    public function terminer(Request $request, Reunion $reunion)
    {
        if (!$reunion->peutEtreTerminee()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Cette réunion ne peut pas être terminée dans son état actuel'
                ], 400);
            }

            return back()->with('error', 'Cette réunion ne peut pas être terminée dans son état actuel');
        }

        try {
            $reunion->terminer(auth()->id());

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion terminée avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return back()->with('success', 'Réunion terminée avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la fin de la réunion',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la fin: ' . $e->getMessage());
        }
    }

    /**
     * Annuler une réunion
     */
    public function annuler(Request $request, Reunion $reunion)
    {
        if (!$reunion->peutEtreAnnulee()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Cette réunion ne peut pas être annulée dans son état actuel'
                ], 400);
            }

            return back()->with('error', 'Cette réunion ne peut pas être annulée dans son état actuel');
        }

        $validator = Validator::make($request->all(), [
            'motif_annulation' => 'required|string|max:1000',
            'message_participants' => 'nullable|string|max:2000'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            $reunion->update([
                'statut' => 'annulee',
                'motif_annulation' => $request->motif_annulation,
                'message_participants' => $request->message_participants,
                'annulee_par' => auth()->id(),
                'annulee_le' => now()
            ]);

            // TODO: Envoyer notifications aux participants

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion annulée avec succès'
                ]);
            }

            return back()->with('success', 'Réunion annulée avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de l\'annulation de la réunion',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'annulation: ' . $e->getMessage());
        }
    }

    /**
     * Reporter une réunion
     */
    public function reporter(Request $request, Reunion $reunion)
    {
        if (!$reunion->peutEtreReportee()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Cette réunion ne peut pas être reportée dans son état actuel'
                ], 400);
            }

            return back()->with('error', 'Cette réunion ne peut pas être reportée dans son état actuel');
        }

        $validator = Validator::make($request->all(), [
            'nouvelle_date' => 'required|date|after:today',
            'motif_annulation' => 'required|string|max:1000',
            'message_participants' => 'nullable|string|max:2000'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            $reunion->update([
                'statut' => 'reportee',
                'nouvelle_date' => $request->nouvelle_date,
                'motif_annulation' => $request->motif_annulation,
                'message_participants' => $request->message_participants,
                'annulee_par' => auth()->id(),
                'annulee_le' => now()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion reportée avec succès'
                ]);
            }

            return back()->with('success', 'Réunion reportée avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors du report de la réunion',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors du report: ' . $e->getMessage());
        }
    }

    /**
     * Suspendre une réunion
     */
    public function suspendre(Request $request, Reunion $reunion)
    {
        if (!in_array($reunion->statut, ['en_cours'])) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Seules les réunions en cours peuvent être suspendues'
                ], 400);
            }

            return back()->with('error', 'Seules les réunions en cours peuvent être suspendues');
        }

        $validator = Validator::make($request->all(), [
            'motif_suspension' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez indiquer le motif de suspension');
        }

        try {
            $reunion->update([
                'statut' => 'suspendue',
                'motif_annulation' => $request->motif_suspension,
                'modifie_par' => auth()->id()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion suspendue avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return back()->with('success', 'Réunion suspendue avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la suspension',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suspension: ' . $e->getMessage());
        }
    }

    /**
     * Reprendre une réunion suspendue
     */
    public function reprendre(Request $request, Reunion $reunion)
    {
        if ($reunion->statut !== 'suspendue') {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Seules les réunions suspendues peuvent être reprises'
                ], 400);
            }

            return back()->with('error', 'Seules les réunions suspendues peuvent être reprises');
        }

        try {
            $reunion->update([
                'statut' => 'en_cours',
                'modifie_par' => auth()->id()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion reprise avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return back()->with('success', 'Réunion reprise avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la reprise',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la reprise: ' . $e->getMessage());
        }
    }

    /**
     * Marquer les présences
     */
    public function marquerPresences(Request $request, Reunion $reunion)
    {
        $validator = Validator::make($request->all(), [
            'nombre_adultes' => 'required|integer|min:0',
            'nombre_enfants' => 'integer|min:0',
            'nombre_nouveaux' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            $reunion->marquerPresences(
                $request->nombre_adultes,
                $request->get('nombre_enfants', 0),
                $request->get('nombre_nouveaux', 0)
            );

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Présences marquées avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return back()->with('success', 'Présences marquées avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors du marquage des présences',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors du marquage des présences: ' . $e->getMessage());
        }
    }

    /**
     * Inscrire un participant
     */
    public function inscrireParticipant(Request $request, Reunion $reunion)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'type_inscription' => 'required|in:normale,prioritaire,liste_attente',
            'commentaire' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Données d\'inscription invalides');
        }

        try {
            // Vérifier si l'membres n'est pas déjà inscrit
            // TODO: Implémenter la logique d'inscription

            $reunion->increment('nombre_inscrits');

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Participant inscrit avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return back()->with('success', 'Participant inscrit avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de l\'inscription',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'inscription: ' . $e->getMessage());
        }
    }

    /**
     * Désinscrire un participant
     */
    public function desinscrireParticipant(Request $request, Reunion $reunion, string $participant)
    {
        try {
            // TODO: Implémenter la logique de désinscription

            $reunion->decrement('nombre_inscrits');

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Participant désinscrit avec succès'
                ]);
            }

            return back()->with('success', 'Participant désinscrit avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la désinscription',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la désinscription: ' . $e->getMessage());
        }
    }

    /**
     * Ajouter des résultats spirituels
     */
    public function ajouterResultatsSpirituel(Request $request, Reunion $reunion)
    {
        $validator = Validator::make($request->all(), [
            'nombre_decisions' => 'integer|min:0',
            'nombre_recommitments' => 'integer|min:0',
            'nombre_guerisons' => 'integer|min:0'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            $reunion->ajouterResultatsSpirituel(
                $request->get('nombre_decisions', 0),
                $request->get('nombre_recommitments', 0),
                $request->get('nombre_guerisons', 0)
            );

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Résultats spirituels ajoutés avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return back()->with('success', 'Résultats spirituels ajoutés avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de l\'ajout des résultats spirituels',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'ajout: ' . $e->getMessage());
        }
    }

    /**
     * Ajouter des témoignages
     */
    public function ajouterTemoignage(Request $request, Reunion $reunion)
    {
        $validator = Validator::make($request->all(), [
            'temoignages' => 'required`|array',
        ]);
        
        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }
//  return response()->json($reunion);
        try {
           
            $temoignages = $reunion->temoignages_recueillis ? $reunion->temoignages_recueillis . "\n\n---\n\n" : '';

            $nouveauTemoignage = "**{$request->auteur}** ({$request->type})\n{$request->temoignage}";

            $reunion->update([
                'temoignages_recueillis' => $temoignages . $nouveauTemoignage,
                'modifie_par' => auth()->id()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Témoignage ajouté avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return back()->with('success', 'Témoignage ajouté avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
            return response()->json([
                'message' => 'Erreur lors de l\'ajout du témoignage',
                'error' => $e->getMessage()
            ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'ajout: ' . $e->getMessage());
        }
    }

    /**
     * Ajouter des demandes de prière
     */
    public function ajouterDemandesPriere(Request $request, Reunion $reunion)
    {
        $validator = Validator::make($request->all(), [
            'demande' => 'required|string|max:2000',
            'demandeur' => 'nullable|string|max:200',
            'confidentiel' => 'boolean'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            $demandes = $reunion->demandes_priere ?
                $reunion->demandes_priere . "\n\n" : '';

            $nouvelleDemande = ($request->demandeur ? "De: {$request->demandeur}\n" : '') .
                $request->demande .
                ($request->boolean('confidentiel') ? ' [CONFIDENTIEL]' : '');

            $reunion->update([
                'demandes_priere' => $demandes . $nouvelleDemande,
                'modifie_par' => auth()->id()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Demande de prière ajoutée avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return back()->with('success', 'Demande de prière ajoutée avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de l\'ajout de la demande',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'ajout: ' . $e->getMessage());
        }
    }

    /**
     * Évaluer une réunion
     */
    public function evaluer(Request $request, Reunion $reunion)
    {
        if ($reunion->statut !== 'terminee') {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Seules les réunions terminées peuvent être évaluées'
                ], 400);
            }

            return back()->with('error', 'Seules les réunions terminées peuvent être évaluées');
        }

        $validator = Validator::make($request->all(), [
            'note_globale' => 'required|numeric|min:1|max:10',
            'note_contenu' => 'nullable|numeric|min:1|max:10',
            'note_organisation' => 'nullable|numeric|min:1|max:10',
            'note_lieu' => 'nullable|numeric|min:1|max:10',
            'taux_satisfaction' => 'nullable|numeric|min:0|max:100',
            'points_positifs' => 'nullable|string|max:2000',
            'points_amelioration' => 'nullable|string|max:2000',
            'feedback_participants' => 'nullable|string|max:5000'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            $reunion->update($validator->validated());

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Évaluation enregistrée avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return redirect()
                ->route('private.reunions.evaluation', $reunion)
                ->with('success', 'Évaluation enregistrée avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de l\'enregistrement de l\'évaluation',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage());
        }
    }

    /**
     * Afficher l'évaluation d'une réunion
     */
    public function afficherEvaluation(Request $request, Reunion $reunion)
    {
        return response()->json([
            'data' => [
                'reunion' => $reunion,
                'evaluation' => [
                    'note_globale' => $reunion->note_globale,
                    'note_contenu' => $reunion->note_contenu,
                    'note_organisation' => $reunion->note_organisation,
                    'note_lieu' => $reunion->note_lieu,
                    'taux_satisfaction' => $reunion->taux_satisfaction,
                    'points_positifs' => $reunion->points_positifs,
                    'points_amelioration' => $reunion->points_amelioration,
                    'feedback_participants' => $reunion->feedback_participants
                ]
            ]
        ]);


    }

    /**
     * Dupliquer une réunion
     */
    public function dupliquer(Request $request, Reunion $reunion)
    {
        $validator = Validator::make($request->all(), [
            'nouvelle_date' => 'required|date|after:today',
            'copier_participants' => 'boolean',
            'ajustements' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            DB::beginTransaction();

            $nouvelleReunion = $reunion->replicate();
            $nouvelleReunion->titre = $reunion->titre . ' (Copie)';
            $nouvelleReunion->date_reunion = $request->nouvelle_date;
            $nouvelleReunion->statut = 'planifiee';
            $nouvelleReunion->cree_par = auth()->id();
            $nouvelleReunion->modifie_par = null;

            // Réinitialiser les données de l'exécution
            $nouvelleReunion->heure_debut_reelle = null;
            $nouvelleReunion->heure_fin_reelle = null;
            $nouvelleReunion->duree_reelle = null;
            $nouvelleReunion->nombre_participants_reel = null;
            $nouvelleReunion->nombre_adultes = null;
            $nouvelleReunion->nombre_enfants = null;
            $nouvelleReunion->nombre_nouveaux = null;

            if (!$request->boolean('copier_participants')) {
                $nouvelleReunion->nombre_inscrits = 0;
            }

            $nouvelleReunion->save();

            DB::commit();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion dupliquée avec succès',
                    'data' => $nouvelleReunion
                ], 201);
            }

            return redirect()
                ->route('private.reunions.show', $nouvelleReunion)
                ->with('success', 'Réunion dupliquée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la duplication',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la duplication: ' . $e->getMessage());
        }
    }

    /**
     * Créer une récurrence
     */
    public function creerRecurrence(Request $request, Reunion $reunion)
    {
        $validator = Validator::make($request->all(), [
            'frequence' => 'required|in:hebdomadaire,bimensuel,mensuel,trimestriel',
            'nombre_occurrences' => 'required|integer|min:1|max:52',
            'fin_recurrence' => 'nullable|date|after:' . $reunion->date_reunion,
            'copier_participants' => 'boolean'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            DB::beginTransaction();

            $reunionsCreees = [];
            $dateBase = Carbon::parse($reunion->date_reunion);

            $intervalles = [
                'hebdomadaire' => 1,
                'bimensuel' => 2,
                'mensuel' => 4,
                'trimestriel' => 12
            ];

            $intervalleSemaines = $intervalles[$request->frequence];

            for ($i = 1; $i <= $request->nombre_occurrences; $i++) {
                $nouvelleDate = $dateBase->clone()->addWeeks($i * $intervalleSemaines);

                if ($request->fin_recurrence && $nouvelleDate->gt(Carbon::parse($request->fin_recurrence))) {
                    break;
                }

                $nouvelleReunion = $reunion->replicate();
                $nouvelleReunion->date_reunion = $nouvelleDate->toDateString();
                $nouvelleReunion->titre = $reunion->titre . " (#{$i})";
                $nouvelleReunion->statut = 'planifiee';
                $nouvelleReunion->est_recurrente = true;
                $nouvelleReunion->reunion_parent_id = $reunion->id;
                $nouvelleReunion->cree_par = auth()->id();

                if (!$request->boolean('copier_participants')) {
                    $nouvelleReunion->nombre_inscrits = 0;
                }

                $nouvelleReunion->save();
                $reunionsCreees[] = $nouvelleReunion;
            }

            // Marquer la réunion originale comme récurrente
            $reunion->update(['est_recurrente' => true]);

            DB::commit();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => count($reunionsCreees) . ' réunions récurrentes créées avec succès',
                    'data' => $reunionsCreees
                ], 201);
            }

            return back()->with('success', count($reunionsCreees) . ' réunions récurrentes créées avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la création de la récurrence',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer un rappel
     */
    public function envoyerRappel(Request $request, Reunion $reunion)
    {
        $validator = Validator::make($request->all(), [
            'type_rappel' => 'required|in:1_semaine,1_jour,personnalise',
            'message_personnalise' => 'required_if:type_rappel,personnalise|string|max:2000'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            // TODO: Implémenter l'envoi de notifications

            $reunion->marquerRappelEnvoye($request->type_rappel);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Rappel envoyé avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return back()->with('success', 'Rappel envoyé avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de l\'envoi du rappel',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'envoi: ' . $e->getMessage());
        }
    }

    /**
     * Notifier les participants
     */
    public function notifierParticipants(Request $request, Reunion $reunion)
    {
        $validator = Validator::make($request->all(), [
            'type_notification' => 'required|in:changement,annulation,rappel,info',
            'message' => 'required|string|max:2000',
            'urgente' => 'boolean'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Veuillez corriger les erreurs du formulaire');
        }

        try {
            // TODO: Implémenter l'envoi de notifications aux participants

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Notification envoyée aux participants'
                ]);
            }

            return back()->with('success', 'Notification envoyée aux participants');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de l\'envoi de la notification',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'envoi: ' . $e->getMessage());
        }
    }

    /**
     * Upload de photos
     */
    public function uploadPhotos(Request $request, Reunion $reunion)
    {
        $validator = Validator::make($request->all(), [
            'photos' => 'required|array|max:10',
            'photos.*' => 'file|mimes:jpg,jpeg,png,gif|max:5120', // 5MB max
            'descriptions' => 'nullable|array',
            'descriptions.*' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Fichiers invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Fichiers de photos invalides');
        }

        try {
            $photos = [];
            $descriptions = $request->get('descriptions', []);

            foreach ($request->file('photos') as $index => $photo) {
                /** @var UploadedFile $photo */
                $path = $photo->store('reunions/photos', 'public');

                /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                $disk = Storage::disk('public');

                $photos[] = [
                    'path' => $path,
                    'url' => $disk->url($path),
                    'description' => $descriptions[$index] ?? '',
                    'uploaded_at' => now()->toISOString(),
                    'uploaded_by' => auth()->id()
                ];
            }

            $photosExistantes = $reunion->photos_reunion ?? [];
            $toutesPhotos = array_merge($photosExistantes, $photos);

            $reunion->update([
                'photos_reunion' => $toutesPhotos,
                'modifie_par' => auth()->id()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => count($photos) . ' photos uploadées avec succès',
                    'data' => $photos
                ]);
            }

            return back()->with('success', count($photos) . ' photos uploadées avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de l\'upload des photos',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'upload: ' . $e->getMessage());
        }
    }

    /**
     * Upload de documents
     */
    public function uploadDocuments(Request $request, Reunion $reunion)
    {
        $validator = Validator::make($request->all(), [
            'documents' => 'required|array|max:5',
            'documents.*' => 'file|mimes:pdf,doc,docx,txt,ppt,pptx|max:10240', // 10MB max
            'descriptions' => 'nullable|array',
            'descriptions.*' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Fichiers invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Fichiers de documents invalides');
        }

        try {
            $documents = [];
            $descriptions = $request->get('descriptions', []);

            foreach ($request->file('documents') as $index => $document) {
                /** @var UploadedFile $document */
                $path = $document->store('reunions/documents', 'public');
                /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                $disk = Storage::disk('public');

                $documents[] = [
                    'nom' => $document->getClientOriginalName(),
                    'path' => $path,
                    'url' => $disk->url($path),
                    'taille' => $document->getSize(),
                    'type' => $document->getMimeType(),
                    'description' => $descriptions[$index] ?? '',
                    'uploaded_at' => now()->toISOString(),
                    'uploaded_by' => auth()->id()
                ];
            }

            $documentsExistants = $reunion->documents_annexes ?? [];
            $tousDocuments = array_merge($documentsExistants, $documents);

            $reunion->update([
                'documents_annexes' => $tousDocuments,
                'modifie_par' => auth()->id()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => count($documents) . ' documents uploadés avec succès',
                    'data' => $documents
                ]);
            }

            return back()->with('success', count($documents) . ' documents uploadés avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de l\'upload des documents',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'upload: ' . $e->getMessage());
        }
    }

    /**
     * Réunions à venir
     */
    public function aVenir(Request $request)
    {
        try {
            $query = Reunion::aVenir()
                ->with(['typeReunion:id,nom,couleur', 'organisateurPrincipal:id,nom,prenom']);

            if ($request->filled('limite')) {
                $query->limit($request->limite);
            }

            $reunions = $query->get();

            return response()->json(['data' => $reunions]);



        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la récupération des réunions à venir',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération: ' . $e->getMessage());
        }
    }

    /**
     * Réunions du jour
     */
    public function duJour(Request $request)
    {
        try {
            $reunions = Reunion::duJour()
                ->with(['typeReunion:id,nom,couleur', 'organisateurPrincipal:id,nom,prenom'])
                ->get();

            return response()->json(['data' => $reunions]);



        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la récupération des réunions du jour',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération: ' . $e->getMessage());
        }
    }

    /**
     * Calendrier des réunions
     */
    public function calendrier(Request $request)
    {
        try {
            $annee = $request->get('annee', now()->year);
            $mois = $request->get('mois');

            $query = Reunion::with(['typeReunion:id,nom,couleur', 'organisateurPrincipal:id,nom,prenom'])
                ->whereYear('date_reunion', $annee);

            if ($mois) {
                $query->whereMonth('date_reunion', $mois);
            }

            $reunions = $query->orderBy('date_reunion')->orderBy('heure_debut_prevue')->get();
            if ($this->expectsJson($request)) {
                return response()->json([
                    'data' => $reunions,
                    'periode' => ['annee' => $annee, 'mois' => $mois]
                ]);
            }

            return view('components.private.reunions.calendrier', compact('reunions'));


        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la récupération du calendrier',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération: ' . $e->getMessage());
        }
    }

    /**
     * Réunions publiques
     */
    public function reunionsPubliques(Request $request)
    {
        try {
            $reunions = Reunion::whereHas('typeReunion', function ($query) {
                $query->where('afficher_site_web', true)->actif();
            })
                ->with(['typeReunion:id,nom,couleur', 'organisateurPrincipal:id,nom,prenom'])
                ->orderBy('date_reunion')
                ->get();

            return response()->json(['data' => $reunions]);



        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la récupération des réunions publiques',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération: ' . $e->getMessage());
        }
    }

    /**
     * Réunions avec diffusion live
     */
    public function avecDiffusionLive(Request $request)
    {
        try {
            $reunions = Reunion::avecDiffusionEnLigne()
                ->with(['typeReunion:id,nom,couleur', 'organisateurPrincipal:id,nom,prenom'])
                ->orderBy('date_reunion')
                ->get();


            return response()->json(['data' => $reunions]);



        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la récupération des réunions avec diffusion',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération: ' . $e->getMessage());
        }
    }

    /**
     * Statistiques
     */
    public function statistiques(Request $request)
    {
        try {
            $dateDebut = $request->get('date_debut', now()->subMonth()->toDateString());
            $dateFin = $request->get('date_fin', now()->toDateString());

            $stats = [
                'total_reunions' => Reunion::whereBetween('date_reunion', [$dateDebut, $dateFin])->count(),
                'reunions_terminees' => Reunion::whereBetween('date_reunion', [$dateDebut, $dateFin])
                    ->where('statut', 'terminee')->count(),
                'reunions_annulees' => Reunion::whereBetween('date_reunion', [$dateDebut, $dateFin])
                    ->where('statut', 'annulee')->count(),
                'moyenne_participants' => Reunion::whereBetween('date_reunion', [$dateDebut, $dateFin])
                    ->whereNotNull('nombre_participants_reel')
                    ->avg('nombre_participants_reel'),
                'total_participants' => Reunion::whereBetween('date_reunion', [$dateDebut, $dateFin])
                    ->sum('nombre_participants_reel'),
                'total_nouveaux' => Reunion::whereBetween('date_reunion', [$dateDebut, $dateFin])
                    ->sum('nombre_nouveaux'),
                'total_decisions' => Reunion::whereBetween('date_reunion', [$dateDebut, $dateFin])
                    ->sum('nombre_decisions'),
                'satisfaction_moyenne' => Reunion::whereBetween('date_reunion', [$dateDebut, $dateFin])
                    ->whereNotNull('taux_satisfaction')
                    ->avg('taux_satisfaction')
            ];

            // Statistiques par type
            $parType = Reunion::selectRaw('
                tr.nom as nom_type,
                COUNT(*) as nombre_reunions,
                AVG(reunions.nombre_participants_reel) as moyenne_participants,
                SUM(reunions.nombre_decisions) as total_decisions
            ')
                ->join('type_reunions as tr', 'reunions.type_reunion_id', '=', 'tr.id')
                ->whereBetween('reunions.date_reunion', [$dateDebut, $dateFin])
                ->groupBy('tr.id', 'tr.nom')
                ->get();

            $statistiques = [
                'periode' => ['debut' => $dateDebut, 'fin' => $dateFin],
                'globales' => $stats,
                'par_type' => $parType
            ];

            if ($this->expectsJson($request)) {
                return response()->json($statistiques);
            }

            return view('components.private.reunions.statistiques', compact('statistiques'));

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la récupération des statistiques',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération: ' . $e->getMessage());
        }
    }

    /**
     * Options et paramètres
     */
    public function options(Request $request)
    {
        $options = [
            'statuts' => $this->getStatutsArray(),
            'niveaux_priorite' => $this->getNiveauxPrioriteArray(),
            'types_reunions' => TypeReunion::actif()->get(['id', 'nom', 'couleur'])
        ];

        return response()->json($options);


    }

    /**
     * Restaurer une réunion supprimée
     */
    public function restore(Request $request, string $id)
    {
        try {
            $reunion = Reunion::withTrashed()->findOrFail($id);
            $reunion->restore();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Réunion restaurée avec succès',
                    'data' => $reunion->fresh()
                ]);
            }

            return redirect()
                ->route('private.reunions.show', $reunion)
                ->with('success', 'Réunion restaurée avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la restauration',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la restauration: ' . $e->getMessage());
        }
    }

    /**
     * Validation des données de la réunion
     */
    private function validateReunion(Request $request, Reunion $reunion = null): \Illuminate\Validation\Validator
    {
        $rules = [
            'type_reunion_id' => 'required|exists:type_reunions,id',
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'objectifs' => 'nullable|string',
            'date_reunion' => 'required|date',
            'heure_debut_prevue' => 'required|date_format:H:i',
            'heure_fin_prevue' => 'nullable|date_format:H:i|after:heure_debut_prevue',
            'lieu' => 'required|string|max:200',
            'adresse_complete' => 'nullable|string',
            'salle' => 'nullable|string|max:100',
            'capacite_salle' => 'nullable|integer|min:1',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'organisateur_principal_id' => 'required|exists:users,id',
            'animateur_id' => 'nullable|exists:users,id',
            'responsable_technique_id' => 'nullable|exists:users,id',
            'responsable_accueil_id' => 'nullable|exists:users,id',
            'nombre_places_disponibles' => 'nullable|integer|min:1',
            'limite_inscription' => 'nullable|date|after:today',
            'frais_inscription' => 'nullable|numeric|min:0',
            'budget_prevu' => 'nullable|numeric|min:0',
            'statut' => 'required|in:planifiee,confirmee,planifie,en_cours,terminee,annulee,reportee,suspendue',
            'niveau_priorite' => 'required|in:faible,normale,haute,urgente,critique',
            'equipe_organisation' => 'nullable|array',
            'intervenants' => 'nullable|array',
            'ordre_du_jour' => 'nullable|array',
            'documents_annexes' => 'nullable|array',
            'checklist_preparation' => 'nullable|array',

            // Champs booléens
            'liste_attente_activee' => 'boolean',
            'diffusion_en_ligne' => 'boolean',
            'enregistrement_autorise' => 'boolean',
            'preparation_terminee' => 'boolean',
            'est_recurrente' => 'boolean',
        ];

        return Validator::make($request->all(), $rules);
    }

    /**
     * Tableau des statuts disponibles
     */
    private function getStatutsArray(): array
    {
        return [
            'planifiee' => 'Planifiée',
            'confirmee' => 'Confirmée',
            'planifie' => 'En préparation',
            'en_cours' => 'En cours',
            'terminee' => 'Terminée',
            'annulee' => 'Annulée',
            'reportee' => 'Reportée',
            'suspendue' => 'Suspendue'
        ];
    }

    /**
     * Tableau des niveaux de priorité
     */
    private function getNiveauxPrioriteArray(): array
    {
        return [
            'faible' => 'Faible',
            'normale' => 'Normale',
            'haute' => 'Haute',
            'urgente' => 'Urgente',
            'critique' => 'Critique'
        ];
    }

    /**
     * Liste des organisateurs potentiels
     */
    private function getOrganisateursArray(): array
    {
        // TODO: Adapter selon votre modèle User
        return []; // User::where('role', 'organisateur')->get(['id', 'nom', 'prenom']);
    }
}
