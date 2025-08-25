<?php

namespace App\Http\Controllers\Private\Web;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Culte;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\InscriptionEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InscriptionEventController extends Controller
{
    /**
     * Constructeur avec middleware d'authentification
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:events.read')->only(['inscriptionsEvent', 'listeAttente']);
        $this->middleware('permission:events.manage_inscriptions')->only(['ajouterInscription', 'modifierInscription', 'supprimerInscription', 'promouvoirInscription']);
    }

    /**
     * Afficher les inscriptions d'un événement
     */
    public function inscriptionsEvent(Event $event, Request $request)
    {

        try {
            $query = InscriptionEvent::query()
                ->with(['inscrit', 'createur', 'modificateur', 'annulateur'])
                ->where('event_id', $event->id);

            // Filtres
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->whereHas('inscrit', function($q) use ($search) {
                    $q->where('prenom', 'ILIKE', "%{$search}%")
                      ->orWhere('nom', 'ILIKE', "%{$search}%")
                      ->orWhere('email', 'ILIKE', "%{$search}%")
                      ->orWhere('telephone_1', 'ILIKE', "%{$search}%");
                });
            }



            if ($request->filled('statut')) {
                $statut = $request->get('statut');
                switch ($statut) {
                    case 'active':
                        $query->whereNull('annule_le');
                        break;
                    case 'annulee':
                        $query->whereNotNull('annule_le');
                        break;
                }
            }

            if ($request->filled('date_inscription')) {
                $query->whereDate('created_at', $request->get('date_inscription'));
            }

            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            $allowedSorts = ['created_at', 'annule_le'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder);
            }

            // Pagination
            $perPage = min($request->get('per_page', 15), 100);
            $inscriptions = $query->paginate($perPage);

            // Statistiques rapides
            $statistiques = [
                'total_inscriptions' => InscriptionEvent::where('event_id', $event->id)->count(),
                'inscriptions_actives' => InscriptionEvent::where('event_id', $event->id)
                    ->whereNull('annule_le')->count(),
                'inscriptions_annulees' => InscriptionEvent::where('event_id', $event->id)
                    ->whereNotNull('annule_le')->count(),
                'places_restantes' => $event->capacite_totale
                    ? max(0, $event->capacite_totale - $event->nombre_inscrits)
                    : null,
                'taux_remplissage' => $event->pourcentage_remplissage
            ];
//  dd(44);
            $cultes = Culte::orderByDesc('date_culte')->get();

            $users = User::orderByRaw('LOWER(nom) ASC')->get();
//  dd(44);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $inscriptions,
                    'statistiques' => $statistiques,
                    'event' => $event,
                    'meta' => [
                        'total' => $inscriptions->total(),
                        'per_page' => $inscriptions->perPage(),
                        'current_page' => $inscriptions->currentPage(),
                        'last_page' => $inscriptions->lastPage()
                    ]
                ]);
            }

            return view('components.private.events.inscriptions.index', compact('event', 'inscriptions', 'statistiques', 'cultes', 'users'));

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des inscriptions',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du chargement des inscriptions: ' . $e->getMessage());
        }
    }

    /**
     * Ajouter une inscription à un événement
     */
    public function ajouterInscription(Event $event, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inscrit_id' => ['required', 'uuid', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $inscritId = $request->get('inscrit_id');

            // Vérifier si l'inscription existe déjà
            $inscriptionExistante = InscriptionEvent::where('event_id', $event->id)
                ->where('inscrit_id', $inscritId)
                ->first();

            if ($inscriptionExistante) {
                $message = 'Cette personne est déjà inscrite à cet événement';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            // Vérifier la capacité
            if ($event->capacite_totale && $event->nombre_inscrits >= $event->capacite_totale) {
                if (!$event->liste_attente) {
                    $message = 'L\'événement est complet et n\'accepte pas de liste d\'attente';

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message
                        ], Response::HTTP_UNPROCESSABLE_ENTITY);
                    }

                    return redirect()->back()->with('error', $message);
                }
            }

            // Vérifier si les inscriptions sont ouvertes
            if (!$event->accepteInscriptions()) {
                $message = 'Les inscriptions ne sont plus ouvertes pour cet événement';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            // Créer l'inscription
            $inscription = InscriptionEvent::create([
                'inscrit_id' => $inscritId,
                'event_id' => $event->id,
                'cree_par' => auth()->id(),
                'cree_le' => now(),
            ]);

            // Mettre à jour le compteur d'inscrits
            $event->increment('nombre_inscrits');

            DB::commit();

            $inscription->load(['inscrit', 'createur']);

            $message = 'Inscription ajoutée avec succès';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $inscription
                ], Response::HTTP_CREATED);
            }

            return redirect()->route('private.events.inscriptions', $event)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout de l\'inscription',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de l\'ajout de l\'inscription: ' . $e->getMessage());
        }
    }

    /**
     * Modifier une inscription
     */
    public function modifierInscription(Event $event, InscriptionEvent $inscription, Request $request)
    {
        try {
            // Vérifier que l'inscription appartient bien à cet événement
            if ($inscription->event_id !== $event->id) {
                $message = 'Cette inscription ne correspond pas à cet événement';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            $inscription->update([
                'modifie_par' => auth()->id(),
            ]);

            $inscription->load(['inscrit', 'modificateur']);

            $message = 'Inscription modifiée avec succès';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $inscription
                ]);
            }

            return redirect()->route('private.events.inscriptions', $event)
                ->with('success', $message);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la modification de l\'inscription',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la modification: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une inscription
     */
    public function supprimerInscription(Event $event, InscriptionEvent $inscription)
    {
        try {
            // Vérifier que l'inscription appartient bien à cet événement
            if ($inscription->event_id !== $event->id) {
                $message = 'Cette inscription ne correspond pas à cet événement';

                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            DB::beginTransaction();

            // Marquer comme supprimée
            $inscription->update([
                'supprimer_par' => auth()->id(),
            ]);

            $inscription->delete();

            // Décrémenter le compteur d'inscrits
            $event->decrement('nombre_inscrits');

            DB::commit();

            $message = 'Inscription supprimée avec succès';

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }

            return redirect()->route('private.events.inscriptions', $event)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de l\'inscription',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Afficher la liste d'attente d'un événement
     */
    public function listeAttente(Event $event, Request $request)
    {
        try {
            if (!$event->liste_attente) {
                $message = 'Cet événement n\'a pas de liste d\'attente activée';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            $inscriptionsListeAttente = InscriptionEvent::query()
                ->with(['inscrit', 'createur'])
                ->where('event_id', $event->id)
                ->whereNull('annule_le')
                ->skip($event->capacite_totale ?? 0)
                ->orderBy('created_at')
                ->get();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $inscriptionsListeAttente,
                    'event' => $event
                ]);
            }

            return view('components.private.events.inscriptions.liste-attente', compact('event', 'inscriptionsListeAttente'));

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération de la liste d\'attente',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du chargement de la liste d\'attente: ' . $e->getMessage());
        }
    }

    /**
     * Promouvoir une inscription de la liste d'attente
     */
    public function promouvoirInscription(Event $event, InscriptionEvent $inscription, Request $request)
    {
        try {
            // Vérifier que l'inscription appartient bien à cet événement
            if ($inscription->event_id !== $event->id) {
                $message = 'Cette inscription ne correspond pas à cet événement';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            // Vérifier qu'il y a de la place
            if ($event->capacite_totale && $event->nombre_inscrits >= $event->capacite_totale) {
                $message = 'Il n\'y a plus de place disponible dans l\'événement';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            // La promotion est automatique par ordre d'arrivée
            // Pas d'action spécifique nécessaire, juste mettre à jour qui a fait la promotion
            $inscription->update([
                'modifie_par' => auth()->id(),
            ]);

            $inscription->load(['inscrit', 'modificateur']);

            $message = 'Inscription promue avec succès de la liste d\'attente';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $inscription
                ]);
            }

            return redirect()->route('private.events.liste-attente', $event)
                ->with('success', $message);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la promotion de l\'inscription',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la promotion: ' . $e->getMessage());
        }
    }

    /**
     * Annuler une inscription
     */
    public function annulerInscription(Event $event, InscriptionEvent $inscription, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'raison' => ['nullable', 'string', 'max:500']
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Vérifier que l'inscription appartient bien à cet événement
            if ($inscription->event_id !== $event->id) {
                $message = 'Cette inscription ne correspond pas à cet événement';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            // Vérifier que l'inscription n'est pas déjà annulée
            if ($inscription->annule_le) {
                $message = 'Cette inscription est déjà annulée';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            DB::beginTransaction();

            // Annuler l'inscription
            $inscription->update([
                'annule_par' => auth()->id(),
                'annule_le' => now(),
                'modifie_par' => auth()->id(),
            ]);

            // Décrémenter le compteur d'inscrits
            $event->decrement('nombre_inscrits');

            DB::commit();

            $inscription->load(['inscrit', 'annulepar']);

            $message = 'Inscription annulée avec succès';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $inscription
                ]);
            }

            return redirect()->route('private.events.inscriptions', $event)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'annulation de l\'inscription',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de l\'annulation: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les statistiques des inscriptions d'un événement
     */
    public function statistiquesInscriptions(Event $event, Request $request)
    {
        try {
            $statistiques = [
                'totaux' => [
                    'total_inscriptions' => InscriptionEvent::where('event_id', $event->id)->count(),
                    'inscriptions_actives' => InscriptionEvent::where('event_id', $event->id)
                        ->whereNull('annule_le')->count(),
                    'inscriptions_annulees' => InscriptionEvent::where('event_id', $event->id)
                        ->whereNotNull('annule_le')->count(),
                    'inscriptions_supprimees' => InscriptionEvent::where('event_id', $event->id)
                        ->onlyTrashed()->count()
                ],
                'capacite' => [
                    'capacite_totale' => $event->capacite_totale,
                    'places_occupees' => $event->nombre_inscrits,
                    'places_restantes' => $event->capacite_totale
                        ? max(0, $event->capacite_totale - $event->nombre_inscrits)
                        : null,
                    'taux_remplissage' => $event->pourcentage_remplissage,
                    'liste_attente_activee' => $event->liste_attente
                ],
                'temporel' => [
                    'inscriptions_derniere_semaine' => InscriptionEvent::where('event_id', $event->id)
                        ->where('created_at', '>=', now()->subWeek())
                        ->count(),
                    'inscriptions_derniere_24h' => InscriptionEvent::where('event_id', $event->id)
                        ->where('created_at', '>=', now()->subDay())
                        ->count(),
                    'annulations_derniere_semaine' => InscriptionEvent::where('event_id', $event->id)
                        ->where('annule_le', '>=', now()->subWeek())
                        ->count()
                ]
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $statistiques,
                    'event' => $event
                ]);
            }

            return view('components.private.events.inscriptions.statistiques', compact('event', 'statistiques'));

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du calcul des statistiques',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du calcul des statistiques: ' . $e->getMessage());
        }
    }
}
