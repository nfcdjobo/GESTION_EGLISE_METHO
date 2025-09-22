<?php

namespace App\Http\Controllers\Private\Web;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\EventRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Constructeur avec middleware d'authentification
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:events.read')->only(['index', 'show', 'statistiques', 'planning', 'dashboard']);
        $this->middleware('permission:events.create')->only(['create', 'store', 'dupliquer']);
        $this->middleware('permission:events.update')->only(['edit', 'update', 'changerStatut', 'restore']);
        $this->middleware('permission:events.delete')->only(['destroy']);
    }

    /**
     * Afficher la liste des événements avec filtres et pagination
     */
    public function index(Request $request)
    {
        $query = Event::query()->with([
            'organisateurPrincipal',
            'coordinateur',
            'responsableLogistique',
            'responsableCommunication'
        ]);

        // Filtres de recherche
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('titre', 'ILIKE', "%{$search}%")
                  ->orWhere('description', 'ILIKE', "%{$search}%")
                  ->orWhere('lieu_nom', 'ILIKE', "%{$search}%")
                  ->orWhere('lieu_ville', 'ILIKE', "%{$search}%");
            });
        }

        // Filtres de statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->get('statut'));
        }

        if ($request->filled('type_evenement')) {
            $query->where('type_evenement', $request->get('type_evenement'));
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->get('categorie'));
        }

        if ($request->filled('organisateur_id')) {
            $query->where('organisateur_principal_id', $request->get('organisateur_id'));
        }

        // Filtre par date
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_debut', [
                $request->get('date_debut'),
                $request->get('date_fin')
            ]);
        } elseif ($request->filled('date_evenement')) {
            $query->whereDate('date_debut', $request->get('date_evenement'));
        }

        if ($request->filled('lieu_ville')) {
            $query->where('lieu_ville', 'ILIKE', '%' . $request->get('lieu_ville') . '%');
        }

        if ($request->boolean('publics_seulement')) {
            $query->publics();
        }

        if ($request->boolean('a_venir')) {
            $query->aVenir();
        }

        if ($request->boolean('termines')) {
            $query->termines();
        }

        if ($request->boolean('inscription_requise')) {
            $query->where('inscription_requise', true);
        }

        // Tri
        $sortBy = $request->get('sort_by', 'date_debut');
        $sortOrder = $request->get('sort_order', 'asc');

        $allowedSorts = [
            'date_debut', 'date_fin', 'heure_debut', 'titre', 'type_evenement',
            'statut', 'nombre_participants', 'created_at', 'priorite'
        ];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = min($request->get('per_page', 10), 100);
        $events = $query->paginate($perPage);

        // Réponse selon le type de requête
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $events,
                'meta' => [
                    'total' => $events->total(),
                    'per_page' => $events->perPage(),
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage()
                ]
            ]);
        }

        // Données supplémentaires pour la vue Blade
        $organisateurs = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Admin', 'Organisateur', 'Coordinateur']);
        })->orderBy('nom')->get();

        return view('components.private.events.index', compact('events', 'organisateurs'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $organisateurs = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Admin', 'Organisateur', 'Coordinateur']);
        })->orderBy('nom')->get();

        $users = User::orderBy('nom')->get();

        return view('components.private.events.create', compact('organisateurs', 'users'));
    }

    /**
     * Afficher un événement spécifique
     */
    public function show(Event $event)
    {
        $event->load([
            'organisateurPrincipal',
            'coordinateur',
            'responsableLogistique',
            'responsableCommunication',
            'annulePar',
            'createur',
            'modificateur'
        ]);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $event
            ]);
        }

        return view('components.private.events.show', compact('event'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Event $event)
    {
        $organisateurs = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['Admin', 'Organisateur', 'Coordinateur']);
        })->orderBy('nom')->get();

        $users = User::orderBy('nom')->get();

        return view('components.private.events.edit', compact('event', 'organisateurs', 'users'));
    }

    /**
     * Créer un nouvel événement
     */
    public function store(EventRequest $request)
    {
        try {
            DB::beginTransaction();

            $event = Event::create($request->validated());

            // Gestion des images si présentes
            if ($request->hasFile('images')) {
                $imagesUrls = $this->handleImagesUpload($request->file('images'));
                $event->update(['galerie_images' => $imagesUrls]);
            }

            if ($request->hasFile('image_principale')) {
                $imagePath = $request->file('image_principale')->store('events/images', 'public');
                $event->update(['image_principale' => Storage::url($imagePath)]);
            }

            DB::commit();

            $event->load([
                'organisateurPrincipal',
                'coordinateur',
                'responsableLogistique',
                'responsableCommunication'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Événement créé avec succès',
                    'data' => $event
                ], Response::HTTP_CREATED);
            }

            return redirect()->route('private.events.show', $event)
                ->with('success', 'Événement créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création de l\'événement',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création de l\'événement: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour un événement
     */
    public function update(EventRequest $request, Event $event)
    {
        try {
            DB::beginTransaction();

            $event->update($request->validated());

            // Gestion des nouvelles images si présentes
            if ($request->hasFile('images')) {
                // Supprimer les anciennes images si nécessaire
                $this->deleteOldImages($event->galerie_images);

                $imagesUrls = $this->handleImagesUpload($request->file('images'));
                $event->update(['galerie_images' => $imagesUrls]);
            }

            if ($request->hasFile('image_principale')) {
                // Supprimer l'ancienne image principale
                if ($event->image_principale) {
                    $this->deleteImage($event->image_principale);
                }

                $imagePath = $request->file('image_principale')->store('events/images', 'public');
                $event->update(['image_principale' => Storage::url($imagePath)]);
            }

            DB::commit();

            $event->load([
                'organisateurPrincipal',
                'coordinateur',
                'responsableLogistique',
                'responsableCommunication'
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Événement mis à jour avec succès',
                    'data' => $event
                ]);
            }

            return redirect()->route('private.events.show', $event)
                ->with('success', 'Événement mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour de l\'événement',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un événement (soft delete)
     */
    public function destroy(Event $event)
    {
        try {
            // Vérifier si l'événement peut être supprimé
            if ($event->statut === 'en_cours') {
                $message = 'Impossible de supprimer un événement en cours';

                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            $event->delete();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Événement supprimé avec succès'
                ]);
            }

            return redirect()->route('private.events.index')
                ->with('success', 'Événement supprimé avec succès');

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression de l\'événement',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Restaurer un événement supprimé
     */
    public function restore(string $id)
    {
        try {
            $event = Event::withTrashed()->findOrFail($id);
            $event->restore();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Événement restauré avec succès',
                    'data' => $event
                ]);
            }

            return redirect()->route('private.events.show', $event)
                ->with('success', 'Événement restauré avec succès');

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la restauration de l\'événement',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la restauration: ' . $e->getMessage());
        }
    }

    /**
     * Changer le statut d'un événement
     */
    public function changerStatut(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'statut' => ['required', 'in:brouillon,planifie,en_promotion,ouvert_inscription,complet,en_cours,termine,annule,reporte,archive'],
            'raison' => ['required_if:statut,annule,reporte', 'string', 'max:500']
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors(),
                    'payload' => $request->all()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $nouveauStatut = $request->get('statut');
            $ancienStatut = $event->statut;

            // Vérifications métier selon le changement de statut
            if (!$this->peutChangerStatut($event, $ancienStatut, $nouveauStatut)) {
                $message = "Changement de statut non autorisé de '{$ancienStatut}' vers '{$nouveauStatut}'";

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                return redirect()->back()->with('error', $message);
            }

            // Actions spécifiques selon le nouveau statut
            $updates = ['statut' => $nouveauStatut];

            if (in_array($nouveauStatut, ['annule', 'reporte'])) {
                $updates['motif_annulation'] = $request->get('raison');
                $updates['annule_par'] = auth()->id();
                $updates['annule_le'] = now();
            }

            $event->update($updates);

            $message = "Statut changé avec succès de '{$ancienStatut}' vers '{$nouveauStatut}'";

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $event
                ]);
            }

            return redirect()->route('private.events.show', $event)
                ->with('success', $message);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du changement de statut',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du changement de statut: ' . $e->getMessage());
        }
    }

    /**
     * Dupliquer un événement
     */
    public function dupliquer(Event $event, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nouvelle_date' => ['required', 'date', 'after:today'],
            'nouvelle_heure' => ['nullable', 'date_format:H:i'],
            'nouveau_titre' => ['nullable', 'string', 'max:200']
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
            $nouvelEvent = $event->replicate();

            // Réinitialiser certains champs
            $nouvelEvent->date_debut = $request->get('nouvelle_date');
            $nouvelEvent->heure_debut = $request->get('nouvelle_heure', $event->heure_debut);
            $nouvelEvent->titre = $request->get('nouveau_titre', $event->titre . ' (Copie)');
            $nouvelEvent->statut = 'brouillon';
            $nouvelEvent->nombre_participants = null;
            $nouvelEvent->nombre_inscrits = 0;
            $nouvelEvent->places_reservees = 0;
            $nouvelEvent->annule_par = null;
            $nouvelEvent->annule_le = null;
            $nouvelEvent->motif_annulation = null;
            $nouvelEvent->note_globale = null;
            $nouvelEvent->note_organisation = null;
            $nouvelEvent->note_contenu = null;
            $nouvelEvent->note_lieu = null;
            $nouvelEvent->taux_satisfaction = null;
            $nouvelEvent->feedback_participants = null;
            $nouvelEvent->galerie_images = null;
            $nouvelEvent->image_principale = null;

            $nouvelEvent->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Événement dupliqué avec succès',
                    'data' => $nouvelEvent
                ], Response::HTTP_CREATED);
            }

            return redirect()->route('private.events.show', $nouvelEvent)
                ->with('success', 'Événement dupliqué avec succès');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la duplication de l\'événement',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la duplication: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les statistiques des événements
     */
    public function statistiques(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'type_evenement' => ['nullable', 'string'],
            'organisateur_id' => ['nullable', 'uuid', 'exists:users,id']
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $dateDebut = $request->get('date_debut', now()->subYear()->format('Y-m-d'));
            $dateFin = $request->get('date_fin', now()->format('Y-m-d'));

            // Requête de base pour les statistiques
            $baseQuery = Event::whereBetween('date_debut', [$dateDebut, $dateFin]);

            if ($request->filled('type_evenement')) {
                $baseQuery->where('type_evenement', $request->get('type_evenement'));
            }

            if ($request->filled('organisateur_id')) {
                $baseQuery->where('organisateur_principal_id', $request->get('organisateur_id'));
            }

            $statistiques = [
                'periode' => [
                    'debut' => $dateDebut,
                    'fin' => $dateFin
                ],
                'totaux' => [
                    'nombre_events' => (clone $baseQuery)->count(),
                    'events_termines' => (clone $baseQuery)->where('statut', 'termine')->count(),
                    'events_annules' => (clone $baseQuery)->where('statut', 'annule')->count(),
                    'total_participants' => (clone $baseQuery)->sum('nombre_participants') ?: 0,
                    'total_recettes' => round((clone $baseQuery)->sum('recettes_inscriptions') ?: 0, 2),
                    'budget_total' => round((clone $baseQuery)->sum('budget_prevu') ?: 0, 2)
                ],
                'moyennes' => [
                    'participants_par_event' => round((clone $baseQuery)->avg('nombre_participants') ?: 0, 1),
                    'note_globale' => round((clone $baseQuery)->avg('note_globale') ?: 0, 1),
                    'note_organisation' => round((clone $baseQuery)->avg('note_organisation') ?: 0, 1),
                    'note_contenu' => round((clone $baseQuery)->avg('note_contenu') ?: 0, 1),
                    'taux_satisfaction' => round((clone $baseQuery)->avg('taux_satisfaction') ?: 0, 1)
                ],
                'par_type' => (clone $baseQuery)->select('type_evenement')
                    ->selectRaw('COUNT(*) as nombre')
                    ->selectRaw('COALESCE(SUM(nombre_participants), 0) as total_participants')
                    ->selectRaw('ROUND(COALESCE(AVG(nombre_participants), 0), 1) as moyenne_participants')
                    ->groupBy('type_evenement')
                    ->orderBy('nombre', 'desc')
                    ->get(),
                'par_mois' => (clone $baseQuery)->select(
                        DB::raw('EXTRACT(YEAR FROM date_debut) as annee'),
                        DB::raw('EXTRACT(MONTH FROM date_debut) as mois')
                    )
                    ->selectRaw('COUNT(*) as nombre_events')
                    ->selectRaw('COALESCE(SUM(nombre_participants), 0) as total_participants')
                    ->selectRaw('ROUND(COALESCE(SUM(recettes_inscriptions), 0), 2) as total_recettes')
                    ->groupBy('annee', 'mois')
                    ->orderBy('annee', 'desc')
                    ->orderBy('mois', 'desc')
                    ->get()
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $statistiques
                ]);
            }

            $organisateurs = User::whereHas('roles', function($q) {
                $q->whereIn('name', ['Admin', 'Organisateur', 'Coordinateur']);
            })->orderBy('nom')->get();

            return view('components.private.events.statistiques', compact('statistiques', 'organisateurs'));

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

    /**
     * Obtenir le planning des événements
     */
    public function planning(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'vue' => ['nullable', 'in:semaine,mois,annee']
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $vue = $request->get('vue', 'mois');
            $dateDebut = $request->get('date_debut');
            $dateFin = $request->get('date_fin');

            // Définir les dates selon la vue
            if (!$dateDebut || !$dateFin) {
                $maintenant = now();
                switch ($vue) {
                    case 'semaine':
                        $dateDebut = $maintenant->copy()->startOfWeek()->format('Y-m-d');
                        $dateFin = $maintenant->copy()->endOfWeek()->format('Y-m-d');
                        break;
                    case 'annee':
                        $dateDebut = $maintenant->copy()->startOfYear()->format('Y-m-d');
                        $dateFin = $maintenant->copy()->endOfYear()->format('Y-m-d');
                        break;
                    default: // mois
                        $dateDebut = $maintenant->copy()->startOfMonth()->format('Y-m-d');
                        $dateFin = $maintenant->copy()->endOfMonth()->format('Y-m-d');
                }
            }

            $events = Event::with([
                    'organisateurPrincipal',
                    'coordinateur'
                ])
                ->whereBetween('date_debut', [$dateDebut, $dateFin])
                ->whereIn('statut', ['brouillon', 'planifie', 'en_promotion', 'ouvert_inscription', 'en_cours'])
                ->orderBy('date_debut')
                ->orderBy('heure_debut')
                ->get();

            $data = [
                'periode' => [
                    'debut' => $dateDebut,
                    'fin' => $dateFin,
                    'vue' => $vue
                ],
                'events' => $events
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return view('components.private.events.planning', $data);

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération du planning',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du chargement du planning: ' . $e->getMessage());
        }
    }

    /**
     * Obtenir les données pour le tableau de bord
     */
    public function dashboard()
    {
        try {
            $aujourd_hui = now()->format('Y-m-d');
            $debut_semaine = now()->copy()->startOfWeek()->format('Y-m-d');
            $fin_semaine = now()->copy()->endOfWeek()->format('Y-m-d');
            $debut_mois = now()->copy()->startOfMonth()->format('Y-m-d');

            $dashboard = [
                'aujourd_hui' => [
                    'events' => Event::whereDate('date_debut', $aujourd_hui)
                        ->with(['organisateurPrincipal'])
                        ->orderBy('heure_debut')
                        ->get(),
                    'nombre' => Event::whereDate('date_debut', $aujourd_hui)->count()
                ],
                'cette_semaine' => [
                    'events_a_venir' => Event::whereBetween('date_debut', [$debut_semaine, $fin_semaine])
                        ->whereIn('statut', ['planifie', 'en_promotion', 'ouvert_inscription'])
                        ->count(),
                    'events_termines' => Event::whereBetween('date_debut', [$debut_semaine, $fin_semaine])
                        ->where('statut', 'termine')
                        ->count()
                ],
                'ce_mois' => [
                    'total_events' => Event::where('date_debut', '>=', $debut_mois)->count(),
                    'total_participants' => Event::where('date_debut', '>=', $debut_mois)
                        ->sum('nombre_participants') ?: 0,
                    'total_recettes' => round(Event::where('date_debut', '>=', $debut_mois)
                        ->sum('recettes_inscriptions') ?: 0, 2),
                    'events_publics' => Event::where('date_debut', '>=', $debut_mois)
                        ->where('ouvert_public', true)
                        ->count()
                ],
                'prochains_events' => Event::where('date_debut', '>=', $aujourd_hui)
                    ->whereIn('statut', ['planifie', 'en_promotion', 'ouvert_inscription'])
                    ->with(['organisateurPrincipal'])
                    ->orderBy('date_debut')
                    ->orderBy('heure_debut')
                    ->limit(5)
                    ->get(),
                'statistiques_rapides' => [
                    'note_moyenne_mois' => round(Event::where('date_debut', '>=', $debut_mois)
                        ->avg('note_globale') ?: 0, 1),
                    'taux_inscription' => $this->calculerTauxInscription($debut_mois)
                ]
            ];

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $dashboard
                ]);
            }

            return view('components.private.events.dashboard', compact('dashboard'));

        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération du tableau de bord',
                    'error' => $e->getMessage()
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du chargement du tableau de bord: ' . $e->getMessage());
        }
    }

    /**
     * Gérer l'upload des images
     */
    private function handleImagesUpload(array $images): array
    {
        $imagesUrls = [];

        foreach ($images as $image) {
            if ($image->isValid()) {
                $path = $image->store('events/images', 'public');
                $imagesUrls[] = Storage::url($path);
            }
        }

        return $imagesUrls;
    }

    /**
     * Supprimer les anciennes images
     */
    private function deleteOldImages(?array $imagesUrls): void
    {
        if (!$imagesUrls) return;

        foreach ($imagesUrls as $url) {
            $this->deleteImage($url);
        }
    }

    /**
     * Supprimer une image
     */
    private function deleteImage(string $imageUrl): void
    {
        $path = str_replace('/storage/', '', parse_url($imageUrl, PHP_URL_PATH));
        Storage::disk('public')->delete($path);
    }

    /**
     * Vérifier si un changement de statut est autorisé
     */
    private function peutChangerStatut(Event $event, string $ancienStatut, string $nouveauStatut): bool
    {
        $transitions_autorisees = [
            'brouillon' => ['planifie', 'annule'],
            'planifie' => ['en_promotion', 'brouillon', 'annule', 'reporte'],
            'en_promotion' => ['ouvert_inscription', 'planifie', 'annule', 'reporte'],
            'ouvert_inscription' => ['complet', 'en_cours', 'en_promotion', 'annule', 'reporte'],
            'complet' => ['en_cours', 'ouvert_inscription', 'annule', 'reporte'],
            'en_cours' => ['termine', 'annule'],
            'termine' => ['archive'], // Un événement terminé peut être archivé
            'annule' => ['planifie', 'brouillon'], // Peut être réactivé
            'reporte' => ['planifie', 'brouillon'], // Peut être réactivé
            'archive' => [] // Archivé ne peut plus changer
        ];

        return in_array($nouveauStatut, $transitions_autorisees[$ancienStatut] ?? []);
    }

    /**
     * Calculer le taux d'inscription
     */
    private function calculerTauxInscription(string $dateDebut): float
    {
        $events = Event::where('date_debut', '>=', $dateDebut)
            ->where('statut', 'termine')
            ->where('inscription_requise', true)
            ->whereNotNull('capacite_totale')
            ->whereNotNull('nombre_inscrits')
            ->where('capacite_totale', '>', 0)
            ->get();

        if ($events->isEmpty()) {
            return 0;
        }

        $totalCapacite = $events->sum('capacite_totale');
        $totalInscrits = $events->sum('nombre_inscrits');

        return $totalCapacite > 0 ? round(($totalInscrits / $totalCapacite) * 100, 1) : 0;
    }
}
