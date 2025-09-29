<?php

namespace App\Http\Controllers\Private\Web;

use Illuminate\View\View;
use App\Models\Parametres;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ParametresController extends Controller
{
    /**
     * Afficher les paramètres de l'église
     *
     * @param Request $request
     * @return View|JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $parametres = Parametres::getInstance();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $parametres,
                    'message' => 'Paramètres récupérés avec succès'
                ]);
            }

            return view('components.private.parametres.index', compact('parametres'));

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des paramètres',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération des paramètres: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire d'édition des paramètres
     *
     * @param Request $request
     * @return View|JsonResponse
     */
    public function edit(Request $request)
    {
        try {
            $parametres = Parametres::getInstance();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $parametres,
                    'message' => 'Formulaire d\'édition récupéré avec succès'
                ]);
            }

            return view('components.private.parametres.edit', compact('parametres'));

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du chargement du formulaire',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors du chargement du formulaire: ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour les paramètres de l'église
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $validator = $this->validateParametres($request);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Erreurs de validation',
                        'errors' => $validator->errors()
                    ], 422);
                }

                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $validator->validated();



            // Gérer l'upload du logo
            if ($request->hasFile('logo')) {
                $data['logo'] = $this->handleFileUpload($request->file('logo'), 'logos');
            }

            // Gérer l'upload des images hero
            if ($request->hasFile('images_hero')) {
                $data['images_hero'] = $this->handleMultipleFileUpload($request->file('images_hero'), 'hero-images');
            }

            // Gérer les programmes (remplace horaires_cultes)
            if ($request->has('programmes')) {
                $data['programmes'] = $this->processProgrammes($request->input('programmes'));
            }

            $updated =  Parametres::updateParametres($data);
            $parametres = Parametres::getInstance();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $parametres,
                    'message' => 'Paramètres mis à jour avec succès'
                ]);
            }

            return redirect()
                ->route('private.parametres.index')
                ->with('success', 'Paramètres mis à jour avec succès');

        } catch (\Exception $e) {
            dd($e->getmessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Récupérer les informations complètes de l'église
     *
     * @param Request $request
     * @return View|JsonResponse
     */
    public function show(Request $request)
    {
        try {
            $parametres = Parametres::getInstance();
            $infosCompletes = $parametres->getInfosCompletes();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $infosCompletes,
                    'message' => 'Informations complètes récupérées avec succès'
                ]);
            }

            return view('components.private.parametres.show', compact('parametres', 'infosCompletes'));

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des informations',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération des informations: ' . $e->getMessage());
        }
    }

    /**
     * Récupérer uniquement les informations publiques (pour le front-end)
     *
     * @param Request $request
     * @return JsonResponse|View
     */
    public function public(Request $request)
    {
        try {
            $parametres = Parametres::getInstance();

            $publicData = [
                'nom_eglise' => $parametres->nom_eglise,
                'telephone_1' => $parametres->telephone_1,
                'telephone_2' => $parametres->telephone_2,
                'email_principal' => $parametres->email_principal,
                'adresse_complete' => $parametres->getAdresseComplete(),
                'logo_url' => $parametres->logo_url,
                'images_hero_urls' => $parametres->images_hero_urls,
                'verset_biblique' => $parametres->verset_biblique,
                'reference_verset' => $parametres->reference_verset,
                'mission_statement' => $parametres->mission_statement,
                'vision' => $parametres->vision,
                'description_eglise' => $parametres->description_eglise,
                'programmes' => $parametres->getProgrammesPublics(), // Remplace horaires_cultes
                'reseaux_sociaux' => [
                    'facebook' => $parametres->facebook_url,
                    'instagram' => $parametres->instagram_url,
                    'youtube' => $parametres->youtube_url,
                    'twitter' => $parametres->twitter_url,
                    'website' => $parametres->website_url,
                ]
            ];

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $publicData,
                    'message' => 'Informations publiques récupérées avec succès'
                ]);
            }

            return view('public.eglise', compact('publicData'));

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la récupération des informations publiques',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la récupération des informations: ' . $e->getMessage());
        }
    }

    // ============= GESTION DES PROGRAMMES =============

    /**
     * Récupérer tous les programmes
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProgrammes(Request $request)
    {
        try {
            $parametres = Parametres::getInstance();
            $programmes = $parametres->getProgrammes();

            return response()->json([
                'success' => true,
                'data' => $programmes,
                'message' => 'Programmes récupérés avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des programmes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer les programmes publics
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProgrammesPublics(Request $request)
    {
        try {
            $parametres = Parametres::getInstance();
            $programmes = $parametres->getProgrammesPublics();

            return response()->json([
                'success' => true,
                'data' => $programmes,
                'message' => 'Programmes publics récupérés avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des programmes publics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer un programme par son UUID
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function getProgramme(Request $request, $id)
    {
        try {
            $parametres = Parametres::getInstance();
            $programme = $parametres->getProgrammeById($id);

            if (!$programme) {
                return response()->json([
                    'success' => false,
                    'message' => 'Programme non trouvé'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $programme,
                'message' => 'Programme récupéré avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du programme',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ajouter un nouveau programme
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function ajouterProgramme(Request $request)
    {
        try {
            $validator = $this->validateProgramme($request);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $parametres = Parametres::getInstance();
            $nouvelId = $parametres->ajouterProgramme($validator->validated());

            $nouveauProgramme = $parametres->getProgrammeById($nouvelId);

            return response()->json([
                'success' => true,
                'data' => $nouveauProgramme,
                'message' => 'Programme ajouté avec succès'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du programme',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour un programme
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function mettreAJourProgramme(Request $request, $id)
    {
        try {
            $parametres = Parametres::getInstance();

            // Vérifier si le programme existe
            if (!$parametres->getProgrammeById($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Programme non trouvé'
                ], 404);
            }

            $validator = $this->validateProgramme($request, false);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $programmeModifie = $parametres->mettreAJourProgramme($id, $validator->validated());

            return response()->json([
                'success' => true,
                'data' => $programmeModifie,
                'message' => 'Programme mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du programme',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un programme
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function supprimerProgramme(Request $request, $id)
    {
        try {
            $parametres = Parametres::getInstance();

            // Vérifier si le programme existe
            if (!$parametres->getProgrammeById($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Programme non trouvé'
                ], 404);
            }

            $parametres->supprimerProgramme($id);

            return response()->json([
                'success' => true,
                'message' => 'Programme supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du programme',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Réorganiser l'ordre des programmes
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reordonnerProgrammes(Request $request)
    {
        try {
            $request->validate([
                'ordre' => 'required|array',
                'ordre.*' => 'required|string|uuid'
            ]);

            $parametres = Parametres::getInstance();
            $programmesReordonnes = $parametres->reordonnerProgrammes($request->input('ordre'));

            return response()->json([
                'success' => true,
                'data' => $programmesReordonnes,
                'message' => 'Programmes réordonnés avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la réorganisation des programmes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour uniquement le logo
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function updateLogo(Request $request)
    {
        try {
            $request->validate([
                'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            $logoPath = $this->handleFileUpload($request->file('logo'), 'logos');

            $parametres = Parametres::getInstance();

            // Supprimer l'ancien logo
            if ($parametres->logo && Storage::disk('public')->exists($parametres->logo)) {
                Storage::disk('public')->delete($parametres->logo);
            }

            $parametres->update(['logo' => $logoPath]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'logo' => $logoPath,
                        'logo_url' => $parametres->logo_url
                    ],
                    'message' => 'Logo mis à jour avec succès'
                ]);
            }

            return back()->with('success', 'Logo mis à jour avec succès');

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du logo',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erreur lors de la mise à jour du logo: ' . $e->getMessage());
        }
    }

    // ============= MÉTHODES PRIVÉES =============

    /**
     * Valider les données des paramètres
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateParametres(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom_eglise' => 'required|string|max:255',
            'telephone_1' => 'required|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'email_principal' => 'required|email|max:255',
            'email_secondaire' => 'nullable|email|max:255',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:255',
            'commune' => 'nullable|string|max:255',
            'pays' => 'required|string|max:255',
            'code_postal' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images_hero.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'verset_biblique' => 'nullable|string',
            'reference_verset' => 'nullable|string|max:255',
            'mission_statement' => 'nullable|string',
            'vision' => 'nullable|string',
            'description_eglise' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'website_url' => 'nullable|url',
            'date_fondation' => 'nullable|date',
            'nombre_membres' => 'nullable|integer|min:0',
            'histoire_eglise' => 'nullable|string',
            'devise' => 'nullable|string|in:EUR,USD,XOF,XAF',
            'langue' => 'nullable|string|in:fr,en,es',
            'fuseau_horaire' => 'nullable|string',

            // Validation des programmes
            'programmes' => 'required|array',
            'programmes.*.id' => 'nullable|string|uuid',
            'programmes.*.titre' => 'required_with:programmes.*|string|max:255',
            'programmes.*.description' => 'required|string',
            'programmes.*.icone' => 'required|string|max:255',
            'programmes.*.type_horaire' => 'required|string|in:regulier,sur_rendez_vous,permanent,ponctuel',
            'programmes.*.jour' => 'nullable|string|max:255',
            'programmes.*.heure_debut' => 'nullable|date_format:H:i',
            'programmes.*.heure_fin' => 'nullable|date_format:H:i|after:programmes.*.heure_debut',
            'programmes.*.horaire_texte' => 'required|string|max:255',
            'programmes.*.est_public' => 'sometimes|boolean',
            'programmes.*.est_actif' => 'sometimes|boolean',
            'programmes.*.ordre' => 'nullable|integer|min:1',
        ]);

        // Si non défini => mettre false
        $data = $validator->validated();
        foreach ($data['programmes'] as &$programme) {
            $programme['est_public'] = $programme['est_public'] ?? 0;
            $programme['est_actif'] = $programme['est_actif'] ?? 0;
        }

        $validator->setData(array_merge($validator->getData(), ['programmes' => $data['programmes']]));

        return $validator;
    }

    /**
     * Valider les données d'un programme
     *
     * @param Request $request
     * @param bool $required
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateProgramme(Request $request, $required = true)
    {
        $rules = [
            'titre' => ($required ? 'required' : 'sometimes') . '|string|max:255',
            'description' => ($required ? 'required' : 'sometimes') . '|string',
            'icone' => ($required ? 'required' : 'sometimes') . '|string|max:255',
            'type_horaire' => ($required ? 'required' : 'sometimes') . '|string|in:regulier,sur_rendez_vous,permanent,ponctuel',
            'jour' => 'nullable|string|max:255',
            'heure_debut' => 'nullable|date_format:H:i',
            'heure_fin' => 'nullable|date_format:H:i|after:heure_debut',
            'horaire_texte' => 'nullable|string|max:255',
            'est_public' => 'sometimes|boolean',
            'est_actif' => 'sometimes|boolean',
            'ordre' => 'sometimes|integer|min:1',
        ];

        return Validator::make($request->all(), $rules);
    }

    /**
     * Gérer l'upload d'un fichier
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $folder
     * @return string
     */
    private function handleFileUpload($file, $folder)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($folder, $filename, 'public');
    }

    /**
     * Gérer l'upload de plusieurs fichiers
     *
     * @param array $files
     * @param string $folder
     * @return array
     */
    private function handleMultipleFileUpload($files, $folder)
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            $uploadedFiles[] = $this->handleFileUpload($file, $folder);
        }

        return $uploadedFiles;
    }

    /**
     * Traiter les programmes
     *
     * @param mixed $programmes
     * @return array
     */
    private function processProgrammes($programmes)
    {
        if (is_string($programmes)) {
            $programmes = json_decode($programmes, true) ?: [];
        }

        if (!is_array($programmes)) {
            return [];
        }

        $programmesTraites = [];

        foreach ($programmes as $index => $programme) {
            // Ignorer les programmes vides (sans titre)
            if (empty($programme['titre'])) {
                continue;
            }

            // Générer un UUID pour les nouveaux programmes (sans ID ou ID vide)
            if (empty($programme['id'])) {
                $programme['id'] = \Illuminate\Support\Str::uuid()->toString();
            }

            // Nettoyer et valider les données
            $programmePropre = [
                'id' => $programme['id'],
                'titre' => $programme['titre'],
                'description' => $programme['description'] ?? '',
                'icone' => $programme['icone'] ?? 'fas fa-calendar',
                'type_horaire' => $programme['type_horaire'] ?? 'regulier',
                'jour' => $programme['jour'] ?? null,
                'heure_debut' => $programme['heure_debut'] ?? null,
                'heure_fin' => $programme['heure_fin'] ?? null,
                'horaire_texte' => $programme['horaire_texte'] ?? '',
                'est_public' => isset($programme['est_public']) ? $programme['est_public'] : false,
                'est_actif' => isset($programme['est_actif']) ? $programme['est_actif'] : false,
                'ordre' => isset($programme['ordre']) ? (int) $programme['ordre'] : ($index + 1),
            ];

            // Auto-générer l'horaire_texte si vide mais qu'on a les données
            if (empty($programmePropre['horaire_texte']) && $programmePropre['jour'] && $programmePropre['heure_debut']) {
                $horaire = $programmePropre['jour'] . ' : ' . $programmePropre['heure_debut'];
                if ($programmePropre['heure_fin']) {
                    $horaire .= ' - ' . $programmePropre['heure_fin'];
                }
                $programmePropre['horaire_texte'] = $horaire;
            }

            // Gestion des types d'horaires spéciaux
            if ($programmePropre['type_horaire'] === 'sur_rendez_vous' && empty($programmePropre['horaire_texte'])) {
                $programmePropre['horaire_texte'] = 'Sur rendez-vous';
            } elseif ($programmePropre['type_horaire'] === 'permanent' && empty($programmePropre['horaire_texte'])) {
                $programmePropre['horaire_texte'] = 'Actions permanentes';
            }

            $programmesTraites[] = $programmePropre;
        }

        // Réorganiser les ordres pour être consécutifs
        usort($programmesTraites, function ($a, $b) {
            return $a['ordre'] <=> $b['ordre'];
        });

        foreach ($programmesTraites as $index => &$programme) {
            $programme['ordre'] = $index + 1;
        }

        return $programmesTraites;
    }
}
