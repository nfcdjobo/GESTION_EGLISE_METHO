<?php

namespace App\Http\Controllers\Private\Web;

use Illuminate\View\View;
use App\Models\ParametreDon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\HistoriqueActionSurParametreDon;

class ParametreDonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ParametreDon::with(['creerPar', 'modifierPar', 'publierPar'])->withCount('dons');

        // Filtres
        if ($request->filled('type')) {
            $query->parType($request->type);
        }

        if ($request->filled('operateur')) {
            $query->parOperateur($request->operateur);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->boolean('statut'));
        }

        if ($request->filled('publier')) {
            $query->where('publier', $request->boolean('publier'));
        }

        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('operateur', 'like', "%{$search}%")
                  ->orWhere('numero_compte', 'like', "%{$search}%");
            });
        }

        $parametres = $query->latest()->paginate(15);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $parametres->items(),
                'pagination' => [
                    'current_page' => $parametres->currentPage(),
                    'last_page' => $parametres->lastPage(),
                    'per_page' => $parametres->perPage(),
                    'total' => $parametres->total(),
                    'has_more' => $parametres->hasMorePages()
                ]
            ]);
        }

        return view('components.private.parametresdons.index', compact('parametres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'types' => ParametreDon::TYPES,
                    'type_labels' => [
                        ParametreDon::TYPE_VIREMENT_BANCAIRE => 'Virement Bancaire',
                        ParametreDon::TYPE_CARTE_BANCAIRE => 'Carte Bancaire',
                        ParametreDon::TYPE_MOBILE_MONEY => 'Mobile Money',
                    ]
                ]
            ]);
        }

        return view('components.private.parametresdons.create');
    }

/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'operateur' => 'required|string|max:50',
        'type' => 'required|in:' . implode(',', ParametreDon::TYPES),
        'numero_compte' => 'required|string|max:100',
        'logo' => 'nullable|file|image|mimes:jpeg,png,jpg,svg,webp|max:2048', // Logo de l'opérateur
        'qrcode' => 'nullable|file|image|mimes:jpeg,png,jpg,svg|max:2048', // Image QR Code
        'statut' => 'boolean',
        'publier' => 'boolean',
    ], [
        'operateur.required' => 'L\'opérateur est obligatoire',
        'operateur.max' => 'L\'opérateur ne peut pas dépasser 50 caractères',
        'type.required' => 'Le type est obligatoire',
        'type.in' => 'Le type sélectionné n\'est pas valide',
        'numero_compte.required' => 'Le numéro de compte est obligatoire',
        'numero_compte.max' => 'Le numéro de compte ne peut pas dépasser 100 caractères',
        'logo.file' => 'Le logo doit être un fichier',
        'logo.image' => 'Le logo doit être une image',
        'logo.mimes' => 'Le logo doit être au format JPEG, PNG, JPG, SVG ou WebP',
        'logo.max' => 'Le logo ne peut pas dépasser 2 Mo',
        'qrcode.file' => 'Le QR Code doit être un fichier',
        'qrcode.image' => 'Le QR Code doit être une image',
        'qrcode.mimes' => 'Le QR Code doit être au format JPEG, PNG, JPG ou SVG',
        'qrcode.max' => 'Le QR Code ne peut pas dépasser 2 Mo',
    ]);

    if ($validator->fails()) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        return redirect()->back()
                       ->withErrors($validator)
                       ->withInput();
    }

    DB::beginTransaction();
    try {
        $data = [
            'operateur' => $request->operateur,
            'type' => $request->type,
            'numero_compte' => $request->numero_compte,
            'statut' => $request->boolean('statut', false),
            'publier' => $request->boolean('publier', false),
            'creer_par' => Auth::id(),
        ];

        // Upload du logo si fourni
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos-operateurs', 'public');
            $data['logo'] = $logoPath;
        }

        // Upload du QR Code si fourni
        if ($request->hasFile('qrcode')) {
            $qrcodePath = $request->file('qrcode')->store('qrcodes-parametres', 'public');
            $data['qrcode'] = $qrcodePath;
        }

        $parametre = ParametreDon::create($data);

        // Enregistrer dans l'historique
        HistoriqueActionSurParametreDon::enregistrerAction(
            $parametre->id,
            HistoriqueActionSurParametreDon::ACTION_AJOUT,
            Auth::id(),
            $request->only(['operateur', 'type', 'numero_compte']) + [
                'logo_uploade' => $request->hasFile('logo'),
                'qrcode_uploade' => $request->hasFile('qrcode')
            ]
        );

        DB::commit();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paramètre de don créé avec succès',
                'data' => $parametre->load(['creerPar'])
            ], 201);
        }

        return redirect()->route('private.parametresdons.index')
                       ->with('success', 'Paramètre de don créé avec succès');

    } catch (\Exception $e) {
        DB::rollback();

        // Supprimer les fichiers uploadés en cas d'erreur
        if (isset($logoPath) && Storage::disk('public')->exists($logoPath)) {
            Storage::disk('public')->delete($logoPath);
        }
        if (isset($qrcodePath) && Storage::disk('public')->exists($qrcodePath)) {
            Storage::disk('public')->delete($qrcodePath);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()
                       ->with('error', 'Erreur lors de la création')
                       ->withInput();
    }
}

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ParametreDon $parametreDon)
    {
        $parametreDon->load([
            'creerPar',
            'modifierPar',
            'publierPar',
            'dons' => function($query) {
                $query->latest()->take(10);
            },
            'historiques' => function($query) {
                $query->with('effectuerPar')->latest()->take(20);
            }
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $parametreDon
            ]);
        }

        return view('components.private.parametresdons.show', compact('parametreDon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, ParametreDon $parametreDon)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'parametre' => $parametreDon,
                    'types' => ParametreDon::TYPES,
                    'type_labels' => [
                        ParametreDon::TYPE_VIREMENT_BANCAIRE => 'Virement Bancaire',
                        ParametreDon::TYPE_CARTE_BANCAIRE => 'Carte Bancaire',
                        ParametreDon::TYPE_MOBILE_MONEY => 'Mobile Money',
                    ]
                ]
            ]);
        }

        return view('components.private.parametresdons.edit', compact('parametreDon'));
    }

/**
 * Update the specified resource in storage.
 */
public function update(Request $request, ParametreDon $parametreDon)
{
    $validator = Validator::make($request->all(), [
        'operateur' => 'required|string|max:50',
        'type' => 'required|in:' . implode(',', ParametreDon::TYPES),
        'numero_compte' => 'required|string|max:100',
        'logo' => 'nullable|file|image|mimes:jpeg,png,jpg,svg,webp|max:2048', // Logo de l'opérateur
        'qrcode' => 'nullable|file|image|mimes:jpeg,png,jpg,svg|max:2048', // Image QR Code
        'delete_logo' => 'nullable|in:0,1', // Pour supprimer le logo existant
        'delete_qrcode' => 'nullable|in:0,1', // Pour supprimer le QR Code existant
        'statut' => 'boolean',
        'publier' => 'boolean',
    ], [
        'operateur.required' => 'L\'opérateur est obligatoire',
        'operateur.max' => 'L\'opérateur ne peut pas dépasser 50 caractères',
        'type.required' => 'Le type est obligatoire',
        'type.in' => 'Le type sélectionné n\'est pas valide',
        'numero_compte.required' => 'Le numéro de compte est obligatoire',
        'numero_compte.max' => 'Le numéro de compte ne peut pas dépasser 100 caractères',
        'logo.file' => 'Le logo doit être un fichier',
        'logo.image' => 'Le logo doit être une image',
        'logo.mimes' => 'Le logo doit être au format JPEG, PNG, JPG, SVG ou WebP',
        'logo.max' => 'Le logo ne peut pas dépasser 2 Mo',
        'qrcode.file' => 'Le QR Code doit être un fichier',
        'qrcode.image' => 'Le QR Code doit être une image',
        'qrcode.mimes' => 'Le QR Code doit être au format JPEG, PNG, JPG ou SVG',
        'qrcode.max' => 'Le QR Code ne peut pas dépasser 2 Mo',
    ]);

    if ($validator->fails()) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreurs de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        return redirect()->back()
                       ->withErrors($validator)
                       ->withInput();
    }

    DB::beginTransaction();
    try {
        $anciennesDonnees = $parametreDon->toArray();

        $dataToUpdate = [
            'operateur' => $request->operateur,
            'type' => $request->type,
            'numero_compte' => $request->numero_compte,
            'statut' => $request->boolean('statut'),
            'publier' => $request->boolean('publier'),
            'modifier_par' => Auth::id(),
        ];

        $changementsEffectues = [
            'logo_modifie' => false,
            'logo_supprime' => false,
            'qrcode_modifie' => false,
            'qrcode_supprime' => false,
        ];

        // Gestion de la suppression du logo
        if ($request->input('delete_logo') == '1') {
            if ($parametreDon->logo && Storage::disk('public')->exists($parametreDon->logo)) {
                Storage::disk('public')->delete($parametreDon->logo);
            }
            $dataToUpdate['logo'] = null;
            $changementsEffectues['logo_supprime'] = true;
        }
        // Upload nouveau logo si fourni
        elseif ($request->hasFile('logo')) {
            // Supprimer l'ancien logo

            if ($parametreDon->logo && Storage::disk('public')->exists($parametreDon->logo)) {
                Storage::disk('public')->delete($parametreDon->logo);
            }
            $dataToUpdate['logo'] = $request->file('logo')->store('logos-operateurs', 'public');
            $changementsEffectues['logo_modifie'] = true;
        }

        // Gestion de la suppression du QR Code
        if ($request->input('delete_qrcode') == '1') {
            if ($parametreDon->qrcode && Storage::disk('public')->exists($parametreDon->qrcode)) {
                Storage::disk('public')->delete($parametreDon->qrcode);
            }
            $dataToUpdate['qrcode'] = null;
            $changementsEffectues['qrcode_supprime'] = true;
        }
        // Upload nouveau QR Code si fourni
        elseif ($request->hasFile('qrcode')) {
            // Supprimer l'ancien QR Code
            if ($parametreDon->qrcode && Storage::disk('public')->exists($parametreDon->qrcode)) {
                Storage::disk('public')->delete($parametreDon->qrcode);
            }
            $dataToUpdate['qrcode'] = $request->file('qrcode')->store('qrcodes-parametres', 'public');
            $changementsEffectues['qrcode_modifie'] = true;
        }

        $parametreDon->update($dataToUpdate);

        // Enregistrer dans l'historique
        HistoriqueActionSurParametreDon::enregistrerAction(
            $parametreDon->id,
            HistoriqueActionSurParametreDon::ACTION_MISE_A_JOUR,
            Auth::id(),
            [
                'anciennes_donnees' => $anciennesDonnees,
                'nouvelles_donnees' => $request->only(['operateur', 'type', 'numero_compte']) + $changementsEffectues
            ]
        );

        DB::commit();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Paramètre de don mis à jour avec succès',
                'data' => $parametreDon->fresh()->load(['modifierPar'])
            ]);
        }

        return redirect()->route('private.parametresdons.show', $parametreDon)
                       ->with('success', 'Paramètre de don mis à jour avec succès');

    } catch (\Exception $e) {
        DB::rollback();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()
                       ->with('error', 'Erreur lors de la mise à jour')
                       ->withInput();
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ParametreDon $parametreDon)
    {
        DB::beginTransaction();
        try {
            // Enregistrer dans l'historique avant suppression
            HistoriqueActionSurParametreDon::enregistrerAction(
                $parametreDon->id,
                HistoriqueActionSurParametreDon::ACTION_SUPPRESSION,
                Auth::id(),
                $parametreDon->toArray()
            );

            // Supprimer le fichier QR Code s'il existe
            if ($parametreDon->qrcode && Storage::disk('public')->exists($parametreDon->qrcode)) {
                Storage::disk('public')->delete($parametreDon->qrcode);
            }

            $parametreDon->update(['supprimer_par' => Auth::id()]);
            $parametreDon->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Paramètre de don supprimé avec succès'
                ]);
            }

            return redirect()->route('private.parametresdons.index')
                           ->with('success', 'Paramètre de don supprimé avec succès');

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                           ->with('error', 'Erreur lors de la suppression');
        }
    }

    /**
     * Publier ou dépublier un paramètre.
     */
    public function togglePublication(Request $request, ParametreDon $parametreDon)
    {
        DB::beginTransaction();
        try {
            $nouveauStatut = !$parametreDon->publier;

            $parametreDon->update([
                'publier' => $nouveauStatut,
                'publier_par' => $nouveauStatut ? Auth::id() : null,
            ]);

            // Enregistrer dans l'historique
            HistoriqueActionSurParametreDon::enregistrerAction(
                $parametreDon->id,
                HistoriqueActionSurParametreDon::ACTION_PUBLICATION,
                Auth::id(),
                ['statut_publication' => $nouveauStatut]
            );

            DB::commit();

            $message = $nouveauStatut ? 'Paramètre publié avec succès' : 'Publication annulée avec succès';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'publier' => $parametreDon->publier,
                        'publier_par' => $parametreDon->publierPar
                    ]
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la publication: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                           ->with('error', 'Erreur lors de la publication');
        }
    }

    /**
     * Activer ou désactiver un paramètre.
     */
    public function toggleStatut(Request $request, ParametreDon $parametreDon)
    {
        DB::beginTransaction();
        try {
            $nouveauStatut = !$parametreDon->statut;

            $parametreDon->update([
                'statut' => $nouveauStatut,
                'modifier_par' => Auth::id(),
            ]);

            // Enregistrer dans l'historique
            HistoriqueActionSurParametreDon::enregistrerAction(
                $parametreDon->id,
                HistoriqueActionSurParametreDon::ACTION_MISE_A_JOUR,
                Auth::id(),
                ['changement_statut' => $nouveauStatut]
            );

            DB::commit();

            $message = $nouveauStatut ? 'Paramètre activé avec succès' : 'Paramètre désactivé avec succès';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'statut' => $parametreDon->statut
                    ]
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du changement de statut: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                           ->with('error', 'Erreur lors du changement de statut');
        }
    }

    /**
     * Récupérer les paramètres publiés pour les dons publics.
     */
    public function parametresPublics(Request $request)
    {
        $parametres = ParametreDon::actif()
                                 ->publie()
                                 ->select(['id', 'operateur', 'type', 'numero_compte', 'qrcode'])
                                 ->get()
                                 ->map(function($parametre) {
                                     // Ajouter l'URL complète du QR Code si disponible
                                     if ($parametre->qrcode) {
                                         $parametre->qrcode_url = asset('storage/' . $parametre->qrcode);
                                     }
                                     return $parametre;
                                 });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $parametres
            ]);
        }

        return view('components.private.parametresdons.publics', compact('parametres'));
    }

    /**
     * Télécharger le QR Code d'un paramètre.
     */
    public function telechargerQrcode(Request $request, ParametreDon $parametreDon)
    {
        if (!$parametreDon->qrcode || !Storage::disk('public')->exists($parametreDon->qrcode)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code non trouvé'
                ], 404);
            }

            abort(404, 'QR Code non trouvé');
        }

        $filename = 'qrcode_' . $parametreDon->operateur . '_' . $parametreDon->id . '.' .
                   pathinfo($parametreDon->qrcode, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($parametreDon->qrcode, $filename);
    }

    /**
     * Afficher le QR Code d'un paramètre dans le navigateur.
     */
    public function afficherQrcode(Request $request, ParametreDon $parametreDon)
    {
        if (!$parametreDon->qrcode || !Storage::disk('public')->exists($parametreDon->qrcode)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code non trouvé'
                ], 404);
            }

            abort(404, 'QR Code non trouvé');
        }

        $file = Storage::disk('public')->get($parametreDon->qrcode);
        $mimeType = Storage::disk('public')->mimeType($parametreDon->qrcode);

        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'public, max-age=3600') // Cache 1 heure
            ->header('Content-Disposition', 'inline; filename="qrcode_' . $parametreDon->operateur . '.png"');
    }
}
