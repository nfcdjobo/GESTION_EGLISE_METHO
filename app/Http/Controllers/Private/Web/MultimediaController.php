<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\User;
use App\Models\Culte;
use App\Models\Event;
use Illuminate\View\View;
use App\Models\Reunion;
use App\Models\Multimedia;
use Illuminate\Http\Request;
use App\Models\Intervention;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MultimediaController extends Controller
{

    public function __construct()
{
    $this->middleware('auth');
    $this->middleware('permission:multimedia.read')->only(['index', 'show', 'download', 'galerie', 'statistiques']);
    $this->middleware('permission:multimedia.create')->only(['create', 'store']);
    $this->middleware('permission:multimedia.update')->only(['edit', 'update', 'toggleFeatured']);
    $this->middleware('permission:multimedia.delete')->only(['destroy']);
    $this->middleware('permission:multimedia.moderate')->only(['approve', 'reject', 'bulkModerate']);
}


    /**
     * Afficher la liste des médias avec filtrage et pagination
     */
    public function index(Request $request)
    {
        $query = Multimedia::with(['culte', 'event', 'intervention', 'reunion', 'uploadedBy', 'moderator'])
                           ->orderBy('created_at', 'desc');

        // Filtres disponibles
        if ($request->filled('culte_id')) {
            $query->where('culte_id', $request->culte_id);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('intervention_id')) {
            $query->where('intervention_id', $request->intervention_id);
        }

        if ($request->filled('reunion_id')) {
            $query->where('reunion_id', $request->reunion_id);
        }

        if ($request->filled('type_media')) {
            $query->ofType($request->type_media);
        }

        if ($request->filled('categorie')) {
            $query->ofCategory($request->categorie);
        }

        if ($request->filled('statut_moderation')) {
            if ($request->statut_moderation === 'en_attente') {
                $query->enAttente();
            } else {
                $query->where('statut_moderation', $request->statut_moderation);
            }
        }

        if ($request->filled('niveau_acces')) {
            $query->where('niveau_acces', $request->niveau_acces);
        }

        if ($request->filled('telecharge_par')) {
            $query->where('telecharge_par', $request->telecharge_par);
        }

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('legende', 'like', "%{$search}%")
                  ->orWhere('photographe', 'like', "%{$search}%")
                  ->orWhere('lieu_prise', 'like', "%{$search}%");
            });
        }

        // Filtres spéciaux
        if ($request->boolean('featured_only')) {
            $query->featured();
        }

        if ($request->boolean('recent_only')) {
            $query->recent($request->get('recent_days', 30));
        }

        if ($request->boolean('visible_only')) {
            $query->visible();
        }

        $multimedia = $query->paginate($request->get('per_page', 10));

        // Données pour les filtres
        $cultes = Culte::select('id', 'titre')->orderBy('titre')->get();
        $events = Event::select('id', 'titre')->orderBy('titre')->get();
        $reunions = Reunion::select('id', 'titre')->orderBy('titre')->get();
        $uploaders = User::select('id', 'nom', 'prenom', 'telephone_1')->orderBy('nom')->get();

        $data = [
            'multimedia' => $multimedia,
            'cultes' => $cultes,
            'events' => $events,
            'reunions' => $reunions,
            'uploaders' => $uploaders,
            'filters' => [
                'types_media' => Multimedia::TYPES_MEDIA,
                'categories' => Multimedia::CATEGORIES,
                'statuts_moderation' => Multimedia::STATUTS_MODERATION,
                'niveaux_acces' => Multimedia::NIVEAUX_ACCES,
                'qualites' => Multimedia::QUALITES
            ],
            'currentFilters' => $request->only([
                'culte_id', 'event_id', 'intervention_id', 'reunion_id',
                'type_media', 'categorie', 'statut_moderation',
                'niveau_acces', 'telecharge_par', 'search'
            ])
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('components.private.multimedia.index', $data);
    }

    /**
     * Afficher le formulaire de téléchargement de nouveau média
     */
    public function create(Request $request)
    {
        $cultes = Culte::select('id', 'titre', 'date_culte')->orderBy('date_culte', 'desc')->get();
        $events = Event::select('id', 'titre', 'date_debut')->orderBy('date_debut', 'desc')->get();
        $reunions = Reunion::select('id', 'titre', 'date_reunion')->orderBy('date_reunion', 'desc')->get();
        $interventions = Intervention::with(['culte', 'reunion', 'intervenant'])
                                   ->select('id', 'titre', 'culte_id', 'reunion_id', 'intervenant_id')
                                   ->orderBy('created_at', 'desc')
                                   ->take(50)
                                   ->get();

        $data = [
            'cultes' => $cultes,
            'events' => $events,
            'reunions' => $reunions,
            'interventions' => $interventions,
            'types_media' => Multimedia::TYPES_MEDIA,
            'categories' => Multimedia::CATEGORIES,
            'niveaux_acces' => Multimedia::NIVEAUX_ACCES,
            'qualites' => Multimedia::QUALITES,
            'multimedia' => new Multimedia()
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('components.private.multimedia.create', $data);
    }

    /**
     * Enregistrer un nouveau média
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Relations (au moins une requise)
            'culte_id' => 'nullable|uuid|exists:cultes,id',
            'event_id' => 'nullable|uuid|exists:events,id',
            'intervention_id' => 'nullable|uuid|exists:interventions,id',
            'reunion_id' => 'nullable|uuid|exists:reunions,id',

            // Fichier requis
            'fichier' => 'required|file|max:2097152', // 2GB max

            // Informations de base
            'titre' => 'required|string|max:200',
            'description' => 'nullable|string',
            'legende' => 'nullable|string',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',

            // Catégorisation
            'categorie' => ['required', Rule::in(array_keys(Multimedia::CATEGORIES))],
            'qualite' => ['nullable', Rule::in(array_keys(Multimedia::QUALITES))],

            // Métadonnées optionnelles
            'date_prise' => 'nullable|date|before_or_equal:today',
            'lieu_prise' => 'nullable|string|max:200',
            'photographe' => 'nullable|string|max:100',
            'appareil' => 'nullable|string|max:100',

            // Permissions et accès
            'niveau_acces' => ['required', Rule::in(array_keys(Multimedia::NIVEAUX_ACCES))],
            'usage_public' => 'boolean',
            'usage_site_web' => 'boolean',
            'usage_reseaux_sociaux' => 'boolean',
            'usage_commercial' => 'boolean',
            'restrictions_usage' => 'nullable|string',

            // SEO
            'alt_text' => 'nullable|string|max:255',
            'titre_seo' => 'nullable|string|max:200',
            'description_seo' => 'nullable|string',

            // Options
            'contenu_sensible' => 'boolean',
            'avertissement' => 'nullable|string',
            'est_featured' => 'boolean'
        ]);

        // Validation personnalisée : au moins une relation requise
        $validator->after(function ($validator) use ($request) {
            if (!$request->filled('culte_id') &&
                !$request->filled('event_id') &&
                !$request->filled('intervention_id') &&
                !$request->filled('reunion_id')) {
                $validator->errors()->add('evenement', 'Le média doit être associé à au moins un événement.');
            }
        });

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $file = $request->file('fichier');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();

            // Déterminer le type de média basé sur le MIME type
            $typeMedia = $this->determineMediaType($mimeType);

            // Générer un nom de fichier unique
            $fileName = uniqid() . '_' . time() . '.' . $extension;

            // Stocker le fichier
            $path = $file->storeAs('multimedia/' . $typeMedia, $fileName, 'public');
            $urlPublique = Storage::url($path);

            // Extraire les métadonnées selon le type
            $metadata = $this->extractMetadata($file->path(), $typeMedia);

            // Générer une miniature si c'est une image
            $miniaturePath = null;
            if ($typeMedia === 'image') {
                $miniaturePath = $this->generateThumbnail($file->path(), $path);
            }

            /**
             * @var User $user
             */
            $user = Auth::user();

            // Créer l'enregistrement
            $multimedia = Multimedia::create([
                'culte_id' => $request->culte_id,
                'event_id' => $request->event_id,
                'intervention_id' => $request->intervention_id,
                'reunion_id' => $request->reunion_id,
                'titre' => $request->titre,
                'description' => $request->description,
                'legende' => $request->legende,
                'tags' => $request->tags,
                'type_media' => $typeMedia,
                'categorie' => $request->categorie,
                'nom_fichier_original' => $originalName,
                'nom_fichier_stockage' => $fileName,
                'chemin_fichier' => $path,
                'url_publique' => $urlPublique,
                'miniature' => $miniaturePath,
                'type_mime' => $mimeType,
                'extension' => $extension,
                'taille_fichier' => $fileSize,
                'hash_fichier' => hash_file('sha256', $file->path()),
                'largeur' => $metadata['width'] ?? null,
                'hauteur' => $metadata['height'] ?? null,
                'duree_secondes' => $metadata['duration'] ?? null,
                'bitrate' => $metadata['bitrate'] ?? null,
                'date_prise' => $request->date_prise,
                'lieu_prise' => $request->lieu_prise,
                'photographe' => $request->photographe,
                'appareil' => $request->appareil,
                'niveau_acces' => $request->niveau_acces,
                'usage_public' => $request->boolean('usage_public', true),
                'usage_site_web' => $request->boolean('usage_site_web', true),
                'usage_reseaux_sociaux' => $request->boolean('usage_reseaux_sociaux'),
                'usage_commercial' => $request->boolean('usage_commercial'),
                'restrictions_usage' => $request->restrictions_usage,
                'alt_text' => $request->alt_text,
                'titre_seo' => $request->titre_seo,
                'description_seo' => $request->description_seo,
                'qualite' => $request->qualite ?? 'standard',
                'contenu_sensible' => $request->boolean('contenu_sensible'),
                'avertissement' => $request->avertissement,
                'est_featured' => $request->boolean('est_featured') && $user->can('feature_media'),
                'telecharge_par' => $user->id,
                'cree_par' => $user->id,
                'service_stockage' => 'local',
                'statut_moderation' => $user->can('auto_approve_media') ? 'approuve' : 'en_attente',
                'est_visible' => $user->can('auto_approve_media')
            ]);

            $multimedia->load(['culte', 'event', 'intervention', 'reunion', 'uploadedBy']);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Média téléchargé avec succès',
                    'data' => $multimedia
                ], 201);
            }

            return redirect()->route('private.multimedia.show', $multimedia)
                           ->with('success', 'Média téléchargé avec succès');

        } catch (\Exception $e) {
            DB::rollback();

            // Supprimer le fichier en cas d'erreur
            if (isset($path)) {
                Storage::disk('public')->delete($path);
                if ($miniaturePath) {
                    Storage::disk('public')->delete($miniaturePath);
                }
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du téléchargement',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withError('Erreur lors du téléchargement: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Afficher un média spécifique
     */
    public function show(Request $request, string $id)
    {

        try {
            $multimedia = Multimedia::with([
                'culte', 'event', 'intervention', 'reunion',
                'uploadedBy', 'creator', 'moderator'
            ])->findOrFail($id);

            // Incrémenter les vues
            $multimedia->incrementerVues();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $multimedia
                ]);
            }

            return view('components.private.multimedia.show', compact('multimedia'));

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Média introuvable'
                ], 404);
            }

            abort(404, 'Média introuvable');
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Request $request, string $id)
    {
        try {
            $multimedia = Multimedia::with([
                'culte', 'event', 'intervention', 'reunion',
                'uploadedBy', 'creator'
            ])->findOrFail($id);

            /**
             * @var User $user
             */
            $user = Auth::user();

            // Vérifier les permissions d'édition
            if (!$user->can('update', $multimedia)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Permission de modification refusée'
                    ], 403);
                }
                abort(403, 'Permission refusée');
            }

            $cultes = Culte::select('id', 'titre', 'date_culte')->orderBy('date_culte', 'desc')->get();
            $events = Event::select('id', 'titre', 'date_debut')->orderBy('date_debut', 'desc')->get();
            $reunions = Reunion::select('id', 'titre', 'date_reunion')->orderBy('date_reunion', 'desc')->get();
            $interventions = Intervention::with(['culte', 'reunion', 'intervenant'])
                                       ->select('id', 'titre', 'culte_id', 'reunion_id', 'intervenant_id')
                                       ->orderBy('created_at', 'desc')
                                       ->take(50)
                                       ->get();

            $data = [
                'multimedia' => $multimedia,
                'cultes' => $cultes,
                'events' => $events,
                'reunions' => $reunions,
                'interventions' => $interventions,
                'types_media' => Multimedia::TYPES_MEDIA,
                'categories' => Multimedia::CATEGORIES,
                'niveaux_acces' => Multimedia::NIVEAUX_ACCES,
                'statuts_moderation' => Multimedia::STATUTS_MODERATION,
                'qualites' => Multimedia::QUALITES
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }

            return view('components.private.multimedia.edit', $data);

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Média introuvable'
                ], 404);
            }

            abort(404, 'Média introuvable');
        }
    }

    /**
     * Mettre à jour un média
     */
    public function update(Request $request, string $id)
    {
        try {
            $multimedia = Multimedia::findOrFail($id);

            /**
             * @var User $user
             */
            $user = Auth::user();

            // Vérifier les permissions
            if (!$user->can('update', $multimedia)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Permission de modification refusée'
                    ], 403);
                }
                abort(403, 'Permission refusée');
            }

            $validator = Validator::make($request->all(), [
                // Relations
                'culte_id' => 'nullable|uuid|exists:cultes,id',
                'event_id' => 'nullable|uuid|exists:events,id',
                'intervention_id' => 'nullable|uuid|exists:interventions,id',
                'reunion_id' => 'nullable|uuid|exists:reunions,id',

                // Informations de base
                'titre' => 'required|string|max:200',
                'description' => 'nullable|string',
                'legende' => 'nullable|string',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',

                // Catégorisation
                'categorie' => ['required', Rule::in(array_keys(Multimedia::CATEGORIES))],
                'qualite' => ['nullable', Rule::in(array_keys(Multimedia::QUALITES))],

                // Métadonnées
                'date_prise' => 'nullable|date|before_or_equal:today',
                'lieu_prise' => 'nullable|string|max:200',
                'photographe' => 'nullable|string|max:100',
                'appareil' => 'nullable|string|max:100',

                // Permissions et accès
                'niveau_acces' => ['required', Rule::in(array_keys(Multimedia::NIVEAUX_ACCES))],
                'usage_public' => 'boolean',
                'usage_site_web' => 'boolean',
                'usage_reseaux_sociaux' => 'boolean',
                'usage_commercial' => 'boolean',
                'restrictions_usage' => 'nullable|string',

                // SEO
                'alt_text' => 'nullable|string|max:255',
                'titre_seo' => 'nullable|string|max:200',
                'description_seo' => 'nullable|string',

                // Options
                'contenu_sensible' => 'boolean',
                'avertissement' => 'nullable|string',
                'est_featured' => 'boolean',
                'est_visible' => 'boolean'
            ]);

            // Validation personnalisée
            $validator->after(function ($validator) use ($request) {
                if (!$request->filled('culte_id') &&
                    !$request->filled('event_id') &&
                    !$request->filled('intervention_id') &&
                    !$request->filled('reunion_id')) {
                    $validator->errors()->add('evenement', 'Le média doit être associé à au moins un événement.');
                }
            });

            if ($validator->fails()) {
                dd($validator->errors());
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreurs de validation',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return back()->withErrors($validator)->withInput();
            }

            $updateData = $request->only([
                'culte_id', 'event_id', 'intervention_id', 'reunion_id',
                'titre', 'description', 'legende', 'tags', 'categorie',
                'date_prise', 'lieu_prise', 'photographe', 'appareil',
                'niveau_acces', 'usage_public', 'usage_site_web',
                'usage_reseaux_sociaux', 'usage_commercial', 'restrictions_usage',
                'alt_text', 'titre_seo', 'description_seo', 'qualite',
                'contenu_sensible', 'avertissement'
            ]);

            /**
             * @var User $user
             */
            $user = Auth::user();

            // Seuls les membres autorisés peuvent modifier ces champs
            if ($user->can('feature_media')) {
                $updateData['est_featured'] = $request->boolean('est_featured');
            }

            if ($user->can('moderate_media')) {
                $updateData['est_visible'] = $request->boolean('est_visible');
            }

            $updateData['modifie_par'] = Auth::id();

            $multimedia->update($updateData);
            $multimedia->load(['culte', 'event', 'intervention', 'reunion', 'uploadedBy']);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Média mis à jour avec succès',
                    'data' => $multimedia
                ]);
            }

            return redirect()->route('private.multimedia.show', $multimedia)
                           ->with('success', 'Média mis à jour avec succès');

        } catch (ModelNotFoundException $e) {
            dd($e->getMessage(), 12);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Média introuvable'
                ], 404);
            }

            abort(404, 'Média introuvable');

        } catch (\Exception $e) {
            dd($e->getMessage(), 2);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withError('Erreur lors de la mise à jour: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Supprimer un média
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $multimedia = Multimedia::findOrFail($id);

            /**
             * @var User $user
             */
            $user = Auth::user();

            // Vérifier les permissions
            if (!$user->can('delete', $multimedia)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Permission de suppression refusée'
                    ], 403);
                }
                return back()->withError('Permission refusée');
            }

            $multimedia->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Média supprimé avec succès'
                ]);
            }

            return redirect()->route('private.multimedia.index')
                           ->with('success', 'Média supprimé avec succès');

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Média introuvable'
                ], 404);
            }

            return back()->withError('Média introuvable');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->withError('Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Télécharger un média
     */
    public function download(Request $request, string $id)
    {
        try {
            $multimedia = Multimedia::findOrFail($id);

            // Vérifier les droits d'accès
            if (!$multimedia->estAccessiblePar(Auth::user())) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Accès non autorisé'
                    ], 403);
                }
                abort(403, 'Accès non autorisé');

            }

            // Incrémenter les téléchargements
            $multimedia->incrementerTelechargements();

            // Retourner le fichier
            if ($multimedia->service_stockage === 'local') {
                /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                $disk = Storage::disk('public');

                return $disk->download(
                    $multimedia->chemin_fichier,
                    $multimedia->nom_fichier_original
                );
            }



            // Pour d'autres services de stockage, rediriger vers l'URL
            return redirect($multimedia->url_publique);

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Média introuvable'
                ], 404);
            }

            abort(404, 'Média introuvable');
        }
    }

    /**
     * Approuver un média (modération)
     */
    public function approve(Request $request, string $id)
    {
        try {
            $multimedia = Multimedia::findOrFail($id);

            /**
             * @var User $user
             */
            $user = Auth::user();

            if (!$user->can('moderate_media')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Permission refusée'
                    ], 403);
                }
                return back()->withError('Permission refusée');
            }

            $multimedia->approuver(Auth::user(), $request->commentaire);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Média approuvé avec succès',
                    'data' => $multimedia->fresh()
                ]);
            }

            return back()->with('success', 'Média approuvé avec succès');

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Média introuvable'
                ], 404);
            }

            return back()->withError('Média introuvable');
        }
    }

    /**
     * Rejeter un média (modération)
     */
    public function reject(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'raison' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Raison du rejet requise',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()->withErrors($validator);
        }

        try {
            $multimedia = Multimedia::findOrFail($id);

            /**
             * @var User $user
             */
            $user = Auth::user();

            if (!$user->can('moderate_media')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Permission refusée'
                    ], 403);
                }
                return back()->withError('Permission refusée');
            }

            $multimedia->rejeter(Auth::user(), $request->raison);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Média rejeté avec succès',
                    'data' => $multimedia->fresh()
                ]);
            }

            return back()->with('success', 'Média rejeté avec succès');

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Média introuvable'
                ], 404);
            }

            return back()->withError('Média introuvable');
        }
    }

    /**
     * Basculer le statut featured d'un média
     */
    public function toggleFeatured(Request $request, string $id)
    {
        try {
            $multimedia = Multimedia::findOrFail($id);

            /**
             * @var User $user
             */
            $user = Auth::user();

            if (!$user->can('feature_media')) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Permission refusée'
                    ], 403);
                }
                return back()->withError('Permission refusée');
            }

            $multimedia->update([
                'est_featured' => !$multimedia->est_featured,
                'modifie_par' => Auth::id()
            ]);

            $message = $multimedia->est_featured ? 'Média mis en avant' : 'Média retiré de la mise en avant';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $multimedia->fresh()
                ]);
            }

            return back()->with('success', $message);

        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Média introuvable'
                ], 404);
            }

            return back()->withError('Média introuvable');
        }
    }

    /**
     * Galerie publique
     */
    public function galerie(Request $request)
    {
        $query = DB::table('galerie_publique')
                  ->orderBy('est_featured', 'desc')
                  ->orderBy('date_publication', 'desc')
                  ->orderBy('created_at', 'desc');

        // Filtres publics
        if ($request->filled('type_media')) {
            $query->where('type_media', $request->type_media);
        }

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('legende', 'like', "%{$search}%");
            });
        }

        $galerie = $query->paginate(24);

        $data = [
            'galerie' => $galerie,
            'types_media' => Multimedia::TYPES_MEDIA,
            'categories' => Multimedia::CATEGORIES,
            'currentFilters' => $request->only(['type_media', 'categorie', 'search'])
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('components.private.multimedia.galerie', $data);
    }

    /**
     * Statistiques des médias
     */
    public function statistiques(Request $request): View|JsonResponse
    {
        $stats = DB::table('statistiques_galerie')
                  ->orderBy('nombre_total', 'desc')
                  ->get();

        $statsGenerales = [
            'total_medias' => Multimedia::count(),
            'total_taille' => Multimedia::sum('taille_fichier'),
            'total_vues' => Multimedia::sum('nombre_vues'),
            'medias_featured' => Multimedia::where('est_featured', true)->count(),
            'en_attente_moderation' => Multimedia::where('statut_moderation', 'en_attente')->count(),
            'plus_vues' => Multimedia::orderBy('nombre_vues', 'desc')->first(),
            'plus_recents' => Multimedia::orderBy('created_at', 'desc')->take(5)->get()
        ];

        $data = [
            'statistiques' => $stats,
            'generales' => $statsGenerales
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        }

        return view('components.private.multimedia.statistiques', $data);
    }

    /**
     * Modération en lot
     */
    public function bulkModerate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'media_ids' => 'required|array|min:1',
            'media_ids.*' => 'uuid|exists:multimedia,id',
            'action' => 'required|in:approve,reject,delete',
            'commentaire' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Données invalides',
                'errors' => $validator->errors()
            ], 422);
        }

        /**
             * @var User $user
             */
            $user = Auth::user();

        if (!$user->can('moderate_media')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        DB::beginTransaction();

        try {
            $mediaIds = $request->media_ids;
            $action = $request->action;
            $commentaire = $request->commentaire;

            $multimedia = Multimedia::whereIn('id', $mediaIds)->get();

            foreach ($multimedia as $media) {
                switch ($action) {
                    case 'approve':
                        $media->approuver(Auth::user(), $commentaire);
                        break;
                    case 'reject':
                        $media->rejeter(Auth::user(), $commentaire ?? 'Rejeté en lot');
                        break;
                    case 'delete':
                        $media->delete();
                        break;
                }
            }

            DB::commit();

            $messages = [
                'approve' => 'Médias approuvés avec succès',
                'reject' => 'Médias rejetés avec succès',
                'delete' => 'Médias supprimés avec succès'
            ];

            return response()->json([
                'success' => true,
                'message' => $messages[$action],
                'processed' => count($multimedia)
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du traitement en lot',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Déterminer le type de média basé sur le MIME type
     */
    private function determineMediaType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }

        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }

        if (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }

        if (in_array($mimeType, ['application/pdf', 'text/plain', 'application/msword'])) {
            return 'document';
        }

        if (in_array($mimeType, ['application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'])) {
            return 'presentation';
        }

        if (in_array($mimeType, ['application/zip', 'application/x-rar-compressed'])) {
            return 'archive';
        }

        return 'document'; // Par défaut
    }

    /**
     * Extraire les métadonnées d'un fichier
     */
    private function extractMetadata(string $filePath, string $typeMedia): array
    {
        $metadata = [];

        if ($typeMedia === 'image') {
            if (function_exists('getimagesize')) {
                $imageInfo = getimagesize($filePath);
                if ($imageInfo) {
                    $metadata['width'] = $imageInfo[0];
                    $metadata['height'] = $imageInfo[1];
                }
            }
        }

        // Pour vidéos et audios, on pourrait utiliser FFmpeg
        // Ici on laisse un placeholder pour l'implémentation future

        return $metadata;
    }

    /**
     * Générer une miniature pour une image
     */
    private function generateThumbnail(string $originalPath, string $storagePath): ?string
    {
        // Placeholder pour la génération de miniatures
        // On pourrait utiliser Intervention Image ou GD
        return null;
    }
}
