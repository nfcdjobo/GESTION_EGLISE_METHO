<?php

namespace App\Http\Controllers\Private\Web;

use App\Models\TypeReunion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TypeReunionController extends Controller
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
     * Liste des types de réunions
     */
    public function index(Request $request)
    {
        try {
            $query = TypeReunion::with(['responsableType:id,nom,prenom,email']);

            // Filtres
            if ($request->filled('categorie')) {
                $query->parCategorie($request->categorie);
            }

            if ($request->filled('niveau_acces')) {
                $query->parNiveauAcces($request->niveau_acces);
            }

            if ($request->filled('actif')) {
                if ($request->boolean('actif')) {
                    $query->actif();
                }
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nom', 'ILIKE', "%{$search}%")
                      ->orWhere('description', 'ILIKE', "%{$search}%")
                      ->orWhere('code', 'ILIKE', "%{$search}%");
                });
            }

            // Tri
            $sortBy = $request->get('sort_by', 'ordre_affichage');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            if ($request->boolean('paginate', true)) {
                $perPage = min($request->get('per_page', 10), 100);
                $types = $query->paginate($perPage);
            } else {
                $types = $query->get();
            }

            if ($this->expectsJson($request)) {
                return response()->json($types);
            }

            // Données pour la vue
            $categories = $this->getCategoriesArray();
            $niveauxAcces = $this->getNiveauxAccesArray();

            return view('components.private.typesreunions.index', compact('types', 'categories', 'niveauxAcces'));

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Erreur lors de la récupération des types de réunions',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération des types de réunions: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(Request $request)
    {
        if ($this->expectsJson($request)) {
            return response()->json([

                'categories' => $this->getCategoriesArray(),
                'niveaux_acces' => $this->getNiveauxAccesArray(),
                'frequences' => $this->getFrequencesArray(),
                'durees_conservation' => $this->getDureesConservationArray()
            ]);
        }

        $categories = $this->getCategoriesArray();
        $niveauxAcces = $this->getNiveauxAccesArray();
        $frequences = $this->getFrequencesArray();

        return view('components.private.typesreunions.create', compact('categories', 'niveauxAcces', 'frequences'));
    }

    /**
     * Créer un nouveau type de réunion
     */
    public function store(Request $request)
    {
        $validator = $this->validateTypeReunion($request);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
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

            $typeReunion = TypeReunion::create(array_merge(
                $validator->validated(),
                ['cree_par' => auth()->id()]
            ));

            DB::commit();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Type de réunion créé avec succès',
                    'data' => $typeReunion->load('responsableType')
                ], 201);
            }

            return redirect()->route('private.types-reunions.show', $typeReunion)
                ->with('success', 'Type de réunion créé avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la création du type de réunion',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Afficher un type de réunion spécifique
     */
    public function show(Request $request, TypeReunion $typeReunion)
    {
        try {
            $typeReunion->load([
                'responsableType:id,nom,prenom,email',
                'createurType:id,nom,prenom',
                'modificateur:id,nom,prenom'
            ]);

            // Statistiques d'utilisation
            $stats = [
                'nombre_reunions_totales' => $typeReunion->reunions()->count(),
                'nombre_reunions_a_venir' => $typeReunion->reunions()->aVenir()->count(),
                'dernier_mois' => $typeReunion->reunions()
                    ->where('date_reunion', '>=', now()->subMonth())
                    ->count(),
                'moyenne_participants' => $typeReunion->reunions()
                    ->whereNotNull('nombre_participants_reel')
                    ->avg('nombre_participants_reel'),
                'statut_utilisation' => $typeReunion->getStatutUtilisation()
            ];

            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $typeReunion,
                    'statistiques' => $stats
                ]);
            }

            return view('components.private.typesreunions.show', compact('typeReunion', 'stats'));

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Type de réunion introuvable',
                    'error' => $e->getMessage()
                ], 404);
            }

            return back()->with('error', 'Type de réunion introuvable');
        }
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Request $request, TypeReunion $typeReunion)
    {
        if ($this->expectsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => $typeReunion->load('responsableType'),
                'categories' => $this->getCategoriesArray(),
                'niveaux_acces' => $this->getNiveauxAccesArray(),
                'frequences' => $this->getFrequencesArray()
            ]);
        }

        $categories = $this->getCategoriesArray();
        $niveauxAcces = $this->getNiveauxAccesArray();
        $frequences = $this->getFrequencesArray();

        return view('components.private.typesreunions.edit', compact('typeReunion', 'categories', 'niveauxAcces', 'frequences'));
    }

    /**
     * Mettre à jour un type de réunion
     */
    public function update(Request $request, TypeReunion $typeReunion)
    {
        $validator = $this->validateTypeReunion($request, $typeReunion);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
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

            $typeReunion->update(array_merge(
                $validator->validated(),
                ['modifie_par' => auth()->id()]
            ));

            DB::commit();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Type de réunion mis à jour avec succès',
                    'data' => $typeReunion->load('responsableType')
                ]);
            }

            return redirect()->route('private.types-reunions.show', $typeReunion)
                ->with('success', 'Type de réunion mis à jour avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du type de réunion',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un type de réunion
     */
    public function destroy(Request $request, TypeReunion $typeReunion)
    {
        try {
            // Vérifier s'il y a des réunions liées
            $nombreReunions = $typeReunion->reunions()->count();

            if ($nombreReunions > 0) {
                if ($this->expectsJson($request)) {
                    return response()->json([
                        'message' => "Impossible de supprimer ce type de réunion car il est utilisé par {$nombreReunions} réunion(s). Archivez-le plutôt."
                    ], 400);
                }

                return back()->with('error', "Impossible de supprimer ce type de réunion car il est utilisé par {$nombreReunions} réunion(s). Archivez-le plutôt.");
            }

            $typeReunion->delete();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'message' => 'Type de réunion supprimé avec succès'
                ]);
            }

            return redirect()->route('private.types-reunions.index')
                ->with('success', 'Type de réunion supprimé avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du type de réunion',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * Archiver un type de réunion
     */
    public function archiver(Request $request, TypeReunion $typeReunion)
    {
        try {
            $typeReunion->update([
                'est_archive' => true,
                'modifie_par' => auth()->id()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type de réunion archivé avec succès',
                    'data' => $typeReunion->fresh()
                ]);
            }

            return back()->with('success', 'Type de réunion archivé avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'archivage',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'archivage: ' . $e->getMessage());
        }
    }

    /**
     * Restaurer un type de réunion archivé
     */
    public function restaurer(Request $request, TypeReunion $typeReunion)
    {
        try {
            $typeReunion->update([
                'est_archive' => false,
                'modifie_par' => auth()->id()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type de réunion restauré avec succès',
                    'data' => $typeReunion->fresh()
                ]);
            }

            return back()->with('success', 'Type de réunion restauré avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la restauration',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la restauration: ' . $e->getMessage());
        }
    }

    /**
     * Activer un type de réunion
     */
    public function activer(Request $request, TypeReunion $typeReunion)
    {
        try {
            $typeReunion->update([
                'actif' => true,
                'modifie_par' => auth()->id()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type de réunion activé avec succès',
                    'data' => $typeReunion->fresh()
                ]);
            }

            return back()->with('success', 'Type de réunion activé avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'activation',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'activation: ' . $e->getMessage());
        }
    }

    /**
     * Désactiver un type de réunion
     */
    public function desactiver(Request $request, TypeReunion $typeReunion)
    {
        try {
            $typeReunion->update([
                'actif' => false,
                'modifie_par' => auth()->id()
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type de réunion désactivé avec succès',
                    'data' => $typeReunion->fresh()
                ]);
            }

            return back()->with('success', 'Type de réunion désactivé avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la désactivation',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la désactivation: ' . $e->getMessage());
        }
    }

    /**
     * Dupliquer un type de réunion
     */
    public function dupliquer(Request $request, TypeReunion $typeReunion)
    {
        try {
            DB::beginTransaction();

            $nouveauType = $typeReunion->replicate();
            $nouveauType->nom = $typeReunion->nom . ' (Copie)';
            $nouveauType->code = $typeReunion->code . '_copie_' . time();
            $nouveauType->cree_par = auth()->id();
            $nouveauType->modifie_par = null;
            $nouveauType->nombre_utilisations = 0;
            $nouveauType->derniere_utilisation = null;
            $nouveauType->save();

            DB::commit();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type de réunion dupliqué avec succès',
                    'data' => $nouveauType
                ], 201);
            }

            return redirect()
                ->route('private.types-reunions.show', $nouveauType)
                ->with('success', 'Type de réunion dupliqué avec succès');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la duplication',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la duplication: ' . $e->getMessage());
        }
    }

    /**
     * Types de réunions publics
     */
    public function typesPublics(Request $request)
    {
        try {
            $types = TypeReunion::public()->get();

                return response()->json([
                    'success' => true,
                    'data' => $types
                ]);

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des types publics',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération des types publics: ' . $e->getMessage());
        }
    }

    /**
     * Catégories disponibles
     */
    public function categoriesDisponibles(Request $request)
    {
        $categories = $this->getCategoriesArray();

            return response()->json($categories);


    }

    /**
     * Statistiques d'utilisation
     */
    public function statistiquesUtilisation(Request $request)
    {
        try {
            $stats = TypeReunion::selectRaw('
                    categorie,
                    COUNT(*) as nombre_types,
                    SUM(nombre_utilisations) as utilisations_totales,
                    AVG(nombre_utilisations) as moyenne_utilisation
                ')
                ->groupBy('categorie')
                ->get();

            $typesPopulaires = TypeReunion::orderBy('nombre_utilisations', 'desc')
                ->limit(10)
                ->get(['id', 'nom', 'nombre_utilisations', 'derniere_utilisation']);

            $typesInutilises = TypeReunion::where('nombre_utilisations', 0)
                ->orWhere('derniere_utilisation', '<', now()->subMonths(6))
                ->count();

            $statistiques = [
                'par_categorie' => $stats,
                'plus_populaires' => $typesPopulaires,
                'non_utilises' => $typesInutilises,
                'total_types' => TypeReunion::count(),
                'types_actifs' => TypeReunion::actif()->count(),
                'types_archives' => TypeReunion::where('est_archive', true)->count()
            ];

            if ($this->expectsJson($request)) {
                return response()->json($statistiques);
            }

            return view('components.private.typesreunions.statistiques', compact('statistiques'));

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des statistiques',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération des statistiques: ' . $e->getMessage());
        }
    }

    /**
     * Options et paramètres
     */
    public function options(Request $request)
    {
        $options = [
            'categories' => $this->getCategoriesArray(),
            'niveaux_acces' => $this->getNiveauxAccesArray(),
            'frequences' => $this->getFrequencesArray(),
            'priorites' => range(1, 10)
        ];

            return response()->json($options);


    }

    /**
     * Upload d'icône
     */
    public function uploadIcone(Request $request, TypeReunion $typeReunion)
    {
        $validator = Validator::make($request->all(), [
            'icone' => 'required|file|mimes:jpg,jpeg,png,gif,svg|max:2048'
        ]);

        if ($validator->fails()) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Fichier invalide',
                    'errors' => $validator->errors()
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->with('error', 'Fichier d\'icône invalide');
        }

        try {
            /** @var UploadedFile $file */
            $file = $request->file('icone');

            // Supprimer l'ancienne icône si elle existe
            if ($typeReunion->icone && Storage::disk('public')->exists($typeReunion->icone)) {
                Storage::disk('public')->delete($typeReunion->icone);
            }

            // Stocker la nouvelle icône
            $path = $file->store('types-reunions/icones', 'public');

            $typeReunion->update([
                'icone' => $path,
                'modifie_par' => auth()->id()
            ]);

            if ($this->expectsJson($request)) {
                /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
                $disk = Storage::disk('public');

                $url = $disk->url($path);
                return response()->json([
                    'success' => true,
                    'message' => 'Icône uploadée avec succès',
                    'data' => [
                        'icone' => $path,
                        'url' => $url
                    ]
                ]);
            }

            return back()->with('success', 'Icône uploadée avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'upload',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de l\'upload: ' . $e->getMessage());
        }
    }

    /**
     * Restaurer un type de réunion supprimé
     */
    public function restore(Request $request, string $id)
    {
        try {
            $typeReunion = TypeReunion::withTrashed()->findOrFail($id);
            $typeReunion->restore();

            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Type de réunion restauré avec succès',
                    'data' => $typeReunion->fresh()
                ]);
            }

            return redirect()
                ->route('private.types-reunions.show', $typeReunion)
                ->with('success', 'Type de réunion restauré avec succès');

        } catch (\Exception $e) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la restauration',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la restauration: ' . $e->getMessage());
        }
    }

    /**
     * Validation des données du type de réunion
     */
    private function validateTypeReunion(Request $request, TypeReunion $typeReunion = null): \Illuminate\Validation\Validator
    {
        $rules = [
            'nom' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:type_reunions,code' . ($typeReunion ? ",{$typeReunion->id}" : ''),
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:100',
            'couleur' => 'nullable|string|regex:/^#([A-Fa-f0-9]{6})$/',
            'categorie' => 'required|in:spirituel,administratif,formation,social,ministeriel,jeunesse,femmes,hommes,enfants,special',
            'niveau_acces' => 'required|in:public,membres,leadership,invite,prive',
            'frequence_type' => 'required|in:unique,hebdomadaire,bimensuel,mensuel,trimestriel,semestriel,annuel,irregulier',
            'duree_standard' => 'nullable|date_format:H:i',
            'duree_min' => 'nullable|date_format:H:i',
            'duree_max' => 'nullable|date_format:H:i',
            'limite_participants' => 'nullable|integer|min:1',
            'age_minimum' => 'nullable|integer|min:0|max:99',
            'frais_standard' => 'nullable|numeric|min:0',
            'delai_annonce_jours' => 'nullable|integer|min:0',
            'equipements_requis' => 'nullable|array',
            'roles_requis' => 'nullable|array',
            'modele_ordre_service' => 'nullable|array',
            'criteres_evaluation' => 'nullable|array',
            'metriques_importantes' => 'nullable|array',
            'ordre_affichage' => 'integer|min:0',
            'priorite' => 'integer|min:1|max:10',
            'responsable_type_id' => 'nullable|exists:users,id',

            // Champs booléens
            'necessite_preparation' => 'boolean',
            'necessite_inscription' => 'boolean',
            'a_limite_participants' => 'boolean',
            'permet_enfants' => 'boolean',
            'inclut_louange' => 'boolean',
            'inclut_message' => 'boolean',
            'inclut_priere' => 'boolean',
            'inclut_communion' => 'boolean',
            'permet_temoignages' => 'boolean',
            'collecte_offrandes' => 'boolean',
            'a_frais_participation' => 'boolean',
            'permet_enregistrement' => 'boolean',
            'permet_diffusion_live' => 'boolean',
            'necessite_promotion' => 'boolean',
            'necessite_evaluation' => 'boolean',
            'necessite_rapport' => 'boolean',
            'compte_conversions' => 'boolean',
            'compte_baptemes' => 'boolean',
            'compte_nouveaux' => 'boolean',
            'afficher_calendrier_public' => 'boolean',
            'afficher_site_web' => 'boolean',
            'actif' => 'boolean',
        ];

        return Validator::make($request->all(), $rules);
    }

    /**
     * Tableau des catégories disponibles
     */
    private function getCategoriesArray(): array
    {
        return [
            'spirituel' => 'Réunions spirituelles',
            'administratif' => 'Réunions administratives',
            'formation' => 'Formation et enseignement',
            'social' => 'Événements sociaux',
            'ministeriel' => 'Réunions ministérielles',
            'jeunesse' => 'Activités jeunesse',
            'femmes' => 'Réunions femmes',
            'hommes' => 'Réunions hommes',
            'enfants' => 'Activités enfants',
            'special' => 'Événements spéciaux'
        ];
    }

    /**
     * Tableau des niveaux d'accès disponibles
     */
    private function getNiveauxAccesArray(): array
    {
        return [
            'public' => 'Ouvert à tous',
            'membres' => 'Réservé aux membres',
            'leadership' => 'Réservé au leadership',
            'invite' => 'Sur invitation uniquement',
            'prive' => 'Privé/fermé'
        ];
    }

    /**
     * Tableau des fréquences disponibles
     */
    private function getFrequencesArray(): array
    {
        return [
            'unique' => 'Événement unique',
            'hebdomadaire' => 'Chaque semaine',
            'bimensuel' => 'Toutes les 2 semaines',
            'mensuel' => 'Chaque mois',
            'trimestriel' => 'Chaque trimestre',
            'semestriel' => 'Chaque semestre',
            'annuel' => 'Chaque année',
            'irregulier' => 'Fréquence irrégulière'
        ];
    }

    /**
     * Tableau des durées de conservation
     */
    private function getDureesConservationArray(): array
    {
        return [
            '1_an' => '1 an',
            '3_ans' => '3 ans',
            '5_ans' => '5 ans',
            '10_ans' => '10 ans',
            'permanent' => 'Permanent'
        ];
    }
}
