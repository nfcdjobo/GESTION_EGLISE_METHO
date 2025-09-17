<?php

namespace App\Http\Controllers\Private\Web;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Culte;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\ParticipantCulte;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ParticipantCulteController extends Controller
{

    public function __construct()
{
    $this->middleware('auth');
    $this->middleware('permission:participant_cultes.read')->only(['index', 'show', 'searchParticipants', 'statistiques', 'nouveauxVisiteurs', 'participantsCulte']);
    $this->middleware('permission:participant_cultes.create')->only(['store', 'storeWithUserCreation', 'storeBulkWithUserCreation', 'ajouterParticipant']);
    $this->middleware('permission:participant_cultes.update')->only(['update', 'confirmerPresence']);
    $this->middleware('permission:participant_cultes.delete')->only(['destroy']);
}

    /**
     * Afficher la liste des participations
     */
    public function index(Request $request)
    {
        try {
            $query = ParticipantCulte::with([
                'participant:id,prenom,nom,email,telephone_1',
                'culte:id,titre,date_culte,type_culte,statut',
                'confirmateur:id,prenom,nom',
                'accompagnateur:id,prenom,nom'
            ]);

            // Filtres
            if ($request->get('culte_id')) {
                $query->where('culte_id', $request->culte_id);
            }

            if ($request->get('participant_id')) {

                $query->where('participant_id', $request->participant_id);
            }
            if ($request->get('statut_presence')) {

                $query->where('statut_presence', $request->statut_presence);
            }

            if ($request->get('type_participation')) {

                $query->where('type_participation', $request->type_participation);
            }

            if ($request->get('role_culte')) {

                $query->where('role_culte', $request->role_culte);
            }

            if ($request->get('premiere_visite')) {
                $query->where('premiere_visite', $request->boolean('premiere_visite'));
            }

            if ($request->get('necessite_suivi')) {
                $query->necessitantSuivi();
            }

            if ($request->get('confirmation')) {
                $query->where('presence_confirmee', $request->boolean('confirmation'));
            }

            // Filtres de date
            if ($request->get('date_debut') && $request->has('date_fin')) {
                $query->parPeriode($request->date_debut, $request->date_fin);
            }

            if ($request->get('jours_recents')) {
                $query->recentes($request->integer('jours_recents', 30));
            }

            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            if ($sortBy === 'date_culte') {
                $query->join('cultes', 'participant_cultes.culte_id', '=', 'cultes.id')
                    ->orderBy('cultes.date_culte', $sortOrder)
                    ->select('participant_cultes.*');
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = $request->integer('per_page', 15);
            $participations = $query->paginate($perPage);


            $cultes = Culte::orderByDesc('date_culte')->get();

            if ($request->expectsJson() || $request->ajax()) {

                return response()->json([
                    'success' => true,
                    'data' => $participations,
                    'message' => 'Liste des participations récupérée avec succès'
                ]);
            }

            return view('components.private.particitantscultes.index', compact('participations', 'cultes'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des participations',
                'error' => $e->getMessage()
            ], 500);

            return redirect()->back()->with('error', 'Erreur lors de la récupération des participations');
        }
    }







    /**
     * Rechercher des participants par nom, prénom, email ou téléphone
     * Exclut les membres déjà enregistrés pour ce culte
     */
    public function searchParticipants(Request $request, string $culte): JsonResponse
    {
        try {
            $query = $request->get('q', '');

            if (strlen($query) < 2) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Requête trop courte'
                ]);
            }

            $users = User::actifs()
                ->where(function ($q) use ($query) {
                    $q->where('prenom', 'ILIKE', "%{$query}%")
                        ->orWhere('nom', 'ILIKE', "%{$query}%")
                        ->orWhere('email', 'ILIKE', "%{$query}%")
                        ->orWhere('telephone_1', 'ILIKE', "%{$query}%")
                        ->orWhere('telephone_2', 'ILIKE', "%{$query}%")
                        ->orWhereRaw("CONCAT(prenom, ' ', nom) ILIKE ?", ["%{$query}%"])
                        ->orWhereRaw("CONCAT(nom, ' ', prenom) ILIKE ?", ["%{$query}%"]);
                })
                // Exclure les membres déjà dans participant_cultes pour ce culte
                ->whereNotIn('id', function ($subQuery) use ($culte) {
                    $subQuery->select('participant_id')
                        ->from('participant_cultes')
                        ->where('culte_id', $culte)
                        ->whereNull('deleted_at'); // Respecter le soft delete
                })
                ->select('id', 'prenom', 'nom', 'email', 'telephone_1', 'telephone_2')
                ->orderBy('nom')
                ->orderBy('prenom')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => count($users) . ' résultat(s) trouvé(s)'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la recherche',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Enregistrer une nouvelle participation
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = $this->validateParticipation($request);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Vérifier que la participation n'existe pas déjà
            $existingParticipation = ParticipantCulte::where('participant_id', $request->participant_id)
                ->where('culte_id', $request->culte_id)
                ->first();

            if ($existingParticipation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette personne est déjà enregistrée pour ce culte'
                ], 422);
            }

            DB::beginTransaction();

            $participation = ParticipantCulte::create($request->validated());

            // Mettre à jour le nombre de participants du culte
            $this->updateNombreParticipants($request->culte_id);

            DB::commit();

            $participation->load([
                'participant:id,prenom,nom,email',
                'culte:id,titre,date_culte',
                'confirmateur:id,prenom,nom',
                'accompagnateur:id,prenom,nom'
            ]);

            return response()->json([
                'success' => true,
                'data' => $participation,
                'message' => 'Participation enregistrée avec succès'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement de la participation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enregistrer une participation avec création automatique de l'membres si nécessaire
     */
    public function storeWithUserCreation(Request $request): JsonResponse
    {

        try {
            $validator = $this->validateParticipationWithUser($request);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $participantId = null;
            $userCreated = false;

            // Si participant_id est fourni, vérifier qu'il existe
            if ($request->has('participant_id')) {

                $participant = User::find($request->participant_id);

                if (!$participant) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Participant introuvable'
                    ], 404);
                }
                $participantId = $request->participant_id;
            } else {
                // Créer un nouvel membres
                $participant = $this->createUserFromRequest($request);
                $participantId = $participant->id;
                $userCreated = true;
            }

            // Vérifier que la participation n'existe pas déjà
            $existingParticipation = ParticipantCulte::where('participant_id', $participantId)
                ->where('culte_id', $request->culte_id)
                ->first();

            if ($existingParticipation) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Cette personne est déjà enregistrée pour ce culte'
                ], 422);
            }



            $participationData = [
                "culte_id" => $request->culte_id,
                "participant_id" => $participantId,
                "statut_presence" => $request->statut_presence,
                "type_participation" => $request->type_participation,
                "heure_arrivee" => $request->heure_arrivee,
                "heure_depart" => $request->heure_depart,
                "role_culte" => $request->role_culte
            ];



            $participation = ParticipantCulte::create($participationData);

            // Mettre à jour le nombre de participants du culte
            $this->updateNombreParticipants($request->culte_id);

            DB::commit();

            $participation->load([
                'participant:id,prenom,nom,email,telephone_1',
                'culte:id,titre,date_culte',
                'confirmateur:id,prenom,nom',
                'accompagnateur:id,prenom,nom'
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'participation' => $participation,
                    'user_created' => $userCreated
                ],
                'message' => $userCreated ?
                    'Membres créé et participation enregistrée avec succès' :
                    'Participation enregistrée avec succès'
            ], 201);




        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    /**
     * Afficher une participation spécifique
     */
    public function show(Request $request, string $participantId, string $culteId)
    {
        try {
            $participation = ParticipantCulte::with([
                'participant:id,prenom,nom,email,telephone_1,statut_membre,created_at',
                'culte:id,titre,date_culte,type_culte,statut,lieu',
                'confirmateur:id,prenom,nom',
                'enregistreur:id,prenom,nom',
                'accompagnateur:id,prenom,nom'
            ])
                ->where('participant_id', $participantId)
                ->where('culte_id', $culteId)
                ->firstOrFail();

                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'data' => $participation,
                        'message' => 'Participationrécupérée avec succès'
                    ]);
                }

            return view('components.private.particitantscultes.show', compact('participation'));


        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Participation non trouvée'
                ], 404);
            }
            return redirect()->back()->with('error', 'Participation non trouvée');
        } catch (\Exception $e) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération de la participation',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Erreur lors de la récupération de la participation');
        }
    }

    /**
     * Mettre à jour une participation
     */
    public function update(Request $request, string $participantId, string $culteId): JsonResponse
    {
        try {
            $participation = ParticipantCulte::where('participant_id', $participantId)
                ->where('culte_id', $culteId)
                ->firstOrFail();

            $validator = $this->validateParticipation($request, true);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $participation->update($request->validated());

            // Mettre à jour le nombre de participants du culte si nécessaire
            $this->updateNombreParticipants($culteId);

            DB::commit();

            $participation->load([
                'participant:id,prenom,nom,email',
                'culte:id,titre,date_culte',
                'confirmateur:id,prenom,nom',
                'accompagnateur:id,prenom,nom'
            ]);

            return response()->json([
                'success' => true,
                'data' => $participation,
                'message' => 'Participation mise à jour avec succès'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Participation non trouvée'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la participation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer une participation
     */
    public function destroy(string $participantId, string $culteId): JsonResponse
    {
        try {
            $participation = ParticipantCulte::where('participant_id', $participantId)
                ->where('culte_id', $culteId)
                ->firstOrFail();

            DB::beginTransaction();

            $participation->delete();

            // Mettre à jour le nombre de participants du culte
            $this->updateNombreParticipants($culteId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Participation supprimée avec succès'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Participation non trouvée'
            ], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la participation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Confirmer une participation
     */
    public function confirmerPresence(Request $request, string $participantId, string $culteId): JsonResponse
    {
        try {
            $participation = ParticipantCulte::where('participant_id', $participantId)
                ->where('culte_id', $culteId)
                ->firstOrFail();

            $confirmateurId = $request->get('confirme_par', auth()->id());

            $participation->confirmerPresence($confirmateurId);

            return response()->json([
                'success' => true,
                'data' => $participation->fresh(['confirmateur:id,prenom,nom']),
                'message' => 'Présence confirmée avec succès'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Participation non trouvée'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la confirmation de la présence',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enregistrer en masse les participations d'un culte avec création d'membres si nécessaire
     */
    public function storeBulkWithUserCreation(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'culte_id' => 'required|uuid|exists:cultes,id',
                'participants' => 'required|array|min:1',
                'participants.*.participant_id' => 'nullable|uuid|exists:users,id',
                // Champs obligatoires pour créer un membres si participant_id n'est pas fourni
                'participants.*.prenom' => 'required_without:participants.*.participant_id|string|max:100',
                'participants.*.nom' => 'required_without:participants.*.participant_id|string|max:100',
                'participants.*.sexe' => 'required_without:participants.*.participant_id|in:masculin,feminin',
                'participants.*.telephone_1' => 'required_without:participants.*.participant_id|string|max:20',
                // Champs de participation
                'participants.*.statut_presence' => 'nullable|in:present,present_partiel,en_retard,parti_tot',
                'participants.*.type_participation' => 'nullable|in:physique,en_ligne,hybride',
                'participants.*.role_culte' => 'nullable|in:participant,equipe_technique,equipe_louange,equipe_accueil,orateur,dirigeant,diacre_service,collecteur_offrande,invite_special,nouveau_visiteur',
                'participants.*.heure_arrivee' => 'nullable|date_format:H:i',
                'participants.*.heure_depart' => 'nullable|date_format:H:i|after:participants.*.heure_arrivee',
                'participants.*.premiere_visite' => 'nullable|boolean',
                'participants.*.accompagne_par' => 'nullable|uuid|exists:users,id',
                'participants.*.demande_contact_pastoral' => 'nullable|boolean',
                'participants.*.interesse_bapteme' => 'nullable|boolean',
                'participants.*.souhaite_devenir_membre' => 'nullable|boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $culteId = $request->culte_id;
            $participations = [];
            $usersCreated = [];
            $errors = [];

            foreach ($request->participants as $index => $participantData) {
                try {
                    $participantId = null;

                    // Si participant_id est fourni, l'utiliser
                    if (!empty($participantData['participant_id'])) {
                        $participantId = $participantData['participant_id'];
                    } else {
                        // Créer un nouvel membres avec seulement les champs obligatoires
                        $userData = [
                            'prenom' => $participantData['prenom'],
                            'nom' => $participantData['nom'],
                            'sexe' => $participantData['sexe'],
                            'telephone_1' => $participantData['telephone_1'],
                            'password' => bcrypt(env('DEFAULT_PASSWORD', 'password')) // Mot de passe par défaut
                        ];

                        $user = User::create($userData);
                        $participantId = $user->id;
                        $usersCreated[] = $user;
                    }

                    // Vérifier si la participation existe déjà
                    $existing = ParticipantCulte::where('participant_id', $participantId)
                        ->where('culte_id', $culteId)
                        ->first();

                    if ($existing) {
                        $errors[] = "Participant {$existing->participant->nom} {$existing->participant->prenom} déjà enregistré";
                        continue;
                    }

                    // Créer la participation
                    $participationData = array_intersect_key($participantData, array_flip([
                        'statut_presence',
                        'type_participation',
                        'heure_arrivee',
                        'heure_depart',
                        'role_culte',
                        'premiere_visite',
                        'accompagne_par',
                        'demande_contact_pastoral',
                        'interesse_bapteme',
                        'souhaite_devenir_membre'
                    ]));

                    $participationData['participant_id'] = $participantId;
                    $participationData['culte_id'] = $culteId;

                    $participation = ParticipantCulte::create($participationData);
                    $participations[] = $participation;

                } catch (\Exception $e) {
                    $errors[] = "Erreur pour le participant {$index}: " . $e->getMessage();
                }
            }

            // Mettre à jour le nombre de participants du culte
            $this->updateNombreParticipants($culteId);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => [
                    'participations_creees' => count($participations),
                    'membres_crees' => count($usersCreated),
                    'erreurs' => $errors
                ],
                'message' => count($participations) . ' participations et ' . count($usersCreated) . ' membres créés avec succès'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'enregistrement en masse',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistiques de participation
     */
    public function statistiques(Request $request)
    {
        try {
            $query = ParticipantCulte::query();

            // Filtres de période
            if ($request->has('date_debut') && $request->has('date_fin')) {
                $query->parPeriode($request->date_debut, $request->date_fin);
            }

            if ($request->has('culte_id')) {
                $query->where('culte_id', $request->culte_id);
            }

            $stats = [
                'total_participations' => $query->count(),
                'par_statut' => $query->select('statut_presence', DB::raw('count(*) as total'))
                    ->groupBy('statut_presence')
                    ->pluck('total', 'statut_presence'),
                'par_type' => $query->select('type_participation', DB::raw('count(*) as total'))
                    ->groupBy('type_participation')
                    ->pluck('total', 'type_participation'),
                'premieres_visites' => $query->where('premiere_visite', true)->count(),
                'demandes_suivi' => $query->necessitantSuivi()->count(),
                'participations_confirmees' => $query->where('presence_confirmee', true)->count()
            ];

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $stats,
                    'message' => 'Statistiques récupérées avec succès'
                ]);
            }

            return view('components.private.particitantscultes.statistiques', compact('stats'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du calcul des statistiques',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Liste des nouveaux visiteurs nécessitant un suivi
     */
    public function nouveauxVisiteurs(Request $request)
    {
        try {
            $jours = $request->integer('jours', 30);

            $visiteurs = ParticipantCulte::with([
                'participant:id,prenom,nom,email,telephone_1',
                'culte:id,titre,date_culte',
                'accompagnateur:id,prenom,nom'
            ])
                ->where('premiere_visite', true)
                ->recentes($jours)
                ->orderBy('created_at', 'desc')
                ->get();


            return view('components.private.particitantscultes.nouveaux-visiteurs', compact('visiteurs'));


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des nouveaux visiteurs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validation des données de participation
     */
    private function validateParticipation(Request $request, bool $isUpdate = false): \Illuminate\Validation\Validator
    {
        $rules = [
            'participant_id' => 'required|uuid|exists:users,id',
            'culte_id' => 'required|uuid|exists:cultes,id',
            'statut_presence' => 'nullable|in:present,present_partiel,en_retard,parti_tot',
            'type_participation' => 'nullable|in:physique,en_ligne,hybride',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i|after:heure_arrivee',
            'role_culte' => 'nullable|in:participant,equipe_technique,equipe_louange,equipe_accueil,orateur,dirigeant,diacre_service,collecteur_offrande,invite_special,nouveau_visiteur',
            'presence_confirmee' => 'nullable|boolean',
            'confirme_par' => 'nullable|uuid|exists:users,id',
            'premiere_visite' => 'nullable|boolean',
            'accompagne_par' => 'nullable|uuid|exists:users,id|different:participant_id',
            'demande_contact_pastoral' => 'nullable|boolean',
            'interesse_bapteme' => 'nullable|boolean',
            'souhaite_devenir_membre' => 'nullable|boolean',
            'notes_responsable' => 'nullable|string|max:2000',
            'commentaires_participant' => 'nullable|string|max:2000'
        ];

        if ($isUpdate) {
            $rules['participant_id'] = 'sometimes|' . $rules['participant_id'];
            $rules['culte_id'] = 'sometimes|' . $rules['culte_id'];
        }

        return Validator::make($request->all(), $rules);
    }



    private function validateParticipationWithUser(Request $request): \Illuminate\Validation\Validator
    {
        $rules = [
            'participant_id' => 'nullable|uuid|exists:users,id',
            'culte_id' => 'required|uuid|exists:cultes,id',

            // Champs de participation
            'statut_presence' => 'nullable|in:present,present_partiel,en_retard,parti_tot',
            'type_participation' => 'nullable|in:physique,en_ligne,hybride',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i|after:heure_arrivee',
            'role_culte' => 'nullable|in:participant,equipe_technique,equipe_louange,equipe_accueil,orateur,dirigeant,diacre_service,collecteur_offrande,invite_special,nouveau_visiteur',
            'presence_confirmee' => 'nullable|boolean',
            'confirme_par' => 'nullable|uuid|exists:users,id',
            'premiere_visite' => 'nullable|boolean',
            'accompagne_par' => 'nullable|uuid|exists:users,id',
            'demande_contact_pastoral' => 'nullable|boolean',
            'interesse_bapteme' => 'nullable|boolean',
            'souhaite_devenir_membre' => 'nullable|boolean',
            'notes_responsable' => 'nullable|string|max:2000',
            'commentaires_participant' => 'nullable|string|max:2000'
        ];

        // Ajouter les règles pour les champs membres seulement si participant_id n'est pas fourni
        if (empty($request->participant_id)) {
            $rules = array_merge($rules, [
                'prenom' => 'required|string|max:100',
                'nom' => 'required|string|max:100',
                'sexe' => 'required|in:masculin,feminin',
                'telephone_1' => 'required|string|max:20',
                'email' => 'nullable|email|max:255'
            ]);
        }

        return Validator::make($request->all(), $rules);
    }

    /**
     * Créer un membres à partir des données de la requête
     */
    private function createUserFromRequest(Request $request): User
    {
        $userData = [
            'prenom' => $request->prenom,
            'nom' => $request->nom,
            'sexe' => $request->sexe,
            'telephone_1' => $request->telephone_1,
            'password' => Hash::make('Metho-bv-2025')
        ];

        if($request->email){
            $userData['email'] = $request->email;
        }

        return User::create($userData);
    }

    /**
     * Mettre à jour le nombre de participants du culte
     */
    private function updateNombreParticipants(string $culteId): void
    {
        $count = ParticipantCulte::where('culte_id', $culteId)->count();

        Culte::where('id', $culteId)->update([
            'nombre_participants' => $count
        ]);
    }

    /**
     * Afficher les participants d'un culte spécifique et permettre d'en ajouter
     */
    public function participantsCulte(Request $request, string $culteId)
    {

        try {
            // Récupérer le culte avec ses relations
            $culte = Culte::with([
                'pasteurPrincipal',
                'predicateur',
                'responsableCulte',
                'dirigeantLouange',
                'programme'
            ])->findOrFail($culteId);

            // Récupérer les participants existants avec pagination
            $participantsQuery = ParticipantCulte::with([
                'participant',
                'confirmateur',
                'enregistreur',
                'accompagnateur'
            ])
                ->where('culte_id', $culteId)
                ->orderBy('created_at', 'desc');

            // Filtres optionnels
            if ($request->filled('statut_presence')) {
                $participantsQuery->where('statut_presence', $request->statut_presence);
            }

            if ($request->filled('type_participation')) {
                $participantsQuery->where('type_participation', $request->type_participation);
            }

            if ($request->filled('role_culte')) {
                $participantsQuery->where('role_culte', $request->role_culte);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $participantsQuery->whereHas('participant', function ($query) use ($search) {
                    $query->where('prenom', 'ILIKE', "%{$search}%")
                        ->orWhere('nom', 'ILIKE', "%{$search}%")
                        ->orWhere('email', 'ILIKE', "%{$search}%");
                });
            }

            $participants = $participantsQuery->paginate(20);

            // Récupérer tous les membres actifs pour le formulaire d'ajout
            $membresDisponibles = User::actifs()
                ->whereNotIn('id', function ($query) use ($culteId) {
                    $query->select('participant_id')
                        ->from('participant_cultes')
                        ->where('culte_id', $culteId)
                        ->whereNull('deleted_at');
                })
                ->orderBy('nom')
                ->orderBy('prenom')
                ->get();

            // Statistiques rapides
            $statistiques = [
                'total' => ParticipantCulte::where('culte_id', $culteId)->count(),
                'presents' => ParticipantCulte::where('culte_id', $culteId)->where('statut_presence', 'present')->count(),
                'en_ligne' => ParticipantCulte::where('culte_id', $culteId)->where('type_participation', 'en_ligne')->count(),
                'premieres_visites' => ParticipantCulte::where('culte_id', $culteId)->where('premiere_visite', true)->count(),
                'necessitant_suivi' => ParticipantCulte::where('culte_id', $culteId)
                    ->where(function ($q) {
                        $q->where('demande_contact_pastoral', true)
                            ->orWhere('interesse_bapteme', true)
                            ->orWhere('souhaite_devenir_membre', true);
                    })->count()
            ];

            return view('components.private.cultes.participants', compact(
                'culte',
                'participants',
                'membresDisponibles',
                'statistiques'
            ));

        } catch (\Exception $e) {
            // dd($e->getMessage());
            return back()->with('error', 'Erreur lors du chargement des participants : ' . $e->getMessage());
        }
    }

    /**
     * Ajouter un participant existant à un culte
     */
    public function ajouterParticipant(Request $request, string $culteId)
    {
        $request->validate([
            'participant_id' => 'required|uuid|exists:users,id',
            'statut_presence' => 'required|in:present,present_partiel,en_retard,parti_tot',
            'type_participation' => 'required|in:physique,en_ligne,hybride',
            'role_culte' => 'required|in:participant,equipe_technique,equipe_louange,equipe_accueil,orateur,dirigeant,diacre_service,collecteur_offrande,invite_special,nouveau_visiteur',
            'heure_arrivee' => 'nullable|date_format:H:i',
            'heure_depart' => 'nullable|date_format:H:i|after:heure_arrivee',
            'premiere_visite' => 'boolean',
            'accompagne_par' => 'nullable|uuid|exists:users,id',
            'demande_contact_pastoral' => 'boolean',
            'interesse_bapteme' => 'boolean',
            'souhaite_devenir_membre' => 'boolean',
            'notes_responsable' => 'nullable|string|max:1000',
            'commentaires_participant' => 'nullable|string|max:1000'
        ]);

        try {
            // Vérifier que le culte existe
            $culte = Culte::findOrFail($culteId);

            // Vérifier que le participant n'est pas déjà inscrit
            $participationExistante = ParticipantCulte::where('participant_id', $request->participant_id)
                ->where('culte_id', $culteId)
                ->first();

            if ($participationExistante) {
                return back()->with('error', 'Ce participant est déjà inscrit à ce culte.');
            }

            // Créer la participation
            ParticipantCulte::create([
                'participant_id' => $request->participant_id,
                'culte_id' => $culteId,
                'statut_presence' => $request->statut_presence,
                'type_participation' => $request->type_participation,
                'role_culte' => $request->role_culte,
                'heure_arrivee' => $request->heure_arrivee,
                'heure_depart' => $request->heure_depart,
                'premiere_visite' => $request->boolean('premiere_visite'),
                'accompagne_par' => $request->accompagne_par,
                'demande_contact_pastoral' => $request->boolean('demande_contact_pastoral'),
                'interesse_bapteme' => $request->boolean('interesse_bapteme'),
                'souhaite_devenir_membre' => $request->boolean('souhaite_devenir_membre'),
                'notes_responsable' => $request->notes_responsable,
                'commentaires_participant' => $request->commentaires_participant,
                'presence_confirmee' => true,
                'confirme_par' => auth()->id(),
                'confirme_le' => now(),
                'enregistre_par' => auth()->id(),
                'enregistre_le' => now()
            ]);

            return back()->with('success', 'Participant ajouté avec succès au culte.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'ajout du participant : ' . $e->getMessage());
        }
    }
}
