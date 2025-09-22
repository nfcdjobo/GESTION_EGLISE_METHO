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

            return view('components.parametres.index', compact('parametres'));

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

            return view('components.parametres.edit', compact('parametres'));

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

            // Gérer les horaires de culte (format JSON)
            if ($request->has('horaires_cultes')) {
                $data['horaires_cultes'] = $this->processHoraires($request->input('horaires_cultes'));
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

            return view('components.parametres.show', compact('parametres', 'infosCompletes'));

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
                'horaires_cultes' => $parametres->horaires_cultes,
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

    /**
     * Valider les données des paramètres
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateParametres(Request $request)
    {
        return Validator::make($request->all(), [
            'nom_eglise' => 'required|string|max:255',
            'telephone_1' => 'required|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'email_principal' => 'required|email|max:255',
            'email_secondaire' => 'nullable|email|max:255',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:255',
            'commune' => 'nullable|string|max:255',
            'pays' => 'required|string|max:255',
            'code_postal' => 'nullable|string|max:10',
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
        ]);
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
     * Traiter les horaires de culte
     *
     * @param mixed $horaires
     * @return array
     */
    private function processHoraires($horaires)
    {
        if (is_string($horaires)) {
            return json_decode($horaires, true) ?: [];
        }

        if (is_array($horaires)) {
            return $horaires;
        }

        return [];
    }
}
