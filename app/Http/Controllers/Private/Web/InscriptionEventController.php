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
        $this->middleware('permission:events.read')->only(['inscriptionsEvent', 'listeAttente', 'statistiquesInscriptions']);
        $this->middleware('permission:events.manage_inscriptions')->only(['ajouterInscription', 'modifierInscription', 'supprimerInscription', 'promouvoirInscription', 'annulerInscription', 'reactiverOrAnnulerInscription']);
    }

    /**
     * Afficher les inscriptions d'un événement
     */
    public function inscriptionsEvent(Event $event, Request $request)
    {
        try {
            // Construction de la requête de base avec les relations nécessaires
            $query = InscriptionEvent::query()
                ->with(['inscrit', 'createur', 'modificateur', 'annulateur']) // Chargement eager des relations pour éviter le problème N+1
                ->where('event_id', $event->id); // Filtrer par l'ID de l'événement

            // SECTION FILTRES
            // Filtre de recherche textuelle
            if ($request->filled('search')) {
                $search = $request->get('search'); // Récupération du terme de recherche
                $query->whereHas('inscrit', function ($q) use ($search) {
                    // Recherche dans la relation 'inscrit' (membres inscrit)
                    $q->where('prenom', 'ILIKE', "%{$search}%")        // Recherche dans le prénom (insensible à la casse)
                        ->orWhere('nom', 'ILIKE', "%{$search}%")        // OU dans le nom
                        ->orWhere('email', 'ILIKE', "%{$search}%")      // OU dans l'email
                        ->orWhere('telephone_1', 'ILIKE', "%{$search}%"); // OU dans le téléphone
                });
            }

            // Filtre par statut de l'inscription
            if ($request->filled('statut')) {
                $statut = $request->get('statut'); // Récupération du statut demandé
                switch ($statut) {
                    case 'active':
                        $query->whereNull('annule_le'); // Inscriptions non annulées
                        break;
                    case 'annulee':
                        $query->whereNotNull('annule_le'); // Inscriptions annulées
                        break;
                }
            }

            // Filtre par date d'inscription
            if ($request->filled('date_inscription')) {
                $query->whereDate('created_at', $request->get('date_inscription')); // Filtre sur la date de création
            }

            // SECTION TRI
            $sortBy = $request->get('sort_by', 'created_at'); // Colonne de tri (défaut: date de création)
            $sortOrder = $request->get('sort_order', 'desc'); // Ordre de tri (défaut: descendant)

            $allowedSorts = ['created_at', 'annule_le']; // Colonnes autorisées pour le tri (sécurité)
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortOrder); // Application du tri si autorisé
            }

            // SECTION PAGINATION
            $perPage = min($request->get('per_page', 10), 100); // Limite à 100 éléments par page maximum
            $inscriptions = $query->paginate($perPage); // Exécution de la requête avec pagination

            // SECTION STATISTIQUES
            // Calcul des statistiques rapides pour l'événement
            $statistiques = [
                'total_inscriptions' => InscriptionEvent::where('event_id', $event->id)->count(), // Nombre total d'inscriptions
                'inscriptions_actives' => InscriptionEvent::where('event_id', $event->id)
                    ->whereNull('annule_le')->count(), // Nombre d'inscriptions actives
                'inscriptions_annulees' => InscriptionEvent::where('event_id', $event->id)
                    ->whereNotNull('annule_le')->count(), // Nombre d'inscriptions annulées
                'places_restantes' => $event->capacite_totale
                    ? max(0, $event->capacite_totale - $event->nombre_inscrits) // Calcul des places restantes
                    : null, // Null si pas de capacité définie
                'taux_remplissage' => $event->pourcentage_remplissage // Pourcentage de remplissage
            ];

            // Récupération des cultes (triés par date décroissante)
            $cultes = Culte::orderByDesc('date_culte')->get();

            // SECTION UTILISATEURS ÉLIGIBLES
            // Récupération des membres qui peuvent encore s'inscrire
            $users = User::query()
                ->where('actif', true) // Membress actifs uniquement
                ->whereNotExists(function ($query) use ($event) {
                    // Sous-requête pour exclure les membres déjà inscrits
                    $query->select(DB::raw(1))
                        ->from('inscription_events') // Table des inscriptions
                        ->whereColumn('inscription_events.inscrit_id', 'users.id') // Jointure par ID membres
                        ->where('inscription_events.event_id', $event->id) // Pour cet événement
                        ->whereNull('inscription_events.deleted_at'); // Non supprimées (soft delete)
                })
                ->where(function ($query) use ($event) {
                    // Exclure les responsables/organisateurs de l'événement
                    $query->where('id', '!=', $event->organisateur_principal_id) // Organisateur principal
                        ->where('id', '!=', $event->coordinateur_id) // Coordinateur
                        ->where('id', '!=', $event->responsable_logistique_id) // Responsable logistique
                        ->where('id', '!=', $event->responsable_communication_id) // Responsable communication
                        ->where('id', '!=', $event->cree_par); // Créateur de l'événement
                })
                ->orderByRaw('LOWER(nom) ASC') // Tri alphabétique insensible à la casse
                ->get(); // Récupération de tous les résultats

            // SECTION RÉPONSE
            // Si la requête attend du JSON (API)
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $inscriptions, // Données paginées
                    'statistiques' => $statistiques, // Statistiques calculées
                    'event' => $event, // Informations de l'événement
                    'meta' => [ // Métadonnées de pagination
                        'total' => $inscriptions->total(), // Nombre total d'éléments
                        'per_page' => $inscriptions->perPage(), // Éléments par page
                        'current_page' => $inscriptions->currentPage(), // Page actuelle
                        'last_page' => $inscriptions->lastPage() // Dernière page
                    ]
                ]);
            }

            // Si requête web classique, retourner la vue
            return view('components.private.events.inscriptions.index', compact('event', 'inscriptions', 'statistiques', 'cultes', 'users'));

        } catch (\Exception $e) {
            // GESTION DES ERREURS
            // Si requête JSON, retourner erreur JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des inscriptions',
                    'error' => $e->getMessage() // Message d'erreur détaillé
                ], Response::HTTP_INTERNAL_SERVER_ERROR); // Code 500
            }

            // Si requête web, rediriger avec message d'erreur
            return redirect()->back()
                ->with('error', 'Erreur lors du chargement des inscriptions: ' . $e->getMessage());
        }
    }



    /**
     * Ajouter une inscription à un événement
     */
    public function ajouterInscription(Event $event, Request $request)
    {
        // SECTION VALIDATION
        // Validation des données d'entrée
        $validator = Validator::make($request->all(), [
            'inscrit_id' => ['required', 'uuid', 'exists:users,id'] // L'ID doit être présent, être un UUID valide et exister dans la table users
        ]);

        // Si la validation échoue
        if ($validator->fails()) {
            // Réponse JSON pour les requêtes API
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors() // Détails des erreurs de validation
                ], Response::HTTP_UNPROCESSABLE_ENTITY); // Code 422
            }
            // Redirection pour les requêtes web avec erreurs et données saisies
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // SECTION TRANSACTION
            DB::beginTransaction(); // Démarrage d'une transaction pour assurer la cohérence

            $inscritId = $request->get('inscrit_id'); // Récupération de l'ID de l'membres à inscrire

            // SECTION VÉRIFICATION INSCRIPTION EXISTANTE
            // Recherche d'une inscription existante (y compris supprimées avec soft delete)
            $inscriptionExistante = InscriptionEvent::withTrashed() // Inclut les enregistrements supprimés
                ->where('event_id', $event->id) // Pour cet événement
                ->where('inscrit_id', $inscritId) // Pour cet membres
                ->first(); // Récupère le premier résultat

            // Si une inscription existe déjà (même supprimée)
            if ($inscriptionExistante) {
                // Réactivation de l'inscription existante
                $inscriptionExistante->cree_par = auth()->id(); // Nouvel membres créateur
                $inscriptionExistante->cree_le = now(); // Nouvelle date de création
                $inscriptionExistante->modifie_par = null; // Remise à zéro du modificateur
                $inscriptionExistante->supprimer_par = null; // Remise à zéro du suppresseur
                $inscriptionExistante->annule_par = null; // Remise à zéro de l'annulateur
                $inscriptionExistante->annule_le = null; // Remise à zéro de la date d'annulation
                $inscriptionExistante->deleted_at = null; // Restauration de l'enregistrement (soft delete)
                $inscriptionExistante->save(); // Sauvegarde des modifications

                // ERREUR DANS LE MESSAGE : le message dit "complet" mais c'est pour une réactivation
                $message = 'L\'événement est complet et n\'accepte pas de liste d\'attente'; // ⚠️ Message incorrect ici

                // Incrémentation du compteur d'inscrits de l'événement
                $event->increment('nombre_inscrits');

                DB::commit(); // Validation de la transaction

                // Réponse selon le type de requête
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'data' => $inscriptionExistante,
                        'message' => $message
                    ], Response::HTTP_CREATED); // Code 201
                }

                return redirect()->back()->with('success', $message);
            }

            // SECTION VÉRIFICATION CAPACITÉ
            // Vérifier si l'événement a une capacité limitée et si elle est atteinte
            if ($event->capacite_totale && $event->nombre_inscrits >= $event->capacite_totale) {
                // Si l'événement n'accepte pas de liste d'attente
                if (!$event->liste_attente) {
                    $message = 'L\'événement est complet et n\'accepte pas de liste d\'attente';

                    // Réponse selon le type de requête
                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message
                        ], Response::HTTP_UNPROCESSABLE_ENTITY); // Code 422
                    }

                    return redirect()->back()->with('error', $message);
                }
                // Note: Si liste d'attente acceptée, le code continue sans vérification supplémentaire
            }

            // SECTION VÉRIFICATION PÉRIODE D'INSCRIPTION
            // Vérifier si les inscriptions sont encore ouvertes (méthode du modèle Event)
            if (!$event->accepteInscriptions()) {
                $message = 'Les inscriptions ne sont plus ouvertes pour cet événement';

                // Réponse selon le type de requête
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY); // Code 422
                }

                return redirect()->back()->with('error', $message);
            }

            // SECTION CRÉATION INSCRIPTION
            // Création de la nouvelle inscription
            $inscription = InscriptionEvent::create([
                'inscrit_id' => $inscritId, // ID de l'membres inscrit
                'event_id' => $event->id, // ID de l'événement
                'cree_par' => auth()->id(), // ID de l'membres qui effectue l'inscription
                'cree_le' => now(), // Date/heure de création
            ]);

            // Incrémentation du compteur d'inscrits de l'événement
            $event->increment('nombre_inscrits');

            DB::commit(); // Validation de la transaction

            // Chargement des relations pour la réponse
            $inscription->load(['inscrit', 'createur']); // Eager loading des relations

            $message = 'Inscription ajoutée avec succès';

            // SECTION RÉPONSE SUCCÈS
            // Réponse selon le type de requête
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $inscription // Inscription créée avec relations
                ], Response::HTTP_CREATED); // Code 201
            }

            // Redirection vers la liste des inscriptions avec message de succès
            return redirect()->route('private.events.inscriptions', $event)
                ->with('success', $message);

        } catch (\Exception $e) {
            // SECTION GESTION D'ERREUR
            DB::rollBack(); // Annulation de la transaction en cas d'erreur

            // Réponse d'erreur selon le type de requête
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'ajout de l\'inscription',
                    'error' => $e->getMessage() // Message d'erreur détaillé
                ], Response::HTTP_INTERNAL_SERVER_ERROR); // Code 500
            }

            // Redirection avec erreur et conservation des données saisies
            return redirect()->back()
                ->withInput() // Conservation des données du formulaire
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

            $inscription->load(['inscrit', 'annulateur']);

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
     * Annuler une inscription
     */
    public function reactivateInscription(Event $event, InscriptionEvent $inscription, Request $request)
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


            DB::beginTransaction();

            // Vérifier que l'inscription n'est pas déjà annulée
            if ($inscription->annule_le) {
                $inscription->update([
                    'annule_par' => null,
                    'annule_le' => null,
                    'modifie_par' => null,
                ]);

                // Mettre à jour le compteur d'inscrits
                $event->increment('nombre_inscrits');
                $message = 'Inscription réactivée avec succès.';
            } else {
                $message = 'Cette inscription est déjà activé.';

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);

            }


            DB::commit();

            $inscription->load(['inscrit', 'annulateur']);



            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $inscription
                ]);
            }

            return redirect()->route('private.events.inscriptions', $event)->with('success', $message);

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
