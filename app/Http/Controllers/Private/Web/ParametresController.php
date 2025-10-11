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
    /**
 * Méthode update() complète et adaptée dans ParametresController.php
 */
public function update(Request $request)
{
    // dd($request->all());
    try {
        $validator = $this->validateParametres($request);

        if ($validator->fails()) {
            dd($validator->errors());
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
            $parametres = Parametres::getInstance();

            // Supprimer l'ancien logo
            if ($parametres->logo && Storage::disk('public')->exists($parametres->logo)) {
                Storage::disk('public')->delete($parametres->logo);
            }

            $data['logo'] = $this->handleFileUpload($request->file('logo'), 'logos');
        }

        // Gérer les images hero avec la structure complète (titre, description, url, active, ordre)
        if ($request->has('images_hero_data')) {
            $data['images_hero'] = $this->processImagesHeroFromForm($request);
        }

        // Gérer les programmes
        if ($request->has('programmes')) {
            $data['programmes'] = $this->processProgrammes($request->input('programmes'));
        }

        $updated = Parametres::updateParametres($data);
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
        \Log::error('Erreur mise à jour paramètres: ' . $e->getMessage());
dd($e->getMessage());
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
     * Récupérer toutes les images hero
     */
    public function getImagesHero(Request $request)
    {
        try {
            $parametres = Parametres::getInstance();
            $images = $parametres->getImagesHero();

            return response()->json([
                'success' => true,
                'data' => $images,
                'message' => 'Images hero récupérées avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des images hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }


     /**
     * Ajouter une nouvelle image hero
     */
    public function ajouterImageHero(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titre' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'active' => 'sometimes|boolean',
                'ordre' => 'sometimes|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Upload de l'image
            $imagePath = $this->handleFileUpload($request->file('image'), 'hero-images');

            $imageData = [
                'titre' => $request->titre,
                'url' => $imagePath,
                'active' => $request->input('active', true),
                'ordre' => $request->input('ordre'),
            ];

            $parametres = Parametres::getInstance();
            $nouvelId = $parametres->ajouterImageHero($imageData);
            $nouvelleImage = $parametres->getImageHeroById($nouvelId);

            return response()->json([
                'success' => true,
                'data' => $nouvelleImage,
                'message' => 'Image hero ajoutée avec succès'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de l\'image hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }


     /**
     * Mettre à jour une image hero
     */
    public function mettreAJourImageHero(Request $request, $id)
    {
        try {
            $parametres = Parametres::getInstance();

            // Vérifier si l'image existe
            if (!$parametres->getImageHeroById($id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image hero non trouvée'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'titre' => 'sometimes|string|max:255',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
                'active' => 'sometimes|boolean',
                'ordre' => 'sometimes|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreurs de validation',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $validator->validated();

            // Si une nouvelle image est uploadée
            if ($request->hasFile('image')) {
                $ancienneImage = $parametres->getImageHeroById($id);

                // Supprimer l'ancienne image
                if ($ancienneImage && isset($ancienneImage['url']) && Storage::disk('public')->exists($ancienneImage['url'])) {
                    Storage::disk('public')->delete($ancienneImage['url']);
                }

                $updateData['url'] = $this->handleFileUpload($request->file('image'), 'hero-images');
            }

            $imageModifiee = $parametres->mettreAJourImageHero($id, $updateData);

            return response()->json([
                'success' => true,
                'data' => $imageModifiee,
                'message' => 'Image hero mise à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'image hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Supprimer une image hero
     */
    public function supprimerImageHero(Request $request, $id)
    {
        try {
            $parametres = Parametres::getInstance();

            // Vérifier si l'image existe
            $image = $parametres->getImageHeroById($id);
            if (!$image) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image hero non trouvée'
                ], 404);
            }

            // Supprimer le fichier physique
            if (isset($image['url']) && Storage::disk('public')->exists($image['url'])) {
                Storage::disk('public')->delete($image['url']);
            }

            $parametres->supprimerImageHero($id);

            return response()->json([
                'success' => true,
                'message' => 'Image hero supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'image hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }



      /**
     * Réorganiser l'ordre des images hero
     */
    public function reordonnerImagesHero(Request $request)
    {
        try {
            $request->validate([
                'ordre' => 'required|array',
                'ordre.*' => 'required|string|uuid'
            ]);

            $parametres = Parametres::getInstance();
            $imagesReordonnees = $parametres->reordonnerImagesHero($request->input('ordre'));

            return response()->json([
                'success' => true,
                'data' => $imagesReordonnees,
                'message' => 'Images hero réordonnées avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la réorganisation des images hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
 * Traiter les données des images hero depuis le formulaire
 * Méthode complète à ajouter dans ParametresController.php
 */
private function processImagesHeroFromForm(Request $request)
{
    $parametres = Parametres::getInstance();
    $imagesData = $request->input('images_hero_data', []);
    $imagesFiles = $request->file('images_hero_files', []);

    $processedImages = [];

    foreach ($imagesData as $index => $imageData) {
        // Vérifier si le titre existe (requis)
        if (empty($imageData['titre'])) {
            continue;
        }

        $imageInfo = [
            'id' => $imageData['id'] ?? \Illuminate\Support\Str::uuid()->toString(),
            'titre' => $imageData['titre'],
            'description' => $imageData['description'] ?? '', // Description pour overlay
            'active' => isset($imageData['active']) && $imageData['active'] == '1',
            'ordre' => isset($imageData['ordre']) ? (int)$imageData['ordre'] : ($index + 1),
        ];

        // Gérer l'URL de l'image
        if (isset($imagesFiles[$index]) && $imagesFiles[$index]->isValid()) {
            // Nouvelle image uploadée
            $imagePath = $this->handleFileUpload($imagesFiles[$index], 'hero-images');

            // Supprimer l'ancienne image si elle existe
            if (!empty($imageData['url']) && Storage::disk('public')->exists($imageData['url'])) {
                Storage::disk('public')->delete($imageData['url']);
            }

            $imageInfo['url'] = $imagePath;
        } elseif (!empty($imageData['url'])) {
            // Conserver l'URL existante
            $imageInfo['url'] = $imageData['url'];
        } else {
            // Pas d'image, ignorer cette entrée
            continue;
        }

        $processedImages[] = $imageInfo;
    }

    // Trier par ordre
    usort($processedImages, function($a, $b) {
        return $a['ordre'] <=> $b['ordre'];
    });

    // Réassigner les ordres pour qu'ils soient consécutifs
    foreach ($processedImages as $index => &$image) {
        $image['ordre'] = $index + 1;
    }

    return $processedImages;
}



     /**
     * Récupérer une image hero par son ID
     */
    public function getImageHero(Request $request, $id)
    {
        try {
            $parametres = Parametres::getInstance();
            $image = $parametres->getImageHeroById($id);

            if (!$image) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image hero non trouvée'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $image,
                'message' => 'Image hero récupérée avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de l\'image hero',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Traiter l'upload des images hero avec structure JSON
     */
    private function processImagesHeroUpload(Request $request)
    {
        $parametres = Parametres::getInstance();
        $imagesExistantes = $parametres->getImagesHero();

        // Si on veut conserver les anciennes images et ajouter les nouvelles
        if ($request->has('keep_existing') && $request->input('keep_existing')) {
            $images = $imagesExistantes;
        } else {
            $images = [];
        }

        $files = $request->file('images_hero');
        $titres = $request->input('images_hero_titres', []);
        $actives = $request->input('images_hero_actives', []);

        foreach ($files as $index => $file) {
            $imagePath = $this->handleFileUpload($file, 'hero-images');

            $images[] = [
                'id' => \Illuminate\Support\Str::uuid()->toString(),
                'titre' => $titres[$index] ?? 'Image ' . ($index + 1),
                'url' => $imagePath,
                'active' => isset($actives[$index]) ? (bool)$actives[$index] : true,
                'ordre' => count($images) + 1,
            ];
        }

        return $images;
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
    // dd($request->vision);
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

        // Validation des images hero pour slider (avec description)
        'images_hero_data' => 'nullable|array',
        'images_hero_data.*.titre' => 'required_with:images_hero_data.*|string|max:255',
        'images_hero_data.*.description' => 'nullable|string|max:500',
        'images_hero_data.*.active' => 'nullable|in:0,1',
        'images_hero_data.*.ordre' => 'nullable|integer|min:1',
        'images_hero_data.*.id' => 'nullable|uuid',
        'images_hero_data.*.url' => 'nullable|string',

        // Validation des fichiers images hero (format optimisé pour slider)
        'images_hero_files.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120|dimensions:min_width=1280,min_height=720',

        'verset_biblique' => 'nullable|string',
        'reference_verset' => 'nullable|string|max:255',
        'mission_statement' => 'nullable|string',
        'vision' => 'nullable|string|max:100',
        'description_eglise' => 'nullable|string|max:50',
        'facebook_url' => 'nullable|url',
        'instagram_url' => 'nullable|url',
        'youtube_url' => 'nullable|url',
        'twitter_url' => 'nullable|url',
        'website_url' => 'nullable|url',
        'date_fondation' => 'nullable|date',
        'nombre_membres' => 'nullable|integer|min:0',
        'histoire_eglise' => 'nullable|string',
        'devise' => 'nullable|string|in:EUR,USD,XOF,XAF,FCFA',
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
    ], [
        // Messages personnalisés pour images hero
        'images_hero_data.*.titre.required_with' => 'Le titre du slide est obligatoire',
        'images_hero_data.*.description.max' => 'La description ne doit pas dépasser 500 caractères',
        'images_hero_files.*.mimes' => 'L\'image doit être au format JPG, PNG ou WebP',
        'images_hero_files.*.max' => 'L\'image ne doit pas dépasser 5 MB',
        'images_hero_files.*.dimensions' => 'L\'image doit faire au minimum 1280x720 pixels (recommandé: 1920x1080)',
    ]);

    $data = $validator->validated();

    // Traiter les programmes
    if (isset($data['programmes'])) {
        foreach ($data['programmes'] as &$programme) {
            $programme['est_public'] = $programme['est_public'] ?? 0;
            $programme['est_actif'] = $programme['est_actif'] ?? 0;
        }
    }

    $validator->setData(array_merge($validator->getData(), ['programmes' => $data['programmes'] ?? []]));

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
    public function processProgrammes($programmes)
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
